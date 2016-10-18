<?php 
/**
 * Product Filter advance option
 *
 * @link       http://theemon.com/p/Woo-Products-filter/LivePreview
 * @since      1.0.0
 *
 * @package    woo-products-filter
 * @subpackage woo-products-filter/admin/search/option
 */ 
$get = 'get';
$settings = array(
		'section_general_title' => array(
				'name' => esc_html__( 'Filter General Settings', 'woopf' ),
				'type' => 'title',
				'desc' => esc_html__( 'These settings will affect all filters.', 'woopf' ),
				'id' => 'wc_settings_woopf_general_title'
		),
		'woopf_enable' => array(
				'name' => esc_html__( 'Enable/Disable Filter Template Overrides', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Uncheck this option in order to disable the Product Filter template override and use the default WooCommerce or', 'woopf') . ' ' . $curr_theme->{$get}('Name') . ' ' . esc_html__('theme filter. This option should be unchecked if you are using the widget version.', 'woopf' ),
				'id'   => 'wc_settings_woopf_enable',
				'default' => 'yes'
		),
		'woopf_instock' => array(
				'name' => esc_html__( 'Show In Stock Products by Default', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to show the In Stock products by default.', 'woopf' ),
				'id'   => 'wc_settings_woopf_instock',
				'default' => 'no'
		),
		'section_general_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_general_end'
		),

		'section_ajax_title' => array(
				'name' => esc_html__( 'Filter AJAX Product Archives Settings', 'woopf' ),
				'type' => 'title',
				'desc' => esc_html__( 'AJAX Product Archives Settings - Setup this section to use AJAX on shop and product archive pages.', 'woopf' ),
				'id' => 'wc_settings_woopf_ajax_title'
		),
		'woopf_use_ajax' => array(
				'name' => esc_html__( 'Use AJAX On Product Archives', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to use AJAX load on shop and product archive pages.', 'woopf' ),
				'id'   => 'wc_settings_woopf_use_ajax',
				'default' => 'no'
		),
		'woopf_ajax_class' => array(
				'name' => esc_html__( 'Override AJAX Wrapper Class', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter custom wrapper class if you are using a broken template and the default setting is not working. Default class: .products', 'woopf' ),
				'id'   => 'wc_settings_woopf_ajax_class',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_ajax_product_class' => array(
				'name' => esc_html__( 'Override AJAX Product Class', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter custom products class if you are using a broken template and the default setting is not working. Default class: .type-product', 'woopf' ),
				'id'   => 'wc_settings_woopf_ajax_product_class',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_ajax_columns' => array(
				'name' => esc_html__( 'AJAX Product Columns', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Enter the number of columns to be used to display the product on product archieve page.', 'woopf' ),
				'id'   => 'wc_settings_woopf_ajax_columns',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_ajax_rows' => array(
				'name' => esc_html__( 'AJAX Product Rows', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Enter the number of rows to be used to display the product on product archieve page.', 'woopf' ),
				'id'   => 'wc_settings_woopf_ajax_rows',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'section_ajax_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_ajax_end'
		),

		'section_advanced_title' => array(
				'name' => esc_html__( 'Advanced Settings', 'woopf' ),
				'type' => 'title',
				'desc' => esc_html__( 'Advanced Settings - These settings will affect all filters.', 'woopf' ),
				'id' => 'wc_settings_woopf_advanced_title'
		),
		'woopf_disable_display' => array(
				'name' => esc_html__( 'Shop/Category Display Types And Product Filter', 'woopf' ),
				'type' => 'multiselect',
				'desc' => esc_html__( 'Select what display types will not show the Product Filter.  Use CTRL+Click to select multiple display types or deselect all.', 'woopf' ),
				'id'   => 'wc_settings_woopf_disable_display',
				'options' => array(
						'subcategories' => esc_html__( 'Show Categories', 'woopf' ),
						'both' => esc_html__( 'Show Both', 'woopf' )
				),
				'default' => array( 'subcategories' ),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_shop_disable' => array(
				'name' => esc_html__( 'Enable/Disable Shop Page Product Filter', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option in order to disable the Product Filter on Shop page. This option can be useful for themes with custom Shop pages, if checked the default WooCommerce or', 'woopf') . ' ' . $curr_theme->{$get}('Name') . ' ' . esc_html__('filter template will be overriden only on product archives that support it.', 'woopf' ),
				'id'   => 'wc_settings_woopf_shop_disable',
				'default' => 'no'
		),
		'woopf_default_templates' => array(
				'name' => esc_html__( 'Enable/Disable Default Filter Templates', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'If you have disabled the Product Filter Override Templates option at the top, then your default WooCommerce or', 'woopf') . ' ' . $curr_theme->{$get}('Name') . ' ' . esc_html__('filter templates will be shown. If you want do disable these default templates too, check this option. This option can be usefull for the widget version of the Product Filter.', 'woopf' ),
				'id'   => 'wc_settings_woopf_default_templates',
				'default' => 'no'
		),
		'woopf_disable_scripts' => array(
				'name' => esc_html__( 'Disable JavaScript Libraries', 'woopf' ),
				'type' => 'multiselect',
				'desc' => esc_html__( 'Select JavaScript libraries to disable. Use CTRL+Click to select multiple libraries or deselect all. Selected libraries will not be loaded.', 'woopf' ),
				'id'   => 'wc_settings_woopf_disable_scripts',
				'options' => array(
						'ionrange' => esc_html__( 'Ion Range Slider', 'woopf' ),
						'isotope' => esc_html__( 'Isotope', 'woopf' ),
						'mcustomscroll' => esc_html__( 'Malihu jQuery Scrollbar', 'woopf' )
				),
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_force_categories' => array(
				'name' => esc_html__( 'Force Filtering thru Categories', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option if you are having issues with the redirects. This option should never be checked unless something is wrong with the template you are using. This option also limits your categories filter. The categories filter should not be used if this option is activated. (This option has changed since the 2.3.0 release. Now all installations should be compatible with the redirects by default. Test your installation before activating the option again)', 'woopf' ),
				'id'   => 'wc_settings_woopf_force_categories',
				'default' => 'no'
		),
		'woopf_force_product' => array(
				'name' => esc_html__( 'Force Post Type Variable', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option if you are having issues with the searches. This option should never be checked unless something is wrong with the template you are using. Option will add the ?post_type=product parameter when filtering.', 'woopf' ),
				'id'   => 'wc_settings_woopf_force_product',
				'default' => 'no'
		),
		'woopf_force_redirects' => array(
				'name' => esc_html__( 'Disable Product Filter Redirects', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option if you are having issues with the shop filter redirects.', 'woopf' ),
				'id'   => 'wc_settings_woopf_force_redirects',
				'default' => 'no'
		),
		'woopf_force_emptyshop' => array(
				'name' => esc_html__( 'Disable Empty Shop Redirects', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option if you are having issues with the shop page redirects.', 'woopf' ),
				'id'   => 'wc_settings_woopf_force_emptyshop',
				'default' => 'no'
		),
		'woopf_use_variable_images' => array(
				'name' => esc_html__( 'Use Variable Images', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to use variable images override on shop and archive pages. CAUTION: This setting does not work on all servers by default. Additional server setup might be needed.', 'woopf' ),
				'id'   => 'wc_settings_woopf_use_variable_images',
				'default' => 'no'
		),
                //Edited
                'woopf_display_product_image' => array(
				'name' => esc_html__( 'Display Product Images In Search  ', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to display product images in product search. CAUTION: This setting does not work on all servers by default. Additional server setup might be needed.', 'woopf' ),
				'id'   => 'wc_settings_woopf_display_product_images',
				'default' => 'no'
		),
                
		'woopf_ajax_js' => array(
				'name' => esc_html__( 'AJAX jQuery and JS Refresh', 'woopf' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Input jQuery or JS code to execute after AJAX calls. This option is usefull if the JS is broken after these calls.', 'woopf' ),
				'id'   => 'wc_settings_woopf_ajax_js',
				'default' => '',
				'css' 		=> 'min-width:600px;margin-top:12px;min-height:150px;',
		),
		'section_advanced_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_advanced_end'
		),
);

