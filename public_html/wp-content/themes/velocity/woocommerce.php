<?php
	global $wp_query;
    $velocity_content_array = $wp_query->get_queried_object();
	if(isset($velocity_content_array->ID)){
    	$velocity_post_id = $velocity_content_array->ID;
	}
	else $velocity_post_id=0;
	if(function_exists('is_shop') && (is_shop() || is_product() || is_product_category() )) $velocity_post_id = get_option('woocommerce_shop_page_id');
?>
<?php
	$velocity_template_uri = get_template_directory_uri();

	$velocity_pagecustoms = velocity_getOptions($velocity_post_id);

	// Themeoptions
	$velocity_themeoptions = velocity_getThemeOptions();

	//Sidebar
	if (isset($velocity_pagecustoms["velocity_activate_sidebar"])){$velocity_sideo = $velocity_pagecustoms['velocity_sidebar_orientation'];}else{$velocity_sideo = "";}
	if (isset($velocity_pagecustoms["velocity_activate_sidebar"])){$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar"];}else{$velocity_sidebar = "Page Sidebar";}
	
	
	//Pagetitle
	if(isset($velocity_pagecustoms['velocity_activate_page_title'])){ $velocity_headline = "off";} else {$velocity_headline = "on";}
	if(isset($velocity_pagecustoms['velocity_header_title']))$velocity_htitle = $velocity_pagecustoms['velocity_header_title']; else $velocity_htitle=get_the_title();
	$velocity_title_orientation = isset($velocity_themeoptions['velocity_title_orientation']) ? $velocity_themeoptions['velocity_title_orientation'] : "left";
	if($velocity_title_orientation == "left"){
		$velocity_torient = "";
	} else if($velocity_title_orientation == "center"){
		$velocity_torient = "text-align: center;";
	}
	
	
	//Page Slider
	if(isset($velocity_pagecustoms["velocity_activate_slider"]) && $velocity_pagecustoms["velocity_activate_slider"]=="on") {
		$velocity_slider = $velocity_pagecustoms["velocity_header_slider"];
		$velocity_slider_height = velocity_get_revslider_property($velocity_slider,"height");
		if(velocity_get_revslider_property($velocity_slider,"slider_type")=="fullscreen") $velocity_slider_height = 50000;
	}else{
		$velocity_slider ="";
		$velocity_slider_height = "";
	}

	//Sidebar
	if(isset($velocity_pagecustoms["velocity_activate_sidebar"])){
		if (isset($velocity_pagecustoms["velocity_sidebar"])){$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar"];}else{$velocity_sidebar = "Blog Sidebar";}
		$velocity_sidebar_orientation = $velocity_pagecustoms["velocity_sidebar_orientation"];
		$velocity_activate_sidebar="on";
	} else { $velocity_activate_sidebar="off"; }
	
	if(function_exists('is_product') && (is_product() || is_product_category())){
			if (isset($velocity_pagecustoms["velocity_activate_sidebar_woo"])){
				$velocity_sidebar_orientation = $velocity_pagecustoms['velocity_sidebar_orientation_woo'];
				$velocity_activate_sidebar = "on";
			}
			else{
				$velocity_sidebar_orientation = "";
				$velocity_activate_sidebar = "off";
			}
			if (isset($velocity_pagecustoms["velocity_activate_sidebar_woo"])){
				$velocity_sidebar = $velocity_pagecustoms["velocity_sidebar_woo"];}
			else{$velocity_sidebar = "Page Sidebar";}
		} 

	/* Theme Layout */
	$velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
	$velocity_slider_parallax = isset($velocity_themeoptions['velocity_parallax_effects']) && $velocity_themeoptions['velocity_parallax_effects'] != 0 ? true : false;
	if($velocity_themelayout=="Full-Width") $velocity_stretchme_on_mobile = "stretchme_on_mobile";
	else $velocity_stretchme_on_mobile = "";


	get_header();
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

	<?php 
		if(is_shop()){	
			$content = get_page($velocity_post_id); $content = $content->post_content;
			$velocity_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			if($velocity_paged>1){
				$content_filtered = apply_filters("the_content",$content);
				echo $content_filtered;
			}
			if(strpos($content, '[vc_row][vc_column width="1/1"][background_block')===0){
					echo '<style>#firstcontentcontainer {display:none}</style>';
			}
		}
	?>

    <?php woocommerce_content(); ?>
    
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
<div class="row top70"></div>
 </section><!-- /container -->
</section><!-- /firstcontentcontainer -->

<?php get_footer(); ?>