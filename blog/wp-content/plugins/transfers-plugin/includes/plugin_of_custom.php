<?php

class Transfers_Plugin_Of_Custom extends Transfers_BaseSingleton {
	
	protected function __construct() {
	
        // our parent class might contain shared code in its constructor
        parent::__construct();		
    }
	
    public function init() {
		
		add_filter( 'optionsframework_repeat_extra_field', array( $this, 'repeat_extra_field_option_type' ), 10, 3 );
		add_filter( 'optionsframework_link_button_field', array( $this, 'link_button_field_option_type' ), 10, 3 );
		add_filter( 'optionsframework_dummy_text', array( $this, 'dummy_text_option_type' ), 10, 3 );
		add_filter( 'of_sanitize_repeat_extra_field', array( $this, 'sanitize_repeat_extra_field' ), 10, 2 );
		add_action( 'optionsframework_custom_scripts', array( $this, 'of_transfers_options_script' ) );
	}
	
	public function register_dynamic_string_for_translation($name, $value) {
	
		if (function_exists('icl_register_string')) {
			icl_register_string('Transfers Theme', $name, $value);
		}
	}
	
	public function get_translated_dynamic_string($name, $value) {
	
		if (function_exists('icl_t')) {
			return icl_t('Transfers Theme', $name, $value);
		}
		return $value;
	}
	
