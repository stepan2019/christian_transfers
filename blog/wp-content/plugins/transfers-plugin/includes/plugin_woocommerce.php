<?php
if(file_exists(WP_PLUGIN_DIR .'/woocommerce/includes/api/interface-wc-api-handler.php')) {
	require_once(WP_PLUGIN_DIR .'/woocommerce/includes/api/interface-wc-api-handler.php');

	require_once(WP_PLUGIN_DIR .'/woocommerce/includes/api/class-wc-api-server.php');
	require_once(WP_PLUGIN_DIR .'/woocommerce/includes/api/class-wc-api-json-handler.php');
	require_once(WP_PLUGIN_DIR .'/woocommerce/includes/api/interface-wc-api-handler.php');
	require_once(WP_PLUGIN_DIR .'/woocommerce/includes/api/class-wc-api-resource.php');
	require_once(WP_PLUGIN_DIR .'/woocommerce/includes/api/class-wc-api-orders.php');
}
	
if ( ! defined( 'TRANSFERS_WOOCOMMERCE_BOOKING_ID' ) )
    define( 'TRANSFERS_WOOCOMMERCE_BOOKING_ID', 'transfers_pa_booking_id' );

if ( ! defined( 'TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY' ) )
    define( 'TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY', 'transfers_transfers_booking_session_key' );
	
if ( ! defined( 'TRANSFERS_WOOCOMMERCE_DESTINATION_FROM_ATT' ) )
    define( 'TRANSFERS_WOOCOMMERCE_DESTINATION_FROM_ATT', 'transfers_pa_destination_from' );

if ( ! defined( 'TRANSFERS_WOOCOMMERCE_DESTINATION_TO_ATT' ) )
    define( 'TRANSFERS_WOOCOMMERCE_DESTINATION_TO_ATT', 'transfers_pa_destination_to' );

if ( ! defined( 'TRANSFERS_WOOCOMMERCE_TRANSPORT_TYPE_ATT' ) )
    define( 'TRANSFERS_WOOCOMMERCE_TRANSPORT_TYPE_ATT', 'transfers_pa_transport_type' );
	
class Transfers_Plugin_WooCommerce extends Transfers_BaseSingleton {

	private $product_slug = 'tf-transfer-product';

	protected function __construct() {
	
        // our parent class might contain shared code in its constructor
        parent::__construct();		
    }
	
	public function build_product_slug($destination_from, $destination_to, $transport_type) {
		return $this->product_slug . "-variation-$destination_from-$destination_to-$transport_type";
	}

    public function init() {
		
		add_filter('woocommerce_cart_item_name', array( $this, 'woocommerce_cart_item_name'), 20, 3);
		add_filter('woocommerce_order_item_name', array( $this, 'woocommerce_order_item_name'), 20, 3);
		add_filter('woocommerce_cart_item_thumbnail', array($this, 'woocommerce_cart_item_thumbnail'), 20, 3);
		add_filter('woocommerce_variation_is_purchasable', array($this, 'woocommerce_variation_is_purchasable'), 20, 2);		   
		
		add_action('woocommerce_before_calculate_totals', array( $this, 'add_custom_total_price'), 20, 1);
		add_action('transfers_initialize_post_types', array($this, 'setup'));
		add_action('woocommerce_before_order_itemmeta', array($this, 'woocommerce_before_order_itemmeta'), 20, 3);
		add_action('wp_ajax_booking_add_to_cart_request', array( $this, 'booking_add_to_cart_request'));
		add_action('wp_ajax_nopriv_booking_add_to_cart_request', array( $this, 'booking_add_to_cart_request'));
		add_action('woocommerce_add_order_item_meta', array( $this, 'add_order_item_meta'), 10, 3);
		add_action('woocommerce_checkout_order_processed', array( $this, 'woocommerce_checkout_order_processed'), 10, 2);
		add_action('woocommerce_order_status_changed', array( $this, 'woocommerce_order_status_changed'), 10, 3 );
		add_action('woocommerce_delete_order_items', array( $this, 'woocommerce_delete_order_items'), 10, 1);
		add_action('woocommerce_cart_updated', array( $this, 'woocommerce_cart_updated') );

		add_action('transfers_before_delete_booking', array( $this, 'transfers_before_delete_booking'));
		add_filter('template_include', array($this, 'woocommerce_template_include' ));
	}
	
