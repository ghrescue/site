<?php

// GET FEATURED IMAGE
function velocity_ST4_get_featured_image($post_ID){
	 $post_thumbnail_id = get_post_thumbnail_id($post_ID);
	 if ($post_thumbnail_id){
	  $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id);
	  return $post_thumbnail_img[0];
	 }
	 else {
		 $imageoptions =  get_post_meta($post_ID,"velocity_background_src",true) ;
		 $backgroundimage = wp_get_attachment_image_src($imageoptions,"full");
		 if(!empty($backgroundimage[0]))
		 return $backgroundimage[0];
	 }
}

// ADD NEW COLUMN
function velocity_ST4_columns_head($defaults) {
 $defaults['featured_image'] = __('Featured Image','velocity');
 return $defaults;
}

// SHOW INFO IN THE NEW COLUMN
function velocity_ST4_columns_content($column_name, $post_ID) {
 if ($column_name == 'featured_image') {
  $post_featured_image = velocity_ST4_get_featured_image($post_ID);
  if ($post_featured_image){
   echo '<img src="' . aq_resize($post_featured_image,55,55,true) . '" />'; 
  }
 }
}

add_filter('manage_posts_columns', 'velocity_ST4_columns_head');
add_filter('manage_posts_custom_column', 'velocity_ST4_columns_content', 10, 2);

/**************************************************************************/
?>