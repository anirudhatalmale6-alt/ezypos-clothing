<?php
class SupReturns_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function getGrnItems(){
        $grnid = $this->input->post('grnid');
        $str="SELECT grnitm_price,grnitm_total,grnitm_discount,grnitm_createdat,itm_id,itm_code,itm_name,cur_currentQTY
        FROM ezy_pos_grn_item
        INNER JOIN ezy_pos_currentqtywithgrn ON ezy_pos_currentqtywithgrn.cur_itmID=ezy_pos_grn_item.grnitm_itemid
        INNER JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_grn_item.grnitm_itemid
        WHERE grnitm_grn_id='".$grnid."'
        AND cur_grnID='".$grnid."'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();           
        }
        else{
            return false;
        }
    }
    public function getGrnDetails(){
        $grnid = $this->input->post('grnid');
        $this->db->select('grn_supplier_id, grn_grandtotal,grn_discount');
        $this->db->from('ezy_pos_grns');
        $this->db->where('grn_id', $grnid);
        $this->db->where('grn_status', 1);
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
            'suprtrn_supID'=>$this->input->post('supID'),            
            'suprtrn_totalRtrn'=>$this->input->post('supRtrnTotal'),
            'suprtrn_createdby'=>$userid,
            'suprtrn_status'=>1
        );
        $this->db->insert('ezy_pos_sup_return', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function addReturnItems(){
        $data=array(
            'supRtrn_itm_supRtrnID'=>$this->input->post('rID'),
            'supRtrn_itm_itmID'=>$this->input->post('rItmID'),
            'supRtrn_itm_rQty'=>$this->input->post('rQty'),
            'supRtrn_itm_rAmount'=>$this->input->post('rAmount')
        );
        return $this->db->insert('ezy_pos_sup_return_item', $data);
    }
}