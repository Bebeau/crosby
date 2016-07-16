var ajaxurl = meta_image.ajaxurl;

var user = {
	onReady: function() {
		user.saveVideo();
		user.removeVideo();
	},
	saveVideo: function(postID) {
	    jQuery.ajax({
	        url: ajaxurl,
	        type: "GET",
	        data: {
	            action: 'setVideo',
	            postID: postID
	        },
	        dataType: 'html',
	        error : function(jqXHR, textStatus, errorThrown) {
	            window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
	        }
	    }); 
	},
	removeVideo: function(postID, key, video_type) {
		jQuery.ajax({
	        url: ajaxurl,
	        type: "GET",
	        data: {
	            action: 'removeVideo',
	            postID: postID,
	            video_type: video_type,
	            key: key
	        },
	        dataType: 'html',
	        error : function(jqXHR, textStatus, errorThrown) {
	            window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
	        }
	    });
	    jQuery(".button-remove").click(function(e){
            e.preventDefault();
            var postID = jQuery(this).parent().attr("data-post");
            var key = jQuery(this).parent().attr("data-key");
            var video_type = jQuery(this).parent().attr("data-type");
            user.removeVideo(postID, key, video_type);
            jQuery(this).parent().remove();
        });
	}
};

jQuery(document).ready(function() {
	user.onReady();
});