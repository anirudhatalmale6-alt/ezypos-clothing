<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bill number helpers.
 *
 * Printed form:
 *
 *      <prefix>-<store letter>-<number>        e.g.  HG-B-001
 *
 * The store letter is the first letter of the store name (Battaramulla -> B).
 * If a second store starts with the same letter it gets a digit after it
 * (B, B2, B3...). Codes are worked out once and saved in
 * ezy_pos_stores.store_bill_code, so they never drift and can be edited by
 * hand later.
 *
 * Every store counts from 1 on its own, so two branches both start at 001
 * and can never produce the same bill number.
 *
 * The prefix lives in ezy_pos_config2 under the key 'bill_prefix' (default
 * 'HG'), so it can be renamed without touching any code.
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

if ( ! function_exists('store_bill_codes'))
{
    /**
     * All store letter codes, keyed by store id.
     *
     * Any store without a saved code gets one worked out here and written
     * back, so this settles itself the first time a bill is printed and never
     * changes afterwards - even if someone renames the store.
     *
     * @return array  array(store_id => 'B', ...)
     */
    function store_bill_codes()
    {
        static $codes = null;
        if ($codes !== null) { return $codes; }

        $codes = array();
        $CI =& get_instance();
        if ( ! isset($CI->db) || ! $CI->db->table_exists('ezy_pos_stores')) { return $codes; }

        $fields = $CI->db->list_fields('ezy_pos_stores');
        $hasCol = in_array('store_bill_code', $fields);

        $stores = $CI->db->select('store_id, store_name'.($hasCol ? ', store_bill_code' : ''))
                         ->order_by('store_id', 'asc')
                         ->get('ezy_pos_stores')->result();

        // Codes already saved are kept exactly as they are.
        $taken = array();
        $todo  = array();
        foreach ($stores as $s) {
            $saved = $hasCol && isset($s->store_bill_code) ? trim((string)$s->store_bill_code) : '';
            if ($saved !== '') {
                $codes[$s->store_id] = strtoupper($saved);
                $taken[strtoupper($saved)] = true;
            } else {
                $todo[] = $s;
            }
        }

        // Work out a code for anything left: first letter of the name, with a
        // digit appended when that letter is already spoken for.
        foreach ($todo as $s) {
            $letter = strtoupper(preg_replace('/[^A-Za-z]/', '', $s->store_name));
            $letter = ($letter === '') ? 'S' : substr($letter, 0, 1);

            $code = $letter;
            $n = 1;
            while (isset($taken[$code])) {
                $n++;
                $code = $letter.$n;
            }
            $taken[$code] = true;
            $codes[$s->store_id] = $code;

            if ($hasCol) {
                $CI->db->where('store_id', $s->store_id)
                       ->update('ezy_pos_stores', array('store_bill_code' => $code));
            }
        }

        return $codes;
    }
}

if ( ! function_exists('store_bill_code'))
{
    function store_bill_code($store_id)
    {
        $codes = store_bill_codes();
        $store_id = intval($store_id);
        // Unknown store (deleted, or id 0): fall back to the id so the number
        // is still unique rather than colliding with a real branch.
        return isset($codes[$store_id]) ? $codes[$store_id] : 'S'.$store_id;
    }
}

if ( ! function_exists('format_bill_no'))
{
    /**
     * Build the printed number from its parts: HG-B-001
     */
    function format_bill_no($store_id, $seq)
    {
        return bill_prefix().'-'.store_bill_code($store_id).'-'.str_pad($seq, 3, '0', STR_PAD_LEFT);
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
        $get = function($key) use ($sale) {
            if (is_object($sale) && isset($sale->$key))  { return $sale->$key; }
            if (is_array($sale)  && isset($sale[$key]))  { return $sale[$key]; }
            return null;
        };

        // Already stored on the row: use it verbatim, so a reprint years later
        // shows exactly what the customer was handed.
        $stored = $get('sale_bill_no');
        if ($stored !== null && trim($stored) !== '') { return $stored; }

        $id  = $get('sale_id');
        if ($id === null && ! is_object($sale) && ! is_array($sale)) { $id = $sale; }
        if ($storeId === null) { $storeId = $get('sale_location'); }
        $seq = $get('sale_bill_seq');

        // Sale from before the change: build it from the store's running
        // number, which the migration filled in.
        if ($seq !== null && $seq !== '' && $storeId !== null && $storeId !== '') {
            return format_bill_no($storeId, $seq);
        }
        if ($storeId !== null && $storeId !== '') {
            return format_bill_no($storeId, $id);
        }
        // Last resort, so a bill can never print blank.
        return bill_prefix().'-'.str_pad($id, 3, '0', STR_PAD_LEFT);
    }
}

