<?php
class DeliveryCompany extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        $this->load->model('DeliveryCompany_model');
        $this->load->model('Configs_model');
    }

    public function manage() {
        $data1['title'] = 'Delivery Companies';
        $data1['config'] = $this->Configs_model->getConfigName();
        $data = array(
            'companies' => $this->DeliveryCompany_model->getAll()
        );
        $this->load->view('templates/header', $data1);
        $this->load->view('masters/delivery_companies', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    public function add() {
        $data = array(
            'dc_name' => $this->input->post('name'),
            'dc_contact' => $this->input->post('contact')
        );
        $id = $this->DeliveryCompany_model->add($data);
        echo json_encode(array('success' => true, 'id' => $id));
    }

    public function update() {
        $id = $this->input->post('dc_id');
        $data = array(
            'dc_name' => $this->input->post('name'),
            'dc_contact' => $this->input->post('contact'),
            'dc_status' => $this->input->post('status')
        );
        $this->DeliveryCompany_model->update($id, $data);
        echo json_encode(array('success' => true));
    }

    public function delete() {
        $id = $this->input->post('dc_id');
        $this->DeliveryCompany_model->delete($id);
        echo json_encode(array('success' => true));
    }

    public function getActive() {
        $companies = $this->DeliveryCompany_model->getActive();
        echo json_encode($companies);
    }
}
