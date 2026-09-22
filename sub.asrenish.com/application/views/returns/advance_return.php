<!-- ==============================================================
     Advance Exchange - scan what comes back, scan what goes out,
     settle the difference.
     Separate from Returns / Exchanges: nothing on this page reads or
     writes the ezy_pos_returns tables.
     ============================================================== -->
<div class="wrapper">
    <div class="container-fluid">

        <?php if (empty($ready)) { ?>
        <div class="alert alert-warning">
            <strong>Nearly there.</strong> The Advance Exchange tables are not in the database yet.
            Open <code>migrate.php</code> and run <strong>Step 8</strong>, then come back to this page.
        </div>
        <?php } elseif (empty($excReady)) { ?>
        <div class="alert alert-warning">
            <strong>One more step.</strong> Goods going out and payments need
            <strong>Step 9</strong> in <code>migrate.php</code>. Until then this page
            can only take goods back.
        </div>
        <?php } ?>

        <div class="row">
            <div class="col-12">
                <h4 class="page-title"><i class="fa fa-exchange"></i> Advance Exchange</h4>
                <p class="text-muted">
                    Scan what the customer is bringing back on the left, and what they are
                    taking away on the right. A bill is optional. Whatever is left to pay -
                    either way round - is settled at the bottom.
                </p>
            </div>
        </div>

        <div class="row">
            <!-- --------------------------------------------- goods coming back -->
            <div class="col-lg-5">
                <div class="card-box" style="border-top:3px solid #c62828;">
                    <h5 class="m-t-0"><i class="fa fa-undo" style="color:#c62828;"></i> Coming back</h5>
                    <div class="form-group row mb-2" style="background:#ffebee;padding:8px;border-radius:4px;">
                        <div class="col-12">
                            <input class="form-control" type="text" id="ar_scan"
                                   placeholder="Scan the item being returned" autofocus>
                        </div>
                    </div>
                    <div id="ar_search_results" class="list-group m-b-10" style="display:none;max-height:200px;overflow:auto;"></div>
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>Code</th><th>Item</th><th style="width:85px;">Qty</th>
                                   <th style="width:105px;">Price</th><th class="text-right" style="width:95px;">Total</th><th style="width:40px;"></th></tr></thead>
                        <tbody id="ar_body"><tr id="ar_empty"><td colspan="6" class="text-center text-muted">Nothing yet.</td></tr></tbody>
                    </table>
                    <div class="text-right"><strong>Returned: LKR <span id="ar_total">0.00</span></strong></div>
                </div>
            </div>

            <!-- ---------------------------------------------- goods going out -->
            <div class="col-lg-5">
                <div class="card-box" style="border-top:3px solid #2e7d32;">
                    <h5 class="m-t-0"><i class="fa fa-shopping-cart" style="color:#2e7d32;"></i> Going out</h5>
                    <div class="form-group row mb-2" style="background:#e8f5e9;padding:8px;border-radius:4px;">
                        <div class="col-12">
                            <input class="form-control" type="text" id="ax_scan"
                                   placeholder="Scan the item being taken away"
                                   <?php echo empty($excReady) ? 'disabled' : ''; ?>>
                        </div>
                    </div>
                    <div id="ax_search_results" class="list-group m-b-10" style="display:none;max-height:200px;overflow:auto;"></div>
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>Code</th><th>Item</th><th style="width:85px;">Qty</th>
                                   <th style="width:105px;">Price</th><th class="text-right" style="width:95px;">Total</th><th style="width:40px;"></th></tr></thead>
                        <tbody id="ax_body"><tr id="ax_empty"><td colspan="6" class="text-center text-muted">Nothing yet.</td></tr></tbody>
                    </table>
                    <div class="text-right"><strong>Going out: LKR <span id="ax_total">0.00</span></strong></div>
                </div>
            </div>

            <!-- ------------------------------------------------ the settlement -->
            <div class="col-lg-2">
                <div class="card-box">
                    <div class="form-group">
                        <label>Branch<span class="text-danger">*</span></label>
                        <select class="form-control form-control-sm" id="ar_store">
                            <?php foreach ($stores as $s) {
                                $sid = is_array($s) ? $s['store_id'] : $s->store_id;
                                $sn  = is_array($s) ? $s['store_name'] : $s->store_name; ?>
                                <option value="<?php echo $sid; ?>"><?php echo htmlspecialchars($sn); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Customer</label>
                        <select class="form-control form-control-sm" id="ar_customer">
                            <option value="">-- none --</option>
                            <?php if (!empty($customers)) { foreach ($customers as $c) {
                                $cid = is_array($c) ? $c['cus_id'] : $c->cus_id;
                                $cn  = is_array($c) ? $c['cus_name'] : $c->cus_name; ?>
                                <option value="<?php echo $cid; ?>"><?php echo htmlspecialchars($cn); ?></option>
                            <?php }} ?>
                        </select>
                    </div>
                    <div class="form-group" style="background:#fff8e1;border-radius:4px;padding:6px;">
                        <label style="cursor:pointer;" for="ar_nobill"><strong>No bill</strong></label>
                        <input type="checkbox" id="ar_nobill" checked style="transform:scale(1.3);float:right;margin-top:4px;">
                    </div>
                    <div id="ar_bill_box" style="display:none;">
                        <div class="form-group">
                            <label>Bill number</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="ar_bill_ref" placeholder="HG-M-0012">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="button" id="ar_find_bill"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <small class="text-muted" id="ar_bill_note"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="cursor:pointer;" for="ar_restock"><strong>Restock</strong></label>
                        <input type="checkbox" id="ar_restock" checked style="transform:scale(1.3);float:right;margin-top:4px;">
                        <small class="text-muted d-block">Untick for damaged goods.</small>
                    </div>
                    <div class="form-group">
                        <label>Reason</label>
                        <input type="text" class="form-control form-control-sm" id="ar_reason" placeholder="optional">
                    </div>
                </div>
            </div>
        </div>

        <!-- ------------------------------------------------------ settle up -->
        <div class="row">
            <div class="col-lg-7">
                <div class="card-box" id="ar_pay_box">
                    <h5 class="m-t-0"><i class="fa fa-money"></i> Settle the difference</h5>

                    <div class="row">
                        <div class="col-md-5">
                            <select class="form-control form-control-sm" id="ar_pm_select">
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <?php if (!empty($paymentMethods)) { foreach ($paymentMethods as $pm) { ?>
                                    <option value="<?php echo htmlspecialchars($pm->pm_name); ?>"><?php echo htmlspecialchars($pm->pm_name); ?></option>
                                <?php }} ?>
                                <option value="Gift Voucher">Gift Voucher</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" min="0" class="form-control form-control-sm"
                                   id="ar_pm_amount" placeholder="Amount">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-sm btn-primary btn-block" id="ar_pm_add">
                                <i class="fa fa-plus"></i> Add payment
                            </button>
                        </div>
                    </div>

                    <table class="table table-sm table-bordered m-t-10">
                        <thead><tr><th>Method</th><th>Reference</th><th class="text-right">Amount</th><th style="width:40px;"></th></tr></thead>
                        <tbody id="ar_pay_body"><tr id="ar_pay_empty"><td colspan="4" class="text-center text-muted">Nothing entered.</td></tr></tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card-box">
                    <table class="table table-sm">
                        <tr><td>Coming back</td><td class="text-right">LKR <span id="sum_ret">0.00</span></td></tr>
                        <tr><td>Going out</td><td class="text-right">LKR <span id="sum_exc">0.00</span></td></tr>
                        <tr>
                            <td style="padding-right:4px;">
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" min="0" class="form-control"
                                           id="ar_disc_value" placeholder="Discount" value="0">
                                    <select class="form-control" id="ar_disc_type" style="max-width:86px;">
                                        <option value="flat">Flat (LKR)</option>
                                        <option value="percentage">%</option>
                                    </select>
                                </div>
                            </td>
                            <td class="text-right">- LKR <span id="sum_disc">0.00</span></td>
                        </tr>
                        <tr style="font-size:18px;font-weight:600;">
                            <td id="sum_label">Nothing to settle</td>
                            <td class="text-right">LKR <span id="sum_net">0.00</span></td></tr>
                        <tr><td>Entered</td><td class="text-right">LKR <span id="sum_paid">0.00</span></td></tr>
                        <tr id="sum_left_row"><td>Still to enter</td>
                            <td class="text-right" style="font-weight:600;">LKR <span id="sum_left">0.00</span></td></tr>
                    </table>

                    <div class="form-group" id="ar_refund_mode_row" style="display:none;">
                        <label>Refund as</label>
                        <select class="form-control form-control-sm" id="ar_mode">
                            <option value="cash">Paid out (enter it above)</option>
                            <option value="store_credit">Store credit on the account</option>
                        </select>
                    </div>

                    <div id="ar_disc_note" class="text-danger" style="display:none;font-size:12px;margin-bottom:6px;">
                        The discount changed after the payments were entered - check the amounts below still add up.
                    </div>
                    <button class="btn btn-danger btn-block" id="ar_save" <?php echo empty($ready) ? 'disabled' : ''; ?>>
                        <i class="fa fa-check"></i> Complete
                    </button>
                    <a href="<?php echo base_url('advance-returns'); ?>" class="btn btn-sm btn-outline-info btn-block m-t-5">
                        <i class="fa fa-list"></i> Past advance exchanges
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reference popup, same idea as the card reference popup on the Sales window. -->
<div class="modal" id="arRefModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="arRefTitle">Reference</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p id="arRefHint" class="text-muted"></p>
                <input type="text" class="form-control" id="arRefInput" placeholder="Reference">
                <p class="m-t-5 m-b-0"><small id="arRefNote"></small></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="arRefConfirm">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){

    var EXC_READY = <?php echo empty($excReady) ? 'false' : 'true'; ?>;

    var retRows = [];   // coming back
    var excRows = [];   // going out
    var payRows = [];   // how the difference is settled

    function esc(v){
        if(v === null || typeof v === 'undefined') return '';
        return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function money(v){ return (parseFloat(v)||0).toFixed(2); }
    function sumRows(rows){
        var t = 0;
        for(var i=0;i<rows.length;i++){ t += (parseFloat(rows[i].qty)||0) * (parseFloat(rows[i].price)||0); }
        return +t.toFixed(2);
    }

    // ---------------------------------------------------------- the item lists
    function renderItems(rows, bodyId, cls, totalId){
        var html = '';
        for(var i=0;i<rows.length;i++){
            var r = rows[i], line = +((r.qty||0) * (r.price||0)).toFixed(2);
            html += '<tr>'
                 +  '<td>'+esc(r.item_code)+'</td>'
                 +  '<td><small>'+esc(r.item_name)+'</small></td>'
                 +  '<td><input type="number" class="form-control form-control-sm '+cls+'-qty" data-i="'+i+'" min="0.01" step="0.01" value="'+r.qty+'"></td>'
                 +  '<td><input type="number" class="form-control form-control-sm '+cls+'-price" data-i="'+i+'" min="0" step="0.01" value="'+r.price+'"></td>'
                 +  '<td class="text-right">'+money(line)+'</td>'
                 +  '<td><a href="javascript:;" class="btn btn-sm btn-danger '+cls+'-del" data-i="'+i+'"><i class="fa fa-times"></i></a></td>'
                 +  '</tr>';
        }
        if(rows.length === 0){
            html = '<tr><td colspan="6" class="text-center text-muted">Nothing yet.</td></tr>';
        }
        $('#'+bodyId).html(html);
        $('#'+totalId).text(money(sumRows(rows)));
        recalc();
    }
    function renderAll(){
        renderItems(retRows, 'ar_body', 'ar', 'ar_total');
        renderItems(excRows, 'ax_body', 'ax', 'ax_total');
    }

    function addTo(rows, it, render){
        for(var i=0;i<rows.length;i++){
            if(rows[i].item_id == it.itm_id){
                rows[i].qty = +(parseFloat(rows[i].qty) + 1).toFixed(2);
                render(); return;
            }
        }
        rows.push({ item_id: it.itm_id, item_code: it.itm_code, item_name: it.itm_name,
                    qty: 1, price: +(parseFloat(it.itm_sellingprice)||0).toFixed(2) });
        render();
    }

    function wireScan(inputId, resultsId, rows){
        $('#'+inputId).on('keydown', function(e){
            if(e.key !== 'Enter') return;
            e.preventDefault();
            var term = $(this).val().trim();
            if(!term) return;
            $.ajax({
                type:'POST',
                url:'<?php echo base_url("AdvanceReturn/searchItems"); ?>',
                data:{ term: term, store_id: $('#ar_store').val() },
                dataType:'json',
                success:function(res){
                    $('#'+resultsId).hide().empty();
                    if(!res || res.length === 0){
                        swal({type:'error',title:'Not found',text:'No item matches "'+term+'".'});
                        return;
                    }
                    if(res.length === 1){ addTo(rows, res[0], renderAll); $('#'+inputId).val('').focus(); return; }
                    var html = '';
                    for(var i=0;i<res.length;i++){
                        html += '<a href="javascript:;" class="list-group-item list-group-item-action pick" data-i="'+i+'">'
                             +  '<strong>'+esc(res[i].itm_code)+'</strong> '+esc(res[i].itm_name)
                             +  ' <span class="pull-right">LKR '+money(res[i].itm_sellingprice)+'</span></a>';
                    }
                    $('#'+resultsId).html(html).data('rows', res).data('target', rows).show();
                },
                error:function(){ swal({type:'error',title:'Search failed',text:'Could not reach the server.'}); }
            });
        });
        $('#'+resultsId).on('click', '.pick', function(){
            var r = $('#'+resultsId).data('rows');
            addTo($('#'+resultsId).data('target'), r[$(this).data('i')], renderAll);
            $('#'+resultsId).hide().empty();
            $('#'+inputId).val('').focus();
        });
    }
    wireScan('ar_scan', 'ar_search_results', retRows);
    if(EXC_READY){ wireScan('ax_scan', 'ax_search_results', excRows); }

    function wireEdit(cls, rows){
        $(document).on('input', '.'+cls+'-qty', function(){
            var v = parseFloat($(this).val());
            rows[$(this).data('i')].qty = (isNaN(v)||v<0) ? 0 : v;
            renderTotalsOnly();
        });
        $(document).on('input', '.'+cls+'-price', function(){
            var v = parseFloat($(this).val());
            rows[$(this).data('i')].price = (isNaN(v)||v<0) ? 0 : v;
            renderTotalsOnly();
        });
        $(document).on('click', '.'+cls+'-del', function(){
            rows.splice($(this).data('i'), 1);
            renderAll();
        });
    }
    // Totals only, so the box being typed in does not lose focus mid-keystroke.
    function renderTotalsOnly(){
        $('#ar_total').text(money(sumRows(retRows)));
        $('#ax_total').text(money(sumRows(excRows)));
        recalc();
    }
    wireEdit('ar', retRows);
    wireEdit('ax', excRows);

    // ------------------------------------------------------------- the money
    // The discount is taken off what the customer is TAKING AWAY, not off
    // what they brought back - a shop does not discount its own refund. So a
    // straight refund is never reduced by it, and a percentage is a percentage
    // of the new goods.
    function discountAmount(){
        var base = sumRows(excRows);
        var v = parseFloat($('#ar_disc_value').val());
        if(isNaN(v) || v <= 0) return 0;
        var d = ($('#ar_disc_type').val() === 'percentage') ? (base * v / 100) : v;
        if(d > base) d = base;          // never more than the goods are worth
        return +d.toFixed(2);
    }
    function net(){ return +(sumRows(excRows) - discountAmount() - sumRows(retRows)).toFixed(2); }
    function paidIn(){
        var t=0; for(var i=0;i<payRows.length;i++){ if(payRows[i].direction==='in') t+=payRows[i].amount; }
        return +t.toFixed(2);
    }
    function paidOut(){
        var t=0; for(var i=0;i<payRows.length;i++){ if(payRows[i].direction==='out') t+=payRows[i].amount; }
        return +t.toFixed(2);
    }

    function recalc(){
        var n = net();
        $('#sum_ret').text(money(sumRows(retRows)));
        $('#sum_exc').text(money(sumRows(excRows)));
        $('#sum_disc').text(money(discountAmount()));
        $('#sum_net').text(money(Math.abs(n)));

        if(n > 0.004){
            $('#sum_label').text('Customer pays').css('color','#2e7d32');
            $('#ar_refund_mode_row').hide();
            $('#sum_paid').text(money(paidIn()));
            $('#sum_left').text(money(Math.max(0, +(n - paidIn()).toFixed(2))));
            $('#sum_left_row').show();
        } else if(n < -0.004){
            $('#sum_label').text('Refund to customer').css('color','#c62828');
            $('#ar_refund_mode_row').show();
            $('#sum_paid').text(money(paidOut()));
            var need = ($('#ar_mode').val() === 'store_credit') ? 0 : Math.abs(n);
            $('#sum_left').text(money(Math.max(0, +(need - paidOut()).toFixed(2))));
            $('#sum_left_row').toggle($('#ar_mode').val() !== 'store_credit');
        } else {
            $('#sum_label').text('Nothing to settle').css('color','');
            $('#ar_refund_mode_row').hide();
            $('#sum_paid').text(money(0));
            $('#sum_left').text(money(0));
            $('#sum_left_row').hide();
        }
    }
    $('#ar_mode').change(recalc);
    // Retyping the discount changes what is owed, so the payments already
    // entered are no longer right. Say so rather than letting the two drift.
    $('#ar_disc_value, #ar_disc_type').on('input change', function(){
        recalc();
        if(payRows.length > 0){
            $('#ar_disc_note').show();
        }
    });

    function renderPayments(){
        var html = '';
        for(var i=0;i<payRows.length;i++){
            var p = payRows[i];
            html += '<tr>'
                 +  '<td>'+esc(p.method)+(p.direction==='out' ? ' <small class="text-danger">(paid out)</small>' : '')+'</td>'
                 +  '<td><small>'+esc(p.reference||'-')+'</small></td>'
                 +  '<td class="text-right">'+money(p.amount)+'</td>'
                 +  '<td><a href="javascript:;" class="btn btn-sm btn-danger pay-del" data-i="'+i+'"><i class="fa fa-times"></i></a></td>'
                 +  '</tr>';
        }
        if(payRows.length === 0){
            html = '<tr><td colspan="4" class="text-center text-muted">Nothing entered.</td></tr>';
        }
        $('#ar_pay_body').html(html);
        recalc();
    }
    $(document).on('click', '.pay-del', function(){
        payRows.splice($(this).data('i'), 1);
        renderPayments();
    });

    // A method that is not cash needs its reference before it is accepted -
    // the card machine slip, the cheque number, the voucher card. Same rule as
    // the Sales window, and what lets the Cash Flow report be tied back to a
    // piece of paper.
    function askReference(method, amount, done){
        if(method === 'Cash'){ done(''); return; }

        var isVoucher = (method === 'Gift Voucher');
        $('#arRefTitle').text(isVoucher ? 'Gift voucher' : method);
        $('#arRefHint').text(isVoucher
            ? 'Scan or type the gift card number.'
            : 'Enter the reference from the ' + method + ' slip.');
        $('#arRefInput').val('').attr('placeholder', isVoucher ? 'Card number' : 'Reference no');
        $('#arRefNote').text('');
        $('#arRefModal').modal('show');
        setTimeout(function(){ $('#arRefInput').focus(); }, 300);

        $('#arRefConfirm').off('click').on('click', function(){
            var ref = $('#arRefInput').val().trim();
            if(!ref){ $('#arRefNote').text('This is required.').css('color','#c62828'); return; }

            if(!isVoucher){ $('#arRefModal').modal('hide'); done(ref, 0); return; }

            // A voucher has to exist, be sold, and still have money on it.
            $.ajax({
                type:'POST',
                url:'<?php echo base_url("AdvanceReturn/checkVoucher"); ?>',
                data:{ card_number: ref },
                dataType:'json',
                success:function(v){
                    if(!v || !v.ok){
                        $('#arRefNote').text((v && v.msg) ? v.msg : 'That card cannot be used.').css('color','#c62828');
                        return;
                    }
                    if(amount > v.remaining + 0.004){
                        $('#arRefNote').text('That card has only ' + money(v.remaining) + ' left on it.').css('color','#c62828');
                        return;
                    }
                    // A single-use card is finished the moment it is used. Say so
                    // before it is taken, so the customer can be told rather than
                    // finding out when they come back for the rest.
                    var lost = +(v.remaining - amount).toFixed(2);
                    if(v.one_off && lost > 0.004){
                        $('#arRefModal').modal('hide');
                        swal({
                            type: 'warning',
                            title: 'Single-use card',
                            text: 'This card is worth ' + money(v.remaining) + ' and only '
                                + money(amount) + ' is being used. It is a single-use card, so the '
                                + 'remaining ' + money(lost) + ' will be lost. Take it anyway?',
                            showCancelButton: true, confirmButtonText: 'Yes, take it'
                        }).then(function(ans){
                            if(ans && ans.value){ done(v.card_number, v.gc_id); }
                        });
                        return;
                    }
                    $('#arRefModal').modal('hide');
                    done(v.card_number, v.gc_id);
                },
                error:function(){ $('#arRefNote').text('Could not reach the server.').css('color','#c62828'); }
            });
        });
    }

    $('#ar_pm_add').click(function(){
        var n = net();
        if(Math.abs(n) < 0.005){
            swal({type:'error',title:'Nothing to settle',text:'The two sides balance, so there is nothing to take or give back.'});
            return;
        }
        var method = $('#ar_pm_select').val();
        var amount = parseFloat($('#ar_pm_amount').val());
        if(!amount || amount <= 0){
            swal({type:'error',title:'Amount needed',text:'Enter how much is being taken on this method.'});
            return;
        }
        var direction = (n > 0) ? 'in' : 'out';
        if(direction === 'out' && method === 'Gift Voucher'){
            swal({type:'error',title:'Not for refunds',
                  text:'A gift voucher pays money IN. To give the refund as credit, choose Store credit on the right.'});
            return;
        }

        askReference(method, amount, function(ref, gcId){
            payRows.push({ method: method, reference: ref, amount: +amount.toFixed(2),
                           direction: direction, gc_id: gcId || 0 });
            $('#ar_pm_amount').val('');
            renderPayments();
        });
    });

    // ------------------------------------------------------ optional bill
    var arSaleId = '';
    $('#ar_nobill').change(function(){
        var without = $(this).is(':checked');
        $('#ar_bill_box').toggle(!without);
        if(without){ $('#ar_bill_ref').val(''); $('#ar_bill_note').text(''); arSaleId=''; }
    });
    $('#ar_find_bill').click(function(){
        var no = $('#ar_bill_ref').val().trim();
        if(!no) return;
        $.ajax({
            type:'POST', url:'<?php echo base_url("AdvanceReturn/findBill"); ?>',
            data:{ bill_no: no }, dataType:'json',
            success:function(res){
                if(!res.ok){ arSaleId=''; $('#ar_bill_note').text(res.msg).css('color','#c62828'); return; }
                arSaleId = res.sale.sale_id;
                $('#ar_bill_note').text('Found: ' + (res.sale.cus_name || 'no customer') + ', '
                                        + res.sale.sale_date + ', LKR ' + money(res.sale.sale_grandtotal))
                                  .css('color','#2e7d32');
                if(res.sale.sale_cus_id){ $('#ar_customer').val(res.sale.sale_cus_id); }
                if(res.sale.sale_location){ $('#ar_store').val(res.sale.sale_location); }
                if(res.sale.items && res.sale.items.length){
                    var html = '', mapped = [];
                    for(var i=0;i<res.sale.items.length;i++){
                        var it = res.sale.items[i];
                        html += '<a href="javascript:;" class="list-group-item list-group-item-action pick" data-i="'+i+'">'
                             +  '<strong>'+esc(it.itm_code)+'</strong> '+esc(it.itm_name)
                             +  ' <span class="pull-right">sold '+money(it.saleitem_quantity)+' @ '+money(it.saleitem_price)+'</span></a>';
                        mapped.push({ itm_id: it.saleitem_item_id, itm_code: it.itm_code,
                                      itm_name: it.itm_name, itm_sellingprice: it.saleitem_price });
                    }
                    $('#ar_search_results').html(html).data('rows', mapped).data('target', retRows).show();
                }
            },
            error:function(){ $('#ar_bill_note').text('Could not reach the server.').css('color','#c62828'); }
        });
    });

    // ------------------------------------------------------------- saving
    var saving = false;
    $('#ar_save').click(function(){
        if(saving) return;
        if(retRows.length === 0 && excRows.length === 0){
            swal({type:'error',title:'Nothing scanned',text:'Scan what is coming back, what is going out, or both.'});
            return;
        }
        var n = net(), mode = $('#ar_mode').val();

        if(n > 0.004 && Math.abs(paidIn() - n) > 0.05){
            swal({type:'error',title:'Collect the difference',
                  text:'The customer owes ' + money(n) + ' and ' + money(paidIn()) + ' has been entered.'});
            return;
        }
        if(n < -0.004 && mode === 'cash' && Math.abs(paidOut() - Math.abs(n)) > 0.05){
            swal({type:'error',title:'Enter the refund',
                  text:'The refund due is ' + money(Math.abs(n)) + ' and ' + money(paidOut()) + ' has been entered. '
                     + 'Add it above, or choose Store credit.'});
            return;
        }
        if(n < -0.004 && mode === 'store_credit' && !$('#ar_customer').val()){
            swal({type:'error',title:'Customer needed',text:'Store credit has to go on an account, so pick the customer.'});
            return;
        }

        var what = (n > 0.004) ? ('Take LKR ' + money(n) + ' from the customer?')
                 : (n < -0.004 ? ((mode === 'store_credit' ? 'Put LKR ' : 'Give LKR ') + money(Math.abs(n))
                                  + (mode === 'store_credit' ? ' on the account?' : ' back to the customer?'))
                               : 'Straight swap, nothing to settle?');

        swal({ title:'Complete this exchange?', text:what, type:'question',
               showCancelButton:true, confirmButtonText:'Yes, complete it' }).then(function(res){
            if(!res || !res.value) return;
            saving = true;
            $('#ar_save').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $.ajax({
                type:'POST',
                url:'<?php echo base_url("AdvanceReturn/save"); ?>',
                data:{
                    store_id: $('#ar_store').val(),
                    cus_id:   $('#ar_customer').val() || 0,
                    bill_ref: $('#ar_nobill').is(':checked') ? '' : $('#ar_bill_ref').val(),
                    sale_id:  $('#ar_nobill').is(':checked') ? '' : arSaleId,
                    refund_mode: mode,
                    discount:      $('#ar_disc_value').val() || 0,
                    discount_type: $('#ar_disc_type').val(),
                    restock:  $('#ar_restock').is(':checked') ? 1 : 0,
                    reason:   $('#ar_reason').val(),
                    lines:          JSON.stringify(retRows),
                    exchange_lines: JSON.stringify(excRows),
                    payments:       JSON.stringify(payRows)
                },
                dataType:'json',
                success:function(res){
                    saving = false;
                    $('#ar_save').prop('disabled', false).html('<i class="fa fa-check"></i> Complete');
                    if(!res || !res.ok){
                        swal({type:'error',title:'Not saved',text:(res && res.msg) ? res.msg : 'It could not be saved.'});
                        return;
                    }
                    window.open('<?php echo base_url("AdvanceReturn/slip/"); ?>' + res.id,
                                '_blank', 'width=420,height=680,scrollbars=yes');
                    swal({type:'success',title:res.ref,text:'Saved.'}).then(function(){ location.reload(); });
                },
                error:function(){
                    saving = false;
                    $('#ar_save').prop('disabled', false).html('<i class="fa fa-check"></i> Complete');
                    swal({type:'error',title:'Not saved',text:'Could not reach the server. Nothing has been changed.'});
                }
            });
        });
    });

    renderAll();
    renderPayments();
});
</script>
