        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
                <div class="row">                    
                        <div class="col-lg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    
                                        <div class="form-group row">
                                            <label for="datepicFrom" class=" col-form-label">Select Month <span class="text-danger">*</span></label>
                                            <div class="">
                                                <input type="month" class="form-control month_select" placeholder="From.." value="" id="month_select" >
                                            </div>
                                        </div>
                                 

                                </div>
                        </div>
<!--                        <div class="col-1 col-xl-1 col-lg-1">
                            <button type="reset" id="reset"  class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-refresh"></i></button>
                        </div>                   -->
                </div>
                <br>
                <div class="row" style="text-align: center; font-size: large;font-weight: bold ">
                     <div class="col-md-3"></div>
                     <div class="col-md-6">
                         <div  id="div_page_content">
                    <table class="table-responsive" style="text-align: right;">
                        
                    <tr>
                        <td  style="text-align: left;"> Report Month : </td><td> <span id="div_load_month"  ></span></td> 
                    </tr>
                    <tr>
                        <td style="text-align: left;"> Total Sale Count  : </td><td> <span id="div_month_sale_count" ></span></td> 
                    </tr>
                    <tr>
                        <td style="text-align: left;"> Minimum Garnd Total Of the month  : </td><td> <span id="div_month_min_grand" ></span></td> 
                    </tr>
                    <tr>
                        <td style="text-align: left;"> Maximum Garnd Total Of the month  : </td><td> <span id="div_month_max_grand"  ></span></td> 
                    </tr>
                    
                    <tr>
                        <td > &nbsp; </td><td> <span>&nbsp;</span></td> 
                    </tr>
                    <tr>
                        <td style="text-align: left;"> Total Month Sub Total  : </td><td> <span id="div_month_sub_total"  ></span></td> 
                    </tr>
                    <tr>
                        <td style="text-align: left;"> Total Month Grand Total  : </td><td> <span id="div_month_grand_total"  ></span></td> 
                    </tr>
              
                   
                     

                    </table>
                         </div>
                          <button onclick="PrintElem('div_page_content')">Print</button>
                   </div>
                       
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


        //load Sales
       // $( "#saleBtn" ).click(function() {
        function load_this_month_sales(){
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>Reports/getSaleReport_this_month',
                async:false,
                dataType:'json',
                success:function(data){ 
                     $('#div_load_month').html("<?php echo date('Y-m'); ?>");
                     $('#div_month_sale_count').html("Sorry, No Data To Disply");
                     $('#div_month_sale_count').html(data.sales_count);
                     $('#div_month_min_grand').html("Sorry, No Data To Disply");
                     $('#div_month_min_grand').html(data.min_sale_grand);
                     $('#div_month_max_grand').html("Sorry, No Data To Disply");
                     $('#div_month_max_grand').html(data.max_sale_grand);
                     $('#div_month_sub_total').html("Sorry, No Data To Disply");
                     $('#div_month_sub_total').html(data.subtotal);
                     $('#div_month_grand_total').html("Sorry, No Data To Disply");
                     $('#div_month_grand_total').html(data.grandtotal);

                  //   $('#datatable-buttons').html(table1);
                },
                error: function(){
                    alert('error data collection');
                }
                
            });
            }
      //  });
        
        load_this_month_sales();


       $( "#reset" ).click(function() {
           $('#datatable-buttons').DataTable().destroy();
           load_sales();
           $('#customer_select').val('');
           $('#datepicFrom').val("");
           $('#datepicTo').val("");
       });
 
           
        $('#month_select').change(function(){
                var selected_month = $('#month_select').val(); 
                $.ajax({
                    type: 'post',
                    url:'<?php echo base_url()?>Reports/getSaleReport_selected_month',
                    data: {selected_month:selected_month},
                    async:false,
                    dataType:'json',
                success:function(data){ 
                     $('#div_load_month').html(selected_month);
                     $('#div_month_sale_count').html("Sorry, No Data To Disply");
                     $('#div_month_sale_count').html(data.sales_count);
                     $('#div_month_min_grand').html("Sorry, No Data To Disply");
                     $('#div_month_min_grand').html(data.min_sale_grand);
                     $('#div_month_max_grand').html("Sorry, No Data To Disply");
                     $('#div_month_max_grand').html(data.max_sale_grand);
                     $('#div_month_sub_total').html("Sorry, No Data To Disply");
                     $('#div_month_sub_total').html(data.subtotal);
                     $('#div_month_grand_total').html("Sorry, No Data To Disply");
                     $('#div_month_grand_total').html(data.grandtotal);
                },
                error: function(){
                    alert('error data collection');
                }
                });         
        });
        
//        $(".datepic").datepicker( "option", "dateFormat", "yy-mm-dd" );


        //load Customer Sales
                $( "#customer_select" ).change(function() {
              
                var load_id=$('#customer_select').val();
                
                if(load_id=="all"){
                  $('#datatable-buttons').DataTable().destroy();
                  load_sales();
                 }else{
                var load_url='<?php echo base_url()?>Reports/getSaleReport_user/'+load_id; 
                 $.ajax({
                type: 'post',
                url:load_url,
                async:false,
                dataType:'json',
                success:function(data){
                    var header='<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th>Code</th>'+
                                        '<th>Customer Name</th>'+
                                        '<th>Date</th>'+
                                        '<th>Sub Total</th>'+  
                                        '<th>Discount</th>'+  
                                        '<th>Grand Total</th>'+                   
                                    '</tr>'+
                                '</thead>'+
                                '<tbody">'
                    var footer='</tbody>';
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){                        
                    rows+= '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].sale_id+'</td>'+
                                '<td>'+data[i].cus_name+'</td>'+
                                '<td>'+data[i].sale_createdat+'</td>'+
                                '<td style="Text-align: right;">'+data[i].sale_subtotal+'</td>'+                                
                                '<td style="Text-align: right;">'+data[i].sale_discount+'</td>'+                                
                                '<td style="Text-align: right;">'+data[i].sale_grandtotal+'</td>'+                                                
                            '</tr>';
                    }
                   
                     var table1=header+rows+footer;
                     $('#datatable-buttons').DataTable().destroy();
                     $('#datatable-buttons').html(table1);
                     init_datatabels();
                },
                error: function(){
                    alert('error data collection');
                }             
            });
                 }
           
      });
      
      
function init_datatabels(){
         //Buttons examples
         var table = $('#datatable-buttons').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

}
        //Reset filtered logs
    /*    $('#reset').click(function(){
            getStocklog();
            $('.datepic').val("");
            table
        .search( this.value )
        .draw();
        }); */

    } ); 

    
</script> 

  