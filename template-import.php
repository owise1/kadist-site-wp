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

// functions
function dateFormat($d, $fmt='Ymd') {
  return date($fmt, strtotime($d));
}
// get posts
$posts = json_decode(file_get_contents(__DIR__ . "/import/kadist.json"));
$programs = array_values(array_filter($posts, function ($node) {
  return($node->type == 'program');
}));
var_dump($programs[0]);
$thing = $programs[0];
$post = array();
$post['post_title'] = $thing->title;
$post['post_content'] = $thing->body->und[0]->value;
$post['post_type'] = 'program';
$post['post_status'] = 'publish';
/* $post['guid'] = $thing->field_tode_program->und[0]->tid; */
$endDateKey = 'field_5717f05150346';
$startDateKey = 'field_5717d9c704fc9';
var_dump($post);
if ($run) {
  $id = wp_insert_post($post);
  update_field($startDateKey, dateFormat($thing->field_program_date->und[0]->value), $id);
  update_field($endDateKey, dateFormat($thing->field_program_date->und[0]->value2), $id);
}

?>
  </pre>
<?php endwhile; ?>
