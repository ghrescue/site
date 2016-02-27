<?php
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
require_once( $path_to_wp.'/wp-includes/functions.php');

$template_url_first = get_template_directory_uri();
$velocity_themeoptions = velocity_getThemeOptions();

//SMOKED GREY
	global $template_url_first;
	
	$velocity_themeoptions["velocity_img_logo"] = isset($velocity_themeoptions["velocity_img_logo"]) ? $velocity_themeoptions["velocity_img_logo"] : $template_url_first."/img/logo.png";
	$velocity_themeoptions["velocity_resp_img_logo"] = isset($velocity_themeoptions["velocity_resp_img_logo"]) ? $velocity_themeoptions["velocity_resp_img_logo"] : $template_url_first."/img/logo@2x.png";
	$velocity_themeoptions["velocity_headertopline"] = isset($velocity_themeoptions["velocity_headertopline"]) ? $velocity_themeoptions["velocity_headertopline"] : "1";
	$velocity_themeoptions["velocity_margin_top"] = isset($velocity_themeoptions["velocity_margin_top"]) ? $velocity_themeoptions["velocity_margin_top"] : "20"; 
	$velocity_themeoptions["velocity_margin_bottom"] = isset($velocity_themeoptions["velocity_margin_bottom"]) ? $velocity_themeoptions["velocity_margin_bottom"] : "20";
	$velocity_themeoptions["velocity_pagetitleline_parallaxspeed"] = isset($velocity_themeoptions["velocity_pagetitleline_parallaxspeed"]) ? $velocity_themeoptions["velocity_pagetitleline_parallaxspeed"] : "150";
	$velocity_themeoptions["velocity_pagetitleimg"] = isset($velocity_themeoptions["velocity_pagetitleimg"]) ? $velocity_themeoptions["velocity_pagetitleimg"] : ""; 
	$velocity_themeoptions["velocity_pagetitleimg_id"] = isset($velocity_themeoptions["velocity_pagetitleimg_id"]) ? $velocity_themeoptions["velocity_pagetitleimg_id"] : ""; 
	$velocity_themeoptions["velocity_headersearch"] = isset($velocity_themeoptions["velocity_headersearch"]) ? $velocity_themeoptions["velocity_headersearch"] : "1";
	$velocity_themeoptions["velocity_submenuwidth"] = isset($velocity_themeoptions["velocity_submenuwidth"]) ? $velocity_themeoptions["velocity_submenuwidth"] : "135";
	$velocity_themeoptions["velocity_stickymenu"] = isset($velocity_themeoptions["velocity_stickymenu"]) ? $velocity_themeoptions["velocity_stickymenu"] : "1";
	$velocity_themeoptions["velocity_slider_effects"] =  isset($velocity_themeoptions["velocity_slider_effects"]) ? $velocity_themeoptions["velocity_slider_effects"] : "none";
	$velocity_themeoptions["velocity_parallax_effects"] =  isset($velocity_themeoptions["velocity_parallax_effects"]) ? $velocity_themeoptions["velocity_parallax_effects"] : "lightheader";
	
	// Theme Options
	update_option('velocity_theme_layout_options',array(
			"velocity_themelayout" => "Full-Width", 
			"velocity_custom_css" => $velocity_themeoptions["velocity_custom_css"], 
			"velocity_responsive" => "1"
	));
	
	update_option('velocity_theme_header_options',array(
			"velocity_img_logo" => $velocity_themeoptions["velocity_img_logo"], 
			"velocity_resp_img_logo" => $velocity_themeoptions["velocity_resp_img_logo"], 
			"velocity_headertopline" => $velocity_themeoptions["velocity_headertopline"], 
			"velocity_margin_top" => $velocity_themeoptions["velocity_margin_top"], 
			"velocity_margin_bottom" => $velocity_themeoptions["velocity_margin_bottom"], 
			"velocity_pagetitleimg" => $velocity_themeoptions["velocity_pagetitleimg"],
			"velocity_pagetitleline_color_opacity" => "0.8",
			"velocity_pagetitleline_color" => "#93adb9",
			"velocity_pagetitleline_parallaxspeed" => $velocity_themeoptions["velocity_pagetitleline_parallaxspeed"],
			"velocity_pagetitleline_color_style" => 'darkpagetitle',
			"velocity_headersearch" => $velocity_themeoptions["velocity_headersearch"], 
			"velocity_submenuwidth" => $velocity_themeoptions["velocity_submenuwidth"], 
			"velocity_stickymenu" => $velocity_themeoptions["velocity_stickymenu"], 
			"velocity_menu_color" => "#93adb9",
			"velocity_slider_effects" => $velocity_themeoptions["velocity_slider_effects"],
			"velocity_parallax_effects" => 0,
			"velocity_header_style" => "darkheader",
	));

	update_option('velocity_theme_color_options',array(
			"velocity_color_highlight" => "#93adb9"
	));
	
	update_option('velocity_theme_background_options',array(
			"velocity_img_bgdefault" => $template_url_first."/img/tiles/retina_wood.png", 
			"velocity_img_bgtype" => "tiled"
	));
header('Location: '.admin_url('admin.php?page=velocity_theme_options&tab=layout_options'));

?>