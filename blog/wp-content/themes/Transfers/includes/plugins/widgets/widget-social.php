<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: Transfers Social Address

-----------------------------------------------------------------------------------*/


// Add function to widgets_init that'll load our widget.
add_action( 'widgets_init', 'transfers_social_widgets' );

// Register widget.
function transfers_social_widgets() {
	register_widget( 'transfers_Social_Widget' );
}

// Widget class.
class transfers_social_widget extends WP_Widget {


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
	function __construct() {
	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'transfers_social_widget', 'description' => esc_html__('Transfers: Social Widget', 'transfers') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 550, 'id_base' => 'transfers_social_widget' );

		/* Create the widget. */
		parent::__construct( 'transfers_social_widget', esc_html__('Transfers: Social Widget', 'transfers'), $widget_ops, $control_ops );
	}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/
	
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$facebook_id = $instance['facebook_id'];
		$twitter_id = $instance['twitter_id'];
		$gplus_profile = $instance['gplus_profile'];	
		$linked_in_profile = $instance['linked_in_profile'];	
		$vimeo_profile = $instance['vimeo_profile'];
		$pinterest_profile = $instance['pinterest_profile'];

		$allowedtags = transfers_get_allowed_widgets_tags_array();
		
		/* Before widget (defined by themes). */
		echo wp_kses($before_widget, $allowedtags);

		/* Display Widget */
		/* Display the widget title if one was input (before and after defined by themes). */
		?>
			<article class="one-fourth">
				<?php 			
				if ( $title )
					echo wp_kses(($before_title . $title . $after_title), $allowedtags);
				?>
				<ul class="social">
				<?php
					if (!empty($facebook_id))
						echo '<li><a href="http://www.facebook.com/' . esc_attr($facebook_id) . '" title="facebook"><span class="social-icon-facebook"></span></a></li>';
					if (!empty($twitter_id))
						echo '<li><a href="http://twitter.com/' . esc_attr($twitter_id) . '" title="twitter"><span class="social-icon-twitter"></span></a></li>';
					if (!empty($linked_in_profile))
						echo '<li><a href="' . esc_url($linked_in_profile) . '" title="linkedin"><span class="social-icon-linkedin"></span></a></li>';
					if (!empty($gplus_profile))
						echo '<li><a href="' . esc_url($gplus_profile) . '" title="gplus"><span class="social-icon-gplus"></span></a></li>';
					if (!empty($vimeo_profile))
						echo '<li><a href="' . esc_url($vimeo_profile) . '" title="vimeo"><span class="social-icon-vimeo"></span></a></li>';
					if (!empty($pinterest_profile))
						echo '<li><a href="' . esc_url($pinterest_profile) . '" title="pinterest"><span class="social-icon-pinterest"></span></a></li>';
					?>
				</ul>
			</article>
		<?php

		/* After widget (defined by themes). */
		echo wp_kses($after_widget, $allowedtags);
	}


/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['facebook_id'] = strip_tags( $new_instance['facebook_id'] );
		$instance['twitter_id'] = strip_tags( $new_instance['twitter_id'] );
		$instance['gplus_profile'] = strip_tags( $new_instance['gplus_profile'] );
		$instance['linked_in_profile'] = strip_tags( $new_instance['linked_in_profile'] );
		$instance['vimeo_profile'] = strip_tags( $new_instance['vimeo_profile'] );
		$instance['pinterest_profile'] = strip_tags( $new_instance['pinterest_profile'] );
		
		return $instance;
	}
	

/*-----------------------------------------------------------------------------------*/
/*	Widget Settings
/*-----------------------------------------------------------------------------------*/
	 
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => esc_html__('Follow us', 'transfers'),
			'facebook_id' => '',
			'twitter_id' => '',
			'linked_in_profile' => '',
			'gplus_profile' => '',
			'vimeo_profile' => '',
			'pinterest_profile' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'transfers') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr ($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'facebook_id' ) ); ?>"><?php esc_html_e('Facebook ID:', 'transfers') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook_id' ) ); ?>" value="<?php echo esc_attr ($instance['facebook_id']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_id' ) ); ?>"><?php esc_html_e('Twitter ID:', 'transfers') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_id' ) ); ?>" value="<?php echo esc_attr ($instance['twitter_id']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'gplus_profile' ) ); ?>"><?php esc_html_e('GPlus url:', 'transfers') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'gplus_profile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'gplus_profile' ) ); ?>" value="<?php echo esc_attr ($instance['gplus_profile']); ?>" />
		</p>		

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'linked_in_profile' ) ); ?>"><?php esc_html_e('LinkedIn url:', 'transfers') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linked_in_profile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linked_in_profile') ); ?>" value="<?php echo esc_attr ($instance['linked_in_profile']); ?>" />
		</p>		

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'vimeo_profile' ) ); ?>"><?php esc_html_e('Vimeo profile:', 'transfers') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'vimeo_profile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'vimeo_profile' ) ); ?>" value="<?php echo esc_attr ($instance['vimeo_profile']); ?>" />
		</p>		

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'pinterest_profile' ) ); ?>"><?php esc_html_e('Pinterest url:', 'transfers') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest_profile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest_profile' ) ); ?>" value="<?php echo esc_attr ($instance['pinterest_profile']); ?>" />
		</p>		
		
	<?php
	}
}