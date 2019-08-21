<?php
/**
/* Template Name: Booking Form Page
 * The template for displaying the booking form page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Transfers
 * @since Transfers 1.0
 */
 
if (!function_exists('is_transfers_plugin_active') || !is_transfers_plugin_active()) {
	wp_redirect(home_url('/'));
	exit;
} 
 
get_header();  
get_sidebar('under-header');

global $post, $transfers_theme_globals, $transfers_plugin_globals, $transfers_plugin_post_types, $transfers_extra_items_post_type, $date_format;

$date_format = get_option('date_format');
$date_format_with_time = $date_format . ' H:i';
$booking_form_page_url = $transfers_plugin_globals->get_booking_form_page_url();

$total_price = 0;

$price_decimal_places = $transfers_plugin_globals->get_price_decimal_places();
$default_currency_symbol = $transfers_plugin_globals->get_default_currency_symbol();
$show_currency_symbol_after = $transfers_plugin_globals->show_currency_symbol_after();
$enc_key = $transfers_theme_globals->get_enc_key();
$add_captcha_to_forms = $transfers_theme_globals->add_captcha_to_forms();

$page_id = $post->ID;
$page_custom_fields = get_post_custom( $page_id);

$page_sidebar_positioning = null;
if (isset($page_custom_fields['page_sidebar_positioning'])) {
	$page_sidebar_positioning = $page_custom_fields['page_sidebar_positioning'][0];
	$page_sidebar_positioning = empty($page_sidebar_positioning) ? '' : $page_sidebar_positioning;
}

$section_class = 'full-width';
if ($page_sidebar_positioning == 'both')
	$section_class = 'one-half';
else if ($page_sidebar_positioning == 'left' || $page_sidebar_positioning == 'right') 
	$section_class = 'three-fourth';

$departure_is_private = null;
if (isset($_GET['depp']) && !empty($_GET['depp']))
	$departure_is_private = intval(wp_kses($_GET['depp'], '')) == 1 ? true : false;

$return_is_private = null;
if (isset($_GET['retp']) && !empty($_GET['retp']))
	$return_is_private = intval(wp_kses($_GET['retp'], '')) == 1 ? true : false;
	
$people = 1;
if (isset($_GET['ppl']) && !empty($_GET['ppl']))
	$people = intval(wp_kses($_GET['ppl'], '')) > 0 ? intval(wp_kses($_GET['ppl'], '')) : $people;
	
$dep_availability_id = null;
if (isset($_GET['depavid']) && !empty($_GET['depavid']))
	$dep_availability_id = intval(wp_kses($_GET['depavid'], ''));

$dep_slot_minutes_number = 0;
if (isset($_GET['depslot']) && !empty($_GET['depslot']))
	$dep_slot_minutes_number = intval(wp_kses($_GET['depslot'], ''));
	
$ret_availability_id = null;
if (isset($_GET['retavid']) && !empty($_GET['retavid']))
	$ret_availability_id = intval(wp_kses($_GET['retavid'], ''));
	
$departure_date = null;
if (isset($_GET['dep']) && !empty($_GET['dep']))
	$departure_date = isset($_GET['dep']) && !empty($_GET['dep']) ? date(TRANSFERS_PHP_DATE_FORMAT, strtotime(wp_kses($_GET['dep'], ''))) : null;
else 
	$departure_date = date(TRANSFERS_PHP_DATE_FORMAT);
	
$availability_entry_dep = $transfers_plugin_post_types->get_availability_entry($dep_availability_id, $departure_date);

if ($availability_entry_dep != null) {
	if ($departure_is_private)
		$total_price += $availability_entry_dep->price_private * $people;
	else {
		$total_price += ($availability_entry_dep->price_share * $people);
	}
	
}

$return_date = null;
$availability_entry_ret = null;

