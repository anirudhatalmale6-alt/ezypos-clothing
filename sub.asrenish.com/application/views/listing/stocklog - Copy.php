        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
                <div class="row">                    
                        <div class="button-list col-6 col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <button id="grnBtn" type="button" class="btn btn-outline-primary waves-effect waves-light">GRN</button>
                            <button id="saleBtn"  type="button" class="btn btn-outline-primary waves-effect waves-light">Sales</button>
                            <button id="supRtrnBtn"  type="button" class="btn btn-outline-primary waves-effect waves-light">Supplier Return</button>
                            <button id="cusRtrnBtn"  type="button" class="btn btn-outline-primary waves-effect waves-light">Customer Return</button>
                        </div>
                        <div class="col-lg-5 col-lg-6 col-md-10 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group row">
                                            <label for="datepicFrom" class="col-3 col-form-label">From<span class="text-danger">*</span></label>
                                            <div class="">
                                                <input class="col-8 form-control datepic" placeholder="From.." value="" id="datepicFrom">
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
                        <div class="col-1 col-xl-1 col-lg-1">
                            <button type="reset" id="reset" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-refresh"></i></button>
                        </div>                   
                </div>
                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                          
                                                                          
                             </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>

            </div> <!-- container -->


<script>
    $( function() {
        //datepicker        
        $(".datepic").datepicker({
            onSelect: function(dateText) {
                var dateFrom= $('#datepicFrom').val(); 
                var dateTo= $('#datepicTo').val(); 
                $.ajax({
                    type: 'post',
                    url:'<?php echo base_url()?>Stocks/filterStockLogs',
                    data: {from:dateFrom,to:dateTo},
                    async:false,
                    dataType:'json',
                    success:function(data){
                  
                        var header='<thead>'+
                                    '<tr>'+ 
                                        '<th>Date</th>'+ 
                                        '<th>Code</th>'+
                                        '<th>Name</th>'+
                                        '<th>Qty</th>'+  
                                        '<th>Description</th>'+              
                                    '</tr>'+
                                '</thead>'+
                                '<tbody">'
                    var footer='</tbody>';
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){
                       var value;
                       var grn= data[i].stocklog_grnID;
                       if(grn==1){value="GRN"}
                       var sale= data[i].stocklog_saleID;
                       if(sale==1){value="Sale"}
                       var supRtrn= data[i].stocklog_return_sup_retrnID;
                       if(supRtrn==1){value="Supplier Return"}
                       var cusRtrn= data[i].stocklog_return_cus_retrnID;
                       if(cusRtrn==1){value="Customer Return"}                        
                    rows+= '<tr>'+
                                '<td>'+data[i].stocklog_createdat+'</td>'+
                                '<td>'+data[i].itm_code+'</td>'+
                                '<td>'+data[i].itm_name+'</td>'+
                                '<td style="Text-align: right;">'+data[i].stocklog_qty+'</td>'+                                
                                '<td>'+value+'</td>'+                      
                            '</tr>';
                    }
                    var table1=header+rows+footer;
                        $('#datatable-buttons').html(table1);
                        
                    },
                    error: function(){
                        alert('Error in customer cheque filtering..');
                    }
                });
            }
        });
        $(".datepic").datepicker( "option", "dateFormat", "yy-mm-dd" );

        //load GRN
        $( "#grnBtn" ).click(function() {
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>Stocks/getGRNReport',
                async:false,
                dataType:'html',
                success:function(data){
                    var header='<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th>GRN ID</th>'+
                                        '<th>GRN Code</th>'+
                                        '<th>Supplier Name</th>'+
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
                                '<td>'+data[i].grn_id+'</td>'+
                                '<td>'+data[i].grn_code+'</td>'+
                                '<td>'+data[i].sup_name+'</td>'+
                                '<td>'+data[i].grn_date+'</td>'+
                                '<td style="Text-align: right;">'+data[i].grn_subtotal+'</td>'+                                
                                '<td style="Text-align: right;">'+data[i].grn_discount+'</td>'+                                
                                '<td style="Text-align: right;">'+data[i].grn_grandtotal+'</td>'+                                                
                            '</tr>';
                    }
                    var table1=header+rows+footer;
              
                    var table = $('#datatable-buttons').DataTable();
 
                    table.clear();
                    $('#datatable-buttons').html(table1);
                    $('#datatable-buttons').DataTable().draw();

                        
            
                    
                },
                error: function(){
                    alert('error data collection');
                }
            });
        });
        
        //load Sales
        $( "#saleBtn" ).click(function() {
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>Stocks/getSaleReport',
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
                        $('#datatable-buttons').html(table1);
                    
                },
                error: function(){
                    alert('error data collection');
                }
            });
        });
        
        //load customer return
        $( "#cusRtrnBtn" ).click(function() {
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>Stocks/getCustomerReturn',
                async:false,
                dataType:'json',
                success:function(data){
                    var header='<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th>Code</th>'+
                                        '<th>Name</th>'+
                                        '<th>Date</th>'+
                                        '<th>Qty</th>'+  
                                        '<th>Customer</th>'+                     
                                    '</tr>'+
                                '</thead>'+
                                '<tbody">'
                    var footer='</tbody>';
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){                        
                    rows+= '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].itm_code+'</td>'+
                                '<td>'+data[i].itm_name+'</td>'+
                                '<td>'+data[i].cusrtrn_createdat+'</td>'+
                                '<td style="Text-align: right;">'+data[i].stocklog_qty+'</td>'+                                
                                '<td>'+data[i].cus_name+'</td>'+                          
                            '</tr>';
                    }
                    var table1=header+rows+footer;
                        $('#datatable-buttons').html(table1);
                    
                },
                error: function(){
                    alert('error data collection');
                }
            });
        });

        //Load supplier return
        $( "#supRtrnBtn" ).click(function() {
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>Stocks/getSupplierReturn',
                async:false,
                dataType:'json',
                success:function(data){
                    var header='<thead>'+
                                    '<tr>'+
                                        '<th>#</th>'+
                                        '<th>Code</th>'+
                                        '<th>Name</th>'+
                                        '<th>Date</th>'+
                                        '<th>Qty</th>'+  
                                        '<th>Supplier</th>'+                     
                                    '</tr>'+
                                '</thead>'+
                                '<tbody">'
                    var footer='</tbody>';
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){                        
                    rows+= '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].itm_code+'</td>'+
                                '<td>'+data[i].itm_name+'</td>'+
                                '<td>'+data[i].suprtrn_createdat+'</td>'+
                                '<td style="Text-align: right;">'+data[i].stocklog_qty+'</td>'+                                
                                '<td>'+data[i].sup_name+'</td>'+                          
                            '</tr>';
                    }
                    var table1=header+rows+footer;
                        $('#datatable-buttons').html(table1);
                    
                },
                error: function(){
                    alert('error data collection');
                }
            });
        });

        //Load stocklog
        function getStocklog(){
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>Stocks/getStocklog',
                async:false,
                dataType:'json',
                success:function(data){
                    var header='<thead>'+
                                    '<tr>'+ 
                                        '<th>Date</th>'+ 
                                        '<th>Code</th>'+
                                        '<th>Name</th>'+
                                        '<th>Qty</th>'+  
                                        '<th>Description</th>'+              
                                    '</tr>'+
                                '</thead>'+
                                '<tbody">'
                    var footer='</tbody>';
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){
                       var value;
                       var grn= data[i].stocklog_grnID;
                       if(grn==1){value="GRN"}
                       var sale= data[i].stocklog_saleID;
                       if(sale==1){value="Sale"}
                       var supRtrn= data[i].stocklog_return_sup_retrnID;
                       if(supRtrn==1){value="Supplier Return"}
                       var cusRtrn= data[i].stocklog_return_cus_retrnID;
                       if(cusRtrn==1){value="Customer Return"}                        
                    rows+= '<tr>'+
                                '<td>'+data[i].stocklog_createdat+'</td>'+
                                '<td>'+data[i].itm_code+'</td>'+
                                '<td>'+data[i].itm_name+'</td>'+
                                '<td style="Text-align: right;">'+data[i].stocklog_qty+'</td>'+                                
                                '<td>'+value+'</td>'+  
                                                    
                            '</tr>';
                    }
                    var table1=header+rows+footer;
                        $('#datatable-buttons').html(table1);
                    
                },
                error: function(){
                    alert('error data collection');
                }
            });
        }
        getStocklog();

         //Buttons examples
         var table = $('#datatable-buttons').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');


        //Reset filtered logs
    /*    $('#reset').click(function(){
            getStocklog();
            $('.datepic').val("");
            table
        .search( this.value )
        .draw();
        }); */

    } ); 
    $(document)
    
</script> 

  