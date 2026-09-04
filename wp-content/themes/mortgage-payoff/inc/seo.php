<?php
/**
 * SEO & Structured Data Management Module
 *
 * Provides complete On-Page SEO, Open Graph, Twitter Cards,
 * Schema.org JSON-LD (WebApplication, FAQPage, FinancialService, BreadcrumbList),
 * and WordPress Admin settings under Settings > SEO Management.
 *
 * @package Mortgage_Payoff
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Defer to dedicated EquityPace SEO Pro plugin if active
if ( defined( 'EQUITYPACE_SEO_ACTIVE' ) || class_exists( 'EquityPace_SEO' ) ) {
	return;
}

/**
 * Get SEO options with default values
 */
function mortgage_payoff_get_seo_options() {
	$defaults = array(
		'meta_title'          => 'Early Mortgage Payoff Calculator with Current Balance | EquityPace',
		'meta_description'    => 'Calculate how fast you can pay off your home loan using your current mortgage balance. See interest savings, amortization schedules, and new payoff dates for free.',
		'meta_keywords'       => 'mortgage payoff calculator using current balance, early mortgage payoff calculator, extra principal payment calculator, mortgage interest savings, loan amortization schedule',
		'canonical_url'       => home_url( '/' ),
		'og_image_url'        => get_template_directory_uri() . '/assets/images/mortgage-payoff-og.jpg',
		'google_verification' => '',
		'google_analytics_id' => '',
	);

	$saved = get_option( 'mortgage_payoff_seo_settings', array() );
	return wp_parse_args( $saved, $defaults );
}

/**
 * Render complete SEO Meta Tags in <head>
 */
function mortgage_payoff_seo_meta_tags() {
	$opts = mortgage_payoff_get_seo_options();

	$title       = esc_attr( $opts['meta_title'] );
	$description = esc_attr( $opts['meta_description'] );
	$keywords    = esc_attr( $opts['meta_keywords'] );
	$canonical   = esc_url( $opts['canonical_url'] );
	$og_image    = esc_url( $opts['og_image_url'] );
	$site_name   = esc_attr( get_bloginfo( 'name' ) );

	echo "\n<!-- ==================== Primary SEO & Metadata ==================== -->\n";
	echo '<title>' . esc_html( $title ) . '</title>' . "\n";
	echo '<meta name="description" content="' . $description . '" />' . "\n";
	echo '<meta name="keywords" content="' . $keywords . '" />' . "\n";
	echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />' . "\n";
	echo '<link rel="canonical" href="' . $canonical . '" />' . "\n";

	// Google Search Console Verification Tag
	if ( ! empty( $opts['google_verification'] ) ) {
		echo '<meta name="google-site-verification" content="' . esc_attr( $opts['google_verification'] ) . '" />' . "\n";
	}

	echo "\n<!-- ==================== Open Graph / Facebook / LinkedIn ==================== -->\n";
	echo '<meta property="og:locale" content="en_US" />' . "\n";
	echo '<meta property="og:type" content="website" />' . "\n";
	echo '<meta property="og:title" content="' . $title . '" />' . "\n";
	echo '<meta property="og:description" content="' . $description . '" />' . "\n";
	echo '<meta property="og:url" content="' . $canonical . '" />' . "\n";
	echo '<meta property="og:site_name" content="' . $site_name . '" />' . "\n";
	echo '<meta property="og:image" content="' . $og_image . '" />' . "\n";
	echo '<meta property="og:image:width" content="1200" />' . "\n";
	echo '<meta property="og:image:height" content="630" />' . "\n";

	echo "\n<!-- ==================== Twitter Card ==================== -->\n";
	echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	echo '<meta name="twitter:title" content="' . $title . '" />' . "\n";
	echo '<meta name="twitter:description" content="' . $description . '" />' . "\n";
	echo '<meta name="twitter:image" content="' . $og_image . '" />' . "\n";

	// Google Analytics 4 (GA4) Tracking
	if ( ! empty( $opts['google_analytics_id'] ) ) {
		$ga_id = esc_attr( $opts['google_analytics_id'] );
		echo "\n<!-- Google Analytics 4 -->\n";
		echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $ga_id . '"></script>' . "\n";
		echo "<script>\n";
		echo "  window.dataLayer = window.dataLayer || [];\n";
		echo "  function gtag(){dataLayer.push(arguments);}\n";
		echo "  gtag('js', new Date());\n";
		echo "  gtag('config', '" . $ga_id . "');\n";
		echo "</script>\n";
	}

	// Schema.org JSON-LD Structured Data
	mortgage_payoff_render_json_ld( $opts );
}
add_action( 'wp_head', 'mortgage_payoff_seo_meta_tags', 1 );

