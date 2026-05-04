        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
        <div class="container-fluid">

            <div class="row">            
                <div class="col-lg-4 col-md-5 col-sm-12"><!-- Add Sales Form -->
                    <div class="row">      
                        <div class="col-12"><!-- col-lg-6 col-md-6 col-sm-8 col-xs-12-->
                            <div class="card-box clearfix">
                                <div class="row">
                                    <div class="col-6"><h4 class="header-title m-t-0 m-b-30">Add New Sales</h4></div>
                                    <div class="col-6">
                                        <select class="form-control" name="storeLoctn" id="storeLoctn">
                                        <?php if($_SESSION['userrole']==1){?>
                                        <option value="0">Sale Location</option>
                                        <?php }?>
                                        <?php
                                            foreach ($storeLoc as $store)
                                            {
                                            echo '<option value="'.  $store->store_id.'"> '. $store->store_name.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <fieldset>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Sale Type<span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <select class="form-control" id="sale_type">
                                            <option value="cash">Cash Customer</option>
                                            <option value="card">Card Customer</option>
                                            <option value="credit">Credit Customer</option>
                                            <option value="online">Online Sale</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="online_sale_id_div" style="display:none;">
                                    <label class="col-4 col-form-label">Online ID<span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <input class="form-control" type="text" id="online_sale_id" placeholder="Online order/sale reference ID">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="customer-auto" class="col-4 col-form-label">Customer<span class="text-danger">*</span></label>
                                    <div class="col-6">
                                        <input class="form-control"  id="customer-auto" placeholder="Select" >
                                        <input type="hidden" class="form-control" name="customer" id="customer-id">
                                   </div>
                                    <div class="col-2">
                                        <a href="#"><b><span id="show_cus" class="hover" data-toggle="tooltip" ></span></b></a>
                                        <button id="btnChange" style="display:none;" class="btn btn-sm btn-warning">
                                            <i class="fa fa-exchange"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Phone<span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <input class="form-control" type="text" id="customer_phone" placeholder="Customer phone number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="datepicker" class="col-4 col-form-label">Date<span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <input class="form-control datepic" value="" id="datepicker">
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
                                <div class="form-group row mb-1">
                                    <label class="col-5 col-form-label"><i class="fa fa-truck"></i> Delivery:</label>
                                    <div class="col-7">
                                        <select class="form-control form-control-sm" id="delivery_company">
                                            <option value="">No Delivery</option>
                                            <?php if(isset($deliveryCompanies)): foreach($deliveryCompanies as $dc): ?>
                                            <option value="<?php echo $dc->dc_id; ?>"><?php echo $dc->dc_name; ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-1" id="delivery_charge_div" style="display:none;">
                                    <label class="col-5 col-form-label">Delivery Charge:</label>
                                    <div class="col-7">
                                        <input class="form-control DecimalFix" type="text" placeholder="0.00" id="delivery_charge_input">
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <label class="col-5 col-form-label" style="font-size:16px;"><strong>Grand Total:</strong></label>
                                    <div class="col-7 col-form-label text-right" style="font-size:16px;"><strong>LKR <span id="grandtotalLbl">0.00</span></strong></div>
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
                                <?php if(isset($paymentMethods) && count($paymentMethods) > 0){ foreach($paymentMethods as $pm){ ?>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label"><?php echo $pm->pm_name; ?>:</label>
                                    <div class="col-8">
                                        <input class="form-control DecimalFix pm-amount-input" type="text" placeholder="0.00"
                                        data-pmid="<?php echo $pm->pm_id; ?>" data-parsley-pattern="^[0-9]*\.?[0-9]*$">
                                    </div>
                                </div>
                                <?php }} ?>
                                <div class="form-group row">
                                    <label class="col-5 col-form-label">Credit Limit:</label>
                                    <div class="col-7 col-form-label text-right">LKR <span id="credit_lmt_value">0.00</span></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-5 col-form-label">Customer Balance:</label>
                                    <div class="col-7 col-form-label text-right">LKR <span id="customer_balance">0.00</span></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-5 col-form-label">Credit:</label>
                                    <div class="col-7 col-form-label text-right">LKR <span id="creditvalue">0.00</span></div>
                                </div>
                                <!-- Gift Voucher Redemption -->
                                <hr>
                                <div style="background:#fff3e0;border-radius:4px;padding:10px;margin-bottom:10px;">
                                    <strong><i class="fa fa-gift"></i> Redeem Gift Voucher</strong>
                                    <div class="input-group m-t-10">
                                        <input type="text" class="form-control" id="redeem_card_number" placeholder="Enter card number...">
                                        <div class="input-group-append">
                                            <button class="btn btn-warning" type="button" id="btn_validate_voucher"><i class="fa fa-check"></i> Apply</button>
                                        </div>
                                    </div>
                                    <div id="voucher_list" class="m-t-10"></div>
                                    <div class="form-group row mb-0 m-t-5" id="voucher_total_row" style="display:none;">
                                        <label class="col-5 col-form-label"><strong>Voucher Total:</strong></label>
                                        <div class="col-7 col-form-label text-right"><strong style="color:#e65100;">LKR <span id="voucher_total">0.00</span></strong></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-4"></div>
                                    <div class="checkbox checkbox-primary">
                                        <input id="cheque" name="cheque" type="checkbox">
                                        <label for="cheque">Cheque</label>
                                    </div>
                                </div>
                                <form action="" id="chequeform">
                                    <div class="field_wrapper" id="cheaqueDetailsDiv" style="display:none;">
                                            <div class="form-group row">
                                                <label for="amount" class="col-4 col-form-label">Amount:</label>
                                                <div class="col-8">
                                                    <input class="form-control DecimalFix staticValication staticChqAmount" type="text" placeholder="Enter Amount 0.00"
                                                    name="amount[]" id="amount" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="bank" class="col-4 col-form-label">Bank:</label>
                                                <div class="col-8">
                                                    <input class="form-control" type="text" placeholder="Bank Name"
                                                    name="bank[]" id="bankname" required >
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="chequeno" class="col-4 col-form-label">Cheque no:</label>
                                                <div class="col-8">
                                                    <input class="form-control" type="text" placeholder="Cheque Number"
                                                    name="chequeno[]" id="chequeno" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-4 col-form-label">Date<span class="text-danger">*</span></label>
                                                <div class="col-6">
                                                    <input class="form-control datepic" id="chequedate" value=""  name="chequedate[]" required>
                                                </div>
                                                <div class="col-2 d-flex align-items-center">
                                                    <a href="javascript:void(0);" class="add_button" title="Add another cheque"><i class="fa fa-plus-square" style="font-size:24px;color:green"></i></a>
                                                </div>
                                            </div>
                                    </div>
                                </form>                                 
                                <div class="pull-right">                                                               
                                    <button href="javascript:window.print()" id="save" disabled class="btn btn-primary waves-effect"><i class="fa fa-print"></i></button>
                                    <div id="div_result"></div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div><!-- End of Add Sales Form // href="javascript:window.print()" _blank//-->

                <div class="col-lg-8 col-md-7 col-sm-12"><!--Start Table & row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row mb-2" style="background:#e8f5e9;padding:8px;border-radius:4px;">
                                <label class="col-3 col-form-label"><i class="fa fa-barcode"></i> Scan Barcode:</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" id="barcode-scan-input" placeholder="Scan or type barcode and press Enter" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                        <section>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="saleitem-id" class="col-form-label">Item Name<span class="text-danger">*</span></label>
                                            <input class="form-control"  id="saleitem-auto" placeholder="Select" required>
                                            <input type="hidden" class="form-control" name="saleitem" id="saleitem-id">
                                            
                                          <!--  <select class="form-control" name="saleitem" id="saleitem" required>
                                                <option value="">-Select Item-</option>
                                                <?php
                                                foreach ($items as $item)
                                                {
                                                print '<option value="'.  $item->itm_id.'"> '. $item->itm_name.'</option>';
                                                }
                                                ?>
                                            </select>
                                            -->
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="itemquantity" class="col-form-label">Item Qty<span class="text-danger">*</span></label>
                                            <input class="form-control DecimalFix" type="text" placeholder="Enter Quantity" 
                                                name="itemquantity" id="itemquantity" required data-parsley-type="number">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="itemprice" class="col-form-label">Item Price</label>
                                            <input class="form-control DecimalFix" type="text" placeholder="0.00" 
                                                name="itemprice" id="itemprice" required data-parsley-pattern="^[0-9]*\.[0-9]{2}$"> 
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
          </div> 
        </div> <!-- container-fluid -->
                
<!-- Card Reference Modal -->
<div class="modal" id="cardRefModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-credit-card"></i> Card Machine Reference IDs</h5>
            </div>
            <div class="modal-body">
                <p class="text-muted">Enter the reference/bill ID from the card machine receipt for each payment:</p>
                <div id="cardRefFields"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnConfirmCardRefs"><i class="fa fa-check"></i> Confirm & Save Sale</button>
            </div>
        </div>
    </div>
</div>

<!-- New Customer Popup for Credit Sales -->
<div class="modal" id="newCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-user-plus"></i> Add New Customer</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-warning">Credit sale requires a saved customer. Add details below:</p>
                <div class="form-group">
                    <label>Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nc_name" placeholder="Customer name">
                </div>
                <div class="form-group">
                    <label>Phone<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nc_phone" placeholder="Phone number">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" id="nc_address" placeholder="Address">
                </div>
                <div class="form-group">
                    <label>Credit Limit</label>
                    <input type="number" class="form-control" id="nc_creditlimit" value="0" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btnSaveNewCustomer"><i class="fa fa-save"></i> Save Customer</button>
            </div>
        </div>
    </div>
</div>

<!-- SMS Send Confirmation Modal -->
<div class="modal" id="smsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-envelope"></i> Send Bill SMS</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <p>Send bill SMS to customer?</p>
                <p><strong id="sms_phone_display"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Skip</button>
                <button type="button" class="btn btn-success" id="btnSendSms"><i class="fa fa-paper-plane"></i> Send SMS</button>
            </div>
        </div>
    </div>
</div>

<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
    $( function() {
        document.getElementById("save").disabled = true;

        // =========== SALE TYPE LOGIC ===========
        function applySaleType() {
            var st = $('#sale_type').val();
            // Show/hide online sale ID
            if (st == 'online') {
                $('#online_sale_id_div').show();
                $('#delivery_company').closest('.form-group').show();
            } else {
                $('#online_sale_id_div').hide();
            }
            // Show/hide payment method inputs based on sale type
            if (st == 'cash') {
                $('#cashvalue').closest('.form-group').show();
                $('.pm-amount-input').closest('.form-group').hide();
            } else if (st == 'card') {
                $('#cashvalue').closest('.form-group').show();
                $('.pm-amount-input').closest('.form-group').show();
            } else if (st == 'credit') {
                $('#cashvalue').closest('.form-group').show();
                $('.pm-amount-input').closest('.form-group').show();
            } else if (st == 'online') {
                $('#cashvalue').closest('.form-group').show();
                $('.pm-amount-input').closest('.form-group').show();
            }
        }
        $('#sale_type').on('change', function(){ applySaleType(); });
        applySaleType();

        // =========== PHONE AUTO-POPULATE ON CUSTOMER SELECT ===========
        $(document).on('customerSelected', function(){
            var cusid = $("#customer-id").val();
            if(cusid){
                $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('Customers/getCusDetails'); ?>",
                    data: {id:cusid},
                    async: false,
                    dataType: "json",
                    success: function(data){
                        if(data && data.cus_contact){
                            $('#customer_phone').val(data.cus_contact);
                        }
                    }
                });
            }
        });

        // =========== CARD REF + SMS FLOW GLOBALS ===========
        var pendingCardRefs = [];
        var lastSavedSaleId = 0;
        var lastSavedPhone = '';

        $('.hover').tooltip({
            borderWidth: 0,
            show: { delay: 0, duration: 0 },
            content:fetchData,
            html:true,
        });
   
   
   
       function noNaN(a) { return a = a || 0 }
       
       
       
        function fetchData(){
            var fetch_data='';
            var cusid=$("#customer-id").val();
                $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('Customers/getCusDetails'); ?>",
                    data: {id:cusid},
                    async: false,
                    dataType: "json",
                    success: function (data) {
                        if(data!=false){
                            fetch_data='<table style="font-family:Georgia, serif; background-color:#000000;font-size:18px; color:white;">'+
                                '<tr>'+
                                    '<td>Name: </td><td>'+data.cus_name+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>Address: </td><td>'+data.cus_address+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>Contact: </td><td>'+data.cus_contact+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>Balance: </td><td>'+data.cus_balance+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td>Credit Limit: </td><td>'+data.cus_creditlimit+'</td>'+
                                '</tr>'+
                            '</table>'
                        }else{
                            fetch_data='';
                        }
                    },
                    error: function (err) {
                        alert("error");
                    }
                });
                return fetch_data;
        }
        //not in use,, using by class name
       // $("#datepicker" ).datepicker();
      //  $("#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
       // $("#datepicker").datepicker().datepicker("setDate", "0");

        //Dynamic datepicker
        $('.field_wrapper').on('click',".add_button", function(){
            $('.datepic').datepicker();
            $('.datepic').datepicker( "option", "dateFormat", "yy-mm-dd" );
            $('.datepic').datepicker().datepicker("setDate", "0");
        });

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
                $("#cheaqueDetailsDiv").show("fast");
                calculateCredit();
            }
            else{
                $("#cheaqueDetailsDiv").hide("fast");
                calCreditWithOutChq();
            }
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

    var moreChqs=false;
    //Once add button is clicked
    $(addButton).click(function(){
        moreChqs=true;
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
        calculateCredit();
    });

      //   $("#customer").select2(); //not in use

        //get selected item price
        var itemid =0;
        function ItemChangedEvent() {
            itemid = $('#saleitem-id').val();
            $.ajax({
                    type: "Post",
                    url:"<?php echo base_url('Sales/getItemPrice'); ?>",
                    data: {itemid:itemid},
                    async: false,
                    dataType: "json",
                    success: function (data) {
                        $('input[name=itemprice]').val(data.itm_sellingprice);
                    },
                    error: function (err) {
                        alert("error");
                    }
                });
        }

        // add sales to below table in the page  And subtotal calculation 
            var grandtotal =0;         
            var subtotal =0;
            var invoiceDis=0;
            var cusID='';
            var date='';
            var store='';
            var k =0;
        $("#formid").submit(function(e) {
            e.preventDefault();
            
            cusID = $('#customer-id').val();
            var quantity = parseFloat($('#itemquantity').val());
            store= $('#storeLoctn').val();
            if(cusID==''){
                swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You must select a customer!'
                    });
            }
            else if(store==0){
                swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You must select a sale location!'
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
                var itemname = $( "#saleitem-auto" ).val();
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
                    $("#saleitem-auto").val("");
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
                                    '<td class="">'+k+'</td>'+
                                    '<td class="" style="display:none;">'+itemid+'</td>'+ 
                                    '<td class="">'+itemname+'</td>'+
                                    '<td class="editable priceField" style="Text-align: right;">'+(price).toFixed(2)+'</td>'+
                                    '<td class="editable qtyField" style="Text-align: right;">'+(quantity).toFixed(2)+'</td>'+
                                    '<td class="totalField" style="Text-align: right;">'+priceINTOqty1+'</td>'+
                                    '<td class="discount"><div class="input-group input-group-sm"><input type="text" style="Text-align: right;" class="form-control itm_discnt dcmlFixDynmc validationDynmic" size="4"/><select class="form-control itm_dis_type" style="max-width:55px;"><option value="percentage">%</option><option value="flat">LKR</option></select></div></td>'+
                                    '<td>'+
                                        '<a href="javascript:;" class="btn btn-sm btn-danger deleteBtn"><i class="fa fa-times-rectangle-o"></i></a>'+
                                    '</td>'+
                                '</tr>';
                    $("#tbodyID").append(rowtable);
                    calSubtotal();
                    $("#saleitem-auto").val("");
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
                function applyDisc(lineTotal, disc, dtype){
                    if(dtype == 'flat') return +(lineTotal - disc).toFixed(2);
                    return +((100-disc)*lineTotal/100).toFixed(2);
                }
                if(price==''){
                    priceINTOqty = +(newContent*qty).toFixed(2);
                    itmTotalWithItmDiscnt = applyDisc(priceINTOqty, discount, discType);
                    totalElmt.text(itmTotalWithItmDiscnt);
                    calSubtotal();
                }
                else if(qty==''){
                    priceINTOqty = +(newContent*price).toFixed(2);
                    itmTotalWithItmDiscnt = applyDisc(priceINTOqty, discount, discType);
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
        function recalcItemDiscount(el){
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
            recalcItemDiscount(this);
        });
        $('#tbodyID').on('change', '.itm_dis_type', function(e){
            recalcItemDiscount(this);
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
            // Add delivery charge
            var deliveryCharge = parseFloat($('#delivery_charge_input').val());
            if(isNaN(deliveryCharge)) deliveryCharge = 0;
            grandtotal = +(discountedTotal + deliveryCharge).toFixed(2);

            $("#grandtotalLbl").html(grandtotal);
            $("#creditvalue").html(grandtotal);
        }

        // invoice Discount
        $( "#invoiceDis" ).keyup(function() {
            grandtotalCalculation();
        });
        // Discount type change
        $('#invoiceDisType').change(function(){
            grandtotalCalculation();
        });
        // Delivery company toggle
        $('#delivery_company').change(function(){
            if($(this).val()){
                $('#delivery_charge_div').show();
            } else {
                $('#delivery_charge_div').hide();
                $('#delivery_charge_input').val('');
            }
            grandtotalCalculation();
        });
        // Delivery charge input
        $('#delivery_charge_input').keyup(function(){
            grandtotalCalculation();
        });

        //Add cheques        
        var ChqFormsubmittd=false;
        $("#chequeform").submit(function(e){
            e.preventDefault(); 
            ChqFormsubmittd=true;                        
        });

        //insert sales to DB with for loop
        var sale_ID = 0;
        $('#save').click(function(){
            if ($("#cheque").is(':checked')) {
                    $("#chequeform").submit();
            }
            var cashvalue=$("#cashvalue").val();
                if(cashvalue==''){cashvalue=0;}
            var creditvalue= parseFloat($('#creditvalue').text());
            var custID= $('#customer-id').val();
            var rows = $("#datatable").find("tr").length;
            var saleType = $('#sale_type').val();
            var customerPhone = $('#customer_phone').val().trim();

            // Validate phone
            if(!customerPhone){
                swal({type:'error',title:'Phone Required',text:'Please enter customer phone number.'});
                return;
            }
            // Validate online sale ID
            if(saleType == 'online' && !$('#online_sale_id').val().trim()){
                swal({type:'error',title:'Online ID Required',text:'Please enter the online sale reference ID.'});
                return;
            }
            // Credit sale without customer: show new customer popup
            if(saleType == 'credit' && (!custID || custID == '')){
                $('#nc_phone').val(customerPhone);
                $('#newCustomerModal').modal('show');
                return;
            }

            if(rows>1&&custID>0&&cashvalue>=0){
                // Check if card payments were used — if so, show card ref popup
                var pmPaymentsForRef = [];
                $('.pm-amount-input').each(function(){
                    var amt = parseFloat($(this).val());
                    if(!isNaN(amt) && amt > 0){
                        var pmid = $(this).data('pmid');
                        var pmname = $(this).closest('.form-group').find('label').first().text().replace(':','').trim();
                        pmPaymentsForRef.push({pm_id: pmid, amount: amt, name: pmname, card_ref: ''});
                    }
                });

                if(pmPaymentsForRef.length > 0){
                    // Show card reference popup
                    var html = '';
                    for(var r=0; r<pmPaymentsForRef.length; r++){
                        html += '<div class="form-group">';
                        html += '<label><strong>'+pmPaymentsForRef[r].name+'</strong> - LKR '+pmPaymentsForRef[r].amount.toFixed(2)+'</label>';
                        html += '<input type="text" class="form-control card-ref-input" data-idx="'+r+'" placeholder="Card machine reference/bill ID">';
                        html += '</div>';
                    }
                    $('#cardRefFields').html(html);
                    pendingCardRefs = pmPaymentsForRef;
                    $('#cardRefModal').modal({backdrop:'static',keyboard:false});
                    return;
                }

                if($("#cheque").is(':checked')) {
                    if(ChqFormsubmittd==true){
                        saveSale();
                        ChqFormsubmittd=false;
                    }
                    else{
                        alert("cheques fields not completed");
                    }
                }
                else{
                    saveSale();
                }
                ///
                function saveSale(){
                    var totalcheq=0;
                    if(moreChqs==true){
                        var chqv =$("input[name='amount[]']").map(function(){
                                var v= parseFloat($(this).val());
                                if(isNaN(v)||v=='') {
                                    v=0;
                                }
                                totalcheq+=v;
                                totalcheq.toFixed(2);
                            }).get();
                    }                    

                    var discountType = $('#invoiceDisType').val();
                    var deliveryCompanyId = $('#delivery_company').val();
                    var deliveryCharge = parseFloat($('#delivery_charge_input').val()) || 0;
                    var saleType = $('#sale_type').val();
                    var onlineSaleId = $('#online_sale_id').val();
                    var customerPhone = $('#customer_phone').val().trim();
                    lastSavedPhone = customerPhone;
                    $.ajax({
                        type: "Post",
                        url:"<?php echo base_url('Sales/addSalePOST'); ?>",
                        data: {cusID:cusID,grandtotal:grandtotal,subtotal:subtotal,invoiceDis:invoiceDis,discount_type:discountType,delivery_company_id:deliveryCompanyId,delivery_charge:deliveryCharge,store:store,date:date,sale_type:saleType,online_sale_id:onlineSaleId,customer_phone:customerPhone},
                        async: false,
                        dataType: "json",
                        success: function (saleID) {
                            sale_ID=saleID ;
                        console.log(" saleid:"+saleID+" cusid:"+cusID);
                        lastSavedSaleId = saleID;
                        // Save third-party payment method amounts with card refs
                        var pmPayments = [];
                        if(pendingCardRefs.length > 0){
                            for(var cr=0; cr<pendingCardRefs.length; cr++){
                                pmPayments.push({pm_id: pendingCardRefs[cr].pm_id, amount: pendingCardRefs[cr].amount, card_ref: pendingCardRefs[cr].card_ref});
                            }
                        } else {
                            $('.pm-amount-input').each(function(){
                                var amt = parseFloat($(this).val());
                                if(!isNaN(amt) && amt > 0){
                                    pmPayments.push({pm_id: $(this).data('pmid'), amount: amt, card_ref: ''});
                                }
                            });
                        }
                        if(pmPayments.length > 0 && saleID > 0){
                            $.ajax({
                                type: "Post",
                                url:"<?php echo base_url('Sales/saveSalePayments'); ?>",
                                data: {sale_id: saleID, payments: pmPayments},
                                async: false,
                                dataType: "json"
                            });
                        }
                        },
                        error: function (err) {
                            alert("sales error");
                        }
                    });
                    var salecrdit=0;
                    var pymnt4sale=0;
                    if(grandtotal<=cashvalue){
                        pymnt4sale=grandtotal;
                        salecrdit=0;
                    }
                    else if(grandtotal>cashvalue){
                        pymnt4sale=cashvalue;
                        salecrdit=creditvalue;
                    }
                    //Add cash and credit                 
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('CustomerPayment/customerCash'); ?>",
                            data: {saleID:sale_ID,cash:cashvalue,credit:creditvalue,date:date},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                            console.log("customer paymnt saved"); 
                            },
                            error: function (err) {
                                alert("customer payment error");
                            }
                        }); 
                        //add credit to customer balance
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('CustomerPayment/cusBalance'); ?>",
                            data: {cusID:cusID,bal:-creditvalue},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                            console.log("customer paymnt saved");
                            },
                            error: function (err) {
                                alert("customer payment error");
                            }
                        });
                        // var creditvalue= parseFloat($('#creditvalue').text());
                        //update credit limit of the customer 
                        // removerd this function request on client
