        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="wrapper">
            <div class="container">
            <?php if(isset($id)) { ?>
             <!-- Page-Title -->
             <div class="row">
                    <div class="col-sm-12">                    
                        <h4 class="page-title">User Editing</h4>
                    </div>
                </div>
                <!-- end row -->
            <?php } ?>
            <?php if(!isset($id)) {
                
                
                ?>
                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">                    
                        <h4 class="page-title">Register</h4>
                    </div>
                </div>
                <!-- end row -->

                <!-- Add User Form -->
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">User Details</h4>
                            <?php $this->load->library('form_validation'); ?>
                            <div id="showerrors"></div>
                            <form id="formid" name="formname" action="#" method="post">
                                <div class="form-group row">                                
                                    <label for="username" class="col-3 col-form-label">Username</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Username" 
                                        name="username" id="username" required data-parsley-minlength="5">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name" class="col-3 col-form-label">Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="name" id="name" required data-parsley-minlength="3">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-3 col-form-label">Password</label>
                                    <div class="col-9">
                                        <input class="form-control" type="password" placeholder="Password" 
                                        name="password" id="password" required data-parsley-minlength="5">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pass2" class="col-3 col-form-label">Confirm Password</label>
                                    <div class="col-9">
                                        <input class="form-control" type="password" placeholder="Re-Type Password" 
                                        name="pass2" id="pass2" required data-parsley-equalto="#password">
                                    </div>
                                </div>
                            <div class="form-group row">
                            <div class="col-3"></div>
                                <div class="col-9 checkbox checkbox-primary">
                                    <input id="admincheck" name="admincheck" type="checkbox" value=1>
                                    <label for="admincheck">
                                        Admin
                                    </label>
                                </div>
                            </div>
                            <!--Privilege pages--> 

                            <div class="row" id="checkboxes">
                                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-6">                
                                    <div class="p-20">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th><b><u> Masters </u></b></th>
                                                <th> </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="item" name="item" type="checkbox" value=1>
                                                    <label for="item">
                                                        Item
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="category" name="category" type="checkbox" value=1>
                                                    <label for="category">
                                                        Category
                                                    </label>
                                                    </div>                                                
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="customer" name="customer" type="checkbox" value=1>
                                                    <label for="customer">
                                                        Customer
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="supplier" name="supplier" type="checkbox" value=1>
                                                    <label for="supplier">
                                                        Supplier
                                                    </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>                                                
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="store" name="store" type="checkbox" value=1>
                                                    <label for="store">
                                                        Store
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="staff" name="staff" type="checkbox" value=1>
                                                    <label for="staff">
                                                        Staff
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="tax" name="tax" type="checkbox" value=1>
                                                    <label for="tax">
                                                        Tax .
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="bank" name="bank" type="checkbox" value=1>
                                                    <label for="bank">
                                                       Bank Account
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>                                                    
                                                </td>
                                            </tr>                                         
                                           <tr>
                                               <th><b><u> Stores </u></b></th>
                                                <th></th>
                                                <th></th>
                                            </tr>                                               
                                            <tr class="col-md-12"> 
                                                <?php foreach($allStores as $store_row){?>
                                                <td style="font-weight:600;" >
                                                    <div class="row">
                                                    <input  class="form-control" type="checkbox" name="user_store[]" value="<?php echo $store_row['store_id'];?>">
                                                    <?php echo $store_row['store_name'];?>    
                                                 </div>
                                                </td>
                                               <?php }?>                                               
                                                   <td > 
                                                   </td>
                                            
                                            </tr> 
                                            <tr>
                                               <th><b><u> Transactions </u></b></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>                                                
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="grn" name="grn" type="checkbox" value=1>
                                                    <label for="grn">
                                                        GRN
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="sales" name="sales" type="checkbox" value=1>
                                                    <label for="sales">
                                                        Sales
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="expense" name="expense" type="checkbox" value=1>
                                                    <label for="expense">
                                                        Expense
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>                                                    
                                                </td>
                                            </tr>
											<tr>
                                                <th><b><u> Listing </u></b></th>
                                                <th> </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="l_allGrn" name="l_allGrn" type="checkbox" value=1>
                                                    <label for="l_allGrn">
                                                      All GRN
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="l_stock" name="l_stock" type="checkbox" value=1>
                                                    <label for="l_stock">
                                                        Stock
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="l_stockSupplierWise" name="l_stockSupplierWise" type="checkbox" value=1>
                                                    <label for="l_stockSupplierWise">
                                                        Stock Supplier Wise
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="l_stockLog" name="l_stockLog" type="checkbox" value=1>
                                                    <label for="l_stockLog">
                                                        Stock Log
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="l_cheque" name="l_cheque" type="checkbox" value=1>
                                                    <label for="l_cheque">
                                                        Cheque
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                               <th><b><u> Reports </u></b></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="re_stock" name="re_stock" type="checkbox" value=1>
                                                    <label for="re_stock">
                                                      Stock
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="re_stockLog" name="re_stockLog" type="checkbox" value=1>
                                                    <label for="re_stockLog">
                                                      Stock Log
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="re_salesReport" name="re_salesReport" type="checkbox" value=1>
                                                    <label for="re_salesReport">
                                                        Sales Report
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="re_monthlySalesReport" name="re_monthlySalesReport" type="checkbox" value=1>
                                                    <label for="re_monthlySalesReport">
                                                       Monthly Sales Report
                                                    </label>
                                                    </div>
                                                </td>
                                                </tr>

                                                <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="re_purchaseReport" name="re_purchaseReport" type="checkbox" value=1>
                                                    <label for="re_purchaseReport">
                                                        Purchase Report
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="re_expenseReport" name="re_expenseReport" type="checkbox" value=1>
                                                    <label for="re_expenseReport">
                                                        Expense Report
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="re_todaySummary" name="re_todaySummary" type="checkbox" value=1>
                                                    <label for="re_todaySummary">
                                                        Today Summary
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="re_profitLossReport" name="re_profitLossReport" type="checkbox" value=1>
                                                    <label for="re_profitLossReport">
                                                        Profit/Loss Report
                                                    </label>
                                                    </div>
                                                </td>

                                            </tr>
											<tr>
                                                <th><b><u> Payments </u></b></th>
                                                <th> </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="py_customerPayment" name="py_customerPayment" type="checkbox" value=1>
                                                    <label for="py_customerPayment">
                                                      Customer Payment
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="py_supplierPayment" name="py_supplierPayment" type="checkbox" value=1>
                                                    <label for="py_supplierPayment">
                                                        Supplier Payment
                                                    </label>
                                                    </div>
                                                </td>

                                            </tr>                                        
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>                               
                            </div>
                            <!-- end row -->
                            <!--End of Privilege pages-->
                            <button type="submit" id="add" class="btn btn-primary waves-effect">Add</button>
                            <button type="reset" class="btn btn-secondary waves-effect">Reset</button>                         
                            </form>  
                           </div>
                    </div>
                </div>
            <?php } ?>
                <!--End of Add User Form -->

                <!-- Start of Edit User Form -->
            <?php if(isset($id)){  foreach($userEdit as $user){?>                   
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-2">
                    </div>      
                    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 ">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">User Details update</h4>
                            <form id="editForm" name="editForm" action="#" method="post">
                            <input type="hidden" name="hiddenID" id="hiddenID" value="<?php echo $id ?>" >
                                <div class="form-group row">
                                    <label for="E_username" class="col-3 col-form-label">Username</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Username" 
                                        name="E_username" id="E_username" value="<?php echo $user['user_username']?>"  required data-parsley-minlength="5">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="E_name" class="col-3 col-form-label">Name</label>
                                    <div class="col-9">
                                        <input class="form-control" type="text" placeholder="Enter Name" 
                                        name="E_name" id="E_name" value="<?php echo $user['user_name']?>" required data-parsley-minlength="3">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="E_password" class="col-3 col-form-label">Password</label>
                                    <div class="col-9">
                                        <input class="form-control" type="password" placeholder="Password" value="<?php echo $user['user_password']?>"
                                        name="E_password" id="E_password" required data-parsley-minlength="5">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="E_pass2" class="col-3 col-form-label">Confirm Password</label>
                                    <div class="col-9">
                                        <input class="form-control" type="password" placeholder="Re-Type Password" value="<?php echo $user['user_password']?>"
                                        name="E_pass2" id="E_pass2" required data-parsley-equalto="#E_password">
                                    </div>
                                </div>
                            <div class="form-group row">
                            <div class="col-3"></div>
                                <div class="col-9 checkbox checkbox-primary">
                                    <input id="E_admincheck" name="E_admincheck" type="checkbox" 
                                    <?php echo ($user['user_role']==1 ? 'checked' : '');?> value=1>
                                    <label for="E_admincheck">
                                       Admin
                                    </label>
                                </div>
                            </div>
                            <!--Privilege pages--> 

                            <div class="row" id="checkboxes">
                                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-6">                
                                    <div class="p-20">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Masters </th>
                                                <th> </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_item" name="E_item" type="checkbox" 
                                                    <?php echo ($user['priv_item']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_item">
                                                        Item
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_category" name="E_category" type="checkbox"
                                                    <?php echo ($user['priv_category']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_category">
                                                        Category
                                                    </label>
                                                    </div>                                                
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_customer" name="E_customer" type="checkbox"
                                                    <?php echo ($user['priv_customer']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_customer">
                                                        Customer
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_supplier" name="E_supplier" type="checkbox"
                                                    <?php echo ($user['priv_supplier']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_supplier">
                                                        Supplier
                                                    </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>                                                
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_store" name="E_store" type="checkbox"
                                                    <?php echo ($user['priv_store']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_store">
                                                        Store
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_staff" name="E_staff" type="checkbox"
                                                    <?php echo ($user['priv_staff']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_staff">
                                                        Staff
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_tax" name="E_tax" type="checkbox"
                                                    <?php echo ($user['priv_tax']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_tax">
                                                        Tax .
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_bank" name="E_bank" type="checkbox"
                                                    <?php echo ($user['priv_bank']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_bank">
                                                       Bank Account
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>                                                    
                                                </td>
                                            </tr>  
                                            <tr>
                                               <th><b><u> Stores </u></b></th>
                                                <th></th>
                                                <th></th>
                                            </tr>                                               
                                            <tr class="col-md-12"> 
                                                <?php
                                                $user_assigned_stores=array();
                                                foreach($userStores as $userStores_row){
                                                  array_push($user_assigned_stores,$userStores_row['store_id']) ;
                                                }
                                                foreach($allStores as $store_row){?>
                                             
                                                   <td>
                                                    <div class="row" style="font-weight:600;">
                                                    <input type="checkbox" name="user_store[]"  <?php echo (in_array($store_row['store_id'], $user_assigned_stores) ? 'checked' : ''); ?> value="<?php echo $store_row['store_id'];?>" >
                                                    <?php echo $store_row['store_name'];?>
                                                   
                                                    </div>
                                                   </td>
                                            
                                               <?php }?>
                                            </tr> 
                                            <tr>
                                                <th>Transactions </th>
                                                <th> </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_grn" name="E_grn" type="checkbox" 
                                                    <?php echo ($user['priv_grn']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_grn">
                                                       GRN
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_sales" name="E_sales" type="checkbox"
                                                    <?php echo ($user['priv_sales']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_sales">
                                                        Sales
                                                    </label>
                                                    </div>                                                
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_expense" name="E_expense" type="checkbox"
                                                    <?php echo ($user['priv_expense']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_expense">
                                                        Expense
                                                    </label>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <th><b><u> Listing </u></b></th>
                                                <th> </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_l_allGrn" name="E_l_allGrn" type="checkbox" 
                                                    <?php echo ($user['priv_l_allGrn']==1 ? 'checked' : '');?>  value=1>
                                                    <label for="E_l_allGrn">
                                                      All GRN
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_l_stock" name="E_l_stock" type="checkbox" 
                                                    <?php echo ($user['priv_l_stock']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_l_stock">
                                                        Stock
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_l_stockSupplierWise" name="E_l_stockSupplierWise" type="checkbox" 
                                                    <?php echo ($user['priv_l_stockSupplierWise']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_l_stockSupplierWise">
                                                        Stock Supplier Wise
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_l_stockLog" name="E_l_stockLog" type="checkbox"
                                                    <?php echo ($user['priv_l_stockLog']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_l_stockLog">
                                                        Stock Log
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_l_cheque" name="E_l_cheque" type="checkbox"
                                                    <?php echo ($user['priv_l_cheque']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_l_cheque">
                                                        Cheque
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                               <th><b><u> Reports </u></b></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_re_stock" name="E_re_stock" type="checkbox"
                                                    <?php echo ($user['priv_re_stock']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_re_stock">
                                                      Stock
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_re_stockLog" name="E_re_stockLog" type="checkbox"
                                                    <?php echo ($user['priv_re_stockLog']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_re_stockLog">
                                                      Stock Log
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_re_salesReport" name="E_re_salesReport" type="checkbox"
                                                    <?php echo ($user['priv_re_salesReport']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_re_salesReport">
                                                        Sales Report
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_re_monthlySalesReport" name="E_re_monthlySalesReport" type="checkbox"
                                                    <?php echo ($user['priv_re_monthlySalesReport']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_re_monthlySalesReport">
                                                       Monthly Sales Report
                                                    </label>
                                                    </div>
                                                </td>
                                                </tr>

                                                <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_re_purchaseReport" name="E_re_purchaseReport" type="checkbox"
                                                    <?php echo ($user['priv_re_purchaseReport']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_re_purchaseReport">
                                                        Purchase Report
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_re_expenseReport" name="E_re_expenseReport" type="checkbox"
                                                    <?php echo ($user['priv_re_expenseReport']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_re_expenseReport">
                                                        Expense Report
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_re_todaySummary" name="E_re_todaySummary" type="checkbox"
                                                    <?php echo ($user['priv_re_todaySummary']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_re_todaySummary">
                                                        Today Summary
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_re_profitLossReport" name="E_re_profitLossReport" type="checkbox"
                                                    <?php echo ($user['priv_re_profitLossReport']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_re_profitLossReport">
                                                        Profit/Loss Report
                                                    </label>
                                                    </div>
                                                </td>

                                            </tr>
											<tr>
                                                <th><b><u> Payments </u></b></th>
                                                <th> </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_py_customerPayment" name="E_py_customerPayment" type="checkbox"
                                                    <?php echo ($user['priv_py_customerPayment']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_py_customerPayment">
                                                      Customer Payment
                                                    </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 checkbox checkbox-custom">
                                                    <input id="E_py_supplierPayment" name="E_py_supplierPayment" type="checkbox"
                                                    <?php echo ($user['priv_py_supplierPayment']==1 ? 'checked' : '');?> value=1>
                                                    <label for="E_py_supplierPayment">
                                                        Supplier Payment
                                                    </label>
                                                    </div>
                                                </td>

                                            </tr>
     
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>                               
                            </div>
                            <!-- end row -->
                            <?php } ?> <!-- end of foreach -->
                            <!--End of Privilege pages-->
                                <button type="submit" id="btnsave" class="btn btn-primary waves-effect">Update</button>
                                <a  href="<?php echo base_url();?>register" class="btn btn-secondary waves-effect">Cancel</a>
                            </form>                     
                        </div>
                    </div>
                </div>
            <?php } ?>
                <!--End of User Edit Form -->
            
                 <!--Start Table & row -->
                 <div class="row">
                    <div class="col-12">
                        <div class="card-box table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody id="tbodyID">                                          
                                </tbody>
                            </table>
                        </div>
                    </div>                 
                </div>         
                 <!-- end Table & row -->
			  
            </div> <!-- container -->

<!-- Validation js (Parsleyjs) -->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/parsleyjs/parsley.min.js'?>"></script>
<script>
    $( function() {
       // $('form').parsley();
   // showStores();
    showAllUsers();
    //check all check boxes for admin
        $("#admincheck").click(function () {
            if ($(this).is(":checked")) {
                $("#checkboxes").hide();  
                $('input:checkbox').attr('checked','checked');       
            } else {
                $("#checkboxes").show();
                $('input:checkbox').removeAttr('checked', false);
            }
        });
        $("#E_admincheck").click(function () {
            if ($(this).is(":checked")) {
                $("#checkboxes").hide();  
                $('input:checkbox').attr('checked','checked');         
            } else {
                $("#checkboxes").show();
                $('input:checkbox').removeAttr('checked', false);
            }
        });
               
    //register users
        $("#formid").submit(function(e) {
            e.preventDefault();
            var data = $('#formid').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('register/addUser'); ?>",
                        data: data,
                        async: false,
                        //dataType:'json',  
                        success: function(response){
                            if(response=="true"){
                                showAllUsers();
                                $('#formid')[0].reset();
                                swal({
                                    type: 'success',
                                    title: 'New user added',
                                    showConfirmButton: false,
                                    timer: 1700
                                    });
                            }else{
                                swal({
                                    type: 'error',
                                    title: 'Validation...',
                                    text: 'Username already exists,or Password miss match!',
                                });
                               // alert(response[0]);
                               // alert(response[1]);
                               // alert(response[2]);
                               // alert(response[3]);
                                //var errors=JSON.parse(response);
                               // var error1 = '<p>'+errors.usernameErr+'</p>';
                                //var error2 = '<p>'+errors.nameErr+'</p>';
                               // var error3 = '<p>'+errors.passwordErr+'</p>';
                               // var error4 = '<p>'+errors.passErr+'</p>';
                            }
                        },
                        error: function() {
                            //Error sweet alert
                        }
                    });
        });

        	function showAllUsers(){
				$.ajax({
					type: 'post',
					url:'<?php echo base_url()?>register/showAllUsers',
					async:false,
					dataType:'json',
					success:function(data){                        
						var rows = '';
						var i;
						for(i=0; i<data.length; i++){
                            if(data[i].user_role==1){
                            $role = 'Admin';
                            }
                            else{
                            $role = 'User';
                        }
                        rows+= '<tr>'+
                                    '<td>'+data[i].user_id+'</td>'+
                                    '<td>'+data[i].user_username+'</td>'+
                                    '<td>'+data[i].user_name+'</td>'+
                                    '<td>'+$role+'</td>'+
                                    '<td>'+
                                    '<a href="<?php echo base_url();?>register/EditUser/'+data[i].user_id+'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>'+
                                    '</td>'+
                                    '<td>'+
                                    '<a href="javascript:;" class="btn btn-sm btn-danger cus-delete" data="'+data[i].user_id+'"><i class="fa fa-times-rectangle-o"></i></a>'+
                                    '</td>'+
                                '</tr>';
						}
							$('#tbodyID').html(rows);						
					},
					error: function(){
						swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            });
					}
				});
			}
                        
                        
//        	function showStores(){
//				$.ajax({
//					type: 'post',
//					url:'<?php //echo base_url()?>Register/showStores',
//					async:false,
//					dataType:'json',
//					success:function(data){                        
//						var rows = '';
//						var i;
//		        for(i=0; i<data.length; i++){
//                        for (x in data) {
//                             alert(x);
//                             }
//                            //  alert(data[i]);
//                              rows+= '<td>'+
//                                        '<div class="col-9 checkbox checkbox-custom">'+
//                                        '<input id="store_list" name="store_list" type="checkbox" value=1>'+
//                                        '<label for="store">'+
//                                        +data[i].store_id+
//                                        +data[i].store_name+
//                                        '</label>'+
//                                        '</div>'+
//                                        '</td>';
//}
//					$('#stores_tr').html(rows);						
//					},
//					error: function(){
//						swal({
//                            type: 'error',
//                            title: 'Oops...',
//                            text: 'Something went wrong!',
//                            });
//					}
//				});
//			}

            //save
            $("#editForm").submit(function(e) {
                e.preventDefault();
			    var data = $('#editForm').serialize();
                $.ajax({
                        type: 'post',
                        url: "<?php echo base_url('register/updateUser'); ?>",
                        data: data,
                        async: false,
                        dataType:'json',  
                        success: function(response){
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'User Data Updated',
                                showConfirmButton: false,
                                timer: 1700
                                });
                                showAllUsers();
                        },
                             error: function (request, status, error){
                             alert(error)
                           //alert(request.responseText);
                        },
                    });
            });

            //Delete
			$('#tbodyID').on('click', '.cus-delete', function(){
                
                var check = confirm("Press OK to continue delete");
                if (check == true) {
                var id = $(this).attr('data');
                        $.ajax({
                                type: 'post',
                                url: "<?php echo base_url('register/DeleteUser'); ?>",
                                data:  {id: id},	
                                async: false,
                                dataType:'json',  
                                success: function(response){
                                    showAllUsers();
                                    alert("User Deleted");                            
                                },
                                error: function() {
                                    alert("There was an error. Try again please.......!");
                                }
                            });
                        }                 
            });

            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                buttons: ['copy', 'excel', 'pdf']
            });
            table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    } ); 
    $(document)
    
</script> 