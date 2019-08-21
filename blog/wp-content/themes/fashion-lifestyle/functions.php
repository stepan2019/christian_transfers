<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


function fashion_lifestyle_theme_setup() {
	/*
	    * Make child theme available for translation.
	    * Translations can be filed in the /languages/ directory.
	*/
	load_child_theme_textdomain( 'fashion-lifestyle', get_stylesheet_directory() . '/languages' );

	add_image_size( 'fashion-lifestyle-blog-home-two', 410, 350, true );

}
add_action( 'after_setup_theme', 'fashion_lifestyle_theme_setup' );

/**
*	Enqueue scripts and styles
**/
if( ! function_exists( 'fashion_lifestyle_scripts' ) ):
	function fashion_lifestyle_scripts() {
		// Use minified libraries if SCRIPT_DEBUG is false
	    $build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
	    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$my_theme = wp_get_theme();
		$version = $my_theme['Version'];

		wp_enqueue_style( 'blossom-fashion-style', trailingslashit( get_template_directory_uri() ). 'style.css' , array( 'animate' ) );
		wp_enqueue_style( 'fashion-lifestyle', get_stylesheet_directory_uri(). '/style.css' , array( 'blossom-fashion-style' ) , $version );
		wp_enqueue_script( 'fashion-lifestyle', get_stylesheet_directory_uri() . '/js' . $build . '/custom' . $suffix . '.js', array( 'jquery' ), $version, true );

		$array = array( 
            'rtl'       => is_rtl(),
            'animation' => get_theme_mod( 'slider_animation' ),
        ); 
        wp_localize_script( 'fashion-lifestyle', 'fashion_lifestyle_data', $array );

	}
endif;
add_action( 'wp_enqueue_scripts', 'fashion_lifestyle_scripts' );

/**
*	Remove a function from the parent theme
**/
function remove_parent_filters(){
	remove_action( 'customize_register', 'blossom_fashion_customizer_theme_info' );
}
add_action( 'init', 'remove_parent_filters' );

/**
*	Fashion Lifestyle Body Classes
**/
function blossom_fashion_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	$home_layout = get_theme_mod( 'home_layout', 'two' );

	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image custom-background';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color custom-background';
	}

	if ( $home_layout == 'two' ) {
		$classes[] = 'homepage-layout-two';
	}
    
    $classes[] = blossom_fashion_sidebar_layout();

	return $classes;
}


