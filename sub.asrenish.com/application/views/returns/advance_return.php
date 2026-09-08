<!-- ==============================================================
     Advance Return - scan the goods, set the quantities, refund.
     Separate from Returns / Exchanges: nothing on this page reads or
     writes the ezy_pos_returns tables.
     ============================================================== -->
<div class="wrapper">
    <div class="container">

        <?php if (empty($ready)) { ?>
        <div class="alert alert-warning">
            <strong>Nearly there.</strong> The Advance Return tables are not in the database yet.
            Open <code>migrate.php</code> and run <strong>Step 8</strong>, then come back to this page.
        </div>
        <?php } ?>

        <div class="row">
            <div class="col-12">
                <h4 class="page-title"><i class="fa fa-undo"></i> Advance Return</h4>
                <p class="text-muted">
                    For goods brought back over the counter. A bill is optional -
                    tick <em>Return without bill</em> and just scan what the customer has.
                </p>
            </div>
        </div>

        <div class="row">
            <!-- ------------------------------------------------ left: details -->
            <div class="col-lg-4 col-md-5">
                <div class="card-box">

                    <div class="form-group row">
                        <label class="col-5 col-form-label">Branch<span class="text-danger">*</span></label>
                        <div class="col-7">
                            <select class="form-control" id="ar_store">
                                <?php foreach ($stores as $s) {
                                    $sid = is_array($s) ? $s['store_id'] : $s->store_id;
                                    $sn  = is_array($s) ? $s['store_name'] : $s->store_name; ?>
                                    <option value="<?php echo $sid; ?>"><?php echo htmlspecialchars($sn); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" style="background:#fff8e1;border-radius:4px;padding:6px 0;">
                        <label class="col-7 col-form-label" style="cursor:pointer;" for="ar_nobill">
                            <strong>Return without bill</strong>
                        </label>
                        <div class="col-5 col-form-label text-right">
                            <input type="checkbox" id="ar_nobill" checked
                                   style="transform:scale(1.4);margin-right:8px;vertical-align:middle;">
                        </div>
                        <div class="col-12"><small class="text-muted">Ticked: the customer has no invoice.</small></div>
                    </div>

                    <div id="ar_bill_box" style="display:none;">
                        <div class="form-group row">
                            <label class="col-5 col-form-label">Bill number</label>
                            <div class="col-7">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="ar_bill_ref" placeholder="HG-M-0012">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="button" id="ar_find_bill"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <small class="text-muted" id="ar_bill_note"></small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-5 col-form-label">Customer</label>
                        <div class="col-7">
                            <select class="form-control" id="ar_customer">
                                <option value="">-- none --</option>
                                <?php if (!empty($customers)) { foreach ($customers as $c) {
                                    $cid = is_array($c) ? $c['cus_id'] : $c->cus_id;
                                    $cn  = is_array($c) ? $c['cus_name'] : $c->cus_name; ?>
                                    <option value="<?php echo $cid; ?>"><?php echo htmlspecialchars($cn); ?></option>
                                <?php }} ?>
                            </select>
                            <small class="text-muted">Only needed for store credit.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-5 col-form-label">Refund as</label>
                        <div class="col-7">
                            <select class="form-control" id="ar_mode">
                                <option value="cash">Cash out of the till</option>
                                <option value="store_credit">Store credit on the account</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-7 col-form-label" style="cursor:pointer;" for="ar_restock">
                            <strong>Put back in stock</strong>
                        </label>
                        <div class="col-5 col-form-label text-right">
                            <input type="checkbox" id="ar_restock" checked
                                   style="transform:scale(1.4);margin-right:8px;vertical-align:middle;">
                        </div>
                        <div class="col-12"><small class="text-muted">Untick for damaged goods - then stock is not increased.</small></div>
                    </div>

                    <div class="form-group row">
                        <label class="col-5 col-form-label">Reason</label>
                        <div class="col-7">
                            <input type="text" class="form-control" id="ar_reason" placeholder="optional">
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <label class="col-5 col-form-label" style="font-size:16px;">Refund total</label>
                        <div class="col-7 col-form-label text-right" style="font-size:22px;font-weight:600;">
                            LKR <span id="ar_total">0.00</span>
                        </div>
                    </div>

                    <button class="btn btn-danger btn-block" id="ar_save" <?php echo empty($ready) ? 'disabled' : ''; ?>>
                        <i class="fa fa-check"></i> Complete Return
                    </button>
                </div>
            </div>

            <!-- ------------------------------------------------ right: items -->
            <div class="col-lg-8 col-md-7">
                <div class="card-box">
                    <div class="form-group row mb-2" style="background:#e8f5e9;padding:8px;border-radius:4px;">
                        <label class="col-3 col-form-label"><i class="fa fa-barcode"></i> Scan item:</label>
                        <div class="col-9">
                            <input class="form-control" type="text" id="ar_scan"
                                   placeholder="Scan the barcode, or type a code or name and press Enter" autofocus>
                        </div>
                    </div>

                    <div id="ar_search_results" class="list-group m-b-10" style="display:none;max-height:220px;overflow:auto;"></div>

                    <table class="table table-bordered table-striped" id="ar_table">
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>Code</th>
                                <th>Item</th>
                                <th style="width:110px;">Qty</th>
                                <th style="width:130px;">Price</th>
                                <th style="width:120px;text-align:right;">Total</th>
                                <th style="width:50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="ar_body">
                            <tr id="ar_empty"><td colspan="7" class="text-center text-muted">Nothing scanned yet.</td></tr>
                        </tbody>
                    </table>

                    <button class="btn btn-sm btn-outline-secondary" id="ar_clear"><i class="fa fa-times"></i> Clear the list</button>
                    <a href="<?php echo base_url('advance-returns'); ?>" class="btn btn-sm btn-outline-info pull-right">
                        <i class="fa fa-list"></i> Past advance returns
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){

    // The return list. One row per item; scanning the same item twice adds to
    // the quantity rather than making a second row, which is what a cashier
    // expects from a scanner.
    var arRows = [];

    function esc(v){
        if(v === null || typeof v === 'undefined') return '';
        return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function money(v){ return (parseFloat(v)||0).toFixed(2); }

    function render(){
        var html = '', total = 0;
        for(var i=0; i<arRows.length; i++){
            var r = arRows[i];
            var line = +(r.qty * r.price).toFixed(2);
            total += line;
            html += '<tr>'
                 +  '<td>'+(i+1)+'</td>'
                 +  '<td>'+esc(r.item_code)+'</td>'
                 +  '<td>'+esc(r.item_name)+'</td>'
                 +  '<td><input type="number" class="form-control form-control-sm ar-qty" data-i="'+i+'" '
                 +      'min="0.01" step="0.01" value="'+r.qty+'"></td>'
                 +  '<td><input type="number" class="form-control form-control-sm ar-price" data-i="'+i+'" '
                 +      'min="0" step="0.01" value="'+r.price+'"></td>'
                 +  '<td style="text-align:right;">'+money(line)+'</td>'
                 +  '<td><a href="javascript:;" class="btn btn-sm btn-danger ar-del" data-i="'+i+'" title="Remove"><i class="fa fa-times"></i></a></td>'
                 +  '</tr>';
        }
        if(arRows.length === 0){
            html = '<tr id="ar_empty"><td colspan="7" class="text-center text-muted">Nothing scanned yet.</td></tr>';
        }
        $('#ar_body').html(html);
        $('#ar_total').text(money(total));
    }

    function addItem(it, qty){
        qty = parseFloat(qty) || 1;
        for(var i=0; i<arRows.length; i++){
            if(arRows[i].item_id == it.itm_id){
                arRows[i].qty = +(parseFloat(arRows[i].qty) + qty).toFixed(2);
                render();
                return;
            }
        }
        arRows.push({
            item_id:   it.itm_id,
            item_code: it.itm_code,
            item_name: it.itm_name,
            qty:       qty,
            price:     +(parseFloat(it.itm_sellingprice) || 0).toFixed(2)
        });
        render();
    }

    // ---- editing a line ----
    $('#ar_body').on('input', '.ar-qty', function(){
        var i = $(this).data('i');
        var v = parseFloat($(this).val());
        arRows[i].qty = (isNaN(v) || v < 0) ? 0 : v;
        var line = +(arRows[i].qty * arRows[i].price).toFixed(2);
        $(this).closest('tr').find('td').eq(5).text(money(line));
        var t = 0;
        for(var k=0;k<arRows.length;k++){ t += arRows[k].qty * arRows[k].price; }
        $('#ar_total').text(money(t));
    });
    $('#ar_body').on('input', '.ar-price', function(){
        var i = $(this).data('i');
        var v = parseFloat($(this).val());
        arRows[i].price = (isNaN(v) || v < 0) ? 0 : v;
        var line = +(arRows[i].qty * arRows[i].price).toFixed(2);
        $(this).closest('tr').find('td').eq(5).text(money(line));
        var t = 0;
        for(var k=0;k<arRows.length;k++){ t += arRows[k].qty * arRows[k].price; }
        $('#ar_total').text(money(t));
    });
    $('#ar_body').on('click', '.ar-del', function(){
        arRows.splice($(this).data('i'), 1);
        render();
    });
    $('#ar_clear').click(function(){
        if(arRows.length === 0) return;
        swal({ title:'Clear the list?', type:'warning', showCancelButton:true,
               confirmButtonText:'Yes, clear it' }).then(function(res){
            if(res && res.value){ arRows = []; render(); }
        });
    });

    // ---- scanning / searching ----
    function search(term){
        if(!term) return;
        $.ajax({
            type:'POST',
            url:'<?php echo base_url("AdvanceReturn/searchItems"); ?>',
            data:{ term: term, store_id: $('#ar_store').val() },
            dataType:'json',
            success:function(rows){
                $('#ar_search_results').hide().empty();
                if(!rows || rows.length === 0){
                    swal({type:'error',title:'Not found',text:'No item matches "'+term+'".'});
                    return;
                }
                // A scanner produces an exact code, and the server returns that
                // one row - add it straight away rather than making the cashier
                // pick it out of a list of one.
                if(rows.length === 1){ addItem(rows[0], 1); $('#ar_scan').val('').focus(); return; }

                var html = '';
                for(var i=0; i<rows.length; i++){
                    var r = rows[i];
                    html += '<a href="javascript:;" class="list-group-item list-group-item-action ar-pick" data-i="'+i+'">'
                         +  '<strong>'+esc(r.itm_code)+'</strong> &nbsp; '+esc(r.itm_name)
                         +  ' <span class="pull-right">LKR '+money(r.itm_sellingprice)+'</span></a>';
                }
                $('#ar_search_results').html(html).data('rows', rows).show();
            },
            error:function(){ swal({type:'error',title:'Search failed',text:'Could not reach the server.'}); }
        });
    }

    $('#ar_search_results').on('click', '.ar-pick', function(){
        var rows = $('#ar_search_results').data('rows');
        addItem(rows[$(this).data('i')], 1);
        $('#ar_search_results').hide().empty();
        $('#ar_scan').val('').focus();
    });

    $('#ar_scan').on('keydown', function(e){
        if(e.key === 'Enter'){
            e.preventDefault();
            search($(this).val().trim());
        }
    });

    // ---- optional bill lookup ----
    $('#ar_nobill').change(function(){
        var without = $(this).is(':checked');
        $('#ar_bill_box').toggle(!without);
        if(without){ $('#ar_bill_ref').val(''); $('#ar_bill_note').text(''); $('#ar_sale_id').val(''); }
    });

    var arSaleId = '';
    $('#ar_find_bill').click(function(){
        var no = $('#ar_bill_ref').val().trim();
        if(!no){ return; }
        $.ajax({
            type:'POST',
            url:'<?php echo base_url("AdvanceReturn/findBill"); ?>',
            data:{ bill_no: no },
            dataType:'json',
            success:function(res){
                if(!res.ok){ arSaleId=''; $('#ar_bill_note').text(res.msg).css('color','#c62828'); return; }
                arSaleId = res.sale.sale_id;
                $('#ar_bill_note')
                    .text('Found: ' + (res.sale.cus_name || 'no customer') + ', ' + res.sale.sale_date
                          + ', LKR ' + money(res.sale.sale_grandtotal))
                    .css('color','#2e7d32');
                if(res.sale.sale_cus_id){ $('#ar_customer').val(res.sale.sale_cus_id); }
                if(res.sale.sale_location){ $('#ar_store').val(res.sale.sale_location); }
                // Offer the lines off that bill so they can be scanned or picked.
                if(res.sale.items && res.sale.items.length){
                    var html = '';
                    for(var i=0; i<res.sale.items.length; i++){
                        var it = res.sale.items[i];
                        html += '<a href="javascript:;" class="list-group-item list-group-item-action ar-pick" data-i="'+i+'">'
                             +  '<strong>'+esc(it.itm_code)+'</strong> &nbsp; '+esc(it.itm_name)
                             +  ' <span class="pull-right">sold '+money(it.saleitem_quantity)+' @ '+money(it.saleitem_price)+'</span></a>';
                    }
                    var mapped = [];
                    for(var j=0; j<res.sale.items.length; j++){
                        mapped.push({ itm_id: res.sale.items[j].saleitem_item_id,
                                      itm_code: res.sale.items[j].itm_code,
                                      itm_name: res.sale.items[j].itm_name,
                                      itm_sellingprice: res.sale.items[j].saleitem_price });
                    }
                    $('#ar_search_results').html(html).data('rows', mapped).show();
                }
            },
            error:function(){ $('#ar_bill_note').text('Could not reach the server.').css('color','#c62828'); }
        });
    });

    // ---- save ----
    var saving = false;
    $('#ar_save').click(function(){
        if(saving) return;
        if(arRows.length === 0){
            swal({type:'error',title:'Nothing to return',text:'Scan the items being returned first.'});
            return;
        }
        for(var i=0; i<arRows.length; i++){
            if(!arRows[i].qty || arRows[i].qty <= 0){
                swal({type:'error',title:'Check the quantities',
                      text:arRows[i].item_name + ' has no quantity on it.'});
                return;
            }
        }
        if($('#ar_mode').val() === 'store_credit' && !$('#ar_customer').val()){
            swal({type:'error',title:'Customer needed',
                  text:'Store credit has to go on an account, so pick the customer.'});
            return;
        }

        var total = $('#ar_total').text();
        swal({
            title: 'Refund LKR ' + total + '?',
            text: $('#ar_mode').val() === 'cash'
                  ? 'This much cash comes out of the till.'
                  : 'This much goes on the customer account as store credit.',
            type: 'question', showCancelButton: true, confirmButtonText: 'Yes, complete it'
        }).then(function(res){
            if(!res || !res.value) return;
            saving = true;
            $('#ar_save').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $.ajax({
                type:'POST',
                url:'<?php echo base_url("AdvanceReturn/save"); ?>',
                data:{
                    store_id:    $('#ar_store').val(),
                    cus_id:      $('#ar_customer').val() || 0,
                    bill_ref:    $('#ar_nobill').is(':checked') ? '' : $('#ar_bill_ref').val(),
                    sale_id:     $('#ar_nobill').is(':checked') ? '' : arSaleId,
                    refund_mode: $('#ar_mode').val(),
                    restock:     $('#ar_restock').is(':checked') ? 1 : 0,
                    reason:      $('#ar_reason').val(),
                    lines:       JSON.stringify(arRows)
                },
                dataType:'json',
                success:function(res){
                    saving = false;
                    $('#ar_save').prop('disabled', false).html('<i class="fa fa-check"></i> Complete Return');
                    if(!res || !res.ok){
                        swal({type:'error',title:'Not saved',text:(res && res.msg) ? res.msg : 'The return could not be saved.'});
                        return;
                    }
                    var url = '<?php echo base_url("AdvanceReturn/slip/"); ?>' + res.id;
                    window.open(url, '_blank', 'width=420,height=650,scrollbars=yes');
                    swal({type:'success',title:res.ref,text:'Return saved. LKR ' + money(res.total) + ' refunded.'})
                        .then(function(){ location.reload(); });
                },
                error:function(){
                    saving = false;
                    $('#ar_save').prop('disabled', false).html('<i class="fa fa-check"></i> Complete Return');
                    swal({type:'error',title:'Not saved',text:'Could not reach the server. Nothing has been changed.'});
                }
            });
        });
    });

    render();
});
</script>
