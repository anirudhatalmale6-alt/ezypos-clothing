<?php
class Payment_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    public function getCustomerPayments($cusid){
        $str ="SELECT sale_grandtotal,cus_name,sale_id,cashAmnt,paidDate,ChqAmnt,ChqGivenDate
        FROM ezy_pos_customers
        LEFT JOIN ezy_pos_sale ON ezy_pos_sale.sale_cus_id=ezy_pos_customers.cus_id
        LEFT JOIN (
            select pymntlog_saleid, GROUP_CONCAT(pymntlog_amount) as cashAmnt,GROUP_CONCAT(pymntlog_date) as paidDate
            from ezy_pos_cus_paymnt_log
            group by pymntlog_saleid
            ) ezy_pos_cus_paymnt_log ON ezy_pos_cus_paymnt_log.pymntlog_saleid=ezy_pos_sale.sale_id
        LEFT JOIN (
            select cus_cheque_saleid, GROUP_CONCAT(cus_cheque_amount) as ChqAmnt,GROUP_CONCAT(cus_cheque_givendate) as ChqGivenDate,
            GROUP_CONCAT(cus_cheque_status) as Chqstatus
            from ezy_pos_cus_cheque
            group by cus_cheque_saleid
        ) ezy_pos_cus_cheque ON ezy_pos_cus_cheque.cus_cheque_saleid=ezy_pos_sale.sale_id
        LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id
        WHERE cus_id='".$cusid."' AND cus_pay_credit>0
        ORDER BY sale_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result_array() ; // ()
        }
        else{
            //return false;
            return array(0);
        }
    }
    public function getCustomerPayments2($cusid){ 
        $str ="SELECT sale_grandtotal,cus_name,sale_id as saleid,chqlogAmnt,cus_cheque_givendate
        FROM ezy_pos_customers
        LEFT JOIN ezy_pos_sale ON ezy_pos_sale.sale_cus_id=ezy_pos_customers.cus_id        
        LEFT JOIN (
            select cheqlog_chequeid,cheqlog_saleid, GROUP_CONCAT(cheqlog_amount) as chqlogAmnt
            from ezy_pos_cus_chequelog
            group by cheqlog_saleid
            ) ezy_pos_cus_chequelog ON ezy_pos_cus_chequelog.cheqlog_saleid=ezy_pos_sale.sale_id
        LEFT JOIN  ezy_pos_cus_cheque ON ezy_pos_cus_cheque.cus_cheque_id=ezy_pos_cus_chequelog.cheqlog_chequeid
        LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id
        WHERE cus_id='".$cusid."' AND cus_pay_credit>0
        ORDER BY sale_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
            //return false;
            return array(0);
        }
    }
    
    
    public function CustomerOutstanding($cusid){ 
        $str ="SELECT    sum(cus_pay_credit) as Outstanding
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id
        WHERE sale_cus_id='".$cusid."'
        group by sale_cus_id";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row()->Outstanding;
        }
        else{
            return 0;
        }
    }
    
    public function CustomerBalance($cusid){ 
        $str ="SELECT cus_balance
        FROM ezy_pos_customers
        WHERE cus_id ='$cusid'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row()->cus_balance;
        }
        else{
            return 0;
        }
    }
    
    public function CustomerPayments($cusid){ 
        $str ="SELECT cus_balance
        FROM ezy_pos_customers
        WHERE cus_id ='$cusid'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row()->cus_balance;
        }
        else{
            return 0;
        }
    }
    
    
//    public function CustomerOutstanding($cusid){ 
//     //$str="SELECT sum(cus_pay_credit)+cus_balance as Outstanding FROM ezy_pos_customers LEFT JOIN ezy_pos_sale ON ezy_pos_customers.cus_id= ezy_pos_sale.sale_cus_id LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id WHERE ezy_pos_customers.cus_id='$cusid' group by ezy_pos_customers.cus_id";
//        
//        $str ="SELECT  sum(cus_pay_credit) as Outstanding
//        FROM ezy_pos_sale
//        LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id
//        WHERE sale_cus_id='".$cusid."'
//        group by sale_cus_id";        
//        $query = $this->db->query($str);
//        if($query->num_rows()>0){
//            return $query->row()->Outstanding;
//        }
//        else{
//            return false;
//        }
//    }
//
    public function getInvoiceByIDCash(){
        $invid = $this->input->post('invid');
        $str ="SELECT sale_grandtotal,cus_name,pymntlog_amount,pymntlog_date    
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_customers ON ezy_pos_customers.cus_id=ezy_pos_sale.sale_cus_id
        LEFT JOIN ezy_pos_cus_paymnt_log ON ezy_pos_cus_paymnt_log.pymntlog_saleid=ezy_pos_sale.sale_id
        WHERE sale_id='".$invid."'";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getInvoiceByIDCheq(){
        $invid = $this->input->post('invid');
        $str ="SELECT cus_cheque_amount,cus_cheque_givendate,cus_cheque_status    
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_cus_cheque ON ezy_pos_cus_cheque.cus_cheque_saleid=ezy_pos_sale.sale_id
        WHERE cus_cheque_saleid='".$invid."'";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getInvoiceOutstandingbyID(){
        $invid = $this->input->post('invid');
        $str ="SELECT cus_pay_cash,cus_pay_credit    
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id
        WHERE sale_id='".$invid."'";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function getInvoiceCheqTotalbyID(){
        $invid = $this->input->post('invid');
        $str ="SELECT sum(cus_cheque_amount) as chqtotal    
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_cus_cheque ON ezy_pos_cus_cheque.cus_cheque_saleid=ezy_pos_sale.sale_id
        WHERE sale_id='".$invid."'";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function getCusID(){
        $invid = $this->input->post('invid');
        $this->db->select('sale_cus_id');
        $this->db->from('ezy_pos_sale');
        $this->db->where('sale_id',$invid);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row()->sale_cus_id;
        }
        else{
            return false;
        }
    }
    //
    public function CusOutstandingByID(){
        $invid = $this->input->post('invid');
        $str ="SELECT sum(cus_pay_credit) as Outstanding    
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id
        WHERE sale_id='".$invid."'
        group by sale_cus_id";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row()->Outstanding;
        }
        else{
            return false;
        }
    }
