<?php
class SupplierPayment extends CI_Controller {
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
            $this->load->model('Payment_model');
            $this->load->model('SupPayment_model');
            $this->load->model('Configs_model');
    }

    public function supplierCash(){
        $result = $this->supPayment_model->supplierCash();
        echo json_encode($result);
    }
    public function supplierCashLog(){
        $result = $this->supPayment_model->supplierCashLog();
        echo json_encode($result);
    }
    public function supplierCheque(){
        $result = $this->supPayment_model->supplierCheque();
        echo json_encode($result);
    }
    public function partyCheques(){
        $result = $this->supPayment_model->partyCheques();
        echo json_encode($result);
    }
    public function ourCheque($page = 'index')
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
    public function getOurCheque(){
        $result = $this->supPayment_model->getOurCheque();
        echo json_encode($result);
    }
    public function filterOurCheque(){
        $result = $this->supPayment_model->filterOurCheque();
        echo json_encode($result);
    }
   
    public function deleteCheque(){
        $result = $this->supPayment_model->deleteCheque();
        echo json_encode($result);
    }
    public function editCheque(){
        $cheqId=$this->input->post('id');
        $user=$this->input->post('user');
        $userrole=$this->session->userdata('userrole');        
        if($userrole==1){
            $result = $this->supPayment_model->editCheque();//
            echo json_encode($result);
        }
        else if($user==0){
            $result = $this->supPayment_model->editCheque();//
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
        $previousAmount =0;
        $previousAmount = $this->supPayment_model->getPreviousAmount($chqid); //200
        $previousStatus = $this->supPayment_model->getPreviousStatus($chqid); //1

        $result1=$this->supPayment_model->updateCheque($chqid); //
        echo json_encode($result1);
        //update credit_amount  accourding to cheque amount changes , No change in status
        if($previousAmount!=$cheque_amount && $previousStatus==$cheque_status){            
            if($cheque_status==3||$cheque_status==4){
                $AmontDif=$cheque_amount-$previousAmount;
                //no change in returned & cancelled amount
               // $result2=$this->supPayment_model->updateCreditAmount($AmontDif,$chqid);
               return true;
            }
            elseif($cheque_status==1||$cheque_status==2){
                $AmontDif=$previousAmount-$cheque_amount;
                $result2=$this->supPayment_model->updateCreditAmount($AmontDif,$chqid);
                return $result2;
            }

        }
        //update credit  for amount change, if both status and cheque amount changes
        elseif($previousAmount!=$cheque_amount && $previousStatus!=$cheque_status){
            if(($previousStatus==1||$previousStatus==2)&&($cheque_status==3||$cheque_status==4)){
                $AmontDif=$previousAmount;
                $result2=$this->supPayment_model->updateCreditAmount($AmontDif,$chqid);
                return $result2;
            }        
            elseif(($previousStatus==3||$previousStatus==4)&&($cheque_status==1||$cheque_status==2)){
                $AmontDif=(-$cheque_amount);
                $result2=$this->supPayment_model->updateCreditAmount($AmontDif,$chqid);
                return $result2;
            }
            elseif(($previousStatus==1||$previousStatus==2)&&($cheque_status==1||$cheque_status==2)){
                $AmontDif=($previousAmount-$cheque_amount);
                $result2=$this->supPayment_model->updateCreditAmount($AmontDif,$chqid);
                return $result2;
            }
            elseif(($previousStatus==3||$previousStatus==4)&&($cheque_status==3||$cheque_status==4)){
                $AmontDif=($cheque_amount-$previousAmount);
                $result2=$this->supPayment_model->updateCreditAmount($AmontDif,$chqid);
                return $result2;
            }
        }
        //whn  status change, update credit for status change
        elseif($previousAmount==$cheque_amount && $previousStatus!=$cheque_status){
            if(($previousStatus==1||$previousStatus==2) && ($cheque_status==3||$cheque_status==4)){
                $result3=$this->supPayment_model->updateCreditAmount($previousAmount,$chqid);
                return $result3;
            }
            elseif(($previousStatus==3||$previousStatus==4) && ($cheque_status==1 || $cheque_status==2)){
                $result3=$this->supPayment_model->updateCreditAmount(-$previousAmount,$chqid);
                return $result3;
            }  
        }           
        
    }
    public function checkAdminPass(){
        $result = $this->supPayment_model->checkAdminPass();
        echo json_encode($result);
    }
    public function todaysCheque(){
        $result = $this->supPayment_model->todaysCheque();
        echo json_encode($result);
    }
    // additional Payments
    public function getSupplierID(){
        $result = $this->supPayment_model->getSupplierID();
        echo json_encode($result);
    }
    public function getGrnCredit(){
        $result = $this->supPayment_model->getGrnCredit();
        echo json_encode($result);
    }
    public function adjustCashCredit(){
        $result = $this->supPayment_model->adjustCashCredit();
        echo json_encode($result);
    }
    public function supPaymentCheque(){
        $result = $this->supPayment_model->supPaymentCheque();
        echo json_encode($result);
    }
    public function supplierChequeLog(){
        $result = $this->supPayment_model->supplierChequeLog();
        echo json_encode($result);
    }
    public function OutStandingGrns(){ 
        $result = $this->supPayment_model->OutStandingGrns();
        echo json_encode($result);
    }
    public function supplierPayCash(){ 
        $result = $this->supPayment_model->supplierPayCash();
        echo json_encode($result);
    }
    public function SupplierOutstanding(){
        $supid=$this->input->post('sup_ID');
        $result = $this->payment_model->SupplierOutstanding($supid);
        echo json_encode($result);
    }

}