<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
   $themename = get_option( 'stylesheet' );
   $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
   return $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'theme-textdomain'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {
	
	$page_sidebars = array(
		'' => esc_html__('No sidebar', 'transfers'),
		'left' => esc_html__('Left sidebar', 'transfers'),
		'right' => esc_html__('Right sidebar', 'transfers'),
		'both' => esc_html__('Left and right sidebars', 'transfers'),
	);
	
	$sort_by_columns = array();
	$sort_by_columns['title'] = esc_html__('Title', 'transfers');
	$sort_by_columns['ID'] = esc_html__('ID', 'transfers');
	$sort_by_columns['date'] =  esc_html__('Publish date', 'transfers');
	$sort_by_columns['rand'] =  esc_html__('Random', 'transfers');
	$sort_by_columns['comment_count'] =  esc_html__('Comment count', 'transfers');

	$color_scheme_array = array(
		'theme-pink' => esc_html__('Default (pink)', 'transfers'),
		'theme-beige' => esc_html__('Beige', 'transfers'),
		'theme-dblue' => esc_html__('Dark blue', 'transfers'),
		'theme-dgreen' => esc_html__('Dark green', 'transfers'),
		'theme-grey' => esc_html__('Grey', 'transfers'),
		'theme-lblue' => esc_html__('Light blue', 'transfers'),
		'theme-lgreen' => esc_html__('Light green', 'transfers'),
		'theme-lime' => esc_html__('Lime', 'transfers'),
		'theme-orange' => esc_html__('Navy', 'transfers'),
		'theme-peach' => esc_html__('Peach', 'transfers'),
		'theme-purple' => esc_html__('Purple', 'transfers'),
		'theme-red' => esc_html__('Red', 'transfers'),
		'theme-teal' => esc_html__('Teal', 'transfers'),
		'theme-turquoise' => esc_html__('Turquoise', 'transfers'),
		'theme-yellow' => esc_html__('Yellow', 'transfers'),		
	);

	$pages = get_pages(); 
	$pages_array = array();
	$pages_array[0] = esc_html__('Select page', 'transfers');
	foreach ( $pages as $page ) {
		$pages_array[$page->ID] = $page->post_title;
	}
	
	$price_decimals_array = array(
		'0' => esc_html__('Zero (e.g. $200)', 'transfers'),
		'1' => esc_html__('One  (e.g. $200.0)', 'transfers'),
		'2' => esc_html__('Two (e.g. $200.00)', 'transfers'),
	);

	$options = array();

	$options[] = array(
		'name' => esc_html__( 'General Settings', 'transfers' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => esc_html__('Website logo', 'transfers'),
		'desc' => esc_html__('Upload your website logo to go in place of default theme logo.', 'transfers'),
		'id' => 'website_logo_upload',
		'type' => 'upload');
		
	if ( ! function_exists( 'get_site_icon_url' ) ) {	
	
		$options[] = array(
			'name' => esc_html__('Favicon', 'transfers'),
			'desc' => esc_html__('Upload your website favicon to go in place of default theme favicon.', 'transfers'),
			'id' => 'website_favicon_upload',
			'type' => 'upload');
	}
		
	$options[] = array(
		'name' => esc_html__('Select color scheme', 'transfers'),
		'desc' => esc_html__('Select website color scheme.', 'transfers'),
		'id' => 'color_scheme_select',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $color_scheme_array);
	
	$options[] = array(
		'name' => esc_html__('Company name', 'transfers'),
		'desc' => esc_html__('Company name displayed on the contact us page.', 'transfers'),
		'id' => 'contact_company_name',
		'std' => 'Transfers LLC',
		'class' => 'mini',
		'type' => 'text');
	
	$options[] = array(
		'name' => esc_html__('Contact address', 'transfers'),
		'desc' => esc_html__('Contact address displayed on the contact us page.', 'transfers'),
		'id' => 'contact_address',
		'std' => '1293 Delancey Street, NY',
		'class' => 'mini',
		'type' => 'text');

	$options[] = array(
		'name' => esc_html__('Contact address latitude', 'transfers'),
		'desc' => esc_html__('Enter your address latitude to use for contact form map', 'transfers'),
		'id' => 'contact_address_latitude',
		'std' => '49.47216',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => esc_html__('Contact address longitude', 'transfers'),
		'desc' => esc_html__('Enter your address longitude to use for contact form map', 'transfers'),
		'id' => 'contact_address_longitude',
		'std' => '-123.76307',
		'class' => 'mini',
		'type' => 'text');
	
	$options[] = array(
		'name' => esc_html__('Footer copyright notice', 'transfers'),
		'desc' => esc_html__('Copyright notice in footer.', 'transfers'),
		'id' => 'copyright_footer',
		'std' => '&copy; transfers.com 2015. All rights reserved.',
		'type' => 'text');
	
	$options[] = array(
		'name' => esc_html__('Configuration Settings', 'transfers'),
		'type' => 'heading');		

	if (function_exists('is_transfers_plugin_active') && is_transfers_plugin_active()) {		

		$slots_array = array();
		$slots_array['1'] = esc_html__('1 minute', 'transfers');
		$slots_array['5'] = esc_html__('5 minutes', 'transfers');
		$slots_array['10'] = esc_html__('10 minutes', 'transfers');
		$slots_array['30'] = esc_html__('30 minutes', 'transfers');
		$slots_array['60'] = esc_html__('60 minutes', 'transfers');
			
		$options[] = array(
			'name' => esc_html__('Transfer time slot increment', 'transfers'),
			'desc' => esc_html__('Time slot increment is used when creating availability time slots for transfers. The increment determines what values the time slot dropdown contains when creating daily, weekly, month entries.', 'transfers'),
			'id' => 'time_slot_increment',
			'std' => 'three',
			'type' => 'select',
			'class' => 'mini', //mini, tiny, small
			'std'	=> '1',
			'options' => $slots_array);
			
		$hour_offset_array = array();
		$hour_offset_array['0'] = esc_html__('0 hours', 'transfers');
		$hour_offset_array['1'] = esc_html__('1 hour', 'transfers');
		$hour_offset_array['2'] = esc_html__('2 hours', 'transfers');
		$hour_offset_array['3'] = esc_html__('3 hours', 'transfers');
		$hour_offset_array['4'] = esc_html__('4 hours', 'transfers');
		$hour_offset_array['5'] = esc_html__('5 hours', 'transfers');
	
		$options[] = array(
			'name' => esc_html__('Search backwards time slot offset', 'transfers'),
			'desc' => esc_html__('When showing search results, show results that include starting times X (this offset) hours before searched-for time', 'transfers'),
			'id' => 'search_time_slot_offset',
			'std' => 'three',
			'type' => 'select',
			'class' => 'mini', //mini, tiny, small
			'std'	=> '1',
			'options' => $hour_offset_array);
			
		$search_results_by_minute_array = array();
		$search_results_by_minute_array['1'] = esc_html__('1 result', 'transfers');
		$search_results_by_minute_array['2'] = esc_html__('2 results', 'transfers');
		$search_results_by_minute_array['3'] = esc_html__('3 results', 'transfers');
		$search_results_by_minute_array['4'] = esc_html__('4 results', 'transfers');
		$search_results_by_minute_array['5'] = esc_html__('5 results', 'transfers');
		$search_results_by_minute_array['6'] = esc_html__('6 results', 'transfers');
		$search_results_by_minute_array['7'] = esc_html__('7 results', 'transfers');
		$search_results_by_minute_array['8'] = esc_html__('8 results', 'transfers');
		$search_results_by_minute_array['9'] = esc_html__('9 results', 'transfers');
		$search_results_by_minute_array['10'] = esc_html__('10 results', 'transfers');
			
		$options[] = array(
			'name' => esc_html__('Search results by minute count', 'transfers'),
			'desc' => esc_html__('If displaying availabilities by minute (running availabilities) this setting determines how many of these you want to show. e.g. if transport is available every 10 minutes, and you set this value to 10, you will show 10 results from now incremenenting by 10 minutes.', 'transfers'),
			'id' => 'search_results_by_minute_count',
			'std' => 'three',
			'type' => 'select',
			'class' => 'mini', //mini, tiny, small
			'std'	=> '1',
			'options' => $search_results_by_minute_array);
	
		$options[] = array(
			'name' => esc_html__('Price decimal places', 'transfers'),
			'desc' => esc_html__('Number of decimal places to show for prices', 'transfers'),
			'id' => 'price_decimal_places',
			'std' => '0',
			'type' => 'select',
			'class' => 'mini', //mini, tiny, small
			'options' => $price_decimals_array);
			
		$options[] = array(
			'name' => esc_html__('Default currency symbol', 'transfers'),
			'desc' => esc_html__('What is your default currency symbol', 'transfers'),
			'id' => 'default_currency_symbol',
			'std' => '$',
			'class' => 'mini', //mini, tiny, small
			'type' => 'text');
	
		$options[] = array(
			'name' => esc_html__('Show currency symbol after price?', 'transfers'),
			'desc' => esc_html__('If this option is checked, currency symbol will show up after the price, instead of before (e.g. 150 $ instead of $150).', 'transfers'),
			'id' => 'show_currency_symbol_after',
			'std' => '0',
			'type' => 'checkbox');
	}
	
	$options[] = array(
		'name' => esc_html__('Enable RTL', 'transfers'),
		'desc' => esc_html__('Enable right-to-left support', 'transfers'),
		'id' => 'enable_rtl',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => esc_html__('Show preloader', 'transfers'),
		'desc' => esc_html__('Show preloader on pages while pages are loading', 'transfers'),
		'id' => 'show_preloader',
		'std' => '1',
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => esc_html__('Add captcha to forms', 'transfers'),
		'desc' => esc_html__('Add simple captcha implemented inside transfers theme to forms (login, register, book, inquire, contact etc)', 'transfers'),
		'id' => 'add_captcha_to_forms',
		'std' => '1',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => esc_html__('Users specify password', 'transfers'),
		'desc' => esc_html__('Let users specify their password when registering', 'transfers'),
		'id' => 'let_users_set_pass',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => esc_html__('Override wp-login.php', 'transfers'),
		'desc' => esc_html__('Override wp-login.php and use custom login, register, forgot password pages', 'transfers'),
		'id' => 'override_wp_login',
		'std' => '0',
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => esc_html__('Page Settings', 'transfers'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => esc_html__('Login page', 'transfers'),
		'desc' => esc_html__('Login page url', 'transfers'),
		'id' => 'login_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => esc_html__('Redirect to after login', 'transfers'),
		'desc' => esc_html__('Page to redirect to after login', 'transfers'),
		'id' => 'redirect_to_after_login',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => esc_html__('Redirect to after logout', 'transfers'),
		'desc' => esc_html__('Page to redirect to after logout', 'transfers'),
		'id' => 'redirect_to_after_logout',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => esc_html__('Register page', 'transfers'),
		'desc' => esc_html__('Register page url', 'transfers'),
		'id' => 'register_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => esc_html__('Reset password page', 'transfers'),
		'desc' => esc_html__('Reset password page url', 'transfers'),
		'id' => 'reset_password_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => esc_html__('Terms &amp; conditions page url', 'transfers'),
		'desc' => esc_html__('Terms &amp; conditions page url', 'transfers'),
		'id' => 'terms_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => esc_html__('Contact page', 'transfers'),
		'desc' => esc_html__('Contact page url', 'transfers'),
		'id' => 'contact_page_url',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $pages_array);
		
	$options[] = array(
		'name' => esc_html__('Blog index sidebar position', 'transfers'),
		'desc' => esc_html__('Select the position (if any) of sidebars to appear on the blog index page of your website.', 'transfers'),
		'id' => 'blog_index_sidebar_position',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $page_sidebars);
		
	$options[] = array(
		'name' => esc_html__('Blog index sort by column', 'transfers'),
		'desc' => esc_html__('Select the column you want blog posts to be sorted in blog index.', 'transfers'),
		'id' => 'blog_index_sort_by_column',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $sort_by_columns);	
		
	$options[] = array(
		'name' => esc_html__("Blog index sort descending", 'transfers'),
		'desc' => esc_html__("Sort posts in descending order on blog index page", 'transfers'),
		'id' => 'blog_index_sort_descending',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => esc_html__("Blog index show posts in grid view", 'transfers'),
		'desc' => esc_html__("Show posts in grid view on blog index page", 'transfers'),
		'id' => 'blog_index_show_grid_view',
		'std' => '0',
		'type' => 'checkbox');		
			
	if (function_exists('is_transfers_plugin_active') && is_transfers_plugin_active()) {
	
		$options[] = array(
			'name' => esc_html__('Services', 'transfers'),
			'type' => 'heading');
			
		$options[] = array(
			'name' => esc_html__("Enable 'Services'", 'transfers'),
			'desc' => esc_html__("Enable the 'Services' data type", 'transfers'),
			'id' => 'enable_services',
			'std' => '1',
			'type' => 'checkbox');		

		$default_classes = "themeenergy_air_conditioning_external\nthemeenergy_air_conditioning_internal\nthemeenergy_blender\nthemeenergy_blender_2\nthemeenergy_blender_3\nthemeenergy_bread_maker_multicookings\nthemeenergy_citrus_squeezer\nthemeenergy_coffee_machine\nthemeenergy_coiled_heater\nthemeenergy_dishwasher\nthemeenergy_drill_perforator\nthemeenergy_electric_kettle_teapot\nthemeenergy_electric_shaver\nthemeenergy_fan\nthemeenergy_fan_1\nthemeenergy_fan_dyson\nthemeenergy_food_processor_1\nthemeenergy_food_processor_2\nthemeenergy_freezer\nthemeenergy_fridge_side_by_side\nthemeenergy_hairdryer\nthemeenergy_heat_fan\nthemeenergy_heater\nthemeenergy_iron\nthemeenergy_juicer\nthemeenergy_kitchen_hood\nthemeenergy_kitchen_hood_1\nthemeenergy_kitchen_timer\nthemeenergy_metal_detector_multimeter_voltmeter\nthemeenergy_microwave\nthemeenergy_mixer\nthemeenergy_paper_shredder\nthemeenergy_radiator\nthemeenergy_refrigerator\nthemeenergy_samovar\nthemeenergy_scale_kitchen_weigher\nthemeenergy_scales\nthemeenergy_sewing_machine\nthemeenergy_stove\nthemeenergy_toaster\nthemeenergy_vacuum_cleaner\nthemeenergy_waffle-iron\nthemeenergy_washing_machine\nthemeenergy_yogurter\nthemeenergy_baby_monitor\nthemeenergy_baby_monitors\nthemeenergy_ball\nthemeenergy_beanbag_maraca\nthemeenergy_bib\nthemeenergy_bicycle_baby_infant\nthemeenergy_birthday_newborn\nthemeenergy_blocks\nthemeenergy_body_suit\nthemeenergy_bottle_infant\nthemeenergy_boy\nthemeenergy_breast_milk\nthemeenergy_breast-feeding_mother\nthemeenergy_buildingkit\nthemeenergy_car\nthemeenergy_chair_baby\nthemeenergy_chamber_pot\nthemeenergy_duck_rubberduck\nthemeenergy_dummy_nipple\nthemeenergy_embryo\nthemeenergy_fertilization_sperm_ovum\nthemeenergy_fork_spoon_baby\nthemeenergy_girl\nthemeenergy_horse_hobby_hobbyhorse\nthemeenergy_mobile_infant\nthemeenergy_napkin\nthemeenergy_pampers_briefs_diapers\nthemeenergy_parental_control_off\nthemeenergy_parental_control_on\nthemeenergy_playpen\nthemeenergy_preserves\nthemeenergy_pyramid\nthemeenergy_RC_car_radiocontrolled\nthemeenergy_scales_baby\nthemeenergy_shirt\nthemeenergy_shoes\nthemeenergy_stadiometer\nthemeenergy_stroller_cane\nthemeenergy_teether\nthemeenergy_tights\nthemeenergy_ultrasound_diagnostics\nthemeenergy_videogame_baby\nthemeenergy_add\nthemeenergy_base\nthemeenergy_base_check\nthemeenergy_base_connect\nthemeenergy_base_delete\nthemeenergy_base_favorite\nthemeenergy_base_new\nthemeenergy_base_remove\nthemeenergy_base_search_in\nthemeenergy_check\nthemeenergy_comment_1\nthemeenergy_comment_2\nthemeenergy_comment_3\nthemeenergy_comment_baloon\nthemeenergy_comment_chat\nthemeenergy_comment_dream\nthemeenergy_connect_close\nthemeenergy_credit_card\nthemeenergy_credit_card_back\nthemeenergy_credit_card_multi\nthemeenergy_delete\nthemeenergy_favorite\nthemeenergy_favorite_add\nthemeenergy_favorite_add_2\nthemeenergy_favorite_add_3\nthemeenergy_favorite_add_4\nthemeenergy_favorite_check_1\nthemeenergy_favorite_check_2\nthemeenergy_favorite_delete_1\nthemeenergy_favorite_delete_2\nthemeenergy_favorite_remove_1\nthemeenergy_favorite_remove_2\nthemeenergy_flag\nthemeenergy_gear\nthemeenergy_glass\nthemeenergy_home\nthemeenergy_key_1\nthemeenergy_key_2\nthemeenergy_lamp\nthemeenergy_lamp_off\nthemeenergy_lamp_on\nthemeenergy_lock\nthemeenergy_minus\nthemeenergy_options_1\nthemeenergy_options_2\nthemeenergy_protect_1\nthemeenergy_protect_2\nthemeenergy_protect_3\nthemeenergy_recycle_bin_1\nthemeenergy_recycle_bin_2\nthemeenergy_recycle_bin_3\nthemeenergy_recycle_bin_4\nthemeenergy_recycle_bin_empty\nthemeenergy_rss\nthemeenergy_search\nthemeenergy_shopping_cart_1\nthemeenergy_shopping_cart_2\nthemeenergy_shopping_cart_3\nthemeenergy_shopping_cart_4\nthemeenergy_star\nthemeenergy_umbrella\nthemeenergy_unlock\nthemeenergy_up\nthemeenergy_wizard_1\nthemeenergy_wizard_2\nthemeenergy_airport\nthemeenergy_ancient_building\nthemeenergy_apartment\nthemeenergy_arch\nthemeenergy_bank\nthemeenergy_belfry\nthemeenergy_bridge_1\nthemeenergy_bridge_2\nthemeenergy_bridge_column\nthemeenergy_building\nthemeenergy_car_wash\nthemeenergy_castle\nthemeenergy_catholic_church\nthemeenergy_church\nthemeenergy_city\nthemeenergy_downtown\nthemeenergy_dwelling_house\nthemeenergy_exhibition\nthemeenergy_factory_1\nthemeenergy_factory_2\nthemeenergy_factory_3\nthemeenergy_fire\nthemeenergy_firefighters\nthemeenergy_for_rent\nthemeenergy_for_sale\nthemeenergy_front_gate\nthemeenergy_garage_1\nthemeenergy_garage_2\nthemeenergy_garage_hangar\nthemeenergy_garage_multilevel\nthemeenergy_government\nthemeenergy_home_1\nthemeenergy_home_2\nthemeenergy_home_3\nthemeenergy_home_4\nthemeenergy_home_farm\nthemeenergy_hospital_clinic\nthemeenergy_housemulti_storey\nthemeenergy_house_1\nthemeenergy_house_2\nthemeenergy_house_3\nthemeenergy_house_4\nthemeenergy_house_five_story\nthemeenergy_house_four_stories\nthemeenergy_house_three_story\nthemeenergy_house_two_storey_1\nthemeenergy_house_two_storey_2\nthemeenergy_house_with_garage_1\nthemeenergy_house_with_garage_2\nthemeenergy_hovel_1\nthemeenergy_hovel_2\nthemeenergy_library_1\nthemeenergy_library_2\nthemeenergy_log_house\nthemeenergy_mosque\nthemeenergy_obelisk\nthemeenergy_orthodox_church\nthemeenergy_palace_of_congresses\nthemeenergy_park_1\nthemeenergy_park_2\nthemeenergy_planetarium_observatory\nthemeenergy_plant\nthemeenergy_police\nthemeenergy_ranch\nthemeenergy_school_1\nthemeenergy_school_2\nthemeenergy_sheriff\nthemeenergy_shop_1\nthemeenergy_shop_2\nthemeenergy_shop_3\nthemeenergy_shop_4\nthemeenergy_shopping_center\nthemeenergy_skyscraper_1\nthemeenergy_skyscraper_2\nthemeenergy_skyscrapers_1\nthemeenergy_skyscrapers_2\nthemeenergy_skyscrapers_3\nthemeenergy_skyway\nthemeenergy_station_gas\nthemeenergy_station_petrol\nthemeenergy_tent_camp\nthemeenergy_terminal\nthemeenergy_theater_1\nthemeenergy_theater_2\nthemeenergy_train_station\nthemeenergy_TV_tower_repeater\nthemeenergy_university\nthemeenergy_warehouse\nthemeenergy_amphora\nthemeenergy_awards\nthemeenergy_books_1\nthemeenergy_books_2\nthemeenergy_crown_1\nthemeenergy_crown_2\nthemeenergy_death\nthemeenergy_eater\nthemeenergy_family\nthemeenergy_ghost\nthemeenergy_kill\nthemeenergy_leader\nthemeenergy_mask\nthemeenergy_matreshka_1\nthemeenergy_matreshka_2\nthemeenergy_nature\nthemeenergy_picture\nthemeenergy_pillar\nthemeenergy_smile_1\nthemeenergy_smile_2\nthemeenergy_smile_3\nthemeenergy_smile_4\nthemeenergy_smile_disappointment\nthemeenergy_smile_fright\nthemeenergy_smile_surprise\nthemeenergy_smile_wink\nthemeenergy_sun\nthemeenergy_wreath\nthemeenergy_yin_yang\nthemeenergy_calculator\nthemeenergy_camera_betacam\nthemeenergy_camera_video_1\nthemeenergy_camera_video_2\nthemeenergy_channel_close\nthemeenergy_channel_delete\nthemeenergy_channel_favorite\nthemeenergy_channel_new\nthemeenergy_channel_pause\nthemeenergy_channel_ready\nthemeenergy_channel_stop\nthemeenergy_channel_watch\nthemeenergy_computer\nthemeenergy_connection\nthemeenergy_connection_close\nthemeenergy_connection_delete\nthemeenergy_connection_favorite\nthemeenergy_connection_new\nthemeenergy_connection_ping\nthemeenergy_connection_ready\nthemeenergy_dec_phone\nthemeenergy_disc\nthemeenergy_external_drive\nthemeenergy_fax_1\nthemeenergy_fax_2\nthemeenergy_flashlight\nthemeenergy_hard_disk\nthemeenergy_hometheatre\nthemeenergy_iphone_horizontal_1\nthemeenergy_iphone_horizontal_2\nthemeenergy_iphone_vertical_1\nthemeenergy_iphone_vertical_2\nthemeenergy_joypad_games\nthemeenergy_keyboard\nthemeenergy_loudspeakers\nthemeenergy_mfu_xerox\nthemeenergy_microphone\nthemeenergy_mobile_phone\nthemeenergy_monitor\nthemeenergy_music_center\nthemeenergy_network\nthemeenergy_notebook_1\nthemeenergy_notebook_2\nthemeenergy_notebook_3\nthemeenergy_notebook_computer\nthemeenergy_photo_backside\nthemeenergy_photo_camera\nthemeenergy_photo_compact\nthemeenergy_photo_flash\nthemeenergy_piano_music\nthemeenergy_print\nthemeenergy_projector\nthemeenergy_psp\nthemeenergy_radio\nthemeenergy_recorder\nthemeenergy_remote_control_1\nthemeenergy_remote_control_2\nthemeenergy_save\nthemeenergy_save_as\nthemeenergy_scanner\nthemeenergy_sd_card_memory\nthemeenergy_server\nthemeenergy_sim_card\nthemeenergy_smartphone_1\nthemeenergy_smartphone_2\nthemeenergy_telephone_1\nthemeenergy_telephone_2\nthemeenergy_telephone_3\nthemeenergy_tv\nthemeenergy_tv_wide\nthemeenergy_usb_flash\nthemeenergy_videocamera_1\nthemeenergy_videocamera_2\nthemeenergy_videocamera_3\nthemeenergy_webcam\nthemeenergy_aac\nthemeenergy_ai\nthemeenergy_ape\nthemeenergy_asf\nthemeenergy_avi\nthemeenergy_bat\nthemeenergy_bmp\nthemeenergy_cdr\nthemeenergy_cfg\nthemeenergy_chm\nthemeenergy_com\nthemeenergy_css\nthemeenergy_csv\nthemeenergy_djv\nthemeenergy_dll\nthemeenergy_doc\nthemeenergy_document_audio\nthemeenergy_document_image\nthemeenergy_document_photo\nthemeenergy_document_system\nthemeenergy_document_text\nthemeenergy_document_unknown\nthemeenergy_document_video\nthemeenergy_document_voice\nthemeenergy_docx\nthemeenergy_eps\nthemeenergy_exe\nthemeenergy_file_audio\nthemeenergy_file_image\nthemeenergy_file_photo\nthemeenergy_file_video\nthemeenergy_file_voice\nthemeenergy_fla\nthemeenergy_flac\nthemeenergy_gif\nthemeenergy_html\nthemeenergy_icl\nthemeenergy_icns\nthemeenergy_ico\nthemeenergy_ini\nthemeenergy_iso\nthemeenergy_jpg\nthemeenergy_log\nthemeenergy_midi\nthemeenergy_mkv\nthemeenergy_mov\nthemeenergy_mp3\nthemeenergy_mp4\nthemeenergy_mpg\nthemeenergy_ogg\nthemeenergy_ogm\nthemeenergy_otf\nthemeenergy_pdf\nthemeenergy_png\nthemeenergy_ppt\nthemeenergy_pptx\nthemeenergy_psd\nthemeenergy_ra\nthemeenergy_rar\nthemeenergy_raw\nthemeenergy_rm\nthemeenergy_rtf\nthemeenergy_svg\nthemeenergy_svgz\nthemeenergy_swf\nthemeenergy_sys\nthemeenergy_tga\nthemeenergy_tif\nthemeenergy_ttf\nthemeenergy_txt\nthemeenergy_wav\nthemeenergy_wma\nthemeenergy_wmv\nthemeenergy_xls\nthemeenergy_xlsx\nthemeenergy_xml\nthemeenergy_zip\nthemeenergy_alarm_clock\nthemeenergy_alphabet\nthemeenergy_army_training\nthemeenergy_badge\nthemeenergy_book_1\nthemeenergy_book_2\nthemeenergy_book_3\nthemeenergy_books_12\nthemeenergy_books_22\nthemeenergy_brush\nthemeenergy_calculator2\nthemeenergy_calendar\nthemeenergy_certificate\nthemeenergy_chemistry_1\nthemeenergy_chemistry_2\nthemeenergy_classboard\nthemeenergy_copybook_1\nthemeenergy_copybook_2\nthemeenergy_copybook_3\nthemeenergy_copybook_4\nthemeenergy_desk\nthemeenergy_dna_1\nthemeenergy_dna_2\nthemeenergy_drawing\nthemeenergy_geography\nthemeenergy_geography_earth\nthemeenergy_geography_globe\nthemeenergy_geometry\nthemeenergy_holliday\nthemeenergy_home2\nthemeenergy_language\nthemeenergy_list_1\nthemeenergy_list_2\nthemeenergy_list_3\nthemeenergy_list_4\nthemeenergy_list_5\nthemeenergy_list_6\nthemeenergy_list_7\nthemeenergy_list_8\nthemeenergy_mark_1\nthemeenergy_mark_1-\nthemeenergy_mark_12\nthemeenergy_mark_2\nthemeenergy_mark_2-\nthemeenergy_mark_22\nthemeenergy_mark_3\nthemeenergy_mark_3-\nthemeenergy_mark_32\nthemeenergy_mark_4\nthemeenergy_mark_4-\nthemeenergy_mark_42\nthemeenergy_mark_5\nthemeenergy_mark_5-\nthemeenergy_mark_52\nthemeenergy_mark_a\nthemeenergy_mark_a-\nthemeenergy_mark_a2\nthemeenergy_mark_b\nthemeenergy_mark_b-\nthemeenergy_mark_b2\nthemeenergy_mark_c\nthemeenergy_mark_c-\nthemeenergy_mark_c2\nthemeenergy_mark_d\nthemeenergy_mark_d-\nthemeenergy_mark_d2\nthemeenergy_mark_e\nthemeenergy_mark_e-\nthemeenergy_mark_e2\nthemeenergy_mathematics\nthemeenergy_music\nthemeenergy_notebook\nthemeenergy_notepad\nthemeenergy_palette_1\nthemeenergy_palette_2\nthemeenergy_pen\nthemeenergy_pencil\nthemeenergy_pupil_boy\nthemeenergy_pupil_girl\nthemeenergy_pupils\nthemeenergy_rating\nthemeenergy_rating_high\nthemeenergy_rating_lowstar\nthemeenergy_ruler\nthemeenergy_school\nthemeenergy_school_bus\nthemeenergy_sport_1\nthemeenergy_sport_2\nthemeenergy_bank_1\nthemeenergy_bank_2\nthemeenergy_bed\nthemeenergy_career\nthemeenergy_chair\nthemeenergy_change\nthemeenergy_coin_amero\nthemeenergy_coin_dollar\nthemeenergy_coin_euro\nthemeenergy_coin_pound\nthemeenergy_coin_ruble\nthemeenergy_coin_yuan\nthemeenergy_coins\nthemeenergy_crisis\nthemeenergy_elevation_1\nthemeenergy_elevation_2\nthemeenergy_elevation_3\nthemeenergy_elevation_4\nthemeenergy_elevation_5\nthemeenergy_elevation_6\nthemeenergy_factory_12\nthemeenergy_factory_22\nthemeenergy_hierarchy_1\nthemeenergy_hierarchy_2\nthemeenergy_income_1\nthemeenergy_income_2\nthemeenergy_income_3\nthemeenergy_income_4\nthemeenergy_loan\nthemeenergy_market\nthemeenergy_money\nthemeenergy_moneys_1\nthemeenergy_moneys_2\nthemeenergy_moneys_3\nthemeenergy_object_child\nthemeenergy_object_root\nthemeenergy_payment_1\nthemeenergy_payment_2\nthemeenergy_payment_3\nthemeenergy_payment_4\nthemeenergy_piggy_bank\nthemeenergy_product_1\nthemeenergy_product_2\nthemeenergy_purse_1\nthemeenergy_purse_2\nthemeenergy_purse_3\nthemeenergy_purse_4\nthemeenergy_purse_5\nthemeenergy_purse_6\nthemeenergy_purse_7\nthemeenergy_purse_8\nthemeenergy_recession_1\nthemeenergy_recession_2\nthemeenergy_recession_3\nthemeenergy_recession_4\nthemeenergy_recession_5\nthemeenergy_rise_and_fall_1\nthemeenergy_rise_and_fall_2\nthemeenergy_room\nthemeenergy_safe\nthemeenergy_scheme\nthemeenergy_sign_amero\nthemeenergy_sign_dollar\nthemeenergy_sign_euro\nthemeenergy_sign_pound\nthemeenergy_sign_ruble\nthemeenergy_sign_yuan\nthemeenergy_skyscraper_12\nthemeenergy_skyscraper_22\nthemeenergy_skyscraper_3\nthemeenergy_stability_1\nthemeenergy_stability_2\nthemeenergy_strategy\nthemeenergy_turning_point_1\nthemeenergy_turning_point_2\nthemeenergy_workplace_1\nthemeenergy_workplace_2\nthemeenergy_apple_1\nthemeenergy_apple_2\nthemeenergy_asparagus\nthemeenergy_banana\nthemeenergy_beans\nthemeenergy_cabbage\nthemeenergy_carrot\nthemeenergy_cauliflower\nthemeenergy_cherry\nthemeenergy_corn\nthemeenergy_cucumber\nthemeenergy_eggplant\nthemeenergy_garlic\nthemeenergy_grapes\nthemeenergy_grass\nthemeenergy_leaves\nthemeenergy_lemon_1\nthemeenergy_lemon_2\nthemeenergy_lemon_slice_1\nthemeenergy_lemon_slice_2\nthemeenergy_mandarine\nthemeenergy_melon_1\nthemeenergy_melon_2\nthemeenergy_mushroom_1\nthemeenergy_mushroom_2\nthemeenergy_nut\nthemeenergy_olive_1\nthemeenergy_olive_2\nthemeenergy_onion\nthemeenergy_orange\nthemeenergy_pattinson\nthemeenergy_peach\nthemeenergy_pear\nthemeenergy_peas_1\nthemeenergy_peas_2\nthemeenergy_pepper\nthemeenergy_pepper_chili\nthemeenergy_persimmon\nthemeenergy_pineapple\nthemeenergy_plum\nthemeenergy_pomegranate_1\nthemeenergy_pomegranate_2\nthemeenergy_pomelo\nthemeenergy_potato\nthemeenergy_pumpkin_1\nthemeenergy_pumpkin_2\nthemeenergy_radish\nthemeenergy_raspberry\nthemeenergy_salad\nthemeenergy_strawberry\nthemeenergy_tomato_1\nthemeenergy_tomato_2\nthemeenergy_watermelon_1\nthemeenergy_watermelon_2\nthemeenergy_wheat\nthemeenergy_barbecue\nthemeenergy_bone\nthemeenergy_bread_1\nthemeenergy_bread_2\nthemeenergy_bread_baguette\nthemeenergy_burger_1\nthemeenergy_burger_2\nthemeenergy_cake_1\nthemeenergy_cake_2\nthemeenergy_cake_wedding\nthemeenergy_candy_1\nthemeenergy_candy_2\nthemeenergy_candy_3\nthemeenergy_candy_lolipop\nthemeenergy_cheese_1\nthemeenergy_cheese_2\nthemeenergy_chicken\nthemeenergy_chicken_leg\nthemeenergy_chips\nthemeenergy_chocolate_1\nthemeenergy_chocolate_2\nthemeenergy_cookies\nthemeenergy_croissant\nthemeenergy_donut_1\nthemeenergy_donut_2\nthemeenergy_egg\nthemeenergy_fish\nthemeenergy_fishbone\nthemeenergy_french_fries\nthemeenergy_grain\nthemeenergy_ham\nthemeenergy_honey\nthemeenergy_hot_dog\nthemeenergy_ice_cream_1\nthemeenergy_ice_cream_2\nthemeenergy_omelette\nthemeenergy_pasta_1\nthemeenergy_pasta_2\nthemeenergy_pasta_3\nthemeenergy_pizza\nthemeenergy_potato_slices\nthemeenergy_pretzel\nthemeenergy_rice\nthemeenergy_roll\nthemeenergy_rolls_1\nthemeenergy_rolls_2\nthemeenergy_rolls_3\nthemeenergy_sausage_1\nthemeenergy_sausage_2\nthemeenergy_sausage_3\nthemeenergy_sausage_4\nthemeenergy_sausage_5\nthemeenergy_steak\nthemeenergy_steak_t-bone\nthemeenergy_sushi_1\nthemeenergy_sushi_2\nthemeenergy_truffle\nthemeenergy_wafer_1\nthemeenergy_wafer_2\nthemeenergy_wafer_3\nthemeenergy_waffle_horn\nthemeenergy_beer\nthemeenergy_bottle_1\nthemeenergy_bottle_2\nthemeenergy_bottle_3\nthemeenergy_bottle_plastic\nthemeenergy_champagne\nthemeenergy_citrus\nthemeenergy_coffee_bean\nthemeenergy_cola_1\nthemeenergy_cola_2\nthemeenergy_condensed_milk\nthemeenergy_cucumbers\nthemeenergy_cup_1\nthemeenergy_cup_2\nthemeenergy_cup_3\nthemeenergy_cup_hot\nthemeenergy_fork_1\nthemeenergy_fork_2\nthemeenergy_glass2\nthemeenergy_glass_water\nthemeenergy_goblet_1\nthemeenergy_goblet_2\nthemeenergy_goblet_3\nthemeenergy_goblet_4\nthemeenergy_goblet_5\nthemeenergy_goblet_6\nthemeenergy_goblet_7\nthemeenergy_goblet_wineglass\nthemeenergy_jam_1\nthemeenergy_jam_2\nthemeenergy_milk\nthemeenergy_mushrooms\nthemeenergy_package\nthemeenergy_pan_1\nthemeenergy_pan_2\nthemeenergy_pan_3\nthemeenergy_pan_4\nthemeenergy_pepper2\nthemeenergy_plate_1\nthemeenergy_plate_2\nthemeenergy_preserves_1\nthemeenergy_preserves_2\nthemeenergy_preserves_3\nthemeenergy_salt\nthemeenergy_take-out_coffee\nthemeenergy_tea\nthemeenergy_tea_bag_1\nthemeenergy_tea_bag_2\nthemeenergy_teapot\nthemeenergy_wine_1\nthemeenergy_wine_2\nthemeenergy_armchair\nthemeenergy_baby_cot\nthemeenergy_bath\nthemeenergy_bed2\nthemeenergy_bench\nthemeenergy_bench_1\nthemeenergy_bidet\nthemeenergy_blanket\nthemeenergy_bookshelf\nthemeenergy_box_locker\nthemeenergy_box_open\nthemeenergy_cabinet\nthemeenergy_carpet\nthemeenergy_chair_1\nthemeenergy_chair_2\nthemeenergy_chair_3\nthemeenergy_chair_director_folding\nthemeenergy_chair_office\nthemeenergy_chair_rocking\nthemeenergy_chair_round\nthemeenergy_chandelier\nthemeenergy_changing_table\nthemeenergy_chest_of_drawers\nthemeenergy_chest_of_drawers_2\nthemeenergy_cot\nthemeenergy_curtains\nthemeenergy_cushion\nthemeenergy_door\nthemeenergy_doublebed\nthemeenergy_floor_lamp_1\nthemeenergy_floor_lamp_2\nthemeenergy_floor_lamp_3\nthemeenergy_hanger\nthemeenergy_hanger_1\nthemeenergy_heated_towel_rail\nthemeenergy_hook\nthemeenergy_hooks\nthemeenergy_linen\nthemeenergy_mirror\nthemeenergy_pan\nthemeenergy_picture2\nthemeenergy_pier-glass\nthemeenergy_rack\nthemeenergy_racks\nthemeenergy_radiator2\nthemeenergy_reading-lamp\nthemeenergy_shelf\nthemeenergy_shelf_2\nthemeenergy_shelfs\nthemeenergy_shelfs_2\nthemeenergy_singlebed\nthemeenergy_sink_bathroom\nthemeenergy_sink_kitchen\nthemeenergy_sofa\nthemeenergy_sofa_1\nthemeenergy_sofa_2\nthemeenergy_stand\nthemeenergy_stand_2\nthemeenergy_stand_3\nthemeenergy_stand_4\nthemeenergy_table_1\nthemeenergy_table_2\nthemeenergy_table_3\nthemeenergy_table_4\nthemeenergy_table_round\nthemeenergy_table-lamp\nthemeenergy_toilet_paper\nthemeenergy_towel\nthemeenergy_wardrobe\nthemeenergy_wardrobe_1\nthemeenergy_wardrobe_2\nthemeenergy_window\nthemeenergy_bag_bagful\nthemeenergy_balloon\nthemeenergy_balloons\nthemeenergy_bell\nthemeenergy_bouquet_flowers\nthemeenergy_bow_knot\nthemeenergy_bracelet\nthemeenergy_candle\nthemeenergy_christmas_newyear_tree\nthemeenergy_christmas_tree_decoration\nthemeenergy_christmas_tree_decoration_lashlight\nthemeenergy_cookie_man\nthemeenergy_crown_king_top\nthemeenergy_diamond\nthemeenergy_diamond_brilliant\nthemeenergy_easter_egg\nthemeenergy_fireworks\nthemeenergy_flag_victory\nthemeenergy_flag_victory_2\nthemeenergy_flower\nthemeenergy_flower_pot\nthemeenergy_flowers\nthemeenergy_four-leaved_shamrock_lucky\nthemeenergy_garland_lamp\nthemeenergy_grandfather_frost\nthemeenergy_happy_sunny\nthemeenergy_horseshoe\nthemeenergy_mitten\nthemeenergy_necklace\nthemeenergy_package2\nthemeenergy_pearl\nthemeenergy_petard\nthemeenergy_poinsettia_christmas_star\nthemeenergy_present\nthemeenergy_present_gift\nthemeenergy_ring\nthemeenergy_ring_finger\nthemeenergy_saint_patrick_hat\nthemeenergy_salute_fireworks\nthemeenergy_salute_fireworks_2\nthemeenergy_salute_fireworks_3\nthemeenergy_salute_fireworks_4\nthemeenergy_santa_claus\nthemeenergy_santa_claus_hat\nthemeenergy_santa_claus_head\nthemeenergy_shopping\nthemeenergy_sledge\nthemeenergy_snowman\nthemeenergy_sock_boots\nthemeenergy_teddy_bear\nthemeenergy_treasures_money_gold_boiler\nthemeenergy_turkey_chicken\nthemeenergy_wedding_jewel\nthemeenergy_add-on\nthemeenergy_advertise_1\nthemeenergy_advertise_2\nthemeenergy_archive\nthemeenergy_box\nthemeenergy_box_opened\nthemeenergy_browser\nthemeenergy_comment_12\nthemeenergy_comment_22\nthemeenergy_comments\nthemeenergy_compare_balance\nthemeenergy_compare_disbalance\nthemeenergy_download_1\nthemeenergy_download_2\nthemeenergy_download_3\nthemeenergy_download_4\nthemeenergy_download_5\nthemeenergy_firewall\nthemeenergy_grid_01\nthemeenergy_grid_02\nthemeenergy_grid_03\nthemeenergy_grid_04\nthemeenergy_grid_05\nthemeenergy_grid_06\nthemeenergy_grid_07\nthemeenergy_grid_08\nthemeenergy_grid_09\nthemeenergy_grid_10\nthemeenergy_grid_11\nthemeenergy_grid_12\nthemeenergy_grid_13\nthemeenergy_grid_14\nthemeenergy_grid_15\nthemeenergy_grid_16\nthemeenergy_grid_17\nthemeenergy_grid_18\nthemeenergy_grid_19\nthemeenergy_grid_20\nthemeenergy_grid_21\nthemeenergy_grid_22\nthemeenergy_grid_23\nthemeenergy_grid_24\nthemeenergy_grid_25\nthemeenergy_grid_26\nthemeenergy_grid_27\nthemeenergy_grid_28\nthemeenergy_grid_29\nthemeenergy_grid_30\nthemeenergy_grid_31\nthemeenergy_grid_32\nthemeenergy_grid_33\nthemeenergy_grid_34\nthemeenergy_grid_35\nthemeenergy_grid_36\nthemeenergy_grid_37\nthemeenergy_grid_38\nthemeenergy_grid_columns\nthemeenergy_grid_layout\nthemeenergy_grid_rows\nthemeenergy_grid_thumbnails\nthemeenergy_headphones\nthemeenergy_html_code\nthemeenergy_index_1\nthemeenergy_index_2\nthemeenergy_knob\nthemeenergy_play\nthemeenergy_player_1\nthemeenergy_player_2\nthemeenergy_presentation\nthemeenergy_preview_cover_flow\nthemeenergy_preview_fullscreen\nthemeenergy_preview_list\nthemeenergy_preview_matrix\nthemeenergy_preview_presentation\nthemeenergy_preview_table\nthemeenergy_preview_thumbnails\nthemeenergy_quotes_1\nthemeenergy_quotes_2\nthemeenergy_send\nthemeenergy_share\nthemeenergy_site_alert\nthemeenergy_site_attention\nthemeenergy_site_back\nthemeenergy_site_close\nthemeenergy_site_close_tab\nthemeenergy_site_favorite\nthemeenergy_site_foward\nthemeenergy_site_new\nthemeenergy_site_options\nthemeenergy_site_ping\nthemeenergy_site_refresh\nthemeenergy_site_search\nthemeenergy_tablet\nthemeenergy_text_center\nthemeenergy_text_justify_all\nthemeenergy_text_justify_centered\nthemeenergy_text_justify_left\nthemeenergy_text_justify_right\nthemeenergy_text_left_align\nthemeenergy_text_right_align\nthemeenergy_top_1\nthemeenergy_top_2\nthemeenergy_view_expand_1\nthemeenergy_view_expand_2\nthemeenergy_view_full_screen\nthemeenergy_view_maximize\nthemeenergy_view_minimize\nthemeenergy_view_roll-up_1\nthemeenergy_view_roll-up_2\nthemeenergy_view_scale_1\nthemeenergy_view_scale_2\nthemeenergy_window2\nthemeenergy_window_attention\nthemeenergy_window_close_1\nthemeenergy_window_close_2\nthemeenergy_window_favorite\nthemeenergy_window_new\nthemeenergy_window_next\nthemeenergy_window_open\nthemeenergy_window_options\nthemeenergy_window_previous\nthemeenergy_window_refresh\nthemeenergy_window_search\nthemeenergy_window_stop\nthemeenergy_bird_1\nthemeenergy_bird_2\nthemeenergy_blink\nthemeenergy_blood\nthemeenergy_brain\nthemeenergy_cardiogram_1\nthemeenergy_cardiogram_2\nthemeenergy_chicken2\nthemeenergy_DNA_1\nthemeenergy_DNA_2\nthemeenergy_DNA_3\nthemeenergy_doctor\nthemeenergy_dog\nthemeenergy_drugs\nthemeenergy_ear\nthemeenergy_eye_1\nthemeenergy_eye_2\nthemeenergy_eye_3\nthemeenergy_eye_4\nthemeenergy_eye_5\nthemeenergy_eye_6\nthemeenergy_eye_7\nthemeenergy_eyelash\nthemeenergy_fish2\nthemeenergy_fist\nthemeenergy_heart\nthemeenergy_hostpital\nthemeenergy_injection\nthemeenergy_kidney\nthemeenergy_lips\nthemeenergy_liver\nthemeenergy_LSD\nthemeenergy_lungs\nthemeenergy_medicine_chest\nthemeenergy_microscope_1\nthemeenergy_microscope_2\nthemeenergy_monkey\nthemeenergy_nose_1\nthemeenergy_nose_2\nthemeenergy_nurse\nthemeenergy_patch_1\nthemeenergy_patch_2\nthemeenergy_pill_1\nthemeenergy_pill_2\nthemeenergy_pill_3\nthemeenergy_pill_4\nthemeenergy_pill_5\nthemeenergy_pill_6\nthemeenergy_pill_7\nthemeenergy_pill_8\nthemeenergy_pill_drugs\nthemeenergy_red_cross\nthemeenergy_skull\nthemeenergy_skull_and_bones\nthemeenergy_sleep_1\nthemeenergy_sleep_2\nthemeenergy_sleep_3\nthemeenergy_smoke\nthemeenergy_snake\nthemeenergy_stomach\nthemeenergy_syringe\nthemeenergy_test-tube_1\nthemeenergy_test-tube_2\nthemeenergy_test-tube_3\nthemeenergy_test-tube_4\nthemeenergy_test-tube_5\nthemeenergy_thermometer\nthemeenergy_tooth\nthemeenergy_anchor\nthemeenergy_axis_3d\nthemeenergy_book\nthemeenergy_categories\nthemeenergy_cloud\nthemeenergy_cloud_connect\nthemeenergy_cloud_delete\nthemeenergy_cloud_download\nthemeenergy_cloud_new\nthemeenergy_cloud_ok\nthemeenergy_cloud_upload\nthemeenergy_compas_1\nthemeenergy_compas_2\nthemeenergy_cup_12\nthemeenergy_cup_22\nthemeenergy_document\nthemeenergy_eye_12\nthemeenergy_eye_22\nthemeenergy_feather\nthemeenergy_flash\nthemeenergy_food_1\nthemeenergy_food_2\nthemeenergy_food_3\nthemeenergy_geo\nthemeenergy_goal\nthemeenergy_goal_1\nthemeenergy_inbox_1\nthemeenergy_inbox_mail\nthemeenergy_inbox_receive\nthemeenergy_inbox_send\nthemeenergy_inbox_sent\nthemeenergy_info_1\nthemeenergy_info_2\nthemeenergy_info_3\nthemeenergy_languages\nthemeenergy_link_1\nthemeenergy_link_2\nthemeenergy_link_3\nthemeenergy_link_close_1\nthemeenergy_link_close_2\nthemeenergy_link_delete_1\nthemeenergy_link_delete_2\nthemeenergy_link_new_1\nthemeenergy_link_new_2\nthemeenergy_location_1\nthemeenergy_location_2\nthemeenergy_location_3\nthemeenergy_location_4\nthemeenergy_location_5\nthemeenergy_location_current\nthemeenergy_location_delete\nthemeenergy_location_favorite\nthemeenergy_location_new\nthemeenergy_location_remove\nthemeenergy_map_1\nthemeenergy_map_2\nthemeenergy_map_3\nthemeenergy_map_4\nthemeenergy_map_location\nthemeenergy_options\nthemeenergy_patch\nthemeenergy_plane\nthemeenergy_present2\nthemeenergy_radio_1\nthemeenergy_radio_2\nthemeenergy_script\nthemeenergy_speed_1\nthemeenergy_speed_2\nthemeenergy_switch_off\nthemeenergy_switch_off_1\nthemeenergy_switch_on\nthemeenergy_switch_on_1\nthemeenergy_tag_1\nthemeenergy_tag_2\nthemeenergy_tag_delete\nthemeenergy_tag_favorite\nthemeenergy_tag_new\nthemeenergy_tag_ready\nthemeenergy_tag_remove\nthemeenergy_tags_1\nthemeenergy_tags_2\nthemeenergy_target\nthemeenergy_target_1\nthemeenergy_toggle_down_slide\nthemeenergy_toggle_left_slide\nthemeenergy_toggle_right_slide\nthemeenergy_toggle_up_slide\nthemeenergy_water\nthemeenergy_airdrop\nthemeenergy_application_delete\nthemeenergy_application_favorite\nthemeenergy_application_new\nthemeenergy_application_ready\nthemeenergy_application_remove\nthemeenergy_battery_1\nthemeenergy_battery_2\nthemeenergy_battery_3\nthemeenergy_battery_empty\nthemeenergy_battery_full\nthemeenergy_bell2\nthemeenergy_bucket_tool\nthemeenergy_bug\nthemeenergy_cards\nthemeenergy_chart_1\nthemeenergy_chart_2\nthemeenergy_chart_3\nthemeenergy_chart_4\nthemeenergy_chess\nthemeenergy_connect_1\nthemeenergy_connect_2\nthemeenergy_contrast\nthemeenergy_crop\nthemeenergy_crop_2\nthemeenergy_dropper_pipette\nthemeenergy_filter\nthemeenergy_folder_close\nthemeenergy_folder_open\nthemeenergy_font\nthemeenergy_glasses\nthemeenergy_graph_tools\nthemeenergy_grid\nthemeenergy_hang\nthemeenergy_layer_order_1\nthemeenergy_layer_order_2\nthemeenergy_megaphone\nthemeenergy_mirror_horizontal\nthemeenergy_mirror_vertical\nthemeenergy_mixer2\nthemeenergy_movie_1\nthemeenergy_movie_2\nthemeenergy_movie_3\nthemeenergy_movie_4\nthemeenergy_music_1\nthemeenergy_music_2\nthemeenergy_news_1\nthemeenergy_news_2\nthemeenergy_news_3\nthemeenergy_news_newspaper_1\nthemeenergy_news_newspaper_2\nthemeenergy_options2\nthemeenergy_palette\nthemeenergy_pattern_tool\nthemeenergy_pen2\nthemeenergy_pen_felt\nthemeenergy_pencil2\nthemeenergy_phone_1\nthemeenergy_phone_2\nthemeenergy_photo_reel\nthemeenergy_picture_portrait\nthemeenergy_pitchfork\nthemeenergy_radar_satellite_antenna\nthemeenergy_rocket\nthemeenergy_saturn\nthemeenergy_signal\nthemeenergy_signal_3\nthemeenergy_speaker_1\nthemeenergy_speaker_2\nthemeenergy_speaker_3\nthemeenergy_speaker_mute\nthemeenergy_sputnik\nthemeenergy_stamp\nthemeenergy_terminal2\nthemeenergy_terminal_application\nthemeenergy_text_align_center\nthemeenergy_text_align_left\nthemeenergy_text_align_right\nthemeenergy_video_add\nthemeenergy_video_delete\nthemeenergy_video_favorite\nthemeenergy_video_pause\nthemeenergy_video_play\nthemeenergy_video_record\nthemeenergy_video_remove\nthemeenergy_video_stop\nthemeenergy_video_uploaded\nthemeenergy_wi-fi_2\nthemeenergy_wi-fi_3\nthemeenergy_wi-fi_4\nthemeenergy_wi-fi_5\nthemeenergy_zoom_in\nthemeenergy_zoom_out\nthemeenergy_alert\nthemeenergy_baby_boy\nthemeenergy_baby_child\nthemeenergy_baby_girl\nthemeenergy_children\nthemeenergy_couple_1\nthemeenergy_couple_2\nthemeenergy_disabled\nthemeenergy_escalator\nthemeenergy_exit_1\nthemeenergy_exit_2\nthemeenergy_father_daughter_1\nthemeenergy_father_daughter_2\nthemeenergy_father_son_1\nthemeenergy_father_son_2\nthemeenergy_female\nthemeenergy_fire_extinguisher\nthemeenergy_firehose\nthemeenergy_fountain_1\nthemeenergy_human\nthemeenergy_lift_1\nthemeenergy_lift_2\nthemeenergy_lift_service\nthemeenergy_male\nthemeenergy_mens\nthemeenergy_mother_child\nthemeenergy_mother_daughter_1\nthemeenergy_mother_daughter_2\nthemeenergy_mother_son_1\nthemeenergy_mother_son_2\nthemeenergy_parking_1\nthemeenergy_parking_2\nthemeenergy_people_crowd\nthemeenergy_pharmacy\nthemeenergy_pram\nthemeenergy_pregnant\nthemeenergy_pump_1\nthemeenergy_pump_2\nthemeenergy_registration\nthemeenergy_run_evacuation\nthemeenergy_run_exit_1\nthemeenergy_run_exit_2\nthemeenergy_sos\nthemeenergy_trash_1\nthemeenergy_trash_2\nthemeenergy_wc_1\nthemeenergy_wc_2\nthemeenergy_wet_floor\nthemeenergy_woman\nthemeenergy_womans\nthemeenergy_action_redo_1\nthemeenergy_action_redo_2\nthemeenergy_action_undo_1\nthemeenergy_action_undo_2\nthemeenergy_arrow_down\nthemeenergy_arrow_down-left\nthemeenergy_arrow_down-right\nthemeenergy_arrow_left\nthemeenergy_arrow_right\nthemeenergy_arrow_up\nthemeenergy_arrow_up-left\nthemeenergy_arrow_up-right\nthemeenergy_button_backward_1\nthemeenergy_button_backward_2\nthemeenergy_button_check_1\nthemeenergy_button_check_2\nthemeenergy_button_delete_1\nthemeenergy_button_delete_2\nthemeenergy_button_foward_1\nthemeenergy_button_foward_2\nthemeenergy_button_minus_1\nthemeenergy_button_minus_2\nthemeenergy_button_new_1\nthemeenergy_button_new_2\nthemeenergy_button_pause_1\nthemeenergy_button_pause_2\nthemeenergy_button_play_1\nthemeenergy_button_play_2\nthemeenergy_button_record_1\nthemeenergy_button_record_2\nthemeenergy_button_stop_1\nthemeenergy_button_stop_2\nthemeenergy_move_1\nthemeenergy_move_2\nthemeenergy_play_consistently\nthemeenergy_play_ping_pong\nthemeenergy_play_repeat\nthemeenergy_play_repeat_all\nthemeenergy_play_shuffle\nthemeenergy_refresh_1\nthemeenergy_refresh_2\nthemeenergy_rotateccw_1\nthemeenergy_rotate_1\nthemeenergy_rotate_2\nthemeenergy_rotate_3\nthemeenergy_rotate_4\nthemeenergy_rotate_ccw_2\nthemeenergy_rotate_cw_1\nthemeenergy_rotate_cw_2\nthemeenergy_swap_horizontal_1\nthemeenergy_swap_vertical_1\nthemeenergy_symbol_backward\nthemeenergy_symbol_foward\nthemeenergy_symbol_pause_1\nthemeenergy_symbol_play_1\nthemeenergy_symbol_record_1\nthemeenergy_symbol_stop_1\nthemeenergy_window_close\nthemeenergy_window_fullscreen_1\nthemeenergy_window_fullscreen_2\nthemeenergy_attention_1\nthemeenergy_attention_2\nthemeenergy_attention_3\nthemeenergy_bomb\nthemeenergy_bonus\nthemeenergy_cancel_1\nthemeenergy_cancel_2\nthemeenergy_cord_1\nthemeenergy_cord_2\nthemeenergy_flash2\nthemeenergy_flower_1\nthemeenergy_flower_2\nthemeenergy_flower_3\nthemeenergy_help_1\nthemeenergy_help_2\nthemeenergy_hierarchy_12\nthemeenergy_hierarchy_22\nthemeenergy_magnet\nthemeenergy_moon\nthemeenergy_peace\nthemeenergy_pie_chart\nthemeenergy_radiation\nthemeenergy_shape_bonus\nthemeenergy_shape_circle\nthemeenergy_shape_ellipse\nthemeenergy_shape_heptagon\nthemeenergy_shape_hexagon\nthemeenergy_shape_hexagonal_rounded\nthemeenergy_shape_hexagonal_star\nthemeenergy_shape_octagon\nthemeenergy_shape_octagonal_rounded\nthemeenergy_shape_octagonal_star\nthemeenergy_shape_pentagon\nthemeenergy_shape_rectangle\nthemeenergy_shape_rounded\nthemeenergy_shape_seven_rounded\nthemeenergy_shape_seven_star\nthemeenergy_shape_square\nthemeenergy_shape_star\nthemeenergy_shape_triangle\nthemeenergy_stop_1\nthemeenergy_stop_2\nthemeenergy_sun2\nthemeenergy_switcher_1\nthemeenergy_switcher_2\nthemeenergy_air_baloon_1\nthemeenergy_air_baloon_2\nthemeenergy_air_baloon_3\nthemeenergy_airliner\nthemeenergy_ambulance\nthemeenergy_bicycle\nthemeenergy_boat\nthemeenergy_bus_1\nthemeenergy_bus_2\nthemeenergy_bus_london\nthemeenergy_cruise_ship\nthemeenergy_fighter\nthemeenergy_gas_tanker\nthemeenergy_helicopter\nthemeenergy_motocycle\nthemeenergy_plane2\nthemeenergy_plane_landing\nthemeenergy_plane_takeoff\nthemeenergy_police_1\nthemeenergy_police_2\nthemeenergy_railroad\nthemeenergy_rocket_1\nthemeenergy_rocket_2\nthemeenergy_ship_1\nthemeenergy_ship_2\nthemeenergy_ship_3\nthemeenergy_shuttle\nthemeenergy_sign_bus\nthemeenergy_sign_car\nthemeenergy_sign_train_1\nthemeenergy_sign_train_2\nthemeenergy_sign_train_3\nthemeenergy_sign_tramway\nthemeenergy_sign_trolley_bus\nthemeenergy_sign_water_transport\nthemeenergy_tank_1\nthemeenergy_tank_2\nthemeenergy_tanker\nthemeenergy_taxi\nthemeenergy_trailer\nthemeenergy_tramway\nthemeenergy_transport\nthemeenergy_trolley_bus\nthemeenergy_truck_1\nthemeenergy_truck_2\nthemeenergy_truck_3\nthemeenergy_ufo\nthemeenergy_add_friend\nthemeenergy_best_friends\nthemeenergy_couple\nthemeenergy_delete_profile\nthemeenergy_forefinger_down\nthemeenergy_forefinger_left\nthemeenergy_forefinger_right\nthemeenergy_forefinger_up\nthemeenergy_friends\nthemeenergy_group\nthemeenergy_hand_stop_1\nthemeenergy_hand_stop_2\nthemeenergy_man\nthemeenergy_registered_user\nthemeenergy_remove_friend\nthemeenergy_user\nthemeenergy_vote_no\nthemeenergy_vote_yes\nthemeenergy_woman2\nthemeenergy_cloud2\nthemeenergy_cold\nthemeenergy_day_cloudy\nthemeenergy_day_lot_clouds\nthemeenergy_day_partly_cloudy\nthemeenergy_day_rain\nthemeenergy_day_sunny\nthemeenergy_flood\nthemeenergy_fog\nthemeenergy_hail\nthemeenergy_hail_heavy\nthemeenergy_hail_light\nthemeenergy_lightning\nthemeenergy_night_cloudy\nthemeenergy_night_lot_clouds\nthemeenergy_night_moon\nthemeenergy_night_partly_cloudy\nthemeenergy_night_rain\nthemeenergy_rain\nthemeenergy_rain_heavy\nthemeenergy_rain_light\nthemeenergy_rain_lightning\nthemeenergy_snow\nthemeenergy_snow_heavy\nthemeenergy_snow_light\nthemeenergy_snow_rain\nthemeenergy_thermometer2\nthemeenergy_thunder\nthemeenergy_tornado\nthemeenergy_umbrella2\nthemeenergy_water2\nthemeenergy_wet\nthemeenergy_attach_1\nthemeenergy_attach_2\nthemeenergy_bag_1\nthemeenergy_bag_2\nthemeenergy_bag_3\nthemeenergy_bag_4\nthemeenergy_bag_5\nthemeenergy_calendar_1\nthemeenergy_calendar_2\nthemeenergy_calendar_3\nthemeenergy_calendar_4\nthemeenergy_calendar_5\nthemeenergy_calendar_6\nthemeenergy_clipboard_1\nthemeenergy_clipboard_2\nthemeenergy_clipboard_3\nthemeenergy_copy\nthemeenergy_count_delete\nthemeenergy_count_finish\nthemeenergy_count_new\nthemeenergy_count_pause\nthemeenergy_count_play\nthemeenergy_count_remove\nthemeenergy_count_stop\nthemeenergy_cursor\nthemeenergy_cut\nthemeenergy_document_1\nthemeenergy_document_2\nthemeenergy_document_check\nthemeenergy_document_delete\nthemeenergy_document_favorite\nthemeenergy_document_new\nthemeenergy_document_remove\nthemeenergy_document_search\nthemeenergy_document_text2\nthemeenergy_enter\nthemeenergy_erase\nthemeenergy_eraser\nthemeenergy_exit\nthemeenergy_factory\nthemeenergy_folder\nthemeenergy_folder_1\nthemeenergy_folder_1_open\nthemeenergy_folder_2\nthemeenergy_folder_2_open\nthemeenergy_folder_check\nthemeenergy_folder_delete\nthemeenergy_folder_favorite\nthemeenergy_folder_new\nthemeenergy_folder_remove\nthemeenergy_folder_search\nthemeenergy_join_1\nthemeenergy_join_2\nthemeenergy_mail\nthemeenergy_paste\nthemeenergy_pen3\nthemeenergy_pencil3\nthemeenergy_pencil_write\nthemeenergy_portfolio\nthemeenergy_profile_1\nthemeenergy_profile_2\nthemeenergy_time\nthemeenergy_time_favorite";
		$options[] = array(
			'name' => esc_html__('Iconic Features Widget classes', 'transfers'),
			'desc' => esc_html__('The css classes used for features icons in Iconic Features Widget on home page and in other sidebars', 'transfers'),
			'id' => 'iconic_features_widget_classes',
			'std' => $default_classes,
			'class' => '', //mini, tiny, small
			'type' => 'textarea');
	
		$options[] = array(
			'name' => esc_html__('Faqs', 'transfers'),
			'type' => 'heading');
			
		$options[] = array(
			'name' => esc_html__("Enable 'Faqs'", 'transfers'),
			'desc' => esc_html__("Enable the 'Faqs' data type", 'transfers'),
			'id' => 'enable_faqs',
			'std' => '1',
			'type' => 'checkbox');
			
		$options[] = array(
			'name' => esc_html__('Destinations', 'transfers'),
			'type' => 'heading');
			
		$options[] = array(
			'name' => esc_html__("Enable 'Destinations'", 'transfers'),
			'desc' => esc_html__("Enable the 'Destinations' data type", 'transfers'),
			'id' => 'enable_destinations',
			'std' => '1',
			'type' => 'checkbox');
			
		$options[] = array(
			'name' => esc_html__('Single destination permalink slug', 'transfers'),
			'desc' => esc_html__('The permalink slug used for creating a single destination (by default it is set to "destinations". <br /><strong>Note:</strong> Please make sure you flush your rewrite rules after changing this setting. You can do so by navigating to <a href="/wp-admin/options-permalink.php">Settings->Permalinks</a> and clicking "Save Changes".', 'transfers'),
			'id' => 'destinations_permalink_slug',
			'std' => 'destination',
			'type' => 'text');
			
		$options[] = array(
			'name' => esc_html__('Destinations archive posts per page', 'transfers'),
			'desc' => esc_html__('Number of destinations to display on destinations archive page', 'transfers'),
			'id' => 'destinations_archive_posts_per_page',
			'std' => '12',
			'type' => 'text');
			
		$options[] = array(
			'name' => esc_html__('Extra fields displayed on single destination page.', 'transfers'),
			'desc' => esc_html__('Extra fields displayed on single destination page if set.', 'transfers'),
			'id' => 'destination_extra_fields',
			'std' => 'Default field label',
			'type' => 'repeat_extra_field');
			
		$options[] = array(
			'name' => esc_html__('Transport Types', 'transfers'),
			'type' => 'heading');
			
		$options[] = array(
			'name' => esc_html__("Enable 'Transport types'", 'transfers'),
			'desc' => esc_html__("Enable the 'Transport types' data type", 'transfers'),
			'id' => 'enable_transport_types',
			'std' => '1',
			'type' => 'checkbox');
			
		$options[] = array(
			'name' => esc_html__("Enable 'Extra items'", 'transfers'),
			'desc' => esc_html__("Enable the 'Extra items' data type", 'transfers'),
			'id' => 'enable_extra_items',
			'std' => '1',
			'type' => 'checkbox');		

		if (transfers_is_woocommerce_active()) {		
			$options[] = array(
				'name' => esc_html__('WooCommerce Settings', 'transfers'),
				'type' => 'heading');		
			
			$options[] = array(
				'name' => esc_html__('Use WooCommerce for checkout', 'transfers'),
				'desc' => esc_html__('Use WooCommerce to enable payment after booking', 'transfers'),
				'id' => 'use_woocommerce_for_checkout',
				'std' => '0',
				'type' => 'checkbox');
				
			$options[] = array(
				'name' => esc_html__('Product placeholder image', 'transfers'),
				'desc' => esc_html__('Upload a custom product placeholder image to go in place of default product image used in WooCommerce cart.', 'transfers'),
				'id' => 'woocommerce_product_placeholder_image',
				'type' => 'upload');
				
			$status_array = array (
				'pending' => esc_html__('Pending', 'transfers'),
				'on-hold' => esc_html__('On hold', 'transfers'),
				'completed' => esc_html__('Completed', 'transfers'),
				'processing' => esc_html__('Processing', 'transfers'),
				'cancelled' => esc_html__('Cancelled', 'transfers')
			);
			
			$options[] = array(
				'name' => esc_html__('Completed order WooCommerce statuses', 'transfers'),
				'desc' => esc_html__('Which WooCommerce statuses do you want to consider as booked so that transfer is no longer seen as available?', 'transfers'),
				'id' => 'completed_order_woocommerce_statuses',
				'options' => $status_array,
				'std' => 'completed',
				'class' => '', //mini, tiny, small
				'type' => 'multicheck');
		
			$options[] = array(
				'name' => esc_html__('WooCommerce pages sidebar position', 'transfers'),
				'desc' => esc_html__('Select the position (if any) of sidebars to appear on all WooCommerce-specific pages of your website.', 'transfers'),
				'id' => 'woocommerce_pages_sidebar_position',
				'std' => 'three',
				'type' => 'select',
				'class' => 'mini', //mini, tiny, small
				'options' => $page_sidebars);
		}
		
		if (function_exists('transfers_extra_tables_exist')) {
		
			if (!transfers_extra_tables_exist()) {

				$options[] = array(
					'name' => esc_html__('Database', 'transfers'),
					'type' => 'heading');

				$options[] = array(
					'text' => __('Create tables', 'transfers'),
					'name' => __('The Transfers Theme database tables don\'t exist!', 'transfers'),
					'desc' => __('The Transfers Theme database tables need creation. Click the button above to create them.', 'transfers'),
					'id' => 'create_transfers_tables',
					'std' => 'Default',
					'type' => 'link_button_field');
			}
		}
	}

	return $options;
}