<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <!-- Left Panel: Add/Edit Category -->
        <div class="col-lg-4 col-md-5">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-gift"></i> Voucher Category</h4>
                <form id="catForm">
                    <input type="hidden" id="edit_vcat_id" value="">
                    <div class="form-group">
                        <label>Category Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="vcat_name" placeholder="e.g. Gift Voucher Rs.1000">
                    </div>
                    <div class="form-group">
                        <label>Value (LKR)<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="vcat_value" placeholder="1000" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Barcode (common for all cards in this tier)</label>
                        <input type="text" class="form-control" id="vcat_barcode" placeholder="e.g. GV1000">
                    </div>
                    <div class="form-group">
                        <div class="checkbox checkbox-primary">
                            <input id="vcat_is_oneoff" type="checkbox" checked>
                            <label for="vcat_is_oneoff">One-time use (card fully consumed even if sale &lt; value)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox checkbox-primary">
                            <input id="vcat_has_expiry" type="checkbox">
                            <label for="vcat_has_expiry">Has expiry</label>
                        </div>
                    </div>
                    <div class="form-group" id="expiry_days_div" style="display:none;">
                        <label>Expires after (days from creation)</label>
                        <input type="number" class="form-control" id="vcat_expiry_days" placeholder="365" min="1">
                    </div>
                    <button type="button" class="btn btn-success btn-block" id="btn_save_cat">
                        <i class="fa fa-save"></i> Save Category
                    </button>
                    <button type="button" class="btn btn-secondary btn-block" id="btn_cancel_edit" style="display:none;">
                        Cancel Edit
                    </button>
                </form>
                <hr>
                <!-- Generate Cards -->
                <h5 class="header-title m-t-15 m-b-15"><i class="fa fa-plus-square"></i> Generate Cards</h5>
                <div class="form-group">
                    <label>Select Category</label>
                    <select class="form-control" id="gen_vcat_id">
                        <option value="">-- Select --</option>
                        <?php if(isset($categories)): foreach($categories as $c): ?>
                        <option value="<?php echo $c->vcat_id; ?>"><?php echo $c->vcat_name; ?> (LKR <?php echo number_format($c->vcat_value, 2); ?>)</option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>How many cards?</label>
                    <input type="number" class="form-control" id="gen_count" placeholder="e.g. 50" min="1" max="500">
                </div>
                <button type="button" class="btn btn-primary btn-block" id="btn_generate">
                    <i class="fa fa-cogs"></i> Generate Cards
                </button>
            </div>
        </div>

        <!-- Right Panel: Categories & Cards -->
        <div class="col-lg-8 col-md-7">
            <!-- Category Summary -->
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-list"></i> Voucher Categories</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="catTable">
                        <thead class="bg-light">
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Barcode</th>
                                <th>Type</th>
                                <th>Expiry</th>
                                <th>Available</th>
                                <th>Sold</th>
                                <th>Redeemed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="catTableBody"></tbody>
                    </table>
                </div>
            </div>

            <!-- Cards List -->
            <div class="card-box clearfix">
                <div class="d-flex justify-content-between align-items-center m-b-15">
                    <h4 class="header-title m-t-0"><i class="fa fa-credit-card"></i> Gift Cards</h4>
                    <select class="form-control" id="filter_status" style="width:150px;">
                        <option value="">All</option>
                        <option value="Available">Available</option>
                        <option value="Sold">Sold</option>
                        <option value="Redeemed">Redeemed</option>
                        <option value="Expired">Expired</option>
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped" id="cardsTable">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Card Number</th>
                                <th>Category</th>
                                <th>Value</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Sold Date</th>
                                <th>Expiry</th>
                            </tr>
                        </thead>
                        <tbody id="cardsTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';

$(document).ready(function() {
    loadCategorySummary();
    loadCards();

    // Toggle expiry days
    $('#vcat_has_expiry').change(function() {
        $('#expiry_days_div').toggle(this.checked);
    });

    // Save category
    $('#btn_save_cat').click(function() {
        var name = $('#vcat_name').val().trim();
        var value = $('#vcat_value').val();
        if (!name) { alert('Enter category name'); return; }
        if (!value || value <= 0) { alert('Enter valid value'); return; }

        var editId = $('#edit_vcat_id').val();
        var url = editId ? BASE_URL + 'GiftVoucher/updateCategory' : BASE_URL + 'GiftVoucher/addCategory';
        var postData = {
            name: name,
            value: value,
            barcode: $('#vcat_barcode').val().trim(),
            is_oneoff: $('#vcat_is_oneoff').is(':checked') ? 1 : 0,
            has_expiry: $('#vcat_has_expiry').is(':checked') ? 1 : 0,
            expiry_days: $('#vcat_expiry_days').val()
        };
        if (editId) postData.vcat_id = editId;

        $.post(url, postData, function(res) {
            var data = JSON.parse(res);
            if (data.success) {
                swal({ title: 'Saved', text: 'Voucher category saved!', type: 'success' });
                resetCatForm();
                loadCategorySummary();
                location.reload(); // Refresh to update generate dropdown
            }
        });
    });

    // Cancel edit
    $('#btn_cancel_edit').click(function() {
        resetCatForm();
    });

    // Generate cards
    $('#btn_generate').click(function() {
        var vcatId = $('#gen_vcat_id').val();
        var count = parseInt($('#gen_count').val());
        if (!vcatId) { alert('Select a category'); return; }
        if (!count || count < 1) { alert('Enter number of cards'); return; }

        swal({
            title: 'Generate ' + count + ' cards?',
            text: 'This will create ' + count + ' new gift cards for the selected category.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Generate'
        }).then(function(result) {
            if (result.value) {
                $.post(BASE_URL + 'GiftVoucher/generateCards', { vcat_id: vcatId, count: count }, function(res) {
                    var data = JSON.parse(res);
                    if (data.success) {
                        swal({ title: 'Done', text: data.count + ' cards generated!', type: 'success' });
                        loadCards();
                        loadCategorySummary();
                        $('#gen_count').val('');
                    } else {
                        swal({ title: 'Error', text: data.msg, type: 'error' });
                    }
                });
            }
        });
    });

    // Filter cards by status
    $('#filter_status').change(function() {
        loadCards($(this).val());
    });
});

