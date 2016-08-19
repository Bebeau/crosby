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
		wp_register_script('custom', get_bloginfo( 'template_url' ) . '/assets/js/custom.js', array('jquery'), null, true);
        wp_localize_script('custom', 'meta_image',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' )
            )
        );
        wp_enqueue_script( 'custom' );
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
	// remove_menu_page( 'plugins.php' );
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

// add artist filter to media listing
add_filter('parse_query', 'node_admin_posts_filter');
add_action('restrict_manage_posts', 'filter_by_artist');
function node_admin_posts_filter($wp_query) {
    if (is_admin() && isset($_GET['post_id']) && $_GET['post_id'] != '') {
        $original_query = $wp_query;
        $wp_query->set('post_parent', $_GET['post_id']);
        $wp_query = $original_query;
        wp_reset_postdata();
    }
}
function filter_by_artist() {
    global $wpdb;
    $get_posts = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE $wpdb->posts.post_type IN ('portfolios') ORDER BY $wpdb->posts.post_title ASC");
    echo '<select name="post_id">';
    echo '<option value="">All Artists</option>';
    $current   = isset($_GET['post_id']) ? $_GET['post_id'] : '';
    foreach($get_posts as $get_post) {
        $select = null;
        if($current == $get_post->ID) { $select = ' selected="selected"'; }
        echo '<option value="' . $get_post->ID . '" ' . $select . '>' . $get_post->post_title . '</option>';
    }
}

// create custom plugin settings menu
add_action('admin_menu', 'add_project_ordering');
function add_project_ordering() {
    add_submenu_page(
        'themes.php',
        'Background',
        'Background',
        'manage_options',
        'theme-setup',
        'theme_background_page' 
    );
}
function theme_background_page() {

    save_custom_theme_options();

    settings_fields( 'theme_setup_options' );
    do_settings_sections( 'theme_setup_options' );

    $background = get_option('custom_bg');
    $video = get_option('videos');

    echo '<div class="wrap">';

        echo "<h1>Background</h1>";

        echo '<section id="bgUpload" data-input="custom_bg">';
            echo '<h2>Set Background Image</h2>';
            echo '<p>Suggested image size is 2880px by 1800px</p>';
            echo '<div class="image-placeholder background">';
                echo '<img src="'.$background.'" alt="" />';
            echo '</div>';
            if ( !empty($background) ) {
                echo '<button class="remove-background button button-large">Remove</button>';
            } else {
                echo '<button class="add button button-large upload-background" id="upload-bg" style="text-align:center;" data-input="custom_bg">Upload/Set Background</button>';
            }
            echo '<input type="hidden" name="custom_bg" id="custom_bg" value="'.$background.'" />';
        echo '</section>';

    echo '</div>';
}
function custom_user_can_save( $action, $nonce ) {
    $is_nonce_set   = isset( $_POST[ $nonce ] );
    $is_valid_nonce = false;
    if ( $is_nonce_set ) {
        $is_valid_nonce = wp_verify_nonce( $_POST[ $nonce ], $action );
    }
    return ( $is_nonce_set && $is_valid_nonce );
}
function save_custom_theme_options() {

    $action = 'theme_setup_options_save';
    $nonce  = 'theme_setup_options_save_nonce';

    if ( !custom_user_can_save( $action, $nonce ) ) {
        return;
    }

    if (isset($_POST["update_settings"])) {

        if ( isset( $_POST['custom_bg'] ) ) {
            update_option('custom_bg', $_POST['custom_bg']);
        }

        echo '<div id="message" class="updated">Settings saved</div>';

    }
}
// ajax response to save background image
add_action('wp_ajax_setBackgroundImage', 'setBackground');
add_action('wp_ajax_nopriv_setBackgroundImage', 'setBackground');
function setBackground() {

    $imageField = (isset($_GET['imageField'])) ? $_GET['imageField'] : 0;
    $imageURL = (isset($_GET['fieldVal'])) ? $_GET['fieldVal'] : 0;

    var_dump($imageURL);

    if($imageURL !== "") {
        update_option( 'custom_bg', $imageURL);
    } else {
        update_option( 'custom_bg', "");
    }
}

// Create social bookmark input fields in general settings
add_action('admin_init', 'my_general_section');  
function my_general_section() {  
    add_settings_section(  
        'my_settings_section', // Section ID 
        'Social Media', // Section Title
        'my_section_options_callback', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );
    add_settings_field( // Option 1
        'facebook', // Option ID
        'Facebook URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'my_settings_section', // Name of our section (General Settings)
        array( // The $args
            'facebook' // Should match Option ID
        )  
    );
    add_settings_field( // Option 2
        'twitter', // Option ID
        'Twitter URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'my_settings_section', // Name of our section (General Settings)
        array( // The $args
            'twitter' // Should match Option ID
        )  
    );
    add_settings_field( // Option 2
        'instagram', // Option ID
        'Instagram URL', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed
        'my_settings_section', // Name of our section (General Settings)
        array( // The $args
            'instagram' // Should match Option ID
        )  
    );
    register_setting('general','facebook', 'esc_attr');
    register_setting('general','twitter', 'esc_attr');
    register_setting('general','instagram', 'esc_attr');
}

function my_section_options_callback() { // Section Callback
    echo '<p>Enter your social media links to have them automatically display in the website footer.</p>';  
}

function my_textbox_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="text" class="regular-text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
}

function add_custom_rewrite_rule() {

    // First, try to load up the rewrite rules. We do this just in case
    // the default permalink structure is being used.
    if( ($current_rules = get_option('rewrite_rules')) ) {

        // Next, iterate through each custom rule adding a new rule
        // that replaces 'movies' with 'films' and give it a higher
        // priority than the existing rule.
        foreach($current_rules as $key => $val) {
            if(strpos($key, 'portfolios') !== false) {
                add_rewrite_rule(str_ireplace('portfolios', 'portfolio', $key), $val, 'top');   
            } // end if
        } // end foreach

    } // end if/else

    // ...and we flush the rules
    flush_rewrite_rules();

} // end add_custom_rewrite_rule
add_action('init', 'add_custom_rewrite_rule');
?>