	function transfers_before_delete_booking($booking_id) {
	
		if(file_exists(WP_PLUGIN_DIR .'/woocommerce/includes/api/interface-wc-api-handler.php')) {
		
			global $transfers_plugin_post_types;
			$booking_entry = $transfers_plugin_post_types->get_booking_entry($booking_id);
			
			if ($booking_entry != null) {	
				$woo_order_id = $booking_entry->woo_order_id;
				
				if ($woo_order_id > 0) {
				
					$wc_api_server = new WC_API_Server('/');
					$wc_orders = new WC_API_Orders($wc_api_server);
					
					$wc_orders->delete_order( $woo_order_id, true );	
				}
			}
		}
	}

	function woocommerce_cart_updated() {
		
		global $transfers_plugin_post_types, $woocommerce ;
		
		if ( isset( $_GET[ 'remove_item' ] ) ){
			
			$cart_item_key = $_GET[ 'remove_item' ];
			
			$cart_item_meta = $woocommerce->session->get(TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY . $cart_item_key);
		
			if ($cart_item_meta != null) {
			
				$booking_id = $cart_item_meta[TRANSFERS_WOOCOMMERCE_BOOKING_ID];
				
				if ($booking_id > 0) {
				
					$transfers_plugin_post_types->delete_booking_entry($booking_id);
				}
			}
		} 
	}
	
	function woocommerce_delete_order_items( $order_id ) {

		global $transfers_plugin_post_types;
		
		$order = new WC_Order( $order_id );
		
		if ($order != null) {

			$items = $order->get_items();
			
			foreach ($items as $item_id => $item) {

				$booking_id = wc_get_order_item_meta($item_id, TRANSFERS_WOOCOMMERCE_BOOKING_ID, true);
				
				if ($booking_id > 0) {
				
					$transfers_plugin_post_types->delete_booking_entry($booking_id);
				}
			}
		}
	}
	
	function woocommerce_order_status_changed( $order_id, $old_status, $new_status ) {

		global $transfers_plugin_post_types;
		
		$order = new WC_Order( $order_id );

		$items = $order->get_items();
		
		if ($items != null) {
		
			foreach ($items as $item_id => $item) {

				$booking_id = wc_get_order_item_meta($item_id, TRANSFERS_WOOCOMMERCE_BOOKING_ID, true);
				
				if ($booking_id > 0) {
				
					$transfers_plugin_post_types->update_booking_entry_woocommerce_info($booking_id, null, null, $new_status);
				}	
			}
		}
	}
	
	function woocommerce_checkout_order_processed( $order_id, $posted ) {
		
		global $transfers_plugin_post_types, $woocommerce;
		
		$order = new WC_Order( $order_id );
		
		if ($order != null) {
		
			$status = $order->get_status();

			if ($woocommerce->cart != null) {
			
				foreach ( $woocommerce->cart->cart_contents as $key => $value ) {
				
					$cart_item_meta = $woocommerce->session->get(TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY . $key);
					
					if ($cart_item_meta != null && isset($cart_item_meta[TRANSFERS_WOOCOMMERCE_BOOKING_ID])) {

						$booking_id = $cart_item_meta[TRANSFERS_WOOCOMMERCE_BOOKING_ID];
						
						if ($booking_id > 0) {

							$transfers_plugin_post_types->update_booking_entry_woocommerce_info($booking_id, $key, $order_id, $status);
							
							$booking_args = array(			
								'first_name' => (isset($posted['billing_first_name']) ? $posted['billing_first_name'] : ''),
								'last_name' => (isset($posted['billing_last_name']) ? $posted['billing_last_name'] : ''),
								'phone' => (isset($posted['billing_phone']) ? $posted['billing_phone'] : ''),
								'email' => (isset($posted['billing_email']) ? $posted['billing_email'] : ''),
								'address' => (isset($posted['billing_address_1']) ? $posted['billing_address_1'] : ''),
								'town' => (isset($posted['billing_city']) ? $posted['billing_city'] : ''),
								'zip' => (isset($posted['billing_postcode']) ? $posted['billing_postcode'] : ''),
								'state' => (isset($posted['billing_state']) ? $posted['billing_state'] : ''),
								'country' => (isset($posted['billing_country']) ? $posted['billing_country'] : ''),
								'booking_datetime' => null,
								'availability_id' => null,
								'people_count' => null,
								'is_private' => null,
								'total_price' => null,			
							);
							
							$transfers_plugin_post_types->update_booking_entry($booking_id, $booking_args);
						}
					}
				}
			}
		}
	}
	
