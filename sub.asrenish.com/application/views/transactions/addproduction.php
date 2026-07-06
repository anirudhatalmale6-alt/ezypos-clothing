<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <!-- Left Panel: Production Details -->
        <div class="col-lg-4 col-md-5 col-sm-12">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-industry"></i> <?php echo (isset($editProdId) && $editProdId) ? 'Edit Production Order' : 'New Production Order'; ?></h4>
                <fieldset>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Production Code</label>
                        <div class="col-7">
                            <input class="form-control" id="prod_code" value="<?php echo $nextCode; ?>" readonly style="background:#f5f5f5;">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Date<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <input type="date" class="form-control" id="prod_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Material From<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <select class="form-control" id="prod_store">
                                <?php if($this->session->userdata('userrole')==1): ?>
                                <option value="0">Select Store</option>
                                <?php endif; ?>
                                <?php if(isset($storeLoc) && $storeLoc): foreach($storeLoc as $s): ?>
                                <option value="<?php echo $s->store_id; ?>"><?php echo $s->store_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">GRN Output To<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <select class="form-control" id="prod_output_store">
                                <?php if($this->session->userdata('userrole')==1): ?>
                                <option value="0">Select Store</option>
                                <?php endif; ?>
                                <?php if(isset($storeLoc) && $storeLoc): foreach($storeLoc as $s): ?>
                                <option value="<?php echo $s->store_id; ?>"><?php echo $s->store_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Output Item<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <input class="form-control" id="output_item_search" placeholder="Search garment..." autocomplete="off">
                            <input type="hidden" id="output_item" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Output Qty<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <input type="number" class="form-control" id="output_qty" value="1" min="1" step="1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Type<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <select class="form-control" id="prod_type">
                                <option value="in-house">In-House</option>
                                <option value="outsource">Outsource</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="tailor_div" style="display:none;">
                        <label class="col-5 col-form-label">Tailor/Supplier</label>
                        <div class="col-7">
                            <select class="form-control" id="tailor_id">
                                <option value="">-- Select Tailor --</option>
                                <?php if($suppliers): foreach($suppliers as $sup): ?>
                                <option value="<?php echo $sup->sup_id; ?>"><?php echo $sup->sup_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Notes</label>
                        <div class="col-7">
                            <textarea class="form-control" id="prod_notes" rows="2" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <!-- Cost Summary -->
                <div id="cost_summary">
                    <div class="form-group row">
                        <label class="col-7 col-form-label"><strong>Material Cost:</strong></label>
                        <label class="col-form-label">LKR <span id="material_cost_lbl">0.00</span></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-7 col-form-label"><strong>Tailoring Cost:</strong></label>
                        <label class="col-form-label">LKR <span id="tailoring_cost_lbl">0.00</span></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-7 col-form-label"><strong>Other Costs:</strong></label>
                        <label class="col-form-label">LKR <span id="other_cost_lbl">0.00</span></label>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-7 col-form-label"><strong>Gross Cost:</strong></label>
                        <label class="col-form-label">LKR <span id="gross_cost_lbl">0.00</span></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label"><strong>Discount:</strong></label>
                        <div class="col-4">
                            <input type="number" class="form-control form-control-sm" id="prod_discount" value="0" min="0" step="0.01">
                        </div>
                        <div class="col-4">
                            <select class="form-control form-control-sm" id="prod_discount_type">
                                <option value="percentage">%</option>
                                <option value="flat">Flat LKR</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-7 col-form-label"><strong style="font-size:16px;">Total Cost:</strong></label>
                        <label class="col-form-label"><strong style="font-size:16px;">LKR <span id="total_cost_lbl">0.00</span></strong></label>
                    </div>
                    <div class="form-group row">
                        <label class="col-7 col-form-label">Unit Cost:</label>
                        <label class="col-form-label">LKR <span id="unit_cost_lbl">0.00</span></label>
                    </div>
                </div>
                <hr>
                <!-- Status -->
                <div class="form-group row" id="status_section" style="display:none;">
                    <label class="col-5 col-form-label"><strong>Status:</strong></label>
                    <div class="col-7">
                        <span id="status_badge" class="badge badge-info" style="font-size:14px;">Issued</span>
                    </div>
                </div>
                <div class="form-group row" id="action_buttons">
                    <div class="col-12">
                        <button class="btn btn-success btn-block" id="btn_create_production" <?php echo (isset($editProdId) && $editProdId) ? 'style="display:none;"' : ''; ?>>
                            <i class="fa fa-plus"></i> Create Production Order
                        </button>
                        <button class="btn btn-primary btn-block" id="btn_update_production" style="display:none;">
                            <i class="fa fa-save"></i> Update Production Header
                        </button>
                    </div>
                </div>
                <div id="status_buttons" style="display:none;">
                    <div class="form-group row">
                        <div class="col-6">
                            <button class="btn btn-warning btn-block btn-sm" id="btn_in_progress">
                                <i class="fa fa-spinner"></i> Mark In-Progress
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-primary btn-block btn-sm" id="btn_complete">
                                <i class="fa fa-check"></i> Complete & Create GRN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Materials & Costs -->
        <div class="col-lg-8 col-md-7 col-sm-12">
            <!-- Add Material Section -->
            <div class="card-box clearfix" id="material_section" style="display:none;">
                <h5 class="header-title m-t-0 m-b-15"><i class="fa fa-scissors"></i> Raw Materials</h5>
                <div class="row">
                    <div class="col-md-4">
                        <label>Material<span class="text-danger">*</span></label>
                        <input class="form-control" id="mat_item_search" placeholder="Search material..." autocomplete="off">
                        <input type="hidden" id="mat_item" value="">
                        <small id="mat_stock_info" class="text-muted"></small>
                    </div>
                    <div class="col-md-2">
                        <label>Qty<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="mat_qty" placeholder="Qty" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label>Unit Price</label>
                        <input type="number" class="form-control" id="mat_price" placeholder="Price" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label>Total</label>
                        <input type="text" class="form-control" id="mat_total" readonly style="background:#f5f5f5;">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button class="btn btn-success btn-block" id="btn_add_material">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </div>

                <!-- Materials Table -->
                <div class="table-responsive m-t-15">
                    <table class="table table-bordered table-sm" id="materials_table">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Material</th>
                                <th>Qty</th>
                                <th>UOM</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="materials_body"></tbody>
                    </table>
                </div>
            </div>

            <!-- Gate Pass Section -->
            <div class="card-box clearfix" id="gatepass_section" style="display:none;">
                <div class="row">
                    <div class="col-6">
                        <h5 class="header-title m-t-0 m-b-15"><i class="fa fa-ticket"></i> Gate Passes</h5>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-primary btn-sm" id="btn_new_gatepass">
                            <i class="fa fa-plus"></i> Issue New Gate Pass
                        </button>
                    </div>
                </div>

                <!-- Gate Passes List -->
                <div id="gatepass_list"></div>
            </div>

            <!-- Gate Pass Modal -->
            <div class="modal fade" id="gatePassModal" tabindex="-1" role="dialog" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fa fa-ticket"></i> Issue Gate Pass</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row m-b-10">
                                <div class="col-md-6">
                                    <label>Source Store</label>
                                    <input type="text" class="form-control" id="gp_store_name" readonly style="background:#f5f5f5;">
                                    <input type="hidden" id="gp_store_id">
                                </div>
                                <div class="col-md-6">
                                    <label>Notes</label>
                                    <input type="text" class="form-control" id="gp_notes" placeholder="Optional notes...">
                                </div>
                            </div>
                            <hr>
                            <h6>Add Materials to Gate Pass</h6>
                            <div class="row m-b-10">
                                <div class="col-md-4">
                                    <label>Material</label>
                                    <input class="form-control" id="gp_mat_search" placeholder="Search material..." autocomplete="off">
                                    <input type="hidden" id="gp_mat_item">
                                    <small id="gp_mat_stock" class="text-muted"></small>
                                </div>
                                <div class="col-md-2">
                                    <label>Qty</label>
                                    <input type="number" class="form-control" id="gp_mat_qty" step="0.01" min="0.01">
                                    <small id="gp_mat_uom_label" class="text-muted"></small>
                                </div>
                                <div class="col-md-2">
                                    <label>Unit Price</label>
                                    <input type="number" class="form-control" id="gp_mat_price" step="0.01">
                                </div>
                                <div class="col-md-2">
                                    <label>Total</label>
                                    <input type="text" class="form-control" id="gp_mat_total" readonly style="background:#f5f5f5;">
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-success btn-block btn-sm" id="btn_gp_add_item">
                                        <i class="fa fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>

                            <!-- Gate Pass Items Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="gp_items_table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Material</th>
                                            <th>UOM</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gp_items_body"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right"><strong>Grand Total:</strong></td>
                                            <td><strong id="gp_grand_total">0.00</strong></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary" id="btn_issue_gatepass">
                                <i class="fa fa-check"></i> Issue Gate Pass
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Return Material Modal -->
            <div class="modal fade" id="returnModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fa fa-undo"></i> Return Material</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p id="return_info"></p>
                            <div class="form-group">
                                <label>Return Quantity</label>
                                <input type="number" class="form-control" id="return_qty" step="0.01" min="0.01">
                                <small class="text-muted" id="return_max_label"></small>
                            </div>
                            <input type="hidden" id="return_gpitem_id">
                            <input type="hidden" id="return_gp_id">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                            <button class="btn btn-warning btn-sm" id="btn_confirm_return">
                                <i class="fa fa-undo"></i> Confirm Return
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Cost Section -->
            <div class="card-box clearfix" id="cost_section" style="display:none;">
                <h5 class="header-title m-t-0 m-b-15"><i class="fa fa-money"></i> Additional Costs</h5>
                <div class="row">
                    <div class="col-md-3">
                        <label>Type<span class="text-danger">*</span></label>
                        <select class="form-control" id="cost_type">
                            <option value="tailoring">Tailoring</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Description<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cost_desc" placeholder="e.g. Stitching charges">
                    </div>
                    <div class="col-md-3">
                        <label>Amount<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="cost_amount" placeholder="0.00" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button class="btn btn-success btn-block" id="btn_add_cost">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </div>

                <!-- Costs Table -->
                <div class="table-responsive m-t-15">
                    <table class="table table-bordered table-sm" id="costs_table">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="costs_body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
