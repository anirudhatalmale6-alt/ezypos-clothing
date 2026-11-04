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

        // The status flips first, and only if it is still Pending: two clicks
        // (or two screens) at once used to both pass the check above and add
        // the stock twice. Now the second finds nothing to flip and stops.
        $this->db->trans_start();
        $this->db->where('transfer_id', $transfer_id)->where('transfer_status', 'Pending');
        $this->db->update('ezy_pos_stock_transfer', array(
            'transfer_status'      => 'Accepted',
            'transfer_accepted_by' => $accepted_by,
            'transfer_accepted_at' => date('Y-m-d H:i:s')
        ));
        if ($this->db->affected_rows() !== 1) { $this->db->trans_complete(); return false; }

        // Increase stock at destination
        $items = $this->getTransferItems($transfer_id);
        if ($items) {
            foreach ($items as $itm) {
                $this->_increaseStock($itm->sti_item_id, $itm->sti_qty, $transfer->transfer_to_store);
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
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

        // Status first, and only from Pending (see acceptTransfer).
        $this->db->trans_start();
        $this->db->where('transfer_id', $transfer_id)->where('transfer_status', 'Pending');
        $this->db->update('ezy_pos_stock_transfer', array(
            'transfer_status'      => 'Rejected',
            'transfer_accepted_by' => $rejected_by,
            'transfer_accepted_at' => date('Y-m-d H:i:s')
        ));
        if ($this->db->affected_rows() !== 1) { $this->db->trans_complete(); return false; }

        // Return stock to source store
        $items = $this->getTransferItems($transfer_id);
        if ($items) {
            foreach ($items as $itm) {
                $this->_increaseStock($itm->sti_item_id, $itm->sti_qty, $transfer->transfer_from_store);
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
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

    // ===================== STOCK TRANSFER AFTER GRN =====================
    //
    // A GRN's goods sent out to the branches as ordinary transfers, one per
    // branch, each marked with the GRN. The GRN's received quantity is never
    // touched; what it has allocated is counted from its transfers (rejected
    // ones do not count), so it cannot drift from what was actually sent.

    public function grnLinkReady()
    {
        return $this->tablesExist() && $this->db->field_exists('transfer_grn_id', 'ezy_pos_stock_transfer');
    }

    /** The GRN header, its lines with received / allocated / available, and the branches it can go to. */
    public function grnAllocation($grnId)
    {
        $grn = $this->db->select('g.grn_id, g.grn_code, g.grn_date, g.grn_location, s.sup_name, st.store_name')
                        ->from('ezy_pos_grns g')
                        ->join('ezy_pos_suppliers s', 's.sup_id = g.grn_supplier_id', 'left')
                        ->join('ezy_pos_stores st', 'st.store_id = g.grn_location', 'left')
                        ->where('g.grn_id', intval($grnId))->where('g.grn_status', 1)->get()->row();
        if (!$grn) { return null; }
        // Before migrate.php step 15 there is no GRN link on transfers yet:
        // show the page (it says so), with nothing allocated.
        $linked = $this->grnLinkReady();
        $allocatedSql = $linked
            ? "(SELECT IFNULL(SUM(ti.sti_qty), 0) FROM ezy_pos_stock_transfer_items ti
                     JOIN ezy_pos_stock_transfer t ON t.transfer_id = ti.sti_transfer_id
                     WHERE t.transfer_grn_id = gi.grnitm_grn_id AND t.transfer_status <> 'Rejected'
                       AND ti.sti_item_id = gi.grnitm_itemid)"
            : "0";
        $lines = $this->db->query(
            "SELECT gi.grnitm_itemid AS item_id, i.itm_code, i.itm_name, i.itm_sellingprice,
                    SUM(gi.grnitm_quantity) AS received,
                    ".$allocatedSql." AS allocated,
                    (SELECT IFNULL(SUM(k.stock_qty), 0) FROM ezy_pos_stock k
                     WHERE k.stock_itm_id = gi.grnitm_itemid AND k.stock_store_id = ?) AS in_store
             FROM ezy_pos_grn_item gi
             JOIN ezy_pos_items i ON i.itm_id = gi.grnitm_itemid
             WHERE gi.grnitm_grn_id = ?
             GROUP BY gi.grnitm_grn_id, gi.grnitm_itemid, i.itm_code, i.itm_name, i.itm_sellingprice
             ORDER BY MIN(gi.grnitm_id)", array($grn->grn_location, $grn->grn_id))->result();
        foreach ($lines as $l) {
            $l->received  = round(floatval($l->received), 2);
            $l->allocated = round(floatval($l->allocated), 2);
            $l->in_store  = round(floatval($l->in_store), 2);
            // Not more than the GRN still has unallocated, nor more than the
            // store it landed in still holds (some may already be sold there).
            $l->available = round(max(0, min($l->received - $l->allocated, $l->in_store)), 2);
        }
        $fields = $this->db->list_fields('ezy_pos_stores');
        $this->db->where('store_status', 1)->where('store_id !=', $grn->grn_location);
        if (in_array('store_is_warehouse', $fields)) { $this->db->where('store_is_warehouse', 0); }
        $branches = $this->db->order_by('store_id')->get('ezy_pos_stores')->result();
        $transfers = !$linked ? array() : $this->db->query(
            "SELECT t.transfer_id, t.transfer_status, t.transfer_created_at, st.store_name AS to_store_name,
                    (SELECT SUM(ti.sti_qty) FROM ezy_pos_stock_transfer_items ti WHERE ti.sti_transfer_id = t.transfer_id) AS qty
             FROM ezy_pos_stock_transfer t LEFT JOIN ezy_pos_stores st ON st.store_id = t.transfer_to_store
             WHERE t.transfer_grn_id = ? ORDER BY t.transfer_id", array($grn->grn_id))->result();
        return array('grn' => $grn, 'lines' => $lines, 'branches' => $branches, 'transfers' => $transfers);
    }

    /**
     * Send a GRN's goods to the branches. $alloc = array(store_id => array(item_id => qty)).
     * One transaction: the GRN row is locked first, so two people allocating
     * the same GRN at once are served one after the other and the second sees
     * what the first took. Returns array(ok, message, transfer ids).
     */
    public function allocateGrn($grnId, $alloc, $userId, $notes)
    {
        if (!$this->grnLinkReady()) { return array(false, 'The database needs updating first: run step 15 in migrate.php.', array()); }
        $this->db->trans_start();
        $this->db->query("SELECT grn_id FROM ezy_pos_grns WHERE grn_id = ? FOR UPDATE", array(intval($grnId)));
        $a = $this->grnAllocation($grnId);
        if (!$a) { $this->db->trans_complete(); return array(false, 'GRN not found.', array()); }

        $lines = array();    foreach ($a['lines'] as $l) { $lines[(int)$l->item_id] = $l; }
        $branches = array(); foreach ($a['branches'] as $b) { $branches[(int)$b->store_id] = $b->store_name; }
        $totals = array(); $clean = array();
        foreach ((array)$alloc as $storeId => $items) {
            $storeId = intval($storeId);
            foreach ((array)$items as $itemId => $qty) {
                $itemId = intval($itemId); $qty = round(floatval($qty), 2);
                if ($qty <= 0) { continue; }
                if (!isset($branches[$storeId])) { $this->db->trans_complete(); return array(false, 'One of the branches cannot receive from this GRN.', array()); }
                if (!isset($lines[$itemId]))     { $this->db->trans_complete(); return array(false, 'An item that is not on this GRN was sent.', array()); }
                $clean[$storeId][$itemId] = $qty;
                $totals[$itemId] = (isset($totals[$itemId]) ? $totals[$itemId] : 0) + $qty;
            }
        }
        if (!$clean) { $this->db->trans_complete(); return array(false, 'Enter a quantity for at least one branch.', array()); }
        $problems = array();
        foreach ($totals as $itemId => $t) {
            if ($t > $lines[$itemId]->available + 0.0001) {
                $problems[] = $lines[$itemId]->itm_code.' - '.(0 + $t).' allocated, but only '.(0 + $lines[$itemId]->available).' is available on this GRN.';
            }
        }
        if ($problems) { $this->db->trans_complete(); return array(false, implode("\n", $problems), array()); }

        $from = intval($a['grn']->grn_location);
        $label = 'From GRN '.($a['grn']->grn_code ? $a['grn']->grn_code : '#'.$a['grn']->grn_id);
        $ids = array();
        foreach ($clean as $storeId => $items) {
            $this->db->insert('ezy_pos_stock_transfer', array(
                'transfer_from_store' => $from, 'transfer_to_store' => $storeId, 'transfer_status' => 'Pending',
                'transfer_notes' => trim($label.'. '.$notes), 'transfer_created_by' => intval($userId),
                'transfer_created_at' => date('Y-m-d H:i:s'), 'transfer_grn_id' => intval($a['grn']->grn_id)));
            $tid = $this->db->insert_id();
            $total = 0;
            foreach ($items as $itemId => $qty) {
                $price = floatval($lines[$itemId]->itm_sellingprice);
                $this->db->insert('ezy_pos_stock_transfer_items', array(
                    'sti_transfer_id' => $tid, 'sti_item_id' => $itemId, 'sti_item_name' => $lines[$itemId]->itm_name,
                    'sti_qty' => $qty, 'sti_price' => $price));
                $this->_decreaseStock($itemId, $qty, $from);
                $total += $qty * $price;
            }
            $this->db->where('transfer_id', $tid)->update('ezy_pos_stock_transfer', array('transfer_total' => round($total, 2)));
            $ids[] = $tid;
        }
        $this->db->trans_complete();
        if (!$this->db->trans_status()) { return array(false, 'The database refused the save; nothing was changed.', array()); }
        return array(true, 'Saved', $ids);
    }

    /** Stock at a store, for the server-side check on a manual transfer. */
    public function stockAt($item_id, $store_id)
    {
        $r = $this->db->query("SELECT IFNULL(SUM(stock_qty), 0) AS q FROM ezy_pos_stock WHERE stock_itm_id = ? AND stock_store_id = ?",
                              array($item_id, $store_id))->row();
        return $r ? floatval($r->q) : 0;
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
