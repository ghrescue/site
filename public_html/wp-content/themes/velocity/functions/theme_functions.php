<?php

/* ------------------------------------- */
/* WOOCOMMERCE */
/* ------------------------------------- */

add_theme_support( 'woocommerce' );

//NUMBER OF PRODICTS TO DISPLAY ON SHOP PAGE
if(function_exists('is_shop')){ $shop_id = get_option('woocommerce_shop_page_id');
$velocity_woo_item = get_post_meta($shop_id, "velocity_shop_item_page_woo",true);
add_filter('loop_shop_per_page', create_function('$cols', "return $velocity_woo_item;"));
}

/* ------------------------------------- */
/* MAIN MENU REGISTRATION */
/* ------------------------------------- */

register_nav_menu( 'navigation', 'Main Menu Navigation' );

// Add Styles for Standard Menu
function velocity_add_menuclass($divclass) {
	return preg_replace('/<div class="menu">/', '<div  id="mainmenu" class="menu ddsmoothmenu">', $divclass, 1);
}
add_filter('wp_page_menu','velocity_add_menuclass');

/* ------------------------------------- */
/* Menu Icon JS Script Actions
/* ------------------------------------- */
function velocity_menu_icon_enqueue($hook) {
    if( 'nav-menus.php' != $hook )
        return;
    wp_enqueue_script( 'goodweb_menu_icon_script', velocity_JAVASCRIPT . '/menu_icons.js' );
}
add_action( 'admin_enqueue_scripts', 'velocity_menu_icon_enqueue' );

function velocity_icon_custom_walker( $args ) {
    return array_merge( $args, array(
        'walker' => new rc_scm_walker()
    ) );
}
if (has_nav_menu( 'navigation' )) add_filter( 'wp_nav_menu_args', 'velocity_icon_custom_walker' );

/* ------------------------------------- */
/* CUSTOM EXCERPT WORD LENGTH */
/* ------------------------------------- */

function velocity_new_excerpt_length($length) {
	global $velocity_excerpt_length;
    return $velocity_excerpt_length;
}

function velocity_excerpt($velocity_limit) {
	$velocity_excerpt = explode(' ', get_the_excerpt(), $velocity_limit);
	if (count($velocity_excerpt)>=$velocity_limit) {
		array_pop($velocity_excerpt);
		$velocity_excerpt = implode(" ",$velocity_excerpt).'...';
	} else {
		$velocity_excerpt = implode(" ",$velocity_excerpt);
	} 
	$velocity_excerpt = preg_replace('`\[[^\]]*\]`','',$velocity_excerpt);

	return $velocity_excerpt;
}

function velocity_excerpt_by_id($velocity_limit,$velocity_post_id) {
	global $post;  
	$velocity_save_post = $post;
	$post = get_post($velocity_post_id);
	$velocity_excerpt = explode(' ', get_the_excerpt(), $velocity_limit);
	if (count($velocity_excerpt)>=$velocity_limit) {
		array_pop($velocity_excerpt);
		$velocity_excerpt = implode(" ",$velocity_excerpt).'...';
	} else {
		$velocity_excerpt = implode(" ",$velocity_excerpt);
	} 
	$velocity_excerpt = preg_replace('`\[[^\]]*\]`','',$velocity_excerpt);
	$post = $velocity_save_post;
	return $velocity_excerpt;
}

/* ------------------------------------- */
/* Get Special Categories */
/* ------------------------------------- */

