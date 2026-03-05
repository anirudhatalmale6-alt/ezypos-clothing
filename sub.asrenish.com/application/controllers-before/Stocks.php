<?php
class Stocks extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
              //  else if(!$this->session->userdata('privstock')==1){
              //          show_404();
               // }
                $this->load->model('Stocks_model');
                $this->load->model('Configs_model');
                $this->load->model('Items_model'); // Assuming you have an Items model
        }

     
       public function showStocks($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('listing/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        
       public function showStocks_list($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $this->load->model('Report_model');
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
                $data['all_products'] = $this->Report_model->load_all_products();
        
                $this->load->view('templates/header', $data);
                $this->load->view('listing/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addItemtoStock(){
                $response = $this->Stocks_model->addItemtoStock();
                echo json_encode($response);
        }

        public function increaseStock(){
                $result =$this->Stocks_model->increaseStock();		 
		echo json_encode($result);
        }
        public function decreaseStock(){
            $result =$this->Stocks_model->decreaseStock();		 
            echo json_encode($result);
        }
        public function stocklog(){
                $result =$this->Stocks_model->stocklog();		 
                echo json_encode($result);
        }
        public function showAllStock_list(){
                $result =$this->Stocks_model->showItemStock();		 
                echo json_encode($result); 
        }
        
        public function showAllStock(){
                $result =$this->Stocks_model->showAllStockWithWarehouses();
                echo json_encode($result); 
        }
        public function showAllSupplierStock($page = 'index'){
                if (!file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
                $data['all_suppliers'] = $this->Stocks_model->get_all_suppliers();
                
        
                $this->load->view('templates/header', $data);
                $this->load->view('listing/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getSupplierStock(){
                $result =$this->Stocks_model->getSupplierStock();		 
                echo json_encode($result); 
        }
        
        public function getSingleSupplierStock(){
                $sup_id=$this->uri->segment(3);
                $result =$this->Stocks_model->getSingleSupplierStock($sup_id);		 
                echo json_encode($result); 
        }
        

        public function showAllStocklog($page = 'index'){
                if ( ! file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('listing/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getStocklog(){
                $result =$this->Stocks_model->getStocklog();		 
                echo json_encode($result); 
        }
        
        public function getGRNReport(){
                $result =$this->Stocks_model->getGRNReport();		 
                echo json_encode($result);
        }
        
        public function getSaleReport(){
                $result =$this->Stocks_model->getSaleReport();		 
                echo json_encode($result);
        }
        public function getSupplierReturn(){
                $result =$this->Stocks_model->getSupplierReturn();		 
                echo json_encode($result);
        }
        public function getCustomerReturn(){
                $result =$this->Stocks_model->getCustomerReturn();		 
                echo json_encode($result);
        }
        public function filterStockLogs(){
                $result =$this->Stocks_model->filterStockLogs();		 
                echo json_encode($result);
        }
//----------------------------------------------------New----------------------------------------------------------



      // Function to fetch all item names for the dropdown
    public function fetchItemNames() {
        $items = $this->Items_model->getAllItems(); // Fetch all items
        echo json_encode($items);
    }

    // Function to fetch stock quantities for the selected item
    public function getItemStockQuantity() {
        $itemId = $this->input->post('item_id'); // Get item ID from POST request

        // Fetch stock quantity from the Items_model
        $quantity = $this->Items_model->getStockQuantityByItemId($itemId); // Get quantity using the new method
        echo json_encode(['quantity' => $quantity]);
    }
        
}