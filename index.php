<?php get_header();

$cats = get_categories('category');
echo '<ul class="menu">';
	foreach($cats as $cat) {
		$catID = $cat->term_id;
		$catLink = get_category_link($catID);
		echo '<li><a href="'.$catLink.'" alt="'.$cat->name.'">'.$cat->name.'</a></li>';
		query_posts( array(
				'post_type' => 'portfolios',
				'order' 	=> 'DESC',
				'cat'		=> $catID
			)
	    );
	    if (have_posts()) : while (have_posts()) : the_post();
	    	echo '<ul>';
	    		echo '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
	    	echo '</ul>';
	    endwhile; endif;
	}
echo '</ul>';

get_footer(); ?>