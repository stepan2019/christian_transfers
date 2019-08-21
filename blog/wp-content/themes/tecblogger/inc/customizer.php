<?php
/**
 * tecblogger Theme Customizer
 *
 * @package tecblogger
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tecblogger_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';








	
/* ----- THEME OPTIONS PANEL ----- */


	
		$wp_customize->add_section( 'title_tagline' , array(
			'title'		=> __( 'Site Logo & Icon', 'tecblogger' ),
			'priority'	=> 10,
		) );
				
		$wp_customize->add_section( 'copyright_footer' , array(
	   		'title'      => __( 'Footer Settings', 'tecblogger' ),
	   		'description'=> 'All Footer Settings ',
	   		'priority'   => 80,
		) );
		
		
		
	
		///////////////////////////////////////////////////////////////////////
		//	
		//	Site Logo & Icon
		//
		//////////////////////////////////////////////////////////////////////
		
		$wp_customize->add_setting(
			'tecblogger_logo_uploader',
			array(
				'default' => '',
				'sanitize_callback' => 'esc_url_raw'
			)
		);
		
		$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,
			'tecblogger_logo_uploader',
				array(
					'label' => esc_html__('Upload a site logo', 'tecblogger'),
					'section' => 'title_tagline',
					'settings' => 'tecblogger_logo_uploader',
				)
			)
		);
		
		
		
		
		///////////////////////////////////////////////////////////////////////
		//	
		//	Footer Settings
		//
		//////////////////////////////////////////////////////////////////////
		
		

		// footer social icons
		$wp_customize->add_setting(
	        'tecblogger_social_icons',
	        array(
	            'default'     			=> false,
	            'sanitize_callback'    	=> 'tecblogger_sanitize_checkbox',
	        )
	    );

		// footer social icons
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'tecblogger_social_icons',
				array(
					'label'      => esc_html__( 'Enable Footer Social Icon', 'tecblogger' ),
					'section'    => 'copyright_footer',
					'settings'   => 'tecblogger_social_icons',
					'type'		 => 'checkbox',
					'priority'	 => 2
				)
			)
		);



	    // Social Icons /////////////////////////////////////////////////////
		$wp_customize->add_setting(
	        'tech_facebook',
	        array(
	            'default'     => '#',
	            'sanitize_callback' => 'esc_url',
	        )
	    );

	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'tech_facebook',
				array(
					'label'      => __( 'Facebook:', 'tecblogger' ),
					'section'    => 'copyright_footer',
					'settings'   => 'tech_facebook',
					'type'		 => 'url',
				)
			)
		);

		$wp_customize->add_setting(
	        'tech_twitter',
	        array(
	            'default'     => '#',
	            'sanitize_callback' => 'esc_url',
	        )
	    );

	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'tech_twitter',
				array(
					'label'      => __( 'Twitter:', 'tecblogger' ),
					'section'    => 'copyright_footer',
					'settings'   => 'tech_twitter',
					'type'		 => 'url',
				)
			)
		);

		$wp_customize->add_setting(
	        'tech_instagram',
	        array(
	            'sanitize_callback' => 'esc_url',
	        )
	    );

	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'tech_instagram',
				array(
					'label'      => __( 'Instagram:', 'tecblogger' ),
					'section'    => 'copyright_footer',
					'settings'   => 'tech_instagram',
					'type'		 => 'url',
				)
			)
		);

		$wp_customize->add_setting(
	        'tech_google',
	        array(
	            'sanitize_callback' => 'esc_url',
	        )
	    );

	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'tech_google',
				array(
					'label'      => __( 'Google Plus:', 'tecblogger' ),
					'section'    => 'copyright_footer',
					'settings'   => 'tech_google',
					'type'		 => 'url',
				)
			)
		);

		$wp_customize->add_setting(
	        'tech_youtube',
	        array(
	            'sanitize_callback' => 'esc_url',
	        )
	    );

	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'tech_youtube',
				array(
					'label'      => __( 'Youtube:', 'tecblogger' ),
					'section'    => 'copyright_footer',
					'settings'   => 'tech_youtube',
					'type'		 => 'url',
				)
			)
		);

		$wp_customize->add_setting(
	        'tech_pinterest',
	        array(
	            'sanitize_callback' => 'esc_url',
	        )
	    );

	    $wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'tech_pinterest',
				array(
					'label'      => __( 'Pinterest:', 'tecblogger' ),
					'section'    => 'copyright_footer',
					'settings'   => 'tech_pinterest',
					'type'		 => 'url',
				)
			)
		);



}
add_action( 'customize_register', 'tecblogger_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function tecblogger_customize_preview_js() {
	wp_enqueue_script( 'tecblogger_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'tecblogger_customize_preview_js' );



function tecblogger_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}