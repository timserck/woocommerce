<?php
/**
 * Woo Products Filter
 * Setup  functionality 
 *
 * @since      1.0.0
 *
 * @package    woo-products-filter
 * @subpackage woo-products-filter/includes
 */

namespace WOOPF\Core\Admin;

class Setup {

	/**
	 * Check woocommerce active
	 * @since  1.0.0
	 */
    public function woocommerceActive() {
        $activePlugins = \get_option('active_plugins');
        return ( in_array('woocommerce/woocommerce.php', $activePlugins) ) ? true : false;
    }

    /**
     * Woocommerce activate notice
     * @since  1.0.0
     */
    public static function woocommercerActivateNotice() {
        ?><div class="error notice is-dismissible">
            <p><?php esc_html_e("Woo Products Filter could not detect woocommerce plugin. Make sure you have activated woocommerce plugin.", "woopf"); ?></p>
        </div>
        <?php
    }
    
    /**
     * Show save message setting
     * @since  1.0.0
     */
    public static function show_message(){
        if(isset($_GET['saved']) && $_GET['saved'] == 'true') :
        ?>
       <div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> <p><strong><?php esc_html_e("Your settings have been saved.","woopf"); ?></strong></p></div>
     <?php
        endif;
    }

    /**
     * Plugin deactive
     * @since  1.0.0
     */
    public static function pluginDeactivate() {
        \deactivate_plugins(WOOPF_BASENAME);
        if (isset($_GET['activate']))
            unset($_GET['activate']);
    }

    /**
     * woopf admin menu
     * @since  1.0.0
     */
    public static function adminMenu() {
        global $submenu;
        $icon = "";
           
        	add_menu_page(esc_html__('Woo Product Filter','woopf'), esc_html__('WooProduct Filter','woopf'), 'manage_options' , 'woo-pf' , array( __CLASS__ , 'woo_filter') , WOOPF_URL.'/public/img/filter.png' , '58');
        	add_submenu_page('woo-pf', esc_html__('General Setting','woopf'), esc_html__('General Setting','woopf'), 'manage_options', 'woo-pf', array( __CLASS__ , 'woo_filter'));
        	
    }
    
    /**
     * woopf setting link
     * @since  1.0.0
     */
    public static function woopf_settings_link($links, $file) {
        if (!\is_admin() || !\current_user_can('manage_options'))
            return $links;
        
        static $plugin;

        $plugin = WOOPF_BASENAME;
        if ($file == $plugin) {
            $settings_link = sprintf('<a href="%s">%s</a>', \admin_url('admin.php') . '?page=woo-pf', esc_html__('Settings', 'woopf'));
            \array_unshift($links, $settings_link);
        }
      return $links;
    }
    
    /**
     * woo-filter function call
     * @since  1.0.0
     */
    public static function woo_filter() { ?>
		 <?php
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = $_GET[ 'tab' ];
            } // end if
        ?>         
        <form method="post" action="">
			<?php require_once ( WOOPF_PATH . 'admin/search/woopf-search-settings.php' );
				do_action('woopf_settings_tabs_settings_products_filter');
				submit_button(); ?>     
        </form>
        <?php
	} 
    
    /**
     * Uses the to set the domain and to register the hook with WordPress.
     * @since  1.0.0
     */
    public static function woopf_load_plugin_textdomain() {
    	load_plugin_textdomain(
    			'woopf', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    	);
    }

}
