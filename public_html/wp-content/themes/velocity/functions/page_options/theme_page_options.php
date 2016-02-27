<?php
/* ------------------------------------- */
/* PAGE/POST/PORTFOLIO OPTIONS */
/* ------------------------------------- */

// Prefix for Page/Post/Portfolio Options
$prefix="velocity_";

// Load the Page Option Meta Fields
$custom_meta_fields=array();
require_once(velocity_FUNCTIONS . '/page_options/theme_page_custom_meta.php');
require_once(velocity_FUNCTIONS . '/page_options/theme_post_custom_meta.php');
require_once(velocity_FUNCTIONS . '/page_options/theme_portfolio_custom_meta.php');

// Generate Page/Post/Portfolio Options
add_action('admin_init', 'init_page_options');
function init_page_options() {
	add_meta_box("page-options", "Page Options", "show_custom_page_meta_box", "page", "normal", "high");
	add_meta_box("portfolio-options", "Portfolio Options", "show_custom_page_portfolio_meta_box", "page", "normal", "high");
	
	//WOO Commerce Single Product Options
	if(isset($_GET['post']) && $_GET['post'] == get_option('woocommerce_shop_page_id')) 
		add_meta_box("woocommerce-options", "Woo Commerce Product Options", "show_custom_page_woocommerce_meta_box", "page", "normal", "high");	
	
	add_meta_box("post-options", "Post Options", "show_custom_post_meta_box", "post", "normal", "high");
	add_meta_box("post-type-options", "Post Type Options", "show_custom_post_type_meta_box", "post", "side", "high");
	
	
	$portfolios = get_option("damojoPortfolio_theme_portfolios_options");
	$portfolio_slugs = array();
	$portfolio_names = array();
	$j = 1;
	if(is_array($portfolios)){
		foreach($portfolios as $key => $value){
			if($j%2==0){
	            array_push($portfolio_slugs,$value);
	            $j = 0 ;
	        }
	        else{
	            array_push($portfolio_names,$value);
	        }
	    	$j++;
		}
	}
	
	if(is_array($portfolio_slugs))
		foreach ( $portfolio_slugs as $slug ){
			add_meta_box("portfolio-post-options", "Portfolio Options", "show_custom_portfolio_meta_box", $slug, "normal", "high");	
			add_meta_box("portfolio-post-type-options", "Portfolio Type Options", "show_custom_post_portfolio_type_meta_fields", $slug, "side", "high"); 
		}
}

// Include the Page Option Framework Functions
require_once(velocity_FUNCTIONS . '/page_options/theme_page_options_functions.php');
?>