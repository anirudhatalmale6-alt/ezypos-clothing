<?php
class Stocks_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function addItemtoStock(){ //from item add , 
        $data = array(
            'stock_itm_id' => $this->input->post('itmid'),            
            'stock_qty' => 0
        );
        return $this->db->insert('ezy_pos_stock', $data);
    }
    public function increaseStock(){    //  grn stock +
        $itmid = $this->input->post('itmid');
        $qty = $this->input->post('qty');
        $sql = "UPDATE ezy_pos_stock SET stock_qty=stock_qty +'".$qty."' WHERE stock_itm_id='".$itmid."'";
        $query = $this->db->query($sql);
        $num= $this->db->affected_rows();
        if($num>0){
            return true;
        }
        else{
            $data = array(
                'stock_itm_id'=>$itmid,
                'stock_qty'=>$qty,
                'stock_status'=>1
            );
            return $this->db->insert('ezy_pos_stock', $data);
        }       
    }
    public function decreaseStock(){  // sales stock -
        $itmid = $this->input->post('itmid');
        $qty = $this->input->post('qty');
        $sql1 = "SELECT * FROM  ezy_pos_stock WHERE stock_itm_id='".$itmid."'";
        $query1 = $this->db->query($sql1);
        if($query1->num_rows()==0){
         $data = array(
            'stock_itm_id' =>$itmid ,            
            'stock_qty' => 0
            );
          $this->db->insert('ezy_pos_stock', $data);
            }
        $sql = "UPDATE ezy_pos_stock SET stock_qty=stock_qty -'".$qty."' WHERE stock_itm_id='".$itmid."'";
        $query = $this->db->query($sql);
        $num= $this->db->affected_rows();
        if($num>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function stocklog(){ // grn,sales,retrn stocklog 
        $saleID=$this->input->post('saleID');//         sale
        $grnID=$this->input->post('grnID');//           grn
        $supReturnID=$this->input->post('supRID'); //   supRetrn 
        $retrnSupID=$this->input->post('supID');//   supRetrn
        $cusReturnID=$this->input->post('cusRID'); //   cusRetrn
        $retrnCusID=$this->input->post('cusID');//   cusRetrn
        if($saleID==''){$saleID=0;}
        if($grnID==''){$grnID=0;}
        if($supReturnID==''){$supReturnID=0;}
        if($retrnSupID==''){$retrnSupID=0;}
        if($cusReturnID==''){$cusReturnID=0;}
        if($retrnCusID==''){$retrnCusID=0;}
        $data = array(
            'stocklog_itmid'=>$this->input->post('itmid'),
            'stocklog_qty'=>$this->input->post('qty'),
            'stocklog_grnID'=>$grnID,
            'stocklog_saleID'=>$saleID,
            'stocklog_return_sup_retrnID'=>$supReturnID,
            'stocklog_return_supID'=>$retrnSupID,
            'stocklog_return_cus_retrnID'=>$cusReturnID,
            'stocklog_return_cusID'=>$retrnCusID,
            'stocklog_status'=>1
        );
        return $this->db->insert('ezy_pos_stock_log', $data);
    }
    
    public function getStockItmQty(){
        $itmID=$this->input->post('itmid');         
        $this->db->select('stock_qty');
        $this->db->where('stock_itm_id', $itmID);
        $query = $this->db->get('ezy_pos_stock');
        $result= $query->row();
        return $result->stock_qty;
//        return -1;
 
    }
    
//    public function getStockItmQty_W(){
//        $itmID=$this->input->post('itmid');  
//        $str="SELECT stock_qty FROM ezy_pos_stock WHERE stock_itm_id='$itmID' ";
//        $query = $this->db->query($str);
//        $row=$query->result_array();
//        return  $row['stock_qty'];
//
//    }
    public function showAllStock(){   
        $str="SELECT itm_id,itm_code,itm_name,itm_reorderlevel,itm_uom,stock_qty,SUM(`cur_grnPrice`*cur_currentQTY) AS grnValue,
         SUM(itm_sellingprice*cur_currentQTY) as sellingValue
        FROM ezy_pos_items
        INNER JOIN ezy_pos_stock ON ezy_pos_stock.stock_itm_id=ezy_pos_items.itm_id
        LEFT JOIN ezy_pos_currentqtywithgrn ON ezy_pos_currentqtywithgrn.cur_itmID=ezy_pos_items.itm_id
        WHERE stock_status=1
        AND itm_status=1
        GROUP BY itm_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        } 
    }
    
    public function showItemStock(){
        $item_id=$this->input->post('item_id');
        $str="SELECT itm_id,itm_code,itm_name,itm_reorderlevel,itm_uom,stock_qty,SUM(`cur_grnPrice`*cur_currentQTY) AS grnValue,
         SUM(itm_sellingprice*cur_currentQTY) as sellingValue
        FROM ezy_pos_items
        INNER JOIN ezy_pos_stock ON ezy_pos_stock.stock_itm_id=ezy_pos_items.itm_id
        LEFT JOIN ezy_pos_currentqtywithgrn ON ezy_pos_currentqtywithgrn.cur_itmID=ezy_pos_items.itm_id
        WHERE stock_status=1
        AND itm_status=1
        AND itm_id =".$item_id."
        GROUP BY itm_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }

    }
    public function getSupplierStock(){
        $str="SELECT itm_code,itm_name,sup_name,SUM(cur_currentQTY) as qty
        FROM ezy_pos_currentqtywithgrn
        INNER JOIN ezy_pos_grns ON ezy_pos_grns.grn_id=ezy_pos_currentqtywithgrn.cur_grnID
        INNER JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id=ezy_pos_grns.grn_supplier_id
        INNER JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_currentqtywithgrn.cur_itmID
        AND itm_status=1
        GROUP BY grn_supplier_id,itm_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    public function getSingleSupplierStock($sup_id){
        $str="SELECT itm_code,itm_name,sup_name,SUM(cur_currentQTY) as qty
        FROM ezy_pos_currentqtywithgrn
        INNER JOIN ezy_pos_grns ON ezy_pos_grns.grn_id=ezy_pos_currentqtywithgrn.cur_grnID
        INNER JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id=ezy_pos_grns.grn_supplier_id
        INNER JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_currentqtywithgrn.cur_itmID
        AND itm_status=1 AND grn_supplier_id= '$sup_id'
        GROUP BY grn_supplier_id,itm_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getStocklog(){
        $str="SELECT itm_code,itm_name,stocklog_qty,stocklog_createdat,
            stocklog_grnID,stocklog_saleID,stocklog_return_sup_retrnID,stocklog_return_cus_retrnID
        FROM ezy_pos_stock_log lg
        INNER JOIN ezy_pos_items ON ezy_pos_items.itm_id=lg.stocklog_itmid
        AND itm_status=1";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getSupplierReturn(){
        $str="SELECT itm_code,itm_name,stocklog_qty,sup_name,suprtrn_createdat
        FROM ezy_pos_stock_log
        INNER JOIN ezy_pos_sup_return ON ezy_pos_sup_return.suprtrn_id=ezy_pos_stock_log.stocklog_return_sup_retrnID
        LEFT JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_stock_log.stocklog_itmid

        LEFT JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id=ezy_pos_sup_return.suprtrn_supID
        AND itm_status=1";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    public function getGRNReport(){
        $str="SELECT g.grn_id,g.grn_code,g.grn_supplier_id,g.grn_grandtotal,g.grn_subtotal,g.grn_discount,g.grn_date,s.sup_name "
                . "FROM ezy_pos_grns g,ezy_pos_suppliers s WHERE g.grn_supplier_id=s.sup_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    public function getSaleReport(){
        $str="SELECT s.sale_id,s.sale_cus_id,s.sale_grandtotal,s.sale_subtotal,s.sale_discount,s.sale_createdat,c.cus_name "
                . "FROM ezy_pos_sale s,ezy_pos_customers c WHERE s.sale_cus_id=c.cus_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    
    public function getCustomerReturn(){
        $str="SELECT itm_code,itm_name,stocklog_qty,cus_name,cusrtrn_createdat
        FROM ezy_pos_stock_log
        INNER JOIN ezy_pos_cus_return ON ezy_pos_cus_return.cusrtrn_id=ezy_pos_stock_log.stocklog_return_cus_retrnID
        LEFT JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_stock_log.stocklog_itmid

        LEFT JOIN ezy_pos_customers ON ezy_pos_customers.cus_id=ezy_pos_cus_return.cusrtrn_cusID
        AND itm_status=1";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    
    public function filterStockLogs(){ //2018-08-04 10:08:36
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $start=$from." 00:00:00";
        $end=$to." 11:59:59";
        $str="SELECT itm_code,itm_name,stocklog_qty,stocklog_createdat,
        stocklog_grnID,stocklog_saleID,stocklog_return_sup_retrnID,stocklog_return_cus_retrnID
        FROM ezy_pos_stock_log lg
        INNER JOIN ezy_pos_items ON ezy_pos_items.itm_id=lg.stocklog_itmid
        WHERE stocklog_createdat BETWEEN '".$start."' AND '".$end."' 
        AND itm_status=1";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
       
        }
        else{
            return false;
        }
    }
    
    
    public function get_all_suppliers(){ //2018-08-04 10:08:36
         $this->db->select('*');
         $this->db->from('ezy_pos_suppliers');
         $this->db->order_by('sup_name',"asc");
         $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        else
        {
            return array();
        }
    }
    
    
 //----------------------------------------------------New----------------------------------------------------------

 
 // Get the stock quantity for a specific item
 public function getStockQuantityByItemId($itemId) {
    // If item_id is empty, return the total stock quantity of all items
    if (empty($itemId)) {
        $this->db->select_sum('stock_qty');
        $query = $this->db->get('ezy_pos_stock');
        return $query->row()->stock_qty;
    }

    // Get the stock quantity for a specific item
    $this->db->select_sum('stock_qty');
    $this->db->where('stock_itm_id', $itemId);
    $query = $this->db->get('ezy_pos_stock');
    return $query->row()->stock_qty;
}



public function showAllStockWithWarehouses() {
    $str = "SELECT 
                i.itm_id,
                i.itm_code,
                i.itm_name,
                i.itm_reorderlevel,
                i.itm_uom,
                s.stock_qty,
                SUM(c.cur_grnPrice * c.cur_currentQTY) AS grnValue,
                SUM(i.itm_sellingprice * c.cur_currentQTY) AS sellingValue,
                GROUP_CONCAT(DISTINCT w.wh_name SEPARATOR ', ') AS warehouse_names
            FROM 
                ezy_pos_items i
            INNER JOIN 
                ezy_pos_stock s ON s.stock_itm_id = i.itm_id
            LEFT JOIN 
                ezy_pos_currentqtywithgrn c ON c.cur_itmID = i.itm_id
            LEFT JOIN 
                ezy_pos_item_warehouse iw ON iw.ware_item_id = i.itm_id
            LEFT JOIN 
                ezy_pos_warehouse w ON w.wh_id = iw.ware_warehouse_id
            WHERE 
                s.stock_status = 1 AND 
                i.itm_status = 1
            GROUP BY 
                i.itm_id";

    $query = $this->db->query($str);
    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}





}



