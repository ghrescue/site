<?php
	// Themeoptions
	$velocity_themeoptions = velocity_getThemeOptions();
	
	/* Default Background */
	$velocity_default_bgimage = empty($velocity_themeoptions['velocity_img_bgdefault']) ? "" : $velocity_themeoptions['velocity_img_bgdefault'];
	/* Theme Layout */
	$velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
	if($velocity_themelayout=="Full-Width"){ $velocity_wideclass = "wide"; } else { $velocity_wideclass = ""; }
	
	$velocity_bgimage = "";
	$velocity_post_id = get_the_ID();
	if (isset($velocity_post_id)){
		$velocity_pagecustoms = velocity_getOptions($velocity_post_id);
		//Custom Background
		if(isset($velocity_pagecustoms["velocity_custom_background"])){
			$velocity_image = wp_get_attachment_image_src($velocity_pagecustoms["velocity_custom_background"], 'full');	$velocity_image = $velocity_image[0];
			$velocity_themeoptions['velocity_img_bgdefault'] = $velocity_image;
			$velocity_themeoptions['velocity_img_bgtype'] = $velocity_pagecustoms["velocity_custom_background_type"];
		}
	}
	
	 $velocity_backstretch = $velocity_themeoptions['velocity_img_bgdefault'];
	 $velocity_themelayout = $velocity_themeoptions['velocity_themelayout'];
	 $velocity_bgtype = $velocity_themeoptions['velocity_img_bgtype'];
	 
	 //Cutomizer Start
	 $getpreview = isset($_COOKIE['velocitypreview']) && $_COOKIE['velocitypreview']!='' ? $_COOKIE['velocitypreview'] : "velocity1.css";
	 
	 switch ($getpreview) {
		 case "velocity_blue_widedark.css":
			   $velocity_wideclass = "wide";		   	
		 break;
		 
		  case "velocity_blue_boxeddark.css":
			   $velocity_wideclass = "";		     
		 break;
		 
		  case "velocity_brown.css":
		   	   $velocity_wideclass = "";
		 break;
		 
		  case "velocity_green.css":
		   	  $velocity_wideclass = "";  
		   	  $velocity_backstretch = "http://themepunch.com/velocity/wp-content/uploads/2014/01/bg_green2.jpg";	 
		   	  $velocity_themelayout = "Boxed";
		   	  $velocity_bgtype = "full";
		 break;
		 
		  case "velocity_orange.css":
		   	  $velocity_wideclass = "wide";
		 break;
		 
		  case "velocity_smoked.css":
		   	$velocity_wideclass = "wide";
		 break;
		 
		 
		 
		 default:
		 break;
	 }	 	 
	 //Cutomizer END	
?>
</section>
<?php if(isset($velocity_themeoptions["velocity_footerwidgetsactive"]) || isset($velocity_themeoptions["velocity_subfooterwidgetsactive"])) : ?>
<!-- Footer -->  
<footer>

		<?php if(!isset($velocity_themeoptions["velocity_footerwidgetsactive"]) && isset($velocity_themeoptions["velocity_subfooterwidgetsactive"])){ 
			$velocity_footermodding = "style='background: #fff !important;padding-top: 0 !important;'";	
		} else { 
			$velocity_footermodding = "";
		}
		?>

		<section class="footerwrap <?php echo $velocity_wideclass ?>" <?php echo $velocity_footermodding ?>>
		
			<?php if(isset($velocity_themeoptions["velocity_footerwidgetsactive"])){ ?>
		
			<section class="footer">
				<div class="row">
				<article class="span3 widget">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Widget Slot 1") ) : ?>
						
						<div class="widget-1  first span3 widget widget_text">			<div class="textwidget">
						Footer Widget Slot 1<br><br>
						Please configure this Widget in the Admin Panel under Appearance -> Widgets -> Footer Widget Slot 1
</div>
								<div class="clear"></div></div>
					
					<?php endif; ?>
				</article>				
				<article class="span3 widget">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Widget Slot 2") ) : ?>
					
						<div class="widget-1  first span3 widget widget_text">			<div class="textwidget">
						Footer Widget Slot 2<br><br>
						Please configure this Widget in the Admin Panel under Appearance -> Widgets -> Footer Widget Slot 2
</div>
								<div class="clear"></div></div>
					<?php endif; ?>
				</article>
				<article class="span3 widget">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Widget Slot 3") ) : ?>
						<div class="widget-1  first span3 widget widget_text">			<div class="textwidget">
						Footer Widget Slot 3<br><br>
						Please configure this Widget in the Admin Panel under Appearance -> Widgets -> Footer Widget Slot 3
</div>
								<div class="clear"></div></div>
					<?php endif; ?>
				</article>
				<article class="span3 widget">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Widget Slot 4") ) : ?>
						<div class="widget-1  first span3 widget widget_text">			<div class="textwidget">
						Footer Widget Slot 4<br><br>
						Please configure this Widget in the Admin Panel under Appearance -> Widgets -> Footer Widget Slot 4
</div>
								<div class="clear"></div></div>
					<?php endif; ?>
				</article>	
				</div>
			</section>
			
			<?php } ?>
						
		</section> 
		
		<?php if(isset($velocity_themeoptions["velocity_subfooterwidgetsactive"])){ ?>
		
			<!-- Sub Footer -->  
			<section class="subfooterwrap <?php echo $velocity_wideclass ?>">
				<div class="subfooter">
					<div class="row">
						<article class="span6 lefttext">
							<div id="text-2" class="widget-1  first widget_text">			
								<div class="textwidget">
									<?php if(isset($velocity_themeoptions["velocity_subfooterlefttext"])) echo $velocity_themeoptions["velocity_subfooterlefttext"]; ?>
								</div>
							<div class="clear"></div></div>
						</article>
						<article class="span6 righttext">
							<div id="text-3" class="widget-1  first widget_text">			
								<div class="textwidget">
									<?php if(isset($velocity_themeoptions["velocity_subfooterrighttext"])) echo $velocity_themeoptions["velocity_subfooterrighttext"]; ?>
								</div>
							<div class="clear"></div></div>
						</article>
					</div>
				</div>
			</section>
		
		<?php } ?>			

</footer>
<?php endif; ?>
<!--<section class="poswrapper <?php echo $velocity_wideclass ?>"><div class="whitebackground"></div></section>  -->
<?php if (!empty($velocity_backstretch) && $velocity_themelayout=="Boxed" && $velocity_bgtype=="full"){ ?><script>jQuery.backstretch("<?php echo $velocity_backstretch; ?>", {speed: 500});</script><?php } ?>
<?php wp_footer(); ?></body></html>