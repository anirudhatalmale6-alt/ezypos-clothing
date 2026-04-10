        <div class="wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-4 col-md-5 col-sm-12"><!-- Add GRN Form -->
                    <div class="row">      
                        <div class="col-12"><!-- col-lg-6 col-md-6 col-sm-8 col-xs-12-->
                            <div class="card-box clearfix">
                                <div class="row">
                                    <div class="col-6"><h4 class="header-title m-t-0 m-b-30">Add New GRN</h4></div>
                                    <div class="col-6">
                                        <select class="form-control" name="grnStoreLoctn" id="grnStoreLoctn">
                                        <?php if($_SESSION['userrole']==1){?>
                                        <option value="0">Store Location</option>
                                        <?php }?>
                                        <?php
                                            if(isset($storeLoc)){
                                            foreach ($storeLoc as $store)
                                            {
                                            echo '<option value="'.  $store->store_id.'"> '. $store->store_name.'</option>';
                                            }}
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <fieldset>
                                <div class="form-group row">
                                    <label for="grnsupplier-auto" class="col-4 col-form-label">Supplier<span class="text-danger">*</span></label>
                                    <div class="col-6" id="supplierdiv">
                                        <input class="form-control"  id="grnsupplier-auto" placeholder="Select" >
                                        <input type="hidden" class="form-control" name="grnsupplier" id="grnsupplier-id">
                                    </div>
                                    <div class="col-2">
                                        <a href="#"><b><span id="show_sup" class="hover" data-toggle="tooltip" ></span></b></a>
                                        <button id="btnChange" style="display:none;" class="btn btn-sm btn-warning">
                                            <i class="fa fa-exchange"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    
                                        <div class="checkbox checkbox-primary col-form-label col-5">
                                            <input id="noitemgrn" name="noitemgrn" type="checkbox">
                                            <label for="noitemgrn">No Item GRN</label>
                                        </div>
                                        <div class="col-7" style="display:none;" id="divGrnDesc">
                                            <textarea placeholder="Describe grn ..."class="form-control" rows="2"  id="grndesc"></textarea>
                                        </div>
                                    
                                </div>
                                <div class="form-group row">
                                    <label for="datepicker" class="col-4 col-form-label">Date<span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <input class="form-control datepic" value="" id="datepicker">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="grncode" class="col-4 col-form-label">GRN Code<span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <input class="form-control" value="" id="grncode">
                                    </div>
                                </div>
                                </fieldset>
                                <hr>
                                <div style="background:#f8f9fa;border-radius:4px;padding:10px 5px;margin-bottom:10px;">
                                <div class="form-group row mb-1">
                                    <label class="col-5 col-form-label">Sub Total:</label>
                                    <div class="col-7 col-form-label text-right"><strong>LKR <span id="subtotal">0.00</span></strong></div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-3 col-form-label">Discount:</label>
                                    <div class="col-5"><input class="form-control DecimalFix" type="text" name="invoiceDis" placeholder="0" id="invoiceDis"/></div>
                                    <div class="col-4">
                                        <select class="form-control" id="invoiceDisType">
                                            <option value="percentage">%</option>
                                            <option value="flat">Flat (LKR)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <label class="col-5 col-form-label" style="font-size:16px;"><strong>Grand Total:</strong></label>
                                    <div class="col-7 col-form-label text-right" style="font-size:16px;">
                                        <strong>LKR <span id="grandtotalLbl">0.00</span></strong>
                                        <input type="text" class="form-control DecimalFix staticValication" style="display:none;" id="noitemTotalLbl">
                                    </div>
                                </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="cashvalue" class="col-4 col-form-label">Cash:</label>
                                    <div class="col-8">
                                        <input class="form-control DecimalFix staticValication" type="text" placeholder="Enter Cash Value 0.00"
                                        name="cashvalue" id="cashvalue" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-5 col-form-label">Credit:</label>
                                    <div class="col-7 col-form-label text-right">LKR <span id="creditvalue">0.00</span></div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-4"></div>
                                    <div class="checkbox checkbox-primary">
                                        <input id="cheque" name="cheque" type="checkbox">
                                        <label for="cheque">Cheque</label>
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
                                </div>        
                                <form action="" id="chequeform">
                                    <div class="field_wrapper cheaqueDetailsDiv" style="display:none;">
                                        <a href="javascript:void(0);" class="add_button" title="Add another cheque"><i class="fa fa-plus-square" style="font-size:24px;color:green"></i></a>
                                    </div>                                    
                                </form>
                                <div class="pull-right">
                                    <button id="save" class="btn btn-primary waves-effect"><i class="fa fa-save"></i></button>
                                    <button id="saveNoItem" class="btn btn-primary waves-effect" style="display:none;"><i class="fa fa-save"></i></button>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div><!-- End of Add GRN Form -->

                <div class="col-lg-8 col-md-7 col-sm-12"><!--Start Table & row -->
                    <div class="row"> 
                        <div class="col-12">
                        <section>
                            <form id="formid" name="formname" action="#" method="post" >                                
                                <div class="row">
                                    <div class="col-4">
                                        <label for="grnitem" class="col-form-label">Item Name<span class="text-danger">*</span></label>                                        
                                        <input class="form-control"  id="grnitem-auto" placeholder="Select" required>
                                        <input type="hidden" class="form-control" name="grnitem" id="grnitem-id">
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="itemprice" class="col-form-label">Item Price<span class="text-danger">*</span></label>
<!--                                            <input class="form-control DecimalFix" type="text" placeholder="Enter Price 0.00" 
                                                name="itemprice" id="itemprice" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"> -->
                                            <input class="form-control DecimalFix" type="text" placeholder="Enter Price 0.00" 
                                                name="itemprice" id="itemprice" required data-parsley-type="number" data-parsley-errors-messages-disabled> 
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="itemquantity" class="col-form-label">Item Qty<span class="text-danger">*</span></label>
                                            <input class="form-control DecimalFix" type="text" placeholder="Enter Quantity" 
                                                   name="itemquantity" id="itemquantity" required data-parsley-type="number" data-parsley-errors-messages-disabled >
                                        </div>
                                    </div>
                                    <div class="" style="margin-top: 33px; margin-left: 30px;">
                                        <button type="submit" id="add" class="btn btn-primary waves-effect"><i class="fa fa-plus-square"></i></button> 
                                        <button type="reset" id="reset" class="btn btn-secondary waves-effect"><i class="fa fa-refresh"></i></button>
                                    </div>
                                </div>                                                      
                            </form>
                        </section>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box table-responsive clearfix"> 
                                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <col width="15">
                                <col width="200">
                                <col width="120">
                                <col width="">
                                <col width="130"> <!-- total -->
                                <col width="60">
                                    <thead>
                                        <tr style="background-color: #C0C0C0">
                                            <th style="font-size: 12px;">#</th>
                                            <th style="display:none;">itemid</th>
                                            <th style="font-size: 12px;">Item</th>
                                            <th style="font-size: 12px;">Price</th>
                                            <th style="font-size: 12px;">Qty</th>
                                            <th style="font-size: 12px;">Total</th>
                                            <th style="font-size: 12px;">Dscnt%</th>
                                            <th style="font-size: 12px;">Act</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyID">                                          
                                    </tbody>
                                </table>                                
                            </div>
                        </div>                 
                    </div>
                </div> <!--End of  Table & row -->
            </div>           
        </div> <!-- container-fluid -->
                