function loadCategorySummary() {
    $.post(BASE_URL + 'GiftVoucher/getCategorySummary', function(res) {
        var data = JSON.parse(res);
        var html = '';
        for (var i = 0; i < data.length; i++) {
            var c = data[i];
            html += '<tr>';
            html += '<td>' + c.vcat_name + '</td>';
            html += '<td>LKR ' + parseFloat(c.vcat_value).toFixed(2) + '</td>';
            html += '<td>' + (c.vcat_barcode || '-') + '</td>';
            html += '<td>' + (c.vcat_is_oneoff == 1 ? '<span class="badge badge-warning">One-Time</span>' : '<span class="badge badge-info">Multi-Use</span>') + '</td>';
            html += '<td>' + (c.vcat_has_expiry == 1 ? c.vcat_expiry_days + ' days' : 'Never') + '</td>';
            html += '<td><span class="badge badge-success">' + (c.available_cards || 0) + '</span></td>';
            html += '<td><span class="badge badge-primary">' + (c.sold_cards || 0) + '</span></td>';
            html += '<td><span class="badge badge-secondary">' + (c.redeemed_cards || 0) + '</span></td>';
            html += '<td>';
            html += '<button class="btn btn-xs btn-info btn-edit-cat" data-id="' + c.vcat_id + '" data-name="' + c.vcat_name + '" data-value="' + c.vcat_value + '" data-barcode="' + (c.vcat_barcode || '') + '" data-oneoff="' + c.vcat_is_oneoff + '" data-expiry="' + c.vcat_has_expiry + '" data-days="' + (c.vcat_expiry_days || '') + '"><i class="fa fa-pencil"></i></button> ';
            html += '<button class="btn btn-xs btn-danger btn-del-cat" data-id="' + c.vcat_id + '"><i class="fa fa-trash"></i></button>';
            html += '</td>';
            html += '</tr>';
        }
        $('#catTableBody').html(html);

        // Edit category
        $('.btn-edit-cat').click(function() {
            $('#edit_vcat_id').val($(this).data('id'));
            $('#vcat_name').val($(this).data('name'));
            $('#vcat_value').val($(this).data('value'));
            $('#vcat_barcode').val($(this).data('barcode'));
            $('#vcat_is_oneoff').prop('checked', $(this).data('oneoff') == 1);
            $('#vcat_has_expiry').prop('checked', $(this).data('expiry') == 1);
            $('#vcat_expiry_days').val($(this).data('days'));
            $('#expiry_days_div').toggle($(this).data('expiry') == 1);
            $('#btn_save_cat').html('<i class="fa fa-save"></i> Update Category');
            $('#btn_cancel_edit').show();
        });

        // Delete category
        $('.btn-del-cat').click(function() {
            var id = $(this).data('id');
            if (confirm('Deactivate this category?')) {
                $.post(BASE_URL + 'GiftVoucher/deleteCategory', { vcat_id: id }, function() {
                    loadCategorySummary();
                    location.reload();
                });
            }
        });
    });
}

function loadCards(status) {
    var postData = {};
    if (status) postData.status = status;
    $.post(BASE_URL + 'GiftVoucher/getAllCards', postData, function(res) {
        var data = JSON.parse(res);
        var html = '';
        for (var i = 0; i < data.length; i++) {
            var c = data[i];
            var statusClass = 'secondary';
            if (c.gc_status == 'Available') statusClass = 'success';
            else if (c.gc_status == 'Sold') statusClass = 'primary';
            else if (c.gc_status == 'Redeemed') statusClass = 'info';
            else if (c.gc_status == 'Expired') statusClass = 'danger';

            html += '<tr>';
            html += '<td>' + c.gc_id + '</td>';
            html += '<td><strong>' + c.gc_card_number + '</strong></td>';
            html += '<td>' + c.vcat_name + '</td>';
            html += '<td>LKR ' + parseFloat(c.gc_original_value).toFixed(2) + '</td>';
            html += '<td>LKR ' + parseFloat(c.gc_remaining_value).toFixed(2) + '</td>';
            html += '<td><span class="badge badge-' + statusClass + '">' + c.gc_status + '</span></td>';
            html += '<td>' + (c.gc_sold_date ? c.gc_sold_date.substring(0, 10) : '-') + '</td>';
            html += '<td>' + (c.gc_expiry_date || 'Never') + '</td>';
            html += '</tr>';
        }
        $('#cardsTableBody').html(html);
        if ($.fn.DataTable.isDataTable('#cardsTable')) {
            $('#cardsTable').DataTable().destroy();
        }
        $('#cardsTable').DataTable({ order: [[0, 'desc']], pageLength: 25 });
    });
}

function resetCatForm() {
    $('#edit_vcat_id').val('');
    $('#vcat_name, #vcat_value, #vcat_barcode, #vcat_expiry_days').val('');
    $('#vcat_is_oneoff').prop('checked', true);
    $('#vcat_has_expiry').prop('checked', false);
    $('#expiry_days_div').hide();
    $('#btn_save_cat').html('<i class="fa fa-save"></i> Save Category');
    $('#btn_cancel_edit').hide();
}
</script>