	function add_order_item_meta($item_id, $values, $cart_item_key ) {

		global $woocommerce;
		$cart_item_meta = $woocommerce->session->get(TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY . $cart_item_key);
		
		if ($cart_item_meta != null) {
			
			$booking_id = $cart_item_meta[TRANSFERS_WOOCOMMERCE_BOOKING_ID];
			
			if ($booking_id > 0) {
			
				wc_add_order_item_meta($item_id, TRANSFERS_WOOCOMMERCE_BOOKING_ID, $booking_id, true);
			}
		}
	}	
	
	function woocommerce_cart_item_thumbnail($image, $cart_item, $cart_item_key) {

		global $transfers_plugin_globals;
	
		if (isset($cart_item['data'])) {
		
			$object_class = get_class($cart_item['data']);
			
			if ($object_class == 'WC_Product_Variation' && isset($cart_item['data']) && $cart_item['data']->post != null && $cart_item['data']->post->post_name == $this->product_slug) {
			
				global $woocommerce, $transfers_plugin_post_types;
			
				$product_id   	= $cart_item['product_id'];
				$variation_id   = $cart_item['variation_id'];
				
				$variation = new WC_Product_Variation($variation_id);
				$attributes = $variation->get_variation_attributes();	
				
				$cart_item_meta = $woocommerce->session->get(TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY . $cart_item_key);
				
				if ($cart_item_meta != null) {

					$booking_id = $cart_item_meta[TRANSFERS_WOOCOMMERCE_BOOKING_ID];
					$booking_entry = $transfers_plugin_post_types->get_booking_entry($booking_id);
					
					if ($booking_entry != null) {
					
						$availability_id = $booking_entry->availability_id;
						$availability_entry = $transfers_plugin_post_types->get_availability_entry($availability_id);
						
						if ($availability_entry != null) {
						
							$transport_type_id = $availability_entry->transport_type_id;
							$transfers_transport_type = new transfers_transport_type($transport_type_id);
							$main_image_src = $transfers_transport_type->get_main_image();
							$transfers_transport_type_title = $transfers_transport_type->get_title();
							
							if (empty($main_image_src)) {
							
								$default_placeholder_img = $transfers_plugin_globals->get_woocommerce_product_placeholder_image();
								return $default_placeholder_img;
							}
							
							return "<img src='$main_image_src' alt='$transfers_transport_type_title' />";
						}
					}
				}
			}
		}
		
		return $image;
	}
	

	function woocommerce_template_include($template) {

		$find = array( );
		$file = '';
		
		global $post;
		
		if (isset($post) && $post->post_name == $this->product_slug) {
			$file 	= '404.php';
			$find[] = $file;
		}

		if ( $file ) {
			$template = locate_template( $find );
		}
		
		return $template;
	}
	
