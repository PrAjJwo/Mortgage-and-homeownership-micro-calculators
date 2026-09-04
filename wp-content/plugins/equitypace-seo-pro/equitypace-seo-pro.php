<?php
/**
 * Plugin Name: EquityPace SEO Pro (Rank Math Edition)
 * Plugin URI: https://equitypace.com/seo/
 * Description: Complete SEO management suite for EquityPace. Features automated & custom meta tags, Open Graph, Twitter Cards, Schema.org JSON-LD (FinancialProduct, WebApplication, BreadcrumbList, FAQPage), dynamic XML Sitemaps, and Rank Math-style on-page SEO analyzer.
 * Version: 1.0.0
 * Author: EquityPace SEO Team
 * Text Domain: equitypace-seo
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Plugin Constants
define( 'EQUITYPACE_SEO_VERSION', '1.0.0' );
define( 'EQUITYPACE_SEO_PATH', plugin_dir_path( __FILE__ ) );
define( 'EQUITYPACE_SEO_URL', plugin_dir_url( __FILE__ ) );
define( 'EQUITYPACE_SEO_ACTIVE', true );

/**
 * Main EquityPace SEO Core Class
 */
class EquityPace_SEO {

	/**
	 * Singleton instance
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include plugin dependencies
	 */
	private function includes() {
		require_once EQUITYPACE_SEO_PATH . 'includes/class-seo-meta.php';
		require_once EQUITYPACE_SEO_PATH . 'includes/class-schema-generator.php';
		require_once EQUITYPACE_SEO_PATH . 'includes/class-xml-sitemap.php';
		require_once EQUITYPACE_SEO_PATH . 'includes/class-admin-settings.php';
		require_once EQUITYPACE_SEO_PATH . 'includes/class-page-metabox.php';
	}

	/**
	 * Initialize modules
	 */
	private function init_hooks() {
		new EquityPace_SEO_Meta();
		new EquityPace_SEO_Schema();
		new EquityPace_SEO_Sitemap();
		new EquityPace_SEO_Admin();
		new EquityPace_SEO_Metabox();
	}

	/**
	 * Get global plugin options with defaults
	 */
	public static function get_options() {
		$defaults = array(
			'title_separator'     => '|',
			'home_title'          => 'Early Mortgage Payoff Calculator with Current Balance & Extra Payments | EquityPace',
			'home_description'    => 'Calculate how fast you can pay off your home loan using current mortgage balance and irregular lump-sum extra payments. Free amortization & interest savings schedule.',
			'home_keyword'        => 'mortgage payoff calculator current balance',
			'default_og_image'    => get_template_directory_uri() . '/assets/images/mortgage-payoff-og.jpg',
			'twitter_handle'      => '@equitypace',
			'google_verification'=> '',
			'bing_verification'   => '',
			'google_analytics_id' => '',
			'enable_sitemap'      => 1,
		);

		$saved = get_option( 'equitypace_seo_options', array() );
		return wp_parse_args( $saved, $defaults );
	}

	/**
	 * Get single option
	 */
	public static function get_option( $key, $default = '' ) {
		$opts = self::get_options();
		return $opts[ $key ] ?? $default;
	}

