        <!-- Start right Content here -->
        <div class="wrapper">
            <div class="container">
                <!-- Add Payment Method Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2"></div>
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30"><i class="fa fa-credit-card"></i> Add Payment Method</h4>
                            <form id="pmForm" action="#" method="post">
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Name<span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <input class="form-control" type="text" placeholder="e.g. Visa, FriMi, PayHere"
                                        name="name" id="pm_name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Commission %</label>
                                    <div class="col-8">
                                        <input class="form-control" type="number" step="0.01" min="0" max="100"
                                        placeholder="e.g. 2.50" name="commission_pct" id="pm_commission_pct" value="0.00">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Fixed Fee (LKR)</label>
                                    <div class="col-8">
                                        <input class="form-control" type="number" step="0.01" min="0"
                                        placeholder="e.g. 10.00" name="commission_fixed" id="pm_commission_fixed" value="0.00">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <small class="text-muted">
                                            <i class="fa fa-info-circle"></i> Commission = (Sale Amount × %) + Fixed Fee.
                                            E.g. 2.5% + LKR 10 on a LKR 5000 sale = LKR 135.00
                                        </small>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary waves-effect"><i class="fa fa-plus"></i> Add</button>
                                <button type="reset" class="btn btn-secondary waves-effect">Reset</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive">
                            <h4 class="header-title m-t-0 m-b-30"><i class="fa fa-list"></i> All Payment Methods</h4>
                            <table id="pmTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Commission %</th>
                                    <th>Fixed Fee (LKR)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="pmTbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal" id="editPmModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Payment Method</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <form id="editPmForm" action="#" method="post">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_pm_id">
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Name<span class="text-danger">*</span></label>
                                    <div class="col-8">
                                        <input class="form-control" type="text" name="name" id="edit_pm_name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Commission %</label>
                                    <div class="col-8">
                                        <input class="form-control" type="number" step="0.01" min="0" max="100"
                                        name="commission_pct" id="edit_pm_commission_pct">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Fixed Fee (LKR)</label>
                                    <div class="col-8">
                                        <input class="form-control" type="number" step="0.01" min="0"
                                        name="commission_fixed" id="edit_pm_commission_fixed">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Status</label>
                                    <div class="col-8">
                                        <select class="form-control" name="status" id="edit_pm_status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

<script>
$(function(){
    loadPaymentMethods();

    // Add payment method
    $('#pmForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '<?php echo base_url("sales/addPaymentMethod"); ?>',
            data: $('#pmForm').serialize(),
            dataType: 'json',
            success: function(id){
                if(id > 0){
                    alert('Payment method added!');
                    $('#pmForm')[0].reset();
                    loadPaymentMethods();
                }
            },
            error: function(){ alert('Error adding payment method'); }
        });
    });

    // Edit
    $('#pmTbody').on('click', '.pm-edit', function(){
        var row = $(this).closest('tr');
        $('#edit_pm_id').val(row.data('id'));
        $('#edit_pm_name').val(row.data('name'));
        $('#edit_pm_commission_pct').val(row.data('pct'));
        $('#edit_pm_commission_fixed').val(row.data('fixed'));
        $('#edit_pm_status').val(row.data('status'));
        $('#editPmModal').modal('show');
    });

    // Update
    $('#editPmForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '<?php echo base_url("sales/updatePaymentMethod"); ?>',
            data: $('#editPmForm').serialize(),
            dataType: 'json',
            success: function(){
                alert('Updated!');
                $('#editPmModal').modal('hide');
                loadPaymentMethods();
            },
            error: function(){ alert('Error updating'); }
        });
    });

    // Delete
    $('#pmTbody').on('click', '.pm-delete', function(){
        if(!confirm('Delete this payment method?')) return;
        var id = $(this).closest('tr').data('id');
        $.ajax({
            type: 'post',
            url: '<?php echo base_url("sales/deletePaymentMethod"); ?>',
            data: {id: id},
            dataType: 'json',
            success: function(){
                alert('Deleted!');
                loadPaymentMethods();
            }
        });
    });
});

function loadPaymentMethods(){
    $.ajax({
        type: 'post',
        url: '<?php echo base_url("sales/getPaymentMethods"); ?>',
        dataType: 'json',
        success: function(data){
            var html = '';
            for(var i = 0; i < data.length; i++){
                var m = data[i];
                var statusBadge = m.pm_status == 1
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-danger">Inactive</span>';
                html += '<tr data-id="'+m.pm_id+'" data-name="'+m.pm_name+'" data-pct="'+m.pm_commission_pct+'" data-fixed="'+m.pm_commission_fixed+'" data-status="'+m.pm_status+'">' +
                    '<td>'+m.pm_id+'</td>' +
                    '<td><i class="fa fa-credit-card"></i> '+m.pm_name+'</td>' +
                    '<td>'+m.pm_commission_pct+'%</td>' +
                    '<td>LKR '+parseFloat(m.pm_commission_fixed).toFixed(2)+'</td>' +
                    '<td>'+statusBadge+'</td>' +
                    '<td>' +
                        '<a href="javascript:;" class="btn btn-sm btn-info pm-edit"><i class="fa fa-edit"></i></a> ' +
                        '<a href="javascript:;" class="btn btn-sm btn-danger pm-delete"><i class="fa fa-trash"></i></a>' +
                    '</td>' +
                    '</tr>';
            }
            $('#pmTbody').html(html);
        }
    });
}
</script>
