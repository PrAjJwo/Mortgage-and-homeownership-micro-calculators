/**
 * EquityPace — Comprehensive Calculators Suite Financial Engine
 * Version 2.0 (2026 U.S. Real Estate & Mortgage Benchmark Architecture)
 * 
 * Houses reactive engines for all 7 modular tools:
 * 1. Rent vs. Buy with Closing Costs
 * 2. Seller Net-Proceeds
 * 3. House-Flipping Profit (70% Rule & Hard Money)
 * 4. Mortgage Recast
 * 5. HELOC Interest-Only & Repayment
 * 6. Home Replacement Cost (Coverage A)
 * 7. Insurance Deductible Savings & Break-Even
 */

(function () {
  'use strict';

  // Obtain centralized benchmarks single source of truth
  const EP_BM = window.EquityPaceBenchmarks || {
    mortgageRates: { fixed30: { rate: 6.71 }, fixed15: { rate: 6.04 } },
    propertyTaxFallback: { rate: 0.90 },
    insuranceDefaults: { genericAnnual: 2750 },
    maintenanceDefaults: { average: 1.5 },
    closingCostDefaults: { buyer: { default: 3.0 } },
    brokerCompensation: { default: 5.0 },
    sellerConcessions: { default: 0 },
    helocRates: { benchmark: 7.29, cltvDefault: 80 },
    mortgageRecast: { feeDefault: 250, lumpSumDefault: 10000 },
    houseFlip: { sellingOverheadDefault: 8.0, hardMoneyRateDefault: 11.0, hardMoneyPointsDefault: 2.0, ltcDefault: 80, holdingCostMonthlyDefault: 750, investorRuleDefault: 70 },
    replacementCost: { baseCostDefault: 200, basementDefault: 50000, garage2CarDefault: 40000, debrisDefaultPct: 10.0 },
    deductibleSavings: { defaultReductionPct: 9.0, genericPremiumDefault: 2750 },
    rentVsBuyAssumptions: { homeAppreciationDefault: 3.0, rentInflationDefault: 3.0, investmentReturn: { default: 7.0 } }
  };

  // Helper: Format Currency
  function formatCurrency(val) {
    if (isNaN(val) || val === null) return '$0';
    return '$' + Math.round(val).toLocaleString('en-US');
  }

  // Helper: Parse Currency String
  function parseCurrency(str) {
    if (typeof str === 'number') return str;
    const clean = String(str).replace(/[^0-9.-]/g, '');
    const num = parseFloat(clean);
    return isNaN(num) ? 0 : num;
  }

  // Helper: Standard Fixed-Rate Amortization Monthly Payment
  function calcStandardPayment(principal, annualRatePct, termYears) {
    if (principal <= 0 || termYears <= 0) return 0;
    const r = (annualRatePct / 100) / 12;
    const n = termYears * 12;
    if (r === 0) return principal / n;
    const factor = Math.pow(1 + r, n);
    return principal * (r * factor) / (factor - 1);
  }

  const chartInstances = {};

  /* ==========================================================================
     1. Rent Versus Buy with Closing Costs Engine
     ========================================================================== */
  function initRentVsBuy() {
    const elPrice = document.getElementById('rvb-home-price');
    if (!elPrice) return;

    function runCalc() {
      const price = parseCurrency(elPrice.value) || 450000;
      const down = parseCurrency(document.getElementById('rvb-down-payment')?.value) || 90000;
      const closingPct = parseFloat(document.getElementById('rvb-closing-costs')?.value) || EP_BM.closingCostDefaults.buyer.default;
      const ratePct = parseFloat(document.getElementById('rvb-interest-rate')?.value) || EP_BM.mortgageRates.fixed30.rate;
      const apprecPct = (parseFloat(document.getElementById('rvb-home-appreciation')?.value) || EP_BM.rentVsBuyAssumptions.homeAppreciationDefault) / 100;
      const taxRatePct = (parseFloat(document.getElementById('rvb-property-tax-rate')?.value) || EP_BM.propertyTaxFallback.rate) / 100;
      const insuranceAnnual = parseCurrency(document.getElementById('rvb-home-insurance')?.value) || EP_BM.insuranceDefaults.genericAnnual;
      const maintRatePct = (parseFloat(document.getElementById('rvb-maintenance-rate')?.value) || EP_BM.maintenanceDefaults.average) / 100;
      const sellingCostPct = (parseFloat(document.getElementById('rvb-selling-costs-pct')?.value) || 7.0) / 100;
      const rentMonthly = parseCurrency(document.getElementById('rvb-monthly-rent')?.value) || 2400;
      const rentInfl = (parseFloat(document.getElementById('rvb-rent-inflation')?.value) || EP_BM.rentVsBuyAssumptions.rentInflationDefault) / 100;
      const stockReturn = (parseFloat(document.getElementById('rvb-investment-return')?.value) || EP_BM.rentVsBuyAssumptions.investmentReturn.default) / 100;

      const loan = Math.max(0, price - down);
      const mMonthlyPI = calcStandardPayment(loan, ratePct, 30);
      const taxMonthly = (price * taxRatePct) / 12;
      const insMonthly = insuranceAnnual / 12;
      const maintMonthly = (price * maintRatePct) / 12;
      const buyerClosingCash = price * (closingPct / 100);
      const buyerTotalInitialCash = down + buyerClosingCash;

      let rMonthly = (ratePct / 100) / 12;
      let buyNWSeries = [];
      let rentNWSeries = [];
      let crossoverYear = null;

      // 30-Year Wealth Projection
      let curLoan = loan;
      let curHomeVal = price;
      let curStockPortfolio = buyerTotalInitialCash;
      let curRent = rentMonthly;

      for (let yr = 1; yr <= 30; yr++) {
        // Amortize 12 months
        for (let m = 1; m <= 12; m++) {
          const interest = curLoan * rMonthly;
          const principal = Math.min(curLoan, mMonthlyPI - interest);
          curLoan = Math.max(0, curLoan - principal);
          const buyerTotalMonthly = mMonthlyPI + taxMonthly + insMonthly + maintMonthly;

          // Rent compounding: savings invested, deficits funded from stock portfolio
          curStockPortfolio *= (1 + (stockReturn / 12));
          if (buyerTotalMonthly > curRent) {
            curStockPortfolio += (buyerTotalMonthly - curRent);
          } else {
            curStockPortfolio -= (curRent - buyerTotalMonthly);
          }
        }

        curHomeVal *= (1 + apprecPct);
        curRent *= (1 + rentInfl);

        // Homebuyer Net Worth = Home Value minus Remaining Loan minus selling overhead costs
        const homeEquity = Math.round((curHomeVal * (1 - sellingCostPct)) - curLoan);
        const rentNW = Math.round(curStockPortfolio);

        buyNWSeries.push(homeEquity);
        rentNWSeries.push(rentNW);

        if (crossoverYear === null && homeEquity > rentNW) {
          crossoverYear = yr;
        }
      }

      // UI Updates
      const elCrossover = document.getElementById('rvb-crossover-years');
      if (elCrossover) elCrossover.textContent = crossoverYear ? `${crossoverYear} Years` : 'Over 15 Years';

      const elSubtext = document.getElementById('rvb-subtext');
      if (elSubtext) {
        elSubtext.textContent = `Taking into account ${formatCurrency(buyerClosingCash)} in upfront buyer closing costs (${closingPct}%), 0.90% property tax, $2,750/yr insurance, and 1.5% maintenance reserves.`;
      }

      const elBuyNW = document.getElementById('rvb-buy-nw');
      if (elBuyNW) elBuyNW.textContent = formatCurrency(buyNWSeries[9] || 0);

      const elBuyMonthly = document.getElementById('rvb-buy-monthly');
      if (elBuyMonthly) {
        const totalOwnerMo = Math.round(mMonthlyPI + taxMonthly + insMonthly + maintMonthly);
        elBuyMonthly.textContent = `${formatCurrency(totalOwnerMo)}/mo (P&I + Escrow + Maint)`;
      }

      const elRentNW = document.getElementById('rvb-rent-nw');
      if (elRentNW) elRentNW.textContent = formatCurrency(rentNWSeries[9] || 0);

      const elAdvantage = document.getElementById('rvb-advantage-tag');
      if (elAdvantage) {
        const diff = (buyNWSeries[9] || 0) - (rentNWSeries[9] || 0);
        elAdvantage.textContent = diff >= 0 
          ? `Buying +${formatCurrency(diff)} Ahead at Yr 10`
          : `Renting +${formatCurrency(Math.abs(diff))} Ahead at Yr 10`;
      }

      renderRvbChart(buyNWSeries, rentNWSeries);
    }

    function renderRvbChart(buyData, rentData) {
      const canvas = document.getElementById('rvb-chart-canvas');
      if (!canvas || typeof Chart === 'undefined') return;

      if (chartInstances.rvb) chartInstances.rvb.destroy();

      const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
      const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : '#f1f5f9';
      const tickColor = isDark ? '#94a3b8' : '#64748b';

      const labels = [];
      for (let y = 1; y <= 30; y++) labels.push('Yr ' + y);

      chartInstances.rvb = new Chart(canvas.getContext('2d'), {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Homeowner Net Worth',
              data: buyData,
              borderColor: '#059669',
              backgroundColor: 'rgba(5, 150, 105, 0.12)',
              borderWidth: 3,
              fill: true,
              tension: 0.3,
              pointRadius: 1
            },
            {
              label: 'Renter + Stock Portfolio',
              data: rentData,
              borderColor: '#3b82f6',
              backgroundColor: 'transparent',
              borderWidth: 2,
              borderDash: [5, 5],
              tension: 0.3,
              pointRadius: 1
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
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
            x: { grid: { color: gridColor }, ticks: { color: tickColor } },
            y: {
              grid: { color: gridColor },
              ticks: {
                color: tickColor,
                callback: function (v) { return '$' + Math.round(v / 1000) + 'k'; }
              }
            }
          }
        }
      });
    }

    // Presets & Event Listeners
    document.querySelectorAll('.js-rvb-price-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rvb-price-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        elPrice.value = parseFloat(this.dataset.price).toLocaleString('en-US');
        runCalc();
      });
    });

    document.querySelectorAll('.js-rvb-closing-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rvb-closing-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('rvb-closing-costs');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-rvb-rate-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rvb-rate-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('rvb-interest-rate');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-rvb-apprec-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rvb-apprec-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('rvb-home-appreciation');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-rvb-maint-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rvb-maint-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('rvb-maintenance-rate');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-rvb-rentinfl-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rvb-rentinfl-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('rvb-rent-inflation');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-rvb-stock-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rvb-stock-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('rvb-investment-return');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.getElementById('btn-recalc-rvb')?.addEventListener('click', runCalc);
    document.querySelectorAll('#rvb-form input').forEach(input => {
      input.addEventListener('input', runCalc);
    });

    runCalc();
  }

  /* ==========================================================================
     2. Seller Net-Proceeds Engine
     ========================================================================== */
  function initSellerProceeds() {
    const elPrice = document.getElementById('seller-sale-price');
    if (!elPrice) return;

    function runCalc() {
      const price = parseCurrency(elPrice.value) || 500000;
      const mortgage = parseCurrency(document.getElementById('seller-mortgage-balance')?.value) || 280000;
      const commPct = parseFloat(document.getElementById('seller-commission-pct')?.value) || EP_BM.brokerCompensation.default;
      const closingPct = parseFloat(document.getElementById('seller-closing-costs-pct')?.value) || 1.5;
      const repairs = parseCurrency(document.getElementById('seller-repairs')?.value) || EP_BM.sellerConcessions.default;

      const commAmount = price * (commPct / 100);
      const closingAmount = price * (closingPct / 100);
      const totalCosts = commAmount + closingAmount + repairs;
      const totalDeductions = mortgage + totalCosts;
      const netCash = Math.max(0, price - totalDeductions);
      const netRatio = price > 0 ? ((netCash / price) * 100).toFixed(1) : 0;

      // Update UI
      const elComm = document.getElementById('indicator-commission');
      if (elComm) elComm.textContent = formatCurrency(commAmount);

      const elNet = document.getElementById('seller-net-proceeds');
      if (elNet) elNet.textContent = formatCurrency(netCash);

      const elResPrice = document.getElementById('seller-res-sale-price');
      if (elResPrice) elResPrice.textContent = formatCurrency(price);

      const elResPayoff = document.getElementById('seller-res-payoff');
      if (elResPayoff) elResPayoff.textContent = formatCurrency(mortgage);

      const elResTotalCosts = document.getElementById('seller-res-total-costs');
      if (elResTotalCosts) elResTotalCosts.textContent = formatCurrency(totalCosts);

      const elResPct = document.getElementById('seller-res-pct');
      if (elResPct) elResPct.textContent = netRatio + '%';

      const elTagNet = document.getElementById('seller-tag-net');
      if (elTagNet) elTagNet.textContent = `Net: ${formatCurrency(netCash)}`;

      // Update Legend
      const cfNet = document.getElementById('cf-sell-net');
      if (cfNet) cfNet.textContent = '$' + (netCash / 1000).toFixed(1) + 'k';
      const cfPayoff = document.getElementById('cf-sell-payoff');
      if (cfPayoff) cfPayoff.textContent = '$' + Math.round(mortgage / 1000) + 'k';
      const cfComm = document.getElementById('cf-sell-comm');
      if (cfComm) cfComm.textContent = '$' + (commAmount / 1000).toFixed(1) + 'k';
      const cfFees = document.getElementById('cf-sell-fees');
      if (cfFees) cfFees.textContent = '$' + ((closingAmount + repairs) / 1000).toFixed(1) + 'k';

      renderSellerChart(netCash, mortgage, commAmount, closingAmount + repairs);
    }

    function renderSellerChart(net, mortgage, comm, fees) {
      const canvas = document.getElementById('seller-chart-canvas');
      if (!canvas || typeof Chart === 'undefined') return;

      if (chartInstances.seller) chartInstances.seller.destroy();

      const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

      chartInstances.seller = new Chart(canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: ['Net Cash to Seller', 'Mortgage Payoff', 'Broker Compensation', 'Closing Fees & Credits'],
          datasets: [
            {
              data: [Math.round(net), Math.round(mortgage), Math.round(comm), Math.round(fees)],
              backgroundColor: ['#059669', '#09131f', '#f59e0b', '#ef4444'],
              borderWidth: 2,
              borderColor: isDark ? '#0f172a' : '#ffffff'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '68%',
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#0f172a',
              callbacks: {
                label: function (ctx) { return ctx.label + ': ' + formatCurrency(ctx.parsed); }
              }
            }
          }
        }
      });
    }

    // Presets
    document.querySelectorAll('.js-seller-price-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-seller-price-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        elPrice.value = parseFloat(this.dataset.price).toLocaleString('en-US');
        runCalc();
      });
    });

    document.querySelectorAll('.js-seller-comm-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-seller-comm-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('seller-commission-pct');
        if (input) input.value = this.dataset.comm;
        runCalc();
      });
    });

    document.querySelectorAll('.js-seller-closing-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-seller-closing-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('seller-closing-costs-pct');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-seller-concession-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-seller-concession-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('seller-repairs');
        if (input) input.value = parseFloat(this.dataset.val).toLocaleString('en-US');
        runCalc();
      });
    });

    document.getElementById('btn-recalc-seller')?.addEventListener('click', runCalc);
    document.querySelectorAll('#seller-form input').forEach(input => {
      input.addEventListener('input', runCalc);
    });

    runCalc();
  }

  /* ==========================================================================
     3. House-Flipping Profit Engine (70% Rule & Hard Money)
     ========================================================================== */
  function initHouseFlip() {
    const elPurch = document.getElementById('flip-purchase-price');
    if (!elPurch) return;

    function runCalc() {
      const purch = parseCurrency(elPurch.value) || 220000;
      const arv = parseCurrency(document.getElementById('flip-arv')?.value) || 350000;
      const rehab = parseCurrency(document.getElementById('flip-rehab-budget')?.value) || 50000;
      const months = parseInt(document.getElementById('flip-holding-months')?.value, 10) || 6;
      const investorRulePct = (parseFloat(document.getElementById('flip-rule-pct')?.value) || EP_BM.houseFlip.investorRuleDefault) / 100;
      const ltcPct = (parseFloat(document.getElementById('flip-loan-pct')?.value) || EP_BM.houseFlip.ltcDefault) / 100;
      const loanRate = (parseFloat(document.getElementById('flip-loan-rate')?.value) || EP_BM.houseFlip.hardMoneyRateDefault) / 100;
      const pointsPct = (parseFloat(document.getElementById('flip-loan-points')?.value) || EP_BM.houseFlip.hardMoneyPointsDefault) / 100;
      const monthlyHolding = parseCurrency(document.getElementById('flip-monthly-holding')?.value) || EP_BM.houseFlip.holdingCostMonthlyDefault;
      const sellingOverheadPct = (parseFloat(document.getElementById('flip-selling-overhead-pct')?.value) || EP_BM.houseFlip.sellingOverheadDefault) / 100;

      const totalProjectCost = purch + rehab;
      const loanAmount = totalProjectCost * ltcPct;
      const equityDown = totalProjectCost - loanAmount;
      const loanPointsFee = loanAmount * pointsPct; // 1 point = 1% of loan amount
      const loanInterest = loanAmount * loanRate * (months / 12);
      const totalHolding = monthlyHolding * months;
      const buyingCosts = 3500; // title and lender legal
      const sellingOverhead = arv * sellingOverheadPct;

      const totalFinancingAndHolding = loanPointsFee + loanInterest + totalHolding;
      const totalExpenditure = totalProjectCost + totalFinancingAndHolding + buyingCosts + sellingOverhead;
      const netProfit = Math.round(arv - totalExpenditure);

      const cashCapitalRequired = equityDown + loanPointsFee + buyingCosts + totalHolding;
      const cocROI = cashCapitalRequired > 0 ? ((netProfit / cashCapitalRequired) * 100).toFixed(1) : 0;
      const annualROI = months > 0 ? (cocROI * (12 / months)).toFixed(1) : 0;

      // Investor Rule MAO = ARV * RulePct - Rehab
      const mao = Math.round((arv * investorRulePct) - rehab);

      // UI
      const elNet = document.getElementById('flip-net-profit');
      if (elNet) elNet.textContent = formatCurrency(netProfit);

      const elMao = document.getElementById('flip-mao-price');
      if (elMao) elMao.textContent = formatCurrency(mao);

      const elSubtext = document.getElementById('flip-subtext');
      if (elSubtext) {
        elSubtext.textContent = `Total net cash return after paying purchase, renovation, hard money financing, $${monthlyHolding}/mo holding, and ${(sellingOverheadPct * 100).toFixed(0)}% resale overhead.`;
      }

      const elCoc = document.getElementById('flip-coc-roi');
      if (elCoc) elCoc.textContent = cocROI + '%';

      const elAnnual = document.getElementById('flip-annual-roi');
      if (elAnnual) elAnnual.textContent = annualROI + '%';

      const elCash = document.getElementById('flip-cash-required');
      if (elCash) elCash.textContent = formatCurrency(cashCapitalRequired);

      const elHolding = document.getElementById('flip-total-holding');
      if (elHolding) elHolding.textContent = formatCurrency(totalFinancingAndHolding);

      const elTagProfit = document.getElementById('flip-tag-profit');
      if (elTagProfit) elTagProfit.textContent = `Net: ${formatCurrency(netProfit)}`;

      // Legend
      const cfP = document.getElementById('cf-flip-purch');
      if (cfP) cfP.textContent = '$' + Math.round(purch / 1000) + 'k';
      const cfR = document.getElementById('cf-flip-rehab');
      if (cfR) cfR.textContent = '$' + Math.round(rehab / 1000) + 'k';
      const cfH = document.getElementById('cf-flip-holding');
      if (cfH) cfH.textContent = '$' + Math.round(totalFinancingAndHolding / 1000) + 'k';
      const cfS = document.getElementById('cf-flip-selling');
      if (cfS) cfS.textContent = '$' + Math.round(sellingOverhead / 1000) + 'k';
      const cfPr = document.getElementById('cf-flip-profit');
      if (cfPr) cfPr.textContent = '$' + (netProfit / 1000).toFixed(1) + 'k';

      renderFlipChart(purch, rehab, totalFinancingAndHolding, sellingOverhead, Math.max(0, netProfit));
    }

    function renderFlipChart(purch, rehab, holding, selling, profit) {
      const canvas = document.getElementById('flip-chart-canvas');
      if (!canvas || typeof Chart === 'undefined') return;

      if (chartInstances.flip) chartInstances.flip.destroy();
      const isDark = document.documentElement.getAttribute('data-theme') === 'dark';

      chartInstances.flip = new Chart(canvas.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: ['Purchase Price', 'Rehab Budget', 'Financing & Holding', 'Selling Overhead', 'Net Flip Profit'],
          datasets: [
            {
              data: [Math.round(purch), Math.round(rehab), Math.round(holding), Math.round(selling), Math.round(profit)],
              backgroundColor: ['#09131f', '#f59e0b', '#ef4444', '#0d9488', '#059669'],
              borderWidth: 2,
              borderColor: isDark ? '#0f172a' : '#ffffff'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '68%',
          plugins: { legend: { display: false } }
        }
      });
    }

    // Presets
    document.querySelectorAll('.js-flip-rule-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-flip-rule-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('flip-rule-pct');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-flip-ltc-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-flip-ltc-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('flip-loan-pct');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-flip-rate-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-flip-rate-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('flip-loan-rate');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-flip-points-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-flip-points-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('flip-loan-points');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.querySelectorAll('.js-flip-holding-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-flip-holding-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('flip-monthly-holding');
        if (input) input.value = parseFloat(this.dataset.val).toLocaleString('en-US');
        runCalc();
      });
    });

    document.querySelectorAll('.js-flip-overhead-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-flip-overhead-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('flip-selling-overhead-pct');
        if (input) input.value = this.dataset.val;
        runCalc();
      });
    });

    document.getElementById('btn-recalc-flip')?.addEventListener('click', runCalc);
    document.querySelectorAll('#flip-form input').forEach(input => {
      input.addEventListener('input', runCalc);
    });

    runCalc();
  }

  /* ==========================================================================
     4. Mortgage Recast Engine
     ========================================================================== */
  function initMortgageRecast() {
    const elBal = document.getElementById('recast-balance');
    if (!elBal) return;

    function runCalc() {
      const balance = parseCurrency(elBal.value) || 265000;
      const ratePct = parseFloat(document.getElementById('recast-rate')?.value) || EP_BM.mortgageRates.fixed30.rate;
      const yearsLeft = parseInt(document.getElementById('recast-years-left')?.value, 10) || 25;
      const lumpSum = parseCurrency(document.getElementById('recast-lump-sum')?.value) || EP_BM.mortgageRecast.lumpSumDefault;
      const recastFee = parseCurrency(document.getElementById('recast-fee')?.value) || EP_BM.mortgageRecast.feeDefault;

      const oldPayment = calcStandardPayment(balance, ratePct, yearsLeft);
      const newBal = Math.max(0, balance - lumpSum);
      const newPayment = calcStandardPayment(newBal, ratePct, yearsLeft);
      const monthlySavings = Math.max(0, oldPayment - newPayment);
      const annualSavings = monthlySavings * 12;

      const totalMonths = yearsLeft * 12;
      const oldRemainingInterest = Math.max(0, (oldPayment * totalMonths) - balance);
      const newRemainingInterest = Math.max(0, (newPayment * totalMonths) - newBal);
      const lifetimeInterestSaved = Math.max(0, oldRemainingInterest - newRemainingInterest);

      // UI
      const elNewPay = document.getElementById('recast-new-payment');
      if (elNewPay) elNewPay.textContent = formatCurrency(newPayment) + '/mo';

      const elSavings = document.getElementById('recast-monthly-savings');
      if (elSavings) elSavings.textContent = `+${formatCurrency(monthlySavings)}/month`;

      const elOldPay = document.getElementById('recast-old-payment');
      if (elOldPay) elOldPay.textContent = formatCurrency(oldPayment) + '/mo';

      const elNewTile = document.getElementById('recast-new-tile-payment');
      if (elNewTile) elNewTile.textContent = formatCurrency(newPayment) + '/mo';

      const elTileSav = document.getElementById('recast-tile-savings');
      if (elTileSav) elTileSav.textContent = `Save ${formatCurrency(monthlySavings)} Every Month`;

      const elFee = document.getElementById('recast-summary-fee');
      if (elFee) elFee.textContent = formatCurrency(recastFee);

      const elResSav = document.getElementById('recast-res-monthly-savings');
      if (elResSav) elResSav.textContent = `+${formatCurrency(monthlySavings)}/mo`;

      const elResRemInt = document.getElementById('recast-res-remaining-interest');
      if (elResRemInt) elResRemInt.textContent = formatCurrency(newRemainingInterest);

      const elResLump = document.getElementById('recast-res-lump');
      if (elResLump) elResLump.textContent = formatCurrency(lumpSum);

      const elResAnn = document.getElementById('recast-res-annual-savings');
      if (elResAnn) elResAnn.textContent = `+${formatCurrency(annualSavings)}/yr`;
    }

    // Presets
    document.querySelectorAll('.js-recast-bal-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-recast-bal-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        elBal.value = parseFloat(this.dataset.bal).toLocaleString('en-US');
        runCalc();
      });
    });

    document.querySelectorAll('.js-recast-lump-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-recast-lump-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('recast-lump-sum');
        if (input) input.value = parseFloat(this.dataset.lump).toLocaleString('en-US');
        runCalc();
      });
    });

    document.getElementById('btn-recalc-recast')?.addEventListener('click', runCalc);
    document.querySelectorAll('#recast-form input').forEach(input => {
      input.addEventListener('input', runCalc);
    });

    runCalc();
  }

  /* ==========================================================================
     5. HELOC Payment Engine (Interest-Only Draw & Amortized Repayment)
     ========================================================================== */
  function initHeloc() {
    const elHomeVal = document.getElementById('heloc-home-val');
    if (!elHomeVal) return;

    let targetCLTV = EP_BM.helocRates.cltvDefault || 80;

    function runCalc() {
      const homeVal = parseCurrency(elHomeVal.value) || 500000;
      const firstMortgage = parseCurrency(document.getElementById('heloc-mortgage-bal')?.value) || 250000;
      const drawn = parseCurrency(document.getElementById('heloc-amount-drawn')?.value) || 60000;
      const ratePct = parseFloat(document.getElementById('heloc-interest-rate')?.value) || EP_BM.helocRates.benchmark;
      const repayYears = parseInt(document.getElementById('heloc-repay-years')?.value, 10) || 20;

      // Draw period calculation (Interest-Only)
      const rMonthly = (ratePct / 100) / 12;
      const ioPayment = Math.round(drawn * rMonthly);

      // Repayment period calculation (Full Amortization)
      const repayPayment = Math.round(calcStandardPayment(drawn, ratePct, repayYears));
      const paymentShock = repayPayment - ioPayment;

      const totalDebt = firstMortgage + drawn;
      const cltv = homeVal > 0 ? ((totalDebt / homeVal) * 100).toFixed(1) : 0;
      const equityLeft = Math.max(0, homeVal - totalDebt);

      // UI
      const elIo = document.getElementById('heloc-io-payment');
      if (elIo) elIo.textContent = formatCurrency(ioPayment) + '/mo';

      const elShock = document.getElementById('heloc-shock-payment');
      if (elShock) elShock.textContent = formatCurrency(repayPayment) + '/mo';

      const elShockText = document.getElementById('heloc-shock-text');
      if (elShockText) {
        elShockText.textContent = `When your draw period ends, mandatory principal amortization begins over ${repayYears} years, increasing your payment by +${formatCurrency(paymentShock)}/mo (+${Math.round((paymentShock / (ioPayment || 1)) * 100)}%).`;
      }

      const elDrawTile = document.getElementById('heloc-draw-tile-payment');
      if (elDrawTile) elDrawTile.textContent = formatCurrency(ioPayment) + '/mo';

      const elRepayTile = document.getElementById('heloc-repay-tile-payment');
      if (elRepayTile) elRepayTile.textContent = formatCurrency(repayPayment) + '/mo';

      const elIndicator = document.getElementById('indicator-cltv');
      if (elIndicator) elIndicator.textContent = cltv + '%';

      const elBadge = document.getElementById('heloc-cltv-badge');
      if (elBadge) {
        if (parseFloat(cltv) <= targetCLTV) {
          elBadge.textContent = `✓ ${cltv}% Safe (Under ${targetCLTV}% CLTV)`;
          elBadge.style.background = '#d1fae5';
          elBadge.style.color = '#065f46';
        } else {
          elBadge.textContent = `⚠️ ${cltv}% Exceeds Target (${targetCLTV}% CLTV)`;
          elBadge.style.background = '#fee2e2';
          elBadge.style.color = '#991b1b';
        }
      }

      const elFill = document.getElementById('heloc-cltv-fill');
      if (elFill) elFill.style.width = Math.min(100, parseFloat(cltv)) + '%';

      const elTot = document.getElementById('heloc-total-debt');
      if (elTot) elTot.textContent = formatCurrency(totalDebt);

      const elEq = document.getElementById('heloc-equity-left');
      if (elEq) elEq.textContent = formatCurrency(equityLeft);
    }

    // Presets
    document.querySelectorAll('.js-heloc-draw-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-heloc-draw-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('heloc-amount-drawn');
        if (input) input.value = parseFloat(this.dataset.draw).toLocaleString('en-US');
        runCalc();
      });
    });

    document.querySelectorAll('.js-heloc-cltv-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-heloc-cltv-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        targetCLTV = parseFloat(this.dataset.cltv) || 80;
        runCalc();
      });
    });

    document.querySelectorAll('#heloc-draw-pills .term-pill-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('#heloc-draw-pills .term-pill-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        runCalc();
      });
    });

    document.getElementById('btn-recalc-heloc')?.addEventListener('click', runCalc);
    document.querySelectorAll('#heloc-form input').forEach(input => {
      input.addEventListener('input', runCalc);
    });

    runCalc();
  }

  /* ==========================================================================
     6. Home Replacement-Cost Engine (Coverage A Rebuild)
     ========================================================================== */
  function initReplacementCost() {
    const elSqft = document.getElementById('rebuild-sqft');
    if (!elSqft) return;

    function runCalc() {
      const sqft = parseInt(elSqft.value, 10) || 2400;
      const costPerSqft = parseFloat(document.getElementById('rebuild-cost-persqft')?.value) || EP_BM.replacementCost.baseCostDefault;
      const foundation = document.getElementById('rebuild-foundation')?.value || 'crawlspace';
      const basementCost = parseCurrency(document.getElementById('rebuild-basement-cost')?.value) || EP_BM.replacementCost.basementDefault;
      const garageCost = parseCurrency(document.getElementById('rebuild-garage-cost')?.value) || EP_BM.replacementCost.garage2CarDefault;
      const debrisPct = (parseFloat(document.getElementById('rebuild-debris-pct')?.value) || EP_BM.replacementCost.debrisDefaultPct) / 100;

      const livingStructure = sqft * costPerSqft;
      const foundationAdd = foundation === 'basement' ? basementCost : 0;
      const addons = foundationAdd + garageCost;

      const subtotal = livingStructure + addons;
      const debrisRemoval = subtotal * debrisPct;
      const totalCost = Math.round(subtotal + debrisRemoval);
      const effectivePerSqft = Math.round(totalCost / sqft);

      // UI
      const elTot = document.getElementById('rebuild-total-cost');
      if (elTot) elTot.textContent = formatCurrency(totalCost);

      const elSqftCost = document.getElementById('rebuild-sqft-cost');
      if (elSqftCost) elSqftCost.textContent = `$${effectivePerSqft}/sq ft`;

      const elLiv = document.getElementById('rebuild-res-living');
      if (elLiv) elLiv.textContent = formatCurrency(livingStructure);

      const elAdd = document.getElementById('rebuild-res-addons');
      if (elAdd) elAdd.textContent = formatCurrency(addons);

      const elDeb = document.getElementById('rebuild-res-debris');
      if (elDeb) elDeb.textContent = formatCurrency(debrisRemoval);

      const elEff = document.getElementById('rebuild-res-per-sqft');
      if (elEff) elEff.textContent = `$${effectivePerSqft}`;
    }

    // Tier presets
    document.querySelectorAll('.js-rebuild-tier-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rebuild-tier-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const cost = parseFloat(this.dataset.cost);
        const input = document.getElementById('rebuild-cost-persqft');
        if (input) input.value = cost;
        const ind = document.getElementById('indicator-base-sqft');
        if (ind) ind.textContent = `$${cost}/sqft`;
        runCalc();
      });
    });

    document.querySelectorAll('.js-rebuild-sqft-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rebuild-sqft-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        elSqft.value = this.dataset.sqft;
        runCalc();
      });
    });

    document.querySelectorAll('.js-rebuild-base-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rebuild-base-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('rebuild-basement-cost');
        if (input) input.value = parseFloat(this.dataset.cost).toLocaleString('en-US');
        runCalc();
      });
    });

    document.querySelectorAll('.js-rebuild-gar-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-rebuild-gar-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('rebuild-garage-cost');
        if (input) input.value = parseFloat(this.dataset.cost).toLocaleString('en-US');
        runCalc();
      });
    });

    document.getElementById('btn-recalc-rebuild')?.addEventListener('click', runCalc);
    document.querySelectorAll('#rebuild-form input, #rebuild-form select').forEach(input => {
      input.addEventListener('change', runCalc);
      input.addEventListener('input', runCalc);
    });

    runCalc();
  }

  /* ==========================================================================
     7. Insurance Deductible Savings Engine
     ========================================================================== */
  function initDeductibleSavings() {
    const elPrem = document.getElementById('ded-current-premium');
    if (!elPrem) return;

    let activeCurrentDed = 1000;
    let activeProposedDed = 2500;

    function runCalc() {
      const prem = parseCurrency(elPrem.value) || EP_BM.deductibleSavings.genericPremiumDefault;
      const discountPct = (parseFloat(document.getElementById('ded-discount-pct')?.value) || EP_BM.deductibleSavings.defaultReductionPct) / 100;

      const riskGap = Math.max(0, activeProposedDed - activeCurrentDed);
      const annualSavings = Math.round(prem * discountPct);
      const breakEvenYears = annualSavings > 0 ? (riskGap / annualSavings).toFixed(1) : 0;
      const fiveYearGain = (annualSavings * 5);
      const tenYearGain = (annualSavings * 10) - riskGap;

      // UI
      const elInd = document.getElementById('indicator-ded-savings');
      if (elInd) elInd.textContent = formatCurrency(annualSavings) + '/yr';

      const elBreakeven = document.getElementById('ded-breakeven-years');
      if (elBreakeven) elBreakeven.textContent = `${breakEvenYears} Years`;

      const elSubtext = document.getElementById('ded-subtext');
      if (elSubtext) {
        elSubtext.textContent = `If you go at least ${breakEvenYears} years without filing a claim, your accumulated premium savings of ${formatCurrency(annualSavings)}/yr will fully cover the ${formatCurrency(riskGap)} deductible risk gap.`;
      }

      const elResAnn = document.getElementById('ded-res-annual-savings');
      if (elResAnn) elResAnn.textContent = `+${formatCurrency(annualSavings)}/yr`;

      const elResGap = document.getElementById('ded-res-risk-gap');
      if (elResGap) elResGap.textContent = formatCurrency(riskGap);

      const elRes5 = document.getElementById('ded-res-5yr-gain');
      if (elRes5) elRes5.textContent = `+${formatCurrency(fiveYearGain)}`;

      const elRes10 = document.getElementById('ded-res-10yr-gain');
      if (elRes10) elRes10.textContent = `+${formatCurrency(tenYearGain)}`;
    }

    // Pills
    document.querySelectorAll('#ded-current-pills .kids-pill-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('#ded-current-pills .kids-pill-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        activeCurrentDed = parseFloat(this.dataset.ded) || 1000;
        runCalc();
      });
    });

    document.querySelectorAll('#ded-proposed-pills .kids-pill-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('#ded-proposed-pills .kids-pill-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        activeProposedDed = parseFloat(this.dataset.ded) || 2500;
        runCalc();
      });
    });

    document.querySelectorAll('.js-ded-prem-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-ded-prem-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        elPrem.value = parseFloat(this.dataset.prem).toLocaleString('en-US');
        runCalc();
      });
    });

    document.querySelectorAll('.js-ded-disc-preset').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.js-ded-disc-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const input = document.getElementById('ded-discount-pct');
        if (input) input.value = this.dataset.disc;
        runCalc();
      });
    });

    document.getElementById('btn-recalc-deductible')?.addEventListener('click', runCalc);
    document.querySelectorAll('#deductible-form input').forEach(input => {
      input.addEventListener('input', runCalc);
    });

    runCalc();
  }

  // Initialize all available modules on page load
  document.addEventListener('DOMContentLoaded', function () {
    initRentVsBuy();
    initSellerProceeds();
    initHouseFlip();
    initMortgageRecast();
    initHeloc();
    initReplacementCost();
    initDeductibleSavings();
  });

  // Re-render charts on theme change
  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.attributeName === 'data-theme') {
        initRentVsBuy();
        initSellerProceeds();
        initHouseFlip();
      }
    });
  });
  observer.observe(document.documentElement, { attributes: true });

})();
