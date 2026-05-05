<?php
class DeliveryCompany_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function getAll() {
        if (!$this->db->table_exists('ezy_pos_delivery_companies')) return array();
        $this->db->order_by('dc_name', 'ASC');
        return $this->db->get('ezy_pos_delivery_companies')->result();
    }

    public function getActive() {
        if (!$this->db->table_exists('ezy_pos_delivery_companies')) return array();
        $this->db->where('dc_status', 1);
        $this->db->order_by('dc_name', 'ASC');
        return $this->db->get('ezy_pos_delivery_companies')->result();
    }

    public function getById($id) {
        $this->db->where('dc_id', $id);
        return $this->db->get('ezy_pos_delivery_companies')->row();
    }

    public function add($data) {
        $this->db->insert('ezy_pos_delivery_companies', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('dc_id', $id);
        return $this->db->update('ezy_pos_delivery_companies', $data);
    }

    public function delete($id) {
        $this->db->where('dc_id', $id);
        return $this->db->update('ezy_pos_delivery_companies', array('dc_status' => 0));
    }
}
