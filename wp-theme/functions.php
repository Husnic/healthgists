<?php
/**
 * Healthgists theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HG_THEME_VERSION', '1.0.0' );

/**
 * Theme setup: supports, menus, image sizes.
 */
function hg_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_post_type_support( 'page', 'excerpt' );
	add_editor_style( array(
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Commissioner:wght@400;500;600;700&display=swap',
		'assets/css/style.build.css',
	) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'healthgists' ),
		)
	);

	// Card crop (post_card/category_card use 16:10) and the wider single-post
	// banner / featured-card crop (4:3) from the static prototype.
	add_image_size( 'hg-card', 800, 500, true );
	add_image_size( 'hg-featured', 900, 675, true );
	add_image_size( 'hg-banner', 1600, 800, true );
	add_image_size( 'hg-og', 1200, 630, true );
}
add_action( 'after_setup_theme', 'hg_setup' );

/**
 * Styles & scripts.
 */
function hg_assets() {
	wp_enqueue_style( 'hg-style', get_stylesheet_uri(), array(), HG_THEME_VERSION );

	wp_enqueue_style(
		'hg-tailwind',
		get_template_directory_uri() . '/assets/css/style.build.css',
		array(),
		HG_THEME_VERSION
	);

	wp_enqueue_style(
		'hg-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Commissioner:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_script(
		'hg-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		HG_THEME_VERSION,
		true
	);

	// Newsletter endpoint — one place to change it, not buried in JS.
	wp_localize_script( 'hg-main', 'hgData', array(
		'newsletterUrl' => hg_org_info( 'newsletter_endpoint' ),
	) );

	if ( is_singular() && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hg_assets' );

/**
 * Excerpt tuning — matches the static prototype's card-length excerpts.
 */
function hg_excerpt_length( $length ) {
	return 28;
}
add_filter( 'excerpt_length', 'hg_excerpt_length' );

function hg_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'hg_excerpt_more' );

/**
 * Comments are disabled site-wide. Healthgists is a publication, not a
 * forum — readers reach the editorial desk via the Contact page or social,
 * matching the same "no open comments" call made on the phf-ogun theme this
 * one shares conventions with. Revisit if reader discussion becomes a real
 * product goal; it's a one-line reversal (remove this whole block).
 */
function hg_disable_comments_support() {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'page', 'comments' );
}
add_action( 'init', 'hg_disable_comments_support', 100 );

function hg_hide_existing_comments( $comments ) {
	return array();
}
add_filter( 'comments_array', 'hg_hide_existing_comments', 10, 2 );

function hg_close_comments_everywhere() {
	return false;
}
add_filter( 'comments_open', 'hg_close_comments_everywhere' );
add_filter( 'pings_open', 'hg_close_comments_everywhere' );

/**
 * Include taxonomy meta, template helpers, blocks, and small handlers.
 */
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/icons.php';
require get_template_directory() . '/inc/category-meta.php';
require get_template_directory() . '/inc/contact-handler.php';
require get_template_directory() . '/inc/blocks.php';
require get_template_directory() . '/inc/seo.php';
