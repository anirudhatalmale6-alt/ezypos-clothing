        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
                <div class="row">   
                         <div class="col-1 col-xl-1 col-lg-1">
                            <button  id="load_today"  class="btn btn-outline-success waves-effect waves-light">TODAY</button>
                        </div> 
                        <div class="col-1 col-xl-1 col-lg-1">
                            <button type="reset" id="reset"  class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-refresh"></i></button>
                        </div> 
                        <div class="col-lg-5 col-md-10 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label for="datepicFrom" class="col-3 col-form-label">From<span class="text-danger">*</span></label>
                                            <div class="">
                                                <input class="col-8 form-control datepic" placeholder="From.." value="" id="datepicFrom" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label for="datepicTo" class="col-3 col-form-label">To<span class="text-danger">*</span></label>
                                            <div class="">
                                                <input class="col-8 form-control datepic" placeholder="To.." value="" id="datepicTo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="button-list col-6 col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label  class="col-3 col-form-label">Select Month<span class="text-danger">*</span></label>
                                            <div class="">
                                               <input type="month" class="form-control month_select" placeholder="From.." value="" id="month_select" >
                                            </div>
                                        </div>
                                    </div>
                        </div>
                  
                                        
                </div>
                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive" id="table_div" > 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                          
                                                                          
                             </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>

            </div> <!-- container -->


<script>
    $( function() {


        //load Sales
       // $( "#saleBtn" ).click(function() {
        function load_expenses(){
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>Reports/getExpenseReport',
                async:false,
                dataType:'json',
                success:function(data){
                    var header='<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th>Expenses Category</th>'+
                                        '<th>Expense Description</th>'+
                                        '<th>Expense Date</th>'+
                                        '<th>Expense Amount</th>'+       
                                    '</tr>'+
                                '</thead>'+
                                '<tbody">'
                    var footer='</tbody>';
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){                        
                    rows+= '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].expencat_catname+'</td>'+
                                '<td>'+data[i].expen_description+'</td>'+
                                '<td>'+data[i].expen_date+'</td>'+
                                '<td style="Text-align: right;">'+data[i].expen_amount+'</td>'+                                          
                            '</tr>';
                    }
                   
                     var table1=header+rows+footer;
                    // $('#datatable-buttons').DataTable().destroy();
                     $('#datatable-buttons').html(table1);
                     init_datatabels();
                },
                error: function(){
                    alert('error data collection');
                }
                
            });
            }
      //  });
        
        load_expenses();


       $( "#reset" ).click(function() {
           $('#datatable-buttons').DataTable().destroy();
           load_expenses();
           $('#month_select').val('');
           $('#datepicFrom').val("");
           $('#datepicTo').val("");
       });
       
       $( "#reset" ).click(function() {
           $('#datatable-buttons').DataTable().destroy();
           load_expenses();
           $('#month_select').val('');
           $('#datepicFrom').val("");
           $('#datepicTo').val("");
       });
 
     
        //datepicker        
        $(".datepic").datepicker({
            onSelect: function(dateText){
                var dateFrom= $('#datepicFrom').val(); 
                var dateTo= $('#datepicTo').val();
                var load_url='<?php echo base_url()?>Reports/getExpenseReport_by_dates';

                $.ajax({
                    type: 'post',
                    url:load_url,
                    data: {from:dateFrom,to:dateTo},
                    async:false,
                    dataType:'json',
                success:function(data){
                    var header='<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th>Expenses Category</th>'+
                                        '<th>Expense Description</th>'+
                                        '<th>Expense Date</th>'+
                                        '<th>Expense Amount</th>'+       
                                    '</tr>'+
                                '</thead>'+
                                '<tbody">'
                    var footer='</tbody>';
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){                        
                    rows+= '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].expencat_catname+'</td>'+
                                '<td>'+data[i].expen_description+'</td>'+
                                '<td>'+data[i].expen_date+'</td>'+
                                '<td style="Text-align: right;">'+data[i].expen_amount+'</td>'+                                          
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
        $(".datepic").datepicker( "option", "dateFormat", "yy-mm-dd" );

           
        $('#month_select').change(function(){
                var selected_month = $('#month_select').val(); 
                $.ajax({
                    type: 'post',
                    url:'<?php echo base_url()?>Reports/getExpenseReport_by_month',
                    data: {selected_month:selected_month},
                    async:false,
                    dataType:'json',
                success:function(data){
                    var header='<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th>Expenses Category</th>'+
                                        '<th>Expense Description</th>'+
                                        '<th>Expense Date</th>'+
                                        '<th>Expense Amount</th>'+       
                                    '</tr>'+
                                '</thead>'+
                                '<tbody">'
                    var footer='</tbody>';
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){                        
                    rows+= '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].expencat_catname+'</td>'+
                                '<td>'+data[i].expen_description+'</td>'+
                                '<td>'+data[i].expen_date+'</td>'+
                                '<td style="Text-align: right;">'+data[i].expen_amount+'</td>'+                                          
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
        });
        

      
           
        $('#load_today').click(function(){
                $.ajax({
                    type: 'post',
                    url:'<?php echo base_url()?>Reports/getExpenseReport_for_today',
                    async:false,
                    dataType:'json',
                success:function(data){
                    var header='<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th>Expenses Category</th>'+
                                        '<th>Expense Description</th>'+
                                        '<th>Expense Date</th>'+
                                        '<th>Expense Amount</th>'+       
                                    '</tr>'+
                                '</thead>'+
                                '<tbody">'
                    var footer='</tbody>';
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){                        
                    rows+= '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].expencat_catname+'</td>'+
                                '<td>'+data[i].expen_description+'</td>'+
                                '<td>'+data[i].expen_date+'</td>'+
                                '<td style="Text-align: right;">'+data[i].expen_amount+'</td>'+                                          
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
        });
        

      
      
function init_datatabels(){
         //Buttons examples
         var table = $('#datatable-buttons').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

}


    } ); 

    
</script> 

  