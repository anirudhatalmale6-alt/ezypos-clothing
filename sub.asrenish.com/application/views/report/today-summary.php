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
                                <div class="col-4">
                                    <input class="form-control datepic" placeholder="From.." id="datepicFrom">
                                </div>
                                <div class="col-4">
                                    <input class="form-control datepic" placeholder="To.." id="datepicTo">
                                </div>
                                <div class="col-2">
                                    <button type="button" id="btnFilterSummary" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                                <div class="col-2">
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

        <tr>
            <td style="text-align:left">SALES</td>
            <td id="td_sale_total"><?php echo number_format($sale_result_total->sum_sale_grandtotal,2); ?></td>
            <td id="td_sale_cash"><?php echo number_format($sale_result_cash->sum_pymntlog_amount,2); ?></td>
            <td id="td_sale_cheque"><?php echo number_format($sale_result_cheque->sum_cus_cheque_amount,2); ?></td>
            <td id="td_sale_credit"><?php
            $today_sale_credit=($sale_result_total->sum_sale_grandtotal)-(($sale_result_cash->sum_pymntlog_amount)+($sale_result_cheque->sum_cus_cheque_amount));
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

    $('#btnFilterSummary').click(function(){
        var from = $('#datepicFrom').val();
        var to = $('#datepicTo').val();
        if(!from || !to){ return; }

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url()?>Reports/getTodaySummaryByDates',
            data: { from: from, to: to },
            dataType: 'json',
            success: function(d){
                if(d.status === 'error') return;

                $('#summaryTitle').text('Summary: ' + from + ' to ' + to);

                var saleCredit = parseFloat(d.sale_total) - (parseFloat(d.sale_cash) + parseFloat(d.sale_cheque));
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
            }
        });
    });

    $('#btnResetSummary').click(function(){
        location.reload();
    });

} );

</script>
