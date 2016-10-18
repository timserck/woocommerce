<?php 
/**
 * The widgets functionality of the plugin.
 *
 * @link       http://theemon.com/p/Woo-Products-filter/LivePreview
 * @since      1.0.0
 *
 * @package    woo-products-filter
 * @subpackage woo-products-filter/admin
 */

function woopfShopWidgets() {
	register_widget('woopfShopWidgets');
}
add_action('widgets_init', 'woopfShopWidgets');


class woopfShopWidgets extends WP_Widget
{
	/**
	 * Declares the wppdShopWidget class.
	 * @since    1.0.0
	 */
	function __construct(){
		$widget_ops = array('classname' => 'woopfShopWidgets', 'description' => esc_html__( "Woo products Filter Widget", 'woopf') );
		$control_ops = array('width' => 300, 'height' => 300);
		parent::__construct('woopfShopWidgets', esc_html__('woopf Filter Widget', 'woopf'), $widget_ops, $control_ops);
	}
	
	/**
	 * widget
	 * @since    1.0.0
	 */
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', !empty($instance['title']) ? $instance['title'] : '');
		$iconClass = apply_filters('widget_iconClass', !empty($instance['iconClass']) ? $instance['iconClass'] : '');
		if ( $title )
			print('<span Class="filter-products-heading">'.$title.' <i class="'.$iconClass.'"></i></span>');

		do_action('woopf_output');
		

	}

	/**
	 * Saves the widgets settings.
	 * @since    1.0.0
	 */
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['iconClass'] = strip_tags(stripslashes($new_instance['iconClass']));
		return $instance;
	}
	
	/**
	 * Creates the edit form for the widget.
	 * @since    1.0.0
	 */
	function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'','iconClass' => '') );
		$title = htmlspecialchars($instance['title']);
		$iconClass = htmlspecialchars($instance['iconClass']);
		# Output the options
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('title') . '">' . esc_html__('Title:', 'woopf') . ' <input style="width: 250px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>'.
		'<p><label for="' . $this->get_field_name('iconClass') . '">' . esc_html__('Icon Class:', 'woopf') . ' <input style="width: 250px;" id="' . $this->get_field_id('iconClass') . '" name="' . $this->get_field_name('iconClass') . '" type="text" value="' . $iconClass . '" /></label></p>';
		
	}
	
}
?>
