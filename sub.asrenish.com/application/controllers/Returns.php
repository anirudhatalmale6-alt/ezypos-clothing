<?php
class Returns extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        // Block direct URL access unless admin or granted Returns/Exchanges permission
        if ($this->session->userdata('userrole') != 1
            && !$this->session->userdata('privReturns')
            && !$this->session->userdata('privExchanges')) {
            show_404();
        }
        $this->load->model('Returns_model');
        $this->load->model('Configs_model');
        $this->load->model('User_model');
        $this->load->model('Items_model');
        $this->load->model('Stores_model');
        $this->load->model('Sales_model');
    }

    // ===================== MAIN PAGE: PROCESS RETURN =====================

    /**
     * Main page - process return/exchange form
     */
    public function index()
    {
        $data1['title'] = 'Returns & Exchanges';
        $data1['config'] = $this->Configs_model->getConfigName();
        // Load stores for return location dropdown
        if($_SESSION['userrole'] == 1){
            $stores = $this->Stores_model->getStoresOnly();
        } else {
            $stores = $this->Stores_model->getStoresOnlyForUser($_SESSION['userid']);
        }
        $data = array(
            'items' => $this->Items_model->getItems(),
            'stores' => $stores ? $stores : array(),
            // Same method list as the Sales screen, so an exchange top-up can be
            // collected by card / cheque / any configured method.
            'paymentMethods' => $this->Sales_model->getActivePaymentMethods()
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
        $entered = $this->input->post('sale_id');
        if (!$entered) {
            echo json_encode(array('status' => 'error', 'message' => 'Sale ID is required'));
            return;
        }
        // Counter staff type whatever is printed on the bill - HG1-0007, an old
        // AS00 number, or a bare id. Resolve all three to the real sale id.
        $sale_id = bill_no_to_sale_id($entered);

        $sale = $sale_id ? $this->Returns_model->getSaleWithCustomer($sale_id) : false;
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
        $type           = $this->input->post('return_type');
        $return_items_raw   = $this->input->post('return_items');
        $exchange_items_raw = $this->input->post('exchange_items');
        $reason         = $this->input->post('reason');

        // Decode JSON strings from JS
        $return_items = is_string($return_items_raw) ? json_decode($return_items_raw, true) : $return_items_raw;
        $exchange_items = is_string($exchange_items_raw) ? json_decode($exchange_items_raw, true) : $exchange_items_raw;

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

        $sale_store_id = isset($sale->store_id) ? $sale->store_id : 0;
        // Return store: where the return is being processed (may differ from sale store for cross-store returns)
        $return_store_id = $this->input->post('return_store_id');
        if(!$return_store_id) $return_store_id = $sale_store_id;
        $store_id = $return_store_id;

        // 1. Calculate totals
        //    The return screen posts each line as 'return_amount'; accept 'total' too
        //    so older callers keep working. (Reading only 'total' made every return
        //    record a refund of 0, which is why the reports disagreed.)
        $return_total = 0;
        foreach ($return_items as $ri) {
            if (isset($ri['return_amount'])) {
                $return_total += floatval($ri['return_amount']);
            } elseif (isset($ri['total'])) {
                $return_total += floatval($ri['total']);
            }
        }

        $exchange_total = 0;
        if ($exchange_items && is_array($exchange_items)) {
            foreach ($exchange_items as $ei) {
                $exchange_total += floatval($ei['total']);
            }
        }
        $exchange_total = round($exchange_total, 2);

        // Discount on the exchange as a whole - the same field, and the same
        // rule, as the bill discount on a sale. Worked out here rather than
        // trusting the figure the screen posted.
        $exc_dis_in   = floatval($this->input->post('exchange_discount_input'));
        $exc_dis_type = ($this->input->post('exchange_discount_type') === 'percentage') ? 'percentage' : 'flat';
        $exchange_discount = 0;
        if ($exc_dis_in > 0 && $exchange_total > 0) {
            if ($exc_dis_type === 'percentage') {
                if ($exc_dis_in > 100) { $exc_dis_in = 100; }
                $exchange_discount = round($exchange_total * $exc_dis_in / 100, 2);
            } else {
                $exchange_discount = min(round($exc_dis_in, 2), $exchange_total);
            }
            $exchange_total = round($exchange_total - $exchange_discount, 2);
            if ($exchange_total < 0) { $exchange_total = 0; }
        }

        // Net amount: positive = refund to customer, negative = customer pays difference
        $net_amount = $return_total - $exchange_total;

        // Refund mode. Only meaningful when money is owed back to the customer
        // (net_amount > 0): either cash was handed over the counter, or the value
        // was left on the bill as store credit for them to spend later.
        $refund_mode = $this->input->post('refund_mode');
        if ($refund_mode !== 'store_credit') { $refund_mode = 'cash'; }
        if ($net_amount <= 0) { $refund_mode = 'cash'; }   // customer paid us; nothing to leave as credit

        // 2. Create the return record
        $ret_data = array(
            'sale_id'         => $sale_id,
            'type'            => $type,
            'refund_amount'   => $return_total,
            'exchange_amount' => $exchange_total,
            'net_amount'      => $net_amount,
            'reason'          => $reason ? $reason : '',
            'return_store_id' => $return_store_id,
            'refund_mode'     => $refund_mode,
            'exchange_discount'      => $exchange_discount,
            'exchange_discount_type' => $exc_dis_type
        );
        $ret_id = $this->Returns_model->createReturn($ret_data);
        if (!$ret_id) {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to create return record. Please ensure the returns table exists.'));
            return;
        }

        // 3. Add return items + increase stock for each
        foreach ($return_items as $ri) {
            $item_id = $ri['item_id'];
            $qty = isset($ri['return_qty']) ? $ri['return_qty'] : (isset($ri['qty']) ? $ri['qty'] : 0);
            $price = isset($ri['original_price']) ? $ri['original_price'] : (isset($ri['price']) ? $ri['price'] : 0);
            $total = isset($ri['return_amount']) ? $ri['return_amount'] : (isset($ri['total']) ? $ri['total'] : 0);
            $discount = isset($ri['discount']) ? $ri['discount'] : 0;

            // Look up item name from DB
            $item_name = '';
            $item_row = $this->db->get_where('ezy_pos_items', array('itm_id' => $item_id))->row();
            if ($item_row) {
                $item_name = $item_row->itm_name;
            }

            $ri_data = array(
                'ret_id'    => $ret_id,
                'item_id'   => $item_id,
                'item_name' => $item_name,
                'qty'       => $qty,
                'price'     => $price,
                'discount'  => $discount,
                'total'     => $total
            );
            $this->Returns_model->addReturnItem($ri_data);
            $this->Returns_model->increaseStock($item_id, $qty, $store_id);
        }

        // 4. If exchange: add exchange items + decrease stock for each
        if ($type == 'exchange' && $exchange_items && is_array($exchange_items)) {
            foreach ($exchange_items as $ei) {
                $item_id = $ei['item_id'];
                $qty = $ei['qty'];
                $price = $ei['price'];
                $total = $ei['total'];

                // Look up item name from DB
                $item_name = '';
                $item_row = $this->db->get_where('ezy_pos_items', array('itm_id' => $item_id))->row();
                if ($item_row) {
                    $item_name = $item_row->itm_name;
                }

                $ei_data = array(
                    'ret_id'    => $ret_id,
                    'item_id'   => $item_id,
                    'item_name' => $item_name,
                    'qty'       => $qty,
                    'price'     => $price,
                    // The line's own discount, which used to be thrown away.
                    'discount'      => isset($ei['discount']) ? floatval($ei['discount']) : 0,
                    'discount_type' => (isset($ei['discount_type']) && $ei['discount_type'] === 'flat') ? 'flat' : 'percentage',
                    'total'     => $total
                );
                $this->Returns_model->addExchangeItem($ei_data);
                $this->Returns_model->decreaseStock($item_id, $qty, $store_id);
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

        // 7. Record how the money moved.
        //    net_amount > 0  -> refund handed back to the customer  (direction 'out')
        //    net_amount < 0  -> customer paid the difference        (direction 'in')
        $direction  = ($net_amount > 0) ? 'out' : 'in';
        $expected   = abs($net_amount);

        if ($refund_mode === 'store_credit') {
            // No money leaves the till. Record one line so the bill and the
            // return history still show what happened, flagged so the Cash Flow
            // report ignores it, and put the value on the customer's account.
            $this->Returns_model->addReturnPayment($ret_id, 'out', 'Store Credit', '', $expected, 0);
            $this->Returns_model->addCustomerCredit($sale_id, $expected);
        } else {
            $payments_raw = $this->input->post('payments');
            $payments = is_string($payments_raw) ? json_decode($payments_raw, true) : $payments_raw;

            $recorded = 0;
            if ($payments && is_array($payments)) {
                foreach ($payments as $p) {
                    $amt = isset($p['amount']) ? floatval($p['amount']) : 0;
                    if ($amt <= 0) continue;
                    $this->Returns_model->addReturnPayment(
                        $ret_id,
                        $direction,
                        isset($p['method']) ? trim($p['method']) : 'Cash',
                        isset($p['reference']) ? trim($p['reference']) : '',
                        $amt
                    );
                    $recorded += $amt;
                }
            }
            // Nothing itemised (or short) -> book the remainder as Cash so the Cash Flow
            // report still balances against the return's net amount.
            $remainder = round($expected - $recorded, 2);
            if ($remainder > 0.001) {
                $this->Returns_model->addReturnPayment($ret_id, $direction, 'Cash', '', $remainder);
            }
        }

        echo json_encode(array(
            'status'         => 'success',
            'return_id'      => $ret_id,
            'return_total'   => $return_total,
            'exchange_total' => $exchange_total,
            'net_amount'     => $net_amount,
            'refund_mode'    => $refund_mode,
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
        // Printed bill number of the original sale, for the modal heading.
        $ret->sale_bill_no = bill_no_by_id($ret->ret_sale_id);

        echo json_encode(array(
            'status'         => 'success',
            'return_info'    => $ret,
            'return_items'   => $return_items ? $return_items : array(),
            'exchange_items' => $exchange_items ? $exchange_items : array()
        ));
    }

    // ===================== AJAX: RETURN HISTORY =====================

    public function getReturnHistory()
    {
        $sale_id = $this->input->post('sale_id');
        if (!$sale_id) {
            echo json_encode(array());
            return;
        }
        $history = $this->Returns_model->getReturnHistory($sale_id);
        echo json_encode($history ? $history : array());
    }

    // ===================== PRINT RETURN BILL =====================

    /**
     * Print return bill / invoice
     */
    public function print_invoice($ret_id)
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
            'exchange_items' => $exchange_items ? $exchange_items : array(),
            // Payment breakdown (method + card/cheque reference) for the bottom of the bill
            'payments'       => $this->Returns_model->getReturnPayments($ret_id)
        );

        $this->load->view('returns/return_invoice', $data);
    }
}
