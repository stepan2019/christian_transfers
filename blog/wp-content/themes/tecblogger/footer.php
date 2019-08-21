<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package tecblogger
 */

?>

<?php $customizer_design = esc_url('https://themepoints.com'); ?>




<div class="footer-top-area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				
			</div>
		</div>
	</div>
</div>


<!-- Footer -->
<div class="footer-area text-center">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php if(get_theme_mod( 'tecblogger_social_icons' ) == true) : ?>
					<div class="site-socialprofiles">
						<ul>
						   <?php $tech_socials_qu = array(                    
								'tech_facebook' 	=> 'facebook',
								'tech_twitter' 		=> 'twitter',
								'tech_instagram' 	=> 'instagram',
								'tech_google' 		=> 'google-plus',
								'tech_youtube' 		=> 'youtube',
								'tech_pinterest' 	=> 'pinterest',
							);

							foreach ( $tech_socials_qu as $key => $value ) {
								if ( get_theme_mod( $key,'#' ) != '' ) {?>

								<li><a href="<?php echo  esc_url( esc_html( get_theme_mod( $key,'#' ) ) ); ?>"><i class="fa fa-<?php echo esc_attr( $value ); ?>"></i></a></li>

								<?php 
								}
							}
							?>
						</ul>
					</div>
				<?php endif; ?>

				<div class="site-info">
					<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'tecblogger' ) ); ?>"><?php printf( esc_html__( 'Powered by %s', 'tecblogger' ), 'WordPress' ); ?></a>
					<span class="sep"> | </span>
					<?php printf( esc_html__( '%1$s by %2$s.', 'tecblogger' ), 'Tecblogger', '<a class="developed" href="'.$customizer_design.'" rel="designer">Themepoints</a>' ); ?>
				</div><!-- .site-info -->
			</div>	
		</div>
	</div>
</div>	<!-- End Footer -->


<?php wp_footer(); ?>

</body>
</html>
