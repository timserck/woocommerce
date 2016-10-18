<?php 
/**
 * Product Filter presets option
 *
 * @link       http://theemon.com/p/Woo-Products-filter/LivePreview
 * @since      1.0.0
 *
 * @package    woo-products-filter
 * @subpackage woo-products-filter/admin/search/option
 */ 
$settings = array(
		'section_basic_title' => array(
				'name'     => esc_html__( 'Filter Basic Settings', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Setup you Product Filter appearance.', 'woopf' ),
				'id'       => 'wc_settings_woopf_basic_title'
		),
		'woopf_always_visible' => array(
				'name' => esc_html__( 'Always Visible', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'This option will make Product Filter visible without the slide up/down animation at all times.', 'woopf' ),
				'id'   => 'wc_settings_woopf_always_visible',
				'default' => 'no',
		),
		'woopf_click_filter' => array(
				'name' => esc_html__( 'Instant Filtering', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to disable the filter button and use instant product filtering.', 'woopf' ),
				'id'   => 'wc_settings_woopf_click_filter',
				'default' => 'no',
		),
		'woopf_show_counts' => array(
				'name' => esc_html__( 'Show Term Products Count', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to show products count with the terms.', 'woopf' ),
				'id'   => 'wc_settings_woopf_show_counts',
				'default' => 'no',
		),
		'woopf_show_search' => array(
				'name' => esc_html__( 'Show Term Search Fields', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to show search fields on supported terms.', 'woopf' ),
				'id'   => 'wc_settings_woopf_show_search',
				'default' => 'no',
		),
		'woopf_adoptive' => array(
				'name' => esc_html__( 'Enable/Disable Adoptive Filtering', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to enable the adoptive filtering.', 'woopf' ),
				'id'   => 'wc_settings_woopf_adoptive',
				'default' => 'no',
		),
		'woopf_adoptive_style' => array(
				'name' => esc_html__( 'Select Adoptive Filtering Style', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select style to use with the filtered terms.', 'woopf' ),
				'id'   => 'wc_settings_woopf_adoptive_style',
				'options' => array(
						'pf_adptv_default' => esc_html__( 'Hide Terms', 'woopf' ),
						'pf_adptv_unclick' => esc_html__( 'Disabled and Unclickable', 'woopf' ),
						'pf_adptv_click' => esc_html__( 'Disabled but Clickable', 'woopf' )
				),
				'default' => 'pf_adptv_default',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_disable_bar' => array(
				'name' => esc_html__( 'Disable Top Bar', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide the Product Filter top bar. This option will also make the filter always visible.', 'woopf' ),
				'id'   => 'wc_settings_woopf_disable_bar',
				'default' => 'no',
		),
		'woopf_disable_showresults' => array(
				'name' => esc_html__( 'Disable Show Results Title', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide the show results text from the filter title.', 'woopf' ),
				'id'   => 'wc_settings_woopf_disable_showresults',
				'default' => 'no',
		),
		'woopf_disable_sale' => array(
				'name' => esc_html__( 'Disable Sale Button', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide the Product Filter sale button.', 'woopf' ),
				'id'   => 'wc_settings_woopf_disable_sale',
				'default' => 'no',
		),
		'woopf_disable_instock' => array(
				'name' => esc_html__( 'Disable In Stock Button', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide the Product Filter in stock button.', 'woopf' ),
				'id'   => 'wc_settings_woopf_disable_instock',
				'default' => 'no',
		),
		'woopf_disable_reset' => array(
				'name' => esc_html__( 'Disable Reset Button', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide the Product Filter reset button.', 'woopf' ),
				'id'   => 'wc_settings_woopf_disable_reset',
				'default' => 'no',
		),
		'woopf_noproducts' => array(
				'name' => esc_html__( 'Override No Products Action', 'woopf' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'Input HTML/Shortcode to override the default action when no products are found. Default action means that random products will be shown when there are no products within the filter query.', 'woopf' ),
				'id'   => 'wc_settings_woopf_noproducts',
				'default' => '',
				'css' 		=> 'min-width:600px;margin-top:12px;min-height:150px;',
		),
		'section_basic_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_enable_end'
		),
		'section_style_title' => array(
				'name'     => esc_html__( 'Filter Style', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Select style preset to use. Use custom preset for your own style. Use Disable CSS to disable all CSS for product filter.', 'woopf' ),
				'id'       => 'wc_settings_woopf_style_title'
		),
		'woopf_limit_max_height' => array(
				'name' => esc_html__( 'Limit Max Height', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'check this option to limit the max height for the filter.', 'woopf' ),
				'id'   => 'wc_settings_woopf_limit_max_height',
				'default' => 'no',
		),
		'woopf_max_height' => array(
				'name' => esc_html__( 'Max Height', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Set the Max Height value.', 'woopf' ),
				'id'   => 'wc_settings_woopf_max_height',
				'default' => 150,
				'custom_attributes' => array(
						'min' 	=> 100,
						'max' 	=> 300,
						'step' 	=> 1
				),
				'css' => 'width:100px;margin-right:12px;'
		),
		'woopf_custom_scrollbar' => array(
				'name' => esc_html__( 'Use Custom Scroll Bars', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to override default browser scroll bars with javascrips scrollbars in Max Height mode.', 'woopf' ),
				'id'   => 'wc_settings_woopf_custom_scrollbar',
				'default' => 'yes',
		),
		'woopf_style_checkboxes' => array(
				'name' => esc_html__( 'Select Checkbox Style', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select style for the term checkboxes.', 'woopf' ),
				'id'   => 'wc_settings_woopf_style_checkboxes',
				'options' => array(
						'woopf_round' => esc_html__( 'Round', 'woopf' ),
						'woopf_square' => esc_html__( 'Square', 'woopf' ),
						'woopf_checkbox' => esc_html__( 'Checkbox', 'woopf' ),
				),
				'default' => 'pf_round',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_button_position' => array(
				'name' => esc_html__( 'Select Filter Buttons Position', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select position of the filter buttons (Filter selected).', 'woopf' ),
				'id'   => 'wc_settings_woopf_button_position',
				'options' => array(
						'bottom' => esc_html__( 'Bottom', 'woopf' ),
						'top' => esc_html__( 'Top', 'woopf' )
				),
				'default' => 'bottom',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_submit' => array(
				'name' => esc_html__( 'Override Filter Submit Text', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Override "Filter selected", the default filter submit button text.', 'woopf' ),
				'id'   => 'wc_settings_woopf_submit',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_loader' => array(
				'name' => esc_html__( 'Select AJAX Loader Icon', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select AJAX loader icon.', 'woopf' ),
				'id'   => 'wc_settings_woopf_loader',
				'options' => array(
						'audio' => esc_html__( 'Audio', 'woopf' ),
						'ball-triangle' => esc_html__( 'Ball Triangle', 'woopf' ),
						'bars' => esc_html__( 'Bars', 'woopf' ),
						'circles' => esc_html__( 'Circles', 'woopf' ),
						'grid' => esc_html__( 'Grid', 'woopf' ),
						'hearts' => esc_html__( 'Hearts', 'woopf' ),
						'oval' => esc_html__( 'Oval', 'woopf' ),
						'puff' => esc_html__( 'Puff', 'woopf' ),
						'rings' => esc_html__( 'Rings', 'woopf' ),
						'spinning-circles' => esc_html__( 'Spining Circles', 'woopf' ),
						'tail-spin' => esc_html__( 'Tail Spin', 'woopf' ),
						'circles' => esc_html__( 'Circles', 'woopf' ),
						'three-dots' => esc_html__( 'Three Dots', 'woopf' )
				),
				'default' => 'oval',
				'css' => 'width:300px;margin-right:12px;'
		),
		'section_style_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_style_end'
		),
		'section_title' => array(
				'name'     => esc_html__( 'Select Filters', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Select filters to use on default template or current filter preset. For basic filters, shown in green and red depending on their use, the settings can be found bellow. Advanced and range filters, shown a blue, are set within the manager itself. Click and drag the filters to reorder.', 'woopf' ),
				'id'       => 'wc_settings_woopf_section_title'
		),
		'woopf_filters' => array(
				'name' => esc_html__( 'Select Filters', 'woopf' ),
				'type' => 'pf_filter',
				'desc' => esc_html__( 'Select filters. Click on a filter to activate or create advanced filters. Click and drag to reorder filters.', 'woopf' )
		),
		'section_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_section_end'
		),

		'section_perpage_filter_title' => array(
				'name'     => esc_html__( 'Products Per Filter Settings', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Setup products per page filter.', 'woopf' ),
				'id'       => 'wc_settings_woopf_perpage_filter_title'
		),
		'woopf_perpage_title' => array(
				'name' => esc_html__( 'Override Products Per Page Filter Title', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter title for the products per page filter. If you leave this field blank default will be used.', 'woopf' ),
				'id'   => 'wc_settings_woopf_perpage_title',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_perpage_label' => array(
				'name' => esc_html__( 'Override Products Per Page Filter Label', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter label for the products per page filter. If you leave this field blank default will be used.', 'woopf' ),
				'id'   => 'wc_settings_woopf_perpage_label',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_perpage_range' => array(
				'name' => esc_html__( 'Per Page Filter Initial', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Initial products per page value.', 'woopf' ),
				'id'   => 'wc_settings_woopf_perpage_range',
				'default' => 20,
				'custom_attributes' => array(
						'min' 	=> 3,
						'max' 	=> 999,
						'step' 	=> 1
				),
				'css' => 'width:100px;margin-right:12px;'
		),
		'woopf_perpage_range_limit' => array(
				'name' => esc_html__( 'Per Page Filter Values', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Number of product per page values.', 'woopf' ),
				'id'   => 'wc_settings_woopf_perpage_range_limit',
				'default' => 5,
				'custom_attributes' => array(
						'min' 	=> 2,
						'max' 	=> 20,
						'step' 	=> 1
				),
				'css' => 'width:100px;margin-right:12px;'
		),
		'section_perpage_filter_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_perpage_filter_end'
		),
		'section_instock_filter_title' => array(
				'name'     => esc_html__( 'In Stock Filter Settings', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Setup in stock filter.', 'woopf' ),
				'id'       => 'wc_settings_woopf_instock_filter_title'
		),
		'woopf_instock_title' => array(
				'name' => esc_html__( 'Override In Stock Filter Title', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter title for the in stock filter. If you leave this field blank default will be used.', 'woopf' ),
				'id'   => 'wc_settings_woopf_instock_title',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'section_instock_filter_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_instock_filter_end'
		),
		'section_orderby_filter_title' => array(
				'name'     => esc_html__( 'Sort By Filter Settings', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Setup sort by filter.', 'woopf' ),
				'id'       => 'wc_settings_woopf_orderby_filter_title'
		),
		'woopf_orderby_title' => array(
				'name' => esc_html__( 'Override Sort By Filter Title', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter title for the sort by filter. If you leave this field blank default will be used.', 'woopf' ),
				'id'   => 'wc_settings_woopf_orderby_title',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_include_orderby' => array(
				'name' => esc_html__( 'Select Sort By Terms', 'woopf' ),
				'type' => 'multiselect',
				'desc' => esc_html__( 'Select Sort by terms to include. Use CTRL+Click to select multiple Sort by terms or deselect all to use all Sort by terms.', 'woopf' ),
				'id'   => 'wc_settings_woopf_include_orderby',
				'options' => array(
						'menu_order'    => esc_html__( 'Default', 'woopf' ),
						'comment_count' => esc_html__( 'Review Count', 'woopf' ),
						'popularity'    => esc_html__( 'Popularity', 'woopf' ),
						'rating'        => esc_html__( 'Average rating', 'woopf' ),
						'date'          => esc_html__( 'Newness', 'woopf' ),
						'price'         => esc_html__( 'Price: low to high', 'woopf' ),
						'price-desc'    => esc_html__( 'Price: high to low', 'woopf' ),
						'rand'          => esc_html__( 'Random Products', 'woopf' ),
						'title'         => esc_html__( 'Product Name', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_orderby_none' => array(
				'name' => esc_html__( 'Order By Hide None', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide None on order by filter.', 'woopf' ),
				'id'   => 'wc_settings_woopf_orderby_none',
				'default' => 'no',
		),
		'section_orderby_filter_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_orderby_filter_end'
		),

		'section_price_filter_title' => array(
				'name'     => esc_html__( 'By Price Filter Settings', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Setup by price filter.', 'woopf' ),
				'id'       => 'wc_settings_woopf_price_filter_title'
		),
		'woopf_price_title' => array(
				'name' => esc_html__( 'Override Price Filter Title', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter title for the price filter. If you leave this field blank default will be used.', 'woopf' ),
				'id'   => 'wc_settings_woopf_price_title',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_price_range' => array(
				'name' => esc_html__( 'Price Range Filter Initial Price', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Initial price for the filter.', 'woopf' ),
				'id'   => 'wc_settings_woopf_price_range',
				'default' => 100,
				'custom_attributes' => array(
						'min' 	=> 0.5,
						'max' 	=> 9999999,
						'step' 	=> 0.1
				),
				'css' => 'width:100px;margin-right:12px;'
		),
		'woopf_price_range_add' => array(
				'name' => esc_html__( 'Price Range Filter Price Add', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Price to add.', 'woopf' ),
				'id'   => 'wc_settings_woopf_price_range_add',
				'default' => 100,
				'custom_attributes' => array(
						'min' 	=> 0.5,
						'max' 	=> 9999999,
						'step' 	=> 0.1
				),
				'css' => 'width:100px;margin-right:12px;'
		),
		'woopf_price_range_limit' => array(
				'name' => esc_html__( 'Price Range Filter Intervals', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Number of price intervals to use. E.G. You have set the initial price to 99.9, and the add price is set to 100, you will achieve filtering like 0-99.9, 99.9-199.9, 199.9- 299.9 for the number of times as set in the price intervals setting.', 'woopf' ),
				'id'   => 'wc_settings_woopf_price_range_limit',
				'default' => 6,
				'custom_attributes' => array(
						'min' 	=> 2,
						'max' 	=> 20,
						'step' 	=> 1
				),
				'css' => 'width:100px;margin-right:12px;'
		),
		'woopf_price_none' => array(
				'name' => esc_html__( 'Price Range Hide None', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide None on price filter.', 'woopf' ),
				'id'   => 'wc_settings_woopf_price_none',
				'default' => 'no',
		),
		'section_price_filter_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_price_filter_end'
		),
		'section_cat_filter_title' => array(
				'name'     => esc_html__( 'By Category Filter Settings', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Setup by category filter.', 'woopf' ),
				'id'       => 'wc_settings_woopf_cat_filter_title'
		),
		'woopf_cat_title' => array(
				'name' => esc_html__( 'Override Category Filter Title', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter title for the category filter. If you leave this field blank default will be used.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_title',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_include_cats' => array(
				'name' => esc_html__( 'Select Categories', 'woopf' ),
				'type' => 'multiselect',
				'desc' => esc_html__( 'Select categories to include. Use CTRL+Click to select multiple categories or deselect all.', 'woopf' ),
				'id'   => 'wc_settings_woopf_include_cats',
				'options' => $curr_cats,
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_cat_orderby' => array(
				'name' => esc_html__( 'Categories Order By', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select the categories order.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_orderby',
				'options' => array(
						'' => esc_html__( 'None', 'woopf' ),
						'id' => esc_html__( 'ID', 'woopf' ),
						'name' => esc_html__( 'Name', 'woopf' ),
						'slug' => esc_html__( 'Slug', 'woopf' ),
						'count' => esc_html__( 'Count', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_cat_order' => array(
				'name' => esc_html__( 'Categories Order', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select ascending or descending order.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_order',
				'options' => array(
						'ASC' => esc_html__( 'ASC', 'woopf' ),
						'DESC' => esc_html__( 'DESC', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_cat_limit' => array(
				'name' => esc_html__( 'Limit Categories', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Limit number of categories to be shown. If limit is set categories with most posts will be shown first.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_limit',
				'default' => 0,
				'custom_attributes' => array(
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
				),
				'css' => 'width:100px;margin-right:12px;'
		),
		'woopf_cat_hierarchy' => array(
				'name' => esc_html__( 'Use Category Hierarchy', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to enable category hierarchy.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_hierarchy',
				'default' => 'no',
		),
		'woopf_cat_hierarchy_mode' => array(
				'name' => esc_html__( 'Categories Hierarchy Mode', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to expand parent categories on load.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_hierarchy_mode',
				'default' => 'no',
		),
		'woopf_cat_mode' => array(
				'name' => esc_html__( 'Categories Hierarchy Filtering Mode', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select how to show categories upon filtering.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_mode',
				'options' => array(
						'showall' => esc_html__( 'Show all', 'woopf' ),
						'subcategories' => esc_html__( 'Keep only parent and children categories', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_cat_multi' => array(
				'name' => esc_html__( 'Use Multi Select', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to enable multi-select on categories.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_multi',
				'default' => 'no',
		),
		'woopf_cat_relation' => array(
				'name' => esc_html__( 'Multi Select Categories Relation', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select categories relation when multiple terms are selected.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_relation',
				'options' => array(
						'IN' => esc_html__( 'Filtered products have at least one term (IN)', 'woopf' ),
						'AND' => esc_html__( 'Filtered products have selected terms (AND)', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_cat_adoptive' => array(
				'name' => esc_html__( 'Use Adoptive Filtering', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to use adoptive filtering on categories.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_adoptive',
				'default' => 'no',
		),
		'woopf_cat_none' => array(
				'name' => esc_html__( 'Categories Hide None', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide None on categories.', 'woopf' ),
				'id'   => 'wc_settings_woopf_cat_none',
				'default' => 'no',
		),
		'section_cat_filter_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_cat_filter_end'
		),
		'section_tag_filter_title' => array(
				'name'     => esc_html__( 'By Tag Filter Settings', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Setup by tag filter.', 'woopf' ),
				'id'       => 'wc_settings_woopf_tag_filter_title'
		),
		'woopf_tag_title' => array(
				'name' => esc_html__( 'Override Tag Filter Title', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter title for the tag filter. If you leave this field blank default will be used.', 'woopf' ),
				'id'   => 'wc_settings_woopf_tag_title',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_include_tags' => array(
				'name' => esc_html__( 'Select Tags', 'woopf' ),
				'type' => 'multiselect',
				'desc' => esc_html__( 'Select tags to include. Use CTRL+Click to select multiple tags or deselect all.', 'woopf' ),
				'id'   => 'wc_settings_woopf_include_tags',
				'options' => $curr_tags,
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_tag_orderby' => array(
				'name' => esc_html__( 'Tags Order By', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select the tags order.', 'woopf' ),
				'id'   => 'wc_settings_woopf_tag_orderby',
				'options' => array(
						'' => esc_html__( 'None', 'woopf' ),
						'id' => esc_html__( 'ID', 'woopf' ),
						'name' => esc_html__( 'Name', 'woopf' ),
						'slug' => esc_html__( 'Slug', 'woopf' ),
						'count' => esc_html__( 'Count', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_tag_order' => array(
				'name' => esc_html__( 'Tags Order', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select ascending or descending order.', 'woopf' ),
				'id'   => 'wc_settings_woopf_tag_order',
				'options' => array(
						'ASC' => esc_html__( 'ASC', 'woopf' ),
						'DESC' => esc_html__( 'DESC', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_tag_limit' => array(
				'name' => esc_html__( 'Limit Tags', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Limit number of tags to be shown. If limit is set tags with most posts will be shown first.', 'woopf' ),
				'id'   => 'wc_settings_woopf_tag_limit',
				'default' => 0,
				'custom_attributes' => array(
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
				),
				'css' => 'width:100px;margin-right:12px;'
		),
		'woopf_tag_multi' => array(
				'name' => esc_html__( 'Use Multi Select', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to enable multi-select on tags.', 'woopf' ),
				'id'   => 'wc_settings_woopf_tag_multi',
				'default' => 'no',
		),
		'woopf_tag_relation' => array(
				'name' => esc_html__( 'Multi Select Tags Relation', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select tags relation when multiple terms are selected.', 'woopf' ),
				'id'   => 'wc_settings_woopf_tag_relation',
				'options' => array(
						'IN' => esc_html__( 'Filtered products have at least one term (IN)', 'woopf' ),
						'AND' => esc_html__( 'Filtered products have selected terms (AND)', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_tag_adoptive' => array(
				'name' => esc_html__( 'Use Adoptive Filtering', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to use adoptive filtering on tags.', 'woopf' ),
				'id'   => 'wc_settings_woopf_tag_adoptive',
				'default' => 'no',
		),
		'woopf_tag_none' => array(
				'name' => esc_html__( 'Tags Hide None', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide None on tags.', 'woopf' ),
				'id'   => 'wc_settings_woopf_tag_none',
				'default' => 'no',
		),
		'section_tag_filter_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_tag_filter_end'
		),
		'section_char_filter_title' => array(
				'name'     => esc_html__( 'By Characteristics Filter Settings', 'woopf' ),
				'type'     => 'title',
				'desc'     => esc_html__( 'Setup by characteristics filter.', 'woopf' ),
				'id'       => 'wc_settings_woopf_char_filter_title'
		),
		'woopf_custom_tax' => array(
				'name' => esc_html__( 'Use Characteristics', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Enable this option to get custom characteristics product meta box.', 'woopf' ),
				'id'   => 'wc_settings_woopf_custom_tax',
				'default' => 'yes',
		),
		'woopf_custom_tax_title' => array(
				'name' => esc_html__( 'Override Characteristics Filter Title', 'woopf' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter title for the characteristics filter. If you leave this field blank default will be used.', 'woopf' ),
				'id'   => 'wc_settings_woopf_custom_tax_title',
				'default' => '',
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_include_chars' => array(
				'name' => esc_html__( 'Select Characteristics', 'woopf' ),
				'type' => 'multiselect',
				'desc' => esc_html__( 'Select characteristics to include. Use CTRL+Click to select multiple characteristics or deselect all.', 'woopf' ),
				'id'   => 'wc_settings_woopf_include_chars',
				'options' => $curr_chars,
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_custom_tax_orderby' => array(
				'name' => esc_html__( 'Characteristics Order By', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select the characteristics order.', 'woopf' ),
				'id'   => 'wc_settings_woopf_custom_tax_orderby',
				'options' => array(
						'' => esc_html__( 'None', 'woopf' ),
						'id' => esc_html__( 'ID', 'woopf' ),
						'name' => esc_html__( 'Name', 'woopf' ),
						'slug' => esc_html__( 'Slug', 'woopf' ),
						'count' => esc_html__( 'Count', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_custom_tax_order' => array(
				'name' => esc_html__( 'Characteristics Order', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select ascending or descending order.', 'woopf' ),
				'id'   => 'wc_settings_woopf_custom_tax_order',
				'options' => array(
						'ASC' => esc_html__( 'ASC', 'woopf' ),
						'DESC' => esc_html__( 'DESC', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_custom_tax_limit' => array(
				'name' => esc_html__( 'Limit Characteristics', 'woopf' ),
				'type' => 'number',
				'desc' => esc_html__( 'Limit number of characteristics to be shown. If limit is set characteristics with most posts will be shown first.', 'woopf' ),
				'id'   => 'wc_settings_woopf_custom_tax_limit',
				'default' => 0,
				'custom_attributes' => array(
						'min' 	=> 0,
						'max' 	=> 100,
						'step' 	=> 1
				),
				'css' => 'width:100px;margin-right:12px;'
		),
		'woopf_chars_multi' => array(
				'name' => esc_html__( 'Use Multi Select', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to enable multi-select on characteristics.', 'woopf' ),
				'id'   => 'wc_settings_woopf_chars_multi',
				'default' => 'no',
		),
		'woopf_custom_tax_relation' => array(
				'name' => esc_html__( 'Multi Select Characteristics Relation', 'woopf' ),
				'type' => 'select',
				'desc' => esc_html__( 'Select characteristics relation when multiple terms are selected.', 'woopf' ),
				'id'   => 'wc_settings_woopf_custom_tax_relation',
				'options' => array(
						'IN' => esc_html__( 'Filtered products have at least one term (IN)', 'woopf' ),
						'AND' => esc_html__( 'Filtered products have selected terms (AND)', 'woopf' )
				),
				'default' => array(),
				'css' => 'width:300px;margin-right:12px;'
		),
		'woopf_chars_adoptive' => array(
				'name' => esc_html__( 'Use Adoptive Filtering', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to use adoptive filtering on characteristics.', 'woopf' ),
				'id'   => 'wc_settings_woopf_chars_adoptive',
				'default' => 'no',
		),
		'woopf_chars_none' => array(
				'name' => esc_html__( 'Characteristics Hide None', 'woopf' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Check this option to hide None on characteristics.', 'woopf' ),
				'id'   => 'wc_settings_woopf_chars_none',
				'default' => 'no',
		),
		'section_char_filter_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_woopf_char_filter_end'
		),

);

