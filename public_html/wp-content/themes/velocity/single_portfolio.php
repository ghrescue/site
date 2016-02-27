<?php

	//Language Options
	$velocity_relates = __('More Projects', 'velocity');
	$velocity_nextproject = __('Next Project &raquo;', 'velocity');
	$velocity_previousproject = __('&laquo; Previous Project', 'velocity');
	$velocity_all = __('All', 'velocity');
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

	if(!empty($velocity_pagecustoms["velocity_launch_project"]) && !empty($velocity_pagecustoms["velocity_launch_project_type"])  && $velocity_pagecustoms["velocity_launch_project_type"]=="external")
		header('Location: '.$velocity_pagecustoms["velocity_launch_project"]);

	// Themeoptions
	$velocity_themeoptions = velocity_getThemeOptions();

	//Portfolio Style Options!
	$velocity_nopostinfo = !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_date']) && !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_author']) && !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_category']) && !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_comments']) && !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_tags']) ? "style='border:none;'" : "";

	$velocity_nopostinfo = !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_date']) && !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_author']) && !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_category']) && !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_comments']) && !isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_tags']) && !isset($velocity_themeoptions["damojoPortfolio_portfoliopostinfo_nav"]) && empty($velocity_postcustoms["velocity_launch_project"]) ? "style='display:none;'" : "";

	if ($velocity_nopostinfo == "style='border:none;'"){ $velocity_titlemod = "margin-bottom: 14px;"; } else { $velocity_titlemod = ""; }
	$velocity_bloglayout = 'Large Media';

	$velocity_blogdateclass = "nodate";
	if ($velocity_nopostinfo == "style='border:none;'" && $velocity_blogdateclass == ""){ $velocity_titlemod = "margin-bottom: 36px;"; } 
	if ($velocity_nopostinfo == "style='border:none;'" && $velocity_blogdateclass == "nodate"){ $velocity_titlemod = "margin-bottom: 14px; margin-top: 16px;"; } 

	//Sidebar & Blog Style
	if(isset($velocity_pagecustoms["velocity_activate_sidebar"])){
		if (isset($velocity_pagecustoms["velocity_sidebar"])){$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar"];}else{$velocity_sidebar = "Blog Sidebar";}
		$velocity_sidebar_orientation = $velocity_pagecustoms["velocity_sidebar_orientation"];
		$velocity_activate_sidebar="on";
		$velocity_withsidebarmod = "withsidebar";
		$velocity_blogfullclass = "";
		$velocity_bloglayoutclass = "";

		$velocity_related_span="span8";
		$velocity_related_wrapspan="span8";

		$velocity_post_top_width = 850;
		$velocity_post_top_height = "";
		if($velocity_bloglayout == "Small Media"){
			$velocity_bloglayoutclass = "smallmedia";
			$velocity_post_top_width = 710;
			$velocity_post_top_height = "";
		}
	}
	else {
		$velocity_activate_sidebar="off";
		$velocity_withsidebarmod = "";
		$velocity_blogfullclass = "fullblog";

		$velocity_related_span="span12";
		$velocity_related_wrapspan="span12";

		$velocity_post_top_width = 1170;
		$velocity_post_top_height = "";
		$velocity_bloglayoutclass = "";
		if($velocity_bloglayout == "Small Media"){
			$velocity_bloglayoutclass = "smallmedia";
			$velocity_post_top_width = 710;
			$velocity_post_top_height = "";
		}
	}

	// Pagetitle
	if(isset($velocity_pagecustoms['velocity_activate_page_title'])){ $velocity_headline = "off";} else {$velocity_headline = "on";}
	if(isset($velocity_pagecustoms['velocity_header_title']))$velocity_htitle = $velocity_pagecustoms['velocity_header_title']; else $velocity_htitle= "".get_the_title()."";
	if(isset($velocity_pagecustoms["velocity_title_orientation"])) $velocity_title_orientation = $velocity_pagecustoms["velocity_title_orientation"]; else $velocity_title_orientation = "left";
	if($velocity_title_orientation == "left"){
		$velocity_torient = "";
	} else if($velocity_title_orientation == "center"){
		$velocity_torient = "text-align: center;";
	}

	/* Theme Layout */
	$velocity_slider="";
	$velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
	$velocity_slider_parallax = isset($velocity_themeoptions['velocity_parallax_effects']) ? true : false;
	if($velocity_themelayout=="Full-Width") $velocity_stretchme_on_mobile = "stretchme_on_mobile";
	else $velocity_stretchme_on_mobile = "";

	if(have_posts()) $velocity_current_cat = get_the_category();

	//Portfolio
	$velocity_ptype = get_post_type();
	$velocity_pcat = "category_".$velocity_ptype;
	//Page Slider
	if(isset($velocity_pagecustoms["velocity_activate_slider"]) && $velocity_pagecustoms["velocity_activate_slider"]=="on") {
		$velocity_slider = $velocity_pagecustoms["velocity_header_slider"];
		$velocity_slider_height = velocity_get_revslider_property($velocity_slider,"height");
		if(velocity_get_revslider_property($velocity_slider,"slider_type")=="fullscreen") $velocity_slider_height = 50000;
	}else{
		$velocity_slider ="";
		$velocity_slider_height = "";
	}

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
?>

<?php if($velocity_slider!="" /*&& $velocity_themelayout=="Full-Width"*/){ ?>
	<div class="homesliderwrapper">
		<div class="row homeslider" style="height:<?php echo $velocity_slider_height;?>px;<?php if(strpos($velocity_slider, 'howbiz')) echo 'padding-bottom:40px;';?> ">
			<?php if(!strpos($velocity_slider, 'howbiz')) echo do_shortcode('[rev_slider '.$velocity_slider.']'); 
				  else echo do_shortcode('[showbiz '.str_replace('showbiz_','',$velocity_slider).']'); 
			?>
		</div>	</div>
    <!--div class="row firstdivider"></div-->
    <?php if ($velocity_slider_parallax){ ?><script>jQuery(document).ready(function(){initSliderFun();});</script><?php } ?>
<?php } ?>

<!-- Main Container -->
<section id="firstcontentcontainer" class="container <?php echo $velocity_stretchme_on_mobile; ?>">

<!-- Body -->
<section class="row">

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
        while(have_posts()) : the_post();

			//Post Time & Info
			$velocity_post_time_day = get_post_time('j', true);
			$velocity_post_time_month = date_i18n('M', strtotime(get_the_date()));
			$velocity_post_time_year = get_post_time('Y', true);
			$velocity_post_time_daymonthyear = date_i18n(get_option('date_format'), strtotime(get_the_date()));

			$velocity_postcustoms = velocity_getOptions($velocity_post_id);
			$velocity_post_top="";
			//Post Type related Object to display in the Head Area of the post
			if(isset($velocity_postcustoms["velocity_post_type"]))
			switch ($velocity_postcustoms["velocity_post_type"]) {
				case 'image':
					$velocity_blogimageurl="";
					$velocity_blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id() ),$velocity_post_top_width,$velocity_post_top_height,true);
					if($velocity_blogimageurl!=""){
						$velocity_post_top = '<img src="'.$velocity_blogimageurl.'" alt="'.get_the_title().'">';
					}
				break;
				case 'video':
					$velocity_video_width = $velocity_postcustoms["velocity_video_width"];
					$velocity_video_height = $velocity_postcustoms["velocity_video_height"];

					if($velocity_video_width>$velocity_post_top_width)
						$velocity_video_width_ratio = $velocity_video_width/$velocity_post_top_width;
					else
						$velocity_video_width_ratio = $velocity_post_top_width/$velocity_video_width;

					$velocity_video_height = round($velocity_video_height*$velocity_video_width_ratio);
					$velocity_video_width = $velocity_post_top_width;

					if($velocity_postcustoms["velocity_video_type"]=="youtube"){
						$velocity_post_top = '<div class="scalevid"><iframe src="http://www.youtube.com/embed/'.$velocity_postcustoms["velocity_youtube_id"].'?hd=1&amp;wmode=opaque&amp;autohide=1&amp;showinfo=0" width="'.$velocity_postcustoms["velocity_video_width"].'" height="'.$velocity_postcustoms["velocity_video_height"].'" style="border:0"></iframe></div>';
					}
					elseif ($velocity_postcustoms["velocity_video_type"]=="vimeo") {
						$velocity_post_top = '<div class="scalevid"><iframe src="http://player.vimeo.com/video/'.$velocity_postcustoms["velocity_vimeo_id"].'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="'.$velocity_postcustoms["velocity_video_width"].'" height="'.$velocity_postcustoms["velocity_video_height"].'" style="border:0"></iframe></div>';
					}
				break;

				case 'slider':
						$velocity_slider = $velocity_postcustoms["velocity_slider"];
						$velocity_post_top = do_shortcode('[rev_slider '.$velocity_slider.']');
				break;

				default:
					$velocity_post_top = "";
				break;
			}

			$velocity_entrycategory = get_the_term_list( '', $velocity_pcat, '', ', ', '' );
			?>

			<?php if($velocity_bloglayout == "Small Media" && $velocity_post_top==""){
				$velocity_bloglayoutclass = "nosmallmedia";
			} ?>

            <article class="blogpost singlepost singlefolio <?php echo $velocity_bloglayoutclass;?> <?php echo $velocity_blogdateclass;?>" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				
				<?php if($velocity_bloglayout == "Large Media"){ ?>
					<header>
											</header>
				<?php } ?>

                <section class="post">
                
	                <div class="postinfo" <?php echo $velocity_nopostinfo; ?>>
						<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_date'])){ ?><div class="time"><?php echo $velocity_post_time_daymonthyear; ?></div><?php } ?>
						<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_author'])){ ?><div class="author"><?php echo $velocity_by ?> <?php the_author_posts_link(); ?></div><?php } ?>
						<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_category'])){ ?><div class="categories"><?php echo $velocity_in ?> <?php echo $velocity_entrycategory; ?></div><?php } ?>
						<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_comments']) && comments_open()){ ?><div class="comments"><?php comments_popup_link(__('no Comments', 'velocity'), __('one Comment', 'velocity'), __( '% Comments', 'velocity')); ?></div><?php } ?>
						<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_tags']) && has_tag()){ ?><div class="tags"><?php echo $velocity_tagged ?> <?php echo the_tags('', ', ', ''); ?></div><?php } ?>
					</div>
					
					                        
                        <div class="projectnavwrapper">
                        <?php if(!empty($velocity_postcustoms["velocity_launch_project"])){ ?><a href="<?php echo $velocity_postcustoms["velocity_launch_project"];?>" target="_blank" class="btn btn-primary btn-normal launchbtn"><?php _e("Launch Project","velocity");?></a><?php } ?>
                        <?php if(isset($velocity_themeoptions["damojoPortfolio_portfoliopostinfo_nav"])) { next_post_link('<div class="projectnav previousproject" data-rel="tooltip" title="'.__("Previous Project","velocity").'">%link</div>', '' ); previous_post_link('<div class="projectnav nextproject" data-rel="tooltip" title="'.__("Next Project","velocity").'">%link</div>', '' );   }?></div>

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
							<!--h2 style="<?php echo $velocity_titlemod; ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2-->
							

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
                            
                            <div class="date">
								<div class="day"><?php echo $velocity_post_time_day;?></div>
								<div class="month"><?php echo $velocity_post_time_month;?></div>
							</div>
							<!--div class="postinfo" <?php echo $velocity_nopostinfo; ?>>
								<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_date'])){ ?><div class="time"><?php echo $velocity_post_time_daymonthyear; ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_author'])){ ?><div class="author"><?php echo $velocity_by ?> <?php the_author_posts_link(); ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_category'])){ ?><div class="categories"><?php echo $velocity_in ?> <?php echo $velocity_entrycategory; ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_comments']) && comments_open()){ ?><div class="comments"><?php comments_popup_link(__('no Comments', 'velocity'), __('one Comment', 'velocity'), __( '% Comments', 'velocity')); ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliopostinfo_tags']) && has_tag()){ ?><div class="tags"><?php echo $velocity_tagged ?> <?php echo the_tags('', ', ', ''); ?></div><?php } ?>
							</div-->
							
                        <?php } ?>

                        <div class="posttext"><?php the_content(); ?></div>
                        
                    </section>
                </section>
            </article>

            <?php if ( comments_open() ) {
				$velocity_relatedmod = "";
			} else {
				$velocity_relatedmod = 'style="border-top: 0; margin-top: 0; padding-top: 0;"';
			} ?>

            <!-- Post Comments -->
            <?php comments_template('', true); ?>
            <!-- Post Comments End -->

            <!-- Loop End -->
            <?php endwhile; ?>


		<?php if (isset($velocity_themeoptions['damojoPortfolio_portfoliorelated'])){ ?>
        <!-- Related Projects -->
        <article class="relatedposts" <?php echo $velocity_relatedmod ?>>
        
        	 <div class="wpb_separator wpb_content_element"></div>

			<div class="contenttitle"><div class="titletext"><h2><?php echo $velocity_relates; ?></h2></div></div>

            <section class="row fourcol portfoliowrap">
                <div class="portfolio <?php echo $velocity_withsidebarmod ?>">

                    <?php
					if ($velocity_activate_sidebar=="on") { $velocity_portfoliopp = 3; } else { $velocity_portfoliopp = 4; }
                    $velocity_args=array(
                        'post_type' => $velocity_ptype,
                        'post__not_in' => array($velocity_post_id),
                        'posts_per_page' => $velocity_portfoliopp
                    );
                    $velocity_temp = $wp_query;
                    $wp_query = null;
                    $wp_query = new WP_Query();
                    $wp_query->query($velocity_args);
                    ?>

                    <?php if ($wp_query->have_posts()) : ?>
                    <?php while ( $wp_query->have_posts() ) : $wp_query->the_post();

                        global $post;
                        $velocity_postcustoms = velocity_getOptions($post->ID);

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
                        else $velocity_item_features = "link";

                        $velocity_p_linkactive = "Off";
                        $velocity_p_zoomactive = "Off";
                        if($velocity_item_features=="link" || $velocity_item_features=="linkzoom"){ $velocity_p_linkactive = "On"; }
                        if($velocity_item_features=="zoom"){ $velocity_p_zoomactive = "On"; }

                        if($velocity_item_features=="linkzoom"){ $velocity_notalonemod = "notalone"; } else { $velocity_notalonemod = ""; }

                        $velocity_themeoptions['velocity_portfolioheightlock'] = empty($velocity_themeoptions['damojoPortfolio_portfolioheightlock']) ? 320 : $velocity_themeoptions['damojoPortfolio_portfolioheightlock'];

                        $velocity_blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($post->ID)),400,250,true);

                        $velocity_categorylinks = get_the_term_list( $post->ID, $velocity_pcat, '', ', ', '' );
                        $velocity_categories = get_the_terms($post->ID,$velocity_pcat);
                        $velocity_categorylist="";
                        if(is_array($velocity_categories)){
                            foreach ($velocity_categories as $velocity_category) {
                                $velocity_categorylist.= $velocity_category->slug." ";
                            }
                        }

                    ?>
					<div class="entry <?php echo $velocity_categorylist; ?>">
						<div class="holderwrap">
							<div class="mediaholder">
								<img src="<?php echo $velocity_blogimageurl; ?>" alt="">
								<div class="cover"></div>
					<?php if($velocity_p_linkactive=="On"){ ?> <a href="<?php echo get_permalink($post->ID);?>"><div class="link icon-forward <?php echo $notalonemod; ?>"></div></a><?php  } ?>
					<?php if($velocity_p_zoomactive=="On"){ ?> <a title="<?php echo get_the_title($post->ID);?>" href="<?php echo $velocity_blogimageurl_pp; ?>" rel="imagegroup" data-rel="imagegroup" class="fancybox"><div class=" show icon-search <?php echo $velocity_notalonemod; ?>"></div></a><?php } ?>
							</div>
							<div class="foliotextholder">
								<div class="foliotextwrapper">
									<h5 class="itemtitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
									<?php if($velocity_item_categories=="On"){ ?><span class="itemcategories"><?php echo $velocity_categorylinks ?></span><?php } ?>
								</div>
							  <div class="clear"></div>
							</div>
							<div class="folio_underlay">
							</div>
						</div>
					</div>

                    <?php endwhile; endif; //have_posts ?>
                    <?php
                    $wp_query = null;
                    $wp_query = $velocity_temp;
                    wp_reset_query();
                    ?>

                </div>
            </section>
        </article><div class="clear"></div>
        <?php } ?>
        <!-- Related Projects End -->
        
        <!-- Content End -->
        <?php if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="right") { ?>
        </section>
            <?php if(function_exists('pagination')){ pagination(); }else{ paginate_links(); } ?>
        </section>
        <?php } else if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="left") { ?>
        </section>
            <?php if(function_exists('pagination')){ pagination(); }else{ paginate_links(); } ?>
        </section>
        <?php } else { ?>
            <?php if(function_exists('pagination')){ pagination(); }else{ paginate_links(); } ?>
        </section>
        <?php } ?>

        <?php else : ?>
            <div class="span12">
                <p><?php _e('Oops, we could not find what you were looking for...', 'velocity'); ?></p>
            </div>
        <?php endif; ?>



        <?php if ($velocity_activate_sidebar=="on"){?>
        <!--
        #####################
            -	SIDEBAR	-
        #####################
        -->
        <aside class="span3 right sidebar">
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
    <div class="row top50"></div>

</section><!-- /container -->

<?php get_footer(); ?>