<?php
/**
 * Schema.org JSON-LD Structured Data Generator
 *
 * @package EquityPace_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EquityPace_SEO_Schema {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'render_schema_json_ld' ), 20 );
	}

	/**
	 * Output JSON-LD Schema in <head>
	 */
	public function render_schema_json_ld() {
		$site_url  = home_url( '/' );
		$theme_url = get_template_directory_uri();
		$data      = EquityPace_SEO_Meta::get_current_seo_data();
		$slug      = $data['slug'];

		$graph = array();

		// 1. WebSite Schema
		$graph[] = array(
			'@type'         => 'WebSite',
			'@id'           => $site_url . '#website',
			'url'           => $site_url,
			'name'          => 'EquityPace',
			'description'   => 'Mortgage Payoff & Real Estate Intelligence Platform',
			'publisher'     => array(
				'@id' => $site_url . '#organization',
			),
			'inLanguage'    => 'en-US',
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => $site_url . '?s={search_term_string}',
				'query-input' => 'required name=search_term_string',
			),
		);

		// 2. Organization / FinancialService Schema
		$graph[] = array(
			'@type'         => 'FinancialService',
			'@id'           => $site_url . '#organization',
			'name'          => 'EquityPace Mortgage Intelligence',
			'url'           => $site_url,
			'logo'          => array(
				'@type'  => 'ImageObject',
				'@id'    => $site_url . '#logo',
				'url'    => $theme_url . '/assets/images/logo.svg',
				'caption'=> 'EquityPace',
			),
			'image'         => $data['og_image'] ?: ( $theme_url . '/assets/images/mortgage-payoff-og.jpg' ),
			'description'   => 'EquityPace provides interactive mortgage payoff calculations, early amortization intelligence, and debt freedom strategies for homeowners.',
			'priceRange'    => '$$',
			'hasCredential' => array(
				'NMLS #472433',
				'Member FDIC',
				'Equal Housing Lender',
			),
			'sameAs'        => array(
				'https://twitter.com/equitypace',
				'https://www.linkedin.com/company/equitypace',
			),
		);

		// 3. BreadcrumbList Schema
		$breadcrumbs = $this->build_breadcrumbs( $data );
		if ( ! empty( $breadcrumbs ) ) {
			$graph[] = $breadcrumbs;
		}

		// 4. WebApplication / SoftwareApplication Schema for Calculators
		$app_schema = $this->build_calculator_schema( $data );
		if ( ! empty( $app_schema ) ) {
			$graph[] = $app_schema;
		}

		// 5. FAQPage Schema
		$faq_schema = $this->build_faq_schema( $slug );
		if ( ! empty( $faq_schema ) ) {
			$graph[] = $faq_schema;
		}

		$output = array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		);

		echo "\n<!-- Schema.org JSON-LD (EquityPace SEO Pro) -->\n";
		echo '<script type="application/ld+json">' . "\n";
		echo wp_json_encode( $output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "\n";
		echo "</script>\n\n";
	}

	/**
	 * Build dynamic BreadcrumbList Schema
	 */
	private function build_breadcrumbs( $data ) {
		$site_url = home_url( '/' );
		$items    = array();

		// Home
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => 'Home',
			'item'     => $site_url,
		);

		if ( is_front_page() || is_home() ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => 'Current Balance & Extra Payments Payoff Calculator',
				'item'     => $site_url,
			);
		} elseif ( is_page() ) {
			$slug = $data['slug'];
			if ( $slug === 'all-calculators' ) {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => 2,
					'name'     => 'All Calculators Directory',
					'item'     => get_permalink(),
				);
			} else {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => 2,
					'name'     => 'Calculators',
					'item'     => home_url( '/all-calculators/' ),
				);
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => 3,
					'name'     => get_the_title(),
					'item'     => get_permalink(),
				);
			}
		}

		return array(
			'@type'           => 'BreadcrumbList',
			'@id'             => ( $data['canonical'] ?: $site_url ) . '#breadcrumb',
			'itemListElement' => $items,
		);
	}

	/**
	 * Build WebApplication / FinancialProduct Schema for Calculators
	 */
	private function build_calculator_schema( $data ) {
		$slug = $data['slug'];
		$tool = EquityPace_SEO::get_tool_seo_defaults( $slug );
		if ( empty( $tool ) ) {
			return null;
		}

		$canonical = $data['canonical'] ?: home_url( '/' );

		return array(
			'@type'               => array( 'WebApplication', 'FinancialProduct' ),
			'@id'                 => $canonical . '#app',
			'name'                => $tool['title'],
			'url'                 => $canonical,
			'applicationCategory' => 'FinanceApplication',
			'operatingSystem'     => 'All Modern Web Browsers (Chrome, Safari, Edge, Firefox)',
			'browserRequirements' => 'Requires JavaScript. Requires HTML5.',
			'description'         => $tool['description'],
			'offers'              => array(
				'@type'         => 'Offer',
				'price'         => '0.00',
				'priceCurrency' => 'USD',
			),
			'aggregateRating'     => array(
				'@type'       => 'AggregateRating',
				'ratingValue' => '4.9',
				'reviewCount' => '942',
				'bestRating'  => '5',
				'worstRating' => '1',
			),
			'featureList'         => $tool['features'] ?? array(
				'Interactive real-time calculation engine',
				'Visual chart and breakdown graphs',
				'Detailed mathematical amortization and expense schedules',
				'100% free with no registration or financial data storage required',
			),
			'screenshot'          => $data['og_image'],
		);
	}

	/**
	 * Build dynamic FAQPage Schema
	 */
	private function build_faq_schema( $slug ) {
		$faqs = EquityPace_SEO::get_tool_faqs( $slug );
		if ( empty( $faqs ) ) {
			return null;
		}

		$main_entity = array();
		foreach ( $faqs as $faq ) {
			$main_entity[] = array(
				'@type'          => 'Question',
				'name'           => $faq['q'],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $faq['a'],
				),
			);
		}

		return array(
			'@type'      => 'FAQPage',
			'@id'        => ( is_front_page() ? home_url( '/' ) : get_permalink() ) . '#faq',
			'mainEntity' => $main_entity,
		);
	}
}
