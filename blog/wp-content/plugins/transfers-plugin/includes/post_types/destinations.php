<?php


class Transfers_Destinations_Post_Type extends Transfers_BaseSingleton {

	private $enable_destinations;
	private $destination_custom_meta_fields;
	private $destination_list_custom_meta_fields;
	private $destination_list_meta_box;	

	protected function __construct() {
	
		global $post, $transfers_plugin_globals;
		
		$this->enable_destinations = $transfers_plugin_globals->enable_destinations();	

		if ($this->enable_destinations) {
		
			$this->destination_custom_meta_fields = array(
			);
			
			global $default_destination_extra_fields;
			$destination_extra_fields = $transfers_plugin_globals->get_destination_extra_fields();
			if (!is_array($destination_extra_fields) || count($destination_extra_fields) == 0) {
				$destination_extra_fields = $default_destination_extra_fields;
			}

			foreach ($destination_extra_fields as $destination_extra_field) {
				$field_is_hidden = isset($destination_extra_field['hide']) ? intval($destination_extra_field['hide']) : 0;
				
				if (!$field_is_hidden) {
					$extra_field = null;
					$field_label = isset($destination_extra_field['label']) ? $destination_extra_field['label'] : '';
					$field_id = isset($destination_extra_field['id']) ? $destination_extra_field['id'] : '';
					$field_type = isset($destination_extra_field['type']) ? $destination_extra_field['type'] :  '';
					if (!empty($field_label) && !empty($field_id) && !empty($field_type)) {
						$extra_field = array(
							'label'	=> $field_label,
							'desc'	=> '',
							'id'	=> '_destination_' . $field_id,
							'type'	=> $field_type
						);
					}

					if ($extra_field) 
						$this->destination_custom_meta_fields[] = $extra_field;
				}
			}
		}
		
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();	
	}
	
    public function init() {

		if ($this->enable_destinations) {	
		
			add_action( 'admin_init', array($this, 'remove_unnecessary_meta_boxes') );
			add_filter( 'manage_edit-destination_columns', array( $this, 'manage_edit_destination_columns'), 10, 1);	
			add_action( 'transfers_plugin_initialize_post_types', array( $this, 'initialize_post_type' ), 0);
			add_action( 'admin_init', array( $this, 'destination_admin_init' ) );
		}
	}
		
	function remove_unnecessary_meta_boxes() {

	}
	
	function manage_edit_destination_columns($columns) {
	
		return $columns;
	}
	
	function destination_admin_init() {
		new Transfers_Add_Meta_Box( 'destination_custom_meta_fields', esc_html__('Extra information', 'transfers'), $this->destination_custom_meta_fields, 'destination' );
	}
	
	function destination_list_add_meta_boxes() {

	}
			
	function initialize_post_type() {
	
		$this->register_destination_post_type();
	}
		
