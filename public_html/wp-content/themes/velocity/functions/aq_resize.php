<?php

if(!function_exists('aq_resize')){
	/**
* Title		: Aqua Resizer
* Description	: Resizes WordPress images on the fly
* Version	: 1.1.4
* Author	: Syamil MJ
* Author URI	: http://aquagraphite.com
* License	: WTFPL - http://sam.zoy.org/wtfpl/
* Documentation	: https://github.com/sy4mil/Aqua-Resizer/
*
* @param string $url - (required) must be uploaded using wp media uploader
* @param int $width - (required)
* @param int $height - (optional)
* @param bool $crop - (optional) default to soft crop
* @param bool $single - (optional) returns an array if false
* @uses wp_upload_dir()
*
* @return str|array
*/

function aq_resize( $url, $width, $height = null, $crop = null, $single = true, $retina = false ) {
		
		 //validate inputs
		 if(!$url OR !$width ) return false;
		
		 //define upload path & dir
		 $upload_info = wp_upload_dir();
		 $upload_dir = $upload_info['basedir'];
		 $upload_url = $upload_info['baseurl'];
		
		 //check if $img_url is local
		 if(strpos( $url, $upload_url ) === false) return false;
		
		 //define path of image
		 $rel_path = str_replace( $upload_url, '', $url);
		 $img_path = $upload_dir . $rel_path;
		
		 //check if img path exists, and is an image indeed
		 if( !file_exists($img_path) OR !getimagesize($img_path) ) return false;
		
		 //get image info
		 $info = pathinfo($img_path);
		 $ext = $info['extension'];
		 list($orig_w,$orig_h) = getimagesize($img_path);
		
		 //get image size after cropping
		 $dims = image_resize_dimensions($orig_w, $orig_h, $width, $height, $crop);
		 $dst_w = $dims[4];
		 $dst_h = $dims[5];
		
		 //use this to check if cropped image already exists, so we can return that instead
		 $suffix = "{$dst_w}x{$dst_h}";
		 $dst_rel_path = str_replace( '.'.$ext, '', $rel_path);
		 $destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";
		
		 //Retina Image
		 if($retina){
		  if(!$dst_h) {
		   //can't resize, so return original url
		   $img_url = $url;
		   $dst_w = $orig_w;
		   $dst_h = $orig_h;
		  }
		  //else check if cache exists
		  elseif(file_exists(str_replace(".".$ext,"@2x.".$ext,$destfilename)) && getimagesize(str_replace(".".$ext,"@2x.".$ext,$destfilename))) {
		   $img_url = "{$upload_url}{$dst_rel_path}-{$suffix}@2x.{$ext}";
		  } 
		  //else, we resize the image and return the new resized image url
		  else {
		   if(function_exists('wp_get_image_editor')) {
		    $editor = wp_get_image_editor($img_path);
		    if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width*2, $height*2, $crop ) ) )
		     return false;
		 
		    $resized_img_path = $editor->save();
		 
		   } else {
		    	//$resized_img_path = image_resize( $img_path, $width*2, $height*2, $crop ); // Fallback foo
		    	return false;
		   }
		 
		   if(!is_wp_error($resized_img_path)) {
		    rename($resized_img_path["path"], str_replace(".".$ext,"@2x.".$ext,$destfilename));
		   } else {
		    return false;
		   }
		 
		  }
		 }
		 
		
		 if(!$dst_h) {
		  //can't resize, so return original url
		  $img_url = $url;
		  $dst_w = $orig_w;
		  $dst_h = $orig_h;
		 }
		 //else check if cache exists
		 elseif(file_exists($destfilename) && getimagesize($destfilename)) {
		  $img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
		 } 
		 //else, we resize the image and return the new resized image url
		 else {
		  if(function_exists('wp_get_image_editor')) {
		   $editor = wp_get_image_editor($img_path);
		
		   if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
		    return false;
		
		   $resized_img_path = $editor->save();
		
		  } else {
		   //$resized_img_path = image_resize( $img_path, $width, $height, $crop ); // Fallback foo
		   return false;
		  }
		  try{
			  if(!is_wp_error($resized_img_path)) {
			   $resized_rel_path = str_replace( $upload_dir, '', $resized_img_path);
			   $img_url = $upload_url . $resized_rel_path['path'];
			  } else {
			   return false;
			  }
		  }  catch (Exception $e) {return false;}
		  
		
		 }
		
		 //return the output
		 if($single) {
		  //str return
		  $image = $img_url;
		 } else {
		  //array return
		  $image = array (
		   0 => $img_url,
		   1 => $dst_w,
		   2 => $dst_h
		  );
		 }
		
		
		$image = $suffix == "x" ? "{$upload_url}{$dst_rel_path}.{$ext}" : "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}"; 
		
		 return $image;
		}
}
?>