<?php
/**
 * Orderby product.
 *
 * @link       http://theemon.com/p/Woo-Products-filter/LivePreview
 * @since      1.0.0
 *
 * @package    woo-products-filter
 * @subpackage woo-products-filter/public
 */
	global $prdctfltr_global;
	
	$prdctfltr_global['woo_template'] = 'order_by';
	include_once( dirname( __FILE__ ) . '/woopf-product-filter.php' );
?>
