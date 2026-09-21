<?php
/**
 * Open Graph / Twitter Card meta tags — no SEO plugin required.
 *
 * WordPress core doesn't output any of these on its own, so link previews
 * (WhatsApp, Facebook, Twitter/X, Slack, etc.) would fall back to whatever
 * they can scrape, usually nothing useful. Same pattern as phf-ogun's
 * inc/seo.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [ url, width, height ] for an attachment at a given size — or the
 * theme's fallback share image (a real 1200x630 file) if no attachment ID
 * is given. Reports the size actually served, not an assumed 1200x630 —
 * a source photo narrower than 1200px can't be upscaled to that crop, so
 * hardcoding the meta dimensions would misreport the real served image.
 */
function hg_og_image( $attachment_id, $size = 'hg-og' ) {
	if ( $attachment_id ) {
		$src = wp_get_attachment_image_src( $attachment_id, $size );
		if ( $src ) {
			return array( $src[0], $src[1], $src[2] );
		}
	}
	return array( get_template_directory_uri() . '/assets/images/brand/og-image.png', 1200, 630 );
}

function hg_social_meta_tags() {
	$title           = wp_get_document_title();
	$description     = '';
	$image_id        = 0;
	$image_term_meta = null; // Categories store an attachment ID in term meta, not on a $post.
	$url             = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	$type            = 'website';

	if ( is_singular( 'post' ) ) {
		$post        = get_queried_object();
		$description = has_excerpt( $post ) ? wp_strip_all_tags( get_the_excerpt( $post ) ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 );
		$url         = get_permalink( $post );
		$type        = 'article';
		$image_id    = get_post_thumbnail_id( $post );
	} elseif ( is_singular() ) {
		$post        = get_queried_object();
		$description = has_excerpt( $post ) ? wp_strip_all_tags( get_the_excerpt( $post ) ) : '';
		$url         = get_permalink( $post );
		$image_id    = get_post_thumbnail_id( $post );
	} elseif ( is_category() ) {
		$term        = get_queried_object();
		$description = ( $term instanceof WP_Term && $term->description ) ? $term->description : '';
		$url         = get_category_link( $term );
		$image_term_meta = ( $term instanceof WP_Term ) ? get_term_meta( $term->term_id, 'hg_category_image', true ) : '';
	} elseif ( is_search() ) {
		/* translators: %s: search query */
		$title = sprintf( __( 'Search results for "%s"', 'healthgists' ), get_search_query() );
	}

	if ( ! $description ) {
		$description = 'Evidence-based health articles, wellness tips, and medical news for Nigeria — curated by professionals.';
	}

	list( $image, $image_width, $image_height ) = hg_og_image( $image_id ? $image_id : $image_term_meta );

	?>
	<meta property="og:type" content="<?php echo esc_attr( $type ); ?>" />
	<meta property="og:site_name" content="<?php echo esc_attr( hg_org_info( 'name' ) ); ?>" />
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
	<meta property="og:description" content="<?php echo esc_attr( $description ); ?>" />
	<meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
	<meta property="og:image" content="<?php echo esc_url( $image ); ?>" />
	<meta property="og:image:width" content="<?php echo (int) $image_width; ?>" />
	<meta property="og:image:height" content="<?php echo (int) $image_height; ?>" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
	<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>" />
	<meta name="twitter:image" content="<?php echo esc_url( $image ); ?>" />
	<?php
}
add_action( 'wp_head', 'hg_social_meta_tags', 1 );
