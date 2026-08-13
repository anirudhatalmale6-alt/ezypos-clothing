<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Page access helpers.
 *
 * Every page in the menu now has a matching checkbox on the user form. The menu
 * hides what a user may not see; these helpers stop the same page being reached
 * by typing the URL. Admins (user_role 1) always pass.
 */

if ( ! function_exists('user_can'))
{
    /**
     * @param string $sessionKey e.g. 'privRe_cashflow'
     */
    function user_can($sessionKey)
    {
        $CI =& get_instance();
        if ($CI->session->userdata('userrole') == 1) { return true; }
        return $CI->session->userdata($sessionKey) == 1;
    }
}

if ( ! function_exists('require_priv'))
{
    /**
     * Show a 404 unless the user holds the privilege. 404 rather than a message
     * so a restricted page looks like it simply is not there.
     */
    function require_priv($sessionKey)
    {
        if ( ! user_can($sessionKey)) { show_404(); }
    }
}
