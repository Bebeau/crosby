<?php get_header(); 

	// get current user id
	global $post;

	$videos = get_post_meta($post->ID, 'videos', true);
	$order = get_post_meta($post->ID, 'sub_nav_order', true);
	
?>
	<div id="singlePortfolio" class="contentwrap">

		<?php if (have_posts()) : while (have_posts()) : the_post();
		    
		    echo '<div class="row">';
			    // get title
		    	echo '<a role="button" data-toggle="collapse" href="#bio" aria-expanded="false" aria-controls="bio" class="info collapsed">';
			    	echo '<h1 class="col-sm-12">';
				    echo '<span class="plus"></span>';
				    	the_title();
				    echo '</h1>';
				echo '</a>';
			echo '</div>';

		    echo '<div class="tab-content">';

		    	echo '<div class="collapse clearfix" id="bio">';
			    	if(has_post_thumbnail()) {
			    		echo '<div class="col-sm-4">';
			    			the_post_thumbnail('large');
			    		echo '</div>';
			    	}
		    		echo '<div class="col-sm-8">';
		    			the_content();
		    		echo '</div>';
		    	echo '</div>';

		    	$terms = get_terms( array(
		    		'post_parent' => $post->ID,
				    'taxonomy' => 'image_type',
				    'hide_empty' => true,
				    'exclude' => 22, 
	                'orderby' => 'include',
	                'include' => $order
				));

				if($terms) {
					foreach ($terms as $term ) {
						if(has_Images($term->slug) && $term->name !== "Flasher") {
							list_Images($term->slug, $term->name, $post->ID);
							break;
						}
					}
				}
    		
			    // get videos
			    if(!empty($videos)) {
			    	echo '<div class="video-pane pane" id="videos">';
			    		echo '<h3 class="termName">// Videos</h3>';
				    	foreach($videos as $video) {
					    	echo '<div class="col-sm-4 single hidden-xs">';
					    	if($video['type'] === "youtube") {
						    	echo '<a href="#videomodal" data-toggle="modal" class="singlevideo" data-type="youtube" data-video="'.$video['id'].'" style="background:url(https://i1.ytimg.com/vi/'.$video['id'].'/hqdefault.jpg) no-repeat scroll center / cover;">';
						    		echo '<div class="playwrap"><i class="fa fa-play"></i></div>';
						    	echo '</a>';
						    } elseif($video['type'] === 'vimeo') {
						    	$imgid = $video['id'];
			                    $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
			                    $thumb = $hash[0]['thumbnail_large'];
						    	echo '<a href="#videomodal" data-toggle="modal" class="singlevideo" data-type="vimeo" data-video="'.$video['id'].'" style="background:url('.$thumb.') no-repeat scroll center / cover;">';
						    		echo '<div class="playwrap"><i class="fa fa-play"></i></div>';
						    	echo '</a>';
						    }
					    	echo '</div>';

					    	echo '<div class="col-sm-4 single visible-xs">';
					    	if($video['type'] === "youtube") {
						    	echo '<iframe src="https://www.youtube.com/embed/'.$video['id'].'" class="videoFrame" width="100%" frameborder="0" allowfullscreen></iframe>';
						    } elseif($video['type'] === 'vimeo') {
						    	echo '<iframe src="https://player.vimeo.com/video/'.$video['id'].'" class="videoFrame" width="100%" frameborder="0" allowfullscreen></iframe>';
						    }
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