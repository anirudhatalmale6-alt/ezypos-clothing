<!-- ==============================================================
     Customer Payment - a customer paying off what they owe.

     Pick the customer (or type one of their bill numbers), enter what they
     paid - cash, cheque or any payment method from Masters, split if they
     paid several ways - and any discount given. The amount settles the
     opening balance and the bills oldest first; the "Settle" column can be
     changed by hand. The payment shows in the Cash Flow report on the day
     it was received.
     ============================================================== -->
<div class="wrapper">
    <div class="container-fluid">
        <?php if (!$ready): ?>
        <div class="alert alert-warning m-t-10">The database needs updating before customer payments can be saved:
            open migrate.php and run <b>step 15</b>.</div>
        <?php endif; ?>
        <div class="row">
            <!-- ------------------------------------------- the payment -->
            <div class="col-lg-5">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-money"></i> Customer Payment</h4>

                    <div class="form-group">
                        <label class="small mb-1">Customer<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cp_customer" placeholder="Type the customer's name or phone" autocomplete="off">
                        <input type="hidden" id="cp_cus_id">
                    </div>
                    <div class="form-group">
                        <label class="small mb-1">...or a bill number</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cp_bill" placeholder="e.g. HG1-0007" autocomplete="off">
                            <div class="input-group-append"><button class="btn btn-outline-primary" id="cp_bill_find" type="button"><i class="fa fa-search"></i></button></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label class="small mb-1">Payment date</label>
                            <input type="date" class="form-control" id="cp_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-6 form-group"<?php echo single_location() ? ' style="display:none;"' : ''; ?>>
                            <label class="small mb-1">Received at</label>
                            <select class="form-control" id="cp_store">
                                <?php foreach ($stores as $st): ?>
                                <option value="<?php echo (int)$st->store_id; ?>"><?php echo htmlspecialchars($st->store_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <label class="small mb-1">Paid by</label>
                    <table class="table table-sm table-bordered mb-1" id="cp_pays">
                        <thead><tr><th style="width:34%;">Method</th><th style="width:26%;">Amount</th><th>Reference / cheque</th><th style="width:34px;"></th></tr></thead>
                        <tbody></tbody>
                    </table>
                    <button class="btn btn-sm btn-outline-secondary mb-3" id="cp_add_pay" type="button"><i class="fa fa-plus"></i> Another payment method</button>

                    <div class="row">
                        <div class="col-6 form-group">
                            <label class="small mb-1">Discount given</label>
                            <input type="number" min="0" step="any" class="form-control" id="cp_discount" placeholder="0.00">
                        </div>
                        <div class="col-6 form-group">
                            <label class="small mb-1">Note</label>
                            <input type="text" class="form-control" id="cp_note" maxlength="255">
                        </div>
                    </div>
                    <div id="cp_error" class="alert alert-danger" style="display:none;white-space:pre-line;"></div>
                    <button class="btn btn-success btn-block" id="cp_save" type="button" disabled><i class="fa fa-save"></i> Save Payment</button>
                </div>
            </div>

            <!-- ------------------------------------------- what is owed -->
            <div class="col-lg-7">
                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="header-title m-t-0 m-b-0"><i class="fa fa-file-text-o"></i> What <span id="cp_cus_name">the customer</span> owes</h4>
                        <button class="btn btn-sm btn-outline-secondary" id="cp_autofill" type="button" title="Settle oldest first">Oldest first</button>
                    </div>
                    <table class="table table-sm table-bordered" id="cp_bills">
                        <thead><tr><th>Bill</th><th>Date</th><th style="text-align:right;">Bill total</th>
                            <th style="text-align:right;">Owed</th><th style="text-align:right;width:140px;">Settle</th></tr></thead>
                        <tbody><tr><td colspan="5" class="text-muted text-center">Pick a customer.</td></tr></tbody>
                    </table>
                    <table class="table table-sm mb-0" style="max-width:360px;margin-left:auto;">
                        <tr><td>Owed in all</td><td style="text-align:right;" id="cp_owed">0.00</td></tr>
                        <tr><td>Paid now</td><td style="text-align:right;" id="cp_money">0.00</td></tr>
                        <tr><td>Discount</td><td style="text-align:right;" id="cp_disc_lbl">0.00</td></tr>
                        <tr><td>Settled against the bills</td><td style="text-align:right;" id="cp_settled">0.00</td></tr>
                        <tr style="font-weight:600;border-top:2px solid #333;"><td>Still owed after this</td><td style="text-align:right;" id="cp_left">0.00</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- ------------------------------------------- recent payments -->
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-list"></i> Recent customer payments</h4>
                    <table class="table table-striped table-bordered table-sm" id="cp_recent" width="100%">
                        <thead><tr><th>Receipt</th><th>Date</th><th>Customer</th><th>Received at</th><th>Paid by</th>
                            <th style="text-align:right;">Amount</th><th style="text-align:right;">Discount</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($recent as $r): ?>
                            <tr><td><?php echo CusReceipt_model::number($r->rcpt_id); ?></td>
                                <td><?php echo htmlspecialchars($r->rcpt_date); ?></td>
                                <td><?php echo htmlspecialchars($r->cus_name); ?></td>
                                <td><?php echo htmlspecialchars($r->store_name); ?></td>
                                <td><?php echo htmlspecialchars($r->methods ? $r->methods : '-'); ?></td>
                                <td style="text-align:right;"><?php echo number_format($r->rcpt_amount, 2); ?></td>
                                <td style="text-align:right;"><?php echo number_format($r->rcpt_discount, 2); ?></td>
                                <td><a class="btn btn-sm btn-outline-secondary" target="_blank" href="<?php echo base_url('cus-receipt/'.(int)$r->rcpt_id); ?>"><i class="fa fa-print"></i></a></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    var BASE = '<?php echo base_url(); ?>';
    var CUSTOMERS = <?php echo json_encode(array_map(function($c){ return array('label' => $c->cus_name.($c->cus_contact ? ' - '.$c->cus_contact : ''), 'value' => (int)$c->cus_id); }, $customers)); ?>;
    var METHODS = <?php echo json_encode(array_map(function($m){ return array('id' => (int)$m->pm_id, 'name' => $m->pm_name); }, $methods)); ?>;
    var OWED = null;   // what the server says the customer owes

    function n(v){ v = parseFloat(v); return isNaN(v) ? 0 : v; }
    function r2(v){ return Math.round(v * 100) / 100; }
    function money(v){ return n(v).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function esc(t){ return String(t==null?'':t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    /* ---------------------------------------------------- payment lines */
    function methodOptions(){
        var h = '<option value="cash">Cash</option><option value="cheque">Cheque</option>';
        $.each(METHODS, function(i, m){ h += '<option value="pm:' + m.id + '">' + esc(m.name) + '</option>'; });
        return h;
    }
    function addPay(){
        var tr = $('<tr>').html(
            '<td><select class="form-control form-control-sm cp-m">' + methodOptions() + '</select></td>'
          + '<td><input type="number" min="0" step="any" class="form-control form-control-sm cp-a" placeholder="0.00"></td>'
          + '<td><input type="text" class="form-control form-control-sm cp-ref" placeholder="Reference (optional)">'
          + '<div class="cp-chq" style="display:none;margin-top:3px;">'
          + '<input type="text" class="form-control form-control-sm cp-bank" placeholder="Bank" style="margin-bottom:3px;">'
          + '<input type="date" class="form-control form-control-sm cp-cdate" title="Cheque date"></div></td>'
          + '<td><button type="button" class="btn btn-sm btn-outline-danger cp-del" title="Remove">&times;</button></td>');
        $('#cp_pays tbody').append(tr);
    }
    $('#cp_add_pay').click(addPay);
    $('#cp_pays').on('change', '.cp-m', function(){
        var chq = $(this).val() === 'cheque';
        var tr = $(this).closest('tr');
        tr.find('.cp-chq').toggle(chq);
        tr.find('.cp-ref').attr('placeholder', chq ? 'Cheque number' : 'Reference (optional)');
    });
    $('#cp_pays').on('click', '.cp-del', function(){
        if ($('#cp_pays tbody tr').length > 1) $(this).closest('tr').remove();
        autofill();
    });
    $('#cp_pays').on('input', '.cp-a', autofill);
    $('#cp_discount').on('input', autofill);
    addPay();

    function moneyIn(){ var t = 0; $('#cp_pays .cp-a').each(function(){ t += n($(this).val()); }); return r2(t); }

    /* ---------------------------------------------------- what is owed */
    function load(params){
        $('#cp_error').hide();
        $.getJSON(BASE + 'CustomerReceipts/outstanding', params).then(function(r){
            if (!r.ok) { $('#cp_error').text(r.msg).show(); return; }
            OWED = r;
            $('#cp_cus_id').val(r.customer.id);
            $('#cp_customer').val(r.customer.name);
            $('#cp_cus_name').text(r.customer.name);
            var h = '';
            if (r.opening > 0) {
                h += '<tr data-sale="0" data-owed="' + r.opening + '"><td colspan="2"><i>Opening balance</i></td><td></td>'
                   + '<td style="text-align:right;">' + money(r.opening) + '</td>'
                   + '<td><input type="number" min="0" step="any" class="form-control form-control-sm cp-s" style="text-align:right;"></td></tr>';
            }
            $.each(r.bills, function(i, b){
                h += '<tr data-sale="' + b.sale_id + '" data-owed="' + b.owed + '"' + (r.from_bill == b.sale_id ? ' class="table-info"' : '') + '>'
                   + '<td>' + esc(b.bill_no) + '</td><td>' + esc(b.date) + '</td>'
                   + '<td style="text-align:right;">' + money(b.total) + '</td>'
                   + '<td style="text-align:right;">' + money(b.owed) + '</td>'
                   + '<td><input type="number" min="0" step="any" class="form-control form-control-sm cp-s" style="text-align:right;"></td></tr>';
            });
            $('#cp_bills tbody').html(h || '<tr><td colspan="5" class="text-success text-center">' + esc(r.customer.name) + ' owes nothing.</td></tr>');
            autofill();
        }, function(x){ $('#cp_error').text('Could not load what the customer owes (' + x.status + ').').show(); });
    }

    $('#cp_customer').autocomplete({
        source: CUSTOMERS, minLength: 1,
        select: function(e, ui){ e.preventDefault(); $('#cp_bill').val(''); load({ cus: ui.item.value }); }
    });
    function findBill(){ var b = $.trim($('#cp_bill').val()); if (b) load({ bill: b }); }
    $('#cp_bill_find').click(findBill);
    $('#cp_bill').on('keydown', function(e){ if (e.which === 13) { e.preventDefault(); findBill(); } });

    // Spread the money and discount over the opening balance and the bills,
    // oldest first. A bill the payment was started from goes first.
    function autofill(){
        var left = r2(moneyIn() + n($('#cp_discount').val()));
        var rows = $('#cp_bills tbody tr[data-owed]').get();
        rows.sort(function(a, b){ return ($(b).hasClass('table-info') ? 1 : 0) - ($(a).hasClass('table-info') ? 1 : 0); });
        $.each(rows, function(i, tr){
            var owed = n($(tr).data('owed'));
            var take = Math.min(owed, left);
            $(tr).find('.cp-s').val(take > 0 ? take.toFixed(2) : '');
            left = r2(left - take);
        });
        recalc();
    }
    $('#cp_autofill').click(autofill);
    $('#cp_bills').on('input', '.cp-s', recalc);

    function recalc(){
        var owed = 0, settled = 0, bad = false;
        $('#cp_bills tbody tr[data-owed]').each(function(){
            var o = n($(this).data('owed')), s = n($(this).find('.cp-s').val());
            owed += o; settled += s;
            var over = s > o + 0.004 || s < 0;
            $(this).find('.cp-s').toggleClass('is-invalid', over); bad = bad || over;
        });
        var m = moneyIn(), d = n($('#cp_discount').val());
        $('#cp_owed').text(money(owed)); $('#cp_money').text(money(m)); $('#cp_disc_lbl').text(money(d));
        $('#cp_settled').text(money(settled)); $('#cp_left').text(money(owed - settled));
        var balanced = Math.abs(r2(settled) - r2(m + d)) < 0.01;
        $('#cp_settled').toggleClass('text-danger', !balanced);
        var extra = r2(m + d - owed);
        if (OWED && extra > 0.004) {
            $('#cp_error').text('That is ' + money(extra) + ' more than ' + OWED.customer.name + ' owes. Reduce the amount or the discount.').show();
        } else if ($('#cp_error').data('fromServer') !== true) { $('#cp_error').hide(); }
        $('#cp_save').prop('disabled', !OWED || bad || !balanced || (m + d) <= 0 || extra > 0.004);
    }

    /* ---------------------------------------------------- save */
    $('#cp_save').click(function(){
        var pays = [], problem = '';
        $('#cp_pays tbody tr').each(function(){
            var a = n($(this).find('.cp-a').val()); if (a <= 0) return;
            var m = $(this).find('.cp-m').val();
            var p = { method: m.indexOf('pm:') === 0 ? 'pm' : m, pm_id: m.indexOf('pm:') === 0 ? parseInt(m.substring(3), 10) : 0,
                      amount: a, reference: $.trim($(this).find('.cp-ref').val()),
                      bank: $.trim($(this).find('.cp-bank').val()), cheque_date: $(this).find('.cp-cdate').val() };
            if (p.method === 'cheque' && (!p.reference || !p.bank)) problem = 'Enter the bank and cheque number for the cheque.';
            pays.push(p);
        });
        if (problem) { $('#cp_error').text(problem).show(); return; }
        var alloc = [];
        $('#cp_bills tbody tr[data-owed]').each(function(){
            var s = n($(this).find('.cp-s').val()); if (s > 0) alloc.push({ sale_id: parseInt($(this).data('sale'), 10), amount: s });
        });
        var $b = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.post(BASE + 'CustomerReceipts/save', {
            cus_id: $('#cp_cus_id').val(), date: $('#cp_date').val(), store_id: $('#cp_store').val() || 0,
            pays: JSON.stringify(pays), discount: n($('#cp_discount').val()), alloc: JSON.stringify(alloc), note: $('#cp_note').val()
        }, null, 'json').then(function(r){
            $b.html('<i class="fa fa-save"></i> Save Payment');
            if (!r.ok) { $('#cp_error').text(r.msg).data('fromServer', true).show(); $b.prop('disabled', false); return; }
            swal({ type: 'success', title: 'Payment saved', text: 'Receipt ' + r.number,
                   showCancelButton: true, confirmButtonText: 'Print receipt', cancelButtonText: 'Close' })
              .then(function(res){
                  if (res === true || (res && res.value)) window.open(BASE + 'cus-receipt/' + r.id, '_blank');
                  location.reload();
              }, function(){ location.reload(); });
        }, function(x){
            $b.prop('disabled', false).html('<i class="fa fa-save"></i> Save Payment');
            $('#cp_error').text('The payment did not reach the server (' + x.status + '). Nothing was saved - try again.').data('fromServer', true).show();
        });
    });
    $('#cp_pays, #cp_discount, #cp_bills').on('input change', function(){ $('#cp_error').data('fromServer', false); });

    $('#cp_recent').DataTable({ pageLength: 10, order: [] });
});
</script>
