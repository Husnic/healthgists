<?php
/**
 * Small reusable template helpers shared across theme templates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Site-wide contact/social/newsletter constants — edit here to update
 * everywhere at once, same pattern as phf-ogun's phf_org_info().
 */
function hg_org_info( $key = null ) {
	$info = array(
		'name'                => 'Healthgists',
		'tagline'             => 'Health News, Tips & Insights',
		'email'               => 'hello@healthgists.com',
		'newsletter_endpoint' => 'https://accurate-diagnosis-api.onrender.com/api/v1/newsletter',
		'socials'             => array(
			'facebook'  => 'https://web.facebook.com/profile.php?id=61589312589012',
			'instagram' => 'https://www.instagram.com/healthgistsnigeria/',
		),
	);

	if ( null === $key ) {
		return $info;
	}

	return isset( $info[ $key ] ) ? $info[ $key ] : '';
}

/**
 * "X min read" — word count over an average reading speed, same estimate
 * the static prototype's sample posts used (~200 wpm).
 */
function hg_read_time( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}
	$words   = str_word_count( wp_strip_all_tags( $post->post_content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	/* translators: %d: number of minutes */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'healthgists' ), $minutes );
}

/**
 * Breadcrumb trail + matching BreadcrumbList JSON-LD, ported from the
 * static prototype's generate.py breadcrumbs(). $trail is a list of
 * [label, url_or_null] pairs — null url = current page, shown as plain text.
 * Echoes both the visible <nav> and the <script type="application/ld+json">
 * block; call once near the top of the template.
 */
function hg_breadcrumbs( $trail ) {
	$crumbs   = array();
	$ld_items = array();

	foreach ( $trail as $i => $item ) {
		list( $label, $url ) = $item;
		$ld_item = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $label,
		);
		if ( $url ) {
			$ld_item['item'] = $url;
			$crumbs[]        = sprintf(
				'<a href="%s" class="text-ink/50 hover:text-green">%s</a>',
				esc_url( $url ),
				esc_html( $label )
			);
		} else {
			$crumbs[] = sprintf( '<span class="font-medium text-ink/80">%s</span>', esc_html( $label ) );
		}
		$ld_items[] = $ld_item;
	}

	$ld = array(
		'@context'        => 'https://schema.org',
		'@type'            => 'BreadcrumbList',
		'itemListElement' => $ld_items,
	);
	?>
	<script type="application/ld+json"><?php echo wp_json_encode( $ld ); // phpcs:ignore -- structured data, not user-facing HTML. ?></script>
	<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'healthgists' ); ?>" class="flex flex-wrap items-center gap-2 text-xs">
		<?php echo implode( '<span class="text-ink/30">/</span>', $crumbs ); // phpcs:ignore -- built from escaped pieces above. ?>
	</nav>
	<?php
}

/**
 * One post card — matches post_card() in the static prototype's
 * generate.py, including its "stretched link" pattern: the whole card is
 * clickable via a ::after pseudo-element on the title link, and the
 * category badge is a separate, real link raised above it with z-10.
 * Nesting <a> inside <a> is invalid HTML and silently breaks browser
 * layout — this avoids that entirely, same fix already made on the static
 * site after it shipped broken once.
 *
 * @param WP_Post $post
 * @param bool    $featured  Large 2-column layout (home's featured post).
 */
