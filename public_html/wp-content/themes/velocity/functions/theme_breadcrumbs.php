<?php

function velocity_breadcrumb(){
if(function_exists('bcn_display'))  {  bcn_display(); } 
else {
	global $wp_query;
    $velocity_content_array = $wp_query->get_queried_object();
	if(isset($velocity_content_array->ID)){
    	$velocity_post_id = $velocity_content_array->ID;
	}
	else $velocity_post_id=0;
	
	$velocity_home = __('Home', 'velocity');
	
	echo '<a href="'.home_url('/').'">'.$velocity_home.'</a>';
	if(is_home()){
		echo '&nbsp; &nbsp;/&nbsp; &nbsp;'.get_the_title($velocity_post_id);
	}
	elseif (is_category() || is_single()) {
		$entrycategory="";
		foreach((get_the_category()) as $category) {
			$entrycategory .= ', <a href="'.get_category_link($category->term_id ).'">'.$category->cat_name.'</a>';
		}
		$entrycategory = substr($entrycategory, 2);
		echo '&nbsp; &nbsp;/&nbsp; &nbsp;'.$entrycategory."";
		
		if (is_single()) {
			if ( get_post_type() != 'post' && isset($_GET["tp"]) ) {
	      	  $parent_id = $_GET["tp"];
		      echo '<a href="'.get_permalink($parent_id).'">'.get_the_title($parent_id).'</a>';
		    }
		    elseif ( get_post_type() != 'post' && !isset($_GET["tp"]) ){
				if(function_exists('is_product') && is_product()){
						$velocity_post_id = get_option('woocommerce_shop_page_id');
						echo '<a href="'.get_permalink($velocity_post_id).'">'.get_the_title($velocity_post_id).'</a>';
				}
				else {		    	
			    	$tb_velocity_portfolio = get_post_type( $velocity_post_id );
			    	global $damojoPortfolio_portfolio_slugs,$damojoPortfolio_portfolio_names;
			    	$velocity_counter = 0;
			    	if(is_array($damojoPortfolio_portfolio_slugs)){
				    	foreach($damojoPortfolio_portfolio_slugs as $tb_velocity_portfolio_try){
					    	if($tb_velocity_portfolio == $tb_velocity_portfolio_try){
						    	$tb_velocity_portfolio = $damojoPortfolio_portfolio_names[$velocity_counter];
						    	$velocity_counter++;
						    	break;
					    	}  
				    	}
					}
				    echo ''.$tb_velocity_portfolio.'';
				}
		    }
		    
		    echo '&nbsp; &nbsp;/&nbsp; &nbsp;';
			echo ''.get_the_title(get_the_id()).'';
		}
	} 
	elseif ( is_search() ) {
      echo '&nbsp; &nbsp;/&nbsp; &nbsp;' . __('Search results for "','velocity') . get_search_query() . '"';
 
    } elseif ( is_day() ) {
      echo '&nbsp; &nbsp;/&nbsp; &nbsp;' . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>';
      echo '&nbsp; &nbsp;/&nbsp; &nbsp;' . '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . '';
      echo '&nbsp; &nbsp;/&nbsp; &nbsp;' . get_the_time('d') . '';
 
    } elseif ( is_month() ) {
      echo '&nbsp; &nbsp;/&nbsp; &nbsp;' .'<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . '';
      echo '&nbsp; &nbsp;/&nbsp; &nbsp;' . get_the_time('F') . '';
 
    } elseif ( is_year() ) {
      echo '&nbsp; &nbsp;/&nbsp; &nbsp;' . get_the_time('Y') . '';
 
    }
	elseif (is_page()) {
		//WOO
		if (function_exists('is_cart') && is_cart()) {	
			echo '&nbsp; &nbsp;/&nbsp; &nbsp;';
			$velocity_post_id = get_option('woocommerce_shop_page_id');
			echo '<a href="'.get_permalink($velocity_post_id).'">'.get_the_title($velocity_post_id).'</a>';
		}	
		if (function_exists('is_checkout') && is_checkout()) {	
			echo '&nbsp; &nbsp;/&nbsp; &nbsp;';
			$velocity_post_id = get_option('woocommerce_shop_page_id');
			echo '<a href="'.get_permalink($velocity_post_id).'">'.get_the_title($velocity_post_id).'</a>';
			echo '&nbsp; &nbsp;/&nbsp; &nbsp;';
			$velocity_post_id = get_option('woocommerce_cart_page_id');
			echo '<a href="'.get_permalink($velocity_post_id).'">'.get_the_title($velocity_post_id).'</a>';
		}
		echo '&nbsp; &nbsp;/&nbsp; &nbsp;';
		echo ''.get_the_title().'';
	} 
	elseif (function_exists('is_shop') && is_shop()) {	
		echo '&nbsp; &nbsp;/&nbsp; &nbsp;';
		$velocity_post_id = get_option('woocommerce_shop_page_id');
		echo ''.get_the_title($velocity_post_id).'';
	} 
}
}


?>