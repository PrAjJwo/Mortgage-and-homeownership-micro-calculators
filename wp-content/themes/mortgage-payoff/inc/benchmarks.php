<?php
/**
 * EquityPace — Centralized 2026 Financial Benchmarks & Data Layer
 * Single source of truth for market benchmarks, fallback estimates, and scenario presets.
 * Updated as of September 3, 2026.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EquityPace_Benchmarks {

	/**
	 * Retrieve all centralized benchmarks as a structured array
	 */
	public static function get_all() {
		return array(
			// Mortgage Rates
			'mortgageRates' => array(
				'fixed30' => array(
					'rate'        => 6.71,
					'date'        => 'September 3, 2026',
					'source'      => 'Freddie Mac Primary Mortgage Market Survey',
					'label'       => '30-Year Fixed Benchmark',
					'badge'       => 'Market benchmark updated September 3, 2026',
				),
				'fixed15' => array(
					'rate'        => 6.04,
					'date'        => 'September 3, 2026',
					'source'      => 'Freddie Mac Primary Mortgage Market Survey',
					'label'       => '15-Year Fixed Benchmark',
					'badge'       => 'Market benchmark updated September 3, 2026',
				),
				'homeEquityLoans' => array(
					'year5'  => 8.13,
					'year10' => 8.28,
					'year15' => 8.21,
					'date'   => 'September 2, 2026',
					'source' => 'Bankrate National Benchmark',
				),
			),

			// HELOC Rates
			'helocRates' => array(
				'benchmarkRate' => 7.29,
				'date'          => 'September 2, 2026',
				'source'        => 'Bankrate Benchmark',
				'cltvDefault'   => 80,
				'cltvPresets'   => array( 75, 80, 85, 90 ),
				'defaultDrawYears'  => 10,
				'defaultRepayYears' => 20,
				'badge'         => 'Benchmark updated Sep. 2, 2026',
				'note'          => 'HELOC rates are commonly variable and may change when the underlying index or lender margin changes.',
			),

			// Mortgage Payoff Defaults
			'mortgagePayoff' => array(
				'exampleBalance'      => 265000,
				'exampleTermYears'    => 25,
				'defaultRate'         => 6.71,
				'defaultExtraMonthly' => 200,
				'defaultPenalty'      => 0,
				'extraMonthlyPresets' => array( 0, 50, 100, 200, 250, 500, 1000 ),
				'lumpSumPresets'      => array( 1000, 3000, 5000, 10000, 25000, 50000 ),
				'payoffQuoteNote'     => 'Your current principal balance may differ from your lender’s official payoff quote. A payoff quote may include accrued interest, fees, and other loan-specific charges.',
				'servicerWarning'     => 'Confirm that your mortgage servicer applies extra payments to principal. Some loans may contain prepayment penalties or other restrictions. Check your loan documents or contact your lender.',
			),

			// Property Tax Fallback
			'propertyTax' => array(
				'nationalFallbackRate' => 0.90, // % annually
				'label'                => 'National fallback estimate',
				'note'                 => 'Property taxes vary significantly by state, county, municipality, assessment ratio, and exemptions.',
			),

			// Homeowners Insurance Defaults
			'homeownersInsurance' => array(
				'genericAnnualDefault' => 2750, // ~$229/mo
				'coverage450kAnnual'   => 3374, // ~$281/mo
				'benchmarks'           => array(
					'300k' => 2424,
					'350k' => 2740,
					'400k' => 2628,
					'450k' => 3374,
				),
				'note' => 'Home insurance premiums vary by state, ZIP code, replacement cost, deductible, roof age, construction type, claims history, weather exposure, and coverage limits.',
			),

			// Combined Tax + Insurance Fallback
			'combinedTaxInsurance' => array(
				'fallbackRate' => 1.60, // % annually
				'note'         => 'Property taxes and homeowners insurance should be entered as separate dollar amounts whenever known.',
			),

			// Home Maintenance Reserve
			'maintenance' => array(
				'defaultRate' => 1.5,
				'presets'     => array(
					array( 'label' => 'Newer Home', 'rate' => 1.0 ),
					array( 'label' => 'Average Home (Default)', 'rate' => 1.5 ),
					array( 'label' => 'Older Home', 'rate' => 2.0 ),
					array( 'label' => 'High-Maintenance', 'rate' => 3.0 ),
				),
				'planningRange' => '1%–4% of property value annually',
				'label'         => 'Annual maintenance planning estimate',
			),

			// Take-Home Pay
			'takeHomePay' => array(
				'defaultRatio' => 75,
				'presets'      => array( 70, 75, 80 ),
				'label'        => 'Estimated take-home ratio',
				'note'         => 'Actual take-home pay depends on federal income tax, state and local taxes, filing status, payroll taxes, benefits, retirement contributions, deductions, and household circumstances.',
			),

			// Childcare & Daycare Benchmarks
			'childcare' => array(
				'nationalBenchmarkMonthly' => 1100, // $13,184/yr (~$1,099/mo)
				'annualBenchmark'          => 13184,
				'presets'                  => array(
					array( 'cost' => 750,  'label' => '$750 (Part-Time/Family Care)' ),
					array( 'cost' => 1100, 'label' => '$1,100 (U.S. Benchmark)', 'is_default' => true ),
					array( 'cost' => 1300, 'label' => '$1,300 (Infant Center Care)' ),
					array( 'cost' => 1440, 'label' => '$1,440 (Daycare Listed Rate)' ),
					array( 'cost' => 2000, 'label' => '$2,000 (Major Metro Scenario)' ),
					array( 'cost' => 2750, 'label' => '$2,750 (High-Cost Metro Scenario)' ),
					array( 'cost' => 3500, 'label' => '$3,500 (Urban Private Care)' ),
				),
				'defaultHorizonYears' => 3,
				'horizonOptions'      => array( 1, 2, 3, 4, 5 ),
				'horizonLabel'        => 'Years until childcare cost changes or ends',
				'note'                => 'Public kindergarten does not necessarily eliminate every childcare expense (e.g. before/after-school care, summer camps).',
			),

			// Debt-to-Income (DTI) Guidelines
			'dti' => array(
				'traditionalFront'    => 28,
				'traditionalBack'     => 36,
				'moderateBack'        => 43,
				'higherManual'        => 45,
				'higherAutomated'     => 50,
				'guidelineLabel'      => 'Traditional affordability guideline',
				'note'                => 'DTI requirements vary by loan program, lender, credit profile, reserves, automated underwriting results, and other borrower factors.',
				'safeHousingTarget'   => 33, // % of net take-home
				'safeHousingLabel'    => 'Suggested household budgeting target',
				'livingBufferRate'    => 26, // % of net income
				'livingBufferMin'     => 2000, // $/mo
				'livingBufferLabel'   => 'Recommended monthly living-cost buffer',
			),

			// Buyer Closing Costs
			'buyerClosingCosts' => array(
				'defaultPct' => 3.0,
				'range'      => '2%–5% of purchase price',
				'note'       => 'Closing costs exclude down payment. Typically covers lender origination, appraisal, title policy, escrow, and prepaid taxes/insurance.',
			),

			// Real Estate Broker Compensation
			'brokerCompensation' => array(
				'defaultPct' => 5.0,
				'presets'    => array( 2.5, 3.0, 4.0, 5.0, 6.0 ),
				'label'      => 'Example total broker compensation (negotiable)',
				'note'       => 'Real estate broker commissions are negotiable by law and agreement between client and broker. Not an official or fixed standard.',
			),

			// Seller Closing / Transfer Costs
			'sellerClosingCosts' => array(
				'defaultPct' => 1.5,
				'range'      => '1%–2%',
				'label'      => 'Estimated seller title, escrow, recording and transfer costs',
				'note'       => 'Actual seller closing costs vary by state, county, contract, title company, transfer-tax rules and local customs.',
			),

			// Seller Concessions & Repairs
			'sellerConcessions' => array(
				'defaultAmount' => 0,
				'presets'       => array( 2500, 5000, 10000, 15000 ),
				'label'         => 'Optional repair credits / seller concessions',
			),

			// House-Flipping & 70% Rule
			'houseFlip' => array(
				'defaultMaoPct'       => 70,
				'maoPresets'          => array( 65, 70, 75 ),
				'maoLabel'            => 'Investor rule-of-thumb (70% Rule)',
				'hardMoneyRate'       => 11.0,
				'hardMoneyRatePresets'=> array( 9.0, 11.0, 14.0 ),
				'hardMoneyPoints'     => 2.0,
				'hardMoneyPointsPresets' => array( 1, 2, 3, 4 ),
				'ltcPct'              => 80.0,
				'ltcPresets'          => array( 70, 75, 80, 85 ),
				'holdingCostsDefault' => 750, // $/mo
				'holdingPresets'      => array( 500, 750, 1000, 1500, 2500 ),
				'sellingOverheadPct'  => 8.0,
				'sellingOverheadRange'=> '6%–10%',
			),

			// Mortgage Recast
			'mortgageRecast' => array(
				'defaultFee'         => 250,
				'feeRange'           => '$150–$500',
				'defaultLumpSum'     => 10000,
				'lumpSumPresets'     => array( 5000, 10000, 20000, 50000, 75000, 100000 ),
				'explanation'        => 'A mortgage recast reduces the required monthly payment after a qualifying principal reduction while generally keeping the existing interest rate and remaining maturity date.',
				'eligibilityNote'    => 'Recasting availability and minimum lump-sum thresholds vary by loan servicer. Most FHA, VA, and USDA loans are not eligible for recasting.',
			),

			// Construction / Replacement Cost
			'constructionCost' => array(
				'defaultPerSqft' => 200,
				'tiers'          => array(
					array( 'cost' => 125, 'label' => 'Economy / Basic ($125/sq ft)' ),
					array( 'cost' => 150, 'label' => 'Standard ($150/sq ft)' ),
					array( 'cost' => 200, 'label' => 'Enhanced / Mid-Range ($200/sq ft)', 'is_default' => true ),
					array( 'cost' => 300, 'label' => 'Custom ($300/sq ft)' ),
					array( 'cost' => 400, 'label' => 'High-End ($400/sq ft)' ),
					array( 'cost' => 475, 'label' => 'Luxury ($450–$500+/sq ft)' ),
				),
				'basementDefault' => 50000,
				'basementPresets' => array( 25000, 50000, 75000, 100000, 150000 ),
				'garageDefault'   => 40000,
				'garagePresets'   => array( 25000, 40000, 60000, 80000 ),
				'debrisPct'       => 10.0,
				'debrisRange'     => '5%–15%',
				'note'            => 'Construction cost varies significantly by ZIP code, labor availability, building codes, site conditions, architecture, materials, contractor pricing and finish level.',
			),

			// Insurance Deductible Savings
			'deductibleSavings' => array(
				'defaultDiscountPct' => 9.0, // % discount on premium for $1k->$2.5k
				'exampleTier500to1000' => '5%–10%',
				'exampleTier1000to2500' => 'approximately 9% planning estimate',
				'note'               => 'Actual savings depend on insurer, location, property, claims history, policy structure and deductible type.',
			),

			// Rent vs. Buy Assumptions
			'rentVsBuy' => array(
				'appreciationDefault' => 3.0,
				'appreciationPresets' => array( 2.0, 3.0, 4.0, 5.0 ),
				'appreciationLabel'   => 'Assumed annual home appreciation',
				'rentInflationDefault'=> 3.0,
				'rentInflationPresets'=> array( 2.0, 3.0, 4.0, 5.0 ),
				'rentInflationLabel'  => 'Assumed annual rent increase',
				'stockReturnDefault'  => 7.0,
				'stockReturnPresets'  => array(
					array( 'rate' => 4.0, 'label' => 'Conservative (4%)' ),
					array( 'rate' => 6.0, 'label' => 'Moderate (6%)' ),
					array( 'rate' => 7.0, 'label' => 'Balanced (7%)', 'is_default' => true ),
					array( 'rate' => 9.0, 'label' => 'Aggressive (9%)' ),
				),
				'stockReturnLabel'    => 'Assumed annual investment return',
				'stockReturnNote'     => 'Investment returns are uncertain and may be negative.',
			),

			// Standard Educational Disclaimer
			'disclaimer' => 'Calculator results are estimates for educational and planning purposes only. Actual mortgage rates, lender requirements, taxes, insurance premiums, closing costs, loan fees, property values, construction costs, investment returns and other expenses may differ. Verify important figures with your lender, tax authority, insurer, financial professional, contractor or other appropriate provider.',
		);
	}

	/**
	 * Output standard educational disclaimer HTML
	 */
	public static function render_disclaimer() {
		$d = self::get_all()['disclaimer'];
		$html  = '<div class="calc-educational-disclaimer" style="margin-top: 28px; padding: 14px 18px; background: rgba(15, 23, 42, 0.03); border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 8px; font-size: 12px; line-height: 1.55; color: var(--text-muted, #64748b);">';
		$html .= '<div style="display: flex; align-items: flex-start; gap: 8px;">';
		$html .= '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:2px; color:#0ea5e9;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';
		$html .= '<div><strong>Educational & Planning Disclaimer:</strong> ' . esc_html( $d ) . '</div>';
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
}
