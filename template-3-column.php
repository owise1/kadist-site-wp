<?php
/**
 * Template Name: 3 Column 
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  <div class="columns">
    <div class="col1">
      <?= get_field('col1') ?>
    </div>
    <div class="col2">
      <?= get_field('col2') ?>
    </div>
    <div class="col3">
      <?= get_field('col3') ?>
    </div>
  </div>
<?php endwhile; ?>
