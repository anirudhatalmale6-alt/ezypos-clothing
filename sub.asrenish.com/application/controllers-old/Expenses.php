<?php
class Expenses extends CI_Controller {
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
               $this->load->model('Expenses_model');
               $this->load->model('ExpenCategories_model');
               $this->load->model('Configs_model'); 
               $this->load->model('Staffs_model'); 
               $this->load->model('Employeesalary_model');
        }

        public function addExpense($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/transactions/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data1 = array(                                  
                    'title' => ucfirst($page),
                    'config' => $this->configs_model->getConfigName()
                );
                $data2 = array(                                  
                        'staffs'=>$this->staffs_model->getAllStaffs(),
                        'expenCategories'=>$this->expenCategories_model->getExpenCategories()
                    );    
                $this->load->view('templates/header', $data1);
                $this->load->view('transactions/'.$page,$data2);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function addExpensePOST(){
                $empsalaryTblId;
                $staffid=$this->input->post('employee');
                $modeOfPaymnt=$this->input->post('paymentMode');
               
                $expenseTblid=$this->expenses_model->addExpensePOST();//insertedID
                $empsalaryTblId=$this->employeesalary_model->isThereRecordInEmpSalary(); //return false/id
                
                if($empsalaryTblId>0){
                        //already there is a record in employee salary table
                        // salary table will have 1 record for each month for a employee salary
                }
                else if($empsalaryTblId==false&&$staffid>0){
                        $empsalaryTblId=$this->employeesalary_model->addEmployeeSalary(); //retn: "insrtid" | if not slry "false" 
                }
                $res="";
                if($staffid>0){
                        $res=$this->employeesalary_model->addSalaryLog($empsalaryTblId,$expenseTblid);                              
                } 
                if($modeOfPaymnt==2){
                        $result1=$this->expenses_model->ExpenseCheque($expenseTblid);
                }
                
                if($res==true){
                        echo json_encode("Employee salary added");
                }              
                else if($expenseTblid>0){
                        echo json_encode("Expense added");
                }else{
                        return false;
                }
        }  
        public function showAllExpenses(){
                $response = $this->expenses_model->showAllExpenses();
                echo json_encode($response);
        }      
        public function EditExpense(){
                $id=$this->input->post('id');
                $count=0;
                $expenseCatgryID=$this->expenses_model->getExpenCategry($id);
                if($expenseCatgryID==1){

                        $salarylog=$this->employeesalary_model->salaryLog($id);//check number of paymnts for this month
                        $empsalry_tblid=$salarylog->emp_slry_log_empsalry_tblid;
                        $empid=$salarylog->emp_slry_log_empid;
                        $month=$salarylog->emp_slry_log_month;
                        $year=$salarylog->emp_slry_log_year;

                        $count=$this->employeesalary_model->NumberOfPayments($empsalry_tblid);  
                                          
                                $result=$this->employeesalary_model->EditSalaryExpense($id);
                                echo json_encode(array($count,$result)); // both return should look similar     
                }else{
                        $result=$this->expenses_model->EditExpense($id);
                        echo json_encode(array($count,$result)); // both return should look similar
                }
                
        }
        public function updateExpense(){
                $result = $this->expenses_model->updateExpense();
                echo json_encode($result);
        }
        public function DeleteExpense(){
                $result = $this->expenses_model->DeleteExpense();
                echo json_encode($result);
        }
        public function paidOfThisMonth(){
                $result = $this->employeesalary_model->paidOfThisMonth();
                echo json_encode($result);
        }
        public function SalaryOfThisMonth(){
                $result = $this->employeesalary_model->SalaryOfThisMonth();
                echo json_encode($result);
        }
        public function updateEmpSalary(){
                $allownces=$this->employeesalary_model->getEmpAllownces();   
                //$oldBscSalry=$allownces->empsalary_basicsalary;          
               // $oldFoods=$allownces->empsalary_food;
               // $oldTrvl=$allownces->empsalary_travel;
               // $oldOther=$allownces->empsalary_other;
               // $oldBonus=$allownces->empsalary_bonus;

                $data = array(
                        'oldBscSalry'=>$allownces->empsalary_basicsalary,
                        'oldFoods'=>$allownces->empsalary_food, 
                        'oldTrvl'=>$allownces->empsalary_travel,  
                        'oldOther'=>$allownces->empsalary_other,
                        'oldBonus'=>$allownces->empsalary_bonus
                    );
                $result=$this->employeesalary_model->updateEmpSalary($data);                
                echo json_encode($result);
        }
        public function getPreviousBonus(){
                $result = $this->employeesalary_model->getPreviousBonus();
                echo json_encode($result);
        }
        public function getOldDate(){
                $empsalry_tblid = $this->employeesalary_model->getOldDate();
                $result = $this->employeesalary_model->getPreviousDate($empsalry_tblid); //get previous payment date
                echo json_encode($result);
        }
        public function getexpenseChq(){
                $result = $this->expenses_model->getexpenseChq();
                echo json_encode($result);
        }
}       