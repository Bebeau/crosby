<?php 

/*
 * Template Name: Contact
 */

get_header(); ?>

	<div id="singlePage" class="contentwrap contact">
		<div class="row">

			<div class="col-md-6">
				<?php

					$address = get_option( 'crosby_address', '' );
					$address2 = get_option( 'crosby_address2', '' );
					$phone = get_option( 'crosby_phone', '' );
					$fax = get_option( 'crosby_fax', '' );

					echo '<h1>'.get_the_title().'</h1>';

					echo '<address><p><strong>'.get_bloginfo("name").'</strong><br />'.$address.'<br />'.$address2.'</p></address>';
					echo '<p class="hidden-xs">tel: <strong>'.$phone.'</strong><br />';
					echo '<p class="visible-xs">tel: <strong><a href="tel:'.$phone.'">'.$phone.'</a></strong><br />';

					echo 'fax: <strong>'.$fax.'</strong></p>';

					echo '<div class="agentwrap">';
						echo '<h2>Agents</h2><span></span>';
					echo '</div>';

					$args = array(
						'blog_id'      => $GLOBALS['blog_id'],
						'role'         => 'agent',
						'role__in'     => array(),
						'role__not_in' => array(),
						'meta_key'     => '',
						'meta_value'   => '',
						'meta_compare' => '',
						'meta_query'   => array(),
						'date_query'   => array(),        
						'include'      => array(4,2,3),
						'exclude'      => array(),
						'orderby'      => 'login',
						'order'        => 'include',
						'offset'       => '',
						'search'       => '',
						'number'       => '',
						'count_total'  => false,
						'fields'       => 'all',
						'who'          => ''
					); 
					
					$agents = get_users($args);

					foreach($agents as $agent) {

						$userID = $agent->ID;
						$email = $agent->user_email;

						$facebook = get_user_meta($userID, "agent_fb_url", true);
						$twitter = get_user_meta($userID, "agent_twit_url", true);
						$instagram = get_user_meta($userID, "agent_ig_url", true);

						$phone = get_user_meta($userID, "phone", true);

						echo '<div class="row agent">';
							echo '<div class="col-xs-6 agent-image" style="background:url('.$agent->agent_image.')no-repeat scroll top center / cover" ></div>';
								echo '<div class="col-xs-6 agent-info">';
									echo '<div class="outer"><div class="inner">';
										echo '<h3>'.$agent->display_name.'</h3>';
										if(!empty($phone)) {
											echo '<div class="phone hidden-xs">'.$phone.'</div>';
											echo '<div class="phone visible-xs"><a href="tel:'.$phone.'">'.$phone.'</a></div>';
										}
										if(!empty($facebook)) {
											echo '<div class="social">';
												if(!empty($facebook)) {
													echo '<a href="'.$facebook.'"><i class="fa fa-facebook"></i></a>';
												}
												if(!empty($twitter)) {
													echo '<a href="'.$twitter.'"><i class="fa fa-twitter"></i></a>';
												}
												if(!empty($instagram)) {
													echo '<a href="'.$instagram.'"><i class="fa fa-instagram"></i></a>';
												}
											echo '</div>';
											echo '<div class="info">';
												if(!empty($email)) {
													echo '<a class="contact-btn" href="mailto:'.$email.'">Contact</a>';
												}
											echo '</div>';
										}
									echo '</div></div>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					}
				?>
			</div>

		</div>
	</div>

<?php get_footer(); ?>