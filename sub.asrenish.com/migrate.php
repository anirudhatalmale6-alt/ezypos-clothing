<?php
/**
 * EzyPOS - database update tool (browser version)
 * ---------------------------------------------------------------------------
 * For hosting without SSH. Upload this file next to index.php and open it in
 * your browser:   https://your-site/migrate.php
 *
 * It reads the database login from application/config/database.php, so there
 * is nothing to type in.
 *
 * STEP 1 - set your own password on the line below (anything you like).
 * STEP 2 - open the page, take a backup, run the updates.
 * STEP 3 - press "Delete this tool" at the bottom when you are done.
 *
 * Running the same update twice does no harm: anything that is already in
 * place is reported as "already there" and skipped.
 */

// ---------------------------------------------------------------------------
// CHANGE THIS. Pick any password. The page will not open until you do.
// ---------------------------------------------------------------------------
define('MIGRATE_PASSWORD', 'CHANGE-ME');
// ---------------------------------------------------------------------------

@set_time_limit(0);
@ini_set('memory_limit', '512M');
session_start();

define('MIGRATE_DIR', __DIR__ . '/application/migrations');

/* ------------------------------------------------------------------ helpers */

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/** Read hostname/username/password/database out of the CodeIgniter config. */
function db_settings()
{
    $file = __DIR__ . '/application/config/database.php';
    if (!is_readable($file)) {
        return array('error' => 'Cannot read application/config/database.php');
    }
    // database.php expects to be loaded by CodeIgniter, so give it the two
    // constants it relies on before including it.
    if (!defined('BASEPATH'))    { define('BASEPATH', __DIR__ . '/system/'); }
    if (!defined('ENVIRONMENT')) { define('ENVIRONMENT', 'production'); }

    $db = array(); $active_group = 'default'; $query_builder = TRUE;
    try {
        include $file;
    } catch (Throwable $e) {
        return db_settings_fallback($file);
    } catch (Exception $e) {
        return db_settings_fallback($file);
    }

    $group = isset($active_group) ? $active_group : 'default';
    if (!isset($db[$group]) || empty($db[$group]['database'])) {
        return db_settings_fallback($file);
    }
    return $db[$group];
}

/** If including the config fails, read the four values out of the text. */
function db_settings_fallback($file)
{
    $txt = @file_get_contents($file);
    if ($txt === false) {
        return array('error' => 'Cannot read application/config/database.php');
    }
    $out = array();
    foreach (array('hostname', 'username', 'password', 'database', 'char_set') as $key) {
        if (preg_match("/'" . $key . "'\s*=>\s*'([^']*)'/", $txt, $m)) { $out[$key] = $m[1]; }
    }
    if (empty($out['database'])) {
        return array('error' => 'Could not find the database name in application/config/database.php');
    }
    return $out;
}

function db_connect(&$err)
{
    $cfg = db_settings();
    if (isset($cfg['error'])) { $err = $cfg['error']; return null; }

    $host = isset($cfg['hostname']) ? $cfg['hostname'] : 'localhost';
    $port = null;
    if (strpos($host, ':') !== false) { list($host, $port) = explode(':', $host, 2); }

    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = $port ? @new mysqli($host, $cfg['username'], $cfg['password'], $cfg['database'], (int)$port)
                  : @new mysqli($host, $cfg['username'], $cfg['password'], $cfg['database']);
    if ($conn->connect_errno) {
        $err = 'Could not connect to the database: ' . $conn->connect_error
             . ' (check the login details in application/config/database.php)';
        return null;
    }
    $charset = !empty($cfg['char_set']) ? $cfg['char_set'] : 'utf8';
    @$conn->set_charset($charset);
    return $conn;
}

/**
 * Split a .sql file into single statements.
 * Comments are dropped, and semicolons inside quotes are left alone.
 */