/**
 * Filter WordPress document title to match SEO meta title
 */
function mortgage_payoff_filter_title( $title ) {
	$opts = mortgage_payoff_get_seo_options();
	return $opts['meta_title'];
}
add_filter( 'pre_get_document_title', 'mortgage_payoff_filter_title', 20 );

/**
 * Generate Schema.org JSON-LD Structured Data
 */
function mortgage_payoff_render_json_ld( $opts ) {
	$site_url  = home_url( '/' );
	$theme_url = get_template_directory_uri();

	$schemas = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(

			// 1. WebApplication Schema (Financial Tool)
			array(
				'@type'               => 'WebApplication',
				'@id'                 => $site_url . '#calculator',
				'name'                => 'Early Mortgage Payoff Calculator with Current Balance',
				'url'                 => $site_url,
				'applicationCategory' => 'FinanceApplication',
				'operatingSystem'     => 'All Modern Web Browsers',
				'browserRequirements' => 'Requires JavaScript. Requires HTML5.',
				'description'         => 'Interactive mortgage early payoff calculator that uses your current loan balance to calculate interest savings, accelerated payoff dates, and amortization schedules.',
				'offers'              => array(
					'@type'         => 'Offer',
					'price'         => '0.00',
					'priceCurrency' => 'USD',
				),
				'featureList'         => array(
					'Current mortgage balance input support',
					'Target years to payoff acceleration calculation',
					'Additional monthly principal payment calculation',
					'Irregular and lump-sum extra payment modeling (annual bonuses, tax refunds, commissions)',
					'Interactive loan balance comparison curve',
					'Year-by-year and monthly amortization breakdown',
					'Lifetime interest savings calculation',
				),
				'screenshot'          => $opts['og_image_url'],
			),

			// 2. FinancialService / Organization Schema
			array(
				'@type'          => 'FinancialService',
				'@id'            => $site_url . '#organization',
				'name'           => 'EquityPace Mortgage Intelligence',
				'url'            => $site_url,
				'logo'           => $theme_url . '/assets/images/logo.svg',
				'description'    => 'EquityPace provides interactive mortgage payoff calculations, early amortization intelligence, and debt freedom strategies for homeowners.',
				'priceRange'     => '$$',
				'hasCredential'  => array(
					'NMLS #472433',
					'Member FDIC',
					'Equal Housing Lender',
				),
			),

			// 3. BreadcrumbList Schema
			array(
				'@type'           => 'BreadcrumbList',
				'@id'             => $site_url . '#breadcrumb',
				'itemListElement' => array(
					array(
						'@type'    => 'ListItem',
						'position' => 1,
						'name'     => 'Home',
						'item'     => $site_url,
					),
					array(
						'@type'    => 'ListItem',
						'position' => 2,
						'name'     => 'Calculators',
						'item'     => $site_url . '#calculator-section',
					),
					array(
						'@type'    => 'ListItem',
						'position' => 3,
						'name'     => 'Early Mortgage Payoff Calculator',
						'item'     => $site_url,
					),
				),
			),

			// 4. FAQPage Schema (Google Rich Results in SERPs)
			array(
				'@type'      => 'FAQPage',
				'@id'        => $site_url . '#faq',
				'mainEntity' => array(
					array(
						'@type'          => 'Question',
						'name'           => 'How do I use my current mortgage balance to calculate early payoff?',
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => 'To calculate early payoff using your current balance, locate the unpaid principal balance on your latest monthly mortgage statement. Enter this amount into the Outstanding Mortgage Balance field, along with your existing interest rate and original term. Then, select either a target number of payoff years or an extra monthly amount to see your exact interest savings and accelerated completion date.',
						),
					),
					array(
						'@type'          => 'Question',
						'name'           => 'How does adding extra money to my monthly mortgage payment help?',
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => 'Every extra dollar you allocate towards your monthly payment goes directly toward reducing your loan\'s principal balance rather than interest. Because mortgage interest is calculated on the remaining balance each month, lowering the principal faster creates a compounding interest reduction, allowing you to pay off a 30-year mortgage 5 to 12 years earlier and save tens of thousands of dollars.',
						),
					),
					array(
						'@type'          => 'Question',
						'name'           => 'Is it better to pay off my mortgage early or invest the extra cash?',
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => 'It depends on your current mortgage interest rate and risk tolerance. Paying off your mortgage provides a guaranteed, risk-free return equal to your mortgage interest rate (e.g., 6.71% interest saved is equivalent to a guaranteed 6.71% return). If your mortgage rate is high, early payoff is often the best mathematical choice. If your rate is locked below 3.5%, investing in broad market index funds may yield higher returns over the long term.',
						),
					),
					array(
						'@type'          => 'Question',
						'name'           => 'Are there prepayment penalties for paying off a mortgage early?',
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => 'Most modern conventional, FHA, and VA home loans in the United States do not have prepayment penalties. However, always review your loan disclosure documents or contact your loan servicer to confirm that extra payments are specified to be applied directly to the principal balance.',
						),
					),
					array(
						'@type'          => 'Question',
						'name'           => 'What is the difference between mortgage recasting and refinancing for early payoff?',
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => 'A mortgage recast allows you to make a large lump-sum principal payment while keeping your existing loan terms and interest rate; the lender then re-amortizes the remaining balance to lower your required monthly payment. Refinancing replaces your existing loan with a brand-new loan, which may have closing costs but allows you to switch from a 30-year term to a 15-year term with a lower interest rate.',
						),
					),
				),
			),

		),
	);

	echo "\n<!-- ==================== Schema.org JSON-LD Structured Data ==================== -->\n";
	echo '<script type="application/ld+json">' . "\n";
	echo wp_json_encode( $schemas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "\n";
	echo "</script>\n";
}

