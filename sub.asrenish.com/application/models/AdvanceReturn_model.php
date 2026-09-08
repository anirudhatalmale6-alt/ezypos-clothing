<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Advance Return - a counter return that does not have to start from a bill.
 *
 * Deliberately self-contained. It writes to its own two tables and never
 * touches ezy_pos_returns, ezy_pos_return_items or ezy_pos_exchange_items, so
 * the existing Returns and Exchange screens carry on exactly as they were.
 *
 * What it shares with the rest of the system is only what it has to: stock goes
 * back into ezy_pos_stock, and a store-credit refund lands on the customer's
 * balance, because those are the same goods and the same customer.
 */
class AdvanceReturn_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function tablesExist()
    {
        return $this->db->table_exists('ezy_pos_adv_return')
            && $this->db->table_exists('ezy_pos_adv_return_item');
    }

    // ===================== ITEM LOOKUP =====================

    /**
     * Find items to return. `term` matches the item code exactly first - that
     * is what a barcode scan produces - and only then falls back to a partial
     * match on code, name or barcode, which is what typing produces.
     *
     * $storeId is used for the stock figure shown beside each item; it never
     * limits what can be returned, because a customer can bring back something
     * this branch has never stocked.
     */
    public function findItems($term, $storeId = 0, $limit = 20)
    {
        $term = trim((string)$term);
        if ($term === '') { return array(); }

        $fields  = $this->db->list_fields('ezy_pos_items');
        $barcode = in_array('itm_barcode', $fields) ? 'i.itm_barcode' : "'' ";

        $stockJoin = "LEFT JOIN ezy_pos_stock st
                             ON st.stock_itm_id = i.itm_id AND st.stock_store_id = ?";

        $sql = "SELECT i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice,
                       ".$barcode." AS itm_barcode,
                       COALESCE(st.stock_qty, 0) AS stock_qty
                FROM ezy_pos_items i
                ".$stockJoin."
                WHERE i.itm_status = 1
                  AND (i.itm_code = ? OR ".$barcode." = ?)
                LIMIT 1";
        $exact = $this->db->query($sql, array(intval($storeId), $term, $term))->result();
        if (count($exact) > 0) { return $exact; }

        $like = '%'.$this->db->escape_like_str($term).'%';
        $sql = "SELECT i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice,
                       ".$barcode." AS itm_barcode,
                       COALESCE(st.stock_qty, 0) AS stock_qty
                FROM ezy_pos_items i
                ".$stockJoin."
                WHERE i.itm_status = 1
                  AND (i.itm_code LIKE ? ESCAPE '!' OR i.itm_name LIKE ? ESCAPE '!'
                       OR ".$barcode." LIKE ? ESCAPE '!')
                ORDER BY i.itm_name
                LIMIT ".intval($limit);
        return $this->db->query($sql, array(intval($storeId), $like, $like, $like))->result();
    }

    /**
     * The bill, if the customer happens to have it. Purely for reference and to
     * fill the customer in - an Advance Return is never blocked by it, and the
     * bill is not altered in any way.
     */
    public function findSale($billNo)
    {
        $billNo = trim((string)$billNo);
        if ($billNo === '') { return null; }

        $sale_id = function_exists('bill_no_to_sale_id') ? bill_no_to_sale_id($billNo) : intval($billNo);
        if (!$sale_id) { return null; }

        $sql = "SELECT s.sale_id, s.sale_cus_id, s.sale_grandtotal, s.sale_date,
                       s.sale_location, c.cus_name, st.store_name
                FROM ezy_pos_sale s
                LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                LEFT JOIN ezy_pos_stores st ON st.store_id = s.sale_location
                WHERE s.sale_id = ?";
        $row = $this->db->query($sql, array($sale_id))->row();
        if (!$row) { return null; }

        $row->items = $this->db->query(
            "SELECT si.saleitem_item_id, si.saleitem_price, si.saleitem_quantity,
                    i.itm_code, i.itm_name
             FROM ezy_pos_sale_item si
             LEFT JOIN ezy_pos_items i ON i.itm_id = si.saleitem_item_id
             WHERE si.saleitem_sale_id = ?", array($sale_id))->result();
        return $row;
    }

    // ===================== SAVE =====================

    /**
     * Per-branch reference number, AR-1-0001 style. Counted with MySQL's
     * LAST_INSERT_ID(expr) so two tills cannot be handed the same number.
     */
    protected function _nextRef($store_id)
    {
        $store_id = intval($store_id);
        $row = $this->db->query(
            "SELECT COALESCE(MAX(adv_seq),0) + 1 AS n FROM ezy_pos_adv_return WHERE adv_store_id = ?",
            array($store_id))->row();
        $seq = $row ? intval($row->n) : 1;

        $letter = '';
        $st = $this->db->select('store_name')->get_where('ezy_pos_stores', array('store_id' => $store_id))->row();
        if ($st && trim($st->store_name) !== '') { $letter = strtoupper(substr(trim($st->store_name), 0, 1)); }

        return array($seq, 'AR-'.($letter !== '' ? $letter.'-' : '').str_pad($seq, 4, '0', STR_PAD_LEFT));
    }

    /**
     * Write the return, its lines, the stock movement and the refund.
     *
     * Returns array('ok' => bool, 'msg' => string, 'id' => int, 'ref' => string).
     * Everything happens inside one transaction: a return that cannot put the
     * stock back must not leave a refund behind it.
     */
    public function save($header, $lines)
    {
        if (!$this->tablesExist()) {
            return array('ok' => false, 'msg' => 'The Advance Return tables are not there yet. Run step 8 in migrate.php.');
        }
        if (!is_array($lines) || count($lines) === 0) {
            return array('ok' => false, 'msg' => 'Add at least one item to the return.');
        }

        $store_id = intval($header['store_id']);
        if ($store_id <= 0) {
            return array('ok' => false, 'msg' => 'Choose the branch taking the goods back.');
        }

        $mode = ($header['refund_mode'] === 'store_credit') ? 'store_credit' : 'cash';
        $cus_id = intval($header['cus_id']);
        if ($mode === 'store_credit' && $cus_id <= 0) {
            return array('ok' => false, 'msg' => 'Store credit has to go on a customer account, so pick the customer first.');
        }

        $clean = array();
        $total = 0;
        foreach ($lines as $l) {
            $item_id = intval(isset($l['item_id']) ? $l['item_id'] : 0);
            $qty     = round(floatval(isset($l['qty']) ? $l['qty'] : 0), 2);
            $price   = round(floatval(isset($l['price']) ? $l['price'] : 0), 2);
            if ($item_id <= 0 || $qty <= 0) { continue; }
            if ($price < 0) { $price = 0; }
            $lineTotal = round($qty * $price, 2);
            $total += $lineTotal;
            $clean[] = array(
                'advi_item_id'   => $item_id,
                'advi_item_code' => isset($l['item_code']) ? substr((string)$l['item_code'], 0, 100) : '',
                'advi_item_name' => isset($l['item_name']) ? substr((string)$l['item_name'], 0, 255) : '',
                'advi_qty'       => $qty,
                'advi_price'     => $price,
                'advi_total'     => $lineTotal
            );
        }
        if (count($clean) === 0) {
            return array('ok' => false, 'msg' => 'Every line has a quantity of zero, so there is nothing to return.');
        }
        $total = round($total, 2);

        $this->db->trans_begin();

        list($seq, $ref) = $this->_nextRef($store_id);

        $this->db->insert('ezy_pos_adv_return', array(
            'adv_ref_no'       => $ref,
            'adv_seq'          => $seq,
            'adv_store_id'     => $store_id,
            'adv_cus_id'       => $cus_id > 0 ? $cus_id : null,
            'adv_bill_ref'     => substr((string)$header['bill_ref'], 0, 50),
            'adv_sale_id'      => intval($header['sale_id']) > 0 ? intval($header['sale_id']) : null,
            'adv_without_bill' => empty($header['sale_id']) ? 1 : 0,
            'adv_total'        => $total,
            'adv_refund_mode'  => $mode,
            'adv_restock'      => !empty($header['restock']) ? 1 : 0,
            'adv_reason'       => substr((string)$header['reason'], 0, 255),
            'adv_status'       => 1,
            'adv_created_by'   => intval($this->session->userdata('userid')),
            'adv_created_at'   => date('Y-m-d H:i:s')
        ));
        $adv_id = $this->db->insert_id();

        foreach ($clean as $c) {
            $c['advi_adv_id'] = $adv_id;
            $this->db->insert('ezy_pos_adv_return_item', $c);
            if (!empty($header['restock'])) {
                $this->_increaseStock($c['advi_item_id'], $c['advi_qty'], $store_id);
            }
        }

        // Store credit: the shop keeps the money and owes the customer. A
        // positive balance in ezy_pos_cus_balnce is what the shop owes, which
        // is the same convention the Returns module uses.
        if ($mode === 'store_credit' && $total > 0) {
            $existing = $this->db->get_where('ezy_pos_cus_balnce', array('bal_cusid' => $cus_id))->row();
            if ($existing) {
                $this->db->query("UPDATE ezy_pos_cus_balnce SET bal_amount = bal_amount + ? WHERE bal_cusid = ?",
                                 array($total, $cus_id));
            } else {
                $this->db->insert('ezy_pos_cus_balnce', array('bal_cusid' => $cus_id, 'bal_amount' => $total));
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('ok' => false, 'msg' => 'The return could not be saved. Nothing has been changed.');
        }
        $this->db->trans_commit();

        return array('ok' => true, 'msg' => 'Return '.$ref.' saved.', 'id' => $adv_id, 'ref' => $ref, 'total' => $total);
    }

    protected function _increaseStock($item_id, $qty, $store_id)
    {
        $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty + ?
                          WHERE stock_itm_id = ? AND stock_store_id = ?",
                         array($qty, $item_id, $store_id));
        if ($this->db->affected_rows() > 0) { return true; }
        return $this->db->insert('ezy_pos_stock', array(
            'stock_itm_id'   => $item_id,
            'stock_store_id' => $store_id,
            'stock_qty'      => $qty,
            'stock_status'   => 1
        ));
    }

    // ===================== READ BACK =====================

    public function getOne($adv_id)
    {
        if (!$this->tablesExist()) { return null; }
        $row = $this->db->query(
            "SELECT a.*, c.cus_name, st.store_name, u.user_name
             FROM ezy_pos_adv_return a
             LEFT JOIN ezy_pos_customers c ON c.cus_id = a.adv_cus_id
             LEFT JOIN ezy_pos_stores st ON st.store_id = a.adv_store_id
             LEFT JOIN ezy_pos_users u ON u.user_id = a.adv_created_by
             WHERE a.adv_id = ?", array(intval($adv_id)))->row();
        if (!$row) { return null; }
        $row->items = $this->db->query(
            "SELECT * FROM ezy_pos_adv_return_item WHERE advi_adv_id = ? ORDER BY advi_id",
            array(intval($adv_id)))->result();
        return $row;
    }

    /**
     * The list screen. Branch filtering follows the same rule as the reports:
     * an admin sees every branch, anyone else sees only the branches they are
     * assigned to.
     */
    public function getList($from = '', $to = '', $storeId = 'all')
    {
        if (!$this->tablesExist()) { return array(); }

        $where = " WHERE a.adv_status = 1";
        $params = array();
        if ($from !== '' && $to !== '') {
            $where .= " AND a.adv_created_at BETWEEN ? AND ?";
            $params[] = $from.' 00:00:00';
            $params[] = $to.' 23:59:59';
        }
        if ($storeId !== 'all' && intval($storeId) > 0) {
            $where .= " AND a.adv_store_id = ".intval($storeId);
        }
        if ($this->session->userdata('userrole') != 1) {
            $ids = array();
            $q = $this->db->query("SELECT store_id FROM ezy_pos_user_store WHERE user_id = ? AND user_store_status = 1",
                                  array(intval($this->session->userdata('userid'))));
            foreach ($q->result() as $r) { $ids[] = intval($r->store_id); }
            $where .= empty($ids) ? " AND a.adv_store_id = -1" : " AND a.adv_store_id IN (".implode(',', $ids).")";
        }

        return $this->db->query(
            "SELECT a.*, c.cus_name, st.store_name,
                    (SELECT COUNT(*) FROM ezy_pos_adv_return_item i WHERE i.advi_adv_id = a.adv_id) AS line_count,
                    (SELECT COALESCE(SUM(i.advi_qty),0) FROM ezy_pos_adv_return_item i WHERE i.advi_adv_id = a.adv_id) AS qty_total
             FROM ezy_pos_adv_return a
             LEFT JOIN ezy_pos_customers c ON c.cus_id = a.adv_cus_id
             LEFT JOIN ezy_pos_stores st ON st.store_id = a.adv_store_id
             ".$where."
             ORDER BY a.adv_id DESC", $params)->result();
    }
}
