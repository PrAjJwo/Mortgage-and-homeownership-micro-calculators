<?php
/**
 * Header template for Mortgage Payoff Theme
 * EquityPace - Mortgage Payoff Intelligence
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="icon" type="image/svg+xml" href="<?php echo esc_url( get_template_directory_uri() . '/assets/images/favicon.svg' ); ?>" />
  <script>
    (function() {
      try {
        var savedTheme = localStorage.getItem('equitypace_theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
      } catch (e) {}
    })();
  </script>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Main Header & Navigation -->
<header class="site-header" role="banner">
  <div class="container">
    <!-- Brand Logo -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand-logo" aria-label="EquityPace Mortgage Payoff Intelligence">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="EquityPace Logo" class="brand-icon-svg" width="42" height="42" />
      <div class="logo-text">
        <span class="brand-name">Equity<span class="brand-accent">Pace</span></span>
        <span class="brand-tagline">Payoff Intelligence</span>
      </div>
    </a>

    <!-- Focused Navigation: Only Active Features -->
    <nav class="main-navigation" role="navigation" aria-label="Primary Feature Navigation">
      <div class="nav-pills-segment">
        
        <!-- Pillar 1: Current Balance Payoff (with flyout sub-tools) -->
        <div class="nav-flyout-item">
          <a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>" class="nav-pill-item active" id="nav-item-current-balance">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
            <span>Current Balance Payoff</span>
            <svg class="chevron-arrow" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </a>

          <div class="nav-flyout-menu">
            <div class="flyout-category-label">Payoff & Loan Strategies</div>
            <a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">⏱</span>
              <div class="flyout-text">
                <strong>Current Balance Payoff</strong>
                <small>Core payoff calculator using current balance</small>
              </div>
            </a>
            <a href="<?php echo esc_url( home_url( '/mortgage-recast/' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">🔄</span>
              <div class="flyout-text">
                <strong>Mortgage Recast Calculator</strong>
                <small>Permanently lower monthly payment with lump-sum</small>
              </div>
            </a>
            <a href="<?php echo esc_url( home_url( '/heloc-payment/' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">💳</span>
              <div class="flyout-text">
                <strong>HELOC Payment Calculator</strong>
                <small>Interest-only draw vs. future payment shock</small>
              </div>
            </a>
            <a href="<?php echo esc_url( home_url( '/#amortization-schedule-section' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">📊</span>
              <div class="flyout-text">
                <strong>Amortization Schedule & Chart</strong>
                <small>Interactive yearly and monthly breakdown</small>
              </div>
            </a>
          </div>
        </div>

        <!-- Pillar 2: Irregular Extra Payments (with flyout sub-tools) -->
        <div class="nav-flyout-item">
          <a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>" class="nav-pill-item" id="nav-item-irregular-payments">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span>Irregular Extra Payments</span>
            <svg class="chevron-arrow" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </a>

          <div class="nav-flyout-menu">
            <div class="flyout-category-label">Lump-Sums & Real Estate Deals</div>
            <a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">🎁</span>
              <div class="flyout-text">
                <strong>Irregular Extra Payments</strong>
                <small>Model bonuses, refunds & commissions</small>
              </div>
            </a>
            <a href="<?php echo esc_url( home_url( '/house-flipping-profit/' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">🔨</span>
              <div class="flyout-text">
                <strong>House-Flipping Profit Calculator</strong>
                <small>ARV, rehab costs & 70% rule MAO</small>
              </div>
            </a>
            <a href="<?php echo esc_url( home_url( '/seller-net-proceeds/' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">💰</span>
              <div class="flyout-text">
                <strong>Seller Net-Proceeds Calculator</strong>
                <small>Net cash at closing table after mortgage payoff</small>
              </div>
            </a>
          </div>
        </div>

        <!-- Pillar 3: Affordability & Daycare (with flyout sub-tools) -->
        <div class="nav-flyout-item">
          <a href="<?php echo esc_url( home_url( '/home-affordability-daycare/' ) ); ?>" class="nav-pill-item" id="nav-item-daycare">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
              <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span>Affordability & Daycare</span>
            <svg class="chevron-arrow" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </a>

          <div class="nav-flyout-menu">
            <div class="flyout-category-label">Affordability & Home Valuation</div>
            <a href="<?php echo esc_url( home_url( '/home-affordability-daycare/' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">👶</span>
              <div class="flyout-text">
                <strong>Home Affordability with Daycare</strong>
                <small>True family budget vs standard bank DTI</small>
              </div>
            </a>
            <a href="<?php echo esc_url( home_url( '/rent-vs-buy/' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">⚖️</span>
              <div class="flyout-text">
                <strong>Rent vs. Buy with Closing Costs</strong>
                <small>30-year wealth crossover & opportunity cost</small>
              </div>
            </a>
            <a href="<?php echo esc_url( home_url( '/home-replacement-cost/' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">🏗️</span>
              <div class="flyout-text">
                <strong>Home Replacement-Cost Calculator</strong>
                <small>Coverage A structural rebuilding cost</small>
              </div>
            </a>
            <a href="<?php echo esc_url( home_url( '/insurance-deductible-savings/' ) ); ?>" class="flyout-link">
              <span class="flyout-icon">🛡️</span>
              <div class="flyout-text">
                <strong>Insurance Deductible Savings</strong>
                <small>Break-even claim horizon on policy deductibles</small>
              </div>
            </a>
          </div>
        </div>

      </div>

      <div class="header-cta">
        <!-- Light/Dark Mode Switcher Button -->
        <button type="button" id="theme-toggle-btn" class="theme-toggle-btn" aria-label="Switch between Light and Dark mode" title="Switch Theme">
          <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
          </svg>
          <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
          </svg>
          <span class="theme-text">Theme</span>
        </button>

        <!-- Amortization Direct CTA -->
        <a href="#amortization-schedule-section" class="btn btn-outline-nav">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"></line>
            <line x1="12" y1="20" x2="12" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="14"></line>
          </svg>
          <span>View Schedule</span>
        </a>
      </div>
    </nav>

    <!-- Mobile Hamburger Toggle -->
    <button type="button" id="mobile-menu-toggle" class="mobile-menu-toggle" aria-label="Open Navigation Menu">
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
    </button>
  </div>

  <!-- Mobile Drawer: All 10 Features Organized -->
  <div id="mobile-drawer" class="mobile-nav-drawer" style="display: none;">
    <div class="mobile-nav-menu">
      <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:8px;">Mortgage Acceleration</div>
      <a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>" class="mobile-nav-item active">
        <span>⏱ Current Balance Payoff</span>
      </a>
      <a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>" class="mobile-nav-item">
        <span>🎁 Irregular Extra Payments</span>
      </a>
      <a href="<?php echo esc_url( home_url( '/mortgage-recast/' ) ); ?>" class="mobile-nav-item">
        <span>🔄 Mortgage Recast Calculator</span>
      </a>
      <a href="<?php echo esc_url( home_url( '/heloc-payment/' ) ); ?>" class="mobile-nav-item">
        <span>💳 HELOC Payment Calculator</span>
      </a>

      <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin:12px 0 8px;">Affordability & Real Estate</div>
      <a href="<?php echo esc_url( home_url( '/home-affordability-daycare/' ) ); ?>" class="mobile-nav-item">
        <span>👶 Affordability with Daycare</span>
      </a>
      <a href="<?php echo esc_url( home_url( '/rent-vs-buy/' ) ); ?>" class="mobile-nav-item">
        <span>⚖️ Rent vs. Buy with Closing</span>
      </a>
      <a href="<?php echo esc_url( home_url( '/seller-net-proceeds/' ) ); ?>" class="mobile-nav-item">
        <span>💰 Seller Net-Proceeds</span>
      </a>
      <a href="<?php echo esc_url( home_url( '/house-flipping-profit/' ) ); ?>" class="mobile-nav-item">
        <span>🔨 House-Flipping Profit</span>
      </a>
      <a href="<?php echo esc_url( home_url( '/home-replacement-cost/' ) ); ?>" class="mobile-nav-item">
        <span>🏗️ Home Replacement Cost</span>
      </a>
      <a href="<?php echo esc_url( home_url( '/insurance-deductible-savings/' ) ); ?>" class="mobile-nav-item">
        <span>🛡️ Deductible Savings</span>
      </a>

      <!-- Mobile Theme Toggle -->
      <button type="button" id="mobile-theme-toggle" class="mobile-nav-item mobile-theme-switch-btn" style="margin-top:12px;">
        <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line></svg>
        <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        <span id="mobile-theme-text">Toggle Theme (Light/Dark)</span>
      </button>
      <div style="margin-top: 10px; padding-top: 14px; border-top: 1px solid var(--border-color);">
        <a href="<?php echo esc_url( home_url( '/#amortization-schedule-section' ) ); ?>" class="btn btn-primary" style="width: 100%; text-align: center; justify-content: center;">
          View Amortization Schedule
        </a>
      </div>
    </div>
  </div>
</header>
