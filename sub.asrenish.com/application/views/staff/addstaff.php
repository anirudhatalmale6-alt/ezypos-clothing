        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
            
                <!-- Add Staff Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Staff Details</h4>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="form-group row">
                                    <label for="staffid" class="col-3 col-form-label">Name<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="staffname" id="staffid" required>
                                    </div> 
                                </div>
                                <div class="form-group row">
                                    <label for="addressid" class="col-3 col-form-label">Address<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Address" 
                                        name="address" id="addressid" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="contactid" class="col-3 col-form-label">Contact<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Contact Number" 
                                        name="contact" id="contactid" required data-parsley-minlength="10">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="basicsalary" class="col-3 col-form-label">Basic salary<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Basic Salary" 
                                        name="basicsalary" id="basicsalary" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="12">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="foodallowance" class="col-3 col-form-label">Food allowance<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Food Allowance" 
                                        name="foodallowance" id="foodallowance" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="8">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="travelallowance" class="col-3 col-form-label">Travel allowance<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Travel Allowance" 
                                        name="travelallowance" id="travelallowance" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="8">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="otherallowance" class="col-3 col-form-label">Other allowance<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Other Allowance" 
                                        name="otherallowance" id="otherallowance" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="8">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="otperhour" class="col-3 col-form-label">O/T per hour<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter O/T Per Hour" 
                                        name="otperhour" id="otperhour" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="7">
                                    </div>
                                </div>
                                <button type="submit" id="add" class="btn btn-primary waves-effect">Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                            </form>                     
                        </div>
                    </div>
                </div>
                <!--End of Add Staff Form -->

                 <!--Start Table & row -->
                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Basic salary</th>
                                    <th>Food</th>
                                    <th>Travel</th>
                                    <th>Other</th>
                                    <th>O/T</th>
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
              <!-- Staff Edit Modal-->
			  <div class="modal " id="EditModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <form id="editForm" name="" action="#" method="post">
                            <input type="hidden" name="hiddenID" id="hiddenID" value="0">
                            <div class="form-group row">
                                    <label for="edit_staffid" class="col-3 col-form-label">Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="edit_staffname" id="edit_staffid" required>
                                    </div> 
                                </div>
                                <div class="form-group row">
                                    <label for="edit_addressid" class="col-3 col-form-label">Address</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Address" 
                                        name="edit_address" id="edit_addressid" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_contactid" class="col-3 col-form-label">Contact</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Contact Number" 
                                        name="edit_contact" id="edit_contactid" required data-parsley-minlength="10">
                                    </div>
                                </div> 
                                <div class="form-group row">
                                    <label for="edit_basicsalary" class="col-3 col-form-label">Basic salary<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" 
                                        name="edit_basicsalary" id="edit_basicsalary" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="12">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_foodallowance" class="col-3 col-form-label">Food allowance<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text"
                                        name="edit_foodallowance" id="edit_foodallowance" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="8">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_travelallowance" class="col-3 col-form-label">Travel allowance<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" 
                                        name="edit_travelallowance" id="edit_travelallowance" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="8">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_otherallowance" class="col-3 col-form-label">Other allowance<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" 
                                        name="edit_otherallowance" id="edit_otherallowance" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="8">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_otperhour" class="col-3 col-form-label">O/T per hour<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" 
                                        name="edit_otperhour" id="edit_otperhour" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="7">
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
                </div>
            </div> <!-- container -->

<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
    $( function() {
        $('form').parsley();

        showAllStaffs();

        $("#formid").submit(function(e) {
            e.preventDefault();
            var data = $('#formid').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('staffs/addStaffPOST'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                        //  $('#formid')[0].reset();
                            alert("Record added");
                            $("#formid")[0].reset();
                           // showAllStaffs();
                           location.reload();
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            $('#staffid').val('');
            $('#addressid').val('');
            $('#contactid').val('');   
        });

        function showAllStaffs(){
				$.ajax({
					type: 'post',
					url:'<?php echo base_url()?>staffs/showAllStaffs',
					async:false,
					dataType:'json',
					success:function(data){
						var rows = '';
						var i;
						for(i=0; i<data.length; i++){
                        rows+= '<tr>'+
                                    '<td>'+data[i].staff_id+'</td>'+
                                    '<td>'+data[i].staff_name+'</td>'+
                                    '<td>'+data[i].staff_address+'</td>'+
                                    '<td>'+data[i].staff_contact+'</td>'+
                                    '<td>'+data[i].staff_basicsalary+'</td>'+
                                    '<td>'+data[i].staff_food+'</td>'+
                                    '<td>'+data[i].staff_travel+'</td>'+
                                    '<td>'+data[i].staff_other+'</td>'+
                                    '<td>'+data[i].staff_otperhour+'</td>'+
                                    '<td>'+
                                        '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-info cls-edit" data="'+data[i].staff_id+'"><i class="fa fa-edit"></i></a>'+
                                        '<a href="javascript:;" class="btn btn-sm btn-danger cls-delete" data="'+data[i].staff_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+
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
						url: "<?php echo base_url('staffs/EditStaff'); ?>",
						data:  {id: id},	
						async: false,
						dataType:'json',  
						success: function(data){
                            $('input[name=edit_staffname]').val(data.staff_name);
                            $('input[name=edit_address]').val(data.staff_address);
                            $('input[name=edit_contact]').val(data.staff_contact);
                            $('input[name=edit_basicsalary]').val(data.staff_basicsalary);
                            $('input[name=edit_foodallowance]').val(data.staff_food);
                            $('input[name=edit_travelallowance]').val(data.staff_travel);
                            $('input[name=edit_otherallowance]').val(data.staff_other);
                            $('input[name=edit_otperhour]').val(data.staff_otperhour);
                            $('input[name=hiddenID]').val(data.staff_id);
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
                        url: "<?php echo base_url('staffs/updateStaff'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            alert("Staff Updated");
                            $('#EditModel').modal('hide');
                            showAllStaffs();
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
                                url: "<?php echo base_url('staffs/DeleteStaff'); ?>",
                                data:  {id: id},	
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                   // showAllStaffs();
                                    alert("Record Deleted");  
                                    location.reload();
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
               
            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                "order": [[ 0, "desc" ]],
            buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    } ); 
    $(document)
    
</script> 

