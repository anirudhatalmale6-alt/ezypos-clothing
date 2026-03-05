<?php
class Banks extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
               // else if(!$this->session->userdata('privexpense')==1){
                       // show_404();
               // }
               $this->load->model('Configs_model'); 
              // $this->load->model('bank_model'); 
               $this->load->model('Bank_model'); 
        }

        public function createbankGet($page = 'index')
        {
                if (!file_exists(APPPATH.'views/transactions/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data1 = array(                                  
                    'title'=>ucfirst($page),
                    'config'=>$this->Configs_model->getConfigName()
                );    
                $this->load->view('templates/header',$data1);
                $this->load->view('transactions/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }   
        public function createBankPost(){
          //  $response = $this->bank_model->createBankPost();
            $response = $this->Bank_model->createBankPost();
            echo json_encode($response);
        }
        public function showAllBanks(){
           // $response = $this->bank_model->showAllBanks();
            $response = $this->Bank_model->showAllBanks();
                echo json_encode($response);
        }
        public function EditBank(){
            //$result = $this->bank_model->EditBank();
            $result = $this->Bank_model->EditBank();
            echo json_encode($result);
        }
        public function updateBank(){
         //   $result = $this->bank_model->updateBank();
            $result = $this->Bank_model->updateBank();
            echo json_encode($result);
        }
        public function deleteBank(){
          //  $result = $this->bank_model->deleteBank();
            $result = $this->Bank_model->deleteBank();
            echo json_encode($result);
        }
        public function getAllBanks(){
           // $result = $this->bank_model->getAllBanks();
            $result = $this->Bank_model->getAllBanks();
            echo json_encode($result);
        }

}