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
                    'config' => $this->Configs_model->getConfigName()
                );
                $data2 = array(                                  
                        'staffs'=>$this->Staffs_model->getAllStaffs(),
                        'expenCategories'=>$this->ExpenCategories_model->getExpenCategories()
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
               
                $expenseTblid=$this->Expenses_model->addExpensePOST();//insertedID
                $empsalaryTblId=$this->Employeesalary_model->isThereRecordInEmpSalary(); //return false/id
                
                if($empsalaryTblId>0){
                        //already there is a record in employee salary table
                        // salary table will have 1 record for each month for a employee salary
                }
                else if($empsalaryTblId==false&&$staffid>0){
                        $empsalaryTblId=$this->Employeesalary_model->addEmployeeSalary(); //retn: "insrtid" | if not slry "false" 
                }
                $res="";
                if($staffid>0){
                        $res=$this->Employeesalary_model->addSalaryLog($empsalaryTblId,$expenseTblid);                              
                } 
                if($modeOfPaymnt==2){
                        $result1=$this->Expenses_model->ExpenseCheque($expenseTblid);
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
                $response = $this->Expenses_model->showAllExpenses();
                echo json_encode($response);
        }      
        public function EditExpense(){
                $id=$this->input->post('id');
                $count=0;
                $expenseCatgryID=$this->Expenses_model->getExpenCategry($id);
                if($expenseCatgryID==1){

                        $salarylog=$this->Employeesalary_model->salaryLog($id);//check number of paymnts for this month
                        $empsalry_tblid=$salarylog->emp_slry_log_empsalry_tblid;
                        $empid=$salarylog->emp_slry_log_empid;
                        $month=$salarylog->emp_slry_log_month;
                        $year=$salarylog->emp_slry_log_year;

                        $count=$this->Employeesalary_model->NumberOfPayments($empsalry_tblid);  
                                          
                                $result=$this->Employeesalary_model->EditSalaryExpense($id);
                                echo json_encode(array($count,$result)); // both return should look similar     
                }else{
                        $result=$this->Expenses_model->EditExpense($id);
                        echo json_encode(array($count,$result)); // both return should look similar
                }
                
        }
        public function updateExpense(){
                $result = $this->Expenses_model->updateExpense();
                echo json_encode($result);
        }
        public function DeleteExpense(){
                $result = $this->Expenses_model->DeleteExpense();
                echo json_encode($result);
        }
        public function paidOfThisMonth(){
                $result = $this->Employeesalary_model->paidOfThisMonth();
                echo json_encode($result);
        }
        public function SalaryOfThisMonth(){
                $result = $this->Employeesalary_model->SalaryOfThisMonth();
                echo json_encode($result);
        }
        public function updateEmpSalary(){
                $allownces=$this->Employeesalary_model->getEmpAllownces();   
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
                $result=$this->Employeesalary_model->updateEmpSalary($data);                
                echo json_encode($result);
        }
        public function getPreviousBonus(){
                $result = $this->Employeesalary_model->getPreviousBonus();
                echo json_encode($result);
        }
        public function getOldDate(){
                $empsalry_tblid = $this->Employeesalary_model->getOldDate();
                $result = $this->Employeesalary_model->getPreviousDate($empsalry_tblid); //get previous payment date
                echo json_encode($result);
        }
        public function getexpenseChq(){
                $result = $this->Expenses_model->getexpenseChq();
                echo json_encode($result);
        }


/*------------------Today's Total Expences In Dash Board--------------------------*/ 
// File: application/controllers/Expenses.php
public function get_today_expenses() {
        $this->load->model('Expenses_model');
        $total_expenses = $this->Expenses_model->get_today_total_expenses();
        echo json_encode(['total_expenses' => $total_expenses]);
    }
    

    

}       