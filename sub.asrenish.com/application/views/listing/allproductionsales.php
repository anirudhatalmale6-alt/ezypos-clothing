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
                            <th>Pickup Store</th>
                            <th>Date</th>
                            <th>Delivery</th>
                            <th style="text-align:right;">Total</th>
                            <th style="text-align:right;">Paid</th>
                            <th style="text-align:right;">Balance</th>
                            <th>Status</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody id="ps_tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Payment Modal for Listing -->
<div class="modal" id="listPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listPaymentTitle">Add Payment</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Payment Method</label>
                    <select class="form-control" id="list_pm_method">
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Credit Card">Credit Card</option>
                    </select>
                </div>
                <div class="form-group" id="list_card_ref_group" style="display:none;">
                    <label>Card Number / Reference</label>
                    <input type="text" class="form-control" id="list_card_ref" placeholder="Enter card number">
                </div>
                <div class="form-group">
                    <label>Payment Amount</label>
                    <input type="number" class="form-control" id="list_pm_amount" step="0.01" placeholder="0.00">
                </div>
                <div style="background:#fff3e0;padding:8px;border-radius:4px;">
                    <strong>Balance: LKR <span id="list_pm_balance">0.00</span></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-success" id="list_btn_confirm_payment">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>

<script>
var BASE_URL = '<?php echo base_url(); ?>';
var listPayPsId = 0;
var listPayDeliverAfter = false;

$(function(){
    loadAllProdSales();

    $('#list_pm_method').change(function(){
        if($(this).val() === 'Credit Card'){
            $('#list_card_ref_group').show();
        } else {
            $('#list_card_ref_group').hide();
        }
    });

    $('#list_btn_confirm_payment').click(function(){
        var amt = parseFloat($('#list_pm_amount').val());
        if(!amt || amt <= 0){ swal({type:'error', title:'Error', text:'Enter valid amount'}); return; }
        var method = $('#list_pm_method').val();
        var cardRef = $('#list_card_ref').val().trim();
        if(method === 'Credit Card' && !cardRef){
            swal({type:'error', title:'Error', text:'Please enter the card number'}); return;
        }

        $.post(BASE_URL + 'ProductionSale/addPayment', {
            prodsale_id: listPayPsId, amount: amt, method: method, card_ref: cardRef
        }, function(){
            $('#listPaymentModal').modal('hide');

            if(listPayDeliverAfter){
                var curBal = parseFloat($('#list_pm_balance').text()) || 0;
                var newBal = curBal - amt;
                if(newBal <= 0){
                    $.post(BASE_URL + 'ProductionSale/updateStatus', { prodsale_id: listPayPsId, status: 'Delivered' }, function(){
                        swal({type:'success', title:'Delivered!', text:'Payment settled and order delivered.'});
                        loadAllProdSales();
                    });
                } else {
                    swal({type:'warning', title:'Partial Payment', text:'Balance remaining: LKR ' + newBal.toFixed(2) + '. Order cannot be delivered until fully paid.'});
                    loadAllProdSales();
                }
            } else {
                swal({type:'success', title:'Payment Added', text: method + ' - LKR ' + amt.toFixed(2)});
                loadAllProdSales();
            }
        });
    });
});

function openListPayment(psId, balance, deliverAfter){
    listPayPsId = psId;
    listPayDeliverAfter = deliverAfter || false;
    $('#list_pm_balance').text(parseFloat(balance).toFixed(2));
    $('#list_pm_amount').val(deliverAfter ? parseFloat(balance).toFixed(2) : '');
    $('#list_pm_method').val('Cash');
    $('#list_card_ref').val('');
    $('#list_card_ref_group').hide();
    $('#listPaymentTitle').text(deliverAfter ? 'Settle Balance to Deliver' : 'Add Payment');
    $('#listPaymentModal').modal('show');
}

