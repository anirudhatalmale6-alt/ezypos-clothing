        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group row">
                            <label for="customer-auto" class="col-4 col-form-label">Customer</label>                                    
                            <div>
                                <input class="form-control "  id="customer-auto" placeholder="Enter Customer Name" >
                                <input type="hidden" class="form-control" name="customer" id="customer-id">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="invoice-auto" class="col-4 col-form-label">Invoice Id</label>                                    
                            <div>
                                <input class="form-control"  id="invoice-auto" placeholder="Enter Invoice Id" >
                                <input type="hidden" class="form-control" name="invoice" id="invoice-id">
                            </div>
                        </div>
                        <hr>
                        <div class="">
                            <div> <!--payment form-->
                                <div class="form-group row">
                                    <label for="datepicker" class="col-4 col-form-label">Payment Date<span class="text-danger">*</span></label>
                                    <div class="">
                                        <input class="form-control datepic" value="" id="datepicker">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cashvalue" class="col-4 col-form-label">Cash:</label>
                                <div class="">
                                    <input class="form-control DecimalFix staticValication" type="text" placeholder="Enter Cash Value 0.00" 
                                    name="cashvalue" id="cashvalue" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$">
                                </div>
                            </div> 
                            <div class="form-group row">
                                <div class="col-4"></div>
                                <div class="checkbox checkbox-primary">
                                    <input id="tickCheque" name="cheque" type="checkbox">
                                    <label for="tickCheque">Cheque</label>
                                </div>
                            </div> 
                            <form action="" id="chequeform">
                                <div class="field_wrapper" id="cheaqueDetailsDiv" style="display:none;">
                                    <div class="form-group row">
                                        <label for="amount" class="col-4 col-form-label">Amount:<span class="text-danger">*</span></label>
                                        <div class="">
                                            <input class="form-control DecimalFix staticValication staticChqAmount" type="text" placeholder="Enter Amount 0.00" name="amount" id="amount" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bank" class="col-4 col-form-label">Bank:<span class="text-danger">*</span></label>
                                        <div class="">
                                            <input class="form-control" type="text" placeholder="Bank Name" name="bank" id="bankname" required >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="chequeno" class="col-4 col-form-label">Cheque no:<span class="text-danger">*</span></label>
                                        <div class="">
                                            <input class="form-control" type="text" placeholder="Cheque Number" name="chequeno" id="chequeno" required >
                                        </div>                                            
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-4 col-form-label">Date:<span class="text-danger">*</span></label>
                                        <div class="">
                                            <input class="form-control datepic" id="chequedate"   name="chequedate" required > 
                                        </div>
                                        <a href="javascript:void(0);" class="add_button" style="display:none;" title="Add another cheque"><i class="fa fa-plus-square" style="font-size:24px;color:green"></i></a>
                                    </div>
                                </div>
                                <div class="pull-right">                                                               
                                <button href="javascript;" id="save" class="btn btn-primary waves-effect"><i class="fa fa-save"></i></button>
                                <div id="div_result"></div>
                            </div>
                            </form>
                           
                        </div>
                    </div> 
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">                        
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr style="background-color: #C0C0C0">
                                        <th>Customer</th>
                                        <th>Invoice id</th>                                        
                                        <th>Paymnt type</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyID">                                          
                                </tbody>
                            </table>
                        </div>                        
                        <div  id="TblinvceInfoDiv" style="display:none;float:right;">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-9">                        
                                <div class=""> 
                                    <table id="datatable2-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <tbody id="TblinvceInfo">                                          
                                        </tbody>
                                    </table>
                                </div>
                            </div>     
                        </div>
                    </div>            
                </div>
               
                    
               
            </div> <!-- container  <div class="row" style=""> -->

