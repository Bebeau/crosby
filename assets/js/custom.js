var init = {
	onReady: function() {
		init.navHover();
		init.videoModal();
		init.photoModal();
		init.activeTab();
	},
	navHover: function() {
		var original = jQuery('.home_bg').css('background-image');
    		bg_url 	 = /^url\((['"]?)(.*)\1\)$/.exec(original);
    		console.log(bg_url);
	    jQuery('.person').hover(function(){
	    	var img = jQuery(this).attr('data-image');
	    	if(img) {
	    		jQuery('.home_bg').addClass("fade");
	    		jQuery('.home_bg').css('background-image','url('+img+')');
				jQuery('.home_bg').removeClass("fade");
	    	}
	    }, function(){
	    	console.log("mouseout");
	    	jQuery('.home_bg').css('background-image','url('+bg_url[2]+')');
	    });
	},
	videoModal: function() {
		// Handling The YouTube Videos play in modals. Basically refreshing the src using this jquery to reload the video
		jQuery('.singlevideo').click( function(e) {

			e.preventDefault();

			var videoID = jQuery(this).attr("data-video");
			var videoURL = 'https://www.youtube.com/embed/'+videoID+'?autoplay=1';

			jQuery('.videoFrame').attr("src", videoURL);

			jQuery('#videomodal').on('hidden.bs.modal', function() {
		    	jQuery('.videoFrame').removeAttr('src');
		    });

		});
	},
	photoModal: function() {
		// Handling The YouTube Videos play in modals. Basically refreshing the src using this jquery to reload the video
		jQuery('.singlephoto').click( function(e) {

			e.preventDefault();

			var photoURL = jQuery(this).attr("data-photo");

			jQuery('#photomodal .modal-body').append('<img src="'+photoURL+'" alt="" />');

			jQuery('#photomodal').on('hidden.bs.modal', function() {
		    	jQuery('.modal-body img').remove();
		    });

		});
	},
	activeTab: function() {
		jQuery('.menu ul li:first-child').trigger("click");
	}

};

jQuery(document).ready(function() {
	init.onReady();
});