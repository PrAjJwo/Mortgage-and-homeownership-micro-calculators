<?php
/**
 * Template Name: Insurance Deductible Savings Calculator
 */
get_header(); ?>

<main id="primary" class="site-main" role="main">
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">Insurance Deductible Savings Calculator</h1>
      <p class="hero-subtitle">
        Find your break-even claim horizon to see if increasing your homeowner insurance deductible saves more in annual premium discounts than your out-of-pocket risk gap.
      </p>
    </div>
  </section>

  <?php get_template_part( 'template-parts/calculators/calc-deductible-savings' ); ?>
  <?php get_template_part( 'template-parts/calculators/tools-directory-grid' ); ?>
</main>

<?php get_footer(); ?>
