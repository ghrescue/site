<?php
/* ------------------------------------- */
/* ENQUEUE JAVASCRIPTS + CSS */
/* ------------------------------------- */

function velocity_loadJSCSS() {

	$velocity_absolute_path = __FILE__;
	$velocity_path_to_file = explode( 'wp-content', $velocity_absolute_path );
	$velocity_path_to_wp = $velocity_path_to_file[0];
	require_once( $velocity_path_to_wp.'/wp-load.php' );

	// Themeoptions
	$velocity_themeoptions = velocity_getThemeOptions();

	/* Google Font */
	$velocity_headlinefonturl = $velocity_themeoptions["velocity_font_headlineurl"];

	wp_enqueue_script( 'jquery' );

	//Google Font
	if(!empty($velocity_headlinefonturl)) wp_enqueue_style( 'velocity_googlefont_style',$velocity_headlinefonturl,null);

	//Fontello Icons
    wp_enqueue_style( 'velocity_fontello_style',velocity_TYPE.'/fontello.css',null);

    //Style for Visual Builder
	if(function_exists("vc_map")){
		wp_enqueue_style('js_composer_front-css', plugins_url().'/js_composer/assets/css/js_composer.css', null);
	}



    //FancyBox2 Helpers
    wp_enqueue_style( 'velocity_fancybox_style',velocity_JAVASCRIPT.'/fancybox/jquery.fancybox.css',null);

	//Slider Style
	wp_enqueue_style( 'velocity_slider_style',velocity_CSS.'/slider.css',null);
	
	// Enqueue the Theme Styles
	wp_enqueue_style( 'velocity_bootstrap_style',velocity_CSS.'/bootstrap.min.css',null);

//<script> videojs.options.flash.swf = velocity_CSS."/videojs/video-js.swf"

	wp_enqueue_style( 'velocity_videojs',velocity_JAVASCRIPT.'/videojs/video-js.min.css',null);
	
	wp_enqueue_style( 'velocity_bootstrap-responsive_style',velocity_CSS.'/bootstrap-responsive.min.css',null);
	

	// Main CSS
	
    wp_enqueue_style( 'velocity_wp_style',get_stylesheet_directory_uri().'/style.css',null);

	// Enqueue the Theme JS
	// Basics
	wp_enqueue_script('themepunchtools', velocity_JAVASCRIPT."/jquery.themepunch.plugins.min.js", array('jquery'),null,true);
	wp_enqueue_script('velocity_modernizr_script', velocity_JAVASCRIPT."/jquery.modernizr.min.js", array('jquery'),null,true);
	wp_enqueue_script('velocity_isotope_script', velocity_JAVASCRIPT."/jquery.isotope.min.js", array('jquery'),null,true);
	wp_enqueue_script('velocity_isotope_script', velocity_JAVASCRIPT."/jquery.isotope.min.js", array('jquery'),null,true);
	wp_enqueue_script('velocity_waypoint_script', velocity_JAVASCRIPT."/waypoints.min.js", array('jquery'),null,true);
	
	//wp_enqueue_script('velocity_easing_script', velocity_JAVASCRIPT."/jquery.easing.1.3.js", array('jquery'),false,true);
	wp_enqueue_script('velocity_fitvid_script', velocity_JAVASCRIPT."/jquery.fitvid.js", array('jquery'),null,true);
	wp_enqueue_script('velocity_backstretch_script', velocity_JAVASCRIPT."/jquery.backstretch.min.js", array('jquery'),null,false);
	
	wp_enqueue_script('velocity_bootstrap_script', velocity_JAVASCRIPT."/bootstrap.min.js", array('jquery'),null,true);
	//wp_enqueue_script('velocity_menu_script', velocity_JAVASCRIPT."/ddsmoothmenu.js", array('jquery'),null,true);
	wp_enqueue_script('velocity_fancybox_script', velocity_JAVASCRIPT."/fancybox/jquery.fancybox.pack.js", array('jquery'),null,true);
	wp_enqueue_script('velocity_fancybox_script_media', velocity_JAVASCRIPT."/fancybox/helpers/jquery.fancybox-media.js", array('jquery'),null,true);
	wp_enqueue_script('velocity_retina_script', velocity_JAVASCRIPT."/retina.js", array('jquery'),null,true);
	
	wp_enqueue_script('velocity_videojs_script', velocity_JAVASCRIPT."/videojs/video.js", array('jquery'),null,true);	
	wp_enqueue_script('velocity_videohelper_script', velocity_JAVASCRIPT."/videohelper.js", array('jquery'),null,true);	
	wp_localize_script('velocity_videohelper_script', 'velocity_vars', array(
			'jspath' => velocity_JAVASCRIPT
		)
	);
	
	//PREVIEW COOKIE LOAD IF NEEDED
	//wp_enqueue_style( 'velocity_wp_preview_style',get_stylesheet_directory_uri().'/'.getCookies());

	// Main Script
	wp_enqueue_script('velocity_screen_script', velocity_JAVASCRIPT."/screen.js", array('jquery'),null,true);
	
	//Comments
	if(is_singular() && get_option("thread_comments")) wp_enqueue_script("comment-reply");
		
}
add_action('wp_enqueue_scripts', 'velocity_loadJSCSS');

/*	
function velocity_enqueue_lt_ie9() { 
		global $is_IE;
	    // Return early, if not IE
	    if ( ! $is_IE ) return;
	    echo '<!--[if lt IE 9]>'; 
		echo '<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>'; 
		echo '<![endif]-->'; 
		global $wp_styles; 
		wp_enqueue_style('velocity_ie8_css',velocity_CSS.'/ie8.css'); 
		$wp_styles->add_data( 'velocity_ie8_css', 'conditional', 'IE 8'); 
}

add_action( 'wp_enqueue_scripts', 'velocity_enqueue_lt_ie9' );*/


function velocity_load_custom_wp_admin_style(){
    wp_register_style( 'velocity_custom_wp_page_admin_css', velocity_CSS.'/page_options.css', false, null );
    wp_register_style( 'velocity_custom_wp_theme_admin_css', velocity_CSS.'/theme_options.css', false, null );
    wp_enqueue_style( 'velocity_custom_wp_page_admin_css' );
    wp_enqueue_style( 'velocity_custom_wp_theme_admin_css' );
    wp_enqueue_style( 'wp-color-picker');
	wp_enqueue_script( 'wp-color-picker');
	wp_enqueue_style( 'moose_menu_back_css',velocity_CSS.'/menu.css');
}
add_action('admin_enqueue_scripts', 'velocity_load_custom_wp_admin_style');
   
// COOKIE LOADING IN CASE YOU NEED ?!
function getCookies() {
	$getpreview = $_COOKIE['velocitypreview']!='' ? $_COOKIE['velocitypreview'] : "velocity1.css";
	return $getpreview;
}
?>