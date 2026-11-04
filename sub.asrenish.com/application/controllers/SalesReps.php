<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Masters > Sales Representatives. Picked on the sale window; the bill then
 * shows the rep's name where it used to show the cashier's login name.
 * Same permission as the Staff master - reps are staff.
 */
class SalesReps extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('username')) { redirect('login'); }
        require_priv('privstaff');
        $this->load->database();
        $this->load->model('Configs_model');
    }

    public function index()
    {
        $ready = $this->db->table_exists('ezy_pos_sales_reps');
        $data = array('title' => 'Sales Representatives', 'config' => $this->Configs_model->getConfigName(),
                      'ready' => $ready,
                      'reps' => $ready ? $this->db->order_by('rep_status', 'desc')->order_by('rep_name')->get('ezy_pos_sales_reps')->result() : array());
        $this->load->view('templates/header', $data);
        $this->load->view('masters/sales_reps', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    public function save()
    {
        $id = intval($this->input->post('id'));
        $name = trim((string)$this->input->post('name'));
        if ($name === '') { $this->_json(array('ok' => false, 'msg' => 'Enter the name.')); return; }
        $dup = $this->db->where('rep_name', $name)->where('rep_id !=', $id)->count_all_results('ezy_pos_sales_reps');
        if ($dup) { $this->_json(array('ok' => false, 'msg' => 'There is already a sales rep called '.$name.'.')); return; }
        $row = array('rep_name' => substr($name, 0, 120),
                     'rep_phone' => substr(trim((string)$this->input->post('phone')), 0, 40),
                     'rep_status' => $this->input->post('status') === '0' ? 0 : 1);
        if ($id) {
            $this->db->where('rep_id', $id)->update('ezy_pos_sales_reps', $row);
        } else {
            $row['rep_createdat'] = date('Y-m-d H:i:s');
            $this->db->insert('ezy_pos_sales_reps', $row);
            $id = $this->db->insert_id();
        }
        $this->_json(array('ok' => true, 'id' => $id));
    }

    private function _json($x)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($x));
    }
}
