<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Item-based reports: stock, sales and purchases, one row per item.
 *
 * Sale price and cost price are always the FINAL amount for ONE unit:
 *   - cost: the GRN line (or production receipt) total over its quantity,
 *     less that line's share of any discount on the GRN as a whole;
 *   - sale: the bill line total over its quantity, less the line's share of
 *     the bill discount and promotion. A loyalty redemption is not a
 *     discount - it is a way of paying - so it is not taken off.
 * In the sales and purchase reports they are averages over the period (what
 * was actually charged / paid). In the stock report they are the item's
 * selling price and its most recent purchase or production cost.
 */
class ItemReport_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function categories()
    {
        return $this->db->where('cat_status', 1)->order_by('cat_name')->get('ezy_pos_categories')->result();
    }

    public function subcategories()
    {
        if ( ! $this->db->table_exists('ezy_pos_subcategories')) { return array(); }
        return $this->db->where('sub_cat_status', 1)->order_by('sub_cat_name')->get('ezy_pos_subcategories')->result();
    }

    public function suppliers()
    {
        return $this->db->select('sup_id, sup_name')->order_by('sup_name')->get('ezy_pos_suppliers')->result();
    }

    public function reps()
    {
        if ( ! $this->db->table_exists('ezy_pos_sales_reps')) { return array(); }
        return $this->db->order_by('rep_name')->get('ezy_pos_sales_reps')->result();
    }

    /** Non-admins only ever see their own stores, whatever is picked. */
    protected function _stores($column, $picked)
    {
        $sql = '';
        if ($this->session->userdata('userrole') != 1) {
            $ids = array();
            foreach ($this->db->query("SELECT store_id FROM ezy_pos_user_store WHERE user_id = ? AND user_store_status = 1",
                                      array(intval($this->session->userdata('userid'))))->result() as $r) { $ids[] = intval($r->store_id); }
            $sql .= $ids ? " AND $column IN (".implode(',', $ids).")" : " AND $column = -1";
        }
        if ($picked !== null && $picked !== '' && $picked !== 'all' && intval($picked) > 0) {
            $sql .= " AND $column = ".intval($picked);
        }
        return $sql;
    }

    /** Category / subcategory / item search, on the items table aliased i. */
    protected function _items($f, &$bind)
    {
        $sql = " AND i.itm_status = 1";
        if ( ! empty($f['category']) && $f['category'] !== 'all') { $sql .= " AND i.itm_category = ?"; $bind[] = intval($f['category']); }
        if ( ! empty($f['subcategory']) && $f['subcategory'] !== 'all') { $sql .= " AND i.itm_subcategory = ?"; $bind[] = intval($f['subcategory']); }
        if (isset($f['item']) && trim($f['item']) !== '') {
            $like = '%'.$this->db->escape_like_str(trim($f['item'])).'%';
            $sql .= " AND (i.itm_code LIKE ? ESCAPE '!' OR i.itm_name LIKE ? ESCAPE '!')"; $bind[] = $like; $bind[] = $like;
        }
        return $sql;
    }

    /** Net cost of one unit on each GRN line (GRN discount shared out by value). */
    protected function _netUnitCostExpr()
    {
        return "(gi.grnitm_total / NULLIF(gi.grnitm_quantity, 0))
                * (CASE WHEN g.grn_subtotal > 0 THEN g.grn_grandtotal / g.grn_subtotal ELSE 1 END)";
    }

    /** Each item's most recent purchase or production cost per unit. */
    protected function _latestCostJoin()
    {
        return "LEFT JOIN (
                    SELECT gi.grnitm_itemid AS item_id, ".$this->_netUnitCostExpr()." AS unit_cost
                    FROM ezy_pos_grn_item gi
                    JOIN ezy_pos_grns g ON g.grn_id = gi.grnitm_grn_id
                    JOIN (SELECT gi2.grnitm_itemid, MAX(gi2.grnitm_id) AS mx
                          FROM ezy_pos_grn_item gi2
                          JOIN ezy_pos_grns g2 ON g2.grn_id = gi2.grnitm_grn_id AND g2.grn_status = 1
                          WHERE gi2.grnitm_quantity > 0
                          GROUP BY gi2.grnitm_itemid) last ON last.mx = gi.grnitm_id
                ) lc ON lc.item_id = i.itm_id";
    }

    protected function _base()
    {
        return "i.itm_id, i.itm_code, i.itm_name, c.cat_name, ".
               ($this->db->table_exists('ezy_pos_subcategories') ? "sc.sub_cat_name" : "NULL")." AS sub_cat_name";
    }

    protected function _catJoins()
    {
        return " LEFT JOIN ezy_pos_categories c ON c.cat_id = i.itm_category ".
               ($this->db->table_exists('ezy_pos_subcategories') ? " LEFT JOIN ezy_pos_subcategories sc ON sc.sub_cat_id = i.itm_subcategory " : "");
    }

    /* ------------------------------------------------------------ stock */
    public function stock($f)
    {
        $bind = array();
        $storeSql = $this->_stores('s.stock_store_id', isset($f['store']) ? $f['store'] : null);
        $sql = "SELECT ".$this->_base().", i.itm_sellingprice AS sale_price, lc.unit_cost AS cost_price,
                       IFNULL(st.qty, 0) AS qty
                FROM ezy_pos_items i ".$this->_catJoins()."
                LEFT JOIN (SELECT s.stock_itm_id, SUM(s.stock_qty) AS qty FROM ezy_pos_stock s
                           WHERE s.stock_status = 1".$storeSql." GROUP BY s.stock_itm_id) st ON st.stock_itm_id = i.itm_id
                ".$this->_latestCostJoin()."
                WHERE 1 = 1".$this->_items($f, $bind);
        if ( ! empty($f['nonzero'])) { $sql .= " AND IFNULL(st.qty, 0) <> 0"; }
        $sql .= " ORDER BY i.itm_name";
        return $this->_round($this->db->query($sql, $bind)->result());
    }

    /* ------------------------------------------------------------ sales */
    public function sales($f)
    {
        $bind = array($f['from'], $f['to']);
        $hasType  = $this->db->field_exists('sale_discount_type', 'ezy_pos_sale');
        $hasPromo = $this->db->field_exists('sale_promo_discount', 'ezy_pos_sale');
        $billDisc = $hasType
            ? "(CASE WHEN s.sale_discount_type = 'flat' THEN s.sale_discount ELSE s.sale_subtotal * s.sale_discount / 100 END)"
            : "s.sale_discount";
        $factor = "(CASE WHEN s.sale_subtotal > 0
                         THEN GREATEST(0, s.sale_subtotal - ".$billDisc.($hasPromo ? " - IFNULL(s.sale_promo_discount, 0)" : "").") / s.sale_subtotal
                         ELSE 1 END)";
        $sql = "SELECT ".$this->_base().",
                       SUM(si.saleitem_quantity) AS qty,
                       SUM(si.saleitem_total * ".$factor.") AS net_value,
                       lc.unit_cost AS cost_price
                FROM ezy_pos_sale_item si
                JOIN ezy_pos_sale s ON s.sale_id = si.saleitem_sale_id
                JOIN ezy_pos_items i ON i.itm_id = si.saleitem_item_id ".$this->_catJoins()."
                ".$this->_latestCostJoin()."
                WHERE s.sale_status = '1' AND s.sale_date BETWEEN ? AND ?"
                .$this->_stores('s.sale_location', isset($f['store']) ? $f['store'] : null)
                .$this->_items($f, $bind);
        if ( ! empty($f['rep']) && $f['rep'] !== 'all' && $this->db->field_exists('sale_rep_id', 'ezy_pos_sale')) {
            $sql .= " AND s.sale_rep_id = ?"; $bind[] = intval($f['rep']);
        }
        $sql .= " GROUP BY i.itm_id, i.itm_code, i.itm_name, c.cat_name, sub_cat_name, lc.unit_cost
                  HAVING SUM(si.saleitem_quantity) <> 0
                  ORDER BY qty DESC, i.itm_name";
        $rows = $this->db->query($sql, $bind)->result();
        foreach ($rows as $r) { $r->sale_price = floatval($r->qty) != 0 ? floatval($r->net_value) / floatval($r->qty) : 0; }
        return $this->_round($rows);
    }

    /* --------------------------------------------------------- purchases */
    public function purchases($f)
    {
        $bind = array($f['from'], $f['to']);
        $sql = "SELECT ".$this->_base().", i.itm_sellingprice AS sale_price,
                       SUM(gi.grnitm_quantity) AS qty,
                       SUM(gi.grnitm_quantity * ".$this->_netUnitCostExpr().") AS net_value
                FROM ezy_pos_grn_item gi
                JOIN ezy_pos_grns g ON g.grn_id = gi.grnitm_grn_id
                JOIN ezy_pos_items i ON i.itm_id = gi.grnitm_itemid ".$this->_catJoins()."
                WHERE g.grn_status = 1 AND g.grn_date BETWEEN ? AND ?"
                .$this->_stores('g.grn_location', isset($f['store']) ? $f['store'] : null)
                .$this->_items($f, $bind);
        if ( ! empty($f['supplier']) && $f['supplier'] !== 'all') { $sql .= " AND g.grn_supplier_id = ?"; $bind[] = intval($f['supplier']); }
        // Production receipts are filed as GRNs numbered MFG-...
        if (isset($f['source']) && $f['source'] === 'grn')        { $sql .= " AND IFNULL(g.grn_code, '') NOT LIKE 'MFG-%'"; }
        if (isset($f['source']) && $f['source'] === 'production') { $sql .= " AND g.grn_code LIKE 'MFG-%'"; }
        $sql .= " GROUP BY i.itm_id, i.itm_code, i.itm_name, c.cat_name, sub_cat_name, i.itm_sellingprice
                  HAVING SUM(gi.grnitm_quantity) <> 0
                  ORDER BY qty DESC, i.itm_name";
        $rows = $this->db->query($sql, $bind)->result();
        foreach ($rows as $r) { $r->cost_price = floatval($r->qty) != 0 ? floatval($r->net_value) / floatval($r->qty) : 0; }
        return $this->_round($rows);
    }

    protected function _round($rows)
    {
        $out = array();
        foreach ($rows as $r) {
            $out[] = array(
                'code' => $r->itm_code, 'name' => $r->itm_name,
                'category' => $r->cat_name, 'subcategory' => $r->sub_cat_name,
                'sale_price' => $r->sale_price === null ? null : round(floatval($r->sale_price), 2),
                'cost_price' => $r->cost_price === null ? null : round(floatval($r->cost_price), 2),
                'qty' => round(floatval($r->qty), 2),
                // The period's exact money, so totals are not built from rounded unit prices.
                'net_value' => isset($r->net_value) ? round(floatval($r->net_value), 2) : null
            );
        }
        return $out;
    }
}
