<?php
class Taxs extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
                else if(!$this->session->userdata('privtax')==1){
                        show_404();
                }
                $this->load->model('Taxs_model');
                $this->load->model('Configs_model');
        }

        public function addTaxGET($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/tax/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('tax/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addTaxPOST(){
                $response = $this->taxs_model->insertTax();
                //return response()->json("Added");
                echo json_encode($response);
        }

        public function showAllTaxs(){
                $result =$this->taxs_model->getAllTaxs();		 
		echo json_encode($result);
        }
        public function EditTax(){
                $result = $this->taxs_model->EditTax();
		echo json_encode($result);
        }

        public function updateTax(){
                $result = $this->taxs_model->updateTax();
		echo json_encode($result);
        }
        public function DeleteTax(){
                $result = $this->taxs_model->DeleteTax();
		echo json_encode($result);
        }
}