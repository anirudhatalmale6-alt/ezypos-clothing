<?php
class CurQtyWithGrn_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    public function addGRNItems()// add grn details
    {
        $cur_itmID = $this->input->post('itmid');
        $storeid = $this->input->post('storeid');
        if($storeid == '' || $storeid == null){ $storeid = 0; }
        $data = array(
            'cur_grnID' => $this->input->post('grnID'),
            'cur_itmID' => $cur_itmID,
            'cur_store_id' => $storeid,
            'cur_grnQty' => $this->input->post('qty'),
            'cur_grnPrice' => $this->input->post('prc'),
            'cur_grnTotal' => $this->input->post('ttl'),
            'cur_currentQTY' => $this->input->post('qty'),
            'cur_status'=>1
        );
        $this->db->insert('ezy_pos_currentqtywithgrn', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function getOldStockQty($itmID, $storeid = 0){
        if($storeid > 0){
            $curQty="SELECT cur_currentQTY FROM ezy_pos_currentqtywithgrn
            WHERE cur_itmID='".$itmID."'
            AND cur_store_id='".$storeid."'
            AND cur_currentQTY>0
            ORDER BY cur_id LIMIT 1";
        } else {
            $curQty="SELECT cur_currentQTY FROM ezy_pos_currentqtywithgrn
            WHERE cur_itmID='".$itmID."'
            AND cur_currentQTY>0
            ORDER BY cur_id LIMIT 1";
        }
        $query = $this->db->query($curQty);
        if($query->num_rows() == 1){
            $result= $query->row();
            return $result->cur_currentQTY;
        }
        else{
            return false;
        }
    }
    public function updateOldestQtyToZero($itmID, $storeid = 0){
        $this->db->trans_start();
        $this->db->query("SET @update_id := 0");
        if($storeid > 0){
            $this->db->query("UPDATE ezy_pos_currentqtywithgrn
            SET cur_currentQTY =0 ,cur_id = (SELECT @update_id := cur_id)
            WHERE cur_itmID='".$itmID."'
            AND cur_store_id='".$storeid."'
            AND cur_currentQTY>0
            ORDER BY cur_id LIMIT 1");
        } else {
            $this->db->query("UPDATE ezy_pos_currentqtywithgrn
            SET cur_currentQTY =0 ,cur_id = (SELECT @update_id := cur_id)
            WHERE cur_itmID='".$itmID."'
            AND cur_currentQTY>0
            ORDER BY cur_id LIMIT 1");
        }
        $query=$this->db->query("SELECT @update_id AS updateid");
        if($this->db->affected_rows()>0){
            $result=$query->row();
            return $result->updateid;
        }
        else{
            return false;
        }
        $this->db->trans_complete();
    }
    public function updateOldestQtyToValue($dif, $itmID, $storeid = 0){
        $this->db->trans_start();
        $this->db->query("SET @update_id := 0");
        if($storeid > 0){
            $this->db->query("UPDATE ezy_pos_currentqtywithgrn
            SET cur_currentQTY = '".$dif."',cur_id = (SELECT @update_id := cur_id)
            WHERE cur_itmID='".$itmID."'
            AND cur_store_id='".$storeid."'
            AND cur_currentQTY>0
            ORDER BY cur_id LIMIT 1");
        } else {
            $this->db->query("UPDATE ezy_pos_currentqtywithgrn
            SET cur_currentQTY = '".$dif."',cur_id = (SELECT @update_id := cur_id)
            WHERE cur_itmID='".$itmID."'
            AND cur_currentQTY>0
            ORDER BY cur_id LIMIT 1");
        }
        $query=$this->db->query("SELECT @update_id AS updateid");
        if($this->db->affected_rows()>0){
            $result=$query->row();
            return $result->updateid;
        }
        else{
            return false;
        }
        $this->db->trans_complete();
    }
     public function addToGrnSale($cur_id_of_updated_row, $quantity){
    $data = array(
        'grnvssale_curQtyWithGrnID' => $cur_id_of_updated_row,
        'grnvssale_itmID' => $this->input->post('itmid'),
        'grnvssale_saleID' => $this->input->post('saleID'),
        'grn_quantity' => $quantity,
        'grnvssale_status' => 1
    );
    return $this->db->insert('ezy_pos_cur_grn_vs_sale', $data);        
    }
    public function insuffi_AddToGrnSale($itmid,$cur_id,$saleID, $deductQty){
        $data = array(
        'grnvssale_curQtyWithGrnID' => $cur_id,
        'grnvssale_itmID' => $itmid,
        'grnvssale_saleID' => $saleID,
        'grn_quantity' => $deductQty,
        'grnvssale_status' => 1
    );
        return $this->db->insert('ezy_pos_cur_grn_vs_sale', $data); 
    }

    //Customer Returns
    public function saleOccurredGrn($itmID,$saleID){
        $str="SELECT cur_grnQty,cur_currentQTY
        FROM ezy_pos_currentqtywithgrn
        INNER JOIN ezy_pos_cur_grn_vs_sale ON ezy_pos_currentqtywithgrn.cur_id=ezy_pos_cur_grn_vs_sale.grnvssale_curQtyWithGrnID
        WHERE grnvssale_saleID='".$saleID."'
        AND cur_itmID='".$itmID."'
        AND cur_grnQty>cur_currentQTY
        ORDER BY cur_id DESC LIMIT 1";
        $query = $this->db->query($str);
        if($query->num_rows() == 1){
            return $query->row();
           // return $result->;
        }
        else{
            return false;
        }  
    }
    public function getMatchingCur_Id($saleID,$itmID){
        $str="SELECT grnvssale_curQtyWithGrnID 
        from ezy_pos_cur_grn_vs_sale
        INNER JOIN ezy_pos_currentqtywithgrn ON ezy_pos_cur_grn_vs_sale.grnvssale_curQtyWithGrnID=ezy_pos_currentqtywithgrn.cur_id
        WHERE grnvssale_saleID='".$saleID."'
        AND cur_itmID='".$itmID."'
        AND cur_grnQty>cur_currentQTY
        ORDER BY grnvssale_id DESC LIMIT 1";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();             
        }
        else{
            return false;
        }
    }
    public function updateSaleOccurredGrn($add,$saleID,$itmID){
        $str="UPDATE ezy_pos_currentqtywithgrn
        SET cur_currentQTY=cur_currentQTY +'".$add."'
        INNER JOIN ezy_pos_cur_grn_vs_sale ON ezy_pos_currentqtywithgrn.cur_id=ezy_pos_cur_grn_vs_sale.grnvssale_curQtyWithGrnID
        WHERE grnvssale_saleID='".$saleID."'
        AND cur_itmID='".$itmID."'
        ORDER BY cur_id DESC LIMIT 1";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }  
    }
    public function updateSaleOccurredGrn2($rQty,$curID){
        $str="UPDATE ezy_pos_currentqtywithgrn
        SET cur_currentQTY=cur_currentQTY +'".$rQty."'
        WHERE cur_id='".$curID."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function updateLatesGrnItmQty($grnID,$itmID,$rQty){
        $str="UPDATE ezy_pos_currentqtywithgrn
        SET cur_currentQTY=cur_currentQTY -'".$rQty."'
        WHERE cur_grnID='".$grnID."'
        AND cur_itmID='".$itmID."'
        AND cur_currentQTY>0";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
   

    //new codes
    public function fetchStores() {
        $this->db->select('wh_id , wh_name');
        $this->db->from('ezy_pos_warehouse');
        $this->db->where('wh_status', 1); // Fetch only active stores
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insertStoreItem($data) {
        return $this->db->insert('ezy_pos_store_items', $data);
    }
    
    
    // Johan Function
    public function ReturnGRNsale() {
       // Just for debugging or placeholder - no DB logic
    echo json_encode(['success' => false, 'message' => 'ReturnGRNsale skipped.']);
    return;
    }
    
    
}


