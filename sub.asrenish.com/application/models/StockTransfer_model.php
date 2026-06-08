<?php
class StockTransfer_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    // ===================== HELPER: TABLE CHECK =====================

    /**
     * Backward-compatible check: do the transfer tables exist?
     */
    public function tablesExist()
    {
        return $this->db->table_exists('ezy_pos_stock_transfer')
            && $this->db->table_exists('ezy_pos_stock_transfer_items');
    }

    // ===================== CREATE TRANSFER =====================

    /**
     * Insert a new transfer header row.
     * Stock at source store is decreased immediately (reserved).
     */
    public function createTransfer($from_store, $to_store, $notes, $created_by, $items)
    {
        if (!$this->tablesExist()) { return false; }

        // Insert header
        $data = array(
            'transfer_from_store' => $from_store,
            'transfer_to_store'   => $to_store,
            'transfer_status'     => 'Pending',
            'transfer_notes'      => $notes,
            'transfer_created_by' => $created_by,
            'transfer_created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('ezy_pos_stock_transfer', $data);
        $transfer_id = $this->db->insert_id();
        if (!$transfer_id) { return false; }

        $total = 0;
        // Insert items and decrease stock at source
        foreach ($items as $itm) {
            $line_total = floatval($itm['qty']) * floatval($itm['price']);
            $total += $line_total;
            $item_data = array(
                'sti_transfer_id' => $transfer_id,
                'sti_item_id'     => $itm['item_id'],
                'sti_item_name'   => $itm['item_name'],
                'sti_qty'         => $itm['qty'],
                'sti_price'       => $itm['price']
            );
            $this->db->insert('ezy_pos_stock_transfer_items', $item_data);

            // Decrease stock at source store immediately (reserved)
            $this->_decreaseStock($itm['item_id'], $itm['qty'], $from_store);
        }

        // Update total
        $this->db->where('transfer_id', $transfer_id);
        $this->db->update('ezy_pos_stock_transfer', array('transfer_total' => $total));

        return $transfer_id;
    }

    // ===================== UPDATE / EDIT TRANSFER =====================

    /**
     * Update a pending transfer (notes, items).
     * Only allowed when status is Pending.
     */
    public function updateTransfer($transfer_id, $notes, $items)
    {
        if (!$this->tablesExist()) { return false; }

        $transfer = $this->getTransferById($transfer_id);
        if (!$transfer || $transfer->transfer_status !== 'Pending') {
            return false;
        }

        // Restore stock for old items first
        $old_items = $this->getTransferItems($transfer_id);
        if ($old_items) {
            foreach ($old_items as $oi) {
                $this->_increaseStock($oi->sti_item_id, $oi->sti_qty, $transfer->transfer_from_store);
            }
        }

        // Delete old items
        $this->db->where('sti_transfer_id', $transfer_id);
        $this->db->delete('ezy_pos_stock_transfer_items');

        // Insert new items and decrease stock
        $total = 0;
        foreach ($items as $itm) {
            $line_total = floatval($itm['qty']) * floatval($itm['price']);
            $total += $line_total;
            $item_data = array(
                'sti_transfer_id' => $transfer_id,
                'sti_item_id'     => $itm['item_id'],
                'sti_item_name'   => $itm['item_name'],
                'sti_qty'         => $itm['qty'],
                'sti_price'       => $itm['price']
            );
            $this->db->insert('ezy_pos_stock_transfer_items', $item_data);
            $this->_decreaseStock($itm['item_id'], $itm['qty'], $transfer->transfer_from_store);
        }

        // Update header
        $this->db->where('transfer_id', $transfer_id);
        $this->db->update('ezy_pos_stock_transfer', array(
            'transfer_notes' => $notes,
            'transfer_total' => $total
        ));

        return true;
    }

    // ===================== ACCEPT / REJECT =====================

    /**
     * Accept a transfer: increase stock at destination store.
     */
    public function acceptTransfer($transfer_id, $accepted_by)
    {
        if (!$this->tablesExist()) { return false; }

        $transfer = $this->getTransferById($transfer_id);
        if (!$transfer || $transfer->transfer_status !== 'Pending') {
            return false;
        }

        // Increase stock at destination
        $items = $this->getTransferItems($transfer_id);
        if ($items) {
            foreach ($items as $itm) {
                $this->_increaseStock($itm->sti_item_id, $itm->sti_qty, $transfer->transfer_to_store);
            }
        }

        // Update status
        $this->db->where('transfer_id', $transfer_id);
        $this->db->update('ezy_pos_stock_transfer', array(
            'transfer_status'      => 'Accepted',
            'transfer_accepted_by' => $accepted_by,
            'transfer_accepted_at' => date('Y-m-d H:i:s')
        ));

        return true;
    }

    /**
     * Reject a transfer: return stock to source store.
     */
    public function rejectTransfer($transfer_id, $rejected_by)
    {
        if (!$this->tablesExist()) { return false; }

        $transfer = $this->getTransferById($transfer_id);
        if (!$transfer || $transfer->transfer_status !== 'Pending') {
            return false;
        }

        // Return stock to source store
        $items = $this->getTransferItems($transfer_id);
        if ($items) {
            foreach ($items as $itm) {
                $this->_increaseStock($itm->sti_item_id, $itm->sti_qty, $transfer->transfer_from_store);
            }
        }

        // Update status (reuse accepted_by/at columns for reject tracking)
        $this->db->where('transfer_id', $transfer_id);
        $this->db->update('ezy_pos_stock_transfer', array(
            'transfer_status'      => 'Rejected',
            'transfer_accepted_by' => $rejected_by,
            'transfer_accepted_at' => date('Y-m-d H:i:s')
        ));

        return true;
    }

    // ===================== FETCH METHODS =====================

    /**
     * Get a single transfer by ID with store names and creator name.
     */
    public function getTransferById($transfer_id)
    {
        if (!$this->tablesExist()) { return false; }

        $str = "SELECT t.*,
                       sf.store_name AS from_store_name,
                       st.store_name AS to_store_name,
                       u.user_name AS created_by_name,
                       ua.user_name AS accepted_by_name
                FROM ezy_pos_stock_transfer t
                LEFT JOIN ezy_pos_stores sf ON sf.store_id = t.transfer_from_store
                LEFT JOIN ezy_pos_stores st ON st.store_id = t.transfer_to_store
                LEFT JOIN ezy_pos_users u ON u.user_id = t.transfer_created_by
                LEFT JOIN ezy_pos_users ua ON ua.user_id = t.transfer_accepted_by
                WHERE t.transfer_id = ?";
        $query = $this->db->query($str, array($transfer_id));
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    /**
     * Get transfer items for a given transfer_id.
     */
    public function getTransferItems($transfer_id)
    {
        if (!$this->tablesExist()) { return false; }

        $str = "SELECT sti.*, i.itm_code, i.itm_name AS item_name_live
                FROM ezy_pos_stock_transfer_items sti
                LEFT JOIN ezy_pos_items i ON i.itm_id = sti.sti_item_id
                WHERE sti.sti_transfer_id = ?";
        $query = $this->db->query($str, array($transfer_id));
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    /**
     * Get all transfers (admin sees all, non-admin sees only their stores).
     */
    public function getAllTransfers($user_id = 0, $is_admin = false)
    {
        if (!$this->tablesExist()) { return false; }

        $str = "SELECT t.*,
                       sf.store_name AS from_store_name,
                       st.store_name AS to_store_name,
                       u.user_name AS created_by_name
                FROM ezy_pos_stock_transfer t
                LEFT JOIN ezy_pos_stores sf ON sf.store_id = t.transfer_from_store
                LEFT JOIN ezy_pos_stores st ON st.store_id = t.transfer_to_store
                LEFT JOIN ezy_pos_users u ON u.user_id = t.transfer_created_by";

        if (!$is_admin && $user_id > 0) {
            $str .= " WHERE (t.transfer_from_store IN (SELECT store_id FROM ezy_pos_user_store WHERE user_id = ? AND user_store_status = 1)
                      OR t.transfer_to_store IN (SELECT store_id FROM ezy_pos_user_store WHERE user_id = ? AND user_store_status = 1))";
            $str .= " ORDER BY t.transfer_id DESC";
            $query = $this->db->query($str, array($user_id, $user_id));
        } else {
            $str .= " ORDER BY t.transfer_id DESC";
            $query = $this->db->query($str);
        }

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    /**
     * Get items with stock for a specific store (for autocomplete).
     */
    public function getItemsWithStockByStore($store_id)
    {
        $str = "SELECT i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice,
                       IFNULL(s.stock_qty, 0) AS stock_qty
                FROM ezy_pos_items i
                LEFT JOIN ezy_pos_stock s ON s.stock_itm_id = i.itm_id AND s.stock_store_id = ?
                WHERE i.itm_status = 1
                ORDER BY i.itm_name ASC";
        $query = $this->db->query($str, array($store_id));
        return ($query->num_rows() > 0) ? $query->result() : array();
    }

    /**
     * Get user's store IDs (for permission checking).
     */
    public function getUserStoreIds($user_id)
    {
        $str = "SELECT store_id FROM ezy_pos_user_store WHERE user_id = ? AND user_store_status = 1";
        $query = $this->db->query($str, array($user_id));
        $ids = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $ids[] = $row->store_id;
            }
        }
        return $ids;
    }

    // ===================== PRIVATE STOCK HELPERS =====================

    /**
     * Decrease stock for an item at a specific store.
     * Creates the stock row if it doesn't exist.
     */
    private function _decreaseStock($item_id, $qty, $store_id)
    {
        // Check if stock row exists
        $check = $this->db->query(
            "SELECT stock_qty FROM ezy_pos_stock WHERE stock_itm_id = ? AND stock_store_id = ?",
            array($item_id, $store_id)
        );
        if ($check->num_rows() == 0) {
            $this->db->insert('ezy_pos_stock', array(
                'stock_itm_id'  => $item_id,
                'stock_store_id'=> $store_id,
                'stock_qty'     => 0,
                'stock_status'  => 1
            ));
        }
        $this->db->query(
            "UPDATE ezy_pos_stock SET stock_qty = stock_qty - ? WHERE stock_itm_id = ? AND stock_store_id = ?",
            array($qty, $item_id, $store_id)
        );
    }

    /**
     * Increase stock for an item at a specific store.
     * Creates the stock row if it doesn't exist.
     */
    private function _increaseStock($item_id, $qty, $store_id)
    {
        $check = $this->db->query(
            "SELECT stock_qty FROM ezy_pos_stock WHERE stock_itm_id = ? AND stock_store_id = ?",
            array($item_id, $store_id)
        );
        if ($check->num_rows() == 0) {
            $this->db->insert('ezy_pos_stock', array(
                'stock_itm_id'  => $item_id,
                'stock_store_id'=> $store_id,
                'stock_qty'     => $qty,
                'stock_status'  => 1
            ));
        } else {
            $this->db->query(
                "UPDATE ezy_pos_stock SET stock_qty = stock_qty + ? WHERE stock_itm_id = ? AND stock_store_id = ?",
                array($qty, $item_id, $store_id)
            );
        }
    }
}
