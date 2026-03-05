<?php
class CustomerPayment extends CI_Controller {
    public function __construct()
    {
            parent::__construct();
            if ( ! $this->session->userdata('username'))
            { 
                redirect('login');
            }
            // else if(!$this->session->userdata('privexpense')==1){
                    // show_404();
            // }
           // $this->load->model('cusPayment_model');
            $this->load->model('CusPayment_model');
            $this->load->model('SupPayment_model');
           // $this->load->model('configs_model');
            $this->load->model('Configs_model');
    }

    public function customerCash(){
        //$result = $this->cusPayment_model->customerCash();
        $result = $this->CusPayment_model->customerCash();
        echo json_encode($result);
    }
    public function customerCashLog(){
        $result = $this->CusPayment_model->customerCashLog();
        echo json_encode($result);
    }
    public function customerChequeLog(){
        $result = $this->CusPayment_model->customerPayCheque();
        echo json_encode($result);
    }
    public function customerCheque(){ // multiple saleid not possible for 1 cheq_id ,
        $result = $this->CusPayment_model->customerCheque();
        echo json_encode($result);
    }

    public function cusPaymentCheque(){
        $result = $this->CusPayment_model->cusPaymentCheque();
        echo json_encode($result);
    }
    
    public function customerPayCheque(){ //multiple saleid possible for 1 cheq_id
        $result = $this->CusPayment_model->customerPayCheque();
        echo json_encode($result);
    }
    public function adjustCashCredit(){
        $result = $this->CusPayment_model->adjustCashCredit();
        echo json_encode($result);
    }
    public function OutStandingInvoices(){ 
        $result = $this->CusPayment_model->OutStandingInvoices();
        echo json_encode($result);
    }
    public function cusTtlCredit(){
        $result = $this->CusPayment_model->cusTtlCredit();
        echo json_encode($result);
    }
    public function cusOpenningBalnce(){
        $result = $this->CusPayment_model->cusOpenningBalnce();
        echo json_encode($result);
    }
    public function updateOpnningBal(){
        $result = $this->CusPayment_model->updateOpnningBal();
        echo json_encode($result);
    }
    public function getCustomerID(){
        $result = $this->CusPayment_model->getCustomerID();
        echo json_encode($result);
    }
    public function cusBalance(){ //sale & custr payment use this
        $result = $this->CusPayment_model->cusBalance();
        echo json_encode($result);
    }
    
