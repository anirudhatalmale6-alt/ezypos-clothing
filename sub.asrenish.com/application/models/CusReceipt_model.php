<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customer payments - money a customer pays later against what they owe.
 *
 * One receipt per payment. It can be split over cash, cheques and any payment
 * method set up in Masters, can carry a discount, and settles the opening
 * balance and the customer's credit bills, oldest first unless the cashier
 * chooses otherwise.
 *
 * What it changes, all in one transaction:
 *   - each bill's outstanding (ezy_pos_cus_payment.cus_pay_credit) goes down;
 *   - the opening balance (ezy_pos_customers.cus_balance) goes down;
 *   - the customer's running balance (ezy_pos_cus_balnce, owed = negative)
 *     goes up by what was settled;
 *   - a cheque goes into the cheque register like any other customer cheque;
 *   - cash is written to the payment log the dashboard's daily totals read.
 * It deliberately does NOT add to a bill's cus_pay_cash: the Cash Flow report
 * reads that on the SALE's date, so a payment made today would have landed on
 * the day of the sale - or nowhere, if that day was outside the report. The
 * receipt is reported on its own date instead (Report_model, Customer Payment).
 */
class CusReceipt_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    /** Are the v21 tables there yet (migrate.php step 15)? */
    public function ready()
    {
        return $this->db->table_exists('ezy_pos_cus_receipt');
    }

    public function customers()
    {
        return $this->db->select('cus_id, cus_name, cus_contact')
                        ->where('cus_status', 1)->order_by('cus_name')
                        ->get('ezy_pos_customers')->result();
    }

    public function paymentMethods()
    {
        if ( ! $this->db->table_exists('ezy_pos_payment_methods')) { return array(); }
        return $this->db->where('pm_status', 1)->order_by('pm_name')->get('ezy_pos_payment_methods')->result();
    }

    /** What the customer owes: opening balance first, then each credit bill, oldest first. */
    public function outstanding($cusId)
    {
        $cusId = intval($cusId);
        $cus = $this->db->get_where('ezy_pos_customers', array('cus_id' => $cusId))->row();
        if ( ! $cus) { return null; }
        $billCols = $this->db->field_exists('sale_bill_no', 'ezy_pos_sale') ? ', s.sale_bill_no, s.sale_bill_seq' : '';
        $rows = $this->db->query(
            "SELECT s.sale_id, s.sale_date, s.sale_location, s.sale_grandtotal, cp.cus_pay_credit".$billCols."
             FROM ezy_pos_sale s
             JOIN ezy_pos_cus_payment cp ON cp.cus_pay_saleid = s.sale_id
             WHERE s.sale_cus_id = ? AND s.sale_status = '1' AND cp.cus_pay_credit > 0.004
             ORDER BY s.sale_date, s.sale_id", array($cusId))->result();
        $bills = array();
        foreach ($rows as $r) {
            $bills[] = array('sale_id' => (int)$r->sale_id, 'bill_no' => bill_no($r),
                             'date' => substr($r->sale_date, 0, 10), 'total' => round(floatval($r->sale_grandtotal), 2),
                             'owed' => round(floatval($r->cus_pay_credit), 2));
        }
        return array('customer' => array('id' => (int)$cus->cus_id, 'name' => $cus->cus_name),
                     'opening' => round(max(0, floatval($cus->cus_balance)), 2),
                     'bills' => $bills);
    }

    /**
     * Save a payment.
     *   $pays  = list of array(method => 'cash'|'cheque'|'pm', pm_id, amount, reference, bank, cheque_date)
     *   $alloc = list of array(sale_id (0 = opening balance), amount)
     * Returns array(ok, message, receipt_id).
     */
    public function save($cusId, $date, $storeId, $pays, $discount, $alloc, $note, $userId)
    {
        if ( ! $this->ready()) { return array(false, 'The database needs updating first: run step 15 in migrate.php.', 0); }
        $owed = $this->outstanding($cusId);
        if ( ! $owed) { return array(false, 'Pick the customer first.', 0); }
        $date = (strtotime($date) !== false && trim($date) !== '') ? date('Y-m-d', strtotime($date)) : date('Y-m-d');
        $discount = round(max(0, floatval($discount)), 2);

        // The money.
        $methods = array();
        foreach ($this->paymentMethods() as $m) { $methods[(int)$m->pm_id] = $m->pm_name; }
        $cleanPays = array(); $money = 0;
        foreach ((array)$pays as $p) {
            $amt = round(floatval(isset($p['amount']) ? $p['amount'] : 0), 2);
            if ($amt <= 0) { continue; }
            $kind = isset($p['method']) ? $p['method'] : 'cash';
            $pmId = intval(isset($p['pm_id']) ? $p['pm_id'] : 0);
            if ($kind === 'pm' && ! isset($methods[$pmId])) { return array(false, 'That payment method is not in Masters any more.', 0); }
            if ($kind === 'cheque') {
                if (trim((string)$p['reference']) === '' || trim((string)$p['bank']) === '') {
                    return array(false, 'A cheque needs its bank and cheque number.', 0);
                }
            }
            if ( ! in_array($kind, array('cash', 'cheque', 'pm'))) { $kind = 'cash'; }
            $cleanPays[] = array('method' => $kind, 'pm_id' => $kind === 'pm' ? $pmId : 0, 'amount' => $amt,
                                 'reference' => substr(trim((string)(isset($p['reference']) ? $p['reference'] : '')), 0, 100),
                                 'bank' => substr(trim((string)(isset($p['bank']) ? $p['bank'] : '')), 0, 100),
                                 'cheque_date' => (isset($p['cheque_date']) && strtotime($p['cheque_date']) !== false && $p['cheque_date'] !== '')
                                                  ? date('Y-m-d', strtotime($p['cheque_date'])) : null);
            $money += $amt;
        }
        $money = round($money, 2);
        if ($money <= 0 && $discount <= 0) { return array(false, 'Enter the amount paid.', 0); }

        // Where it goes. Nothing may settle more than is owed on it.
        $limits = array(0 => $owed['opening']);
        foreach ($owed['bills'] as $b) { $limits[$b['sale_id']] = $b['owed']; }
        $cleanAlloc = array(); $settled = 0;
        foreach ((array)$alloc as $a) {
            $sid = intval(isset($a['sale_id']) ? $a['sale_id'] : -1);
            $amt = round(floatval(isset($a['amount']) ? $a['amount'] : 0), 2);
            if ($amt <= 0) { continue; }
            if ( ! array_key_exists($sid, $limits)) { return array(false, 'One of the bills is not an open bill of this customer.', 0); }
            if ($amt > $limits[$sid] + 0.004) {
                return array(false, ($sid ? 'A bill' : 'The opening balance').' would be paid more than is owed on it ('
                             .number_format($limits[$sid], 2).').', 0);
            }
            $cleanAlloc[] = array('sale_id' => $sid, 'amount' => $amt);
            $settled += $amt;
        }
        $settled = round($settled, 2);
        if (abs($settled - round($money + $discount, 2)) > 0.009) {
            return array(false, 'The amount paid plus the discount ('.number_format($money + $discount, 2)
                         .') must equal what is settled against the bills ('.number_format($settled, 2).').', 0);
        }

        $this->db->trans_start();
        $this->db->insert('ezy_pos_cus_receipt', array(
            'rcpt_cus_id' => intval($cusId), 'rcpt_date' => $date, 'rcpt_store_id' => intval($storeId),
            'rcpt_amount' => $money, 'rcpt_discount' => $discount, 'rcpt_note' => substr((string)$note, 0, 255),
            'rcpt_status' => 1, 'rcpt_createdby' => intval($userId), 'rcpt_createdat' => date('Y-m-d H:i:s')));
        $rid = $this->db->insert_id();

        $cash = 0;
        foreach ($cleanPays as $p) {
            $this->db->insert('ezy_pos_cus_receipt_pay', array(
                'rp_rcpt_id' => $rid, 'rp_method' => $p['method'], 'rp_pm_id' => $p['pm_id'], 'rp_amount' => $p['amount'],
                'rp_reference' => $p['reference'], 'rp_bank' => $p['bank'], 'rp_cheque_date' => $p['cheque_date']));
            if ($p['method'] === 'cash') { $cash += $p['amount']; }
            if ($p['method'] === 'cheque') {
                $this->db->insert('ezy_pos_cus_cheque', array(
                    'cus_cheque_saleid' => 0, 'cheque_cus_id' => intval($cusId), 'cus_cheque_amount' => $p['amount'],
                    'cus_cheque_bank' => $p['bank'], 'cus_cheque_num' => $p['reference'],
                    'cus_cheque_date' => $p['cheque_date'] ? $p['cheque_date'] : $date, 'cus_cheque_givendate' => $date,
                    'cus_cheque_status' => 1, 'cus_cheque_transferred' => 0, 'cus_cheque_rcpt_id' => $rid));
            }
        }
        foreach ($cleanAlloc as $a) {
            $this->db->insert('ezy_pos_cus_receipt_alloc', array('ra_rcpt_id' => $rid, 'ra_sale_id' => $a['sale_id'], 'ra_amount' => $a['amount']));
            if ($a['sale_id']) {
                $this->db->set('cus_pay_credit', 'cus_pay_credit - '.floatval($a['amount']), false)
                         ->where('cus_pay_saleid', $a['sale_id'])->update('ezy_pos_cus_payment');
            } else {
                $this->db->set('cus_balance', 'cus_balance - '.floatval($a['amount']), false)
                         ->where('cus_id', intval($cusId))->update('ezy_pos_customers');
            }
        }
        // The running balance a sale lowers by its credit goes back up by what was settled.
        $this->db->set('bal_amount', 'bal_amount + '.floatval($settled), false)
                 ->where('bal_cusid', intval($cusId))->update('ezy_pos_cus_balnce');
        // Cash goes into the payment log the dashboard's daily cash totals read.
        if ($cash > 0) {
            $firstSale = 0;
            foreach ($cleanAlloc as $a) { if ($a['sale_id']) { $firstSale = $a['sale_id']; break; } }
            $this->db->insert('ezy_pos_cus_paymnt_log', array('pymntlog_saleid' => $firstSale, 'pymntlog_amount' => round($cash, 2), 'pymntlog_date' => $date));
        }
        $this->db->trans_complete();
        if ( ! $this->db->trans_status()) { return array(false, 'The database refused the save; nothing was changed.', 0); }
        return array(true, 'Saved', $rid);
    }

    public function receipt($id)
    {
        $r = $this->db->select('r.*, c.cus_name, c.cus_contact, st.store_name, u.user_name')
                      ->from('ezy_pos_cus_receipt r')
                      ->join('ezy_pos_customers c', 'c.cus_id = r.rcpt_cus_id', 'left')
                      ->join('ezy_pos_stores st', 'st.store_id = r.rcpt_store_id', 'left')
                      ->join('ezy_pos_users u', 'u.user_id = r.rcpt_createdby', 'left')
                      ->where('r.rcpt_id', intval($id))->get()->row();
        if ( ! $r) { return null; }
        $r->pays = $this->db->query("SELECT p.*, pm.pm_name FROM ezy_pos_cus_receipt_pay p
                                     LEFT JOIN ezy_pos_payment_methods pm ON pm.pm_id = p.rp_pm_id
                                     WHERE p.rp_rcpt_id = ? ORDER BY p.rp_id", array($r->rcpt_id))->result();
        $billCols = $this->db->field_exists('sale_bill_no', 'ezy_pos_sale') ? ', s.sale_bill_no, s.sale_bill_seq' : '';
        $r->alloc = $this->db->query("SELECT a.ra_sale_id, a.ra_amount, s.sale_id, s.sale_date, s.sale_location".$billCols."
                                      FROM ezy_pos_cus_receipt_alloc a LEFT JOIN ezy_pos_sale s ON s.sale_id = a.ra_sale_id
                                      WHERE a.ra_rcpt_id = ? ORDER BY a.ra_id", array($r->rcpt_id))->result();
        return $r;
    }

    public function recent($limit = 50)
    {
        if ( ! $this->ready()) { return array(); }
        return $this->db->query(
            "SELECT r.rcpt_id, r.rcpt_date, r.rcpt_amount, r.rcpt_discount, c.cus_name, st.store_name,
                    (SELECT GROUP_CONCAT(CASE p.rp_method WHEN 'pm' THEN pm.pm_name WHEN 'cheque' THEN 'Cheque' ELSE 'Cash' END
                                         ORDER BY p.rp_id SEPARATOR ', ')
                     FROM ezy_pos_cus_receipt_pay p LEFT JOIN ezy_pos_payment_methods pm ON pm.pm_id = p.rp_pm_id
                     WHERE p.rp_rcpt_id = r.rcpt_id) AS methods
             FROM ezy_pos_cus_receipt r
             LEFT JOIN ezy_pos_customers c ON c.cus_id = r.rcpt_cus_id
             LEFT JOIN ezy_pos_stores st ON st.store_id = r.rcpt_store_id
             WHERE r.rcpt_status = 1
             ORDER BY r.rcpt_id DESC LIMIT ".intval($limit))->result();
    }

    public static function number($id) { return 'CP-'.str_pad((int)$id, 5, '0', STR_PAD_LEFT); }
}