<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
//    function check_numbers(num_val,id_of_val){
//        if(Number.isInteger(num_val)){
//            var new_decimal_val=num_val.toFixed(2);
//            var id_val="#"+id_of_val;
//            $(id_val).val(new_decimal_val);
//            alert("Wishwa");
//        }
//    }
    
    $( function() {  

        $('.hover').tooltip({
            borderWidth: 0,
            show: { delay: 0, duration: 0 },
            content:fetchData,
            html:true,
        });
        function fetchData(){
            var fetch_data='';
            var supid=$("#grnsupplier-id").val();
                $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('Suppliers/getSupDetails'); ?>",
                    data: {id:supid},
                    async: false,
                    dataType: "json",
                    success: function (data) {
                        if(data!=false){
                            fetch_data='<table style="font-family:Georgia, serif; background-color:#000000;font-size:18px; color:white;">'+
                                '<tr>'+
                                    '<td>Name: </td><td>'+data.sup_name+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>Address: </td><td>'+data.sup_address+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>Contact: </td><td>'+data.sup_contact+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>Balance: </td><td>'+data.sup_balance+'</td>'+
                                '</tr>'+
                            '</table>'
                        }else{
                            fetch_data='';
                        }
                    },
                    error: function (err) {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Error in fetching supplier details!'
                        });
                    }
                });
                return fetch_data;
        }

        //Dynamic datepicker
        $('#chequeInHandform').on('click',".add_button", function(){
            $('.datepic').datepicker();
            $('.datepic').datepicker( "option", "dateFormat", "yy-mm-dd" );
            $('.datepic').datepicker().datepicker("setDate", "0");
        });
        //static datepicker
        $(".datepic" ).datepicker();
        $(".datepic" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
        $(".datepic").datepicker().datepicker("setDate", "0");
          
        $('form').parsley();    // errors will display only when from submited, to submint the form submit button should be in side the form
        
        //form reset
        $('#reset').click(function(){
            $('#formid')[0].reset();
        });

        // show & hide cheque details box
        $("#cheque").change(function() {
            if(this.checked) {
                $(".cheaqueDetailsDiv").show("fast");
                calculateCredit();
            }
            else{
                $(".cheaqueDetailsDiv").hide("fast");
                calCreditWithOutChq();
            }
        });
        // show & hide for No-item-grn (openning balance+) 
        $("#noitemgrn").change(function() {
            if(this.checked) {
                $("#grnitem-auto").prop('disabled', true);   
                $("#itemprice").prop('disabled', true);
                $("#itemquantity").prop('disabled', true);    
                $("#invoiceDis").prop('disabled', true);     
                $("#cashvalue").prop('disabled', true);
                $("#cheque").attr("disabled", true); // hide 
                $("#grandtotalLbl").hide();
                $("#noitemTotalLbl").show();  
                $("#save").hide();
                $("#saveNoItem").show(); 
                $("#divGrnDesc").show();
            }
            else{
                $("#grnitem-auto").prop('disabled', false);
                $("#itemprice").prop('disabled', false);
                $("#itemquantity").prop('disabled', false);
                $("#invoiceDis").prop('disabled', false);
                $("#cashvalue").prop('disabled', false);
                $("#cheque").attr("disabled", false);
                $("#grandtotalLbl").show();
                $("#noitemTotalLbl").hide();
                $("#save").show();
                $("#saveNoItem").hide();
                $("#divGrnDesc").hide();
            }
        });
        $('#saveNoItem').click(function(){
            var grncde=$("#grncode").val();
            var supplierid=$('#grnsupplier-id').val();
            var amount=parseFloat($("#noitemTotalLbl").val());
            var grnDec=$("#grndesc").val();
            var grnStore=$("#grnStoreLoctn").val();
            if(grnStore == '' || grnStore == '0' || grnStore == null){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You must select a store location!'
                });
                return false;
            }
            if(isNaN(amount)){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Invalid amount!'
                    });
                return false;                
            }
            else if(supplierid==''){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You must select a supplier!'
                });
                return false;
            }
            else if(grncde==''){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You must enter grn code!'
                    });
                    return false;
            }
            else{
                $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('Grns/addGrnPOST2'); ?>",
                    data: {grncode:grncde,supplierid:supplierid,grandtotal:amount,subtotal:0,invoiceDis:0,discount_type:'percentage',date:date,grnDec:grnDec,storeid:grnStore},
                    async: false,
                    dataType: "json",
                    success: function (grnid) {
                        if(grnid>0){
                            swal({
                                type: 'success',
                                title: 'No item grn added',
                                showConfirmButton: false,
                                timer: 1700
                            });
                        }                    
                    },
                    error: function (err) {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Error in grn!'
                        });
                    }
                });
            }      
        });     

        //multiple cheques
        var maxCheques = 5; 
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
             // w 
             $.ajax({ //get all banks to cheque box
                    type: 'post',
                    url: "<?php echo base_url('Banks/getAllBanks'); ?>",	
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
                                                '<select class="form-control" name="bank[]" id="bank" required>'+
                                                    '<option value="">-Select Bank-</option>'+
                                                    options+                                                                 
                                                '</select>'+
                                            '</div>';
                                    
                       chequeHTML ='<div id="chequeDIV">'+
                       '<hr>'+
                        '<div class="form-group row">'+
                            '<label for="amount" class="col-4 col-form-label">Amount:</label>'+
                            '<div class="">'+
                                '<input class="form-control dcmlFixDynmc validationDynmic dynmcChqAmount" type="text" placeholder="Enter Amount 0.00"'+
                                'name="amount[]" id="" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$">'+
                            '</div>'+
                        '</div>'+'<div class="form-group row selectBankDiv">'+  
                        banklist+
                        '</div>'+
                        '<div class="form-group row">'+
                            '<label for="chequeno" class="col-4 col-form-label">Cheque no:</label>'+
                            '<div class="">'+
                                '<input class="form-control" type="text" placeholder="Cheque no" '+
                                'name="chequeno[]" id="" required >'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group row">'+
                            '<label for="date" class="col-4 col-form-label">Date:</label>'+
                            '<div class="">'+
                                '<input class="form-control datepic" value=""  type="date" name="chequedate[]" required>'+
                            '</div>'+
                            '<a href="javascript:void(0);" class="remove_button" title="Remove cheque"><i class="fa fa-minus-square" style="font-size:24px;color:red"></i></a>'+
                        '<div>'+
                    '</div>';                
                                    
                                    
                      //  $("#bank").val());
                      //  $(".selectBankDiv").html(banklist);
                    },
                    error: function() {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'There was an error. Try again please!!'
                        });
                    }
                });
                
     

        var counter = 1;
        var moreChqs=false;
        //Once add button is clicked
        $(addButton).click(function(){
            moreChqs=true;
            if(counter < maxCheques){ 
                counter++; 
                $(wrapper).append(chequeHTML);
               // loadBanks();
            }
        });

        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent().parent('div').remove(); //Remove field html
            counter--; //Decrement field counter
            if(counter==1){moreChqs=false;}
            calculateCredit();
        }); 
          //Once remove button is clicked
          $(".class-chequesinhand").on('click', '.remove_cheqInHand', function(e){
            e.preventDefault();
            $(this).parent().parent('div').remove(); //Remove field html
            var cusCheqId = $(this).attr('rel');
            $("#pCheques option[value=" + cusCheqId + "]").removeAttr('disabled');
            $("#pCheques").val(0);
            //calculateCredit();
        });

        // add grns to below table in the page  And subtotal calculation 
            var grandtotal =0;         
            var subtotal =0;
            var invoiceDis=0;
            var supplierid='';
            var date='';
            var k =0;
           $("#formid").submit(function(e) {
            e.preventDefault();
            $("#cashvalue").val("");
            $("#amount").val(""); 
            $("#bank").val("");
            $("#chequeno").val("");            
            $("#pCheques").val(0);
            supplierid = $('#grnsupplier-id').val();
            var grncode = $("#grncode").val();
            var quantity = parseFloat($('#itemquantity').val());
            if(supplierid==''){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You must select a supplier!'
                });
            }
            else if(grncode==''){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You must enter grn code!'
                    });
            }
            else if (quantity==0){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You must enter quantity!'
                });
            }
            else{
                var itemid = $('#grnitem-id').val();
                var itemname = $( "#grnitem-auto" ).val();
                var price = parseFloat($('#itemprice').val());                
                var priceINTOqty1 = +(price*quantity).toFixed(2); 
                date= $('#datepicker').val();                

                var rows = $("#datatable").find("tr").length;
                var checkNewItem=0;
                for (var j = 1;j<rows; j++) {
                    enteredItemId=$("#datatable").find("tr").eq(j).find("td").eq(1).text();
                    if(itemid==enteredItemId){
                        checkNewItem=0;
                        var enteredItemQty=$("#datatable").find("tr").eq(j).find("td").eq(4).text();
                        var enteredItemQtyInt =parseFloat(enteredItemQty);
                        var newQty = (quantity+enteredItemQtyInt).toFixed(2);
                        var newPriceInTOQty = +(price*newQty).toFixed(2);
                        $("#datatable").find("tr").eq(j).find("td").eq(3).text((price).toFixed(2));
                        $("#datatable").find("tr").eq(j).find("td").eq(4).text(newQty);
                        $("#datatable").find("tr").eq(j).find("td").eq(5).text(newPriceInTOQty);
                        calSubtotal();
                        $("#grnitem-auto").val("");
                        $("#itemquantity").val("");
                        $("#itemprice").val("");
                    }
                    else{
                        checkNewItem=1;
                    }
                }      
                if(checkNewItem==1 || rows==1){
                    k++;
                    var rowtable= '<tr>'+
                                        '<td class="" id="kval_'+k+'">'+k+'</td>'+
                                        '<td class="" style="display:none;" id="itemid_'+k+'">'+itemid+'</td>'+
                                        '<td class="" id="itemname_'+k+'">'+itemname+'</td>'+
                                        '<td class="editable priceField" id="price_'+k+'" style="Text-align: right;">'+(price).toFixed(2)+'</td>'+
                                        '<td class="editable qtyField" id="quantity_'+k+'"  style="Text-align: right;">'+(quantity).toFixed(2)+'</td>'+
                                        '<td class="totalField" id="priceINTOqty1_'+k+'"  style="Text-align: right;">'+priceINTOqty1+'</td>'+
                                        '<td class="discount"><div class="input-group input-group-sm"><input type="text" id="discount_'+k+'" style="Text-align: right;" class="form-control itm_discnt dcmlFixDynmc validationDynmic" size="4"/><select class="form-control itm_dis_type" style="max-width:55px;"><option value="percentage">%</option><option value="flat">LKR</option></select></div></td>'+
                                        '<td>'+
                                            '<a href="javascript:;" class="btn btn-sm btn-danger deleteBtn"><i class="fa fa-times-rectangle-o"></i></a>'+
                                        '</td>'+
                                '</tr>';
                    $("#tbodyID").append(rowtable);
                    calSubtotal();
                    $("#grnitem-auto").val("");
                    $("#itemquantity").val("");
                    $("#itemprice").val("");
                    
                }  
                }            
           });   

        // price & Qty Cell Editing
        var discount=0;
        var priceINTOqty =0;
        var itmTotalWithItmDiscnt=0;     // <= itmTotalWithItmDiscnt: one item's total with discount 
        var OriginalContent = 0;           
        var price,qty,totalElmt;  
        $('#tbodyID').on('dblclick', '.editable', function(e){
            OriginalContent = $(this).text();
                price = $(this).siblings('.priceField').text();                            
                qty = $(this).siblings('.qtyField').text(); 
                totalElmt = $(this).siblings('.totalField');
                discount = $(this).siblings('.discount').find('input[type=text]').val();
                if(discount==''){discount=0; }  //validation required
            $(this).addClass("cellEditing");
            $(this).html("<input name='pq' class='priceANDqty validationDynmic' type='text' value='" + OriginalContent + "' />");
            $(this).children().first().focus();     
        });         

        //single item total with discnt calculations when price & qty edited
        var newContent = 0;
        $('#tbodyID').on('keyup', '.priceANDqty', function(e){
            e.preventDefault();
                newContent = $(this).val();
                var discType = $(this).closest('tr').find('.itm_dis_type').val() || 'percentage';
                function applyDiscGrn(lineTotal, disc, dtype){
                    if(dtype == 'flat') return +(lineTotal - disc).toFixed(2);
                    return +((100-disc)*lineTotal/100).toFixed(2);
                }
                if(price==''){
                    priceINTOqty = +(newContent*qty).toFixed(2);
                    itmTotalWithItmDiscnt = applyDiscGrn(priceINTOqty, discount, discType);
                    totalElmt.text(itmTotalWithItmDiscnt);
                    calSubtotal();
                }
                else if(qty==''){
                    priceINTOqty = +(newContent*price).toFixed(2);
                    itmTotalWithItmDiscnt = applyDiscGrn(priceINTOqty, discount, discType);
                    totalElmt.text(itmTotalWithItmDiscnt);
                    calSubtotal();
                }
        });
        $('#tbodyID').on('focusout', '.priceANDqty', function(){ 
            var num =$( this ).val();
            var para =$( this ).parent();
            if(newContent==0){//validation required // didn't edit
                $(this).parent().text(OriginalContent);
            }
            else{
                $(this).parent().text(newContent); //edited
                newContent=0;
                var DcmlDigts = decimalPlaces(num);
                if(DcmlDigts<2 && num!=''){
                var newvalue = parseFloat(num).toFixed(2);
                para.text(newvalue);                
            }
            } 
            $(this).parent().removeClass("cellEditing");                        
        });

 

        //Item discount & subtotal calculation , when discount field change
        function recalcGrnItemDiscount(el){
            var $row = $(el).closest('tr');
            var dis = parseFloat($row.find('.itm_discnt').val());
            if(isNaN(dis)) dis = 0;
            var disType = $row.find('.itm_dis_type').val() || 'percentage';
            var prc = parseFloat($row.find('.priceField').text());
            var qnty = parseFloat($row.find('.qtyField').text());
            var totalElemnt = $row.find('.totalField');
            var lineTotal = prc * qnty;
            if(disType == 'flat'){
                itmTotalWithItmDiscnt = +(lineTotal - dis).toFixed(2);
            } else {
                itmTotalWithItmDiscnt = +((100-dis)*lineTotal/100).toFixed(2);
            }
            if(itmTotalWithItmDiscnt < 0) itmTotalWithItmDiscnt = 0;
            totalElemnt.text(itmTotalWithItmDiscnt);
            calSubtotal();
        }
        $('#tbodyID').on('keyup', '.itm_discnt', function(e){
            recalcGrnItemDiscount(this);
        });
        $('#tbodyID').on('change', '.itm_dis_type', function(e){
            recalcGrnItemDiscount(this);
        });
        //Subtotal
        function calSubtotal(){
            var rows = $("#datatable").find("tr").length;
            subtotal =0;
            for(var i=1; i<=rows; i++){
                var rowTotal=$("#datatable").find("tr").eq(i).find("td").eq(5).text();
                subtotal= +(1*subtotal+1*rowTotal).toFixed(2);
                $("#subtotal").html(subtotal);                
            }
            grandtotalCalculation();
        }
        //Grand total calculation
        function grandtotalCalculation(){
            invoiceDis = parseFloat($('#invoiceDis').val());
            if(isNaN(invoiceDis)){invoiceDis=0;}
            var disType = $('#invoiceDisType').val();
            var discountedTotal = 0;
            if(disType == 'flat'){
                discountedTotal = +(subtotal - invoiceDis).toFixed(2);
            } else {
                discountedTotal = +((100-invoiceDis)*subtotal/100).toFixed(2);
            }
            if(discountedTotal < 0) discountedTotal = 0;
            grandtotal = +(discountedTotal).toFixed(2);

            $("#grandtotalLbl").html(grandtotal);
            $("#creditvalue").html(grandtotal);
        }

        // invoice Discount
        $( "#invoiceDis" ).keyup(function() {
            grandtotalCalculation();
        });
        $('#invoiceDisType').change(function(){
            grandtotalCalculation();
        });

        //Add cheques        
        var ChqFormsubmittd =false;
        $("#chequeform").submit(function(e){
            e.preventDefault(); 
            ChqFormsubmittd =true;                     
        });
        
        //loadbanks
        function loadBanks(){
            $.ajax({ //get all banks to cheque box
                    type: 'post',
                    url: "<?php echo base_url('Banks/getAllBanks'); ?>",	
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
                                                '<select class="form-control" name="bank[]" id="bank" required>'+
                                                    '<option value="">-Select Bank-</option>'+
                                                    options+                                                                 
                                                '</select>'+
                                            '</div>';
                        //$("#bank").val());
                        $(".selectBankDiv").html(banklist);
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

        $("#cheque").change(function() {
            if(this.checked) {
                loadBanks();
            }
        });

        //insert grns to DB by for loop
        $('#save').click(function(){
            if ($("#cheque").is(':checked')) {
                    $("#chequeform").submit();
            }
            var cashvalue=$("#cashvalue").val();
                if(cashvalue==''){cashvalue=0;}
                var creditvalue= parseFloat($('#creditvalue').text());
            supplierid = $("#grnsupplier-id").val();
            var grncode = $("#grncode").val();
            var grnStore = $("#grnStoreLoctn").val();
            var grn_ID;
            var rows = $("#datatable").find("tr").length;

            if(grnStore == '' || grnStore == '0' || grnStore == null){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You must select a store location!'
                });
                return false;
            }

            if(rows>1&&supplierid>0&&cashvalue>=0&&grncode!=''){
                if($("#cheque").is(':checked')) {
                    if(ChqFormsubmittd==true){
                        saveGrn();
                        ChqFormsubmittd =false;
                    }
                    else{
                        swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'cheqque fields are not filled!'
                            });
                    }
                }
                else{
                    saveGrn();
                }
                function saveGrn(){
                  
                   // alert("W saveGrn started");
                    $.ajax({
                        type: "Post",
                        url:"<?php echo base_url('Grns/addGrnPOST2'); ?>",
                        data: {grncode:grncode,supplierid:supplierid,grandtotal:grandtotal,subtotal:subtotal,invoiceDis:invoiceDis,discount_type:$('#invoiceDisType').val(),date:date,grnDec:"",creditvalue:creditvalue,storeid:grnStore},
                        async: false,
                        dataType: "json",
                        success: function (grnid) {
                            grn_ID= grnid;
                           swal({
                                type: 'success',
                                title: 'GRN Added',
                                showConfirmButton: true,
                                }).then(function() {
                                 location.reload();
                                });  
                        },
                        error: function (err) {
                         // alert("W saveGrn error started");
                            swal({
                              
                                type: 'error',
                                title: 'Oops...',
                                text: 'Error in grn!'
                            });
                        }
                    });

                    //Add cash and credit                 
                    $.ajax({
                        type: "Post",
                        url:"<?php echo base_url('SupplierPayment/supplierCash'); ?>",
                        data: {grnID:grn_ID,cash:cashvalue,credit:creditvalue},
                        async: false,
                        dataType: "json",
                        success: function (res) {
                            
                        console.log("supplier paymnt saved"); 
                        },
                        error: function (err) {
                            alert("supplier payment error");
                        }
                    });

                    //payment log by cash
                    insertMainCashTable(supplierid,cashvalue,date, function(cashID){
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('SupplierPayment/supplierCashLog'); ?>",
                            data: {grnID:grn_ID,cashID:cashID,cash:cashvalue,date:date},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                           // alert("W payment log by cash success started");
                            console.log("supplier paymnt log saved"); 
                            },
                            error: function (err) {
                               // alert("W payment log by cash error started");
                                alert("supplier payment log error");
                            }
                        });
                    });
                    //supplier payment,Add cheques
                    if (($("#cheque").is(':checked'))&&moreChqs==true) {
                        // First, get the supplier ID
                        var supplier_newid = $('#grnsupplier-id').val();
                        
                        // Then serialize form data and append additional parameters
                        var data = $('#chequeform').serialize() + "&grnID=" + grn_ID + "&date=" + date + "&supplier_newid=" + supplier_newid;
                        
                        
                        // Log the supplier ID
                        console.log("The supplier ID for the cheque is: " + supplier_newid);
                        
                        
                        $.ajax({
                            type: 'post',
                            url: "<?php echo base_url('SupplierPayment/supplierCheque'); ?>",
                            data: data,
                            async: false,
                            dataType:'json',
                            success: function(supChqID){
                             // alert(supChqID);                           
                            },
                            error: function() {
                                alert("There was an error in cheaques. Please check again!");
                            }
                        });
                    }
                    var countCheqs =$('#pCheques > option').length; 
                    var countParty = $("#cheqsInHandDiv div").length; //check whether ther are party cheqs 
                    if(countCheqs>1&&countParty>0){
                        //Add Supplier payment by party cheques 
                        if ($("#cheque").is(':checked')) {
                            var data = $('#chequeInHandform').serialize()+ "&grnID="+grn_ID+"&date="+date+"&sup_id=" + sup_id;
                            $.ajax({
                                type: 'post',
                                url: "<?php echo base_url('SupplierPayment/partyCheques'); ?>",
                                data: data,
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                // alert("party cheque inserted");                            
                                },
                                error: function() {
                                    alert("Error in adding cheques in hand. Please check again!");
                                }
                            });
                            // marked as transferred(1) after customer cheque sent to supplier
                            $.ajax({
                                type: 'post',
                                url: "<?php echo base_url('CustomerPayment/markAsTransterred'); ?>",
                                data: data,
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                    loadCusCheques();
                                    //alert("customer cheque transferred successfully");                            
                                },
                                error: function() {
                                    alert("Error in customer cheque transfering. Please check again!");
                                }
                            });
                        }
                    }
               
                    var itemid,price,quantity,total,itmDis;            
                    var rows = $("#datatable").find("tr").length; 
                    var w_resetter=rows-1;
                   // alert("Row count is = "+ rows);
                    var wish_w=0;
                    for (var i = 1; i < rows; i++) { 
                    
                        itemid = $("#datatable").find("tr").eq(i).find("td").eq(1).text();
                        price=$("#datatable").find("tr").eq(i).find("td").eq(3).text();
                        quantity=$("#datatable").find("tr").eq(i).find("td").eq(4).text();
                        total=$("#datatable").find("tr").eq(i).find("td").eq(5).text();
                        itmDis=$("#datatable").find("tr").eq(i).find("td").eq(6).find('input[type=text]').val();
                        var itmDisType=$("#datatable").find("tr").eq(i).find("td").eq(6).find('.itm_dis_type').val() || 'percentage';

                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('Grns/insertGrnItemPOST2'); ?>",
                            data: {grnID:grn_ID,itemid:itemid,price:price,quantity:quantity,total:total,itmDis:itmDis,itmDisType:itmDisType},
                            async: false,
                            dataType: "json",
                            success: function () {
                            },
                            error: function (err) {
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Error in grn items!'
                                });
                            }
                        });
                
                        //to change crrent qty
                        var curgrnInsrtdid=0;
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('CurQtyWithGrn/addGRNItems'); ?>",
                            data: {grnID:grn_ID,itmid:itemid,qty:quantity,prc:price,ttl:total,storeid:grnStore},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                                curgrnInsrtdid=res;
                                //alert(curgrnInsrtdid);
                            },
                            error: function (err) {
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Error in adding grn to qty maintainace table!'
                                });
                                console.log(err);
                            }
                        });
                        
                        if(curgrnInsrtdid>0){
                            //change crrent qty of grn with pending insufficient stock solds
                            $.ajax({
                                type: "Post",
                                url:"<?php echo base_url('InsufficentStockSale/adjustGrnWithinsuffi'); ?>",
                                data: {itmid:itemid,qty:quantity,cur_id:curgrnInsrtdid},
                                async: false,
                                dataType: "json",
                                success: function (res) {
                                    if (res.status === 'error') {
                                        console.log('Insufficient stock adjustment error:', res.message);
                                    } else {
                                        console.log('Insufficient stock adjusted:', res);
                                    }
                                },
                                error: function (err) {
                                    console.log('Insufficient stock adjustment skipped:', err);
                                }
                            });
                        }else{
                            console.log('GRN item added, no insufficient stock to adjust');
                        }
                    

                        // stock +
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('Stocks/increaseStock'); ?>",
                            data: {itmid:itemid,qty:quantity,storeid:grnStore},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                                console.log("W new data added to stock");
                            },
                            error: function (err) {
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'error in grn stock !'
                                });
                                console.log(err);
                            }
                        });

                        // stocklog +
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('Stocks/stocklog'); ?>",
                            data: {itmid:itemid,qty:quantity,grnID:grn_ID,storeid:grnStore},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                                //alert("added to stock");
                            },
                            error: function (err) {
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'error in grn stocklog'
                                });
                                console.log(err);
                            }
                        });
                        if(w_resetter==i){ 
                        //To reset datatable into initial state,
                        $("#tbodyID").empty();
                        }
                        $("#subtotal").html("0.00"); 
                        $("#invoiceDis").val("");
                        $("#grandtotalLbl").html("0.00");
                        $("#cashvalue").val("");
                        $("#creditvalue").html("0.00");
                        $("#amount").val(""); 
                        $("#bank").val(""); 
                        $("#chequeno").val(""); 
                        $("#chequeDIV").remove();
                        $('#cheque').prop('checked', false);
                        $(".cheaqueDetailsDiv").hide("fast");
                        $("#cheqsInHandDiv").empty();
                    }
                }
                
            }
            else{
                if(supplierid==''){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You must select a supplier!'
                    });
                }
                else if(grncode==''){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You must enter grn code!'
                    });
                }
                else if(rows<=1){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Grn items are empty!'
                    });
                }
                else if(!(cashvalue>0)&&cashvalue!=''){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Entered cash value not valid!'
                    });
                }
            }                     
        });
        function insertMainCashTable(sup_ID,new1Cash,date,callback){
                $.ajax({// insert to cash main table
                    type: 'post',
                    url: "<?php echo base_url('SupplierPayment/supplierPayCash'); ?>",
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

        //delete & calculations
        $('#tbodyID').on('click', '.deleteBtn', function(e){
            $(this).parent().parent().remove();
            var rows = $("#datatable").find("tr").length;
            for ( k = 1; k <= rows; k++) { 
                $("#datatable").find("tr").eq(k).find("td").eq(0).text(k);                
            }
            k=(rows-1);
            calSubtotal();
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
        $('#tbodyID,.cheaqueDetailsDiv').on('focusout', '.dcmlFixDynmc', function(e){            
            var num = $(this).val();
            var para = $(this).parent();
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

        //dynamically created table cell validation
        $('#tbodyID,.cheaqueDetailsDiv').on('keypress', '.validationDynmic', function(e){
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
            ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
            event.preventDefault();
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'In valid number!'
                });
        }
        var text = $(this).val();
        if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Invalid!'
                });
            }
        });
        
    //autoload supliers
    var availableTags = [
        <?php
         foreach ($suppliers as $supplier)
        {
           echo '{ label: "'.$supplier->sup_name.'", value:"'.$supplier->sup_id.'" },';
        }
        ?>
    ];
    $( "#grnsupplier-auto" ).autocomplete({
        source: availableTags,
        select: function(event, ui) {
                event.preventDefault();
                $("#grnsupplier-auto").val(ui.item.label);
                $('#grnsupplier-id').val(ui.item.value);
                $("#show_sup").show("fast");
                $("#btnChange").show("fast");
                $("#grnsupplier-auto").parent().hide("fast");
                var slectedsup = ui.item.label;
                $("#show_sup").text(slectedsup);
            },      
    });
    $("#btnChange").click(function() {
            $("#btnChange").hide("fast");
            $("#show_sup").hide("fast");
            $("#grnsupplier-auto").val('');
            $("#grnsupplier-id").val('');
            $("#grnsupplier-auto").parent().show();            
    });

    //autoload items 
    var availableItems=[
        <?php
         foreach ($items as $item)
        {
           echo '{ label: "'.$item->itm_name.' - '.$item->itm_code.'", value:"'.$item->itm_id.'" },';
        }
        ?>
    ];
    $( "#grnitem-auto" ).autocomplete({
        source: availableItems,
        select: function(event, ui) {
                event.preventDefault();
                $("#grnitem-auto").val(ui.item.label);
                $('#grnitem-id').val(ui.item.value);             
            },      
    });

    //Payment calculate credit & display according to only cash
    function calCreditWithOutChq(){
        var cashvalue=parseFloat($("#cashvalue").val());
        if(isNaN(cashvalue)||cashvalue=='') {
        cashvalue = 0;
        }
        var creditvalue=(grandtotal-cashvalue).toFixed(2);
        $("#creditvalue").html(creditvalue);
    }

    //Payment calculate credit & display according to cheq & cash
    function calculateCredit(){
        var cashvalue=parseFloat($("#cashvalue").val());
            if(isNaN(cashvalue)||cashvalue=='') {
            cashvalue = 0;
            }

        var partyCheqAmnt=0;
        var partyamnt =$("input[name='amountParty[]']").map(function(){
                    var w= parseFloat($(this).val());
                    if(isNaN(w)||w=='') {
                        w=0;
                    }
                    partyCheqAmnt+=w;
                    partyCheqAmnt.toFixed(2);
                }).get();


        var totalcheq=0;
        var chqv =$("input[name='amount[]']").map(function(){
                    var v= parseFloat($(this).val());
                    if(isNaN(v)||v=='') {
                        v=0;
                    }
                    totalcheq+=v;
                    totalcheq.toFixed(2);
                }).get();
        var creditvalue=(grandtotal-cashvalue-totalcheq-partyCheqAmnt).toFixed(2);
        $("#creditvalue").html(creditvalue);
    }

    //Payments : ajust the credit accourding to cash
    $("#cashvalue").keyup(function(){
        calculateCredit();
    });

    //Payments : ajust the credit accourding to cheque,Static
    $(".staticChqAmount").keyup(function(){
        calculateCredit();   
    });

     //Payments : ajust the credit accourding to cheque,dynamic
     $('.cheaqueDetailsDiv').on('keyup', '.dynmcChqAmount', function(e){
        calculateCredit();
    });

    //Payments : ajust the credit accourding to party Cheques
    $("#pCheques").change(function () { 
        var prtyChqID=$("#pCheques").val();
        if(prtyChqID!=0){
            $("#pCheques option:selected").attr('disabled','disabled');        
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
                            '<input type="hidden" name="hiddenCusChqID[]" id="hiddenID" value="'+data.cus_cheque_id+'">'+
                            '<div class="form-group row">'+
                                '<label for="amount2" class="col-4 col-form-label">Amount:</label>'+
                                '<div class="">'+
                                    '<input class="form-control   " type="text" readonly '+
                                    'name="amountParty[]" id="amount2" value="'+data.cus_cheque_amount+'" >'+
                                '</div>'+
                            '</div>'+
                            
                            '<div class="form-group row">'+
                                '<label for="bank2" class="col-4 col-form-label">Bank:</label>'+
                                '<div class="">'+
                                    '<input class="form-control" type="text" readonly '+
                                    'name="bankParty[]" id="bank2" value="'+data.cus_cheque_bank+'" >'+
                                '</div>'+
                            '</div>'+

                            '<div class="form-group row">'+
                                '<label for="chequeno2" class="col-4 col-form-label">Cheque no:</label>'+
                                '<div class="">'+
                                    '<input class="form-control" type="text" readonly '+
                                    'name="chequenoParty[]" id="chequeno2" value="'+data.cus_cheque_num+'" >'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-group row">'+
                                '<label for="date2" class="col-4 col-form-label">Date:</label>'+
                                '<div class="">'+
                                    '<input class="form-control datepic" name="chequedateParty[]" id="date2" value="'+data.cus_cheque_date+'" readonly>'+
                                '</div>'+
                                '<a href="javascript:void(0);" rel="'+data.cus_cheque_id+'" class="remove_cheqInHand" title="Remove cheque"><i class="fa fa-minus-square" style="font-size:24px;color:red"></i></a>'+
                            '<div>'+
                        '</div>'; 
                    $("#cheqsInHandDiv").append(chequesInHand);
                    calculateCredit();
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
            calculateCredit();
        } 
    });
    function loadCusCheques(){
        $.ajax({
            type: 'post',
            url: "<?php echo base_url('Grns/getPartyCheques'); ?>",
            async: false,
            dataType:'json',  
            success: function(data){
                var len=data.length;
                $('#pCheques').empty().append('<option value="0">-None-</option>');
                for( var i = 0; i<len; i++){
                            var ChqAmount = data[i]['cus_cheque_amount'];
                            var chqid = data[i]['cus_cheque_id'];  
                            var chqNum = data[i]['cus_cheque_num']; 
                            var chqDate = data[i]['cus_cheque_date']; 
                            $("#pCheques").append("<option data-amount='"+ChqAmount+"' value='"+chqid+"'>"+chqNum+"-"+ChqAmount+"-"+chqDate+"</option>");
                        }  
            },
            error: function() {
                alert("There was an error. Try again please!");
            }
        });
    }
    loadCusCheques();//party cheques

  });






  </script>