<?php get_header(); ?>

	<div class="col-md-offset-3 col-lg-offset-2 col-sm-offset-3 col-sm-9 col-md-9" id="singlePage">

		<?php if (have_posts()) : while (have_posts()) : the_post();
			the_title('<h1>','</h1>');
			echo '<div class="row">';
				if(has_post_thumbnail()) {
					echo '<div class="col-sm-4">';
						the_post_thumbnail();
					echo '</div>';
				}
				echo '<div class="col-sm-8">';
					the_content();
				echo '</div>';
			echo '</div>';
		endwhile; endif; ?>

	</div>

<?php get_footer(); ?>