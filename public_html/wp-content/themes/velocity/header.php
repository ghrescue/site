<?php
	global $wp_query;
    $velocity_content_array = $wp_query->get_queried_object();
	if(isset($velocity_content_array->ID)){
    	$velocity_post_id = $velocity_content_array->ID;
	}
	else $velocity_post_id=0;

	//WOOcommerce
	if(function_exists('is_shop') && (is_shop() || is_product() )) $velocity_post_id = get_option('woocommerce_shop_page_id');

	if(is_404()) $velocity_post_id = get_current_blog_id();

	// Themeoptions
	$velocity_themeoptions = velocity_getThemeOptions();
	$velocity_template_uri = get_template_directory_uri();

	//echo $_COOKIE['velocitypreview'];
	if(isset($_GET["style"])){
		setcookie("velocitypreview", $_GET["style"],time()+3600,"/","themepunch.com");
		$_COOKIE['velocitypreview'] = $_GET["style"];
	}
	


?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes();?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes();?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes();?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes();?>> <!--<![endif]-->

<!--
#######################################
	- THE HEAD PART -
######################################
-->
<head>

    <!-- Basic Page Needs
    ================================================== -->
    <meta http-equiv="Content-Type" content="<?php echo get_bloginfo('html_type'); ?>; charset=<?php echo get_bloginfo('charset'); ?>" />
    <title><?php echo wp_title("",true); ?> <?php if(!is_front_page()) { ?> &raquo; <?php } ?> <?php echo get_bloginfo('name'); ?></title>
    <meta name="robots" content="index, follow" />
    

    <!-- Options
	================================================== -->

    <!-- Mobile Specific Metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
   
	<?php
		/* Theme Layout */
		$velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
		if($velocity_themelayout=="Full-Width"){ $velocity_wideclass = "wide"; } else { $velocity_wideclass = ""; }
		if($velocity_themelayout=="Full-Width") $velocity_stretchme_on_mobile = "stretchme_on_mobile";
		else $velocity_stretchme_on_mobile = "";


	?>
	<?php wp_head(); ?>
</head>

<!--
#######################################
	- THE BODY PART -
######################################
-->