	function repeat_extra_field_option_type( $option_name, $option, $values ){

		global $transfers_plugin_of_default_fields, $repeatable_field_types, $default_destination_extra_fields;
		$max_field_index = -1;
		
		$default_values = array();
		
		if ($option['id'] == 'destination_extra_fields') {
			$default_values = $default_destination_extra_fields;
		}

		if (!is_array( $values ) || count($values) == 0 ) {
			$values = $default_values;
		}

		$output = '<div class="of-repeat-loop">';
		
		$output .= '<ul class="sortable of-repeat-extra-fields">';

		if( is_array( $values ) ) {

			foreach ( (array)$values as $key => $value ){
			
				if (isset($value['label']) && isset($value['type']) && isset($value['id']) && isset($value['index'])) {
					
					$field_label 	= $value['label'];
					$field_type 	= $value['type'];
					$field_id 		= $value['id'];
					$field_index 	= $value['index'];
					$field_hidden 	= isset($value['hide']) && $value['hide'] == '1' ? true : false;
	 
					$output .= '<li class="ui-state-default of-repeat-group">';
					$output .= '	<div class="of-input-wrap">';
					$output .= '		<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-field-id" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$field_index.'][id]' ) . '">' . esc_html__('Field id', 'transfers') . '</label>';
					$output .= '		<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-input input-field-id" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$field_index.'][id]' ) . '" type="text" value="' . esc_attr( $field_id ) . '" readonly="readonly" />';					
					$output .= '	</div>';
					$output .= '	<div class="of-input-wrap">';
					$output .= '		<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$field_index.'][label]' ) . '">' . esc_html__('Field label', 'transfers') . '</label>';
					$output .= '		<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-input input-field-label" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$field_index.'][label]' ) . '" type="text" value="' . esc_attr( $field_label ) . '" />';
					$output .= '	</div>';
					$output .= '	<div class="of-select-wrap">';
					$output .= '		<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-field-type" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$field_index.'][type]' ) . '">' . esc_html__('Field type', 'transfers') . '</label>';
					$output .= '		<select data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-select select-field-type" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$field_index.'][type]' ) . '">';
					
					foreach($repeatable_field_types as $input_type_key => $input_type_text) {
						$output .= '		<option value="' . $input_type_key . '" ' . ($field_type == $input_type_key ? 'selected' : '') . '>' . $input_type_text . '</option>';
					}		
					
					$output .= '		</select>';
					$output .= '	</div>';
					$output .= '	<div class="of-input-wrap of-checkbox-wrap">';
					$output .= '		<label data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$field_index.'][hide]' ) . '">' . esc_html__('Is hidden?', 'transfers') . '</label>';
					$output .= '		<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="of-checkbox checkbox-hide-field" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$field_index.'][hide]' ) . '" type="checkbox" value="1" ' . ($field_hidden ? 'checked' : '') . ' />';
					$output .= '	</div>';
					$output .= '	<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="input-field-index" name="' . esc_attr( $option_name . '[' . $option['id'] . ']['.$field_index.'][index]' ) . '" type="hidden" value="' . $field_index . '" />';
					$output .= '	<span class="ui-icon ui-icon-close"></span>';
					
					$output .= '</li><!--.of-repeat-group-->';
			 
					$max_field_index = $field_index > $max_field_index ? $field_index : $max_field_index;
				}
			}
		}
	 
		$output .= '	<li class="to-copy ui-state-default of-repeat-group">';
		$output .= '		<div class="of-input-wrap">';
		$output .= '			<label class="of-label label-field-id" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . esc_html__('Field id', 'transfers') . '</label>';
		$output .= '			<input class="of-input input-field-id" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="text" placeholder="' . esc_html__('Id will be created when \'Save Options\' is clicked.', 'transfers') . '" readonly="readonly" />';
		$output .= '		</div>';
		$output .= '		<div class="of-input-wrap">';
		$output .= '			<label class="of-label label-field-label" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . esc_html__('Field label', 'transfers') . '</label>';
		$output .= '			<input class="of-input input-field-label" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="text" value="' . esc_attr( $option['std'] ) . '" />';
		$output .= '		</div>';
		$output .= '		<div class="of-select-wrap">';
		$output .= '			<label class="of-label label-field-type" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . esc_html__('Field type', 'transfers') . '</label>';
		$output .= '			<select class="of-select select-field-type" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">';
		foreach($repeatable_field_types as $input_type_key => $input_type_text) {
			$output .= '			<option value="' . $input_type_key . '">' . $input_type_text . '</option>';
		}
		$output .= '			</select>';
		$output .= '		</div>';
		$output .= '		<div class="of-input-wrap of-checkbox-wrap">';
		$output .= '			<label class="of-label label-hide-field" for="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '">' . esc_html__('Is hidden?', 'transfers') . '</label>';
		$output .= '			<input class="of-checkbox checkbox-hide-field" data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" type="checkbox" value="1" />';
		$output .= '		</div>';
		$output .= '		<input data-rel="' . esc_attr( $option_name . '[' . $option['id'] . ']' ) . '" class="input-field-index" type="hidden" value="' . $max_field_index . '" />';
		$output .= '		<span class="ui-icon ui-icon-close"></span>';
		$output .= '	</li><!--.of-repeat-group-->'; 
		$output .= '</ul><!--.sortable-->';
		$output .= '<input type="hidden" class="max_field_index" value="' . $max_field_index . '" />';
		$output .= '<a href="#" class="docopy_field button icon add">' . esc_html__('Add field', 'transfers') . '</a>';

		$output .= '</div><!--.of-repeat-loop-->';

		return $output;

	}

	function link_button_field_option_type ( $option_name, $option, $values) {

		$button_text = $option['name'];
		if (isset($option['text'])) {
			$button_text = $option['text'];
		}
	
		$output = '<div class="of-input">';
		$output .= '	<a href="#" class="button-secondary of-button-field ' . $option['id'] . '">' . $button_text . '</a>';
		$output .= '</div>';

		return $output;
	}
	
	function dummy_text_option_type ( $option_name, $option, $values) {

		$output = '';
		return $output;
	}

	function get_option_id_context($option_id) {

		$option_id_context = '';
		
		if ($option_id == 'destination_extra_fields')
			$option_id_context = 'Destination extra field';
			
		return $option_id_context;
	}
	
	/*
	 * Sanitize Repeat inputs
	 */
	function sanitize_repeat_extra_field( $fields, $option ){

		$results = array();
		if (is_array($fields)) {
			for ($i = 0; $i < count($fields); $i++) { 
				if (isset($fields[$i])) {
				
					$field = $fields[$i];
					
					$field_id = isset($field['id']) ? $field['id'] : '';
					$field_label = isset($field['label']) ? $field['label'] : '';
					$field_index = isset($field['index']) ? $field['index'] : 0;
					
					$field_id = trim($field_id);
					if (empty($field_id) && !empty($field_label)) {
						$field_id = URLify::filter($field_label . '-' . $field_index);
						$field_id = str_replace("-", "_", $field_id);
						$field['id'] = $field_id;
					}
						
					if (isset($field['label']))
						$this->register_dynamic_string_for_translation($this->get_option_id_context($option['id']) . ' ' . $field['label'], $field['label']);
						
					$results[] = $field;
				}
			}
		}

		return $results;
	}
	
	/*
	 * Custom repeating field scripts
	 * Add and Delete buttons
	 */
	function of_transfers_options_script(){	
		global $transfers_plugin_globals;?>
		<style>
			#optionsframework .to-copy {display: none;}
		</style>
		<script type="text/javascript">
		<?php
			echo '	window.adminAjaxUrl = "' . admin_url('admin-ajax.php') . '";';
			echo '  window.adminSiteUrl = "' . admin_url('themes.php?page=options-framework') . '";';
		?>	
		jQuery(function($){
		
			$(".of-repeat-extra-fields").sortable();
			

			$(".create_transfers_tables").on('click', function(e) {
				
				var parentDiv = $(this).parent();
				var loadingDiv = parentDiv.find('.loading');
				loadingDiv.show();
				var _wpnonce = $('#_wpnonce').val();
					
				var dataObj = {
						'action':'transfers_extra_tables_ajax_request',
						'nonce' : _wpnonce }				  

				$.ajax({
					url: window.adminAjaxUrl,
					data: dataObj,
					success:function(json) {
						// This outputs the result of the ajax request
						loadingDiv.hide();
						window.location = window.adminSiteUrl;
					},
					error: function(errorThrown){
						console.log(errorThrown);
					}
				}); 
				
				e.preventDefault();
			});
			
			$(".docopy_field").on("click", function(e){
	 
				// the loop object
				$section = $(this).closest(".section");
				$field_loop = $section.find('.of-repeat-extra-fields');

				// the group to copy
				$to_copy = $field_loop.find('.to-copy');
				$group = $to_copy.clone();
				$group.removeClass('to-copy');
				$group.insertBefore($to_copy);

				var max_field_index = parseInt($section.find('.max_field_index').val()) + 1;
				$section.find('.max_field_index').val(max_field_index);

				prepareCustomField('input', 	'.input-field-label', 	'label', 	$group, max_field_index, '');
				prepareCustomField('input', 	'.input-field-id', 		'id', 		$group, max_field_index, '');
				prepareCustomField('select', 	'.select-field-type', 	'type', 	$group, max_field_index, '');
				prepareCustomField('input', 	'.input-field-index', 	'index', 	$group, max_field_index, '');
				prepareCustomField('input', 	'.checkbox-hide-field', 'hide', 	$group, max_field_index, 'label.label-hide-field');

				$group.find('input.input-field-index').val(max_field_index);
				
				bindIconCloseEvent();

				e.preventDefault();
	 
			});
			
			bindIconCloseEvent();	
			
			function bindIconCloseEvent() {
				/* Bind the X behavior to the original elements*/
				$(".ui-icon-close").click(function() {
					$(this).parent().remove();
					return false;
				});
			}
			
			function prepareCustomField(fieldType, fieldSelector, fieldKey, $groupObj, fieldIndex, labelSelector) {
			
				var $fieldControl = $groupObj.find(fieldType + fieldSelector);
				$fieldControl.attr('name', $fieldControl.attr('data-rel') + '[' + fieldIndex + '][' + fieldKey + ']');
				$fieldControl.attr('id', $fieldControl.attr('data-rel') + '[' + fieldIndex + '][' + fieldKey + ']');
			  
				if (labelSelector.length > 0) {
					var $labelControl = $group.find(labelSelector);
					$labelControl.attr('for', $fieldControl.attr('data-rel') + '[' + fieldIndex + '][' + fieldKey + ']');
				}
			}
			
			bindTabVisibility('services', 'enable_services');
			bindTabVisibility('faqs', 'enable_faqs');
			bindTabVisibility('destinations', 'enable_destinations');
			bindTabVisibility('transporttypes', 'enable_transport_types');
			bindTabVisibility('woocommercesettings', 'use_woocommerce_for_checkout');
			
			function bindTabVisibility(groupClass, checkboxId) {
				toggleTabVisibility($("#" + checkboxId).is(':checked'), groupClass, checkboxId);
				
				$("#" + checkboxId).change(function() {
					toggleTabVisibility(this.checked, groupClass, checkboxId);
				});
			}
			
			function toggleTabVisibility(show, groupClass, checkboxId) {
				if (show){
					$(".group." + groupClass).children().show();
				} else {
					$(".group." + groupClass).children().hide();
					$("#section-" + checkboxId).show();
					$(".group." + groupClass + " > h3").show();
					$("#section-" + checkboxId).children().show();	
				}		
			}
			
		});
		 
		</script>
	<?php
	}
}

// store the instance in a variable to be retrieved later and call init
$transfers_plugin_of_custom = Transfers_Plugin_Of_Custom::get_instance();
$transfers_plugin_of_custom->init();