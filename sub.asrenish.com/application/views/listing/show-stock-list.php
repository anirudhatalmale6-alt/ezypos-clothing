        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
                <div class="row">  
                    <div class="button-list col-6 col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12">
                               <select name="item_select" id="item_select" class="form-control" >
                                    <option value="all">Select Item</option>
                                    <?php foreach($all_products as $product_row){?>
                                    <option value="<?php echo $product_row['itm_id'];?>"><?php echo $product_row['itm_name'];?></option>
                                    <?php }?>
                                </select>
                    
                    </div>
                </div>
                <br>
                <div class="row">
               
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <div class="row" style="text-align: left;  font-size: large;font-weight: bold; background: gray" >
                                <div class="col-md-2">TOTAL SELLING VALUE : </div>
                                <div class="col-md-4" id="id_total_selling"></div>
                                <div class="col-md-2">TOTAL GRN VALUE : </div>
                                <div class="col-md-4" id="id_total_grn"></div>
                            </div>
                            <br>
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Qty in stock</th>    
                                                      
                                </tr>
                                </thead>
                                <tbody id="tbodyID">                                          
                                </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>
            </div> <!-- container -->


<script>
    $( function() {

         $( "#item_select" ).change(function() {
            var item_id=$('#item_select').val();
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>stocks/showAllStock_list',
                data: { item_id: item_id },
                async:false,
                dataType:'json',
                success:function(data){
                    var rows = '';
                    var i;
                    var total_grn=0;
                    var total_selling=0;
                    for(i=0; i<data.length; i++){
                        var reorderQty=parseFloat(data[i].itm_reorderlevel);
                        var stckQty=parseFloat(data[i].stock_qty);
                        var grnValue= parseFloat(data[i].grnValue).toFixed(2);
                        var sellingValue=parseFloat(data[i].sellingValue).toFixed(2);
                        if(isNaN(grnValue)){grnValue="No GRN";total_grn_error=true;}else{total_grn=parseFloat(data[i].grnValue)+total_grn;}
                        if(isNaN(sellingValue)){sellingValue="No GRN";total_selling_error=true;}else{total_selling=parseFloat(data[i].sellingValue)+total_selling;}
                        
                        var alertColor='';
                        if(stckQty>=reorderQty){
                            alertColor='style="background-color:green";';
                        }
                        else if(stckQty>0&&stckQty<reorderQty){
                            alertColor='style="background-color:orange";';
                        }
                        else{
                            alertColor='style="background-color:red";';
                        }
                    rows+= '<tr>'+
                                '<td '+alertColor+'>'+data[i].itm_id+'</td>'+
                                '<td>'+data[i].itm_code+'</td>'+
                                '<td>'+data[i].itm_name+'</td>'+
                                '<td style="Text-align: right;">'+stckQty.toFixed(2)+" "+data[i].itm_uom+'</td>'+                                                    
                            '</tr>';
                    }
                        $('#id_total_grn').html(total_grn.toFixed(2));
                        $('#id_total_selling').html(total_selling.toFixed(2));
                        $('#tbodyID').html(rows);
                    
                },
                error: function(){
                    alert('error data collection');
                }
            });
         });
        showAllStock();

         //Buttons examples
         var table = $('#datatable-buttons').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    } ); 
    $(document)
    
</script> 