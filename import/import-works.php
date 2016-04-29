<?php

// works
$works = array_values(array_filter($posts, function ($node) {
  return($node->type == 'work');
}));
$works = array_slice($works, 0, $howMany);
$productions = array('N/A', 'Produced by Kadist', 'Co-produced by Kadist');
$media = array(
  '1230' => 'c-print',
  '1808' => 'oil-on-canvas',
  '2173' => 'plastic-bags-and-paint',
  '2152' => 'framed-with-uv-glass-and-acid-free-board',
  '2151' => 'charcoal-and-pastel-on-paper',
  '2148' => 'photgraphic-installation',
  '2063' => 'base',
  '2062' => 'lucite-blocks',
  '2061' => 'oil-paint',
  '2060' => '23-sheets-of-glass',
  '1881' => 'film-video',
  '1235' => 'mixed-media',
  '1949' => 'video-installation',
  '2008' => 'text',
  '1226' => 'collage',
  '1228' => 'sculpture',
  '1239' => 'performance',
  '1231' => 'painting',
  '1238' => 'print',
  '1552' => 'photography',
  '1234' => 'photograph',
  '1237' => 'drawing',
  '1229' => 'installation',
  '1236' => 'film',
  '1236' => 'film',
  '1224' => 'video',
);
$collections = array(
  '3' => 'paris',
  '5' => 'a3',
  '7' => 'video-americas',
  '4' => '101',
  '6' => 'el-sur',
);

foreach($works as $thing){
  $post = createPostCommon($thing);
  $post['post_type'] = 'work';

  if (getWpPosts('work', $thing->field_tode_work->und[0]->tid)) continue;

  if ($run) {
    $id = wp_insert_post($post);
    // weight
    update_field('field_5717f2064a20e', $thing->field_weight->und[0]->value, $id);
    // size
    update_field('field_5717f29f4a212', $thing->field_size->und[0]->value, $id);
    // new acqusition?
    $newAc = $thing->field_new_acquisition && $thing->field_new_acquisition->und[0]->value;
    update_field('field_5717f20f4a20f', $newAc, $id);
    // produced by
    $productionI = 0;
    if ($thing->field_production) $productionI = $thing->field_production->und[0]->value;
    update_field('field_5717f2274a210', $productions[$productionI], $id);
    // work date
    update_field('field_5717f25d4a211', dateFormat($thing->field_workdate->und[0]->value), $id);
    // medium
    if ($thing->field_medium->und[0]->tid){
      $mediumSlug = $media[ $thing->field_medium->und[0]->tid ];
      wp_set_object_terms($id, $mediumSlug, 'medium');
    }
    update_field('field_zzxz75553a8826', $thing->field_tode_work->und[0]->tid, $id); // original id
    // collection
    if ($thing->field_collection->und[0]->tid) {
      wp_set_object_terms($id, $collections[$thing->field_collection->und[0]->tid], 'collections');
    }
    // video urls
    if ($thing->field_videos->und) {
      $vals = array();
      foreach($thing->field_videos->und as $link){
        $vals[] = array('url' => $link->video_url);
      }
      update_field('field_ntjrk59e3jws9d', $vals, $id);
    }

  }

}

?>