/**
 * Register SEO Settings in WordPress Admin under Settings > SEO Management
 */
function mortgage_payoff_register_seo_settings() {
	register_setting( 'mortgage_payoff_seo_group', 'mortgage_payoff_seo_settings', array(
		'sanitize_callback' => 'mortgage_payoff_sanitize_seo_settings',
	) );
}
add_action( 'admin_init', 'mortgage_payoff_register_seo_settings' );

function mortgage_payoff_sanitize_seo_settings( $input ) {
	$sanitized = array();
	$sanitized['meta_title']          = sanitize_text_field( $input['meta_title'] ?? '' );
	$sanitized['meta_description']    = sanitize_textarea_field( $input['meta_description'] ?? '' );
	$sanitized['meta_keywords']       = sanitize_text_field( $input['meta_keywords'] ?? '' );
	$sanitized['canonical_url']       = esc_url_raw( $input['canonical_url'] ?? home_url( '/' ) );
	$sanitized['og_image_url']        = esc_url_raw( $input['og_image_url'] ?? '' );
	$sanitized['google_verification'] = sanitize_text_field( $input['google_verification'] ?? '' );
	$sanitized['google_analytics_id'] = sanitize_text_field( $input['google_analytics_id'] ?? '' );
	return $sanitized;
}

/**
 * Add SEO Management Page to Admin Menu
 */
