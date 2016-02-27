<?php //Creating TinyMCE buttons
//********************************************************************
//check user has correct permissions and hook some functions into the tiny MCE architecture.
function add_editor_button() {
   //Check if user has correct level of privileges + hook into Tiny MC methods.
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
   {
     //Check if Editor is in Visual, or rich text, edior mode.
     if (get_user_option('rich_editing')) {
        //Called when tiny MCE loads plugins - 'add_custom' is defined below.
        add_filter('mce_external_plugins', 'add_custom');
        //Called when buttons are loading. -'register_button' is defined below.
        add_filter('mce_buttons', 'register_button');
     }
   }
} 
 
add_action('init', 'add_editor_button');

//Add button to the button array.
function register_button($buttons) {
   //Use PHP 'array_push' function to add the columnThird button to the $buttons array
   array_push($buttons, "columnThird");
   //Return buttons array to TinyMCE
   return $buttons;
} 
 
//Add custom plugin to TinyMCE - returns associative array which contains link to JS file. The JS file will contain your plugin when created in the following step.
function add_custom($plugin_array) {
       $plugin_array['columnThird'] = get_template_directory_uri().'/functions/thundercodes/thundericons_plugin.js';
       return $plugin_array;
}

function fontello(){
	wp_enqueue_style( 'velocity_fontello_style',velocity_TYPE.'/fontello.css');
}
add_action('init', 'fontello');

?>