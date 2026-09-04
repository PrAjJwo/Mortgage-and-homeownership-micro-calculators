<?php
/**
 * Frontend SEO Meta Tags, Social Graph, and Tracking
 *
 * @package EquityPace_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EquityPace_SEO_Meta {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Document title filter
		add_filter( 'pre_get_document_title', array( $this, 'filter_document_title' ), 99 );

		// Output meta tags in <head>
		add_action( 'wp_head', array( $this, 'render_head_meta' ), 1 );

		// Hook into robots meta
		add_filter( 'wp_robots', array( $this, 'filter_wp_robots' ) );
	}

	/**
	 * Get resolved SEO data for the current query/page
	 */
	public static function get_current_seo_data() {
		$options = EquityPace_SEO::get_options();
		$sep = $options['title_separator'] ?? '|';
		$site_name = get_bloginfo( 'name' );

		$data = array(
			'title'       => '',
			'description' => '',
			'keywords'    => '',
			'canonical'   => '',
			'og_image'    => $options['default_og_image'] ?? '',
			'noindex'     => false,
			'keyword'     => '',
			'page_type'   => 'website',
			'slug'        => '',
		);

		if ( is_front_page() || is_home() ) {
			$data['slug']        = 'home';
			$data['title']       = $options['home_title'] ?: "Early Mortgage Payoff Calculator with Current Balance & Irregular Payments {$sep} {$site_name}";
			$data['description'] = $options['home_description'] ?: "Calculate how fast you can pay off your home loan using current mortgage balance and irregular lump-sum extra payments. Free amortization & interest savings schedule.";
			$data['keyword']     = $options['home_keyword'] ?: "mortgage payoff calculator current balance";
			$data['keywords']    = "mortgage payoff calculator, current balance payoff, irregular mortgage payments, early loan amortization, mortgage interest savings";
			$data['canonical']   = home_url( '/' );
			return $data;
		}

		if ( is_page() ) {
			global $post;
			if ( $post ) {
				$post_id = $post->ID;
				$slug    = $post->post_name;
				$data['slug'] = $slug;

				// Check custom meta overrides
				$custom_title     = get_post_meta( $post_id, '_equitypace_seo_title', true );
				$custom_desc      = get_post_meta( $post_id, '_equitypace_seo_desc', true );
				$custom_keyword   = get_post_meta( $post_id, '_equitypace_seo_keyword', true );
				$custom_canonical = get_post_meta( $post_id, '_equitypace_seo_canonical', true );
				$custom_noindex   = get_post_meta( $post_id, '_equitypace_seo_noindex', true );

				// Check predefined tool defaults if not custom
				$tool_defaults = EquityPace_SEO::get_tool_seo_defaults( $slug );

				// Title
				if ( ! empty( $custom_title ) ) {
					$data['title'] = $custom_title;
				} elseif ( ! empty( $tool_defaults['title'] ) ) {
					$data['title'] = $tool_defaults['title'] . " {$sep} {$site_name}";
				} else {
					$data['title'] = get_the_title( $post_id ) . " {$sep} {$site_name}";
				}

				// Description
				if ( ! empty( $custom_desc ) ) {
					$data['description'] = $custom_desc;
				} elseif ( ! empty( $tool_defaults['description'] ) ) {
					$data['description'] = $tool_defaults['description'];
				} else {
					$excerpt = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : wp_strip_all_tags( $post->post_content );
					$data['description'] = wp_trim_words( $excerpt, 25, '...' );
				}

				// Keyword
				$data['keyword'] = ! empty( $custom_keyword ) ? $custom_keyword : ( $tool_defaults['keyword'] ?? '' );
				$data['keywords'] = $data['keyword'];

				// Canonical
				$data['canonical'] = ! empty( $custom_canonical ) ? $custom_canonical : get_permalink( $post_id );

				// Noindex
				$data['noindex'] = ( $custom_noindex === '1' || $custom_noindex === 'yes' );

				// OG Image
				if ( has_post_thumbnail( $post_id ) ) {
					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
					if ( $thumb ) {
						$data['og_image'] = $thumb[0];
					}
				}

				return $data;
			}
		}

		// Generic fallback
		$data['title']       = wp_get_document_title();
		$data['description'] = get_bloginfo( 'description' );
		$data['canonical']   = home_url( add_query_arg( null, null ) );

		return $data;
	}

	/**
	 * Filter document title tag
	 */
	public function filter_document_title( $title ) {
		$data = self::get_current_seo_data();
		if ( ! empty( $data['title'] ) ) {
			return $data['title'];
		}
		return $title;
	}

	/**
	 * Filter wp_robots meta
	 */
	public function filter_wp_robots( $robots ) {
		$data = self::get_current_seo_data();
		if ( $data['noindex'] ) {
			$robots['noindex'] = true;
			$robots['nofollow'] = true;
		} else {
			$robots['index'] = true;
			$robots['follow'] = true;
			$robots['max-snippet'] = -1;
			$robots['max-image-preview'] = 'large';
			$robots['max-video-preview'] = -1;
		}
		return $robots;
	}

	/**
	 * Render SEO meta tags into <head>
	 */
	public function render_head_meta() {
		$options = EquityPace_SEO::get_options();
		$data    = self::get_current_seo_data();

		$site_name = get_bloginfo( 'name' );
		$title     = esc_attr( $data['title'] );
		$desc      = esc_attr( $data['description'] );
		$canonical = esc_url( $data['canonical'] );
		$og_image  = ! empty( $data['og_image'] ) ? esc_url( $data['og_image'] ) : esc_url( get_template_directory_uri() . '/assets/images/mortgage-payoff-og.jpg' );

		echo "\n<!-- ==================== EquityPace SEO Pro (Rank Math Engine) ==================== -->\n";
		if ( ! empty( $desc ) ) {
			echo '<meta name="description" content="' . $desc . '" />' . "\n";
		}
		if ( ! empty( $data['keywords'] ) ) {
			echo '<meta name="keywords" content="' . esc_attr( $data['keywords'] ) . '" />' . "\n";
		}
		if ( ! empty( $data['keyword'] ) ) {
			echo '<meta name="news_keywords" content="' . esc_attr( $data['keyword'] ) . '" />' . "\n";
		}

		if ( $data['noindex'] ) {
			echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
		} else {
			echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />' . "\n";
		}

		if ( ! empty( $canonical ) ) {
			echo '<link rel="canonical" href="' . $canonical . '" />' . "\n";
		}

		// Webmaster verification
		if ( ! empty( $options['google_verification'] ) ) {
			echo '<meta name="google-site-verification" content="' . esc_attr( $options['google_verification'] ) . '" />' . "\n";
		}
		if ( ! empty( $options['bing_verification'] ) ) {
			echo '<meta name="msvalidate.01" content="' . esc_attr( $options['bing_verification'] ) . '" />' . "\n";
		}

		// Open Graph Tags
		echo "\n<!-- Open Graph / Facebook -->\n";
		echo '<meta property="og:locale" content="en_US" />' . "\n";
		echo '<meta property="og:type" content="' . esc_attr( $data['page_type'] ) . '" />' . "\n";
		echo '<meta property="og:title" content="' . $title . '" />' . "\n";
		echo '<meta property="og:description" content="' . $desc . '" />' . "\n";
		echo '<meta property="og:url" content="' . $canonical . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '" />' . "\n";
		echo '<meta property="og:image" content="' . $og_image . '" />' . "\n";
		echo '<meta property="og:image:width" content="1200" />' . "\n";
		echo '<meta property="og:image:height" content="630" />' . "\n";
		echo '<meta property="og:image:alt" content="' . $title . '" />' . "\n";

		// Twitter Card Tags
		echo "\n<!-- Twitter Cards -->\n";
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		echo '<meta name="twitter:title" content="' . $title . '" />' . "\n";
		echo '<meta name="twitter:description" content="' . $desc . '" />' . "\n";
		echo '<meta name="twitter:image" content="' . $og_image . '" />' . "\n";
		if ( ! empty( $options['twitter_handle'] ) ) {
			echo '<meta name="twitter:site" content="' . esc_attr( $options['twitter_handle'] ) . '" />' . "\n";
		}

		// GA4 / GTM Analytics
		if ( ! empty( $options['google_analytics_id'] ) ) {
			$ga_id = esc_attr( $options['google_analytics_id'] );
			echo "\n<!-- Google Analytics 4 (EquityPace SEO) -->\n";
			echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $ga_id . '"></script>' . "\n";
			echo "<script>\n";
			echo "  window.dataLayer = window.dataLayer || [];\n";
			echo "  function gtag(){dataLayer.push(arguments);}\n";
			echo "  gtag('js', new Date());\n";
			echo "  gtag('config', '" . $ga_id . "');\n";
			echo "</script>\n";
		}

		echo "<!-- ==================== /EquityPace SEO Pro ==================== -->\n\n";
	}
}
