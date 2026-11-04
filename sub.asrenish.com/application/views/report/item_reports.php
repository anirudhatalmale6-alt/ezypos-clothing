<!-- ==============================================================
     Item Stock / Item Sales / Item Purchase report - one page, three kinds.
     ============================================================== -->
<?php $dated = ($type !== 'stock'); ?>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="header-title m-t-0 m-b-0"><i class="fa fa-cubes"></i> <?php echo htmlspecialchars($heading); ?></h4>
                        <?php if ($type === 'sales'): ?>
                        <a href="<?php echo base_url('item-sales-receipts'); ?>" class="small">Find the receipts an item was sold on &rarr;</a>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <?php if ($dated): ?>
                        <div class="col-md-2 col-6"><label class="small mb-1">From</label>
                            <input type="date" class="form-control form-control-sm" id="ir_from" value="<?php echo date('Y-m-01'); ?>"></div>
                        <div class="col-md-2 col-6"><label class="small mb-1">To</label>
                            <input type="date" class="form-control form-control-sm" id="ir_to" value="<?php echo date('Y-m-d'); ?>"></div>
                        <?php endif; ?>
                        <div class="col-md-2 col-6"<?php echo single_location() ? ' style="display:none;"' : ''; ?>><label class="small mb-1">Store</label>
                            <select class="form-control form-control-sm" id="ir_store"><option value="all">All stores</option>
                            <?php foreach ($stores as $s): ?><option value="<?php echo (int)$s->store_id; ?>"><?php echo htmlspecialchars($s->store_name); ?></option><?php endforeach; ?>
                            </select></div>
                        <div class="col-md-2 col-6"><label class="small mb-1">Category</label>
                            <select class="form-control form-control-sm" id="ir_cat"><option value="all">All categories</option>
                            <?php foreach ($categories as $c): ?><option value="<?php echo (int)$c->cat_id; ?>"><?php echo htmlspecialchars($c->cat_name); ?></option><?php endforeach; ?>
                            </select></div>
                        <div class="col-md-2 col-6"><label class="small mb-1">Subcategory</label>
                            <select class="form-control form-control-sm" id="ir_sub"><option value="all">All subcategories</option>
                            <?php foreach ($subcategories as $s): ?><option value="<?php echo (int)$s->sub_cat_id; ?>" data-cat="<?php echo (int)$s->parent_cat_id; ?>"><?php echo htmlspecialchars($s->sub_cat_name); ?></option><?php endforeach; ?>
                            </select></div>
                        <div class="col-md-2 col-6"><label class="small mb-1">Item code or name</label>
                            <input type="text" class="form-control form-control-sm" id="ir_item" placeholder="all items"></div>
                        <?php if ($type === 'purchase'): ?>
                        <div class="col-md-2 col-6"><label class="small mb-1">Supplier</label>
                            <select class="form-control form-control-sm" id="ir_supplier"><option value="all">All suppliers</option>
                            <?php foreach ($suppliers as $s): ?><option value="<?php echo (int)$s->sup_id; ?>"><?php echo htmlspecialchars($s->sup_name); ?></option><?php endforeach; ?>
                            </select></div>
                        <div class="col-md-2 col-6"><label class="small mb-1">Received through</label>
                            <select class="form-control form-control-sm" id="ir_source"><option value="all">GRN and Production</option>
                                <option value="grn">GRN only</option><option value="production">Production only</option></select></div>
                        <?php endif; ?>
                        <?php if ($type === 'sales' && $reps): ?>
                        <div class="col-md-2 col-6"><label class="small mb-1">Sales rep</label>
                            <select class="form-control form-control-sm" id="ir_rep"><option value="all">All</option>
                            <?php foreach ($reps as $r): ?><option value="<?php echo (int)$r->rep_id; ?>"><?php echo htmlspecialchars($r->rep_name); ?></option><?php endforeach; ?>
                            </select></div>
                        <?php endif; ?>
                        <?php if ($type === 'stock'): ?>
                        <div class="col-md-2 col-6" style="padding-top:28px;">
                            <label class="small"><input type="checkbox" id="ir_nonzero" checked> Hide items with no stock</label></div>
                        <?php endif; ?>
                        <div class="col-md-2 col-12" style="padding-top:24px;">
                            <button class="btn btn-primary btn-sm" id="ir_go"><i class="fa fa-search"></i> Show</button></div>
                    </div>
                    <p class="text-muted small m-t-10 m-b-0">
                        Sale and cost price are the final amount for one unit, after discounts.
                        <?php if ($type === 'stock'): ?>Sale price is the item's selling price; cost price is what one unit cost on its most recent GRN or production.
                        <?php elseif ($type === 'sales'): ?>Sale price is the average actually charged in the period (bill and line discounts and promotions taken off); cost price is the most recent GRN or production cost.
                        <?php else: ?>Cost price is the average actually paid in the period (GRN discounts shared out); sale price is the item's selling price.<?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <div class="row m-b-10" id="ir_cards" style="display:none;">
                        <div class="col-md-3 col-6"><div class="small text-muted">Items</div><div class="h5" id="ir_c_items">0</div></div>
                        <div class="col-md-3 col-6"><div class="small text-muted"><?php echo htmlspecialchars($qtyLabel); ?></div><div class="h5" id="ir_c_qty">0</div></div>
                        <div class="col-md-3 col-6"><div class="small text-muted">At cost price</div><div class="h5" id="ir_c_cost">0.00</div></div>
                        <div class="col-md-3 col-6"><div class="small text-muted">At sale price</div><div class="h5" id="ir_c_sale">0.00</div></div>
                    </div>
                    <table id="ir_table" class="table table-striped table-bordered table-sm" width="100%">
                        <thead><tr><th>Item Code</th><th>Item Description</th><th>Category</th><th>Subcategory</th>
                            <th style="text-align:right;">Sale Price</th><th style="text-align:right;">Cost Price</th>
                            <th style="text-align:right;"><?php echo htmlspecialchars($qtyLabel); ?></th></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    var BASE = '<?php echo base_url(); ?>', TYPE = '<?php echo $type; ?>';
    function esc(t){ return String(t==null?'':t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
    function money(v){ return v == null ? '-' : (parseFloat(v)||0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function qty(v){ v = parseFloat(v)||0; return (Math.round(v*100)/100).toString(); }

    // Subcategories follow the chosen category.
    $('#ir_cat').change(function(){
        var c = $(this).val();
        $('#ir_sub option[data-cat]').each(function(){ $(this).toggle(c === 'all' || String($(this).data('cat')) === c); });
        if ($('#ir_sub option:selected').is(':hidden')) $('#ir_sub').val('all');
    });

    var dt = null;
    function load(){
        var p = { store: $('#ir_store').val(), category: $('#ir_cat').val(), subcategory: $('#ir_sub').val(), item: $('#ir_item').val() };
        if ($('#ir_from').length) { p.from = $('#ir_from').val(); p.to = $('#ir_to').val();
            if (p.from > p.to) { swal({type:'error', title:'Dates the wrong way round', text:'The From date is after the To date.'}); return; } }
        if ($('#ir_supplier').length) p.supplier = $('#ir_supplier').val();
        if ($('#ir_source').length) p.source = $('#ir_source').val();
        if ($('#ir_rep').length) p.rep = $('#ir_rep').val();
        if ($('#ir_nonzero').length) p.nonzero = $('#ir_nonzero').is(':checked') ? 1 : '';
        $('#ir_go').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $.post(BASE + 'ItemReports/data/' + TYPE, p, null, 'json').then(function(rows){
            $('#ir_go').prop('disabled', false).html('<i class="fa fa-search"></i> Show');
            if (dt) { dt.destroy(); dt = null; }
            var h = '', tq = 0, tc = 0, ts = 0;
            $.each(rows, function(i, r){
                h += '<tr><td>' + esc(r.code) + '</td><td>' + esc(r.name) + '</td><td>' + esc(r.category) + '</td><td>' + esc(r.subcategory) + '</td>'
                   + '<td style="text-align:right;">' + money(r.sale_price) + '</td><td style="text-align:right;">' + money(r.cost_price) + '</td>'
                   + '<td style="text-align:right;">' + qty(r.qty) + '</td></tr>';
                tq += r.qty;
                tc += (TYPE === 'purchase' && r.net_value != null) ? r.net_value : r.qty * (r.cost_price || 0);
                ts += (TYPE === 'sales' && r.net_value != null) ? r.net_value : r.qty * (r.sale_price || 0);
            });
            $('#ir_table tbody').html(h);
            $('#ir_c_items').text(rows.length); $('#ir_c_qty').text(qty(tq)); $('#ir_c_cost').text(money(tc)); $('#ir_c_sale').text(money(ts));
            $('#ir_cards').show();
            dt = $('#ir_table').DataTable({ pageLength: 50, order: [], dom: 'Bfrtip', buttons: ['copy', 'excel', 'pdf', 'print'] });
        }, function(x){
            $('#ir_go').prop('disabled', false).html('<i class="fa fa-search"></i> Show');
            swal({type:'error', title:'Could not load', text:'The report could not be fetched (' + x.status + '). Try again.'});
        });
    }
    $('#ir_go').click(load);
    $('#ir_item').on('keydown', function(e){ if (e.which === 13) load(); });
    load();
});
</script>
