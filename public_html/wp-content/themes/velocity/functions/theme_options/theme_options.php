<?php
	// Theme Options		
		$theme_sections = array(
			array(
				'label' => 'Layout',
				'desc' => '',
				'sections' => array(
					'style' => 'Layout Style',
					'presets' => 'Presets'
				),
				'fields' => array(
								//Layout Style
									array(
										'id' => 'themelayout',
										'label' => __('Set theme layout to','velocity'),
										'description' => '',
										'type' => 'radio',
										'options' => array("Boxed"=>"Boxed","Full-Width"=>"Full-Width"),
										'section' => 'style'
									),
									array(
										'id' => 'custom_css',
										'label' => __('Custom CSS','velocity'),
										'description' => __('Insert some custom CSS here (fast hack for quick CSS changes without Child Theme)','velocity'),
										'size' => '',
										'type' => 'textarea',
										'section' => 'style'
									),
									array(
										'id' => 'preset',
										'label' => __('Set a Preset Style','velocity'),
										'description' => __('','velocity'),
										'size' => '',
										'type' => 'preset',
										'section' => 'presets'
									),
							), 				
				'slug' => 'layout'
			),
			array(
				'label' => 'Header',
				'desc' => '',
				'slug' => 'header',
				'sections' => array(
					'logo' => __('Logo','velocity'),
					'headertopline' => __('Top Header Line','velocity'),
					'pagetitleline' => __('Page Title Background (Parallax)','velocity'),
					'search' => __('Header Search','velocity'),
					'submenu' => __('Menu','velocity'),
					'slider' => __('Head Slider','velocity'),
					'headerstyle' => __('Head Style','velocity')
				),
				'fields' => array(
								//Logo	
									array(
										'id' => 'img_logo',
										'label' => __('Logo Image','velocity'),
										'description' => __('The logo used in the left header area.','velocity'),
										'type' => 'image',
										'section' => 'logo'
									),
									array(
										'id' => 'resp_img_logo',
										'label' => __('Logo Image Responsive','velocity'),
										'description' => __('Please create an image twice the size as the main logo and name it like the normal logo but with @2x as extension of the base name. 
															Assuming your logo is named "logo.png" please name it "logo@2x.png"','velocity'),
										'type' => 'image',
										'section' => 'logo'
									),  
									array(
										'id' => 'margin_top',
										'label' => 'Logo Margin Top',
										'description' => 'Margin above the Logo (in px)',
										'size' => '',
										'type' => 'input',
										'section' => 'logo'
									),
									array(
										'id' => 'margin_bottom',
										'label' => 'Logo Margin Bottom',
										'description' => 'Margin beneath the Logo (in px)',
										'size' => '',
										'type' => 'input',
										'section' => 'logo'
									),

								//Top Header Line
									array(
										'id' => 'headertopline',
										'label' => 'Header Top Line Active?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'headertopline'
									),
									
								// PAGE TITLE LINE	
									
									array(
										'id' => 'pagetitleimg',
										'label' => 'Page Title Background Image',
										'description' => 'Please create an image which should be used with the Parallax Effect under the Page Title.',
										'type' => 'image',
										'section' => 'pagetitleline'
									),
									array(
										'id' => 'pagetitleline_color',																				
										'label' => 'Page Title Background Color',
										'description' => 'The color used in Page Title Section',
										'type' => 'color',
										'section' => 'pagetitleline'																			
									),
									array(
										'id' => 'pagetitleline_color_opacity',
										'label' => 'Background Color Opacity',
										'description' => 'The Opacity of the background Color.',
										'size' => '',
										'type' => 'input',
										'section' => 'pagetitleline'
									),
									array(
										'id' => 'pagetitleline_color_style',
										'label' => __('Page Title Color Style','velocity'),
										'description' => 'What color scheme to use in the Pagetitle header?',
										'type' => 'radio',
										'options' => array("lightpagetitle"=>"Light","darkpagetitle"=>"Dark"),
										'section' => 'pagetitleline'
									),
									array(
										'id' => 'pagetitleline_parallaxspeed',
										'label' => 'Background Image Parallax Speed',
										'description' => 'from 1 - 200 where 1 is very fast and 200 very slow or even only fixed',
										'size' => '',
										'type' => 'input',
										'section' => 'pagetitleline'
									),
									array(
										'id' => 'header_style',
										'label' => 'Header Color Scheme',
										'description' => 'Display the header in a dark or light version',
										'type' => 'radio',
										'options' => array("lightheader"=>"Light","darkheader"=>"Dark"),
										'section' => 'headerstyle'
									),

								//Header Search
									array(
										'id' => 'headersearch',
										'label' => 'Header Search Active?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'search'
									),	
								//Menu  
									array(
										'id' => 'submenuwidth',
										'label' => 'Submenu Width',
										'description' => 'The width of the submenu items.',
										'size' => '',
										'type' => 'input',
										'section' => 'submenu'
									),
									array(
										'id' => 'stickymenu',
										'label' => 'Header Menu sticky?',
										'description' => 'Stick the Menu to the top of the window',
										'type' => 'checkbox',
										'section' => 'submenu'
									),
									
								//Main Color
									array(
										'id' => 'menu_color',
										'label' => 'Theme Active Menu Color',
										'description' => 'The highlight color used in Active Menu.',
										'type' => 'color',
										'section' => 'submenu'										
									),
																											
								//Home Slider
									array(
										'id' => 'slider_effects',
										'label' => 'Head Slider ScrollDown Effects',
										'description' => 'Effect that takes place with the Slider when scrolling down',
										'type' => 'radio',
										'options' => array("none"=>"None","fadeoutslider"=>"Fade Out"),
										'section' => 'slider'
									),
									array(
										'id' => 'parallax_effects',
										'label' => 'Head Slider with Layer Parallax effect?',
										'description' => 'Use the Parallax effect when scrolling down',
										'type' => 'checkbox',
										'section' => 'slider'
									),
									
				)
			),
			array(
				'label' => 'Footer',
				'desc' => '',
				'sections' => array(
					'footer' => 'Footers'
				),
				'fields' => array(
								//Footer
									array(
										'id' => 'footerwidgetsactive',
										'label' => 'Footer Active?',
										'description' => 'Display the Footer widget area',
										'type' => 'checkbox',
										'section' => 'footer'
									),
									array(
										'id' => 'footer_color',
										'label' => 'Footer Background Color',
										'description' => 'The color used as Footer Background.',
										'type' => 'color',
										'section' => 'footer'										
									),
									array(
										'id' => 'subfooterwidgetsactive',
										'label' => 'Sub Footer Active?',
										'description' => 'Display the Sub Footer widget area',
										'type' => 'checkbox',
										'section' => 'footer'
									),
									array(
										'id' => 'subfooterlefttext',
										'label' => 'Subfooter Left Column',
										'description' => 'Content of the left Column in the subfooter',
										'size' => '500',
										'type' => 'input',
										'section' => 'footer'
									),
									array(
										'id' => 'subfooterrighttext',
										'label' => 'Subfooter Right Column',
										'description' => 'Content of the right Column in the subfooter',
										'size' => '500',
										'type' => 'input',
										'section' => 'footer'
									),
									array(
										'id' => 'stickyfooter',
										'label' => 'Footer (Subfooter) Fixed?',
										'description' => 'Display the Footer without the layer effect',
										'type' => 'checkbox',
										'section' => 'footer'
									),
				), 				
				'slug' => 'footer'
			),
			array(
				'label' => 'Fonts',
				'desc' => '',
				'slug' => 'font',
				'sections' => array(
					'font' => 'Google Font',
				),
				'fields' => array(
								//Font									
									array(
										'id' => 'font_headlineurl',
										'label' => 'Google Web Font URL',
										'description' => '<strong>Optional</strong> - The google web font URL of the font used. Example: <strong>http://fonts.googleapis.com/css?family=PT+Sans:400,700</strong>',
										'size' => '500',
										'type' => 'input',
										'section' => 'font'
									),
									array(
										'id' => 'font_headlinefamily',
										'label' => 'Font Family',
										'description' => 'The font family string used in the CSS (could be google font from above). Example: \'PT Sans\', sans-serif',
										'size' => '500',
										'type' => 'input',
										'section' => 'font'
									),
				) 
			),
			array(
				'label' => 'Highlight Color',
				'desc' => '',
				'slug' => 'color',
				'sections' => array(
					'highlight' => 'Highlight'
				),
				'fields' => array(
								//Main Color
									array(
										'id' => 'color_highlight',
										'label' => 'Theme Main Highlight Color',
										'description' => 'The highlight color used in the theme. (Everything that is blue in the theme preview)',
										'type' => 'color',
										'section' => 'highlight'
										
									)
				)
			),
			array(
				'label' => 'Background',
				'desc' => '',
				'slug' => 'background',
				'sections' => array(
					'background' => 'Background Settings'
				),
				'fields' => array(
									array(
										'id' => 'img_bgdefault',
										'label' => 'Default Site Background Image',
										'description' => 'This is the default background image for the site. Leave blank for no background image.',
										'type' => 'image',
										'section' => 'background'
									),
									array(
										'id' => 'img_bgtype',
										'label' => 'Background Image Type',
										'description' => '',
										'type' => 'radio',
										'options' => array("tiled"=>"Tiled","full"=>"Full"),
										'section' => 'background'
									),
				)
			),
			array(
				'label' => 'Search & 404',
				'desc' => '',
				'slug' => 'search',
				'sections' => array(
					'search' => 'Search Settings',
					'fourofour' => '404 Page'
				),
				'fields' => array(
								//Main Color
									array(
										'id' => 'searchresultsnumber',
										'label' => 'Results Per Page',
										'description' => 'The number of maximum search results you want to see on one page.',
										'type' => 'input',
										'section' => 'search'
										
									),
									array(
										'id' => '404page',
										'label' => '404 Page',
										'description' => 'Set the page you want to redirect your view if he reaches a dead link.',
										'type' => 'selectpage',
										'section' => 'fourofour'
										
									)
				)
			),
			array(
				'label' => 'Sidebars',
				'desc' => '',
				'sections' => array(
					'sidebars' => 'Sidebars'
				),
				'fields' => array(
								//Sidebars
									array(
										'id' => 'sidebar_builder',
										'label' => 'Sidebars',
										'description' => 'Which one?',
										'type' => 'sidebar_build',
										'section' => 'sidebars'
									),
									array(
										'id' => 'sidebar_hide_mobile',
										'label' => 'Hide Sidebars on Phones?',
										'description' => 'Hide the sidebars when viewing on Mobile phones',
										'type' => 'checkbox',
										'section' => 'sidebars'
									),
				), 				
				'slug' => 'sidebars'
			),
			array(
				'label' => 'Blog Settings',
				'desc' => '',
				'sections' => array(
					'overview' => 'Overview',
					'single' => 'Single Post',
					'archive' => 'Category & Archive'
				),
				'fields' => array(
								//Overview
									array(
										'id' => 'blogoverviewpostlayout',
										'label' => 'Post Layout',
										'description' => 'Select which layout you want for single portfolio posts.',
										'type' => 'radio',
										'options' => array("Large Media"=>"Large Media","Small Media"=>"Small Media"),
										'section' => 'overview'
									),
									array(
										'id' => 'blogoverviewsingledate',
										'label' => 'Display Date Box?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'overview'
									),
									array(
										'id' => 'blogoverviewpostinfo_date',
										'label' => 'Display Date in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'overview'
									),
									array(
										'id' => 'blogoverviewpostinfo_author',
										'label' => 'Display Author in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'overview'
									),
									array(
										'id' => 'blogoverviewpostinfo_category',
										'label' => 'Display Category in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'overview'
									),
									array(
										'id' => 'blogoverviewpostinfo_comments',
										'label' => 'Display Comments in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'overview'
									),
									array(
										'id' => 'blogoverviewpostinfo_tags',
										'label' => 'Display Tags in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'overview'
									),
									array(
										'id' => 'blogoverview_excerpt',
										'label' => 'Excerpt Length',
										'description' => 'Number of Words to display (blank = WordPress standard)',
										'type' => 'input',
										'section' => 'overview'
										
									),
								//Entry
									array(
										'id' => 'blogpostlayout',
										'label' => 'Post Layout',
										'description' => 'Select which layout you want for single portfolio posts.',
										'type' => 'radio',
										'options' => array("Large Media"=>"Large Media","Small Media"=>"Small Media"),
										'section' => 'single'
									),
									array(
										'id' => 'blogrelated',
										'label' => 'Display Related Posts?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'single'
									),
									array(
										'id' => 'blogpostinfo_date',
										'label' => 'Display Date in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'single'
									),
									array(
										'id' => 'blogpostinfo_author',
										'label' => 'Display Author in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'single'
									),
									array(
										'id' => 'blogpostinfo_category',
										'label' => 'Display Category in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'single'
									),
									array(
										'id' => 'blogpostinfo_comments',
										'label' => 'Display Comments in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'single'
									),
									array(
										'id' => 'blogpostinfo_tags',
										'label' => 'Display Tags in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'single'
									),
									array(
										'id' => 'blogpostsidebar',
										'label' => 'Default Page Layout',
										'description' => 'This setting determines the default layout of the blog\'s posts.',
										'type' => 'radio',
										'options' => array("Full-Width"=>"Full-Width","left"=>"Sidebar Left","right"=>"Sidebar Right"),
										'section' => 'single'
									),
									array(
										'id' => 'blogpostsidebar_select',
										'label' => 'Default Sidebar (optional, see previous)',
										'description' => 'Default Sidebar to display if corresponding layout chosen above?',
										'type' => 'sidebar_mandotory_choose',
										'section' => 'single'
									),
								//Cat & Arch
									array(
										'id' => 'blogarchivesidebar',
										'label' => 'Page Layout',
										'description' => 'This setting determines the site layout of the blog\'s category & archive page.',
										'type' => 'radio',
										'options' => array("Full-Width"=>"Full-Width","Sidebar Left"=>"Sidebar Left","Sidebar Right"=>"Sidebar Right"),
										'section' => 'archive'
									),
									array(
										'id' => 'blogarchivesidebar_select',
										'label' => 'Sidebar (optional, see previous)',
										'description' => 'Which Sidebar to display with Category/Archive?',
										'type' => 'sidebar_mandotory_choose',
										'section' => 'archive'
									),
							), 				
				'slug' => 'blog'
			),
			array(
				'label' => 'Support/FAQ',
				'desc' => '',
				'slug' => 'support',
				'sections' => array('support' => ''),
				'fields' => array(
								//Support Text
									array(
										'id' => 'support',
										'label' => '<strong>Get Support</strong>',
										'description' => '<p>In case you face any problems feel free to contact us via the "Item Discussion" or the ticketing system 
															"ticksy"</p><p><a href="http://'.''.'damojo.ticksy.com"><img src="'.get_template_directory_uri().'/img/ticksy.png"></a></p>
															<style> #wpbody .submit {display:none;}</style>
															<ul>
																<li><a title="Install" href="http://www.goodwebtheme.com/velodoc/" target="_blank">Install Velocity</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/install-plugins/" target="_blank">Include Plugins</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/demo-contentslider/" target="_blank">Import Demo Content</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/settings-menu/" target="_blank">Set the Menu</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/front-page-blog/" target="_blank">Set the Front Page / Blog Page</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/theme-options/" target="_blank">Set the Theme Options</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/pages/" target="_blank">Pages Overview</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/blog/" target="_blank">How to build a Blog</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/portfolio-plugin/" target="_blank">How to install a Portfolio</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/portfolio-page-shortcode/" target="_blank">How to build a Portfolio Page</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/visual-composer/" target="_blank">Visual Composer Overview</a></li>
																<li><a href="http://kb.wpbakery.com/index.php?title=Visual_Composer" target="_blank">Visual Composer Detailed Documentation</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/localization-wordinglanguage/" target="_blank">How to Localize the Theme</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/widgets" target="_blank">How to create the Contact Widgets in Subheader and Footer?</a></li>
																<li><a href="http://www.goodwebtheme.com/velodoc/localization-wordinglanguage/" target="_blank">How to change the wording</a></li>
															</ul>
															',
										'type' => 'html',
										'section' => 'support'
									)

			)
		)
		);
?>