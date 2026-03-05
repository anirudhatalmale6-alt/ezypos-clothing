
<!DOCTYPE html>
<html>
    
<!-- Mirrored from coderthemes.com/uplon/horizontal/pages-starter.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 08 Dec 2017 14:10:55 GMT -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico">

        <!-- App title -->
        <title> <?php echo $title; ?></title>

        <!-- Custom box css -->
        <link href="<?php echo base_url(); ?>assets/plugins/custombox/css/custombox.min.css" rel="stylesheet">
        
        <!-- DataTables -->
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <!--Form Wizard-->
        <link href="<?php echo base_url(); ?>assets/plugins/jquery.steps/demo/css/jquery.steps.css" rel="stylesheet" type="text/css" />
        
        <!-- Switchery css -->
        <link href="<?php echo base_url(); ?>assets/plugins/switchery/switchery.min.css" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- App CSS -->
        <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <!--ui date css -->
        <link href="<?php echo base_url(); ?>assets/date/jquery-ui.css" rel="stylesheet" type="text/css" />

        <!-- Navbar sub menu dropdown -->
    
        <!-- Searchable dropdown -->
        <link rel="stylesheet" type="text/css" href="assets/ui/dropdown.min.css">
        <link rel="stylesheet" type="text/css" href="assets/ui/transition.min.css">

        <!-- searchable dropdown CSS -->
        <link href='<?php echo base_url(); ?>assets/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>
        
        <!--tootlip-->
        <link rel="stylesheet" type="text/css" href='<?php echo base_url(); ?>assets/plugins/tooltip/tooltipster/dist/css/tooltipster.bundle.min.css' />
        <link rel="stylesheet" href='<?php echo base_url(); ?>assets/plugins/tooltip/myTooltip/src/myTooltip.css'>
        <link rel="stylesheet" type="text/css" href='<?php echo base_url(); ?>assets/plugins/tooltip/darktooltip/dist/darktooltip.css'>
   
        <style>
            th {font-size: 18px;text-align: center;},
            .pull-right {float: right;}           
        </style>

        <!-- Modernizr js -->
        <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>
        <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','../../../www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-79190402-1', 'auto');
        ga('send', 'pageview');

        </script>
        <script src="<?php echo base_url().'assets/js/jquery.min.js'?>"></script>

        <!--searchable dropdown-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src='<?php echo base_url(); ?>assets/select2/dist/js/select2.min.js' type='text/javascript'></script>

        <script src="<?php echo base_url().'assets/ui/dropdown.min.js'?>"></script>        
        <script src="<?php echo base_url().'assets/ui/transition.min.js'?>"></script>

    <!--tootlip-->
    <script type="text/javascript" src='<?php echo base_url(); ?>assets/plugins/tooltip/tooltipster/dist/js/tooltipster.bundle.min.js'></script>
    <script src='<?php echo base_url(); ?>assets/plugins/tooltip/myTooltip/src/myTooltip.js'></script>
     <script type="text/javascript" src='<?php echo base_url(); ?>assets/plugins/tooltip/darktooltip/dist/jquery.darktooltip.js'></script>
    </head>


    <body  id="html">

        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main">
                <div class="container">

                    <!-- LOGO -->
                    <div class="topbar-left">
                        <a href="<?php echo base_url()?>" class="logo">
                            <i class="zmdi zmdi-group-work icon-c-logo"></i>                            
                            <span> 

                            <?php foreach($config as $row) 
                                    {
                                    echo $row->config_value; 
                                    }
                            ?>                                     
                            </span> 
     
                        </a>
                    </div>
                    <!-- End Logo container-->

                    <div class="menu-extras navbar-topbar">

                        <ul class="list-inline float-right mb-0">

                            <li class="list-inline-item">
                                <!-- Mobile menu toggle-->
                                <a class="navbar-toggle">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                                <!-- End mobile menu toggle-->
                            </li>

                            <?php //if(!isset($_SESSION['username'])){ ?>   
                            <!--<li class="list-inline-item dropdown notification-list">
                                <a type="button" href="<?php// echo base_url('login')?>" class="btn">
                                    <i class="zmdi zmdi-power"></i> <span>Login</span>
                                </a>
                            </li>
                            --><?php // }
                             if(isset($_SESSION['username'])){ ?>
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                   
                                   <div class="noti-title">
                                        <h5 class="text-overflow"><small><?php echo $_SESSION['username']; ?></small> </h5>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                                    <!-- item
                                    <div class="dropdown-item noti-title">
                                        <h5 class="text-overflow"><small>Welcome ! John</small> </h5>
                                    </div>
                                    -->
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="zmdi zmdi-account-circle"></i> <span>Profile</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="zmdi zmdi-settings"></i> <span>Settings</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="zmdi zmdi-lock-open"></i> <span>Lock Screen</span>
                                    </a>

                                    <!-- item-->
                                    <a href="<?php echo base_url('logout')?>" class="dropdown-item notify-item">
                                        <i class="zmdi zmdi-power"></i> <span>Logout</span>
                                    </a>

                                </div>
                            </li>
                            <?php } ?>
                        </ul>

                    </div> <!-- end menu-extras -->
                    <div class="clearfix"></div>

                </div> <!-- end container -->
            </div>
            <!-- end topbar-main <?php if(isset($_SESSION['privitem'])){if($_SESSION['privitem'] == 1){}}  ?> --> 

            <div class="navbar-custom">
                <div class="container">
                    <div id="navigation">
                        <!-- Navigation Menu&& ($_SESSION['userrole']==1) -->
                        <ul class="navigation-menu">
                            <li>
                                <a href="<?php echo base_url()?>"><i class="zmdi zmdi-view-dashboard"></i> <span> Dashboard </span> </a>
                            </li> 
                            <?php if(isset($_SESSION['privMasters'])){if($_SESSION['privMasters'] >= 1){  ?>         
                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-album"></i> <span> Masters </span> </a>
                                <ul class="submenu">
                                    <?php if(isset($_SESSION['privitem'])){if($_SESSION['privitem'] == 1){?> 
                                    <li class="has-submenu"><a href="#">Item</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-item')?>"><span> Add </span> </a></li>
                                               <!-- <li><a class="has-submenu" href="<?php// echo base_url('list-items')?>"><span> View </span> </a></li>-->
                                            </ul>
                                    </li>
                                    <?php }} if(isset($_SESSION['privcategory'])){if($_SESSION['privcategory'] == 1){  ?>
                                    <li class="has-submenu"><a href="#">Category</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-category')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php }} if(isset($_SESSION['privcustomer'])){if($_SESSION['privcustomer'] == 1){  ?>
                                    <li class="has-submenu"><a href="#">Customer</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-customer')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php }} if(isset($_SESSION['privsupplier'])){if($_SESSION['privsupplier'] == 1){  ?>
                                    <li class="has-submenu"><a href="#">Supplier</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-supplier')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li> 
                                    <?php }} if(isset($_SESSION['privtax'])){if($_SESSION['privtax'] == 1){  ?>
                                    <li class="has-submenu"><a href="#">Tax</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-tax')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php }} if(isset($_SESSION['privstore'])){if($_SESSION['privstore'] == 1){  ?>
                                    <li class="has-submenu"><a href="#">Store</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-store')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li> 
                                    <?php }} if(isset($_SESSION['privstaff'])){if($_SESSION['privstaff'] == 1){  ?>
                                    <li class="has-submenu"><a href="#">Staff</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-staff')?>"><span> Add </span> </a></li>

                                            </ul>
                                    </li>
                                    <?php }} ?>                                                               
                                </ul>
                            </li>
                            <?php }} ?>
                            <?php if(isset($_SESSION['privUsers'])){if($_SESSION['privUsers'] >= 1){  ?>
                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-album"></i> <span>Users </span> </a>
                                <ul class="submenu">
                                    <?php if(isset($_SESSION['privregister'])){if($_SESSION['privregister'] == 1){  ?>
                                    <li class="has-submenu"><a href="#">New User</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('register')?>"><span> Add </span> </a></li>
                                                <li><a class="has-submenu" href="<?php echo base_url('test')?>"><span> View </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php }} ?>                                
                                </ul>
                            </li>
                            <?php }} ?>
                            <?php ?>
                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-album"></i> <span>Transactions </span> </a>
                                <ul class="submenu">
                                    <?php   ?>
                                    <li class="has-submenu"><a href="#">GRN</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-grn')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>
                                    <?php   ?>
                                    <li class="has-submenu"><a href="#">Sales</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-sale')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>
                                    <?php   ?>
                                    <li class="has-submenu"><a href="#">Expense</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-expense')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>
                                    <li class="has-submenu"><a href="#">Bank Account</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('add-bankacc')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>                                
                                </ul>
                            </li>
                            <?php ?>
                            <?php ?>
                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-album"></i> <span>Listing </span> </a>
                                <ul class="submenu">
                                    <?php   ?>
                                    <li class=""><a href="<?php echo base_url('show-stock')?>">Stock</a>
                                           <!-- <ul class="submenu open">
                                                <li><a class="has-submenu" href=""><span> Add </span> </a></li>
                                            </ul> -->
                                    </li>
                                    <?php  ?>
                                    <?php   ?>
                                    <li class=""><a href="<?php echo base_url('stock-supplier')?>">Stock Supplier Wise</a>
                                           <!--  <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('#')?>"><span> Add </span> </a></li>
                                            </ul> -->
                                    </li>
                                    <?php  ?>  
                                    <?php   ?>
                                    <li class=""><a href="<?php echo base_url('stock-log')?>">Stocklog</a>
                                           <!--  <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('#')?>"><span> Add </span> </a></li>
                                            </ul> -->
                                    </li>
                                    <?php  ?>
                                    <?php   ?>
                                    <li class="has-submenu"><a href="<?php echo base_url('stock-log')?>">Cheque</a>
                                             <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('cus-cheque')?>"><span>Customer Cheque</span> </a></li>
                                                <li><a class="has-submenu" href="<?php echo base_url('our-cheque')?>"><span>Our Cheque</span> </a></li>
                                            </ul>                                            
                                    </li>
                                    <?php  ?>
                                </ul>
                            </li>
                            <?php ?>
                            <?php ?>
                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-album"></i> <span>Reports </span> </a>
                                <ul class="submenu">
                                    <?php   ?>
                                    <li class="has-submenu"><a href="#">Stock</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('show-stock')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>
                                    <?php   ?>
                                    <li class="has-submenu"><a href="#">Stock Log</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('show-stocklog')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>
                                    <?php   ?>
                                    <li class="has-submenu"><a href="#">##</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('#')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>                                
                                </ul>
                            </li>
                            <?php ?>
                            <?php ?>
                            <li class="has-submenu">
                                <a href="#"><i class="zmdi zmdi-album"></i> <span>Payments </span> </a>
                                <ul class="submenu">
                                    <?php   ?>
                                    <li class="has-submenu"><a href="#">Stock</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('sh')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>
                                    <?php   ?>
                                    <li class="has-submenu"><a href="#">##</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('show-stocog')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>
                                    <?php   ?>
                                    <li class="has-submenu"><a href="#">##</a>
                                            <ul class="submenu open">
                                                <li><a class="has-submenu" href="<?php echo base_url('#')?>"><span> Add </span> </a></li>
                                            </ul>
                                    </li>
                                    <?php  ?>                                
                                </ul>
                            </li>
                            <?php ?>
                            <li class=""  style="float:right;">
                                <ul>
                                    <button id="Cus_Rtn_btn" class="btn btn-purple waves-effect waves-light">Customer Return</button>
                                </ul>
                            </li>
                            <li class=""  style="float:right;">
                                <ul>
                                    <button id="Sup_Rtn_btn" class="btn btn-purple waves-effect waves-light">Supplier Return</button>
                                </ul>
                            </li>
                        </ul>
                        <!-- End navigation menu  -->
                    </div>
                    <div>
                        <!-- Modal Customer start-->
                        <div class="modal " id="customerModal" tabindex="-1" role="dialog" aria-labelledby="xampleModalLabel" aria-hidden="true" data-backdrop="false">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="xampleModalLabel">Customer Return</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                            <!-- <form id="Form11" name="" action="#" method="post"> -->
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="invoice_r" class="col-3 col-form-label header-title m-t-0 m-b-30">Invoice ID</label>
                                            <div class="col-9">
                                                <input class="form-control" type="text" placeholder="Enter Invoice ID" 
                                                name="invoice_r" id="invoice_r" required>
                                            </div><!--saleitem_ctreatedat-->
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-box table-responsive"> 
                                                    <table id="returnTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead><!--table column +/- will affect return save js-->
                                                        <tr> 
                                                            <th style="display:none;font-size: 12px;">itemID</th>                                           
                                                            <th style="font-size: 12px;">Code</th>
                                                            <th style="font-size: 12px;">Item</th>
                                                            <th style="font-size: 12px;">Price</th>
                                                            <th style="font-size: 12px;">Qty</th>
                                                            <th style="font-size: 12px;">Disc</th>
                                                            <th style="font-size: 12px;">Amount</th>
                                                            <th style="font-size: 12px;">Return Qty</th>
                                                            <th style="font-size: 12px;">Return amount</th>
                                                            <th style="font-size: 12px;">Rmv</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="cusRtrnTbody">                                          
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>                 
                                        </div> 
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-box table-responsive"> 
                                                    <table id="saleDetails" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>                                                        
                                                        </thead>
                                                        <tbody id="saleDetailTbody"> 
                                                           <!-- <tr>
                                                                <td>Grand Total</td><td>Return Total</td>
                                                            </tr>   -->                                       
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>                 
                                        </div>                             
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button id="saveCusRetn" type="" class="btn btn-primary">Save Return</button>
                                    </div>
                            <!-- </form> -->
                                </div>
                            </div>
                        </div>
                        <!-- Modal customer end-->
                        <!-- Modal supplier start-->
                        <div class="modal " id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="xampleModalLabel" aria-hidden="true" data-backdrop="false">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="xampleModalLabel">Supplier Return</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                            <!-- <form id="Form11" name="" action="#" method="post"> -->
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="grnid_r" class="col-3 col-form-label header-title m-t-0 m-b-30">GRN ID</label>
                                            <div class="col-9">
                                                <input class="form-control" type="text" placeholder="Enter GRN ID" 
                                                name="grnid_r" id="grnid_r" required>
                                            </div><!--saleitem_ctreatedat-->
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-box table-responsive"> 
                                                    <table id="supReturnTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead><!--table column +/- will affect return save js-->
                                                        <tr> 
                                                            <th style="display:none;font-size: 12px;">itemID</th>                                           
                                                            <th style="font-size: 12px;">Code</th>
                                                            <th style="font-size: 12px;">Item</th>
                                                            <th style="font-size: 12px;">Price</th>
                                                            <th style="font-size: 12px;">Crnt Qty</th>
                                                            <th style="font-size: 12px;">Disc</th>
                                                            <th style="font-size: 12px;">Amount</th>
                                                            <th style="font-size: 12px;">Return Qty</th>
                                                            <th style="font-size: 12px;">Return amount</th>
                                                            <th style="font-size: 12px;">Rmv</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="supRtrnTbody">                                          
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>                 
                                        </div> 
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-box table-responsive"> 
                                                    <table id="saleDetails" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>                                                        
                                                        </thead>
                                                        <tbody id="grnDetailTbody"> 
                                                           <!-- <tr>
                                                                <td>Grand Total</td><td>Return Total</td>
                                                            </tr>   -->                                       
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>                 
                                        </div>                             
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button id="saveSupRetn" type="" class="btn btn-primary">Save Return</button>
                                    </div>
                            <!-- </form> -->
                                </div>
                            </div>
                        </div>
                        <!-- Modal supplier end-->
                    </div>
                </div>
            </div>
        </header>
        <!-- End Navigation Bar-->        

        <script>
            $( function() {
                $("#Cus_Rtn_btn").click(function() { //
                    $('#customerModal').modal('show');                 
                });

                var discnt_CR=0;
                var cusID_R=0;
                var saleID_R=0;
                //get invoice items to modal
                function getInvoice(){
                    $.ajax({
                            type: 'post',
                            url: "<?php echo base_url('cusReturns/getInvoice'); ?>",
                            data:  {invcID: saleID_R},	
                            async: false,
                            dataType:'json',  
                            success: function(data){
                                var rows = '';
                                var i;
                                for(i=0; i<data.length; i++){
                                rows+= '<tr>'+
                                            '<td class="" style="display:none;">'+data[i].itm_id+'</td>'+
                                            '<td>'+data[i].itm_code+'</td>'+
                                            '<td>'+data[i].itm_name+'</td>'+
                                            '<td style="Text-align: right;">'+data[i].saleitem_price+'</td>'+
                                            '<td class="oldQty" style="Text-align: right;">'+data[i].saleitem_quantity+'</td>'+//field in use
                                            '<td style="Text-align: right;">'+data[i].saleitem_discount+'% </td>'+
                                            '<td class="amount" style="Text-align: right;">'+data[i].saleitem_total+'</td>'+
                                            '<td><input type="text" name="rtrnQty" class="rtrnQty" style="text-align:right;" size="6"></td>'+
                                            '<td class="rtrnAmont" style="Text-align: right;">0.00</td>'+
                                            '<td>'+
                                            '<a href="javascript:;" class="btn btn-sm btn-danger cls-delete"><i class="fa fa-times-rectangle-o"></i></a>'+
                                            '</td>'+
                                        '</tr>';
                                }
                                $('#cusRtrnTbody').html(rows);
                                if(data==false){
                                    alert("No item found for entered invoice");
                                }
                            },
                            error: function() {
                                 //saleitem_ctreatedat
                                alert("There was an error. Try again please!");
                            },
                        });
                }
                //get all items of entered invoice to modal, from db
                $("#invoice_r").on('keyup', function (e) {
                    e.preventDefault();
                        saleID_R = $(this).val();//invoiceID
                        getInvoice();

                        //get cusid, granttotal,discount
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('cusReturns/getSaleDetails'); ?>",
                            data: {invcID: saleID_R}, 
                            async: false,
                            dataType: "json",
                            success: function (data) {
                                cusID_R = data.sale_cus_id;
                                var grandtotal_CR =data.sale_grandtotal;
                                var subtotal_CR =data.sale_subtotal;
                                var less_CR =data.sale_less;
                                discnt_CR =data.sale_discount;
                                var row='<tr>'+
                                            '<td> Sub Total : '+subtotal_CR+'</td>'+
                                            '<td> Invoice Dis : '+discnt_CR+'% </td>'+ 
                                            '<td> Invoice Less  : '+less_CR+'</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<td> Grand Total : '+grandtotal_CR+'</td>'+
                                            '<td>'+""+'</td>'+
                                            '<td>'+""+'</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<td id="ReturnTotal"> Return Total : 0.00</td>'+
                                            '<td>'+""+'</td>'+ 
                                            '<td>'+""+'</td>'+
                                        '</tr>';
                                        var cusrows = $("#returnTable").find("tr").length;
                                        if(cusrows>1){
                                            $('#saleDetailTbody').html(row);
                                        }
                                        else{
                                            $('#saleDetailTbody').html("");
                                        }                                
                            },
                            error: function (err) {
                                alert("Sales info collection error");
                            }
                        });
                });               
                
                //Remove non returing items
                $('#cusRtrnTbody').on('click', '.cls-delete', function(e){
                    $(this).parent().parent().remove();
                    var cusrows = $("#returnTable").find("tr").length;
                    if(cusrows==1){
                        $('#saleDetailTbody').html("");
                    }
                });
                
                var rtrnTotal=0;
                var checkNum,checkDeci,checkQty;
                //check return qty with sold qty & return qty validation
                $('#cusRtrnTbody').on('keyup', '.rtrnQty', function(event){
                    checkNum=1;
                    checkDeci=1;
                    checkQty=1;
                    var rtrnQty = $(this).val();
                    var oldQty =parseFloat($(this).parent().siblings('.oldQty').text());                    
                        if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
                            (event.keyCode != 13) &&
                            ((event.which < 48 || event.which > 57) &&
                             (event.which != 0 && event.which != 8))) {
                            event.preventDefault();
                            alert("Quantity should be a number");
                            checkNum=0;
                        }
                        else if ((rtrnQty.indexOf('.') != -1) &&
                            (rtrnQty.substring(rtrnQty.indexOf('.')).length > 3) &&
                            (event.which != 0 && event.which != 8) &&
                            ($(this)[0].selectionStart >= rtrnQty.length - 2)) {
                            event.preventDefault();
                            alert("Quantity is not valid");
                            checkDeci=0;
                        }
                        else if(rtrnQty>parseFloat(oldQty)){
                            alert("Return quantity is greater than sold quantity");
                            checkQty=0;
                        }
                        else{
                            var amount=parseFloat($(this).parent().siblings('.amount').text());  
                            var actualSalePriceOf1Piece=(amount-(amount*discnt_CR/100))/oldQty;
                            var actualTotalReturnOf1Item=(actualSalePriceOf1Piece*rtrnQty).toFixed(2);
                            $(this).parent().siblings('.rtrnAmont').text(actualTotalReturnOf1Item);

                            var rows = $("#returnTable").find("tr").length;    
                            var retrn1Item=0;
                            rtrnTotal=0;
                            for(var i = 1; i < rows; i++) {      
                                retrn1Item=parseFloat($("#returnTable").find("tr").eq(i).find("td").eq(8).text());                            
                                rtrnTotal+=retrn1Item;
                            }
                            var rtrnTotal2= (rtrnTotal).toFixed(2);
                                $('#ReturnTotal').html('Return Total : '+rtrnTotal2);
                        }
                });
                
                //save cus returns
                $('#saveCusRetn').click(function(e){
                    var ReturnID=0;
                    var rows = $("#returnTable").find("tr").length;
                    if(checkNum!=0&&checkDeci!=0&&checkQty!=0&&rows>1){
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('cusReturns/addReturn'); ?>",
                            data: {cusID:cusID_R,rtrnTotal:rtrnTotal},
                            async: false,
                            dataType: "json",
                            success: function (RID) {
                                ReturnID=RID;
                            },
                            error: function (err) {
                                alert("Error in return...");
                            }
                        });
                        //update the cus retunr in sales table
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('sales/updateSalesforCusReturn'); ?>",
                            data: {saleIDR:saleID_R,rtrnTotal:rtrnTotal,discnt:discnt_CR},
                            async: false,
                            dataType: "json",
                            success: function (res) {
                                if(res){
                                   // console.log("sales table updated for cus return")
                                }
                            },
                            error: function (err) {
                                alert("Error in sales update...");
                            }
                        });
                        //save cus return items
                        for(var i = 1; i < rows; i++){
                            var retrnAmount=parseFloat($("#returnTable").find("tr").eq(i).find("td").eq(8).text()); 
                            var retrnQty=$("#returnTable").find("tr").eq(i).find("td").eq(7).children().val();
                            if(retrnQty>0&&retrnAmount>0){
                                var rItmID=$("#returnTable").find("tr").eq(i).find("td").eq(0).text();
                                var rItmname=$("#returnTable").find("tr").eq(i).find("td").eq(2).text();
                                
                                $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('cusReturns/addReturnItems'); ?>",
                                        data: {rID:ReturnID,rItmID:rItmID,rQty:retrnQty,rAmount:retrnAmount},
                                        async: false,
                                        dataType: "json",
                                        success: function () {
                                            //alert("complete");
                                        },
                                        error: function (err) {
                                            alert("Error in return items");
                                        }
                                    });
                                
                                var insuffRetrnQty=0;
                                // get the qty  from insuff table of this item 
                                $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('InsufficentStockSale/checkIsItInsuffiItem'); ?>",
                                        data: {saleIDR:saleID_R,rItmID:rItmID},
                                        async: false,
                                        dataType: "json",
                                        success: function (res) {
                                            insuffRetrnQty=res;
                                            alert(insuffRetrnQty);
                                        },
                                        error: function (err) {
                                            alert("Error in return items");
                                        }
                                    });

                                   if(insuffRetrnQty>0){
                                            $.ajax({
                                                type: "Post",
                                                url:"<?php echo base_url('InsufficentStockSale/returnUpdateInInsuffi'); ?>",
                                                data: {saleIDR:saleID_R,rItmID:rItmID,qty:retrnQty},
                                                async: false,
                                                dataType: "json",
                                                success: function (res) {
                                                    alert(rItmname+ " returned");
                                                },
                                                error: function (err) {
                                                    alert("Error in return items");
                                                }
                                            });

                                   }else{
                                        //change crrent qty for cus return (in additional grn table)-
                                        $.ajax({
                                            type: "Post",
                                            url:"<?php echo base_url('CurQtyWithGrn/ChangeQtyToCusReturn'); ?>",
                                            data: {saleID:saleID_R,itmid:rItmID,qty:retrnQty},
                                            async: false,
                                            dataType: "json",
                                            success: function (res) {
                                                if(res){
                                                    alert(rItmname+ " returned");
                                                }
                                                else{
                                                    alert("Error in "+rItmname+" returns"); 
                                                }
                                            },
                                            error: function (err) {
                                                alert("Error in customer return");
                                                console.log(err);
                                            }
                                        });

                                        //update in sales item table for cus retrn
                                        $.ajax({
                                            type: "Post",
                                            url:"<?php echo base_url('sales/updateSaleItemsforCusReturn'); ?>",
                                            data: {saleID:saleID_R,itmid:rItmID,qty:retrnQty},
                                            async: false,
                                            dataType: "json",
                                            success: function (res) {
                                                if(res){
                                                // alert(rItmname+ " returned");
                                                }
                                                else{
                                                    alert("Error in "+rItmname+" sales update"); 
                                                }
                                            },
                                            error: function (err) {
                                                alert("Error in sales update for return");
                                                console.log(err);
                                            }
                                        });

                                   }                           



                                    //cus return stock +
                                    $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('stocks/increaseStock'); ?>",
                                        data: {itmid:rItmID,qty:retrnQty},
                                        async: false,
                                        dataType: "json",
                                        success: function (res) {
                                            //alert("added to stock");
                                        },
                                        error: function (err) {
                                            alert("error in return stock ");
                                            console.log(err);
                                        }
                                    });

                                    //cus return stocklog 
                                    $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('stocks/stocklog'); ?>",
                                        data: {itmid:rItmID,qty:retrnQty,cusRID:ReturnID,cusID:cusID_R},
                                        async: false,
                                        dataType: "json",
                                        success: function (res) {
                                            //alert("added to stock");
                                        },
                                        error: function (err) {
                                            alert("error in grn stocklog");
                                            console.log(err);
                                        }
                                    });

                                                                    
                            }                                                       
                        }
                        //update new qty in modal
                        getInvoice();
                    }
                    else{
                        alert("Return quantity is not valid");
                    }                 
                });
                //End of Customer Return//

                // Start of Supplier Return //

                $("#Sup_Rtn_btn").click(function() { 
                    $('#supplierModal').modal('show');                 
                });
                var discnt_SR=0;
                var supID_R=0;
                var grnid_R=0;
                function getGrnItems(){
                    $.ajax({
                            type: 'post',
                            url: "<?php echo base_url('supReturns/getGrnItems'); ?>",
                            data:  {grnid: grnid_R},	
                            async: false,
                            dataType:'json',  
                            success: function(data){                                
                                var rows = '';
                                var i;
                                for(i=0; i<data.length; i++){  //grnitm_createdat
                                rows+= '<tr>'+
                                            '<td class="" style="display:none;">'+data[i].itm_id+'</td>'+
                                            '<td>'+data[i].itm_code+'</td>'+
                                            '<td>'+data[i].itm_name+'</td>'+
                                            '<td style="Text-align: right;">'+data[i].grnitm_price+'</td>'+
                                            '<td class="grn_oldQty" style="Text-align: right;">'+data[i].cur_currentQTY+'</td>'+//field in use
                                            '<td style="Text-align: right;">'+data[i].grnitm_discount+'% </td>'+
                                            '<td class="grn_amount" style="Text-align: right;">'+data[i].grnitm_total+'</td>'+
                                            '<td><input type="text" name="" class="sup_rtrnQty" style="text-align:right;" size="6"></td>'+
                                            '<td class="sup_rtrnAmont" style="Text-align: right;">0.00</td>'+
                                            '<td>'+
                                            '<a href="javascript:;" class="btn btn-sm btn-danger cls-delete"><i class="fa fa-times-rectangle-o"></i></a>'+
                                            '</td>'+
                                        '</tr>';
                                }
                                $('#supRtrnTbody').html(rows);
                                if(data==false){
                                    alert("No item found for entered grn");
                                }
                            },
                            error: function() {
                                alert("There was an error. Try again please!");
                            },
                        });
                }
                //get all items of entered grnid to modal, from db
                $("#grnid_r").on('keyup', function (e) {
                    e.preventDefault();
                        grnid_R = $(this).val();//grnid
                        getGrnItems();

                        //get supid, granttotal,discount
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('supReturns/getGrnDetails'); ?>",
                            data: {grnid: grnid_R},
                            async: false,
                            dataType: "json",
                            success: function (data) {
                                supID_R = data.grn_supplier_id;
                                var grandtotal_SR =data.grn_grandtotal;
                                discnt_SR =data.grn_discount;
                                var row='<tr>'+
                                            '<td> Grn Total : '+grandtotal_SR+'</td>'+
                                            '<td> Grn Dis : '+discnt_SR+'</td>'+ 
                                        '</tr>'+
                                        '<tr>'+
                                            '<td id="supReturnTotal"> Return Total : 0.00</td>'+
                                            '<td>'+""+'</td>'+ 
                                        '</tr>';
                                        var suprows = $("#supReturnTable").find("tr").length;
                                        if(suprows>1){
                                            $('#grnDetailTbody').html(row);
                                        }
                                        else{
                                            $('#grnDetailTbody').html("");
                                        }                                
                            },
                            error: function (err) {
                                alert("Grn info collection error");
                            }
                        });
                });

                //Remove non returing items
                $('#supRtrnTbody').on('click', '.cls-delete', function(e){
                    $(this).parent().parent().remove();
                    var suprows = $("#supReturnTable").find("tr").length;
                    if(suprows==1){
                        $('#grnDetailTbody').html("");
                    }
                });

                var supRtrnTotal=0;
                var supCheckNum,supCheckDeci,supCheckQty;
                //check return qty with boughtQty & return qty validation
                $('#supRtrnTbody').on('keyup', '.sup_rtrnQty', function(event){
                    supCheckNum=1;
                    supCheckDeci=1;
                    supCheckQty=1;
                    var supRtrnQty = $(this).val();
                    var boughtQty =parseFloat($(this).parent().siblings('.grn_oldQty').text());                    
                        if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
                            (event.keyCode != 13) &&
                            ((event.which < 48 || event.which > 57) &&
                             (event.which != 0 && event.which != 8))) {
                            event.preventDefault();
                            alert("Quantity should be a number");
                            supCheckNum=0;
                        }
                        else if ((supRtrnQty.indexOf('.') != -1) &&
                            (supRtrnQty.substring(supRtrnQty.indexOf('.')).length > 3) &&
                            (event.which != 0 && event.which != 8) &&
                            ($(this)[0].selectionStart >= supRtrnQty.length - 2)) {
                            event.preventDefault();
                            alert("Quantity is not valid");
                            supCheckDeci=0;
                        }
                        else if(supRtrnQty>parseFloat(boughtQty)){
                            alert("Return quantity is greater than bought quantity");
                            supCheckQty=0;
                        }
                        else{
                            var amount=parseFloat($(this).parent().siblings('.grn_amount').text());  
                            var actualSalePriceOf1Piece=(amount-(amount*discnt_SR/100))/boughtQty;
                            var actualTotalReturnOf1Item=(actualSalePriceOf1Piece*supRtrnQty).toFixed(2);
                            $(this).parent().siblings('.sup_rtrnAmont').text(actualTotalReturnOf1Item);

                            var rows = $("#supReturnTable").find("tr").length;    
                            var retrn1Item=0;
                            supRtrnTotal=0;
                            for(var i = 1; i < rows; i++) {      
                                retrn1Item=parseFloat($("#supReturnTable").find("tr").eq(i).find("td").eq(8).text());                            
                                supRtrnTotal+=retrn1Item;
                            }
                            var rtrnTotal2= (supRtrnTotal).toFixed(2);
                                $('#supReturnTotal').html('Return Total : '+rtrnTotal2);
                        }
                });

                //save sup returns
                $('#saveSupRetn').click(function(e){
                    var SupRtrnID=0;
                    var rows = $("#supReturnTable").find("tr").length;
                    if(supCheckNum!=0&&supCheckDeci!=0&&supCheckQty!=0&&rows>1){
                        $.ajax({
                            type: "Post",
                            url:"<?php echo base_url('supReturns/addReturn'); ?>",
                            data: {supID:supID_R,supRtrnTotal:supRtrnTotal},
                            async: false,
                            dataType: "json",
                            success: function (RID) {
                                SupRtrnID=RID;
                            },
                            error: function (err) {
                                alert("Error in supplier return...");
                            }
                        });
                        //save sup return items
                        for(var i = 1; i < rows; i++){
                            var retrnAmount=parseFloat($("#supReturnTable").find("tr").eq(i).find("td").eq(8).text()); 
                            var retrnQty=$("#supReturnTable").find("tr").eq(i).find("td").eq(7).children().val();
                            if(retrnQty>0&&retrnAmount>0){
                                var rItmID=$("#supReturnTable").find("tr").eq(i).find("td").eq(0).text();
                                var rItmname=$("#supReturnTable").find("tr").eq(i).find("td").eq(2).text();
                                    $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('supReturns/addReturnItems'); ?>",
                                        data: {rID:SupRtrnID,rItmID:rItmID,rQty:retrnQty,rAmount:retrnAmount},
                                        async: false,
                                        dataType: "json",
                                        success: function () {
                                            //alert("complete");
                                        },
                                        error: function (err) {
                                            alert("Error in return items");
                                        }
                                    });

                                    // stock -
                                    $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('stocks/decreaseStock'); ?>",
                                        data: {itmid:rItmID,qty:retrnQty},
                                        async: false,
                                        dataType: "json",
                                        success: function (res) {
                                            //alert("added to stock");
                                        },
                                        error: function (err) {
                                            alert("error in return stock ");
                                            console.log(err);
                                        }
                                    });

                                     // stocklog 
                                    $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('stocks/stocklog'); ?>",
                                        data: {itmid:rItmID,qty:retrnQty,supRID:SupRtrnID,supID:supID_R},
                                        async: false,
                                        dataType: "json",
                                        success: function (res) {
                                            //alert("added to stock");
                                        },
                                        error: function (err) {
                                            alert("error in grn stocklog");
                                            console.log(err);
                                        }
                                    });

                                    // to change crrent qty -
                                    $.ajax({
                                        type: "Post",
                                        url:"<?php echo base_url('CurQtyWithGrn/ChangeQtyToSupReturn'); ?>",
                                        data: {grnID:grnid_R,itmid:rItmID,qty:retrnQty},
                                        async: false,
                                        dataType: "json",
                                        success: function (res) {
                                            if(res){
                                                alert(rItmname+ " returned");
                                            }
                                            else{
                                                alert("No " +rItmname+ " in stock"); 
                                            }
                                        },
                                        error: function (err) {
                                            alert("Error in supplier return");
                                            console.log(err);
                                        }
                                    });                                                                 
                            } 
                                                     
                        }
                        //update new qty in modal
                        getGrnItems();
                    }
                    else{
                        alert("Return quantity is not valid");
                    }                 
                });
            });
        </script>