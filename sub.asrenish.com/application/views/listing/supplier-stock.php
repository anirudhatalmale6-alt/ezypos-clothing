        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <div class="row" style="text-align: left">

                            <div class="col-md-4">
                                <select name="supplier_select" id="supplier_select" class="form-control" onchange>
                                    <option value="all">All Supliers</option>
                                    <?php foreach ($all_suppliers as $suppliers_row){?>
                                    <option value="<?php echo $suppliers_row['sup_id'];?>"><?php echo $suppliers_row['sup_name'];?></option>
                                    <?php }?>
                                </select>
                            </div>
                            </div>
                            <br>
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Supplier</th>    
                                    <th>Qty</th>                       
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

        
        function getSupplierStock(){
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>stocks/getSupplierStock',
                async:false,
                dataType:'json',
                success:function(data){
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){
                        
                    rows+= '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].itm_code+'</td>'+
                                '<td>'+data[i].itm_name+'</td>'+
                                '<td>'+data[i].sup_name+'</td>'+                                
                                '<td style="Text-align: right;">'+data[i].qty+'</td>'+                            
                            '</tr>';
                    }
                        $('#tbodyID').html(rows);
                    
                },
                error: function(){
                    alert('error data collection');
                }
            });
        }
        
        
      $('#supplier_select').change(function(){
                var load_id=$('#supplier_select').val();
                if(load_id=="all"){
                var load_url='<?php echo base_url();?>'+'stocks/getSupplierStock';
                 }else{
                var load_url='<?php echo base_url();?>'+'stocks/getSingleSupplierStock/'+load_id;     
                 }
                $.ajax({
                type: 'post',
                url:load_url,
                async:false,
                dataType:'json',
                success:function(data){
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){
                    rows+= '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+data[i].itm_code+'</td>'+
                                '<td>'+data[i].itm_name+'</td>'+
                                '<td>'+data[i].sup_name+'</td>'+                                
                                '<td style="Text-align: right;">'+data[i].qty+'</td>'+                            
                            '</tr>';
                    }
                        $('#tbodyID').html(rows);
                    
                },
                error: function(){
                    alert('error data collection');
                }
            });
      });
        
        
        getSupplierStock();

         //Buttons examples
         var table = $('#datatable-buttons').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    } ); 
    $(document)
    
</script> 