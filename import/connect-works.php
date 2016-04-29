<?php

$works = array_values(array_filter($posts, function ($node) {
  return($node->type == 'work');
}));
$works = array_slice($works, 0, $howMany);

foreach($works as $thing){
  $peopleID = $thing->field_people->und[0]->tid;
  $workID = $thing->field_tode_work->und[0]->tid;

  if ($run) {
    // works to people
    foreach(getWpPosts('people', $peopleID) as $peep){
      foreach(getWpPosts('work', $workID) as $w){
        p2p_type('works_to_people')->connect($w->ID, $peep->ID);
      }
    }
  }
}

?>