var currentProdId = <?php echo isset($editProdId) && $editProdId ? $editProdId : 'null'; ?>;
var isEditMode = <?php echo isset($editProdId) && $editProdId ? 'true' : 'false'; ?>;

$(document).ready(function() {
    // Output Item autocomplete search
    var finishedItems = [
        <?php if($finishedItems): foreach($finishedItems as $item): ?>
        { label: "<?php echo addslashes($item->itm_code . ' - ' . $item->itm_name); ?>", value: "<?php echo $item->itm_id; ?>" },
        <?php endforeach; endif; ?>
    ];
    $('#output_item_search').autocomplete({
        source: finishedItems,
        minLength: 0,
        select: function(event, ui) {
            event.preventDefault();
            $('#output_item_search').val(ui.item.label);
            $('#output_item').val(ui.item.value);
        }
    }).on('focus', function(){ $(this).autocomplete('search', ''); });

    // Show/hide tailor dropdown based on type
    $('#prod_type').change(function() {
        if ($(this).val() == 'outsource') {
            $('#tailor_div').show();
        } else {
            $('#tailor_div').hide();
            $('#tailor_id').val('');
        }
    });

    // Calculate material total on qty/price change
    $('#mat_qty, #mat_price').on('input', function() {
        var qty = parseFloat($('#mat_qty').val()) || 0;
        var price = parseFloat($('#mat_price').val()) || 0;
        $('#mat_total').val((qty * price).toFixed(2));
    });

    // Material autocomplete - loaded dynamically by store
    var rawMaterials = [];
    var selectedMatStock = 0;
    var selectedMatUom = '';

    function loadRawMaterialsByStore(storeId) {
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'production/getRawMaterialsByStore',
            data: { store_id: storeId },
            dataType: 'json',
            success: function(data) {
                rawMaterials = [];
                if (data) {
                    for (var i = 0; i < data.length; i++) {
                        var m = data[i];
                        rawMaterials.push({
                            label: m.itm_code + ' - ' + m.itm_name + ' (Stock: ' + parseFloat(m.stock_qty).toFixed(0) + ' ' + m.itm_uom + ')',
                            value: m.itm_id,
                            code: m.itm_code,
                            name: m.itm_name,
                            stock: parseFloat(m.stock_qty) || 0,
                            uom: m.itm_uom
                        });
                    }
                }
                // Re-initialize autocomplete with new data
                $('#mat_item_search').autocomplete('option', 'source', rawMaterials);
                // Clear any existing selection
                $('#mat_item_search').val('');
                $('#mat_item').val('');
                $('#mat_stock_info').text('');
            }
        });
    }

    // Reload materials when source store changes
    $('#prod_store').change(function() {
        var storeId = $(this).val() || 0;
        if (currentProdId) {
            loadRawMaterialsByStore(storeId);
        }
    });

    // Initialize autocomplete
    $('#mat_item_search').autocomplete({
        source: rawMaterials,
        minLength: 0,
        select: function(event, ui) {
            event.preventDefault();
            $('#mat_item_search').val(ui.item.code + ' - ' + ui.item.name);
            $('#mat_item').val(ui.item.value);
            selectedMatStock = ui.item.stock;
            selectedMatUom = ui.item.uom;
            $('#mat_stock_info').text('Stock: ' + ui.item.stock + ' ' + ui.item.uom);
            // Auto-fetch price filtered by store
            $.post(BASE_URL + 'production/getMaterialPrice', { item_id: ui.item.value, store_id: $('#prod_store').val() || 0 }, function(res) {
                var price = JSON.parse(res);
                $('#mat_price').val(parseFloat(price).toFixed(2));
                $('#mat_qty').trigger('input');
            });
        }
    }).on('focus', function(){ $(this).autocomplete('search', ''); });

    // Create Production Order
    $('#btn_create_production').click(function() {
        var outputItem = $('#output_item').val();
        var outputQty = $('#output_qty').val();
        var storeId = $('#prod_store').val();
        var outputStoreId = $('#prod_output_store').val();
        if (!storeId || storeId == '0') { alert('Please select the source store (Material From)'); return; }
        if (!outputStoreId || outputStoreId == '0') { alert('Please select the output store (GRN Output To)'); return; }
        if (!outputItem) { alert('Please select an output item'); return; }
        if (!outputQty || outputQty < 1) { alert('Please enter output quantity'); return; }

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'production/addProductionPOST',
            data: {
                prod_code: $('#prod_code').val(),
                prod_date: $('#prod_date').val(),
                output_item: outputItem,
                output_qty: outputQty,
                prod_type: $('#prod_type').val(),
                tailor_id: $('#tailor_id').val(),
                prod_notes: $('#prod_notes').val(),
                store_id: storeId,
                output_store_id: outputStoreId
            },
            dataType: 'json',
            success: function(prodId) {
                if (prodId > 0) {
                    currentProdId = prodId;
                    // Disable header fields
                    $('#prod_code, #prod_date, #output_item, #output_item_search, #output_qty, #prod_type, #tailor_id, #prod_store, #prod_output_store').prop('disabled', true);
                    $('#btn_create_production').hide();
                    // Show material & cost & gatepass sections
                    $('#material_section, #cost_section, #gatepass_section, #status_section, #status_buttons').show();
                    // Load raw materials for the selected source store
                    loadRawMaterialsByStore(storeId);
                    Swal.fire('Success', 'Production order created! Now add materials and costs.', 'success');
                } else {
                    Swal.fire('Error', 'Failed to create production order', 'error');
                }
            }
        });
    });

    // Add Material
    $('#btn_add_material').click(function() {
        var itemId = $('#mat_item').val();
        var qty = parseFloat($('#mat_qty').val());
        var price = parseFloat($('#mat_price').val());

        if (!itemId) { alert('Select a material'); return; }
        if (!qty || qty <= 0) { alert('Enter valid quantity'); return; }
        if (!price || price < 0) { alert('Enter valid price'); return; }

        // Check stock
        if (qty > selectedMatStock) {
            Swal.fire('Warning', 'Only ' + selectedMatStock + ' available in stock!', 'warning');
            return;
        }

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'production/addMaterial',
            data: {
                prod_id: currentProdId,
                item_id: itemId,
                qty: qty,
                unit_price: price,
                storeid: $('#prod_store').val() || 0
            },
            dataType: 'json',
            success: function(res) {
                if (res) {
                    loadMaterials();
                    refreshCosts();
                    // Reset material form
                    $('#mat_item').val('');
                    $('#mat_item_search').val('');
                    $('#mat_stock_info').text('');
                    $('#mat_qty, #mat_price, #mat_total').val('');
                    selectedMatStock = selectedMatStock - qty;
                }
            }
        });
    });

    // Add Cost
    $('#btn_add_cost').click(function() {
        var desc = $('#cost_desc').val().trim();
        var amount = parseFloat($('#cost_amount').val());
        if (!desc) { alert('Enter description'); return; }
        if (!amount || amount <= 0) { alert('Enter valid amount'); return; }

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'production/addCost',
            data: {
                prod_id: currentProdId,
                cost_type: $('#cost_type').val(),
                description: desc,
                amount: amount
            },
            dataType: 'json',
            success: function(res) {
                if (res) {
                    loadCosts();
                    refreshCosts();
                    $('#cost_desc, #cost_amount').val('');
                }
            }
        });
    });

    // Mark In-Progress
    $('#btn_in_progress').click(function() {
        $.post(BASE_URL + 'production/updateStatus', { prod_id: currentProdId, status: 'In-Progress' }, function(res) {
            $('#status_badge').text('In-Progress').removeClass().addClass('badge badge-warning').css('font-size','14px');
            $('#btn_in_progress').hide();
            Swal.fire('Updated', 'Production marked as In-Progress', 'info');
        });
    });

    // Update Production Header (edit mode)
    $('#btn_update_production').click(function(){
        var outputItem = $('#output_item').val();
        var outputQty = $('#output_qty').val();
        if(!outputItem){ alert('Please select an output item'); return; }
        if(!outputQty || outputQty < 1){ alert('Please enter output quantity'); return; }
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'production/updateProductionHeader',
            data: {
                prod_id: currentProdId,
                prod_date: $('#prod_date').val(),
                output_item: outputItem,
                output_qty: outputQty,
                prod_type: $('#prod_type').val(),
                tailor_id: $('#tailor_id').val(),
                prod_notes: $('#prod_notes').val(),
                store_id: $('#prod_store').val() || 0,
                output_store_id: $('#prod_output_store').val() || 0
            },
            dataType: 'json',
            success: function(res){
                if(res.success){
                    swal({type:'success',title:'Updated',text:'Production header updated.'});
                    refreshCosts();
                } else {
                    swal({type:'error',title:'Error',text:res.msg || 'Update failed'});
                }
            }
        });
    });

    // Edit mode: auto-load existing production
    if(isEditMode && currentProdId){
        $.post(BASE_URL + 'production/getProductionDetails', { prod_id: currentProdId }, function(res){
            var prod = JSON.parse(res);
            if(!prod){ swal({type:'error',title:'Not Found',text:'Production not found'}); return; }
            $('#prod_code').val(prod.prod_code);
            $('#prod_date').val(prod.prod_date);
            $('#prod_store').val(prod.prod_store_id);
            $('#prod_output_store').val(prod.prod_output_store_id || 0);
            if(prod.output_item_name){
                $('#output_item_search').val(prod.output_item_code + ' - ' + prod.output_item_name);
                $('#output_item').val(prod.prod_output_item_id);
            }
            $('#output_qty').val(parseFloat(prod.prod_output_qty).toFixed(0));
            $('#prod_type').val(prod.prod_type);
            if(prod.prod_type === 'outsource'){ $('#tailor_div').show(); }
            if(prod.prod_tailor_id){ $('#tailor_id').val(prod.prod_tailor_id); }
            $('#prod_notes').val(prod.prod_notes || '');
            var st = prod.prod_status;
            var stClass = 'info';
            if(st === 'In-Progress') stClass = 'warning';
            else if(st === 'Completed') stClass = 'success';
            else if(st === 'Cancelled') stClass = 'danger';
            $('#status_badge').text(st).removeClass().addClass('badge badge-'+stClass).css('font-size','14px');
            $('#material_section, #cost_section, #gatepass_section, #status_section').show();
            if(st === 'Completed' || st === 'Cancelled'){
                $('#prod_code, #prod_date, #output_item, #output_item_search, #output_qty, #prod_type, #tailor_id, #prod_store, #prod_output_store, #prod_notes').prop('disabled', true);
                $('#btn_update_production, #status_buttons').hide();
                $('#btn_add_material, #btn_add_cost, #btn_new_gatepass').prop('disabled', true);
            } else {
                $('#status_buttons').show();
                $('#btn_update_production').show();
                if(st === 'In-Progress') $('#btn_in_progress').hide();
            }
            loadRawMaterialsByStore(prod.prod_store_id);
            loadMaterials();
            loadCosts();
            refreshCosts();
            loadGatePasses();
        });
    }

    // Complete Production
    $('#btn_complete').click(function() {
        Swal.fire({
            title: 'Complete Production?',
            text: 'This will create a GRN for the finished garments and add them to stock. This action cannot be undone.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Complete',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post(BASE_URL + 'production/updateStatus', { prod_id: currentProdId, status: 'Completed' }, function(res) {
                    $('#status_badge').text('Completed').removeClass().addClass('badge badge-success').css('font-size','14px');
                    $('#status_buttons').hide();
                    // Disable all inputs
                    $('input, select, textarea, button').not('#btn_new').prop('disabled', true);
                    Swal.fire('Completed!', 'Production completed! Finished garments added to stock via GRN.', 'success');
                });
            }
        });
    });
});

