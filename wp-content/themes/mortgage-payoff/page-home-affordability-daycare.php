<?php
/**
 * Template Name: Home Affordability Including Daycare Costs
 * Description: Dedicated standalone page template for Home Affordability with Daycare Costs.
 */

get_header(); ?>

<main id="primary" class="site-main" role="main">

  <!-- Keyword Targeted Hero Section -->
  <section class="hero-section">
    <div class="container">
      <h1 class="hero-title">Home Affordability Calculator Including Daycare Costs</h1>
      <p class="hero-subtitle">
        Calculate your true home buying power factoring in childcare, nursery tuition, and preschool expenses. Avoid the bank DTI trap with realistic family cash flow modeling.
      </p>
    </div>
  </section>

  <!-- Interactive Daycare Affordability Calculator -->
  <?php get_template_part( 'template-parts/calculator-daycare' ); ?>

  <!-- Educational Section -->
  <section class="seo-guide-section" style="padding-top: 40px;">
    <div class="container">
      <div class="guide-header">
        <span class="guide-badge">Family Real Estate Guide</span>
        <h2 class="guide-title">Why Daycare Costs Break Standard Mortgage Pre-Approvals</h2>
        <p class="guide-lead">
          Lenders evaluate mortgage qualification using gross income and debt-to-income (DTI) caps. Because childcare is technically a "living expense" and not reported on credit bureaus, banks will happily approve a mortgage payment that leaves parents unable to pay for preschool.
        </p>
      </div>

      <div class="steps-grid">
        <div class="step-card">
          <div class="step-icon-wrap">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
          </div>
          <h3>1. The Invisible DTI Liability</h3>
          <p>A $2,000 monthly daycare payment is equivalent to a $350,000 mortgage loan payment. Ignoring this massive outflow leads families to purchase homes that strain their monthly cash flow.</p>
        </div>

        <div class="step-card">
          <div class="step-icon-wrap">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <h3>2. Net Pay vs. Gross Calculation</h3>
          <p>Banks qualify you on your pre-tax gross salary. After income taxes, healthcare premiums, and retirement contributions, your actual take-home cash is 25% to 30% lower than the bank's underwriting assumes.</p>
        </div>

        <div class="step-card">
          <div class="step-icon-wrap">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          </div>
          <h3>3. The Kindergarten Relief Curve</h3>
          <p>Daycare expenses are intense but temporary. Planning your mortgage with a 3-to-5 year transition horizon allows you to survive the preschool years and accelerate equity once kids enter public school.</p>
        </div>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
