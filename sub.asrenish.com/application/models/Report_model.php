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

    /**
     * Store filter that also honours an explicit branch chosen in a report dropdown.
     * A chosen branch always narrows further - a non-admin can never widen their
     * access by picking a store they are not assigned to.
     */
    /**
     * Change handed back to the customer on a cash sale.
     *
     * Everything the customer put on the counter (cash, cheque, card, gift
     * voucher) minus what the bill came to. Anything left over went back to
     * them as cash out of the till.
     *
     * Returns 0 for a credit sale (nothing was overpaid, the balance is owed)
     * and for any bill that has since been returned or exchanged, because a
     * return lowers sale_grandtotal and the difference would look like change
     * that was never actually given.
     */
    protected function _changeGivenOnSale($r)
    {
        if(isset($r->cus_pay_credit) && floatval($r->cus_pay_credit) > 0.004){ return 0; }
        if(isset($r->sale_return_status) && trim((string)$r->sale_return_status) !== ''){ return 0; }

        $grand = isset($r->sale_grandtotal) ? floatval($r->sale_grandtotal) : 0;
        if($grand <= 0){ return 0; }

        $paid = floatval($r->cus_pay_cash);

        if($this->db->table_exists('ezy_pos_cus_cheque')){
            $q = $this->db->query("SELECT COALESCE(SUM(cus_cheque_amount),0) AS t
                                   FROM ezy_pos_cus_cheque WHERE cus_cheque_saleid = ?", array($r->sale_id))->row();
            if($q){ $paid += floatval($q->t); }
        }
        if($this->db->table_exists('ezy_pos_sale_payments')){
            $q = $this->db->query("SELECT COALESCE(SUM(sp_amount),0) AS t
                                   FROM ezy_pos_sale_payments WHERE sp_sale_id = ?", array($r->sale_id))->row();
            if($q){ $paid += floatval($q->t); }
        }
        if($this->db->table_exists('ezy_pos_voucher_redemptions')){
            $q = $this->db->query("SELECT COALESCE(SUM(vr_amount),0) AS t
                                   FROM ezy_pos_voucher_redemptions WHERE vr_sale_id = ?", array($r->sale_id))->row();
            if($q){ $paid += floatval($q->t); }
        }

        $change = round($paid - $grand, 2);
        // Never report more change than there was cash to give it from.
        if($change > floatval($r->cus_pay_cash)){ $change = floatval($r->cus_pay_cash); }
        return $change > 0 ? $change : 0;
    }

    protected function _storeFilterFor($column, $storeId = null){
        $base = $this->_storeFilter($column);
        if($storeId !== null && $storeId !== '' && $storeId !== 'all' && intval($storeId) > 0){
            return $base." AND $column = ".intval($storeId);
        }
        return $base;
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
        $sf = $this->_storeFilterFor('s.sale_location', $this->input->post('store_id'));
        $str="SELECT s.sale_id,s.sale_cus_id,s.sale_grandtotal,s.sale_subtotal,s.sale_discount,s.sale_createdat,c.cus_name "
                . "FROM ezy_pos_sale s,ezy_pos_customers c WHERE s.sale_cus_id='$cus_id' AND  c.cus_id='$cus_id'".$sf;
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
        $sf = $this->_storeFilterFor('s.sale_location', $this->input->post('store_id'));

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
        $sf = $this->_storeFilterFor('s.sale_location', $this->input->post('store_id'));

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
        $sf = $this->_storeFilterFor('sale_location', $this->input->post('store_id'));

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
        $sf = $this->_storeFilterFor('sale_location', $this->input->post('store_id'));
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
    //
    // There is ONE source of truth for money movement: getCashMovement().
    // The Cash Flow report, its summary, and Today's Summary all read from it,
    // so those screens can never show different numbers for the same period.
    //
    // What counts as money movement:
    //   IN   - cash / cheque / card taken against a sale
    //   IN   - every payment recorded against a tailoring (production sale) order
    //   IN   - an exchange where the new items cost MORE than the returned ones
    //          (the customer pays the difference)
    //   OUT  - a refund handed back on a return, or on an exchange where the
    //          returned items were worth more than the new ones
    // =========================================================================

    /**
     * Canonical list of money movements for a period.
     *
     * @param string $from     Start date (Y-m-d)
     * @param string $to       End date (Y-m-d)
     * @param string $method   'all', 'cash', or a specific pm_id (filters sale card methods)
     * @param mixed  $storeId  null/'all' for every branch the user may see, or a store_id
     * @return array  list of rows: date, source, ref, customer_name, method_name,
     *                reference, direction ('in'|'out'), amount (always positive),
     *                signed_amount (+in / -out), store_name
     */
    public function getCashMovement($from, $to, $method = 'all', $storeId = null){
        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $rows  = array();

        $wantAll   = ($method === 'all' || $method === '' || $method === null);
        $wantCash  = ($wantAll || $method === 'cash');
        $wantPmId  = (!$wantAll && $method !== 'cash') ? intval($method) : 0;

        // Bills print a per-store number (HG1-0001). Older databases that have not
        // run the v9 migration yet have no such column, so fall back to the sale id.
        $saleFields = $this->db->list_fields('ezy_pos_sale');
        $billCol    = (in_array('sale_bill_no', $saleFields) ? 's.sale_bill_no' : "'' AS sale_bill_no")
                    .', s.sale_location'
                    .(in_array('sale_bill_seq', $saleFields) ? ', s.sale_bill_seq' : ', NULL AS sale_bill_seq');
        $billOf    = function($r){ return bill_no($r); };

        $mk = function($date, $source, $ref, $customer, $methodName, $reference, $direction, $amount, $store){
            $o = new stdClass();
            $o->date          = $date;
            $o->source        = $source;
            $o->ref           = $ref;
            $o->customer_name = $customer;
            $o->method_name   = $methodName;
            $o->reference     = $reference === null ? '' : $reference;
            $o->direction     = $direction;
            $o->amount        = round(floatval($amount), 2);
            $o->signed_amount = ($direction === 'out') ? -round(floatval($amount), 2) : round(floatval($amount), 2);
            $o->store_name    = $store === null ? '' : $store;
            // Kept for backward compatibility with the old report table.
            $o->sale_id       = $ref;
            $o->sale_date     = $date;
            $o->type          = ($methodName === 'Cash') ? 'cash' : 'method';
            return $o;
        };

        // ------------------------------------------------------------------
        // 1. SALES - cash
        // ------------------------------------------------------------------
        if($wantCash){
            $sf = $this->_storeFilterFor('s.sale_location', $storeId);
            // cus_pay_cash is what the customer HANDED OVER, not what the bill
            // came to. On a 4,900 bill paid with 5,000 it holds 5,000, and the
            // 100 handed back as change was never recorded anywhere - so cash in
            // was overstated and the till could not tally. The change is worked
            // out here and shown as its own Cash OUT line:
            //     in 5,000  -  out 100  =  4,900 actually in the drawer.
            $str = "SELECT s.sale_id, s.sale_date, s.sale_grandtotal, s.sale_return_status,
                           c.cus_name, cp.cus_pay_cash, cp.cus_pay_credit, st.store_name, ".$billCol."
                    FROM ezy_pos_cus_payment cp
                    INNER JOIN ezy_pos_sale s ON s.sale_id = cp.cus_pay_saleid
                    LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                    LEFT JOIN ezy_pos_stores st ON st.store_id = s.sale_location
                    WHERE s.sale_date BETWEEN ? AND ?
                      AND s.sale_status = '1'
                      AND cp.cus_pay_cash > 0"
                    .$sf.
                    " ORDER BY s.sale_id DESC";
            foreach($this->db->query($str, array($start, $end))->result() as $r){
                $rows[] = $mk($r->sale_date, 'Sale', $billOf($r), $r->cus_name,
                              'Cash', '', 'in', $r->cus_pay_cash, $r->store_name);

                $change = $this->_changeGivenOnSale($r);
                if($change > 0.004){
                    $rows[] = $mk($r->sale_date, 'Change Given', $billOf($r), $r->cus_name,
                                  'Cash', '', 'out', $change, $r->store_name);
                }
            }
        }

        // ------------------------------------------------------------------
        // 2. SALES - cheques
        // ------------------------------------------------------------------
        if($wantAll && $this->db->table_exists('ezy_pos_cus_cheque')){
            $sf = $this->_storeFilterFor('s.sale_location', $storeId);
            $str = "SELECT s.sale_id, s.sale_date, c.cus_name, st.store_name, ".$billCol.",
                           ch.cus_cheque_amount, ch.cus_cheque_num, ch.cus_cheque_bank
                    FROM ezy_pos_cus_cheque ch
                    INNER JOIN ezy_pos_sale s ON s.sale_id = ch.cus_cheque_saleid
                    LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                    LEFT JOIN ezy_pos_stores st ON st.store_id = s.sale_location
                    WHERE s.sale_date BETWEEN ? AND ?
                      AND s.sale_status = '1'
                      AND ch.cus_cheque_amount > 0"
                    .$sf.
                    " ORDER BY s.sale_id DESC";
            foreach($this->db->query($str, array($start, $end))->result() as $r){
                $ref = trim($r->cus_cheque_num);
                if(trim($r->cus_cheque_bank) !== ''){
                    $ref = trim($r->cus_cheque_bank.($ref !== '' ? ' - '.$ref : ''));
                }
                $rows[] = $mk($r->sale_date, 'Sale', $billOf($r), $r->cus_name,
                              'Cheque', $ref, 'in', $r->cus_cheque_amount, $r->store_name);
            }
        }

        // ------------------------------------------------------------------
        // 3. SALES - card / configured payment methods
        // ------------------------------------------------------------------
        if($method !== 'cash'
           && $this->db->table_exists('ezy_pos_sale_payments')
           && $this->db->table_exists('ezy_pos_payment_methods')){
            $sf = $this->_storeFilterFor('s.sale_location', $storeId);
            $str = "SELECT sp.sp_amount, sp.sp_card_ref, pm.pm_name,
                           s.sale_id, s.sale_date, c.cus_name, st.store_name, ".$billCol."
                    FROM ezy_pos_sale_payments sp
                    INNER JOIN ezy_pos_sale s ON s.sale_id = sp.sp_sale_id
                    LEFT JOIN ezy_pos_payment_methods pm ON pm.pm_id = sp.sp_pm_id
                    LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                    LEFT JOIN ezy_pos_stores st ON st.store_id = s.sale_location
                    WHERE s.sale_date BETWEEN ? AND ?
                      AND s.sale_status = '1'
                      AND sp.sp_amount > 0"
                    .$sf;
            $params = array($start, $end);
            if($wantPmId > 0){
                $str .= " AND sp.sp_pm_id = ?";
                $params[] = $wantPmId;
            }
            $str .= " ORDER BY s.sale_id DESC";
            foreach($this->db->query($str, $params)->result() as $r){
                $rows[] = $mk($r->sale_date, 'Sale', $billOf($r), $r->cus_name,
                              ($r->pm_name ? $r->pm_name : 'Card'), $r->sp_card_ref,
                              'in', $r->sp_amount, $r->store_name);
            }
        }

        // ------------------------------------------------------------------
        // 4. TAILORING ORDERS - every payment taken (advance + later settlements)
        // ------------------------------------------------------------------
        if($this->db->table_exists('ezy_pos_prodsale_payments')
           && $this->db->table_exists('ezy_pos_prodsale')){
            $sf = $this->_storeFilterFor('p.prodsale_store_id', $storeId);
            $fields = $this->db->list_fields('ezy_pos_prodsale_payments');
            $refCol = in_array('psp_card_ref', $fields) ? 'psp.psp_card_ref' : "''";
            $str = "SELECT psp.psp_amount, psp.psp_method, ".$refCol." AS psp_ref,
                           psp.psp_created_at, p.prodsale_id, p.prodsale_code,
                           c.cus_name, st.store_name
                    FROM ezy_pos_prodsale_payments psp
                    INNER JOIN ezy_pos_prodsale p ON p.prodsale_id = psp.psp_prodsale_id
                    LEFT JOIN ezy_pos_customers c ON c.cus_id = p.prodsale_cus_id
                    LEFT JOIN ezy_pos_stores st ON st.store_id = p.prodsale_store_id
                    WHERE psp.psp_created_at BETWEEN ? AND ?
                      AND psp.psp_amount > 0"
                    .$sf.
                    " ORDER BY psp.psp_id DESC";
            foreach($this->db->query($str, array($start, $end))->result() as $r){
                $mName = $r->psp_method ? $r->psp_method : 'Cash';
                // Honour the method filter the same way sales rows do.
                if($method === 'cash' && strtolower($mName) !== 'cash') continue;
                if($wantPmId > 0){
                    $pmRow = $this->db->query("SELECT pm_name FROM ezy_pos_payment_methods WHERE pm_id = ?", array($wantPmId))->row();
                    if(!$pmRow || strtolower($pmRow->pm_name) !== strtolower($mName)) continue;
                }
                $rows[] = $mk(substr($r->psp_created_at, 0, 10), 'Tailoring',
                              ($r->prodsale_code ? $r->prodsale_code : 'TLR-'.$r->prodsale_id),
                              $r->cus_name, $mName, $r->psp_ref, 'in', $r->psp_amount, $r->store_name);
            }
        }

        // ------------------------------------------------------------------
        // 5. RETURNS & EXCHANGES
        //    Refund out, or the customer paying the difference on an exchange.
        // ------------------------------------------------------------------
        if($this->db->table_exists('ezy_pos_returns')){
            $retFields  = $this->db->list_fields('ezy_pos_returns');
            $hasStore   = in_array('ret_store_id', $retFields);
            $sf         = $hasStore ? $this->_storeFilterFor('r.ret_store_id', $storeId) : '';
            $hasPayTbl  = $this->db->table_exists('ezy_pos_return_payments');

            $modeCol = in_array('ret_refund_mode', $retFields) ? 'r.ret_refund_mode' : "'cash' AS ret_refund_mode";
            $str = "SELECT r.ret_id, r.ret_type, r.ret_net_amount, r.ret_created_at,
                           c.cus_name, st.store_name, ".$modeCol."
                    FROM ezy_pos_returns r
                    LEFT JOIN ezy_pos_sale s ON s.sale_id = r.ret_sale_id
                    LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                    LEFT JOIN ezy_pos_stores st ON st.store_id = "
                          .($hasStore ? "r.ret_store_id" : "s.sale_location")."
                    WHERE r.ret_created_at BETWEEN ? AND ?
                      AND r.ret_status = 1"
                    .$sf.
                    " ORDER BY r.ret_id DESC";

            foreach($this->db->query($str, array($start, $end))->result() as $r){
                $label = ($r->ret_type === 'exchange') ? 'Exchange' : 'Return';
                $date  = substr($r->ret_created_at, 0, 10);
                $net   = floatval($r->ret_net_amount);

                // Prefer the payment lines actually recorded against this return.
                // Lines flagged rp_in_cashflow = 0 (store credit left on the bill)
                // are deliberately excluded: no money moved.
                $payRows = array();
                if($hasPayTbl){
                    $cfFilter = in_array('rp_in_cashflow', $this->db->list_fields('ezy_pos_return_payments'))
                              ? ' AND rp_in_cashflow = 1' : '';
                    $payRows = $this->db->query(
                        "SELECT * FROM ezy_pos_return_payments WHERE rp_ret_id = ? AND rp_amount > 0".$cfFilter." ORDER BY rp_id",
                        array($r->ret_id)
                    )->result();
                }
                // A store-credit refund has payment lines, they are just all
                // excluded above - so it must not fall through to the net-amount
                // fallback and get counted as cash after all.
                $isStoreCredit = (isset($r->ret_refund_mode) && $r->ret_refund_mode === 'store_credit');

                if(count($payRows) > 0){
                    foreach($payRows as $p){
                        $mName = $p->rp_method ? $p->rp_method : 'Cash';
                        if($method === 'cash' && strtolower($mName) !== 'cash') continue;
                        if($wantPmId > 0){
                            $pmRow = $this->db->query("SELECT pm_name FROM ezy_pos_payment_methods WHERE pm_id = ?", array($wantPmId))->row();
                            if(!$pmRow || strtolower($pmRow->pm_name) !== strtolower($mName)) continue;
                        }
                        $rows[] = $mk($date, $label, 'RET-'.$r->ret_id, $r->cus_name, $mName,
                                      $p->rp_reference,
                                      ($p->rp_direction === 'out' ? 'out' : 'in'),
                                      $p->rp_amount, $r->store_name);
                    }
                } else {
                    // Older return with no payment lines: fall back to its net amount,
                    // booked as cash so the totals still balance.
                    if($isStoreCredit) continue;
                    if(abs($net) < 0.005) continue;
                    if(!$wantCash) continue;
                    $rows[] = $mk($date, $label, 'RET-'.$r->ret_id, $r->cus_name, 'Cash', '',
                                  ($net > 0 ? 'out' : 'in'), abs($net), $r->store_name);
                }
            }
        }

        // Newest first, so the report reads like a day book.
        usort($rows, function($a, $b){
            if($a->date === $b->date) return 0;
            return ($a->date < $b->date) ? 1 : -1;
        });

        return $rows;
    }

    /**
     * Per-method totals for the same period, derived from getCashMovement()
     * so the summary cards can never disagree with the table below them.
     */
    public function getCashMovementSummary($from, $to, $storeId = null, $method = 'all'){
        $rows    = $this->getCashMovement($from, $to, $method, $storeId);
        $byMethod = array();
        $in = 0; $out = 0;

        foreach($rows as $r){
            $key = $r->method_name;
            if(!isset($byMethod[$key])){
                $byMethod[$key] = array('in' => 0, 'out' => 0);
            }
            if($r->direction === 'out'){
                $byMethod[$key]['out'] += $r->amount;
                $out += $r->amount;
            } else {
                $byMethod[$key]['in'] += $r->amount;
                $in += $r->amount;
            }
        }

        $methods = array();
        foreach($byMethod as $name => $v){
            $o = new stdClass();
            $o->method_name  = $name;
            $o->total_in     = round($v['in'], 2);
            $o->total_out    = round($v['out'], 2);
            // total_amount = net for this method (kept as the name the view already uses)
            $o->total_amount = round($v['in'] - $v['out'], 2);
            $methods[] = $o;
        }

        usort($methods, function($a, $b){
            if($a->total_amount == $b->total_amount) return 0;
            return ($a->total_amount < $b->total_amount) ? 1 : -1;
        });

        return array(
            'methods'   => $methods,
            'total_in'  => round($in, 2),
            'total_out' => round($out, 2),
            'net'       => round($in - $out, 2)
        );
    }

    /**
     * Totals per source (Sale / Tailoring / Return / Exchange) for a period.
     * Today's Summary uses this so its cash figure is literally the same number
     * the Cash Flow report shows.
     */
    public function getCashMovementBySource($from, $to, $storeId = null){
        $rows = $this->getCashMovement($from, $to, 'all', $storeId);
        $out = array(
            'sale_in' => 0, 'tailoring_in' => 0, 'return_out' => 0,
            'exchange_in' => 0, 'exchange_out' => 0,
            // Change handed back to customers - money out, but not a refund.
            'change_out' => 0,
            'total_in' => 0, 'total_out' => 0, 'net' => 0,
            // Split of the sale money by tender, so Today's Summary shows exactly
            // the same Cash / Cheque figures the Cash Flow report does.
            'sale_cash' => 0, 'sale_cheque' => 0, 'sale_card' => 0,
            'tailoring_cash' => 0,
            // Cash-only view (physical notes in the till)
            'cash_in' => 0, 'cash_out' => 0, 'cash_net' => 0
        );
        foreach($rows as $r){
            $isCash = (strtolower($r->method_name) === 'cash');
            $isChq  = (strtolower($r->method_name) === 'cheque');

            if($r->direction === 'out'){
                $out['total_out'] += $r->amount;
                if($isCash) $out['cash_out'] += $r->amount;
                if($r->source === 'Change Given')  $out['change_out']   += $r->amount;
                elseif($r->source === 'Exchange')  $out['exchange_out'] += $r->amount;
                else                               $out['return_out']   += $r->amount;
            } else {
                $out['total_in'] += $r->amount;
                if($isCash) $out['cash_in'] += $r->amount;
                if($r->source === 'Sale'){
                    $out['sale_in'] += $r->amount;
                    if($isCash)      $out['sale_cash']   += $r->amount;
                    elseif($isChq)   $out['sale_cheque'] += $r->amount;
                    else             $out['sale_card']   += $r->amount;
                }
                elseif($r->source === 'Tailoring'){
                    $out['tailoring_in'] += $r->amount;
                    if($isCash) $out['tailoring_cash'] += $r->amount;
                }
                elseif($r->source === 'Exchange'){ $out['exchange_in'] += $r->amount; }
            }
        }
        foreach($out as $k => $v){ $out[$k] = round($v, 2); }
        $out['net']      = round($out['total_in'] - $out['total_out'], 2);
        $out['cash_net'] = round($out['cash_in']  - $out['cash_out'],  2);
        return $out;
    }

    /**
     * Cash flow detail rows for the report table.
     * Thin wrapper kept so existing callers/route names keep working.
     */
    public function getCashFlowReport($from, $to, $method = 'all', $storeId = null){
        $rows = $this->getCashMovement($from, $to, $method, $storeId);
        return count($rows) > 0 ? $rows : false;
    }

    /**
     * Cash flow summary rows (one per payment method).
     */
    public function getCashFlowSummary($from, $to, $storeId = null, $method = 'all'){
        $summary = $this->getCashMovementSummary($from, $to, $storeId, $method);
        return count($summary['methods']) > 0 ? $summary['methods'] : false;
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


    // All sales receipts (individual bills) that contain a given item code/name.
    // Used by the Item Sales screen so the user can open / print each bill.
    public function getSalesReceiptsByItem($item_code, $from = '', $to = ''){
        if(!$item_code || trim($item_code) === '') return array();
        $sf = $this->_storeFilter('s.sale_location');
        $where = "WHERE (i.itm_code LIKE ? OR i.itm_name LIKE ?)";
        $params = array('%'.$item_code.'%', '%'.$item_code.'%');
        if($from && $to){
            $where .= " AND s.sale_date BETWEEN ? AND ?";
            $params[] = $from . " 00:00:00";
            $params[] = $to . " 23:59:59";
        }
        $str = "SELECT s.sale_id, s.sale_date, s.sale_grandtotal,
                       c.cus_name,
                       i.itm_code, i.itm_name,
                       si.saleitem_quantity, si.saleitem_price, si.saleitem_total
                FROM ezy_pos_sale_item si
                INNER JOIN ezy_pos_items i ON i.itm_id = si.saleitem_item_id
                INNER JOIN ezy_pos_sale s ON s.sale_id = si.saleitem_sale_id
                LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                ".$where." ".$sf."
                ORDER BY s.sale_id DESC";
        $q = $this->db->query($str, $params);
        return $q->result();
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
        $str = "SELECT gi.grnitm_itemid AS itm_id,
                       COUNT(DISTINCT gi.grnitm_grn_id) AS num_grns,
                       SUM(gi.grnitm_quantity) AS grn_qty,
                       SUM(gi.grnitm_total) AS grn_cost
                FROM ezy_pos_grn_item gi
                INNER JOIN ezy_pos_grns g ON g.grn_id = gi.grnitm_grn_id
                WHERE 1=1"
                .$grnDateFilter.
                " GROUP BY gi.grnitm_itemid";
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

    // Branch filter for the returns table (only when the store column exists).
    private function _returnStoreFilter($storeId = null){
        if(!in_array('ret_store_id', $this->db->list_fields('ezy_pos_returns'))) return '';
        return $this->_storeFilterFor('ret_store_id', $storeId);
    }

    public function getReturnsTotalForToday($storeId = null){
        if(!$this->db->table_exists('ezy_pos_returns')){
            $obj = new stdClass();
            $obj->total_returns = 0;
            $obj->return_count = 0;
            return $obj;
        }
        $str = "SELECT COALESCE(SUM(ret_net_amount),0) AS total_returns, COUNT(*) AS return_count
                FROM ezy_pos_returns
                WHERE DATE(ret_created_at) = CURDATE() AND ret_status = 1"
                .$this->_returnStoreFilter($storeId);
        $query = $this->db->query($str);
        return $query->row();
    }

    public function getReturnsTotalByDates($from, $to, $storeId = null){
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
                WHERE ret_created_at BETWEEN ? AND ? AND ret_status = 1"
                .$this->_returnStoreFilter($storeId);
        $query = $this->db->query($str, array($start, $end));
        return $query->row();
    }

    public function getTodaySummaryByDates($from, $to, $storeId = null){
        $start = $from . " 00:00:00";
        $end   = $to   . " 23:59:59";
        $sf    = $this->_storeFilterFor('sale_location', $storeId);

        $result = array();

        // Sales total
        $str = "SELECT COALESCE(SUM(sale_grandtotal),0) AS total FROM ezy_pos_sale WHERE sale_date BETWEEN ? AND ? AND sale_status='1'".$sf;
        $q = $this->db->query($str, array($start, $end));
        $result['sale_total'] = $q->row()->total;

        // Sales cash + cheque come from the SAME engine the Cash Flow report uses,
        // so the two screens can never report different figures for one period.
        $cf = $this->getCashMovementBySource($from, $to, $storeId);
        $result['sale_cash']   = $cf['sale_cash'];
        $result['sale_cheque'] = $cf['sale_cheque'];
        $result['sale_card']   = $cf['sale_card'];

        // Full cash-flow block (tailoring in, refunds out, exchange top-ups, net)
        $result['cf_sale_in']      = $cf['sale_in'];
        $result['cf_tailoring_in'] = $cf['tailoring_in'];
        $result['cf_return_out']   = $cf['return_out'];
        $result['cf_exchange_in']  = $cf['exchange_in'];
        $result['cf_exchange_out'] = $cf['exchange_out'];
        $result['cf_change_out']   = $cf['change_out'];
        $result['cf_total_in']     = $cf['total_in'];
        $result['cf_total_out']    = $cf['total_out'];
        $result['cf_net']          = $cf['net'];
        $result['cf_cash_in']      = $cf['cash_in'];
        $result['cf_cash_out']     = $cf['cash_out'];
        $result['cf_cash_net']     = $cf['cash_net'];

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
        $retData = $this->getReturnsTotalByDates($from, $to, $storeId);
        $result['returns_total'] = $retData->total_returns;
        $result['returns_count'] = $retData->return_count;

        return $result;
    }

}



