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
        
        /**
         * A sale takes its quantity out of the GRN batches, oldest first, and
         * whatever the shop does not have is recorded as pending GRN.
         *
         * "What the shop has" is the stock of ALL stores together - one pool,
         * as it was before stock was split per branch in March. Since then this
         * looked only at the selling branch, so goods received into the main
         * store or the warehouse but not yet transferred made every branch sale
         * go fully pending, while the stock sat there unused (5 in stock, sell
         * 10, pending showed 10 instead of 5). Each branch's own stock figure
         * is still kept per branch; only the pending calculation is shop-wide.
         *
         * The loop is bounded: it used to spin for ever when the stock figure
         * said there was enough but the batches had run out.
         */
        public function ChangeQtyToSale(){
            $saleID  = $this->input->post('saleID');
            $itmID   = $this->input->post('itmid');
            $saleQty = floatval($this->input->post('qty'));

            $row = $this->db->select_sum('stock_qty')->where('stock_itm_id', $itmID)->get('ezy_pos_stock')->row();
            $stockQty = ($row && $row->stock_qty) ? floatval($row->stock_qty) : 0;

            // What can come out of stock, and what is short.
            $fromStock = max(0, min($saleQty, $stockQty));
            $short     = round($saleQty - $fromStock, 2);

            $take = $fromStock;
            $guard = 0;
            while ($take > 0.0001 && $guard++ < 1000) {
                $cQty = $this->CurQtyWithGrn_model->getOldStockQty($itmID, 0);
                if ($cQty === false || floatval($cQty) <= 0) { break; }   // batches ran out before the stock figure did
                $cQty = floatval($cQty);
                if ($cQty <= $take) {
                    $curId = $this->CurQtyWithGrn_model->updateOldestQtyToZero($itmID, 0);
                    $used = $cQty;
                } else {
                    $curId = $this->CurQtyWithGrn_model->updateOldestQtyToValue($cQty - $take, $itmID, 0);
                    $used = $take;
                }
                if ( ! $curId) { break; }
                $this->CurQtyWithGrn_model->addToGrnSale($curId, $used);
                $take -= $used;
            }

            if ($short > 0) {
                $this->InsufficentStockSale_model->insertInsuffiSaleItem($itmID, $short, $saleID);
                echo json_encode($fromStock > 0 ? "partial qty Sold by external support stock" : "fully qty Sold by external support stock");
            } else {
                echo json_encode(true);
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