	function register_destination_post_type() {
			
		global $transfers_plugin_globals;
		$destinations_permalink_slug = $transfers_plugin_globals->get_destinations_permalink_slug();
		
		$destination_list_page_id = $transfers_plugin_globals->get_destination_list_page_id();
		
		if ($destination_list_page_id > 0) {

			add_rewrite_rule(
				"{$destinations_permalink_slug}$",
				"index.php?post_type=page&page_id={$destination_list_page_id}", 'top');
		
			add_rewrite_rule(
				"{$destinations_permalink_slug}/page/?([1-9][0-9]*)",
				"index.php?post_type=page&page_id={$destination_list_page_id}&paged=\$matches[1]", 'top');
		
		}
		
		add_rewrite_rule(
			"{$destinations_permalink_slug}/([^/]+)/page/?([1-9][0-9]*)",
			"index.php?post_type=destination&name=\$matches[1]&paged-tf=\$matches[2]", 'top');
			
		add_rewrite_tag('%paged-tf%', '([1-9][0-9]*)');		
		
		$labels = array(
			'name'                => _x( 'Destinations', 'Post Type General Name', 'transfers' ),
			'singular_name'       => _x( 'Destination', 'Post Type Singular Name', 'transfers' ),
			'menu_name'           => esc_html__( 'Destinations', 'transfers' ),
			'all_items'           => esc_html__( 'All Destinations', 'transfers' ),
			'view_item'           => esc_html__( 'View Destination', 'transfers' ),
			'add_new_item'        => esc_html__( 'Add New Destination', 'transfers' ),
			'add_new'             => esc_html__( 'New Destination', 'transfers' ),
			'edit_item'           => esc_html__( 'Edit Destination', 'transfers' ),
			'update_item'         => esc_html__( 'Update Destination', 'transfers' ),
			'search_items'        => esc_html__( 'Search Destinations', 'transfers' ),
			'not_found'           => esc_html__( 'No Destinations found', 'transfers' ),
			'not_found_in_trash'  => esc_html__( 'No Destinations found in Trash', 'transfers' ),
		);
		$args = array(
			'label'               => esc_html__( 'Destination', 'transfers' ),
			'description'         => esc_html__( 'Destination information pages', 'transfers' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'page-attributes' ),
			'taxonomies'          => array( ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite' 			  =>  array('slug' => $destinations_permalink_slug),
		);
		
		register_post_type( 'destination', $args );	
	}
	
	function destinations_search_join($join) {
		global $wp_query, $wpdb, $transfers_multi_language_count;

		if (!is_admin()) {
			if(defined('ICL_LANGUAGE_CODE') && (transfers_get_default_language() != ICL_LANGUAGE_CODE || $transfers_multi_language_count > 1)) {
				$join .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations_default ON translations_default.element_type = 'post_destination' AND translations_default.language_code='" . transfers_get_default_language() . "' AND translations_default.element_id = {$wpdb->posts}.ID ";			
				$join .= " INNER JOIN " . $wpdb->prefix . "icl_translations translations ON translations.element_type = 'post_destination' AND translations.language_code='" . ICL_LANGUAGE_CODE . "' AND translations.trid = translations_default.trid ";
			}
		}
		
		return $join;
	}
	
	function list_destinations($paged = 0, $per_page = -1, $orderby = '', $order = '', $parent_id = null, $author_id = null, $include_private = false, $count_only = false ) {
	
		$args = array(
			'post_type'         => 'destination',
			'post_status'       => array('publish'),
			'posts_per_page'    => $per_page,
			'paged' 			=> $paged, 
			'orderby'           => $orderby,
			'suppress_filters' 	=> false,
			'order'				=> $order,
		);

		if (isset($parent_id) && $parent_id > 0) {
			$args['post_parent'] = $parent_id;
		} else {
			$args['post_parent'] = 0;
		}
		
		$meta_query = array('relation' => 'AND');
		
		if ($include_private) {
			$args['post_status'][] = 'private';
		}
		
		if (isset($author_id)) {
			$author_id = intval($author_id);
			if ($author_id > 0) {
				$args['author'] = $author_id;
			}
		}
		
		$args['meta_query'] = $meta_query;
	
		add_filter('posts_join', array($this, 'destinations_search_join'), 10, 2 );	
	
		$posts_query = new WP_Query($args);
		
		if ($count_only) {
			$results = array(
				'total' => $posts_query->found_posts,
				'results' => null
			);	
		} else {
			$results = array();
			
			if ($posts_query->have_posts() ) {
				while ( $posts_query->have_posts() ) {
					global $post;
					$posts_query->the_post(); 
					$results[] = $post;
				}
			}
		
			$results = array(
				'total' => $posts_query->found_posts,
				'results' => $results
			);
		}
		
		wp_reset_postdata();
		
		return $results;
	}
	
	public function list_leaf_destinations($parent_id=null, &$leaves) {
		
		$destination_results = $this->list_destinations(0, -1, 'title', 'ASC', $parent_id);
		
		if ( count($destination_results) > 0 && $destination_results['total'] > 0 ) {
			
			foreach ($destination_results['results'] as $destination_result) {

				$transfers_sub_destination = new transfers_destination($destination_result->ID);
				
				if ($transfers_sub_destination->is_parent() == '1') {
					$this->list_leaf_destinations($transfers_sub_destination->get_id(), $leaves);
				} else {
					$leaves[] = $transfers_sub_destination;
				}
			}		
		}
	}
}

global $transfers_destinations_post_type;
// store the instance in a variable to be retrieved later and call init
$transfers_destinations_post_type = Transfers_Destinations_Post_Type::get_instance();
$transfers_destinations_post_type->init();