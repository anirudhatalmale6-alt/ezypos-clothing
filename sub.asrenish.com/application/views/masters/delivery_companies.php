<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-md-5">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-truck"></i> Add Delivery Company</h4>
                <form id="dcForm">
                    <input type="hidden" id="edit_dc_id" value="">
                    <div class="form-group">
                        <label>Company Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dc_name" placeholder="e.g. DHL, FedEx">
                    </div>
                    <div class="form-group">
                        <label>Contact</label>
                        <input type="text" class="form-control" id="dc_contact" placeholder="Phone or email">
                    </div>
                    <div class="form-group" id="status_div" style="display:none;">
                        <label>Status</label>
                        <select class="form-control" id="dc_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-success btn-block" id="btn_save_dc">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary btn-block" id="btn_cancel_edit" style="display:none;">
                        Cancel Edit
                    </button>
                </form>
            </div>
        </div>
        <div class="col-lg-8 col-md-7">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-list"></i> Delivery Companies</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dcTable">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Company Name</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($companies) && $companies): foreach($companies as $c): ?>
                            <tr>
                                <td><?php echo $c->dc_id; ?></td>
                                <td><?php echo $c->dc_name; ?></td>
                                <td><?php echo $c->dc_contact ?: '-'; ?></td>
                                <td>
                                    <?php if($c->dc_status == 1): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-info btn-edit-dc"
                                        data-id="<?php echo $c->dc_id; ?>"
                                        data-name="<?php echo htmlspecialchars($c->dc_name); ?>"
                                        data-contact="<?php echo htmlspecialchars($c->dc_contact); ?>"
                                        data-status="<?php echo $c->dc_status; ?>">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <?php if($c->dc_status == 1): ?>
                                    <button class="btn btn-xs btn-danger btn-del-dc" data-id="<?php echo $c->dc_id; ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';

$('#btn_save_dc').click(function() {
    var name = $('#dc_name').val().trim();
    if (!name) { alert('Enter company name'); return; }
    var editId = $('#edit_dc_id').val();
    var url = editId ? BASE_URL + 'DeliveryCompany/update' : BASE_URL + 'DeliveryCompany/add';
    var postData = {
        name: name,
        contact: $('#dc_contact').val().trim()
    };
    if (editId) {
        postData.dc_id = editId;
        postData.status = $('#dc_status').val();
    }
    $.post(url, postData, function(res) {
        var data = JSON.parse(res);
        if (data.success) {
            location.reload();
        }
    });
});

$('.btn-edit-dc').click(function() {
    $('#edit_dc_id').val($(this).data('id'));
    $('#dc_name').val($(this).data('name'));
    $('#dc_contact').val($(this).data('contact'));
    $('#dc_status').val($(this).data('status'));
    $('#status_div').show();
    $('#btn_save_dc').html('<i class="fa fa-save"></i> Update');
    $('#btn_cancel_edit').show();
});

$('#btn_cancel_edit').click(function() {
    $('#edit_dc_id').val('');
    $('#dc_name, #dc_contact').val('');
    $('#status_div').hide();
    $('#btn_save_dc').html('<i class="fa fa-save"></i> Save');
    $('#btn_cancel_edit').hide();
});

$('.btn-del-dc').click(function() {
    var id = $(this).data('id');
    if (confirm('Deactivate this delivery company?')) {
        $.post(BASE_URL + 'DeliveryCompany/delete', { dc_id: id }, function() {
            location.reload();
        });
    }
});
</script>