function loadMaterials() {
    $.post(BASE_URL + 'production/getMaterials', { prod_id: currentProdId }, function(res) {
        var materials = JSON.parse(res);
        var html = '';
        $.each(materials, function(i, m) {
            html += '<tr>';
            html += '<td>' + (i+1) + '</td>';
            html += '<td>' + m.itm_code + ' - ' + m.itm_name + '</td>';
            html += '<td>' + parseFloat(m.prodmat_qty).toFixed(2) + '</td>';
            html += '<td>' + m.itm_uom + '</td>';
            html += '<td>' + parseFloat(m.prodmat_unit_price).toFixed(2) + '</td>';
            html += '<td>' + parseFloat(m.prodmat_total).toFixed(2) + '</td>';
            html += '<td><button class="btn btn-danger btn-xs btn-del-mat" data-id="'+m.prodmat_id+'"><i class="fa fa-trash"></i></button></td>';
            html += '</tr>';
        });
        $('#materials_body').html(html);

        // Bind delete
        $('.btn-del-mat').click(function() {
            var matId = $(this).data('id');
            if (confirm('Remove this material?')) {
                $.post(BASE_URL + 'production/deleteMaterial', { matId: matId, storeid: $('#prod_store').val() || 0 }, function() {
                    loadMaterials();
                    refreshCosts();
                });
            }
        });
    });
}

