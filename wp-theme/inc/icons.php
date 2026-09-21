<?php
/**
 * Minimal inline SVG icon set for the Value Prop Item block, echoed via
 * hg_icon( $name, $class ). Paths ported verbatim from the static
 * prototype's bin/generate.py (ICON_CHECK/ICON_GLOBE/ICON_SPARK).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hg_icon( $name, $class = 'h-5 w-5' ) {
	$icons = array(
		'check' => '<path d="M5 12.5 9.5 17 19 7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>',
		'globe' => '<circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6"/><path d="M4 12h16M12 4c2.2 2.2 3.4 5 3.4 8s-1.2 5.8-3.4 8c-2.2-2.2-3.4-5-3.4-8s1.2-5.8 3.4-8Z" stroke="currentColor" stroke-width="1.6"/>',
		'spark' => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
		'heart' => '<path d="M12 20.5s-7.5-4.6-9.7-9.4C.7 7.4 2.4 4 5.8 3.4c2-.3 3.9.6 5 2.2a5.6 5.6 0 0 1 5-2.2c3.4.6 5.1 4 3.5 7.7-2.2 4.8-9.7 9.4-9.7 9.4Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
		'shield' => '<path d="M12 3.5 5 6v6c0 4.5 3 7.7 7 8.5 4-.8 7-4 7-8.5V6l-7-2.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
		'facebook'  => '<path d="M14 8.5h2V5.5h-2c-2.2 0-3.5 1.4-3.5 3.5v2H8v3h2.5V21H13v-7h2.3l.4-3H13V9c0-.6.3-1 1-1Z" fill="currentColor"/>',
		'instagram' => '<rect x="4" y="4" width="16" height="16" rx="4" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="1.6"/><circle cx="16.2" cy="7.8" r="0.9" fill="currentColor"/>',
		'x'         => '<path d="M4 4l7.2 8.6L4.3 20h2.1l6-6.8L17 20h3l-7.5-9 6.6-7h-2.1l-5.5 6.2L7 4H4Z" fill="currentColor"/>',
		'whatsapp'  => '<path d="M12 3.5a8.5 8.5 0 0 0-7.4 12.7L3.5 20.5l4.4-1.2A8.5 8.5 0 1 0 12 3.5Z" stroke="currentColor" stroke-width="1.5"/><path d="M8.7 8.6c.2-.4.4-.4.6-.4h.5c.2 0 .4 0 .5.4.2.4.6 1.4.6 1.5.1.1.1.3 0 .4-.1.2-.2.3-.3.4-.1.1-.3.3-.4.4-.1.1-.3.3-.1.6.2.3.8 1.3 1.7 2 1.2 1 2.1 1.3 2.5 1.4.3.1.5.1.6-.1.2-.2.7-.8.9-1.1.2-.3.4-.2.6-.1.2.1 1.5.7 1.8.8.3.1.5.2.5.3 0 .2 0 1-.4 1.4-.4.4-1.2.8-2 .8-.7 0-1.6-.1-4-1.8-2.9-2.1-3.6-4.4-3.7-4.7-.1-.3-.6-1-.6-1.9 0-.9.5-1.4.7-1.6Z" fill="currentColor"/>',
		'link'      => '<path d="M9.5 14.5 14.5 9.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M11 7.5 12.3 6.2a3.2 3.2 0 0 1 4.5 4.5L15.5 12M13 16.5 11.7 17.8a3.2 3.2 0 0 1-4.5-4.5L8.5 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		$name = 'spark';
	}

	printf( '<svg viewBox="0 0 24 24" fill="none" class="%s">%s</svg>', esc_attr( $class ), $icons[ $name ] ); // phpcs:ignore -- static trusted SVG map, no user input.
}

/**
 * Icon slugs available in the Value Prop Item block's picker.
 */
function hg_icon_choices() {
	return array(
		'check'  => __( 'Check', 'healthgists' ),
		'globe'  => __( 'Globe', 'healthgists' ),
		'spark'  => __( 'Spark', 'healthgists' ),
		'heart'  => __( 'Heart', 'healthgists' ),
		'shield' => __( 'Shield', 'healthgists' ),
	);
}