	/**
	 * Predefined SEO Metadata Dictionary for All Calculators
	 */
	public static function get_tool_seo_defaults( $slug = '' ) {
		$tools = array(
			'home' => array(
				'title'       => 'Early Mortgage Payoff Calculator with Current Balance & Irregular Payments',
				'description' => 'Calculate how fast you can pay off your home loan using current mortgage balance and irregular lump-sum extra payments. Free amortization & interest savings schedule.',
				'keyword'     => 'mortgage payoff calculator current balance',
				'category'    => 'Mortgage & Payoff Strategies',
				'features'    => array(
					'Current mortgage balance input support',
					'Target years to payoff acceleration calculation',
					'Additional monthly principal payment calculation',
					'Irregular and lump-sum extra payment modeling (annual bonuses, tax refunds, commissions)',
					'Interactive loan balance comparison curve',
					'Year-by-year and monthly amortization breakdown',
					'Lifetime interest savings calculation',
				),
			),
			'home-affordability-daycare' => array(
				'title'       => 'Home Affordability Calculator with Daycare Costs & DTI Limits',
				'description' => 'Accurately calculate how much house you can afford when factoring in childcare, daycare tuition, and strict DTI limits. Plan your family home budget with confidence.',
				'keyword'     => 'home affordability daycare costs',
				'category'    => 'Affordability & Family Planning',
				'features'    => array(
					'Multi-child daycare and childcare tuition breakdown',
					'Front-end and back-end DTI threshold calculation',
					'Down payment and property tax estimation',
					'Maximum recommended home purchase budget',
				),
			),
			'rent-vs-buy' => array(
				'title'       => 'Rent vs. Buy Calculator with Closing Costs & True ROI',
				'description' => 'Compare true costs of renting vs. buying a home factoring in 2%-5% closing costs, HOA fees, property taxes, maintenance, and opportunity cost of down payment.',
				'keyword'     => 'rent vs buy calculator closing costs',
				'category'    => 'Real Estate & Investment Analysis',
				'features'    => array(
					'Upfront closing costs (2% - 5%) integration',
					'Down payment investment opportunity cost modeling',
					'Home appreciation vs rent inflation curve',
					'Net financial breakeven horizon in years',
				),
			),
			'seller-net-proceeds' => array(
				'title'       => 'Seller Net-Proceeds Calculator with Closing Fees & Commissions',
				'description' => 'Calculate exactly how much cash you pocket after selling your house. Deduct mortgage payoff, 5%-6% realtor commissions, transfer taxes, and closing concessions.',
				'keyword'     => 'seller net proceeds calculator',
				'category'    => 'Real Estate & Investment Analysis',
				'features'    => array(
					'Existing mortgage principal payoff deduction',
					'Listing and buyer broker commission sliders',
					'Title, escrow, and state transfer tax breakdown',
					'Seller repair concession and net walk-away cash',
				),
			),
			'house-flipping-profit' => array(
				'title'       => 'House-Flipping Profit Calculator: 70% Rule & Holding Costs',
				'description' => 'Calculate net ROI on residential fix & flips. Accounts for 70% ARV rule, renovation budget, hard money loan interest, holding costs, and exit fees.',
				'keyword'     => 'house flipping profit calculator 70 rule',
				'category'    => 'Real Estate & Investment Analysis',
				'features'    => array(
					'70% ARV Maximum Allowable Offer (MAO) benchmark',
					'Hard money loan points and monthly carrying interest',
					'Holding costs (utilities, taxes, insurance, HOA)',
					'Net flipping profit and annualized return on capital',
				),
			),
			'mortgage-recast' => array(
				'title'       => 'Mortgage Recast Calculator: Lump-Sum Amortization & Savings',
				'description' => 'See how a lump-sum principal recast lowers your monthly mortgage payment without changing your interest rate or refinancing closing costs.',
				'keyword'     => 'mortgage recast calculator lump sum',
				'category'    => 'Mortgage & Payoff Strategies',
				'features'    => array(
					'Lump-sum principal reduction re-amortization',
					'New reduced monthly payment vs original payment',
					'Lifetime interest savings calculation',
					'Comparison with refinancing closing costs',
				),
			),
			'heloc-payment' => array(
				'title'       => 'HELOC Interest-Only Payment Calculator: Draw & Repayment Shock',
				'description' => 'Calculate monthly interest-only payments during your HELOC draw period, plus principal & interest payment shock during the 20-year repayment phase.',
				'keyword'     => 'heloc interest only payment calculator',
				'category'    => 'Mortgage & Payoff Strategies',
				'features'    => array(
					'Interest-only draw period monthly payment calculation',
					'Full principal-and-interest repayment payment shock modeling',
					'Variable interest rate scenario testing',
					'Total financing cost over loan life',
				),
			),
			'home-replacement-cost' => array(
				'title'       => 'Home Replacement-Cost Calculator: Square Footage & Materials',
				'description' => 'Estimate full reconstruction cost of your home for accurate hazard & homeowners insurance coverage. Factors in regional labor, architectural style, and finishes.',
				'keyword'     => 'home replacement cost calculator insurance',
				'category'    => 'Home Value & Insurance Coverage',
				'features'    => array(
					'Living area square footage and story adjustments',
					'Construction quality tier (Standard, Custom, Luxury)',
					'Garage, foundation, and debris removal additions',
					'Estimated total replacement cost for insurance limits',
				),
			),
			'insurance-deductible-savings' => array(
				'title'       => 'Insurance Deductible Savings Calculator: Higher Deductible ROI',
				'description' => 'Calculate how much you save on home insurance premiums by raising your deductible from $500 to $1,000, $2,500, or $5,000, and find your breakeven period.',
				'keyword'     => 'insurance deductible savings calculator',
				'category'    => 'Home Value & Insurance Coverage',
				'features'    => array(
					'Deductible step comparison ($500 to $5,000)',
					'Annual and monthly insurance premium discount',
					'Out-of-pocket breakeven horizon in years',
					'Optimal risk-reward deductible recommendation',
				),
			),
			'all-calculators' => array(
				'title'       => 'All Property & Mortgage Calculators Suite',
				'description' => 'Browse our complete directory of 10 free mortgage payoff, home affordability, real estate investing, HELOC, and insurance calculators in one complete hub.',
				'keyword'     => 'mortgage calculators real estate suite',
				'category'    => 'Calculators Hub',
				'features'    => array(
					'Access to all 10 specialized financial calculators',
					'Direct scenario simulations with zero paywalls',
					'Mobile-responsive and lightning-fast calculations',
				),
			),
		);

		if ( ! empty( $slug ) ) {
			return $tools[ $slug ] ?? array();
		}
		return $tools;
	}

