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
$(document).ready(function() {
    loadProductions();
});

function loadProductions() {
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("production/loadAllProductions"); ?>',
        dataType: 'json',
        success: function(data) {
            var html = '';
            $.each(data, function(i, p) {
                var statusClass = 'secondary';
                if (p.prod_status == 'Issued') statusClass = 'info';
                else if (p.prod_status == 'In-Progress') statusClass = 'warning';
                else if (p.prod_status == 'Completed') statusClass = 'success';
                else if (p.prod_status == 'Cancelled') statusClass = 'danger';

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
                html += '<td><span class="badge badge-' + statusClass + '">' + p.prod_status + '</span></td>';
                html += '</tr>';
            });
            $('#productionsTable tbody').html(html);
            $('#productionsTable').DataTable({
                destroy: true,
                order: [[0, 'desc']]
            });
        }
    });
}
</script>
