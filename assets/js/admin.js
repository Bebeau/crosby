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
	removeVideo: function(postID) {
		jQuery.ajax({
	        url: ajaxurl,
	        type: "GET",
	        data: {
	            action: 'removeVideo',
	            postID: postID
	        },
	        dataType: 'html',
	        error : function(jqXHR, textStatus, errorThrown) {
	            window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
	        }
	    });
	    jQuery(".remove-btn").click(function(e){
            e.preventDefault();
            var postID = jQuery(this).attr("data-post");
            removeVideo(postID);
        });
	}
};

jQuery(document).ready(function() {
	user.onReady();
});