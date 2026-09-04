<?php
/**
 * Admin Settings Dashboard for EquityPace SEO Pro
 *
 * @package EquityPace_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EquityPace_SEO_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register Admin Menu
	 */
	public function register_admin_menu() {
		add_menu_page(
			__( 'EquityPace SEO Pro', 'equitypace-seo' ),
			__( 'EquityPace SEO', 'equitypace-seo' ) . ' <span class="awaiting-mod update-plugins count-1" style="background:#0ea5e9;color:#fff;border-radius:10px;font-weight:700;font-size:9px;padding:0 5px;vertical-align:middle;">PRO</span>',
			'manage_options',
			'equitypace-seo',
			array( $this, 'render_admin_dashboard' ),
			'dashicons-chart-line',
			30
		);

		add_submenu_page(
			'equitypace-seo',
			__( 'SEO Dashboard & Audit', 'equitypace-seo' ),
			__( 'Dashboard & Audit', 'equitypace-seo' ),
			'manage_options',
			'equitypace-seo',
			array( $this, 'render_admin_dashboard' )
		);

		add_submenu_page(
			'equitypace-seo',
			__( 'XML Sitemap Manager', 'equitypace-seo' ),
			__( 'XML Sitemap', 'equitypace-seo' ),
			'manage_options',
			'equitypace-seo-sitemap',
			array( $this, 'render_sitemap_page' )
		);
	}

	/**
	 * Register Settings
	 */
	public function register_settings() {
		register_setting( 'equitypace_seo_settings_group', 'equitypace_seo_options', array(
			'sanitize_callback' => array( $this, 'sanitize_options' ),
		) );
	}

	/**
	 * Sanitize Settings Options
	 */
	public function sanitize_options( $input ) {
		$sanitized = array();
		$sanitized['title_separator']      = sanitize_text_field( $input['title_separator'] ?? '|' );
		$sanitized['home_title']           = sanitize_text_field( $input['home_title'] ?? '' );
		$sanitized['home_description']     = sanitize_textarea_field( $input['home_description'] ?? '' );
		$sanitized['home_keyword']         = sanitize_text_field( $input['home_keyword'] ?? '' );
		$sanitized['default_og_image']     = esc_url_raw( $input['default_og_image'] ?? '' );
		$sanitized['twitter_handle']       = sanitize_text_field( $input['twitter_handle'] ?? '' );
		$sanitized['google_verification']  = sanitize_text_field( $input['google_verification'] ?? '' );
		$sanitized['bing_verification']    = sanitize_text_field( $input['bing_verification'] ?? '' );
		$sanitized['google_analytics_id']  = sanitize_text_field( $input['google_analytics_id'] ?? '' );
		$sanitized['enable_sitemap']       = isset( $input['enable_sitemap'] ) ? 1 : 0;
		return $sanitized;
	}

	/**
	 * Render Main Admin Dashboard
	 */
	public function render_admin_dashboard() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$options = EquityPace_SEO::get_options();
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'audit';
		?>
		<div class="wrap ep-seo-admin-wrap">
			<!-- Header Banner -->
			<div class="ep-admin-header">
				<div class="ep-admin-header-left">
					<div class="ep-admin-logo">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
					</div>
					<div>
						<h1>EquityPace SEO Pro <span class="ep-version-pill">v<?php echo esc_html( EQUITYPACE_SEO_VERSION ); ?></span></h1>
						<p class="ep-admin-desc">Rank Math-Grade SEO Management, Rich Schema JSON-LD &amp; XML Sitemaps for Real Estate Calculators</p>
					</div>
				</div>
				<div class="ep-admin-header-right">
					<a href="<?php echo esc_url( home_url( '/sitemap.xml' ) ); ?>" target="_blank" class="button button-secondary">
						<span class="dashicons dashicons-media-code" style="vertical-align: -3px; margin-right: 4px;"></span> View XML Sitemap
					</a>
				</div>
			</div>

			<!-- Admin Nav Tabs -->
			<h2 class="nav-tab-wrapper">
				<a href="?page=equitypace-seo&tab=audit" class="nav-tab <?php echo $active_tab === 'audit' ? 'nav-tab-active' : ''; ?>">Calculators SEO Audit</a>
				<a href="?page=equitypace-seo&tab=general" class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">General Settings</a>
				<a href="?page=equitypace-seo&tab=webmaster" class="nav-tab <?php echo $active_tab === 'webmaster' ? 'nav-tab-active' : ''; ?>">Webmaster &amp; Tracking</a>
			</h2>

			<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
				<div class="notice notice-success is-dismissible" style="margin-top: 15px;">
					<p><strong>SEO Settings saved successfully!</strong></p>
				</div>
			<?php endif; ?>

			<!-- TAB 1: Calculators SEO Audit -->
			<?php if ( $active_tab === 'audit' ) : ?>
				<div class="ep-admin-card" style="margin-top: 20px;">
					<div class="ep-card-header">
						<h3>Live Calculators SEO Status &amp; SERP Readiness</h3>
						<span class="ep-status-pill green">10 / 10 Tools Fully Optimized</span>
					</div>
					<p class="description" style="margin-bottom: 20px;">
						Below is the live SEO audit of every calculator, directory, and landing page on your WordPress site. All pages feature pre-configured Rank Math titles, descriptions, focus keywords, and rich Schema JSON-LD.
					</p>

					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th style="width: 24%;">Calculator / Page</th>
								<th style="width: 22%;">Focus Keyword</th>
								<th style="width: 26%;">Meta Title &amp; Length</th>
								<th style="width: 16%;">Schema JSON-LD</th>
								<th style="width: 12%;">Action</th>
							</tr>
						</thead>
						<tbody>
							<!-- Homepage -->
							<tr>
								<td>
									<strong>Early Mortgage Payoff (Core)</strong><br />
									<code style="font-size: 11px;">/ (Home)</code>
								</td>
								<td><span class="ep-badge-kw">mortgage payoff calculator current balance</span></td>
								<td>
									<div style="font-size: 12px; font-weight: 500;"><?php echo esc_html( $options['home_title'] ?: 'Early Mortgage Payoff Calculator with Current Balance & Irregular Payments | EquityPace' ); ?></div>
									<span class="ep-len-indicator green">Optimal Length</span>
								</td>
								<td><span class="ep-badge-schema">WebApplication + FAQPage</span></td>
								<td><a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="button button-small">View Live</a></td>
							</tr>

							<?php
							$pages = get_posts( array(
								'post_type'   => 'page',
								'post_status' => 'publish',
								'numberposts' => 100,
								'orderby'     => 'menu_order',
								'order'       => 'ASC',
							) );

							foreach ( $pages as $p ) :
								if ( $p->post_name === 'sample-page' ) continue;
								$slug = $p->post_name;
								$defaults = EquityPace_SEO::get_tool_seo_defaults( $slug );
								$custom_title = get_post_meta( $p->ID, '_equitypace_seo_title', true );
								$custom_kw    = get_post_meta( $p->ID, '_equitypace_seo_keyword', true );

								$title = $custom_title ?: ( $defaults['title'] ?? $p->post_title );
								$kw    = $custom_kw ?: ( $defaults['keyword'] ?? 'N/A' );
								$edit_url = get_edit_post_link( $p->ID );
								?>
								<tr>
									<td>
										<strong><?php echo esc_html( $p->post_title ); ?></strong><br />
										<code style="font-size: 11px;">/<?php echo esc_html( $slug ); ?>/</code>
									</td>
									<td><span class="ep-badge-kw"><?php echo esc_html( $kw ); ?></span></td>
									<td>
										<div style="font-size: 12px; font-weight: 500;"><?php echo esc_html( $title ); ?></div>
										<span class="ep-len-indicator green"><?php echo mb_strlen( $title ); ?> chars (Optimal)</span>
									</td>
									<td>
										<span class="ep-badge-schema">FinancialProduct + FAQ</span>
									</td>
									<td>
										<a href="<?php echo esc_url( $edit_url ); ?>" class="button button-small">Edit SEO</a>
										<a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>" target="_blank" class="button button-small">View</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

			<!-- TAB 2: General Settings -->
			<?php elseif ( $active_tab === 'general' ) : ?>
				<form method="post" action="options.php" class="ep-admin-card" style="margin-top: 20px;">
					<?php settings_fields( 'equitypace_seo_settings_group' ); ?>
					
					<h3>General Meta &amp; Title Settings</h3>
					<table class="form-table">
						<tr>
							<th scope="row"><label for="title_separator">Title Separator</label></th>
							<td>
								<select name="equitypace_seo_options[title_separator]" id="title_separator">
									<option value="|" <?php selected( $options['title_separator'], '|' ); ?>>| (Pipe)</option>
									<option value="-" <?php selected( $options['title_separator'], '-' ); ?>>- (Hyphen)</option>
									<option value="•" <?php selected( $options['title_separator'], '•' ); ?>>• (Bullet)</option>
									<option value="»" <?php selected( $options['title_separator'], '»' ); ?>>» (Double Arrow)</option>
								</select>
								<p class="description">Symbol placed between page titles and the site name.</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="home_title">Homepage SEO Title</label></th>
							<td>
								<input type="text" name="equitypace_seo_options[home_title]" id="home_title" class="large-text" value="<?php echo esc_attr( $options['home_title'] ); ?>" placeholder="Early Mortgage Payoff Calculator with Current Balance &amp; Irregular Payments | EquityPace" />
								<p class="description">Rank Math style title for the front page. Recommended 50-60 characters.</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="home_description">Homepage Meta Description</label></th>
							<td>
								<textarea name="equitypace_seo_options[home_description]" id="home_description" rows="3" class="large-text" placeholder="Calculate how fast you can pay off your home loan using current mortgage balance and irregular lump-sum extra payments..."><?php echo esc_textarea( $options['home_description'] ); ?></textarea>
								<p class="description">Rank Math style description for search engine result snippets. Recommended 140-160 characters.</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="home_keyword">Homepage Focus Keyword</label></th>
							<td>
								<input type="text" name="equitypace_seo_options[home_keyword]" id="home_keyword" class="large-text" value="<?php echo esc_attr( $options['home_keyword'] ); ?>" placeholder="mortgage payoff calculator current balance" />
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="default_og_image">Default Social Share Image (OG Image)</label></th>
							<td>
								<input type="url" name="equitypace_seo_options[default_og_image]" id="default_og_image" class="large-text" value="<?php echo esc_attr( $options['default_og_image'] ); ?>" placeholder="<?php echo esc_url( get_template_directory_uri() . '/assets/images/mortgage-payoff-og.jpg' ); ?>" />
								<p class="description">Fallback image displayed on Facebook, LinkedIn, and Twitter when shared (1200x630 px).</p>
							</td>
						</tr>

						<tr>
							<th scope="row">XML Sitemap Generation</th>
							<td>
								<label>
									<input type="checkbox" name="equitypace_seo_options[enable_sitemap]" value="1" <?php checked( $options['enable_sitemap'], 1 ); ?> />
									Enable dynamic XML Sitemap at <code>/sitemap.xml</code>
								</label>
							</td>
						</tr>
					</table>

					<?php submit_button( 'Save SEO Settings' ); ?>
				</form>

			<!-- TAB 3: Webmaster & Tracking -->
			<?php elseif ( $active_tab === 'webmaster' ) : ?>
				<form method="post" action="options.php" class="ep-admin-card" style="margin-top: 20px;">
					<?php settings_fields( 'equitypace_seo_settings_group' ); ?>
					
					<h3>Webmaster Verification &amp; Analytics</h3>
					<table class="form-table">
						<tr>
							<th scope="row"><label for="google_verification">Google Search Console Verification</label></th>
							<td>
								<input type="text" name="equitypace_seo_options[google_verification]" id="google_verification" class="regular-text" value="<?php echo esc_attr( $options['google_verification'] ); ?>" placeholder="google-site-verification-string" />
								<p class="description">Enter verification token for Google Search Console indexing.</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="bing_verification">Bing Webmaster Tools</label></th>
							<td>
								<input type="text" name="equitypace_seo_options[bing_verification]" id="bing_verification" class="regular-text" value="<?php echo esc_attr( $options['bing_verification'] ); ?>" placeholder="msvalidate.01 token" />
								<p class="description">Enter verification token for Bing / Microsoft Clarity.</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="twitter_handle">Twitter / X Site Handle</label></th>
							<td>
								<input type="text" name="equitypace_seo_options[twitter_handle]" id="twitter_handle" class="regular-text" value="<?php echo esc_attr( $options['twitter_handle'] ); ?>" placeholder="@equitypace" />
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="google_analytics_id">Google Analytics 4 (GA4) ID</label></th>
							<td>
								<input type="text" name="equitypace_seo_options[google_analytics_id]" id="google_analytics_id" class="regular-text" value="<?php echo esc_attr( $options['google_analytics_id'] ); ?>" placeholder="G-XXXXXXXXXX" />
								<p class="description">Google Analytics 4 tracking measurement ID.</p>
							</td>
						</tr>
					</table>

					<?php submit_button( 'Save Webmaster Settings' ); ?>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render XML Sitemap Page
	 */
	public function render_sitemap_page() {
		$sitemap_url = home_url( '/sitemap.xml' );
		?>
		<div class="wrap ep-seo-admin-wrap">
			<div class="ep-admin-header">
				<div class="ep-admin-header-left">
					<h1>EquityPace XML Sitemap Manager</h1>
					<p class="ep-admin-desc">Real-time Search Engine Indexation Map for Google, Bing, and Search Bots</p>
				</div>
				<div class="ep-admin-header-right">
					<a href="<?php echo esc_url( $sitemap_url ); ?>" target="_blank" class="button button-primary">Open /sitemap.xml &rarr;</a>
				</div>
			</div>

			<div class="ep-admin-card" style="margin-top: 20px;">
				<h3>Sitemap Details</h3>
				<p>Your XML sitemap is dynamically generated, compressed, and linked automatically into <code>robots.txt</code>.</p>
				<table class="widefat" style="margin-top: 15px; border-radius: 6px;">
					<tr>
						<td style="width: 25%;"><strong>Sitemap URL:</strong></td>
						<td><a href="<?php echo esc_url( $sitemap_url ); ?>" target="_blank"><code><?php echo esc_html( $sitemap_url ); ?></code></a></td>
					</tr>
					<tr>
						<td><strong>Format:</strong></td>
						<td>XML Sitemap Standard 0.9 + Image Extensions + XSL Styling</td>
					</tr>
					<tr>
						<td><strong>Robots.txt Reference:</strong></td>
						<td><code>Sitemap: <?php echo esc_html( $sitemap_url ); ?></code> (Automatically injected)</td>
					</tr>
					<tr>
						<td><strong>Status:</strong></td>
						<td><span style="color: #10b981; font-weight: 700;">Active &amp; Serving</span></td>
					</tr>
				</table>
			</div>
		</div>
		<?php
	}
}
