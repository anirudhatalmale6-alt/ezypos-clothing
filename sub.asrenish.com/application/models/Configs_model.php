<?php
class Configs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * New Method: Fetch configuration value by config_id and config_key.
     * This is isolated and will not affect existing methods.
     */
    public function fetch_config_value($config_id, $config_key) {
        $this->db->select('config_value');
        $this->db->from('ezy_pos_config2');
        if ($config_id !== null) {
            $this->db->where('config_id', $config_id);
        }
        $this->db->where('config_key', $config_key);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->config_value;
        }
        return null;
    }

    // Existing methods remain intact
    public function getConfigName() {     
        $this->db->select('config_value');
        $this->db->from('ezy_pos_config2');    
        $this->db->where('config_key', 'name');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function getConfigAdd1() {     
        $this->db->select('config_value');
        $this->db->from('ezy_pos_config2');    
        $this->db->where('config_key', 'addressLine1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function getConfigAdd2() {     
        $this->db->select('config_value');
        $this->db->from('ezy_pos_config2');    
        $this->db->where('config_key', 'addressLine2');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function getConfigTel() {     
        $this->db->select('config_value');
        $this->db->from('ezy_pos_config2');    
        $this->db->where('config_key', 'tel');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function getConfigMob() {     
        $this->db->select('config_value');
        $this->db->from('ezy_pos_config2');    
        $this->db->where('config_key', 'mob');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function getConfigFoot() {     
        $this->db->select('config_value');
        $this->db->from('ezy_pos_config2');    
        $this->db->where('config_key', 'footer');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }
}
