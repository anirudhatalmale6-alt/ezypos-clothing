<?php
class Sales_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function getPrice(){
        $id = $this->input->post('itemid');
        $this->db->select('itm_sellingprice');
        $this->db->from('ezy_pos_items');
        $this->db->where('itm_id', $id);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }

    public function addSalePOST(){
        if(isset($_SESSION['userid'])){
            $userid = $_SESSION['userid'];
        }
        $data = array(
            'sale_cus_id'=>$this->input->post('cusID'),            
            'sale_grandtotal'=>$this->input->post('grandtotal'),
            'sale_subtotal'=>$this->input->post('subtotal'),
            'sale_discount'=>$this->input->post('invoiceDis'),
            'sale_less'=>0,
            'sale_createdby'=>$userid,
            'sale_date'=>$this->input->post('date'),
            'sale_location'=>$this->input->post('store'),
            'sale_status'=>1            
        );
        $this->db->insert('ezy_pos_sale', $data);
        $sale_id = $this->db->insert_id();
        return $sale_id;
    }
    public function addSaleItemPOST(){
        $data = array(
            'saleitem_sale_id' => $this->input->post('sale_ID'),
            'saleitem_item_id' => $this->input->post('itemid1'),            
            'saleitem_price' => $this->input->post('price'),
            'saleitem_quantity' => $this->input->post('quantity'),
            'saleitem_total' => $this->input->post('total'),
            'saleitem_discount' => $this->input->post('itmDis')         
        );
        return $this->db->insert('ezy_pos_sale_item', $data);
    }
    //get sale items details for a spesific sale
    public function invoicePreview2($saleid){
        $str ="SELECT itm_name,saleitem_price,saleitem_quantity,saleitem_discount,saleitem_total
                FROM ezy_pos_items
                INNER JOIN ezy_pos_sale_item ON ezy_pos_items.itm_id = ezy_pos_sale_item.saleitem_item_id
                WHERE saleitem_sale_id = '".$saleid."'";        
                $query = $this->db->query($str);
                if($query->num_rows()>0){
                    return $query->result();
                }
                else{
                    return false;
                }
    }
    public function getCustomer($saleid){
        $str ="SELECT cus_name, cus_address
                FROM ezy_pos_sale
                INNER JOIN ezy_pos_customers ON ezy_pos_customers.cus_id=ezy_pos_sale.sale_cus_id
                WHERE sale_id='".$saleid."'";        
                $query = $this->db->query($str);
                if($query->num_rows()>0){
                    return $query->row();
                }
                else{
                    return false;
                }
    }
    public function saleDetails($saleid){
        $str ="SELECT ezy_pos_sale.*,ezy_pos_stores.*
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_stores ON ezy_pos_stores.store_id=ezy_pos_sale.sale_location
        WHERE sale_id='".$saleid."'";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateSalesforCusReturn(){
        $sale_id = $this->input->post('saleIDR');
        $rttl = $this->input->post('rtrnTotal');
        $disc = $this->input->post('discnt');
        $subttl=$rttl*100/(100-$disc);
        $str="UPDATE ezy_pos_sale 
        SET sale_grandtotal=sale_grandtotal -'".$rttl."',
        sale_subtotal=sale_subtotal -'".$subttl."'
        WHERE sale_id='".$sale_id."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function updateSaleItemsforCusReturn(){
        $saleid = $this->input->post('saleID');
        $itmid = $this->input->post('itmid');
        $qty = $this->input->post('qty');

        $str="UPDATE ezy_pos_sale_item 
        SET saleitem_quantity=saleitem_quantity -'".$qty."'
        WHERE saleitem_sale_id='".$saleid."'
        AND saleitem_item_id='".$itmid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            $str2="UPDATE ezy_pos_sale_item 
            SET saleitem_total=saleitem_price*saleitem_quantity-saleitem_discount/100*saleitem_price*saleitem_quantity
            WHERE saleitem_sale_id='".$saleid."'
            AND saleitem_item_id='".$itmid."'";
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
    public function getInvoices(){
        $this->db->select('sale_id');
        $this->db->from('ezy_pos_sale');
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }


/*------------------Today's Total Sales In Dash Board--------------------------*/ 


    // Fetch all sales for today (Admin)
    public function getSalesByDate($date) {
        $this->db->select('*');
        $this->db->from('ezy_pos_sale');
        $this->db->where('sale_date', $date);  // Filter by today's date
        $query = $this->db->get();
        return $query->result();
    }

    // Fetch sales for today created by a specific user (User)
    public function getSalesByDateAndUser($date, $userId) {
        $this->db->select('*');
        $this->db->from('ezy_pos_sale');
        $this->db->where('sale_date', $date);  // Filter by today's date
        $this->db->where('sale_createdby', $userId);  // Filter by user ID
        $query = $this->db->get();
        return $query->result();
    }

    // Add this method to get the total sales for a specific date (Admin)
    public function getTotalSalesByDate($date) {
        $this->db->select_sum('sale_grandtotal');  // Sum up the total sales
        $this->db->from('ezy_pos_sale');
        $this->db->where('sale_date', $date);  // Filter by date
        $query = $this->db->get();
        return $query->row()->sale_grandtotal ;  // Return the total sales, or 0 if none
    }

    // ===================== PAYMENT METHODS =====================

    public function getAllPaymentMethods() {
        $this->db->order_by('pm_id');
        return $this->db->get('ezy_pos_payment_methods')->result();
    }

    public function getActivePaymentMethods() {
        $this->db->where('pm_status', 1);
        $this->db->order_by('pm_name');
        return $this->db->get('ezy_pos_payment_methods')->result();
    }

    public function getPaymentMethodById($id) {
        $this->db->where('pm_id', $id);
        return $this->db->get('ezy_pos_payment_methods')->row();
    }

    public function addPaymentMethod() {
        $data = array(
            'pm_name' => $this->input->post('name'),
            'pm_commission_pct' => $this->input->post('commission_pct'),
            'pm_commission_fixed' => $this->input->post('commission_fixed')
        );
        $this->db->insert('ezy_pos_payment_methods', $data);
        return $this->db->insert_id();
    }

    public function updatePaymentMethod() {
        $id = $this->input->post('id');
        $data = array(
            'pm_name' => $this->input->post('name'),
            'pm_commission_pct' => $this->input->post('commission_pct'),
            'pm_commission_fixed' => $this->input->post('commission_fixed'),
            'pm_status' => $this->input->post('status')
        );
        $this->db->where('pm_id', $id);
        return $this->db->update('ezy_pos_payment_methods', $data);
    }

    public function deletePaymentMethod($id) {
        $this->db->where('pm_id', $id);
        return $this->db->delete('ezy_pos_payment_methods');
    }

    public function updateSalePaymentMethod($sale_id, $pm_id, $commission) {
        $this->db->where('sale_id', $sale_id);
        return $this->db->update('ezy_pos_sale', array(
            'sale_payment_method' => $pm_id,
            'sale_commission' => $commission
        ));
    }

    public function calculateCommission($pm_id, $amount) {
        $method = $this->getPaymentMethodById($pm_id);
        if (!$method) return 0;
        $commission = ($amount * $method->pm_commission_pct / 100) + $method->pm_commission_fixed;
        return round($commission, 2);
    }
}

