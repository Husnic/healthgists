<?php
/**
 * Registers the theme's custom blocks. Each is a dynamic block (rendered
 * server-side here) so the frontend markup exactly matches the static
 * prototype's design — the editor UI is just for entering content (text,
 * images, links, which post/category to pull from), not for controlling
 * the visuals.
 *
 * Build the editor JS with `npm run build:blocks` (or `npm run start:blocks`
 * while developing) — see package.json. Compiled output lives in /build.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A custom block category so ours are easy to find in the inserter.
 */
function hg_block_categories( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'healthgists-blocks',
				'title' => __( 'Healthgists', 'healthgists' ),
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'hg_block_categories' );

/**
 * Makes the icon list (from inc/icons.php) available to block editor JS as
 * `window.hgBlockData` — single source of truth, no duplicating the list
 * in JavaScript. Same technique as phf-ogun's phfBlockData.
 */
function hg_blocks_editor_assets() {
	$data = array(
		'icons' => hg_icon_choices(),
	);
	foreach ( array( 'healthgists-value-prop-item-editor-script', 'healthgists-hero-slide-editor-script' ) as $handle ) {
		if ( wp_script_is( $handle, 'registered' ) ) {
			wp_add_inline_script( $handle, 'window.hgBlockData = ' . wp_json_encode( $data ) . ';', 'before' );
			break;
		}
	}
}
add_action( 'enqueue_block_editor_assets', 'hg_blocks_editor_assets' );

/**
 * Registers every custom block. Each folder under /build/blocks has its own
 * block.json (copied there from /src at build time by @wordpress/scripts).
 */
function hg_register_blocks() {
	$build_dir = get_template_directory() . '/build/blocks';

	if ( ! is_dir( $build_dir ) ) {
		return;
	}

	register_block_type( $build_dir . '/hero-slider', array(
		'render_callback' => 'hg_render_hero_slider_block',
	) );

	register_block_type( $build_dir . '/hero-slide', array(
		'render_callback' => 'hg_render_hero_slide_block',
	) );

	register_block_type( $build_dir . '/category-showcase', array(
		'render_callback' => 'hg_render_category_showcase_block',
	) );

	register_block_type( $build_dir . '/category-card', array(
		'render_callback' => 'hg_render_category_card_block',
	) );

	register_block_type( $build_dir . '/value-prop-grid', array(
		'render_callback' => 'hg_render_value_prop_grid_block',
	) );

	register_block_type( $build_dir . '/value-prop-item', array(
		'render_callback' => 'hg_render_value_prop_item_block',
	) );

	register_block_type( $build_dir . '/process-steps', array(
		'render_callback' => 'hg_render_process_steps_block',
	) );

	register_block_type( $build_dir . '/process-step', array(
		'render_callback' => 'hg_render_process_step_block',
	) );
}
add_action( 'init', 'hg_register_blocks' );

/* ------------------------------------------------------------------ *
 * Hero Slider / Hero Slide
 * ------------------------------------------------------------------ */

/**
 * One hero slide, in one of two modes — ported from generate.py's
 * hero_brand_slide() (manual mode) and hero_post_slide() (linked mode).
 * Which heading level a slide uses is decided by MODE, not by its position
 * in the deck (manual = h1, linked = h2) — this matches the static
 * prototype's actual behavior exactly: hero_brand_slide() always emitted
 * h1 and hero_post_slide() always emitted h2, regardless of slide order.
 */
