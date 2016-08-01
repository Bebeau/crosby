<?php

global $post;

// Add admin styles for portfolios
add_action( 'admin_enqueue_scripts', 'load_portfolios_admin' );
function load_portfolios_admin() {
    global $typenow;
    if( $typenow == 'portfolios' ) {
        wp_enqueue_style( 'portfolio-styles', get_template_directory_uri() . '/assets/css/portfolios.css', false, '1.0.0' );
        wp_enqueue_script('jquery_ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'), null, true);
        // Registers and enqueues the required javascript.
        wp_register_script( 'admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ) );
        wp_localize_script( 'admin', 'meta_image',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' )
            )
        );
        wp_enqueue_script( 'admin' );
        // Overrides code styling to accommodate for a third dropdown filter
        add_action( 'admin_footer', function(){ ?>
            <style>
                .media-modal-content .media-frame select.attachment-filters {
                    max-width: -webkit-calc(33% - 12px);
                    max-width: calc(33% - 12px);
                }
            </style>
            <?php
        });
    }
    wp_enqueue_style( 'user-styles', get_template_directory_uri() . '/assets/css/user.css', false, '1.0.0' );
    // Registers and enqueues the required javascript.
    wp_register_script( 'meta-box-image-upload', get_template_directory_uri() . '/assets/js/editProfile.js', array( 'jquery' ) );
    wp_localize_script( 'meta-box-image-upload', 'meta_image',
        array(
            'title' => 'Choose or Upload Image',
            'button' => 'Select Image',
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        )
    );
    wp_enqueue_script( 'meta-box-image-upload' );
    wp_enqueue_media();
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

// Add custom meta boxes to display recipe specs
add_action( 'add_meta_boxes', 'portfolio_meta_box', 1 );
function portfolio_meta_box( $post ) {
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
        'Assign Agent', 
        'agent',
        'portfolios', 
        'side', 
        'high'
    );
    add_meta_box(
        'social', 
        'Artist Social', 
        'social',
        'portfolios', 
        'side', 
        'high'
    );
    add_meta_box(
        'image_type_ordering', 
        'Tab Ordering', 
        'image_type_ordering',
        'portfolios', 
        'side', 
        'high'
    );
}
// commercials meta box
function commercials($post) { 
    // Use nonce for verification
    wp_nonce_field( 'commercial_links', 'commercials_noncename' );

    //get the saved meta as an arry
    $commercials = get_post_meta($post->ID,'commercials', true);

    echo '<h3>Upload Commercials</h3>';
    echo '<p>Copy &amp; paste <a href="https://youtube.com/" alt="YouTube" target="_BLANK">YouTube</a> or <a href="https://vimeo.com/" alt="Vimeo" target="_BLANK">Vimeo</a> video links below and click "Add Video" to save. Once saved, videos will display below. Drag and drop the thumbnails to rearrange the order, and simply click the "x" to remove the video.</p>';
    echo '<section id="Commercials">';
        echo '<article class="link"> <i class="youtube"></i><input type="text" name="yt_commercial" value="" placeholder="https://www.youtube.com/watch?v=VIDEOID"/><button type="submit" class="button button-large">+ Add Video</button></article>';
        echo '<article class="link"> <i class="vimeo"></i><input type="text" name="vimeo_commercial" value="" placeholder="https://www.vimeo.com/VIDEOID"/><button type="submit" class="button button-large">+ Add Video</button></article>';
    echo '</section>'; ?>
    <?php if ( !empty($commercials) ) {
        echo '<ul class="videoWrap videoSort" data-post="'.$post->ID.'" data-type="commercials">';
            $c = 0;
            foreach( $commercials as $commercial ) {
                if($commercial['type'] === "youtube") { ?>
                    <li class="video ui-state-default" data-key="<?php echo $c; ?>" data-order="<?php echo $commercial['id'];?>" data-video="youtube" data-link="https://www.youtube.com/watch?v=<?php echo $commercial['id']; ?>" style="background: url('https://i1.ytimg.com/vi/<?php echo $commercial['id']; ?>/hqdefault.jpg') no-repeat scroll center / cover;">
                        <span class="button button-remove">X</span>
                    </li>
                <?php } elseif($commercial['type'] === "vimeo") {
                    $imgid = $commercial['id'];
                    $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
                    $thumb = $hash[0]['thumbnail_large'];
                ?>
                <li class="video ui-state-default" data-key="<?php echo $c; ?>" data-order="<?php echo $commercial['id'];?>" data-video="vimeo" data-link="https://www.vimeo.com/<?php echo $commercial['id']; ?>" style="background: url('<?php echo $thumb; ?>') no-repeat scroll center / contain;">
                    <span class="button button-remove">X</span>
                </li>
                <?php } $c++;
            }
        echo '</ul>';
    }
}
// music videos meta box
function music_videos($post) { 
    // Use nonce for verification
    wp_nonce_field( 'music_videos', 'music_videos_noncename' );

    //get the saved meta as an arry
    $music_videos = get_post_meta($post->ID,'music_videos', true);
    
    echo '<h3>Upload Music Videos</h3>';
    echo '<p>Copy &amp; paste <a href="https://youtube.com/" alt="YouTube" target="_BLANK">YouTube</a> or <a href="https://vimeo.com/" alt="Vimeo" target="_BLANK">Vimeo</a> video links below and click "Add Video" to save. Once saved, videos will display below. Drag and drop the thumbnails to rearrange the order, and simply click the "x" to remove the video.</p>';
    echo '<section id="MusicVideos">';
        echo '<article class="link"> <i class="youtube"></i><input type="text" name="yt_musicVideo" value="" placeholder="https://www.youtube.com/watch?v=VIDEOID"/><button type="submit" class="button button-large">+ Add Video</button></article>';
        echo '<article class="link"> <i class="vimeo"></i><input type="text" name="vimeo_musicVideo" value="" placeholder="https://www.vimeo.com/VIDEOID"/><button type="submit" class="button button-large">+ Add Video</button></article>';
    echo '</section>'; ?>
    <?php if ( !empty($music_videos) ) {
        echo '<ul class="videoWrap videoSort" data-post="'.$post->ID.'" data-type="music_videos">';
            $c = 0;
            foreach( $music_videos as $video ) {
                if($video['type'] === "youtube") { ?>
                    <li class="video ui-state-default" data-key="<?php echo $c; ?>" data-order="<?php echo $video['id'];?>" data-video="youtube" data-link="https://www.youtube.com/watch?v=<?php echo $video['id']; ?>" style="background: url('https://i1.ytimg.com/vi/<?php echo $video['id']; ?>/hqdefault.jpg') no-repeat scroll center / cover;">
                        <span class="button button-remove">X</span>
                    </li>
                <?php } elseif($video['type'] === "vimeo") {
                    $imgid = $video['id'];
                    $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
                    $thumb = $hash[0]['thumbnail_large'];
                ?>
                <li class="video ui-state-default" data-key="<?php echo $c; ?>" data-order="<?php echo $video['id'];?>" data-video="vimeo" data-link="https://www.vimeo.com/<?php echo $video['id']; ?>" style="background: url('<?php echo $thumb; ?>') no-repeat scroll center / contain;">
                    <span class="button button-remove">X</span>
                </li>
                <?php } $c++;
            }
        echo '</section>';
    }
}
// ajax response to save order
add_action('wp_ajax_setVideoOrder', 'setVidOrder');
add_action('wp_ajax_nopriv_setVideoOrder', 'setVidOrder');
function setVidOrder() {
    $order = str_replace( array( '[', ']','"' ),'', $_GET['order'] );
    $postID = (isset($_GET['postID'])) ? $_GET['postID'] : 0;
    $type = (isset($_GET['type'])) ? $_GET['type'] : 0;
    update_post_meta($postID, $type, $order );
}