/**
*	Fashion Lifestyle_customize_register
**/
if( ! function_exists( 'fashion_lifestyle_customize_register ') ):
	function fashion_lifestyle_customize_register( $wp_customize ){

		/** DEMO & DOCUMENTATION */
		$wp_customize->add_section(
			'theme_info',
			array(
				'title' 	=> __( 'Demo & Documentation', 'fashion-lifestyle' ),
				'priority'	=> 6,
			)
		);

		/** Important Links */
		$wp_customize->add_setting(
			'theme_info_link',
			array(
				'default'	=> '',
				'sanitize_callback'	=> 'wp_kses_post',
			)
		);

		$theme_info  = '<p>';
		$theme_info .= sprintf( __( '%1$sDemo Link:%2$s %3$sClick here.%4$s', 'fashion-lifestyle' ),'<strong>', '</strong>',  '<a href="' . esc_url( 'https://demo.blossomthemes.com/fashion-lifestyle/' ) . '" target="_blank">', '</a>' );
		$theme_info .= '</p><p>';
    	$theme_info .= sprintf( __( '%1$sDocumentation Link:%2$s %3$sClick here.%4$s', 'fashion-lifestyle' ),'<strong>', '</strong>',  '<a href="' . esc_url( 'https://docs.blossomthemes.com/docs/fashion-lifestyle/' ) . '" target="_blank">', '</a>' );
    	$theme_info .= '</p>';

    	$wp_customize->add_control( new Blossom_Fashion_Note_Control( $wp_customize,
		        'theme_info_link',
		        array(
		            'section'       => 'theme_info',
		            'description'   => $theme_info,
		        ) 
	    	)
    	);

    	/** Typography */
	    $wp_customize->add_section(
	        'typography_settings',
	        array(
	            'title'    => __( 'Typography', 'fashion-lifestyle' ),
	            'priority' => 10,
	            'panel'    => 'appearance_settings',
	        )
	    );

	    /** Primary Font */
	    $wp_customize->add_setting(
	        'primary_font',
	        array(
	            'default'           => 'Nunito Sans',
	            'sanitize_callback' => 'blossom_fashion_sanitize_select'
	        )
	    );

	    $wp_customize->add_control(
	        new Blossom_Fashion_Select_Control(
	            $wp_customize,
	            'primary_font',
	            array(
	                'label'       => __( 'Primary Font', 'fashion-lifestyle' ),
	                'description' => __( 'Primary font of the site.', 'fashion-lifestyle' ),
	                'section'     => 'typography_settings',
	                'choices'     => blossom_fashion_get_all_fonts(),  
	            )
	        )
	    );
	    /** Secondary Font */
	    $wp_customize->add_setting(
	        'secondary_font',
	        array(
	            'default'           => 'Cormorant Garamond',
	            'sanitize_callback' => 'blossom_fashion_sanitize_select'
	        )
	    );

	    $wp_customize->add_control(
	        new Blossom_Fashion_Select_Control(
	            $wp_customize,
	            'secondary_font',
	            array(
	                'label'       => __( 'Secondary Font', 'fashion-lifestyle' ),
	                'description' => __( 'Secondary font of the site.', 'fashion-lifestyle' ),
	                'section'     => 'typography_settings',
	                'choices'     => blossom_fashion_get_all_fonts(),  
	            )
	        )
	    );
	    
	    /** Font Size*/
	    $wp_customize->add_setting( 
	        'font_size', 
	        array(
	            'default'           => 16,
	            'sanitize_callback' => 'blossom_fashion_sanitize_number_absint'
	        ) 
	    );
	    
	    $wp_customize->add_control(
	        new Blossom_Fashion_Slider_Control( 
	            $wp_customize,
	            'font_size',
	            array(
	                'section'     => 'typography_settings',
	                'label'       => __( 'Font Size', 'fashion-lifestyle' ),
	                'description' => __( 'Change the font size of your site.', 'fashion-lifestyle' ),
	                'choices'     => array(
	                    'min'   => 10,
	                    'max'   => 50,
	                    'step'  => 1,
	                )                 
	            )
	        )
	    );

	    /** Primary Color*/
	    $wp_customize->add_setting( 
	        'primary_color', array(
	            'default'           => '#60c5ba',
	            'sanitize_callback' => 'sanitize_hex_color'
	        ) 
	    );

	    $wp_customize->add_control( 
	        new WP_Customize_Color_Control( 
	            $wp_customize, 
	            'primary_color', 
	            array(
	                'label'       => __( 'Primary Color', 'fashion-lifestyle' ),
	                'description' => __( 'Primary color of the theme.', 'fashion-lifestyle' ),
	                'section'     => 'colors',
	                'priority'    => 5,                
	            )
	        )
	    );

    	/** LAYOUT SETTINGS PANEL */
    	$wp_customize->add_panel(
    		'layout_settings',
    		array(
    			'title'    => __( 'Layout Settings', 'fashion-lifestyle' ),
    			'priority' => 45,	
    		)
    	);

    	/** Header Layout */
    	$wp_customize->add_section(
    		'header_layout_settings',
    		array(
    			'title' 	=> __( 'Header Layout', 'fashion-lifestyle' ),
    			'panel'		=> 'layout_settings',
    			'priority'	=> 10,
    		)
    	);
    	/** Blog Page layout */
	    $wp_customize->add_setting( 
	        'header_layout', 
	        array(
	            'default'           => 'two',
	            'sanitize_callback' => 'esc_attr'
	        ) 
	    );
    	
    	$wp_customize->add_control(
    		new Blossom_Fashion_Radio_Image_Control(
    			$wp_customize,
    			'header_layout',
    			array(
    				'section'	=> 'header_layout_settings',
    				'label' 	=> __( 'Header Layout', 'fashion-lifestyle' ),
    				'description'	=> __( 'This is the available layout for header', 'fashion-lifestyle' ),
    				'choices'		=> array(
    					'one'	=> get_stylesheet_directory_uri(). '/images/header/header-one.png',
    					'two'	=> get_stylesheet_directory_uri(). '/images/header/header-two.png',

    				)
    			)
    		)
    	);

    	/** Slider Layouts */
    	$wp_customize->add_section(
    		'slider_layout_settings',
    		array(
    			'title' 	=> __( 'Slider Layouts', 'fashion-lifestyle' ),
    			'panel'		=> 'layout_settings',
    			'priority'	=> 20,
    		)
    	);

    	$wp_customize->add_setting( 
	        'slider_layout', 
	        array(
	            'default'           => 'two',
	            'sanitize_callback' => 'esc_attr'
	        ) 
   		);


    	$wp_customize->add_control(
        new Blossom_Fashion_Radio_Image_Control(
            $wp_customize,
            'slider_layout',
            	array(
	                'section'     => 'slider_layout_settings',
	                'label'       => __( 'Slider Layout', 'fashion-lifestyle' ),
	                'description' => __( 'Choose the layout of the slider for your site.', 'fashion-lifestyle' ),
	                'choices'     => array(
	                    'one'   => get_stylesheet_directory_uri() . '/images/slider/slider-one.jpg',
	                    'two'   => get_stylesheet_directory_uri() . '/images/slider/slider-two.jpg',
                	)
            	)
        	)
    	);


    	/** Home Page Layouts */
    	$wp_customize->add_section(
    		'home_layout_settings',
    		array(
    			'title' 	=> __( 'Home Page Layouts', 'fashion-lifestyle' ),
    			'panel'		=> 'layout_settings',
    			'priority'	=> 30,
    		)
    	);

    	$wp_customize->add_setting( 
	        'home_layout', 
	        array(
	            'default'           => 'two',
	            'sanitize_callback' => 'esc_attr'
	        ) 
   		);

    	$wp_customize->add_control(
	        new Blossom_Fashion_Radio_Image_Control(
	            $wp_customize,
	            'home_layout',
	            	array(
		                'section'     => 'home_layout_settings',
		                'label'       => __( 'Home Page Layout', 'fashion-lifestyle' ),
		                'description' => __( 'Choose the layout of the home page for your site.', 'fashion-lifestyle' ),
		                'choices'     => array(
		                    'one'   => get_stylesheet_directory_uri() . '/images/home/home-one.jpg',
		                    'two'   => get_stylesheet_directory_uri() . '/images/home/home-two.jpg',
	                )
	            )
	        )
	    );
	}
