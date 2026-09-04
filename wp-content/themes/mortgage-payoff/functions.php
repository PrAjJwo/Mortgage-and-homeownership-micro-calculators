<?php
/**
 * Theme functions and definitions
 *
 * @package Mortgage_Payoff
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include SEO & Structured Data Management
require_once get_template_directory() . '/inc/seo.php';

// Include 2026 Financial Benchmarks Configuration
require_once get_template_directory() . '/inc/benchmarks.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function mortgage_payoff_setup() {
	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Switch default core markup for search form, comment form, etc., to HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Register Primary and Footer navigation menus.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'mortgage-payoff' ),
			'footer'  => esc_html__( 'Footer Menu', 'mortgage-payoff' ),
		)
	);
}
add_action( 'after_setup_theme', 'mortgage_payoff_setup' );

/**
 * Enqueue scripts and styles.
 */
function mortgage_payoff_scripts() {
	// Google Fonts: Plus Jakarta Sans & Inter
	wp_enqueue_style(
		'mortgage-payoff-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap',
		array(),
		null
	);

	// Main theme stylesheet
	wp_enqueue_style(
		'mortgage-payoff-style',
		get_stylesheet_uri(),
		array( 'mortgage-payoff-fonts' ),
		'2.1.0'
	);

	// Calculator specific stylesheet
	wp_enqueue_style(
		'mortgage-payoff-calc-css',
		get_template_directory_uri() . '/assets/css/calculator.css',
		array( 'mortgage-payoff-style' ),
		'2.1.0'
	);

	// Daycare Affordability stylesheet
	wp_enqueue_style(
		'daycare-calculator-css',
		get_template_directory_uri() . '/assets/css/daycare-calculator.css',
		array( 'mortgage-payoff-style' ),
		'2.1.0'
	);

	// Chart.js UMD bundle from jsdelivr CDN
	wp_enqueue_script(
		'chart-js',
		'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
		array(),
		'4.4.1',
		true
	);

	// Centralized 2026 Financial Benchmarks
	wp_enqueue_script(
		'equitypace-benchmarks',
		get_template_directory_uri() . '/assets/js/equitypace-benchmarks.js',
		array(),
		'2026.09.03',
		true
	);

	// Calculator math & interaction script
	wp_enqueue_script(
		'mortgage-payoff-calc-js',
		get_template_directory_uri() . '/assets/js/calculator.js',
		array( 'chart-js', 'equitypace-benchmarks' ),
		'2.3.0',
		true
	);

	// Daycare Affordability math & interaction script
	wp_enqueue_script(
		'daycare-calculator-js',
		get_template_directory_uri() . '/assets/js/daycare-calculator.js',
		array( 'chart-js', 'equitypace-benchmarks' ),
		'2.3.0',
		true
	);

	// Calculators Suite stylesheet
	wp_enqueue_style(
		'calculators-suite-css',
		get_template_directory_uri() . '/assets/css/calculators-suite.css',
		array( 'mortgage-payoff-style' ),
		'2.3.0'
	);

	// Calculators Suite interaction script
	wp_enqueue_script(
		'calculators-suite-js',
		get_template_directory_uri() . '/assets/js/calculators-suite.js',
		array( 'chart-js', 'equitypace-benchmarks' ),
		'2.3.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'mortgage_payoff_scripts' );
