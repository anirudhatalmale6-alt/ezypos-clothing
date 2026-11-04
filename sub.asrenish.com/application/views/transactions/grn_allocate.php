<!-- ==============================================================
     Allocate GRN - send what a GRN brought in out to the branches.

     Enter how many of each item go to each branch. With "last branch takes
     the rest" ticked, typing 50 for Branch A puts the remaining 50 under the
     last branch by itself. Saving makes one ordinary stock transfer per
     branch (marked with this GRN), so the branches accept them on the Stock
     Transfers page exactly as they do today. The GRN's received quantity is
     never changed - Allocated and Available are worked out from its
     transfers.
     ============================================================== -->
<?php $g = $a['grn']; $br = $a['branches']; ?>
<div class="wrapper">
    <div class="container-fluid">
        <?php if (!$ready): ?>
        <div class="alert alert-warning">The database needs updating before a GRN can be allocated:
            open migrate.php and run <b>step 15</b>.</div>
        <?php endif; ?>
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                        <h4 class="header-title m-t-0 m-b-0"><i class="fa fa-random"></i> Allocate GRN
                            <?php echo htmlspecialchars($g->grn_code ? $g->grn_code : '#'.$g->grn_id); ?> to branches</h4>
                        <div class="small text-muted">
                            Supplier: <b><?php echo htmlspecialchars($g->sup_name ? $g->sup_name : '-'); ?></b> &nbsp;
                            Received at: <b><?php echo htmlspecialchars($g->store_name); ?></b> &nbsp;
                            Date: <b><?php echo htmlspecialchars(substr($g->grn_date, 0, 10)); ?></b>
                        </div>
                    </div>

                    <?php if (!$br): ?>
                    <div class="alert alert-info">There is no other branch to send this GRN's goods to.</div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="al_table">
                            <thead class="bg-light">
                                <tr>
                                    <th>Code</th><th>Item</th>
                                    <th style="text-align:right;">Received</th>
                                    <th style="text-align:right;">Allocated</th>
                                    <th style="text-align:right;">Available</th>
                                    <?php foreach ($br as $b): ?>
                                    <th style="text-align:right;min-width:110px;"><?php echo htmlspecialchars($b->store_name); ?></th>
                                    <?php endforeach; ?>
                                    <th style="text-align:right;">Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($a['lines'] as $l): ?>
                                <tr data-item="<?php echo (int)$l->item_id; ?>" data-avail="<?php echo $l->available; ?>">
                                    <td><?php echo htmlspecialchars($l->itm_code); ?></td>
                                    <td><?php echo htmlspecialchars($l->itm_name); ?>
                                        <?php if ($l->in_store < $l->received - $l->allocated): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($g->store_name); ?> holds <?php echo 0 + $l->in_store; ?> of it now</small>
                                        <?php endif; ?></td>
                                    <td style="text-align:right;"><?php echo 0 + $l->received; ?></td>
                                    <td style="text-align:right;"><?php echo 0 + $l->allocated; ?></td>
                                    <td style="text-align:right;font-weight:600;"><?php echo 0 + $l->available; ?></td>
                                    <?php foreach ($br as $i => $b): ?>
                                    <td><input type="number" min="0" step="any" class="form-control form-control-sm al-q<?php echo $i === count($br) - 1 ? ' al-last' : ''; ?>"
                                               data-store="<?php echo (int)$b->store_id; ?>" style="text-align:right;"
                                               <?php echo $l->available > 0 ? '' : 'disabled'; ?>></td>
                                    <?php endforeach; ?>
                                    <td style="text-align:right;" class="al-rem"><?php echo 0 + $l->available; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (count($br) > 1): ?>
                            <label class="small"><input type="checkbox" id="al_auto" checked>
                                <b><?php echo htmlspecialchars($br[count($br) - 1]->store_name); ?></b> takes whatever is left</label>
                            <?php endif; ?>
                            <input type="text" class="form-control form-control-sm m-t-5" id="al_notes" maxlength="200" placeholder="Note on the transfers (optional)">
                        </div>
                        <div class="col-md-6 text-right" style="padding-top:8px;">
                            <div id="al_error" class="alert alert-danger text-left" style="display:none;white-space:pre-line;"></div>
                            <a href="<?php echo base_url('show-all-grn'); ?>" class="btn btn-outline-secondary">Skip</a>
                            <?php if ($ready): ?>
                            <button class="btn btn-success" id="al_save" type="button" disabled><i class="fa fa-random"></i> Create transfers</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="small text-muted m-t-10 m-b-0">The transfers wait for each branch to accept them on the Stock Transfers page,
                        as transfers do now. Anything not allocated stays at <?php echo htmlspecialchars($g->store_name); ?>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($a['transfers']): ?>
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-10"><i class="fa fa-exchange"></i> Transfers already made from this GRN</h4>
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="bg-light"><tr><th>Transfer</th><th>To</th><th style="text-align:right;">Qty</th><th>Status</th><th>Created</th></tr></thead>
                        <tbody>
                        <?php foreach ($a['transfers'] as $t): ?>
                            <tr><td>#<?php echo (int)$t->transfer_id; ?></td><td><?php echo htmlspecialchars($t->to_store_name); ?></td>
                                <td style="text-align:right;"><?php echo 0 + $t->qty; ?></td>
                                <td><?php echo htmlspecialchars($t->transfer_status); ?></td>
                                <td><?php echo htmlspecialchars($t->transfer_created_at); ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<script>
