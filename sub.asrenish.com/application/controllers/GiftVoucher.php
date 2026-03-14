<?php
class GiftVoucher extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
        $this->load->model('GiftVoucher_model');
        $this->load->model('Configs_model');
    }

    // =========== MANAGEMENT PAGE ===========

    public function manage($page = 'gift-voucher-manage') {
        if (!file_exists(APPPATH . 'views/transactions/' . $page . '.php')) {
            show_404();
        }
        $data['title'] = 'Gift Voucher Management';
        $data['config'] = $this->Configs_model->getConfigName();
        $data['categories'] = $this->GiftVoucher_model->getActiveCategories();
        $data['categorySummary'] = $this->GiftVoucher_model->getCategorySummary();
        $this->load->view('templates/header', $data);
        $this->load->view('transactions/' . $page, $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/footerscripts');
    }

    // =========== CATEGORY CRUD ===========

    public function addCategory() {
        $data = array(
            'vcat_name' => $this->input->post('name'),
            'vcat_value' => $this->input->post('value'),
            'vcat_barcode' => $this->input->post('barcode'),
            'vcat_is_oneoff' => $this->input->post('is_oneoff') ? 1 : 0,
            'vcat_has_expiry' => $this->input->post('has_expiry') ? 1 : 0,
            'vcat_expiry_days' => $this->input->post('has_expiry') ? $this->input->post('expiry_days') : NULL
        );
        $id = $this->GiftVoucher_model->addCategory($data);
        echo json_encode(array('success' => true, 'id' => $id));
    }

    public function updateCategory() {
        $id = $this->input->post('vcat_id');
        $data = array(
            'vcat_name' => $this->input->post('name'),
            'vcat_value' => $this->input->post('value'),
            'vcat_barcode' => $this->input->post('barcode'),
            'vcat_is_oneoff' => $this->input->post('is_oneoff') ? 1 : 0,
            'vcat_has_expiry' => $this->input->post('has_expiry') ? 1 : 0,
            'vcat_expiry_days' => $this->input->post('has_expiry') ? $this->input->post('expiry_days') : NULL
        );
        $this->GiftVoucher_model->updateCategory($id, $data);
        echo json_encode(array('success' => true));
    }

    public function deleteCategory() {
        $id = $this->input->post('vcat_id');
        $this->GiftVoucher_model->deleteCategory($id);
        echo json_encode(array('success' => true));
    }

    // =========== CARD GENERATION ===========

    public function generateCards() {
        $vcat_id = $this->input->post('vcat_id');
        $count = intval($this->input->post('count'));
        if ($count < 1 || $count > 500) {
            echo json_encode(array('success' => false, 'msg' => 'Count must be 1-500'));
            return;
        }
        $generated = $this->GiftVoucher_model->generateCards($vcat_id, $count);
        echo json_encode(array('success' => true, 'count' => $generated));
    }

    public function getCards() {
        $vcat_id = $this->input->post('vcat_id');
        $cards = $this->GiftVoucher_model->getCardsByCategory($vcat_id);
        echo json_encode($cards);
    }

    public function getAllCards() {
        $status = $this->input->post('status');
        $cards = $this->GiftVoucher_model->getAllCards($status ?: null);
        echo json_encode($cards);
    }

    // =========== POS INTEGRATION (Selling) ===========

    // Get available card for a voucher category (when selling at POS)
    public function getNextAvailableCard() {
        $vcat_id = $this->input->post('vcat_id');
        $card = $this->GiftVoucher_model->getNextAvailableCard($vcat_id);
        echo json_encode($card);
    }

    // Mark card as sold after sale is completed
    public function markCardSold() {
        $gc_id = $this->input->post('gc_id');
        $sale_id = $this->input->post('sale_id');
        $this->GiftVoucher_model->markCardSold($gc_id, $sale_id);
        echo json_encode(array('success' => true));
    }

    // =========== POS INTEGRATION (Redemption) ===========

    // Validate a card number for redemption
    public function validateCard() {
        $card_number = $this->input->post('card_number');
        $card = $this->GiftVoucher_model->getCardByNumber($card_number);

        if (!$card) {
            echo json_encode(array('valid' => false, 'msg' => 'Card not found'));
            return;
        }
        if ($card->gc_status == 'Available') {
            echo json_encode(array('valid' => false, 'msg' => 'This card has not been sold yet'));
            return;
        }
        if ($card->gc_status == 'Redeemed') {
            echo json_encode(array('valid' => false, 'msg' => 'This card has already been fully redeemed'));
            return;
        }
        if ($card->gc_status == 'Expired') {
            echo json_encode(array('valid' => false, 'msg' => 'This card has expired'));
            return;
        }
        // Check expiry date
        if ($card->gc_expiry_date && strtotime($card->gc_expiry_date) < strtotime('today')) {
            // Auto-expire
            $this->db->where('gc_id', $card->gc_id);
            $this->db->update('ezy_pos_gift_cards', array('gc_status' => 'Expired'));
            echo json_encode(array('valid' => false, 'msg' => 'This card has expired'));
            return;
        }
        if ($card->gc_remaining_value <= 0) {
            echo json_encode(array('valid' => false, 'msg' => 'No remaining balance on this card'));
            return;
        }

        echo json_encode(array(
            'valid' => true,
            'gc_id' => $card->gc_id,
            'card_number' => $card->gc_card_number,
            'category' => $card->vcat_name,
            'original_value' => $card->gc_original_value,
            'remaining_value' => $card->gc_remaining_value,
            'is_oneoff' => $card->vcat_is_oneoff,
            'expiry_date' => $card->gc_expiry_date
        ));
    }

    // Process redemption
    public function redeem() {
        $gc_id = $this->input->post('gc_id');
        $sale_id = $this->input->post('sale_id');
        $amount = floatval($this->input->post('amount'));
        $user_id = $this->session->userdata('userid');

        $redeemed = $this->GiftVoucher_model->redeemCard($gc_id, $sale_id, $amount, $user_id);
        if ($redeemed === false) {
            echo json_encode(array('success' => false, 'msg' => 'Card could not be redeemed'));
        } else {
            echo json_encode(array('success' => true, 'amount_redeemed' => $redeemed));
        }
    }

    // =========== REPORTS ===========

    public function reports($page = 'gift-voucher-reports') {
        if (!file_exists(APPPATH . 'views/listing/' . $page . '.php')) {
            show_404();
        }
        $data['title'] = 'Gift Voucher Reports';
        $data['config'] = $this->Configs_model->getConfigName();
        $this->load->view('templates/header', $data);
        $this->load->view('listing/' . $page, $data);
        $this->load->view('templates/footer');
        $this->load->view('templates/footerscripts');
    }

    public function getSalesReport() {
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $data = $this->GiftVoucher_model->getVoucherSalesReport($from, $to);
        echo json_encode($data);
    }

    public function getOutstandingReport() {
        $data = $this->GiftVoucher_model->getOutstandingVouchers();
        echo json_encode($data);
    }

    public function getCategorySummary() {
        $data = $this->GiftVoucher_model->getCategorySummary();
        echo json_encode($data);
    }

    // Get active voucher categories (for POS item list)
    public function getActiveCategories() {
        $cats = $this->GiftVoucher_model->getActiveCategories();
        echo json_encode($cats);
    }
}
