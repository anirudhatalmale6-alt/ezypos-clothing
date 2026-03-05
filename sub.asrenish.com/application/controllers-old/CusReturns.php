<?php
class CusReturns extends CI_Controller {
    public function __construct()
    {
            parent::__construct();
            if ( ! $this->session->userdata('username'))
            { 
                redirect('login');
            }
            // else if(!$this->session->userdata('privprofit')==1){
            //         show_404();
            // }
            $this->load->model('Cusreturns_model');
    }
    public function getInvoice(){
        $response = $this->cusreturns_model->getInvoice();
        echo json_encode($response);
    }
    public function getSaleDetails(){
        $response = $this->cusreturns_model->getSaleDetails();
        echo json_encode($response);
    }
    public function addReturn(){
        $response = $this->cusreturns_model->addReturn();
        echo json_encode($response); 
    }
    public function addReturnItems(){
        $response = $this->cusreturns_model->addReturnItems();
        echo json_encode($response);
    }
}