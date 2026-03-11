<?php
class Items_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
    public function addItemPOST()    {        
        $data = array(
            'itm_code' => $this->input->post('code'),
            'itm_name' => $this->input->post('name'),
            'itm_category' => $this->input->post('category'),
            'itm_subcategory' => $this->input->post('subCategory'),
            'itm_brand' => $this->input->post('brand'),
            'itm_manufacture' => $this->input->post('manufacture'),
            'itm_description' => $this->input->post('description'),
            'itm_sellingprice' => $this->input->post('sellingprice'),
            'itm_reorderlevel'=>$this->input->post('reorderlevel'),
            'itm_quantity' => 0,
            'itm_uom' => $this->input->post('uom')
        );
        $this->db->insert('ezy_pos_items', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    // public function getAllItems(){
    //     $str ="select itm_id,itm_code,itm_name,itm_brand,itm_manufacture,itm_description,
    //         itm_sellingprice,itm_quantity,itm_reorderlevel,itm_uom,cat_name,sub_cat_name
    //     FROM ezy_pos_items
    //     LEFT JOIN ezy_pos_categories ON ezy_pos_categories.cat_id=ezy_pos_items.itm_category
    //     LEFT JOIN ezy_pos_subcategories ON ezy_pos_subcategories.sub_cat_id=ezy_pos_items.itm_subcategory
    //     where  itm_status= 1";        
    //     $query = $this->db->query($str);
    //     if($query->num_rows()>0){
    //         return $query->result();
    //     }
    //     else{
    //         return false;
    //     }
    // }
    public function getAllItems() {
        $str = "SELECT itm_id, itm_code, itm_name, itm_brand, itm_manufacture, itm_description,
                itm_sellingprice, itm_quantity, itm_reorderlevel, itm_uom, cat_name, sub_cat_name,
                wh_name 
                FROM ezy_pos_items
                LEFT JOIN ezy_pos_categories ON ezy_pos_categories.cat_id = ezy_pos_items.itm_category
                LEFT JOIN ezy_pos_subcategories ON ezy_pos_subcategories.sub_cat_id = ezy_pos_items.itm_subcategory
                LEFT JOIN ezy_pos_item_warehouse ON ezy_pos_item_warehouse.ware_item_id = ezy_pos_items.itm_id
                LEFT JOIN ezy_pos_warehouse ON ezy_pos_warehouse.wh_id = ezy_pos_item_warehouse.ware_warehouse_id
                WHERE itm_status = 1";        
        $query = $this->db->query($str);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    public function editItem(){        
        $id = $this->input->post('id');
        $this->db->where('itm_id', $id);
        $query = $this->db->get('ezy_pos_items');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function updateItem() {
        $itm_id = $this->input->post('hiddenID');  // Item ID
        $warehouseID = $this->input->post('Edit_warehouse');  // Warehouse ID from the form
        
        // Prepare the data to update the item in the `ezy_pos_items` table
        $updateData = array(
            'itm_code' => $this->input->post('Edit_code'),
            'itm_name' => $this->input->post('Edit_itmname'),
            'itm_category' => $this->input->post('Edit_category'),
            'itm_subcategory' => $this->input->post('Edit_subCategory'),
            'itm_brand' => $this->input->post('Edit_brand'),
            'itm_manufacture' => $this->input->post('Edit_manufacturer'),
            'itm_description' => $this->input->post('Edit_description'),
            'itm_sellingprice' => $this->input->post('Edit_sellingprice'),
            'itm_reorderlevel' => $this->input->post('Edit_reorderlevel'),
            'itm_uom' => $this->input->post('Edit_uom')
        );
    
        // Update the item data in the `ezy_pos_items` table
        $this->db->where('itm_id', $itm_id);
        $this->db->update('ezy_pos_items', $updateData);
        
        // Ensure we check if the warehouse relationship exists
        $this->db->where('ware_item_id', $itm_id);
        $query = $this->db->get('ezy_pos_item_warehouse');
        
        // If the warehouse is already assigned to the item, update it
        if ($query->num_rows() > 0) {
            // Warehouse exists, so update it
            $data = array('ware_warehouse_id' => $warehouseID);
            $this->db->where('ware_item_id', $itm_id);
            $this->db->update('ezy_pos_item_warehouse', $data);
        } else {
            // Warehouse not assigned, so insert a new relationship
            $data = array(
                'ware_item_id' => $itm_id,
                'ware_warehouse_id' => $warehouseID
            );
            $this->db->insert('ezy_pos_item_warehouse', $data);
        }
    }
    
    
    
    public function updateItemWarehouse($itmID, $warehouseID) {
        $data = array('ware_warehouse_id' => $warehouseID);
        $this->db->where('ware_item_id', $itmID);
        $this->db->update('ezy_pos_item_warehouse', $data);
        return $this->db->affected_rows() > 0;
    }
    
    // public function deleteItem(){
    //     $id = $this->input->post('id');
    //     $updateData = array(
    //         'itm_status' => 0
    //     );
    //     $this->db->where('itm_id', $id);
    //     $this->db->update('ezy_pos_items', $updateData);
    //     if($this->db->affected_rows()>0){
    //         return true;
    //     }
    //     else{
    //         return false;
    //     }
    // }


    public function deleteItem($id) {
        // Set the conditions for deletion
        $this->db->where('itm_id', $id);
        $this->db->delete('ezy_pos_items'); // Delete from items table
    
        // Check if the item was successfully deleted
        if ($this->db->affected_rows() > 0) {
            // Now, remove the item-warehouse relationship
            $this->db->where('ware_item_id', $id);
            $this->db->delete('ezy_pos_item_warehouse'); // Delete from the warehouse relationship table
    
            return true; // Return true if both deletions are successful
        }
    
        return false; // Return false if the item deletion failed
    }
    
    



    public function getItems(){ //get name& id only
        $this->db->select('i.itm_id,i.itm_code,i.itm_name,i.itm_sellingprice,s.stock_qty');
        $this->db->from('ezy_pos_items i');
        $this->db->join('ezy_pos_stock s','i.itm_id=s.stock_itm_id','left');
        $this->db->where('itm_status', 1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }
    public function getmoreInfo(){
        $id = $this->input->post('id');
        $this->db->select('itm_name,itm_brand,itm_manufacture,itm_description');
        $this->db->where('itm_id', $id);
        $query = $this->db->get('ezy_pos_items');
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function getItemName($itmid){
        $this->db->select('itm_name');
        $this->db->from('ezy_pos_items');
        $this->db->where('itm_status', 1);
        $this->db->where('itm_id', $itmid);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $result=$query->row();
            return $result->itm_name;
        }
        else{
            return false;
        }
    }
    

    //-------------------------------------------NEW FUNCTION-----------------------------------------------

    public function getStockQuantityByItemId($itemId = null) {
        // If no itemId is provided, return total stock quantity
        if ($itemId === null) {
            $this->db->select_sum('stock_qty');
            $query = $this->db->get('ezy_pos_stock');
            return $query->row()->stock_qty; // Return total stock quantity of all items
        }

        // If itemId is provided, return stock quantity for that item
        $this->db->select_sum('stock_qty');
        $this->db->where('stock_itm_id', $itemId);
        $query = $this->db->get('ezy_pos_stock');
        return $query->row()->stock_qty; // Return stock quantity for the specific item
    }


    public function getWarehouses() {
        // Query to get all warehouses
        $this->db->select('wh_id, wh_name');
        $this->db->from('ezy_pos_warehouse');
        $query = $this->db->get();
        
        return $query->result(); // Return the result as an array
    }
    
    public function saveItemWarehouse($itmID, $warehouseID) {
        // Insert the item-warehouse relationship into the ezy_pos_item_warehouse table
        $data = array(
            'ware_item_id' => $itmID,
            'ware_warehouse_id' => $warehouseID
        );
        
        $this->db->insert('ezy_pos_item_warehouse', $data);
        
        // Return true if insert is successful
        return $this->db->affected_rows() > 0;
    }


    // Get all items (itm_code, itm_name) for the bulk assign modal
public function getAllItemsForBulkAssign() {
    $this->db->select('itm_id, itm_code, itm_name');
    $this->db->from('ezy_pos_items');
    $this->db->where('itm_status', 1);
    $query = $this->db->get();
    
    return $query->result(); // Return the result as an array
}

// Bulk assign items to a warehouse
public function bulkAssignItemsToWarehouse($itemIds, $warehouseId) {
    $this->db->trans_start();

    foreach ($itemIds as $itemId) {
        // Check if the item-warehouse relationship exists
        $this->db->where('ware_item_id', $itemId);
        $query = $this->db->get('ezy_pos_item_warehouse');

        if ($query->num_rows() > 0) {
            // Update warehouse for the existing item
            $this->db->where('ware_item_id', $itemId);
            $this->db->update('ezy_pos_item_warehouse', ['ware_warehouse_id' => $warehouseId]);
        } else {
            // Insert a new item-warehouse relationship
            $this->db->insert('ezy_pos_item_warehouse', [
                'ware_item_id' => $itemId,
                'ware_warehouse_id' => $warehouseId
            ]);
        }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        return ['status' => 'error']; // If any query fails
    }
    return ['status' => 'success']; // If all queries succeed
}


    
    
}

