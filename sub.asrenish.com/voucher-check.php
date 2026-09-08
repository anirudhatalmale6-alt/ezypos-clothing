<?php
/**
 * voucher-check.php - read-only. Answers one question:
 *
 *      "Is the money from my gift voucher sales recorded, and if it is,
 *       why is it not showing in the Cash Flow report?"
 *
 * It CHANGES NOTHING. Every statement in this file is a SELECT. You can run it
 * on the live site during shop hours and put it back down afterwards.
 *
 * HOW TO USE
 *   1. Put this file next to index.php in your site folder.
 *   2. Change the password on the line below.
 *   3. Open https://your-site/voucher-check.php and type it in.
 *   4. Read the verdict column, then delete the file.
 *
 * WHAT IT IS LOOKING FOR
 *   A bill with no stock line on it - a gift voucher sold on its own is the
 *   usual case - used to be saved with an empty date, which MySQL stores as
 *   0000-00-00. Every report asks for "date between two dates", and no range
 *   anyone can pick contains 0000-00-00, so those bills are invisible in the
 *   Cash Flow report, Today's Summary and Payments Received. The Sales Report
 *   reads a different column, which is why the same sale shows up there and
 *   nowhere else.
 */

define('CHECK_PASSWORD', 'CHANGE-ME');

session_start();
date_default_timezone_set('Asia/Colombo');

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n) { return number_format((float)$n, 2); }

/* ------------------------------------------------------------ db settings */
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

