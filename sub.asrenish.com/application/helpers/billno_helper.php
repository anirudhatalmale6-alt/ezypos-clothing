<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bill number helpers.
 *
 * Every store keeps its own running sequence, so Main Store and Branch Store
 * both start at 1 and never share a number. The printed form is
 *
 *      <prefix><store id>-<4 digit sequence>        e.g.  HG1-0001
 *
 * The prefix lives in ezy_pos_config2 under the key 'bill_prefix' (default
 * 'HG'), so it can be renamed without touching any code.
 *
 * Sales saved before this went live have their number backfilled by the v9
 * migration; anything still without one falls back to the raw sale id so a
 * bill can never print blank.
 */

if ( ! function_exists('bill_prefix'))
{
    function bill_prefix()
    {
        static $prefix = null;
        if ($prefix !== null) { return $prefix; }

        $prefix = 'HG';
        $CI =& get_instance();
        if (isset($CI->db) && $CI->db->table_exists('ezy_pos_config2')) {
            $row = $CI->db->get_where('ezy_pos_config2', array('config_key' => 'bill_prefix'))->row();
            if ($row && trim($row->config_value) !== '') {
                $prefix = trim($row->config_value);
            }
        }
        return $prefix;
    }
}

if ( ! function_exists('bill_no'))
{
    /**
     * Format the bill number for a sale.
     *
     * @param mixed $sale     sale row (object or array) or a bare sale id
     * @param mixed $storeId  store id, only needed when $sale is a bare id
     * @return string
     */
    function bill_no($sale, $storeId = null)
    {
        // Already stored on the row: use it verbatim, so a reprint years later
        // shows exactly what the customer was handed.
        if (is_object($sale) && isset($sale->sale_bill_no) && trim($sale->sale_bill_no) !== '') {
            return $sale->sale_bill_no;
        }
        if (is_array($sale) && isset($sale['sale_bill_no']) && trim($sale['sale_bill_no']) !== '') {
            return $sale['sale_bill_no'];
        }

        // Fall back to the sale id so nothing ever prints empty.
        $id = $sale;
        if (is_object($sale)) { $id = isset($sale->sale_id) ? $sale->sale_id : 0; }
        elseif (is_array($sale)) { $id = isset($sale['sale_id']) ? $sale['sale_id'] : 0; }

        if ($storeId === null && is_object($sale) && isset($sale->sale_location)) { $storeId = $sale->sale_location; }
        if ($storeId === null && is_array($sale) && isset($sale['sale_location'])) { $storeId = $sale['sale_location']; }

        if ($storeId !== null && $storeId !== '') {
            return bill_prefix().intval($storeId).'-'.str_pad($id, 4, '0', STR_PAD_LEFT);
        }
        return bill_prefix().str_pad($id, 4, '0', STR_PAD_LEFT);
    }
}

if ( ! function_exists('bill_no_by_id'))
{
    /**
     * Look the stored bill number up by sale id. Used by screens that only
     * have the id to hand (returns list, customer payments, reports).
     */
    function bill_no_by_id($sale_id)
    {
        $CI =& get_instance();
        if (isset($CI->db)) {
            $fields = $CI->db->list_fields('ezy_pos_sale');
            if (in_array('sale_bill_no', $fields)) {
                $row = $CI->db->select('sale_bill_no, sale_id, sale_location')
                              ->get_where('ezy_pos_sale', array('sale_id' => $sale_id))->row();
                if ($row) { return bill_no($row); }
            }
        }
        return bill_prefix().str_pad($sale_id, 4, '0', STR_PAD_LEFT);
    }
}

if ( ! function_exists('bill_no_to_sale_id'))
{
    /**
     * Reverse lookup: accept anything the counter staff might type - the full
     * printed number (HG1-0001), the old AS00 form, or a bare sale id - and
     * return the sale id it belongs to, or 0 when there is no such bill.
     */
    function bill_no_to_sale_id($entered)
    {
        $entered = trim((string)$entered);
        if ($entered === '') { return 0; }

        $CI =& get_instance();

        // Exact match on the stored bill number first.
        if (isset($CI->db) && in_array('sale_bill_no', $CI->db->list_fields('ezy_pos_sale'))) {
            $row = $CI->db->select('sale_id')
                          ->get_where('ezy_pos_sale', array('sale_bill_no' => $entered))->row();
            if ($row) { return intval($row->sale_id); }
        }

        // Legacy AS00 numbers, a bare id, or "HG1-0007" typed against a database
        // that has not been backfilled: strip the letters and any store part.
        $stripped = preg_replace('/^[A-Za-z]+/', '', $entered);   // HG1-0007 -> 1-0007
        if (strpos($stripped, '-') !== false) {
            $parts = explode('-', $stripped);
            $stripped = end($parts);                              // 1-0007 -> 0007
        }
        $stripped = preg_replace('/[^0-9]/', '', $stripped);
        return $stripped === '' ? 0 : intval(ltrim($stripped, '0'));
    }
}
