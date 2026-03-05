<?php
class CurQtyWithGrn extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                if ( ! $this->session->userdata('username'))
                { 
                    redirect('login');
                }
               // else if(!$this->session->userdata('privprofit')==1){
               //         show_404();
               // }
                $this->load->model('CurQtyWithGrn_model');
                $this->load->model('Configs_model');
                $this->load->model('Stocks_model');
                $this->load->model('InsufficentStockSale_model');
        }

        public function addGRNItems(){
                $cur_id = $this->curQtyWithGrn_model->addGRNItems();
                echo json_encode($cur_id);
        }
        
        public function ChangeQtyToSale(){
                $saleID=$this->input->post('saleID');
                $itmID=$this->input->post('itmid');
                $saleQty=null;                                                  //5 ,           2
                $saleQty=$this->input->post('qty'); 
                //$cQty=null;                                                                     //3,            4         
                $stockQty=$this->stocks_model->getStockItmQty();
                //above edited
                
                if($stockQty>=$saleQty){
                        $cQty=null;
                        $cQty=$this->curQtyWithGrn_model->getOldStockQty($itmID);      //3
                        if($cQty>0){
                                $dif=0;
                                $dif=$cQty-$saleQty;                                     //3-5           4-2
                                //echo "1stsaleQty ".$saleQty." ";                        
                                while($cQty<$saleQty)                                        //-2
                                {       //cur_id_of_updated_row = is also grnid & index id of  curQtyWithGrn table                     
                                        $cur_id_of_updated_row = $this->curQtyWithGrn_model->updateOldestQtyToZero($itmID);
                                        $saleQty=$saleQty-$cQty;                         //5-3
                                        //echo " saleQty ".$saleQty." ";                  //2
                                        $cQty = $this->curQtyWithGrn_model->getOldStockQty($itmID); 
                                        //echo " qty ".$cQty." ";                          //4
                                        $dif=$cQty-$saleQty;
                                        $res = $this->curQtyWithGrn_model->addToGrnSale($cur_id_of_updated_row);
                                }
                                if($cQty>=$saleQty){
                                        $cur_id_of_updated_row = $this->curQtyWithGrn_model->updateOldestQtyToValue($dif,$itmID);
                                        $res = $this->curQtyWithGrn_model->addToGrnSale($cur_id_of_updated_row);
                                        echo json_encode($res);
                                }
                        }
                        else{
                                echo json_encode("No items in stock");     
                        }        
                }
                else if($stockQty<$saleQty){ //stock 50 , saleqty 60       
                        if($stockQty>0){//make available all grn_stock to zero
                                $cQty=null;
                                $cQty=$this->curQtyWithGrn_model->getOldStockQty($itmID);
                                
                                while($cQty>0){
                                        $cur_id_of_updated_row = $this->curQtyWithGrn_model->updateOldestQtyToZero($itmID);
                                        $res1 = $this->curQtyWithGrn_model->addToGrnSale($cur_id_of_updated_row);
                                        $cQty=$this->curQtyWithGrn_model->getOldStockQty($itmID);
                                        
                                        if($cQty==false){
                                                echo json_encode("__");                                               
                                        }
                                        echo json_encode("There was few stock");
                                }   
                                $QtyDif=$saleQty-$stockQty;
                                $res= $this->insufficentStockSale_model->insertInsuffiSaleItem($itmID,$QtyDif,$saleID); 
                                echo json_encode("partial qty Sold by external support stock");
                        }
                        else{
                        $qtyDif=$saleQty;
                        $res= $this->insufficentStockSale_model->insertInsuffiSaleItem($itmID,$qtyDif,$saleID); 
                       
                        echo json_encode("fully qty Sold by external support stock");
                        }     
                }
                else{
                        echo json_encode(false);
                }
               // $needQty=$saleQty-$stockQty;
               // $str="Current stock is ".$stockQty.",  Need more ".$needQty."units to Proceed the sale";
               // echo json_encode($str);
                 
                
        }
        
        public function ChangeQtyToCusReturn(){
                $saleID=$this->input->post('saleID');
                $itmID=$this->input->post('itmid');
                $rQty=$this->input->post('qty'); //1
                
                $res=$this->curQtyWithGrn_model->saleOccurredGrn($itmID,$saleID);
                $grnQty=$res->cur_grnQty; //5
                $curQty=$res->cur_currentQTY; //2
                $dif=$grnQty-$curQty; //3                              
                
                while($rQty>$dif){     //if return will occure in more than 1 grn
                        $cur_ID=$this->curQtyWithGrn_model->getMatchingCur_Id($saleID,$itmID);
                        $curID=$cur_ID->grnvssale_curQtyWithGrnID;
                        $update1=$this->curQtyWithGrn_model->updateSaleOccurredGrn2($dif,$curID); //update to full capacity
                        $rQty=$rQty-$dif;
                        $res=$this->curQtyWithGrn_model->saleOccurredGrn($itmID,$saleID);
                        $grnQty=$res->cur_grnQty; 
                        $curQty=$res->cur_currentQTY; 
                        $dif=$grnQty-$curQty; 
                }
                if($rQty<=$dif){ //return will occur in only one grn                       
                        $cur_ID=$this->curQtyWithGrn_model->getMatchingCur_Id($saleID,$itmID);
                        $curID=$cur_ID->grnvssale_curQtyWithGrnID;                        
                        $update2=$this->curQtyWithGrn_model->updateSaleOccurredGrn2($rQty,$curID); //update +with rQty
                        echo json_encode($update2);
                }     
        }
        public function ChangeQtyToSupReturn(){
                $grnID=$this->input->post('grnID');
                $itmID=$this->input->post('itmid');
                $rQty=$this->input->post('qty');

                $response=$this->curQtyWithGrn_model->updateLatesGrnItmQty($grnID,$itmID,$rQty);
                echo json_encode($response);
        }

}