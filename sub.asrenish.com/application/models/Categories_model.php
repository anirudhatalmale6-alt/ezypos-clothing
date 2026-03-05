<?php
class Categories_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function insertMainCategory()
    {
        $data = array(
            'cat_name' => $this->input->post('categoryname')          
        );

        return $this->db->insert('ezy_pos_categories', $data);
    }

    public function getTypes()
    {
        $this->db->where('cat_status', 1);
        $query = $this->db->get('ezy_pos_categories');
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getTypes_w()
    {
//        $this->db->where('cat_status', 1);
//        $query = $this->db->get('ezy_pos_categories');
        $query=$this->db->query("SELECT * FROM ezy_pos_categories WHERE cat_status='1' ORDER BY cat_name ASC");
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }

    public function showAllCategories(){
        $this->db->order_by('cat_id', 'desc');
        $this->db->where('cat_status', 1);
        $query = $this->db->get('ezy_pos_categories');
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    public function showAllCategories_w(){
        $query = $this->db->query("SELECT * FROM ezy_pos_categories WHERE cat_status='1' ORDER BY cat_name desc");
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }


    public function updateMainCategory(){        
    }
    
    public function DeleteParentCategory(){
        $id = $this->input->post('id');
        $updateData = array(
            'cat_status' => 0
        );
        $this->db->where('cat_id', $id);
        $this->db->update('ezy_pos_categories', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function EditParentCategory(){
        $parent_cat_id = $this->input->post('parent_cat_id');
        $this->db->where('cat_id', $parent_cat_id);
        $query = $this->db->get('ezy_pos_categories');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateParentCategory(){
        $cat_id = $this->input->post('hiddenParentID');
        $updateData = array(
            'cat_name' => $this->input->post('Edit_parent_cat_name') 
        );
        $this->db->where('cat_id', $cat_id);
        $this->db->update('ezy_pos_categories', $updateData);

    }



/*
inner

        $this->db->join('ezy_pos_categories', 'ezy_pos_categories.cat_id = ezy_pos_subcategories.parent_cat_id', 'left');
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
   $this->db->order_by('itm_id', 'desc');
        $query = $this->db->get('ezy_pos_items');
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }

*/
}