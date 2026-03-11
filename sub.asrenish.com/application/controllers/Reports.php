<?php
class Reports extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
              //  else if(!$this->session->userdata('privstock')==1){
              //          show_404();
               // }
                $this->load->model('Report_model');
                $this->load->model('Configs_model');
                $this->load->model('Stores_model');
        }

        // Helper: get SQL WHERE clause for store filtering based on user role
        private function _storeFilter($column = 'sale_location', $storeid_override = null){
            if($storeid_override && $storeid_override != 'all' && $storeid_override != '0'){
                return " AND $column = '".intval($storeid_override)."'";
            }
            if($this->session->userdata('userrole') == 1){
                return ''; // admin sees all stores
            }
            // Non-admin: restrict to assigned stores
            $uid = $this->session->userdata('userid');
            $this->db->select('store_id');
            $this->db->where('user_id', $uid);
            $this->db->where('user_store_status', 1);
            $q = $this->db->get('ezy_pos_user_store');
            $ids = array();
            foreach($q->result() as $r){ $ids[] = intval($r->store_id); }
            if(empty($ids)) return " AND $column = -1"; // no store assigned, show nothing
            return " AND $column IN (".implode(',', $ids).")";
        }

        // Helper: load stores for dropdown (admin=all, staff=assigned only)
        private function _loadStoresForUser(){
            if($this->session->userdata('userrole') == 1){
                return $this->Stores_model->getAllStores();
            }
            $uid = $this->session->userdata('userid');
            return $this->Stores_model->getAllStoresfornonadmin($uid);
        }

       public function sales_report($page = 'index')
        {
           
                if ( ! file_exists(APPPATH.'views/report/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
                $data['all_customers'] = $this->Report_model->load_all_customers();
        
                
                $this->load->view('templates/header', $data);
                $this->load->view('report/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
     
       public function monthly_sales_report($page = 'index')
        {
           
                if ( ! file_exists(APPPATH.'views/report/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();

        
                
                $this->load->view('templates/header', $data);
                $this->load->view('report/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        
       public function purchase_report($page = 'index')
        {
           
                if ( ! file_exists(APPPATH.'views/report/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();

        
                
                $this->load->view('templates/header', $data);
                $this->load->view('report/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        
        
       public function profit_loss_report($page = 'index')
        {
           
                if ( ! file_exists(APPPATH.'views/report/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();

        
                
                $this->load->view('templates/header', $data);
                $this->load->view('report/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        
        
       public function backup($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/report/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $this->load->view('report/'.$page);
        }
        
        
        
       public function payment_methods_report($page = 'index')
        {
                if ( ! file_exists(APPPATH.'views/report/'.$page.'.php'))
                {
                        show_404();
                }

                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
                $data['payment_methods'] = $this->Report_model->getAllPaymentMethodsList();

                $this->load->view('templates/header', $data);
                $this->load->view('report/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

        public function getPaymentMethodsReportData(){
                $from = $this->input->post('from');
                $to = $this->input->post('to');
                $pm_id = $this->input->post('pm_id');
                if(!$from) $from = '';
                if(!$to) $to = '';
                if(!$pm_id) $pm_id = 'all';
                $result = $this->Report_model->getPaymentMethodsReport($from, $to, $pm_id);
                echo json_encode($result);
        }

        public function getPaymentMethodsSummaryData(){
                $from = $this->input->post('from');
                $to = $this->input->post('to');
                if(!$from) $from = '';
                if(!$to) $to = '';
                $result = $this->Report_model->getPaymentMethodsSummary($from, $to);
                echo json_encode($result);
        }

       public function today_summary($page = 'index')
        {
           
                if ( ! file_exists(APPPATH.'views/report/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
                
                $data['sale_result_total'] = $this->Report_model->get_sale_Report_for_today_summary_total();
                $data['sale_result_cash'] = $this->Report_model->get_sale_Report_for_today_summary_cash();
                $data['sale_result_cheque'] = $this->Report_model->get_sale_Report_for_today_summary_cheque();
               
                $data['expense_result_cash'] = $this->Report_model->get_expense_Report_for_today_summary_cash();
                $data['expense_result_cheque'] = $this->Report_model->get_expense_Report_for_today_summary_cheque();
                
                $data['purchase_result_total'] = $this->Report_model->get_total_purchase_Report_for_today_summary();
                $data['purchase_result_cash'] = $this->Report_model->get_purchase_Report_for_today_summary_cash();
                $data['purchase_result_cheque'] = $this->Report_model->get_purchase_Report_for_today_summary_cheque();
                $data['purchase_result_credit'] = $this->Report_model->get_total_credit_purchase_Report_for_today_summary();
               
                $data['payment_result_cash'] = $this->Report_model->get_payment_Report_for_today_summary_cash();
                $data['payment_result_cheque'] = $this->Report_model->get_payment_Report_for_today_summary_cheque();
               
                 
        
                
                $this->load->view('templates/header',$data);
                $this->load->view('report/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        
       public function expense_report($page = 'index')
        {
           
                if ( ! file_exists(APPPATH.'views/report/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
        
                
                $this->load->view('templates/header', $data);
                $this->load->view('report/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }

 
        
       public function getSaleReport_user(){
                $cus_id=$this->uri->segment(3);
                $result =$this->Report_model->getSaleReport_by_users($cus_id);		 
                echo json_encode($result);
        }
        
       public function getSaleReport_this_month(){
                $date_signature =  date('Y-m'); 
                $result =$this->Report_model->getSaleReport_for_month($date_signature);		 
                echo json_encode($result);
        }
        
       public function getSaleReport_selected_month(){
                $date_signature=$this->input->post('selected_month');
                $result =$this->Report_model->getSaleReport_for_month($date_signature);		 
                echo json_encode($result);
        }
        
        
       public function getSaleReport_user_dates(){
                $cus_id=$this->uri->segment(3);
               	 
                echo json_encode($result);
        }
        public function sales_log_by_dates(){
                $cus_id=$this->input->post('cus_id');
                if($cus_id=="all"){
                $result =$this->Report_model->filter_sales_log_by_dates();
                }else{
                $result =$this->Report_model->getSaleReport_by_users_dates();	
                }
                echo json_encode($result);
        }
        
        
        
        public function getPurchaseReport(){
                $result =$this->Report_model->get_purchase_Report();		 
                echo json_encode($result);
        }
        
        public function getPurchaseReport_by_dates(){ 
                $result =$this->Report_model->get_purchase_Report_by_dates();	
                echo json_encode($result);
        }
        public function getPurchaseReport_by_month(){ 
                $result =$this->Report_model->get_purchase_Report_by_month();	
                echo json_encode($result);
        }
        
               
        public function getExpenseReport(){
                $result =$this->Report_model->get_expense_Report();		 
                echo json_encode($result);
        }
        
        public function getExpenseReport_by_dates(){ 
                $result =$this->Report_model->get_expense_Report_by_dates();	
                echo json_encode($result);
        }
        public function getExpenseReport_by_month(){ 
                $result =$this->Report_model->get_expense_Report_by_month();	
                echo json_encode($result);
        }
        
        public function getExpenseReport_for_today(){ 
                $result =$this->Report_model->get_expense_Report_for_today();	
                echo json_encode($result);
        }
        
               
        public function get_profit_loss_Report_by_dates(){ 
        $result_sale = $this->Report_model->get_sales_Report_by_dates();
        $result_expenses = $this->Report_model->get_expenses_Report_by_dates();
        $result_purchases = $this->Report_model->get_purchase_Report_by_dates2();
        $profit=($result_sale->sum_sale_grandtotal)-(($result_expenses->sum_expen_amount)+($result_purchases->sum_grn_grandtotal));
        if($profit<0){
            $p_l_type="Loss Value : ";
            //$loss=(($result_expenses->sum_expen_amount)+($result_purchases->sum_grn_grandtotal))-($result_sale->sum_sale_grandtotal);
            //$final_value=$loss;
            $final_value=$profit*(-1);
        }else{
            $p_l_type="Profit Value : "; 
            $final_value=$profit;
        }
        $resut_array=array('sum_sale_grandtotal'=>$result_sale->sum_sale_grandtotal,"sum_expen_amount"=>$result_expenses->sum_expen_amount,"sum_grn_grandtotal"=>$result_purchases->sum_grn_grandtotal,"p_l_type"=>$p_l_type,'final_value'=>$final_value);
        echo json_encode($resut_array);
        }
        
        public function get_profit_loss_Report_by_month(){ 
        $result_sale = $this->Report_model->get_sales_Report_by_month();
        $result_expenses = $this->Report_model->get_expenses_Report_by_month();
        $result_purchases = $this->Report_model->get_purchase_Report_by_month2();
        $profit=($result_sale->sum_sale_grandtotal)-(($result_expenses->sum_expen_amount)+($result_purchases->sum_grn_grandtotal));
        if($profit<0){
            $p_l_type="Loss Value : ";
            //$loss=(($result_expenses->sum_expen_amount)+($result_purchases->sum_grn_grandtotal))-($result_sale->sum_sale_grandtotal);
            //$final_value=$loss;
            $final_value=$profit*(-1);
        }else{
            $p_l_type="Profit Value : "; 
            $final_value=$profit;
        }
        $resut_array=array('sum_sale_grandtotal'=>$result_sale->sum_sale_grandtotal,"sum_expen_amount"=>$result_expenses->sum_expen_amount,"sum_grn_grandtotal"=>$result_purchases->sum_grn_grandtotal,"p_l_type"=>$p_l_type,'final_value'=>$final_value);
        echo json_encode($resut_array);
        }
        
        
        
        
        // Johan profit calcultaion
        
public function get_total_expenses() {
    // Get the selected time period (if applicable)
    $from_date = $this->input->get('from_date');
    $to_date = $this->input->get('to_date');

    // Query to calculate total expense for the items sold in the selected period
    $query = $this->db->query('
        SELECT SUM(grns.grn_quantity * grn.cur_grnPrice) AS total_expense FROM ezy_pos_cur_grn_vs_sale grns JOIN ezy_pos_currentqtywithgrn grn ON grns.grnvssale_curQtyWithGrnID = grn.cur_id JOIN ezy_pos_sale sales ON grns.grnvssale_saleID = sales.sale_id WHERE sales.sale_date BETWEEN ? AND ? 
    ', array($from_date, $to_date));

    // Get the result
    $result = $query->row();  // Get the first row of the result
    
    // Ensure the total_expense is a number
    $total_expense = is_numeric($result->total_expense) ? $result->total_expense : 0;
    
    // Return the total expense as a JSON response
    echo json_encode(array('total_expense' => $total_expense));
}
public function get_total_sales() {
    // Get the selected time period (if applicable)
    $from_date = $this->input->get('from_date');
    $to_date = $this->input->get('to_date');

    // Modify the query to sum the sale_grandtotal for the selected period
    $query = $this->db->query('
        SELECT 
            SUM(sales.sale_grandtotal) AS total_sales
        FROM 
            ezy_pos_sale sales
        WHERE 
            sales.sale_date BETWEEN ? AND ?  -- Filter by sale_date
    ', array($from_date, $to_date));

    // Get the result
    $result = $query->row();  // Get the first row of the result
    
    // Ensure the total_sales is a number
    $total_sales = is_numeric($result->total_sales) ? $result->total_sales : 0;
    
    // Return the total sales as a JSON response
    echo json_encode(array('total_sales' => $total_sales));
}
public function get_overall_expenses() {
    $from_date = $this->input->get('from_date');
    $to_date = $this->input->get('to_date');

    $query = $this->db->query('
        SELECT 
            SUM(expen_amount) AS overall_expense 
        FROM 
            ezy_pos_expense 
        WHERE 
            expen_date BETWEEN ? AND ?
    ', array($from_date, $to_date));

    $result = $query->row();
    $overall_expense = is_numeric($result->overall_expense) ? $result->overall_expense : 0;

    echo json_encode(array('overall_expense' => $overall_expense));
}

        
        
        
        
        
        
        
        
        
        
        
        
        public function addItemtoStock(){
                $response = $this->Stocks_model->addItemtoStock();
                echo json_encode($response);
        }

        public function increaseStock(){
                $result =$this->Stocks_model->increaseStock();		 
		echo json_encode($result);
        }
        public function decreaseStock(){
            $result =$this->Stocks_model->decreaseStock();		 
            echo json_encode($result);
        }
        public function stocklog(){
                $result =$this->Stocks_model->stocklog();		 
                echo json_encode($result);
        }
        public function showAllStock(){
                $result =$this->Stocks_model->showAllStock();		 
                echo json_encode($result); 
        }
        public function showAllSupplierStock($page = 'index'){
                if (!file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
                $data['all_suppliers'] = $this->Stocks_model->get_all_suppliers();
                
        
                $this->load->view('templates/header', $data);
                $this->load->view('listing/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getSupplierStock(){
                $result =$this->Stocks_model->getSupplierStock();		 
                echo json_encode($result); 
        }
        
        public function getSingleSupplierStock(){
                $sup_id=$this->uri->segment(3);
                $result =$this->Stocks_model->getSingleSupplierStock($sup_id);		 
                echo json_encode($result); 
        }
        

        public function showAllStocklog($page = 'index'){
                if ( ! file_exists(APPPATH.'views/listing/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
        
                $data['title'] = ucfirst($page);
                $data['config'] = $this->Configs_model->getConfigName();
        
                $this->load->view('templates/header', $data);
                $this->load->view('listing/'.$page);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getStocklog(){
                $result =$this->Stocks_model->getStocklog();		 
                echo json_encode($result); 
        }
        
        public function getGRNReport(){
                $result =$this->Stocks_model->getGRNReport();		 
                echo json_encode($result);
        }
        
        public function getSaleReport(){
                $result =$this->Stocks_model->getSaleReport();		 
                echo json_encode($result);
        }
        
          
        public function getSupplierReturn(){
                $result =$this->Stocks_model->getSupplierReturn();		 
                echo json_encode($result);
        }
        public function getCustomerReturn(){
                $result =$this->Stocks_model->getCustomerReturn();		 
                echo json_encode($result);
        }



        
}