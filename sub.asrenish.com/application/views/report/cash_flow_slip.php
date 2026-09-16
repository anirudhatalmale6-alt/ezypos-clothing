<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cash Flow</title>
    <style>
        /* 80 mm thermal, same width as the sale receipt so it comes off the
           same printer without changing any setting. */
        @page { size: 80mm auto; margin: 0; }
        body { width: 74mm; margin: 0 auto; padding: 4mm 0;
               font-family: "Courier New", monospace; font-size: 12px; color: #000; }
        h2 { font-size: 15px; text-align: center; margin: 0 0 2px; }
        .c  { text-align: center; }
        .r  { text-align: right; }
        hr  { border: none; border-top: 1px dashed #000; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; }
        td, th { font-size: 12px; padding: 1px 0; vertical-align: top; }
        td.r, th.r { padding-left: 6px; white-space: nowrap; }
        th { border-bottom: 1px solid #000; text-align: left; }
        .big  { font-size: 14px; font-weight: bold; }
        .head { font-weight: bold; border-bottom: 1px solid #000; }
        @media print { .noprint { display: none; } }
    </style>
</head>
<body onload="window.print();">
<?php
    $cn = ($comName && isset($comName[0])) ? $comName[0]->config_value : 'Handloom Gallery';
    $a1 = ($addLine1 && isset($addLine1[0])) ? trim($addLine1[0]->config_value) : '';
    $tel= ($telephone && isset($telephone[0])) ? trim($telephone[0]->config_value) : '';
?>
<h2><?php echo htmlspecialchars($cn); ?></h2>
<?php if ($a1 !== '') { ?><div class="c"><?php echo htmlspecialchars($a1); ?></div><?php } ?>
<?php if ($tel !== '') { ?><div class="c">Tel: <?php echo htmlspecialchars($tel); ?></div><?php } ?>
<hr>
<div class="c"><strong>CASH FLOW</strong></div>
<hr>
<div>From&nbsp;&nbsp;: <?php echo htmlspecialchars($from); ?></div>
<div>To&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo htmlspecialchars($to); ?></div>
<div>Branch: <?php echo htmlspecialchars($storeName); ?></div>
<div>Method: <?php echo htmlspecialchars($methodName); ?></div>
<div>Printed: <?php echo date('Y-m-d H:i'); ?></div>
<hr>

<!-- Per payment method, which is the figure the till is counted against. -->
<table>
    <thead><tr><th>Method</th><th class="r">In</th><th class="r">Out</th><th class="r">Net</th></tr></thead>
    <tbody>
    <?php if (empty($summary['methods'])) { ?>
        <tr><td colspan="4" class="c">No money moved in this period.</td></tr>
    <?php } else {
        foreach ($summary['methods'] as $m) { ?>
        <tr>
            <td><?php echo htmlspecialchars($m->method_name); ?></td>
            <td class="r"><?php echo number_format($m->total_in, 2); ?></td>
            <td class="r"><?php echo $m->total_out > 0 ? number_format($m->total_out, 2) : '-'; ?></td>
            <td class="r"><?php echo number_format($m->total_amount, 2); ?></td>
        </tr>
    <?php }} ?>
    </tbody>
</table>
<hr>

<table>
    <tr class="big"><td>MONEY IN</td><td class="r"><?php echo number_format($summary['total_in'], 2); ?></td></tr>
    <tr class="big"><td>MONEY OUT</td><td class="r"><?php echo number_format($summary['total_out'], 2); ?></td></tr>
    <tr class="big"><td>NET CASH FLOW</td><td class="r"><?php echo number_format($summary['net'], 2); ?></td></tr>
</table>
<hr>

<!-- Cash on its own. This is what should physically be in the drawer, and it
     is the number the shop actually counts at the end of the day. -->
<table>
    <tr><td class="head" colspan="2">CASH ONLY</td></tr>
    <tr><td>Cash in</td><td class="r"><?php echo number_format($bySource['cash_in'], 2); ?></td></tr>
    <tr><td>Cash out</td><td class="r"><?php echo number_format($bySource['cash_out'], 2); ?></td></tr>
    <tr class="big"><td>CASH IN DRAWER</td><td class="r"><?php echo number_format($bySource['cash_net'], 2); ?></td></tr>
</table>
<hr>

<!-- Where the money came from and went, so a difference can be traced to a
     part of the day's trading rather than hunted for. -->
<table>
    <tr><td class="head" colspan="2">WHERE IT CAME FROM</td></tr>
    <tr><td>Sales</td><td class="r"><?php echo number_format($bySource['sale_in'], 2); ?></td></tr>
    <?php if ($voucherSales > 0) { ?>
    <tr><td>&nbsp;&nbsp;of which vouchers</td><td class="r"><?php echo number_format($voucherSales, 2); ?></td></tr>
    <?php } ?>
    <tr><td>Tailoring payments</td><td class="r"><?php echo number_format($bySource['tailoring_in'], 2); ?></td></tr>
    <tr><td>Exchange top-ups</td><td class="r"><?php echo number_format($bySource['exchange_in'], 2); ?></td></tr>
    <tr><td class="head" colspan="2">WHERE IT WENT</td></tr>
    <tr><td>Refunds</td><td class="r"><?php echo number_format($bySource['return_out'] + $bySource['exchange_out'], 2); ?></td></tr>
    <tr><td>Change given</td><td class="r"><?php echo number_format($bySource['change_out'], 2); ?></td></tr>
</table>
<hr>
<div class="c">Printed by <?php echo htmlspecialchars($userName); ?></div>
<br>
<div class="c noprint"><button onclick="window.print();">Print</button></div>
</body>
</html>