	/**
	 * Predefined High-Authority FAQ Schema for Rich Snippets
	 */
	public static function get_tool_faqs( $slug = '' ) {
		$faqs = array(
			'home' => array(
				array(
					'q' => 'How do I use my current mortgage balance to calculate early payoff?',
					'a' => 'Locate the unpaid principal balance on your latest monthly mortgage statement and enter it into the Outstanding Mortgage Balance field. Select a target payoff timeframe or an extra monthly principal amount to see your accelerated completion date and interest savings.',
				),
				array(
					'q' => 'How does adding extra money to my monthly mortgage payment help?',
					'a' => 'Every extra dollar you allocate goes directly toward reducing your principal balance rather than interest. Lowering the principal creates a compounding reduction in subsequent interest charges, shortening a 30-year loan by years.',
				),
				array(
					'q' => 'Is it better to make irregular lump-sum payments or extra monthly payments?',
					'a' => 'Both strategies significantly reduce total interest. Monthly extra payments provide steady, predictable loan compression, while irregular payments (bonuses, tax refunds) immediately knock down principal balances at key financial milestones.',
				),
			),
			'home-affordability-daycare' => array(
				array(
					'q' => 'How do daycare costs affect how much mortgage I can get approved for?',
					'a' => 'While child care is not always a mandatory line-item on standard credit reports, lenders examine your overall monthly cash flow, recurring obligations, and bank statements. Daycare directly reduces discretionary net income available for housing expenses.',
				),
				array(
					'q' => 'What is the recommended debt-to-income (DTI) ratio when paying for childcare?',
					'a' => 'Most conventional mortgage guidelines set a maximum back-end DTI between 36% and 43%. Families paying high daycare tuition should aim for an effective total expense ratio below 33% to maintain adequate financial breathing room.',
				),
			),
			'rent-vs-buy' => array(
				array(
					'q' => 'What upfront closing costs are involved in buying a house?',
					'a' => 'Closing costs typically range from 3% to 6% of the purchase price and include lender origination fees, appraisal, title insurance, escrow reserves, and government recording taxes.',
				),
				array(
					'q' => 'What is the opportunity cost of a mortgage down payment?',
					'a' => 'When you invest cash into a home down payment, you forfeit potential returns that the capital could have earned in a diversified stock market index fund or high-yield savings account.',
				),
			),
			'seller-net-proceeds' => array(
				array(
					'q' => 'What expenses reduce a home seller\'s net proceeds?',
					'a' => 'The largest deductions are existing mortgage payoffs, real estate agent commissions (typically 5% to 6%), title and escrow fees, transfer taxes, and seller concessions or repair credits negotiated with the buyer.',
				),
			),
			'house-flipping-profit' => array(
				array(
					'q' => 'What is the 70% rule in house flipping?',
					'a' => 'The 70% rule states that an investor should pay no more than 70% of the After Repair Value (ARV) of a property minus estimated renovation and repair costs to preserve a safe profit margin.',
				),
			),
			'mortgage-recast' => array(
				array(
					'q' => 'What is a mortgage recast and how does it differ from refinancing?',
					'a' => 'A mortgage recast allows you to make a lump-sum principal payment while keeping your existing interest rate, term, and lender. The lender re-amortizes the remaining balance to lower your required monthly payment, typically for a small administrative fee without closing costs.',
				),
			),
			'heloc-payment' => array(
				array(
					'q' => 'What is payment shock when transitioning from HELOC draw to repayment?',
					'a' => 'During the draw period (usually 10 years), borrowers only pay interest. When the repayment period begins, monthly payments jump dramatically because borrowers must now pay both principal and interest to amortize the debt over the remaining term.',
				),
			),
			'home-replacement-cost' => array(
				array(
					'q' => 'How does replacement cost differ from market value?',
					'a' => 'Market value includes the price of land and neighborhood market demand. Replacement cost is strictly the physical expense of rebuilding the structure from the foundation up using contemporary labor and construction materials.',
				),
			),
			'insurance-deductible-savings' => array(
				array(
					'q' => 'How do I calculate the breakeven period for a higher homeowners insurance deductible?',
					'a' => 'Divide the increase in out-of-pocket risk (e.g. $1,000 to $2,500 = $1,500 difference) by your annual premium savings. If you save $300 per year, your breakeven period is 5 years ($1,500 / $300).',
				),
			),
		);

		return $faqs[ $slug ] ?? array();
	}
}

