
jQuery(document).ready(function() {


	/* MegaMenu */
	if(jQuery('.mainmenu #mainmenu').length==0){
		jQuery('.toplevel').attr("id","mainmenu").addClass('navigation').find('ul').first().addClass('toplevel').attr("id","menu-navigation");
		jQuery('#menu-navigation li').addClass("menu-item");
		jQuery('#menu-navigation li a').addClass("menu-link ");
	}
	
	jQuery(".sub-menu").not(':has(li)').remove();
	jQuery('.megamenu li.menu-item-has-children.hassubmenu ul.sub-menu').remove();
	jQuery('.megamenu li').removeClass('menu-item-has-children hassubmenu');
	
	jQuery('.megamenu a').hover(function() {
	  	if(jQuery(this).data("placement")=="bottom")
	  		jQuery('body').addClass("megamenuopenbottom");
	  	else
	  		jQuery('body').addClass("megamenuopentop");
	},
	function() {
	  	jQuery('body').removeClass("megamenuopenbottom").removeClass("megamenuopentop");
	});
	
	/* UTF8 Tabs*/
	jQuery('.tab-link').each(function(){
		jQuery(this).attr('href',jQuery(this).attr('href').split("%").join("_"));
	});
	jQuery('.tab-pane').each(function(){
		jQuery(this).attr('id',jQuery(this).attr('id').split("%").join("_"));
	});
	
	/* FancyBox 2 */
	addFancyBox();

	/* Responsive Menu Generation */
    menuHandler();

	window.setTimeout('jQuery("a.wpmenucart-contents").addClass("menu-link")',0);
	window.setTimeout('jQuery(".menu-link").show();',300);

	/* Responsive Select  */
	jQuery("#responsive-menu select").change(function() {
		window.location = jQuery(this).find("option:selected").val();
	});

	/* Activate Tabs */
	jQuery('.themetab a').click(function (e) {
		e.preventDefault();
		jQuery(this).tab('show');
	})

	/* Activate Carousels */
	jQuery('.carousel').carousel({ interval: 5000 })

	/* Footer Position
	setTimeout(function() {
		footerHandler();
	},1000);*/

	/* Fit Videos */
	jQuery('body').find('iframe').each(function() {
	    if (jQuery(this).is('[src]') && ((jQuery(this).attr('src').split("youtube").length>1 || jQuery(this).attr('src').split("vimeo").length>1) && !jQuery(this).parent().hasClass("tp-caption"))) {
				jQuery(this).wrap('<div class="scalevid" />');
				jQuery(this).parent().fitVids();
	    }
	});

	jQuery("body").find(".scalevid").each(function(){jQuery(this).fitVids();});
	//window.setTimeout('jQuery("body").find(".scalevid").each(function(){jQuery(this).fitVids();});',1000);

	/* Tooltips */
	jQuery("a[data-rel^='tooltip']").tooltip();
	jQuery("div.projectnav[data-rel^='tooltip']").tooltip();

	/* Popovers */
	jQuery("a[data-rel^='popover']").popover();


	/* Collapse Extra Functions */
	initCollapseExtras();

	/* Team Member Adjustement */
	initTeamMemberAdjustment();


	//Adjust Tags for WooProducts
	initWooTags();
	
	//CHANGE WOOCOMMERCE INPUT FIELD
	jQuery('.woocommerce-page input[type="number"]').each(function() {
		jQuery(this).attr('type','text');
	});

	/* ADJUST THE MENU HEIGHT AND THE CONTAINER BELOW IT */
	/*menuLineAdjustment();
	jQuery('.logo').waitForImages(function() {
		menuLineAdjustment();
	})*/

	jQuery(window).resize(function() {

		initTeamMemberAdjustment();
	})

	/*Fancy Box Build Group */
	jQuery('.portfoliofilter a').on('click',function() {

		setTimeout(function() {

		   jQuery('.portfolio.isotope').find('.isotope-item').each(function() {
		   		jQuery(this).find('.fancybox').data('rel','fancygroup');
		   		jQuery(this).find('.fancybox').attr('rel','fancygroup');
		     });
		   jQuery('.portfolio.isotope').find('.isotope-hidden').each(function() {
		   		jQuery(this).find('.fancybox').data('rel','hiddengroup');
		   		jQuery(this).find('.fancybox').attr('rel','hiddengroup');
		     });

		 },500);
	});

	/* SET SEARCH FORM FOR BBPRESS */
	var fs = jQuery('body').data('forumsearch');
	if (fs!=undefined)
		jQuery('#bbp-search-form #bbp_search').val(fs)
	jQuery('#bbp-search-form #bbp_search').addClass("prepared-input");

	/* CARE ABOUT THE INPUT FIELDS */
	initInputFields();

	/* BUDDYPRESS CLICKS  */
	initBuddyPressClicks();

	/* MENU WIDTH ADJUSTMENT
	menuWidthAdjustment();*/


	/*WAIT FOR SLIDER LOADER*/
	initSliderHeight();

	// BG HANDLIGNS
	parrallaxBG();
	videoParallaxBg();
	
	// PREPARING DATAS
	var logo = jQuery('header .logo');
	if (logo.data('mtop')== undefined) logo.data('mtop',parseInt(logo.css('marginTop'),0));
	if (logo.data('mbottom')== undefined) logo.data('mbottom',parseInt(logo.css('marginBottom')));
	
	
	// REVSLIDER && SHOWBIZ IN MEGAMENU
	jQuery('#mainmenu .megamenu').each(function() {
		var li = jQuery(this).closest('li');
		
		var showbiz = li.find('.showbiz-container');
		
		if (showbiz.length>0) {
			li.on('mouseenter',function() {
				console.log("JJ");
				setTimeout(function() {
					jQuery(window).trigger("resize");	
				},20);
			})
		}
		// REVOLUTION SLIDER IN MEGAMENU
		var slider = li.find('.rev_slider');
		if (slider.length>0) {
			var api = slider.revolution();
			api.revpause();
			li.on('mouseenter',function() {
				api.revredraw();			
				api.revresume();	
			})
			li.on('mouseleave',function() {			
				api.revpause();	
			})

		}
		
	})
	

	// STICKY HEADER
	if (jQuery('body').hasClass("stickymenu") || is_mobile())  {
		stickyHeader();
		if (jQuery(window).scrollTop()>150) {
			if (jQuery(window).width()>767) {
		   		stuckDesktopMenu();
		   		unStuckMobileMenu();
		   	 } else {
			   	 unStuckDesktopMenu();
			   	 stuckMobileMenu();
		   	 }
		}
	}
	
	// STICKY HOME SLIDER
	if (jQuery('body').hasClass('stickyhomeslider') && !is_mobile()) stickyHomeSlider();
	if (jQuery('body').hasClass('fadeoutslider') && !is_mobile()) 	fadeOutSlider();


    // STICKY FOOTER
    if (jQuery('body').hasClass('stickyfooter') && !is_mobile()) stickyFooter();

    jQuery('.centerlist_onmobile').each(function(){
	    var ul = jQuery(this);
	    var maxl = 0;
	    ul.find('li').each(function() {
		    var li = jQuery(this);
		    if (li.outerWidth(true) > maxl) maxl=li.outerWidth(true);
	    });

	    ul.css({'width':maxl+"px"});
    })
	
	
	// MOVE THE MENU TO THE LEFT
	jQuery('#mainmenu').find('ul li ul li').each(function() {
		var li = jQuery(this);
		li.hover(function() {
			var li = jQuery(this);
			var ul = li.find('>ul').first();
			if (ul.length>0) {
				ul.css({left:li.width()+"px",right:"auto"});
				var rr = ul.offset().left+ul.outerWidth();
				var winw = jQuery(window).width();				
				if (winw<rr) 
					ul.css({left:"auto",right:ul.outerWidth()+"px"});
			}
		})
	})
	
	
	// REMOVE UNNEDED PARTS OF PORTFOLIO ON MOBILE
	if (is_mobile()) {
		jQuery('.foliotextholder, .folio_underlay').remove();
	}
	
	
	if (!is_mobile()) initThemeSwitcher();


});

	/******************************
		-	INIT THEMESWITCHER	-
	********************************/
	function initThemeSwitcher() {

		
		if (!window.location.origin) {
			  window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
			}
			
		var hostname = window.location.origin;
				
		if (hostname.toLowerCase().split('themepunch').length>1 || hostname.toLowerCase().split('themeforest').length>1) {
					
			if (readCookie("velocitypreview")!=null) {
				if (jQuery('#latestcookie').data('cookie') != readCookie("velocitypreview")) {
					location.reload(true);
				}
			}
			jQuery('body').append('<div style="background:#fff; border: 1px solid #e5e5e5; position:absolute; width:200px; height:300px;top:0px;left:0px; border-radius:0px 0px 5px 0px; -webkit-border-radius:0px 0px 5px 0px; box-shadow:2px 0px 30px 1px rgba(0,0,0,0.1); -webkit-box-shadow:2px 0px 30px 1px rgba(0,0,0,0.1); -moz-box-shadow:2px 0px 30px 1px rgba(0,0,0,0.1);" id="tswitcher"></div>');
			var tw = jQuery('#tswitcher');
			tw.css({
						position:"fixed",
						top:"149px",
						left:"-252px",
						width:"200px",
						height:"auto",						
						zIndex:"1000"
			});
			var newbt = '<div style="position:relative" class="cprev_wrap"><div class="cprev_wrap_inner">'+
						'<div class="cprev" style="color:#fff; font-weight: 700; font-size:11px; padding:2px 8px; border-radius: 5px; -webkit-border-radius: 5px; margin-bottom:2px; cursor:pointer; ';
			var newbtap = '<div style="float: right;font-weight:700;font-style:italic;color: rgba(255,255,255,0.65);">';
			var newbtend = '</div></div><div class="prchekced" style="position:absolute;right:0px;top:0px"><i style="font-size:18px;" class="icon-check"></i></div></div>'+
						   '<div class="pr-image-vel" style="position:absolute;left:200px;top:0px;width:575px;height:441px;padding:5px; visibility:hidden;background:#fff;box-shadow:0px 0px 10px 2px rgba(0,0,0,0.1); -webkit-box-shadow:0px 0px 10px 2px rgba(0,0,0,0.1);"><img style="width:100%;height:100%;" id="previewimagevelocity"'
						    
			// PREV BUTTONS
			tw.append('<div class="tsbody" style="width:100%;height:auto; padding:15px;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;">'+
				  '<div style="font-size:12px; font-weight:800; color:#555; line-height:15px;margin-bottom:12px;">Velocity Style Switcher</div>'+
				  newbt+' background-color:#13c0df; " data-cookie="velocity1.css" >Ocean Blue 1 '+newbtap+'wide'+newbtend+'src="http://themepunch.com/velocity/wp-content/uploads/2014/01/switcherpreview1.jpg"></div></div>'+
				  newbt+' background-color:#119ab3; " data-cookie="velocity_blue_widedark.css" >Ocean Blue 2 '+newbtap+'wide'+newbtend+'src="http://themepunch.com/velocity/wp-content/uploads/2014/01/switcherpreview2.jpg"></div></div>'+
				  newbt+' background-color:#0d7388; " data-cookie="velocity_blue_boxeddark.css" >Ocean Blue 3 '+newbtap+'boxed'+newbtend+'src="http://themepunch.com/velocity/wp-content/uploads/2014/01/switcherpreview3.jpg"></div></div>'+
				  newbt+' background-color:#a6896d; " data-cookie="velocity_brown.css" >Woody Brown '+newbtap+'boxed'+newbtend+'src="http://themepunch.com/velocity/wp-content/uploads/2014/01/switcherpreview4.jpg"></div></div>'+
				  newbt+' background-color:#99bc56; " data-cookie="velocity_green.css" >Natural Green '+newbtap+'boxed'+newbtend+'src="http://themepunch.com/velocity/wp-content/uploads/2014/01/switcherpreview5.jpg"></div></div>'+
				  newbt+' background-color:#dd740b; " data-cookie="velocity_orange.css" >Fresh Orange '+newbtap+'wide'+newbtend+'src="http://themepunch.com/velocity/wp-content/uploads/2014/01/switcherpreview6.jpg"></div></div>'+
				  newbt+' background-color:#93adb9; " data-cookie="velocity_smoked.css" >Smoked Grey '+newbtap+'wide'+newbtend+'src="http://themepunch.com/velocity/wp-content/uploads/2014/01/switcherpreview7.jpg"></div></div>'+
				  '<div style="font-size:11px; font-weight:400; color:#333; line-height:15px;margin-top:10px;">Choose a Predefined Style. These are only Examples, via the Theme Options you can create unlimited additional styles</div>'+			  
				  '</div>');
			
			var setted = false;
			
			tw.find('.cprev_wrap').each(function() {
				var cw = jQuery(this);
				var cwi = jQuery(this).find('.cprev_wrap_inner');
				var c = cw.find('.cprev');
				var ic = cw.find('.icon-check');
				var img = cw.find('.pr-image-vel');
				var col = c.css('backgroundColor');
				

				ic.css({color:col});

				if ((readCookie("velocitypreview") == c.data('cookie') || 
					 readCookie("velocitypreview")==undefined || 
					 readCookie("velocitypreview").length<5) && !setted) {					
						TweenLite.to(c,0.3,{width:125,ease:Power2.easeInOut});
						TweenLite.to(ic.parent(),0.3,{scale:1,autoAlpha:1});						
						setted=true;
				} else {
				
					TweenLite.set(ic.parent(),{autoAlpha:0,scale:0.2});
					
					cwi.hover(function() {
						TweenLite.to(c,0.3,{width:125,ease:Power2.easeInOut});
						TweenLite.to(ic.parent(),0.3,{scale:1,autoAlpha:1});
						TweenLite.fromTo(img,0.6,{y:-100,transformPerspective:1200,transformOrigin:"50% 50%", rotationX:90},{autoAlpha:1,rotationX:0,y:0,ease:Power3.easeOut});
					},function() {
						TweenLite.to(c,0.3,{width:155,ease:Power2.easeInOut});					
						TweenLite.to(ic.parent(),0.3,{scale:0.2,autoAlpha:0,overwrite:"all"});					
						TweenLite.to(img,0.6,{y:100,transformPerspective:1200,transformOrigin:"50% 50%", autoAlpha:0,rotationX:-90,ease:Power3.easeOut});						
					})
				}
			})
			
			
			
			switch(readCookie("velocitypreview")) {
				case "velocity_brown.css":
					jQuery('body').find('.bgwithparallax_overlay').each(function() {
						var t = jQuery(this);
						if (t.css("backgroundColor").split('rgba(19, 192, 223, 0').length>1) {
							t.css({"backgroundColor":"rgba(166,137,109,0.65)"});							
						}
					});
					jQuery('body').find('.megamenu .sidebar').addClass('footer').removeClass('sidebar');
				break;
				case "velocity_green.css":
					jQuery('body').find('.bgwithparallax_overlay').each(function() {
						var t = jQuery(this);
						if (t.css("backgroundColor").split('rgba(19, 192, 223, 0').length>1) {
							t.css({"backgroundColor":"rgba(153,188,86,0.65)"});							
						}
					});
				break;
				case "velocity_orange.css":
					jQuery('body').find('.bgwithparallax_overlay').each(function() {
						var t = jQuery(this);
						if (t.css("backgroundColor").split('rgba(19, 192, 223, 0').length>1) {
							t.css({"backgroundColor":"rgba(221,116,11,0.65)"});							
						}
					});
				break;
				case "velocity_smoked.css":
					jQuery('body').find('.bgwithparallax_overlay').each(function() {
						var t = jQuery(this);
						if (t.css("backgroundColor").split('rgba(19, 192, 223, 0').length>1) {
							t.css({"backgroundColor":"rgba(147,173,185,0.65)"});							
						}
					});
					jQuery('body').find('.megamenu .sidebar').addClass('footer').removeClass('sidebar');
				break;
				case "velocity_blue_widedark.css":
					jQuery('body').find('.megamenu .sidebar').addClass('footer').removeClass('sidebar');
				break;
				case "velocity_blue_boxeddark.css":
					jQuery('body').find('.megamenu .sidebar').addClass('footer').removeClass('sidebar');
				break;

			}
			
			// COG BUTTON
			tw.append('<div class="tsbutton" style="z-index:10000;cursor:pointer;text-align:center;position:absolute;right:-41px; width:40px;top:-1px; padding:8px 0px;background:#fff;border:1px solid #e5e5e5; border-radius:0px 5px 5px 0px; -webkit-border-radius:0px 5px 5px 0px; border-left:none;" >'+
					  '<i style="font-size:20px;color:#555; vertical-align:middle; " class="icon-cog"></i>'+
					  '</div>');
					  
		
			tw.find('.tsbutton').on('click',function() {
				var tw = jQuery('#tswitcher');
				if (!tw.hasClass("opened")) {
					TweenLite.to(tw,0.5,{left:0,ease:Power3.easeOut});
					tw.addClass("opened");
				} else {				
					TweenLite.to(tw,0.5,{left:-202,ease:Power3.easeOut});
					tw.removeClass("opened");					
				}				
				
			})
														
			
			tw.find('.cprev').each(function() {
				var sw = jQuery(this);
				sw.click(function() {
					createCookie("velocitypreview", sw.data('cookie'), 0.1);
					setTimeout(function() {
						location.reload();
					},100);
				})
			});
			
			if (readCookie("velocitynotfirsttime")=="yes") {
				TweenLite.to(tw,0.5,{left:-202,ease:Power3.easeOut,delay:0})
			} else {
				createCookie("velocitynotfirsttime","yes",0.1);
				TweenLite.to(tw,0.5,{left:0,ease:Back.easeOut,delay:0})		
				tw.addClass("opened");		
			}

		}
	}
	
	function readCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
        
	function createCookie(name, value, days) {
	    var expires;
	
	    if (days) {
	        var date = new Date();
	        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
	        expires = "; expires=" + date.toGMTString();
	    } else {
	        expires = "";
	    }
	    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/velocity";
	}

	/******************************
		-	STICKY FOOTER-
	********************************/
	function stickyFooter() {

		jQuery('footer').css({position:'fixed',bottom:'0px',zIndex:0});
		
		setMBBottom();

		// SET SIZE OF MARGIN REGULARY NEW, SINCE CONTENT CAN BE LOADED INTO THE FOOTER LATER
		setInterval(function() {
			setMBBottom();
		},500);

		// SET MARGIN BOTTOM OF ALLWRAPPER AFTER RESIZE
		jQuery(window).resize(function() {
			setMBBottom();
		})

		jQuery(window).scroll(function() {
			var opa = ((jQuery(document).height()- jQuery(window).height()) - jQuery(window).scrollTop());

			opa = opa / jQuery('footer').height();
			opa = 1- opa;

			TweenLite.set(jQuery('footer .footer, footer .subfooter'),{opacity:opa});
		});

	}
	
	function setMBBottom() {
		if (jQuery(window).width()>767)
			jQuery('.allwrapper').css({marginBottom:jQuery('footer').height()+"px"});
		else
			jQuery('.allwrapper').css({marginBottom:"0px"});
		
	}

	/******************************
		-	STICKY HOME SLIDER	-
	********************************/
	function stickyHomeSlider() {

			jQuery('.homesliderwrapper').before('<div id="sliderfake"></div>');

			jQuery(window).scroll(function() {
				 if (jQuery('.homesliderwrapper').offset().top- jQuery(window).scrollTop()<=0)
					if (jQuery(window).width()>767) {
					   	jQuery('.homesliderwrapper').addClass("stucked");
						jQuery('#sliderfake').css({display:'block'});
						jQuery('#sliderfake').height(jQuery('.homesliderwrapper').outerHeight());
				   	 }
				  if (jQuery(window).width()>767) {
				   	  var opa = (1+jQuery('head').height()/jQuery(window).height()) - (jQuery(window).scrollTop()/(jQuery(window).height()/2));
					   if (opa>0)
					   	TweenLite.set(jQuery('.homesliderwrapper .rev_slider_wrapper'),{opacity:opa, display:'block'});
					   else
					   	TweenLite.set(jQuery('.homesliderwrapper .rev_slider_wrapper'),{display:'none'});
				  } else {
					  	TweenLite.set(jQuery('.homesliderwrapper .rev_slider_wrapper'),{opacity:1, display:'block'});
				  }
				});





		jQuery('#sliderfake').waypoint(function(direction) {
				if (direction=="up") {

					jQuery('.homesliderwrapper').removeClass("stucked");
					jQuery('#sliderfake').css({display:'none'});
					jQuery('#sliderfake').height(0);
				}
			},{offset:jQuery('.headertopwrap').height()});
	}


	function fadeOutSlider() {
		jQuery(window).scroll(function() {
			if (jQuery(window).width()>767) {
			   var opa = (1+jQuery('head').height()/jQuery(window).height()) - (jQuery(window).scrollTop()/(jQuery(window).height()/2));
			   if (opa>0)
			   	TweenLite.set(jQuery('.homesliderwrapper .rev_slider_wrapper'),{opacity:opa, visibility:'visible'});
			   else
			   	TweenLite.set(jQuery('.homesliderwrapper .rev_slider_wrapper'),{visibility:'visible'});
			 } else {
				 TweenLite.set(jQuery('.homesliderwrapper .rev_slider_wrapper'),{opacity:1, display:'block'});

			 }
		});
	}

	/******************************
		-	STICKY HEADER 	-
	********************************/
	function stickyHeader() {


				jQuery(window).scroll(function() {
				 if ((jQuery('.headerwrap').offset().top-jQuery(window).scrollTop()<=0) && 
				 	 (jQuery(window).scrollTop()!=0)) {
					if (jQuery(window).width()>767) {
				   		stuckDesktopMenu();
				   		unStuckMobileMenu();
				   	 } else {
					   	 unStuckDesktopMenu();
					   	 stuckMobileMenu();
				   	 }
				   	} else {
					   	unStuckDesktopMenu();
				   		unStuckMobileMenu();
				   	}

				   	if (jQuery('#headerfake'))
				   	  jQuery('#headerfake').height(jQuery('.headerwrap').outerHeight());


				});


				jQuery('.headerwrap').waypoint(function(direction){

				   if (direction=="down") {
				   	 if (jQuery(window).width()>767) {

				   		stuckDesktopMenu();
				   		unStuckMobileMenu();
				   	 } else {
					   	 unStuckDesktopMenu();
					   	 stuckMobileMenu();
				   	 }
					}
				},{offset:-1});

				jQuery('.headertopwrap').waypoint(function(direction){
					if (direction=="up") {
				   		unStuckDesktopMenu();
				   		unStuckMobileMenu();
				   	}

				}, {offset:0-jQuery('.headertopwrap').height()});

		}

	// RESIZE ALL ELEMENTS IN MENU AT SMALL MENU
	function makeLogoSmall() {

		if (jQuery(window).width()>767) {
			// CALCULATE LOGO
			var logo = jQuery('header .logo');
			if (logo.data('mtop')== undefined) logo.data('mtop',parseInt(logo.css('marginTop'),0));
			if (logo.data('mbottom')== undefined) logo.data('mbottom',parseInt(logo.css('marginBottom')));
			TweenLite.to(logo,0.3,{scale:0.7, transformOrigin:"0% 50%", marginTop:logo.data('mtop')*0.5, marginBottom:logo.data('mbottom')*0.5});

			var menu_top = logo.data('mtop')+((logo.height()*0.5)/2)-6;
			var menu_bottom = logo.data('mbottom')+((logo.height()*0.5)/2)-10;
			var header_search_top = logo.data('mtop')+((logo.height()*0.5)/2)-20;

			var hh = 0;
			jQuery('.navigation >ul>li>a').each(function() {
				var a = jQuery(this);
				if ((a.height()+menu_top+menu_bottom)>hh) hh = a.height()+menu_top+menu_bottom;
				var newmt = menu_top;
				var newmb = menu_bottom;
				if (a.closest('li').hasClass('current-menu-item') || a.closest('li').hasClass('current-menu-ancestor')) {
						newmt = newmt-2;
						newmb = newmb;
					}

				TweenLite.to(a,0.3,{paddingTop:newmt, paddingBottom:newmb});
			})

			/*jQuery('.navigation >ul>li>ul').each(function() {
				var ul =jQuery(this);
				TweenLite.to(ul,0.3,{top:hh+1});
			})*/

			TweenLite.to(jQuery('.headersearch'),0.3,{top:header_search_top});
		}


	};

	// RESET MENU SIZES EVERYWHERE
	function makeLogoNormal() {

		if (jQuery(window).width()>767) {

				// RESET LOGO
				var logo = jQuery('header .logo');
				TweenLite.to(logo,0.3,{scale:1, marginTop:logo.data('mtop'), marginBottom:logo.data('mbottom')});

				var menu_top = logo.data('mtop')+((logo.height())/2)-6;
				var menu_bottom = logo.data('mbottom')+((logo.height())/2)-10;
				var header_search_top = logo.data('mtop')+((logo.height())/2)-20;

				var hh = 0;
				jQuery('.navigation >ul>li>a').each(function() {
					var a = jQuery(this);
					if ((a.height()+menu_top+menu_bottom)>hh) hh = a.height()+menu_top+menu_bottom;
					var newmt = menu_top;
					var newmb = menu_bottom;
					if (a.closest('li').hasClass('current-menu-item') || a.closest('li').hasClass('current-menu-ancestor')) {
							newmt = newmt-2;
							newmb = newmb;
						}

					TweenLite.to(a,0.3,{paddingTop:newmt, paddingBottom:newmb});
				})
/*
				jQuery('.navigation >ul>li>ul').each(function() {
					var ul =jQuery(this);
					TweenLite.to(ul,0.3,{top:hh+1});
				})*/

				TweenLite.to(jQuery('.headersearch'),0.3,{top:header_search_top});
		}

	};

	/******************************
		-	STUCK DESKTOP MENU	-
	********************************/
	function stuckDesktopMenu() {
		if (jQuery('#headerfake').length==0) {

			jQuery('.headerwrap').addClass("stucked");
			jQuery('.headerwrap').parent().append('<div id="headerfake" style="height:'+jQuery('.headerwrap').outerHeight()+'px"></div>');

			//jQuery('body').trigger("makelogosmall");
			makeLogoSmall();

		}
	}

	function unStuckDesktopMenu() {
	   		jQuery('.headerwrap').removeClass("stucked")
	   		jQuery('#headerfake').remove();
	   		//jQuery('body').trigger("makelogonormal");
	   		makeLogoNormal();
	}

	function stuckMobileMenu() {
		jQuery('.row.mobilemenu').addClass("stucked");
	}

	function unStuckMobileMenu() {
		jQuery('.row.mobilemenu').removeClass("stucked");
	}

	/****************************************
		-	PARALLAX BACKGROUND HANDLER	-
	****************************************/
	function parrallaxBG() {
	    jQuery('.parallaxbg').each(function(){
	        var $bgobj = jQuery(this); // assigning the object

	        jQuery(window).scroll(bgScrollPosCalculate);
	        bgScrollPosCalculate();

	        function bgScrollPosCalculate() {
		        var yPos = -(jQuery(this).scrollTop() / $bgobj.data('speed'));


	            var coords = '50% '+ yPos + 'px';

	            // Move the background
	            $bgobj.css({ backgroundPosition: coords });
	        }

	    });
	  }


	/****************************************
		-	BACKGROUND VIDEO HANDLING	-
	***************************************/
		function videoParallaxBg() {
		    jQuery('.videobgparallax').each(function(i) {

				var cont = jQuery(this).parent();
				var mp4 = jQuery(this).data('mp4');
				var ogv = jQuery(this).data('ogv');
				var webm = jQuery(this).data('webm');
				var poster = jQuery(this).data('poster');
				var posi = "absolute";
				if ((mp4!=undefined || ogv!=undefined || webm!=undefined) && (mp4.length>3 || ogv.length>3 || webm.length>3)) {

					jQuery(this).css({'overflow':'hidden'});
					jQuery(this).append('<video id="mainbgvideo" class="video-js vjs-default-skin" loop preload="none" style="position:'+posi+';top:0px;left:0px;" width="100%" height="100%"'+
									'poster="'+poster+'" data-setup="{}">'+
									'<source src="'+mp4+'" type="video/mp4" />'+
									'<source src="'+webm+'" type="video/webm" />'+
									'<source src="'+ogv+'" type="video/ogg" />'+
									'</video>');



					var html5vid = jQuery('#mainbgvideo');
					html5vid.css({zIndex:0});

					var mediaaspect=16/9;
					html5vid.data('mediaAspect',mediaaspect);
					updateHTML5Size(html5vid,cont);

					var videoID = "videoid_"+Math.round(Math.random()*1000+1);
					html5vid.attr('id',videoID);

					videojs(videoID).ready(function(){
						html5vidready(this,videoID)
					});


					html5vid.find('.vjs-poster').css({display:"block"});

					videojs(videoID).ready(function(){
						var myPlayer = this;

							myPlayer.volume(0);

							setTimeout(function() {
								myPlayer.play(0);
								html5vid.find('.vjs-poster').css({display:"none"});
							},50);

					});

					if (html5vid.data('ww') == undefined) html5vid.data('ww',html5vid.width());
					if (html5vid.data('hh') == undefined) html5vid.data('hh',html5vid.height());

					updateHTML5Size(html5vid,cont);
					jQuery(window).resize(function() {
						updateHTML5Size(html5vid,cont);
					});
					html5vid.addClass("fullcoveredvideo");
				}
		    })
		}


		///////////////////////////////////////
		// EVENT HANDLING FOR VIDEO JS VIDEOS //
		////////////////////////////////////////
		function html5vidready(myPlayer,player_id) {

			if (player_id==undefined) player_id = jQuery(myPlayer["b"]).attr('id');
			var player_cont = jQuery('#'+player_id);


			myPlayer.on("play",function() {
			  myPlayer.volume(0);
			});


			myPlayer.on('loadedmetadata', function(data) {

					var videoWidth =0;
					var videoHeight=0;


					for(var prop in this) {
							try{

								if(this[prop].hasOwnProperty('videoWidth'))
									videoWidth = this[prop].videoWidth;

								if(this[prop].hasOwnProperty('videoHeight'))
									 videoHeight = this[prop].videoHeight;
							} catch(e) {}
						}

					var mediaAspect = videoWidth/videoHeight;

					if (player_cont.data('mediaAspect') == undefined) player_cont.data('mediaAspect',mediaAspect);
					if (player_cont.closest('.tp-caption').data('forcecover')==1)
						updateHTML5Size(player_cont,player_cont.parent().parent());

			});

		}

		/////////////////////////////////////
		// RESIZE HTML5VIDEO FOR FULLSCREEN//
		/////////////////////////////////////
		function updateHTML5Size(pc,container) {
				var windowW = container.outerWidth(false);
				var windowH = container.outerHeight(false);


				var mediaAspect = pc.data('mediaAspect');

				var windowAspect = windowW/windowH;
				pc.parent().find('.vjs-poster').css({width:"100%",height:"100%"});

				if (windowAspect < mediaAspect) {
					// taller
						pc
							.width(windowH*mediaAspect)
							.height(windowH);
						pc
							.css('top',0)
							.css('left',-(windowH*mediaAspect-windowW)/2)
							.css('height',windowH);
						pc.find('.vjs-tech').css('width',windowH*mediaAspect);

				} else {
					// wider
						pc
							.width(windowW)
							.height(windowW/mediaAspect);
						pc
							.css('top',-(windowW/mediaAspect-windowH)/2)
							.css('left',0)
							.css('height',windowW/mediaAspect);
						pc.find('.vjs-tech').css('width','100%');

				}

			}



		/******************************
			-	SLIDER FUN ;-)	-
		********************************/
		function initSliderFun() {
			// CHANGE HEIGHT OF DEF CONTAINER
			if(!is_mobile()){
				jQuery('.homeslider').css({height:'auto'});

				jQuery('.homeslider ul li').each(function() {
					jQuery(this).find('.tp-caption').each(function(){
						if(jQuery(this).html().lastIndexOf("vimeo")>-1 || jQuery(this).html().lastIndexOf("vimeo")>-1 || jQuery(this).html().lastIndexOf("href")>-1)
							zindex = "30001";
						else
						 	zindex = "10";
						//jQuery(this).wrap('<div class="tp-parallax" style="position:absolute;width:100%;height:100%;top:0px;left:0px;z-index:'+zindex+';"></div>');

					});

			});

			jQuery(window).scroll(function() {

				var offset = jQuery(window).scrollTop();
					jQuery('.homeslider .tp-caption').each(function() {
						var tp=jQuery(this);
						TweenLite.to(tp,0.3,{z:1000,scale:1, rotationX:offset/10,z:0.01,transformOrigin:"center bottom",opacity:1-Math.abs(offset/1000),transformPerspective:1000,y:offset/2,ease:Linear.easeNone});
						if(navigator.userAgent.indexOf('Chrome') > -1){
							tp.css("-webkit-transform-origin",'none');
							tp.css("-webkit-transform",'none');
						}
					});

			});
			}
		}

		function initSliderHeight(){
			jQuery('.homeslider').css({height:'auto'});

			jQuery(".comment-reply-link").click(function(){
				if(jQuery(this).closest("li .depth-1") != jQuery("#comments li").last()) jQuery(".form-submit #submit").css("margin-bottom","50px");
				else
					jQuery(".form-submit #submit").css("margin-bottom","10px");
			});
		}

		////////////////////////////
		// INIT BUDDYPRESS CLICKS //
		////////////////////////////
		function initBuddyPressClicks() {
			var wn=jQuery('#whats-new');
			wn.data('oldheight',wn.height());
			wn.unbind('click');
			wn.unbind('focus');

		}

		///////////////////////
		// INIT INPUT FIELDS //
		//////////////////////

		function initInputFields() {

			// Check the Search value on Standard
				jQuery(".prepared-input, .searchinput").each(function() {
					var field=jQuery(this);
					field.data('standard',field.val());
				});


				jQuery(".prepared-input, .searchinput").focus(function(){
					var $this = jQuery(this);

					$this.val($this.val()== $this.data('standard') ? "" : $this.val());
				});
				jQuery(".prepared-input, .searchinput").blur(function(){
					var $this = jQuery(this);
					$this.val($this.val()== "" ? $this.data('standard') : $this.val());
				});
		}




