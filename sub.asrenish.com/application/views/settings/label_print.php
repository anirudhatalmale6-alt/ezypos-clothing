<!-- ==============================================================
     Print Labels - TSPL through QZ Tray.

     The server builds the printer's own command language (the same bytes
     the Windows label program would send) and QZ Tray, a small print agent
     on this computer, hands it to the TSC raw. The browser's page printing,
     and the Windows page-length logic that made labels walk up the roll,
     are not involved at all.
     ============================================================== -->
<div class="wrapper">
    <div class="container">

        <!-- -------------------------------------------- QZ Tray status -->
        <div class="row">
            <div class="col-12">
                <div class="card-box" id="qzBox" style="border-left:4px solid #9e9e9e;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="m-t-0 m-b-5"><i class="fa fa-plug"></i> QZ Tray: <span id="qzState">connecting...</span></h5>
                            <div class="text-muted" id="qzHelp" style="font-size:13px;"></div>
                        </div>
                        <div class="form-inline">
                            <label class="m-r-5"><strong>Printer</strong></label>
                            <select class="form-control form-control-sm m-r-5" id="lpPrinter" style="min-width:240px;" disabled>
                                <option value="">-- connect QZ Tray first --</option>
                            </select>
                            <button class="btn btn-sm btn-outline-secondary" id="lpReconnect" type="button"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- ---------------------------------------------- the queue -->
            <div class="col-lg-7">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-tags"></i> Labels to print</h4>
                    <div class="row m-b-10">
                        <div class="col-md-7 ui-front">
                            <input type="text" class="form-control" id="lpSearch" autocomplete="off"
                                   placeholder="Item code or name - or scan it">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="lpQty" value="1" min="1" max="1000">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-block" id="lpAdd" type="button"><i class="fa fa-plus"></i> Add</button>
                        </div>
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="lpGrn" placeholder="...or a GRN number - one label per unit received">
                        </div>
                        <div class="col-md-5">
                            <button class="btn btn-outline-primary btn-block" id="lpLoadGrn" type="button"><i class="fa fa-download"></i> Load GRN</button>
                        </div>
                    </div>
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>Code</th><th>Item</th><th class="text-right">Price</th>
                                   <th style="width:90px;">Labels</th><th style="width:40px;"></th></tr></thead>
                        <tbody id="lpBody"><tr class="lp-empty"><td colspan="5" class="text-center text-muted">Nothing yet.</td></tr></tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong id="lpTotals">0 labels</strong>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm" id="lpClear" type="button">Clear</button>
                            <button class="btn btn-outline-info btn-sm" id="lpShow" type="button">Show the TSPL</button>
                            <button class="btn btn-success" id="lpPrint" type="button"><i class="fa fa-print"></i> Print</button>
                        </div>
                    </div>
                    <div id="lpMsg" class="m-t-10" style="display:none;white-space:pre-line;"></div>
                    <pre id="lpTspl" style="display:none;max-height:260px;overflow:auto;font-size:11px;background:#f7f7f7;padding:8px;margin-top:10px;"></pre>
                </div>
            </div>

            <!-- ------------------------------------- roll + calibration -->
            <div class="col-lg-5">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-5"><i class="fa fa-crosshairs"></i> If the labels creep up or down</h4>
                    <p class="text-muted" style="font-size:13px;">
                        Printing that is right on the first label and moves on each one after it means
                        the printer is feeding a slightly wrong distance per label. Do these in order.
                    </p>
                    <ol style="font-size:13px;padding-left:18px;">
                        <li class="m-b-5">Measure the roll with a ruler and enter the sizes below. Save.</li>
                        <li class="m-b-5"><button class="btn btn-sm btn-outline-dark" id="lpTeach" type="button">Teach the printer this roll</button>
                            <br><span class="text-muted">It feeds 2-3 blank labels while it measures the gap itself.</span></li>
                        <li class="m-b-5">Tick <strong>Use the printer's own measurement</strong> below and save.
                            <span class="text-muted">From then on the job stops overriding what the printer measured.</span></li>
                        <li class="m-b-5">Print 10-15 labels and compare the last with the first.</li>
                        <li><button class="btn btn-sm btn-outline-dark" id="lpCal" type="button">Print a calibration sheet</button>
                            <span class="text-muted">- one row of rulers, if every label is out by the same amount.</span></li>
                    </ol>
                </div>

                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-sliders"></i> The roll (millimetres)</h4>
                    <div class="row" id="lpSettings">
                        <?php
                        $fields = array(
                            'width_mm'   => array('Sticker width', 'one sticker, edge to edge'),
                            'height_mm'  => array('Sticker height', 'top edge to bottom edge'),
                            'columns'    => array('Stickers across', '2 for a 2-up roll'),
                            'col_gap_mm' => array('Gap between them', 'side by side'),
                            'row_gap_mm' => array('Gap between rows', 'the die-cut gap'),
                            'left_mm'    => array('Left margin', 'paper edge to first sticker'),
                            'top_mm'     => array('Vertical trim', '+ moves the print down'),
                            'bar_height_mm' => array('Barcode height', ''),
                            'speed'      => array('Speed', '1-12'),
                            'density'    => array('Darkness', '0-15'),
                        );
                        foreach ($fields as $k => $f) { ?>
                        <div class="col-6 m-b-10">
                            <label class="small mb-0"><?php echo $f[0]; ?></label>
                            <input type="number" step="0.1" class="form-control form-control-sm lp-set" data-k="<?php echo $k; ?>"
                                   value="<?php echo htmlspecialchars($settings[$k]); ?>">
                            <?php if ($f[1]) { ?><small class="text-muted"><?php echo $f[1]; ?></small><?php } ?>
                        </div>
                        <?php } ?>
                        <div class="col-12 m-b-10">
                            <label class="small mb-0">Line above the item name (optional)</label>
                            <input type="text" class="form-control form-control-sm lp-set" data-k="shop_line"
                                   value="<?php echo htmlspecialchars($settings['shop_line']); ?>" placeholder="e.g. HANDLOOM GALLERY">
                        </div>
                        <div class="col-12">
                            <div class="checkbox checkbox-custom">
                                <input id="lpTrust" type="checkbox" class="lp-chk" data-k="trust_printer" value="1"
                                       <?php echo !empty($settings['trust_printer']) ? 'checked' : ''; ?>>
                                <label for="lpTrust">Use the printer's own measurement <small class="text-muted">(after teaching it the roll)</small></label>
                            </div>
                            <div class="checkbox checkbox-custom">
                                <input id="lpPrice" type="checkbox" class="lp-chk" data-k="show_price" value="1"
                                       <?php echo !empty($settings['show_price']) ? 'checked' : ''; ?>>
                                <label for="lpPrice">Print the price</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm m-t-10" id="lpSave" type="button"><i class="fa fa-save"></i> Save</button>
                    <span id="lpSaved" class="text-success m-l-10" style="display:none;">Saved.</span>
                </div>

                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-5"><i class="fa fa-lock"></i> Stop QZ Tray asking "Allow?"</h4>
                    <p class="text-muted" style="font-size:13px;">
                        QZ Tray asks before a website prints, unless the site is one it trusts. To make it trust
                        this one, once per till:
                    </p>
                    <ol style="font-size:13px;padding-left:18px;" class="m-b-0">
                        <li><a href="<?php echo base_url('label-print/downloadCert'); ?>">Download override.crt</a></li>
                        <li>Copy it into QZ Tray's folder - normally <code>C:\Program Files\QZ Tray\</code></li>
                        <li>Right-click the QZ Tray icon by the clock, choose Exit, and start QZ Tray again.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qz-tray@2.3.0/qz-tray.js"></script>
