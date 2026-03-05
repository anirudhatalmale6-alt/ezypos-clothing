        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
                <div class="row">                    
                        <div class="col-lg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">                                  
                                        <div class="form-group row">
                                            <h2>Today Summary (<?php echo date("Y-F-d",time());?>)</h2>
                                        </div>
                                </div>
                        </div>
<!--                        <div class="col-1 col-xl-1 col-lg-1">
                            <button type="reset" id="reset"  class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-refresh"></i></button>
                        </div>                   -->
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
    <tbody >
        
        <tr>
            <td style="text-align:left">SALES</td>
            <td><?php echo number_format($sale_result_total->sum_sale_grandtotal,2); ?></td>
            <td><?php echo number_format($sale_result_cash->sum_pymntlog_amount,2); ?></td>
            <td><?php echo number_format($sale_result_cheque->sum_cus_cheque_amount,2); ?></td>
            <td><?php 
            $today_sale_credit=($sale_result_total->sum_sale_grandtotal)-(($sale_result_cash->sum_pymntlog_amount)+($sale_result_cheque->sum_cus_cheque_amount));
            echo number_format($today_sale_credit,2); ?></td>
            
        </tr>
        
        <tr>
            <td style="text-align: left">PURCHASE</td>
            <td><?php echo number_format($purchase_result_total->sum_grn_grandtotal,2); ?></td>
            <td><?php echo number_format($purchase_result_cash->sum_supcash_amount,2); ?></td>
            <td><?php echo number_format($purchase_result_cheque->sum_sup_cheque_amount,2); ?></td>
            <td><?php echo number_format($purchase_result_credit->sum_sup_pay_credit,2); ?></td>
            
            
        </tr>
        
        <tr>
            <td style="text-align: left">EXPENSES</td>
            <td><?php 
            $expense_total =($expense_result_cash->expen_amount_sum);
            echo number_format($expense_total, 2);
            ?></td>
            <td><?php 
            $expense_cash =($expense_result_cash->expen_amount_sum )-($expense_result_cheque->expen_amount_cheque);
            echo number_format($expense_cash, 2);
            ?></td>
            <td><?php  echo number_format($expense_result_cheque->expen_amount_cheque,2);?></td>
            <td>-</td>
        </tr>
        
         <tr>
            <td style="text-align:left">PAYMENTS RECEIVED</td>
            <td><?php 
            $payments_total=($payment_result_cash->sum_pymntlog_amount)+($payment_result_cheque->sum_cus_cheque_amount);
            echo number_format($payments_total,2); ?></td>
            <td><?php echo number_format($payment_result_cash->sum_pymntlog_amount,2); ?></td>
            <td><?php echo number_format($payment_result_cheque->sum_cus_cheque_amount,2); ?></td>
            <td>-</td>
         </tr>
         <tr>
            <td style="text-align:left">BALANCE CASH IN HAND </td>
            <td><?php 
            $balance_cash_in_hand=($payment_result_cash->sum_pymntlog_amount-($expense_cash+$purchase_result_cash->sum_supcash_amount));
            echo number_format($balance_cash_in_hand,2); ?></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            
        </tr>
         <tr>
            <td style="text-align:left">BALANCE CHEQUE IN HAND </td>
            <td><?php 
            $balance_cheque_in_hand=($payment_result_cheque->sum_cus_cheque_amount-($expense_result_cheque->expen_amount_cheque+$purchase_result_cheque->sum_sup_cheque_amount));
            echo number_format($balance_cheque_in_hand,2); ?></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            
        </tr>
    </tbody>
</table>
                </div>
                <button onclick="PrintElem('div_page_content')">Print</button>
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

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}   
    
    
$( function() {

function init_datatabels(){
         //Buttons examples
         var table = $('#datatable-buttons').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

}

init_datatabels();
    } ); 
  
</script> 

  