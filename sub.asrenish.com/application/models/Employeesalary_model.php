<?php
class Employeesalary_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function addEmployeeSalary(){
        $staffid=$this->input->post('employee');
        if($staffid>0){
            if(isset($_SESSION['userid'])){
                $userid=$_SESSION['userid'];
            }
            $otperhr=$this->input->post('otperhour');
            $hrs=$this->input->post('othours');
            $otcash=$otperhr*$hrs;
            $data = array(
                'empsalary_staffid'=>$staffid,
                'empsalary_month'=>$this->input->post('month'), 
                'empsalary_year'=>$this->input->post('year'),  
                'empsalary_balance'=>0,
                'empsalary_basicsalary'=>$this->input->post('basicSalary'),
                'empsalary_food'=>$this->input->post('foodAllownc'),
                'empsalary_travel'=>$this->input->post('travelAllowce'),
                'empsalary_other'=>$this->input->post('otherAllownc'),
                'empsalary_ot'=>$otcash,
                'empsalary_bonus'=>$this->input->post('bonus'),
                'empsalary_total'=>$this->input->post('total'),
                'empsalary_createdby'=>$userid,
                'empsalary_status'=>1
            );        

            $this->db->insert('ezy_pos_employe_salary', $data);
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }
        else{
            return false;
        }
    }
    public function isThereRecordInEmpSalary(){
        $staffid=$this->input->post('employee');
        $month=$this->input->post('month'); 
        $year=$this->input->post('year');
        $this->db->select('empsalary_id');
        $this->db->from('ezy_pos_employe_salary');
        $this->db->where('empsalary_staffid',$staffid);
        $this->db->where('empsalary_month',$month);
        $this->db->where('empsalary_year',$year);
        $this->db->where('empsalary_status',1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row()->empsalary_id;
        }
        else{
            return false;
        }
    }
    public function addSalaryLog($empsalaryTblId,$expenseTblid){ 
        $staffid=$this->input->post('employee');
        $data = array(
            'emp_slry_log_expense_tblid'=>$expenseTblid,
            'emp_slry_log_empsalry_tblid'=>$empsalaryTblId,
            'emp_slry_log_empid'=>$staffid,
            'emp_slry_log_month'=>$this->input->post('month'), 
            'emp_slry_log_year'=>$this->input->post('year'),  
            'emp_slry_log_amount'=>$this->input->post('amount'), 
            //'emp_slry_log_totalsalary'=>$this->input->post('total'), //removed from db
            'emp_slry_log_balanceofmonth'=>0,
            'emp_slry_log_status'=>1
        ); 
        return $this->db->insert('ezy_pos_employe_salary_log', $data);
    }
    public function paidOfThisMonth(){
        $month=$this->input->post('month');
        $year=$this->input->post('year');
        $empid=$this->input->post('empid');
        $str="SELECT sum(emp_slry_log_amount) as paid FROM ezy_pos_employe_salary_log
        WHERE emp_slry_log_empid='".$empid."'
        AND emp_slry_log_month='".$month."'
        AND emp_slry_log_year='".$year."'
        AND emp_slry_log_status=1";
        $query = $this->db->query($str);
        if($query->num_rows() == 1){
            return $query->row()->paid;
        }
        else{
            return false;
        }
    }
    public function SalaryOfThisMonth(){
        $month=$this->input->post('month');
        $year=$this->input->post('year');
        $empid=$this->input->post('empid');
        $this->db->select('empsalary_total');
        $this->db->from(' ezy_pos_employe_salary');
        $this->db->where('empsalary_staffid',$empid);
        $this->db->where('empsalary_month',$month);
        $this->db->where('empsalary_year',$year);
        $this->db->where('empsalary_status',1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row()->empsalary_total;
        }
        else{
            return false;
        }
    }
    public function EditSalaryExpense($id){
        $str ="SELECT expen_id,expen_expencat_id,expen_description,expen_amount,expen_date,
        emp_slry_log_empid,emp_slry_log_month,emp_slry_log_year,emp_slry_log_amount,
        empsalary_basicsalary,empsalary_food,empsalary_travel,empsalary_other,empsalary_bonus,empsalary_total
        FROM ezy_pos_expense
        RIGHT JOIN ezy_pos_employe_salary_log ON ezy_pos_employe_salary_log.emp_slry_log_expense_tblid = ezy_pos_expense.expen_id
        LEFT JOIN ezy_pos_employe_salary ON ezy_pos_employe_salary.empsalary_id=ezy_pos_employe_salary_log.emp_slry_log_empsalry_tblid
        WHERE expen_id ='".$id."'
        AND expen_expencat_id=1";   
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function getEmpAllownces(){
        $expen_id = $this->input->post('hiddenSalaryID');
        $str ="SELECT empsalary_basicsalary,empsalary_food,empsalary_travel,empsalary_other,empsalary_bonus
        FROM ezy_pos_expense
        RIGHT JOIN ezy_pos_employe_salary_log ON ezy_pos_employe_salary_log.emp_slry_log_expense_tblid = ezy_pos_expense.expen_id
        LEFT JOIN ezy_pos_employe_salary ON ezy_pos_employe_salary.empsalary_id=ezy_pos_employe_salary_log.emp_slry_log_empsalry_tblid
        WHERE expen_id ='".$expen_id."'
        AND expen_expencat_id=1";   
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        } 
    }
    public function updateEmpSalary($allwnces){ 
        $empid=$this->input->post('editSlry_emp');
        $month=$this->input->post('editSalry_month');
        $year=$this->input->post('editSalry_year');
        $amount=$this->input->post('editSlry_amount');

        $oldBscSalry=$allwnces['oldBscSalry'];          
        $oldFoods=$allwnces['oldFoods'];
        $oldTrvl=$allwnces['oldTrvl'];
        $oldOther=$allwnces['oldOther'];
        $oldBonus=$allwnces['oldBonus'];
        $bscSalary=$this->input->post('editSlry_BscSalay'); 
        $food=$this->input->post('editSlry_food');
        $travel=$this->input->post('editSlry_travel');
        $other=$this->input->post('editSlry_other');
        $bonus=$this->input->post('editSlry_bonus');
        $expen_id = $this->input->post('hiddenSalaryID'); //expense_id

        $dif=$bscSalary-$oldBscSalry+$food-$oldFoods+$travel-$oldTrvl+$other-$oldOther+$bonus-$oldBonus;

        $updateData = array(
            'expen_description'=>$this->input->post('editSalry_descrip'),
            'expen_amount'=>$amount,
            'expen_date'=>$this->input->post('editSlry_date')
        );
        $this->db->where('expen_id', $expen_id);
        $this->db->update('ezy_pos_expense', $updateData);
 
        $updateData2 = array(
            'emp_slry_log_empid'=>$empid,
            'emp_slry_log_month'=>$month,
            'emp_slry_log_year'=>$year,
            'emp_slry_log_amount'=>$amount
        );
        $this->db->where('emp_slry_log_expense_tblid', $expen_id);
        $this->db->update('ezy_pos_employe_salary_log', $updateData2);

        $str="UPDATE ezy_pos_expense 
        LEFT JOIN ezy_pos_employe_salary_log ON ezy_pos_employe_salary_log.emp_slry_log_expense_tblid=ezy_pos_expense.expen_id
        LEFT JOIN ezy_pos_employe_salary ON ezy_pos_employe_salary.empsalary_id=ezy_pos_employe_salary_log.emp_slry_log_empsalry_tblid
        SET empsalary_staffid= '".$empid."',
        empsalary_month= '".$month."',
        empsalary_year= '".$year."',
        empsalary_basicsalary= '".$bscSalary."',
        empsalary_food= '".$food."',
        empsalary_travel= '".$travel."',
        empsalary_other= '".$other."',
        empsalary_bonus= '".$bonus."',
        empsalary_total=empsalary_total+'".$dif."'       
        WHERE expen_expencat_id=1
        AND expen_id='".$expen_id."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function salaryLog($expenid){
        $str ="SELECT emp_slry_log_empsalry_tblid,emp_slry_log_empid,emp_slry_log_month,emp_slry_log_year
        FROM ezy_pos_employe_salary_log
        WHERE emp_slry_log_expense_tblid ='".$expenid."'";   
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function NumberOfPayments($empsalry_tblid){
        $str ="SELECT emp_slry_log_expense_tblid
        FROM ezy_pos_employe_salary_log
        WHERE emp_slry_log_empsalry_tblid ='".$empsalry_tblid."'";   
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->num_rows();
        }
        else{
            return false;
        }
    }
    public function getPreviousBonus(){
        $empid=$this->input->post('emp');
        $month=$this->input->post('month');
        $year=$this->input->post('year');
        $str ="SELECT empsalary_basicsalary,empsalary_food,empsalary_travel,empsalary_other,empsalary_bonus,empsalary_total
        FROM ezy_pos_employe_salary
        WHERE empsalary_staffid ='".$empid."'
        AND empsalary_month ='".$month."'
        AND empsalary_year ='".$year."'";   
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function getOldDate(){
        $expnid=$this->input->post('exid');


        $str ="SELECT emp_slry_log_empsalry_tblid
        FROM ezy_pos_expense
        RIGHT JOIN ezy_pos_employe_salary_log ON ezy_pos_employe_salary_log.emp_slry_log_expense_tblid = ezy_pos_expense.expen_id
        WHERE expen_id ='".$expen_id."'
        AND expen_expencat_id=1";   
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row()->ezy_pos_expense;
        }
        else{
            return false;
        }
        //select * from foo where id = (select min(id) from foo where id > 4)
    }
   
}