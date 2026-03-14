<?php
class GiftVoucher_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    // =========== VOUCHER CATEGORIES ===========

    public function getAllCategories() {
        $this->db->order_by('vcat_id', 'DESC');
        return $this->db->get('ezy_pos_voucher_categories')->result();
    }

    public function getActiveCategories() {
        $this->db->where('vcat_status', 1);
        $this->db->order_by('vcat_value', 'ASC');
        return $this->db->get('ezy_pos_voucher_categories')->result();
    }

    public function getCategory($id) {
        $this->db->where('vcat_id', $id);
        return $this->db->get('ezy_pos_voucher_categories')->row();
    }

    public function addCategory($data) {
        $this->db->insert('ezy_pos_voucher_categories', $data);
        return $this->db->insert_id();
    }

    public function updateCategory($id, $data) {
        $this->db->where('vcat_id', $id);
        return $this->db->update('ezy_pos_voucher_categories', $data);
    }

    public function deleteCategory($id) {
        $this->db->where('vcat_id', $id);
        return $this->db->update('ezy_pos_voucher_categories', array('vcat_status' => 0));
    }

    // =========== GIFT CARDS ===========

    public function generateCards($vcat_id, $count) {
        $cat = $this->getCategory($vcat_id);
        if (!$cat) return 0;

        $prefix = 'GV' . str_pad($vcat_id, 2, '0', STR_PAD_LEFT) . '-';
        // Get the last card number for this category
        $this->db->select_max('gc_id');
        $this->db->where('gc_vcat_id', $vcat_id);
        $last = $this->db->get('ezy_pos_gift_cards')->row();
        $start = ($last && $last->gc_id) ? $last->gc_id : 0;

        // Calculate expiry
        $expiry = null;
        if ($cat->vcat_has_expiry && $cat->vcat_expiry_days > 0) {
            $expiry = date('Y-m-d', strtotime('+' . $cat->vcat_expiry_days . ' days'));
        }

        $generated = 0;
        for ($i = 1; $i <= $count; $i++) {
            $num = $start + $i;
            $card_number = $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);

            // Check uniqueness
            $exists = $this->db->where('gc_card_number', $card_number)->get('ezy_pos_gift_cards')->num_rows();
            if ($exists > 0) {
                $start++;
                $card_number = $prefix . str_pad($start + $i, 5, '0', STR_PAD_LEFT);
            }

            $data = array(
                'gc_vcat_id' => $vcat_id,
                'gc_card_number' => $card_number,
                'gc_original_value' => $cat->vcat_value,
                'gc_remaining_value' => $cat->vcat_value,
                'gc_status' => 'Available',
                'gc_expiry_date' => $expiry
            );
            $this->db->insert('ezy_pos_gift_cards', $data);
            $generated++;
        }
        return $generated;
    }

    public function getCardsByCategory($vcat_id) {
        $this->db->where('gc_vcat_id', $vcat_id);
        $this->db->order_by('gc_id', 'DESC');
        return $this->db->get('ezy_pos_gift_cards')->result();
    }

    public function getAllCards($status = null) {
        $str = "SELECT gc.*, vc.vcat_name, vc.vcat_value, vc.vcat_barcode, vc.vcat_is_oneoff
                FROM ezy_pos_gift_cards gc
                INNER JOIN ezy_pos_voucher_categories vc ON gc.gc_vcat_id = vc.vcat_id";
        if ($status) {
            $str .= " WHERE gc.gc_status = " . $this->db->escape($status);
        }
        $str .= " ORDER BY gc.gc_id DESC";
        return $this->db->query($str)->result();
    }

    public function getCardByNumber($card_number) {
        $str = "SELECT gc.*, vc.vcat_name, vc.vcat_value, vc.vcat_barcode, vc.vcat_is_oneoff, vc.vcat_has_expiry
                FROM ezy_pos_gift_cards gc
                INNER JOIN ezy_pos_voucher_categories vc ON gc.gc_vcat_id = vc.vcat_id
                WHERE gc.gc_card_number = ?";
        return $this->db->query($str, array($card_number))->row();
    }

    public function getCardById($id) {
        $str = "SELECT gc.*, vc.vcat_name, vc.vcat_value, vc.vcat_barcode, vc.vcat_is_oneoff
                FROM ezy_pos_gift_cards gc
                INNER JOIN ezy_pos_voucher_categories vc ON gc.gc_vcat_id = vc.vcat_id
                WHERE gc.gc_id = ?";
        return $this->db->query($str, array($id))->row();
    }

    public function getNextAvailableCard($vcat_id) {
        $str = "SELECT gc.* FROM ezy_pos_gift_cards gc
                WHERE gc.gc_vcat_id = ? AND gc.gc_status = 'Available'
                ORDER BY gc.gc_id ASC LIMIT 1";
        return $this->db->query($str, array($vcat_id))->row();
    }

    // Mark card as sold (when sold at POS)
    public function markCardSold($gc_id, $sale_id) {
        $this->db->where('gc_id', $gc_id);
        return $this->db->update('ezy_pos_gift_cards', array(
            'gc_status' => 'Sold',
            'gc_sold_sale_id' => $sale_id,
            'gc_sold_date' => date('Y-m-d H:i:s')
        ));
    }

    // =========== REDEMPTION ===========

    public function redeemCard($gc_id, $sale_id, $amount, $user_id) {
        $card = $this->getCardById($gc_id);
        if (!$card) return false;

        // Check validity
        if ($card->gc_status == 'Available') return false; // Not sold yet
        if ($card->gc_status == 'Redeemed') return false;  // Already fully used
        if ($card->gc_status == 'Expired') return false;
        if ($card->gc_remaining_value <= 0) return false;

        // Check expiry
        if ($card->gc_expiry_date && strtotime($card->gc_expiry_date) < strtotime('today')) {
            $this->db->where('gc_id', $gc_id);
            $this->db->update('ezy_pos_gift_cards', array('gc_status' => 'Expired'));
            return false;
        }

        // Cap amount to remaining value
        if ($amount > $card->gc_remaining_value) {
            $amount = $card->gc_remaining_value;
        }

        // Insert redemption record
        $this->db->insert('ezy_pos_voucher_redemptions', array(
            'vr_gc_id' => $gc_id,
            'vr_sale_id' => $sale_id,
            'vr_amount' => $amount,
            'vr_redeemed_by' => $user_id
        ));

        // Update card balance
        $new_balance = $card->gc_remaining_value - $amount;
        $update = array('gc_remaining_value' => $new_balance);

        // If one-off OR balance is now 0, mark as redeemed
        if ($card->vcat_is_oneoff || $new_balance <= 0) {
            $update['gc_status'] = 'Redeemed';
            $update['gc_remaining_value'] = 0;
        }

        $this->db->where('gc_id', $gc_id);
        $this->db->update('ezy_pos_gift_cards', $update);

        return $amount; // Return actual amount redeemed
    }

    // =========== REPORTS ===========

    public function getRedemptionsBySale($sale_id) {
        $str = "SELECT vr.*, gc.gc_card_number, vc.vcat_name
                FROM ezy_pos_voucher_redemptions vr
                INNER JOIN ezy_pos_gift_cards gc ON vr.vr_gc_id = gc.gc_id
                INNER JOIN ezy_pos_voucher_categories vc ON gc.gc_vcat_id = vc.vcat_id
                WHERE vr.vr_sale_id = ?
                ORDER BY vr.vr_id";
        return $this->db->query($str, array($sale_id))->result();
    }

    public function getVoucherSalesReport($from = null, $to = null) {
        $str = "SELECT gc.gc_card_number, gc.gc_original_value, gc.gc_remaining_value, gc.gc_status,
                gc.gc_sold_date, vc.vcat_name,
                COALESCE(SUM(vr.vr_amount), 0) as total_redeemed
                FROM ezy_pos_gift_cards gc
                INNER JOIN ezy_pos_voucher_categories vc ON gc.gc_vcat_id = vc.vcat_id
                LEFT JOIN ezy_pos_voucher_redemptions vr ON gc.gc_id = vr.vr_gc_id
                WHERE gc.gc_status != 'Available'";
        $params = array();
        if ($from) { $str .= " AND gc.gc_sold_date >= ?"; $params[] = $from . ' 00:00:00'; }
        if ($to) { $str .= " AND gc.gc_sold_date <= ?"; $params[] = $to . ' 23:59:59'; }
        $str .= " GROUP BY gc.gc_id ORDER BY gc.gc_sold_date DESC";
        return $this->db->query($str, $params)->result();
    }

    public function getOutstandingVouchers() {
        $str = "SELECT gc.*, vc.vcat_name
                FROM ezy_pos_gift_cards gc
                INNER JOIN ezy_pos_voucher_categories vc ON gc.gc_vcat_id = vc.vcat_id
                WHERE gc.gc_status = 'Sold' AND gc.gc_remaining_value > 0
                ORDER BY gc.gc_sold_date";
        return $this->db->query($str)->result();
    }

    public function getCategorySummary() {
        $str = "SELECT vc.*,
                COUNT(gc.gc_id) as total_cards,
                SUM(CASE WHEN gc.gc_status = 'Available' THEN 1 ELSE 0 END) as available_cards,
                SUM(CASE WHEN gc.gc_status = 'Sold' THEN 1 ELSE 0 END) as sold_cards,
                SUM(CASE WHEN gc.gc_status = 'Redeemed' THEN 1 ELSE 0 END) as redeemed_cards,
                SUM(CASE WHEN gc.gc_status = 'Expired' THEN 1 ELSE 0 END) as expired_cards
                FROM ezy_pos_voucher_categories vc
                LEFT JOIN ezy_pos_gift_cards gc ON vc.vcat_id = gc.gc_vcat_id
                WHERE vc.vcat_status = 1
                GROUP BY vc.vcat_id
                ORDER BY vc.vcat_value";
        return $this->db->query($str)->result();
    }
}
