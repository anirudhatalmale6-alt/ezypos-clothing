<?php
class BarcodeApi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Items_model');
        $this->load->model('Configs_model');
        $this->load->database();
    }

    private function _sendJson($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, X-API-Key');
        echo json_encode($data);
        exit;
    }

    private function _authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $this->_sendJson(array('status' => 'ok'));
        }

        $apiKey = '';
        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            $apiKey = $_SERVER['HTTP_X_API_KEY'];
        } elseif ($this->input->get('api_key')) {
            $apiKey = $this->input->get('api_key');
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'];
            if (strpos($auth, 'Bearer ') === 0) {
                $apiKey = substr($auth, 7);
            }
        }

        if (empty($apiKey)) {
            $this->_sendJson(array('error' => 'API key required. Pass via X-API-Key header, Authorization: Bearer <key>, or ?api_key= query parameter.'), 401);
        }

        $storedKey = $this->Configs_model->fetch_config_value(null, 'labeljoy_api_key');
        if (empty($storedKey) || $apiKey !== $storedKey) {
            $this->_sendJson(array('error' => 'Invalid API key.'), 403);
        }
    }

    // GET /barcode-api/items - List all active items with barcode data
    public function items()
    {
        $this->_authenticate();

        $search = $this->input->get('search');
        $category = $this->input->get('category');
        $store_id = $this->input->get('store_id');

        $this->db->select('i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice, i.itm_brand, i.itm_category, i.itm_uom, c.cat_name');
        $this->db->from('ezy_pos_items i');
        $this->db->join('ezy_pos_categories c', 'i.itm_category = c.cat_id', 'left');
        $this->db->where('i.itm_status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('i.itm_code', $search);
            $this->db->or_like('i.itm_name', $search);
            $this->db->or_like('i.itm_brand', $search);
            $this->db->group_end();
        }
        if ($category) {
            $this->db->where('i.itm_category', intval($category));
        }

        $this->db->order_by('i.itm_name', 'ASC');
        $query = $this->db->get();

        $items = array();
        foreach ($query->result() as $row) {
            $stock = 0;
            if ($store_id) {
                $sq = $this->db->select('stock_qty')->where('stock_itm_id', $row->itm_id)->where('stock_store_id', intval($store_id))->get('ezy_pos_stock');
            } else {
                $sq = $this->db->select('SUM(stock_qty) as stock_qty')->where('stock_itm_id', $row->itm_id)->get('ezy_pos_stock');
            }
            $sr = $sq->row();
            if ($sr) $stock = floatval($sr->stock_qty);

            $items[] = array(
                'item_id'       => intval($row->itm_id),
                'item_code'     => $row->itm_code,
                'barcode_value' => $row->itm_code,
                'item_name'     => $row->itm_name,
                'selling_price' => floatval($row->itm_sellingprice),
                'brand'         => $row->itm_brand,
                'category'      => $row->cat_name ?: '',
                'uom'           => $row->itm_uom,
                'stock_qty'     => $stock
            );
        }

        $this->_sendJson(array(
            'success' => true,
            'count'   => count($items),
            'items'   => $items
        ));
    }

    // GET /barcode-api/item/{id} - Get single item barcode data
    public function item($id = 0)
    {
        $this->_authenticate();

        if (!$id) {
            $this->_sendJson(array('error' => 'Item ID required.'), 400);
        }

        $this->db->select('i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice, i.itm_brand, i.itm_category, i.itm_uom, c.cat_name');
        $this->db->from('ezy_pos_items i');
        $this->db->join('ezy_pos_categories c', 'i.itm_category = c.cat_id', 'left');
        $this->db->where('i.itm_id', intval($id));
        $this->db->where('i.itm_status', 1);
        $row = $this->db->get()->row();

        if (!$row) {
            $this->_sendJson(array('error' => 'Item not found.'), 404);
        }

        $sq = $this->db->select('SUM(stock_qty) as stock_qty')->where('stock_itm_id', $row->itm_id)->get('ezy_pos_stock');
        $sr = $sq->row();
        $stock = $sr ? floatval($sr->stock_qty) : 0;

        $this->_sendJson(array(
            'success' => true,
            'item' => array(
                'item_id'       => intval($row->itm_id),
                'item_code'     => $row->itm_code,
                'barcode_value' => $row->itm_code,
                'item_name'     => $row->itm_name,
                'selling_price' => floatval($row->itm_sellingprice),
                'brand'         => $row->itm_brand,
                'category'      => $row->cat_name ?: '',
                'uom'           => $row->itm_uom,
                'stock_qty'     => $stock
            )
        ));
    }

    // POST /barcode-api/batch - Get barcode data for a batch of items with quantities
    // Body: {"items": [{"item_id": 1, "quantity": 10}, {"item_id": 2, "quantity": 20}]}
    // OR:   {"items": [{"item_code": "ITEM001", "quantity": 10}]}
    public function batch()
    {
        $this->_authenticate();

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['items']) || !is_array($input['items'])) {
            $this->_sendJson(array('error' => 'Request body must contain "items" array. Example: {"items": [{"item_id": 1, "quantity": 10}]}'), 400);
        }

        $labels = array();
        $totalLabels = 0;

        foreach ($input['items'] as $req) {
            $qty = isset($req['quantity']) ? intval($req['quantity']) : 1;
            if ($qty < 1) $qty = 1;

            $this->db->select('i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice, i.itm_brand, c.cat_name');
            $this->db->from('ezy_pos_items i');
            $this->db->join('ezy_pos_categories c', 'i.itm_category = c.cat_id', 'left');
            $this->db->where('i.itm_status', 1);

            if (isset($req['item_id'])) {
                $this->db->where('i.itm_id', intval($req['item_id']));
            } elseif (isset($req['item_code'])) {
                $this->db->where('i.itm_code', $req['item_code']);
            } else {
                continue;
            }

            $row = $this->db->get()->row();
            if (!$row) continue;

            $labels[] = array(
                'item_id'       => intval($row->itm_id),
                'item_code'     => $row->itm_code,
                'barcode_value' => $row->itm_code,
                'item_name'     => $row->itm_name,
                'selling_price' => floatval($row->itm_sellingprice),
                'brand'         => $row->itm_brand,
                'category'      => $row->cat_name ?: '',
                'label_count'   => $qty
            );
            $totalLabels += $qty;
        }

        $this->_sendJson(array(
            'success'      => true,
            'total_items'  => count($labels),
            'total_labels' => $totalLabels,
            'labels'       => $labels
        ));
    }

    // GET /barcode-api/batch-flat?items=1:10,2:20 - Flat label list (one row per label)
    // LabelJoy may need one row per printed label for template binding
    public function batch_flat()
    {
        $this->_authenticate();

        $itemsParam = $this->input->get('items');
        $input = null;

        if ($itemsParam) {
            $pairs = explode(',', $itemsParam);
            $requestItems = array();
            foreach ($pairs as $pair) {
                $parts = explode(':', trim($pair));
                if (count($parts) == 2) {
                    $requestItems[] = array('item_id' => intval($parts[0]), 'quantity' => intval($parts[1]));
                }
            }
            $input = array('items' => $requestItems);
        } else {
            $input = json_decode(file_get_contents('php://input'), true);
        }

        if (!$input || !isset($input['items']) || !is_array($input['items'])) {
            $this->_sendJson(array('error' => 'Pass items as query ?items=ID:QTY,ID:QTY or POST body {"items":[{"item_id":1,"quantity":10}]}'), 400);
        }

        $flatLabels = array();
        foreach ($input['items'] as $req) {
            $qty = isset($req['quantity']) ? intval($req['quantity']) : 1;
            if ($qty < 1) $qty = 1;

            $this->db->select('i.itm_id, i.itm_code, i.itm_name, i.itm_sellingprice, i.itm_brand, c.cat_name');
            $this->db->from('ezy_pos_items i');
            $this->db->join('ezy_pos_categories c', 'i.itm_category = c.cat_id', 'left');
            $this->db->where('i.itm_status', 1);

            if (isset($req['item_id'])) {
                $this->db->where('i.itm_id', intval($req['item_id']));
            } elseif (isset($req['item_code'])) {
                $this->db->where('i.itm_code', $req['item_code']);
            } else {
                continue;
            }

            $row = $this->db->get()->row();
            if (!$row) continue;

            for ($i = 0; $i < $qty; $i++) {
                $flatLabels[] = array(
                    'item_code'     => $row->itm_code,
                    'barcode_value' => $row->itm_code,
                    'item_name'     => $row->itm_name,
                    'selling_price' => floatval($row->itm_sellingprice),
                    'brand'         => $row->itm_brand,
                    'category'      => $row->cat_name ?: ''
                );
            }
        }

        $this->_sendJson(array(
            'success'      => true,
            'total_labels' => count($flatLabels),
            'labels'       => $flatLabels
        ));
    }

    // GET /barcode-api/categories - List all categories
    public function categories()
    {
        $this->_authenticate();

        $this->db->select('cat_id, cat_name');
        $this->db->from('ezy_pos_categories');
        $this->db->where('cat_status', 1);
        $this->db->order_by('cat_name', 'ASC');
        $query = $this->db->get();

        $cats = array();
        foreach ($query->result() as $row) {
            $cats[] = array(
                'category_id'   => intval($row->cat_id),
                'category_name' => $row->cat_name
            );
        }

        $this->_sendJson(array('success' => true, 'categories' => $cats));
    }

    // GET /barcode-api/stores - List all stores
    public function stores()
    {
        $this->_authenticate();

        $this->db->select('store_id, store_name');
        $this->db->from('ezy_pos_stores');
        $this->db->where('store_status', 1);
        $this->db->order_by('store_name', 'ASC');
        $query = $this->db->get();

        $stores = array();
        foreach ($query->result() as $row) {
            $stores[] = array(
                'store_id'   => intval($row->store_id),
                'store_name' => $row->store_name
            );
        }

        $this->_sendJson(array('success' => true, 'stores' => $stores));
    }

    // GET /barcode-api/info - API information and documentation
    public function info()
    {
        $this->_sendJson(array(
            'api'     => 'EzyPOS Barcode API for LabelJoy',
            'version' => '1.0',
            'endpoints' => array(
                array(
                    'method' => 'GET',
                    'path'   => '/barcode-api/items',
                    'params' => 'search, category, store_id (all optional)',
                    'description' => 'List all active items with barcode data'
                ),
                array(
                    'method' => 'GET',
                    'path'   => '/barcode-api/item/{id}',
                    'description' => 'Get single item barcode data'
                ),
                array(
                    'method' => 'POST',
                    'path'   => '/barcode-api/batch',
                    'body'   => '{"items": [{"item_id": 1, "quantity": 10}, {"item_code": "ITEM001", "quantity": 20}]}',
                    'description' => 'Get barcode data for a batch with label counts per item'
                ),
                array(
                    'method' => 'GET/POST',
                    'path'   => '/barcode-api/batch-flat',
                    'params' => 'GET: ?items=1:10,2:20  |  POST body same as /batch',
                    'description' => 'Get flat label list (one row per printed label) for LabelJoy template binding'
                ),
                array(
                    'method' => 'GET',
                    'path'   => '/barcode-api/categories',
                    'description' => 'List all active categories'
                ),
                array(
                    'method' => 'GET',
                    'path'   => '/barcode-api/stores',
                    'description' => 'List all stores'
                )
            ),
            'authentication' => 'Pass API key via X-API-Key header, Authorization: Bearer <key>, or ?api_key= query parameter'
        ));
    }

    // Generate/regenerate API key (admin only, session-authenticated)
    public function generate_key()
    {
        if (!$this->session->userdata('username') || $this->session->userdata('userrole') != 1) {
            $this->_sendJson(array('error' => 'Admin login required.'), 403);
        }

        $newKey = bin2hex(openssl_random_pseudo_bytes(24));

        $existing = $this->Configs_model->fetch_config_value(null, 'labeljoy_api_key');
        if ($existing !== null) {
            $this->db->where('config_key', 'labeljoy_api_key');
            $this->db->update('ezy_pos_config2', array('config_value' => $newKey));
        } else {
            $this->db->insert('ezy_pos_config2', array(
                'config_key'   => 'labeljoy_api_key',
                'config_value' => $newKey
            ));
        }

        $this->_sendJson(array('success' => true, 'api_key' => $newKey));
    }

    // Get current API key (admin only, session-authenticated)
    public function get_key()
    {
        if (!$this->session->userdata('username') || $this->session->userdata('userrole') != 1) {
            $this->_sendJson(array('error' => 'Admin login required.'), 403);
        }

        $key = $this->Configs_model->fetch_config_value(null, 'labeljoy_api_key');
        $this->_sendJson(array('success' => true, 'api_key' => $key ?: ''));
    }

    // Admin settings page for LabelJoy API
    public function settings_page()
    {
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        if ($this->session->userdata('userrole') != 1) {
            show_404();
        }

        $data['title'] = 'LabelJoy API Settings';
        $data['config'] = $this->Configs_model->getConfigName();

        $this->load->view('templates/header', $data);
        $this->load->view('settings/barcode_api_settings');
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }
}
