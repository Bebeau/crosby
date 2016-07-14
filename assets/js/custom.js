// Handle resize of videos/images on window resize
function windowResize() {

	var width = (window.innerWidth > 0) ? window.innerWidth : screen.width,
		item = $('.full');
		// height = $(window).height();

	item.each(function(){
		if (item.width() < width ) {
			item.width(width);
			item.height('auto');
		}
		if ( item.width() > width ) {
			item.css('left', - ( ( item.width() ) - width ) / 2 );
		} else {
			item.css('left', 0);
		}
	});
}

jQuery(document).ready(function($) {

	// originally run size and carousel arrow functions
	windowResize();

});