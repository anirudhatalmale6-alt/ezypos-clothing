<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <!-- Left Panel: Order Details -->
        <div class="col-lg-4 col-md-5 col-sm-12">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-scissors"></i> <?php echo (isset($editPsId) && $editPsId) ? 'Edit Tailoring Order' : 'Production Sale (Tailoring Order)'; ?></h4>
                <fieldset>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Order Code</label>
                        <div class="col-7">
                            <input class="form-control" id="ps_code" value="<?php echo $nextCode; ?>" readonly style="background:#f5f5f5;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Store<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <?php
                                // The branch this login belongs to. Both store boxes
                                // start on it. Every branch is still in the list - an
                                // order taken at one shop can be collected at another.
                                $psMyStore = isset($userStoreId) ? intval($userStoreId) : 0;
                            ?>
                            <select class="form-control" id="ps_store">
                                <?php if($this->session->userdata('userrole')==1 || !$psMyStore): ?>
                                <option value="0">Select Store</option>
                                <?php endif; ?>
                                <?php if($storeLoc): foreach($storeLoc as $s): ?>
                                <option value="<?php echo $s->store_id; ?>"<?php echo ($psMyStore && $psMyStore == $s->store_id) ? ' selected' : ''; ?>><?php echo $s->store_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Customer<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <div class="input-group">
                                <input class="form-control" id="ps_customer_search" placeholder="Search customer..." autocomplete="off">
                                <div class="input-group-append">
                                    <!-- A new face at the counter should not send the
                                         staff off to the Customers page and back. Same
                                         quick-add box as the Sales window, and the new
                                         customer goes straight on this order. -->
                                    <button id="ps_btn_add_customer" type="button" class="btn btn-success"
                                            title="Add a new customer">
                                        <i class="fa fa-user-plus"></i> New
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" id="ps_customer_id" value="">
                            <span id="ps_cus_name" class="text-primary" style="font-weight:bold;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Order Date<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <input type="date" class="form-control" id="ps_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Delivery Date<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <input type="date" class="form-control" id="ps_delivery_date" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Pickup Store</label>
                        <div class="col-7">
                            <select class="form-control" id="ps_pickup_store">
                                <option value="">Same as Order Store</option>
                                <?php if($storeLoc): foreach($storeLoc as $s): ?>
                                <option value="<?php echo $s->store_id; ?>"<?php echo ($psMyStore && $psMyStore == $s->store_id) ? ' selected' : ''; ?>><?php echo $s->store_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                            <small class="text-muted">Change this only if the customer is collecting from another branch.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Estimated Cost</label>
                        <div class="col-7">
                            <input type="number" class="form-control" id="ps_tailoring_charge" value="0.00" step="0.01" placeholder="Estimated total (optional)">
                            <small class="text-muted">Can be updated after tailor confirms actual cost</small>
                        </div>
                    </div>
                    <div class="form-group row" id="advance_payment_row" <?php echo (isset($editPsId) && $editPsId) ? 'style="display:none;"' : ''; ?>>
                        <label class="col-5 col-form-label">Advance Payment</label>
                        <div class="col-7">
                            <input type="number" class="form-control" id="ps_advance_payment" value="0.00" step="0.01" placeholder="Advance amount (optional)">
                        </div>
                    </div>
                    <div class="form-group row" id="advance_pm_row" style="display:none;">
                        <label class="col-5 col-form-label">Payment Method</label>
                        <div class="col-7">
                            <select class="form-control" id="ps_advance_method">
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Credit Card">Credit Card</option>
                                <?php if(isset($paymentMethods)){ foreach($paymentMethods as $pm){ ?>
                                <option value="<?php echo $pm->pm_name; ?>"><?php echo $pm->pm_name; ?></option>
                                <?php }} ?>
                                <!-- The advance on a tailoring order can be paid with a gift
                                     card, the same as on the Sales window and on Manage
                                     Payments. The card is checked before the order is
                                     created and spent for the amount used. -->
                                <option value="Gift Voucher">Gift Voucher</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="advance_cardref_row" style="display:none;">
                        <label class="col-5 col-form-label">Card No / Reference</label>
                        <div class="col-7">
                            <input type="text" class="form-control" id="ps_advance_cardref" placeholder="Enter card number / reference no">
                            <small id="ps_advance_voucher_note" class="text-muted"></small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Notes</label>
                        <div class="col-7">
                            <textarea class="form-control" id="ps_notes" rows="2" placeholder="Measurements, instructions..."></textarea>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <!-- Cost Summary -->
                <div id="ps_cost_summary">
                    <div class="form-group row mb-1">
                        <label class="col-7 col-form-label"><strong>Material Cost:</strong></label>
                        <label class="col-form-label">LKR <span id="ps_material_cost_lbl">0.00</span></label>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-7 col-form-label"><strong>Tailoring Charge:</strong></label>
                        <label class="col-form-label">LKR <span id="ps_tailoring_cost_lbl">0.00</span></label>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-7 col-form-label"><strong>Service Charges:</strong></label>
                        <label class="col-form-label">LKR <span id="ps_service_cost_lbl">0.00</span></label>
                    </div>
                    <div class="form-group row mb-1" id="ps_discount_row">
                        <label class="col-5 col-form-label"><strong>Discount:</strong></label>
                        <div class="col-7">
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" id="ps_discount_value"
                                       value="0" step="0.01" min="0">
                                <select class="form-control" id="ps_discount_type" style="max-width:96px;">
                                    <option value="flat">Flat (LKR)</option>
                                    <option value="percentage">%</option>
                                </select>
                            </div>
                            <small class="text-muted">Taken off: LKR <span id="ps_discount_lbl">0.00</span></small>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row mb-1">
                        <label class="col-7 col-form-label"><strong style="font-size:16px;">Grand Total:</strong></label>
                        <label class="col-form-label"><strong style="font-size:16px;">LKR <span id="ps_total_lbl">0.00</span></strong></label>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-7 col-form-label">Paid:</label>
                        <label class="col-form-label text-success">LKR <span id="ps_paid_lbl">0.00</span></label>
                    </div>
                    <div class="form-group row mb-1">
                        <label class="col-7 col-form-label"><strong>Balance Due:</strong></label>
                        <label class="col-form-label text-danger"><strong>LKR <span id="ps_balance_lbl">0.00</span></strong></label>
                    </div>
                </div>
                <hr>
                <!-- Status -->
                <div class="form-group row" id="ps_status_section" style="display:none;">
                    <label class="col-5 col-form-label"><strong>Status:</strong></label>
                    <div class="col-7">
                        <span id="ps_status_badge" class="badge badge-info" style="font-size:14px;">Pending</span>
                    </div>
                </div>
                <!-- Tailor assignment (assigned LATER, during processing) -->
                <div id="ps_tailor_section" style="display:none;">
                    <div class="form-group row">
                        <label class="col-5 col-form-label"><strong>Tailor:</strong></label>
                        <div class="col-7">
                            <div class="input-group">
                                <select class="form-control" id="ps_tailor">
                                    <option value="">-- Select Tailor --</option>
                                    <?php if(!empty($tailors)){ foreach($tailors as $t){ ?>
                                    <option value="<?php echo $t->sup_id; ?>"><?php echo htmlspecialchars($t->sup_name); ?></option>
                                    <?php }} ?>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" id="btn_assign_tailor" type="button"><i class="fa fa-check"></i></button>
                                </div>
                            </div>
                            <?php if(empty($tailors)): ?>
                            <small class="text-muted">No tailors yet. Mark a Supplier as "Is Tailor" first.</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div id="ps_create_btn">
                    <button class="btn btn-success btn-block" id="btn_create_ps" <?php echo (isset($editPsId) && $editPsId) ? 'style="display:none;"' : ''; ?>>
                        <i class="fa fa-plus"></i> Create Tailoring Order
                    </button>
                    <button class="btn btn-primary btn-block" id="btn_update_ps" style="display:none;">
                        <i class="fa fa-save"></i> Update Order
                    </button>
                </div>
                <div id="ps_status_buttons" style="display:none;">
                    <div class="form-group row">
                        <div class="col-4">
                            <button class="btn btn-warning btn-block btn-sm" id="btn_ps_cutting">
                                <i class="fa fa-scissors"></i> Cutting
                            </button>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-info btn-block btn-sm" id="btn_ps_stitching">
                                <i class="fa fa-spinner"></i> Stitching
                            </button>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-primary btn-block btn-sm" id="btn_ps_ready">
                                <i class="fa fa-check"></i> Ready
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <button class="btn btn-success btn-block btn-sm" id="btn_ps_delivered">
                                <i class="fa fa-truck"></i> Delivered
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-sm btn-block" style="background:#e0e0e0;" id="btn_ps_payment">
                                <i class="fa fa-money"></i> Add Payment
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <a class="btn btn-outline-secondary btn-block btn-sm" id="btn_print_estimate" target="_blank" href="#">
                                <i class="fa fa-print"></i> Estimate Bill
                            </a>
                        </div>
                        <div class="col-6">
                            <a class="btn btn-outline-dark btn-block btn-sm" id="btn_print_final" target="_blank" href="#">
                                <i class="fa fa-print"></i> Final Bill
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Items & Services -->
        <div class="col-lg-8 col-md-7 col-sm-12">
            <!-- Add Material/Item Section -->
            <div class="card-box clearfix" id="ps_items_section" style="display:none;">
                <h5 class="header-title m-t-0 m-b-15"><i class="fa fa-cubes"></i> Materials & Items</h5>
                <div class="row">
                    <div class="col-md-4">
                        <label>Item<span class="text-danger">*</span></label>
                        <input class="form-control" id="ps_item_search" placeholder="Search item..." autocomplete="off">
                        <input type="hidden" id="ps_item_id" value="">
                        <small id="ps_item_stock_info" class="text-muted"></small>
                    </div>
                    <div class="col-md-2">
                        <label>Qty<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="ps_item_qty" placeholder="Qty" step="0.01" value="1">
                    </div>
                    <div class="col-md-2">
                        <label>Price</label>
                        <input type="number" class="form-control" id="ps_item_price" placeholder="0.00" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label>Total</label>
                        <input type="text" class="form-control" id="ps_item_total" readonly style="background:#f5f5f5;">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button class="btn btn-success btn-block" id="btn_add_ps_item">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive m-t-15">
                    <table class="table table-bordered table-sm" id="ps_items_table">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>UOM</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Act</th>
                            </tr>
                        </thead>
                        <tbody id="ps_items_body"></tbody>
                    </table>
                </div>
            </div>

            <!-- Additional Services Section -->
            <div class="card-box clearfix" id="ps_services_section" style="display:none;">
                <h5 class="header-title m-t-0 m-b-15"><i class="fa fa-wrench"></i> Additional Services</h5>
                <div class="row">
                    <div class="col-md-5">
                        <label>Description<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ps_svc_desc" placeholder="e.g. Button holes, Embroidery">
                    </div>
                    <div class="col-md-3">
                        <label>Charge<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="ps_svc_charge" placeholder="0.00" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button class="btn btn-success btn-block" id="btn_add_ps_svc">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </div>
                <div class="table-responsive m-t-15">
                    <table class="table table-bordered table-sm" id="ps_services_table">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Charge</th>
                                <th>Act</th>
                            </tr>
                        </thead>
                        <tbody id="ps_services_body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Payment Modal -->
