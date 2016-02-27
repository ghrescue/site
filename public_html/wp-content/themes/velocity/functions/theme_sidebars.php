<?php
/* ------------------------------------- */
/* SIDEBAR REGISTRATION */
/* ------------------------------------- */

if ( function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Blog Sidebar',
        'id' => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<div class="footertitle"><h4>',
        'after_title' => '</h4></div>'
    ));
        register_sidebar(array(
        'name' => 'Portfolio Sidebar',
        'id' => 'sidebar-2',
		'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<div class="footertitle"><h4>',
        'after_title' => '</h4></div>'
    ));
        register_sidebar(array(
        'name' => 'Contact Sidebar',
        'id' => 'sidebar-3',
		'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<div class="footertitle"><h4>',
        'after_title' => '</h4></div>'
    ));
        register_sidebar(array(
        'name' => 'Page Sidebar',
        'id' => 'sidebar-4',
		'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<div class="footertitle"><h4>',
        'after_title' => '</h4></div>'
    ));
       register_sidebar(array(
        'name' => 'Footer Widget Slot 1',
        'id' => 'sidebar-5',
		'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<table class="footertitle"><tr style="width:100%"><td style="white-space:nowrap;"><h4>',
        'after_title' => '</h4></td><td style="width:100%"><div class="widgettitlebg"></div></td></tr></table>'
    ));
     register_sidebar(array(
        'name' => 'Footer Widget Slot 2',
        'id' => 'sidebar-6',
		'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<table class="footertitle"><tr style="width:100%"><td style="white-space:nowrap;"><h4>',
        'after_title' => '</h4></td><td style="width:100%"><div class="widgettitlebg"></div></td></tr></table>'
    ));
    register_sidebar(array(
        'name' => 'Footer Widget Slot 3',
        'id' => 'sidebar-7',
		'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<table class="footertitle"><tr style="width:100%"><td style="white-space:nowrap;"><h4>',
        'after_title' => '</h4></td><td style="width:100%"><div class="widgettitlebg"></div></td></tr></table>'
    ));
    register_sidebar(array(
        'name' => 'Footer Widget Slot 4',
        'id' => 'sidebar-8',
		'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<table class="footertitle"><tr style="width:100%"><td style="white-space:nowrap;"><h4>',
        'after_title' => '</h4></td><td style="width:100%"><div class="widgettitlebg"></div></td></tr></table>'
    ));
    
    register_sidebar(array(
        'name' => 'Header Top Line Left',
        'id' => 'sidebar-11',
		'before_widget' => '<div id="%1$s" class="%2$s headerleftwidget">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<div class="footertitle"><h4>',
        'after_title' => '</h4></div>'
    ));

    register_sidebar(array(
        'name' => 'Header Top Line Right',
        'id' => 'sidebar-10',
		'before_widget' => '<div id="%1$s" class="%2$s headerrightwidget"  style="float:right">',
        'after_widget' => '<div class="clear"></div></div>',
        'before_title' => '<div class="footertitle"><h4>',
        'after_title' => '</h4></div>'
    ));
    


    //CUSTOM SIDEBARS   
	    $sidebars = get_option("velocity_theme_sidebars_options");
		$i = 0;
	    $j = 1; 
	    if (is_array($sidebars) && !empty($sidebars)) {  
	        foreach($sidebars as $row) {
	        	if($j%2==0){
	        		register_sidebar(array(
		               'name' => $sidebar,
		               'id' => 'sidebar-'.$row,
		               'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
					   'after_widget' => '<div class="clear"></div></div>',
					   'before_title' => '<div class="footertitle"><h4>',
					   'after_title' => '</h4></div>'
		            )); 
	                $j = 0;
		        }
		        else{
			        $sidebar = $row;
			    }
			    $j++;
	        }
	    }
}?>