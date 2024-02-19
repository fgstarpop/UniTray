<?php
/**
 * Plugin Name: ThueAPI for WooCommerce - Thanh toÃ¡n Ä‘Æ¡n giáº£n vá»›i há»‡ thá»‘ng tá»± Ä‘á»™ng !
 * Plugin URI: https://thueapi.com
 * Version: 1.0.9
 * Description: Giáº£i phÃ¡p xá»­ lÃ½ giao dá»‹ch tá»± Ä‘á»™ng cho Ä‘Æ¡n hÃ ng thanh toÃ¡n báº±ng hÃ¬nh thá»©c chuyá»ƒn khoáº£n qua cÃ¡c ngÃ¢n hÃ ng táº¡i Viá»‡t Nam. CÃ¡c ngÃ¢n hÃ ng thÃ´ng dá»¥ng nhÆ°: Vietcombank, Techcombank, ACB, Momo, MBBank, TPBank, VPBank...
 * Author: #CODETAY
 * Author URI: http://codetay.com
 * Tested up to: 5.7
 * WC tested up to: 5.1.0
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */

defined('ABSPATH') or die('Code your dream');

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}

add_action('plugins_loaded', function () {
    require_once(plugin_basename('classes/wc-thueapi.php'));
}, 11);

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('thueapi', plugin_dir_url(__FILE__) . 'assets/js/app.js', [], '1.0.0', true);
    wp_enqueue_style('thueapi', plugin_dir_url(__FILE__) . 'assets/css/app.css', [], '1.0.0', 'all');
});

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('thueapi', plugin_dir_url(__FILE__) . 'assets/js/app.admin.js', [], '1.0.0', true);
    wp_enqueue_style('thueapi', plugin_dir_url(__FILE__) . 'assets/css/app.admin.css', [], '1.0.0', 'all');
});

add_filter('woocommerce_payment_gateways', function ($gateways) {
    $gateways[] = 'ThueAPI_Gateway';
    return $gateways;
});

function addWcLessPaidPostStatus()
{
    register_post_status('wc-over-paid', [
        'label' => 'Thanh toÃ¡n dÆ°',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Thanh toÃ¡n dÆ° (%s)', 'Thanh toÃ¡n dÆ° (%s)')
    ]);

    register_post_status('wc-less-paid', [
        'label' => 'Thanh toÃ¡n thiáº¿u',
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Thanh toÃ¡n thiáº¿u (%s)', 'Thanh toÃ¡n thiáº¿u (%s)')
    ]);
}

add_action('init', 'addWcLessPaidPostStatus');

add_filter('wc_order_statuses', function ($orderStatuses) {

    $newOrderStatuses = [];

    foreach ($orderStatuses as $key => $status) {
        $newOrderStatuses[$key] = $status;
    }

    $newOrderStatuses = array_merge($newOrderStatuses, [
        'wc-over-paid' => _('Thanh toÃ¡n dÆ°'),
        'wc-less-paid' => _('Thanh toÃ¡n thiáº¿u')
    ]);

    return $newOrderStatuses;
});

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $actionLinks = [
        'premium_plugins' => sprintf('<a href="https://codetay.com"  target="_blank" style="color: #e64a19; font-weight: bold; font-size: 108%%;" title="%s">%s</a>', _('Premium Plugins'), _('Premium Plugins')),
        'settings' => sprintf('<a href="%s" title="%s">%s</a>', admin_url('admin.php?page=wc-settings&tab=checkout&section=thueapi'), _('Thiáº¿t láº­p'), _('Thiáº¿t láº­p')),
    ];
    return array_merge($actionLinks, $links);
});
