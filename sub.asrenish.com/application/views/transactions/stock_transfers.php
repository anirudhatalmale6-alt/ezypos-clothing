<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="wrapper">
<div class="container-fluid">

    <div class="row">
        <!-- ========== LEFT PANEL: Create Transfer Form ========== -->
        <div class="col-lg-4 col-md-5 col-sm-12">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-exchange"></i> New Stock Transfer</h4>
                <fieldset>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">From Store<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <select class="form-control" id="transfer_from_store">
                                <option value="0">-- Select Source --</option>
                                <?php if(isset($storeLoc) && $storeLoc): foreach($storeLoc as $s): ?>
                                <option value="<?php echo $s->store_id; ?>"><?php echo $s->store_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">To Store<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <select class="form-control" id="transfer_to_store">
                                <option value="0">-- Select Destination --</option>
                                <?php if(isset($storeLoc) && $storeLoc): foreach($storeLoc as $s): ?>
                                <option value="<?php echo $s->store_id; ?>"><?php echo $s->store_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-5 col-form-label">Notes</label>
                        <div class="col-7">
                            <textarea class="form-control" id="transfer_notes" rows="2" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <!-- Item Entry -->
                <h5 class="header-title m-t-0 m-b-10">Add Items</h5>
                <div class="form-group row">
                    <label class="col-4 col-form-label">Item<span class="text-danger">*</span></label>
                    <div class="col-8">
                        <input class="form-control" id="tf_item_search" placeholder="Search item..." autocomplete="off">
                        <input type="hidden" id="tf_item_id" value="">
                        <small id="tf_stock_info" class="text-muted"></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">Qty<span class="text-danger">*</span></label>
                    <div class="col-4">
                        <input type="text" class="form-control" id="tf_item_qty" placeholder="0">
                    </div>
                    <label class="col-1 col-form-label">Price</label>
                    <div class="col-3">
                        <input type="text" class="form-control" id="tf_item_price" placeholder="0.00" readonly style="background:#f5f5f5;">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-sm" id="btn_add_tf_item"><i class="fa fa-plus-square"></i> Add Item</button>
                        <button type="button" class="btn btn-secondary btn-sm" id="btn_reset_tf_item"><i class="fa fa-refresh"></i></button>
                    </div>
                </div>
                <!-- Items Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="tf_items_table">
                        <thead style="background-color:#C0C0C0;">
                            <tr>
                                <th style="font-size:12px;">#</th>
                                <th style="font-size:12px;display:none;">ID</th>
                                <th style="font-size:12px;">Item</th>
                                <th style="font-size:12px;">Qty</th>
                                <th style="font-size:12px;">Price</th>
                                <th style="font-size:12px;">Total</th>
                                <th style="font-size:12px;">Act</th>
                            </tr>
                        </thead>
                        <tbody id="tf_items_body"></tbody>
                    </table>
                </div>
                <hr>
                <div style="background:#f8f9fa;border-radius:4px;padding:10px;margin-bottom:10px;">
                    <div class="form-group row mb-0">
                        <label class="col-5 col-form-label" style="font-size:16px;"><strong>Grand Total:</strong></label>
                        <div class="col-7 col-form-label text-right" style="font-size:16px;">
                            <strong>LKR <span id="tf_grand_total">0.00</span></strong>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-right">
                    <button class="btn btn-success" id="btn_submit_transfer" disabled>
                        <i class="fa fa-paper-plane"></i> Submit Transfer
                    </button>
                </div>
                <!-- Hidden field for edit mode -->
                <input type="hidden" id="edit_transfer_id" value="">
            </div>
        </div>

        <!-- ========== RIGHT PANEL: Transfers List ========== -->
        <div class="col-lg-8 col-md-7 col-sm-12">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-list"></i> All Stock Transfers</h4>
                <div class="table-responsive">
                    <table id="transfers_datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr style="background-color:#C0C0C0;">
                                <th style="font-size:12px;">#ID</th>
                                <th style="font-size:12px;">From</th>
                                <th style="font-size:12px;">To</th>
                                <th style="font-size:12px;">Total</th>
                                <th style="font-size:12px;">Status</th>
                                <th style="font-size:12px;">Created By</th>
                                <th style="font-size:12px;">Date</th>
                                <th style="font-size:12px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="transfers_tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- ========== VIEW TRANSFER MODAL ========== -->