function split_sql($sql)
{
    $out = array(); $buf = '';
    $n = strlen($sql); $i = 0;
    $inS = false; $inD = false; $inB = false;

    while ($i < $n) {
        $c  = $sql[$i];
        $c2 = substr($sql, $i, 2);

        if (!$inS && !$inD && !$inB) {
            // -- comment (must be followed by whitespace, as MySQL requires)
            if ($c2 === '--') {
                $next = ($i + 2 < $n) ? $sql[$i + 2] : "\n";
                if ($next === ' ' || $next === "\t" || $next === "\n" || $next === "\r") {
                    while ($i < $n && $sql[$i] !== "\n") { $i++; }
                    $buf .= "\n";
                    continue;
                }
            }
            if ($c === '#') {
                while ($i < $n && $sql[$i] !== "\n") { $i++; }
                $buf .= "\n";
                continue;
            }
            if ($c2 === '/*') {
                $end = strpos($sql, '*/', $i + 2);
                $i = ($end === false) ? $n : $end + 2;
                $buf .= ' ';
                continue;
            }
        }

        if ($c === "'"  && !$inD && !$inB) { $inS = !$inS; }
        elseif ($c === '"'  && !$inS && !$inB) { $inD = !$inD; }
        elseif ($c === '`'  && !$inS && !$inD) { $inB = !$inB; }
        elseif ($c === '\\' && ($inS || $inD)) { $buf .= $c; $i++; if ($i < $n) { $buf .= $sql[$i]; $i++; } continue; }

        if ($c === ';' && !$inS && !$inD && !$inB) {
            $t = trim($buf);
            if ($t !== '') { $out[] = $t; }
            $buf = ''; $i++;
            continue;
        }

        $buf .= $c; $i++;
    }
    $t = trim($buf);
    if ($t !== '') { $out[] = $t; }
    return $out;
}

/** Errors that simply mean "this part is already in place". */
function is_already_applied($errno)
{
    // 1050 table exists, 1060 column exists, 1061 index exists,
    // 1091 nothing to drop, 1826 duplicate foreign key
    return in_array((int)$errno, array(1050, 1060, 1061, 1091, 1826), true);
}

function first_words($sql, $count = 9)
{
    $clean = preg_replace('/\s+/', ' ', trim($sql));
    $words = explode(' ', $clean);
    $short = implode(' ', array_slice($words, 0, $count));
    return (count($words) > $count) ? $short . ' ...' : $short;
}

function is_select($sql)
{
    return (bool)preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $sql);
}

/** Run a list of statements and print a row per statement. */
function run_statements($conn, $statements, $title)
{
    $ok = 0; $skipped = 0; $failed = 0;

    echo '<div class="box"><h3>' . h($title) . '</h3>';
    echo '<table class="log"><tr><th style="width:38px">#</th><th>Statement</th><th style="width:210px">Result</th></tr>';

    $no = 0;
    foreach ($statements as $sql) {
        $no++;
        echo '<tr><td>' . $no . '</td><td><code>' . h(first_words($sql)) . '</code>';

        $res = @$conn->query($sql);

        if ($res === false) {
            if (is_already_applied($conn->errno)) {
                $skipped++;
                echo '</td><td class="skip">already there - skipped</td></tr>';
            } else {
                $failed++;
                echo '<div class="errmsg">' . h($conn->error) . '</div>';
                echo '</td><td class="bad">FAILED</td></tr>';
            }
            continue;
        }

        $ok++;

        if ($res instanceof mysqli_result) {
            $rows = array(); $limit = 200;
            while (($r = $res->fetch_assoc()) && count($rows) < $limit) { $rows[] = $r; }
            $more = $res->num_rows > $limit;
            $total = $res->num_rows;
            $res->free();

            if ($total === 0) {
                echo '<div class="none">Nothing found - nothing to correct here.</div>';
            } else {
                echo '<div class="result"><table class="data"><tr>';
                foreach (array_keys($rows[0]) as $col) { echo '<th>' . h($col) . '</th>'; }
                echo '</tr>';
                foreach ($rows as $r) {
                    echo '<tr>';
                    foreach ($r as $v) { echo '<td>' . h($v === null ? 'NULL' : $v) . '</td>'; }
                    echo '</tr>';
                }
                echo '</table>';
                if ($more) { echo '<div class="none">Showing the first ' . $limit . ' of ' . $total . ' rows.</div>'; }
                echo '</div>';
            }
            echo '</td><td class="info">' . $total . ' row(s) listed</td></tr>';
        } else {
            $aff = $conn->affected_rows;
            echo '</td><td class="good">done' . ($aff > 0 ? ' - ' . $aff . ' row(s)' : '') . '</td></tr>';
        }
    }

    echo '</table>';
    echo '<p class="summary">' . $ok . ' ran, ' . $skipped . ' already there, '
       . '<span class="' . ($failed ? 'bad' : '') . '">' . $failed . ' failed</span>.</p>';
    if ($failed) {
        echo '<p class="warn">Something failed. Please send me a screenshot of this page '
           . 'before doing anything else - do not restore the backup yet.</p>';
    }
    echo '</div>';

    return array($ok, $skipped, $failed);
}

