        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- need changes -->        
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group row">
                            <label for="supplier-auto" class="col-4 col-form-label">Supplier</label>                                    
                            <div>
                                <input class="form-control "  id="supplier-auto" placeholder="Enter Supplier Name" >
                                <input type="hidden" class="form-control" name="supplier" id="supplier-id">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="grn-auto" class="col-4 col-form-label">Grn ID</label>                                    
                            <div>
                                <input class="form-control"  id="grn-auto" placeholder="Enter Grn Id" >
                                <input type="hidden" class="form-control" name="grn" id="grn-id">
                            </div>
                        </div>
                        <hr>
                        <div> <!--payment form-->
                                <div class="form-group row" id="out_cal_div">
                                   
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
                            <div class="class-chequesinhand cheaqueDetailsDiv" style="display:none;">
                                <div class="form-group row">
                                    <label for="pCheques" class="col-4 col-form-label">Select Cheques:</label>
                                    <div class="">
                                        <select class="form-control" name="pCheques" id="pCheques"></select>
                                    </div>
                                </div> 
                                    <form action="" id="chequeInHandform">
                                        <div id="cheqsInHandDiv"></div>  
                                    </form> 
                                <hr>
                                <a href="javascript:void(0);" class="add_button" style="" title="Add another cheque"><i class="fa fa-plus-square" style="font-size:24px;color:green"></i></a>
                            </div>
                            <form action="" id="chequeform">
                                <div class="field_wrapper cheaqueDetailsDiv2" style="display:none;">
                                    <div class="form-group row">
                                        <label for="amount" class="col-4 col-form-label">Amount:<span class="text-danger">*</span></label>
                                        <div class="">
                                            <input class="form-control DecimalFix staticValication" type="text" placeholder="Enter Amount 0.00" name="amount" id="amount" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$">
                                        </div>
                                    </div>
                                    <div class="form-group row selectBankDiv"></div>
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
                                    </div>
                                    <a href="javascript:void(0);" class="remove_button" title="Remove cheque"><i class="fa fa-minus-square" style="font-size:24px;color:red"></i></a>
                                </div>
                                <div class="pull-right">                                                               
                                <button href="javascript:void(0);" id="save" class="btn btn-primary waves-effect"><i class="fa fa-save"></i></button>
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
                                        <th>Supplier</th>
                                        <th>Grn ID</th> 
                                        <th>Grn Code</th>                                       
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
        $("#cashvalue").change(function(e){
         var due_payment_val_w = $("#due_payment").html();
         var cashvalue_w = $("#cashvalue").val();
         alert("load da ||"+due_payment_val_w+"|"+cashvalue_w);
         if(cashvalue_w==""){
            $("#due_payment").html(due_payment_val_w);
         }else{
           var new_due_payment= due_payment_val_w-cashvalue_w;
            $("#due_payment").html(new_due_payment);
         } 
        });


    
    
    
    $(function() {
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

        // load all grns for selected supplier
        var OutstandingSup=0;
        function getSupPayments(){ //payment by supplier id
            $("#TblinvceInfoDiv").show();
            var supid=$('#supplier-id').val();
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>payments/getSupPayments',
                data:  {supid: supid},
                async:false,
                dataType:'json',
                success:function(data){
                    var msg=data[0];
                    if(msg==1){
                        alert("No GRN or No Credit for this supplier");
                    }
                    var rows = '';
                    var cash='';
                    var cheq='';
                    var cheqlog='';
                    var firstRow='';
                    OutstandingSup=data[1];
                    if(OutstandingSup==false){OutstandingSup=0;}
                    var i;
                    for(i=0; i<data[0].length; i++){                         
                    var cashAmnt=data[0][i].cashAmnt;
                    var paidDate=data[0][i].paidDate;
                    var chqAmnt=data[0][i].chqAmnt;
                    var givnDate=data[0][i].ChqGivenDate;

                    var s3=data[0][i].sup_name;
                    var sl=data[0][i].grn_id;
                    var s2=data[0][i].grn_code;                    

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
                    
                    firstRow='<tr>'+ //changes
                                '<td>'+data[0][i].sup_name+'</td>'+
                                '<td>'+""+data[0][i].grn_id+'</td>'+
                                '<td>'+""+data[0][i].grn_code+'</td>'+
                                '<td></td>'+                                   
                                '<td></td>'+
                                '<td style="Text-align: right;"></td>'+
                            '</tr>';
                    if(cashAmnt!=null){                                                 
                        var x;
                        for(x=0; x<cashAmnt_array.length; x++){
                            if(cashAmnt_array[x]>0){
                                cash+='<tr>'+
                                            '<td>'+'</td>'+
                                            '<td>'+'</td>'+
                                            '<td></td>'+
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
                                    '<td></td>'+
                                    '<td>'+"Cheque"+'</td>'+                                   
                                    '<td>'+givnDate_array[j]+'</td>'+
                                    '<td style="Text-align: right;">'+chqAmnt_array[j]+'</td>'+
                                '</tr>';
                            }
                        }
                    }   
                    rows+=(firstRow+cash+cheq);  //changes
                    cheq='';
                    cash='';  
                    firstRow='';
                    cheqlog='';
                    }
                       var OutstandingSup_val=Number(OutstandingSup).toFixed(2);
                      // var OutstandingSup_val=Number(OutstandingSup_val);
                        $('#tbodyID').html(rows);
                        var outstanding='<tr>'+                                            
                                            '<td>Over all outstanding</td>'+
                                            '<td id="tot_sup_out">'+OutstandingSup+'</td>'+
                                        '</tr>';
                        var outstanding_calc= '<div class="form-group row">'+
                            '<label for="grn-auto" class="col-6 col-form-label">Outstanding Payment</label>'+                                  
                            '<div class="col-6" style="text-align:right;background-color:red" id="due_payment">'+
                                +OutstandingSup_val+
                            '</div>'+
                        '</div>';   
                        $('#TblinvceInfo').html(outstanding);						
                        $('#out_cal_div').html(outstanding_calc);						
                },
                error: function(){
                    alert('error data collection');
                }
				});
        }
        
