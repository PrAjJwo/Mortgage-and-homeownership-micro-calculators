<?php
/**
 * Template Part: Amortization Schedule & Chart Component
 */
?>
<section class="amortization-section" id="amortization-schedule-section">
  <div class="container">

    <div class="amortization-header">
      <h2 class="amortization-title">Amortization Schedule</h2>
      <p class="amortization-subtitle">See a breakdown of payments over the life of your loan and visualize your acceleration timeline.</p>
    </div>

    <!-- Toggle Switcher Pills -->
    <div class="amort-switcher-wrap">
      <div class="amort-switcher">
        <button type="button" class="amort-tab-btn active" id="tab-chart-balance">
          Loan Balance
        </button>
        <button type="button" class="amort-tab-btn" id="tab-chart-payment">
          Payment Breakdown
        </button>
      </div>
    </div>

    <!-- Visual Chart & KPI Metrics Card -->
    <div class="amort-content-card">
      <div class="chart-header">
        <h3 class="chart-title">Timeline Comparison</h3>
        <div class="chart-legend" id="chart-legend-container">
          <!-- Populated dynamically by calculator.js -->
        </div>
      </div>

      <div class="chart-container-box">
        <canvas id="amortization-chart-canvas"></canvas>
      </div>

      <!-- KPI Summary Cards -->
      <div class="amort-kpis-grid">
        <div class="kpi-card">
          <div class="kpi-label">Early Payoff Savings</div>
          <div class="kpi-num text-green" id="kpi-savings">$0</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-label">Total Extra & Lump Sums</div>
          <div class="kpi-num" id="kpi-extra-total" style="color: var(--primary-blue);">$0</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-label">Current Balance Basis</div>
          <div class="kpi-num" id="kpi-remaining">$0</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-label">Total Principal to Pay</div>
          <div class="kpi-num" id="kpi-principal">$0</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-label">Total Interest Paid</div>
          <div class="kpi-num" id="kpi-interest">$0</div>
        </div>
      </div>
    </div>

    <!-- Annual Amortization Table -->
    <div style="margin-top: 48px;">
      <h3 style="font-size: 22px; font-weight: 700; color: var(--primary-navy); margin-bottom: 16px;">
        Annual Amortization Table
      </h3>
      <div class="table-responsive-wrapper">
        <table class="amort-table">
          <thead>
            <tr>
              <th>Year</th>
              <th>Starting Balance</th>
              <th>Principal Paid</th>
              <th>Interest Paid</th>
              <th>Extra Payment</th>
              <th>Total Payment</th>
              <th>Ending Balance</th>
            </tr>
          </thead>
          <tbody id="amort-table-body">
            <!-- Populated dynamically by calculator.js -->
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
