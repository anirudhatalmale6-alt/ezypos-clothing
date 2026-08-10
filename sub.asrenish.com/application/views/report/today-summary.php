        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
                <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h2 id="summaryTitle">Today Summary (<?php echo date("Y-F-d",time());?>)</h2>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="row">
                                <div class="col-3">
                                    <select class="form-control" id="store_select">
                                        <option value="all">All Branches</option>
                                        <?php if(isset($storesForFilter) && $storesForFilter){ foreach ($storesForFilter as $st) {
                                            $sid   = is_array($st) ? $st['store_id']   : $st->store_id;
                                            $sname = is_array($st) ? $st['store_name'] : $st->store_name; ?>
                                            <option value="<?php echo $sid; ?>"><?php echo htmlspecialchars($sname); ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input class="form-control datepic" placeholder="From.." id="datepicFrom">
                                </div>
                                <div class="col-3">
                                    <input class="form-control datepic" placeholder="To.." id="datepicTo">
                                </div>
                                <div class="col-1">
                                    <button type="button" id="btnFilterSummary" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                                <div class="col-1">
                                    <button type="button" id="btnResetSummary" class="btn btn-outline-danger"><i class="fa fa-refresh"></i></button>
                                </div>
                            </div>
                        </div>
                </div>
                <br>
                <div id="div_page_content">
    <table id="1datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%" style="text-align: center; font-size: large;text-align: right">
    <thead>
        <tr  style="text-align: right">
            <th ></th>
            <th>TOTAL</th>
            <th>CASH</th>
            <th>CHEQUE</th>
            <th>CREDIT</th>
        </tr>
    </thead>
    <tbody>

        <?php
            // Cash / Cheque figures come from the SAME engine as the Cash Flow report,
            // so the two screens always agree.
            $cf = isset($cashflow_today) ? $cashflow_today : array(
                'sale_cash'=>0,'sale_cheque'=>0,'sale_card'=>0,'sale_in'=>0,'tailoring_in'=>0,
                'return_out'=>0,'exchange_in'=>0,'total_in'=>0,'total_out'=>0,'net'=>0,
                'cash_in'=>0,'cash_out'=>0,'cash_net'=>0
            );
        ?>
        <tr>
            <td style="text-align:left">SALES</td>
            <td id="td_sale_total"><?php echo number_format($sale_result_total->sum_sale_grandtotal,2); ?></td>
            <td id="td_sale_cash"><?php echo number_format($cf['sale_cash'],2); ?></td>
            <td id="td_sale_cheque"><?php echo number_format($cf['sale_cheque'],2); ?></td>
            <td id="td_sale_credit"><?php
            $today_sale_credit=($sale_result_total->sum_sale_grandtotal)-($cf['sale_cash']+$cf['sale_cheque']+$cf['sale_card']);
            echo number_format($today_sale_credit,2); ?></td>
        </tr>

        <tr>
            <td style="text-align: left">PURCHASE</td>
            <td id="td_purchase_total"><?php echo number_format($purchase_result_total->sum_grn_grandtotal,2); ?></td>
            <td id="td_purchase_cash"><?php echo number_format($purchase_result_cash->sum_supcash_amount,2); ?></td>
            <td id="td_purchase_cheque"><?php echo number_format($purchase_result_cheque->sum_sup_cheque_amount,2); ?></td>
            <td id="td_purchase_credit"><?php echo number_format($purchase_result_credit->sum_sup_pay_credit,2); ?></td>
        </tr>

        <tr>
            <td style="text-align: left">EXPENSES</td>
            <td id="td_expense_total"><?php
            $expense_total =($expense_result_cash->expen_amount_sum);
            echo number_format($expense_total, 2);
            ?></td>
            <td id="td_expense_cash"><?php
            $expense_cash =($expense_result_cash->expen_amount_sum )-($expense_result_cheque->expen_amount_cheque);
            echo number_format($expense_cash, 2);
            ?></td>
            <td id="td_expense_cheque"><?php  echo number_format($expense_result_cheque->expen_amount_cheque,2);?></td>
            <td>-</td>
        </tr>

        <tr style="background-color:#fff3e0;">
            <td style="text-align:left;color:#c62828;"><strong>RETURNS</strong></td>
            <td id="td_returns_total" style="color:#c62828;"><?php echo number_format(isset($returns_result->total_returns) ? $returns_result->total_returns : 0, 2); ?></td>
            <td id="td_returns_count" colspan="3" style="text-align:center;color:#666;"><?php echo isset($returns_result->return_count) ? $returns_result->return_count : 0; ?> return(s)</td>
        </tr>

         <tr>
            <td style="text-align:left">PAYMENTS RECEIVED</td>
            <td id="td_payment_total"><?php
            $payments_total=($payment_result_cash->sum_pymntlog_amount)+($payment_result_cheque->sum_cus_cheque_amount);
            echo number_format($payments_total,2); ?></td>
            <td id="td_payment_cash"><?php echo number_format($payment_result_cash->sum_pymntlog_amount,2); ?></td>
            <td id="td_payment_cheque"><?php echo number_format($payment_result_cheque->sum_cus_cheque_amount,2); ?></td>
            <td>-</td>
         </tr>
         <tr>
            <td style="text-align:left">BALANCE CASH IN HAND </td>
            <td id="td_balance_cash"><?php
            $balance_cash_in_hand=($payment_result_cash->sum_pymntlog_amount-($expense_cash+$purchase_result_cash->sum_supcash_amount));
            $returns_amt = isset($returns_result->total_returns) ? $returns_result->total_returns : 0;
            $balance_cash_in_hand = $balance_cash_in_hand - $returns_amt;
            echo number_format($balance_cash_in_hand,2); ?></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr>
         <tr>
            <td style="text-align:left">BALANCE CHEQUE IN HAND </td>
            <td id="td_balance_cheque"><?php
            $balance_cheque_in_hand=($payment_result_cheque->sum_cus_cheque_amount-($expense_result_cheque->expen_amount_cheque+$purchase_result_cheque->sum_sup_cheque_amount));
            echo number_format($balance_cheque_in_hand,2); ?></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr>
    </tbody>
</table>

<!-- Cash Flow block: identical figures to the Cash Flow report for the same period -->
<h4 style="margin-top:25px;">Cash Flow</h4>
<table class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size:large;text-align:right;">
    <tbody>
        <tr>
            <td style="text-align:left">Sales (cash + cheque + card received)</td>
            <td id="td_cf_sale_in" style="color:#2e7d32;"><?php echo number_format($cf['sale_in'],2); ?></td>
        </tr>
        <tr>
            <td style="text-align:left">Tailoring order payments received</td>
            <td id="td_cf_tailoring_in" style="color:#2e7d32;"><?php echo number_format($cf['tailoring_in'],2); ?></td>
        </tr>
        <tr>
            <td style="text-align:left">Exchange top-ups (new items cost more)</td>
            <td id="td_cf_exchange_in" style="color:#2e7d32;"><?php echo number_format($cf['exchange_in'],2); ?></td>
        </tr>
        <tr>
            <td style="text-align:left">Refunds paid out on returns / exchanges</td>
            <td id="td_cf_return_out" style="color:#c62828;">-<?php echo number_format($cf['return_out'] + $cf['exchange_out'],2); ?></td>
        </tr>
        <tr style="background-color:#e8f5e9;font-weight:bold;">
            <td style="text-align:left">NET CASH FLOW</td>
            <td id="td_cf_net"><?php echo number_format($cf['net'],2); ?></td>
        </tr>
        <tr>
            <td style="text-align:left;color:#666;font-size:medium;">Of which physical cash (in <?php echo number_format($cf['cash_in'],2); ?> / out <?php echo number_format($cf['cash_out'],2); ?>)</td>
            <td id="td_cf_cash_net" style="color:#666;font-size:medium;"><?php echo number_format($cf['cash_net'],2); ?></td>
        </tr>
    </tbody>
</table>
                </div>
                <button onclick="PrintElem('div_page_content')" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                  <div class="col-md-3"></div>
                </div>

            </div> <!-- container -->


<script>

 function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');
    mywindow.document.close();
    mywindow.focus();
    mywindow.print();
    mywindow.close();
    return true;
}


$( function() {

    $(".datepic").datepicker({ dateFormat: "yy-mm-dd" });

    function fmt(n){ return parseFloat(n || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","); }

    function loadSummary(){
        var from = $('#datepicFrom').val();
        var to = $('#datepicTo').val();
        if(!from || !to){ return; }

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url()?>Reports/getTodaySummaryByDates',
            data: { from: from, to: to, store_id: $('#store_select').val() },
            dataType: 'json',
            success: function(d){
                if(d.status === 'error') return;

                $('#summaryTitle').text('Summary: ' + from + ' to ' + to);

                var saleCredit = parseFloat(d.sale_total) - (parseFloat(d.sale_cash) + parseFloat(d.sale_cheque) + parseFloat(d.sale_card || 0));
                $('#td_sale_total').text(fmt(d.sale_total));
                $('#td_sale_cash').text(fmt(d.sale_cash));
                $('#td_sale_cheque').text(fmt(d.sale_cheque));
                $('#td_sale_credit').text(fmt(saleCredit));

                $('#td_purchase_total').text(fmt(d.purchase_total));
                $('#td_purchase_cash').text(fmt(d.purchase_cash));
                $('#td_purchase_cheque').text(fmt(d.purchase_cheque));
                $('#td_purchase_credit').text(fmt(d.purchase_credit));

                var expCash = parseFloat(d.expense_total) - parseFloat(d.expense_cheque);
                $('#td_expense_total').text(fmt(d.expense_total));
                $('#td_expense_cash').text(fmt(expCash));
                $('#td_expense_cheque').text(fmt(d.expense_cheque));

                $('#td_returns_total').text(fmt(d.returns_total));
                $('#td_returns_count').text((d.returns_count || 0) + ' return(s)');

                var payTotal = parseFloat(d.payment_cash) + parseFloat(d.payment_cheque);
                $('#td_payment_total').text(fmt(payTotal));
                $('#td_payment_cash').text(fmt(d.payment_cash));
                $('#td_payment_cheque').text(fmt(d.payment_cheque));

                var balCash = parseFloat(d.payment_cash) - (expCash + parseFloat(d.purchase_cash)) - parseFloat(d.returns_total);
                $('#td_balance_cash').text(fmt(balCash));

                var balCheque = parseFloat(d.payment_cheque) - (parseFloat(d.expense_cheque) + parseFloat(d.purchase_cheque));
                $('#td_balance_cheque').text(fmt(balCheque));

                // Cash flow block
                $('#td_cf_sale_in').text(fmt(d.cf_sale_in));
                $('#td_cf_tailoring_in').text(fmt(d.cf_tailoring_in));
                $('#td_cf_exchange_in').text(fmt(d.cf_exchange_in));
                $('#td_cf_return_out').text('-' + fmt(parseFloat(d.cf_return_out || 0) + parseFloat(d.cf_exchange_out || 0)));
                $('#td_cf_net').text(fmt(d.cf_net));
                $('#td_cf_cash_net').text(fmt(d.cf_cash_net));
            }
        });
    }

    $('#btnFilterSummary').click(function(){ loadSummary(); });
    $('#store_select').change(function(){
        // With no date range picked the page still shows today's server-rendered
        // figures, so reload to apply the branch to those too.
        if($('#datepicFrom').val() && $('#datepicTo').val()){ loadSummary(); }
        else {
            var t = '<?php echo date("Y-m-d"); ?>';
            $('#datepicFrom').val(t); $('#datepicTo').val(t);
            loadSummary();
        }
    });

    $('#btnResetSummary').click(function(){
        location.reload();
    });

} );

</script>
