<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Promotions (Point 9)
 * Percentage / fixed discounts scoped to the whole bill, a specific item,
 * a category, or a payment method, optionally limited by date range and a
 * minimum bill value. Active auto promotions are evaluated in the sales screen.
 */
class Promotions_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function tableExists() {
        return $this->db->table_exists('ezy_pos_promotions');
    }

    public function getAll() {
        if (!$this->tableExists()) return array();
        $this->db->order_by('promo_status', 'DESC');
        $this->db->order_by('promo_priority', 'DESC');
        $this->db->order_by('promo_id', 'DESC');
        return $this->db->get('ezy_pos_promotions')->result();
    }

    public function getById($id) {
        return $this->db->where('promo_id', $id)->get('ezy_pos_promotions')->row();
    }

    /**
     * Active, auto-apply promotions valid for the given date, enriched with
     * the target's display name so the sales screen can label them.
     */
    public function getActiveForSale($date = null) {
        if (!$this->tableExists()) return array();
        if (!$date) $date = date('Y-m-d');
        $this->db->from('ezy_pos_promotions');
        $this->db->where('promo_status', 1);
        $this->db->where('promo_auto', 1);
        $this->db->group_start();
            $this->db->where('promo_start_date IS NULL', null, false);
            $this->db->or_where('promo_start_date <=', $date);
        $this->db->group_end();
        $this->db->group_start();
            $this->db->where('promo_end_date IS NULL', null, false);
            $this->db->or_where('promo_end_date >=', $date);
        $this->db->group_end();
        $this->db->order_by('promo_priority', 'DESC');
        $rows = $this->db->get()->result();

        // Attach target labels
        foreach ($rows as $r) {
            $r->target_label = '';
            if ($r->promo_scope == 'item' && $r->promo_target_id) {
                $it = $this->db->select('itm_name')->where('itm_id', $r->promo_target_id)->get('ezy_pos_items')->row();
                $r->target_label = $it ? $it->itm_name : '';
            } elseif ($r->promo_scope == 'category' && $r->promo_target_id) {
                $ct = $this->db->select('cat_name')->where('cat_id', $r->promo_target_id)->get('ezy_pos_categories')->row();
                $r->target_label = $ct ? $ct->cat_name : '';
                // item ids belonging to this category, so the sales screen can match cart lines
                $its = $this->db->select('itm_id')->where('itm_category', $r->promo_target_id)->get('ezy_pos_items')->result();
                $ids = array();
                foreach ($its as $x) { $ids[] = intval($x->itm_id); }
                $r->item_ids = $ids;
            } elseif ($r->promo_scope == 'payment' && $r->promo_target_id) {
                if ($this->db->table_exists('ezy_pos_payment_methods')) {
                    $pm = $this->db->select('pm_name')->where('pm_id', $r->promo_target_id)->get('ezy_pos_payment_methods')->row();
                    $r->target_label = $pm ? $pm->pm_name : '';
                }
            }
        }
        return $rows;
    }

    public function create($data) {
        $this->db->insert('ezy_pos_promotions', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('promo_id', $id);
        return $this->db->update('ezy_pos_promotions', $data);
    }

    public function toggle($id, $status) {
        $this->db->where('promo_id', $id);
        return $this->db->update('ezy_pos_promotions', array('promo_status' => $status));
    }

    /* ---- link applied promotions to a sale ---- */

    public function recordForSale($sale_id, $applied) {
        if (!$this->db->table_exists('ezy_pos_sale_promotions')) return;
        $totalDisc = 0;
        foreach ($applied as $a) {
            $disc = floatval($a['discount']);
            if ($disc <= 0) continue;
            $totalDisc += $disc;
            $this->db->insert('ezy_pos_sale_promotions', array(
                'spr_sale_id'   => $sale_id,
                'spr_promo_id'  => isset($a['promo_id']) ? $a['promo_id'] : null,
                'spr_promo_name'=> isset($a['name']) ? $a['name'] : '',
                'spr_scope'     => isset($a['scope']) ? $a['scope'] : '',
                'spr_discount'  => $disc
            ));
        }
        // snapshot on the sale row if the column exists
        if ($totalDisc > 0 && in_array('sale_promo_discount', $this->db->list_fields('ezy_pos_sale'))) {
            $this->db->where('sale_id', $sale_id);
            $this->db->update('ezy_pos_sale', array('sale_promo_discount' => $totalDisc));
        }
        return $totalDisc;
    }

    public function getSalePromotions($sale_id) {
        if (!$this->db->table_exists('ezy_pos_sale_promotions')) return array();
        return $this->db->where('spr_sale_id', $sale_id)->get('ezy_pos_sale_promotions')->result();
    }
}
