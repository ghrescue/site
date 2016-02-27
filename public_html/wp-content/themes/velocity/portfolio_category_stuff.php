<?php
/*
Template Name: Portfolio per Category
*/
?>
<?php
	//Language Options
	$velocity_all = __('All', 'velocity');

	global $wp_query;
    $velocity_content_array = $wp_query->get_queried_object();
	if(isset($velocity_content_array->ID)){
    	$velocity_post_id = $velocity_content_array->ID;
	}
	else $velocity_post_id=0;
	$velocity_template_uri = get_template_directory_uri();

	//Page Options
	$velocity_pagecustoms = velocity_getOptions($velocity_post_id);

	// Themeoptions
	$velocity_themeoptions = velocity_getThemeOptions();

	//Pagetitle
	if(isset($velocity_pagecustoms['velocity_activate_page_title'])){ $velocity_headline = "off";} else {$velocity_headline = "on";}
	if(isset($velocity_pagecustoms['velocity_header_title']))$velocity_htitle = $velocity_pagecustoms['velocity_header_title']; else $velocity_htitle=get_the_title($velocity_post_id);
	if(isset($velocity_pagecustoms["velocity_title_orientation"])) $velocity_title_orientation = $velocity_pagecustoms["velocity_title_orientation"]; else $velocity_title_orientation = "left";
	if($velocity_title_orientation == "left"){
		$velocity_torient = "";
	} else if($velocity_title_orientation == "center"){
		$velocity_torient = "text-align: center;";
	}

	//Sidebar
	if (isset($velocity_pagecustoms["velocity_activate_sidebar"])){$velocity_sideo = $velocity_pagecustoms['velocity_sidebar_orientation'];}else{$velocity_sideo = "";}
	if (isset($velocity_pagecustoms["velocity_activate_sidebar"])){$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar"];}else{$velocity_sidebar = "Page Sidebar";}
	if(isset($velocity_pagecustoms["velocity_activate_sidebar"])){
		if (isset($velocity_pagecustoms["velocity_sidebar"])){$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar"];}else{$velocity_sidebar = "Page Sidebar";}
		$velocity_sidebar_orientation = $velocity_pagecustoms["velocity_sidebar_orientation"];
		$velocity_activate_sidebar="on";
		$velocity_withsidebarmod = "withsidebar";
	} else { $velocity_activate_sidebar="off"; $velocity_withsidebarmod = ""; }

	/* Theme Layout */
	$velocity_slider="";
	$velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
	$velocity_slider_parallax = isset($velocity_themeoptions['velocity_parallax_effects']) ? true : false;
	if($velocity_themelayout=="Full-Width") $velocity_stretchme_on_mobile = "stretchme_on_mobile";
	else $velocity_stretchme_on_mobile = "";


	//Portfolio
	if(isset($velocity_pagecustoms["velocity_portfolio_display_type"])) $velocity_portfolio_display_type = $velocity_pagecustoms["velocity_portfolio_display_type"];
	else $velocity_portfolio_display_type = 4;
	if(isset($velocity_pagecustoms["velocity_portfolio_items"])) $velocity_portfolio_items = $velocity_pagecustoms["velocity_portfolio_items"];
	else $velocity_portfolio_items = 15;

	if(!empty($velocity_pagecustoms["velocity_portfolio_lock"])){
		$velocity_portfolioheightlock = $velocity_pagecustoms["velocity_portfolio_lock"];
	} else {
		$velocity_portfolioheightlock = !empty($velocity_themeoptions["velocity_portfoliolock"]) ? $velocity_themeoptions["velocity_portfoliolock"] : "";
	}

	$display_span = "";

	switch($velocity_portfolio_display_type){
		case "5":
			$velocity_display_span = "fivecol";
		break;
		case "4":
			$velocity_display_span = "fourcol";
		break;
		case "3":
			$velocity_display_span = "threecol";
		break;
	}
	

	$class = "";

	$velocity_ptype = $velocity_pagecustoms['velocity_portfolio'];
	$velocity_pcat = "category_".$velocity_ptype;
	$velocity_pcat_array = get_option('velocity_portfolio_slug');
	$velocity_tax_terms = get_terms($velocity_pcat);

	get_header();
?>

<!-- Main Container -->
<section id="firstcontentcontainer" style="position:relative" class="<?php echo $velocity_stretchme_on_mobile; ?>">
 <section class="container">

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
    <section class="span12">
    <?php } ?>
    
     <?php if(have_posts()) :
		while(have_posts()) : the_post();
			$the_content = get_the_content();
			if(strpos($the_content, '[vc_row][vc_column width="1/1"][background_block')===0){
				echo '<style>#firstcontentcontainer {display:none}</style>';
			}
			$the_content = apply_filters("the_content",$the_content);
			echo str_replace("wpb_widgetised_column","wpb_widgetised_column sidebar",$the_content);
		endwhile;  //have_posts
		else : ?>
		<div>
			<p><?php _e('Oops, we could not find what you were looking for...', 'velocity'); ?></p>
		</div>
	<?php endif; ?>

    <!-- Portfolio -->
    <!--section class="row <?php echo $velocity_display_span; ?> portfoliowrap"-->
	    <?php if(!isset($velocity_pagecustoms["velocity_activate_portfolio_category"])){ ?>
        <!-- Category Filter -->
		<?php
				if(is_array($velocity_tax_terms)){
					foreach ( $velocity_tax_terms as $velocity_tax_term ) {
						echo '<h2>'.$velocity_tax_term->name.'</h2>';
		?>

			       <!-- Portfolio -->
				   <div class="row <?php echo $display_span; ?> portfoliowrap foliooverview" >
			        <!-- Portfolio Items -->
			        <div class="portfolio <?php echo $velocity_display_span; ?> <?php echo $velocity_withsidebarmod; ?>">
			
			            <?php
						$velocity_args=array(
							'post_type' => $velocity_ptype,
							'tax_query' => array(
								array(
									'taxonomy' => $velocity_pcat,
									'field'    => 'slug',
									'terms'    => $velocity_tax_term->slug
								)
							)
			              );

			
						$velocity_temp = $wp_query;
						$wp_query = null;
						$wp_query = new WP_Query();
						$wp_query->query($velocity_args);
			
						$velocity_categories2 = array();
			
						?>
			
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
								
						$thetarget = "";
							
						if(!empty($velocity_postcustoms["velocity_launch_project"]) && !empty($velocity_postcustoms["velocity_launch_project_type"]) && ($velocity_postcustoms["velocity_launch_project_type"]=="external" || $velocity_postcustoms["velocity_launch_project_type"]=="internal") ){
									 if($velocity_postcustoms["velocity_launch_project_type"]=="external")
									 	$thetarget = 'target="_blank"';
									 else
									 	$thetarget = '';
									 $thelink = $velocity_postcustoms["velocity_launch_project"];
								 }
								 else {
									$thelink = get_permalink();
									$thetarget = "";
								} 
			
						?>
			
							<div class="entry <?php echo $velocity_categorylist; ?>">
									<div class="holderwrap">
										<div class="mediaholder">
											<img src="<?php echo $velocity_blogimageurl; ?>" alt="">
											<div class="cover"></div>
											<?php if($velocity_p_linkactive=="On"){ ?>  <a <?php echo $thetarget; ?> href="<?php echo $thelink; ?>"><div class="link icon-forward '.$notalonemod.'"></div></a> <?php } ?>
											<?php if($velocity_p_zoomactive=="On"){ ?>  <a title="<?php the_title(); ?>" href="<?php echo $velocity_blogimageurl_pp; ?>" rel="imagegroup" data-rel="imagegroup" class="fancybox"><div class=" show icon-search <?php echo $velocity_notalonemod; ?>"></div></a> <?php } ?>
								 		</div>
										<div class="foliotextholder">
											<div class="foliotextwrapper">
												<h5 class="itemtitle"><a <?php echo $thetarget; ?> href="<?php echo $thelink; ?>"><?php the_title(); ?></a></h5>
												<?php if($velocity_item_categories=="On" && !is_wp_error($velocity_categorylinks)){ ?> <span class="itemcategories"><?php echo $velocity_categorylinks; ?></span><?php } ?>
											</div>
										  <div class="clear"></div>
										</div>
										<div class="folio_underlay">
										</div>
									</div>
								</div>
			            <?php endwhile; endif; //have_posts ?>
			            </div> </div>
			        <!--/article>
			    </section-->
	<?php } } }?>
    <!-- Content End -->
	<?php if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="right") { ?>
	    </section>
	        <?php if(function_exists('velocity_spec_pagination')){ echo'<div class="row" style="margin-top:30px;"></div>'; velocity_spec_pagination($wp_query); }else{ paginate_links(); } ?>
	    </section>
	    <?php } else if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="left") { ?>
	    </section>
	    	<div class="clear"></div>
	        <?php if(function_exists('velocity_spec_pagination')){ echo'<div class="row" style="margin-top:30px;"></div>'; velocity_spec_pagination($wp_query); }else{ paginate_links(); } ?>
	    </section>
	    <?php } else { ?>
	    <div class="clear"></div>
	        <?php if(function_exists('velocity_spec_pagination')){ echo'<div class="row" style="margin-top:30px;"></div>'; velocity_spec_pagination($wp_query); }else{ paginate_links(); } ?>
	    </section>
    <?php } ?>

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
	<style> .portfoliofilter #<?php echo implode(" ,.portfoliofilter #",$velocity_categories2); ?> {display:inline !important;}</style>
</section><div class="clear"></div>
<!-- /Body -->

<!-- Bottom Spacing -->
<div class="row top40"></div>
 </section><!-- /container -->
</section><!-- /firstcontentcontainer -->

<?php get_footer(); ?>