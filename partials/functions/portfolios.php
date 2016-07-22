<?php

add_role(
    'agent',
    __( 'Agent' ),
    array(
        'read'         => true,  // true allows this capability
        'edit_posts'   => true,
        'delete_posts' => false, // Use false to explicitly deny
        'upload_files' => true
    )
);
// remove default roles
remove_role( 'subscriber' );
remove_role( 'contributor' );
remove_role( 'author' );
remove_role( 'editor' );

// remove color scheme
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
if ( ! function_exists( 'rdm_remove_personal_options' ) ) {
    // removes the leftover 'Visual Editor', 'Keyboard Shortcuts' and 'Toolbar' options.
  function rdm_remove_personal_options( $subject ) {
    $subject = preg_replace( '#<h2>Personal Options</h2>.+?/table>#s', '', $subject, 1 );
    return $subject;
  }
  function rdm_profile_subject_start() {
    ob_start( 'rdm_remove_personal_options' );
  }
  function rdm_profile_subject_end() {
    ob_end_flush();
  }
}

// remove unneeded contact fields
add_filter('user_contactmethods','hide_contact_fields',10,1);
function hide_contact_fields( $contactmethods ) {
    unset($contactmethods['aim']);
    unset($contactmethods['jabber']);
    unset($contactmethods['yim']);
    return $contactmethods;
}
// add phone number field to contact info
add_filter('user_contactmethods','add_phone',10,1);
function add_phone( $contactmethods ) {
    $contactmethods['phone'] = 'Phone';
    return $contactmethods;
}
// hide website field
function remove_website_input() {
    echo '<style>tr.user-url-wrap{ display: none; }</style>';
}
add_action( 'admin_head-user-edit.php', 'remove_website_input' );
add_action( 'admin_head-profile.php',   'remove_website_input' );


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
    add_meta_box(
        'agent', 
        'Agent Contact', 
        'agent',
        'portfolios', 
        'side', 
        'high'
    );
}
function commercials() { 
    global $post;
    // Use nonce for verification
    wp_nonce_field( 'commercial_links', 'commercials_noncename' );

    //get the saved meta as an arry
    $commercials = get_post_meta($post->ID,'commercials', true);
    
    $c = 0;
    echo '<h3>Upload Commercials</h3>';
    echo '<p>Copy &amp; paste <a href="https://youtube.com/" alt="YouTube" target="_BLANK">YouTube</a> or <a href="https://vimeo.com/" alt="Vimeo" target="_BLANK">Vimeo</a> video links below and save/update to assign commercials to this portfolio.</p>';
    echo '<section id="Commercials">';
        echo '<article class="link"> <i class="youtube"></i><input type="text" name="yt_commercial" value="" placeholder="https://www.youtube.com/watch?v=VIDEOID"/></article>';
        echo '<article class="link"> <i class="vimeo"></i><input type="text" name="vimeo_commercial" value="" placeholder="https://www.vimeo.com/VIDEOID"/></article>';
    echo '</section>'; ?>
    <button type="submit" class="button button-primary button-large">+ Add Video</button>
    <?php if ( !empty($commercials) ) {
        echo '<h3>Videos</h3>';
        echo '<p>Below is a listing of commercials being displayed on the portfolio page. Simply click the "x" to remove any video.</p>';
        echo '<section class="videoWrap">';
            $c = 0;
            foreach( $commercials as $commercial ) {
                if($commercial['type'] === "youtube") {
             ?>
                <div class="video" data-post="<?php echo $post->ID; ?>" data-key="<?php echo $c; ?>" data-type="commercials">
                    <a href="https://www.youtube.com/watch?v=<?php echo $commercial['id']; ?>" target="_BLANK">
                        <img src="https://i1.ytimg.com/vi/<?php echo $commercial['id']; ?>/hqdefault.jpg" alt="" />
                    </a>
                    <span class="button button-remove">X</span>
                </div>
            <?php 
            $c++; 
            } elseif($commercial['type'] === "vimeo") {
                $imgid = $commercial['id'];
                $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
                $thumb = $hash[0]['thumbnail_large'];
            ?>
                <div class="video" data-post="<?php echo $post->ID; ?>" data-key="<?php echo $c; ?>" data-type="commercials">
                    <a href="https://www.vimeo.com/<?php echo $commercial['id']; ?>" target="_BLANK">
                        <img src="<?php echo $thumb; ?>" alt="" />
                    </a>
                    <span class="button button-remove">X</span>
                </div>
           <?php }
        }
        echo '</section>';
    }
}
function music_videos() { 
    global $post;
    // Use nonce for verification
    wp_nonce_field( 'music_videos', 'music_videos_noncename' );

    //get the saved meta as an arry
    $music_videos = get_post_meta($post->ID,'music_videos', true);

    var_dump($music_videos);
    
    $c = 0;
    echo '<h3>Upload Music Videos</h3>';
    echo '<p>Copy &amp; paste <a href="https://youtube.com/" alt="YouTube" target="_BLANK">YouTube</a> or <a href="https://vimeo.com/" alt="Vimeo" target="_BLANK">Vimeo</a> video links below and save/update to assign Music Videos to this portfolio.</p>';
    echo '<section id="MusicVideos">';
        echo '<article class="link"> <i class="youtube"></i><input type="text" name="yt_musicVideo" value="" placeholder="https://www.youtube.com/watch?v=VIDEOID"/></article>';
        echo '<article class="link"> <i class="vimeo"></i><input type="text" name="vimeo_musicVideo" value="" placeholder="https://www.vimeo.com/VIDEOID"/></article>';
    echo '</section>'; ?>
    <button type="submit" class="button button-primary button-large">+ Add Video</button>
    <?php if ( !empty($music_videos) ) {
        echo '<h3>Videos</h3>';
        echo '<p>Below is a listing of Music Videos being displayed on the portfolio page. Simply click the "x" to remove any video.</p>';
        echo '<section class="videoWrap">';
            $c = 0;
            foreach( $music_videos as $video ) {
                if($video['type'] === "youtube") {
             ?>
                <div class="video" data-post="<?php echo $post->ID; ?>" data-key="<?php echo $c; ?>" data-type="music_videos">
                    <a href="https://www.youtube.com/watch?v=<?php echo $video['id']; ?>" target="_BLANK">
                        <img src="https://i1.ytimg.com/vi/<?php echo $video['id']; ?>/hqdefault.jpg" alt="" />
                    </a>
                    <span class="button button-remove">X</span>
                </div>
            <?php 
            $c++; 
            } elseif($video['type'] === "vimeo") {
                $imgid = $video['id'];
                $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
                $thumb = $hash[0]['thumbnail_large'];
            ?>
                <div class="video" data-post="<?php echo $post->ID; ?>" data-key="<?php echo $c; ?>" data-type="music_videos">
                    <a href="https://www.vimeo.com/<?php echo $video['id']; ?>" target="_BLANK">
                        <img src="<?php echo $thumb; ?>" alt="" />
                    </a>
                    <span class="button button-remove">X</span>
                </div>
           <?php }
        }
        echo '</section>';
    }
}
// add assign to producer option
function agent() {
    global $post;

    $firstname = get_post_meta($post->ID,'agentFirstName', true);
    $lastname = get_post_meta($post->ID,'agentLastName', true);
    $emailaddress = get_post_meta($post->ID,'agentEmailaddress', true);

    $notify = get_post_meta($post->ID,'agentNotify', true); 

    $current_user = wp_get_current_user();
    $producerEmail = $current_user->user_email;
    $producerName = $current_user->user_firstname;

    $selected = get_post_meta($post->ID,'agent', true);

    $args = array(
        'role' => 'agent'
    );
    $users = get_users($args);
    if( !empty($users) ) {
        echo'<select name="agent" class="assignedAgent">';
        foreach( $users as $user ){
            $info = get_userdata($user->data->ID);
            echo '<option value="'.$info->ID.'"'.selected( $selected, $info->ID ).'>'.$info->display_name.'</option>';
        }
        echo'</select>';
        echo '<label for="ccd" id="cc_crosby"><input type="checkbox" name="ccd" id="ccd" /> CC Crosby</label>';
    } else {
        echo "<p>There are no agents.</p>";
    }

}
// ajax call to save shipped selection
add_action( 'admin_footer', 'contact_javascript' );
function contact_javascript() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            notify = function(btn, producer) {
                jQuery.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    type: "GET",
                    data: {
                        email: btn.attr('data-email'),
                        producer: producer,
                        recipeID: btn.attr('data-recipe'),
                        action: 'Notify',
                        _ajax_nonce: "<?php echo wp_create_nonce( 'my_recipe_ajax_nonce' ); ?>"
                    },
                    dataType: 'html',
                    success: function(response){
                        jQuery('.producerWrap').replaceWith('<p class="success">Assigned</p>');
                    },
                    error : function(jqXHR, textStatus, errorThrown) {
                        window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
                    }
                }); 
            };
            jQuery('.button-notify').click(function(e){
                e.preventDefault();
                var btn = jQuery(this);
                var producer = jQuery('.assignedAgent').val();
                notify(btn, producer);
            });
        });
    </script>
