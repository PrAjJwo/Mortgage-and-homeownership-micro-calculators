<?php
/**
 * Template Part: Mortgage Recast Calculator
 * Updated with 2026 U.S. Benchmarks
 */
?>
<section class="calculator-main-section" id="mortgage-recast-section">
  <div class="container">

    <div class="calc-studio-grid">

      <!-- Left Console: Inputs -->
      <div class="studio-input-card">
        <div class="input-card-header">
          <div class="card-icon-badge" style="background-color: var(--primary-emerald-light); color: var(--primary-emerald);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <polyline points="23 4 23 10 17 10"></polyline>
              <polyline points="1 20 1 14 7 14"></polyline>
              <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
          </div>
          <div>
            <h3 class="input-card-title">Existing Loan & Recast Payment</h3>
            <p class="input-card-desc">Lower your required monthly payment after a lump-sum principal reduction without refinancing.</p>
          </div>
        </div>

        <form id="recast-form" onsubmit="return false;">

          <!-- Current Unpaid Balance -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label" for="recast-balance">
                Current Mortgage Balance
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">The principal balance you currently still owe. This may differ from your lender's official payoff quote.</span>
                </span>
              </label>
            </div>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="recast-balance" class="studio-input" value="265,000" inputmode="numeric">
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-recast-bal-preset" data-bal="200000">$200k</button>
              <button type="button" class="quick-chip js-recast-bal-preset active" data-bal="265000">$265k (Example)</button>
              <button type="button" class="quick-chip js-recast-bal-preset" data-bal="350000">$350k</button>
              <button type="button" class="quick-chip js-recast-bal-preset" data-bal="500000">$500k</button>
            </div>
          </div>

          <!-- Interest Rate & Remaining Years -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="recast-rate">
                  Interest Rate (%)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Your existing note rate. In a recast, your interest rate and final maturity date remain unchanged.</span>
                  </span>
                </label>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="recast-rate" class="studio-input" value="6.71" step="0.01" min="1" max="15">
                <span class="studio-unit">%</span>
              </div>
            </div>

            <div class="studio-field-group">
              <label class="studio-label" for="recast-years-left">Remaining Loan Term (Years)</label>
              <div class="studio-input-wrap">
                <input type="number" id="recast-years-left" class="studio-input" value="25" min="1" max="30">
                <span class="studio-unit">Yrs</span>
              </div>
            </div>
          </div>

          <!-- Lump-Sum Recast Payment -->
          <div class="daycare-sub-card">
            <div class="sub-card-title">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
              <span>Lump-Sum Principal Reduction & Fee</span>
            </div>

            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="recast-lump-sum">Lump-Sum Recast Payment ($)</label>
                <span class="total-daycare-indicator">Typical Min: <strong>$5k–$10k</strong></span>
              </div>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="recast-lump-sum" class="studio-input" value="10,000" inputmode="numeric">
              </div>
              <div class="quick-shortcuts-row">
                <span class="quick-label">Presets:</span>
                <button type="button" class="quick-chip js-recast-lump-preset" data-lump="5000">+$5k</button>
                <button type="button" class="quick-chip js-recast-lump-preset active" data-lump="10000">+$10k</button>
                <button type="button" class="quick-chip js-recast-lump-preset" data-lump="20000">+$20k</button>
                <button type="button" class="quick-chip js-recast-lump-preset" data-lump="50000">+$50k</button>
                <button type="button" class="quick-chip js-recast-lump-preset" data-lump="75000">+$75k</button>
                <button type="button" class="quick-chip js-recast-lump-preset" data-lump="100000">+$100k</button>
              </div>
            </div>

            <div class="studio-field-group" style="margin-bottom: 0;">
              <div class="studio-label-row">
                <label class="studio-label" for="recast-fee">
                  Lender Recast Processing Fee ($)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Typical servicer administrative recast fee ranges from $150 to $500 (default $250).</span>
                  </span>
                </label>
              </div>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="recast-fee" class="studio-input" value="250">
              </div>
            </div>
          </div>

          <!-- Eligibility Note -->
          <div class="field-hint-text" style="margin-top: 12px; font-size: 0.8rem; color: var(--text-tertiary); line-height: 1.45;">
            💡 <strong>Eligibility Notice:</strong> A mortgage recast reduces the required monthly payment after a qualifying principal reduction while generally keeping the existing interest rate and remaining maturity date. Not all loan types qualify (FHA, VA, and USDA loans typically do not allow recasting; conventional and jumbo conforming loans usually do, subject to servicer policy).
          </div>

          <div class="studio-actions-wrap">
            <button type="button" id="btn-recalc-recast" class="btn btn-primary btn-calculate-studio">
              <span>Calculate Recast Payment</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Right Console: Before vs After Recast -->
      <div class="studio-results-console">
        <div class="console-card">

          <div class="console-header-badge">
            <span class="pulse-dot"></span>
            <span>Re-Amortization Impact</span>
          </div>

          <h2 class="console-headline" id="recast-headline">
            New Monthly Payment: <span class="highlight" id="recast-new-payment">$1,754/mo</span>
          </h2>
          <p class="console-subheadline" id="recast-subtext">
            Your monthly mortgage payment drops by <strong id="recast-monthly-savings">+$69/month</strong> for the remaining 25 years without resetting your loan term or changing your interest rate.
          </p>

          <!-- Before vs After Tiles -->
          <div class="dual-affordability-grid">
            <div class="afford-tile bank-limit-tile">
              <span class="tile-tag">Original Current Payment</span>
              <span class="tile-big-num" id="recast-old-payment">$1,823/mo</span>
              <span class="tile-detail">Principal & Interest on $265,000</span>
              <span class="tile-payment">25 Years Remaining at 6.71%</span>
            </div>

            <div class="afford-tile safe-limit-tile">
              <span class="tile-tag">New Recast Payment</span>
              <span class="tile-big-num text-emerald" id="recast-new-tile-payment">$1,754/mo</span>
              <span class="tile-detail">Principal & Interest on $255,000</span>
              <span class="tile-payment text-emerald" id="recast-tile-savings">Save $69 Every Month</span>
            </div>
          </div>

          <!-- Recast vs Refinance Comparison Box -->
          <div class="kindergarten-milestone-card" style="margin-bottom: 24px;">
            <div class="k-card-header">
              <div class="k-icon">⚖️</div>
              <div>
                <h4 class="k-title">Why Recasting Beats Refinancing</h4>
                <p class="k-subtitle">Preserve your locked note rate with minimal administrative fees</p>
              </div>
            </div>
            <div class="k-stat-row">
              <div class="k-stat">
                <span class="k-stat-label">Recast Fee</span>
                <span class="k-stat-val text-emerald" id="recast-summary-fee">$250</span>
              </div>
              <div class="k-stat">
                <span class="k-stat-label">Refinance Closing Costs</span>
                <span class="k-stat-val" style="color: #ef4444;">~$5,000–$8,000</span>
              </div>
              <div class="k-stat">
                <span class="k-stat-label">Appraisal & Underwriting</span>
                <span class="k-stat-val text-emerald">Typically None</span>
              </div>
            </div>
          </div>

          <!-- 4 Core Metrics -->
          <div class="console-metrics-grid">
            <div class="console-metric-tile">
              <span class="tile-label">Monthly Cash Flow Freed</span>
              <span class="tile-value text-emerald" id="recast-res-monthly-savings">+$69/mo</span>
              <span class="tile-note">Direct monthly reduction</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Remaining Interest After Recast</span>
              <span class="tile-value text-teal" id="recast-res-remaining-interest">$271,280</span>
              <span class="tile-note">Over 25 years remaining</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Principal Paid Down</span>
              <span class="tile-value" id="recast-res-lump">$10,000</span>
              <span class="tile-note">Direct principal reduction</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Annual Cash Flow Gain</span>
              <span class="tile-value text-emerald" id="recast-res-annual-savings">+$828/yr</span>
              <span class="tile-note">Extra annual liquidity</span>
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
