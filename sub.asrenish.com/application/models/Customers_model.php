<?php
class Customers_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }

    public function addCustomerPOST()
    {
        $data = array(
            'cus_name' => $this->input->post('name'),            
            'cus_address' => $this->input->post('address'),
            'cus_contact' => $this->input->post('contact'),
            'cus_balance' => $this->input->post('balance'),
            'cus_creditlimit' => $this->input->post('creditlimit')
        );
        $this->db->insert('ezy_pos_customers', $data);
        $insert_id = $this->db->insert_id();
        $data = array(
            'bal_cusid' =>$insert_id,            
            'bal_amount' =>0
        );
        return $this->db->insert('ezy_pos_cus_balnce', $data);
    }
    public function getAllCustomers(){
            $this->db->order_by('cus_id', 'desc');
            $this->db->where('cus_status', 1);
            $query = $this->db->get('ezy_pos_customers');
            if($query->num_rows()>0){
                    return $query->result();
            }
            else{
                    return false;
            }
    }
    
    public function getAllCustomers2(){
            $this->db->order_by('cus_id', 'desc');
            $this->db->where('cus_status', 1);
            $this->db->join('ezy_pos_cus_balnce', 'cus_id = bal_cusid', 'left');
            $query = $this->db->get('ezy_pos_customers');
            if($query->num_rows()>0){
                    return $query->result();
            }
            else{
                    return false;
            }
    }
    

    public function editCustomer2(){        
        $id = $this->input->post('id');
        $this->db->where('cus_id', $id);
        $this->db->join('ezy_pos_cus_balnce', 'cus_id = bal_cusid', 'left');
        $query = $this->db->get('ezy_pos_customers');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    
    public function editCustomer(){        
        $id = $this->input->post('id');
        $this->db->where('cus_id', $id);
        $query = $this->db->get('ezy_pos_customers');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updatecustomer(){
        $cus_id = $this->input->post('hiddenID');
        $updateData = array(
            'cus_name' => $this->input->post('edit_name'),            
            'cus_address' => $this->input->post('edit_address'),
            'cus_contact' => $this->input->post('edit_contact'),
            'cus_balance' => $this->input->post('edit_balance'),
            'cus_creditlimit' => $this->input->post('edit_creditlimit')
        );
        $this->db->where('cus_id', $cus_id);
        $this->db->update('ezy_pos_customers', $updateData);
    }
    public function DeleteCustomer(){
        $id = $this->input->post('id');
        $updateData = array(
            'cus_status' => 0
        );
        $this->db->where('cus_id', $id);
        $this->db->update('ezy_pos_customers', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getCustomers(){ //get name, id & contact
        $this->db->select('cus_id,cus_name,cus_contact');
        $this->db->from('ezy_pos_customers');
        $this->db->where('cus_status', 1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getCusDetails(){
        $id = $this->input->post('id');
        $str="SELECT c.cus_id,c.cus_name,c.cus_address,c.cus_contact,c.cus_balance,c.cus_creditlimit,c.cus_status,c.cus_createdat,bal_amount FROM ezy_pos_customers c
        LEFT JOIN ezy_pos_cus_balnce ON bal_cusid='$id'
        WHERE cus_id ='".$id."'
        AND cus_status=1";
        $query = $this->db->query($str);
        if($query->num_rows()==1){
            return $query->row();
        }
        else{
            return false;
        }
    }
  public function getBal() {
    $id = $this->input->post('id');

    // Using query bindings for security
    $sql = "SELECT * FROM ezy_pos_cus_balnce WHERE bal_cusid = ?";
    $query = $this->db->query($sql, [$id]);

    // Check if query executed successfully
    if ($query && $query->num_rows() == 1) {
        return $query->row(); // return full row object
    } else {
        return false;
    }
}





}