function hg_render_post_card( $post, $featured = false ) {
	$categories = get_the_category( $post->ID );
	$cat        = ! empty( $categories ) ? $categories[0] : null;
	$image      = get_the_post_thumbnail_url( $post->ID, $featured ? 'hg-featured' : 'hg-card' );
	$permalink  = get_permalink( $post );
	$excerpt    = get_the_excerpt( $post );
	$read_time  = hg_read_time( $post->ID );
	$author     = get_the_author_meta( 'display_name', $post->post_author );

	$wrap_class  = $featured
		? 'group relative grid grid-cols-1 overflow-hidden rounded-3xl border border-ink/10 bg-white transition-shadow hover:shadow-xl hover:shadow-ink/5 md:grid-cols-2'
		: 'group relative flex flex-col overflow-hidden rounded-2xl border border-ink/10 bg-white transition-shadow hover:shadow-xl hover:shadow-ink/5';
	$image_class = $featured ? 'aspect-[4/3] overflow-hidden' : 'aspect-[16/10] overflow-hidden';
	$body_class  = $featured ? 'flex flex-col justify-center p-8 md:p-10' : 'flex flex-1 flex-col p-6';
	$title_tag   = $featured ? 'h2' : 'h3';
	$title_class = $featured
		? 'mt-4 text-2xl font-bold leading-snug text-ink md:text-3xl group-hover:text-green'
		: 'mt-3 text-lg font-bold leading-snug text-ink group-hover:text-green';
	?>
	<div class="<?php echo esc_attr( $wrap_class ); ?>">
		<div class="<?php echo esc_attr( $image_class ); ?>">
			<?php if ( $image ) : ?>
				<img src="<?php echo esc_url( $image ); ?>" alt="" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
			<?php else : ?>
				<div class="h-full w-full bg-paper-warm"></div>
			<?php endif; ?>
		</div>
		<div class="<?php echo esc_attr( $body_class ); ?>">
			<?php if ( $cat ) : ?>
				<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" class="font-sans relative z-10 inline-block w-fit rounded-full bg-green/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-green hover:bg-green/20"><?php echo esc_html( $cat->name ); ?></a>
			<?php endif; ?>
			<<?php echo esc_html( $title_tag ); ?> class="<?php echo esc_attr( $title_class ); ?>">
				<a href="<?php echo esc_url( $permalink ); ?>" class="after:absolute after:inset-0"><?php echo esc_html( get_the_title( $post ) ); ?></a>
			</<?php echo esc_html( $title_tag ); ?>>
			<p class="<?php echo $featured ? 'mt-3 text-sm leading-relaxed text-ink/60' : 'mt-2 flex-1 text-sm leading-relaxed text-ink/60'; ?>"><?php echo esc_html( $excerpt ); ?></p>
			<div class="font-sans <?php echo $featured ? 'mt-5' : 'mt-4'; ?> flex items-center gap-3 text-xs font-medium text-ink/45">
				<span><?php echo esc_html( $author ); ?></span><span>&middot;</span><span><?php echo esc_html( $read_time ); ?></span>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Attachment ID a category term's cover image (hg_category_image term meta)
 * points to, resolved to a URL — see inc/category-meta.php for where that
 * meta is registered and edited.
 */
function hg_get_category_image_url( $term, $size = 'hg-card' ) {
	if ( ! $term ) {
		return '';
	}
	$term_id       = is_object( $term ) ? $term->term_id : $term;
	$attachment_id = get_term_meta( $term_id, 'hg_category_image', true );
	if ( ! $attachment_id ) {
		return '';
	}
	$url = wp_get_attachment_image_url( $attachment_id, $size );
	return $url ? $url : '';
}

/**
 * One category showcase card — matches category_card() in the static
 * prototype: full-bleed photo, gradient overlay, name + description.
 */
function hg_render_category_card( $term ) {
	if ( ! $term ) {
		return;
	}
	$image = hg_get_category_image_url( $term, 'hg-card' );
	?>
	<a href="<?php echo esc_url( get_category_link( $term ) ); ?>" class="group relative flex h-56 flex-col justify-end overflow-hidden rounded-3xl p-6 shadow-lg shadow-ink/5">
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
		<?php else : ?>
			<div class="absolute inset-0 bg-ink"></div>
		<?php endif; ?>
		<div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/10 to-transparent"></div>
		<div class="relative">
			<h3 class="text-lg font-bold text-white"><?php echo esc_html( $term->name ); ?></h3>
			<?php if ( $term->description ) : ?>
				<p class="mt-1 text-xs leading-relaxed text-white/70"><?php echo esc_html( $term->description ); ?></p>
			<?php endif; ?>
		</div>
	</a>
	<?php
}

/**
 * Photo banner hero for interior pages — see template-parts/page-hero.php.
 * Falls back to the current post's featured image if no 'image' arg given.
 */
function hg_page_hero( $args = array() ) {
	$defaults = array(
		'eyebrow'  => '',
		'title'    => get_the_title(),
		'subtitle' => '',
		'image'    => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'hg-banner' ) : '',
	);
	$args = wp_parse_args( $args, $defaults );
	get_template_part( 'template-parts/page-hero', null, $args );
}
