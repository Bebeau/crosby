<?php

// Hide admin bar
add_filter('show_admin_bar', '__return_false');

// Load all styles and scripts for the site
if (!function_exists( 'load_custom_scripts' ) ) {
	function load_custom_scripts() {
		// Styles
		wp_enqueue_style( 'Style CSS', get_bloginfo( 'template_url' ) . '/style.css', false, '', 'all' );

		// Load default Wordpress jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false, '', false);
		wp_enqueue_script('jquery');

		// Load custom scripts
		wp_enqueue_script('custom', get_bloginfo( 'template_url' ) . '/assets/js/custom.min.js', array('jquery'), null, true);

	}
}
add_action( 'wp_print_styles', 'load_custom_scripts' );

// Load widget areas
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'id'	=> 'sidebar',
		'before_widget' => '<div class="widgetWrap">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>',
	));
}

// Register Navigation Menu Areas
add_action( 'INiT', 'register_my_menus' );
function register_my_menu() {
  register_nav_menu( 'primary', 'Primary Menu' );
}

// remove WordPress admin menu items
function remove_menus(){
	// remove_menu_page( 'edit.php' );
	// remove_menu_page( 'edit.php?post_type=page' );
	// remove_menu_page( 'edit-comments.php' );
	// remove_menu_page( 'tools.php' );
	// remove_menu_page( 'themes.php' );
	// remove_menu_page( 'plugins.php' );
	// remove_menu_page( 'users.php' );
	// remove_menu_page( 'upload.php' );
}
add_action( 'admin_menu', 'remove_menus' );

include(TEMPLATEPATH.'/partials/functions/portfolios.php');