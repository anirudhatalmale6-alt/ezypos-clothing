<!-- ==============================================================
     Supplier Return - new or edit.

     Type the GRN number or code from the paperwork, enter how many of each
     item go back, Save. The server works out the value from the GRN's own
     prices (after its discounts), checks every quantity against what is
     still in stock from that GRN, and saves lines, stock, the GRN batch and
     the stock log together.
     ============================================================== -->
<?php
$editing = !empty($ret);
$editable = !$editing || (int)$ret->suprtrn_grnID > 0;
?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title m-t-0 m-b-0"><i class="fa fa-reply"></i>
                            <?php echo $editing ? 'Edit Supplier Return #'.(int)$ret->suprtrn_id : 'New Supplier Return'; ?></h4>
                        <a href="<?php echo base_url('supplier-returns'); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa fa-list"></i> All Supplier Returns</a>
                    </div>

                    <?php if (!$linked): ?>
                    <div class="alert alert-warning">The database needs updating before returns can be saved here:
                        open migrate.php and run <b>step 15</b>.</div>
                    <?php elseif (!$editable): ?>
                    <div class="alert alert-info">This return was made with the old screen, which did not record which GRN it
                        came from, so it cannot be edited. Its figures are unchanged.</div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-4">
                            <label class="small mb-1">GRN number or code<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="sr_grn" autocomplete="off"
                                       value="<?php echo $grn ? htmlspecialchars($grn->grn_code !== '' && $grn->grn_code !== null ? $grn->grn_code : $grn->grn_id) : ''; ?>"
                                       <?php echo $editing ? 'readonly' : ''; ?> placeholder="e.g. 501 or GRN-501">
                                <?php if (!$editing): ?>
                                <div class="input-group-append"><button class="btn btn-primary" id="sr_find" type="button"><i class="fa fa-search"></i> Find</button></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-8" id="sr_grn_info" style="padding-top:26px;"></div>
                    </div>

                    <div class="table-responsive m-t-15">
                        <table class="table table-bordered table-sm" id="sr_table">
                            <thead>
                                <tr>
                                    <th>Code</th><th>Item</th>
                                    <th style="text-align:right;">Received</th>
                                    <th style="text-align:right;">Unit cost</th>
                                    <th style="text-align:right;">Can return</th>
                                    <th style="text-align:right;width:130px;">Return qty</th>
                                    <th style="text-align:right;">Value</th>
                                </tr>
                            </thead>
                            <tbody><tr><td colspan="7" class="text-muted text-center">Find a GRN to list its items.</td></tr></tbody>
                            <tfoot>
                                <tr style="font-weight:600;"><td colspan="5" style="text-align:right;">Total</td>
                                    <td style="text-align:right;" id="sr_tqty">0</td><td style="text-align:right;" id="sr_tval">0.00</td></tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="sr_error" class="alert alert-danger" style="display:none;white-space:pre-line;"></div>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="small mb-1">Note (reason, supplier reference)</label>
                            <input type="text" class="form-control" id="sr_note" maxlength="255"
                                   value="<?php echo $editing ? htmlspecialchars($ret->suprtrn_note) : ''; ?>">
                        </div>
                        <div class="col-md-4" style="padding-top:26px;text-align:right;">
                            <?php if ($linked && $editable): ?>
                            <button class="btn btn-success" id="sr_save" type="button" disabled><i class="fa fa-save"></i> Save Return</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    var BASE = '<?php echo base_url(); ?>';
    var RETURN_ID = <?php echo $editing ? (int)$ret->suprtrn_id : 0; ?>;
    var GRN_REF = '';
    function esc(t){ return String(t==null?'':t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
    function money(v){ return (parseFloat(v)||0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }

    function render(grn, lines){
        GRN_REF = String(grn.grn_id);
        $('#sr_grn_info').html('<b>Supplier:</b> ' + esc(grn.sup_name) + ' &nbsp; <b>Store:</b> ' + esc(grn.store_name || '-')
            + ' &nbsp; <b>GRN date:</b> ' + esc(String(grn.grn_date || '').substring(0,10)));
        var h = '';
        $.each(lines, function(i, l){
            h += '<tr data-id="' + l.item_id + '" data-cost="' + l.unit_cost + '" data-max="' + l.available + '">'
               + '<td>' + esc(l.code) + '</td><td>' + esc(l.name) + '</td>'
               + '<td style="text-align:right;">' + (0 + l.received) + '</td>'
               + '<td style="text-align:right;">' + money(l.unit_cost) + '</td>'
               + '<td style="text-align:right;">' + (0 + l.available) + '</td>'
               + '<td><input type="number" min="0" step="any" class="form-control form-control-sm sr-qty" style="text-align:right;"'
               + ' value="' + (l.returning ? l.returning : '') + '"' + (l.available > 0 ? '' : ' disabled') + '></td>'
               + '<td style="text-align:right;" class="sr-val">0.00</td></tr>';
        });
        $('#sr_table tbody').html(h || '<tr><td colspan="7" class="text-muted text-center">This GRN has no items.</td></tr>');
        recalc();
    }

    function recalc(){
        var tq = 0, tv = 0, bad = false;
        $('#sr_table tbody tr[data-id]').each(function(){
            var q = parseFloat($(this).find('.sr-qty').val()) || 0;
            var max = parseFloat($(this).data('max')) || 0;
            var over = q > max + 0.0001 || q < 0;
            $(this).toggleClass('table-danger', over); bad = bad || over;
            var v = q * (parseFloat($(this).data('cost')) || 0);
            $(this).find('.sr-val').text(money(v));
            tq += q; tv += v;
        });
        $('#sr_tqty').text(Math.round(tq * 100) / 100); $('#sr_tval').text(money(tv));
        $('#sr_save').prop('disabled', bad || tq <= 0);
    }
    $('#sr_table').on('input', '.sr-qty', function(){ $('#sr_error').hide(); recalc(); });

    function find(){
        var ref = $.trim($('#sr_grn').val());
        if (!ref) return;
        $('#sr_error').hide();
        $.getJSON(BASE + 'SupReturns/findGrn', { ref: ref }).then(function(r){
            if (!r.ok) { $('#sr_table tbody').html('<tr><td colspan="7" class="text-danger text-center">' + esc(r.msg) + '</td></tr>');
                         $('#sr_grn_info').html(''); GRN_REF = ''; recalc(); return; }
            render(r.grn, r.lines);
        }, function(x){ $('#sr_error').text('Could not look the GRN up (' + x.status + '). Try again.').show(); });
    }
    $('#sr_find').click(find);
    $('#sr_grn').on('keydown', function(e){ if (e.which === 13) { e.preventDefault(); find(); } });

    $('#sr_save').click(function(){
        var lines = {};
        $('#sr_table tbody tr[data-id]').each(function(){
            var q = parseFloat($(this).find('.sr-qty').val()) || 0;
            if (q > 0) lines[$(this).data('id')] = q;
        });
        var $b = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.post(BASE + 'SupReturns/save', { return_id: RETURN_ID, grn_ref: GRN_REF, note: $('#sr_note').val(),
                                           lines: JSON.stringify(lines) }, null, 'json')
         .then(function(r){
            if (!r.ok) { $('#sr_error').text(r.msg).show(); $b.prop('disabled', false).html('<i class="fa fa-save"></i> Save Return'); return; }
            swal({ type: 'success', title: 'Supplier return saved', text: 'Return #' + r.id + ' - stock has been reduced.' })
              .then(function(){ location.href = BASE + 'supplier-returns'; });
         }, function(x){
            $('#sr_error').text('The save did not reach the server (' + x.status + '). Nothing was changed - try again.').show();
            $b.prop('disabled', false).html('<i class="fa fa-save"></i> Save Return');
         });
    });

    <?php if ($grn): ?>
    render(<?php echo json_encode($grn); ?>, <?php echo json_encode($lines); ?>);
    <?php endif; ?>
    <?php if (!$editable): ?>
    $('#sr_table').find('input').prop('disabled', true);
    <?php endif; ?>
});
</script>
