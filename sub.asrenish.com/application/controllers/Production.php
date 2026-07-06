<?php
class Production extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        if ($this->session->userdata('userrole') != 1 && !$this->session->userdata('privProduction')) {
            show_404();
        }
        $this->load->model('Production_model');
        $this->load->model('Stocks_model');
        $this->load->model('Items_model');
        $this->load->model('CurQtyWithGrn_model');
        $this->load->model('Configs_model');
        $this->load->model('Stores_model');
    }

    // Show Add Production form
    public function addProductionGET($page = 'addproduction') {
        if (!file_exists(APPPATH . 'views/transactions/' . $page . '.php')) {
            show_404();
        }
        $data['title'] = 'Add Production';
        $data['config'] = $this->Configs_model->getConfigName();
        $data['rawMaterials'] = $this->Production_model->getRawMaterialItems();
        $data['finishedItems'] = $this->Production_model->getFinishedItems();
        $data['suppliers'] = $this->Production_model->getTailors();
        $data['nextCode'] = $this->Production_model->getNextProductionCode();
        // Load stores based on role
        if ($this->session->userdata('userrole') == 1) {
            $data['storeLoc'] = $this->Stores_model->getAllStores();
        } else {
            $data['storeLoc'] = $this->Stores_model->getAllStoresfornonadmin($_SESSION['userid']);
        }
        $this->load->view('templates/header', $data);
        $this->load->view('transactions/' . $page, $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/footerscripts');
    }

    // Edit existing production
    public function editProductionGET($prod_id) {
        $data['title'] = 'Edit Production';
        $data['config'] = $this->Configs_model->getConfigName();
        $data['rawMaterials'] = $this->Production_model->getRawMaterialItems();
        $data['finishedItems'] = $this->Production_model->getFinishedItems();
        $data['suppliers'] = $this->Production_model->getTailors();
        $data['nextCode'] = '';
        if ($this->session->userdata('userrole') == 1) {
            $data['storeLoc'] = $this->Stores_model->getAllStores();
        } else {
            $data['storeLoc'] = $this->Stores_model->getAllStoresfornonadmin($_SESSION['userid']);
        }
        $data['editProdId'] = intval($prod_id);
        $this->load->view('templates/header', $data);
        $this->load->view('transactions/addproduction', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/footerscripts');
    }

    // Update production header fields
    public function updateProductionHeader() {
        $prod_id = $this->input->post('prod_id');
        $prod = $this->Production_model->getProduction($prod_id);
        if (!$prod || $prod->prod_status == 'Completed' || $prod->prod_status == 'Cancelled') {
            echo json_encode(array('success' => false, 'msg' => 'Cannot edit completed/cancelled production'));
            return;
        }
        $data = array(
            'prod_date' => $this->input->post('prod_date'),
            'prod_output_item_id' => $this->input->post('output_item'),
            'prod_output_qty' => $this->input->post('output_qty'),
            'prod_tailor_id' => $this->input->post('tailor_id') ?: NULL,
            'prod_type' => $this->input->post('prod_type'),
            'prod_notes' => $this->input->post('prod_notes'),
            'prod_store_id' => $this->input->post('store_id') ?: 0,
            'prod_output_store_id' => $this->input->post('output_store_id') ?: 0
        );
        $this->db->where('prod_id', $prod_id);
        $this->db->update('ezy_pos_production', $data);
        $this->Production_model->recalculateCosts($prod_id);
        echo json_encode(array('success' => true));
    }

    // Save production order header
    public function addProductionPOST() {
        $data = array(
            'prod_code' => $this->input->post('prod_code'),
            'prod_date' => $this->input->post('prod_date'),
            'prod_output_item_id' => $this->input->post('output_item'),
            'prod_output_qty' => $this->input->post('output_qty'),
            'prod_tailor_id' => $this->input->post('tailor_id') ?: NULL,
            'prod_type' => $this->input->post('prod_type'),
            'prod_notes' => $this->input->post('prod_notes'),
            'prod_createdby' => $this->session->userdata('userid'),
            'prod_status' => 'Issued',
            'prod_store_id' => $this->input->post('store_id') ?: 0,
            'prod_output_store_id' => $this->input->post('output_store_id') ?: 0
        );
        $prod_id = $this->Production_model->createProduction($data);
        echo json_encode($prod_id);
    }

    // Add material to production
    public function addMaterial() {
        $prod_id = $this->input->post('prod_id');
        $item_id = $this->input->post('item_id');
        $qty = $this->input->post('qty');
        $unit_price = $this->input->post('unit_price');
        $storeid = $this->input->post('storeid');
        if ($storeid == '' || $storeid == null) { $storeid = 0; }
        $total = $qty * $unit_price;

        $data = array(
            'prodmat_prod_id' => $prod_id,
            'prodmat_item_id' => $item_id,
            'prodmat_qty' => $qty,
            'prodmat_unit_price' => $unit_price,
            'prodmat_total' => $total
        );
        $result = $this->Production_model->addMaterial($data);

        // Deduct from ezy_pos_stock (with store filter)
        if ($storeid > 0) {
            $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty - ? WHERE stock_itm_id = ? AND stock_store_id = ?", array($qty, $item_id, $storeid));
        } else {
            $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty - ? WHERE stock_itm_id = ?", array($qty, $item_id));
        }

        // FIFO deduction from ezy_pos_currentqtywithgrn
        $this->_deductFromFifo($item_id, $qty, $storeid);

        // Stock log
        $log_data = array(
            'stocklog_itmid' => $item_id,
            'stocklog_store_id' => $storeid,
            'stocklog_qty' => $qty,
            'stocklog_grnID' => 0,
            'stocklog_saleID' => 0,
            'stocklog_return_sup_retrnID' => 0,
            'stocklog_return_supID' => 0,
            'stocklog_return_cus_retrnID' => 0,
            'stocklog_return_cusID' => 0,
            'stocklog_status' => 1
        );
        $this->db->insert('ezy_pos_stock_log', $log_data);

        // Update production material cost
        $this->Production_model->recalculateCosts($prod_id);

        echo json_encode($result);
    }

    // FIFO deduction helper (same logic as CurQtyWithGrn/ChangeQtyToSale)
    private function _deductFromFifo($item_id, $saleQty, $storeid = 0) {
        $cQty = $this->CurQtyWithGrn_model->getOldStockQty($item_id, $storeid);
        if (!$cQty || $cQty <= 0) return;

        $dif = $cQty - $saleQty;
        while ($cQty < $saleQty) {
            $cur_id = $this->CurQtyWithGrn_model->updateOldestQtyToZero($item_id, $storeid);
            $saleQty -= $cQty;
            $cQty = $this->CurQtyWithGrn_model->getOldStockQty($item_id, $storeid);
            if (!$cQty || $cQty <= 0) return;
            $dif = $cQty - $saleQty;
        }
        if ($cQty >= $saleQty) {
            $this->CurQtyWithGrn_model->updateOldestQtyToValue($dif, $item_id, $storeid);
        }
    }

    // Add cost to production
    public function addCost() {
        $prod_id = $this->input->post('prod_id');
        $data = array(
            'prodcost_prod_id' => $prod_id,
            'prodcost_description' => $this->input->post('description'),
            'prodcost_type' => $this->input->post('cost_type'),
            'prodcost_amount' => $this->input->post('amount')
        );
        $result = $this->Production_model->addCost($data);

        // Recalculate totals
        $this->Production_model->recalculateCosts($prod_id);

        echo json_encode($result);
    }

    // Update production status
    public function updateStatus() {
        $prod_id = $this->input->post('prod_id');
        $status = $this->input->post('status');

        $result = $this->Production_model->updateStatus($prod_id, $status);

        // If completed, create Production GRN for the finished garments
        if ($status == 'Completed') {
            $this->completeProduction($prod_id);
        }

        echo json_encode($result);
    }

    // Complete production - create GRN for finished garments
    private function completeProduction($prod_id) {
        $prod = $this->Production_model->getProduction($prod_id);
        if (!$prod) return false;

        // Determine output store (where GRN goes)
        $output_store = isset($prod->prod_output_store_id) ? $prod->prod_output_store_id : 0;

        // Create a GRN for the finished output
        $grn_data = array(
            'grn_code' => 'PROD-' . $prod->prod_code,
            'grn_supplier_id' => $prod->prod_tailor_id ?: 0,
            'grn_grandtotal' => $prod->prod_total_cost,
            'grn_subtotal' => $prod->prod_total_cost,
            'grn_discount' => 0,
            'grn_less' => 0,
            'grn_createdby' => $this->session->userdata('userid'),
            'grn_location' => $output_store,
            'grn_date' => date('Y-m-d'),
            'grn_status' => 1
        );
        $this->db->insert('ezy_pos_grns', $grn_data);
        $grn_id = $this->db->insert_id();

        // Add GRN item
        $grn_item_data = array(
            'grnitm_grn_id' => $grn_id,
            'grnitm_itemid' => $prod->prod_output_item_id,
            'grnitm_price' => $prod->prod_unit_cost,
            'grnitm_quantity' => $prod->prod_output_qty,
            'grnitm_total' => $prod->prod_total_cost,
            'grnitm_discount' => 0
        );
        $this->db->insert('ezy_pos_grn_item', $grn_item_data);

        // Add to currentqtywithgrn (for FIFO cost tracking) with store
        $cur_data = array(
            'cur_grnID' => $grn_id,
            'cur_itmID' => $prod->prod_output_item_id,
            'cur_grnQty' => $prod->prod_output_qty,
            'cur_grnPrice' => $prod->prod_unit_cost,
            'cur_grnTotal' => $prod->prod_total_cost,
            'cur_currentQTY' => $prod->prod_output_qty,
            'cur_store_id' => $output_store,
            'cur_status' => 1
        );
        $this->db->insert('ezy_pos_currentqtywithgrn', $cur_data);

        // Increase stock for the finished item in the output store
        if ($output_store > 0) {
            $exists = $this->db->query("SELECT stock_id FROM ezy_pos_stock WHERE stock_itm_id = ? AND stock_store_id = ?", array($prod->prod_output_item_id, $output_store))->num_rows();
            if ($exists > 0) {
                $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty + ? WHERE stock_itm_id = ? AND stock_store_id = ?", array($prod->prod_output_qty, $prod->prod_output_item_id, $output_store));
            } else {
                $this->db->insert('ezy_pos_stock', array('stock_itm_id' => $prod->prod_output_item_id, 'stock_qty' => $prod->prod_output_qty, 'stock_store_id' => $output_store, 'stock_status' => 1));
            }
        } else {
            $exists = $this->db->query("SELECT stock_id FROM ezy_pos_stock WHERE stock_itm_id = ?", array($prod->prod_output_item_id))->num_rows();
            if ($exists > 0) {
                $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty + ? WHERE stock_itm_id = ?", array($prod->prod_output_qty, $prod->prod_output_item_id));
            } else {
                $this->db->insert('ezy_pos_stock', array('stock_itm_id' => $prod->prod_output_item_id, 'stock_qty' => $prod->prod_output_qty, 'stock_status' => 1));
            }
        }

        // Log stock movement
        $stocklog_data = array(
            'stocklog_itmid' => $prod->prod_output_item_id,
            'stocklog_store_id' => $output_store,
            'stocklog_qty' => $prod->prod_output_qty,
            'stocklog_grnID' => $grn_id,
            'stocklog_status' => 1
        );
        $this->db->insert('ezy_pos_stock_log', $stocklog_data);

        // Link production to GRN (foreign key relationship)
        $this->db->where('prod_id', $prod_id);
        $this->db->update('ezy_pos_production', array(
            'prod_grn_id' => $grn_id,
            'prod_completedat' => date('Y-m-d H:i:s')
        ));
    }

    // Get production materials
    public function getMaterials() {
        $prod_id = $this->input->post('prod_id');
        $materials = $this->Production_model->getMaterials($prod_id);
        echo json_encode($materials);
    }

    // Get production costs
    public function getCosts() {
        $prod_id = $this->input->post('prod_id');
        $costs = $this->Production_model->getCosts($prod_id);
        echo json_encode($costs);
    }

    // Get production details
    public function getProductionDetails() {
        $prod_id = $this->input->post('prod_id');
        $production = $this->Production_model->getProduction($prod_id);
        echo json_encode($production);
    }

    // List all productions
    public function showAllProductions($page = 'All-Productions') {
        if (!file_exists(APPPATH . 'views/listing/' . $page . '.php')) {
            show_404();
        }
        $data['title'] = 'All Productions';
        $data['config'] = $this->Configs_model->getConfigName();
        $this->load->view('templates/header', $data);
        $this->load->view('listing/' . $page, $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/footerscripts');
    }

    // Load all productions for datatable
    public function loadAllProductions() {
        $productions = $this->Production_model->getAllProductions();
        echo json_encode($productions);
    }

    // Delete material from production
    public function deleteMaterial() {
        $matId = $this->input->post('matId');
        $storeid = $this->input->post('storeid');
        if ($storeid == '' || $storeid == null) { $storeid = 0; }
        $material = $this->Production_model->getMaterialById($matId);
        if ($material) {
            // Restore stock
            if ($storeid > 0) {
                $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty + ? WHERE stock_itm_id = ? AND stock_store_id = ?", array($material->prodmat_qty, $material->prodmat_item_id, $storeid));
            } else {
                $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty + ? WHERE stock_itm_id = ?", array($material->prodmat_qty, $material->prodmat_item_id));
            }
            $this->Production_model->deleteMaterial($matId);
            $this->Production_model->recalculateCosts($material->prodmat_prod_id);
            echo json_encode(array('success' => true, 'item_id' => $material->prodmat_item_id, 'qty' => $material->prodmat_qty));
        } else {
            echo json_encode(false);
        }
    }

    // Delete cost from production
    public function deleteCost() {
        $costId = $this->input->post('costId');
        $cost = $this->Production_model->getCostById($costId);
        if ($cost) {
            $this->Production_model->deleteCost($costId);
            $this->Production_model->recalculateCosts($cost->prodcost_prod_id);
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }

    // Get raw material items for dropdown
    public function getRawMaterials() {
        $items = $this->Production_model->getRawMaterialItems();
        echo json_encode($items);
    }

    // Get stock price for a raw material item (FIFO - oldest GRN price)
    public function getMaterialPrice() {
        $item_id = $this->input->post('item_id');
        $store_id = $this->input->post('store_id') ?: 0;
        $price = $this->Production_model->getOldestGrnPrice($item_id, $store_id);
        echo json_encode($price);
    }

    // Save discount on production
    public function saveDiscount() {
        $prod_id = $this->input->post('prod_id');
        $discount = $this->input->post('discount') ?: 0;
        $discount_type = $this->input->post('discount_type') ?: 'percentage';
        $this->db->where('prod_id', $prod_id);
        $this->db->update('ezy_pos_production', array(
            'prod_discount' => $discount,
            'prod_discount_type' => $discount_type
        ));
        // Recalculate to update total_cost and unit_cost with discount
        $this->Production_model->recalculateCosts($prod_id);
        echo json_encode(true);
    }

    // Get raw materials filtered by store (AJAX)
    public function getRawMaterialsByStore() {
        $store_id = $this->input->post('store_id') ?: 0;
        $items = $this->Production_model->getRawMaterialItemsByStore($store_id);
        echo json_encode($items);
    }

    // ==================== GATE PASS ====================

    // Create gate pass with multiple materials
    public function createGatePass() {
        $this->load->model('GatePass_model');

        $prod_id = intval($this->input->post('prod_id'));
        $store_id = intval($this->input->post('store_id'));
        $notes = $this->input->post('notes') ?: '';
        $items_json = $this->input->post('items');
        $items = json_decode($items_json, true);

        if (!$prod_id || !$store_id || !$items || empty($items)) {
            echo json_encode(array('success' => false, 'msg' => 'Missing required data'));
            return;
        }

        $prod = $this->Production_model->getProduction($prod_id);
        if (!$prod || in_array($prod->prod_status, array('Completed', 'Cancelled'))) {
            echo json_encode(array('success' => false, 'msg' => 'Cannot issue gate pass for completed/cancelled production'));
            return;
        }

        // Verify stock availability before proceeding
        foreach ($items as $item) {
            $item_id = intval($item['item_id']);
            $qty = floatval($item['qty']);
            $sq = $this->db->select('stock_qty')->where('stock_itm_id', $item_id)->where('stock_store_id', $store_id)->get('ezy_pos_stock')->row();
            $available = $sq ? floatval($sq->stock_qty) : 0;
            if ($qty > $available) {
                $iname = $this->db->select('itm_name')->where('itm_id', $item_id)->get('ezy_pos_items')->row();
                echo json_encode(array('success' => false, 'msg' => 'Insufficient stock for ' . ($iname ? $iname->itm_name : 'Item #' . $item_id) . '. Available: ' . $available));
                return;
            }
        }

        $gp_code = $this->GatePass_model->getNextCode();
        $gp_data = array(
            'gp_code' => $gp_code,
            'gp_prod_id' => $prod_id,
            'gp_date' => date('Y-m-d'),
            'gp_store_id' => $store_id,
            'gp_issued_by' => $this->session->userdata('userid'),
            'gp_notes' => $notes,
            'gp_status' => 'Issued',
            'gp_total' => 0
        );

        $gp_id = $this->GatePass_model->createGatePass($gp_data);
        if (!$gp_id) {
            echo json_encode(array('success' => false, 'msg' => 'Failed to create gate pass'));
            return;
        }

        $gp_total = 0;
        $has_gp_col = in_array('prodmat_gp_id', $this->db->list_fields('ezy_pos_production_materials'));

        foreach ($items as $item) {
            $item_id = intval($item['item_id']);
            $qty = floatval($item['qty']);
            $price = floatval($item['price']);
            $uom = isset($item['uom']) ? $item['uom'] : '';
            $item_total = $qty * $price;

            // Gate pass item
            $this->GatePass_model->addItem(array(
                'gpitem_gp_id' => $gp_id,
                'gpitem_item_id' => $item_id,
                'gpitem_qty' => $qty,
                'gpitem_returned_qty' => 0,
                'gpitem_unit_price' => $price,
                'gpitem_total' => $item_total,
                'gpitem_uom' => $uom
            ));

            // Production material (existing cost calculation)
            $mat_data = array(
                'prodmat_prod_id' => $prod_id,
                'prodmat_item_id' => $item_id,
                'prodmat_qty' => $qty,
                'prodmat_unit_price' => $price,
                'prodmat_total' => $item_total
            );
            if ($has_gp_col) {
                $mat_data['prodmat_gp_id'] = $gp_id;
            }
            $this->Production_model->addMaterial($mat_data);

            // Deduct stock
            $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty - ? WHERE stock_itm_id = ? AND stock_store_id = ?", array($qty, $item_id, $store_id));
            $this->_deductFromFifo($item_id, $qty, $store_id);

            // Stock log
            $this->db->insert('ezy_pos_stock_log', array(
                'stocklog_itmid' => $item_id,
                'stocklog_store_id' => $store_id,
                'stocklog_qty' => $qty,
                'stocklog_grnID' => 0,
                'stocklog_saleID' => 0,
                'stocklog_return_sup_retrnID' => 0,
                'stocklog_return_supID' => 0,
                'stocklog_return_cus_retrnID' => 0,
                'stocklog_return_cusID' => 0,
                'stocklog_status' => 1
            ));

            $gp_total += $item_total;
        }

        // Update gate pass total
        $this->db->where('gp_id', $gp_id);
        $this->db->update('ezy_pos_gate_pass', array('gp_total' => $gp_total));

        $this->Production_model->recalculateCosts($prod_id);

        echo json_encode(array('success' => true, 'gp_id' => $gp_id, 'gp_code' => $gp_code));
    }

    // Get gate passes for a production
    public function getGatePasses() {
        $this->load->model('GatePass_model');
        $prod_id = $this->input->post('prod_id');
        $passes = $this->GatePass_model->getGatePassesByProduction($prod_id);
        echo json_encode($passes);
    }

    // Get gate pass items
    public function getGatePassItems() {
        $this->load->model('GatePass_model');
        $gp_id = $this->input->post('gp_id');
        $items = $this->GatePass_model->getItems($gp_id);
        echo json_encode($items);
    }

    // Return material from gate pass
    public function returnGatePassItem() {
        $this->load->model('GatePass_model');

        $gpitem_id = intval($this->input->post('gpitem_id'));
        $return_qty = floatval($this->input->post('return_qty'));
        $gp_id = intval($this->input->post('gp_id'));

        if (!$gpitem_id || $return_qty <= 0) {
            echo json_encode(array('success' => false, 'msg' => 'Invalid return data'));
            return;
        }

        $gpitem = $this->GatePass_model->getItemById($gpitem_id);
        if (!$gpitem) {
            echo json_encode(array('success' => false, 'msg' => 'Gate pass item not found'));
            return;
        }

        $gp = $this->GatePass_model->getGatePass($gp_id);
        if (!$gp) {
            echo json_encode(array('success' => false, 'msg' => 'Gate pass not found'));
            return;
        }

        // Check production not completed
        $prod = $this->Production_model->getProduction($gp->gp_prod_id);
        if ($prod && in_array($prod->prod_status, array('Completed', 'Cancelled'))) {
            echo json_encode(array('success' => false, 'msg' => 'Cannot return materials for completed/cancelled production'));
            return;
        }

        $max_returnable = floatval($gpitem->gpitem_qty) - floatval($gpitem->gpitem_returned_qty);
        if ($return_qty > $max_returnable) {
            echo json_encode(array('success' => false, 'msg' => 'Return qty exceeds maximum returnable (' . $max_returnable . ')'));
            return;
        }

        // Update gate pass item
        $this->GatePass_model->returnItem($gpitem_id, $return_qty);
        $this->GatePass_model->recalculateTotal($gp_id);

        // Restore stock to the gate pass store
        $store_id = intval($gp->gp_store_id);
        $item_id = intval($gpitem->gpitem_item_id);
        $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty + ? WHERE stock_itm_id = ? AND stock_store_id = ?", array($return_qty, $item_id, $store_id));

        // Stock log for return
        $this->db->insert('ezy_pos_stock_log', array(
            'stocklog_itmid' => $item_id,
            'stocklog_store_id' => $store_id,
            'stocklog_qty' => $return_qty,
            'stocklog_grnID' => 0,
            'stocklog_saleID' => 0,
            'stocklog_return_sup_retrnID' => 0,
            'stocklog_return_supID' => 0,
            'stocklog_return_cus_retrnID' => 0,
            'stocklog_return_cusID' => 0,
            'stocklog_status' => 1
        ));

        // Reduce production material qty (find matching material row)
        $has_gp_col = in_array('prodmat_gp_id', $this->db->list_fields('ezy_pos_production_materials'));
        if ($has_gp_col) {
            $mat = $this->db->select('prodmat_id, prodmat_qty, prodmat_unit_price')
                ->where('prodmat_prod_id', $gp->gp_prod_id)
                ->where('prodmat_item_id', $item_id)
                ->where('prodmat_gp_id', $gp_id)
                ->order_by('prodmat_id', 'DESC')
                ->get('ezy_pos_production_materials')->row();
        } else {
            $mat = $this->db->select('prodmat_id, prodmat_qty, prodmat_unit_price')
                ->where('prodmat_prod_id', $gp->gp_prod_id)
                ->where('prodmat_item_id', $item_id)
                ->order_by('prodmat_id', 'DESC')
                ->get('ezy_pos_production_materials')->row();
        }

        if ($mat) {
            $new_qty = floatval($mat->prodmat_qty) - $return_qty;
            if ($new_qty <= 0) {
                $this->db->delete('ezy_pos_production_materials', array('prodmat_id' => $mat->prodmat_id));
            } else {
                $this->db->where('prodmat_id', $mat->prodmat_id);
                $this->db->update('ezy_pos_production_materials', array(
                    'prodmat_qty' => $new_qty,
                    'prodmat_total' => $new_qty * floatval($mat->prodmat_unit_price)
                ));
            }
        }

        $this->Production_model->recalculateCosts($gp->gp_prod_id);

        echo json_encode(array('success' => true));
    }

    // Print gate pass
    public function printGatePass($gp_id = 0) {
        $this->load->model('GatePass_model');
        if ($gp_id == 0) $gp_id = $this->uri->segment(3);

        $data['gatepass'] = $this->GatePass_model->getGatePass($gp_id);
        $data['items'] = $this->GatePass_model->getItems($gp_id);
        $data['config'] = $this->Configs_model->getConfigName();

        $prod = $this->Production_model->getProduction($data['gatepass']->gp_prod_id);
        $data['production'] = $prod;

        $this->load->view('transactions/gate_pass_print', $data);
    }
}
