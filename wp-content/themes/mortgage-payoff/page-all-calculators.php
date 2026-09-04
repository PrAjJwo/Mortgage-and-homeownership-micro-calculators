<?php
/**
 * Template Name: All Calculators Directory
 */
get_header(); ?>

<main id="primary" class="site-main" role="main">
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">Real Estate & Mortgage Calculators Suite</h1>
      <p class="hero-subtitle">
        Access all 10 independent mathematical calculators designed to help homeowners, buyers, and investors make smarter property decisions.
      </p>
    </div>
  </section>

  <?php get_template_part( 'template-parts/calculators/tools-directory-grid' ); ?>
</main>

<?php get_footer(); ?>
