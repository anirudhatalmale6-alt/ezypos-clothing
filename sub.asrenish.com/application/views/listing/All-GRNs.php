        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        //
         <link rel="stylesheet" href="cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
         <script src="cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <div class="wrapper">
            <div class="container">
                <!--show all grn -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box table-responsive clearfix"> 
                                <table id="datatableGrn" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Supplier</th>
                                            <th>Subtotal</th>
                                            <th>Discount</th>
                                            <th>Grandtotal</th>
                                            <th>Credit</th>
                                            <th>Cash</th>
                                            <th>Createdby</th>
                                            <th>Date</th>
                                            <th>Timestamp</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyShowGrn">                                          
                                    </tbody>
                                </table>                                
                            </div>
                        </div>                 
                    </div> 
                <!--End of show all grn -->
                <!--show grn item modal-->
                    <div class="modal fade bd-example-modal-lg" id="grnItmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-box table-responsive clearfix"> 
                                                <table id="datatableItems" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Item</th>
                                                            <th>Price</th>
                                                            <th>Quantity</th>
                                                            <th>Discount</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbodyShowItems">                                          
                                                    </tbody>
                                                </table>                                
                                            </div>
                                        </div>                 
                                    </div>    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--end of grn item modal-->

                
                <!--grn  Edit modal-->
                <div class="modal fade" id="grnEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <form id="form_edit_grn" name="" action="#" method="post">
                            <input type="hidden" name="hiddngrnID" id="hiddngrnID" value="0">
                            <input type="hidden" name="hiddnCash" id="hiddnCash" value="0">
                            <input type="hidden" name="hiddnCredit" id="hiddnCredit" value="0">
                            
                                <div class="form-group ui-front row">
                                    <label for="grnsupplier-auto"class="col-3 col-form-label">Supplier</label>
                                    <div class="col-9">
                                        <input class="form-control"  id="grnsupplier-auto" placeholder="Select" >
                                        <input type="hidden" class="form-control" name="grnsupplier" id="grnsupplier-id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="categoryid" class="col-3 col-form-label">Cash</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="" 
                                        name="Edit_cash" id="Edit_cash" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="categoryid" class="col-3 col-form-label">Credit</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="" 
                                        name="Edit_credit" id="Edit_credit" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="categoryid" class="col-3 col-form-label">Subtotal</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" 
                                        name="Edit_subtotal" id="Edit_subtotal" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="categoryid" class="col-3 col-form-label">Discount</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="" 
                                        name="Edit_discount" id="Edit_discount" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="categoryid" class="col-3 col-form-label">Grandtotal</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="" 
                                        name="Edit_grandtotal" id="Edit_grandtotal" readonly>
                                    </div>
                                </div>  
                                <div class="form-group row">
                                    <label for="categoryid" class="col-3 col-form-label">Date</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="" 
                                        name="Edit_date" id="Edit_date" required>
                                    </div>
                                </div>                                                     
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button id="btnsave" type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end of Edit modal-->
                
            </div> <!-- container -->


