<?php
/**
 * Template Name: Seller Net-Proceeds Calculator
 */
get_header(); ?>

<main id="primary" class="site-main" role="main">
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">Seller Net-Proceeds Calculator</h1>
      <p class="hero-subtitle">
        Calculate your exact net cash proceeds at the closing table after paying off existing mortgages, real estate agent commissions, title escrow fees, transfer taxes, and staging repairs.
      </p>
    </div>
  </section>

  <?php get_template_part( 'template-parts/calculators/calc-seller-proceeds' ); ?>
  <?php get_template_part( 'template-parts/calculators/tools-directory-grid' ); ?>
</main>

<?php get_footer(); ?>
