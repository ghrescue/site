<?php
	// Theme Options		
		$damojoPortfolio_theme_sections = array(
			array(
				'label' => 'Portfolios',
				'desc' => '',
				'sections' => array(
					'portfolios' => 'Portfolios'
				),
				'slug' => 'portfolios',
				'fields' => array(
								//Sidebars
									array(
										'id' => 'portfolio_builder',
										'label' => 'Portfolios',
										'description' => 'Which one?',
										'type' => 'portfolio_build',
										'section' => 'portfolios'
									)
				)
			),
			array(
				'label' => 'Portfolio Settings',
				'desc' => '',
				'sections' => array(
					'overview' => 'Overview',
					'entry' => 'Single Entry'
				),
				'fields' => array(
								//Overview
									array(
										'id' => 'portfoliolock',
										'label' => 'Default Lock Portfolio Thumb Heights (in px)',
										'description' => 'Set to the height you want to lock all portfolio thumbs to proportional. Leave blank for automatic proportional height. This value can be overwritten in the Portfolio Pages.',
										'type' => 'input',
										'section' => 'overview'
										
									),
									array(
										'id' => 'portfolioarchivesidebar',
										'label' => 'Category Page Layout',
										'description' => 'This setting determines the site layout of the portfolio category page.',
										'type' => 'radio',
										'options' => array("Full-Width"=>"Full-Width","Sidebar Left"=>"Sidebar Left","Sidebar Right"=>"Sidebar Right"),
										'section' => 'overview'
									),
                                    array(
										'id' => 'portfolioarchivesidebar_select',
										'label' => 'Sidebar (optional, see previous)',
										'description' => 'Which Sidebar to display with Category?',
										'type' => 'sidebar_mandotory_choose',
										'section' => 'overview'
									),
									array(
										'id' => 'portfolioarchivenumber',
										'label' => 'Category Items on Page',
										'description' => 'How many items to display on one Category page?',
										'type' => 'input',
										'section' => 'overview'
										
									),
								//Entry
									/*array(
										'id' => 'portfoliopostlayout',
										'label' => 'Post Layout',
										'description' => 'Select which layout you want for single portfolio posts.',
										'type' => 'radio',
										'options' => array("Large Media"=>"Large Media","Small Media"=>"Small Media"),
										'section' => 'entry'
									),*/
									array(
										'id' => 'portfoliorelated',
										'label' => 'Display Related Posts?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'entry'
									),
									array(
										'id' => 'portfoliopostinfo_nav',
										'label' => 'Display Navigation in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'entry'
									),
									array(
										'id' => 'portfoliopostinfo_date',
										'label' => 'Display Date in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'entry'
									),
									array(
										'id' => 'portfoliopostinfo_author',
										'label' => 'Display Author in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'entry'
									),
									array(
										'id' => 'portfoliopostinfo_category',
										'label' => 'Display Category in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'entry'
									),
									array(
										'id' => 'portfoliopostinfo_comments',
										'label' => 'Display Comments in Infoline?',
										'description' => 'Yes',
										'type' => 'checkbox',
										'section' => 'entry'
									),
									
							), 				
				'slug' => 'portfoliodef'
			),
		);
?>