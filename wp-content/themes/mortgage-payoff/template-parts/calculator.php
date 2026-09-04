<?php
/**
 * Template Part: EquityPace Mortgage Early Payoff Intelligence Console
 * Compliant with 2026 U.S. Mortgage Benchmarks & Implementation Rules.
 * Benchmark Rate: 6.71% (30-Yr Fixed Freddie Mac, Sep 3, 2026).
 */
$benchmarks = EquityPace_Benchmarks::get_all();
$rate30 = $benchmarks['mortgageRates']['fixed30']['rate'];
$rate15 = $benchmarks['mortgageRates']['fixed15']['rate'];
$rateDate = $benchmarks['mortgageRates']['fixed30']['date'];
$rateBadge = $benchmarks['mortgageRates']['fixed30']['badge'];
$payoffDefaults = $benchmarks['mortgagePayoff'];
?>
<section class="calculator-main-section" id="calculator-section">
  <div class="container">

    <!-- Top Strategy Switcher: Modern Segmented Navigation -->
    <div class="strategy-switcher-container">
      <div class="strategy-switcher-header">
        <span class="strategy-badge">Select Strategy</span>
        <h2 class="strategy-heading">Choose Your Acceleration Method</h2>
      </div>
      <div class="strategy-segmented-bar" role="tablist">
        <button type="button" class="strategy-tab-btn active" id="btn-mode-years" role="tab" aria-selected="true">
          <span class="tab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          </span>
          <span class="tab-text">
            <strong>Target Payoff Horizon</strong>
            <small>Pay off in X years</small>
          </span>
        </button>

        <button type="button" class="strategy-tab-btn" id="btn-mode-monthly" role="tab" aria-selected="false">
          <span class="tab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
          </span>
          <span class="tab-text">
            <strong>Extra Monthly Principal</strong>
            <small>Add extra to monthly payment</small>
          </span>
        </button>

        <button type="button" class="strategy-tab-btn" id="btn-mode-irregular" role="tab" aria-selected="false">
          <span class="tab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
          </span>
          <span class="tab-text">
            <strong>Scheduled Lump Sums</strong>
            <small>Bonuses, windfalls & refunds</small>
          </span>
        </button>
      </div>
    </div>

    <!-- Main Studio Grid -->
    <div class="calc-studio-grid">

      <!-- Left Console: Interactive Input Form -->
      <div class="studio-input-card">
        <div class="input-card-header">
          <div class="card-icon-badge">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <div>
            <h3 class="input-card-title">Loan Parameters</h3>
            <p class="input-card-desc">Enter your mortgage details to model compound principal acceleration.</p>
          </div>
        </div>

        <form id="mortgage-calc-form" onsubmit="return false;">

          <!-- 1. Original Mortgage Amount (Example Default) -->
          <div class="studio-field-group">
            <label class="studio-label" for="input-orig-amount">
              Original Mortgage Amount
              <span class="tooltip-trigger">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                <span class="tooltip-popover">The initial principal loan amount when your mortgage originated.</span>
              </span>
            </label>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="input-orig-amount" class="studio-input" value="300,000" inputmode="numeric">
            </div>
          </div>

          <!-- 2. Example Current Balance -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label" for="input-current-balance">
                Example Current Balance
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">The principal you currently still owe. This may differ from your lender’s official payoff quote.</span>
                </span>
              </label>
            </div>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="input-current-balance" class="studio-input" value="265,000" inputmode="numeric">
            </div>

            <!-- Official Payoff Quote Explanatory Notice -->
            <p class="studio-tip-text" style="font-size: 11.5px; color: var(--text-muted, #64748b); margin: 6px 0 0; line-height: 1.45;">
              <span style="color: #0284c7; font-weight: 600;">Note:</span> Your current principal balance may differ from your lender’s official payoff quote. A payoff quote may include accrued interest, fees, and other loan-specific charges.
            </p>

            <!-- Quick Balance Shortcuts -->
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-balance-preset" data-amount="175000">$175k</button>
              <button type="button" class="quick-chip js-balance-preset active" data-amount="265000">$265k (Example)</button>
              <button type="button" class="quick-chip js-balance-preset" data-amount="350000">$350k</button>
              <button type="button" class="quick-chip js-balance-preset" data-amount="500000">$500k</button>
            </div>
          </div>

          <!-- 3. Example Remaining Term & Interest Rate Grid -->
          <div class="studio-two-col">
            <!-- Example Remaining Loan Term with Interactive Pills -->
            <div class="studio-field-group">
              <label class="studio-label" for="input-loan-term">
                Example Remaining Term
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">How many years are left until your scheduled final mortgage payment.</span>
                </span>
              </label>
              <select id="input-loan-term" style="display: none;">
                <option value="30">30</option>
                <option value="25" selected>25</option>
                <option value="20">20</option>
                <option value="15">15</option>
                <option value="10">10</option>
              </select>
              <div class="term-pills-selector" id="term-pills-container">
                <button type="button" class="term-pill-btn" data-term="30">30Y</button>
                <button type="button" class="term-pill-btn active" data-term="25">25Y</button>
                <button type="button" class="term-pill-btn" data-term="20">20Y</button>
                <button type="button" class="term-pill-btn" data-term="15">15Y</button>
                <button type="button" class="term-pill-btn" data-term="10">10Y</button>
              </div>
            </div>

            <!-- Interest Rate Input with Live 2026 Benchmark Label -->
            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="input-interest-rate">
                  Interest Rate (%)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">The annual interest rate currently charged on your mortgage.</span>
                  </span>
                </label>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="input-interest-rate" class="studio-input" value="<?php echo esc_attr( $rate30 ); ?>" step="0.01" min="0.0" max="25">
                <span class="studio-unit">%</span>
              </div>
              <div class="quick-shortcuts-row">
                <button type="button" class="quick-chip js-rate-preset active" data-rate="<?php echo esc_attr( $rate30 ); ?>">30Y (<?php echo esc_html( $rate30 ); ?>%)</button>
                <button type="button" class="quick-chip js-rate-preset" data-rate="<?php echo esc_attr( $rate15 ); ?>">15Y (<?php echo esc_html( $rate15 ); ?>%)</button>
              </div>
              <span class="benchmark-date-tag" style="font-size: 10px; color: #0284c7; display: block; margin-top: 4px;">
                ⚡ Market benchmark updated September 3, 2026 (Freddie Mac)
              </span>
            </div>
          </div>

          <!-- 4. Dynamic Strategy Parameter: Target Horizon (Mode 1) -->
          <div class="studio-field-group" id="group-target-years">
            <div class="studio-label-row">
              <label class="studio-label" for="input-target-years">Target Payoff Horizon (Years from Today)</label>
            </div>
            <div class="studio-input-wrap">
              <input type="text" id="input-target-years" class="studio-input" value="15">
              <div class="stepper-controls">
                <button type="button" class="stepper-btn" data-target="input-target-years" data-step="-1" aria-label="Decrease years">−</button>
                <button type="button" class="stepper-btn" data-target="input-target-years" data-step="1" aria-label="Increase years">+</button>
              </div>
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Target:</span>
              <button type="button" class="quick-chip js-target-preset" data-years="7">7 Yrs</button>
              <button type="button" class="quick-chip js-target-preset" data-years="10">10 Yrs</button>
              <button type="button" class="quick-chip js-target-preset active" data-years="15">15 Yrs</button>
              <button type="button" class="quick-chip js-target-preset" data-years="20">20 Yrs</button>
            </div>
          </div>

          <!-- 5. Dynamic Strategy Parameter: Extra Monthly Principal (Mode 2) -->
          <div class="studio-field-group" id="group-additional-monthly" style="display: none;">
            <div class="studio-label-row">
              <label class="studio-label" for="input-add-monthly">
                Extra Monthly Principal
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Additional money you plan to apply toward mortgage principal each month.</span>
                </span>
              </label>
            </div>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="input-add-monthly" class="studio-input" value="200">
              <div class="stepper-controls">
                <button type="button" class="stepper-btn" data-target="input-add-monthly" data-step="-50" aria-label="Decrease extra payment">−</button>
                <button type="button" class="stepper-btn" data-target="input-add-monthly" data-step="50" aria-label="Increase extra payment">+</button>
              </div>
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-boost-preset" data-boost="0">$0</button>
              <button type="button" class="quick-chip js-boost-preset" data-boost="50">+$50</button>
              <button type="button" class="quick-chip js-boost-preset" data-boost="100">+$100</button>
              <button type="button" class="quick-chip js-boost-preset active" data-boost="200">+$200</button>
              <button type="button" class="quick-chip js-boost-preset" data-boost="250">+$250</button>
              <button type="button" class="quick-chip js-boost-preset" data-boost="500">+$500</button>
              <button type="button" class="quick-chip js-boost-preset" data-boost="1000">+$1,000</button>
            </div>
          </div>

          <!-- 6. Dynamic Strategy Parameter: Irregular & Lump Sums Manager (Mode 3) -->
          <div class="calc-irregular-group" id="group-irregular-payments" style="display: none;">
            <div class="calc-label-row">
              <label class="studio-label">
                Scheduled Lump Sums & Extra Payments
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">A one-time or recurring extra payment applied directly toward principal on a specific month or date.</span>
                </span>
              </label>
            </div>

            <!-- Presets: $1k, $3k, $5k, $10k, $25k, $50k -->
            <div class="irregular-chips-wrap">
              <span class="chips-label">Lump-Sum Presets:</span>
              <button type="button" class="chip-preset-btn" data-type="one-time" data-amount="1000" data-month="6" data-label="Bonus">+$1k</button>
              <button type="button" class="chip-preset-btn" data-type="one-time" data-amount="3000" data-month="6" data-label="Commission">+$3k</button>
              <button type="button" class="chip-preset-btn" data-type="annual" data-amount="5000" data-month="12" data-label="Annual Bonus">+$5k Annual</button>
              <button type="button" class="chip-preset-btn" data-type="one-time" data-amount="10000" data-month="6" data-label="Tax Refund">+$10k Refund</button>
              <button type="button" class="chip-preset-btn" data-type="one-time" data-amount="25000" data-month="12" data-label="Windfall">+$25k</button>
              <button type="button" class="chip-preset-btn" data-type="one-time" data-amount="50000" data-month="12" data-label="Lump Sum">+$50k</button>
            </div>

            <!-- Dynamic Payments List -->
            <div id="irregular-payments-list" class="irregular-payments-list">
              <!-- Rendered by calculator.js -->
            </div>

            <button type="button" id="btn-add-irregular-payment" class="btn btn-outline btn-add-payment">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
              <span>+ Add Scheduled Payment</span>
            </button>
          </div>

          <!-- 7. Optional Prepayment Penalty Field -->
          <div class="studio-field-group" style="margin-top: 14px;">
            <label class="studio-label" for="input-prepayment-penalty">
              Optional Prepayment Penalty ($)
              <span class="tooltip-trigger">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                <span class="tooltip-popover">Most modern U.S. conventional, FHA, and VA loans have $0 prepayment penalties. Enter an amount only if your specific loan documents specify a prepayment charge.</span>
              </span>
            </label>
            <div class="studio-input-wrap">
              <span class="studio-curr">$</span>
              <input type="text" id="input-prepayment-penalty" class="studio-input" value="0">
            </div>
          </div>

          <!-- Servicer Warning & Prepayment Notice -->
          <div class="servicer-warning-box" style="margin-top: 16px; padding: 12px 14px; background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.25); border-radius: 8px; font-size: 11.5px; line-height: 1.45; color: #92400e;">
            Confirm that your mortgage servicer applies extra payments to principal. Some loans may contain prepayment penalties or other restrictions. Check your loan documents or contact your lender.
          </div>

          <div class="studio-actions-wrap" style="margin-top: 18px;">
            <button type="button" id="btn-calculate-now" class="btn btn-primary btn-calculate-studio">
              <span>Recalculate Payoff</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Right Console: High-Impact Financial Freedom Console -->
      <div class="studio-results-console">
        <div class="console-card">

          <!-- Top Freedom Badge -->
          <div class="console-header-badge">
            <span class="pulse-dot"></span>
            <span>Payoff Acceleration Impact</span>
          </div>

          <!-- Main Executive Savings Headline -->
          <h2 class="console-headline" id="res-time-saved-headline">
            You'll pay off your loan <span class="highlight">sooner!</span>
          </h2>
          <p class="console-subheadline" id="res-time-saved-text">
            Calculating savings breakdown...
          </p>

          <!-- Interactive Graphical Timeline Bar -->
          <div class="timeline-visual-box">
            <div class="timeline-labels-row">
              <span class="timeline-tag">Loan Timeline Comparison</span>
              <span class="timeline-cut-badge" id="timeline-years-cut">⚡ Calculating...</span>
            </div>
            <div class="timeline-track">
              <div class="timeline-fill" id="timeline-progress-fill" style="width: 50%;"></div>
            </div>
            <div class="timeline-endpoints">
              <div>
                <small>EquityPace Accelerated</small>
                <strong id="timeline-early-date">--</strong>
              </div>
              <div style="text-align: right;">
                <small>Original Baseline</small>
                <strong id="timeline-orig-date">--</strong>
              </div>
            </div>
          </div>

          <!-- Compound Interest Efficiency Callout -->
          <div class="efficiency-callout-box">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
            <span id="efficiency-callout-text">Every $1 extra in principal eliminates interest and directly accelerates your mortgage freedom.</span>
          </div>

          <!-- 6 Core Financial Metrics Grid (Section 3 Compliant) -->
          <div class="console-metrics-grid" style="grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));">
            <div class="console-metric-tile">
              <span class="tile-label">Total Interest Saved</span>
              <span class="tile-value text-emerald" id="res-total-interest-saved">$0</span>
              <span class="tile-note">Kept in your pocket</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Time Saved</span>
              <span class="tile-value" id="res-time-saved-metric" style="color: #0ea5e9;">--</span>
              <span class="tile-note" id="res-months-saved-sub">0 months</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">New Payoff Date</span>
              <span class="tile-value" id="res-new-payoff-date" style="font-size: 17px;">--</span>
              <span class="tile-note">Accelerated maturity</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Original Baseline Date</span>
              <span class="tile-value" id="res-orig-payoff-date" style="font-size: 17px; color: #64748b;">--</span>
              <span class="tile-note">Without extra principal</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">New Remaining Interest</span>
              <span class="tile-value" id="res-new-total-interest">$0</span>
              <span class="tile-note" id="res-orig-interest-sub">Orig: $0</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Extra Principal Paid</span>
              <span class="tile-value text-teal" id="res-extra-total">$0</span>
              <span class="tile-note">Direct principal basis</span>
            </div>
          </div>

          <!-- Utility Action Buttons -->
          <div class="console-actions-row">
            <a href="#amortization-schedule-section" id="btn-scroll-to-schedule" class="btn btn-primary btn-console-primary">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
              <span>Explore Amortization Schedule</span>
            </a>

            <button type="button" id="btn-print-summary" class="btn btn-outline btn-console-secondary" aria-label="Print or save calculation summary">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
              <span>Print / Save</span>
            </button>
          </div>

        </div>
      </div>

    </div>

    <!-- Standard Educational Methodology Disclaimer (Rule 51) -->
    <?php echo EquityPace_Benchmarks::render_disclaimer(); ?>

  </div>
</section>
