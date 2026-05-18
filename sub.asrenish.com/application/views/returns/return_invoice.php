<html>
    <head>
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans Condensed', sans-serif;
        }
        @media print {
            #printBtn {
                display: none;
            }
        }
    </style>
    </head>
    <body>
    <div class="">
        <div class="container">
               <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <div class="panel-body">
                                <div class="clearfix" style="font-family: arial;">
                                    <?php date_default_timezone_set('Asia/Colombo'); ?>
                                    <div class="">
                                        <h3 style="text-align:center; font-family: arial;"><?php if(isset($comName)){ foreach($comName as $nme){ echo $nme->config_value; }} ?></h3>
                                        <div class="row" style="text-align:center">
                                            <div class="col-12">
                                                <div style="font-family: arial;">
                                                    <?php if(isset($addLine1)){ foreach($addLine1 as $addr){ echo $addr->config_value; }} ?><br>
                                                    <?php if(isset($addLine2)){ foreach($addLine2 as $addr2){ echo $addr2->config_value; }} ?><br>
                                                    <?php if(isset($telephone)){ foreach($telephone as $tel){ echo $tel->config_value; }} ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="text-align:center; margin:10px 0;">
                                            <span style="font-size:16px; font-weight:bold; border:1px solid #333; padding:3px 12px;">RETURN BILL</span>
                                        </div>
                                        <div class="row">
                                            <div class="col-7">
                                                <table cellspacing="0" width="100%" style="font-family: arial;">
                                                    <tbody>
                                                        <tr><td>Return ID: RET-<?php echo $ret->ret_id; ?></td></tr>
                                                        <tr><td>Inv No: AS00<?php echo $ret->ret_sale_id; ?></td></tr>
                                                        <?php if(isset($ret->cus_name)): ?>
                                                        <tr><td>Customer: <?php echo $ret->cus_name; ?></td></tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-5">
                                                <table cellspacing="0" width="100%" style="font-family: arial;">
                                                    <tbody>
                                                        <tr><td>Return Date: <?php echo $ret->ret_created_at; ?></td></tr>
                                                        <?php
                                                            $typeLabels = array(
                                                                'full_return' => 'Full Return',
                                                                'partial_return' => 'Partial Return',
                                                                'exchange' => 'Exchange'
                                                            );
                                                            $typeLabel = isset($typeLabels[$ret->ret_type]) ? $typeLabels[$ret->ret_type] : $ret->ret_type;
                                                        ?>
                                                        <tr><td>Type: <?php echo $typeLabel; ?></td></tr>
                                                        <tr><td>Bill By: <?php echo isset($user) ? $user : ''; ?></td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Returned Items -->
                                <div class="row" style="margin-top:10px;">
                                    <div class="col-12">
                                        <div style="font-family: arial; font-weight:bold; margin-bottom:5px;">Returned Items:</div>
                                        <table class="table" cellspacing="0" width="100%" style="margin-right:24px;">
                                            <thead>
                                                <tr>
                                                    <th style="margin-right:5px;">#</th>
                                                    <th>Item</th>
                                                    <th style="text-align:right;">Qty</th>
                                                    <th style="text-align:right;">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($return_items)): $x = 0; foreach($return_items as $ri): $x++; ?>
                                                <tr style="font-family: arial;">
                                                    <td><?php echo $x; ?></td>
                                                    <td><?php echo $ri->ri_item_name; ?></td>
                                                    <td style="text-align:right;"><?php echo $ri->ri_qty; ?></td>
                                                    <td style="text-align:right;"><?php echo number_format($ri->ri_total, 2); ?></td>
                                                </tr>
                                                <?php endforeach; endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Exchange Items (if any) -->
                                <?php if(isset($exchange_items) && count($exchange_items) > 0): ?>
                                <div class="row">
                                    <div class="col-12">
                                        <div style="font-family: arial; font-weight:bold; margin-bottom:5px;">Exchange Items (New):</div>
                                        <table class="table" cellspacing="0" width="100%" style="margin-right:24px;">
                                            <thead>
                                                <tr>
                                                    <th style="margin-right:5px;">#</th>
                                                    <th>Item</th>
                                                    <th style="text-align:right;">Qty</th>
                                                    <th style="text-align:right;">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $y = 0; foreach($exchange_items as $ei): $y++; ?>
                                                <tr style="font-family: arial;">
                                                    <td><?php echo $y; ?></td>
                                                    <td><?php echo $ei->ei_item_name; ?></td>
                                                    <td style="text-align:right;"><?php echo $ei->ei_qty; ?></td>
                                                    <td style="text-align:right;"><?php echo number_format($ei->ei_total, 2); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <hr>
                                <!-- Totals -->
                                <div class="row" style="margin-right:5px;">
                                    <div class="col-12">
                                        <div style="font-family: arial;"><b>Return Total:</b><span style="float:right;"><?php echo number_format($ret->ret_refund_amount, 2); ?></span></div>
                                        <?php if($ret->ret_exchange_amount > 0): ?>
                                        <div style="font-family: arial;"><b>Exchange Total:</b><span style="float:right;"><?php echo number_format($ret->ret_exchange_amount, 2); ?></span></div>
                                        <?php endif; ?>
                                        <div style="font-family: arial; font-size:14px;"><b>Net <?php echo ($ret->ret_net_amount >= 0) ? 'Refund' : 'Payment Due'; ?>:</b><span style="float:right;font-weight:bold;"><?php echo number_format(abs($ret->ret_net_amount), 2); ?></span></div>
                                    </div>
                                </div>

                                <?php if(isset($ret->ret_reason) && trim($ret->ret_reason) !== ''): ?>
                                <hr>
                                <div class="row">
                                    <div class="col-12" style="font-family: arial;">
                                        <b>Reason:</b> <?php echo $ret->ret_reason; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <hr>
                                <div class="row text-center justify-content-center">
                                    <div class="col-12" style="font-family: arial;">
                                        <?php if(isset($footer)){ foreach($footer as $Ftext){ echo $Ftext->config_value; }} ?><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button id="printBtn" class="btn btn-primary">Print</button>
        </div>
    </div>
        <script src="<?php echo base_url().'assets/js/jquery.min.js'?>"></script>
        <script src="<?php echo base_url().'assets/js/bootstrap.min.js'?>"></script>
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
