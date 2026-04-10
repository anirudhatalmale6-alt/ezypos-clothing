        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <!-- Add Item Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Item Details</h4>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="form-group row">
                                    <label for="codeid" class="col-3 col-form-label">Code<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Item Code" 
                                        name="code" id="codeid" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="itemid" class="col-3 col-form-label">Name<span class="text-danger">*<br>(' and " not allowed)</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="name" id="itemid" required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="category" class="col-3 col-form-label">Category<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                    <select class="form-control" name="category" required id="category">
                                        <option value="0">-Select Cetegory-</option>
                                        <?php
                                        foreach ($types as $type)
                                            {
                                           print '<option value="'.  $type->cat_id.'"> '. $type->cat_name.'</option>';
                                            }
	                                    ?>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="subCategory" class="col-3 col-form-label">Subcategory<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                    <select class="form-control" name="subCategory" required id="subCategory"> 
                                    <option value=0>-Select Sub Category-</option>                                       
                                        </select>
                                    </div>
                                </div>  
                                <div class="form-group row">
                                    <label for="brandid" class="col-3 col-form-label">Brand</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Brand" 
                                        name="brand" id="brandid">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="manufactureid" class="col-3 col-form-label">Manufacture</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Manufacture" 
                                        name="manufacture" id="manufactureid" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discriptionid" class="col-3 col-form-label">Description</label>
                                    <div class="col-9">
                                        <textarea class="form-control" placeholder="Enter Description" 
                                        name="description" id="discriptionid" rows="3" ></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="priceid" class="col-3 col-form-label">Selling Price<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Price 0.00" 
                                        name="sellingprice" id="priceid" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <!--
                                <div class="form-group row">
                                    <label for="quantityid" class="col-3 col-form-label">Quantity</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Quantity" 
                                        name="quantity" id="quantityid" data-parsley-type="number" 
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                -->                                 
                                <div class="form-group row">
                                    <label for="reorderlevel" class="col-3 col-form-label">Reorder Level</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Alerting Quantity" 
                                        name="reorderlevel" id="reorderlevel" data-parsley-type="number" 
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="uom" class="col-3 col-form-label">UOM<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Unit of Measurement" 
                                        name="uom" id="uom" required 
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="warehouse" class="col-3 col-form-label">Warehouse<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <select class="form-control" name="warehouse" id="warehouse" required>
                                            <option value="0">-Select Warehouse-</option>
                                            <!-- Options will be populated by AJAX -->
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" id="submit" class="btn btn-primary waves-effect">Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                            </form>                     
                        </div>
                    </div>
                </div>
                 <!--End of Add Item Form -->

                 <!--Start Table & row --> 
               
               <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                        <div class="d-flex justify-content-end mb-3 mr-3">
                                <button id="bulkAssignBtn" class="btn btn-warning waves-effect waves-light"><i class="fa fa-plus"></i> Bulk Assign</button>
                            </div>
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Price</th>
                                    <th>Reorder level</th>
                                    <th>UOM</th>
                                    <th>Warehouse</th>
                                    <th>Barcode</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyID">                                          
                                </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>
                 <!-- end Table & row -->
                 <!-- Item Edit Modal-->
                <div class="modal " id="EditModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editForm" name="" action="#" method="post">
                        <div class="modal-body">
                                <h4 class="header-title m-t-0 m-b-30">Edit Item Details</h4>                            
                                <input type="hidden" name="hiddenID" id="hiddenID" value="0">
                                <div class="form-group row">
                                        <label for="Edit_code" class="col-3 col-form-label">Code<span class="text-danger">*</span></label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Item Code" 
                                            name="Edit_code" id="Edit_code" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Edit_item" class="col-3 col-form-label">Name<span class="text-danger">*<br>(' and " not allowed)</span></label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Name" 
                                            name="Edit_itmname" id="Edit_item" required>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="Edit_category" class="col-3 col-form-label">Category<span class="text-danger">*</span></label>
                                        <div class="col-9">
                                            <select class="form-control" name="Edit_category" id="Edit_category" required>
                                            <option value="">-Select Parent Cetegory-</option>
                                            <?php
                                            foreach ($types as $type)
                                                {
                                            print '<option value="'.  $type->cat_id.'"> '. $type->cat_name.'</option>';
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Edit_subCategory" class="col-3 col-form-label">Subcategory</label>
                                        <div class="col-9">
                                        <select class="form-control" name="Edit_subCategory" required id="Edit_subCategory"> 
                                        <option value="">-Select a Sub Category-</option>                                       
                                        </select>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="Edit_brand" class="col-3 col-form-label">Brand</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Brand" 
                                            name="Edit_brand" id="Edit_brand" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Edit_manufacturer" class="col-3 col-form-label">Manufacturer</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Manufacture" 
                                            name="Edit_manufacturer" id="Edit_manufacturer" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Edit_discription" class="col-3 col-form-label">Description</label>
                                        <div class="col-9">
                                            <textarea class="form-control" placeholder="Enter Description" 
                                            name="Edit_description" id="Edit_discription" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Edit_price" class="col-3 col-form-label">Selling Price</label>
                                        <div class="col-9">
                                            <input class="form-control DecimalFix" type="text" placeholder="Enter Price 0.00" 
                                            name="Edit_sellingprice" id="Edit_price" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                            data-parsley-maxlength="21">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Edit_reorderlevel" class="col-3 col-form-label">Reorder Level</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Alerting Quantity" 
                                            name="Edit_reorderlevel" id="Edit_reorderlevel" data-parsley-type="number" 
                                            data-parsley-maxlength="21">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Edit_uom" class="col-3 col-form-label">UOM</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Unit of Measurement" 
                                            name="Edit_uom" id="Edit_uom" required 
                                            data-parsley-maxlength="21">
                                        </div>
                                    </div>
                                     <!-- Warehouse -->
                                    <div class="form-group row">
                                        <label for="Edit_warehouse" class="col-3 col-form-label">Warehouse<span class="text-danger">*</span></label>
                                        <div class="col-9">
                                            <select class="form-control" name="Edit_warehouse" id="Edit_warehouse" required>
                                                <option value="0">-Select Warehouse-</option>
                                                <!-- Options will be populated by AJAX -->
                                            </select>
                                        </div>
                                    </div>
                                         
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button id="" type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!--moreinfoModel-->
                <div class="modal " id="moreinfoModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">                        
                        <div class="modal-body">
                                <h4 class="header-title m-t-0 m-b-30">More informations..</h4>       
                                    <div class="form-group row">
                                        <label for="infoitmname" class="col-3 col-form-label">Name</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" 
                                            name="infoitmname" id="infoitmname" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="infobrand" class="col-3 col-form-label">Brand</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text"
                                            name="infobrand" id="infobrand" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="infomanufac" class="col-3 col-form-label">Manufacturer</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text"
                                            name="infomanufac" id="infomanufac" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="infodescription" class="col-3 col-form-label">Description</label>
                                        <div class="col-9">
                                            <textarea class="form-control" placeholder="Enter Description" 
                                            name="infodescription" id="infodescription" rows="3" ></textarea>
                                        </div>
                                    </div>                                        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>
                <!--end of moreinfoModel-->
                <!-- Barcode Modal -->
                <div class="modal" id="barcodeModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fa fa-barcode"></i> Barcode Generator</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Barcode Type</label>
                                    <select id="barcodeFormat" class="form-control">
                                        <option value="CODE128" selected>CODE 128</option>
                                        <option value="CODE39">CODE 39</option>
                                        <option value="EAN13">EAN-13</option>
                                        <option value="UPC">UPC</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Number of Labels</label>
                                    <input type="number" id="barcodeCopies" class="form-control" value="1" min="1" max="100">
                                </div>
                                <div class="col-md-4">
                                    <label>Show Price</label>
                                    <select id="barcodeShowPrice" class="form-control">
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div id="barcodePreview" class="text-center p-3 border rounded" style="background:#fff;">
                                <!-- Barcode preview renders here -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" id="exportBarcodeJpeg" class="btn btn-success"><i class="fa fa-download"></i> Export JPEG (144 DPI)</button>
                            <button type="button" id="printBarcodeBtn" class="btn btn-primary"><i class="fa fa-print"></i> Print Barcodes</button>
                        </div>
                        </div>
                    </div>
                </div>
                <!--end of barcodeModal-->
                <!-- Modal for Bulk Assign -->
                <div class="modal" id="bulkAssignModal" tabindex="-1" role="dialog" aria-labelledby="bulkAssignModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="max-width: 1200px; width: 100%;"> <!-- Ensure the width stays fixed -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bulkAssignModalLabel">Bulk Assign Items to Warehouse</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="height: 650px; display: flex;"> <!-- Use flex to align the columns -->
                                <!-- Left side (Items with checkboxes) -->
                                <div class="col-md-6" style="overflow-y: auto; height: 100%; padding-right: 15px; width: 50%;"> <!-- Fixed width with scrollable content -->
                                    <h6>Select Items</h6>
                                    <!-- Search Bar -->
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input type="text" id="searchItems" class="form-control" placeholder="Search Items" onkeyup="filterItems()">
                                        </div>
                                    </div>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> Select</th>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                            </tr>
                                        </thead>
                                        <tbody id="leftSideItems" style="max-height: 350px; overflow-y: auto;"></tbody> <!-- Allow scrolling here -->
                                    </table>
                                </div>

                                <!-- Right side (Selected items) -->
                                <div class="col-md-6" style="overflow-y: auto; height: 100%; padding-left: 15px; width: 50%;"> <!-- Fixed width with scrollable content -->
                                    <h6>Selected Items</h6>
                                    <div class="form-group">
                                        <label for="warehouseDropdown">Select Warehouse</label>
                                        <select class="form-control" id="warehouseDropdown" required>
                                            <option value="" disabled selected>-- Select Warehouse --</option>
                                        </select>
                                    </div>
                                    <table class="table" id="rightSideItems" style="max-height: 350px; overflow-y: auto;">
                                        <thead>
                                            <tr>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" id="addItemsBtn" class="btn btn-primary">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div> <!-- container -->

    
<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
    
//       $("#itemid").change(function(){
//        
//         
//          $("#itemid").value = runThis($("#itemid").val());
//              
//          function runThis(textValue){
//             return textValue.replace(/[^a-zA-Z0-9\s]/g, "");
//          }
//        });
        
    $( function() {    
        
             
            $("#itemid").change(function(){
           var textValue = $("#itemid").val();
           var nSingal = textValue.search("'");
           var nDouble = textValue.search('"');
           if(nSingal>-1 || nDouble>-1){
               alert("Sorry! Character \" and \' not allowed to enter.");
               document.getElementById("itemid").value="";
               
             }
          });
           
            $("#Edit_item").change(function(){
           var textValue = $("#Edit_item").val();
           var nSingal = textValue.search("'");
           var nDouble = textValue.search('"');
           if(nSingal>-1 || nDouble>-1){
               alert("Sorry! Character \" and \' not allowed to enter.");
               document.getElementById("Edit_item").value="";
           }
        });
        
        
        
        $('form').parsley();        

        $("#formid").submit(function(e) {
            e.preventDefault();
            var data = $('#formid').serialize();
            var itm_ID=0;
            	$.ajax({
						type: 'post',
						url: "<?php echo base_url('items/addItemPOST'); ?>",
						data: data,
						async: false,
						dataType:'json',  
						success: function(itmID){
                            if(itmID>0){
                                itm_ID=itmID;
                                $('#formid')[0].reset();
                                //showAllItems();
                                alert("Record added");
                                       // add to stock, item qty = 0     
                                        $.ajax({
                                            type: 'post',
                                            url: "<?php echo base_url('stocks/addItemtoStock'); ?>",
                                            data: {itmid: itm_ID},
                                            async: false,
                                            dataType:'json',  
                                            success: function(response){
                                            
                                            },
                                            error: function() {
                                                swal({
                                                    type: 'error',
                                                    title: 'Oops...',
                                                    text: 'Stock error. Try again please!'
                                                });                                             
                                            }
                                        });
                            location.reload();
                            }
                            else if(itmID==false){
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Item code already exists!!'
                                });
                            }                                                       
                        },
                        error: function() {
                            swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'There was an error. Try again please!'
                                });
                        }
                    });
                
            
        });
        
		// function showAllItems(){
		// 		$.ajax({
		// 			type: 'post',
		// 			url:'<?php echo base_url()?>items/showAllItems',
		// 			async:false,
		// 			dataType:'json',
		// 			success:function(data){
		// 				var rows = '';
		// 				var i;
		// 				for(i=0; i<data.length; i++){
        //                     var category=data[i].cat_name;
        //                     if(category==null||category==0){category='-'}
        //                     var subcategory=data[i].sub_cat_name;
        //                     if(subcategory==null||subcategory==0){subcategory='-'}
        //                 rows+= '<tr>'+
        //                             '<td>'+data[i].itm_code+'</td>'+
        //                             '<td>'+data[i].itm_name+'</td>'+
        //                             '<td>'+category+'</td>'+
        //                             '<td>'+subcategory+'</td>'+
        //                             '<td>'+data[i].itm_sellingprice+'</td>'+
        //                             '<td>'+data[i].itm_reorderlevel+'</td>'+
        //                             '<td style="width:30px";>'+data[i].itm_uom+'</td>'+
        //                             '<td style="width:115px";>'+
        //                             '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-success cls-info" data="'+data[i].itm_id+'"><i class="fa fa-info-circle"></i></a>'+
        //                                 '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-info cls-edit" data="'+data[i].itm_id+'"><i class="fa fa-edit"></i></a>'+
        //                                 '<a href="javascript:;" class="btn btn-sm btn-danger cls-delete" data="'+data[i].itm_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+
        //                             '</td>'+
        //                         '</tr>';
		// 				}
		// 					$('#tbodyID').html(rows);
						
		// 			},
		// 			error: function(){
		// 				alert('error data collection');
		// 			}
		// 		});
		// 	}
        function showAllItems() {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url()?>items/showAllItems',
                async: false,
                dataType: 'json',
                success: function(data) {
                    var rows = '';
                    var i;
                    for (i = 0; i < data.length; i++) {
                        var category = data[i].cat_name;
                        if (category == null || category == 0) { category = '-'; }
                        var subcategory = data[i].sub_cat_name;
                        if (subcategory == null || subcategory == 0) { subcategory = '-'; }

                        var warehouse = data[i].wh_name ? data[i].wh_name : '-'; // Display warehouse name

                        rows += '<tr>' +
                            '<td>' + data[i].itm_code + '</td>' +
                            '<td>' + data[i].itm_name + '</td>' +
                            '<td>' + category + '</td>' +
                            '<td>' + subcategory + '</td>' +
                            '<td>' + data[i].itm_sellingprice + '</td>' +
                            '<td>' + data[i].itm_reorderlevel + '</td>' +
                            '<td style="width:30px";>' + data[i].itm_uom + '</td>' +
                            '<td>' + warehouse + '</td>' +
                            '<td><a href="javascript:;" class="btn btn-sm btn-warning cls-barcode" data-code="' + data[i].itm_code + '" data-name="' + data[i].itm_name + '" data-price="' + data[i].itm_sellingprice + '"><i class="fa fa-barcode"></i></a></td>' +
                            '<td style="width:115px";>' +
                            '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-success cls-info" data="' + data[i].itm_id + '"><i class="fa fa-info-circle"></i></a>' +
                            '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-info cls-edit" data="' + data[i].itm_id + '"><i class="fa fa-edit"></i></a>' +
                            '<a href="javascript:;" class="btn btn-sm btn-danger cls-delete" data="' + data[i].itm_id + '"><i class="fa fa-times-rectangle-o"></i></a>' +
                            '</td>' +
                            '</tr>';
                    }
                    $('#tbodyID').html(rows);
                },
                error: function() {
                    alert('Error loading data');
                }
            });
        }


            //show more informations..
			$('#tbodyID').on('click', '.cls-info', function(){
                var id = $(this).attr('data');
                $('#moreinfoModel').modal('show');
                $.ajax({
						type: 'post',
						url: "<?php echo base_url('items/getmoreInfo'); ?>",
						data:  {id: id},	
						async: false,
						dataType:'json',  
						success: function(data){                           
                            $('input[name=infoitmname]').val(data.itm_name);
                            $('input[name=infobrand]').val(data.itm_brand);
                            $('input[name=infomanufac]').val(data.itm_manufacture);
                            $('textarea[name=infodescription]').val(data.itm_description);
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            });

            
            // Edit - get to view
$('#tbodyID').on('click', '.cls-edit', function(){
    var id = $(this).attr('data');
    $('#EditModel').modal('show');
    $('#EditModel').find('.modal-title').text("Editing");

    $.ajax({
        type: 'post',
        url: "<?php echo base_url('items/EditItem'); ?>",
        data: { id: id },
        async: false,
        dataType: 'json',
        success: function(data) {
            // Load subcategories based on selected category
            var catid1 = data.itm_category;
            $.ajax({
                method: 'post',
                url: '<?php echo base_url()?>Categories/viewSubCategory',
                data: { cat_id: catid1 },
                async: false,
                dataType: 'json',
                success: function(data1) {
                    var len = data1.length;
                    $('#Edit_subCategory').empty();
                    $("#Edit_subCategory").append("<option value=0>-Select a Sub Category-</option>");
                    for (var i = 0; i < len; i++) {
                        var subCatname = data1[i]['sub_cat_name'];
                        var subCatid = data1[i]['sub_cat_id'];
                        $("#Edit_subCategory").append("<option value='" + subCatid + "'>" + subCatname + "</option>");
                    }
                },
                error: function() {
                    alert('Could not load subcategories.');
                }
            });

            // Populate warehouse dropdown in the modal
            $.ajax({
                type: 'post',
                url: "<?php echo base_url('items/getWarehouses'); ?>",
                dataType: 'json',
                success: function(warehouses) {
                    var options = '<option value="0">-Select Warehouse-</option>';
                    $.each(warehouses, function(index, warehouse) {
                        var selected = (warehouse.wh_id == data.itm_warehouse_id) ? 'selected' : '';
                        options += '<option value="' + warehouse.wh_id + '" ' + selected + '>' + warehouse.wh_name + '</option>';
                    });
                    $('#Edit_warehouse').html(options);  // Populate warehouse dropdown
                },
                error: function() {
                    alert("Error loading warehouses. Please try again.");
                }
            });

            // Populate form fields with existing data
            $('input[name=Edit_code]').val(data.itm_code);
            $('input[name=Edit_itmname]').val(data.itm_name);
            $('#Edit_category').val(data.itm_category);
            $('#Edit_subCategory').val(data.itm_subcategory);
            $('input[name=Edit_brand]').val(data.itm_brand);
            $('input[name=Edit_manufacturer]').val(data.itm_manufacture);
            $('textarea[name=Edit_description]').val(data.itm_description);
            $('input[name=Edit_sellingprice]').val(data.itm_sellingprice);
            $('input[name=Edit_reorderlevel]').val(data.itm_reorderlevel);
            $('input[name=Edit_uom]').val(data.itm_uom);
            //$('input[name=Edit_quantity]').val(data.itm_quantity); 
            $('input[name=hiddenID]').val(data.itm_id);
        },
        error: function() {
            alert("There was an error. Try again please!");
        }
    });
});


      //EDit:-load subcategories on change of main category
      $("#Edit_category").change(function() {
                var cat_id= $( "#Edit_category" ).val();
                $.ajax({
					method:'post',
					url:'<?php echo base_url()?>Categories/viewSubCategory',
					data: {cat_id:cat_id},
					async:false,
					dataType:'json',
					success:function(data)	{
                        var len = data.length;
                        $('#Edit_subCategory').empty();
                        $("#Edit_subCategory").append("<option value=0>-Select a Sub Category-</option>");                        
                        for( var i = 0; i<len; i++){
                            var subCatname = data[i]['sub_cat_name'];
                            var subCatid = data[i]['sub_cat_id'];                    
                            $("#Edit_subCategory").append("<option value='"+subCatid+"'>"+subCatname+"</option>");
                        }
					},
					error: function(){
						alert('Could not Edit Data');
					}
				});
            });

            //update
            $("#editForm").submit(function(e) {
                e.preventDefault();
			    var data = $('#editForm').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('items/updateItem'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            showAllItems();
                            alert("Item Updated");
                            $('#EditModel').modal('hide');
                            location.reload();
                            
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            });

             //Delete
             $('#tbodyID').on('click', '.cls-delete', function() {
                var check = confirm("Press OK to continue delete");
                if (check == true) {
                    var id = $(this).attr('data');
                    
                    // Send AJAX request to delete both item and warehouse relationship
                    $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('items/DeleteItem'); ?>",
                        data: { id: id },
                        async: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                showAllItems();  // Reload table after deletion
                                alert("Item deleted successfully");
                            } else {
                                alert("Error deleting item.");
                            }
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
                }
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

            //load subcategories on change of main category
            $("#category").change(function() {
                var cat_id= $( "#category" ).val();
                $.ajax({
					method:'post',
					url:'<?php echo base_url()?>Categories/viewSubCategory',
					data: {cat_id:cat_id},
					async:false,
					dataType:'json',
					success:function(data)	{
                        var len = data.length;
                        $('#subCategory').empty();
                        $("#subCategory").append("<option value=0>-Select a Sub Category-</option>");                        
                        for( var i = 0; i<len; i++){
                            var subCatname = data[i]['sub_cat_name'];
                            var subCatid = data[i]['sub_cat_id'];                    
                            $("#subCategory").append("<option value='"+subCatid+"'>"+subCatname+"</option>");
                        }
					},
					error: function(){
						alert('Could not Edit Data');
					}
				});
            });
            
            //Show All Items
            showAllItems();

            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                "order": [[ 0, "desc" ]],
                buttons: ['copy', 'excel', 'pdf']
                
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    } ); 
    $(document)
    
  

    //new codes


    // Load warehouses in dropdown
function loadWarehouses() {
    $.ajax({
        type: 'post',
        url: "<?php echo base_url('items/getWarehouses'); ?>",
        dataType: 'json',
        success: function(data) {
            var options = '<option value="0">-Select Warehouse-</option>';
            for (var i = 0; i < data.length; i++) {
                options += '<option value="' + data[i].wh_id + '">' + data[i].wh_name + '</option>';
            }
            $('#warehouse').html(options);
        },
        error: function() {
            alert("Error loading warehouses. Please try again.");
        }
    });
}

// Call function to load warehouses when document is ready
$(document).ready(function() {
    loadWarehouses(); // Populate warehouse dropdown on page load
});

// Modify the Add Item form submit to save to both tables (items & item_warehouse)
$("#formid").submit(function(e) {
    e.preventDefault();
    var data = $('#formid').serialize();
    var itmID = 0;
    var warehouseID = $('#warehouse').val(); // Get selected warehouse

    if (warehouseID == 0) {
        alert("Please select a warehouse.");
        return;
    }

    // Add item to ezy_pos_items
    $.ajax({
        type: 'post',
        url: "<?php echo base_url('items/addItemPOST'); ?>",
        data: data,
        async: false,
        dataType: 'json',
        success: function(itmID) {
            if (itmID > 0) {
                itmID = itmID;
                $('#formid')[0].reset();
                alert("Record added");

                // Now, save the relationship between item and warehouse in ezy_pos_item_warehouse
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('items/saveItemWarehouse'); ?>",
                    data: {
                        itmID: itmID,
                        warehouseID: warehouseID
                    },
                    async: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log("Warehouse data saved successfully.");
                    },
                    error: function() {
                        alert("Error saving item-warehouse data. Try again.");
                    }
                });

                location.reload();
            } else if (itmID == false) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Item code already exists!!'
                });
            }
        },
        error: function() {
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'There was an error. Try again please!'
            });
        }
    });
});