	// Show order details (from, to, transport type, dates etc) in order admin when viewing individual orders.
	function woocommerce_before_order_itemmeta($item_id, $item, $_product) {
	
		global $transfers_plugin_post_types;
		
		$date_format = get_option('date_format');
	
		$product_id   	= $item['product_id'];
		$variation_id   = $item['variation_id'];
		
		$variation = new WC_Product_Variation($variation_id);
		
		$booking_id = wc_get_order_item_meta($item_id, TRANSFERS_WOOCOMMERCE_BOOKING_ID, true);
		$booking_entry = $transfers_plugin_post_types->get_booking_entry($booking_id);
		
		if ($booking_entry != null && $variation != null) {
		
			$attributes = $variation->get_variation_attributes();	

			$destination_from_title = $attributes['attribute_' . TRANSFERS_WOOCOMMERCE_DESTINATION_FROM_ATT];
			$destination_to_title = $attributes['attribute_' . TRANSFERS_WOOCOMMERCE_DESTINATION_TO_ATT];
			$transport_type_title = $attributes['attribute_' . TRANSFERS_WOOCOMMERCE_TRANSPORT_TYPE_ATT];
			
			echo '<br />';
		
			$is_private = $booking_entry->is_private;
			$people_count = $booking_entry->people_count;
			$availability_id = $booking_entry->availability_id;
			$booking_datetime = $booking_entry->booking_datetime;
			$booking_datetime = date($date_format, strtotime($booking_datetime));
			
			$availability_entry = $transfers_plugin_post_types->get_availability_entry($availability_id);
			if ($availability_entry->entry_type=='byminute') {
				$full_booking_datetime = date(TRANSFERS_PHP_DATE_FORMAT, strtotime($booking_entry->booking_datetime));
				$hours = intval(date('H', strtotime($full_booking_datetime)));
				$minutes = intval(date('i', strtotime($full_booking_datetime)));

				$total_minutes = ($hours * 60) + $minutes;
				$slot_minutes = Transfers_Plugin_Utils::display_hours_and_minutes($total_minutes);
			}				
			else
				$slot_minutes = Transfers_Plugin_Utils::display_hours_and_minutes($availability_entry->slot_minutes);
			
			$extra_items_string = '';				
			$extra_items_results = $transfers_plugin_post_types->get_booking_entry_extra_items($booking_id);
			
			foreach ($extra_items_results as $extra_item) {
				$extra_items_string .= $extra_item->quantity . ' x ' . $extra_item->extra_item . ', ';
			}
		
			$extra_items_string = trim(rtrim($extra_items_string, ', '));
			
			if (empty($extra_items_string)) {
				echo sprintf(__('Transfer from %s to %s by %s (%s) <br />on %s at %s<br />People: %d', 'transfers'), $destination_from_title, $destination_to_title, $transport_type_title, ($is_private ? esc_html__('Private', 'transfers') : esc_html__('Shared', 'transfers')), $booking_datetime, $slot_minutes, $people_count);
			} else {
				echo sprintf(__('Transfer from %s to %s by %s (%s) <br />on %s at %s<br />People: %d<br />Extras: %s', 'transfers'), $destination_from_title, $destination_to_title, $transport_type_title, ($is_private ? esc_html__('Private', 'transfers') : esc_html__('Shared', 'transfers')), $booking_datetime, $slot_minutes, $people_count, $extra_items_string);		
			}
		}
	}
	
	function woocommerce_variation_is_purchasable($purchasable, $product_variation) {
	
		$object_class = get_class($product_variation);
		
		if ($object_class == 'WC_Product_Variation' && $product_variation->post->post_name == $this->product_slug) {
			// mark purchasable as true even though we have not specified product price when creating product and variation, which allows us to set the price at the time product is added to cart.
			$purchasable = true;
		}
		
		return $purchasable;
	}
		
	function add_custom_total_price($cart_object) {
		
		// this is where we access our booking object, get price, and update cart with it to have things synced.
		global $woocommerce, $transfers_plugin_post_types;
		
		foreach ( $cart_object->cart_contents as $key => $value ) {
		
			$cart_item_meta = $woocommerce->session->get(TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY . $key);
			if ($cart_item_meta != null) {

				$booking_id = $cart_item_meta[TRANSFERS_WOOCOMMERCE_BOOKING_ID];
				$booking_entry = $transfers_plugin_post_types->get_booking_entry($booking_id);
				
				if ($booking_entry != null)
					$value['data']->price = $booking_entry->total_price;
			}
		}
	}
		
