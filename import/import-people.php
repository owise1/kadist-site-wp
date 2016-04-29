<?php

// people
$people = array_values(array_filter($posts, function ($node) {
  return($node->type == 'people');
}));
$people = array_slice($people, 0, $howMany);


foreach($people as $thing){
  $post = createPostCommon($thing);
  $post['post_type'] = 'people';

  if (getWpPosts('people', $thing->field_tode_people->und[0]->tid)) continue;

  if ($run) {
    $id = wp_insert_post($post);
    update_field('field_5717f2c3a8825',$thing->field_firstname->und[0]->value, $id);
    update_field('field_5717f2d3a8826', $thing->field_lastname->und[0]->value, $id);
    update_field('field_zzzz75553a8826', $thing->field_tode_people->und[0]->tid, $id); // original id
    update_field('field_5717f2f5a8828', dateFormat($thing->field_birthdate->und[0]->value), $id);

    //external links
    if ($thing->field_external_link->und) {
      $vals = array();
      foreach($thing->field_external_link->und as $link){
        $vals[] = array('url' => $link->url, 'title' => $link->title);
      }
      update_field('field_5717f59e3a19d', $vals, $id);
    }

  }
}

?>
