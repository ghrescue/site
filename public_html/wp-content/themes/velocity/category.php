<?php

	//Language Options
	$velocity_categoryname = __('Category', 'velocity');
	$velocity_archivename = __('Archives', 'velocity');
	$velocity_tags = __('Tag', 'velocity');
	$velocity_all = __('All', 'velocity');
	$velocity_readmore =  __('Read More', 'velocity');
	$velocity_in = __('in', 'velocity');
	$velocity_by = __('by', 'velocity');
	$velocity_tagged = __('tagged: ', 'velocity');

	global $wp_query;
    $content_array = $wp_query->get_queried_object();
	if(isset($content_array->ID)){
    	$post_id = $content_array->ID;
	}
	else $post_id=get_option('page_for_posts');
	$template_uri = get_template_directory_uri();

	$pagecustoms = velocity_getOptions($post_id);

	// Themeoptions
	$themeoptions = velocity_getThemeOptions();

	//Blog Style Options
	$velocity_portfolioheightlock = $themeoptions['damojoPortfolio_portfoliolock'];
	$portfoliocategorysidebar = $themeoptions['damojoPortfolio_portfolioarchivesidebar'];
	
	//Blog Style Options
	$nopostinfo = !isset($themeoptions['velocity_blogoverviewpostinfo_date']) && !isset($themeoptions['velocity_blogoverviewpostinfo_author']) && !isset($themeoptions['velocity_blogoverviewpostinfo_category']) && !isset($themeoptions['velocity_blogoverviewpostinfo_comments']) && !isset($themeoptions['velocity_blogoverviewpostinfo_tags']) ? "style='display:none;'" : "";
	$velocity_bloglayout = $themeoptions['velocity_blogoverviewpostlayout'];
	$blogdateclass = isset($themeoptions['velocity_blogoverviewsingledate']) ? "" : "nodate";
	$velocity_archivelayout = $themeoptions['velocity_blogarchivesidebar'];
	

	//Sidebar & Blog Style
	if(isset($pagecustoms["velocity_activate_sidebar"])){
		if (isset($pagecustoms["velocity_sidebar"])){$sidebar = $pagecustoms["velocity_sidebar"];}else{$sidebar = "Blog Sidebar";}
		$sidebar_orientation = $pagecustoms["velocity_sidebar_orientation"];
		$velocity_activate_sidebar="on";
		$blogfullclass = "";
		$bloglayoutclass = "";

		$post_top_width = 850;
		$post_top_height = "";
		if($velocity_bloglayout == "Small Media"){
			$bloglayoutclass = "smallmedia";
			$post_top_width = 710;
			$post_top_height = "";
		}
	}
	else {
		$velocity_activate_sidebar="off";
		$blogfullclass = "fullblog";

		$post_top_width = 1170;
		$post_top_height = "";
		if($velocity_bloglayout == "Small Media"){
			$bloglayoutclass = "smallmedia";
			$post_top_width = 710;
			$post_top_height = "";
		}
	}


	//Pagetitle
	if(isset($pagecustoms['velocity_activate_page_title'])){ $headline = "off";} else {$headline = "on";}
	if(isset($pagecustoms['velocity_header_title']))$htitle = $pagecustoms['velocity_header_title']; else $htitle=get_the_title($post_id);
	if(isset($pagecustoms['velocity_title_orientation']))$title_orientation = $pagecustoms["velocity_title_orientation"]; else $title_orientation = "left";
	if($title_orientation == "left"){
		$torient = "";
	} else if($title_orientation == "center"){
		$torient = "text-align: center;";
	}

	if(have_posts()) $current_cat = get_the_category();

	if(is_category() || is_archive()){
		if(is_category()){
			$catname = single_cat_title("", false);
			$htitle = $velocity_categoryname." - ".$catname;

			if($velocity_archivelayout=="Full-Width"){
				$velocity_activate_sidebar="off";
				$blogfullclass = "fullblog";
				$withsidebarmod = "";
			}else if($velocity_archivelayout=="Sidebar Left"){
				$velocity_activate_sidebar="on";
				$sidebar_orientation ="left";
				$withsidebarmod = "withsidebar";
			}else if($velocity_archivelayout=="Sidebar Right"){
				$velocity_activate_sidebar="on";
				$sidebar_orientation ="right";
				$withsidebarmod = "withsidebar";
			}
			$bloglayoutclass = "";
			//$sidebar = "Blog Sidebar";
		}

		elseif(is_archive()){
			wp_link_pages();
			if(is_tax()){
				
				if(isset($wp_query->query_vars['taxonomy']) && taxonomy_exists($wp_query->query_vars['taxonomy'])) {
					$value    = get_query_var($wp_query->query_vars['taxonomy']);
					if (term_exists($wp_query->query_vars['term'])) {
						$term = get_term_by( 'slug', get_query_var( 'term'  ),$wp_query->query_vars['taxonomy'] );
						$htitle_cat = $term->name;
					}
				}

				$tax_slug = get_post_type();
				$display_span = "fourcol";
				$portfolio_slugs = get_option("velocity_portfolio_slug");
				$portfolio_counter = 0;
				$portfolio_name = get_option("velocity_portfolio_name");
				if(is_array($portfolio_slugs)){
					foreach ( $portfolio_slugs as $slug ){
						if($slug == $tax_slug) break;
						else $portfolio_counter++;
					}
				}
				else $portfolio_name[$portfolio_counter] = "";
				$htitle = "".$portfolio_name[$portfolio_counter]." - ".$htitle_cat;
                
                $velocity_pcat = "category_".$portfolio_slugs[$portfolio_counter];

				if($portfoliocategorysidebar=="Full-Width"){
					$velocity_activate_sidebar="off";
					$blogfullclass = "fullblog";
					$withsidebarmod = "";
				}else if($portfoliocategorysidebar=="Sidebar Left"){
					$velocity_activate_sidebar="on";
					$sidebar_orientation ="left";
					$withsidebarmod = "withsidebar";
				}else if($portfoliocategorysidebar=="Sidebar Right"){
					$velocity_activate_sidebar="on";
					$sidebar_orientation ="right";
					$withsidebarmod = "withsidebar";
				}
				$bloglayoutclass = "";
				//$sidebar = "Blog Sidebar";
                if (isset($themeoptions["damojoPortfolio_portfolioarchivesidebar_select"])){$sidebar = $themeoptions["damojoPortfolio_portfolioarchivesidebar_select"];}else{$sidebar = "Blog Sidebar";}
				
				//Portfolio Posts per Page Default Setting WP
				$posts_per_page = 4;//empty($themeoptions['damojoPortfolio_portfolioarchivenumber']) ? get_option('posts_per_page') : $themeoptions['damojoPortfolio_portfolioarchivenumber'];
			}
			else{
				$htitle = $velocity_archivename." - ".single_month_title(' ', false);

				if($velocity_archivelayout=="Full-Width"){
					$velocity_activate_sidebar="off";
					$blogfullclass = "fullblog";
					$withsidebarmod = "";
				}else if($velocity_archivelayout=="Sidebar Left"){
					$velocity_activate_sidebar="on";
					$sidebar_orientation ="left";
					$withsidebarmod = "withsidebar";
				}else if($velocity_archivelayout=="Sidebar Right"){
					$velocity_activate_sidebar="on";
					$sidebar_orientation ="right";
					$withsidebarmod = "withsidebar";
				}
				$bloglayoutclass = "";
				$sidebar = "Blog Sidebar";
			}
		}
	}

	/* Theme Layout */
	$velocity_slider="";
	$velocity_themelayout = $themeoptions['velocity_themelayout'];


	if(is_tag()) $htitle = $velocity_tags." - ".single_tag_title(' ', false);

	get_header();
