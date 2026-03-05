<?php
class Bank_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function createBankPost(){
        $userid=0;
        if(isset($_SESSION['userid'])){
            $userid=$_SESSION['userid'];
        }
        $data = array(
            'bank_name'=>$this->input->post('bankname'),
            'bank_branch'=>$this->input->post('branch'),
            'bank_accname'=>$this->input->post('accname'),
            'bank_accnumber'=>$this->input->post('accnumber'),
            'bank_createdby'=>$userid,   
            'bank_status'=>1
        );
        return $this->db->insert('ezy_pos_bank', $data);
    }
    public function showAllBanks(){
        $str ="select bank_id,bank_name,bank_branch,bank_accname,bank_accnumber,user_name
        FROM ezy_pos_bank 
        LEFT JOIN ezy_pos_users ON ezy_pos_users.user_id = ezy_pos_bank.bank_createdby 
        WHERE bank_status=1 ORDER BY bank_id DESC";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function EditBank(){ 
        $id = $this->input->post('id');       
        $str ="select bank_id,bank_name,bank_branch,bank_accname,bank_accnumber
        FROM ezy_pos_bank
        WHERE bank_id ='".$id."'";   
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateBank(){
        $bankid = $this->input->post('hiddenID'); 
        $updateData = array(
            'bank_name' => $this->input->post('edit_bankname'),
            'bank_branch' => $this->input->post('edit_branch'),
            'bank_accname' => $this->input->post('edit_accname'),
            'bank_accnumber' => $this->input->post('edit_accnumber')
        );
        $this->db->where('bank_id', $bankid);
        $this->db->update('ezy_pos_bank', $updateData);
    }
    public function deleteBank(){
        $id = $this->input->post('id');
        $updateData = array(
            'bank_status' => 0
        );
        $this->db->where('bank_id', $id);
        $this->db->update('ezy_pos_bank', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getAllBanks(){
        $this->db->order_by('bank_id', 'desc');
        $this->db->where('bank_status', 1);
        $query = $this->db->get('ezy_pos_bank');
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
 
}