    public function cusCreditUpdate(){ //sale payment use this
        $result = $this->CusPayment_model->cusCreditUpdate();
        echo json_encode($result);
    }
    public function getInvoiceCredit(){
        $result = $this->CusPayment_model->getInvoiceCredit();
        echo json_encode($result);
    }
    public function cusCheque($page = 'index')
    {
            if ( ! file_exists(APPPATH.'views/cheque/'.$page.'.php'))
            {
                    // Whoops, we don't have a page for that!
                    show_404();
            }
    
            $data['title'] = ucfirst($page);                
            $data['config'] = $this->Configs_model->getConfigName();

            $this->load->view('templates/header', $data);
            $this->load->view('cheque/'.$page);
            $this->load->view('templates/footer');
            $this->load->view('templates/rightslidebar');
            $this->load->view('templates/footerscripts');
    }
    public function getCusPendingCheque(){
        $result = $this->CusPayment_model->getCusPendingCheque();
        echo json_encode($result);
    }
    public function getCusCheque(){
        $result = $this->CusPayment_model->getCusCheque();
        echo json_encode($result);
    }
    public function filterCusCheque(){
        $result = $this->CusPayment_model->filterCusCheque();
        echo json_encode($result);
    }
    public function deleteCheque(){
        $result = $this->CusPayment_model->deleteCheque();
        echo json_encode($result);
    }
    public function editCheque(){
        $cheqId=$this->input->post('id');
        $user=$this->input->post('user');
        $userrole=$this->session->userdata('userrole');        
        if($userrole==1){
            $result = $this->CusPayment_model->editCheque();//
            echo json_encode($result);
        }
        else if($user==0){
            $result = $this->CusPayment_model->editCheque();//
            echo json_encode($result);
        }
        else{
            echo json_encode(false);
        }        
    }
    public function updateCheque(){
        $chqid = $this->input->post('HiddenChqID');
        $cheque_amount=$this->input->post('Edit_amount'); //100
        $cheque_status=$this->input->post('Edit_status'); //3
        $trnsfrrd=$this->input->post('HddnTrnsfrrd');// for party cheque
        $previousAmount =0;
        $previousAmount = $this->CusPayment_model->getPreviousAmount($chqid); //200
        $previousStatus = $this->CusPayment_model->getPreviousStatus($chqid); //1

        $result1=$this->CusPayment_model->updateCheque($chqid); //
        echo json_encode($result1);
        if($trnsfrrd==1){
            $result4=$this->SupPayment_model->updateSupCheque($chqid);
        }
        //update credit_amount  accourding to cheque amount changes , No change in status
        if($previousAmount!=$cheque_amount && $previousStatus==$cheque_status){   
                  
            if($cheque_status==3||$cheque_status==4){
                $AmontDif=$cheque_amount-$previousAmount;
                 //no change in returned & cancelled amount
              //  $result2=$this->CusPayment_model->updateCreditAmount($AmontDif,$chqid);  
                if($trnsfrrd==1){
              //  $result5=$this->SupPayment_model->updateSupCredit($AmontDif,$chqid);
                }
                return true;
            }
            elseif($cheque_status==1||$cheque_status==2){
                $AmontDif=$previousAmount-$cheque_amount;
                $result2=$this->CusPayment_model->updateCreditAmount($AmontDif,$chqid);
                //if party cheq: update amount for sup cheq
                if($trnsfrrd==1 && $result2==true){
                    $result5=$this->SupPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result5;
                }
            }
        }
        //update credit  for amount change, if both status and cheque amount changes
        elseif($previousAmount!=$cheque_amount && $previousStatus!=$cheque_status){          
            if(($previousStatus==1||$previousStatus==2)&&($cheque_status==3||$cheque_status==4)){//
                $AmontDif=$previousAmount;
                $result2=$this->CusPayment_model->updateCreditAmount($AmontDif,$chqid);
                if($trnsfrrd==1 && $result2==true){
                    $result6=$this->SupPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result6;
                }
            }        
            elseif(($previousStatus==3||$previousStatus==4)&&($cheque_status==1||$cheque_status==2)){//
                $AmontDif=(-$cheque_amount);
                $result2=$this->CusPayment_model->updateCreditAmount($AmontDif,$chqid);
                if($trnsfrrd==1 && $result2==true){
                    $result7=$this->SupPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result7;
                }
            }
            elseif(($previousStatus==1||$previousStatus==2)&&($cheque_status==1||$cheque_status==2)){
                $AmontDif=($previousAmount-$cheque_amount);
                $result2=$this->CusPayment_model->updateCreditAmount($AmontDif,$chqid);
                if($trnsfrrd==1 && $result2==true){
                    $result8=$this->SupPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result8;
                }
            }
            elseif(($previousStatus==3||$previousStatus==4)&&($cheque_status==3||$cheque_status==4)){
                $AmontDif=($cheque_amount-$previousAmount);
                $result2=$this->CusPayment_model->updateCreditAmount($AmontDif,$chqid);
                if($trnsfrrd==1 && $result2==true){
                    $result8=$this->SupPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result8;
                }
            }
        }
        //whn  status change, update credit for status change
        elseif($previousAmount==$cheque_amount && $previousStatus!=$cheque_status){
            if(($previousStatus==1||$previousStatus==2) && ($cheque_status==3||$cheque_status==4)){
                $result3=$this->CusPayment_model->updateCreditAmount($previousAmount,$chqid);
                if($trnsfrrd==1 && $result3==true){
                    $result8=$this->SupPayment_model->updateSupCredit($previousAmount,$chqid);
                    return $result8;
                }
            }
            elseif(($previousStatus==3||$previousStatus==4) && ($cheque_status==1 || $cheque_status==2)){
                $result3=$this->CusPayment_model->updateCreditAmount(-$previousAmount,$chqid);
                if($trnsfrrd==1 && $result3==true){
                    $result8=$this->SupPayment_model->updateSupCredit(-$previousAmount,$chqid);
                    return $result8;
                }
            } 
        }
           
    }
    public function todaysCheque(){
        $result = $this->CusPayment_model->todaysCheque();
        echo json_encode($result);
    }
    public function checkAdminPass(){
        $result = $this->CusPayment_model->checkAdminPass();
        echo json_encode($result);
    }
    public function filterStatusDate(){
        $result = $this->CusPayment_model->filterStatusDate();
        echo json_encode($result);
    }
    public function getPartyChqDtails(){
        $result = $this->CusPayment_model->getPartyChqDtails();
        echo json_encode($result);
    }
    public function markAsTransterred(){
        $result = $this->CusPayment_model->markAsTransterred();
        echo json_encode($result);
    }
    public function markAsTransterred1by1(){
        $result = $this->CusPayment_model->markAsTransterred1by1();
        echo json_encode($result);
    }
    public function TransferredHistory(){
        $result = $this->CusPayment_model->TransferredHistory();
        echo json_encode($result);
    }