<?php
	if (!empty($velocity_post_id)){
		$velocity_pagecustoms = velocity_getOptions($velocity_post_id);
		//Custom Background
		if(isset($velocity_pagecustoms["velocity_custom_background"])){
			$velocity_image = wp_get_attachment_image_src($velocity_pagecustoms["velocity_custom_background"], 'full');	$velocity_image = $velocity_image[0];
			$velocity_themeoptions['velocity_img_bgdefault'] = $velocity_image;
			$velocity_themeoptions['velocity_img_bgtype'] = $velocity_pagecustoms["velocity_custom_background_type"];
		}
	}
	$velocity_themeoptions['velocity_stickymenu'] = isset($velocity_themeoptions['velocity_stickymenu']) ? "stickymenu" : "";
	$velocity_themeoptions['velocity_slider_effects'] = isset($velocity_themeoptions['velocity_slider_effects']) ? $velocity_themeoptions['velocity_slider_effects'] : "";
	$velocity_themeoptions['velocity_stickyfooter'] = !isset($velocity_themeoptions['velocity_stickyfooter']) ? "stickyfooter" : "";

	$velocity_style="";
	if ($velocity_themeoptions['velocity_themelayout']=="Boxed"){
		if ($velocity_themeoptions['velocity_img_bgtype']!="full") {
			$velocity_style='style="background-image: url('.$velocity_themeoptions["velocity_img_bgdefault"].');  }"';
}}

	//Pagetitle
	$ptclass = "";
	if(function_exists('is_product') && is_product() ){
		if(isset($velocity_pagecustoms['velocity_activate_page_title_woo'])){ $velocity_headline = "off";} else {$velocity_headline = "on";}
		if(isset($velocity_pagecustoms['velocity_header_title_woo'])) $velocity_htitle = $velocity_pagecustoms['velocity_header_title_woo']; else $velocity_htitle=get_the_title();
		$velocity_title_orientation = $velocity_pagecustoms["velocity_title_orientation_woo"];
	}
	else{
		if(isset($velocity_pagecustoms['velocity_activate_page_title'])){ $velocity_headline = "off"; $ptclass = "nopagetitle"; } else {$velocity_headline = "on"; $ptclass = ""; }
		if(isset($velocity_pagecustoms['velocity_header_title']))$velocity_htitle = $velocity_pagecustoms['velocity_header_title']; else $velocity_htitle=get_the_title($velocity_post_id);
		$velocity_title_orientation = isset($velocity_themeoptions['velocity_title_orientation']) ? $velocity_themeoptions['velocity_title_orientation'] : "left";
	}
	
	
	

	//PAGE TITLE BACKGROUND OPTIONS

	$velocity_pagetitle_img = isset($velocity_themeoptions['velocity_pagetitleimg']) ?  $velocity_themeoptions['velocity_pagetitleimg'] : "";

	$velocity_pagetitle_color = isset($velocity_themeoptions['velocity_pagetitleline_color']) ? $velocity_themeoptions['velocity_pagetitleline_color'] : "#2c3e50";
	$velocity_pagetitle_opacity = isset($velocity_themeoptions['velocity_pagetitleline_color_opacity']) ? $velocity_themeoptions['velocity_pagetitleline_color_opacity'] : "0.85";
	$velocity_pagetitle_pspeed = isset($velocity_themeoptions['velocity_pagetitleline_parallaxspeed']) ? $velocity_themeoptions['velocity_pagetitleline_parallaxspeed'] : "8";
	$velocity_pagetitle_rgb = velocity_HexToRGB($velocity_pagetitle_color);
	$velocity_pagetitle_rgba = $velocity_pagetitle_rgb["r"].",".$velocity_pagetitle_rgb["g"].",".$velocity_pagetitle_rgb["b"].",";

	$velocity_pagetitle_style_pagetitle = isset($velocity_themeoptions['velocity_pagetitleline_color_style']) ? $velocity_themeoptions['velocity_pagetitleline_color_style'] : "lightpagetitle" ;

	if(is_numeric($velocity_pagetitle_img)){
					$velocity_pagetitle_img = wp_get_attachment_image_src($velocity_pagetitle_img, 'full'); $velocity_pagetitle_img = $velocity_pagetitle_img[0];
				}
	 $velocity_pagetitle_class="parallaxbg";
	 $velocity_pagetitle_style="background-attachment:fixed;";

	 //Header Style
	 $velocity_header_style = isset($velocity_themeoptions["velocity_header_style"]) ? $velocity_themeoptions["velocity_header_style"] : "light";

	 $stickyfooterclass = "";
	 if($velocity_themelayout=="Boxed"){
		 $stickymenuclass = "";
		 $stickyfooterclass = "";
	 }else{
		 $stickymenuclass = $velocity_themeoptions['velocity_stickymenu'];
		 $stickyfooterclass = $velocity_themeoptions['velocity_stickyfooter'];
	 }

	 $logosrc = $velocity_themeoptions["velocity_img_logo"];

	 //Cutomizer Start

	 $getpreview = isset($_COOKIE['velocitypreview']) && $_COOKIE['velocitypreview']!='' ? $_COOKIE['velocitypreview'] : "velocity1.css";



	 switch ($getpreview) {
		 case "velocity_blue_widedark.css":
		   	$velocity_themelayout = "Full-Width";
		   	$velocity_header_style = "darkheader";
		   	$logosrc = "http://themepunch.com/velocity/wp-content/uploads/2014/01/logo_blue_white.png";
		 break;

		  case "velocity_blue_boxeddark.css":
		     $velocity_themelayout = "Boxed";
		   	 $stickymenuclass = "";
		   	 $stickyfooterclass = "";
		   	 $velocity_stretchme_on_mobile="";
		   	 $velocity_header_style = "darkheader";
		   	 $logosrc = "http://themepunch.com/velocity/wp-content/uploads/2014/01/logo_blue_white.png";
		   	 $velocity_style='style="background-image: url(http://themepunch.com/velocity/wp-content/uploads/2014/01/velocity_tile5.jpg);  }"';
		 break;

		  case "velocity_brown.css":
		   	 $velocity_themelayout = "Boxed";
		   	 $stickymenuclass = "";
		   	 $stickyfooterclass = "";
		   	 $velocity_stretchme_on_mobile="";
		   	 $logosrc = "http://themepunch.com/velocity/wp-content/uploads/2014/01/logo_brown.png";
		   	 $velocity_header_style = "darkheader";
		   	 $velocity_style='style="background-image: url(http://themepunch.com/velocity/wp-content/uploads/2014/01/velocity_tile21.jpg);  }"';
		   	 $velocity_pagetitle_color = "#514335";
		   	 $velocity_pagetitle_opacity = "0.85";
		   	 $velocity_pagetitle_rgb = velocity_HexToRGB($velocity_pagetitle_color);
		   	 $velocity_pagetitle_rgba = $velocity_pagetitle_rgb["r"].",".$velocity_pagetitle_rgb["g"].",".$velocity_pagetitle_rgb["b"].",";

		  break;

		  case "velocity_green.css":
		   	 $velocity_themelayout = "Boxed";
		   	 $stickymenuclass = "";
		   	 $stickyfooterclass = "";
		   	 $velocity_stretchme_on_mobile="";
		   	 $logosrc = "http://themepunch.com/velocity/wp-content/uploads/2014/01/logo_green.png";
		   	 $velocity_header_style = "lightheader";
		   	 $velocity_style='style="  }"';
		   	 $velocity_pagetitle_color = "#7d9947";
		   	 $velocity_pagetitle_opacity = "0.85";
		   	 $velocity_pagetitle_rgb = velocity_HexToRGB($velocity_pagetitle_color);
		   	 $velocity_pagetitle_rgba = $velocity_pagetitle_rgb["r"].",".$velocity_pagetitle_rgb["g"].",".$velocity_pagetitle_rgb["b"].",";

		 break;

		  case "velocity_orange.css":
		   	$velocity_themelayout = "Full-Width";
		   	$velocity_header_style = "lightheader";
		   	$logosrc = "http://themepunch.com/velocity/wp-content/uploads/2014/01/logo_orange.png";
		   	$velocity_style='style="  }"';
		   	$velocity_pagetitle_color = "#f5f5f5";
		   	$velocity_pagetitle_opacity = "0.92";
		   	$velocity_pagetitle_rgb = velocity_HexToRGB($velocity_pagetitle_color);
		   	$velocity_pagetitle_rgba = $velocity_pagetitle_rgb["r"].",".$velocity_pagetitle_rgb["g"].",".$velocity_pagetitle_rgb["b"].",";
		   	$velocity_pagetitle_style_pagetitle = "lightpagetitle";

		 break;

		  case "velocity_smoked.css":
		   	$velocity_themelayout = "Full-Width";
		   	$velocity_header_style = "darkheader";
		   	$logosrc = "http://themepunch.com/velocity/wp-content/uploads/2014/01/logo_smoked.png";
		   	$velocity_style='style="  }"';
		   	$velocity_pagetitle_color = "#93adb9";
		   	$velocity_pagetitle_opacity = "0.85";
		   	$velocity_pagetitle_rgb = velocity_HexToRGB($velocity_pagetitle_color);
		   	$velocity_pagetitle_rgba = $velocity_pagetitle_rgb["r"].",".$velocity_pagetitle_rgb["g"].",".$velocity_pagetitle_rgb["b"].",";
		 break;



		 default:
		 break;
	 }

	 //Cutomizer END
	 
	 if($velocity_themelayout=="Full-Width"){ 
	 	$fullboxedclass = "fullwidthlayout";
	 } else { 
	 	$fullboxedclass = "boxedlayout"; 
	 } 
	 
	 $velocity_themeoptions["velocity_parallax_effects"] = isset($velocity_themeoptions["velocity_parallax_effects"]) ? $velocity_themeoptions["velocity_parallax_effects"] : '';