?>

<!-- Main Container -->
<div id="firstcontentcontainer" class="container">

	
<!-- Body -->
<div class="row">

	<?php
    //If this is the Blog
    if(!is_tax()){?>

        <!-- Content -->
        <?php if ($velocity_activate_sidebar=="on" && $sidebar_orientation =="right") { ?>
        <div class="span9 left">
        <div class="pagewrapright">
        <?php } else if ($velocity_activate_sidebar=="on" && $sidebar_orientation =="left") { ?>
        <div class="span9 right">
        <div class="pagewrapleft">
        <?php } else { ?>
        <div class="span12 <?php echo $blogfullclass;?>">
        <?php } ?>

        <!--
        #################################
            -	BLOG	-
        #################################
        -->

		<?php 
		
		if(have_posts()) :
        while(have_posts()) : the_post();

			//Post Time & Info
			$post_time_day = get_post_time('j', true);
			$post_time_month = date_i18n('M', strtotime($post->post_date_gmt));
			$post_time_year = get_post_time('Y', true);
			$post_time_daymonthyear = date_i18n(get_option('date_format'), strtotime($post->post_date_gmt));

			$postcustoms = velocity_getOptions($post->ID);
			$post_top="";

			$blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($post->ID) ),$post_top_width,$post_top_height,true);
			//Post Type related Object to display in the Head Area of the post
			if(isset($postcustoms["velocity_post_type"]))
			switch ($postcustoms["velocity_post_type"]) {
				case 'image':
					$blogimageurl="";
					$blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($post->ID) ),$post_top_width,$post_top_height,true);
					if($blogimageurl!=""){
						$post_top = '<a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.$blogimageurl.'" alt=""></a>';
					}
				break;
				case 'video':
					$video_width = $postcustoms["velocity_video_width"];
					$video_height = $postcustoms["velocity_video_height"];

					if($video_width>$post_top_width)
						$video_width_ratio = $video_width/$post_top_width;
					else
						$video_width_ratio = $post_top_width/$video_width;

					$video_height = round($video_height*$video_width_ratio);
					$video_width = $post_top_width;

					if($postcustoms["velocity_video_type"]=="youtube"){
						$post_top = '<div class="scalevid"><iframe src="http://www.youtube.com/embed/'.$postcustoms["velocity_youtube_id"].'?hd=1&amp;wmode=opaque&amp;autohide=1&amp;showinfo=0" width="'.$postcustoms["velocity_video_width"].'" height="'.$postcustoms["velocity_video_height"].'" style="border:0"></iframe></div>';
					}
					elseif ($postcustoms["velocity_video_type"]=="vimeo") {
						$post_top = '<div class="scalevid"><iframe src="http://player.vimeo.com/video/'.$postcustoms["velocity_vimeo_id"].'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="'.$postcustoms["velocity_video_width"].'" height="'.$postcustoms["velocity_video_height"].'" style="border:0"></iframe></div>';
					}
				break;

				case 'slider':
						$velocity_slider = $postcustoms["velocity_slider"];
						$post_top = do_shortcode('[rev_slider '.$velocity_slider.']');
				break;

				default:
					$blogimageurl="";
					$blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($post->ID) ),$post_top_width,$post_top_height,true);
					if($blogimageurl!=""){
						$post_top = '<a href="'.get_permalink().'" title="'.get_the_title().'"><img src="'.$blogimageurl.'" alt=""></a>';
					}
					else {
				$post_top = ""; }
				break;
			}

			$entrycategory = "";
			if(is_tax()){
				$entrycategory = get_the_term_list( '', "category_".$tax_slug, '', ', ', '' );
			} else {
				foreach((get_the_category()) as $category) {
					$entrycategory .= ', <a href="'.get_category_link($category->term_id ).'">'.$category->cat_name.'</a>';
				}
				$entrycategory = substr($entrycategory, 2);
			} ?>

            <?php if($velocity_bloglayout == "Small Media" && $post_top==""){
				$bloglayoutclass = "nosmallmedia";
			} ?>
			
            <?php  if($velocity_bloglayout == "Small Media"){ $bloglayoutclass = "smallmedia"; } ?>

            <div class="blogpost <?php echo $bloglayoutclass;?>" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if($velocity_bloglayout == "Large Media"){ ?>
					<div class="date">
						<div class="day"><?php echo $post_time_day;?></div>
						<div class="month"><?php echo $post_time_month;?></div>
					</div>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="postinfo" <?php echo $nopostinfo; ?>>
						<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_date'])){ ?><div class="time"><?php echo $post_time_daymonthyear; ?></div><?php } ?>
						<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_author'])){ ?><div class="author"><?php echo $velocity_by ?> <?php the_author_posts_link(); ?></div><?php } ?>
						<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_category'])){ ?><div class="categories"><?php echo $velocity_in ?> <?php echo $entrycategory; ?></div><?php } ?>
						<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_comments']) && comments_open()){ ?><div class="comments"><?php comments_popup_link(__('no Comments', 'velocity'), __('one Comment', 'velocity'), __( '% Comments', 'velocity')); ?></div><?php } ?>
						<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_tags']) && has_tag()){ ?><div class="tags"><?php echo $velocity_tagged ?> <?php echo the_tags('', ', ', ''); ?></div><?php } ?>
					</div>
				<?php } ?>

                <div class="post">

                    <?php if($velocity_bloglayout == "Small Media"){ ?>
                        <?php if ($post_top!=""){?>
                            <div class="postmedia">
                                <?php echo $post_top; ?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <div class="postbody" <?php  echo $velocity_bloglayout == "Small Media" && empty($post_top) ? 'style="width:100%"' : "";  ?>>

						<?php if($velocity_bloglayout == "Small Media"){ ?>
							<div class="date">
								<div class="day"><?php echo $post_time_day;?></div>
								<div class="month"><?php echo $post_time_month;?></div>
							</div>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="postinfo" <?php echo $nopostinfo; ?>>
								<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_date'])){ ?><div class="time"><?php echo $post_time_daymonthyear; ?></div><?php } ?>
								<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_author'])){ ?><div class="author"><?php echo $velocity_by ?> <?php the_author_posts_link(); ?></div><?php } ?>
								<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_category'])){ ?><div class="categories"><?php echo $velocity_in ?> <?php echo $entrycategory; ?></div><?php } ?>
								<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_comments']) && comments_open()){ ?><div class="comments"><?php comments_popup_link(__('no Comments', 'velocity'), __('one Comment', 'velocity'), __( '% Comments', 'velocity')); ?></div><?php } ?>
								<?php if (isset($themeoptions['velocity_blogoverviewpostinfo_tags']) && has_tag()){ ?><div class="tags"><?php echo $velocity_tagged ?> <?php echo the_tags('', ', ', ''); ?></div><?php } ?>
							</div>
						<?php } ?>

                        <?php if($velocity_bloglayout == "Large Media"){ ?>
                            <?php if ($post_top!=""){?>
                            	<?php if ($postcustoms["velocity_post_type"]!='slider') {?>
                                <div class="postmedia">
                                    <?php echo $post_top; ?>
                                </div>
                            	<?php } else {?>
                            		  <div class="postmedia-slide">
                                    <?php echo $post_top; ?>
                                </div>
                            	<?php } ?>
                            <?php } ?>
                        <?php } ?>

                        <div class="posttext"><?php the_excerpt(); ?></div>
						<div class="readmore"><a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php echo $velocity_readmore ?></a></div>

                    </div>

                    <div class="postdivider"></div>
                </div>
            </div>


        <!-- Loop End -->
        <?php endwhile; ?>

        <!-- Content End -->
        <?php if ($velocity_activate_sidebar=="on" && $sidebar_orientation =="right") { ?>
        </div>
            <?php velocity_pagination();  ?>
        </div>
        <?php } else if ($velocity_activate_sidebar=="on" && $sidebar_orientation =="left") { ?>
        </div>
            <?php velocity_pagination(); ?>
        </div>
        <?php } else { ?>
            <?php  velocity_pagination(); ?>
        </div>
        <?php } ?>

        <?php else : ?>
            <div>
				<h4 style="text-align:center; margin-bottom: 500px;"><?php _e('Oops, we could not find what you were looking for...', 'velocity'); ?></h4>
            </div>
        <?php endif; ?>

        <?php //If this is the portfolio
    	} else if(is_tax()){ ?>

        	<!-- Content -->
			<?php if ($velocity_activate_sidebar=="on" && $sidebar_orientation =="right") { ?>
            <div class="span9 left">
            <div class="pagewrapright">
            <?php } else if ($velocity_activate_sidebar=="on" && $sidebar_orientation =="left") { ?>
            <div class="span9 right">
            <div class="pagewrapleft">
            <?php } else { ?>
            <div class="span12 <?php echo $blogfullclass;?>">
            <?php } ?>

            <!-- Portfolio -->
            <div class="row <?php echo $display_span; ?> portfoliowrap">

                <!-- Portfolio Items -->
                <div class="portfolio <?php echo $withsidebarmod ?>">

                    <?php if ($wp_query->have_posts()) : ?>
                    <?php while ( $wp_query->have_posts() ) : $wp_query->the_post();

                        $velocity_postcustoms = velocity_getOptions($post->ID);

				//Post Type
				$velocity_blogimageurl_pp = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
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

				$velocity_blogimageurl = aq_resize(wp_get_attachment_url( get_post_thumbnail_id($post->ID)),400,$velocity_portfolioheightlock,true);

				if($velocity_blogimageurl==""){
					$velocity_blogimageurl = $velocity_template_uri.'/img/demo/400x300.jpg';
				}
                
                
				$velocity_categorylinks = get_the_term_list( $post->ID, $velocity_pcat, '', ', ', '' );
				if(empty($velocity_categorylinks)) $velocity_categorylinks = "";
				$velocity_categories = get_the_terms($post->ID,$velocity_pcat);
				$velocity_categorylist="";
				if(is_array($velocity_categories)){
					foreach ($velocity_categories as $velocity_category) {
						$velocity_categorylist.= $velocity_category->slug." ";
						$velocity_categories2[] = $velocity_category->slug;
					}
				}
                    
                    
                    if(!empty($velocity_postcustoms["velocity_launch_project"]) && !empty($velocity_postcustoms["velocity_launch_project_type"])  && $velocity_postcustoms["velocity_launch_project_type"]=="external")
					$thelink = $velocity_postcustoms["velocity_launch_project"];
				else $thelink = get_permalink();

				?>

                    <div class="entry <?php //echo $velocity_categorylist; ?>">
						<div class="holderwrap">
							<div class="mediaholder">
								<img src="<?php echo $velocity_blogimageurl; ?>" alt="">
								<div class="cover"></div>
								<?php if($velocity_p_linkactive=="On"){ ?>  <a href="<?php echo $thelink; ?>"><div class="link icon-forward '.$notalonemod.'"></div></a> <?php } ?>
								<?php if($velocity_p_zoomactive=="On"){ ?>  <a title="<?php the_title(); ?>" href="<?php echo $velocity_blogimageurl_pp; ?>" rel="imagegroup" data-rel="imagegroup" class="fancybox"><div class=" show icon-search <?php echo $velocity_notalonemod; ?>"></div></a> <?php } ?>
					 		</div>
							<div class="foliotextholder">
								<div class="foliotextwrapper">
									<h5 class="itemtitle"><a href="<?php echo $thelink; ?>"><?php the_title(); ?></a></h5>
									<?php if($velocity_item_categories=="On" && !is_wp_error($velocity_categorylinks)){ ?> <span class="itemcategories"><?php echo $velocity_categorylinks; ?></span><?php } ?>
								</div>
							  <div class="clear"></div>
							</div>
							<div class="folio_underlay">
							</div>
						</div>
					</div>

                    <?php endwhile; endif; //have_posts ?>

                </div>
            </div>

            <!-- Content End -->
            <?php if ($velocity_activate_sidebar=="on" && $sidebar_orientation =="right") { ?>
            </div>
                <?php if(function_exists('velocity_spec_pagination')){ echo'<div class="row" style="margin-top:40px;"></div>'; velocity_spec_pagination($wp_query); }else{ paginate_links(); } ?>
            </div>
            <?php } else if ($velocity_activate_sidebar=="on" && $sidebar_orientation =="left") { ?>
            </div>
                <?php if(function_exists('velocity_spec_pagination')){ echo'<div class="row" style="margin-top:40px;"></div>'; velocity_spec_pagination($wp_query); }else{ paginate_links(); } ?>
            </div>
            <?php } else { ?>
                <?php if(function_exists('velocity_spec_pagination')){ echo'<div class="row" style="margin-top:40px;"></div>'; velocity_spec_pagination($wp_query); }else{ paginate_links(); } ?>
            </div>
            <?php } ?>


        <?php } ?>


        <?php if ($velocity_activate_sidebar=="on"){?>
        <!--
        #####################
            -	SIDEBAR	-
        #####################
        -->
        
       <?php 
        if ($velocity_activate_sidebar=="on" && is_tax()) { $sbmod = "style='margin-top: 0px !important;'"; } else { $sbmod = ""; }
        ?>
        
        <div class="span3 right sidebar" <?php echo $sbmod ?>>
			<div class="row">

                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($sidebar) ) : ?>
                    <div class="span3 widget">
                        <div class="footertitle"><h4>Sidebar Widget Area</h4></div>
                        <div class="widgetclass">
                        Please configure this Widget Area in the Admin Panel under Appearance -> Widgets
                        </div><div class="clear"></div>
                    </div>
                <?php endif; ?>

        	</div>
        </div>
        <!-- /Sidebar -->
        <?php } ?>

    </div><div class="clear"></div>
    <!-- /Body -->

	<!-- Bottom Spacing -->
    <div class="row top50"></div>

</div><!-- /container -->

<?php get_footer(); ?>