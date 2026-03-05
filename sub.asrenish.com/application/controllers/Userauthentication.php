<?php
class Userauthentication extends CI_Controller {

    public function __construct(){
        parent::__construct();  
        if ( $this->session->userdata('username'))
                { 
                    redirect('/');
                }    
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Configs_model'); // Load Configs_model for additional functionality
    }
   
    public function index($page = 'login')
    {
        if ( ! file_exists(APPPATH.'views/auth/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        // Fetch company name from database
        $company_name = $this->Configs_model->fetch_config_value(7, 'name');

        $data['title'] = ucfirst($page); // Capitalize the first letter
        $data['company_name'] = $company_name; // Pass company name to view
        $this->load->view('auth/'.$page, $data);        
    }

    /*
    public function validate(){ //not in use
            $usr=$this->input->post('username');
            $pass=$this->input->post('password');            

            if($this->User_model->check_user_exist($usr,$pass))
            {
                $userID = $this->User_model->getUserID($usr);                    
                        
                $session_data = array(
                'username' => $usr,
                'userid' => $userID
                );
                $this->session->set_userdata($session_data);
                
                $this->session->set_flashdata('logsuccess', 'You are welcome');
                redirect(base_url());
            }
            else{
                $this->session->set_flashdata('loginfailmsg', 'Invalid Username or Password');
            redirect(base_url("login"));
            }        
    } */
    public function login_process()
    {
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));
        //  $password = $this->input->post('password');
        $data = array(
                'username' => $username,
                'password' => $password
                );
        $result = $this->User_model->check_login($data);
        if($result == true)
        {
            
            $user_info = $this->User_model->read_user_information($username);
            if($user_info !== false)
            {

                $useruser = $user_info[0]['user_name'];

                $itm = $user_info[0]['priv_item'];
                $cat=$user_info[0]['priv_category'];
                $cus=$user_info[0]['priv_customer'];
                $sup=$user_info[0]['priv_supplier'];
                $store=$user_info[0]['priv_store'];
                $staff=$user_info[0]['priv_staff'];
                $tax=$user_info[0]['priv_tax'];
                $expense_cat=$user_info[0]['priv_expense_cat'];
                $register=$user_info[0]['priv_register'];
                $grn=$user_info[0]['priv_grn'];
                $sales=$user_info[0]['priv_sales'];
                $expense=$user_info[0]['priv_expense'];
                $bank=$user_info[0]['priv_bank'];


                $l_allGrn=$user_info[0]['priv_l_allGrn'];
                $l_stock=$user_info[0]['priv_l_stock'];
                $l_stockSupplierWise=$user_info[0]['priv_l_stockSupplierWise'];
                $l_stockLog=$user_info[0]['priv_l_stockLog'];
                $l_cheque=$user_info[0]['priv_l_cheque'];
                $re_stock=$user_info[0]['priv_re_stock'];
                $re_stockLog=$user_info[0]['priv_re_stockLog'];
                $re_salesReport=$user_info[0]['priv_re_salesReport'];
                $re_monthlySalesReport=$user_info[0]['priv_re_monthlySalesReport'];
                $re_purchaseReport=$user_info[0]['priv_re_purchaseReport'];
                $re_expenseReport=$user_info[0]['priv_re_expenseReport'];
                $re_todaySummary=$user_info[0]['priv_re_todaySummary'];
                $re_profitLossReport=$user_info[0]['priv_re_profitLossReport'];
                $py_customerPayment=$user_info[0]['priv_py_customerPayment'];
                $py_supplierPayment=$user_info[0]['priv_py_supplierPayment'];


                $Master=$itm+$cat+$cus+$sup+$store+$staff+$tax+$expense_cat;
                $User=$register;

                $session_data = array(
                    'username'=>$username,
                    'useruser' => $useruser,
                    'userid'=>$user_info[0]['user_id'],
                    'userrole'=>$user_info[0]['user_role'],
                    'privitem'=>$itm,
                    'privcategory'=>$cat,
                    'privcustomer'=>$cus,
                    'privsupplier'=>$sup,
                    'privstore'=>$store,
                    'privstaff'=>$staff,
                    'privtax'=>$tax,
                    'privregister'=>$register,
                    'privMasters'=>$Master,
                    'privUsers'=>$User,
                    'privGrn'=>$grn,
                    'privSales'=>$sales,
                    'privExpense'=>$expense,
                    'privL_allGrn'=>$l_allGrn,
                    'privL_stock'=>$l_stock,
                    'privL_stockSupplierWise'=>$l_stockSupplierWise,
                    'privL_stockLog'=>$l_stockLog,
                    'privL_cheque'=>$l_cheque,
                    'privRe_stock'=>$re_stock,
                    'privRe_stockLog'=>$re_stockLog,
                    'privRe_salesReport'=>$re_salesReport,
                    'privRe_monthlySalesReport'=>$re_monthlySalesReport,
                    'privRe_purchaseReport'=>$re_purchaseReport,
                    'privRe_expenseReport'=>$re_expenseReport,
                    'privRe_todaySummary'=>$re_todaySummary,
                    'privRe_profitLossReport'=>$re_profitLossReport,
                    'privPy_customerPayment'=>$py_customerPayment,
                    'privPy_supplierPayment'=>$py_supplierPayment,
                    'priv_bank'=>$bank       
                );
                        
                $this->session->set_userdata($session_data);
                echo json_encode(true);     
                
            
            }
            else
            {
                echo json_encode(false);
            }
        }
        else
        {
            echo json_encode(false);
        }
    }

    //new Functions
    
    
}
