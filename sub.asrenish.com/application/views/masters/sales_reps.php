<div class="wrapper">
<div class="container-fluid">
    <?php if (!$ready): ?>
    <div class="alert alert-warning">The database needs updating before sales representatives can be added:
        open migrate.php and run <b>step 15</b>.</div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-4 col-md-5">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-user-circle"></i> <span id="sr_title">Add Sales Representative</span></h4>
                <input type="hidden" id="sr_id" value="">
                <div class="form-group">
                    <label>Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sr_name" maxlength="120" placeholder="As it should print on the bill">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control" id="sr_phone" maxlength="40">
                </div>
                <div class="form-group" id="sr_status_div" style="display:none;">
                    <label>Status</label>
                    <select class="form-control" id="sr_status">
                        <option value="1">Active</option>
                        <option value="0">Inactive (hidden on the sale window)</option>
                    </select>
                </div>
                <button type="button" class="btn btn-success btn-block" id="sr_save" <?php echo $ready ? '' : 'disabled'; ?>><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-secondary btn-block" id="sr_cancel" style="display:none;">Cancel Edit</button>
            </div>
        </div>
        <div class="col-lg-8 col-md-7">
            <div class="card-box clearfix">
                <h4 class="header-title m-t-0 m-b-15"><i class="fa fa-list"></i> Sales Representatives</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="srTable">
                        <thead class="bg-light"><tr><th>#</th><th>Name</th><th>Phone</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                        <?php foreach ($reps as $r): ?>
                            <tr>
                                <td><?php echo (int)$r->rep_id; ?></td>
                                <td><?php echo htmlspecialchars($r->rep_name); ?></td>
                                <td><?php echo $r->rep_phone !== '' && $r->rep_phone !== null ? htmlspecialchars($r->rep_phone) : '-'; ?></td>
                                <td><?php echo $r->rep_status ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>'; ?></td>
                                <td><button type="button" class="btn btn-sm btn-info sr-edit"
                                        data-id="<?php echo (int)$r->rep_id; ?>"
                                        data-name="<?php echo htmlspecialchars($r->rep_name, ENT_QUOTES); ?>"
                                        data-phone="<?php echo htmlspecialchars($r->rep_phone, ENT_QUOTES); ?>"
                                        data-status="<?php echo (int)$r->rep_status; ?>"><i class="fa fa-edit"></i></button></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
$(function(){
    var BASE = '<?php echo base_url(); ?>';
    function reset(){
        $('#sr_id,#sr_name,#sr_phone').val(''); $('#sr_status').val('1');
        $('#sr_status_div,#sr_cancel').hide(); $('#sr_title').text('Add Sales Representative');
    }
    $('#srTable').on('click', '.sr-edit', function(){
        var b = $(this);
        $('#sr_id').val(b.data('id')); $('#sr_name').val(b.data('name')); $('#sr_phone').val(b.data('phone'));
        $('#sr_status').val(String(b.data('status'))); $('#sr_status_div,#sr_cancel').show();
        $('#sr_title').text('Edit Sales Representative'); $('#sr_name').focus();
    });
    $('#sr_cancel').click(reset);
    $('#sr_save').click(function(){
        $.post(BASE + 'SalesReps/save', { id: $('#sr_id').val(), name: $('#sr_name').val(), phone: $('#sr_phone').val(),
                                          status: $('#sr_status').val() }, null, 'json')
         .then(function(r){
            if (!r.ok) { swal({ type: 'error', title: 'Not saved', text: r.msg }); return; }
            location.reload();
         }, function(x){ swal({ type: 'error', title: 'Not saved', text: 'The server did not answer (' + x.status + '). Try again.' }); });
    });
    $('#srTable').DataTable({ pageLength: 25, order: [] });
});
</script>
