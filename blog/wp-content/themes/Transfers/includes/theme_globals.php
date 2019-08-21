<?php

class Transfers_Theme_Globals extends Transfers_BaseSingleton {

	protected function __construct() {
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();
    }

    public function init() {
	
    }
	
	public function get_time_slot_increment() {
		$increment = (int)of_get_option('time_slot_increment', 5);
		if ($increment == 0)
			return 5;
		return $increment;
	}
		
	public function get_search_results_by_minute_count() {
		$search_results_by_minute_count = (int)of_get_option('search_results_by_minute_count', 5);
		if ($search_results_by_minute_count == 0)
			return 5;
		return $search_results_by_minute_count;
	}
	
	public function get_terms_page_url() {
		$terms_page_url_id = transfers_get_current_language_page_id(of_get_option('terms_page_url', ''));
		return get_permalink($terms_page_url_id);
	}
	
	public function override_wp_login() {
	
		$login_page_url_id = transfers_get_current_language_page_id( of_get_option('login_page_url', '') );
		$login_page_url = get_permalink($login_page_url_id);		

		if (empty($login_page_url) || $login_page_url_id == 0)
			return 0;

		// only allow override of wp login if a login page is set.
		return of_get_option('override_wp_login', 0);
	}
	
	public function get_blog_index_sidebar_position() {
		return of_get_option('blog_index_sidebar_position', '');
	}
	
	public function get_blog_index_sort_by_column() {
		return of_get_option('blog_index_sort_by_column', 'date');
	}
	
	public function blog_index_sort_descending() {
		return of_get_option('blog_index_sort_descending', 0);
	}
	
	public function blog_index_show_grid_view() {
		return of_get_option('blog_index_show_grid_view', 0);
	}

	public function get_woocommerce_pages_sidebar_position() {
		return of_get_option('woocommerce_pages_sidebar_position', null);
	}
	
	public function get_iconic_features_icon_classes() {
		$default_classes = "award\nclip\nheart\nlock\npig\nshuttle\ntelephone\nwallet\nwand";
		return of_get_option('iconic_features_widget_classes', $default_classes);
	}
	
	public function get_countries() {
		if (class_exists('WC_Countries')) {
			$woo_countries       = new WC_Countries();
			return $woo_countries->get_countries();		
		}
		return array();
	}

	public function get_breadcrumbs() {
	
		$bread = '';
		
		if ( function_exists('woocommerce_breadcrumb') ) {
			$breadcrumb_args = array(
					'delimiter' => '',
					'before' => '<li>',
					'after' => '</li>',
					'wrap_before' => '<nav role="navigation" class="breadcrumbs"><ul>',
					'wrap_after' => '</ul></nav>',
			);
			$bread = woocommerce_breadcrumb($breadcrumb_args);
		}
		
		return apply_filters( 'transfers_breadcrumbs', $bread );
	}

	public function get_contact_company_name() {
		return of_get_option('contact_company_name', '');
	}

	public function get_contact_address() {
		return of_get_option('contact_address', '');
	}	
	
	public function get_contact_address_latitude() {
		return of_get_option('contact_address_latitude', '');
	}
	
	public function get_contact_address_longitude() {
		return of_get_option('contact_address_longitude', '');
	}
	
	public function add_captcha_to_forms() {
		return (int)of_get_option('add_captcha_to_forms', true);
	}
	
	public function get_enc_key() {
		return preg_replace('{/$}', '', $_SERVER['SERVER_NAME']);
	}
	
	public function let_users_set_pass() {
		return of_get_option('let_users_set_pass', false);
	}
	
	public function enable_rtl() {
		return of_get_option('enable_rtl', false);
	}

	public function show_preloader() {
		return of_get_option('show_preloader', true);
	}
	
	public function get_blog_posts_root_url() {
		return get_permalink( get_option( 'page_for_posts' ) );
	}
	
	public function get_site_url() {
		return site_url();
	}
	
	public function get_copyright_footer() {
		return of_get_option('copyright_footer', '');
	}
		
	public function get_color_scheme_style_sheet() {
		return of_get_option('color_scheme_select', 'theme-pink');
	}

	public function get_theme_logo_src() {
	
		$logo_src = of_get_option( 'website_logo_upload', '' );
		if (empty($logo_src)) {
			$logo_src = transfers_get_file_uri('/images/transfers.jpg');
		}		
		return $logo_src;
	}
	
	public function get_theme_favicon_src() {
	
		$favicon_src = of_get_option( 'website_favicon_upload', '' );
		if (empty($favicon_src)) {
			$favicon_src = transfers_get_file_uri('/images/favicon.ico');
		}		

		return $favicon_src;
	}
	
	public function get_contact_page_url() {
		$contact_page_url_id = transfers_get_current_language_page_id( of_get_option('contact_page_url', '') );
		return get_permalink($contact_page_url_id);
	}
	
	public function get_login_page_url() {
		$login_page_url_id = transfers_get_current_language_page_id( of_get_option('login_page_url', '') );
		$login_page_url = get_permalink($login_page_url_id);		
		if (empty($login_page_url) || $login_page_url_id == 0)
			$login_page_url = home_url('/') . '/wp-login.php';
		return $login_page_url;
	}
	
	public function get_redirect_to_after_login_page_url() {
		$redirect_to_after_login_id = transfers_get_current_language_page_id( of_get_option('redirect_to_after_login', '') );
		$redirect_to_after_login_url = get_permalink($redirect_to_after_login_id);
		if (empty($redirect_to_after_login_url) || $redirect_to_after_login_id == 0)
			$redirect_to_after_login_url = home_url('/');
		return $redirect_to_after_login_url;
	}
	
	public function get_redirect_to_after_logout_url() {
		$redirect_to_after_logout_id = transfers_get_current_language_page_id(of_get_option('redirect_to_after_logout', ''));
		$redirect_to_after_logout_url = get_permalink($redirect_to_after_logout_id);
		if (empty($redirect_to_after_logout_url) || $redirect_to_after_logout_id == 0)
			$redirect_to_after_logout_url = home_url('/');
		return $redirect_to_after_logout_url;
	}
	
	public function get_register_page_url() {
		$register_page_url_id = transfers_get_current_language_page_id(of_get_option('register_page_url', ''));
		$register_page_url = get_permalink($register_page_url_id);		
		if (empty($register_page_url) || $register_page_url_id == 0)
			$register_page_url = home_url('/') . '/wp-login.php?action=register';
		return $register_page_url;
	}
	
	public function get_reset_password_page_url() {
		$reset_password_page_url_id = transfers_get_current_language_page_id(of_get_option('reset_password_page_url', ''));
		$reset_password_page_url = get_permalink($reset_password_page_url_id);
		if (empty($reset_password_page_url) || $reset_password_page_url_id == 0)
			$reset_password_page_url = home_url('/') . '/wp-login.php?action=lostpassword';
		return $reset_password_page_url;
	}
	
	public function get_user_account_page_url() {
		$user_account_page_url_id = transfers_get_current_language_page_id(of_get_option('user_account_page_url', ''));
		$user_account_page_url = get_permalink($user_account_page_url_id);
		return $user_account_page_url;
	}
	
}

global $transfers_theme_globals;
// store the instance in a variable to be retrieved later and call init
$transfers_theme_globals = Transfers_Theme_Globals::get_instance();
$transfers_theme_globals->init();