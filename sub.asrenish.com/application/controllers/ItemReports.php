<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reports > Item Stock / Item Sales / Item Purchase. One row per item with
 * code, description, category, subcategory, per-unit sale and cost price and
 * the quantity. See models/ItemReport_model.php for how the prices are worked
 * out. Each uses the permission of the report it sits beside.
 */
class ItemReports extends CI_Controller {

    protected $types = array(
        'stock'    => array('Item Stock Report',    'privRe_stock',          'Available Stock'),
        'sales'    => array('Item Sales Report',    'privRe_itemsales',      'Quantity Sold'),
        'purchase' => array('Item Purchase Report', 'privRe_purchaseReport', 'Quantity Purchased')
    );

    public function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('username')) { redirect('login'); }
        $this->load->database();
        $this->load->model('ItemReport_model', 'ir');
        $this->load->model('Configs_model');
        $this->load->model('Stores_model');
    }

    public function stock()    { $this->_page('stock'); }
    public function sales()    { $this->_page('sales'); }
    public function purchase() { $this->_page('purchase'); }

    protected function _page($type)
    {
        require_priv($this->types[$type][1]);
        $isAdmin = $this->session->userdata('userrole') == 1;
        $stores = $isAdmin ? $this->Stores_model->getStoresOnly()
                           : $this->Stores_model->getStoresOnlyForUser($this->session->userdata('userid'));
        $wh = $isAdmin ? $this->Stores_model->getWarehouse() : false;
        $stores = $stores ? $stores : array();
        if ($wh) { $stores[] = $wh; }
        $data = array('title' => $this->types[$type][0], 'config' => $this->Configs_model->getConfigName(),
                      'type' => $type, 'heading' => $this->types[$type][0], 'qtyLabel' => $this->types[$type][2],
                      'stores' => $stores, 'categories' => $this->ir->categories(), 'subcategories' => $this->ir->subcategories(),
                      'suppliers' => $type === 'purchase' ? $this->ir->suppliers() : array(),
                      'reps' => $type === 'sales' ? $this->ir->reps() : array());
        $this->load->view('templates/header', $data);
        $this->load->view('report/item_reports', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    public function data($type = '')
    {
        if ( ! isset($this->types[$type])) { show_404(); }
        require_priv($this->types[$type][1]);
        $f = array();
        foreach (array('store', 'category', 'subcategory', 'item', 'supplier', 'source', 'rep', 'nonzero') as $k) {
            $f[$k] = (string)$this->input->post($k);
        }
        $from = $this->input->post('from'); $to = $this->input->post('to');
        $f['from'] = strtotime($from) ? date('Y-m-d', strtotime($from)) : date('Y-m-01');
        $f['to']   = strtotime($to)   ? date('Y-m-d', strtotime($to))   : date('Y-m-d');
        $rows = $type === 'stock' ? $this->ir->stock($f) : ($type === 'sales' ? $this->ir->sales($f) : $this->ir->purchases($f));
        $this->output->set_content_type('application/json')->set_output(json_encode($rows));
    }
}
