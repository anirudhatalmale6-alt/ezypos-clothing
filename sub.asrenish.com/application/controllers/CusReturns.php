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
        $response = $this->Cusreturns_model->getInvoice();
        echo json_encode($response);
    }
    public function getSaleDetails(){
        $response = $this->Cusreturns_model->getSaleDetails();
        echo json_encode($response);
    }
    public function addReturn(){
        $response = $this->Cusreturns_model->addReturn();
        echo json_encode($response);
    }
    public function addReturnItems(){
        $response = $this->Cusreturns_model->addReturnItems();
        echo json_encode($response);
    }
}