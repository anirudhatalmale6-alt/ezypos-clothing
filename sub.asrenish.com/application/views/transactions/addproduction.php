<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <!-- Left Panel: Production Details -->
        <div class="col-lg-4 col-md-5 col-sm-12">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-industry"></i> New Production Order</h4>
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
                        <label class="col-5 col-form-label">Store<span class="text-danger">*</span></label>
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
                        <button class="btn btn-success btn-block" id="btn_create_production">
                            <i class="fa fa-plus"></i> Create Production Order
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
var currentProdId = null;

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

    // Material autocomplete search
    var rawMaterials = [
        <?php if($rawMaterials): foreach($rawMaterials as $mat): ?>
        { label: "<?php echo addslashes($mat->itm_code . ' - ' . $mat->itm_name . ' (Stock: ' . $mat->stock_qty . ' ' . $mat->itm_uom . ')'); ?>",
          value: "<?php echo $mat->itm_id; ?>",
          code: "<?php echo addslashes($mat->itm_code); ?>",
          name: "<?php echo addslashes($mat->itm_name); ?>",
          stock: <?php echo ($mat->stock_qty ? $mat->stock_qty : 0); ?>,
          uom: "<?php echo addslashes($mat->itm_uom); ?>" },
        <?php endforeach; endif; ?>
    ];
    var selectedMatStock = 0;
    var selectedMatUom = '';
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
            // Auto-fetch price
            $.post(BASE_URL + 'production/getMaterialPrice', { item_id: ui.item.value }, function(res) {
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
                prod_notes: $('#prod_notes').val()
            },
            dataType: 'json',
            success: function(prodId) {
                if (prodId > 0) {
                    currentProdId = prodId;
                    // Disable header fields
                    $('#prod_code, #prod_date, #output_item, #output_item_search, #output_qty, #prod_type, #tailor_id').prop('disabled', true);
                    $('#btn_create_production').hide();
                    // Show material & cost sections
                    $('#material_section, #cost_section, #status_section, #status_buttons').show();
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
        $('#material_cost_lbl').text(parseFloat(prod.prod_material_cost).toFixed(2));
        $('#tailoring_cost_lbl').text(parseFloat(prod.prod_tailoring_cost).toFixed(2));
        $('#other_cost_lbl').text(parseFloat(prod.prod_other_cost).toFixed(2));
        $('#total_cost_lbl').text(parseFloat(prod.prod_total_cost).toFixed(2));
        $('#unit_cost_lbl').text(parseFloat(prod.prod_unit_cost).toFixed(2));
    });
}
</script>
