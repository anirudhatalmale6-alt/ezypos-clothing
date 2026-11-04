<!-- ==============================================================
     Supplier Returns - every return to a supplier, newest first.
     Returns made with the new screen keep their GRN and can be edited;
     ones made with the old popup were never linked to a GRN and are
     shown read-only.
     ============================================================== -->
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title m-t-0 m-b-0"><i class="fa fa-reply"></i> Supplier Returns</h4>
                        <a href="<?php echo base_url('supplier-return'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Supplier Return</a>
                    </div>
                    <?php if (!$linked): ?>
                    <div class="alert alert-warning">The database needs updating before returns can be saved here:
                        open migrate.php and run <b>step 15</b>.</div>
                    <?php endif; ?>
                    <table id="srList" class="table table-striped table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>No</th><th>Date</th><th>GRN</th><th>Supplier</th><th>Store</th>
                                <th style="text-align:right;">Items</th><th style="text-align:right;">Qty</th>
                                <th style="text-align:right;">Value</th><th>Note</th><th>By</th><th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($returns as $r): ?>
                            <tr>
                                <td><?php echo (int)$r->suprtrn_id; ?></td>
                                <td><?php echo htmlspecialchars(substr($r->suprtrn_createdat, 0, 16)); ?></td>
                                <td><?php echo $r->grn_code !== null && $r->grn_code !== '' ? htmlspecialchars($r->grn_code)
                                        : ((int)$r->suprtrn_grnID ? '#'.(int)$r->suprtrn_grnID : '<span class="text-muted">not linked</span>'); ?></td>
                                <td><?php echo htmlspecialchars($r->sup_name); ?></td>
                                <td><?php echo htmlspecialchars($r->store_name); ?></td>
                                <td style="text-align:right;"><?php echo (int)$r->lines_count; ?></td>
                                <td style="text-align:right;"><?php echo 0 + $r->qty; ?></td>
                                <td style="text-align:right;"><?php echo number_format($r->suprtrn_totalRtrn, 2); ?></td>
                                <td><?php echo htmlspecialchars($r->suprtrn_note); ?></td>
                                <td><?php echo htmlspecialchars($r->user_name); ?></td>
                                <td>
                                    <?php if ((int)$r->suprtrn_grnID): ?>
                                    <a href="<?php echo base_url('supplier-return/'.(int)$r->suprtrn_id); ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-pencil"></i> Edit</a>
                                    <?php else: ?>
                                    <span class="text-muted" title="Made with the old screen, which did not record the GRN">old screen</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){ $('#srList').DataTable({ pageLength: 25, order: [] }); });
</script>
