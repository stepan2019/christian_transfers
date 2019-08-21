<?php
/**
 * The sidebar containing the home content widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Transfers
 * @since Transfers 1.0
 */
if ( is_active_sidebar( 'home-content' ) ) { ?>
	<section class="home-content-sidebar">
		<ul>
		<?php dynamic_sidebar( 'home-content' ); ?>
		</ul>
	</section><!-- #secondary -->
<?php } else { ?>
	<section class="home-content-sidebar">
		<?php 
		global $transfers_theme_globals;

		echo '<ul>';
		$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
		the_widget('transfers_Hero_Unit_Widget', null, $widget_args); 
		
		if (function_exists('is_transfers_plugin_active') && is_transfers_plugin_active()) {
			$widget_args = array('before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
			the_widget('transfers_Advanced_Search_Widget', null, $widget_args); 
		}
		
		$widget_default_features = array(
			array('class' => 'award', 'title' => esc_html__('Award winning service', 'transfers'), 'text' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy tinc dolore magna'),
			array('class' => 'clip', 'title' => esc_html__('Benefits for partners', 'transfers'), 'text' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy tinc dolore magna'),
			array('class' => 'heart', 'title' => esc_html__('Free cancellation', 'transfers'), 'text' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy tinc dolore magna'),
			array('class' => 'lock', 'title' => esc_html__('Reliable transfers', 'transfers'), 'text' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy tinc dolore magna'),
			array('class' => 'pig', 'title' => esc_html__('Fixed rates', 'transfers'), 'text' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy tinc dolore magna'),
			array('class' => 'shuttle', 'title' => esc_html__('Quality vehicles', 'transfers'), 'text' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy tinc dolore magna'),
			array('class' => 'telephone', 'title' => esc_html__('24H customer support', 'transfers'), 'text' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy tinc dolore magna'),
			array('class' => 'wallet', 'title' => esc_html__('No booking fees', 'transfers'), 'text' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy tinc dolore magna'),
			array('class' => 'wand', 'title' => esc_html__('Booking flexibility', 'transfers'), 'text' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy tinc dolore magna'),
		);
		
		$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>', 'widget_default_features' => $widget_default_features );
		the_widget('transfers_Iconic_Features_Widget', null, $widget_args);
		
		$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>', 'widget_reverse_color_scheme' => true );
		the_widget('transfers_Call_To_Action_Widget', null, $widget_args); 
		
		if (function_exists('is_transfers_plugin_active') && is_transfers_plugin_active()) {
			$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
			the_widget('transfers_Featured_Services_Widget', null, $widget_args); 
		}

		$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
		the_widget('transfers_Featured_Testimonial_Widget', null, $widget_args); 
		
		// We display this partners widget by default.
		// However, our home page is completely widgetized and you can go to Appearance -> Widgets and add any widgets to the empty-by-default
		// Home Content Widget area for only those widgets to appear.
		// In case you do, you can add these "Our partners" widget by adding a Text Widget the html
		// that looks like the one shown below.
		ob_start();
		?>
		<div class="partners white center">
			<div class="wrap">
				<h2 class="wow fadeIn"><?php esc_html_e("Our partners", "transfers"); ?></h2>
				<div class="one-fifth wow fadeIn"><a href="#"><img src="<?php echo transfers_get_file_uri('/images/uploads/logo1.jpg'); ?>" alt="" /></a></div>
				<div class="one-fifth wow fadeIn" data-wow-delay=".2s"><a href="#"><img src="<?php echo transfers_get_file_uri('/images/uploads/logo2.jpg'); ?>" alt="" /></a></div>
				<div class="one-fifth wow fadeIn" data-wow-delay=".4s"><a href="#"><img src="<?php echo transfers_get_file_uri('/images/uploads/logo3.jpg'); ?>" alt="" /></a></div>
				<div class="one-fifth wow fadeIn" data-wow-delay=".6s"><a href="#"><img src="<?php echo transfers_get_file_uri('/images/uploads/logo4.jpg'); ?>" alt="" /></a></div>
				<div class="one-fifth" data-wow-delay=".8s"><a href="#"><img src="<?php echo transfers_get_file_uri('/images/uploads/logo5.jpg'); ?>" alt="" /></a></div>
			</div>
		</div>
		<?php	
		$output = ob_get_contents();
		ob_end_clean();
		global $wp_widget_factory;
		$widget_obj = $wp_widget_factory->widgets['WP_Widget_Text'];
		if (( $widget_obj instanceof WP_Widget ) ) {
			$widget_args = array( 'before_widget' => '<li class="widget widget-sidebar">', 'after_widget'  => '</li>', 'before_title'  => '<h3>', 'after_title'   => '</h3>' );
			$widget_obj->text = $output;
			the_widget( 'WP_Widget_Text', $widget_obj, $widget_args ); 
		}
		
		echo '</ul>';
		?>
	</section>
<?php } 