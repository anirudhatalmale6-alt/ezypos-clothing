        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
            
                <!-- Add Expense Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Expense Details</h4>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="form-group row">
                                    <label for="expenseCatid" class="col-3 col-form-label">Expense Category:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <select class="form-control" name="expenseCat" id="expenseCatid" required>
                                            <option value="">-Select-</option>
                                               <?php
                                            $otherCategory = null;
                                            foreach ($expenCategories as $expenCat) {
                                                if ($expenCat->expencat_id == 6) {
                                                    $otherCategory = $expenCat;
                                                } else {
                                                    print '<option value="'. $expenCat->expencat_id.'"> '. $expenCat->expencat_catname.'</option>';
                                                }
                                            }
                                            if ($otherCategory) {
                                                print '<option value="'. $otherCategory->expencat_id.'"> '. $otherCategory->expencat_catname.'</option>';
                                            }
                                            ?><!-- "Other" category (ID: 6) will always appear as the last option in the dropdown -->                                     
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="employeediv"></div>   
                                <div id="employeeExpensediv"></div>                             
                                <div class="form-group row">
                                    <label for="descriptionid" class="col-3 col-form-label">Description:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Description" 
                                        name="description" id="descriptionid" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="amountid" class="col-3 col-form-label">Amount:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Amount" 
                                        name="amount" id="amountid" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="paymentMode" class="col-3 col-form-label">Mode of Payment:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <div class="radio radio-primary">
                                            <input type="radio" name="paymentMode" id="modeCash" value=1>
                                            <label for="modeCash">
                                                Cash
                                            </label>
                                            <input style="margin: 0 10px;" type="radio" name="paymentMode" id="modeCheque" value=2>
                                            <label for="modeCheque">
                                                Cheque
                                            </label>                                            
                                        </div>
                                    </div>
                                </div>
                                <div id="selectBankDiv"></div> 
                                <div class="form-group row">
                                    <label for="datepicker" class="col-3 col-form-label">Date of payment:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input class="form-control datepicker hasDatepicker parsley-success" name="date" value="" type="date" id="datepicker" required="" data-parsley-id="15">
                                    </div>
                                </div>
                                <button type="submit" id="add" class="btn btn-primary waves-effect">Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                                <div id="pymntAlert" style="display:none;" class="text-danger"><b>PAYMENTS MADE MORE THAN ONCE FOR THE SAME MONTH CANNOT BE EDITED</b></div>
                            </form>                     
                        </div>
                    </div>
                </div>
                <!--End of Add Expense Form -->

                 <!--Start Table & row -->
                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Expense</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Date</th>
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
              <!-- espense Edit Modal-->
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
                                    <label for="edit_expenseCatid" class="col-3 col-form-label">Expense</label>
                                    <div class="col-9">
                                        <select class="form-control" name="edit_expenseCat" id="edit_expenseCatid" required>
                                            <option value="">-Select-</option>
                                            <?php
                                            foreach ($expenses as $expen)
                                            {
                                           print '<option value="'.  $expen->expen_id.'"> '. $expen->expen_catname.'</option>';
                                            }
	                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_descriptionid" class="col-3 col-form-label">Description</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Description" 
                                        name="edit_description" id="edit_descriptionid" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit_amountid" class="col-3 col-form-label">Amount</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Amount " 
                                        name="edit_amount" id="edit_amountid"  required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div> 
                                <div class="form-group row">
                                    <label for="edit_dateid" class="col-3 col-form-label">Date</label>
                                    <div class="col-9">
                                    <input class="form-control datepicker" name="edit_date" value="" id="edit_dateid" required>                                      
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
                <!--end of expense Edit Modal-->

                <!-- emp salary espense Edit Modal-->
			    <div class="modal " id="SalaryEditModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editSalaryForm" name="" action="" method="post">
                    <div class="modal-body">                            
                            <input type="hidden" name="hiddenSalaryID" id="hiddenSalaryID" value="0">
                                
                                <div class="form-group row">
                                    <label for="editSlry_emp" class="col-3 col-form-label">Employee</label>
                                    <div class="col-9">
                                        <select class="form-control" name="editSlry_emp" id="editSlry_emp" required>
                                            <option value="">-Select-</option>
                                            <?php
                                            foreach ($expenses as $expen)
                                            {
                                           print '<option value="'.  $expen->expen_id.'"> '. $expen->expen_catname.'</option>';
                                            }
	                                        ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="editSalry_descrip" class="col-3 col-form-label">Description</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Description" 
                                        name="editSalry_descrip" id="editSalry_descrip" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="editSalry_month" class="col-3 col-form-label">Month:<span class="text-danger">*</span></label>
                                    <div class="col-3">
                                        <select class="form-control" name="editSalry_month" id="editSalry_month" required>
                                            <option value="">Select Month</option>
                                            <option value="1">January</option><option value="2">February</option>
                                            <option value="3">March</option><option value="4">April</option>
                                            <option value="5">May</option><option value="6">June</option>
                                            <option value="7">July</option><option value="8">August</option>
                                            <option value="9">September</option><option value="10">October</option>
                                            <option value="11">November</option><option value="12">December</option>
                                        </select>
                                    </div>    
                                    <label for="editSalry_year" class="col-3 col-form-label">Year:<span class="text-danger">*</span></label>
                                    <div class="col-3">
                                        <input class="form-control" name="editSalry_year" value='+year+' type="number" id="editSalry_year" required>                                     
                                    </div>                                
                                </div>
                                <div class="form-group row">
                                    <label for="editSlry_salary" class="col-3 col-form-label">Salary of Month:</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" readonly
                                        name="editSlry_salary" id="editSlry_salary"  required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="editSlry_BscSalay" class="col-3 col-form-label">Basic Salary:</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Basic Salary " 
                                        name="editSlry_BscSalay" id="editSlry_BscSalay"  data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="editSlry_food" class="col-3 col-form-label">Food Allowance:</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Food Allowance " 
                                        name="editSlry_food" id="editSlry_food"  data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="editSlry_travel" class="col-3 col-form-label">Travel Allowance:</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Travel Allowance " 
                                        name="editSlry_travel" id="editSlry_travel"  data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="editSlry_other" class="col-3 col-form-label">Other Allowance:</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Other Allowance " 
                                        name="editSlry_other" id="editSlry_other"  data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>



                                <div class="form-group row">
                                    <label for="editSlry_bonus" class="col-3 col-form-label">Bonus:</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Amount " 
                                        name="editSlry_bonus" id="editSlry_bonus"  data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="editSlry_amount" class="col-3 col-form-label">Amount:</label>
                                    <div class="col-9">
                                        <input class="form-control DecimalFix" type="text" placeholder="Enter Amount " 
                                        name="editSlry_amount" id="editSlry_amount"  required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"
                                        data-parsley-maxlength="21">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="editSlry_Mode" class="col-3 col-form-label">Mode of Payment:<span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <div class="radio radio-primary">
                                            <input type="radio" name="editSlry_Mode" id="editSlry_Cash" value=1>
                                            <label for="editSlry_Cash">
                                                Cash
                                            </label>
                                            <input style="margin: 0 10px;" type="radio" name="editSlry_Mode" id="editSlry_Cheque" value=2>
                                            <label for="editSlry_Cheque">
                                                Cheque
                                            </label>                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="editSlry_date" class="col-3 col-form-label">Date:</label>
                                    <div class="col-9">
                                    <input class="form-control date" name="editSlry_date" value="" id="editSlry_date" required>                                      
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
                <!--end of emp salary expense Edit Modal-->
            </div> <!-- container -->