function loadCosts() {
    $.post(BASE_URL + 'production/getCosts', { prod_id: currentProdId }, function(res) {
        var costs = JSON.parse(res);
        var html = '';
        $.each(costs, function(i, c) {
            html += '<tr>';
            html += '<td>' + (i+1) + '</td>';
            html += '<td><span class="badge badge-'+(c.prodcost_type=='tailoring'?'info':'secondary')+'">' + c.prodcost_type + '</span></td>';
            html += '<td>' + c.prodcost_description + '</td>';
            html += '<td>' + parseFloat(c.prodcost_amount).toFixed(2) + '</td>';
            html += '<td><button class="btn btn-danger btn-xs btn-del-cost" data-id="'+c.prodcost_id+'"><i class="fa fa-trash"></i></button></td>';
            html += '</tr>';
        });
        $('#costs_body').html(html);

        // Bind delete
        $('.btn-del-cost').click(function() {
            var costId = $(this).data('id');
            if (confirm('Remove this cost?')) {
                $.post(BASE_URL + 'production/deleteCost', { costId: costId }, function() {
                    loadCosts();
                    refreshCosts();
                });
            }
        });
    });
}

function refreshCosts() {
    $.post(BASE_URL + 'production/getProductionDetails', { prod_id: currentProdId }, function(res) {
        var prod = JSON.parse(res);
        var matCost = parseFloat(prod.prod_material_cost) || 0;
        var tailCost = parseFloat(prod.prod_tailoring_cost) || 0;
        var otherCost = parseFloat(prod.prod_other_cost) || 0;
        var grossCost = matCost + tailCost + otherCost;
        $('#material_cost_lbl').text(matCost.toFixed(2));
        $('#tailoring_cost_lbl').text(tailCost.toFixed(2));
        $('#other_cost_lbl').text(otherCost.toFixed(2));
        $('#gross_cost_lbl').text(grossCost.toFixed(2));
        if (prod.prod_discount) $('#prod_discount').val(parseFloat(prod.prod_discount));
        if (prod.prod_discount_type) $('#prod_discount_type').val(prod.prod_discount_type);
        applyProdDiscount(grossCost);
    });
}

