<?php
class Production_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // Get next production code
    public function getNextProductionCode() {
        $this->db->select_max('prod_id');
        $result = $this->db->get('ezy_pos_production')->row();
        $next = ($result->prod_id ?? 0) + 1;
        return 'PROD-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    // Get raw material items (categories flagged as raw)
    public function getRawMaterialItems() {
        $str = "SELECT i.itm_id, i.itm_code, i.itm_name, i.itm_uom, c.cat_name,
                COALESCE(s.stock_qty, 0) as stock_qty
                FROM ezy_pos_items i
                INNER JOIN ezy_pos_categories c ON i.itm_category = c.cat_id
                LEFT JOIN ezy_pos_stock s ON i.itm_id = s.stock_itm_id
                WHERE c.cat_is_raw = 1 AND i.itm_status = 1
                ORDER BY c.cat_name, i.itm_name";
        $query = $this->db->query($str);
        return $query->result();
    }

    // Get finished (non-raw) items for output
    public function getFinishedItems() {
        $str = "SELECT i.itm_id, i.itm_code, i.itm_name, i.itm_uom, c.cat_name
                FROM ezy_pos_items i
                INNER JOIN ezy_pos_categories c ON i.itm_category = c.cat_id
                WHERE c.cat_is_raw = 0 AND i.itm_status = 1
                ORDER BY c.cat_name, i.itm_name";
        $query = $this->db->query($str);
        return $query->result();
    }

    // Get tailors (suppliers)
    public function getTailors() {
        $this->db->where('sup_status', 1);
        $this->db->order_by('sup_name');
        return $this->db->get('ezy_pos_suppliers')->result();
    }

    // Create production order
    public function createProduction($data) {
        $this->db->insert('ezy_pos_production', $data);
        return $this->db->insert_id();
    }

    // Add material to production
    public function addMaterial($data) {
        $this->db->insert('ezy_pos_production_materials', $data);
        return $this->db->insert_id();
    }

    // Add cost to production
    public function addCost($data) {
        $this->db->insert('ezy_pos_production_costs', $data);
        return $this->db->insert_id();
    }

    // Recalculate production costs
    public function recalculateCosts($prod_id) {
        // Sum material costs
        $this->db->select_sum('prodmat_total');
        $this->db->where('prodmat_prod_id', $prod_id);
        $mat = $this->db->get('ezy_pos_production_materials')->row();
        $material_cost = $mat->prodmat_total ?? 0;

        // Sum tailoring costs
        $this->db->select_sum('prodcost_amount');
        $this->db->where('prodcost_prod_id', $prod_id);
        $this->db->where('prodcost_type', 'tailoring');
        $tail = $this->db->get('ezy_pos_production_costs')->row();
        $tailoring_cost = $tail->prodcost_amount ?? 0;

        // Sum other costs
        $this->db->select_sum('prodcost_amount');
        $this->db->where('prodcost_prod_id', $prod_id);
        $this->db->where('prodcost_type', 'other');
        $other = $this->db->get('ezy_pos_production_costs')->row();
        $other_cost = $other->prodcost_amount ?? 0;

        $total_cost = $material_cost + $tailoring_cost + $other_cost;

        // Get output qty for unit cost
        $this->db->select('prod_output_qty');
        $this->db->where('prod_id', $prod_id);
        $prod = $this->db->get('ezy_pos_production')->row();
        $unit_cost = $prod->prod_output_qty > 0 ? $total_cost / $prod->prod_output_qty : 0;

        $this->db->where('prod_id', $prod_id);
        $this->db->update('ezy_pos_production', array(
            'prod_material_cost' => $material_cost,
            'prod_tailoring_cost' => $tailoring_cost,
            'prod_other_cost' => $other_cost,
            'prod_total_cost' => $total_cost,
            'prod_unit_cost' => $unit_cost
        ));
    }

    // Update status
    public function updateStatus($prod_id, $status) {
        $this->db->where('prod_id', $prod_id);
        return $this->db->update('ezy_pos_production', array('prod_status' => $status));
    }

    // Get single production
    public function getProduction($prod_id) {
        $str = "SELECT p.*, i.itm_name as output_item_name, i.itm_code as output_item_code,
                s.sup_name as tailor_name
                FROM ezy_pos_production p
                LEFT JOIN ezy_pos_items i ON p.prod_output_item_id = i.itm_id
                LEFT JOIN ezy_pos_suppliers s ON p.prod_tailor_id = s.sup_id
                WHERE p.prod_id = ?";
        $query = $this->db->query($str, array($prod_id));
        return $query->row();
    }

    // Get materials for a production
    public function getMaterials($prod_id) {
        $str = "SELECT m.*, i.itm_name, i.itm_code, i.itm_uom
                FROM ezy_pos_production_materials m
                INNER JOIN ezy_pos_items i ON m.prodmat_item_id = i.itm_id
                WHERE m.prodmat_prod_id = ?
                ORDER BY m.prodmat_id";
        $query = $this->db->query($str, array($prod_id));
        return $query->result();
    }

    // Get costs for a production
    public function getCosts($prod_id) {
        $this->db->where('prodcost_prod_id', $prod_id);
        $this->db->order_by('prodcost_id');
        return $this->db->get('ezy_pos_production_costs')->result();
    }

    // Get all productions
    public function getAllProductions() {
        $str = "SELECT p.*, i.itm_name as output_item_name, i.itm_code as output_item_code,
                s.sup_name as tailor_name, u.user_name as created_by_name
                FROM ezy_pos_production p
                LEFT JOIN ezy_pos_items i ON p.prod_output_item_id = i.itm_id
                LEFT JOIN ezy_pos_suppliers s ON p.prod_tailor_id = s.sup_id
                LEFT JOIN ezy_pos_users u ON p.prod_createdby = u.user_id
                ORDER BY p.prod_id DESC";
        $query = $this->db->query($str);
        return $query->result();
    }

    // Get material by ID
    public function getMaterialById($matId) {
        $this->db->where('prodmat_id', $matId);
        return $this->db->get('ezy_pos_production_materials')->row();
    }

    // Delete material
    public function deleteMaterial($matId) {
        $this->db->where('prodmat_id', $matId);
        return $this->db->delete('ezy_pos_production_materials');
    }

    // Get cost by ID
    public function getCostById($costId) {
        $this->db->where('prodcost_id', $costId);
        return $this->db->get('ezy_pos_production_costs')->row();
    }

    // Delete cost
    public function deleteCost($costId) {
        $this->db->where('prodcost_id', $costId);
        return $this->db->delete('ezy_pos_production_costs');
    }

    // Get oldest GRN price for material (FIFO costing)
    public function getOldestGrnPrice($item_id) {
        $str = "SELECT cur_grnPrice FROM ezy_pos_currentqtywithgrn
                WHERE cur_itmID = ? AND cur_currentQTY > 0
                ORDER BY cur_id ASC LIMIT 1";
        $query = $this->db->query($str, array($item_id));
        if ($query->num_rows() > 0) {
            return $query->row()->cur_grnPrice;
        }
        // Fallback: get selling price from items table
        $this->db->select('itm_sellingprice');
        $this->db->where('itm_id', $item_id);
        $item = $this->db->get('ezy_pos_items')->row();
        return $item ? $item->itm_sellingprice : 0;
    }
}
