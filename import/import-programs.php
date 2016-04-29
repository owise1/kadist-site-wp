<?php

// programs
$programs = array_values(array_filter($posts, function ($node) {
  return($node->type == 'program');
}));
$categories = array('events', 'publications', 'exhibitions', 'videos', 'residencies');
$programs = array_slice($programs, 0, $howMany);
foreach($programs as $thing){

  $post = createPostCommon($thing);
  $post['post_type'] = 'program';

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

    // video links
    if ($thing->field_videos->und) {
      $vals = array();
      foreach($thing->field_videos->und as $link){
        $vals[] = array('url' => $link->video_url);
      }
      update_field('field_5717f59e3jws9d', $vals, $id);
    }

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
    fetchImages($id, $thing, $images);
  }
}
?>