<div class="modal" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Payment Method</label>
                    <select class="form-control" id="ps_payment_method">
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Credit Card">Credit Card</option>
                        <?php if(isset($paymentMethods)){ foreach($paymentMethods as $pm){ ?>
                        <option value="<?php echo $pm->pm_name; ?>"><?php echo $pm->pm_name; ?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group" id="ps_card_ref_group" style="display:none;">
                    <label>Card Number / Reference No</label>
                    <input type="text" class="form-control" id="ps_card_ref" placeholder="Enter card number / reference no">
                </div>
                <div class="form-group">
                    <label>Payment Amount</label>
                    <input type="number" class="form-control" id="ps_payment_amount" step="0.01" placeholder="0.00">
                </div>
                <div id="ps_balance_info" style="background:#fff3e0;padding:8px;border-radius:4px;margin-top:10px;">
                    <strong>Current Balance: LKR <span id="ps_modal_balance">0.00</span></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-success" id="btn_confirm_payment">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- New Customer popup - the same one the Sales window uses, same two fields
     and the same Customers/quickAddCustomer endpoint, so a customer added
     from either screen is the same record. -->
<div class="modal" id="psNewCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-user-plus"></i> Add New Customer</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">The customer is saved and put on this order straight away.</p>
                <div class="form-group">
                    <label>Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="ps_nc_name" placeholder="Customer name">
                </div>
                <div class="form-group">
                    <label>Phone<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="ps_nc_phone" placeholder="Phone number">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="ps_btn_save_customer"><i class="fa fa-save"></i> Save Customer</button>
            </div>
        </div>
    </div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
var currentPsId = <?php echo isset($editPsId) && $editPsId ? $editPsId : 'null'; ?>;
var isEditMode = <?php echo isset($editPsId) && $editPsId ? 'true' : 'false'; ?>;
var currentPsStoreId = 0;

$(document).ready(function() {
    // Customer autocomplete
    var psCustomers = [
        <?php if($customers): foreach($customers as $c): ?>
        { label: "<?php echo addslashes($c->cus_name); ?><?php echo isset($c->cus_contact) && $c->cus_contact ? ' - '.addslashes($c->cus_contact) : ''; ?>", cusname: "<?php echo addslashes($c->cus_name); ?>", value: "<?php echo $c->cus_id; ?>" },
        <?php endforeach; endif; ?>
    ];
    $('#ps_customer_search').autocomplete({
        source: psCustomers,
        select: function(event, ui) {
            event.preventDefault();
            $('#ps_customer_search').val(ui.item.cusname);
            $('#ps_customer_id').val(ui.item.value);
            $('#ps_cus_name').text(ui.item.cusname);
        }
    });

    // =========== ADD CUSTOMER FROM THE TAILORING ORDER SCREEN ===========
    $('#ps_btn_add_customer').click(function(){
        if($('#ps_customer_search').prop('disabled')){
            // The order already exists; its customer cannot be changed.
            return;
        }
        $('#ps_nc_name').val('');
        $('#ps_nc_phone').val('');
        $('#psNewCustomerModal').modal('show');
        setTimeout(function(){ $('#ps_nc_name').focus(); }, 300);
    });

    // A customer created here has to be findable straight away without
    // reloading - the page load is what built this list, and reloading would
    // throw away everything typed into the order so far.
    function psAddToCustomerList(id, name, phone){
        var entry = { label: name + (phone ? ' - ' + phone : ''),
                      cusname: name, value: String(id) };
        psCustomers.push(entry);
        try{
            $('#ps_customer_search').autocomplete('option', 'source', psCustomers);
        }catch(e){ /* widget not ready - the new customer is selected regardless */ }
    }

    $('#ps_btn_save_customer').click(function(){
        var name  = $('#ps_nc_name').val().trim();
        var phone = $('#ps_nc_phone').val().trim();
        if(!name){  swal({type:'error', title:'Name needed',  text:'Enter the customer name.'});  return; }
        if(!phone){ swal({type:'error', title:'Phone needed', text:'Enter the customer phone number.'}); return; }
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'Customers/quickAddCustomer',
            data: { name: name, contact: phone, address: '', creditlimit: 0 },
            dataType: 'json',
            success: function(newCusId){
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Customer');
                if(newCusId > 0){
                    $('#ps_customer_id').val(newCusId);
                    $('#ps_customer_search').val(name);
                    $('#ps_cus_name').text(name);
                    psAddToCustomerList(newCusId, name, phone);
                    $('#psNewCustomerModal').modal('hide');
                    swal({type:'success', title:'Customer added',
                          text: name + ' is now on this order.',
                          showConfirmButton:false, timer:1500});
                } else {
                    swal({type:'error', title:'Not saved',
                          text:'The customer could not be created. Check the phone number is not already used.'});
                }
            },
            error: function(xhr){
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Customer');
                // Adding a customer goes through the Customers page, which has
                // its own permission. Saying so beats "could not reach the
                // server" when the server answered perfectly well.
                var msg = (xhr && xhr.status === 404)
                        ? 'You do not have the Customers permission, so a customer cannot be added from here. Ask an administrator to tick it under Users.'
                        : 'Could not reach the server. Try again.';
                swal({type:'error', title:'Not saved', text: msg});
            }
        });
    });

    // Show advance payment method when amount > 0
    $('#ps_advance_payment').on('input change', function(){
        var amt = parseFloat($(this).val()) || 0;
        if(amt > 0) $('#advance_pm_row').show();
        else { $('#advance_pm_row').hide(); $('#advance_cardref_row').hide(); }
    });

    // Advance payment: show card/reference field for any non-Cash method (same as Sales)
    $('#ps_advance_method').change(function(){
        var m = $(this).val();
        $('#ps_advance_voucher_note').text('');
        if(m !== 'Cash'){
            $('#advance_cardref_row').show();
            var voucher = (m === 'Gift Voucher');
            $('#ps_advance_cardref').attr('placeholder', voucher ? 'Scan or type the card number'
                                                                 : 'Enter card number / reference no');
        } else {
            $('#advance_cardref_row').hide();
            $('#ps_advance_cardref').val('');
        }
    });

    // Check the card while the counter staff still have it in their hand,
    // rather than after the order has been created.
    $('#ps_advance_cardref').on('blur', function(){
        if($('#ps_advance_method').val() !== 'Gift Voucher') return;
        var cn = $(this).val().trim();
        if(!cn){ $('#ps_advance_voucher_note').text(''); return; }
        $.post(BASE_URL + 'ProductionSale/checkVoucher', { card_number: cn }, function(v){
            if(!v || !v.ok){
                $('#ps_advance_voucher_note').text((v && v.msg) ? v.msg : 'That card cannot be used.')
                                             .css('color','#c62828');
                return;
            }
            var note = 'LKR ' + parseFloat(v.remaining).toFixed(2) + ' left on this card.';
            if(v.one_off){
                // Single-use: whatever is not used on this payment is lost.
                $('#ps_advance_voucher_note')
                    .text(note + ' Single-use card - anything not used now is lost.').css('color','#8a6d00');
            } else {
                $('#ps_advance_voucher_note').text(note).css('color','#2e7d32');
            }
        }, 'json');
    });

    // Item autocomplete
    var psItems = [
        <?php if($items): foreach($items as $it): ?>
        { label: "<?php echo addslashes($it->itm_code . ' - ' . $it->itm_name . ' (Stock: ' . $it->stock_qty . ')'); ?>",
          value: "<?php echo $it->itm_id; ?>",
          code: "<?php echo addslashes($it->itm_code); ?>",
          name: "<?php echo addslashes($it->itm_name); ?>",
          price: "<?php echo $it->itm_sellingprice; ?>",
          stock: <?php echo ($it->stock_qty ? $it->stock_qty : 0); ?>,
          uom: "<?php echo addslashes($it->itm_uom); ?>" },
        <?php endforeach; endif; ?>
    ];
    var selectedPsItemStock = 0;
    $('#ps_item_search').autocomplete({
        source: psItems,
        minLength: 0,
        select: function(event, ui) {
            event.preventDefault();
            $('#ps_item_search').val(ui.item.code + ' - ' + ui.item.name);
            $('#ps_item_id').val(ui.item.value);
            $('#ps_item_price').val(parseFloat(ui.item.price).toFixed(2));
            selectedPsItemStock = ui.item.stock;
            $('#ps_item_stock_info').text('Stock: ' + ui.item.stock + ' ' + ui.item.uom);
            calcItemTotal();
        }
    }).on('focus', function(){ $(this).autocomplete('search', ''); });

    // Calc item total
    function calcItemTotal(){
        var q = parseFloat($('#ps_item_qty').val()) || 0;
        var p = parseFloat($('#ps_item_price').val()) || 0;
        $('#ps_item_total').val((q*p).toFixed(2));
    }
    $('#ps_item_qty, #ps_item_price').on('input', calcItemTotal);

    // Create Order
    $('#btn_create_ps').click(function(){
        var cusId = $('#ps_customer_id').val();
        var storeId = $('#ps_store').val();
        var deliveryDate = $('#ps_delivery_date').val();
        if(!cusId){ alert('Please select a customer'); return; }
        if(!storeId || storeId == '0'){ alert('Please select a store'); return; }
        if(!deliveryDate){ alert('Please set a delivery date'); return; }

        // If an advance payment is entered with a non-Cash method, require a reference (same as Sales)
        var advAmtChk = parseFloat($('#ps_advance_payment').val()) || 0;
        var advMethodChk = $('#ps_advance_method').val() || 'Cash';
        if(advAmtChk > 0 && advMethodChk !== 'Cash' && !$('#ps_advance_cardref').val().trim()){
            swal({type:'error', title:'Reference required', text:'Please enter the card number / reference for ' + advMethodChk}); return;
        }

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'ProductionSale/createOrder',
            data: {
                code: $('#ps_code').val(),
                cus_id: cusId,
                store_id: storeId,
                pickup_store_id: $('#ps_pickup_store').val() || '',
                order_date: $('#ps_date').val(),
                delivery_date: deliveryDate,
                tailoring_charge: $('#ps_tailoring_charge').val() || 0,
                notes: $('#ps_notes').val()
            },
            dataType: 'json',
            success: function(id){
                if(id > 0){
                    currentPsId = id;
                    currentPsStoreId = $('#ps_store').val();
                    // Process advance payment if any
                    var advAmt = parseFloat($('#ps_advance_payment').val()) || 0;
                    var advFailed = '';
                    if(advAmt > 0){
                        var advMethod = $('#ps_advance_method').val() || 'Cash';
                        var advRef = (advMethod !== 'Cash') ? $('#ps_advance_cardref').val().trim() : '';
                        // The reply was being thrown away, so a refused gift card
                        // still produced "Order created with advance payment of...".
                        // The order is real either way - only the payment failed.
                        $.ajax({
                            type: 'POST', url: BASE_URL + 'ProductionSale/addPayment',
                            data: { prodsale_id: id, amount: advAmt, method: advMethod, card_ref: advRef },
                            async: false, dataType: 'json',
                            success: function(pr){
                                if(pr && pr.ok === false){
                                    advFailed = pr.msg || 'The payment could not be taken.';
                                    advAmt = 0;
                                }
                            }
                        });
                    }
                    $('#ps_code, #ps_store, #ps_pickup_store, #ps_customer_search, #ps_date, #ps_delivery_date, #ps_tailoring_charge, #ps_notes, #ps_advance_payment, #ps_advance_method').prop('disabled', true);
                    // The customer on an existing order cannot be changed, so
                    // the button that would change it goes away with the box.
                    $('#ps_btn_add_customer').hide();
                    $('#ps_create_btn, #advance_payment_row, #advance_pm_row').hide();
                    $('#ps_items_section, #ps_services_section, #ps_status_section, #ps_status_buttons, #ps_tailor_section').show();
                    setPrintLinks();
                    refreshPsOrder();
                    if(advFailed){
                        swal({type:'warning', title:'Order created, advance NOT taken',
                              text: advFailed + ' Take the advance from Manage Payments on the orders list.'});
                    } else {
                        var msg = advAmt > 0 ? 'Order created with advance payment of LKR ' + advAmt.toFixed(2) + '. Estimate bill is opening in a new tab.' : 'Order created. Estimate bill is opening in a new tab.';
                        swal({type:'success', title:'Order Created!', text: msg});
                    }
                    // Print the estimate bill immediately after creation + advance
                    window.open(BASE_URL + 'tailoring-estimate/' + currentPsId, '_blank');
                }
            }
        });
    });

    // Add Item
    $('#btn_add_ps_item').click(function(){
        var itemId = $('#ps_item_id').val();
        var qty = parseFloat($('#ps_item_qty').val());
        var price = parseFloat($('#ps_item_price').val());
        if(!itemId){ alert('Select an item'); return; }
        if(!qty || qty <= 0){ alert('Enter valid quantity'); return; }
        var lineTotal = qty * price;

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'ProductionSale/addItem',
            data: { prodsale_id: currentPsId, item_id: itemId, qty: qty, unit_price: price, type: 'material' },
            dataType: 'json',
            success: function(res){
                // Decrease stock (ezy_pos_stock)
                $.ajax({
                    type: 'POST', url: BASE_URL + 'Stocks/decreaseStock',
                    data: { itmid: itemId, qty: qty, storeid: currentPsStoreId },
                    async: false, dataType: 'json'
                });
                // FIFO deduction (ezy_pos_currentqtywithgrn)
                $.ajax({
                    type: 'POST', url: BASE_URL + 'CurQtyWithGrn/ChangeQtyToSale',
                    data: { saleID: 0, itmid: itemId, qty: qty, prc: price, ttl: lineTotal, storeid: currentPsStoreId },
                    async: false, dataType: 'json'
                });
                // Stock log
                $.ajax({
                    type: 'POST', url: BASE_URL + 'Stocks/stocklog',
                    data: { itmid: itemId, qty: qty, saleID: 0, storeid: currentPsStoreId },
                    async: false, dataType: 'json'
                });

                loadPsItems();
                refreshPsOrder();
                $('#ps_item_id').val(''); $('#ps_item_search').val('');
                $('#ps_item_qty').val('1'); $('#ps_item_price, #ps_item_total').val('');
                $('#ps_item_stock_info').text('');
            }
        });
    });

    // Add Service
    $('#btn_add_ps_svc').click(function(){
        var desc = $('#ps_svc_desc').val().trim();
        var charge = parseFloat($('#ps_svc_charge').val());
        if(!desc){ alert('Enter description'); return; }
        if(!charge || charge <= 0){ alert('Enter valid charge'); return; }

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'ProductionSale/addService',
            data: { prodsale_id: currentPsId, description: desc, charge: charge },
            dataType: 'json',
            success: function(res){
                loadPsServices();
                refreshPsOrder();
                $('#ps_svc_desc, #ps_svc_charge').val('');
            }
        });
    });

    // Status buttons
    function updatePsStatus(status, badgeClass, label){
        $.post(BASE_URL + 'ProductionSale/updateStatus', { prodsale_id: currentPsId, status: status }, function(){
            $('#ps_status_badge').text(label).removeClass().addClass('badge badge-'+badgeClass).css('font-size','14px');
            swal({type:'info', title:'Status Updated', text:'Order is now: ' + label});
        });
    }
    // Assign / change the tailor (later, during processing)
    $('#btn_assign_tailor').click(function(){
        var tid = $('#ps_tailor').val();
        if(!tid){ swal({type:'error', title:'Select tailor', text:'Please choose a tailor first.'}); return; }
        $.post(BASE_URL + 'ProductionSale/assignTailor', { prodsale_id: currentPsId, tailor_id: tid }, function(res){
            var r = (typeof res === 'string') ? JSON.parse(res) : res;
            if(r.success){ swal({type:'success', title:'Tailor assigned', timer:1200, showConfirmButton:false}); }
            else { swal({type:'error', title:'Error', text:r.msg || 'Failed'}); }
        });
    });

    $('#btn_ps_cutting').click(function(){ updatePsStatus('Cutting', 'warning', 'Cutting'); });
    $('#btn_ps_stitching').click(function(){ updatePsStatus('Stitching', 'info', 'Stitching'); });
    $('#btn_ps_ready').click(function(){ updatePsStatus('Ready', 'primary', 'Ready for Pickup'); });
    $('#btn_ps_delivered').click(function(){
        var balance = parseFloat($('#ps_balance_lbl').text()) || 0;
        if(balance > 0){
            $('#ps_modal_balance').text(balance.toFixed(2));
            $('#ps_payment_amount').val(balance.toFixed(2));
            $('#ps_payment_method').val('Cash');
            $('#ps_card_ref_group').hide();
            $('#paymentModal').find('.modal-title').text('Settle Balance to Deliver');
            $('#btn_confirm_payment').data('deliver-after', true);
            $('#paymentModal').modal('show');
            return;
        }
        updatePsStatus('Delivered', 'success', 'Delivered');
    });

    // Payment
    // Show/hide card reference field based on payment method.
    // Same rule as the Sales module: any non-Cash method (Card / Cheque /
    // Reference / configured methods) requires a card number / reference.
    $('#ps_payment_method').change(function(){
        if($(this).val() !== 'Cash'){
            $('#ps_card_ref_group').show();
        } else {
            $('#ps_card_ref_group').hide();
            $('#ps_card_ref').val('');
        }
    });

    $('#btn_ps_payment').click(function(){
        var bal = $('#ps_balance_lbl').text();
        $('#ps_modal_balance').text(bal);
        $('#ps_payment_amount').val('');
        $('#ps_payment_method').val('Cash');
        $('#ps_card_ref').val('');
        $('#ps_card_ref_group').hide();
        $('#paymentModal').find('.modal-title').text('Add Payment');
        $('#btn_confirm_payment').data('deliver-after', false);
        $('#paymentModal').modal('show');
    });
    // Update order header (edit mode)
    $('#btn_update_ps').click(function(){
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'ProductionSale/updateOrderHeader',
            data: {
                prodsale_id: currentPsId,
                order_date: $('#ps_date').val(),
                delivery_date: $('#ps_delivery_date').val(),
                pickup_store_id: $('#ps_pickup_store').val() || '',
                tailoring_charge: $('#ps_tailoring_charge').val() || 0,
                notes: $('#ps_notes').val()
            },
            dataType: 'json',
            success: function(res){
                if(res.success){
                    swal({type:'success',title:'Updated',text:'Order updated.'});
                    refreshPsOrder();
                } else {
                    swal({type:'error',title:'Error',text:res.msg || 'Update failed'});
                }
            }
        });
    });

    // Edit mode: auto-load existing order
    if(isEditMode && currentPsId){
        $.post(BASE_URL + 'ProductionSale/getOrderDetails', { prodsale_id: currentPsId }, function(res){
            var o = JSON.parse(res);
            if(!o){ swal({type:'error',title:'Not Found',text:'Order not found'}); return; }
            $('#ps_code').val(o.prodsale_code);
            $('#ps_store').val(o.prodsale_store_id);
            currentPsStoreId = o.prodsale_store_id;
            if(o.prodsale_pickup_store_id) $('#ps_pickup_store').val(o.prodsale_pickup_store_id);
            $('#ps_date').val(o.prodsale_date);
            $('#ps_delivery_date').val(o.prodsale_delivery_date || '');
            $('#ps_tailoring_charge').val(parseFloat(o.prodsale_tailoring_charge || 0).toFixed(2));
            $('#ps_notes').val(o.prodsale_notes || '');
            // Set customer
            if(o.cus_name){
                $('#ps_customer_search').val(o.cus_name);
                $('#ps_customer_id').val(o.prodsale_cus_id);
                $('#ps_cus_name').text(o.cus_name);
            }
            // Disable customer and store (can't change after creation)
            $('#ps_code, #ps_store, #ps_customer_search').prop('disabled', true);
            $('#ps_btn_add_customer').hide();
            var st = o.prodsale_status;
            var stClass = 'info';
            if(st === 'Cutting') stClass = 'warning';
            else if(st === 'Stitching') stClass = 'info';
            else if(st === 'Ready') stClass = 'primary';
            else if(st === 'Delivered') stClass = 'success';
            $('#ps_status_badge').text(st).removeClass().addClass('badge badge-'+stClass).css('font-size','14px');
            $('#ps_items_section, #ps_services_section, #ps_status_section, #ps_tailor_section').show();
            if(o.prodsale_tailor_id) $('#ps_tailor').val(o.prodsale_tailor_id);
            setPrintLinks();
            if(st === 'Delivered'){
                $('#ps_date, #ps_delivery_date, #ps_pickup_store, #ps_tailoring_charge, #ps_notes').prop('disabled', true);
                $('#ps_status_buttons, #btn_update_ps').hide();
                $('#btn_add_ps_item, #btn_add_ps_svc').prop('disabled', true);
            } else {
                $('#ps_status_buttons').show();
                $('#btn_update_ps').show();
            }
            loadPsItems();
            loadPsServices();
            refreshPsOrder();
        });
    }

    $('#btn_confirm_payment').click(function(){
        var amt = parseFloat($('#ps_payment_amount').val());
        if(!amt || amt <= 0){ swal({type:'error', title:'Error', text:'Enter valid amount'}); return; }
        var method = $('#ps_payment_method').val();
        var cardRef = $('#ps_card_ref').val().trim();
        var deliverAfter = $(this).data('deliver-after');

        if(method !== 'Cash' && !cardRef){
            swal({type:'error', title:'Error', text:'Please enter the card number / reference for ' + method}); return;
        }

        $.post(BASE_URL + 'ProductionSale/addPayment', { prodsale_id: currentPsId, amount: amt, method: method, card_ref: cardRef }, function(){
            refreshPsOrder();
            $('#paymentModal').modal('hide');
            $('#ps_payment_amount').val('');
            $('#ps_card_ref').val('');

            if(deliverAfter){
                // Check if balance is now 0 after payment
                setTimeout(function(){
                    var newBal = parseFloat($('#ps_balance_lbl').text()) || 0;
                    if(newBal <= 0){
                        updatePsStatus('Delivered', 'success', 'Delivered');
                        swal({type:'success', title:'Delivered!', text:'Payment settled and order delivered.'});
                    } else {
                        swal({type:'warning', title:'Partial Payment', text:'Balance remaining: LKR ' + newBal.toFixed(2) + '. Order cannot be delivered until fully paid.'});
                    }
                }, 500);
            } else {
                swal({type:'success', title:'Payment Added', text: method + ' - LKR ' + amt.toFixed(2)});
            }
        });
    });
});

