// columnizer
!function($){var e="columnizer-original-dom";$.fn.columnize=function(t){function i(e,t){try{e.append(t)}catch(i){e[0].appendChild(t[0])}}this.each(function(){var t=$(this);t.data(e,t.clone(!0,!0))}),this.cols=[],this.offset=0,this.before=[],this.lastOther=0,this.prevMax=0,this.debug=0,this.setColumnStart=null,this.elipsisText="";var s={width:400,columns:!1,buildOnce:!1,overflow:!1,doneFunc:function(){},target:!1,ignoreImageLoading:!0,columnFloat:"left",lastNeverTallest:!1,accuracy:!1,precise:!1,manualBreaks:!1,cssClassPrefix:"",elipsisText:"...",debug:0};return t=$.extend(s,t),"string"==typeof t.width&&(t.width=parseInt(t.width,10),isNaN(t.width)&&(t.width=s.width)),"function"==typeof t.setColumnStart&&(this.setColumnStart=t.setColumnStart),"string"==typeof t.elipsisText&&(this.elipsisText=t.elipsisText),t.debug&&(this.debug=t.debug),t.setWidth||(t.precise?t.setWidth=function(e){return 100/e}:t.setWidth=function(e){return Math.floor(100/e)}),this.each(function(){function e(e,t){var i=t?".":"";return m.length?i+m+"-"+e:i+e}function n(s,n,l,o){for(;(g||l.height()<o)&&n[0].childNodes.length;){var r=n[0].childNodes[0];if($(r).find(e("columnbreak",!0)).length)return;if($(r).hasClass(e("columnbreak")))return;i(s,$(r))}if(0!==s[0].childNodes.length){var a=s[0].childNodes,h=a[a.length-1];s[0].removeChild(h);var d=$(h);if(3==d[0].nodeType){var f=d[0].nodeValue,c=t.width/18;t.accuracy&&(c=t.accuracy);for(var u,m=null;l.height()<o&&f.length;){var v=f.indexOf(" ",c);u=-1!=v?f.substring(0,v):f,m=document.createTextNode(u),i(s,$(m)),f=f.length>c&&-1!=v?f.substring(v):""}if(l.height()>=o&&null!==m&&(s[0].removeChild(m),f=m.nodeValue+f),!f.length)return!1;d[0].nodeValue=f}return n.contents().length?n.prepend(d):i(n,d),3==d[0].nodeType}}function l(t,s,o,r){if(!t.contents(":last").find(e("columnbreak",!0)).length&&!t.contents(":last").hasClass(e("columnbreak"))&&s.contents().length){var a=s.contents(":first");if("undefined"==typeof a.get(0)||1!=a.get(0).nodeType)return;var h=a.clone(!0);if(a.hasClass(e("columnbreak")))i(t,h),a.remove();else if(g)i(t,h),a.remove();else if(1==h.get(0).nodeType&&!h.hasClass(e("dontend")))if(i(t,h),h.is("img")&&o.height()<r+20)a.remove();else if(a.hasClass(e("dontsplit"))&&o.height()<r+20)a.remove();else if(h.is("img")||a.hasClass(e("dontsplit")))h.remove();else{if(h.empty(),n(h,a,o,r))a.addClass(e("split"));else{if(a.addClass(e("split")),"OL"==a.get(0).tagName){var d=h.get(0).childElementCount+h.get(0).start;a.attr("start",d+1)}a.children().length&&l(h,a,o,r)}if(0===h.get(0).childNodes.length)h.remove(),a.removeClass(e("split"));else if(1==h.get(0).childNodes.length){var f=h.get(0).childNodes[0];if(3==f.nodeType){var c=/\S/,u=f.nodeValue;c.test(u)||(h.remove(),a.removeClass(e("split")))}}}}}function o(){if(!h.data("columnized")||1!=h.children().length){if(h.data("columnized",!0),h.data("columnizing",!0),h.empty(),h.append($("<div class='"+e("first")+" "+e("last")+" "+e("column")+" ' style='width:100%; float: "+t.columnFloat+";'></div>")),$col=h.children().eq(h.children().length-1),$destroyable=f.clone(!0),t.overflow){for(targetHeight=t.overflow.height,n($col,$destroyable,$col,targetHeight),$destroyable.contents().find(":first-child").hasClass(e("dontend"))||l($col,$destroyable,$col,targetHeight);$col.contents(":last").length&&r($col.contents(":last").get(0));){var s=$col.contents(":last");s.remove(),$destroyable.prepend(s)}for(var o="",a=document.createElement("DIV");$destroyable[0].childNodes.length>0;){var d=$destroyable[0].childNodes[0];if(d.attributes)for(var c=0;c<d.attributes.length;c++)0===d.attributes[c].nodeName.indexOf("jQuery")&&d.removeAttribute(d.attributes[c].nodeName);a.innerHTML="",a.appendChild($destroyable[0].childNodes[0]),o+=a.innerHTML}var u=$(t.overflow.id)[0];u.innerHTML=o}else i($col,$destroyable.contents());h.data("columnizing",!1),t.overflow&&t.overflow.doneFunc&&t.overflow.doneFunc(),t.doneFunc()}}function r(t){return 3==t.nodeType?/^\s+$/.test(t.nodeValue)&&t.previousSibling?r(t.previousSibling):!1:1!=t.nodeType?!1:$(t).hasClass(e("dontend"))?!0:0===t.childNodes.length?!1:r(t.childNodes[t.childNodes.length-1])}function a(){if(v=0,c!=h.width()){c=h.width();var s=Math.round(h.width()/t.width),a=t.width,u=t.height;if(t.columns&&(s=t.columns),g&&(s=f.find(e("columnbreak",!0)).length+1,a=!1),1>=s)return o();if(!h.data("columnizing")){h.data("columnized",!0),h.data("columnizing",!0),h.empty(),h.append($("<div style='width:"+t.setWidth(s)+"%; float: "+t.columnFloat+";'></div>")),N=h.children(":last"),i(N,f.clone()),d=N.height(),h.empty();var m=d/s,p=!0,b=3,y=!1;t.overflow?(b=1,m=t.overflow.height):u&&a&&(b=1,m=u,y=!0);for(var w=0;b>w&&20>w;w++){h.empty();var x,C,N,T;try{x=f.clone(!0)}catch(L){x=f.clone()}x.css("visibility","hidden");for(var M=0;s>M;M++)C=0===M?e("first"):"",C+=" "+e("column"),C=M==s-1?e("last")+" "+C:C,h.append($("<div class='"+C+"' style='width:"+t.setWidth(s)+"%; float: "+t.columnFloat+";'></div>"));for(M=0;M<s-(t.overflow?0:1)||y&&x.contents().length;){for(h.children().length<=M&&h.append($("<div class='"+C+"' style='width:"+t.setWidth(s)+"%; float: "+t.columnFloat+";'></div>")),N=h.children().eq(M),y&&N.width(a+"px"),n(N,x,N,m),l(N,x,N,m);N.contents(":last").length&&r(N.contents(":last").get(0));)T=N.contents(":last"),T.remove(),x.prepend(T);M++,0===N.contents().length&&x.contents().length?N.append(x.contents(":first")):M!=s-(t.overflow?0:1)||t.overflow||x.find(e("columnbreak",!0)).length&&s++}if(t.overflow&&!y){var O=!1,S=document.all&&-1!=navigator.appVersion.indexOf("MSIE 7.");if(O||S){for(var z="",k=document.createElement("DIV");x[0].childNodes.length>0;){var F=x[0].childNodes[0];for(M=0;M<F.attributes.length;M++)0===F.attributes[M].nodeName.indexOf("jQuery")&&F.removeAttribute(F.attributes[M].nodeName);k.innerHTML="",k.appendChild(x[0].childNodes[0]),z+=k.innerHTML}var H=$(t.overflow.id)[0];H.innerHTML=z}else $(t.overflow.id).empty().append(x.contents().clone(!0))}else if(y)h.children().each(function(t){N=h.children().eq(t),N.width(a+"px"),0===t?N.addClass(e("first")):t==h.children().length-1?N.addClass(e("last")):(N.removeClass(e("first")),N.removeClass(e("last")))}),h.width(h.children().length*a+"px");else{N=h.children().eq(h.children().length-1),x.contents().each(function(){N.append($(this))});var I=N.height(),V=I-m,W=0,q=1e7,B=0,P=!1,E=0;h.children().each(function(t){return function(i){var s=t.children().eq(i),n=s.children(":last").find(e("columnbreak",!0)).length;if(!n){var l=s.height();P=!1,W+=l,l>B&&(B=l,P=!0),q>l&&(q=l),E++}}}(h));var j=W/E;0===W?w=b:t.lastNeverTallest&&P?(v+=5,m+=30,w==b-1&&b++):B-q>30?m=j+30:Math.abs(j-m)>20?m=j:w=b}h.append($("<br style='clear:both;'>"))}h.find(e("column",!0)).find(":first"+e("removeiffirst",!0)).remove(),h.find(e("column",!0)).find(":last"+e("removeiflast",!0)).remove(),h.find(e("split",!0)).find(":first"+e("removeiffirst",!0)).remove(),h.find(e("split",!0)).find(":last"+e("removeiflast",!0)).remove(),h.data("columnizing",!1),t.overflow&&t.overflow.doneFunc(),t.doneFunc()}}}var h=$(t.target?t.target:this),d=$(this).height(),f=$("<div></div>"),c=0,u=!1,g=t.manualBreaks,m=s.cssClassPrefix;"string"==typeof t.cssClassPrefix&&(m=t.cssClassPrefix);var v=0;if(i(f,$(this).contents().clone(!0)),!t.ignoreImageLoading&&!t.target&&!h.data("imageLoaded")&&(h.data("imageLoaded",!0),$(this).find("img").length>0)){var p=function(e,s){return function(){e.data("firstImageLoaded")||(e.data("firstImageLoaded","true"),i(e.empty(),s.children().clone(!0)),e.columnize(t))}}($(this),f);return $(this).find("img").one("load",p),void $(this).find("img").one("abort",p)}h.empty(),a(),t.buildOnce||$(window).resize(function(){t.buildOnce||(h.data("timeout")&&clearTimeout(h.data("timeout")),h.data("timeout",setTimeout(a,200)))})})},$.fn.uncolumnize=function(){this.each(function(){var t=$(this),i;(i=t.data(e))&&t.replaceWith(i)})},$.fn.renumberByJS=function(e,t,i,s){if(this.setList=function(e,t,i){var s=this.before.parents(),n;if(n=$(e[this.offset-1]).find(">*"),n.last()[0].tagName!=i.toUpperCase())return this.debug&&console.log("Last item in previous column, isn't a list..."),0;n=n.length;var l=1;if(l=this.lastOther<=0?this.before.children().length+1:$(s[this.lastOther]).children().length+1,$(e[this.offset]).find(i+":first li.split").length){var o=$(e[this.offset-1]).find(i+":last li:last");if(""===this.elipsisText||$(e[this.offset-1]).find(i+":last ~ div").length||$(e[this.offset-1]).find(i+":last ~ p").length);else if(0==$(o).find("ul, ol, dl").length){var r=o.last().text(),a=r.length;";"==r.substring(a-1)?r.substring(a-4)!=this.elipsisText+";"&&(r=r.substring(0,a-1)+this.elipsisText+";"):r.substring(a-3)!=this.elipsisText&&(r+=this.elipsisText),o.last().text(r)}0==$(e[this.offset]).find(i+":first >li.split >"+i).length&&l--}if(1==n&&(l+=this.prevMax),this.nest>1){this.debug&&console.log("Supposed to be a nested list...decr"),l--;var h=$(e[this.offset-1]).find(i+":first li.split:first");h.length>0&&(this.debug&&console.log("Previous column started with a split item, so that count is one less than expected"),l--),h=$(e[this.offset]).find(i+":first li:first").clone(),h.children().remove(),$.trim(h.text()).length>0&&(this.debug&&console.log("If that was a complete list in the previous column, don't decr."),l++,0==$(e[this.offset-1]).find(">"+i+":last ").children().length&&(this.debug&&console.log("unless that was empty, in which case revert"),l--))}else{var h=$(e[this.offset]).find(i+":first li:first "+i+".split li.split");h.length>0&&(this.debug&&console.log("[Nested] Column started with a split item, so that count is one less than expected"),l--)}return this.debug&&console.log("Setting the start value to "+l+" ("+this.prevMax+")"),l>0&&("function"==typeof this.setColumnStart?this.setColumnStart(t,l):t.attr("start",l)),0},"undefined"==typeof i&&(i=!1),"undefined"==typeof s&&(s=!1),!i&&!s)throw"renumberByJS(): Bad param, must pass an id or a class";var n="";this.prevMax=1,n=s?"."+s:"#"+i;var l=e.toLowerCase(),o=e.toUpperCase();for(this.cols=$(n),this.debug&&console.log("There are "+this.cols.length+" items, looking for "+l),this.before=$(this.cols[0]).find(l+":last"),this.prevMax=this.before.children().length,this.offset=1;this.offset<this.cols.length;this.offset++)if(this.debug&&console.log("iterating "+this.offset+"...[of "+this.cols.length+"]"),this.offset%t!=0){if(this.before=$(this.cols[this.offset-1]).find(l+":last"),this.before.length){this.debug&&console.log("Have some "+e+" elements in the previous column");var r=$(this.cols[this.offset]).find(l+":first"),a=$(this.cols[this.offset]).find("*:first");if(a[0]!==r[0])continue;var h=this.before.parents();this.lastOther=0;for(var d=!1;this.lastOther<h.length;this.lastOther++)if(h[this.lastOther].tagName!=o&&"LI"!=h[this.lastOther].tagName){d=!0,this.lastOther--;break}this.nest=1,$(this.cols[this.offset]).find(">"+l+":first li "+l+":first").length&&(this.nest=2),this.setList(this.cols,r,l),this.lastOther--,r=$(this.cols[this.offset]).find(l+":first li "+l+":first"),r.length&&(this.before=$(this.cols[this.offset-1]).find(">"+l+":last li "+l+":last"),this.prevMax=0,this.nest=1,this.setList(this.cols,r,l));var f=$(this.cols[this.offset-1]).find(">"+l+":last");this.prevMax=f.children().length}}else this.debug&&console.log("First column (in theory..)"),this.prevMax=1;return 0}}(jQuery);
// check to make sure it is not loaded on mobile device
var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);

