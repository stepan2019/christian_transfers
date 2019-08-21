<?php

class Transfers_Services_Post_Type extends Transfers_BaseSingleton {

	private $enable_services;

	protected function __construct() {
	
		global $transfers_plugin_globals;
		
		$this->enable_services = $transfers_plugin_globals->enable_services();
	
        // our parent class might
        // contain shared code in its constructor
        parent::__construct();	
	}
	
    public function init() {
	
		if ($this->enable_services) {

			add_action( 'admin_init', array($this, 'remove_unnecessary_meta_boxes') );
			add_filter('manage_edit-service_columns', array( $this, 'manage_edit_service_columns'), 10, 1);	
			add_action( 'transfers_plugin_initialize_post_types', array( $this, 'initialize_post_type' ), 0);		
		}		
	
	}
	
	function remove_unnecessary_meta_boxes() {

	}	
	
	function manage_edit_service_columns($columns) {
	
		//unset($columns['taxonomy-service_type']);
		return $columns;
	}
	
	function initialize_post_type() {
	
		$this->register_service_post_type();
	}
	
	function register_service_post_type() {
		
		global $transfers_plugin_globals;
		
		$labels = array(
			'name'                => _x( 'Services', 'Post Type General Name', 'transfers' ),
			'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'transfers' ),
			'menu_name'           => esc_html__( 'Services', 'transfers' ),
			'all_items'           => esc_html__( 'All Services', 'transfers' ),
			'view_item'           => esc_html__( 'View Service', 'transfers' ),
			'add_new_item'        => esc_html__( 'Add New Service', 'transfers' ),
			'add_new'             => esc_html__( 'New Service', 'transfers' ),
			'edit_item'           => esc_html__( 'Edit Service', 'transfers' ),
			'update_item'         => esc_html__( 'Update Service', 'transfers' ),
			'search_items'        => esc_html__( 'Search Services', 'transfers' ),
			'not_found'           => esc_html__( 'No Services found', 'transfers' ),
			'not_found_in_trash'  => esc_html__( 'No Services found in Trash', 'transfers' ),
		);
		$args = array(
			'label'               => esc_html__( 'service', 'transfers' ),
			'description'         => esc_html__( 'Service information pages', 'transfers' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
			'taxonomies'          => array( ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'rewrite' 			  => false,
		);
		
		register_post_type( 'service', $args );	
	}
	
	
	function list_services($paged = 0, $per_page = -1, $orderby = '', $order = '', $author_id = null, $include_private = false, $count_only = false ) {
	
		$args = array(
			'post_type'         => 'service',
			'post_status'       => array('publish'),
			'posts_per_page'    => $per_page,
			'paged' 			=> $paged, 
			'orderby'           => $orderby,
			'suppress_filters' 	=> false,
			'order'				=> $order,
			'meta_query'        => array('relation' => 'AND')
		);
		
		if ($include_private) {
			$args['post_status'][] = 'private';
		}
		
		if (isset($author_id)) {
			$author_id = intval($author_id);
			if ($author_id > 0) {
				$args['author'] = $author_id;
			}
		}
	
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
	
}

global $transfers_services_post_type;
// store the instance in a variable to be retrieved later and call init
$transfers_services_post_type = Transfers_Services_Post_Type::get_instance();
$transfers_services_post_type->init();