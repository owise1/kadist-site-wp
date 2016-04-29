<?php
/**
 * Template Name: Import Template
 */

include("import/functions.php");

$run = false;
$howMany = 1000;

$initImport = false;
$connections = false;


// get posts
$posts = json_decode(file_get_contents(__DIR__ . "/import/kadist.json"));

$importI = get_query_var('importI');
if ($importI){
  $i = $importI - 1;
  $post = $posts[$i];
  if ($post){
    $postTypes = array('people', 'program', 'work');
    if(in_array($post->type, $postTypes)){
      $field = 'field_tode_' . $post->type;
      $oid = $post->$field->und[0]->tid;

      // images
      foreach(getWpPosts($post->type, $oid) as $p){
        fetchImages($p->ID, $post, true, $post->type == 'people' ? 'field_attachments' : 'field_images');
      }

      echo "imported " . $importI . " " . $post->type . " : " . $oid;

    } else {
      echo "skipped " . $importI;
    }
    echo "<h4><a href='/kadist/import-page'>STOP</a></h4>";
    echo "<script>window.location.href='?importI=".($importI+1)."'</script>";
  } else {
    echo "Imported " . $importI . " posts";
  }
} else {
  echo "<form ><input type='submit' value='Start Asset Import' /><input type='hidden' name='importI' value='1'></form>";
}
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  <pre>
<?php



if ($initImport) {
  include(__DIR__ . "/import/import-works.php");
  include(__DIR__ . "/import/import-people.php");
  include(__DIR__ . "/import/import-programs.php");
}

if ($connections){
  include(__DIR__ . "/import/connect-works.php");
  include(__DIR__ . "/import/connect-programs.php");
}




?>
  </pre>
<?php endwhile; ?>
