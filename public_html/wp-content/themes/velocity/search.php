<?php
/*
Template Name: Search
*/
?>
<?php

	//Language Options
	$velocity_categoryname = __('<strong>Category</strong>', 'velocity');
	$velocity_archivename = __('<strong>Archives</strong>', 'velocity');
	$velocity_tags = __('<strong>Tag</strong>', 'velocity');
	$velocity_all = __('All', 'velocity');
	$velocity_searchresults = __('search results for', 'velocity');

	global $wp_query;
    $velocity_content_array = $wp_query->get_queried_object();
	if(isset($velocity_content_array->ID)){
    	$velocity_post_id = $velocity_content_array->ID;
	}
	else $velocity_post_id=0;
	$velocity_template_uri = get_template_directory_uri();

	//Sidebar & Blog Style
	$velocity_activate_sidebar="off";
	$velocity_blogdateclass = "nodate";
	$velocity_blogfullclass = "fullblog";
	
	$velocity_allsearch = new WP_Query("s=$s&showposts=-1");
	$velocity_searchcount = $velocity_allsearch->post_count;
	wp_reset_query();
	
	// Themeoptions
	$velocity_themeoptions = velocity_getThemeOptions();
	$velocity_searchresultsnum = $velocity_themeoptions['velocity_searchresultsnumber'];
	
	$velocity_headline = "on";
	
	/* Theme Layout */
	$velocity_slider="";
	$velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
	$velocity_slider_parallax = isset($velocity_themeoptions['velocity_parallax_effects']) ? true : false;
	if($velocity_themelayout=="Full-Width") $velocity_stretchme_on_mobile = "stretchme_on_mobile";
	else $velocity_stretchme_on_mobile = "";

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

<!-- Main Container -->
<section id="firstcontentcontainer" class="container <?php echo $velocity_stretchme_on_mobile; ?>">

	<?php if($velocity_themelayout!="Full-Width"){ ?>
		<?php if ($velocity_headline!="off"){ ?>
			<!-- Page Title -->
			<section class="pagetitlewrap boxed">
				<div class="row pagetitle">
					<h1 style="<?php echo $velocity_torient;?>"><?php echo "<strong>".$velocity_searchcount."</strong> ".$velocity_searchresults ?> "<?php the_search_query(); ?></h1>
					<div class="breadcrumbwrap"><?php velocity_breadcrumb(); ?></div>
				</div>
			</section>
			<?php if($velocity_slider==""){ ?><div class="row top40"></div><?php } ?>
		<?php } else { ?>
			<div class="row notitleboxedtop"></div>
		<?php } ?>
	<?php } ?>
	
<!-- Body -->
<section class="row">
    
        <section class="span12 <?php echo $velocity_blogfullclass;?>">

        <!--
        #################################
            -	SEARCH RESULTS 	-
        #################################
        -->
        
		<?php 
        $velocity_paged =
            ( get_query_var('paged') && get_query_var('paged') > 1 )
            ? get_query_var('paged')
            : 1;
        $velocity_args = array(
            //'posts_per_page' => $velocity_searchresultsnum,
            'paged' => $velocity_paged
        );
        $velocity_args =
            ( $wp_query->query && !empty( $wp_query->query ) )
            ? array_merge( $wp_query->query , $velocity_args )
            : $velocity_args;
        query_posts( $velocity_args );
        ?>
        
		<?php if(have_posts()) :
        while(have_posts()) : the_post();
		
			if($velocity_searchcount>0){
		
			//Post Time & Info
			$velocity_post_time_day = get_post_time('j', true);
			$velocity_post_time_month = date_i18n('M', strtotime($post->post_date_gmt));
			$velocity_post_time_year = get_post_time('Y', true);
			$velocity_post_time_daymonthyear = date_i18n(get_option('date_format'), strtotime($post->post_date_gmt));

			$velocity_excerpt_content = velocity_excerpt(50);
			if($velocity_excerpt_content=="") {
				$velocity_excerpt_content = do_shortcode(get_the_content());
				$velocity_excerpt_content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
				$velocity_excerpt_content = substr(strip_tags($velocity_excerpt_content), 0, 250);
				if(strlen($velocity_excerpt_content)>200) $velocity_excerpt_content .= "...";
			}
			?>
		
            <article class="blogpost <?php echo $velocity_blogdateclass ?>">
                <div class="post">
                    
                    <div class="postbody">
                        
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="posttext" style="margin-bottom: 0px;"><?php echo $velocity_excerpt_content; ?></div>
                        
                    </div>
                    
                    <div class="postdivider" style="margin-top: 21px; margin-bottom: 20px;"></div>
                </div>
            </article>
            
        <!-- Loop End -->
        <?php } endwhile; ?>
		
        <!-- Content End -->

        <?php if(function_exists('velocity_spec_pagination')){ velocity_spec_pagination($wp_query); }else{ paginate_links(); } ?>
        </section>
      
        <?php else : ?>
            <article>
                <h4 style="text-align:center;"><?php _e('Oops, we could not find what you were looking for...', 'velocity'); ?></h4>
            </article>
</section><div class="clear"></div>
        <?php endif; ?>

    
    </section><div class="clear"></div>
    <!-- /Body -->

	<!-- Bottom Spacing -->
    <div class="row top80"></div>

</section><!-- /container -->

<?php get_footer(); ?>