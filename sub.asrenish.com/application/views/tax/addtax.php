        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <!-- Add Tax Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Tax Details</h4>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="form-group row">
                                    <label for="taxid" class="col-3 col-form-label">Tax Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="taxname" id="taxid" required>
                                    </div>
                                </div>                            
                                <div class="form-group row">
                                    <label for="valueid" class="col-3 col-form-label">Value</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Tax Value 0.00" 
                                        name="taxvalue" id="valueid" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$">
                                    </div>
                                </div>
                                <button type="submit" id="add" class="btn btn-primary waves-effect">Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                            </form>                     
                        </div>
                    </div>
                </div>
                <!--End of Add Tax Form -->

                 <!--Start Table & row -->
                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                 <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tax Name</th>
                                    <th>Value</th>
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
                                    <label for="edit_taxid" class="col-3 col-form-label">Tax Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="edit_taxname" id="edit_taxid" required>
                                    </div>
                                </div>                            
                                <div class="form-group row">
                                    <label for="edit_valueid" class="col-3 col-form-label">Value</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Tax Value 0.00" 
                                        name="edit_taxvalue" id="edit_valueid" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$">
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
        $('form').parsley();

        showAllTaxs();

        $("#formid").submit(function(e) {
            e.preventDefault();
            var data = $('#formid').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('taxs/addTaxPOST'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                        //  $('#formid')[0].reset();
                            alert("Record added");
                            showAllTaxs();
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            $('#taxid').val('');
            $('#valueid').val('');
            location.reload();
        });  

        		function showAllTaxs(){
				$.ajax({
					type: 'post',
					url:'<?php echo base_url()?>taxs/showAllTaxs',
					async:false,
					dataType:'json',
					success:function(data){
						var rows = '';
						var i;
						for(i=0; i<data.length; i++){
                        rows+= '<tr>'+
                                    '<td>'+data[i].tax_id+'</td>'+
                                    '<td>'+data[i].tax_name+'</td>'+
                                    '<td>'+data[i].tax_value+'</td>'+
                                    '<td>'+
                                    '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-info cls-edit" data="'+data[i].tax_id+'"><i class="fa fa-edit"></i></a>'+
                                    '<a href="javascript:;" class="btn btn-sm btn-danger cls-delete" data="'+data[i].tax_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+
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
                $('#EditModel').find('.modal-title').text("Editing");
                $.ajax({
						type: 'post',
						url: "<?php echo base_url('taxs/EditTax'); ?>",
						data:  {id: id},	
						async: false,
						dataType:'json',  
						success: function(data){
                            $('input[name=edit_taxname]').val(data.tax_name);
                            $('input[name=edit_taxvalue]').val(data.tax_value);
                            $('input[name=hiddenID]').val(data.tax_id);
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
                        url: "<?php echo base_url('taxs/updateTax'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            $('#EditModel').modal('hide');
                            alert("Tax Updated");
                            showAllTaxs();
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
                                url: "<?php echo base_url('taxs/DeleteTax'); ?>",
                                data:  {id: id},	
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                 //   showAllTaxs();
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

