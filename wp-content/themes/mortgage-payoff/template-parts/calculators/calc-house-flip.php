<?php
/**
 * Template Part: House-Flipping Profit Calculator
 * Updated with 2026 U.S. Benchmarks
 */
?>
<section class="calculator-main-section" id="house-flip-section">
  <div class="container">

    <div class="calc-studio-grid">

      <!-- Left Console: Inputs -->
      <div class="studio-input-card">
        <div class="input-card-header">
          <div class="card-icon-badge" style="background-color: var(--primary-emerald-light); color: var(--primary-emerald);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
          </div>
          <div>
            <h3 class="input-card-title">Deal & Rehab Parameters</h3>
            <p class="input-card-desc">Underwrite your fix-and-flip investment with financing, holding, and exit costs.</p>
          </div>
        </div>

        <form id="flip-form" onsubmit="return false;">

          <!-- Purchase Price & ARV -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <label class="studio-label" for="flip-purchase-price">Purchase Price</label>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="flip-purchase-price" class="studio-input" value="220,000" inputmode="numeric">
              </div>
            </div>

            <div class="studio-field-group">
              <label class="studio-label" for="flip-arv">
                After-Repair Value (ARV)
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Estimated fair market resale value once all renovations and cosmetic upgrades are complete.</span>
                </span>
              </label>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="flip-arv" class="studio-input" value="350,000" inputmode="numeric">
              </div>
            </div>
          </div>

          <!-- Rehab Budget & Holding Timeline -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <label class="studio-label" for="flip-rehab-budget">Rehab / Renovation Cost</label>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="flip-rehab-budget" class="studio-input" value="50,000" inputmode="numeric">
              </div>
            </div>

            <div class="studio-field-group">
              <label class="studio-label" for="flip-holding-months">Project Duration (Months)</label>
              <div class="studio-input-wrap">
                <input type="number" id="flip-holding-months" class="studio-input" value="6" min="1" max="24">
                <span class="studio-unit">Mo</span>
              </div>
            </div>
          </div>

          <!-- 70% Rule Investor Percentage -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label" for="flip-rule-pct">
                Investor Rule-of-Thumb Benchmark (%)
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Maximum Allowable Offer (MAO) = (ARV × Investor %) − Rehab. 70% is a common investor guideline, not a mandatory pricing formula.</span>
                </span>
              </label>
            </div>
            <div class="studio-input-wrap">
              <input type="number" id="flip-rule-pct" class="studio-input" value="70" step="5" min="50" max="85">
              <span class="studio-unit">%</span>
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-flip-rule-preset" data-val="65">65%</button>
              <button type="button" class="quick-chip js-flip-rule-preset active" data-val="70">70% (Standard)</button>
              <button type="button" class="quick-chip js-flip-rule-preset" data-val="75">75%</button>
            </div>
          </div>

          <!-- Financing Sub-Card -->
          <div class="daycare-sub-card">
            <div class="sub-card-title">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
              </svg>
              <span>Financing & Capital Structure (Hard Money)</span>
            </div>

            <div class="studio-two-col">
              <div class="studio-field-group">
                <label class="studio-label" for="flip-loan-pct">Loan-to-Cost (LTC %)</label>
                <div class="studio-input-wrap">
                  <input type="number" id="flip-loan-pct" class="studio-input" value="80" min="0" max="100">
                  <span class="studio-unit">%</span>
                </div>
                <div class="quick-shortcuts-row">
                  <span class="quick-label">Presets:</span>
                  <button type="button" class="quick-chip js-flip-ltc-preset" data-val="70">70%</button>
                  <button type="button" class="quick-chip js-flip-ltc-preset" data-val="75">75%</button>
                  <button type="button" class="quick-chip js-flip-ltc-preset active" data-val="80">80%</button>
                  <button type="button" class="quick-chip js-flip-ltc-preset" data-val="85">85%</button>
                </div>
              </div>

              <div class="studio-field-group">
                <label class="studio-label" for="flip-loan-rate">Hard Money Interest (%/yr)</label>
                <div class="studio-input-wrap">
                  <input type="number" id="flip-loan-rate" class="studio-input" value="11.0" step="0.5" min="0" max="25">
                  <span class="studio-unit">%</span>
                </div>
                <div class="quick-shortcuts-row">
                  <span class="quick-label">Presets:</span>
                  <button type="button" class="quick-chip js-flip-rate-preset" data-val="9.0">9%</button>
                  <button type="button" class="quick-chip js-flip-rate-preset active" data-val="11.0">11% (Default)</button>
                  <button type="button" class="quick-chip js-flip-rate-preset" data-val="14.0">14%</button>
                </div>
              </div>
            </div>

            <div class="studio-two-col">
              <div class="studio-field-group">
                <label class="studio-label" for="flip-loan-points">Origination Points (1 point = 1%)</label>
                <div class="studio-input-wrap">
                  <input type="number" id="flip-loan-points" class="studio-input" value="2.0" step="0.5" min="0" max="10">
                  <span class="studio-unit">Pts</span>
                </div>
                <div class="quick-shortcuts-row">
                  <span class="quick-label">Presets:</span>
                  <button type="button" class="quick-chip js-flip-points-preset" data-val="1.0">1 pt</button>
                  <button type="button" class="quick-chip js-flip-points-preset active" data-val="2.0">2 pts</button>
                  <button type="button" class="quick-chip js-flip-points-preset" data-val="3.0">3 pts</button>
                  <button type="button" class="quick-chip js-flip-points-preset" data-val="4.0">4 pts</button>
                </div>
              </div>

              <div class="studio-field-group">
                <div class="studio-label-row">
                  <label class="studio-label" for="flip-monthly-holding">
                    Monthly Holding Costs ($/mo)
                    <span class="tooltip-trigger">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                      <span class="tooltip-popover">Property taxes, builder risk insurance, utilities, HOA, lawn care, and security during rehab. Default is $750/mo.</span>
                    </span>
                  </label>
                </div>
                <div class="studio-input-wrap">
                  <span class="studio-curr">$</span>
                  <input type="text" id="flip-monthly-holding" class="studio-input" value="750">
                </div>
                <div class="quick-shortcuts-row">
                  <span class="quick-label">Presets:</span>
                  <button type="button" class="quick-chip js-flip-holding-preset" data-val="500">$500</button>
                  <button type="button" class="quick-chip js-flip-holding-preset active" data-val="750">$750</button>
                  <button type="button" class="quick-chip js-flip-holding-preset" data-val="1000">$1k</button>
                  <button type="button" class="quick-chip js-flip-holding-preset" data-val="1500">$1.5k</button>
                  <button type="button" class="quick-chip js-flip-holding-preset" data-val="2500">$2.5k</button>
                </div>
              </div>
            </div>

            <!-- Selling Overhead Override -->
            <div class="studio-field-group" style="margin-bottom: 0;">
              <div class="studio-label-row">
                <label class="studio-label" for="flip-selling-overhead-pct">
                  Estimated Selling Overhead (% of ARV)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Estimated total selling overhead (typically 6%–10%, default 8%). Covers broker compensation, title, escrow, transfer taxes, staging, and closing concessions.</span>
                  </span>
                </label>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="flip-selling-overhead-pct" class="studio-input" value="8.0" step="0.5" min="4" max="15">
                <span class="studio-unit">%</span>
              </div>
              <div class="quick-shortcuts-row">
                <span class="quick-label">Presets:</span>
                <button type="button" class="quick-chip js-flip-overhead-preset" data-val="6.0">6%</button>
                <button type="button" class="quick-chip js-flip-overhead-preset" data-val="7.0">7%</button>
                <button type="button" class="quick-chip js-flip-overhead-preset active" data-val="8.0">8% (Default)</button>
                <button type="button" class="quick-chip js-flip-overhead-preset" data-val="9.0">9%</button>
                <button type="button" class="quick-chip js-flip-overhead-preset" data-val="10.0">10%</button>
              </div>
            </div>
          </div>

          <div class="studio-actions-wrap">
            <button type="button" id="btn-recalc-flip" class="btn btn-primary btn-calculate-studio">
              <span>Recalculate Deal Profit</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Right Console: Profitability & 70% Rule Scorecard -->
      <div class="studio-results-console">
        <div class="console-card">

          <div class="console-header-badge">
            <span class="pulse-dot"></span>
            <span>Investment Viability Analysis</span>
          </div>

          <h2 class="console-headline" id="flip-headline">
            Net Flip Profit: <span class="highlight" id="flip-net-profit">$37,220</span>
          </h2>
          <p class="console-subheadline" id="flip-subtext">
            Total net cash return after paying purchase, renovation, hard money interest, $750/mo holding costs, and 8% resale overhead.
          </p>

          <!-- 70% Rule Benchmark Card -->
          <div class="daycare-gap-alert" id="flip-mao-alert" style="background-color: var(--primary-emerald-light); border-color: var(--primary-emerald-border);">
            <div class="gap-icon">🎯</div>
            <div class="gap-content">
              <strong style="color: var(--primary-emerald-dark);">Investor Benchmark MAO: <span id="flip-mao-price">$195,000</span></strong>
              <p id="flip-mao-subtext" style="color: var(--text-secondary);">Your $220k purchase price is within 12% of the standard 70% investor rule-of-thumb.</p>
            </div>
          </div>

          <!-- 4 Core Performance Metrics -->
          <div class="console-metrics-grid">
            <div class="console-metric-tile">
              <span class="tile-label">Cash-on-Cash ROI</span>
              <span class="tile-value text-emerald" id="flip-coc-roi">41.2%</span>
              <span class="tile-note">Profit / Total cash invested</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Annualized Return</span>
              <span class="tile-value text-teal" id="flip-annual-roi">82.4%</span>
              <span class="tile-note">Prorated to 12 months</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Cash Capital Required</span>
              <span class="tile-value" id="flip-cash-required">$90,320</span>
              <span class="tile-note">Down payment + points + holding</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Financing & Holding</span>
              <span class="tile-value" id="flip-total-holding" style="color: #ef4444;">$20,780</span>
              <span class="tile-note">Points + interest + carrying</span>
            </div>
          </div>

          <!-- Donut Breakdown -->
          <div class="cashflow-visual-card">
            <div class="cashflow-title-row">
              <span class="cashflow-heading">Capital Allocation Breakdown</span>
              <span class="cashflow-income-tag" id="flip-tag-profit">Net Profit: $37.2k</span>
            </div>

            <div class="cashflow-chart-wrap" style="position: relative; height: 210px; width: 100%;">
              <canvas id="flip-chart-canvas"></canvas>
            </div>

            <div class="cashflow-legend-grid">
              <div class="cf-legend-item"><span class="cf-dot" style="background:#09131f;"></span> <span>Purchase (<strong id="cf-flip-purch">$220k</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#f59e0b;"></span> <span>Rehab (<strong id="cf-flip-rehab">$50k</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#ef4444;"></span> <span>Holding & Loans (<strong id="cf-flip-holding">$21k</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#0d9488;"></span> <span>Selling Overhead (<strong id="cf-flip-selling">$28k</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#059669;"></span> <span>Net Profit (<strong id="cf-flip-profit">$37.2k</strong>)</span></div>
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
