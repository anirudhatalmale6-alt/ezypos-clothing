<?php
class Subcategories_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }

    public function insertSubCategory()
    {
        $data = array(
            'parent_cat_id' => $this->input->post('maintype'),
            'sub_cat_name' => $this->input->post('categoryname')          
        );
        return $this->db->insert('ezy_pos_subcategories', $data);
    }

    public function getAllSubCategories(){
        $parent_cat_id = $this->input->post('cat_id');

        $this->db->order_by('sub_cat_id', 'desc');
        $this->db->where('parent_cat_id', $parent_cat_id);
        $this->db->where('sub_cat_status', 1);
        $query = $this->db->get('ezy_pos_subcategories');
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    public function getAllSubCategories_w(){
        $parent_cat_id = $this->input->post('cat_id');
//   WAY 1        
//        $this->db->order_by('sub_cat_id', 'desc');
//        $this->db->where('parent_cat_id', $parent_cat_id);
//        $this->db->where('sub_cat_status', 1);
//        $query = $this->db->get('ezy_pos_subcategories');
//   WAY 2
//        $sql="SELECT * FROM ezy_pos_subcategories WHERE sub_cat_status='1' AND parent_cat_id =? ORDER BY sub_cat_name";
//        $query = $this->db->query($sql,array($parent_cat_id));
//   WAY 3
        $query = $this->db->query("SELECT * FROM ezy_pos_subcategories WHERE sub_cat_status='1' AND parent_cat_id ='$parent_cat_id' ORDER BY sub_cat_name");
        if($query->num_rows()>0){
        return $query->result();
        }
        else{
        return false;
        }
    }
    
    public function showCategorySubCats_w(){
        $parent_cat_id = $this->input->post('category_id');
        $query = $this->db->query("SELECT * FROM ezy_pos_subcategories WHERE sub_cat_status='1' AND parent_cat_id ='$parent_cat_id' ORDER BY sub_cat_name");
        return $query->num_rows();
       
    }

    public function updateSubCategory_SameParent(){
        $subCat_id = $this->input->post('sub_catID');
        $updateData = array(
            'sub_cat_name' => $this->input->post('Edit_categoryname')
        );
        $this->db->where('sub_cat_id', $subCat_id);
        $this->db->update('ezy_pos_subcategories', $updateData);
    }

    public function updateSubCategory_ParentChanged(){ 
        $sub_catID = $this->input->post('sub_catID'); 
        $newParent_catID = $this->input->post('Edit_maintype');
        $newSubcategoryname = $this->input->post('Edit_categoryname');
        
        $updateData = array(
            'parent_cat_id' => $newParent_catID,
            'sub_cat_name' => $newSubcategoryname
        );
        $this->db->where('sub_cat_id', $sub_catID);
        $this->db->update('ezy_pos_subcategories', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }       
    }
    public function DeleteSubCategory(){
        $id = $this->input->post('id');
        $updateData = array(
            'sub_cat_status' => 0
        );
        $this->db->where('sub_cat_id', $id);
        $this->db->update('ezy_pos_subcategories', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function Listsubcategories(){
        $id = $this->input->post('id');
        //$t = $this->input->get('id'); //
        //$parent_id= $_GET['id'];
        $this->db->order_by('sub_cat_id', 'desc');
        $this->db->where('parent_cat_id', $id);
        $this->db->where('sub_cat_status', 1);
        $query = $this->db->get('ezy_pos_subcategories');
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return $id;
        }
    }


}