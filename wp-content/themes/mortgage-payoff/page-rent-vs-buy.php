<?php
/**
 * Template Name: Rent Versus Buy Calculator
 */
get_header(); ?>

<main id="primary" class="site-main" role="main">
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">Rent vs. Buy Calculator with Closing Costs</h1>
      <p class="hero-subtitle">
        Evaluate the 30-year wealth trajectories of homeownership vs. renting and investing in stock market index funds, factoring in upfront buyer closing costs and property maintenance.
      </p>
    </div>
  </section>

  <?php get_template_part( 'template-parts/calculators/calc-rent-vs-buy' ); ?>
  <?php get_template_part( 'template-parts/calculators/tools-directory-grid' ); ?>
</main>

<?php get_footer(); ?>