function mortgage_payoff_add_seo_admin_menu() {
	add_options_page(
		'Mortgage SEO Management',
		'SEO Management',
		'manage_options',
		'mortgage-payoff-seo',
		'mortgage_payoff_render_seo_admin_page'
	);
}
add_action( 'admin_menu', 'mortgage_payoff_add_seo_admin_menu' );

/**
 * Render WordPress Admin SEO Settings Form
 */
function mortgage_payoff_render_seo_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$opts = mortgage_payoff_get_seo_options();
	?>
	<div class="wrap" style="max-width: 900px;">
		<h1><span class="dashicons dashicons-chart-line" style="font-size: 32px; width: 32px; height: 32px; margin-right: 8px;"></span> Mortgage Website SEO Management</h1>
		<p class="description">
			Manage meta titles, search descriptions, social sharing cards, and tracking tags to optimize search engine ranking for your mortgage payoff calculator.
		</p>

		<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><strong>SEO Settings saved successfully!</strong></p>
			</div>
		<?php endif; ?>

		<form method="post" action="options.php" style="background: #ffffff; padding: 24px 32px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-top: 20px;">
			<?php settings_fields( 'mortgage_payoff_seo_group' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="meta_title"><strong>Search Engine Title Tag</strong></label></th>
					<td>
						<input name="mortgage_payoff_seo_settings[meta_title]" type="text" id="meta_title" value="<?php echo esc_attr( $opts['meta_title'] ); ?>" class="large-text" />
						<p class="description">Recommended: 50–60 characters. Target primary keyword: <em>"Mortgage Payoff Calculator with Current Balance"</em>.</p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="meta_description"><strong>Meta Description</strong></label></th>
					<td>
						<textarea name="mortgage_payoff_seo_settings[meta_description]" id="meta_description" rows="3" class="large-text"><?php echo esc_textarea( $opts['meta_description'] ); ?></textarea>
						<p class="description">Recommended: 140–160 characters. Provide an enticing summary that encourages clicks in Google search results.</p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="meta_keywords"><strong>Target Keywords</strong></label></th>
					<td>
						<input name="mortgage_payoff_seo_settings[meta_keywords]" type="text" id="meta_keywords" value="<?php echo esc_attr( $opts['meta_keywords'] ); ?>" class="large-text" />
						<p class="description">Comma-separated keywords for reference and indexation.</p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="canonical_url"><strong>Canonical URL</strong></label></th>
					<td>
						<input name="mortgage_payoff_seo_settings[canonical_url]" type="url" id="canonical_url" value="<?php echo esc_attr( $opts['canonical_url'] ); ?>" class="large-text" />
						<p class="description">Defines the primary URL for search engine indexation to avoid duplicate content penalties.</p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="og_image_url"><strong>Social Share Image (Open Graph / Twitter)</strong></label></th>
					<td>
						<input name="mortgage_payoff_seo_settings[og_image_url]" type="url" id="og_image_url" value="<?php echo esc_attr( $opts['og_image_url'] ); ?>" class="large-text" />
						<p class="description">Direct link to social preview image (1200x630 pixels recommended).</p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="google_verification"><strong>Google Search Console Verification</strong></label></th>
					<td>
						<input name="mortgage_payoff_seo_settings[google_verification]" type="text" id="google_verification" value="<?php echo esc_attr( $opts['google_verification'] ); ?>" class="regular-text" placeholder="e.g. google-site-verification-token" />
						<p class="description">Meta tag verification string from Google Search Console.</p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="google_analytics_id"><strong>Google Analytics 4 Measurement ID</strong></label></th>
					<td>
						<input name="mortgage_payoff_seo_settings[google_analytics_id]" type="text" id="google_analytics_id" value="<?php echo esc_attr( $opts['google_analytics_id'] ); ?>" class="regular-text" placeholder="G-XXXXXXXXXX" />
						<p class="description">GA4 Measurement ID to track traffic and visitor interactions.</p>
					</td>
				</tr>
			</table>

			<?php submit_button( 'Save SEO Settings' ); ?>
		</form>
	</div>
	<?php
}
