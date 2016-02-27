<?php
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
require_once( $path_to_wp.'/wp-includes/functions.php');

$template_url_first = get_template_directory_uri();
$velocity_themeoptions = velocity_getThemeOptions();

$velocity_themeoptions["velocity_img_logo"] = isset($velocity_themeoptions["velocity_img_logo"]) ? $velocity_themeoptions["velocity_img_logo"] : $template_url_first."/img/logo.png";
$velocity_themeoptions["velocity_resp_img_logo"] = isset($velocity_themeoptions["velocity_resp_img_logo"]) ? $velocity_themeoptions["velocity_resp_img_logo"] : $template_url_first."/img/logo@2x.png";
$velocity_themeoptions["velocity_headertopline"] = isset($velocity_themeoptions["velocity_headertopline"]) ? $velocity_themeoptions["velocity_headertopline"] : "1";
$velocity_themeoptions["velocity_margin_top"] = isset($velocity_themeoptions["velocity_margin_top"]) ? $velocity_themeoptions["velocity_margin_top"] : "20"; 
$velocity_themeoptions["velocity_margin_bottom"] = isset($velocity_themeoptions["velocity_margin_bottom"]) ? $velocity_themeoptions["velocity_margin_bottom"] : "20";
$velocity_themeoptions["velocity_pagetitleline_parallaxspeed"] = isset($velocity_themeoptions["velocity_pagetitleline_parallaxspeed"]) ? $velocity_themeoptions["velocity_pagetitleline_parallaxspeed"] : "150";
$velocity_themeoptions["velocity_pagetitleimg"] = isset($velocity_themeoptions["velocity_pagetitleimg"]) ? $velocity_themeoptions["velocity_pagetitleimg"] : ""; 
$velocity_themeoptions["velocity_pagetitleimg_id"] = isset($velocity_themeoptions["velocity_pagetitleimg_id"]) ? $velocity_themeoptions["velocity_pagetitleimg_id"] : ""; 
$velocity_themeoptions["velocity_headersearch"] = isset($velocity_themeoptions["velocity_headersearch"]) ? $velocity_themeoptions["velocity_headersearch"] : "1";
$velocity_themeoptions["velocity_submenuwidth"] = isset($velocity_themeoptions["velocity_submenuwidth"]) ? $velocity_themeoptions["velocity_submenuwidth"] : "179";
$velocity_themeoptions["velocity_stickymenu"] = isset($velocity_themeoptions["velocity_stickymenu"]) ? $velocity_themeoptions["velocity_stickymenu"] : "1";
$velocity_themeoptions["velocity_slider_effects"] =  isset($velocity_themeoptions["velocity_slider_effects"]) ? $velocity_themeoptions["velocity_slider_effects"] : "none";
$velocity_themeoptions["velocity_parallax_effects"] =  isset($velocity_themeoptions["velocity_parallax_effects"]) ? $velocity_themeoptions["velocity_parallax_effects"] : "lightheader";


if(get_option('velocity_second_import')!="on"){
	velocity_second_import_check();
}

function velocity_second_import_check(){
	global $template_url_first,$velocity_themeoptions;
	update_option('velocity_second_import','on');
	
	// Theme Options
	update_option('velocity_theme_header_options',array(
			"velocity_img_logo" => $velocity_themeoptions["velocity_img_logo"], 
			"velocity_resp_img_logo" => $velocity_themeoptions["velocity_resp_img_logo"], 
			"velocity_pagetitleimg" => $velocity_themeoptions["velocity_pagetitleimg"],
			"velocity_margin_top" => $velocity_themeoptions["velocity_margin_top"], 
			"velocity_margin_bottom" => $velocity_themeoptions["velocity_margin_bottom"], 
			"velocity_headertopline" => $velocity_themeoptions["velocity_headertopline"],
			"velocity_pagetitleline_color_opacity" => $velocity_themeoptions["velocity_pagetitleline_color_opacity"],
			"velocity_pagetitleline_color" => $velocity_themeoptions["velocity_pagetitleline_color"],
			"velocity_pagetitleline_parallaxspeed" => $velocity_themeoptions["velocity_pagetitleline_parallaxspeed"],
			"velocity_pagetitleline_color_style" => $velocity_themeoptions["velocity_pagetitleline_color_style"],
			"velocity_headersearch" => $velocity_themeoptions["velocity_headersearch"], 
			"velocity_submenuwidth" => '179', 
			"velocity_stickymenu" => $velocity_themeoptions["velocity_stickymenu"], 
			"velocity_menu_color" => $velocity_themeoptions["velocity_menu_color"],
			"velocity_slider_effects" => $velocity_themeoptions["velocity_slider_effects"],
			"velocity_parallax_effects" => $velocity_themeoptions["velocity_parallax_effects"],
			"velocity_header_style" => $velocity_themeoptions["velocity_header_style"],
	));
}
?>