/** Small checks so you can see what is and is not in place. */
function status_checks($conn)
{
    $cfg = db_settings();
    $dbname = $conn->real_escape_string($cfg['database']);

    $hasCol = function ($table, $col) use ($conn, $dbname) {
        $q = $conn->query("SELECT 1 FROM information_schema.COLUMNS
                           WHERE TABLE_SCHEMA='$dbname' AND TABLE_NAME='"
                           . $conn->real_escape_string($table) . "' AND COLUMN_NAME='"
                           . $conn->real_escape_string($col) . "' LIMIT 1");
        return ($q && $q->num_rows > 0);
    };
    $hasTable = function ($table) use ($conn, $dbname) {
        $q = $conn->query("SELECT 1 FROM information_schema.TABLES
                           WHERE TABLE_SCHEMA='$dbname' AND TABLE_NAME='"
                           . $conn->real_escape_string($table) . "' LIMIT 1");
        return ($q && $q->num_rows > 0);
    };
    $count = function ($sql) use ($conn) {
        $q = @$conn->query($sql);
        if (!$q) { return '-'; }
        $r = $q->fetch_row();
        return $r ? (int)$r[0] : 0;
    };

    $rows = array();
    $rows[] = array('Per-store bill numbers (sale_bill_no)', $hasCol('ezy_pos_sale', 'sale_bill_no'));
    $rows[] = array('Bill counter table (ezy_pos_bill_counters)', $hasTable('ezy_pos_bill_counters'));
    $rows[] = array('Store letter column (store_bill_code)', $hasCol('ezy_pos_stores', 'store_bill_code'));
    $rows[] = array('Store credit on returns (ret_refund_mode)', $hasCol('ezy_pos_returns', 'ret_refund_mode'));
    $rows[] = array('Cash flow flag on refunds (rp_in_cashflow)', $hasCol('ezy_pos_return_payments', 'rp_in_cashflow'));
    $rows[] = array('New page permissions (priv_gatepass)', $hasCol('ezy_pos_privileges', 'priv_gatepass'));
    $rows[] = array('Branch on returns (ret_store_id)', $hasCol('ezy_pos_returns', 'ret_store_id'));
    $rows[] = array('Return tracking on sales (sale_return_status)', $hasCol('ezy_pos_sale', 'sale_return_status'));
    $rows[] = array('Discount on exchanges (ret_exchange_discount)', $hasCol('ezy_pos_returns', 'ret_exchange_discount'));
    $rows[] = array('Discount type on exchange lines (ei_discount_type)', $hasCol('ezy_pos_exchange_items', 'ei_discount_type'));
    $rows[] = array('Expense subcategories (expencat_parent_id)', $hasCol('ezy_pos_expense_cat', 'expencat_parent_id'));

    echo '<div class="box"><h3>What is already in place</h3><table class="data">';
    foreach ($rows as $r) {
        echo '<tr><td>' . h($r[0]) . '</td><td class="' . ($r[1] ? 'good' : 'skip') . '">'
           . ($r[1] ? 'in place' : 'not yet') . '</td></tr>';
    }
    echo '</table>';

    if ($hasTable('ezy_pos_returns')) {
        $badRefunds = $count("SELECT COUNT(*) FROM (
                SELECT r.ret_id
                FROM ezy_pos_returns r
                LEFT JOIN ezy_pos_return_items ri ON ri.ri_return_id = r.ret_id
                GROUP BY r.ret_id, r.ret_refund_amount
                HAVING r.ret_refund_amount <> COALESCE(SUM(ri.ri_total),0)) z");
        $noBranch  = $count("SELECT COUNT(*) FROM ezy_pos_returns WHERE ret_store_id IS NULL OR ret_store_id = 0");
        $noSaleBr  = $count("SELECT COUNT(*) FROM ezy_pos_sale WHERE sale_location IS NULL OR sale_location = 0");

        echo '<h3 style="margin-top:18px">Records still needing repair</h3><table class="data">';
        echo '<tr><td>Returns with a wrong refund total</td><td class="' . ($badRefunds ? 'skip' : 'good') . '">' . h($badRefunds) . '</td></tr>';
        echo '<tr><td>Returns with no branch on them</td><td class="' . ($noBranch ? 'skip' : 'good') . '">' . h($noBranch) . '</td></tr>';
        echo '<tr><td>Bills with no branch on them (voucher sales)</td><td class="' . ($noSaleBr ? 'skip' : 'good') . '">' . h($noSaleBr) . '</td></tr>';
        echo '</table><p class="note">Zeros here after step 3 mean the repair worked.</p>';
    }
    echo '</div>';
}

