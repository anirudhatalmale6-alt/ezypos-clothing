<?php
class Stores_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function addStorePOST()
    {
        $data = array(
            'store_name' => $this->input->post('storename'),            
            'store_address' => $this->input->post('address'),
            'store_address2' => $this->input->post('address2'),
            'store_tel' => $this->input->post('tel'),
            'store_mobile' => $this->input->post('mobile'),
            'store_mobile2' => $this->input->post('mobile2'),
            'store_fax' => $this->input->post('fax'),
            'store_email' => $this->input->post('email')
        );
        return $this->db->insert('ezy_pos_stores', $data);
    }
    public function getAllStores(){
        $this->db->order_by('store_id', 'desc');
        $this->db->where('store_status', 1);
        $query = $this->db->get('ezy_pos_stores');
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    public function getAllStoresfornonadmin($user_id){
        $str="SELECT * FROM ezy_pos_stores s,ezy_pos_user_store us WHERE s.store_status ='1' AND us.user_store_status='1' AND us.user_id='".$user_id."' AND us.store_id=s.store_id";
        $query = $this->db->query($str);
        
        if($query->num_rows()>0){
           return $query->result();
        }
        else{
            return false;
        }
    }
    public function EditStore(){
        
        $id = $this->input->post('id');
        $this->db->where('store_id', $id);
        $query = $this->db->get('ezy_pos_stores');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateStore(){
        $store_id = $this->input->post('hiddenID');
        $updateData = array(
            'store_name' => $this->input->post('edit_storename'),            
            'store_address' => $this->input->post('edit_address'),
            'store_address2' => $this->input->post('edit_address2'),
            'store_tel' => $this->input->post('edit_tel'),
            'store_mobile' => $this->input->post('edit_mobile'),
            'store_mobile2' => $this->input->post('edit_mobile2'),
            'store_fax' => $this->input->post('edit_fax'),
            'store_email' => $this->input->post('edit_email')
        );
        $this->db->where('store_id', $store_id);
        $this->db->update('ezy_pos_stores', $updateData);

    }
    public function DeleteStore(){
        $id = $this->input->post('id');
        $updateData = array(
            'store_status' => 0
        );
        $this->db->where('store_id', $id);
        $this->db->update('ezy_pos_stores', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
  

}