<div class="modal" id="viewTransferModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-exchange"></i> Transfer Details <span id="modal_tf_id"></span></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>From:</strong> <span id="modal_from_store"></span><br>
                        <strong>To:</strong> <span id="modal_to_store"></span><br>
                        <strong>Date:</strong> <span id="modal_date"></span>
                    </div>
                    <div class="col-6 text-right">
                        <strong>Status:</strong> <span id="modal_status_badge"></span><br>
                        <strong>Created By:</strong> <span id="modal_created_by"></span><br>
                        <strong>Accepted/Rejected By:</strong> <span id="modal_accepted_by"></span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12">
                        <strong>Notes:</strong> <span id="modal_notes" class="text-muted"></span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="modal_items_body"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Grand Total:</strong></td>
                                <td><strong>LKR <span id="modal_grand_total"></span></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer" id="modal_action_buttons">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="modal_btn_print"><i class="fa fa-print"></i> Print</button>
                <button type="button" class="btn btn-warning" id="modal_btn_edit" style="display:none;"><i class="fa fa-pencil"></i> Edit</button>
                <button type="button" class="btn btn-success" id="modal_btn_accept" style="display:none;"><i class="fa fa-check"></i> Accept</button>
                <button type="button" class="btn btn-danger" id="modal_btn_reject" style="display:none;"><i class="fa fa-times"></i> Reject</button>
            </div>
        </div>
    </div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
var IS_ADMIN = <?php echo $is_admin ? 'true' : 'false'; ?>;
var CURRENT_USER_ID = <?php echo (int)$user_id; ?>;
var USER_STORE_IDS = [];

// Store list for quick lookup
var storeList = [
    <?php if(isset($storeLoc) && $storeLoc): foreach($storeLoc as $s): ?>
    { id: <?php echo $s->store_id; ?>, name: '<?php echo addslashes($s->store_name); ?>' },
    <?php endforeach; endif; ?>
];

// Populate user store IDs
for (var si = 0; si < storeList.length; si++) {
    USER_STORE_IDS.push(storeList[si].id);
}

// Transfer items array for the create/edit form
var transferItems = [];
var tfItemCounter = 0;
var currentViewTransferId = 0;

// Item autocomplete data (loaded per store)
var storeItemsData = [];
var selectedItemStock = 0;

