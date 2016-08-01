<?php

// Hide admin bar
add_filter('show_admin_bar', '__return_false');

// Load all styles and scripts for the site
if (!function_exists( 'load_custom_scripts' ) ) {
	function load_custom_scripts() {
		// Styles
		wp_enqueue_style( 'Bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', false, '', 'all' );
		wp_enqueue_style( 'Style CSS', get_bloginfo( 'template_url' ) . '/style.css', false, '', 'all' );

		// Load default Wordpress jQuery
		wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', ( "//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" ), false, '1.11.3' );
        wp_enqueue_script( 'jquery' );

		// Load custom scripts
		wp_enqueue_script('bootstrap_js', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', array('jquery'), null, true);
		wp_enqueue_script('custom', get_bloginfo( 'template_url' ) . '/assets/js/custom.js', array('jquery'), null, true);

	}
}
add_action( 'wp_print_styles', 'load_custom_scripts' );

// Thumbnail Support
add_theme_support( 'post-thumbnails', array('page','portfolios') );

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
add_action( 'after_setup_theme', 'register_my_menu' );
function register_my_menu() {
  register_nav_menu( 'primary', 'Main Menu' );
}

// remove WordPress admin menu items
function remove_menus(){
	remove_menu_page( 'edit.php' );
	// remove_menu_page( 'edit.php?post_type=page' );
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'tools.php' );
	// remove_menu_page( 'themes.php' );
	remove_menu_page( 'plugins.php' );
	// remove_menu_page( 'users.php' );
	// remove_menu_page( 'upload.php' );
}
add_action( 'admin_menu', 'remove_menus' );

include(TEMPLATEPATH.'/partials/functions/portfolios.php');
include(TEMPLATEPATH.'/partials/functions/agents.php');

add_filter('admin_init', 'my_general_settings_register_fields');
 
function my_general_settings_register_fields() {
    register_setting(
    	'general', 
    	'crosby_address', 
    	'esc_attr'
	);
    add_settings_field(
    	'crosby_address', 
    	'<label for="crosby_address">'.__('Address' , 'crosby_address' ).'</label>' , 
    	'crosby_address', 
    	'general'
	);
	register_setting(
    	'general', 
    	'crosby_address2', 
    	'esc_attr'
	);
    add_settings_field(
    	'crosby_address2', 
    	'<label for="crosby_address2">'.__('City/State/Zip' , 'crosby_address2' ).'</label>' , 
    	'crosby_address2', 
    	'general'
	);
	register_setting(
    	'general', 
    	'crosby_phone', 
    	'esc_attr'
	);
    add_settings_field(
    	'crosby_phone', 
    	'<label for="crosby_phone">'.__('Phone' , 'crosby_phone' ).'</label>' , 
    	'crosby_phone', 
    	'general'
	);
	register_setting(
    	'general', 
    	'crosby_fax', 
    	'esc_attr'
	);
    add_settings_field(
    	'crosby_fax', 
    	'<label for="crosby_fax">'.__('Fax' , 'crosby_fax' ).'</label>' , 
    	'crosby_fax', 
    	'general'
	);
}
 
function crosby_address() {
    $address = get_option( 'crosby_address', '' );
    echo '<input type="text" class="regular-text ltr" id="crosby_address" name="crosby_address" value="' . $address . '" placeholder="address" />';
}
function crosby_address2() {
    $address2 = get_option( 'crosby_address2', '' );
    echo '<input type="text" class="regular-text ltr" id="crosby_address2" name="crosby_address2" value="' . $address2 . '" placeholder="city/state/zip" />';
}
function crosby_phone() {
    $phone = get_option( 'crosby_phone', '' );
    echo '<input type="text" id="crosby_phone" name="crosby_phone" value="' . $phone . '" placeholder="555-555-5555"/>';
}
function crosby_fax() {
    $fax = get_option( 'crosby_fax', '' );
    echo '<input type="text" id="crosby_fax" name="crosby_fax" value="' . $fax . '" placeholder="555-555-5555" />';
}