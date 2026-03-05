<?php
class Grns extends CI_Controller {
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
               $this->load->model('Suppliers_model');
               $this->load->model('Items_model');
               $this->load->model('Grns_model');
               $this->load->model('CusPayment_model');
               $this->load->model('Configs_model'); 
        }

        public function addGrnGET($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/transactions/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data1['title'] = ucfirst($page); 
                $data1['config'] = $this->configs_model->getConfigName(); 
                $data = array(                                   
                        'suppliers' => $this->suppliers_model->getSuppliers(),
                        'items' => $this->items_model->getItems()
                       // 'prtyChqs' => $this->Cuspayment_model->getPartyCheques()
                );
        
                $this->load->view('templates/header', $data1);
                $this->load->view('transactions/'.$page, $data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getPartyCheques(){
                $response = $this->cusPayment_model->getPartyCheques();
                echo json_encode($response);
        }
        public function addGrnPOST(){
                $response = $this->grns_model->insertGRN();
                //return response()->json("Added");
                echo json_encode($response);
        }        
        public function addGrnPOST2(){
                $response = $this->grns_model->addGrnPOST2();
                echo json_encode($response);
        }
        public function insertGrnItemPOST2(){
                $response = $this->grns_model->insertGrnItemPOST2();
                echo json_encode($response);
        }
        public function loadItems(){ //autocomplte purpose
                $response = $this->items_model->loadItems();
                echo json_encode($response);
        }
        public function showAllGRN($page){
                if ( ! file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data1['title'] = ucfirst($page); 
                $data1['config'] = $this->configs_model->getConfigName(); 
                $data = array(                                   
                        'suppliers' => $this->suppliers_model->getSuppliers()
                );
                $this->load->view('templates/header', $data1);
                $this->load->view('listing/'.$page,$data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function loadAllGrn(){
                $response = $this->grns_model->loadAllGrn();
                echo json_encode($response);
        }
        public function loadGrnItems(){
                $response = $this->grns_model->loadGrnItems();
                echo json_encode($response);
        }
        public function editGrn(){
                $response = $this->grns_model->editGrn();
                echo json_encode($response);  
        }
        public function updateGrn(){
                $response = $this->grns_model->updateGrn();
                echo json_encode($response);  
        }
        

}