$(document).ready(function() {

    // ===================== LOAD ITEMS WHEN FROM STORE CHANGES =====================
    $('#transfer_from_store').change(function() {
        var storeId = $(this).val();
        storeItemsData = [];
        $('#tf_item_search').val('');
        $('#tf_item_id').val('');
        $('#tf_stock_info').text('');
        $('#tf_item_price').val('');

        if (!storeId || storeId == '0') return;

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'StockTransfer/getItemsByStore',
            data: { store_id: storeId },
            dataType: 'json',
            async: false,
            success: function(data) {
                if (data) {
                    for (var i = 0; i < data.length; i++) {
                        var m = data[i];
                        storeItemsData.push({
                            label: m.itm_code + ' - ' + m.itm_name + ' (Stock: ' + parseFloat(m.stock_qty).toFixed(0) + ')',
                            value: m.itm_id,
                            code: m.itm_code,
                            name: m.itm_name,
                            stock: parseFloat(m.stock_qty) || 0,
                            price: parseFloat(m.itm_sellingprice) || 0
                        });
                    }
                }
                $('#tf_item_search').autocomplete('option', 'source', storeItemsData);
            }
        });
    });

    // ===================== ITEM AUTOCOMPLETE =====================
    $('#tf_item_search').autocomplete({
        source: storeItemsData,
        minLength: 0,
        select: function(event, ui) {
            event.preventDefault();
            $('#tf_item_search').val(ui.item.code + ' - ' + ui.item.name);
            $('#tf_item_id').val(ui.item.value);
            selectedItemStock = ui.item.stock;
            $('#tf_stock_info').text('Available: ' + ui.item.stock);
            $('#tf_item_price').val(ui.item.price.toFixed(2));
        }
    });

    // ===================== ADD ITEM TO TABLE =====================
    $('#btn_add_tf_item').click(function() {
        var itemId = $('#tf_item_id').val();
        var itemLabel = $('#tf_item_search').val();
        var qty = parseFloat($('#tf_item_qty').val());
        var price = parseFloat($('#tf_item_price').val());

        if (!itemId) { swal({ type: 'error', title: 'Oops...', text: 'Please select an item!' }); return; }
        if (!qty || qty <= 0) { swal({ type: 'error', title: 'Oops...', text: 'Please enter a valid quantity!' }); return; }
        if (isNaN(price)) price = 0;

        if (qty > selectedItemStock) {
            swal({ type: 'warning', title: 'Insufficient Stock', text: 'Only ' + selectedItemStock + ' available at source store!' });
            return;
        }

        // Check if item already in table - merge qty
        var merged = false;
        for (var i = 0; i < transferItems.length; i++) {
            if (transferItems[i].item_id == itemId) {
                transferItems[i].qty = parseFloat(transferItems[i].qty) + qty;
                merged = true;
                break;
            }
        }

        if (!merged) {
            tfItemCounter++;
            // Extract item name from label (code - name)
            var itemName = itemLabel;
            var dashIdx = itemLabel.indexOf(' - ');
            if (dashIdx > -1) {
                itemName = itemLabel.substring(dashIdx + 3);
                // Remove stock info from name
                var parenIdx = itemName.lastIndexOf(' (Stock:');
                if (parenIdx > -1) itemName = itemName.substring(0, parenIdx);
            }
            transferItems.push({
                row_num: tfItemCounter,
                item_id: itemId,
                item_name: itemName,
                qty: qty,
                price: price
            });
        }

        renderTransferItemsTable();
        // Reset item fields
        $('#tf_item_search').val('');
        $('#tf_item_id').val('');
        $('#tf_item_qty').val('');
        $('#tf_item_price').val('');
        $('#tf_stock_info').text('');
        selectedItemStock = 0;
    });

    // Reset item fields
    $('#btn_reset_tf_item').click(function() {
        $('#tf_item_search').val('');
        $('#tf_item_id').val('');
        $('#tf_item_qty').val('');
        $('#tf_item_price').val('');
        $('#tf_stock_info').text('');
    });

    // ===================== SUBMIT TRANSFER =====================
    $('#btn_submit_transfer').click(function() {
        var fromStore = $('#transfer_from_store').val();
        var toStore = $('#transfer_to_store').val();
        var notes = $('#transfer_notes').val();
        var editId = $('#edit_transfer_id').val();

        if (!fromStore || fromStore == '0') {
            swal({ type: 'error', title: 'Oops...', text: 'Please select a source store!' }); return;
        }
        if (!toStore || toStore == '0') {
            swal({ type: 'error', title: 'Oops...', text: 'Please select a destination store!' }); return;
        }
        if (fromStore == toStore) {
            swal({ type: 'error', title: 'Oops...', text: 'Source and destination stores cannot be the same!' }); return;
        }
        if (transferItems.length == 0) {
            swal({ type: 'error', title: 'Oops...', text: 'Please add at least one item!' }); return;
        }

        var itemsPayload = JSON.stringify(transferItems);

        if (editId) {
            // Update existing transfer
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'StockTransfer/updateTransfer',
                data: { transfer_id: editId, notes: notes, items: itemsPayload },
                dataType: 'json',
                async: false,
                success: function(res) {
                    if (res.status == 'success') {
                        swal({ type: 'success', title: 'Updated!', text: 'Transfer updated successfully.' });
                        resetTransferForm();
                        loadTransfersList();
                    } else {
                        swal({ type: 'error', title: 'Error', text: res.message || 'Failed to update transfer' });
                    }
                },
                error: function() {
                    swal({ type: 'error', title: 'Error', text: 'Server error while updating transfer' });
                }
            });
        } else {
            // Create new transfer
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'StockTransfer/createTransfer',
                data: { from_store: fromStore, to_store: toStore, notes: notes, items: itemsPayload },
                dataType: 'json',
                async: false,
                success: function(res) {
                    if (res.status == 'success') {
                        swal({ type: 'success', title: 'Transfer Created!', text: 'Transfer #' + res.transfer_id + ' created. Stock reserved at source.' });
                        // Open print window
                        var horizontal = Math.floor(window.innerWidth / 2);
                        var left = horizontal - 200;
                        window.open(BASE_URL + 'StockTransfer/print_invoice/' + res.transfer_id, '_blank',
                            'toolbar=yes,scrollbars=yes,resizable=yes,top=40,left=' + left + ',width=500,height=600');
                        resetTransferForm();
                        loadTransfersList();
                    } else {
                        swal({ type: 'error', title: 'Error', text: res.message || 'Failed to create transfer' });
                    }
                },
                error: function() {
                    swal({ type: 'error', title: 'Error', text: 'Server error while creating transfer' });
                }
            });
        }
    });

    // ===================== LOAD TRANSFERS LIST =====================
    loadTransfersList();

    // ===================== MODAL: VIEW TRANSFER =====================
    $(document).on('click', '.btn-view-transfer', function() {
        var tfId = $(this).data('id');
        currentViewTransferId = tfId;
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'StockTransfer/getTransferDetails',
            data: { transfer_id: tfId },
            dataType: 'json',
            async: false,
            success: function(res) {
                if (res.status == 'success') {
                    var t = res.transfer;
                    var items = res.items;
                    $('#modal_tf_id').text('#' + t.transfer_id);
                    $('#modal_from_store').text(t.from_store_name || 'N/A');
                    $('#modal_to_store').text(t.to_store_name || 'N/A');
                    $('#modal_date').text(t.transfer_created_at);
                    $('#modal_created_by').text(t.created_by_name || 'N/A');
                    $('#modal_accepted_by').text(t.accepted_by_name || '-');
                    $('#modal_notes').text(t.transfer_notes || '-');

                    // Status badge
                    var statusClass = 'badge-warning';
                    if (t.transfer_status == 'Accepted') statusClass = 'badge-success';
                    else if (t.transfer_status == 'Rejected') statusClass = 'badge-danger';
                    $('#modal_status_badge').html('<span class="badge ' + statusClass + '" style="font-size:13px;">' + t.transfer_status + '</span>');

                    // Items table
                    var html = '';
                    var grandTotal = 0;
                    for (var i = 0; i < items.length; i++) {
                        var itm = items[i];
                        var lineTotal = parseFloat(itm.sti_qty) * parseFloat(itm.sti_price);
                        grandTotal += lineTotal;
                        html += '<tr>';
                        html += '<td>' + (i + 1) + '</td>';
                        html += '<td>' + (itm.itm_code || '-') + '</td>';
                        html += '<td>' + itm.sti_item_name + '</td>';
                        html += '<td style="text-align:right;">' + parseFloat(itm.sti_qty).toFixed(2) + '</td>';
                        html += '<td style="text-align:right;">' + parseFloat(itm.sti_price).toFixed(2) + '</td>';
                        html += '<td style="text-align:right;">' + lineTotal.toFixed(2) + '</td>';
                        html += '</tr>';
                    }
                    $('#modal_items_body').html(html);
                    $('#modal_grand_total').text(grandTotal.toFixed(2));

                    // Show/hide action buttons based on status and permissions
                    $('#modal_btn_edit, #modal_btn_accept, #modal_btn_reject').hide();
                    $('#modal_pending_hint').remove();
                    if (t.transfer_status == 'Pending') {
                        // Edit: creator or admin
                        if (IS_ADMIN || t.transfer_created_by == CURRENT_USER_ID) {
                            $('#modal_btn_edit').show();
                        }
                        // Accept/Reject: destination (receiving) store user or admin only.
                        // Stock is added to the destination ONLY after they approve.
                        var canAcceptReject = IS_ADMIN;
                        if (!IS_ADMIN) {
                            for (var u = 0; u < USER_STORE_IDS.length; u++) {
                                if (USER_STORE_IDS[u] == t.transfer_to_store) {
                                    canAcceptReject = true;
                                    break;
                                }
                            }
                        }
                        if (canAcceptReject) {
                            $('#modal_btn_accept').show();
                            $('#modal_btn_reject').show();
                        } else {
                            $('#modal_btn_accept').before('<div id="modal_pending_hint" class="text-warning mr-auto" style="align-self:center;"><i class="fa fa-clock-o"></i> Awaiting approval from the receiving store (' + (t.to_store_name || '') + ').</div>');
                        }
                    }

                    $('#viewTransferModal').modal('show');
                }
            }
        });
    });

    // ===================== MODAL: PRINT =====================
    $('#modal_btn_print').click(function() {
        var horizontal = Math.floor(window.innerWidth / 2);
        var left = horizontal - 200;
        window.open(BASE_URL + 'StockTransfer/print_invoice/' + currentViewTransferId, '_blank',
            'toolbar=yes,scrollbars=yes,resizable=yes,top=40,left=' + left + ',width=500,height=600');
    });

    // ===================== MODAL: EDIT =====================
    $('#modal_btn_edit').click(function() {
        $('#viewTransferModal').modal('hide');
        // Load transfer data into the form
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'StockTransfer/getTransferDetails',
            data: { transfer_id: currentViewTransferId },
            dataType: 'json',
            async: false,
            success: function(res) {
                if (res.status == 'success') {
                    var t = res.transfer;
                    var items = res.items;

                    $('#edit_transfer_id').val(t.transfer_id);
                    $('#transfer_from_store').val(t.transfer_from_store).prop('disabled', true);
                    $('#transfer_to_store').val(t.transfer_to_store).prop('disabled', true);
                    $('#transfer_notes').val(t.transfer_notes);

                    // Load items for this store
                    $('#transfer_from_store').trigger('change');

                    // Fill items table
                    transferItems = [];
                    tfItemCounter = 0;
                    for (var i = 0; i < items.length; i++) {
                        tfItemCounter++;
                        transferItems.push({
                            row_num: tfItemCounter,
                            item_id: items[i].sti_item_id,
                            item_name: items[i].sti_item_name,
                            qty: parseFloat(items[i].sti_qty),
                            price: parseFloat(items[i].sti_price)
                        });
                    }
                    renderTransferItemsTable();

                    // Change button text
                    $('#btn_submit_transfer').html('<i class="fa fa-save"></i> Update Transfer').prop('disabled', false);

                    // Scroll to top
                    $('html, body').animate({ scrollTop: 0 }, 300);
                }
            }
        });
    });

    // ===================== MODAL: ACCEPT =====================
    $('#modal_btn_accept').click(function() {
        swal({
            title: 'Accept Transfer?',
            text: 'Stock will be added to the destination store. This cannot be undone.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Accept',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: BASE_URL + 'StockTransfer/acceptTransfer',
                    data: { transfer_id: currentViewTransferId },
                    dataType: 'json',
                    async: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            swal({ type: 'success', title: 'Accepted!', text: 'Transfer accepted. Stock added to destination.' });
                            $('#viewTransferModal').modal('hide');
                            loadTransfersList();
                        } else {
                            swal({ type: 'error', title: 'Error', text: res.message || 'Failed to accept' });
                        }
                    }
                });
            }
        });
    });

    // ===================== MODAL: REJECT =====================
    $('#modal_btn_reject').click(function() {
        swal({
            title: 'Reject Transfer?',
            text: 'Stock will be returned to the source store.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Reject',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: BASE_URL + 'StockTransfer/rejectTransfer',
                    data: { transfer_id: currentViewTransferId },
                    dataType: 'json',
                    async: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            swal({ type: 'success', title: 'Rejected!', text: 'Transfer rejected. Stock returned to source.' });
                            $('#viewTransferModal').modal('hide');
                            loadTransfersList();
                        } else {
                            swal({ type: 'error', title: 'Error', text: res.message || 'Failed to reject' });
                        }
                    }
                });
            }
        });
    });

}); // end document.ready

