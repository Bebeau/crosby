var ajaxurl = meta_image.ajaxurl;

var init = {
	onReady: function() {
		init.saveVideo();
		init.removeVideo();
		init.onSelectChange();
		init.lockPhotos();
		init.subOrder();
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
            init.removeVideo(postID, key, video_type);
            jQuery(this).parent().remove();
        });
	},
	setAgent: function(agentID, postID) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
            	postID: postID,
                agentID: agentID,
                action: 'assignAgent'
            },
            dataType: 'html',
            error : function(jqXHR, textStatus, errorThrown) {
                window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    },
    onSelectChange: function() {
    	jQuery('.assignedAgent').change(function() {
    		var postID = jQuery(this).attr("data-post");
            var agentID = jQuery('.assignedAgent').val();
            init.setAgent(agentID, postID);
        });
        jQuery('.email-button').click(function(e){
        	e.preventDefault();
        	jQuery('.emails').append('<input type="text" name="cc_emails[]" placeholder="email@address.." />');
        });
    },
    lockPhotos: function() {
    	jQuery(document).on("DOMNodeInserted", function(){
	        // Lock uploads to "Uploaded to this post"
	        jQuery('select.attachment-filters [value="uploaded"]').attr( 'selected', true ).parent().trigger('change');
	    });
    },
    saveOrder: function(order, postID) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
            	order : order,
            	postID: postID,
                action: 'setOrder'
            },
            dataType: 'JSON'
        });
    },
    subOrder: function() {
		jQuery( "#sortable" ).sortable({
			placeholder: "ui-state-highlight",
			// Do callback function on jquery ui drop
			update: function( event, ui ) {
				order = [];
				jQuery("#sortable tr").each(function() {
					order.push(jQuery(this).attr("data-order"));
				});
				var postID = jQuery('#sortable').attr("data-post");
				init.saveOrder(order, postID);
			}
		});
		jQuery( "#sortable" ).disableSelection();
    }
};

jQuery(document).ready(function() {
	init.onReady();
});