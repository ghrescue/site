// closure to avoid namespace collision
(function(){
		tinymce.create('tinymce.plugins.thundercodes', {
	    createControl: function(n, cm) {
	        switch (n) {
	            case 'thundercodes_button':
	                var c = cm.createSplitButton('thundercodes_button', {
	                    title : 'themetasticCodes',
	                    image : '../wp-content/themes/velocity/functions/thundercodes/thunder_icon.png',
	                    
	                });
	
	                c.onRenderMenu.add(function(c, m) {
	                    m.add({title : 'themetasticCodes', 'class' : 'mceMenuItemTitle'}).setDisabled(1);
	
	                    m.add({title : 'Headline', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[themetastic_headline title="Buttons" target="_self"]');		
	                    }});
	                    m.add({title : 'Button', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[bs_button class="default,primary,info,warning,success" content="Regular Button" size="normal|large" target="_self" extra_class="margin5"]');
	                    }});
	                    m.add({title : 'Spacer', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[spacer height="40"]');
	                    }});
	                    m.add({title : 'Message Box', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[bs_alert content="Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor." type="default,info,success,error"]');
	                    }});
	                    m.add({title : 'Lightbox Image', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[lightbox title="Some Caption" thumb="URL to thumb or image id" thumbwidth="200" thumbheight="200"]');
	                    }});
	                    m.add({title : 'Accordion', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[vc_accordion] [vc_accordion_tab title="Section 1"]Lorem ipsum ...[/vc_accordion_tab] [vc_accordion_tab title="Section 2"]Lorem ipsum ...[/vc_accordion_tab][/vc_accordion]');
	                    }});
	                    m.add({title : 'Tabs', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[vc_tabs] [vc_tab title="Tab 1"] Lorem Ipsum... [/vc_tab] [vc_tab title="Tab 2"] Lorem Ipsum... [/vc_tab] ');
	                    }}); 
	                    m.add({title : 'Service Block', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[service_block title="Head Title" subtitle="Sub Title" icon="home-1 (see all icons available via the themetasticIcons" href="URL Link" target="_self"]');
	                    }}); 
	                    m.add({title : 'Latest Posts', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[latest_posts number="3" excerpt_words="30" category_info="on" date_info="on" comments_info="on" category="optinal insert one category slug to display only"]');
	                    }});
	                     m.add({title : 'Latest Projects', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[latest_projects portfolio="portfolioslug" columns="3" items="6" height="300"]');
	                    }}); 
	                    m.add({title : 'Team Member', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[team_member name="Damojo" position="Theme Designer" mail="info@mail.com" phone="1334234" facebook="facebook profile URL" twitter="twitter accout URL" gplus="gplus URL" linkedin_1="LinkedIn URL" content="Lorem Ipsum..."]');	
	                    }}); 
	                    m.add({title : 'Price Table', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[pricetable_columns title1="Basic Package" titlesubline1="Entry level solution" highlight1="normal" price1="9.95" price_currency1="$" button_url1="#" button_text1="Select" button_type1="primary" content1="2 GB Webspace,1 Domain,PHP 5 enabled,24h – Support" title2="Advanced Package" titlesubline2="More features" highlight2="highlight" price2="17.95" price_currency2="$" button_url2="#" button_text2="Select" button_type2="primary" content2="4 GB Webspace,2 Domains,PHP 5 enabled,24h – Support" title3="Smart Package" titlesubline3="Perfect business solution" highlight3="normal" price3="19.95" price_currency3="$" button_url3="#" button_text3="Select" button_type3="primary" content3="8 GB Webspace,4 Domains,PHP 5 enabled,24h – Support" highlight4="normal" button_type4="primary" highlight5="normal" button_type5="primary" el_position="first last"]');	
	                    }}); 
     
	                });
	
	              // Return the new splitbutton instance
	              return c;
	        }
	
	        return null;
	    }
	});	
	
	
	
	tinymce.PluginManager.add('thundercodes', tinymce.plugins.thundercodes);

	
	function callshortcode(thundershortcode){
		switch (thundershortcode) {
		    case "button": 
		    				var form = jQuery('<div id="thundercodes-form" class="thundercodes-form"><table id="thundercodes-table" class="form-table">\
										<tr>\
				<th><label for="thundercodes-link">Link</label></th>\
				<td><input type="text" id="thundercodes-link" name="link" value="#" /><br />\
				<small>The link a button sends you when clicking</small></td>\
			</tr>\
												<tr>\
				<th><label for="thundercodes-target">Target</label></th>\
				<td><input type="text" id="thundercodes-target" name="target" value="_self" /><br />\
				<small>Where should the link be opened?</small></td>\
			</tr>\
												<tr>\
				<th><label for="thundercodes-color">Color</label></th>\
				<td><select name="color" id="thundercodes-color">\ <option value="highlight">highlight</option><option value="blue">blue</option><option value="pink">pink</option><option value="purple">purple</option><option value="black">black</option><option value="green">green</option><option value="red">red</option><option value="brown">brown</option><option value="yellow">yellow</option><option value="aqua">aqua</option><option value="orange">orange</option>		</select><br />\
				<small>Select the hover Color</small></td>\
			</tr>\
												<tr>\
				<th><label for="thundercodes-content">Text</label></th>\
				<td><input type="text" id="thundercodes-content" name="content" value="Button" /><br />\
				<small>Text on the button</small></td>\
			</tr>\
												</table>\
										<p class="submit">\
											<input type="button" id="thundercodes-submit" class="button-primary" value="Insert Button" name="submit" onclick="submitForm()" />\
										</p>\
									</div>');
							thunder_options = Array("link","target","color"); 
							thunder_shortcode = "[button {{link}} {{target}} {{color}}]YOUR_BUTTON_TEXT[/button]";
							H = 294;
		    	break;	
		    
		    case "box": 
		    				var form = jQuery('<div id="thundercodes-form" class="thundercodes-form"><table id="thundercodes-table" class="form-table">\
										<tr>\
				<th><label for="thundercodes-style">Style</label></th>\
				<td><select name="style" id="thundercodes-style">\ <option value="info">info</option><option value="download">download</option><option value="warning">warning</option><option value="note">note</option>		</select><br />\
				<small>Define the display style of the Box</small></td>\
			</tr>\
												</table>\
										<p class="submit">\
											<input type="button" id="thundercodes-submit" class="button-primary" value="Insert Box" name="submit" onclick="submitForm()" />\
										</p>\
									</div>');
							thunder_options = Array("style"); 
							thunder_shortcode = "[box {{style}}]YOUR_BOX_TEXT[/box]";
							H = 110;
		    	break;	
		    	
		    case "projects": 
		    				var form = jQuery('<div id="thundercodes-form" class="thundercodes-form"><table id="thundercodes-table" class="form-table">\
										<tr>\
				<th><label for="thundercodes-portfolioslug">Portfolio</label></th>\
				<td><select name="portfolioslug" id="thundercodes-portfolioslug">\ 		</select><br />\
				<small>Choose the Portfolio to Display</small></td> \
			</tr>\ 										<tr>\
				<th><label for="thundercodes-number">Number</label></th>\
				<td><input type="text" id="thundercodes-number" name="number" value="2" /><br />\
				<small>How many Portfolio items do you want to display?</small></td>\
			</tr>\
												</table>\
										<p class="submit">\
											<input type="button" id="thundercodes-submit" class="button-primary" value="Insert Latest Projects" name="submit" onclick="submitForm()" />\
										</p>\
									</div>');
							thunder_options = Array("number","portfolioslug"); 
							thunder_shortcode = "[latest_projects {{number}} {{portfolioslug}}]";
							H = 170;
		    	break;	
		    case "tabs": 
		    				var form = jQuery('<div id="thundercodes-form" class="thundercodes-form"><table id="thundercodes-table" class="form-table">\
										<tr>\
				<th><label for="thundercodes-number">Number of Tabs</label></th>\
				<td><input type="text" id="thundercodes-number" name="number" value="3" /><br />\
				<small>How many Tabs?</small></td>\
			</tr>\
												<tr>\
				<th><label for="thundercodes-align">Tabs Align</label></th>\
				<td><select name="align" id="thundercodes-align">\ <option value="side">side</option><option value="top">top</option>		</select><br />\
				<small>Display the tabs bound to the left or on top (classic)</small></td>\
			</tr>\
												</table>\
										<p class="submit">\
											<input type="button" id="thundercodes-submit" class="button-primary" value="Insert Tabs" name="submit" onclick="submitTabsForm()" />\
										</p>\
									</div>');
							thunder_options = Array("number"); 
							thunder_shortcode = "{{tabs}}";
							H = 170;
		    	break;	
		    case "socialbar": 
		    				var form = jQuery('<div id="thundercodes-form" class="thundercodes-form"><table id="thundercodes-table" class="form-table">\
										<tr>\
				<th><label for="thundercodes-socials">Socials</label></th>\
				<td>\
				<div>\
			        <div style="display:none;">\
			        	<br><div class="thesocials"><select class="widefat">\
				        	<option value="dribbble">Dribbble</option>\
				        	<option value="facebook">Facebook</option>\
				        	<option value="flickr">Flickr</option>\
				        	<option value="forrst">Forrst</option>\
				        	<option value="google">Google</option>\
				        	<option value="lastfm">LastFM</option>\
				        	<option value="linkedin">LinkedIn</option>\
				        	<option value="pinterest">Pinterest</option>\
				        	<option value="rss">RSS</option>\
				        	<option value="skype">Skype</option>\
				        	<option value="tumblr">Tumblr</option>\
				        	<option value="twitter">Twitter</option>\
				        	<option value="vimeo">Vimeo</option>\
				        	<option value="youtube">Youtube</option>\
				        </select>\
				        <label>URL Link:</label>\
				        <input type="text" class="widefat"/>\</div>\
				        <br><a href="#" class="tb_longwave_delete_social">Delete Social</a><br>\
				   </div>\
				   <div class="thesocials"><select class="widefat">\
			        	<option value="dribbble">Dribbble</option>\
			        	<option value="facebook">Facebook</option>\
			        	<option value="flickr">Flickr</option>\
			        	<option value="forrst">Forrst</option>\
			        	<option value="google">Google</option>\
			        	<option value="lastfm">LastFM</option>\
			        	<option value="linkedin">LinkedIn</option>\
			        	<option value="pinterest">Pinterest</option>\
			        	<option value="rss">RSS</option>\
			        	<option value="skype">Skype</option>\
			        	<option value="tumblr">Tumblr</option>\
			        	<option value="twitter">Twitter</option>\
			        	<option value="vimeo">Vimeo</option>\
			        	<option value="youtube">Youtube</option>\
			        </select>\
			        <label>URL Link:</label>\
			        <input type="text" class="widefat"/></div><br>\
			        <span></span>\
			        <br><hr><a href="#" class="tb_longwave_add_social">Add Social</a>\
				</div>\
				</td>\
			</tr>\
												</table>\
										<p class="submit">\
											<input type="button" id="thundercodes-submit" class="button-primary" value="Insert Socialbar" name="submit" onclick="submitSocialsForm()" />\
										</p>\
									</div>');
							thunder_options = Array("number"); 
							thunder_shortcode = "[socialbar{{socials}}]";
							H = 350;
		    	break;	
		}
			
		jQuery(".thundercodes-form").remove();							
		var table = form.find('#thundercodes-table');
		form.appendTo('body').hide();
    
        // triggers the thickbox
		//var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
		var width = jQuery(window).width(), W = ( 720 < width ) ? 720 : width;
		W = W - 80;
		//H = H - 84;
		tb_show( 'ThunderCodes', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=thundercodes-form' );
		jQuery("#TB_window").css("height",H+65);
		jQuery("#TB_window").css("overflow-y","auto");
		jQuery("#TB_window").css("overflow-x","hidden");

		// handles the click event of the submit button
		//form.find('#thundercodes-submit').click(function(){
	}

	jQuery(".tb_longwave_add_social").live("click",function(){
    	$parent = jQuery(this).closest("div");
    	$field = $parent.find("div:first").clone(true);
    	$field.find("select,input").each(function(){
        	$this = jQuery(this);
        	$this.attr("name",$this.data("name"));
        	});
    	$field.css("display","");
    	$parent.find("span").before($field);
    	return false;
	});
	jQuery(".tb_longwave_delete_social").live("click",function(){
    	jQuery(this).closest("div").remove();	
    	return false;
	});

})()
	var thunder_options;
	var thunder_shortcode;
	
	function submitForm(){
			for(index in thunder_options){
				var value = jQuery("#thundercodes-table").find('#thundercodes-' + thunder_options[index]).val();
				// attaches the attribute to the shortcode only if it's different from the default value
				//if ( value !== options[index] )
				if(value!="")
					thunder_shortcode = thunder_shortcode.replace( "{{"+thunder_options[index]+"}}" , thunder_options[index] + '="'+ value +'"');
				else 
					thunder_shortcode = thunder_shortcode.replace( "{{"+thunder_options[index]+"}}" , "");
			};
			/*value = jQuery("#thundercodes-table").find('#thundercodes-content').val();
			if(value!="")	
				thunder_shortcode = thunder_shortcode.replace( "{{content}}" , value);
			else 
				thunder_shortcode = thunder_shortcode.replace( "{{content}}" , '');
			*/			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, thunder_shortcode);
			
			// closes Thickbox
			tb_remove();
	}
	
	function submitTabsForm(){
			var number = jQuery("#thundercodes-table").find('#thundercodes-number').val();
			var align = jQuery("#thundercodes-table").find('#thundercodes-align').val();	
			var tabs = "";	
			for (i=1; i <= number; i++ ){
				tabs += '[tab title="Tab Title '+i+'"]Tab Content '+i+'[/tab]';
			}
			thunder_shortcode = '[tabs align="' +align+'"]' +thunder_shortcode+ '[/tabs]';
			thunder_shortcode = thunder_shortcode.replace( "{{tabs}}" , tabs);
			
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, thunder_shortcode);
			
			// closes Thickbox
			tb_remove();
	}
	
	function submitSocialsForm(){
			socials = "";
			jQuery(".thesocials").each(function(){
				$this = jQuery(this);
				network = $this.find("select").val();
				link = $this.find("input").val();
				if(link!="")
					socials += ' ' + network + '=' + '"' + link + '"';
			});
			
			thunder_shortcode = thunder_shortcode.replace( "{{socials}}" , socials);
			
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, thunder_shortcode);
			
			// closes Thickbox
			tb_remove();
	}