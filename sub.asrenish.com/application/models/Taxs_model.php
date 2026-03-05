<?php
class Taxs_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function insertTax()
    {
        $data = array(
            'tax_name' => $this->input->post('taxname'),            
            'tax_value' => $this->input->post('taxvalue')
        );

        return $this->db->insert('ezy_pos_taxs', $data);
    }

    public function getAllTaxs(){
            $this->db->order_by('tax_id', 'desc');
            $this->db->where('tax_status', 1);
			$query = $this->db->get('ezy_pos_taxs');
			if($query->num_rows()>0){
				return $query->result();
			}
			else{
				return false;
			}
    }
    public function EditTax(){        
        $id = $this->input->post('id');
        $this->db->where('tax_id', $id);
        $query = $this->db->get('ezy_pos_taxs');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateTax(){
        $tax_id = $this->input->post('hiddenID');
        $updateData = array(
            'tax_name' => $this->input->post('edit_taxname'),            
            'tax_value' => $this->input->post('edit_taxvalue')
        );
        $this->db->where('tax_id', $tax_id);
        $this->db->update('ezy_pos_taxs', $updateData);

    }
    public function DeleteTax(){
        $id = $this->input->post('id');
        $updateData = array(
            'tax_status' => 0
        );
        $this->db->where('tax_id', $id);
        $this->db->update('ezy_pos_taxs', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
}