if (isset($_GET['ret']) && !empty($_GET['ret'])) {

	$ret_slot_minutes_number = 0;
	if (isset($_GET['retslot']) && !empty($_GET['retslot']))
		$ret_slot_minutes_number = intval(wp_kses($_GET['retslot'], ''));
		
	$return_date = isset($_GET['ret']) && !empty($_GET['ret']) ? date(TRANSFERS_PHP_DATE_FORMAT, strtotime(wp_kses($_GET['ret'], ''))) : null;
	$availability_entry_ret = $transfers_plugin_post_types->get_availability_entry($ret_availability_id, $return_date);
	
	if ($availability_entry_ret != null) {
		if ($return_is_private)
			$total_price += $availability_entry_ret->price_private * $people;
		else {
			$total_price += ($availability_entry_ret->price_share * $people);
		}
	}
}
	
$formatted_total_price = '<span class="total_price_sum">' . number_format_i18n( $total_price, $price_decimal_places ) . '</span>';
	
$current_date = date(TRANSFERS_PHP_DATE_FORMAT);

if (empty($dep_availability_id)) {
	$error_text = esc_html__('You have not selected a valid transfer. Please use our advanced search facility to find and select a valid transfer before proceeding.', 'transfers');
} else if (!isset($departure_date) || $departure_date == null || strtotime($departure_date) < strtotime($current_date)) {
	$error_text = esc_html__('You have not selected a valid departure date for your transfer. Please use our advanced search facility to select a valid transfer before proceeding.', 'transfers');
} else if ((isset($return_date) && $return_date != null) && (!isset($departure_date) || $departure_date == null || strtotime($departure_date) > strtotime($return_date) || strtotime($return_date) < strtotime($current_date))) {
	$error_text = esc_html__('You have not selected a valid return date for your transfer. Please use our advanced search facility to select a valid transfer before proceeding.', 'transfers');
} else { 
	if ($availability_entry_dep == null) {
		$error_text = esc_html__('The transfer you have selected is no longer available. Please use our advanced search facility to find and select a valid transfer before proceeding.', 'transfers');
	} 
}

$c_val_1 = mt_rand(1, 20);
$c_val_2 = mt_rand(1, 20);

$c_val_1_str = transfers_encrypt($c_val_1, $enc_key);
$c_val_2_str = transfers_encrypt($c_val_2, $enc_key);

if (!isset($current_user))
	$current_user = wp_get_current_user();

$user_info = get_userdata($current_user->ID);

$countries = $transfers_theme_globals->get_countries();

$slot_minutes_dep = '';
$slot_minutes_ret = '';
if (!empty($availability_entry_dep)) 
	$slot_minutes_dep = Transfers_Plugin_Utils::display_hours_and_minutes($dep_slot_minutes_number);

if ($availability_entry_ret != null)
	$slot_minutes_ret = Transfers_Plugin_Utils::display_hours_and_minutes($ret_slot_minutes_number);
?>

