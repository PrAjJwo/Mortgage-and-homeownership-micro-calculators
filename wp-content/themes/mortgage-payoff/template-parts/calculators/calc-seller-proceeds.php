<?php
/**
 * Template Part: Seller Net-Proceeds Calculator
 * Updated with 2026 U.S. Benchmarks
 */
?>
<section class="calculator-main-section" id="seller-proceeds-section">
  <div class="container">

    <div class="calc-studio-grid">

      <!-- Left Console: Inputs -->
      <div class="studio-input-card">
        <div class="input-card-header">
          <div class="card-icon-badge" style="background-color: var(--primary-emerald-light); color: var(--primary-emerald);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <line x1="12" y1="1" x2="12" y2="23"></line>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
          </div>
          <div>
            <h3 class="input-card-title">Sale & Payoff Details</h3>
            <p class="input-card-desc">Enter your target sale price and existing liabilities to compute net closing proceeds.</p>
          </div>
        </div>

        <form id="seller-form" onsubmit="return false;">

          <!-- Sale Price -->
          <div class="studio-field-group">
            <label class="studio-label" for="seller-sale-price">Expected Home Sale Price</label>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="seller-sale-price" class="studio-input" value="500,000" inputmode="numeric">
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-seller-price-preset" data-price="350000">$350k</button>
              <button type="button" class="quick-chip js-seller-price-preset active" data-price="500000">$500k</button>
              <button type="button" class="quick-chip js-seller-price-preset" data-price="750000">$750k</button>
              <button type="button" class="quick-chip js-seller-price-preset" data-price="1000000">$1M</button>
            </div>
          </div>

          <!-- Existing Mortgage Balance -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label" for="seller-mortgage-balance">
                Existing Mortgage Payoff Balance
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Your current unpaid mortgage balance. The title/escrow company will wire this amount to your lender at closing. Payoff quotes may include accrued interest and servicer payoff statement fees.</span>
                </span>
              </label>
            </div>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="seller-mortgage-balance" class="studio-input" value="280,000" inputmode="numeric">
            </div>
          </div>

          <!-- Real Estate Broker Compensation -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label" for="seller-commission-pct">
                Estimated Broker Compensation (%)
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Real estate broker compensation is fully negotiable by law and varies by market, listing agreement, and brokerage business model. 5.0% is shown as an editable scenario example only.</span>
                </span>
              </label>
              <span class="total-daycare-indicator">Fee: <strong id="indicator-commission">$25,000</strong></span>
            </div>
            <div class="studio-input-wrap">
              <input type="number" id="seller-commission-pct" class="studio-input" value="5.0" step="0.25" min="0" max="10">
              <span class="studio-unit">%</span>
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-seller-comm-preset" data-comm="2.5">2.5%</button>
              <button type="button" class="quick-chip js-seller-comm-preset" data-comm="3.0">3.0%</button>
              <button type="button" class="quick-chip js-seller-comm-preset" data-comm="4.0">4.0%</button>
              <button type="button" class="quick-chip js-seller-comm-preset active" data-comm="5.0">5.0% (Example)</button>
              <button type="button" class="quick-chip js-seller-comm-preset" data-comm="6.0">6.0%</button>
            </div>
            <p class="field-hint-text" style="margin-top: 6px; font-size: 0.78rem; color: var(--text-tertiary);">Note: Real estate broker commissions are negotiable and not set by law.</p>
          </div>

          <!-- Closing Fees & Repairs -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="seller-closing-costs-pct">
                  Seller Title, Escrow & Transfer (%)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Actual seller closing costs vary by state, county, contract, title company, transfer-tax rules and local customs. Typical range is 1% to 2% (default 1.5%).</span>
                  </span>
                </label>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="seller-closing-costs-pct" class="studio-input" value="1.5" step="0.1" min="0" max="8">
                <span class="studio-unit">%</span>
              </div>
              <div class="quick-shortcuts-row">
                <span class="quick-label">Presets:</span>
                <button type="button" class="quick-chip js-seller-closing-preset" data-val="1.0">1.0%</button>
                <button type="button" class="quick-chip js-seller-closing-preset active" data-val="1.5">1.5%</button>
                <button type="button" class="quick-chip js-seller-closing-preset" data-val="2.0">2.0%</button>
              </div>
            </div>

            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="seller-repairs">
                  Optional Repair Credits / Concessions ($)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Optional seller credits negotiated after home inspection or closing assistance offered to the buyer. Defaults to $0.</span>
                  </span>
                </label>
              </div>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="seller-repairs" class="studio-input" value="0">
              </div>
              <div class="quick-shortcuts-row">
                <span class="quick-label">Presets:</span>
                <button type="button" class="quick-chip js-seller-concession-preset active" data-val="0">$0</button>
                <button type="button" class="quick-chip js-seller-concession-preset" data-val="2500">$2.5k</button>
                <button type="button" class="quick-chip js-seller-concession-preset" data-val="5000">$5k</button>
                <button type="button" class="quick-chip js-seller-concession-preset" data-val="10000">$10k</button>
                <button type="button" class="quick-chip js-seller-concession-preset" data-val="15000">$15k</button>
              </div>
            </div>
          </div>

          <div class="studio-actions-wrap">
            <button type="button" id="btn-recalc-seller" class="btn btn-primary btn-calculate-studio">
              <span>Recalculate Net Proceeds</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Right Console: Net Proceeds Waterfall -->
      <div class="studio-results-console">
        <div class="console-card">

          <div class="console-header-badge">
            <span class="pulse-dot"></span>
            <span>Net Closing Cash Summary</span>
          </div>

          <h2 class="console-headline" id="seller-headline">
            Net Cash to Seller: <span class="highlight" id="seller-net-proceeds">$187,500</span>
          </h2>
          <p class="console-subheadline" id="seller-subtext">
            Estimated net cash wired to your bank account after paying off your mortgage and all estimated closing costs.
          </p>

          <!-- 4 Core Metrics Grid -->
          <div class="console-metrics-grid">
            <div class="console-metric-tile">
              <span class="tile-label">Sale Price</span>
              <span class="tile-value" id="seller-res-sale-price">$500,000</span>
              <span class="tile-note">Gross contract price</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Mortgage Payoff</span>
              <span class="tile-value text-teal" id="seller-res-payoff">$280,000</span>
              <span class="tile-note">Existing balance cleared</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Total Selling Costs</span>
              <span class="tile-value" id="seller-res-total-costs" style="color: #ef4444;">$32,500</span>
              <span class="tile-note">Broker + fees + concessions</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Net Proceeds Ratio</span>
              <span class="tile-value text-emerald" id="seller-res-pct">37.5%</span>
              <span class="tile-note">Net cash / sale price</span>
            </div>
          </div>

          <!-- Proceeds Donut Chart -->
          <div class="cashflow-visual-card">
            <div class="cashflow-title-row">
              <span class="cashflow-heading">Sale Price Distribution</span>
              <span class="cashflow-income-tag" id="seller-tag-net">Net: $187,500</span>
            </div>

            <div class="cashflow-chart-wrap" style="position: relative; height: 210px; width: 100%;">
              <canvas id="seller-chart-canvas"></canvas>
            </div>

            <div class="cashflow-legend-grid">
              <div class="cf-legend-item"><span class="cf-dot" style="background:#059669;"></span> <span>Net Cash (<strong id="cf-sell-net">$187.5k</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#09131f;"></span> <span>Mortgage Payoff (<strong id="cf-sell-payoff">$280k</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#f59e0b;"></span> <span>Broker Compensation (<strong id="cf-sell-comm">$25k</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#ef4444;"></span> <span>Closing Fees & Credits (<strong id="cf-sell-fees">$7.5k</strong>)</span></div>
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
