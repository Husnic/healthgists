<?php
/**
 * Search results — same card grid/pagination as home.php and archive.php,
 * with a results heading and the search term pre-filled back into the box.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="border-b border-ink/10 bg-white py-14 md:py-16">
	<div class="container-xw">
		<p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-green"><?php esc_html_e( 'Search results', 'healthgists' ); ?></p>
		<h1 class="mt-2 text-3xl font-extrabold text-ink md:text-4xl">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( '%s results for “%s”', 'healthgists' ),
				(int) $GLOBALS['wp_query']->found_posts,
				get_search_query()
			);
			?>
		</h1>
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="mt-6 w-full sm:w-96">
			<label class="sr-only" for="hg-search-again"><?php esc_html_e( 'Search articles', 'healthgists' ); ?></label>
			<div class="relative">
				<svg viewBox="0 0 24 24" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-ink/40" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>
				<input id="hg-search-again" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search articles…', 'healthgists' ); ?>" class="w-full rounded-full border border-ink/15 bg-white py-2.5 pl-10 pr-4 text-sm text-ink outline-none focus:border-green" />
			</div>
		</form>
	</div>
</section>

<section class="py-16 md:py-20">
	<div class="container-xw">
		<?php if ( have_posts() ) : ?>
			<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
				<?php
				while ( have_posts() ) :
					the_post();
					hg_render_post_card( get_post() );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="text-sm text-ink/50"><?php esc_html_e( 'No articles matched your search — try a different term.', 'healthgists' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/newsletter' ); ?>

<?php get_footer(); ?>
