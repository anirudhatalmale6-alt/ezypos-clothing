<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>GRN <?php echo $grn->grn_code ? $grn->grn_code : ('#'.$grn->grn_id); ?></title>
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
<style>
  body{ font-family: Arial, sans-serif; color:#222; font-size:13px; }
  .wrap{ max-width:760px; margin:0 auto; padding:18px; }
  .head{ text-align:center; }
  .head h3{ margin:0; }
  .doc-title{ background:#34495e; color:#fff; text-align:center; padding:6px; letter-spacing:2px; margin:12px 0; font-size:16px; }
  .meta td{ padding:3px 8px; vertical-align:top; }
  table.items{ width:100%; border-collapse:collapse; margin-top:8px; }
  table.items th, table.items td{ border:1px solid #333; padding:6px 8px; font-size:12px; }
  table.items th{ background:#eee; }
  .totals{ width:320px; float:right; margin-top:10px; }
  .totals td{ padding:4px 8px; }
  .totals .grand{ font-size:15px; font-weight:bold; border-top:2px solid #333; }
  @media print{ #pbtn{ display:none; } }
</style>
</head>
<body>
<div class="wrap">
  <div class="head">
    <h3><?php if($company){ foreach($company as $c){ echo $c->config_value; break; } } ?></h3>
    <div>Goods Received Note</div>
  </div>
  <div class="doc-title">GRN</div>

  <table class="meta" width="100%">
    <tr>
      <td><strong>GRN No:</strong> <?php echo $grn->grn_code ? $grn->grn_code : ('#'.$grn->grn_id); ?></td>
      <td><strong>Date:</strong> <?php echo $grn->grn_date; ?></td>
    </tr>
    <tr>
      <td><strong>Supplier:</strong> <?php echo htmlspecialchars($grn->sup_name); ?></td>
      <td><strong>Store:</strong> <?php echo htmlspecialchars($grn->store_name); ?></td>
    </tr>
    <tr>
      <td><strong>Supplier Contact:</strong> <?php echo htmlspecialchars($grn->sup_contact); ?></td>
      <td><strong>Received By:</strong> <?php echo htmlspecialchars($grn->user_name); ?></td>
    </tr>
  </table>

  <table class="items">
    <thead>
      <tr><th>#</th><th>Item Code</th><th>Item</th><th class="text-right">Price</th><th class="text-right">Qty</th><th class="text-right">Disc</th><th class="text-right">Total</th></tr>
    </thead>
    <tbody>
      <?php $i=0; if($items){ foreach($items as $it){ $i++; ?>
      <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $it->itm_code; ?></td>
        <td><?php echo htmlspecialchars($it->itm_name); ?></td>
        <td class="text-right"><?php echo number_format($it->grnitm_price,2); ?></td>
        <td class="text-right"><?php echo number_format($it->grnitm_quantity,2); ?></td>
        <td class="text-right"><?php echo number_format($it->grnitm_discount,2); ?></td>
        <td class="text-right"><?php echo number_format($it->grnitm_total,2); ?></td>
      </tr>
      <?php } } if($i==0){ echo '<tr><td colspan="7" class="text-center">No items.</td></tr>'; } ?>
    </tbody>
  </table>

  <table class="totals">
    <tr><td>Subtotal</td><td class="text-right">LKR <?php echo number_format($grn->grn_subtotal,2); ?></td></tr>
    <tr><td>Discount</td><td class="text-right">LKR <?php echo number_format($grn->grn_discount,2); ?></td></tr>
    <tr class="grand"><td>Grand Total</td><td class="text-right">LKR <?php echo number_format($grn->grn_grandtotal,2); ?></td></tr>
    <?php if(isset($grn->sup_pay_cash)): ?>
    <tr><td>Cash Paid</td><td class="text-right">LKR <?php echo number_format($grn->sup_pay_cash,2); ?></td></tr>
    <tr><td>Credit</td><td class="text-right">LKR <?php echo number_format($grn->sup_pay_credit,2); ?></td></tr>
    <?php endif; ?>
  </table>

  <div style="clear:both;text-align:center;margin-top:30px;">
    <button id="pbtn" class="btn btn-primary" onclick="window.print()">Print GRN</button>
  </div>
</div>
</body>
</html>
