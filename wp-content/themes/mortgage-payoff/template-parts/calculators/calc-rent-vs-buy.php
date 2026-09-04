<?php
/**
 * Template Part: Rent Versus Buy with Closing Costs Calculator
 * Updated with 2026 U.S. Benchmarks
 */
?>
<section class="calculator-main-section" id="rent-vs-buy-section">
  <div class="container">

    <div class="calc-studio-grid">

      <!-- Left Console: Inputs -->
      <div class="studio-input-card">
        <div class="input-card-header">
          <div class="card-icon-badge" style="background-color: var(--primary-emerald-light); color: var(--primary-emerald);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
              <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
          </div>
          <div>
            <h3 class="input-card-title">Rent vs. Buy Parameters</h3>
            <p class="input-card-desc">Compare the 30-year wealth trajectories of homeownership vs. renting & stock investing.</p>
          </div>
        </div>

        <form id="rvb-form" onsubmit="return false;">

          <!-- Home Purchase Price -->
          <div class="studio-field-group">
            <label class="studio-label" for="rvb-home-price">Target Home Purchase Price</label>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="rvb-home-price" class="studio-input" value="450,000" inputmode="numeric">
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-rvb-price-preset" data-price="350000">$350k</button>
              <button type="button" class="quick-chip js-rvb-price-preset active" data-price="450000">$450k</button>
              <button type="button" class="quick-chip js-rvb-price-preset" data-price="600000">$600k</button>
              <button type="button" class="quick-chip js-rvb-price-preset" data-price="850000">$850k</button>
            </div>
          </div>

          <!-- Down Payment & Closing Costs -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <label class="studio-label" for="rvb-down-payment">Down Payment ($)</label>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="rvb-down-payment" class="studio-input" value="90,000" inputmode="numeric">
              </div>
            </div>

            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="rvb-closing-costs">
                  Buyer Closing Costs (%)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Upfront fees paid at closing (lender origination, appraisal, title policy, transfer taxes). Typical estimated range is 2% to 5% of purchase price (excluding down payment).</span>
                  </span>
                </label>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="rvb-closing-costs" class="studio-input" value="3.0" step="0.1" min="0" max="10">
                <span class="studio-unit">%</span>
              </div>
              <div class="quick-shortcuts-row">
                <span class="quick-label">Presets:</span>
                <button type="button" class="quick-chip js-rvb-closing-preset" data-val="2.0">2%</button>
                <button type="button" class="quick-chip js-rvb-closing-preset active" data-val="3.0">3% (Default)</button>
                <button type="button" class="quick-chip js-rvb-closing-preset" data-val="4.0">4%</button>
                <button type="button" class="quick-chip js-rvb-closing-preset" data-val="5.0">5%</button>
              </div>
            </div>
          </div>

          <!-- Mortgage Rate & Appreciation -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="rvb-interest-rate">
                  Mortgage Rate (%)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">The annual interest rate currently charged on your mortgage. Market benchmark updated Sep. 3, 2026.</span>
                  </span>
                </label>
                <span class="benchmark-date-badge">Benchmark Sep. 3, 2026</span>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="rvb-interest-rate" class="studio-input" value="6.71" step="0.01" min="1" max="15">
                <span class="studio-unit">%</span>
              </div>
              <div class="quick-shortcuts-row">
                <button type="button" class="quick-chip js-rvb-rate-preset active" data-val="6.71">30Y (6.71%)</button>
                <button type="button" class="quick-chip js-rvb-rate-preset" data-val="6.04">15Y (6.04%)</button>
              </div>
            </div>

            <div class="studio-field-group">
              <label class="studio-label" for="rvb-home-appreciation">Assumed Annual Home Appreciation (%)</label>
              <div class="studio-input-wrap">
                <input type="number" id="rvb-home-appreciation" class="studio-input" value="3.0" step="0.1" min="0" max="15">
                <span class="studio-unit">%</span>
              </div>
              <div class="quick-shortcuts-row">
                <span class="quick-label">Presets:</span>
                <button type="button" class="quick-chip js-rvb-apprec-preset" data-val="2.0">2%</button>
                <button type="button" class="quick-chip js-rvb-apprec-preset active" data-val="3.0">3% (Default)</button>
                <button type="button" class="quick-chip js-rvb-apprec-preset" data-val="4.0">4%</button>
                <button type="button" class="quick-chip js-rvb-apprec-preset" data-val="5.0">5%</button>
              </div>
            </div>
          </div>

          <!-- Homeowner Ongoing Expenses Sub-Card (Taxes, Insurance, Maintenance) -->
          <div class="daycare-sub-card" style="margin-top: 10px;">
            <div class="sub-card-title">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
              </svg>
              <span>Ongoing Homeownership Operating Costs</span>
            </div>

            <div class="studio-two-col">
              <div class="studio-field-group">
                <div class="studio-label-row">
                  <label class="studio-label" for="rvb-property-tax-rate">
                    Property Tax Rate (%/yr)
                    <span class="tooltip-trigger">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                      <span class="tooltip-popover">National fallback estimate is 0.90% per year. Property taxes vary significantly by state and local jurisdiction.</span>
                    </span>
                  </label>
                </div>
                <div class="studio-input-wrap">
                  <input type="number" id="rvb-property-tax-rate" class="studio-input" value="0.90" step="0.05" min="0" max="5">
                  <span class="studio-unit">%</span>
                </div>
              </div>

              <div class="studio-field-group">
                <div class="studio-label-row">
                  <label class="studio-label" for="rvb-home-insurance">
                    Homeowners Insurance ($/yr)
                    <span class="tooltip-trigger">
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                      <span class="tooltip-popover">National generic benchmark is $2,750/yr (~$229/mo). Varies by state, replacement cost, deductible, and weather exposure.</span>
                    </span>
                  </label>
                </div>
                <div class="studio-input-wrap">
                  <span class="studio-curr">$</span>
                  <input type="text" id="rvb-home-insurance" class="studio-input" value="2,750" inputmode="numeric">
                </div>
              </div>
            </div>

            <div class="studio-two-col" style="margin-bottom: 0;">
              <div class="studio-field-group">
                <label class="studio-label" for="rvb-maintenance-rate">Annual Maintenance Planning (%/yr)</label>
                <div class="studio-input-wrap">
                  <input type="number" id="rvb-maintenance-rate" class="studio-input" value="1.5" step="0.1" min="0.5" max="5">
                  <span class="studio-unit">%</span>
                </div>
                <div class="quick-shortcuts-row">
                  <span class="quick-label">Presets:</span>
                  <button type="button" class="quick-chip js-rvb-maint-preset" data-val="1.0">1.0% (Newer)</button>
                  <button type="button" class="quick-chip js-rvb-maint-preset active" data-val="1.5">1.5% (Avg)</button>
                  <button type="button" class="quick-chip js-rvb-maint-preset" data-val="2.0">2.0% (Older)</button>
                  <button type="button" class="quick-chip js-rvb-maint-preset" data-val="3.0">3.0% (High)</button>
                </div>
              </div>

              <div class="studio-field-group">
                <label class="studio-label" for="rvb-selling-costs-pct">Future Resale Selling Overhead (%)</label>
                <div class="studio-input-wrap">
                  <input type="number" id="rvb-selling-costs-pct" class="studio-input" value="7.0" step="0.5" min="0" max="15">
                  <span class="studio-unit">%</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Rental Comparison Section -->
          <div class="daycare-sub-card" style="margin-top: 10px;">
            <div class="sub-card-title">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
              </svg>
              <span>Rental & Investment Alternatives</span>
            </div>

            <div class="studio-two-col">
              <div class="studio-field-group">
                <label class="studio-label" for="rvb-monthly-rent">Comparable Monthly Rent</label>
                <div class="studio-input-wrap">
                  <span class="studio-curr">$</span>
                  <input type="text" id="rvb-monthly-rent" class="studio-input" value="2,400" inputmode="numeric">
                </div>
              </div>

              <div class="studio-field-group">
                <label class="studio-label" for="rvb-rent-inflation">Assumed Annual Rent Increase (%)</label>
                <div class="studio-input-wrap">
                  <input type="number" id="rvb-rent-inflation" class="studio-input" value="3.0" step="0.1" min="0" max="15">
                  <span class="studio-unit">%</span>
                </div>
                <div class="quick-shortcuts-row">
                  <span class="quick-label">Presets:</span>
                  <button type="button" class="quick-chip js-rvb-rentinfl-preset" data-val="2.0">2%</button>
                  <button type="button" class="quick-chip js-rvb-rentinfl-preset active" data-val="3.0">3% (Default)</button>
                  <button type="button" class="quick-chip js-rvb-rentinfl-preset" data-val="4.0">4%</button>
                  <button type="button" class="quick-chip js-rvb-rentinfl-preset" data-val="5.0">5%</button>
                </div>
              </div>
            </div>

            <div class="studio-field-group" style="margin-bottom: 0;">
              <div class="studio-label-row">
                <label class="studio-label" for="rvb-investment-return">
                  Assumed Annual Investment Return (%/yr)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Assumed return from investing saved down payment, closing costs, and monthly cash flow differences into a diversified index fund portfolio. Investment returns are uncertain and may be negative.</span>
                  </span>
                </label>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="rvb-investment-return" class="studio-input" value="7.0" step="0.5" min="1" max="15">
                <span class="studio-unit">%</span>
              </div>
              <div class="quick-shortcuts-row">
                <span class="quick-label">Presets:</span>
                <button type="button" class="quick-chip js-rvb-stock-preset" data-val="4.0">4% (Conservative)</button>
                <button type="button" class="quick-chip js-rvb-stock-preset" data-val="6.0">6% (Moderate)</button>
                <button type="button" class="quick-chip js-rvb-stock-preset active" data-val="7.0">7% (Default)</button>
                <button type="button" class="quick-chip js-rvb-stock-preset" data-val="9.0">9% (Aggressive)</button>
              </div>
              <p class="field-hint-text" style="margin-top: 6px; font-size: 0.78rem; color: var(--text-tertiary);">Note: Investment returns are uncertain and may be negative.</p>
            </div>
          </div>

          <div class="studio-actions-wrap">
            <button type="button" id="btn-recalc-rvb" class="btn btn-primary btn-calculate-studio">
              <span>Recalculate Rent vs. Buy</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Right Console: Break-Even & Wealth Crossover -->
      <div class="studio-results-console">
        <div class="console-card">

          <div class="console-header-badge">
            <span class="pulse-dot"></span>
            <span>Wealth Trajectory Analysis</span>
          </div>

          <h2 class="console-headline" id="rvb-headline">
            Buying becomes cheaper after <span class="highlight" id="rvb-crossover-years">4.5 Years</span>
          </h2>
          <p class="console-subheadline" id="rvb-subtext">
            Taking into account $13,500 upfront buyer closing costs (3%), property taxes, insurance, maintenance, and investment opportunity costs.
          </p>

          <!-- 10-Year Net Worth Comparison Tiles -->
          <div class="dual-affordability-grid">
            <div class="afford-tile safe-limit-tile">
              <span class="tile-tag">10-Year Homebuyer Net Worth</span>
              <span class="tile-big-num text-emerald" id="rvb-buy-nw">$268,400</span>
              <span class="tile-detail">Home Equity (Net of 7% Selling Costs)</span>
              <span class="tile-payment" id="rvb-buy-monthly">$2,890/mo (P&I + Escrow + Maint)</span>
            </div>

            <div class="afford-tile bank-limit-tile">
              <span class="tile-tag">10-Year Renter Net Worth</span>
              <span class="tile-big-num" id="rvb-rent-nw">$224,100</span>
              <span class="tile-detail">Compounded Investment Portfolio</span>
              <span class="tile-payment" id="rvb-rent-monthly">$2,860/mo (Inflated Rent)</span>
            </div>
          </div>

          <!-- Crossover Chart -->
          <div class="cashflow-visual-card">
            <div class="cashflow-title-row">
              <span class="cashflow-heading">30-Year Net Worth Crossover Curve</span>
              <span class="cashflow-income-tag" id="rvb-advantage-tag">Buying +$44,300 Ahead at Yr 10</span>
            </div>
            <div class="chart-container-box" style="height: 250px; margin-bottom: 10px;">
              <canvas id="rvb-chart-canvas"></canvas>
            </div>
            <div class="chart-legend" style="justify-content: center;">
              <div class="legend-item"><span class="legend-color" style="background:#059669;"></span> Homeowner Net Worth</div>
              <div class="legend-item"><span class="legend-color" style="background:#3b82f6;"></span> Renter + Stock Portfolio</div>
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
