<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

add_action( 'init', __NAMESPACE__ . '\\create_post_type' );
function create_post_type() {
	register_post_type( 'funding',
		array(
			'labels' => array(
				'name' => __( 'Programs' ),
				'singular_name' => __( 'Program' )
			),
		'public' => true,
		'has_archive' => true,
		'menu_icon' => get_template_directory_uri() .'/assets/images/program.png',
		'menu_position' => 5,
		'supports' => array('title', 'editor', 'excerpt'),
		'rewrite' => array('has_archive' => true,'slug' => 'program')
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
		'menu_position' => 5,
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
		'menu_position' => 5,
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
		'rewrite' => array('has_archive' => true,'slug' => 'people')
		)
	);
}
