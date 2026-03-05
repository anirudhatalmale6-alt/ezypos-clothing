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
            $this->load->model('Supreturns_model');
    }
    public function getGrnItems(){
        $response = $this->supreturns_model->getGrnItems();
        echo json_encode($response);
    }
    public function getGrnDetails(){
        $response = $this->supreturns_model->getGrnDetails();
        echo json_encode($response);
    }
    public function addReturn(){
        $response = $this->supreturns_model->addReturn();
        echo json_encode($response);
    }
    public function addReturnItems(){
        $response = $this->supreturns_model->addReturnItems();
        echo json_encode($response);
    }
    
}