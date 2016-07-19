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
        'query_var' => true,
        'rewrite' => array( 'slug' => 'image-type' ),
        'show_admin_column' => true,
        'show_ui' => true
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

add_action( 'admin_footer-post-new.php', 'portfolio_specific_images' );
add_action( 'admin_footer-post.php', 'portfolio_specific_images' );
function portfolio_specific_images() { ?>
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
    
    $c = 0;
    echo '<h3>Upload YouTube videos</h3>';
    echo '<p>Copy &amp; paste <a href="https://youtube.com/" alt="YouTube" target="_BLANK">YouTube</a> Video Links below and save/update to assign commercials to this portfolio.</p>';
    echo '<section id="Commercials">';
        echo '<article class="link"> <input type="text" name="commercials" value="" placeholder="https://www.youtube.com/watch?v=VIDEOID"/></article>';
    echo '</section>'; ?>
    <button type="submit" class="button button-primary button-large">+ Add Video</button>
    <?php
        if ( !empty($commercials) ) {
            echo '<h3>Videos</h3>';
            echo '<p>Below is a listing of commercials being displayed on the portfolio page. Simply click the "x" to remove any video.</p>';
            echo '<section class="videoWrap">';
                $c = 0;
                foreach( $commercials as $commercial ) { ?>
                    <div class="video" data-post="<?php echo $post->ID; ?>" data-key="<?php echo $c; ?>" data-type="commercials">
                        <a href="https://www.youtube.com/watch?v=<?php echo $commercial; ?>" target="_BLANK">
                            <img src="https://i1.ytimg.com/vi/<?php echo $commercial; ?>/hqdefault.jpg" alt="" />
                        </a>
                        <span class="button button-remove">X</span>
                    </div>
                <?php $c++; }
            echo '</section>';
        }
    }
function music_videos() { 
    global $post;
    // Use nonce for verification
    wp_nonce_field( 'music_videos', 'music_videos_noncename' );

    //get the saved meta as an arry
    $music_videos = get_post_meta($post->ID,'music_videos', true);

    $c = 0;
    echo '<h3>Upload YouTube videos</h3>';
    echo '<p>Copy &amp; paste <a href="https://youtube.com/" alt="YouTube" target="_BLANK">YouTube</a> Video Links below and save/update to assign music videos to this portfolio.</p>';
    echo '<section id="MusicVideos">';
        echo '<article class="link"> <input type="text" name="music_videos" value="" placeholder="https://www.youtube.com/watch?v=VIDEOID"/></article>';
    echo '</section>'; ?>
    <button type="submit" class="button button-primary button-large">+ Add Video</button>
    <?php
        if ( !empty($music_videos) ) {
            echo '<h3>Videos</h3>';
            echo '<p>Below is a listing of music videos being displayed on the portfolio page. Simply click the "x" to remove any video.</p>';
            echo '<section class="videoWrap">';
                $c = 0;
                foreach( $music_videos as $video ) { ?>
                    <div class="video" data-post="<?php echo $post->ID; ?>" data-key="<?php echo $c; ?>" data-type="music_videos">
                        <a href="https://www.youtube.com/watch?v=<?php echo $video; ?>" target="_BLANK">
                            <img src="https://i1.ytimg.com/vi/<?php echo $video; ?>/hqdefault.jpg" alt="" />
                        </a>
                        <span class="button button-remove">X</span>
                    </div>
                <?php $c++; }
            echo '</section>';
        }
    }
/* When the post is saved, saves our custom data */
add_action( 'save_post', 'dynamic_save_postdata' );
function dynamic_save_postdata( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    if ( !isset( $_POST['commercials_noncename'] ) || !wp_verify_nonce( $_POST['commercials_noncename'], 'commercial_links' ) )
        return;

    // save commercials
    if(!empty($_POST['commercials'])) {
        // add current video ID's to array
        $old  = get_post_meta($post_id,'commercials', true);
        $new  = $_POST['commercials'];
        if($old && !empty($new)) {
            $old[] = getYoutubeIdFromUrl($new);
            $result1 = $old;
        } elseif($new) {
            $commercials[] = getYoutubeIdFromUrl($new);
            $result1 = $commercials;
        }
        update_post_meta($post_id,'commercials',$result1);
    }

    if ( !isset( $_POST['music_videos_noncename'] ) || !wp_verify_nonce( $_POST['music_videos_noncename'], 'music_videos' ) )
        return;

    // save music videos
    if(!empty($_POST['music_videos'])) {
        // add current video ID's to array
        $oldVids  = get_post_meta($post_id,'music_videos', true);
        $newVid  = $_POST['music_videos'];
        if($oldVids) {
            $oldVids[] = getYoutubeIdFromUrl($newVid);
            $result2 = $oldVids;
        } elseif($newVid) {
            $music_videos[] = getYoutubeIdFromUrl($newVid);
            $result2 = $music_videos;
        }
        update_post_meta($post_id,'music_videos',$result2);
    }
}
function getYoutubeIdFromUrl($url) {
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
        }else if(isset($qs['vi'])){
            return $qs['vi'];
        }
    }
    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }
    return false;
}
// ajax response to save download track
add_action('wp_ajax_removeVideo', 'removeYouTubeVideo');
add_action('wp_ajax_nopriv_removeVideo', 'removeYouTubeVideo');
function removeYouTubeVideo() {
    $postID = (isset($_GET['postID'])) ? $_GET['postID'] : 0;
    $key = (isset($_GET['key'])) ? $_GET['key'] : 0;
    $video_type = (isset($_GET['video_type'])) ? $_GET['video_type'] : 0;

    $videos = get_post_meta($postID, $video_type, true );
    unset($videos[$key]);

    update_post_meta($postID, $video_type, $videos);
}

function has_Images($cat) {
    global $post;
    $args = array(
        'post_parent' => $post->ID,
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png',
        'tax_query' => array(
            array(
                'taxonomy' => 'image_type',
                'field' => 'slug',
                'terms' => $cat
            )
        )
    );
    $query = new WP_Query($args);
    return $query->have_posts();
}

function list_Images($cat) {
    global $post;
    $args = array(
        'post_parent' => $post->ID,
        'post_type' => 'attachment',
        'posts_per_page'    => -1,
        'post_status' => 'inherit',
        'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png',
        'tax_query' => array(
            array(
                'taxonomy' => 'image_type',
                'field' => 'slug',
                'terms' => $cat
            )
        )
    );
    $query = new WP_Query($args);
    if($query->have_posts()) {
        echo '<div role="tabpanel" class="tab-pane fade" id="'.$cat.'">';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<a href="#photomodal" data-toggle="modal" class="singlephoto" data-photo="'.$post->guid.'">';
                echo '<img src="'.$post->guid.'" alt="" />';
                echo '<div class="playwrap"><i class="fa fa-plus"></i></div>';
            echo '</a>';
        }
        echo '</div>';
    }
    wp_reset_query();
}

?>