endif;
add_action( 'customize_register', 'fashion_lifestyle_customize_register', 40 );


/**
*	Fashion Lifestyle Header Section
**/

function blossom_fashion_header(){
	$header_layout = get_theme_mod( 'header_layout', 'two' );
	$ed_cart = get_theme_mod( 'ed_shopping_cart', true ); ?>

	 <header class="site-header <?php if( $header_layout == 'two' ) echo 'header-two' ;?>" itemscope itemtype="http://schema.org/WPHeader">
		<div class="header-holder">
			<div class="header-t">
				<div class="container">
					<?php if( $header_layout == 'two' ) { ?>
						<div class="overlay"></div>
		    			<div id="toggle-button">
		    				<span></span><?php esc_html_e( 'Menu', 'fashion-lifestyle' ); ?>
		    			</div>
						
					<?php
						fashion_lifestyle_primary_navigation();
					?>
					<div class="form-holder">
						<div class="btn-close-form"><span></span></div>
	                    <?php get_search_form(); ?>
					</div>
					<?php if( blossom_fashion_social_links( false ) || ( blossom_fashion_is_woocommerce_activated() && $ed_cart ) ){ ?>
		                <div class="right">
							<?php if( ( blossom_fashion_is_woocommerce_activated() && $ed_cart ) ){ ?>
		                    <div class="tools">
								<?php 
		                            if( blossom_fashion_is_woocommerce_activated() && $ed_cart ) blossom_fashion_wc_cart_count();                           
		                        ?>
		                        <div class="form-section">
		                        	<span id="btn-search">
		                        		<i class="fa fa-search"></i>
		                        	</span>
		                        </div>
							</div>
		                    <?php }
		                    
		                    if( ( ( blossom_fashion_is_woocommerce_activated() && $ed_cart ) ) && blossom_fashion_social_links( false ) ) echo '<span class="separator"></span>';
		                    
			                    if( blossom_fashion_social_links( false ) ){ ?>
									<div class="social-networks-holder">
										<?php blossom_fashion_social_links(); ?>
									</div>
			                    <?php } ?>
						</div>
                	<?php } 
				 	} elseif( $header_layout == 'one' ){ ?>
				 		<div class="row">
							<div class="col">
								<?php get_search_form(); ?>
							</div>
							<div class="col">
								<?php fashion_lifestyle_site_branding(); ?>
							</div>
							<div class="col">
								<div class="tools">
									<?php 
	                                if( blossom_fashion_social_links( false ) || ( blossom_fashion_is_woocommerce_activated() && $ed_cart ) ){
	                                    if( blossom_fashion_is_woocommerce_activated() && $ed_cart ) blossom_fashion_wc_cart_count();
	                                    if( blossom_fashion_is_woocommerce_activated() && $ed_cart && blossom_fashion_social_links( false ) ) echo '<span class="separator"></span>';
	                                    blossom_fashion_social_links();
	                                }                                    
	                                ?>
								</div>
							</div>
						</div><!-- .row-->
					<?php }	 ?>					
				</div> <!-- .container -->
			</div> <!-- .header-t -->
		</div> <!-- .header-holder -->
		<div class="<?php echo ( $header_layout == 'two' ) ? 'main-header' : 'nav-holder'; ?>">
			<div class="container">
				<?php if( $header_layout == 'one' ) { ?>
				<div class="overlay"></div>
    			<div id="toggle-button">
    				<span></span><?php esc_html_e( 'Menu', 'fashion-lifestyle' ); ?>
    			</div>
    		   <?php } ?>
				<?php ($header_layout == 'two') ? fashion_lifestyle_site_branding() : fashion_lifestyle_primary_navigation(); 

					if( $header_layout == 'one' ) {
				?>

				<div class="form-holder">
					<?php get_search_form(); ?>
				</div>
                <div class="tools">
					<div class="form-section">
						<span id="btn-search"><i class="fa fa-search"></i></span>						
					</div>
                    <?php 
                    if( blossom_fashion_social_links( false ) || ( blossom_fashion_is_woocommerce_activated() && $ed_cart ) ){
                        if( blossom_fashion_is_woocommerce_activated() && $ed_cart ) blossom_fashion_wc_cart_count();
                        if( blossom_fashion_is_woocommerce_activated() && $ed_cart && blossom_fashion_social_links( false ) ) echo '<span class="separator"></span>';
                        blossom_fashion_social_links();
                    }
                    ?>					
				</div>
			<?php } ?>
			</div>			
		</div>
	 </header>
<?php
}

