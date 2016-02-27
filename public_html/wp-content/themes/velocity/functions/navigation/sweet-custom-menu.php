<?php
/*
Plugin Name: Sweet Custom Menu
Plugin URL: http://remicorson.com/sweet-custom-menu
Description: A little plugin to add attributes to WordPress menus
Version: 0.1
Author: Remi Corson
Author URI: http://remicorson.com
Contributors: corsonr
Text Domain: rc_scm
Domain Path: languages
*/

class rc_sweet_custom_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// load the plugin translation files
		add_action( 'init', array( $this, 'textdomain' ) );
		
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'rc_scm_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'rc_scm_update_custom_nav_fields'), 10, 3 );
		
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'rc_scm_edit_walker'), 10, 2 );

	} // end constructor
	
	
	/**
	 * Load the plugin's text domain
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'rc_scm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_add_custom_nav_fields( $menu_item ) {
	
	    $menu_item->megamenu = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
	    $menu_item->columnmegamenu = get_post_meta( $menu_item->ID, '_menu_item_columnmegamenu', true );
	    $menu_item->columnmegamenuwidth = get_post_meta( $menu_item->ID, '_menu_item_columnmegamenuwidth', true );
	    $menu_item->columnmegamenufullwidth = get_post_meta( $menu_item->ID, '_menu_item_columnmegamenufullwidth', true );
	    $menu_item->columntitlemegamenu = get_post_meta( $menu_item->ID, '_menu_item_columntitlemegamenu', true );
	    $menu_item->widgetareashow = get_post_meta( $menu_item->ID, '_menu_item_widgetareashow', true );
	    $menu_item->widgetarea = get_post_meta( $menu_item->ID, '_menu_item_widgetarea', true );
	    $menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
	    return $menu_item;
	    
	}
	
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	
	    // Check if element is properly sent
	    if ( isset($_REQUEST['menu-item-megamenu']) && is_array( $_REQUEST['menu-item-megamenu']) ) {
	        if(isset($_REQUEST['menu-item-megamenu'][$menu_item_db_id])) $megamenu_value = $_REQUEST['menu-item-megamenu'][$menu_item_db_id];
	        else $megamenu_value = '';
	        update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $megamenu_value );
	    }
	    else {
			$megamenu_value = '';
	        update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $megamenu_value );
		    
	    }
	    
	    if ( isset($_REQUEST['menu-item-columnmegamenu']) && is_array( $_REQUEST['menu-item-columnmegamenu']) ) {
	        if(isset($_REQUEST['menu-item-columnmegamenu'][$menu_item_db_id])) $columnmegamenu_value = $_REQUEST['menu-item-columnmegamenu'][$menu_item_db_id];
	        else $columnmegamenu_value = "";
	        update_post_meta( $menu_item_db_id, '_menu_item_columnmegamenu', $columnmegamenu_value );
	    }
	    else {
			$columnmegamenu_value = '';
	        update_post_meta( $menu_item_db_id, '_menu_item_columnmegamenu', $columnmegamenu_value );
		    
	    }
	    
	    if ( isset($_REQUEST['menu-item-columnmegamenuwidth']) && is_array( $_REQUEST['menu-item-columnmegamenuwidth']) ) {
	        if(isset($_REQUEST['menu-item-columnmegamenuwidth'][$menu_item_db_id])) $columnmegamenuwidth_value = $_REQUEST['menu-item-columnmegamenuwidth'][$menu_item_db_id];
	        else $columnmegamenuwidth_value = "";
	        update_post_meta( $menu_item_db_id, '_menu_item_columnmegamenuwidth', $columnmegamenuwidth_value );
	    }
	    
	    if ( isset($_REQUEST['menu-item-columntitlemegamenu']) && is_array( $_REQUEST['menu-item-columntitlemegamenu']) ) {
	        if(isset($_REQUEST['menu-item-columntitlemegamenu'][$menu_item_db_id])) $columntitlemegamenu_value = $_REQUEST['menu-item-columntitlemegamenu'][$menu_item_db_id];
	        else $columntitlemegamenu_value = "";
	        update_post_meta( $menu_item_db_id, '_menu_item_columntitlemegamenu', $columntitlemegamenu_value );
	    }
	    else {
	    	$columntitlemegamenu_value = "";
	        update_post_meta( $menu_item_db_id, '_menu_item_columntitlemegamenu', $columntitlemegamenu_value );
	    }

	    
	    if ( isset($_REQUEST['menu-item-widgetarea']) && is_array( $_REQUEST['menu-item-widgetarea']) ) {
	        if(isset($_REQUEST['menu-item-widgetarea'][$menu_item_db_id])) $widgetarea_value = $_REQUEST['menu-item-widgetarea'][$menu_item_db_id];
	        else $widgetarea_value = "";
	        update_post_meta( $menu_item_db_id, '_menu_item_widgetarea', $widgetarea_value );
	    }
	    
	    if ( isset($_REQUEST['menu-item-widgetareashow']) && is_array( $_REQUEST['menu-item-widgetareashow']) ) {
	        if(isset($_REQUEST['menu-item-widgetareashow'][$menu_item_db_id])) $widgetareashow_value = $_REQUEST['menu-item-widgetareashow'][$menu_item_db_id];
	        else $widgetareashow_value = "";
	        update_post_meta( $menu_item_db_id, '_menu_item_widgetareashow', $widgetareashow_value );
	    }
	    else{
	    	$widgetareashow_value = "";
	        update_post_meta( $menu_item_db_id, '_menu_item_widgetareashow', $widgetareashow_value );
	    }
	    
	    
	    if ( isset($_REQUEST['menu-item-icon']) && is_array( $_REQUEST['menu-item-icon']) ) {
	        if(isset($_REQUEST['menu-item-icon'][$menu_item_db_id])) $icon_value = $_REQUEST['menu-item-icon'][$menu_item_db_id];
	        else $icon_value = "";
	        update_post_meta( $menu_item_db_id, '_menu_item_icon', $icon_value );
	    }
	    
	}
	
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_edit_walker($walker,$menu_id) {
	
	    return 'Walker_Nav_Menu_Edit_Custom';
	    
	}

}

// instantiate plugin's class
$GLOBALS['sweet_custom_menu'] = new rc_sweet_custom_menu();


include_once( 'edit_custom_walker.php' );
include_once( 'custom_walker.php' );