$(document).ready(function() {
    // Function to validate the form before adding items
    function validateAddButton() {
        // Check if at least one item is selected
        let selectedItems = $('#rightSideItems tbody tr').length > 0;
        // Check if a valid warehouse is selected
        let warehouseId = $('#warehouseDropdown').val();
        
        if (selectedItems && warehouseId && warehouseId != '0') {
            // Enable the Add button if both conditions are met
            $('#addItemsBtn').prop('disabled', false);
        } else {
            // Disable the Add button if conditions are not met
            $('#addItemsBtn').prop('disabled', true);
        }
    }

    // Open the modal on button click
    $("#bulkAssignBtn").click(function() {
        $('#bulkAssignModal').modal('show');
        loadItems(); // Load items when modal opens
        loadWarehouses(); // Load warehouses when modal opens
    });

    // Load items dynamically into the left table
    function loadItems() {
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('items/getAllItemsForBulkAssign'); ?>',
            dataType: 'json',
            success: function(data) {
                let rows = '';
                $.each(data, function(index, item) {
                    rows += `<tr>
                        <td><input type="checkbox" class="itemCheckbox" data-id="${item.itm_id}" data-code="${item.itm_code}" data-name="${item.itm_name}" /></td>
                        <td>${item.itm_code}</td>
                        <td>${item.itm_name}</td>
                    </tr>`;
                });
                $('#leftSideItems').html(rows);
            }
        });
    }

    // Load warehouses dynamically into the dropdown
    function loadWarehouses() {
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('items/getWarehouses'); ?>',
            dataType: 'json',
            success: function(warehouses) {
                let options = '<option value="0">-Select Warehouse-</option>';
                $.each(warehouses, function(index, warehouse) {
                    options += `<option value="${warehouse.wh_id}">${warehouse.wh_name}</option>`;
                });
                $('#warehouseDropdown').html(options);
            }
        });
    }

    // Move selected items to the right side
    $('#leftSideItems').on('change', '.itemCheckbox', function() {
        let itemId = $(this).data('id');
        let itemCode = $(this).data('code');
        let itemName = $(this).data('name');
        
        if ($(this).is(':checked')) {
            let row = `<tr data-id="${itemId}">
                <td>${itemCode}</td>
                <td>${itemName}</td>
                <td><button type="button" class="btn btn-danger removeItemBtn">X</button></td>
            </tr>`;
            $('#rightSideItems tbody').append(row);
        } else {
            $(`#rightSideItems tbody tr[data-id="${itemId}"]`).remove();
        }

        // Validate if the Add button should be enabled
        validateAddButton();
    });

    // Remove item from the right side
    $('#rightSideItems').on('click', '.removeItemBtn', function() {
        let itemId = $(this).closest('tr').data('id');
        $(`#leftSideItems tbody tr input[data-id="${itemId}"]`).prop('checked', false); // Uncheck left side
        $(this).closest('tr').remove(); // Remove from right side

        // Validate if the Add button should be enabled
        validateAddButton();
    });

    // Enable/Disable Add button based on selection
    $('#warehouseDropdown').on('change', function() {
        validateAddButton(); // Re-check the button state on warehouse selection change
    });

    // Add selected items to warehouse
    $('#addItemsBtn').click(function() {
        let selectedItems = [];
        $('#rightSideItems tbody tr').each(function() {
            selectedItems.push($(this).data('id'));
        });

        let warehouseId = $('#warehouseDropdown').val();

        if (selectedItems.length == 0) {
            alert('Please select at least one item.');
            return;
        }

        if (warehouseId == "" || warehouseId == "0") {
            alert('Please select a warehouse.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('items/bulkAssignItemsToWarehouse'); ?>',
            data: {
                itemIds: selectedItems,
                warehouseId: warehouseId
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert('Items successfully assigned to warehouse.');
                    $('#bulkAssignModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error assigning items to warehouse.');
                }
            }
        });
    });

    // Initial validation to disable the "Add" button
    validateAddButton();
});

