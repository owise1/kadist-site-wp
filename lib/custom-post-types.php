<?php
namespace Kadist;

add_action( 'init', __NAMESPACE__ . '\\create_post_type' );
function create_post_type() {
	register_taxonomy(
		'location',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'program',   		 //post type name
		array(
		  'hierarchical' 		=> true,
		  'label' 			=> 'Location',  //Display name
		  'query_var' 		=> true,
		  'rewrite'			=> array(
		    'slug' 			=> 'location', // This controls the base slug that will display before each term
		    'with_front' 	=> false // Don't display the category base before
		  )
		)
	);
	register_post_type( 'program',
		array(
			'labels' => array(
				'name' => __( 'Programs' ),
				'singular_name' => __( 'Program' )
			),
    'taxonomies' => array('category', 'location'),
		'public' => true,
		'has_archive' => true,
		'menu_icon' => get_template_directory_uri() .'/assets/images/program.png',
		'menu_position' => 2,
		'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail'),
		'rewrite' => array('has_archive' => true,'slug' => 'program')
		)
	);
	register_taxonomy(
		'medium',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'work',   		 //post type name
		array(
		  'label' 			=> 'Medium',  //Display name
		  'query_var' 		=> true,
		  'rewrite'			=> array(
		    'slug' 			=> 'medium', // This controls the base slug that will display before each term
		    'with_front' 	=> false // Don't display the category base before
		  )
		)
	);
	register_taxonomy(
		'collections',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'work',   		 //post type name
		array(
		  'hierarchical' 		=> true,
		  'label' 			=> 'Collection',  //Display name
		  'query_var' 		=> true,
		  'rewrite'			=> array(
		    'slug' 			=> 'collection', // This controls the base slug that will display before each term
		    'with_front' 	=> false // Don't display the category base before
		  )
		)
	);
	register_post_type( 'work',
		array(
			'labels' => array(
				'name' => __( 'Works' ),
				'singular_name' => __( 'Work' )
			),
		'public' => true,
		'has_archive' => true,
		'taxonomies' => array('collections'),
		'menu_icon' => get_template_directory_uri() .'/assets/images/artwork.png',
		'menu_position' => 3,
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
		'rewrite' => array('has_archive' => true,'slug' => 'work')
		)
	);
	register_post_type( 'people',
		array(
			'labels' => array(
				'name' => __( 'People' ),
				'singular_name' => __( 'Person' )
			),
		'public' => true,
		'has_archive' => true,
		'taxonomies' => array('groups'),
		'menu_icon' => get_template_directory_uri() .'/assets/images/people.png',
		'menu_position' => 4,
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
		'rewrite' => array('has_archive' => true,'slug' => 'people')
		)
	);
}
// advanced custom fields
include("advanced-custom-fields/acf.php");
include("acf-repeater/acf-repeater.php");
define( 'ACF_LITE', true );

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_people-fields',
		'title' => 'People Fields',
		'fields' => array (
			array (
				'key' => 'field_5717f2c3a8825',
				'label' => 'First Name',
				'name' => 'first_name',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5717f2d3a8826',
				'label' => 'Last Name',
				'name' => 'last_name',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5717f2f5a8828',
				'label' => 'Birth Date',
				'name' => 'birth_date',
				'type' => 'date_picker',
				'date_format' => 'yymmdd',
				'display_format' => 'mm/dd/yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_5717f59e3a19d',
				'label' => 'Links',
				'name' => 'links',
				'type' => 'repeater',
				'sub_fields' => array (
					array (
						'key' => 'field_5717f5a93a19e',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_5717f5bc3a19f',
						'label' => 'URL',
						'name' => 'url',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => 0,
				'row_limit' => '',
				'layout' => 'row',
				'button_label' => 'Add Link',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'people',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_program-fields',
		'title' => 'Program Fields',
		'fields' => array (
      array (
				'key' => 'field_57194992841e2',
				'label' => 'French Translation',
				'name' => 'fr',
				'type' => 'wysiwyg',
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
			),
			array (
				'key' => 'field_5717d9c704fc9',
				'label' => 'Start Date',
				'name' => 'start_date',
				'type' => 'date_picker',
				'date_format' => 'yymmdd',
				'display_format' => 'mm/dd/yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_5717f05150346',
				'label' => 'End Date',
				'name' => 'end_date',
				'type' => 'date_picker',
				'date_format' => 'yymmdd',
				'display_format' => 'mm/dd/yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_57175553a8826',
				'label' => 'Original ID',
				'name' => 'oid',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5717f59e3jws9d',
				'label' => 'Links',
				'name' => 'links',
				'type' => 'repeater',
				'sub_fields' => array (
					array (
						'key' => 'field_571wp4j93a19e',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_571bejh4c3a19f',
						'label' => 'URL',
						'name' => 'url',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => 0,
				'row_limit' => '',
				'layout' => 'row',
				'button_label' => 'Add Link',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'program',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_work-fields',
		'title' => 'Work Fields',
		'fields' => array (
			array (
				'key' => 'field_5717f2064a20e',
				'label' => 'Weight',
				'name' => 'weight',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5717f20f4a20f',
				'label' => 'New Acquisition',
				'name' => 'new_acquisition',
				'type' => 'true_false',
				'message' => '',
				'default_value' => 0,
			),
			array (
				'key' => 'field_5717f2274a210',
				'label' => 'Production',
				'name' => 'production',
				'type' => 'select',
				'choices' => array (
					'N/A' => 'N/A',
					'Produced by Kadist' => 'Produced by Kadist',
					'Co-produced by Kadist' => 'Co-produced by Kadist',
				),
				'default_value' => '',
				'allow_null' => 1,
				'multiple' => 0,
			),
			array (
				'key' => 'field_5717f25d4a211',
				'label' => 'Work Date',
				'name' => 'work_date',
				'type' => 'date_picker',
				'date_format' => 'yymmdd',
				'display_format' => 'mm/dd/yy',
				'first_day' => 1,
			),
			array (
				'key' => 'field_5717f29f4a212',
				'label' => 'Size',
				'name' => 'size',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_ntjrk59e3jws9d',
				'label' => 'Links',
				'name' => 'links',
				'type' => 'repeater',
				'sub_fields' => array (
					array (
						'key' => 'field_571sdsj93a19e',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
					array (
						'key' => 'field_571bejleqpa19f',
						'label' => 'URL',
						'name' => 'url',
						'type' => 'text',
						'column_width' => '',
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'html',
						'maxlength' => '',
					),
				),
				'row_min' => 0,
				'row_limit' => '',
				'layout' => 'row',
				'button_label' => 'Add Link',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'work',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}

// connections
function my_connection_types() {
    p2p_register_connection_type( array(
        'name' => 'works_to_people',
        'from' => 'work',
        'to' => 'people'
    ) );
    p2p_register_connection_type( array(
        'name' => 'programs_to_programs',
        'from' => 'program',
        'to' => 'program',
        'reciprocal' => true
    ) );
    p2p_register_connection_type( array(
        'name' => 'programs_to_works',
        'from' => 'program',
        'to' => 'work'
    ) );
    p2p_register_connection_type( array(
        'name' => 'programs_to_people',
        'from' => 'program',
        'to' => 'people'
    ) );
}
add_action( 'p2p_init', __NAMESPACE__ .'\\my_connection_types' );

?>
