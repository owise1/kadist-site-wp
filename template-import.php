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

include("import/functions.php");

$run = true;
$images = false;
$howMany = 2;

$locations = array('1' => 'paris', '2' => 'san-francisco', '2079' => 'offsite');
// get posts
$posts = json_decode(file_get_contents(__DIR__ . "/import/kadist.json"));

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
    // collection
    if ($thing->field_collection->und[0]->tid) {
      wp_set_object_terms($id, $collections[$thing->field_collection->und[0]->tid], 'collections');
    }

    // images
    fetchImages($id, $thing, $images);
  }

}
return;

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
  </pre>
<?php endwhile; ?>