function loadPsItems(){
    $.post(BASE_URL + 'ProductionSale/getItems', { prodsale_id: currentPsId }, function(res){
        var items = JSON.parse(res);
        var html = '';
        $.each(items, function(i, m){
            html += '<tr>';
            html += '<td>'+(i+1)+'</td>';
            html += '<td>'+m.itm_code+' - '+m.itm_name+'</td>';
            html += '<td>'+parseFloat(m.prodsaleitem_qty).toFixed(2)+'</td>';
            html += '<td>'+m.itm_uom+'</td>';
            html += '<td>'+parseFloat(m.prodsaleitem_unit_price).toFixed(2)+'</td>';
            html += '<td>'+parseFloat(m.prodsaleitem_total).toFixed(2)+'</td>';
            html += '<td><button class="btn btn-danger btn-xs btn-del-psitem" data-id="'+m.prodsaleitem_id+'"><i class="fa fa-trash"></i></button></td>';
            html += '</tr>';
        });
        $('#ps_items_body').html(html);
        $('.btn-del-psitem').click(function(){
            if(confirm('Remove this item?')){
                $.post(BASE_URL + 'ProductionSale/deleteItem', { item_id: $(this).data('id'), prodsale_id: currentPsId }, function(res){
                    var data = (typeof res === 'string') ? JSON.parse(res) : res;
                    // Reverse stock deduction
                    if(data.item_id && data.item_id > 0){
                        $.ajax({
                            type: 'POST', url: BASE_URL + 'Stocks/increaseStock',
                            data: { itmid: data.item_id, qty: data.qty, storeid: currentPsStoreId },
                            async: false, dataType: 'json'
                        });
                    }
                    loadPsItems(); refreshPsOrder();
                });
            }
        });
    });
}