	function woocommerce_order_item_name($product_title, $item) {
	
		global $transfers_plugin_post_types;
		
		$product_id   	= $item['product_id'];
		$variation_id   = $item['variation_id'];
		
		$variation = new WC_Product_Variation($variation_id);
		
		if (isset($item[TRANSFERS_WOOCOMMERCE_BOOKING_ID])) {
		
			$booking_id = (int)$item[TRANSFERS_WOOCOMMERCE_BOOKING_ID];
			$booking_entry = $transfers_plugin_post_types->get_booking_entry($booking_id);
			
			if ($booking_entry != null && $variation != null) {
			
				$attributes = $variation->get_variation_attributes();	

				$destination_from_title = $attributes['attribute_' . TRANSFERS_WOOCOMMERCE_DESTINATION_FROM_ATT];
				$destination_to_title = $attributes['attribute_' . TRANSFERS_WOOCOMMERCE_DESTINATION_TO_ATT];
				$transport_type_title = $attributes['attribute_' . TRANSFERS_WOOCOMMERCE_TRANSPORT_TYPE_ATT];
			
				$date_format = get_option('date_format');
			
				$is_private = $booking_entry->is_private;
				$people_count = $booking_entry->people_count;
				$availability_id = $booking_entry->availability_id;
				$booking_datetime = $booking_entry->booking_datetime;
				$booking_datetime = date($date_format, strtotime($booking_datetime));
				
				$availability_entry = $transfers_plugin_post_types->get_availability_entry($availability_id);
				if ($availability_entry->entry_type=='byminute') {
					$full_booking_datetime = date(TRANSFERS_PHP_DATE_FORMAT, strtotime($booking_entry->booking_datetime));
					$hours = intval(date('H', strtotime($full_booking_datetime)));
					$minutes = intval(date('i', strtotime($full_booking_datetime)));

					$total_minutes = ($hours * 60) + $minutes;
					$slot_minutes = Transfers_Plugin_Utils::display_hours_and_minutes($total_minutes);
				}				
				else
					$slot_minutes = Transfers_Plugin_Utils::display_hours_and_minutes($availability_entry->slot_minutes);
				
				$extra_items_string = '';				
				$extra_items_results = $transfers_plugin_post_types->get_booking_entry_extra_items($booking_id);
				
				foreach ($extra_items_results as $extra_item) {
					$extra_items_string .= $extra_item->quantity . ' x ' . $extra_item->extra_item . ', ';
				}
			
				$extra_items_string = trim(rtrim($extra_items_string, ', '));
				
				if (empty($extra_items_string)) {
					return sprintf(__('Transfer from %s to %s by %s (%s) <br />on %s at %s<br />People: %d', 'transfers'), $destination_from_title, $destination_to_title, $transport_type_title, ($is_private ? esc_html__('Private', 'transfers') : esc_html__('Shared', 'transfers')), $booking_datetime, $slot_minutes, $people_count);
				} else {
					return sprintf(__('Transfer from %s to %s by %s (%s) <br />on %s at %s<br />People: %d<br />Extras: %s', 'transfers'), $destination_from_title, $destination_to_title, $transport_type_title, ($is_private ? esc_html__('Private', 'transfers') : esc_html__('Shared', 'transfers')), $booking_datetime, $slot_minutes, $people_count, $extra_items_string);		
				}
			}
		}
		
		return $product_title;
	}
	
