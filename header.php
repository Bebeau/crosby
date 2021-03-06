<!DOCTYPE html>

<html <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml" itemscope itemtype="http://schema.org/Article">

<head>
	<!-- Basic Page Needs
	================================================== -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="google-site-verification" content="cMwy6gwG2_utC68iF--k9po6YGzJpNOPshTCiPNb2DE" />

	<title><?php wp_title(); ?></title>
	<meta name="keywords" content="" />
	<meta name="author" content="The INiT Group">
	
	<!-- Mobile Specific Metas
  	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon/favicon.ico">
	
	<!-- Facebook open graph tags -->
	<meta property="og:title" content="<?php the_title(); ?>"/>
	<meta property="og:description" content="<?php
	  if ( function_exists('WPSEO_Meta::get_value()') ) {
	    echo WPSEO_Meta::get_value('metadesc');
	  } else {
	    echo $post->post_excerpt;
	  }
	?>"/>

	<?php if (have_posts()):while(have_posts()):the_post(); endwhile; endif;?>
		<meta property="fb:app_id" content="496408310403833" />
	<?php if (is_single()) { ?>
		<!-- Open Graph -->
		<meta property="og:url" content="<?php the_permalink(); ?>"/>
		<meta property="og:title" content="<?php single_post_title(''); ?>" />
		<meta property="og:description" content="<?php echo strip_tags(get_the_excerpt()); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:image" content="<?php if (function_exists('wp_get_attachment_thumb_url')) {
			echo wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'medium', false );
		} ?>" />
		<!-- Schema.org -->
		<meta itemprop="name" content="<?php single_post_title(''); ?>"> 
		<meta itemprop="description" content="<?php echo strip_tags(get_the_excerpt()); ?>"> 
		<meta itemprop="image" content="<?php if (function_exists('wp_get_attachment_thumb_url')) {
			echo wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'medium', false );
		} ?>">
		<!-- Twitter Cards -->
		<meta property="twitter:card" content="summary"> 
		<meta property="twitter:site" content="@CrosbyC"> 
		<meta property="twitter:title" content="<?php single_post_title(''); ?>"> 
		<meta property="twitter:description" content="<?php echo strip_tags(get_the_excerpt()); ?>"> 
		<meta property="twitter:creator" content="@CrosbyC"> 
		<meta property="twitter:image" content="<?php if (function_exists('wp_get_attachment_thumb_url')) {
			echo wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'medium', false );
		} ?>">
		<meta property="twitter:url" content="<?php the_permalink(); ?>" />
		<meta property="twitter:domain" content="<?php echo site_url(); ?>">
	<?php } else { ?>
		<!-- Open Graph -->
		<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
		<meta property="og:description" content="<?php bloginfo('description'); ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:image" content="<?php echo bloginfo('template_directory'); ?>/assets/images/facebook.jpg" />
		<!-- Schema.org -->
		<meta itemprop="name" content="<?php bloginfo('name'); ?>"> 
		<meta itemprop="description" content="<?php bloginfo('description'); ?>"> 
		<meta itemprop="image" content="<?php echo bloginfo('template_directory'); ?>/assets/images/google.jpg">
		<!-- Twitter Cards -->
		<meta property="twitter:card" content="summary"> 
		<meta property="twitter:site" content="@CrosbyC"> 
		<meta property="twitter:title" content="<?php bloginfo('name'); ?>"> 
		<meta property="twitter:description" content="<?php bloginfo('description'); ?>"> 
		<meta property="twitter:creator" content="@CrosbyC"> 
		<meta property="twitter:image" content="<?php echo bloginfo('template_directory'); ?>/assets/images/twitter.jpg">
		<meta property="twitter:url" content="<?php the_permalink() ?>" />
		<meta property="twitter:domain" content="<?php echo site_url(); ?>">
	<?php } ?>

	<!-- WP Generated Header
	================================================== --> 
	<?php wp_head(); ?>
    
</head>

<body <?php body_class();?>>

	<div id="loader">
		<div id="load"></div>
	</div>

<?php

	$menu_args = array(
		'theme_location'  => 'primary',
		'menu'            => 'Primary Menu',
		'container'       => 'div',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="%1$s" class="%2$s list-unstyled">%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
	);

if(is_front_page()) {
	$background = get_option('custom_bg');
	$info = new SplFileInfo($background);
    $fileType = $info->getExtension();
	if(!empty($background)) {
		if($fileType === "mp4" || $fileType === "webm" || $fileType === "ogv" || $fileType === "ogg") {
			echo '<div class="home_bg video_bg">';
				echo '<video muted autoplay id="bgvid" loop>';
	                echo '<source src="'.$background.'" type="video/webm">';
	                echo '<source src="'.$background.'" type="video/ogv">';
	                echo '<source src="'.$background.'" type="video/mp4">';
	            echo '</video>';
	        echo '</div>';
		} else {
			echo '<div class="home_bg" style="background:url('.$background.') no-repeat scroll top left / cover;"></div>';
		}
	} else {
		echo '<div class="home_bg"></div>';
	}
	echo '<header class="home">';
		echo '<a id="logo" href="'.get_site_url().'"><img class="logo" src="'.get_bloginfo('template_directory').'/assets/images/logo.png" alt="" /></a>';
		wp_nav_menu( $menu_args );
	echo '</header>'; ?>

	<?php 
}

