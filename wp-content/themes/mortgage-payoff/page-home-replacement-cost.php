<?php
/**
 * Template Name: Home Replacement-Cost Calculator
 */
get_header(); ?>

<main id="primary" class="site-main" role="main">
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">Home Replacement-Cost Calculator</h1>
      <p class="hero-subtitle">
        Estimate your homeowner insurance dwelling reconstruction limit (Coverage A) based on finished square footage, architectural grade, foundation type, and debris removal buffers.
      </p>
    </div>
  </section>

  <?php get_template_part( 'template-parts/calculators/calc-replacement-cost' ); ?>
  <?php get_template_part( 'template-parts/calculators/tools-directory-grid' ); ?>
</main>

<?php get_footer(); ?>
