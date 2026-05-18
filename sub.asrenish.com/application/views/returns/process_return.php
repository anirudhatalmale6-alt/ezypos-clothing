        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-4 col-md-5 col-sm-12"><!-- Return Form Panel -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box clearfix">
                                <h4 class="header-title m-t-0 m-b-30"><i class="fa fa-undo"></i> Returns &amp; Exchanges</h4>
                                <fieldset>
                                <!-- Sale Search -->
                                <div class="form-group row">
                                    <label class="col-4 col-form-label">Invoice #<span class="text-danger">*</span></label>
                                    <div class="col-6">
                                        <input class="form-control" type="text" id="sale_id_input" placeholder="Sale / Invoice ID">
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-sm btn-primary" id="btnLoadSale"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>

                                <!-- Sale Info (hidden until loaded) -->
                                <div id="saleInfoSection" style="display:none;">
                                    <div style="background:#f8f9fa;border-radius:4px;padding:10px;margin-bottom:10px;">
                                        <div class="form-group row mb-1">
                                            <label class="col-5 col-form-label">Invoice No:</label>
                                            <div class="col-7 col-form-label text-right"><strong>AS00<span id="lbl_sale_id"></span></strong></div>
                                        </div>
                                        <div class="form-group row mb-1">
                                            <label class="col-5 col-form-label">Date:</label>
                                            <div class="col-7 col-form-label text-right"><span id="lbl_sale_date"></span></div>
                                        </div>
                                        <div class="form-group row mb-1">
                                            <label class="col-5 col-form-label">Customer:</label>
                                            <div class="col-7 col-form-label text-right"><span id="lbl_customer"></span></div>
                                        </div>
                                        <div class="form-group row mb-1">
                                            <label class="col-5 col-form-label">Grand Total:</label>
                                            <div class="col-7 col-form-label text-right"><strong>LKR <span id="lbl_grand_total">0.00</span></strong></div>
                                        </div>
                                        <div class="form-group row mb-0" id="returnStatusRow" style="display:none;">
                                            <label class="col-5 col-form-label">Return Status:</label>
                                            <div class="col-7 col-form-label text-right"><span id="lbl_return_status" class="badge badge-warning"></span></div>
                                        </div>
                                    </div>

                                    <hr>
                                    <!-- Return Type -->
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Return Type<span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <select class="form-control" id="return_type">
                                                <option value="full_return">Full Return</option>
                                                <option value="partial_return" selected>Partial Return</option>
                                                <option value="exchange">Exchange</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Reason -->
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Reason</label>
                                        <div class="col-8">
                                            <textarea class="form-control" id="return_reason" rows="2" placeholder="Optional reason..."></textarea>
                                        </div>
                                    </div>

                                    <hr>
                                    <!-- Return Summary -->
                                    <div style="background:#fff3e0;border-radius:4px;padding:10px;margin-bottom:10px;">
                                        <div class="form-group row mb-1">
                                            <label class="col-6 col-form-label"><strong>Return Total:</strong></label>
                                            <div class="col-6 col-form-label text-right"><strong style="color:#c62828;">LKR <span id="lbl_return_total">0.00</span></strong></div>
                                        </div>
                                        <div class="form-group row mb-1" id="exchangeTotalRow" style="display:none;">
                                            <label class="col-6 col-form-label"><strong>Exchange Total:</strong></label>
                                            <div class="col-6 col-form-label text-right"><strong style="color:#2e7d32;">LKR <span id="lbl_exchange_total">0.00</span></strong></div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <label class="col-6 col-form-label" style="font-size:16px;"><strong>Net Refund:</strong></label>
                                            <div class="col-6 col-form-label text-right" style="font-size:16px;"><strong>LKR <span id="lbl_net_amount">0.00</span></strong></div>
                                        </div>
                                    </div>

                                    <!-- Process Button -->
                                    <div class="pull-right">
                                        <button id="btnProcessReturn" disabled class="btn btn-danger waves-effect"><i class="fa fa-undo"></i> Process Return</button>
                                    </div>
                                </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div><!-- End of Return Form Panel -->

                <div class="col-lg-8 col-md-7 col-sm-12"><!--Start Table & row -->
                    <!-- Original Sale Items -->
                    <div id="saleItemsSection" style="display:none;">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-box table-responsive clearfix">
                                    <h5 class="header-title m-t-0 m-b-10">Original Sale Items</h5>
                                    <table id="saleItemsTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr style="background-color: #C0C0C0">
                                                <th style="font-size:12px;">#</th>
                                                <th style="font-size:12px;">Item Name</th>
                                                <th style="font-size:12px;text-align:right;">Price</th>
                                                <th style="font-size:12px;text-align:right;">Qty</th>
                                                <th style="font-size:12px;text-align:right;">Discount</th>
                                                <th style="font-size:12px;text-align:right;">Total</th>
                                                <th style="font-size:12px;text-align:center;width:80px;">Return Qty</th>
                                                <th style="font-size:12px;text-align:right;">Return Amt</th>
                                            </tr>
                                        </thead>
                                        <tbody id="saleItemsBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Exchange Items Section (shown only for exchange type) -->
                        <div id="exchangeSection" style="display:none;">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-box clearfix">
                                        <h5 class="header-title m-t-0 m-b-10"><i class="fa fa-exchange"></i> Exchange Items (New Items)</h5>
                                        <div class="row m-b-10">
                                            <div class="col-5">
                                                <input class="form-control" id="exc_item_auto" placeholder="Search item...">
                                                <input type="hidden" id="exc_item_id">
                                            </div>
                                            <div class="col-2">
                                                <input class="form-control" type="text" id="exc_qty" placeholder="Qty" value="1">
                                            </div>
                                            <div class="col-3">
                                                <input class="form-control" type="text" id="exc_price" placeholder="Price">
                                            </div>
                                            <div class="col-2">
                                                <button class="btn btn-success btn-sm" id="btnAddExcItem" style="margin-top:2px;"><i class="fa fa-plus"></i> Add</button>
                                            </div>
                                        </div>
                                        <table id="exchangeItemsTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr style="background-color:#d4edda;">
                                                    <th style="font-size:12px;">#</th>
                                                    <th style="font-size:12px;">Item</th>
                                                    <th style="font-size:12px;text-align:right;">Price</th>
                                                    <th style="font-size:12px;text-align:right;">Qty</th>
                                                    <th style="font-size:12px;text-align:right;">Total</th>
                                                    <th style="font-size:12px;text-align:center;">Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody id="exchangeItemsBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Return History for this Sale -->
                        <div class="row" id="returnHistorySection" style="display:none;">
                            <div class="col-12">
                                <div class="card-box table-responsive clearfix">
                                    <h5 class="header-title m-t-0 m-b-10"><i class="fa fa-history"></i> Previous Returns for this Sale</h5>
                                    <table class="table table-sm table-bordered" id="returnHistoryTable">
                                        <thead>
                                            <tr style="background-color:#f5f5f5;">
                                                <th>Return ID</th>
                                                <th>Type</th>
                                                <th>Return Amt</th>
                                                <th>Exchange Amt</th>
                                                <th>Net</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="returnHistoryBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Placeholder when no sale loaded -->
                    <div id="noSalePlaceholder" class="text-center" style="padding:80px 20px;color:#999;">
                        <i class="fa fa-search" style="font-size:48px;"></i>
                        <p class="m-t-20" style="font-size:16px;">Enter an invoice number and click Load to begin processing a return.</p>
                    </div>

                </div> <!--End of Table & row -->
            </div>
          </div>
        </div> <!-- container-fluid -->

