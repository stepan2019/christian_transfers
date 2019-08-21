<?php
	global $slot_minutes, $slot_minutes_number, $availability_result, $date_format, $transport_type_price, $transfer_is_return, $transport_type_is_private, $transfer_class, $transfers_theme_globals, $transfers_plugin_globals, $transfers_destinations_post_type;
	
	$price_decimal_places = $transfers_plugin_globals->get_price_decimal_places();
	$default_currency_symbol = $transfers_plugin_globals->get_default_currency_symbol();
	$show_currency_symbol_after = $transfers_plugin_globals->show_currency_symbol_after();

	$formatted_price = number_format_i18n( $transport_type_price, $price_decimal_places );
	
	$transport_type_id = $availability_result->transport_type_id;
	$transport_type_obj = new transfers_transport_type($transport_type_id);
	$base_id = $transport_type_obj->get_base_id();
	
	$max_people_per_vehicle = $availability_result->max_people_per_vehicle;

	$post_image = $transport_type_obj->get_main_image(TRANSFERS_CONTENT_IMAGE_SIZE);	
	if (empty($post_image)) {
		$post_image = transfers_get_file_uri('/images/uploads/img.jpg');
	}
	
	$transport_type_content = $transport_type_obj->get_content();
	// $transport_type_content = transfers_strip_tags_and_shorten($transport_type_content, 100);
	
	$transport_type_title = $transport_type_obj->get_title();
	
	$people = 0;
	if (isset($_GET['ppl']) && !empty($_GET['ppl']))
		$people = intval(wp_kses($_GET['ppl'], ''));
		
	$departure_date = null;
	if (isset($_GET['dep']) && !empty($_GET['dep']))
		$departure_date = isset($_GET['dep']) && !empty($_GET['dep']) ? date(TRANSFERS_PHP_DATE_FORMAT_ZERO_TIME, strtotime(wp_kses($_GET['dep'], ''))) : null;
	else 
		$departure_date = date(TRANSFERS_PHP_DATE_FORMAT_ZERO_TIME);
		
	$formatted_departure_date = date($date_format, strtotime($departure_date));
		
	$return_date = null;
	$formatted_return_date = '';
	if (isset($_GET['ret']) && !empty($_GET['ret'])) {
		$return_date = isset($_GET['ret']) && !empty($_GET['ret']) ? date(TRANSFERS_PHP_DATE_FORMAT_ZERO_TIME, strtotime(wp_kses($_GET['ret'], ''))) : null;
		$formatted_return_date = date($date_format, strtotime($return_date));
	}
		
?>
<!-- Item -->
<article class="result">
	<div class="one-fourth heightfix">
		<?php if (!empty($post_image)) { ?>
		<img src="<?php echo esc_url($post_image); ?>" alt="<?php echo esc_attr($transport_type_obj->get_title()); ?>" />
		<?php } ?>
	</div>
	<div class="one-half heightfix">
		<h3><?php echo esc_html($transport_type_is_private ? __('Private', 'transfers') : __('Shared', 'transfers')); ?> <?php echo esc_html($transport_type_title); ?> <?php if (!empty($transport_type_content)) { ?><a href="javascript:void(0)" class="trigger color" title="<?php esc_attr_e('Read more', 'transfers') ?>">?</a><?php } ?></h3>
		<ul>
			<li>
				<span class="ico themeenergy_user"></span>
				<p><?php echo sprintf(wp_kses(__('Max <strong>%d people</strong> <br />per vehicle', 'transfers'), array('strong' => array(), 'br' => array())), $max_people_per_vehicle); ?></p>
			</li>
			<li>
				<span class="ico themeenergy_calendar_4"></span>
				<p><?php echo sprintf(wp_kses(__('Departure date<br /><strong>%s</strong>', 'transfers'), array('strong' => array(), 'br' => array())), ($transfer_is_return ? $formatted_return_date : $formatted_departure_date)); ?></p>
			</li>
			<li>
				<span class="ico themeenergy_time"></span>
				<p><?php echo sprintf(wp_kses(__('Departure time<br /><strong>%s</strong>', 'transfers'), array('strong' => array(), 'br' => array())), $slot_minutes); ?></p>
			</li>
		</ul>
	</div>
	<div class="one-fourth heightfix">
		<div>
			<div class="price">
				<?php 
				if ($show_currency_symbol_after) {
					echo esc_html($formatted_price . ' ' . $default_currency_symbol . '');
				} else {
					echo esc_html('' . $default_currency_symbol . ' ' . $formatted_price);
				}
				?>
			</div>
			<span class="meta"><?php esc_html_e('per passenger', 'transfers') ?></span>
			<a href="#" class="btn grey large select-avail-slot select-avail-slot-time-<?php echo $slot_minutes_number; ?> <?php echo esc_attr($transfer_is_return ? "select-avail-ret-slot" : "select-avail-dep-slot"); ?>  <?php echo esc_attr($transport_type_is_private ? "select-avail-slot-private" : ""); ?>" id="select-avail-slot-<?php echo esc_attr($availability_result->Id); ?>"><?php esc_html_e('select', 'transfers') ?></a>
		</div>
	</div>
	<div class="full-width information">	
		<a href="javascript:void(0)" class="close color" title="Close">x</a>
		<p>
		<?php 
			$allowed_tags = transfers_get_allowed_content_tags_array();
			echo wp_kses($transport_type_content, $allowed_tags); 
		?>
		</p>
	</div>
</article>
<!-- //Item -->