// Function to filter the items based on the search input
function filterItems() {
    var input, filter, table, tr, td, i, txtValue, itemCode, itemName;
    input = document.getElementById("searchItems");
    filter = input.value.toUpperCase();
    table = document.getElementById("leftSideItems");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those that don't match the search
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");

        if (td) {
            itemCode = td[1]; // Item Code column
            itemName = td[2]; // Item Name column
            
            // Get the text content of both columns
            var itemCodeText = itemCode.textContent || itemCode.innerText;
            var itemNameText = itemName.textContent || itemName.innerText;

            // Check if the search term matches either the Item Code or Item Name
            if (itemCodeText.toUpperCase().indexOf(filter) > -1 || itemNameText.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

// Load items dynamically into the left table
function loadItems() {
    $.ajax({
        type: 'GET',
        url: '<?php echo base_url('items/getAllItemsForBulkAssign'); ?>',
        dataType: 'json',
        success: function(data) {
            let rows = '';
            $.each(data, function(index, item) {
                rows += `<tr>
                    <td><input type="checkbox" class="itemCheckbox" data-id="${item.itm_id}" data-code="${item.itm_code}" data-name="${item.itm_name}" /></td>
                    <td>${item.itm_code}</td>
                    <td>${item.itm_name}</td>
                </tr>`;
            });
            $('#leftSideItems').html(rows); // Populate the left side items table
        }
    });
}

// Initial loading of items
loadItems();

</script>
<script src="<?php echo base_url().'assets/js/JsBarcode.all.min.js'?>"></script>
<script>
// Barcode generation
var currentBarcodeData = {};

$('#tbodyID').on('click', '.cls-barcode', function(){
    currentBarcodeData = {
        code: $(this).data('code'),
        name: $(this).data('name'),
        price: $(this).data('price')
    };
    $('#barcodeModal').modal('show');
    generateBarcodePreview();
});

$('#barcodeFormat, #barcodeCopies, #barcodeShowPrice').on('change', function(){
    generateBarcodePreview();
});

function generateBarcodePreview(){
    var format = $('#barcodeFormat').val();
    var copies = parseInt($('#barcodeCopies').val()) || 1;
    var showPrice = $('#barcodeShowPrice').val() == '1';
    var code = currentBarcodeData.code;
    var name = currentBarcodeData.name;
    var price = currentBarcodeData.price;

    var html = '<div style="display:flex;flex-wrap:wrap;justify-content:center;gap:10px;">';
    for(var i = 0; i < Math.min(copies, 20); i++){
        html += '<div class="barcode-label" style="border:1px dashed #ccc;padding:8px;text-align:center;width:220px;">';
        html += '<div style="font-size:11px;font-weight:bold;margin-bottom:2px;">' + name + '</div>';
        html += '<svg class="barcode-svg"></svg>';
        if(showPrice){
            html += '<div style="font-size:12px;font-weight:bold;margin-top:2px;">LKR ' + parseFloat(price).toFixed(2) + '</div>';
        }
        html += '</div>';
    }
    if(copies > 20){
        html += '<div class="text-muted mt-2">Showing 20 of ' + copies + ' labels (all will print)</div>';
    }
    html += '</div>';
    $('#barcodePreview').html(html);

    // Generate barcodes
    try {
        JsBarcode('.barcode-svg', code, {
            format: format,
            width: 1.5,
            height: 40,
            fontSize: 12,
            margin: 2,
            displayValue: true
        });
    } catch(e) {
        $('#barcodePreview').html('<div class="alert alert-danger">Invalid barcode format for this item code. Try CODE 128 or CODE 39.</div>');
    }
}

$('#printBarcodeBtn').on('click', function(){
    var format = $('#barcodeFormat').val();
    var copies = parseInt($('#barcodeCopies').val()) || 1;
    var showPrice = $('#barcodeShowPrice').val() == '1';
    var code = currentBarcodeData.code;
    var name = currentBarcodeData.name;
    var price = currentBarcodeData.price;

    var printWin = window.open('', '_blank', 'width=800,height=600');
    var html = '<!DOCTYPE html><html><head><title>Print Barcodes - ' + code + '</title>';
    html += '<script src="<?php echo base_url()?>assets/js/JsBarcode.all.min.js"><\/script>';
    html += '<style>body{margin:0;padding:10px;font-family:Arial,sans-serif;}';
    html += '.labels{display:flex;flex-wrap:wrap;gap:5px;}';
    html += '.label{border:1px dashed #ccc;padding:5px;text-align:center;width:200px;page-break-inside:avoid;}';
    html += '.label .name{font-size:10px;font-weight:bold;margin-bottom:1px;}';
    html += '.label .price{font-size:11px;font-weight:bold;margin-top:1px;}';
    html += '@media print{.labels{gap:2px;}.label{border:none;padding:3px;}}</style></head><body>';
    html += '<div class="labels">';
    for(var i = 0; i < copies; i++){
        html += '<div class="label">';
        html += '<div class="name">' + name + '</div>';
        html += '<svg class="bc"></svg>';
        if(showPrice) html += '<div class="price">LKR ' + parseFloat(price).toFixed(2) + '</div>';
        html += '</div>';
    }
    html += '</div>';
    html += '<script>JsBarcode(".bc","' + code + '",{format:"' + format + '",width:1.5,height:35,fontSize:11,margin:1,displayValue:true});window.onload=function(){window.print();}<\/script>';
    html += '</body></html>';
    printWin.document.write(html);
    printWin.document.close();
});

// Export JPEG at 144 DPI for TSC TTP-244 Pro
// Label size: 50mm x 25mm at 144 DPI = 283 x 142 pixels
$('#exportBarcodeJpeg').on('click', function(){
    var DPI = 144;
    var LABEL_W_MM = 50, LABEL_H_MM = 25;
    var LABEL_W = Math.round(LABEL_W_MM / 25.4 * DPI); // ~283px
    var LABEL_H = Math.round(LABEL_H_MM / 25.4 * DPI); // ~142px
    var copies = parseInt($('#barcodeCopies').val()) || 1;
    var format = $('#barcodeFormat').val();
    var showPrice = $('#barcodeShowPrice').val() == '1';
    var code = currentBarcodeData.code;
    var name = currentBarcodeData.name;
    var price = currentBarcodeData.price;

    // Create a hidden SVG for barcode generation
    var tmpSvg = document.createElement('svg');
    tmpSvg.style.position = 'absolute';
    tmpSvg.style.left = '-9999px';
    document.body.appendChild(tmpSvg);
    try {
        JsBarcode(tmpSvg, code, {
            format: format,
            width: 1.5,
            height: Math.round(LABEL_H * 0.4),
            fontSize: Math.round(LABEL_H * 0.08),
            margin: 0,
            displayValue: true
        });
    } catch(e) {
        alert('Invalid barcode format for this item code.');
        document.body.removeChild(tmpSvg);
        return;
    }

    var svgData = new XMLSerializer().serializeToString(tmpSvg);
    var svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
    var svgUrl = URL.createObjectURL(svgBlob);
    var img = new Image();
    img.onload = function(){
        // Render labels in a grid: 4 columns
        var COLS = 4;
        var ROWS = Math.ceil(copies / COLS);
        var GAP = 4;
        var canvasW = COLS * LABEL_W + (COLS - 1) * GAP;
        var canvasH = ROWS * LABEL_H + (ROWS - 1) * GAP;
        var canvas = document.createElement('canvas');
        canvas.width = canvasW;
        canvas.height = canvasH;
        var ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvasW, canvasH);

        for (var i = 0; i < copies; i++) {
            var col = i % COLS;
            var row = Math.floor(i / COLS);
            var x = col * (LABEL_W + GAP);
            var y = row * (LABEL_H + GAP);

            // White background for label
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(x, y, LABEL_W, LABEL_H);

            // Item name
            ctx.fillStyle = '#000000';
            ctx.font = 'bold ' + Math.round(LABEL_H * 0.09) + 'px Arial';
            ctx.textAlign = 'center';
            var nameY = y + Math.round(LABEL_H * 0.12);
            ctx.fillText(name, x + LABEL_W / 2, nameY, LABEL_W - 4);

            // Barcode SVG
            var bcY = nameY + 2;
            var bcH = Math.round(LABEL_H * 0.55);
            var bcW = Math.min(img.width, LABEL_W - 10);
            var bcX = x + (LABEL_W - bcW) / 2;
            ctx.drawImage(img, bcX, bcY, bcW, bcH);

            // Price
            if (showPrice) {
                ctx.font = 'bold ' + Math.round(LABEL_H * 0.10) + 'px Arial';
                var priceY = bcY + bcH + Math.round(LABEL_H * 0.10);
                ctx.fillText('LKR ' + parseFloat(price).toFixed(2), x + LABEL_W / 2, priceY, LABEL_W - 4);
            }
        }

        // Download as JPEG
        var jpegUrl = canvas.toDataURL('image/jpeg', 0.95);
        var a = document.createElement('a');
        a.href = jpegUrl;
        a.download = 'barcode_' + code + '_' + copies + 'labels.jpg';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(svgUrl);
        document.body.removeChild(tmpSvg);
    };
    img.src = svgUrl;
});
</script>

