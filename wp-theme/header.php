<?php
/**
 * The header for the theme — matches header_nav() in the static
 * prototype's bin/generate.py, including the full-screen mobile popover
 * (not an inline dropdown) that assets/js/main.js drives.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Flat nav, matching the static prototype's NAV_LINKS exactly. Kept as a
// plain array rather than wp_nav_menu() — this is a 4-link publication nav,
// not a structure editors need to rearrange; register_nav_menus() still
// exposes a 'primary' location in functions.php if that's ever wanted later.
$hg_nav_links = array(
	array( 'label' => __( 'Home', 'healthgists' ), 'url' => home_url( '/' ) ),
	array( 'label' => __( 'Blog', 'healthgists' ), 'url' => get_permalink( get_option( 'page_for_posts' ) ) ),
	array( 'label' => __( 'About', 'healthgists' ), 'url' => home_url( '/about/' ) ),
	array( 'label' => __( 'Contact', 'healthgists' ), 'url' => home_url( '/contact/' ) ),
);
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand/icon-blue.svg' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-paper text-ink antialiased' ); ?>>
<?php wp_body_open(); ?>

<a class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-green focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white" href="#main">
	<?php esc_html_e( 'Skip to content', 'healthgists' ); ?>
</a>

<header class="sticky top-0 z-40 border-b border-ink/10 bg-paper/90 backdrop-blur">
	<div class="container-xw flex h-20 items-center justify-between">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand/logo-green.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-8 w-auto" />
		</a>
		<nav class="hidden items-center gap-8 md:flex" aria-label="<?php esc_attr_e( 'Primary', 'healthgists' ); ?>">
			<?php foreach ( $hg_nav_links as $item ) : ?>
				<a href="<?php echo esc_url( $item['url'] ); ?>" class="text-sm font-semibold text-ink/70 transition-colors hover:text-ink"><?php echo esc_html( $item['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<div class="hidden md:block">
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="inline-flex items-center justify-center rounded-full bg-green px-5 py-2.5 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5"><?php esc_html_e( 'Read the Blog', 'healthgists' ); ?></a>
		</div>
		<button data-nav-toggle aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'healthgists' ); ?>" class="flex h-10 w-10 items-center justify-center rounded-full border border-ink/15 md:hidden">
			<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
		</button>
	</div>

	<!-- Mobile nav: full-screen popover, not an inline dropdown. -->
	<div data-nav-menu class="fixed inset-0 z-50 hidden flex-col bg-paper md:hidden">
		<div class="container-xw flex h-20 items-center justify-between border-b border-ink/10">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand/logo-green.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-8 w-auto" />
			</a>
			<button data-nav-close aria-label="<?php esc_attr_e( 'Close menu', 'healthgists' ); ?>" class="flex h-10 w-10 items-center justify-center rounded-full border border-ink/15">
				<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
			</button>
		</div>
		<nav class="container-xw flex flex-1 flex-col justify-center gap-1" aria-label="<?php esc_attr_e( 'Mobile', 'healthgists' ); ?>">
			<?php foreach ( $hg_nav_links as $item ) : ?>
				<a href="<?php echo esc_url( $item['url'] ); ?>" data-nav-link class="border-b border-ink/10 py-4 text-2xl font-semibold text-ink/70"><?php echo esc_html( $item['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<div class="container-xw pb-10">
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="flex items-center justify-center rounded-full bg-green px-5 py-3.5 text-sm font-semibold text-white"><?php esc_html_e( 'Read the Blog', 'healthgists' ); ?></a>
		</div>
	</div>
</header>

<main id="main">
