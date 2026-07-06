<html>
<head>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        @media print {
            #printBtn { display: none; }
            .no-print { display: none; }
        }
        .gp-header { text-align: center; margin-bottom: 10px; }
        .gp-header h3 { margin: 0; font-size: 18px; }
        .gp-header h4 { margin: 5px 0; font-size: 16px; font-weight: bold; border-bottom: 2px solid #333; padding-bottom: 5px; }
        .gp-info td { padding: 2px 8px; font-size: 12px; }
        .gp-items th { font-size: 11px; background: #f0f0f0; border: 1px solid #ccc; padding: 4px 6px; text-align: center; }
        .gp-items td { font-size: 12px; border: 1px solid #ccc; padding: 4px 6px; }
        .gp-items .text-right { text-align: right; }
        .gp-items .text-center { text-align: center; }
        .signature-line { border-top: 1px solid #333; width: 150px; text-align: center; padding-top: 4px; font-size: 11px; display: inline-block; margin: 0 30px; }
    </style>
</head>
<body>
<div class="container" style="max-width: 700px;">
    <div class="gp-header">
        <h3><?php foreach($config as $c){ echo $c->config_value; } ?></h3>
        <h4>GATE PASS</h4>
    </div>

    <table class="gp-info" style="width:100%; margin-bottom: 10px;">
        <tr>
            <td><strong>Gate Pass No:</strong></td>
            <td><?php echo $gatepass->gp_code; ?></td>
            <td><strong>Date:</strong></td>
            <td><?php echo date('d/m/Y', strtotime($gatepass->gp_date)); ?></td>
        </tr>
        <tr>
            <td><strong>Production:</strong></td>
            <td><?php echo $gatepass->prod_code; ?></td>
            <td><strong>Store:</strong></td>
            <td><?php echo $gatepass->store_name; ?></td>
        </tr>
        <tr>
            <td><strong>Output Item:</strong></td>
            <td><?php echo isset($production->itm_name) ? $production->itm_name : '-'; ?></td>
            <td><strong>Issued By:</strong></td>
            <td><?php echo $gatepass->issued_by_name; ?></td>
        </tr>
        <?php if($production && isset($production->sup_name) && $production->sup_name): ?>
        <tr>
            <td><strong>Tailor:</strong></td>
            <td colspan="3"><?php echo $production->sup_name; ?></td>
        </tr>
        <?php endif; ?>
        <?php if($gatepass->gp_notes): ?>
        <tr>
            <td><strong>Notes:</strong></td>
            <td colspan="3"><?php echo $gatepass->gp_notes; ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <table class="gp-items" style="width:100%; border-collapse: collapse; margin-bottom: 15px;">
        <thead>
            <tr>
                <th style="width:30px;">#</th>
                <th>Item Code</th>
                <th>Material Name</th>
                <th>UOM</th>
                <th>Qty Issued</th>
                <?php
                $has_returns = false;
                foreach($items as $i){ if(floatval($i->gpitem_returned_qty) > 0){ $has_returns = true; break; } }
                if($has_returns):
                ?>
                <th>Qty Returned</th>
                <th>Net Qty</th>
                <?php endif; ?>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n = 1;
            $grand_total = 0;
            foreach($items as $i):
                $net_qty = floatval($i->gpitem_qty) - floatval($i->gpitem_returned_qty);
                $grand_total += floatval($i->gpitem_total);
            ?>
            <tr>
                <td class="text-center"><?php echo $n++; ?></td>
                <td><?php echo $i->itm_code; ?></td>
                <td><?php echo $i->itm_name; ?></td>
                <td class="text-center"><?php echo $i->itm_uom ?: $i->gpitem_uom; ?></td>
                <td class="text-center"><?php echo number_format($i->gpitem_qty, 2); ?></td>
                <?php if($has_returns): ?>
                <td class="text-center"><?php echo number_format($i->gpitem_returned_qty, 2); ?></td>
                <td class="text-center"><?php echo number_format($net_qty, 2); ?></td>
                <?php endif; ?>
                <td class="text-right"><?php echo number_format($i->gpitem_unit_price, 2); ?></td>
                <td class="text-right"><?php echo number_format($i->gpitem_total, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?php echo $has_returns ? 7 : 4; ?>" class="text-right"><strong>Total:</strong></td>
                <td class="text-right" colspan="2"><strong><?php echo number_format($grand_total, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <div style="text-align: center; margin-top: 40px;">
        <span class="signature-line">Issued By</span>
        <span class="signature-line">Received By</span>
        <span class="signature-line">Authorized By</span>
    </div>

    <div class="text-center" style="margin-top: 15px; font-size: 10px; color: #888;">
        Status: <?php echo $gatepass->gp_status; ?> | Printed: <?php echo date('d/m/Y H:i'); ?>
    </div>

    <div class="text-center no-print" style="margin-top: 20px;">
        <button id="printBtn" class="btn btn-primary" onclick="window.print();">
            <i class="fa fa-print"></i> Print Gate Pass
        </button>
    </div>
</div>
</body>
</html>
