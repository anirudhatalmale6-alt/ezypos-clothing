<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customer Payment - money a customer pays later against their credit bills.
 * See models/CusReceipt_model.php for what a payment changes and why.
 * The old Customer Payment screen is kept at cus-payment-old.
 */
class CustomerReceipts extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('username')) { redirect('login'); }
        require_priv('privPy_customerPayment');
        $this->load->database();
        $this->load->model('CusReceipt_model', 'rcpt');
        $this->load->model('Configs_model');
        $this->load->model('Stores_model');
    }

    public function index()
    {
        $isAdmin = $this->session->userdata('userrole') == 1;
        $stores = $isAdmin ? $this->Stores_model->getStoresOnly()
                           : $this->Stores_model->getStoresOnlyForUser($this->session->userdata('userid'));
        $data = array(
            'title'     => 'Customer Payment',
            'config'    => $this->Configs_model->getConfigName(),
            'ready'     => $this->rcpt->ready(),
            'customers' => $this->rcpt->customers(),
            'methods'   => $this->rcpt->paymentMethods(),
            'stores'    => $stores ? $stores : array(),
            'recent'    => $this->rcpt->recent(50)
        );
        $this->load->view('templates/header', $data);
        $this->load->view('payment/customer_payment', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    /** What a customer owes. ?cus=ID, or ?bill=printed bill number to start from a bill. */
    public function outstanding()
    {
        $cus = intval($this->input->get('cus'));
        $bill = trim((string)$this->input->get('bill'));
        $saleId = 0;
        if ( ! $cus && $bill !== '') {
            $saleId = intval(bill_no_to_sale_id($bill));
            $row = $saleId ? $this->db->select('sale_cus_id')->get_where('ezy_pos_sale', array('sale_id' => $saleId))->row() : null;
            if ( ! $row) { $this->_json(array('ok' => false, 'msg' => 'No bill with that number.')); return; }
            $cus = (int)$row->sale_cus_id;
        }
        $o = $this->rcpt->outstanding($cus);
        if ( ! $o) { $this->_json(array('ok' => false, 'msg' => 'Customer not found.')); return; }
        $o['ok'] = true; $o['from_bill'] = $saleId;
        $this->_json($o);
    }

    public function save()
    {
        $pays  = json_decode((string)$this->input->post('pays'), true);
        $alloc = json_decode((string)$this->input->post('alloc'), true);
        $res = $this->rcpt->save(
            intval($this->input->post('cus_id')), $this->input->post('date'), intval($this->input->post('store_id')),
            is_array($pays) ? $pays : array(), $this->input->post('discount'),
            is_array($alloc) ? $alloc : array(), $this->input->post('note'), $this->session->userdata('userid'));
        $this->_json(array('ok' => $res[0], 'msg' => $res[1], 'id' => $res[2],
                           'number' => $res[2] ? CusReceipt_model::number($res[2]) : ''));
    }

    /** 80 mm receipt for the customer. */
    public function receipt($id = 0)
    {
        $r = $this->rcpt->receipt($id);
        if ( ! $r) { show_404(); }
        $this->load->view('payment/customer_receipt_print', array(
            'r' => $r, 'number' => CusReceipt_model::number($r->rcpt_id),
            'config' => $this->Configs_model->getConfigName()));
    }

    private function _json($x)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($x));
    }
}