function loadPsServices(){
    $.post(BASE_URL + 'ProductionSale/getServices', { prodsale_id: currentPsId }, function(res){
        var svcs = JSON.parse(res);
        var html = '';
        $.each(svcs, function(i, s){
            html += '<tr>';
            html += '<td>'+(i+1)+'</td>';
            html += '<td>'+s.prodsvc_description+'</td>';
            html += '<td>'+parseFloat(s.prodsvc_charge).toFixed(2)+'</td>';
            html += '<td><button class="btn btn-danger btn-xs btn-del-pssvc" data-id="'+s.prodsvc_id+'"><i class="fa fa-trash"></i></button></td>';
            html += '</tr>';
        });
        $('#ps_services_body').html(html);
        $('.btn-del-pssvc').click(function(){
            if(confirm('Remove this service?')){
                $.post(BASE_URL + 'ProductionSale/deleteService', { svc_id: $(this).data('id'), prodsale_id: currentPsId }, function(){
                    loadPsServices(); refreshPsOrder();
                });
            }
        });
    });
}

function setPrintLinks(){
    if(!currentPsId) return;
    $('#btn_print_estimate').attr('href', BASE_URL + 'tailoring-estimate/' + currentPsId);
    $('#btn_print_final').attr('href', BASE_URL + 'tailoring-final/' + currentPsId);
}