// ===================== RENDER TRANSFER ITEMS TABLE =====================
function renderTransferItemsTable() {
    var html = '';
    var grandTotal = 0;
    for (var i = 0; i < transferItems.length; i++) {
        var itm = transferItems[i];
        var lineTotal = parseFloat(itm.qty) * parseFloat(itm.price);
        grandTotal += lineTotal;
        html += '<tr>';
        html += '<td>' + (i + 1) + '</td>';
        html += '<td style="display:none;">' + itm.item_id + '</td>';
        html += '<td>' + itm.item_name + '</td>';
        html += '<td style="text-align:right;">' + parseFloat(itm.qty).toFixed(2) + '</td>';
        html += '<td style="text-align:right;">' + parseFloat(itm.price).toFixed(2) + '</td>';
        html += '<td style="text-align:right;">' + lineTotal.toFixed(2) + '</td>';
        html += '<td><a href="javascript:;" class="btn btn-sm btn-danger btn-del-tf-item" data-idx="' + i + '"><i class="fa fa-times-rectangle-o"></i></a></td>';
        html += '</tr>';
    }
    $('#tf_items_body').html(html);
    $('#tf_grand_total').text(grandTotal.toFixed(2));

    // Enable/disable submit button
    $('#btn_submit_transfer').prop('disabled', transferItems.length === 0);

    // Bind delete
    $('.btn-del-tf-item').click(function() {
        var idx = parseInt($(this).data('idx'));
        transferItems.splice(idx, 1);
        renderTransferItemsTable();
    });
}

