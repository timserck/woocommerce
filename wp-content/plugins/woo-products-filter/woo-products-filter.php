<?php
/**
 * @link            http://theemon.com/p/Woo-Products-filter/LivePreview
 * @since           1.0.0
 * @package         woo-products-filter
 * 
 * 
 * Plugin Name:     Woo Products Filter
 * Plugin URI:      http://www.theemon.com/
 * Description:     Filter and sort wocommerce products by category, product name, price, colors, etc...
 * Version:         1.0.0
 * Text Domain:     woopf
 * Domain Path:     /languages
 * Author:          Theemon
 * Author URI:      http://theemon.com/p/Woo-Products-filter/LivePreview
 * License: 
 */ 

// If this file is called directly, abort.
if (!defined('WPINC')) { 
    die; 
}

define("WOOPF_VERSION", "1.0.0");
define('WOOPF_URL', plugins_url('', __FILE__));
define("WOOPF_PATH", plugin_dir_path( __FILE__ ));
define("WOOPF_BASENAME", plugin_basename(__FILE__));

require_once ( WOOPF_PATH . 'includes/woopf.php' );
require_once ( WOOPF_PATH . 'admin/search/woopf-search-settings.php' );
require_once ( WOOPF_PATH . 'admin/woopf-widgets.php' );
require_once ( WOOPF_PATH . 'public/woopf-public.php');
require_once ( WOOPF_PATH . 'includes/class-woopf-activator.php' );

/** This action is documented in includes/class-woopf-activator.php */
register_activation_hook(__FILE__, array('WOOPF_Activator', 'activate'));

/**
 * The code that runs during plugin deactivation.
 */
require_once ( WOOPF_PATH . 'includes/class-woopf-deactivator.php' );
/** This action is documented in includes/class-woopf-deactivator.php */
register_deactivation_hook(__FILE__, array('WOOPF_Deactivator', 'deactivate'));
