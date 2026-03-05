<?php
class InsufficentStockSale_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function insertInsuffiSaleItem($itmID,$qtyDif,$saleID){
        $data = array(
            'insuffi_saleid'=>$saleID,
            'insuffi_itmid'=>$itmID,   
            'insuffi_qty'=>$qtyDif,   
            'insuffi_newqty'=>$qtyDif,     
            'insuffi_grnstatus'=> 0,
            'insuffi_salestatus'=> 0
        );
        return $this->db->insert('ezy_pos_insuffistocksale', $data);
    }
    // public function showPendingGrn(){
    //     $str ="SELECT itm_id, itm_name, insuffi_newqty, insuffi_saleid FROM ezy_pos_items INNER JOIN ezy_pos_insuffistocksale ON ezy_pos_insuffistocksale.insuffi_itmid = ezy_pos_items.itm_id WHERE insuffi_newqty != 0";        
    //     $query = $this->db->query($str);
    //     if($query->num_rows()>0){
    //         return $query->result();
    //     }
    //     else{
    //         return false;
    //     }
    // }
    
    
    // johan pending grn
    
    public function showPendingGrn(){
        $str = "SELECT itm_id, itm_name, insuffi_newqty, insuffi_saleid 
                FROM ezy_pos_items 
                INNER JOIN ezy_pos_insuffistocksale 
                ON ezy_pos_insuffistocksale.insuffi_itmid = ezy_pos_items.itm_id 
                WHERE insuffi_newqty > 0"; // updated condition
    
        $query = $this->db->query($str);
    
        if($query->num_rows() > 0){
            return $query->result();
        } else {
            return false;
        }
    }
    public function showPendingStockAdjust(){
        $str ="select insuffi_saleid,insuffi_itmid,insuffi_qty
        FROM ezy_pos_insuffistocksale
        where insuffi_salestatus = 0";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getInsuffiSoldQty($itmid){  
        $str="SELECT SUM(insuffi_newqty)  as q
        FROM ezy_pos_insuffistocksale
        where insuffi_itmid ='".$itmid."'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            $result= $query->row();
            return $result->q;
        }
        else{
            return 0;
        }      
    }
    public function allInsuffiSoldQtyToZero($itmid){
        $str="UPDATE ezy_pos_insuffistocksale
        SET insuffi_newqty=0
        where insuffi_itmid ='".$itmid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){             
                $str2="UPDATE ezy_pos_insuffistocksale
                SET insuffi_grnstatus=1
                where insuffi_itmid ='".$itmid."'";
                $query = $this->db->query($str2);
                if($this->db->affected_rows()>0){
                    return true;
                }
                else{
                    return false;
                }
        }
        else{
            return false;
        }
    }
    public function updateCurrentGrnqty($itmid,$insuffi_soldQty,$cur_id){
        $str="UPDATE ezy_pos_currentqtywithgrn
        SET cur_currentQTY=cur_currentQTY-'".$insuffi_soldQty."'
        where cur_id ='".$cur_id."'
        AND cur_itmID ='".$itmid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getOldestInsuffiSoldItm($itmid){
        $str="SELECT insuffi_newqty FROM ezy_pos_insuffistocksale 
        WHERE insuffi_itmid='".$itmid."' 
        AND insuffi_newqty>0 
        ORDER BY insuffi_id LIMIT 1";
        $query = $this->db->query($str);
        if($query->num_rows() == 1){
            $result= $query->row();
            return $result->insuffi_newqty;
        }
        else{
            return false;
        }
    }
    public function updateInsuffiSoldQtyTo1($itmid,$grnqty){
        $str="UPDATE ezy_pos_insuffistocksale
        SET insuffi_newqty=insuffi_newqty-'".$grnqty."'
        where insuffi_itmid ='".$itmid."'
        AND insuffi_newqty>0
        ORDER BY insuffi_id LIMIT 1";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function updateInsuffiSoldQtyTo($itmid, $grnqty) {
        $deductedTotal = 0;
    
        while ($grnqty > 0) {
            // Get the oldest insuffi record
            $this->db->select('insuffi_id, insuffi_newqty');
            $this->db->from('ezy_pos_insuffistocksale');
            $this->db->where('insuffi_itmid', $itmid);
            $this->db->where('insuffi_newqty >', 0);
            $this->db->order_by('insuffi_id', 'ASC');
            $this->db->limit(1);
            $query = $this->db->get();
    
            if ($query->num_rows() == 0) {
                echo "<script>console.log('[" . __FUNCTION__ . "] No more insuffi stock to update.');</script>";
                break;
            }
    
            $row = $query->row();
            $availableQty = $row->insuffi_newqty;
            $insuffi_id = $row->insuffi_id;
    
            $deductQty = ($grnqty <= $availableQty) ? $grnqty : $availableQty;
    
            $this->db->set('insuffi_newqty', 'insuffi_newqty - ' . $deductQty, FALSE);
            $this->db->where('insuffi_id', $insuffi_id);
            $this->db->update('ezy_pos_insuffistocksale');
    
            if ($this->db->affected_rows() > 0) {
                $deductedTotal += $deductQty;
                $grnqty -= $deductQty;
                echo "<script>console.log('[" . __FUNCTION__ . "] Worked for insuffi_id: $insuffi_id | Deducted: $deductQty');</script>";
            } else {
                echo "<script>console.log('[" . __FUNCTION__ . "] Failed to update insuffi_id: $insuffi_id');</script>";
                break;
            }
        }
    
        echo "<script>console.log('[" . __FUNCTION__ . "] Total Deducted: $deductedTotal');</script>";
        return $deductedTotal;
    }



    public function updateInsuffiSoldQtyToZero($itmid,$oldstQty){
        $str = "UPDATE ezy_pos_insuffistocksale
        SET insuffi_newqty = 0,
            insuffi_grnstatus = 1,
            insuffi_salestatus = 1
        WHERE insuffi_itmid = '" . $itmid . "'
          AND insuffi_newqty > 0
        ORDER BY insuffi_id LIMIT 1";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }

    public function getOldestInsuffiSaleIDandQty($itmid){
        $str="SELECT insuffi_saleid,insuffi_newqty FROM ezy_pos_insuffistocksale 
        WHERE insuffi_itmid='".$itmid."' 
        AND insuffi_newqty>0 
        ORDER BY insuffi_id LIMIT 1";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function checkIsItInsuffiItem(){
        $saleID = $this->input->post('saleIDR');
        $rItmID = $this->input->post('rItmID');

        $str="SELECT insuffi_newqty
        FROM ezy_pos_insuffistocksale
        WHERE insuffi_saleid ='".$saleID."'
        AND insuffi_itmid ='".$rItmID."'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            $result= $query->row();
            return $result->insuffi_newqty;
        }
        else{
            return 0;
        }
    }
    public function returnUpdateInInsuffi(){
        $saleID = $this->input->post('saleIDR');
        $rItmID = $this->input->post('rItmID');
        $qty = $this->input->post('qty');

        $str="UPDATE ezy_pos_insuffistocksale
        SET insuffi_newqty=insuffi_newqty-'".$qty."'
        WHERE insuffi_saleid ='".$saleID."'
        AND insuffi_itmid ='".$rItmID."'
        ORDER BY insuffi_id LIMIT 1";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function returnUpdateInInsuffi2(){
        $saleID = $this->input->post('saleIDR');
        $rItmID = $this->input->post('rItmID');
    
        $str = "UPDATE ezy_pos_insuffistocksale
                SET insuffi_newqty = 0
                WHERE insuffi_saleid = '".$saleID."'
                AND insuffi_itmid = '".$rItmID."'
                ORDER BY insuffi_id
                LIMIT 1";
                
        $query = $this->db->query($str);
        
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}

