<?php
// Array that holds all Portfolio Options
// class is used to trigger some jQuery action

$custom_portfolio_meta_fields = array(
		array(
			'label'	=> 'Hide Page Title?',
			'text' => 'On/Off',
			'desc'	=> 'Hides the page title',
			'id'	=> $prefix.'activate_page_title',
			'type'	=> 'checkbox',
			'default' => 'checked',
			'class' => 'tp_options content portfolio index home_page contact'
		),
		array(
			'label'	=> 'Post Title Text',
			'desc'	=> 'Alternative Head Title over the content, leave blank for no title',
			'id'	=> $prefix.'header_title',
			'type'	=> 'text',
			'class' => 'tp_options content portfolio index home_page contact'
		),
		array(
			'label'	=> 'Activate Sidebar',
			'text' => 'On/Off',
			'desc'	=> 'Use a sidebar or full view',
			'id'	=> $prefix.'activate_sidebar',
			'type'	=> 'checkbox',
			'default' => 'checked',
			'class' => ''
		),
		array(
			'label'	=> 'Select Sidebar',
			'desc'	=> 'Choose the Sidebar to this Post',
			'id'	=>  $prefix.'sidebar',
			'default' => 'Blog Sidebar',
			'type'	=> 'sidebar_list',
			'class' => 'sidebar'
		),
		array (
			'label'	=> 'Sidebar Orientation',
			'desc'	=> 'Places the sidebar left or right',
			'id'	=> $prefix.'sidebar_orientation',
			'type'	=> 'radio',
			'default' => 'right',
			'options' => array (
				'right' => array ('label' => 'Right','value'	=> 'right'),
				'left' => array ('label' => 'Left','value'	=> 'left')
			),
			'class' => 'sidebar'
		),
		array(
			'label'	=> 'Custom Background Image',
			'desc'	=> 'Insert the url of a custom background image uploaded with the media uploader, leave blank to use the default background',
			'id'	=> $prefix.'custom_background',
			'type'	=> 'image',
			'class' => 'tp_options content_page default portfolio index home_page contact clients'
		),
		array (
			'label'	=> 'Custom Background Type',
			'desc'	=> 'What type of background image?',
			'id'	=> $prefix.'custom_background_type',
			'type'	=> 'radio',
			'default' => 'tiled',
			'options' => array (
				'tiled' => array (
					'label' => 'Tiled',
					'value'	=> 'tiled'
				),
				'full' => array (
					'label' => 'Full',
					'value'	=> 'full'
				)
			),
			'class' => ''
		),
		array(
			'label'	=> 'Use Slider',
			'text' => 'On/Off',
			'desc'	=> 'Use a slider at the top of this page',
			'id'	=> $prefix.'activate_slider',
			'type'	=> 'checkbox',
			'default' => 'checked',
			'class' => 'tp_options home_page content_page default clients'
		),
		array(
			'label'	=> 'Select Slider',
			'desc'	=> 'Choose the Slider to this Page',
			'id'	=>  $prefix.'header_slider',
			'default' => '',
			'type'	=> 'slider_list',
			'class' => 'tp_options default slider_content'
		),
);

$custom_post_portfolio_type_meta_fields = array(
		array(
			'label'	=> 'Project Link',
			'desc'	=> 'Link for "Launch Project"<br> Button in Detail view or <br>open directly <br>(Example: <br>http://www.link.com)',
			'id'	=> $prefix.'launch_project',
			'type'	=> 'text_side',
			'class' => 'side superwide'
		),
		array (
			'label'	=> 'Project Link Type',
			'desc'	=> '',
			'id'	=> $prefix.'launch_project_type',
			'type'	=> 'radio',
			'default' => 'launch_button',
			'options' => array (
				'velocity_launch_button' => array ('label' => 'Launch Button','value'	=> 'launch_button'),
				'velocity_launch_button_external' => array ('label' => 'Direct Link New Tab','value'	=> 'external'),
				'velocity_launch_button_external_same' => array ('label' => 'Direct Link Same Tab','value'	=> 'internal')
			),
			'class' => 'side'
		),
		array (
			'label'	=> 'Post Type',
			'desc'	=> '',
			'id'	=> $prefix.'post_type',
			'type'	=> 'radio',
			'default' => 'image',
			'options' => array (
				'velocity_post_type_text' => array ('label' => 'Default','value'	=> 'text'),
				'velocity_post_type_image' => array ('label' => 'Image','value'	=> 'image'),
				'velocity_post_type_video' => array ('label' => 'Video','value'	=> 'video'),
				'velocity_post_type_slider' => array ('label' => 'Slider','value'	=> 'slider')

			),
			'class' => 'side'
		),
		array(
			'label'	=> '',
			'id' 	=> '',
			'desc'	=> 'For thumbnail images use <br>"featured image" option of WP',
			'type'	=> 'desc',
			'class' => ''
		),
		array (
			'label'	=> 'Video Type',
			'desc'	=> '',
			'id'	=> $prefix.'video_type',
			'type'	=> 'radio',
			'default' => '',
			'options' => array (
				'youtube' => array ('label' => 'Youtube','value'	=> 'youtube'),
				'vimeo' => array ('label' => 'Vimeo','value'	=> 'vimeo')
			),
			'class' => 'side post_type video youtube vimeo'
		),
		array(
			'label'	=> 'Youtube ID',
			'desc'	=> 'ID of the Youtube Video',
			'id'	=> $prefix.'youtube_id',
			'type'	=> 'text',
			'class' => 'side post_type youtube'
		),
		array(
			'label'	=> 'Vimeo ID',
			'desc'	=> 'ID of the Vimeo Video',
			'id'	=> $prefix.'vimeo_id',
			'type'	=> 'text',
			'class' => 'side post_type vimeo'
		),
		array(
			'label'	=> 'Video Width',
			'desc'	=> 'Width of the Video',
			'id'	=> $prefix.'video_width',
			'type'	=> 'text',
			'class' => 'side post_type youtube vimeo'
		),
		array(
			'label'	=> 'Video Height',
			'desc'	=> 'Height of the Video',
			'id'	=> $prefix.'video_height',
			'type'	=> 'text',
			'class' => 'side post_type youtube vimeo'
		),
		array(
			'label'	=> 'Select Slider',
			'desc'	=> 'Choose the Slider to this Page (make sure it is a FULLWIDTH Slider to fit the container)',
			'id'	=>  $prefix.'slider',
			'default' => '',
			'type'	=> 'slider_list',
			'class' => 'side post_type slider'
		),
		array(
			'label'	=> 'Hide Item Categories?',
			'text' => 'On/Off',
			'desc'	=> '',
			'id'	=> $prefix.'item_categories',
			'type'	=> 'checkbox',
			'default' => 'checked',
			'class' => 'side'
		),
		array (
			'label'	=> 'Item Features',
			'desc'	=> '',
			'id'	=> $prefix.'item_features',
			'type'	=> 'radio',
			'default' => 'link',
			'options' => array (
				//'velocity_feature_link_zoom' => array ('label' => 'Link & Lightbox','value'	=> 'linkzoom'),
				'velocity_feature_link' => array ('label' => 'Link only','value' => 'link'),
				'velocity_feature_zoom' => array ('label' => 'Lightbox only','value' => 'zoom')
			),
			'class' => 'side'
		)
);
?>