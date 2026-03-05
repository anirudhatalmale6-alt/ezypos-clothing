<?php
class ExpenCategories extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
               // else if(!$this->session->userdata('privexpense_cat')==1){
                       // show_404();
               // }
               $this->load->model('ExpenCategories_model');
               $this->load->model('Configs_model'); 
        }

        public function addExpenseCategory($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/expense-categry/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data1['title'] = ucfirst($page); 
                $data1['config'] = $this->Configs_model->getConfigName(); 
             
        
                $this->load->view('templates/header', $data1);
                $this->load->view('expense-categry/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function addExpensePOST(){
            $response = $this->ExpenCategories_model->addExpensePOST();
            //return response()->json("Added");
            echo json_encode($response);
        }
        public function showAllExpenses(){
            $result =$this->ExpenCategories_model->showAllExpenses();		 
            echo json_encode($result);
        }

        public function EditExpenses(){
            $result = $this->ExpenCategories_model->EditExpenses();
            echo json_encode($result);
        }

        public function updateExpenses(){
            $result = $this->ExpenCategories_model->updateExpenses();
            echo json_encode($result);
        }
        public function DeleteExpenses(){
            $result = $this->ExpenCategories_model->DeleteExpenses();
            echo json_encode($result);
        }
        public function getExpenCategories(){
            $result = $this->ExpenCategories_model->getExpenCategories();
            echo json_encode($result);
        }
}