function fashion_lifestyle_primary_navigation(){ ?>
	<nav id="site-navigation" class="main-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
	            'fallback_cb'    => 'blossom_fashion_primary_menu_fallback',
			) );
		?>
	</nav><!-- #site-navigation -->
<?php
}
/**
 * Site Branding
 */
function fashion_lifestyle_site_branding() { 
$header_layout = get_theme_mod( 'header_layout', 'two' );
	?>
<div class="<?php echo ( $header_layout == 'two') ? 'site-branding' : 'text-logo'; ?>" itemscope itemtype="http://schema.org/Organization">
	<?php 
        if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
            the_custom_logo();
        }
        
        if( is_front_page() ){ ?>
            <h1 class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>
		    <?php 
        }else{ ?>
            <p class="site-title" itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php bloginfo( 'name' ); ?></a></p>
        <?php 
        } 
     
        $description = get_bloginfo( 'description', 'display' );
        if ( $description || is_customize_preview() ){ ?>
            <p class="site-description"><?php echo $description; ?></p>
        <?php
        } ?>
</div>
<?php
}


/** Fashion Lifestyle Slider Section */

function blossom_fashion_banner(){
	$slider_layout 	= get_theme_mod( 'slider_layout', 'two' );
	$ed_banner      = get_theme_mod( 'ed_banner_section', 'slider_banner' );
    $slider_type    = get_theme_mod( 'slider_type', 'latest_posts' ); 
    $slider_cat     = get_theme_mod( 'slider_cat' );
    $posts_per_page = get_theme_mod( 'no_of_slides', 3 );  
    
    if( is_front_page() || is_home() ){ 
        
        if( $ed_banner == 'static_banner' && has_custom_header() ){ ?>
            <div class="banner<?php if( has_header_video() ) echo esc_attr( ' video-banner' ); ?>">
                <?php the_custom_header_markup(); ?>
            </div>
            <?php
        }elseif( $ed_banner == 'slider_banner' ){
            $args = array(
                'post_type'           => 'post',
                'post_status'         => 'publish',            
                'ignore_sticky_posts' => true
            );
            
            if( $slider_type === 'cat' && $slider_cat ){
                $args['cat']            = $slider_cat; 
                $args['posts_per_page'] = -1;  
            }else{
                $args['posts_per_page'] = $posts_per_page;
            }
                
            $qry = new WP_Query( $args );
            
            if( $qry->have_posts() ){ ?>
            
            <?php if( $slider_layout == 'one') echo '<div class="banner">'; ?>
        		<div <?php if( $slider_layout == 'one' ) echo 'id="banner-slider"'; ?> class="<?php echo ( $slider_layout == 'two' ) ? 'banner banner-layout-'.esc_attr($slider_layout) : 'owl-carousel' ?>">
        			<?php 
        				if( $slider_layout == 'two' ) {
        					echo '<div id="banner-slider-two" class="owl-carousel">';
        				}
        			?>
        			<?php while( $qry->have_posts() ) { $qry->the_post(); ?>
	                    <div class="item">
	        				<?php 
	                        if( has_post_thumbnail() ){
	        				    the_post_thumbnail( 'blossom-fashion-slider' );    
	        				}else{ 
	        					blossom_fashion_get_fallback_svg( 'blossom-fashion-slider' ); 
	                        }
	                        ?>                        
	        				<div class="banner-text">
	        					<div class="container">
	        						<div class="text-holder">
	        							<?php
	                                        blossom_fashion_category();
	                                        the_title( '<h2 class="title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
	                                    ?>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
        			<?php } 
					
					if($slider_layout == 'two') echo '</div>';
     				?>
                    
        		</div> <!-- #banner-slider -->
        	<?php if( $slider_layout == 'one') echo '</div>'; ?>
            <?php
            wp_reset_postdata();
            }
        } 
    }    
}

/** Blossom Fashion Category */
function blossom_fashion_category(){
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( ' ' );
		if ( $categories_list ) {
			echo '<span class="cat-links" itemprop="about">' . $categories_list . '</span>';
		}
	}
}

