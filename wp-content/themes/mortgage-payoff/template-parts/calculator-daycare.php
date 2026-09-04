<?php
/**
 * Template Part: Home Affordability Calculator Including Daycare Costs
 * EquityPace Family Wealth Intelligence
 * Updated for 2026 U.S. Real Estate & Mortgage Guidelines.
 */
$benchmarks = EquityPace_Benchmarks::get_all();
$rate30 = $benchmarks['mortgageRates']['fixed30']['rate'];
$rateBadge = $benchmarks['mortgageRates']['fixed30']['badge'];
$childcareDef = $benchmarks['childcare']['nationalBenchmarkMonthly'];
$propertyTaxFallback = $benchmarks['propertyTax']['nationalFallbackRate'];
$insuranceDefault = $benchmarks['homeownersInsurance']['genericAnnualDefault'];
$takeHomeDefault = $benchmarks['takeHomePay']['defaultRatio'];
?>
<section class="daycare-affordability-section" id="daycare-affordability-section">
  <div class="container">

    <!-- Section Header -->
    <div class="daycare-header">
      <span class="daycare-badge">Family Wealth Intelligence</span>
      <h2 class="daycare-title">Home Affordability Calculator Including Daycare Costs</h2>
      <p class="daycare-subtitle">
        Standard mortgage lenders completely ignore daycare expenses when issuing pre-approvals. Use our dual-perspective model to see what a lender's traditional guideline allows vs. what your family can realistically afford without becoming house-poor.
      </p>
    </div>

    <!-- Main Studio Grid -->
    <div class="calc-studio-grid">

      <!-- Left Console: Family Financial Profile & Childcare Parameters -->
      <div class="studio-input-card">
        <div class="input-card-header">
          <div class="card-icon-badge" style="background-color: var(--primary-emerald-light); color: var(--primary-emerald);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
              <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
          </div>
          <div>
            <h3 class="input-card-title">Family Financial Profile</h3>
            <p class="input-card-desc">Enter your household income, childcare costs, and current obligations.</p>
          </div>
        </div>

        <form id="daycare-calc-form" onsubmit="return false;">

          <!-- 1. Gross Annual Household Income -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label" for="input-household-income">
                Annual Household Gross Income
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Total combined pretax annual income for all borrowers on the mortgage application.</span>
                </span>
              </label>
            </div>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="input-household-income" class="studio-input" value="140,000" inputmode="numeric">
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-income-preset" data-income="90000">$90k</button>
              <button type="button" class="quick-chip js-income-preset active" data-income="140000">$140k</button>
              <button type="button" class="quick-chip js-income-preset" data-income="180000">$180k</button>
              <button type="button" class="quick-chip js-income-preset" data-income="240000">$240k</button>
            </div>
          </div>

          <!-- 2. Cash Down Payment -->
          <div class="studio-field-group">
            <label class="studio-label" for="input-down-payment">Available Down Payment</label>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="input-down-payment" class="studio-input" value="60,000" inputmode="numeric">
            </div>
          </div>

          <!-- 3. Daycare & Childcare Specifics Box -->
          <div class="daycare-sub-card">
            <div class="sub-card-title">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <circle cx="12" cy="8" r="4"></circle>
                <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"></path>
              </svg>
              <span>Childcare &amp; Daycare Expenses</span>
            </div>

            <!-- Number of Children in Daycare -->
            <div class="studio-field-group">
              <label class="studio-label">Children Currently in Paid Childcare</label>
              <div class="kids-pills-selector" id="kids-pills-container">
                <button type="button" class="kids-pill-btn" data-kids="0">0 (None)</button>
                <button type="button" class="kids-pill-btn active" data-kids="1">1 Child</button>
                <button type="button" class="kids-pill-btn" data-kids="2">2 Children</button>
                <button type="button" class="kids-pill-btn" data-kids="3">3+ Children</button>
              </div>
            </div>

            <!-- Monthly Cost per Child (2026 U.S. Benchmark) -->
            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="input-daycare-per-child">
                  Monthly Cost per Child
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Average monthly tuition for daycare, infant care, preschool, or nanny share. Overall national benchmark is $13,184/yr ($1,099/mo).</span>
                  </span>
                </label>
                <span class="total-daycare-indicator">Total: <strong id="indicator-total-daycare">$1,100/mo</strong></span>
              </div>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="input-daycare-per-child" class="studio-input" value="1,100" inputmode="numeric">
              </div>
              <div class="quick-shortcuts-row">
                <span class="quick-label">Presets:</span>
                <button type="button" class="quick-chip js-daycare-preset" data-cost="750">$750 (Part-Time)</button>
                <button type="button" class="quick-chip js-daycare-preset active" data-cost="1100">$1,100 (U.S. Benchmark)</button>
                <button type="button" class="quick-chip js-daycare-preset" data-cost="1300">$1,300 (Infant Center)</button>
                <button type="button" class="quick-chip js-daycare-preset" data-cost="1440">$1,440 (Listed Rate)</button>
                <button type="button" class="quick-chip js-daycare-preset" data-cost="2000">$2,000 (Metro Scenario)</button>
                <button type="button" class="quick-chip js-daycare-preset" data-cost="2750">$2,750 (High-Cost Metro)</button>
                <button type="button" class="quick-chip js-daycare-preset" data-cost="3500">$3,500 (Urban Private)</button>
              </div>
            </div>

            <!-- Years until childcare cost changes or ends -->
            <div class="studio-field-group" style="margin-bottom: 0;">
              <label class="studio-label" for="input-years-daycare">
                Years until childcare cost changes or ends
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">How long until public school or childcare tuition ends. Note: Kindergarten does not necessarily eliminate every childcare cost (e.g. after-school programs, summer camps).</span>
                </span>
              </label>
              <div class="term-pills-selector" id="kindergarten-pills-container">
                <button type="button" class="term-pill-btn" data-years="1">1 Yr</button>
                <button type="button" class="term-pill-btn" data-years="2">2 Yrs</button>
                <button type="button" class="term-pill-btn active" data-years="3">3 Yrs</button>
                <button type="button" class="term-pill-btn" data-years="4">4 Yrs</button>
                <button type="button" class="term-pill-btn" data-years="5">5 Yrs</button>
              </div>
            </div>
          </div>

          <!-- 4. Other Debts & Mortgage Parameters Grid -->
          <div class="studio-two-col" style="margin-top: 22px;">
            <div class="studio-field-group">
              <label class="studio-label" for="input-monthly-debts">
                Other Monthly Debts
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Car loans, student loans, and minimum monthly credit card payments.</span>
                </span>
              </label>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="input-monthly-debts" class="studio-input" value="500">
              </div>
            </div>

            <div class="studio-field-group">
              <label class="studio-label" for="input-daycare-rate">
                Mortgage Rate (%)
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Fixed annual mortgage interest rate for a 30-year loan.</span>
                </span>
              </label>
              <div class="studio-input-wrap">
                <input type="number" id="input-daycare-rate" class="studio-input" value="<?php echo esc_attr( $rate30 ); ?>" step="0.01" min="0.1" max="20">
                <span class="studio-unit">%</span>
              </div>
              <span class="benchmark-date-tag" style="font-size: 10px; color: #0284c7; display: block; margin-top: 3px;">
                ⚡ <?php echo esc_html( $rateBadge ); ?>
              </span>
            </div>
          </div>

          <!-- 5. Taxes, Insurance & Take-Home Settings (Section 6, 7, 10 Compliant) -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <label class="studio-label" for="input-property-tax-rate">
                Property Tax Rate (%/yr)
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">National fallback estimate is 0.90%. Enter your specific county or municipality rate if known.</span>
                </span>
              </label>
              <div class="studio-input-wrap">
                <input type="number" id="input-property-tax-rate" class="studio-input" value="<?php echo esc_attr( $propertyTaxFallback ); ?>" step="0.05" min="0.1" max="5.0">
                <span class="studio-unit">%</span>
              </div>
              <span style="font-size: 10px; color: #64748b;">National fallback estimate: 0.90%</span>
            </div>

            <div class="studio-field-group">
              <label class="studio-label" for="input-annual-insurance">
                Homeowners Insurance ($/yr)
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Home insurance premiums vary by state, ZIP code, replacement cost, deductible, and roof age. Generic default is $2,750/yr (~$229/mo).</span>
                </span>
              </label>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="input-annual-insurance" class="studio-input" value="2,750">
              </div>
              <span style="font-size: 10px; color: #64748b;">~$229/month estimate</span>
            </div>
          </div>

          <!-- 6. Take-Home Pay Ratio Selector (Section 10 Compliant) -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label">
                Estimated Take-Home Ratio
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Actual take-home pay depends on federal income tax, state and local taxes, filing status, payroll taxes, benefits, retirement contributions, deductions, and household circumstances.</span>
                </span>
              </label>
            </div>
            <div class="term-pills-selector" id="takehome-pills-container">
              <button type="button" class="term-pill-btn js-takehome-preset" data-ratio="70">70% Net</button>
              <button type="button" class="term-pill-btn js-takehome-preset active" data-ratio="75">75% Net (Default)</button>
              <button type="button" class="term-pill-btn js-takehome-preset" data-ratio="80">80% Net</button>
            </div>
            <input type="hidden" id="input-takehome-ratio" value="75">
          </div>

          <!-- 7. DTI Guidelines Selector (Section 13 Compliant) -->
          <div class="studio-field-group">
            <label class="studio-label">
              Lender DTI Guideline Scenario
              <span class="tooltip-trigger">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                <span class="tooltip-popover">DTI requirements vary by loan program, lender, credit profile, reserves, automated underwriting results, and other borrower factors. 28/36% is a traditional guideline, not a legal requirement.</span>
              </span>
            </label>
            <div class="term-pills-selector" id="dti-pills-container">
              <button type="button" class="term-pill-btn js-dti-preset active" data-dti="36">Traditional (36% DTI)</button>
              <button type="button" class="term-pill-btn js-dti-preset" data-dti="43">Moderate (43% DTI)</button>
              <button type="button" class="term-pill-btn js-dti-preset" data-dti="45">Manual (45% DTI)</button>
              <button type="button" class="term-pill-btn js-dti-preset" data-dti="50">Automated (50% DTI)</button>
            </div>
            <input type="hidden" id="input-lender-dti" value="36">
          </div>

          <div class="studio-actions-wrap" style="margin-top: 20px;">
            <button type="button" id="btn-recalc-daycare" class="btn btn-primary btn-calculate-studio">
              <span>Recalculate Family Affordability</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Right Console: Dual-Perspective Family Affordability Console -->
      <div class="studio-results-console">
        <div class="console-card">

          <!-- Top Badge -->
          <div class="console-header-badge">
            <span class="pulse-dot"></span>
            <span>Dual-Perspective Analysis</span>
          </div>

          <!-- Main Safe Price Headline -->
          <h2 class="console-headline" id="res-safe-headline">
            Safe Family Home Price: <span class="highlight" id="res-safe-home-price">$435,000</span>
          </h2>
          <p class="console-subheadline" id="res-safe-subtext">
            Suggested household budgeting target (max 33% of take-home pay) with a recommended living buffer to prevent becoming house-poor while paying active childcare tuition.
          </p>

          <!-- The Daycare Purchasing Gap Alert Box -->
          <div class="daycare-gap-alert" id="res-daycare-gap-alert">
            <div class="gap-icon">⚠️</div>
            <div class="gap-content">
              <strong>The Daycare Purchasing Gap: <span id="res-gap-amount">-$145,000</span></strong>
              <p id="res-gap-text">Daycare reduces your realistic purchasing power compared to what a bank will approve under traditional guidelines.</p>
            </div>
          </div>

          <!-- Dual Side-by-Side Comparison Cards -->
          <div class="dual-affordability-grid">
            <div class="afford-tile bank-limit-tile">
              <span class="tile-tag">Traditional Affordability Guideline (28% Front / 36% Back)</span>
              <span class="tile-big-num" id="res-bank-home-price">$580,000</span>
              <span class="tile-detail" id="res-bank-dti-sub">36% DTI Scenario (Ignores Childcare)</span>
              <span class="tile-payment" id="res-bank-payment">$3,650/mo total</span>
            </div>

            <div class="afford-tile safe-limit-tile">
              <span class="tile-tag">Suggested Household Budgeting Target</span>
              <span class="tile-big-num text-emerald" id="res-safe-tile-price">$435,000</span>
              <span class="tile-detail">Factoring Daycare &amp; Living Buffer</span>
              <span class="tile-payment" id="res-safe-payment">$2,740/mo total</span>
            </div>
          </div>

          <!-- Monthly Cash Flow Visual Breakdown -->
          <div class="cashflow-visual-card">
            <div class="cashflow-title-row">
              <span class="cashflow-heading">Estimated Monthly Take-Home Breakdown</span>
              <span class="cashflow-income-tag" id="res-takehome-tag">Net Pay: $8,750/mo</span>
            </div>

            <div class="cashflow-chart-wrap" style="position: relative; height: 210px; width: 100%;">
              <canvas id="daycare-cashflow-chart"></canvas>
            </div>

            <div class="cashflow-legend-grid">
              <div class="cf-legend-item"><span class="cf-dot" style="background:#059669;"></span> <span>Mortgage P&amp;I (<strong id="cf-val-mortgage">$2,380</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#f59e0b;"></span> <span>Daycare (<strong id="cf-val-daycare">$1,100</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#0d9488;"></span> <span>Taxes &amp; Ins (<strong id="cf-val-escrow">$360</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#ef4444;"></span> <span>Other Debts (<strong id="cf-val-debts">$500</strong>)</span></div>
              <div class="cf-legend-item"><span class="cf-dot" style="background:#3b82f6;"></span> <span>Recommended monthly living-cost buffer (<strong id="cf-val-buffer">$4,410</strong>)</span></div>
            </div>
          </div>

          <!-- Post-Childcare Cash Flow Liberation Milestone Card -->
          <div class="kindergarten-milestone-card">
            <div class="k-card-header">
              <div class="k-icon">🎓</div>
              <div>
                <h4 class="k-title">Post-Childcare Cash Flow Liberation</h4>
                <p class="k-subtitle" id="res-k-timeline-text">When daycare tuition ends in 3 years</p>
              </div>
            </div>
            <div class="k-body">
              <div class="k-stat-row">
                <div class="k-stat">
                  <span class="k-stat-label">Unlocked Monthly Cash</span>
                  <span class="k-stat-val text-emerald" id="res-k-unlocked-cash">+$1,100/mo</span>
                </div>
                <div class="k-stat">
                  <span class="k-stat-label">Potential Time Shaved</span>
                  <span class="k-stat-val" id="res-k-years-cut">⚡ 10.4 Years</span>
                </div>
                <div class="k-stat">
                  <span class="k-stat-label">Interest Saved</span>
                  <span class="k-stat-val text-emerald" id="res-k-interest-saved">$112,500</span>
                </div>
              </div>
              <p class="k-explanation">
                Redirecting your former childcare tuition directly into your mortgage principal once expenses change or end turns a temporary family expense into accelerated home equity.
              </p>
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- Educational Methodology Disclaimer (Rule 51) -->
    <?php echo EquityPace_Benchmarks::render_disclaimer(); ?>

  </div>
</section>
