<?php
class Sales extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
               // else if(!$this->session->userdata('privgrn')==1){
                       // show_404();
               // }
               $this->load->model('Customers_model');
               $this->load->model('Items_model');
               $this->load->model('Sales_model');
               $this->load->model('User_model');
               $this->load->model('CusPayment_model');
               $this->load->model('Stores_model');
               $this->load->model('Configs_model'); 
               $this->load->helper("language");        
        }

        public function addSaleGET($page = 'index')
        {
                if (! file_exists(APPPATH.'views/transactions/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $storeLoc="";
                if($_SESSION['userrole']==1)
                    {$storeLoc=$this->Stores_model->getAllStores();}
                else{
                     $storeLoc=$this->Stores_model->getAllStoresfornonadmin($_SESSION['userid']);
                }
                $data1['title'] = ucfirst($page);
                $data1['config'] = $this->Configs_model->getConfigName();
                $data = array(
                        'customers'=>$this->Customers_model->getCustomers(),
                        'items'=>$this->Items_model->getItems(),
                        'storeLoc'=> $storeLoc,
                        'paymentMethods'=>$this->Sales_model->getActivePaymentMethods()
                );
        
                $this->load->view('templates/header', $data1);
                $this->load->view('transactions/'.$page, $data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getItemPrice(){
                $response = $this->Sales_model->getPrice();
                echo json_encode($response);
        }
        
        public function addSalePOST(){
                $sale_id = $this->Sales_model->addSalePOST(); 
                echo json_encode($sale_id);
        }
        public function addSaleItemPOST(){
                $response = $this->Sales_model->addSaleItemPOST();
                echo json_encode($response);
        }
        public function updateSalesforCusReturn(){
                $response = $this->Sales_model->updateSalesforCusReturn();
                echo json_encode($response);
        }
        public function updateSaleItemsforCusReturn(){
                $response = $this->Sales_model->updateSaleItemsforCusReturn();
                echo json_encode($response);
        }
        
        public function print_inv($saleID){
                $userid;
                if(isset($_SESSION['userid'])){
                    $userid=$_SESSION['userid'];
                    }
                $data = array(
                        'comName'=> $this->Configs_model->getConfigName(),
                        'addLine1'=> $this->Configs_model->getConfigAdd1(),
                        'addLine2'=> $this->Configs_model->getConfigAdd2(),
                        'telephone'=> $this->Configs_model->getConfigTel(),
                        'mobile'=> $this->Configs_model->getConfigMob(),
                        'footer'=> $this->Configs_model->getConfigFoot(),
                        'user'=> $this->User_model->getUserName($userid),
                        'customer'=> $this->Sales_model->getCustomer($saleID),
                        'paymnt'=> $this->CusPayment_model->getPayment($saleID),
                        'saleitems'=> $this->Sales_model->invoicePreview2($saleID),
                        'sales'=> $this->Sales_model->saleDetails($saleID)    
                );
                $res2=$this->load->view('invoice/invoice',$data);
        }



/*------------------Today's Total Sales In Dash Board--------------------------*/ 

 // Method to fetch today's sales based on user role
 public function getTodaysSales() {
        $today = date('Y-m-d');  // Get today's date
        $userId = $this->session->userdata('userid');  // Logged-in user's ID
        $userRole = $this->session->userdata('userrole');  // Logged-in user's role (1 for Admin, 0 for User)

        // Fetch sales data based on the role
        if ($userRole == 1) {
            // Admin: Fetch all sales for today
            $sales = $this->Sales_model->getSalesByDate($today);
            $title = "Today's Total Sales";  // Title for Admin
        } else {
            // User: Fetch sales created by the logged-in user for today
            $sales = $this->Sales_model->getSalesByDateAndUser($today, $userId);
            $title = "Your Total Sales Today";  // Title for User
        }

        // Calculate the total sales for the day
        $total_sales = 0;
        foreach ($sales as $sale) {
            $total_sales += $sale->sale_grandtotal;
        }

        // Return the total sales and title as JSON
        echo json_encode(['total_sales' => $total_sales, 'title' => $title]);
    }

    // ===================== PAYMENT METHODS =====================

    public function getPaymentMethods() {
        echo json_encode($this->Sales_model->getAllPaymentMethods());
    }

    public function getActivePaymentMethods() {
        echo json_encode($this->Sales_model->getActivePaymentMethods());
    }

    public function addPaymentMethod() {
        $id = $this->Sales_model->addPaymentMethod();
        echo json_encode($id);
    }

    public function updatePaymentMethod() {
        $result = $this->Sales_model->updatePaymentMethod();
        echo json_encode($result);
    }

    public function deletePaymentMethod() {
        $id = $this->input->post('id');
        $result = $this->Sales_model->deletePaymentMethod($id);
        echo json_encode($result);
    }

    public function getPaymentMethodCommission() {
        $pm_id = $this->input->post('pm_id');
        $amount = $this->input->post('amount');
        $commission = $this->Sales_model->calculateCommission($pm_id, $amount);
        $method = $this->Sales_model->getPaymentMethodById($pm_id);
        echo json_encode(array(
            'commission' => $commission,
            'pct' => $method ? $method->pm_commission_pct : 0,
            'fixed' => $method ? $method->pm_commission_fixed : 0
        ));
    }

    public function saveSalePaymentMethod() {
        $sale_id = $this->input->post('sale_id');
        $pm_id = $this->input->post('pm_id');
        $amount = $this->input->post('amount');
        $commission = $this->Sales_model->calculateCommission($pm_id, $amount);
        $result = $this->Sales_model->updateSalePaymentMethod($sale_id, $pm_id, $commission);
        echo json_encode(array('result' => $result, 'commission' => $commission));
    }

    // Payment Methods Master Page
    public function paymentMethods() {
        $data1['title'] = 'Payment Methods';
        $data1['config'] = $this->Configs_model->getConfigName();
        $data = array(
            'methods' => $this->Sales_model->getAllPaymentMethods()
        );
        $this->load->view('templates/header', $data1);
        $this->load->view('payment/payment_methods', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }





}