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
                                    <!-- Item 8: "Add New Sales" title removed. Item 9: Sale Location
                                         hidden for non-admin users (kept in DOM so the store still submits). -->
                                    <div class="col-12" style="<?php echo ($_SESSION['userrole']==1) ? '' : 'display:none;'; ?>">
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
                                <!-- Item 9: Date hidden for non-admin (defaults to today, still submits). -->
                                <div class="form-group row" style="<?php echo ($_SESSION['userrole']==1) ? '' : 'display:none;'; ?>">
                                    <label for="datepicker" class="col-4 col-form-label">Date<span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <input class="form-control datepic" value="<?php echo date('Y-m-d'); ?>" id="datepicker">
                                    </div>
                                </div>
                                <!-- Item 10: Online Delivery checkbox + fields moved below the credit section (see later). -->
                                </fieldset>
                                <hr>
                                <div style="background:#f8f9fa;border-radius:4px;padding:10px 5px;margin-bottom:10px;">
                                <!-- Item 11: Total quantity shown at the top of the bill, above Sub Total. -->
                                <div class="form-group row mb-1">
                                    <label class="col-5 col-form-label">Total Qty:</label>
                                    <div class="col-7 col-form-label text-right"><strong><span id="totalQtyLbl">0</span> pcs</strong></div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-5 col-form-label">Sub Total:</label>
                                    <div class="col-7 col-form-label text-right"><strong>LKR <span id="subtotal">0.00</span></strong></div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-3 col-form-label">Discount:</label>
                                    <div class="col-5"><input class="form-control DecimalFix" type="text" name="invoiceDis" placeholder="0" id="invoiceDis"/></div>
                                    <div class="col-4">
                                        <!-- Item 15: Flat is the default discount type. -->
                                        <select class="form-control" id="invoiceDisType">
                                            <option value="flat" selected>Flat (LKR)</option>
                                            <option value="percentage">%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-1" id="promo_summary_row" style="display:none;">
                                    <label class="col-5 col-form-label"><i class="fa fa-tags"></i> Promotions:</label>
                                    <div class="col-7 col-form-label text-right" style="color:#2e7d32;">- LKR <span id="promo_discount_display">0.00</span></div>
                                </div>
                                <div class="form-group row mb-1" id="promo_names_row" style="display:none;">
                                    <div class="col-12 text-right" style="font-size:11px;color:#2e7d32;"><span id="promo_applied_names"></span></div>
                                </div>
                                <div class="form-group row mb-1" id="loyalty_summary_row" style="display:none;">
                                    <label class="col-5 col-form-label"><i class="fa fa-star"></i> Points Redeemed:</label>
                                    <div class="col-7 col-form-label text-right" style="color:#e65100;">- LKR <span id="loyalty_redeem_display">0.00</span></div>
                                </div>
                                <div class="form-group row mb-1" id="delivery_charge_summary" style="display:none;">
                                    <label class="col-5 col-form-label"><i class="fa fa-truck"></i> Delivery:</label>
                                    <div class="col-7 col-form-label text-right">LKR <span id="delivery_charge_total_display">0.00</span></div>
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
                                <div id="dynamic_pm_container"></div>
                                <div class="form-group row" id="add_pm_row">
                                    <div class="col-12 text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddPaymentMethod"><i class="fa fa-plus"></i> Add Payment Method</button>
                                    </div>
                                </div>
                                <div class="form-group row" id="pm_select_row" style="display:none;">
                                    <div class="col-8">
                                        <select class="form-control form-control-sm" id="pm_select">
                                            <option value="">-- Select --</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-sm btn-success" id="btnConfirmAddPM"><i class="fa fa-check"></i></button>
                                        <button type="button" class="btn btn-sm btn-secondary" id="btnCancelAddPM"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-5 col-form-label">Credit Limit:</label>
                                    <div class="col-7 col-form-label text-right">LKR <span id="credit_lmt_value">0.00</span></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-5 col-form-label">Customer Balance:</label>
                                    <div class="col-7 col-form-label text-right">LKR <span id="customer_balance">0.00</span></div>
                                </div>
                                <div class="form-group row mb-0" style="background:#fff8e1;border-radius:4px;padding:6px 0;">
                                    <label class="col-5 col-form-label" style="cursor:pointer;" for="credit_order"><strong>Credit Order:</strong></label>
                                    <div class="col-7 col-form-label text-right">
                                        <input type="checkbox" id="credit_order" style="transform:scale(1.4);margin-right:8px;vertical-align:middle;">
                                        <small class="text-muted">tick for credit sale</small>
                                    </div>
                                </div>
                                <div class="form-group row" id="credit_outstanding_row">
                                    <label class="col-5 col-form-label">Credit:</label>
                                    <div class="col-7 col-form-label text-right">LKR <span id="creditvalue">0.00</span></div>
                                </div>
                                <div class="form-group row mb-0" id="change_return_row" style="display:none;background:#e8f5e9;border-radius:4px;padding:6px 0;">
                                    <label class="col-5 col-form-label" style="font-size:16px;color:#1b5e20;"><strong>Balance to Return:</strong></label>
                                    <div class="col-7 col-form-label text-right" style="font-size:16px;color:#1b5e20;"><strong>LKR <span id="change_return">0.00</span></strong></div>
                                </div>
                                <!-- Item 10: Online Delivery below the credit section -->
                                <div class="form-group row mb-0 m-t-5">
                                    <div class="col-12">
                                        <div class="checkbox checkbox-primary">
                                            <input id="online_delivery" type="checkbox">
                                            <label for="online_delivery">Online Delivery</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="online_delivery_div" style="display:none;">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Delivery Co:</label>
                                        <div class="col-8">
                                            <select class="form-control form-control-sm" id="delivery_company">
                                                <option value="">-- Select --</option>
                                                <?php if(isset($deliveryCompanies)): foreach($deliveryCompanies as $dc): ?>
                                                <option value="<?php echo $dc->dc_id; ?>"><?php echo $dc->dc_name; ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Delivery Charge:</label>
                                        <div class="col-8">
                                            <input class="form-control DecimalFix" type="text" placeholder="0.00" id="delivery_charge_input">
                                        </div>
                                    </div>
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
                                <!-- Loyalty Points Redemption -->
                                <div id="loyalty_block" style="background:#e8f5e9;border-radius:4px;padding:10px;margin-bottom:10px;display:none;">
                                    <strong><i class="fa fa-star"></i> Loyalty Points</strong>
                                    <div class="m-t-5" style="font-size:13px;">
                                        Available: <strong><span id="loyalty_available">0</span></strong> pts
                                        (worth LKR <span id="loyalty_available_value">0.00</span>)
                                    </div>
                                    <div class="input-group m-t-8">
                                        <input type="number" class="form-control" id="loyalty_redeem_points" placeholder="Points to redeem" min="0">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="button" id="btn_apply_loyalty"><i class="fa fa-check"></i> Redeem</button>
                                            <button class="btn btn-secondary" type="button" id="btn_clear_loyalty"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <small class="text-muted" id="loyalty_hint"></small>
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
                    <!-- Item 7: "Sell Gift Vouchers" section moved to the bottom of the page (see below). -->
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
                                            <th style="font-size: 12px;" class="dscnt-col">Dscnt%</th>
                                            <th style="font-size: 12px;">Act</th>
                                            <?php // Item 14: individual item discount hidden for non-admin users
                                            if($_SESSION['userrole'] != 1){ echo '<style>#datatable .dscnt-col, #datatable td.discount{display:none;}</style>'; } ?>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyID">                                          
                                    </tbody>
                                </table>
                        </div>
                        </div>
                    </div>
                    <!-- Item 7: Sell Gift Vouchers section moved here, to the bottom of the sales page. -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box clearfix" style="border-left:3px solid #e65100;">
                                <h5 class="header-title m-t-0 m-b-10"><i class="fa fa-gift" style="color:#e65100;"></i> Sell Gift Vouchers</h5>
                                <div class="row">
                                    <div class="col-5">
                                        <select class="form-control" id="gv_category">
                                            <option value="">Select Voucher Type</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <input type="number" class="form-control" id="gv_qty" min="1" value="1" placeholder="Qty">
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-warning" id="btn_add_vouchers"><i class="fa fa-plus"></i> Add Vouchers</button>
                                    </div>
                                </div>
                                <div id="gv_sell_rows" class="m-t-10"></div>
                                <div id="gv_sell_total_row" style="display:none;padding:8px;background:#fff3e0;border-radius:4px;margin-top:8px;">
                                    <strong>Voucher Total: LKR <span id="gv_sell_total">0.00</span></strong>
                                </div>
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
                <!-- Address and credit limit are no longer asked for on the customer
                     screens, so the quick-add box does not ask for them either. -->
                <input type="hidden" id="nc_address" value="">
                <input type="hidden" id="nc_creditlimit" value="0">
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

        // =========== ONLINE DELIVERY TOGGLE ===========
        $('#online_delivery').change(function(){
            if(this.checked){
                $('#online_delivery_div').show();
            } else {
                $('#online_delivery_div').hide();
                $('#delivery_company').val('');
                $('#delivery_charge_input').val('');
                $('#delivery_charge_summary').hide();
                $('#delivery_charge_total_display').text('0.00');
                grandtotalCalculation();
            }
        });

        // =========== DYNAMIC PAYMENT METHODS ===========
        var availablePaymentMethods = [
            <?php if(isset($paymentMethods) && count($paymentMethods) > 0): foreach($paymentMethods as $pm): ?>
            {id: <?php echo $pm->pm_id; ?>, name: '<?php echo addslashes($pm->pm_name); ?>'},
            <?php endforeach; endif; ?>
        ];
        var addedPmIds = [];

        $('#btnAddPaymentMethod').click(function(){
            var html = '<option value="">-- Select --</option>';
            for(var i=0; i<availablePaymentMethods.length; i++){
                if(addedPmIds.indexOf(availablePaymentMethods[i].id) === -1){
                    html += '<option value="'+availablePaymentMethods[i].id+'">'+availablePaymentMethods[i].name+'</option>';
                }
            }
            $('#pm_select').html(html);
            $('#pm_select_row').show();
            $('#add_pm_row').hide();
        });

        $('#btnCancelAddPM').click(function(){
            $('#pm_select_row').hide();
            $('#add_pm_row').show();
        });

        $('#btnConfirmAddPM').click(function(){
            var pmId = parseInt($('#pm_select').val());
            if(!pmId) return;
            var pmName = $('#pm_select option:selected').text();
            addedPmIds.push(pmId);
            var row = '<div class="form-group row pm-dynamic-row" data-pmid="'+pmId+'">'+
                '<label class="col-4 col-form-label">'+pmName+':</label>'+
                '<div class="col-6">'+
                '<input class="form-control DecimalFix pm-amount-input" type="text" placeholder="0.00" data-pmid="'+pmId+'">'+
                '</div>'+
                '<div class="col-2">'+
                '<button type="button" class="btn btn-sm btn-danger pm-remove-btn" style="margin-top:5px;"><i class="fa fa-times"></i></button>'+
                '</div>'+
                '</div>';
            $('#dynamic_pm_container').append(row);
            $('#pm_select_row').hide();
            $('#add_pm_row').show();

            // Item 12: balance carry-forward. When a new payment method is added, auto-fill it
            // with the amount still outstanding = grand total (after voucher) - cash - cheques
            // - amounts already entered against the other payment methods.
            var gt   = parseFloat($('#grandtotalLbl').text()) || 0;
            var cash = parseFloat($('#cashvalue').val()) || 0;
            var cheq = 0;
            $("input[name='amount[]']").each(function(){ var v=parseFloat($(this).val()); if(!isNaN(v)) cheq += v; });
            var pmSum = 0;
            $('#dynamic_pm_container .pm-amount-input').each(function(){ var v=parseFloat($(this).val()); if(!isNaN(v) && v>0) pmSum += v; });
            var remaining = +(gt - cash - cheq - pmSum).toFixed(2);
            if(remaining < 0){ remaining = 0; }
            $('#dynamic_pm_container .pm-amount-input').last().val(remaining.toFixed(2));

            if(typeof grandtotalCalculation === 'function'){ grandtotalCalculation(); } // payment-scope promos
            if(typeof calculateCredit === 'function'){ calculateCredit(); }
        });

        $(document).on('click', '.pm-remove-btn', function(){
            var pmId = parseInt($(this).closest('.pm-dynamic-row').data('pmid'));
            var idx = addedPmIds.indexOf(pmId);
            if(idx > -1) addedPmIds.splice(idx, 1);
            $(this).closest('.pm-dynamic-row').remove();
            calculateCredit();
            if(typeof grandtotalCalculation === 'function'){ grandtotalCalculation(); } // payment-scope promos
        });

        // Load active promotions once for auto-apply (Point 9)
        loadActivePromotions();

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
            // Load loyalty points for the selected customer (Point 8)
            if(typeof loadLoyaltyForCustomer === 'function'){ loadLoyaltyForCustomer(); }
            if(typeof grandtotalCalculation === 'function'){ grandtotalCalculation(); }
        });

        // =========== CARD REF + SMS FLOW GLOBALS ===========
        var pendingCardRefs = [];
        var cardRefsConfirmed = false;
        var lastSavedSaleId = 0;
        var lastSavedPhone = '';
        var lastSavedIsOnline = false;

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

            // Ensure itemid is current (barcode scan sets #saleitem-id directly)
            itemid = $('#saleitem-id').val();

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
                var checkNewItem=1;
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
                    break;
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
            var totalQty = 0;
            for(var i=1; i<=rows; i++){
                var rowTotal=$("#datatable").find("tr").eq(i).find("td").eq(5).text();
                subtotal= +(1*subtotal+1*rowTotal).toFixed(2);
                var rowQty = parseFloat($("#datatable").find("tr").eq(i).find("td").eq(4).text());
                if(!isNaN(rowQty)){ totalQty += rowQty; }
            }
            // Item 11: show total pieces sold at the top of the bill
            $("#totalQtyLbl").html(+(totalQty.toFixed(2)));
            // Add gift voucher selling total
            if(typeof getGvSellTotal === 'function'){
                subtotal = +(subtotal + getGvSellTotal()).toFixed(2);
            }
            $("#subtotal").html(subtotal);
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

            // ---- Promotions (Point 9): auto-detect & apply ----
            evaluatePromotions();
            var afterPromo = +(discountedTotal - promoDiscount).toFixed(2);
            if(afterPromo < 0) afterPromo = 0;

            // ---- Loyalty (Point 8): redemption ----
            recalcLoyaltyRedeem(afterPromo);
            var afterLoyalty = +(afterPromo - loyaltyRedeemAmount).toFixed(2);
            if(afterLoyalty < 0) afterLoyalty = 0;
            loyaltyEarnBase = afterLoyalty; // points earned on the net merchandise value

            // Add delivery charge
            var deliveryCharge = parseFloat($('#delivery_charge_input').val());
            if(isNaN(deliveryCharge)) deliveryCharge = 0;
            grandtotal = +(afterLoyalty + deliveryCharge).toFixed(2);

            // Deduct any applied gift vouchers so the Grand Total shows the real payable.
            // Guard redeemedVouchers: grandtotalCalculation() can run during page load
            // (customer auto-select) before that array is initialised further down.
            var voucherTotal = (typeof getVoucherTotal === 'function' && typeof redeemedVouchers !== 'undefined') ? getVoucherTotal() : 0;
            var netPayable = +(grandtotal - voucherTotal).toFixed(2);
            if(netPayable < 0) netPayable = 0;

            $("#grandtotalLbl").html(netPayable.toFixed(2));
            $("#creditvalue").html(netPayable.toFixed(2));
        }

        // ===================== PROMOTIONS (Point 9) =====================
        var activePromos = [];
        var promoDiscount = 0;
        var appliedPromosList = [];

        function loadActivePromotions(){
            $.ajax({
                type: 'GET',
                url: '<?php echo base_url("promotions/active"); ?>',
                dataType: 'json',
                success: function(res){ activePromos = res || []; },
                error: function(){ activePromos = []; }
            });
        }

        // read current cart lines as {id,total}
        function getCartLines(){
            var lines = [];
            var rws = $("#datatable").find("tr").length;
            for (var i = 1; i < rws; i++){
                var id = parseInt($("#datatable").find("tr").eq(i).find("td").eq(1).text());
                var tot = parseFloat($("#datatable").find("tr").eq(i).find("td").eq(5).text());
                if(!isNaN(id) && !isNaN(tot)){ lines.push({id:id, total:tot}); }
            }
            return lines;
        }

        function promoDiscountFor(promo, base){
            if(promo.promo_type == 'percentage'){
                return +(base * parseFloat(promo.promo_value) / 100).toFixed(2);
            }
            var d = parseFloat(promo.promo_value);
            return d > base ? +base.toFixed(2) : +d.toFixed(2);
        }

        function evaluatePromotions(){
            promoDiscount = 0;
            appliedPromosList = [];
            if(!activePromos || activePromos.length === 0){ updatePromoUI(); return; }

            var lines = getCartLines();
            var sub = subtotal;
            var applyMap = {}; // promo_id -> aggregated

            function record(promo, disc){
                if(disc <= 0) return;
                var key = promo.promo_id;
                if(!applyMap[key]){
                    applyMap[key] = {promo_id: promo.promo_id, name: promo.promo_name, scope: promo.promo_scope, discount: 0};
                }
                applyMap[key].discount = +(applyMap[key].discount + disc).toFixed(2);
                promoDiscount = +(promoDiscount + disc).toFixed(2);
            }

            // item + category scope: best matching promo per cart line
            for(var li=0; li<lines.length; li++){
                var line = lines[li];
                var bestDisc = 0, bestPromo = null;
                for(var pi=0; pi<activePromos.length; pi++){
                    var p = activePromos[pi];
                    if(p.promo_scope != 'item' && p.promo_scope != 'category') continue;
                    if(sub < parseFloat(p.promo_min_bill || 0)) continue;
                    var match = false;
                    if(p.promo_scope == 'item' && parseInt(p.promo_target_id) === line.id) match = true;
                    if(p.promo_scope == 'category' && p.item_ids && p.item_ids.indexOf(line.id) !== -1) match = true;
                    if(!match) continue;
                    var d = promoDiscountFor(p, line.total);
                    if(d > bestDisc){ bestDisc = d; bestPromo = p; }
                }
                if(bestPromo){ record(bestPromo, bestDisc); }
            }

            // bill scope: single best applicable
            var bBest = 0, bPromo = null;
            for(var bi=0; bi<activePromos.length; bi++){
                var bp = activePromos[bi];
                if(bp.promo_scope != 'bill') continue;
                if(sub < parseFloat(bp.promo_min_bill || 0)) continue;
                var bd = promoDiscountFor(bp, sub);
                if(bd > bBest){ bBest = bd; bPromo = bp; }
            }
            if(bPromo){ record(bPromo, bBest); }

            // payment scope: applies if the matching payment method has been added
            for(var yi=0; yi<activePromos.length; yi++){
                var yp = activePromos[yi];
                if(yp.promo_scope != 'payment') continue;
                if(sub < parseFloat(yp.promo_min_bill || 0)) continue;
                if(typeof addedPmIds === 'undefined' || addedPmIds.indexOf(parseInt(yp.promo_target_id)) === -1) continue;
                record(yp, promoDiscountFor(yp, sub));
            }

            for(var k in applyMap){ if(applyMap.hasOwnProperty(k)) appliedPromosList.push(applyMap[k]); }
            updatePromoUI();
        }

        function updatePromoUI(){
            if(promoDiscount > 0){
                $("#promo_discount_display").html(promoDiscount.toFixed(2));
                $("#promo_summary_row").show();
                var names = appliedPromosList.map(function(a){ return a.name; }).join(', ');
                $("#promo_applied_names").text(names);
                $("#promo_names_row").toggle(names.length > 0);
            } else {
                $("#promo_summary_row").hide();
                $("#promo_names_row").hide();
            }
        }

        // ===================== LOYALTY (Point 8) =====================
        var loyaltyInfo = {enabled:0, points:0, redeem_value:1, min_redeem:0, max_redeem_pct:100};
        var loyaltyRedeemPoints = 0;          // points the cashier asked to redeem
        var loyaltyRedeemPointsEffective = 0;  // points actually consumed after capping
        var loyaltyRedeemAmount = 0;           // currency value redeemed
        var loyaltyEarnBase = 0;

        function loadLoyaltyForCustomer(){
            var cid = $('#customer-id').val();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url("loyalty/customerInfo"); ?>',
                data: {cus_id: cid},
                dataType: 'json',
                async: false,
                success: function(res){
                    loyaltyInfo = res;
                    if(res.enabled == 1 && cid){
                        $("#loyalty_available").text((res.points||0));
                        $("#loyalty_available_value").text((res.points * res.redeem_value).toFixed(2));
                        $("#loyalty_block").show();
                    } else {
                        $("#loyalty_block").hide();
                    }
                    // reset any previous redemption when customer changes
                    clearLoyaltyRedeem();
                },
                error: function(){ $("#loyalty_block").hide(); }
            });
        }

        function clearLoyaltyRedeem(){
            loyaltyRedeemPoints = 0;
            loyaltyRedeemPointsEffective = 0;
            loyaltyRedeemAmount = 0;
            $('#loyalty_redeem_points').val('');
            $('#loyalty_hint').text('');
            $('#loyalty_summary_row').hide();
        }

        function recalcLoyaltyRedeem(billBase){
            if(loyaltyInfo.enabled != 1 || loyaltyRedeemPoints <= 0){
                loyaltyRedeemAmount = 0;
                loyaltyRedeemPointsEffective = 0;
                $('#loyalty_summary_row').hide();
                return;
            }
            var maxAmt = +(billBase * (loyaltyInfo.max_redeem_pct/100)).toFixed(2);
            var amt = +(loyaltyRedeemPoints * loyaltyInfo.redeem_value).toFixed(2);
            if(amt > maxAmt) amt = maxAmt;
            if(amt < 0) amt = 0;
            loyaltyRedeemAmount = amt;
            loyaltyRedeemPointsEffective = loyaltyInfo.redeem_value > 0 ? +(amt / loyaltyInfo.redeem_value).toFixed(2) : 0;
            $("#loyalty_redeem_display").html(amt.toFixed(2));
            $('#loyalty_summary_row').toggle(amt > 0);
        }

        $('#btn_apply_loyalty').click(function(){
            var pts = parseFloat($('#loyalty_redeem_points').val());
            if(isNaN(pts) || pts <= 0){ swal({type:'error',title:'Oops...',text:'Enter points to redeem.'}); return; }
            if(pts > (loyaltyInfo.points||0)){ swal({type:'error',title:'Oops...',text:'Customer does not have that many points.'}); return; }
            if((loyaltyInfo.points||0) < loyaltyInfo.min_redeem){
                swal({type:'error',title:'Oops...',text:'Minimum '+loyaltyInfo.min_redeem+' points required to redeem.'}); return;
            }
            loyaltyRedeemPoints = pts;
            $('#loyalty_hint').text('Redeeming '+pts+' pts = LKR '+(pts*loyaltyInfo.redeem_value).toFixed(2)+' (capped to '+loyaltyInfo.max_redeem_pct+'% of bill)');
            grandtotalCalculation();
        });

        $('#btn_clear_loyalty').click(function(){
            clearLoyaltyRedeem();
            grandtotalCalculation();
        });

        // invoice Discount
        $( "#invoiceDis" ).keyup(function() {
            grandtotalCalculation();
        });
        // Discount type change
        $('#invoiceDisType').change(function(){
            grandtotalCalculation();
        });
        // Delivery charge input - update summary display + grand total
        $('#delivery_charge_input').keyup(function(){
            var charge = parseFloat($(this).val());
            if(!isNaN(charge) && charge > 0){
                $('#delivery_charge_total_display').text(charge.toFixed(2));
                $('#delivery_charge_summary').show();
            } else {
                $('#delivery_charge_summary').hide();
            }
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
        // Guard against the same sale being saved twice. Every request below runs
        // synchronously, so a second click landing while the first is still going
        // used to create a duplicate bill. The flag blocks re-entry and the button
        // is disabled so the operator can see the sale is already going through.
        var saleSaveInProgress = false;
        function lockSaveButton(){
            saleSaveInProgress = true;
            $('#save').prop('disabled', true);
            $('#btnConfirmCardRefs').prop('disabled', true);
        }
        function unlockSaveButton(){
            saleSaveInProgress = false;
            $('#save').prop('disabled', false);
            $('#btnConfirmCardRefs').prop('disabled', false);
        }
        $('#save').click(function(){
            if(saleSaveInProgress){ return; }
            if ($("#cheque").is(':checked')) {
                    $("#chequeform").submit();
            }
            var cashvalue=$("#cashvalue").val();
                if(cashvalue==''){cashvalue=0;}
            var creditvalue= parseFloat($('#creditvalue').text());
            var custID= $('#customer-id').val();
            var rows = $("#datatable").find("tr").length;
            var customerPhone = $('#customer_phone').val().trim();
            // Credit Order flag: separates a normal (fully paid / cash) sale from a
            // credit sale where the unpaid balance is carried forward to the customer.
            var isCreditOrder = $('#credit_order').is(':checked');

            // Validate phone
            if(!customerPhone){
                swal({type:'error',title:'Phone Required',text:'Please enter customer phone number.'});
                return;
            }

            // Normal (non-credit) sale must be paid in full. A shortfall is only allowed
            // when the user explicitly marks it as a Credit Order.
            if(!isCreditOrder && creditvalue > 0.001){
                swal({type:'error',title:'Payment Incomplete',text:'Amount paid is less than the bill total. Tick "Credit Order" to carry the balance forward as customer credit, or collect the full amount.'});
                return;
            }

            // Validate voucher card numbers if any
            var hasGvRows = (typeof gvSellRows !== 'undefined' && gvSellRows.length > 0);
            if(hasGvRows && typeof validateGvSellRows === 'function' && !validateGvSellRows()){
                return;
            }

            if((rows>1 || hasGvRows)&&custID>0&&cashvalue>=0){
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

                if(pmPaymentsForRef.length > 0 && !cardRefsConfirmed){
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
                cardRefsConfirmed = false;

                if($("#cheque").is(':checked')) {
                    if(ChqFormsubmittd==true){
                        lockSaveButton();
                        saveSale();
                        ChqFormsubmittd=false;
                    }
                    else{
                        alert("cheques fields not completed");
                    }
                }
                else{
                    lockSaveButton();
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
                    var saleType = $('#online_delivery').is(':checked') ? 'online' : 'cash';
                    var onlineSaleId = '';
                    lastSavedIsOnline = (saleType == 'online');
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
                    // Only a Credit Order records an outstanding credit against the customer.
                    // A normal sale never creates/updates customer credit (any overpayment is
                    // simply cash change handed back), so the stored credit is forced to 0.
                    var creditToSave = isCreditOrder ? creditvalue : 0;
                    //Add cash and credit
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('CustomerPayment/customerCash'); ?>",
                            data: {saleID:sale_ID,cash:cashvalue,credit:creditToSave,date:date},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                            console.log("customer paymnt saved");
                            },
                            error: function (err) {
                                alert("customer payment error");
                            }
                        });
                        //add credit to customer balance — credit orders only
                        if(isCreditOrder && creditToSave != 0){
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('CustomerPayment/cusBalance'); ?>",
                            data: {cusID:cusID,bal:-creditToSave},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                            console.log("customer paymnt saved");
                            },
                            error: function (err) {
                                alert("customer payment error");
                            }
                        });
                        }
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

                    // ===== Loyalty accrual + redemption (Point 8) =====
                    if (sale_ID > 0 && loyaltyInfo.enabled == 1 && cusID) {
                        $.ajax({
                            type: "Post",
                            url: "<?php echo base_url('loyalty/processSale'); ?>",
                            data: {
                                sale_id: sale_ID,
                                cus_id: cusID,
                                grandtotal: loyaltyEarnBase,
                                redeem_points: loyaltyRedeemPointsEffective,
                                redeem_amount: loyaltyRedeemAmount
                            },
                            async: false,
                            dataType: "json",
                            success: function(res){ console.log('loyalty processed', res); },
                            error: function(err){ console.log('loyalty error', err); }
                        });
                    }

                    // ===== Record applied promotions (Point 9) =====
                    if (sale_ID > 0 && appliedPromosList.length > 0) {
                        $.ajax({
                            type: "Post",
                            url: "<?php echo base_url('promotions/recordSale'); ?>",
                            data: { sale_id: sale_ID, applied: appliedPromosList },
                            async: false,
                            dataType: "json",
                            success: function(res){ console.log('promotions recorded', res); },
                            error: function(err){ console.log('promotion record error', err); }
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

                    // Process voucher sales (mark cards as sold if voucher items were sold)
                    try {
                        if(sale_ID > 0){ processVoucherSales(sale_ID); }
                    } catch(e){ console.log('Voucher processing error:', e); }

                    //
                    $("#tbodyID").empty();
                    $("#subtotal").html("0.00");
                    $("#grandtotalLbl").html("0.00");
                    $("#credit_lmt_value").html("0.00");
                    $("#creditvalue").html("0.00");
                    $("#change_return").html("0.00");
                    $("#change_return_row").hide();
                    $("#credit_order").prop('checked', false);
                    $("#credit_outstanding_row").hide();
                    $("#invoiceDis").val("");
                    $("#invoiceDisType").val("flat");
                    $("#totalQtyLbl").html("0");
                    $("#delivery_company").val("");
                    $("#delivery_charge_input").val("");
                    $("#delivery_charge_div").hide();
                    $("#customer_phone").val("");
                    $("#online_delivery").prop('checked', false);
                    $("#online_delivery_div").hide();
                    $("#delivery_charge_summary").hide();
                    $("#dynamic_pm_container").empty();
                    addedPmIds = [];
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
                        if(lastSavedIsOnline || lastSavedPhone){
                            $('#sms_phone_display').text(lastSavedPhone);
                            $('#smsModal').modal('show');
                            // Reload after modal closes
                            $('#smsModal').on('hidden.bs.modal', function(){ location.reload(); });
                        } else {
                            location.reload();
                        }
                    }
                    else {
                        // Nothing was written - release the button so it can be retried.
                        unlockSaveButton();
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
    // Credit Limit popup removed as requested. Credit sales still work normally; we no
    // longer nag with an alert or block the Save button when a customer is over their
    // credit limit. (Kept as a no-op so all existing callers remain valid.)
    function validate_user_credits(){
        var saveBtn = document.getElementById("save");
        if(saveBtn){ saveBtn.disabled = false; }
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

    function refreshItemsByStore(storeId){
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>Sales/getItemsByStore',
            data: { store_id: storeId },
            dataType: 'json',
            success: function(data){
                availableItems = [];
                itemByCode = {};
                if(data && data.length > 0){
                    for(var i=0; i<data.length; i++){
                        var it = data[i];
                        var sp = it.itm_sellingprice || '0';
                        var sq = it.stock_qty || '0';
                        var obj = { label: it.itm_name + ' - ' + it.itm_code + ' /stock =  ' + sq, value: it.itm_id, code: it.itm_code, price: sp };
                        availableItems.push(obj);
                        if(it.itm_code){
                            itemByCode[it.itm_code.toUpperCase()] = obj;
                        }
                    }
                }
                $("#saleitem-auto").autocomplete("option", "source", availableItems);
            }
        });
    }

    $('#storeLoctn').change(function(){
        refreshItemsByStore($(this).val());
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

    // Default Walk-in Customer (Customer ID 1) on page load
    <?php
    $cashCusId = 0;
    $cashCusName = '';
    if(isset($customers)){
        // Prefer the Walk-in Customer (id 1)
        foreach($customers as $c){
            if(intval($c->cus_id) === 1){
                $cashCusId = $c->cus_id;
                $cashCusName = $c->cus_name;
                break;
            }
        }
        // Fall back to a WALK-IN / CASH named customer if id 1 is not present
        if($cashCusId == 0){
            foreach($customers as $c){
                $n = strtoupper($c->cus_name);
                if(strpos($n, 'WALK') !== false || $n === 'CASH'){
                    $cashCusId = $c->cus_id;
                    $cashCusName = $c->cus_name;
                    break;
                }
            }
        }
    }
    // Guarantee a default of Customer ID 1 as requested
    if($cashCusId == 0){ $cashCusId = 1; $cashCusName = 'Walk-in Customer'; }
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

    // Adjust credit when third-party payment method amounts change (event delegation for dynamic elements)
    $(document).on('keyup', '.pm-amount-input', function(){
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
                    if(typeof grandtotalCalculation === 'function'){ grandtotalCalculation(); }
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
            if(typeof grandtotalCalculation === 'function'){ grandtotalCalculation(); }
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
        if(typeof redeemedVouchers === 'undefined' || !redeemedVouchers) return 0;
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
        updateChangeReturn();
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
        updateChangeReturn();
    };

    // Two separate business processes, driven by the "Credit Order" checkbox:
    //  - Credit Order OFF (normal sale): if the customer pays MORE than the total,
    //    show the Balance to Return (change). Customer credit is NOT touched.
    //  - Credit Order ON (credit sale): show the outstanding Credit that will be
    //    carried forward to the customer's balance. No return-balance display.
    function updateChangeReturn(){
        var credit = parseFloat($('#creditvalue').text());
        if (isNaN(credit)) credit = 0;
        var isCreditOrder = $('#credit_order').is(':checked');
        if (isCreditOrder) {
            // Credit sale: show outstanding credit, never the return balance.
            $('#credit_outstanding_row').show();
            $('#change_return').text('0.00');
            $('#change_return_row').hide();
        } else {
            // Normal sale: outstanding credit is not applicable; show change when overpaid.
            $('#credit_outstanding_row').hide();
            if (credit < -0.001) {
                $('#change_return').text(Math.abs(credit).toFixed(2));
                $('#change_return_row').show();
            } else {
                $('#change_return').text('0.00');
                $('#change_return_row').hide();
            }
        }
    }
    // Re-evaluate the display whenever the Credit Order checkbox is toggled.
    $(document).on('change', '#credit_order', function(){
        if (typeof calculateCredit === 'function') { calculateCredit(); }
        else { updateChangeReturn(); }
    });

    // =========== CARD REF CONFIRM HANDLER ===========
    $('#btnConfirmCardRefs').click(function(){
        // The save handler disables #save the moment a sale starts going through.
        // Checking it here stops a second click on Confirm creating a second bill.
        if($('#save').prop('disabled')){ return; }
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
        cardRefsConfirmed = true;
        $('#save').click();
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

    // =========== GIFT VOUCHER SELLING ===========
    var GV_BASE = '<?php echo base_url(); ?>';
    var gvSellRows = []; // {vcat_id, vcat_name, value, card_number, validated, gc_id}

    // Load voucher categories into dropdown
    $.ajax({
        type: 'GET',
        url: GV_BASE + 'GiftVoucher/getActiveCategories',
        dataType: 'json',
        success: function(cats){
            if(cats && cats.length > 0){
                for(var i=0; i<cats.length; i++){
                    $('#gv_category').append('<option value="'+cats[i].vcat_id+'" data-name="'+cats[i].vcat_name+'" data-value="'+cats[i].vcat_value+'">Gift Voucher - '+cats[i].vcat_name+' (LKR '+parseFloat(cats[i].vcat_value).toFixed(2)+')</option>');
                }
            }
        }
    });

    $('#btn_add_vouchers').click(function(){
        var vcatId = $('#gv_category').val();
        if(!vcatId){ swal({type:'error',title:'Error',text:'Please select a voucher type'}); return; }
        var qty = parseInt($('#gv_qty').val()) || 0;
        if(qty < 1){ swal({type:'error',title:'Error',text:'Enter at least 1'}); return; }
        var opt = $('#gv_category option:selected');
        var vcatName = opt.data('name');
        var vcatValue = parseFloat(opt.data('value'));
        for(var i=0; i<qty; i++){
            gvSellRows.push({
                vcat_id: vcatId,
                vcat_name: vcatName,
                value: vcatValue,
                card_number: '',
                validated: false,
                gc_id: 0
            });
        }
        renderGvSellRows();
        $('#gv_qty').val('1');
    });

    function renderGvSellRows(){
        if(gvSellRows.length === 0){
            $('#gv_sell_rows').html('');
            $('#gv_sell_total_row').hide();
            gvRecalcTotal();
            return;
        }
        var html = '<table class="table table-sm table-bordered" style="margin-bottom:0;"><thead><tr style="background:#fff3e0;"><th style="width:30px;">#</th><th>Voucher</th><th style="text-align:right;width:90px;">Value</th><th style="width:200px;">Card Number</th><th style="width:50px;">Status</th><th style="width:40px;"></th></tr></thead><tbody>';
        for(var i=0; i<gvSellRows.length; i++){
            var r = gvSellRows[i];
            var statusIcon = r.validated ? '<i class="fa fa-check-circle" style="color:green;font-size:18px;"></i>' : '<i class="fa fa-exclamation-circle" style="color:#e65100;font-size:18px;"></i>';
            var borderStyle = r.validated ? 'border:2px solid green;' : '';
            html += '<tr>';
            html += '<td>'+(i+1)+'</td>';
            html += '<td>Gift Voucher - '+r.vcat_name+'</td>';
            html += '<td style="text-align:right;">'+r.value.toFixed(2)+'</td>';
            html += '<td><input type="text" class="form-control form-control-sm gv-card-input" data-idx="'+i+'" value="'+r.card_number+'" placeholder="Enter card number" style="'+borderStyle+'"></td>';
            html += '<td style="text-align:center;">'+statusIcon+'</td>';
            html += '<td><a href="javascript:;" class="btn btn-sm btn-danger gv-remove-row" data-idx="'+i+'"><i class="fa fa-times"></i></a></td>';
            html += '</tr>';
        }
        html += '</tbody></table>';
        $('#gv_sell_rows').html(html);
        $('#gv_sell_total_row').show();
        gvRecalcTotal();

        // Bind card number validation on blur
        $('.gv-card-input').off('blur').on('blur', function(){
            var idx = $(this).data('idx');
            var cn = $(this).val().trim();
            if(!cn){ gvSellRows[idx].validated = false; gvSellRows[idx].card_number = ''; renderGvSellRows(); return; }

            // Check duplicate within this transaction
            for(var d=0; d<gvSellRows.length; d++){
                if(d !== idx && gvSellRows[d].card_number === cn){
                    swal({type:'error',title:'Duplicate',text:'Card number '+cn+' is already entered in row '+(d+1)});
                    gvSellRows[idx].validated = false;
                    gvSellRows[idx].card_number = '';
                    renderGvSellRows();
                    return;
                }
            }

            var $inp = $(this);
            $.ajax({
                type: 'POST',
                url: GV_BASE + 'GiftVoucher/validateCardForSale',
                data: { card_number: cn },
                dataType: 'json',
                success: function(res){
                    if(res.valid){
                        gvSellRows[idx].card_number = cn;
                        gvSellRows[idx].validated = true;
                        gvSellRows[idx].gc_id = res.gc_id;
                        renderGvSellRows();
                    } else {
                        swal({type:'error',title:'Invalid Card',text:res.msg});
                        gvSellRows[idx].validated = false;
                        gvSellRows[idx].card_number = '';
                        renderGvSellRows();
                    }
                }
            });
        });

        // Bind enter key to move to next input
        $('.gv-card-input').off('keypress').on('keypress', function(e){
            if(e.which === 13){
                e.preventDefault();
                $(this).blur();
                var nextIdx = $(this).data('idx') + 1;
                setTimeout(function(){
                    $('.gv-card-input[data-idx="'+nextIdx+'"]').focus();
                }, 300);
            }
        });

        // Bind remove
        $('.gv-remove-row').off('click').on('click', function(){
            var idx = $(this).data('idx');
            gvSellRows.splice(idx, 1);
            renderGvSellRows();
        });
    }

    function gvRecalcTotal(){
        var total = 0;
        for(var i=0; i<gvSellRows.length; i++){
            total += gvSellRows[i].value;
        }
        $('#gv_sell_total').text(total.toFixed(2));
        calSubtotal();
    }

    // Get voucher selling total for grand total calculation
    function getGvSellTotal(){
        var total = 0;
        if(typeof gvSellRows === 'undefined' || !gvSellRows) return 0;
        for(var i=0; i<gvSellRows.length; i++){
            total += gvSellRows[i].value;
        }
        return total;
    }

    // Validate all voucher card numbers before sale save
    function validateGvSellRows(){
        if(gvSellRows.length === 0) return true;
        for(var i=0; i<gvSellRows.length; i++){
            if(!gvSellRows[i].card_number || !gvSellRows[i].validated){
                swal({type:'error',title:'Voucher Card Required',text:'Please enter and validate the card number for voucher row '+(i+1)});
                return false;
            }
        }
        return true;
    }

    // Process voucher card sales after sale is saved
    function processVoucherSales(saleId){
        if(!saleId || saleId <= 0 || typeof gvSellRows === 'undefined' || !gvSellRows || gvSellRows.length === 0) return;
        var cardNumbers = [];
        for(var i=0; i<gvSellRows.length; i++){
            if(gvSellRows[i].validated && gvSellRows[i].card_number){
                cardNumbers.push(gvSellRows[i].card_number);
            }
        }
        if(cardNumbers.length === 0){ gvSellRows = []; return; }
        try {
            $.ajax({
                type: 'POST',
                url: GV_BASE + 'GiftVoucher/markCardsSoldBatch',
                data: { sale_id: saleId, card_numbers: JSON.stringify(cardNumbers) },
                async: false,
                dataType: 'json',
                success: function(res){
                    if(res && res.count > 0){
                        console.log('Voucher cards sold: ' + res.count);
                    }
                },
                error: function(xhr){
                    console.log('Voucher batch mark error:', xhr.statusText);
                }
            });
        } catch(e){ console.log('Voucher AJAX error:', e); }
        gvSellRows = [];
        renderGvSellRows();
    }

  });
  </script>
