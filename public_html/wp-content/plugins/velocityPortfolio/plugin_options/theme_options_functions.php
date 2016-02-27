<?php

/**
 * This function introduces the theme options into the 'Appearance' menu and into a top-level menu.
 */
 
function damojoPortfolio_example_theme_menu() {
	global $damojoPortfolio_theme_sections; 	// The Sections Array that defines the single Tabs later on
	
    add_menu_page(
		'Portfolio Manager',						// The value used to populate the browser's title bar when the menu page is active
		'Portfolio Manager',						// The text of the menu in the administrator's sidebar
		'administrator',						// What roles are able to access the menu
		'damojoPortfolio_theme_options',				// The ID used to bind submenu items to this menu 
		'damojoPortfolio_theme_display'//,			// The callback function used to render this menu
		//get_template_directory_uri()."/img/favicon.ico"
	);
  /*  
	add_theme_page(
		'Portfolio Manager', 					// The title to be displayed in the browser window for this page.
		'Portfolio Manager',						// The text to be displayed for this menu item
		'administrator',						// Which type of users can see this menu item
		'damojoPortfolio_theme_options',			// The unique ID - that is, the slug - for this menu item
		'damojoPortfolio_theme_display'				// The name of the function to call when rendering this menu's page
	);*/
} // end damojoPortfolio_example_theme_menu
add_action( 'admin_menu', 'damojoPortfolio_example_theme_menu' );


/**
 * Renders a simple page to display for the theme menu defined above.
 */
function damojoPortfolio_theme_display( $active_tab = '' ) {
	global $damojoPortfolio_theme_sections;
	
	//if(isset($_GET["settings-updated"]) && $_GET["settings-updated"] == "true") generateCSS();
	
?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<?php settings_errors(); ?>
		
		<?php 
			if( isset( $_GET[ 'tab' ] ) ) {
				$active_tab = $_GET[ 'tab' ];
				$active_slug = str_replace("damojoPortfolio_theme_","",$_GET[ 'tab' ]);
				$active_slug = str_replace("_options","",$active_slug);
			} else {
				$active_tab = str_replace("damojoPortfolio_theme_","",$_GET[ 'page' ]);
				$active_slug = str_replace("_options","",$active_tab);
				if($_GET[ 'page' ]=="damojoPortfolio_theme_options"){
					$active_tab = 'portfolios_options';
					$active_slug = 'portfolios';
				}
			}
		?>
		
		<h2 class="nav-tab-wrapper">
		<?php foreach($damojoPortfolio_theme_sections as $theme_section): ?>
			<a href="?page=damojoPortfolio_theme_options&tab=<?php echo $theme_section["slug"]; ?>_options" class="nav-tab <?php echo $active_tab == $theme_section["slug"].'_options' ? 'nav-tab-active' : ''; ?>"><?php echo $theme_section["label"]; ?></a>
		<?php endforeach;?>
		</h2>
		
		<form method="post" action="options.php">
			<?php
				settings_fields( 'damojoPortfolio_theme_'.$active_slug.'_options' );
				do_settings_sections( 'damojoPortfolio_theme_'.$active_slug.'_options' );				
				submit_button();
			?>
		</form>
		
	</div><!-- /.wrap -->
<?php
} // end damojoPortfolio_theme_display
?>
<?php
/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 
/**
 * Initializes the theme's display options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function damojoPortfolio_initialize_theme_options() {
	global $damojoPortfolio_theme_sections;
	foreach($damojoPortfolio_theme_sections as $theme_section):

		// If the theme options don't exist, create them.
		if( false == get_option( 'damojoPortfolio_theme_'.$theme_section["slug"].'_options' ) ) {	
			add_option( 'damojoPortfolio_theme_'.$theme_section["slug"].'_options' );
		} // end if
	
		// First, we register a section. This is necessary since all future options must belong to a 
		
		/*add_settings_section(
			'damojoPortfolio_'.$theme_section["slug"].'_settings_section',			// ID used to identify this section and with which to register options
			'General Options',													// Title to be displayed on the administration page
			'',																	// Callback used to render the description of the section
			'damojoPortfolio_theme_'.$theme_section["slug"].'_options'				// Page on which to add this section of options
		);*/
		if(isset($theme_section["sections"]) && is_array($theme_section["sections"])){
			foreach	($theme_section["sections"] as $sectionslug => $sectionname){
				add_settings_section(
					'damojoPortfolio_'.$sectionslug.'_settings_section',
					$sectionname,
					'',
					'damojoPortfolio_theme_'.$theme_section["slug"].'_options'
				);
			}
		}		
			
		
		
		if(!empty($theme_section["fields"]))
			foreach($theme_section["fields"] as $field):
				$options = empty($field["options"]) ? "" : $field["options"]; 
				if(!empty($field["size"])){
					$options = !empty($options) ? $options.",".$field["size"] : $field["size"]; 
				}
				 
				$section = !empty($field["section"]) ? $field["section"] : $theme_section["slug"];
				
				
				// Next, we'll introduce the fields for toggling the visibility of content elements.
				add_settings_field(	
					'damojoPortfolio_'.$field["id"],									// ID used to identify the field throughout the theme
					$field["label"],												// The label to the left of the option interface element
					'damojoPortfolio_'.$field["type"].'_callback',						// The name of the function responsible for rendering the option interface
					'damojoPortfolio_theme_'.$theme_section["slug"].'_options',			// The page on which this option will be displayed
					'damojoPortfolio_'.$section.'_settings_section',					// The name of the section to which this field belongs
					array(															// The array of arguments to pass to the callback
						'damojoPortfolio_'.$field["id"],'damojoPortfolio_theme_'.$theme_section["slug"].'_options',$field["description"],$options
					)
				);
			endforeach;
		
		// Finally, we register the fields with WordPress
		register_setting(
			'damojoPortfolio_theme_'.$theme_section["slug"].'_options',
			'damojoPortfolio_theme_'.$theme_section["slug"].'_options'
		);
	endforeach;
} // end damojoPortfolio_initialize_theme_options
add_action( 'admin_init', 'damojoPortfolio_initialize_theme_options' );

$themeurl = get_template_directory_uri();
?>