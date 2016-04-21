<?php
/**
 * Template Name: Import Template
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  <pre>
<?php

$run = false;
$images = false;
$howMany = 10;

// functions
function dateFormat($d, $fmt='Ymd') {
  return date($fmt, strtotime($d));
}
function _import_photo( $postid, $url ) {
	$post = get_post( $postid );
	if( empty( $post ) )
		return false;

	if( !class_exists( 'WP_Http' ) )
	  include_once( ABSPATH . WPINC. '/class-http.php' );

	$photo = new WP_Http();
	$photo = $photo->request( $url );
	if( $photo['response']['code'] != 200 )
		return false;

	$attachment = wp_upload_bits( rand() . '.jpg', null, $photo['body'], date("Y-m", strtotime( $photo['headers']['last-modified'] ) ) );
	if( !empty( $attachment['error'] ) )
		return false;

	$filetype = wp_check_filetype( basename( $attachment['file'] ), null );

	$postinfo = array(
		'post_mime_type'	=> $filetype['type'],
		'post_title'		=> $post->post_title,
		'post_content'		=> '',
		'post_status'		=> 'inherit',
	);
	$filename = $attachment['file'];
	$attach_id = wp_insert_attachment( $postinfo, $filename, $postid );

	if( !function_exists( 'wp_generate_attachment_data' ) )
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
	wp_update_attachment_metadata( $attach_id,  $attach_data );
	return $attach_id;
}

$locations = array('1' => 'paris', '2' => 'san-francisco', '2079' => 'offsite');
// get posts
$posts = json_decode(file_get_contents(__DIR__ . "/import/kadist.json"));
$programs = array_values(array_filter($posts, function ($node) {
  return($node->type == 'program');
}));
$categories = array('events', 'publications', 'exhibitions', 'videos', 'residencies');
$programs = array_slice($programs, 0, $howMany);

foreach($programs as $thing){

  $post = array();
  $post['post_title'] = $thing->title;
  $body = $thing->body->und[0]->value;
  if (!$body) $body = $thing->body->en[0]->value;
  if (!$body) $body = $thing->body->fr[0]->value;
  $post['post_content'] = $body;
  $post['post_type'] = 'program';
  $post['post_status'] = 'publish';

  // category
  $cat = array_values((array) $thing->field_todeparent->und[0]->value);
  $cat = array_pop($cat);
  $cat = strtolower($cat->name);
  $allCats[] = $cat;

  if ($run) {
    $id = wp_insert_post($post);
    if (!$id) {
      var_dump($post);
      var_dump($thing);
    }
    $endDateKey = 'field_5717f05150346';
    $startDateKey = 'field_5717d9c704fc9';
    update_field($startDateKey, dateFormat($thing->field_program_date->und[0]->value), $id);
    update_field($endDateKey, dateFormat($thing->field_program_date->und[0]->value2), $id);
    update_field('field_57175553a8826', $thing->field_tode_program->und[0]->tid, $id);

    // french
    if ($thing->body->fr) {
      update_field('field_57194992841e2', $thing->body->fr[0]->value, $id);
    }

    // location
    $locationID = $thing->field_location->und[0]->tid;
    if (array_key_exists($locationID, $locations)){
      wp_set_object_terms($id, $locations[$locationID], 'location');
    } else {
      // offsite
      wp_set_object_terms($id, $locations[2079], 'location');
    }

    // categories
    if (in_array($cat , $categories)) {
      wp_set_object_terms($id, $cat, 'category');
    // TODO: it's tied to something else
    } else {
    }

    // images
    if ($images) {
      $first = true;
      foreach ($thing->field_images->und as $imgInfo) {
        $imgID = _import_photo($id, $imgInfo->node_export_file_url);
        if ($first) {
          add_post_meta($id, '_thumbnail_id', $imgID);
          $first = false;
        }
      }
    }
  }
}


?>
  </pre>
<?php endwhile; ?>
