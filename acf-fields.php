<?php

if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_61b106e7870d5',
		'title' => 'Admissions Visits',
		'fields' => array(
			array(
				'key' => 'field_61b106f5a9f95',
				'label' => 'Visit Date',
				'name' => 'mu_visits_date',
				'type' => 'date_picker',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'display_format' => 'm/d/Y',
				'return_format' => 'Ymd',
				'first_day' => 0,
			),
			array(
				'key' => 'field_61b1070b02676',
				'label' => 'Visit Start Time',
				'name' => 'mu_visits_start_time',
				'type' => 'time_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'display_format' => 'g:i a',
				'return_format' => 'H:i:s',
			),
			array(
				'key' => 'field_61b1071df1823',
				'label' => 'Visit End Time',
				'name' => 'mu_visits_end_time',
				'type' => 'time_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'display_format' => 'g:i a',
				'return_format' => 'H:i:s',
			),
			array(
				'key' => 'field_61b10746883df',
				'label' => 'Visit Type',
				'name' => 'mu_visits_type',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'Virtual' => 'Virtual',
					'In-Person' => 'In-Person',
				),
				'default_value' => false,
				'allow_null' => 1,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'visit',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));

	endif;
