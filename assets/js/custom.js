var init = {
	onReady: function() {
		init.navHover();
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
	}
};

jQuery(document).ready(function() {
	init.onReady();
});