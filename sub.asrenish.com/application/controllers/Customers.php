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
                $data['config'] = $this->Configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('customer/'.$page, $data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addCustomerPOST(){
                $response = $this->Customers_model->addCustomerPOST();
                echo json_encode($response);
        }

        public function showAllCustomers(){
                $result =$this->Customers_model->getAllCustomers2();		 
		echo json_encode($result);
        }

        public function editCustomer(){
                $result =$this->Customers_model->editCustomer2();		 
		echo json_encode($result);
        }
        public function updateCustomer(){
                $result =$this->Customers_model->updatecustomer();		 
		echo json_encode($result);
        }
        public function DeleteCustomer(){
                $result =$this->Customers_model->DeleteCustomer();		 
		echo json_encode($result);
        }
        public function getCusDetails(){
                $result =$this->Customers_model->getCusDetails();
		echo json_encode($result);
        }
        public function quickAddCustomer(){
            $data = array(
                'cus_name' => $this->input->post('name'),
                'cus_address' => $this->input->post('address'),
                'cus_contact' => $this->input->post('contact'),
                'cus_balance' => 0,
                'cus_creditlimit' => $this->input->post('creditlimit') ?: 0,
                'cus_status' => 1
            );
            $this->db->insert('ezy_pos_customers', $data);
            $cus_id = $this->db->insert_id();
            if($cus_id){
                $this->db->insert('ezy_pos_cus_balnce', array('bal_cusid'=>$cus_id,'bal_amount'=>0));
            }
            echo json_encode($cus_id);
        }
        public function getBal() {
    header('Content-Type: application/json');
    $result = $this->Customers_model->getBal();

    if ($result) {
        echo json_encode(['bal_amount' => $result->bal_amount]);
    } else {
        echo json_encode(['bal_amount' => 0]);
    }
}



}