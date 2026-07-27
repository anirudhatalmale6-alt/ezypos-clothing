<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Gate Pass <?php echo $gp->gp_code; ?></title>
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
<style>
    body{ font-family: Arial, sans-serif; color:#222; font-size:13px; }
    .wrap{ max-width:820px; margin:0 auto; padding:18px; }
    .gp-title{ text-align:center; }
    .gp-title h2{ margin:0; letter-spacing:1px; }
    .meta td{ padding:3px 8px; vertical-align:top; }
    table.items{ width:100%; border-collapse:collapse; margin-top:10px; }
    table.items th, table.items td{ border:1px solid #333; padding:6px 8px; font-size:12px; }
    table.items th{ background:#eee; }
    .sig{ margin-top:60px; display:flex; justify-content:space-between; }
    .sig div{ width:30%; text-align:center; border-top:1px solid #333; padding-top:6px; }
    .box{ border:1px solid #333; padding:8px; margin-top:10px; }
    @media print{ #pbtn{ display:none; } }
</style>
</head>
<body onload="window.print && document.getElementById('pbtn')">
<div class="wrap">
    <div class="gp-title">
        <h3><?php foreach($company as $c){ echo $c->config_value; break; } ?></h3>
        <h2>GATE PASS</h2>
        <div>(Materials issued from warehouse to production)</div>
    </div>
    <table class="meta" width="100%">
        <tr>
            <td><strong>Gate Pass No:</strong> <?php echo $gp->gp_code; ?></td>
            <td><strong>Date &amp; Time:</strong> <?php echo $gp->gp_dispatchedat ? $gp->gp_dispatchedat : $gp->gp_createdat; ?></td>
        </tr>
        <tr>
            <td><strong>Source Warehouse:</strong> <?php echo $gp->store_name; ?></td>
            <td><strong>Status:</strong> <?php echo $gp->gp_status; ?></td>
        </tr>
    </table>

    <?php foreach($prods as $p): ?>
    <div class="box">
        <strong>Production ID:</strong> <?php echo $p->p_code; ?>
        &nbsp;|&nbsp; <strong>Raw Material:</strong> <?php echo $p->raw_name; ?> (<?php echo $p->raw_code; ?>)
        &nbsp;|&nbsp; <strong>Qty:</strong> <?php echo number_format($p->p_raw_qty,2); ?> <?php echo $p->p_raw_uom; ?>
        <?php if(!empty($p->tailor_name)): ?>&nbsp;|&nbsp; <strong>Tailor:</strong> <?php echo htmlspecialchars($p->tailor_name); ?><?php endif; ?>
        <table class="items">
            <thead>
                <tr><th>#</th><th>Output Item</th><th>Material Used</th><th>Qty Produced</th></tr>
            </thead>
            <tbody>
                <?php $i=0; foreach($p->outputs as $o){ $i++; ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $o->itm_name; ?> <small>(<?php echo $o->itm_code; ?>)</small></td>
                    <td><?php echo number_format($o->o_material_used,2); ?></td>
                    <td><?php echo number_format($o->o_qty_produced,2); ?></td>
                </tr>
                <?php } if($i==0){ echo '<tr><td colspan="4" style="text-align:center;">No output items recorded</td></tr>'; } ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; ?>

    <?php if(!empty($gp->gp_notes)): ?>
    <div style="margin-top:10px;"><strong>Remarks / Notes:</strong> <?php echo htmlspecialchars($gp->gp_notes); ?></div>
    <?php endif; ?>

    <div class="sig">
        <div>Prepared By<br><?php echo $preparedBy; ?></div>
        <div>Approved By<br><?php echo $approvedBy; ?></div>
        <div>Received By</div>
    </div>

    <div style="text-align:center;margin-top:20px;">
        <button id="pbtn" class="btn btn-primary" onclick="window.print()">Print</button>
    </div>
</div>
</body>
</html>