function hg_render_hero_slide_block( $attributes ) {
	$linked_id = ! empty( $attributes['linkedPostId'] ) ? (int) $attributes['linkedPostId'] : 0;

	if ( $linked_id && 'publish' === get_post_status( $linked_id ) ) {
		$post       = get_post( $linked_id );
		$categories = get_the_category( $linked_id );
		$cat        = ! empty( $categories ) ? $categories[0] : null;
		$image      = get_the_post_thumbnail_url( $linked_id, 'hg-banner' );
		$excerpt    = get_the_excerpt( $post );

		ob_start();
		?>
		<div data-hero-slide class="absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-1000 ease-in-out">
			<?php if ( $image ) : ?>
				<img src="<?php echo esc_url( $image ); ?>" alt="" class="h-full w-full object-cover" />
			<?php else : ?>
				<div class="h-full w-full bg-ink"></div>
			<?php endif; ?>
			<div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/55 to-ink/20"></div>
			<div class="container-xw absolute inset-0 flex flex-col justify-end pb-20 md:pb-28">
				<div class="max-w-xl">
					<?php if ( $cat ) : ?>
						<span class="font-sans inline-flex w-fit items-center gap-2 rounded-full bg-green/25 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white"><?php echo esc_html( $cat->name ); ?></span>
					<?php endif; ?>
					<h2 class="mt-5 text-3xl font-extrabold leading-tight text-white md:text-5xl"><?php echo esc_html( get_the_title( $post ) ); ?></h2>
					<?php if ( $excerpt ) : ?>
						<p class="mt-5 max-w-lg text-base leading-relaxed text-white/75"><?php echo esc_html( $excerpt ); ?></p>
					<?php endif; ?>
					<div class="mt-8 flex flex-wrap gap-3">
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="font-sans inline-flex items-center justify-center rounded-full bg-green px-6 py-3 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5"><?php esc_html_e( 'Read Article', 'healthgists' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	// Manual / brand slide.
	$eyebrow  = isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '';
	$title    = isset( $attributes['title'] ) ? $attributes['title'] : '';
	$subtitle = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
	$image    = isset( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : '';

	ob_start();
	?>
	<div data-hero-slide class="absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-1000 ease-in-out">
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" class="h-full w-full object-cover" />
		<?php else : ?>
			<div class="h-full w-full bg-ink"></div>
		<?php endif; ?>
		<div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/55 to-ink/20"></div>
		<div class="container-xw absolute inset-0 flex flex-col justify-end pb-20 md:pb-28">
			<div class="max-w-xl">
				<?php if ( $eyebrow ) : ?>
					<span class="font-sans inline-flex w-fit items-center gap-2 rounded-full bg-gold/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gold"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<?php if ( $title ) : ?>
					<h1 class="mt-5 text-4xl font-extrabold leading-tight text-white md:text-5xl"><?php echo wp_kses_post( $title ); ?></h1>
				<?php endif; ?>
				<?php if ( $subtitle ) : ?>
					<p class="mt-5 max-w-lg text-base leading-relaxed text-white/75"><?php echo wp_kses_post( $subtitle ); ?></p>
				<?php endif; ?>
				<div class="mt-8 flex flex-wrap gap-3">
					<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="font-sans inline-flex items-center justify-center rounded-full bg-green px-6 py-3 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5"><?php esc_html_e( 'Start Reading', 'healthgists' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="font-sans inline-flex items-center justify-center rounded-full border border-white/30 px-6 py-3 text-sm font-semibold text-white transition-all hover:border-white/70"><?php esc_html_e( 'About Healthgists', 'healthgists' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Wraps the whole slide deck: the crossfade section, dots nav — matching
 * build_index()'s hero <section> in the static prototype exactly, including
 * the data-hero-slides/data-hero-dot hooks assets/js/main.js depends on.
 */
function hg_render_hero_slider_block( $attributes, $content, $block ) {
	$slide_count = isset( $block->parsed_block['innerBlocks'] ) ? count( $block->parsed_block['innerBlocks'] ) : 1;

	ob_start();
	?>
	<section class="bleed relative min-h-[85vh] overflow-hidden bg-ink md:min-h-[90vh]">
		<div data-hero-slides class="absolute inset-0">
			<?php echo $content; // phpcs:ignore -- rendered hero-slide children. ?>
		</div>
		<?php if ( $slide_count > 1 ) : ?>
			<div class="absolute bottom-6 left-1/2 z-10 flex -translate-x-1/2 gap-2 md:bottom-8">
				<?php for ( $i = 0; $i < $slide_count; $i++ ) : ?>
					<button type="button" data-hero-dot aria-label="<?php echo esc_attr( sprintf( __( 'Show slide %d', 'healthgists' ), $i + 1 ) ); ?>" class="h-1.5 rounded-full transition-all duration-300 <?php echo 0 === $i ? 'w-6 bg-gold' : 'w-1.5 bg-white/40'; ?>"></button>
				<?php endfor; ?>
			</div>
		<?php endif; ?>
	</section>
	<?php
	return ob_get_clean();
}

/* ------------------------------------------------------------------ *
 * Category Showcase / Category Card
 * ------------------------------------------------------------------ */

function hg_render_category_showcase_block( $attributes, $content ) {
	$columns = isset( $attributes['columns'] ) ? (int) $attributes['columns'] : 4;
	$col_map = array(
		2 => 'sm:grid-cols-2',
		3 => 'sm:grid-cols-2 lg:grid-cols-3',
		4 => 'sm:grid-cols-2 lg:grid-cols-4',
	);
	$col_class = isset( $col_map[ $columns ] ) ? $col_map[ $columns ] : $col_map[4];

	$eyebrow   = isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '';
	$heading   = isset( $attributes['heading'] ) ? $attributes['heading'] : '';
	$variant   = isset( $attributes['variant'] ) ? $attributes['variant'] : 'light';
	$link_text = isset( $attributes['linkText'] ) ? $attributes['linkText'] : '';
	$link_url  = isset( $attributes['linkUrl'] ) ? $attributes['linkUrl'] : '';
	$has_head  = $eyebrow || $heading || $link_text;
	$is_dark   = 'dark' === $variant;

	// Literal class strings per variant (not interpolation) so Tailwind's
	// static scanner can find them — see the value-prop tint comment below.
	$section_bg    = $is_dark ? 'bg-ink' : 'bg-paper-warm';
	$eyebrow_class = $is_dark ? 'text-white/50' : 'text-green';
	$heading_class = $is_dark ? 'text-white' : 'text-ink';
	$link_class    = $is_dark ? 'text-white/80 hover:text-white' : 'text-green hover:underline';

	ob_start();
	?>
	<section class="bleed <?php echo esc_attr( $section_bg ); ?> py-16 md:py-20">
		<div class="container-xw">
			<?php if ( $has_head ) : ?>
				<div class="flex flex-wrap items-end justify-between gap-4">
					<?php if ( $eyebrow || $heading ) : ?>
						<div class="max-w-lg">
							<?php if ( $eyebrow ) : ?>
								<p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] <?php echo esc_attr( $eyebrow_class ); ?>"><?php echo esc_html( $eyebrow ); ?></p>
							<?php endif; ?>
							<?php if ( $heading ) : ?>
								<h2 class="mt-2 text-2xl font-bold <?php echo esc_attr( $heading_class ); ?> md:text-3xl"><?php echo esc_html( $heading ); ?></h2>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $link_text && $link_url ) : ?>
						<a href="<?php echo esc_url( $link_url ); ?>" class="font-sans text-sm font-semibold <?php echo esc_attr( $link_class ); ?>"><?php echo esc_html( $link_text ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="<?php echo $has_head ? 'mt-8' : ''; ?> grid grid-cols-1 gap-5 <?php echo esc_attr( $col_class ); ?>">
				<?php echo $content; // phpcs:ignore -- already-rendered child blocks. ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function hg_render_category_card_block( $attributes ) {
	$term_id = isset( $attributes['termId'] ) ? (int) $attributes['termId'] : 0;
	if ( ! $term_id ) {
		return '';
	}
	$term = get_term( $term_id, 'category' );
	if ( ! $term || is_wp_error( $term ) ) {
		return '';
	}
	ob_start();
	hg_render_category_card( $term );
	return ob_get_clean();
}

/* ------------------------------------------------------------------ *
 * Value Prop Grid / Value Prop Item
 * ------------------------------------------------------------------ */

function hg_render_value_prop_grid_block( $attributes, $content ) {
	$eyebrow = isset( $attributes['eyebrow'] ) ? $attributes['eyebrow'] : '';
	$heading = isset( $attributes['heading'] ) ? $attributes['heading'] : '';
	$variant = isset( $attributes['variant'] ) ? $attributes['variant'] : 'light';
	$is_dark = 'dark' === $variant;

	$section_bg    = $is_dark ? 'bleed bg-ink' : '';
	$eyebrow_class = $is_dark ? 'text-white/50' : 'text-green';
	$heading_class = $is_dark ? 'text-white' : 'text-ink';

	ob_start();
	?>
	<section class="<?php echo esc_attr( $section_bg ); ?> py-16 md:py-20">
		<div class="container-xw<?php echo $is_dark ? '' : ' mx-auto max-w-4xl'; ?>">
			<?php if ( $eyebrow || $heading ) : ?>
				<div class="mb-10 max-w-lg">
					<?php if ( $eyebrow ) : ?>
						<p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] <?php echo esc_attr( $eyebrow_class ); ?>"><?php echo esc_html( $eyebrow ); ?></p>
					<?php endif; ?>
					<?php if ( $heading ) : ?>
						<h2 class="mt-2 text-2xl font-bold <?php echo esc_attr( $heading_class ); ?> md:text-3xl"><?php echo esc_html( $heading ); ?></h2>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="grid grid-cols-1 gap-10 sm:grid-cols-3<?php echo $is_dark ? ' value-prop-dark' : ''; ?>">
				<?php echo $content; // phpcs:ignore -- already-rendered child blocks. ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function hg_render_value_prop_item_block( $attributes ) {
	$icon  = isset( $attributes['icon'] ) ? $attributes['icon'] : 'spark';
	$title = isset( $attributes['title'] ) ? $attributes['title'] : '';
	$body  = isset( $attributes['body'] ) ? $attributes['body'] : '';
	$tint  = isset( $attributes['tint'] ) ? $attributes['tint'] : 'green';

	// Literal class strings per tint (not string interpolation) so
	// Tailwind's static scanner can find and generate them — an
	// interpolated "bg-{$tint}/15" would never appear in the compiled CSS.
	$tint_classes = array(
		'green' => 'bg-green/15 text-green',
		'blue'  => 'bg-blue/15 text-blue',
		'gold'  => 'bg-gold/15 text-gold',
	);
	$badge_class = isset( $tint_classes[ $tint ] ) ? $tint_classes[ $tint ] : $tint_classes['green'];

	ob_start();
	?>
	<div>
		<div class="flex h-11 w-11 items-center justify-center rounded-xl <?php echo esc_attr( $badge_class ); ?>">
			<?php hg_icon( $icon, 'h-5 w-5' ); ?>
		</div>
		<?php if ( $title ) : ?>
			<h3 class="mt-4 font-bold text-ink"><?php echo wp_kses_post( $title ); ?></h3>
		<?php endif; ?>
		<?php if ( $body ) : ?>
			<p class="mt-2 text-sm leading-relaxed text-ink/60"><?php echo wp_kses_post( $body ); ?></p>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/* ------------------------------------------------------------------ *
 * Process Steps / Process Step
 * ------------------------------------------------------------------ */

function hg_render_process_steps_block( $attributes, $content ) {
	return sprintf(
		'<div class="grid grid-cols-1 gap-8 sm:grid-cols-3">%s</div>',
		$content // phpcs:ignore -- already-rendered child blocks.
	);
}

function hg_render_process_step_block( $attributes ) {
	$number = isset( $attributes['number'] ) ? $attributes['number'] : '';
	$title  = isset( $attributes['title'] ) ? $attributes['title'] : '';
	$body   = isset( $attributes['body'] ) ? $attributes['body'] : '';

	ob_start();
	?>
	<div class="rounded-2xl border border-ink/10 p-6">
		<?php if ( $number ) : ?>
			<span class="font-sans text-xs font-bold text-green"><?php echo esc_html( $number ); ?></span>
		<?php endif; ?>
		<?php if ( $title ) : ?>
			<h3 class="mt-3 font-bold text-ink"><?php echo wp_kses_post( $title ); ?></h3>
		<?php endif; ?>
		<?php if ( $body ) : ?>
			<p class="mt-2 text-sm leading-relaxed text-ink/60"><?php echo wp_kses_post( $body ); ?></p>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
