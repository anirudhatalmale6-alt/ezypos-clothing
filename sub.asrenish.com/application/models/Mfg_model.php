<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Manufacturing / Production (Item 4 redesign)
 *
 * Workflow: 1 Raw Material (Fabric) = many Output Items,
 *           1 Gate Pass = many Production records.
 *
 * All data lives in the ezy_pos_mfg_* tables so the old production
 * module is untouched.
 */
class Mfg_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function ready() {
        return $this->db->table_exists('ezy_pos_mfg_gatepass')
            && $this->db->table_exists('ezy_pos_mfg_production');
    }

    /* ============================ lookups ============================ */

    // Warehouses only (Production consumes from / returns to a warehouse)
    public function getWarehouses() {
        $fields = $this->db->list_fields('ezy_pos_stores');
        $this->db->where('store_status', 1);
        if (in_array('store_is_warehouse', $fields)) {
            $this->db->where('store_is_warehouse', 1);
        }
        $this->db->order_by('store_name');
        return $this->db->get('ezy_pos_stores')->result();
    }

    // Default warehouse for Production (auto-selected). Uses the first active
    // warehouse (store_is_warehouse = 1). Production always consumes from this
    // warehouse so the user never has to pick it manually.
    public function getDefaultWarehouse() {
        $fields = $this->db->list_fields('ezy_pos_stores');
        $this->db->where('store_status', 1);
        if (in_array('store_is_warehouse', $fields)) {
            $this->db->where('store_is_warehouse', 1);
        }
        $this->db->order_by('store_id', 'asc');
        $this->db->limit(1);
        return $this->db->get('ezy_pos_stores')->row();
    }

    // Raw materials = Fabric category (cat_is_raw = 1). Latest GRN cost + stock.
    public function getFabricItems() {
        $str = "SELECT i.itm_id, i.itm_code, i.itm_name, i.itm_uom, c.cat_name
                FROM ezy_pos_items i
                INNER JOIN ezy_pos_categories c ON i.itm_category = c.cat_id
                WHERE c.cat_is_raw = 1 AND i.itm_status = 1
                ORDER BY i.itm_name";
        return $this->db->query($str)->result();
    }

    public function getFinishedItems() {
        $str = "SELECT i.itm_id, i.itm_code, i.itm_name, i.itm_uom, c.cat_name
                FROM ezy_pos_items i
                INNER JOIN ezy_pos_categories c ON i.itm_category = c.cat_id
                WHERE c.cat_is_raw = 0 AND i.itm_status = 1
                ORDER BY i.itm_name";
        return $this->db->query($str)->result();
    }

    public function getItem($item_id) {
        $this->db->where('itm_id', $item_id);
        return $this->db->get('ezy_pos_items')->row();
    }

    // Latest GRN cost per UOM for a raw material (most recent GRN layer).
    public function getLatestGrnPrice($item_id, $store_id = 0) {
        // Prefer the most recent layer that still has stock in this warehouse
        if ($store_id > 0) {
            $row = $this->db->query(
                "SELECT cur_grnPrice FROM ezy_pos_currentqtywithgrn
                 WHERE cur_itmID = ? AND cur_store_id = ?
                 ORDER BY cur_id DESC LIMIT 1",
                array($item_id, $store_id))->row();
            if ($row) return floatval($row->cur_grnPrice);
        }
        $row = $this->db->query(
            "SELECT cur_grnPrice FROM ezy_pos_currentqtywithgrn
             WHERE cur_itmID = ?
             ORDER BY cur_id DESC LIMIT 1", array($item_id))->row();
        if ($row) return floatval($row->cur_grnPrice);
        // Fallback: item selling price
        $it = $this->getItem($item_id);
        return $it ? floatval($it->itm_sellingprice) : 0;
    }

    // Available stock of a raw material in a warehouse
    public function getStockQty($item_id, $store_id = 0) {
        if ($store_id > 0) {
            $row = $this->db->query(
                "SELECT COALESCE(SUM(stock_qty),0) q FROM ezy_pos_stock
                 WHERE stock_itm_id = ? AND stock_store_id = ?",
                array($item_id, $store_id))->row();
        } else {
            $row = $this->db->query(
                "SELECT COALESCE(SUM(stock_qty),0) q FROM ezy_pos_stock
                 WHERE stock_itm_id = ?", array($item_id))->row();
        }
        return $row ? floatval($row->q) : 0;
    }

    /* ============================ gate pass ============================ */

    public function getNextGpCode() {
        $r = $this->db->select_max('gp_id')->get('ezy_pos_mfg_gatepass')->row();
        $next = (isset($r->gp_id) ? $r->gp_id : 0) + 1;
        return 'GP-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function createGatePass($data) {
        $this->db->insert('ezy_pos_mfg_gatepass', $data);
        return $this->db->insert_id();
    }

    // True if a gate pass with this code already exists (uniqueness check for
    // the manually-entered gate pass number). Case-insensitive, trimmed.
    public function gatePassCodeExists($code, $exclude_id = 0) {
        $this->db->from('ezy_pos_mfg_gatepass');
        $this->db->where('LOWER(TRIM(gp_code))', strtolower(trim($code)));
        if ($exclude_id > 0) $this->db->where('gp_id !=', $exclude_id);
        return $this->db->count_all_results() > 0;
    }

    public function getGatePass($gp_id) {
        $str = "SELECT g.*, s.store_name
                FROM ezy_pos_mfg_gatepass g
                LEFT JOIN ezy_pos_stores s ON g.gp_store_id = s.store_id
                WHERE g.gp_id = ?";
        return $this->db->query($str, array($gp_id))->row();
    }

    // Search gate passes by code (for the searchable dropdown)
    public function searchGatePasses($term = '') {
        $this->db->select('g.*, s.store_name');
        $this->db->from('ezy_pos_mfg_gatepass g');
        $this->db->join('ezy_pos_stores s', 'g.gp_store_id = s.store_id', 'left');
        if ($term !== '') {
            $this->db->like('g.gp_code', $term);
        }
        $this->db->order_by('g.gp_id', 'DESC');
        $this->db->limit(50);
        return $this->db->get()->result();
    }

    public function updateGatePass($gp_id, $data) {
        $this->db->where('gp_id', $gp_id);
        return $this->db->update('ezy_pos_mfg_gatepass', $data);
    }

    /* ============================ production ============================ */

    public function getNextProdCode() {
        $r = $this->db->select_max('p_id')->get('ezy_pos_mfg_production')->row();
        $next = (isset($r->p_id) ? $r->p_id : 0) + 1;
        return 'PRD-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function createProduction($data) {
        $this->db->insert('ezy_pos_mfg_production', $data);
        return $this->db->insert_id();
    }

    public function updateProduction($p_id, $data) {
        $this->db->where('p_id', $p_id);
        return $this->db->update('ezy_pos_mfg_production', $data);
    }

    public function getProduction($p_id) {
        // tailor name joined only if the p_tailor_id column exists
        $hasTailor = in_array('p_tailor_id', $this->db->list_fields('ezy_pos_mfg_production'));
        $tailorSel  = $hasTailor ? ", sup.sup_name AS tailor_name, p.p_tailor_id AS tailor_id" : "";
        $tailorJoin = $hasTailor ? " LEFT JOIN ezy_pos_suppliers sup ON p.p_tailor_id = sup.sup_id" : "";
        $str = "SELECT p.*, g.gp_code, g.gp_status, g.gp_store_id,
                       i.itm_name AS raw_name, i.itm_code AS raw_code, i.itm_uom AS raw_uom_master,
                       s.store_name" . $tailorSel . "
                FROM ezy_pos_mfg_production p
                LEFT JOIN ezy_pos_mfg_gatepass g ON p.p_gp_id = g.gp_id
                LEFT JOIN ezy_pos_items i ON p.p_raw_item_id = i.itm_id
                LEFT JOIN ezy_pos_stores s ON p.p_store_id = s.store_id" . $tailorJoin . "
                WHERE p.p_id = ?";
        return $this->db->query($str, array($p_id))->row();
    }

    public function getProductionsByGp($gp_id) {
        $hasTailor = in_array('p_tailor_id', $this->db->list_fields('ezy_pos_mfg_production'));
        $tailorSel  = $hasTailor ? ", sup.sup_name AS tailor_name" : "";
        $tailorJoin = $hasTailor ? " LEFT JOIN ezy_pos_suppliers sup ON p.p_tailor_id = sup.sup_id" : "";
        $str = "SELECT p.*, i.itm_name AS raw_name, i.itm_code AS raw_code" . $tailorSel . "
                FROM ezy_pos_mfg_production p
                LEFT JOIN ezy_pos_items i ON p.p_raw_item_id = i.itm_id" . $tailorJoin . "
                WHERE p.p_gp_id = ?
                ORDER BY p.p_id";
        return $this->db->query($str, array($gp_id))->result();
    }

    public function listAll() {
        $str = "SELECT p.*, g.gp_code, g.gp_status, i.itm_name AS raw_name,
                       s.store_name, u.user_name AS created_by_name
                FROM ezy_pos_mfg_production p
                LEFT JOIN ezy_pos_mfg_gatepass g ON p.p_gp_id = g.gp_id
                LEFT JOIN ezy_pos_items i ON p.p_raw_item_id = i.itm_id
                LEFT JOIN ezy_pos_stores s ON p.p_store_id = s.store_id
                LEFT JOIN ezy_pos_users u ON p.p_createdby = u.user_id
                ORDER BY p.p_id DESC";
        return $this->db->query($str)->result();
    }

    /* ============================ outputs ============================ */

    public function getOutputs($p_id) {
        $str = "SELECT o.*, i.itm_name, i.itm_code, i.itm_uom
                FROM ezy_pos_mfg_output o
                LEFT JOIN ezy_pos_items i ON o.o_item_id = i.itm_id
                WHERE o.o_p_id = ?
                ORDER BY o.o_id";
        return $this->db->query($str, array($p_id))->result();
    }

    public function deleteOutputs($p_id) {
        $this->db->where('o_p_id', $p_id);
        $this->db->delete('ezy_pos_mfg_output');
    }

    public function addOutput($data) {
        $this->db->insert('ezy_pos_mfg_output', $data);
        return $this->db->insert_id();
    }

    public function updateOutput($o_id, $data) {
        $this->db->where('o_id', $o_id);
        return $this->db->update('ezy_pos_mfg_output', $data);
    }

    /* ============================ charges ============================ */

    public function getCharges($p_id) {
        $this->db->where('c_p_id', $p_id);
        $this->db->order_by('c_id');
        return $this->db->get('ezy_pos_mfg_charge')->result();
    }

    public function deleteCharges($p_id) {
        $this->db->where('c_p_id', $p_id);
        $this->db->delete('ezy_pos_mfg_charge');
    }

    public function addCharge($data) {
        $this->db->insert('ezy_pos_mfg_charge', $data);
        return $this->db->insert_id();
    }

    /* ============================ receiving / damaged ============================ */

    public function addReceiving($data) {
        $this->db->insert('ezy_pos_mfg_receiving', $data);
        return $this->db->insert_id();
    }

    public function getReceiving($p_id) {
        $str = "SELECT r.*, i.itm_name, i.itm_code, g.gp_code, u.user_name AS received_by
                FROM ezy_pos_mfg_receiving r
                LEFT JOIN ezy_pos_items i ON r.r_item_id = i.itm_id
                LEFT JOIN ezy_pos_mfg_production p ON r.r_p_id = p.p_id
                LEFT JOIN ezy_pos_mfg_gatepass g ON p.p_gp_id = g.gp_id
                LEFT JOIN ezy_pos_users u ON r.r_by = u.user_id
                WHERE r.r_p_id = ?
                ORDER BY r.r_id";
        return $this->db->query($str, array($p_id))->result();
    }

    public function addDamaged($data) {
        $this->db->insert('ezy_pos_mfg_damaged', $data);
        return $this->db->insert_id();
    }

    /* ============================ payments ============================ */

    // Same workflow as the Tailoring payment module: Cash / Cheque, multiple
    // payments, running paid/balance and a full payment history.
    public function paymentsReady() {
        return $this->db->table_exists('ezy_pos_mfg_payment');
    }

    public function getPayments($p_id) {
        $str = "SELECT mp.*, u.user_name AS created_by_name
                FROM ezy_pos_mfg_payment mp
                LEFT JOIN ezy_pos_users u ON mp.mp_created_by = u.user_id
                WHERE mp.mp_p_id = ?
                ORDER BY mp.mp_id DESC";
        return $this->db->query($str, array($p_id))->result();
    }

    public function addPayment($p_id, $amount, $method = 'Cash', $ref = '', $by = 0) {
        $this->db->insert('ezy_pos_mfg_payment', array(
            'mp_p_id'       => $p_id,
            'mp_amount'     => $amount,
            'mp_method'     => $method ? $method : 'Cash',
            'mp_ref'        => $ref,
            'mp_created_by' => $by
        ));
        $this->syncPaidBalance($p_id);
        return $this->db->insert_id();
    }

    public function deletePayment($mp_id) {
        $row = $this->db->query("SELECT mp_p_id FROM ezy_pos_mfg_payment WHERE mp_id = ?", array($mp_id))->row();
        $this->db->where('mp_id', $mp_id)->delete('ezy_pos_mfg_payment');
        if ($row) $this->syncPaidBalance($row->mp_p_id);
    }

    // Recompute paid + balance from the ledger against the final bill.
    public function syncPaidBalance($p_id) {
        if (!$this->paymentsReady()) return;
        $fields = $this->db->list_fields('ezy_pos_mfg_production');
        if (!in_array('p_paid', $fields)) return;
        $paidRow = $this->db->query("SELECT COALESCE(SUM(mp_amount),0) paid FROM ezy_pos_mfg_payment WHERE mp_p_id = ?", array($p_id))->row();
        $paid = $paidRow ? floatval($paidRow->paid) : 0;
        $prod = $this->db->query("SELECT p_final_bill FROM ezy_pos_mfg_production WHERE p_id = ?", array($p_id))->row();
        $bill = $prod ? floatval($prod->p_final_bill) : 0;
        $this->db->where('p_id', $p_id);
        $this->db->update('ezy_pos_mfg_production', array(
            'p_paid'    => round($paid, 2),
            'p_balance' => round($bill - $paid, 2)
        ));
    }

    /**
     * Recalculate a production's derived totals from its outputs & charges.
     * - material cost per output = material used x latest GRN cost per UOM
     * - output total = material cost + additional + tailoring
     * - subtotal = sum of output totals
     * - overall charges total = sum of charges
     * - final bill = subtotal + overall charges
     * - remaining raw = raw qty - total material used
     * Overall charges are split EQUALLY across output rows (item types) and the
     * per-piece GRN cost is (output total + allocated charge) / qty produced.
     */
    public function recalc($p_id) {
        $prod = $this->getProduction($p_id);
        if (!$prod) return;
        $grnCost = floatval($prod->p_raw_grn_cost);
        $outputs = $this->getOutputs($p_id);
        $charges = $this->getCharges($p_id);

        $overall = 0;
        foreach ($charges as $c) $overall += floatval($c->c_amount);

        $n = count($outputs);
        $perItemCharge = $n > 0 ? ($overall / $n) : 0;

        $subtotal = 0; $materialUsed = 0;
        foreach ($outputs as $o) {
            $matUsed  = floatval($o->o_material_used);
            $matCost  = $matUsed * $grnCost;
            $total    = $matCost + floatval($o->o_additional) + floatval($o->o_tailoring);
            $finalItemCost = $total + $perItemCharge; // after equal charge distribution
            $qty      = floatval($o->o_qty_produced);
            $grnPer   = $qty > 0 ? ($finalItemCost / $qty) : 0;
            $materialUsed += $matUsed;
            $subtotal     += $total;
            $this->updateOutput($o->o_id, array(
                'o_material_cost'    => round($matCost, 2),
                'o_allocated_charge' => round($perItemCharge, 2),
                'o_total'            => round($total, 2),
                'o_grn_cost'         => round($grnPer, 2)
            ));
        }

        $finalBill = $subtotal + $overall;
        $remaining = floatval($prod->p_raw_qty) - $materialUsed;

        $this->updateProduction($p_id, array(
            'p_material_used' => round($materialUsed, 2),
            'p_subtotal'      => round($subtotal, 2),
            'p_overall_total' => round($overall, 2),
            'p_final_bill'    => round($finalBill, 2),
            'p_remaining_raw' => round($remaining, 2)
        ));
        // Keep paid/balance snapshot in sync now that the final bill changed
        $this->syncPaidBalance($p_id);
    }
}