	function woocommerce_cart_item_name($product_title, $cart_item, $cart_item_key){
	   
		$date_format = get_option('date_format');
		global $woocommerce, $transfers_plugin_post_types;
		
		if (isset($cart_item['data'])) {
		
			$item_data = $cart_item['data'];
			
			$object_class = get_class($item_data);		
			
			if ( !$item_data || !$item_data->post || $item_data->post->post_name != $this->product_slug || $object_class != 'WC_Product_Variation') {
				return $product_title;
			}
			
			$attributes = $item_data->get_variation_attributes();		
			if ( ! $attributes ) {
				return $product_title;
			}

			$destination_from_title = $attributes['attribute_' . TRANSFERS_WOOCOMMERCE_DESTINATION_FROM_ATT];
			$destination_to_title = $attributes['attribute_' . TRANSFERS_WOOCOMMERCE_DESTINATION_TO_ATT];
			$transport_type_title = $attributes['attribute_' . TRANSFERS_WOOCOMMERCE_TRANSPORT_TYPE_ATT];
			
			$cart_item_meta = $woocommerce->session->get(TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY . $cart_item_key);
			$booking_id = $cart_item_meta[TRANSFERS_WOOCOMMERCE_BOOKING_ID];
			$booking_entry = $transfers_plugin_post_types->get_booking_entry($booking_id);
			
			if ($booking_entry != null) {
				$is_private = $booking_entry->is_private;
				$availability_id = $booking_entry->availability_id;
				$people_count = $booking_entry->people_count;
				$booking_datetime = $booking_entry->booking_datetime;
				$booking_datetime = date($date_format, strtotime($booking_datetime));
				
				$availability_entry = $transfers_plugin_post_types->get_availability_entry($availability_id);
				if ($availability_entry->entry_type=='byminute') {
				
					$full_booking_datetime = date(TRANSFERS_PHP_DATE_FORMAT, strtotime($booking_entry->booking_datetime));
					
					$hours = intval(date('H', strtotime($full_booking_datetime)));
					$minutes = intval(date('i', strtotime($full_booking_datetime)));
					
					$total_minutes = ($hours * 60) + $minutes;
					$slot_minutes = Transfers_Plugin_Utils::display_hours_and_minutes($total_minutes);
				}				
				else
					$slot_minutes = Transfers_Plugin_Utils::display_hours_and_minutes($availability_entry->slot_minutes);
				
				$extra_items_string = '';				
				$extra_items_results = $transfers_plugin_post_types->get_booking_entry_extra_items($booking_id);
				
				foreach ($extra_items_results as $extra_item) {
					$extra_items_string .= $extra_item->quantity . ' x ' . $extra_item->extra_item . ', ';
				}
			
				$extra_items_string = trim(rtrim($extra_items_string, ', '));
				
				if (empty($extra_items_string)) {
					return sprintf(__('Transfer from %s to %s by %s (%s) <br />on %s at %s<br />People: %d', 'transfers'), $destination_from_title, $destination_to_title, $transport_type_title, ($is_private ? esc_html__('Private', 'transfers') : esc_html__('Shared', 'transfers')), $booking_datetime, $slot_minutes, $people_count);
				} else {
					return sprintf(__('Transfer from %s to %s by %s (%s) <br />on %s at %s<br />People: %d<br />Extras: %s', 'transfers'), $destination_from_title, $destination_to_title, $transport_type_title, ($is_private ? esc_html__('Private', 'transfers') : esc_html__('Shared', 'transfers')), $booking_datetime, $slot_minutes, $people_count, $extra_items_string);		
				}
			}
			
			return $product_title;
		}
	}
	
	public function get_post_id_from_attributes($attributes, $item_key, $language_id, $post_type) {
	
		$post_id = 0;
		
	    foreach ( $attributes as $key => $value ) {		
		
			if ($key == $item_key) {
				$post_id = transfers_get_language_post_id($value, $post_type, $language_id);
				break;
			}
		}
		
		return $post_id;
	}
	
	
	public function booking_add_to_cart_request() {
	
		if ( isset($_REQUEST) ) {

			$nonce = $_REQUEST['nonce'];
			
			if ( wp_verify_nonce( $nonce, 'transfers-ajax-nonce' ) ) {
					
				global $transfers_plugin_globals, $woocommerce, $transfers_plugin_post_types;
				
				if ($transfers_plugin_globals->use_woocommerce_for_checkout()) {
					
					$booking_object 		= $transfers_plugin_post_types->retrieve_booking_object_from_request();
					
					$departure_booking_id 	= $transfers_plugin_post_types->create_booking_entry($booking_object->departure_booking_args);
					$return_booking_id		= 0;
					
					if (isset($booking_object->return_booking_args) && $booking_object->return_booking_args != null) {					
						$return_booking_id 	= $transfers_plugin_post_types->create_booking_entry($booking_object->return_booking_args);
					}
					
					$departure_booking			= $transfers_plugin_post_types->get_booking_entry($departure_booking_id);
					$departure_destination_from = $departure_booking->destination_from;
					$departure_destination_to 	= $departure_booking->destination_to;
					$departure_transport_type 	= $departure_booking->transport_type;
					
					$product_id 			= $this->get_transfers_product_id();
					if (!isset($product_id) || empty($product_id)) {
						$product_id 		= $this->create_transfers_product();
					}
				
					$variation_id 			= $this->get_transfers_product_variation_id($product_id, $departure_destination_from, $departure_destination_to, $departure_transport_type);
					if (!isset($variation_id) || empty($variation_id)) {
						$variation_id 		= $this->create_transfers_product_variation($product_id, $departure_destination_from, $departure_destination_to, $departure_transport_type);
					}
					
					if ($product_id > 0 && $variation_id > 0) {

						$cart_item_data 		= array('booking_id' => $departure_booking_id);
						$cart_item_key 			= $woocommerce->cart->add_to_cart( $product_id, 1, $variation_id, null, $cart_item_data);
						$woocommerce->session->set(TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY . $cart_item_key, array(TRANSFERS_WOOCOMMERCE_BOOKING_ID => $departure_booking_id));
					}			
					
					if ($return_booking_id > 0) {
					
						$return_booking			= $transfers_plugin_post_types->get_booking_entry($return_booking_id);
						$return_destination_from = $return_booking->destination_from;
						$return_destination_to 	= $return_booking->destination_to;
						$return_transport_type 	= $return_booking->transport_type;
					
						$product_id 			= $this->get_transfers_product_id();
					
						$variation_id 			= $this->get_transfers_product_variation_id($product_id, $return_destination_from, $return_destination_to, $return_transport_type);
						if (!isset($variation_id) || empty($variation_id)) {
							$variation_id 		= $this->create_transfers_product_variation($product_id, $return_destination_from, $return_destination_to, $return_transport_type);
						}
					
						if ($product_id > 0 && $variation_id > 0) {
							$cart_item_data 		= array('booking_id' => $return_booking_id);
							$cart_item_key 			= $woocommerce->cart->add_to_cart( $product_id, 1, $variation_id, null, $cart_item_data);
							$woocommerce->session->set(TRANSFERS_WOOCOMMERCE_BOOKING_SESSION_KEY . $cart_item_key, array(TRANSFERS_WOOCOMMERCE_BOOKING_ID => $return_booking_id));
						}
					}
				}
			}
		}
		
		die();
	}
	
