<?php
class ProductionSale_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function getNextCode()
    {
        $this->db->select_max('prodsale_id');
        $q = $this->db->get('ezy_pos_prodsale');
        $row = $q->row();
        $next = ($row && $row->prodsale_id) ? $row->prodsale_id + 1 : 1;
        return 'PS-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function getAllActiveItems()
    {
        $str = "SELECT i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice, i.itm_uom,
                COALESCE(SUM(s.stock_qty), 0) as stock_qty
                FROM ezy_pos_items i
                LEFT JOIN ezy_pos_stock s ON s.stock_itm_id = i.itm_id AND s.stock_status = 1
                WHERE i.itm_status = 1
                GROUP BY i.itm_id
                ORDER BY i.itm_name";
        $q = $this->db->query($str);
        return $q->result();
    }

    public function createOrder($data)
    {
        $this->db->insert('ezy_pos_prodsale', $data);
        return $this->db->insert_id();
    }

    public function addItem($data)
    {
        return $this->db->insert('ezy_pos_prodsale_items', $data);
    }

    public function addService($data)
    {
        return $this->db->insert('ezy_pos_prodsale_services', $data);
    }

    public function getItems($prodsale_id)
    {
        $str = "SELECT pi.*, i.itm_code, i.itm_name, i.itm_uom
                FROM ezy_pos_prodsale_items pi
                INNER JOIN ezy_pos_items i ON i.itm_id = pi.prodsaleitem_item_id
                WHERE pi.prodsaleitem_prodsale_id = '" . intval($prodsale_id) . "'
                AND pi.prodsaleitem_status = 1";
        $q = $this->db->query($str);
        return $q->result();
    }

    public function getServices($prodsale_id)
    {
        $this->db->where('prodsvc_prodsale_id', $prodsale_id);
        $this->db->where('prodsvc_status', 1);
        $q = $this->db->get('ezy_pos_prodsale_services');
        return $q->result();
    }

    public function getOrderDetails($prodsale_id)
    {
        // include tailor + customer contact if the columns exist
        $hasTailor = in_array('prodsale_tailor_id', $this->db->list_fields('ezy_pos_prodsale'));
        $tailorSel  = $hasTailor ? ", sup.sup_name AS tailor_name" : "";
        $tailorJoin = $hasTailor ? " LEFT JOIN ezy_pos_suppliers sup ON sup.sup_id = p.prodsale_tailor_id" : "";
        $str = "SELECT p.*, c.cus_name, c.cus_contact, c.cus_address, st.store_name" . $tailorSel . "
                FROM ezy_pos_prodsale p
                LEFT JOIN ezy_pos_customers c ON c.cus_id = p.prodsale_cus_id
                LEFT JOIN ezy_pos_stores st ON st.store_id = p.prodsale_store_id" . $tailorJoin . "
                WHERE p.prodsale_id = '" . intval($prodsale_id) . "'";
        $q = $this->db->query($str);
        return $q->row();
    }

    // Payment history for an order (for the estimate / final bill printouts)
    public function getPayments($prodsale_id)
    {
        if (!$this->db->table_exists('ezy_pos_prodsale_payments')) return array();
        $this->db->where('psp_prodsale_id', $prodsale_id);
        $this->db->order_by('psp_id', 'asc');
        return $this->db->get('ezy_pos_prodsale_payments')->result();
    }

    public function getItemById($id)
    {
        $this->db->where('prodsaleitem_id', $id);
        $q = $this->db->get('ezy_pos_prodsale_items');
        return $q->row();
    }

    public function deleteItem($id)
    {
        $this->db->where('prodsaleitem_id', $id);
        $this->db->update('ezy_pos_prodsale_items', array('prodsaleitem_status' => 0));
    }

    public function deleteService($id)
    {
        $this->db->where('prodsvc_id', $id);
        $this->db->update('ezy_pos_prodsale_services', array('prodsvc_status' => 0));
    }

    public function recalculateTotals($prodsale_id)
    {
        // Material cost
        $q1 = $this->db->query("SELECT COALESCE(SUM(prodsaleitem_total), 0) as mat_cost
            FROM ezy_pos_prodsale_items
            WHERE prodsaleitem_prodsale_id = '" . intval($prodsale_id) . "' AND prodsaleitem_status = 1");
        $mat_cost = $q1->row()->mat_cost;

        // Tailoring/service charges
        $q2 = $this->db->query("SELECT COALESCE(SUM(prodsvc_charge), 0) as svc_cost
            FROM ezy_pos_prodsale_services
            WHERE prodsvc_prodsale_id = '" . intval($prodsale_id) . "' AND prodsvc_status = 1");
        $svc_cost = $q2->row()->svc_cost;

        // Get existing tailoring charge from header
        $q3 = $this->db->query("SELECT * FROM ezy_pos_prodsale WHERE prodsale_id = '" . intval($prodsale_id) . "'");
        $order = $q3->row();
        $tailoring = $order ? $order->prodsale_tailoring_charge : 0;
        $paid = $order ? $order->prodsale_paid : 0;

        $total_tailoring = $tailoring + $svc_cost;
        $gross = $mat_cost + $total_tailoring;

        // The discount comes off the whole order - cloth, tailoring and any
        // service charges together - so a percentage means what the customer
        // thinks it means. It is worked out again on every recalculation: add
        // another item to a 10% order and the 10% follows it, which is why the
        // rupee figure is stored rather than trusted from before.
        $update = array(
            'prodsale_material_cost' => $mat_cost
        );
        $discount = 0;
        $fields = $this->db->list_fields('ezy_pos_prodsale');
        if ($order && in_array('prodsale_discount', $fields)) {
            $type = ($order->prodsale_discount_type === 'percentage') ? 'percentage' : 'flat';
            $rate = floatval($order->prodsale_discount_rate);
            if ($rate < 0) { $rate = 0; }
            $discount = ($type === 'percentage') ? round($gross * $rate / 100, 2) : round($rate, 2);
            // Never more than the order is worth - a discount must not turn
            // into money owed to the customer.
            if ($discount > $gross) { $discount = $gross; }
            if ($discount < 0) { $discount = 0; }
            $update['prodsale_discount'] = $discount;
        }

        $total = round($gross - $discount, 2);
        $update['prodsale_total'] = $total;
        $update['prodsale_balance'] = round($total - $paid, 2);

        $this->db->where('prodsale_id', $prodsale_id);
        $this->db->update('ezy_pos_prodsale', $update);
    }

    /**
     * Record the discount that was typed, then let recalculateTotals turn it
     * into rupees. Nothing is worked out here, so there is only one place in
     * the system that decides what an order comes to.
     */
    public function setDiscount($prodsale_id, $value, $type)
    {
        $fields = $this->db->list_fields('ezy_pos_prodsale');
        if (!in_array('prodsale_discount_rate', $fields)) {
            return false;   // v17 not run yet
        }
        $value = floatval($value);
        if ($value < 0) { $value = 0; }
        $type = ($type === 'percentage') ? 'percentage' : 'flat';
        if ($type === 'percentage' && $value > 100) { $value = 100; }

        $this->db->where('prodsale_id', intval($prodsale_id));
        $this->db->update('ezy_pos_prodsale', array(
            'prodsale_discount_rate' => $value,
            'prodsale_discount_type' => $type
        ));
        $this->recalculateTotals($prodsale_id);
        return true;
    }

    public function updateStatus($id, $status)
    {
        $this->db->where('prodsale_id', $id);
        $this->db->update('ezy_pos_prodsale', array('prodsale_status' => $status));
    }

    public function addPayment($id, $amount, $method = 'Cash', $card_ref = '')
    {
        $q = $this->db->query("SELECT prodsale_paid, prodsale_total FROM ezy_pos_prodsale WHERE prodsale_id = '" . intval($id) . "'");
        $row = $q->row();
        $newPaid = ($row ? $row->prodsale_paid : 0) + $amount;
        $balance = ($row ? $row->prodsale_total : 0) - $newPaid;

        $this->db->where('prodsale_id', $id);
        $this->db->update('ezy_pos_prodsale', array(
            'prodsale_paid' => $newPaid,
            'prodsale_balance' => $balance
        ));

        // Log payment with method if payments table exists
        if($this->db->table_exists('ezy_pos_prodsale_payments')){
            $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
            $insert = array(
                'psp_prodsale_id' => $id,
                'psp_amount' => $amount,
                'psp_method' => $method,
                'psp_created_by' => $userid
            );
            // Save card reference if provided
            $fields = $this->db->list_fields('ezy_pos_prodsale_payments');
            if(in_array('psp_card_ref', $fields) && $card_ref){
                $insert['psp_card_ref'] = $card_ref;
            }
            $this->db->insert('ezy_pos_prodsale_payments', $insert);
        }
    }

    public function getAllOrders()
    {
        $pickup_col = '';
        $pickup_join = '';
        $fields = $this->db->list_fields('ezy_pos_prodsale');
        if(in_array('prodsale_pickup_store_id', $fields)){
            $pickup_col = ', ps.store_name AS pickup_store_name';
            $pickup_join = ' LEFT JOIN ezy_pos_stores ps ON ps.store_id = p.prodsale_pickup_store_id';
        }
        $str = "SELECT p.*, c.cus_name, st.store_name".$pickup_col."
                FROM ezy_pos_prodsale p
                LEFT JOIN ezy_pos_customers c ON c.cus_id = p.prodsale_cus_id
                LEFT JOIN ezy_pos_stores st ON st.store_id = p.prodsale_store_id"
                .$pickup_join.
                " ORDER BY p.prodsale_id DESC";
        $q = $this->db->query($str);
        return $q->result();
    }
}
