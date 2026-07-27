<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Final Bill <?php echo $order ? $order->prodsale_code : ''; ?></title>
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
<style>
  body{ font-family: Arial, sans-serif; color:#222; font-size:13px; }
  .wrap{ max-width:760px; margin:0 auto; padding:18px; }
  .head{ text-align:center; }
  .head h3{ margin:0; }
  .doc-title{ background:#27ae60; color:#fff; text-align:center; padding:6px; letter-spacing:2px; margin:12px 0; font-size:16px; }
  .meta td{ padding:3px 8px; vertical-align:top; }
  table.items{ width:100%; border-collapse:collapse; margin-top:8px; }
  table.items th, table.items td{ border:1px solid #333; padding:6px 8px; font-size:12px; }
  table.items th{ background:#eee; }
  table.pay{ width:100%; border-collapse:collapse; margin-top:8px; }
  table.pay th, table.pay td{ border:1px solid #999; padding:5px 8px; font-size:12px; }
  table.pay th{ background:#f3f3f3; }
  .totals{ width:340px; float:right; margin-top:10px; }
  .totals td{ padding:4px 8px; }
  .totals .grand{ font-size:15px; font-weight:bold; border-top:2px solid #333; }
  .note{ clear:both; padding-top:30px; font-size:12px; color:#555; }
  @media print{ #pbtn{ display:none; } }
</style>
</head>
<body>
<div class="wrap">
  <?php if(!$order): ?>
    <div class="alert alert-danger">Order not found.</div>
  <?php else: ?>
  <div class="head">
    <h3><?php if($config){ foreach($config as $c){ echo $c->config_value; break; } } ?></h3>
    <div>Tailoring Order</div>
  </div>
  <div class="doc-title">FINAL BILL</div>

  <table class="meta" width="100%">
    <tr>
      <td><strong>Order No:</strong> <?php echo $order->prodsale_code; ?></td>
      <td><strong>Order Date:</strong> <?php echo $order->prodsale_date; ?></td>
    </tr>
    <tr>
      <td><strong>Customer:</strong> <?php echo htmlspecialchars($order->cus_name); ?></td>
      <td><strong>Delivery Date:</strong> <?php echo $order->prodsale_delivery_date; ?></td>
    </tr>
    <tr>
      <td><strong>Contact:</strong> <?php echo htmlspecialchars($order->cus_contact); ?></td>
      <td><strong>Status:</strong> <?php echo $order->prodsale_status; ?></td>
    </tr>
    <?php if(!empty($order->tailor_name)): ?>
    <tr><td><strong>Tailor:</strong> <?php echo htmlspecialchars($order->tailor_name); ?></td><td><strong>Store:</strong> <?php echo htmlspecialchars($order->store_name); ?></td></tr>
    <?php else: ?>
    <tr><td colspan="2"><strong>Store:</strong> <?php echo htmlspecialchars($order->store_name); ?></td></tr>
    <?php endif; ?>
  </table>

  <table class="items">
    <thead>
      <tr><th>#</th><th>Item / Material</th><th class="text-right">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th></tr>
    </thead>
    <tbody>
      <?php $i=0; if($items){ foreach($items as $m){ $i++; ?>
      <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $m->itm_code; ?> - <?php echo htmlspecialchars($m->itm_name); ?></td>
        <td class="text-right"><?php echo number_format($m->prodsaleitem_qty,2); ?> <?php echo $m->itm_uom; ?></td>
        <td class="text-right"><?php echo number_format($m->prodsaleitem_unit_price,2); ?></td>
        <td class="text-right"><?php echo number_format($m->prodsaleitem_total,2); ?></td>
      </tr>
      <?php }} ?>
      <?php if($services){ foreach($services as $s){ $i++; ?>
      <tr>
        <td><?php echo $i; ?></td>
        <td><em>Service:</em> <?php echo htmlspecialchars($s->prodsvc_description); ?></td>
        <td class="text-right">-</td>
        <td class="text-right">-</td>
        <td class="text-right"><?php echo number_format($s->prodsvc_charge,2); ?></td>
      </tr>
      <?php }} ?>
      <?php if($i==0){ echo '<tr><td colspan="5" class="text-center">No items.</td></tr>'; } ?>
    </tbody>
  </table>

  <?php
    $matCost = floatval($order->prodsale_material_cost);
    $total   = floatval($order->prodsale_total);
    $paid    = floatval($order->prodsale_paid);
    $balance = floatval($order->prodsale_balance);
  ?>
  <table class="totals">
    <tr><td>Material Cost</td><td class="text-right">LKR <?php echo number_format($matCost,2); ?></td></tr>
    <tr><td>Final Tailoring / Service Charges</td><td class="text-right">LKR <?php echo number_format($total-$matCost,2); ?></td></tr>
    <tr class="grand"><td>Final Total</td><td class="text-right">LKR <?php echo number_format($total,2); ?></td></tr>
    <tr><td>Total Paid</td><td class="text-right">LKR <?php echo number_format($paid,2); ?></td></tr>
    <tr class="grand"><td>Balance</td><td class="text-right">LKR <?php echo number_format($balance,2); ?></td></tr>
  </table>

  <div style="clear:both;"></div>
  <h6 style="margin-top:16px;">Payment Summary</h6>
  <table class="pay">
    <thead><tr><th>#</th><th>Date</th><th>Method</th><th>Reference</th><th class="text-right">Amount</th></tr></thead>
    <tbody>
      <?php $j=0; if(!empty($payments)){ foreach($payments as $p){ $j++; ?>
      <tr>
        <td><?php echo $j; ?></td>
        <td><?php echo isset($p->psp_created_at)?$p->psp_created_at:''; ?></td>
        <td><?php echo $p->psp_method; ?></td>
        <td><?php echo isset($p->psp_card_ref)?htmlspecialchars($p->psp_card_ref):''; ?></td>
        <td class="text-right"><?php echo number_format($p->psp_amount,2); ?></td>
      </tr>
      <?php }} if($j==0){ echo '<tr><td colspan="5" class="text-center">No payments recorded.</td></tr>'; } ?>
    </tbody>
  </table>

  <div class="note">
    <?php if(!empty($order->prodsale_notes)): ?><strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($order->prodsale_notes)); ?><br><br><?php endif; ?>
    Thank you for your business.
  </div>

  <div style="text-align:center;margin-top:20px;">
    <button id="pbtn" class="btn btn-success" onclick="window.print()">Print Final Bill</button>
  </div>
  <?php endif; ?>
</div>
</body>
</html>
