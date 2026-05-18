        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive clearfix">
                            <h4 class="header-title m-t-0 m-b-20"><i class="fa fa-undo"></i> All Returns &amp; Exchanges</h4>
                            <table id="returnsDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Return ID</th>
                                        <th>Sale ID</th>
                                        <th>Type</th>
                                        <th>Customer</th>
                                        <th style="text-align:right;">Return Amt</th>
                                        <th style="text-align:right;">Exchange Amt</th>
                                        <th style="text-align:right;">Net Amt</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($returns)): foreach($returns as $ret):
                                        $typeLabels = array(
                                            'full_return' => '<span class="badge badge-danger">Full Return</span>',
                                            'partial_return' => '<span class="badge badge-warning">Partial Return</span>',
                                            'exchange' => '<span class="badge badge-info">Exchange</span>'
                                        );
                                        $typeLabel = isset($typeLabels[$ret->ret_type]) ? $typeLabels[$ret->ret_type] : $ret->ret_type;
                                        $statusLabel = ($ret->ret_status == 1) ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Voided</span>';
                                    ?>
                                    <tr>
                                        <td><?php echo $ret->ret_id; ?></td>
                                        <td>AS00<?php echo $ret->ret_sale_id; ?></td>
                                        <td><?php echo $typeLabel; ?></td>
                                        <td><?php echo isset($ret->cus_name) ? $ret->cus_name : 'N/A'; ?></td>
                                        <td style="text-align:right;"><?php echo number_format($ret->ret_refund_amount, 2); ?></td>
                                        <td style="text-align:right;"><?php echo number_format($ret->ret_exchange_amount, 2); ?></td>
                                        <td style="text-align:right;"><?php echo number_format($ret->ret_net_amount, 2); ?></td>
                                        <td><?php echo $ret->ret_created_at; ?></td>
                                        <td><?php echo $statusLabel; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary btn-view-return" data-ret-id="<?php echo $ret->ret_id; ?>"><i class="fa fa-eye"></i> View</button>
                                            <button class="btn btn-sm btn-default btn-print-return" data-ret-id="<?php echo $ret->ret_id; ?>"><i class="fa fa-print"></i></button>
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

        <!-- Return Details Modal -->
        <div class="modal fade" id="returnDetailsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-undo"></i> Return Details - <span id="modal_ret_id"></span></h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <!-- Return Info -->
                        <div class="row m-b-10">
                            <div class="col-6">
                                <table class="table table-sm table-borderless">
                                    <tr><td><strong>Sale Invoice:</strong></td><td id="modal_sale_id"></td></tr>
                                    <tr><td><strong>Return Type:</strong></td><td id="modal_ret_type"></td></tr>
                                    <tr><td><strong>Customer:</strong></td><td id="modal_customer"></td></tr>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class="table table-sm table-borderless">
                                    <tr><td><strong>Date:</strong></td><td id="modal_date"></td></tr>
                                    <tr><td><strong>Processed By:</strong></td><td id="modal_created_by"></td></tr>
                                    <tr><td><strong>Reason:</strong></td><td id="modal_reason"></td></tr>
                                </table>
                            </div>
                        </div>

                        <!-- Returned Items -->
                        <h6><strong>Returned Items</strong></h6>
                        <table class="table table-sm table-bordered m-b-15">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th style="text-align:right;">Price</th>
                                    <th style="text-align:right;">Return Qty</th>
                                    <th style="text-align:right;">Discount</th>
                                    <th style="text-align:right;">Return Amount</th>
                                </tr>
                            </thead>
                            <tbody id="modal_return_items"></tbody>
                        </table>

                        <!-- Exchange Items (shown only if exists) -->
                        <div id="modal_exchange_section" style="display:none;">
                            <h6><strong>Exchange Items (New)</strong></h6>
                            <table class="table table-sm table-bordered m-b-15">
                                <thead style="background-color:#d4edda;">
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th style="text-align:right;">Price</th>
                                        <th style="text-align:right;">Qty</th>
                                        <th style="text-align:right;">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="modal_exchange_items"></tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div style="background:#f8f9fa;border-radius:4px;padding:10px;">
                            <div class="row">
                                <div class="col-4 text-center"><strong>Return Total:</strong><br><span id="modal_return_total" style="color:#c62828;font-size:16px;"></span></div>
                                <div class="col-4 text-center"><strong>Exchange Total:</strong><br><span id="modal_exchange_total" style="color:#2e7d32;font-size:16px;"></span></div>
                                <div class="col-4 text-center"><strong>Net Amount:</strong><br><span id="modal_net_amount" style="font-size:16px;font-weight:bold;"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-print-from-modal"><i class="fa fa-print"></i> Print</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

