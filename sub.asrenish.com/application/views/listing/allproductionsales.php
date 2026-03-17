<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <div class="d-flex justify-content-between align-items-center m-b-20">
                    <h4 class="header-title m-t-0"><i class="fa fa-scissors"></i> All Production Sales (Tailoring Orders)</h4>
                    <a href="<?php echo base_url('add-production-sale'); ?>" class="btn btn-success">
                        <i class="fa fa-plus"></i> New Tailoring Order
                    </a>
                </div>
                <table id="ps_datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Customer</th>
                            <th>Store</th>
                            <th>Date</th>
                            <th>Delivery</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="ps_tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
$(function(){
    loadAllProdSales();
});

function loadAllProdSales(){
    $.ajax({
        type: 'POST',
        url: BASE_URL + 'ProductionSale/getAllOrders',
        dataType: 'json',
        success: function(data){
            var rows = '';
            if(data){
                for(var i=0; i<data.length; i++){
                    var d = data[i];

                    // Status dropdown
                    var statusCell = '';
                    if (d.prodsale_status == 'Delivered') {
                        statusCell = '<span class="badge badge-success">Delivered</span>';
                    } else {
                        statusCell = '<select class="form-control form-control-sm ps-status-select" data-id="' + d.prodsale_id + '" style="min-width:120px;">';
                        var statuses = ['Pending', 'Cutting', 'Stitching', 'Ready', 'Delivered'];
                        for (var s = 0; s < statuses.length; s++) {
                            statusCell += '<option value="' + statuses[s] + '"' + (statuses[s] == d.prodsale_status ? ' selected' : '') + '>' + statuses[s] + '</option>';
                        }
                        statusCell += '</select>';
                    }

                    rows += '<tr>'+
                        '<td>'+d.prodsale_id+'</td>'+
                        '<td>'+d.prodsale_code+'</td>'+
                        '<td>'+(d.cus_name || '-')+'</td>'+
                        '<td>'+(d.store_name || '-')+'</td>'+
                        '<td>'+d.prodsale_date+'</td>'+
                        '<td>'+(d.prodsale_delivery_date || '-')+'</td>'+
                        '<td style="text-align:right">'+parseFloat(d.prodsale_total).toFixed(2)+'</td>'+
                        '<td style="text-align:right">'+parseFloat(d.prodsale_paid).toFixed(2)+'</td>'+
                        '<td style="text-align:right">'+parseFloat(d.prodsale_balance).toFixed(2)+'</td>'+
                        '<td>'+statusCell+'</td>'+
                        '</tr>';
                }
            }
            $('#ps_tbody').html(rows);
            $('#ps_datatable').DataTable({
                "order": [[0, "desc"]],
                buttons: ['copy', 'excel', 'pdf'],
                destroy: true
            });

            // Bind status change
            $('.ps-status-select').off('change').on('change', function() {
                var psId = $(this).data('id');
                var newStatus = $(this).val();
                $.post(BASE_URL + 'ProductionSale/updateStatus', { prodsale_id: psId, status: newStatus }, function(res) {
                    swal({ title: 'Updated', text: 'Tailoring order status changed to ' + newStatus, type: 'success' });
                    loadAllProdSales();
                });
            });
        }
    });
}
</script>