function velocity_getCategories($velocity_id){
	$velocity_categories = get_the_category($velocity_id);
	$velocity_output = '';
	if($velocity_categories){
		foreach($velocity_categories as $velocity_category) {
			$velocity_output .= '<div class="blogcategory"><a href="'.get_category_link($velocity_category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s",'velocity' ), $velocity_category->name ) ) . '">'.$velocity_category->cat_name.'</a></div>';
		}
	return $velocity_output;
	}
}

/* ------------------------------------- */
/* FUNCTION TO RETRIEVE POST AND PAGE OPTIONS 
	http://www.wprecipes.com/wordpress-tip-get-all-custom-fields-from-a-page-or-a-post */
/* ------------------------------------- */

function velocity_getOptions($id = 0){
    if ($id == 0) :
        global $wp_query;
        $velocity_content_array = $wp_query->get_queried_object();
		if(isset($velocity_content_array->ID)){
        	$velocity_id = $velocity_content_array->ID;
		}
    endif;   

    $velocity_first_array = get_post_custom_keys($id);

	if(isset($velocity_first_array)){
		foreach ($velocity_first_array as $velocity_key => $velocity_value) :
			   $velocity_second_array[$velocity_value] =  get_post_meta($id, $velocity_value, FALSE);
				foreach($velocity_second_array as $velocity_second_key => $velocity_second_value) :
						   $velocity_result[$velocity_second_key] = $velocity_second_value[0];
				endforeach;
		 endforeach;
	 }
	
	if(isset($velocity_result)){
    	return $velocity_result;
	}
}

/* ------------------------------------- */
/* FUNCTION TO RETRIEVE THEME OPTIONS	 */
/* ------------------------------------- */
	function velocity_getThemeOptions(){
		$damojoPortfolio = get_option("damojoPortfolio_theme_portfoliodef_options");
		if(!empty($damojoPortfolio)){
			return array_merge(get_option("damojoPortfolio_theme_portfolios_options"),get_option("damojoPortfolio_theme_portfoliodef_options"),get_option("velocity_theme_header_options"),get_option("velocity_theme_footer_options"),get_option("velocity_theme_layout_options"),
							get_option("velocity_theme_color_options"),get_option("velocity_theme_font_options"),get_option("velocity_theme_background_options"),get_option("velocity_theme_search_options"),get_option("velocity_theme_blog_options"));
		}
		else{
			return array_merge(get_option("velocity_theme_header_options"),get_option("velocity_theme_footer_options"),get_option("velocity_theme_layout_options"),
							get_option("velocity_theme_color_options"),get_option("velocity_theme_font_options"),get_option("velocity_theme_background_options"),get_option("velocity_theme_search_options"),get_option("velocity_theme_blog_options"));
		}
	}


/* ------------------------------------- */
/* ID BY SLUG FUNCTION */
/* ------------------------------------- */

function velocity_idbyslug($velocity_page_slug) {
	$velocity_page = get_page_by_path($velocity_page_slug);
	if ($velocity_page) {
		return $velocity_page->ID;
	} else {
		return null;
	}
};


$content_width = 940; 

/* ------------------------------------- */
/* ADD FIRST AND LAST CSS CLASS TO WIDGETS
   by MathSmath http://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets*/
/* ------------------------------------- */

function velocity_widget_first_last_classes($velocity_params) {
	global $my_widget_num; // Global a counter array
	$velocity_this_id = $velocity_params[0]['id']; // Get the id for the current sidebar we're processing
	$velocity_arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets	

	if(!$my_widget_num) {// If the counter array doesn't exist, create it
		$my_widget_num = array();
	}

	if(!isset($velocity_arr_registered_widgets[$velocity_this_id]) || !is_array($velocity_arr_registered_widgets[$velocity_this_id])) { // Check if the current sidebar has no widgets
		return $velocity_params; // No widgets in this sidebar... bail early.
	}

	if(isset($my_widget_num[$velocity_this_id])) { // See if the counter array has an entry for this sidebar
		$my_widget_num[$velocity_this_id] ++;
	} else { // If not, create it starting with 1
		$my_widget_num[$velocity_this_id] = 1;
	}

	$velocity_class = 'class="widget-' . $my_widget_num[$velocity_this_id] . ' '; // Add a widget number class for additional styling options

	if($my_widget_num[$velocity_this_id] == 1 ) { // If this is the first widget
		$velocity_class .= ' first ';
	} elseif($my_widget_num[$velocity_this_id] == count($velocity_arr_registered_widgets[$velocity_this_id])) { // If this is the last widget
		$velocity_class .= ' last ';
	}

	$velocity_params[0]['before_widget'] = str_replace('class="', $velocity_class, $velocity_params[0]['before_widget']); // Insert our new classes into "before widget"

	return $velocity_params;
}
add_filter('dynamic_sidebar_params','velocity_widget_first_last_classes');


/* ------------------------------------- */
/* Special Comment Reply Link
/* ------------------------------------- */
function velocity_special_comment_reply_link($velocity_args = array(), $velocity_comment = null, $velocity_post = null) {
	        global $user_ID;
	
	        $velocity_defaults = array('add_below' => 'comment', 'respond_id' => 'respond', 'reply_text' => __('Reply','velocity'),
	                'login_text' => __('Log in to Reply','velocity'), 'depth' => 0, 'before' => '', 'after' => '');
	
	        $velocity_args = wp_parse_args($velocity_args, $velocity_defaults);
	
	        if ( 0 == $velocity_args['depth'] || $velocity_args['max_depth'] <= $velocity_args['depth'] )
	                return;
	
	        extract($velocity_args, EXTR_SKIP);
	
	        $comment = get_comment($comment);
	        if ( empty($post) )
	                $post = $comment->comment_post_ID;
	        $post = get_post($post);
	
	        if ( !comments_open($post->ID) )
	                return false;
	
	        $velocity_link = '';
	
	        if ( get_option('comment_registration') && !$user_ID )
	                $velocity_link = '<a rel="nofollow" class="comment-reply-login tpbutton buttondark leftfloat" href="' . esc_url( wp_login_url( get_permalink() ) ) . '">' . $login_text . '</a>';
	        else
	                $velocity_link = "<a class='comment-reply-link tpbutton buttondark leftfloat' href='" . esc_url( add_query_arg( 'replytocom', $comment->comment_ID ) ) . "#" . $respond_id . "' onclick='return addComment.moveForm(\"$add_below-$comment->comment_ID\", \"$comment->comment_ID\", \"$respond_id\", \"$post->ID\")'> - $reply_text</a>";
	        return apply_filters('comment_reply_link', $before . $link . $after, $args, $comment, $post);
	}



/* ------------------------------------- */
/* Color Hex to RGB
/* ------------------------------------- */
function velocity_HexToRGB($hex) {
		$hex = str_replace("#", "", $hex);
		$color = array();
 
		if(strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . $r);
			$color['g'] = hexdec(substr($hex, 1, 1) . $g);
			$color['b'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}
 
		return $color;
	}
		

/* ------------------------------------- */
/* Allow Contact Form 7 Forms to include shortcodes
/* ------------------------------------- */

	add_filter( 'wpcf7_form_elements', 'velocity_wpcf7_form_elements' );	
	function velocity_wpcf7_form_elements( $form ) {
		$form = velocity_parse_shortcode_content( $form );
		$array = array (
	                '<p>[' => '[',
	                ']</p>' => ']',
	                ']<br />' => ']'
	        );
	
	    $form = strtr($form, $array);
		$form = do_shortcode( $form );
		return $form;
	}


/* ------------------------------------- */
/* Parse Uneended Half Open Tags
/* ------------------------------------- */	
	
	function velocity_parse_shortcode_content( $content ) { 
	 	/* Remove '</p> or <br>' from the start of the string. */ 
	    if ( substr( $content, 0, 6 ) == '<br />' ) 
	        $content = substr( $content, 6 ); 
	    
	    if ( substr( $content, 0, 4 ) == '</p>' ) 
	        $content = substr( $content, 4 ); 
	 
	    /* Remove '<p> or <br>' from the end of the string. */ 
	    if ( substr( $content, -3, 3 ) == '<p>' ) 
	        $content = substr( $content, 0, -3 ); 
	    
	     if ( substr( $content, -6, 6 ) == '<br />' ) 
	        $content = substr( $content, 0, -6 ); 
	 
	    return $content; 
	} 	
	
/* ------------------------------------- */
/* Tag Cloud Widget Font Size
/* ------------------------------------- */

function velocity_tagcloud_settings($args){
	$args = array('smallest' => 13, 'largest' => 13, 'unit' => 'px');
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'velocity_tagcloud_settings' );

/* ------------------------------------- */
/* Add shortcode support to widgets
/* ------------------------------------- */
add_filter('widget_text', 'do_shortcode');


add_action( 'pre_get_posts',  'velocity_set_posts_per_page'  );
function velocity_set_posts_per_page( $query ) {

  global $wp_the_query;
  $archive_options = get_option("damojoPortfolio_theme_portfoliodef_options");
  
 $amount = empty($archive_options['damojoPortfolio_portfolioarchivenumber']) ? get_option('posts_per_page') : $archive_options['damojoPortfolio_portfolioarchivenumber'];

  if ( ( ! is_admin() ) && ( $query === $wp_the_query ) && ( $query->is_archive() && $query->is_tax() ) ) {
    $query->set( 'posts_per_page', $amount );
  }
  // Etc..

  return $query;
}

function velocity_get_attachment_id_from_src ($image_src) {

		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;

}

//Change ReadMore [...]
	function velocity_new_excerpt_more( $more ) {
		return " ...";
	}
	add_filter('excerpt_more', 'velocity_new_excerpt_more');

//Get RevSlider Property
	function velocity_get_revslider_property($slider,$property){
		global $wpdb;
		global $table_prefix;
		$table_prefix = $wpdb->base_prefix;

		if (!isset($wpdb->tablename)) {
			$wpdb->tablename = $table_prefix . 'revslider_sliders';
		}
		$revolution_sliders = $wpdb->get_results(
			"
			SELECT params
			FROM $wpdb->tablename
			WHERE alias='$slider'
			"
		);
		//return $revolution_sliders[0];
		if(!empty($revolution_sliders[0]))
			foreach($revolution_sliders[0] as $key => $value){
				$vowels = array("{", "}", '"');
			 	$sliderparams = str_replace($vowels,"",$value);
			 	$sliderparams_array = explode(",", $sliderparams);
			 	foreach($sliderparams_array as $sliderparam){
				 	$sliderparam_array = explode(":",$sliderparam);
				 	if(isset($sliderparam_array[0]) && $sliderparam_array[0]==$property){
					 	$return_value = $sliderparam_array[1];
					 	return $return_value;
				 	}
			 	}
			}
		if(!empty($return_value))
			return $return_value;
		else return false;
	}
	
	add_shortcode( 'vc_tabs', 'tabs_builder' );
	add_shortcode( 'vc_tab', 'tab_builder' );
	
	add_filter('mce_buttons','wysiwyg_editor');
	function wysiwyg_editor($mce_buttons) {
	    $pos = array_search('wp_more',$mce_buttons,true);
	    if ($pos !== false) {
	        $tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
	        $tmp_buttons[] = 'wp_page';
	        $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
	    }
	    return $mce_buttons;
	}
	
	if(function_exists('is_shop')){
		$velocity_shop_id = get_option('woocommerce_shop_page_id');
		if(!empty($velocity_shop_id)){
			$velocity_shopcustoms = velocity_getOptions($velocity_shop_id);
		
			if(isset($velocity_shopcustoms["velocity_activate_cart_woo"]) )
				add_filter( 'woocommerce_add_to_cart_message', 'woocommrece_custom_add_to_cart_message' );	
		}
	} 
	
	function woocommrece_custom_add_to_cart_message() {
	
	global $woocommerce;
	
	 
	
	// Output success messages
	
	if (get_option('woocommerce_cart_redirect_after_add')=='yes') :
	
	 
	
	$return_to = get_permalink(woocommerce_get_page_id('shop'));// Give the url, you want to redirect
	
	 
	
	$message = sprintf('<a href="%s">%s</a> %s', $return_to, __('Continue Shopping &rarr;', 'woocommerce'), __('Product successfully added to your cart.', 'woocommerce') );
	
	 
	
	else :
	
	 
	
	$message = sprintf('<a href="%s">%s</a> %s', get_permalink(woocommerce_get_page_id('cart')), __('View Cart &rarr;', 'woocommerce'), __('Product successfully added to your cart.', 'woocommerce') );
	
	 
	
	endif;
	
	 
	
	return $message;
	
	}
	
	/* Custom Add To Cart Messages */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	
?>