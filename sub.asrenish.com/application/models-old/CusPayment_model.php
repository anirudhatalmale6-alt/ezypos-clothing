<?php
class CusPayment_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function customerCash(){
        $data = array(
            'cus_pay_saleid'=>$this->input->post('saleID'),
            'cus_pay_cash'=>$this->input->post('cash'),
            'cus_pay_credit'=>$this->input->post('credit'),   
            'cus_pay_paiddate'=>$this->input->post('date'),  //=saledate     
        );
        return $this->db->insert('ezy_pos_cus_payment', $data);
    }
    public function customerCashLog(){
        $data = array(
            'pymntlog_saleid'=>$this->input->post('saleID'),
            'pymntlog_amount'=>$this->input->post('cash'),   
            'pymntlog_date'=>$this->input->post('date')  //=saledate     
        );
        return $this->db->insert('ezy_pos_cus_paymnt_log', $data);
    }

    public function customerCheque(){
        $checked = $this->input->post('checked');
        $saleid = $this->input->post('saleID');
        $amount = $this->input->post('amount');
        $bank = $this->input->post('bank');
        $num = $this->input->post('chequeno');
        $date = $this->input->post('chequedate');
        $givendate = $this->input->post('date');
        $cusID = $this->input->post('cusID'); // ✅ Get cusID from POST
    
        $cnt = count($amount);
        if ($checked == 1) {
            for ($x = 0; $x < $cnt; $x++) {
                $data = array(
                    'cus_cheque_saleid'      => $saleid,
                    'cheque_cus_id'      => $cusID,             // ✅ Insert cusID here
                    'cus_cheque_amount'      => $amount[$x],
                    'cus_cheque_bank'        => $bank[$x],   
                    'cus_cheque_num'         => $num[$x],
                    'cus_cheque_date'        => $date[$x],
                    'cus_cheque_givendate'   => $givendate, // = sale date
                    'cus_cheque_status'      => 1,
                    'cus_cheque_transferred' => 0 // not transferred yet
                );
    
                $this->db->insert('ezy_pos_cus_cheque', $data);
            }
            return true;  
        }
       
    }
    
    public function cusPaymentCheque() {
    $saleid = $this->input->post('saleID');
    $amount = $this->input->post('amount2');
    $bank = $this->input->post('bank');
    $num = $this->input->post('chequeno');
    $date = $this->input->post('chequedate');
    $givendate = $this->input->post('date');
    $cusID = $this->input->post('cus_ID'); 

    // Log for debugging
    log_message('debug', 'cus_ID: ' . $cusID);

    // Prepare data for insertion
    $data = array(
        'cus_cheque_saleid' => $saleid,
        'cus_cheque_amount' => $amount,
        'cus_cheque_bank' => $bank,   
        'cus_cheque_num' => $num,
        'cus_cheque_date' => $date,
        'cus_cheque_givendate' => $givendate, //=payment date
        'cus_cheque_status' => 1,
        'cus_cheque_transferred' => 0, // not transferred yet
        'cheque_cus_id' => $cusID
    );

    // Insert data into the database
    if ($this->db->insert('ezy_pos_cus_cheque', $data)) {
        $insert_id = $this->db->insert_id();             
        return $insert_id;
    } else {
        log_message('error', 'Database insert failed: ' . $this->db->_error_message());
        return false;
    }
}
    public function customerPayCheque(){ // 1 cheaq payable for multiple sales(saleid) 
        try{
            $cusChqID=$this->input->post('cusChqID');
            $saleid=$this->input->post('saleID');
            $amount=$this->input->post('Amnt');
                $data = array(
                    'cheqlog_chequeid'=>$cusChqID,
                    'cheqlog_saleid'=>$saleid,
                    'cheqlog_amount'=>$amount
                );
                 $res = $this->db->insert('ezy_pos_cus_chequelog', $data);
                 return [$data,$res];
        }
        catch(Ex $er)
        {
            return $er;
        }       
    }
    public function getCustomerID(){
        $saleid=$this->input->post('saleID');
        $this->db->select('sale_cus_id');
        $this->db->from('ezy_pos_sale');
        $this->db->where('sale_id', $saleid);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return $query->row()->sale_cus_id;
        }
        else{
            return false;
        }
    }
    public function cusBalance(){  //credit in minus , 
        $cusid=$this->input->post('cusID');
        $bal=$this->input->post('bal');
        $str="UPDATE ezy_pos_cus_balnce        
        SET bal_amount=bal_amount+'".$bal."'
        WHERE bal_cusid='".$cusid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function cusCreditUpdate(){  //credit in minus , 
        $cusid=$this->input->post('cusID');
        $credit_val=$this->input->post('ncreditval');
        $str="UPDATE ezy_pos_customers        
        SET cus_creditlimit=cus_creditlimit-'".$credit_val."'
        WHERE cus_id='".$cusid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function adjustCashCredit(){
        $saleid=$this->input->post('saleID');
        $cash=$this->input->post('cash');
        $cheq=$this->input->post('cheq');
        $total=$cash+$cheq;
        $str="UPDATE ezy_pos_cus_payment        
        INNER JOIN ezy_pos_sale ON ezy_pos_sale.sale_id=ezy_pos_cus_payment.cus_pay_saleid
        SET cus_pay_cash=cus_pay_cash+'".$cash."',
        cus_pay_credit=cus_pay_credit-'".$total."'
        WHERE cus_pay_saleid='".$saleid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }

    public function getCusPendingCheque(){
        $str="SELECT cus_name,cus_cheque_id,cus_cheque_amount,cus_cheque_bank,cus_cheque_num,cus_cheque_date,cus_cheque_status 
        from ezy_pos_cus_cheque
        LEFT JOIN ezy_pos_sale ON ezy_pos_sale.sale_id=ezy_pos_cus_cheque.cus_cheque_saleid 
        INNER JOIN ezy_pos_customers ON ezy_pos_customers.cus_id=ezy_pos_sale.sale_cus_id
        WHERE cus_cheque_status=1
        ORDER BY cus_cheque_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
public function getCusCheque() {
    $status = $this->input->post('statusNo');

    $str = "
        SELECT 
            cus_name,
            cus_cheque_id,
            cus_cheque_amount,
            cus_cheque_bank,
            cus_cheque_num,
            cus_cheque_date,
            cus_cheque_status,
            cus_cheque_transferred 
        FROM ezy_pos_cus_cheque
        LEFT JOIN ezy_pos_customers 
            ON ezy_pos_customers.cus_id = ezy_pos_cus_cheque.cheque_cus_id
        WHERE cus_cheque_status = ?
        ORDER BY cus_cheque_id
    ";

    $query = $this->db->query($str, [$status]);

    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return false;
    }
}
    
   

    public function filterCusCheque(){
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $str="SELECT cus_name,cus_cheque_id,cus_cheque_amount,cus_cheque_bank,cus_cheque_num,cus_cheque_date,cus_cheque_status 
        from ezy_pos_cus_cheque
        LEFT JOIN ezy_pos_sale ON ezy_pos_sale.sale_id=ezy_pos_cus_cheque.cus_cheque_saleid 
        INNER JOIN ezy_pos_customers ON ezy_pos_customers.cus_id=ezy_pos_sale.sale_cus_id
        WHERE cus_cheque_date BETWEEN '".$from."' AND '".$to."'
        AND cus_cheque_status!=0
        ORDER BY cus_cheque_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    
    public function deleteCheque(){
        $chqid=$this->input->post('id');
        $updateData = array(
            'cus_cheque_status' => 0
        );
        $this->db->where('cus_cheque_id', $chqid);
        $this->db->update('ezy_pos_cus_cheque', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function editCheque(){
        $chqid = $this->input->post('id');
        $this->db->where('cus_cheque_id', $chqid);
        $query = $this->db->get('ezy_pos_cus_cheque');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function getPreviousAmount($chqid){
        //$chqid = $this->input->post('HiddenChqID');
        $this->db->select('cus_cheque_amount');
        $this->db->from('ezy_pos_cus_cheque');
        $this->db->where('cus_cheque_id', $chqid);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return $query->row()->cus_cheque_amount;
        }
        else{
            return false;
        }
    }
    public function getPreviousStatus($chqid){
        $this->db->select('cus_cheque_status');
        $this->db->from('ezy_pos_cus_cheque');
        $this->db->where('cus_cheque_id', $chqid);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return $query->row()->cus_cheque_status;
        }
        else{
            return false;
        }
    }
    
    public function updateCheque($chqid){
        $updateData = array(
            'cus_cheque_amount' => $this->input->post('Edit_amount'),            
            'cus_cheque_bank' => $this->input->post('Edit_bank'),
            'cus_cheque_num' => $this->input->post('Edit_chqno'),
            'cus_cheque_date' => $this->input->post('Edit_date'),
            'cus_cheque_status' => $this->input->post('Edit_status')
        );
        $this->db->where('cus_cheque_id', $chqid);
        $this->db->update('ezy_pos_cus_cheque', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function updateCreditAmount($AmontDif,$chqid){
  //update credit amount accourding to cheque amont changes
        $str="UPDATE ezy_pos_cus_cheque        
        INNER JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_cus_cheque.cus_cheque_saleid
        SET cus_pay_credit=cus_pay_credit+'".$AmontDif."'
        WHERE cus_cheque_id='".$chqid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
  
    
    public function todaysCheque() {
        $today = $this->input->post('today');
    
        $sql = "
            SELECT 
                cus_name,
                cus_cheque_id,
                cus_cheque_amount,
                cus_cheque_bank,
                cus_cheque_num,
                cus_cheque_date,
                cus_cheque_status,
                cus_cheque_transferred 
            FROM ezy_pos_cus_cheque
            LEFT JOIN ezy_pos_customers 
                ON ezy_pos_customers.cus_id = ezy_pos_cus_cheque.cheque_cus_id
            WHERE cus_cheque_date = ?
            AND cus_cheque_status = 1
            ORDER BY cus_cheque_id
        ";
    
        $query = $this->db->query($sql, [$today]);
    
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function checkAdminPass(){
        $pass=$this->input->post('adminpass');
        $username=$this->input->post('username');
        $this->db->where('user_username',$username);
        $this->db->where('user_password',$pass);
        $this->db->where('user_role',1);        
        $query = $this->db->get('ezy_pos_users');
        if($query->num_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getPartyCheques(){
        $this->db->select('cus_cheque_id,cus_cheque_amount,cus_cheque_num,cus_cheque_date');
        $this->db->from('ezy_pos_cus_cheque');
        $this->db->where('cus_cheque_status',1);
        $this->db->where('cus_cheque_transferred',0);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function filterStatusDate() {
        $stts = $this->input->post('status');
        $from = $this->input->post('from');
        $to   = $this->input->post('to');
    
        $sql = "
            SELECT 
                cus_name,
                cus_cheque_id,
                cus_cheque_amount,
                cus_cheque_bank,
                cus_cheque_num,
                cus_cheque_date,
                cus_cheque_status,
                cus_cheque_transferred 
            FROM ezy_pos_cus_cheque
            LEFT JOIN ezy_pos_customers 
                ON ezy_pos_customers.cus_id = ezy_pos_cus_cheque.cheque_cus_id
            WHERE cus_cheque_date BETWEEN ? AND ?
            AND cus_cheque_status = ?
            ORDER BY cus_cheque_id
        ";
    
        $query = $this->db->query($sql, [$from, $to, $stts]);
    
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function getPartyChqDtails(){
        $pChqID=$this->input->post('id');
        $this->db->select('cus_cheque_id,cus_cheque_amount,cus_cheque_bank,cus_cheque_num,cus_cheque_date');
        $this->db->from('ezy_pos_cus_cheque');
        $this->db->where('cus_cheque_id',$pChqID);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function markAsTransterred(){
        $cusChqTblID=$this->input->post('hiddenCusChqID');
        $cnt =count($cusChqTblID);
        $res;
        for($x = 0; $x < $cnt; $x++){
            $updateData = array(
                'cus_cheque_transferred' => 1
            );
            $this->db->where('cus_cheque_id', $cusChqTblID[$x]);
            $this->db->update('ezy_pos_cus_cheque', $updateData);
            if($this->db->affected_rows()>0){
                $res= true;
            }
        }  
        if($res==true){
            return true;
        }
        else{
            return false;
        }
    }
    public function markAsTransterred1by1(){
        $cusChqTblID=$this->input->post('cusChqID');
        $updateData = array(
            'cus_cheque_transferred' => 1
        );
        $this->db->where('cus_cheque_id', $cusChqTblID);
        $this->db->update('ezy_pos_cus_cheque', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function TransferredHistory(){
        $cusChqid=$this->input->post('id');
        $str="SELECT  sup_name
        FROM ezy_pos_cus_cheque
        LEFT JOIN ezy_pos_sup_cheque ON ezy_pos_sup_cheque.sup_cheque_our0_cuscheqtblid=ezy_pos_cus_cheque.cus_cheque_id
        LEFT JOIN ezy_pos_grns ON ezy_pos_grns.grn_id=ezy_pos_sup_cheque.sup_cheque_grnid  
        LEFT JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id=ezy_pos_grns.grn_supplier_id 
        WHERE cus_cheque_id='".$cusChqid."'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function getPayment($saleID){
        $str="SELECT  cus_pay_cash,cus_pay_credit,sum(cus_cheque_amount) as cheq, count(cus_cheque_amount) as noOfChqs
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id
        LEFT JOIN ezy_pos_cus_cheque ON ezy_pos_cus_cheque.cus_cheque_saleid=ezy_pos_sale.sale_id 
        WHERE sale_id='".$saleID."'
        GROUP BY cus_cheque_saleid";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function OutStandingInvoices(){
        $cusid=$this->input->post('cusID');
        $str="SELECT  sale_id,cus_pay_credit
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id
        WHERE sale_cus_id='".$cusid."'
        AND cus_pay_credit>0
        ORDER BY sale_id asc";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function cusTtlCredit(){
        $cusid=$this->input->post('cusID');
        $str="SELECT  sale_id,sum(cus_pay_credit) as credits
        FROM ezy_pos_sale
        LEFT JOIN ezy_pos_cus_payment ON ezy_pos_cus_payment.cus_pay_saleid=ezy_pos_sale.sale_id
        WHERE sale_cus_id='".$cusid."'
        AND cus_pay_credit>0
        GROUP BY sale_cus_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function cusOpenningBalnce(){
        $cusid=$this->input->post('cusID');
        $this->db->select('cus_balance');
        $this->db->from('ezy_pos_customers');
        $this->db->where('cus_id', $cusid);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return $query->row()->cus_balance;
        }
        else{
            return false;
        }
    }
    public function updateOpnningBal(){
        $cusid=$this->input->post('cusID');
        $newbal=$this->input->post('value');
        $str="UPDATE ezy_pos_customers        
        SET cus_balance='".$newbal."'
        WHERE cus_id='".$cusid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getInvoiceCredit(){
        $saleid=$this->input->post('saleID');
        $this->db->select('cus_pay_credit');
        $this->db->from('ezy_pos_cus_payment');
        $this->db->where('cus_pay_saleid', $saleid);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return $query->row()->cus_pay_credit;
        }
        else{
            return false;
        }
    }
 
    

//New Functions

    
    public function getCustomerBalance($customerID)
    {
        $this->db->select('bal_amount');
        $this->db->from('ezy_pos_cus_balnce');
        $this->db->where('bal_cusid', $customerID);
        $query = $this->db->get();
    
        if ($query->num_rows() == 1) {
            return $query->row()->bal_amount; // Return the current balance
        } else {
            return false; // Return false if no record found
        }
    }
    public function updateCustomerBalance($customerID, $newBalance)
    {
        $data = ['bal_amount' => $newBalance]; // Set the new balance
    
        $this->db->where('bal_cusid', $customerID);
        $this->db->update('ezy_pos_cus_balnce', $data);
    
        return $this->db->affected_rows() > 0;
    }
        
    public function getCustomerIDByNewSaleID($saleID)
    {
        $this->db->select('sale_cus_id');
        $this->db->from('ezy_pos_sale');
        $this->db->where('sale_id', $saleID);
        $query = $this->db->get();
    
        if ($query->num_rows() == 1) {
            return $query->row()->sale_cus_id; // Return the customer ID
        } else {
            return false; // Sale ID not found
        }
    }
    public function deductNewCustomerBalance($customerID, $deductionAmount)
    {
        // Fetch the current balance for the customer
        $this->db->select('bal_amount');
        $this->db->from('ezy_pos_cus_balnce');
        $this->db->where('bal_cusid', $customerID);
        $query = $this->db->get();
    
        if ($query->num_rows() == 1) {
            $currentBalance = $query->row()->bal_amount;
    
            // Deduct the entered amount from the current balance
            $newBalance = $currentBalance + $deductionAmount; // Adding because balances are stored as negatives
    
            // Update the balance in the database
            $data = ['bal_amount' => $newBalance];
            $this->db->where('bal_cusid', $customerID);
            $this->db->update('ezy_pos_cus_balnce', $data);
    
            return $this->db->affected_rows() > 0;
        } else {
            return false; // Customer not found in balance table
        }
    }
        
}


