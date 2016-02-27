<?php

vc_set_as_theme($notifier = false);
if(function_exists('vc_disable_frontend'))  vc_disable_frontend();

// Targets
	$target_arr = array(__("Same window", "js_composer") => "_self", __("New window", "js_composer") => "_blank");

//Showbiz
	if(shortcode_exists("showbiz")){
		global $wpdb;
		global $table_prefix;
		//$table_prefix = $wpdb->base_prefix;
		
		$showbiz_arr = array();
		
		//if (!isset($wpdb->tablename)) {
		$wpdb->tablename = $table_prefix . 'showbiz_sliders';
		//}
		$showbiz_sliders = $wpdb->get_results( 
			"
			SELECT title,alias 
			FROM $wpdb->tablename
			"
		);
		foreach ( $showbiz_sliders as $showbiz_slider ) 
		{
			$showbiz_arr[$showbiz_slider->title] = $showbiz_slider->alias;
		}

	}
// Portfolios
	$portfolios = get_option("damojoPortfolio_theme_portfolios_options");
	$portfolio_slugs = array();
	$portfolio_name = array();
	$j = 1;
	if(is_array($portfolios)){
		foreach($portfolios as $key => $value){
			if($j%2==0){
	            array_push($portfolio_slugs,$value);
	            $j = 0 ;
	        }
	        else{
	            array_push($portfolio_name,$value);
	        }
	    	$j++;
		}
	}
	
	$portfolio_counter = 0;
	$portfolio_arr = array();
	if(is_array($portfolio_slugs))
		foreach ( $portfolio_slugs as $slug ){
				$portfolio_arr[$portfolio_name[$portfolio_counter++]] = "$slug";
		}

// Blog Categories
$category_arr = array("All"=>"");
$cat_args=array(
  'orderby' => 'name',
  'order' => 'ASC'
   );
$categories=get_categories($cat_args);
  foreach($categories as $category) {
	  $category_arr[$category->name] = $category->slug;
  }

$style_array = array("info"=>"info", "warning"=>"warning", "success"=>"success", "danger"=>"danger");
$button_style_array = array("default"=>"","primary"=>"primary","info"=>"info", "warning"=>"warning", "success"=>"success", "danger"=>"danger","inverse"=>"inverse");
$box_style_array = array("default"=>"","info"=>"info", "success"=>"success", "error"=>"error");
$enadisa_array = array("default"=>"disabled","Enabled"=>"enabled","Disabled"=>"disabled");
$contentcolor_array = array("default"=>"dark-on-light","Dark On Light"=>"dark-on-light","Light On Dark"=>"light-on-dark");

