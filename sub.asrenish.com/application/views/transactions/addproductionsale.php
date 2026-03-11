<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <!-- Left Panel: Order Details -->
        <div class="col-lg-4 col-md-5 col-sm-12">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-scissors"></i> Production Sale (Tailoring Order)</h4>
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
                            <select class="form-control" id="ps_store">
                                <?php if($this->session->userdata('userrole')==1): ?>
                                <option value="0">Select Store</option>
                                <?php endif; ?>
                                <?php if($storeLoc): foreach($storeLoc as $s): ?>
                                <option value="<?php echo $s->store_id; ?>"><?php echo $s->store_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Customer<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <input class="form-control" id="ps_customer_search" placeholder="Search customer..." autocomplete="off">
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
                        <label class="col-5 col-form-label">Tailoring Charge</label>
                        <div class="col-7">
                            <input type="number" class="form-control" id="ps_tailoring_charge" value="0.00" step="0.01" placeholder="0.00">
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
                <!-- Action Buttons -->
                <div id="ps_create_btn">
                    <button class="btn btn-success btn-block" id="btn_create_ps">
                        <i class="fa fa-plus"></i> Create Tailoring Order
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
                    <label>Payment Amount</label>
                    <input type="number" class="form-control" id="ps_payment_amount" step="0.01" placeholder="0.00">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-success" id="btn_confirm_payment">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
var currentPsId = null;

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

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'ProductionSale/createOrder',
            data: {
                code: $('#ps_code').val(),
                cus_id: cusId,
                store_id: storeId,
                order_date: $('#ps_date').val(),
                delivery_date: deliveryDate,
                tailoring_charge: $('#ps_tailoring_charge').val() || 0,
                notes: $('#ps_notes').val()
            },
            dataType: 'json',
            success: function(id){
                if(id > 0){
                    currentPsId = id;
                    $('#ps_code, #ps_store, #ps_customer_search, #ps_date, #ps_delivery_date, #ps_tailoring_charge, #ps_notes').prop('disabled', true);
                    $('#ps_create_btn').hide();
                    $('#ps_items_section, #ps_services_section, #ps_status_section, #ps_status_buttons').show();
                    refreshPsOrder();
                    swal({type:'success', title:'Order Created!', text:'Now add materials and services.'});
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

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'ProductionSale/addItem',
            data: { prodsale_id: currentPsId, item_id: itemId, qty: qty, unit_price: price, type: 'material' },
            dataType: 'json',
            success: function(res){
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
    $('#btn_ps_cutting').click(function(){ updatePsStatus('Cutting', 'warning', 'Cutting'); });
    $('#btn_ps_stitching').click(function(){ updatePsStatus('Stitching', 'info', 'Stitching'); });
    $('#btn_ps_ready').click(function(){ updatePsStatus('Ready', 'primary', 'Ready for Pickup'); });
    $('#btn_ps_delivered').click(function(){ updatePsStatus('Delivered', 'success', 'Delivered'); });

    // Payment
    $('#btn_ps_payment').click(function(){ $('#paymentModal').modal('show'); });
    $('#btn_confirm_payment').click(function(){
        var amt = parseFloat($('#ps_payment_amount').val());
        if(!amt || amt <= 0){ alert('Enter valid amount'); return; }
        $.post(BASE_URL + 'ProductionSale/addPayment', { prodsale_id: currentPsId, amount: amt }, function(){
            refreshPsOrder();
            $('#paymentModal').modal('hide');
            $('#ps_payment_amount').val('');
            swal({type:'success', title:'Payment Added'});
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
                $.post(BASE_URL + 'ProductionSale/deleteItem', { item_id: $(this).data('id'), prodsale_id: currentPsId }, function(){
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

function refreshPsOrder(){
    $.post(BASE_URL + 'ProductionSale/getOrderDetails', { prodsale_id: currentPsId }, function(res){
        var o = JSON.parse(res);
        var matCost = parseFloat(o.prodsale_material_cost) || 0;
        var tailoring = parseFloat(o.prodsale_tailoring_charge) || 0;
        var total = parseFloat(o.prodsale_total) || 0;
        var paid = parseFloat(o.prodsale_paid) || 0;
        var balance = parseFloat(o.prodsale_balance) || 0;
        var svcCost = total - matCost - tailoring;
        if(svcCost < 0) svcCost = 0;

        $('#ps_material_cost_lbl').text(matCost.toFixed(2));
        $('#ps_tailoring_cost_lbl').text(tailoring.toFixed(2));
        $('#ps_service_cost_lbl').text(svcCost.toFixed(2));
        $('#ps_total_lbl').text(total.toFixed(2));
        $('#ps_paid_lbl').text(paid.toFixed(2));
        $('#ps_balance_lbl').text(balance.toFixed(2));
    });
}
</script>
