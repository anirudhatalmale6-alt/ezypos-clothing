<?php
class Suppliers_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function insertSupplier()
    {
        $data = array(
            'sup_name' => $this->input->post('suppliername'),
            'sup_address' => $this->input->post('address'),
            'sup_contact' => $this->input->post('contact'),
            'sup_balance' => $this->input->post('balance')
        );
        // "Is Tailor" flag (only if the column exists)
        if (in_array('sup_is_tailor', $this->db->list_fields('ezy_pos_suppliers'))) {
            $data['sup_is_tailor'] = $this->input->post('is_tailor') ? 1 : 0;
        }

        return $this->db->insert('ezy_pos_suppliers', $data);
    }

    // Suppliers marked as tailors (Is Tailor = 1) - for the Tailor dropdowns.
    public function getTailors()
    {
        if (!in_array('sup_is_tailor', $this->db->list_fields('ezy_pos_suppliers'))) {
            return array();
        }
        $this->db->select('sup_id, sup_name, sup_contact');
        $this->db->from('ezy_pos_suppliers');
        $this->db->where('sup_status', 1);
        $this->db->where('sup_is_tailor', 1);
        $this->db->order_by('sup_name');
        return $this->db->get()->result();
    }
    public function getAllSuppliers(){
            $this->db->order_by('sup_id', 'desc');
            $this->db->where('sup_status', 1);
			$query = $this->db->get('ezy_pos_suppliers');
			if($query->num_rows()>0){
				return $query->result();
			}
			else{
				return false;
			}
    }

    public function editSupplier(){        
        $id = $this->input->post('id');
        $this->db->where('sup_id', $id);
        $query = $this->db->get('ezy_pos_suppliers');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateSupplier(){
        $sup_id = $this->input->post('hiddenID');
        $updateData = array(
            'sup_name' => $this->input->post('edit_suppliername'),
            'sup_address' => $this->input->post('edit_address'),
            'sup_contact' => $this->input->post('edit_contact'),
            'sup_balance' => $this->input->post('edit_balance')
        );
        if (in_array('sup_is_tailor', $this->db->list_fields('ezy_pos_suppliers'))) {
            $updateData['sup_is_tailor'] = $this->input->post('edit_is_tailor') ? 1 : 0;
        }
        $this->db->where('sup_id', $sup_id);
        $this->db->update('ezy_pos_suppliers', $updateData);

    }
    public function DeleteSupplier(){
        $id = $this->input->post('id');
        $updateData = array(
            'sup_status' => 0
        );
        $this->db->where('sup_id', $id);
        $this->db->update('ezy_pos_suppliers', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getSuppliers(){ //get name& id
        $this->db->select('sup_id, sup_name');
        $this->db->from('ezy_pos_suppliers');
        $this->db->where('sup_status', 1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getSupDetails(){
        $id = $this->input->post('id');
        $str="SELECT * FROM ezy_pos_suppliers
        WHERE sup_id='".$id."'
        AND sup_status=1";
        $query = $this->db->query($str);
        if($query->num_rows()==1){
            return $query->row();
        }
        else{
            return false;
        }
    }

}