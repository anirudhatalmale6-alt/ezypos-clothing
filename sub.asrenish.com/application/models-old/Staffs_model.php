<?php
class Staffs_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    public function insertStaff()
    {
        $data = array(
            'staff_name' => $this->input->post('staffname'),            
            'staff_address' => $this->input->post('address'),
            'staff_contact' => $this->input->post('contact'),
            'staff_basicsalary' => $this->input->post('basicsalary'),
            'staff_food' => $this->input->post('foodallowance'),
            'staff_travel' => $this->input->post('travelallowance'),
            'staff_other' => $this->input->post('otherallowance'),
            'staff_otperhour' => $this->input->post('otperhour')
        );
        return $this->db->insert('ezy_pos_staffs', $data);
    }
    public function getAllStaffs(){
            $this->db->order_by('staff_id', 'desc');
            $this->db->where('staff_status', 1);
			$query = $this->db->get('ezy_pos_staffs');
			if($query->num_rows()>0){
				return $query->result();
			}
			else{
				return false;
			}
    }
    public function EditStaff(){        
        $id = $this->input->post('id');
        $this->db->where('staff_id', $id);
        $query = $this->db->get('ezy_pos_staffs');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateStaff(){
        $staff_id = $this->input->post('hiddenID');
        $updateData = array(
            'staff_name' => $this->input->post('edit_staffname'),            
            'staff_address' => $this->input->post('edit_address'),
            'staff_contact' => $this->input->post('edit_contact'),
            'staff_basicsalary' => $this->input->post('edit_basicsalary'),
            'staff_food' => $this->input->post('edit_foodallowance'),
            'staff_travel' => $this->input->post('edit_travelallowance'),
            'staff_other' => $this->input->post('edit_otherallowance'),
            'staff_otperhour' => $this->input->post('edit_otperhour'),
            'staff_status' =>1
        );
        $this->db->where('staff_id', $staff_id);
        $this->db->update('ezy_pos_staffs', $updateData);
    }
    public function DeleteStaff(){
        $id = $this->input->post('id');
        $updateData = array(
            'staff_status' => 0
        );
        $this->db->where('staff_id', $id);
        $this->db->update('ezy_pos_staffs', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getEmployeeExpense(){
        $id = $this->input->post('id');
        $this->db->select('staff_basicsalary,staff_food,staff_travel,staff_other,staff_otperhour');
        $this->db->where('staff_id', $id);
        $query = $this->db->get('ezy_pos_staffs');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function getEmployeeNameID(){
        $this->db->select('staff_id,staff_name');
        $this->db->from('ezy_pos_staffs');
        $this->db->where('staff_status',1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
}