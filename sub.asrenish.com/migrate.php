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
    foreach (array('hostname', 'username', 'password', 'database', 'char_set', 'port') as $key) {
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
    // CodeIgniter takes the port as its own setting rather than glued onto the
    // hostname. Honour that too, or a database on a non-standard port reports
    // "Access denied" and sends you hunting for the wrong problem.
    if (!$port && !empty($cfg['port'])) { $port = $cfg['port']; }

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
    $rows[] = array('Bill number on customer returns (cusrtrn_saleID)', $hasCol('ezy_pos_cus_return', 'cusrtrn_saleID'));
    $rows[] = array('Advance Return tables (ezy_pos_adv_return)', $hasTable('ezy_pos_adv_return'));
    $rows[] = array('Advance Return permission (priv_advreturn)', $hasCol('ezy_pos_privileges', 'priv_advreturn'));
    $rows[] = array('Advance Exchange - goods going out', $hasTable('ezy_pos_adv_exchange_item'));
    $rows[] = array('Advance Exchange - payments', $hasTable('ezy_pos_adv_payment'));

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
        if (in_array($action, array('v9','v10','v11','v12','v13','v14','v15','v16'), true)) {
            $files = array(
                'v9'  => array(MIGRATE_DIR . '/v9_billno_storecredit_privileges.sql',
                               'Step 2 - new columns and tables (v9)'),
                'v10' => array(MIGRATE_DIR . '/v10_data_repair.sql',
                               'Step 3 - repair existing records (v10, parts 1 to 3)'),
                'v11' => array(MIGRATE_DIR . '/v11_exchange_discount.sql',
                               'Step 4 - discount columns for exchanges (v11)'),
                'v12' => array(MIGRATE_DIR . '/v12_expense_subcategories.sql',
                               'Step 5 - parent categories and subcategories for expenses (v12)'),
                'v13' => array(MIGRATE_DIR . '/v13_return_keeps_sale_qty.sql',
                               'Step 6 - keep the sold quantity when an item is returned (v13)'),
                'v14' => array(MIGRATE_DIR . '/v14_repair_empty_sale_dates.sql',
                               'Step 7 - put a date back on the bills saved without one (v14)'),
                'v15' => array(MIGRATE_DIR . '/v15_advance_return.sql',
                               'Step 8 - the Advance Return module (v15)'),
                'v16' => array(MIGRATE_DIR . '/v16_advance_exchange.sql',
                               'Step 9 - Advance Return becomes Advance Exchange (v16)'),
                'v17' => array(MIGRATE_DIR . '/v17_exchange_tailoring_discount.sql',
                               'Step 10 - discount on exchanges and tailoring orders (v17)'),
                'v18' => array(MIGRATE_DIR . '/v18_sales_report_permission.sql',
                               'Step 11 - permission for the new Sales Report (v18)'),
                'v19' => array(MIGRATE_DIR . '/v19_super_admin_features.sql',
                               'Step 12 - the Super Admin feature switches (v19)'),
                'v20' => array(MIGRATE_DIR . '/v20_super_admin_login.sql',
                               'Step 13 - the provider login (v20)'),
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
        elseif ($action === 'makesuper') {
            // Create (or reset) the provider login.
            //
            // This lives here rather than inside the program on purpose. The
            // Super Admin page is for the provider only - a shop administrator
            // must never see it, not even on a freshly updated system with no
            // provider yet. So the first one is made from this page, which is
            // already behind a password and is deleted when the update is done.
            $u  = trim((string)(isset($_POST['su_user']) ? $_POST['su_user'] : ''));
            $p1 = (string)(isset($_POST['su_pass'])  ? $_POST['su_pass']  : '');
            $p2 = (string)(isset($_POST['su_pass2']) ? $_POST['su_pass2'] : '');

            $col = @$conn->query("SHOW COLUMNS FROM ezy_pos_users LIKE 'user_is_super'");
            $haveCol = ($col && $col->num_rows > 0);

            if (!$haveCol) {
                echo '<div class="box"><p class="warn">Please run step 13 first - it adds the '
                   . 'column that marks the provider login.</p></div>';
            } elseif ($u === '' || $p1 === '') {
                echo '<div class="box"><p class="warn">Fill in both the username and the password. '
                   . 'Nothing has been changed.</p></div>';
            } elseif ($p1 !== $p2) {
                echo '<div class="box"><p class="warn">The two passwords do not match. '
                   . 'Nothing has been changed.</p></div>';
            } elseif (strlen($p1) < 8) {
                echo '<div class="box"><p class="warn">Use a password of at least 8 characters - '
                   . 'this login outranks every other account on the system. '
                   . 'Nothing has been changed.</p></div>';
            } else {
                $uEsc  = $conn->real_escape_string($u);
                $pHash = md5($p1);

                // Is there one already? Then this is a reset, not a new account.
                $ex = $conn->query("SELECT user_id FROM ezy_pos_users WHERE user_is_super = 1 LIMIT 1");
                $exRow = ($ex && $ex->num_rows > 0) ? $ex->fetch_assoc() : null;

                // The username must not collide with a shop login.
                $clashSql = "SELECT user_id FROM ezy_pos_users WHERE user_username = '".$uEsc."'";
                if ($exRow) { $clashSql .= " AND user_id <> ".intval($exRow['user_id']); }
                $clash = $conn->query($clashSql);

                if ($clash && $clash->num_rows > 0) {
                    echo '<div class="box"><p class="warn">That username is already used by another '
                       . 'login. Pick a different one. Nothing has been changed.</p></div>';
                } elseif ($exRow) {
                    $id = intval($exRow['user_id']);
                    $conn->query("UPDATE ezy_pos_users
                                  SET user_username = '".$uEsc."', user_password = '".$pHash."',
                                      user_status = 1
                                  WHERE user_id = ".$id);
                    echo '<div class="box"><h3>Provider login updated</h3>'
                       . '<p class="ok">The username and password have been changed. '
                       . 'Sign in as <b>' . h($u) . '</b>.</p></div>';
                } else {
                    // An administrator as well as the provider, so every existing
                    // "is this an admin" check in the system passes; the flag on
                    // top is what makes it the provider.
                    $conn->query("INSERT INTO ezy_pos_users
                                   (user_username, user_name, user_password, user_role, user_status, user_is_super)
                                  VALUES ('".$uEsc."', 'Super Admin', '".$pHash."', 1, 1, 1)");
                    $newId = $conn->insert_id;

                    if (!$newId) {
                        echo '<div class="box"><p class="warn">The login could not be created: '
                           . h($conn->error) . '</p></div>';
                    } else {
                        // The login query joins the privileges table, so without a
                        // row here the new account could not sign in at all.
                        $cols = array(); $vals = array();
                        $pc = $conn->query("SHOW COLUMNS FROM ezy_pos_privileges");
                        while ($pc && ($c = $pc->fetch_assoc())) {
                            if ($c['Field'] === 'priv_id') { continue; }
                            $cols[] = $c['Field'];
                            $vals[] = ($c['Field'] === 'priv_userid') ? intval($newId) : 1;
                        }
                        $conn->query("INSERT INTO ezy_pos_privileges (".implode(',', $cols).")
                                      VALUES (".implode(',', $vals).")");

                        echo '<div class="box"><h3>Provider login created</h3>'
                           . '<p class="ok">Sign in at your normal login page as <b>' . h($u) . '</b>. '
                           . 'Super Admin Settings then appears under Masters, and only this login sees it. '
                           . 'Your shop administrators cannot see that page, and this account does not '
                           . 'appear in their user list at all.</p></div>';
                    }
                }
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

  <div class="box step">
    <h3>Step 6 - Keep the sold quantity when an item is returned</h3>
    <p>Until now a customer return subtracted the returned pieces from the original bill,
       so a bill for 2 pieces with 1 brought back read as a bill for 1 piece ever after.
       This adds one column that records which bill a return came off, so the return can
       be kept as its own record instead. The bill keeps the quantity it was rung up with
       and the Grand Total still comes down by the refund.</p>
    <p class="note">It only adds a column. Nothing already in the database is changed.</p>
    <form method="post"><input type="hidden" name="action" value="v13">
      <button type="submit">Run step 6</button></form>
  </div>

  <div class="box step">
    <h3>Step 7 - Put a date back on the bills that were saved without one</h3>
    <p>On the sales screen the date was only read when an item was added to the bill.
       A bill with no stock line on it - a gift voucher sold on its own is the usual
       case - was saved with no date at all, and the database stored 0000-00-00.</p>
    <p>Every report asks for "date between these two dates", and 0000-00-00 is inside
       no range you can pick, so those bills were invisible in the Cash Flow report,
       Today's Summary and Payments Received - permanently. The Sales Report uses a
       different column, which is why the same sale shows up there and nowhere else.</p>
    <p>This copies the date the database itself recorded when the bill was created
       onto the bill and its payment lines. It only touches rows that are already
       broken; a bill with a real date on it is left alone.</p>
    <form method="post"><input type="hidden" name="action" value="v14">
      <button type="submit">Run step 7</button></form>
  </div>

  <div class="box step">
    <h3>Step 8 - The Advance Return module</h3>
    <p>Adds the two tables the Advance Return page uses, and the Advance Return tick
       box on the user permissions page. It only adds things - the existing Returns
       and Exchange module and its tables are not touched at all.</p>
    <p class="note">Nobody sees the new page until you tick Advance Return for them
       under Users. Administrators see it straight away.</p>
    <form method="post"><input type="hidden" name="action" value="v15">
      <button type="submit">Run step 8</button></form>
  </div>

  <div class="box step">
    <h3>Step 9 - Advance Return becomes Advance Exchange</h3>
    <p>Adds the two tables the exchange side needs - the goods going out, and how the
       difference was paid - plus three columns on the existing table. It only adds
       things. Advance Returns you have already taken are left exactly as they are;
       they simply read as an exchange with nothing going out.</p>
    <p class="note">After this, the page can take goods back AND hand goods over, and
       settle the difference by cash, cheque, any card machine or a gift voucher.</p>
    <form method="post"><input type="hidden" name="action" value="v16">
      <button type="submit">Run step 9</button></form>
  </div>

  <div class="box step">
    <h3>Step 10 - Discount on exchanges and tailoring orders</h3>
    <p>Adds three columns to the Advance Exchange table and three to the tailoring
       orders table: the discount in rupees, whether a flat amount or a percentage
       was typed, and the number that was typed. It only adds columns, all starting
       at zero, so every exchange and every order you already have is unchanged.</p>
    <p class="note">After this, both screens have a Discount box that takes either a
       flat rupee amount or a percentage, the same as the Sales window. The figure is
       printed on the slip and on the tailoring estimate and final bill.</p>
    <form method="post"><input type="hidden" name="action" value="v17">
      <button type="submit">Run step 10</button></form>
  </div>

  <div class="box step">
    <h3>Step 11 - The new Sales Report</h3>
    <p>The page that was called Sales Report is now called <strong>Sales Reprint</strong> -
       it still finds a bill, reprints it, and prints the cash flow slip, exactly as
       before. A new <strong>Sales Report</strong> page sits next to it with the totals:
       what was sold, what was collected and on which tender, how much of it was gift
       vouchers, and what came back as returns, with date, payment method and branch
       filters.</p>
    <p>This step adds one column - the permission for the new page. It starts at 0, so
       nobody sees it until you tick <strong>Sales Report</strong> for them under Users.
       Whoever had the old Sales Report keeps Sales Reprint, which is the page they have
       been using. Administrators see both straight away.</p>
    <form method="post"><input type="hidden" name="action" value="v18">
      <button type="submit">Run step 11</button></form>
  </div>

  <div class="box step">
    <h3>Step 12 - The Super Admin feature switches</h3>
    <p>Writes the settings rows that decide which parts of the system this shop gets -
       GRN direct to store, single location sales, and an on/off for Production,
       Tailoring, Loyalty, Label printing, Stock Transfer, Supplier Return and
       Delivery. They go into the same settings table the shop name already uses;
       no table is created and no column is added to anything.</p>
    <p class="note"><strong>Every switch starts at exactly what the system does today</strong>,
       so running this changes nothing at all until you move one. Direct GRN and single
       location start OFF because today the goods go through the warehouse and the
       screens ask which branch. The rest start ON.</p>
    <p class="note">Run it again later and nothing is reset - a switch you have changed
       is left alone.</p>
    <p class="note">Afterwards the page is under <strong>Masters &gt; Super Admin
       Settings</strong>, visible to administrators only.</p>
    <form method="post"><input type="hidden" name="action" value="v19">
      <button type="submit">Run step 12</button></form>
  </div>

  <div class="box step">
    <h3>Step 13 - The provider login</h3>
    <p>Super Admin is a rank <strong>above</strong> the shop's administrator. The
       administrator runs the shop; the provider decides which parts of the system the
       shop has at all. This step adds the one column that tells the two apart.</p>
    <p class="note">It creates no account and sets no password, deliberately - a
       password shipped with the software is a door left open on every shop that
       installs it. Every existing user keeps exactly the access they have today.</p>
    <p class="note"><strong>Making the first provider login:</strong> sign in as an
       administrator, open Masters &gt; Super Admin Settings, and fill in the Provider
       login box at the top. The moment you save it, that page stops being open to the
       shop's administrators - only the provider login opens it from then on, and the
       account does not appear in the shop's user list at all.</p>
    <form method="post"><input type="hidden" name="action" value="v20">
      <button type="submit">Run step 13</button></form>
  </div>

  <div class="box step">
    <h3>Step 14 - Create the provider (Super Admin) login</h3>
    <p>This is the account that opens Super Admin Settings. It is a rank <strong>above</strong>
       your administrators: they cannot see that page, cannot see this account in their user
       list, and cannot change or delete it.</p>
    <p class="note">It is created from here, not from inside the program, deliberately - so that
       a shop administrator never sees the Super Admin page at any point, not even on a system
       that has just been updated. This page is already behind a password and you delete it when
       the update is finished.</p>
    <p class="note"><strong>Keep the password somewhere safe.</strong> Once migrate.php is deleted,
       the only way to change it is to sign in as this account. If you lose it, upload migrate.php
       again and run this step - it resets the login rather than creating a second one.</p>
    <form method="post">
      <input type="hidden" name="action" value="makesuper">
      <p>
        Username <input type="text" name="su_user" value="superadmin" style="width:220px;">
      </p>
      <p>
        Password <input type="text" name="su_pass" style="width:220px;" placeholder="at least 8 characters">
        &nbsp; repeat <input type="text" name="su_pass2" style="width:220px;">
      </p>
      <button type="submit">Create the provider login</button>
    </form>
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
