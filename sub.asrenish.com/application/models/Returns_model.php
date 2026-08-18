<?php
class Returns_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    // ===================== HELPER: TABLE/COLUMN CHECKS =====================

    /**
     * Check if new returns tables exist (safe for pre-migration)
     */
    private function _tablesExist()
    {
        return $this->db->table_exists('ezy_pos_returns')
            && $this->db->table_exists('ezy_pos_return_items')
            && $this->db->table_exists('ezy_pos_exchange_items');
    }

    /**
     * Check if sale table has the new return-status columns
     */
    private function _saleHasReturnCols()
    {
        $fields = $this->db->list_fields('ezy_pos_sale');
        return in_array('sale_return_status', $fields)
            && in_array('sale_last_modified', $fields);
    }

    // ===================== SALE LOOKUP =====================

    /**
     * Get sale details with customer info
     */
    public function getSaleWithCustomer($sale_id)
    {
        $str = "SELECT s.*, c.cus_name, c.cus_address, c.cus_id,
                       st.store_name, st.store_id
                FROM ezy_pos_sale s
                LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                LEFT JOIN ezy_pos_stores st ON st.store_id = s.sale_location
                WHERE s.sale_id = ?
                AND s.sale_status = 1";
        $query = $this->db->query($str, array($sale_id));
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    /**
     * Get sale items with item details
     */
    public function getSaleItems($sale_id)
    {
        $str = "SELECT si.saleitem_item_id, si.saleitem_price, si.saleitem_quantity,
                       si.saleitem_discount, si.saleitem_total,
                       i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice
                FROM ezy_pos_sale_item si
                INNER JOIN ezy_pos_items i ON i.itm_id = si.saleitem_item_id
                WHERE si.saleitem_sale_id = ?";
        $query = $this->db->query($str, array($sale_id));
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /**
     * Get return history for a sale (from the new returns table)
     */
    public function getReturnHistory($sale_id)
    {
        if (!$this->db->table_exists('ezy_pos_returns')) {
            return false;
        }
        $str = "SELECT r.*, u.user_name
                FROM ezy_pos_returns r
                LEFT JOIN ezy_pos_users u ON u.user_id = r.ret_created_by
                WHERE r.ret_sale_id = ?
                ORDER BY r.ret_created_at DESC";
        $query = $this->db->query($str, array($sale_id));
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    // ===================== CREATE RETURN / EXCHANGE =====================

    /**
     * Create a return/exchange record
     * $data keys: sale_id, type, refund_amount, exchange_amount, net_amount, reason
     */
    public function createReturn($data)
    {
        if (!$this->db->table_exists('ezy_pos_returns')) {
            return false;
        }
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        $insert = array(
            'ret_sale_id'        => $data['sale_id'],
            'ret_type'           => $data['type'],
            'ret_refund_amount'  => $data['refund_amount'],
            'ret_exchange_amount'=> $data['exchange_amount'],
            'ret_net_amount'     => $data['net_amount'],
            'ret_reason'         => $data['reason'],
            'ret_created_by'     => $userid,
            'ret_status'         => 1
        );
        // Add return store location if column exists
        $fields = $this->db->list_fields('ezy_pos_returns');
        if(in_array('ret_store_id', $fields) && isset($data['return_store_id'])){
            $insert['ret_store_id'] = $data['return_store_id'];
        }
        // 'cash' = money handed back, 'store_credit' = left on the customer's account
        if(in_array('ret_refund_mode', $fields) && isset($data['refund_mode'])){
            $insert['ret_refund_mode'] = $data['refund_mode'];
        }
        // Discount given on the exchange as a whole. ret_exchange_amount is
        // already net of it; these two only record what was taken off and how,
        // and are written only where the v11 columns exist.
        if(in_array('ret_exchange_discount', $fields) && isset($data['exchange_discount'])){
            $insert['ret_exchange_discount'] = $data['exchange_discount'];
        }
        if(in_array('ret_exchange_discount_type', $fields) && isset($data['exchange_discount_type'])){
            $insert['ret_exchange_discount_type'] = $data['exchange_discount_type'];
        }
        $this->db->insert('ezy_pos_returns', $insert);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Add a returned item row
     * $data keys: ret_id, item_id, item_name, qty, price, discount, total
     */
    public function addReturnItem($data)
    {
        if (!$this->db->table_exists('ezy_pos_return_items')) {
            return false;
        }
        $insert = array(
            'ri_return_id' => $data['ret_id'],
            'ri_item_id'   => $data['item_id'],
            'ri_item_name' => $data['item_name'],
            'ri_qty'       => $data['qty'],
            'ri_price'     => $data['price'],
            'ri_discount'  => $data['discount'],
            'ri_total'     => $data['total']
        );
        return $this->db->insert('ezy_pos_return_items', $insert);
    }

    /**
     * Add an exchange item row (new item given to customer)
     * $data keys: ret_id, item_id, item_name, qty, price, discount, total
     */
    public function addExchangeItem($data)
    {
        if (!$this->db->table_exists('ezy_pos_exchange_items')) {
            return false;
        }
        $insert = array(
            'ei_return_id' => $data['ret_id'],
            'ei_item_id'   => $data['item_id'],
            'ei_item_name' => $data['item_name'],
            'ei_qty'       => $data['qty'],
            'ei_price'     => $data['price'],
            'ei_discount'  => $data['discount'],
            'ei_total'     => $data['total']
        );
        // Whether that discount was a percentage or a flat amount. Written only
        // where the v11 column exists, so this still runs before the migration.
        if(isset($data['discount_type'])
           && in_array('ei_discount_type', $this->db->list_fields('ezy_pos_exchange_items'))){
            $insert['ei_discount_type'] = $data['discount_type'];
        }
        return $this->db->insert('ezy_pos_exchange_items', $insert);
    }

    // ===================== SALE STATUS UPDATE =====================

    /**
     * Update sale return status column
     * $status: 'refunded', 'partial_refunded', 'exchanged'
     */
    public function updateSaleReturnStatus($sale_id, $status, $modified_date)
    {
        if (!$this->_saleHasReturnCols()) {
            return false;
        }
        $str = "UPDATE ezy_pos_sale
                SET sale_return_status = ?, sale_last_modified = ?
                WHERE sale_id = ?";
        $this->db->query($str, array($status, $modified_date, $sale_id));
        return ($this->db->affected_rows() > 0);
    }

    /**
     * Update sale totals after a return (reduce grandtotal, subtotal)
     */
    public function updateSaleTotals($sale_id, $return_amount)
    {
        $str = "UPDATE ezy_pos_sale
                SET sale_grandtotal = sale_grandtotal - ?
                WHERE sale_id = ?";
        $this->db->query($str, array($return_amount, $sale_id));
        return ($this->db->affected_rows() >= 0);
    }

    // ===================== STOCK OPERATIONS =====================

    /**
     * Increase stock for a returned item (item comes back to inventory)
     */
    public function increaseStock($item_id, $qty, $store_id)
    {
        if ($store_id == '' || $store_id == null) { $store_id = 0; }
        $str = "UPDATE ezy_pos_stock SET stock_qty = stock_qty + ?
                WHERE stock_itm_id = ? AND stock_store_id = ?";
        $this->db->query($str, array($qty, $item_id, $store_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        // Row does not exist yet -- insert it
        $data = array(
            'stock_itm_id'  => $item_id,
            'stock_store_id'=> $store_id,
            'stock_qty'     => $qty,
            'stock_status'  => 1
        );
        return $this->db->insert('ezy_pos_stock', $data);
    }

    /**
     * Decrease stock for an exchange item (new item leaves inventory)
     */
    public function decreaseStock($item_id, $qty, $store_id)
    {
        if ($store_id == '' || $store_id == null) { $store_id = 0; }
        // Ensure row exists before decrement
        $str1 = "SELECT * FROM ezy_pos_stock WHERE stock_itm_id = ? AND stock_store_id = ?";
        $q1 = $this->db->query($str1, array($item_id, $store_id));
        if ($q1->num_rows() == 0) {
            $data = array(
                'stock_itm_id'  => $item_id,
                'stock_store_id'=> $store_id,
                'stock_qty'     => 0,
                'stock_status'  => 1
            );
            $this->db->insert('ezy_pos_stock', $data);
        }
        $str = "UPDATE ezy_pos_stock SET stock_qty = stock_qty - ?
                WHERE stock_itm_id = ? AND stock_store_id = ?";
        $this->db->query($str, array($qty, $item_id, $store_id));
        return ($this->db->affected_rows() > 0);
    }

    // ===================== RETURNS LISTING =====================

    /**
     * Get all returns for listing page
     */
    public function getAllReturns()
    {
        if (!$this->db->table_exists('ezy_pos_returns')) {
            return false;
        }
        $str = "SELECT r.*, s.sale_id, s.sale_grandtotal, s.sale_date,
                       c.cus_name, u.user_name
                FROM ezy_pos_returns r
                LEFT JOIN ezy_pos_sale s ON s.sale_id = r.ret_sale_id
                LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                LEFT JOIN ezy_pos_users u ON u.user_id = r.ret_created_by
                WHERE r.ret_status = 1
                ORDER BY r.ret_id DESC";
        $query = $this->db->query($str);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    // ===================== RETURN DETAIL =====================

    /**
     * Get a single return record by ID
     */
    public function getReturnDetails($ret_id)
    {
        if (!$this->db->table_exists('ezy_pos_returns')) {
            return false;
        }
        $str = "SELECT r.*, s.sale_id, s.sale_grandtotal, s.sale_date, s.sale_location,
                       c.cus_name, c.cus_address, c.cus_id,
                       u.user_name,
                       st.store_name
                FROM ezy_pos_returns r
                LEFT JOIN ezy_pos_sale s ON s.sale_id = r.ret_sale_id
                LEFT JOIN ezy_pos_customers c ON c.cus_id = s.sale_cus_id
                LEFT JOIN ezy_pos_users u ON u.user_id = r.ret_created_by
                LEFT JOIN ezy_pos_stores st ON st.store_id = s.sale_location
                WHERE r.ret_id = ?";
        $query = $this->db->query($str, array($ret_id));
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    /**
     * Get returned items for a given return
     */
    public function getReturnItems($ret_id)
    {
        if (!$this->db->table_exists('ezy_pos_return_items')) {
            return false;
        }
        $str = "SELECT ri.*, i.itm_code, i.itm_name AS item_name_live
                FROM ezy_pos_return_items ri
                LEFT JOIN ezy_pos_items i ON i.itm_id = ri.ri_item_id
                WHERE ri.ri_return_id = ?";
        $query = $this->db->query($str, array($ret_id));
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /**
     * Get exchange items for a given return
     */
    public function getExchangeItems($ret_id)
    {
        if (!$this->db->table_exists('ezy_pos_exchange_items')) {
            return false;
        }
        $str = "SELECT ei.*, i.itm_code, i.itm_name AS item_name_live
                FROM ezy_pos_exchange_items ei
                LEFT JOIN ezy_pos_items i ON i.itm_id = ei.ei_item_id
                WHERE ei.ei_return_id = ?";
        $query = $this->db->query($str, array($ret_id));
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    // ===================== RETURN / EXCHANGE PAYMENTS =====================

    /**
     * Record one payment line against a return.
     * $direction: 'in'  = customer paid the exchange difference
     *             'out' = refund handed back to the customer
     */
    public function addReturnPayment($ret_id, $direction, $method, $reference, $amount, $inCashflow = 1)
    {
        if (!$this->db->table_exists('ezy_pos_return_payments')) {
            return false;
        }
        $amount = floatval($amount);
        if ($amount <= 0) return false;

        $row = array(
            'rp_ret_id'     => intval($ret_id),
            'rp_direction'  => ($direction === 'out' ? 'out' : 'in'),
            'rp_method'     => ($method !== '' ? $method : 'Cash'),
            'rp_reference'  => ($reference !== '' ? $reference : null),
            'rp_amount'     => $amount,
            'rp_created_by' => isset($_SESSION['userid']) ? intval($_SESSION['userid']) : 0
        );
        // Store credit is not money moving, so it must not reach the Cash Flow report.
        if (in_array('rp_in_cashflow', $this->db->list_fields('ezy_pos_return_payments'))) {
            $row['rp_in_cashflow'] = $inCashflow ? 1 : 0;
        }
        return $this->db->insert('ezy_pos_return_payments', $row);
    }

    /**
     * Put a refund on the customer's account instead of handing cash back.
     *
     * ezy_pos_cus_balnce.bal_amount holds credit as a positive figure (a credit
     * sale subtracts from it), so store credit is added on.
     */
    public function addCustomerCredit($sale_id, $amount)
    {
        $amount = floatval($amount);
        if ($amount <= 0) return false;

        $sale = $this->db->select('sale_cus_id')
                         ->get_where('ezy_pos_sale', array('sale_id' => $sale_id))->row();
        if (!$sale || !$sale->sale_cus_id) return false;
        $cus_id = intval($sale->sale_cus_id);

        $existing = $this->db->get_where('ezy_pos_cus_balnce', array('bal_cusid' => $cus_id))->row();
        if ($existing) {
            $this->db->query("UPDATE ezy_pos_cus_balnce SET bal_amount = bal_amount + ? WHERE bal_cusid = ?",
                             array($amount, $cus_id));
        } else {
            $this->db->insert('ezy_pos_cus_balnce', array('bal_cusid' => $cus_id, 'bal_amount' => $amount));
        }
        return true;
    }

    /**
     * All payment lines recorded against a return, oldest first.
     */
    public function getReturnPayments($ret_id)
    {
        if (!$this->db->table_exists('ezy_pos_return_payments')) {
            return array();
        }
        return $this->db->query(
            "SELECT * FROM ezy_pos_return_payments WHERE rp_ret_id = ? ORDER BY rp_id",
            array($ret_id)
        )->result();
    }
}
