<?php
/**
 * Template Part: Insurance Deductible Savings Calculator
 * Updated with 2026 U.S. Benchmarks
 */
?>
<section class="calculator-main-section" id="deductible-savings-section">
  <div class="container">

    <div class="calc-studio-grid">

      <!-- Left Console: Inputs -->
      <div class="studio-input-card">
        <div class="input-card-header">
          <div class="card-icon-badge" style="background-color: var(--primary-emerald-light); color: var(--primary-emerald);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
          </div>
          <div>
            <h3 class="input-card-title">Policy & Deductible Comparison</h3>
            <p class="input-card-desc">Calculate your break-even horizon for raising your homeowner insurance deductible.</p>
          </div>
        </div>

        <form id="deductible-form" onsubmit="return false;">

          <!-- Current Annual Premium -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label" for="ded-current-premium">
                Current Annual Insurance Premium ($/yr)
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">National benchmark average is $2,750/yr (~$229/mo). Varies widely based on coverage amount, ZIP code, roof age, and insurer underwriting.</span>
                </span>
              </label>
            </div>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="ded-current-premium" class="studio-input" value="2,750" inputmode="numeric">
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-ded-prem-preset" data-prem="1800">$1,800</button>
              <button type="button" class="quick-chip js-ded-prem-preset" data-prem="2424">$2,424 ($300k Dwelling)</button>
              <button type="button" class="quick-chip js-ded-prem-preset active" data-prem="2750">$2,750 (Benchmark)</button>
              <button type="button" class="quick-chip js-ded-prem-preset" data-prem="3374">$3,374 ($450k Dwelling)</button>
            </div>
          </div>

          <!-- Current Deductible -->
          <div class="studio-field-group">
            <label class="studio-label">Current Policy Deductible</label>
            <div class="kids-pills-selector" id="ded-current-pills">
              <button type="button" class="kids-pill-btn" data-ded="500">$500</button>
              <button type="button" class="kids-pill-btn active" data-ded="1000">$1,000</button>
              <button type="button" class="kids-pill-btn" data-ded="1500">$1,500</button>
              <button type="button" class="kids-pill-btn" data-ded="2000">$2,000</button>
            </div>
          </div>

          <!-- Proposed Higher Deductible -->
          <div class="studio-field-group">
            <label class="studio-label">Proposed Higher Deductible</label>
            <div class="kids-pills-selector" id="ded-proposed-pills">
              <button type="button" class="kids-pill-btn" data-ded="1500">$1,500</button>
              <button type="button" class="kids-pill-btn active" data-ded="2500">$2,500</button>
              <button type="button" class="kids-pill-btn" data-ded="5000">$5,000</button>
              <button type="button" class="kids-pill-btn" data-ded="10000">$10,000</button>
            </div>
          </div>

          <!-- Expected Premium Discount Percentage -->
          <div class="daycare-sub-card">
            <div class="sub-card-title">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="19" y1="5" x2="5" y2="19"></line>
                <circle cx="6.5" cy="6.5" r="2.5"></circle>
                <circle cx="17.5" cy="17.5" r="2.5"></circle>
              </svg>
              <span>Premium Discount Planning Estimate</span>
            </div>

            <div class="studio-field-group" style="margin-bottom: 0;">
              <div class="studio-label-row">
                <label class="studio-label" for="ded-discount-pct">
                  Expected Premium Reduction (%)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Moving from $1,000 to $2,500 typically saves an estimated ~9% nationally. Moving from $500 to $1,000 saves ~5%–10%. Actual savings depend on insurer, location, property, claims history, and policy structure.</span>
                  </span>
                </label>
                <span class="total-daycare-indicator">Save: <strong id="indicator-ded-savings">$248/yr</strong></span>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="ded-discount-pct" class="studio-input" value="9" min="1" max="50">
                <span class="studio-unit">%</span>
              </div>
              <div class="quick-shortcuts-row">
                <span class="quick-label">Presets:</span>
                <button type="button" class="quick-chip js-ded-disc-preset" data-disc="5">5% ($500→$1k)</button>
                <button type="button" class="quick-chip js-ded-disc-preset" data-disc="7">7%</button>
                <button type="button" class="quick-chip js-ded-disc-preset active" data-disc="9">9% ($1k→$2.5k Est)</button>
                <button type="button" class="quick-chip js-ded-disc-preset" data-disc="12">12%</button>
                <button type="button" class="quick-chip js-ded-disc-preset" data-disc="15">15%</button>
              </div>
            </div>
          </div>

          <div class="field-hint-text" style="margin-top: 12px; font-size: 0.78rem; color: var(--text-tertiary); line-height: 1.45;">
            ℹ️ Actual premium savings depend on insurer, location, property, claims history, policy structure, and deductible type. Savings are estimates, not guaranteed reductions.
          </div>

          <div class="studio-actions-wrap">
            <button type="button" id="btn-recalc-deductible" class="btn btn-primary btn-calculate-studio">
              <span>Recalculate Break-Even Horizon</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Right Console: Break-Even Horizon -->
      <div class="studio-results-console">
        <div class="console-card">

          <div class="console-header-badge">
            <span class="pulse-dot"></span>
            <span>Risk vs. Reward Horizon</span>
          </div>

          <h2 class="console-headline" id="ded-headline">
            Break-Even: <span class="highlight" id="ded-breakeven-years">6.1 Years</span>
          </h2>
          <p class="console-subheadline" id="ded-subtext">
            If you go at least 6.1 years without filing a claim, accumulated premium savings ($248/yr) will completely cover the $1,500 deductible risk gap.
          </p>

          <!-- Claim Frequency Benchmark Card -->
          <div class="daycare-gap-alert" style="background-color: var(--primary-emerald-light); border-color: var(--primary-emerald-border);">
            <div class="gap-icon">🎯</div>
            <div class="gap-content">
              <strong style="color: var(--primary-emerald-dark);">National Claim Frequency Benchmark</strong>
              <p style="color: var(--text-secondary); margin:0;">The Insurance Information Institute (III) reports the average homeowner files a property claim once every 9 to 10 years. A break-even horizon under 9 years gives you a favorable statistical probability.</p>
            </div>
          </div>

          <!-- 4 Core Metrics -->
          <div class="console-metrics-grid">
            <div class="console-metric-tile">
              <span class="tile-label">Annual Premium Savings</span>
              <span class="tile-value text-emerald" id="ded-res-annual-savings">+$248/yr</span>
              <span class="tile-note">Save ~$21/month</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Deductible Risk Gap</span>
              <span class="tile-value text-teal" id="ded-res-risk-gap">$1,500</span>
              <span class="tile-note">Extra cash if claim occurs</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">5-Year Net Cash Gain</span>
              <span class="tile-value text-emerald" id="ded-res-5yr-gain">+$1,240</span>
              <span class="tile-note">Without a claim filed</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">10-Year Cumulative Gain</span>
              <span class="tile-value text-emerald" id="ded-res-10yr-gain">+$980</span>
              <span class="tile-note">Net of 1 claim deduction</span>
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
