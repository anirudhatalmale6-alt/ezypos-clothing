<?php
class SupReturns extends CI_Controller {
    public function __construct()
    {
            parent::__construct();
            if ( ! $this->session->userdata('username'))
            { 
                redirect('login');
            }
            // else if(!$this->session->userdata('privprofit')==1){
            //         show_404();
            // }
        // Switched off for this shop on the Super Admin page means the page is
        // not there, for anyone - typing the address included. The module's
        // code and its records are untouched; switch it on and it is back.
            require_feature('supplier_return');
            require_priv('privSupreturn');
            $this->load->model('Supreturns_model');
    }

    /* ------------------------------------------------ Supplier Returns page */

    /** All supplier returns, newest first, each with Edit. */
    public function index()
    {
        $this->load->database();
        $this->load->model('Configs_model');
        $data = array('title' => 'Supplier Returns', 'config' => $this->Configs_model->getConfigName(),
                      'returns' => $this->Supreturns_model->listReturns(),
                      'linked' => $this->Supreturns_model->linkedReady());
        $this->_page('listing/supplier_returns', $data);
    }

    /** New return (no id) or edit an existing one. */
    public function form($id = 0)
    {
        $this->load->database();
        $this->load->model('Configs_model');
        $ret = null; $lines = array(); $grn = null;
        if ($id) {
            $ret = $this->Supreturns_model->getReturn($id);
            if ( ! $ret || ! $ret->suprtrn_status) { show_404(); }
            if (intval($ret->suprtrn_grnID)) {
                $grn = $this->Supreturns_model->findGrn($ret->suprtrn_grnID);
                if ($grn) { $lines = $this->Supreturns_model->grnLines($grn, $id); }
            }
        }
        $data = array('title' => $id ? 'Edit Supplier Return' : 'New Supplier Return',
                      'config' => $this->Configs_model->getConfigName(),
                      'ret' => $ret, 'grn' => $grn, 'lines' => $lines,
                      'linked' => $this->Supreturns_model->linkedReady());
        $this->_page('transactions/supplier_return', $data);
    }

    /** The GRN typed in the form: header and returnable lines. */
    public function findGrn()
    {
        $grn = $this->Supreturns_model->findGrn($this->input->get('ref'));
        if ( ! $grn) { $this->_json(array('ok' => false, 'msg' => 'No GRN with that number or code.')); return; }
        $this->_json(array('ok' => true, 'grn' => $grn, 'lines' => $this->Supreturns_model->grnLines($grn)));
    }

    public function save()
    {
        $lines = json_decode((string)$this->input->post('lines'), true);
        if ( ! is_array($lines)) { $lines = array(); }
        $res = $this->Supreturns_model->saveReturn(
            intval($this->input->post('return_id')), $this->input->post('grn_ref'), $lines,
            $this->input->post('note'), $this->session->userdata('userid'));
        $this->_json(array('ok' => $res[0], 'msg' => $res[1], 'id' => $res[2]));
    }

    private function _page($view, $data)
    {
        $this->load->view('templates/header', $data);
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    private function _json($x)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($x));
    }
    public function getGrnItems(){
        $response = $this->Supreturns_model->getGrnItems();
        echo json_encode($response);
    }
    public function getGrnDetails(){
        $response = $this->Supreturns_model->getGrnDetails();
        echo json_encode($response);
    }
    public function addReturn(){
        $response = $this->Supreturns_model->addReturn();
        echo json_encode($response);
    }
    public function addReturnItems(){
        $response = $this->Supreturns_model->addReturnItems();
        echo json_encode($response);
    }
    
}