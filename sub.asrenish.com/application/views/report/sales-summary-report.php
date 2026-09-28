<!-- ==============================================================
     Sales Report - the totals.

     What was sold in a period, what was collected for it, on which tender,
     how much of it was gift vouchers, and what came back as returns.

     The old page of this name only ever looked a bill up and reprinted it;
     it is now called Sales Reprint and still does exactly that. This page
     is the one that answers "how did we do".
     ============================================================== -->
<div class="wrapper">
    <div class="container">

        <!-- ------------------------------------------------ filters -->
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-2 col-sm-6">
                            <label class="small mb-1">From<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="sr_from" value="<?php echo date('Y-m-01'); ?>">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label class="small mb-1">To<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="sr_to" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="small mb-1">Payment method</label>
                            <select class="form-control" id="sr_method">
                                <option value="all">All payment methods</option>
                                <option value="cash">Cash</option>
                                <?php if (!empty($paymentMethods)) { foreach ($paymentMethods as $pm) { ?>
                                <option value="<?php echo $pm->pm_id; ?>"><?php echo htmlspecialchars($pm->pm_name); ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="small mb-1">Branch</label>
                            <select class="form-control" id="sr_store">
                                <option value="all">All branches</option>
                                <?php if (!empty($storesForFilter)) { foreach ($storesForFilter as $st) { ?>
                                <option value="<?php echo $st->store_id; ?>"><?php echo htmlspecialchars($st->store_name); ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12" style="padding-top:26px;">
                            <button class="btn btn-primary" id="sr_search"><i class="fa fa-search"></i> Search</button>
                            <button class="btn btn-outline-secondary" id="sr_reset" title="Clear"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ------------------------------------------------ headline -->
        <div class="row" id="sr_summary" style="display:none;">
            <div class="col-12">
                <div class="card-box" style="background:#0d47a1;color:#fff;padding:14px 20px;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <div style="font-size:13px;opacity:.85;text-transform:uppercase;letter-spacing:1px;">Total Sales</div>
                            <div style="font-size:30px;font-weight:600;line-height:1.2;">
                                LKR <span id="sr_gross">0.00</span>
                            </div>
                        </div>
                        <div class="text-right" style="font-size:13px;opacity:.9;">
                            <div id="sr_scope"></div>
                            <div id="sr_billcount" style="margin-top:4px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="sr_cards" style="display:none;">
            <div class="col-md-8">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-list"></i> The figures</h4>
                    <table class="table table-sm mb-0">
                        <tr><td>Sales (what the bills came to)</td>
                            <td class="text-right">LKR <span id="f_gross">0.00</span></td></tr>
                        <tr><td>Discount given</td>
                            <td class="text-right">LKR <span id="f_discount">0.00</span></td></tr>
                        <tr><td>Returned to customers <small class="text-muted" id="f_retcount"></small></td>
                            <td class="text-right text-danger">- LKR <span id="f_returned">0.00</span></td></tr>
                        <tr style="font-size:17px;font-weight:600;border-top:2px solid #333;">
                            <td>Net sales</td>
                            <td class="text-right">LKR <span id="f_net">0.00</span></td></tr>
                        <tr><td colspan="2" style="padding-top:14px;"></td></tr>
                        <tr style="background:#fff8e1;">
                            <td><strong>Of which gift vouchers sold</strong>
                                <br><small class="text-muted">Already inside the sales figure above -
                                a voucher goes on the bill like any other line. Shown so it can be seen.</small></td>
                            <td class="text-right" style="font-weight:600;">LKR <span id="f_voucher">0.00</span></td></tr>
                        <tr><td>Collected against these bills</td>
                            <td class="text-right">LKR <span id="f_collected">0.00</span></td></tr>
                        <tr><td>Left on account (credit)</td>
                            <td class="text-right">LKR <span id="f_credit">0.00</span></td></tr>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-credit-card"></i> By payment method</h4>
                    <table class="table table-sm mb-0" id="sr_methods">
                        <tbody><tr><td class="text-muted text-center">Nothing to show.</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ------------------------------------------------ the bills -->
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="header-title m-t-0 m-b-0"><i class="fa fa-file-text-o"></i> Bills</h4>
                        <button class="btn btn-sm btn-outline-secondary" id="sr_print"><i class="fa fa-print"></i> Print</button>
                    </div>
                    <table id="sr_table" class="table table-striped table-bordered" width="100%">
                        <thead><tr><th>Choose the dates and press Search</th></tr></thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(function(){
    var BASE_URL = '<?php echo base_url(); ?>';

    function money(v){
        var n = parseFloat(v) || 0;
        return n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
    function esc(t){
        return String(t === null || t === undefined ? '' : t)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function load(){
        var from = $('#sr_from').val(), to = $('#sr_to').val();
        if(!from || !to){
            swal({type:'error', title:'Pick the dates', text:'Choose a From and a To date, then press Search.'});
            return;
        }
        if(from > to){
            swal({type:'error', title:'Dates the wrong way round',
                  text:'The From date is after the To date, so this range holds no days at all.'});
            return;
        }
        $('#sr_search').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Searching...');

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'Reports/getSalesSummaryData',
            data: { from: from, to: to, method: $('#sr_method').val(), store_id: $('#sr_store').val() },
            dataType: 'json',
            success: function(res){
                $('#sr_search').prop('disabled', false).html('<i class="fa fa-search"></i> Search');
                if(!res || !res.totals){ return; }
                var t = res.totals;

                $('#sr_gross').text(money(t.gross));
                $('#sr_scope').text($('#sr_method option:selected').text() + ' - '
                                    + $('#sr_store option:selected').text());
                $('#sr_billcount').text(t.bills + (t.bills === 1 ? ' bill' : ' bills')
                                        + ' from ' + from + ' to ' + to);
                $('#sr_summary').show();

                $('#f_gross').text(money(t.gross));
                $('#f_discount').text(money(t.discount));
                $('#f_returned').text(money(t.returned));
                $('#f_retcount').text(t.return_count ? '(' + t.return_count + ')' : '');
                $('#f_net').text(money(t.net));
                $('#f_voucher').text(money(t.voucher));
                $('#f_collected').text(money(t.collected));
                $('#f_credit').text(money(t.credit));
                $('#sr_cards').show();

                // by payment method
                var mh = '';
                if(res.byMethod && res.byMethod.length){
                    for(var m=0; m<res.byMethod.length; m++){
                        mh += '<tr><td>' + esc(res.byMethod[m].method_name) + '</td>'
                            + '<td class="text-right">LKR ' + money(res.byMethod[m].amount) + '</td></tr>';
                    }
                } else {
                    mh = '<tr><td class="text-muted text-center">Nothing collected in this period.</td></tr>';
                }
                $('#sr_methods tbody').html(mh);

                // the bills
                try{ $('#sr_table').DataTable().destroy(); }catch(e){}
                if(!res.bills || res.bills.length === 0){
                    $('#sr_table').html('<thead><tr><th>No sales in this period'
                        + ($('#sr_method').val() !== 'all' ? ' on that payment method' : '') + '.</th></tr></thead>');
                    return;
                }
                var h = '<thead><tr>'
                      + '<th>Bill No</th><th>Date</th><th>Branch</th><th>Customer</th>'
                      + '<th style="text-align:right;">Bill Total</th>'
                      + '<th style="text-align:right;">Voucher</th>'
                      + '<th>Paid by</th>'
                      + '<th style="text-align:right;">Collected</th>'
                      + '</tr></thead><tbody>';
                for(var i=0; i<res.bills.length; i++){
                    var b = res.bills[i];
                    h += '<tr>'
                       + '<td>' + esc(b.bill_no) + '</td>'
                       + '<td>' + esc(String(b.sale_date).substring(0,10)) + '</td>'
                       + '<td>' + esc(b.store_name || '') + '</td>'
                       + '<td>' + esc(b.cus_name || '') + '</td>'
                       + '<td style="text-align:right;">' + money(b.sale_grandtotal) + '</td>'
                       + '<td style="text-align:right;">' + (parseFloat(b.voucher_value) > 0
                             ? '<span class="badge badge-warning">' + money(b.voucher_value) + '</span>' : '-') + '</td>'
                       + '<td>' + esc(b.method_text) + '</td>'
                       + '<td style="text-align:right;">' + money(b.method_amount) + '</td>'
                       + '</tr>';
                }
                h += '</tbody>';
                $('#sr_table').html(h);
                $('#sr_table').DataTable({ pageLength: 25, order: [] });
            },
            error: function(){
                $('#sr_search').prop('disabled', false).html('<i class="fa fa-search"></i> Search');
                swal({type:'error', title:'Could not load',
                      text:'The report could not be fetched. Nothing has been changed - try again.'});
            }
        });
    }

    $('#sr_search').click(load);
    $('#sr_reset').click(function(){
        $('#sr_from').val('<?php echo date('Y-m-01'); ?>');
        $('#sr_to').val('<?php echo date('Y-m-d'); ?>');
        $('#sr_method').val('all');
        $('#sr_store').val('all');
        $('#sr_summary').hide();
        $('#sr_cards').hide();
        try{ $('#sr_table').DataTable().destroy(); }catch(e){}
        $('#sr_table').html('<thead><tr><th>Choose the dates and press Search</th></tr></thead>');
    });
    $('#sr_print').click(function(){ window.print(); });

    // Open on this month so the page is useful the moment it loads.
    load();
});
</script>
