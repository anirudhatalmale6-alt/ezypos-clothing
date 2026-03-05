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
                $response = $this->InsufficentStockSale_model->showPendingGrn();
                echo json_encode($response);
        } 
        public function showPendingStockAdjust(){
                $response = $this->InsufficentStockSale_model->showPendingStockAdjust();
                echo json_encode($response);
        }
        public function adjustGrnWithinsuffi(){
                $itmid = $this->input->post('itmid');
                $grnqty = $this->input->post('qty');
                $cur_id = $this->input->post('cur_id');
                $itemName =$this->Items_model->getItemName($itmid);
                //insuffi_totalQty=sum of insuff for this item
                $insuffi_totalQty =$this->InsufficentStockSale_model->getInsuffiSoldQty($itmid);
                
                if($insuffi_totalQty>0){
                        if($insuffi_totalQty<=$grnqty){                                 

                                //update grnQty=grnQty-insuffi_totalQty
                                $update2=$this->InsufficentStockSale_model->updateCurrentGrnqty($itmid,$insuffi_totalQty,$cur_id);
                                
                                if ($update2) {
                                
                                    while($insuffi_totalQty>0){

                                        $itmDetail=$this->InsufficentStockSale_model->getOldestInsuffiSaleIDandQty($itmid);                                       
                                        $saleID=$itmDetail->insuffi_saleid;
                                        $oldQty=$itmDetail->insuffi_newqty;
                                        
                                       

                                        //update oldst insuffi qty to zero
                                        $update5=$this->InsufficentStockSale_model->updateInsuffiSoldQtyToZero($itmid,$oldQty);                                        
                                        $deductQty = $oldQty;
                                        
                                        if ($update5){
                                            $res = $this->CurQtyWithGrn_model->insuffi_AddToGrnSale($itmid,$cur_id,$saleID, $deductQty);
                                            if ($res){
                                                echo json_encode("All Pending grn are adjusted for ".$itemName);
                                                $insuffi_totalQty = $insuffi_totalQty-$oldQty;
                                            }
                                             else{
                                                    // If there’s an error, return an error message
                                                    echo json_encode(['status' => 'error', 'message' => 'Error in $res = $this->CurQtyWithGrn_model->insuffi_AddToGrnSale($itmid,$cur_id,$saleID, $deductQty);']);
                                                }
                                        }
                                        else{
                                            // If there’s an error, return an error message
                                        echo json_encode(['status' => 'error', 'message' => 'Error in updating updateInsuffiSoldQtyToZero ($update5)']);
                                        }

                                        
                                    }
                                }
                                else{
                                    // If there’s an error, return an error message
                                    echo json_encode(['status' => 'error', 'message' => 'Error in updating updateCurrentGrnqty ($update2)']);
                                }
                               
                        }
                        else{//if($grnqty<$insuffi_totalQty) ,update =>  grnqty=0
                                $oldstQty=0;
                                $oldstQty=$this->InsufficentStockSale_model->getOldestInsuffiSoldItm($itmid);//all active pending grn quantity with item id   
                                if($grnqty<=$oldstQty){ 
                                        //get SaleID of oldest insuffi
                                        $itmDetail=$this->InsufficentStockSale_model->getOldestInsuffiSaleIDandQty($itmid);// taking sale id of pending grn                                       
                                        $saleID=$itmDetail->insuffi_saleid;

                                        //update oldst qty to remaining qty                              
                                        $deductQty = $this->InsufficentStockSale_model->updateInsuffiSoldQtyTo($itmid, $grnqty); 
                                      
                                      //update cur_grn qty to zero 
                                      $update4=$this->InsufficentStockSale_model->updateCurrentGrnqty($itmid,$grnqty,$cur_id);

                                    //   $res2 = $this->CurQtyWithGrn_model->insuffi_AddToGrnSale($itmid, $cur_id, $saleID, $deductQty);
                                    
                                    $deductQty = $grnqty;
                                    
                                    $insertResult = $this->CurQtyWithGrn_model->insuffi_AddToGrnSale($itmid, $cur_id, $saleID, $deductQty);

                                    if ($insertResult) {
                                        echo "<script>console.log('[insuffi_AddToGrnSale] Worked: GRN to Sale Linked for ItemID: {$itmid}, Quantity: {$deductQty}');</script>";
                                    } else {
                                        echo "<script>console.log('[insuffi_AddToGrnSale] Failed: Could not link GRN to Sale for ItemID: {$itmid}');</script>";
                                    }

                                      echo json_encode("Partial of Pending grn are adjusted for ".$itemName);
                                }                                
                          
                               else{
                                        $gQty=0;
                                        $gQty=$grnqty; 
                                        while($gQty>0){
                                        //get SaleID of oldest insuffi
                                        $itmDetail=$this->InsufficentStockSale_model->getOldestInsuffiSaleIDandQty($itmid);                                       
                                        $saleID=$itmDetail->insuffi_saleid;
                                        $remainingBeforeUpdate = $itmDetail->insuffi_newqty;

                                                if($oldstQty<=$gQty){
                                                   //update oldst insuffi qty to zero
                                                   $update6=$this->InsufficentStockSale_model->updateInsuffiSoldQtyToZero($itmid,$oldstQty);      
                                                
                                        $deductQty = $remainingBeforeUpdate;         
                                                   
                                                }
                                                else{
                                                     //update oldst to balance qty
                                                     $update7=$this->InsufficentStockSale_model->updateInsuffiSoldQtyTo($itmid,$gQty);   
                                                     $deductQty = $gQty;
                                                }                                        
                                            
                                                
                                                $res1 = $this->CurQtyWithGrn_model->insuffi_AddToGrnSale($itmid, $cur_id, $saleID, $deductQty);
                                                
                                                
                                                $gQty=$gQty-$oldstQty;
                                                $oldstQty=$this->InsufficentStockSale_model->getOldestInsuffiSoldItm($itmid);
                                        }  
                                        
                                        
                                        
                                        
                                        
                                        
                                        //update cur_grn qty to zero 
                                        $update8=$this->InsufficentStockSale_model->updateCurrentGrnqty($itmid,$grnqty,$cur_id);

                                        echo json_encode("Partial of Pending grn are adjusted for ".$itemName);
                               }



                        }
                }
                else {
                        echo json_encode("No insufficient stock sold for this item");
                }
                
        }
        public function checkIsItInsuffiItem(){
                $response = $this->InsufficentStockSale_model->checkIsItInsuffiItem();
                echo json_encode($response);
        }
        public function returnUpdateInInsuffi(){
                $response = $this->InsufficentStockSale_model->returnUpdateInInsuffi();
                echo json_encode($response);  
        }
        
        
        
        public function processReturnToGrn()
        {
            $itmID     = $this->input->post('itmID');
            $saleID    = $this->input->post('saleID');
            $returnQty = $this->input->post('returnQty');
        
            $this->db->select('grnvssale_id, grn_quantity');
            $this->db->from('ezy_pos_cur_grn_vs_sale');
            $this->db->where('grnvssale_itmID', $itmID);
            $this->db->where('grnvssale_saleID', $saleID);
            $this->db->where('grn_quantity >', 0);
            $this->db->order_by('grnvssale_id', 'ASC');
            $query = $this->db->get();
        
            foreach ($query->result() as $row) {
                if ($returnQty <= 0) break;
        
                $deductFromThisGRN = min($row->grn_quantity, $returnQty);
        
                $this->db->set('grn_quantity', 'grn_quantity - ' . $deductFromThisGRN, false);
                $this->db->where('grnvssale_id', $row->grnvssale_id);
                $this->db->update('ezy_pos_cur_grn_vs_sale');
        
                $returnQty -= $deductFromThisGRN;
            }
        
            echo json_encode(['status' => true]);
        }



     

}