//                        $.ajax({
//                            type: "Post",
//                            url:"<?php echo base_url('CustomerPayment/cusCreditUpdate'); ?>",
//                            data: {cusID:cusID,ncreditval:creditvalue},
//                            async: false,
//                            dataType: "json",
//                            success: function (res) {
//                            console.log("customer paymnt saved");
//                            },
//                            error: function (err) {
//                                alert("customer payment error");
//                            }
//                        });

                        //payment log by cash
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('CustomerPayment/customerCashLog'); ?>",
                            data: {saleID:sale_ID,cash:pymnt4sale,date:date},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                            console.log("customer paymnt log saved"); 
                            },
                            error: function (err) {
                                alert("customer payment log error");
                            }
                        });

                    //Add cheques /cheque log
                    var checked=0;
                    if ($("#cheque").is(':checked')) {checked=1}
                    var data = $('#chequeform').serialize() + "&saleID=" + sale_ID + "&checked=" + checked + "&date=" + date + "&cusID=" + cusID;
                    // Log the customer ID
    console.log("The cusID for cheque is: " + cusID);
                    // cusID:cusID
                    $.ajax({
                            type: 'post',
                            url: "<?php echo base_url('CustomerPayment/customerCheque'); ?>",
                            data: data,
                            async: false,
                            dataType:'json',  
                            success: function(response){
                                //alert(response);  
                               console.log("cheques inserted");                          
                            },
                            error: function() {
                                alert("There was an error. Try again please!");
                            }
                        });
                  

                    var itemid1,price,quantity,total,itmDis;      
                    var itemAdded = false;
                    for (var i = 1; i < rows; i++) { 
                        itemid1=$("#datatable").find("tr").eq(i).find("td").eq(1).text();
                    // iName=$("#datatable").find("tr").eq(i).find("td").eq(2).text();
                        price=$("#datatable").find("tr").eq(i).find("td").eq(3).text();
                        quantity=$("#datatable").find("tr").eq(i).find("td").eq(4).text();
                        total=$("#datatable").find("tr").eq(i).find("td").eq(5).text();
                        itmDis=$("#datatable").find("tr").eq(i).find("td").eq(6).find('input[type=text]').val();
                        var itmDisType=$("#datatable").find("tr").eq(i).find("td").eq(6).find('select.itm_dis_type').val() || 'percentage';

                    /* $.ajax({
                            type: "Post",
                            url:"<?php //echo base_url('Stocks/checkItemStock'); ?>",
                            data: {sale_ID:sale_ID,itemid1:itemid1,price:price,quantity:quantity,total:total,itmDis:itmDis},
                            async: false,
                            dataType: "json",
                            success: function () {
                            // itemAdded = 1;
                            },
                            error: function (err) {
                                alert("error");
                            }
                        }); */

                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('Sales/addSaleItemPOST'); ?>",
                            data: {sale_ID:sale_ID,itemid1:itemid1,price:price,quantity:quantity,total:total,itmDis:itmDis,itmDisType:itmDisType},
                            async: false,
                            dataType: "json",
                            success: function () {
                                itemAdded = true;
                            },
                            error: function (err) {
                                alert("error");
                                itemAdded = false;
                            }
                        });

                        // to change crrent qty -
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('CurQtyWithGrn/ChangeQtyToSale'); ?>",
                            data: {saleID:sale_ID,itmid:itemid1,qty:quantity,prc:price,ttl:total,storeid:store},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                                if(res==true){
                                    console.log("current qty changed as to Sale");
                                }
                                else{
                                // alert(res);
                                console.log(res);
                                }
                            },
                            error: function (err) {
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Error in Adjust grn stock qty for sale!'
                                });
                                console.log(err);
                            }
                        });

                        // stock -
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('Stocks/decreaseStock'); ?>",
                            data: {itmid:itemid1,qty:quantity,storeid:store},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                                console.log("stock decreased");
                            },
                            error: function (err) {
                                console.log("stock decrease note:", err);
                            }
                        });

                        // stocklog -
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('Stocks/stocklog'); ?>",
                            data: {itmid:itemid1,qty:quantity,saleID:sale_ID,storeid:store},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                                //alert("added to stock");
                            },
                            error: function (err) {
                                alert("error in grn stocklog");
                                console.log(err);
                            }
                        });
                    } 
                    // Process gift voucher redemptions
                    if (redeemedVouchers.length > 0 && sale_ID > 0) {
                        for (var rv = 0; rv < redeemedVouchers.length; rv++) {
                            var voucher = redeemedVouchers[rv];
                            // Determine actual redemption amount (capped to what's needed)
                            var redeemAmount = voucher.remaining_value;
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo base_url("GiftVoucher/redeem"); ?>',
                                data: { gc_id: voucher.gc_id, sale_id: sale_ID, amount: redeemAmount },
                                async: false,
                                dataType: 'json',
                                success: function(res) {
                                    console.log('Voucher redeemed: ' + voucher.card_number);
                                }
                            });
                        }
                        redeemedVouchers = [];
                        renderVoucherList();
                        recalcVoucherTotal();
                    }

                    //
                    $("#tbodyID").empty();
                    $("#subtotal").html("0.00");
                    $("#grandtotalLbl").html("0.00");
                    $("#credit_lmt_value").html("0.00");
                    $("#creditvalue").html("0.00");
                    $("#invoiceDis").val("");
                    $("#invoiceDisType").val("percentage");
                    $("#delivery_company").val("");
                    $("#delivery_charge_input").val("");
                    $("#delivery_charge_div").hide();
                    $("#customer_phone").val("");
                    $("#online_sale_id").val("");
                    $("#sale_type").val("cash");
                    applySaleType();
                    $(".pm-amount-input").val("");
                    $("#cashvalue").val("");
                    $("#amount").val("");
                    $("#bankname").val("");
                    $("#chequeno").val("");
                    $("#chequedate").val("");
                    $("#customer-id").val("");
                    // $("#customer-auto").html('<input class="form-control"  id="customer-auto" placeholder="Select" >');
                    $("#chequeDIV").remove();
                    pendingCardRefs = [];
                    if(itemAdded==true)
                    {  // get the print window
                        var horizontal = Math.floor(window.innerWidth/2);
                        var left=horizontal-200;
                        var rurl="<?= base_url('Sales/print_inv')?>/"+sale_ID;
                        console.log(rurl);
                        window.open(rurl, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=40,left="+left+",width=400,height=600");
                        // Show SMS option for online sales or if user wants
                        var st = $('#sale_type').val();
                        if(st == 'online' || lastSavedPhone){
                            $('#sms_phone_display').text(lastSavedPhone);
                            $('#smsModal').modal('show');
                            // Reload after modal closes
                            $('#smsModal').on('hidden.bs.modal', function(){ location.reload(); });
                        } else {
                            location.reload();
                        }
                    }
                }//end saveSale
  
                }
                
                else{
                    if(custID==''){
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'You must select customer!'
                        });
                    }
                    else if(rows<=1){
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Sale items are empty!'
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
        }); //end of save

  

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
            else if(DcmlDigts>2){
                var newvalue = parseFloat(num).toFixed(2);
                $(this).val(newvalue);                
            }
         });
         // fix two decimal points for dynamic
        $('#tbodyID,#cheaqueDetailsDiv').on('focusout', '.dcmlFixDynmc', function(e){            
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
        $('#tbodyID,#cheaqueDetailsDiv').on('keypress', '.validationDynmic', function(e){
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
            ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
            event.preventDefault();
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

    var availableCustomers = [
        <?php
         foreach ($customers as $customer)
        {
           $phone = isset($customer->cus_contact) ? $customer->cus_contact : '';
           $searchLabel = $customer->cus_name . ($phone ? ' - ' . $phone : '');
           echo '{ label: "'.addslashes($searchLabel).'", cusname:"'.addslashes($customer->cus_name).'", value:"'.$customer->cus_id.'" },';
        }
        ?>
    ];
    $("#customer-auto").autocomplete({
        source: availableCustomers,
        select: function(event, ui) {
                event.preventDefault();
                $("#customer-auto").val(ui.item.cusname || ui.item.label);
                $('#customer-id').val(ui.item.value);
                $("#show_cus").show("fast");
                $("#btnChange").show("fast");
                $("#customer-auto").parent().hide("fast");
                var slectedsup = ui.item.cusname || ui.item.label;
                $("#show_cus").text(slectedsup);
                load_cus_credit_and_dues();
                $(document).trigger('customerSelected');
                //credit_lmt_value
                //window.location="#"; //location to go when you select an item
            },
      
    });
    function load_cus_credit_and_dues(){
             var cusid=$("#customer-id").val();
             $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('Customers/getCusDetails'); ?>",
                            data: {id:cusid},
                            async: false,
                            dataType: "json",
                            success: function (data) {
                              
                              cus_balance = parseFloat(noNaN(data.cus_balance ));
                              bal_amount = parseFloat(noNaN(data.bal_amount));
                              customer_total_balance_w = cus_balance+(-bal_amount);
                              $('#credit_lmt_value').text(data.cus_creditlimit);
                              $('#customer_balance').text(customer_total_balance_w);
                             // validate_user_credits();
                              //alert(data.cus_creditlimit);
                            },
                            error: function (err) {
                                console.log("customer details load error:", err);
                            }
           });
    }
    var alert_show=false;
    function validate_user_credits(){
                              var credit_limit_val=Number($('#credit_lmt_value').html());
                              var customer_balance_val=Number($('#customer_balance').html());
                              var credit_val=Number($('#creditvalue').html());
                              
                              if(credit_limit_val>=(customer_balance_val+credit_val))               
                              {   document.getElementById("save").disabled = false;
                                  alert_show=false;
                              }else{
                                  if(alert_show==false){
                                  alert("CUSTOMER HAS REACHED THE CREDIT LIMIT");
                                  alert_show=true;
                              }
                                  document.getElementById("save").disabled = true;
                                  
                              }
    }
    
    
    $("#btnChange").click(function() {
            $("#btnChange").hide("fast");
            $("#show_cus").hide("fast");
            $("#customer-auto").val('');
            $("#customer-id").val('');
            $("#customer-auto").parent().show();            
    });

//load items
    var availableItems = [
        <?php
         foreach ($items as $item)
        {
           $sp = isset($item->itm_sellingprice) ? $item->itm_sellingprice : '0';
           echo '{ label: "'.addslashes($item->itm_name).' - '.$item->itm_code.' /stock =  '.$item->stock_qty.'", value:"'.$item->itm_id.'", code:"'.$item->itm_code.'", price:"'.$sp.'" },';
        }
        ?>
    ];
    // Build a lookup map by item code for barcode scanning
    var itemByCode = {};
    for(var ix=0; ix<availableItems.length; ix++){
        if(availableItems[ix].code){
            itemByCode[availableItems[ix].code.toUpperCase()] = availableItems[ix];
        }
    }

    $( "#saleitem-auto" ).autocomplete({
        source: availableItems,
        select: function(event, ui) {
                event.preventDefault();
                $("#saleitem-auto").val(ui.item.label);
                $('#saleitem-id').val(ui.item.value);
                ItemChangedEvent();
            },

    });

    // Barcode scanner handler — scanners type fast then press Enter
    var scanBuffer = '';
    var scanTimeout = null;
    $(document).on('keypress', function(e){
        // Only capture when no input is focused or the barcode field is focused
        var activeEl = document.activeElement;
        var isInputFocused = (activeEl && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'TEXTAREA' || activeEl.tagName === 'SELECT'));
        // If an input other than saleitem-auto is focused, let it type normally
        if(isInputFocused && activeEl.id !== 'saleitem-auto') return;

        if(e.which === 13){ // Enter key
            e.preventDefault();
            var code = scanBuffer.trim().toUpperCase();
            scanBuffer = '';
            if(code && itemByCode[code]){
                var matched = itemByCode[code];
                $('#saleitem-id').val(matched.value);
                $('#saleitem-auto').val(matched.label);
                $('#itemprice').val(matched.price);
                $('#itemquantity').val('1');
                // Auto-submit the add-item form
                $('#formid').submit();
            }
            return;
        }
        var char = String.fromCharCode(e.which);
        scanBuffer += char;
        clearTimeout(scanTimeout);
        scanTimeout = setTimeout(function(){ scanBuffer = ''; }, 300);
    });

    // Dedicated barcode input field handler
    $('#barcode-scan-input').on('keypress', function(e){
        if(e.which === 13){
            e.preventDefault();
            var code = $(this).val().trim().toUpperCase();
            $(this).val('');
            if(code && itemByCode[code]){
                var matched = itemByCode[code];
                $('#saleitem-id').val(matched.value);
                $('#saleitem-auto').val(matched.label);
                $('#itemprice').val(matched.price);
                $('#itemquantity').val('1');
                $('#formid').submit();
                $(this).focus();
            } else if(code) {
                swal({type:'warning', title:'Item not found', text:'No item with code: '+code});
                $(this).focus();
            }
        }
    });

    // Default CASH customer on page load
    <?php
    $cashCusId = 0;
    $cashCusName = '';
    if(isset($customers)){
        foreach($customers as $c){
            if(strtoupper($c->cus_name) === 'CASH'){
                $cashCusId = $c->cus_id;
                $cashCusName = $c->cus_name;
                break;
            }
        }
    }
    if($cashCusId > 0){
    ?>
    // Auto-select CASH customer
    $('#customer-id').val('<?php echo $cashCusId; ?>');
    $('#customer-auto').val('<?php echo addslashes($cashCusName); ?>');
    $('#show_cus').text('<?php echo addslashes($cashCusName); ?>');
    $('#show_cus').show();
    $('#btnChange').show();
    $('#customer-auto').parent().hide();
    load_cus_credit_and_dues();
    $(document).trigger('customerSelected');
    <?php } ?>
    
    //Payment calculate credit & display according to only cash + third-party methods
    function calCreditWithOutChq(){
        var cashvalue=parseFloat($("#cashvalue").val());
        if(isNaN(cashvalue)||cashvalue=='') {
        cashvalue = 0;
        }
        var totalPmPayments = 0;
        $('.pm-amount-input').each(function(){
            var v = parseFloat($(this).val());
            if(!isNaN(v) && v > 0) totalPmPayments += v;
        });
        var creditvalue=(grandtotal-cashvalue-totalPmPayments).toFixed(2);
        $("#creditvalue").html(creditvalue);
    }

    //Payment calculate credit & display according to cheq, cash & third-party methods
    function calculateCredit(){
        var cashvalue=parseFloat($("#cashvalue").val());
        if(isNaN(cashvalue)||cashvalue=='') {
        cashvalue = 0;
        }
        var totalcheq=0;
        var chqv =$("input[name='amount[]']").map(function(){
                    var v= parseFloat($(this).val());
                    if(isNaN(v)||v=='') {
                        v=0;
                    }
                    totalcheq+=v;
                    totalcheq.toFixed(2);
                }).get();
        // Sum third-party payment method amounts
        var totalPmPayments = 0;
        $('.pm-amount-input').each(function(){
            var v = parseFloat($(this).val());
            if(!isNaN(v) && v > 0) totalPmPayments += v;
        });
        var creditvalue=(grandtotal-cashvalue-totalcheq-totalPmPayments).toFixed(2);

        $("#creditvalue").html(creditvalue);
        validate_user_credits();
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
     $('#cheaqueDetailsDiv').on('keyup', '.dynmcChqAmount', function(e){
        calculateCredit();
    });

    // Adjust credit when third-party payment method amounts change
    $(".pm-amount-input").keyup(function(){
        calculateCredit();
    });

    // =========== GIFT VOUCHER REDEMPTION ===========
    var redeemedVouchers = []; // Array of {gc_id, card_number, amount, is_oneoff}

    $('#btn_validate_voucher').click(function() {
        var cardNum = $('#redeem_card_number').val().trim();
        if (!cardNum) { alert('Enter a card number'); return; }

        // Check if already added
        for (var v = 0; v < redeemedVouchers.length; v++) {
            if (redeemedVouchers[v].card_number == cardNum) {
                alert('This card is already added'); return;
            }
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("GiftVoucher/validateCard"); ?>',
            data: { card_number: cardNum },
            dataType: 'json',
            success: function(res) {
                if (res.valid) {
                    redeemedVouchers.push({
                        gc_id: res.gc_id,
                        card_number: res.card_number,
                        remaining_value: parseFloat(res.remaining_value),
                        amount: parseFloat(res.remaining_value), // Will be adjusted at save
                        category: res.category,
                        is_oneoff: res.is_oneoff
                    });
                    renderVoucherList();
                    recalcVoucherTotal();
                    calculateCredit();
                    $('#redeem_card_number').val('');
                } else {
                    Swal.fire('Invalid', res.msg, 'error');
                }
            }
        });
    });

    // Enter key on card number input
    $('#redeem_card_number').on('keypress', function(e) {
        if (e.which == 13) { e.preventDefault(); $('#btn_validate_voucher').click(); }
    });

    function renderVoucherList() {
        var html = '';
        for (var i = 0; i < redeemedVouchers.length; i++) {
            var v = redeemedVouchers[i];
            html += '<div class="d-flex justify-content-between align-items-center" style="background:#fff;padding:5px 8px;border-radius:3px;margin-bottom:4px;border:1px solid #ffe0b2;">';
            html += '<span><i class="fa fa-credit-card"></i> ' + v.card_number + ' <small class="text-muted">(' + v.category + ')</small></span>';
            html += '<span><strong>LKR ' + v.remaining_value.toFixed(2) + '</strong> ';
            html += '<a href="javascript:;" class="text-danger btn-remove-voucher" data-idx="' + i + '"><i class="fa fa-times"></i></a></span>';
            html += '</div>';
        }
        $('#voucher_list').html(html);
        $('#voucher_total_row').toggle(redeemedVouchers.length > 0);

        // Bind remove
        $('.btn-remove-voucher').click(function() {
            var idx = $(this).data('idx');
            redeemedVouchers.splice(idx, 1);
            renderVoucherList();
            recalcVoucherTotal();
            calculateCredit();
        });
    }

    function recalcVoucherTotal() {
        var total = 0;
        for (var i = 0; i < redeemedVouchers.length; i++) {
            total += redeemedVouchers[i].remaining_value;
        }
        $('#voucher_total').text(total.toFixed(2));
        return total;
    }

    function getVoucherTotal() {
        var total = 0;
        for (var i = 0; i < redeemedVouchers.length; i++) {
            total += redeemedVouchers[i].remaining_value;
        }
        return total;
    }

    // Override credit calculation to include voucher amounts
    var origCalcCredit = calculateCredit;
    calculateCredit = function() {
        var cashvalue = parseFloat($("#cashvalue").val());
        if (isNaN(cashvalue) || cashvalue == '') cashvalue = 0;

        var totalcheq = 0;
        $("input[name='amount[]']").each(function() {
            var v = parseFloat($(this).val());
            if (!isNaN(v)) totalcheq += v;
        });

        var totalPmPayments = 0;
        $('.pm-amount-input').each(function() {
            var v = parseFloat($(this).val());
            if (!isNaN(v) && v > 0) totalPmPayments += v;
        });

        var voucherTotal = getVoucherTotal();
        var creditvalue = (grandtotal - cashvalue - totalcheq - totalPmPayments - voucherTotal).toFixed(2);
        $("#creditvalue").html(creditvalue);
        validate_user_credits();
    };

    calCreditWithOutChq = function() {
        var cashvalue = parseFloat($("#cashvalue").val());
        if (isNaN(cashvalue) || cashvalue == '') cashvalue = 0;

        var totalPmPayments = 0;
        $('.pm-amount-input').each(function() {
            var v = parseFloat($(this).val());
            if (!isNaN(v) && v > 0) totalPmPayments += v;
        });

        var voucherTotal = getVoucherTotal();
        var creditvalue = (grandtotal - cashvalue - totalPmPayments - voucherTotal).toFixed(2);
        $("#creditvalue").html(creditvalue);
    };

    // =========== CARD REF CONFIRM HANDLER ===========
    $('#btnConfirmCardRefs').click(function(){
        var allFilled = true;
        $('.card-ref-input').each(function(){
            var idx = $(this).data('idx');
            var refVal = $(this).val().trim();
            if(!refVal){ allFilled = false; $(this).css('border-color','red'); }
            else { pendingCardRefs[idx].card_ref = refVal; $(this).css('border-color',''); }
        });
        if(!allFilled){
            swal({type:'warning',title:'Missing Reference',text:'Please enter all card machine reference IDs.'});
            return;
        }
        $('#cardRefModal').modal('hide');
        // Now proceed with save
        if($("#cheque").is(':checked')){
            if(ChqFormsubmittd==true){ saveSale(); ChqFormsubmittd=false; }
            else { alert("cheques fields not completed"); }
        } else {
            saveSale();
        }
    });

    // =========== NEW CUSTOMER SAVE (Credit Sales) ===========
    $('#btnSaveNewCustomer').click(function(){
        var ncName = $('#nc_name').val().trim();
        var ncPhone = $('#nc_phone').val().trim();
        if(!ncName){ alert('Customer name is required'); return; }
        if(!ncPhone){ alert('Customer phone is required'); return; }
        $.ajax({
            type:'POST',
            url:'<?php echo base_url("Customers/quickAddCustomer"); ?>',
            data: {name:ncName, contact:ncPhone, address:$('#nc_address').val(), creditlimit:$('#nc_creditlimit').val()||0},
            async:false,
            dataType:'json',
            success:function(newCusId){
                if(newCusId > 0){
                    $('#customer-id').val(newCusId);
                    $('#customer-auto').val(ncName);
                    $("#show_cus").text(ncName).show();
                    $("#btnChange").show();
                    $("#customer-auto").parent().hide();
                    $('#customer_phone').val(ncPhone);
                    $('#newCustomerModal').modal('hide');
                    load_cus_credit_and_dues();
                    swal({type:'success',title:'Customer Saved',text:'Now click Pay again to complete the sale.',showConfirmButton:true});
                } else {
                    alert('Error creating customer');
                }
            },
            error:function(){ alert('Error creating customer'); }
        });
    });

    // =========== SMS SEND HANDLER ===========
    $('#btnSendSms').click(function(){
        var btn = $(this);
        btn.prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
        $.ajax({
            type:'POST',
            url:'<?php echo base_url("Sales/sendSmsReceipt"); ?>',
            data:{sale_id: lastSavedSaleId, phone: lastSavedPhone},
            dataType:'json',
            success:function(res){
                btn.prop('disabled',false).html('<i class="fa fa-paper-plane"></i> Send SMS');
                if(res.status == 'success'){
                    swal({type:'success',title:'SMS Sent',showConfirmButton:false,timer:1500});
                } else {
                    swal({type:'error',title:'SMS Failed',text:res.message||'Could not send SMS'});
                }
                $('#smsModal').modal('hide');
            },
            error:function(){
                btn.prop('disabled',false).html('<i class="fa fa-paper-plane"></i> Send SMS');
                swal({type:'error',title:'SMS Error',text:'Network error sending SMS'});
                $('#smsModal').modal('hide');
            }
        });
    });

    // =========== VOUCHER ITEM SELLING (auto-popup for card number) ===========
    var pendingVoucherSales = []; // Cards to mark as sold after sale is saved

    // Hook into the item add flow to detect voucher items
    // Voucher items have item codes starting with 'GV' (matching voucher category barcodes)
    // We'll check on save if any items in cart are voucher items

  });
  </script>
