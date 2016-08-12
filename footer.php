
		<?php
			if(is_single()) {
				
				echo '<footer class="visible-xs">';

				$fb_url = get_post_meta($post->ID, 'artist_fb_url', true);
				$ig_url = get_post_meta($post->ID, 'artist_ig_url', true);
				$twit_url = get_post_meta($post->ID, 'artist_twit_url', true);

				if(!empty($fb_url) || !empty($ig_url) || !empty($twit_url)) {
					echo '<div id="artistSocial">';
						if(!empty($fb_url)) {
							echo '<a href="'.$fb_url.'"><i class="facebook"></i></a>';
						}
						if(!empty($twit_url)) {
							echo '<a href="'.$twit_url.'"><i class="twitter"></i></a>';
						}
						if(!empty($in_url)) {
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
				echo '<div class="legal">&copy; '.date("Y").' '.get_bloginfo("name").' <br />All Rights Reserved.</div>';

				echo '</footer>';
			}
		?>
    
	</body>
</html>

<?php wp_footer(); ?>  