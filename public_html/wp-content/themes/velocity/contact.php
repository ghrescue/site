<?php
/*
Template Name: Contact
*/
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

	//Sidebar
	if (isset($velocity_pagecustoms["velocity_activate_sidebar"])){$velocity_sideo = $velocity_pagecustoms['velocity_sidebar_orientation'];}else{$velocity_sideo = "";}
	if (isset($velocity_pagecustoms["velocity_activate_sidebar"])){$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar"];}else{$velocity_sidebar = "Page Sidebar";}

	//Pagetitle
	if(isset($velocity_pagecustoms['velocity_activate_page_title'])){ $velocity_headline = "off";} else {$velocity_headline = "on";}
	if(isset($velocity_pagecustoms['velocity_header_title']))$velocity_htitle = $velocity_pagecustoms['velocity_header_title']; else $velocity_htitle=get_the_title($velocity_post_id);
	if(isset($velocity_pagecustoms['velocity_title_orientation']))$velocity_title_orientation = $velocity_pagecustoms["velocity_title_orientation"]; else $velocity_title_orientation = "left";
	if($velocity_title_orientation == "left"){
		$velocity_torient = "";
	} else if($velocity_title_orientation == "center"){
		$velocity_torient = "text-align: center;";
	}

	//Sidebar
	if(isset($velocity_pagecustoms["velocity_activate_sidebar"])){
		if (isset($velocity_pagecustoms["velocity_sidebar"])){$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar"];}else{$velocity_sidebar = "Blog Sidebar";}
		$velocity_sidebar_orientation = $velocity_pagecustoms["velocity_sidebar_orientation"];
		$velocity_activate_sidebar="on";
	} else { $velocity_activate_sidebar="off"; }

	//Google Data
	if(isset($velocity_pagecustoms['velocity_gmapadress']))$velocity_gmapaddress = $velocity_pagecustoms["velocity_gmapadress"]; else $velocity_gmapaddress = "";
	$velocity_gmapzoom = empty($velocity_pagecustoms["velocity_gmapzoom"]) ? 14 : $velocity_pagecustoms["velocity_gmapzoom"];
	$velocity_gmapinfo = empty($velocity_pagecustoms["velocity_gmapinfo"]) ? "" : $velocity_pagecustoms["velocity_gmapinfo"];
	
	/* Theme Layout */
	$velocity_slider="";
	$velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
	$velocity_slider_parallax = isset($velocity_themeoptions['velocity_parallax_effects']) ? true : false;
	if($velocity_themelayout=="Full-Width") $velocity_stretchme_on_mobile = "stretchme_on_mobile";
	else $velocity_stretchme_on_mobile = "";


	get_header();
?>

<?php if($velocity_gmapaddress!=""){ ?>
		<article class="gmap"><div id="gmap_inner"></div></article>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="<?php echo $velocity_template_uri;?>/js/jquery.gmap.js"></script>
		<script>
			  jQuery(window).load(function(){
				  //set google map with marker
				  jQuery("#gmap_inner").gMap({
					  scrollwheel: false,
					  markers: [{
						  address: '<?php echo $velocity_gmapaddress; ?>'<?php if($velocity_gmapinfo!="") {?>,
						  html: '<?php echo $velocity_gmapinfo; ?>' <?php } ?>
						}],
					  zoom: <?php echo $velocity_gmapzoom;?>
				  });
			  });
		</script>
		<article class="gmapheight"></article>
	<?php } ?>

<!-- Main Container -->
<section id="firstcontentcontainer" style="position:relative" class="<?php echo $velocity_stretchme_on_mobile; ?>">
 <section class="container">
	<?php if($velocity_themelayout!="Full-Width"){ ?>
		<?php if ($velocity_headline!="off"){ ?>
			<!-- Page Title -->
			<section class="pagetitlewrap boxed">
				<div class="row pagetitle">
					<h1 style="<?php echo $velocity_torient;?>"><?php echo $velocity_htitle;?></h1>
					<div class="breadcrumbwrap"><?php velocity_breadcrumb(); ?></div>
				</div>
			</section>
			<?php if($velocity_slider==""){ ?><div class="row top50"></div><?php } ?>
		<?php } else { ?>
			<div class="row notitleboxedtop"></div>
		<?php } ?>
	<?php } ?>

<!-- Body -->
<section class="row">

	<!-- Google Map -->
	


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

	<!-- Content End -->
	<?php if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="right") { ?>
    </section>
        <?php if(function_exists('velocity_pagination')){ velocity_pagination(); }else{ paginate_links(); } ?>
    </section>
    <?php } else if ($velocity_activate_sidebar=="on" && $velocity_sidebar_orientation =="left") { ?>
    </section>
        <?php if(function_exists('velocity_pagination')){ velocity_pagination(); }else{ paginate_links(); } ?>
    </section>
    <?php } else { ?>
        <?php if(function_exists('velocity_pagination')){ velocity_pagination(); }else{ paginate_links(); } ?>
    </section>
    <?php } ?>

    <?php if ($velocity_activate_sidebar=="on"){?>
    <!--
    #####################
        -	SIDEBAR	-
    #####################
    -->
    <aside class="span3 right sidebar" style="margin-top: 0px !important;">
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
<div class="row top80"></div>
 </section><!-- /container -->
</section><!-- /firstcontentcontainer -->

<?php get_footer(); ?>