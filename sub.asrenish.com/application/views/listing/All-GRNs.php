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
                                            <th>Store</th>
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
                    <div class="modal-dialog modal-lg" role="document">
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

                                <!-- Item 2: editable GRN item list -->
                                <div class="card-box table-responsive" style="padding:10px;margin-bottom:12px;">
                                    <strong><i class="fa fa-list"></i> Items</strong>
                                    <table class="table table-sm table-bordered m-t-5" style="font-size:13px;">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th style="width:110px;">Cost Price</th>
                                                <th style="width:90px;">Qty</th>
                                                <th style="width:100px;">Discount</th>
                                                <th style="width:110px;text-align:right;">Total</th>
                                                <th style="width:40px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="grnEditItemsBody"></tbody>
                                        <tfoot>
                                            <tr class="ui-front">
                                                <td>
                                                    <input class="form-control form-control-sm" id="grnEditItemAuto" placeholder="Search item to add...">
                                                    <input type="hidden" id="grnEditItemId">
                                                </td>
                                                <td><input class="form-control form-control-sm" type="number" step="0.01" id="grnEditNewPrice" placeholder="0.00"></td>
                                                <td><input class="form-control form-control-sm" type="number" step="0.01" id="grnEditNewQty" placeholder="0"></td>
                                                <td><input class="form-control form-control-sm" type="number" step="0.01" id="grnEditNewDis" value="0"></td>
                                                <td></td>
                                                <td><button type="button" class="btn btn-sm btn-success" id="btnAddGrnEditItem" title="Add item"><i class="fa fa-plus"></i></button></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <small class="text-muted">Editing quantity/cost updates stock automatically. A line already sold from cannot be removed or dropped below the sold quantity.</small>
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
                                    <div class="col-6">
                                        <input class="form-control" type="number" step="0.01" placeholder=""
                                        name="Edit_discount" id="Edit_discount" required>
                                    </div>
                                    <div class="col-3">
                                        <select class="form-control" name="Edit_disc_type" id="Edit_disc_type">
                                            <option value="percentage">%</option>
                                            <option value="flat">Flat</option>
                                        </select>
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
                                '<td>'+(data[i].store_name ? data[i].store_name : 'N/A')+'</td>'+
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
                                    '<a href="<?php echo base_url("grn-print/"); ?>'+data[i].grn_id+'" target="_blank" style="margin-right:10px;" class="btn btn-sm btn-dark" title="Print GRN"><i class="fa fa-print"></i></a>'+
                                '</td>'+
                            '</tr>';
                    }
                        try{ $('#datatableGrn').DataTable().destroy(); }catch(e){}
                        $('#tbodyShowGrn').html("");
                        $('#tbodyShowGrn').html(rows);
                        // Item 15: search bar + sorting on All GRNs (DataTables global search
                        // covers GRN no, supplier, store, date, reference, etc.)
                        $('#datatableGrn').DataTable({ order: [[0,'desc']], pageLength: 25, destroy: true });
                },
                error: function(){
                    alert('error data collection');
                }
            });
        }
        
        showAllGrn(); 
        
        $(document).on('click', '.show_items', function(){
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
        // ---------- Item 2: full GRN edit (editable item list) ----------
        // Items available to add to a GRN (id / name / code)
        var grnEditItems = [
            <?php foreach ($items as $item){ echo '{ label: "'.addslashes($item->itm_name).' - '.addslashes($item->itm_code).'", value:"'.$item->itm_id.'" },'; } ?>
        ];
        var grnEditDeleted = []; // existing lines removed during this edit

        function nz(v){ v = parseFloat(v); return isNaN(v) ? 0 : v; }

        function grnEditRowHtml(gid, itmid, name, price, qty, dis){
            var total = (nz(price)*nz(qty) - nz(dis)); if(total<0){total=0;}
            return '<tr data-gid="'+gid+'" data-itmid="'+itmid+'">'+
                '<td>'+name+'</td>'+
                '<td><input type="number" step="0.01" class="form-control form-control-sm grn-ln-price" value="'+nz(price)+'"></td>'+
                '<td><input type="number" step="0.01" class="form-control form-control-sm grn-ln-qty" value="'+nz(qty)+'"></td>'+
                '<td><input type="number" step="0.01" class="form-control form-control-sm grn-ln-dis" value="'+nz(dis)+'"></td>'+
                '<td style="text-align:right;" class="grn-ln-total">'+total.toFixed(2)+'</td>'+
                '<td><button type="button" class="btn btn-sm btn-danger grn-ln-remove" title="Remove"><i class="fa fa-times"></i></button></td>'+
            '</tr>';
        }

        function grnEditRecalc(){
            var subtotal = 0;
            $('#grnEditItemsBody tr').each(function(){
                var p = nz($(this).find('.grn-ln-price').val());
                var q = nz($(this).find('.grn-ln-qty').val());
                var d = nz($(this).find('.grn-ln-dis').val());
                var t = p*q - d; if(t<0){t=0;}
                $(this).find('.grn-ln-total').text(t.toFixed(2));
                subtotal += t;
            });
            $('#Edit_subtotal').val(subtotal.toFixed(2));
            var disc = nz($('#Edit_discount').val());
            var grand = ($('#Edit_disc_type').val()=='flat') ? (subtotal - disc) : ((100-disc)*subtotal/100);
            if(grand<0){grand=0;}
            $('#Edit_grandtotal').val(grand.toFixed(2));
            var cash = nz($('#Edit_cash').val());
            $('#Edit_credit').val((grand - cash).toFixed(2));
        }

        $(document).on('click', '.grn_edit', function(){
            var id = $(this).attr('data');
            grnEditDeleted = [];
            $('#grnEditItemsBody').html('');
            $('#grnEditModal').modal('show');
            $('#grnEditModal').find('.modal-title').text("Edit GRN");
            // header
            $.ajax({
                    type: 'post', url: "<?php echo base_url('grns/editGrn'); ?>",
                    data:  {id: id}, async: false, dataType:'json',
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
                        $('#Edit_disc_type').val(res.grn_discount_type || 'percentage');
                        $('input[name=Edit_grandtotal]').val(res.grn_grandtotal);
                        $('input[name=Edit_date]').val(res.grn_date);
                    },
                    error: function(){ alert("There was an error. Try again please!"); }
                });
            // items
            $.ajax({
                    type: 'post', url: "<?php echo base_url('grns/grnItemsForEdit'); ?>",
                    data:  {id: id}, async: false, dataType:'json',
                    success: function(res){
                        var rows = '';
                        for(var i=0;i<res.length;i++){
                            var nm = (res[i].itm_name||'')+' - '+(res[i].itm_code||'');
                            rows += grnEditRowHtml(res[i].grnitm_id, res[i].grnitm_itemid, nm, res[i].grnitm_price, res[i].grnitm_quantity, res[i].grnitm_discount);
                        }
                        $('#grnEditItemsBody').html(rows);
                        grnEditRecalc();
                    },
                    error: function(){ alert("Could not load GRN items."); }
                });
        });

        // recalc as line fields change
        $(document).on('input', '.grn-ln-price, .grn-ln-qty, .grn-ln-dis', grnEditRecalc);
        $(document).on('keyup change', '#Edit_discount, #Edit_disc_type, #Edit_cash', grnEditRecalc);

        // remove a line
        $(document).on('click', '.grn-ln-remove', function(){
            var $tr = $(this).closest('tr');
            var gid = parseInt($tr.attr('data-gid'))||0;
            if(gid>0){
                if(!confirm('Remove this item from the GRN? This will reverse its stock. Not allowed if any of it has been sold.')) return;
                grnEditDeleted.push({gid:gid, itmid: parseInt($tr.attr('data-itmid'))||0, del:1});
            }
            $tr.remove();
            grnEditRecalc();
        });

        // add a new line
        $('#grnEditItemAuto').autocomplete({
            source: grnEditItems,
            select: function(event, ui){
                event.preventDefault();
                $('#grnEditItemAuto').val(ui.item.label);
                $('#grnEditItemId').val(ui.item.value);
            }
        });
        $('#btnAddGrnEditItem').click(function(){
            var itmid = parseInt($('#grnEditItemId').val())||0;
            var name = $('#grnEditItemAuto').val();
            var price = nz($('#grnEditNewPrice').val());
            var qty = nz($('#grnEditNewQty').val());
            var dis = nz($('#grnEditNewDis').val());
            if(itmid<=0 || !name){ alert('Pick an item from the list first.'); return; }
            if(qty<=0){ alert('Enter a quantity greater than 0.'); return; }
            $('#grnEditItemsBody').append(grnEditRowHtml(0, itmid, name, price, qty, dis));
            $('#grnEditItemAuto').val(''); $('#grnEditItemId').val('');
            $('#grnEditNewPrice').val(''); $('#grnEditNewQty').val(''); $('#grnEditNewDis').val('0');
            grnEditRecalc();
        });

        //update grn (header + items)
        $("#form_edit_grn").submit(function(e) {
            e.preventDefault();
            if($('#grnEditItemsBody tr').length === 0){ alert('A GRN must have at least one item.'); return; }
            var items = [];
            $('#grnEditItemsBody tr').each(function(){
                items.push({
                    gid: parseInt($(this).attr('data-gid'))||0,
                    itmid: parseInt($(this).attr('data-itmid'))||0,
                    price: nz($(this).find('.grn-ln-price').val()),
                    qty: nz($(this).find('.grn-ln-qty').val()),
                    dis: nz($(this).find('.grn-ln-dis').val()),
                    distype: 'flat',
                    del: 0
                });
            });
            items = items.concat(grnEditDeleted);
            var payload = $('#form_edit_grn').serializeArray();
            payload.push({name:'items', value: JSON.stringify(items)});
            $.ajax({
                    type: 'post', url: "<?php echo base_url('grns/updateGrnFull'); ?>",
                    data: payload, async: false, dataType:'json',
                    success: function(response){
                        if(response && response.status === 'error'){
                            alert(response.message || 'Could not update GRN.');
                            return;
                        }
                        showAllGrn();
                        alert("GRN Updated");
                        $('#grnEditModal').modal('hide');
                    },
                    error: function(){ alert("There was an error. Try again please!"); }
                });
        });

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

  