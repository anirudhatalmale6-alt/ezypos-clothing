<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customer Loyalty (Point 8)
 */
class Loyalty extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        $this->load->database();
        $this->load->model('Loyalty_model');
        $this->load->model('Configs_model');
        $this->load->model('Customers_model');
    }

    // Admin OR a user granted the Customer Loyalty permission may manage loyalty.
    // (customerInfo() and processSale() are used by the sales screen and are left open.)
    private function _isAdmin() {
        return (isset($_SESSION['userrole']) && $_SESSION['userrole'] == 1)
            || (isset($_SESSION['privLoyalty']) && $_SESSION['privLoyalty'] == 1);
    }

    /* ---------------- admin settings + report page ---------------- */

    public function settings() {
        if (!$this->_isAdmin()) { show_error('Access denied', 403); return; }
        $data1['title']  = 'Customer Loyalty';
        $data1['config'] = $this->Configs_model->getConfigName();
        $data = array(
            'settings'  => $this->Loyalty_model->getSettings(),
            'customers' => $this->Loyalty_model->getCustomersWithPoints(),
            'ready'     => $this->Loyalty_model->tableExists() && $this->Loyalty_model->hasPointsColumn()
        );
        $this->load->view('templates/header', $data1);
        $this->load->view('loyalty/settings', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    public function saveSettings() {
        if (!$this->_isAdmin()) { echo json_encode(array('success' => false, 'msg' => 'Access denied')); return; }
        $keys = array('loyalty_enabled','loyalty_earn_points','loyalty_earn_amount',
                      'loyalty_redeem_value','loyalty_min_redeem','loyalty_min_purchase',
                      'loyalty_max_redeem_pct','loyalty_round');
        $vals = array();
        foreach ($keys as $k) {
            $v = $this->input->post($k);
            if ($v !== null) $vals[$k] = $v;
        }
        $this->Loyalty_model->saveSettings($vals);
        echo json_encode(array('success' => true));
    }

    public function report() {
        if (!$this->_isAdmin()) { show_error('Access denied', 403); return; }
        $data1['title']  = 'Loyalty Report';
        $data1['config'] = $this->Configs_model->getConfigName();
        $cus_id = $this->input->get('cus_id');
        $from   = $this->input->get('from');
        $to     = $this->input->get('to');
        $data = array(
            'ledger'    => $this->Loyalty_model->tableExists() ? $this->Loyalty_model->getLedger($cus_id, $from, $to) : array(),
            'customers' => $this->Customers_model->getCustomers(),
            'f_cus'     => $cus_id,
            'f_from'    => $from,
            'f_to'      => $to
        );
        $this->load->view('templates/header', $data1);
        $this->load->view('loyalty/report', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    /* ---------------- used by the sales screen ---------------- */

    // GET points + settings for a customer (called when customer selected)
    public function customerInfo() {
        $cus_id = $this->input->post('cus_id');
        $s = $this->Loyalty_model->getSettings();
        echo json_encode(array(
            'enabled'       => $this->Loyalty_model->isEnabled() ? 1 : 0,
            'points'        => $cus_id ? $this->Loyalty_model->getPoints($cus_id) : 0,
            'redeem_value'  => floatval($s['loyalty_redeem_value']),
            'min_redeem'    => floatval($s['loyalty_min_redeem']),
            'max_redeem_pct'=> floatval($s['loyalty_max_redeem_pct']),
            'earn_points'   => floatval($s['loyalty_earn_points']),
            'earn_amount'   => floatval($s['loyalty_earn_amount']),
            'min_purchase'  => floatval($s['loyalty_min_purchase'])
        ));
    }

    // Apply earn + redeem for a completed sale. Called after the sale is saved.
    public function processSale() {
        $sale_id    = $this->input->post('sale_id');
        $cus_id     = $this->input->post('cus_id');
        $grandtotal = floatval($this->input->post('grandtotal'));
        $redeemPts  = floatval($this->input->post('redeem_points'));
        $redeemAmt  = floatval($this->input->post('redeem_amount'));

        if (!$this->Loyalty_model->isEnabled() || !$cus_id) {
            echo json_encode(array('success' => false, 'reason' => 'disabled_or_no_customer'));
            return;
        }

        $earnedOut = 0;
        $redeemedOut = 0;

        // Redeem first (was already discounted from the bill on screen)
        if ($redeemPts > 0) {
            $this->Loyalty_model->redeem($cus_id, $redeemPts, $redeemAmt, $sale_id);
            $redeemedOut = $redeemPts;
        }

        // Earn on the amount actually paid (grandtotal already net of redemption)
        $earn = $this->Loyalty_model->calcEarnedPoints($grandtotal);
        if ($earn > 0) {
            $this->Loyalty_model->earn($cus_id, $earn, $sale_id, $grandtotal);
            $earnedOut = $earn;
        }

        // Store snapshot on the sale row if the columns exist
        $fields = $this->db->list_fields('ezy_pos_sale');
        $upd = array();
        if (in_array('sale_loyalty_redeemed', $fields))      $upd['sale_loyalty_redeemed'] = $redeemAmt;
        if (in_array('sale_loyalty_points_used', $fields))   $upd['sale_loyalty_points_used'] = $redeemedOut;
        if (in_array('sale_loyalty_points_earned', $fields)) $upd['sale_loyalty_points_earned'] = $earnedOut;
        if (!empty($upd)) {
            $this->db->where('sale_id', $sale_id);
            $this->db->update('ezy_pos_sale', $upd);
        }

        echo json_encode(array('success' => true, 'earned' => $earnedOut, 'redeemed' => $redeemedOut));
    }

    // Manual point adjustment from the admin page
    public function adjust() {
        if (!$this->_isAdmin()) { echo json_encode(array('success' => false)); return; }
        $cus_id = $this->input->post('cus_id');
        $points = floatval($this->input->post('points'));
        $note   = $this->input->post('note');
        $bal = $this->Loyalty_model->adjust($cus_id, $points, $note);
        echo json_encode(array('success' => true, 'balance' => $bal));
    }
}