	function setup() {
	
		global $transfers_plugin_globals, $woocommerce;
	}
	
	function get_transfers_product_id() {
		
		global $wpdb;
		
		$sql = "SELECT Id FROM $wpdb->posts WHERE post_type='product' AND post_name = '%s' AND post_status='publish'";
		
		$id = $wpdb->get_var($wpdb->prepare($sql, $this->product_slug));
		
		return intval($id);		
	}
	
	function get_transfers_product_variation_id($product_id, $destination_from, $destination_to, $transport_type) {
		
		global $wpdb;
		
		$sql = "SELECT Id 
				FROM $wpdb->posts 
				WHERE post_type='product_variation' AND post_parent = %d AND post_name LIKE '%s' AND post_status='publish'";
		
		$product_name = $this->build_product_slug($destination_from, $destination_to, $transport_type);
		$sql = $wpdb->prepare($sql, $product_id, $product_name);
		
		return intval($wpdb->get_var($sql));		
	}
	
	function build_destinations_string($destination_id=null) {
		
		global $transfers_destinations_post_type;
		$destinations_str = '';
		
		$destination_results = $transfers_destinations_post_type->list_destinations(0, -1, 'title', 'ASC', $destination_id);
		
		if ( count($destination_results) > 0 && $destination_results['total'] > 0 ) {
			
			foreach ($destination_results['results'] as $destination_result) {

				$transfers_destination = new transfers_destination($destination_result->ID);
				$destinations_str .= $transfers_destination->get_title() . '|';
				
				$destinations_str .= $this->build_destinations_string($transfers_destination->get_id());
			}
		}
		
		return $destinations_str;
	}
		
	function build_transport_types_string() {
		
		global $transfers_transport_types_post_type;
		$transport_types_str = '';
		
		$transport_type_results = $transfers_transport_types_post_type->list_transport_types(0, -1, 'title', 'ASC');
		
		if ( count($transport_type_results) > 0 && $transport_type_results['total'] > 0 ) {
			
			foreach ($transport_type_results['results'] as $transport_type_result) {

				$transfers_transport_type = new transfers_transport_type($transport_type_result->ID);
				$transport_types_str .= $transfers_transport_type->get_title() . '|';
			}
		}
		
		return $transport_types_str;
	}
	
