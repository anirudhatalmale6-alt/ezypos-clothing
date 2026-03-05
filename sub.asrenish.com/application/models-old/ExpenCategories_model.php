<?php
class ExpenCategories_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function addExpensePOST()
    {
        $data = array(
            'expencat_catname' => $this->input->post('expensename'),
            'expencat_status' => 1
        );

        return $this->db->insert('ezy_pos_expense_cat', $data);
    }
    public function showAllExpenses(){
            $this->db->order_by('expencat_id', 'desc');
            $this->db->where('expencat_status', 1);
			$query = $this->db->get('ezy_pos_expense_cat');
			if($query->num_rows()>0){
				return $query->result();
			}
			else{
				return false;
			}
    }
    public function EditExpenses(){        
        $id = $this->input->post('id');
        $this->db->where('expencat_id', $id);
        $query = $this->db->get('ezy_pos_expense_cat');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateExpenses(){
        $expen_id = $this->input->post('hiddenID');
        $updateData = array(
            'expencat_catname' => $this->input->post('edit_expensename')
        );
        $this->db->where('expencat_id', $expen_id);
        $this->db->update('ezy_pos_expense_cat', $updateData);

    }
    public function DeleteExpenses(){
        $id = $this->input->post('id');
        $updateData = array(
            'expencat_status' => 0
        );
        $this->db->where('expencat_id', $id);
        $this->db->update('ezy_pos_expense_cat', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getExpenCategories(){// ID & NAME
        $this->db->select('expencat_id, expencat_catname');
        $this->db->from('ezy_pos_expense_cat');
        $this->db->where('expencat_status', 1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
}