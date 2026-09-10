<?php
/**
 * sale-check.php - read-only. Finds bills whose stored lines do not account
 * for the money on them, and shows any one bill in full.
 *
 * It CHANGES NOTHING. Every statement in this file is a SELECT. Safe to run on
 * the live site during shop hours.
 *
 * HOW TO USE
 *   1. Put this file next to index.php.
 *   2. Change the password on the line below.
 *   3. Open https://your-site/sale-check.php
 *   4. Delete the file afterwards.
 *
 * WHAT IT CHECKS, PER BILL
 *   lines total + gift vouchers sold          (what the bill is made of)
 *     - bill discount, + delivery charge      (bill-level adjustments)
 *     - anything already returned             (refunds come off the total)
 *     = what the grand total OUGHT to be
 *
 *   If the stored grand total is HIGHER than that, money on the bill is not
 *   backed by anything - lines are missing. That is the fault to look for.
 *
 *   A grand total LOWER than that is normal: loyalty points and promotions are
 *   taken off the bill and are not stored line by line.
 */

define('CHECK_PASSWORD', 'CHANGE-ME');

session_start();
date_default_timezone_set('Asia/Colombo');

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n) { return number_format((float)$n, 2); }

function db_settings()
{
    $file = __DIR__ . '/application/config/database.php';
    if (!is_readable($file)) { return array('error' => 'Cannot read application/config/database.php'); }
    if (!defined('BASEPATH'))    { define('BASEPATH', __DIR__ . '/system/'); }
    if (!defined('ENVIRONMENT')) { define('ENVIRONMENT', 'production'); }
    $db = array(); $active_group = 'default'; $query_builder = TRUE;
    try { include $file; }
    catch (Throwable $e) { return db_fallback($file); }
    catch (Exception $e) { return db_fallback($file); }
    $group = isset($active_group) ? $active_group : 'default';
    if (!isset($db[$group]) || empty($db[$group]['database'])) { return db_fallback($file); }
    return $db[$group];
}

function db_fallback($file)
{
    $txt = @file_get_contents($file);
    if ($txt === false) { return array('error' => 'Cannot read application/config/database.php'); }
    $out = array();
    foreach (array('hostname','username','password','database','port') as $k) {
        if (preg_match("/'".$k."'\s*=>\s*'?([^',]*)'?/", $txt, $m)) { $out[$k] = trim($m[1]); }
    }
    if (empty($out['database'])) { return array('error' => 'Could not find the database name.'); }
    return $out;
}

function db_connect(&$err)
{
    $cfg = db_settings();
    if (isset($cfg['error'])) { $err = $cfg['error']; return null; }
    $host = isset($cfg['hostname']) ? $cfg['hostname'] : 'localhost';
    $port = null;
    if (strpos($host, ':') !== false) { list($host, $port) = explode(':', $host, 2); }
    if (!$port && !empty($cfg['port'])) { $port = $cfg['port']; }
    mysqli_report(MYSQLI_REPORT_OFF);
    $c = $port ? @new mysqli($host, $cfg['username'], $cfg['password'], $cfg['database'], (int)$port)
               : @new mysqli($host, $cfg['username'], $cfg['password'], $cfg['database']);
    if ($c->connect_errno) { $err = 'Could not connect: ' . $c->connect_error; return null; }
    @$c->set_charset('utf8');
    return $c;
}

