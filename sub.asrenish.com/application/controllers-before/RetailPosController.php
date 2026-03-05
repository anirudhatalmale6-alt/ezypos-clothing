<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RetailPosController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Check if the user is logged in
        if (!$this->session->userdata('username')) {
            redirect('login'); // Redirect to the login page if not logged in
        }
    }

    public function index() {
        // Load only the retail POS view without the header and footer
        $this->load->view('pages/retail_pos');
    }
}
