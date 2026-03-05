        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <!-- Add Customer Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Customer Details</h4>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="form-group row">
                                    <label for="customerid" class="col-3 col-form-label">Name<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="name" id="customerid" required>
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
                                    <label for="balanceid" class="col-3 col-form-label">Balance<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Balance 0.00" 
                                        name="balance" id="balanceid" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$" 
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="creditlimitid" class="col-3 col-form-label">Credit Limit<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Credit Limit 0.00" 
                                        name="creditlimit" id="creditlimitid" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$" 
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                
                    
                                <button type="submit" id="add" class="btn btn-primary waves-effect">Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                            </form>                     
                        </div>
                    </div>
                </div>
                <!--End of Add Customer Form -->

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
                                    <?php if ($this->session->userdata('userrole') == 1): ?>
                                    <th>Balance</th>
                                    <th>Credit Limit</th>
                                      <?php endif; ?>
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
                                    <label for="edit_customerid" class="col-3 col-form-label">Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="edit_name" id="edit_customerid" required>
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
                                    <label for="edit_balanceid" class="col-3 col-form-label">Starting Balance</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Balance 0.00" 
                                        name="edit_balance" id="edit_balanceid" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$" 
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="edit_balanceid" class="col-3 col-form-label" title="Total balance= Starting balance+Current bill balance">Total Balance</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Balance 0.00" 
                                        name="edit_total_balance" id="edit_total_balanceid" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$" 
                                        data-parsley-maxlength="21" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_creditlimitid" class="col-3 col-form-label">Credit Limit</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Credit Limit 0.00" 
                                        name="edit_creditlimit" id="edit_creditlimitid" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$" 
                                        data-parsley-maxlength="21">
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
                <!-- end of edit modal -->
            </div> <!-- container -->

<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
    $( function() {
        $('form').parsley();
        
        
        function noNaN(a) { return a = a || 0 }

         
        showAllCustomers();

        $("#formid").submit(function(e) {
            e.preventDefault();
            var data = $('#formid').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('customers/addCustomerPOST'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                        //  $('#formid')[0].reset();
                            alert("Record added");
                           // showAllCustomers();
                            location.reload();
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
                    $('#formid')[0].reset();
        });

function showAllCustomers(){
    var userRole = "<?php echo $this->session->userdata('userrole'); ?>";  // Get the PHP session user role

    $.ajax({
        type: 'post',
        url:'<?php echo base_url()?>customers/showAllCustomers',
        async:false,
        dataType:'json',
        success:function(data){
            var rows = '';
            var i;
            for(i=0; i<data.length; i++){
                cus_balance = parseFloat(noNaN(data[i].cus_balance));
                bal_amount = parseFloat(noNaN(data[i].bal_amount));
                cus_total_balance = cus_balance + (-bal_amount);

                // Check if userRole is not 1, and hide columns for 'cus_total_balance' and 'cus_creditlimit'
                var totalBalanceColumn = '';
                var creditLimitColumn = '';
                if (userRole == 1) {
                    totalBalanceColumn = '<td style="text-align:right">' + cus_total_balance.toFixed(2) + '</td>';
                    creditLimitColumn = '<td style="text-align:right">' + data[i].cus_creditlimit + '</td>';
                }

                rows += '<tr>' +
                    '<td>' + data[i].cus_id + '</td>' +
                    '<td>' + data[i].cus_name + '</td>' +
                    '<td>' + data[i].cus_address + '</td>' +
                    '<td>' + data[i].cus_contact + '</td>' +
                    totalBalanceColumn +  // Add total balance column only if userRole is 1
                    creditLimitColumn +  // Add credit limit column only if userRole is 1
                    '<td>' +
                    '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-info cus-edit" data="' + data[i].cus_id + '"><i class="fa fa-edit"></i></a>' +
                    '<a href="javascript:;" class="btn btn-sm btn-danger cus-delete" data="' + data[i].cus_id + '"><i class="fa fa-times-rectangle-o"></i></a>' +
                    '</td>' +
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
             $('#tbodyID').on('click', '.cus-edit', function(){
                var id = $(this).attr('data');
                $('#EditModel').modal('show');
                $('#EditModel').find('.modal-title').text("Editing")
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('customers/editCustomer'); ?>",
                    data:  {id: id},	
                    async: false,
                    dataType:'json',  
                    success: function(data){
                        cus_balance = parseFloat(noNaN(data.cus_balance ));
                        bal_amount = parseFloat(noNaN(data.bal_amount));
                        customer_total_balance_w = cus_balance+(-bal_amount);
                        $('input[name=edit_name]').val(data.cus_name);
                        $('input[name=edit_address]').val(data.cus_address);
                        $('input[name=edit_contact]').val(data.cus_contact);
                        $('input[name=edit_balance]').val(data.cus_balance);
                        $('input[name=edit_total_balance]').val(customer_total_balance_w.toFixed(2));
                        $('input[name=edit_creditlimit]').val(data.cus_creditlimit);
                        $('input[name=hiddenID]').val(data.cus_id);
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            });

            //update
            $("#editForm").submit(function(e) {
            e.preventDefault();
			    var data = $('#editForm').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('customers/updateCustomer'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            alert("Customer Updated");
                            $('#EditModel').modal('hide');
                            showAllCustomers();
                        },
                        error: function() {
                            alert("There was an error. Try again please!");
                        }
                    });
            });

            //Delete
			$('#tbodyID').on('click', '.cus-delete', function(){
                
                var check = confirm("Press OK to continue delete");
                if (check == true) {
                var id = $(this).attr('data');
                        $.ajax({
                                type: 'post',
                                url: "<?php echo base_url('customers/DeleteCustomer'); ?>",
                                data:  {id: id},	
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                    //showAllCustomers();
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