<script>
$(document).ready(function(){
    var BASE_URL = '<?php echo base_url(); ?>';

    // Initialize DataTable
    $('#returnsDataTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 25
    });

    // =========== VIEW RETURN DETAILS ===========
    var currentViewRetId = 0;
    $(document).on('click', '.btn-view-return', function(){
        var retId = $(this).data('ret-id');
        currentViewRetId = retId;
        loadReturnDetails(retId);
    });

    function loadReturnDetails(retId){
        $.ajax({
            type: "POST",
            url: BASE_URL + "Returns/getReturnDetails",
            data: {ret_id: retId},
            dataType: "json",
            success: function(data){
                if(data.status === 'error'){
                    swal({type:'error', title:'Error', text: data.message || 'Return not found.'});
                    return;
                }

                var ret = data.return_info;
                var retItems = data.return_items || [];
                var excItems = data.exchange_items || [];

                // Populate header
                $('#modal_ret_id').text('RET-' + ret.ret_id);
                $('#modal_sale_id').text('AS00' + ret.ret_sale_id);
                var typeLabels = {full_return:'Full Return', partial_return:'Partial Return', exchange:'Exchange'};
                $('#modal_ret_type').text(typeLabels[ret.ret_type] || ret.ret_type);
                $('#modal_customer').text(ret.cus_name || 'N/A');
                $('#modal_date').text(ret.ret_created_at);
                $('#modal_created_by').text(ret.user_name || 'N/A');
                $('#modal_reason').text(ret.ret_reason || '-');

                // Returned items
                var html = '';
                for(var i = 0; i < retItems.length; i++){
                    var ri = retItems[i];
                    var disLabel = parseFloat(ri.ri_discount || 0).toFixed(2) + '%';
                    html += '<tr>';
                    html += '<td>'+(i+1)+'</td>';
                    html += '<td>'+(ri.ri_item_name || ri.item_name_live || 'Item #'+ri.ri_item_id)+'</td>';
                    html += '<td style="text-align:right;">'+parseFloat(ri.ri_price).toFixed(2)+'</td>';
                    html += '<td style="text-align:right;">'+parseFloat(ri.ri_qty).toFixed(2)+'</td>';
                    html += '<td style="text-align:right;">'+disLabel+'</td>';
                    html += '<td style="text-align:right;">'+parseFloat(ri.ri_total).toFixed(2)+'</td>';
                    html += '</tr>';
                }
                $('#modal_return_items').html(html);

                // Exchange items
                if(excItems.length > 0){
                    var ehtml = '';
                    for(var j = 0; j < excItems.length; j++){
                        var ei = excItems[j];
                        ehtml += '<tr>';
                        ehtml += '<td>'+(j+1)+'</td>';
                        ehtml += '<td>'+(ei.ei_item_name || ei.item_name_live || 'Item #'+ei.ei_item_id)+'</td>';
                        ehtml += '<td style="text-align:right;">'+parseFloat(ei.ei_price).toFixed(2)+'</td>';
                        ehtml += '<td style="text-align:right;">'+parseFloat(ei.ei_qty).toFixed(2)+'</td>';
                        ehtml += '<td style="text-align:right;">'+parseFloat(ei.ei_total).toFixed(2)+'</td>';
                        ehtml += '</tr>';
                    }
                    $('#modal_exchange_items').html(ehtml);
                    $('#modal_exchange_section').show();
                } else {
                    $('#modal_exchange_section').hide();
                }

                // Summary
                $('#modal_return_total').text('LKR ' + parseFloat(ret.ret_refund_amount).toFixed(2));
                $('#modal_exchange_total').text('LKR ' + parseFloat(ret.ret_exchange_amount).toFixed(2));
                var netAmt = parseFloat(ret.ret_net_amount);
                $('#modal_net_amount').text('LKR ' + netAmt.toFixed(2));
                if(netAmt > 0){
                    $('#modal_net_amount').css('color', '#c62828');
                } else if(netAmt < 0){
                    $('#modal_net_amount').css('color', '#2e7d32');
                } else {
                    $('#modal_net_amount').css('color', '#333');
                }

                $('#returnDetailsModal').modal('show');
            },
            error: function(){
                swal({type:'error', title:'Error', text:'Failed to load return details.'});
            }
        });
    }

    // =========== PRINT ===========
    $(document).on('click', '.btn-print-return', function(){
        var retId = $(this).data('ret-id');
        openPrintWindow(retId);
    });
    $(document).on('click', '.btn-print-from-modal', function(){
        openPrintWindow(currentViewRetId);
    });

    function openPrintWindow(retId){
        var horizontal = Math.floor(window.innerWidth/2);
        var left = horizontal - 200;
        var rurl = BASE_URL + "Returns/print_invoice/" + retId;
        window.open(rurl, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=40,left="+left+",width=400,height=600");
    }
});
</script>