function applyProdDiscount(grossCost) {
    if (typeof grossCost === 'undefined') grossCost = parseFloat($('#gross_cost_lbl').text()) || 0;
    var disc = parseFloat($('#prod_discount').val()) || 0;
    var discType = $('#prod_discount_type').val();
    var totalCost = 0;
    if (discType == 'flat') {
        totalCost = grossCost - disc;
    } else {
        totalCost = grossCost * (100 - disc) / 100;
    }
    if (totalCost < 0) totalCost = 0;
    $('#total_cost_lbl').text(totalCost.toFixed(2));
    var outQty = parseFloat($('#output_qty').val()) || 1;
    $('#unit_cost_lbl').text((totalCost / outQty).toFixed(2));
}

$('#prod_discount').on('keyup change', function() { applyProdDiscount(); saveProdDiscount(); });
$('#prod_discount_type').on('change', function() { applyProdDiscount(); saveProdDiscount(); });

function saveProdDiscount() {
    if (!currentProdId) return;
    $.post(BASE_URL + 'production/saveDiscount', {
        prod_id: currentProdId,
        discount: $('#prod_discount').val(),
        discount_type: $('#prod_discount_type').val()
    });
}

// ==================== GATE PASS ====================

var gpTempItems = [];
var gpSelectedStock = 0;
var gpSelectedUom = '';

