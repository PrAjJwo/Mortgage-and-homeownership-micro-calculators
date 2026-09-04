/**
 * EquityPace — Centralized 2026 Financial Benchmarks & Data Layer (Client-Side)
 * Single source of truth for all calculators on EquityPace.
 * Updated as of September 3, 2026.
 */
(function(window) {
  'use strict';

  const EquityPaceBenchmarks = {
    // Current Market Benchmarks
    mortgageRates: {
      fixed30: {
        rate: 6.71,
        date: 'September 3, 2026',
        source: 'Freddie Mac Primary Mortgage Market Survey',
        label: '30-Year Fixed Benchmark',
        badge: 'Market benchmark updated Sep. 3, 2026'
      },
      fixed15: {
        rate: 6.04,
        date: 'September 3, 2026',
        source: 'Freddie Mac Primary Mortgage Market Survey',
        label: '15-Year Fixed Benchmark',
        badge: 'Market benchmark updated Sep. 3, 2026'
      },
      homeEquityLoans: {
        year5: 8.13,
        year10: 8.28,
        year15: 8.21,
        date: 'September 2, 2026',
        source: 'Bankrate National Benchmark'
      }
    },

    helocRates: {
      benchmarkRate: 7.29,
      date: 'September 2, 2026',
      source: 'Bankrate Benchmark',
      cltvDefault: 80,
      cltvPresets: [75, 80, 85, 90],
      defaultDrawYears: 10,
      defaultRepayYears: 20,
      badge: 'Benchmark updated Sep. 2, 2026',
      note: 'HELOC rates are commonly variable and may change when the underlying index or lender margin changes.'
    },

    // Mortgage Payoff Defaults
    mortgagePayoff: {
      exampleBalance: 265000,
      exampleTermYears: 25,
      defaultRate: 6.71,
      defaultExtraMonthly: 200,
      defaultPenalty: 0,
      extraMonthlyPresets: [0, 50, 100, 200, 250, 500, 1000],
      lumpSumPresets: [1000, 3000, 5000, 10000, 25000, 50000],
      payoffQuoteNote: 'Your current principal balance may differ from your lender’s official payoff quote. A payoff quote may include accrued interest, fees, and other loan-specific charges.',
      servicerWarning: 'Confirm that your mortgage servicer applies extra payments to principal. Some loans may contain prepayment penalties or other restrictions. Check your loan documents or contact your lender.'
    },

    // Property Tax & Insurance Fallbacks
    propertyTax: {
      nationalFallbackRate: 0.90, // % annually
      label: 'National fallback estimate',
      note: 'Property taxes vary significantly by state, county, municipality, assessment ratio, and exemptions.'
    },

    homeownersInsurance: {
      genericAnnualDefault: 2750, // ~$229/mo
      coverage450kAnnual: 3374,   // ~$281/mo
      benchmarks: {
        300000: 2424,
        350000: 2740,
        400000: 2628,
        450000: 3374
      },
      note: 'Home insurance premiums vary by state, ZIP code, replacement cost, deductible, roof age, construction type, claims history, weather exposure, and coverage limits.'
    },

    combinedTaxInsuranceFallback: 1.60, // % annually

    // Home Maintenance Planning
    maintenance: {
      defaultRate: 1.5,
      presets: [
        { label: 'Newer Home', rate: 1.0 },
        { label: 'Average Home (Default)', rate: 1.5 },
        { label: 'Older Home', rate: 2.0 },
        { label: 'High-Maintenance Property', rate: 3.0 }
      ],
      planningRange: '1%–4% of property value annually',
      label: 'Annual maintenance planning estimate'
    },

    // Take-Home Pay
    takeHomePay: {
      defaultRatio: 75,
      presets: [70, 75, 80],
      label: 'Estimated take-home ratio',
      note: 'Actual take-home pay depends on federal income tax, state and local taxes, filing status, payroll taxes, benefits, retirement contributions, deductions, and household circumstances.'
    },

    // Childcare & Daycare
    childcare: {
      nationalBenchmarkMonthly: 1100, // $13,184/yr (~$1,099/mo)
      annualBenchmark: 13184,
      presets: [
        { cost: 750,  label: '$750 (Part-Time/Family Care)' },
        { cost: 1100, label: '$1,100 (U.S. Benchmark)', isDefault: true },
        { cost: 1300, label: '$1,300 (Infant Center Care)' },
        { cost: 1440, label: '$1,440 (Daycare Listed Rate)' },
        { cost: 2000, label: '$2,000 (Major Metro Scenario)' },
        { cost: 2750, label: '$2,750 (High-Cost Metro Scenario)' },
        { cost: 3500, label: '$3,500 (Urban Private Care)' }
      ],
      defaultHorizonYears: 3,
      horizonOptions: [1, 2, 3, 4, 5],
      horizonLabel: 'Years until childcare cost changes or ends',
      note: 'Public kindergarten does not necessarily eliminate every childcare expense (e.g. before/after-school care, summer camps).'
    },

    // DTI & Living Buffer
    dti: {
      traditionalFront: 28,
      traditionalBack: 36,
      moderateBack: 43,
      higherManual: 45,
      higherAutomated: 50,
      guidelineLabel: 'Traditional affordability guideline',
      note: 'DTI requirements vary by loan program, lender, credit profile, reserves, automated underwriting results, and other borrower factors.',
      safeHousingTarget: 33, // % of net take-home
      safeHousingLabel: 'Suggested household budgeting target',
      livingBufferRate: 26,  // % of net income
      livingBufferMin: 2000, // $/mo
      livingBufferLabel: 'Recommended monthly living-cost buffer'
    },

    // Closing Costs & Broker Compensation
    buyerClosingCosts: {
      defaultPct: 3.0,
      range: '2%–5% of purchase price',
      note: 'Closing costs exclude down payment. Typically covers lender origination, appraisal, title policy, escrow, and prepaid taxes/insurance.'
    },

    brokerCompensation: {
      defaultPct: 5.0,
      presets: [2.5, 3.0, 4.0, 5.0, 6.0],
      label: 'Example total broker compensation (negotiable)',
      note: 'Real estate broker commissions are negotiable by law and agreement between client and broker. Not an official or fixed standard.'
    },

    sellerClosingCosts: {
      defaultPct: 1.5,
      range: '1%–2%',
      label: 'Estimated seller title, escrow, recording and transfer costs',
      note: 'Actual seller closing costs vary by state, county, contract, title company, transfer-tax rules and local customs.'
    },

    sellerConcessions: {
      defaultAmount: 0,
      presets: [2500, 5000, 10000, 15000],
      label: 'Optional repair credits / seller concessions'
    },

    // House-Flipping Profit & 70% Rule
    houseFlip: {
      defaultMaoPct: 70,
      maoPresets: [65, 70, 75],
      maoLabel: 'Investor rule-of-thumb (70% Rule)',
      hardMoneyRate: 11.0,
      hardMoneyRatePresets: [9.0, 11.0, 14.0],
      hardMoneyPoints: 2.0,
      hardMoneyPointsPresets: [1, 2, 3, 4],
      ltcPct: 80.0,
      ltcPresets: [70, 75, 80, 85],
      holdingCostsDefault: 750, // $/mo
      holdingPresets: [500, 750, 1000, 1500, 2500],
      sellingOverheadPct: 8.0,
      sellingOverheadRange: '6%–10%'
    },

    // Mortgage Recast
    mortgageRecast: {
      defaultFee: 250,
      feeRange: '$150–$500',
      defaultLumpSum: 10000,
      lumpSumPresets: [5000, 10000, 20000, 50000, 75000, 100000],
      explanation: 'A mortgage recast reduces the required monthly payment after a qualifying principal reduction while generally keeping the existing interest rate and remaining maturity date.',
      eligibilityNote: 'Recasting availability and minimum lump-sum thresholds vary by loan servicer. Most FHA, VA, and USDA loans are not eligible for recasting.'
    },

    // Construction / Replacement Cost
    constructionCost: {
      defaultPerSqft: 200,
      tiers: [
        { cost: 125, label: 'Economy / Basic ($125/sq ft)' },
        { cost: 150, label: 'Standard ($150/sq ft)' },
        { cost: 200, label: 'Enhanced / Mid-Range ($200/sq ft)', isDefault: true },
        { cost: 300, label: 'Custom ($300/sq ft)' },
        { cost: 400, label: 'High-End ($400/sq ft)' },
        { cost: 475, label: 'Luxury ($450–$500+/sq ft)' }
      ],
      basementDefault: 50000,
      basementPresets: [25000, 50000, 75000, 100000, 150000],
      garageDefault: 40000,
      garagePresets: [25000, 40000, 60000, 80000],
      debrisPct: 10.0,
      debrisRange: '5%–15%',
      note: 'Construction cost varies significantly by ZIP code, labor availability, building codes, site conditions, architecture, materials, contractor pricing and finish level.'
    },

    // Deductible Savings
    deductibleSavings: {
      defaultDiscountPct: 9.0, // % discount on premium for $1k->$2.5k
      exampleTier500to1000: '5%–10%',
      exampleTier1000to2500: 'approximately 9% planning estimate',
      note: 'Actual savings depend on insurer, location, property, claims history, policy structure and deductible type.'
    },

    // Rent vs. Buy
    rentVsBuy: {
      appreciationDefault: 3.0,
      appreciationPresets: [2.0, 3.0, 4.0, 5.0],
      appreciationLabel: 'Assumed annual home appreciation',
      rentInflationDefault: 3.0,
      rentInflationPresets: [2.0, 3.0, 4.0, 5.0],
      rentInflationLabel: 'Assumed annual rent increase',
      stockReturnDefault: 7.0,
      stockReturnPresets: [
        { rate: 4.0, label: 'Conservative (4%)' },
        { rate: 6.0, label: 'Moderate (6%)' },
        { rate: 7.0, label: 'Balanced (7%)', isDefault: true },
        { rate: 9.0, label: 'Aggressive (9%)' }
      ],
      stockReturnLabel: 'Assumed annual investment return',
      stockReturnNote: 'Investment returns are uncertain and may be negative.'
    },

    // Utility Functions
    formatCurrency: function(val, showCents) {
      if (isNaN(val) || val === null) return '$0';
      if (showCents) {
        return '$' + Number(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      }
      return '$' + Math.round(val).toLocaleString('en-US');
    },

    parseCurrency: function(str) {
      if (typeof str === 'number') return str;
      if (!str) return 0;
      const clean = String(str).replace(/[^0-9.-]/g, '');
      const num = parseFloat(clean);
      return isNaN(num) ? 0 : num;
    },

    formatPercent: function(val, decimals) {
      if (isNaN(val) || val === null) return '0%';
      const d = (typeof decimals === 'number') ? decimals : 2;
      return Number(val).toFixed(d) + '%';
    },

    formatMonthsToYears: function(totalMonths) {
      const yrs = Math.floor(totalMonths / 12);
      const mos = totalMonths % 12;
      if (yrs === 0) return mos + ' mos';
      if (mos === 0) return yrs + (yrs === 1 ? ' year' : ' years');
      return yrs + (yrs === 1 ? ' yr ' : ' yrs ') + mos + (mos === 1 ? ' mo' : ' mos');
    },

    calcStandardPayment: function(principal, annualRatePct, termYears) {
      if (principal <= 0 || termYears <= 0) return 0;
      const r = (annualRatePct / 100) / 12;
      const n = termYears * 12;
      if (r === 0) return principal / n;
      const factor = Math.pow(1 + r, n);
      return principal * (r * factor) / (factor - 1);
    },

    calcPaymentFromMonths: function(principal, annualRatePct, totalMonths) {
      if (principal <= 0 || totalMonths <= 0) return 0;
      const r = (annualRatePct / 100) / 12;
      if (r === 0) return principal / totalMonths;
      const factor = Math.pow(1 + r, totalMonths);
      return principal * (r * factor) / (factor - 1);
    }
  };

  // Attach globally
  window.EquityPaceBenchmarks = EquityPaceBenchmarks;

})(typeof window !== 'undefined' ? window : this);
