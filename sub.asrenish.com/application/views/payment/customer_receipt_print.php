<?php
$shop = (!empty($config) && isset($config[0]->config_value)) ? $config[0]->config_value : 'EzyPOS';
$methodName = function($p){
    if ($p->rp_method === 'cheque') { return 'Cheque'; }
    if ($p->rp_method === 'pm') { return $p->pm_name ? $p->pm_name : 'Card'; }
    return 'Cash';
};
?><!doctype html>
<html><head><meta charset="utf-8"><title><?php echo htmlspecialchars($number); ?></title>
<style>
  @page { size: 80mm auto; margin: 3mm; }
  body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; width: 74mm; margin: 0 auto; }
  h2 { text-align: center; margin: 4px 0 2px; font-size: 16px; }
  .c { text-align: center; } .r { text-align: right; } .b { font-weight: bold; }
  table { width: 100%; border-collapse: collapse; }
  td { padding: 2px 0; vertical-align: top; }
  hr { border: 0; border-top: 1px dashed #000; margin: 6px 0; }
  @media print { .np { display: none; } }
</style></head>
<body>
  <h2><?php echo htmlspecialchars($shop); ?></h2>
  <div class="c b">PAYMENT RECEIPT</div>
  <hr>
  <table>
    <tr><td>Receipt</td><td class="r b"><?php echo htmlspecialchars($number); ?></td></tr>
    <tr><td>Date</td><td class="r"><?php echo htmlspecialchars($r->rcpt_date); ?></td></tr>
    <tr><td>Customer</td><td class="r"><?php echo htmlspecialchars($r->cus_name); ?></td></tr>
    <?php if ($r->store_name): ?><tr><td>Received at</td><td class="r"><?php echo htmlspecialchars($r->store_name); ?></td></tr><?php endif; ?>
  </table>
  <hr>
  <div class="b">Paid by</div>
  <table>
    <?php foreach ($r->pays as $p): ?>
    <tr><td><?php echo htmlspecialchars($methodName($p)); ?>
        <?php if ($p->rp_reference !== null && $p->rp_reference !== ''): ?><br><small><?php echo htmlspecialchars(trim($p->rp_bank.' '.$p->rp_reference)); ?></small><?php endif; ?></td>
        <td class="r"><?php echo number_format($p->rp_amount, 2); ?></td></tr>
    <?php endforeach; ?>
    <?php if (floatval($r->rcpt_discount) > 0): ?>
    <tr><td>Discount</td><td class="r"><?php echo number_format($r->rcpt_discount, 2); ?></td></tr>
    <?php endif; ?>
    <tr class="b"><td>Total settled</td><td class="r"><?php echo number_format($r->rcpt_amount + $r->rcpt_discount, 2); ?></td></tr>
  </table>
  <hr>
  <div class="b">Settled against</div>
  <table>
    <?php foreach ($r->alloc as $a): ?>
    <tr><td><?php echo $a->ra_sale_id ? 'Bill '.htmlspecialchars(bill_no($a)) : 'Opening balance'; ?></td>
        <td class="r"><?php echo number_format($a->ra_amount, 2); ?></td></tr>
    <?php endforeach; ?>
  </table>
  <?php if ($r->rcpt_note): ?><hr><div><?php echo htmlspecialchars($r->rcpt_note); ?></div><?php endif; ?>
  <hr>
  <div class="c">Received by <?php echo htmlspecialchars($r->user_name); ?><br>Thank you!</div>
  <div class="c np" style="margin-top:10px;"><button onclick="window.print()">Print</button></div>
  <script>window.onload = function(){ window.print(); };</script>
</body></html>
