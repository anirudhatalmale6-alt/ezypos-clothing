<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Label printing from the browser, through QZ Tray.
 *
 * The server builds the TSPL (application/libraries/Tspl_label.php - the same
 * builder as the Windows label program, byte for byte), and QZ Tray - a small
 * print agent on the till - hands it to the TSC raw. Windows' page logic,
 * which is what made browser-printed labels walk up the roll, is never
 * involved.
 *
 * QZ Tray asks "allow this site to print?" unless the site signs its requests.
 * This controller signs them: it makes its own key the first time it is
 * needed and keeps it in the database. The matching certificate is offered
 * for download so it can be installed in QZ Tray once, after which printing
 * is silent. The private key never leaves the server and is never in the
 * repository.
 */
class LabelPrint extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('username')) {
            redirect('login');
        }
        // Label printing switched off on the Super Admin page closes this too.
        require_feature('labeljoy');
        require_priv('privLabeljoy');
        // The database is not autoloaded in this application.
        $this->load->database();
        $this->load->model('Configs_model');
        $this->load->library('tspl_label', $this->_settings(), 'tspl');
    }

    /* ---------------------------------------------------------- settings */

    /** Label settings live in ezy_pos_config2 as label_* rows, like everything else. */
    protected function _settings()
    {
        $out = array();
        $rows = $this->db->select('config_key, config_value')
                         ->like('config_key', 'label_', 'after')
                         ->get('ezy_pos_config2')->result();
        foreach ($rows as $r) { $out[substr($r->config_key, 6)] = $r->config_value; }
        return $out;
    }

    public function saveSettings()
    {
        $in = $this->input->post('settings');
        if (is_string($in)) { $in = json_decode($in, true); }
        if ( ! is_array($in)) { $this->_json(array('ok' => false, 'msg' => 'Nothing to save.')); return; }

        // Check the combination - what is saved already plus what changed -
        // before keeping any of it.
        $trial = new Tspl_label(array_merge($this->_settings(), $in));
        $bad = $trial->validate();
        if ($bad) { $this->_json(array('ok' => false, 'msg' => $bad)); return; }

        foreach (Tspl_label::defaults() as $k => $v) {
            if ( ! array_key_exists($k, $in)) { continue; }
            $key = 'label_'.$k;
            $val = substr((string)$in[$k], 0, 200);
            if ($this->db->where('config_key', $key)->count_all_results('ezy_pos_config2') > 0) {
                $this->db->where('config_key', $key)->update('ezy_pos_config2', array('config_value' => $val));
            } else {
                $this->db->insert('ezy_pos_config2', array('config_key' => $key, 'config_value' => $val));
            }
        }
        $this->_json(array('ok' => true, 'settings' => (new Tspl_label($this->_settings()))->settings()));
    }

    /* -------------------------------------------------------------- page */

    public function index()
    {
        $prefill = array();
        $itm  = intval($this->input->get('item'));
        $code = trim((string)$this->input->get('code'));
        if ($itm > 0 || $code !== '') {
            $row = $itm > 0 ? $this->db->get_where('ezy_pos_items', array('itm_id' => $itm))->row()
                            : $this->db->get_where('ezy_pos_items', array('itm_code' => $code))->row();
            if ($row) {
                $prefill[] = array('id' => $row->itm_id, 'code' => $row->itm_code, 'name' => $row->itm_name,
                                   'price' => (float)$row->itm_sellingprice,
                                   'qty' => max(1, intval($this->input->get('qty'))));
            }
        }
        $data = array(
            'title'    => 'Print Labels',
            'config'   => $this->Configs_model->getConfigName(),
            'settings' => $this->tspl->settings(),
            'prefill'  => $prefill
        );
        $this->load->view('templates/header', $data);
        $this->load->view('settings/label_print', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    /** Items for the search box: code, name, price. */
    public function items()
    {
        $term = trim((string)$this->input->get('term'));
        if ($term === '') { $this->_json(array()); return; }
        $like = '%'.$this->db->escape_like_str($term).'%';
        $rows = $this->db->query(
            "SELECT itm_id, itm_code, itm_name, itm_sellingprice FROM ezy_pos_items
             WHERE itm_status = 1 AND (itm_code LIKE ? ESCAPE '!' OR itm_name LIKE ? ESCAPE '!')
             ORDER BY (itm_code = ?) DESC, itm_name LIMIT 25", array($like, $like, $term))->result();
        $out = array();
        foreach ($rows as $r) {
            $out[] = array('id' => (int)$r->itm_id, 'code' => $r->itm_code, 'name' => $r->itm_name,
                           'price' => (float)$r->itm_sellingprice);
        }
        $this->_json($out);
    }

    /** Everything on one GRN, one label per unit received - which is what a shop prints. */
    public function grnItems()
    {
        $ref = trim((string)$this->input->get('grn'));
        $id = ctype_digit($ref) ? intval($ref) : 0;
        if ( ! $id && $ref !== '') {
            $g = $this->db->get_where('ezy_pos_grns', array('grn_code' => $ref))->row();
            if ($g) { $id = (int)$g->grn_id; }
        }
        if ( ! $id) { $this->_json(array('ok' => false, 'msg' => 'No GRN with that number.')); return; }
        $rows = $this->db->query(
            "SELECT i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice, gi.grnitm_quantity
             FROM ezy_pos_grn_item gi JOIN ezy_pos_items i ON i.itm_id = gi.grnitm_itemid
             WHERE gi.grnitm_grn_id = ? ORDER BY gi.grnitm_id", array($id))->result();
        if ( ! $rows) { $this->_json(array('ok' => false, 'msg' => 'That GRN has no items.')); return; }
        $out = array();
        foreach ($rows as $r) {
            $out[] = array('id' => (int)$r->itm_id, 'code' => $r->itm_code, 'name' => $r->itm_name,
                           'price' => (float)$r->itm_sellingprice,
                           'qty' => max(0, (int)round((float)$r->grnitm_quantity)));
        }
        $this->_json(array('ok' => true, 'items' => $out));
    }

    /* --------------------------------------------------------- the job */

    public function build()
    {
        $queue = json_decode((string)$this->input->post('queue'), true);
        if ( ! is_array($queue)) { $queue = array(); }

        // The prices and names come from the item master, not from the browser.
        // A label is something a customer reads a price off.
        $clean = array();
        foreach ($queue as $q) {
            $row = $this->db->get_where('ezy_pos_items', array('itm_id' => intval(isset($q['id']) ? $q['id'] : 0)))->row();
            if ( ! $row) { continue; }
            $clean[] = array('code' => $row->itm_code, 'barcode' => '', 'name' => $row->itm_name,
                             'price' => (float)$row->itm_sellingprice,
                             'qty' => max(0, min(1000, intval(isset($q['qty']) ? $q['qty'] : 0))));
        }
        $this->_json($this->tspl->build($clean));
    }

    public function calibration() { $this->_json(array('tspl' => $this->tspl->calibrationSheet())); }
    public function teach()       { $this->_json(array('tspl' => $this->tspl->teachRoll())); }

    /* ------------------------------------------------- QZ Tray signing */

    /**
     * The signing key. Made the first time it is asked for and kept in its
     * own small table - the settings table's values are only 250 characters
     * and a key is nearly two thousand. Created here if it is not there yet,
     * so printing never depends on a migration step having been run.
     */
    protected function _keys()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS ezy_pos_qz_keys (
                            qz_id INT AUTO_INCREMENT PRIMARY KEY,
                            qz_private_key TEXT NOT NULL,
                            qz_certificate TEXT NOT NULL,
                            qz_created_at DATETIME NOT NULL
                          ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $row = $this->db->order_by('qz_id', 'desc')->limit(1)->get('ezy_pos_qz_keys')->row();
        if ($row) { return $row; }

        if ( ! function_exists('openssl_pkey_new')) { return null; }
        $cfg = array('private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA, 'digest_alg' => 'sha256');
        $key = openssl_pkey_new($cfg);
        if ( ! $key) { return null; }
        $host = preg_replace('/[^A-Za-z0-9.\-]/', '', (string)(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'ezypos'));
        $dn = array('commonName' => ($host ? $host : 'ezypos'), 'organizationName' => 'EzyPOS label printing');
        $csr  = openssl_csr_new($dn, $key, $cfg);
        $cert = openssl_csr_sign($csr, null, $key, 3650, $cfg, random_int(1, PHP_INT_MAX));
        openssl_pkey_export($key, $pem);
        openssl_x509_export($cert, $certPem);
        $this->db->insert('ezy_pos_qz_keys', array(
            'qz_private_key' => $pem, 'qz_certificate' => $certPem, 'qz_created_at' => date('Y-m-d H:i:s')));
        return $this->db->order_by('qz_id', 'desc')->limit(1)->get('ezy_pos_qz_keys')->row();
    }

    /** The public certificate - safe to hand out, it is what QZ Tray checks against. */
    public function qzCert()
    {
        $k = $this->_keys();
        $this->output->set_content_type('text/plain')->set_output($k ? $k->qz_certificate : '');
    }

    /** Sign one QZ Tray request. Only for someone signed in and allowed to print labels. */
    public function qzSign()
    {
        $k = $this->_keys();
        $req = (string)$this->input->post('request');
        if ( ! $k || $req === '') { $this->output->set_status_header(400)->set_output(''); return; }
        $pk = openssl_pkey_get_private($k->qz_private_key);
        openssl_sign($req, $sig, $pk, OPENSSL_ALGO_SHA512);
        $this->output->set_content_type('text/plain')->set_output(base64_encode($sig));
    }

    /** The certificate as a file named the way QZ Tray wants it. */
    public function downloadCert()
    {
        $k = $this->_keys();
        if ( ! $k) { show_error('This server cannot make a signing key (the PHP openssl extension is missing).'); return; }
        $this->output->set_content_type('application/x-x509-ca-cert')
                     ->set_header('Content-Disposition: attachment; filename="override.crt"')
                     ->set_output($k->qz_certificate);
    }

    protected function _json($x)
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($x));
    }
}