<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
    $(function(){
        //Dynamic datepicker
        $('.field_wrapper').on('click',".add_button", function(){
            $('.datepic').datepicker();
            $('.datepic').datepicker( "option", "dateFormat", "yy-mm-dd" );
            $('.datepic').datepicker().datepicker("setDate", "0");
        });
        $(".datepic" ).datepicker();
        $(".datepic" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
        $(".datepic").datepicker().datepicker("setDate", "0");
        $('form').parsley();
        $("#save").prop('disabled', true);

        // load all invoices for selected customer
        var totalOutstanding=0;
        function getCustomerPayments(){ //payment by customer id
            $("#TblinvceInfoDiv").show();
            var cusid=$('#customer-id').val();
            $.ajax({
					type: 'post',
					url:'<?php echo base_url()?>payments/getCustomerPayments',
                    data:  {cusid: cusid},
					async:false,
					dataType:'json',
					success:function(data){
                        var rows = '';
                        var cash='';
                        var cheq='';
                        var firstRow='';
                        totalOutstanding=data[1];
                        var i;
						for(i=0; i<data[0].length; i++){
                         
                        var cashAmnt=data[0][i].cashAmnt;
                        var paidDate=data[0][i].paidDate;
                        var chqAmnt=data[0][i].ChqAmnt;
                        var givnDate=data[0][i].ChqGivenDate;

                        if(cashAmnt!=null){
                            var cashAmnt_array = cashAmnt.split(',');
                        }
                        if(paidDate!=null){
                            var paidDate_array = paidDate.split(',');
                        }
                        if(chqAmnt!=null){
                            var chqAmnt_array = chqAmnt.split(',');
                        }
                        if(givnDate!=null){
                            var givnDate_array = givnDate.split(',');
                        }
                        if(cashAmnt!=null){
                        firstRow='<tr>'+
                                    '<td>'+data[0][i].cus_name+'</td>'+
                                    '<td>'+"AS00"+data[0][i].sale_id+'</td>'+
                                    '<td>'+"Cash"+'</td>'+                                   
                                    '<td>'+paidDate_array[0]+'</td>'+
                                    '<td style="Text-align: right;">'+cashAmnt_array[0]+'</td>'+
                                '</tr>';                             
                            var x;
                            for(x=1; x<cashAmnt_array.length; x++){
                                if(cashAmnt_array[x]>0){
                                    cash+='<tr>'+
                                        '<td>'+'</td>'+
                                        '<td>'+'</td>'+
                                        '<td>'+"Cash"+'</td>'+                                   
                                        '<td>'+paidDate_array[x]+'</td>'+
                                        '<td style="Text-align: right;">'+cashAmnt_array[x]+'</td>'+
                                    '</tr>';
                                }
                            }
                        }
                        if(chqAmnt!=null){
                            var j;
                            for(j=0; j<chqAmnt_array.length; j++){
                                if(chqAmnt_array[j]>0){
                                    cheq+='<tr>'+
                                        '<td>'+'</td>'+
                                        '<td>'+'</td>'+
                                        '<td>'+"Cheque"+'</td>'+                                   
                                        '<td>'+givnDate_array[j]+'</td>'+
                                        '<td style="Text-align: right;">'+chqAmnt_array[j]+'</td>'+
                                    '</tr>';
                                }
                            }
                        }                              
                        rows+=(firstRow+cash+cheq); 
                        cheq='';
                        cash='';  
                        firstRow='';
						}
							$('#tbodyID').html(rows);
                            var outstanding='<tr>'+                                            
                                                '<td>Over all outstanding</td>'+
                                                '<td>'+totalOutstanding+'</td>'+
                                            '</tr>';
                            $('#TblinvceInfo').html(outstanding);						
					},
					error: function(){
						alert('error data collection');
					}
				});
        }
        var ttlOutstanding=0;
        function getInvoiceByID(){ //
            $("#TblinvceInfoDiv").show();
            var invid=$('#invoice-id').val();
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>payments/getInvoiceByID',
                data:  {invid: invid},
                async:false,
                dataType:'json',
                success:function(data){ //pymntlog_amount,pymntlog_date 
                        var grandtotal=data[0][0].sale_grandtotal;
                        var invcOutstanding=data[2].cus_pay_credit;
                        var paidOfCash=parseFloat(data[2].cus_pay_cash);
                        var chqtotal=parseFloat(data[3].chqtotal);
                        ttlOutstanding=parseFloat(data[4]);
                        var totalPaid=(paidOfCash+chqtotal).toFixed(2);
                        var frstRow='<tr>'+
                                        '<td>'+data[0][0].cus_name+'</td>'+
                                        '<td>'+"AS00"+invid+'</td>'+                                    
                                        '<td>'+"Cash"+'</td>'+
                                        '<td>'+data[0][0].pymntlog_date+'</td>'+
                                        '<td style="Text-align: right;">'+data[0][0].pymntlog_amount+'</td>'+
                                    '</tr>';
                        $('#tbodyID').html(frstRow);
                	    var rows = '';
						var i;
						for(i=1; i<data[0].length; i++){                                               
                        rows+= '<tr>'+
                                    '<td>'+'</td>'+
                                    '<td>'+'</td>'+                                    
                                    '<td>'+"Cash"+'</td>'+
                                    '<td>'+data[0][i].pymntlog_date+'</td>'+
                                    '<td style="Text-align: right;">'+data[0][i].pymntlog_amount+'</td>'+
                                '</tr>';
						}
                        $('#tbodyID').append(rows);
                        var cheqs = '';
						var i;
						for(i=0; i<data[1].length; i++){                            
                        cheqs+='<tr>'+
                                    '<td>'+'</td>'+
                                    '<td>'+'</td>'+                                    
                                    '<td>'+"Cheque"+'</td>'+
                                    '<td>'+data[1][i].cus_cheque_givendate+'</td>'+
                                    '<td style="Text-align: right;">'+data[1][i].cus_cheque_amount+'</td>'+
                                '</tr>';
						}
                        $('#tbodyID').append(cheqs);
                        var invoiceInfo='<tr>'+                                            
                                            '<td>Invoice total</td>'+
                                            '<td>'+grandtotal+'</td>'+
                                        '</tr>'+
                                        '<tr>'+                                            
                                            '<td>Total paid</td>'+
                                            '<td>'+totalPaid+'</td>'+
                                        '</tr>'+
                                        '<tr>'+                                            
                                            '<td>Invoice outstanding total</td>'+
                                            '<td>'+invcOutstanding+'</td>'+
                                        '</tr>'+
                                        '<tr>'+                                            
                                            '<td>Over all outstanding</td>'+
                                            '<td>'+ttlOutstanding+'</td>'+
                                        '</tr>';
                        $('#TblinvceInfo').html(invoiceInfo);
                },
                error: function(){
                    alert('error data collection');
                }
            });
        }
        

        var availableCustomers = [
        <?php
         foreach ($customers as $customer)
        {
           echo '{ label: "'.$customer->cus_name.'", value:"'.$customer->cus_id.'" },';
        }
        ?>
        ];
        $( "#customer-auto" ).autocomplete({
            source: availableCustomers,
            select: function(event, ui) {
                    event.preventDefault();
                    $("#customer-auto").val(ui.item.label);
                    $('#customer-id').val(ui.item.value);
                    var slectedsup = ui.item.value;
                    getCustomerPayments();
                    $("#invoice-auto").val("");
                    $("#save").prop('disabled', false); 
                },
        });

        var availableInvoice = [
        <?php
         foreach ($invoices as $invoice)
        {
           echo '{ label: "'."AS00".$invoice->sale_id.'", value:"'.$invoice->sale_id.'" },';
        }
        ?>
        ];
        $("#invoice-auto" ).autocomplete({
            source: availableInvoice,
            select: function(event, ui) {
                    event.preventDefault();
                    $("#invoice-auto").val(ui.item.label);
                    $('#invoice-id').val(ui.item.value);
                    var slectedsup = ui.item.value;
                    getInvoiceByID();
                    $("#customer-auto").val("");
                    $("#save").prop('disabled', false);
                },
        });
        //Cheque
        // show & hide cheque details box
        $("#tickCheque").change(function() {
            if(this.checked) {
                $("#cheaqueDetailsDiv").show("fast");
               // calculateCredit();
            }
            else{
                $("#cheaqueDetailsDiv").hide("fast");
                //calCreditWithOutChq();
            }
        });
        $("#customer-auto").keyup(function(){
            $("#invoice-auto").val("");
            $("#save").prop('disabled', true);
        });
        $("#invoice-auto").keyup(function(){
            $("#customer-auto").val("");
            $("#save").prop('disabled', true);
        });
        //multiple cheques
        var maxCheques = 5; 
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper

        var chequeHTML ='<div id="chequeDIV">'+
                            '<hr>'+
                            '<div class="form-group row">'+
                                '<label for="amount" class="col-4 col-form-label">Amount:</label>'+
                                '<div class="">'+
                                    '<input class="form-control dcmlFixDynmc validationDynmic dynmcChqAmount" type="text" placeholder="Enter Amount 0.00"'+
                                    'name="amount[]" id="" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$">'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-group row">'+
                                '<label for="bank" class="col-4 col-form-label">Bank:</label>'+
                                '<div class="">'+
                                    '<input class="form-control" type="text" placeholder="Bank Name" '+
                                    'name="bank[]" id="" required >'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-group row">'+
                                '<label for="chequeno" class="col-4 col-form-label">Cheque no:</label>'+
                                '<div class="">'+
                                    '<input class="form-control" type="text" placeholder="Cheque no" '+
                                    'name="chequeno[]" id="" required >'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-group row">'+
                                '<label for="" class="col-4 col-form-label">Date:</label>'+
                                '<div class="">'+
                                    '<input class="form-control datepic" value=""  name="chequedate[]" required>'+
                                '</div>'+
                                '<a href="javascript:void(0);" class="remove_button" title="Remove cheque"><i class="fa fa-minus-square" style="font-size:24px;color:red"></i></a>'+
                            '<div>'+
                        '</div>'; 
                var counter = 1;

        //Once add button is clicked
        $(addButton).click(function(){
            if(counter < maxCheques){ 
                counter++;
                $(wrapper).append(chequeHTML);
            }
        });
                    
        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent().parent('div').remove(); //Remove field html
            counter--; //Decrement field counter
            //calculateCredit();
        });

        var ChqFormsubmittd=false;
        $("#chequeform").submit(function(e){
            e.preventDefault();
            ChqFormsubmittd=true;
        });

        //payment saving 
        $("#save").click(function(){ 
            if ($("#tickCheque").is(':checked')) {
                $("#chequeform").submit();
            }
            var cashvalue=0;
            cashvalue=parseFloat($("#cashvalue").val());
            if(cashvalue==''||(!(cashvalue>0))){cashvalue=0;}

            var invcID=$("#invoice-auto").val();
            var custName=$("#customer-auto").val();

            var cheqTtl=0;
            if ($("#tickCheque").is(':checked')) {                    
                cheqTtl=parseFloat($("#amount").val());
            }
           
            var totalpaymnt=cashvalue+cheqTtl;
            if(totalpaymnt<0&&cashvalue<=0){  
                alert("Enter valid amount");
            }               
            else if(invcID!=''){ 
                if($("#tickCheque").is(':checked')) {
                    if(ChqFormsubmittd==true){
                        saveInvoicePayment();
                        ChqFormsubmittd=false;
                    }
                    else{
                        alert("Please fill the cheque fields");
                    }
                }
                else{
                    saveInvoicePayment();
                }
            }
            else if(custName!=''){ 
                if($("#tickCheque").is(':checked')) {
                    if(ChqFormsubmittd==true){
                        saveCusPayment();
                        ChqFormsubmittd=false;
                    }
                    else{
                        alert("Please fill the cheque fields");
                    }
                }
                else{
                    saveCusPayment();
                }
            }
            else if(invcID==''&&custName==''){
                alert("Please Enter a Invoice ID or Customer");
            }
            var openingBal=0;
            var custid=0;
            var newCash=0;
            var newCheqTtl=0;
            var NewOpeningBal=0;
            var invCredit=0;
            function saveInvoicePayment(){ // payment by invoice id, for only one invoice
                $("#save").prop('disabled', true);
                sale_ID= $('#invoice-id').val(); 
                date= $('#datepicker').val(); 
               
                
                getCustID();                
                getOpenningBalance(custid);
                newCash=cashvalue;
                newCheqTtl=cheqTtl;
                if(openingBal>0){
                    if(cashvalue>0){
                        newCash=cashvalue-openingBal;
                        NewOpeningBal=0;
                        if(newCash<0){
                            NewOpeningBal=(-newCash);
                            if(cheqTtl>0){
                                newCheqTtl=cheqTtl+newCash;
                                NewOpeningBal=0;
                                if(newCheqTtl<0){
                                    NewOpeningBal=(-newCheqTtl);
                                    newCheqTtl=0;
                                }
                            }
                            newCash=0;
                        }                    
                    } 
                    else if(cashvalue==0&&cheqTtl>0){
                        newCheqTtl=cheqTtl-openingBal;
                        NewOpeningBal=0;
                        if(newCheqTtl<0){
                            NewOpeningBal=(-newCheqTtl)
                            newCheqTtl=0;
                        }
                    }
                    updateOpenningBal(NewOpeningBal,custid);
                }        
                      
                    var paidCash=false;
                    var paidCheq=false;
                    var cashBal=0;
                    if(newCash>0){  
                    //get credit for entered invoice                      
                    getInvoiceCredit(sale_ID);
                        if(invCredit>newCash){
                                //payment log by cash
                                $.ajax({
                                    type: "Post",
                                    url:"<?php echo base_url('customerPayment/customerCashLog'); ?>",
                                    data: {saleID:sale_ID,cash:newCash,date:date},
                                    async: false,
                                    dataType: "json",
                                    success: function (res) {
                                    console.log("customer paymnt log saved"); 
                                    },
                                    error: function (err) {
                                        alert("customer payment log error");
                                    }
                                });
                                //update in payment table
                                $.ajax({
                                    type: "Post",
                                    url:"<?php echo base_url('customerPayment/adjustCashCredit'); ?>",
                                    data: {saleID:sale_ID,cash:newCash,cheq:0},
                                    async: false,
                                    dataType: "json",
                                    success: function (res) {                                        
                                        paidCash=true;                        
                                    console.log("customer paymnt saved"); 
                                    },
                                    error: function (err) {
                                        alert("customer payment error");
                                    }
                                });
                        }
                        else if(invCredit<=newCash){
                                 //payment log by cash
                                 $.ajax({
                                    type: "Post",
                                    url:"<?php echo base_url('customerPayment/customerCashLog'); ?>",
                                    data: {saleID:sale_ID,cash:invCredit,date:date},
                                    async: false,
                                    dataType: "json",
                                    success: function (res) {
                                    console.log("customer paymnt log saved"); 
                                    },
                                    error: function (err) {
                                        alert("customer payment log error");
                                    }
                                });
                                cashBal=newCash-invCredit;
                                 //update in payment table
                                 $.ajax({
                                    type: "Post",
                                    url:"<?php echo base_url('customerPayment/adjustCashCredit'); ?>",
                                    data: {saleID:sale_ID,cash:invCredit,cheq:0},
                                    async: false,
                                    dataType: "json",
                                    success: function (res) {
                                        paidCash=true;                        
                                    console.log("customer paymnt saved"); 
                                    },
                                    error: function (err) {
                                        alert("customer payment error");
                                    }
                                });
                        }

                    
                    }
                    //alert("balance in cheq to settle sale's credit(newCheqTtl) "+newCheqTtl);
                    var cheqBal=0;
                    if(newCheqTtl>0){                                         
                    getInvoiceCredit(sale_ID); //get the credit for entered invoice  
                    //alert("invCredit "+invCredit);
                        if(invCredit>newCheqTtl){
                                //Add cheques /cheque log
                                var checked=0;
                                if ($("#tickCheque").is(':checked')){    // amount2= paid amount for that invoice
                                    checked=1
                                    var data = $('#chequeform').serialize()+"&saleID="+sale_ID+"&amount2="+newCheqTtl+"&date="+date;
                                    $.ajax({
                                        type: 'post',
                                        url: "<?php echo base_url('customerPayment/cusPaymentCheque'); ?>",
                                        data: data,
                                        async: false,
                                        dataType:'json',  
                                        success: function(response){                           
                                        },
                                        error: function() {
                                            alert("There was an error. Try again please!");
                                        }
                                    });
                                    //update in payment table
                                    $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('customerPayment/adjustCashCredit'); ?>",
                                        data: {saleID:sale_ID,cash:0,cheq:newCheqTtl},
                                        async: false,
                                        dataType: "json",
                                        success: function (res) {
                                            paidCheq=true;                        
                                        console.log("customer paymnt saved"); 
                                        },
                                        error: function (err) {
                                            alert("customer payment error");
                                        }
                                    });
                                }
                        }
                        
                        else if(invCredit<=newCheqTtl){
                                //Add cheques /cheque log
                                var checked=0;
                                if ($("#tickCheque").is(':checked')){     
                                    checked=1
                                    var data = $('#chequeform').serialize()+"&saleID="+sale_ID+"&amount2="+invCredit+"&date="+date;
                                    $.ajax({
                                        type: 'post',
                                        url: "<?php echo base_url('customerPayment/cusPaymentCheque'); ?>",
                                        data: data,
                                        async: false,
                                        dataType:'json',  
                                        success: function(response){                            
                                        },
                                        error: function() {
                                            alert("There was an error. Try again please!");
                                        }
                                    });
                                     //update in payment table
                                     $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('customerPayment/adjustCashCredit'); ?>",
                                        data: {saleID:sale_ID,cash:0,cheq:invCredit},
                                        async: false,
                                        dataType: "json",
                                        success: function (res) {                                          
                                            paidCheq=true;                       
                                        console.log("customer paymnt saved"); 
                                        },
                                        error: function (err) {
                                            alert("customer payment error");
                                        }
                                    });
                                }
                                cheqBal=newCheqTtl-invCredit;
                        }
                    }
                    if(paidCheq==true&&paidCash==true){
                        var str="Cash and Cheque"
                        invcPaymntSuccess(str);
                    }
                    else if(paidCash==true){
                        var str="Cash"
                        invcPaymntSuccess(str);
                    }
                    else if(paidCheq==true){ 
                        var str="Cheque"
                        invcPaymntSuccess(str);
                    }

                    var paidMore=cheqBal+cashBal;
                    //update customer +balance, if he paid more 
                    updateCusBalanceForInvoice(custid,paidMore);//custid,newCheqTtl,sale_ID
            
            }
            var cusPaidCash=false;
            var cusPaidChq=false;
            function saveCusPayment(){// payment by selecting invoices of a customer
                $("#save").prop('disabled', true);
                var cus_ID= $("#customer-id").val(); 
                var date= $("#datepicker").val();
                var cheqAmnt=0;
                cheqAmnt=parseFloat($("#amount").val());
                    if(cheqAmnt==''||(!(cheqAmnt>0))){cheqAmnt=0;}
                var saleCredit=0;
                if((!(cashvalue>0))&&(!(cheqAmnt>0))){
                        alert("Please Enter Valid Amounts");
                }
                if(cashvalue>0||cheqAmnt>0){                    
                    getOpenningBalance(cus_ID);
                    newCash=cashvalue;
                    newCheqTtl=cheqTtl;
                    if(openingBal>0){
                        if(cashvalue>0){
                            newCash=cashvalue-openingBal;
                            NewOpeningBal=0;
                            if(newCash<0){
                                NewOpeningBal=(-newCash);
                                if(cheqTtl>0){
                                    newCheqTtl=cheqTtl+newCash;
                                    NewOpeningBal=0;
                                    if(newCheqTtl<0){
                                        NewOpeningBal=(-newCheqTtl);
                                        newCheqTtl=0;
                                    }
                                }
                                newCash=0;
                            }                    
                        } 
                        else if(cashvalue==0&&cheqTtl>0){
                            newCheqTtl=cheqTtl-openingBal;
                            NewOpeningBal=0;
                            if(newCheqTtl<0){
                                NewOpeningBal=(-newCheqTtl)
                                newCheqTtl=0;
                            }
                        }
                        updateOpenningBal(NewOpeningBal,cus_ID);
                    }
                }    
                var totalPaid=newCash+newCheqTtl;
                if(totalPaid>0){ //after deduction openning balance
                    updateCusBalanceForCustomer(cus_ID,totalPaid); 
                }
                          

                if(newCash>0){                                       
                    $.ajax({//out standing invoices of selected customer
                        type: 'post',
                        url: "<?php echo base_url('customerPayment/OutStandingInvoices'); ?>",
                        data: {cusID:cus_ID},
                        async: false,
                        dataType:'json',  
                        success: function(data){                                            
                            var k;
                            for(k=0; k<data.length; k++){ 
                                var saleid=data[k].sale_id;
                                var creditOfSale=data[k].cus_pay_credit; 
                                cashLog(saleid,creditOfSale,cus_ID);
                            }
                        },
                        error: function() {
                            alert("Try again please!");
                        }
                    });            
                }
                if(newCheqTtl>0){
                    var amount=$("#amount").val(); 
                    var cusChqeID=0;
                    //Add cheques in main cheque table
                    var checked=0;
                    if ($("#tickCheque").is(':checked')){     
                        checked=1
                        var data = $('#chequeform').serialize()+"&saleID="+0+"&date="+date+"&amount2="+amount;
                        $.ajax({
                            type: 'post',
                            url: "<?php echo base_url('customerPayment/cusPaymentCheque'); ?>",
                            data: data,
                            async: false,
                            dataType:'json',  
                            success: function(cusChqID){
                                cusChqeID=cusChqID;
                                console.log("cusChqID: "+cusChqID);                 
                            },
                            error: function() {
                                alert("There was an error. Try again please!");
                            }
                        });
                    }
                    $.ajax({//outstanding invoices of selected customer
                        type: 'post',
                        url: "<?php echo base_url('customerPayment/OutStandingInvoices'); ?>",
                        data: {cusID:cus_ID},
                        async: false,
                        dataType:'json',  
                        success: function(data){                                            
                            var k;
                            for(k=0; k<data.length; k++){ 
                                var saleid=data[k].sale_id;
                                var creditOfSale=data[k].cus_pay_credit; 
                                chequelog(saleid,creditOfSale,cusChqeID,cus_ID);
                            }
                        },
                        error: function() {
                            alert("Try again please!");
                        }
                    });                                   
                }                
                function cashLog(sale_ID,creditOfSale,cus_ID){//payment log by cash                    
                    if(newCash>0){
                         
                        // this case run only for 1st saleid
                        if(creditOfSale>=newCash){ 
                                $.ajax({
                                    type: "Post",
                                    url:"<?php echo base_url('customerPayment/customerCashLog'); ?>",
                                    data: {saleID:sale_ID,cash:newCash,date:date},
                                    async: false,
                                    dataType: "json",
                                    success: function (res) {
                                        adjustCreditForCash(newCash);
                                        newCash=0;
                                    console.log("customer paymnt log saved"); 
                                    },
                                    error: function (err) {
                                        alert("customer payment log error");
                                    }
                                });
                        }                        
                        else if(creditOfSale<newCash){ // newCash<0 & creditOfSale<newCash
                            $.ajax({
                                type: "Post",
                                url:"<?php echo base_url('customerPayment/customerCashLog'); ?>",
                                data: {saleID:sale_ID,cash:creditOfSale,date:date},
                                async: false,
                                dataType: "json",
                                success: function (res) {
                                    newCash=newCash-creditOfSale;
                                    console.log("customer paymnt log saved"); 
                                    adjustCreditForCash(creditOfSale);
                                },
                                error: function (err) {
                                    alert("customer payment log error");
                                }
                            });
                        }
                        function adjustCreditForCash(creditOfSale){
                            //make change in main-payment/credit                 
                            $.ajax({
                                type: "Post",
                                url:"<?php echo base_url('customerPayment/adjustCashCredit'); ?>",
                                data: {saleID:sale_ID,cash:creditOfSale,cheq:0},
                                async: false,
                                dataType: "json",
                                success: function (res) {
                                    //reload invoices  
                                    cusPaidCash=true;                       
                                console.log("customer paymnt saved"); 
                                },
                                error: function (err) {
                                    alert("customer payment error");
                                }
                            });
                        }     
                    }
                }

                
                function chequelog(sale_ID,creditOfSale,cusChqeID,cus_ID){//payment log by cheque   
                    if(newCheqTtl>0&&($("#tickCheque").is(':checked'))){ 
                        // this case run only for 1st saleid
                        if(creditOfSale>=newCheqTtl){  
                            //Add cheques /cheque log
                            var checked=0;    
                                checked=1
                                $.ajax({
                                    type: 'post',
                                    url: "<?php echo base_url('customerPayment/customerPayCheque'); ?>",
                                    data:{cusChqID:cusChqeID,saleID:sale_ID,Amnt:newCheqTtl},
                                    async: false,
                                    dataType:'json',  
                                    success: function(response){
                                        adjustCreditForCheq(newCheqTtl); 
                                        newCheqTtl=0;                 
                                    },
                                    error: function() {
                                        alert("There was an error. Try again please!");
                                    }
                                });
                        }  
                      
                        else if(creditOfSale<newCheqTtl){ // newCheqTtl<0 & creditOfSale<newCheqTtl
                                //Add cheques /cheque log
                                var checked=1; 
                                    $.ajax({
                                        type: 'post',
                                        url: "<?php echo base_url('customerPayment/customerPayCheque'); ?>",//
                                        data:{cusChqID:cusChqeID,saleID:sale_ID,Amnt:creditOfSale},
                                        async: false,
                                        dataType:'json',  
                                        success: function(response){
                                            newCheqTtl=newCheqTtl-creditOfSale;
                                            adjustCreditForCheq(creditOfSale);         
                                        },
                                        error: function() {
                                            alert("There was an error. Try again please!");
                                        }
                                    });
                        }
                        function adjustCreditForCheq(Amnt){
                            //make change in main-payment/credit                 
                            $.ajax({
                                type: "Post",
                                url:"<?php echo base_url('customerPayment/adjustCashCredit'); ?>",
                                data: {saleID:sale_ID,cash:0,cheq:Amnt},
                                async: false,
                                dataType: "json",
                                success: function (res) {
                                    //reload invoices
                                   cusPaidChq=true;                        
                                console.log("customer paymnt saved"); 
                                },
                                error: function (err) {
                                    alert("customer payment error");
                                }
                            });
                        }     
                    }
                }
                if(cusPaidCash==true&&cusPaidChq==true){
                    PymntSuccess("Cash and Cheaque");
                }
                else if(cusPaidCash==true){
                    PymntSuccess("Cash");
                }
                else if(cusPaidChq==true){
                    PymntSuccess("Cheaque");
                }
            }
            function getInvoiceCredit(sale_ID){
                $.ajax({
                        type: "Post",
                        url:"<?php echo base_url('customerPayment/getInvoiceCredit'); ?>",
                        data: {saleID:sale_ID},
                        async: false,
                        dataType: "json",
                        success: function (res) {
                            invCredit=res;
                        },
                        error: function (err) {
                            alert("customer payment log error");
                        }
                    });
            }
                  

            //upadte openning balance of customer
            function updateOpenningBal(NewOpeningBal,cus_ID){
                $.ajax({
                        type: "Post",
                        url:"<?php echo base_url('customerPayment/updateOpnningBal'); ?>",
                        data: {cusID:cus_ID,value:NewOpeningBal},
                        async: false,
                        dataType: "json",
                        success: function (res) {
                            alert("Previous openning balance "+openingBal+", Deducted LKR"+(openingBal-NewOpeningBal)+"/=");
                        },
                        error: function (err) {
                            alert("Error in customer balance");
                        }
                    });
            }

            function updateCusBalanceForInvoice(cus_ID,balnce){   
                     //update customer balance =>credit in (-) in customer balance table
                    if(balnce>0){
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('customerPayment/cusBalance'); ?>",
                            data: {cusID:cus_ID,bal:balnce},
                            async: false,
                            dataType: "json",
                                success: function (res) {
                            },
                            error: function (err) {
                                alert("Error in customer balance update");
                            }
                        });
                    }
            }
            function updateCusBalanceForCustomer(cus_ID,totalPaid){
                var CusCredit=0;
                $.ajax({// get total credited amount of customer (opennig balance not included)
                        type: "Post",
                        url:"<?php echo base_url('customerPayment/cusTtlCredit'); ?>",
                        data: {cusID:cus_ID},
                        async: false,
                        dataType: "json",
                        success: function (res) {
                            CusCredit=res.credits;
                        },
                        error: function (err) {
                            alert("Error in customer balance");
                        }
                    });
                    var blnce=totalPaid-CusCredit;
                    if(blnce>0){
                        updateCusBalanceForInvoice(cus_ID,blnce);
                    }
            }
            function getOpenningBalance(cus_ID){                
                $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('customerPayment/cusOpenningBalnce'); ?>",
                    data: {cusID:cus_ID},
                    async: false,
                    dataType: "json",
                    success: function (res) {
                        openingBal=res;
                    },
                    error: function (err) {
                        alert("Error in customer balance");
                    }
                });
            }
            function getCustID(){                    
                $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('customerPayment/getCustomerID'); ?>",
                    data: {saleID:sale_ID},
                    async: false,
                    dataType: "json",
                    success: function (res) {
                        custid=res;
                    },
                    error: function (err) {
                        alert("customer payment log error");
                    }
                });
            }
            function PymntSuccess(str){
                getCustomerPayments();
                alert(str+" Payment Added");
                $('#tickCheque').prop('checked', false);
                $("#cheaqueDetailsDiv").hide("fast");
                $("#chequeDIV").remove();
                $("#amount").val("");
                $("#bankname").val("");
                $("#chequeno").val("");
            }
            function invcPaymntSuccess(str){
                getInvoiceByID();
                alert(str+" Payment Added");
                $('#tickCheque').prop('checked', false);
                $("#cheaqueDetailsDiv").hide("fast");
                $("#chequeDIV").remove();
                $("#amount").val("");
                $("#bankname").val("");
                $("#chequeno").val("");
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
            else if(DcmlDigts>2){
                var newvalue = parseFloat(num).toFixed(2);
                $(this).val(newvalue);                
            }
         });
         // fix two decimal points for dynamic
        $('#tbodyID,.field_wrapper').on('focusout', '.dcmlFixDynmc', function(e){            
            var num = $(this).val();
            var para = $(this).parent();
            var DcmlDigts = decimalPlaces(num);
            if(DcmlDigts<2 && num!=''){
                var newvalue = parseFloat(num).toFixed(2);
                $(this).val(newvalue);
            }
         });
                    

    }); 
    $(document)
    
</script> 