<script>
$(function(){
    var BASE = '<?php echo base_url(); ?>';
    var settings = <?php echo json_encode($settings); ?>;
    var queue = <?php echo json_encode($prefill); ?>;

    function esc(t){ return String(t==null?'':t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
    function money(v){ return (parseFloat(v)||0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function msg(text, kind){
        $('#lpMsg').removeClass().addClass('m-t-10 alert alert-' + (kind||'info')).text(text).show();
    }

    /* ---------------------------------------------------------- QZ Tray */
    var qzOk = typeof qz !== 'undefined';
    if (qzOk) {
        // Sign every request with this site's own key, so QZ Tray can be told
        // to trust the site once instead of asking before every print.
        qz.security.setCertificatePromise(function(resolve, reject){
            $.ajax({ url: BASE + 'label-print/qzCert', cache: false, dataType: 'text' }).then(resolve, reject);
        });
        qz.security.setSignatureAlgorithm('SHA512');
        qz.security.setSignaturePromise(function(toSign){
            return function(resolve, reject){
                $.post(BASE + 'label-print/qzSign', { request: toSign }, null, 'text').then(resolve, reject);
            };
        });
    }

    function setState(text, colour, help){
        $('#qzState').text(text);
        $('#qzBox').css('border-left-color', colour);
        $('#qzHelp').html(help || '');
    }

    function connect(){
        if (!qzOk) {
            setState('not loaded', '#c62828', 'The QZ Tray script could not be fetched. Check this computer is online.');
            return;
        }
        setState('connecting...', '#9e9e9e', '');
        var p = qz.websocket.isActive() ? Promise.resolve() : qz.websocket.connect({ retries: 1, delay: 1 });
        p.then(function(){
            setState('connected', '#2e7d32', 'Printing goes straight to the printer as TSPL.');
            return qz.printers.find();
        }).then(function(list){
            var $p = $('#lpPrinter').empty().prop('disabled', false);
            var keep = settings.printer || '';
            var guess = '';
            $.each(list, function(i, name){
                $p.append($('<option>').val(name).text(name));
                if (!guess && /tsc|ttp|label/i.test(name)) guess = name;
            });
            $p.val(list.indexOf(keep) >= 0 ? keep : guess || list[0]);
        }).catch(function(err){
            setState('not running', '#c62828',
                'Start QZ Tray on this computer, or install it from <a href="https://qz.io/download/" target="_blank" rel="noopener">qz.io/download</a>, then press &#8635;.');
        });
    }
    $('#lpReconnect').click(connect);
    $('#lpPrinter').change(function(){
        settings.printer = $(this).val();
        $.post(BASE + 'label-print/saveSettings', { settings: JSON.stringify({ printer: settings.printer }) });
    });

    function sendRaw(tspl, what){
        var printer = $('#lpPrinter').val();
        if (!qzOk || !qz.websocket.isActive()) { msg('QZ Tray is not connected - start it and press the refresh button.', 'danger'); return Promise.reject(); }
        if (!printer) { msg('Pick the label printer first.', 'warning'); return Promise.reject(); }
        var cfg = qz.configs.create(printer);
        // Raw: the bytes go to the printer as they are. No page, no driver
        // layout, nothing in between to change the feed length.
        return qz.print(cfg, [{ type: 'raw', format: 'command', flavor: 'plain', data: tspl }])
            .then(function(){ msg(what + ' sent to ' + printer + '.', 'success'); })
            .catch(function(e){ msg('The printer did not take it: ' + (e && e.message ? e.message : e), 'danger'); });
    }

    /* -------------------------------------------------------------- queue */
    function render(){
        var h = '', n = 0;
        $.each(queue, function(i, q){
            n += (parseInt(q.qty,10)||0);
            h += '<tr><td>'+esc(q.code)+'</td><td>'+esc(q.name)+'</td><td class="text-right">'+money(q.price)+'</td>'
               + '<td><input type="number" min="0" max="1000" class="form-control form-control-sm lp-qty" data-i="'+i+'" value="'+q.qty+'"></td>'
               + '<td><a href="javascript:;" class="btn btn-sm btn-danger lp-del" data-i="'+i+'"><i class="fa fa-times"></i></a></td></tr>';
        });
        $('#lpBody').html(h || '<tr class="lp-empty"><td colspan="5" class="text-center text-muted">Nothing yet.</td></tr>');
        var cols = parseInt(settings.columns,10) || 1;
        $('#lpTotals').text(n + (n === 1 ? ' label' : ' labels') + ' - ' + Math.ceil(n / cols) + ' rows'
            + (n % cols ? ' (last sticker left blank)' : ''));
    }
    function addItem(it, qty){
        for (var i = 0; i < queue.length; i++) {
            if (queue[i].id == it.id) { queue[i].qty = (parseInt(queue[i].qty,10)||0) + qty; render(); return; }
        }
        queue.push({ id: it.id, code: it.code, name: it.name, price: it.price, qty: qty });
        render();
    }
    $(document).on('input', '.lp-qty', function(){ queue[$(this).data('i')].qty = parseInt($(this).val(),10)||0; });
    $(document).on('change', '.lp-qty', render);
    $(document).on('click', '.lp-del', function(){ queue.splice($(this).data('i'), 1); render(); });
    $('#lpClear').click(function(){ queue = []; render(); $('#lpTspl').hide(); $('#lpMsg').hide(); });

    var picked = null;
    $('#lpSearch').autocomplete({
        source: function(req, res){
            $.getJSON(BASE + 'label-print/items', { term: req.term }, function(rows){
                res($.map(rows, function(r){ return { label: r.code + '  -  ' + r.name + '  -  Rs. ' + money(r.price), value: r.code, item: r }; }));
            });
        },
        minLength: 2,
        select: function(e, ui){ picked = ui.item.item; }
    });
    function addPicked(){
        var qty = parseInt($('#lpQty').val(),10) || 1;
        if (picked) { addItem(picked, qty); picked = null; $('#lpSearch').val('').focus(); return; }
        // Typed or scanned a code and pressed Enter: take an exact match.
        var term = $.trim($('#lpSearch').val());
        if (!term) return;
        $.getJSON(BASE + 'label-print/items', { term: term }, function(rows){
            var hit = null;
            $.each(rows, function(i, r){ if (r.code.toUpperCase() === term.toUpperCase()) hit = r; });
            if (hit) { addItem(hit, qty); $('#lpSearch').val('').focus(); }
            else msg('No item with code ' + term + '. Pick one from the list.', 'warning');
        });
    }
    $('#lpAdd').click(addPicked);
    $('#lpSearch').keydown(function(e){ if (e.key === 'Enter'){ e.preventDefault(); setTimeout(addPicked, 50); } });

    $('#lpLoadGrn').click(function(){
        var g = $.trim($('#lpGrn').val());
        if (!g) return;
        $.getJSON(BASE + 'label-print/grnItems', { grn: g }, function(r){
            if (!r.ok) { msg(r.msg, 'warning'); return; }
            $.each(r.items, function(i, it){ if (it.qty > 0) addItem(it, it.qty); });
            msg('GRN ' + g + ' loaded - ' + r.items.length + ' items.', 'success');
        });
    });

    /* ------------------------------------------------------- build/print */
    function build(){
        return $.post(BASE + 'label-print/build', { queue: JSON.stringify(queue) }, null, 'json');
    }
    $('#lpShow').click(function(){
        build().then(function(r){
            if (r.error) { msg(r.error, 'danger'); return; }
            $('#lpTspl').text(r.tspl).show();
            msg(r.labels + ' labels in ' + r.rows + ' rows.' + (r.warnings.length ? '\n' + r.warnings.join('\n') : ''), r.warnings.length ? 'warning' : 'info');
        });
    });
    $('#lpPrint').click(function(){
        var $b = $(this).prop('disabled', true);
        build().then(function(r){
            if (r.error) { msg(r.error, 'danger'); $b.prop('disabled', false); return; }
            sendRaw(r.tspl, r.labels + ' labels').then(function(){
                if (r.warnings.length) msg(r.labels + ' labels sent.\n' + r.warnings.join('\n'), 'warning');
            }).finally(function(){ $b.prop('disabled', false); });
        }, function(){ msg('Could not build the job - the server did not answer.', 'danger'); $b.prop('disabled', false); });
    });
    $('#lpTeach').click(function(){
        if (!confirm('The printer will feed two or three blank labels while it measures the roll.\nMake sure the roll you print on is loaded.')) return;
        $.post(BASE + 'label-print/teach', {}, null, 'json').then(function(r){ sendRaw(r.tspl, 'Roll measurement'); });
    });
    $('#lpCal').click(function(){
        $.post(BASE + 'label-print/calibration', {}, null, 'json').then(function(r){ sendRaw(r.tspl, 'Calibration sheet'); });
    });

    /* ------------------------------------------------------------ settings */
    $('#lpSave').click(function(){
        var s = {};
        $('.lp-set').each(function(){ s[$(this).data('k')] = $(this).val(); });
        $('.lp-chk').each(function(){ s[$(this).data('k')] = $(this).is(':checked') ? 1 : 0; });
        $.post(BASE + 'label-print/saveSettings', { settings: JSON.stringify(s) }, null, 'json').then(function(r){
            if (!r.ok) { msg(r.msg, 'danger'); return; }
            settings = r.settings; render();
            $('#lpSaved').show().delay(1500).fadeOut();
        });
    });

    render();
    connect();
});
</script>