// add assign to producer option
function agent($post) {

    wp_nonce_field( 'agent_email', 'agent_noncename' );

    $agentID = get_post_meta($post->ID,'agentID', true);

    $args = array(
        'role' => 'agent'
    );
    $users = get_users($args);
    if( !empty($users) ) {
        echo '<p>Assign an agent to this artist from the dropdown below.</p>';
        echo'<select name="agentID" class="assignedAgent" data-post="'.$post->ID.'">';
        echo '<option value="">-- assign an agent --</option>';
        foreach( $users as $user ){
            $info = get_userdata($user->data->ID);
            if(strval($info->ID) === $agentID) {
                echo '<option value="'.$info->ID.'" selected="selected">'.$info->display_name.'</option>';
            } else {
                echo '<option value="'.$info->ID.'">'.$info->display_name.'</option>';
            }
        }
        echo'</select>';
    } else {
        echo "<p>There are no agents.</p>";
    }
    $emails = get_post_meta($post->ID,'cc_emails', true);
    echo '<p>Add emails below to CC them in on contact inquiries.</p>';
    echo '<span class="emails">';
    if(!empty($emails)) {
        foreach($emails as $email) {
            echo '<input type="text" name="cc_emails[]" placeholder="email@address.." value="'.$email.'" />';
        }
    } else {
        echo '<input type="text" name="cc_emails[]" placeholder="email@address.." />';
    }
    echo '</span>';
    echo '<button class="button email-button">+ Add Email</button>';

}
// add image_type ordering
function image_type_ordering($post) {
    // get previous saved order
    $order = get_post_meta($post->ID, 'sub_nav_order', true);
    // get terms
    $args = array(
        'taxonomy' => 'image_type',
        'hide_empty' => true
    );
    $terms = get_terms( $args );
    // get saved terms
    $args = array(
        'taxonomy' => 'image_type',
        'hide_empty' => true,
        'orderby' => 'include',
        'include' => $order
    );
    $savedTerms = get_terms( $args);
    if($savedTerms && $order) {
        echo '<table class="wp-list-table widefat fixed striped pages"><tbody id="sortable" data-post="'.$post->ID.'">';
        foreach ($savedTerms as $term ) {
            if(has_Images($term->slug) && $term->slug !== "featured") { ?>
                <tr data-order="<?php echo $term->term_id; ?>" class="ui-state-default" >
                    <td>
                        <span class="bars"><i></i></span> <?php echo $term->name; ?>
                    </td>
                </tr>
           <?php }
        }
        foreach ($terms as $term ) {
            if(!in_array($term->term_id, $order) && has_Images($term->slug) && $term->slug !== "featured") { ?>
                 <tr data-order="<?php echo $term->term_id; ?>" class="ui-state-default" >
                    <td>
                        <span class="bars"><i></i></span> <?php echo $term->name; ?>
                    </td>
                </tr>
            <?php }
        }
        echo '</tbody></table>';
    } else {
        echo '<table class="wp-list-table widefat fixed striped pages"><tbody id="sortable" data-post="'.$post->ID.'">';
        foreach ($terms as $term ) {
            if(has_Images($term->slug) && $term->slug !== "featured") { ?>
                 <tr data-order="<?php echo $term->term_id; ?>" class="ui-state-default" >
                    <td>
                        <span class="bars"><i></i></span> <?php echo $term->name; ?>
                    </td>
                </tr>
            <?php }
        }
        echo '</tbody></table>';
    }
}
// ajax response to save order
add_action('wp_ajax_setOrder', 'setProjectOrder');
add_action('wp_ajax_nopriv_setOrder', 'setProjectOrder');
function setProjectOrder() {
    $order = str_replace( array( '[', ']','"' ),'', $_GET['order'] );
    $postID = (isset($_GET['postID'])) ? $_GET['postID'] : 0;
    update_post_meta($postID, 'sub_nav_order', $order );
}
// ajax response to save download track
add_action('wp_ajax_assignAgent', 'assignAgentEmail');
add_action('wp_ajax_nopriv_assignAgent', 'assignAgentEmail');
function assignAgentEmail() {
    $agentID = (isset($_GET['agentID'])) ? $_GET['agentID'] : 0;
    $postID = (isset($_GET['postID'])) ? $_GET['postID'] : 0;
    update_post_meta($postID, 'agentID', $agentID);
}
// add social fields for artists
function social($post) {
    wp_nonce_field( 'social_links', 'social_noncename' );

    $fb_url     = get_post_meta($post->ID,'artist_fb_url',true);
    $twit_url   = get_post_meta($post->ID,'artist_twit_url',true);
    $ig_url     = get_post_meta($post->ID,'artist_ig_url',true);
    $in_url     = get_post_meta($post->ID,'artist_in_url',true);

    echo '<p>Assign social media links to artists portfolios using the fields below</p>';
    echo '<span class="field"><i class="facebook"></i><input type="text" name="artist_fb_url" placeholder="https://facebook.com/USER" value="'.$fb_url.'" /></span>';
    echo '<span class="field"><i class="instagram"></i><input type="text" name="artist_ig_url" placeholder="https://instagram.com/USER" value="'.$ig_url.'" /></span>';
    echo '<span class="field"><i class="twitter"></i><input type="text" name="artist_twit_url" placeholder="https://twitter.com/USER" value="'.$twit_url.'" /></span>';
    echo '<span class="field"><i class="linkedin"></i><input type="text" name="artist_in_url" placeholder="https://linkedin.com/in/USER" value="'.$in_url.'" /></span>';
}
/* When the post is saved, saves our custom data */
add_action( 'save_post', 'dynamic_save_postdata' );
function dynamic_save_postdata( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    // check for social link nonce
    if ( !isset( $_POST['social_noncename'] ) || !wp_verify_nonce( $_POST['social_noncename'], 'social_links' ) )
        return;

    // save social links
    $fb_url     = $_POST['artist_fb_url'];
    $twit_url   = $_POST['artist_twit_url'];
    $ig_url     = $_POST['artist_ig_url'];
    $in_url     = $_POST['artist_in_url'];

    if(isset($fb_url)) {
        update_post_meta($post_id,'artist_fb_url',$fb_url);
    } else {
        update_post_meta($post_id,'artist_fb_url',"");
    }
    if(isset($twit_url)) {
        update_post_meta($post_id,'artist_twit_url',$twit_url);
    } else {
        update_post_meta($post_id,'artist_twit_url',"");
    }
    if(isset($ig_url)) {
        update_post_meta($post_id,'artist_ig_url',$ig_url);
    } else {
        update_post_meta($post_id,'artist_ig_url',"");
    }
    if(isset($in_url)) {
        update_post_meta($post_id,'artist_in_url',$in_url);
    } else {
        update_post_meta($post_id,'artist_in_url',"");
    }

    // check for commercial nonce
    if ( !isset( $_POST['commercials_noncename'] ) || !wp_verify_nonce( $_POST['commercials_noncename'], 'commercial_links' ) )
        return;

    // save commercials
    $commercials  = get_post_meta($post_id,'commercials', true);
    $yt_new  = $_POST['yt_commercial'];
    $vimeo_new  = $_POST['vimeo_commercial'];

    if(!empty($yt_new) || !empty($vimeo_new) ) {
        
        if($commercials && $yt_new) {
            $old[] = array(
                    'id' => getVideoIdFromUrl($yt_new),
                    'type' => 'youtube'
                );
            $comms = array_merge($commercials, $old);
        } elseif($commercials && $vimeo_new) {
            $old[] = array(
                    'id' => getVideoIdFromUrl($vimeo_new),
                    'type' => 'vimeo'
                );
            $comms = array_merge($commercials, $old);
        } elseif($yt_new) {
            $commercials[] = array(
                    'id' => getVideoIdFromUrl($yt_new),
                    'type' => 'youtube'
                );
            $comms = $commercials;
        } elseif($vimeo_new) {
            $commercials[] = array(
                    'id' => getVideoIdFromUrl($vimeo_new),
                    'type' => 'vimeo'
                );
            $comms = $commercials;
        }

        update_post_meta($post_id,'commercials',$comms);
    }

    // check for music video nonce
    if ( !isset( $_POST['music_videos_noncename'] ) || !wp_verify_nonce( $_POST['music_videos_noncename'], 'music_videos' ) )
        return;

    // save music video
    $musicVideos  = get_post_meta($post_id,'music_videos', true);
    $yt_newMusic  = $_POST['yt_musicVideo'];
    $vimeo_newMusic  = $_POST['vimeo_musicVideo'];

    if(!empty($yt_newMusic) || !empty($vimeo_newMusic) ) {
        
        if($musicVideos && $yt_newMusic) {
            $oldVideos[] = array(
                    'id' => getVideoIdFromUrl($yt_newMusic),
                    'type' => 'youtube'
                );
            $videos = array_merge($musicVideos, $oldVideos);
        } elseif($musicVideos && $vimeo_newMusic) {
            $oldVideos[] = array(
                    'id' => getVideoIdFromUrl($vimeo_newMusic),
                    'type' => 'vimeo'
                );
            $videos = array_merge($musicVideos, $oldVideos);
        } elseif($yt_newMusic) {
            $musicvideos[] = array(
                    'id' => getVideoIdFromUrl($yt_newMusic),
                    'type' => 'youtube'
                );
            $videos = $musicvideos;
        } elseif($vimeo_newMusic) {
            $musicvideos[] = array(
                    'id' => getVideoIdFromUrl($vimeo_newMusic),
                    'type' => 'vimeo'
                );
            $videos = $musicvideos;
        }

        update_post_meta($post_id,'music_videos',$videos);
    }

    // check for music video nonce
    if ( !isset( $_POST['agent_noncename'] ) || !wp_verify_nonce( $_POST['agent_noncename'], 'agent_email' ) )
        return;
    
    $cc_emails = array_filter($_POST['cc_emails']);
    $emails = array();
    if(is_array($cc_emails)) {
        foreach($cc_emails as $email) {
            $emails[] = $email;
        }
        update_post_meta($post_id, 'cc_emails', $emails);
    } else {
        update_post_meta($post_id, 'cc_emails', "");
    }

}
// parse youtube/vimeo id from url submitted
function getVideoIdFromUrl($url) {
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
// check to see if image_type exists
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
// list images from given category
function list_Images($cat, $name) {
    global $post;
    $args = array(
        'post_parent' => $post->ID,
        'post_type' => 'attachment',
        'posts_per_page'    => -1,
        'post_status' => 'inherit',
        'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png',
        'orderby' => 'menu_order ID',
        'order' => 'ASC',
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
                        echo '<div class="playwrap"><span class="plus"></span></div>';
                    echo '</a>';
                }
            echo '</div>';
        echo '</div>';
    }
    wp_reset_query();
}
// add featured image to menu item in data-image attribute
add_filter( 'nav_menu_link_attributes', 'add_feature_image', 10, 4 );
function add_feature_image( $atts, $item, $args ) {
    $post_id = get_post_meta( $item->ID, '_menu_item_object_id', true );
    $args = array(
        'post_parent' => $post_id,
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
        $atts['data-image'] = $image->guid;
    }
    return $atts;
}

?>