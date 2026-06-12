<?php
class Report_model extends CI_Model {
    public function __construct()
    {
          $this->load->database();
    }

    // Helper: build store filter SQL for non-admin users
    protected function _storeFilter($column = 'sale_location'){
        $userrole = $this->session->userdata('userrole');
        if($userrole == 1) return ''; // admin sees all stores
        $userid = $this->session->userdata('userid');
        $q = $this->db->query("SELECT store_id FROM ezy_pos_user_store WHERE user_id='".intval($userid)."' AND user_store_status=1");
        $ids = array();
        foreach($q->result() as $r){ $ids[] = intval($r->store_id); }
        if(empty($ids)) return " AND $column = -1";
        return " AND $column IN (".implode(',', $ids).")";
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
    
    
        public function get_sale_Report_for_today_summary_total(){
        $sf = $this->_storeFilter('sale_location');
        $str="SELECT SUM(sale_grandtotal) AS sum_sale_grandtotal FROM ezy_pos_sale WHERE sale_date = CURDATE() AND sale_status='1'".$sf;
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            //return false;
            return $query->row();
        }
    }
    
        public function get_sale_Report_for_today_summary_cash(){
        $str="SELECT SUM(l.pymntlog_amount) AS sum_pymntlog_amount FROM ezy_pos_sale s,ezy_pos_cus_paymnt_log l WHERE s.sale_date = CURDATE() AND s.sale_id=l.pymntlog_saleid AND l.pymntlog_date=CURDATE() AND s.sale_status='1'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            //return false;
            return $query->row();
        }
    }
    
      
    
        public function get_sale_Report_for_today_summary_cheque(){
        $str="SELECT SUM(c.cus_cheque_amount) AS sum_cus_cheque_amount FROM ezy_pos_sale s,ezy_pos_cus_cheque c WHERE s.sale_date = CURDATE() AND s.sale_id=c.cus_cheque_saleid AND c.cus_cheque_givendate=CURDATE() AND s.sale_status='1'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            //return false;
            return $query->row();
        }
    }
    
    
    
    
    
    
    
    
      public function get_payment_Report_for_today_summary_cash(){
        $str="SELECT SUM(l.pymntlog_amount) AS sum_pymntlog_amount FROM ezy_pos_cus_paymnt_log l WHERE  l.pymntlog_date=CURDATE() ";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            //return false;
            return $query->row();
        }
    }
    
    
     public function get_payment_Report_for_today_summary_cheque(){
        $str="SELECT SUM(c.cus_cheque_amount) AS sum_cus_cheque_amount FROM ezy_pos_cus_cheque c WHERE  c.cus_cheque_givendate=CURDATE() ";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            //return false;
            return $query->row();
        }
    }
    
    
    
    
    
     public function load_all_products(){       
        $this->db->order_by('itm_name','ASC');
        $query = $this->db->get('ezy_pos_items');
        return $query->result_array();
    }
    
    
     public function load_all_customers(){       
        $this->db->order_by('cus_name','ASC');
        $query = $this->db->get('ezy_pos_customers');
        return $query->result_array();
    }
    
    
    
    
     public function get_purchase_Report(){
        $this->db->select('ezy_pos_grns.grn_code,ezy_pos_grns.grn_subtotal,ezy_pos_grns.grn_discount,ezy_pos_grns.grn_grandtotal,ezy_pos_grns.grn_date,ezy_pos_suppliers.sup_name'); 
        $this->db->from('ezy_pos_grns');
        $this->db->join('ezy_pos_suppliers','ezy_pos_grns.grn_supplier_id=ezy_pos_suppliers.sup_id');
        $this->db->order_by('grn_date','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    
     public function get_purchase_Report_by_dates(){ //2018-08-04 10:08:36
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $start=$from;
        $end=$to; 

        $this->db->select('ezy_pos_grns.grn_code,ezy_pos_grns.grn_subtotal,ezy_pos_grns.grn_discount,ezy_pos_grns.grn_grandtotal,ezy_pos_grns.grn_date,ezy_pos_suppliers.sup_name'); 
        $this->db->from('ezy_pos_grns');
        $this->db->where("ezy_pos_grns.grn_date BETWEEN '$start' AND '$end'");
        $this->db->join('ezy_pos_suppliers','ezy_pos_grns.grn_supplier_id=ezy_pos_suppliers.sup_id');
        $this->db->order_by('grn_date','DESC');
        $query = $this->db->get();
        
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
            return false;
        }
    }
    
    
     public function get_purchase_Report_by_month(){ //2018-08-04 10:08:36
        $selected_month=$this->input->post('selected_month');

        $this->db->select('ezy_pos_grns.grn_code,ezy_pos_grns.grn_subtotal,ezy_pos_grns.grn_discount,ezy_pos_grns.grn_grandtotal,ezy_pos_grns.grn_date,ezy_pos_suppliers.sup_name'); 
        $this->db->from('ezy_pos_grns');
        $this->db->where("ezy_pos_grns.grn_date like '%$selected_month%'");
        $this->db->join('ezy_pos_suppliers','ezy_pos_grns.grn_supplier_id=ezy_pos_suppliers.sup_id');
        $this->db->order_by('grn_date','DESC');
        $query = $this->db->get();
        
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
            return false;
        }
    }
    
    
     public function get_purchase_Report_for_today_summary_total_grn(){ 
        $this->db->select('SUM(ezy_pos_grns.grn_grandtotal) AS sum_grn_grandtotal'); 
        $this->db->from('ezy_pos_grns');
        $this->db->where("ezy_pos_grns.grn_date = CURDATE()");
        $this->db->join('ezy_pos_suppliers','ezy_pos_grns.grn_supplier_id=ezy_pos_suppliers.sup_id');
        $this->db->order_by('grn_date','DESC');
        $query = $this->db->get();
        
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    
     public function get_total_purchase_Report_for_today_summary(){ 
        $str="SELECT SUM(grn_grandtotal) AS sum_grn_grandtotal FROM ezy_pos_grns WHERE grn_date= CURDATE()";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return $query->row();
           
        }

    }
     public function get_purchase_Report_for_today_summary_cash(){ 
        $this->db->select('SUM(supcash_amount) AS sum_supcash_amount'); 
        $this->db->from('ezy_pos_sup_cash_payment');
        $this->db->where("supcash_date = CURDATE()"); 
        $query = $this->db->get();
        
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return $query->row();
           
        }

    }
    
     public function get_purchase_Report_for_today_summary_cheque(){ 
        $this->db->select('SUM(sup_cheque_amount) AS sum_sup_cheque_amount'); 
        $this->db->from('ezy_pos_sup_cheque');
        $this->db->where("sup_cheque_givendate = CURDATE()"); 
        $query = $this->db->get();
        
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false; 
        }
    }
    
      public function get_total_credit_purchase_Report_for_today_summary(){ 
        $str="SELECT SUM(sp.sup_pay_credit) AS sum_sup_pay_credit FROM ezy_pos_grns g INNER JOIN ezy_pos_sup_payment sp ON g.grn_id=sp.sup_pay_grnid WHERE g.grn_date= CURDATE()";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return $query->row();          
        }
    }
    
    
    
    
    
    
     public function get_expense_Report(){
        $this->db->select('ezy_pos_expense.expen_id,ezy_pos_expense.expen_description,ezy_pos_expense.expen_amount,ezy_pos_expense.expen_date,ezy_pos_expense_cat.expencat_catname'); 
        $this->db->from('ezy_pos_expense');
        $this->db->join('ezy_pos_expense_cat','ezy_pos_expense.expen_expencat_id=ezy_pos_expense_cat.expencat_id');
        $this->db->order_by('expen_createdat','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
 
     public function get_expense_Report_by_dates(){ //2018-08-04 10:08:36
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $start=$from;
        $end=$to; 
        $this->db->select('ezy_pos_expense.expen_id,ezy_pos_expense.expen_description,ezy_pos_expense.expen_amount,ezy_pos_expense.expen_date,ezy_pos_expense_cat.expencat_catname'); 
        $this->db->from('ezy_pos_expense');
        $this->db->where("ezy_pos_expense.expen_date BETWEEN '$start' AND '$end'");
        $this->db->join('ezy_pos_expense_cat','ezy_pos_expense.expen_expencat_id=ezy_pos_expense_cat.expencat_id');
        $this->db->order_by('expen_createdat','DESC');
        $query = $this->db->get();
        
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
            return false;
        }
    }
    
    
     public function get_expense_Report_by_month(){ //2018-08-04 10:08:36
        $selected_month=$this->input->post('selected_month');

        
        
        $this->db->select('ezy_pos_expense.expen_id,ezy_pos_expense.expen_description,ezy_pos_expense.expen_amount,ezy_pos_expense.expen_date,ezy_pos_expense_cat.expencat_catname'); 
        $this->db->from('ezy_pos_expense');
        $this->db->where("ezy_pos_expense.expen_date like '%$selected_month%'");
        $this->db->join('ezy_pos_expense_cat','ezy_pos_expense.expen_expencat_id=ezy_pos_expense_cat.expencat_id');
        $this->db->order_by('expen_createdat','DESC');
        $query = $this->db->get();
        
        
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
            return false;
        }
    }
    
    
     public function get_expense_Report_for_today(){ 
        $this->db->select('ezy_pos_expense.expen_id,ezy_pos_expense.expen_description,ezy_pos_expense.expen_amount,ezy_pos_expense.expen_date,ezy_pos_expense_cat.expencat_catname'); 
        $this->db->from('ezy_pos_expense');
        $this->db->where("ezy_pos_expense.expen_date = CURDATE()");
        $this->db->join('ezy_pos_expense_cat','ezy_pos_expense.expen_expencat_id=ezy_pos_expense_cat.expencat_id');
        $this->db->order_by('expen_createdat','DESC');
        $query = $this->db->get();
        
        
        if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
            return $query->result_array();
        }
    }
    
     public function get_expense_Report_for_today_summary_cash(){ 
        $this->db->select('SUM(ezy_pos_expense.expen_amount) AS expen_amount_sum'); 
        $this->db->from('ezy_pos_expense');
        $this->db->where("ezy_pos_expense.expen_date = CURDATE()");
        //$this->db->group_by('ezy_pos_expense.expen_date');
        $query = $this->db->get();
        
       $str="SELECT SUM(ezy_pos_expense.expen_amount) AS expen_amount_sum FROM ezy_pos_expense WHERE ezy_pos_expense.expen_date = CURDATE()";
        $query = $this->db->query($str); 
        if($query->num_rows()>0){
           return $query->row();
        }
        else{
           return $query->row();
        }
    }
    
     public function get_expense_Report_for_today_summary_cheque(){
        $str="SELECT SUM(ezy_pos_expense.expen_amount) AS expen_amount_cheque FROM ezy_pos_expense INNER JOIN ezy_pos_expen_cheque ON ezy_pos_expense.expen_id=ezy_pos_expen_cheque.expen_chq_expntblid WHERE ezy_pos_expense.expen_date = CURDATE()";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return $query->row();
        }
    }
    
     public function get_expense_Report_for_today_summary(){ 
        $this->db->select('SUM(ezy_pos_expense.expen_amount) AS expen_amount_sum'); 
        $this->db->from('ezy_pos_expense');
        $this->db->where("ezy_pos_expense.expen_date = CURDATE()");
        $this->db->group_by('ezy_pos_expense.expen_date');
        $query = $this->db->get();
        
        
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return $query->row();
        }
    }
    
    
    
    
    
    
    
    public function getSaleReport_by_users($cus_id){
        $str="SELECT s.sale_id,s.sale_cus_id,s.sale_grandtotal,s.sale_subtotal,s.sale_discount,s.sale_createdat,c.cus_name "
                . "FROM ezy_pos_sale s,ezy_pos_customers c WHERE s.sale_cus_id='$cus_id' AND  c.cus_id='$cus_id'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
        
    public function getSaleReport_for_month($date_signature){
        $str="SELECT SUM(s.sale_grandtotal) AS grandtotal , SUM(s.sale_subtotal) AS subtotal ,count(s.sale_id) sales_count ,MAX(sale_grandtotal) max_sale_grand,MIN(sale_grandtotal) min_sale_grand"
        . " FROM ezy_pos_sale s,ezy_pos_customers c WHERE s.sale_cus_id=c.cus_id AND s.sale_createdat LIKE '%$date_signature%' GROUP BY '$date_signature'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }

    }
        
   
    
    public function getSaleReport_by_users_dates(){ //2018-08-04 10:08:36
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $cus_id=$this->input->post('cus_id');
        $start=$from." 00:00:00";
        $end=$to." 23:59:59";
        $sf = $this->_storeFilter('s.sale_location');

        $str="SELECT s.sale_id,s.sale_cus_id,s.sale_grandtotal,s.sale_subtotal,s.sale_discount,s.sale_createdat,c.cus_name "
        . "FROM ezy_pos_sale s,ezy_pos_customers c WHERE s.sale_cus_id='$cus_id' AND c.cus_id='$cus_id' AND sale_createdat BETWEEN '".$start."' AND '".$end."' ".$sf;
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    public function filter_sales_log_by_dates(){ //2018-08-04 10:08:36
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $start=$from." 00:00:00";
        $end=$to." 23:59:59";
        $sf = $this->_storeFilter('s.sale_location');

        $str="SELECT s.sale_id,s.sale_cus_id,s.sale_grandtotal,s.sale_subtotal,s.sale_discount,s.sale_createdat,c.cus_name "
        . "FROM ezy_pos_sale s,ezy_pos_customers c WHERE s.sale_cus_id=c.cus_id AND sale_createdat BETWEEN '".$start."' AND '".$end."' ".$sf;
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    
    public function get_sales_Report_by_dates(){ //2020-09-14 10:08:36
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $start=$from." 00:00:00";
        $end=$to." 23:59:59";
        $sf = $this->_storeFilter('sale_location');

        $str="SELECT SUM(sale_grandtotal) AS sum_sale_grandtotal FROM ezy_pos_sale WHERE sale_date BETWEEN '".$start."' AND '".$end."' ".$sf;
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    
    public function get_sales_Report_by_month(){ //2020-09-14 10:08:36
        $selected_month=$this->input->post('selected_month');
        $sf = $this->_storeFilter('sale_location');
        $str="SELECT SUM(sale_grandtotal) AS sum_sale_grandtotal FROM ezy_pos_sale WHERE sale_date LIKE '%".$selected_month."%' ".$sf;
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    
    
    public function get_expenses_Report_by_dates(){ //2020-09-14 10:08:36
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $start=$from." 00:00:00";
        $end=$to." 23:59:59"; 
        
        $str="SELECT SUM(expen_amount) AS sum_expen_amount FROM ezy_pos_expense WHERE expen_date BETWEEN '".$start."' AND '".$end."' ";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function get_expenses_Report_by_month(){ //2020-09-14 10:08:36
       $selected_month=$this->input->post('selected_month');
        
        $str="SELECT SUM(expen_amount) AS sum_expen_amount FROM ezy_pos_expense WHERE expen_date LIKE '%".$selected_month."%'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    
    public function get_purchase_Report_by_dates2(){ //2020-09-14 10:08:36
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $start=$from." 00:00:00";
        $end=$to." 23:59:59";
        $sf = $this->_storeFilter('grn_location');

        $str="SELECT SUM(grn_grandtotal) AS sum_grn_grandtotal FROM ezy_pos_grns WHERE grn_date BETWEEN '".$start."' AND '".$end."' ".$sf;
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function get_purchase_Report_by_month2(){ //2020-09-14 10:08:36
        $selected_month=$this->input->post('selected_month');
        
        $str="SELECT SUM(grn_grandtotal) AS sum_grn_grandtotal FROM ezy_pos_grns WHERE grn_date LIKE '%".$selected_month."%' ";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
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
        $end=$to." 23:59:59";
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
    
    
    public function getPaymentMethodsReport($from, $to, $pm_id){
        $str = "SELECT s.sale_id, s.sale_date, s.sale_grandtotal, s.sale_commission,
                c.cus_name,
                sp.sp_pm_id, sp.sp_amount, sp.sp_commission,
                pm.pm_name
                FROM ezy_pos_sale s
                INNER JOIN ezy_pos_customers c ON s.sale_cus_id = c.cus_id
                INNER JOIN ezy_pos_sale_payments sp ON s.sale_id = sp.sp_sale_id
                INNER JOIN ezy_pos_payment_methods pm ON sp.sp_pm_id = pm.pm_id
                WHERE s.sale_status = '1'";

        if($from != '' && $to != ''){
            $str .= " AND s.sale_date BETWEEN '".$this->db->escape_str($from)."' AND '".$this->db->escape_str($to)."'";
        }
        if($pm_id != '' && $pm_id != 'all'){
            $str .= " AND sp.sp_pm_id = '".$this->db->escape_str($pm_id)."'";
        }
        $str .= " ORDER BY s.sale_date DESC, s.sale_id DESC";

        $query = $this->db->query($str);
        if($query->num_rows() > 0){
            return $query->result();
        } else {
            return array();
        }
    }

    public function getPaymentMethodsSummary($from, $to){
        $str = "SELECT pm.pm_id, pm.pm_name,
                COUNT(DISTINCT sp.sp_sale_id) AS sale_count,
                SUM(sp.sp_amount) AS total_amount,
                SUM(sp.sp_commission) AS total_commission
                FROM ezy_pos_sale_payments sp
                INNER JOIN ezy_pos_payment_methods pm ON sp.sp_pm_id = pm.pm_id
                INNER JOIN ezy_pos_sale s ON sp.sp_sale_id = s.sale_id
                WHERE s.sale_status = '1'";

        if($from != '' && $to != ''){
            $str .= " AND s.sale_date BETWEEN '".$this->db->escape_str($from)."' AND '".$this->db->escape_str($to)."'";
        }
        $str .= " GROUP BY pm.pm_id ORDER BY total_amount DESC";

        $query = $this->db->query($str);
        if($query->num_rows() > 0){
            return $query->result();
        } else {
            return array();
        }
    }

    public function getAllPaymentMethodsList(){
        $query = $this->db->get_where('ezy_pos_payment_methods', array('pm_status' => 1));
        return $query->result();
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


    // =========================================================================
    // CASH FLOW REPORT METHODS
    // =========================================================================

    /**
     * Get cash flow report - combines cash payments + third-party payment methods
     * @param string $from  Start date (Y-m-d)
     * @param string $to    End date (Y-m-d)
     * @param string $method  'all', 'cash', or a specific pm_id
     * @return array|false
     */
    public function getCashFlowReport($from, $to, $method = 'all'){
        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $sf    = $this->_storeFilter('s.sale_location');
        $results = array();

        // Cash payments
        if($method === 'all' || $method === 'cash'){
            $str = "SELECT s.sale_id, s.sale_date, c.cus_name, cp.cus_pay_cash, cp.cus_pay_credit
                    FROM ezy_pos_cus_payment cp
                    INNER JOIN ezy_pos_sale s ON s.sale_id = cp.cus_pay_saleid
                    LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                    WHERE s.sale_date BETWEEN ? AND ?
                    AND cp.cus_pay_cash > 0"
                    .$sf.
                    " ORDER BY s.sale_id DESC";
            $query = $this->db->query($str, array($start, $end));
            if($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $obj = new stdClass();
                    $obj->sale_id       = $row->sale_id;
                    $obj->sale_date     = $row->sale_date;
                    $obj->customer_name = $row->cus_name;
                    $obj->method_name   = 'Cash';
                    $obj->amount        = $row->cus_pay_cash;
                    $obj->type          = 'cash';
                    $results[] = $obj;
                }
            }
        }

        // Third-party payment method payments
        if($method !== 'cash' && $this->db->table_exists('ezy_pos_sale_payments') && $this->db->table_exists('ezy_pos_payment_methods')){
            $str = "SELECT sp.sp_amount, sp.sp_commission, sp.sp_card_ref, pm.pm_name,
                           s.sale_id, s.sale_date, c.cus_name
                    FROM ezy_pos_sale_payments sp
                    INNER JOIN ezy_pos_sale s ON s.sale_id = sp.sp_sale_id
                    LEFT JOIN ezy_pos_payment_methods pm ON pm.pm_id = sp.sp_pm_id
                    LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                    WHERE s.sale_date BETWEEN ? AND ?"
                    .$sf;

            $params = array($start, $end);

            if($method !== 'all'){
                $str .= " AND sp.sp_pm_id = ?";
                $params[] = intval($method);
            }

            $str .= " ORDER BY s.sale_id DESC";

            $query = $this->db->query($str, $params);
            if($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $obj = new stdClass();
                    $obj->sale_id       = $row->sale_id;
                    $obj->sale_date     = $row->sale_date;
                    $obj->customer_name = $row->cus_name;
                    $obj->method_name   = $row->pm_name;
                    $obj->amount        = $row->sp_amount;
                    $obj->type          = 'method';
                    $results[] = $obj;
                }
            }
        }

        if(count($results) > 0){
            return $results;
        }
        return false;
    }

    /**
     * Get cash flow summary - totals per payment method
     * @param string $from  Start date (Y-m-d)
     * @param string $to    End date (Y-m-d)
     * @return array|false
     */
    public function getCashFlowSummary($from, $to){
        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $sf    = $this->_storeFilter('s.sale_location');
        $results = array();

        // Total cash payments
        $str = "SELECT SUM(cp.cus_pay_cash) AS total_amount
                FROM ezy_pos_cus_payment cp
                INNER JOIN ezy_pos_sale s ON s.sale_id = cp.cus_pay_saleid
                WHERE s.sale_date BETWEEN ? AND ?
                AND cp.cus_pay_cash > 0"
                .$sf;
        $query = $this->db->query($str, array($start, $end));
        if($query->num_rows() > 0 && $query->row()->total_amount > 0){
            $obj = new stdClass();
            $obj->method_name   = 'Cash';
            $obj->total_amount  = $query->row()->total_amount;
            $results[] = $obj;
        }

        // Total cheque payments
        $str = "SELECT SUM(ch.cus_cheque_amount) AS total_amount
                FROM ezy_pos_cus_cheque ch
                INNER JOIN ezy_pos_sale s ON s.sale_id = ch.cus_cheque_saleid
                WHERE s.sale_date BETWEEN ? AND ?"
                .$sf;
        $query = $this->db->query($str, array($start, $end));
        if($query->num_rows() > 0 && $query->row()->total_amount > 0){
            $obj = new stdClass();
            $obj->method_name   = 'Cheque';
            $obj->total_amount  = $query->row()->total_amount;
            $results[] = $obj;
        }

        // Totals per third-party payment method
        if($this->db->table_exists('ezy_pos_sale_payments') && $this->db->table_exists('ezy_pos_payment_methods')){
            $str = "SELECT pm.pm_name, SUM(sp.sp_amount) AS total_amount
                    FROM ezy_pos_sale_payments sp
                    INNER JOIN ezy_pos_payment_methods pm ON pm.pm_id = sp.sp_pm_id
                    INNER JOIN ezy_pos_sale s ON s.sale_id = sp.sp_sale_id
                    WHERE s.sale_date BETWEEN ? AND ?"
                    .$sf.
                    " GROUP BY sp.sp_pm_id
                    ORDER BY total_amount DESC";
            $query = $this->db->query($str, array($start, $end));
            if($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $obj = new stdClass();
                    $obj->method_name   = $row->pm_name;
                    $obj->total_amount  = $row->total_amount;
                    $results[] = $obj;
                }
            }
        }

        if(count($results) > 0){
            return $results;
        }
        return false;
    }


    // =========================================================================
    // ITEM SALES REPORT METHODS
    // =========================================================================

    /**
     * Get item-wise sales report - aggregate sales by item
     * @param string $from  Start date (Y-m-d)
     * @param string $to    End date (Y-m-d)
     * @return array|false
     */
    public function getItemSalesReport($from, $to){
        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $sf    = $this->_storeFilter('s.sale_location');

        $str = "SELECT i.itm_id, i.itm_code, i.itm_name, c.cat_name,
                       COUNT(DISTINCT si.saleitem_sale_id) AS num_sales,
                       SUM(si.saleitem_quantity) AS total_qty,
                       SUM(si.saleitem_total) AS total_revenue
                FROM ezy_pos_sale_item si
                INNER JOIN ezy_pos_items i ON i.itm_id = si.saleitem_item_id
                LEFT JOIN ezy_pos_categories c ON c.cat_id = i.itm_category
                INNER JOIN ezy_pos_sale s ON s.sale_id = si.saleitem_sale_id
                WHERE s.sale_date BETWEEN ? AND ?
                AND s.sale_status = 1"
                .$sf.
                " GROUP BY i.itm_id
                ORDER BY total_revenue DESC";

        $query = $this->db->query($str, array($start, $end));
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }


    public function getItemFullReport($from, $to, $item_code = ''){
        $hasDateRange = ($from && $to && $from !== '' && $to !== '');
        $start = $hasDateRange ? $from . " 00:00:00" : '';
        $end   = $hasDateRange ? $to   . " 23:59:59" : '';
        $sf    = $this->_storeFilter('s.sale_location');

        $results = array();

        // Get all items (optionally filtered by code)
        $itemWhere = "WHERE i.itm_status = 1";
        $params = array();
        if($item_code && $item_code !== ''){
            $itemWhere .= " AND (i.itm_code LIKE ? OR i.itm_name LIKE ?)";
            $params[] = '%'.$item_code.'%';
            $params[] = '%'.$item_code.'%';
        }

        $str = "SELECT i.itm_id, i.itm_code, i.itm_name, c.cat_name, i.itm_sellingprice
                FROM ezy_pos_items i
                LEFT JOIN ezy_pos_categories c ON c.cat_id = i.itm_category
                ".$itemWhere."
                ORDER BY i.itm_name ASC";
        $query = $this->db->query($str, $params);
        if($query->num_rows() == 0) return false;

        $items = array();
        foreach($query->result() as $row){
            $items[$row->itm_id] = (object)array(
                'itm_id' => $row->itm_id,
                'itm_code' => $row->itm_code,
                'itm_name' => $row->itm_name,
                'cat_name' => $row->cat_name,
                'itm_sellingprice' => $row->itm_sellingprice,
                'num_sales' => 0,
                'sold_qty' => 0,
                'sale_revenue' => 0,
                'num_grns' => 0,
                'grn_qty' => 0,
                'grn_cost' => 0
            );
        }

        // Sales data
        $saleParams = array();
        $saleDateFilter = '';
        if($hasDateRange){
            $saleDateFilter = ' AND s.sale_date BETWEEN ? AND ?';
            $saleParams[] = $start;
            $saleParams[] = $end;
        }
        $str = "SELECT si.saleitem_item_id AS itm_id,
                       COUNT(DISTINCT si.saleitem_sale_id) AS num_sales,
                       SUM(si.saleitem_quantity) AS sold_qty,
                       SUM(si.saleitem_total) AS sale_revenue
                FROM ezy_pos_sale_item si
                INNER JOIN ezy_pos_sale s ON s.sale_id = si.saleitem_sale_id
                WHERE s.sale_status = 1"
                .$saleDateFilter.$sf.
                " GROUP BY si.saleitem_item_id";
        $q = $this->db->query($str, $saleParams);
        foreach($q->result() as $row){
            if(isset($items[$row->itm_id])){
                $items[$row->itm_id]->num_sales = $row->num_sales;
                $items[$row->itm_id]->sold_qty = $row->sold_qty;
                $items[$row->itm_id]->sale_revenue = $row->sale_revenue;
            }
        }

        // GRN data
        $grnParams = array();
        $grnDateFilter = '';
        if($hasDateRange){
            $grnDateFilter = ' AND g.grn_date BETWEEN ? AND ?';
            $grnParams[] = $start;
            $grnParams[] = $end;
        }
        $str = "SELECT gi.grnitem_itmid AS itm_id,
                       COUNT(DISTINCT gi.grnitem_grnid) AS num_grns,
                       SUM(gi.grnitem_quantity) AS grn_qty,
                       SUM(gi.grnitem_total) AS grn_cost
                FROM ezy_pos_grn_item gi
                INNER JOIN ezy_pos_grns g ON g.grn_id = gi.grnitem_grnid
                WHERE 1=1"
                .$grnDateFilter.
                " GROUP BY gi.grnitem_itmid";
        $q = $this->db->query($str, $grnParams);
        foreach($q->result() as $row){
            if(isset($items[$row->itm_id])){
                $items[$row->itm_id]->num_grns = $row->num_grns;
                $items[$row->itm_id]->grn_qty = $row->grn_qty;
                $items[$row->itm_id]->grn_cost = $row->grn_cost;
            }
        }

        // Filter out items with no activity if no search term
        if(!$item_code || $item_code === ''){
            foreach($items as $id => $itm){
                if($itm->num_sales == 0 && $itm->num_grns == 0){
                    unset($items[$id]);
                }
            }
        }

        return array_values($items);
    }

    // =========================================================================
    // PRODUCTION & TAILORING REPORT METHODS
    // =========================================================================

    /**
     * Get production orders report
     * @param string $from    Start date (Y-m-d)
     * @param string $to      End date (Y-m-d)
     * @param string $status  'all' or status code (0=draft, 1=in_progress, 2=completed, 3=cancelled)
     * @return array|false
     */
    public function getProductionReport($from, $to, $status = 'all'){
        if(!$this->db->table_exists('ezy_pos_production')){
            return false;
        }

        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $sf    = $this->_storeFilter('p.prod_store_id');

        $str = "SELECT p.*, i.itm_name, i.itm_code,
                       sup.sup_name AS tailor_name,
                       u.user_name AS created_by
                FROM ezy_pos_production p
                LEFT JOIN ezy_pos_items i ON i.itm_id = p.prod_output_item_id
                LEFT JOIN ezy_pos_suppliers sup ON sup.sup_id = p.prod_tailor_id
                LEFT JOIN ezy_pos_users u ON u.user_id = p.prod_createdby
                WHERE p.prod_date BETWEEN ? AND ?"
                .$sf;

        $params = array($start, $end);

        if($status !== 'all'){
            $str .= " AND p.prod_status = ?";
            $params[] = $status;
        }

        $str .= " ORDER BY p.prod_date DESC";

        $query = $this->db->query($str, $params);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    /**
     * Get production summary - counts and totals by status
     * @param string $from  Start date (Y-m-d)
     * @param string $to    End date (Y-m-d)
     * @return array|false
     */
    public function getProductionSummary($from, $to){
        if(!$this->db->table_exists('ezy_pos_production')){
            return false;
        }

        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $sf    = $this->_storeFilter('p.prod_store_id');

        $str = "SELECT p.prod_status,
                       COUNT(*) AS order_count,
                       SUM(p.prod_output_qty) AS total_qty,
                       SUM(p.prod_total_cost) AS total_cost
                FROM ezy_pos_production p
                WHERE p.prod_date BETWEEN ? AND ?"
                .$sf.
                " GROUP BY p.prod_status
                ORDER BY p.prod_status ASC";

        $query = $this->db->query($str, array($start, $end));
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    /**
     * Get tailoring orders report
     * @param string $from    Start date (Y-m-d)
     * @param string $to      End date (Y-m-d)
     * @param string $status  'all' or status code
     * @return array|false
     */
    public function getTailoringOrdersReport($from, $to, $status = 'all'){
        if(!$this->db->table_exists('ezy_pos_production_sale')){
            return false;
        }

        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $sf    = $this->_storeFilter('ps.prodsale_store_id');

        $str = "SELECT ps.*,
                       c.cus_name,
                       st.store_name,
                       u.user_name AS created_by
                FROM ezy_pos_production_sale ps
                LEFT JOIN ezy_pos_customers c ON c.cus_id = ps.prodsale_cus_id
                LEFT JOIN ezy_pos_stores st ON st.store_id = ps.prodsale_store_id
                LEFT JOIN ezy_pos_users u ON u.user_id = ps.prodsale_createdby
                WHERE ps.prodsale_date BETWEEN ? AND ?"
                .$sf;

        $params = array($start, $end);

        if($status !== 'all'){
            $str .= " AND ps.prodsale_status = ?";
            $params[] = $status;
        }

        $str .= " ORDER BY ps.prodsale_date DESC";

        $query = $this->db->query($str, $params);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    /**
     * Get tailoring summary - counts and totals by status
     * @param string $from  Start date (Y-m-d)
     * @param string $to    End date (Y-m-d)
     * @return array|false
     */
    public function getTailoringSummary($from, $to){
        if(!$this->db->table_exists('ezy_pos_production_sale')){
            return false;
        }

        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $sf    = $this->_storeFilter('ps.prodsale_store_id');

        $str = "SELECT ps.prodsale_status,
                       COUNT(*) AS order_count,
                       SUM(ps.prodsale_total) AS total_amount
                FROM ezy_pos_production_sale ps
                WHERE ps.prodsale_date BETWEEN ? AND ?"
                .$sf.
                " GROUP BY ps.prodsale_status
                ORDER BY ps.prodsale_status ASC";

        $query = $this->db->query($str, array($start, $end));
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }


    // =========================================================================
    // RETURNS SUMMARY FOR TODAY / DATE RANGE
    // =========================================================================

    public function getReturnsTotalForToday(){
        if(!$this->db->table_exists('ezy_pos_returns')){
            $obj = new stdClass();
            $obj->total_returns = 0;
            $obj->return_count = 0;
            return $obj;
        }
        $str = "SELECT COALESCE(SUM(ret_net_amount),0) AS total_returns, COUNT(*) AS return_count
                FROM ezy_pos_returns
                WHERE DATE(ret_created_at) = CURDATE() AND ret_status = 1";
        $query = $this->db->query($str);
        return $query->row();
    }

    public function getReturnsTotalByDates($from, $to){
        if(!$this->db->table_exists('ezy_pos_returns')){
            $obj = new stdClass();
            $obj->total_returns = 0;
            $obj->return_count = 0;
            return $obj;
        }
        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $str = "SELECT COALESCE(SUM(ret_net_amount),0) AS total_returns, COUNT(*) AS return_count
                FROM ezy_pos_returns
                WHERE ret_created_at BETWEEN ? AND ? AND ret_status = 1";
        $query = $this->db->query($str, array($start, $end));
        return $query->row();
    }

    public function getTodaySummaryByDates($from, $to){
        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $sf    = $this->_storeFilter('sale_location');

        $result = array();

        // Sales total
        $str = "SELECT COALESCE(SUM(sale_grandtotal),0) AS total FROM ezy_pos_sale WHERE sale_date BETWEEN ? AND ? AND sale_status='1'".$sf;
        $q = $this->db->query($str, array($start, $end));
        $result['sale_total'] = $q->row()->total;

        // Sales cash
        $str = "SELECT COALESCE(SUM(l.pymntlog_amount),0) AS total FROM ezy_pos_sale s, ezy_pos_cus_paymnt_log l WHERE s.sale_id=l.pymntlog_saleid AND l.pymntlog_date BETWEEN ? AND ? AND s.sale_status='1'".$sf;
        $q = $this->db->query($str, array($from, $to));
        $result['sale_cash'] = $q->row()->total;

        // Sales cheque
        $str = "SELECT COALESCE(SUM(c.cus_cheque_amount),0) AS total FROM ezy_pos_sale s, ezy_pos_cus_cheque c WHERE s.sale_id=c.cus_cheque_saleid AND c.cus_cheque_givendate BETWEEN ? AND ? AND s.sale_status='1'".$sf;
        $q = $this->db->query($str, array($from, $to));
        $result['sale_cheque'] = $q->row()->total;

        // Purchase total
        $str = "SELECT COALESCE(SUM(grn_grandtotal),0) AS total FROM ezy_pos_grns WHERE grn_date BETWEEN ? AND ?";
        $q = $this->db->query($str, array($start, $end));
        $result['purchase_total'] = $q->row()->total;

        // Purchase cash
        $str = "SELECT COALESCE(SUM(supcash_amount),0) AS total FROM ezy_pos_sup_cash_payment WHERE supcash_date BETWEEN ? AND ?";
        $q = $this->db->query($str, array($from, $to));
        $result['purchase_cash'] = $q->row()->total;

        // Purchase cheque
        $str = "SELECT COALESCE(SUM(sup_cheque_amount),0) AS total FROM ezy_pos_sup_cheque WHERE sup_cheque_givendate BETWEEN ? AND ?";
        $q = $this->db->query($str, array($from, $to));
        $result['purchase_cheque'] = $q->row()->total;

        // Purchase credit
        $str = "SELECT COALESCE(SUM(sp.sup_pay_credit),0) AS total FROM ezy_pos_grns g INNER JOIN ezy_pos_sup_payment sp ON g.grn_id=sp.sup_pay_grnid WHERE g.grn_date BETWEEN ? AND ?";
        $q = $this->db->query($str, array($start, $end));
        $result['purchase_credit'] = $q->row()->total;

        // Expense total & cash
        $str = "SELECT COALESCE(SUM(expen_amount),0) AS total FROM ezy_pos_expense WHERE expen_date BETWEEN ? AND ?";
        $q = $this->db->query($str, array($start, $end));
        $result['expense_total'] = $q->row()->total;

        // Expense cheque
        $str = "SELECT COALESCE(SUM(e.expen_amount),0) AS total FROM ezy_pos_expense e INNER JOIN ezy_pos_expen_cheque ec ON e.expen_id=ec.expen_chq_expntblid WHERE e.expen_date BETWEEN ? AND ?";
        $q = $this->db->query($str, array($start, $end));
        $result['expense_cheque'] = $q->row()->total;

        // Payments received cash
        $str = "SELECT COALESCE(SUM(pymntlog_amount),0) AS total FROM ezy_pos_cus_paymnt_log WHERE pymntlog_date BETWEEN ? AND ?";
        $q = $this->db->query($str, array($from, $to));
        $result['payment_cash'] = $q->row()->total;

        // Payments received cheque
        $str = "SELECT COALESCE(SUM(cus_cheque_amount),0) AS total FROM ezy_pos_cus_cheque WHERE cus_cheque_givendate BETWEEN ? AND ?";
        $q = $this->db->query($str, array($from, $to));
        $result['payment_cheque'] = $q->row()->total;

        // Returns
        $retData = $this->getReturnsTotalByDates($from, $to);
        $result['returns_total'] = $retData->total_returns;
        $result['returns_count'] = $retData->return_count;

        return $result;
    }

}