// Open gate pass modal
$('#btn_new_gatepass').click(function(){
    gpTempItems = [];
    renderGpTempItems();
    var storeName = $('#prod_store option:selected').text();
    var storeId = $('#prod_store').val() || 0;
    $('#gp_store_name').val(storeName);
    $('#gp_store_id').val(storeId);
    $('#gp_notes').val('');
    $('#gp_mat_search, #gp_mat_item, #gp_mat_qty, #gp_mat_price, #gp_mat_total').val('');
    $('#gp_mat_stock, #gp_mat_uom_label').text('');
    // Init autocomplete with same raw materials
    $('#gp_mat_search').autocomplete({
        source: rawMaterials,
        minLength: 0,
        select: function(event, ui){
            event.preventDefault();
            $('#gp_mat_search').val(ui.item.code + ' - ' + ui.item.name);
            $('#gp_mat_item').val(ui.item.value);
            gpSelectedStock = ui.item.stock;
            gpSelectedUom = ui.item.uom;
            $('#gp_mat_stock').text('Stock: ' + ui.item.stock + ' ' + ui.item.uom);
            $('#gp_mat_uom_label').text(ui.item.uom);
            $.post(BASE_URL + 'production/getMaterialPrice', {item_id: ui.item.value, store_id: storeId}, function(res){
                var price = JSON.parse(res);
                $('#gp_mat_price').val(parseFloat(price).toFixed(2));
                $('#gp_mat_qty').trigger('input');
            });
        }
    }).on('focus', function(){ $(this).autocomplete('search',''); });
    $('#gatePassModal').modal('show');
});

// Calculate GP item total
$('#gp_mat_qty, #gp_mat_price').on('input', function(){
    var q = parseFloat($('#gp_mat_qty').val()) || 0;
    var p = parseFloat($('#gp_mat_price').val()) || 0;
    $('#gp_mat_total').val((q * p).toFixed(2));
});

