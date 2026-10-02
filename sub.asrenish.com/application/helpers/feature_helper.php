<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Feature switches.
 *
 * The same system is sold to a clothing shop, a hardware shop, a bakery. They
 * do not all want tailoring orders, or a warehouse, or loyalty points. Rather
 * than cutting code out for each one, every one of those features stays
 * exactly where it is and a switch decides whether it is reachable.
 *
 * Nothing here deletes or hides data. A feature that is switched off keeps its
 * tables, its rows and its history; switch it back on and it is as it was.
 *
 * WHERE THE VALUES LIVE
 * ---------------------
 * In ezy_pos_config2, the key/value table the shop name and bill prefix
 * already use. No new table, and the same read pattern as everything else.
 * Keys are prefixed `feature_`.
 *
 * DEFAULTS ARE THE RULE THAT MATTERS
 * ----------------------------------
 * A default is what a shop gets before anyone has touched the settings page.
 * So every default below is the behaviour the system has TODAY. Applying this
 * to a running shop changes nothing at all until a switch is moved. The two
 * that default OFF - direct GRN and single location - default off precisely
 * because today's behaviour is the warehouse route and the branch picker.
 */

if ( ! function_exists('feature_defaults'))
{
    /**
     * Every switch, and what it means when nobody has set it.
     *
     * Adding a feature here is all that is needed for it to appear on the
     * Super Admin page - the page is drawn from this list.
     */
    function feature_defaults()
    {
        return array(
            // --- main settings ---
            'feature_grn_direct_to_store' => array(
                'default' => 0,
                'group'   => 'main',
                'label'   => 'GRN direct to store',
                'on'      => 'A GRN puts the goods straight into the store.',
                'off'     => 'Goods arrive at the warehouse first and reach the store by a stock transfer. This is how the system works today.'
            ),
            'feature_single_location' => array(
                'default' => 0,
                'group'   => 'main',
                'label'   => 'Single location sales',
                'on'      => 'One shop, one till. The branch picker is hidden, and credit and cheque are not offered on a sale.',
                'off'     => 'More than one branch. Every screen asks which one. This is how the system works today.'
            ),

            // --- whole modules ---
            'feature_production' => array(
                'default' => 1, 'group' => 'modules', 'label' => 'Production',
                'on' => 'Production orders, materials, costs and gate passes.',
                'off' => 'Hidden everywhere. The orders already taken are kept.'
            ),
            'feature_tailoring' => array(
                'default' => 1, 'group' => 'modules', 'label' => 'Tailoring orders',
                'on' => 'Tailoring orders, advances, estimates and final bills.',
                'off' => 'Hidden everywhere. The orders already taken are kept.'
            ),
            'feature_loyalty' => array(
                'default' => 1, 'group' => 'modules', 'label' => 'Customer loyalty',
                'on' => 'Points earned and redeemed on a sale.',
                'off' => 'Hidden everywhere. Points already earned are kept.'
            ),
            'feature_labeljoy' => array(
                'default' => 1, 'group' => 'modules', 'label' => 'Label printing',
                'on' => 'The barcode label screen and the Windows label printer.',
                'off' => 'Hidden everywhere.'
            ),
            'feature_stocktransfer' => array(
                'default' => 1, 'group' => 'modules', 'label' => 'Stock transfer',
                'on' => 'Moving stock between branches and warehouses.',
                'off' => 'Hidden everywhere. Transfers already made are kept. Turning GRN direct to store ON usually means this can go off too.'
            ),
            'feature_supplier_return' => array(
                'default' => 1, 'group' => 'modules', 'label' => 'Supplier return',
                'on' => 'Sending goods back to the supplier.',
                'off' => 'Hidden everywhere. Returns already made are kept.'
            ),
            'feature_delivery' => array(
                'default' => 1, 'group' => 'modules', 'label' => 'Delivery',
                'on' => 'Delivery companies, and the delivery charge on a sale.',
                'off' => 'Hidden everywhere. Deliveries already recorded are kept.'
            )
        );
    }
}

if ( ! function_exists('feature_all'))
{
    /**
     * Every switch and its current value, defaults filled in.
     *
     * Read once per request and held, because the menu asks about half a dozen
     * of these on every single page and a query each would be a query each.
     */
    function feature_all()
    {
        static $cache = null;
        if ($cache !== null) { return $cache; }

        $defs  = feature_defaults();
        $cache = array();
        foreach ($defs as $key => $d) { $cache[$key] = (int)$d['default']; }

        $CI =& get_instance();

        // The database is not autoloaded in this application, and the place
        // these switches matter most is a controller CONSTRUCTOR - which runs
        // before that controller has loaded a single model. Without this, the
        // lookup found no $CI->db, quietly fell back to the defaults, and
        // every switched-off feature read as switched on. Load it here.
        if ( ! isset($CI->db)) {
            if (isset($CI->load)) { $CI->load->database('', false, true); }
        }
        if ( ! isset($CI->db)) { return $cache; }

        // Before the settings table has the feature rows in it - an older
        // server, or the migration not run yet - every switch keeps its
        // default, which is today's behaviour. The system carries on.
        if ( ! $CI->db->table_exists('ezy_pos_config2')) { return $cache; }

        $rows = $CI->db->select('config_key, config_value')
                       ->like('config_key', 'feature_', 'after')
                       ->get('ezy_pos_config2')->result();
        foreach ($rows as $r) {
            if (array_key_exists($r->config_key, $cache)) {
                $cache[$r->config_key] = ((string)$r->config_value === '1') ? 1 : 0;
            }
        }
        return $cache;
    }
}

