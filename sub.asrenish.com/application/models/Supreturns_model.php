<?php
class SupReturns_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
    public function getGrnItems(){
        $grnid = $this->input->post('grnid');
        $str="SELECT grnitm_price,grnitm_total,grnitm_discount,grnitm_createdat,itm_id,itm_code,itm_name,cur_currentQTY
        FROM ezy_pos_grn_item
        INNER JOIN ezy_pos_currentqtywithgrn ON ezy_pos_currentqtywithgrn.cur_itmID=ezy_pos_grn_item.grnitm_itemid
        INNER JOIN ezy_pos_items ON ezy_pos_items.itm_id=ezy_pos_grn_item.grnitm_itemid
        WHERE grnitm_grn_id='".$grnid."'
        AND cur_grnID='".$grnid."'";
        $query = $this->db->query($str);
        if($query->num_rows()>0){
            return $query->result();           
        }
        else{
            return false;
        }
    }
    public function getGrnDetails(){
        $grnid = $this->input->post('grnid');
        $this->db->select('grn_supplier_id, grn_grandtotal, grn_discount, grn_location');
        $this->db->from('ezy_pos_grns');
        $this->db->where('grn_id', $grnid);
        $this->db->where('grn_status', 1);
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row();
        }
        else{
            return false;
        }
    }
    public function addReturn(){
        if(isset($_SESSION['userid'])){
            $userid = $_SESSION['userid'];
        }
        $data = array(
            'suprtrn_supID'=>$this->input->post('supID'),            
            'suprtrn_totalRtrn'=>$this->input->post('supRtrnTotal'),
            'suprtrn_createdby'=>$userid,
            'suprtrn_status'=>1
        );
        $this->db->insert('ezy_pos_sup_return', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function addReturnItems(){
        $data=array(
            'supRtrn_itm_supRtrnID'=>$this->input->post('rID'),
            'supRtrn_itm_itmID'=>$this->input->post('rItmID'),
            'supRtrn_itm_rQty'=>$this->input->post('rQty'),
            'supRtrn_itm_rAmount'=>$this->input->post('rAmount')
        );
        return $this->db->insert('ezy_pos_sup_return_item', $data);
    }

    /* ==================================================================
     * Supplier Returns page (list, new, edit).
     *
     * The old popup in the menu asked for the GRN's internal database id -
     * a number nobody sees - so typing the GRN number from the paperwork
     * found nothing and nothing could be saved. A return was also never
     * tied to its GRN, so it could not be opened again.
     *
     * Here the GRN is found by its code or its number, every return keeps
     * its GRN and store, and the whole save - lines, stock, GRN batch and
     * stock log - happens on the server in one transaction: it all goes in
     * or none of it does. Prices come from the GRN, never from the browser.
     * ================================================================== */

    /** Does the database have the v21 columns yet (migrate.php step 15)? */
    public function linkedReady()
    {
        return $this->db->field_exists('suprtrn_grnID', 'ezy_pos_sup_return');
    }

    /** A GRN by its number (id) or its code - whichever the user typed. */
    public function findGrn($ref)
    {
        $ref = trim((string)$ref);
        if ($ref === '') { return null; }
        $this->db->select('g.grn_id, g.grn_code, g.grn_date, g.grn_location, g.grn_supplier_id,
                           g.grn_subtotal, g.grn_grandtotal, s.sup_name, st.store_name')
                 ->from('ezy_pos_grns g')
                 ->join('ezy_pos_suppliers s', 's.sup_id = g.grn_supplier_id', 'left')
                 ->join('ezy_pos_stores st', 'st.store_id = g.grn_location', 'left')
                 ->where('g.grn_status', 1);
        if (ctype_digit($ref)) {
            $this->db->group_start()->where('g.grn_id', intval($ref))->or_where('g.grn_code', $ref)->group_end();
        } else {
            $this->db->where('g.grn_code', $ref);
        }
        return $this->db->order_by('g.grn_id', 'desc')->limit(1)->get()->row();
    }

    /**
     * The GRN's lines with what can still go back.
     *   unit_cost  - what one unit actually cost: the line total over its
     *                quantity, less its share of any discount on the GRN.
     *   available  - what is left of that GRN batch (not yet sold or
     *                returned), no more than the store still holds, plus
     *                what this return already holds when it is being edited.
     */
    public function grnLines($grn, $exceptReturnId = 0)
    {
        $factor = (floatval($grn->grn_subtotal) > 0) ? floatval($grn->grn_grandtotal) / floatval($grn->grn_subtotal) : 1;
        $rows = $this->db->query(
            "SELECT gi.grnitm_itemid AS item_id, i.itm_code, i.itm_name,
                    SUM(gi.grnitm_quantity) AS received, SUM(gi.grnitm_total) AS line_total
             FROM ezy_pos_grn_item gi
             JOIN ezy_pos_items i ON i.itm_id = gi.grnitm_itemid
             WHERE gi.grnitm_grn_id = ?
             GROUP BY gi.grnitm_itemid, i.itm_code, i.itm_name
             ORDER BY MIN(gi.grnitm_id)", array($grn->grn_id))->result();
        $mine = array();
        if ($exceptReturnId) {
            foreach ($this->returnLines($exceptReturnId) as $l) { $mine[$l->supRtrn_itm_itmID] = floatval($l->supRtrn_itm_rQty); }
        }
        $out = array();
        foreach ($rows as $r) {
            $left = $this->db->query(
                "SELECT IFNULL(SUM(cur_currentQTY), 0) AS q FROM ezy_pos_currentqtywithgrn
                 WHERE cur_grnID = ? AND cur_itmID = ?", array($grn->grn_id, $r->item_id))->row()->q;
            // Goods can only go back if the store still holds them: a GRN batch
            // can say 10 are left while the pieces were moved to a branch.
            $inStore = $this->db->query(
                "SELECT IFNULL(SUM(stock_qty), 0) AS q FROM ezy_pos_stock
                 WHERE stock_itm_id = ? AND stock_store_id = ?", array($r->item_id, $grn->grn_location))->row()->q;
            $left = min(floatval($left), max(0, floatval($inStore)));
            $qty = floatval($r->received);
            $out[] = array(
                'item_id'   => (int)$r->item_id,
                'code'      => $r->itm_code,
                'name'      => $r->itm_name,
                'received'  => $qty,
                'unit_cost' => $qty > 0 ? round(floatval($r->line_total) / $qty * $factor, 2) : 0,
                'available' => max(0, floatval($left)) + (isset($mine[$r->item_id]) ? $mine[$r->item_id] : 0),
                'returning' => isset($mine[$r->item_id]) ? $mine[$r->item_id] : 0
            );
        }
        return $out;
    }

    public function getReturn($id)
    {
        return $this->db->select('r.*, s.sup_name, g.grn_code, st.store_name, u.user_name')
                        ->from('ezy_pos_sup_return r')
                        ->join('ezy_pos_suppliers s', 's.sup_id = r.suprtrn_supID', 'left')
                        ->join('ezy_pos_grns g', 'g.grn_id = r.suprtrn_grnID', 'left')
                        ->join('ezy_pos_stores st', 'st.store_id = r.suprtrn_store_id', 'left')
                        ->join('ezy_pos_users u', 'u.user_id = r.suprtrn_createdby', 'left')
                        ->where('r.suprtrn_id', intval($id))->get()->row();
    }

    public function returnLines($id)
    {
        return $this->db->where('supRtrn_itm_supRtrnID', intval($id))->get('ezy_pos_sup_return_item')->result();
    }

    public function listReturns()
    {
        $linked = $this->linkedReady();
        $sql = "SELECT r.suprtrn_id, r.suprtrn_totalRtrn, r.suprtrn_createdat, s.sup_name, u.user_name,
                       ".($linked ? "r.suprtrn_grnID, g.grn_code, st.store_name, r.suprtrn_note" : "0 AS suprtrn_grnID, NULL AS grn_code, NULL AS store_name, NULL AS suprtrn_note").",
                       (SELECT COUNT(*) FROM ezy_pos_sup_return_item it WHERE it.supRtrn_itm_supRtrnID = r.suprtrn_id) AS lines_count,
                       (SELECT IFNULL(SUM(it.supRtrn_itm_rQty),0) FROM ezy_pos_sup_return_item it WHERE it.supRtrn_itm_supRtrnID = r.suprtrn_id) AS qty
                FROM ezy_pos_sup_return r
                LEFT JOIN ezy_pos_suppliers s ON s.sup_id = r.suprtrn_supID
                LEFT JOIN ezy_pos_users u ON u.user_id = r.suprtrn_createdby
                ".($linked ? "LEFT JOIN ezy_pos_grns g ON g.grn_id = r.suprtrn_grnID
                LEFT JOIN ezy_pos_stores st ON st.store_id = r.suprtrn_store_id" : "")."
                WHERE r.suprtrn_status = 1
                ORDER BY r.suprtrn_id DESC";
        return $this->db->query($sql)->result();
    }

    /**
     * Save a new return, or replace the lines of an existing one.
     * $lines = array(item_id => qty). Returns array(ok, msg, id).
     */
    public function saveReturn($returnId, $grnRef, $lines, $note, $userId)
    {
        if ( ! $this->linkedReady()) {
            return array(false, 'The database needs updating first: run step 15 in migrate.php.', 0);
        }
        $returnId = intval($returnId);
        $old = null;
        if ($returnId) {
            $old = $this->getReturn($returnId);
            if ( ! $old || ! $old->suprtrn_status) { return array(false, 'That return was not found.', 0); }
            if ( ! intval($old->suprtrn_grnID)) {
                return array(false, 'This return was made with the old screen and is not linked to a GRN, so it cannot be edited.', 0);
            }
            $grnRef = $old->suprtrn_grnID;   // a return stays on its own GRN
        }
        $grn = $this->findGrn($grnRef);
        if ( ! $grn) { return array(false, 'No GRN with that number or code.', 0); }
        $store = intval($grn->grn_location);

        // Check every line before touching anything.
        $avail = array();
        foreach ($this->grnLines($grn, $returnId) as $l) { $avail[$l['item_id']] = $l; }
        $clean = array(); $problems = array();
        foreach ($lines as $itemId => $qty) {
            $itemId = intval($itemId); $qty = round(floatval($qty), 2);
            if ($qty <= 0) { continue; }
            if ( ! isset($avail[$itemId])) { $problems[] = 'An item that is not on this GRN was sent.'; continue; }
            if ($qty > $avail[$itemId]['available'] + 0.0001) {
                $problems[] = $avail[$itemId]['code'].' - you entered '.(0 + $qty).', but only '.(0 + $avail[$itemId]['available'])
                            .' of this GRN can go back (what is left of it, and no more than the store holds).';
                continue;
            }
            $clean[$itemId] = $qty;
        }
        if ($problems) { return array(false, implode("\n", $problems), 0); }
        if ( ! $clean) { return array(false, 'Enter a return quantity for at least one item.', 0); }

        $this->db->trans_start();

        if ($old) {
            // Put the old lines back first, then apply the new ones.
            foreach ($this->returnLines($returnId) as $l) {
                $this->_moveStock($l->supRtrn_itm_itmID, $store, $grn->grn_id, +floatval($l->supRtrn_itm_rQty));
            }
            $this->db->where('supRtrn_itm_supRtrnID', $returnId)->delete('ezy_pos_sup_return_item');
            $this->db->where('stocklog_return_sup_retrnID', $returnId)->delete('ezy_pos_stock_log');
        } else {
            $this->db->insert('ezy_pos_sup_return', array(
                'suprtrn_supID'     => intval($grn->grn_supplier_id),
                'suprtrn_totalRtrn' => 0,
                'suprtrn_createdby' => intval($userId),
                'suprtrn_status'    => 1,
                'suprtrn_grnID'     => intval($grn->grn_id),
                'suprtrn_store_id'  => $store
            ));
            $returnId = $this->db->insert_id();
        }

        $total = 0;
        foreach ($clean as $itemId => $qty) {
            $amount = round($qty * $avail[$itemId]['unit_cost'], 2);
            $total += $amount;
            $this->db->insert('ezy_pos_sup_return_item', array(
                'supRtrn_itm_supRtrnID' => $returnId,
                'supRtrn_itm_itmID'     => $itemId,
                'supRtrn_itm_rQty'      => $qty,
                'supRtrn_itm_rAmount'   => $amount
            ));
            $this->_moveStock($itemId, $store, $grn->grn_id, -$qty);
            $this->db->insert('ezy_pos_stock_log', array(
                'stocklog_itmid' => $itemId, 'stocklog_store_id' => $store, 'stocklog_qty' => $qty,
                'stocklog_grnID' => 0, 'stocklog_saleID' => 0,
                'stocklog_return_sup_retrnID' => $returnId, 'stocklog_return_supID' => intval($grn->grn_supplier_id),
                'stocklog_return_cus_retrnID' => 0, 'stocklog_return_cusID' => 0, 'stocklog_status' => 1
            ));
        }
        $this->db->where('suprtrn_id', $returnId)->update('ezy_pos_sup_return', array(
            'suprtrn_totalRtrn' => round($total, 2),
            'suprtrn_note'      => substr((string)$note, 0, 255)
        ));

        $this->db->trans_complete();
        if ( ! $this->db->trans_status()) { return array(false, 'The database refused the save; nothing was changed.', 0); }
        return array(true, 'Saved', $returnId);
    }

    /** Stock at the GRN's store and the GRN's own batch move together. */
    private function _moveStock($itemId, $store, $grnId, $delta)
    {
        $exists = $this->db->where('stock_itm_id', $itemId)->where('stock_store_id', $store)->count_all_results('ezy_pos_stock');
        if ( ! $exists) {
            $this->db->insert('ezy_pos_stock', array('stock_itm_id' => $itemId, 'stock_store_id' => $store, 'stock_qty' => 0, 'stock_status' => 1));
        }
        $this->db->set('stock_qty', 'stock_qty + '.floatval($delta), false)
                 ->where('stock_itm_id', $itemId)->where('stock_store_id', $store)->update('ezy_pos_stock');
        // The batch: take from (or give back to) this GRN's row for the item.
        $batch = $this->db->where('cur_grnID', $grnId)->where('cur_itmID', $itemId)
                          ->order_by('cur_id', 'asc')->limit(1)->get('ezy_pos_currentqtywithgrn')->row();
        if ($batch) {
            $this->db->set('cur_currentQTY', 'cur_currentQTY + '.floatval($delta), false)
                     ->where('cur_id', $batch->cur_id)->update('ezy_pos_currentqtywithgrn');
        }
    }
}
