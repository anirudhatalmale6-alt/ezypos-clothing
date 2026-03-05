<?php
class Stores extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
                else if(!$this->session->userdata('privstore')==1){
                        show_404();
                }
                $this->load->model('Stores_model');
                $this->load->model('Configs_model');
        }

        public function addStoreGET($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/store/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('store/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addStorePOST(){
                $response = $this->Stores_model->addStorePOST();
                //return response()->json("Added");
                echo json_encode($response);
        }
        public function showAllStores(){
                $result =$this->Stores_model->getAllStores();		 
		echo json_encode($result);
        }

        public function EditStore(){
                $result = $this->Stores_model->EditStore();
		echo json_encode($result);
        }

        public function updateStore(){
                $result = $this->Stores_model->updateStore();
		echo json_encode($result);
        }
        public function DeleteStore(){
                $result = $this->Stores_model->DeleteStore();
		echo json_encode($result);
        }
}