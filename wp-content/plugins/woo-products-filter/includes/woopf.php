<?php
/**
 * WooCommerce products filter
 *
 * @since      1.0.0
 *
 * @package    Woo Products Filter
 * @subpackage woo-products-filter/includes
 */

require WOOPF_PATH . 'includes/setup.php';
/* Launch the woo-filter */
$woopfSetup = new WOOPF\Core\Admin\Setup();
$woocommerceActive = 'woocommerceActive';

if (!$woopfSetup->{$woocommerceActive}()) {
    add_action('admin_notices', array('WOOPF\Core\Admin\Setup', 'woocommercerActivateNotice'));
    add_action('admin_init', array('WOOPF\Core\Admin\Setup', 'pluginDeactivate'));
    return;
}

global $woopf_options;
$woopf_options  = get_option("woopf_wp");

add_action('admin_menu', array('WOOPF\Core\Admin\Setup', 'adminMenu'));
add_filter('plugin_action_links', array('WOOPF\Core\Admin\Setup', 'woopf_settings_link'), 10, 2);

/**
 * Define the locale for this plugin for internationalization.
 *
 * Uses the WOOPF\Core\Admin in order to set the domain and to register the hook
 * with WordPress.
 *
 * @since    1.0.0
 * @access   private
 */
add_action ('init', array('WOOPF\Core\Admin\Setup', 'woopf_load_plugin_textdomain') );
