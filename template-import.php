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

$run = false;
$howMany = 10;

$images = false;
$initImport = false;

$locations = array('1' => 'paris', '2' => 'san-francisco', '2079' => 'offsite');
// get posts
$posts = json_decode(file_get_contents(__DIR__ . "/import/kadist.json"));


if ($initImport) {
  include(__DIR__ . "/import/import-works.php");
  include(__DIR__ . "/import/import-people.php");
  include(__DIR__ . "/import/import-programs.php");
}


/* include(__DIR__ . "/import/connect-works.php");
 */

$programs = array_values(array_filter($posts, function ($node) {
  return($node->type == 'program');
}));
$programs = array_slice($programs, 0, $howMany);

foreach($programs as $thing){

  // programs to people
  if ($thing->field_people->und) {
    foreach($thing->field_people->und as $person){
      $personID = $person->tid;
      if ($run) {
        foreach(getWpPosts('program', $thing->field_tode_program->und[0]->tid) as $prog1){
          foreach(getWpPosts('people', $personID) as $p){
            p2p_type('programs_to_people')->connect($prog1->ID, $p->ID);
          }
        }
      }
    }
  }
  // programs to works
  if ($thing->field_works->und) {
    foreach($thing->field_works->und as $work){
      $workID = $work->tid;
      if ($run) {
        foreach(getWpPosts('program', $thing->field_tode_program->und[0]->tid) as $prog1){
          foreach(getWpPosts('work', $workID) as $w){
            p2p_type('programs_to_works')->connect($prog1->ID, $w->ID);
          }
        }
      }
    }
  }
  // programs_to_programs
  if ($thing->field_related_programs->und) {
    foreach($thing->field_related_programs->und as $p){
      $programID = $p->tid;
      if ($run) {
        foreach(getWpPosts('program', $thing->field_tode_program->und[0]->tid) as $prog1){
          foreach(getWpPosts('program', $programID) as $prog2){
            p2p_type('programs_to_programs')->connect($prog1->ID, $prog2->ID);
          }
        }
      }
    }
  }
}

?>
  </pre>
<?php endwhile; ?>
