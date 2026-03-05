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
        $result = $this->cusPayment_model->customerCash();
        echo json_encode($result);
    }
    public function customerCashLog(){
        $result = $this->cusPayment_model->customerCashLog();
        echo json_encode($result);
    }
    public function customerCheque(){ // multiple saleid not possible for 1 cheq_id ,
        $result = $this->cusPayment_model->customerCheque();
        echo json_encode($result);
    }

    public function cusPaymentCheque(){
        $result = $this->cusPayment_model->cusPaymentCheque();
        echo json_encode($result);
    }
    
    public function customerPayCheque(){ //multiple saleid possible for 1 cheq_id
        $result = $this->cusPayment_model->customerPayCheque();
        echo json_encode($result);
    }
    public function adjustCashCredit(){
        $result = $this->cusPayment_model->adjustCashCredit();
        echo json_encode($result);
    }
    public function OutStandingInvoices(){ 
        $result = $this->cusPayment_model->OutStandingInvoices();
        echo json_encode($result);
    }
    public function cusTtlCredit(){
        $result = $this->cusPayment_model->cusTtlCredit();
        echo json_encode($result);
    }
    public function cusOpenningBalnce(){
        $result = $this->cusPayment_model->cusOpenningBalnce();
        echo json_encode($result);
    }
    public function updateOpnningBal(){
        $result = $this->cusPayment_model->updateOpnningBal();
        echo json_encode($result);
    }
    public function getCustomerID(){
        $result = $this->cusPayment_model->getCustomerID();
        echo json_encode($result);
    }
    public function cusBalance(){ //sale & custr payment use this
        $result = $this->cusPayment_model->cusBalance();
        echo json_encode($result);
    }
    
    public function cusCreditUpdate(){ //sale payment use this
        $result = $this->cusPayment_model->cusCreditUpdate();
        echo json_encode($result);
    }
    public function getInvoiceCredit(){
        $result = $this->cusPayment_model->getInvoiceCredit();
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
            $data['config'] = $this->configs_model->getConfigName();

            $this->load->view('templates/header', $data);
            $this->load->view('cheque/'.$page);
            $this->load->view('templates/footer');
            $this->load->view('templates/rightslidebar');
            $this->load->view('templates/footerscripts');
    }
    public function getCusPendingCheque(){
        $result = $this->cusPayment_model->getCusPendingCheque();
        echo json_encode($result);
    }
    public function getCusCheque(){
        $result = $this->cusPayment_model->getCusCheque();
        echo json_encode($result);
    }
    public function filterCusCheque(){
        $result = $this->cusPayment_model->filterCusCheque();
        echo json_encode($result);
    }
    public function deleteCheque(){
        $result = $this->cusPayment_model->deleteCheque();
        echo json_encode($result);
    }
    public function editCheque(){
        $cheqId=$this->input->post('id');
        $user=$this->input->post('user');
        $userrole=$this->session->userdata('userrole');        
        if($userrole==1){
            $result = $this->cusPayment_model->editCheque();//
            echo json_encode($result);
        }
        else if($user==0){
            $result = $this->cusPayment_model->editCheque();//
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
        $previousAmount = $this->cusPayment_model->getPreviousAmount($chqid); //200
        $previousStatus = $this->cusPayment_model->getPreviousStatus($chqid); //1

        $result1=$this->cusPayment_model->updateCheque($chqid); //
        echo json_encode($result1);
        if($trnsfrrd==1){
            $result4=$this->supPayment_model->updateSupCheque($chqid);
        }
        //update credit_amount  accourding to cheque amount changes , No change in status
        if($previousAmount!=$cheque_amount && $previousStatus==$cheque_status){   
                  
            if($cheque_status==3||$cheque_status==4){
                $AmontDif=$cheque_amount-$previousAmount;
                 //no change in returned & cancelled amount
              //  $result2=$this->cusPayment_model->updateCreditAmount($AmontDif,$chqid);  
                if($trnsfrrd==1){
              //  $result5=$this->supPayment_model->updateSupCredit($AmontDif,$chqid);
                }
                return true;
            }
            elseif($cheque_status==1||$cheque_status==2){
                $AmontDif=$previousAmount-$cheque_amount;
                $result2=$this->cusPayment_model->updateCreditAmount($AmontDif,$chqid);
                //if party cheq: update amount for sup cheq
                if($trnsfrrd==1 && $result2==true){
                    $result5=$this->supPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result5;
                }
            }
        }
        //update credit  for amount change, if both status and cheque amount changes
        elseif($previousAmount!=$cheque_amount && $previousStatus!=$cheque_status){          
            if(($previousStatus==1||$previousStatus==2)&&($cheque_status==3||$cheque_status==4)){//
                $AmontDif=$previousAmount;
                $result2=$this->cusPayment_model->updateCreditAmount($AmontDif,$chqid);
                if($trnsfrrd==1 && $result2==true){
                    $result6=$this->supPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result6;
                }
            }        
            elseif(($previousStatus==3||$previousStatus==4)&&($cheque_status==1||$cheque_status==2)){//
                $AmontDif=(-$cheque_amount);
                $result2=$this->cusPayment_model->updateCreditAmount($AmontDif,$chqid);
                if($trnsfrrd==1 && $result2==true){
                    $result7=$this->supPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result7;
                }
            }
            elseif(($previousStatus==1||$previousStatus==2)&&($cheque_status==1||$cheque_status==2)){
                $AmontDif=($previousAmount-$cheque_amount);
                $result2=$this->cusPayment_model->updateCreditAmount($AmontDif,$chqid);
                if($trnsfrrd==1 && $result2==true){
                    $result8=$this->supPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result8;
                }
            }
            elseif(($previousStatus==3||$previousStatus==4)&&($cheque_status==3||$cheque_status==4)){
                $AmontDif=($cheque_amount-$previousAmount);
                $result2=$this->cusPayment_model->updateCreditAmount($AmontDif,$chqid);
                if($trnsfrrd==1 && $result2==true){
                    $result8=$this->supPayment_model->updateSupCredit($AmontDif,$chqid);
                    return $result8;
                }
            }
        }
        //whn  status change, update credit for status change
        elseif($previousAmount==$cheque_amount && $previousStatus!=$cheque_status){
            if(($previousStatus==1||$previousStatus==2) && ($cheque_status==3||$cheque_status==4)){
                $result3=$this->cusPayment_model->updateCreditAmount($previousAmount,$chqid);
                if($trnsfrrd==1 && $result3==true){
                    $result8=$this->supPayment_model->updateSupCredit($previousAmount,$chqid);
                    return $result8;
                }
            }
            elseif(($previousStatus==3||$previousStatus==4) && ($cheque_status==1 || $cheque_status==2)){
                $result3=$this->cusPayment_model->updateCreditAmount(-$previousAmount,$chqid);
                if($trnsfrrd==1 && $result3==true){
                    $result8=$this->supPayment_model->updateSupCredit(-$previousAmount,$chqid);
                    return $result8;
                }
            } 
        }
           
    }
    public function todaysCheque(){
        $result = $this->cusPayment_model->todaysCheque();
        echo json_encode($result);
    }
    public function checkAdminPass(){
        $result = $this->cusPayment_model->checkAdminPass();
        echo json_encode($result);
    }
    public function filterStatusDate(){
        $result = $this->cusPayment_model->filterStatusDate();
        echo json_encode($result);
    }
    public function getPartyChqDtails(){
        $result = $this->cusPayment_model->getPartyChqDtails();
        echo json_encode($result);
    }
    public function markAsTransterred(){
        $result = $this->cusPayment_model->markAsTransterred();
        echo json_encode($result);
    }
    public function markAsTransterred1by1(){
        $result = $this->cusPayment_model->markAsTransterred1by1();
        echo json_encode($result);
    }
    public function TransferredHistory(){
        $result = $this->cusPayment_model->TransferredHistory();
        echo json_encode($result);
    }

}