/* ----------------------------------------------------------------- login */
$locked = (CHECK_PASSWORD === 'CHANGE-ME' || CHECK_PASSWORD === '');
if (isset($_GET['logout'])) { $_SESSION['vcheck_ok'] = false; header('Location: voucher-check.php'); exit; }
if (!$locked && isset($_POST['password'])) {
    if (hash_equals(CHECK_PASSWORD, (string)$_POST['password'])) { $_SESSION['vcheck_ok'] = true; }
    else { $loginError = 'Wrong password.'; }
}
$authed = !$locked && !empty($_SESSION['vcheck_ok']);
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gift voucher check</title>
<style>
  body  { font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif; background:#f4f6f8; color:#243; margin:0; padding:24px; }
  .wrap { max-width:1100px; margin:0 auto; }
  h1    { font-size:22px; margin:0 0 4px; }
  .sub  { color:#678; margin:0 0 20px; font-size:14px; }
  .box  { background:#fff; border:1px solid #dde3e8; border-radius:6px; padding:16px 18px; margin-bottom:16px; }
  table { border-collapse:collapse; width:100%; font-size:13px; }
  th,td { border:1px solid #e3e8ec; padding:6px 8px; text-align:left; }
  th    { background:#f7f9fb; }
  td.r  { text-align:right; }
  .ok   { color:#1b7a34; font-weight:600; }
  .bad  { color:#b3261e; font-weight:600; }
  .warn { color:#8a6d00; font-weight:600; }
  .big  { font-size:20px; font-weight:700; }
  input[type=password] { padding:8px 10px; border:1px solid #cfd8e0; border-radius:4px; font-size:14px; }
  button{ padding:8px 14px; border:0; border-radius:4px; background:#0d6efd; color:#fff; font-size:14px; cursor:pointer; }
  code  { background:#eef2f5; padding:1px 5px; border-radius:3px; }
</style>
</head>
<body>
<div class="wrap">
<h1>Gift voucher check</h1>
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

      if (!$has('ezy_pos_gift_cards')) {
          echo '<div class="box"><p class="warn">There is no gift voucher table in this database yet.</p></div>';
      } else {
          $chq  = $has('ezy_pos_cus_cheque')
                ? "(SELECT COALESCE(SUM(cus_cheque_amount),0) FROM ezy_pos_cus_cheque WHERE cus_cheque_saleid = s.sale_id)" : "0";
          $card = $has('ezy_pos_sale_payments')
                ? "(SELECT COALESCE(SUM(sp_amount),0) FROM ezy_pos_sale_payments WHERE sp_sale_id = s.sale_id)" : "0";

          $sql = "SELECT s.sale_id, s.sale_date, s.sale_createdat, s.sale_location, s.sale_grandtotal,
                         COUNT(gc.gc_id) AS cards,
                         COALESCE(SUM(gc.gc_original_value),0) AS voucher_value,
                         GROUP_CONCAT(gc.gc_card_number ORDER BY gc.gc_id SEPARATOR ', ') AS card_numbers,
                         (SELECT COALESCE(SUM(cus_pay_cash),0) FROM ezy_pos_cus_payment WHERE cus_pay_saleid = s.sale_id) AS cash,
                         (SELECT COALESCE(SUM(cus_pay_credit),0) FROM ezy_pos_cus_payment WHERE cus_pay_saleid = s.sale_id) AS credit,
                         $chq AS cheque,
                         $card AS card,
                         st.store_name
                  FROM ezy_pos_gift_cards gc
                  INNER JOIN ezy_pos_sale s ON s.sale_id = gc.gc_sold_sale_id
                  LEFT JOIN ezy_pos_stores st ON st.store_id = s.sale_location
                  WHERE gc.gc_sold_sale_id IS NOT NULL
                  GROUP BY s.sale_id
                  ORDER BY s.sale_id DESC
                  LIMIT 500";
          $res = $c->query($sql);

          $rows = array();
          $nBadDate = 0; $nNoMoney = 0; $nNoBranch = 0; $sumVoucher = 0; $sumCollected = 0;
          if ($res) {
              while ($r = $res->fetch_assoc()) {
                  $r['collected'] = (float)$r['cash'] + (float)$r['cheque'] + (float)$r['card'];
                  $badDate  = ($r['sale_date'] === null || $r['sale_date'] === '0000-00-00' || $r['sale_date'] < '2000-01-01');
                  $noMoney  = ($r['collected'] <= 0.004 && (float)$r['credit'] <= 0.004);
                  $noBranch = (intval($r['sale_location']) <= 0);
                  $r['_badDate'] = $badDate; $r['_noMoney'] = $noMoney; $r['_noBranch'] = $noBranch;
                  if ($badDate)  { $nBadDate++; }
                  if ($noMoney)  { $nNoMoney++; }
                  if ($noBranch) { $nNoBranch++; }
                  $sumVoucher   += (float)$r['voucher_value'];
                  $sumCollected += $r['collected'];
                  $rows[] = $r;
              }
          }
          ?>
          <div class="box">
            <p><span class="big"><?php echo count($rows); ?></span> bills have sold a gift voucher.</p>
            <p>Voucher face value on them: <strong>LKR <?php echo money($sumVoucher); ?></strong><br>
               Money recorded against those bills: <strong>LKR <?php echo money($sumCollected); ?></strong></p>
            <?php if ($nBadDate > 0): ?>
              <p class="bad">
                <?php echo $nBadDate; ?> of them have NO DATE (0000-00-00).
                Those are invisible in the Cash Flow report, Today's Summary and Payments
                Received - for every date range, not just some. Step 7 in migrate.php puts
                the date back.
              </p>
            <?php else: ?>
              <p class="ok">Every one of them has a real date, so no date range can miss them.</p>
            <?php endif; ?>
            <?php if ($nNoBranch > 0): ?>
              <p class="warn"><?php echo $nNoBranch; ?> have no branch on them - they will not
                appear when the Cash Flow report is filtered to one branch.</p>
            <?php endif; ?>
            <?php if ($nNoMoney > 0): ?>
              <p class="warn"><?php echo $nNoMoney; ?> have no money recorded against them at all
                (no cash, no cheque, no card, no credit). Those are bills where the payment was
                never saved - tell me the bill numbers and I will look at them.</p>
            <?php endif; ?>
          </div>

          <div class="box">
            <table>
              <tr>
                <th>Bill id</th><th>Date stored</th><th>Created</th><th>Branch</th>
                <th>Cards</th><th class="r">Voucher value</th><th class="r">Cash</th>
                <th class="r">Cheque</th><th class="r">Card</th><th class="r">Credit</th>
                <th>Will it show in Cash Flow?</th>
              </tr>
              <?php foreach ($rows as $r):
                  if ($r['_badDate'])      { $cls = 'bad';  $verdict = 'NO - the bill has no date'; }
                  elseif ($r['_noMoney'])  { $cls = 'warn'; $verdict = 'No money was recorded on this bill'; }
                  elseif ($r['_noBranch']) { $cls = 'warn'; $verdict = 'Yes, but not under a branch filter'; }
                  else                     { $cls = 'ok';   $verdict = 'Yes'; }
              ?>
              <tr>
                <td><?php echo h($r['sale_id']); ?></td>
                <td class="<?php echo $r['_badDate'] ? 'bad' : ''; ?>"><?php echo h($r['sale_date']); ?></td>
                <td><?php echo h($r['sale_createdat']); ?></td>
                <td><?php echo h($r['store_name'] !== null ? $r['store_name'] : '-'); ?></td>
                <td><small><?php echo h($r['card_numbers']); ?></small></td>
                <td class="r"><?php echo money($r['voucher_value']); ?></td>
                <td class="r"><?php echo money($r['cash']); ?></td>
                <td class="r"><?php echo money($r['cheque']); ?></td>
                <td class="r"><?php echo money($r['card']); ?></td>
                <td class="r"><?php echo money($r['credit']); ?></td>
                <td class="<?php echo $cls; ?>"><?php echo h($verdict); ?></td>
              </tr>
              <?php endforeach; ?>
            </table>
            <?php if (count($rows) === 0): ?>
              <p class="warn">No gift voucher has ever been marked as sold on a bill in this
                 database. If you have been selling them, the card numbers may not have been
                 entered on the sale - send me one bill number and I will check it.</p>
            <?php endif; ?>
          </div>

          <div class="box">
            <p>Finished? Delete this file from the server - it is only a diagnostic.
               <a href="?logout=1">Log out</a></p>
          </div>
      <?php }
      $c->close();
  }
endif; ?>
</div>
</body>
</html>
