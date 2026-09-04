/**
 * EquityPace — Mortgage Payoff Intelligence Console Engine
 * Core financial calculations, interactive timeline progress, and studio controls
 */

(function () {
  'use strict';

  // Benchmark defaults helper
  const benchmarks = window.EquityPaceBenchmarks || {};
  const payoffDefaults = benchmarks.mortgagePayoff || {};
  const rates = benchmarks.mortgageRates || {};

  // State
  const state = {
    mode: 'years', // 'years', 'monthly', or 'irregular'
    originalAmount: 300000,
    currentBalance: payoffDefaults.exampleBalance || 265000,
    originalTermYears: payoffDefaults.exampleTermYears || 25,
    interestRate: (rates.fixed30 && rates.fixed30.rate) || 6.71,
    targetPayoffYears: 15,
    additionalMonthly: payoffDefaults.defaultExtraMonthly || 200,
    prepaymentPenalty: payoffDefaults.defaultPenalty || 0,
    // Irregular & Lump-Sum Extra Payments
    irregularPayments: [
      { id: 1, type: 'annual', amount: 5000, month: 12, label: 'Annual Bonus' }
    ],
    nextIrregularId: 2,
    activeChartTab: 'balance', // 'balance' or 'payment'
    chartInstance: null
  };

  // DOM Elements cache
  let dom = {};

  function initElements() {
    dom = {
      btnModeYears: document.getElementById('btn-mode-years'),
      btnModeMonthly: document.getElementById('btn-mode-monthly'),
      btnModeIrregular: document.getElementById('btn-mode-irregular'),

      groupTargetYears: document.getElementById('group-target-years'),
      groupAdditionalMonthly: document.getElementById('group-additional-monthly'),
      groupIrregularPayments: document.getElementById('group-irregular-payments'),

      inputOriginalAmount: document.getElementById('input-orig-amount'),
      inputCurrentBalance: document.getElementById('input-current-balance'),
      inputLoanTerm: document.getElementById('input-loan-term'),
      inputInterestRate: document.getElementById('input-interest-rate'),
      inputTargetYears: document.getElementById('input-target-years'),
      inputAddMonthly: document.getElementById('input-add-monthly'),
      inputPrepaymentPenalty: document.getElementById('input-prepayment-penalty'),

      // Irregular payments list & controls
      irregularListContainer: document.getElementById('irregular-payments-list'),
      btnAddIrregularPayment: document.getElementById('btn-add-irregular-payment'),
      btnCalc: document.getElementById('btn-calculate-now'),

      // Results Console
      resTimeSavedHeadline: document.getElementById('res-time-saved-headline'),
      resTimeSavedText: document.getElementById('res-time-saved-text'),
      resMonthlyPayment: document.getElementById('res-monthly-payment'),
      resMonthlyNote: document.getElementById('res-monthly-note'),
      resTotalInterestSaved: document.getElementById('res-total-interest-saved'),
      resTimeSavedMetric: document.getElementById('res-time-saved-metric'),
      resMonthsSavedSub: document.getElementById('res-months-saved-sub'),
      resNewPayoffDate: document.getElementById('res-new-payoff-date'),
      resOrigPayoffDate: document.getElementById('res-orig-payoff-date'),
      resNewTotalInterest: document.getElementById('res-new-total-interest'),
      resOrigInterestSub: document.getElementById('res-orig-interest-sub'),
      resExtraTotal: document.getElementById('res-extra-total'),

      // Graphical Timeline Elements
      timelineFill: document.getElementById('timeline-progress-fill'),
      timelineYearsCut: document.getElementById('timeline-years-cut'),
      timelineEarlyDate: document.getElementById('timeline-early-date'),
      timelineOrigDate: document.getElementById('timeline-orig-date'),
      efficiencyCalloutText: document.getElementById('efficiency-callout-text'),
      btnPrintSummary: document.getElementById('btn-print-summary'),

      // Amortization KPIs
      kpiSavings: document.getElementById('kpi-savings'),
      kpiExtraTotal: document.getElementById('kpi-extra-total'),
      kpiRemaining: document.getElementById('kpi-remaining'),
      kpiPrincipal: document.getElementById('kpi-principal'),
      kpiInterest: document.getElementById('kpi-interest'),

      // Amortization Schedule & Chart
      tabChartBalance: document.getElementById('tab-chart-balance'),
      tabChartPayment: document.getElementById('tab-chart-payment'),
      chartCanvas: document.getElementById('amortization-chart-canvas'),
      chartLegend: document.getElementById('chart-legend-container'),
      amortTableBody: document.getElementById('amort-table-body'),
      btnScrollSchedule: document.getElementById('btn-scroll-to-schedule'),
      scheduleSection: document.getElementById('amortization-schedule-section')
    };
  }

  // Formatters
  function formatCurrency(val) {
    if (isNaN(val) || val === null) return '$0';
    return '$' + Math.round(val).toLocaleString('en-US');
  }

  function parseCurrency(str) {
    if (typeof str === 'number') return str;
    const clean = String(str).replace(/[^0-9.-]/g, '');
    const num = parseFloat(clean);
    return isNaN(num) ? 0 : num;
  }

  // Monthly mortgage payment formula (Principal + Interest)
  function calcStandardMonthlyPayment(principal, annualRatePct, totalMonths) {
    if (principal <= 0 || totalMonths <= 0) return 0;
    const r = (annualRatePct / 100) / 12;
    if (r === 0) return principal / totalMonths;
    const factor = Math.pow(1 + r, totalMonths);
    return principal * (r * factor) / (factor - 1);
  }

  // Helper: Get irregular extra payments matching a specific simulation month
  function getIrregularAmountForMonth(m) {
    let total = 0;
    const badges = [];

    state.irregularPayments.forEach(item => {
      const startMonth = parseInt(item.month, 10) || 1;
      const amount = parseFloat(item.amount) || 0;
      if (amount <= 0) return;

      if (item.type === 'one-time') {
        if (m === startMonth) {
          total += amount;
          badges.push(item.label || 'Lump Sum');
        }
      } else if (item.type === 'annual') {
        if (m >= startMonth && (m - startMonth) % 12 === 0) {
          total += amount;
          badges.push(item.label || 'Annual Bonus');
        }
      } else if (item.type === 'quarterly') {
        if (m >= startMonth && (m - startMonth) % 3 === 0) {
          total += amount;
          badges.push(item.label || 'Quarterly');
        }
      }
    });

    return { total, badges };
  }

  // Comprehensive calculation
  function calculatePayoff() {
    const P0 = Math.max(1000, state.originalAmount);
    const P_curr = Math.max(100, state.currentBalance);
    const termYears = Math.max(1, state.originalTermYears);
    const totalRemainingMonths = termYears * 12;
    const annualRate = Math.max(0, state.interestRate);
    const r = (annualRate / 100) / 12;

    // Standard original monthly payment from current balance over remaining term
    const standardPayment = calcStandardMonthlyPayment(P_curr, annualRate, totalRemainingMonths);

    // Baseline simulation: standard payment from current balance
    let baselineBalance = P_curr;
    let baselineTotalInterest = 0;
    let baselineMonths = 0;
    const baselineSchedule = [];

    while (baselineBalance > 0.01 && baselineMonths < 600) {
      baselineMonths++;
      const interest = r === 0 ? 0 : baselineBalance * r;
      let principalPaid = standardPayment - interest;
      if (principalPaid > baselineBalance) {
        principalPaid = baselineBalance;
      }
      if (principalPaid <= 0 && r > 0) break;

      baselineBalance -= principalPaid;
      baselineTotalInterest += interest;

      baselineSchedule.push({
        month: baselineMonths,
        interest: interest,
        principal: principalPaid,
        balance: Math.max(0, baselineBalance)
      });
    }

    // Determine extra monthly payment or target payoff
    let extraMonthly = 0;
    let effectiveTargetYears = state.targetPayoffYears;

    if (state.mode === 'years') {
      const targetMonths = Math.max(12, effectiveTargetYears * 12);
      const reqPayment = calcStandardMonthlyPayment(P_curr, annualRate, targetMonths);
      extraMonthly = Math.max(0, reqPayment - standardPayment);
      state.additionalMonthly = Math.round(extraMonthly);
      if (dom.inputAddMonthly) {
        dom.inputAddMonthly.value = state.additionalMonthly.toLocaleString('en-US');
      }
    } else if (state.mode === 'monthly') {
      extraMonthly = Math.max(0, state.additionalMonthly);
    } else if (state.mode === 'irregular') {
      extraMonthly = 0;
    }

    const earlyPayment = standardPayment + extraMonthly;

    // Early payoff simulation with irregular payments
    let earlyBalance = P_curr;
    let earlyTotalInterest = 0;
    let earlyMonths = 0;
    let totalPrincipalPaid = 0;
    let totalExtraContributed = 0;
    let totalIrregularContributed = 0;

    const earlyMonthlySchedule = [];
    const annualSchedule = [];

    let curYearPrincipal = 0;
    let curYearInterest = 0;
    let curYearExtra = 0;
    let curYearIrregular = 0;
    let curYearBadges = [];
    let curYearStartBalance = earlyBalance;

    while (earlyBalance > 0.01 && earlyMonths < 600) {
      earlyMonths++;
      const interest = earlyBalance * r;
      let regularPrincipal = standardPayment - interest;
      if (regularPrincipal > earlyBalance) {
        regularPrincipal = earlyBalance;
      }

      // Check for irregular payment in this month
      const irregular = getIrregularAmountForMonth(earlyMonths);
      const extraIrregular = irregular.total;

      // Available principal to pay
      let remainingToPay = Math.max(0, earlyBalance - regularPrincipal);

      // 1. Regular extra principal
      let actualRegularExtra = 0;
      if (state.mode !== 'irregular' && extraMonthly > 0 && remainingToPay > 0) {
        actualRegularExtra = Math.min(remainingToPay, extraMonthly);
        remainingToPay -= actualRegularExtra;
      }

      // 2. Irregular extra principal
      let actualIrregularExtra = 0;
      if (extraIrregular > 0 && remainingToPay > 0) {
        actualIrregularExtra = Math.min(remainingToPay, extraIrregular);
        remainingToPay -= actualIrregularExtra;
        if (irregular.badges.length > 0) {
          curYearBadges = curYearBadges.concat(irregular.badges);
        }
      }

      const totalPrincipalThisMonth = regularPrincipal + actualRegularExtra + actualIrregularExtra;
      earlyBalance = Math.max(0, earlyBalance - totalPrincipalThisMonth);
      earlyTotalInterest += interest;
      totalPrincipalPaid += totalPrincipalThisMonth;

      const totalExtraThisMonth = actualRegularExtra + actualIrregularExtra;
      totalExtraContributed += totalExtraThisMonth;
      totalIrregularContributed += actualIrregularExtra;

      curYearPrincipal += regularPrincipal;
      curYearInterest += interest;
      curYearExtra += totalExtraThisMonth;
      curYearIrregular += actualIrregularExtra;

      earlyMonthlySchedule.push({
        month: earlyMonths,
        interest: interest,
        regularPrincipal: regularPrincipal,
        extraPrincipal: totalExtraThisMonth,
        irregularPrincipal: actualIrregularExtra,
        totalPrincipal: totalPrincipalThisMonth,
        totalPayment: totalPrincipalThisMonth + interest,
        balance: earlyBalance
      });

      // Close year
      if (earlyMonths % 12 === 0 || earlyBalance <= 0.01) {
        const yearNumber = Math.ceil(earlyMonths / 12);
        annualSchedule.push({
          year: yearNumber,
          startBalance: curYearStartBalance,
          principalPaid: curYearPrincipal,
          extraPaid: curYearExtra,
          irregularPaid: curYearIrregular,
          badges: Array.from(new Set(curYearBadges)),
          totalPrincipalPaid: curYearPrincipal + curYearExtra,
          interestPaid: curYearInterest,
          totalPayment: curYearPrincipal + curYearExtra + curYearInterest,
          endBalance: earlyBalance
        });
        curYearStartBalance = earlyBalance;
        curYearPrincipal = 0;
        curYearInterest = 0;
        curYearExtra = 0;
        curYearIrregular = 0;
        curYearBadges = [];
      }
    }

    // Savings & Comparisons
    const monthsSaved = Math.max(0, baselineMonths - earlyMonths);
    const yearsSaved = Math.floor(monthsSaved / 12);
    const remMonthsSaved = monthsSaved % 12;
    const totalInterestSaved = Math.max(0, baselineTotalInterest - earlyTotalInterest);

    // Date math
    const now = new Date();
    const newPayoffDate = new Date(now.getFullYear(), now.getMonth() + earlyMonths, 1);
    const origPayoffDate = new Date(now.getFullYear(), now.getMonth() + baselineMonths, 1);

    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const formattedNewPayoff = monthNames[newPayoffDate.getMonth()] + ' ' + newPayoffDate.getFullYear();
    const formattedOrigPayoff = monthNames[origPayoffDate.getMonth()] + ' ' + origPayoffDate.getFullYear();

    const actualEarlyYears = (earlyMonths / 12).toFixed(1);
    const origYearsRemain = (baselineMonths / 12).toFixed(1);

    return {
      P0,
      P_curr,
      standardPayment,
      extraMonthly,
      earlyPayment,
      baselineMonths,
      earlyMonths,
      monthsSaved,
      yearsSaved,
      remMonthsSaved,
      totalInterestSaved,
      baselineTotalInterest,
      earlyTotalInterest,
      formattedNewPayoff,
      formattedOrigPayoff,
      actualEarlyYears,
      origYearsRemain,
      baselineSchedule,
      earlyMonthlySchedule,
      annualSchedule,
      totalPrincipalPaid,
      totalExtraContributed,
      totalIrregularContributed
    };
  }

  // Render Irregular Payments List in Form
  function renderIrregularPaymentsList() {
    if (!dom.irregularListContainer) return;

    if (state.irregularPayments.length === 0) {
      dom.irregularListContainer.innerHTML = `
        <div class="irregular-empty-state">
          No irregular payments added yet. Click "+ Add Scheduled Payment" or select a preset above.
        </div>
      `;
      return;
    }

    let html = '';
    state.irregularPayments.forEach((p) => {
      html += `
        <div class="irregular-item-row" data-id="${p.id}">
          <div class="irregular-col-type">
            <label class="irregular-field-label">Frequency</label>
            <select class="irregular-select js-irregular-type" data-id="${p.id}">
              <option value="one-time" ${p.type === 'one-time' ? 'selected' : ''}>One-Time Lump Sum</option>
              <option value="annual" ${p.type === 'annual' ? 'selected' : ''}>Annual Recurring</option>
              <option value="quarterly" ${p.type === 'quarterly' ? 'selected' : ''}>Quarterly Recurring</option>
            </select>
          </div>

          <div class="irregular-col-amount">
            <label class="irregular-field-label">Amount ($)</label>
            <div class="irregular-input-wrap">
              <span class="irregular-curr">$</span>
              <input type="text" class="irregular-input js-irregular-amount" data-id="${p.id}" value="${Math.round(p.amount).toLocaleString('en-US')}" inputmode="numeric">
            </div>
          </div>

          <div class="irregular-col-month">
            <label class="irregular-field-label">${p.type === 'one-time' ? 'At Month' : 'Start Month'}</label>
            <input type="number" class="irregular-input js-irregular-month" data-id="${p.id}" value="${p.month || 1}" min="1" max="360">
          </div>

          <div class="irregular-col-label">
            <label class="irregular-field-label">Description</label>
            <input type="text" class="irregular-input js-irregular-label" data-id="${p.id}" value="${p.label || ''}" placeholder="e.g. Bonus">
          </div>

          <button type="button" class="btn-remove-irregular js-remove-irregular" data-id="${p.id}" aria-label="Remove payment">
            &times;
          </button>
        </div>
      `;
    });

    dom.irregularListContainer.innerHTML = html;
    bindIrregularItemEvents();
  }

  // Bind change events to dynamic irregular payment inputs
  function bindIrregularItemEvents() {
    if (!dom.irregularListContainer) return;

    // Type change
    dom.irregularListContainer.querySelectorAll('.js-irregular-type').forEach(select => {
      select.addEventListener('change', function () {
        const id = parseInt(this.dataset.id, 10);
        const item = state.irregularPayments.find(p => p.id === id);
        if (item) {
          item.type = this.value;
          renderIrregularPaymentsList();
          updateUI();
        }
      });
    });

    // Amount change
    dom.irregularListContainer.querySelectorAll('.js-irregular-amount').forEach(input => {
      input.addEventListener('input', function () {
        const id = parseInt(this.dataset.id, 10);
        const item = state.irregularPayments.find(p => p.id === id);
        if (item) {
          item.amount = parseCurrency(this.value);
          updateUI();
        }
      });
    });

    // Month change
    dom.irregularListContainer.querySelectorAll('.js-irregular-month').forEach(input => {
      input.addEventListener('input', function () {
        const id = parseInt(this.dataset.id, 10);
        const item = state.irregularPayments.find(p => p.id === id);
        if (item) {
          item.month = Math.max(1, parseInt(this.value, 10) || 1);
          updateUI();
        }
      });
    });

    // Label change
    dom.irregularListContainer.querySelectorAll('.js-irregular-label').forEach(input => {
      input.addEventListener('input', function () {
        const id = parseInt(this.dataset.id, 10);
        const item = state.irregularPayments.find(p => p.id === id);
        if (item) {
          item.label = this.value;
          updateUI();
        }
      });
    });

    // Remove item
    dom.irregularListContainer.querySelectorAll('.js-remove-irregular').forEach(btn => {
      btn.addEventListener('click', function () {
        const id = parseInt(this.dataset.id, 10);
        state.irregularPayments = state.irregularPayments.filter(p => p.id !== id);
        renderIrregularPaymentsList();
        updateUI();
      });
    });
  }

  // Update UI displays
  function updateUI() {
    const results = calculatePayoff();

    // Results Headline
    if (dom.resTimeSavedHeadline) {
      if (results.yearsSaved > 0 || results.remMonthsSaved > 0) {
        let timeStr = '';
        if (results.yearsSaved > 0) timeStr += results.yearsSaved + (results.yearsSaved === 1 ? ' year ' : ' years ');
        if (results.remMonthsSaved > 0) timeStr += results.remMonthsSaved + (results.remMonthsSaved === 1 ? ' month' : ' months');
        dom.resTimeSavedHeadline.innerHTML = `You'll pay off your loan <span class="highlight">${timeStr.trim()} sooner!</span>`;
      } else {
        dom.resTimeSavedHeadline.innerHTML = `Pay off in <span class="highlight">${results.actualEarlyYears} years</span>`;
      }
    }

    if (dom.resTimeSavedText) {
      dom.resTimeSavedText.textContent = `Pay off your debt by ${results.formattedNewPayoff} instead of ${results.formattedOrigPayoff}, saving thousands.`;
    }

    if (dom.resMonthlyPayment) {
      dom.resMonthlyPayment.textContent = formatCurrency(results.earlyPayment) + '/mo';
    }

    if (dom.resMonthlyNote) {
      if (state.mode === 'irregular') {
        dom.resMonthlyNote.textContent = `Base: ${formatCurrency(results.standardPayment)} + irregular windfalls`;
      } else if (results.extraMonthly > 0) {
        dom.resMonthlyNote.textContent = `Includes ${formatCurrency(results.extraMonthly)} extra (Base: ${formatCurrency(results.standardPayment)})`;
      } else {
        dom.resMonthlyNote.textContent = `Standard monthly basis`;
      }
    }

    if (dom.resTotalInterestSaved) {
      dom.resTotalInterestSaved.textContent = formatCurrency(results.totalInterestSaved);
    }

    if (dom.resTimeSavedMetric) {
      if (results.yearsSaved > 0) {
        dom.resTimeSavedMetric.textContent = results.yearsSaved + 'y ' + results.remMonthsSaved + 'm';
      } else {
        dom.resTimeSavedMetric.textContent = results.remMonthsSaved + ' mos';
      }
    }

    if (dom.resMonthsSavedSub) {
      dom.resMonthsSavedSub.textContent = results.monthsSaved + ' months earlier';
    }

    if (dom.resNewPayoffDate) {
      dom.resNewPayoffDate.textContent = results.formattedNewPayoff;
    }

    if (dom.resOrigPayoffDate) {
      dom.resOrigPayoffDate.textContent = results.formattedOrigPayoff;
    }

    if (dom.resNewTotalInterest) {
      dom.resNewTotalInterest.textContent = formatCurrency(results.earlyTotalInterest);
    }

    if (dom.resOrigInterestSub) {
      dom.resOrigInterestSub.textContent = 'Orig: ' + formatCurrency(results.baselineTotalInterest);
    }

    if (dom.resExtraTotal) {
      dom.resExtraTotal.textContent = formatCurrency(results.totalExtraContributed);
    }

    // Graphical Timeline Bar Updates
    const yearsCut = (results.monthsSaved / 12).toFixed(1);
    if (dom.timelineYearsCut) {
      dom.timelineYearsCut.textContent = '⚡ ' + yearsCut + ' Years Erased';
    }
    if (dom.timelineEarlyDate) {
      dom.timelineEarlyDate.textContent = results.formattedNewPayoff;
    }
    if (dom.timelineOrigDate) {
      dom.timelineOrigDate.textContent = results.formattedOrigPayoff;
    }
    if (dom.timelineFill) {
      const pct = Math.min(100, Math.max(10, Math.round((results.earlyMonths / Math.max(1, results.baselineMonths)) * 100)));
      dom.timelineFill.style.width = pct + '%';
    }

    // Efficiency Callout
    if (dom.efficiencyCalloutText) {
      const ratio = results.totalExtraContributed > 0 
        ? (results.totalInterestSaved / results.totalExtraContributed).toFixed(2)
        : (results.extraMonthly > 0 ? (results.totalInterestSaved / (results.extraMonthly * results.earlyMonths)).toFixed(2) : '1.84');
      const cleanRatio = (parseFloat(ratio) > 0 && isFinite(ratio)) ? ratio : '1.84';
      dom.efficiencyCalloutText.textContent = `Every $1 extra in principal eliminates ~$${cleanRatio} in lifetime compounding bank interest.`;
    }

    // Amortization KPIs
    if (dom.kpiSavings) dom.kpiSavings.textContent = formatCurrency(results.totalInterestSaved);
    if (dom.kpiExtraTotal) dom.kpiExtraTotal.textContent = formatCurrency(results.totalExtraContributed);
    if (dom.kpiRemaining) dom.kpiRemaining.textContent = formatCurrency(results.P_curr);
    if (dom.kpiPrincipal) dom.kpiPrincipal.textContent = formatCurrency(results.totalPrincipalPaid);
    if (dom.kpiInterest) dom.kpiInterest.textContent = formatCurrency(results.earlyTotalInterest);

    // Update Chart & Table
    renderChart(results);
    renderTable(results);
  }

  // Render Chart.js
  function renderChart(results) {
    if (!dom.chartCanvas || typeof Chart === 'undefined') return;

    const maxYears = Math.max(
      results.annualSchedule.length,
      Math.ceil(results.baselineMonths / 12)
    );

    const labels = [];
    for (let y = 1; y <= maxYears; y++) {
      labels.push('Yr ' + y);
    }

    // Baseline curve vs Early payoff curve
    const baselineBalances = [];
    const earlyBalances = [];

    for (let y = 1; y <= maxYears; y++) {
      const mIdx = Math.min(results.baselineSchedule.length - 1, y * 12 - 1);
      baselineBalances.push(mIdx >= 0 ? Math.round(results.baselineSchedule[mIdx].balance) : 0);

      const earlyItem = results.annualSchedule.find(item => item.year === y);
      if (earlyItem) {
        earlyBalances.push(Math.round(earlyItem.endBalance));
      } else {
        earlyBalances.push(0);
      }
    }

    // Annual payment breakdown datasets
    const annualPrincipal = [];
    const annualExtra = [];
    const annualInterest = [];

    for (let y = 1; y <= maxYears; y++) {
      const item = results.annualSchedule.find(i => i.year === y);
      if (item) {
        annualPrincipal.push(Math.round(item.principalPaid));
        annualExtra.push(Math.round(item.extraPaid));
        annualInterest.push(Math.round(item.interestPaid));
      } else {
        annualPrincipal.push(0);
        annualExtra.push(0);
        annualInterest.push(0);
      }
    }

    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : '#f1f5f9';
    const tickColor = isDark ? '#94a3b8' : '#64748b';
    const baselineBorder = isDark ? '#64748b' : '#94a3b8';
    const baselineBg = isDark ? 'rgba(100, 116, 139, 0.05)' : 'rgba(148, 163, 184, 0.08)';
    const earlyBorder = isDark ? '#10b981' : '#059669';
    const earlyBg = isDark ? 'rgba(16, 185, 129, 0.18)' : 'rgba(5, 150, 105, 0.12)';
    const barPrincipal = isDark ? '#334155' : '#0f172a';
    const barExtra = isDark ? '#10b981' : '#059669';

    // Chart Legend UI update
    if (dom.chartLegend) {
      if (state.activeChartTab === 'balance') {
        dom.chartLegend.innerHTML = `
          <div class="legend-item"><span class="legend-color" style="background: ${baselineBorder};"></span> Original Balance</div>
          <div class="legend-item"><span class="legend-color" style="background: ${earlyBorder};"></span> Accelerated Balance</div>
        `;
      } else {
        dom.chartLegend.innerHTML = `
          <div class="legend-item"><span class="legend-color" style="background: ${barPrincipal};"></span> Principal</div>
          <div class="legend-item"><span class="legend-color" style="background: ${barExtra};"></span> Extra & Lump Sums</div>
          <div class="legend-item"><span class="legend-color" style="background: #f59e0b;"></span> Interest</div>
        `;
      }
    }

    if (state.chartInstance) {
      state.chartInstance.destroy();
    }

    const ctx = dom.chartCanvas.getContext('2d');

    if (state.activeChartTab === 'balance') {
      state.chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Original Balance',
              data: baselineBalances,
              borderColor: baselineBorder,
              backgroundColor: baselineBg,
              borderWidth: 2,
              borderDash: [5, 5],
              fill: false,
              tension: 0.2,
              pointRadius: 2
            },
            {
              label: 'Accelerated Balance',
              data: earlyBalances,
              borderColor: earlyBorder,
              backgroundColor: earlyBg,
              borderWidth: 3,
              fill: true,
              tension: 0.2,
              pointRadius: 3,
              pointBackgroundColor: earlyBorder,
              pointBorderColor: '#ffffff'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#0f172a',
              borderColor: isDark ? 'rgba(255, 255, 255, 0.1)' : '#e2e8f0',
              borderWidth: 1,
              titleColor: '#ffffff',
              bodyColor: '#cbd5e1',
              callbacks: {
                label: function (ctx) {
                  return ctx.dataset.label + ': ' + formatCurrency(ctx.parsed.y);
                }
              }
            }
          },
          scales: {
            x: { 
              grid: { color: gridColor },
              ticks: { color: tickColor }
            },
            y: {
              grid: { color: gridColor },
              ticks: {
                color: tickColor,
                callback: function (val) {
                  return '$' + (val >= 1000 ? (val / 1000) + 'k' : val);
                }
              }
            }
          }
        }
      });
    } else {
      // Stacked Bar Chart for Payment Breakdown
      state.chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels.slice(0, results.annualSchedule.length),
          datasets: [
            {
              label: 'Principal',
              data: annualPrincipal,
              backgroundColor: barPrincipal
            },
            {
              label: 'Extra & Lump Sums',
              data: annualExtra,
              backgroundColor: barExtra
            },
            {
              label: 'Interest',
              data: annualInterest,
              backgroundColor: '#f59e0b'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: { 
              stacked: true, 
              grid: { color: gridColor },
              ticks: { color: tickColor }
            },
            y: {
              stacked: true,
              grid: { color: gridColor },
              ticks: {
                color: tickColor,
                callback: function (val) {
                  return '$' + (val >= 1000 ? (val / 1000) + 'k' : val);
                }
              }
            }
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#0f172a',
              borderColor: isDark ? 'rgba(255, 255, 255, 0.1)' : '#e2e8f0',
              borderWidth: 1,
              titleColor: '#ffffff',
              bodyColor: '#cbd5e1',
              callbacks: {
                label: function (ctx) {
                  return ctx.dataset.label + ': ' + formatCurrency(ctx.parsed.y);
                }
              }
            }
          }
        }
      });
    }
  }

  // Render Amortization Table with Badges
  function renderTable(results) {
    if (!dom.amortTableBody) return;

    let html = '';
    results.annualSchedule.forEach(row => {
      const isPayoffYear = row.endBalance <= 0;
      let badgeHtml = '';
      if (row.badges && row.badges.length > 0) {
        badgeHtml = row.badges.map(b => `<span class="lump-sum-badge">${b}</span>`).join(' ');
      }

      html += `
        <tr class="${isPayoffYear ? 'early-payoff-row' : ''}">
          <td><strong>Year ${row.year}</strong> ${isPayoffYear ? '<span style="color:#34d399; font-size:12px; margin-left:6px; font-weight:700;">✓ Debt Free</span>' : ''}</td>
          <td>${formatCurrency(row.startBalance)}</td>
          <td>${formatCurrency(row.principalPaid)}</td>
          <td>${formatCurrency(row.interestPaid)}</td>
          <td style="color:#34d399; font-weight:700;">
            +${formatCurrency(row.extraPaid)}
            ${badgeHtml}
          </td>
          <td>${formatCurrency(row.totalPayment)}</td>
          <td><strong style="color:#ffffff;">${formatCurrency(row.endBalance)}</strong></td>
        </tr>
      `;
    });

    dom.amortTableBody.innerHTML = html;
  }

  // Helper to sync active nav pill
  function setActiveNav(feature) {
    const navBalance = document.getElementById('nav-item-current-balance');
    const navIrregular = document.getElementById('nav-item-irregular-payments');
    const mobBalance = document.getElementById('mobile-item-current-balance');
    const mobIrregular = document.getElementById('mobile-item-irregular-payments');

    if (feature === 'balance') {
      navBalance?.classList.add('active');
      navIrregular?.classList.remove('active');
      mobBalance?.classList.add('active');
      mobIrregular?.classList.remove('active');
    } else {
      navIrregular?.classList.add('active');
      navBalance?.classList.remove('active');
      mobIrregular?.classList.add('active');
      mobBalance?.classList.remove('active');
    }
  }

  // Bind Events
  function bindEvents() {
    // Top Strategy Switcher Buttons
    if (dom.btnModeYears) {
      dom.btnModeYears.addEventListener('click', function () {
        state.mode = 'years';
        setActiveNav('balance');
        dom.btnModeYears.classList.add('active');
        dom.btnModeMonthly.classList.remove('active');
        if (dom.btnModeIrregular) dom.btnModeIrregular.classList.remove('active');

        if (dom.groupTargetYears) dom.groupTargetYears.style.display = 'block';
        if (dom.groupAdditionalMonthly) dom.groupAdditionalMonthly.style.display = 'none';
        if (dom.groupIrregularPayments) dom.groupIrregularPayments.style.display = 'none';
        updateUI();
      });
    }

    if (dom.btnModeMonthly) {
      dom.btnModeMonthly.addEventListener('click', function () {
        state.mode = 'monthly';
        setActiveNav('balance');
        dom.btnModeMonthly.classList.add('active');
        dom.btnModeYears.classList.remove('active');
        if (dom.btnModeIrregular) dom.btnModeIrregular.classList.remove('active');

        if (dom.groupTargetYears) dom.groupTargetYears.style.display = 'none';
        if (dom.groupAdditionalMonthly) dom.groupAdditionalMonthly.style.display = 'block';
        if (dom.groupIrregularPayments) dom.groupIrregularPayments.style.display = 'block';
        updateUI();
      });
    }

    if (dom.btnModeIrregular) {
      dom.btnModeIrregular.addEventListener('click', function () {
        state.mode = 'irregular';
        setActiveNav('irregular');
        dom.btnModeIrregular.classList.add('active');
        dom.btnModeYears.classList.remove('active');
        dom.btnModeMonthly.classList.remove('active');

        if (dom.groupTargetYears) dom.groupTargetYears.style.display = 'none';
        if (dom.groupAdditionalMonthly) dom.groupAdditionalMonthly.style.display = 'none';
        if (dom.groupIrregularPayments) dom.groupIrregularPayments.style.display = 'block';
        updateUI();
      });
    }

    // Quick Term Pills (30Y, 25Y, 20Y, 15Y, 10Y)
    document.querySelectorAll('.term-pill-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.term-pill-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        state.originalTermYears = parseInt(this.dataset.term, 10);
        if (dom.inputLoanTerm) dom.inputLoanTerm.value = state.originalTermYears;
        updateUI();
      });
    });

    // Quick Balance Presets
    document.querySelectorAll('.js-balance-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-balance-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const amount = parseFloat(this.dataset.amount);
        if (!isNaN(amount)) {
          state.currentBalance = amount;
        } else {
          const pct = parseFloat(this.dataset.pct) || 0.83333;
          state.currentBalance = Math.round(state.originalAmount * pct);
        }
        if (dom.inputCurrentBalance) {
          dom.inputCurrentBalance.value = state.currentBalance.toLocaleString('en-US');
        }
        updateUI();
      });
    });

    // Rate Benchmark Presets
    document.querySelectorAll('.js-rate-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rate-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const r = parseFloat(this.dataset.rate);
        if (!isNaN(r)) {
          state.interestRate = r;
          if (dom.inputInterestRate) {
            dom.inputInterestRate.value = state.interestRate;
          }
          updateUI();
        }
      });
    });

    // Prepayment Penalty input
    if (dom.inputPrepaymentPenalty) {
      dom.inputPrepaymentPenalty.addEventListener('input', function () {
        state.prepaymentPenalty = parseCurrency(this.value);
        updateUI();
      });
    }

    // Quick Target Horizon Presets
    document.querySelectorAll('.js-target-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-target-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        state.targetPayoffYears = parseInt(this.dataset.years, 10);
        if (dom.inputTargetYears) {
          dom.inputTargetYears.value = state.targetPayoffYears;
        }
        updateUI();
      });
    });

    // Quick Monthly Boost Presets
    document.querySelectorAll('.js-boost-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-boost-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        state.additionalMonthly = parseFloat(this.dataset.boost) || 500;
        if (dom.inputAddMonthly) {
          dom.inputAddMonthly.value = state.additionalMonthly.toLocaleString('en-US');
        }
        updateUI();
      });
    });

    // Header & Mobile Nav item clicks
    document.getElementById('nav-item-current-balance')?.addEventListener('click', function (e) {
      e.preventDefault();
      dom.btnModeYears?.click();
      document.getElementById('calculator-section')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.getElementById('nav-item-irregular-payments')?.addEventListener('click', function (e) {
      e.preventDefault();
      dom.btnModeIrregular?.click();
      document.getElementById('group-irregular-payments')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.getElementById('mobile-item-current-balance')?.addEventListener('click', function (e) {
      e.preventDefault();
      const drawer = document.getElementById('mobile-drawer');
      if (drawer) drawer.style.display = 'none';
      dom.btnModeYears?.click();
      document.getElementById('calculator-section')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.getElementById('mobile-item-irregular-payments')?.addEventListener('click', function (e) {
      e.preventDefault();
      const drawer = document.getElementById('mobile-drawer');
      if (drawer) drawer.style.display = 'none';
      dom.btnModeIrregular?.click();
      document.getElementById('group-irregular-payments')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.getElementById('footer-link-balance')?.addEventListener('click', function (e) {
      e.preventDefault();
      dom.btnModeYears?.click();
      document.getElementById('calculator-section')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.getElementById('footer-link-irregular')?.addEventListener('click', function (e) {
      e.preventDefault();
      dom.btnModeIrregular?.click();
      document.getElementById('group-irregular-payments')?.scrollIntoView({ behavior: 'smooth' });
    });

    // Preset Chip Buttons for Irregular payments
    document.querySelectorAll('.chip-preset-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const type = this.dataset.type || 'annual';
        const amount = parseFloat(this.dataset.amount) || 5000;
        const month = parseInt(this.dataset.month, 10) || 12;
        const label = this.dataset.label || 'Bonus';

        state.irregularPayments.push({
          id: state.nextIrregularId++,
          type: type,
          amount: amount,
          month: month,
          label: label
        });

        renderIrregularPaymentsList();
        updateUI();
      });
    });

    // Add Irregular Payment Button
    if (dom.btnAddIrregularPayment) {
      dom.btnAddIrregularPayment.addEventListener('click', function () {
        state.irregularPayments.push({
          id: state.nextIrregularId++,
          type: 'annual',
          amount: 5000,
          month: 12,
          label: 'Annual Bonus'
        });
        renderIrregularPaymentsList();
        updateUI();
      });
    }

    // Chart Tabs switcher
    if (dom.tabChartBalance) {
      dom.tabChartBalance.addEventListener('click', function () {
        state.activeChartTab = 'balance';
        dom.tabChartBalance.classList.add('active');
        dom.tabChartPayment.classList.remove('active');
        updateUI();
      });
    }

    if (dom.tabChartPayment) {
      dom.tabChartPayment.addEventListener('click', function () {
        state.activeChartTab = 'payment';
        dom.tabChartPayment.classList.add('active');
        dom.tabChartBalance.classList.remove('active');
        updateUI();
      });
    }

    // Original Amount
    if (dom.inputOriginalAmount) {
      dom.inputOriginalAmount.addEventListener('input', function () {
        state.originalAmount = parseCurrency(this.value);
        updateUI();
      });
    }

    // Current Balance
    if (dom.inputCurrentBalance) {
      dom.inputCurrentBalance.addEventListener('input', function () {
        state.currentBalance = parseCurrency(this.value);
        updateUI();
      });
    }

    // Interest Rate
    if (dom.inputInterestRate) {
      dom.inputInterestRate.addEventListener('input', function () {
        state.interestRate = parseFloat(this.value) || 0;
        updateUI();
      });
    }

    // Target Payoff Years
    if (dom.inputTargetYears) {
      dom.inputTargetYears.addEventListener('input', function () {
        state.targetPayoffYears = parseInt(this.value, 10) || 1;
        updateUI();
      });
    }

    // Additional Monthly
    if (dom.inputAddMonthly) {
      dom.inputAddMonthly.addEventListener('input', function () {
        state.additionalMonthly = parseCurrency(this.value);
        updateUI();
      });
    }

    // Stepper buttons (+/-)
    document.querySelectorAll('.stepper-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const targetId = this.dataset.target;
        const delta = parseFloat(this.dataset.step) || 1;
        const input = document.getElementById(targetId);
        if (!input) return;

        let val = parseCurrency(input.value) + delta;
        if (targetId === 'input-target-years') val = Math.max(1, Math.min(40, val));
        if (targetId === 'input-add-monthly') val = Math.max(0, val);

        input.value = val.toLocaleString('en-US');
        input.dispatchEvent(new Event('input'));
      });
    });

    // Calculate Button
    if (dom.btnCalc) {
      dom.btnCalc.addEventListener('click', function () {
        updateUI();
        if (window.innerWidth < 1024 && dom.resTimeSavedHeadline) {
          dom.resTimeSavedHeadline.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    }

    // Print / Save Summary Button
    if (dom.btnPrintSummary) {
      dom.btnPrintSummary.addEventListener('click', function () {
        window.print();
      });
    }

    // Light / Dark Theme Switcher
    function toggleTheme() {
      const current = document.documentElement.getAttribute('data-theme') || 'light';
      const next = current === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      try {
        localStorage.setItem('equitypace_theme', next);
      } catch (e) {}

      const mobLabel = document.getElementById('mobile-theme-text');
      if (mobLabel) {
        mobLabel.textContent = next === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode';
      }

      updateUI();
    }

    document.getElementById('theme-toggle-btn')?.addEventListener('click', toggleTheme);
    document.getElementById('mobile-theme-toggle')?.addEventListener('click', toggleTheme);

    // Scroll to schedule
    if (dom.btnScrollSchedule && dom.scheduleSection) {
      dom.btnScrollSchedule.addEventListener('click', function (e) {
        e.preventDefault();
        dom.scheduleSection.scrollIntoView({ behavior: 'smooth' });
      });
    }
  }

  // Initialize on DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    initElements();
    renderIrregularPaymentsList();
    bindEvents();
    updateUI();
  });

})();
