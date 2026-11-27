<?php
/**
 * PawGadgetHub theme functions.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PGH_VERSION', '1.4.0' );
define( 'PGH_DIR', get_template_directory() );
define( 'PGH_URI', get_template_directory_uri() );

require_once PGH_DIR . '/inc/template-tags.php';
require_once PGH_DIR . '/inc/customizer.php';
require_once PGH_DIR . '/inc/shortcodes.php';
require_once PGH_DIR . '/inc/content-filters.php';
require_once PGH_DIR . '/inc/meta-boxes.php';
require_once PGH_DIR . '/inc/patterns.php';

/**
 * Disable Rank Math sitemap caching so REST-published posts appear immediately.
 */
add_filter( 'rank_math/sitemap/enable_caching', '__return_false' );

/**
 * Block self-pingbacks and exclude pingbacks from comment counts.
 */
add_action( 'pre_ping', function ( &$links ) {
	$home = home_url();
	foreach ( $links as $l => $link ) {
		if ( 0 === strpos( $link, $home ) ) {
			unset( $links[ $l ] );
		}
	}
} );
add_filter( 'get_comments_number', function ( $count, $post_id ) {
	if ( ! is_admin() ) {
		$real = get_comments( array( 'post_id' => $post_id, 'type' => 'comment', 'status' => 'approve', 'count' => true ) );
		return (int) $real;
	}
	return $count;
}, 10, 2 );

/**
 * Theme setup.
 */
function pgh_setup() {
	load_theme_textdomain( 'pawgadgethub', PGH_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 56,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_editor_style( 'assets/css/editor.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu (header)', 'pawgadgethub' ),
			'footer'  => __( 'Footer menu (legal pages)', 'pawgadgethub' ),
		)
	);

	add_image_size( 'pgh-card', 720, 450, true );
	add_image_size( 'pgh-wide', 1200, 630, true );
}
add_action( 'after_setup_theme', 'pgh_setup' );

/**
 * Content width for embeds.
 */
function pgh_content_width() {
	$GLOBALS['content_width'] = 760;
}
add_action( 'after_setup_theme', 'pgh_content_width', 0 );

/**
 * Front-end assets. No jQuery, no external requests.
 */
function pgh_assets() {
	$css_ver = file_exists( PGH_DIR . '/assets/css/main.css' ) ? filemtime( PGH_DIR . '/assets/css/main.css' ) : PGH_VERSION;
	$js_ver  = file_exists( PGH_DIR . '/assets/js/main.js' ) ? filemtime( PGH_DIR . '/assets/js/main.js' ) : PGH_VERSION;

	wp_enqueue_style( 'pawgadgethub', PGH_URI . '/assets/css/main.css', array(), $css_ver );
	wp_enqueue_script(
		'pawgadgethub',
		PGH_URI . '/assets/js/main.js',
		array(),
		$js_ver,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'pgh_assets' );

/**
 * Head extras: font preloads, theme color, fallback favicons.
 */
function pgh_head_extras() {
	$fonts = array( 'plus-jakarta-sans-latin-var.woff2', 'inter-latin-var.woff2' );
	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( PGH_URI . '/assets/fonts/' . $font )
		);
	}
	echo '<meta name="theme-color" content="#12264A">' . "\n";

	// Only when no Site Icon has been set in Appearance > Customize.
	if ( ! has_site_icon() ) {
		printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( PGH_URI . '/assets/img/favicon.svg' ) );
		printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( PGH_URI . '/assets/img/apple-touch-icon.png' ) );
	}
}
add_action( 'wp_head', 'pgh_head_extras', 1 );

/**
 * Default social share image for Open Graph (used only if no SEO plugin is active).
 */
function pgh_og_fallback() {
	if ( defined( 'RANK_MATH_VERSION' ) || defined( 'WPSEO_VERSION' ) || defined( 'AIOSEO_VERSION' ) ) {
		return;
	}
	$image = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'pgh-wide' ) : PGH_URI . '/assets/img/social-share.png';
	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action( 'wp_head', 'pgh_og_fallback', 5 );

/**
 * Trim WordPress bloat that slows pages down.
 */
function pgh_cleanup() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', 'pgh_cleanup' );

/**
 * Excerpts.
 */
function pgh_excerpt_length() {
	return 26;
}
add_filter( 'excerpt_length', 'pgh_excerpt_length' );

function pgh_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'pgh_excerpt_more' );

/**
 * Cleaner archive titles ("Smart Feeders" instead of "Category: Smart Feeders").
 *
 * @param string $title Archive title.
 * @return string
 */
function pgh_archive_title( $title ) {
	if ( is_category() || is_tag() || is_tax() ) {
		return single_term_title( '', false );
	}
	if ( is_author() ) {
		return get_the_author();
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'pgh_archive_title' );

/**
 * Block styles registered for the editor.
 */
function pgh_block_styles() {
	register_block_style(
		'core/table',
		array(
			'name'  => 'pgh-compare',
			'label' => __( 'Comparison table', 'pawgadgethub' ),
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'pgh-callout',
			'label' => __( 'Callout box', 'pawgadgethub' ),
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'pgh-verdict',
			'label' => __( 'Quick verdict', 'pawgadgethub' ),
		)
	);
	register_block_style(
		'core/button',
		array(
			'name'  => 'pgh-buy',
			'label' => __( 'Buy button', 'pawgadgethub' ),
		)
	);
}
add_action( 'init', 'pgh_block_styles' );

/**
 * Body classes.
 */
function pgh_body_classes( $classes ) {
	if ( is_singular( 'post' ) ) {
		$classes[] = 'pgh-single-review';
	}
	return $classes;
}
add_filter( 'body_class', 'pgh_body_classes' );
