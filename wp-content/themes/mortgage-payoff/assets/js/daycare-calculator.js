/**
 * EquityPace — Home Affordability Including Daycare Costs Engine
 * Models the Dual-Perspective Family Affordability Gap and Kindergarten Windfall
 * Updated for 2026 U.S. Real Estate & Mortgage Benchmarks
 */

(function () {
  'use strict';

  const benchmarks = window.EquityPaceBenchmarks || {};
  const childcareDefaults = benchmarks.childcare || {};
  const rates = benchmarks.mortgageRates || {};
  const propTaxDefaults = benchmarks.propertyTax || {};
  const insDefaults = benchmarks.homeownersInsurance || {};
  const takeHomeDefaults = benchmarks.takeHomePay || {};

  const state = {
    householdIncome: 140000,
    downPayment: 60000,
    numKids: 1,
    daycarePerChild: childcareDefaults.nationalBenchmarkMonthly || 1100,
    yearsInDaycare: childcareDefaults.defaultHorizonYears || 3,
    monthlyDebts: 500,
    mortgageRate: (rates.fixed30 && rates.fixed30.rate) || 6.71,
    loanTermYears: 30,
    propertyTaxRate: propTaxDefaults.nationalFallbackRate || 0.90,
    annualInsurance: insDefaults.genericAnnualDefault || 2750,
    takeHomeRatio: takeHomeDefaults.defaultRatio || 75,
    lenderDti: 36,
    chartInstance: null
  };

  // Helper: Currency formatters
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

  // Monthly factor for loan payment per $1 borrowed
  function getMonthlyFactor(annualRatePct, termYears) {
    const r = (annualRatePct / 100) / 12;
    const n = termYears * 12;
    if (r === 0) return 1 / n;
    const factor = Math.pow(1 + r, n);
    return (r * factor) / (factor - 1);
  }

  // Calculate standard monthly payment
  function calcStandardPayment(principal, annualRatePct, termYears) {
    if (principal <= 0) return 0;
    const factor = getMonthlyFactor(annualRatePct, termYears);
    return principal * factor;
  }

  // Core Affordability Math (2026 Updated)
  function calculateAffordability() {
    const grossAnnual = Math.max(20000, state.householdIncome);
    const downPayment = Math.max(0, state.downPayment);
    const grossMonthly = grossAnnual / 12;
    const totalDaycare = state.numKids * Math.max(0, state.daycarePerChild);
    const otherDebts = Math.max(0, state.monthlyDebts);
    const ratePct = Math.max(0.1, state.mortgageRate);
    const termYears = state.loanTermYears;

    const mFactor = getMonthlyFactor(ratePct, termYears);
    const taxMonthlyFactor = (Math.max(0, state.propertyTaxRate) / 100) / 12; // 0.90%/yr = 0.00075/mo
    const insMonthly = Math.max(0, state.annualInsurance) / 12;

    // 1. Traditional Bank Approval Limit (Ignoring Daycare)
    // 28% front-end DTI cap guideline
    const maxFrontHousing = grossMonthly * 0.28;
    // Selected back-end DTI cap (36%, 43%, 45%, 50%) minus other debts
    const maxBackHousing = Math.max(300, (grossMonthly * (state.lenderDti / 100)) - otherDebts);
    const bankAllowedHousingPayment = Math.min(maxFrontHousing, maxBackHousing);

    // Solve for Home Price: (Price - Down) * mFactor + Price * taxMonthlyFactor + insMonthly = AllowedPayment
    // Price * (mFactor + taxMonthlyFactor) = AllowedPayment - insMonthly + (Down * mFactor)
    const netBankPaymentForLoanAndTax = Math.max(100, bankAllowedHousingPayment - insMonthly);
    let bankHomePrice = (netBankPaymentForLoanAndTax + (downPayment * mFactor)) / (mFactor + taxMonthlyFactor);
    bankHomePrice = Math.max(downPayment + 10000, Math.round(bankHomePrice));
    const bankLoan = Math.max(0, bankHomePrice - downPayment);
    const bankMortgagePI = calcStandardPayment(bankLoan, ratePct, termYears);
    const bankTaxMonthly = bankHomePrice * taxMonthlyFactor;
    const bankTotalPayment = bankMortgagePI + bankTaxMonthly + insMonthly;

    // 2. True Safe Family Affordability (Factoring in Daycare & Living Buffer)
    // Estimate net take-home pay after taxes and deductions (Default: 75%)
    const netMonthly = (grossAnnual * (state.takeHomeRatio / 100)) / 12;

    // Safe discretionary living buffer (groceries, utilities, healthcare, car fuel, savings)
    const livingBuffer = Math.max(2000, netMonthly * 0.26);

    // Remaining cash available for housing:
    let safeHousingPayment = netMonthly - totalDaycare - otherDebts - livingBuffer;
    // Suggested household budgeting target (max 33% of take-home pay)
    safeHousingPayment = Math.min(safeHousingPayment, netMonthly * 0.33);
    safeHousingPayment = Math.max(400, safeHousingPayment);

    const netSafePaymentForLoanAndTax = Math.max(100, safeHousingPayment - insMonthly);
    let safeHomePrice = (netSafePaymentForLoanAndTax + (downPayment * mFactor)) / (mFactor + taxMonthlyFactor);
    safeHomePrice = Math.max(downPayment + 5000, Math.round(safeHomePrice));
    const safeLoan = Math.max(0, safeHomePrice - downPayment);
    const safeMortgagePI = calcStandardPayment(safeLoan, ratePct, termYears);
    const safeTaxMonthly = safeHomePrice * taxMonthlyFactor;
    const safeTotalPayment = safeMortgagePI + safeTaxMonthly + insMonthly;

    // The Daycare Purchasing Gap
    const gapAmount = Math.max(0, bankHomePrice - safeHomePrice);

    // Free cash buffer remaining in family budget
    const freeBuffer = Math.max(0, netMonthly - safeTotalPayment - totalDaycare - otherDebts);

    // 3. Post-Childcare Milestone Cash Flow Windfall
    // When daycare ends in state.yearsInDaycare years, +totalDaycare is freed up
    const kUnlockedMonthly = totalDaycare;
    let kYearsCut = 0;
    let kInterestSaved = 0;

    if (totalDaycare > 0 && safeLoan > 1000) {
      const r = (ratePct / 100) / 12;
      let balance = safeLoan;
      let baselineMonths = 0;
      let baselineTotalInterest = 0;

      // Baseline amortization
      while (balance > 0.01 && baselineMonths < 360) {
        baselineMonths++;
        const interest = balance * r;
        let pPaid = safeMortgagePI - interest;
        if (pPaid > balance) pPaid = balance;
        balance -= pPaid;
        baselineTotalInterest += interest;
      }

      // Accelerated amortization with Kindergarten Extra Principal starting at Year N
      let earlyBalance = safeLoan;
      let earlyMonths = 0;
      let earlyTotalInterest = 0;
      const startMonthExtra = state.yearsInDaycare * 12;

      while (earlyBalance > 0.01 && earlyMonths < 360) {
        earlyMonths++;
        const interest = earlyBalance * r;
        let pPaid = safeMortgagePI - interest;
        if (earlyMonths >= startMonthExtra) {
          pPaid += totalDaycare;
        }
        if (pPaid > earlyBalance) pPaid = earlyBalance;
        earlyBalance -= pPaid;
        earlyTotalInterest += interest;
      }

      const monthsCut = Math.max(0, baselineMonths - earlyMonths);
      kYearsCut = (monthsCut / 12).toFixed(1);
      kInterestSaved = Math.max(0, Math.round(baselineTotalInterest - earlyTotalInterest));
    }

    return {
      grossAnnual,
      grossMonthly,
      netMonthly,
      totalDaycare,
      otherDebts,
      livingBuffer,
      freeBuffer,
      // Bank Limits
      bankHomePrice,
      bankLoan,
      bankMortgagePI,
      bankEscrow: bankTaxMonthly + insMonthly,
      bankTotalPayment,
      // Safe Limits
      safeHomePrice,
      safeLoan,
      safeMortgagePI,
      safeEscrow: safeTaxMonthly + insMonthly,
      safeTotalPayment,
      // Gap & Kindergarten
      gapAmount,
      kUnlockedMonthly,
      kYearsCut,
      kInterestSaved
    };
  }

  // Update UI Elements
  function updateUI() {
    const data = calculateAffordability();

    // Total Daycare indicator in input form
    const indTotalDaycare = document.getElementById('indicator-total-daycare');
    if (indTotalDaycare) indTotalDaycare.textContent = formatCurrency(data.totalDaycare) + '/mo';

    // Safe Headline
    const elSafePrice = document.getElementById('res-safe-home-price');
    if (elSafePrice) elSafePrice.textContent = formatCurrency(data.safeHomePrice);

    // Gap Alert Box
    const elGapAmount = document.getElementById('res-gap-amount');
    if (elGapAmount) elGapAmount.textContent = '-' + formatCurrency(data.gapAmount);

    const elGapText = document.getElementById('res-gap-text');
    if (elGapText) {
      if (data.totalDaycare > 0) {
        elGapText.textContent = `Daycare tuition reduces your realistic family purchasing power by ${formatCurrency(data.gapAmount)} compared to traditional lender guidelines.`;
      } else {
        elGapText.textContent = `No active daycare costs entered. Your budget reflects standard living buffer guidelines.`;
      }
    }

    // Dual Comparison Cards
    const elBankPrice = document.getElementById('res-bank-home-price');
    if (elBankPrice) elBankPrice.textContent = formatCurrency(data.bankHomePrice);

    const elBankPay = document.getElementById('res-bank-payment');
    if (elBankPay) elBankPay.textContent = `${formatCurrency(data.bankTotalPayment)}/mo total`;

    const elBankDtiSub = document.getElementById('res-bank-dti-sub');
    if (elBankDtiSub) elBankDtiSub.textContent = `${state.lenderDti}% DTI Scenario (Ignores Childcare)`;

    const elSafeTilePrice = document.getElementById('res-safe-tile-price');
    if (elSafeTilePrice) elSafeTilePrice.textContent = formatCurrency(data.safeHomePrice);

    const elSafePay = document.getElementById('res-safe-payment');
    if (elSafePay) elSafePay.textContent = `${formatCurrency(data.safeTotalPayment)}/mo total`;

    // Monthly Cash Flow Chart
    const elNetTag = document.getElementById('res-takehome-tag');
    if (elNetTag) elNetTag.textContent = `Net Pay: ${formatCurrency(data.netMonthly)}/mo (${state.takeHomeRatio}%)`;

    // Legend items
    const cfMortgage = document.getElementById('cf-val-mortgage');
    if (cfMortgage) cfMortgage.textContent = formatCurrency(data.safeMortgagePI);

    const cfDaycare = document.getElementById('cf-val-daycare');
    if (cfDaycare) cfDaycare.textContent = formatCurrency(data.totalDaycare);

    const cfEscrow = document.getElementById('cf-val-escrow');
    if (cfEscrow) cfEscrow.textContent = formatCurrency(data.safeEscrow);

    const cfDebts = document.getElementById('cf-val-debts');
    if (cfDebts) cfDebts.textContent = formatCurrency(data.otherDebts);

    const cfBuffer = document.getElementById('cf-val-buffer');
    if (cfBuffer) cfBuffer.textContent = formatCurrency(data.freeBuffer + data.livingBuffer);

    // Kindergarten Milestone Card
    const elKSub = document.getElementById('res-k-timeline-text');
    if (elKSub) elKSub.textContent = `When daycare costs change or end in ${state.yearsInDaycare} years`;

    const elKCash = document.getElementById('res-k-unlocked-cash');
    if (elKCash) elKCash.textContent = `+${formatCurrency(data.kUnlockedMonthly)}/mo`;

    const elKYears = document.getElementById('res-k-years-cut');
    if (elKYears) elKYears.textContent = `⚡ ${data.kYearsCut} Years`;

    const elKInterest = document.getElementById('res-k-interest-saved');
    if (elKInterest) elKInterest.textContent = formatCurrency(data.kInterestSaved);

    // Render Doughnut Chart
    renderChart(data);
  }

  // Render Doughnut Chart using Chart.js
  function renderChart(data) {
    const canvas = document.getElementById('daycare-cashflow-chart');
    if (!canvas || typeof Chart === 'undefined') return;

    if (state.chartInstance) {
      state.chartInstance.destroy();
    }

    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const ctx = canvas.getContext('2d');

    state.chartInstance = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Mortgage P&I', 'Daycare / Childcare', 'Taxes & Insurance', 'Other Debts', 'Safe Living Buffer'],
        datasets: [{
          data: [
            Math.round(data.safeMortgagePI),
            Math.round(data.totalDaycare),
            Math.round(data.safeEscrow),
            Math.round(data.otherDebts),
            Math.round(data.freeBuffer + data.livingBuffer)
          ],
          backgroundColor: [
            '#059669', // Emerald
            '#f59e0b', // Amber
            '#0d9488', // Teal
            '#ef4444', // Red
            '#3b82f6'  // Blue
          ],
          borderWidth: 2,
          borderColor: isDark ? '#0f172a' : '#ffffff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
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
                const val = ctx.parsed;
                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                const pct = total > 0 ? Math.round((val / total) * 100) : 0;
                return `${ctx.label}: ${formatCurrency(val)} (${pct}%)`;
              }
            }
          }
        }
      }
    });
  }

  // Bind Events
  function bindEvents() {
    // Income Presets
    document.querySelectorAll('.js-income-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-income-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        state.householdIncome = parseFloat(this.dataset.income) || 140000;
        const input = document.getElementById('input-household-income');
        if (input) input.value = state.householdIncome.toLocaleString('en-US');
        updateUI();
      });
    });

    // Kids Pills
    document.querySelectorAll('.kids-pill-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.kids-pill-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        state.numKids = parseInt(this.dataset.kids, 10);
        updateUI();
      });
    });

    // Daycare Cost Presets
    document.querySelectorAll('.js-daycare-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-daycare-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        state.daycarePerChild = parseFloat(this.dataset.cost) || 1100;
        const input = document.getElementById('input-daycare-per-child');
        if (input) input.value = state.daycarePerChild.toLocaleString('en-US');
        updateUI();
      });
    });

    // Childcare Horizon Pills
    document.querySelectorAll('#kindergarten-pills-container .term-pill-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('#kindergarten-pills-container .term-pill-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        state.yearsInDaycare = parseInt(this.dataset.years, 10) || 3;
        updateUI();
      });
    });

    // Take-Home Ratio Pills
    document.querySelectorAll('.js-takehome-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-takehome-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        state.takeHomeRatio = parseFloat(this.dataset.ratio) || 75;
        const input = document.getElementById('input-takehome-ratio');
        if (input) input.value = state.takeHomeRatio;
        updateUI();
      });
    });

    // DTI Guideline Pills
    document.querySelectorAll('.js-dti-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-dti-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        state.lenderDti = parseFloat(this.dataset.dti) || 36;
        const input = document.getElementById('input-lender-dti');
        if (input) input.value = state.lenderDti;
        updateUI();
      });
    });

    // Income Input
    document.getElementById('input-household-income')?.addEventListener('input', function () {
      state.householdIncome = parseCurrency(this.value);
      updateUI();
    });

    // Down Payment Input
    document.getElementById('input-down-payment')?.addEventListener('input', function () {
      state.downPayment = parseCurrency(this.value);
      updateUI();
    });

    // Daycare Cost Input
    document.getElementById('input-daycare-per-child')?.addEventListener('input', function () {
      state.daycarePerChild = parseCurrency(this.value);
      updateUI();
    });

    // Other Debts Input
    document.getElementById('input-monthly-debts')?.addEventListener('input', function () {
      state.monthlyDebts = parseCurrency(this.value);
      updateUI();
    });

    // Interest Rate Input
    document.getElementById('input-daycare-rate')?.addEventListener('input', function () {
      state.mortgageRate = parseFloat(this.value) || 6.71;
      updateUI();
    });

    // Property Tax Rate Input
    document.getElementById('input-property-tax-rate')?.addEventListener('input', function () {
      state.propertyTaxRate = parseFloat(this.value) || 0.90;
      updateUI();
    });

    // Annual Insurance Input
    document.getElementById('input-annual-insurance')?.addEventListener('input', function () {
      state.annualInsurance = parseCurrency(this.value);
      updateUI();
    });

    // Recalculate Button
    document.getElementById('btn-recalc-daycare')?.addEventListener('click', function () {
      updateUI();
      if (window.innerWidth < 1024) {
        document.getElementById('res-safe-headline')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });

    // Nav Item Clicks for Daycare
    document.getElementById('nav-item-daycare')?.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelectorAll('.nav-pill-item').forEach(p => p.classList.remove('active'));
      this.classList.add('active');
      document.getElementById('daycare-affordability-section')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.getElementById('mobile-item-daycare')?.addEventListener('click', function (e) {
      e.preventDefault();
      const drawer = document.getElementById('mobile-drawer');
      if (drawer) drawer.style.display = 'none';
      document.getElementById('daycare-affordability-section')?.scrollIntoView({ behavior: 'smooth' });
    });

    document.getElementById('footer-link-daycare')?.addEventListener('click', function (e) {
      e.preventDefault();
      document.getElementById('daycare-affordability-section')?.scrollIntoView({ behavior: 'smooth' });
    });
  }

  // Initialize on DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    bindEvents();
    updateUI();
  });

  // Re-render chart on theme change
  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.attributeName === 'data-theme') {
        updateUI();
      }
    });
  });
  observer.observe(document.documentElement, { attributes: true });

})();
