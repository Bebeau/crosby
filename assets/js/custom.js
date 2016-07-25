// check to make sure it is not loaded on mobile device
var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);

var init = {
	onReady: function() {
		init.loaded();
		init.navHover();
		init.videoModal();
		init.photoModal();
		init.activeTab();
		init.navSlideIn();
		init.linkPatch();
		init.portfolioIn();
		// init.pageAjax();
		// init.stopClick();
	},
	shrinkLogo: function() {
		if(jQuery('header').hasClass("sub") && !isMobile) {
			jQuery('header').addClass("shrink");
			jQuery('#singlePortfolio').addClass("shrink");
		}
	},
	loaded: function() {
		setTimeout(
			function(){
				jQuery("#loader").addClass("loaded");
			}, 250
		);
		setTimeout(
			function(){
				jQuery("#loader").remove();
			}, 1000
		);
	},
	navHover: function() {
		var original = jQuery('.home_bg').css('background-image');
    		bg_url 	 = /^url\((['"]?)(.*)\1\)$/.exec(original);
	    jQuery('.person').hover(function(){
	    	var img = jQuery(this).attr('data-image');
	    	if(img != "") {
	    		jQuery('.home_bg').fadeOut('fast', function () {
	    			jQuery('.home_bg').css('background-image','url('+img+')');
	    			jQuery('.home_bg').fadeIn();
	    		});
	    	}
	    }, function(){
	    	if(img != "") {
		    	jQuery('.home_bg').fadeOut('fast', function () {
			    	jQuery('.home_bg').css('background-image','url('+bg_url[2]+')');
			    	jQuery('.home_bg').fadeIn();
			    });
		    }
	    });
	},
	videoModal: function() {
		// Handling The YouTube Videos play in modals. Basically refreshing the src using this jquery to reload the video
		jQuery('.singlevideo').click( function(e) {

			e.preventDefault();

			var videoID = jQuery(this).attr("data-video");
			var type = jQuery(this).attr("data-type");
			
			if(type === "youtube") {
				var yt_URL = 'https://www.youtube.com/embed/'+videoID+'?autoplay=1';
				jQuery('.videoFrame').attr("src", yt_URL);
			} else {
				var vimeo_URL = 'https://player.vimeo.com/video/'+videoID+'?autoplay=1';
				jQuery('.videoFrame').attr("src", vimeo_URL);
			}

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
		jQuery('.single .menu ul li:nth-child(2) a').tab('show');
		jQuery('.tab-content').addClass("in");
		jQuery('.menu ul li a').click(function(e){
			e.preventDefault();

			var tab = jQuery(this).attr("href");
			jQuery('.menu ul li').removeClass("active");
			jQuery(this).parent().addClass("active");

			if(!jQuery(this).hasClass("info")) {
				jQuery('.tab-content').removeClass("in");
				setTimeout(
					function(){
						jQuery('.pane').removeClass("active");
						jQuery(tab).addClass("active");
						jQuery('#bio').collapse('hide');
						jQuery('.tab-content').addClass("in");
					}, 500
				);
			}
		});
	},
	portfolioIn: function() {
		jQuery('#singlePortfolio').addClass("slideIn");
	},
	linkPatch: function() {
		jQuery('header.home .menu ul li a').click(function(e){
			e.preventDefault();
			init.navSlideOut();
			var url = jQuery(this).attr("href");
			setTimeout(
				function(){
					jQuery(location).attr('href',url);
				}, 1250
			);
		});
	},
	navSlideIn: function() {
		setTimeout(
			function(){
				jQuery('#logo').addClass("slideIn");
			}, 250
		);
		setTimeout(
			function(){
				jQuery('.menu li').each(function(e){
					jQuery(this).delay(50*e).queue(function(){
	    				jQuery(this).addClass("slideIn").dequeue();
	    			});
				});

			}, 500
		);
		setTimeout(
			function(){
				init.shrinkLogo();
			}, 1500
		);
	},
	navSlideOut: function() {
		setTimeout(
			function(){
				jQuery(jQuery('.menu li').get().reverse()).each(function(e){
					jQuery(this).delay(50*e).queue(function(){
	    				jQuery(this).removeClass("slideIn").dequeue();
	    			});
				});
			}, 250
		);
		setTimeout(
			function(){
				jQuery('#logo').removeClass("slideIn");
				jQuery('.home_bg').fadeOut("slow");
			}, 1000
		);
	},
	pageAjax: function() {
		// ajax load of page clicked in header
		jQuery('.home .menu a').on('click',function(event){
			
			// Prevent the default click from occuring
			event.preventDefault();

			init.navSlideOut();
			
			// ajax page clicked
			var pageurl = jQuery(this).attr("data-link");
			var ajaxwrap = pageurl + " .wrap > *";

			jQuery.ajax({
				type 		: "GET",
				url 		: pageurl,
				dataType    : "html",
				cache		: true,
				beforeSend: function(){
			    	jQuery('.wrap').addClass("out");
			    	jQuery('header').addClass("sub");
			   	},
				success: function() { 
					jQuery(".wrap").load(ajaxwrap, function(){
						// init.onAjaxComplete();
						jQuery('.wrap').removeClass("out");
					});
					init.navSlideIn();
				},
				complete: function() {
					// to change the browser URL to the given link location
					if(pageurl !== window.location){
						window.history.pushState({path:pageurl},'',pageurl);
						// I need to add get title based on url and replace upon request
					}
				}
			});

		});
	}
};

jQuery(document).ready(function() {
	init.onReady();
});