<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Advance Return.
 *
 * A separate module from Returns / Exchanges - separate page, separate tables,
 * separate permission. Nothing here touches those, and a user holding
 * priv_returns or priv_exchanges does not get in here without priv_advreturn.
 */
class AdvanceReturn extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        // Its own permission, on purpose. Typing the URL gets a 404 like any
        // other page the user is not allowed to open.
        require_priv('privAdvreturn');

        $this->load->model('AdvanceReturn_model');
        $this->load->model('Configs_model');
        $this->load->model('Stores_model');
        $this->load->model('Customers_model');
    }

    protected function _stores()
    {
        if ($this->session->userdata('userrole') == 1) {
            $s = $this->Stores_model->getStoresOnly();
        } else {
            $s = $this->Stores_model->getStoresOnlyForUser($this->session->userdata('userid'));
        }
        return $s ? $s : array();
    }

    // ===================== PAGES =====================

    public function index()
    {
        $this->load->model('Sales_model');
        $data = array(
            'title'     => 'Advance Exchange',
            'config'    => $this->Configs_model->getConfigName(),
            'stores'    => $this->_stores(),
            'customers' => $this->Customers_model->getCustomers(),
            'ready'     => $this->AdvanceReturn_model->tablesExist(),
            'excReady'  => $this->AdvanceReturn_model->exchangeReady(),
            // The same method list the Sales window offers, so the difference
            // on an exchange can be settled exactly the same way.
            'paymentMethods' => $this->Sales_model->getActivePaymentMethods()
        );
        $this->load->view('templates/header', $data);
        $this->load->view('returns/advance_return', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    public function listing()
    {
        $data = array(
            'title'  => 'Advance Exchanges',
            'config' => $this->Configs_model->getConfigName(),
            'stores' => $this->_stores(),
            'ready'  => $this->AdvanceReturn_model->tablesExist()
        );
        $this->load->view('templates/header', $data);
        $this->load->view('returns/advance_return_list', $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/rightslidebar');
        $this->load->view('templates/footerscripts');
    }

    /** The printable slip handed to the customer. */
    public function slip($adv_id)
    {
        $data = array(
            'ret'      => $this->AdvanceReturn_model->getOne($adv_id),
            'comName'  => $this->Configs_model->getConfigName(),
            'addLine1' => $this->Configs_model->getConfigAdd1(),
            'addLine2' => $this->Configs_model->getConfigAdd2(),
            'telephone'=> $this->Configs_model->getConfigTel()
        );
        $this->load->view('returns/advance_return_slip', $data);
    }

    // ===================== AJAX =====================

    /** Barcode scan or typed search. */
    public function searchItems()
    {
        $rows = $this->AdvanceReturn_model->findItems(
            $this->input->post('term'),
            $this->input->post('store_id')
        );
        echo json_encode($rows ? $rows : array());
    }

    /** Optional: pull a bill up for reference. Never alters it. */
    public function findBill()
    {
        $sale = $this->AdvanceReturn_model->findSale($this->input->post('bill_no'));
        if (!$sale) {
            echo json_encode(array('ok' => false, 'msg' => 'No bill found with that number. You can still return without a bill.'));
            return;
        }
        echo json_encode(array('ok' => true, 'sale' => $sale));
    }

    public function save()
    {
        $decode = function($v){ return is_string($v) ? json_decode($v, true) : $v; };

        $header = array(
            'store_id'    => $this->input->post('store_id'),
            'cus_id'      => $this->input->post('cus_id'),
            'bill_ref'    => $this->input->post('bill_ref'),
            'sale_id'     => $this->input->post('sale_id'),
            'refund_mode' => $this->input->post('refund_mode'),
            'discount'      => $this->input->post('discount'),
            'discount_type' => $this->input->post('discount_type'),
            'restock'     => $this->input->post('restock'),
            'reason'      => $this->input->post('reason')
        );

        $returns   = $decode($this->input->post('lines'));
        $exchanges = $decode($this->input->post('exchange_lines'));
        $payments  = $decode($this->input->post('payments'));

        // Everything goes through the exchange path once v16 is in place - a
        // plain return is just an exchange with nothing going out. The old
        // path is kept only so the page still works before the migration.
        if ($this->AdvanceReturn_model->exchangeReady()) {
            $res = $this->AdvanceReturn_model->saveExchange($header, $returns, $exchanges, $payments);
        } else {
            $res = $this->AdvanceReturn_model->save($header, $returns);
        }
        echo json_encode($res);
    }

    /** Validate a gift card being used to pay the difference on an exchange. */
    public function checkVoucher()
    {
        $this->load->model('GiftVoucher_model');
        $card = $this->GiftVoucher_model->getCardByNumber(trim((string)$this->input->post('card_number')));
        if (!$card) {
            echo json_encode(array('ok' => false, 'msg' => 'No card with that number.'));
            return;
        }
        if ($card->gc_status === 'Available') {
            echo json_encode(array('ok' => false, 'msg' => 'That card has not been sold yet, so there is nothing on it.'));
            return;
        }
        if ($card->gc_status === 'Redeemed' || floatval($card->gc_remaining_value) <= 0) {
            echo json_encode(array('ok' => false, 'msg' => 'That card has already been used up.'));
            return;
        }
        if ($card->gc_status === 'Expired') {
            echo json_encode(array('ok' => false, 'msg' => 'That card has expired.'));
            return;
        }
        echo json_encode(array('ok' => true, 'gc_id' => $card->gc_id,
                               'card_number' => $card->gc_card_number,
                               'remaining' => round(floatval($card->gc_remaining_value), 2),
                               // A one-off card is finished the first time it is
                               // used, whatever is left on it. The cashier has to
                               // be able to tell the customer that BEFORE taking it.
                               'one_off' => !empty($card->vcat_is_oneoff)));
    }

    public function getList()
    {
        $rows = $this->AdvanceReturn_model->getList(
            $this->input->post('from'),
            $this->input->post('to'),
            $this->input->post('store_id') ? $this->input->post('store_id') : 'all'
        );
        echo json_encode($rows ? $rows : array());
    }
}
