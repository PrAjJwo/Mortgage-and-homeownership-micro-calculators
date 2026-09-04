<?php
/**
 * Template Name: House-Flipping Profit Calculator
 */
get_header(); ?>

<main id="primary" class="site-main" role="main">
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">House-Flipping Profit Calculator</h1>
      <p class="hero-subtitle">
        Underwrite fix-and-flip investment deals with precision: calculate net profit, cash-on-cash ROI, annualized returns, hard money loan interest, and the conservative 70% Rule Maximum Allowable Offer (MAO).
      </p>
    </div>
  </section>

  <?php get_template_part( 'template-parts/calculators/calc-house-flip' ); ?>
  <?php get_template_part( 'template-parts/calculators/tools-directory-grid' ); ?>
</main>

<?php get_footer(); ?>