<script>
    $(function() {
//        $("#Edit_date" ).datepicker();
//        $("#Edit_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
        function showAllGrn(){
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>grns/loadAllGrn',
                async:false,
                dataType:'json',
                success:function(data){
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){
                    rows+= '<tr>'+
                                '<td>'+data[i].grn_id+'</td>'+
                                '<td>'+data[i].sup_name+'</td>'+
                                '<td style="Text-align: right;">'+data[i].grn_subtotal+'</td>'+
                                '<td style="Text-align: right;">'+data[i].grn_discount+'</td>'+
                                '<td style="Text-align: right;">'+data[i].grn_grandtotal+'</td>'+
                                '<td style="Text-align: right;">'+data[i].sup_pay_credit+'</td>'+
                                '<td style="Text-align: right;">'+data[i].sup_pay_cash+'</td>'+
                                '<td>'+data[i].user_name+'</td>'+
                                '<td>'+data[i].grn_date+'</td>'+
                                '<td>'+data[i].grn_createdat+'</td>'+
                                '<td>'+
                                    '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-success show_items" data="'+data[i].grn_id+'"><i class="fa fa-info-circle"></i></a>'+
                                    '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-info grn_edit" data="'+data[i].grn_id+'"><i class="fa fa-edit"></i></a>'+
                                '</td>'+
                            '</tr>';
                    }
                        $('#tbodyShowGrn').html("");
                        $('#tbodyShowGrn').html(rows);						
                },
                error: function(){
                    alert('error data collection');
                }
            });
        }
        
        showAllGrn(); 
        
        $(".show_items").click(function(){
            var id = $(this).attr('data');
            $('#grnItmModal').modal('show');
                $('#grnItmModal').find('.modal-title').text("Items");
                $.ajax({
						type: 'post',
						url: "<?php echo base_url('grns/loadGrnItems'); ?>",
						data:  {id: id},	
						async: false,
						dataType:'json',  
						success: function(res){
                            var rows = '';
                            var y;
                            for(y=0; y<res.length; y++){
                            rows+= '<tr>'+
                                        '<td>'+res[y].itm_name+'-'+res[y].itm_code+'</td>'+
                                        '<td style="Text-align: right;">'+res[y].grnitm_price+'</td>'+
                                        '<td style="Text-align: right;">'+res[y].grnitm_quantity+'</td>'+
                                        '<td style="Text-align: right;">'+res[y].grnitm_discount+'</td>'+
                                        '<td style="Text-align: right;">'+res[y].grnitm_total+'</td>'+
                                    '</tr>';
                            }
							$('#tbodyShowItems').html(rows);
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
        });
        $(".grn_edit").click(function(){
            var id = $(this).attr('data');
            $('#grnEditModal').modal('show');
            $('#grnEditModal').find('.modal-title').text("Edit GRN");
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('grns/editGrn'); ?>",
                    data:  {id: id},	
                    async: false,
                    dataType:'json',  
                    success: function(res){
                        $('input[name=hiddngrnID]').val(res.grn_id);
                        $('input[name=hiddnCash]').val(res.sup_pay_cash);
                        $('input[name=hiddnCredit]').val(res.sup_pay_credit);
                        $("#grnsupplier-auto").val(res.sup_name);
                        $('#grnsupplier-id').val(res.sup_id);
                        $('input[name=Edit_cash]').val(res.sup_pay_cash);
                        $('input[name=Edit_credit]').val(res.sup_pay_credit);
                        $('input[name=Edit_subtotal]').val(res.grn_subtotal);
                        $('input[name=Edit_discount]').val(res.grn_discount);
                        $('input[name=Edit_grandtotal]').val(res.grn_grandtotal);
                        $('input[name=Edit_date]').val(res.grn_date);
                    },
                    error: function() {
                        alert("There was an error. Try again please!");
                    }
                });//,,,sup_name, ,sup_pay_credit
        });
        //update grn
        $("#form_edit_grn").submit(function(e) {
            e.preventDefault();
            var data = $('#form_edit_grn').serialize();
            
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('grns/updateGrn'); ?>",
                    data: data,
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        showAllGrn();
                        alert("GRN Updated"); 
                        $('#grnEditModal').modal('hide');                          
                    },
                    error: function() {
                        alert("There was an error. Try again please!");
                    }
                });
        });

        //get new grandtotal for when discount change
        $("#Edit_discount").keyup(function(){
            var discnt=parseFloat($("#Edit_discount").val());
            if(isNaN(discnt)){discnt=0;}
            var subtotal=parseFloat($("#Edit_subtotal").val());
            var newGrndTotal=(subtotal-(discnt*subtotal/100)).toFixed(2);
            $("#Edit_grandtotal").val(newGrndTotal);
        });

        //get new credit for when cash change
        $("#Edit_cash").keyup(function(){
            var cashOld=parseFloat($("#hiddnCash").val());
            var creditOld=parseFloat($("#hiddnCredit").val());
            var cashNew=parseFloat($("#Edit_cash").val());
            if(isNaN(cashNew)){cashNew=0;}
            var newCredit=(cashOld-cashNew+creditOld).toFixed(2);
            $("#Edit_credit").val(newCredit);
            
            
            
        })

        //auto add two decimal 
        function decimalPlaces(num) {
            var match = (''+num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
            if (!match) { return 0; }
            return Math.max(
                0,
                // Number of digits right of decimal point.
                (match[1] ? match[1].length : 0)
                // Adjust for scientific notation.
                - (match[2] ? +match[2] : 0));
        }
        $('.DecimalFix').focusout(function(){
            var num = $(this).val();
            var DcmlDigts = decimalPlaces(num);
            if(DcmlDigts<2 && num!=''){
                var newvalue = parseFloat(num).toFixed(2);
                $(this).val(newvalue);                
            }
        });

        //autoload supliers
        var availableSuppliers = [
            <?php
            foreach ($suppliers as $supplier)
            {
            echo '{ label: "'.$supplier->sup_name.'", value:"'.$supplier->sup_id.'" },';
            }
            ?>
        ];
        $( "#grnsupplier-auto" ).autocomplete({
            source: availableSuppliers,
            select: function(event, ui) {
                    event.preventDefault();
                    $("#grnsupplier-auto").val(ui.item.label);
                    $('#grnsupplier-id').val(ui.item.value);            
                },      
        });
        

         
           //Buttons examples
         var table = $('#datatableItems').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    }); 
    $(document)
    
</script> 

  