function loadAllProdSales(){
    $.ajax({
        type: 'POST',
        url: BASE_URL + 'ProductionSale/getAllOrders',
        dataType: 'json',
        success: function(data){
            try{ $('#ps_datatable').DataTable().destroy(); }catch(e){}
            var rows = '';
            if(data){
                for(var i=0; i<data.length; i++){
                    var d = data[i];
                    var bal = parseFloat(d.prodsale_balance) || 0;

                    // Status dropdown
                    var statusCell = '';
                    if (d.prodsale_status == 'Delivered') {
                        statusCell = '<span class="badge badge-success">Delivered</span>';
                    } else {
                        statusCell = '<select class="form-control form-control-sm ps-status-select" data-id="' + d.prodsale_id + '" data-balance="' + bal + '" style="min-width:120px;">';
                        var statuses = ['Pending', 'Cutting', 'Stitching', 'Ready', 'Delivered'];
                        for (var s = 0; s < statuses.length; s++) {
                            statusCell += '<option value="' + statuses[s] + '"' + (statuses[s] == d.prodsale_status ? ' selected' : '') + '>' + statuses[s] + '</option>';
                        }
                        statusCell += '</select>';
                    }

                    // Payment button
                    var payCell = '';
                    if(d.prodsale_status !== 'Delivered' && bal > 0){
                        payCell = '<button class="btn btn-sm btn-warning btn-list-pay" data-id="' + d.prodsale_id + '" data-balance="' + bal + '"><i class="fa fa-money"></i> Pay</button>';
                    } else if(bal <= 0){
                        payCell = '<span class="badge badge-success">Paid</span>';
                    } else {
                        payCell = '-';
                    }

                    // Color balance red if > 0
                    var balStyle = bal > 0 ? 'color:#c62828;font-weight:bold;' : '';

                    rows += '<tr>'+
                        '<td>'+d.prodsale_id+'</td>'+
                        '<td>'+d.prodsale_code+'</td>'+
                        '<td>'+(d.cus_name || '-')+'</td>'+
                        '<td>'+(d.store_name || '-')+'</td>'+
                        '<td>'+(d.pickup_store_name || d.store_name || '-')+'</td>'+
                        '<td>'+d.prodsale_date+'</td>'+
                        '<td>'+(d.prodsale_delivery_date || '-')+'</td>'+
                        '<td style="text-align:right">'+parseFloat(d.prodsale_total).toFixed(2)+'</td>'+
                        '<td style="text-align:right">'+parseFloat(d.prodsale_paid).toFixed(2)+'</td>'+
                        '<td style="text-align:right;'+balStyle+'">'+bal.toFixed(2)+'</td>'+
                        '<td>'+statusCell+'</td>'+
                        '<td>'+payCell+'</td>'+
                        '</tr>';
                }
            }
            $('#ps_tbody').html(rows);
            $('#ps_datatable').DataTable({
                "order": [[0, "desc"]],
                buttons: ['copy', 'excel', 'pdf'],
                destroy: true
            });

            // Bind payment button
            $('.btn-list-pay').off('click').on('click', function(){
                var psId = $(this).data('id');
                var bal = $(this).data('balance');
                openListPayment(psId, bal, false);
            });

            // Bind status change with delivery balance check
            $('.ps-status-select').off('change').on('change', function() {
                var psId = $(this).data('id');
                var newStatus = $(this).val();
                var bal = parseFloat($(this).data('balance')) || 0;
                var $sel = $(this);

                if(newStatus === 'Delivered' && bal > 0){
                    openListPayment(psId, bal, true);
                    // Reset the dropdown to previous value
                    $sel.val($sel.find('option:not(:selected)').filter(function(){ return this.defaultSelected; }).val() || 'Ready');
                    return;
                }

                $.post(BASE_URL + 'ProductionSale/updateStatus', { prodsale_id: psId, status: newStatus }, function(res) {
                    var r = (typeof res === 'string') ? JSON.parse(res) : res;
                    if(r.status === 'error'){
                        swal({type:'error', title:'Error', text: r.message});
                        loadAllProdSales();
                        return;
                    }
                    swal({ title: 'Updated', text: 'Status changed to ' + newStatus, type: 'success' });
                    loadAllProdSales();
                });
            });
        }
    });
}
</script>
