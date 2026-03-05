<?php
class Expenses_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function addExpensePOST()
    {
        if(isset($_SESSION['userid'])){
            $userid=$_SESSION['userid'];
        }
        $data = array(
            'expen_expencat_id'=>$this->input->post('expenseCat'),
            'expen_description'=>$this->input->post('description'),
            'expen_amount'=>$this->input->post('amount'),
            'expen_date'=>$this->input->post('date'),
            'expen_createdby'=>$userid,
            'expen_status'=>1
        );        
        $this->db->insert('ezy_pos_expense', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    public function showAllExpenses(){
        $str ="select expencat_catname,expen_id,expen_description,expen_amount,expen_date FROM ezy_pos_expense_cat INNER JOIN ezy_pos_expense ON ezy_pos_expense_cat.expencat_id = ezy_pos_expense.expen_expencat_id WHERE expen_status=1 ORDER BY `ezy_pos_expense`.`expen_id` DESC";        
                $query = $this->db->query($str);
                if($query->num_rows()>0){
                    return $query->result();
                }
                else{
                    return false;
                }
    }
    public function getExpenCategry($id){
        $this->db->select('expen_expencat_id');
        $this->db->from('ezy_pos_expense');
        $this->db->where('expen_id',$id);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row()->expen_expencat_id;
        }
        else{
            return false;
        }
    }
    public function EditExpense($id){       
        $str ="select expen_expencat_id,expencat_id,expencat_catname,expen_id,expen_description,expen_amount,expen_date
        FROM ezy_pos_expense_cat
        INNER JOIN ezy_pos_expense ON ezy_pos_expense_cat.expencat_id = ezy_pos_expense.expen_expencat_id
        WHERE expen_id ='".$id."'";   
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateExpense(){
        $expen_id = $this->input->post('hiddenID'); //expense_id
        $updateData = array(
            'expen_expencat_id' => $this->input->post('edit_expenseCat'),
            'expen_description' => $this->input->post('edit_description'),
            'expen_amount' => $this->input->post('edit_amount'),
            'expen_date' => $this->input->post('edit_date')
        );
        $this->db->where('expen_id', $expen_id);
        $this->db->update('ezy_pos_expense', $updateData);
    }
    public function DeleteExpense(){
        $id = $this->input->post('id');
        $updateData = array(
            'expen_status' => 0
        );
        $this->db->where('expen_id', $id);
        $this->db->update('ezy_pos_expense', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function ExpenseCheque($expenseTblid){
        $data = array(
            'expen_chq_expntblid'=>$expenseTblid,
            'expen_chq_amount'=>$this->input->post('amount'),
            'expen_chq_bank'=>$this->input->post('selectbank'),
            'expen_chq_chq_no'=>$this->input->post('cheqno'),
            'expen_chq_date'=>$this->input->post('cheqdate'),
            'expen_chq_status'=>1
        );        
        $this->db->insert('ezy_pos_expen_cheque', $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    public function getexpenseChq(){
        $str="SELECT expen_chq_id,expen_chq_amount,expen_chq_chq_no,expen_chq_date,expen_chq_status,
        bank_name,expen_description,expen_expencat_id,empsalary_staffid,staff_name
         
        from ezy_pos_expen_cheque
        LEFT JOIN ezy_pos_bank ON ezy_pos_bank.bank_id=ezy_pos_expen_cheque.expen_chq_bank
        LEFT JOIN ezy_pos_expense ON ezy_pos_expense.expen_id=ezy_pos_expen_cheque.expen_chq_expntblid
        LEFT JOIN ezy_pos_employe_salary_log ON ezy_pos_employe_salary_log.emp_slry_log_expense_tblid=ezy_pos_expense.expen_id
        LEFT JOIN ezy_pos_employe_salary ON ezy_pos_employe_salary.empsalary_id=ezy_pos_employe_salary_log.emp_slry_log_empsalry_tblid
        LEFT JOIN ezy_pos_staffs ON ezy_pos_staffs.staff_id=ezy_pos_employe_salary.empsalary_staffid
        ";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }

    /*------------------Today's Total EXPENSES In Dash Board--------------------------*/

// File: application/models/Expenses_model.php
public function get_today_total_expenses() {
    $today = date('Y-m-d');
    $this->db->select_sum('expen_amount');
    $this->db->from('ezy_pos_expense');
    $this->db->where('DATE(expen_date)', $today); // Assumes 'expen_date' is in DATE format
    $this->db->where('expen_status', 1); // To exclude deleted/hidden expenses
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->row()->expen_amount ?: 0;
    } else {
        return 0;
    }
}


    
    
}