/* Team Member Adjustement */
function initTeamMemberAdjustment() {
	var maxh=0;
	var padds=0;
	jQuery('.team').waitForImages(function() {
		jQuery('.team').find('.memberwrap').each(function() {
			var th=jQuery(this);
			padds=parseInt(th.css('paddingBottom'),0) + parseInt(th.css('paddingTop'),0);
			if (maxh<th.find('.member').outerHeight(true)) maxh=th.find('.member').outerHeight(true);
		})



		jQuery('.team').find('.memberwrap').each(function() {
			jQuery(this).css({'height':(maxh+padds)+"px"});
		})
	});
}

/* INITIALISE  THE COLLAPSE FUNCTIONS */
function initCollapseExtras() {
	jQuery('.accordion-toggle').each(function() {
		jQuery(this).click(function() {
			jQuery(this).closest('.accordion').find('.accordion-toggle').each(function(i) {

				jQuery(this).addClass('collapsed');
			})
		})
	})
}








jQuery(window).load(function(){

	/* Isotope Portfolio */
	var $container = jQuery('.portfolio');
	$container.isotope({
		filter: '*',
		animationOptions: {
			duration: 500,
			easing: 'linear',
			queue: false
		}
	});

	jQuery(window).resize(function() {
		setTimeout(function() {jQuery('.portfolio').isotope('reLayout');},550);
	});

	jQuery('.portfolio').isotope('reLayout');

	jQuery('ul.portfoliofilter a').click(function(){
		var selector = jQuery(this).attr('data-filter');
		$container.isotope({
			filter: selector,
			animationOptions: {
				duration: 500,
				easing: 'linear',
				queue: false
			}
		});
	  return false;
	});

	var $optionSets = jQuery('ul.portfoliofilter'),
	       $optionLinks = $optionSets.find('a');
	       $optionLinks.click(function(){
	          var $this = jQuery(this);
		  // don't proceed if already selected
		  if ( $this.hasClass('selected') ) {
		      return false;
		  }
	   var $optionSet = $this.parents('ul.portfoliofilter');
	   $optionSet.find('.selected').removeClass('selected');
	   $this.addClass('selected');
	});

	jQuery('.isotope-item').hover(function() {
		var item = jQuery(this);
		var textholder = item.find('.foliotextholder')

		item.find('.folio_underlay').css({paddingBottom:35 + textholder.height()+"px"});
	});

});

