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
        $str = "SELECT p.*, c.cus_name, st.store_name
                FROM ezy_pos_prodsale p
                LEFT JOIN ezy_pos_customers c ON c.cus_id = p.prodsale_cus_id
                LEFT JOIN ezy_pos_stores st ON st.store_id = p.prodsale_store_id
                WHERE p.prodsale_id = '" . intval($prodsale_id) . "'";
        $q = $this->db->query($str);
        return $q->row();
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
        $q3 = $this->db->query("SELECT prodsale_tailoring_charge, prodsale_paid FROM ezy_pos_prodsale WHERE prodsale_id = '" . intval($prodsale_id) . "'");
        $order = $q3->row();
        $tailoring = $order ? $order->prodsale_tailoring_charge : 0;
        $paid = $order ? $order->prodsale_paid : 0;

        $total_tailoring = $tailoring + $svc_cost;
        $total = $mat_cost + $total_tailoring;
        $balance = $total - $paid;

        $this->db->where('prodsale_id', $prodsale_id);
        $this->db->update('ezy_pos_prodsale', array(
            'prodsale_material_cost' => $mat_cost,
            'prodsale_total' => $total,
            'prodsale_balance' => $balance
        ));
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
