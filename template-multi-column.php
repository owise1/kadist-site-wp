<?php
/**
 * Template Name: Multi Column 
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  <div class="columns">
    <?php foreach(get_field('columns') as $col) : ?>
      <div class="col col-<?= ++$i ?>">
        <?= $col['col'] ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endwhile; ?>
