<?php
	$exampleID = '"slider1"';
	if(!empty($arrSliders))
		$exampleID = '"'.$arrSliders[0]->getAlias().'"';
?>

	<div class='wrap'>
	
	<div class="title_line">
		<h2>
			ShowBiz Sliders
		</h2>
		
		<?php BizOperations::putGlobalSettingsHelp(); ?>
		<?php BizOperations::putLinkHelp(GlobalsShowBiz::LINK_HELP_SLIDERS); ?>
		
	</div>
	
	<br>
	<?php if(empty($arrSliders)): ?>
		No Sliders Found
		<br>
	<?php else:
		try{
		 
			require self::getPathTemplate("sliders_list");
		 
		}catch(Exception $e){
			$message = $e->getMessage();
			$trace = $e->getTraceAsString();
			echo "Showbiz Error: <b>".$message." , <br> Please turn to the developer to solve this error!</b>";
		}
		 		 	 		
	endif?>
	
	
	<br>
	<p>			
		<a class='button-primary revblue' href='<?php echo $addNewLink?>'>Create New Slider</a>
	</p>
	 
	 <br>
	 
	<div>		
		<h3>How To Use:</h3>
		
		<ul>
			<li>
				* From the <b>theme html</b> use: <code>&lt?php putShowBiz( "alias" ) ?&gt</code> example: <code>&lt?php putShowBiz(<?echo $exampleID?>) ?&gt</code>
				<br>
				&nbsp;&nbsp; For show only on homepage use: <code>&lt?php putShowBiz(<?echo $exampleID?>,"homepage") ?&gt</code>
				<br>&nbsp;&nbsp; For show on certain pages use: <code>&lt?php putShowBiz(<?echo $exampleID?>,"2,10") ?&gt</code> 
			</li>
			<li>* From the <b>widgets panel</b> drag the "ShowBiz" widget to the desired sidebar</li>
			<li>* From the <b>post editor</b> insert the shortcode from the sliders table</li>
		</ul>
		---------
		<p>
			<?php _e("If you have some support issue:",SHOWBIZ_TEXTDOMAIN); ?><br />
			<?php _e("- In case the Slider came bundled with your theme, please contact your theme author",SHOWBIZ_TEXTDOMAIN); ?><br />
			<?php _e("- If the Slider has been purchased at CodeCanyon, visit",SHOWBIZ_TEXTDOMAIN); ?> <a href="http://www.themepunch.com/">Themepunch</a> or the <a href="http://codecanyon.net/item/showbiz-pro-responsive-teaser-wordpress-plugin/4720988?ref=themepunch">Showbiz</a> <?php _e("Discussion Page",SHOWBIZ_TEXTDOMAIN);?>
		</p> 
	</div>
	
	<p></p>
	
	
	</div>
