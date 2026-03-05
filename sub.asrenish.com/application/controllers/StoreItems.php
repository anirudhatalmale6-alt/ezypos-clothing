<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class StoreItems extends CI_Controller
{


    public function __construct() {
        parent::__construct();
        // Check if the user is logged in
        if (!$this->session->userdata('username')) {
            redirect('login'); // Redirect to the login page if not logged in
        }
        $this->load->model('Configs_model');
        $this->load->model('Store_items_model');
        
        
    }



    public function storeItem($page = 'index'){
        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }
        $data['title'] = ucfirst($page); 
        $data['config'] = $this->Configs_model->getConfigName(); 
        
        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
}

    public function warehouse($page = 'warehouse'){
        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }
        $data['title'] = ucfirst($page); 
        $data['config'] = $this->Configs_model->getConfigName(); 
        
        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
}


// Fetch stores for dropdown
public function getStores() {
    $stores = $this->Store_items_model->get_all_stores();
    echo json_encode($stores);
}


public function getStoreItems() {
    $storeItems = $this->Store_items_model->fetch_store_items();
    echo json_encode($storeItems);
}


public function searchGRN() {
    $grnID = $this->input->post('grn_id');
    $result = $this->Store_items_model->search_grn($grnID);
    echo json_encode($result);
}


public function saveStoreItems() {
    $storeData = $this->input->post('storeData');
    if (!empty($storeData)) {
        $this->load->model('Store_items_model');
        $this->Store_items_model->insert_store_items($storeData);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}


 // Fetch all warehouses
 public function getWarehouses() {
    $warehouses = $this->Store_items_model->getAllWarehouses();
    echo json_encode($warehouses);
}

// Delete warehouse by ID
public function deleteWarehouse() {
    $wh_id = $this->input->post('wh_id');
    $result = $this->Store_items_model->deleteWarehouseById($wh_id);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

public function updateWarehouse() {
    $wh_id = $this->input->post('wh_id');
    $updateData = array(
        'wh_name' => $this->input->post('wh_name'),
        'wh_address' => $this->input->post('wh_address'),
        'wh_address2' => $this->input->post('wh_address2'),
        'wh_tel' => $this->input->post('wh_tel'),
        'wh_mobile' => $this->input->post('wh_mobile'),
        'wh_mobile2' => $this->input->post('wh_mobile2'),
        'wh_fax' => $this->input->post('wh_fax'),
        'wh_email' => $this->input->post('wh_email')
    );

    $result = $this->Store_items_model->updateWarehouseById($wh_id, $updateData);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

// Create Warehouse
public function createWarehouse() {
    $this->load->helper('security');

    $data = [
        'wh_name'     => $this->input->post('wh_name', TRUE),
        'wh_address'  => $this->input->post('wh_address', TRUE),
        'wh_address2' => $this->input->post('wh_address2', TRUE),
        'wh_tel'      => $this->input->post('wh_tel', TRUE),
        'wh_mobile'   => $this->input->post('wh_mobile', TRUE),
        'wh_mobile2'  => $this->input->post('wh_mobile2', TRUE),
        'wh_fax'      => $this->input->post('wh_fax', TRUE),
        'wh_email'    => $this->input->post('wh_email', TRUE),
        'wh_status'   => 1, // Active by default
        'wh_createdat'=> date('Y-m-d H:i:s') // Timestamp
    ];

    // Validate required fields
    if (empty($data['wh_name']) || empty($data['wh_address']) || empty($data['wh_tel']) || empty($data['wh_mobile'])) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        return;
    }

    $insert_id = $this->Store_items_model->insertWarehouse($data);
    if ($insert_id) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add warehouse.']);
    }
}

public function updateStoreWarehouse() {
    $grnId = $this->input->post('grn_id');
    $itemName = $this->input->post('item_name');
    $newWarehouseId = $this->input->post('new_warehouse_id');

    if (empty($grnId) || empty($itemName) || empty($newWarehouseId)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
        return;
    }

    $this->load->model('Store_items_model');

    $result = $this->Store_items_model->updateWarehouseForItem($grnId, $itemName, $newWarehouseId);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}


// warehouse functions

public function fetch_itemwise_current_qty_with_warehouse() {
    $storeItems = $this->Store_items_model->fetch_itemwise_current_qty_with_warehouse();
    echo json_encode($storeItems);
}


public function getWarehouseData() {
    $warehouse_id = $this->input->post('warehouse_id');

    if (!$warehouse_id) {
        // Return an error response if warehouse_id is not provided
        echo json_encode(['error' => 'Warehouse ID is required']);
        return;
    }

    $data = $this->Store_items_model->getWarehouseData($warehouse_id);

    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'No data found for this warehouse']);
    }
}



public function getStockByWarehouse() {
    $warehouse_id = $this->input->post('warehouse_id');

    if (!$warehouse_id) {
        echo json_encode(['error' => 'Warehouse ID missing']);
        return;
    }

    // Load model
    $this->load->model('Stock_model');

    $stock = $this->Stock_model->getStockByWarehouse($warehouse_id);

    if ($stock) {
        echo json_encode($stock);
    } else {
        echo json_encode([]);
    }
}
} 

