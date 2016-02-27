<?php

/**
 * This function introduces the theme options into the 'Appearance' menu and into a top-level menu.
 */
 
function velocity_example_theme_menu() {
	global $theme_sections; 	// The Sections Array that defines the single Tabs later on
	
	/*add_theme_page(
		'Velocity Theme', 					// The title to be displayed in the browser window for this page.
		'Velocity Theme',						// The text to be displayed for this menu item
		'administrator',						// Which type of users can see this menu item
		'velocity_theme_options',			// The unique ID - that is, the slug - for this menu item
		'velocity_theme_display'				// The name of the function to call when rendering this menu's page
	);*/
	
	add_menu_page(
		'Velocity Options',						// The value used to populate the browser's title bar when the menu page is active
		'Velocity Options',						// The text of the menu in the administrator's sidebar
		'administrator',						// What roles are able to access the menu
		'velocity_theme_options',				// The ID used to bind submenu items to this menu 
		'velocity_theme_display'//,			// The callback function used to render this menu
		//get_template_directory_uri()."/img/favicon.ico"
	);
	
	$firstrun = true;
	foreach($theme_sections as $theme_section):
		if(!$firstrun){
			add_submenu_page(
				'velocity_theme_menu',				// The ID of the top-level menu page to which this submenu item belongs
				$theme_section["label"],				// The value used to populate the browser's title bar when the menu page is active
				$theme_section["label"],				// The label of this submenu item displayed in the menu
				'administrator',						// What roles are able to access this submenu item
				'velocity_theme_'.$theme_section["slug"].'_options',	// The ID used to represent this submenu item
				create_function( null, 'velocity_theme_display( "'.$theme_section["slug"].'_options" );' )
			);
		}
		else{	
			add_submenu_page(
				'velocity_theme_menu',				// The ID of the top-level menu page to which this submenu item belongs
				$theme_section["label"],				// The value used to populate the browser's title bar when the menu page is active
				$theme_section["label"],				// The label of this submenu item displayed in the menu
				'administrator',						// What roles are able to access this submenu item
				'velocity_theme_'.$theme_section["slug"].'_options',	// The ID used to represent this submenu item
				'velocity_theme_display'				// The callback function used to render the options for this submenu item
			);
			$firstrun = false;
		}
	endforeach;
} // end velocity_example_theme_menu
add_action( 'admin_menu', 'velocity_example_theme_menu' );


/**
 * Renders a simple page to display for the theme menu defined above.
 */
function velocity_theme_display( $active_tab = '' ) {
	global $theme_sections;
	
	if(isset($_GET["settings-updated"]) && $_GET["settings-updated"] == "true") generateCSS();
	
?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2>Velocity Theme Options</h2>
		<?php settings_errors(); ?>
		
		<?php 
			if( isset( $_GET[ 'tab' ] ) ) {
				$active_tab = $_GET[ 'tab' ];
				$active_slug = str_replace("velocity_theme_","",$_GET[ 'tab' ]);
				$active_slug = str_replace("_options","",$active_slug);
			} else {
				$active_tab = str_replace("velocity_theme_","",$_GET[ 'page' ]);
				$active_slug = str_replace("_options","",$active_tab);
				if($_GET[ 'page' ]=="velocity_theme_menu"){
					$active_tab = 'layout_options';
					$active_slug = 'layout';
				}
			}
		?>
		
		<h2 class="nav-tab-wrapper">
		<?php foreach($theme_sections as $theme_section): ?>
			<a href="?page=velocity_theme_options&tab=<?php echo $theme_section["slug"]; ?>_options" class="nav-tab <?php echo $active_tab == $theme_section["slug"].'_options' ? 'nav-tab-active' : ''; ?>"><?php echo $theme_section["label"]; ?></a>
		<?php endforeach;?>
		</h2>
		
		<form method="post" action="options.php">
			<?php
				settings_fields( 'velocity_theme_'.$active_slug.'_options' );
				do_settings_sections( 'velocity_theme_'.$active_slug.'_options' );				
				submit_button();
			?>
		</form>
		
	</div><!-- /.wrap -->
<?php
} // end velocity_theme_display
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
function velocity_initialize_theme_options() {
	global $theme_sections;
	foreach($theme_sections as $theme_section):

		// If the theme options don't exist, create them.
		if( false == get_option( 'velocity_theme_'.$theme_section["slug"].'_options' ) ) {	
			add_option( 'velocity_theme_'.$theme_section["slug"].'_options' );
		} // end if
	
		// First, we register a section. This is necessary since all future options must belong to a 
		
		/*add_settings_section(
			'velocity_'.$theme_section["slug"].'_settings_section',			// ID used to identify this section and with which to register options
			'General Options',													// Title to be displayed on the administration page
			'',																	// Callback used to render the description of the section
			'velocity_theme_'.$theme_section["slug"].'_options'				// Page on which to add this section of options
		);*/
		if(isset($theme_section["sections"]) && is_array($theme_section["sections"])){
			foreach	($theme_section["sections"] as $sectionslug => $sectionname){
				add_settings_section(
					'velocity_'.$sectionslug.'_settings_section',
					$sectionname,
					'',
					'velocity_theme_'.$theme_section["slug"].'_options'
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
					'velocity_'.$field["id"],									// ID used to identify the field throughout the theme
					$field["label"],												// The label to the left of the option interface element
					'velocity_'.$field["type"].'_callback',						// The name of the function responsible for rendering the option interface
					'velocity_theme_'.$theme_section["slug"].'_options',			// The page on which this option will be displayed
					'velocity_'.$section.'_settings_section',					// The name of the section to which this field belongs
					array(															// The array of arguments to pass to the callback
						'velocity_'.$field["id"],'velocity_theme_'.$theme_section["slug"].'_options',$field["description"],$options
					)
				);
			endforeach;
		
		// Finally, we register the fields with WordPress
		register_setting(
			'velocity_theme_'.$theme_section["slug"].'_options',
			'velocity_theme_'.$theme_section["slug"].'_options'
		);
	endforeach;
} // end velocity_initialize_theme_options
add_action( 'admin_init', 'velocity_initialize_theme_options' );

$themeurl = get_template_directory_uri();
?>