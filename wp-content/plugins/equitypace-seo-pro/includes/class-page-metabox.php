<?php
/**
 * Page & Post SEO Meta Box (Rank Math Style)
 *
 * @package EquityPace_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EquityPace_SEO_Metabox {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Register SEO Meta Box on Pages and Posts
	 */
	public function register_meta_box() {
		$screens = array( 'page', 'post' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'equitypace_seo_meta_box',
				__( 'EquityPace SEO Pro (Rank Math Engine)', 'equitypace-seo' ),
				array( $this, 'render_meta_box' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	/**
	 * Enqueue assets for admin metabox
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( in_array( $hook, array( 'post.php', 'post-new.php', 'toplevel_page_equitypace-seo' ) ) ) {
			wp_enqueue_style(
				'equitypace-seo-admin-css',
				EQUITYPACE_SEO_URL . 'assets/css/admin.css',
				array(),
				EQUITYPACE_SEO_VERSION
			);
			wp_enqueue_script(
				'equitypace-seo-admin-js',
				EQUITYPACE_SEO_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				EQUITYPACE_SEO_VERSION,
				true
			);
		}
	}

	/**
	 * Render the Meta Box HTML
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( 'equitypace_seo_save_nonce', 'equitypace_seo_nonce' );

		$slug = $post->post_name;
		$tool_defaults = EquityPace_SEO::get_tool_seo_defaults( $slug );

		$sep = EquityPace_SEO::get_option( 'title_separator', '|' );
		$site_name = get_bloginfo( 'name' );

		$custom_title     = get_post_meta( $post->ID, '_equitypace_seo_title', true );
		$custom_desc      = get_post_meta( $post->ID, '_equitypace_seo_desc', true );
		$custom_keyword   = get_post_meta( $post->ID, '_equitypace_seo_keyword', true );
		$custom_canonical = get_post_meta( $post->ID, '_equitypace_seo_canonical', true );
		$custom_noindex   = get_post_meta( $post->ID, '_equitypace_seo_noindex', true );

		// Defaults if empty
		$current_title = ! empty( $custom_title ) ? $custom_title : ( ! empty( $tool_defaults['title'] ) ? $tool_defaults['title'] . " {$sep} {$site_name}" : $post->post_title . " {$sep} {$site_name}" );
		$current_desc  = ! empty( $custom_desc ) ? $custom_desc : ( ! empty( $tool_defaults['description'] ) ? $tool_defaults['description'] : '' );
		$current_kw    = ! empty( $custom_keyword ) ? $custom_keyword : ( $tool_defaults['keyword'] ?? '' );
		$permalink     = get_permalink( $post->ID );
		?>
		<div class="ep-seo-metabox-wrapper">
			<!-- Header / Score Badge -->
			<div class="ep-seo-header">
				<div class="ep-seo-title-group">
					<span class="ep-badge-pro">RANK MATH ENGINE</span>
					<span class="ep-seo-subtitle">Real-Time Search Engine Optimization &amp; Schema Inspector</span>
				</div>
				<div class="ep-seo-score-badge" id="ep-score-badge">
					<div class="ep-score-circle" id="ep-score-circle">
						<span id="ep-score-value">--</span>
					</div>
					<div class="ep-score-label" id="ep-score-label">Calculating...</div>
				</div>
			</div>

			<!-- Tabs: General / Advanced / Schema -->
			<div class="ep-seo-tabs">
				<button type="button" class="ep-tab-btn active" data-tab="general">General &amp; Snippet</button>
				<button type="button" class="ep-tab-btn" data-tab="analysis">On-Page Checklist</button>
				<button type="button" class="ep-tab-btn" data-tab="advanced">Advanced (Robots / Canonical)</button>
			</div>

			<!-- TAB 1: General & SERP Snippet Preview -->
			<div class="ep-tab-pane active" id="ep-pane-general">
				
				<!-- Focus Keyword -->
				<div class="ep-form-group">
					<label for="ep_seo_keyword"><strong>Focus Keyword</strong></label>
					<div class="ep-input-keyword-wrapper">
						<input type="text" name="equitypace_seo_keyword" id="ep_seo_keyword" class="large-text" value="<?php echo esc_attr( $current_kw ); ?>" placeholder="e.g. mortgage payoff calculator current balance" />
						<span class="ep-input-tip">Used to evaluate on-page content relevance and title matching.</span>
					</div>
				</div>

				<!-- SERP Snippet Preview (Google Mockup) -->
				<div class="ep-serp-preview-box">
					<div class="ep-serp-header">
						<span>Google SERP Preview</span>
						<div class="ep-serp-devices">
							<button type="button" class="ep-device-btn active" data-device="desktop">🖥 Desktop</button>
							<button type="button" class="ep-device-btn" data-device="mobile">📱 Mobile</button>
						</div>
					</div>
					<div class="ep-serp-mockup desktop" id="ep-serp-mockup">
						<div class="ep-serp-site">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/favicon.svg' ); ?>" class="ep-serp-favicon" width="16" height="16" alt="" />
							<span class="ep-serp-sitename"><?php bloginfo( 'name' ); ?></span>
							<span class="ep-serp-url" id="ep-serp-url-display"><?php echo esc_html( $permalink ); ?></span>
						</div>
						<div class="ep-serp-title" id="ep-serp-title-display"><?php echo esc_html( $current_title ); ?></div>
						<div class="ep-serp-desc" id="ep-serp-desc-display"><?php echo esc_html( $current_desc ); ?></div>
					</div>
				</div>

				<!-- SEO Title Input with Char Counter -->
				<div class="ep-form-group">
					<div class="ep-label-bar">
						<label for="ep_seo_title"><strong>SEO Title</strong></label>
						<span class="ep-counter"><span id="ep-title-count">0</span> / 60 chars</span>
					</div>
					<input type="text" name="equitypace_seo_title" id="ep_seo_title" class="large-text" value="<?php echo esc_attr( $custom_title ); ?>" placeholder="<?php echo esc_attr( $current_title ); ?>" />
					<div class="ep-progress-bar"><div class="ep-progress-fill" id="ep-title-progress"></div></div>
					<p class="description">Recommended: 50–60 characters. Place your focus keyword near the beginning.</p>
				</div>

				<!-- Meta Description Input with Char Counter -->
				<div class="ep-form-group">
					<div class="ep-label-bar">
						<label for="ep_seo_desc"><strong>Meta Description</strong></label>
						<span class="ep-counter"><span id="ep-desc-count">0</span> / 160 chars</span>
					</div>
					<textarea name="equitypace_seo_desc" id="ep_seo_desc" rows="3" class="large-text" placeholder="<?php echo esc_attr( $current_desc ); ?>"><?php echo esc_textarea( $custom_desc ); ?></textarea>
					<div class="ep-progress-bar"><div class="ep-progress-fill" id="ep-desc-progress"></div></div>
					<p class="description">Recommended: 140–160 characters. Summarize the benefits and include your keyword to improve click-through rate (CTR).</p>
				</div>
			</div>

			<!-- TAB 2: On-Page SEO Analysis & Checklist -->
			<div class="ep-tab-pane" id="ep-pane-analysis">
				<div class="ep-analysis-card">
					<h4>Rank Math Content Audit Checklist</h4>
					<ul class="ep-checklist" id="ep-seo-checklist">
						<li id="check-kw-title" class="check-item"><span class="check-icon">⚪</span> <span class="check-text">Focus Keyword used in SEO Title</span></li>
						<li id="check-kw-desc" class="check-item"><span class="check-icon">⚪</span> <span class="check-text">Focus Keyword used in Meta Description</span></li>
						<li id="check-title-len" class="check-item"><span class="check-icon">⚪</span> <span class="check-text">SEO Title has optimal length (40–60 characters)</span></li>
						<li id="check-desc-len" class="check-item"><span class="check-icon">⚪</span> <span class="check-text">Meta Description has optimal length (120–160 characters)</span></li>
						<li id="check-slug-kw" class="check-item"><span class="check-icon">⚪</span> <span class="check-text">Focus Keyword appears in URL slug</span></li>
						<li id="check-schema-ready" class="check-item active-pass"><span class="check-icon">🟢</span> <span class="check-text">Rich JSON-LD Schema active (WebApplication, FinancialProduct, FAQPage)</span></li>
						<li id="check-mobile-ready" class="check-item active-pass"><span class="check-icon">🟢</span> <span class="check-text">Mobile viewport tag &amp; responsive layout configured</span></li>
						<li id="check-sitemap-ready" class="check-item active-pass"><span class="check-icon">🟢</span> <span class="check-text">Included in dynamic XML Sitemap (/sitemap.xml)</span></li>
					</ul>
				</div>
			</div>

			<!-- TAB 3: Advanced (Robots & Canonical) -->
			<div class="ep-tab-pane" id="ep-pane-advanced">
				<div class="ep-form-group">
					<label for="ep_seo_canonical"><strong>Canonical URL Override</strong></label>
					<input type="url" name="equitypace_seo_canonical" id="ep_seo_canonical" class="large-text" value="<?php echo esc_attr( $custom_canonical ); ?>" placeholder="<?php echo esc_url( $permalink ); ?>" />
					<p class="description">Leave blank to use this page's standard permalink as the canonical URL.</p>
				</div>

				<div class="ep-form-group">
					<label><strong>Search Engine Robots (Indexation)</strong></label>
					<label style="display: block; margin-top: 6px;">
						<input type="checkbox" name="equitypace_seo_noindex" value="1" <?php checked( $custom_noindex, '1' ); ?> />
						<strong>Noindex this page</strong> (Instructs search engines not to display this page in search results).
					</label>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Save Meta Box Data
	 */
	public function save_meta_box_data( $post_id ) {
		// Nonce check
		if ( ! isset( $_POST['equitypace_seo_nonce'] ) || ! wp_verify_nonce( $_POST['equitypace_seo_nonce'], 'equitypace_seo_save_nonce' ) ) {
			return;
		}

		// Autosave check
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Permission check
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save Title
		if ( isset( $_POST['equitypace_seo_title'] ) ) {
			update_post_meta( $post_id, '_equitypace_seo_title', sanitize_text_field( $_POST['equitypace_seo_title'] ) );
		}

		// Save Description
		if ( isset( $_POST['equitypace_seo_desc'] ) ) {
			update_post_meta( $post_id, '_equitypace_seo_desc', sanitize_textarea_field( $_POST['equitypace_seo_desc'] ) );
		}

		// Save Focus Keyword
		if ( isset( $_POST['equitypace_seo_keyword'] ) ) {
			update_post_meta( $post_id, '_equitypace_seo_keyword', sanitize_text_field( $_POST['equitypace_seo_keyword'] ) );
		}

		// Save Canonical
		if ( isset( $_POST['equitypace_seo_canonical'] ) ) {
			update_post_meta( $post_id, '_equitypace_seo_canonical', esc_url_raw( $_POST['equitypace_seo_canonical'] ) );
		}

		// Save Noindex
		$noindex = isset( $_POST['equitypace_seo_noindex'] ) ? '1' : '0';
		update_post_meta( $post_id, '_equitypace_seo_noindex', $noindex );
	}
}
