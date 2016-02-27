<?php


/* ------------------------------------- */
/* velocity LATEST PROJECTS WIDGET */
/* ------------------------------------- */



class velocityLatestProjects extends WP_Widget {

	function velocityLatestProjects() {
		$widget_ops = array('classname' => 'velocityLatestProjects', 'description' => 'A widget to display links to the latest projects.');
    	$this->WP_Widget('velocityLatestProjects', 'Velocity Latest Projects', $widget_ops);
	}
	
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance ); 
		$portfolio_category = "";
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br /><input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( isset($instance['title']) ) echo $instance['title']; ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id( 'projectcount' ); ?>">Number of Projects to show:</label><br /><input class="widefat" id="<?php echo $this->get_field_id( 'projectcount' ); ?>" name="<?php echo $this->get_field_name( 'projectcount' ); ?>" value="<?php if( isset($instance['projectcount']) ) echo $instance['projectcount']; ?>" /></p> 

        <?php if ( !isset($instance['projectdisplay'])) $instance['projectdisplay']="text";

        ?>

		<p style="display:none"><label for="<?php echo $this->get_field_id( 'projectdisplay' ); ?>">Display Type:</label><br />
			<div style="display:none" style="white-space:nowrap"><input  type="radio" id="<?php echo $this->get_field_id( 'projectdisplay' ); ?>" name="<?php echo $this->get_field_name( 'projectdisplay' ); ?>" value="text" <?php if( isset($instance['projectdisplay']) &&  $instance['projectdisplay'] == "text") echo "checked"; ?> > Text</div>
			<div style="display:none" style="white-space:nowrap"><input  type="radio" id="<?php echo $this->get_field_id( 'projectdisplay' ); ?>" name="<?php echo $this->get_field_name( 'projectdisplay' ); ?>" value="image" <?php if( isset($instance['projectdisplay']) &&  $instance['projectdisplay'] == "image") echo "checked"; ?> > Image</div></p> 
        <p>
		<?php 
                $portfolio_counter = 0;
	        	$portfolios = get_option("damojoPortfolio_theme_portfolios_options");
				$portfolio_slugs = array();
				$portfolio_name = array();
				$j = 1;
				if(is_array($portfolios)){
					foreach($portfolios as $key => $value){
						if($j%2==0){
				            array_push($portfolio_slugs,$value);
				            $j = 0 ;
				        }
				        else{
				            array_push($portfolio_name,$value);
				        }
				    	$j++;
					}
	        	}
                $portfolio_list = "";
                foreach ( $portfolio_slugs as $slug ){
                    $checked="";
                    if(isset($instance['portfolio_category']) && $slug==$instance['portfolio_category']) $checked="selected";
                    $portfolio_list .= "<option value='$slug' $checked >".$portfolio_name[$portfolio_counter++]."</option>";
                }
        
        echo '<select name="'.$this->get_field_name( 'portfolio_category' ).'" class="widefat" >'.$portfolio_list.'
        </select></p>';
    }

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		if ( isset($instance['id']) ) $id = $instance['id'];
		$projectcount = $instance['projectcount'];
		$portfolio_category = $instance['portfolio_category'];
		$type = $instance['projectdisplay'];
		echo $before_widget;
		
		$velocity_teaser_readmore = __('Read More', 'velocity');

	   	if ( $title ) echo $before_title . $title . $after_title;
		
		$columndiv = "";

		$pcat = "category_".$portfolio_category;
		$args=array(
			'post_type' => $portfolio_category,
			'posts_per_page' => $projectcount,
			'suppress_filters' => 0
		);

		$rownumber = 2;
		global $wp_query;
		$temp = $wp_query; 
		$wp_query = null;
		$wp_query = new WP_Query();
		$wp_query->query($args);
		$terms = get_terms($pcat);
		$popcount=1;
		$unique = uniqid();
		$poplist = get_posts( $args );
		$element_count=1;
					echo '<div class="widget_projects"><ul>';
		foreach ($poplist as $poppost) :  setup_postdata($poppost);
           	    $category = get_the_category($poppost->ID);
           	    if(isset($category[0]))
					$first_category = $category[0]->cat_name;
				else
					$first_category = "uncatagorized";
				$repl = strtolower((preg_replace('/\s+/', '-', $first_category)));
				$base = home_url();

				/*if(strlen($poppost->post_title)>18)
					$posttitle = substr($poppost->post_title, 0, 18)."...";
				else*/
					$posttitle = $poppost->post_title;
                
                if($popcount == sizeof($poplist)) $last=" style='margin-bottom:7px;'";
                else $last="";
                
                $thetarget = '';
                
               $blogimageurl = wp_get_attachment_url( get_post_thumbnail_id($poppost->ID) ); 
				if (!empty($blogimageurl)){
					$velocity_project_widget_pagecustoms = velocity_getOptions($poppost->ID);
					if(!empty($velocity_project_widget_pagecustoms["velocity_launch_project"]) && !empty($velocity_project_widget_pagecustoms["velocity_launch_project_type"]) && ($velocity_project_widget_pagecustoms["velocity_launch_project_type"]=="external" || $velocity_project_widget_pagecustoms["velocity_launch_project_type"]=="internal") ){
						 if($velocity_project_widget_pagecustoms["velocity_launch_project_type"]=="external")
						 	$thetarget = 'target="_blank"';
						 else
						 	$thetarget = '';
						 $thelink = $velocity_project_widget_pagecustoms["velocity_launch_project"];
					 }
					 else {
						$thelink = get_permalink($poppost->ID);
						$thetarget = "";
					} 
				
					$theblogimage = aq_resize( $blogimageurl, 60, 60, true, true, true );
					echo '<li><a href="'.$thelink.'" data-rel="tooltip" data-animation="false" '.$thetarget.' title="'.$posttitle.'"><img src="'.$theblogimage.'" alt="" /></a><div class="overl"></div></li>';
				}
				$popcount++;
      endforeach;
      	echo '</ul></div>';

		echo $after_widget;
		wp_reset_query();
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['projectcount'] = $new_instance['projectcount'];
		$instance['portfolio_category'] = $new_instance['portfolio_category'];
		$instance['projectdisplay'] = $new_instance['projectdisplay'];
		return $instance;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("velocityLatestProjects");') );


