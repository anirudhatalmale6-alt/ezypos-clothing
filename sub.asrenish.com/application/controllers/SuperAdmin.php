<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Super Admin - the switches that decide which parts of the system this
 * particular shop gets.
 *
 * The same software goes to a clothing shop, a hardware shop and a bakery.
 * They do not all want tailoring orders, or a warehouse, or loyalty points.
 * Nothing is removed for any of them - every feature keeps its code, its
 * tables and its history - a switch here simply decides whether it is
 * reachable.
 *
 * WHO CAN OPEN THIS
 * -----------------
 * Administrators only, and deliberately NOT through the normal permission
 * list. There is no tick box for it on the user form, so it cannot be handed
 * to a shop user by accident. A shop manager is not meant to be able to turn
 * the warehouse off.
 */
class SuperAdmin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('username')) {
            redirect('login');
        }
        // Not require_priv() - there is no privilege for this on purpose.
        if ($this->session->userdata('userrole') != 1) {
            show_404();
        }
        $this->load->model('Configs_model');
    }

    public function index()
    {
        $data = array(
            'title'          => 'Super Admin Settings',
            'config'         => $this->Configs_model->getConfigName(),
            'features'       => feature_defaults(),
            'values'         => feature_all(),
            'paymentMethods' => $this->_paymentMethods(),
            'saved'          => $this->session->flashdata('sa_saved')
        );

        $this->load->view('templates/header', $data);
        $this->load->view('settings/super_admin', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    /**
     * Save the switches, and which payment methods the system offers.
     *
     * Payment methods already had an active flag on their own master page -
     * this writes the same flag, so the two screens cannot disagree and no
     * second source of truth is created.
     */
    public function save()
    {
        feature_save($this->input->post());

        if ($this->db->table_exists('ezy_pos_payment_methods')) {
            $wanted = $this->input->post('pm');
            if ( ! is_array($wanted)) { $wanted = array(); }
            foreach ($this->_paymentMethods() as $pm) {
                $on = in_array((string)$pm->pm_id, array_map('strval', $wanted)) ? 1 : 0;
                $this->db->where('pm_id', $pm->pm_id)
                         ->update('ezy_pos_payment_methods', array('pm_status' => $on));
            }
        }

        $this->session->set_flashdata('sa_saved', 1);
        redirect('super-admin');
    }

    /** Every payment method, on or off - this page is where that is decided. */
    protected function _paymentMethods()
    {
        if ( ! $this->db->table_exists('ezy_pos_payment_methods')) { return array(); }
        return $this->db->order_by('pm_name', 'asc')
                        ->get('ezy_pos_payment_methods')->result();
    }
}