$locked = (CHECK_PASSWORD === 'CHANGE-ME' || CHECK_PASSWORD === '');
if (isset($_GET['logout'])) { $_SESSION['scheck_ok'] = false; header('Location: sale-check.php'); exit; }
if (!$locked && isset($_POST['password'])) {
    if (hash_equals(CHECK_PASSWORD, (string)$_POST['password'])) { $_SESSION['scheck_ok'] = true; }
    else { $loginError = 'Wrong password.'; }
}
$authed = !$locked && !empty($_SESSION['scheck_ok']);
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bill check</title>
<style>
  body  { font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif; background:#f4f6f8; color:#243; margin:0; padding:24px; }
  .wrap { max-width:1150px; margin:0 auto; }
  h1    { font-size:22px; margin:0 0 4px; }
  h2    { font-size:16px; margin:22px 0 8px; }
  .sub  { color:#678; margin:0 0 20px; font-size:14px; }
  .box  { background:#fff; border:1px solid #dde3e8; border-radius:6px; padding:16px 18px; margin-bottom:16px; }
  table { border-collapse:collapse; width:100%; font-size:13px; }
  th,td { border:1px solid #e3e8ec; padding:6px 8px; text-align:left; }
  th    { background:#f7f9fb; }
  td.r,th.r { text-align:right; }
  .ok   { color:#1b7a34; font-weight:600; }
  .bad  { color:#b3261e; font-weight:600; }
  .big  { font-size:20px; font-weight:700; }
  input[type=password], input[type=text] { padding:8px 10px; border:1px solid #cfd8e0; border-radius:4px; font-size:14px; }
  button{ padding:8px 14px; border:0; border-radius:4px; background:#0d6efd; color:#fff; font-size:14px; cursor:pointer; }
  code  { background:#eef2f5; padding:1px 5px; border-radius:3px; }
</style>
</head>
<body>
<div class="wrap">
<h1>Bill check</h1>
<p class="sub">Read-only. Nothing on this page changes anything in your database.</p>

<?php if ($locked): ?>
  <div class="box">
    <p>Open this file and change the password line, then reload:</p>
    <p><code>define('CHECK_PASSWORD', 'CHANGE-ME');</code></p>
  </div>

<?php elseif (!$authed): ?>
  <div class="box">
    <form method="post">
      <p>Password:</p>
      <p><input type="password" name="password" autofocus> <button type="submit">Open</button></p>
      <?php if (!empty($loginError)): ?><p class="bad"><?php echo h($loginError); ?></p><?php endif; ?>
    </form>
  </div>

<?php else:
  $err = null; $c = db_connect($err);
  if (!$c) {
      echo '<div class="box"><p class="bad">' . h($err) . '</p></div>';
  } else {
      $has = function ($t) use ($c) {
          $q = $c->query("SHOW TABLES LIKE '" . $c->real_escape_string($t) . "'");
          return ($q && $q->num_rows > 0);
      };
      $col = function ($t, $col) use ($c) {
          $q = $c->query("SHOW COLUMNS FROM `" . $c->real_escape_string($t) . "` LIKE '" . $c->real_escape_string($col) . "'");
          return ($q && $q->num_rows > 0);
      };

      $vouchers = $has('ezy_pos_gift_cards')
          ? "(SELECT COALESCE(SUM(gc_original_value),0) FROM ezy_pos_gift_cards WHERE gc_sold_sale_id = s.sale_id)" : "0";
      $returns  = $has('ezy_pos_returns')
          ? "(SELECT COALESCE(SUM(ret_net_amount),0) FROM ezy_pos_returns WHERE ret_sale_id = s.sale_id AND ret_status = 1)" : "0";
      $dtype    = $col('ezy_pos_sale', 'sale_discount_type') ? "s.sale_discount_type" : "'percentage'";
      $delivery = $col('ezy_pos_sale', 'sale_delivery_charge') ? "COALESCE(s.sale_delivery_charge,0)" : "0";
      $billno   = $col('ezy_pos_sale', 'sale_bill_no') ? "s.sale_bill_no" : "''";

      // what the bill ought to come to, from what is actually stored
      $expected = "ROUND(
            (CASE WHEN $dtype = 'flat'
                  THEN (COALESCE(li.t,0) + $vouchers) - COALESCE(s.sale_discount,0)
                  ELSE (COALESCE(li.t,0) + $vouchers) * (100 - COALESCE(s.sale_discount,0)) / 100
             END) + $delivery - $returns, 2)";

      $base = "FROM ezy_pos_sale s
               LEFT JOIN (SELECT saleitem_sale_id sid, COUNT(*) n, SUM(saleitem_total) t
                          FROM ezy_pos_sale_item GROUP BY saleitem_sale_id) li
                      ON li.sid = s.sale_id
               WHERE s.sale_status = '1'";

      $one = isset($_POST['sale_id']) ? trim($_POST['sale_id']) : '';
      ?>

      <div class="box">
        <form method="post">
          <p>Look up one bill (type the bill number or the internal id):</p>
          <p><input type="text" name="sale_id" value="<?php echo h($one); ?>" placeholder="881">
             <button type="submit">Show it</button></p>
        </form>
      </div>

      <?php if ($one !== ''):
          $idEsc = $c->real_escape_string($one);
          $q = $c->query("SELECT s.*, $billno AS bno, COALESCE(li.n,0) AS lines_n, COALESCE(li.t,0) AS lines_t,
                                 $vouchers AS vouchers, $returns AS returned, $expected AS expected
                          $base AND (s.sale_id = '$idEsc' " . ($col('ezy_pos_sale','sale_bill_no') ? "OR s.sale_bill_no = '$idEsc'" : "") . ") LIMIT 1");
          $r = $q ? $q->fetch_assoc() : null;
          if (!$r): ?>
            <div class="box"><p class="bad">No bill found for "<?php echo h($one); ?>".</p></div>
          <?php else:
            $short = round((float)$r['sale_grandtotal'] - (float)$r['expected'], 2); ?>
            <div class="box">
              <h2>Bill <?php echo h($r['bno'] ? $r['bno'] : $r['sale_id']); ?>
                  (internal id <?php echo h($r['sale_id']); ?>)</h2>
              <table>
                <tr><th>Date</th><td><?php echo h($r['sale_date']); ?></td>
                    <th>Created</th><td><?php echo h($r['sale_createdat']); ?></td></tr>
                <tr><th>Lines stored</th><td><?php echo h($r['lines_n']); ?></td>
                    <th>Value of those lines</th><td class="r"><?php echo money($r['lines_t']); ?></td></tr>
                <tr><th>Gift vouchers on it</th><td class="r"><?php echo money($r['vouchers']); ?></td>
                    <th>Already returned</th><td class="r"><?php echo money($r['returned']); ?></td></tr>
                <tr><th>Bill discount</th><td><?php echo money($r['sale_discount']); ?>
                        <?php echo isset($r['sale_discount_type']) ? h($r['sale_discount_type']) : ''; ?></td>
                    <th>Sub total stored</th><td class="r"><?php echo money($r['sale_subtotal']); ?></td></tr>
                <tr><th>Grand total stored</th><td class="r big"><?php echo money($r['sale_grandtotal']); ?></td>
                    <th>What the stored lines account for</th><td class="r big"><?php echo money($r['expected']); ?></td></tr>
                <tr><th>Difference</th>
                    <td colspan="3" class="<?php echo $short > 0.05 ? 'bad' : 'ok'; ?>">
                      <?php if ($short > 0.05): ?>
                        <?php echo money($short); ?> of this bill is not backed by any stored line.
                        Lines are missing from it.
                      <?php else: ?>
                        The bill adds up.
                      <?php endif; ?>
                    </td></tr>
              </table>

              <h2>The lines that ARE stored</h2>
              <table>
                <tr><th>#</th><th>Code</th><th>Item</th><th class="r">Price</th><th class="r">Qty</th>
                    <th class="r">Disc</th><th class="r">Line total</th><th>Saved at</th></tr>
                <?php
                $li = $c->query("SELECT si.*, i.itm_code, i.itm_name
                                 FROM ezy_pos_sale_item si
                                 LEFT JOIN ezy_pos_items i ON i.itm_id = si.saleitem_item_id
                                 WHERE si.saleitem_sale_id = '" . $c->real_escape_string($r['sale_id']) . "'
                                 ORDER BY si.saleitem_id");
                $n = 0;
                while ($li && $x = $li->fetch_assoc()): $n++; ?>
                  <tr>
                    <td><?php echo $n; ?></td>
                    <td><?php echo h($x['itm_code']); ?></td>
                    <td><?php echo h($x['itm_name']); ?></td>
                    <td class="r"><?php echo money($x['saleitem_price']); ?></td>
                    <td class="r"><?php echo money($x['saleitem_quantity']); ?></td>
                    <td class="r"><?php echo money($x['saleitem_discount']); ?></td>
                    <td class="r"><?php echo money($x['saleitem_total']); ?></td>
                    <td><?php echo h($x['saleitem_ctreatedat']); ?></td>
                  </tr>
                <?php endwhile; ?>
              </table>
              <p class="sub">The "Saved at" times tell you how the bill was written. Lines
                 that stop abruptly - all within a second or two of each other and then
                 nothing - are the signature of a save that was cut off part way through.</p>
            </div>
          <?php endif;
      endif; ?>

      <?php
      // ---- the sweep ----
      $q = $c->query("SELECT s.sale_id, $billno AS bno, s.sale_date, s.sale_grandtotal,
                             COALESCE(li.n,0) AS lines_n, COALESCE(li.t,0) AS lines_t,
                             $vouchers AS vouchers, $returns AS returned, $expected AS expected
                      $base
                      HAVING s.sale_grandtotal - expected > 0.05
                      ORDER BY (s.sale_grandtotal - expected) DESC
                      LIMIT 300");
      $bad = array(); $sumShort = 0;
      while ($q && $x = $q->fetch_assoc()) {
          $x['short'] = round((float)$x['sale_grandtotal'] - (float)$x['expected'], 2);
          $sumShort += $x['short'];
          $bad[] = $x;
      }
      ?>
      <div class="box">
        <h2>Every bill whose stored lines do not account for its total</h2>
        <?php if (count($bad) === 0): ?>
          <p class="ok">None. Every bill in the database adds up.</p>
        <?php else: ?>
          <p><span class="big"><?php echo count($bad); ?></span> bills, missing
             <strong>LKR <?php echo money($sumShort); ?></strong> of lines between them.</p>
          <table>
            <tr><th>Bill</th><th>Internal id</th><th>Date</th><th class="r">Lines</th>
                <th class="r">Lines total</th><th class="r">Grand total</th><th class="r">Not accounted for</th></tr>
            <?php foreach ($bad as $x): ?>
              <tr>
                <td><?php echo h($x['bno'] ? $x['bno'] : $x['sale_id']); ?></td>
                <td><?php echo h($x['sale_id']); ?></td>
                <td><?php echo h($x['sale_date']); ?></td>
                <td class="r"><?php echo h($x['lines_n']); ?></td>
                <td class="r"><?php echo money($x['lines_t']); ?></td>
                <td class="r"><?php echo money($x['sale_grandtotal']); ?></td>
                <td class="r bad"><?php echo money($x['short']); ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
          <p class="sub">Loyalty and promotion discounts are taken off a bill without being
             stored line by line, so a bill worth LESS than its lines is normal and is not
             listed here. Only bills worth MORE than their lines appear.</p>
        <?php endif; ?>
      </div>

      <div class="box">
        <p>Finished? Delete this file from the server - it is only a diagnostic.
           <a href="?logout=1">Log out</a></p>
      </div>
  <?php }
  if ($c) { $c->close(); }
endif; ?>
</div>
</body>
</html>
