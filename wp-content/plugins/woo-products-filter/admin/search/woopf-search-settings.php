<?php
/**
 * Product Filter Settings Class
 *
 * @link       http://theemon.com/p/Woo-Products-filter/LivePreview
 * @since      1.0.0
 *
 * @package    woo-products-filter
 * @subpackage woo-products-filter/admin/search
 */
	class WC_Settings_Woopf {

		/**
		 * Init
		 * @since    1.0.0
		 */
		public static function init() {
			add_action( 'admin_enqueue_scripts', __CLASS__ . '::woopf_admin_scripts' );
			add_action( 'woopf_settings_tabs_settings_products_filter', __CLASS__ . '::woopf_settings_tab' );
			add_action( 'woopf_update_options_settings_products_filter', __CLASS__ . '::woopf_update_settings' );
			add_action( 'woocommerce_admin_field_pf_filter', __CLASS__ . '::woopf_pf_filter', 10 );

			add_action( 'wp_ajax_woopf_admin_save', __CLASS__ . '::woopf_admin_save' );
			add_action( 'wwoopfdctfltr_admin_load', __CLASS__ . '::woopf_admin_load' );
			add_action( 'wp_ajax_woopf_admin_delete', __CLASS__ . '::woopf_admin_delete' );
			add_action( 'wp_ajax_woopf_or_add', __CLASS__ . '::woopf_or_add' );
			add_action( 'wp_ajax_woopf_or_remove', __CLASS__ . '::woopf_or_remove' );
			add_action( 'wp_ajax_woopf_c_fields', __CLASS__ . '::woopf_c_fields' );
			add_action( 'wp_ajax_woopf_c_terms', __CLASS__ . '::woopf_c_terms' );
			add_action( 'wp_ajax_woopf_r_fields', __CLASS__ . '::woopf_r_fields' );
			add_action( 'wp_ajax_woopf_r_terms', __CLASS__ . '::woopf_r_terms' );
		}
		
		/**
		 * Admin script load
		 * @since    1.0.0
		 */
		public static function woopf_admin_scripts($hook) {
			if ( isset( $_GET['page']) && ($_GET['page'] == 'woo-pf' )) {
				wp_register_style( 'woopf-admin', WOOPF_URL.'/admin/css/woopf-admin.css', false, '1.0.0' );
				wp_enqueue_style( 'woopf-admin' );
				wp_register_script( 'woopf-admin-js', WOOPF_URL.'/admin/js/woopf-admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), '1.0.0', true );
				wp_enqueue_script( 'woopf-admin-js' );

				$curr_args = array( 'ajax' => admin_url( 'admin-ajax.php' ) );
				wp_localize_script( 'woopf-admin-js', 'woopf', $curr_args );
			}
		}
		
		/**
		 * Get attribute
		 * @since    1.0.0
		 */
		function wc_get_attribute_taxonomies() {
			$transient_name = 'wc_attribute_taxonomies';
			if ( false === ( $attribute_taxonomies = get_transient( $transient_name ) ) ) {
				global $wpdb;	
				$get_results = 'get_results';
				$attribute_taxonomies = $wpdb->{$get_results}( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies" );
				set_transient( $transient_name, $attribute_taxonomies );
			}
			return (array) array_filter( apply_filters( 'woocommerce_attribute_taxonomies', $attribute_taxonomies ) );
		}
		/**
		 * Product filter
		 * @since    1.0.0
		 */
		public static function woopf_pf_filter($field) {
		global $woocommerce;
		$plugin_url = 'plugin_url';
	?><tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?></label>
				<?php echo '<img class="help_tip" data-tip="' . esc_attr( $field['desc'] ) . '" src="' . $woocommerce->{$plugin_url}() . '/assets/images/help.png" height="16" width="16" />'; ?>
			</th>
			<td class="forminp forminp-<?php echo sanitize_title( $field['type'] ) ?>"><?php
					$pf_filters_selected = get_option('wc_settings_woopf_active_filters');
					if ( $pf_filters_selected === false ) {
						$pf_filters_selected = array();
					}
					if ( empty($pf_filters_selected) ) {
						$curr_selected = get_option( 'wc_settings_woopf_selected', array('sort','price','cat') );
						$curr_selected_attr = get_option( 'wc_settings_woopf_attributes', array() );
						$pf_filters_selected = array_merge($curr_selected, $curr_selected_attr);
					}

					$curr_filters = array(
						'sort' => esc_html__('Sort By', 'woopf'),
						'price' => esc_html__('By Price', 'woopf'),
						'cat' => esc_html__('By Categories', 'woopf'),
						'tag' => esc_html__('By Tags', 'woopf'),
						'char' => esc_html__('By Characteristics', 'woopf'),
						'instock' => esc_html__('In Stock Filter', 'woopf'),
						'per_page' => esc_html__('Products Per Page', 'woopf')
					);
					
					//$curr_attr = array();
					global $wpdb;
					$get_results = 'get_results';
					$attribute_taxonomies = $wpdb->{$get_results}( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies" );

					if ( $attribute_taxonomies ) {
					$curr_attr = array();
					foreach ( $attribute_taxonomies as $tax ) {
						$curr_label = ! empty( $tax->attribute_label ) ? $tax->attribute_label : $tax->attribute_name;
						$curr_attr['pa_' . $tax->attribute_name] = ucfirst($curr_label);
					  }
					}	
					$pf_filters = ( is_array($curr_filters) ? $curr_filters : array() ) + ( is_array($curr_attr) ? $curr_attr : array() );
				?><p class="form-field woopf_customizer_fields"><?php
					foreach ( $pf_filters as $k => $v ) {
						if ( in_array($k, $pf_filters_selected) ) {
							$add['class'] = ' pf_active';
							$add['icon'] = '<i class="woopf-eye"></i>';
						}
						else {
							$add['class'] = '';
							$add['icon'] = '<i class="woopf-eye-disabled"></i>';
						}
				?><a href="#" class="woopf_c_add_filter<?php echo esc_attr($add['class']); ?>" data-filter="<?php echo esc_attr($k); ?>">
						<?php print($add['icon']); ?> 
						<span><?php print($v); ?></span>
					</a><?php
				   }
				  ?><a href="#" class="woopf_c_add pf_advanced"><i class="woopf-plus"></i> <span><?php esc_html_e('Add advanced filter', 'woopf'); ?></span></a>
					<a href="#" class="woopf_c_add pf_range"><i class="woopf-plus"></i> <span><?php esc_html_e('Add range filter', 'woopf'); ?></span></a>
				</p>
				<p class="form-field woopf_customizer"><?php
					$pf_filters_advanced = get_option('wc_settings_woopf_advanced_filters');
					if ( $pf_filters_advanced === false ) { $pf_filters_advanced = array(); }

					$pf_filters_range = get_option('wc_settings_woopf_range_filters');
					if ( $pf_filters_range === false ) { $pf_filters_range = array(); }

					$i=0;$q=0;

					foreach ( $pf_filters_selected as $v ) {
						if ( $v == 'advanced' ) {
					?><span class="pf_element adv" data-filter="advanced" data-id="<?php echo esc_attr($i); ?>">
								<span><?php esc_html_e('Advanced Filter', 'woopf'); ?></span>
								<a href="#" class="woopf_c_delete"><i class="woopf-delete"></i></a>
								<a href="#" class="woopf_c_move"><i class="woopf-move"></i></a>
								<span class="pf_options_holder"><?php
								$taxonomies = get_object_taxonomies( 'product', 'object' );

								$html = '';
								$html .= sprintf( '<label><input type="text" name="pfa_title[%1$s]" value="%2$s"/> %3$s</label>', $i, $pf_filters_advanced['pfa_title'][$i], esc_html__( 'Override title.', 'woopf' ) );
								$html .= sprintf('<label><select class="woopf_adv_select" name="pfa_taxonomy[%1$s]">', $i);

								foreach ( $taxonomies as $k => $v ) {
									if ( $k == 'product_type' ) { continue; }
									$html .= '<option value="' . $k . '"' . ( $pf_filters_advanced['pfa_taxonomy'][$i] == $k ? ' selected="selected"' : '' ) .'>' . $v->label . '</option>';
								}
								$html .= '</select></label>';

								$catalog_attrs = get_terms( $pf_filters_advanced['pfa_taxonomy'][$i] );
								$curr_options = '';
								if ( !empty( $catalog_attrs ) && !is_wp_error( $catalog_attrs ) ){
									foreach ( $catalog_attrs as $term ) {
										$decode_slug = WC_Woopf::woopf_utf8_decode($term->slug);
										$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $decode_slug, $term->name, ( in_array($decode_slug, $pf_filters_advanced['pfa_include'][$i]) ? ' selected="selected"' : '' ) );
									}
								}

								$html .= sprintf( '<label><span>%3$s</span> <select name="pfa_include[%2$s][]" multiple="multiple">%1$s</select></label>', $curr_options, $i, esc_html__( 'Include terms', 'woopf' ) );

								$curr_options = '';
								$orderby_params = array(
									'' => esc_html__( 'None', 'woopf' ),
									'id' => esc_html__( 'ID', 'woopf' ),
									'name' => esc_html__( 'Name', 'woopf' ),
									'number' => esc_html__( 'Number', 'woopf' ),
									'slug' => esc_html__( 'Slug', 'woopf' ),
									'count' => esc_html__( 'Count', 'woopf' )
								);
								$orderby_params_tax = array(
									'' => esc_html__( 'None', 'woopf' ),
									'id' => esc_html__( 'ID', 'woopf' ),
									'name' => esc_html__( 'Name', 'woopf' ),
									'slug' => esc_html__( 'Slug', 'woopf' ),
									'count' => esc_html__( 'Count', 'woopf' )
								);
								foreach ( $orderby_params as $k => $v ) {
									$selected = ( isset($pf_filters_advanced['pfa_orderby'][$i]) && $pf_filters_advanced['pfa_orderby'][$i] == $k ? ' selected="selected"' : '' );
									$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
								}
								$html .= sprintf( '<label><span>%3$s</span> <select name="pfa_orderby[%2$s]">%1$s</select></label>', $curr_options, $i, esc_html__( 'Term order by', 'woopf' ) );

								$curr_options = '';
								$order_params = array(
									'ASC' => esc_html__( 'ASC', 'woopf' ),
									'DESC' => esc_html__( 'DESC', 'woopf' )
								);
								foreach ( $order_params as $k => $v ) {
									$selected = ( isset($pf_filters_advanced['pfa_order'][$i]) && $pf_filters_advanced['pfa_order'][$i] == $k ? ' selected="selected"' : '' );
									$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
								}

								$html .= sprintf( '<label><span>%3$s</span> <select name="pfa_order[%2$s]">%1$s</select></label>', $curr_options, $i, esc_html__( 'Term order', 'woopf' ) );
								$html .= sprintf( '<label><input type="checkbox" name="pfa_multiselect[%1$s]" value="yes" %2$s /> %3$s</label>', $i, ( $pf_filters_advanced['pfa_multiselect'][$i] == 'yes' ? ' checked="checked"' : '' ), esc_html__( 'Use multi select', 'woopf' ) );

								$curr_options = '';
								$relation_params = array(
									'IN' => esc_html__( 'Filtered products have at least one term (IN)', 'woopf' ),
									'AND' => esc_html__( 'Filtered products have selected terms (AND)', 'woopf' )
								);
								foreach ( $relation_params as $k => $v ) {
									$selected = ( isset($pf_filters_advanced['pfa_relation'][$i]) && $pf_filters_advanced['pfa_relation'][$i] == $k ? ' selected="selected"' : '' );
									$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
								}
								$html .= sprintf( '<label><span>%3$s</span> <select name="pfa_relation[%2$s]">%1$s</select></label>', $curr_options, $i, esc_html__( 'Term relation', 'woopf' ) );
								$html .= sprintf( '<label><input type="checkbox" name="pfa_adoptive[%1$s]" value="yes" %2$s /> %3$s</label>', $i, ( $pf_filters_advanced['pfa_adoptive'][$i] == 'yes' ? ' checked="checked"' : '' ), esc_html__( 'Use adoptive filtering', 'woopf' ) );
								$html .= sprintf( '<label><input type="checkbox" name="pfa_none[%1$s]" value="yes" %2$s /> %3$s</label>', $i, ( isset($pf_filters_advanced['pfa_none'][$i]) && $pf_filters_advanced['pfa_none'][$i] == 'yes' ? ' checked="checked"' : '' ), esc_html__( 'Disable None', 'woopf' ) );

								echo $html;
							?></span>
							</span><?php
							$i++;
						}
						else if ( $v == 'range') {
					?><span class="pf_element rng" data-filter="range" data-id="<?php echo esc_attr($q); ?>">
						<span><?php esc_html_e('Range Filter', 'woopf'); ?></span>
						<a href="#" class="woopf_c_delete"><i class="woopf-delete"></i></a>
						<a href="#" class="woopf_c_move"><i class="woopf-move"></i></a>
						<span class="pf_options_holder"><?php
								//$taxonomies = wc_get_attribute_taxonomies();
								global $wpdb;
								$get_results = 'get_results';
								$taxonomies = $wpdb->{$get_results}( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies" );

								$html = '';
								$html .= sprintf( '<label><span>%3$s</span> <input type="text" name="pfr_title[%1$s]" value="%2$s"/></label>', $q, $pf_filters_range['pfr_title'][$q], esc_html__( 'Override title.', 'woopf' ) );
								$html .= sprintf('<label><span>%2$s</span> <select class="woopf_rng_select" name="pfr_taxonomy[%1$s]">', $q, esc_html__( 'Select range', 'woopf' ));
								$html .= '<option value="price"' . ( $pf_filters_range['pfr_taxonomy'][$q] == 'price' ? ' selected="selected"' : '' ) . '>' . esc_html__( 'Price range', 'woopf' ) . '</option>';

								foreach ( $taxonomies as $k => $v ) {
									$curr_label = ! empty( $v->attribute_label ) ? $v->attribute_label : $v->attribute_name;
									$html .= '<option value="pa_' . $v->attribute_name . '"' . ( $pf_filters_range['pfr_taxonomy'][$q] == 'pa_' . $v->attribute_name ? ' selected="selected"' : '' ) .'>' . $curr_label . '</option>';
								}
								$html .= '</select></label>';

								if ( $pf_filters_range['pfr_taxonomy'][$q] !== 'price' ) {

									$catalog_attrs = get_terms( $pf_filters_range['pfr_taxonomy'][$q] );
									$curr_options = '';
									if ( !empty( $catalog_attrs ) && !is_wp_error( $catalog_attrs ) ){
										foreach ( $catalog_attrs as $term ) {
											$decode_slug = WC_Woopf::woopf_utf8_decode($term->slug);
											$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $decode_slug, $term->name, ( in_array($decode_slug, $pf_filters_range['pfr_include'][$q]) ? ' selected="selected"' : '' ) );
										}
									}

									$html .= sprintf( '<label><span>%3$s</span> <select name="pfr_include[%2$s][]" multiple="multiple">%1$s</select></label>', $curr_options, $q, esc_html__( 'Include terms', 'woopf' ) );
									$add_disabled = '';

								}
								else {
									$html .= sprintf( '<label><span>%2$s</span> <select name="pfr_include[%1$s][]" multiple="multiple" disabled></select></label>', $q, esc_html__( 'Include terms', 'woopf' ) );
									$add_disabled = ' disabled';

								}

								$curr_options = '';
								$orderby_params = array(
									'' => esc_html__( 'None', 'woopf' ),
									'id' => esc_html__( 'ID', 'woopf' ),
									'name' => esc_html__( 'Name', 'woopf' ),
									'number' => esc_html__( 'Number', 'woopf' ),
									'slug' => esc_html__( 'Slug', 'woopf' ),
									'count' => esc_html__( 'Count', 'woopf' )
								);
								foreach ( $orderby_params as $k => $v ) {
									$selected = ( isset($pf_filters_range['pfr_orderby'][$q]) && $pf_filters_range['pfr_orderby'][$q] == $k ? ' selected="selected"' : '' );
									$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
								}
								$html .= sprintf( '<label><span>%3$s</span> <select name="pfr_orderby[%2$s]"%4$s>%1$s</select></label>', $curr_options, $q, esc_html__( 'Term order by', 'woopf' ), $add_disabled );

								$curr_options = '';
								$order_params = array(
									'ASC' => esc_html__( 'ASC', 'woopf' ),
									'DESC' => esc_html__( 'DESC', 'woopf' )
								);
								foreach ( $order_params as $k => $v ) {
									$selected = ( isset($pf_filters_advanced['pfr_order'][$q]) && $pf_filters_advanced['pfr_order'][$q] == $k ? ' selected="selected"' : '' );
									$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
								}

								$html .= sprintf( '<label><span>%3$s</span> <select name="pfr_order[%2$s]"%4$s>%1$s</select></label>', $curr_options, $q, esc_html__( 'Term order', 'woopf' ), $add_disabled );

								$catalog_style = array( 'flat' => esc_html__( 'Flat', 'woopf' ), 'modern' => esc_html__( 'Modern', 'woopf' ), 'html5' => esc_html__( 'HTML5', 'woopf' ), 'white' => esc_html__( 'White', 'woopf' ) );
								$curr_options = '';
								foreach ( $catalog_style as $k => $v ) {
									$selected = ( $pf_filters_range['pfr_style'][$q] == $k ? ' selected="selected"' : '' );
									$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
								}

								$html .= sprintf( '<label><span>%2$s</span> <select name="pfr_style[%3$s]">%1$s</select></label>', $curr_options, esc_html__( 'Select style', 'woopf' ), $q );

								$selected = ( $pf_filters_range['pfr_grid'][$q] == 'yes' ? ' checked="checked"' : '' ) ;
								$html .= sprintf( '<label><input type="checkbox" name="pfr_grid[%3$s]" value="yes"%1$s /> %2$s</label>', $selected, esc_html__( 'Use grid', 'woopf' ), $q );

								echo $html;
							?></span>
							</span><?php
							$q++;
						}
						else {
						?><span class="pf_element" data-filter="<?php echo esc_attr($v); ?>">
							<span><?php echo esc_html($pf_filters[$v]); ?></span>
							<a href="#" class="woopf_c_delete"><i class="woopf-delete"></i></a>
							<a href="#" class="woopf_c_move"><i class="woopf-move"></i></a>
						</span><?php
						}
					}
				?></p>
				<p class="form-field woopf_hidden">
					<select name="wc_settings_woopf_active_filters[]" id="wc_settings_woopf_active_filters" class="hidden" multiple="multiple"><?php
						foreach ( $pf_filters_selected as $v ) {
							if ( $v !== 'advanced') {
						?><option value="<?php echo esc_attr($v); ?>" selected="selected"><?php echo esc_html($pf_filters[$v]); ?></option><?php
							}
							else {
						?><option value="<?php echo esc_attr($v); ?>" selected="selected"><?php esc_html_e('Advanced Filter', 'woopf'); ?></option><?php
							}
						}
					?></select>
				</p>
			</td>
		</tr><?php
		}

		/**
		 * Plugin tab setting
		 * @since    1.0.0
		 */
		public static function woopf_add_settings_tab( $settings_tabs ) {
			$settings_tabs['settings_products_filter'] = esc_html__( 'Woo Product Filter', 'woopf' );
			return $settings_tabs;
		}
       
		/**
		 * Setting tab
		 * @since    1.0.0
		 */
		public static function woopf_settings_tab() {
			do_action('woopf_update_options_settings_products_filter');
			woocommerce_admin_fields( self::woopf_get_settings( 'get' ) );
		}
         
		/**
		 * Upadte Setting
		 * @since    1.0.0
		 */
		public static function woopf_update_settings() {
			if ( isset($_POST['pfa_taxonomy']) ) {
				$adv_filters = array();

				for($i = 0; $i < count($_POST['pfa_taxonomy']); $i++ ) {
					$adv_filters['pfa_title'][$i] = $_POST['pfa_title'][$i];
					$adv_filters['pfa_taxonomy'][$i] = $_POST['pfa_taxonomy'][$i];
					$adv_filters['pfa_include'][$i] = ( isset($_POST['pfa_include'][$i]) ? $_POST['pfa_include'][$i] : array() );
					$adv_filters['pfa_orderby'][$i] = ( isset($_POST['pfa_orderby'][$i]) ? $_POST['pfa_orderby'][$i] : '' );
					$adv_filters['pfa_order'][$i] = ( isset($_POST['pfa_order'][$i]) ? $_POST['pfa_order'][$i] : '' );
					$adv_filters['pfa_multiselect'][$i] = ( isset($_POST['pfa_multiselect'][$i]) ? $_POST['pfa_multiselect'][$i] : 'no' );
					$adv_filters['pfa_relation'][$i] = ( isset($_POST['pfa_relation'][$i]) ? $_POST['pfa_relation'][$i] : 'IN' );
					$adv_filters['pfa_adoptive'][$i] = ( isset($_POST['pfa_adoptive'][$i]) ? $_POST['pfa_adoptive'][$i] : 'no' );
					$adv_filters['pfa_none'][$i] = ( isset($_POST['pfa_none'][$i]) ? $_POST['pfa_none'][$i] : 'no' );
				}
				update_option('wc_settings_woopf_advanced_filters', $adv_filters);
			}

			if ( isset($_POST['pfr_taxonomy']) ) {
				$rng_filters = array();

				for($i = 0; $i < count($_POST['pfr_taxonomy']); $i++ ) {
					$rng_filters['pfr_title'][$i] = $_POST['pfr_title'][$i];
					$rng_filters['pfr_taxonomy'][$i] = $_POST['pfr_taxonomy'][$i];
					$rng_filters['pfr_include'][$i] = ( isset($_POST['pfr_include'][$i]) ? $_POST['pfr_include'][$i] : array() );
					$rng_filters['pfr_orderby'][$i] = ( isset($_POST['pfr_orderby'][$i]) ? $_POST['pfr_orderby'][$i] : '' );
					$rng_filters['pfr_order'][$i] = ( isset($_POST['pfr_order'][$i]) ? $_POST['pfr_order'][$i] : '' );
					$rng_filters['pfr_style'][$i] = ( isset($_POST['pfr_style'][$i]) ? $_POST['pfr_style'][$i] : 'flat' );
					$rng_filters['pfr_grid'][$i] = ( isset($_POST['pfr_grid'][$i]) ? $_POST['pfr_grid'][$i] : 'no' );
				}
				update_option('wc_settings_woopf_range_filters', $rng_filters);
			}

			if ( isset($_POST['wc_settings_woopf_active_filters']) ) {
				update_option('wc_settings_woopf_active_filters', $_POST['wc_settings_woopf_active_filters']);
			}
			woocommerce_update_options( self::woopf_get_settings( 'update' ) );
		}
        
		/**
		 * Get Setting
		 * @since    1.0.0
		 */
		public static function woopf_get_settings( $action = 'get' ) {
			$catalog_categories = get_terms( 'product_cat' );
			$curr_cats = array();
			if ( !empty( $catalog_categories ) && !is_wp_error( $catalog_categories ) ){
				foreach ( $catalog_categories as $term ) {
					$curr_cats[$term->slug] = $term->name;
				}
			}

			$catalog_tags = get_terms( 'product_tag' );
			$curr_tags = array();
			if ( !empty( $catalog_tags ) && !is_wp_error( $catalog_tags ) ){
				foreach ( $catalog_tags as $term ) {
					$curr_tags[$term->slug] = $term->name;
				}
			}

			$catalog_chars = ( taxonomy_exists('characteristics') ? get_terms( 'characteristics' ) : array() );
			$curr_chars = array();
			if ( !empty( $catalog_chars ) && !is_wp_error( $catalog_chars ) ){
				foreach ( $catalog_chars as $term ) {
					$curr_chars[$term->slug] = $term->name;
				}
			}

			//$attribute_taxonomies = wc_get_attribute_taxonomies();
			global $wpdb;
			$get_results = 'get_results';
			$attribute_taxonomies = $wpdb->{$get_results}( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies" );
				
			$curr_atts = array();
			if ( !empty( $attribute_taxonomies ) && !is_wp_error( $attribute_taxonomies ) ){
				foreach ( $attribute_taxonomies as $term ) {
					$curr_atts['pa_' . $term->attribute_name] = $term->attribute_name;
				}
			}

			if ( $action == 'get' ) {
		?><ul class="subsubsub"><?php
			$sections = array(
				'presets' => esc_html__( 'Default Filter', 'woopf' ),
				'advanced' => esc_html__( 'Advanced Options', 'woopf' )
			);

			$i=0;
			foreach ( $sections as $k => $v ) {
				$curr_class = ( ( isset( $_GET['section'] ) && $_GET['section'] == $k ) || ( !isset($_GET['section'] ) && $k == 'presets' ) ? ' class="current"' : '' );
				printf( '<li>%3$s<a href="%1$s"%3$s>%2$s</a></li>', admin_url( 'admin.php?page=woo-pf&section=' . $k ), $v, ( $i == 0 ? '' : '  ' ), $curr_class );
				$i++;
			}
		?></ul><br class="clear" /><?php
			}
			if ( isset($_GET['section']) && $_GET['section'] == 'advanced' ) {
				$curr_theme = wp_get_theme();
				require ( WOOPF_PATH . 'admin/search/option/advance-option.php' );
			}
			else if ( ( isset($_GET['section']) && $_GET['section'] == 'presets' ) || !isset($_GET['section']) ) {
				if ( $action == 'get' ) {

					printf( '<h3>%1$s</h3><p>', esc_html__( 'Product Filter', 'woopf' )  );
			?><select id="woopf_filter_presets">
					<option value="default"><?php esc_html_e('Default', 'wcwar'); ?></option>
					<?php
						$curr_presets = get_option('woopf_templates');
						if ( $curr_presets === false ) {
							$curr_presets = array();
						}
						if ( !empty($curr_presets) ) {
							foreach ( $curr_presets as $k => $v ) {
						?><option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($k); ?></option><?php
							}
						}
			 ?></select><?php
				}
				require ( WOOPF_PATH . 'admin/search/option/presets-option.php' );

				if ($attribute_taxonomies) {
					$settings = $settings + array ();
					foreach ($attribute_taxonomies as $tax) {

						$catalog_attrs = get_terms( 'pa_' . $tax->attribute_name );
						$curr_attrs = array();
						if ( !empty( $catalog_attrs ) && !is_wp_error( $catalog_attrs ) ){
							foreach ( $catalog_attrs as $term ) {
								$curr_attrs[ WC_Woopf::woopf_utf8_decode( $term->slug ) ] = $term->name;
							}
						}

						$tax->attribute_label = !empty( $tax->attribute_label ) ? $tax->attribute_label : $tax->attribute_name;

						$settings = $settings + array(
							'section_pa_'.$tax->attribute_name.'_title' => array(
								'name'     => esc_html__( 'By', 'woopf' ) . ' ' . $tax->attribute_label . ' ' . esc_html__( 'Filter Settings', 'woopf' ),
								'type'     => 'title',
								'desc'     => esc_html__( 'Select options for the current attribute.', 'woopf' ),
								'id'       => 'wc_settings_woopf_pa_'.$tax->attribute_name.'_title'
							),
							'woopf_pa_'.$tax->attribute_name.'_title' => array(
								'name' => esc_html__( 'Override ' . $tax->attribute_label . ' Filter Title', 'woopf' ),
								'type' => 'text',
								'desc' => esc_html__( 'Enter title for the characteristics filter. If you leave this field blank default will be used.', 'woopf' ),
								'id'   => 'wc_settings_woopf_pa_'.$tax->attribute_name.'_title',
								'default' => '',
								'css' => 'width:300px;margin-right:12px;'
							),
							'woopf_include_pa_'.$tax->attribute_name => array(
								'name' => esc_html__( 'Select Terms', 'woopf' ),
								'type' => 'multiselect',
								'desc' => esc_html__( 'Select terms to include. Use CTRL+Click to select multiple terms or deselect all.', 'woopf' ),
								'id'   => 'wc_settings_woopf_include_pa_'.$tax->attribute_name,
								'options' => $curr_attrs,
								'default' => array(),
								'css' => 'width:300px;margin-right:12px;'
							),
							'woopf_pa_'.$tax->attribute_name.'_orderby' => array(
								'name' => esc_html__( 'Terms Order By', 'woopf' ),
								'type' => 'select',
								'desc' => esc_html__( 'Select the term order.', 'woopf' ),
								'id'   => 'wc_settings_pa_'.$tax->attribute_name.'_orderby',
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
							'woopf_pa_'.$tax->attribute_name.'_order' => array(
								'name' => esc_html__( 'Terms Order', 'woopf' ),
								'type' => 'select',
								'desc' => esc_html__( 'Select ascending or descending order.', 'woopf' ),
								'id'   => 'wc_settings_woopf_pa_'.$tax->attribute_name.'_order',
								'options' => array(
										'ASC' => esc_html__( 'ASC', 'woopf' ),
										'DESC' => esc_html__( 'DESC', 'woopf' )
									),
								'default' => array(),
								'css' => 'width:300px;margin-right:12px;'
							),
							'woopf_pa_'.$tax->attribute_name => array(
								'name' => esc_html__( 'Appearance', 'woopf' ),
								'type' => 'select',
								'desc' => esc_html__( 'Select style preset to use with the current attribute.', 'woopf' ),
								'id'   => 'wc_settings_woopf_pa_'.$tax->attribute_name,
								'options' => array(
									'pf_attr_text' => esc_html__( 'Text', 'woopf' ),
									'pf_attr_imgtext' => esc_html__( 'Thumbnails with text', 'woopf' ),
									'pf_attr_img' => esc_html__( 'Thumbnails only', 'woopf' )
								),
								'default' => 'pf_attr_text',
								'css' => 'width:300px;margin-right:12px;'
							),
							'woopf_pa_'.$tax->attribute_name.'_hierarchy' => array(
								'name' => esc_html__( 'Use Attribute Hierarchy', 'woopf' ),
								'type' => 'checkbox',
								'desc' => esc_html__( 'Check this option to enable attribute hierarchy.', 'woopf' ),
								'id'   => 'wc_settings_woopf_pa_'.$tax->attribute_name.'_hierarchy',
								'default' => 'no',
							),
							'woopf_pa_'.$tax->attribute_name.'_multi' => array(
								'name' => esc_html__( 'Use Multi Select', 'woopf' ),
								'type' => 'checkbox',
								'desc' => esc_html__( 'Check this option to enable multi-select on current attribute.', 'woopf' ),
								'id'   => 'wc_settings_woopf_pa_'.$tax->attribute_name.'_multi',
								'default' => 'no',
							),
							'woopf_pa_'.$tax->attribute_name.'_relation' => array(
								'name' => esc_html__( 'Multi Select Terms Relation', 'woopf' ),
								'type' => 'select',
								'desc' => esc_html__( 'Select terms relation when multiple terms are selected.', 'woopf' ),
								'id'   => 'wc_settings_woopf_pa_'.$tax->attribute_name.'_relation',
								'options' => array(
										'IN' => esc_html__( 'Filtered products have at least one term (IN)', 'woopf' ),
										'AND' => esc_html__( 'Filtered products have selected terms (AND)', 'woopf' )
									),
								'default' => array(),
								'css' => 'width:300px;margin-right:12px;'
							),
							'woopf_pa_'.$tax->attribute_name.'_adoptive' => array(
								'name' => esc_html__( 'Use Adoptive Filtering', 'woopf' ),
								'type' => 'checkbox',
								'desc' => esc_html__( 'Check this option to use adoptive filtering on current attribute.', 'woopf' ),
								'id'   => 'wc_settings_woopf_pa_'.$tax->attribute_name.'_adoptive',
								'default' => 'no',
							),
							'woopf_pa_'.$tax->attribute_name.'_none' => array(
								'name' => esc_html__( 'Hide None', 'woopf' ),
								'type' => 'checkbox',
								'desc' => esc_html__( 'Check this option to hide None on current attribute.', 'woopf' ),
								'id'   => 'wc_settings_woopf_pa_'.$tax->attribute_name.'_none',
								'default' => 'no',
							),
							'section_pa_'.$tax->attribute_name.'_end' => array(
								'type' => 'sectionend',
								'id' => 'wc_settings_woopf_pa_'.$tax->attribute_name.'_end'
							),

						);
					}
				}
			}
			else if ( isset($_GET['section']) && $_GET['section'] == 'overrides' ) {
				$settings = array();
				if ( $action == 'get' ) {
					$curr_or_settings = get_option( 'woopf_overrides', array() );
				?>	<h3><?php esc_html_e( 'Product Filter Shop Archives Override', 'woopf' ); ?></h3>
					<p><?php esc_html_e( 'Override archive filters. Select the term you wish to override and the desired filter preset and click Add Override to enable the new filter preset on this archive page.', 'woopf' ); ?></p>
				<?php
					$curr_overrides = array(
						'product_cat' => array( 'text' => esc_html__( 'Product Categories Overrides', 'woopf' ), 'values' => $curr_cats ),
						'product_tag' => array( 'text' => esc_html__( 'Product Tags Overrides', 'woopf' ), 'values' => $curr_tags ),
						'characteristics' => array( 'text' => esc_html__( 'Product Characteristics Overrides', 'woopf' ), 'values' => $curr_chars )
					);
					foreach ( $curr_overrides as $n => $m ) {
						if ( empty($m['values']) ) { continue; }
				  ?>	<h3><?php echo esc_html($m['text']); ?></h3>
						<p class="<?php echo esc_attr($n); ?>"><?php
							if ( isset($curr_or_settings[$n]) ) {
								foreach ( $curr_or_settings[$n] as $k => $v ) {
							?><span class="woopf_override"><input type="checkbox" class="pf_override_checkbox" /> <?php echo esc_html__('Term slug', 'woopf') . ' : <span class="slug">' . $k . '</span>'; ?> <?php echo esc_html__('Filter Preset', 'woopf') . ' : <span class="preset">' . $v; ?></span> <a href="#" class="button woopf_or_remove"><?php esc_html_e('Remove Override', 'woopf'); ?></a><span class="clearfix"></span></span>
							<?php
								}
							}
						?>	<span class="woopf_override_controls">
								<a href="#" class="button woopf_or_remove_selected"><?php esc_html_e('Remove Selected Overrides', 'woopf'); ?></a> <a href="#" class="button woopf_or_remove_all"><?php esc_html_e('Remove All Overrides', 'woopf'); ?></a>
							</span>
							<select class="woopf_or_select"><?php
							foreach ( $m['values'] as $k => $v ) {
								printf( '<option value="%1$s">%2$s</option>', $k, $v );
							}
						?></select>
							<select class="woopf_filter_presets">
								<option value="default"><?php esc_html_e('Default', 'wcwar'); ?></option>
								<?php
									$curr_presets = get_option('woopf_templates');
									if ( $curr_presets === false ) {
										$curr_presets = array();
									}
									if ( !empty($curr_presets) ) {
										foreach ( $curr_presets as $k => $v ) {
									?><option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($k); ?></option><?php
										}
									}
								?></select>
							<a href="#" class="button-primary woopf_or_add"><?php esc_html_e( 'Add Override', 'woopf' ); ?></a>
						</p><?php
					}
				}
			}
			return apply_filters( 'wc_settings_products_filter_settings', $settings );
		}

		/**
		 * AJAX Save Preset
		 * @since    1.0.0
		 */
		public static function woopf_admin_save() {
			$curr_name = $_POST['curr_name'];
			$curr_data = array();
			$curr_data[$curr_name] = $_POST['curr_settings'];
			$curr_presets = get_option('woopf_templates');

			if ( $curr_presets === false ) {
				$curr_presets = array();
			}

			if ( isset($curr_presets) && is_array($curr_presets) ) {
				if ( array_key_exists($curr_name, $curr_presets) ) { unset($curr_presets[$curr_name]); }

				$curr_presets = $curr_presets + $curr_data;
				update_option('woopf_templates', $curr_presets);

				die('1');
				exit;
			}
			die();
			exit;
		}

		/**
		 * AJAX Load Preset
		 * @since    1.0.0
		 */
		public static function woopf_admin_load() {
			$curr_name = $_POST['curr_name'];
			$curr_presets = get_option('woopf_templates');
			if ( isset($curr_presets) && !empty($curr_presets) && is_array($curr_presets) ) {
				if ( array_key_exists($curr_name, $curr_presets) ) {
					die(stripslashes($curr_presets[$curr_name]));
					exit;
				}
				die('1');
				exit;
			}
			die();
			exit;
		}

		/**
		 * AJAX Delete Preset
		 * @since    1.0.0
		 */
		public static function woopf_admin_delete() {
			$curr_name = $_POST['curr_name'];
			$curr_presets = get_option('woopf_templates');
			if ( isset($curr_presets) && !empty($curr_presets) && is_array($curr_presets) ) {
				if ( array_key_exists($curr_name, $curr_presets) ) {
					unset($curr_presets[$curr_name]);
					update_option('woopf_templates', $curr_presets);
				}
				die('1');
				exit;
			}
			die();
			exit;
		}

		/**
		 * AJAX Override Add
		 * @since    1.0.0
		 */
		public static function woopf_or_add() {
			$curr_tax = $_POST['curr_tax'];
			$curr_term = $_POST['curr_term'];
			$curr_override = $_POST['curr_override'];
			$curr_overrides = get_option('woopf_overrides');

			if ( $curr_overrides === false ) {
				$curr_overrides = array();
			}

			$curr_data = array(
				$curr_tax => array( $curr_term => $curr_override )
			);

			if ( isset($curr_overrides) && is_array($curr_overrides) ) {
				if ( isset($curr_overrides[$curr_tax]) && isset($curr_overrides[$curr_tax][$curr_term])) {
					unset($curr_overrides[$curr_tax][$curr_term]);
				}
				$curr_overrides = array_merge_recursive($curr_overrides, $curr_data);
				update_option('woopf_overrides', $curr_overrides);
				die('1');
				exit;
			}
			die();
			exit;
		}

		/**
		 * AJAX Override Remove
		 * @since    1.0.0
		 */
		public static function woopf_or_remove() {
			$curr_tax = $_POST['curr_tax'];
			$curr_term = $_POST['curr_term'];
			$curr_overrides = get_option('woopf_overrides');

			if ( $curr_overrides === false ) { $curr_overrides = array(); }
			if ( isset($curr_overrides) && is_array($curr_overrides) ) {
				if ( isset($curr_overrides[$curr_tax]) && isset($curr_overrides[$curr_tax][$curr_term])) {
					unset($curr_overrides[$curr_tax][$curr_term]);
					update_option('woopf_overrides', $curr_overrides);
					die('1');
					exit;
				}
			}
			die();
			exit;
		}

		/**
		 * AJAX Advanced Taxonomies
		 * @since    1.0.0
		 */
		public static function woopf_c_fields() {
			$taxonomies = get_object_taxonomies( 'product', 'object' );
			$pf_id = ( isset( $_POST['pf_id'] ) ? $_POST['pf_id'] : 0 );

			$html = '';
			$html .= sprintf( '<label><span>%2$s</span> <input type="text" name="pfa_title[%3$s]" value="%1$s" /></label>', ( isset($_POST['pfa_title']) ? $_POST['pfa_title'] : '' ), esc_html__( 'Override title', 'woopf' ), $pf_id );
			$html .= '<label><span>' . esc_html__( 'Select taxonomy','woopf' ) . '</span> <select class="woopf_adv_select" name="pfa_taxonomy[' . $pf_id . ']">';

			$i=0;

			foreach ( $taxonomies as $k => $v ) {
				if ( $k == 'product_type' ) { continue; }
				$selected = ( isset($_POST['pfa_taxonomy']) && $_POST['pfa_taxonomy'] == $k ? ' selected="selected"' : '' ) ;
				$html .= '<option value="' . $k . '"' . $selected . '>' . $v->label . '</option>';
				if ( !isset($_POST['pfa_taxonomy']) && $i==0 ) { $curr_fix = $k; } $i++;
			}
			if ( isset($_POST['pfa_taxonomy']) ) {
				$curr_fix = $_POST['pfa_taxonomy'];
			}

			$html .= '</select></label>';

			$catalog_attrs = get_terms( $curr_fix );
			$curr_options = '';
			if ( !empty( $catalog_attrs ) && !is_wp_error( $catalog_attrs ) ){
				foreach ( $catalog_attrs as $term ) {
					$selected = ( isset($_POST['pfa_include']) && is_array($_POST['pfa_include']) && in_array($term->slug, $_POST['pfa_include']) ? ' selected="selected"' : '' ) ;
					$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $term->slug, $term->name, $selected );
				}
			}

			$html .= sprintf( '<label><span>%2$s</span> <select name="pfa_include[%3$s][]" multiple="multiple">%1$s</select></label>', $curr_options, esc_html__( 'Include terms', 'woopf' ), $pf_id );

			$curr_options = '';
			$orderby_params = array(
				'' => esc_html__( 'None', 'woopf' ),
				'id' => esc_html__( 'ID', 'woopf' ),
				'name' => esc_html__( 'Name', 'woopf' ),
				'number' => esc_html__( 'Number', 'woopf' ),
				'slug' => esc_html__( 'Slug', 'woopf' ),
				'count' => esc_html__( 'Count', 'woopf' )
			);
			foreach ( $orderby_params as $k => $v ) {
				$selected = ( isset($_POST['pfa_orderby']) && $_POST['pfa_orderby'] == $k ? ' selected="selected"' : '' );
				$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
			}
			$html .= sprintf( '<label><span>%3$s</span> <select name="pfa_orderby[%2$s]">%1$s</select></label>', $curr_options, $pf_id, esc_html__( 'Term order by', 'woopf' ) );

			$curr_options = '';
			$order_params = array(
				'ASC' => esc_html__( 'ASC', 'woopf' ),
				'DESC' => esc_html__( 'DESC', 'woopf' )
			);
			foreach ( $order_params as $k => $v ) {
				$selected = ( isset($pf_filters_advanced['pfa_order'][$pf_id]) && $pf_filters_advanced['pfa_order'][$pf_id] == $k ? ' selected="selected"' : '' );
				$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
			}

			$html .= sprintf( '<label><span>%3$s</span> <select name="pfa_order[%2$s]"%4$s>%1$s</select></label>', $curr_options, $pf_id, esc_html__( 'Term order', 'woopf' ), $add_disable );

			$selected = ( isset($_POST['pfa_multiselect']) && $_POST['pfa_multiselect'] == 'yes' ? ' checked="checked"' : '' ) ;
			$html .= sprintf( '<label><input type="checkbox" name="pfa_multiselect[%3$s]" value="yes"%1$s /> %2$s</label>', $selected, esc_html__( 'Use multi select', 'woopf' ), $pf_id );

			$curr_options = '';
			$relation_params = array(
				'IN' => esc_html__( 'Filtered products have at least one term (IN)', 'woopf' ),
				'AND' => esc_html__( 'Filtered products have selected terms (AND)', 'woopf' )
			);
			foreach ( $relation_params as $k => $v ) {
				$selected = ( isset($_POST['pfa_relation']) && $_POST['pfa_relation'] == $k ? ' selected="selected"' : '' );
				$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
			}
			$html .= sprintf( '<label><span>%3$s</span> <select name="pfa_relation[%2$s]">%1$s</select></label>', $curr_options, $i, esc_html__( 'Term relation', 'woopf' ) );

			$selected = ( isset($_POST['pfa_adoptive']) && $_POST['pfa_adoptive'] == 'yes' ? ' checked="checked"' : '' ) ;
			$html .= sprintf( '<label><input type="checkbox" name="pfa_adoptive[%3$s]" value="yes"%1$s /> %2$s</label>', $selected, esc_html__( 'Use adoptive filtering', 'woopf' ), $pf_id );

			$selected = ( isset($_POST['pfa_none']) && $_POST['pfa_none'] == 'yes' ? ' checked="checked"' : '' ) ;
			$html .= sprintf( '<label><input type="checkbox" name="pfa_none[%3$s]" value="yes"%1$s /> %2$s</label>', $selected, esc_html__( 'Hide none', 'woopf' ), $pf_id );

			die($pf_id . '%SPLIT%' . $html);
			exit;
		}

		/**
		 * AJAX Advanced Terms
		 * @since    1.0.0
		 */
		public static function woopf_c_terms() {
			$curr_tax = ( isset($_POST['taxonomy']) ? $_POST['taxonomy'] : '' );
			if ( $curr_tax == '' ) {
				die();
				exit;
			}

			$html = '';

			$catalog_attrs = get_terms( $curr_tax );
			$curr_options = '';
			if ( !empty( $catalog_attrs ) && !is_wp_error( $catalog_attrs ) ){
				foreach ( $catalog_attrs as $term ) {
					$curr_options .= sprintf( '<option value="%1$s">%2$s</option>', $term->slug, $term->name );
				}
			}
			$html .= sprintf( '<label><span>%2$s</span> <select name="pfa_include[%%%%][]" multiple="multiple">%1$s</select></label>', $curr_options, esc_html__( 'Include terms', 'woopf' ) );
			die($html);
			exit;
		}

		/**
		 * AJAX Range Taxonomies
		 * @since    1.0.0
		 */
		public static function woopf_r_fields() {
			//$taxonomies = wc_get_attribute_taxonomies();
			global $wpdb;
			$get_results = 'get_results';
			$taxonomies = $wpdb->{$get_results}( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies" );
				
			$pf_id = ( isset( $_POST['pf_id'] ) ? $_POST['pf_id'] : 0 );

			$html = '';
			$html .= sprintf( '<label><span>%2$s</span> <input type="text" name="pfr_title[%3$s]" value="%1$s" /></label>', ( isset($_POST['pfr_title']) ? $_POST['pfr_title'] : '' ), esc_html__( 'Override title', 'woopf' ), $pf_id );
			$html .= '<label><span>' . esc_html__( 'Select attribute','woopf' ) . '</span> <select class="woopf_rng_select" name="pfr_taxonomy[' . $pf_id . ']">';
			$html .= '<option value="price"' . ( isset($_POST['pfr_taxonomy']) && $_POST['pfr_taxonomy'] == 'price' ? ' selected="selected"' : '' ) . '>' . esc_html__( 'Price range', 'woopf' ) . '</option>';

			foreach ( $taxonomies as $k => $v ) {
				$selected = ( isset($_POST['pfr_taxonomy']) && $_POST['pfr_taxonomy'] == 'pa_' . $v->attribute_name ? ' selected="selected"' : '' ) ;
				$curr_label = !empty( $v->attribute_label ) ? $v->attribute_label : $v->attribute_name;
				$html .= '<option value="pa_' . $v->attribute_name . '"' . $selected . '>' . $curr_label . '</option>';
			}
			if ( isset($_POST['pfr_taxonomy']) ) { $curr_fix = $_POST['pfr_taxonomy']; }
			else { $curr_fix = 'price'; }

			$html .= '</select></label>';

			if ( $curr_fix == 'price' ) {
				$html .= sprintf( '<label><span>%2$s</span> <select name="pfr_include[%3$s][]" multiple="multiple" disabled>%1$s</select></label>', array(), esc_html__( 'Include terms', 'woopf' ), $pf_id );
				$add_disable = ' disabled';
			}
			else {
				$catalog_attrs = get_terms( $curr_fix );
				$curr_options = '';

				if ( !empty( $catalog_attrs ) && !is_wp_error( $catalog_attrs ) ){
					foreach ( $catalog_attrs as $term ) {
						$selected = ( isset($_POST['pfr_include']) && is_array($_POST['pfr_include']) && in_array($term->slug, $_POST['pfr_include']) ? ' selected="selected"' : '' ) ;
						$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $term->slug, $term->name, $selected );
					}
				}

				$html .= sprintf( '<label><span>%2$s</span> <select name="pfr_include[%3$s][]" multiple="multiple">%1$s</select></label>', $curr_options, esc_html__( 'Include terms', 'woopf' ), $pf_id );
				$add_disable = '';
			}

			$curr_options = '';
			$orderby_params = array(
				'' => esc_html__( 'None', 'woopf' ),
				'id' => esc_html__( 'ID', 'woopf' ),
				'name' => esc_html__( 'Name', 'woopf' ),
				'number' => esc_html__( 'Number', 'woopf' ),
				'slug' => esc_html__( 'Slug', 'woopf' ),
				'count' => esc_html__( 'Count', 'woopf' )
			);
			foreach ( $orderby_params as $k => $v ) {
				$selected = ( isset($_POST['pfr_orderby']) && $_POST['pfr_orderby'] == $k ? ' selected="selected"' : '' );
				$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
			}
			$html .= sprintf( '<label><span>%3$s</span> <select name="pfr_orderby[%2$s]"%4$s>%1$s</select></label>', $curr_options, $pf_id, esc_html__( 'Term order by', 'woopf' ), $add_disable );

			$curr_options = '';
			$order_params = array(
				'ASC' => esc_html__( 'ASC', 'woopf' ),
				'DESC' => esc_html__( 'DESC', 'woopf' )
			);
			foreach ( $order_params as $k => $v ) {
				$selected = ( isset($pf_filters_advanced['pfr_order'][$pf_id]) && $pf_filters_advanced['pfr_order'][$pf_id] == $k ? ' selected="selected"' : '' );
				$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
			}

			$html .= sprintf( '<label><span>%3$s</span> <select name="pfr_order[%2$s]"%4$s>%1$s</select></label>', $curr_options, $pf_id, esc_html__( 'Term order', 'woopf' ), $add_disable );

			$catalog_style = array( 'flat' => esc_html__( 'Flat', 'woopf' ), 'modern' => esc_html__( 'Modern', 'woopf' ), 'html5' => esc_html__( 'HTML5', 'woopf' ), 'white' => esc_html__( 'White', 'woopf' ) );
			$curr_options = '';
			foreach ( $catalog_style as $k => $v ) {
				$selected = ( isset($_POST['pfr_style']) && $_POST['pfr_style'] == $k ? ' selected="selected"' : '' ) ;
				$curr_options .= sprintf( '<option value="%1$s"%3$s>%2$s</option>', $k, $v, $selected );
			}

			$html .= sprintf( '<label><span>%2$s</span> <select name="pfr_style[%3$s]">%1$s</select></label>', $curr_options, esc_html__( 'Select style', 'woopf' ), $pf_id );

			$selected = ( isset($_POST['pfr_grid']) && $_POST['pfr_grid'] == 'yes' ? ' checked="checked"' : '' ) ;
			$html .= sprintf( '<label><input type="checkbox" name="pfr_grid[%3$s]" value="yes"%1$s /> %2$s</label>', $selected, esc_html__( 'Use grid', 'woopf' ), $pf_id );

			die($pf_id . '%SPLIT%' . $html);
			exit;
		}

		/**
		 * AJAX Range Terms
		 * @since    1.0.0
		 */
		public static function woopf_r_terms() {
			$curr_tax = ( isset($_POST['taxonomy']) ? $_POST['taxonomy'] : '' );
			if ( $curr_tax == '' ) {
				die();
				exit;
			}

			$html = '';

			if ( !in_array( $curr_tax, array( 'price' ) ) ) {
				$catalog_attrs = get_terms( $curr_tax );
				$curr_options = '';
				if ( !empty( $catalog_attrs ) && !is_wp_error( $catalog_attrs ) ){
					foreach ( $catalog_attrs as $term ) {
						$curr_options .= sprintf( '<option value="%1$s">%2$s</option>', $term->slug, $term->name );
					}
				}
				$html .= sprintf( '<label><span>%2$s</span> <select name="pfr_include[%%%%][]" multiple="multiple">%1$s</select></label>', $curr_options, esc_html__( 'Include terms', 'woopf' ) );
			}
			else {
				$html .= sprintf( '<label><span>%1$s</span> <select name="pfr_include[%%%%][]" multiple="multiple" disabled></select></label>', esc_html__( 'Include terms', 'woopf' ) );
			}
			die($html);
			exit;
		}
	}
	add_action( 'init', 'WC_Settings_Woopf::init');
?>
