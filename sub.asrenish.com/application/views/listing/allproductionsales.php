<div class="wrapper">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-scissors"></i> All Production Sales (Tailoring Orders)</h4>
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
$(function(){
    loadAllProdSales();
    function loadAllProdSales(){
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("ProductionSale/getAllOrders"); ?>',
            dataType: 'json',
            success: function(data){
                var rows = '';
                if(data){
                    for(var i=0; i<data.length; i++){
                        var d = data[i];
                        var statusClass = 'default';
                        switch(d.prodsale_status){
                            case 'Pending': statusClass = 'secondary'; break;
                            case 'Cutting': statusClass = 'warning'; break;
                            case 'Stitching': statusClass = 'info'; break;
                            case 'Ready': statusClass = 'primary'; break;
                            case 'Delivered': statusClass = 'success'; break;
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
                            '<td><span class="badge badge-'+statusClass+'">'+d.prodsale_status+'</span></td>'+
                            '</tr>';
                    }
                }
                $('#ps_tbody').html(rows);
                $('#ps_datatable').DataTable({
                    "order": [[0, "desc"]],
                    buttons: ['copy', 'excel', 'pdf'],
                    destroy: true
                });
            }
        });
    }
});
</script>
