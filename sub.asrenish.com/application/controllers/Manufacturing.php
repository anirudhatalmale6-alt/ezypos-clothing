<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Manufacturing / Production (Item 4 redesign)
 *
 * Replaces the old Production module UI. Workflow:
 *   Gate Pass popup -> Production header (warehouse, gate pass, prod id)
 *   -> Fabric raw material + qty -> output items -> production bill
 *   -> overall charges -> dispatch (raw leaves warehouse)
 *   -> receive outputs (finished goods GRN) -> complete (raw return/damage).
 */
class Manufacturing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        // Same permission gate as the old Production module
        if ($this->session->userdata('userrole') != 1 && !$this->session->userdata('privProduction')) {
            show_404();
        }
        $this->load->model('Mfg_model');
        $this->load->model('Configs_model');
        $this->load->model('Stores_model');
        $this->load->model('CurQtyWithGrn_model');
    }

    private function _uid() { return $this->session->userdata('userid'); }

    /* ============================ pages ============================ */

    public function index() {
        $data1['title']  = 'Production';
        $data1['config'] = $this->Configs_model->getConfigName();
        $data = array(
            'ready'         => $this->Mfg_model->ready(),
            'warehouses'    => $this->Mfg_model->getWarehouses(),
            'fabrics'       => $this->Mfg_model->getFabricItems(),
            'finishedItems' => $this->Mfg_model->getFinishedItems(),
            'nextGp'        => $this->Mfg_model->ready() ? $this->Mfg_model->getNextGpCode() : 'GP-00001',
            'nextProd'      => $this->Mfg_model->ready() ? $this->Mfg_model->getNextProdCode() : 'PRD-00001'
        );
        $this->load->view('templates/header', $data1);
        $this->load->view('transactions/manufacturing', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    public function listAll() {
        $data1['title']  = 'All Productions';
        $data1['config'] = $this->Configs_model->getConfigName();
        $data = array('productions' => $this->Mfg_model->ready() ? $this->Mfg_model->listAll() : array());
        $this->load->view('templates/header', $data1);
        $this->load->view('transactions/manufacturing_list', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    /* ============================ gate pass ============================ */

    public function searchGatePasses() {
        $term = $this->input->get('term');
        if ($term === null) $term = $this->input->post('term');
        $rows = $this->Mfg_model->searchGatePasses($term ? $term : '');
        echo json_encode($rows);
    }

    public function createGatePass() {
        $store_id = intval($this->input->post('store_id'));
        if ($store_id <= 0) { echo json_encode(array('success' => false, 'msg' => 'Select a warehouse')); return; }
        $code = $this->Mfg_model->getNextGpCode();
        $gp_id = $this->Mfg_model->createGatePass(array(
            'gp_code'      => $code,
            'gp_store_id'  => $store_id,
            'gp_status'    => 'Draft',
            'gp_notes'     => $this->input->post('notes'),
            'gp_prepared_by' => $this->session->userdata('useruser'),
            'gp_createdby' => $this->_uid()
        ));
        echo json_encode(array('success' => true, 'gp_id' => $gp_id, 'gp_code' => $code, 'store_id' => $store_id));
    }

    public function getGatePass($gp_id = 0) {
        $gp = $this->Mfg_model->getGatePass($gp_id);
        if (!$gp) { echo json_encode(array('success' => false)); return; }
        $prods = $this->Mfg_model->getProductionsByGp($gp_id);
        echo json_encode(array('success' => true, 'gatepass' => $gp, 'productions' => $prods,
            'nextProd' => $this->Mfg_model->getNextProdCode()));
    }

    // Latest GRN cost + UOM + warehouse stock for a chosen raw material
    public function fabricInfo() {
        $item_id  = intval($this->input->post('item_id'));
        $store_id = intval($this->input->post('store_id'));
        $it = $this->Mfg_model->getItem($item_id);
        echo json_encode(array(
            'uom'      => $it ? $it->itm_uom : '',
            'grn_cost' => $this->Mfg_model->getLatestGrnPrice($item_id, $store_id),
            'stock'    => $this->Mfg_model->getStockQty($item_id, $store_id)
        ));
    }

    /* ============================ production save ============================ */

    /**
     * Create or update a production (raw material + outputs + charges).
     * Allowed only while the gate pass is still editable (Draft / Ready for Dispatch).
     */
    public function saveProduction() {
        $p_id     = intval($this->input->post('p_id'));
        $gp_id    = intval($this->input->post('gp_id'));
        $store_id = intval($this->input->post('store_id'));
        $raw_item = intval($this->input->post('raw_item_id'));
        $raw_qty  = floatval($this->input->post('raw_qty'));
        $raw_uom  = $this->input->post('raw_uom');
        $grn_cost = floatval($this->input->post('raw_grn_cost'));
        $notes    = $this->input->post('notes');
        $outputs  = json_decode($this->input->post('outputs'), true);
        $charges  = json_decode($this->input->post('charges'), true);
        if (!is_array($outputs)) $outputs = array();
        if (!is_array($charges)) $charges = array();

        $gp = $this->Mfg_model->getGatePass($gp_id);
        if (!$gp) { echo json_encode(array('success' => false, 'msg' => 'Gate pass not found')); return; }
        if (!in_array($gp->gp_status, array('Draft', 'Ready for Dispatch'))) {
            echo json_encode(array('success' => false, 'msg' => 'This gate pass is already dispatched and can no longer be edited.'));
            return;
        }
        if ($raw_item <= 0 || $raw_qty <= 0) {
            echo json_encode(array('success' => false, 'msg' => 'Select a fabric raw material and quantity')); return;
        }

        // Validate: total material used cannot exceed raw quantity
        $usedTotal = 0;
        foreach ($outputs as $o) $usedTotal += floatval($o['material_used']);
        if ($usedTotal > $raw_qty + 0.0001) {
            echo json_encode(array('success' => false, 'msg' => 'Material used across outputs ('.$usedTotal.') exceeds the raw material quantity ('.$raw_qty.').'));
            return;
        }

        $header = array(
            'p_gp_id'       => $gp_id,
            'p_store_id'    => $store_id > 0 ? $store_id : $gp->gp_store_id,
            'p_raw_item_id' => $raw_item,
            'p_raw_qty'     => $raw_qty,
            'p_raw_uom'     => $raw_uom,
            'p_raw_grn_cost'=> $grn_cost,
            'p_notes'       => $notes
        );

        if ($p_id > 0) {
            $existing = $this->Mfg_model->getProduction($p_id);
            if (!$existing) { echo json_encode(array('success' => false, 'msg' => 'Production not found')); return; }
            $this->Mfg_model->updateProduction($p_id, $header);
        } else {
            $header['p_code']      = $this->Mfg_model->getNextProdCode();
            $header['p_status']    = 'Draft';
            $header['p_createdby'] = $this->_uid();
            $p_id = $this->Mfg_model->createProduction($header);
        }

        // Replace outputs & charges
        $this->Mfg_model->deleteOutputs($p_id);
        foreach ($outputs as $o) {
            if (intval($o['item_id']) <= 0) continue;
            $this->Mfg_model->addOutput(array(
                'o_p_id'          => $p_id,
                'o_item_id'       => intval($o['item_id']),
                'o_material_used' => floatval($o['material_used']),
                'o_qty_produced'  => floatval($o['qty_produced']),
                'o_additional'    => floatval($o['additional']),
                'o_tailoring'     => floatval($o['tailoring'])
            ));
        }
        $this->Mfg_model->deleteCharges($p_id);
        foreach ($charges as $c) {
            if (floatval($c['amount']) == 0 && trim($c['description']) === '') continue;
            $this->Mfg_model->addCharge(array(
                'c_p_id'        => $p_id,
                'c_description'  => $c['description'],
                'c_amount'      => floatval($c['amount'])
            ));
        }

        $this->Mfg_model->recalc($p_id);
        $prod = $this->Mfg_model->getProduction($p_id);
        echo json_encode(array('success' => true, 'p_id' => $p_id, 'p_code' => $prod->p_code, 'production' => $prod));
    }

    public function getProduction($p_id = 0) {
        $prod = $this->Mfg_model->getProduction($p_id);
        if (!$prod) { echo json_encode(array('success' => false)); return; }
        echo json_encode(array(
            'success'   => true,
            'production'=> $prod,
            'outputs'   => $this->Mfg_model->getOutputs($p_id),
            'charges'   => $this->Mfg_model->getCharges($p_id),
            'receiving' => $this->Mfg_model->getReceiving($p_id)
        ));
    }

    /* ============================ dispatch ============================ */

    /**
     * Dispatch a gate pass: raw materials physically leave the warehouse.
     * Deduct each production's raw quantity (FIFO cost layers + store stock),
     * lock the gate pass and its productions for editing.
     */
    public function dispatchGatePass() {
        $gp_id = intval($this->input->post('gp_id'));
        $gp = $this->Mfg_model->getGatePass($gp_id);
        if (!$gp) { echo json_encode(array('success' => false, 'msg' => 'Gate pass not found')); return; }
        if ($gp->gp_status === 'Dispatched' || $gp->gp_status === 'In Production' || $gp->gp_status === 'Completed') {
            echo json_encode(array('success' => false, 'msg' => 'Gate pass already dispatched.')); return;
        }
        $prods = $this->Mfg_model->getProductionsByGp($gp_id);
        if (empty($prods)) { echo json_encode(array('success' => false, 'msg' => 'Add at least one production before dispatch.')); return; }

        foreach ($prods as $p) {
            $store = $p->p_store_id > 0 ? $p->p_store_id : $gp->gp_store_id;
            $this->_deductFromFifo($p->p_raw_item_id, floatval($p->p_raw_qty), $store);
            $this->_adjustStock($p->p_raw_item_id, -floatval($p->p_raw_qty), $store);
            $this->_stockLog($p->p_raw_item_id, $store, -floatval($p->p_raw_qty), 0);
            $this->Mfg_model->updateProduction($p->p_id, array('p_status' => 'Dispatched'));
        }
        $this->Mfg_model->updateGatePass($gp_id, array(
            'gp_status' => 'Dispatched',
            'gp_dispatchedat' => date('Y-m-d H:i:s'),
            'gp_approved_by'  => $this->session->userdata('useruser')
        ));
        echo json_encode(array('success' => true));
    }

    public function setGpStatus() {
        $gp_id  = intval($this->input->post('gp_id'));
        $status = $this->input->post('status');
        $allowed = array('Draft', 'Ready for Dispatch', 'In Production');
        if (!in_array($status, $allowed)) { echo json_encode(array('success' => false, 'msg' => 'Invalid status')); return; }
        $gp = $this->Mfg_model->getGatePass($gp_id);
        if (!$gp) { echo json_encode(array('success' => false)); return; }
        // Cannot go back to draft after dispatch
        if (in_array($gp->gp_status, array('Dispatched', 'In Production', 'Completed')) && in_array($status, array('Draft', 'Ready for Dispatch'))) {
            echo json_encode(array('success' => false, 'msg' => 'Cannot edit a dispatched gate pass.')); return;
        }
        $this->Mfg_model->updateGatePass($gp_id, array('gp_status' => $status));
        echo json_encode(array('success' => true));
    }

    /* ============================ receiving ============================ */

    /**
     * Receive output items into the warehouse. For each output the user enters
     * received & damaged (received + damaged must equal produced). Received qty
     * is added to warehouse stock via a GRN; damaged is recorded separately.
     * GRN cost per piece uses the actual received quantity.
     */
    public function receiveOutputs() {
        $p_id  = intval($this->input->post('p_id'));
        $items = json_decode($this->input->post('items'), true);
        $prod  = $this->Mfg_model->getProduction($p_id);
        if (!$prod) { echo json_encode(array('success' => false, 'msg' => 'Production not found')); return; }
        if (!in_array($prod->gp_status, array('Dispatched', 'In Production'))) {
            echo json_encode(array('success' => false, 'msg' => 'Materials must be dispatched before receiving output.')); return;
        }
        if ($prod->p_status === 'Received' || $prod->p_status === 'Completed') {
            echo json_encode(array('success' => false, 'msg' => 'Outputs already received for this production.')); return;
        }
        if (!is_array($items) || empty($items)) { echo json_encode(array('success' => false, 'msg' => 'No output items')); return; }

        $store = $prod->p_store_id > 0 ? $prod->p_store_id : $prod->gp_store_id;
        $outputs = $this->Mfg_model->getOutputs($p_id);
        $byId = array();
        foreach ($outputs as $o) $byId[$o->o_id] = $o;

        // Validate received + damaged == produced for each
        foreach ($items as $it) {
            $oid = intval($it['o_id']);
            if (!isset($byId[$oid])) { echo json_encode(array('success' => false, 'msg' => 'Unknown output row')); return; }
            $produced = floatval($byId[$oid]->o_qty_produced);
            $recv = floatval($it['received']); $dmg = floatval($it['damaged']);
            if (abs(($recv + $dmg) - $produced) > 0.0001) {
                echo json_encode(array('success' => false, 'msg' => 'For '.($byId[$oid]->itm_name).': received ('.$recv.') + damaged ('.$dmg.') must equal produced ('.$produced.').'));
                return;
            }
        }

        // Create one GRN for the whole production's received finished goods
        $grn_id = $this->_openGrn('MFG-' . $prod->p_code, $store, 0);
        $grnTotal = 0;
        foreach ($items as $it) {
            $oid = intval($it['o_id']); $o = $byId[$oid];
            $recv = floatval($it['received']); $dmg = floatval($it['damaged']);
            // GRN cost per piece uses the ACTUAL received qty (final item cost / received)
            $finalItemCost = floatval($o->o_total) + floatval($o->o_allocated_charge);
            $grnPer = $recv > 0 ? ($finalItemCost / $recv) : 0;

            $this->Mfg_model->updateOutput($oid, array(
                'o_received_qty' => $recv,
                'o_damaged_qty'  => $dmg,
                'o_grn_cost'     => round($grnPer, 2),
                'o_remarks'      => isset($it['remarks']) ? $it['remarks'] : ''
            ));
            if ($recv > 0) {
                $this->_grnLine($grn_id, $o->o_item_id, $grnPer, $recv, round($grnPer * $recv, 2));
                $this->_adjustStock($o->o_item_id, $recv, $store);
                $this->_stockLog($o->o_item_id, $store, $recv, $grn_id);
                $grnTotal += $grnPer * $recv;
            }
            if ($dmg > 0) {
                $this->Mfg_model->addDamaged(array('d_p_id' => $p_id, 'd_item_id' => $o->o_item_id,
                    'd_type' => 'output', 'd_qty' => $dmg, 'd_store_id' => $store));
            }
            $this->Mfg_model->addReceiving(array(
                'r_p_id' => $p_id, 'r_o_id' => $oid, 'r_item_id' => $o->o_item_id,
                'r_produced' => floatval($o->o_qty_produced), 'r_received' => $recv, 'r_damaged' => $dmg,
                'r_remarks' => isset($it['remarks']) ? $it['remarks'] : '', 'r_by' => $this->_uid()
            ));
        }
        $this->_closeGrn($grn_id, $grnTotal);
        $this->Mfg_model->updateProduction($p_id, array('p_status' => 'Received'));
        echo json_encode(array('success' => true, 'grn_id' => $grn_id));
    }

    /* ============================ complete ============================ */

    /**
     * Complete a production: settle the remaining (unused) raw material.
     * return_qty goes back into warehouse stock, damaged_qty is written off.
     * (return_qty + damaged_qty must equal the remaining raw material.)
     */
    public function completeProduction() {
        $p_id       = intval($this->input->post('p_id'));
        $return_qty = floatval($this->input->post('return_qty'));
        $damaged_qty= floatval($this->input->post('damaged_qty'));
        $prod = $this->Mfg_model->getProduction($p_id);
        if (!$prod) { echo json_encode(array('success' => false, 'msg' => 'Production not found')); return; }
        if ($prod->p_status !== 'Received') {
            echo json_encode(array('success' => false, 'msg' => 'Receive the output items before completing.')); return;
        }
        $remaining = floatval($prod->p_remaining_raw);
        if (abs(($return_qty + $damaged_qty) - $remaining) > 0.0001) {
            echo json_encode(array('success' => false, 'msg' => 'Return ('.$return_qty.') + Damaged ('.$damaged_qty.') must equal the remaining raw material ('.$remaining.').'));
            return;
        }
        $store = $prod->p_store_id > 0 ? $prod->p_store_id : $prod->gp_store_id;

        if ($return_qty > 0) {
            // Return unused raw material to the warehouse at its GRN cost
            $grn_id = $this->_openGrn('MFG-RET-' . $prod->p_code, $store, 0);
            $this->_grnLine($grn_id, $prod->p_raw_item_id, floatval($prod->p_raw_grn_cost), $return_qty, round(floatval($prod->p_raw_grn_cost) * $return_qty, 2));
            $this->_closeGrn($grn_id, floatval($prod->p_raw_grn_cost) * $return_qty);
            $this->_adjustStock($prod->p_raw_item_id, $return_qty, $store);
            $this->_stockLog($prod->p_raw_item_id, $store, $return_qty, $grn_id);
        }
        if ($damaged_qty > 0) {
            $this->Mfg_model->addDamaged(array('d_p_id' => $p_id, 'd_item_id' => $prod->p_raw_item_id,
                'd_type' => 'raw', 'd_qty' => $damaged_qty, 'd_store_id' => $store));
        }

        $this->Mfg_model->updateProduction($p_id, array(
            'p_returned_raw' => $return_qty,
            'p_damaged_raw'  => $damaged_qty,
            'p_status'       => 'Completed',
            'p_completedat'  => date('Y-m-d H:i:s')
        ));

        // If every production under the gate pass is complete, close the gate pass
        $siblings = $this->Mfg_model->getProductionsByGp($prod->p_gp_id);
        $allDone = true;
        foreach ($siblings as $s) { if ($s->p_status !== 'Completed') { $allDone = false; break; } }
        if ($allDone) {
            $this->Mfg_model->updateGatePass($prod->p_gp_id, array('gp_status' => 'Completed', 'gp_completedat' => date('Y-m-d H:i:s')));
        } else {
            $this->Mfg_model->updateGatePass($prod->p_gp_id, array('gp_status' => 'In Production'));
        }
        echo json_encode(array('success' => true));
    }

    /* ============================ gate pass printout ============================ */

    public function printGatePass($gp_id = 0) {
        $gp = $this->Mfg_model->getGatePass($gp_id);
        if (!$gp) { show_404(); return; }
        $prods = $this->Mfg_model->getProductionsByGp($gp_id);
        // attach raw material details per production
        foreach ($prods as $p) {
            $p->outputs = $this->Mfg_model->getOutputs($p->p_id);
        }
        $data = array(
            'gp'      => $gp,
            'prods'   => $prods,
            'company' => $this->Configs_model->getConfigName(),
            'preparedBy' => $gp->gp_prepared_by,
            'approvedBy' => $gp->gp_approved_by
        );
        $this->load->view('transactions/mfg_gate_pass_print', $data);
    }

    /* ============================ stock helpers ============================ */

    // Self-contained FIFO deduction (raw queries, no shared model that leaves
    // an uncommitted transaction open). Walks oldest cost layers with stock.
    private function _deductFromFifo($item_id, $qty, $storeid = 0) {
        $remaining = floatval($qty);
        if ($remaining <= 0) return;
        $sql = "SELECT cur_id, cur_currentQTY FROM ezy_pos_currentqtywithgrn
                WHERE cur_itmID = ? AND cur_currentQTY > 0 ";
        if ($storeid > 0) $sql .= "AND cur_store_id = " . intval($storeid) . " ";
        $sql .= "ORDER BY cur_id ASC";
        $layers = $this->db->query($sql, array($item_id))->result();
        foreach ($layers as $L) {
            if ($remaining <= 0) break;
            $have = floatval($L->cur_currentQTY);
            $take = min($remaining, $have);
            $newQ = $have - $take;
            $this->db->query("UPDATE ezy_pos_currentqtywithgrn SET cur_currentQTY = ? WHERE cur_id = ?",
                array($newQ, $L->cur_id));
            $remaining -= $take;
        }
    }

    // Adjust per-store display stock by a signed delta
    private function _adjustStock($item_id, $delta, $store_id) {
        if ($store_id > 0) {
            $exists = $this->db->query("SELECT stock_id FROM ezy_pos_stock WHERE stock_itm_id=? AND stock_store_id=?", array($item_id, $store_id))->num_rows();
            if ($exists > 0) {
                $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty + ? WHERE stock_itm_id=? AND stock_store_id=?", array($delta, $item_id, $store_id));
            } else {
                $this->db->insert('ezy_pos_stock', array('stock_itm_id' => $item_id, 'stock_qty' => $delta, 'stock_store_id' => $store_id, 'stock_status' => 1));
            }
        } else {
            $exists = $this->db->query("SELECT stock_id FROM ezy_pos_stock WHERE stock_itm_id=?", array($item_id))->num_rows();
            if ($exists > 0) {
                $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty + ? WHERE stock_itm_id=?", array($delta, $item_id));
            } else {
                $this->db->insert('ezy_pos_stock', array('stock_itm_id' => $item_id, 'stock_qty' => $delta, 'stock_status' => 1));
            }
        }
    }

    private function _stockLog($item_id, $store_id, $qty, $grn_id) {
        $this->db->insert('ezy_pos_stock_log', array(
            'stocklog_itmid' => $item_id,
            'stocklog_store_id' => $store_id,
            'stocklog_qty' => $qty,
            'stocklog_grnID' => $grn_id,
            'stocklog_status' => 1
        ));
    }

    private function _openGrn($code, $store_id, $supplier_id) {
        $this->db->insert('ezy_pos_grns', array(
            'grn_code' => $code,
            'grn_supplier_id' => $supplier_id ?: 0,
            'grn_grandtotal' => 0,
            'grn_subtotal' => 0,
            'grn_discount' => 0,
            'grn_less' => 0,
            'grn_createdby' => $this->_uid(),
            'grn_location' => $store_id,
            'grn_date' => date('Y-m-d'),
            'grn_status' => 1
        ));
        return $this->db->insert_id();
    }

    private function _grnLine($grn_id, $item_id, $price, $qty, $total) {
        $this->db->insert('ezy_pos_grn_item', array(
            'grnitm_grn_id' => $grn_id,
            'grnitm_itemid' => $item_id,
            'grnitm_price' => $price,
            'grnitm_quantity' => $qty,
            'grnitm_total' => $total,
            'grnitm_discount' => 0
        ));
        // FIFO cost layer for the finished / returned goods
        $this->db->insert('ezy_pos_currentqtywithgrn', array(
            'cur_grnID' => $grn_id,
            'cur_itmID' => $item_id,
            'cur_grnQty' => $qty,
            'cur_grnPrice' => $price,
            'cur_grnTotal' => $total,
            'cur_currentQTY' => $qty,
            'cur_store_id' => $this->db->query("SELECT grn_location FROM ezy_pos_grns WHERE grn_id=?", array($grn_id))->row()->grn_location,
            'cur_status' => 1
        ));
    }

    private function _closeGrn($grn_id, $total) {
        $this->db->where('grn_id', $grn_id);
        $this->db->update('ezy_pos_grns', array('grn_grandtotal' => round($total, 2), 'grn_subtotal' => round($total, 2)));
    }
}
