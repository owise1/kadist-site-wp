<?php

function getWpPosts($type, $oid){
  if (!$oid) return array();
  return get_posts(array(
    'numberposts' => -1,
    'post_type' => $type,
    'meta_key' => 'oid',
    'meta_value' => $oid
  ));
}

function dateFormat($d, $fmt='Ymd') {
  if ($d) return date($fmt, strtotime($d));
}

function _import_photo( $postid, $url, $fileName ) {
	$post = get_post( $postid );
	if( empty( $post ) )
		return false;

	if( !class_exists( 'WP_Http' ) )
	  include_once( ABSPATH . WPINC. '/class-http.php' );

	$photo = new WP_Http();
	$photo = $photo->request( $url );
	if( $photo['response']['code'] != 200 )
		return false;

	$attachment = wp_upload_bits( $fileName, null, $photo['body'], date("Y-m", strtotime( $photo['headers']['last-modified'] ) ) );
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

function createPostCommon($thing){
  global $images;

  $post = array();
  $post['post_title'] = $thing->title ? $thing->title : 'untitled';
  $body = $thing->body->und[0]->value;
  if (!$body) $body = $thing->body->en[0]->value;
  if (!$body) $body = $thing->body->fr[0]->value;
  if (!$body) $body = 'nbsp;';
  $post['post_content'] = $body;
  $post['post_status'] = 'publish';
  return $post;

}

function fetchImages ($id, $thing, $images, $field='field_images') {
  if ($images) {
    $first = true;
    foreach ($thing->$field->und as $imgInfo) {
      $imgID = _import_photo($id, $imgInfo->node_export_file_url, $imgInfo->origname);
      if ($first && $field == 'field_images') {
        add_post_meta($id, '_thumbnail_id', $imgID);
        $first = false;
      }
    }
  }
}


?>
