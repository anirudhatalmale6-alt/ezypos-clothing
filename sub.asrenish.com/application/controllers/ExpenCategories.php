<?php
class ExpenCategories extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                {
                    redirect('login');
                }
               $this->load->model('ExpenCategories_model');
               $this->load->model('Configs_model');
        }

        public function addExpenseCategory($page = 'index')
        {
                // Same permission as every other Masters page: admins always pass,
                // everyone else needs the Expense Categories tick box on their user.
                require_priv('privExpense_cat');
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
            require_priv('privExpense_cat');
            $response = $this->ExpenCategories_model->addExpensePOST();
            echo json_encode($response);
        }
        public function showAllExpenses(){
            require_priv('privExpense_cat');
            // Inactive categories are listed too, greyed out, so a category
            // that was switched off can be switched back on again.
            $result =$this->ExpenCategories_model->showAllExpenses(true);
            echo json_encode($result);
        }

        // Switch a category on or off. Categories are never deleted - old
        // expenses stay pointing at them and would otherwise lose their name.
        public function setStatus(){
            require_priv('privExpense_cat');
            $result = $this->ExpenCategories_model->setStatus();
            echo json_encode($result);
        }

        public function EditExpenses(){
            require_priv('privExpense_cat');
            $result = $this->ExpenCategories_model->EditExpenses();
            echo json_encode($result);
        }

        public function updateExpenses(){
            require_priv('privExpense_cat');
            $result = $this->ExpenCategories_model->updateExpenses();
            echo json_encode($result);
        }
        // Kept for anything still calling it. It only ever set the status to 0,
        // which is what Deactivate does now - nothing is really deleted.
        public function DeleteExpenses(){
            require_priv('privExpense_cat');
            $result = $this->ExpenCategories_model->DeleteExpenses();
            echo json_encode($result);
        }
        public function getExpenCategories(){
            $result = $this->ExpenCategories_model->getExpenCategories();
            echo json_encode($result);
        }
}