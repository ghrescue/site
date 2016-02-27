<?php
/* ------------------------------------- */
/* ENABLING FUNCTION SUPPORT */
/* ------------------------------------- */

if (function_exists('add_theme_support')){
	add_theme_support( 'post-thumbnails');
	
	add_theme_support( 'menus');
	add_theme_support( 'custom-background');
	add_theme_support( 'custom-header');
}
if (function_exists('automatic-feed-links')) {
	add_theme_support( 'automatic-feed-links');
}

if (function_exists("gradil-feed")){
	the_post_thumbnail();
	add_editor_style();
}

remove_action ('wp_head', 'rsd_link');
remove_action ('wp_head', 'wlwmanifest_link');
remove_action ('wp_head', 'wp_generator');
?>