function refreshPsOrder(){
    $.post(BASE_URL + 'ProductionSale/getOrderDetails', { prodsale_id: currentPsId }, function(res){
        var o = JSON.parse(res);
        showPsTotals(o);
    });
}

// One place that fills the cost summary, so the figures after a discount and
// the figures after adding an item are drawn by the same code.
function showPsTotals(o){
    var matCost = parseFloat(o.prodsale_material_cost) || 0;
    var tailoring = parseFloat(o.prodsale_tailoring_charge) || 0;
    var total = parseFloat(o.prodsale_total) || 0;
    var paid = parseFloat(o.prodsale_paid) || 0;
    var balance = parseFloat(o.prodsale_balance) || 0;
    var discount = parseFloat(o.prodsale_discount) || 0;
    // The service charge is whatever is left once the cloth and the tailoring
    // charge are taken out - and the discount has already come off the total,
    // so it has to be added back before that subtraction, or the service line
    // would silently absorb it.
    var svcCost = (total + discount) - matCost - tailoring;
    if(svcCost < 0) svcCost = 0;

    $('#ps_material_cost_lbl').text(matCost.toFixed(2));
    $('#ps_tailoring_cost_lbl').text(tailoring.toFixed(2));
    $('#ps_service_cost_lbl').text(svcCost.toFixed(2));
    $('#ps_discount_lbl').text(discount.toFixed(2));
    $('#ps_total_lbl').text(total.toFixed(2));
    $('#ps_paid_lbl').text(paid.toFixed(2));
    $('#ps_balance_lbl').text(balance.toFixed(2));
    if(document.activeElement && document.activeElement.id !== 'ps_discount_value'){
        $('#ps_discount_value').val((parseFloat(o.prodsale_discount_rate) || 0).toFixed(2));
    }
    $('#ps_discount_type').val(o.prodsale_discount_type === 'percentage' ? 'percentage' : 'flat');
}

// Saved when the box is left or the type is changed, not on every keystroke -
// a half-typed "25" must not spend a moment being a 2% discount.
function savePsDiscount(){
    if(!currentPsId) return;
    $.post(BASE_URL + 'ProductionSale/setDiscount', {
        prodsale_id: currentPsId,
        discount: $('#ps_discount_value').val() || 0,
        discount_type: $('#ps_discount_type').val()
    }, function(res){
        var r = (typeof res === 'string') ? JSON.parse(res) : res;
        if(!r.success){
            swal({type:'error', title:'Discount not saved', text: r.msg || 'It could not be saved.'});
            return;
        }
        showPsTotals(r.order);
    });
}
$(document).on('change', '#ps_discount_value, #ps_discount_type', savePsDiscount);
</script>