if(is_singular()) {
	// get current user id
	global $post;

	$videos 		= get_post_meta($post->ID, 'videos', true);
	$order 			= get_post_meta($post->ID, 'sub_nav_order', true);
	$attachments 	= get_children( array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image') );

	$image_types = get_the_terms($post->ID, 'image_type');

	echo '<header class="sub hidden-xs">';
		echo '<a id="logo" href="'.get_site_url().'"><img class="logo logo-sub" src="'.get_bloginfo('template_directory').'/assets/images/logo_blue.png" alt="" /></a>';
		
		echo '<ul class="menu portfolio-menu">';
		$category = get_the_category();
		if($category) {
			echo '<li class="cat_title">'.$category[0]->name.' //</li>';
		} else {
			echo '<li class="cat_title">Agency //</li>';
		}
		echo '<li>'.get_the_title().' <span class="slash">//</span>';
			echo '<ul>';
			if(is_single()) {
				echo '<li><a role="button" data-toggle="collapse" href="#bio" aria-expanded="false" aria-controls="bio" class="info collapsed">Bio</a></li>';
			}
			$args = array(
				'post_parent' => $post->ID,
			    'taxonomy' => 'image_type',
			    'hide_empty' => true,
			    'orderby' => 'include',
			    'include' => $order
			);
			$terms = get_terms($args);
			if($terms) {
				foreach ($terms  as $term ) {
					if(has_Images($term->slug) && $term->name != "Flasher") {
						echo '<li><a class="tab" href="#'.$term->slug.'" data-post="'.$post->ID.'">'.$term->name.'</a></li>';
					}
				}
			}  
			if(!empty($videos)) {
				echo '<li><a class="tab" href="#videos">Videos</a></li>';
			}
			echo '</ul>';
		echo '</li>';
		echo '</ul>';

		echo '<hr>';

		$fb_url = get_post_meta($post->ID, 'artist_fb_url', true);
		$ig_url = get_post_meta($post->ID, 'artist_ig_url', true);
		$twit_url = get_post_meta($post->ID, 'artist_twit_url', true);

		if(!empty($fb_url) || !empty($ig_url) || !empty($twit_url)) {
			echo '<div id="artistSocial" class="socialWrap">';
				if(!empty($fb_url)) {
					echo '<a href="'.$fb_url.'"><i class="facebook"></i></a>';
				}
				if(!empty($twit_url)) {
					echo '<a href="'.$twit_url.'"><i class="twitter"></i></a>';
				}
				if(!empty($ig_url)) {
					echo '<a href="'.$ig_url.'"><i class="instagram"></i></a>';
				}
			echo '</div>';
		}

		$agentID = get_post_meta($post->ID, 'agentID', true);
		
		if(!empty($agentID)) {
			$userInfo = get_userdata($agentID);
			$agentEmail = $userInfo->user_email;
			$cc_emails = get_post_meta($post->ID, 'cc_emails', true);
			$emails = array_filter($cc_emails);
			if(!empty($emails)) {
				$email_string = implode(",",$emails);
				echo '<a class="contact-btn" href="mailto:'.$agentEmail.'?&subject='.get_the_title().'- Website Inquiry&cc='.$email_string.'">Contact Agent</a>';
			} else {
				echo '<a class="contact-btn" href="mailto:'.$agentEmail.'?&subject='.get_the_title().'- Website Inquiry">Contact Agent</a>';
			}
		}

		if(!empty($fb_url) || !empty($ig_url) || !empty($twit_url) || !empty($agentID)) {
			echo '<hr>';
		}

		wp_nav_menu( $menu_args );

		echo '<hr>';

		echo '<span class="legal">&copy; '.date("Y").' '.get_bloginfo("name").' <br />All Rights Reserved.</span>';

	echo '</header>';

	echo '<div class="sub visible-xs">';
		echo '<a class="logoWrap" href="'.get_site_url().'"><img class="logo logo-sub" src="'.get_bloginfo('template_directory').'/assets/images/logo_blue.png" alt="" /></a>';
		echo '<div class="dropdown">';
			echo '<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
				echo 'Menu';
			    echo ' <i class="fa fa-angle-down"></i>';
			echo '</button>';
			echo '<ul role="tablist" class="portfolio-menu dropdown-menu" aria-labelledby="dLabel">';
				$terms = get_terms( array(
					'post_parent' => $post->ID,
				    'taxonomy' => 'image_type',
				    'hide_empty' => true,
				    'exclude' => '22'
				) );
				if($terms) {
					foreach ($terms  as $term ) {
						if(has_Images($term->slug) && $term->name != "Flasher") {
							echo '<li role="presentation"><a href="#'.$term->slug.'" data-post="'.$post->ID.'">'.$term->name.'</a></li>';
						}
					}
				}  
				if(!empty($videos)) {
					echo '<li><a class="tab" href="#videos">Videos</a></li>';
				}
			echo '</ul>';
		echo '</div>';
	echo '</div>';
} ?>