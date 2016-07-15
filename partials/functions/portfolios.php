<?php

// Add admin styles for portfolios
add_action( 'admin_enqueue_scripts', 'load_portfolios_admin' );
function load_portfolios_admin() {
    global $typenow;
    if( $typenow == 'portfolios' ) {
        wp_enqueue_style( 'recipe-styles', get_bloginfo( 'template_url' ) . '/assets/css/portfolios.css', false, '1.0.0' );
        wp_localize_script( 'my-ajax-request', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }
    // Registers and enqueues the required javascript.
    wp_register_script( 'meta-box-image-upload', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ) );
    wp_localize_script( 'meta-box-image-upload', 'meta_image',
        array(
            'title' => 'Choose or Upload Image',
            'button' => 'Select Image',
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        )
    );
    wp_enqueue_script( 'meta-box-image-upload' );
}

// Create custom post type for recipes
add_action( 'init', 'init_artistManagement' );
function init_artistManagement() {

    $artist_type_labels = array(
        'name' => _x('Portfolio', 'post type general name'),
        'singular_name' => _x('Portfolio', 'post type singular name'),
        'add_new' => _x('Add New Portfolio', 'video'),
        'add_new_item' => __('Add New Portfolio'),
        'edit_item' => __('Edit Portfolio'),
        'new_item' => __('Add New Portfolio'),
        'all_items' => __('View Portfolios'),
        'view_item' => __('View Portfolio'),
        'search_items' => __('Search Portfolios'),
        'not_found' =>  __('No Portfolios found'),
        'not_found_in_trash' => __('No Portfolios found in Trash'), 
        'parent_item_colon' => '',
        'menu_name' => 'Portfolios'
    );
    $artist_type_args = array(
        'labels' => $artist_type_labels,
        'public' => true,
        'publicly_queryable' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'Portfolios' ),
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true, 
        'hierarchical' => true,
        'map_meta_cap' => true,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('category')
    );
    register_post_type('Portfolios', $artist_type_args);
}

// add custom taxonomy for ingredients
function init_image_type() {
    $labels = array(
        'name' => _x( 'Image Type', 'taxonomy general name' ),
        'singular_name' => _x( 'Image Type', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Image Types' ),
        'all_items' => __( 'All Image Types' ),
        'parent_item' => __( 'Parent Image Type' ),
        'parent_item_colon' => __( 'Parent Image Type:' ),
        'edit_item' => __( 'Edit Image Type' ), 
        'update_item' => __( 'Update Image Type' ),
        'add_new_item' => __( 'Add New Image Type' ),
        'new_item_name' => __( 'New Image Type Name' ),
        'menu_name' => __( 'Image Types' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'query_var' => 'true',
        'rewrite' => array( 'slug' => 'image-type' ),
        'show_admin_column' => 'true',
    );
    register_taxonomy(
        'image_type',
        'attachment',
        $args
    );
}
add_action( 'init', 'init_image_type' );

// Thumbnail Support
add_theme_support( 'post-thumbnails', array('portfolios') );

add_action( 'admin_footer-post-new.php', 'artist_specific_images' );
add_action( 'admin_footer-post.php', 'artist_specific_images' );
function artist_specific_images() { ?>
  <script type="text/javascript">
    jQuery(document).on("DOMNodeInserted", function(){
        // Lock uploads to "Uploaded to this post"
        jQuery('select.attachment-filters [value="uploaded"]').attr( 'selected', true ).parent().trigger('change');
    });
  </script>
<?php }

// Add custom meta boxes to display recipe specs
add_action( 'add_meta_boxes', 'recipe_meta_box', 1 );
function recipe_meta_box( $post ) {
    add_meta_box(
        'commercials', 
        'Commercials', 
        'commercials',
        'portfolios', 
        'normal', 
        'low'
    );
    add_meta_box(
        'music_videos', 
        'Music Videos', 
        'music_videos',
        'portfolios', 
        'normal', 
        'low'
    );
}
function commercials() { 
    global $post;
    // Use nonce for verification
    wp_nonce_field( 'commercial_links', 'commercials_noncename' );

    //get the saved meta as an arry
    $commercials = get_post_meta($post->ID,'commercials', true);
    var_dump($commercials);

    $c = 0;
    echo '<p>Copy &amp; paste <a href="https://youtube.com/" alt="YouTube" target="_BLANK">YouTube</a> Video Links below and save/update to assign commercials to this portfolio.</p>';
    echo '<section id="Commercials">';
        if ( !is_array($commercials) ) {
            echo '<div class="single-instruction"><article class="link"> <input type="text" name="commercials['.$c.'][link]" value="" placeholder="https://www.youtube.com/watch?v=VIDEOID"/></article></div>';
        }
    echo '</section>'; ?>
    <span class="add_commercial button button-primary button-large">+ <?php _e('Add YouTube Video Link'); ?></span>
    <?php 
        if ( is_array($commercials) ) {
            foreach( $commercials as $commercial ) {
                printf( '
                    <div class="commercial">
                        <img src="http://img.youtube.com/vi/%2$s/hqdefault.jpg" alt="" />
                        <span class="button-remove"><i class="fa fa-close"></i></span>
                    </div>', 
                    $c, 
                    $commercial['link']
                );
                $c = $c + 1;
            }
        }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            removeInstructions = function() {
                jQuery(".button-remove").on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).parent().remove();
                });
            }
            var count = <?php echo $c; ?>;
            jQuery(".add_commercial").click(function(e) {
                count = count + 1;
                e.preventDefault();
                jQuery('#Commercials').append('<div class="single-instruction"><article class="link"> <input type="text" name="commercials['+count+'][link]" value="" placeholder="https://www.youtube.com/watch?v=VIDEOID"/></article></div>' );
                removeInstructions();
            });
            removeInstructions();
        });
    </script>
<?php }
function music_videos() { 
    echo "test";
}
/* When the post is saved, saves our custom data */
function dynamic_save_postdata( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    if ( !isset( $_POST['instructions_noncename'] ) || !wp_verify_nonce( $_POST['instructions_noncename'], plugin_basename( __FILE__ ) ) )
        return;

    if(isset($_POST['instructions'])) {
        $instructions = $_POST['instructions'];
        update_post_meta($post_id,'instructions',$instructions);
    }
}

// ajax response to save download track
add_action('wp_ajax_setVideo', 'setYouTubeVideo');
add_action('wp_ajax_nopriv_setVideo', 'setYouTubeVideo');
function setYouTubeVideo() {
    
    $videoID = (isset($_GET['videoID'])) ? $_GET['videoID'] : 0;

    $postID = (isset($_GET['userID'])) ? $_GET['userID'] : 0;
    
    if ( !current_user_can( 'edit_user', $userID ) ) { 
        return false; 
    }

    if(isset($videoID)) {
        update_post_meta($postID, 'heard_epk_featureVideoID', $videoID);
    }
}
// ajax response to display random winner
add_action('wp_ajax_removeVideo', 'removeVideo');
add_action('wp_ajax_nopriv_removeVideo', 'removeVideo');
function removeVideo() {
    $postID = (isset($_GET['postID'])) ? $_GET['postID'] : 0;
    update_post_meta( $postID, 'video_link', '' );
}

?>