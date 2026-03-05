<?php
class Suppliers extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
                else if(!$this->session->userdata('privsupplier')==1){
                        show_404();
                }
                $this->load->model('Suppliers_model');
                $this->load->model('Configs_model');
        }

        public function addSupplierGET($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/supplier/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('supplier/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addSupplierPOST(){
                $response = $this->Suppliers_model->insertSupplier();
                //return response()->json("Added");
                echo json_encode($response);
        }

        public function showAllSuppliers(){
                $result =$this->Suppliers_model->getAllSuppliers();		 
		echo json_encode($result);
        }

        public function EditSupplier(){
                $result = $this->Suppliers_model->editSupplier();
		echo json_encode($result);
        }
        public function updateSupplier(){
                $result = $this->Suppliers_model->updateSupplier();
		echo json_encode($result);
        }
        public function DeleteSupplier(){
                $result = $this->Suppliers_model->DeleteSupplier();
		echo json_encode($result);
        }
        public function getSupDetails(){
                $result =$this->Suppliers_model->getSupDetails();		 
		echo json_encode($result); 
        }
}