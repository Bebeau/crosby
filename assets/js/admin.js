var ajaxurl = meta_image.ajaxurl;

var init = {
	onReady: function() {
		init.saveVideo();
		init.removeVideo();
		init.onSelectChange();
		init.lockPhotos();
		init.subCatOrdering();
		init.videoOrdering();
	},
	getURLvar: function(variable) {
		var query = window.location.search.substring(1);
		var vars = query.split("&");
		for (var i=0;i<vars.length;i++) {
		       var pair = vars[i].split("=");
		       if(pair[0] == variable){return pair[1];}
		}
		return(false);
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
            var postID = jQuery(this).parent().parent().attr("data-post");
            var video_type = jQuery(this).parent().parent().attr("data-type");
            var key = jQuery(this).parent().attr("data-key");
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
    saveCatOrder: function(order, postID) {
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
    subCatOrdering: function() {
    	jQuery( "#sortable" ).sortable({
			placeholder: "ui-state-highlight",
			// Do callback function on jquery ui drop
			update: function( event, ui ) {
				order = [];
				jQuery("#sortable tr").each(function() {
					order.push(jQuery(this).attr("data-order"));
				});
				var postID = jQuery('#sortable').attr("data-post");
				init.saveCatOrder(order, postID);
			}
		});
		jQuery( "#sortable" ).disableSelection();

		order = [];
        jQuery("#sortable tr").each(function() {
            order.push(jQuery(this).attr("data-order"));
        });
        var postID = jQuery('#sortable').attr("data-post");
        init.saveCatOrder(order, postID);
    },
    saveVideoOrder: function(order, type, postID) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
            	type: type,
            	order : order,
            	postID: postID,
                action: 'setVideoOrder'
            },
            dataType: 'JSON'
        });
    },
    videoOrdering: function() {
    	jQuery( ".videoSort" ).sortable({
			placeholder: "ui-state-highlight",
			// Do callback function on jquery ui drop
			update: function( event, ui ) {
				var order = [];
				jQuery(".videoSort li").each(function() {
					order.push({
						id: jQuery(this).attr("data-order"),
						type: jQuery(this).attr("data-video")
					});
				});
				var postID = jQuery('.videoSort').attr("data-post");
				var videoType = jQuery('.videoSort').attr("data-type");
				init.saveVideoOrder(order, videoType, postID);
			}
		});
		jQuery( ".videoSort" ).disableSelection();
    }
};

jQuery(document).ready(function() {
	init.onReady();
});