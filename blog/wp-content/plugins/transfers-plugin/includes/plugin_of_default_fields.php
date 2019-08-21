<?php

class Transfers_Plugin_Of_Default_Fields extends Transfers_BaseSingleton {

	protected function __construct() {
	
        // our parent class might contain shared code in its constructor
        parent::__construct();
		
    }
	
    public function init() {

	}	
}

// store the instance in a variable to be retrieved later and call init
$transfers_plugin_of_default_fields = Transfers_Plugin_Of_Default_Fields::get_instance();
$transfers_plugin_of_default_fields->init();

global $repeatable_field_types;
$repeatable_field_types = array(
	'text' => esc_html__('Text box', 'transfers'),
	'textarea' => esc_html__('Text area', 'transfers'),
	'image' => esc_html__('Image selector', 'transfers')
);

global $default_destination_extra_fields;
$default_destination_extra_fields = array();