?>

<body <?php body_class($stickymenuclass." ".$fullboxedclass." ".$velocity_themeoptions["velocity_parallax_effects"]." ".$velocity_header_style." ".$velocity_pagetitle_style_pagetitle." colored ".$stickyfooterclass." ".$ptclass." ".$velocity_themeoptions['velocity_slider_effects']); echo " ".$velocity_style; ?> data-forumsearch="<?php _e('Search Forum...', 'velocity'); ?>">
	<?php
	
	 echo '<div style="display:none" id="latestcookie" data-cookie="'.$getpreview.'"></div>';

	 //Cutomizer Start
	 if ($getpreview == "velocity1.css")
	 		echo generateCSS();
	 else
		 echo "<link rel='stylesheet' id='velocity_wp_preview_style-css'  href='http://themepunch.com/velocity/wp-content/themes/velocity/".$getpreview."?ver=3.8' type='text/css' media='all' />";
	 //Cutomizer End

	  ?>

<section class="allwrapper">
	<header class="<?php echo $velocity_stretchme_on_mobile; ?>">

			<?php if(isset($velocity_themeoptions["velocity_headertopline"])){ ?>
			<!-- Header Text Line -->
			<section class="headertopwrap">
				<div class="headertop">
					<div class="row">
						<div class="span6 headerlefttext">
							<div class="headerleftwrap"><div class="headerleftinner"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Header Top Line Left") ) {} ?></div><div class="clear"></div></div>
						</div>
						<div class="span6 headerrighttext">
							<div class="headerleftwrap"><div class="headerleftinner"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Header Top Line Right") ) {} ?></div><div class="clear"></div></div>

						</div>
					</div>
				</div>
			</section>
			<?php } ?>

			<!-- Header Logo and Menu -->
			<section class="headerwrap">
				<div class="header span12">
						<?php
							$velocity_themeoptions["velocity_img_logo_id"] = velocity_get_attachment_id_from_src($velocity_themeoptions["velocity_img_logo"]);
							$velocity_logo_alt = get_post_meta($velocity_themeoptions["velocity_img_logo_id"], '_wp_attachment_image_alt', true);
							$velocity_image = wp_get_attachment_image_src($velocity_themeoptions["velocity_img_logo_id"], 'full');
							$velocity_logo_width = $velocity_image[1];
							
							$velocity_image_ww = isset($velocity_themeoptions["velocity_img_logo_width"]) ? $velocity_themeoptions["velocity_img_logo_width"] : 135;
							$velocity_image_hh = isset($velocity_themeoptions["velocity_img_logo_height"]) ? $velocity_themeoptions["velocity_img_logo_height"] : 40;
							$velocity_logo_width = isset($velocity_themeoptions["velocity_img_logo_width"]) ? $velocity_themeoptions["velocity_img_logo_width"] : $velocity_logo_width;
						?>

						<div class="logo" data-ww="<?php echo $velocity_image_ww; ?>" data-hh="<?php echo $velocity_image_hh; ?>" style="width:<?php echo $velocity_logo_width; ?>px"><a href="<?php echo home_url(); ?>"><img src="<?php echo $logosrc ?>" alt="<?php echo $velocity_logo_alt; ?>" /></a></div>

						<nav class="mainmenu">
							
							<?php wp_nav_menu( array( 'menu' => '', 'container_class' => 'navigation', 'container_id' => 'mainmenu', 'menu_class' => 'toplevel', 'theme_location' => 'navigation' ) ); ?>

							<?php if(isset($velocity_themeoptions["velocity_headersearch"])){ ?>
							
							<div class="headersearch">
								<?php get_search_form(); ?>
							</div>
							<?php } else {?>
							<style>.navigation {margin-right:0;}</style>
							<?php }?>
						</nav>
						
				
						<!-- Responsive Main Menu -->
						<div class="row mobilemenu">
							<div class="icon-menu"></div>
							<form id="responsive-menu" action="#" method="post">
								<select>
									<option value=""><?php _e('Menu', 'velocity'); ?></option>
								</select>
							</form>
						</div>
				</div>
			</section>
			<div class="clear"></div>



	</header>

	<?php if($velocity_post_id > 0)	$velocity_pagecustoms = velocity_getOptions($velocity_post_id);

	//Page Slider
	if(isset($velocity_pagecustoms["velocity_activate_slider"]) && $velocity_pagecustoms["velocity_activate_slider"]=="on") {
		$velocity_slider = isset($velocity_pagecustoms["velocity_header_slider"]) ? $velocity_pagecustoms["velocity_header_slider"] : '';
		$velocity_slider_height = velocity_get_revslider_property($velocity_slider,"height");
	}else{
		$velocity_slider ="";
		$velocity_slider_height = "";
	}

	if($velocity_title_orientation == "left"){
		$velocity_torient = "";
	} else if($velocity_title_orientation == "center"){
		$velocity_torient = "text-align: center;";
	}

	if ( is_search() ) {
		$velocity_searchresults = __('search results for', 'velocity');
		$velocity_allsearch = new WP_Query("s=$s&showposts=-1");
		$velocity_searchcount = $velocity_allsearch->post_count;
		wp_reset_query();
		$velocity_htitle = "<strong>".$velocity_searchcount."</strong> ".$velocity_searchresults." \"".get_search_query()."\"";
	}

	$velocity_categoryname = __('Category ', 'velocity');
	$velocity_archivename = __('Archives ', 'velocity');
	$velocity_tags = __('Tag ', 'velocity');
	$velocity_all = __('All', 'velocity');
	$velocity_readmore =  __('Read More', 'velocity');
	$velocity_in = __('in', 'velocity');
	$velocity_by = __('by', 'velocity');
	$velocity_tagged = __('tagged: ', 'velocity');


	if(is_category() || is_archive()){
		if(is_category()){
			$velocity_catname = single_cat_title("", false);
			$velocity_htitle = __('Category ', 'velocity')." - ".$velocity_catname;
		}

		elseif(is_archive()){
			if(is_tax()){
				$velocity_tax_slug = get_post_type();
				$velocity_portfolio_counter = 0;
				if(isset($wp_query->query_vars['taxonomy']) && taxonomy_exists($wp_query->query_vars['taxonomy'])) {
					$velocity_value    = get_query_var($wp_query->query_vars['taxonomy']);
					if (term_exists($wp_query->query_vars['term'])) {
						$velocity_term = get_term_by( 'slug', get_query_var( 'term'  ),$wp_query->query_vars['taxonomy'] );
						$velocity_htitle_cat = $velocity_term->name;
					}
				}

				$velocity_portfolios = get_option("damojoPortfolio_theme_portfolios_options");
				$velocity_portfolio_slugs = array();
				$velocity_portfolio_names = array();
				$j = 1;
				if(is_array($velocity_portfolios)){
					foreach($velocity_portfolios as $key => $velocity_value){
						if($j%2==0){
				            array_push($velocity_portfolio_slugs,$velocity_value);
				            $j = 0 ;
				        }
				        else{
				            array_push($velocity_portfolio_names,$velocity_value);
				        }
				    	$j++;
					}
				}
				foreach ( $velocity_portfolio_slugs as $velocity_slug ){
						if($velocity_slug == $velocity_tax_slug) break;
						else $velocity_portfolio_counter++;
				}
				$velocity_htitle = $velocity_portfolio_names[$velocity_portfolio_counter]." - ".$velocity_htitle_cat;
			}
			else{
				$velocity_htitle = $velocity_archivename." - ".single_month_title(' ', false);
			}
		}
	}
	if(is_tag()) $velocity_htitle = $velocity_tags." - ".single_tag_title(' ', false); //tag title
	
	if(function_exists("is_shop") && is_shop()){ 
		if(isset($velocity_pagecustoms['velocity_header_title'])) $velocity_htitle = $velocity_pagecustoms['velocity_header_title']; 
		else $velocity_htitle = get_the_title(get_option('woocommerce_shop_page_id'));
	}
	
	if(function_exists('is_product') && is_product() ){
			if(isset($velocity_pagecustoms['velocity_header_title_woo'])) $velocity_htitle = $velocity_pagecustoms['velocity_header_title_woo']; else $velocity_htitle=get_the_title();
	}
	
	?>

	<!--?php if($velocity_themelayout=="Full-Width"){ ?-->
		<?php if ($velocity_headline!="off"){ ?>
			<!-- Page Title -->
			<section class="pagetitlewrap">
			  <div class="<?php echo $velocity_pagetitle_class ?> bgwithparallax" data-speed="<?php echo $velocity_pagetitle_pspeed?>"  style="background:url(<?php echo $velocity_pagetitle_img ?>) 50% 0% repeat;<?php echo $velocity_pagetitle_style ?> ;background-size:100%"></div>
 				<div class="bgwithparallax_overlay" style="background-color:rgba(<?php echo $velocity_pagetitle_rgba.$velocity_pagetitle_opacity ?>);"></div>

				<div class="row pagetitle">
					<h1 style="<?php echo $velocity_torient;?>"><?php echo $velocity_htitle;?></h1>
					<div class="breadcrumbwrap"><?php velocity_breadcrumb(); ?></div>
				</div>
			</section>
		<?php } ?>
	<!--?php } ?-->