        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Create Bank Acc</h4> 
                            <form id="formid" name="formname" action="#" method="post">                              
                                <div class="form-group row">
                                    <label for="bankname" class="col-3 col-form-label">Bank Name:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name of Bank" 
                                        name="bankname" id="bankname" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="branch" class="col-3 col-form-label">Branch:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Enter Branch" 
                                        name="branch" id="branch" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="accname" class="col-3 col-form-label">Account Name:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Enter Account Name" 
                                        name="accname" id="accname" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="accnumber" class="col-3 col-form-label">Account Number:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                    <input class="form-control" type="text" placeholder="Enter Bank Account Number" 
                                        name="accnumber" id="accnumber" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="30">
                                    </div>
                                </div>
                                <button type="submit" id="add" class="btn btn-primary waves-effect">Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                            </form> 
                        </div>
                    </div>       
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Bank</th>
                                    <th>Branch</th>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    <th>Created by</th>
                                    <th>Actions</th>                              
                                </tr>
                                </thead>
                                <tbody id="tbodyID">                                          
                                </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>
        <!-- bank Edit Modal-->
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
                                    <label for="edit_bankname" class="col-3 col-form-label">Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="edit_bankname" id="edit_bankname" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_branch" class="col-3 col-form-label">Branch</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Branch " 
                                        name="edit_branch" id="edit_branch"  required>
                                    </div>
                                </div>  
                                <div class="form-group row">
                                    <label for="edit_accname" class="col-3 col-form-label">Account Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Account Name " 
                                        name="edit_accname" id="edit_accname"  required>
                                    </div>
                                </div>  
                                <div class="form-group row">
                                    <label for="edit_accnumber" class="col-3 col-form-label">Account Number</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Account Number " 
                                        name="edit_accnumber" id="edit_accnumber"  required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="30">
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
                <!--end of bank Edit Modal-->
            </div> <!-- container -->


<script>
$( function() {
    $("#formid").submit(function(e) {
        e.preventDefault();
        var data = $('#formid').serialize();
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('banks/createBankPost'); ?>",
                    data: data,
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        $('#formid')[0].reset();
                        swal({
                            type: 'success',
                            title: 'Bank Acount Created',
                            showConfirmButton: false,
                            timer: 1700
                        });
                       // showAllBanks();
                       location.reload();
                    },
                    error: function() {
                        swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'There was an error. Try again please!!'
                            });
                    }
                });   
    });

    function showAllBanks(){
        $.ajax({
            type: 'post',
            url:'<?php echo base_url()?>banks/showAllBanks',
            async:false,
            dataType:'json',
            success:function(data){
                var rows = '';
                var i;
                for(i=0; i<data.length; i++){
                rows+= '<tr>'+
                            '<td>'+data[i].bank_name+'</td>'+
                            '<td>'+data[i].bank_branch+'</td>'+
                            '<td>'+data[i].bank_accname+'</td>'+
                            '<td>'+data[i].bank_accnumber+'</td>'+
                            '<td>'+data[i].user_name+'</td>'+
                            '<td>'+
                                '<a href="javascript:;" style="margin-right:10px;" class="btn btn-sm btn-info cls-edit" data="'+data[i].bank_id+'"><i class="fa fa-edit"></i></a>'+
                                '<a href="javascript:;" class="btn btn-sm btn-danger cls-delete" data="'+data[i].bank_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+
                            '</td>'+
                        '</tr>';
                }
                    $('#tbodyID').html(rows);						
            },

            
            error: function(){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'There was an error. Try again please!!'
                });
            }
        });
    }
    showAllBanks();

    //Edit -get to view
    $('#tbodyID').on('click', '.cls-edit', function(){
            var id = $(this).attr('data');
            $('#EditModel').modal('show');
            $('#EditModel').find('.modal-title').text("Editing");
            var E_Category,E_descrptn,E_amount,E_date;
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('banks/EditBank'); ?>",
                    data:  {id: id},	
                    async: false,
                    dataType:'json',  
                    success: function(data){
                        $('input[name=hiddenID]').val(data.bank_id); 
                        $('input[name=edit_bankname]').val(data.bank_name);
                        $('input[name=edit_branch]').val(data.bank_branch);
                        $('input[name=edit_accname]').val(data.bank_accname);
                        $('input[name=edit_accnumber]').val(data.bank_accnumber);
                    },
                    error: function() {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'There was an error. Try again please!!'
                        });
                    }
                });

        });
        //update
            $("#editForm").submit(function(e) {
            e.preventDefault();
            var data = $('#editForm').serialize();
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('banks/updateBank'); ?>",
                    data: data,
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        swal({
                            type: 'success',
                            title: 'Bank Updated',
                            showConfirmButton: false,
                            timer: 2500
                        });
                        
                        showAllBanks();
                        $("#EditModel").modal('hide');
                       // $('#editForm')[0].reset();
                    },
                    error: function() {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'There was an error. Try again please!!'
                        });
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
                            url: "<?php echo base_url('banks/deleteBank'); ?>",
                            data:  {id: id},	
                            async: false,
                            dataType:'json',  
                            success: function(response){
                               // showAllBanks();
                                alert("Bank Deleted");   
                                location.reload();
                            },
                            error: function() {
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'There was an error. Try again please!!'
                                });
                            }
                        });
                    }                 
        });    

}); 
$(document)    
</script> 