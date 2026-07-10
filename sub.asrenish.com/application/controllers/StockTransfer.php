<?php
class StockTransfer extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        // Block direct URL access unless admin or granted Stock Transfers permission
        if ($this->session->userdata('userrole') != 1 && !$this->session->userdata('privStocktransfer')) {
            show_404();
        }
        $this->load->model('StockTransfer_model');
        $this->load->model('Stores_model');
        $this->load->model('Items_model');
        $this->load->model('Configs_model');
        $this->load->model('User_model');
        $this->load->helper('language');
    }

    // ===================== MAIN PAGE =====================

    /**
     * Main stock transfers page (create + list)
     */
    public function index()
    {
        $is_admin = ($_SESSION['userrole'] == 1);
        $user_id  = $_SESSION['userid'];

        // Get stores for dropdowns (admin = all stores, non-admin = assigned stores)
        if ($is_admin) {
            $storeLoc = $this->Stores_model->getStoresOnly();
        } else {
            $storeLoc = $this->Stores_model->getStoresOnlyForUser($user_id);
        }

        $data1 = array(
            'title'  => 'Stock Transfers',
            'config' => $this->Configs_model->getConfigName()
        );
        $data = array(
            'storeLoc'  => $storeLoc ? $storeLoc : array(),
            'is_admin'  => $is_admin,
            'user_id'   => $user_id
        );

        $this->load->view('templates/header', $data1);
        $this->load->view('transactions/stock_transfers', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    // ===================== AJAX: GET ITEMS BY STORE =====================

    /**
     * AJAX: Return items with stock for a given store (for autocomplete).
     * POST: store_id
     */
    public function getItemsByStore()
    {
        $store_id = $this->input->post('store_id');
        if (!$store_id) {
            echo json_encode(array());
            return;
        }
        $items = $this->StockTransfer_model->getItemsWithStockByStore($store_id);
        echo json_encode($items);
    }

    // ===================== AJAX: CREATE TRANSFER =====================

    /**
     * AJAX: Create a new stock transfer.
     * POST: from_store, to_store, notes, items (JSON)
     * Returns: transfer_id or false
     */
    public function createTransfer()
    {
        $from_store = $this->input->post('from_store');
        $to_store   = $this->input->post('to_store');
        $notes      = $this->input->post('notes');
        $items_json = $this->input->post('items');
        $created_by = $_SESSION['userid'];

        if (!$from_store || !$to_store || $from_store == $to_store) {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid store selection'));
            return;
        }

        $items = json_decode($items_json, true);
        if (!$items || count($items) == 0) {
            echo json_encode(array('status' => 'error', 'message' => 'No items to transfer'));
            return;
        }

        // Permission check for non-admin
        if ($_SESSION['userrole'] != 1) {
            $user_stores = $this->StockTransfer_model->getUserStoreIds($_SESSION['userid']);
            if (!in_array($from_store, $user_stores) || !in_array($to_store, $user_stores)) {
                echo json_encode(array('status' => 'error', 'message' => 'You do not have access to one or both stores'));
                return;
            }
        }

        $transfer_id = $this->StockTransfer_model->createTransfer($from_store, $to_store, $notes, $created_by, $items);
        if ($transfer_id) {
            echo json_encode(array('status' => 'success', 'transfer_id' => $transfer_id));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to create transfer'));
        }
    }

    // ===================== AJAX: UPDATE TRANSFER =====================

    /**
     * AJAX: Update a pending transfer.
     * POST: transfer_id, notes, items (JSON)
     */
    public function updateTransfer()
    {
        $transfer_id = $this->input->post('transfer_id');
        $notes       = $this->input->post('notes');
        $items_json  = $this->input->post('items');

        if (!$transfer_id) {
            echo json_encode(array('status' => 'error', 'message' => 'Transfer ID required'));
            return;
        }

        $items = json_decode($items_json, true);
        if (!$items || count($items) == 0) {
            echo json_encode(array('status' => 'error', 'message' => 'No items to transfer'));
            return;
        }

        $result = $this->StockTransfer_model->updateTransfer($transfer_id, $notes, $items);
        if ($result) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update transfer. It may no longer be pending.'));
        }
    }

    // ===================== AJAX: ACCEPT TRANSFER =====================

    /**
     * AJAX: Accept a pending transfer.
     * POST: transfer_id
     */
    public function acceptTransfer()
    {
        $transfer_id = $this->input->post('transfer_id');
        $accepted_by = $_SESSION['userid'];

        if (!$transfer_id) {
            echo json_encode(array('status' => 'error', 'message' => 'Transfer ID required'));
            return;
        }

        // Permission check: only destination store users (or admin) can accept
        if ($_SESSION['userrole'] != 1) {
            $transfer = $this->StockTransfer_model->getTransferById($transfer_id);
            if (!$transfer) {
                echo json_encode(array('status' => 'error', 'message' => 'Transfer not found'));
                return;
            }
            $user_stores = $this->StockTransfer_model->getUserStoreIds($_SESSION['userid']);
            if (!in_array($transfer->transfer_to_store, $user_stores)) {
                echo json_encode(array('status' => 'error', 'message' => 'Only the receiving store can accept this transfer'));
                return;
            }
        }

        $result = $this->StockTransfer_model->acceptTransfer($transfer_id, $accepted_by);
        if ($result) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to accept transfer'));
        }
    }

    // ===================== AJAX: REJECT TRANSFER =====================

    /**
     * AJAX: Reject a pending transfer.
     * POST: transfer_id
     */
    public function rejectTransfer()
    {
        $transfer_id = $this->input->post('transfer_id');
        $rejected_by = $_SESSION['userid'];

        if (!$transfer_id) {
            echo json_encode(array('status' => 'error', 'message' => 'Transfer ID required'));
            return;
        }

        // Permission check: only destination store users (or admin) can reject
        if ($_SESSION['userrole'] != 1) {
            $transfer = $this->StockTransfer_model->getTransferById($transfer_id);
            if (!$transfer) {
                echo json_encode(array('status' => 'error', 'message' => 'Transfer not found'));
                return;
            }
            $user_stores = $this->StockTransfer_model->getUserStoreIds($_SESSION['userid']);
            if (!in_array($transfer->transfer_to_store, $user_stores)) {
                echo json_encode(array('status' => 'error', 'message' => 'Only the receiving store can reject this transfer'));
                return;
            }
        }

        $result = $this->StockTransfer_model->rejectTransfer($transfer_id, $rejected_by);
        if ($result) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to reject transfer'));
        }
    }

    // ===================== AJAX: GET ALL TRANSFERS =====================

    /**
     * AJAX: Get all transfers for the DataTable.
     */
    public function getAllTransfers()
    {
        $is_admin = ($_SESSION['userrole'] == 1);
        $user_id  = $_SESSION['userid'];
        $transfers = $this->StockTransfer_model->getAllTransfers($user_id, $is_admin);
        echo json_encode($transfers ? $transfers : array());
    }

    // ===================== AJAX: GET TRANSFER DETAILS =====================

    /**
     * AJAX: Get single transfer details + items.
     * POST: transfer_id
     */
    public function getTransferDetails()
    {
        $transfer_id = $this->input->post('transfer_id');
        if (!$transfer_id) {
            echo json_encode(array('status' => 'error', 'message' => 'Transfer ID required'));
            return;
        }

        $transfer = $this->StockTransfer_model->getTransferById($transfer_id);
        $items    = $this->StockTransfer_model->getTransferItems($transfer_id);

        if (!$transfer) {
            echo json_encode(array('status' => 'error', 'message' => 'Transfer not found'));
            return;
        }

        echo json_encode(array(
            'status'   => 'success',
            'transfer' => $transfer,
            'items'    => $items ? $items : array()
        ));
    }

    // ===================== PRINT INVOICE =====================

    /**
     * Print transfer receipt/invoice in a popup window.
     */
    public function print_invoice($transfer_id)
    {
        $transfer = $this->StockTransfer_model->getTransferById($transfer_id);
        $items    = $this->StockTransfer_model->getTransferItems($transfer_id);

        if (!$transfer) {
            echo 'Transfer not found';
            return;
        }

        $data = array(
            'transfer'  => $transfer,
            'items'     => $items ? $items : array(),
            'comName'   => $this->Configs_model->getConfigName(),
            'addLine1'  => $this->Configs_model->getConfigAdd1(),
            'addLine2'  => $this->Configs_model->getConfigAdd2(),
            'telephone' => $this->Configs_model->getConfigTel()
        );

        $this->load->view('transactions/transfer_invoice', $data);
    }
}