<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
    $( function() {
        var table;
        $('form').parsley();
        $(document).on("focus", ".datepicker", function(){
             $(this).datepicker();
             $(".datepicker").datepicker({ dateFormat: "yy-mm-dd" });
        });
        $(".datepicker" ).datepicker();
        $(".datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
        $("#datepicker").datepicker().datepicker("setDate", "0");//not set for edit
        $(".date").datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText){
                
                $expenid=$("#hiddenSalaryID").val();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('expenses/getOldDate'); ?>",
                        data:{exid:$expenid},
                        async: false,
                        dataType:'json',  
                        success: function(oldDateDB){
                           // alert(response);
                           // alert(dateText);
                            if(oldDateDB>dateText){
                                alert("err");
                            }
                           
                          
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

        showAllExpenses();

        $("#formid").submit(function(e) {
       
            e.preventDefault();
            var data = $('#formid').serialize();
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('Expenses/addExpensePOST'); ?>",
                    data: data,
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        $('#formid')[0].reset();
                        // alert(response);
                        swal({
                            type: 'success',
                            title: response,
                            showConfirmButton: false,
                            timer: 1700
                            });
                        showAllExpenses();                           
                        //table.clear().draw();
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

        function showAllExpenses(){
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>expenses/showAllExpenses',
                async:false,
                dataType:'json',
                success:function(data){
                    var rows = '';
                    var i;
                    for(i=0; i<data.length; i++){
                    rows+= '<tr>'+
                                '<td>'+data[i].expen_id+'</td>'+
                                '<td>'+data[i].expencat_catname+'</td>'+
                                '<td>'+data[i].expen_description+'</td>'+
                                '<td align="right">'+data[i].expen_amount+'</td>'+
                                '<td align="right">'+data[i].expen_date+'</td>'+
                                '<td>'+
                                    '<a href="javascript:;" style="margin-right:5px;" class="btn btn-sm btn-info cls-edit" data="'+data[i].expen_id+'"><i class="fa fa-edit"></i></a>'+
                                    '<a href="javascript:;" class="btn btn-sm btn-danger cls-delete" data="'+data[i].expen_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+
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

        // for emp salary edit
        function loadEmpNameID(){
            $.ajax({ //get all employees to dropdown 
                    type: 'post',
                    url: "<?php echo base_url('staffs/getEmployeeNameID'); ?>",	
                    async: false,
                    dataType:'json',  
                    success: function(data){
                        var len = data.length;
                        $('#editSlry_emp').empty();    
                        $("#editSlry_emp").append("<option value=''>-Select-</option>");    
                        for( var i = 0; i<len; i++){
                            var empname = data[i]['staff_name'];
                            var id = data[i]['staff_id'];                    
                            $("#editSlry_emp").append("<option value='"+id+"'>"+empname+"</option>");
                        }
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
        //for expen edit
        function loadExpenCatgryNameID(){
            $.ajax({ //get all expense categories to dropdown
                    type: 'post',
                    url: "<?php echo base_url('expenCategories/getExpenCategories'); ?>",	
                    async: false,
                    dataType:'json',  
                    success: function(data){
                        var len = data.length;
                        $('#edit_expenseCatid').empty();    
                        $("#edit_expenseCatid").append("<option value=''>-Select-</option>");    
                        for( var i = 0; i<len; i++){
                            var catname = data[i]['expencat_catname'];
                            var id = data[i]['expencat_id'];                    
                            $("#edit_expenseCatid").append("<option value='"+id+"'>"+catname+"</option>");
                        }                       
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

        //Edit -get to view
        $('#tbodyID').on('click', '.cls-edit', function(){
            var id = $(this).attr('data');        
            var E_Category,E_descrptn,E_amount,E_date;
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('expenses/EditExpense'); ?>",
                    data:  {id: id},	//expen_id
                    async: false,
                    dataType:'json',  
                    success: function(data){
                        var count=0;
                        count=data[0];
                        var catgry=data[1].expen_expencat_id;
                        if(catgry==1){  //if salary                             
                            $('#SalaryEditModel').modal('show');
                            $('#SalaryEditModel').find('.modal-title').text("Employee Salary Editing");
                            $('input[name=hiddenSalaryID]').val(data[1].expen_id);
                            $('input[name=editSalry_descrip]').val(data[1].expen_description);                             
                            $('input[name=editSlry_salary]').val(data[1].empsalary_total);
                            $('input[name=editSlry_BscSalay]').val(data[1].empsalary_basicsalary);
                            $('input[name=editSlry_food]').val(data[1].empsalary_food);
                            $('input[name=editSlry_travel]').val(data[1].empsalary_travel);
                            $('input[name=editSlry_other]').val(data[1].empsalary_other);
                            $('input[name=editSlry_bonus]').val(data[1].empsalary_bonus); 
                            $('input[name=editSlry_amount]').val(data[1].expen_amount);
                            $('input[name=editSlry_date]').val(data[1].expen_date);                             
                            loadEmpNameID(); 
                            if(count==1){
                                $('#editSalry_month').val(data[1].emp_slry_log_month).attr("disabled", false);
                                $('input[name=editSalry_year]').val(data[1].emp_slry_log_year).prop('readonly', false);                               
                                $("#editSlry_emp").val(data[1].emp_slry_log_empid).attr("disabled", false);
                            }else{
                                $('#editSalry_month').val(data[1].emp_slry_log_month).attr("disabled", true);
                                $('input[name=editSalry_year]').val(data[1].emp_slry_log_year).prop('readonly', true);
                                $("#editSlry_emp").val(data[1].emp_slry_log_empid).attr("disabled", true);
                            }                         
                        }else{
                            $('#EditModel').modal('show');
                            $('#EditModel').find('.modal-title').text("Editing");
                            $('input[name=hiddenID]').val(data[1].expen_id);
                             
                            $('input[name=edit_description]').val(data[1].expen_description);
                            $('input[name=edit_amount]').val(data[1].expen_amount);
                            $('input[name=edit_date]').val(data[1].expen_date);
                            loadExpenCatgryNameID();
                            $("#edit_expenseCatid").val(data[1].expencat_id);
                        }
                        
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
        //empsalary_basicsalary,empsalary_food,empsalary_travel,empsalary_other,

        //when bonus change=> total of month also changing
        $("#editSlry_bonus,#editSlry_BscSalay,#editSlry_food,#editSlry_travel,#editSlry_other").keyup(function(){
            var newBsc=0;
            newBsc=parseFloat($("#editSlry_BscSalay").val());
            if(isNaN(newBsc)){newBsc=0;}
            var newFd=0;
            newFd=parseFloat($("#editSlry_food").val());
            if(isNaN(newFd)){newFd=0;}
            var newTrvl=0;
            newTrvl=parseFloat($("#editSlry_travel").val());
            if(isNaN(newTrvl)){newTrvl=0;}
            var newOther=0;
            newOther=parseFloat($("#editSlry_other").val());
            if(isNaN(newOther)){newOther=0;}
            var newBonus=0;
            newBonus=parseFloat($("#editSlry_bonus").val());
            if(isNaN(newBonus)){newBonus=0;}
            var empid1=$("#editSlry_emp").val();  
            var month1=$("#editSalry_month").val();
            var year1=$("#editSalry_year").val();
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('expenses/getPreviousBonus'); ?>",
                    data:{emp:empid1,month:month1,year:year1},
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        var basic1=parseFloat(response.empsalary_basicsalary);
                        var fd1=parseFloat(response.empsalary_food);
                        var trvl1=parseFloat(response.empsalary_travel);
                        var other1=parseFloat(response.empsalary_other);
                        var bonus=parseFloat(response.empsalary_bonus);
                        var salary=parseFloat(response.empsalary_total);
                        var salary1=(salary+newBonus-bonus+newBsc-basic1+newFd-fd1+newTrvl-trvl1+newOther-other1).toFixed(2);
                        $("#editSlry_salary ").val(salary1);
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
                    url: "<?php echo base_url('expenses/updateExpense'); ?>",
                    data: data,
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        //alert("Expense Updated");
                        swal({
                            type: 'success',
                            title:'Expense Updated',
                            showConfirmButton: false,
                            timer: 1700
                        });
                        showAllExpenses();
                        $('#editForm')[0].reset();
                        $('#EditModel').modal('hide');
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
        //update emp salary
        $("#editSalaryForm").submit(function(e) {
            e.preventDefault();
            var data = $('#editSalaryForm').serialize();
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('expenses/updateEmpSalary'); ?>",
                    data: data,
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        swal({
                            type: 'success',
                            title:'Expense Updated',
                            showConfirmButton: false,
                            timer: 1700
                        });
                        showAllExpenses();
                        $('#editSalaryForm')[0].reset();
                        $('#SalaryEditModel').modal('hide');
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
                            url: "<?php echo base_url('expenses/DeleteExpense'); ?>",
                            data:  {id: id},	
                            async: false,
                            dataType:'json',  
                            success: function(response){
                                showAllExpenses();
                                alert("Record Deleted");                            
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
        var total=0;
        var basic=0;
        var other=0;
        var travel=0;
        var food=0;
        var OT=0;
        function loadEmplyeeExpense(id){
            $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('staffs/getEmployeeExpense'); ?>",
                    data:  {id: id},	
                    async: false,
                    dataType:'json',  
                    success: function(response){     
                        basic=parseFloat(response.staff_basicsalary);
                        food=parseFloat(response.staff_food);
                        travel=parseFloat(response.staff_travel);
                        other=parseFloat(response.staff_other);
                        OT=parseFloat(response.staff_otperhour);
                        total=(basic+food+travel+other).toFixed(2);
                        var year= (new Date).getFullYear();
                    var expenseSet='<div class="form-group row">'+
                                    '<label for="month" class="col-3 col-form-label">Month:<span class="text-danger">*</span></label>'+
                                    '<div class="col-3">'+
                                        '<select class="form-control" name="month" id="month" required>'+
                                            '<option value="">Select Month</option>'+
                                            '<option value="1">Jan</option><option value="2">Feb</option>'+
                                            '<option value="3">Mar</option><option value="4">Apr</option>'+
                                            '<option value="5">May</option><option value="6">Jun</option>'+
                                            '<option value="7">Jul</option><option value="8">Aug</option>'+
                                            '<option value="9">Sep</option><option value="10">Oct</option>'+
                                            '<option value="11">Nov</option><option value="12">Dec</option>'+
                                        '</select>'+
                                    '</div>'+     
                                    '<label for="year" class="col-3 col-form-label">Year:<span class="text-danger">*</span></label>'+
                                    '<div class="col-3">'+ 
                                        '<input class="form-control" name="year" value='+year+' type="number" id="year" required>'+                                       
                                    '</div>'+                                    
                                '</div>'+                    
                    '           <div class="form-group row">'+
                                    '<label for="basicsalary" class="col-3 col-form-label">Basic Salary:</label>'+
                                    '<div class="col-9">'+
                                        '<input class="form-control" type="text" value='+basic+' name="basicSalary" id="basicSalary" readonly>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="form-group row">'+
                                    '<label for="foodAllownc" class="col-3 col-form-label">Food allowance:</label>'+
                                    '<div class="col-9">'+
                                        '<input class="form-control" type="text" value='+food+' name="foodAllownc" id="foodAllownc" readonly>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="form-group row">'+
                                    '<label for="travelAllowce" class="col-3 col-form-label">Travel allowanc:</label>'+
                                    '<div class="col-9">'+
                                        '<input class="form-control" type="text" value='+travel+' name="travelAllowce" id="travelAllowce" readonly>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="form-group row">'+
                                    '<label for="otherAllownc" class="col-3 col-form-label">Other allowance:</label>'+
                                    '<div class="col-9">'+
                                        '<input class="form-control" type="text" value='+other+' name="otherAllownc" id="otherAllownc" readonly>'+
                                    '</div>'+                                  
                                '</div>'+
                                '<div class="form-group row">'+
                                    '<label for="otperhour" class="col-3 col-form-label">O/T per hour:</label>'+
                                    '<div class="col-3">'+
                                        '<input class="form-control" type="text" value='+OT+' name="otperhour" id="otperhour" readonly>'+
                                    '</div>'+
                                    '<label for="othours" class="col-3 col-form-label">Hours:</label>'+
                                    '<div class="col-3">'+
                                        '<input class="form-control dcmlFixDynmc" type="text"  name="othours" id="othours" data-parsley-pattern="^[0-9]*\.[0-9]{2}$">'+
                                    '</div>'+
                                '</div>'+

                                '<div class="form-group row">'+
                                    '<label for="bonus" class="col-3 col-form-label">Bonus:</label>'+
                                    '<div class="col-9">'+
                                        '<input class="form-control dcmlFixDynmc" type="text" name="bonus"  id="bonus" data-parsley-pattern="^[0-9]*\.[0-9]{2}$">'+
                                    '</div>'+                                  
                                '</div>'+
                                
                                '<div class="form-group row" style="display:none;" id="TotalSalaryDB_div">'+
                                    '<label for="TotalSalaryDB" class="col-3 col-form-label">Total:</label>'+
                                    '<div class="col-9">'+
                                        '<input class="form-control" type="text"  id="TotalSalaryDB" readonly>'+
                                    '</div>'+                                  
                                '</div>'+

                                '<div class="form-group row" id="totaldiv">'+
                                    '<label for="total" class="col-3 col-form-label">Total:</label>'+
                                    '<div class="col-9">'+
                                        '<input class="form-control" type="text" value='+total+' name="total" id="total" readonly>'+
                                    '</div>'+                                  
                                '</div>'+

                                '<div class="form-group row" style="display:none;">'+
                                    '<label for="balanceofmonth" class="col-3 col-form-label">Previous Balance of this month DB:</label>'+
                                    '<div class="col-9">'+
                                        '<input class="form-control" type="text"  id="balanceofmonth" readonly>'+
                                    '</div>'+                                  
                                '</div>'+

                                '<div class="form-group row">'+
                                    '<label for="balance" class="col-3 col-form-label">Balance:</label>'+

                                    '<div class="col-3" style="display:none;" id="paidBalanceDiv">'+
                                        '<input class="form-control" type="text" id="paidBalance" readonly>'+
                                    '</div>'+
                                    
                                    '<div class="col-3" id="balancediv">'+
                                        '<input class="form-control" type="text" id="balance" readonly>'+
                                    '</div>'+  
                                    '<label for="paidofthismonth" class="col-3 col-form-label">Paid:</label>'+
                                    '<div class="col-3">'+
                                        '<input class="form-control" type="text"   id="paidofthismonth" readonly>'+
                                    '</div>'+                                
                                '</div>';
                                $("#employeeExpensediv").html(expenseSet);
                                         
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
        var total=0;
        //culculate total expense with OT
        $('#employeeExpensediv').on('keyup', '#othours', function(e){
            calBalance();
        });

        //total payment made for this month
        var paidAmnt=0;
        function paidOfThisMonth(){
            var month=$('#month').val();
            var year=$('#year').val();
            var empid=$('#employee-id').val();
            if(month>0&&year>0&&empid>0){
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('expenses/paidOfThisMonth'); ?>",
                    data:  {month:month,year:year,empid:empid},	
                    async: false,
                    dataType:'json',  
                    success: function(paidAmount){    
                        if(paidAmount==null){
                            $('#othours').prop('readonly', false);
                            $('#bonus').prop('readonly', false);
                            $("#TotalSalaryDB_div").hide();
                            $("#totaldiv").show();
                            $("#balancediv").show(); 
                            $("#paidBalanceDiv").hide();
                            $("#paidofthismonth").val("0.00");
                        }else if(paidAmount>0){
                            $("#paidofthismonth").val(paidAmount);
                            //already if there was a payment for this month salary
                            $('#othours').prop('readonly', true);
                            $('#bonus').prop('readonly', true);
                            paidAmnt=paidAmount;
                            SalaryOfThisMonth();                            
                        }                         
                    },
                    error: function() {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'There was an error in calculation of paid amount of this month!!'
                        });
                    }
                });
            }
        }
        //SalaryOfThisMonth
        function SalaryOfThisMonth(){
            var month=$('#month').val();
            var year=$('#year').val();
            var empid=$('#employee-id').val();
            if(month>0&&year>0&&empid>0){
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('expenses/SalaryOfThisMonth'); ?>",
                    data:  {month:month,year:year,empid:empid},	
                    async: false,
                    dataType:'json',  
                    success: function(empsalary){    
                        if(empsalary==null){
                            slryOfMonth=0;
                        }else if(empsalary>0){
                            slryOfMonth=empsalary;
                            var balanceofmonth=(slryOfMonth-paidAmnt).toFixed(2);
                            $("#paidBalanceDiv").show();
                            $("#balanceofmonth").val(balanceofmonth);
                            $("#TotalSalaryDB").val(slryOfMonth);
                            $("#TotalSalaryDB_div").show();
                            $("#totaldiv").hide();   
                            $("#balancediv").hide(); 
                            calBalance();
                        }                         
                    },
                    error: function() {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Error in entered month salary retrieval!!'
                        });
                    }
                });
            }
        }

        //balance
        function calBalance(){
            var totl=parseFloat((basic+food+travel+other)).toFixed(2);
            var othours=parseFloat($("#othours").val());            
                if(isNaN(othours)){
                    othours=0;
                }
            var OTCash=parseFloat(OT*othours).toFixed(2);

            var bonusAmount=parseFloat($("#bonus").val());
                if(isNaN(bonusAmount)){
                    bonusAmount=0;
                }
            var total1=0;
            total1=parseFloat(totl)+parseFloat(OTCash)+parseFloat(bonusAmount);
            total1=(total1).toFixed(2);
            $("#total").val(total1);
            //console.log(totl+" "+ OTCash +" "+ bonusAmount +" "+ total1);
            var paidAmount=0;
            paidAmount=parseFloat($("#amountid").val());
                if(isNaN(paidAmount)){
                    paidAmount=0;
                }
                var balance=0;
                var balance=(total1-paidAmount).toFixed(2);
               $("#balance").val(balance); 

               //-- get balance if there a payment already--//
               var previsBalnce =parseFloat($("#balanceofmonth").val());   
               var crrntBalance=(previsBalnce-paidAmount).toFixed(2);  
               $("#paidBalance").val(crrntBalance);
        }

        //calculate the balance while amout typing
        $("#amountid").keyup(function(){ 
            var amount1=parseFloat($("#amountid").val());

            var isVisible1=$("#totaldiv").is(":visible"); //TotalSalaryDB
            if(isVisible1){
                var total1=parseFloat($("#total").val());
                if(amount1>total1){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Entered amount is greater than the total salary of the month'
                    });
                    $("#amountid").val("");
                }
          }
          var isVisible2=$("#TotalSalaryDB_div").is(":visible");
          if(isVisible2){
            var balance2=parseFloat($("#balanceofmonth").val());
            //alert(balance2);
            if(amount1>balance2){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Entered amount is greater than the balance should be paid'
                    });
                    $("#amountid").val("");
                }
          }
            calBalance();
        });
        //calculate the balance while bonus typing
        $('#employeeExpensediv').on('keyup', '#bonus', function(e){ 
            calBalance();
        });
        $('#employeeExpensediv').on('change', '#month', function(e){ 
            paidOfThisMonth();
            $("#amountid").val('');
            $("#balance").val('');

        });
        $('#employeeExpensediv').on('mouseup keyup', '#year', function(e){ 
            paidOfThisMonth();
        });

        $("#expenseCatid").change(function(){
            var expenid=$("#expenseCatid").val();
            if(expenid==1){  
                $("#pymntAlert").show();
                var employeelist=   '<label for="employeeid" class="col-3 col-form-label">Employee<span class="text-danger">*</span></label>'+                                  
                                    '<div class="col-9">'+                                    
                                        '<input class="form-control"  id="employee-auto" placeholder="Select" required>'+
                                        '<input type="hidden" class="form-control" name="employee" id="employee-id">'+
                                    '</div>';
                                    $("#employeediv").html(employeelist);         
                //autoload items 
                var availableStaffs=[
                    <?php
                    foreach ($staffs as $staff)
                    {
                    echo '{ label: "'.$staff->staff_name.'", value:"'.$staff->staff_id.'" },';
                    }
                    ?>
                ];
                $("#employee-auto").autocomplete({
                    source: availableStaffs,
                    select: function(event, ui) {                       
                            event.preventDefault();
                            $("#employee-auto").val(ui.item.label);
                            $('#employee-id').val(ui.item.value);  
                            var empid= $('#employee-id').val();
                            loadEmplyeeExpense(empid);
                        },      
                });
            }
            else{
                $("#pymntAlert").hide();
                $("#employeediv").empty(); 
                $("#employeeExpensediv").empty();
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
        $('#employeeExpensediv').on('focusout', '.dcmlFixDynmc', function(e){            
            var num = $(this).val();
            var para = $(this).parent();
            var DcmlDigts = decimalPlaces(num);
            if(DcmlDigts<2 && num!=''){
                var newvalue = parseFloat(num).toFixed(2);
                $(this).val(newvalue);
            }
         });

         //payment mode if cheque , load bank accounts,
         $('input[type=radio][name=paymentMode]').change(function() {
            if (this.value==1) {
                $("#selectBankDiv").empty();
            }
            else if (this.value==2) {
                $.ajax({ //get all banks to dropdown
                    type: 'post',
                    url: "<?php echo base_url('banks/getAllBanks'); ?>",	
                    async: false,
                    dataType:'json',  
                    success: function(data){
                        var options;
                        var len = data.length;    
                        for( var i = 0; i<len; i++){
                        var bankname = data[i]['bank_name'];
                        var id = data[i]['bank_id'];                    
                        options+="<option value='"+id+"'>"+bankname+"</option>";
                    }
                        var banklist='<div class="form-group row">'+
                                        '<label for="selectbank" class="col-3 col-form-label">Bank Account:<span class="text-danger">*</span></label>'+
                                        '<div class="col-9">'+
                                            '<select class="form-control" name="selectbank" id="selectbank" required>'+
                                                '<option value="">-Select-</option>'+
                                                options+                                                                 
                                            '</select>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-group row">'+
                                        '<label for="cheqno" class="col-3 col-form-label">Cheque no:<span class="text-danger">*</span></label>'+
                                        '<div class="col-9">'+
                                            '<input class="form-control " type="text" placeholder="Enter Cheque number " name="cheqno" id="cheqno" required>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-group row">'+
                                        '<label for="cheqdate" class="col-3 col-form-label">Cheque date:<span class="text-danger">*</span></label>'+
                                        '<div class="col-9">'+
                                            '<input class="form-control datepicker" name="cheqdate" value="" id="cheqdate" required>'+
                                        '</div>'+
                                    '</div>';                    
                        $("#selectBankDiv").html(banklist);
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
            
        //Buttons examples
        table = $('#datatable-buttons').DataTable({
            "order": [[ 0, "desc" ]],
        buttons: ['copy', 'excel', 'pdf']
        });
        table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    }); 
    $(document)
    
</script> 
