<?php

// Register Bootstrap Navigation Walker
include '/class/wp_bootstrap_navwalker/wp_bootstrap_navwalker.php';
 
require TEMPLATEPATH.'/framework/theme.php';
$theme = new Theme(
	array(

	'menus' => array( 'nav' => 'Navigation'),
			
	'sidebar' => array( 
		'principal' => array(
			'name' => 'sidebar Principale',
			'id' => 'main',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' =>'</h3>'
		)
	)


 ));
 ?>