$(function(){
    var BASE = '<?php echo base_url(); ?>', GRN = <?php echo (int)$g->grn_id; ?>;
    function n(v){ v = parseFloat(v); return isNaN(v) ? 0 : v; }
    function r2(v){ return Math.round(v * 100) / 100; }

    function row(tr, typedInLast){
        var avail = n($(tr).data('avail'));
        if ($('#al_auto').is(':checked') && !typedInLast) {
            var others = 0;
            $(tr).find('.al-q').not('.al-last').each(function(){ others += n($(this).val()); });
            var rest = r2(avail - others);
            $(tr).find('.al-last').val(rest > 0 && others > 0 ? rest : (others > 0 ? 0 : ''));
        }
        var sum = 0; $(tr).find('.al-q').each(function(){ sum += n($(this).val()); });
        var rem = r2(avail - sum);
        $(tr).find('.al-rem').text(rem).toggleClass('text-danger font-weight-bold', rem < 0);
        $(tr).toggleClass('table-danger', rem < 0);
    }
    function check(){
        var any = false, bad = false;
        $('#al_table tbody tr').each(function(){
            if (n($(this).find('.al-rem').text()) < 0) bad = true;
            $(this).find('.al-q').each(function(){ if (n($(this).val()) > 0) any = true; if (n($(this).val()) < 0) bad = true; });
        });
        $('#al_save').prop('disabled', !any || bad);
    }
    $('#al_table').on('input', '.al-q', function(){
        $('#al_error').hide();
        row($(this).closest('tr'), $(this).hasClass('al-last')); check();
    });
    $('#al_auto').change(function(){ $('#al_table tbody tr').each(function(){ row(this, false); }); check(); });

    $('#al_save').click(function(){
        var alloc = {};
        $('#al_table tbody tr').each(function(){
            var item = $(this).data('item');
            $(this).find('.al-q').each(function(){
                var q = n($(this).val()); if (q <= 0) return;
                var s = $(this).data('store'); alloc[s] = alloc[s] || {}; alloc[s][item] = q;
            });
        });
        var $b = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating...');
        $.post(BASE + 'StockTransfer/saveAllocation', { grn_id: GRN, notes: $('#al_notes').val(), alloc: JSON.stringify(alloc) }, null, 'json')
         .then(function(r){
            if (!r.ok) { $('#al_error').text(r.msg).show(); $b.prop('disabled', false).html('<i class="fa fa-random"></i> Create transfers'); return; }
            swal({ type: 'success', title: 'Transfers created',
                   text: 'Transfer' + (r.transfers.length > 1 ? 's #' : ' #') + r.transfers.join(', #')
                       + ' - waiting for each branch to accept on the Stock Transfers page.' })
              .then(function(){ location.reload(); }, function(){ location.reload(); });
         }, function(x){
            $('#al_error').text('The allocation did not reach the server (' + x.status + '). Nothing was created - try again.').show();
            $b.prop('disabled', false).html('<i class="fa fa-random"></i> Create transfers');
         });
    });
});
</script>
