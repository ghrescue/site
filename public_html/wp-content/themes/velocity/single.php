<?php

	//Language Options
	$velocity_readmore = __('Read More', 'velocity');
	$velocity_category = __('Category', 'velocity');
	$velocity_relates = __('Related Posts', 'velocity');
	$velocity_categoryname = __('Category', 'velocity');
	$velocity_archivename = __('Archives', 'velocity');
	$velocity_tags = __('Tag', 'velocity');
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

	//if(have_posts()) 
	$velocity_pagecustoms = velocity_getOptions($velocity_post_id);

	// Themeoptions
	$velocity_themeoptions = velocity_getThemeOptions();

	//Blog Style Options
	$velocity_nopostinfo = !isset($velocity_themeoptions['velocity_blogpostinfo_date']) && !isset($velocity_themeoptions['velocity_blogpostinfo_author']) && !isset($velocity_themeoptions['velocity_blogpostinfo_category']) && !isset($velocity_themeoptions['velocity_blogpostinfo_comments']) && !isset($velocity_themeoptions['velocity_blogpostinfo_tags']) ? "style='display:none;'" : "";
	if ($velocity_nopostinfo == "style='display:none;'"){ $velocity_titlemod = "margin-bottom: 14px;"; } else { $velocity_titlemod = ""; }
	$velocity_bloglayout = $velocity_themeoptions['velocity_blogpostlayout'];
	//$velocity_blogdateclass = isset($velocity_themeoptions['velocity_blogsingledate']) ? "" : "nodate";
	$velocity_blogdateclass = "nodate";
	if ($velocity_nopostinfo == "style='display:none;'" && $velocity_blogdateclass == ""){ $velocity_titlemod = "margin-bottom: 36px;"; } 

	$velocity_bloglayoutclass = "";

	//Default Sidebar
		if(!isset($velocity_pagecustoms["velocity_activate_sidebar"])){
			if (isset($velocity_themeoptions["velocity_blogpostsidebar"]) && $velocity_themeoptions["velocity_blogpostsidebar"]!="Full-Width"){
				$velocity_pagecustoms["velocity_activate_sidebar"] = 'on';
				$velocity_pagecustoms["velocity_sidebar"] = $velocity_themeoptions["velocity_blogpostsidebar_select"]; 
				$velocity_pagecustoms["velocity_sidebar_orientation"] = $velocity_themeoptions["velocity_blogpostsidebar"];
			}	
		}
	
	//Sidebar & Blog Style
	if(isset($velocity_pagecustoms["velocity_activate_sidebar"])){
		if (isset($velocity_pagecustoms["velocity_sidebar"])){$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar"];}else{$velocity_sidebar = "Blog Sidebar";}
		$velocity_sidebar_orientation = $velocity_pagecustoms["velocity_sidebar_orientation"];
		$velocity_activate_sidebar="on";
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
		$velocity_blogfullclass = "fullblog";

		$velocity_related_span="span12";
		$velocity_related_wrapspan="span12";

		$velocity_post_top_width = 1170;
		$velocity_post_top_height = "";
		if($velocity_bloglayout == "Small Media"){
			$velocity_bloglayoutclass = "smallmedia";
			$velocity_post_top_width = 710;
			$velocity_post_top_height = "";
		}
	}

	// Pagetitle
	if(isset($velocity_pagecustoms['velocity_activate_page_title'])){ $velocity_headline = "off";} else {$velocity_headline = "on";}
	if(isset($velocity_pagecustoms['velocity_header_title']))$velocity_htitle = $velocity_pagecustoms['velocity_header_title']; else $velocity_htitle= "".get_the_title()."";
	if(have_posts()) $velocity_current_cat = get_the_category();

	/* Theme Layout */
	$velocity_slider="";
	$velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
	$velocity_slider_parallax = isset($velocity_themeoptions['velocity_parallax_effects']) ? true : false;
	if($velocity_themelayout=="Full-Width") $velocity_stretchme_on_mobile = "stretchme_on_mobile";
	else $velocity_stretchme_on_mobile = "";

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
		<div class="row homeslider" style="height:<?php echo $velocity_slider_height;?>px;">
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

			$velocity_entrycategory = "";
			if(is_tax()){
				$velocity_entrycategory = get_the_term_list( '', "category_".$velocity_tax_slug, '<div class="blogcategory">', '</div><div class="blogcategory">', '</div>' );
			} else {
				foreach((get_the_category()) as $velocity_category) {
					$velocity_entrycategory .= ', <a href="'.get_category_link($velocity_category->term_id ).'">'.$velocity_category->cat_name.'</a>';
				}
				$velocity_entrycategory = substr($velocity_entrycategory, 2);
			} ?>

			<?php if($velocity_bloglayout == "Small Media" && $velocity_post_top==""){
				$velocity_bloglayoutclass = "nosmallmedia";
			} 
			
			//Post Pagination args
				$velocity_postpaginationargs = array(
					'before'           => '<div class="postdivider"></div><div class="pagenavi paginatedpost"><span class="pages">' . __('Continue Reading','velocity').'</span><div class="pagination"><ul>',
					'after'            => '</ul></div></div>',
					'link_before'      => '<span>',
					'link_after'       => '</span>',
					'next_or_number'   => 'number',
					'nextpagelink'     => __('Next','velocity'),
					'previouspagelink' => __('Previous','velocity'),
					'pagelink'         => '<li>%</li>',
					'echo'             => 1
				); 
			?>

            <article class="blogpost singlepost <?php echo $velocity_bloglayoutclass;?> <?php echo $velocity_blogdateclass;?>" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if($velocity_bloglayout == "Large Media"){ ?>
					
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
							<!--h2 style="<?php echo $velocity_titlemod; ?>"><?php the_title(); ?></h2-->
							<div class="postinfo" <?php echo $velocity_nopostinfo; ?>>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_date'])){ ?><div class="time"><?php echo $velocity_post_time_daymonthyear; ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_author'])){ ?><div class="author"><?php echo $velocity_by ?> <?php the_author_posts_link(); ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_category'])){ ?><div class="categories"><?php echo $velocity_in ?> <?php echo $velocity_entrycategory; ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_comments']) && comments_open()){ ?><div class="comments"><?php comments_popup_link(__('no Comments', 'velocity'), __('one Comment', 'velocity'), __( '% Comments', 'velocity')); ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_tags']) && has_tag()){ ?><div class="tags"><?php echo $velocity_tagged ?> <?php echo the_tags('', ', ', ''); ?></div><?php } ?>
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
                            
                            <div class="date">
								<div class="day"><?php echo $velocity_post_time_day;?></div>
								<div class="month"><?php echo $velocity_post_time_month;?></div>
							</div>
							<h2 style="<?php echo $velocity_titlemod; ?>"><!--<?php the_title(); ?>--></h2>
							<div class="postinfo" <?php echo $velocity_nopostinfo; ?>>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_date'])){ ?><div class="time"><?php echo $velocity_post_time_daymonthyear; ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_author'])){ ?><div class="author"><?php echo $velocity_by ?> <?php the_author_posts_link(); ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_category'])){ ?><div class="categories"><?php echo $velocity_in ?> <?php echo $velocity_entrycategory; ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_comments']) && comments_open()){ ?><div class="comments"><?php comments_popup_link(__('no Comments', 'velocity'), __('one Comment', 'velocity'), __( '% Comments', 'velocity')); ?></div><?php } ?>
								<?php if (isset($velocity_themeoptions['velocity_blogpostinfo_tags']) && has_tag()){ ?><div class="tags"><?php echo $velocity_tagged ?> <?php echo the_tags('', ', ', ''); ?></div><?php } ?>
							</div>
                            
                        <?php } ?>

                        <div class="posttext"><?php the_content(); ?></div>
						<?php wp_link_pages( $velocity_postpaginationargs ); ?>
                    </section>
                </section>
            </article>

            <!-- Post Comments -->
            <?php comments_template('', true); ?>
            <!-- Post Comments End -->

            <!-- Loop End -->
            <?php endwhile; ?>

            <?php if (isset($velocity_themeoptions['velocity_blogrelated'])){ ?>

                <!-- Related Posts -->
                <?php
                $velocity_tags = wp_get_post_tags($post->ID);

                if ($velocity_tags) {
                    $velocity_tag_ids = array();
                    foreach($velocity_tags as $velocity_individual_tag) {
						$velocity_tag_ids[] = $velocity_individual_tag->term_id;
					}

                    $velocity_args=array(
                        'tag__in' => $velocity_tag_ids,
                        'post__not_in' => array($post->ID),
                        'showposts'=> 4,
                        'ignore_sticky_posts'=> 1,
                        'suppress_filters' => 0
                    );
                    $velocity_temp = $wp_query;
                    $velocity_my_query = new wp_query($velocity_args);
                    if( $velocity_my_query->have_posts() ) {
                        ?>
                        <div class="clear"></div>
                        <div class="wpb_separator wpb_content_element"></div>

                        <div class="relatedposts">
                        
						<div class="contenttitle"><div class="titletext"><h2><?php echo $velocity_relates; ?></h2></div></div>

                        <div class="<?php echo $velocity_related_wrapspan ?> relatedwrap">
                        	<div class="homeposts">

                            <?php
                            $counter = 0;
                            while ($velocity_my_query->have_posts()) { $velocity_my_query->the_post();

                                $velocity_newspostcustoms = velocity_getOptions($post->ID);
                                $velocity_newscategory = get_the_category($post->ID);
                                $velocity_newsfirst_category = $velocity_newscategory[0]->cat_name;
                                $velocity_newsrepl = strtolower((preg_replace('/\s+/', '-', $velocity_newsfirst_category)));
                                $velocity_newsbase = home_url();
                                $velocity_newsimageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

                                $velocity_post_time_day = date_i18n('j', strtotime($post->post_date_gmt));
                                $velocity_post_time_month = date_i18n('M', strtotime($post->post_date_gmt));
                                $velocity_post_time_year = date_i18n('Y', strtotime($post->post_date_gmt));

                                $velocity_newsexcerpt = velocity_excerpt(15);
                                $velocity_thenewsimageurl = aq_resize( $velocity_newsimageurl, 50, 50, true );

                                $velocity_newscatlist = "";
								foreach((get_the_category($post->ID)) as $velocity_category) {
									$velocity_newscatlist .= ', <a href="'.get_category_link($velocity_category->term_id ).'">'.$velocity_category->cat_name.'</a>';
								}
								$velocity_newscatlist = substr($velocity_newscatlist, 2);

								$velocity_num_comments = get_comments_number($post->ID);
								if ( comments_open() ) {
								 if ( $velocity_num_comments == 0 ) {
								  $velocity_comments = __('No Comments','velocity');
								 } elseif ( $velocity_num_comments > 1 ) {
								  $velocity_comments = $velocity_num_comments . __(' Comments','velocity');
								 } else {
								  $velocity_comments = __('1 Comment','velocity');
								 }
								 $velocity_write_comments = '<a href="' . get_comments_link($post->ID) .'">'. $velocity_comments.'</a>';
								} else {
								 $velocity_write_comments =  __('Comments are off for this post.','velocity');
								}

								$postcustoms = velocity_getOptions($post->ID);
								$post_top_width = 140;
								$post_top_height = 140;
								$post_top = '<a href="'.get_permalink($post->ID).'" title="'.get_the_title().'"><div class="posticonbg"><i class="icon-forward posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png"></div></a>';
								$blogimageurl="";
								$blogimageurl = aq_resize(wp_get_attachment_url(get_post_thumbnail_id($post->ID) ),$post_top_width,$post_top_height,true);
								//Post Type related Object to display in the Head Area of the post
									if(isset($postcustoms["velocity_post_type"])){ 
											switch ($postcustoms["velocity_post_type"]) {
												case 'image':
													if($blogimageurl!=""){										
														$post_top = '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'" class="withimage"><div class="posticonbg"><i class="icon-forward posticon"></i></div><img src="'.$blogimageurl.'" alt="" width="70" height="70"></a>';						}
													else{
														$post_top = '<a href="'.get_permalink($post->ID).'" title="'.get_the_title().'"><div class="posticonbg"></div><i class="icon-forward posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png"></a>';	
													}
												break;
												case 'video':
													if($blogimageurl!=""){										
														$post_top = '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'" class="withimage"><div class="posticonbg"><i class="icon-video-1 posticon"></i></div><img src="'.$blogimageurl.'" alt="" width="70" height="70"></a>';						}
													else{
														$post_top = '<a href="'.get_permalink($post->ID).'" title="'.get_the_title().'"><div class="posticonbg"></div><i class="icon-video-1 posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png"></a>';
													}
												break;
												case 'slider':
													if($blogimageurl!=""){										
														$post_top = '<a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'" class="withimage"><div class="posticonbg"><i class="icon-retweet posticon"></i></div><img src="'.$blogimageurl.'" alt="" width="70" height="70"></a>';						}	
													else{
														$post_top = '<a href="'.get_permalink($post->ID).'" title="'.get_the_title().'"><div class="posticonbg"></div><i class="icon-retweet posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png"></a>';
													}
												break;
												default:
													$blogimageurl="";
													$blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($post->ID) ),$post_top_width,$post_top_height,true);
													if(!empty($blogimageurl)){
														$post_top = '<a href="'.get_permalink($post->ID).'" title="'.get_the_title().'"><img src="'.$blogimageurl.'" alt=""></a>';
													}
													else $post_top = '<a href="'.get_permalink($post->ID).'" title="'.get_the_title().'"><div class="posticonbg"></div><i class="icon-forward posticon"></i><img src="'.get_template_directory_uri().'/img/posticonbg.png" width="70" height="70"></a>';
												break;
											}
				//						print_r($post_top);
									}

								$counter++;
								$class="";

								if ($counter%2 != 0) {
				                	 $class="";
								} else {
									 $counter=0;
									 $class="lastcolumn";
								}

                            ?>
                            	<div class="one_half <?php echo $class; ?>">
                            		<div class="homepostholder ">
                            			<div class="homepostimage">
                            				<?php echo $post_top; ?>
	                            			<div class="date">
												<div class="day"><?php echo $velocity_post_time_day; ?></div>
												<div class="month"><?php echo $velocity_post_time_month; ?></div>
											</div>
                            			</div>
                            		
	                            	<div class="homepost">
										<div class="post">
											<div class="postbody">
												<h4><a href="<?php the_permalink(); ?>"><?php echo $post->post_title ?></a></h4>
												<div class="postinfo">
													<div class="categories"><?php echo $velocity_newscatlist; ?></div>
													<div class="comments"><?php echo $velocity_write_comments ?></div>
												</div>
												<?php echo velocity_excerpt(20); ?>
												<div class="clear"></div>
											</div>
										</div>
									</div>
									<div class="clear"></div>
								</div></div>
                            <?php }
							$wp_query = null;
							$wp_query = $velocity_temp;
							wp_reset_query();
							?>

                        <div class="clear"></div></div></div>
                    <?php } ?>
                <?php } ?>
                <!-- Related Posts End -->
            <?php } ?>

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
            <article class="span12">
                <p><?php _e('Oops, we could not find what you were looking for...', 'velocity'); ?></p>
            </article>
        <?php endif; ?>

        <?php if ($velocity_activate_sidebar=="on"){?>
        <!--
        #####################
            -	SIDEBAR	-
        #####################
        -->
        
        <?php if ($velocity_activate_sidebar=="on") { $velocity_sbmod = "style='margin-top: 0px !important;'"; } else { $velocity_sbmod = ""; }?>
        
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
    <div class="row top50"></div>

</section><!-- /container -->

<?php get_footer(); ?>