    //New Functions
    
    public function updateCustomerBalance()
    {
        $customerID = $this->input->post('customer_id'); // Get customer ID from the request
        $cashValue = $this->input->post('cash'); // Get the cash value entered by the user
    
        if (!empty($customerID) && isset($cashValue)) {
            $this->load->model('CusPayment_model'); // Ensure the model is loaded
    
            // Fetch the current balance for the customer
            $currentBalance = $this->CusPayment_model->getCustomerBalance($customerID);
    
            if ($currentBalance !== false) {
                // Add the cash value to the current (negative) balance
                $updatedBalance = $currentBalance + $cashValue;
    
                // Update the balance in the database
                $result = $this->CusPayment_model->updateCustomerBalance($customerID, $updatedBalance);

                
                if ($result) {
                    echo json_encode(['status' => 'success', 'new_balance' => $updatedBalance]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update balance.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Customer balance not found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
        }
    }
    
    public function newProcessCashPayment()
    {
        $invoiceID = $this->input->post('invoice_id'); // Get the entered invoice ID (AS00+sale_id)
        $cashValue = $this->input->post('cash_value'); // Get the entered cash value
    
        if (!empty($invoiceID) && isset($cashValue)) {
            // Remove "AS00" prefix to get the sale ID
            $saleID = str_replace("AS00", "", $invoiceID);
    
            $this->load->model('CusPayment_model'); // Load the model
    
            // Fetch customer ID from the sale ID
            $customerID = $this->CusPayment_model->getCustomerIDByNewSaleID($saleID);
    
            if ($customerID) {
                // Deduct cash value from the customer's balance
                $result = $this->CusPayment_model->deductNewCustomerBalance($customerID, $cashValue);
    
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Cash payment processed successfully!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to process cash payment.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Sale ID or customer ID not found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
        }
    }

    
    public function newProcessChequePayment()
{
    $invoiceID = $this->input->post('invoice_id'); // Get the entered invoice ID (AS00+sale_id)
    $chequeValue = $this->input->post('cheque_value'); // Get the entered cheque value

    if (!empty($invoiceID) && isset($chequeValue)) {
        // Remove "AS00" prefix to get the sale ID
        $saleID = str_replace("AS00", "", $invoiceID);

        $this->load->model('CusPayment_model'); // Load the model

        // Fetch customer ID from the sale ID
        $customerID = $this->CusPayment_model->getCustomerIDByNewSaleID($saleID);
        $chequeLogID = $this->CusPayment_model->customerChequeLog();

        if ($customerID) {
            // Deduct cheque value from the customer's balance
            $result = $this->CusPayment_model->deductNewCustomerBalance($customerID, $chequeValue);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Cheque payment processed successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to process cheque payment.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Sale ID or customer ID not found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
    }
}


}