<?php
/*
Plugin Name: Velocity Portfolio CPT
Plugin URI: http://www.damojothemes.com
Description: Plugin for creating Portfolio Items in the Velocity Theme
Author: Damojo
Version: 1.0
Author URI: http://themepunch.com
*/
	define( 'damojoPortfolio_PATH', plugin_dir_path(__FILE__) );
	define( 'damojoPortfolio_URL', str_replace("velocityPortfolio.php","",plugins_url( 'velocityPortfolio.php', __FILE__ )));
	
	/* ------------------------------------------------------------------------ *
	 * Field Definitions
	 * ------------------------------------------------------------------------ */ 
	require_once(damojoPortfolio_PATH.'/plugin_options/theme_options.php');
	
	/* ------------------------------------------------------------------------ *
	 * Build Functions
	 * ------------------------------------------------------------------------ */ 
	require_once(damojoPortfolio_PATH.'/plugin_options/theme_options_functions.php');
	
	/* ------------------------------------------------------------------------ *
	 * Fields
	 * ------------------------------------------------------------------------ */	
	require_once(damojoPortfolio_PATH.'/plugin_options/theme_options_fields.php');
	
	//Get Portfolio Slugs & Names
		$damojoPortfolio_portfolios = get_option("damojoPortfolio_theme_portfolios_options");
		$damojoPortfolio_portfolio_slugs = array();
		$damojoPortfolio_portfolio_names = array();
		$j = 1;
		
		if(is_array($damojoPortfolio_portfolios)){
			foreach($damojoPortfolio_portfolios as $key => $value){
				if($j%2==0){
		            array_push($damojoPortfolio_portfolio_slugs,$value);
		            $j = 0 ;
		        }
		        else{
		            array_push($damojoPortfolio_portfolio_names,$value);
		        }
		    	$j++;
			}

			//Create Post Types
			$portfolio_counter = 0;
			foreach ( $damojoPortfolio_portfolio_slugs as $slug ){
					add_action('init', 'create_portfolio');
					register_taxonomy("category_".$slug, array($slug), array("hierarchical" => true, "label" => $damojoPortfolio_portfolio_names[$portfolio_counter++]." Categories", "query_var" => false, "singular_label" => "$slug Category", "rewrite" => false));
			}
		}		
		
	function create_portfolio() {
		global $damojoPortfolio_portfolio_slugs,$damojoPortfolio_portfolio_names;
		
		$portfolio_counter = 0;
		foreach ( $damojoPortfolio_portfolio_slugs as $slug ){
			$portfolio_args = array(
				'label' => "Portfolio '".$damojoPortfolio_portfolio_names[$portfolio_counter]."'",
				'singular_label' => $damojoPortfolio_portfolio_names[$portfolio_counter++],
				'public' => true,
				'show_ui' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'rewrite' => array('slug' => $slug, 'with_front' => false),
				'supports' => array('title', 'editor', 'thumbnail', 'author', 'comments', 'excerpt'),
				'taxonomies' => array('category_'.$slug,'post_tag') // this is IMPORTANT
			);
			register_post_type($slug,$portfolio_args);
		}
	}
	
	
	function portfolioSingleRedirect(){
	    global $wp_query;
	    $queryptype = $wp_query->query_vars["post_type"];
		global $damojoPortfolio_portfolio_slugs,$damojoPortfolio_portfolio_names;
		if(is_array($damojoPortfolio_portfolio_slugs))
			foreach ( $damojoPortfolio_portfolio_slugs as $slug ){
				if ($queryptype == $slug){
					if (have_posts()){
						global $pcat;
						$pcat = "category_".$slug;
						require(TEMPLATEPATH . '/single_portfolio.php');
						die();
					}else{
						$wp_query->is_404 = true;
					}
				}
			}
	}
	add_action("template_redirect", 'portfolioSingleRedirect'); 
?>