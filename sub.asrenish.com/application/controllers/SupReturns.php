<?php
class SupReturns extends CI_Controller {
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
        // Switched off for this shop on the Super Admin page means the page is
        // not there, for anyone - typing the address included. The module's
        // code and its records are untouched; switch it on and it is back.
            require_feature('supplier_return');
            $this->load->model('Supreturns_model');
    }
    public function getGrnItems(){
        $response = $this->Supreturns_model->getGrnItems();
        echo json_encode($response);
    }
    public function getGrnDetails(){
        $response = $this->Supreturns_model->getGrnDetails();
        echo json_encode($response);
    }
    public function addReturn(){
        $response = $this->Supreturns_model->addReturn();
        echo json_encode($response);
    }
    public function addReturnItems(){
        $response = $this->Supreturns_model->addReturnItems();
        echo json_encode($response);
    }
    
}