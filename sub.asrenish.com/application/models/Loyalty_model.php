<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customer Loyalty (Point 8)
 * - Settings are stored in the existing ezy_pos_config2 table (config_id NULL)
 *   so no extra settings table is needed.
 * - Points balance is kept on ezy_pos_customers.cus_loyalty_points and every
 *   movement is written to ezy_pos_loyalty_ledger.
 */
class Loyalty_model extends CI_Model {

    // default settings if nothing configured yet
    private $defaults = array(
        'loyalty_enabled'          => '0',   // master on/off
        'loyalty_earn_points'      => '1',   // points earned ...
        'loyalty_earn_amount'      => '100', // ... per this amount spent
        'loyalty_redeem_value'     => '1',   // 1 point = this much currency on redemption
        'loyalty_min_redeem'       => '100', // minimum point balance before redeeming allowed
        'loyalty_min_purchase'     => '0',   // minimum bill (grand total) to earn points
        'loyalty_max_redeem_pct'   => '100', // max % of a bill that can be paid with points
        'loyalty_round'            => 'down' // how earned points are rounded: down|nearest
    );

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function tableExists() {
        return $this->db->table_exists('ezy_pos_loyalty_ledger');
    }

    public function hasPointsColumn() {
        return in_array('cus_loyalty_points', $this->db->list_fields('ezy_pos_customers'));
    }

    /* ---------------- settings ---------------- */

    public function getSettings() {
        $out = $this->defaults;
        if ($this->db->table_exists('ezy_pos_config2')) {
            $this->db->where_in('config_key', array_keys($this->defaults));
            $rows = $this->db->get('ezy_pos_config2')->result();
            foreach ($rows as $r) {
                $out[$r->config_key] = $r->config_value;
            }
        }
        return $out;
    }

    public function getSetting($key) {
        $s = $this->getSettings();
        return isset($s[$key]) ? $s[$key] : null;
    }

    public function saveSettings($values) {
        foreach ($values as $key => $val) {
            if (!array_key_exists($key, $this->defaults)) continue;
            // Match the existing config-write pattern (filter by key only)
            $this->db->where('config_key', $key);
            $exists = $this->db->get('ezy_pos_config2')->row();
            if ($exists) {
                $this->db->where('config_key', $key);
                $this->db->update('ezy_pos_config2', array('config_value' => $val));
            } else {
                $this->db->insert('ezy_pos_config2', array(
                    'config_key'   => $key,
                    'config_value' => $val
                ));
            }
        }
        return true;
    }

    public function isEnabled() {
        return $this->getSetting('loyalty_enabled') == '1' && $this->tableExists() && $this->hasPointsColumn();
    }

    /* ---------------- balance ---------------- */

    public function getPoints($cus_id) {
        if (!$this->hasPointsColumn()) return 0;
        $row = $this->db->select('cus_loyalty_points')
                        ->where('cus_id', $cus_id)
                        ->get('ezy_pos_customers')->row();
        return $row ? floatval($row->cus_loyalty_points) : 0;
    }

    /**
     * Points a bill would earn given current settings.
     */
    public function calcEarnedPoints($grandtotal) {
        $s = $this->getSettings();
        if ($s['loyalty_enabled'] != '1') return 0;
        if (floatval($grandtotal) < floatval($s['loyalty_min_purchase'])) return 0;
        $per = floatval($s['loyalty_earn_amount']);
        if ($per <= 0) return 0;
        $pts = (floatval($grandtotal) / $per) * floatval($s['loyalty_earn_points']);
        if ($s['loyalty_round'] == 'nearest') return round($pts, 2);
        return floor($pts * 100) / 100; // round down to 2dp
    }

    /**
     * Write a ledger row and update the customer balance in one shot.
     */
    private function _move($cus_id, $type, $points, $amount, $sale_id, $note) {
        $current = $this->getPoints($cus_id);
        $newBal  = $current + $points; // points already signed (+earn / -redeem)
        if ($newBal < 0) $newBal = 0;

        $this->db->insert('ezy_pos_loyalty_ledger', array(
            'll_cus_id'        => $cus_id,
            'll_sale_id'       => $sale_id ? $sale_id : null,
            'll_type'          => $type,
            'll_points'        => $points,
            'll_amount'        => $amount,
            'll_balance_after' => $newBal,
            'll_note'          => $note,
            'll_createdby'     => isset($_SESSION['userid']) ? $_SESSION['userid'] : null
        ));

        $this->db->where('cus_id', $cus_id);
        $this->db->update('ezy_pos_customers', array('cus_loyalty_points' => $newBal));
        return $newBal;
    }

    public function earn($cus_id, $points, $sale_id, $grandtotal) {
        if (!$cus_id || $points <= 0) return 0;
        return $this->_move($cus_id, 'earn', abs($points), $grandtotal, $sale_id,
            'Earned on sale #' . $sale_id);
    }

    public function redeem($cus_id, $points, $amount, $sale_id) {
        if (!$cus_id || $points <= 0) return 0;
        $bal = $this->getPoints($cus_id);
        if ($points > $bal) $points = $bal; // never go negative
        return $this->_move($cus_id, 'redeem', -abs($points), $amount, $sale_id,
            'Redeemed on sale #' . $sale_id);
    }

    public function adjust($cus_id, $points, $note) {
        if (!$cus_id) return 0;
        return $this->_move($cus_id, 'adjust', floatval($points), 0, null,
            $note ? $note : 'Manual adjustment');
    }

    /* ---------------- reporting ---------------- */

    public function getLedger($cus_id = null, $from = null, $to = null) {
        $this->db->select('l.*, c.cus_name, c.cus_contact');
        $this->db->from('ezy_pos_loyalty_ledger l');
        $this->db->join('ezy_pos_customers c', 'l.ll_cus_id = c.cus_id', 'left');
        if ($cus_id) $this->db->where('l.ll_cus_id', $cus_id);
        if ($from)   $this->db->where('DATE(l.ll_createdat) >=', $from);
        if ($to)     $this->db->where('DATE(l.ll_createdat) <=', $to);
        $this->db->order_by('l.ll_id', 'DESC');
        return $this->db->get()->result();
    }

    public function getCustomersWithPoints() {
        if (!$this->hasPointsColumn()) return array();
        $this->db->select('cus_id, cus_name, cus_contact, cus_loyalty_points');
        $this->db->where('cus_status', 1);
        $this->db->where('cus_loyalty_points >', 0);
        $this->db->order_by('cus_loyalty_points', 'DESC');
        return $this->db->get('ezy_pos_customers')->result();
    }
}
