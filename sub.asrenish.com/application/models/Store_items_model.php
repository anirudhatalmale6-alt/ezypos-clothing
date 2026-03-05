<?php
class Store_items_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all active stores
    public function get_all_stores() {
        $this->db->select('wh_id, wh_name');
        $this->db->from('ezy_pos_warehouse');
        $this->db->where('wh_status', 1); // Fetch only active stores
        $query = $this->db->get();
        return $query->result_array();
    }

    public function fetch_store_items() {
        $this->db->select('
            ezy_pos_store_items.storeitem_grnid AS grn_id,
            ezy_pos_items.itm_name AS item_name,
            ezy_pos_currentqtywithgrn.cur_grnQty AS grn_quantity,
            ezy_pos_currentqtywithgrn.cur_currentQTY AS current_quantity,
            ezy_pos_warehouse.wh_name AS wh_name,
            ezy_pos_grn_item.grnitm_createdat AS date
        ');
        $this->db->from('ezy_pos_store_items');
        $this->db->join('ezy_pos_items', 'ezy_pos_items.itm_id = ezy_pos_store_items.storeitem_itemid');
        $this->db->join('ezy_pos_currentqtywithgrn', 'ezy_pos_currentqtywithgrn.cur_grnID = ezy_pos_store_items.storeitem_grnid AND ezy_pos_currentqtywithgrn.cur_itmID = ezy_pos_store_items.storeitem_itemid');
        $this->db->join('ezy_pos_warehouse', 'ezy_pos_warehouse.wh_id = ezy_pos_store_items.storeitem_storeid');
        $this->db->join('ezy_pos_grn_item', 'ezy_pos_grn_item.grnitm_grn_id = ezy_pos_store_items.storeitem_grnid AND ezy_pos_grn_item.grnitm_itemid = ezy_pos_store_items.storeitem_itemid');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    // Warehouse functions

    public function search_grn($grnID) {
        $this->db->select('cq.cur_grnID as grn_id, cq.cur_itmID as item_id, i.itm_name as item_name, cq.cur_grnQty as grn_quantity, cq.cur_currentQTY as current_quantity, s.wh_name, si.storeitem_storeid as wh_id');
        $this->db->from('ezy_pos_currentqtywithgrn cq');
        $this->db->join('ezy_pos_items i', 'i.itm_id = cq.cur_itmID');
        $this->db->join('ezy_pos_store_items si', 'si.storeitem_grnid = cq.cur_grnID AND si.storeitem_itemid = cq.cur_itmID', 'left');
        $this->db->join('ezy_pos_warehouse s', 's.wh_id = si.storeitem_storeid', 'left');
        $this->db->where('cq.cur_grnID', $grnID);
        
        $query = $this->db->get();
        return $query->result_array();
    }
    


    public function insert_store_items($data) {
        $this->db->insert_batch('ezy_pos_store_items', $data);
    }
    
    
     // Fetch all warehouses from the database
     public function getAllWarehouses() {
        $query = $this->db->get('ezy_pos_warehouse');
        return $query->result_array();
    }

    // Delete a warehouse by ID
    public function deleteWarehouseById($wh_id) {
        $this->db->where('wh_id', $wh_id);
        return $this->db->delete('ezy_pos_warehouse');
    }
    

    public function updateWarehouseById($wh_id, $data) {
        $this->db->where('wh_id', $wh_id);
        return $this->db->update('ezy_pos_warehouse', $data);
    }

     // Insert warehouse data into the database
     public function insertWarehouse($data) {
        $this->db->insert('ezy_pos_warehouse', $data);
        return $this->db->insert_id(); // Return last inserted ID
    }

   
    
    public function updateWarehouseForItem($grnId, $itemName, $newWarehouseId) {
        // Fetch item ID from the item name
        $this->db->select('itm_id');
        $this->db->from('ezy_pos_items');
        $this->db->where('itm_name', $itemName);
        $query = $this->db->get();
        $item = $query->row();
    
        if (!$item) {
            return false; // Item not found
        }
    
        $itemId = $item->itm_id;
    
        // Update the warehouse for the selected item
        $this->db->where('storeitem_grnid', $grnId);
        $this->db->where('storeitem_itemid', $itemId);
        return $this->db->update('ezy_pos_store_items', ['storeitem_storeid' => $newWarehouseId]);
    }
    
    
    
    // Warehouse functions
    public function fetch_itemwise_current_qty_with_warehouse() {
        $this->db->select('
            ezy_pos_items.itm_id AS item_id,
            ezy_pos_items.itm_name AS item_name,
            ezy_pos_warehouse.wh_id AS warehouse_id,
            ezy_pos_warehouse.wh_name AS warehouse_name,
            SUM(ezy_pos_currentqtywithgrn.cur_currentQTY) AS total_current_quantity
        ');
        $this->db->from('ezy_pos_currentqtywithgrn');
        $this->db->join('ezy_pos_items', 'ezy_pos_items.itm_id = ezy_pos_currentqtywithgrn.cur_itmID');
        $this->db->join('ezy_pos_item_warehouse', 'ezy_pos_item_warehouse.ware_item_id = ezy_pos_currentqtywithgrn.cur_itmID');
        $this->db->join('ezy_pos_warehouse', 'ezy_pos_warehouse.wh_id = ezy_pos_item_warehouse.ware_warehouse_id');
        $this->db->group_by('ezy_pos_items.itm_id, ezy_pos_warehouse.wh_id');
    
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getWarehouseData($warehouse_id) {
        $this->db->select('
            SUM(ezy_pos_currentqtywithgrn.cur_currentQTY * ezy_pos_currentqtywithgrn.cur_grnPrice) AS total_grn_value,
            SUM(ezy_pos_currentqtywithgrn.cur_currentQTY * ezy_pos_items.itm_sellingprice) AS total_selling_value
        ');
        $this->db->from('ezy_pos_currentqtywithgrn');
        $this->db->join('ezy_pos_item_warehouse', 'ezy_pos_item_warehouse.ware_item_id = ezy_pos_currentqtywithgrn.cur_itmID');
        $this->db->join('ezy_pos_warehouse', 'ezy_pos_warehouse.wh_id = ezy_pos_item_warehouse.ware_warehouse_id');
        $this->db->join('ezy_pos_items', 'ezy_pos_items.itm_id = ezy_pos_currentqtywithgrn.cur_itmID'); // join items for selling price
        $this->db->where('ezy_pos_warehouse.wh_id', $warehouse_id);
    
        $query = $this->db->get();
        return $query->row_array(); // single row with total_grn_value and total_selling_value
    }
    
    
    public function getStockByWarehouse($warehouse_id)
        {
            $this->db->select('s.*, i.itm_code, i.itm_name, i.itm_uom, i.itm_reorderlevel, w.warehouse_name as warehouse_names');
            $this->db->from('stocks s');
            $this->db->join('items i', 's.item_id = i.itm_id', 'left');
            $this->db->join('warehouses w', 's.warehouse_id = w.id', 'left');
            $this->db->where('s.warehouse_id', $warehouse_id);
            $query = $this->db->get();
        
            return $query->result();
        }

    
}