<script>
	window.bookingRequest = {};
	window.bookingRequest.departureDate = '<?php echo esc_js(isset($departure_date) ? date($date_format, strtotime($departure_date)) . ' ' . $slot_minutes_dep : 'null'); ?>';
	window.bookingRequest.departureDateAlt = '<?php echo esc_js(isset($departure_date) ? date(TRANSFERS_PHP_DATE_FORMAT, strtotime($departure_date)) : 'null'); ?>';
	window.bookingRequest.returnDate = '<?php echo esc_js(isset($return_date) ? date($date_format, strtotime($return_date)) . ' ' . $slot_minutes_ret : 'null'); ?>';
	window.bookingRequest.returnDateAlt = '<?php echo esc_js(isset($return_date) ? date(TRANSFERS_PHP_DATE_FORMAT, strtotime($return_date)) : 'null'); ?>';
	window.bookingRequest.people = <?php echo esc_js($people > 1 ? $people : 1); ?>;
	window.bookingRequest.departureAvailabilityId = <?php echo esc_js($dep_availability_id > 0 ? $dep_availability_id : 0); ?>;	
	window.bookingRequest.departureSlotMinutesNumber = <?php echo esc_js(isset($dep_slot_minutes_number) && $dep_slot_minutes_number > 0 ? $dep_slot_minutes_number : 0); ?>;
	window.bookingRequest.returnAvailabilityId = <?php echo esc_js(isset($ret_availability_id) && $ret_availability_id > 0 ? $ret_availability_id : 0); ?>;
	window.bookingRequest.returnSlotMinutesNumber = <?php echo esc_js(isset($ret_slot_minutes_number) && $ret_slot_minutes_number > 0 ? $ret_slot_minutes_number : 0); ?>;
	window.bookingRequest.departureIsPrivate = <?php echo esc_js(isset($departure_is_private) ? $departure_is_private : 0); ?>;
	window.bookingRequest.returnIsPrivate = <?php echo esc_js(isset($return_is_private) ? $return_is_private : 0); ?>;
	window.bookingRequest.departureExtras = {};
	window.bookingRequest.returnExtras = {};
	window.bookingRequest.totalPrice = <?php echo esc_js($total_price); ?>;
	window.bookingRequest.departureFrom = '<?php echo esc_js(isset($availability_entry_dep) ? $availability_entry_dep->destination_from : ''); ?>';
	window.bookingRequest.departureTo = '<?php echo esc_js(isset($availability_entry_dep) ? $availability_entry_dep->destination_to : ''); ?>';
	window.bookingRequest.returnFrom = '<?php echo esc_js(isset($availability_entry_ret) ? $availability_entry_ret->destination_from : ''); ?>';
	window.bookingRequest.returnTo = '<?php echo esc_js(isset($availability_entry_ret) ? $availability_entry_ret->destination_to : ''); ?>';	
	window.bookingRequest.departureTransportType = '<?php echo esc_js(isset($availability_entry_dep) ? $availability_entry_dep->transport_type : ''); ?>';	
	window.bookingRequest.returnTransportType = '<?php echo esc_js(isset($availability_entry_ret) ? $availability_entry_ret->transport_type : ''); ?>';	
	window.bookingFormPageUrl = '<?php echo esc_js($booking_form_page_url); ?>';
	window.priceDecimalPlaces = <?php echo esc_js($price_decimal_places); ?>;
	window.addCaptchaToForms = <?php echo esc_js($add_captcha_to_forms); ?>;
	window.useWooCommerceForCheckout = <?php echo esc_js($transfers_plugin_globals->use_woocommerce_for_checkout()); ?>;
	window.wooCartPageUri = '<?php echo esc_js(transfers_get_cart_page_url()); ?>';
	window.yesLabel = '<?php echo esc_js(__('Yes', 'transfers')); ?>';
	window.noLabel = '<?php echo esc_js(__('No', 'transfers')); ?>';
