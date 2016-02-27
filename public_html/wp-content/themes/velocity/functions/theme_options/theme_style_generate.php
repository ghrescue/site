<?php
function generateCSS(){
		$templateurl = get_template_directory_uri();
	
		// Themeoptions
		$themeoptions = velocity_getThemeOptions();
				
	
		/* HIDE SIDEBAR ON MOBILE*/
		$hidesidebarmobile = isset($themeoptions['sidebar_hide_mobile']) ? true : false ;

		/* COLORS */
		$highlightcolor = $themeoptions['velocity_color_highlight'];
		$headerbgcolor = $themeoptions['velocity_pagetitleline_color'];
		$menu_color = $themeoptions['velocity_menu_color'];
	
		/* GOOGLE FONT */
		$headlinefontfamily = $themeoptions['velocity_font_headlinefamily'];
	
		/* LOGO MARGINS */
		$logo_width = !empty($themeoptions["velocity_img_logo_width"]) ? $themeoptions["velocity_img_logo_width"] : 135;
		$logo_height = !empty($themeoptions["velocity_img_logo_height"]) ? $themeoptions["velocity_img_logo_height"] : 40;
			
		$logo_margin_top = (int)$themeoptions['velocity_margin_top'];
		$logo_margin_bottom = (int)$themeoptions['velocity_margin_bottom'];
		
		$mobile_menu_top = $logo_margin_top+($logo_height/2)-20;
		$mobile_menu_bottom = $logo_margin_bottom+($logo_height/2)-12;
		
		$menu_top = $logo_margin_top+($logo_height/2)-6;
		$menu_bottom = $logo_margin_bottom+($logo_height/2)-10;
		
		$header_search_top = $logo_margin_top+($logo_height/2)-20;
	
		/* SUBMENU */
		$submenuwidth = $themeoptions['velocity_submenuwidth'];
	
		/* Theme Layout */
		$velocity_themelayout = $themeoptions['velocity_themelayout'];
		$footer_background_color = $themeoptions['velocity_footer_color'];
		
		/* WOO Commerce Colors */
		$woocolor = get_option('woocommerce_frontend_css_colors');
		$woo_primary = $woocolor['primary'];
		$woo_secondary = $woocolor['secondary'];
		$woo_highlight = $woocolor['highlight'];
		
	$data='<style>	
	body { font-family:' . $headlinefontfamily  . ';}
	.footerwrap.wide { background-color: '.$footer_background_color.'}
	.themecolor { color: ' . $highlightcolor  . '; }
	h1,h2,h3,h4,h5,h6 { font-family: ' . $headlinefontfamily  . ';  }
	::selection { background: ' . $highlightcolor  . '; }
	::-moz-selection { background: ' . $highlightcolor  . '; }
	.notfounderror {  color: ' . $highlightcolor  . '; }
	i.highlightcolor	{	color: ' . $highlightcolor  . '; }
	i.checkicon			{	margin-right:10px;}
	a { color: ' . $highlightcolor  . '; }
	a.color { color: ' . $highlightcolor  . '; }
	
	.headertop .headerlefttext a:hover, .headertop .headerrighttext a:hover { color: ' . $highlightcolor  . '; }

	.serviceicon div { color: ' . $highlightcolor  . '; border: 1px solid ' . $highlightcolor  . '; }
	a.service:hover .serviceicon div { background: ' . $highlightcolor  . '; }
	
	.pricecol.highlight .pricewrap { border: 1px solid ' . $highlightcolor  . '; }
	.pricing .highlight .buy { background: ' . $highlightcolor  . '; }
	.pricing .price { color: ' . $highlightcolor  . ';  }
	.pricing .price .dollar { color: ' . $highlightcolor  . ';  }
	.pricing .highlight .thead { background: ' . $highlightcolor  . '; }
	
	.homepostimage a img { border: 1px solid ' . $highlightcolor  . ';  }
	.homepostimage a .posticon { color: ' . $highlightcolor  . ';  }
	.homepostimage a .posticonbg { background-color: ' . $highlightcolor  . ';	}
	.homepostimage a.withimage .posticonbg { background-color: ' . $highlightcolor  . '; }
	.homepostholder .readmorelink:hover, .homepostholder .readmorelink:visited:hover { color: ' . $highlightcolor  . '; }
	.homepost .postinfo a:hover,
    .homepost .postinfo .categories a:visited:hover,
    .homepost .postinfo .comments a:visited:hover 	{	color:' . $highlightcolor  . '; }  
    .light-on-dark .homepostholder .readmorelink:hover, .light-on-dark .homepostholder .readmorelink:visited:hover { color: ' . $highlightcolor  . '; }
    .light-on-dark .homepostimage a .posticonbg { background-color: ' . $highlightcolor  . '; }
    
    .postinfo a:hover { color: ' . $highlightcolor  . '; }
    .blogpost.singlefolio .postinfo a:hover { color: ' . $highlightcolor  . '; }
    
    .sidebar a { color: ' . $highlightcolor  . '; }
    .footer a:hover { color: ' . $highlightcolor  . '; }
    .subfooter a:hover { color: ' . $highlightcolor  . '; }
    .sidebar .tagcloud a {  color: ' . $highlightcolor  . '; border: 1px solid ' . $highlightcolor  . '; }
    .widget_archive ul li a, .widget_categories ul li a, .widget_meta ul li a, .widget_recent_entries ul li a { color: ' . $highlightcolor  . '; }
    .footer .widget_archive ul li a:hover, .footer .widget_categories ul li a:hover, .footer .widget_meta ul li a:hover, .footer .widget_recent_entries ul li a:hover { color: ' . $highlightcolor  . ';}
    .widget_pages ul li a:hover, .widget_pages ul li a:visited:hover { color: ' . $highlightcolor  . ' !important; }
	.widget_pages ul li.current_page_item a { color: ' . $highlightcolor  . ';  }
	.footer .widget_pages ul li a:hover, .footer .widget_pages ul li a:visited:hover { color: ' . $highlightcolor  . ' !important; }	
	.footer .widget_pages ul li.current_page_item a { color: ' . $highlightcolor  . '; }
	.sidebar .widget_nav_menu ul li a:hover { color: ' . $highlightcolor  . '; }
	.sidebar .widget_nav_menu ul li.current_page_item > a { color: ' . $highlightcolor  . ';  }
	.footer .widget_nav_menu ul li a:hover { color: ' . $highlightcolor  . '; }
	.footer .widget_nav_menu ul li.current_page_item > a { color: ' . $highlightcolor  . '; }
	.footer ul#recentcomments li a:hover { color: ' . $highlightcolor  . '; }
	.footer ul#recentcomments li a.url:hover { color: ' . $highlightcolor  . '; }
	
	ul.portfoliofilter li:hover a,
	ul.portfoliofilter li a:hover	 { background: ' . $highlightcolor  . '; border: 1px solid ' . $highlightcolor  . ';}
	ul.portfoliofilter li a.selected {  background: ' . $highlightcolor  . '; border: 1px solid ' . $highlightcolor  . '; }
	.light-on-dark ul.portfoliofilter li:hover a,
	.light-on-dark ul.portfoliofilter li a:hover	 { background: ' . $highlightcolor  . '; }
	.light-on-dark ul.portfoliofilter li a.selected {  background: ' . $highlightcolor  . ';  }
	.light-on-dark ul.portfoliofilter li:hover a,
	.light-on-dark .pagination ul > li > a:hover	 { background: ' . $highlightcolor  . '; }
	.light-on-dark ul.portfoliofilter li a.selected {  background: ' . $highlightcolor  . '; }
	.projectnav a:hover { border: 1px solid ' . $highlightcolor  . '; }
	.projectnav a:hover:before { color: ' . $highlightcolor  . '; }
	
	.btn 	{ background-color: ' . $highlightcolor  . ';  }
 	.btn:hover { background-color: ' . $highlightcolor  . ';  }
.btn-primary { background-color: ' . $highlightcolor  . '; }
	.btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] { background-color: ' . $highlightcolor  . ' !important; }
	.btn-primary:active, .btn-primary.active { background: ' . $highlightcolor  . ' !important; }
	.pricecol.highlight .btn-primary, .pricecol.highlight .btn { background: ' . $highlightcolor  . ' url(img/tiles/dark25.png) repeat !important; }
	.pricecol.highlight .btn-primary:hover, .pricecol.highlight .btn:hover { background-color: ' . $highlightcolor  . ' !important; }
	.btnfine { color: ' . $highlightcolor  . '; border: 1px solid ' . $highlightcolor  . '; }
	
	.form-submit #submit , .standardbtn { background-color: ' . $highlightcolor  . '; }
	.form-submit #submit:hover , .standardbtn:hover,
	.form-submit #submit:active , .standardbtn:active,
	.form-submit #submit:active , .standardbtn.active,
	.form-submit #submit:disabled , .standardbtn.disabled,
	.form-submit #submit:hover , .standardbtn[disabled] { background-color: ' . $highlightcolor  . ' !important; }
	.form-submit #submit:active, .standardbtn:active, .standardbtn.active { background: ' . $highlightcolor  . ' !important; }
	.pagination ul > li > a:hover { background: ' . $highlightcolor  . '; border: 1px solid ' . $highlightcolor  . '; }
	.pagination ul > .disabled > span, .pagination ul > .disabled > a, .pagination ul > .disabled > a:hover, .pagination ul > .active > a, .pagination ul > .active > span, .pagination ul > .active > a:hover {background: ' . $highlightcolor  . '; border: 1px solid ' . $highlightcolor  . ';}
	.paginatedpost ul span li { background: ' . $highlightcolor  . '; border: 1px solid ' . $highlightcolor  . '; }
	.paginatedpost ul a:hover span li { background: ' . $highlightcolor  . '; border: 1px solid ' . $highlightcolor  . ';  }
	
	.pagetitle h1 { font-family: ' . $headlinefontfamily  . ';  }
	.colored .pagetitlewrap { background: ' . $headerbgcolor  . '; }

	.header .logo {  margin-top: '.$logo_margin_top.'px; margin-bottom: '.$logo_margin_bottom.'px; }
	
	.navigation ul ul.sub-menu {border-top: 2px solid ' . $highlightcolor  . '; }
	.navigation ul li.menu-item:hover,
	.navigation ul li.menu-item.buttonon,
	.navigation ul li.menu-item.current-menu-item,
	.navigation ul li.menu-item.current-menu-ancestor	{	color: ' . $menu_color  . ' !important; }
	.navigation >ul >li.menu-item:hover >a.menu-link,
	.navigation >ul >li.menu-item.current-menu-item >a.menu-link,
	.navigation >ul >li.menu-item.current-menu-ancestor >a.menu-link 	{	color: ' . $menu_color  . ' !important; }
	.navigation ul li.menu-item-has-children:hover a.menu-link {  color: ' . $menu_color  . '; }
	.navigation ul ul.sub-menu li.menu-item a.menu-link:hover { color: ' . $highlightcolor  . ' !important;  }
	.navigation ul ul.sub-menu { min-width: ' . $submenuwidth  . 'px; }
	.navigation >ul >li.menu-item >a.menu-link,
	.navigation >ul >li.menu-item >a.menu-link:visited	{ padding-top: '.$menu_top.'px; padding-bottom:'.$menu_bottom.'px; }
	.navigation ul li.menu-item.current-menu-item a.menu-link, .navigation ul li.menu-item.current-menu-ancestor a.menu-link {  color: ' . $menu_color  . '; border-top: 2px solid ' . $highlightcolor  . ' !important; padding-bottom: '.($menu_bottom).'px; padding-top: '.($menu_top-2).'px; }	
	.navigation ul ul.sub-menu li.menu-item a.menu-link:hover { color: ' . $highlightcolor  . ' !important; }
	
	.navigation .megamenu { border-top: 2px solid ' . $highlightcolor  . '; }
	.darkheader .navigation >ul >li.menu-item.current-menu-item >a.menu-link, .darkheader .navigation >ul >li.menu-item.current-menu-ancestor >a.menu-link {color: #fff !important; }
	.darkheader .navigation >ul >li.menu-item:hover >a.menu-link, .darkheader .navigation >ul >li.menu-item.menu-item-has-children:hover >a.menu-link { color: #fff !important; }

	span.hlink { color: ' . $highlightcolor  . ';}
	.blogpost.sticky h2 a { color: ' . $highlightcolor  . '; }
	.blogpost.sticky .month, .blogpost.sticky .month, .blogpost.sticky .day, .blogpost.sticky .day  { color: ' . $highlightcolor  . '; }
	.sidebar .widget_posts ul li span {  color: ' . $highlightcolor  . ';  }
	.footer .tagcloud a:hover { background-color: ' . $highlightcolor  . '; color: #fff; }
	.sidebar .tagcloud a:hover { background-color: ' . $highlightcolor  . '; border-color: ' . $highlightcolor  . '; }

	#wp-calendar tbody td a { color: ' . $highlightcolor  . '; }
	.widget_archive ul li a:before, .widget_categories ul li a:before , .widget_meta ul li a:before, .widget_recent_entries ul li a:before {  color: ' . $highlightcolor  . '; }

	.widget_pages ul li a:hover { color: ' . $highlightcolor  . ';	}
	.widget_pages ul li.current_page_item a {  color: ' . $highlightcolor  . ';   }

	ul#recentcomments li a.url {  color: ' . $highlightcolor  . ';  }
	ul#recentcomments li a:before {  color: ' . $highlightcolor  . '; }
	.carousel .item { font-family: ' . $headlinefontfamily  . '; }
	blockquote { font-family: ' . $headlinefontfamily  . ';  }
	ul.portfoliofilter li a:hover { color: ' . $highlightcolor  . '; }
	ul.portfoliofilter li a.selected { color: ' . $highlightcolor  . '; }
	.mediaholder .link	{ background: ' . $highlightcolor  . '; }
	.mediaholder .show	{  background: ' . $highlightcolor  . '; }

	.btn-primary { background: ' . $highlightcolor  . ' url(img/tiles/transparent.png) repeat; }
	.btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] { background: ' . $highlightcolor  . ' url('.get_template_directory_uri().'/img/tiles/light20.png) repeat !important; }
	.btn-primary:active, .btn-primary.active { background: ' . $highlightcolor  . ' !important; }
	.form-submit #submit , .standardbtn { font-family: ' . $headlinefontfamily  . ' !important; background: ' . $highlightcolor  . ' url(img/tiles/transparent.png) repeat; }
	
	.form-submit #submit:hover , .standardbtn:hover,
	.form-submit #submit:active , .standardbtn:active,
	.form-submit #submit:active , .standardbtn.active,
	.form-submit #submit:disabled , .standardbtn.disabled,
	.form-submit #submit:hover , .standardbtn[disabled] {  background: ' . $highlightcolor  . ' url('.get_template_directory_uri().'/img/tiles/light20.png) repeat !important; }
	
	.form-submit #submit:active,
	.standardbtn:active,
	.standardbtn.active { background: ' . $highlightcolor  . ' !important; }
	#buddypress form#whats-new-form textarea, select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"],
	#bbp_search, #buddypress div.dir-search input[type=text], #buddypress .standard-form textarea, #buddypress .standard-form input[type=text], #buddypress .standard-form select, #buddypress .standard-form input[type=password], #buddypress .dir-search input[type=text]  { font-family: ' . $headlinefontfamily  . '; }	
	#buddypress form#whats-new-form textarea:focus, #bbp_search:focus, textarea:focus, input[type="text"]:focus, input[type="password"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="date"]:focus, input[type="month"]:focus, input[type="time"]:focus, input[type="week"]:focus, input[type="number"]:focus, input[type="email"]:focus, input[type="url"]:focus, input[type="search"]:focus, input[type="tel"]:focus, input[type="color"]:focus, .uneditable-input:focus {
	  border-color: ' . $highlightcolor  . '; }
	.headersearch { top: '.$header_search_top.'px; }
	.headersearch input { font-family: ' . $headlinefontfamily  . ';
	 }
	.progress-success .bar { background: ' . $highlightcolor  . ';}

	.btn, .btn:hover { background-color: '.$highlightcolor.';}
	
	ul.portfoliofilter li:hover a,
	ul.portfoliofilter li a:hover	 { background: '.$highlightcolor.';  color: #fff !important;}
	ul.portfoliofilter li a.selected {  background: '.$highlightcolor.'; color: #fff; }

		
	.active .tab-prefix,
	li:hover .tab-prefix	{	background-color:'.$highlightcolor.';}
	.accordion-toggle { cursor: pointer; color: ' . $highlightcolor  . ';}
	a.accordion-toggle:hover { color: ' . $highlightcolor  . '; }

	';
		

	
	if (isset($themeoptions["velocity_responsive"])){  $data .= '
		@media only screen and (min-width: 980px) and (max-width: 1199px) {
			.mobilemenu, .boxedlayout .mobilemenu { padding-right: 0px; float: right; margin-top: '.$mobile_menu_top.'px; margin-bottom: '.$mobile_menu_bottom.'px; }
		}';
	}
	
	$data .= '
	
	ul.product-categories li a:before {  color: ' . $highlightcolor  . ' !important; }
	.woocommerce .product_meta a:hover { color: ' . $highlightcolor  . ' !important; }
	a.reset_variations:hover { color: ' . $highlightcolor  . ' !important; }
	
	.quantity .minus:hover, .woocommerce #content .quantity .minus:hover, .woocommerce-page .quantity .minus:hover, .woocommerce-page #content .quantity .minus:hover, .quantity .plus:hover, .woocommerce #content .quantity .plus:hover, .woocommerce-page .quantity .plus:hover, .woocommerce-page #content .quantity .plus:hover { background: ' . $woo_highlight  . ' !important; border: 1px solid ' . $woo_highlight  . ' !important; }
		
	.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover { border: 1px solid ' . $woo_highlight  . ' !important; background: ' . $woo_highlight  . ' !important; }
			
	.widget_price_filter .ui-slider .ui-slider-range, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range { background: ' . $woo_primary  . ' !important; }
	
	.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle { border: 1px solid ' . $woo_primary  . ' !important; background: ' . $woo_primary  . ' !important; }
	
	.woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt { background: ' . $woo_primary  . ' url('.get_template_directory_uri().'/img/tiles/transparent.png) repeat !important; }

	.woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover { background: ' . $woo_primary  . ' url('.get_template_directory_uri().'/img/tiles/light20.png) repeat !important; border: 0 !important; }



';

$data .= $themeoptions["velocity_custom_css"];

return $data."</style>";

}