// Add item to gate pass temp list
$('#btn_gp_add_item').click(function(){
    var itemId = $('#gp_mat_item').val();
    var itemName = $('#gp_mat_search').val();
    var qty = parseFloat($('#gp_mat_qty').val());
    var price = parseFloat($('#gp_mat_price').val());
    if(!itemId){ alert('Select a material'); return; }
    if(!qty || qty <= 0){ alert('Enter valid quantity'); return; }
    if(!price || price < 0){ alert('Enter valid price'); return; }

    // Check stock (account for already added items of same type)
    var alreadyAdded = 0;
    for(var i=0; i<gpTempItems.length; i++){
        if(gpTempItems[i].item_id == itemId) alreadyAdded += gpTempItems[i].qty;
    }
    if((qty + alreadyAdded) > gpSelectedStock){
        swal({type:'warning', title:'Insufficient Stock', text:'Only ' + (gpSelectedStock - alreadyAdded).toFixed(2) + ' ' + gpSelectedUom + ' available!'});
        return;
    }

    gpTempItems.push({
        item_id: itemId,
        name: itemName,
        qty: qty,
        price: price,
        total: qty * price,
        uom: gpSelectedUom
    });
    renderGpTempItems();
    // Clear inputs
    $('#gp_mat_search, #gp_mat_item, #gp_mat_qty, #gp_mat_price, #gp_mat_total').val('');
    $('#gp_mat_stock, #gp_mat_uom_label').text('');
});

function renderGpTempItems(){
    var html = '';
    var grandTotal = 0;
    for(var i=0; i<gpTempItems.length; i++){
        var it = gpTempItems[i];
        grandTotal += it.total;
        html += '<tr>';
        html += '<td>'+(i+1)+'</td>';
        html += '<td>'+it.name+'</td>';
        html += '<td>'+it.uom+'</td>';
        html += '<td>'+it.qty.toFixed(2)+'</td>';
        html += '<td>'+it.price.toFixed(2)+'</td>';
        html += '<td>'+it.total.toFixed(2)+'</td>';
        html += '<td><button class="btn btn-danger btn-xs btn-gp-remove" data-idx="'+i+'"><i class="fa fa-times"></i></button></td>';
        html += '</tr>';
    }
    $('#gp_items_body').html(html);
    $('#gp_grand_total').text(grandTotal.toFixed(2));
    // Bind remove
    $('.btn-gp-remove').click(function(){
        var idx = $(this).data('idx');
        gpTempItems.splice(idx, 1);
        renderGpTempItems();
    });
}

// Issue Gate Pass
$('#btn_issue_gatepass').click(function(){
    if(gpTempItems.length === 0){
        swal({type:'warning', title:'No Items', text:'Add at least one material to the gate pass.'});
        return;
    }
    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Issuing...');

    $.ajax({
        type: 'POST',
        url: BASE_URL + 'production/createGatePass',
        data: {
            prod_id: currentProdId,
            store_id: $('#gp_store_id').val(),
            notes: $('#gp_notes').val(),
            items: JSON.stringify(gpTempItems)
        },
        dataType: 'json',
        success: function(res){
            btn.prop('disabled', false).html('<i class="fa fa-check"></i> Issue Gate Pass');
            if(res.success){
                $('#gatePassModal').modal('hide');
                swal({type:'success', title:'Gate Pass Issued', text:'Gate Pass ' + res.gp_code + ' has been created.'});
                loadGatePasses();
                loadMaterials();
                refreshCosts();
                loadRawMaterialsByStore($('#prod_store').val());
            } else {
                swal({type:'error', title:'Error', text: res.msg || 'Failed to create gate pass'});
            }
        },
        error: function(){
            btn.prop('disabled', false).html('<i class="fa fa-check"></i> Issue Gate Pass');
            swal({type:'error', title:'Error', text:'Server error. Please try again.'});
        }
    });
});

// Load gate passes for current production
function loadGatePasses(){
    if(!currentProdId) return;
    $.ajax({
        type: 'POST',
        url: BASE_URL + 'production/getGatePasses',
        data: { prod_id: currentProdId },
        dataType: 'json',
        success: function(passes){
            if(!passes || passes.length === 0){
                $('#gatepass_list').html('<p class="text-muted text-center m-t-10">No gate passes issued yet. Click "Issue New Gate Pass" to create one.</p>');
                return;
            }
            var html = '';
            for(var p=0; p<passes.length; p++){
                var gp = passes[p];
                var statusClass = 'info';
                if(gp.gp_status === 'Partially Returned') statusClass = 'warning';
                else if(gp.gp_status === 'Fully Returned') statusClass = 'success';
                else if(gp.gp_status === 'Cancelled') statusClass = 'danger';
                html += '<div class="card m-b-10" style="border:1px solid #ddd;">';
                html += '<div class="card-header" style="padding:8px 15px; background:#f8f9fa; cursor:pointer;" data-toggle="collapse" data-target="#gp_detail_'+gp.gp_id+'">';
                html += '<div class="row">';
                html += '<div class="col-3"><strong>'+gp.gp_code+'</strong></div>';
                html += '<div class="col-2">'+gp.gp_date+'</div>';
                html += '<div class="col-2"><span class="badge badge-'+statusClass+'">'+gp.gp_status+'</span></div>';
                html += '<div class="col-3">Total: LKR '+parseFloat(gp.gp_total).toFixed(2)+'</div>';
                html += '<div class="col-2 text-right">';
                html += '<a href="'+BASE_URL+'print-gate-pass/'+gp.gp_id+'" target="_blank" class="btn btn-info btn-xs" title="Print"><i class="fa fa-print"></i></a>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<div id="gp_detail_'+gp.gp_id+'" class="collapse">';
                html += '<div class="card-body" style="padding:10px 15px;">';
                if(gp.gp_notes) html += '<p class="text-muted" style="font-size:12px;">Notes: '+gp.gp_notes+'</p>';
                html += '<div class="gp-items-container" data-gpid="'+gp.gp_id+'"><p class="text-muted">Loading items...</p></div>';
                html += '</div></div></div>';
            }
            $('#gatepass_list').html(html);

            // Load items when expanded
            $('[data-toggle="collapse"]').on('click', function(){
                var target = $(this).data('target');
                var gpId = target.replace('#gp_detail_','');
                var container = $(target).find('.gp-items-container');
                if(container.data('loaded')) return;
                container.data('loaded', true);
                loadGatePassItems(gpId, container);
            });
        }
    });
}

