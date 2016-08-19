<!-- add social media icons if url is posted in general settings within WP admin. -->
<?php if( get_option('facebook') || get_option('twitter') || get_option('instagram') || get_option('pinterest') || get_option('youtube') ) { ?>
	<div class="socialWrap">
		<?php if( get_option('facebook')) { ?>
			<a class="facebook" href="<?php echo get_option('facebook'); ?>" target="_blank">
				<i class="facebook"></i>
			</a>
		<?php } ?>
		<?php if( get_option('twitter')) { ?>
			<a class="twitter" href="<?php echo get_option('twitter'); ?>" target="_blank">
				<i class="twitter"></i>
			</a>
		<?php } ?>
		<?php if( get_option('instagram')) { ?>
			<a class="instagram" href="<?php echo get_option('instagram'); ?>" target="_blank">
				<i class="instagram"></i>
			</a>
		<?php } ?>
	</div>
<?php } ?>