/* ------------------------------------- */
/* TWITTER TimeLine FEED WIDGET */
/* ------------------------------------- */


class velocityTwitterTimelinefeed extends WP_Widget {

 function velocityTwitterTimelinefeed() {
  $widget_ops = array('classname' => 'velocityTwitterTimelinefeed', 'description' => 'Twitter Timeline Widget');
     $this->WP_Widget('velocityTwitterTimelinefeed', 'Twitter Timeline Feed', $widget_ops);
 }
 
 function form( $instance ) {
  $instance = wp_parse_args( (array) $instance ); ?>
        
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br /><input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( isset($instance['title']) ) echo $instance['title']; ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id( 'twittercode' ); ?>">Timeline Code (Create <a  target="_blank" href="<?php echo 'https://twitter.';?>com/settings/widgets">here</a>, <a href="<?php echo 'http://www.themepunch.'; ?>com/valiano/wp-content/uploads/2013/03/twitter_widget.png" target="_blank">this</a> code):</label><br /><textarea class="widefat" style="height:150px;" id="<?php echo $this->get_field_id( 'twittercode' ); ?>" name="<?php echo $this->get_field_name( 'twittercode' ); ?>"><?php if( isset($instance['twittercode']) ) echo $instance['twittercode']; ?></textarea></p> 
        
        <p><label for="<?php echo $this->get_field_id( 'feedcount' ); ?>">Feed Count:</label><br /><input class="widefat" id="<?php echo $this->get_field_id( 'feedcount' ); ?>" name="<?php echo $this->get_field_name( 'feedcount' ); ?>" value="<?php if( isset($instance['feedcount']) ) echo $instance['feedcount']; ?>" /></p> 
        
        <p><label for="<?php echo $this->get_field_id( 'style' ); ?>">Original Twitter Timeline Style:</label><br /><input class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" type="checkbox" value="on" <?php if( isset($instance['style']) ) echo "checked"; ?> /></p> 
        
 <?php
 }

