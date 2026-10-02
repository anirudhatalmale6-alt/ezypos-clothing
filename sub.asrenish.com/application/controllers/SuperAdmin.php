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
 * The provider login, and nobody else. Super Admin is a rank above the shop's
 * administrator: the administrator runs the shop, the provider decides which
 * parts of the system the shop has at all. There is no tick box for it on the
 * user form, so it cannot be handed to a shop user by accident.
 *
 * One exception, and only one: until a provider login has been created, an
 * administrator can get in to create it. Otherwise a system that has just
 * been upgraded would have no way in at all. The moment a provider login
 * exists that door closes for good.
 */
class SuperAdmin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('username')) {
            redirect('login');
        }
        // Not require_priv() - there is no privilege for this on purpose.
        require_super();
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
            'saved'          => $this->session->flashdata('sa_saved'),
            'superMsg'       => $this->session->flashdata('sa_super_msg'),
            'superErr'       => $this->session->flashdata('sa_super_err'),
            'superUser'      => $this->_superAdmin(),
            'bootstrap'      => ! super_admin_exists()
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

    /** The provider login itself, if one has been made. */
    protected function _superAdmin()
    {
        if ( ! in_array('user_is_super', $this->db->list_fields('ezy_pos_users'))) { return null; }
        return $this->db->where('user_is_super', 1)->get('ezy_pos_users')->row();
    }

    /**
     * Create the provider login, or change its password.
     *
     * No account and no password is created by the migration on purpose -
     * a shipped default password is a door left open on every shop that
     * installs this. The provider sets their own, once, here.
     */
    public function saveSuper()
    {
        if ( ! in_array('user_is_super', $this->db->list_fields('ezy_pos_users'))) {
            $this->session->set_flashdata('sa_super_err',
                'The provider login column is not there yet. Run step 13 in migrate.php.');
            redirect('super-admin');
        }

        $name     = trim((string)$this->input->post('su_name'));
        $username = trim((string)$this->input->post('su_username'));
        $pass     = (string)$this->input->post('su_password');
        $confirm  = (string)$this->input->post('su_password2');
        $existing = $this->_superAdmin();

        if ($username === '' && ! $existing) {
            $this->session->set_flashdata('sa_super_err', 'Choose a username for the provider login.');
            redirect('super-admin');
        }
        if ($pass !== '' && $pass !== $confirm) {
            $this->session->set_flashdata('sa_super_err', 'The two passwords do not match. Nothing has been changed.');
            redirect('super-admin');
        }
        if ( ! $existing && strlen($pass) < 8) {
            $this->session->set_flashdata('sa_super_err',
                'Give the provider login a password of at least 8 characters. This account outranks every other one on the system.');
            redirect('super-admin');
        }
        if ($pass !== '' && strlen($pass) < 8) {
            $this->session->set_flashdata('sa_super_err', 'The new password must be at least 8 characters.');
            redirect('super-admin');
        }

        // A username already in use would collide with a shop login.
        if ($username !== '') {
            $clash = $this->db->where('user_username', $username);
            if ($existing) { $this->db->where('user_id !=', $existing->user_id); }
            if ($this->db->count_all_results('ezy_pos_users') > 0) {
                $this->session->set_flashdata('sa_super_err',
                    'That username is already taken by another login. Nothing has been changed.');
                redirect('super-admin');
            }
        }

        if ($existing) {
            $update = array();
            if ($name !== '')     { $update['user_name'] = $name; }
            if ($username !== '') { $update['user_username'] = $username; }
            if ($pass !== '')     { $update['user_password'] = md5($pass); }
            if ($update) {
                $this->db->where('user_id', $existing->user_id)
                         ->update('ezy_pos_users', $update);
            }
            $this->session->set_flashdata('sa_super_msg', 'Provider login updated.');
            redirect('super-admin');
        }

        // Creating it. It is an administrator as well as the provider, so that
        // every existing "is this an admin" check in the system passes - the
        // flag is what makes it the provider on top of that.
        $this->db->insert('ezy_pos_users', array(
            'user_username' => $username,
            'user_name'     => ($name !== '' ? $name : 'Super Admin'),
            'user_password' => md5($pass),
            'user_role'     => 1,
            'user_status'   => 1,
            'user_is_super' => 1
        ));
        $newId = $this->db->insert_id();
        if ( ! $newId) {
            $this->session->set_flashdata('sa_super_err', 'The login could not be created.');
            redirect('super-admin');
        }

        // The login query joins the privileges table, so without a row here
        // the new account could not sign in at all.
        $priv = array('priv_userid' => $newId);
        foreach ($this->db->list_fields('ezy_pos_privileges') as $col) {
            if ($col === 'priv_id' || $col === 'priv_userid') { continue; }
            $priv[$col] = 1;
        }
        $this->db->insert('ezy_pos_privileges', $priv);

        // From this moment the administrator who created it can no longer open
        // this page - that is the whole point. Sending them back to it would
        // show them a 404 and no explanation, so they are signed out and told
        // what happened on the login screen.
        // Signed out by clearing the keys that mark someone as logged in, NOT by
        // destroying the session - a destroyed session takes the message with it
        // and they would arrive at the login screen with no explanation at all.
        $this->session->set_flashdata('provider_created',
            'Provider login created. Sign in as "'.$username.'" to reach Super Admin Settings. '
          . 'The shop administrators can no longer see that page.');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('userrole');
        $this->session->unset_userdata('is_super');
        redirect('login');
    }

    /** Every payment method, on or off - this page is where that is decided. */
    protected function _paymentMethods()
    {
        if ( ! $this->db->table_exists('ezy_pos_payment_methods')) { return array(); }
        return $this->db->order_by('pm_name', 'asc')
                        ->get('ezy_pos_payment_methods')->result();
    }
}
