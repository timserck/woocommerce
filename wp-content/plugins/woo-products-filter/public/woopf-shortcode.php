<?php
/**
 * Product Filter Shortcodes
 *
 * @link       http://theemon.com/p/Woo-Products-filter/LivePreview
 * @since      1.0.0
 *
 * @package    woo-products-filter
 * @subpackage woo-products-filter/public
 */

	class WC_Woopf_Shortcodes {

		public static function init() {
			$class = __CLASS__;
			new $class;
		}

		/**
		 * Define the core functionality.
		 * @since    1.0.0
		 */
		function __construct() {
			add_shortcode( 'woopf_filter', array( &$this, 'woopf_sc_get_filter' ) );
			add_action('wp_ajax_nopriv_woopf_respond', array( &$this, 'woopf_respond' ) );
			add_action('wp_ajax_woopf_respond', array( &$this, 'woopf_respond' ) );
		}
		
	
			/**
		 * Shortcode AJAX Respond
		 * @since    1.0.0
		 */
		function woopf_respond() {

			global $prdctfltr_global;
			
			$shortcode_params = explode('|', $_POST['pf_shortcode']);

			$preset = ( $shortcode_params[0] !== 'false' ? $shortcode_params[0] : '' );
			$columns = ( $shortcode_params[1] !== 'false' ? $shortcode_params[1] : 4 );
			$rows = ( $shortcode_params[2] !== 'false' ? $shortcode_params[2] : 4 );
			$pagination = ( $shortcode_params[3] !== 'false' ? $shortcode_params[3] : '' );
			$no_products = ( $shortcode_params[4] !== 'false' ? $shortcode_params[4] : '' );
			$show_products = ( $shortcode_params[5] !== 'false' ? $shortcode_params[5] : '' );
			$use_filter = ( $shortcode_params[6] !== 'false' ? $shortcode_params[6] : '' );
			$action = ( $shortcode_params[7] !== 'false' ? $shortcode_params[7] : '' );
			$bot_margin = ( $shortcode_params[8] !== 'false' ? $shortcode_params[8] : '' );
			$class = ( $shortcode_params[9] !== 'false' ? $shortcode_params[9] : '' );
			$shortcode_id = ( $shortcode_params[10] !== 'false' ? $shortcode_params[10] : '' );
			$disable_overrides = ( $shortcode_params[11] !== 'false' ? $shortcode_params[11] : '' );

			$res_paged = ( isset( $_POST['pf_paged'] ) ? $_POST['pf_paged'] : $_POST['pf_page'] );

			$ajax_query = $_POST['pf_query'];

			$current_page = WC_Woopf::woopf_get_between( $ajax_query, 'paged=', '&' );
			$page = $res_paged;

			$args = str_replace( 'paged=' . $current_page . '&', 'paged=' . $page . '&', $ajax_query );

			$prdctfltr_global['ajax_query'] = $args;

			if ( $no_products == 'yes' ) :
				$use_filter = 'no';
				$pagination = 'no';
				$orderby = 'rand';
			endif;

			$add_ajax = ' data-query="' . $args . '" data-page="' . $res_paged . '" data-shortcode="' . $_POST['pf_shortcode'] . '"';

			$bot_margin = (int)$bot_margin;
			$margin = " style='margin-bottom:" . $bot_margin . "px'";

			if ( isset($_POST['pf_filters']) ) : $curr_filters = $_POST['pf_filters']; 
			else : $curr_filters = array(); 
			endif;

			$filter_args = '';
			foreach ( $curr_filters as $k => $v ) :
				if ( strpos($v, ',') ) { $new_v = str_replace(',', '%2C', $v); }
				else if ( strpos($v, '+') ) { $new_v = str_replace('+', '%2C', $v); }
				else { $new_v = $v; }
				$filter_args .= '&' . $k . '=' . $new_v;
			endforeach;

			$args = $args . $filter_args;

			$prdctfltr_global['ajax_paged'] = $res_paged;
			$prdctfltr_global['active_filters'] = $curr_filters;

			if ( $action !== '' ) : $prdctfltr_global['action'] = $action; endif;
			if ( $preset !== '' ) : $prdctfltr_global['preset'] = $preset; endif;
			if ( $disable_overrides !== '' ) : $prdctfltr_global['disable_overrides'] = $disable_overrides; endif;

			$out = '';

			global $woocommerce, $woocommerce_loop;

			$woocommerce_loop['columns'] = $columns;

			$prdctfltr_global['ajax'] = true;
			$prdctfltr_global['sc_ajax'] = $_POST['pf_mode'] == 'no' ? 'no' : null;

			$products = new WP_Query( $args . '&prdctfltr=active' );

			global $wp_the_query;
			$wp_the_query = $products;

			ob_start();

			if ( $use_filter == 'yes' ) :
				include_once( WC_Woopf::$dir . 'woopf-product-filter.php' );
			endif;
			
             $have_posts = 'have_posts';
             $the_post = 'the_post';
			if ( $products->{$have_posts}() ) {
				if ( $show_products == 'yes' ) :
					woocommerce_product_loop_start();
					
					while ( $products->{$have_posts}() ) : $products->{$the_post}();
					//------call attribute funation--------
					$this->woopf_check_attribute($products->post->ID);
					//----------------
						wc_get_template_part( 'content', 'product' );
					endwhile;
					//print_r($this->woopf_filter_attrs);
					if(count($this->woopf_filter_attrs)> 0) :
						foreach($this->woopf_filter_attrs as $key=> $val):
							?><style> .woopf_filter.woopf_attributes.woopf_<?php print($key); ?>{ display:block }; </style><?php
						endforeach; 
					endif;			
					woocommerce_product_loop_end();
				else :
				 $pagination = 'no'; 
				endif;
			}
			else if ( $_POST['pf_widget'] == 'yes' ) {
				$prdctfltr_global['widget_search'] = $_POST['pf_widget'];
				include_once( WC_Woopf::$dir . 'woopf-product-filter.php' );
			}

			$prdctfltr_global['ajax'] = null;

			$shortcode = str_replace( 'type-product', 'product type-product', ob_get_clean() );

			$out .= '<div' . ( $shortcode_id != '' ? ' id="'.$shortcode_id.'"' : '' ) . ' class="woopf_sc_products woocommerce woopf_ajax' . ( $class != '' ? ' '.$class.'' : '' ) . '"'.$margin.$add_ajax.'>';
			$out .= do_shortcode($shortcode);

			if ( $pagination == 'yes' ) :

				ob_start();

				add_filter( 'woocommerce_pagination_args', 'WC_Woopf::woopf_pagination_filter', 999, 1 );
				 wc_get_template( 'loop/pagination.php' );
				remove_filter( 'woocommerce_pagination_args', 'WC_Woopf::woopf_pagination_filter' );
				
				$pagination = ob_get_clean();
				$out .= $pagination;
			endif;

			$out .= '</div>';
			die($out);
			exit;
		}
		
		/**
		 * Check product attribute for sidebar
		 * @since    1.0.0
		 */
		private $woopf_filter_attrs;
		
		function woopf_check_attribute($product_id){
			$attrs=get_post_meta($product_id,'_product_attributes', true);
			
			if(count($attrs) > 0):
			 foreach($attrs as $key => $val):
				$this->woopf_filter_attrs[$key]=true;
			 endforeach;
			endif;
		}

		/**
		 * [woopf_filter]
		 * @since    1.0.0
		 */
		function woopf_sc_get_filter( $atts, $content = null ) {
			return include_once( WC_Woopf::$dir . 'woopf-orderby.php' );
		}
	}
	add_action( 'init', array( 'WC_Woopf_Shortcodes', 'init' ), 999 );
?>
