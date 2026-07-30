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
    // GRN header for the print view (single GRN by id)
    public function getGrnHeader($id){
        $str ="SELECT g.*, IFNULL(s.sup_name,'') AS sup_name, IFNULL(s.sup_contact,'') AS sup_contact,
               IFNULL(st.store_name,'N/A') AS store_name, IFNULL(u.user_name,'') AS user_name,
               sp.sup_pay_cash, sp.sup_pay_credit
               FROM ezy_pos_grns g
               LEFT JOIN ezy_pos_suppliers s ON s.sup_id=g.grn_supplier_id
               LEFT JOIN ezy_pos_stores st ON st.store_id=g.grn_location
               LEFT JOIN ezy_pos_users u ON u.user_id=g.grn_createdby
               LEFT JOIN ezy_pos_sup_payment sp ON sp.sup_pay_grnid=g.grn_id
               WHERE g.grn_id=? ORDER BY g.grn_id LIMIT 1";
        return $this->db->query($str, array($id))->row();
    }
    // GRN line items for the print view (single GRN by id)
    public function getGrnItemsById($id){
        $str ="SELECT grnitm_price,grnitm_quantity,grnitm_discount,grnitm_total,itm_name,itm_code
               FROM ezy_pos_grn_item
               LEFT JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_grn_item.grnitm_itemid
               WHERE grnitm_grn_id=? ORDER BY grnitm_id";
        return $this->db->query($str, array($id))->result();
    }
    public function editGrn(){
        $grndid = intval($this->input->post('id'));
        $str ="SELECT g.grn_id, g.grn_subtotal, g.grn_discount, g.grn_grandtotal, g.grn_date,
        s.sup_id, s.sup_name, sp.sup_pay_cash, sp.sup_pay_credit
        FROM ezy_pos_grns g
        LEFT JOIN ezy_pos_suppliers s ON s.sup_id=g.grn_supplier_id
        LEFT JOIN ezy_pos_sup_payment sp ON sp.sup_pay_grnid=g.grn_id
        WHERE g.grn_id=? ORDER BY g.grn_id LIMIT 1";
        $row = $this->db->query($str, array($grndid))->row();
        if($row){
            // Discount type defaults to percentage (the GRN creation default)
            $row->grn_discount_type = 'percentage';
            if(in_array('grn_discount_type', $this->db->list_fields('ezy_pos_grns'))){
                $dt = $this->db->query("SELECT grn_discount_type FROM ezy_pos_grns WHERE grn_id=?", array($grndid))->row();
                if($dt && $dt->grn_discount_type){ $row->grn_discount_type = $dt->grn_discount_type; }
            }
            return $row;
        }
        return false;
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
    // Full GRN edit: header + editable item list (add / change qty, cost, discount / remove)
    // with FIFO layer + per-store stock reconciliation. Returns array(status,message).
    public function updateGrnFull(){
        $grnid = intval($this->input->post('hiddngrnID'));
        $supplier = intval($this->input->post('grnsupplier'));
        $cash = floatval($this->input->post('Edit_cash'));
        $discount = floatval($this->input->post('Edit_discount'));
        $date = $this->input->post('Edit_date');
        $itemsJson = $this->input->post('items');
        $items = json_decode($itemsJson, true);
        if(!is_array($items)){ $items = array(); }

        if($grnid <= 0){ return array('status'=>'error','message'=>'Invalid GRN.'); }

        // GRN store + current supplier/credit (for balance reconciliation)
        $hdr = $this->db->query("SELECT grn_location, grn_supplier_id FROM ezy_pos_grns WHERE grn_id=?", array($grnid))->row();
        if(!$hdr){ return array('status'=>'error','message'=>'GRN not found.'); }
        $store = intval($hdr->grn_location);
        $oldSupplier = intval($hdr->grn_supplier_id);
        $oldPay = $this->db->query("SELECT sup_pay_credit FROM ezy_pos_sup_payment WHERE sup_pay_grnid=? LIMIT 1", array($grnid))->row();
        $oldCredit = $oldPay ? floatval($oldPay->sup_pay_credit) : 0;
        $hasItmDisType = in_array('grnitm_discount_type', $this->db->list_fields('ezy_pos_grn_item'));

        // ---------- Pass 1: validate everything before mutating ----------
        foreach($items as $it){
            $gid   = intval(isset($it['gid'])?$it['gid']:0);
            $itmid = intval(isset($it['itmid'])?$it['itmid']:0);
            $qty   = floatval(isset($it['qty'])?$it['qty']:0);
            $del   = !empty($it['del']);
            if($gid > 0){
                // Existing line — check how much is already consumed from its FIFO layer
                $lay = $this->db->query("SELECT cur_grnQty, cur_currentQTY FROM ezy_pos_currentqtywithgrn WHERE cur_grnID=? AND cur_itmID=? AND cur_store_id=? LIMIT 1", array($grnid,$itmid,$store))->row();
                $received = $lay ? floatval($lay->cur_grnQty) : 0;
                $remaining = $lay ? floatval($lay->cur_currentQTY) : 0;
                $consumed = $received - $remaining;
                if($del){
                    if($consumed > 0.0001){
                        return array('status'=>'error','message'=>'Cannot remove an item that has already been sold/used ('.$consumed.' units gone). Adjust quantity instead.');
                    }
                } else {
                    if($qty <= 0){ return array('status'=>'error','message'=>'Quantity must be greater than 0. Use Remove to delete a line.'); }
                    if($qty < $consumed - 0.0001){
                        return array('status'=>'error','message'=>'New quantity ('.$qty.') is less than the quantity already sold/used ('.$consumed.') for one of the items.');
                    }
                }
            } else if(!$del) {
                if($itmid <= 0){ return array('status'=>'error','message'=>'Please pick a valid item for every new row.'); }
                if($qty <= 0){ return array('status'=>'error','message'=>'New item quantity must be greater than 0.'); }
            }
        }

        // ---------- Pass 2: apply ----------
        foreach($items as $it){
            $gid   = intval(isset($it['gid'])?$it['gid']:0);
            $itmid = intval(isset($it['itmid'])?$it['itmid']:0);
            $price = floatval(isset($it['price'])?$it['price']:0);
            $qty   = floatval(isset($it['qty'])?$it['qty']:0);
            $dis   = floatval(isset($it['dis'])?$it['dis']:0);
            $del   = !empty($it['del']);
            $total = ($price * $qty) - $dis;
            if($total < 0){ $total = 0; }

            if($gid > 0 && $del){
                // Remove line (validated: nothing consumed)
                $lay = $this->db->query("SELECT cur_grnQty FROM ezy_pos_currentqtywithgrn WHERE cur_grnID=? AND cur_itmID=? AND cur_store_id=? LIMIT 1", array($grnid,$itmid,$store))->row();
                $received = $lay ? floatval($lay->cur_grnQty) : 0;
                $this->db->query("DELETE FROM ezy_pos_grn_item WHERE grnitm_id=?", array($gid));
                $this->db->query("DELETE FROM ezy_pos_currentqtywithgrn WHERE cur_grnID=? AND cur_itmID=? AND cur_store_id=?", array($grnid,$itmid,$store));
                $this->_adjustStock($itmid, $store, -$received);
                $this->_stockLog($itmid, $store, -$received, $grnid);
            }
            else if($gid > 0){
                // Update existing line
                $lay = $this->db->query("SELECT cur_grnQty, cur_currentQTY FROM ezy_pos_currentqtywithgrn WHERE cur_grnID=? AND cur_itmID=? AND cur_store_id=? LIMIT 1", array($grnid,$itmid,$store))->row();
                $oldQty = $lay ? floatval($lay->cur_grnQty) : 0;
                $remaining = $lay ? floatval($lay->cur_currentQTY) : 0;
                $delta = $qty - $oldQty;
                // grn_item
                $set = "grnitm_price='".$price."', grnitm_quantity='".$qty."', grnitm_total='".$total."', grnitm_discount='".$dis."'";
                if($hasItmDisType && isset($it['distype'])){ $set .= ", grnitm_discount_type='".$this->db->escape_str($it['distype'])."'"; }
                $this->db->query("UPDATE ezy_pos_grn_item SET ".$set." WHERE grnitm_id=".$gid);
                // FIFO layer: keep already-consumed portion, apply delta to remaining
                $this->db->query("UPDATE ezy_pos_currentqtywithgrn SET cur_grnPrice='".$price."', cur_grnQty='".$qty."', cur_grnTotal='".$total."', cur_currentQTY='".($remaining + $delta)."' WHERE cur_grnID=".$grnid." AND cur_itmID=".$itmid." AND cur_store_id=".$store);
                if(abs($delta) > 0.0001){
                    $this->_adjustStock($itmid, $store, $delta);
                    $this->_stockLog($itmid, $store, $delta, $grnid);
                }
            }
            else if(!$del){
                // New line
                $disType = ($hasItmDisType && isset($it['distype'])) ? $this->db->escape_str($it['distype']) : 'amount';
                $cols = "grnitm_grn_id, grnitm_itemid, grnitm_price, grnitm_quantity, grnitm_total, grnitm_discount";
                $vals = "'".$grnid."','".$itmid."','".$price."','".$qty."','".$total."','".$dis."'";
                if($hasItmDisType){ $cols .= ", grnitm_discount_type"; $vals .= ",'".$disType."'"; }
                $this->db->query("INSERT INTO ezy_pos_grn_item (".$cols.") VALUES (".$vals.")");
                $this->db->query("INSERT INTO ezy_pos_currentqtywithgrn (cur_grnID, cur_itmID, cur_store_id, cur_grnQty, cur_grnPrice, cur_grnTotal, cur_currentQTY, cur_status) VALUES ('".$grnid."','".$itmid."','".$store."','".$qty."','".$price."','".$total."','".$qty."',1)");
                $this->_adjustStock($itmid, $store, $qty);
                $this->_stockLog($itmid, $store, $qty, $grnid);
            }
        }

        // ---------- Header totals + payment + supplier balance ----------
        $sub = $this->db->query("SELECT IFNULL(SUM(grnitm_total),0) AS s FROM ezy_pos_grn_item WHERE grnitm_grn_id=?", array($grnid))->row();
        $subtotal = floatval($sub->s);
        $discType = $this->input->post('Edit_disc_type');
        if(!$discType){ $discType = 'percentage'; }
        if($discType == 'flat'){
            $grandtotal = $subtotal - $discount;
        } else {
            $grandtotal = (100 - $discount) * $subtotal / 100;
        }
        if($grandtotal < 0){ $grandtotal = 0; }
        $newCredit = $grandtotal - $cash;

        $discTypeSet = '';
        if(in_array('grn_discount_type', $this->db->list_fields('ezy_pos_grns'))){
            $discTypeSet = ", grn_discount_type='".$this->db->escape_str($discType)."'";
        }
        $this->db->query("UPDATE ezy_pos_grns SET grn_supplier_id='".$supplier."', grn_subtotal='".$subtotal."', grn_discount='".$discount."', grn_grandtotal='".$grandtotal."'".$discTypeSet.", grn_date='".$this->db->escape_str($date)."' WHERE grn_id='".$grnid."'");
        // sup_payment row (create if missing)
        $payExists = $this->db->query("SELECT 1 FROM ezy_pos_sup_payment WHERE sup_pay_grnid=? LIMIT 1", array($grnid))->num_rows();
        if($payExists){
            $this->db->query("UPDATE ezy_pos_sup_payment SET sup_pay_cash='".$cash."', sup_pay_credit='".$newCredit."' WHERE sup_pay_grnid='".$grnid."'");
        } else {
            $this->db->query("INSERT INTO ezy_pos_sup_payment (sup_pay_grnid, sup_pay_cash, sup_pay_credit) VALUES ('".$grnid."','".$cash."','".$newCredit."')");
        }
        // Supplier balance: back out old credit from old supplier, apply new credit to new supplier
        if($supplier == $oldSupplier){
            $diff = $newCredit - $oldCredit;
            if(abs($diff) > 0.0001){
                $this->db->query("UPDATE ezy_pos_suppliers SET sup_balance=sup_balance+'".$diff."' WHERE sup_id='".$supplier."'");
            }
        } else {
            $this->db->query("UPDATE ezy_pos_suppliers SET sup_balance=sup_balance-'".$oldCredit."' WHERE sup_id='".$oldSupplier."'");
            $this->db->query("UPDATE ezy_pos_suppliers SET sup_balance=sup_balance+'".$newCredit."' WHERE sup_id='".$supplier."'");
        }

        return array('status'=>'success','message'=>'GRN updated.','subtotal'=>$subtotal,'grandtotal'=>$grandtotal,'credit'=>$newCredit);
    }

    // Upsert per-store stock by a (possibly negative) delta.
    private function _adjustStock($itmid, $store, $delta){
        $n = $this->db->query("UPDATE ezy_pos_stock SET stock_qty=stock_qty+'".$delta."' WHERE stock_itm_id='".$itmid."' AND stock_store_id='".$store."'");
        if($this->db->affected_rows() <= 0){
            // no row matched — check existence, insert if brand new
            $exists = $this->db->query("SELECT 1 FROM ezy_pos_stock WHERE stock_itm_id=? AND stock_store_id=? LIMIT 1", array($itmid,$store))->num_rows();
            if(!$exists){
                $this->db->query("INSERT INTO ezy_pos_stock (stock_itm_id, stock_store_id, stock_qty, stock_status) VALUES ('".$itmid."','".$store."','".$delta."',1)");
            }
        }
    }
    private function _stockLog($itmid, $store, $qty, $grnid){
        $this->db->query("INSERT INTO ezy_pos_stock_log (stocklog_itmid, stocklog_store_id, stocklog_qty, stocklog_grnID, stocklog_status) VALUES ('".$itmid."','".$store."','".$qty."','".$grnid."',1)");
    }

    // Items of a GRN including their row id + item id (for the editable modal)
    public function getGrnItemsForEdit($grnid){
        $str = "SELECT gi.grnitm_id, gi.grnitm_itemid, gi.grnitm_price, gi.grnitm_quantity, gi.grnitm_discount, gi.grnitm_total,
                i.itm_name, i.itm_code
                FROM ezy_pos_grn_item gi
                LEFT JOIN ezy_pos_items i ON i.itm_id=gi.grnitm_itemid
                WHERE gi.grnitm_grn_id=? ORDER BY gi.grnitm_id";
        return $this->db->query($str, array($grnid))->result();
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