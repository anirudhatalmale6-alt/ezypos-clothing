<html>
    <head>
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=EB+Garamond" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pathway+Gothic+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
    <style>
        body {
            font-family:  'Open Sans Condensed', sans-serif;
        }
        @media print {
            #printBtn{
                display: none;
            }
        }
        
     
    </style>
    </head>
    <body>
    <div class="">
        <div class="container">
               <!-- Page-Title -->    
               <div class="row" >
                    <div class="col-12">
                        <div class="card-box">
                            <!-- <div class="panel-heading">
                                <h4>Invoice</h4>
                            </div> -->
                            <div class="panel-body">
                                <div class="clearfix" style="font-family: arial;">
                                    <?php date_default_timezone_set('Asia/Colombo');?>
                                    <div class="">
                                        <h3  style="text-align:center; font-family: arial;"><?php foreach($comName as $nme){ echo $nme->config_value;} ?></h3>
                                        <div class="row" style="text-align:center">
                                            <div class="col-lg-12 col-md-12 col-12 col-12">
                                                <div class="" style="font-family: arial">
                                                        <?php echo $sales->store_name;?> <br>
                                                        <?php echo $sales->store_address.",";?><?php echo $sales->store_address2;?><br>
                                                        <?php echo $sales->store_tel.",";?>
                                                        <?php echo $sales->store_mobile.",";?>
                                                        <?php echo $sales->store_mobile2.",";?>
                                                        <?php echo $sales->store_fax.",";?>
                                                        <br>
                                                        <?php echo $sales->store_email;?>
                                                    
                                                </div>      
                                            </div><!-- end col -->
                                        </div>
                                        <div class="" style="text-align:center">
                                            <div class="col-lg-12 col-md-12 col-12 col-12">
                                                    <span ><b>Invoice</b></span>     
                                            </div><!-- end col -->
                                        </div>
                                        <div class="row">
                                            <div class="col-7">
                                                <div class="card-box table-responsive"> 
                                                    <table  class="" cellspacing="0" width="100%" style="font-family: arial">                                
                                                        <tbody>
                                                                <tr>
                                                                    <td>Customer:<?php echo " ".$customer->cus_name ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Address:<?php echo " ".$customer->cus_address ?></td>
                                                                </tr>
                                                                <tr>  
                                                                    <td>Created At:<?php echo " ".$sales->sale_createdat ?></td> 
                                                                </tr>                                               
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="card-box table-responsive"> 
                                                    <table  class="" cellspacing="0" width="100%" style="font-family: arial">                                
                                                        <tbody>
                                                                <tr>
                                                                    <td >Inv No: <?php echo " "."AS00".$sales->sale_id ?></td>
                                                                </tr>
                                                                <?php if(isset($sales->sale_type) && $sales->sale_type != 'cash'): ?>
                                                                <tr>
                                                                    <td>Type: <?php echo ucfirst($sales->sale_type); ?></td>
                                                                </tr>
                                                                <?php endif; ?>
                                                                <?php if(isset($sales->sale_online_id) && $sales->sale_online_id): ?>
                                                                <tr>
                                                                    <td>Online Ref: <?php echo $sales->sale_online_id; ?></td>
                                                                </tr>
                                                                <?php endif; ?>
                                                                <tr>
                                                                    <td >Bill By:<?php echo " ".$user ?></td>
                                                                </tr>                                              
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>   

                                    </div>
                                </div>                             
                                <!-- end row -->

                                <div class="row" >
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                                        <div class="card-box responsive"> 
                                            <table  class="table" cellspacing="0" width="100%" style="margin-right: 24px ">                                
                                                <thead>
                                                    <tr>
                                                        <th style="margin-right:5px;">#</th>
                                                        <th>Name</th>
                                                        <th style="Text-align:right;">Price</th>
                                                        <th style="Text-align:right;">Qty</th>
                                                        <th style="Text-align:right;">Disc(%)</th>
                                                        <th style="Text-align:right;padding-right: -10px">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $x = 0; foreach($saleitems as $saleitem) { $x++?>
                                                        <tr>
                                                            <td style="margin-right:5px; font-family: arial"><?php echo $x ?></td>
                                                            <td colspan="5" style="font-family: arial"><?php echo $saleitem->itm_name ?></td>
                                                            
                                                        </tr>
                                                        <tr style="font-family: arial;">
                                                            <td>&nbsp;</td>                                                                                                                   
                                                            <td colspan="2" style="Text-align:right;"><?php echo $saleitem->saleitem_price ?></td>
                                                            <td style="Text-align:right;"><?php echo $saleitem->saleitem_quantity ?></td>
                                                            <td style="Text-align:right;"><?php echo $saleitem->saleitem_discount ?></td>
                                                            <td style="Text-align:right;"><?php echo $saleitem->saleitem_total ?></td>
                                                        </tr>
                                                        
                                                    <?php } ?>                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <div class="row" style="margin-right: 5px ">                                 
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xm-12 col-12">
                                            <div><b>Sub-total:</b><span style="float:right;font-family: arial"><?php echo $sales->sale_subtotal ?></span></div>
                                            <div><b>Discount<?php echo (isset($sales->sale_discount_type) && $sales->sale_discount_type=='flat') ? ' (Flat)' : ' (%)'; ?>:</b><span style="float:right;font-family: arial"> <?php echo $sales->sale_discount ?></span></div>
                                            <?php if(isset($sales->sale_delivery_charge) && $sales->sale_delivery_charge > 0){ ?>
                                            <div><b>Delivery Charge:</b><span style="float:right;font-family: arial"><?php echo number_format($sales->sale_delivery_charge, 2) ?></span></div>
                                            <?php } ?>
                                            <div><b>Grand Total:</b><span style="float:right;font-family: arial"><?php echo $sales->sale_grandtotal ?></span></div>
                                        <hr>
                                        <?php 
                                            $cash=$paymnt->cus_pay_cash; 
                                            $credit=$paymnt->cus_pay_credit; 
                                            $noChqs=$paymnt->noOfChqs; 
                                        
                                        if($cash>0) { ?>
                                            <div><b>Cash:</b><span style="float:right;font-family: arial"><?php echo $cash ?></span></div>
                                        <?php }
                                        if($noChqs>0) { ?>
                                            <div><b>Cheque:</b>(No:<?php echo $noChqs ?>)<span style="float:right;font-family: arial"><?php echo $paymnt->cheq; ?></span></div>
                                        <?php }
                                        if($credit>0) { ?>
                                            <div><b>Credit:</b><span style="float:right;font-family: arial"><?php echo $credit ?></span></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row text-center justify-content-center">
                                    <div class="col-12" style=";font-family: arial"> 
                                        <?php foreach($footer as $Ftext){ echo $Ftext->config_value;} ?><br>
                                    </div>
                                </div>                                                            
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->              
                <!-- end row -->
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
