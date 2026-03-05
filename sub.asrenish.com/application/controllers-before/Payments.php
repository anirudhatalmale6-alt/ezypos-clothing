<?php
class Payments extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
               // else if(!$this->session->userdata('privgrn')==1){
                       // show_404();
               // }
               $this->load->model('Configs_model'); 
               $this->load->model('Customers_model');
               $this->load->model('Sales_model');
               $this->load->model('Payment_model');
               $this->load->model('Suppliers_model');
               $this->load->model('Grns_model');
               $this->load->helper("language");        
        }

        public function CusPaymentGET($page = 'index')
        {
                if (! file_exists(APPPATH.'views/payment/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data1['title'] = ucfirst($page);
                $data1['config'] = $this->Configs_model->getConfigName();
                $data = array(                                  
                        'customers' => $this->Customers_model->getCustomers(),
                        'invoices' => $this->Sales_model->getInvoices()
                );
        
                $this->load->view('templates/header', $data1);
                $this->load->view('payment/'.$page,$data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function SupPaymentGET($page = 'index')
        {
                if (! file_exists(APPPATH.'views/payment/'.$page.'.php'))
                {
                        // Whoops, we don't have a page for that!
                        show_404();
                }
                $data1['title'] = ucfirst($page);
                $data1['config'] = $this->Configs_model->getConfigName();
                $data = array(                                  
                        'suppliers' => $this->Suppliers_model->getSuppliers(),
                        'grns' => $this->Grns_model->getGrns()
                );        
                $this->load->view('templates/header', $data1);
                $this->load->view('payment/'.$page,$data);
                $this->load->view('templates/footer');
                $this->load->view('templates/rightslidebar');
                $this->load->view('templates/footerscripts');
        }
        public function getCustomerPayments(){
                $cusid = $this->input->post('cusid');
                $res1 = $this->Payment_model->getCustomerPayments($cusid);
                $res2 = $this->Payment_model->CustomerOutstanding($cusid);
                $res3 = $this->Payment_model->CustomerBalance($cusid);
                $res4 = $this->Payment_model->getCustomerPayments2($cusid); //1 payment many sales
                //$res1=array(1,2,4);
                //$res2=array(1,2,4);
                //$res3=array(1,2,4);
                $result=array_merge($res1,$res4);
                echo json_encode(array($result,$res2,$res3));
        }
        public function getInvoiceByID(){
                $res1=$this->Payment_model->getInvoiceByIDCash(); //cash payments search by invc ID
                $res2=$this->Payment_model->getInvoiceByIDCheq();
                $res3=$this->Payment_model->getInvoiceOutstandingbyID();
                $res4=$this->Payment_model->getInvoiceCheqTotalbyID();
                $cusid = $this->Payment_model->getCusID();
                $res5 = $this->Payment_model->CustomerOutstanding($cusid);
                echo json_encode(array($res1,$res2,$res3,$res4,$res5));
        }//*-+
        public function getSupPayments(){ //payment by sup_id, select outstanding grns of a supplier
                $supid=$this->input->post('supid');
                $res1 = $this->Payment_model->getSupplierPayments3($supid);
                $res2 = $this->Payment_model->supplierOutstanding($supid);
                $res3 = $this->Payment_model->supplier_for_payment($supid);
                
                echo json_encode(array($res1,$res2,$res3));
                                   
        }
        public function getGrnByID(){
                $res1=$this->Payment_model->getGrnByIDCash(); //cash payments search by grn ID
                $res2=$this->Payment_model->getGrnByIDCheq();
                $res3=$this->Payment_model->getGrnOutstandingbyID();
                $res4=$this->Payment_model->getCheqAmontForGrn();
                $supid = $this->Payment_model->getSupID();
                $res5 = $this->Payment_model->SupplierOutstanding($supid); //outstanding for that supplier, for all his grns
                $res6 = $this->Payment_model->selectSupplierOutstanding($supid); //outstanding for that supplier, for all his grns
                
                echo json_encode(array($res1,$res2,$res3,$res4,$res5,$supid,$res6));
        }
  

}