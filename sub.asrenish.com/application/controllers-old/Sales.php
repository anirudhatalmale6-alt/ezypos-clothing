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
                    {$storeLoc=$this->stores_model->getAllStores();}
                else{
                     $storeLoc=$this->stores_model->getAllStoresfornonadmin($_SESSION['userid']);
                }
                $data1['title'] = ucfirst($page);
                $data1['config'] = $this->configs_model->getConfigName();
                $data = array(                                  
                        'customers'=>$this->customers_model->getCustomers(),
                        'items'=>$this->items_model->getItems(),
                        'storeLoc'=> $storeLoc
                );
        
                $this->load->view('templates/header', $data1);
                $this->load->view('transactions/'.$page, $data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getItemPrice(){
                $response = $this->sales_model->getPrice();
                echo json_encode($response);
        }
        
        public function addSalePOST(){
                $sale_id = $this->sales_model->addSalePOST(); 
                echo json_encode($sale_id);
        }
        public function addSaleItemPOST(){
                $response = $this->sales_model->addSaleItemPOST();
                echo json_encode($response);
        }
        public function updateSalesforCusReturn(){
                $response = $this->sales_model->updateSalesforCusReturn();
                echo json_encode($response);
        }
        public function updateSaleItemsforCusReturn(){
                $response = $this->sales_model->updateSaleItemsforCusReturn();
                echo json_encode($response);
        }
        public function print_inv($saleID){
                $userid;
                if(isset($_SESSION['userid'])){
                        $userid=$_SESSION['userid'];
                    }
                $data = array(
                        'comName'=> $this->configs_model->getConfigName(),
                        'addLine1'=> $this->configs_model->getConfigAdd1(),
                        'addLine2'=> $this->configs_model->getConfigAdd2(),
                        'telephone'=> $this->configs_model->getConfigTel(),
                        'mobile'=> $this->configs_model->getConfigMob(),
                        'footer'=> $this->configs_model->getConfigFoot(),
                        'user'=> $this->user_model->getUserName($userid),
                        'customer'=> $this->sales_model->getCustomer($saleID),
                        'paymnt'=> $this->cusPayment_model->getPayment($saleID),
                        'saleitems'=> $this->sales_model->invoicePreview2($saleID),
                        'sales'=> $this->sales_model->saleDetails($saleID)    
                );
                $res2=$this->load->view('invoice/invoice',$data);
        }

}