<?php
// Array that holds all Page Options
// class is used to trigger some jQuery action

$custom_page_meta_fields = array(
		array(
			'label'	=> 'Hide Page Title?',
			'text' => 'On/Off',
			'desc'	=> 'Hides the page title',
			'id'	=> $prefix.'activate_page_title',
			'type'	=> 'checkbox',
			'default' => 'checked',
			'class' => 'tp_options content_page blank default portfolio index index_small_media_preview index_large_media home_page contact clients'
		),
		array(
			'label'	=> 'Page Title Text',
			'desc'	=> 'Alternative Head Title over the content, leave blank for no title',
			'id'	=> $prefix.'header_title',
			'type'	=> 'text',
			'class' => 'tp_options content_page blank default portfolio index index_small_media_preview index_large_media home_page contact clients'
		),
		array(
			'label'	=> 'Activate Sidebar',
			'text' => 'On/Off',
			'desc'	=> 'Use a sidebar or full view',
			'id'	=> $prefix.'activate_sidebar',
			'type'	=> 'checkbox',
			'default' => 'checked',
			'class' => 'tp_options content_page blank default portfolio index index_small_media_preview index_large_media home_page contact clients'
		),
		array (
			'label'	=> 'Sidebar Orientation',
			'desc'	=> 'Places the sidebar left or right',
			'id'	=> $prefix.'sidebar_orientation',
			'type'	=> 'radio',
			'default' => 'right',
			'options' => array (
				'right' => array (
					'label' => 'Right',
					'value'	=> 'right'
				),
				'left' => array (
					'label' => 'Left',
					'value'	=> 'left'
				)
			),
			'class' => 'tp_options content_page blank default portfolio index index_small_media_preview index_large_media sidebar home_page contact clients'
		),
		array(
			'label'	=> 'Select Sidebar',
			'desc'	=> 'Choose the Sidebar to this Page',
			'id'	=>  $prefix.'sidebar',
			'default' => 'Blog Sidebar',
			'type'	=> 'sidebar_list',
			'class' => 'tp_options content_page default blank portfolio index index_small_media_preview index_large_media sidebar home_page contact clients'
		),
		array(
			'label'	=> 'Custom Background Image',
			'desc'	=> 'Insert the url of a custom background image uploaded with the media uploader, leave blank to use the default background',
			'id'	=> $prefix.'custom_background',
			'type'	=> 'image',
			'class' => 'tp_options content_page default blank portfolio index index_small_media_preview index_large_media home_page contact clients'
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
			'class' => 'tp_options home_page content_page blank default clients'
		),
		array(
			'label'	=> 'Select Slider',
			'desc'	=> 'Choose the Slider to this Page',
			'id'	=>  $prefix.'header_slider',
			'default' => '',
			'type'	=> 'slider_list',
			'class' => 'tp_options default blank slider_content'
		),
		array(
			'label'	=> 'Google Map Address',
			'desc'	=> 'Insert Data that works best with maps.google.com',
			'id'	=> $prefix.'gmapadress',
			'type'	=> 'text',
			'class' => 'tp_options contact'
		),
		array(
			'label'	=> 'Google Map Zoom',
			'desc'	=> 'Insert Zoom that works best with maps.google.com',
			'id'	=> $prefix.'gmapzoom',
			'type'	=> 'slider',
			'class' => 'tp_options contact'
		),
		array(
			'label'	=> 'Google Map Info Text',
			'desc'	=> 'The info text displayed when clicking the location',
			'id'	=> $prefix.'gmapinfo',
			'type'	=> 'text',
			'class' => 'tp_options contact'
		)
);


$custom_page_woocommerce_meta_fields = array(
		array(
			'label'	=> 'Shop Items per Page',
			'desc'	=> 'How many items to display on a shop page',
			'id'	=> $prefix.'shop_item_page_woo',
			'type'	=> 'text',
			'class' => ''
		),
		array(
			'label'	=> 'Hide Default Page Title?',
			'text' => 'On/Off',
			'desc'	=> 'Hides the page title',
			'id'	=> $prefix.'activate_page_title_woo',
			'type'	=> 'checkbox',
			'default' => 'checked',
			'class' => ''
		),
		array(
			'label'	=> 'Default Page Title Text',
			'desc'	=> 'Alternative Head Title over the content, leave blank for Product Name',
			'id'	=> $prefix.'header_title_woo',
			'type'	=> 'text',
			'class' => ''
		),
		array(
			'label'	=> 'Activate Default Sidebar',
			'text' => 'On/Off',
			'desc'	=> 'Use a sidebar or full view',
			'id'	=> $prefix.'activate_sidebar_woo',
			'type'	=> 'checkbox',
			'default' => 'checked',
			'class' => ''
		),
		array (
			'label'	=> 'Default Sidebar Orientation',
			'desc'	=> 'Places the sidebar left or right',
			'id'	=> $prefix.'sidebar_orientation_woo',
			'type'	=> 'radio',
			'default' => 'right',
			'options' => array (
				'right' => array (
					'label' => 'Right',
					'value'	=> 'right'
				),
				'left' => array (
					'label' => 'Left',
					'value'	=> 'left'
				)
			),
			'class' => 'sidebar_woo'
		),
		array(
			'label'	=> 'Select Default Sidebar',
			'desc'	=> 'Choose the Sidebar to this Page',
			'id'	=>  $prefix.'sidebar_woo',
			'default' => 'Blog Sidebar',
			'type'	=> 'sidebar_list',
			'class' => 'sidebar_woo'
		),
		array(
			'label'	=> 'Redirect to Shop Page after Add to cart',
			'text' => 'On/Off',
			'desc'	=> 'hitting "Continue Shopping" after adding an item to cart, redirects back to the shop page',
			'id'	=> $prefix.'activate_cart_woo',
			'type'	=> 'checkbox',
			'default' => '',
			'class' => ''
		),
);

$custom_page_portfolio_meta_fields = array(
		array(
			'label'	=> 'Select Portfolio',
			'desc'	=> 'Choose the Portfolio to display',
			'id'	=>  $prefix.'portfolio',
			'type'	=> 'portfolio_list',
			'class' => 'portfolio home_page'
			),
		array (
			'label'	=> 'Portfolio Display Style',
			'desc'	=> 'Decide if you want the Featured Images to be displayed in 3,4 or 5 Columns',
			'id'	=> $prefix.'portfolio_display_type',
			'type'	=> 'radio',
			'default' => '4',
			'options' => array (
				'3' => array (
					'label' => '3 Columns',
					'value'	=> '3'
				),
				'4' => array (
					'label' => '4 Columns',
					'value'	=> '4'
				),
				'5' => array (
					'label' => '5 Columns',
					'value'	=> '5'
				)

			),
			'class' => 'portfolio home_page'
			),
		array(
			'label'	=> 'Hide Portfolio Categories?',
			'text' => 'On/Off',
			'desc'	=> 'Hides the portfolio category filters',
			'id'	=> $prefix.'activate_portfolio_category',
			'type'	=> 'checkbox',
			'default' => 'checked',
			'class' => 'portfolio home_page'
		),
		array(
			'label'	=> 'Page Items',
			'desc'	=> 'How many Items to display per page?',
			'id'	=> $prefix.'portfolio_items',
			'type'	=> 'text',
			'class' => 'portfolio'
		),
		array(
			'label'	=> 'Lock Portfolio Thumb Heights (in px)',
			'desc'	=> 'Set to the height you want to lock all portfolio thumbs to. Leave blank for automatic proportional height.',
			'id'	=> $prefix.'portfolio_lock',
			'type'	=> 'text',
			'class' => 'portfolio'
		)
);

?>