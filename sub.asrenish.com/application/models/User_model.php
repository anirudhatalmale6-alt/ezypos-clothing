<?php
class User_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function check_user_exist($usr,$pass){ //not in use
        $this->db->where("user_username",$usr);
        $this->db->where("user_password",$pass);
        $query=$this->db->get("ezy_pos_users");
        if($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }        
    }
    public function getUserID($usr){ //not in use
                $this->db->select('user_id');																		
		$this->db->where('user_username', $usr);
		return $this->db->get('ezy_pos_users')->row('user_id');
    }
    
    public function check_login($data)
	{
		//$query = $this->db->query("select * from ezy_pos_users where user_username = '".$data['username']."' AND user_status =1");
		$query = $this->db->query("select * from ezy_pos_users where user_username = '".$data['username']."'AND user_password = '".$data['password']."' AND user_status =1");
		if($query->num_rows() == 1)
		{
                    return true;
//			$result = $query->result_array();
//			$pwd = $result[0]['user_password'];  
//			if($pwd == $data['password'])
//			{
//				return true;
//			}
//			else
//			{
//				return false;
//			}
		}
		else
		{
			return false;
		}

    }
    
    public function read_user_information($username) //should edited for added pages session
	{
        $str ="SELECT user_name,user_id,user_role,priv_item,priv_category,priv_customer,priv_supplier,priv_store,priv_staff,priv_tax,priv_register,priv_expense_cat,priv_grn,priv_sales,priv_expense,
        priv_l_allGrn,priv_l_stock,priv_l_stockSupplierWise,priv_l_stockLog,priv_l_cheque,priv_re_stock,priv_re_stockLog,priv_re_salesReport,priv_re_monthlySalesReport,
        priv_re_purchaseReport,priv_re_expenseReport,priv_re_todaySummary,priv_re_profitLossReport,priv_py_customerPayment,priv_py_supplierPayment,priv_bank
                FROM ezy_pos_users
                INNER JOIN ezy_pos_privileges ON ezy_pos_users.user_id = ezy_pos_privileges.priv_userid
                where user_username = '".$username."'";

		$query = $this->db->query($str);
		if($query->num_rows() == 1)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
        
        
    public function addUser(){
        $role =0;
        $user_role = $this->input->post('admincheck');
        if($user_role ==1){
            $role = 1;
        }
        $data = array(
            'user_username'=>$this->input->post('username'),            
            'user_name'=>$this->input->post('name'),
            'user_password'=>md5($this->input->post('password')),
            'user_role'=>$role
        );
        $this->db->insert('ezy_pos_users', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function addPrivileges($userid){
        $item =0 ;
        $category =0 ;
        $customer =0 ;
        $supplier =0 ;
        $tax =0 ;
        $store =0 ;
        $staff =0 ;
        $grn =0 ;
        $sales =0 ;
        $expense =0 ;
        $bank =0 ;

        $l_allGrn =0 ;
        $l_stock =0 ;
        $l_stockSupplierWise =0 ;
        $l_stockLog =0 ;
        $l_cheque =0 ;
        $re_stock =0 ;
        $re_stockLog =0 ;
        $re_salesReport =0 ;
        $re_monthlySalesReport =0 ;
        $re_purchaseReport =0 ;
        $re_expenseReport =0 ;
        $re_todaySummary =0 ;
        $re_profitLossReport =0 ;
        $py_customerPayment =0 ;
        $py_supplierPayment =0 ;


        
        if(isset($_POST['item'])){ $item = $this->input->post('item');} 
        if(isset($_POST['category'])){ $category = $this->input->post('category');} 
        if(isset($_POST['customer'])){ $customer = $this->input->post('customer');} 
        if(isset($_POST['supplier'])){ $supplier =  $this->input->post('supplier');} 
        if(isset($_POST['tax'])){ $tax = $this->input->post('tax');} 
        if(isset($_POST['store'])){  $store =  $this->input->post('store');} 
        if(isset($_POST['staff'])){ $staff = $this->input->post('staff');} 
        if(isset($_POST['grn'])){ $grn = $this->input->post('grn');} 
        if(isset($_POST['sales'])){ $sales = $this->input->post('sales');} 
        if(isset($_POST['expense'])){ $expense = $this->input->post('expense');}
        if(isset($_POST['bank'])){ $bank = $this->input->post('bank');}


        if(isset($_POST['l_allGrn'])){ $l_allGrn = $this->input->post('l_allGrn');}
        if(isset($_POST['l_stock'])){ $l_stock = $this->input->post('l_stock');}
        if(isset($_POST['l_stockSupplierWise'])){ $l_stockSupplierWise = $this->input->post('l_stockSupplierWise');}
        if(isset($_POST['l_stockLog'])){ $l_stockLog = $this->input->post('l_stockLog');}
        if(isset($_POST['l_cheque'])){ $l_cheque = $this->input->post('l_cheque');}
        if(isset($_POST['re_stock'])){ $re_stock = $this->input->post('re_stock');}
        if(isset($_POST['re_stockLog'])){ $re_stockLog = $this->input->post('re_stockLog');}
        if(isset($_POST['re_salesReport'])){ $re_salesReport = $this->input->post('re_salesReport');}
        if(isset($_POST['re_monthlySalesReport'])){ $re_monthlySalesReport = $this->input->post('re_monthlySalesReport');}
        if(isset($_POST['re_purchaseReport'])){ $re_purchaseReport = $this->input->post('re_purchaseReport');}
        if(isset($_POST['re_expenseReport'])){ $re_expenseReport = $this->input->post('re_expenseReport');}
        if(isset($_POST['re_todaySummary'])){ $re_todaySummary = $this->input->post('re_todaySummary');}
        if(isset($_POST['re_profitLossReport'])){ $re_profitLossReport = $this->input->post('re_profitLossReport');}
        if(isset($_POST['py_customerPayment'])){ $py_customerPayment = $this->input->post('py_customerPayment');}
        if(isset($_POST['py_supplierPayment'])){ $py_supplierPayment = $this->input->post('py_supplierPayment');}

        $data = array(
            'priv_userid' => $userid,            
            'priv_item' => $item,
            'priv_category' => $category,            
            'priv_customer' => $customer,
            'priv_supplier' => $supplier,
            'priv_tax' => $tax,
            'priv_store' => $store,
            'priv_staff' => $staff, 
            'priv_grn' => $grn,            
            'priv_sales' => $sales,
            'priv_expense' => $expense,
            'priv_l_allGrn' => $l_allGrn,
            'priv_l_stock' => $l_stock,
            'priv_l_stockSupplierWise' => $l_stockSupplierWise,
            'priv_l_stockLog' => $l_stockLog,
            'priv_l_cheque' => $l_cheque,
            'priv_re_stock' => $re_stock,
            'priv_re_stockLog' => $re_stockLog,
            'priv_re_salesReport' => $re_salesReport,
            'priv_re_monthlySalesReport' => $re_monthlySalesReport,
            'priv_re_purchaseReport' => $re_purchaseReport,
            'priv_re_expenseReport' => $re_expenseReport,
            'priv_re_todaySummary' => $re_todaySummary,
            'priv_re_profitLossReport' => $re_profitLossReport,
            'priv_py_customerPayment' => $py_customerPayment,
            'priv_py_supplierPayment' => $py_supplierPayment,
            'priv_bank' => $bank
             );

        
        return $this->db->insert('ezy_pos_privileges', $data);
        
    }
    public function getAllUsers(){
        $this->db->order_by('user_id', 'desc');
        $this->db->where('user_status', 1);
        $query = $this->db->get('ezy_pos_users');
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    public function getAllStores(){
        $str1='SELECT store_id,store_name FROM `ezy_pos_stores` WHERE `store_status`="1"';
        $query = $this->db->query($str1);
        if($query->num_rows()>0){
         return $query->result_array();
         //return $query;
        }
        else{
            return false;
        }
    }
    
    public function getAllStores_for_update(){
        $str1='SELECT store_id,store_name FROM `ezy_pos_stores` ';
        $query = $this->db->query($str1);
        if($query->num_rows()>0){
         return $query->result_array();
         //return $query;
        }
        else{
            return false;
        }
    }
    public function addUserStores($userid,$store_selected){
       $data = array(
            'user_id' => $userid,            
            'store_id' => $store_selected 
                    );
        return $this->db->insert('ezy_pos_user_store', $data);     
    }
    public function editUsers($id){ //should edit for more pages

        $str2 ="SELECT user_id,user_username,user_name,user_password,user_role,priv_item,priv_category,priv_customer,priv_supplier,priv_store,priv_staff,priv_tax,priv_grn,priv_sales,priv_expense,
        priv_l_allGrn,priv_l_stock,priv_l_stockSupplierWise,priv_l_stockLog,priv_l_cheque,priv_re_stock,priv_re_stockLog,priv_re_salesReport,priv_re_monthlySalesReport,
        priv_re_purchaseReport,priv_re_expenseReport,priv_re_todaySummary,priv_re_profitLossReport,priv_py_customerPayment,priv_py_supplierPayment,priv_bank
        FROM ezy_pos_users
        INNER JOIN ezy_pos_privileges ON ezy_pos_users.user_id = ezy_pos_privileges.priv_userid
        where user_id = '".$id."'";

        $query = $this->db->query($str2);
        if($query->num_rows() == 1)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
    }
    
    public function editUsers_stores($id){ //should edit for more pages
         $this->db->select('ezy_pos_stores.store_id,ezy_pos_stores.store_name');
         $this->db->from('ezy_pos_stores');
         $this->db->join('ezy_pos_user_store','ezy_pos_stores.store_id = ezy_pos_user_store.store_id');
         $this->db->where('ezy_pos_user_store.user_id',$id);
         $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        else
        {
           // return array("store_id"=>0,"store_name"=>0);
            return array();
        }
    }


    
    public function updateUser(){ //woring on here 
        $user_id = $this->input->post('hiddenID');

        $role=0;
        $user_role = $this->input->post('E_admincheck');
        if($user_role ==1){
            $role = 1;
        }
        $updateDate = array(
            'user_username' => $this->input->post('E_username'),            
            'user_name' => $this->input->post('E_name'),
            'user_password' => $this->input->post('E_password'),
            'user_role' => $role
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('ezy_pos_users', $updateDate);
    }
    
    
    public function deleteUserStores($userid){ //woring on here 
          $this -> db -> where('user_id', $userid);
          $this -> db -> delete('ezy_pos_user_store');
    }

    public function updatePrivileges(){
        $user_id = $this->input->post('hiddenID');
        $item =0 ;
        $category =0 ;
        $customer =0 ;
        $supplier =0 ;
        $tax =0 ;
        $store =0 ;
        $staff =0 ;
        $grn =0 ;
        $sales =0 ;
        $expense =0 ;
        $bank =0 ;


        $l_allGrn =0 ;
        $l_stock =0 ;
        $l_stockSupplierWise =0 ;
        $l_stockLog =0 ;
        $l_cheque =0 ;
        $re_stock =0 ;
        $re_stockLog =0 ;
        $re_salesReport =0 ;
        $re_monthlySalesReport =0 ;
        $re_purchaseReport =0 ;
        $re_expenseReport =0 ;
        $re_todaySummary =0 ;
        $re_profitLossReport =0 ;
        $py_customerPayment =0 ;
        $py_supplierPayment =0 ;
                
        if(isset($_POST['E_item'])){ $item = $this->input->post('E_item');} 
        if(isset($_POST['E_category'])){ $category = $this->input->post('E_category');} 
        if(isset($_POST['E_customer'])){ $customer = $this->input->post('E_customer');} 
        if(isset($_POST['E_supplier'])){ $supplier =  $this->input->post('E_supplier');} 
        if(isset($_POST['E_tax'])){ $tax = $this->input->post('E_tax');} 
        if(isset($_POST['E_store'])){  $store =  $this->input->post('E_store');} 
        if(isset($_POST['E_staff'])){ $staff = $this->input->post('E_staff');}
        if(isset($_POST['E_bank'])){ $bank = $this->input->post('E_bank');}

        if(isset($_POST['E_grn'])){ $grn = $this->input->post('E_grn');} 
        if(isset($_POST['E_sales'])){ $sales = $this->input->post('E_sales');} 
        if(isset($_POST['E_expense'])){ $expense = $this->input->post('E_expense');} 

        if(isset($_POST['E_l_allGrn'])){ $l_allGrn = $this->input->post('E_l_allGrn');}
        if(isset($_POST['E_l_stock'])){ $l_stock = $this->input->post('E_l_stock');}
        if(isset($_POST['E_l_stockSupplierWise'])){ $l_stockSupplierWise = $this->input->post('E_l_stockSupplierWise');}
        if(isset($_POST['E_l_stockLog'])){ $l_stockLog = $this->input->post('E_l_stockLog');}
        if(isset($_POST['E_l_cheque'])){ $l_cheque = $this->input->post('E_l_cheque');}
        if(isset($_POST['E_re_stock'])){ $re_stock = $this->input->post('E_re_stock');}
        if(isset($_POST['E_re_stockLog'])){ $re_stockLog = $this->input->post('E_re_stockLog');}
        if(isset($_POST['E_re_salesReport'])){ $re_salesReport = $this->input->post('E_re_salesReport');}
        if(isset($_POST['E_re_monthlySalesReport'])){ $re_monthlySalesReport = $this->input->post('E_re_monthlySalesReport');}
        if(isset($_POST['E_re_purchaseReport'])){ $re_purchaseReport = $this->input->post('E_re_purchaseReport');}
        if(isset($_POST['E_re_expenseReport'])){ $re_expenseReport = $this->input->post('E_re_expenseReport');}
        if(isset($_POST['E_re_todaySummary'])){ $re_todaySummary = $this->input->post('E_re_todaySummary');}
        if(isset($_POST['E_re_profitLossReport'])){ $re_profitLossReport = $this->input->post('E_re_profitLossReport');}
        if(isset($_POST['E_py_customerPayment'])){ $py_customerPayment = $this->input->post('E_py_customerPayment');}
        if(isset($_POST['E_py_supplierPayment'])){ $py_supplierPayment = $this->input->post('E_py_supplierPayment');}

        
        $updateData = array(            
            'priv_item' => $item,
            'priv_category' => $category,            
            'priv_customer' => $customer,
            'priv_supplier' => $supplier,
            'priv_tax' => $tax,
            'priv_store' => $store,
            'priv_staff' => $staff,
            'priv_grn' => $grn,            
            'priv_sales' => $sales,
            'priv_expense' => $expense,
            'priv_l_allGrn' => $l_allGrn,
            'priv_l_stock' => $l_stock,
            'priv_l_stockSupplierWise' => $l_stockSupplierWise,
            'priv_l_stockLog' => $l_stockLog,
            'priv_l_cheque' => $l_cheque,
            'priv_re_stock' => $re_stock,
            'priv_re_stockLog' => $re_stockLog,
            'priv_re_salesReport' => $re_salesReport,
            'priv_re_monthlySalesReport' => $re_monthlySalesReport,
            'priv_re_purchaseReport' => $re_purchaseReport,
            'priv_re_expenseReport' => $re_expenseReport,
            'priv_re_todaySummary' => $re_todaySummary,
            'priv_re_profitLossReport' => $re_profitLossReport,
            'priv_py_customerPayment' => $py_customerPayment,
            'priv_py_supplierPayment' => $py_supplierPayment,
            'priv_bank'=>$bank
        );
        // $updateData = array(            
        //     'priv_item' => $item,
        //     'priv_category' => $category,            
        //     'priv_customer' => $customer,
        //     'priv_supplier' => $supplier,
        //     'priv_tax' => $tax,
        //     'priv_store' => $store,
        //     'priv_staff' => $staff,
        //     'priv_grn' => $grn,            
        //     'priv_sales' => $sales,
        //     'priv_expense' => $expense
        // );

        $this->db->where('priv_userid', $user_id);
        $this->db->update('ezy_pos_privileges', $updateData);

    }
    public function DeleteUser(){
        $id = $this->input->post('id');
        //
        $updateData = array(
            'user_store_status' => 0
        );
        $this->db->where('user_id', $id);
        $this->db->update('ezy_pos_user_store', $updateData);
        
        $updateData = array(
            'user_status' => 0
        );
        $this->db->where('user_id', $id);
        $this->db->update('ezy_pos_users', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getUserName($id){
        $this->db->select('user_name');
        $this->db->from('ezy_pos_users');
        $this->db->where('user_id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row()->user_name;
        }
        else{
            return false;
        }
    }

    
}
