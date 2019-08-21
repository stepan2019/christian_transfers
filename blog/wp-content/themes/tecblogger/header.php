<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package tecblogger
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>


<!-- Start Header -->
<header class="header">
	<!-- header-area -->
	<div class="header-area">
		<div class="header-main-area">
			<div class="container">
				<div class="row">
					<div class="logo">
						<div class="col-md-12">
							<?php if(get_theme_mod('tecblogger_logo_uploader')): ?>
								<a href="<?php echo esc_url(site_url()); ?>"><img src="<?php echo esc_url(get_theme_mod('tecblogger_logo_uploader'));
									?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
							<?php else: ?>
								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="background-area"></div>
		<div class="background-overlay-area" style="background-position: 50% 0px;"></div>
	</div><!-- end header-area -->

		<!-- main navigation -->
		<div class="mainmenu-area">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="responsivemenu"></div> <!-- responsive mobile menu -->
						
						<!-- navigation menu start -->
						<nav id="site-navigation" class="main-navigation">
					        <?php
					         wp_nav_menu( array
								('container'        => false, 
								'theme_location'    => 'primary',
								'menu_class'        => 'header-navigation',
								'items_wrap'        => '<ul class="header-navigation">%3$s</ul>',
								'fallback_cb'       => 'wp_tecblogger_navwalker::fallback',
	                            'walker'            => new wp_tecblogger_navwalker()
								)); 
							?>
						</nav><!-- navigation menu end -->
					</div><!-- end column -->
				</div><!-- end row -->
			</div><!-- end container -->
		</div><!-- end Navigation -->
</header> <!-- end Header -->