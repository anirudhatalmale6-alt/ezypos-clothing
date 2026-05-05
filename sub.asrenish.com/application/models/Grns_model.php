<?php
class Grns_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function addGrnPOST2(){
        if(isset($_SESSION['userid'])){
            $userid = $_SESSION['userid'];
            
        }
        $storeid = $this->input->post('storeid');
        if($storeid == '' || $storeid == null){ $storeid = 0; }
        $data = array(
            'grn_code'=>$this->input->post('grncode'),
            'grn_supplier_id'=>$this->input->post('supplierid'),
            'grn_grandtotal'=>$this->input->post('grandtotal'),
            'grn_subtotal'=>$this->input->post('subtotal'),
            'grn_discount'=>$this->input->post('invoiceDis'),
            'grn_less'=>0,
            'grn_createdby'=>$userid,
            'grn_location'=>$storeid,
            'grn_date'=>$this->input->post('date'),
            //'grn_description'=>$this->input->post('grnDec'),
            'grn_status'=>1
        );
        $fields = $this->db->list_fields('ezy_pos_grns');
        if (in_array('grn_discount_type', $fields)) {
            $data['grn_discount_type'] = ($this->input->post('discount_type') ? $this->input->post('discount_type') : 'percentage');
        }
        $this->db->insert('ezy_pos_grns', $data);
        $grn_id = $this->db->insert_id();
        
        if($grn_id){
            $creditvalue=$this->input->post('creditvalue');
            $supplierid=$this->input->post('supplierid');
            $str="UPDATE `ezy_pos_suppliers` SET sup_balance=sup_balance+'$creditvalue'  WHERE `sup_id`='$supplierid'";
            $query = $this->db->query($str);
        }
        return $grn_id;
       // return 10;
    }
    public function insertGrnItemPOST2(){
        $data = array(
            'grnitm_grn_id' => $this->input->post('grnID'),
            'grnitm_itemid' => $this->input->post('itemid'),            
            'grnitm_price' => $this->input->post('price'),
            'grnitm_quantity' => $this->input->post('quantity'),
            'grnitm_total' => $this->input->post('total'),
            'grnitm_discount' => $this->input->post('itmDis')
        );
        $fields = $this->db->list_fields('ezy_pos_grn_item');
        if (in_array('grnitm_discount_type', $fields)) {
            $data['grnitm_discount_type'] = ($this->input->post('itmDisType') ? $this->input->post('itmDisType') : 'percentage');
        }
        return $this->db->insert('ezy_pos_grn_item', $data);
    }
    public function loadAllGrn(){
        $str ="SELECT grn_id,grn_subtotal,grn_discount,grn_grandtotal,grn_date,grn_createdat,sup_name,user_name,
        sup_pay_credit,sup_pay_cash,
        IFNULL(st.store_name,'N/A') as store_name
        FROM ezy_pos_grns
        LEFT JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id=ezy_pos_grns.grn_supplier_id
        LEFT JOIN ezy_pos_users ON ezy_pos_users.user_id=ezy_pos_grns.grn_createdby
        LEFT JOIN ezy_pos_sup_payment ON ezy_pos_sup_payment.sup_pay_grnid=ezy_pos_grns.grn_id
        LEFT JOIN ezy_pos_stores st ON st.store_id=ezy_pos_grns.grn_location
        WHERE grn_status= 1 ORDER BY grn_id DESC";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function loadGrnItems(){
        $grndid = $this->input->post('id');
        $str ="SELECT grnitm_price,grnitm_quantity,grnitm_discount,grnitm_total,itm_name,itm_code
        FROM ezy_pos_grn_item
        LEFT JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_grn_item.grnitm_itemid
        WHERE grnitm_grn_id='".$grndid."' ORDER BY grnitm_id";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function editGrn(){
        $grndid = $this->input->post('id');
        $str ="SELECT grn_id,grn_subtotal,grn_discount,grn_grandtotal,grn_date,
        sup_id,sup_name,
        sup_pay_cash,sup_pay_credit
        FROM ezy_pos_grns
        LEFT JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id=ezy_pos_grns.grn_supplier_id
        LEFT JOIN ezy_pos_sup_payment ON ezy_pos_sup_payment.sup_pay_grnid=ezy_pos_grns.grn_id
        WHERE grn_id='".$grndid."' ORDER BY grn_id";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateGrn(){
        $grndid = $this->input->post('hiddngrnID');
        $sup = $this->input->post('grnsupplier');
        $cash = $this->input->post('Edit_cash');
        $credit = $this->input->post('Edit_credit');
        $discnt = $this->input->post('Edit_discount');
        $grndtotal = $this->input->post('Edit_grandtotal');
        $date = $this->input->post('Edit_date');
        $str ="UPDATE ezy_pos_grns            
        LEFT JOIN ezy_pos_sup_payment ON ezy_pos_sup_payment.sup_pay_grnid=ezy_pos_grns.grn_id
        SET grn_supplier_id='".$sup."',
        sup_pay_cash='".$cash."',
        sup_pay_credit='".$credit."',
        grn_discount='".$discnt."',
        grn_grandtotal='".$grndtotal."',
        grn_date='".$date."'
        WHERE grn_id='".$grndid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getGrns(){
        $this->db->select('grn_id,grn_code');
        $this->db->from('ezy_pos_grns');
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
}