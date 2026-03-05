<?php
class Customers extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
                else if(!$this->session->userdata('privcustomer')==1){
                        show_404();
                }
                $this->load->model('Customers_model');
                $this->load->model('Configs_model'); 
        }

        public function addCustomerGET($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/customer/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('customer/'.$page, $data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addCustomerPOST(){
                $response = $this->customers_model->addCustomerPOST();
                echo json_encode($response);
        }

        public function showAllCustomers(){
                $result =$this->customers_model->getAllCustomers();		 
		echo json_encode($result);
        }

        public function EditCustomer(){
                $result =$this->customers_model->editCustomer();		 
		echo json_encode($result);
        }
        public function updateCustomer(){
                $result =$this->customers_model->updatecustomer();		 
		echo json_encode($result);
        }
        public function DeleteCustomer(){
                $result =$this->customers_model->DeleteCustomer();		 
		echo json_encode($result);
        }
        public function getCusDetails(){
                $result =$this->customers_model->getCusDetails();		 
		echo json_encode($result); 
        }
}