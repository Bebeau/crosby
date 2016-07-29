<?php get_header(); ?>

	<div id="singlePage" class="contentwrap">

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