if ( ! function_exists('bill_no_by_id'))
{
    /**
     * Look the bill number up by sale id. Used by screens that only have the
     * id to hand (returns list, customer payments, reports).
     */
    function bill_no_by_id($sale_id)
    {
        $CI =& get_instance();
        if (isset($CI->db)) {
            $fields = $CI->db->list_fields('ezy_pos_sale');
            $cols = array('sale_id', 'sale_location');
            if (in_array('sale_bill_no', $fields))  { $cols[] = 'sale_bill_no'; }
            if (in_array('sale_bill_seq', $fields)) { $cols[] = 'sale_bill_seq'; }
            $row = $CI->db->select(implode(',', $cols))
                          ->get_where('ezy_pos_sale', array('sale_id' => $sale_id))->row();
            if ($row) { return bill_no($row); }
        }
        return bill_prefix().'-'.str_pad($sale_id, 3, '0', STR_PAD_LEFT);
    }
}

if ( ! function_exists('bill_no_to_sale_id'))
{
    /**
     * Reverse lookup: accept anything the counter staff might type - the full
     * printed number (HG-B-001), the older HG1-0001 form, the original AS00
     * form, or a bare sale id - and return the sale id it belongs to, or 0
     * when there is no such bill.
     */
    function bill_no_to_sale_id($entered)
    {
        $entered = trim((string)$entered);
        if ($entered === '') { return 0; }

        $CI =& get_instance();
        if ( ! isset($CI->db)) { return 0; }
        $fields = $CI->db->list_fields('ezy_pos_sale');

        // 1. Exact match on the stored bill number.
        if (in_array('sale_bill_no', $fields)) {
            $row = $CI->db->select('sale_id')
                          ->get_where('ezy_pos_sale', array('sale_bill_no' => $entered))->row();
            if ($row) { return intval($row->sale_id); }
        }

        // 2. <prefix>-<store code>-<number>: find the store by its code, then
        //    the sale holding that running number in that store.
        if (in_array('sale_bill_seq', $fields) && preg_match('/^[A-Za-z]+[-]([A-Za-z][0-9]*)[-]0*([0-9]+)$/', $entered, $m)) {
            $code = strtoupper($m[1]);
            $seq  = intval($m[2]);
            foreach (store_bill_codes() as $storeId => $storeCode) {
                if ($storeCode === $code) {
                    $row = $CI->db->select('sale_id')
                                  ->get_where('ezy_pos_sale', array('sale_location' => $storeId,
                                                                    'sale_bill_seq' => $seq))->row();
                    if ($row) { return intval($row->sale_id); }
                }
            }
        }

        // 3. Older HG<store id>-<number> form.
        if (in_array('sale_bill_seq', $fields) && preg_match('/^[A-Za-z]+([0-9]+)[-]0*([0-9]+)$/', $entered, $m)) {
            $row = $CI->db->select('sale_id')
                          ->get_where('ezy_pos_sale', array('sale_location' => intval($m[1]),
                                                            'sale_bill_seq' => intval($m[2])))->row();
            if ($row) { return intval($row->sale_id); }
        }

        // 4. Legacy AS00 numbers, "#7", or a bare sale id.
        $stripped = preg_replace('/[^0-9]/', '', $entered);
        return $stripped === '' ? 0 : intval(ltrim($stripped, '0'));
    }
}