function blossom_fashion_post_thumbnail(){
	global $wp_query;
    $image_size     = 'thumbnail';
    $ed_featured    = get_theme_mod( 'ed_featured_image', true );
    $sidebar_layout = blossom_fashion_sidebar_layout();
    $home_layout 	= get_theme_mod( 'home_layout', 'two' );
    
    if( is_front_page() || is_home() ){
        echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
        if( has_post_thumbnail() ){
        	if( $home_layout == 'two' ){
                $image_size = 'fashion-lifestyle-blog-home-two';  
            }elseif( $wp_query->current_post == 0 ){                
                $image_size = ( $sidebar_layout == 'full-width' ) ? 'blossom-fashion-fullwidth' : 'blossom-fashion-with-sidebar';
            }else{
            	$image_size = 'blossom-fashion-blog-home';
            }         
            the_post_thumbnail( $image_size );    
        }else{
            $image_size = ( $wp_query->current_post == 0 ) ? 'blossom-fashion-fullwidth' : 'blossom-fashion-blog-home';
            blossom_fashion_get_fallback_svg( $image_size );    
        }        
        echo '</a>';
    }elseif( is_home() ){      
        echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
        if( has_post_thumbnail() ){                        
            the_post_thumbnail( 'blossom-fashion-blog-home' );  
        }else{ 
        	blossom_fashion_get_fallback_svg( 'blossom-fashion-blog-home' );
        }        
        echo '</a>';
    }elseif( is_archive() || is_search() ){
        echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
        if( has_post_thumbnail() ){
            the_post_thumbnail( 'blossom-fashion-blog-archive' );    
        }else{ 
        	blossom_fashion_get_fallback_svg( 'blossom-fashion-blog-archive' );
        }
        echo '</a>';
    }elseif( is_singular() ){
        echo '<div class="post-thumbnail">';
        $image_size = ( $sidebar_layout == 'full-width' ) ? 'blossom-fashion-fullwidth' : 'blossom-fashion-with-sidebar';
        if( is_single() ){
            if( $ed_featured ) the_post_thumbnail( $image_size );
        }else{
            the_post_thumbnail( $image_size );
        }
        echo '</div>';
    }
}

/** Blossom Fashion Fonts URL */
function blossom_fashion_fonts_url(){
    $fonts_url = '';
    
    $primary_font       = get_theme_mod( 'primary_font', 'Nunito Sans' );
    $ig_primary_font    = blossom_fashion_is_google_font( $primary_font );    
    $secondary_font     = get_theme_mod( 'secondary_font', 'Cormorant Garamond' );
    $ig_secondary_font  = blossom_fashion_is_google_font( $secondary_font );    
    $site_title_font    = get_theme_mod( 'site_title_font', array( 'font-family'=>'Rufina', 'variant'=>'regular' ) );
    $ig_site_title_font = blossom_fashion_is_google_font( $site_title_font['font-family'] );
        
    /* Translators: If there are characters in your language that are not
    * supported by respective fonts, translate this to 'off'. Do not translate
    * into your own language.
    */
    $primary    = _x( 'on', 'Primary Font: on or off', 'fashion-lifestyle' );
    $secondary  = _x( 'on', 'Secondary Font: on or off', 'fashion-lifestyle' );
    $site_title = _x( 'on', 'Site Title Font: on or off', 'fashion-lifestyle' );
    
    
    if ( 'off' !== $primary || 'off' !== $secondary || 'off' !== $site_title ) {
        
        $font_families = array();
     
        if ( 'off' !== $primary && $ig_primary_font ) {
            $primary_variant = blossom_fashion_check_varient( $primary_font, 'regular', true );
            if( $primary_variant ){
                $primary_var = ':' . $primary_variant;
            }else{
                $primary_var = '';    
            }            
            $font_families[] = $primary_font . $primary_var;
        }
         
        if ( 'off' !== $secondary && $ig_secondary_font ) {
            $secondary_variant = blossom_fashion_check_varient( $secondary_font, 'regular', true );
            if( $secondary_variant ){
                $secondary_var = ':' . $secondary_variant;    
            }else{
                $secondary_var = '';
            }
            $font_families[] = $secondary_font . $secondary_var;
        }
        
        if ( 'off' !== $site_title && $ig_site_title_font ) {
            
            if( ! empty( $site_title_font['variant'] ) ){
                $site_title_var = ':' . blossom_fashion_check_varient( $site_title_font['font-family'], $site_title_font['variant'] );    
            }else{
                $site_title_var = '';
            }
            $font_families[] = $site_title_font['font-family'] . $site_title_var;
        }
        
        $font_families = array_diff( array_unique( $font_families ), array('') );
        
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),            
        );
        
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }
     
    return esc_url_raw( $fonts_url );
}

