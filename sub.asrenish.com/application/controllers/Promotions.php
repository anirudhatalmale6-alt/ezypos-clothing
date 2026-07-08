<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Promotions (Point 9)
 */
class Promotions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        $this->load->model('Promotions_model');
        $this->load->model('Configs_model');
        $this->load->model('Items_model');
        $this->load->model('Categories_model');
        $this->load->model('Sales_model');
    }

    private function _isAdmin() {
        return isset($_SESSION['userrole']) && $_SESSION['userrole'] == 1;
    }

    public function manage() {
        if (!$this->_isAdmin()) { show_error('Access denied', 403); return; }
        $data1['title']  = 'Promotions';
        $data1['config'] = $this->Configs_model->getConfigName();
        $data = array(
            'promotions' => $this->Promotions_model->getAll(),
            'items'      => $this->Items_model->getItems(),
            'categories' => $this->Categories_model->getTypes(),
            'payments'   => $this->Sales_model->getActivePaymentMethods(),
            'ready'      => $this->Promotions_model->tableExists()
        );
        $this->load->view('templates/header', $data1);
        $this->load->view('promotions/manage', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    private function _collectPost() {
        $scope = $this->input->post('scope');
        $target = $this->input->post('target_id');
        if ($scope == 'bill' || $target === '' || $target === null) $target = null;
        $start = $this->input->post('start_date');
        $end   = $this->input->post('end_date');
        return array(
            'promo_name'       => $this->input->post('name'),
            'promo_type'       => $this->input->post('type'),
            'promo_value'      => floatval($this->input->post('value')),
            'promo_scope'      => $scope,
            'promo_target_id'  => $target,
            'promo_min_bill'   => floatval($this->input->post('min_bill')),
            'promo_start_date' => $start ? $start : null,
            'promo_end_date'   => $end ? $end : null,
            'promo_priority'   => intval($this->input->post('priority')),
            'promo_auto'       => $this->input->post('auto') !== null ? intval($this->input->post('auto')) : 1
        );
    }

    public function add() {
        if (!$this->_isAdmin()) { echo json_encode(array('success' => false)); return; }
        $data = $this->_collectPost();
        if (!$data['promo_name'] || $data['promo_value'] <= 0) {
            echo json_encode(array('success' => false, 'msg' => 'Name and value are required')); return;
        }
        $data['promo_status']    = 1;
        $data['promo_createdby'] = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
        $id = $this->Promotions_model->create($data);
        echo json_encode(array('success' => true, 'id' => $id));
    }

    public function edit() {
        if (!$this->_isAdmin()) { echo json_encode(array('success' => false)); return; }
        $id = $this->input->post('promo_id');
        $data = $this->_collectPost();
        $data['promo_status'] = intval($this->input->post('status'));
        $this->Promotions_model->update($id, $data);
        echo json_encode(array('success' => true));
    }

    public function toggle() {
        if (!$this->_isAdmin()) { echo json_encode(array('success' => false)); return; }
        $id = $this->input->post('promo_id');
        $status = intval($this->input->post('status'));
        $this->Promotions_model->toggle($id, $status);
        echo json_encode(array('success' => true));
    }

    // Used by the sales screen to auto-apply promotions
    public function active() {
        echo json_encode($this->Promotions_model->getActiveForSale());
    }

    // Called after a sale is saved to record which promotions applied
    public function recordSale() {
        $sale_id = $this->input->post('sale_id');
        $applied = $this->input->post('applied'); // array of {promo_id,name,scope,discount}
        if (!$sale_id || !is_array($applied)) { echo json_encode(array('success' => false)); return; }
        $total = $this->Promotions_model->recordForSale($sale_id, $applied);
        echo json_encode(array('success' => true, 'total' => $total));
    }
}
