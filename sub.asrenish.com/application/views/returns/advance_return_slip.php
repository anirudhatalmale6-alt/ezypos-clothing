<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Advance Return</title>
    <style>
        /* 80 mm thermal, same width as the sale receipt. */
        @page { size: 80mm auto; margin: 0; }
        body { width: 74mm; margin: 0 auto; padding: 4mm 0;
               font-family: "Courier New", monospace; font-size: 12px; color: #000; }
        h2 { font-size: 15px; text-align: center; margin: 0 0 2px; }
        .c { text-align: center; }
        .r { text-align: right; }
        hr { border: none; border-top: 1px dashed #000; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; }
        td, th { font-size: 12px; padding: 1px 0; vertical-align: top; }
        /* The three number columns run into each other without this - on an
           80 mm slip there is no room to spare, but none at all is unreadable. */
        td.r, th.r { padding-left: 6px; white-space: nowrap; }
        th { border-bottom: 1px solid #000; text-align: left; }
        .tot { font-size: 14px; font-weight: bold; }
        @media print { .noprint { display: none; } }
    </style>
</head>
<body onload="window.print();">
<?php if (!$ret) { ?>
    <p class="c">That return could not be found.</p>
<?php } else { ?>
    <h2><?php echo htmlspecialchars($comName ? $comName[0]->config_value : 'Handloom Gallery'); ?></h2>
    <?php if (!empty($addLine1) && trim($addLine1[0]->config_value) !== '') { ?>
        <div class="c"><?php echo htmlspecialchars($addLine1[0]->config_value); ?></div>
    <?php } ?>
    <?php if (!empty($telephone) && trim($telephone[0]->config_value) !== '') { ?>
        <div class="c">Tel: <?php echo htmlspecialchars($telephone[0]->config_value); ?></div>
    <?php } ?>
    <hr>
    <div class="c"><strong>GOODS RETURN</strong></div>
    <hr>
    <div>Ref&nbsp;&nbsp;&nbsp;: <?php echo htmlspecialchars($ret->adv_ref_no); ?></div>
    <div>Date&nbsp;&nbsp;: <?php echo htmlspecialchars($ret->adv_created_at); ?></div>
    <div>Branch: <?php echo htmlspecialchars($ret->store_name); ?></div>
    <?php if ($ret->cus_name) { ?><div>Customer: <?php echo htmlspecialchars($ret->cus_name); ?></div><?php } ?>
    <div>Bill&nbsp;&nbsp;: <?php echo $ret->adv_without_bill ? 'not produced' : htmlspecialchars($ret->adv_bill_ref); ?></div>
    <?php if ($ret->adv_reason) { ?><div>Reason: <?php echo htmlspecialchars($ret->adv_reason); ?></div><?php } ?>
    <hr>
    <table>
        <thead><tr><th>Item</th><th class="r">Qty</th><th class="r">Price</th><th class="r">Total</th></tr></thead>
        <tbody>
        <?php foreach ($ret->items as $i) { ?>
            <tr>
                <td><?php echo htmlspecialchars($i->advi_item_code); ?><br>
                    <small><?php echo htmlspecialchars($i->advi_item_name); ?></small></td>
                <td class="r"><?php echo number_format($i->advi_qty, 2); ?></td>
                <td class="r"><?php echo number_format($i->advi_price, 2); ?></td>
                <td class="r"><?php echo number_format($i->advi_total, 2); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <hr>
    <table>
        <tr class="tot"><td>REFUND</td><td class="r">LKR <?php echo number_format($ret->adv_total, 2); ?></td></tr>
        <tr><td>Paid as</td><td class="r"><?php echo $ret->adv_refund_mode === 'store_credit' ? 'Store credit' : 'Cash'; ?></td></tr>
        <tr><td>Stock</td><td class="r"><?php echo $ret->adv_restock ? 'returned to shelf' : 'not restocked'; ?></td></tr>
    </table>
    <hr>
    <div class="c">Served by <?php echo htmlspecialchars($ret->user_name); ?></div>
    <div class="c">Thank you</div>
    <br>
    <div class="c noprint"><button onclick="window.print();">Print</button></div>
<?php } ?>
</body>
</html>