	function create_transfers_product() {
		
		$new_post = array(
			'post_title' 		=> esc_html__('Transfers Product', 'transfers'),
			'post_content' 		=> esc_html__('This is a variable product used for transfers processed via WooCommerce', 'transfers'),
			'post_status' 		=> 'publish',
			'post_name' 		=> $this->product_slug,
			'post_type' 		=> 'product',
			'comment_status' 	=> 'closed'
		);

		$product_id 			= wp_insert_post($new_post);
		$skuu 					= $this->woocommerce_random_sku('transfers_transfer_', 6);
		
		update_post_meta($product_id, '_sku', 				$skuu );
		
		wp_set_object_terms($product_id, 'variable', 		'product_type');
		
		$destinations_str = $this->build_destinations_string(null);
		$destinations_str = rtrim($destinations_str, '|');
		
		$transport_types_str = $this->build_transport_types_string();
		$transport_types_str = rtrim($transport_types_str, '|');
		
		$product_attributes = array(
			TRANSFERS_WOOCOMMERCE_DESTINATION_FROM_ATT => array(
				'name'			=> TRANSFERS_WOOCOMMERCE_DESTINATION_FROM_ATT,
				'value'			=> $destinations_str,
				'is_visible' 	=> '1',
				'is_variation' 	=> '1',
				'is_taxonomy' 	=> '0'
			),
			TRANSFERS_WOOCOMMERCE_DESTINATION_TO_ATT => array(
				'name'			=> TRANSFERS_WOOCOMMERCE_DESTINATION_TO_ATT,
				'value'			=> $destinations_str,
				'is_visible' 	=> '1',
				'is_variation' 	=> '1',
				'is_taxonomy' 	=> '0'
			),
			TRANSFERS_WOOCOMMERCE_TRANSPORT_TYPE_ATT => array(
				'name'			=> TRANSFERS_WOOCOMMERCE_TRANSPORT_TYPE_ATT,
				'value'			=> $transport_types_str,
				'is_visible' 	=> '1',
				'is_variation' 	=> '1',
				'is_taxonomy' 	=> '0'
			),
		);
		
		update_post_meta( $product_id, '_product_attributes', $product_attributes);
		
		return $product_id;
	}
	
	function create_transfers_product_variation($product_id, $destination_from, $destination_to, $transport_type) {
		
		global $transfers_plugin_post_types;
		
		$new_post = array(
			'post_title' 		=> sprintf(__('Transfer from %s to %s by %s', 'transfers'), $destination_from, $destination_to, $transport_type),
			'post_content' 		=> sprintf(__('This is a product variation used for the transfers processed via WooCommerce for transfer from %s to %s by %s', 'transfers'), $destination_from, $destination_to, $transport_type),
			'post_status' 		=> 'publish',
			'post_type' 		=> 'product_variation',
			'post_parent'		=> $product_id,
			'post_name' 		=> $this->build_product_slug($destination_from, $destination_to, $transport_type),
			'comment_status' 	=> 'closed'
		);

		$variation_id 			= wp_insert_post($new_post);
		
		update_post_meta($variation_id, '_stock_status', 		'instock');
		update_post_meta($variation_id, '_sold_individually', 	'no');
		update_post_meta($variation_id, '_virtual', 			'yes');
		update_post_meta($variation_id, '_downloadable', 		'no');
		update_post_meta($variation_id, 'attribute_' . TRANSFERS_WOOCOMMERCE_DESTINATION_FROM_ATT, $destination_from);
		update_post_meta($variation_id, 'attribute_' . TRANSFERS_WOOCOMMERCE_DESTINATION_TO_ATT, $destination_to);
		update_post_meta($variation_id, 'attribute_' . TRANSFERS_WOOCOMMERCE_TRANSPORT_TYPE_ATT, $transport_type);
		
		return $variation_id;
	}
	
	function woocommerce_random_sku($prefix, $len = 6) {
		$str = '';
		for ($i = 0; $i < $len; $i++) {
			$str .= substr('0123456789', mt_rand(0, strlen('0123456789') - 1), 1);
		}
		return $prefix . $str; 
	}	
}

// store the instance in a variable to be retrieved later and call init
$transfers_plugin_woocommerce = Transfers_Plugin_WooCommerce::get_instance();
$transfers_plugin_woocommerce->init();