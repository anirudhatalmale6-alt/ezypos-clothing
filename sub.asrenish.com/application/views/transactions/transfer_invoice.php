<html>
    <head>
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans Condensed', sans-serif;
            font-size: 14px;
        }
        @media print {
            #printBtn, #pdfBtn {
                display: none;
            }
        }
        .invoice-header { text-align: center; margin-bottom: 15px; }
        .info-table td { padding: 2px 5px; font-size: 13px; }
        .items-table th { font-size: 12px; background: #f0f0f0; }
        .items-table td { font-size: 13px; }
        .status-Pending { color: #e67e22; font-weight: bold; }
        .status-Accepted { color: #27ae60; font-weight: bold; }
        .status-Rejected { color: #e74c3c; font-weight: bold; }
    </style>
    </head>
    <body>
    <div class="container" style="max-width: 450px; margin: 0 auto;">
        <!-- Company Header -->
        <div class="invoice-header">
            <h3 style="font-family: arial; margin-bottom: 3px;">
                <?php if(isset($comName) && $comName): foreach($comName as $nme): echo $nme->config_value; endforeach; endif; ?>
            </h3>
            <div style="font-family: arial; font-size: 12px;">
                <?php
                if(isset($addLine1) && $addLine1): foreach($addLine1 as $a1): echo $a1->config_value; endforeach; endif;
                echo '<br>';
                if(isset($addLine2) && $addLine2): foreach($addLine2 as $a2): echo $a2->config_value; endforeach; endif;
                echo '<br>';
                if(isset($telephone) && $telephone): foreach($telephone as $tel): echo $tel->config_value; endforeach; endif;
                ?>
            </div>
            <hr style="margin: 5px 0;">
            <span style="font-size: 16px;"><b>STOCK TRANSFER</b></span>
        </div>

        <!-- Transfer Info -->
        <table class="info-table" width="100%" style="font-family: arial; margin-bottom: 10px;">
            <tr>
                <td><b>Transfer #:</b> TF<?php echo str_pad($transfer->transfer_id, 4, '0', STR_PAD_LEFT); ?></td>
                <td style="text-align:right;"><b>Date:</b> <?php echo $transfer->transfer_created_at; ?></td>
            </tr>
            <tr>
                <td><b>From:</b> <?php echo isset($transfer->from_store_name) ? $transfer->from_store_name : 'N/A'; ?></td>
                <td style="text-align:right;"><b>Status:</b>
                    <span class="status-<?php echo $transfer->transfer_status; ?>">
                        <?php echo $transfer->transfer_status; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td><b>To:</b> <?php echo isset($transfer->to_store_name) ? $transfer->to_store_name : 'N/A'; ?></td>
                <td style="text-align:right;"><b>By:</b> <?php echo isset($transfer->created_by_name) ? $transfer->created_by_name : ''; ?></td>
            </tr>
            <?php if($transfer->transfer_notes): ?>
            <tr>
                <td colspan="2"><b>Notes:</b> <?php echo htmlspecialchars($transfer->transfer_notes); ?></td>
            </tr>
            <?php endif; ?>
            <?php if($transfer->transfer_status !== 'Pending' && isset($transfer->accepted_by_name)): ?>
            <tr>
                <td colspan="2">
                    <b><?php echo ($transfer->transfer_status == 'Accepted') ? 'Accepted' : 'Rejected'; ?> By:</b>
                    <?php echo $transfer->accepted_by_name; ?>
                    <b>At:</b> <?php echo $transfer->transfer_accepted_at; ?>
                </td>
            </tr>
            <?php endif; ?>
        </table>

        <!-- Items Table -->
        <table class="table table-bordered table-sm items-table" cellspacing="0" width="100%" style="font-family: arial;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th style="text-align:right;">Qty</th>
                    <th style="text-align:right;">Price</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                $count = 0;
                if(isset($items) && $items):
                    foreach($items as $itm):
                        $count++;
                        $line_total = floatval($itm->sti_qty) * floatval($itm->sti_price);
                        $grand_total += $line_total;
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo htmlspecialchars($itm->sti_item_name); ?></td>
                    <td style="text-align:right;"><?php echo number_format($itm->sti_qty, 2); ?></td>
                    <td style="text-align:right;"><?php echo number_format($itm->sti_price, 2); ?></td>
                    <td style="text-align:right;"><?php echo number_format($line_total, 2); ?></td>
                </tr>
                <?php
                    endforeach;
                endif;
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right;"><strong>Grand Total:</strong></td>
                    <td style="text-align:right;"><strong>LKR <?php echo number_format($grand_total, 2); ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <hr>
        <div style="text-align:center; font-size:11px; color:#888;">
            Printed: <?php echo date('Y-m-d H:i:s'); ?>
        </div>

        <!-- Action Buttons (hidden in print) -->
        <div class="text-center m-t-10" style="margin-top: 15px;">
            <button id="printBtn" class="btn btn-primary btn-sm" onclick="window.print();">
                <i class="fa fa-print"></i> Print
            </button>
            <button id="pdfBtn" class="btn btn-info btn-sm" onclick="window.close();">
                <i class="fa fa-times"></i> Close
            </button>
        </div>
    </div>
    </body>
</html>
