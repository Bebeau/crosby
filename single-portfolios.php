<?php get_header(); 

	// get current user id
	global $post;

	$commercials 	= get_post_meta($post->ID, 'commercials', true);
	$music_videos 	= get_post_meta($post->ID, 'music_videos', true);
	
?>
	<div id="singlePortfolio">

		<?php if (have_posts()) : while (have_posts()) : the_post();
		    
		    echo '<div class="row">';
			    // get title
		    	echo '<a role="button" data-toggle="collapse" href="#bio" aria-expanded="false" aria-controls="bio" class="info collapsed">';
			    	echo '<h1 class="col-sm-12">';
				    echo '<i class="fa fa-plus"></i> ';
				    	the_title();
				    echo '</h1>';
				echo '</a>';
			echo '</div>';

		    echo '<div class="tab-content">';

		    	echo '<div class="collapse clearfix" id="bio">';
			    	if(has_post_thumbnail()) {
			    		echo '<div class="col-sm-4">';
			    			the_post_thumbnail();
			    		echo '</div>';
			    	}
		    		echo '<div class="col-sm-8">';
		    			the_content();
		    		echo '</div>';
		    	echo '</div>';

		    	$terms = get_terms( array(
				    'taxonomy' => 'image_type',
				    'hide_empty' => true,
				    'exclude' => 22
				) );
				if($terms) {
					foreach ($terms  as $term ) {
						if(has_Images($term->slug)) {
							list_Images($term->slug, $term->name);
						}
					}
				}
    		
			    // get commercials
			    if(!empty($commercials)) {
			    	echo '<div class="video-pane pane" id="commercial">';
			    		echo '<h3 class="termName">// Commercials</h3>';
				    	foreach($commercials as $video) {
					    	echo '<div class="col-sm-4 single">';
						    	echo '<a href="#videomodal" data-toggle="modal" class="singlevideo" data-video="'.$video.'" style="background:url(https://i1.ytimg.com/vi/'.$video.'/hqdefault.jpg) no-repeat scroll center / cover;">';
						    		echo '<div class="playwrap"><i class="fa fa-play"></i></div>';
						    	echo '</a>';
					    	echo '</div>';
					    }
					echo '</div>';
			    }

			    // get music videos
			    if(!empty($music_videos)) {
			    	echo '<div class="video-pane pane" id="music">';
			    		echo '<h3 class="termName">// Music Videos</h3>';
				    	foreach($music_videos as $video) {
					    	echo '<div class="col-sm-4 single">';
					    		echo '<a href="#videomodal" data-toggle="modal" class="singlevideo" data-video="'.$video.'" style="background:url(https://i1.ytimg.com/vi/'.$video.'/hqdefault.jpg) no-repeat scroll center / cover;">';
					    			echo '<div class="playwrap"><i class="fa fa-play"></i></div>';
					    		echo '</a>';
					    	echo '</div>';
					    }
					echo '</div>';
			    }


			echo '</div>';

	    endwhile; endif; ?>

	</div>

	<div class="modal fade in bs-example-modal-lg" id="videomodal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<i class="fa fa-times-circle" data-dismiss="modal"></i>
					<iframe class="videoFrame" width="640" height="360" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade in bs-example-modal-lg" id="photomodal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<i class="fa fa-times-circle" data-dismiss="modal"></i>
				</div>
			</div>
		</div>
	</div>

<?php 

get_footer(); ?>