// Delete Standard Elements
//vc_remove_element("vc_message");
		
	if(shortcode_exists("showbiz")){
			wpb_map( array(
			   "name" => __("Showbiz", "js_composer"),
			   "base" => "showbiz_build",
			   "class" => "",
			   "icon"      => "icon-wpb-projects",
			   "category" => __('Content', "js_composer"),
			   "params" => array(
			   	  array(
			            "type" => "dropdown",
			            "heading" => __("Showbiz Slider", "js_composer"),
			            "param_name" => "showbiz",
			            "value" => $showbiz_arr ,
			            "admin_label" => true,
			            "description" => __("Select from the Showbiz Sliders.", "js_composer")
			        ),
			   )
			) );
		}
	
	wpb_map( array(
		   "name" => __("Service", "js_composer"),
		   "base" => "service_block",
		   "class" => "",
		   "icon"      => "icon-wpb-service",
		   "controls" => "full",
		   "category" => __('Content', "js_composer"),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   "params" => array(
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Title", "js_composer"),
		         "param_name" => "title",
		         "value" => __("Head Title", "js_composer"),
		         "description" => __("The big Head Title.", "js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("SubTitle", "js_composer"),
		         "param_name" => "subtitle",
		         "value" => __("", "js_composer"),
		         "description" => __("The smaller Headline under the title (optional).","js_composer")
		      ),
		       array(
		            "type" => "attach_images",
		            "heading" => __("Icon Image", "js_composer"),
		            "param_name" => "iconimage",
		            "value" => "",
		            "description" => "Choose an Image for the Service"
		        ),
		      array(
		            "type" => "textfield",
		            "heading" => __("Icon", "js_composer", "js_composer"),
		            "param_name" => "icon",
		            "admin_label" => true,
		            "value" => "",//$reticonarray,
		            "description" => __("Select an Icon, a clickable list will be shown when you enter the field" , "js_composer")
		      ),
		       array(
		            "type" => "textfield",
		            "heading" => __("URL (Link)", "js_composer"),
		            "param_name" => "href",
		            "value" => "",
		            "description" => __("Service Block link.", "js_composer")
		        ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Link target", "js_composer"),
		            "param_name" => "target",
		            "value" => $target_arr
		        ),
		      array(
		         "type" => "textarea_html",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Content", "js_composer"),
		         "param_name" => "content",
		         "value" => __("<p>I am test text block. Click edit button to change this text.</p>", "js_composer"),
		         "description" => __("Enter your content.", "js_composer")
		      )
		   )
		) );
		
		wpb_map( array(
		   "name" => __("Headline", "js_composer"),
		   "base" => "velocity_headline",
		   "class" => "",
		   "icon"      => "icon-wpb-headline",
		   "controls" => "full",
		   "category" => __('Content', "js_composer"),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   "params" => array(
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Title", "js_composer"),
		         "param_name" => "title",
		         "value" => __("Head Title", "js_composer"),
		         "description" => __("The Headline itself.", "js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Linktext", "js_composer"),
		         "param_name" => "linktext",
		         "value" => __("","js_composer"),
		         "description" => __("The Link Text appearing on the right.", "js_composer")
		      ),
		      array(
		            "type" => "textfield",
		            "heading" => __("URL (Link)", "js_composer"),
		            "param_name" => "link",
		            "value" => "",
		            "description" => __("URL to link to.", "js_composer")
		        ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Link target", "js_composer"),
		            "param_name" => "target",
		            "value" => $target_arr
		        ),
		      array(
		            "type" => "textfield",
		            "heading" => __("Extra class name", "js_composer"),
		            "param_name" => "el_class",
		            "value" => "",
		            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
		        )
		   )
		) );
		
		
		wpb_map( array(
		   "name" => __("Latest Projects", "js_composer"),
		   "base" => "latest_projects",
		   "class" => "",
		   "icon"      => "icon-wpb-projects",
		   "category" => __('Content', "js_composer"),
		   "params" => array(
		   	  array(
		            "type" => "dropdown",
		            "heading" => __("Portfolio", "js_composer"),
		            "param_name" => "portfolio",
		            "value" => $portfolio_arr ,
		            "admin_label" => true,
		            "description" => __("Select Portfolio. Please use the Latest Projects only in a full row.", "js_composer")
		        ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Columns count", "js_composer"),
		            "param_name" => "columns",
		            "value" => array(5, 4, 3),
		            "admin_label" => true,
		            "description" => __("Select columns count.", "js_composer")
		        ),
		        array(
		            "type" => "textfield",
		            "heading" => __("Items count", "js_composer"),
		            "param_name" => "items",
		            "value" => "",// array(10,9,8,7,6,5,4,3),
		            "admin_label" => true,
		            "description" => __("How many items to display at max.", "js_composer")
		        ),
		        array(
		            "type" => "textfield",
		            "heading" => __("Lock Height (px)", "js_composer"),
		            "param_name" => "height",
		            "value" => "",
		            "description" => __("Lock the height to this (Please enter a height in respect to the width of 400px).", "js_composer")
		        ),
		        array(
		            "type" => "dropdown",
		            "heading" => __("Hide Filter?", "js_composer"),
		            "param_name" => "filter",
		            "value" => array("off"=>"","on"=>"on"),
		            "admin_label" => true,
		            //"description" => __("Select items count.", "js_composer")
		        ),
		   )
		) );
		
		/*! Latest Blog Posts */
		wpb_map( array(
		   "name" => __("Latest Blog Posts", "js_composer"),
		   "base" => "latest_posts",
		   "class" => "",
		   "controls" => "full",
		   "icon"      => "icon-wpb-posts",
		   "category" => __('Content', "js_composer"),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   "params" => array(
		      array(
		            "type" => "dropdown",
		            "heading" => __("Category", "js_composer"),
		            "param_name" => "category",
		            "value" => $category_arr ,
		            "admin_label" => true,
		            "description" => __("Select Category.", "js_composer")
		        ),
		     array(
		            "type" => "textfield",
		            "heading" => __("Items count", "js_composer"),
		            "param_name" => "number",
		            "value" => 'all',
		            "admin_label" => true,
		            "description" => __("Select items count.", "js_composer")
		        ),
		     array(
		            "type" => "dropdown",
		            "heading" => __("Columns", "js_composer"),
		            "param_name" => "columns",
		            "value" => array(3,2,1),
		            "admin_label" => true,
		            "description" => __("Select columns to display Posts in.", "js_composer")
		        ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Excerpt Words", "js_composer"),
		            "param_name" => "excerpt_words",
		            "value" => array(55,40,30,20,10),
		            "admin_label" => true,
		            "description" => __("Display how many words?", "js_composer")
		        ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Display Category Info?", "js_composer"),
		            "param_name" => "category_info",
		            "value" => array(""=>"off","on"=>"on"),
		            "admin_label" => true,
		            //"description" => __("Select items count.", "js_composer")
		        ),
		       array(
		            "type" => "dropdown",
		            "heading" => __("Display Date Info?", "js_composer"),
		            "param_name" => "date_info",
		            "value" => array(""=>"off","on"=>"on"),
		            "admin_label" => true,
		            //"description" => __("Select items count.", "js_composer")
		        ),
		        array(
		            "type" => "dropdown",
		            "heading" => __("Display Comment Info?", "js_composer"),
		            "param_name" => "comments_info",
		            "value" => array(""=>"off","on"=>"on"),
		            "admin_label" => true,
		            //"description" => __("Select items count.", "js_composer")
		        ),
		   )
		) );

		wpb_map( array(
		   "name" => __("Spacer", "js_composer"),
		   "base" => "spacer",
		   "class" => "",
		   "icon"      => "icon-wpb-spacer",
		   "controls" => "full",
		   "category" => __('Content','js_composer'),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   "params" => array(
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Height in px",'js_composer'),
		         "param_name" => "height",
		         "value" => "35",
		         "description" => __("Spacer of this height.",'js_composer')
		      ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Hide in Mobile View?", "js_composer"),
		            "param_name" => "hidemobile",
		            "value" => array("off"=>"","on"=>"on"),
		            "admin_label" => true,
		            //"description" => __("Select items count.", "js_composer")
		        ),
		     array(
		            "type" => "dropdown",
		            "heading" => __("Show only in Mobile View?", "js_composer"),
		            "param_name" => "visiblemobile",
		            "value" => array("off"=>"","on"=>"on"),
		            "admin_label" => true,
		            //"description" => __("Select items count.", "js_composer")
		        ),
		   )
		) );	
		
		wpb_map( array(
		   "name" => __("Progress Bar",'js_composer'),
		   "base" => "progressbar",
		   "class" => "",
		   "icon"      => "icon-wpb-progress",
		   "controls" => "full",
		   "category" => __('Content','js_composer'),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   "params" => array(
		   	array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Title",'js_composer'),
		         "param_name" => "title",
		         "value" => __("","js_composer"),
		         "description" => __("Title to display on the Progressbar",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Progress in %",'js_composer'),
		         "param_name" => "percent",
		         "value" => __("50",'js_composer'),
		         "description" => __("Progress to display in %",'js_composer')
		      ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Style", "js_composer"),
		            "param_name" => "style",
		            "value" => $style_array ,
		            "admin_label" => true,
		            "description" => __("Select Color style.", "js_composer")
		        ),
		   )
		) );		
		
		wpb_map( array(
		   "name" => __("Highlight Box",'js_composer'),
		   "base" => "highlightbox",
		   "class" => "",
		   "icon"      => "icon-wpb-highlightbox",
		   "controls" => "full",
		   "category" => __('Content','js_composer'),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   'default_content' => '',
		   "params" => array(
		      array(
		         "type" => "textarea_html",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Content",'js_composer'),
		         "param_name" => "content",
		         "value" => __('This is the highlight box text.','js_composer'),
		         "description" => __("Enter your content.",'js_composer')
		      )
		   )
		) );
		
		wpb_map( array(
		   "name" => __("Checklist",'js_composer'),
		   "base" => "checklist",
		   "class" => "",
		   "icon"      => "icon-wpb-checklist",
		   "controls" => "full",
		   "category" => __('Content','js_composer'),
		    "wrapper_class" => "clearfix",
		      "controls"	=> "full",
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => array(get_template_directory_uri().'/css/theme_builder.css'),
		   'default_content' => '
		        [vc_column width="1/1"][/vc_column]
		    ',
		   "params" => array(
		      array(
		         "type" => "textarea_html",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Include List here",'js_composer'),
		         "param_name" => "content",
		         "value" => __('<ul><li>1 item</li><li>2 item</li></ul>','js_composer'),
		         "description" => __("Enter your content.",'js_composer')
		      )
		   )
		) );	
		
		wpb_map( array(
		    "name"		=> __("Clients List", "js_composer"),
		    "base"		=> "clients_list",
		    "class"		=> "wpb_vc_gallery_widget",
			"icon"		=> "icon-wpb-clients",
		    "category"  => __('Content', 'js_composer'),
		    "params"	=> array(
		        array(
		            "type" => "attach_images",
		            "heading" => __("Images", "js_composer"),
		            "param_name" => "images",
		            "value" => "",
		            "description" => ""
		        ),
		        array(
		            "type" => "exploded_textarea",
		            "heading" => __("Tooltips", "js_composer"),
		            "param_name" => "tooltips",
		            "description" => __('Enter tooltips (no ","(comma) please) for each slide here. Divide links with linebreaks (Enter).', 'js_composer')
		        ),
		        array(
		            "type" => "exploded_textarea",
		            "heading" => __("Links", "js_composer"),
		            "param_name" => "links",
		            "description" => __('Enter links for each slide here. Divide links with linebreaks (Enter).', 'js_composer')
		        ),
		        array(
		            "type" => "dropdown",
		            "heading" => __("Custom link target", "js_composer"),
		            "param_name" => "custom_links_target",
		            "description" => __('Select where to open  custom links.', 'js_composer'),
		            'value' => $target_arr
		        ),
		        array(
		            "type" => "textfield",
		            "heading" => __("Extra class name", "js_composer"),
		            "param_name" => "el_class",
		            "value" => "",
		            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
		        )
		    )
		) );
		
	//	class WPBakeryShortCode_Team extends WPBakeryShortCode_VC_Row {}

		wpb_map( array(
		   "name" => __("Team Row",'js_composer'),
		   "base" => "team",
		   "class" => "",
		   "icon"      => "icon-wpb-team",
		   "controls" => "full",
		   "category" => __('Content','js_composer'),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   "params" => array(
		   	array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("<h2>Member 1</h2>Name",'js_composer'),
		         "param_name" => "name_1",
		         "value" => __("",'js_composer'),
		         "description" => __("Name of the Person.",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Position",'js_composer'),
		         "param_name" => "position_1",
		         "value" => __("",'js_composer'),
		         "description" => __("Position description or an other sub headline.",'js_composer')
		      ),
		     array(
	  			"type" => "attach_image",
	  			"heading" => __("Image", "js_composer"),
	  			"param_name" => "image_id_1",
	  			"value" => "",
	  
	  			"description" => ""
	  		),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Mail",'js_composer'),
		         "param_name" => "mail_1",
		         "value" => __("",'js_composer'),
		         "description" => __("The e-Mail address.",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Phone",'js_composer'),
		         "param_name" => "phone_1",
		         "value" => __("",'js_composer'),
		         "description" => __("Phone Number",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Facebook",'js_composer'),
		         "param_name" => "facebook_1",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Facebook profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Twitter",'js_composer'),
		         "param_name" => "twitter_1",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Twitter profile",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Google Plus",'js_composer'),
		         "param_name" => "gplus_1",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Google Plus profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("LinkedIn",'js_composer'),
		         "param_name" => "linkedin_1",
		         "value" => __("","js_composer"),
		         "description" => __("Link to LinkedIn profile",'js_composer')
		      ),
		      array(
		         "type" => "textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Description",'js_composer'),
		         "param_name" => "content_1",
		         "value" => __('','js_composer'),
		         "description" => __("Enter your content.",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("<h2>Member 2</h2>Name",'js_composer'),
		         "param_name" => "name_2",
		         "value" => __("",'js_composer'),
		         "description" => __("Name of the Person.",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Position",'js_composer'),
		         "param_name" => "position_2",
		         "value" => __("",'js_composer'),
		         "description" => __("Position description or an other sub headline.",'js_composer')
		      ),
		     array(
	  			"type" => "attach_image",
	  			"heading" => __("Image", "js_composer"),
	  			"param_name" => "image_id_2",
	  			"value" => "",
	  
	  			"description" => ""
	  		),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Mail",'js_composer'),
		         "param_name" => "mail_2",
		         "value" => __("",'js_composer'),
		         "description" => __("The e-Mail address.",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Phone",'js_composer'),
		         "param_name" => "phone_2",
		         "value" => __("",'js_composer'),
		         "description" => __("Phone Number",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Facebook",'js_composer'),
		         "param_name" => "facebook_2",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Facebook profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Twitter",'js_composer'),
		         "param_name" => "twitter_2",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Twitter profile",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Google Plus",'js_composer'),
		         "param_name" => "gplus_2",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Google Plus profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("LinkedIn",'js_composer'),
		         "param_name" => "linkedin_2",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to LinkedIn profile",'js_composer')
		      ),
		      array(
		         "type" => "textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Description",'js_composer'),
		         "param_name" => "content_2",
		         "value" => __('','js_composer'),
		         "description" => __("Enter your content.",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("<h2>Member 3</h2>Name",'js_composer'),
		         "param_name" => "name_3",
		         "value" => __("",'js_composer'),
		         "description" => __("Name of the Person.",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Position",'js_composer'),
		         "param_name" => "position_3",
		         "value" => __("",'js_composer'),
		         "description" => __("Position description or an other sub headline.",'js_composer')
		      ),
		     array(
	  			"type" => "attach_image",
	  			"heading" => __("Image", "js_composer",'js_composer'),
	  			"param_name" => "image_id_3",
	  			"value" => "",
	  
	  			"description" => ""
	  		),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Mail",'js_composer'),
		         "param_name" => "mail_3",
		         "value" => __("",'js_composer'),
		         "description" => __("The e-Mail address.",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Phone",'js_composer'),
		         "param_name" => "phone_3",
		         "value" => __("",'js_composer'),
		         "description" => __("Phone Number",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Facebook",'js_composer'),
		         "param_name" => "facebook_3",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Facebook profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Twitter",'js_composer'),
		         "param_name" => "twitter_3",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Twitter profile",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Google Plus",'js_composer'),
		         "param_name" => "gplus_3",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Google Plus profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("LinkedIn",'js_composer'),
		         "param_name" => "linkedin_3",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to LinkedIn profile",'js_composer')
		      ),
		      array(
		         "type" => "textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Description",'js_composer'),
		         "param_name" => "content_3",
		         "value" => __('','js_composer'),
		         "description" => __("Enter your content.",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("<h2>Member 4</h2>Name",'js_composer'),
		         "param_name" => "name_4",
		         "value" => __("",'js_composer'),
		         "description" => __("Name of the Person.",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Position",'js_composer'),
		         "param_name" => "position_4",
		         "value" => __("",'js_composer'),
		         "description" => __("Position description or an other sub headline.",'js_composer')
		      ),
		     array(
	  			"type" => "attach_image",
	  			"heading" => __("Image", "js_composer"),
	  			"param_name" => "image_id_4",
	  			"value" => "",
	  
	  			"description" => ""
	  		),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Mail",'js_composer'),
		         "param_name" => "mail_4",
		         "value" => __("",'js_composer'),
		         "description" => __("The e-Mail address.",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Phone",'js_composer'),
		         "param_name" => "phone_4",
		         "value" => __("",'js_composer'),
		         "description" => __("Phone Number",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Facebook",'js_composer'),
		         "param_name" => "facebook_4",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Facebook profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Twitter",'js_composer'),
		         "param_name" => "twitter_4",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Twitter profile",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Google Plus",'js_composer'),
		         "param_name" => "gplus_4",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Google Plus profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("LinkedIn",'js_composer'),
		         "param_name" => "linkedin_4",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to LinkedIn profile",'js_composer')
		      ),
		      array(
		         "type" => "textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Description",'js_composer'),
		         "param_name" => "content_4",
		         "value" => __('','js_composer'),
		         "description" => __("Enter your content.",'js_composer')
		      )
		   )
		) );
		
		wpb_map( array(
		   "name" => __("Team Member",'js_composer'),
		   "base" => "team_member",
		   "class" => "",
		   "icon"      => "icon-wpb-team_member",
		   "controls" => "full",
		   "category" => __('Content','js_composer'),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   "params" => array(
		   	array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Name",'js_composer'),
		         "param_name" => "name",
		         "value" => __("",'js_composer'),
		         "description" => __("Name of the Person.",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Position",'js_composer'),
		         "param_name" => "position",
		         "value" => __("",'js_composer'),
		         "description" => __("Position description or an other sub headline.",'js_composer')
		      ),
		     array(
	  			"type" => "attach_image",
	  			"heading" => __("Image", "js_composer"),
	  			"param_name" => "image_id",
	  			"value" => "",
	  
	  			"description" => ""
	  		),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Mail",'js_composer'),
		         "param_name" => "mail",
		         "value" => __("",'js_composer'),
		         "description" => __("The e-Mail address.",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Phone",'js_composer'),
		         "param_name" => "phone",
		         "value" => __("",'js_composer'),
		         "description" => __("Phone Number",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Facebook",'js_composer'),
		         "param_name" => "facebook",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Facebook profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Twitter",'js_composer'),
		         "param_name" => "twitter",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Twitter profile",'js_composer')
		      ),
		     array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Google Plus",'js_composer'),
		         "param_name" => "gplus",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to Google Plus profile",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("LinkedIn",'js_composer'),
		         "param_name" => "linkedin",
		         "value" => __("",'js_composer'),
		         "description" => __("Link to LinkedIn profile",'js_composer')
		      ),
		      array(
		         "type" => "textarea_html",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Description",'js_composer'),
		         "param_name" => "content",
		         "value" => __('','js_composer'),
		         "description" => __("Enter your content.",'js_composer')
		      )
		   )
		) );

		wpb_map( array(
		    "name"		=> __("Price Table", "js_composer"),
		    "base"		=> "pricetable_columns",
		    "class"		=> "wpb_vc_gallery_widget",
			"icon"		=> "icon-wpb-pricetable",
		    "category"  => __('Content', 'js_composer'),
		    "params"	=> array(
			  array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("<h2>Column 1</h2>Title",'js_composer'),
		         "param_name" => "title1",
		         "value" => __("","js_composer"),
		         "description" => __("Headline of this Column",'js_composer')
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Title Subline","js_composer"),
		         "param_name" => "titlesubline1",
		         "value" => __("","js_composer"),
		         "description" => __("Subline below the Headline","js_composer")
		      ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Column Type", "js_composer"),
		            "param_name" => "highlight1",
		            "admin_label" => true,
		            "value" => array("normal"=>"normal","highlight"=>"highlight"),
		            "description" => __("Select Button type.", "js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price","js_composer"),
		         "param_name" => "price1",
		         "value" => __("","js_composer"),
		         "description" => __("Price to display here","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price Currency Symbol","js_composer"),
		         "param_name" => "price_currency1",
		         "value" => __("","js_composer"),
		         "description" => __("Currency to display","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Url","js_composer"),
		         "param_name" => "button_url1",
		         "value" => __("","js_composer"),
		         "description" => __("Button Linking Address","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Text","js_composer"),
		         "param_name" => "button_text1",
		         "value" => __("","js_composer"),
		         "description" => __("Text on the link button","js_composer")
		      ),
		       /*array(
		            "type" => "dropdown",
		            "heading" => __("Button Type", "js_composer"),
		            "param_name" => "button_type1",
		            "admin_label" => true,
		            "value" => $button_style_array,
		            "description" => __("Select Button type.", "js_composer")
		      ),*/
			  array(
		         "type" => "exploded_textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Content","js_composer"),
		         "param_name" => "content1",
		         "value" => __("","js_composer"),
		         "description" => __("Enter the content of the price table body. Each line will become a small border in frontend. Please do not use comma &lsquo;,&lsquo; inside","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("<h2>Column 2</h2>Title","js_composer"),
		         "param_name" => "title2",
		         "value" => __("","js_composer"),
		         "description" => __("Headline of this Column","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Title Subline","js_composer"),
		         "param_name" => "titlesubline2",
		         "value" => __("","js_composer"),
		         "description" => __("Subline below the Headline","js_composer")
		      ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Column Type", "js_composer"),
		            "param_name" => "highlight2",
		            "admin_label" => true,
		            "value" => array("normal"=>"normal","highlight"=>"highlight"),
		            "description" => __("Select Button type.", "js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price","js_composer"),
		         "param_name" => "price2",
		         "value" => __("","js_composer"),
		         "description" => __("Price to display here","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price Currency Symbol","js_composer"),
		         "param_name" => "price_currency2",
		         "value" => __("","js_composer"),
		         "description" => __("Currency to display","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Url","js_composer"),
		         "param_name" => "button_url2",
		         "value" => __("","js_composer"),
		         "description" => __("Button Linking Address","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Text","js_composer"),
		         "param_name" => "button_text2",
		         "value" => __("","js_composer"),
		         "description" => __("Text on the link button","js_composer")
		      ),
		     /*  array(
		            "type" => "dropdown",
		            "heading" => __("Button Type", "js_composer"),
		            "param_name" => "button_type2",
		            "admin_label" => true,
		            "value" => $button_style_array,
		            "description" => __("Select Button type.", "js_composer")
		      ),*/
			  array(
		         "type" => "exploded_textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Content","js_composer"),
		         "param_name" => "content2",
		         "value" => __("","js_composer"),
		         "description" => __("Enter the content of the price table body. Each line will become a small border in frontend. Please do not use comma &lsquo;,&lsquo; inside","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("<h2>Column 3</h2>Title","js_composer"),
		         "param_name" => "title3",
		         "value" => __("","js_composer"),
		         "description" => __("Headline of this Column","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Title Subline","js_composer"),
		         "param_name" => "titlesubline3",
		         "value" => __("","js_composer"),
		         "description" => __("Subline below the Headline","js_composer")
		      ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Column Type", "js_composer"),
		            "param_name" => "highlight3",
		            "admin_label" => true,
		            "value" => array("normal"=>"normal","highlight"=>"highlight"),
		            "description" => __("Select Button type.", "js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price","js_composer"),
		         "param_name" => "price3",
		         "value" => __("","js_composer"),
		         "description" => __("Price to display here","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price Currency Symbol","js_composer"),
		         "param_name" => "price_currency3",
		         "value" => __("","js_composer"),
		         "description" => __("Currency to display","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Url","js_composer"),
		         "param_name" => "button_url3",
		         "value" => __("","js_composer"),
		         "description" => __("Button Linking Address","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Text","js_composer"),
		         "param_name" => "button_text3",
		         "value" => __("","js_composer"),
		         "description" => __("Text on the link button","js_composer")
		      ),
		      /* array(
		            "type" => "dropdown",
		            "heading" => __("Button Type", "js_composer"),
		            "param_name" => "button_type3",
		            "admin_label" => true,
		            "value" => $button_style_array,
		            "description" => __("Select Button type.", "js_composer")
		      ),*/
			  array(
		         "type" => "exploded_textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Content","js_composer"),
		         "param_name" => "content3",
		         "value" => __("","js_composer"),
		         "description" => __("Enter the content of the price table body. Each line will become a small border in frontend. Please do not use comma &lsquo;,&lsquo; inside","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("<h2>Column 4</h2>Title","js_composer"),
		         "param_name" => "title4",
		         "value" => __("","js_composer"),
		         "description" => __("Headline of this Column","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Title Subline","js_composer"),
		         "param_name" => "titlesubline4",
		         "value" => __("","js_composer"),
		         "description" => __("Subline below the Headline","js_composer")
		      ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Column Type", "js_composer"),
		            "param_name" => "highlight4",
		            "admin_label" => true,
		            "value" => array("normal"=>"normal","highlight"=>"highlight"),
		            "description" => __("Select Button type.", "js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price","js_composer"),
		         "param_name" => "price4",
		         "value" => __("","js_composer"),
		         "description" => __("Price to display here","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price Currency Symbol","js_composer"),
		         "param_name" => "price_currency4",
		         "value" => __("","js_composer"),
		         "description" => __("Currency to display","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Url","js_composer"),
		         "param_name" => "button_url4",
		         "value" => __("","js_composer"),
		         "description" => __("Button Linking Address","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Text","js_composer"),
		         "param_name" => "button_text4",
		         "value" => __("","js_composer"),
		         "description" => __("Text on the link button","js_composer")
		      ),
		      /* array(
		            "type" => "dropdown",
		            "heading" => __("Button Type", "js_composer"),
		            "param_name" => "button_type4",
		            "admin_label" => true,
		            "value" => $button_style_array,
		            "description" => __("Select Button type.", "js_composer")
		      ),*/
			  array(
		         "type" => "exploded_textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Content","js_composer"),
		         "param_name" => "content4",
		         "value" => __("","js_composer"),
		         "description" => __("Enter the content of the price table body. Each line will become a small border in frontend. Please do not use comma &lsquo;,&lsquo; inside","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("<h2>Column 5</h2>Title","js_composer"),
		         "param_name" => "title5",
		         "value" => __("","js_composer"),
		         "description" => __("Headline of this Column","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Title Subline","js_composer"),
		         "param_name" => "titlesubline5",
		         "value" => __("","js_composer"),
		         "description" => __("Subline below the Headline","js_composer")
		      ),
		      array(
		            "type" => "dropdown",
		            "heading" => __("Column Type", "js_composer"),
		            "param_name" => "highlight5",
		            "admin_label" => true,
		            "value" => array("normal"=>"normal","highlight"=>"highlight"),
		            "description" => __("Select Button type.", "js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price","js_composer"),
		         "param_name" => "price5",
		         "value" => __("","js_composer"),
		         "description" => __("Price to display here","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Price Currency Symbol","js_composer"),
		         "param_name" => "price_currency5",
		         "value" => __("","js_composer"),
		         "description" => __("Currency to display","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Url","js_composer"),
		         "param_name" => "button_url5",
		         "value" => __("","js_composer"),
		         "description" => __("Button Linking Address","js_composer")
		      ),
		      array(
		         "type" => "textfield",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Button Text","js_composer"),
		         "param_name" => "button_text5",
		         "value" => __("","js_composer"),
		         "description" => __("Text on the link button","js_composer")
		      ),
		      /* array(
		            "type" => "dropdown",
		            "heading" => __("Button Type", "js_composer"),
		            "param_name" => "button_type5",
		            "admin_label" => true,
		            "value" => $button_style_array,
		            "description" => __("Select Button type.", "js_composer")
		      ),*/
			  array(
		         "type" => "exploded_textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Content","js_composer"),
		         "param_name" => "content5",
		         "value" => __("","js_composer"),
		         "description" => __("Enter the content of the price table body. Each line will become a small border in frontend. Please do not use comma &lsquo;,&lsquo; inside","js_composer")
		      )

		    )
		) );

		wpb_map( array(
		   "name" => __("Testimonial","js_composer"),
		   "base" => "testimonial",
		   "class" => "",
		   "icon"      => "icon-wpb-testimonial",
		   "controls" => "full",
		   "category" => __('Content','js_composer'),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   "params" => array(
		   	array(
		         "type" => "exploded_textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Names","js_composer"),
		         "param_name" => "names",
		         "value" => __("","js_composer"),
		         "description" => __("Names of the Person, Enter Names for each slide here. Divide links with linebreaks (Enter). Please use no Comma.","js_composer")
		      ),
		      array(
		         "type" => "exploded_textarea",
		         "holder" => "div",
		         "class" => "",
		         "heading" => __("Quotes","js_composer"),
		         "param_name" => "quotes",
		         "value" => __("","js_composer"),
		         "description" => __("Enter Quotes for each slide here. Divide links with linebreaks (Enter). Please use no Comma.","js_composer")
		      )
		   )
		) );

	wpb_map( array(
	    "name"		=> __("Velocity Button", "js_composer"),
	    "base"		=> "bs_button",
	    "class"		=> "wpb_vc_button wpb_controls_top_right",
		"icon"		=> "icon-wpb-ui-button",
		"category"  => __('Content', 'js_composer'),
	    "controls"	=> "edit_popup_delete",
	    "params"	=> array(
	        array(
	            "type" => "textfield",
	            "heading" => __("Text on the button", "js_composer"),
	            "holder" => "button",
	            "class" => "wpb_button",
	            "param_name" => "content",
	            "value" => __("Text on the button", "js_composer"),
	            "description" => __("Text on the button.", "js_composer")
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("URL (Link)", "js_composer"),
	            "param_name" => "url",
	            "value" => "",
	            "description" => __("Button link.", "js_composer")
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Style", "js_composer"),
	            "param_name" => "class",
	            "value" => $button_style_array,
	            "description" => __("Button color.", "js_composer")
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Size", "js_composer"),
	            "param_name" => "size",
	            "value" => array(""=>"normal","large"=>"large"),
	            "description" => __("Button size.", "js_composer")
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Target", "js_composer"),
	            "param_name" => "target",
	            "value" => $target_arr
	        ),
	        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "extra_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
	    ),
	    //"js_callback" => array("init" => "wpbButtonInitCallBack", "save" => "wpbButtonSaveCallBack")
	    //"js_callback" => array("init" => "wpbCallToActionInitCallBack", "shortcode" => "wpbCallToActionShortcodeCallBack")
	) );
		
	wpb_map( array(
		"name"		=> __("Lightbox Image", "js_composer"),
		"base"		=> "lightbox",
		"class"		=> "wpb_vc_single_image_widget",
		"icon"		=> "icon-wpb-single-image",
	    "category"  => __('Content', 'js_composer'),
	    "params"	=> array(
	      array(
	        "type" => "textfield",
	        "heading" => __("Caption", "js_composer"),
	        "param_name" => "title",
	        "value" => "",
	        "description" => __("What text use as image title. Leave blank if no title is needed.", "js_composer")
	      ),
	  		array(
	  			"type" => "attach_image",
	  			"heading" => __("Image", "js_composer"),
	  			"param_name" => "thumb",
	  			"value" => "",
	  
	  			"description" => ""
	  		),
	      array(
	        "type" => "textfield",
	        "heading" => __("Image Width", "js_composer"),
	        "param_name" => "thumbwidth",
	        "value" => "",
	        "description" => __("Enter image width.", "js_composer")
	      ),
	      array(
	        "type" => "textfield",
	        "heading" => __("Image Height", "js_composer"),
	        "param_name" => "thumbheight",
	        "value" => "",
	        "description" => __("Enter image height.", "js_composer")
	      ),
	      array(
	        "type" => "textfield",
	        "heading" => __("Image link", "js_composer"),
	        "param_name" => "lightbox_link",
	        "value" => "",
	        "description" => __("Leave empty if you will show a lightbox version of the image. You can also include a link to a video or link an other image.", "js_composer")
	      )
	    )
	));

	wpb_map( array(
	    "name"		=> __("velocity Message Box", "js_composer"),
	    "base"		=> "bs_alert",
	    "class"		=> "",
		"icon"		=> "icon-wpb-information-white",
	    "controls"	=> "edit_popup_delete",
	    "category"  => __('Content', 'js_composer'),
	    "params"	=> array(
	        array(
	            "type" => "textfield",
	            "heading" => __("Text inside the Box", "js_composer"),
	            "holder" => "button",
	            "class" => "wpb_button",
	            "param_name" => "content",
	            "value" => __("Text inside the Box", "js_composer"),
	            "description" => __("Text inside the Box.", "js_composer")
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Style", "js_composer"),
	            "param_name" => "type",
	            "value" => $box_style_array,
	            "description" => __("Box Message Style.", "js_composer")
	        )
	    ),
	) );
	
	/*! Background */
			wpb_map( array(
		   "name" => __("Fullwidth Background", "js_composer"),
		   "base" => "background_block",
		   "class" => "",
		   "icon"      => "icon-wpb-row",
		   "controls" => "full",
		   "category" => __('Content', "js_composer"),
		   'admin_enqueue_js' => '',
		   'admin_enqueue_css' => '',
		   "params" => array(
		   	 array(
		   	 		"type" => "textfield",
		   	 		"heading" => __("Video in Background (MP4)", "js_composer"),
		            "param_name" => "mp4",
		            "value" => "",
		            "description" => __("MP4 Video Source. Optional. If no Video Set, Simple image will be used", "js_composer")
		           ),
			 array(
		   	 		"type" => "textfield",
		   	 		"heading" => __("Video in Background (OGV) - Optional", "js_composer"),
		            "param_name" => "ogv",
		            "value" => "",
		            "description" => __("OGV Video Source. Optional. If no Video Set, Simple image will be used", "js_composer")
		           ),
			 array(
		   	 		"type" => "textfield",
		   	 		"heading" => __("Video in Background (WebM) - Optional", "js_composer"),
		            "param_name" => "webm",
		            "value" => "",
		            "description" => __("WebM Video Source. Optional. If no Video Set, Simple image will be used", "js_composer")
		           ),
			  array(
		            "type" => "attach_images",
		            "heading" => __("Poster Image", "js_composer"),
		            "param_name" => "poster",
		            "value" => "",
		            "admin_label" => true,
		            "description" => __("Choose a Poster for Video for the Following Container","js_composer")
		        ),
		     array(
		            "type" => "attach_images",
		            "heading" => __("Background Image", "js_composer"),
		            "param_name" => "bgimage",
		            "value" => "",
		            "admin_label" => true,
		            "description" => __("Choose a Background Image for the Following Container","js_composer")
		        ),
		     array(
			         "type" => "colorpicker",
			         "holder" => "div",
			         "class" => "",
			         "heading" => __("Background Color Overlay","js_composer"),
			         "param_name" => "bgcolor",
			         "value" => '#44576a', //Default Red color
			         "admin_label" => true,
			         "description" => __("Choose the Backgrouind Color Overlay","js_composer")
			      ),
		     
		      array(
		            "type" => "textfield",
		            "heading" => __("Opacity", "js_composer"),
		            "param_name" => "opacity",
		            "value" => "1.0",
		            "description" => __("Opacity of Background Color Overlay", "js_composer")
		        ),
		      array(
	            "type" => "dropdown",
	            "heading" => __("Content Coloring", "js_composer"),
	            "param_name" => "contentcolorclass",
	            "value" => $contentcolor_array
	            ),
		     array(
	            "type" => "dropdown",
	            "heading" => __("Parallax Mode", "js_composer"),
	            "param_name" => "parallax",
	            "admin_label" => true,
	            "value" => $enadisa_array
	            ),
	         array(
		            "type" => "textfield",
		            "heading" => __("Parallax Speed", "js_composer"),
		            "param_name" => "parspeed",
		            "value" => "8",
		            "description" => __("Set Parallax Speed from 2 - 20 (smaller is quicker)", "js_composer")
		        ),
		     array(
		            "type" => "textfield",
		            "heading" => __("Padding Top", "js_composer"),
		            "param_name" => "ptop",
		            "value" => "100",
		            "description" => __("Set Top Padding in container", "js_composer")
		        ),
		     array(
		            "type" => "textfield",
		            "heading" => __("Border Top Width", "js_composer"),
		            "param_name" => "btwidth",
		            "value" => "0",
		            "description" => __("Set Top Border Size of the Container", "js_composer")
		        ),
		      array(
			         "type" => "colorpicker",
			         "holder" => "div",
			         "class" => "",
			         "heading" => __("Border Top Color","js_composer"),
			         "param_name" => "btcolor",
			         "value" => '#44576a', //Default Red color
			         "description" => __("Choose the Top Border Color of the Container","js_composer")
			      ),
		     array(
		            "type" => "textfield",
		            "heading" => __("Border Bottom Width", "js_composer"),
		            "param_name" => "bbwidth",
		            "value" => "0",
		            "description" => __("Set Bottom Border Size of the Container", "js_composer")
		        ),
		      array(
			         "type" => "colorpicker",
			         "holder" => "div",
			         "class" => "",
			         "heading" => __("Border Bottom Color","js_composer"),
			         "param_name" => "bbcolor",
			         "value" => '#44576a', //Default Red color
			         "description" => __("Choose the Bottom Border Color of the Container","js_composer")
			      ),
 				array(
		            "type" => "textfield",
		            "heading" => __("Extra class name", "js_composer"),
		            "param_name" => "el_class",
		            "value" => "",
		            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
		        )

	        
		   )
		) );
						
		vc_map( array(
		  "name" => __("Tab", "js_composer"),
		  "base" => "vc_tab",
		  "allowed_container_element" => 'vc_row',
		  "is_container" => true,
		  "content_element" => false,
		  "params" => array(
		  	array(
		      "type" => "textfield",
		      "heading" => __("Prefix", "js_composer"),
		      "param_name" => "prefix",
		      "description" => __("Some Prefix.", "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => __("Title", "js_composer"),
		      "param_name" => "title",
		      "description" => __("Tab title.", "js_composer")
		    ),
		    array(
		      "type" => "tab_id",
		      "heading" => __("Tab ID", "js_composer"),
		      "param_name" => "tab_id"
		    )
		  ),
		  'js_view' => ($vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35')
		) );
	

		
		
		
?>