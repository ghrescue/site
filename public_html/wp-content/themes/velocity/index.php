<?php
/*
Template Name: Blog Overview
*/
?>
<?php
	//Language Options
	$velocity_categoryname = __('<strong>Category</strong>', 'velocity');
	$velocity_archivename = __('<strong>Archives</strong>', 'velocity');
	$velocity_tags = __('<strong>Tag</strong>', 'velocity');
	$velocity_all = __('All', 'velocity');
	$velocity_readmore =  __('Read More', 'velocity');
	$velocity_in = __('in', 'velocity');
	$velocity_by = __('by', 'velocity');
	$velocity_tagged = __('tagged: ', 'velocity');

	global $wp_query;
    $velocity_content_array = $wp_query->get_queried_object();
	if(isset($velocity_content_array->ID)){
    	$velocity_post_id = $velocity_content_array->ID;
	}
	else $velocity_post_id=0;
	$velocity_template_uri = get_template_directory_uri();

	$velocity_pagecustoms = velocity_getOptions($velocity_post_id);

	// Themeoptions
	$velocity_themeoptions = velocity_getThemeOptions();
	
	//Posts per Page Default Setting WP
	$velocity_posts_per_page = get_option('posts_per_page');
	
	//PAGE TITLE BACKGROUND OPTIONS
	$velocity_pagetitle_img = isset($velocity_themeoptions['pagetitleimg']) ?  $velocity_themeoptions['pagetitleimg'] : "";
	$velocity_pagetitle_color = isset($velocity_themeoptions['pagetitleline_color']) ? $velocity_themeoptions['pagetitleline_color'] : "#2c3e50";
	$velocity_pagetitle_opacity = isset($velocity_themeoptions['pagetitleline_color_opacity']) ? $velocity_themeoptions['pagetitleline_color_opacity'] : "0.85";
	$velocity_pagetitle_pspeed = isset($velocity_themeoptions['pagetitleline_parallaxspeed']) ? $velocity_themeoptions['pagetitleline_parallaxspeed'] : "8";			
	$velocity_pagetitle_rgb = velocity_HexToRGB($velocity_pagetitle_color);
	$velocity_pagetitle_rgba = $velocity_pagetitle_rgb["r"].",".$velocity_pagetitle_rgb["g"].",".$velocity_pagetitle_rgb["b"].",";
	if(is_numeric($velocity_pagetitle_img)){
					$velocity_pagetitle_img = wp_get_attachment_image_src($velocity_pagetitle_img, 'full'); $velocity_pagetitle_img = $velocity_pagetitle_img[0];
				}
	   

	//Blog Style Options
	$velocity_bloglayoutclass = "";
	$velocity_nopostinfo = !isset($velocity_themeoptions['velocity_blogoverviewpostinfo_date']) && !isset($velocity_themeoptions['velocity_blogoverviewpostinfo_author']) && !isset($velocity_themeoptions['velocity_blogoverviewpostinfo_category']) && !isset($velocity_themeoptions['velocity_blogoverviewpostinfo_comments']) && !isset($velocity_themeoptions['velocity_blogoverviewpostinfo_tags']) ? "style='display:none;'" : "";
	if ($velocity_nopostinfo == "style='display:none;'"){ $velocity_titlemod = "margin-bottom: 14px;"; } else { $velocity_titlemod = ""; }
	$velocity_bloglayout = $velocity_themeoptions['velocity_blogoverviewpostlayout'];
	$velocity_blogdateclass = isset($velocity_themeoptions['velocity_blogoverviewsingledate']) ? "" : "nodate";
	if ($velocity_nopostinfo == "style='display:none;'" && $velocity_blogdateclass == ""){ $velocity_titlemod = "margin-bottom: 36px;"; } 

	//Sidebar & Blog Style
	if(isset($velocity_pagecustoms["velocity_activate_sidebar"])){
		if (isset($velocity_pagecustoms["velocity_sidebar"])){$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar"];}else{$velocity_sidebar = "Blog Sidebar";}
		$velocity_sidebar_orientation = $velocity_pagecustoms["velocity_sidebar_orientation"];
		$velocity_activate_sidebar="on";
		$velocity_blogfullclass = "";
		$velocity_bloglayoutclass = "";

		$velocity_post_top_width = 850;
		$velocity_post_top_height = "";
		if($velocity_bloglayout == "Small Media"){
			$velocity_bloglayoutclass = "smallmedia";
			$velocity_post_top_width = 270; 
			$velocity_post_top_height = "";
		}
	}
	else {
		$velocity_activate_sidebar="off";
		$velocity_blogfullclass = "fullblog";

		$velocity_post_top_width = 1170;
		$velocity_post_top_height = "";
		if($velocity_bloglayout == "Small Media"){
			$velocity_bloglayoutclass = "smallmedia";
			$velocity_post_top_width = 370;
			$velocity_post_top_height = "";
		}
	}


	//Pagetitle
	if(isset($velocity_pagecustoms['velocity_activate_page_title'])){ $velocity_headline = "off";} else {$velocity_headline = "on";}
	if(isset($velocity_pagecustoms['velocity_header_title']))$velocity_htitle = $velocity_pagecustoms['velocity_header_title']; else $velocity_htitle=get_the_title($velocity_post_id);
	if(isset($velocity_pagecustoms['velocity_title_orientation']))$velocity_title_orientation = $velocity_pagecustoms["velocity_title_orientation"]; else $velocity_title_orientation = "left";
	if($velocity_title_orientation == "left"){
		$velocity_torient = "";
	} else if($velocity_title_orientation == "center"){
		$velocity_torient = "text-align: center;";
	}

	if(have_posts()) $velocity_current_cat = get_the_category();

	if(is_category() || is_archive()){
		if(is_category()){
			$velocity_catname = single_cat_title("", false);
			$velocity_htitle = $velocity_categoryname." ".$velocity_catname;

			if($velocity_archivelayout=="Full-Width"){
				$velocity_activate_sidebar="off";
				$velocity_blogfullclass = "fullblog";
				$velocity_withsidebarmod = "";
			}else if($velocity_archivelayout=="Sidebar Left"){
				$velocity_activate_sidebar="on";
				$velocity_sidebar_orientation ="left";
				$velocity_withsidebarmod = "withsidebar";
			}else if($velocity_archivelayout=="Sidebar Right"){
				$velocity_activate_sidebar="on";
				$velocity_sidebar_orientation ="right";
				$velocity_withsidebarmod = "withsidebar";
			}
			$velocity_bloglayoutclass = "";
			$velocity_sidebar = "Blog Sidebar";
		}

		elseif(is_archive()){
			wp_link_pages();
			if(is_tax()){
				if(isset($wp_query->query_vars['taxonomy']) && taxonomy_exists($wp_query->query_vars['taxonomy'])) {
					$velocity_value    = get_query_var($wp_query->query_vars['taxonomy']);
					if (term_exists($wp_query->query_vars['term'])) {
						$velocity_term = get_term_by( 'slug', get_query_var( 'term'  ),$wp_query->query_vars['taxonomy'] );
						$velocity_htitle_cat = $velocity_term->name;
					}
				}

				$velocity_tax_slug = get_post_type();
				$velocity_display_span = "fourcol";
				$velocity_portfolio_slugs = get_option("velocity_portfolio_slug");
				$velocity_portfolio_counter = 0;
				$velocity_portfolio_name = get_option("velocity_portfolio_name");
					foreach ( $velocity_portfolio_slugs as $velocity_slug ){
						if($velocity_slug == $velocity_tax_slug) break;
						else $velocity_portfolio_counter++;
					}
				$velocity_htitle = "<strong>".$velocity_portfolio_name[$velocity_portfolio_counter]."</strong> ".$velocity_htitle_cat;

				if($velocity_portfoliocategorysidebar=="Full-Width"){
					$velocity_activate_sidebar="off";
					$velocity_blogfullclass = "fullblog";
					$velocity_withsidebarmod = "";
				}else if($velocity_portfoliocategorysidebar=="Sidebar Left"){
					$velocity_activate_sidebar="on";
					$velocity_sidebar_orientation ="left";
					$velocity_withsidebarmod = "withsidebar";
				}else if($velocity_portfoliocategorysidebar=="Sidebar Right"){
					$velocity_activate_sidebar="on";
					$velocity_sidebar_orientation ="right";
					$velocity_withsidebarmod = "withsidebar";
				}
				$velocity_bloglayoutclass = "";
				$velocity_sidebar = "Blog Sidebar";
			}
			else{
				$velocity_htitle = $velocity_archivename." ".single_month_title(' ', false);

				if($velocity_archivelayout=="Full-Width"){
					$velocity_activate_sidebar="off";
					$velocity_blogfullclass = "fullblog";
					$velocity_withsidebarmod = "";
				}else if($velocity_archivelayout=="Sidebar Left"){
					$velocity_activate_sidebar="on";
					$velocity_sidebar_orientation ="left";
					$velocity_withsidebarmod = "withsidebar";
				}else if($velocity_archivelayout=="Sidebar Right"){
					$velocity_activate_sidebar="on";
					$velocity_sidebar_orientation ="right";
					$velocity_withsidebarmod = "withsidebar";
				}
				$velocity_bloglayoutclass = "";
				$velocity_sidebar = "Blog Sidebar";
			}
		}
	}

	/* Theme Layout */
	$velocity_slider="";
	$velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
	$velocity_slider_parallax = isset($velocity_themeoptions['velocity_parallax_effects']) ? true : false;
	if($velocity_themelayout=="Full-Width") $velocity_stretchme_on_mobile = "stretchme_on_mobile";
	else $velocity_stretchme_on_mobile = "";

	if(is_tag()) $velocity_htitle = $velocity_tags." ".single_tag_title(' ', false);


	// Excerpt Length
	global $velocity_excerpt_length;
	$velocity_excerpt_length =  empty($velocity_themeoptions['velocity_blogoverviewpost_excerpt']) ? 55 : $velocity_themeoptions['velocity_blogoverviewpost_excerpt'];
	add_filter('excerpt_length', 'velocity_new_excerpt_length');

	get_header();


     //Cutomizer Start
	 $getpreview = isset($_COOKIE['velocitypreview']) && $_COOKIE['velocitypreview']!='' ? $_COOKIE['velocitypreview'] : "velocity1.css";
	 
	 switch ($getpreview) {
		 case "velocity_blue_widedark.css":
		   $velocity_stretchme_on_mobile = "stretchme_on_mobile";
		 break;
		 
		  case "velocity_blue_boxeddark.css":
		    $velocity_stretchme_on_mobile = "";
		 break;
		 
		  case "velocity_brown.css":
		     $velocity_stretchme_on_mobile="";		   	 
		 break;
		 
		  case "velocity_green.css":
		   	 $velocity_stretchme_on_mobile = "";		   	 

		 break;
		 
		  case "velocity_orange.css":
		   	$velocity_stretchme_on_mobile = "stretchme_on_mobile";
		 break;
		 
		  case "velocity_smoked.css":
		   	$velocity_stretchme_on_mobile = "stretchme_on_mobile";
		 break;
		 
		 
		 
		 default:
		 break;
	 }	 	 
	 //Cutomizer END	

	 //Page Slider
		if(isset($velocity_pagecustoms["velocity_activate_slider"]) && $velocity_pagecustoms["velocity_activate_slider"]=="on") {
			$velocity_slider = $velocity_pagecustoms["velocity_header_slider"];
			$velocity_slider_height = velocity_get_revslider_property($velocity_slider,"height");
			if(velocity_get_revslider_property($velocity_slider,"slider_type")=="fullscreen") $velocity_slider_height = 50000;
		}else{
			$velocity_slider ="";
			$velocity_slider_height = "";
		}
	
?>

<?php if($velocity_slider!="" /*&& $velocity_themelayout=="Full-Width"*/){ ?>
	<div class="homesliderwrapper">
		<div class="row homeslider" style="height:<?php echo $velocity_slider_height;?>px; ">
			<?php if(!strpos($velocity_slider, 'howbiz')) echo do_shortcode('[rev_slider '.$velocity_slider.']'); 
				  else echo do_shortcode('[showbiz '.str_replace('showbiz_','',$velocity_slider).']'); 
			?>
		</div>
	</div>
    <!--div class="row firstdivider"></div-->
    <?php if ($velocity_slider_parallax){ ?><script>jQuery(document).ready(function(){initSliderFun();});</script><?php } ?>
<?php } ?>

<!-- Main Container -->
<section id="firstcontentcontainer" class="container <?php echo $velocity_stretchme_on_mobile; ?>">

<!-- Body -->
<section class="row">

	<?php
    //If this is the Blog
    if(!is_tax()){?>

        <!-- Content -->
        <?php if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="right") { ?>
        <section class="span9 left">
        <section class="pagewrapright">
        <?php } else if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="left") { ?>
        <section class="span9 right">
        <section class="pagewrapleft">
        <?php } else { ?>
        <section class="span12 <?php echo $velocity_blogfullclass;?>">
        <?php } ?>

        <!--
        #################################
            -	BLOG	-
        #################################
        -->

		<?php if(have_posts()) : 
				//Postcounter is for Linebreaks + Display
					$velocity_postcounter = 1;
					//while(have_posts()) : the_post();	
						//Custom Blog WP Query
						if(!is_front_page())
							$velocity_paged = (get_query_var('paged')) ? get_query_var('paged') : get_query_var('page');
						else
							$velocity_paged = (get_query_var('page')) ? get_query_var('page') : 1;
						if(empty($velocity_paged)) $velocity_paged = 1;
						$velocity_args = array('offset'=> 0, 'paged'=>$velocity_paged, 'posts_per_page'=>$velocity_posts_per_page);
						$velocity_all_posts = new WP_Query($velocity_args);
						
						//Custom Loop
						while($velocity_all_posts->have_posts() && $velocity_postcounter <= $velocity_posts_per_page) : $velocity_all_posts->the_post();
							$velocity_more = 0;
							$velocity_postcounter++;
							//Post Time & Info
							$velocity_post_time_day = get_post_time('j', true);
							$velocity_post_time_month = date_i18n('M', strtotime($post->post_date_gmt));
							$velocity_post_time_year = get_post_time('Y', true);
							$velocity_post_time_daymonthyear = date_i18n(get_option('date_format'), strtotime($post->post_date_gmt));
				
							$velocity_postcustoms = velocity_getOptions($post->ID);
							$velocity_post_top="";
				
							//Post Type related Object to display in the Head Area of the post
							if(isset($velocity_postcustoms["velocity_post_type"]))
							switch ($velocity_postcustoms["velocity_post_type"]) {
								case 'image':
									$velocity_blogimageurl="";
									$velocity_blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($post->ID) ),$velocity_post_top_width,$velocity_post_top_height,true);
									if($velocity_blogimageurl!=""){
										$velocity_post_top = '<a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.$velocity_blogimageurl.'" alt=""></a>';
									}
								break;
								case 'video':
									if(empty($velocity_postcustoms["velocity_vimeo_id"])) $velocity_postcustoms["velocity_vimeo_id"] = "";
									if(empty($velocity_postcustoms["velocity_youtube_id"])) $velocity_postcustoms["velocity_youtube_id"] = "";

									$velocity_video_width = $velocity_postcustoms["velocity_video_width"];
									$velocity_video_height = $velocity_postcustoms["velocity_video_height"];

									if($velocity_bloglayout != "Small Media"){
										if($velocity_video_width>$velocity_post_top_width)
											$velocity_video_width_ratio = $velocity_video_width/$velocity_post_top_width;
										else
											$velocity_video_width_ratio = $velocity_post_top_width/$velocity_video_width;
									}
									else{
										if($velocity_video_width<$velocity_post_top_width)
											$velocity_video_width_ratio = $velocity_video_width/$velocity_post_top_width;
										else
											$velocity_video_width_ratio = $velocity_post_top_width/$velocity_video_width;
									}
									
									$velocity_video_height = round($velocity_video_height*$velocity_video_width_ratio);
									$velocity_video_width = $velocity_post_top_width;
				
									if(!empty($velocity_postcustoms["velocity_video_type"]) && $velocity_postcustoms["velocity_video_type"]=="youtube"){
										$velocity_post_top = '<div class="scalevid"><iframe src="http://www.youtube.com/embed/'.$velocity_postcustoms["velocity_youtube_id"].'?hd=1&amp;wmode=opaque&amp;autohide=1&amp;showinfo=0"  width="'.$velocity_video_width.'" height="'.$velocity_video_height.'" style="border:0"></iframe></div>';
									}
									elseif (!empty($velocity_postcustoms["velocity_video_type"]) && $velocity_postcustoms["velocity_video_type"]=="vimeo") {
										$velocity_post_top = '<div class="scalevid"><iframe src="http://player.vimeo.com/video/'.$velocity_postcustoms["velocity_vimeo_id"].'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff"  width="'.$velocity_video_width.'" height="'.$velocity_video_height.'" style="border:0"></iframe></div>';
									}
									else $velocity_post_top = '';
								break;
				
								case 'slider':
										$velocity_slider = $velocity_postcustoms["velocity_slider"];
										$velocity_post_top = do_shortcode('[rev_slider '.$velocity_slider.']');
								break;
				
								default:
									$velocity_blogimageurl="";
									$velocity_blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($post->ID) ),$velocity_post_top_width,$velocity_post_top_height,true);
									if(!empty($velocity_blogimageurl)){
										$velocity_post_top = '<a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.$velocity_blogimageurl.'" alt=""></a>';
									}
									else $velocity_post_top = "";
								break;
							}
							
							if ($velocity_post_top =="") $velocity_post_top='';
				
							$velocity_entrycategory = "";
							if(is_tax()){
								$velocity_entrycategory = get_the_term_list( '', "category_".$velocity_tax_slug, '', ', ', '' );
							} else {
								foreach((get_the_category()) as $velocity_category) {
									$velocity_entrycategory .= ', <a href="'.get_category_link($velocity_category->term_id ).'">'.$velocity_category->cat_name.'</a>';
								}
								$velocity_entrycategory = substr($velocity_entrycategory, 2);
							} ?>
				
				            <?php if($velocity_bloglayout == "Small Media" && $velocity_post_top==""){
								$velocity_bloglayoutclass = "nosmallmedia";
							} ?>
				
				            <article <?php post_class('blogpost '.$velocity_bloglayoutclass.' '.$velocity_blogdateclass);?>  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
								<?php if($velocity_bloglayout == "Large Media"){ ?>
									<div class="date">
										<div class="day"><?php echo $velocity_post_time_day;?></div>
										<div class="month"><?php echo $velocity_post_time_month;?></div>
									</div>
									<h2 style="<?php echo $velocity_titlemod; ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<div class="postinfo" <?php echo $velocity_nopostinfo; ?>>
										<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_date'])){ ?><div class="time"><?php echo $velocity_post_time_daymonthyear; ?></div><?php } ?>
										<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_author'])){ ?><div class="author"><?php echo $velocity_by ?> <?php the_author_posts_link(); ?></div><?php } ?>
										<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_category'])){ ?><div class="categories"><?php echo $velocity_in ?> <?php echo $velocity_entrycategory; ?></div><?php } ?>
										<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_comments']) && comments_open()){ ?><div class="comments"><?php comments_popup_link(__('no Comments', 'velocity'), __('one Comment', 'velocity'), __( '% Comments', 'velocity')); ?></div><?php } ?>
										<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_tags']) && has_tag()){ ?><div class="tags"><?php echo $velocity_tagged ?> <?php echo the_tags('', ', ', ''); ?></div><?php } ?>
									</div>
								<?php } ?>
				
				                <section class="post">
				
				                    <?php if($velocity_bloglayout == "Small Media"){ ?>
				                        <?php if ($velocity_post_top!=""){?>
				                            <div class="postmedia">
				                                <?php echo $velocity_post_top; ?>
				                            </div>
				                        <?php } ?>
				                    <?php } ?>
				
				                    <section class="postbody">
				
										<?php if($velocity_bloglayout == "Small Media"){ ?>
											<div class="date">
												<div class="day"><?php echo $velocity_post_time_day;?></div>
												<div class="month"><?php echo $velocity_post_time_month;?></div>
											</div>
											<h2 style="<?php echo $velocity_titlemod; ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
											<div class="postinfo" <?php echo $velocity_nopostinfo; ?>>
																<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_date'])){ ?><div class="time"><?php echo $velocity_post_time_daymonthyear; ?></div><?php } ?>
										<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_author'])){ ?><div class="author"><?php echo $velocity_by ?> <?php the_author_posts_link(); ?></div><?php } ?>
										<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_category'])){ ?><div class="categories"><?php echo $velocity_in ?> <?php echo $velocity_entrycategory; ?></div><?php } ?>
										<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_comments']) && comments_open()){ ?><div class="comments"><?php comments_popup_link(__('no Comments', 'velocity'), __('one Comment', 'velocity'), __( '% Comments', 'velocity')); ?></div><?php } ?>
										<?php if (isset($velocity_themeoptions['velocity_blogoverviewpostinfo_tags']) && has_tag()){ ?><div class="tags"><?php echo $velocity_tagged ?> <?php echo the_tags('', ', ', ''); ?></div><?php } ?>
											</div>
										<?php } ?>
				
				                        <?php if($velocity_bloglayout == "Large Media"){ ?>
				                            <?php if ($velocity_post_top!=""){?>
				                            	<?php if ($velocity_postcustoms["velocity_post_type"]!='slider') {?>
				                                <div class="postmedia">
				                                    <?php echo $velocity_post_top; ?>
				                                </div>
				                            	<?php } else {?>
				                            		  <div class="postmedia-slide">
				                                    <?php echo $velocity_post_top; ?>
				                                </div>
				                            	<?php } ?>
				                            <?php } ?>
				                        <?php } ?>
				
				                        <div class="posttext"><?php the_excerpt(); ?></div>
										<div class="readmore"><a href="<?php the_permalink(); ?>" class="btn"><?php echo $velocity_readmore ?></a></div>
				
				                    </section>
				
				                    <div class="postdivider"></div>
				                </section>
				            </article>
				
				            <?php  if($velocity_bloglayout == "Small Media"){ $velocity_bloglayoutclass = "smallmedia"; } ?>

        <!-- Loop End -->
        <?php endwhile; //endwhile; ?>

        <!-- Content End -->
        <?php if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="right") { ?>
        </section>
            <?php if(function_exists('velocity_spec_pagination')){ velocity_spec_pagination($velocity_all_posts); }else{ paginate_links(); } ?>
        </section>
        <?php } else if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="left") { ?>
        </section>
            <?php if(function_exists('velocity_spec_pagination')){ velocity_spec_pagination($velocity_all_posts); }else{ paginate_links(); } ?>
        </section>
        <?php } else { ?>
            <?php if(function_exists('velocity_spec_pagination')){ velocity_spec_pagination($velocity_all_posts); }else{ paginate_links(); } ?>
        </section>
        <?php } ?>

        <?php else : ?>
            <article>
				<h4 style="text-align:center; margin-bottom: 500px;"><?php _e('Oops, we could not find what you were looking for...', 'velocity'); ?></h4>
            </article>
        <?php endif; ?>

        <?php //If this is the portfolio
    	} else if(is_tax()){ ?>

        	<!-- Content -->
			<?php if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="right") { ?>
            <section class="span9 left">
            <section class="pagewrapright">
            <?php } else if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="left") { ?>
            <section class="span9 right">
            <section class="pagewrapleft">
            <?php } else { ?>
            <section class="span12 <?php echo $velocity_blogfullclass;?>">
            <?php } ?>

            <!-- Portfolio -->
            <article class="row <?php echo $velocity_display_span; ?> portfoliowrap">

                <!-- Portfolio Items -->
                <div class="portfolio <?php echo $velocity_withsidebarmod ?>">

                    <?php if ($wp_query->have_posts()) : ?>
                    <?php while ( $wp_query->have_posts() ) : $wp_query->the_post();

                        $velocity_postcustoms = getOptions($post->ID);

                        //Post Type
                        if(isset($velocity_postcustoms["velocity_post_type"])){
                            switch ($velocity_postcustoms["velocity_post_type"]) {
                                case 'video':
                                    if($velocity_postcustoms["velocity_video_type"]=="youtube") $velocity_blogimageurl_pp = "http://www.youtube.com/watch?v=".$velocity_postcustoms["velocity_youtube_id"];
                                    elseif($velocity_postcustoms["velocity_video_type"]=="vimeo") $velocity_blogimageurl_pp = "http://vimeo.com/".$velocity_postcustoms["velocity_vimeo_id"];
                                    else $velocity_blogimageurl_pp = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
                                break;
                                default:
                                    $velocity_blogimageurl_pp = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
                                break;
                            }
                        }

                        //Post Features
                        if(isset($velocity_postcustoms["velocity_item_categories"])) $velocity_item_categories = "Off";
                        else $velocity_item_categories = "On";

                        if(isset($velocity_postcustoms["velocity_item_features"])) $velocity_item_features = $velocity_postcustoms['velocity_item_features'];
                        else $velocity_item_features = "linkzoom";

                        $velocity_p_linkactive = "Off";
                        $velocity_p_zoomactive = "Off";
                        if($velocity_item_features=="link" || $velocity_item_features=="linkzoom"){ $velocity_p_linkactive = "On"; }
                        if($velocity_item_features=="zoom" || $velocity_item_features=="linkzoom"){ $velocity_p_zoomactive = "On"; }

                        if($velocity_item_features=="linkzoom"){ $velocity_notalonemod = "notalone"; } else { $velocity_notalonemod = ""; }

                        $velocity_blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($post->ID)),400,$velocity_portfolioheightlock,true);

                        $velocity_categorylinks = get_the_term_list( '', "category_".$velocity_tax_slug, '', ', ', '' );
                    ?>

                    <div class="entry <?php echo $velocity_categorylist; ?>">
						<div class="holderwrap">
							<div class="mediaholder">
								<img src="<?php echo $velocity_blogimageurl; ?>" alt="">
								<div class="cover"></div>
								<?php if($velocity_p_linkactive=="On"){ ?><a href="<?php the_permalink(); ?>"><div class="link icon-plus <?php echo $velocity_notalonemod; ?>"></div></a><?php } ?>
								<?php if($velocity_p_zoomactive=="On"){ ?><a title="<?php the_title(); ?>" href="<?php echo $velocity_blogimageurl_pp; ?>" rel="group1" data-rel="group1" class="fancybox"><div class="show icon-search <?php echo $velocity_notalonemod; ?>"></div></a><?php } ?>
							</div>
							<div class="foliotextholder">
								<div class="foliotextwrapper">
									<h5 class="itemtitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
									<?php if($velocity_item_categories=="On"){ ?><span class="itemcategories"><?php echo $velocity_categorylinks ?></span><?php } ?>
								</div>
							</div>
						</div>
					</div>

                    <?php endwhile; endif; //have_posts ?>

                </div>
            </article>

            <!-- Content End -->
            <?php if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="right") { ?>
            </section>
                <?php if(function_exists('velocity_spec_pagination')){ echo'<div class="row" style="margin-top:30px;"></div>'; velocity_spec_pagination($velocity_all_posts); }else{ paginate_links(); } ?>
            </section>
            <?php } else if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="left") { ?>
            </section>
                <?php if(function_exists('velocity_spec_pagination')){ echo'<div class="row" style="margin-top:30px;"></div>'; velocity_spec_pagination($velocity_all_posts); }else{ paginate_links(); } ?>
            </section>
            <?php } else { ?>
                <?php if(function_exists('velocity_spec_pagination')){ echo'<div class="row" style="margin-top:30px;"></div>'; velocity_spec_pagination($velocity_all_posts); }else{ paginate_links(); } ?>
            </section>
            <?php } ?>


        <?php } ?>


        <?php if ($velocity_activate_sidebar=="on"){?>
        <!--
        #####################
            -	SIDEBAR	-
        #####################
        -->
		
		<?php if($velocity_bloglayout == "Large Media"){ $velocity_sbmod = ""; } else { $velocity_sbmod = "style='margin-top: 0px !important;'"; }?>
		
        <aside class="span3 right sidebar" <?php echo $velocity_sbmod ?>>
			<section class="row">

                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($velocity_sidebar) ) : ?>
                    <article class="span3 widget">
                        <div class="footertitle"><h4>Sidebar Widget Area</h4></div>
                        <div class="widgetclass">
                        Please configure this Widget Area in the Admin Panel under Appearance -> Widgets
                        </div><div class="clear"></div>
                    </article>
                <?php endif; ?>

        	</section>
        </aside>
        <!-- /Sidebar -->
        <?php } ?>

    </section><div class="clear"></div>
    <!-- /Body -->

	<!-- Bottom Spacing -->
    <div class="row top70"></div>

</section><!-- /container -->

<?php get_footer(); ?>