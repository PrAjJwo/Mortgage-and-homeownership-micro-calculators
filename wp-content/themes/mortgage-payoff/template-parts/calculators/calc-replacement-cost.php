<?php
/**
 * Template Part: Home Replacement-Cost Calculator
 * Updated with 2026 U.S. Benchmarks
 */
?>
<section class="calculator-main-section" id="replacement-cost-section">
  <div class="container">

    <div class="calc-studio-grid">

      <!-- Left Console: Inputs -->
      <div class="studio-input-card">
        <div class="input-card-header">
          <div class="card-icon-badge" style="background-color: var(--primary-emerald-light); color: var(--primary-emerald);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M2 20h20"></path>
              <path d="M5 20V8l7-5 7 5v12"></path>
              <rect x="9" y="13" width="6" height="7"></rect>
            </svg>
          </div>
          <div>
            <h3 class="input-card-title">Dwelling Construction Profile</h3>
            <p class="input-card-desc">Estimate your structural rebuild cost (Coverage A) based on square footage and quality grade.</p>
          </div>
        </div>

        <form id="rebuild-form" onsubmit="return false;">

          <!-- Square Footage -->
          <div class="studio-field-group">
            <label class="studio-label" for="rebuild-sqft">Finished Living Area (Square Footage)</label>
            <div class="studio-input-wrap">
              <input type="number" id="rebuild-sqft" class="studio-input" value="2400" min="500" max="25000">
              <span class="studio-unit">Sq Ft</span>
            </div>
            <div class="quick-shortcuts-row">
              <span class="quick-label">Presets:</span>
              <button type="button" class="quick-chip js-rebuild-sqft-preset" data-sqft="1800">1,800</button>
              <button type="button" class="quick-chip js-rebuild-sqft-preset active" data-sqft="2400">2,400 (Avg)</button>
              <button type="button" class="quick-chip js-rebuild-sqft-preset" data-sqft="3200">3,200</button>
              <button type="button" class="quick-chip js-rebuild-sqft-preset" data-sqft="4500">4,500</button>
            </div>
          </div>

          <!-- Construction Quality / Grade (2026 6-Tier System) -->
          <div class="studio-field-group">
            <div class="studio-label-row">
              <label class="studio-label" for="rebuild-cost-persqft">
                Base Construction Cost ($/sq ft)
                <span class="tooltip-trigger">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                  <span class="tooltip-popover">Construction cost varies significantly by ZIP code, labor availability, building codes, site conditions, architecture, materials, contractor pricing and finish level. Neutral mid-range default is $200/sq ft.</span>
                </span>
              </label>
              <span class="total-daycare-indicator">Tier: <strong id="indicator-base-sqft">$200/sqft</strong></span>
            </div>
            <div class="studio-input-wrap" style="margin-bottom: 8px;">
              <span class="studio-curr">$</span>
              <input type="number" id="rebuild-cost-persqft" class="studio-input" value="200" min="100" max="1000">
              <span class="studio-unit">/sq ft</span>
            </div>
            <div class="quick-shortcuts-row" id="rebuild-tier-pills" style="flex-wrap: wrap;">
              <button type="button" class="quick-chip js-rebuild-tier-btn" data-cost="125">Economy ($125)</button>
              <button type="button" class="quick-chip js-rebuild-tier-btn" data-cost="150">Standard ($150)</button>
              <button type="button" class="quick-chip js-rebuild-tier-btn active" data-cost="200">Mid-Range ($200)</button>
              <button type="button" class="quick-chip js-rebuild-tier-btn" data-cost="300">Custom ($300)</button>
              <button type="button" class="quick-chip js-rebuild-tier-btn" data-cost="400">High-End ($400)</button>
              <button type="button" class="quick-chip js-rebuild-tier-btn" data-cost="475">Luxury ($475)</button>
            </div>
          </div>

          <!-- Foundation & Basement Cost Override -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <label class="studio-label" for="rebuild-foundation">Foundation Structure</label>
              <div class="studio-input-wrap">
                <select id="rebuild-foundation" class="studio-input" style="padding-left: 14px;">
                  <option value="slab">Concrete Slab</option>
                  <option value="crawlspace" selected>Crawlspace</option>
                  <option value="basement">Basement / Foundation Addition</option>
                </select>
              </div>
            </div>

            <div class="studio-field-group" id="group-basement-cost">
              <div class="studio-label-row">
                <label class="studio-label" for="rebuild-basement-cost">Estimated Basement Cost ($)</label>
              </div>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="rebuild-basement-cost" class="studio-input" value="50,000">
              </div>
              <div class="quick-shortcuts-row">
                <button type="button" class="quick-chip js-rebuild-base-preset" data-cost="25000">$25k</button>
                <button type="button" class="quick-chip js-rebuild-base-preset active" data-cost="50000">$50k</button>
                <button type="button" class="quick-chip js-rebuild-base-preset" data-cost="75000">$75k</button>
                <button type="button" class="quick-chip js-rebuild-base-preset" data-cost="100000">$100k</button>
              </div>
            </div>
          </div>

          <!-- Garage Construction & Debris Removal -->
          <div class="studio-two-col">
            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="rebuild-garage-cost">Estimated Garage Cost ($)</label>
              </div>
              <div class="studio-input-wrap">
                <span class="studio-curr">$</span>
                <input type="text" id="rebuild-garage-cost" class="studio-input" value="40,000">
              </div>
              <div class="quick-shortcuts-row">
                <button type="button" class="quick-chip js-rebuild-gar-preset" data-cost="0">$0 (None)</button>
                <button type="button" class="quick-chip js-rebuild-gar-preset" data-cost="25000">$25k (1-Car)</button>
                <button type="button" class="quick-chip js-rebuild-gar-preset active" data-cost="40000">$40k (2-Car)</button>
                <button type="button" class="quick-chip js-rebuild-gar-preset" data-cost="60000">$60k (3-Car)</button>
                <button type="button" class="quick-chip js-rebuild-gar-preset" data-cost="80000">$80k (Custom)</button>
              </div>
            </div>

            <div class="studio-field-group">
              <div class="studio-label-row">
                <label class="studio-label" for="rebuild-debris-pct">
                  Demolition & Debris (%)
                  <span class="tooltip-trigger">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    <span class="tooltip-popover">Typical planning range is 5% to 15% (default 10%). Includes demolition, debris hauling, landfill disposal, site prep, and environmental permits.</span>
                  </span>
                </label>
              </div>
              <div class="studio-input-wrap">
                <input type="number" id="rebuild-debris-pct" class="studio-input" value="10.0" step="1" min="5" max="25">
                <span class="studio-unit">%</span>
              </div>
            </div>
          </div>

          <div class="field-hint-text" style="font-size: 0.78rem; color: var(--text-tertiary); line-height: 1.45;">
            ℹ️ Construction cost varies significantly by ZIP code, labor availability, building codes, site conditions, architecture, materials, contractor pricing and finish level.
          </div>

          <div class="studio-actions-wrap">
            <button type="button" id="btn-recalc-rebuild" class="btn btn-primary btn-calculate-studio">
              <span>Calculate Replacement Cost</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Right Console: Coverage A Replacement Certificate -->
      <div class="studio-results-console">
        <div class="console-card">

          <div class="console-header-badge">
            <span class="pulse-dot"></span>
            <span>Coverage A Dwelling Valuation</span>
          </div>

          <h2 class="console-headline" id="rebuild-headline">
            Replacement Cost: <span class="highlight" id="rebuild-total-cost">$572,000</span>
          </h2>
          <p class="console-subheadline" id="rebuild-subtext">
            Recommended homeowner insurance dwelling limit (Coverage A) based on an estimated <strong id="rebuild-sqft-cost">$238/sq ft</strong> turnkey rebuild cost.
          </p>

          <!-- Crucial Insurance Disclosure Alert -->
          <div class="daycare-gap-alert" style="background-color: var(--primary-emerald-light); border-color: var(--primary-emerald-border);">
            <div class="gap-icon">💡</div>
            <div class="gap-content">
              <strong style="color: var(--primary-emerald-dark);">Replacement Cost vs. Real Estate Market Value</strong>
              <p style="color: var(--text-secondary); margin:0;">Dwelling replacement cost excludes the underlying land value (which does not burn down or blow away), while factoring in demolition, debris cleanup, and contemporary building code updates.</p>
            </div>
          </div>

          <!-- 4 Core Rebuild Cost Metrics -->
          <div class="console-metrics-grid">
            <div class="console-metric-tile">
              <span class="tile-label">Living Area Structure</span>
              <span class="tile-value text-emerald" id="rebuild-res-living">$480,000</span>
              <span class="tile-note">2,400 sq ft @ $200/sq ft</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Garage & Foundation</span>
              <span class="tile-value text-teal" id="rebuild-res-addons">$40,000</span>
              <span class="tile-note">Garage & foundation addons</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Debris Removal (10%)</span>
              <span class="tile-value" id="rebuild-res-debris">$52,000</span>
              <span class="tile-note">Site prep & disposal buffer</span>
            </div>

            <div class="console-metric-tile">
              <span class="tile-label">Effective Turnkey / Sq Ft</span>
              <span class="tile-value text-emerald" id="rebuild-res-per-sqft">$238</span>
              <span class="tile-note">Turnkey reconstruction</span>
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
