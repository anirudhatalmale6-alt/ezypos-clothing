<?php
class Production extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
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
            'prod_status' => 'Issued'
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

        // Create a GRN for the finished output
        $grn_data = array(
            'grn_code' => 'PROD-' . $prod->prod_code,
            'grn_supplier_id' => $prod->prod_tailor_id ?: 0,
            'grn_grandtotal' => $prod->prod_total_cost,
            'grn_subtotal' => $prod->prod_total_cost,
            'grn_discount' => 0,
            'grn_less' => 0,
            'grn_createdby' => $this->session->userdata('userid'),
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

        // Add to currentqtywithgrn (for FIFO cost tracking)
        $cur_data = array(
            'cur_grnID' => $grn_id,
            'cur_itmID' => $prod->prod_output_item_id,
            'cur_grnQty' => $prod->prod_output_qty,
            'cur_grnPrice' => $prod->prod_unit_cost,
            'cur_grnTotal' => $prod->prod_total_cost,
            'cur_currentQTY' => $prod->prod_output_qty,
            'cur_status' => 1
        );
        $this->db->insert('ezy_pos_currentqtywithgrn', $cur_data);

        // Increase stock for the finished item
        $exists = $this->db->query("SELECT stock_id FROM ezy_pos_stock WHERE stock_itm_id = ?", array($prod->prod_output_item_id))->num_rows();
        if ($exists > 0) {
            $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty + ? WHERE stock_itm_id = ?", array($prod->prod_output_qty, $prod->prod_output_item_id));
        } else {
            $this->db->insert('ezy_pos_stock', array('stock_itm_id' => $prod->prod_output_item_id, 'stock_qty' => $prod->prod_output_qty, 'stock_status' => 1));
        }

        // Log stock movement
        $stocklog_data = array(
            'stocklog_itmid' => $prod->prod_output_item_id,
            'stocklog_qty' => $prod->prod_output_qty,
            'stocklog_grnID' => $grn_id,
            'stocklog_status' => 1
        );
        $this->db->insert('ezy_pos_stock_log', $stocklog_data);

        // Update production with GRN reference
        $this->Production_model->updateStatus($prod_id, 'Completed');
        $this->db->where('prod_id', $prod_id);
        $this->db->update('ezy_pos_production', array('prod_completedat' => date('Y-m-d H:i:s')));
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
        $price = $this->Production_model->getOldestGrnPrice($item_id);
        echo json_encode($price);
    }
}
