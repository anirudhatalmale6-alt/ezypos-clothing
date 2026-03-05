        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <!-- Add Store Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Store Details</h4>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="form-group row">
                                    <label for="storeid" class="col-3 col-form-label">Store Name<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="storename" id="storeid" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="addressid" class="col-3 col-form-label">Address Line 1<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Address Line 1" 
                                        name="address" id="addressid" required>
                                    </div>
                                </div>    
                                <div class="form-group row">
                                    <label for="address2" class="col-3 col-form-label">Address Line 2<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Address Line 2" 
                                        name="address2" id="address2" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tel" class="col-3 col-form-label">Telephone<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Telephone No" 
                                        name="tel" id="tel" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile" class="col-3 col-form-label">Mobile<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Mobile No" 
                                        name="mobile" id="mobile" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile2" class="col-3 col-form-label">Mobile 2</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Mobile No" 
                                        name="mobile2" id="mobile2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fax" class="col-3 col-form-label">Fax</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Fax" 
                                        name="fax" id="fax">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-3 col-form-label">Email</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Email" 
                                        name="email" id="email">
                                    </div>
                                </div>
                                <button type="submit" id="add" class="btn btn-primary waves-effect">Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                            </form>                     
                        </div>
                    </div>
                </div>
                <!--End of Add Store Form -->

                 <!--Start Table & row -->
                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Store Name</th>
                                    <th>Address Line 1</th>
                                    <th>Actions Line 2</th>
                                    <th>Telephone</th>
                                    <th>Mobile</th>
                                    <th>Mobile 2</th>
                                    <th>Fax</th>
                                    <th>Email</th>
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
                                <input type="hidden" name="hiddenID" id="hiddenID" value="0">
                                <div class="form-group row">
                                        <label for="edit_storeid" class="col-3 col-form-label">Store Name<span class="text-danger">*</span></label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Name" 
                                            name="edit_storename" id="edit_storeid" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="edit_addressid" class="col-3 col-form-label">Address<span class="text-danger">*</span></label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Address" 
                                            name="edit_address" id="edit_addressid" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="edit_address2" class="col-3 col-form-label">Address Line 2<span class="text-danger">*</span></label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Address Line 2" 
                                            name="edit_address2" id="edit_address2" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="edit_tel" class="col-3 col-form-label">Telephone<span class="text-danger">*</span></label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Telephone No" 
                                            name="edit_tel" id="edit_tel" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="edit_mobile" class="col-3 col-form-label">Mobile<span class="text-danger">*</span></label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Mobile No" 
                                            name="edit_mobile" id="edit_mobile" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="edit_mobile2" class="col-3 col-form-label">Mobile 2</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Mobile No" 
                                            name="edit_mobile2" id="edit_mobile2">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="edit_fax" class="col-3 col-form-label">Fax</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Fax" 
                                            name="edit_fax" id="edit_fax">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="edit_email" class="col-3 col-form-label">Email</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="Enter Email" 
                                            name="edit_email" id="edit_email">
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
            </div> <!-- container -->

<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
    $( function() {
        showAllStores();
        $('form').parsley();         

        $("#formid").submit(function(e) {
            e.preventDefault();
            var data = $('#formid').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('stores/addStorePOST'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                        //  $('#formid')[0].reset();
                            alert("Record added");
                           $('#formid')[0].reset();
                           // showAllStores();
                           location.reload();
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            $('#storeid').val('');
            $('#addressid').val('');
        });

        		function showAllStores(){
				$.ajax({
					type: 'post',
					url:'<?php echo base_url()?>stores/showAllStores',
					async:false,
					dataType:'json',
					success:function(data){
						var rows = '';
						var i;
						for(i=0; i<data.length; i++){
                        rows+= '<tr>'+
                                    '<td>'+data[i].store_name+'</td>'+
                                    '<td>'+data[i].store_address+'</td>'+
                                    '<td>'+data[i].store_address2+'</td>'+
                                    '<td>'+data[i].store_tel+'</td>'+
                                    '<td>'+data[i].store_mobile+'</td>'+
                                    '<td>'+data[i].store_mobile2+'</td>'+
                                    '<td>'+data[i].store_fax+'</td>'+
                                    '<td>'+data[i].store_email+'</td>'+
                                    '<td>'+
                                    '<a href="javascript:;" style="margin-right:5px;" class="btn btn-sm btn-info cls-edit" data="'+data[i].store_id+'"><i class="fa fa-edit"></i></a>'+
                                    '<a href="javascript:;" class="btn btn-sm btn-danger cls-delete" data="'+data[i].store_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+
                                    '</td>'+
                                '</tr>';
						}
							$('#tbodyID').html(rows);
						
					},
					error: function(){
						alert('error data collection');
					}
				});
			}

            //Edit -get to view
			$('#tbodyID').on('click', '.cls-edit', function(){
                var id = $(this).attr('data');
                $('#EditModel').modal('show');
                $('#EditModel').find('.modal-title').text("Editing")
                $.ajax({
						type: 'post',
						url: "<?php echo base_url('stores/EditStore'); ?>",
						data:  {id: id},	
						async: false,
						dataType:'json',  
						success: function(data){
                            $('input[name=edit_storename]').val(data.store_name);
                            $('input[name=edit_address]').val(data.store_address);
                            $('input[name=edit_address2]').val(data.store_address2);
                            $('input[name=edit_tel]').val(data.store_tel);
                            $('input[name=edit_mobile]').val(data.store_mobile);
                            $('input[name=edit_mobile2]').val(data.store_mobile2);
                            $('input[name=edit_fax]').val(data.store_fax);
                            $('input[name=edit_email]').val(data.store_email);
                            $('input[name=hiddenID]').val(data.store_id);
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            });
            //save
            $("#editForm").submit(function(e) {
                e.preventDefault();
			    var data = $('#editForm').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('stores/updateStore'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            $('#EditModel').modal('hide');
                            alert("Store Updated");
                            showAllStores();
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            });

            //Delete
            $('#tbodyID').on('click', '.cls-delete', function(){
                            
                            var check = confirm("Press OK to continue delete");
                            if (check == true) {
                            var id = $(this).attr('data');
                                    $.ajax({
                                            type: 'post',
                                            url: "<?php echo base_url('stores/DeleteStore'); ?>",
                                            data:  {id: id},	
                                            async: false,
                                            dataType:'json',  
                                            success: function(response){
                                               // showAllStores();
                                                alert("Record Deleted");    
                                                location.reload();
                                            },
                                            error: function() {
                                                alert("There was an error. Try again please!");
                                            }
                                        });
                                    }                 
            });

            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    } ); 
    $(document)
    
</script> 

