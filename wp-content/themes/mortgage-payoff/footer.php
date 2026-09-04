<?php
/**
 * Footer template for Mortgage Payoff Theme
 * EquityPace - Mortgage Payoff Intelligence
 */
?>
<footer class="site-footer" id="footer-section" role="contentinfo">
  <div class="container">
    <div class="footer-top">

      <!-- Col 1: Brand & Mission -->
      <div class="footer-brand">
        <div style="display: flex; align-items: center; gap: 12px;">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="EquityPace Logo" width="40" height="40" style="border-radius: 10px;" />
          <span style="font-size: 22px; font-weight: 800; color: #ffffff;">Equity<span style="color: var(--primary-blue);">Pace</span></span>
        </div>
        <p>
          Empowering homeowners to eliminate unnecessary mortgage interest, model compound payoff windfalls, and accelerate complete financial independence with mathematical precision.
        </p>
        <div style="margin-top: 20px;">
          <a href="#calculator-section" class="btn btn-outline-white" style="padding: 10px 20px; font-size: 14px;">
            Calculate Payoff Savings
          </a>
        </div>
      </div>

      <!-- Col 2: Mortgage & Payoff Tools -->
      <div class="footer-col">
        <h4>Mortgage Acceleration</h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>">Current Balance Payoff</a></li>
          <li><a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>">Irregular Extra Payments</a></li>
          <li><a href="<?php echo esc_url( home_url( '/mortgage-recast/' ) ); ?>">Mortgage Recast Calculator</a></li>
          <li><a href="<?php echo esc_url( home_url( '/heloc-payment/' ) ); ?>">HELOC Payment Calculator</a></li>
          <li><a href="<?php echo esc_url( home_url( '/#amortization-schedule-section' ) ); ?>">Amortization Schedule & Chart</a></li>
        </ul>
      </div>

      <!-- Col 3: Affordability & Real Estate Tools -->
      <div class="footer-col">
        <h4>Affordability & Investing</h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/home-affordability-daycare/' ) ); ?>">Affordability with Daycare</a></li>
          <li><a href="<?php echo esc_url( home_url( '/rent-vs-buy/' ) ); ?>">Rent vs. Buy with Closing</a></li>
          <li><a href="<?php echo esc_url( home_url( '/seller-net-proceeds/' ) ); ?>">Seller Net-Proceeds</a></li>
          <li><a href="<?php echo esc_url( home_url( '/house-flipping-profit/' ) ); ?>">House-Flipping Profit</a></li>
          <li><a href="<?php echo esc_url( home_url( '/home-replacement-cost/' ) ); ?>">Home Replacement Cost</a></li>
          <li><a href="<?php echo esc_url( home_url( '/insurance-deductible-savings/' ) ); ?>">Deductible Savings</a></li>
        </ul>
      </div>

      <!-- Col 4: Disclosures & Transparency -->
      <div class="footer-col">
        <h4>Disclosures</h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/all-calculators/' ) ); ?>">All 10 Calculators Directory</a></li>
          <li><a href="#footer-section">Independent Educational Tool</a></li>
          <li><a href="#footer-section">No Affiliation with Third-Party Lenders</a></li>
          <li><a href="#footer-section">Privacy Policy & Terms</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
      <div class="lender-badge">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="16" x2="12" y2="12"></line>
          <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <span>EquityPace Financial Intelligence | Independent Educational Calculator</span>
      </div>

      <div>
        &copy; <?php echo date( 'Y' ); ?> EquityPace. All rights reserved. Mathematical projections are estimates for educational planning.
      </div>
    </div>
  </div>
</footer>

<script>
  // Mobile menu toggle
  document.getElementById('btn-mobile-toggle')?.addEventListener('click', function() {
    const drawer = document.getElementById('mobile-drawer');
    if (drawer) {
      drawer.style.display = drawer.style.display === 'none' ? 'block' : 'none';
    }
  });
</script>

<?php wp_footer(); ?>
</body>
</html>
