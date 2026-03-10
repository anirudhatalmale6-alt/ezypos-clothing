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
                $cur_id = $this->CurQtyWithGrn_model->addGRNItems();
                echo json_encode($cur_id);
        }
        
        public function ChangeQtyToSale(){
            $saleID = $this->input->post('saleID');
            $itmID = $this->input->post('itmid');
            $saleQty = $this->input->post('qty');
            $storeid = $this->input->post('storeid');
            if($storeid == '' || $storeid == null){ $storeid = 0; }
            $stockQty = $this->Stocks_model->getStockItmQty();

            if ($stockQty >= $saleQty) {
                $cQty = $this->CurQtyWithGrn_model->getOldStockQty($itmID, $storeid);

                if ($cQty > 0) {
                    $dif = $cQty - $saleQty;
                    while ($cQty < $saleQty) {
                        $cur_id_of_updated_row = $this->CurQtyWithGrn_model->updateOldestQtyToZero($itmID, $storeid);
                        $saleQty -= $cQty;
                        $res = $this->CurQtyWithGrn_model->addToGrnSale($cur_id_of_updated_row, $cQty);
                        $cQty = $this->CurQtyWithGrn_model->getOldStockQty($itmID, $storeid);
                        $dif = $cQty - $saleQty;
                    }
                    if ($cQty >= $saleQty) {
                        $cur_id_of_updated_row = $this->CurQtyWithGrn_model->updateOldestQtyToValue($dif, $itmID, $storeid);
                        $res = $this->CurQtyWithGrn_model->addToGrnSale($cur_id_of_updated_row, $saleQty);
                        echo json_encode($res);
                    }
                } else {
                    echo json_encode("No items in stock");
                }
            } else if ($stockQty < $saleQty) {
                if ($stockQty > 0) {
                    $cQty = $this->CurQtyWithGrn_model->getOldStockQty($itmID, $storeid);

                    while ($cQty > 0) {
                        $cur_id_of_updated_row = $this->CurQtyWithGrn_model->updateOldestQtyToZero($itmID, $storeid);
                        $res1 = $this->CurQtyWithGrn_model->addToGrnSale($cur_id_of_updated_row, $cQty);
                        $cQty = $this->CurQtyWithGrn_model->getOldStockQty($itmID, $storeid);
                    }
                    $QtyDif = $saleQty - $stockQty;
                    $res = $this->InsufficentStockSale_model->insertInsuffiSaleItem($itmID, $QtyDif, $saleID);
                    echo json_encode("partial qty Sold by external support stock");
                } else {
                    $qtyDif = $saleQty;
                    $res = $this->InsufficentStockSale_model->insertInsuffiSaleItem($itmID, $qtyDif, $saleID);
                    echo json_encode("fully qty Sold by external support stock");
                }
            } else {
                echo json_encode(false);
            }
}

        
        public function ChangeQtyToCusReturn(){
                $saleID=$this->input->post('saleID');
                $itmID=$this->input->post('itmid');
                $rQty=$this->input->post('qty'); //1
                
                $res=$this->CurQtyWithGrn_model->saleOccurredGrn($itmID,$saleID);
                $grnQty=$res->cur_grnQty; //5
                $curQty=$res->cur_currentQTY; //2
                $dif=$grnQty-$curQty; //3                              
                
                while($rQty>$dif){     //if return will occure in more than 1 grn
                        $cur_ID=$this->CurQtyWithGrn_model->getMatchingCur_Id($saleID,$itmID);
                        $curID=$cur_ID->grnvssale_curQtyWithGrnID;
                        $update1=$this->CurQtyWithGrn_model->updateSaleOccurredGrn2($dif,$curID); //update to full capacity
                        $rQty=$rQty-$dif;
                        $res=$this->CurQtyWithGrn_model->saleOccurredGrn($itmID,$saleID);
                        $grnQty=$res->cur_grnQty; 
                        $curQty=$res->cur_currentQTY; 
                        $dif=$grnQty-$curQty; 
                }
                if($rQty<=$dif){ //return will occur in only one grn                       
                        $cur_ID=$this->CurQtyWithGrn_model->getMatchingCur_Id($saleID,$itmID);
                        $curID=$cur_ID->grnvssale_curQtyWithGrnID;                        
                        $update2=$this->CurQtyWithGrn_model->updateSaleOccurredGrn2($rQty,$curID); //update +with rQty
                        echo json_encode($update2);
                }     
        }
        public function ChangeQtyToSupReturn(){
                $grnID=$this->input->post('grnID');
                $itmID=$this->input->post('itmid');
                $rQty=$this->input->post('qty');

                $response=$this->CurQtyWithGrn_model->updateLatesGrnItmQty($grnID,$itmID,$rQty);
                echo json_encode($response);
        }


        // new codes

        public function getStores() {
                $this->load->model('CurQtyWithGrn_model');
                $stores = $this->CurQtyWithGrn_model->fetchStores();
                echo json_encode($stores);
            }


            
            public function saveStoreItems() {
                $grnID = $this->input->post('grn_id'); // Get GRN ID
                $storeItems = $this->input->post('store_items'); // Get Store-Item list (Array)
        
                if (empty($grnID) || empty($storeItems)) {
                    $response = array('status' => 'error', 'message' => 'Missing data');
                    echo json_encode($response);
                    return;
                }
        
                // Loop through each item and save to database
                foreach ($storeItems as $item) {
                    $data = array(
                        'storeitem_storeid' => $item['store_id'],
                        'storeitem_itemid' => $item['item_id'],
                        'storeitem_grnid' => $grnID
                    );
        
                    $this->CurQtyWithGrn_model->insertStoreItem($data);
                }
        
                $response = array('status' => 'success', 'message' => 'Store items saved successfully');
                echo json_encode($response);
            }
            
            public function ReturnGRNsale(){
                $cur_id = $this->CurQtyWithGrn_model->ReturnGRNsale();
                echo json_encode($cur_id);
            }
}