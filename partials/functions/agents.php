<?php

add_role(
    'agent-role',
    __( 'Agent Role' ),
    array(
    	'activate_plugins' 		=> true,
    	'delete_others_pages' 	=> true,
    	'delete_others_posts' 	=> true,
    	'delete_pages' 			=> true,
    	'delete_posts' 			=> true,
    	'delete_private_pages' 	=> true,
    	'delete_private_posts' 	=> true,
    	'delete_published_posts' => true,
    	'delete_published_pages' => true,
    	'edit_dashboard' 		=> true,
    	'edit_others_pages' 	=> true,
    	'edit_others_posts' 	=> true,
    	'edit_pages' 			=> true,
    	'edit_posts' 			=> true,
    	'edit_private_pages' 	=> true,
    	'edit_private_posts' 	=> true,
    	'edit_published_pages' 	=> true,
    	'edit_published_posts' 	=> true,
    	'edit_theme_options'	=> true,
    	'export' 				=> true,
    	'import' 				=> true,
    	'list_users' 			=> true,
    	'manage_categories' 	=> true,
    	'manage_links' 			=> true,
    	'manage_options' 		=> true,
    	'moderate_comments' 	=> true,
    	'promote_users' 		=> true,
    	'publish_pages' 		=> true,
    	'publish_posts' 		=> true,
    	'read_private_pages' 	=> true,
    	'read_private_posts' 	=> true,
        'read'         			=> true,
        'remove_users'   		=> true,
        'switch_themes' 		=> false,
        'upload_files' 			=> true
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
add_action( 'admin_head-user-edit.php', 'remove_website_input' );
add_action( 'admin_head-profile.php',   'remove_website_input' );
function remove_website_input() {
    echo '<style>tr.user-url-wrap{ display: none; }</style>';
}

// add upload functionality for custom user roles
add_action('admin_init', 'allow_user_uploads');
function allow_user_uploads() {
  $artist = get_role('agent-role');
  $artist->add_cap('upload_files');
}

// Add social inputs to user contact info
add_filter('user_contactmethods', 'modify_contact_methods');
function modify_contact_methods($profile_fields) {
	// Add new fields
	$profile_fields['agent_fb_url'] = 'Facebook URL';
	$profile_fields['agent_twit_url'] = 'Twitter URL';
	$profile_fields['agent_ig_url'] = 'Instagram URL';
	return $profile_fields;
}
// add upload logo
add_action( 'show_user_profile', 'heard_upload_logo' );
add_action( 'edit_user_profile', 'heard_upload_logo' );
function heard_upload_logo() {
	if(is_admin()) {
		global $profileuser;
		$user_id = $profileuser->ID;
	} else {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
	}

	$logo = get_user_meta($user_id, 'agent_image', true);

	?>
	<h2>Agent Profile Image</h2>
	<p class="description">Upload your agent head shot.</p>

	<section class="imageUpload" data-input="agent_image" data-user="<?php echo $user_id; ?>" data-img="profile">
		<div class="image-placeholder profile">
			<img src="<?php echo $logo; ?>" alt="" />
		</div>
	    <?php if ( !empty($logo) ) { ?>
	    	<button class="remove-image button button-large">Remove</button>
		<?php } else { ?>
			<button class="add button button-large upload-image" id="upload-logo" style="text-align:center;">
		        Upload/Set Profile Image
		    </button>
		<?php } ?>
	    <input type="hidden" name="agent_image" id="agent_image" value="<?php echo $logo; ?>" />
	</section>
<?php }

// ajax response to save download track
add_action('wp_ajax_setImage', 'setCustomImage');
add_action('wp_ajax_nopriv_setImage', 'setCustomImage');
function setCustomImage() {
	// set userID
	if(is_admin()) {
		global $profileuser;
		$user_id = $profileuser->ID;
	} else {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
	}

	$imageField = (isset($_GET['imageField'])) ? $_GET['imageField'] : 0;
	$imageURL = (isset($_GET['fieldVal'])) ? $_GET['fieldVal'] : 0;
	$userID = (isset($_GET['userID'])) ? $_GET['userID'] : 0;

	if($imageURL !== "") {
        update_user_meta( $userID, $imageField, $imageURL);
    } else {
		update_user_meta( $userID, $imageField, "");
	}
}
// ajax response to save download track
add_action('wp_ajax_dropImage', 'dropCustomImage');
add_action('wp_ajax_nopriv_dropImage', 'dropCustomImage');
function dropCustomImage() {
	
	$imageField = (isset($_GET['imageField'])) ? $_GET['imageField'] : 0;
	$userID 	= (isset($_GET['userID'])) ? $_GET['userID'] : 0;
	$image  	= (isset($_GET['imageVal'])) ? $_GET['imageVal'] : 0;

	if(isset($image)) {
        $uploaddir = wp_upload_dir();
        $file = $image;
        $uploadfile = $uploaddir['path'] . '/' . basename( $file["name"] );

        move_uploaded_file( $file["tmp_name"], $uploadfile );
        $filename = basename( $uploadfile );

        $wp_filetype = wp_check_filetype(basename($filename), null );

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        
        wp_insert_attachment( $attachment, $uploadfile);
        
        update_user_meta($userID,$imageField,$uploadfile);
    }
}