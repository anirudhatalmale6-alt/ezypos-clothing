<?php
class Returns extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        $this->load->model('Returns_model');
        $this->load->model('Configs_model');
        $this->load->model('User_model');
        $this->load->model('Items_model');
        $this->load->model('Stores_model');
    }

    // ===================== MAIN PAGE: PROCESS RETURN =====================

    /**
     * Main page - process return/exchange form
     */
    public function index()
    {
        $data1['title'] = 'Returns & Exchanges';
        $data1['config'] = $this->Configs_model->getConfigName();
        $data = array(
            'items' => $this->Items_model->getItems()
        );
        $this->load->view('templates/header', $data1);
        $this->load->view('returns/process_return', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    // ===================== AJAX: SALE DETAILS =====================

    /**
     * AJAX: Get sale details for return processing
     * POST: sale_id
     * Returns: sale info + items + return history
     */
    public function getSaleDetails()
    {
        $sale_id = $this->input->post('sale_id');
        if (!$sale_id) {
            echo json_encode(array('status' => 'error', 'message' => 'Sale ID is required'));
            return;
        }

        $sale = $this->Returns_model->getSaleWithCustomer($sale_id);
        if (!$sale) {
            echo json_encode(array('status' => 'error', 'message' => 'Sale not found or inactive'));
            return;
        }

        $items = $this->Returns_model->getSaleItems($sale_id);
        $history = $this->Returns_model->getReturnHistory($sale_id);

        echo json_encode(array(
            'status'  => 'success',
            'sale'    => $sale,
            'items'   => $items ? $items : array(),
            'history' => $history ? $history : array()
        ));
    }

    // ===================== AJAX: SAVE RETURN =====================

    /**
     * AJAX: Save a return / exchange
     * POST: sale_id, type (full_return/partial_return/exchange),
     *        return_items[] (array of {item_id, item_name, qty, price, discount, total}),
     *        exchange_items[] (array of {item_id, item_name, qty, price, discount, total}),
     *        reason
     */
    public function saveReturn()
    {
        $sale_id        = $this->input->post('sale_id');
        $type           = $this->input->post('type');
        $return_items   = $this->input->post('return_items');
        $exchange_items = $this->input->post('exchange_items');
        $reason         = $this->input->post('reason');

        // Validate required fields
        if (!$sale_id || !$type) {
            echo json_encode(array('status' => 'error', 'message' => 'Sale ID and return type are required'));
            return;
        }
        if (!$return_items || !is_array($return_items) || count($return_items) == 0) {
            echo json_encode(array('status' => 'error', 'message' => 'At least one return item is required'));
            return;
        }

        // Verify sale exists
        $sale = $this->Returns_model->getSaleWithCustomer($sale_id);
        if (!$sale) {
            echo json_encode(array('status' => 'error', 'message' => 'Sale not found'));
            return;
        }

        $store_id = isset($sale->store_id) ? $sale->store_id : 0;

        // 1. Calculate totals
        $return_total = 0;
        foreach ($return_items as $ri) {
            $return_total += floatval($ri['total']);
        }

        $exchange_total = 0;
        if ($exchange_items && is_array($exchange_items)) {
            foreach ($exchange_items as $ei) {
                $exchange_total += floatval($ei['total']);
            }
        }

        // Net amount: positive = refund to customer, negative = customer pays difference
        $net_amount = $return_total - $exchange_total;

        // 2. Create the return record
        $ret_data = array(
            'sale_id'         => $sale_id,
            'type'            => $type,
            'refund_amount'   => $return_total,
            'exchange_amount' => $exchange_total,
            'net_amount'      => $net_amount,
            'reason'          => $reason ? $reason : ''
        );
        $ret_id = $this->Returns_model->createReturn($ret_data);
        if (!$ret_id) {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to create return record. Please ensure the returns table exists.'));
            return;
        }

        // 3. Add return items + increase stock for each
        foreach ($return_items as $ri) {
            $ri_data = array(
                'ret_id'    => $ret_id,
                'item_id'   => $ri['item_id'],
                'item_name' => $ri['item_name'],
                'qty'       => $ri['qty'],
                'price'     => $ri['price'],
                'discount'  => isset($ri['discount']) ? $ri['discount'] : 0,
                'total'     => $ri['total']
            );
            $this->Returns_model->addReturnItem($ri_data);
            // Increase stock (returned item goes back to inventory)
            $this->Returns_model->increaseStock($ri['item_id'], $ri['qty'], $store_id);
        }

        // 4. If exchange: add exchange items + decrease stock for each
        if ($type == 'exchange' && $exchange_items && is_array($exchange_items)) {
            foreach ($exchange_items as $ei) {
                $ei_data = array(
                    'ret_id'    => $ret_id,
                    'item_id'   => $ei['item_id'],
                    'item_name' => $ei['item_name'],
                    'qty'       => $ei['qty'],
                    'price'     => $ei['price'],
                    'discount'  => isset($ei['discount']) ? $ei['discount'] : 0,
                    'total'     => $ei['total']
                );
                $this->Returns_model->addExchangeItem($ei_data);
                // Decrease stock (exchange item leaves inventory)
                $this->Returns_model->decreaseStock($ei['item_id'], $ei['qty'], $store_id);
            }
        }

        // 5. Update sale totals
        //    Only reduce grand total by the net refund (if positive)
        if ($net_amount > 0) {
            $this->Returns_model->updateSaleTotals($sale_id, $net_amount);
        }

        // 6. Determine and set return status
        $now = date('Y-m-d H:i:s');
        if ($type == 'full_return') {
            $status = 'refunded';
        } elseif ($type == 'exchange') {
            $status = 'exchanged';
        } else {
            $status = 'partial_refunded';
        }
        $this->Returns_model->updateSaleReturnStatus($sale_id, $status, $now);

        echo json_encode(array(
            'status'         => 'success',
            'ret_id'         => $ret_id,
            'return_total'   => $return_total,
            'exchange_total' => $exchange_total,
            'net_amount'     => $net_amount,
            'message'        => 'Return processed successfully'
        ));
    }

    // ===================== LIST ALL RETURNS =====================

    /**
     * List all returns page
     */
    public function listReturns()
    {
        $data1['title'] = 'Returns History';
        $data1['config'] = $this->Configs_model->getConfigName();
        $returns = $this->Returns_model->getAllReturns();
        $data = array(
            'returns' => $returns ? $returns : array()
        );
        $this->load->view('templates/header', $data1);
        $this->load->view('returns/list_returns', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    // ===================== AJAX: RETURN DETAILS =====================

    /**
     * AJAX: Get full return details for viewing
     * POST: ret_id
     */
    public function getReturnDetails()
    {
        $ret_id = $this->input->post('ret_id');
        if (!$ret_id) {
            echo json_encode(array('status' => 'error', 'message' => 'Return ID is required'));
            return;
        }
        $ret = $this->Returns_model->getReturnDetails($ret_id);
        if (!$ret) {
            echo json_encode(array('status' => 'error', 'message' => 'Return not found'));
            return;
        }
        $return_items   = $this->Returns_model->getReturnItems($ret_id);
        $exchange_items = $this->Returns_model->getExchangeItems($ret_id);

        echo json_encode(array(
            'status'         => 'success',
            'return'         => $ret,
            'return_items'   => $return_items ? $return_items : array(),
            'exchange_items' => $exchange_items ? $exchange_items : array()
        ));
    }

    // ===================== PRINT RETURN BILL =====================

    /**
     * Print return bill / invoice
     */
    public function printReturnBill($ret_id)
    {
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;

        $ret = $this->Returns_model->getReturnDetails($ret_id);
        if (!$ret) {
            show_404();
            return;
        }

        $return_items   = $this->Returns_model->getReturnItems($ret_id);
        $exchange_items = $this->Returns_model->getExchangeItems($ret_id);

        $data = array(
            'comName'        => $this->Configs_model->getConfigName(),
            'addLine1'       => $this->Configs_model->getConfigAdd1(),
            'addLine2'       => $this->Configs_model->getConfigAdd2(),
            'telephone'      => $this->Configs_model->getConfigTel(),
            'mobile'         => $this->Configs_model->getConfigMob(),
            'footer'         => $this->Configs_model->getConfigFoot(),
            'user'           => $this->User_model->getUserName($userid),
            'ret'            => $ret,
            'return_items'   => $return_items ? $return_items : array(),
            'exchange_items' => $exchange_items ? $exchange_items : array()
        );

        $this->load->view('returns/return_invoice', $data);
    }
}
