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

    /** Whether v16 has been run - goods going out, and payments for them. */
    public function exchangeReady()
    {
        return $this->tablesExist()
            && $this->db->table_exists('ezy_pos_adv_exchange_item')
            && $this->db->table_exists('ezy_pos_adv_payment')
            && in_array('adv_exchange_total', $this->db->list_fields('ezy_pos_adv_return'));
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
    /**
     * Turn a posted line array into clean rows, and total them.
     * `$map` names the columns, so the same rule serves both the goods coming
     * back and the goods going out - one place to get the rounding right.
     */
    protected function _cleanLines($lines, $map)
    {
        $out = array('rows' => array(), 'total' => 0);
        if (!is_array($lines)) { return $out; }
        foreach ($lines as $l) {
            $item_id = intval(isset($l['item_id']) ? $l['item_id'] : 0);
            $qty     = round(floatval(isset($l['qty']) ? $l['qty'] : 0), 2);
            $price   = round(floatval(isset($l['price']) ? $l['price'] : 0), 2);
            if ($item_id <= 0 || $qty <= 0) { continue; }
            if ($price < 0) { $price = 0; }
            $lineTotal = round($qty * $price, 2);
            $out['total'] += $lineTotal;
            $out['rows'][] = array(
                $map['item'] => $item_id,
                $map['code'] => isset($l['item_code']) ? substr((string)$l['item_code'], 0, 100) : '',
                $map['name'] => isset($l['item_name']) ? substr((string)$l['item_name'], 0, 255) : '',
                $map['qty']  => $qty,
                $map['price']=> $price,
                $map['total']=> $lineTotal
            );
        }
        $out['total'] = round($out['total'], 2);
        return $out;
    }

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

    /**
     * Save an Advance Exchange: goods in, goods out, and the difference settled.
     *
     * Everything is in one transaction. An exchange that cannot move the stock
     * must not leave a payment behind it, and a payment that cannot be taken
     * must not leave the goods gone.
     *
     * $payments is one row per method, exactly like a sale can be part cash and
     * part card. A gift voucher is a payment method here too - the card is
     * redeemed for the amount used, no more.
     */
    public function saveExchange($header, $returnLines, $exchangeLines, $payments)
    {
        if (!$this->exchangeReady()) {
            return array('ok' => false, 'msg' => 'The Advance Exchange tables are not there yet. Run step 9 in migrate.php.');
        }

        $store_id = intval($header['store_id']);
        if ($store_id <= 0) {
            return array('ok' => false, 'msg' => 'Choose the branch first.');
        }

        $ret = $this->_cleanLines($returnLines, array(
            'item'=>'advi_item_id','code'=>'advi_item_code','name'=>'advi_item_name',
            'qty'=>'advi_qty','price'=>'advi_price','total'=>'advi_total'));
        $exc = $this->_cleanLines($exchangeLines, array(
            'item'=>'adve_item_id','code'=>'adve_item_code','name'=>'adve_item_name',
            'qty'=>'adve_qty','price'=>'adve_price','total'=>'adve_total'));

        if (count($ret['rows']) === 0 && count($exc['rows']) === 0) {
            return array('ok' => false, 'msg' => 'Scan what is coming back, what is going out, or both.');
        }

        // Positive: the customer owes us. Negative: we owe the customer.
        $net = round($exc['total'] - $ret['total'], 2);

        $mode   = (isset($header['refund_mode']) && $header['refund_mode'] === 'store_credit') ? 'store_credit' : 'cash';
        $cus_id = intval($header['cus_id']);
        if ($net < -0.004 && $mode === 'store_credit' && $cus_id <= 0) {
            return array('ok' => false, 'msg' => 'Store credit has to go on a customer account, so pick the customer first.');
        }

        // --- the money ---
        $pays = array();
        $paidIn = 0; $paidOut = 0;
        if (is_array($payments)) {
            foreach ($payments as $p) {
                $amt = round(floatval(isset($p['amount']) ? $p['amount'] : 0), 2);
                if ($amt <= 0) { continue; }
                $method = trim((string)(isset($p['method']) ? $p['method'] : 'Cash'));
                if ($method === '') { $method = 'Cash'; }
                $dir = (isset($p['direction']) && $p['direction'] === 'out') ? 'out' : 'in';
                // Anything that is not cash carries a reference - the card
                // machine slip, the cheque number, the voucher card. Same rule
                // as the Sales window, and the reason the Cash Flow report can
                // be tied back to a piece of paper.
                $ref = trim((string)(isset($p['reference']) ? $p['reference'] : ''));
                if (strcasecmp($method, 'Cash') !== 0 && $ref === '') {
                    return array('ok' => false,
                                 'msg' => $method.' needs its reference number. Nothing has been saved.');
                }
                $pays[] = array(
                    'advp_method'    => substr($method, 0, 50),
                    'advp_reference' => substr($ref, 0, 100),
                    'advp_amount'    => $amt,
                    'advp_direction' => $dir,
                    'advp_gc_id'     => (isset($p['gc_id']) && intval($p['gc_id']) > 0) ? intval($p['gc_id']) : null
                );
                if ($dir === 'in') { $paidIn += $amt; } else { $paidOut += $amt; }
            }
        }
        $paidIn = round($paidIn, 2); $paidOut = round($paidOut, 2);

        // The customer owes money: it has to be collected in full here, or the
        // shop hands the goods over and the debt exists nowhere.
        if ($net > 0.004 && abs($paidIn - $net) > 0.05) {
            return array('ok' => false,
                         'msg' => 'The customer owes '.number_format($net, 2).' and '.number_format($paidIn, 2)
                                . ' has been entered. Collect the exact difference. Nothing has been saved.');
        }
        // We owe the customer: either pay it out here, or put it on the account.
        if ($net < -0.004 && $mode === 'cash' && abs($paidOut - abs($net)) > 0.05) {
            return array('ok' => false,
                         'msg' => 'The refund due is '.number_format(abs($net), 2).' and '.number_format($paidOut, 2)
                                . ' has been entered. Nothing has been saved.');
        }

        $this->db->trans_begin();

        list($seq, $ref) = $this->_nextRef($store_id);
        $type = (count($exc['rows']) > 0) ? 'exchange' : 'return';

        $this->db->insert('ezy_pos_adv_return', array(
            'adv_ref_no'         => $ref,
            'adv_seq'            => $seq,
            'adv_store_id'       => $store_id,
            'adv_cus_id'         => $cus_id > 0 ? $cus_id : null,
            'adv_bill_ref'       => substr((string)$header['bill_ref'], 0, 50),
            'adv_sale_id'        => intval($header['sale_id']) > 0 ? intval($header['sale_id']) : null,
            'adv_without_bill'   => empty($header['sale_id']) ? 1 : 0,
            'adv_total'          => $ret['total'],
            'adv_exchange_total' => $exc['total'],
            'adv_net_amount'     => $net,
            'adv_type'           => $type,
            'adv_refund_mode'    => $mode,
            'adv_restock'        => !empty($header['restock']) ? 1 : 0,
            'adv_reason'         => substr((string)$header['reason'], 0, 255),
            'adv_status'         => 1,
            'adv_created_by'     => intval($this->session->userdata('userid')),
            'adv_created_at'     => date('Y-m-d H:i:s')
        ));
        $adv_id = $this->db->insert_id();
        if (!$adv_id) {
            $this->db->trans_rollback();
            return array('ok' => false, 'msg' => 'Could not save. Nothing has been changed.');
        }

        // goods coming back
        foreach ($ret['rows'] as $r) {
            $r['advi_adv_id'] = $adv_id;
            $this->db->insert('ezy_pos_adv_return_item', $r);
            if (!empty($header['restock'])) {
                $this->_increaseStock($r['advi_item_id'], $r['advi_qty'], $store_id);
            }
        }
        // goods going out
        foreach ($exc['rows'] as $r) {
            $r['adve_adv_id'] = $adv_id;
            $this->db->insert('ezy_pos_adv_exchange_item', $r);
            $this->_decreaseStock($r['adve_item_id'], $r['adve_qty'], $store_id);
        }
        // how the difference was settled
        $now = date('Y-m-d H:i:s');
        foreach ($pays as $p) {
            $p['advp_adv_id'] = $adv_id;
            $p['advp_created_at'] = $now;
            $this->db->insert('ezy_pos_adv_payment', $p);

            // A voucher used as payment is spent for the amount used, so it
            // cannot be spent again.
            if ($p['advp_gc_id'] && $this->db->table_exists('ezy_pos_gift_cards')) {
                $this->_redeemVoucher($p['advp_gc_id'], $p['advp_amount']);
            }
        }

        // A refund left on the account rather than handed over in cash.
        if ($net < -0.004 && $mode === 'store_credit' && $cus_id > 0) {
            $owed = abs($net);
            $existing = $this->db->get_where('ezy_pos_cus_balnce', array('bal_cusid' => $cus_id))->row();
            if ($existing) {
                $this->db->query("UPDATE ezy_pos_cus_balnce SET bal_amount = bal_amount + ? WHERE bal_cusid = ?",
                                 array($owed, $cus_id));
            } else {
                $this->db->insert('ezy_pos_cus_balnce', array('bal_cusid' => $cus_id, 'bal_amount' => $owed));
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('ok' => false, 'msg' => 'Could not save it all, so nothing has been saved. Try again.');
        }
        $this->db->trans_commit();

        return array('ok' => true, 'msg' => $ref.' saved.', 'id' => $adv_id, 'ref' => $ref,
                     'type' => $type, 'return_total' => $ret['total'],
                     'exchange_total' => $exc['total'], 'net' => $net);
    }

    /**
     * Spend a gift card by the amount used on this exchange.
     *
     * Goes through the same model the Sales window uses, so a card behaves
     * identically wherever it is spent - including the shop's own single-use
     * rule, where a category marked one-off is finished the first time it is
     * used and any unspent value on it is forfeited.
     */
    protected function _redeemVoucher($gc_id, $amount)
    {
        $this->load->model('GiftVoucher_model');
        return $this->GiftVoucher_model->redeemCard(
            intval($gc_id), null, round(floatval($amount), 2),
            intval($this->session->userdata('userid')));
    }

    /** Left in place only so the old direct writer is not called by mistake. */
    protected function _redeemVoucherRaw($gc_id, $amount)
    {
        $card = $this->db->get_where('ezy_pos_gift_cards', array('gc_id' => intval($gc_id)))->row();
        if (!$card) { return false; }
        $use = min(round(floatval($amount), 2), round(floatval($card->gc_remaining_value), 2));
        if ($use <= 0) { return false; }

        $left = round(floatval($card->gc_remaining_value) - $use, 2);
        $this->db->where('gc_id', intval($gc_id))->update('ezy_pos_gift_cards', array(
            'gc_remaining_value' => $left,
            'gc_status' => ($left <= 0.004 ? 'Redeemed' : $card->gc_status)
        ));
        if ($this->db->table_exists('ezy_pos_voucher_redemptions')) {
            // No sale id - this was not a sale. The exchange it paid for is on
            // the payment row that points at this card.
            $this->db->insert('ezy_pos_voucher_redemptions', array(
                'vr_gc_id'       => intval($gc_id),
                'vr_sale_id'     => null,
                'vr_amount'      => $use,
                'vr_redeemed_by' => intval($this->session->userdata('userid'))
            ));
        }
        return true;
    }

    protected function _decreaseStock($item_id, $qty, $store_id)
    {
        $this->db->query("UPDATE ezy_pos_stock SET stock_qty = stock_qty - ?
                          WHERE stock_itm_id = ? AND stock_store_id = ?",
                         array($qty, $item_id, $store_id));
        if ($this->db->affected_rows() > 0) { return true; }
        // The branch has never carried this item. Record the movement anyway,
        // so the shortfall is visible rather than silently ignored.
        return $this->db->insert('ezy_pos_stock', array(
            'stock_itm_id'   => $item_id,
            'stock_store_id' => $store_id,
            'stock_qty'      => -$qty,
            'stock_status'   => 1
        ));
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

        $row->exchange_items = array();
        if ($this->db->table_exists('ezy_pos_adv_exchange_item')) {
            $row->exchange_items = $this->db->query(
                "SELECT * FROM ezy_pos_adv_exchange_item WHERE adve_adv_id = ? ORDER BY adve_id",
                array(intval($adv_id)))->result();
        }
        $row->payments = array();
        if ($this->db->table_exists('ezy_pos_adv_payment')) {
            $row->payments = $this->db->query(
                "SELECT * FROM ezy_pos_adv_payment WHERE advp_adv_id = ? ORDER BY advp_id",
                array(intval($adv_id)))->result();
        }
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

        // A database that has not had v16 yet has no outgoing-items table, so
        // those columns are faked as zero rather than named.
        $excCount = $this->db->table_exists('ezy_pos_adv_exchange_item')
            ? "(SELECT COUNT(*) FROM ezy_pos_adv_exchange_item e WHERE e.adve_adv_id = a.adv_id)"
            : "0";
        $fields = $this->db->list_fields('ezy_pos_adv_return');
        $excTot = in_array('adv_exchange_total', $fields) ? 'a.adv_exchange_total' : '0';
        $netAmt = in_array('adv_net_amount', $fields)     ? 'a.adv_net_amount'     : '-a.adv_total';
        $typeCol= in_array('adv_type', $fields)           ? 'a.adv_type'           : "'return'";

        return $this->db->query(
            "SELECT a.*, c.cus_name, st.store_name,
                    ".$excTot." AS exchange_total, ".$netAmt." AS net_amount, ".$typeCol." AS type,
                    ".$excCount." AS exchange_count,
                    (SELECT COUNT(*) FROM ezy_pos_adv_return_item i WHERE i.advi_adv_id = a.adv_id) AS line_count,
                    (SELECT COALESCE(SUM(i.advi_qty),0) FROM ezy_pos_adv_return_item i WHERE i.advi_adv_id = a.adv_id) AS qty_total
             FROM ezy_pos_adv_return a
             LEFT JOIN ezy_pos_customers c ON c.cus_id = a.adv_cus_id
             LEFT JOIN ezy_pos_stores st ON st.store_id = a.adv_store_id
             ".$where."
             ORDER BY a.adv_id DESC", $params)->result();
    }
}
