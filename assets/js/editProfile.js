var ajaxurl = meta_image.ajaxurl;

var user = {
	onReady: function() {
		user.imageUploader();
		user.backgroundUploader();
		user.removeImage();
		user.removeBackgroundImage();
		user.imageDrop();
	},
	imageUploader: function() {
		var meta_image_frame;
     	// Runs when the image button is clicked.
	    jQuery('.upload-image').click(function(e){

	    	// Prevents the default action from occuring.
	        e.preventDefault();
	        var button = jQuery(this);
	        var elem = jQuery(this).parent();
	    	var image = elem.attr("data-img");
	    	var val = elem.attr("data-input");
	    	var userID = elem.attr("data-user");
	    	var input = jQuery('#'+val);
	    	var img = jQuery('.'+image);

	        // Sets up the media library frame
	        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
	            title: meta_image.title,
	            button: { text:  meta_image.button },
	            library: { type: 'image' },
	            multiple: true
	        });

	        // Runs when an image is selected.
	        meta_image_frame.on('select', function(){

	            // Grabs the attachment selection and creates a JSON representation of the model.
	            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
	            // Sends the attachment URL to our custom image input field.
	            img.find("img").remove();
	            img.append('<img src="'+media_attachment.url+'" alt="" />' );
	            input.val(media_attachment.url);
	            elem.append('<button class="remove-image button button-large heard-btn">Remove</button>');
	            button.remove();
	            user.saveImage( val, userID, media_attachment.url );
	        });

	        // Opens the media library frame.
	        meta_image_frame.open();
	    });
	},
	removeImage: function() {
		jQuery('.remove-image').click(function(e){
			e.preventDefault();

			var val = jQuery(this).parent().attr("data-input");
	    	var userID = jQuery(this).parent().attr("data-user");

			jQuery(this).parent().find("input").val("");
			jQuery(this).parent().find("img").remove();

			user.saveImage(val,userID,"");

			jQuery(this).parent().append('<button class="add button button-large upload-image" id="upload-logo" style="text-align:center;" data-input="agent_image" data-img="profile" data-user="<?php echo $user_id; ?>">Upload/Set Profile Image</button>');
			jQuery(this).remove();

			user.imageUploader();

		});
	},
	saveImage: function(field, userID, image) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
                imageField: field,
                fieldVal: image,
                userID: userID,
                action: 'setImage'
            },
            dataType: 'html',
            success : function() {
            	user.removeImage();
            },
            error : function(jqXHR, textStatus, errorThrown) {
                window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        }); 
    },
    backgroundUploader: function() {
		var meta_image_frame;
     	// Runs when the image button is clicked.
	    jQuery('.upload-background').click(function(e){

	    	// Prevents the default action from occuring.
	        e.preventDefault();
	        var button = jQuery(this);
	        var elem = jQuery(this).parent();
	    	var val = elem.attr("data-input");
	    	var input = jQuery('#'+val);
	    	var img = jQuery('.background');

	        // Sets up the media library frame
	        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
	            title: meta_image.title,
	            button: { text:  meta_image.button },
	            library: { type: 'image' },
	            multiple: false
	        });

	        // Runs when an image is selected.
	        meta_image_frame.on('select', function(){

	            // Grabs the attachment selection and creates a JSON representation of the model.
	            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
	            // Sends the attachment URL to our custom image input field.
	            img.find("img").remove();
	            img.append('<img src="'+media_attachment.url+'" alt="" />' );
	            input.val(media_attachment.url);
	            elem.append('<button class="remove-background button button-large">Remove</button>');
	            button.remove();
	            user.saveBackgroundImage( val, media_attachment.url );
	            user.removeBackgroundImage();
	        });

	        // Opens the media library frame.
	        meta_image_frame.open();
	    });
	},
    saveBackgroundImage: function(field, image) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
                imageField: field,
                fieldVal: image,
                action: 'setBackgroundImage'
            },
            dataType: 'html',
            error : function(jqXHR, textStatus, errorThrown) {
                window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        }); 
    },
    removeBackgroundImage: function() {
		jQuery('.remove-background').click(function(e){
			e.preventDefault();

			var val = jQuery(this).parent().attr("data-input");

			jQuery(this).parent().find("input").val("");
			jQuery(this).parent().find("img").remove();

			user.saveBackgroundImage(val,"");

			jQuery(this).parent().append('<button class="add button button-large upload-background" style="text-align:center;" data-input="custom_bg" data-img="bg">Upload/Set Background</button>');
			jQuery(this).remove();

			user.backgroundUploader();

		});
	},
    dropImage: function(field, userID, image, elem) {
        jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
                imageField: field,
                imageVal: image,
                userID: userID,
                action: 'dropImage'
            },
            processData: false,
			contentType: false,
            success: function(elem, image) {
            	jQuery('#'+elem).css('background','url('+image+') no-repeat scroll center / cover');
            },
            error : function(jqXHR, textStatus, errorThrown) {
                window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        }); 
    },
    imageDrop: function() {
        jQuery('input[type=file]').change(function(event){
        	var field = jQuery(this).attr("name");
        	var userID = jQuery(this).attr("data-user");
        	var elem = jQuery(this).attr("data-target");
            user.dropImage(field,userID,image,elem);
        });
    }
};

jQuery(document).ready(function() {
	user.onReady();
});