var ajaxurl = meta_image.ajaxurl;

var init = {
	onReady: function() {
		init.loaded();
		init.navHover();
		init.videoModal();
		init.photoModal();
		init.activeTab();
		init.navSlideIn();
		init.linkPatch();
		// init.pageAjax();
		// init.stopClick();
		init.subNavCollapse();
		if(isMobile) {
			init.mobileHoverFix();
			init.mobileFilter();
		} else {
			init.pageIn();
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
	getImages: function(slug,name,id) {
		jQuery.ajax({
            url: ajaxurl,
            type: "GET",
            data: {
                name: name,
                slug: slug,
                id: id,
                action: 'getImages'
            },
            dataType: 'html',
            success : function(data){
                var $data = jQuery(data);
                if($data.length && !jQuery('.pane#'+slug).length ) {
                    // add data to #blog-listing #content
                    jQuery(".tab-content").append($data);
                }
            },
            error : function(jqXHR, textStatus, errorThrown) {
                window.alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
	},
	activeTab: function() {
		jQuery('.single .portfolio-menu ul li:nth-child(2) .tab').tab('show');
		jQuery('.tab-content').addClass("in");
		jQuery('.portfolio-menu ul li a').click(function(e){
			e.preventDefault();

			// scroll to top of page
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");

			var tab = jQuery(this).attr("href");
			var slug = jQuery(this).attr("href").replace("#", "");
			var name = jQuery(this).text();
			var id = jQuery(this).attr("data-post");

			jQuery('.portfolio-menu ul li').removeClass("active");
			jQuery(this).parent().addClass("active");

			if(!jQuery(this).hasClass("info")) {
				jQuery('.tab-content').removeClass("in");
				init.getImages(slug, name, id);
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
	mobileFilter: function() {
		jQuery('.imageWrap').each(function(){
			jQuery(this).columnize({columns: 2 });
		});
		jQuery('.pane').css("display", "none");
		jQuery('.active.pane').css("display", "block");
		jQuery('.dropdown-menu a').click(function(){
			var id = jQuery(this).attr('href');
			jQuery('.dropdown-menu li').removeClass("active");
			setTimeout(
				function(){
					jQuery('.pane').css("display", "none");
					jQuery('.active.pane').css("display", "block");
				}, 250
			);
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