if ( ! function_exists('feature_on'))
{
    /**
     * Is this feature switched on?
     *
     * @param string $key e.g. 'feature_tailoring', or just 'tailoring'
     */
    function feature_on($key)
    {
        if (strpos($key, 'feature_') !== 0) { $key = 'feature_'.$key; }
        $all = feature_all();
        return isset($all[$key]) ? ($all[$key] === 1) : false;
    }
}

if ( ! function_exists('feature_off'))
{
    function feature_off($key) { return ! feature_on($key); }
}

if ( ! function_exists('require_feature'))
{
    /**
     * A switched-off page must not open by typing its address either. 404
     * rather than a message, so it reads as a page that is simply not there -
     * the same way a page the user has no permission for behaves.
     *
     * Note this applies to administrators too. "Off" means off for the shop;
     * the only place it can be turned back on is the Super Admin page.
     */
    function require_feature($key)
    {
        if (feature_off($key)) { show_404(); }
    }
}

if ( ! function_exists('single_location'))
{
    /** Shorthand - this one is asked about on a lot of screens. */
    function single_location() { return feature_on('feature_single_location'); }
}

/* =====================================================================
 * The provider login.
 *
 * Super Admin is not an administrator with an extra tick - it is a rank
 * above. The shop's administrator runs the shop; the provider decides which
 * parts of the system that shop has. A shop administrator must not be able
 * to see this account, edit it, delete it, or promote themselves into it.
 *
 * It is a flag on the user row rather than a new role number, deliberately.
 * Hundreds of places in this system ask `userrole == 1` to mean "allowed to
 * do administrator things"; giving the provider a different role number
 * would have made them a restricted user everywhere and meant rewriting all
 * of it. So the provider is an administrator AND carries this flag, and the
 * flag is the only thing that opens the Super Admin page.
 * ===================================================================== */

if ( ! function_exists('super_admin_exists'))
{
    /** Has a provider login been created yet? */
    function super_admin_exists()
    {
        static $known = null;
        if ($known !== null) { return $known; }

        $CI =& get_instance();
        if ( ! isset($CI->db)) {
            if (isset($CI->load)) { $CI->load->database('', false, true); }
        }
        if ( ! isset($CI->db)) { return false; }
        if ( ! in_array('user_is_super', $CI->db->list_fields('ezy_pos_users'))) {
            $known = false;
            return $known;
        }
        $known = ($CI->db->where('user_is_super', 1)->count_all_results('ezy_pos_users') > 0);
        return $known;
    }
}

if ( ! function_exists('is_super'))
{
    /**
     * Is the person signed in the provider?
     *
     * Until a provider login has been created, the shop's administrator can
     * reach the page in order to create one - otherwise there would be no way
     * in on a system that has just been upgraded. The moment one exists, that
     * door closes and only the provider gets in.
     */
    function is_super()
    {
        $CI =& get_instance();
        if ($CI->session->userdata('is_super') == 1) { return true; }
        if ($CI->session->userdata('userrole') == 1 && ! super_admin_exists()) { return true; }
        return false;
    }
}

if ( ! function_exists('require_super'))
{
    function require_super()
    {
        if ( ! is_super()) { show_404(); }
    }
}

if ( ! function_exists('feature_save'))
{
    /**
     * Write the switches back. Only keys that exist in feature_defaults() are
     * accepted, so a hand-made form post cannot put junk in the settings table.
     */
    function feature_save($values)
    {
        $CI =& get_instance();
        if ( ! isset($CI->db) || ! $CI->db->table_exists('ezy_pos_config2')) { return false; }

        foreach (feature_defaults() as $key => $d) {
            $val = (isset($values[$key]) && (string)$values[$key] === '1') ? '1' : '0';
            $existing = $CI->db->get_where('ezy_pos_config2', array('config_key' => $key))->row();
            if ($existing) {
                $CI->db->where('config_key', $key)
                       ->update('ezy_pos_config2', array('config_value' => $val));
            } else {
                $CI->db->insert('ezy_pos_config2',
                    array('config_key' => $key, 'config_value' => $val));
            }
        }
        return true;
    }
}