/* -------------------------------------------------------------------- login */

$locked = (MIGRATE_PASSWORD === 'CHANGE-ME' || MIGRATE_PASSWORD === '');

if (isset($_GET['logout'])) { $_SESSION['migrate_ok'] = false; header('Location: migrate.php'); exit; }

if (!$locked && isset($_POST['password'])) {
    if (hash_equals(MIGRATE_PASSWORD, (string)$_POST['password'])) {
        $_SESSION['migrate_ok'] = true;
    } else {
        $loginError = 'Wrong password.';
    }
}
$authed = !$locked && !empty($_SESSION['migrate_ok']);

/* --------------------------------------------------------------------- page */
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>EzyPOS - Database Update</title>
<style>
  body   { font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif; background:#f4f6f8; color:#243; margin:0; padding:24px; }
  .wrap  { max-width:1000px; margin:0 auto; }
  h1     { font-size:22px; margin:0 0 4px; }
  h2     { font-size:16px; margin:26px 0 8px; }
  h3     { font-size:15px; margin:0 0 10px; }
  .sub   { color:#678; margin:0 0 20px; font-size:14px; }
  .box   { background:#fff; border:1px solid #dde3e8; border-radius:6px; padding:16px 18px; margin-bottom:16px; }
  .step  { border-left:4px solid #2d7ff9; }
  table  { border-collapse:collapse; width:100%; font-size:13px; }
  .log td, .log th, .data td, .data th { border:1px solid #e3e8ec; padding:6px 8px; text-align:left; vertical-align:top; }
  .log th, .data th { background:#f7f9fb; font-weight:600; }
  code   { font-family:Consolas,Monaco,monospace; font-size:12px; color:#345; }
  .good  { color:#1a7f37; font-weight:600; }
  .skip  { color:#8a6d1f; }
  .bad   { color:#c0392b; font-weight:600; }
  .info  { color:#345; }
  .errmsg{ color:#c0392b; font-size:12px; margin-top:5px; }
  .result{ margin:8px 0 2px; max-height:340px; overflow:auto; }
  .none  { color:#789; font-size:12px; margin-top:5px; }
  .summary{ font-size:13px; margin:10px 0 0; }
  .warn  { background:#fff4f2; border:1px solid #f0c0b6; color:#a03020; padding:10px 12px; border-radius:4px; font-size:13px; }
  .tip   { background:#f2f8ff; border:1px solid #cfe2f7; padding:10px 12px; border-radius:4px; font-size:13px; }
  .note  { color:#678; font-size:12px; }
  button { background:#2d7ff9; color:#fff; border:0; border-radius:4px; padding:9px 16px; font-size:14px; cursor:pointer; }
  button.grey { background:#7b8a99; }
  button.red  { background:#c0392b; }
  input[type=text], input[type=password] { padding:8px 10px; border:1px solid #cfd8e0; border-radius:4px; font-size:14px; }
  ol { padding-left:20px; font-size:14px; line-height:1.7; }
  a  { color:#2d7ff9; }
</style>
</head>
<body>
<div class="wrap">
<h1>EzyPOS - Database Update</h1>
<p class="sub">Runs the database updates from your browser. No SSH needed.</p>

<?php if ($locked): ?>
  <div class="box">
    <p class="warn">This tool is switched off until you set a password.</p>
    <p>Open <code>migrate.php</code> in the cPanel File Manager editor, find this line near the top:</p>
    <p><code>define('MIGRATE_PASSWORD', 'CHANGE-ME');</code></p>
    <p>Replace <code>CHANGE-ME</code> with any password you like, save, then reload this page.</p>
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
    $err  = null;
    $conn = db_connect($err);
    if (!$conn) {
        echo '<div class="box"><p class="warn">' . h($err) . '</p></div>';
    } else {
        $cfg = db_settings();
        echo '<div class="box"><h3>Connected</h3><table class="data">'
           . '<tr><td>Database</td><td><b>' . h($cfg['database']) . '</b></td></tr>'
           . '<tr><td>Server</td><td>' . h($cfg['hostname']) . ' (MySQL ' . h($conn->server_info) . ')</td></tr>'
           . '</table></div>';

        $action = isset($_POST['action']) ? $_POST['action'] : '';

        /* ----------------------------------------------------------- actions */
        if ($action === 'v9' || $action === 'v10' || $action === 'v11' || $action === 'v12') {
            $files = array(
                'v9'  => array(MIGRATE_DIR . '/v9_billno_storecredit_privileges.sql',
                               'Step 2 - new columns and tables (v9)'),
                'v10' => array(MIGRATE_DIR . '/v10_data_repair.sql',
                               'Step 3 - repair existing records (v10, parts 1 to 3)'),
                'v11' => array(MIGRATE_DIR . '/v11_exchange_discount.sql',
                               'Step 4 - discount columns for exchanges (v11)'),
                'v12' => array(MIGRATE_DIR . '/v12_expense_subcategories.sql',
                               'Step 5 - parent categories and subcategories for expenses (v12)'),
            );
            $file = $files[$action][0];
            $name = $files[$action][1];

            if (!is_readable($file)) {
                echo '<div class="box"><p class="warn">Cannot find ' . h(basename($file))
                   . '. Make sure the whole application/migrations folder was uploaded.</p></div>';
            } else {
                run_statements($conn, split_sql(file_get_contents($file)), $name);
            }
        }
        elseif ($action === 'part4') {
            $check = @$conn->query("SELECT ret_total_adjusted FROM ezy_pos_returns LIMIT 1");

            // Accept it in any case, with any spacing. The grey wording inside the
            // box is only a hint - it has to actually be typed.
            $typed = strtoupper(preg_replace('/\s+/', ' ',
                        trim((string)(isset($_POST['confirm']) ? $_POST['confirm'] : ''))));

            if ($typed !== 'RUN PART 4') {
                echo '<div class="box"><p class="warn">Not run. Nothing has been changed.<br><br>'
                   . 'To confirm, click inside the box next to the Run part 4 button and type '
                   . '<b>RUN PART 4</b> yourself, then press the button. The grey wording you can '
                   . 'see in the box is only a hint - it is not typed in.</p></div>';
            } elseif ($check === false) {
                echo '<div class="box"><p class="warn">Not run - please do step 3 first. '
                   . 'Part 4 needs the marker column that step 3 adds.</p></div>';
            } else {
                // Same statements as Part 4 of v10_data_repair.sql. Each return
                // used here is stamped ret_total_adjusted = 1, so a second run
                // finds nothing left to do and cannot subtract twice.
                $part4 = array(
                    "DROP TEMPORARY TABLE IF EXISTS tmp_part4_sales",

                    "CREATE TEMPORARY TABLE tmp_part4_sales AS
                     SELECT t.ret_sale_id AS sale_id, t.refunded
                     FROM (
                         SELECT ret_sale_id, SUM(ret_refund_amount) AS refunded
                         FROM ezy_pos_returns
                         WHERE ret_status = 1 AND ret_type <> 'exchange' AND ret_total_adjusted = 0
                         GROUP BY ret_sale_id
                     ) t
                     JOIN ezy_pos_sale s ON s.sale_id = t.ret_sale_id
                     WHERE t.refunded > 0 AND s.sale_grandtotal - t.refunded >= 0",

                    "UPDATE ezy_pos_sale s
                     JOIN tmp_part4_sales p ON p.sale_id = s.sale_id
                     SET s.sale_grandtotal = s.sale_grandtotal - p.refunded",

                    "UPDATE ezy_pos_returns r
                     JOIN tmp_part4_sales p ON p.sale_id = r.ret_sale_id
                     SET r.ret_total_adjusted = 1
                     WHERE r.ret_total_adjusted = 0 AND r.ret_type <> 'exchange'",

                    "DROP TEMPORARY TABLE IF EXISTS tmp_part4_sales",
                );
                run_statements($conn, $part4, 'Part 4 - reduce bill totals by what was refunded');
            }
        }
        elseif ($action === 'selfdelete') {
            if (@unlink(__FILE__)) {
                echo '<div class="box"><h3>Deleted</h3><p>This tool has removed itself from the server. '
                   . 'Nothing else to do.</p></div></div></body></html>';
                exit;
            }
            echo '<div class="box"><p class="warn">Could not delete the file automatically. '
               . 'Please delete migrate.php in the cPanel File Manager.</p></div>';
        }

        /* ------------------------------------------------------------- steps */
        status_checks($conn);
?>
  <h2>Do these in order</h2>

  <div class="box step">
    <h3>Step 1 - Back up the database</h3>
    <p class="tip">In cPanel open <b>phpMyAdmin</b>, pick the database <b><?php echo h($cfg['database']); ?></b>,
    press <b>Export</b> and then <b>Go</b>. Keep the file it downloads.<br>
    Do not skip this. Step 3 changes existing records.</p>
  </div>

  <div class="box step">
    <h3>Step 2 - Add the new columns and tables</h3>
    <p>Adds the per-store bill numbers, the store credit option on returns and the new page
       permissions. It only adds things - no sale, item, customer or stock figure is touched.</p>
    <form method="post"><input type="hidden" name="action" value="v9">
      <button type="submit">Run step 2</button></form>
  </div>

  <div class="box step">
    <h3>Step 3 - Repair the records that were saved wrong</h3>
    <p>Puts the correct refund total back on the old returns (the 5,250 / 7,250 case), gives the
       branchless returns their branch, and files the gift voucher bills under Ethulkotte.<br>
       <b>Upload the new program files first</b>, then run this.</p>
    <form method="post"><input type="hidden" name="action" value="v10">
      <button type="submit">Run step 3</button></form>
  </div>

  <div class="box step">
    <h3>Step 4 - Discount on exchanges</h3>
    <p>Adds the columns that record a discount given on an exchange. It only adds things -
       nothing existing is touched. The discount works on screen with or without this;
       running it just means a past exchange can be read back with its discount later.</p>
    <form method="post"><input type="hidden" name="action" value="v11">
      <button type="submit">Run step 4</button></form>
  </div>

  <div class="box step">
    <h3>Step 5 - Parent categories and subcategories for expenses</h3>
    <p>Adds one column so an expense category can sit under a parent one
       (Transportation &gt; Fuel). It only adds things. Every category you have today
       becomes a parent category, and every expense already entered keeps exactly the
       category it has now, so nothing in the Expense Report changes.</p>
    <form method="post"><input type="hidden" name="action" value="v12">
      <button type="submit">Run step 5</button></form>
  </div>

  <div class="box">
    <h3>Optional - the sales total correction (changes past sales figures)</h3>
    <p>On a return with no exchange, the old program never took the refunded value off the
       original bill, so those bills still show their full value in the Sales Report.
       This corrects them - which means your past sales totals will go down by the amount
       that was refunded.</p>
    <p class="note">Step 3 above prints the list of bills this would affect. Look at that list first.</p>
    <form method="post" onsubmit="return confirm('This changes past sales totals. Continue?');">
      <input type="hidden" name="action" value="part4">
      <p style="font-size:13px;margin:0 0 6px">Type <b>RUN PART 4</b> in this box, then press the button:</p>
      <input type="text" name="confirm" placeholder="type it here" size="18">
      <button type="submit" class="grey">Run part 4</button>
    </form>
  </div>

  <div class="box">
    <h3>When you are finished</h3>
    <p>Remove this tool so nobody else can open it.</p>
    <form method="post" onsubmit="return confirm('Delete migrate.php from the server?');">
      <input type="hidden" name="action" value="selfdelete">
      <button type="submit" class="red">Delete this tool</button>
      <a href="migrate.php?logout=1" style="margin-left:14px">Log out</a>
    </form>
  </div>
<?php
    }
endif; ?>
</div>
</body>
</html>