// ===================== LOAD TRANSFERS LIST =====================
function loadTransfersList() {
    $.ajax({
        type: 'POST',
        url: BASE_URL + 'StockTransfer/getAllTransfers',
        dataType: 'json',
        async: false,
        success: function(data) {
            var html = '';
            if (data && data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    var t = data[i];
                    var statusClass = 'badge-warning';
                    if (t.transfer_status == 'Accepted') statusClass = 'badge-success';
                    else if (t.transfer_status == 'Rejected') statusClass = 'badge-danger';

                    html += '<tr>';
                    html += '<td>' + t.transfer_id + '</td>';
                    html += '<td>' + (t.from_store_name || '') + '</td>';
                    html += '<td>' + (t.to_store_name || '') + '</td>';
                    html += '<td style="text-align:right;">' + parseFloat(t.transfer_total || 0).toFixed(2) + '</td>';
                    html += '<td><span class="badge ' + statusClass + '">' + t.transfer_status + '</span></td>';
                    html += '<td>' + (t.created_by_name || '') + '</td>';
                    html += '<td>' + (t.transfer_created_at || '') + '</td>';
                    html += '<td>';
                    html += '<button class="btn btn-sm btn-info btn-view-transfer" data-id="' + t.transfer_id + '" title="View"><i class="fa fa-eye"></i></button> ';
                    html += '</td>';
                    html += '</tr>';
                }
            }
            $('#transfers_tbody').html(html);
            // Re-initialize DataTable
            if ($.fn.DataTable.isDataTable('#transfers_datatable')) {
                $('#transfers_datatable').DataTable().destroy();
            }
            $('#transfers_datatable').DataTable({
                order: [[0, 'desc']],
                pageLength: 15,
                responsive: true,
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'pdf', 'print']
            });
        }
    });
}

// ===================== RESET FORM =====================
function resetTransferForm() {
    $('#edit_transfer_id').val('');
    $('#transfer_from_store').val('0').prop('disabled', false);
    $('#transfer_to_store').val('0').prop('disabled', false);
    $('#transfer_notes').val('');
    $('#tf_item_search').val('');
    $('#tf_item_id').val('');
    $('#tf_item_qty').val('');
    $('#tf_item_price').val('');
    $('#tf_stock_info').text('');
    transferItems = [];
    tfItemCounter = 0;
    storeItemsData = [];
    renderTransferItemsTable();
    $('#btn_submit_transfer').html('<i class="fa fa-paper-plane"></i> Submit Transfer').prop('disabled', true);
}
</script>
