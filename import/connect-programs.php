<?php
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
