<!DOCTYPE html>

<html <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml" itemscope itemtype="http://schema.org/Article">

<head>
	<!-- Basic Page Needs
	================================================== -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="google-site-verification" content="" />

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
	<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/favicon/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('template_directory'); ?>/favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('template_directory'); ?>/favicon/apple-touch-icon-114x114.png">
	
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
		<meta property="twitter:site" content="@sophieuliano"> 
		<meta property="twitter:title" content="<?php single_post_title(''); ?>"> 
		<meta property="twitter:description" content="<?php echo strip_tags(get_the_excerpt()); ?>"> 
		<meta property="twitter:creator" content="@sophieuliano"> 
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
		<meta property="og:image" content="<?php echo bloginfo('template_directory'); ?>/assets/images/default_facebook.jpg" />
		<!-- Schema.org -->
		<meta itemprop="name" content="<?php bloginfo('name'); ?>"> 
		<meta itemprop="description" content="<?php bloginfo('description'); ?>"> 
		<meta itemprop="image" content="<?php echo bloginfo('template_directory'); ?>/assets/images/default_google.jpg">
		<!-- Twitter Cards -->
		<meta property="twitter:card" content="summary"> 
		<meta property="twitter:site" content="@sophieuliano"> 
		<meta property="twitter:title" content="<?php bloginfo('name'); ?>"> 
		<meta property="twitter:description" content="<?php bloginfo('description'); ?>"> 
		<meta property="twitter:creator" content="@sophieuliano"> 
		<meta property="twitter:image" content="<?php echo bloginfo('template_directory'); ?>/assets/images/default_twitter.jpg">
		<meta property="twitter:url" content="<?php the_permalink() ?>" />
		<meta property="twitter:domain" content="<?php echo site_url(); ?>">
	<?php } ?>

	<!-- WP Generated Header
	================================================== --> 
	<?php wp_head(); ?>
    
</head>

<body>

<?php if(is_front_page()) {
	echo '<div class="home_bg"></div>';
} ?>

<div class="container-fluid wrap">

<?php if(is_front_page()) {
	echo '<header class="col-sm-4 home">';
		echo '<a href="'.get_site_url().'"><img class="logo" src="'.get_bloginfo('template_directory').'/assets/images/logo.png" alt="" /></a>';

			$args = array(
			    'hierarchical'             => 1,
			    'orderby'                  => 'id',
			    'order'                    => 'ASC'
			); 
			$cats = get_categories($args);
			echo '<ul class="menu">';
				foreach($cats as $cat) {
					$catID = $cat->term_id;
					$catLink = get_category_link($catID);
					echo '<li>'.$cat->name.' //';
						query_posts( array(
								'post_type' => 'portfolios',
								'order' 	=> 'DESC',
								'cat'		=> $catID
							)
					    );
					    if (have_posts()) : while (have_posts()) : the_post();
					    	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'large' );
					    	echo '<ul>';
					    		echo '<li><a class="person" href="'.get_the_permalink().'" data-image="'.$image[0].'">'.get_the_title().'</a></li>';
					    	echo '</ul>';
					    endwhile; endif;
					echo '</li>';
				}
			echo '</ul>';
	echo '</header>';
}

if(is_singular()) {
	// get current user id
	global $post;

	$commercials 	= get_post_meta($post->ID, 'commercials', true);
	$music_videos 	= get_post_meta($post->ID, 'music_videos', true);
	$attachments 	= get_children( array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image') );

	$image_types = get_the_terms($post->ID, 'image_type');

	echo '<header class="col-sm-4 sub hidden-xs">';
		echo '<a href="'.get_site_url().'"><img class="logo logo-sub" src="'.get_bloginfo('template_directory').'/assets/images/logo.png" alt="" /></a>';
		echo '<ul class="menu"><li>'.get_the_title().' //';
			echo '<ul role="tablist">';
			$terms = get_terms( array(
				'post_parent' => $post->ID,
			    'taxonomy' => 'image_type',
			    'hide_empty' => true,
			) );
			if($terms) {
				foreach ($terms  as $term ) {
					if(has_Images($term->slug)) {
						echo '<li role="presentation"><a href="#'.$term->slug.'" aria-controls="'.$term->slug.'" role="tab" data-toggle="tab">'.$term->name.'</a></li>';
					}
				}
			}  
			if(!empty($commercials)) {
				echo '<li role="presentation"><a href="#commercial" aria-controls="commercial" role="tab" data-toggle="tab">Commercials</a></li>';
			}
			if(!empty($music_videos)) {
				echo '<li role="presentation"><a href="#music" aria-controls="music" role="tab" data-toggle="tab">Music Videos</a></li>';
			}
			echo '</ul>';
		echo '</li></ul>';
	echo '</header>';

	echo '<header class="col-sm-4 sub visible-xs">';
		echo '<a class="logoWrap" href="'.get_site_url().'"><img class="logo logo-sub" src="'.get_bloginfo('template_directory').'/assets/images/logo.png" alt="" /></a>';
		echo '<div class="dropdown">';
		echo '<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			echo 'Categories';
		    echo ' <span class="caret"></span>';
		echo '</button>';
		echo '<ul role="tablist" class="dropdown-menu" aria-labelledby="dLabel">';
			$terms = get_terms( array(
				'post_parent' => $post->ID,
			    'taxonomy' => 'image_type',
			    'hide_empty' => true,
			) );
			if($terms) {
				foreach ($terms  as $term ) {
					if(has_Images($term->slug)) {
						echo '<li role="presentation"><a href="#'.$term->slug.'" aria-controls="'.$term->slug.'" role="tab" data-toggle="tab">'.$term->name.'</a></li>';
					}
				}
			}  
			if(!empty($commercials)) {
				echo '<li role="presentation"><a href="#commercial" aria-controls="commercial" role="tab" data-toggle="tab">Commercials</a></li>';
			}
			if(!empty($music_videos)) {
				echo '<li role="presentation"><a href="#music" aria-controls="music" role="tab" data-toggle="tab">Music Videos</a></li>';
			}
		echo '</ul>';
	echo '</header>';
} ?>