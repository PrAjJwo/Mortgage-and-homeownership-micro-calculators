<?php
/**
 * Template Name: HELOC Interest-Only Payment Calculator
 */
get_header(); ?>

<main id="primary" class="site-main" role="main">
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">HELOC Interest-Only Payment Calculator</h1>
      <p class="hero-subtitle">
        Calculate low interest-only payments during your Home Equity Line of Credit draw period and prepare for future payment shock when full principal amortization begins.
      </p>
    </div>
  </section>

  <?php get_template_part( 'template-parts/calculators/calc-heloc' ); ?>
  <?php get_template_part( 'template-parts/calculators/tools-directory-grid' ); ?>
</main>

<?php get_footer(); ?>
