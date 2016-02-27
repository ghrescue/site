<?php
define('velocity_FUNCTIONS', get_template_directory() . '/functions/');
define('velocity_THEME', get_template_directory_uri());
define('velocity_JAVASCRIPT', get_template_directory_uri() . '/js');
define('velocity_CSS', get_template_directory_uri() . '/css');
define('velocity_TYPE', get_template_directory_uri() . '/type');

/* Admin Functionality */
if (is_admin()){
	require_once(velocity_FUNCTIONS . '/page_options/theme_page_options.php');
	require_once(velocity_FUNCTIONS . '/theme_options/theme_settings.php');
	if(function_exists("wpb_map")){
		require_once(velocity_FUNCTIONS . '/theme_builder.php');
	}
	require_once(velocity_FUNCTIONS . '/thundercodes/thundericons.php');
	if(get_option('velocity_first_import')!="on"){
		require_once(velocity_FUNCTIONS . '/theme_activate.php');
	}
	require_once(velocity_FUNCTIONS . '/theme_plugins.php');
	require_once(velocity_FUNCTIONS . '/theme_startmessage.php');
	require_once(velocity_FUNCTIONS . '/theme_featured_image_preview.php');
	require_once(velocity_FUNCTIONS . '/theme_docu.php');
}

require_once(velocity_FUNCTIONS . '/navigation/sweet-custom-menu.php');



/* Theme Functionality */
require_once(velocity_FUNCTIONS . '/theme_support.php');
require_once(velocity_FUNCTIONS . '/aq_resize.php');
require_once(velocity_FUNCTIONS . '/theme_functions.php');
require_once(velocity_FUNCTIONS . '/theme_pagination.php');
require_once(velocity_FUNCTIONS . '/theme_javascriptcss.php');
require_once(velocity_FUNCTIONS . '/theme_widgets.php');
require_once(velocity_FUNCTIONS . '/theme_sidebars.php');
require_once(velocity_FUNCTIONS . '/theme_post_comments.php');
require_once(velocity_FUNCTIONS . '/theme_breadcrumbs.php');


if(!is_admin()){
	require_once(velocity_FUNCTIONS . '/theme_options/theme_style_generate.php');
}

if(get_option('velocity_second_import')!="on"){
	require_once(velocity_FUNCTIONS . '/theme_update.php');
}

/* Theme Language */
require_once(velocity_FUNCTIONS . '/theme_language.php');

/* Media Box */
function load_media_box(){
 if(function_exists(wp_enqueue_media())) wp_enqueue_media();
 wp_enqueue_script('thickbox');
 wp_enqueue_style('thickbox');
}
add_action('admin_enqueue_scripts', 'load_media_box'); 
?>