/* #Fancy Box
================================================== */

function addFancyBox() {
	/* PrettyPhoto init */

	jQuery(".prettyphoto").each(function(){
		jQuery(this).removeClass("prettyphoto").addClass("fancybox").attr("rel","gallery").attr("title",jQuery(this).find("img").attr("alt"));
	});

	jQuery(".fancybox").fancybox({
		openEffect  : 'none',
		closeEffect : 'none',
		autoResize : true,
		fitToView : true,
		helpers : {
			media : {}
		}
	});

	jQuery(window).resize(function() {
		jQuery.fancybox.update();
	});
}

/* #Menu Handler
================================================== */

function menuHandler() {

	var defpar = jQuery('#mainmenu').parents().length;

	jQuery('#mainmenu li > a').each(function(i) {
		var a=jQuery(this);
		var par= a.parents().length-defpar -3;

		var prep = a.html();
		
		if (a.hasClass('menu-link') && !a.hasClass('wpmenucart-contents')) {

		
			prep = prep.split('>');
			prep = prep.length>1 ? prep[prep.length-1] : prep[0];

				
			var atext = prep; //a.html().split('<')[0];

			
			atext = atext.toLowerCase();
			atext = atext.substr(0,1).toUpperCase() + atext.substr(1,atext.length);
	
			if (par==0)
				var newtxt=jQuery("<div>"+atext+"</div>").text();
			else
				if (par==2)
					var newtxt=jQuery("<div>&nbsp;&nbsp;&nbsp;"+atext+"</div>").text();
				else
					if (par==3)
						var newtxt=jQuery("<div>&nbsp;&nbsp;&nbsp;&nbsp;"+atext+"</div>").text();

					if (par==4)
						var newtxt=jQuery("<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+atext+"</div>").text();
						

			 jQuery('#responsive-menu select').append(new Option(newtxt,a.attr('href')) );
		}
	});
}

/*#Footer Handler
================================================== */
function footerHandler() {

	var footer_wrap = jQuery('body').find('.footerwrap');

	jQuery(window).resize(function() {
		footerAdjust();
		setTimeout(function() {footerAdjust();},100);
	});

	var intfa_c=0;
	var intfa=setInterval(function() {
		footerAdjust();
		intfa_c++;
		if (intfa_c>15) clearInterval(intfa);
	},100);

	function footerAdjust(){

		var footerh = footer_wrap.outerHeight();
		var windowh = jQuery(window).height();
		dif=0;
		if(footer_wrap.length){
			dif =  parseInt(footer_wrap.css('marginTop'),0) + Math.round(windowh - (footer_wrap.offset().top + footerh));
			if (dif>0) {
				footer_wrap.stop();
				footer_wrap.animate({'marginTop':dif+"px"},{duration:300,queue:false});
			}
			if (dif<0) {
				footer_wrap.stop(true,true);
				footer_wrap.animate({'marginTop':"0px"},{duration:300,queue:false});
			}
		}


	};



}


// Check Mobile
	function is_mobile() {
	      var agents = ['android', 'webos', 'iphone', 'ipad', 'blackberry','Android', 'webos', ,'iPod', 'iPhone', 'iPad', 'Blackberry', 'BlackBerry'];
	   var ismobile=false;
	      for(i in agents) {

	       if (navigator.userAgent.split(agents[i]).length>1) {
	              ismobile = true;

	            }
	      }
	      return ismobile;
	  }

// Woo Tags
	function initWooTags(){
		jQuery('body').find('.widget_product_tag_cloud .tagcloud a').css("font-size","inherit");
	}