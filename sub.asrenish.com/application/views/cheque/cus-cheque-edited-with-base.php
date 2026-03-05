        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
                <div class="row">
                        <div class="col-1">
                            <button type="button" id="today" class="btn btn-outline-primary waves-effect waves-light">Today's <i class="fa fa-money"></i></button>
                        </div>
                        <div class="col-4">
                            <div class="form-group row">
                                <label for="datepicFrom" class="col-4 col-form-label">From<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control datepic" placeholder="From.." value="" id="datepicFrom">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group row">
                                <label for="datepicTo" class="col-4 col-form-label">To<span class="text-danger">*</span></label>
                                <div class="">
                                    <input class="form-control datepic" placeholder="To.." value="" id="datepicTo">
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="col-1">
                            <button type="reset" id="reset" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-refresh"></i></button>
                        </div>
                        -->
                        <div class="col-2">
                            <select class="form-control" name="cheqType" id="cheqType">
                                <option value="1">Cheque Status</option>
                                <option value="1">Pending</option> 
                                <option value="2">Passed</option>
                                <option value="3">Cancelled</option>
                                <option value="4">Returned</option>
                            </select>
                        </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Bank</th>    
                                    <th>Cheque No</th>  
                                    <th>Date</th> 
                                    <th>Status</th>
                                    <th>Action</th>                 
                                </tr>
                                </thead>
                                <tbody id="tbodyID">                                          
                                </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>

                <!--Start of edit modal-->
                <div class="modal fade" id="editModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <h4 class="header-title m-t-0 m-b-30">Edit Cheque</h4>
                            <form id="updateform" name="" action="#" method="post">
                            <input type="hidden" name="HiddenChqID" id="HiddenChqID" value="0">
                            <input type="hidden" name="HddnTrnsfrrd" id="HddnTrnsfrrd" value="0">
                                <div class="form-group row">
                                    <label for="Edit_amount" class="col-3 col-form-label">Amount</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="" 
                                        name="Edit_amount" id="Edit_amount" required>
                                    </div>
                                </div> 
                                <div class="form-group row">
                                    <label for="Edit_bank" class="col-3 col-form-label">Bank</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="" 
                                        name="Edit_bank" id="Edit_bank" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Edit_chqno" class="col-3 col-form-label">Cheque no</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="" 
                                        name="Edit_chqno" id="Edit_chqno" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Edit_date" class="col-3 col-form-label">Date</label>
                                    <div class="col-9">
                                        <input class="form-control" name="Edit_date" 
                                         value="" id="Edit_date">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Edit_status" class="col-3 col-form-label">Status</label>
                                    <div class="col-9">
                                        <select class="form-control" name="Edit_status" id="Edit_status">
                                            <option value="1">Pending</option> 
                                            <option value="2">Passed</option>
                                            <option value="3">Cancelled</option>
                                            <option value="4">Returned</option>                                                                        
                                        </select>
                                    </div>
                                </div> 
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button id="btnUpdate"type="submit" class="btn btn-primary">Update Cheque</button>
                                </div>
                            </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!--End of edit modal-->
                <!--Start of admin pass modal-->
                <div class="modal fade" id="adminPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Do you want to allow this user to make changes</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <form id="AdminPassform" name="" action="#" method="post">
                            <input type="hidden" name="HddnAdmnChq" id="HddnAdmnChq" value="0">
                            <div class="form-group row">
                                    <label for="" class="col-12 col-form-label">
                                    To continue, type an administrator name and password?
                                    </label>                                   
                                </div>
                                <div class="form-group row">
                                    <label for="username" class="col-3 col-form-label">Username</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter username" 
                                        name="username" id="username" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="adminpass" class="col-3 col-form-label">Password</label>
                                    <div class="col-9">
                                        <input class="form-control" type="password" placeholder="Enter password" 
                                        name="adminpass" id="adminpass" required>
                                    </div>
                                </div>                                                    
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button id="btnAdminPass"type="submit" class="btn btn-primary">Ok</button>
                                </div>
                            </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!--End of admin pass modal-->
            </div> <!-- container -->