<script>
$( function() {

    var BASE_URL = '<?php echo base_url(); ?>';
    var loadedSale = null;
    var loadedSaleItems = [];
    var exchangeItems = [];
    var excCounter = 0;

    // =========== ITEM AUTOCOMPLETE FOR EXCHANGE ===========
    var availableItems = [
        <?php
        if(isset($items)){
            foreach ($items as $item)
            {
               $sp = isset($item->itm_sellingprice) ? $item->itm_sellingprice : '0';
               echo '{ label: "'.addslashes($item->itm_name).' - '.$item->itm_code.' /stock = '.$item->stock_qty.'", value:"'.$item->itm_id.'", price:"'.$sp.'", name:"'.addslashes($item->itm_name).'" },';
            }
        }
        ?>
    ];

    $("#exc_item_auto").autocomplete({
        source: availableItems,
        select: function(event, ui) {
            event.preventDefault();
            $("#exc_item_auto").val(ui.item.label);
            $('#exc_item_id').val(ui.item.value);
            $('#exc_price').val(parseFloat(ui.item.price).toFixed(2));
        }
    });

    // =========== RETURN TYPE CHANGE ===========
    $('#return_type').change(function(){
        var type = $(this).val();
        if(type === 'exchange'){
            $('#exchangeSection').show();
            $('#exchangeTotalRow').show();
        } else {
            $('#exchangeSection').hide();
            $('#exchangeTotalRow').hide();
        }
        if(type === 'full_return'){
            autoFillFullReturn();
        } else {
            // For partial/exchange, reset return qtys
            if(loadedSale){
                clearReturnQtys();
            }
        }
        calculateReturnTotals();
    });

    // =========== LOAD SALE ===========
    $('#btnLoadSale').click(function(){ loadSale(); });
    $('#sale_id_input').on('keypress', function(e){
        if(e.which === 13){ e.preventDefault(); loadSale(); }
    });

    function loadSale(){
        var saleId = $('#sale_id_input').val().trim();
        if(!saleId){
            swal({type:'error', title:'Error', text:'Please enter an invoice number.'});
            return;
        }
        $.ajax({
            type: "POST",
            url: BASE_URL + "Returns/getSaleDetails",
            data: {sale_id: saleId},
            dataType: "json",
            success: function(data){
                if(data.status === 'error'){
                    swal({type:'error', title:'Not Found', text: data.message || 'Sale not found.'});
                    return;
                }
                loadedSale = data.sale;
                loadedSaleItems = data.items || [];

                // Populate sale info
                $('#lbl_sale_id').text(loadedSale.sale_id);
                $('#lbl_sale_date').text(loadedSale.sale_createdat);
                $('#lbl_customer').text(loadedSale.cus_name || 'N/A');
                $('#lbl_grand_total').text(parseFloat(loadedSale.sale_grandtotal).toFixed(2));

                // Return status
                if(loadedSale.sale_return_status){
                    $('#lbl_return_status').text(loadedSale.sale_return_status);
                    $('#returnStatusRow').show();
                } else {
                    $('#returnStatusRow').hide();
                }

                // Populate sale items table
                var html = '';
                for(var i = 0; i < loadedSaleItems.length; i++){
                    var itm = loadedSaleItems[i];
                    var disLabel = parseFloat(itm.saleitem_discount || 0).toFixed(2);
                    if(itm.saleitem_discount_type && itm.saleitem_discount_type === 'flat'){
                        disLabel += ' (LKR)';
                    } else {
                        disLabel += '%';
                    }
                    // Calculate max returnable qty (original qty minus already returned qty)
                    var maxRetQty = parseFloat(itm.saleitem_quantity) - parseFloat(itm.returned_qty || 0);
                    if(maxRetQty < 0) maxRetQty = 0;

                    html += '<tr data-item-id="'+itm.saleitem_item_id+'" data-price="'+itm.saleitem_price+'" data-qty="'+itm.saleitem_quantity+'" data-discount="'+(itm.saleitem_discount||0)+'" data-discount-type="'+(itm.saleitem_discount_type||'percentage')+'" data-max-ret="'+maxRetQty.toFixed(2)+'">';
                    html += '<td>'+(i+1)+'</td>';
                    html += '<td>'+itm.itm_name+'</td>';
                    html += '<td style="text-align:right;">'+parseFloat(itm.saleitem_price).toFixed(2)+'</td>';
                    html += '<td style="text-align:right;">'+parseFloat(itm.saleitem_quantity).toFixed(2)+'</td>';
                    html += '<td style="text-align:right;">'+disLabel+'</td>';
                    html += '<td style="text-align:right;">'+parseFloat(itm.saleitem_total).toFixed(2)+'</td>';
                    html += '<td style="text-align:center;"><input type="number" class="form-control form-control-sm return-qty-input" style="width:70px;margin:0 auto;text-align:right;" min="0" max="'+maxRetQty.toFixed(2)+'" step="0.01" value="0" '+(maxRetQty <= 0 ? 'disabled' : '')+' /></td>';
                    html += '<td style="text-align:right;" class="return-amt-cell">0.00</td>';
                    html += '</tr>';
                }
                $('#saleItemsBody').html(html);

                // Show sections
                $('#saleInfoSection').show();
                $('#saleItemsSection').show();
                $('#noSalePlaceholder').hide();
                $('#btnProcessReturn').prop('disabled', false);

                // Reset exchange items
                exchangeItems = [];
                excCounter = 0;
                $('#exchangeItemsBody').html('');

                // Apply return type
                $('#return_type').trigger('change');

                // Load return history
                loadReturnHistory(loadedSale.sale_id);
            },
            error: function(){
                swal({type:'error', title:'Error', text:'Failed to load sale details.'});
            }
        });
    }

    // =========== RETURN QTY INPUT ===========
    $(document).on('keyup change', '.return-qty-input', function(){
        var $row = $(this).closest('tr');
        var qty = parseFloat($(this).val()) || 0;
        var maxRet = parseFloat($row.data('max-ret'));
        if(qty > maxRet){ qty = maxRet; $(this).val(maxRet); }
        if(qty < 0){ qty = 0; $(this).val(0); }

        var price = parseFloat($row.data('price'));
        var discount = parseFloat($row.data('discount')) || 0;
        var discType = $row.data('discount-type') || 'percentage';

        var lineTotal = price * qty;
        if(discType === 'flat'){
            // Flat discount applied proportionally per unit
            var origQty = parseFloat($row.data('qty'));
            var perUnitDiscount = (origQty > 0) ? (discount / origQty) : 0;
            lineTotal = (price * qty) - (perUnitDiscount * qty);
        } else {
            lineTotal = lineTotal * (100 - discount) / 100;
        }
        if(lineTotal < 0) lineTotal = 0;

        $row.find('.return-amt-cell').text(lineTotal.toFixed(2));
        calculateReturnTotals();
    });

    // =========== AUTO-FILL FULL RETURN ===========
    function autoFillFullReturn(){
        $('#saleItemsBody tr').each(function(){
            var maxRet = parseFloat($(this).data('max-ret'));
            $(this).find('.return-qty-input').val(maxRet.toFixed(2)).trigger('change');
        });
    }

    function clearReturnQtys(){
        $('#saleItemsBody tr').each(function(){
            $(this).find('.return-qty-input').val('0').trigger('change');
        });
    }

    // =========== EXCHANGE ITEMS ===========
    $('#btnAddExcItem').click(function(){
        var itemId = $('#exc_item_id').val();
        var itemLabel = $('#exc_item_auto').val();
        var qty = parseFloat($('#exc_qty').val()) || 0;
        var price = parseFloat($('#exc_price').val()) || 0;

        if(!itemId || !itemLabel){
            swal({type:'error', title:'Error', text:'Please select an item.'});
            return;
        }
        if(qty <= 0){
            swal({type:'error', title:'Error', text:'Quantity must be greater than 0.'});
            return;
        }
        if(price <= 0){
            swal({type:'error', title:'Error', text:'Price must be greater than 0.'});
            return;
        }

        excCounter++;
        var total = (price * qty).toFixed(2);
        // Extract just the item name from the autocomplete label
        var itemName = itemLabel.split(' - ')[0];

        exchangeItems.push({
            idx: excCounter,
            item_id: itemId,
            item_name: itemName,
            price: price,
            qty: qty,
            total: parseFloat(total)
        });

        var row = '<tr data-exc-idx="'+excCounter+'">';
        row += '<td>'+excCounter+'</td>';
        row += '<td>'+itemName+'</td>';
        row += '<td style="text-align:right;">'+price.toFixed(2)+'</td>';
        row += '<td style="text-align:right;">'+qty.toFixed(2)+'</td>';
        row += '<td style="text-align:right;">'+total+'</td>';
        row += '<td style="text-align:center;"><a href="javascript:;" class="btn btn-sm btn-danger btn-remove-exc" data-exc-idx="'+excCounter+'"><i class="fa fa-times"></i></a></td>';
        row += '</tr>';
        $('#exchangeItemsBody').append(row);

        // Clear inputs
        $('#exc_item_auto').val('');
        $('#exc_item_id').val('');
        $('#exc_qty').val('1');
        $('#exc_price').val('');

        calculateReturnTotals();
    });

    $(document).on('click', '.btn-remove-exc', function(){
        var idx = $(this).data('exc-idx');
        exchangeItems = exchangeItems.filter(function(e){ return e.idx !== idx; });
        $(this).closest('tr').remove();
        // Re-number rows
        var n = 0;
        $('#exchangeItemsBody tr').each(function(){
            n++;
            $(this).find('td:first').text(n);
        });
        calculateReturnTotals();
    });

    // =========== CALCULATE RETURN TOTALS ===========
    function calculateReturnTotals(){
        var returnTotal = 0;
        $('#saleItemsBody tr').each(function(){
            returnTotal += parseFloat($(this).find('.return-amt-cell').text()) || 0;
        });

        var exchangeTotal = 0;
        for(var i = 0; i < exchangeItems.length; i++){
            exchangeTotal += exchangeItems[i].total;
        }

        var netAmount = returnTotal - exchangeTotal;
        $('#lbl_return_total').text(returnTotal.toFixed(2));
        $('#lbl_exchange_total').text(exchangeTotal.toFixed(2));
        $('#lbl_net_amount').text(netAmount.toFixed(2));

        // Color the net amount
        if(netAmount > 0){
            $('#lbl_net_amount').css('color', '#c62828'); // Refund to customer
        } else if(netAmount < 0){
            $('#lbl_net_amount').css('color', '#2e7d32'); // Customer pays
        } else {
            $('#lbl_net_amount').css('color', '#333');
        }

        // Enable/disable process button
        var hasReturnItems = false;
        $('#saleItemsBody tr').each(function(){
            var rq = parseFloat($(this).find('.return-qty-input').val()) || 0;
            if(rq > 0) hasReturnItems = true;
        });
        $('#btnProcessReturn').prop('disabled', !hasReturnItems);
    }

    // =========== PROCESS RETURN ===========
    $('#btnProcessReturn').click(function(){
        if(!loadedSale){
            swal({type:'error', title:'Error', text:'No sale loaded.'});
            return;
        }

        // Collect return items
        var returnItems = [];
        $('#saleItemsBody tr').each(function(){
            var rq = parseFloat($(this).find('.return-qty-input').val()) || 0;
            if(rq > 0){
                returnItems.push({
                    item_id: $(this).data('item-id'),
                    original_price: $(this).data('price'),
                    original_qty: $(this).data('qty'),
                    return_qty: rq,
                    return_amount: parseFloat($(this).find('.return-amt-cell').text()),
                    discount: $(this).data('discount'),
                    discount_type: $(this).data('discount-type')
                });
            }
        });

        if(returnItems.length === 0){
            swal({type:'error', title:'Error', text:'Please enter return quantities for at least one item.'});
            return;
        }

        // Collect exchange items
        var excItemsData = [];
        for(var i = 0; i < exchangeItems.length; i++){
            excItemsData.push({
                item_id: exchangeItems[i].item_id,
                price: exchangeItems[i].price,
                qty: exchangeItems[i].qty,
                total: exchangeItems[i].total
            });
        }

        var postData = {
            sale_id: loadedSale.sale_id,
            return_type: $('#return_type').val(),
            reason: $('#return_reason').val(),
            refund_amount: parseFloat($('#lbl_return_total').text()),
            exchange_amount: parseFloat($('#lbl_exchange_total').text()),
            net_amount: parseFloat($('#lbl_net_amount').text()),
            return_items: JSON.stringify(returnItems),
            exchange_items: JSON.stringify(excItemsData)
        };

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            type: "POST",
            url: BASE_URL + "Returns/saveReturn",
            data: postData,
            dataType: "json",
            success: function(res){
                btn.prop('disabled', false).html('<i class="fa fa-undo"></i> Process Return');
                if(res.status === 'success'){
                    swal({
                        type: 'success',
                        title: 'Return Processed!',
                        text: 'Return ID: ' + res.return_id,
                        showConfirmButton: true
                    });
                    // Open print window
                    if(res.return_id){
                        var horizontal = Math.floor(window.innerWidth/2);
                        var left = horizontal - 200;
                        var rurl = BASE_URL + "Returns/print_invoice/" + res.return_id;
                        window.open(rurl, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=40,left="+left+",width=400,height=600");
                    }
                    // Reload the sale to refresh data
                    loadSale();
                } else {
                    swal({type:'error', title:'Error', text: res.message || 'Failed to process return.'});
                }
            },
            error: function(){
                btn.prop('disabled', false).html('<i class="fa fa-undo"></i> Process Return');
                swal({type:'error', title:'Error', text:'Network error. Please try again.'});
            }
        });
    });

    // =========== LOAD RETURN HISTORY ===========
    function loadReturnHistory(saleId){
        $.ajax({
            type: "POST",
            url: BASE_URL + "Returns/getReturnHistory",
            data: {sale_id: saleId},
            dataType: "json",
            success: function(data){
                if(data && data.length > 0){
                    var html = '';
                    for(var i = 0; i < data.length; i++){
                        var r = data[i];
                        var typeLabel = r.ret_type.replace('_', ' ');
                        typeLabel = typeLabel.charAt(0).toUpperCase() + typeLabel.slice(1);
                        html += '<tr>';
                        html += '<td>'+r.ret_id+'</td>';
                        html += '<td>'+typeLabel+'</td>';
                        html += '<td style="text-align:right;">'+parseFloat(r.ret_refund_amount).toFixed(2)+'</td>';
                        html += '<td style="text-align:right;">'+parseFloat(r.ret_exchange_amount).toFixed(2)+'</td>';
                        html += '<td style="text-align:right;">'+parseFloat(r.ret_net_amount).toFixed(2)+'</td>';
                        html += '<td>'+r.ret_created_at+'</td>';
                        html += '</tr>';
                    }
                    $('#returnHistoryBody').html(html);
                    $('#returnHistorySection').show();
                } else {
                    $('#returnHistorySection').hide();
                }
            }
        });
    }

});
</script>
