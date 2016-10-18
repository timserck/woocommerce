<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://theemon.com/p/Woo-Products-filter/LivePreview
 * @since      1.0.0
 *
 * @package    woo-products-filter
 * @subpackage woo-products-filter/public
 */

	class WC_Woopf {
		
		public static $dir;
		public static $path;
		public static $url_path;
		public static $settings;
		public static function init() {
			$class = __CLASS__;
			new $class;
		}
		
		/**
		 * Define the core functionality of the plugin.
		 * @since    1.0.0
		 */
		function __construct() {

			self::$dir = trailingslashit( dirname( __FILE__ ) );
			self::$path = trailingslashit( plugin_dir_path( __FILE__ ) );
			self::$url_path = plugins_url('', __FILE__);
			
			self::$settings['permalink_structure'] = get_option( 'permalink_structure' );
			self::$settings['wc_settings_woopf_disable_scripts'] = get_option( 'wc_settings_woopf_disable_scripts', array() );
			self::$settings['wc_settings_woopf_ajax_js'] = get_option( 'wc_settings_woopf_ajax_js', '' );
			self::$settings['wc_settings_woopf_custom_tax'] = get_option( 'wc_settings_woopf_custom_tax', 'no' );
			self::$settings['wc_settings_woopf_enable'] = get_option( 'wc_settings_woopf_enable', 'yes' );
			self::$settings['wc_settings_woopf_default_templates'] = get_option( 'wc_settings_woopf_default_templates', 'no' );
			self::$settings['wc_settings_woopf_force_categories'] = get_option( 'wc_settings_woopf_force_categories', 'no' );
			self::$settings['wc_settings_woopf_instock'] = get_option( 'wc_settings_woopf_instock', 'no' );
			self::$settings['wc_settings_woopf_use_ajax'] = get_option( 'wc_settings_woopf_use_ajax', 'no' );
			self::$settings['wc_settings_woopf_ajax_class'] = get_option( 'wc_settings_woopf_ajax_class', '' );
			self::$settings['wc_settings_woopf_ajax_product_class'] = get_option( 'wc_settings_woopf_ajax_product_class', '' );
			self::$settings['wc_settings_woopf_ajax_columns'] = get_option( 'wc_settings_woopf_ajax_columns', '4' );
			self::$settings['wc_settings_woopf_ajax_rows'] = get_option( 'wc_settings_woopf_ajax_rows', '4' );
			self::$settings['wc_settings_woopf_force_redirects'] = get_option( 'wc_settings_woopf_force_redirects', 'no' );
			self::$settings['wc_settings_woopf_force_emptyshop'] = get_option( 'wc_settings_woopf_force_emptyshop', 'no' );
			self::$settings['wc_settings']['product_taxonomies'] = get_object_taxonomies( 'product' );

			if ( self::$settings['wc_settings_woopf_enable'] == 'yes') :
				add_filter( 'woocommerce_locate_template', array( &$this, 'woopf_add_loop_filter' ), 10, 3 );
				add_filter( 'wc_get_template_part', array( &$this, 'woopf_add_filter' ), 10, 3 );
			endif;

			if ( self::$settings['wc_settings_woopf_enable'] == 'no' && self::$settings['wc_settings_woopf_default_templates'] == 'yes' ) :
				add_filter( 'woocommerce_locate_template', array( &$this, 'woopf_add_loop_filter_blank' ), 10, 3 );
				add_filter( 'wc_get_template_part', array( &$this, 'woopf_add_filter_blank' ), 10, 3 );
			endif;

			if ( !is_admin() && self::$settings['wc_settings_woopf_force_categories'] == 'no' ) :
				if ( self::$settings['wc_settings_woopf_force_redirects'] !== 'yes' && self::$settings['permalink_structure'] !== '' ) :
					add_action( 'template_redirect', array( &$this, 'woopf_redirect' ), 999 );
				endif;
				if ( self::$settings['wc_settings_woopf_force_emptyshop'] !== 'yes' ) :
					add_action( 'template_redirect',array( &$this, 'woopf_redirect_empty_shop' ), 998 );
				endif;
			endif;

			if ( self::$settings['wc_settings_woopf_use_ajax'] == 'yes' ) :
				add_filter( 'woocommerce_pagination_args', array( &$this, 'woopf_pagination_filter' ), 999, 1 );
			endif;

			add_action( 'wp_enqueue_scripts', array( &$this, 'woopf_scripts' ) );
			add_filter( 'pre_get_posts', array( &$this, 'woopf_wc_query' ), 999999, 1 );
			add_action( 'woopf_output', array( &$this, 'woopf_get_filter' ), 10 );
					
		}

		
		/**
		 * Product Load Scripts
		 * @since    1.0.0
		 */
		function woopf_scripts() {

			$curr_scripts = self::$settings['wc_settings_woopf_disable_scripts'];

			wp_register_style( 'woopf-public-css', self::$url_path .'/css/woopf-public.css', false, '1.0.0' );
			wp_enqueue_style( 'woopf-public-css' );
	
			if ( !in_array( 'mcustomscroll', $curr_scripts ) ) :
				wp_register_script( 'woopf-scrollbar-js', self::$url_path .'/js/woopf-jquery.mCustomScrollbar.concat.min.js', array( 'jquery' ), '4.0.4', true );
			wp_enqueue_script( 'woopf-scrollbar-js' );
			endif;

		
		

			wp_register_script( 'woopf-public-js', self::$url_path .'/js/woopf_public.js', array( 'jquery', 'hoverIntent' ), '1.0.0', true );
			
			wp_enqueue_script( 'woopf-public-js' );
			
			$curr_args = array(
				'ajax' => admin_url( 'admin-ajax.php' ),
				'url' => self::$url_path,
				'js' => self::$settings['wc_settings_woopf_ajax_js'],
				'use_ajax' => self::$settings['wc_settings_woopf_use_ajax'],
				'ajax_class' => self::$settings['wc_settings_woopf_ajax_class'],
				'ajax_product_class' => self::$settings['wc_settings_woopf_ajax_product_class'],
					'scaleHeight' => '250',
				    'localization' => array(
					'close_filter' => esc_html__( 'Close filter', 'woopf' ),
					'filter_terms' => esc_html__( 'Filter terms', 'woopf' )
				)
			);

			wp_localize_script( 'woopf-public-js', 'woopf', $curr_args );
		}

		/**
		 * Product Filter Pre_Get_Posts.
		 * @since    1.0.0
		 */
		function woopf_wc_query( $query ) {
			global $prdctfltr_global;
			$is_main_query = 'is_main_query';
			$is_tax = 'is_tax';
			$is_post_type_archive = 'is_post_type_archive';
			
			$product_taxonomies = get_object_taxonomies( 'product' );
			if ( is_admin() && ( defined('DOING_AJAX') && DOING_AJAX ) === false ) { 
				return; 
			}
			else if ( !is_admin() && ( isset( $query->query['prdctfltr'] ) && $query->query['prdctfltr'] == 'active' ) !== false ) {
				$pf_mode = 'shortcode';
			}
			else if ( !is_admin() && $query->{$is_main_query}() && ( $query->{$is_tax}( $product_taxonomies ) || !is_admin() && $query->{$is_main_query}() && $query->{$is_post_type_archive}( 'product' ) || !is_admin() && $query->{$is_main_query}() && ( isset( $query->query_vars['page_id'] ) && $query->query_vars['page_id'] == ( self::woopf_wpml_get_id( wc_get_page_id( 'shop' ) ) ) ) ) ) {
				$pf_mode = 'archive';
			}
			else if ( ( ( isset( $query->query['wc_query'] ) && $query->query['wc_query'] == 'product_query' ) !== false || ( isset( $query->query['post_type'] ) && $query->query['post_type'] == 'product' ) !== false ) && isset( $prdctfltr_global['ajax'] ) && ( defined('DOING_AJAX') && DOING_AJAX ) ) {
				$pf_mode = 'shortcode_ajax';
			}
			else { 
				return; 
			}

			$curr_args = array();
			$pf_next = array();
			$f_attrs = array();
			$f_terms = array();
			$rng_terms = array();

			$pf_not_allowed = array( 'product_cat', 'product_tag', 'characteristics', 'product_type' );

			foreach ( $product_taxonomies as $pf_tax ) :
				if ( !in_array( $pf_tax, $pf_not_allowed ) ) :
					$pf_next[] = $pf_tax;
				endif;
			endforeach;

			$pf_allowed = array( 'products_per_page', 'instock_products', 'orderby' );

			if ( isset( $prdctfltr_global['ajax'] ) ) {
				foreach( $query->query as $k => $v ){
					if ( substr($k, 0, 4) == 'rng_' && $v !== '' ) {
						if ( substr($k, 0, 8) == 'rng_min_' ) {
							$rng_terms[str_replace('rng_min_', '', $k)]['min'] = $v;
						}
						else if ( substr($k, 0, 8) == 'rng_max_' ) {
							$rng_terms[str_replace('rng_max_', '', $k)]['max'] = $v;
						}
						else if ( substr($k, 0, 10) == 'rng_order_' ) {
							$rng_terms[str_replace('rng_order_', '', $k)]['order'] = $v;
						}
						$_GET[$k] = $v;
					}
					else if ( in_array($k, $pf_next) && substr($k, 0, 3) == 'pa_' ) {
						$_GET[$k] = $v;
					}
					else if ( in_array( $k, $product_taxonomies ) ) {
						$_GET[$k] = $v;
					}
					else if ( in_array( $k, $pf_allowed ) ) {
						$_GET[$k] = $v;
					}
				}
			}

			$pf_not_allowed = array( 'product_cat', 'product_tag', 'characteristics', 'min_price', 'max_price', 'sale_products', 'instock_products', 'products_per_page', 'widget_search', 'page_id', 'lang' );

			foreach( $_GET as $k => $v ){
				if ( !in_array($k, $pf_not_allowed) ) {
					if ( substr($k, 0, 4) == 'rng_' && $v !== '' ) {
						$curr_val = str_replace('rng_max_', '', $k);
						if ( substr($k, 0, 8) == 'rng_min_' ) {
							$rng_terms[str_replace('rng_min_', '', $k)]['min'] = $v;
						}
						else if ( substr($k, 0, 8) == 'rng_max_' ) {
							$rng_terms[str_replace('rng_max_', '', $k)]['max'] = $v;
						}
						else if ( substr($k, 0, 12) == 'rng_orderby_' ) {
							$rng_terms[str_replace('rng_orderby_', '', $k)]['orderby'] = $v;
						}
						else if ( substr($k, 0, 10) == 'rng_order_' ) {
							$rng_terms[str_replace('rng_order_', '', $k)]['order'] = $v;
						}
					}
					else if ( in_array($k, $pf_next) && substr($k, 0, 3) == 'pa_' ) {
						$curr_args = array_merge( $curr_args, array( $k => $v ) );
						$f_attrs[] = '"attribute_'.$k.'"';
						if ( strpos($v, ',') ) {
							$v_val = explode(',', $v);
							foreach ( $v_val as $o => $z ) :
								$f_terms[] = '"'.self::woopf_utf8_decode($z).'"';
							endforeach;
						}
						else if ( strpos($v, '+') ) {
							$v_val = explode('+', $v);
							foreach ( $v_val as $o => $z ) :
								$f_terms[] = '"'.self::woopf_utf8_decode($z).'"';
							endforeach;
						}
						else {
							$f_terms[] = '"'.self::woopf_utf8_decode($v).'"';
						}
					}
					else if ( in_array( $k, $product_taxonomies ) ) {
						$curr_args = array_merge( $curr_args, array( $k => $v ) );
					}
				}
			}

			if ( !empty($rng_terms) ) {
				foreach ( $rng_terms as $rng_name => $rng_inside ) {
					if ( ( isset($rng_inside['min']) && isset($rng_inside['max']) ) === false ) { continue; }
					if ( !in_array( $rng_name, array( 'price' ) ) ) {
						if ( isset($rng_terms[$rng_name]['orderby']) && $rng_terms[$rng_name]['orderby'] == 'number' ) {
							$attr_args = array(
								'hide_empty' => 1,
								'orderby' => 'slug'
							);
							$sort_args = array(
								'order' => ( isset( $rng_terms[$rng_name]['order'] ) ? $rng_terms[$rng_name]['order'] : 'ASC' )
							);
							$curr_attributes = self::woopf_get_terms( $rng_name, $attr_args );
							$curr_attributes = self::woopf_sort_terms_naturally( $curr_attributes, $sort_args );
						}
						else if ( isset($rng_terms[$rng_name]['orderby']) && $rng_terms[$rng_name]['orderby'] !== '' ) {
							$attr_args = array(
								'hide_empty' => 1,
								'orderby' => $rng_terms[$rng_name]['orderby'],
								'order' => ( isset( $rng_terms[$rng_name]['order'] ) ? $rng_terms[$rng_name]['order'] : 'ASC' )
							);
							$curr_attributes = self::woopf_get_terms( $rng_name, $attr_args );
						}
						else {
							$attr_args = array( 'hide_empty' => 1 );
							$curr_attributes = self::woopf_get_terms( $rng_name, $attr_args );
						}

						if ( empty( $curr_attributes ) ) { 
							continue; 
						}

						$rng_found = false;
						$curr_ranges = array();
						foreach ( $curr_attributes as $c => $s ) :
							if ( $rng_found == true ) :
								$curr_ranges[] = $s->slug;
								if ( $s->slug == $rng_inside['max'] ) :
									$rng_found = false;
									continue;
								endif;
							endif;
							if ( $s->slug == $rng_inside['min'] && $rng_found === false ) :
								$rng_found = true;
								$curr_ranges[] = $s->slug;
							endif;
						endforeach;
						$curr_args = array_merge( $curr_args, array(
								$rng_name => implode( $curr_ranges, ',' )
							) );

						$f_attrs[] = '"attribute_' . $rng_name . '"';
						$f_terms_rng = array();
						foreach ( $curr_ranges as $c ) :
							$f_terms_rng[] = '"' . $c . '"';
						endforeach;
						$f_terms[] = implode( $f_terms_rng, ',' );
					}

				}
			}

			if ( !isset($_GET['orderby']) && isset($query->query['orderby']) && $query->query['orderby'] !== '' ) :
				$_GET['orderby'] = $query->query['orderby'];
			endif;

			if ( isset($_GET['orderby']) && $_GET['orderby'] !== '' ) {
				if ( $_GET['orderby'] == 'price' || $_GET['orderby'] == 'price-desc' ) {
					$orderby = 'meta_value_num';
					$order = ( $_GET['orderby'] == 'price-desc' ? 'DESC' : 'ASC' );
					$curr_args = array_merge( $curr_args, array(
							'meta_key' => '_price',
							'orderby' => $orderby,
							'order' => $order
						) );
				}
				else if ( $_GET['orderby'] == 'rating' ) {
					add_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
				}
				else if ( $_GET['orderby'] == 'popularity' ) {
					$orderby = 'meta_value_num';
					$order = 'DESC';
					$curr_args = array_merge( $curr_args, array(
							'meta_key' => 'total_sales',
							'orderby' => $orderby,
							'order' => $order
						) );
				}
				else {
					$orderby = $_GET['orderby'];
					$order = ( isset($_GET['order']) ? $_GET['order'] : in_array( $orderby, array( 'date', 'comment_count' ) ) ? 'DESC' : 'ASC' );
					$curr_args = array_merge( $curr_args, array(
							'orderby' => $orderby,
							'order' => $order
						) );
				}
			}

			if ( isset($_GET['product_cat']) && $_GET['product_cat'] !== '' ) {
				$curr_args = array_merge( $curr_args, array(
							'product_cat' => $_GET['product_cat']
					) );
			}
			else if ( get_query_var('product_cat') !== '' ) {
				$curr_args = array_merge( $curr_args, array(
							'product_cat' => get_query_var('product_cat')
					) );
			}
			else if ( isset($query->query['product_cat']) ) {
				$curr_args = array_merge( $curr_args, array(
							'product_cat' => $query->query['product_cat']
					) );
			}

			if ( isset($_GET['product_tag']) && $_GET['product_tag'] !== '' ) {
				$curr_args = array_merge( $curr_args, array(
							'product_tag' => $_GET['product_tag']
					) );
			}
			else if ( get_query_var('product_tag') !== '' ) {
				$curr_args = array_merge( $curr_args, array(
							'product_tag' => get_query_var('product_tag')
					) );
			}
			else if ( isset($query->query['product_tag']) ) {
				$curr_args = array_merge( $curr_args, array(
							'product_tag' => $query->query['product_tag']
					) );
			}

			if ( isset($_GET['characteristics']) && $_GET['characteristics'] !== '' ) {
				$curr_args = array_merge( $curr_args, array(
							'characteristics' => $_GET['characteristics']
					) );
			}
			else if ( get_query_var('characteristics') !== '' ) {
				$curr_args = array_merge( $curr_args, array(
							'characteristics' => get_query_var('characteristics')
					) );
			}
			else if ( isset($query->query['product_characteristics']) ) {
				$curr_args = array_merge( $curr_args, array(
							'characteristics' => $query->query['product_characteristics']
					) );
			}

			if ( !isset($_GET['min_price']) && !isset($_GET['rng_min_price']) && isset($query->query['min_price']) && $query->query['min_price'] !== '' ) {
				$_GET['min_price'] = $query->query['min_price'];
			}
			if ( !isset($_GET['max_price']) && !isset($_GET['rng_max_price']) && isset($query->query['max_price']) && $query->query['max_price'] !== '' ) {
				$_GET['max_price'] = $query->query['max_price'];
			}

			if ( ( isset($_GET['min_price']) || isset($_GET['max_price']) ) !== false || ( isset($_GET['rng_min_price']) && isset($_GET['rng_max_price']) ) !== false || ( isset($_GET['sale_products']) || isset($query->query['sale_products']) ) !== false ) {
				add_filter( 'posts_join' , array( &$this, 'woopf_join_price' ) );
				add_filter( 'posts_where' , array( &$this, 'woopf_price_filter' ), 998, 2 );
			}

			if ( !isset($_GET['instock_products']) && isset($query->query['instock_products']) && $query->query['instock_products'] !== '' ) {
				$_GET['instock_products'] = $query->query['instock_products'];
			}

			$curr_instock = self::$settings['wc_settings_woopf_instock'];

			if ( ( ( ( isset($_GET['instock_products']) && $_GET['instock_products'] !== '' && ( $_GET['instock_products'] == 'in' || $_GET['instock_products'] == 'out' ) ) || $curr_instock == 'yes' ) !== false ) && ( !isset($_GET['instock_products']) || $_GET['instock_products'] !== 'both' ) ) {
				
				if ( !isset($_GET['instock_products']) && $curr_instock == 'yes' ) {
					$i_arr['f_results'] = 'outofstock';
					$i_arr['s_results'] = 'instock';
				}
				else if ( $_GET['instock_products'] == 'in' ) {
					$i_arr['f_results'] = 'outofstock';
					$i_arr['s_results'] = 'instock';
				}
				else if ( $_GET['instock_products'] == 'out' ) {
					$i_arr['f_results'] = 'instock';
					$i_arr['s_results'] = 'outofstock';
				}

					if ( count($f_terms) == 0 ) {
						foreach($query->query as $k => $v){
							if (substr($k, 0, 3) == 'pa_') {
								$f_attrs[] = '"attribute_'.$k.'"';

								if ( strpos($v, ',') ) {
									$v_val = explode(',', $v);
									foreach ( $v_val as $o => $z ) :
										$f_terms[] = '"'.self::woopf_utf8_decode($z).'"';
									endforeach;
								}
								else if ( strpos($v, '+') ) {
									$v_val = explode('+', $v);
									foreach ( $v_val as $o => $z ) :
										$f_terms[] = '"'.self::woopf_utf8_decode($z).'"';
									endforeach;
								}
								else {
									$f_terms[] = '"'.self::woopf_utf8_decode($v).'"';
								}

							}
						}
					}

					$curr_atts = join(',', $f_attrs);
					$curr_terms = join(',', $f_terms);
					$curr_count = count($f_attrs)+1;

					if ( $curr_count > 1 ) {
						global $wpdb;
						$get_results = 'get_results';
						$prepare = 'prepare';

						$pf_exclude_product = $wpdb->{$get_results}( $wpdb->{$prepare}( '
							SELECT DISTINCT(post_parent) FROM %1$s
							INNER JOIN %2$s ON (%1$s.ID = %2$s.post_id)
							WHERE %1$s.post_parent != "0"
							AND %2$s.meta_key IN ("_stock_status",'.$curr_atts.')
							AND %2$s.meta_value IN ("'.$i_arr['f_results'].'",'.$curr_terms.',"")
							GROUP BY %2$s.post_id
							HAVING COUNT(DISTINCT %2$s.meta_value) = ' . $curr_count .'
							ORDER BY %1$s.ID ASC
						', $wpdb->posts, $wpdb->postmeta ) );

						$curr_in = array();
						foreach ( $pf_exclude_product as $p ) :
							$curr_in[] = $p->post_parent;
						endforeach;

						$pf_exclude_product_out = $wpdb->{$get_results}( $wpdb->{$prepare}( '
							SELECT DISTINCT(post_parent) FROM %1$s
							INNER JOIN %2$s ON (%1$s.ID = %2$s.post_id)
							WHERE %1$s.post_parent != "0"
							AND %2$s.meta_key IN ("_stock_status",'.$curr_atts.')
							AND %2$s.meta_value IN ("'.$i_arr['s_results'].'",'.$curr_terms.',"")
							GROUP BY %2$s.post_id
							HAVING COUNT(DISTINCT %2$s.meta_value) = ' . $curr_count .'
							ORDER BY %1$s.ID ASC
						', $wpdb->posts, $wpdb->postmeta ) );

						$curr_in_out = array();
						foreach ( $pf_exclude_product_out as $p ) :
							$curr_in_out[] = $p->post_parent;
						endforeach;

						if ( $curr_instock == 'yes' || $_GET['instock_products'] == 'in' ) {

							foreach ( $curr_in as $q => $w ) :
								if ( in_array( $w, $curr_in_out) ) :
									unset($curr_in[$q]);
								endif;
							endforeach;
							$curr_args = array_merge( $curr_args, array( 'post__not_in' => $curr_in ) );

							add_filter( 'posts_join' , array( &$this, 'woopf_join_instock' ) );
							add_filter( 'posts_where' , array( &$this, 'woopf_instock_filter' ), 999, 2 );

						}
						else if ( $_GET['instock_products'] == 'out' ) {
							foreach ( $curr_in_out as $e => $r ) :
								if ( in_array( $r, $curr_in) ) :
									unset($curr_in_out[$e]);
								endif;
							endforeach;

							$pf_exclude_product_addon = $wpdb->{$get_results}( $wpdb->{$prepare}( '
								SELECT DISTINCT(ID) FROM %1$s
								INNER JOIN %2$s ON (%1$s.ID = %2$s.post_id)
								WHERE %1$s.post_parent = "0"
								AND %2$s.meta_key IN ("_stock_status",'.$curr_atts.')
								AND %2$s.meta_value IN ("outofstock",'.$curr_terms.')
								GROUP BY %2$s.post_id
								ORDER BY %1$s.ID ASC
							', $wpdb->posts, $wpdb->postmeta ) );

							$curr_in_out_addon = array();
							foreach ( $pf_exclude_product_addon as $a ) :
								$curr_in_out_addon[] = $a->ID;
							endforeach;
							$curr_in_out = $curr_in_out + $curr_in_out_addon;
							$curr_args = array_merge( $curr_args, array( 'post__in' => $curr_in_out ) );
						}
					}
					else {
						if ( !isset($_GET['instock_products']) && $curr_instock == 'yes' ) {
							add_filter( 'posts_join' , array( &$this, 'woopf_join_instock' ) );
							add_filter( 'posts_where' , array( &$this, 'woopf_instock_filter' ), 999, 2 );
						}
						else if ( isset($_GET['instock_products']) && $_GET['instock_products'] == 'in' ) {
							add_filter( 'posts_join' , array( &$this, 'woopf_join_instock' ) );
							add_filter( 'posts_where' , array( &$this, 'woopf_instock_filter' ), 999, 2 );
						}
						else if ( isset($_GET['instock_products']) && $_GET['instock_products'] == 'out' ) {
							add_filter( 'posts_join' , array( &$this, 'woopf_join_instock' ) );
							add_filter( 'posts_where' , array( &$this, 'woopf_outofstock_filter' ), 999, 2 );
						}
					}
			}

			if ( isset($_GET['products_per_page']) && $_GET['products_per_page'] !== '' ) :
				$curr_args = array_merge( $curr_args, array(
					'posts_per_page' => floatval( $_GET['products_per_page'] )
				) );
			endif;

			if ( isset($query->query_vars['http_query']) ) :
				parse_str(html_entity_decode($query->query['http_query']), $curr_http_args);
				$curr_args = array_merge( $curr_args, $curr_http_args );
			endif;

			$pf_tax_query = array ();

			foreach ( $curr_args as $k => $v ) :
				$set = 'set';
				if ( in_array($k, $product_taxonomies ) ) :
				   if ( strpos($v, ',') ) {
						$pf_tax_query[] = array( 'taxonomy' => $k, 'field' => 'slug', 'terms' => explode(',', $v), 'operator' => 'IN' );
					}
					else if ( strpos($v, '+') ) {
						if ( $k == 'product_cat' ) : 
						    $operator = 'IN'; 
						else : 
						    $operator = 'AND'; 
						endif;
						$pf_tax_query[] = array( 'taxonomy' => $k, 'field' => 'slug', 'terms' => explode('+', $v), 'operator' => $operator );
					}
					else {
						$pf_tax_query[] = array( 'taxonomy' => $k, 'field' => 'slug', 'terms' => array( $v ) );
					}
					$query->{$set}( $k, $v );
				else :
					$query->{$set}( $k, $v );
				endif;
			endforeach;

			if ( !empty($pf_tax_query) ) :
				$set = 'set';
				$pf_tax_query['relation'] = 'AND';
				$query->{$set}( 'tax_query', $pf_tax_query );
			endif;
		}

		/**
		 * Product Filter Join Sale Tables
		 * @since    1.0.0
		 */
		function woopf_join_price($join){
			global $wpdb;
			$join .= " JOIN $wpdb->postmeta AS pf_price ON $wpdb->posts.ID = pf_price.post_id JOIN $wpdb->postmeta AS pf_price_max ON $wpdb->posts.ID = pf_price_max.post_id ";
			return $join;
		}

		/**
		 * Product Filter Join Instock Tables
		 * @since    1.0.0
		 */
		function woopf_join_instock($join){
			global $wpdb;
			$join .= " JOIN $wpdb->postmeta AS pf_instock ON $wpdb->posts.ID = pf_instock.post_id ";
			return $join;
		}

		/**
		 * Product Filter Sale Filter
		 * @since    1.0.0
		 */
		function woopf_price_filter ( $where, &$wp_query ) {
			global $wpdb;
			if ( ( isset( $_GET['sale_products'] ) && $_GET['sale_products'] == 'on' ) !== false || ( isset( $wp_query->query_vars['sale_products'] ) && $wp_query->query_vars['sale_products'] ) !== false ) :
				$pf_sale = true;
				$pf_where_keys = array(
					'"_sale_price","_min_variation_sale_price"',
					'"_sale_price","_max_variation_sale_price"',
					'meta_keys' => array( '_sale_price', '_min_variation_sale_price', '_max_variation_sale_price' )
				);
			else :
				$pf_sale = false;
				$pf_where_keys = array(
					'"_price","_min_variation_price","_sale_price","_min_variation_sale_price"',
					'"_price","_max_variation_price","_sale_price","_max_variation_sale_price"',
					'meta_keys' => array( '_price', '_min_variation_price', '_max_variation_price' )
				);
			endif;

	if ( isset( $wp_query->query_vars['rng_min_price'] ) ) :
		$_min_price = $wp_query->query_vars['rng_min_price']; 
	endif;
	
	if ( isset( $wp_query->query_vars['min_price'] ) ) :
		$_min_price =  $wp_query->query_vars['min_price']; 
	endif;
	
	if ( isset( $_GET['rng_min_price'] ) ) :
		$_min_price = $_GET['rng_min_price']; 
	endif;
	
	if ( isset( $_GET['min_price'] ) ) : 
		$_min_price =  $_GET['min_price']; 
	endif;
	
			if ( !isset( $_min_price ) ) :
				$get_var = 'get_var';
				$prepare = 'prepare';
				$_min = floor( $wpdb->{$get_var}(
					$wpdb->{$prepare}('
						SELECT min(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
						WHERE ( meta_key = \'%3$s\' OR meta_key = \'%4$s\' )
						AND meta_value != ""
						', $wpdb->posts, $wpdb->postmeta, $pf_where_keys['meta_keys'][0], $pf_where_keys['meta_keys'][1] )
					)
				);
			endif;

		if ( isset( $wp_query->query_vars['rng_max_price'] ) ) { $_max_price = $wp_query->query_vars['rng_max_price']; }
		if ( isset( $wp_query->query_vars['max_price'] ) ) { $_max_price =  $wp_query->query_vars['max_price']; }
		if ( isset( $_GET['rng_max_price'] ) ) { $_max_price = $_GET['rng_max_price']; }
		if ( isset( $_GET['max_price'] ) ) { $_max_price =  $_GET['max_price'];	}

			if ( !isset( $_max_price ) ) :
				$get_var = 'get_var';
				$prepare = 'prepare';
				$_max = ceil( $wpdb->{$get_var}(
					$wpdb->{$prepare}('
						SELECT max(meta_value + 0)
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
						WHERE ( meta_key = \'%3$s\' OR meta_key = \'%4$s\' )
						AND meta_value != ""
						', $wpdb->posts, $wpdb->postmeta, $pf_where_keys['meta_keys'][0], $pf_where_keys['meta_keys'][2] )
				) );
			endif;

			if ( ( isset($_min_price) || isset($_max_price) ) !== false ) {
				
				if ( !isset( $_min_price) ) : $_min_price = $_min; endif;
				if ( !isset( $_max_price) ) : $_max_price = $_max; endif;
				$where .= " AND ( pf_price.meta_key IN ($pf_where_keys[0]) AND pf_price.meta_value >= $_min_price AND pf_price.meta_value != \"\" ) AND ( pf_price_max.meta_key IN ($pf_where_keys[1]) AND pf_price_max.meta_value <= $_max_price AND pf_price_max.meta_value != \"\" ) ";
			}
			else if ( $pf_sale === true ) {
				$where .= " AND ( pf_price.meta_key IN (\"_sale_price\",\"_min_variation_sale_price\") AND pf_price.meta_value > 0 ) ";
			}
			remove_filter( 'posts_where' , 'woopf_price_filter' );
			return $where;
		}

		/**
		 * Product Filter Instock Filter
		 * @since    1.0.0
		 */
		function woopf_instock_filter ( $where, &$wp_query ) {
			global $wpdb;
			$where = str_replace("AND ( ($wpdb->postmeta.meta_key = '_visibility' AND CAST($wpdb->postmeta.meta_value AS CHAR) IN ('visible','catalog')) )", "", $where);
			$where .= " AND ( pf_instock.meta_key LIKE '_stock_status' AND pf_instock.meta_value = 'instock' ) ";
			remove_filter( 'posts_where' , 'woopf_instock_filter' );
			return $where;
		}

		/**
		 * Product Filter Outofstock Filter
		 * @since    1.0.0
		 */
		function woopf_outofstock_filter ( $where, &$wp_query ) {
			global $wpdb;
			$where = str_replace("AND ( ($wpdb->postmeta.meta_key = '_visibility' AND CAST($wpdb->postmeta.meta_value AS CHAR) IN ('visible','catalog')) )", "", $where);
			$where .= " AND ( pf_instock.meta_key LIKE '_stock_status' AND pf_instock.meta_value = 'outofstock' ) ";
			remove_filter( 'posts_where' , 'woopf_outofstock_filter' );
			return $where;
		}

		/**
		 * Product Filter Override WooCommerce Template
		 * @since    1.0.0
		 */
		function woopf_add_filter ( $template, $slug, $name ) {
			$template_path = 'template_path';
			if ( $name ) :
				$path = self::$path . WC()->{$template_path}() . "{$slug}-{$name}.php";
			else :
				$path = self::$path . WC()->{$template_path}() . "{$slug}.php";
			endif;
			return file_exists( $path ) ? $path : $template;
		}
		
		/**
		 * Product Filter Override WooCommerce Template
		 * @since    1.0.0
		 */
		function woopf_add_loop_filter ( $template, $template_name, $template_path ) {
			$path = self::$path . $template_path . $template_name;
			return file_exists( $path ) ? $path : $template;
		}
		
		/**
		 * Product Filter Override WooCommerce Template - Blank
		 * @since    1.0.0
		 */
		function woopf_add_filter_blank ( $template, $slug, $name ) {
			if ( $name ) :
				$template_path = 'template_path';
				$path = self::$path . 'blank/' . WC()->{$template_path}() . "{$slug}-{$name}.php";
			else :
				$path = self::$path . 'blank/' . WC()->{$template_path}() . "{$slug}.php";
			endif;
			return file_exists( $path ) ? $path : $template;
		}

		/**
		 * Product Filter Override WooCommerce Template - Blank
		 * @since    1.0.0
		 */
		function woopf_add_loop_filter_blank ( $template, $template_name, $template_path ) {
			self::$path . 'blank/' . $template_path . $template_name;
			return file_exists( $path ) ? $path : $template;
		}

		/**
		 * Product Filter Redirects
		 * @since    1.0.0
		 */
		function woopf_redirect() {
			if ( is_post_type_archive( 'product' ) || is_tax( self::$settings['wc_settings']['product_taxonomies'] ) ) :
				if ( isset( $_REQUEST['product_cat'] ) ) :
					if ( strpos( $_REQUEST['product_cat'], ',' ) || strpos( $_REQUEST['product_cat'], '+' ) ) :
						global $wp_rewrite;
						$get_extra_permastruct = 'get_extra_permastruct';
						$redirect = $wp_rewrite->{$get_extra_permastruct}('product_cat');
						$redirect = get_bloginfo('url') . '/' . str_replace( '%product_cat%', $_REQUEST['product_cat'], $redirect);
					else :
						$redirect = get_term_link( $_REQUEST['product_cat'], 'product_cat' );
					endif;

					if ( substr( $redirect, -1 ) != '/' ) { $redirect .= '/'; }
					unset( $_REQUEST['product_cat'] );
					if ( !empty( $_REQUEST ) ) :
						$req = '';
						foreach( $_REQUEST as $k => $v ) :
							if ( strpos($v, ',') ) { $new_v = str_replace(',', '%2C', $v); }
							else if ( strpos($v, '+') ) { $new_v = str_replace('+', '%2C', $v); }
							else { $new_v = $v; }

							$req .= $k . '=' . $new_v . '&';
						endforeach;
					 $redirect = $redirect . '?' . $req;
					endif;
					header("Location: $redirect", true, 302);
					exit;
				endif;
			endif;
		}

		/**
		 * Product Filter Redirects Empty Shop
		 * @since    1.0.0
		 */
		function woopf_redirect_empty_shop() {
			$curr_display_disable = get_option( 'wc_settings_woopf_disable_display', array( 'subcategories' ) );
			if ( !empty( $_REQUEST ) && ( !empty( $_GET ) && !isset($_GET['lang']) ) && is_shop() && !is_product_category() && in_array( get_option( 'woocommerce_shop_page_display' ), $curr_display_disable ) ) {
				$_REQUEST = array();
				$redirect = get_permalink( self::woopf_wpml_get_id( wc_get_page_id( 'shop' ) ) );
				if ( substr( $redirect, -1 ) != '/' ) :
					$redirect .= '/'; 
				endif;
				remove_action( 'template_redirect', 'woopf_redirect', 999 );
				header("Location: $redirect", true, 302);
				exit;
			}
			else if ( !empty( $_REQUEST ) && ( !empty( $_GET ) && !isset($_GET['lang']) ) && is_post_type_archive( 'product' ) || is_tax( self::$settings['wc_settings']['product_taxonomies'] ) ) {
				if ( isset( $_REQUEST['product_cat'] ) ) :
					if ( count($_REQUEST) == 1 ) return;
					$term = term_exists( $_REQUEST['product_cat'], 'product_cat' );
					if ($term !== 0 && $term !== null) :
						$display_type = get_woocommerce_term_meta( $term['term_id'], 'display_type', true );
						$display_type = ( $display_type == '' ? get_option( 'woocommerce_category_archive_display' ) : $display_type );
						if ( in_array( $display_type, $curr_display_disable ) ) :
							$redirect = get_term_link( $_REQUEST['product_cat'], 'product_cat' );
							$_REQUEST = array( 'product_cat', $_REQUEST['product_cat'] );
							remove_action( 'template_redirect', 'woopf_redirect', 999 );
							header("Location: $redirect", true, 302);
							exit;
						endif;
					endif;
				endif;
			}
		}

		/**
		 * Product Filter Search Variable Products
		 * @since    1.0.0
		 */
		public static function woopf_search_array( $array, $attrs ) {
			$results = array();
			$found = 0;
			foreach ( $array as $subarray ) :
				if ( isset( $subarray['attributes'] ) ) :
					foreach ( $attrs as $k => $v ) :
						if ( in_array($v, $subarray['attributes'] ) ) :
							$found++;
						endif;
					endforeach;
				endif;
				if ( count($attrs) == $found ) { 
					$results[] = $subarray; 
				}
				else if ( $found > 0 ) { 
					$results[] = $subarray; 
				}
				$found = 0;
			endforeach;
		  return $results;
		}

		/**
		 * Product Filter Sort Hierarchicaly
		 * @since    1.0.0
		 */
		public static function woopf_sort_terms_hierarchicaly( Array &$cats, Array &$into, $parentId = 0 ) {
			foreach ($cats as $i => $cat) :
				if ($cat->parent == $parentId) :
					$into[$cat->term_id] = $cat;
					unset($cats[$i]);
				endif;
			endforeach;
			foreach ($into as $topCat) :
				$topCat->children = array();
				self::woopf_sort_terms_hierarchicaly($cats, $topCat->children, $topCat->term_id);
			endforeach;
		}

		/**
		 * Product Filter Sort by Number
		 * @since    1.0.0
		 */
		public static function woopf_sort_terms_naturally( $terms, $args ) {
			$sort_terms = array();
			foreach($terms as $term) :
				$sort_terms[$term->name] = $term; 
			endforeach;
			uksort( $sort_terms, 'strnatcmp');
			if ( strtolower( $args['order'] ) == 'DESC' ) :
				$sort_terms = array_reverse( $sort_terms );
			endif;
		  return $sort_terms;
		}

		/**
		 * Product Filter Action
		 * @since    1.0.0
		 */
		public static function woopf_get_filter() {
			include_once( self::$dir . 'woopf-product-filter.php' );
		}

		/**
		 * Product Filter Get Between
		 * @since    1.0.0
		 */
		public static function woopf_get_between( $content, $start, $end ){
			$r = explode($start, $content);
			if (isset($r[1])):
				$r = explode($end, $r[1]);
				return $r[0];
			endif;
		  return '';
		}

		/**
		 * Internatinal Support
		 * @since    1.0.0
		 */
		public static function woopf_utf8_decode( $str ) {
			$str = preg_replace( "/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode( $str ) );
			return html_entity_decode( $str, null, 'UTF-8' );
		}

		/**
		 * WPML Support
		 * @since    1.0.0
		 */
		public static function woopf_wpml_get_id( $id ) {
			if( function_exists( 'icl_object_id' ) ) :
				return icl_object_id( $id, 'page', true );
			else :
				return $id;
			endif;
		}
        
		/**
		 * WPML terms
		 * @since    1.0.0
		 */
		public static function woopf_wpml_include_terms( $curr_include, $attr ) {
			if( function_exists( 'icl_object_id' ) ) :
				global $sitepress;
				$translated_include = array();
				foreach( $curr_include as $curr ) :
					$current_term = get_term_by( 'slug', $curr, $attr );
					if($current_term) :
						$get_default_language = 'get_default_language';
						$get_current_language = 'get_current_language';
						$default_language = $sitepress->{$get_default_language}();
						$current_language = $sitepress->{$get_current_language}();

						$term_id = $current_term->term_id;
						if ( $default_language != $current_language ) :
							$term_id = icl_object_id( $term_id, $attr, false, $current_language );
						endif;

						$term = get_term( $term_id, $attr );
						$translated_include[] = $term->slug;

					endif;
				endforeach;
				return $translated_include;
		   else : return $curr_include; 
		  endif;
		}

		/**
		 * Get Filter Settings
		 * @since    1.0.0
		 */
		public static function woopf_check_appearance() {
			global $prdctfltr_global;
			if ( isset($prdctfltr_global['active']) && $prdctfltr_global['active'] == 'true' ) {
				if ( isset( $prdctfltr_global['woo_template'] ) ) {
					unset($prdctfltr_global['woo_template']);
				}
				if ( isset( $prdctfltr_global['widget_search'] ) && !isset( $prdctfltr_global['sc_query'] ) ) {
					echo '<span class="woopf_error"><small>' . esc_html__( 'Product Filter was already activated on this page using a template override. Uncheck the Enable/Disable Product Filter Template Overrides in the product filter advanced options tab to use the widget version instead of the order by filter template override.', 'woopf' ) . '</small></span>';
				}
				else if ( isset( $prdctfltr_global['widget_search'] ) && isset( $prdctfltr_global['sc_query'] ) ) {
					echo '<span class="woopf_error"><small>' . esc_html__( 'Product Filter was already activated on this page using a shortcode. You cannot use the widget filter and the shortcode filter on a same page. If you want to use the widget filter with the shortcode then use the shortcode parameter', 'woopf' ) . ' <code>use_filter="no"</code> ' .  esc_html__( 'This parameter will hide the shortcode filter and the widget filter will appear.', 'woopf' ) . '</small></span>';
				}
				else if ( isset( $prdctfltr_global['sc_query'] ) && !isset( $prdctfltr_global['sc_ajax'] ) ) {
					echo '<span class="woopf_error"><small>' . esc_html__( 'Product Filter was already activated on this page using a non-ajax shortcode. Multiple shortcode instances are only possible when AJAX is activated for all shortcodes used in the page.', 'woopf' ) . '</small></span>';
				}
				else if ( isset( $prdctfltr_global['sc_query'] ) && isset( $prdctfltr_global['sc_ajax'] ) ) {
					$pf_dont_return = true;
				}
				
				if ( !isset( $pf_dont_return ) ) : return false; endif;
			}
			
			$curr_shop_disable = get_option( 'wc_settings_woopf_shop_disable', 'no' );
			if ( $curr_shop_disable == 'yes' && is_shop() && !is_product_category() ) :
				if ( isset( $prdctfltr_global['woo_template'] ) ) :
					unset($prdctfltr_global['woo_template']);
				endif;
			  return false;
			endif;

			$curr_display_disable = get_option( 'wc_settings_woopf_disable_display', array( 'subcategories' ) );
			if ( is_shop() && !is_product_category() && in_array( get_option( 'woocommerce_shop_page_display' ), $curr_display_disable ) ) :
				if ( isset( $prdctfltr_global['woo_template'] ) ) :
					unset($prdctfltr_global['woo_template']);
				endif;
			  return false;
			endif;
			if ( is_product_category() ) :
				$pf_queried_term = get_queried_object();
				$display_type = get_woocommerce_term_meta( $pf_queried_term->term_id, 'display_type', true );
				$display_type = ( $display_type == '' ? get_option( 'woocommerce_category_archive_display' ) : $display_type );

				if ( in_array( $display_type, $curr_display_disable ) ) :
					if ( isset( $prdctfltr_global['woo_template'] ) ) :
						unset($prdctfltr_global['woo_template']);
				    endif;
				  return false;
				endif;
			endif;
		}

		/**
		 * Get Filter Style
		 * @since    1.0.0
		 */
		public static function woopf_get_styles( $curr_options, $curr_mod ) {
			$curr_styles = array(
				( in_array( $curr_options['wc_settings_woopf_style_preset'], array( 'pf_arrow', 'pf_arrow_inline', 'pf_default', 'pf_default_inline', 'pf_select', 'pf_default_select', 'pf_sidebar', 'pf_sidebar_right', 'pf_sidebar_css', 'pf_sidebar_css_right', 'pf_fullscreen' ) ) ? ' ' . $curr_options['wc_settings_woopf_style_preset'] : 'pf_default' ),
				( $curr_options['wc_settings_woopf_always_visible'] == 'no' && $curr_options['wc_settings_woopf_disable_bar'] == 'no' || in_array( $curr_options['wc_settings_woopf_style_preset'], array( 'pf_sidebar', 'pf_sidebar_right', 'pf_sidebar_css', 'pf_sidebar_css_right', 'pf_fullscreen' ) ) ? 'woopf_slide' : 'woopf_always_visible' ),
				( $curr_options['wc_settings_woopf_click_filter'] == 'no' ? 'woopf_click' : 'woopf_click_filter' ),
				( $curr_options['wc_settings_woopf_limit_max_height'] == 'no' ? 'woopf_rows' : 'woopf_maxheight' ),
				( $curr_options['wc_settings_woopf_custom_scrollbar'] == 'no' ? '' : 'woopf_scroll_active' ),
				( $curr_options['wc_settings_woopf_disable_bar'] == 'no' || in_array( $curr_options['wc_settings_woopf_style_preset'], array( 'pf_sidebar', 'pf_sidebar_right', 'pf_sidebar_css', 'pf_sidebar_css_right' ) ) ? '' : 'woopf_disable_bar' ),
				$curr_mod,
				( $curr_options['wc_settings_woopf_adoptive'] == 'no' ? '' : $curr_options['wc_settings_woopf_adoptive_style'] ),
				$curr_options['wc_settings_woopf_style_checkboxes'],
				( $curr_options['wc_settings_woopf_show_search'] == 'no' ? '' : 'woopf_search_fields' ),
			);
			return $curr_styles;
		}

		/**
		 * Get Filter Settings
		 * @since    1.0.0
		 */
		public static function woopf_get_settings() {
			global $prdctfltr_global;
			if ( isset( $prdctfltr_global['preset'] ) ) { 
				$get_options = $prdctfltr_global['preset']; 
			}
			if ( !isset($prdctfltr_global['disable_overrides']) || ( isset($prdctfltr_global['disable_overrides']) && $prdctfltr_global['disable_overrides'] !== 'yes' ) ) {
				$curr_overrides = get_option( 'woopf_overrides', array() );
				$pf_check_overrides = array( 'product_cat', 'product_tag', 'characteristics' );

				foreach ( $pf_check_overrides as $pf_check_override ) :
					if ( isset($_GET[$pf_check_override]) && $_GET[$pf_check_override] !== '' || get_query_var( $pf_check_override ) !== '' ) {
						if ( !isset( $_GET[$pf_check_override] ) ) :
							$_GET[$pf_check_override] = get_query_var( $pf_check_override ); 
						endif;
						if ( !term_exists( $_GET[$pf_check_override], $pf_check_override ) ) : 
							continue; 
						endif;
						if ( is_array($curr_overrides) && isset($curr_overrides[$pf_check_override]) ) {

							if ( array_key_exists($_GET[$pf_check_override], $curr_overrides[$pf_check_override]) ) {
								$get_options = $curr_overrides[$pf_check_override][$_GET[$pf_check_override]];
								break;
							}

							else if ( $pf_check_override == 'product_cat' ) :
								$curr_check = get_term_by( 'slug', $_GET[$pf_check_override], $pf_check_override );
								if ( $curr_check->parent !== 0 ) :
									$curr_check_parent = get_term_by( 'id', $curr_check->parent, $pf_check_override );
									if ( array_key_exists( $curr_check_parent->slug, $curr_overrides[$pf_check_override]) ) :
										$get_options = $curr_overrides[$pf_check_override][$curr_check_parent->slug];
										break;
									endif;
								endif;
							endif;

						}
					}
				endforeach;
			}

			if ( isset($get_options) ) :
				$curr_or_presets = get_option( 'woopf_templates', array() );
				if ( isset($curr_or_presets) && is_array($curr_or_presets) ) :
					if ( array_key_exists($get_options, $curr_or_presets) ) :
						$get_curr_options = json_decode(stripslashes($curr_or_presets[$get_options]), true);
					endif;
				endif;
			endif;

			$pf_chck_settings = array(
				'wc_settings_woopf_style_preset' => 'pf_default',
				'wc_settings_woopf_always_visible' => 'no',
				'wc_settings_woopf_click_filter' => 'no',
				'wc_settings_woopf_limit_max_height' => 'no',
				'wc_settings_woopf_max_height' => 150,
				'wc_settings_woopf_custom_scrollbar' => 'no',
				'wc_settings_woopf_disable_bar' => 'no',
				'wc_settings_woopf_icon' => '',
				'wc_settings_woopf_max_columns' => 6,
				'wc_settings_woopf_adoptive' => 'no',
				'wc_settings_woopf_cat_adoptive' => 'no',
				'wc_settings_woopf_tag_adoptive' => 'no',
				'wc_settings_woopf_chars_adoptive' => 'no',
				'wc_settings_woopf_price_adoptive' => 'no',
				'wc_settings_woopf_orderby_title' => '',
				'wc_settings_woopf_price_title' => '',
				'wc_settings_woopf_price_range' => 100,
				'wc_settings_woopf_price_range_add' => 100,
				'wc_settings_woopf_price_range_limit' => 6,
				'wc_settings_woopf_cat_title' => '',
				'wc_settings_woopf_cat_orderby' => '',
				'wc_settings_woopf_cat_order' => '',
				'wc_settings_woopf_cat_relation' => 'IN',
				'wc_settings_woopf_cat_limit' => 0,
				'wc_settings_woopf_cat_hierarchy' => 'no',
				'wc_settings_woopf_cat_multi' => 'no',
				'wc_settings_woopf_include_cats' => array(),
				'wc_settings_woopf_tag_title' => '',
				'wc_settings_woopf_tag_orderby' => '',
				'wc_settings_woopf_tag_order' => '',
				'wc_settings_woopf_tag_relation' => 'IN',
				'wc_settings_woopf_tag_limit' => 0,
				'wc_settings_woopf_tag_multi' => 'no',
				'wc_settings_woopf_include_tags' => array(),
				'wc_settings_woopf_custom_tax_title' => '',
				'wc_settings_woopf_custom_tax_orderby' => '',
				'wc_settings_woopf_custom_tax_order' => '',
				'wc_settings_woopf_custom_tax_relation' => 'IN',
				'wc_settings_woopf_custom_tax_limit' => 0,
				'wc_settings_woopf_chars_multi' => 'no',
				'wc_settings_woopf_include_chars' => array(),
				'wc_settings_woopf_disable_sale' => 'no',
				'wc_settings_woopf_noproducts' => '',
				'wc_settings_woopf_advanced_filters' => array(),
				'wc_settings_woopf_range_filters' => array(),
				'wc_settings_woopf_disable_instock' => 'no',
				'wc_settings_woopf_title' => '',
				'wc_settings_woopf_style_mode' => 'pf_mod_multirow',
				'wc_settings_woopf_instock_title' => '',
				'wc_settings_woopf_disable_reset' => 'no',
				'wc_settings_woopf_include_orderby' => array( 'menu_order', 'popularity', 'rating', 'date' ,'price', 'price-desc' ),
				'wc_settings_woopf_adoptive_style' => 'pf_adptv_default',
				'wc_settings_woopf_show_counts' => 'no',
				'wc_settings_woopf_disable_showresults' => 'no',
				'wc_settings_woopf_orderby_none' => 'no',
				'wc_settings_woopf_price_none' => 'no',
				'wc_settings_woopf_cat_none' => 'no',
				'wc_settings_woopf_tag_none' => 'no',
				'wc_settings_woopf_chars_none' => 'no',
				'wc_settings_woopf_perpage_title' => '',
				'wc_settings_woopf_perpage_label' => '',
				'wc_settings_woopf_perpage_range' => 20,
				'wc_settings_woopf_perpage_range_limit' => 5,
				'wc_settings_woopf_cat_mode' => 'showall',
				'wc_settings_woopf_style_checkboxes' => 'woopf_round',
				'wc_settings_woopf_cat_hierarchy_mode' => 'no',
				'wc_settings_woopf_show_search' => 'no',
				'wc_settings_woopf_button_position' => 'bottom',
				'wc_settings_woopf_submit' => '',
				'wc_settings_woopf_loader' => 'spinning-circles'
			);

			if ( isset($get_curr_options) ) :
				$curr_options = $get_curr_options;
				foreach ( $pf_chck_settings as $z => $x) :
					if ( !isset($curr_options[$z]) ) :
						$curr_options[$z] = $x;
					endif;
				endforeach;

				$wc_settings_woopf_active_filters = $curr_options['wc_settings_woopf_active_filters'];

				if ( is_array($wc_settings_woopf_active_filters) ) :
					$wc_settings_woopf_selected = array();
					$wc_settings_woopf_attributes = array();
					foreach ( $wc_settings_woopf_active_filters as $k ) :
						if (substr($k, 0, 3) == 'pa_') :
							$wc_settings_woopf_attributes[] = $k;
						endif;
					endforeach;
				endif;

				$curr_attrs = $wc_settings_woopf_attributes;

				foreach ( $curr_attrs as $k => $attr ) :
					$curr_array = array(
						'wc_settings_woopf_'.$attr.'_hierarchy' => 'no',
						'wc_settings_woopf_'.$attr.'_none' => 'no',
						'wc_settings_woopf_'.$attr.'_adoptive' => 'no',
						'wc_settings_woopf_'.$attr.'_title' => '',
						'wc_settings_woopf_'.$attr.'_orderby' => '',
						'wc_settings_woopf_'.$attr.'_order' => '',
						'wc_settings_woopf_'.$attr.'_relation' => 'IN',
						'wc_settings_woopf_'.$attr => 'pf_attr_text',
						'wc_settings_woopf_'.$attr.'_multi' => 'no',
						'wc_settings_woopf_include_'.$attr => array()
					);

					foreach ( $curr_array as $dk => $dv ) :
						if ( !isset($curr_options[$dk]) ) :
							$curr_options[$dk] = $dv;
						endif;
					endforeach;
				endforeach;
			else :
				$wc_settings_woopf_active_filters = get_option( 'wc_settings_woopf_active_filters' );

				if ( $wc_settings_woopf_active_filters === false ) {
					$wc_settings_woopf_selected = get_option( 'wc_settings_woopf_selected', array('sort','price','cat') );
					$wc_settings_woopf_attributes = get_option( 'wc_settings_woopf_attributes', array() );
					$wc_settings_woopf_active_filters = array();
					$wc_settings_woopf_active_filters = array_merge( $wc_settings_woopf_selected,  $wc_settings_woopf_attributes );
				}
				else if ( is_array($wc_settings_woopf_active_filters) ) {
					$wc_settings_woopf_selected = array();
					$wc_settings_woopf_attributes = array();
					foreach ( $wc_settings_woopf_active_filters as $k ) :
						if (substr($k, 0, 3) == 'pa_') :
							$wc_settings_woopf_attributes[] = $k;
						endif;
					endforeach;
				}

				$curr_attrs = $wc_settings_woopf_attributes;
				$curr_options = array(
					'wc_settings_woopf_selected' => $wc_settings_woopf_selected,
					'wc_settings_woopf_attributes' => $wc_settings_woopf_attributes,
					'wc_settings_woopf_active_filters' => $wc_settings_woopf_active_filters
				);
				
				foreach ( $pf_chck_settings as $z => $x) :
					$curr_z = get_option( $z );
					if ( $curr_z === false ) :
						$curr_options[$z] = $x;
					else:
						$curr_options[$z] = $curr_z;
					endif;
				endforeach;

				foreach ( $curr_attrs as $k => $attr ) :
					$curr_options['wc_settings_woopf_'.$attr.'_hierarchy'] = get_option( 'wc_settings_woopf_'.$attr.'_hierarchy', 'no' );
					$curr_options['wc_settings_woopf_'.$attr.'_none'] = get_option( 'wc_settings_woopf_'.$attr.'_none', 'no' );
					$curr_options['wc_settings_woopf_'.$attr.'_adoptive'] = get_option( 'wc_settings_woopf_'.$attr.'_adoptive', 'no' );
					$curr_options['wc_settings_woopf_'.$attr.'_title'] = get_option( 'wc_settings_woopf_'.$attr.'_title', '' );
					$curr_options['wc_settings_woopf_'.$attr.'_orderby'] = get_option( 'wc_settings_woopf_'.$attr.'_orderby', '' );
					$curr_options['wc_settings_woopf_'.$attr.'_order'] = get_option( 'wc_settings_woopf_'.$attr.'_order', '' );
					$curr_options['wc_settings_woopf_'.$attr.'_relation'] = get_option( 'wc_settings_woopf_'.$attr.'_relation', 'IN' );
					$curr_options['wc_settings_woopf_' . $attr] = get_option( 'wc_settings_woopf_' . $attr, 'pf_attr_text' );
					$curr_options['wc_settings_woopf_' . $attr . '_multi'] = get_option( 'wc_settings_woopf_' . $attr . '_multi', 'no' );
					$curr_options['wc_settings_woopf_include_' . $attr] = get_option( 'wc_settings_woopf_include_' . $attr, array() );
				endforeach;
			endif;
			if ( $curr_options['wc_settings_woopf_button_position'] == 'top' ) :
				add_action( 'woopf_filter_form_before', 'WC_Woopf::woopf_filter_buttons', 10, 2 );
				remove_action( 'woopf_filter_form_after', 'WC_Woopf::woopf_filter_buttons');
			else :
				add_action( 'woopf_filter_form_after', 'WC_Woopf::woopf_filter_buttons', 10, 2 );
				remove_action( 'woopf_filter_form_before', 'WC_Woopf::woopf_filter_buttons');
		    endif;
		  return $curr_options;
		}

		/**
		 * Get Filter Settings
		 * @since    1.0.0
		 */
		public static function woopf_get_terms( $curr_term, $curr_term_args ) {
			if ( !isset($_GET['orderby']) && ( defined('DOING_AJAX') && DOING_AJAX ) === false || !isset($_GET['orderby']) ) {
				$curr_terms = get_terms( $curr_term, $curr_term_args );
			}
			else if ( isset($_GET['orderby']) ) {
				$curr_keep = $_GET['orderby'];
				unset($_GET['orderby']);
				$curr_terms = get_terms( $curr_term, $curr_term_args );
				$_GET['orderby'] = $curr_keep;
			}
			return $curr_terms;
		}

		/**
		 * WooCommerce Pagination Filter
		 * @since    1.0.0
		 */
		public static function woopf_pagination_filter ( $args ) {
			global $prdctfltr_global;
			if ( isset($prdctfltr_global['sc_ajax']) || self::$settings['wc_settings_woopf_use_ajax'] == 'yes' && is_woocommerce() ) :
				$args['base'] = esc_url( add_query_arg('paged','%#%') );
				$args['format'] = '';
			endif;
		  return $args;
		}

		/**
		 * Product Filter Form Buttons
		 * @since    1.0.0
		 */
		public static function woopf_filter_buttons ( $curr_options, $pf_activated ) {
			global $prdctfltr_global;
			$curr_elements = ( $curr_options['wc_settings_woopf_active_filters'] !== NULL ? $curr_options['wc_settings_woopf_active_filters'] : array() );
			ob_start();
		?><div class="woopf_buttons"><?php
				if ( $curr_options['wc_settings_woopf_click_filter'] == 'no' ) :
			?><a <?php ( isset( $prdctfltr_global['active'] ) ? '' : 'id="woopf_woocommerce_filter_submit" ' ); ?>class="button woopf_woocommerce_filter_submit" href="#"><?php
						if ( $curr_options['wc_settings_woopf_submit'] !== '' ) :
							echo esc_html($curr_options['wc_settings_woopf_submit']);
						else :
							esc_html_e('Filter selected', 'woopf');
						endif;
					?></a><?php
				endif;
				if ( $curr_options['wc_settings_woopf_disable_sale'] == 'no' ) :
				?><span class="woopf_sale"><?php
					printf('<label%2$s><input name="sale_products" type="checkbox"%3$s/><span>%1$s</span></label>', esc_html__('Show only products on sale' , 'woopf'), ( isset($_GET['sale_products']) ? ' class="woopf_active"' : '' ), ( isset($_GET['sale_products']) ? ' checked' : '' ) );
				?></span><?php
				endif;
				if ( $curr_options['wc_settings_woopf_disable_instock'] == 'no' && !in_array('instock', $curr_elements) ) :
				?><span class="woopf_instock"><?php
					$curr_instock = get_option( 'wc_settings_woopf_instock', 'no' );
					if ( $curr_instock == 'yes' ) :
						printf('<label%2$s><input name="instock_products" type="checkbox" value="both"%3$s/><span>%1$s</span></label>', esc_html__('Show out of stock products' , 'woopf'), ( isset($_GET['instock_products']) ? ' class="woopf_active"' : '' ), ( isset($_GET['instock_products']) ? ' checked' : '' ) );
					else :
						printf('<label%2$s><input name="instock_products" type="checkbox" value="in"%3$s/><span>%1$s</span></label>', esc_html__('In stock only' , 'woopf'), ( isset($_GET['instock_products']) ? ' class="woopf_active"' : '' ), ( isset($_GET['instock_products']) ? ' checked' : '' ) );
					endif;
				?></span><?php
				endif;
				if ( $curr_options['wc_settings_woopf_disable_reset'] == 'no' && isset($pf_activated) && !empty($pf_activated) ) :
				?><span class="woopf_reset"><?php
					printf('<label><input name="reset_filter" type="checkbox" /><span>%1$s</span></label>', esc_html__('Clear all filters' , 'woopf') );
				?></span><?php 
				endif; ?></div><?php
			$out = ob_get_clean();
			print($out);
		}
	}
	
	include_once( WOOPF_PATH . 'public/woopf-shortcode.php' );
	add_action( 'init', array( 'WC_Woopf', 'init' ), 998 );
?>