/** Blossom Fashion Dynamic CSS */
function blossom_fashion_dynamic_css(){
    
    $primary_font    = get_theme_mod( 'primary_font', 'Nunito Sans' );
    $primary_fonts   = blossom_fashion_get_fonts( $primary_font, 'regular' );
    $secondary_font  = get_theme_mod( 'secondary_font', 'Cormorant Garamond' );
    $secondary_fonts = blossom_fashion_get_fonts( $secondary_font, 'regular' );
    $font_size       = get_theme_mod( 'font_size', 16 );
    
    $site_title_font      = get_theme_mod( 'site_title_font', array( 'font-family'=>'Rufina', 'variant'=>'regular' ) );
    $site_title_fonts     = blossom_fashion_get_fonts( $site_title_font['font-family'], $site_title_font['variant'] );
    $site_title_font_size = get_theme_mod( 'site_title_font_size', 120 );
    
    $primary_color = get_theme_mod( 'primary_color', '#60c5ba' );
    
    $rgb = blossom_fashion_hex2rgb( blossom_fashion_sanitize_hex_color( $primary_color ) );
     
    echo "<style type='text/css' media='all'>"; ?>
     
    .content-newsletter .blossomthemes-email-newsletter-wrapper.bg-img:after,
    .widget_blossomthemes_email_newsletter_widget .blossomthemes-email-newsletter-wrapper:after{
        <?php echo 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.8);'; ?>
    }
    
    /*Typography*/
    body,
    button,
    input,
    select,
    optgroup,
    textarea{
        font-family : <?php echo wp_kses_post( $primary_fonts['font'] ); ?>;
        font-size   : <?php echo absint( $font_size ); ?>px;        
    }
    
    .site-title{
        font-size   : <?php echo absint( $site_title_font_size ); ?>px;
        font-family : <?php echo wp_kses_post( $site_title_fonts['font'] ); ?>;
        font-weight : <?php echo esc_html( $site_title_fonts['weight'] ); ?>;
        font-style  : <?php echo esc_html( $site_title_fonts['style'] ); ?>;
    }
    
    /*Color Scheme*/
    a,
    .site-header .social-networks li a:hover,
    .site-header .social-networks li a:focus,
    .site-title a:hover,
    .site-title a:focus,
	.shop-section .shop-slider .item h3 a:hover,
	.shop-section .shop-slider .item h3 a:focus,
	#primary .post .entry-header .entry-meta a:hover,
	#primary .post .entry-header .entry-meta a:focus,
	#primary .post .entry-footer .social-networks li a:hover,
	#primary .post .entry-footer .social-networks li a:focus,
	.widget ul li a:hover, .widget ul li a:focus,
	.widget_bttk_author_bio .author-bio-socicons ul li a:hover,
	.widget_bttk_author_bio .author-bio-socicons ul li a:focus,
	.widget_bttk_popular_post ul li .entry-header .entry-title a:hover,
	.widget_bttk_popular_post ul li .entry-header .entry-title a:focus,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-title a:hover,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-title a:focus,
	.widget_bttk_popular_post ul li .entry-header .entry-meta a:hover,
	.widget_bttk_popular_post ul li .entry-header .entry-meta a:focus,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-meta a:hover,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-meta a:focus,
	.bottom-shop-section .bottom-shop-slider .item .product-category a:hover,
	.bottom-shop-section .bottom-shop-slider .item .product-category a:focus,
	.bottom-shop-section .bottom-shop-slider .item h3 a:hover,
	.bottom-shop-section .bottom-shop-slider .item h3 a:focus,
	.instagram-section .header .title a:hover,
	.instagram-section .header .title a:focus,
	.site-footer .widget ul li a:hover,
	.site-footer .widget ul li a:focus,
	.site-footer .widget_bttk_popular_post ul li .entry-header .entry-title a:hover,
	.site-footer .widget_bttk_pro_recent_post ul li .entry-header .entry-title a:hover,
	.site-footer .widget_bttk_popular_post ul li .entry-header .entry-title a:focus,
	.site-footer .widget_bttk_pro_recent_post ul li .entry-header .entry-title a:focus,
	.single .single-header .site-title:hover,
	.single .single-header .site-title:focus,
	.single .single-header .right .social-share .social-networks li a:hover,
	.single .single-header .right .social-share .social-networks li a:focus,
	.comments-area .comment-body .fn a:hover,
	.comments-area .comment-body .fn a:focus,
	.comments-area .comment-body .comment-metadata a:hover,
	.comments-area .comment-body .comment-metadata a:focus,
	.page-template-contact .contact-details .contact-info-holder .col .icon-holder,
	.page-template-contact .contact-details .contact-info-holder .col .text-holder h3 a:hover,
	.page-template-contact .contact-details .contact-info-holder .col .text-holder h3 a:focus,
	.page-template-contact .contact-details .contact-info-holder .col .social-networks li a:hover,
	.page-template-contact .contact-details .contact-info-holder .col .social-networks li a:focus,
    #secondary .widget_bttk_description_widget .social-profile li a:hover,
    #secondary .widget_bttk_description_widget .social-profile li a:focus,
    #secondary .widget_bttk_contact_social_links .social-networks li a:hover,
    #secondary .widget_bttk_contact_social_links .social-networks li a:focus,
    .site-footer .widget_bttk_contact_social_links .social-networks li a:hover,
    .site-footer .widget_bttk_contact_social_links .social-networks li a:focus,
    .site-footer .widget_bttk_description_widget .social-profile li a:hover,
    .site-footer .widget_bttk_description_widget .social-profile li a:focus,
    .portfolio-sorting .button:hover,
    .portfolio-sorting .button:focus,
    .portfolio-sorting .button.is-checked,
    .portfolio-item .portfolio-cat a:hover,
    .portfolio-item .portfolio-cat a:focus,
    .entry-header .portfolio-cat a:hover,
    .entry-header .portfolio-cat a:focus,
    .single-blossom-portfolio .post-navigation .nav-previous a:hover,
    .single-blossom-portfolio .post-navigation .nav-previous a:focus,
    .single-blossom-portfolio .post-navigation .nav-next a:hover,
    .single-blossom-portfolio .post-navigation .nav-next a:focus, 
    .banner .text-holder .title a:hover, .header-four .main-navigation ul li a:hover, .header-four .main-navigation ul ul li a:hover, #primary .post .entry-header .entry-title a:hover, .portfolio-item .portfolio-img-title a:hover,
    .widget_bttk_posts_category_slider_widget .carousel-title .title a:hover, .widget_bttk_posts_category_slider_widget .carousel-title .title a:focus{
		color: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
	}

	.site-header .tools .cart .number,
	.shop-section .header .title:after,
	.header-two .header-t,
	.header-six .header-t,
	.header-eight .header-t,
	.shop-section .shop-slider .item .product-image .btn-add-to-cart:hover,
	.shop-section .shop-slider .item .product-image .btn-add-to-cart:focus,
	.widget .widget-title:before,
	.widget .widget-title:after,
	.widget_calendar caption,
	.widget_bttk_popular_post .style-two li:after,
	.widget_bttk_popular_post .style-three li:after,
	.widget_bttk_pro_recent_post .style-two li:after,
	.widget_bttk_pro_recent_post .style-three li:after,
	.instagram-section .header .title:before,
	.instagram-section .header .title:after,
	#primary .post .entry-content .pull-left:after,
	#primary .page .entry-content .pull-left:after,
	#primary .post .entry-content .pull-right:after,
	#primary .page .entry-content .pull-right:after,
	.page-template-contact .contact-details .contact-info-holder h2:after,
    .widget_bttk_image_text_widget ul li .btn-readmore:hover,
    .widget_bttk_image_text_widget ul li .btn-readmore:focus,
    #secondary .widget_bttk_icon_text_widget .text-holder .btn-readmore:hover,
    #secondary .widget_bttk_icon_text_widget .text-holder .btn-readmore:focus,
    #secondary .widget_blossomtheme_companion_cta_widget .btn-cta:hover,
    #secondary .widget_blossomtheme_companion_cta_widget .btn-cta:focus,
    #secondary .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:hover,
    #secondary .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:focus, 
    #primary .post .entry-header .cat-links a:hover,
	#primary .post .entry-header .cat-links a:focus, 
	.banner .text-holder .cat-links a:hover,
	.banner .text-holder .cat-links a:focus,
	.widget_bttk_author_bio .text-holder .readmore:hover, 
	.widget_bttk_author_bio .text-holder .readmore:focus, 
	.banner .text-holder .cat-links a:hover, #primary .post .entry-header .cat-links a:hover, .widget_bttk_popular_post .style-two li .entry-header .cat-links a:hover, .widget_bttk_pro_recent_post .style-two li .entry-header .cat-links a:hover, .widget_bttk_popular_post .style-three li .entry-header .cat-links a:hover, .widget_bttk_pro_recent_post .style-three li .entry-header .cat-links a:hover, .page-header span, .widget_bttk_posts_category_slider_widget .carousel-title .cat-links a:hover, .portfolio-item .portfolio-cat a:hover, .entry-header .portfolio-cat a:hover, 
	.widget_bttk_posts_category_slider_widget .owl-theme .owl-nav [class*="owl-"]:hover, 
	 .widget_bttk_posts_category_slider_widget .owl-theme .owl-nav [class*="owl-"]:focus,
	 .widget_calendar table tbody td a,
	 .widget_tag_cloud .tagcloud a:hover, 
	 .widget_tag_cloud .tagcloud a:focus{
		background: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
	}
    
    .banner .text-holder .cat-links a,
	#primary .post .entry-header .cat-links a,
	.widget_bttk_popular_post .style-two li .entry-header .cat-links a,
	.widget_bttk_pro_recent_post .style-two li .entry-header .cat-links a,
	.widget_bttk_popular_post .style-three li .entry-header .cat-links a,
	.widget_bttk_pro_recent_post .style-three li .entry-header .cat-links a,
	.page-header span,
	.page-template-contact .top-section .section-header span,
    .portfolio-item .portfolio-cat a,
    .entry-header .portfolio-cat a{
		border-bottom-color: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
	}

	.banner .text-holder .title a,
	.header-four .main-navigation ul li a,
	.header-four .main-navigation ul ul li a,
	#primary .post .entry-header .entry-title a,
    .portfolio-item .portfolio-img-title a{
		background-image: linear-gradient(180deg, transparent 96%, <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?> 0);
	}

	.widget_bttk_social_links ul li a:hover,
	.widget_bttk_social_links ul li a:focus{
		border-color: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
	}

	button:hover,
	input[type="button"]:hover,
	input[type="reset"]:hover,
	input[type="submit"]:hover,
	button:focus,
	input[type="button"]:focus,
	input[type="reset"]:focus,
	input[type="submit"]:focus{
		background: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
		border-color: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
	}

	#primary .post .btn-readmore:hover,
	#primary .post .btn-readmore:focus{
		background: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
	}

	@media only screen and (min-width: 1025px){
		.main-navigation ul li:after{
			background: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
		}
		
	}

	@media only screen and (max-width: 1025px){
		.header-two .main-navigation ul li a:hover {
			color: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
		}
	}
    
    /*Typography*/
	.banner .text-holder .title,
	.top-section .newsletter .blossomthemes-email-newsletter-wrapper .text-holder h3,
	.shop-section .header .title,
	#primary .post .entry-header .entry-title,
	#primary .post .post-shope-holder .header .title,
	.widget_bttk_author_bio .title-holder,
	.widget_bttk_popular_post ul li .entry-header .entry-title,
	.widget_bttk_pro_recent_post ul li .entry-header .entry-title,
	.widget-area .widget_blossomthemes_email_newsletter_widget .text-holder h3,
	.bottom-shop-section .bottom-shop-slider .item h3,
	.page-title,
	#primary .post .entry-content blockquote,
	#primary .page .entry-content blockquote,
	#primary .post .entry-content .dropcap,
	#primary .page .entry-content .dropcap,
	#primary .post .entry-content .pull-left,
	#primary .page .entry-content .pull-left,
	#primary .post .entry-content .pull-right,
	#primary .page .entry-content .pull-right,
	.author-section .text-holder .title,
	.single .newsletter .blossomthemes-email-newsletter-wrapper .text-holder h3,
	.related-posts .title, .popular-posts .title,
	.comments-area .comments-title,
	.comments-area .comment-reply-title,
	.single .single-header .title-holder .post-title,
    .portfolio-text-holder .portfolio-img-title,
    .portfolio-holder .entry-header .entry-title,
    .related-portfolio-title{
		font-family: <?php echo wp_kses_post( $secondary_fonts['font'] ); ?>;
	}

	.main-navigation ul{
		font-family: <?php echo wp_kses_post( $primary_fonts['font'] ); ?>;
	}

    <?php if( blossom_fashion_is_woocommerce_activated() ) { ?>
        .woocommerce #secondary .widget_price_filter .ui-slider .ui-slider-range{
			background: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
    	}
        
        .woocommerce #secondary .widget .product_list_widget li .product-title:hover,
    	.woocommerce #secondary .widget .product_list_widget li .product-title:focus,
    	.woocommerce div.product .entry-summary .product_meta .posted_in a:hover,
    	.woocommerce div.product .entry-summary .product_meta .posted_in a:focus,
    	.woocommerce div.product .entry-summary .product_meta .tagged_as a:hover,
    	.woocommerce div.product .entry-summary .product_meta .tagged_as a:focus{
			color: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
    	}
        
        .woocommerce-checkout .woocommerce .woocommerce-info,
        .woocommerce ul.products li.product .add_to_cart_button:hover,
        .woocommerce ul.products li.product .add_to_cart_button:focus,
        .woocommerce ul.products li.product .product_type_external:hover,
        .woocommerce ul.products li.product .product_type_external:focus,
        .woocommerce ul.products li.product .ajax_add_to_cart:hover,
        .woocommerce ul.products li.product .ajax_add_to_cart:focus,
        .woocommerce ul.products li.product .added_to_cart:hover,
        .woocommerce ul.products li.product .added_to_cart:focus,
        .woocommerce div.product form.cart .single_add_to_cart_button:hover,
        .woocommerce div.product form.cart .single_add_to_cart_button:focus,
        .woocommerce div.product .cart .single_add_to_cart_button.alt:hover,
        .woocommerce div.product .cart .single_add_to_cart_button.alt:focus,
        .woocommerce #secondary .widget_shopping_cart .buttons .button:hover,
        .woocommerce #secondary .widget_shopping_cart .buttons .button:focus,
        .woocommerce #secondary .widget_price_filter .price_slider_amount .button:hover,
        .woocommerce #secondary .widget_price_filter .price_slider_amount .button:focus,
        .woocommerce-cart #primary .page .entry-content table.shop_table td.actions .coupon input[type="submit"]:hover,
        .woocommerce-cart #primary .page .entry-content table.shop_table td.actions .coupon input[type="submit"]:focus,
        .woocommerce-cart #primary .page .entry-content .cart_totals .checkout-button:hover,
        .woocommerce-cart #primary .page .entry-content .cart_totals .checkout-button:focus{
			background: <?php echo blossom_fashion_sanitize_hex_color( $primary_color ); ?>;
    	}

    	.woocommerce div.product .product_title,
    	.woocommerce div.product .woocommerce-tabs .panel h2{
			font-family: <?php echo wp_kses_post( $secondary_fonts['font'] ); ?>;
    	}    
    <?php } ?>
           
    <?php echo "</style>";
}

/** Fashion Lifestyle Footer */
function blossom_fashion_footer_bottom(){ ?>
    <div class="footer-b">
		<div class="container">
			<div class="site-info">            
            <?php
                blossom_fashion_get_footer_copyright();
                esc_html_e( ' Fashion Lifestyle | Developed By ', 'fashion-lifestyle' );                                
                echo '<a href="' . esc_url( 'https://blossomthemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Blossom Themes', 'fashion-lifestyle' ) . '</a>.';                                
                printf( esc_html__( ' Powered by %s', 'fashion-lifestyle' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'fashion-lifestyle' ) ) .'" target="_blank">WordPress</a>.' );
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>               
            </div>
		</div>
	</div>
    <?php
}
