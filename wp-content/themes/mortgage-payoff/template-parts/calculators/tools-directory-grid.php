<?php
/**
 * Template Part: Comprehensive Real Estate & Mortgage Tools Directory Grid
 * Displays all 10 tools organized logically by category
 */
?>
<section class="tools-directory-section" id="tools-directory-section">
  <div class="container">

    <div class="tools-directory-header">
      <span class="tools-badge">Complete Financial Suite</span>
      <h2 class="tools-title">Explore All 10 Property & Mortgage Calculators</h2>
      <p class="tools-subtitle">
        Independent, mathematical financial modeling tools built for homeowners, homebuyers, and real estate investors.
      </p>
    </div>

    <!-- Category 1: Mortgage Acceleration & Payoff -->
    <div class="tools-category-block">
      <div class="category-title-row">
        <div class="category-icon">⚡</div>
        <div>
          <h3 class="category-title">Mortgage Acceleration & Payoff</h3>
          <p class="category-desc">Strategies to eliminate compound interest and achieve debt freedom years ahead of schedule.</p>
        </div>
      </div>

      <div class="tools-cards-grid">
        <a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>" class="tool-card">
          <div class="tool-card-icon">⏱</div>
          <div class="tool-card-body">
            <h4 class="tool-name">Current Balance Payoff Calculator</h4>
            <p class="tool-desc">Model early debt payoff timelines using your actual outstanding mortgage balance instead of day-one original loan terms.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>

        <a href="<?php echo esc_url( home_url( '/#calculator-section' ) ); ?>" class="tool-card">
          <div class="tool-card-icon">🎁</div>
          <div class="tool-card-body">
            <h4 class="tool-name">Irregular & Lump-Sum Extra Payments</h4>
            <p class="tool-desc">Simulate the compounding impact of annual job bonuses, tax refunds, and quarterly commissions applied directly to principal.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>

        <a href="<?php echo esc_url( home_url( '/mortgage-recast/' ) ); ?>" class="tool-card">
          <div class="tool-card-icon">🔄</div>
          <div class="tool-card-body">
            <h4 class="tool-name">Mortgage Recast Calculator</h4>
            <p class="tool-desc">See how a lump-sum payment permanently lowers your monthly mortgage payment without changing your interest rate or refinancing.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>
      </div>
    </div>

    <!-- Category 2: Home Affordability & Purchasing -->
    <div class="tools-category-block">
      <div class="category-title-row">
        <div class="category-icon">🏡</div>
        <div>
          <h3 class="category-title">Affordability & Home Acquisition</h3>
          <p class="category-desc">Precision cash flow underwriting tailored to real-world family budgets and financial trade-offs.</p>
        </div>
      </div>

      <div class="tools-cards-grid">
        <a href="<?php echo esc_url( home_url( '/home-affordability-daycare/' ) ); ?>" class="tool-card highlight-card">
          <div class="tool-card-icon">👶</div>
          <div class="tool-card-body">
            <span class="card-mini-badge">Popular</span>
            <h4 class="tool-name">Home Affordability with Daycare Costs</h4>
            <p class="tool-desc">Standard lender DTIs ignore childcare tuition. Calculate what your family can realistically buy without becoming house-poor.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>

        <a href="<?php echo esc_url( home_url( '/rent-vs-buy/' ) ); ?>" class="tool-card">
          <div class="tool-card-icon">⚖️</div>
          <div class="tool-card-body">
            <h4 class="tool-name">Rent vs. Buy with Closing Costs</h4>
            <p class="tool-desc">Find your exact break-even crossover year comparing home equity accumulation vs. renting and investing in index funds.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>

        <a href="<?php echo esc_url( home_url( '/heloc-payment/' ) ); ?>" class="tool-card">
          <div class="tool-card-icon">💳</div>
          <div class="tool-card-body">
            <h4 class="tool-name">HELOC Interest-Only Payment Calculator</h4>
            <p class="tool-desc">Calculate low interest-only payments during your draw period and prepare for future payment shock during repayment.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>
      </div>
    </div>

    <!-- Category 3: Homeowner & Real Estate Investor -->
    <div class="tools-category-block">
      <div class="category-title-row">
        <div class="category-icon">📈</div>
        <div>
          <h3 class="category-title">Homeowner Equity & Investor Intelligence</h3>
          <p class="category-desc">Maximize net profit on property sales, rehab investments, and insurance policy deductibles.</p>
        </div>
      </div>

      <div class="tools-cards-grid">
        <a href="<?php echo esc_url( home_url( '/seller-net-proceeds/' ) ); ?>" class="tool-card">
          <div class="tool-card-icon">💰</div>
          <div class="tool-card-body">
            <h4 class="tool-name">Seller Net-Proceeds Calculator</h4>
            <p class="tool-desc">Estimate the exact net cash proceeds you will walk away with at the closing table after paying off mortgages, commissions, and fees.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>

        <a href="<?php echo esc_url( home_url( '/house-flipping-profit/' ) ); ?>" class="tool-card">
          <div class="tool-card-icon">🔨</div>
          <div class="tool-card-body">
            <h4 class="tool-name">House-Flipping Profit Calculator</h4>
            <p class="tool-desc">Evaluate fix-and-flip deals factoring in purchase price, rehab budget, hard money financing, holding costs, and the 70% rule MAO.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>

        <a href="<?php echo esc_url( home_url( '/home-replacement-cost/' ) ); ?>" class="tool-card">
          <div class="tool-card-icon">🏗️</div>
          <div class="tool-card-body">
            <h4 class="tool-name">Home Replacement-Cost Calculator</h4>
            <p class="tool-desc">Estimate your dwelling reconstruction cost (Coverage A) based on square footage, architectural grade, and local construction indices.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>

        <a href="<?php echo esc_url( home_url( '/insurance-deductible-savings/' ) ); ?>" class="tool-card">
          <div class="tool-card-icon">🛡️</div>
          <div class="tool-card-body">
            <h4 class="tool-name">Insurance Deductible Savings Calculator</h4>
            <p class="tool-desc">Find your break-even claim horizon to see if raising your homeowner insurance deductible saves more than the out-of-pocket risk.</p>
            <span class="tool-link-text">Launch Tool →</span>
          </div>
        </a>
      </div>
    </div>

  </div>
</section>