<?php }
/* When the post is saved, saves our custom data */
add_action( 'save_post', 'dynamic_save_postdata' );
function dynamic_save_postdata( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    if ( !isset( $_POST['commercials_noncename'] ) || !wp_verify_nonce( $_POST['commercials_noncename'], 'commercial_links' ) )
        return;

    // save youtube commercials
    // add current video ID's to array
    $commercials  = get_post_meta($post_id,'commercials', true);
    
    $yt_new  = $_POST['yt_commercial'];
    $vimeo_new  = $_POST['vimeo_commercial'];

    if(!empty($yt_new) || !empty($vimeo_new) ) {
        
        if($commercials && $yt_new) {
            $old[] = array(
                    'id' => getYoutubeIdFromUrl($yt_new),
                    'type' => 'youtube'
                );
            $comms = $old;
        } elseif($commercials && $vimeo_new) {
            $old[] = array(
                    'id' => getYoutubeIdFromUrl($vimeo_new),
                    'type' => 'vimeo'
                );
            $comms = $old;
        } elseif($yt_new) {
            $commercials[] = array(
                    'id' => getYoutubeIdFromUrl($yt_new),
                    'type' => 'youtube'
                );
            $comms = $commercials;
        } elseif($vimeo_new) {
            $commercials[] = array(
                    'id' => getYoutubeIdFromUrl($vimeo_new),
                    'type' => 'vimeo'
                );
            $comms = $commercials;
        }

        update_post_meta($post_id,'commercials',$comms);
    }

    if ( !isset( $_POST['music_videos_noncename'] ) || !wp_verify_nonce( $_POST['music_videos_noncename'], 'music_videos' ) )
        return;

    $musicVideos  = get_post_meta($post_id,'music_videos', true);
    
    $yt_newMusic  = $_POST['yt_musicVideo'];
    $vimeo_newMusic  = $_POST['vimeo_musicVideo'];

    if(!empty($yt_newMusic) || !empty($vimeo_newMusic) ) {
        
        if($musicVideos && $yt_newMusic) {
            $oldVideos[] = array(
                    'id' => getYoutubeIdFromUrl($yt_newMusic),
                    'type' => 'youtube'
                );
            $videos = $oldVideos;
        } elseif($musicVideos && $vimeo_newMusic) {
            $oldVideos[] = array(
                    'id' => getYoutubeIdFromUrl($vimeo_newMusic),
                    'type' => 'vimeo'
                );
            $videos = $oldVideos;
        } elseif($yt_newMusic) {
            $commercials[] = array(
                    'id' => getYoutubeIdFromUrl($yt_newMusic),
                    'type' => 'youtube'
                );
            $videos = $commercials;
        } elseif($vimeo_newMusic) {
            $commercials[] = array(
                    'id' => getYoutubeIdFromUrl($vimeo_newMusic),
                    'type' => 'vimeo'
                );
            $videos = $commercials;
        }

        update_post_meta($post_id,'music_videos',$videos);
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

function list_Images($cat, $name) {
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
        echo '<div class="pane" id="'.$cat.'">';
        echo '<h3 class="termName">// '.$name.'</h3>';
            echo '<div class="imageWrap">';
                while ($query->have_posts()) {
                    $query->the_post();
                    echo '<a href="#photomodal" data-toggle="modal" class="singlephoto" data-photo="'.$post->guid.'">';
                        echo '<img src="'.$post->guid.'" alt="" />';
                        echo '<div class="playwrap"><i class="fa fa-plus"></i></div>';
                    echo '</a>';
                }
            echo '</div>';
        echo '</div>';
    }
    wp_reset_query();
}

function get_featured($id) {
    $args = array(
        'post_parent' => $id,
        'post_type' => 'attachment',
        'posts_per_page' => 1,
        'post_status' => 'inherit',
        'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png',
        'tax_query' => array(
            array(
                'taxonomy' => 'image_type',
                'field' => 'slug',
                'terms' => 'featured'
            )
        )
    );
    $featured = get_posts($args);
    foreach($featured as $image) {
        return $image->guid;
    }
}

?>