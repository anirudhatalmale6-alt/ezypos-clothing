<?php
class GatePass_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getNextCode()
    {
        $this->db->select_max('gp_id');
        $row = $this->db->get('ezy_pos_gate_pass')->row();
        $next = ($row && $row->gp_id) ? $row->gp_id + 1 : 1;
        return 'GP-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function createGatePass($data)
    {
        $this->db->insert('ezy_pos_gate_pass', $data);
        return $this->db->insert_id();
    }

    public function addItem($data)
    {
        $this->db->insert('ezy_pos_gate_pass_items', $data);
        return $this->db->insert_id();
    }

    public function getGatePassesByProduction($prod_id)
    {
        $this->db->select('gp.*, u.user_name AS issued_by_name, s.store_name');
        $this->db->from('ezy_pos_gate_pass gp');
        $this->db->join('ezy_pos_users u', 'gp.gp_issued_by = u.user_id', 'left');
        $this->db->join('ezy_pos_stores s', 'gp.gp_store_id = s.store_id', 'left');
        $this->db->where('gp.gp_prod_id', $prod_id);
        $this->db->where('gp.gp_status !=', 'Cancelled');
        $this->db->order_by('gp.gp_id', 'ASC');
        return $this->db->get()->result();
    }

    public function getGatePass($gp_id)
    {
        $this->db->select('gp.*, u.user_name AS issued_by_name, s.store_name, p.prod_code');
        $this->db->from('ezy_pos_gate_pass gp');
        $this->db->join('ezy_pos_users u', 'gp.gp_issued_by = u.user_id', 'left');
        $this->db->join('ezy_pos_stores s', 'gp.gp_store_id = s.store_id', 'left');
        $this->db->join('ezy_pos_production p', 'gp.gp_prod_id = p.prod_id', 'left');
        $this->db->where('gp.gp_id', $gp_id);
        return $this->db->get()->row();
    }

    public function getItems($gp_id)
    {
        $this->db->select('gi.*, i.itm_code, i.itm_name, i.itm_uom');
        $this->db->from('ezy_pos_gate_pass_items gi');
        $this->db->join('ezy_pos_items i', 'gi.gpitem_item_id = i.itm_id', 'left');
        $this->db->where('gi.gpitem_gp_id', $gp_id);
        $this->db->order_by('gi.gpitem_id', 'ASC');
        return $this->db->get()->result();
    }

    public function getItemById($gpitem_id)
    {
        return $this->db->where('gpitem_id', $gpitem_id)->get('ezy_pos_gate_pass_items')->row();
    }

    public function returnItem($gpitem_id, $return_qty)
    {
        $item = $this->getItemById($gpitem_id);
        if (!$item) return false;

        $max_returnable = floatval($item->gpitem_qty) - floatval($item->gpitem_returned_qty);
        if ($return_qty > $max_returnable) return false;

        $new_returned = floatval($item->gpitem_returned_qty) + $return_qty;
        $net_qty = floatval($item->gpitem_qty) - $new_returned;
        $new_total = $net_qty * floatval($item->gpitem_unit_price);

        $this->db->where('gpitem_id', $gpitem_id);
        $this->db->update('ezy_pos_gate_pass_items', array(
            'gpitem_returned_qty' => $new_returned,
            'gpitem_total' => $new_total
        ));

        return $item;
    }

    public function recalculateTotal($gp_id)
    {
        $this->db->select('SUM(gpitem_total) as total');
        $this->db->where('gpitem_gp_id', $gp_id);
        $row = $this->db->get('ezy_pos_gate_pass_items')->row();
        $total = $row ? floatval($row->total) : 0;

        $this->db->where('gp_id', $gp_id);
        $this->db->update('ezy_pos_gate_pass', array('gp_total' => $total));

        // Update gate pass status based on returns
        $items = $this->getItems($gp_id);
        $all_returned = true;
        $any_returned = false;
        foreach ($items as $gi) {
            if (floatval($gi->gpitem_returned_qty) > 0) $any_returned = true;
            if (floatval($gi->gpitem_returned_qty) < floatval($gi->gpitem_qty)) $all_returned = false;
        }

        if ($all_returned && count($items) > 0) {
            $status = 'Fully Returned';
        } elseif ($any_returned) {
            $status = 'Partially Returned';
        } else {
            $status = 'Issued';
        }

        $this->db->where('gp_id', $gp_id);
        $this->db->update('ezy_pos_gate_pass', array('gp_status' => $status));

        return $total;
    }

    public function getAllGatePassMaterials($prod_id)
    {
        $this->db->select('gi.*, i.itm_code, i.itm_name, i.itm_uom, gp.gp_code, gp.gp_date, gp.gp_id');
        $this->db->from('ezy_pos_gate_pass_items gi');
        $this->db->join('ezy_pos_gate_pass gp', 'gi.gpitem_gp_id = gp.gp_id');
        $this->db->join('ezy_pos_items i', 'gi.gpitem_item_id = i.itm_id', 'left');
        $this->db->where('gp.gp_prod_id', $prod_id);
        $this->db->where('gp.gp_status !=', 'Cancelled');
        $this->db->order_by('gp.gp_id, gi.gpitem_id', 'ASC');
        return $this->db->get()->result();
    }

    public function tableExists()
    {
        return $this->db->table_exists('ezy_pos_gate_pass');
    }
}
