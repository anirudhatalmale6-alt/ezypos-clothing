<html>
    <head>
    <?php date_default_timezone_set('Asia/Colombo');
        // Discount type + value (robust): percentage shows a % sign, flat shows a plain amount.
        $discType = (isset($sales->sale_discount_type) && $sales->sale_discount_type == 'flat') ? 'flat' : 'percentage';
        $discNum  = floatval(isset($sales->sale_discount) ? $sales->sale_discount : 0);
    ?>
    <style>
        /* 90mm thermal receipt: full printable width, bold/high-density, tight spacing. */
        * { color:#000 !important; box-sizing:border-box; }
        html,body {
            margin:0; padding:0;
            font-family: Arial, "Helvetica Neue", sans-serif;
            font-weight:bold;                 /* thick font for clear thermal print */
            font-size:13px; line-height:1.15;
            -webkit-print-color-adjust:exact; print-color-adjust:exact;
        }
        .rcpt { width:100%; max-width:80mm; margin:0 auto; padding:2mm 2mm 4mm; }
        .rcpt .center { text-align:center; }
        .rcpt .shopname { font-size:18px; font-weight:900; margin:0 0 2px; }
        .rcpt .shopinfo { font-size:12px; margin:0 0 4px; }
        .rcpt .doctitle { font-size:14px; font-weight:900; margin:4px 0; letter-spacing:1px; }
        .rcpt .meta { width:100%; font-size:12px; margin:2px 0; }
        .rcpt .meta td { padding:0; vertical-align:top; }
        .rcpt hr { border:none; border-top:1px dashed #000; margin:4px 0; }
        table.items { width:100%; border-collapse:collapse; font-size:12px; }
        table.items th { border-bottom:1px solid #000; padding:2px 1px; text-align:left; font-weight:900; }
        table.items td { padding:1px; vertical-align:top; }         /* tight rows = more items per print */
        table.items .r { text-align:right; }
        .tots { width:100%; font-size:13px; margin-top:2px; }
        .tots td { padding:0; }
        .tots td.r { text-align:right; }
        .tots .grand td { font-size:15px; font-weight:900; border-top:1px solid #000; padding-top:2px; }
        .foot { text-align:center; font-size:12px; margin-top:6px; font-weight:900; }
        @media print { #printBtn{ display:none; } @page { margin:2mm; } }
    </style>
    </head>
    <body>
    <div class="rcpt">
        <div class="center">
            <div class="shopname"><?php if(is_array($comName)){ foreach($comName as $nme){ echo $nme->config_value; } } ?></div>
            <div class="shopinfo">
                <?php echo $sales->store_name; ?><br>
                <?php echo $sales->store_address; ?><?php echo ($sales->store_address2 ? ', '.$sales->store_address2 : ''); ?><br>
                <?php
                    $tels = array_filter(array($sales->store_tel, $sales->store_mobile, $sales->store_mobile2));
                    echo implode(' / ', $tels);
                ?>
            </div>
            <div class="doctitle">INVOICE</div>
        </div>

        <table class="meta">
            <tr>
                <td>Customer: <?php echo $customer->cus_name; ?></td>
                <td class="r">Inv No: <?php echo bill_no($sales); ?></td>
            </tr>
            <tr>
                <td>Address: <?php echo $customer->cus_address; ?></td>
                <td class="r">Bill By: <?php echo $user; ?></td>
            </tr>
            <tr>
                <td colspan="2">Date: <?php echo $sales->sale_createdat; ?></td>
            </tr>
            <?php if(isset($sales->sale_type) && $sales->sale_type != 'cash'): ?>
            <tr><td colspan="2">Type: <?php echo ucfirst($sales->sale_type); ?><?php echo (isset($sales->sale_online_id) && $sales->sale_online_id) ? ' (Ref: '.$sales->sale_online_id.')' : ''; ?></td></tr>
            <?php endif; ?>
        </table>
        <hr>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="r">Qty</th>
                    <th class="r">Price</th>
                    <th class="r">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $itemRows = (isset($saleitems) && is_array($saleitems)) ? $saleitems : array();
                    $totalQty = 0;
                    foreach($itemRows as $saleitem){ $totalQty += floatval($saleitem->saleitem_quantity); ?>
                <tr>
                    <td colspan="4"><?php echo (isset($saleitem->itm_code) && $saleitem->itm_code) ? '['.$saleitem->itm_code.'] ' : ''; ?><?php echo $saleitem->itm_name; ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="r"><?php echo rtrim(rtrim(number_format(floatval($saleitem->saleitem_quantity),2),'0'),'.'); ?></td>
                    <td class="r"><?php echo number_format(floatval($saleitem->saleitem_price),2); ?></td>
                    <td class="r"><?php echo number_format(floatval($saleitem->saleitem_total),2); ?></td>
                </tr>
                <?php }
                if(count($itemRows) == 0){ ?>
                <tr><td colspan="4" style="text-align:center;">No items recorded on this bill</td></tr>
                <?php } ?>
            </tbody>
        </table>
        <hr>

        <table class="tots">
            <tr><td>Total Qty:</td><td class="r"><?php echo rtrim(rtrim(number_format($totalQty,2),'0'),'.'); ?> pcs</td></tr>
            <tr><td>Sub-total:</td><td class="r"><?php echo number_format(floatval($sales->sale_subtotal),2); ?></td></tr>
            <?php if($discNum > 0){ ?>
            <tr>
                <td>Discount:</td>
                <td class="r"><?php
                    if($discType == 'flat'){ echo number_format($discNum,2); }
                    else { echo rtrim(rtrim(number_format($discNum,2),'0'),'.').'%'; }
                ?></td>
            </tr>
            <?php } ?>
            <?php if(isset($sales->sale_promo_discount) && $sales->sale_promo_discount > 0){ ?>
            <tr><td>Promotions:</td><td class="r">- <?php echo number_format($sales->sale_promo_discount,2); ?></td></tr>
            <?php } ?>
            <?php if(isset($sales->sale_loyalty_redeemed) && $sales->sale_loyalty_redeemed > 0){ ?>
            <tr><td>Points Redeemed:</td><td class="r">- <?php echo number_format($sales->sale_loyalty_redeemed,2); ?></td></tr>
            <?php } ?>
            <?php if(isset($sales->sale_delivery_charge) && $sales->sale_delivery_charge > 0){ ?>
            <tr><td>Delivery Charge:</td><td class="r"><?php echo number_format($sales->sale_delivery_charge,2); ?></td></tr>
            <?php } ?>
            <tr class="grand"><td>Grand Total:</td><td class="r"><?php echo number_format(floatval($sales->sale_grandtotal),2); ?></td></tr>
            <?php if(isset($sales->sale_loyalty_points_earned) && $sales->sale_loyalty_points_earned > 0){ ?>
            <tr><td style="font-size:11px;">Points Earned:</td><td class="r" style="font-size:11px;"><?php echo number_format($sales->sale_loyalty_points_earned,2); ?></td></tr>
            <?php } ?>
        </table>
        <hr>

        <?php
            // Payment breakdown: every method used, with its card-machine / cheque reference.
            $payRows = (isset($payments) && is_array($payments)) ? $payments : array();
            $paidTotal = 0; $creditTotal = 0;
            foreach($payRows as $pr){
                if($pr->is_credit){ $creditTotal += $pr->amount; } else { $paidTotal += $pr->amount; }
            }
            // Fall back to the old aggregate record if the breakdown came back empty.
            if(count($payRows) == 0 && isset($paymnt) && $paymnt){
                $cash = floatval($paymnt->cus_pay_cash); $credit = floatval($paymnt->cus_pay_credit);
                $chq  = floatval(isset($paymnt->cheq) ? $paymnt->cheq : 0);
                if($cash > 0){ $payRows[] = (object)array('label'=>'Cash','reference'=>'','amount'=>$cash,'is_credit'=>false); }
                if($chq  > 0){ $payRows[] = (object)array('label'=>'Cheque','reference'=>'','amount'=>$chq,'is_credit'=>false); }
                if($credit > 0){ $payRows[] = (object)array('label'=>'Credit','reference'=>'','amount'=>$credit,'is_credit'=>true); }
                $paidTotal = $cash + $chq; $creditTotal = $credit;
            }
            $multiMethod = count($payRows) > 1;
        ?>
        <table class="tots">
            <?php if($multiMethod){ ?>
            <tr><td colspan="2" style="font-weight:900;">Payments:</td></tr>
            <?php }
            foreach($payRows as $pr){ ?>
                <tr>
                    <td><?php echo ($multiMethod ? '&nbsp;&nbsp;' : ''); echo htmlspecialchars($pr->label); ?>:</td>
                    <td class="r"><?php echo number_format($pr->amount,2); ?></td>
                </tr>
                <?php if($pr->reference !== ''){ ?>
                <tr>
                    <td colspan="2" style="font-size:11px;">&nbsp;&nbsp;Ref: <?php echo htmlspecialchars($pr->reference); ?></td>
                </tr>
                <?php }
            }
            // Balance to Return (change) on a normal cash sale where more than the total was paid.
            // Skipped once the sale has been returned/exchanged: the grand total is reduced
            // by the refund, which would otherwise show the refund as change owed.
            $wasReturned  = (isset($sales->sale_return_status) && trim($sales->sale_return_status) !== '');
            $changeReturn = $paidTotal - floatval($sales->sale_grandtotal);
            if(!$wasReturned && $creditTotal <= 0 && $changeReturn > 0.001){ ?>
                <tr><td>Balance to Return:</td><td class="r"><?php echo number_format($changeReturn,2); ?></td></tr>
            <?php } ?>
        </table>

        <div class="foot">
            Thank You! Come Again.<br>
            Exchange within 7 days only.
        </div>

        <div class="center" style="margin-top:8px;">
            <button id="printBtn" class="btn btn-primary">Print</button>
        </div>
    </div>
        <script src="<?php echo base_url().'assets/js/jquery.min.js'?>"></script>
        <script>
            $(function(){
                $("#printBtn").click(function(){
                    window.print();
                    $("#printBtn").hide();
                });
            });
        </script>
    </body>
</html>
