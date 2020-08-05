<?php
/**
 * Builds our Customizer controls.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'customize_register', 'matangi_set_customizer_helpers', 1 );
/**
 * Set up helpers early so they're always available.
 * Other modules might need access to them at some point.
 *
 */
function matangi_set_customizer_helpers( $wp_customize ) {
	// Load helpers
	require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';
}

if ( ! function_exists( 'matangi_customize_register' ) ) {
	add_action( 'customize_register', 'matangi_customize_register' );
	/**
	 * Add our base options to the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function matangi_customize_register( $wp_customize ) {
		// Get our default values
		$defaults = matangi_get_defaults();

		// Load helpers
		require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';

		if ( $wp_customize->get_control( 'blogdescription' ) ) {
			$wp_customize->get_control('blogdescription')->priority = 3;
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		}

		if ( $wp_customize->get_control( 'blogname' ) ) {
			$wp_customize->get_control('blogname')->priority = 1;
			$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		}

		if ( $wp_customize->get_control( 'custom_logo' ) ) {
			$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
		}

		// Add control types so controls can be built using JS
		if ( method_exists( $wp_customize, 'register_control_type' ) ) {
			$wp_customize->register_control_type( 'Matangi_Customize_Misc_Control' );
			$wp_customize->register_control_type( 'Matangi_Range_Slider_Control' );
		}

		// Add upsell section type
		if ( method_exists( $wp_customize, 'register_section_type' ) ) {
			$wp_customize->register_section_type( 'Matangi_Upsell_Section' );
		}

		// Add selective refresh to site title and description
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector' => '.main-title a',
				'render_callback' => 'matangi_customize_partial_blogname',
			) );

			$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector' => '.site-description',
				'render_callback' => 'matangi_customize_partial_blogdescription',
			) );
		}

		// Remove title
		$wp_customize->add_setting(
			'matangi_settings[hide_title]',
			array(
				'default' => $defaults['hide_title'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'matangi_settings[hide_title]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Hide site title', 'matangi' ),
				'section' => 'title_tagline',
				'priority' => 2
			)
		);

		// Remove tagline
		$wp_customize->add_setting(
			'matangi_settings[hide_tagline]',
			array(
				'default' => $defaults['hide_tagline'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'matangi_settings[hide_tagline]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Hide site tagline', 'matangi' ),
				'section' => 'title_tagline',
				'priority' => 4
			)
		);

		$wp_customize->add_setting(
			'matangi_settings[retina_logo]',
			array(
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'matangi_settings[retina_logo]',
				array(
					'label' => __( 'Retina Logo', 'matangi' ),
					'section' => 'title_tagline',
					'settings' => 'matangi_settings[retina_logo]',
					'active_callback' => 'matangi_has_custom_logo_callback'
				)
			)
		);

		$wp_customize->add_setting(
			'matangi_settings[side_inside_color]', array(
				'default' => $defaults['side_inside_color'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'matangi_settings[side_inside_color]',
				array(
					'label' => __( 'Inside padding', 'matangi' ),
					'section' => 'colors',
					'settings' => 'matangi_settings[side_inside_color]',
					'active_callback' => 'matangi_is_side_padding_active',
				)
			)
		);

		$wp_customize->add_setting(
			'matangi_settings[text_color]', array(
				'default' => $defaults['text_color'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'matangi_settings[text_color]',
				array(
					'label' => __( 'Text Color', 'matangi' ),
					'section' => 'colors',
					'settings' => 'matangi_settings[text_color]'
				)
			)
		);

		$wp_customize->add_setting(
			'matangi_settings[link_color]', array(
				'default' => $defaults['link_color'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'matangi_settings[link_color]',
				array(
					'label' => __( 'Link Color', 'matangi' ),
					'section' => 'colors',
					'settings' => 'matangi_settings[link_color]'
				)
			)
		);

		$wp_customize->add_setting(
			'matangi_settings[link_color_hover]', array(
				'default' => $defaults['link_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'matangi_settings[link_color_hover]',
				array(
					'label' => __( 'Link Color Hover', 'matangi' ),
					'section' => 'colors',
					'settings' => 'matangi_settings[link_color_hover]'
				)
			)
		);

		$wp_customize->add_setting(
			'matangi_settings[link_color_visited]', array(
				'default' => $defaults['link_color_visited'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_hex_color',
				'transport' => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'matangi_settings[link_color_visited]',
				array(
					'label' => __( 'Link Color Visited', 'matangi' ),
					'section' => 'colors',
					'settings' => 'matangi_settings[link_color_visited]'
				)
			)
		);

		if ( ! function_exists( 'matangi_colors_customize_register' ) && ! defined( 'MATANGI_PREMIUM_VERSION' ) ) {
			$wp_customize->add_control(
				new Matangi_Customize_Misc_Control(
					$wp_customize,
					'colors_get_addon_desc',
					array(
						'section' => 'colors',
						'type' => 'addon',
						'label' => __( 'More info', 'matangi' ),
						'description' => __( 'More colors are available in Matangi premium version. Visit wpkoi.com for more info.', 'matangi' ),
						'url' => esc_url( MATANGI_THEME_URL ),
						'priority' => 30,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);
		}

		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'matangi_layout_panel' ) ) {
				$wp_customize->add_panel( 'matangi_layout_panel', array(
					'priority' => 25,
					'title' => __( 'Layout', 'matangi' ),
				) );
			}
		}

		// Add Layout section
		$wp_customize->add_section(
			'matangi_layout_container',
			array(
				'title' => __( 'Container', 'matangi' ),
				'priority' => 10,
				'panel' => 'matangi_layout_panel'
			)
		);

		// Container width
		$wp_customize->add_setting(
			'matangi_settings[container_width]',
			array(
				'default' => $defaults['container_width'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_integer',
				'transport' => 'postMessage'
			)
		);

		$wp_customize->add_control(
			new Matangi_Range_Slider_Control(
				$wp_customize,
				'matangi_settings[container_width]',
				array(
					'type' => 'matangi-range-slider',
					'label' => __( 'Container Width', 'matangi' ),
					'section' => 'matangi_layout_container',
					'settings' => array(
						'desktop' => 'matangi_settings[container_width]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 700,
							'max' => 2000,
							'step' => 5,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'priority' => 0,
				)
			)
		);

		// Add Top Bar section
		$wp_customize->add_section(
			'matangi_top_bar',
			array(
				'title' => __( 'Top Bar', 'matangi' ),
				'priority' => 15,
				'panel' => 'matangi_layout_panel',
			)
		);

		// Add Top Bar width
		$wp_customize->add_setting(
			'matangi_settings[top_bar_width]',
			array(
				'default' => $defaults['top_bar_width'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add Top Bar width control
		$wp_customize->add_control(
			'matangi_settings[top_bar_width]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar Width', 'matangi' ),
				'section' => 'matangi_top_bar',
				'choices' => array(
					'full' => __( 'Full', 'matangi' ),
					'contained' => __( 'Contained', 'matangi' )
				),
				'settings' => 'matangi_settings[top_bar_width]',
				'priority' => 5,
				'active_callback' => 'matangi_is_top_bar_active',
			)
		);

		// Add Top Bar inner width
		$wp_customize->add_setting(
			'matangi_settings[top_bar_inner_width]',
			array(
				'default' => $defaults['top_bar_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add Top Bar width control
		$wp_customize->add_control(
			'matangi_settings[top_bar_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar Inner Width', 'matangi' ),
				'section' => 'matangi_top_bar',
				'choices' => array(
					'full' => __( 'Full', 'matangi' ),
					'contained' => __( 'Contained', 'matangi' )
				),
				'settings' => 'matangi_settings[top_bar_inner_width]',
				'priority' => 10,
				'active_callback' => 'matangi_is_top_bar_active',
			)
		);

		// Add top bar alignment
		$wp_customize->add_setting(
			'matangi_settings[top_bar_alignment]',
			array(
				'default' => $defaults['top_bar_alignment'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'matangi_settings[top_bar_alignment]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar Alignment', 'matangi' ),
				'section' => 'matangi_top_bar',
				'choices' => array(
					'left' => __( 'Left', 'matangi' ),
					'center' => __( 'Center', 'matangi' ),
					'right' => __( 'Right', 'matangi' )
				),
				'settings' => 'matangi_settings[top_bar_alignment]',
				'priority' => 15,
				'active_callback' => 'matangi_is_top_bar_active',
			)
		);

		// Add Header section
		$wp_customize->add_section(
			'matangi_layout_header',
			array(
				'title' => __( 'Header', 'matangi' ),
				'priority' => 20,
				'panel' => 'matangi_layout_panel'
			)
		);

		// Add Header Layout setting
		$wp_customize->add_setting(
			'matangi_settings[header_layout_setting]',
			array(
				'default' => $defaults['header_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add Header Layout control
		$wp_customize->add_control(
			'matangi_settings[header_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Header Width', 'matangi' ),
				'section' => 'matangi_layout_header',
				'choices' => array(
					'fluid-header' => __( 'Full', 'matangi' ),
					'contained-header' => __( 'Contained', 'matangi' )
				),
				'settings' => 'matangi_settings[header_layout_setting]',
				'priority' => 5
			)
		);

		// Add Inside Header Layout setting
		$wp_customize->add_setting(
			'matangi_settings[header_inner_width]',
			array(
				'default' => $defaults['header_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add Header Layout control
		$wp_customize->add_control(
			'matangi_settings[header_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Header Width', 'matangi' ),
				'section' => 'matangi_layout_header',
				'choices' => array(
					'contained' => __( 'Contained', 'matangi' ),
					'full-width' => __( 'Full', 'matangi' )
				),
				'settings' => 'matangi_settings[header_inner_width]',
				'priority' => 6
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'matangi_settings[header_alignment_setting]',
			array(
				'default' => $defaults['header_alignment_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'matangi_settings[header_alignment_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Header Alignment', 'matangi' ),
				'section' => 'matangi_layout_header',
				'choices' => array(
					'left' => __( 'Left', 'matangi' ),
					'center' => __( 'Center', 'matangi' ),
					'right' => __( 'Right', 'matangi' )
				),
				'settings' => 'matangi_settings[header_alignment_setting]',
				'priority' => 10
			)
		);

		$wp_customize->add_section(
			'matangi_layout_navigation',
			array(
				'title' => __( 'Primary Navigation', 'matangi' ),
				'priority' => 30,
				'panel' => 'matangi_layout_panel'
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'matangi_settings[nav_layout_setting]',
			array(
				'default' => $defaults['nav_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'matangi_settings[nav_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Width', 'matangi' ),
				'section' => 'matangi_layout_navigation',
				'choices' => array(
					'fluid-nav' => __( 'Full', 'matangi' ),
					'contained-nav' => __( 'Contained', 'matangi' )
				),
				'settings' => 'matangi_settings[nav_layout_setting]',
				'priority' => 15
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'matangi_settings[nav_inner_width]',
			array(
				'default' => $defaults['nav_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'matangi_settings[nav_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Navigation Width', 'matangi' ),
				'section' => 'matangi_layout_navigation',
				'choices' => array(
					'contained' => __( 'Contained', 'matangi' ),
					'full-width' => __( 'Full', 'matangi' )
				),
				'settings' => 'matangi_settings[nav_inner_width]',
				'priority' => 16
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'matangi_settings[nav_alignment_setting]',
			array(
				'default' => $defaults['nav_alignment_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'matangi_settings[nav_alignment_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Alignment', 'matangi' ),
				'section' => 'matangi_layout_navigation',
				'choices' => array(
					'left' => __( 'Left', 'matangi' ),
					'center' => __( 'Center', 'matangi' ),
					'right' => __( 'Right', 'matangi' )
				),
				'settings' => 'matangi_settings[nav_alignment_setting]',
				'priority' => 20
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'matangi_settings[nav_position_setting]',
			array(
				'default' => $defaults['nav_position_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => ( '' !== matangi_get_setting( 'nav_position_setting' ) ) ? 'postMessage' : 'refresh'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'matangi_settings[nav_position_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Location', 'matangi' ),
				'section' => 'matangi_layout_navigation',
				'choices' => array(
					'nav-below-header' => __( 'Below Header', 'matangi' ),
					'nav-above-header' => __( 'Above Header', 'matangi' ),
					'nav-float-right' => __( 'Float Right', 'matangi' ),
					'nav-float-left' => __( 'Float Left', 'matangi' ),
					'nav-left-sidebar' => __( 'Left Sidebar', 'matangi' ),
					'nav-right-sidebar' => __( 'Right Sidebar', 'matangi' ),
					'' => __( 'No Navigation', 'matangi' )
				),
				'settings' => 'matangi_settings[nav_position_setting]',
				'priority' => 22
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'matangi_settings[nav_dropdown_type]',
			array(
				'default' => $defaults['nav_dropdown_type'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'matangi_settings[nav_dropdown_type]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Dropdown', 'matangi' ),
				'section' => 'matangi_layout_navigation',
				'choices' => array(
					'hover' => __( 'Hover', 'matangi' ),
					'click' => __( 'Click - Menu Item', 'matangi' ),
					'click-arrow' => __( 'Click - Arrow', 'matangi' )
				),
				'settings' => 'matangi_settings[nav_dropdown_type]',
				'priority' => 22
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'matangi_settings[nav_search]',
			array(
				'default' => $defaults['nav_search'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'matangi_settings[nav_search]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Search', 'matangi' ),
				'section' => 'matangi_layout_navigation',
				'choices' => array(
					'enable' => __( 'Enable', 'matangi' ),
					'disable' => __( 'Disable', 'matangi' )
				),
				'settings' => 'matangi_settings[nav_search]',
				'priority' => 23
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'matangi_settings[nav_effect]',
			array(
				'default' => $defaults['nav_effect'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'matangi_settings[nav_effect]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Effects', 'matangi' ),
				'section' => 'matangi_layout_navigation',
				'choices' => array(
					'none' => __( 'None', 'matangi' ),
					'stylea' => __( 'Brackets', 'matangi' ),
					'styleb' => __( 'Borders', 'matangi' ),
					'stylec' => __( 'Switch', 'matangi' ),
					'styled' => __( 'Fall down', 'matangi' )
				),
				'settings' => 'matangi_settings[nav_effect]',
				'priority' => 24
			)
		);

		// Add content setting
		$wp_customize->add_setting(
			'matangi_settings[content_layout_setting]',
			array(
				'default' => $defaults['content_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'matangi_settings[content_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Content Layout', 'matangi' ),
				'section' => 'matangi_layout_container',
				'choices' => array(
					'separate-containers' => __( 'Separate Containers', 'matangi' ),
					'one-container' => __( 'One Container', 'matangi' )
				),
				'settings' => 'matangi_settings[content_layout_setting]',
				'priority' => 25
			)
		);

		$wp_customize->add_section(
			'matangi_layout_sidecontent',
			array(
				'title' => __( 'Fixed Side Content', 'matangi' ),
				'priority' => 39,
				'panel' => 'matangi_layout_panel'
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[fixed_side_content]',
			array(
				'default' => $defaults['fixed_side_content'],
				'type' => 'option',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[fixed_side_content]',
			array(
				'type' 		 => 'textarea',
				'label'      => __( 'Fixed Side Content', 'matangi' ),
				'description'=> __( 'Content that You want to display fixed on the left.', 'matangi' ),
				'section'    => 'matangi_layout_sidecontent',
				'settings'   => 'matangi_settings[fixed_side_content]',
			)
		);

		$wp_customize->add_section(
			'matangi_layout_sidebars',
			array(
				'title' => __( 'Sidebars', 'matangi' ),
				'priority' => 40,
				'panel' => 'matangi_layout_panel'
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'matangi_settings[layout_setting]',
			array(
				'default' => $defaults['layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'matangi_settings[layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Sidebar Layout', 'matangi' ),
				'section' => 'matangi_layout_sidebars',
				'choices' => array(
					'left-sidebar' => __( 'Sidebar / Content', 'matangi' ),
					'right-sidebar' => __( 'Content / Sidebar', 'matangi' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'matangi' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'matangi' ),
					'both-left' => __( 'Sidebar / Sidebar / Content', 'matangi' ),
					'both-right' => __( 'Content / Sidebar / Sidebar', 'matangi' )
				),
				'settings' => 'matangi_settings[layout_setting]',
				'priority' => 30
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'matangi_settings[blog_layout_setting]',
			array(
				'default' => $defaults['blog_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'matangi_settings[blog_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Blog Sidebar Layout', 'matangi' ),
				'section' => 'matangi_layout_sidebars',
				'choices' => array(
					'left-sidebar' => __( 'Sidebar / Content', 'matangi' ),
					'right-sidebar' => __( 'Content / Sidebar', 'matangi' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'matangi' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'matangi' ),
					'both-left' => __( 'Sidebar / Sidebar / Content', 'matangi' ),
					'both-right' => __( 'Content / Sidebar / Sidebar', 'matangi' )
				),
				'settings' => 'matangi_settings[blog_layout_setting]',
				'priority' => 35
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'matangi_settings[single_layout_setting]',
			array(
				'default' => $defaults['single_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'matangi_settings[single_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Single Post Sidebar Layout', 'matangi' ),
				'section' => 'matangi_layout_sidebars',
				'choices' => array(
					'left-sidebar' => __( 'Sidebar / Content', 'matangi' ),
					'right-sidebar' => __( 'Content / Sidebar', 'matangi' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'matangi' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'matangi' ),
					'both-left' => __( 'Sidebar / Sidebar / Content', 'matangi' ),
					'both-right' => __( 'Content / Sidebar / Sidebar', 'matangi' )
				),
				'settings' => 'matangi_settings[single_layout_setting]',
				'priority' => 36
			)
		);

		$wp_customize->add_section(
			'matangi_layout_footer',
			array(
				'title' => __( 'Footer', 'matangi' ),
				'priority' => 50,
				'panel' => 'matangi_layout_panel'
			)
		);

		// Add footer setting
		$wp_customize->add_setting(
			'matangi_settings[footer_layout_setting]',
			array(
				'default' => $defaults['footer_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'matangi_settings[footer_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Footer Width', 'matangi' ),
				'section' => 'matangi_layout_footer',
				'choices' => array(
					'fluid-footer' => __( 'Full', 'matangi' ),
					'contained-footer' => __( 'Contained', 'matangi' )
				),
				'settings' => 'matangi_settings[footer_layout_setting]',
				'priority' => 40
			)
		);

		// Add footer setting
		$wp_customize->add_setting(
			'matangi_settings[footer_widgets_inner_width]',
			array(
				'default' => $defaults['footer_widgets_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
			)
		);

		// Add content control
		$wp_customize->add_control(
			'matangi_settings[footer_widgets_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Footer Widgets Width', 'matangi' ),
				'section' => 'matangi_layout_footer',
				'choices' => array(
					'contained' => __( 'Contained', 'matangi' ),
					'full-width' => __( 'Full', 'matangi' )
				),
				'settings' => 'matangi_settings[footer_widgets_inner_width]',
				'priority' => 41
			)
		);

		// Add footer setting
		$wp_customize->add_setting(
			'matangi_settings[footer_inner_width]',
			array(
				'default' => $defaults['footer_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'matangi_settings[footer_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Footer Width', 'matangi' ),
				'section' => 'matangi_layout_footer',
				'choices' => array(
					'contained' => __( 'Contained', 'matangi' ),
					'full-width' => __( 'Full', 'matangi' )
				),
				'settings' => 'matangi_settings[footer_inner_width]',
				'priority' => 41
			)
		);

		// Add footer widget setting
		$wp_customize->add_setting(
			'matangi_settings[footer_widget_setting]',
			array(
				'default' => $defaults['footer_widget_setting'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add footer widget control
		$wp_customize->add_control(
			'matangi_settings[footer_widget_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Footer Widgets', 'matangi' ),
				'section' => 'matangi_layout_footer',
				'choices' => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5'
				),
				'settings' => 'matangi_settings[footer_widget_setting]',
				'priority' => 45
			)
		);

		// Add footer widget setting
		$wp_customize->add_setting(
			'matangi_settings[footer_bar_alignment]',
			array(
				'default' => $defaults['footer_bar_alignment'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices',
				'transport' => 'postMessage'
			)
		);

		// Add footer widget control
		$wp_customize->add_control(
			'matangi_settings[footer_bar_alignment]',
			array(
				'type' => 'select',
				'label' => __( 'Footer Bar Alignment', 'matangi' ),
				'section' => 'matangi_layout_footer',
				'choices' => array(
					'left' => __( 'Left','matangi' ),
					'center' => __( 'Center','matangi' ),
					'right' => __( 'Right','matangi' )
				),
				'settings' => 'matangi_settings[footer_bar_alignment]',
				'priority' => 47,
				'active_callback' => 'matangi_is_footer_bar_active'
			)
		);

		// Add back to top setting
		$wp_customize->add_setting(
			'matangi_settings[back_to_top]',
			array(
				'default' => $defaults['back_to_top'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_choices'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'matangi_settings[back_to_top]',
			array(
				'type' => 'select',
				'label' => __( 'Back to Top Button', 'matangi' ),
				'section' => 'matangi_layout_footer',
				'choices' => array(
					'enable' => __( 'Enable', 'matangi' ),
					'' => __( 'Disable', 'matangi' )
				),
				'settings' => 'matangi_settings[back_to_top]',
				'priority' => 50
			)
		);

		// Add Layout section
		$wp_customize->add_section(
			'matangi_blog_section',
			array(
				'title' => __( 'Blog', 'matangi' ),
				'priority' => 55,
				'panel' => 'matangi_layout_panel'
			)
		);

		$wp_customize->add_setting(
			'matangi_settings[blog_header_image]',
			array(
				'default' => $defaults['blog_header_image'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'matangi_settings[blog_header_image]',
				array(
					'label' => __( 'Blog Header image', 'matangi' ),
					'section' => 'matangi_blog_section',
					'settings' => 'matangi_settings[blog_header_image]',
					'description' => __( 'Recommended size: 1520*660px', 'matangi' )
				)
			)
		);

		// Blog header texts
		$wp_customize->add_setting(
			'matangi_settings[blog_header_title]',
			array(
				'default' => $defaults['blog_header_title'],
				'type' => 'option',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[blog_header_title]',
			array(
				'type' 		 => 'textarea',
				'label'      => __( 'Blog Header title', 'matangi' ),
				'section'    => 'matangi_blog_section',
				'settings'   => 'matangi_settings[blog_header_title]',
				'description' => __( 'HTML allowed.', 'matangi' )
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[blog_header_text]',
			array(
				'default' => $defaults['blog_header_text'],
				'type' => 'option',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[blog_header_text]',
			array(
				'type' 		 => 'textarea',
				'label'      => __( 'Blog Header text', 'matangi' ),
				'section'    => 'matangi_blog_section',
				'settings'   => 'matangi_settings[blog_header_text]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[blog_header_button_text]',
			array(
				'default' => $defaults['blog_header_button_text'],
				'type' => 'option',
				'sanitize_callback' => 'esc_html',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[blog_header_button_text]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Blog Header button text', 'matangi' ),
				'section'    => 'matangi_blog_section',
				'settings'   => 'matangi_settings[blog_header_button_text]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[blog_header_button_url]',
			array(
				'default' => $defaults['blog_header_button_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[blog_header_button_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Blog Header button url', 'matangi' ),
				'section'    => 'matangi_blog_section',
				'settings'   => 'matangi_settings[blog_header_button_url]',
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'matangi_settings[post_content]',
			array(
				'default' => $defaults['post_content'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_blog_excerpt'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'blog_content_control',
			array(
				'type' => 'select',
				'label' => __( 'Content Type', 'matangi' ),
				'section' => 'matangi_blog_section',
				'choices' => array(
					'full' => __( 'Full', 'matangi' ),
					'excerpt' => __( 'Excerpt', 'matangi' )
				),
				'settings' => 'matangi_settings[post_content]',
				'priority' => 10
			)
		);

		if ( ! function_exists( 'matangi_blog_customize_register' ) && ! defined( 'MATANGI_PREMIUM_VERSION' ) ) {
			$wp_customize->add_control(
				new Matangi_Customize_Misc_Control(
					$wp_customize,
					'blog_get_addon_desc',
					array(
						'section' => 'matangi_blog_section',
						'type' => 'addon',
						'label' => __( 'Learn more', 'matangi' ),
						'description' => __( 'More options are available for this section in our premium version.', 'matangi' ),
						'url' => esc_url( MATANGI_THEME_URL ),
						'priority' => 30,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);
		}

		// Add Performance section
		$wp_customize->add_section(
			'matangi_general_section',
			array(
				'title' => __( 'General', 'matangi' ),
				'priority' => 99
			)
		);

		if ( ! apply_filters( 'matangi_fontawesome_essentials', false ) ) {
			$wp_customize->add_setting(
				'matangi_settings[font_awesome_essentials]',
				array(
					'default' => $defaults['font_awesome_essentials'],
					'type' => 'option',
					'sanitize_callback' => 'matangi_sanitize_checkbox'
				)
			);

			$wp_customize->add_control(
				'matangi_settings[font_awesome_essentials]',
				array(
					'type' => 'checkbox',
					'label' => __( 'Load essential icons only', 'matangi' ),
					'description' => __( 'Load essential Font Awesome icons instead of the full library.', 'matangi' ),
					'section' => 'matangi_general_section',
					'settings' => 'matangi_settings[font_awesome_essentials]',
				)
			);
		}

		// Add Socials section
		$wp_customize->add_section(
			'matangi_socials_section',
			array(
				'title' => __( 'Socials', 'matangi' ),
				'priority' => 99
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_display_side]',
			array(
				'default' => $defaults['socials_display_side'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_display_side]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Display on fixed side', 'matangi' ),
				'section' => 'matangi_socials_section'
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_display_top]',
			array(
				'default' => $defaults['socials_display_top'],
				'type' => 'option',
				'sanitize_callback' => 'matangi_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_display_top]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Display on top bar', 'matangi' ),
				'section' => 'matangi_socials_section'
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_facebook_url]',
			array(
				'default' => $defaults['socials_facebook_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_facebook_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Facebook url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_facebook_url]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_twitter_url]',
			array(
				'default' => $defaults['socials_twitter_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_twitter_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Twitter url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_twitter_url]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_google_url]',
			array(
				'default' => $defaults['socials_google_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_google_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Google url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_google_url]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_tumblr_url]',
			array(
				'default' => $defaults['socials_tumblr_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_tumblr_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Tumblr url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_tumblr_url]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_pinterest_url]',
			array(
				'default' => $defaults['socials_pinterest_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_pinterest_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Pinterest url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_pinterest_url]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_youtube_url]',
			array(
				'default' => $defaults['socials_youtube_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_youtube_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Youtube url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_youtube_url]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_linkedin_url]',
			array(
				'default' => $defaults['socials_linkedin_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_linkedin_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Linkedin url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_linkedin_url]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_linkedin_url]',
			array(
				'default' => $defaults['socials_linkedin_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_linkedin_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Linkedin url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_linkedin_url]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_custom_icon_1]',
			array(
				'default' => $defaults['socials_custom_icon_1'],
				'type' => 'option',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_custom_icon_1]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 1', 'matangi' ),
				'description'=> sprintf( 
					'%1$s<br>%2$s<code>fa-file-pdf-o</code><br>%3$s<a href="%4$s" target="_blank">%5$s</a>',
					esc_html__( 'You can add icon code for Your button.', 'matangi' ),
					esc_html__( 'Example: ', 'matangi' ),
					esc_html__( 'Use the codes from ', 'matangi' ),
					esc_url( MATANGI_FONT_AWESOME_LINK ),
					esc_html__( 'Font Awesome', 'matangi' )
				),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_custom_icon_1]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_custom_icon_url_1]',
			array(
				'default' => $defaults['socials_custom_icon_url_1'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_custom_icon_url_1]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 1 url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_custom_icon_url_1]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_custom_icon_2]',
			array(
				'default' => $defaults['socials_custom_icon_2'],
				'type' => 'option',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_custom_icon_2]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 2', 'matangi' ),
				'description'=> sprintf( 
					'%1$s<br>%2$s<code>fa-file-pdf-o</code><br>%3$s<a href="%4$s" target="_blank">%5$s</a>',
					esc_html__( 'You can add icon code for Your button.', 'matangi' ),
					esc_html__( 'Example: ', 'matangi' ),
					esc_html__( 'Use the codes from ', 'matangi' ),
					esc_url( MATANGI_FONT_AWESOME_LINK ),
					esc_html__( 'Font Awesome', 'matangi' )
				),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_custom_icon_2]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_custom_icon_url_2]',
			array(
				'default' => $defaults['socials_custom_icon_url_2'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_custom_icon_url_2]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 2 url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_custom_icon_url_2]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_custom_icon_3]',
			array(
				'default' => $defaults['socials_custom_icon_3'],
				'type' => 'option',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_custom_icon_3]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 3', 'matangi' ),
				'description'=> sprintf( 
					'%1$s<br>%2$s<code>fa-file-pdf-o</code><br>%3$s<a href="%4$s" target="_blank">%5$s</a>',
					esc_html__( 'You can add icon code for Your button.', 'matangi' ),
					esc_html__( 'Example: ', 'matangi' ),
					esc_html__( 'Use the codes from ', 'matangi' ),
					esc_url( MATANGI_FONT_AWESOME_LINK ),
					esc_html__( 'Font Awesome', 'matangi' )
				),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_custom_icon_3]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_custom_icon_url_3]',
			array(
				'default' => $defaults['socials_custom_icon_url_3'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_custom_icon_url_3]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Custom icon 3 url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_custom_icon_url_3]',
			)
		);
		
		$wp_customize->add_setting(
			'matangi_settings[socials_mail_url]',
			array(
				'default' => $defaults['socials_mail_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			'matangi_settings[socials_mail_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'E-mail url', 'matangi' ),
				'section'    => 'matangi_socials_section',
				'settings'   => 'matangi_settings[socials_mail_url]',
			)
		);

		// Add Matangi Premium section
		if ( ! defined( 'MATANGI_PREMIUM_VERSION' ) ) {
			$wp_customize->add_section(
				new Matangi_Upsell_Section( $wp_customize, 'matangi_upsell_section',
					array(
						'pro_text' => __( 'Get Premium for more!', 'matangi' ),
						'pro_url' => esc_url( MATANGI_THEME_URL ),
						'capability' => 'edit_theme_options',
						'priority' => 555,
						'type' => 'matangi-upsell-section',
					)
				)
			);
		}
	}
}

if ( ! function_exists( 'matangi_customizer_live_preview' ) ) {
	add_action( 'customize_preview_init', 'matangi_customizer_live_preview', 100 );
	/**
	 * Add our live preview scripts
	 *
	 */
	function matangi_customizer_live_preview() {
		wp_enqueue_script( 'matangi-themecustomizer', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/controls/js/customizer-live-preview.js', array( 'customize-preview' ), MATANGI_VERSION, true );
	}
}
