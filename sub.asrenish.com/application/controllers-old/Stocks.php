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
        }

     
       public function showStocks($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('listing/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addItemtoStock(){
                $response = $this->stocks_model->addItemtoStock();
                echo json_encode($response);
        }

        public function increaseStock(){
                $result =$this->stocks_model->increaseStock();		 
		echo json_encode($result);
        }
        public function decreaseStock(){
            $result =$this->stocks_model->decreaseStock();		 
            echo json_encode($result);
        }
        public function stocklog(){
                $result =$this->stocks_model->stocklog();		 
                echo json_encode($result);
        }
        public function showAllStock(){
                $result =$this->stocks_model->showAllStock();		 
                echo json_encode($result); 
        }
        public function showAllSupplierStock($page = 'index'){
                if ( ! file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('listing/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getSupplierStock(){
                $result =$this->stocks_model->getSupplierStock();		 
                echo json_encode($result); 
        }
        public function showAllStocklog($page = 'index'){
                if ( ! file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('listing/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getStocklog(){
                $result =$this->stocks_model->getStocklog();		 
                echo json_encode($result); 
        }
        
        public function getGRNReport(){
                $result =$this->stocks_model->getGRNReport();		 
                echo json_encode($result);
        }
        
        public function getSaleReport(){
                $result =$this->stocks_model->getSaleReport();		 
                echo json_encode($result);
        }
        public function getSupplierReturn(){
                $result =$this->stocks_model->getSupplierReturn();		 
                echo json_encode($result);
        }
        public function getCustomerReturn(){
                $result =$this->stocks_model->getCustomerReturn();		 
                echo json_encode($result);
        }
        public function filterStockLogs(){
                $result =$this->stocks_model->filterStockLogs();		 
                echo json_encode($result);
        }


}