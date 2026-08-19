<?php
/*
 * Online label test page for EzyPOS.
 *
 * Drop this file next to index.php on the EzyPOS site and open
 *     https://your-site/label-test.php
 * on the PC that has the TSC TTP-244 Pro and the label program on it.
 *
 * WHY IT WORKS THIS WAY
 * A browser cannot talk to a USB thermal printer. No browser can, on any
 * operating system - there is no way for a web page to reach a USB device like
 * that. So this page does not try. It asks EzyPOS for the item data over the
 * normal barcode API, and then hands the finished queue to the label program
 * running on the same PC, at http://127.0.0.1:9110. The program does the
 * printing. That address is this machine only - nothing on the internet can
 * reach it.
 *
 * The API key never leaves the server: the page asks THIS file for items, and
 * this file adds the key server side. So the key is not sitting in the page
 * source where anyone could read it.
 */

// ---------------------------------------------------------------------------
// The barcode API key.
//
// If you would rather not have this page read the database, just paste the key
// here and it will use it. Masters > Barcode Settings in EzyPOS shows it.
// ---------------------------------------------------------------------------
define('LABEL_TEST_API_KEY', '');

$apiKey = LABEL_TEST_API_KEY;

if ($apiKey === '') {
    // Otherwise read it out of the EzyPOS config table, so there is only ever
    // one copy of the key and the two cannot drift apart.
    //
    // CodeIgniter config files refuse to load unless BASEPATH and ENVIRONMENT
    // are defined - that is what stops them being opened directly in a browser.
    // Defining them here only lets the file set $db; nothing else happens.
    $configFile = __DIR__ . '/application/config/database.php';
    if (is_readable($configFile)) {
        if (!defined('BASEPATH'))   { define('BASEPATH', __DIR__ . '/system/'); }
        if (!defined('ENVIRONMENT')) { define('ENVIRONMENT', 'production'); }
        try {
            require_once $configFile;
            if (isset($db['default'])) {
                $c = $db['default'];
                $host = $c['hostname'];
                $port = 3306;
                if (strpos($host, ':') !== false) { list($host, $port) = explode(':', $host, 2); }
                $mysqli = @new mysqli($host, $c['username'], $c['password'], $c['database'], (int)$port);
                if (!$mysqli->connect_errno) {
                    $res = $mysqli->query("SELECT config_value FROM ezy_pos_config2 WHERE config_key='labeljoy_api_key' LIMIT 1");
                    if ($res && ($row = $res->fetch_row())) { $apiKey = $row[0]; }
                    $mysqli->close();
                }
            }
        } catch (Throwable $e) {
            // Fall through with an empty key; the page says what to do about it.
            $apiKey = '';
        }
    }
}

$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
      . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

