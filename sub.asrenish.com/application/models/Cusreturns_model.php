<?php
class CusReturns_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function getInvoice(){
        // INNER JOIN ezy_pos_currentqtywithgrn ON ezy_pos_currentqtywithgrn.cur_itmID=ezy_pos_sale_item.saleitem_item_id
        $invcID = intval($this->input->post('invcID'));
        $str="SELECT saleitem_price,saleitem_quantity,saleitem_total,saleitem_discount,saleitem_ctreatedat,itm_id,itm_code,itm_name
        FROM ezy_pos_sale_item
        INNER JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_sale_item.saleitem_item_id       
        WHERE saleitem_sale_id='".$invcID."'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            $rows = $query->result();
            $done = $this->returnedQtyBySale($invcID);
            foreach($rows as $r){
                $id = intval($r->itm_id);
                $r->returned_qty  = isset($done[$id]) ? round($done[$id], 2) : 0;
                // What may still be brought back. The sold quantity is no longer
                // reduced by a return, so the limit has to be worked out rather
                // than read off the sale line.
                $r->remaining_qty = round(floatval($r->saleitem_quantity) - $r->returned_qty, 2);
                if($r->remaining_qty < 0){ $r->remaining_qty = 0; }
            }
            return $rows;
        }
        else{
            return false;
        }
    }

    /**
     * How much of each item on a bill has already come back, counting both
     * return screens: this one and the newer Returns / Exchange module.
     *
     * Returns array(item_id => qty).
     */
    public function returnedQtyBySale($sale_id)
    {
        $sale_id = intval($sale_id);
        $out = array();

        // This screen. cusrtrn_saleID arrived in v13; before that the old rows
        // simply cannot be tied to a bill, so they are left out rather than
        // guessed at.
        if($this->db->table_exists('ezy_pos_cus_return')
           && in_array('cusrtrn_saleID', $this->db->list_fields('ezy_pos_cus_return'))){
            $q = $this->db->query(
                "SELECT ri.retrn_itm_itmID AS iid, COALESCE(SUM(ri.retrn_itm_rQty),0) AS q
                 FROM ezy_pos_cus_return_item ri
                 INNER JOIN ezy_pos_cus_return r ON r.cusrtrn_id = ri.retrn_itm_retrnID
                 WHERE r.cusrtrn_saleID = ? AND r.cusrtrn_status = 1
                 GROUP BY ri.retrn_itm_itmID", array($sale_id));
            foreach($q->result() as $x){ $out[intval($x->iid)] = floatval($x->q); }
        }

        // The Returns / Exchange module.
        if($this->db->table_exists('ezy_pos_returns') && $this->db->table_exists('ezy_pos_return_items')){
            $q = $this->db->query(
                "SELECT ri.ri_item_id AS iid, COALESCE(SUM(ri.ri_qty),0) AS q
                 FROM ezy_pos_return_items ri
                 INNER JOIN ezy_pos_returns r ON r.ret_id = ri.ri_return_id
                 WHERE r.ret_sale_id = ? AND r.ret_status = 1
                 GROUP BY ri.ri_item_id", array($sale_id));
            foreach($q->result() as $x){
                $iid = intval($x->iid);
                $out[$iid] = (isset($out[$iid]) ? $out[$iid] : 0) + floatval($x->q);
            }
        }

        return $out;
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
        // Which bill it came off. Without this the return cannot be shown
        // against the sale, and the only way to record it was to shrink the
        // sale itself - which is what we are getting away from.
        if(in_array('cusrtrn_saleID', $this->db->list_fields('ezy_pos_cus_return'))){
            $data['cusrtrn_saleID'] = intval($this->input->post('saleID'));
        }
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