<script>
    $( function() {
        function filtering(dateFrom,dateTo,status){
            $.ajax({
                type: 'post',
                url:"<?php echo base_url('CustomerPayment/filterStatusDate')?>",
                data: {status:status,from:dateFrom,to:dateTo},
                async:false,
                dataType:'json',
                success:function(data){
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){
                        var chqStatus =data[i].cus_cheque_status;
                        var transferrd =data[i].cus_cheque_transferred;
                        var info;
                        if(transferrd==1){
                            var info='<a href="javascript:;"style="margin-left:5px;" class="btn btn-sm btn-warning transferred" id="'+data[i].cus_cheque_id+'"><i class="fa fa-info"></i></a>'
                        }
                        else{
                            info="";
                        }
                        var status;
                        if(chqStatus==1){status="Pending"}
                        else if(chqStatus==2){status="Passed"}
                        else if(chqStatus==3){status="Cancelled"}
                        else if(chqStatus==4){status="Returned"}
                    rows+= '<tr>'+
                                '<td>'+data[i].cus_name+'</td>'+
                                '<td style="Text-align: right;">'+data[i].cus_cheque_amount+'</td>'+
                                '<td>'+data[i].cus_cheque_bank+'</td>'+
                                '<td style="Text-align: right;">'+data[i].cus_cheque_num+'</td>'+                                
                                '<td>'+data[i].cus_cheque_date+'</td>'+
                                '<td>'+status+info+'</td>'+
                                '<td>'+
                                    '<a href="javascript:;"style="margin-right:5px;" class="btn btn-sm btn-info chequeEdit" id="'+data[i].cus_cheque_id+'"><i class="fa fa-edit"></i></a>'+
                                    '<a href="javascript:;" class="btn btn-sm btn-danger chequeDlt"  id="'+data[i].cus_cheque_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+ 
                                '</td>'+                         
                            '</tr>';
                    }
                        $('#tbodyID').html(rows);
                    
                },
                error: function(){
                    alert('Error in customer cheque filtering..');
                }
            });
        }

        //static datepicker
        $("#Edit_date" ).datepicker();
        $("#Edit_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
        $(".datepic" ).datepicker({
            onSelect: function(dateText) {
                var dateFrom= $('#datepicFrom').val(); 
                var dateTo= $('#datepicTo').val(); 
                filtering(dateFrom,dateTo,1);
            }
        });
        $(".datepic" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

        function getCusCheque(statusNo){
            $.ajax({
                type: 'post',
                url:'<?php echo base_url("CustomerPayment/getCusCheque")?>',
                data:{statusNo:statusNo},
                async:false,
                dataType:'json',
                success:function(data){
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){
                        var chqStatus =data[i].cus_cheque_status;
                        var transferrd =data[i].cus_cheque_transferred;
                        var info;
                        if(transferrd==1){
                            var info='<a href="javascript:;"style="margin-left:5px;" class="btn btn-sm btn-warning transferred" id="'+data[i].cus_cheque_id+'"><i class="fa fa-info"></i></a>'
                        }
                        else{
                            info="";
                        }
                        var status;
                        if(chqStatus==1){status="Pending"}
                        else if(chqStatus==2){status="Passed"}
                        else if(chqStatus==3){status="Cancelled"}
                        else if(chqStatus==4){status="Returned"}
                    rows+= '<tr>'+
                                '<td>'+data[i].cus_name+'</td>'+
                                '<td style="Text-align: right;">'+data[i].cus_cheque_amount+'</td>'+
                                '<td>'+data[i].cus_cheque_bank+'</td>'+
                                '<td style="Text-align: right;">'+data[i].cus_cheque_num+'</td>'+                                
                                '<td>'+data[i].cus_cheque_date+'</td>'+
                                '<td>'+status+info+'</td>'+
                                '<td>'+
                                    '<a href="javascript:;"style="margin-right:5px;" class="btn btn-sm btn-info chequeEdit" id="'+data[i].cus_cheque_id+'"><i class="fa fa-edit"></i></a>'+
                                    '<a href="javascript:;" class="btn btn-sm btn-danger chequeDlt"  id2="'+data[i].cus_cheque_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+ 
                                '</td>'+                         
                            '</tr>';
                    }
                        $('#tbodyID').html(rows);
                },
                error: function(){
                    alert('Error in customer cheque collection');
                }
            });
        }
        //pending cheques
        getCusCheque(1);
        
        //filter cheques by Status and  filter by date
        $("#cheqType").change(function(){
            var statusNo=$("#cheqType").val();
            var dateFrom= $('#datepicFrom').val(); 
            var dateTo= $('#datepicTo').val();
            if(dateFrom==''||dateTo==''){
                getCusCheque(statusNo);
            }
            else{
                filtering(dateFrom,dateTo,statusNo);           
            }  
        });

        //Today's cheque
        $("#today").click(function(){
            var fullDate = new Date();
            var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1);
            var currentDate = fullDate.getFullYear()+ "-" +twoDigitMonth + "-" +fullDate.getDate();
            $.ajax({
                    type: 'post',
                    url:'<?php echo base_url("CustomerPayment/todaysCheque")?>',
                    data: {today:currentDate},
                    async:false,
                    dataType:'json',
                    success:function(data){
                        var rows = '';
                        var i;
                        for(i=0; i<data.length; i++){
                            var chqStatus =data[i].cus_cheque_status;
                            var transferrd =data[i].cus_cheque_transferred;
                            var info;
                            if(transferrd==1){
                                var info='<a href="javascript:;"style="margin-left:5px;" class="btn btn-sm btn-warning transferred" id="'+data[i].cus_cheque_id+'"><i class="fa fa-info"></i></a>'
                            }
                            else{
                                info="";
                            }
                            var status;
                            if(chqStatus==1){status="Pending"}
                            else if(chqStatus==2){status="Passed"}
                            else if(chqStatus==3){status="Cancelled"}
                            else if(chqStatus==4){status="Returned"}
                        rows+= '<tr>'+
                                    '<td>'+data[i].cus_name+'</td>'+
                                    '<td style="Text-align: right;">'+data[i].cus_cheque_amount+'</td>'+
                                    '<td>'+data[i].cus_cheque_bank+'</td>'+
                                    '<td style="Text-align: right;">'+data[i].cus_cheque_num+'</td>'+                                
                                    '<td>'+data[i].cus_cheque_date+'</td>'+
                                    '<td>'+status+info+'</td>'+
                                    '<td>'+
                                        '<a href="javascript:;"style="margin-right:5px;" class="btn btn-sm btn-info chequeEdit" id="'+data[i].cus_cheque_id+'"><i class="fa fa-edit"></i></a>'+
                                        '<a href="javascript:;" class="btn btn-sm btn-danger chequeDlt"  id="'+data[i].cus_cheque_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+ 
                                    '</td>'+                         
                                '</tr>';
                        }
                            $('#tbodyID').html(rows);
                        
                    },
                    error: function(){
                        alert('Error in customer cheque filtering..');
                    }
                });
        });

     

        //Delete  
        $('#tbodyID').on('click', '.chequeDlt', function(){
            var check = confirm("Press OK to continue delete");
            if (check == true) {
                var chqid = $(this).attr('id2');
                $.ajax({
                    type: 'post',
                    url:'<?php echo base_url("CustomerPayment/deleteCheque")?>',
                    data: {id:chqid},
                    async:false,
                    dataType:'json',
                    success:function(data){  
                        alert("Cheque deleted");                      
                        var statusNo=$("#cheqType").val();
                        getCusCheque(statusNo);
                    },
                    error: function(){
                        alert('Error in customer cheque deleting.. ');
                    }
                });
                        
            }  
        });

       //Edit
       $('#tbodyID').on('click', '.chequeEdit', function(){ 
                var chqid = $(this).attr('id');                
                $.ajax({
                    type: 'post',
                    url:'<?php echo base_url("CustomerPayment/editCheque")?>',
                    data: {id:chqid,user:1},
                    async:false,
                    dataType:'json',
                    success:function(data){
                        if(data==false){
                            $('#adminPass').modal('show');
                            $('input[name=HddnAdmnChq]').val(chqid);
                        }else{
                            $('#editModel').modal('show');
                            $('#editModel').find('.modal-title').text("Editing...");
                            $('input[name=HiddenChqID]').val(chqid);
                            $('input[name=HddnTrnsfrrd]').val(data.cus_cheque_transferred);
                            $('input[name=Edit_amount]').val(data.cus_cheque_amount);
                            $('input[name=Edit_bank]').val(data.cus_cheque_bank);
                            $('input[name=Edit_chqno]').val(data.cus_cheque_num);
                            $('input[name=Edit_date]').val(data.cus_cheque_date);
                            $('input[name=Edit_status]').val(data.cus_cheque_status); 
                            $("#Edit_status").val(data.cus_cheque_status);
                        }                                   
                    },
                    error: function(){
                        alert('Error in customer cheque editing.. ');
                    }
                });             
        });

         //update
         $("#updateform").submit(function(e) {
            e.preventDefault();
                    var data = $('#updateform').serialize();
                    $.ajax({
                            type: 'post',
                            url: "<?php echo base_url('CustomerPayment/updateCheque'); ?>",
                            data: data,
                            async: false,
                            dataType:'json',  
                            success: function(response){
                                if(response==true){
                                    alert("Cheque Updated");
                                    $('#editModel').modal('hide');
                                    var statusNo=$("#cheqType").val();
                                    getCusCheque(statusNo);
                                }
                                else{
                                    alert("There was an error. Try again please!"); 
                                }                          
                            },
                            error: function() {
                                alert("Try again please!");
                            }
                        });
        });

        //Show Transferred Histrory
       $('#tbodyID').on('click', '.transferred', function(){ 
                var cusChqid = $(this).attr('id');                
                $.ajax({
                    type: 'post',
                    url:'<?php echo base_url("CustomerPayment/TransferredHistory")?>',
                    data: {id:cusChqid},
                    async:false,
                    dataType:'json',
                    success:function(data){
                        var supName=data.sup_name;
                        swal('Cheque given to supplier: '+supName);          
                    },
                    error: function(){
                        alert('Error in fetching transferred cheque history.. ');
                    }
                });             
        });

        

        $("#AdminPassform").submit(function(e) {
        e.preventDefault();
            var chqid = $('#HddnAdmnChq').val();
            var data = $('#AdminPassform').serialize();
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('CustomerPayment/checkAdminPass'); ?>",
                    data: data,
                    async: false,
                    dataType:'json',  
                    success: function(res){
                        if(res==true){
                            $('#AdminPassform')[0].reset()
                            $('#adminPass').modal('hide');
                            $('#editModel').modal('show');
                                        $.ajax({
                                            type: 'post',
                                            url:'<?php echo base_url("CustomerPayment/editCheque")?>',
                                            data: {id:chqid,user:0},
                                            async:false,
                                            dataType:'json',
                                            success:function(data){                                                     
                                                    $('#editModel').find('.modal-title').text("Editing...");
                                                    $('input[name=HiddenChqID]').val(chqid);
                                                    $('input[name=HddnTrnsfrrd]').val(data.cus_cheque_transferred);
                                                    $('input[name=Edit_amount]').val(data.cus_cheque_amount);
                                                    $('input[name=Edit_bank]').val(data.cus_cheque_bank);
                                                    $('input[name=Edit_chqno]').val(data.cus_cheque_num);
                                                    $('input[name=Edit_date]').val(data.cus_cheque_date);
                                                    $('input[name=Edit_status]').val(data.cus_cheque_status); 
                                                    $("#Edit_status").val(data.cus_cheque_status);
                                                                                
                                            },
                                            error: function(){
                                                alert('Error in customer cheque editing.. ');
                                            }
                                        });
                        }else if(res==false){
                            alert("Password miss matched");
                        }                      
                    },
                    error: function() {
                        alert("There was an error. Try again please!");
                    }
                });
        });
            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                buttons: ['copy', 'excel', 'pdf']
                });
            table.buttons().container()
            .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

        //Reset filtered cheques
        $('#reset').click(function(){
            getCusPendingCheque();
            $('.datepic').val("");
        });
        
    }); 
    $(document)
    
</script> 