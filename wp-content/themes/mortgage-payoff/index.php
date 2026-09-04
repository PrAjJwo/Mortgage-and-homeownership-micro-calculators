<?php
/**
 * Main Template File (Fallback)
 */

get_header(); ?>

<main id="primary" class="site-main">
  <?php
  if ( have_posts() ) :
    while ( have_posts() ) :
      the_post();
      get_template_part( 'front-page' );
    endwhile;
  else :
    get_template_part( 'front-page' );
  endif;
  ?>
</main>

<?php get_footer(); ?>
