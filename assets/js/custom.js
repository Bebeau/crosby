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
		init.pageIn();
		// init.pageAjax();
		// init.stopClick();
		init.subNavCollapse();
		if(isMobile) {
			init.mobileHoverFix();
		}
	},
	mobileHoverFix: function() {
		// Check if the device supports touch events
		if('ontouchstart' in document.documentElement) {
			// Loop through each stylesheet
			for(var sheetI = document.styleSheets.length - 1; sheetI >= 0; sheetI--) {
				var sheet = document.styleSheets[sheetI];
				// Verify if cssRules exists in sheet
				if(sheet.cssRules) {
					// Loop through each rule in sheet
					for(var ruleI = sheet.cssRules.length - 1; ruleI >= 0; ruleI--) {
						var rule = sheet.cssRules[ruleI];
						// Verify rule has selector text
						if(rule.selectorText) {
							// Replace hover psuedo-class with active psuedo-class
							rule.selectorText = rule.selectorText.replace(":hover", ":active");
						}
					}
				}
			}
		}
		// try to keep the hover over effect on mobile links
		jQuery('a, button').each(function() {
			jQuery(this).bind('touchstart touchend', function() {
		        jQuery(this).toggleClass("hover");
		    });
		});
	},
	subNavCollapse: function() {
		jQuery('.single-portfolios .menu-item').hover(function(){
			jQuery(this).find("ul").stop().slideToggle("slow");
		});
		jQuery('.page .menu-item').hover(function(){
			jQuery(this).find("ul").stop().slideToggle("slow");
		});
		jQuery('.page .menu-item a').click(function(e){
			e.preventDefault();
		});
	},
	shrinkLogo: function() {
		if(jQuery('header').hasClass("sub") && !isMobile) {
			jQuery('header').addClass("shrink");
			jQuery('#singlePortfolio').addClass("shrink");
			jQuery('#singlePage').addClass("shrink");
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
	    jQuery('.sub-menu a').hover(function(){
	    	if(jQuery(this)[0].hasAttribute("data-image")) {
	    		var img = jQuery(this).attr('data-image');
	    		jQuery('.home_bg').fadeOut('fast', function () {
	    			jQuery('.home_bg').css('background-image','url('+img+')');
	    			jQuery('.home_bg').fadeIn();
	    		});
	    	}
	    }, function(){
	    	if(jQuery(this)[0].hasAttribute("data-image")) {
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
		jQuery('.single .portfolio-menu ul li:nth-child(2) .tab').tab('show');
		jQuery('.tab-content').addClass("in");
		jQuery('.portfolio-menu ul li a').click(function(e){
			e.preventDefault();

			var tab = jQuery(this).attr("href");
			jQuery('.portfolio-menu ul li').removeClass("active");
			jQuery(this).parent().addClass("active");

			if(!jQuery(this).hasClass("info")) {
				jQuery('.tab-content').removeClass("in");
				setTimeout(
					function(){
						jQuery('#bio').collapse('hide');
						jQuery('.pane').removeClass("active");
						jQuery(tab).addClass("active");
					}, 500
				);
				setTimeout(
					function(){
						jQuery('.tab-content').addClass("in");
					}, 800
				);
			}
		});
	},
	pageIn: function() {
		jQuery('.contentwrap').addClass("slideIn");
	},
	linkPatch: function() {
		jQuery('.sub-menu li a').click(function(e){
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