// Item search, proxied so the key stays on the server.
if (isset($_GET['action']) && $_GET['action'] === 'items') {
    header('Content-Type: application/json; charset=utf-8');
    if ($apiKey === '') {
        echo json_encode(array('error' => 'No barcode API key is set up in EzyPOS yet. Open Masters > Barcode Settings and generate one.'));
        exit;
    }
    $url = $base . '/barcode-api/items';
    if (isset($_GET['search']) && $_GET['search'] !== '') {
        $url .= '?search=' . urlencode($_GET['search']);
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => array('X-API-Key: ' . $apiKey),
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_SSL_VERIFYPEER => false
    ));
    $body = curl_exec($ch);
    $err  = curl_error($ch);
    curl_close($ch);
    echo $body !== false ? $body : json_encode(array('error' => 'Could not reach the barcode API: ' . $err));
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Label printing test - EzyPOS</title>
<style>
  body { font-family: Segoe UI, Arial, sans-serif; margin: 0; background: #f4f6f8; color: #24292f; }
  .wrap { max-width: 1000px; margin: 0 auto; padding: 24px 16px 60px; }
  h1 { font-size: 22px; margin: 0 0 4px; }
  .sub { color: #57606a; margin: 0 0 20px; }
  .card { background: #fff; border: 1px solid #d8dee4; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
  .card h2 { font-size: 15px; margin: 0 0 12px; text-transform: uppercase; letter-spacing: .04em; color: #57606a; }
  input, button, select { font: inherit; }
  input[type=text], input[type=number] { padding: 7px 9px; border: 1px solid #d0d7de; border-radius: 6px; }
  button { padding: 8px 14px; border: 1px solid #d0d7de; border-radius: 6px; background: #f6f8fa; cursor: pointer; }
  button:hover { background: #eef1f4; }
  button.primary { background: #1f6feb; border-color: #1f6feb; color: #fff; font-weight: 600; }
  button.primary:hover { background: #1a5fd0; }
  button.primary:disabled { background: #9fb8dd; border-color: #9fb8dd; cursor: not-allowed; }
  table { width: 100%; border-collapse: collapse; }
  th, td { text-align: left; padding: 7px 8px; border-bottom: 1px solid #eaeef2; font-size: 14px; }
  th { color: #57606a; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
  td.num, th.num { text-align: right; }
  .pill { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
  .ok   { background: #dafbe1; color: #116329; }
  .bad  { background: #ffebe9; color: #82071e; }
  .warn { background: #fff8c5; color: #7d4e00; }
  .muted { color: #57606a; font-size: 13px; }
  .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
  .totals { font-size: 16px; font-weight: 600; }
  pre { background: #0d1117; color: #c9d1d9; padding: 12px; border-radius: 6px; overflow: auto; max-height: 320px; font-size: 12px; }
</style>
</head>
<body>
<div class="wrap">
  <h1>Label printing test</h1>
  <p class="sub">Queue up some labels and send them to the TSC TTP-244 Pro on this PC, without leaving the browser.</p>

  <div class="card">
    <h2>Print agent</h2>
    <div class="row">
      <span>Address</span>
      <input type="text" id="agent" value="http://127.0.0.1:9110" style="width:220px">
      <button onclick="checkAgent()">Check</button>
      <span id="agentStatus" class="pill warn">not checked</span>
    </div>
    <p class="muted" id="agentDetail" style="margin-bottom:0">
      The EzyPOS Label Printer program must be open on this PC, with the online test page turned on in its Settings.
    </p>
  </div>

  <div class="card">
    <h2>1. Find items</h2>
    <div class="row">
      <input type="text" id="search" placeholder="item code, name or brand" style="width:300px"
             onkeydown="if(event.key==='Enter'){findItems();}">
      <button onclick="findItems()">Search EzyPOS</button>
      <span>Labels each</span>
      <input type="number" id="qty" value="10" min="1" max="10000" style="width:90px">
    </div>
    <table id="results" style="margin-top:12px"><tbody></tbody></table>
  </div>

  <div class="card">
    <h2>2. Print queue</h2>
    <table id="queue">
      <thead><tr><th>Code</th><th>Item</th><th class="num">Price</th><th class="num">Labels</th><th>Status</th><th></th></tr></thead>
      <tbody></tbody>
    </table>
    <p class="muted" id="empty">Nothing queued yet.</p>
    <div class="row" style="margin-top:14px">
      <button class="primary" id="btnPrint" onclick="doPrint()" disabled>Print</button>
      <button onclick="doPreview()">Show the TSPL</button>
      <button onclick="clearQueue()">Clear</button>
      <span class="totals" id="totals"></span>
    </div>
    <div id="result" style="margin-top:12px"></div>
  </div>

  <div class="card" id="tsplCard" style="display:none">
    <h2>TSPL that would be sent</h2>
    <pre id="tspl"></pre>
  </div>
</div>

<script>
var QUEUE = [];

function agentUrl(path){ return document.getElementById('agent').value.replace(/\/+$/, '') + path; }
function esc(s){ var d = document.createElement('div'); d.textContent = (s === null || s === undefined) ? '' : s; return d.innerHTML; }
function money(v){ return (parseFloat(v) || 0).toFixed(2); }

function setAgent(cls, text, detail){
  var el = document.getElementById('agentStatus');
  el.className = 'pill ' + cls;
  el.textContent = text;
  if (detail) document.getElementById('agentDetail').textContent = detail;
}

function checkAgent(){
  setAgent('warn', 'checking...');
  fetch(agentUrl('/status'))
    .then(function(r){ return r.json(); })
    .then(function(d){
      if (d.printer_ready) {
        setAgent('ok', 'ready',
          'Printing to "' + d.printer + '". Label ' + d.label.width_mm + ' x ' + d.label.height_mm +
          ' mm, ' + d.label.columns + ' across, ' + d.label.column_gap_mm + ' mm between them.');
      } else {
        setAgent('bad', 'printer not connected', d.printer_message || 'The printer is not ready.');
      }
    })
    .catch(function(){
      setAgent('bad', 'agent not running',
        'Could not reach the label program on this PC. Open EzyPOS Label Printer, go to Settings, and make sure ' +
        '"Let a browser page print through this program" is ticked.');
    });
}

function findItems(){
  var q = document.getElementById('search').value;
  var tb = document.querySelector('#results tbody');
  tb.innerHTML = '<tr><td colspan="5" class="muted">Searching...</td></tr>';
  fetch('?action=items&search=' + encodeURIComponent(q))
    .then(function(r){ return r.json(); })
    .then(function(d){
      if (d.error) { tb.innerHTML = '<tr><td colspan="5" class="muted">' + esc(d.error) + '</td></tr>'; return; }
      var items = d.items || [];
      if (!items.length) { tb.innerHTML = '<tr><td colspan="5" class="muted">Nothing matched.</td></tr>'; return; }
      var html = '';
      for (var i = 0; i < Math.min(items.length, 100); i++){
        var it = items[i];
        html += '<tr><td>' + esc(it.item_code) + '</td><td>' + esc(it.item_name) + '</td>' +
                '<td class="num">' + money(it.selling_price) + '</td>' +
                '<td class="num"><button onclick="addItem(' + i + ')">Add</button></td></tr>';
      }
      tb.innerHTML = html;
      window.__found = items;
    })
    .catch(function(e){ tb.innerHTML = '<tr><td colspan="5" class="muted">Search failed: ' + esc(e.message) + '</td></tr>'; });
}

function addItem(i){
  var it = (window.__found || [])[i];
  if (!it) return;
  var qty = parseInt(document.getElementById('qty').value, 10) || 1;
  var found = null;
  for (var k = 0; k < QUEUE.length; k++) if (QUEUE[k].item_id === it.item_id) found = QUEUE[k];
  if (found) found.label_count += qty;
  else QUEUE.push({
    item_id: it.item_id, item_code: it.item_code, barcode_value: it.barcode_value || it.item_code,
    item_name: it.item_name, selling_price: it.selling_price, label_count: qty
  });
  drawQueue();
}

function removeRow(i){ QUEUE.splice(i, 1); drawQueue(); }
function clearQueue(){ QUEUE = []; drawQueue(); document.getElementById('result').innerHTML = ''; }

function drawQueue(){
  var tb = document.querySelector('#queue tbody');
  var html = '', total = 0;
  for (var i = 0; i < QUEUE.length; i++){
    var q = QUEUE[i];
    total += q.label_count;
    html += '<tr><td>' + esc(q.item_code) + '</td><td>' + esc(q.item_name) + '</td>' +
            '<td class="num">' + money(q.selling_price) + '</td>' +
            '<td class="num"><input type="number" min="1" max="10000" value="' + q.label_count +
            '" style="width:80px" onchange="QUEUE[' + i + '].label_count=parseInt(this.value,10)||1;drawQueue();"></td>' +
            '<td><span class="pill ok">Ready</span></td>' +
            '<td class="num"><button onclick="removeRow(' + i + ')">Remove</button></td></tr>';
  }
  tb.innerHTML = html;
  document.getElementById('empty').style.display = QUEUE.length ? 'none' : 'block';
  document.getElementById('btnPrint').disabled = QUEUE.length === 0;
  document.getElementById('totals').textContent = total ? (total + ' labels') : '';
}

function post(path, cb){
  fetch(agentUrl(path), {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ labels: QUEUE })
  })
  .then(function(r){ return r.json().then(function(d){ return { status: r.status, body: d }; }); })
  .then(cb)
  .catch(function(e){
    document.getElementById('result').innerHTML =
      '<span class="pill bad">failed</span> <span class="muted">Could not reach the label program on this PC. ' +
      esc(e.message) + '</span>';
  });
}

function doPreview(){
  post('/preview', function(r){
    var d = r.body;
    if (!d.ok) {
      document.getElementById('result').innerHTML = '<span class="pill bad">cannot print</span> <span class="muted">' + esc(d.error) + '</span>';
      return;
    }
    document.getElementById('tsplCard').style.display = 'block';
    document.getElementById('tspl').textContent = d.tspl;
    document.getElementById('result').innerHTML =
      '<span class="pill ok">' + d.labels + ' labels in ' + d.rows + ' rows</span>' +
      (d.warnings && d.warnings.length ? ' <span class="muted">' + esc(d.warnings.join(' ')) + '</span>' : '');
  });
}

function doPrint(){
  document.getElementById('btnPrint').disabled = true;
  document.getElementById('result').innerHTML = '<span class="muted">Sending...</span>';
  post('/print', function(r){
    var d = r.body;
    document.getElementById('btnPrint').disabled = QUEUE.length === 0;
    if (d.ok) {
      document.getElementById('result').innerHTML =
        '<span class="pill ok">printed</span> <span class="muted">' + esc(d.message) +
        ' (' + d.rows + ' rows)</span>' +
        (d.warnings && d.warnings.length ? '<br><span class="muted">' + esc(d.warnings.join(' ')) + '</span>' : '');
    } else {
      document.getElementById('result').innerHTML =
        '<span class="pill bad">not printed</span> <span class="muted">' + esc(d.error) + '</span>';
    }
  });
}

checkAgent();
drawQueue();
</script>
</body>
</html>
