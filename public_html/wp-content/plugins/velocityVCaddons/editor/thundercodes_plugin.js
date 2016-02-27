// closure to avoid namespace collision
(function(){
		tinymce.create('tinymce.plugins.thundercodes', {
	    init : function(ed, url) {
		    thundercodesurl = url;
		},
	    createControl: function(n, cm) {
	        switch (n) {
	            case 'thundercodes_button':
	                var c = cm.createSplitButton('thundercodes_button', {
	                    title : 'punchCodes',
	                    image : thundercodesurl+'/thunder_icon.png',
	                    
	                });
	
	                c.onRenderMenu.add(function(c, m) {
	                    m.add({title : 'punchCodes Examples', 'class' : 'mceMenuItemTitle'}).setDisabled(1);
						
						/*! HEADLINE */
						m.add({title : 'Special Headline', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_headline]Headline[/tp_headline]');
	                    }});
						
						/*! DIVIDER */
						m.add({title : 'Divider Line', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_divider top_margin="25" bot_margin="25"]');
	                    }});
	                    
	                    /*! CLEAR */
						m.add({title : 'Clear Floats', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_clear]');
	                    }});
	                     
	                    /*! SPACER */
						m.add({title : 'Spacer', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_spacer height="25" visible_desktop="true" visible_tablet="" visible_phone="" hidden_desktop="" hidden_tablet="" hidden_phone=""]');
	                    }});
	                    
	                    /*! TABS */
						m.add({title : 'Tabs', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_tabs title="Title1|Title2|Title3" active="true|false|false"]Content1|Content2|Content3[/tp_tabs]');
	                    }});
	                    
	                    /*! ACCORDION */
						m.add({title : 'Accordion', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_accordion style="colored or glas" title="Title1|Title2|Title3" active="true|false|false"]Content1|Content2|Content3[/tp_accordion]');
	                    }});
	                    
	                    /*! BUTTON */
						m.add({title : 'Button', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_button button_text="Click me" button_url="http://www.themepunch.com" button_target="_blank" button_color_text="#ffffff" button_color="#65517c" decoredbutton="true (or leave blank)" fullwidth="on (or leave blank)"]');
	                    }});

						/*! SERVICE */
						m.add({title : 'Service', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_service image="http://www.themepunch.com/goodweb/wp-content/uploads/2013/09/flaticon_12.png" title="Title" button_text="Click Me" button_url="http://www.themepunch.com" button_target="_blank" button_color_text="#ffffff" button_color="#65517c"]Content[/tp_service]');
	                    }});

						/*! TEAM MEMBER SOLO */
						m.add({title : 'Team Member Single', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_team_member image="" name="John Doe" position="WP Specialist" mail="info@goodweb.com" phone="+1 555 123456" facebook="" twitter="http://www.twitter.com/themepunch" gplus="" linkedin=""]Content[/tp_team_member]');
	                    }});

						/*! PROGRESSBAR */
						m.add({title : 'Progress Bars', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_progressbar title="Bar1|Bar2|Bar3" percent="50|60|70"]');
	                    }});

						/*! TEAMWALL */
						m.add({title : 'Team Wall', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_teamwall columns="threecolumn or fourcolumn" image="" name="Name1|Name2|Name3|Name4" position="Position1|Position2|Position3|Position4" mail="mail1@goodweb.com||mail3@goodweb.com|" phone="+1 555 123456|||+1 555 123456" facebook="" twitter="http://www.twitter.com/themepunch|http://www.twitter.com/themepunch|http://www.twitter.com/themepunch|http://www.twitter.com/themepunch" gplus="" linkedin=""]');
	                    }});
	                    
	                    /*! VIDEO */
						m.add({title : 'Video', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_video fitvid="true (or leave blank)"]Sharing/Embed Video Iframe you get from Video hoster. Examples for Youtube http://www.themepunch.com/item_pics/youtube_hint.png and Vimeo http://www.themepunch.com/item_pics/vimeo.png[/tp_video]');
	                    }});

						 /*! PRICETABLE */
						m.add({title : 'Pricetable', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_pricetable width="onefourth or onethird" style="colored or glass" headline="Headline1|Headline2|Headline3" subline="Subline1|Subline2|Subline3" price="99|98|97" highlight="|true|" currency="$|$|$" price_subline="/mo|/mo|/mo" button_text="Buy me|Buy me|Buy me" buton_url="#|#|#" button_target="_self|_blank|_self" button_text_color="#ffffff|#ffffff|#ffffff" button_color="#6967b1|#65517c|#6967b1" row1="Content Row|Content Row|Content Row" row2="Content Row|Content Row|Content Row" row3="Content Row|Content Row|Content Row" row4="Content Row|Content Row|Content Row" row5="||" row6="||" row7="||" row8="||" row9="||" row10="||"]');
	                    }});

						/*! IMAGE */
						m.add({title : 'Image', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_image text_align="left or right or center" link="url to link to or show in punchbox" link_type="standard or new-tab or lightbox-image" title="Lighbox Title" meta="MetaInfo content in Lightbox"]http://1.s3.envato.com/files/8552911/logo2.jpg[/tp_image]');
	                    }});
	                    
	                    /*! MAPGYVER */
						m.add({title : 'Google Map', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[tp_mapgyver address="Wallstreet New York" zoom="15"]Your Content[/tp_mapgyver]');
	                    }});

						/*! REVSLIDER */
						m.add({title : 'RevSlider', onclick : function() {
	                    	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[revslider sliderslug_seeRevSliderBackend]');
	                    }});

	                });
	
	              // Return the new splitbutton instance
	              return c;
	        }
	        return null;
	    }
	});	

	tinymce.PluginManager.add('thundercodes', tinymce.plugins.thundercodes);
})()