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
  'post_type' => 'work'
));
?>

<?php while (have_posts()) : the_post();
  $title = get_the_title();
echo get_the_id() . " ";

  $connected = new WP_Query( array(
    'connected_type' => 'works_to_people',
    'connected_items' => $post,
    'nopaging' => true,
  ) );
  if ( $connected->have_posts() ) {
    while ( $connected->have_posts() ) {
      $connected->the_post();
      $artist = get_the_title();
     echo $title . $artist . "<br/>";
    }
    wp_reset_postdata();

  }




?>
<?php endwhile; ?>