 function widget( $args, $instance ) {
   extract( $args );
 
   $title = apply_filters('widget_title', $instance['title'] );
   if ( isset($instance['id']) ) $id = $instance['id'];
   $code = $instance['twittercode'];
   $feeds = $instance['feedcount'];
   $style = isset($instance['style']) ? true : false;
   echo $before_widget;
   
   $uniq = rand(5, 15);
   
      if ( $title ) echo $before_title . $title . $after_title;
   echo '<div id="twitterfeed_'.$uniq.'">';
   echo $code;
   echo '
    <style>
     iframe.twitter-timeline{
      width:100% !important;
      min-width:100% !important;
      max-width:100% !important;
     }
    </style>';
   if(!$style)
    echo '
    <style>
     iframe.twitter-timeline{
      display:none;
     }
    </style>
    <script>
    
    jQuery(document).ready(function(){
     var ibody;
  
     // Customize twitter feed
     var changeTwitterAttempts = 0;	 	 
	 function initTwitterBoxElements() {
	     if ( jQuery(\'#twitterfeed_'.$uniq.' [id*=twitter]\').length ) {
             jQuery(\'#twitterfeed_'.$uniq.' [id*=twitter]\').each( function(){
				 
                 var ibody = jQuery(this).contents().find( \'html\' );
                 if ( ibody.find( \'.timeline .stream .h-feed li.tweet\' ).length ) {
                   
						  ibody.find( \'.timeline .stream .h-feed li.tweet\' ).slice('.$feeds.', ibody.find( \'.timeline .stream .h-feed li.tweet\' ).length).remove();
						  
						  ibody.find( \'.timeline .stream\').css(\'height\',(ibody.find( \'.timeline .stream ol\').outerHeight())+"px");
						  jQuery( \'#twitterfeed_'.$uniq.' .twitter-timeline\').css(\'height\',(ibody.find( \'.timeline .stream ol\').outerHeight()+10)+"px");
						 
						  ibody.find( \'.timeline\' ).css( \'border\', \'0px solid #000\' );
						  ibody.find( \'.timeline\' ).css( \'background-color\', \'transparent\' );
						  ibody.find( \'.tweet\' ).css( \'border\', "0px solid #000" );
						  ibody.find( \'.timeline .stream\' ).css( \'overflow-x\', \'hidden\' );
						  ibody.find( \'.timeline .stream\' ).css( \'overflow-y\', \'hidden\' );
						  ibody.find( \'.timeline-header\').hide();
						  ibody.find( \'.timeline-footer\').hide();
						  ibody.find( \'.timeline .load-more\').hide();
						  ibody.find( \'.tweet-actions\').css("shadow","0 0 0");
						  //ibody.find( \'.tweet-actions\').css("background-color","transparent");
						  jQuery( \'.twitter-timeline\').show(10); 
						  ibody.find( \'.timeline .stream\').each(function() {
							   jQuery(this).click(changeTwitterBoxElements);
						  });
                 }                 
             });
             }
             
               setTimeout(function() { changeTwitterBoxElements();},250);
             
	 }
	 
	 
     function changeTwitterBoxElements() {       
             if ( jQuery(\'#twitterfeed_'.$uniq.' [id*=twitter]\').length ) {
             jQuery(\'#twitterfeed_'.$uniq.' [id*=twitter]\').each( function(){
				 
                 var ibody = jQuery(this).contents().find( \'html\' );
                 if ( ibody.find( \'.timeline .stream .h-feed li.tweet\' ).length ) {                   
					ibody.find( \'.timeline .stream\').css(\'height\',(ibody.find( \'.timeline .stream ol\').outerHeight())+"px");
					jQuery( \'#twitterfeed_'.$uniq.' .twitter-timeline\').css(\'height\',(ibody.find( \'.timeline .stream ol\').outerHeight()+10)+"px");                 
					ibody.find( \'.timeline .stream\' ).css( \'overflow-x\', \'hidden\' );
					ibody.find( \'.timeline .stream\' ).css( \'overflow-y\', \'hidden\' );
                 }
             });
             }
             changeTwitterAttempts++;
             if ( changeTwitterAttempts < 3 ) {								 
                 setTimeout(function() { changeTwitterBoxElements();},250);
             }  else {
				changeTwitterAttempts=0;
			 }
     }
	 
	 
	 var twi=setInterval(function() {	 
		jQuery(\'#twitterfeed_'.$uniq.' [id*=twitter]\').each( function(){
			var trbody = jQuery(this).contents().find( \'html\' );
			if (trbody.find(\'.h-feed .tweet\').length>='.$feeds.') {
			  initTwitterBoxElements();
			  setTimeout(function() {initTwitterBoxElements();},1000);
			  clearInterval(twi);
			} else {
				// NOTHING
			}
		
			
		});	
	},100);
     
     var timeouts;
     
     jQuery(window).resize(function() {
      clearTimeout(timeouts);
      timeouts= setTimeout(function() {
       changeTwitterBoxElements();
      },150);
     });
    
    });
    
    </script>
    ';
   echo '</div>';
   echo $after_widget;
 }

 function update( $new_instance, $old_instance ) {
  $instance = $old_instance;
  $instance['title'] = $new_instance['title'];
  $instance['twittercode'] = $new_instance['twittercode'];
  $instance['feedcount'] = $new_instance['feedcount'];
  $instance['style'] = $new_instance['style'];
  return $instance;
 }
}
add_action( 'widgets_init', create_function('', 'return register_widget("velocityTwitterTimelinefeed");') );

/* ------------------------------------- */
/* velocity POSTS WIDGET */
/* ------------------------------------- */


class velocityPosts extends WP_Widget {

	function velocityPosts() {
		$widget_ops = array('classname' => 'velocityPosts', 'description' => 'A popular/latest posts widget.');
    	$this->WP_Widget('velocityPosts', 'Velocity Popular/Latest Posts', $widget_ops);
	}
	
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance ); ?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br /><input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( isset($instance['title']) ) echo $instance['title']; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'postcount' ); ?>">Post Count:</label><br /><input class="widefat" id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" value="<?php if( isset($instance['postcount']) ) echo $instance['postcount']; ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id( 'poplatest' ); ?>">Latest or Popular:</label><br />
        <select class="widefat" id="<?php echo $this->get_field_id( 'poplatest' ); ?>" name="<?php echo $this->get_field_name( 'poplatest' ); ?>">
        	<option value="1" <?php 
        		if( isset($instance['poplatest']) && $instance['poplatest']== 1 ) {
        			echo "selected"; 
        		}
        	?>>Latest Posts</option>
        	<option value="2" <?php 
        		if( isset($instance['poplatest']) && $instance['poplatest']== 2 ) {
        			echo "selected"; 
        		}
        	?>>Popular Posts</option>
        </select>
        </p>
        
        <p><label for="<?php echo $this->get_field_id( 'posttype' ); ?>">Show this Category Slug:</label><br /><input class="widefat" id="<?php echo $this->get_field_id( 'posttype' ); ?>" name="<?php echo $this->get_field_name( 'posttype' ); ?>" value="<?php if( isset($instance['posttype']) ) echo $instance['posttype']; ?>" /></p>
        <!--
        <p><label for="<?php echo $this->get_field_id( 'timeformat' ); ?>">Time Format (see <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">here</a>):</label><br /><input class="widefat" id="<?php echo $this->get_field_id( 'timeformat' ); ?>" name="<?php echo $this->get_field_name( 'timeformat' ); ?>" value="<?php if( isset($instance['timeformat']) ) echo $instance['timeformat']; ?>" /></p>
        -->
        
	<?php
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		if ( isset($instance['id']) ) $id = $instance['id'];
		$pcount = $instance['postcount'];
		$platest = $instance['poplatest'];
		$ptype = $instance['posttype'];
		$tformat = $instance['timeformat'];
		$rmore = $instance['readmore'];
		
		$velocity_teaser_readmore = __('Read More', 'velocity');

		echo $before_widget;
		
	   	if ( $title ) echo $before_title . $title . $after_title;
		
		if($ptype==""){
			$ptype = 'post';
		}

		$category = get_category_by_slug($ptype);
		if($category)$catid = $category->term_id;
		else $catid="";

		if($platest==1){
			$popargs = array( 'numberposts' => $pcount, 'orderby' => 'post_date', 'cat' => $catid ,'suppress_filters' => 0 );
		}else{
			$popargs = array( 'numberposts' => $pcount, 'orderby' => 'comment_count', 'cat' => $catid ,'suppress_filters' => 0 );
		}
		$unique = uniqid();
		$poplist = get_posts( $popargs );
		$popcount=1;
					echo '<div class="widget_posts"><ul>';
		foreach ($poplist as $poppost) :  setup_postdata($poppost);
           	    $category = get_the_category($poppost->ID);
           	    if(isset($category[0]))
					$first_category = $category[0]->cat_name;
				else
					$first_category = "uncatagorized";
				$repl = strtolower((preg_replace('/\s+/', '-', $first_category)));
				$base = home_url();

				/*if(strlen($poppost->post_title)>18)
					$posttitle = substr($poppost->post_title, 0, 18)."...";
				else*/
					$posttitle = $poppost->post_title;
                
                if($popcount == sizeof($poplist)) $last=" style='margin-bottom:7px;'";
                else $last="";


                	echo '<li><a href="'.get_permalink($poppost->ID).'">'.$posttitle.'</a></li>';
												
							
				
				$popcount++;
      endforeach;
      	echo '</ul>
								</div>';

		echo $after_widget;
		wp_reset_query();
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['postcount'] = $new_instance['postcount'];
		$instance['poplatest'] = $new_instance['poplatest'];
		$instance['posttype'] = $new_instance['posttype'];
		$instance['timeformat'] = $new_instance['timeformat'];
		$instance['readmore'] = $new_instance['readmore'];
		return $instance;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("velocityPosts");') );



/* ------------------------------------- */
/* velocity CUSTOM ARCHIVES WIDGET */
/* ------------------------------------- */


class velocityArchives extends WP_Widget
{
  function velocityArchives()
  {
    $widget_ops = array('classname' => 'velocityArchives', 'description' => 'Displays the Blog Archives' );
    $this->WP_Widget('velocityArchives', 'Velocity Archives', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if( isset($instance['title']) ) echo $instance['title']; ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
    echo $before_title . $title . $after_title;

	echo '<div class="widget_categories"><ul>';
	wp_get_archives(apply_filters('widget_archives_dropdown_args', array('type' => 'monthly', 'format' => 'html', 'before' => '<span>&rsaquo; </span>')));
    echo '</ul></div><div style="clear:both;"></div>';
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("velocityArchives");') );



/* ------------------------------------- */
/* velocity CUSTOM CATEGORIES WIDGET */
/* ------------------------------------- */



class velocityCategories extends WP_Widget
{
  function velocityCategories()
  {
    $widget_ops = array('classname' => 'velocityCategories', 'description' => 'Displays a list of Blog Categories' );
    $this->WP_Widget('velocityCategories', 'Velocity Categories', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if( isset($instance['title']) ) echo $instance['title']; ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
	echo '<div class="widget_categories"><ul>';
	$cats = get_categories();
	foreach ($cats as $cat) {
		$my_query = new WP_Query('category_name='.$cat->name.'&posts_per_page=1'); 
 		while ($my_query->have_posts()) : $my_query->the_post();
      		 $blogimageurl = wp_get_attachment_url( get_post_thumbnail_id() ); 
        endwhile; 
		echo '<li><span>&rsaquo; </span><a href="'.get_category_link( $cat->term_id ).'">'.$cat->name.'</a></li>';
	}
    echo '</ul></div>';
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("velocityCategories");') );


/*-----------------------------------------------------------------------------------

	Plugin Name: Social Buttons Widget Plugin
	Plugin URI: http://www.thunderbuddies.com
	Description: A widget that displays a simple list of social profile icons
	Version: 1.0
	Author: thunderbuddies
	Author URI: http://www.thunderbuddies.com

-----------------------------------------------------------------------------------*/	
	add_action( 'widgets_init', create_function('', 'return register_widget("velocitySocials");') );
	class velocitySocials extends WP_Widget {
	
		function velocitySocials() {
			$widget_ops = array('classname' => 'velocitySocials', 'description' => 'Simple list of Social Profile icons');
	    	$this->WP_Widget('velocitySocials', 'Velocity Socials Widget', $widget_ops);
		}
		
		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance ); ?>
	        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
	        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( isset($instance['title']) ) echo $instance['title']; ?>" />
	        <label>Socials:</label><hr><br>
		    <div>
		        <div style="display:none;">
		        	<select class="widefat" data-name="<?php echo $this->get_field_name( 'socialicon' ); ?>[]">
			        	<option value="facebook">Facebook</option>
			        	<option value="gplus">Google+</option>
			        	<option value="linkedin">LinkedIn</option>
			        	<option value="pinterest">Pinterest</option>
			        	<option value="rss">RSS</option>
			        	<option value="twitter">Twitter</option>
			        	<option value="vimeo">Vimeo</option>
			        	<option value="youtube">Youtube</option>
			        	<option value="instagram">Instagram</option>
			        	<option value="foursquare">Foursquare</option>
			        </select>
			        <label for="<?php echo $this->get_field_name( 'link' ); ?>[]">URL Link:</label>
			        <input type="text" class="widefat" data-name="<?php echo $this->get_field_name( 'link' ); ?>[]"/>
			        <br><a href="#" class="velocity_delete_social">Delete Social</a>
		        </div>
		        <?php 
		        	$social_count=0;
		        	$social_selected="";
		        	$uniq = uniqid();
		        	if(isset($instance['socialicon'])){
			        	foreach($instance['socialicon'] as $socialicon){
				        	if(!empty($socialicon))
				        		echo '<div><select class="widefat" name="'.$this->get_field_name( 'socialicon' ).'[]">';
				        		
					        	if($socialicon=="facebook") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="facebook" '.$social_selected.'>Facebook</option>';

					        	if($socialicon=="gplus") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="gplus" '.$social_selected.'>Google+</option>';
					        	
					        	if($socialicon=="linkedin") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="linkedin" '.$social_selected.'>LinkedIn</option>';
					        	
					        	if($socialicon=="pinterest") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="pinterest" '.$social_selected.'>Pinterest</option>';
					        	
					        	if($socialicon=="rss") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="rss" '.$social_selected.'>RSS</option>';
					        	
					        	if($socialicon=="twitter") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="twitter" '.$social_selected.'>Twitter</option>';
					        	
					        	if($socialicon=="vimeo") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="vimeo" '.$social_selected.'>Vimeo</option>';
					        	
					        	if($socialicon=="youtube") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="youtube" '.$social_selected.'>Youtube</option>';

					        	if($socialicon=="instagram") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="instagram" '.$social_selected.'>Instagram</option>';
					        	
					        	if($socialicon=="foursquare") $social_selected="selected"; else $social_selected ="";
					        	echo '<option value="foursquare" '.$social_selected.'>Foursquare</option>';					        	

						        echo '</select>';
						        
						        $link = "";
						        if( isset($instance['link'][$social_count]) ) $link = $instance['link'][$social_count++]; 
						        echo '<label for="'.$this->get_field_name( 'link' ).'">URL Link:</label><input type="text" class="widefat" name="'.$this->get_field_name( 'link' ).'[]" value="'.$link.'" />';

						        echo '<br><a href="#" class="velocity_delete_social">Delete Social</a></div><br>';
			        	}
			        }?>
	        	<span></span>
	        	<br><hr><a href='#' class="velocity_add_social_<?php echo $uniq;?>">Add Social</a>
	        </div>
	        
	        <script>
		       
		        	jQuery(".velocity_add_social_<?php echo $uniq;?>").live("click",function(){
		        		$parent = jQuery(this).closest("div");
		        		$field = $parent.find("div:first").clone(true);
			        	$field.find("select,input").each(function(){
				        	$this = jQuery(this);
				        	$this.attr("name",$this.data("name"));
				        	});
			        	$field.css("display","");
			        	$parent.find("span").before($field);
			        	return false;
			        });
		        	jQuery(".velocity_delete_social").live("click",function(){
			        	jQuery(this).closest("div").remove();	
			        	return false;
		        	});

	        </script>
	    <?php
		}
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = $new_instance['title'];
			$instance['socialicon'] = $new_instance['socialicon'];
			$instance['link'] = $new_instance['link'];

			return $instance;
		}
	
		function widget( $args, $instance ) {
			extract( $args );
	
			$title = apply_filters('widget_title', $instance['title'] );
			if ( isset($instance['id']) ) $id = $instance['id'];

						
			echo $before_widget;
		   	if ( !empty($title) ) echo $before_title . $title . $after_title;
			echo '<div class="social"><ul>';
				$social_count = 0;
				if(is_array($instance['socialicon']))
					foreach($instance['socialicon'] as $class){
						$network = ucfirst($class);
						if($network == "Rss") $network = "RSS";
						if($network == "Linkedin") $network = "LinkedIn";
						if($network == "Gplus") $network = "Google+";		
						if($network == "Youtube") $network = "YouTube";			
						echo '<li><a href="'.$instance['link'][$social_count++].'" target="_blank" class="so_'.$class.'" data-rel="tooltip" data-animation="false" data-placement="bottom" title="'.$network.'"><div class="s_icon social-'.$class.'"></div></a></li>';
					}
			echo '</ul></div>';
			echo $after_widget;	
		}
	}

?>