// Activation Hook
register_activation_hook( __FILE__, function() {
	// Set default options if not exists
	if ( ! get_option( 'equitypace_seo_options' ) ) {
		$defaults = array(
			'title_separator'     => '|',
			'home_title'          => 'Early Mortgage Payoff Calculator with Current Balance & Irregular Payments | EquityPace',
			'home_description'    => 'Calculate how fast you can pay off your home loan using current mortgage balance and irregular lump-sum extra payments. Free amortization & interest savings schedule.',
			'home_keyword'        => 'mortgage payoff calculator current balance',
			'default_og_image'    => get_template_directory_uri() . '/assets/images/mortgage-payoff-og.jpg',
			'twitter_handle'      => '@equitypace',
			'google_verification'=> '',
			'bing_verification'   => '',
			'google_analytics_id' => '',
			'enable_sitemap'      => 1,
		);
		update_option( 'equitypace_seo_options', $defaults );
	}

	// Register rewrite and flush
	$sitemap = new EquityPace_SEO_Sitemap();
	$sitemap->register_sitemap_rewrite();
	flush_rewrite_rules();
} );

// Deactivation Hook
register_deactivation_hook( __FILE__, function() {
	flush_rewrite_rules();
} );

// Instantiate Plugin
EquityPace_SEO::get_instance();