//        // show Outstanding calculations
//        $("#tot_sup_out").change(function() {
//        alert(OutstandingSup);
////            if(this.checked) {
////                $(".cheaqueDetailsDiv").show("fast");
////            }
////            else{
////                $(".cheaqueDetailsDiv").hide("fast");
////            }
//        });
        function getGrnByID(){
            $("#TblinvceInfoDiv").show();
            var grnid=$('#grn-id').val();
            alert("grnid "+grnid);
            $.ajax({
                type: 'post',
                url:'<?php echo base_url()?>payments/getGrnByID',
                data:  {grnid: grnid},
                async:false,
                dataType:'json',
                success:function(data){ //pymntlog_amount,pymntlog_date 
                alert(data);
                        var grandtotal=data[0][0].grn_grandtotal;
                        var grnOutstanding=data[2].sup_pay_credit;
                        var paidOfCash=parseFloat(data[2].sup_pay_cash);
                        var chqtotal=parseFloat(data[3].chqtotal);
                        if((isNaN(chqtotal))==true){chqtotal=0;}
                        ttlOutstanding=parseFloat(data[4]);
                        var totalPaid=(paidOfCash+chqtotal).toFixed(2);
                        var frstRow='<tr>'+
                                        '<td>'+data[0][0].sup_name+'</td>'+
                                        '<td>'+""+grnid+'</td>'+    
                                        '<td>'+data[0][0].grn_code+'</td>'+                                
                                        '<td></td>'+
                                        '<td></td>'+
                                        '<td style="Text-align: right;"></td>'+
                                    '</tr>';
                        $('#tbodyID').html(frstRow);
                	    var rows = '';
						var i;
						for(i=0; i<data[0].length; i++){ 
                            var cashLogAmnt=data[0][i].paycashlog_amount;
                            if(cashLogAmnt>0){
                                rows+= '<tr>'+
                                    '<td>'+'</td>'+
                                    '<td>'+'</td>'+  
                                    '<td></td>'+                                  
                                    '<td>'+"Cash"+'</td>'+
                                    '<td>'+data[0][i].paycashlog_date+'</td>'+
                                    '<td style="Text-align: right;">'+cashLogAmnt+'</td>'+
                                '</tr>';
                            }                                             
						}
                        $('#tbodyID').append(rows);
                        var cheqs = '';
						var i;
						for(i=0; i<data[1].length; i++){                
                        cheqs+='<tr>'+
                                    '<td>'+'</td>'+
                                    '<td>'+'</td>'+  
                                    '<td></td>'+                                  
                                    '<td>'+"Cheque"+'</td>'+
                                    '<td>'+data[1][i].supchqlog_givndate+'</td>'+
                                    '<td style="Text-align: right;">'+data[1][i].supchqlog_amnt+'</td>'+
                                '</tr>';
						}
                        $('#tbodyID').append(cheqs);
                        var grnInfo='<tr>'+                                            
                                            '<td>Invoice total</td>'+
                                            '<td>'+grandtotal+'</td>'+
                                        '</tr>'+
                                        '<tr>'+                                            
                                            '<td>Total paid</td>'+
                                            '<td>'+totalPaid+'</td>'+
                                        '</tr>'+
                                        '<tr>'+                                            
                                            '<td>Invoice outstanding total</td>'+
                                            '<td>'+grnOutstanding+'</td>'+
                                        '</tr>'+
                                        '<tr>'+                                            
                                            '<td>Over all outstanding</td>'+
                                            '<td>'+ttlOutstanding+'</td>'+
                                        '</tr>';
                        $('#TblinvceInfo').html(grnInfo);
                },
                error: function(){
                    alert('error data collection');
                }
            });
        }

        // show & hide cheque details box
        $("#tickCheque").change(function() {
            if(this.checked) {
                $(".cheaqueDetailsDiv").show("fast");
            }
            else{
                $(".cheaqueDetailsDiv").hide("fast");
            }
        });
        var availableSupplier = [
        <?php
            if($suppliers!=''){
                foreach ($suppliers as $supplier)
                {
                echo '{ label: "'.$supplier->sup_name.'", value:"'.$supplier->sup_id.'" },';
                }
            }
        ?>
        ];
        $( "#supplier-auto" ).autocomplete({
            source: availableSupplier,
            select: function(event, ui) {
                    event.preventDefault();
                    $("#supplier-auto").val(ui.item.label);
                    $('#supplier-id').val(ui.item.value);
                    var slectedsup = ui.item.value;
                    getSupPayments();
                    $("#grn-auto").val("");
                    $("#save").prop('disabled', false); 
                },
        });

        var availableGrns = [
        <?php
            if($grns!=''){
                foreach ($grns as $grn)
                {
                echo '{ label: "'.$grn->grn_id." - ".$grn->grn_code.'", value:"'.$grn->grn_id.'" },';
                }
            }
        ?>
        ];
        $("#grn-auto" ).autocomplete({
            source: availableGrns,
            select: function(event, ui) {
                    event.preventDefault();
                    $("#grn-auto").val(ui.item.label);
                    $('#grn-id').val(ui.item.value);
                    var slectedsup = ui.item.value;
                    getGrnByID();
                    $("#supplier-auto").val("");
                    $("#save").prop('disabled', false);
                },
        });

        //Once remove button is clicked
        $(".class-chequesinhand").on('click', '.remove_cheqInHand', function(e){
            e.preventDefault();
            $(this).parent().parent('div').remove(); //Remove field html
            var cusCheqId = $(this).attr('rel');
            $("#pCheques option[value=" + cusCheqId + "]").removeAttr('disabled');
            $("#pCheques").val(0);
            //calculateCredit();
            $("#pCheques").prop("disabled", false);
        });
        //accourding to party Cheques
        $("#pCheques").change(function () { 
                var prtyChqID=$("#pCheques").val();
                if(prtyChqID!=0){
                   // $("#pCheques option:selected").attr('disabled','disabled');    //disable only selected
                   $("#pCheques").prop("disabled", true); // this will disable whole    
                    $.ajax({
                        type: "Post",
                        url:"<?php echo base_url('CustomerPayment/getPartyChqDtails'); ?>",
                        data: {id:prtyChqID},
                        async: false,
                        dataType: "json",
                        success: function (data) {
                            
                            var chequesInHand =
                                '<div>'+
                                    '<hr>'+
                                    '<input type="hidden" name="hiddenCusChqID" id="hiddenID" value="'+data.cus_cheque_id+'">'+
                                    '<div class="form-group row">'+
                                        '<label for="amountPrty" class="col-4 col-form-label">Amount:</label>'+
                                        '<div class="">'+
                                            '<input class="form-control   " type="text" readonly '+
                                            'name="amountParty" id="amountPrty" value="'+data.cus_cheque_amount+'" >'+
                                        '</div>'+
                                    '</div>'+
                                    
                                    '<div class="form-group row">'+
                                        '<label for="bankPrty" class="col-4 col-form-label">Bank:</label>'+
                                        '<div class="">'+
                                            '<input class="form-control" type="text" readonly '+
                                            'name="bankParty" id="bankPrty" value="'+data.cus_cheque_bank+'" >'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="form-group row">'+
                                        '<label for="chequenoPrty" class="col-4 col-form-label">Cheque no:</label>'+
                                        '<div class="">'+
                                            '<input class="form-control" type="text" readonly '+
                                            'name="chequenoParty" id="chequenoPrty" value="'+data.cus_cheque_num+'" >'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-group row">'+
                                        '<label for="datePrty" class="col-4 col-form-label">Date:</label>'+
                                        '<div class="">'+
                                            '<input class="form-control datepic" name="chequedateParty" id="datePrty" value="'+data.cus_cheque_date+'" readonly>'+
                                        '</div>'+
                                        '<a href="javascript:void(0);" rel="'+data.cus_cheque_id+'" class="remove_cheqInHand" title="Remove cheque"><i class="fa fa-minus-square" style="font-size:24px;color:red"></i></a>'+
                                    '<div>'+
                                '</div>'; 
                            $("#cheqsInHandDiv").append(chequesInHand);
                        },
                        error: function (err) {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Error in fetching cheques in hand!'
                            });
                        }
                    });
                }
                else{
                    $("#cheqsInHandDiv").empty();
                    $('option').prop('disabled', false);
                } 
        });
        function loadCusCheques(){
        $.ajax({
            type: 'post',
            url: "<?php echo base_url('grns/getPartyCheques'); ?>",
            async: false,
            dataType:'json',  
            success: function(data){
                var len=data.length;
                $('#pCheques').empty().append('<option value="0">-None-</option>');
                for( var i = 0; i<len; i++){
                            var ChqAmount = data[i]['cus_cheque_amount'];
                            var cusChqID = data[i]['cus_cheque_id'];  
                            var chqNum = data[i]['cus_cheque_num']; 
                            var chqDate = data[i]['cus_cheque_date']; 
                            $("#pCheques").append("<option data-amount='"+ChqAmount+"' value='"+cusChqID+"'>"+chqNum+"-"+ChqAmount+"-"+chqDate+"</option>");
                        }  
            },
            error: function() {
                alert("There was an error. Please try again!");
            }
        });
        }
        loadCusCheques();//party cheques

        //loadbanks
        function loadBanks(){
            $.ajax({ //get all banks to cheque box
                    type: 'post',
                    url: "<?php echo base_url('banks/getAllBanks'); ?>",	
                    async: false,
                    dataType:'json',  
                    success: function(data){
                        var options;
                        var len = data.length;    
                        for( var i = 0; i<len; i++){
                        var id = data[i]['bank_id'];    
                        var bankName = data[i]['bank_name'];
                        var acctNumbr = data[i]['bank_accnumber']; 
                        var acctName = data[i]['bank_accname'];                
                        options+="<option value='"+id+"'>"+bankName+"-"+acctNumbr+"</option>";
                    }
                        var banklist='<label for="bank" class="col-4 col-form-label">Bank:</label>'+
                                            '<div class="">'+
                                                '<select class="form-control" name="bank" id="bank" required>'+
                                                    '<option value="">-Select Bank-</option>'+
                                                    options+                                                                 
                                                '</select>'+
                                            '</div>';
                        $(".selectBankDiv").html(banklist);
                    },
                    error: function() {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'There was an error. Please try again!!'
                        });
                    }
                });
        }
        $("#tickCheque").change(function() {
            if(this.checked) {
                loadBanks();
            }
        });
        $("#supplier-auto").keyup(function(){
            $("#grn-auto").val("");
            $("#save").prop('disabled', true);
        });
        $("#grn-auto").keyup(function(){
            $("#supplier-auto").val("");
            $("#save").prop('disabled', true);
        });
        var chqAddButton=false; //for cheaques in form
        //Once add button is clicked
        $(".add_button").click(function(){
            $(".cheaqueDetailsDiv2").show("fast");
            chqAddButton=true;
        });
        //Once remove button is clicked
        $(".remove_button").click(function(){
            $(".cheaqueDetailsDiv2").hide("fast");
            chqAddButton=false;
        });

        var ChqFormsubmittd=false;
        $("#chequeform").submit(function(e){
            e.preventDefault();
            ChqFormsubmittd=true;
        });
        
       
        //payment saving 
        //w
        $("#save").click(function(){ 
            if (($("#tickCheque").is(':checked'))&&chqAddButton==true){
                $("#chequeform").submit();
            }
            var cashvalue=0;
            cashvalue=parseFloat($("#cashvalue").val());
            if(cashvalue==''||(!(cashvalue>0))){cashvalue=0;}

            var grnID=$("#grn-id").val();
            var supName=$("#supplier-auto").val();

            var Ttl=0;
            var partyChq=0;
            var ourChq=0;
            if ($("#tickCheque").is(':checked')) { 
                if($("#pCheques").val()!=0){                        
                    partyChq=parseFloat($("#amountPrty").val());                    
                }   
                if(chqAddButton==true){
                    ourChq=parseFloat($("#amount").val());
                    if(ourChq==''||(!(ourChq>0))){ourChq=0;}
                }  
            }
            var totalpaymnt=cashvalue+partyChq+ourChq;
            if(!(totalpaymnt>0)){   
                alert("Enter valid amount");
            }
            else if(grnID==''&&supName==''){
                alert("Please Enter a GRN ID or Supplier");
            }
            else if(cashvalue>0||partyChq>0||ourChq>0){
                if(grnID!=''){ 
                    if(($("#tickCheque").is(':checked'))&&chqAddButton==true) {
                        if(ChqFormsubmittd==true){
                            var result=saveGrnPayment();
                            ChqFormsubmittd=false;
                            if(result==false){
                            alert("Payment should not be greater than outstanding of GRN");
                            }
                        }
                        else{
                            alert("Please fill the cheque fields");
                        }
                    }
                    else{
                        var result=saveGrnPayment();
                        if(result==false){
                            alert("Payment should not be greater than outstanding of GRN");
                        }
                    }
                }
                else if(supName!=''){ 
                    if(($("#tickCheque").is(':checked'))&&chqAddButton==true) {
                        if(ChqFormsubmittd==true){
                            saveSupPayment();
                            ChqFormsubmittd=false;
                        }
                        else{
                            alert("Please fill the cheque fields");
                        }
                    }
                    else{
                        saveSupPayment();
                    }
                }
            }
  
           
            var supid=0;            
            var newCash=0;
            var newCheqTtl=0;
            var grnCredit=0;
            function saveGrnPayment(){ //payment by grnID
                $("#save").prop('disabled', true);
                date1= $('#datepicker').val();
                getSupID();

                newCash=cashvalue;
                newOurChq=ourChq;
                newPartyChq=partyChq;

                var paidGrnCash=false;
                var paidOurCheq=false;
                var paidPrtyCheq=false;
                var totalPayment=newCash+newOurChq+newPartyChq;
                if(totalPayment>grnCredit){
                    return false;
                }
                var cashBal=0;
                if(newCash>0){                       
                    getGrnCredit(grnID); //get the credit for entered grn                    
                    if(grnCredit>=newCash&&grnCredit>0){ // ???
                        insertMainCashTable(supid,newCash,date1, function(cashID){
                            if(cashID>0){                        
                                    $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('supplierPayment/supplierCashLog'); ?>",
                                        data: {grnID:grnID,cashID:cashID,cash:newCash,date:date1},
                                        async: false,
                                        dataType: "json",
                                        success: function (res) {
                                            adjustCashCredit(grnID,newCash,0); 
                                            paidGrnCash=true;
                                            console.log("supplier paymnt log saved"); 
                                        },
                                        error: function (err) {
                                            alert("supplier payment log error");
                                        }
                                    });
                            }else{
                                alert("There was an error. Please check again!")
                            }
                        });
                    }
                }
                var prtyCheqBal=0;
                    if(newPartyChq>0){  //this executed only if party check is selected                                       
                    getGrnCredit(grnID); 
                    var cusChqID=parseFloat($("#hiddenID").val()); //id from cus chq table
                    var bank=$("#bankPrty").val();
                    var cheqno=$("#chequenoPrty").val();
                    var dateprty=$("#datePrty").val();  
                        if(grnCredit>=newPartyChq&&grnCredit>0){
                                //Add cheques to main cheq table
                                if ($("#tickCheque").is(':checked')){  
                                    $.ajax({
                                        type: 'post',
                                        url: "<?php echo base_url('supplierPayment/supPaymentCheque'); ?>",
                                        data: {grnID:0,amount2:newPartyChq,bank:bank,chequeno:cheqno,cusChqID:cusChqID,chequedate:dateprty,date:date1},
                                        async: false,
                                        dataType:'json',  
                                        success: function(res){ 
                                            if(res>0){
                                                var supChqID=res;
                                                updatePrtyChqTransfered(cusChqID); 
                                                adjustCashCredit(grnID,0,newPartyChq); 
                                                GRNIDPayChequeLog(supChqID,grnID,newPartyChq, function(data){
                                                    if(data==true){
                                                        paidPrtyCheq=true;
                                                    }else{
                                                        alert("There was an error. Please check again!")
                                                    }
                                                });                                                          
                                            }                          
                                        },
                                        error: function() {
                                            alert("There was an error. Please try again!");
                                        }
                                    });
                                }
                        }   
                    }
                var ourCheqBal=0;
                    if(newOurChq>0){                                         
                    getGrnCredit(grnID); //get the credit for entered grn  
                        if(grnCredit>=newOurChq&&grnCredit>0){ // ?????????? need changes, 
                                //Add cheques to main cheq table
                                if (($("#tickCheque").is(':checked'))&&chqAddButton==true){    // amount2= paid amount for that invoice
                                    var data = $('#chequeform').serialize()+"&grnID="+0+"&amount2="+newOurChq+"&cusChqID="+0+"&date="+date1;
                                    $.ajax({
                                        type: 'post',
                                        url: "<?php echo base_url('supplierPayment/supPaymentCheque'); ?>",
                                        data: data,
                                        async: false,
                                        dataType:'json',  
                                        success: function(response){ 
                                            if(response>0){
                                                var supChqID=response;
                                                adjustCashCredit(grnID,0,newOurChq);                                                 
                                                GRNIDPayChequeLog(supChqID,grnID,newOurChq,date1, function(data){
                                                    if(data==true){
                                                        paidOurCheq=true;
                                                    }else{
                                                        alert("There was an error. Please check again!")
                                                    }
                                                });        
                                            }                                                                   
                                        },
                                        error: function() {
                                            alert("There was an error. Please try again!");
                                        }
                                    });
                                }
                        }               
                    } //--
                    if(paidGrnCash==true&&paidPrtyCheq==true&&paidOurCheq==true){
                        var str="Cash and Cheques"
                        grnPaymntSuccess(str);
                         location.reload(true);
                    }
                    else if(paidGrnCash==true&&paidPrtyCheq==true){
                        var str="Cash and Cheque"
                        grnPaymntSuccess(str);
                         location.reload(true);
                    }
                    else if(paidGrnCash==true&&paidOurCheq==true){
                        var str="Cash and Cheque"
                        grnPaymntSuccess(str);
                         location.reload(true);
                    }
                    else if(paidGrnCash==true){
                        var str="Cash"
                        grnPaymntSuccess(str);
                         location.reload(true);
                    }
                    else if(paidPrtyCheq==true||paidOurCheq==true){ 
                        var str="Cheque"
                        grnPaymntSuccess(str);
                         location.reload(true);
                    }
                     
                    var paidMore=cashBal+prtyCheqBal+ourCheqBal;// always = 0
                  /*  if(cashBal>0){
                        var from="pyntByID";
                        cashPayment(supid,cashBal,from);                 
                    }
                    if(prtyCheqBal>0){

                    }
                    if(ourCheqBal>0){

                    }*/
                    //update supplier +balance, if he paid more 
                    //updateCusBalanceForInvoice(custid,paidMore);//custid,newCheqTtl,grnid
            }
            var date;
            var supCredit=0;
            var supChqeID=0;
            function saveSupPayment(){// payment by selecting all outstnding grns of a supplier
                $("#save").prop('disabled', true);
                var sup_ID= $("#supplier-id").val(); 
                date= $("#datepicker").val();

                getSupplierCredit(sup_ID);
                if(!(supCredit>0)){
                    return false;                      
                }                                  
                newCash=cashvalue;                
                newPartyChq=partyChq;
                newOurChq=ourChq;                 
            
                if(newCash>0){
                    var from="pyntBySupNme";
                    getSupplierCredit(sup_ID); //function not created yet
                    if(supCredit>0){
                        //alert("supCredit >0 :cash: "+supCredit);
                        cashPayment(sup_ID,newCash); 
                    }                                            
                }
                if(newPartyChq>0){
                    var from="pyntBySupNme";
                    getSupplierCredit(sup_ID);
                    if(supCredit>0){
                       // alert("supCredit >0 : partychq "+supCredit);
                        prtyChqPayment(sup_ID,newPartyChq); 
                    }                                             
                }
                if(newOurChq>0){
                    var from="pyntBySupNme";
                    getSupplierCredit(sup_ID);
                    if(supCredit>0){
                       // alert("supCredit >0 our chq "+supCredit);
                        ourChqPayment(sup_ID,newOurChq); 
                    }                                             
                }

            } //End of save-Sup-Payment

            var new1Cash=0;
            function cashPayment(sup_ID,new1Cash1){
                new1Cash=new1Cash1;
                insertMainCashTable(sup_ID,new1Cash,date, function(cashID){
                    if(cashID>0){                        
                            $.ajax({//out standing gnrs of selected supplier
                                type: 'post',
                                url: "<?php echo base_url('supplierPayment/OutStandingGrns'); ?>",
                                data: {sup_ID:sup_ID},
                                async: false,
                                dataType:'json',  
                                success: function(data){         //                                
                                    var k;
                                    for(k=0; k<data.length; k++){ 
                                        var grnid=data[k].grn_id;
                                        var creditOfGrn=data[k].sup_pay_credit; 
                                        cashLog(grnid,creditOfGrn,sup_ID,cashID);
                                    }
                                },
                                error: function() {
                                    alert("Please try again!");
                                }
                            });
                    }else{
                        alert("There was an error. Please check again!")
                    }
                });
            }
            var paidSupCash;
            function cashLog(grnid,creditOfGrn,sup_ID,cashID){    //payment log by cash   
                paidSupCash= false;                
                    if(creditOfGrn>=new1Cash&&new1Cash>0){ // this case run only for 1 grnid
                            $.ajax({
                                type: "Post",
                                url:"<?php echo base_url('supplierPayment/supplierCashLog'); ?>",
                                data: {grnID:grnid,cashID:cashID,cash:new1Cash,date:date},
                                async: false,
                                dataType: "json",
                                success: function (res) {
                                    if(res==true){
                                        adjustCashCredit(grnid,new1Cash,0);
                                        new1Cash=0;
                                        paidSupCash=true;
                                    }                                     
                                },
                                error: function (err) {
                                    alert("supplier payment log error");
                                }
                            });
                    }                      
                    else if(creditOfGrn<new1Cash&&new1Cash>0){ // new1Cash<0 & creditOfGrn<newCash
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('supplierPayment/supplierCashLog'); ?>",
                            data: {grnID:grnid,cashID:cashID,cash:creditOfGrn,date:date},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                                new1Cash=new1Cash-creditOfGrn;
                                console.log("supplier paymnt log saved"); 
                                adjustCashCredit(grnid,creditOfGrn,0);
                                paidSupCash=true;
                                //paidCash==paidPrtyCheq==paidOurCheq
                            },
                            error: function (err) {
                                alert("supplier payment log error");
                            }
                        });
                    }     
            }   var new1CashBal=new1Cash; 

            var new1OurChq=0;
            function ourChqPayment(sup_ID,new1OurChq1){
                new1OurChq=new1OurChq1;
                var amount=$("#amount").val(); 
                var supChqeID=0;                
                if (($("#tickCheque").is(':checked'))&&chqAddButton==true){   //Add cheques in main cheque table  
                    var data = $('#chequeform').serialize()+"&grnID="+0+"&date="+date+"&amount2="+amount+"&cusChqID="+0;
                    $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('supplierPayment/supPaymentCheque'); ?>",
                        data: data,
                        async: false,//  cheque inserted & if  below paymnt didnt occur ?? execute only if there credit 
                        dataType:'json',  //need changes
                        success: function(supChqID){
                            supChqeID=supChqID;
                            console.log("supChqID: "+supChqID);                 
                        },
                        error: function() {
                            alert("There was an error. Please try again!");
                        }
                    });
                }
                $.ajax({//outstanding invoices of selected supplier
                    type: 'post',
                    url: "<?php echo base_url('supplierPayment/OutStandingGrns'); ?>",
                    data: {sup_ID:sup_ID},
                    async: false,
                    dataType:'json',  
                    success: function(data){                                            
                        var k;
                        for(k=0; k<data.length; k++){ 
                            var grnid=data[k].grn_id;
                            var creditOfGrn=data[k].sup_pay_credit; 
                            chequelog(grnid,creditOfGrn,supChqeID,sup_ID);
                        }
                    },
                    error: function() {
                        alert("Please try again!");
                    }
                });
            }

            var paidSupOurchq;            
            function chequelog(grnid,creditOfGrn,supChqeID,sup_ID){//payment log by cheque   
                paidSupOurchq=false;
                if(($("#tickCheque").is(':checked'))&&chqAddButton==true){   
                    if(creditOfGrn>=new1OurChq&&new1OurChq!=0){  // this case run only for 1st grnid  
                        alert("111 ")  ;
                            $.ajax({    //Add cheques /cheque log
                                type: 'post',
                                url: "<?php echo base_url('supplierPayment/supplierChequeLog'); ?>",
                                data:{supChqID:supChqeID,grnID:grnid,Amnt:new1OurChq,date:date},
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                    adjustCashCredit(grnid,0,new1OurChq);                                      
                                    paidSupOurchq=true;
                                    new1OurChq=0;                
                                },
                                error: function() {
                                    alert("There was an error.Please try again!");
                                }
                            });
                    }                     
                    else if(creditOfGrn<new1OurChq&&new1OurChq!=0){ // new1OurChq<0 & creditOfGrn<new1OurChq
                        alert("222")  ;
                                $.ajax({      //Add cheques /cheque log 
                                    type: 'post',
                                    url: "<?php echo base_url('supplierPayment/supplierChequeLog'); ?>",//
                                    data:{supChqID:supChqeID,grnID:grnid,Amnt:creditOfGrn,date:date},
                                    async: false,
                                    dataType:'json',  
                                    success: function(response){
                                        new1OurChq=new1OurChq-creditOfGrn; 
                                        adjustCashCredit(grnid,0,creditOfGrn);   
                                        paidSupOurchq=true;     
                                    },
                                    error: function() {
                                        alert("There was an error.Please try again!");
                                    }
                                });
                    }     
                }
            }   var new1chqBal=new1OurChq;
            
            var new1prtychq;
            function prtyChqPayment(sup_ID,new1prtychq1){ 
                new1prtychq=new1prtychq1;
                    var supChqeID=0;                       
                    var cusChqID=parseFloat($("#hiddenID").val()); //id from cus chq table
                    var bank=$("#bankPrty").val();
                    var cheqno=$("#chequenoPrty").val();
                    var dateprty=$("#datePrty").val(); 
                    if ($("#tickCheque").is(':checked')){   //Add cheques in main cheque table  
                        $.ajax({
                            type: 'post',
                            url: "<?php echo base_url('supplierPayment/supPaymentCheque'); ?>",
                            data: {grnID:0,amount2:new1prtychq,bank:bank,chequeno:cheqno,cusChqID:cusChqID,chequedate:dateprty,date:date},
                            async: false,//  cheque inserted & if  below paymnt didnt occur ?? execute only if there credit 
                            dataType:'json',  //need changes
                            success: function(supChqID){
                                if(supChqID>0){
                                    supChqeID=supChqID;
                                    updatePrtyChqTransfered(cusChqID);                                                           
                                }                 
                            },
                            error: function() {
                                alert("There was an error. Please try again!");
                            }
                        });

                        $.ajax({//outstanding invoices of selected supplier
                            type: 'post',
                            url: "<?php echo base_url('supplierPayment/OutStandingGrns'); ?>",
                            data: {sup_ID:sup_ID},
                            async: false,
                            dataType:'json',  
                            success: function(data){                                            
                                var k;
                                for(k=0; k<data.length; k++){ 
                                    var grnid=data[k].grn_id;
                                    var creditOfGrn=data[k].sup_pay_credit; 
                                    partychqlog(grnid,creditOfGrn,supChqeID,sup_ID);
                                }
                            },
                            error: function() {
                                alert("Please try again!");
                            }
                        });
                    }                                                
            } 
            var paidSupPrtychq;
            function partychqlog(grnid,creditOfGrn,supChqeID,sup_ID){//payment log by party cheque                       
                paidSupPrtychq=false;
                    if(creditOfGrn>=new1prtychq&&new1prtychq>0){  // this case run only for 1st grnid
                        //Add cheques /cheque log
                            $.ajax({
                                type: 'post',
                                url: "<?php echo base_url('supplierPayment/supplierChequeLog'); ?>",
                                data:{supChqID:supChqeID,grnID:grnid,Amnt:new1prtychq,date:date},
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                    if(response==true){
                                        adjustCashCredit(grnid,0,new1prtychq) 
                                        new1prtychq=0;   
                                        paidSupPrtychq=true;
                                    }                                                  
                                },
                                error: function() {
                                    alert("There was an error. Please try again!");
                                }
                            });
                    }                     
                    else if(creditOfGrn<new1prtychq&&new1prtychq>0){ // new1prtychq<0 & creditOfGrn<new1prtychq
                            //Add cheques /cheque log 
                                $.ajax({
                                    type: 'post',
                                    url: "<?php echo base_url('supplierPayment/supplierChequeLog'); ?>",//
                                    data:{supChqID:supChqeID,grnID:grnid,Amnt:creditOfGrn,date:date},
                                    async: false,
                                    dataType:'json',  
                                    success: function(response){
                                        new1prtychq=new1prtychq-creditOfGrn; 
                                        adjustCashCredit(grnid,0,creditOfGrn);
                                        paidSupPrtychq=true;        
                                    },
                                    error: function() {
                                        alert("There was an error. Please try again!");
                                    }
                                });
                    }     
            }   var new1prtyBal=new1prtychq;
            
    //w
    //alert(  paidSupCash+ " " +paidSupPrtychq+ " " +paidSupOurchq);
            if(paidSupCash==true&&(paidSupPrtychq==true||paidSupOurchq==true)){
                var str="Cash and Cheques"
                supPaymntSuccess(str);
            }           
            else if(paidSupCash==true){
                var str="Cash"
                supPaymntSuccess(str);
            }
            else if(paidSupPrtychq==true||paidSupOurchq==true){ 
                var str="Cheque"
                supPaymntSuccess(str);
            }
            
            function insertMainCashTable(sup_ID,new1Cash,date,callback){
                $.ajax({// insert to cash main table
                    type: 'post',
                    url: "<?php echo base_url('supplierPayment/supplierPayCash'); ?>",
                    data: {sup_ID:sup_ID,cash:new1Cash,date:date},
                    async: false,
                    dataType:'json',  
                    success: function(cashID){                                    
                            if(cashID>0){
                                callback(cashID);
                            }
                    },
                    error: function() {
                        alert("Please try again!");
                    }
                });
            }
            function GRNIDPayChequeLog(supChqID,grnID,ChqAmnt,date1,callback){
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('supplierPayment/supplierChequeLog'); ?>",
                    data:{supChqID:supChqID,grnID:grnID,Amnt:ChqAmnt,date:date1},
                    async: false,
                    dataType:'json',  
                    success: function(response){                        
                        if(response==true){
                            if(callback) callback(response);
                        }                                        
                    },
                    error: function() {
                        alert("There was an error. Please try again!");
                    }
                });
            }

            function getSupID(){                    
                $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('supplierPayment/getSupplierID'); ?>",
                    data: {grnID:grnID},
                    async: false,
                    dataType: "json",
                    success: function (res) {
                        supid=res;
                    },
                    error: function (err) {
                        alert("supplier payment log error");
                    }
                });
            }
            function getGrnCredit(grnID){
                $.ajax({
                        type: "Post",
                        url:"<?php echo base_url('supplierPayment/getGrnCredit'); ?>",
                        data: {grnID:grnID},
                        async: false,
                        dataType: "json",
                        success: function (res) {
                            grnCredit=res;
                        },
                        error: function (err) {
                            alert("grn credit value error");
                        }
                    });
            }
            function updatePrtyChqTransfered(cusChqID){
                // marked as transferred(1) after customer cheque sent to supplier
                $.ajax({
                    type: 'post',
                    url: "<?php echo base_url('CustomerPayment/markAsTransterred1by1'); ?>",
                    data: {cusChqID:cusChqID},
                    async: false,
                    dataType:'json',  
                    success: function(response){
                        loadCusCheques();
                        //alert("customer cheque transferred successfully");                            
                    },
                    error: function() {
                        alert("Error in customer cheque transfering. Please try again!");
                    }
                });
            }
            function adjustCashCredit(grn_id,cash,cheq){//update in payment table
                $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('supplierPayment/adjustCashCredit'); ?>",
                    data: {grnID:grn_id,cash:cash,cheq:cheq}, //data: {grnID:grnID,cash:0,cheq:grnCredit},
                    async: false,
                    dataType: "json",
                    success: function (res) {  
                        if(res==true){
                            return true;
                        }
                    },
                    error: function (err) {
                        alert("supplier payment error");
                    }
                });
            }
            function getSupplierCredit(sup_ID){// overall outstanding of supplier //
                $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('supplierPayment/SupplierOutstanding'); ?>",
                    data: {sup_ID:sup_ID},
                    async: false,
                    dataType: "json",
                    success: function (res) {  
                        supCredit=res;
                    },
                    error: function (err) {
                        alert("supplier payment error");
                    }
                });            
            }
            function grnPaymntSuccess(str){ 
                getGrnByID();
                $("#grn-auto").val("");
                $("#grn-id").val("");                
                alert(str+" Payment Added");
            }
            function supPaymntSuccess(str){
                getSupPayments();
                $("#supplier-auto").val("");
                $("#supplier-id").val("");                
                alert(str+" Payment Added");
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
        //static input validation
        $(".staticValication").keypress(function(e){
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
            ((event.which < 48 || event.which > 57) &&
            (event.which!=0&&event.which!=8&&event.which!=13))) {
            event.preventDefault();
           // $(this).val('');
            alert("In valid number");
        }
        var text = $(this).val();
        if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
            alert("Not allowed");
            }
        });


    }); 
    $(document)
    
</script> 