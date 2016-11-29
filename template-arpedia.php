<?php
/**
 * Template Name: Arpedia 
   Otobong Nkanga - Tsumeb Fragments
   The info on this page is on the image. No work for this thing
// http://kadist.org/en/work-218
// http://dev.kadist.org/post.php?post=4316&action=edit

   Public Movement - Falling Wall
   http://kadist.org/en/work-217
 */

query_posts(array(
  'post_type' => 'people',
  'posts_per_page' => -1
));
$tags = json_decode(file_get_contents(get_template_directory() . '/import/corpus_2.json'));
function formatResponse($tag){
  $ret = array();
  foreach($tag->word_tags as $i => $word){
    $item = new stdClass;
    $item->word = $word;
    $item->synset = $tag->wordnet_tags[$i];
    $ret[] = $item;
  }
  return $ret;
}
function titleForArtist($artist){
  global $tags;
  foreach($tags as $tag){
    if ($tag->artist_name == $artist) {
      return ($tag->title);
    } 
  }
}
function tagsForArtist($artist){
  global $tags;
  foreach($tags as $tag){
    if ($tag->artist_name == $artist) {
      return formatResponse($tag);
    } 
  }
}
function getFirst($artist){
  $pieces = explode(' ', $artist);
  return strtolower($pieces[0]);
}
function tagsForArtistFirst($artist){
  global $tags;
  $ret = array();
  $a = getFirst($artist);
  foreach($tags as $tag){
    $ta = getFirst($tag->artist_name);
    if ($ta == $a) {
      $ret[] = formatResponse($tag);
    } 
  }
  return $ret;
}
function showTags($artist, $tags){
    if ($tags) {
      echo $artist . "<br/>";
      var_dump($tags);
      echo "<br/>";
    } else {
      /* echo 'no results for: ' . $artist . '<br/>';
       */
    }
}
?>

<?php while (have_posts()) : the_post();
  $artist = get_the_title();

  $works = array();
  $connected = new WP_Query( array(
    'connected_type' => 'works_to_people',
    'connected_items' => $post,
    'nopaging' => true,
  ) );
  if ( $connected->have_posts() ) {
    while ( $connected->have_posts() ) {
      $connected->the_post();
      $works[] = $post;
    }
    wp_reset_postdata();
    if (count($works) == 1){
      $artistTags = tagsForArtist($artist);
      if ($artistTags) {
        $work = $works[0];

        // word tags
        /* update_post_meta($work->ID, 'wordnet_tagging_tag_list', json_encode($artistTags));*/

        // update title
        if($work->post_title == 'untitled'){
          /* wp_update_post(array(
           *   'ID' => $work->ID,
           *   'post_title' => titleForArtist($artist) 
           * ));*/
        }

        $count++;
      }
      /* showTags($artist, $artistTags);*/
      /* $artistTags = tagsForArtistFirst($artist);
       * showTags(getFirst($artist), $artistTags);*/
    }
  }
?>
<?php endwhile; ?>
<?= $count ?>
