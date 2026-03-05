<?php
class InsufficentStockSale extends CI_Controller {
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
               $this->load->model('InsufficentStockSale_model'); 
               $this->load->model('Items_model');
               //$this->load->model('CurQtyWithGrn_model');              
               $this->load->model('CurQtyWithGrn_model');              
        }
        public function showPendingGrn(){
                $response = $this->insufficentStockSale_model->showPendingGrn();
                echo json_encode($response);
        } 
        public function showPendingStockAdjust(){
                $response = $this->insufficentStockSale_model->showPendingStockAdjust();
                echo json_encode($response);
        }
        public function adjustGrnWithinsuffi(){
                $itmid = $this->input->post('itmid');
                $grnqty = $this->input->post('qty');
                $cur_id = $this->input->post('cur_id');
                $itemName =$this->items_model->getItemName($itmid);
                //insuffi_totalQty=sum of insuff for this item
                $insuffi_totalQty =$this->insufficentStockSale_model->getInsuffiSoldQty($itmid);
                if($insuffi_totalQty>0){
                        if($insuffi_totalQty<=$grnqty){                                 

                                //update grnQty=grnQty-insuffi_totalQty
                                $update2=$this->insufficentStockSale_model->updateCurrentGrnqty($itmid,$insuffi_totalQty,$cur_id);
                                while($insuffi_totalQty>0){

                                        $itmDetail=$this->insufficentStockSale_model->getOldestInsuffiSaleIDandQty($itmid);                                       
                                        $saleID=$itmDetail->insuffi_saleid;
                                        $oldQty=$itmDetail->insuffi_newqty;
                                        
                                        ////update all insuffi qty to zero for this item,
                                        ////$update1=$this->insufficentStockSale_model->allInsuffiSoldQtyToZero($itmid);

                                        //update oldst insuffi qty to zero
                                        $update5=$this->insufficentStockSale_model->updateInsuffiSoldQtyToZero($itmid,$oldQty);                                        
                                       
                                        $res = $this->curQtyWithGrn_model->insuffi_AddToGrnSale($itmid,$cur_id,$saleID);
                                        echo json_encode("All Pending grn are adjusted for ".$itemName);

                                        $insuffi_totalQty=$insuffi_totalQty-$oldQty;
                                }
                               
                        }
                        else{//if($grnqty<$insuffi_totalQty) ,update =>  grnqty=0
                                $oldstQty=0;
                                $oldstQty=$this->insufficentStockSale_model->getOldestInsuffiSoldItm($itmid);   
                                if($grnqty<=$oldstQty){ 
                                        //get SaleID of oldest insuffi
                                        $itmDetail=$this->insufficentStockSale_model->getOldestInsuffiSaleIDandQty($itmid);                                       
                                        $saleID=$itmDetail->insuffi_saleid;

                                        //update oldst qty to remaining qty                              
                                        $update3=$this->insufficentStockSale_model->updateInsuffiSoldQtyTo($itmid,$grnqty); 
                                      
                                      //update cur_grn qty to zero 
                                      $update4=$this->insufficentStockSale_model->updateCurrentGrnqty($itmid,$grnqty,$cur_id);

                                      $res2 = $this->curQtyWithGrn_model->insuffi_AddToGrnSale($itmid,$cur_id,$saleID);
                                      echo json_encode("Partial of Pending grn are adjusted for ".$itemName);
                                }                                
                          
                               else{
                                        $gQty=0;
                                        $gQty=$grnqty; 
                                        while($gQty>0){
                                        //get SaleID of oldest insuffi
                                        $itmDetail=$this->insufficentStockSale_model->getOldestInsuffiSaleIDandQty($itmid);                                       
                                        $saleID=$itmDetail->insuffi_saleid;

                                                if($oldstQty<=$gQty){
                                                   //update oldst insuffi qty to zero
                                                   $update6=$this->insufficentStockSale_model->updateInsuffiSoldQtyToZero($itmid,$oldstQty);      
                                                   
                                                }else{
                                                     //update oldst to balance qty
                                                     $update7=$this->insufficentStockSale_model->updateInsuffiSoldQtyTo($itmid,$gQty);   
                                                }                                               
                                                $res1 = $this->curQtyWithGrn_model->insuffi_AddToGrnSale($itmid,$cur_id,$saleID);
                                                $gQty=$gQty-$oldstQty;
                                                $oldstQty=$this->insufficentStockSale_model->getOldestInsuffiSoldItm($itmid);
                                        }  
                                        //update cur_grn qty to zero 
                                        $update8=$this->insufficentStockSale_model->updateCurrentGrnqty($itmid,$grnqty,$cur_id);

                                        echo json_encode("Partial of Pending grn are adjusted for ".$itemName);
                               }



                        }
                }
                else {
                        echo json_encode("No insufficient stock sold for this item");
                }
                
        }
        public function checkIsItInsuffiItem(){
                $response = $this->insufficentStockSale_model->checkIsItInsuffiItem();
                echo json_encode($response);
        }
        public function returnUpdateInInsuffi(){
                $response = $this->insufficentStockSale_model->returnUpdateInInsuffi();
                echo json_encode($response);  
        }

     

}