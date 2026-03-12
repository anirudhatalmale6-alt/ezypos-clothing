<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card-box clearfix">
                <div class="d-flex justify-content-between align-items-center m-b-20">
                    <h4 class="header-title m-t-0"><i class="fa fa-industry"></i> All Productions</h4>
                    <a href="<?php echo base_url('add-production'); ?>" class="btn btn-success">
                        <i class="fa fa-plus"></i> New Production
                    </a>
                </div>
                <div class="table-responsive">
                    <table id="productionsTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Date</th>
                                <th>Output Item</th>
                                <th>Qty</th>
                                <th>Type</th>
                                <th>Tailor</th>
                                <th>Total Cost</th>
                                <th>Unit Cost</th>
                                <th>Status</th>
                                <th>GRN</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
    loadProductions();
});

function loadProductions() {
    $.ajax({
        type: 'POST',
        url: BASE_URL + 'production/loadAllProductions',
        dataType: 'json',
        success: function(data) {
            var html = '';
            $.each(data, function(i, p) {
                var statusClass = 'secondary';
                if (p.prod_status == 'Issued') statusClass = 'info';
                else if (p.prod_status == 'In-Progress') statusClass = 'warning';
                else if (p.prod_status == 'Completed') statusClass = 'success';
                else if (p.prod_status == 'Cancelled') statusClass = 'danger';

                // GRN link
                var grnInfo = '-';
                if (p.prod_grn_id && p.prod_grn_id > 0) {
                    grnInfo = '<span class="badge badge-success">GRN #' + p.prod_grn_id + '</span>';
                }

                // Status dropdown (only if not Completed or Cancelled)
                var statusCell = '';
                if (p.prod_status == 'Completed' || p.prod_status == 'Cancelled') {
                    statusCell = '<span class="badge badge-' + statusClass + '">' + p.prod_status + '</span>';
                } else {
                    statusCell = '<select class="form-control form-control-sm prod-status-select" data-id="' + p.prod_id + '" style="min-width:130px;">';
                    var statuses = ['Issued', 'In-Progress', 'Completed', 'Cancelled'];
                    for (var s = 0; s < statuses.length; s++) {
                        statusCell += '<option value="' + statuses[s] + '"' + (statuses[s] == p.prod_status ? ' selected' : '') + '>' + statuses[s] + '</option>';
                    }
                    statusCell += '</select>';
                }

                html += '<tr>';
                html += '<td>' + p.prod_id + '</td>';
                html += '<td>' + p.prod_code + '</td>';
                html += '<td>' + p.prod_date + '</td>';
                html += '<td>' + (p.output_item_code || '') + ' - ' + (p.output_item_name || '') + '</td>';
                html += '<td>' + parseFloat(p.prod_output_qty).toFixed(0) + '</td>';
                html += '<td>' + p.prod_type + '</td>';
                html += '<td>' + (p.tailor_name || 'In-House') + '</td>';
                html += '<td>LKR ' + parseFloat(p.prod_total_cost).toFixed(2) + '</td>';
                html += '<td>LKR ' + parseFloat(p.prod_unit_cost).toFixed(2) + '</td>';
                html += '<td>' + statusCell + '</td>';
                html += '<td>' + grnInfo + '</td>';
                html += '<td><a href="' + BASE_URL + 'add-production" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a></td>';
                html += '</tr>';
            });
            $('#productionsTable tbody').html(html);
            $('#productionsTable').DataTable({
                destroy: true,
                order: [[0, 'desc']]
            });

            // Bind status change
            $('.prod-status-select').off('change').on('change', function() {
                var prodId = $(this).data('id');
                var newStatus = $(this).val();
                var $select = $(this);

                if (newStatus == 'Completed') {
                    Swal.fire({
                        title: 'Complete Production?',
                        text: 'This will create a GRN for the finished garments and add them to stock. This cannot be undone.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Complete',
                        cancelButtonText: 'Cancel'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            updateProductionStatus(prodId, newStatus);
                        } else {
                            // Revert selection
                            loadProductions();
                        }
                    });
                } else if (newStatus == 'Cancelled') {
                    Swal.fire({
                        title: 'Cancel Production?',
                        text: 'Are you sure you want to cancel this production?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Cancel It',
                        cancelButtonText: 'No'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            updateProductionStatus(prodId, newStatus);
                        } else {
                            loadProductions();
                        }
                    });
                } else {
                    updateProductionStatus(prodId, newStatus);
                }
            });
        }
    });
}

function updateProductionStatus(prodId, status) {
    $.post(BASE_URL + 'production/updateStatus', { prod_id: prodId, status: status }, function(res) {
        Swal.fire('Updated', 'Production status changed to ' + status, 'success');
        loadProductions();
    });
}
</script>
