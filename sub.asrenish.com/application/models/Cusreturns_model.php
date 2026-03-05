<?php
class CusReturns_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function getInvoice(){
        // INNER JOIN ezy_pos_currentqtywithgrn ON ezy_pos_currentqtywithgrn.cur_itmID=ezy_pos_sale_item.saleitem_item_id
        $invcID = $this->input->post('invcID');
        $str="SELECT saleitem_price,saleitem_quantity,saleitem_total,saleitem_discount,saleitem_ctreatedat,itm_id,itm_code,itm_name
        FROM ezy_pos_sale_item
        INNER JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_sale_item.saleitem_item_id       
        WHERE saleitem_sale_id='".$invcID."'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getSaleDetails(){
        $invcID = $this->input->post('invcID');
        $this->db->select('sale_cus_id, sale_grandtotal,sale_subtotal,sale_discount,sale_less');
        $this->db->from('ezy_pos_sale');
        $this->db->where('sale_id', $invcID);
        $this->db->where('sale_status', 1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function addReturn(){
        if(isset($_SESSION['userid'])){
            $userid = $_SESSION['userid'];
        }
        $data = array(
            'cusrtrn_cusID'=>$this->input->post('cusID'),            
            'cusrtrn_totalRtrn'=>$this->input->post('rtrnTotal'),
            'cusrtrn_createdby'=>$userid,
            'cusrtrn_status'=>1
        );
        $this->db->insert('ezy_pos_cus_return', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function addReturnItems(){
        $data=array(
            'retrn_itm_retrnID'=>$this->input->post('rID'),
            'retrn_itm_itmID'=>$this->input->post('rItmID'),
            'retrn_itm_rQty'=>$this->input->post('rQty'),
            'retrn_itm_rAmount'=>$this->input->post('rAmount')
        );
        return $this->db->insert('ezy_pos_cus_return_item', $data);
    }
}
