<?php
class SupPayment_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function supplierCash(){
        $data = array(
            'sup_pay_grnid' => $this->input->post('grnID'),
            'sup_pay_cash' => $this->input->post('cash'),
            'sup_pay_credit' => $this->input->post('credit')          
        );
        return $this->db->insert('ezy_pos_sup_payment', $data);
    }
    public function supplierCashLog(){
        $data = array(
            'paycashlog_grnid'=>$this->input->post('grnID'),
            'paycashlog_cashid'=>$this->input->post('cashID'), //--
            'paycashlog_amount'=>$this->input->post('cash'),   
            'paycashlog_date'=>$this->input->post('date'),
            'paycashlog_status'=>1  
        );
        return $this->db->insert('ezy_pos_sup_paymentcashlog', $data);
    }
    
    
    public function supplierCheque(){
        $grnid      = $this->input->post('grnID');
        $supid      = $this->input->post('supplier_newid'); // ✅ Get sup_id from POST
        $amount     = $this->input->post('amount');
        $bank       = $this->input->post('bank');
        $num        = $this->input->post('chequeno');
        $date       = $this->input->post('chequedate');
        $givenDate  = $this->input->post('date');
        $cnt        = count($amount);
    
        for ($x = 0; $x < $cnt; $x++) {
            $data = array(
                'sup_cheque_grnid'        => $grnid,
                'sup_cheque_supid'        => $supid,         // ✅ Insert sup_id here
                'sup_cheque_amount'       => $amount[$x],
                'sup_cheque_bank'         => $bank[$x],
                'sup_cheque_num'          => $num[$x],
                'sup_cheque_our0_cuscheqtblid' => 0,
                'sup_cheque_date'         => $date[$x],
                'sup_cheque_givendate'    => $givenDate,
                'sup_cheque_status'       => 1
            );
            $this->db->insert('ezy_pos_sup_cheque', $data);
            $insert_id = $this->db->insert_id();
    
            $datalog = array(
                'supchqlog_chqid'     => $insert_id,
                'supchqlog_grnid'     => $grnid,
                'supchqlog_amnt'      => $amount[$x],
                'supchqlog_givndate'  => $givenDate,
                'supchqlog_status'    => 1
            );
            $this->db->insert('ezy_pos_sup_chequelog', $datalog);
        }
    
        return true;
    }
    
    
    
    // public function supplierCheque(){
    //     $grnid=$this->input->post('grnID');
    //     $amount=$this->input->post('amount');
    //     $bank=$this->input->post('bank');
    //     $num=$this->input->post('chequeno');
    //     $date=$this->input->post('chequedate');
    //     $givenDate=$this->input->post('date');
    //     $cnt =count($amount);
    //     for($x = 0; $x < $cnt; $x++){
    //         $data = array(
    //             'sup_cheque_grnid'=>$grnid,
    //             'sup_cheque_amount'=>$amount[$x],
    //             'sup_cheque_bank'=>$bank[$x],   
    //             'sup_cheque_num'=>$num[$x],
    //             'sup_cheque_our0_cuscheqtblid'=>0,  //if it is our cheque then 0
    //             'sup_cheque_date'=>$date[$x],
    //             'sup_cheque_givendate'=>$givenDate,
    //             'sup_cheque_status'=>1
    //         );
    //         $this->db->insert('ezy_pos_sup_cheque', $data);
    //         $insert_id = $this->db->insert_id();            
            
    //         $datalog = array(
    //             'supchqlog_chqid'=>$insert_id,
    //             'supchqlog_grnid'=>$grnid,
    //             'supchqlog_amnt'=>$amount[$x],   
    //             'supchqlog_givndate'=>$givenDate,
    //             'supchqlog_status'=>1
    //         );
    //         $this->db->insert('ezy_pos_sup_chequelog', $datalog);          
    //     }
    //     return true;
    // }
    
    
    
    public function partyCheques(){
        $cusChqTblID = $this->input->post('hiddenCusChqID');
        $amount      = $this->input->post('amountParty');
        $bank        = $this->input->post('bankParty');
        $num         = $this->input->post('chequenoParty');
        $date        = $this->input->post('chequedateParty');
        $grnid       = $this->input->post('grnID');
        $givenDate   = $this->input->post('date');
        $supid       = $this->input->post('supplier_newid');// ✅ Get sup_id from POST
    
        $cnt = count($amount);
        for ($x = 0; $x < $cnt; $x++) {
            $data = array(
                'sup_cheque_grnid'            => $grnid,
                'sup_cheque_supid'            => $supid,                  // ✅ Insert sup_id here
                'sup_cheque_amount'           => $amount[$x],
                'sup_cheque_bank'             => $bank[$x],
                'sup_cheque_num'              => $num[$x],
                'sup_cheque_our0_cuscheqtblid'=> $cusChqTblID[$x],        // Party cheque link
                'sup_cheque_date'             => $date[$x],
                'sup_cheque_givendate'        => $givenDate,
                'sup_cheque_status'           => 1
            );
            $this->db->insert('ezy_pos_sup_cheque', $data);
            $insert_id = $this->db->insert_id();
    
            $datalog = array(
                'supchqlog_chqid'     => $insert_id,
                'supchqlog_grnid'     => $grnid,
                'supchqlog_amnt'      => $amount[$x],
                'supchqlog_givndate'  => $givenDate,
                'supchqlog_status'    => 1
            );
            $this->db->insert('ezy_pos_sup_chequelog', $datalog);
        }
    
        return true;
    }

    
    
    
    
    // public function partyCheques(){
    //     $cusChqTblID=$this->input->post('hiddenCusChqID');
    //     $amount=$this->input->post('amountParty');
    //     $bank=$this->input->post('bankParty');
    //     $num=$this->input->post('chequenoParty');
    //     $date=$this->input->post('chequedateParty');
    //     $grnid=$this->input->post('grnID');
    //     $givenDate=$this->input->post('date');
    //     $cnt =count($amount);
    //     for($x = 0; $x < $cnt; $x++){
    //         $data = array(
    //             //'sup_cheque_grnid'=>0, updated by W
    //             'sup_cheque_grnid'=>$grnid,
    //             'sup_cheque_amount'=>$amount[$x],
    //             'sup_cheque_bank'=>$bank[$x],   
    //             'sup_cheque_num'=>$num[$x],
    //             'sup_cheque_our0_cuscheqtblid'=>$cusChqTblID[$x],  //if it is partychque ,then cusCheqID
    //             'sup_cheque_date'=>$date[$x],
    //             'sup_cheque_givendate'=>$givenDate,
    //             'sup_cheque_status'=>1
    //         );
    //         $this->db->insert('ezy_pos_sup_cheque', $data);
    //         $insert_id = $this->db->insert_id();

    //         $datalog = array(
    //             'supchqlog_chqid'=>$insert_id,
    //             'supchqlog_grnid'=>$grnid,
    //             'supchqlog_amnt'=>$amount[$x],   
    //             'supchqlog_givndate'=>$givenDate,
    //             'supchqlog_status'=>1
    //         );
    //       // $this->db->insert('ezy_pos_sup_cheque', $datalog); edited by w
    //         $this->db->insert('ezy_pos_sup_chequelog', $datalog);


    //     }
    //     return true;
    // }
    
    public function getourCheque() {
        $str = "SELECT 
                    s.sup_name,
                    c.sup_cheque_id,
                    c.sup_cheque_amount,
                    c.sup_cheque_num,
                    c.sup_cheque_date,
                    c.sup_cheque_status,
                    b.bank_name 
                FROM ezy_pos_sup_cheque c
                LEFT JOIN ezy_pos_bank b ON b.bank_id = c.sup_cheque_bank
                INNER JOIN ezy_pos_suppliers s ON s.sup_id = c.sup_cheque_supid
                WHERE c.sup_cheque_status != 0
                ORDER BY c.sup_cheque_id";
    
        $query = $this->db->query($str);
    
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    // public function getourCheque(){
    //     $str="SELECT ezy_pos_sup_cheque.sup_cheque_id, 
    //                 ezy_pos_sup_cheque.sup_cheque_amount, 
    //                 ezy_pos_sup_cheque.sup_cheque_num, 
    //                 ezy_pos_sup_cheque.sup_cheque_date, 
    //                 ezy_pos_sup_cheque.sup_cheque_status,
    //                 ezy_pos_bank.bank_name,
    //                 ezy_pos_suppliers.sup_name 
    //           FROM ezy_pos_sup_cheque
    //           LEFT JOIN ezy_pos_bank ON ezy_pos_bank.bank_id = ezy_pos_sup_cheque.sup_cheque_bank
    //           LEFT JOIN ezy_pos_grns ON ezy_pos_grns.grn_id = ezy_pos_sup_cheque.sup_cheque_grnid
    //           LEFT JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id = ezy_pos_grns.grn_supplier_id
    //           WHERE ezy_pos_sup_cheque.sup_cheque_status != 0
    //           ORDER BY ezy_pos_sup_cheque.sup_cheque_id";
        
    //     $query = $this->db->query($str);
    //     if($query->num_rows() > 0){
    //         return $query->result();
    //     } else {
    //         return false;
    //     }
    // }
    
    //all belows not in use
    public function filterOurCheque(){
        $from=$this->input->post('from');
        $to=$this->input->post('to');
        $str="SELECT sup_name,sup_cheque_id,sup_cheque_amount,sup_cheque_num,sup_cheque_date,sup_cheque_status,
        bank_name 
        from ezy_pos_sup_cheque
        LEFT JOIN ezy_pos_bank ON ezy_pos_bank.bank_id=ezy_pos_sup_cheque.sup_cheque_bank
        LEFT JOIN ezy_pos_grns ON ezy_pos_grns.grn_id=ezy_pos_sup_cheque.sup_cheque_grnid 
        INNER JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id=ezy_pos_grns.grn_supplier_id
        WHERE sup_cheque_date BETWEEN '".$from."' AND '".$to."'
        AND sup_cheque_status!=0
        ORDER BY sup_cheque_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    // public function filterOurCheque(){
    //     $from = $this->input->post('from');
    //     $to = $this->input->post('to');
        
    //     $str = "SELECT ezy_pos_sup_cheque.sup_cheque_id, 
    //                    ezy_pos_sup_cheque.sup_cheque_amount, 
    //                    ezy_pos_sup_cheque.sup_cheque_num, 
    //                    ezy_pos_sup_cheque.sup_cheque_date,  
    //                    ezy_pos_sup_cheque.sup_cheque_status,
    //                    ezy_pos_bank.bank_name,
    //                    COALESCE(ezy_pos_suppliers.sup_name, 'N/A') AS sup_name
    //             FROM ezy_pos_sup_cheque
    //             LEFT JOIN ezy_pos_bank ON ezy_pos_bank.bank_id = ezy_pos_sup_cheque.sup_cheque_bank
    //             LEFT JOIN ezy_pos_grns ON ezy_pos_grns.grn_id = ezy_pos_sup_cheque.sup_cheque_grnid
    //             LEFT JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id = ezy_pos_grns.grn_supplier_id
    //             WHERE ezy_pos_sup_cheque.sup_cheque_date BETWEEN ? AND ?
    //             AND ezy_pos_sup_cheque.sup_cheque_status != 0
    //             ORDER BY ezy_pos_sup_cheque.sup_cheque_id";
    
    //     $query = $this->db->query($str, array($from, $to));
    
    //     if($query->num_rows() > 0){
    //         return $query->result();
    //     } else {
    //         return false;
    //     }
    // }
    
    public function deleteCheque(){
        $chqid=$this->input->post('id');
        $updateData = array(
            'sup_cheque_status' => 0
        );
        $this->db->where('sup_cheque_id', $chqid);
        $this->db->update('ezy_pos_sup_cheque', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function editCheque(){
        $chqid = $this->input->post('id');
        $this->db->where('sup_cheque_id', $chqid);
        $query = $this->db->get('ezy_pos_sup_cheque');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function getPreviousAmount($chqid){
        //$chqid = $this->input->post('HiddenChqID');
        $this->db->select('sup_cheque_amount');
        $this->db->from('ezy_pos_sup_cheque');
        $this->db->where('sup_cheque_id', $chqid);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return $query->row()->sup_cheque_amount;
        }
        else{
            return false;
        }
    }
    public function getPreviousStatus($chqid){
        $this->db->select('sup_cheque_status');
        $this->db->from('ezy_pos_sup_cheque');
        $this->db->where('sup_cheque_id', $chqid);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return $query->row()->sup_cheque_status;
        }
        else{
            return false;
        }
    }
    
    public function updateCheque($chqid){
        $updateData = array(
            'sup_cheque_amount' => $this->input->post('Edit_amount'),            
            'sup_cheque_bank' => $this->input->post('Edit_bank'),
            'sup_cheque_num' => $this->input->post('Edit_chqno'),
            'sup_cheque_date' => $this->input->post('Edit_date'),
            'sup_cheque_status' => $this->input->post('Edit_status')
        );
        $this->db->where('sup_cheque_id', $chqid);
        $this->db->update('ezy_pos_sup_cheque', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function updateCreditAmount($AmontDif,$chqid){
  //update credit amount accourding to cheque amont changes
        $str="UPDATE ezy_pos_sup_cheque        
        INNER JOIN ezy_pos_sup_payment ON ezy_pos_sup_payment.sup_pay_grnid=ezy_pos_sup_cheque.sup_cheque_grnid
        SET sup_pay_credit=sup_pay_credit +'".$AmontDif."'
        WHERE sup_cheque_id='".$chqid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
  
    
    public function todaysCheque(){
        $today=$this->input->post('today');
        $str="SELECT sup_name,sup_cheque_id,sup_cheque_amount,sup_cheque_num,sup_cheque_date,sup_cheque_status,
        bank_name 
        from ezy_pos_sup_cheque
        LEFT JOIN ezy_pos_bank ON ezy_pos_bank.bank_id=ezy_pos_sup_cheque.sup_cheque_bank
        LEFT JOIN ezy_pos_grns ON ezy_pos_grns.grn_id=ezy_pos_sup_cheque.sup_cheque_grnid 
        INNER JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id=ezy_pos_grns.grn_supplier_id
        WHERE sup_cheque_date='".$today."'
        AND sup_cheque_status!=0
        ORDER BY sup_cheque_id";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
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
    #### Party Cheques ####
    public function updateSupCheque($CusChqid){
        $updateData = array(
            'sup_cheque_amount' => $this->input->post('Edit_amount'),            
            'sup_cheque_bank' => $this->input->post('Edit_bank'),
            'sup_cheque_num' => $this->input->post('Edit_chqno'),
            'sup_cheque_date' => $this->input->post('Edit_date'),
            'sup_cheque_status' => $this->input->post('Edit_status')
        );
        $this->db->where('sup_cheque_our0_cuscheqtblid', $CusChqid);
        $this->db->update('ezy_pos_sup_cheque', $updateData);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function updateSupCredit($AmontDif,$CusChqid){
        //update sup credit amount accourding to party cheq amount edited
        $str="UPDATE ezy_pos_sup_cheque        
        Left JOIN ezy_pos_cus_cheque ON ezy_pos_cus_cheque.cus_cheque_id=ezy_pos_sup_cheque.sup_cheque_our0_cuscheqtblid        
        INNER JOIN ezy_pos_sup_payment ON ezy_pos_sup_payment.sup_pay_grnid=ezy_pos_sup_cheque.sup_cheque_grnid
        SET sup_pay_credit=sup_pay_credit +'".$AmontDif."'
        WHERE sup_cheque_our0_cuscheqtblid='".$CusChqid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
//additional payments
    public function getSupplierID(){
        $grnid=$this->input->post('grnID');
        $this->db->select('grn_supplier_id');
        $this->db->from('ezy_pos_grns');
        $this->db->where('grn_id', $grnid);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return $query->row()->grn_supplier_id;
        }
        else{
            return false;
        }
    }
    public function getGrnCredit(){
        $grnid=$this->input->post('grnID');
        $this->db->select('sup_pay_credit');
        $this->db->from('ezy_pos_sup_payment');
        $this->db->where('sup_pay_grnid', $grnid);
        $query = $this->db->get();
        if($query->num_rows()==1){
            return $query->row()->sup_pay_credit;
        }
        else{
            return false;
        }
    }
    public function adjustCashCredit(){
        $grnid=$this->input->post('grnID');
        $cash=$this->input->post('cash');
        $cheq=$this->input->post('cheq');
        $total=$cash+$cheq;
        $str="UPDATE ezy_pos_sup_payment        
        INNER JOIN ezy_pos_grns ON ezy_pos_grns.grn_id=ezy_pos_sup_payment.sup_pay_grnid
        SET sup_pay_cash=sup_pay_cash+'".$cash."',
        sup_pay_credit=sup_pay_credit-'".$total."'
        WHERE sup_pay_grnid='".$grnid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    
    
    public function adjust_supllier_CashCredit(){
        $grnid=$this->input->post('grnID');
        $cash=$this->input->post('cash');
        $cheq=$this->input->post('cheq');
        $total=$cash+$cheq;
        $str="UPDATE ezy_pos_suppliers s      
        INNER JOIN ezy_pos_grns g ON g.grn_supplier_id=s.sup_id 
        SET s.sup_balance=s.sup_balance - '".$total."'
        WHERE g.grn_id='".$grnid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function supplier_nongrn_balance_update(){
        $supid=$this->input->post('sup_id');
        $total=$this->input->post('non_Grn_balance_rest');
       
        $str="UPDATE ezy_pos_suppliers s      
        SET s.sup_balance=s.sup_balance - '".$total."'
        WHERE s.sup_id='".$supid."'";
        $query = $this->db->query($str);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function supPaymentCheque(){
        $grnid = $this->input->post('grnID');
        $amount = $this->input->post('amount2');
        $bank = $this->input->post('bank');
        $num = $this->input->post('chequeno');
        $cusChqID = $this->input->post('cusChqID');
        $date = $this->input->post('chequedate');
        $givendate = $this->input->post('date');
        $supID = $this->input->post('supplier_newid'); // ✅ Get supplier ID from POST
    
        $data = array(
            'sup_cheque_grnid'           => $grnid,
            'sup_cheque_supid'           => $supID,           // ✅ Add this line
            'sup_cheque_amount'          => $amount,
            'sup_cheque_bank'            => $bank,   
            'sup_cheque_num'             => $num,
            'sup_cheque_our0_cuscheqtblid'=> $cusChqID,  // partycheque/ourCheq: if it is our cheque then 0
            'sup_cheque_date'            => $date,
            'sup_cheque_givendate'       => $givendate,
            'sup_cheque_status'          => 1
        );
    
        $this->db->insert('ezy_pos_sup_cheque', $data);
        $insert_id = $this->db->insert_id();             
        return $insert_id;  
    }
    public function supplierChequeLog(){// 1 cheaq payable for multiple grns(grn_id) 
        $supChqID=$this->input->post('supChqID');
        $grnid=$this->input->post('grnID');
        $amount=$this->input->post('Amnt');
        $givendate=$this->input->post('date');
                $data = array(
                    'supchqlog_chqid'=>$supChqID,
                    'supchqlog_grnid'=>$grnid,
                    'supchqlog_amnt'=>$amount,
                    'supchqlog_givndate'=>$givendate,
                    'supchqlog_status'=>1
                );
                $result= $this->db->insert('ezy_pos_sup_chequelog', $data);       
                return $result;
    }
    public function OutStandingGrns(){
        $supid=$this->input->post('sup_ID');
        $str="SELECT  grn_id,sup_pay_credit,sup_balance
        FROM ezy_pos_grns
        LEFT JOIN ezy_pos_sup_payment ON ezy_pos_sup_payment.sup_pay_grnid=ezy_pos_grns.grn_id
        LEFT JOIN ezy_pos_suppliers ON ezy_pos_suppliers.sup_id='".$supid."'
        WHERE grn_supplier_id='".$supid."'
        AND sup_pay_credit>0
        ORDER BY grn_id asc";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function supplierPayCash(){// cash main table
        $data = array(
            'supcash_supid' => $this->input->post('sup_ID'),
            'supcash_amount' => $this->input->post('cash'),
            'supcash_date' => $this->input->post('date'),
            'supcash_status' =>1       
        );
        $this->db->insert('ezy_pos_sup_cash_payment', $data);
        $insert_id = $this->db->insert_id();             
        return  $insert_id;
    }
}