function loadGatePassItems(gpId, container){
    $.ajax({
        type: 'POST',
        url: BASE_URL + 'production/getGatePassItems',
        data: { gp_id: gpId },
        dataType: 'json',
        success: function(items){
            if(!items || items.length === 0){
                container.html('<p class="text-muted">No items in this gate pass.</p>');
                return;
            }
            var html = '<table class="table table-bordered table-sm" style="font-size:12px;">';
            html += '<thead class="bg-light"><tr><th>#</th><th>Material</th><th>UOM</th><th>Issued</th><th>Returned</th><th>Net Qty</th><th>Unit Price</th><th>Total</th><th>Action</th></tr></thead><tbody>';
            for(var i=0; i<items.length; i++){
                var it = items[i];
                var issued = parseFloat(it.gpitem_qty);
                var returned = parseFloat(it.gpitem_returned_qty);
                var net = issued - returned;
                var returnable = issued - returned;
                html += '<tr>';
                html += '<td>'+(i+1)+'</td>';
                html += '<td>'+it.itm_code+' - '+it.itm_name+'</td>';
                html += '<td>'+(it.itm_uom || it.gpitem_uom)+'</td>';
                html += '<td>'+issued.toFixed(2)+'</td>';
                html += '<td>'+(returned > 0 ? returned.toFixed(2) : '-')+'</td>';
                html += '<td><strong>'+net.toFixed(2)+'</strong></td>';
                html += '<td>'+parseFloat(it.gpitem_unit_price).toFixed(2)+'</td>';
                html += '<td>'+parseFloat(it.gpitem_total).toFixed(2)+'</td>';
                html += '<td>';
                if(returnable > 0){
                    html += '<button class="btn btn-warning btn-xs btn-return-mat" data-gpitemid="'+it.gpitem_id+'" data-gpid="'+gpId+'" data-max="'+returnable.toFixed(2)+'" data-name="'+it.itm_code+' - '+it.itm_name+'" data-uom="'+(it.itm_uom||it.gpitem_uom)+'"><i class="fa fa-undo"></i> Return</button>';
                } else {
                    html += '<span class="badge badge-success">Fully Returned</span>';
                }
                html += '</td></tr>';
            }
            html += '</tbody></table>';
            container.html(html);

            // Bind return buttons
            container.find('.btn-return-mat').click(function(){
                var gpitemId = $(this).data('gpitemid');
                var gpIdRet = $(this).data('gpid');
                var maxQty = $(this).data('max');
                var matName = $(this).data('name');
                var uom = $(this).data('uom');
                $('#return_gpitem_id').val(gpitemId);
                $('#return_gp_id').val(gpIdRet);
                $('#return_qty').val('').attr('max', maxQty);
                $('#return_info').html('<strong>'+matName+'</strong>');
                $('#return_max_label').text('Max returnable: ' + maxQty + ' ' + uom);
                $('#returnModal').modal('show');
            });
        }
    });
}

// Confirm return
$('#btn_confirm_return').click(function(){
    var returnQty = parseFloat($('#return_qty').val());
    var gpitemId = $('#return_gpitem_id').val();
    var gpId = $('#return_gp_id').val();
    if(!returnQty || returnQty <= 0){ alert('Enter valid return quantity'); return; }

    var btn = $(this);
    btn.prop('disabled', true);

    $.ajax({
        type: 'POST',
        url: BASE_URL + 'production/returnGatePassItem',
        data: {
            gpitem_id: gpitemId,
            return_qty: returnQty,
            gp_id: gpId
        },
        dataType: 'json',
        success: function(res){
            btn.prop('disabled', false);
            if(res.success){
                $('#returnModal').modal('hide');
                swal({type:'success', title:'Returned', text:'Material returned to warehouse successfully.'});
                loadGatePasses();
                loadMaterials();
                refreshCosts();
                loadRawMaterialsByStore($('#prod_store').val());
            } else {
                swal({type:'error', title:'Error', text: res.msg || 'Return failed'});
            }
        },
        error: function(){
            btn.prop('disabled', false);
            swal({type:'error', title:'Error', text:'Server error'});
        }
    });
});
</script>
