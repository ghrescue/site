<?php
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
require_once( $path_to_wp.'/wp-includes/functions.php');

$template_url_first = get_template_directory_uri();

if(get_option('velocity_first_import')!="on"){
	velocity_first_import_check();
}

function velocity_first_import_check(){
	global $template_url_first;
	update_option('velocity_first_import','on');
	
	// Theme Options
	update_option('velocity_theme_layout_options',array(
			"velocity_themelayout" => "Full-Width", 
			"velocity_custom_css" => ".serviceicon.withimg img { width: 100px; height: 100px; }", 
			"velocity_responsive" => "1"
	));
	
	update_option('velocity_theme_header_options',array(
			"velocity_img_logo" => $template_url_first."/img/logo.png", 
			"velocity_resp_img_logo" => $template_url_first."/img/logo@2x.png", 
			"velocity_pagetitleimg" => "",
			"velocity_headertopline" => "1", 
			"velocity_margin_top" => "20", 
			"velocity_margin_bottom" => "20", 
			"velocity_headertopline" => "1", 
			"velocity_pagetitleline_color_opacity" => "0.85",
			"velocity_pagetitleline_color" => "#13c0df",
			"velocity_pagetitleline_parallaxspeed" => "150",
			"velocity_pagetitleline_color_style" => 'darkpagetitle',
			"velocity_headersearch" => "1", 
			"velocity_submenuwidth" => "135", 
			"velocity_stickymenu" => "1", 
			"velocity_menu_color" => "#13c0df",
			"velocity_slider_effects" => "none",
			"velocity_parallax_effects" => 0,
			"velocity_header_style" => "lightheader",
	));
	
	update_option('velocity_theme_footer_options',array(
			"velocity_footerwidgetsactive" => "1", 
			"velocity_subfooterwidgetsactive" => "1", 
			"velocity_footer_color" => "#333637",
			"velocity_stickyfooter" => "0",
	));
	
	update_option('velocity_theme_font_options',array(
			"velocity_font_headlineurl" => "http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800", 
			"velocity_font_headlinefamily" => "'Open Sans', sans-serif;"
	));
	
	update_option('velocity_theme_color_options',array(
			"velocity_color_highlight" => "#13c0df"
	));
	
	update_option('velocity_theme_background_options',array(
			"velocity_img_bgdefault" => $template_url_first."/img/tiles/retina_wood.png", 
			"velocity_img_bgtype" => "tiled"
	));
	
	update_option('velocity_theme_search_options',array(
			"velocity_searchresultsnumber" => "10", 
			"velocity_404page" => "2413"
	));
	
	update_option('velocity_theme_sidebars_options',array(
			"velocity_sidebar_builder_name-0" => "Sidebar1", 
			"velocity_sidebar_builder_slug-0" => "sidebar_1366028838"
	));
	
	update_option('damojoPortfolio_theme_portfolios_options',array(
			"damojoPortfolio_portfolio_builder_name-0" => "Our Portfolio", 
			"damojoPortfolio_portfolio_builder_slug-0" => "portfolio"
	));
	
	update_option('damojoPortfolio_theme_portfoliodef_options',array(
			"damojoPortfolio_portfoliolock" => "225", 
			"damojoPortfolio_portfolioarchivesidebar" => "Sidebar Right", 
			"damojoPortfolio_portfolioarchivenumber" => "6", 
			"damojoPortfolio_portfoliopostlayout" => "Large Media", 
			"damojoPortfolio_portfoliorelated" => "1", 
			"damojoPortfolio_portfoliopostinfo_author" => "1", 
			"damojoPortfolio_portfoliopostinfo_category" => "1"
	));
	
	update_option('velocity_theme_blog_options',array(
			"velocity_blogoverviewpostlayout" => "Large Media", 
			"velocity_blogoverviewsingledate" => "1", 
			"velocity_blogoverviewpostinfo_date" => "1", 
			"velocity_blogoverviewpostinfo_author" => "1", 
			"velocity_blogoverviewpostinfo_category" => "1", 
			"velocity_blogoverviewpostinfo_comments" => "1", 
			"velocity_blogoverviewpostinfo_tags" => "1", 
			"velocity_blogoverview_excerpt" => "55",
			"velocity_blogpostlayout" => "Large Media", 
			"velocity_blogrelated" => "1", 
			"velocity_blogpostinfo_date" => "1", 
			"velocity_blogpostinfo_author" => "1", 
			"velocity_blogpostinfo_category" => "1", 
			"velocity_blogpostinfo_comments" => "1", 
			"velocity_blogpostinfo_tags" => "1", 
			"velocity_blogarchivesidebar" => "Sidebar Right", 
			"velocity_blogarchivesidebar_select" => "Blog Sidebar"
	));
	
	header('Location: '.$template_url_first.'/functions/theme_options/theme_preset_ocean_blue_1.php');
}
?>