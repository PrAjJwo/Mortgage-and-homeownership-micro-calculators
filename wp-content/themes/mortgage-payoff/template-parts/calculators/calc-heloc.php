<?php
/**
 * Template Part: HELOC Interest-Only Payment Calculator
 * Updated with 2026 U.S. Benchmarks
 */
?>
<section class="calculator-main-section" id="heloc-payment-section">
  <div class="container">

    <div class="calc-studio-grid">

      <!-- Left Console: Inputs -->
      <div class="studio-input-card">
        <div class="input-card-header">
          <div class="card-icon-badge" style="background-color: var(--primary-emerald-light); color: var(--primary-emerald);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
              <line x1="1" y1="10" x2="23" y2="10"></line>
            </svg>
          </div>
          <div>
            <h3 class="input-card-title">HELOC Balance & Rate</h3>
            <p class="input-card-desc">Calculate interest-only draw payments and prepare for future repayment payment shock.</p>
          </div>
        </div>

        <form id="heloc-form" onsubmit="return false;">

          <!-- Home Value & 1st Mortgage -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <label class="studio-label" for="heloc-home-val">Current Home Market Value</label>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="heloc-home-val" class="studio-input" value="500,000" inputmode="numeric">
              </div>
            </div>

            <div class="studio-field-group">
              <label class="studio-label" for="heloc-mortgage-bal">Existing 1st Mortgage Balance</label>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="heloc-mortgage-bal" class="studio-input" value="250,000" inputmode="numeric">
              </div>
            </div>
          </div>

          <!-- HELOC Amount Drawn -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label" for="heloc-amount-drawn">
                HELOC Amount Drawn (Borrowed)
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">The portion of your home equity line of credit currently utilized. Combined Loan-to-Value (CLTV) calculates total mortgages divided by home value.</span>
                </span>
              </label>
              <span class="total-daycare-indicator">CLTV: <strong id="indicator-cltv">62.0%</strong></span>
            </div>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="heloc-amount-drawn" class="studio-input" value="60,000" inputmode="numeric">
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-heloc-draw-preset" data-draw="30000">$30k</button>
              <button type="button" class="quick-chip js-heloc-draw-preset active" data-draw="60000">$60k</button>
              <button type="button" class="quick-chip js-heloc-draw-preset" data-draw="100000">$100k</button>
              <button type="button" class="quick-chip js-heloc-draw-preset" data-draw="150000">$150k</button>
            </div>
          </div>

          <!-- Target Max CLTV Presets -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label">
                Target Maximum CLTV Limit
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Most lenders cap combined home loans at 80% to 85% CLTV. CLTV = (1st Mortgage + HELOC) ÷ Home Value.</span>
                </span>
              </label>
            </div>
            <div class="quick-shortcuts-row" id="heloc-cltv-presets">
              <button type="button" class="quick-chip js-heloc-cltv-preset" data-cltv="75">75% Max CLTV</button>
              <button type="button" class="quick-chip js-heloc-cltv-preset active" data-cltv="80">80% Max CLTV (Standard)</button>
              <button type="button" class="quick-chip js-heloc-cltv-preset" data-cltv="85">85% Max CLTV</button>
              <button type="button" class="quick-chip js-heloc-cltv-preset" data-cltv="90">90% Max CLTV</button>
            </div>
          </div>

          <!-- HELOC Interest Rate & Periods -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="heloc-interest-rate">
                  HELOC Interest Rate (%)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Bankrate benchmark updated September 2, 2026. HELOC rates are commonly variable and may change when the underlying index (e.g. Prime Rate) or lender margin changes.</span>
                  </span>
                </label>
                <span class="benchmark-date-badge">Benchmark Sep. 2, 2026</span>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="heloc-interest-rate" class="studio-input" value="7.29" step="0.01" min="1" max="25">
                <span class="studio-unit">%</span>
              </div>
            </div>

            <div class="studio-field-group">
              <label class="studio-label" for="heloc-repay-years">Repayment Period (Years)</label>
              <div class="studio-input-wrap">
                <input type="number" id="heloc-repay-years" class="studio-input" value="20" min="5" max="30">
                <span class="studio-unit">Yrs</span>
              </div>
            </div>
          </div>

          <!-- Draw Period Toggle -->
          <div class="studio-field-group">
            <label class="studio-label">Draw Period (Interest-Only)</label>
            <div class="term-pills-selector" id="heloc-draw-pills">
              <button type="button" class="term-pill-btn" data-draw="5">5 Years</button>
              <button type="button" class="term-pill-btn active" data-draw="10">10 Years (Default)</button>
              <button type="button" class="term-pill-btn" data-draw="15">15 Years</button>
            </div>
          </div>

          <div class="field-hint-text" style="font-size: 0.78rem; color: var(--text-tertiary); line-height: 1.45;">
            ℹ️ Note: HELOC rates are commonly variable and may fluctuate when the Prime Rate or lender margin changes.
          </div>

          <div class="studio-actions-wrap">
            <button type="button" id="btn-recalc-heloc" class="btn btn-primary btn-calculate-studio">
              <span>Recalculate HELOC Payments</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Right Console: Interest-Only vs Payment Shock -->
      <div class="studio-results-console">
        <div class="console-card">

          <div class="console-header-badge">
            <span class="pulse-dot"></span>
            <span>HELOC Cash Flow Timeline</span>
          </div>

          <h2 class="console-headline" id="heloc-headline">
            Interest-Only: <span class="highlight" id="heloc-io-payment">$365/mo</span>
          </h2>
          <p class="console-subheadline" id="heloc-subtext">
            Interest-only monthly payment during your 10-year draw period on a $60,000 balance at 7.29%.
          </p>

          <!-- Payment Shock Warning Alert -->
          <div class="daycare-gap-alert" id="heloc-shock-alert">
            <div class="gap-icon">⚡</div>
            <div class="gap-content">
              <strong>Future Repayment Shock: <span id="heloc-shock-payment">$476/mo</span></strong>
              <p id="heloc-shock-text">When your 10-year draw ends, mandatory principal amortization begins over 20 years, increasing your payment by +$111/mo (+30%).</p>
            </div>
          </div>

          <!-- Dual Comparison Cards -->
          <div class="dual-affordability-grid">
            <div class="afford-tile safe-limit-tile">
              <span class="tile-tag">Draw Period Payment</span>
              <span class="tile-big-num text-emerald" id="heloc-draw-tile-payment">$365/mo</span>
              <span class="tile-detail">100% Interest-Only Payment</span>
              <span class="tile-payment">Principal Balance Does Not Decrease</span>
            </div>

            <div class="afford-tile bank-limit-tile">
              <span class="tile-tag">Repayment Period Payment</span>
              <span class="tile-big-num" id="heloc-repay-tile-payment">$476/mo</span>
              <span class="tile-detail">Amortizing Principal + Interest</span>
              <span class="tile-payment text-teal">Full Payoff Over 20 Years</span>
            </div>
          </div>

          <!-- Combined Loan to Value (CLTV) Safety Bar -->
          <div class="timeline-visual-box">
            <div class="timeline-labels-row">
              <span class="timeline-tag">Combined Loan-To-Value (CLTV)</span>
              <span class="timeline-cut-badge" id="heloc-cltv-badge" style="background:#d1fae5; color:#065f46;">✓ 62.0% Safe (Under 80%)</span>
            </div>
            <div class="timeline-track">
              <div class="timeline-fill" id="heloc-cltv-fill" style="width: 62%;"></div>
            </div>
            <div class="timeline-endpoints">
              <div>
                <small>Total Debt (1st + HELOC)</small>
                <strong id="heloc-total-debt">$310,000</strong>
              </div>
              <div style="text-align: right;">
                <small>Home Equity Left</small>
                <strong id="heloc-equity-left">$190,000</strong>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- Educational Disclaimer -->
    <?php if ( class_exists( 'EquityPace_Benchmarks' ) ) {
      echo EquityPace_Benchmarks::render_disclaimer();
    } ?>

  </div>
</section>