/////wishwa
    public function getSupplierPayments3($supid){//new
        $str ="SELECT sup_name,grn_id,grn_code,chqAmnt,cashAmnt,ChqGivenDate,paidDate,sup_balance
        FROM ezy_pos_suppliers
        LEFT JOIN ezy_pos_grns ON ezy_pos_grns.grn_supplier_id=ezy_pos_suppliers.sup_id            
        LEFT JOIN (
            select supchqlog_grnid,GROUP_CONCAT(supchqlog_amnt) as chqAmnt,GROUP_CONCAT(supchqlog_givndate) as ChqGivenDate
            from ezy_pos_sup_chequelog
            group by supchqlog_grnid
            ) ezy_pos_sup_chequelog ON ezy_pos_sup_chequelog.supchqlog_grnid=ezy_pos_grns.grn_id
        LEFT JOIN (
            select paycashlog_grnid,GROUP_CONCAT(paycashlog_amount) as cashAmnt,GROUP_CONCAT(paycashlog_date) as paidDate
            from ezy_pos_sup_paymentcashlog
            group by paycashlog_grnid
            ) ezy_pos_sup_paymentcashlog ON ezy_pos_sup_paymentcashlog.paycashlog_grnid=ezy_pos_grns.grn_id
        LEFT JOIN ezy_pos_sup_payment ON ezy_pos_sup_payment.sup_pay_grnid=ezy_pos_grns.grn_id
        WHERE sup_id='".$supid."' AND sup_pay_credit>0
        ORDER BY grn_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
            return false;
        }
    }
    public function supplierOutstanding($supid){ // using in :Payments&SupplierPayment controllers
        $str ="SELECT sum(sup_pay_credit) as Outstanding
        FROM ezy_pos_grns
        LEFT JOIN ezy_pos_sup_payment ON ezy_pos_sup_payment.sup_pay_grnid=ezy_pos_grns.grn_id
        WHERE grn_supplier_id='".$supid."'
        group by grn_supplier_id";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row()->Outstanding;
        }
        else{
            return false;
        }
    }
    
    public function selectSupplierOutstanding($supid){ // 
        $str ="SELECT sup_balance
        FROM ezy_pos_suppliers
        WHERE sup_id='".$supid."'";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row()->sup_balance;
        }
        else{
            return false;
        }
    }
    
    public function supplier_for_payment($supid){ // using in :Payments&SupplierPayment controllers
        $str ="SELECT sup_balance
        FROM ezy_pos_suppliers
        WHERE sup_id='".$supid."'";        
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row()->sup_balance;
        }
        else{
            return false;
        }
    }

public function getGrnByIDCash(){ 
    $grnid = $this->input->post('grnid');
    $str ="SELECT grn_code,grn_grandtotal,sup_name,paycashlog_amount,paycashlog_date    
    FROM ezy_pos_grns
    LEFT JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id=ezy_pos_grns.grn_supplier_id
    LEFT JOIN ezy_pos_sup_paymentcashlog ON ezy_pos_sup_paymentcashlog.paycashlog_grnid=ezy_pos_grns.grn_id
    WHERE grn_id='".$grnid."'";        
    $query = $this->db->query($str);
    if($query->num_rows()>0){
        return $query->result();
    }
    else{
        return false;
    }
}
public function getGrnByIDCheq(){
    $grnid = $this->input->post('grnid');
    $str ="SELECT supchqlog_amnt,supchqlog_givndate    
    FROM ezy_pos_grns
    LEFT JOIN ezy_pos_sup_chequelog ON ezy_pos_sup_chequelog.supchqlog_grnid=ezy_pos_grns.grn_id
    WHERE supchqlog_grnid='".$grnid."'";        
    $query = $this->db->query($str);
    if($query->num_rows()>0){
        return $query->result();
    }
    else{
        return false;
    }
}
public function getGrnOutstandingbyID(){
    $grnid = $this->input->post('grnid');
    $str ="SELECT sup_pay_cash,sup_pay_credit    
    FROM ezy_pos_grns
    LEFT JOIN ezy_pos_sup_payment ON ezy_pos_sup_payment.sup_pay_grnid=ezy_pos_grns.grn_id
    WHERE grn_id='".$grnid."'";        
    $query = $this->db->query($str);
    if($query->num_rows()>0){
        return $query->row();
    }
    else{
        return false;
    }
}
public function getCheqAmontForGrn(){
    $grnid = $this->input->post('grnid');
    $str ="SELECT sum(supchqlog_amnt) as chqtotal    
    FROM ezy_pos_grns
    LEFT JOIN ezy_pos_sup_chequelog ON ezy_pos_sup_chequelog.supchqlog_grnid=ezy_pos_grns.grn_id
    WHERE grn_id='".$grnid."'";        
    $query = $this->db->query($str);
    if($query->num_rows()>0){
        return $query->row();
    }
    else{
        return false;
    }
}
public function getSupID(){
    $grnid = $this->input->post('grnid');
    $this->db->select('grn_supplier_id');
    $this->db->from('ezy_pos_grns');
    $this->db->where('grn_id',$grnid);
    $query = $this->db->get();
    if($query->num_rows()>0){
        return $query->row()->grn_supplier_id;
    }
    else{
        return false;
    }
}

//////////////




}

