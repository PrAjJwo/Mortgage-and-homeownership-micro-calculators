<?php
/**
 * Template Name: Mortgage Recast Calculator
 */
get_header(); ?>

<main id="primary" class="site-main" role="main">
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">Mortgage Recast Calculator</h1>
      <p class="hero-subtitle">
        Lower your required monthly mortgage payment by making a lump-sum principal reduction without resetting your loan term or paying costly refinance closing fees.
      </p>
    </div>
  </section>

  <?php get_template_part( 'template-parts/calculators/calc-mortgage-recast' ); ?>
  <?php get_template_part( 'template-parts/calculators/tools-directory-grid' ); ?>
</main>

<?php get_footer(); ?>