</script>
	<!-- Page info -->
	<?php if (!empty($availability_entry_dep))  { ?>
	<header class="output step1 step2 color <?php echo (isset($return_date)) ? 'twoway' : ''; ?>">
		<div class="wrap">
			<div>
				<p><?php echo date($date_format, strtotime($departure_date)); ?> <small><?php esc_html_e('at', 'transfers'); ?></small> <?php echo esc_html($slot_minutes_dep); ?></p>
				<p><?php echo esc_html($availability_entry_dep->destination_from); ?> <small><?php esc_html_e('to', 'transfers'); ?></small> <?php echo esc_html($availability_entry_dep->destination_to); ?> <small><?php esc_html_e('by', 'transfers'); ?></small> <?php echo esc_html($availability_entry_dep->transport_type); ?> (<?php echo esc_html($departure_is_private ? __('private', 'transfers') : __('shared', 'transfers')); ?>)</p>
			</div>
			<?php if ($availability_entry_ret != null) { ?>
			<div>
				<p><?php echo date($date_format, strtotime($return_date)); ?> <small><?php esc_html_e('at', 'transfers'); ?></small> <?php echo esc_html($slot_minutes_ret); ?></p>
				<p><?php echo esc_html($availability_entry_ret->destination_from); ?> <small><?php esc_html_e('to', 'transfers'); ?></small> <?php echo esc_html($availability_entry_ret->destination_to); ?> <small><?php esc_html_e('by', 'transfers'); ?></small> <?php echo esc_html($availability_entry_ret->transport_type); ?> (<?php echo esc_html($return_is_private ? __('private', 'transfers') : __('shared', 'transfers')); ?>)</p>
			</div>
			<?php } ?>
		</div>
	</header>
	<?php } ?>
	<header class="site-title color step3" style="display:none">
		<div class="wrap">
			<div class="container">
				<h1><?php esc_html_e('Thank you. Your booking is now confirmed.', 'transfers'); ?></h1>
			</div>
		</div>
	</header>
	<div class="wrap">
		<div class="row">
			<!--- Content -->
			<?php
			if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'left')
				get_sidebar('left');
			?>
			<div class="<?php echo esc_attr($section_class); ?>">
				<?php  if ( have_posts() ) : the_post(); ?>
				<div class="textongrey content">
					<?php if ( has_post_thumbnail() ) { ?>
					<figure class="entry-featured">						
						<?php the_post_thumbnail(TRANSFERS_CONTENT_IMAGE_SIZE, array('title' => '')); ?>
						<div class="overlay">
							<a href="<?php esc_url(get_the_permalink()) ?>" class="expand">+</a>
						</div>
					</figure>
					<?php } ?>
					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'transfers' ) ); ?>
					<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
				</div>
				<?php endif; ?>	
				<?php				
				if (!empty($error_text)) {
					echo esc_html($error_text);
				} else {
				?>
				<form method="post" action="<?php echo esc_url(transfers_get_current_page_url()); ?>" name="form-booking" id="form-booking">
					<div class="row">
						<!--- Content -->
						<div class="full-width step1">
							<h2><?php esc_html_e('Extra items', 'transfers'); ?></h2>
							<p><?php esc_html_e('Please select the total number of extra items for your transfers. If you arrive with more extra items than specified at booking, we cannot guarantee to transport them. In case we are able to transport them, we will charge you an additional fee.', 'transfers'); ?></p>
						</div>
						<div class="full-width step2" style="display:none">
							<h2><?php esc_html_e('Passenger details', 'transfers'); ?></h2>
							<p><?php esc_html_e('Please ensure all of the required fields are completed at the time of booking. This information is imperative to ensure a smooth journey.<br />All fields are required.', 'transfers'); ?></p>
						</div>
						<!--- //Content -->					
						<div class="three-fourth step1">
							<?php
							$departure_extra_items = $transfers_extra_items_post_type->list_extra_items_by_transport_type($availability_entry_dep->transport_type_id);
							if ($departure_extra_items && count($departure_extra_items) > 0) { ?>
								<h3><?php esc_html_e('Departure extra items', 'transfers'); ?></h3>
								<table class="data responsive">
									<tr>
										<th><?php esc_html_e('Extra item type', 'transfers'); ?></th>
										<th><?php esc_html_e('Price', 'transfers'); ?></th>
										<th><?php esc_html_e('Quantity', 'transfers'); ?></th>
									</tr>
									<?php 
									foreach ($departure_extra_items as $extra_item) { 
										$transfers_extra_item = new transfers_extra_item($extra_item);
										$item_price = $departure_is_private ? $transfers_extra_item->get_custom_field('price_private') : $transfers_extra_item->get_custom_field('price_shared');
										$formatted_price = number_format_i18n( $item_price, $price_decimal_places );
										
										$teaser = $transfers_extra_item->get_content();
										$teaser = transfers_strip_tags_and_shorten_by_words($teaser, 20);
										$max_allowed = $departure_is_private ? $transfers_extra_item->get_custom_field('max_allowed_per_private_transfer') : $transfers_extra_item->get_custom_field('max_allowed_per_shared_transfer');
										if ($max_allowed > 0) {
									?>
									<tr>
										<td>
											<span name="dep_extra_item_title_<?php echo esc_attr($extra_item->ID); ?>" id="dep_extra_item_title_<?php echo esc_attr($extra_item->ID); ?>"><?php echo esc_html($extra_item->post_title); ?></span>
											<?php if (!empty($teaser)) { ?>
											<i>
											<?php
											$allowed_tags = transfers_get_allowed_content_tags_array();
											echo wp_kses($teaser, $allowed_tags); 
											?>											
											</i>
											<?php } ?>
										</td>
										<td>
										<?php
											if ($show_currency_symbol_after) {
												echo esc_html($formatted_price . ' ' . $default_currency_symbol);
											} else {
												echo esc_html($default_currency_symbol . ' ' . $formatted_price);
											}
										?>
											<input type="hidden" name="dep_extra_item_price_<?php echo esc_attr($extra_item->ID); ?>" id="dep_extra_item_price_<?php echo esc_attr($extra_item->ID); ?>" value="<?php echo esc_attr($item_price); ?>">
										</td>
										<td>
										<select class="dep_extra_item_quantity" name="dep_extra_item_quantity_<?php echo esc_attr($extra_item->ID); ?>" id="dep_extra_item_quantity_<?php echo esc_attr($extra_item->ID); ?>">
										<?php for ($i=0;$i<=$max_allowed;$i++) {?>
										<option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></option>
										<?php } ?>
										</select>
										</td>
									</tr>
								<?php 
										}
									} // foreach?>
								</table>
							<?php } // if ?>
							<?php 
							if ($availability_entry_ret != null) {
								$return_extra_items = $transfers_extra_items_post_type->list_extra_items_by_transport_type($availability_entry_ret->transport_type_id);
								if ($return_extra_items && count($return_extra_items) > 0) { ?>
									<h3><?php esc_html_e('Return extra items', 'transfers'); ?></h3>
									<table class="data responsive">
										<tr>
											<th><?php esc_html_e('Extra item type', 'transfers'); ?></th>
											<th><?php esc_html_e('Price', 'transfers'); ?></th>
											<th><?php esc_html_e('Quantity', 'transfers'); ?></th>
										</tr>
										<?php 
										foreach ($return_extra_items as $extra_item) { 
											$transfers_extra_item = new transfers_extra_item($extra_item);
											$item_price = $return_is_private ? $transfers_extra_item->get_custom_field('price_private') : $transfers_extra_item->get_custom_field('price_shared');
											$formatted_price = number_format_i18n( $item_price, $price_decimal_places );
											
											$teaser = $transfers_extra_item->get_content();
											$teaser = transfers_strip_tags_and_shorten_by_words($teaser, 20);
											$max_allowed = $return_is_private ? $transfers_extra_item->get_custom_field('max_allowed_per_private_transfer') : $transfers_extra_item->get_custom_field('max_allowed_per_shared_transfer');
											if ($max_allowed > 0) {
										?>
										<tr>
											<td>
												<span name="ret_extra_item_title_<?php echo esc_attr($extra_item->ID); ?>" id="ret_extra_item_title_<?php echo esc_attr($extra_item->ID); ?>"><?php echo esc_html($extra_item->post_title); ?></span>
												<?php if (!empty($teaser)) { ?>
												<i>
												<?php
												$allowed_tags = transfers_get_allowed_content_tags_array();
												echo wp_kses($teaser, $allowed_tags); 
												?>													
												</i>
												<?php } ?>
											</td>
											<td>
											<?php
												if ($show_currency_symbol_after) {
													echo esc_html($formatted_price . ' ' . $default_currency_symbol . '');
												} else {
													echo esc_html('' . $default_currency_symbol . ' ' . $formatted_price);
												}
											?>
												<input type="hidden" name="ret_extra_item_price_<?php echo esc_attr($extra_item->ID); ?>" id="ret_extra_item_price_<?php echo esc_attr($extra_item->ID); ?>" value="<?php echo esc_attr($item_price); ?>">
											</td>
											<td>
											<select class="ret_extra_item_quantity" name="ret_extra_item_quantity_<?php echo esc_attr($extra_item->ID); ?>" id="ret_extra_item_quantity_<?php echo esc_attr($extra_item->ID); ?>">
											<?php for ($i=0;$i<=$max_allowed;$i++) {?>
											<option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></option>
											<?php } ?>
											</select>
											</td>
										</tr>
										<?php 
												}
											} // foreach?>
									</table>
									<?php } // if ?>
								<?php } // if ?>
							<div class="actions">
								<a href="#" onclick="history.go(-1); return false;" class="btn medium step1-back back"><?php esc_html_e('Go back', 'transfers'); ?></a>
								<a href="#" class="btn medium color step1-next right"><?php esc_html_e('Continue', 'transfers'); ?></a>
							</div>
						</div>
						<div class="three-fourth step2" style="display:none"> 
							<div class="error captcha_error" style="display:none">
								<p><?php esc_html_e('Invalid captcha. Please answer captcha correctly.', 'transfers'); ?></p>
							</div>
							<fieldset>
								<div class="f-row">
									<div class="one-half">
										<label for="billing_first_name"><?php esc_html_e('First name', 'transfers') ?></label>
										<input type="text" id="billing_first_name" name="billing_first_name" value="<?php echo esc_attr($user_info != null && isset($user_info->billing_first_name) ? $user_info->billing_first_name : ''); ?>" />
									</div>
									<div class="one-half">
										<label for="billing_last_name"><?php esc_html_e('Last name', 'transfers') ?></label>
										<input type="text" id="billing_last_name" name="billing_last_name" value="<?php echo esc_attr($user_info != null && isset($user_info->billing_last_name) ? $user_info->billing_last_name : ''); ?>" />
									</div>
								</div>
								<div class="f-row">
									<div class="one-half">
										<label for="billing_email"><?php esc_html_e('Email address', 'transfers') ?></label>
										<input type="email" id="billing_email" name="billing_email" value="<?php echo esc_attr($user_info != null && isset($user_info->billing_email) ? $user_info->billing_email : ''); ?>" />
									</div>
									<div class="one-half">
										<label for="billing_phone"><?php esc_html_e('Telephone', 'transfers') ?></label>
										<input type="text" id="billing_phone" name="billing_phone" value="<?php echo esc_attr($user_info != null && isset($user_info->billing_phone) ? $user_info->billing_phone : ''); ?>" />
									</div>
								</div>
								<div class="f-row">
									<div class="one-half">
										<label for="billing_address_1"><?php esc_html_e('Street address', 'transfers') ?></label>
										<input type="text" id="billing_address_1" name="billing_address_1" value="<?php echo esc_attr($user_info != null && isset($user_info->billing_address_1) ? $user_info->billing_address_1 : ''); ?>" />
									</div>
									<div class="one-half">
										<label for="billing_city"><?php esc_html_e('City', 'transfers') ?></label>
										<input type="text" id="billing_city" name="billing_city" value="<?php echo esc_attr($user_info != null && isset($user_info->billing_city) ? $user_info->billing_city : ''); ?>" />
									</div>
								</div>
								<div class="f-row">
									<div class="one-half">
										<label for="billing_postcode"><?php esc_html_e('Post code', 'transfers') ?></label>
										<input type="text" id="billing_postcode" name="billing_postcode" value="<?php echo esc_attr($user_info != null && isset($user_info->billing_postcode) ? $user_info->billing_postcode : ''); ?>" />
									</div>
									<div class="one-half">
										<label for="billing_state"><?php esc_html_e('State', 'transfers') ?></label>
										<input type="text" id="billing_state" name="billing_state" value="<?php echo esc_attr($user_info != null && isset($user_info->billing_state) ? $user_info->billing_state : ''); ?>" />
									</div>
								</div>
								<div class="f-row">
									<div class="one-half">
										<label for="billing_country"><?php esc_html_e('Country', 'transfers') ?></label>
										<select id="billing_country" name="billing_country">
										<?php									
											foreach ($countries as $short_country => $long_country) {
												echo sprintf("<option value='%s'>%s</option>", esc_html($short_country), esc_html($long_country));
											}
										?>
										</select>
									</div>
								<?php if ($add_captcha_to_forms) { ?>
									<div class="one-half captcha">
										<label><?php echo sprintf(esc_html__('How much is %d + %d', 'transfers'), $c_val_1, $c_val_2) ?>?</label>
										<input type="text" id="c_val_s_book" name="c_val_s" class="required" />
										<input type="hidden" name="c_val_1" id="c_val_1_book" value="<?php echo esc_attr($c_val_1_str); ?>" />
										<input type="hidden" name="c_val_2" id="c_val_2_book" value="<?php echo esc_attr($c_val_2_str); ?>" />
									</div>
								</div>
								<?php } ?>
								
								<?php wp_nonce_field('booking_form','booking_form_nonce'); ?>
							</fieldset>
						
							<div class="actions">
								<a href="#" onclick="history.go(-1); return false;" class="btn medium step2-back back"><?php esc_html_e('Go back', 'transfers'); ?></a>
								<input class="right" name="step2-next" type="submit" value="<?php esc_attr_e('Continue', 'transfers'); ?>" />
							</div>
						</div>
						<div class="three-fourth step3" style="display:none">
							<div class="box readonly">
								<h3><?php esc_html_e('Passenger details', 'transfers') ?></h3>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Name and surname', 'transfers') ?></div>
									<div class="three-fourth confirmation_full_name"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Telephone', 'transfers') ?></div>
									<div class="three-fourth confirmation_telephone"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Email', 'transfers') ?></div>
									<div class="three-fourth confirmation_email"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Street address', 'transfers') ?></div>
									<div class="three-fourth confirmation_address"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('City', 'transfers') ?></div>
									<div class="three-fourth confirmation_city"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Post code', 'transfers') ?></div>
									<div class="three-fourth confirmation_post_code"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('State', 'transfers') ?></div>
									<div class="three-fourth confirmation_state"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Country', 'transfers') ?></div>
									<div class="three-fourth confirmation_country"></div>
								</div>
								<h3><?php esc_html_e('Departure Transfer details', 'transfers') ?></h3>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Date', 'transfers') ?></div>
									<div class="three-fourth confirmation_departure_date"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('From', 'transfers') ?></div>
									<div class="three-fourth confirmation_departure_from"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('To', 'transfers') ?></div>
									<div class="three-fourth confirmation_departure_to"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Transport type', 'transfers') ?></div>
									<div class="three-fourth confirmation_departure_transport_type"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Is private?', 'transfers') ?></div>
									<div class="three-fourth confirmation_departure_is_private"></div>
								</div>
								<div class="f-row">
									<div class="one-fourth"><?php esc_html_e('Extras', 'transfers') ?></div>
									<div class="three-fourth confirmation_departure_extras"></div>
								</div>
								<div class="confirmation_return_details">
									<h3><?php esc_html_e('Return Transfer details', 'transfers') ?></h3>
									<div class="f-row">
										<div class="one-fourth"><?php esc_html_e('Date', 'transfers') ?></div>
										<div class="three-fourth confirmation_return_date"></div>
									</div>
									<div class="f-row">
										<div class="one-fourth"><?php esc_html_e('From', 'transfers') ?></div>
										<div class="three-fourth confirmation_return_from"></div>
									</div>
									<div class="f-row">
										<div class="one-fourth"><?php esc_html_e('To', 'transfers') ?></div>
										<div class="three-fourth confirmation_return_to"></div>
									</div>
									<div class="f-row">
										<div class="one-fourth"><?php esc_html_e('Transport type', 'transfers') ?></div>
										<div class="three-fourth confirmation_return_transport_type"></div>
									</div>
									<div class="f-row">
										<div class="one-fourth"><?php esc_html_e('Is private?', 'transfers') ?></div>
										<div class="three-fourth confirmation_return_is_private"></div>
									</div>
									<div class="f-row">
										<div class="one-fourth"><?php esc_html_e('Extras', 'transfers') ?></div>
										<div class="three-fourth confirmation_return_extras"></div>
									</div>
								</div>
							</div>
						</div>						
						<aside class="one-fourth sidebar right">
							<!-- Widget -->
							<div class="widget">
								<h4><?php esc_html_e('Booking summary', 'transfers') ?></h4>
								<div class="summary">
									<div>
										<h5><?php esc_html_e('Departure', 'transfers') ?></h5>
										<dl>
											<dt><?php esc_html_e('Date', 'transfers') ?></dt>
											<dd class="departure_date"><?php echo esc_html(date($date_format, strtotime($departure_date))); ?> <?php echo esc_html($slot_minutes_dep); ?></dd>
											<dt><?php esc_html_e('From', 'transfers') ?></dt>
											<dd class="departure_from"><?php echo esc_html($availability_entry_dep->destination_from); ?></dd>
											<dt><?php esc_html_e('To', 'transfers') ?></dt>
											<dd class="departure_to"><?php echo esc_html($availability_entry_dep->destination_to); ?></dd>
											<dt><?php esc_html_e('Vehicle', 'transfers') ?></dt>
											<dd class="departure_transport_type"><?php echo esc_html($availability_entry_dep->transport_type); ?></dd>
											<dt><?php esc_html_e('People', 'transfers') ?></dt>
											<dd class="departure_people"><?php echo esc_html($people); ?></dd>
											<dt><?php esc_html_e('Extras', 'transfers') ?></dt>
											<dd class="departure_extras"></dd>
										</dl>
									</div>
									<?php if ($availability_entry_ret != null) { ?>
									<div>
										<h5><?php esc_html_e('Return', 'transfers') ?></h5>
										<dl>
											<dt><?php esc_html_e('Date', 'transfers') ?></dt>
											<dd class="return_date"><?php echo esc_html(date($date_format, strtotime($return_date))); ?> <?php echo esc_html($slot_minutes_ret); ?></dd>
											<dt><?php esc_html_e('From', 'transfers') ?></dt>
											<dd class="return_from"><?php echo esc_html($availability_entry_ret->destination_from); ?></dd>
											<dt><?php esc_html_e('To', 'transfers') ?></dt>
											<dd class="return_to"><?php echo esc_html($availability_entry_ret->destination_to); ?></dd>
											<dt><?php esc_html_e('Vehicle', 'transfers') ?></dt>
											<dd class="return_transport_type"><?php echo esc_html($availability_entry_ret->transport_type); ?></dd>
											<dt><?php esc_html_e('People', 'transfers') ?></dt>
											<dd class="return_people"><?php echo esc_html($people); ?></dd>
											<dt><?php esc_html_e('Extras', 'transfers') ?></dt>
											<dd class="return_extras"></dd>
										</dl>
									</div>
									<?php } ?>
									<dl class="total">
										<dt><?php esc_html_e('Total', 'transfers') ?></dt>
										<dd>
										<?php
											if ($show_currency_symbol_after) {
												echo wp_kses(($formatted_total_price . ' ' . $default_currency_symbol . ''), array('span' => array('class' => array())));
											} else {
												echo wp_kses(('' . $default_currency_symbol . ' ' . $formatted_total_price), array('span' => array('class' => array())));
											}
										?>
										</dd>
									</dl>
								</div>
							</div>
							<!-- //Widget -->
						</aside>
					</form>
					<?php 
					}					
					?>
				</div>
			</div>
			<!-- //Form -->
			<!--- // Content -->
			<?php
			if ($page_sidebar_positioning == 'both' || $page_sidebar_positioning == 'right')
				get_sidebar('right');
			?>
		</div>
	</div>	
<?php 
get_footer();