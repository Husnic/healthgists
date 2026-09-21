<?php
/**
 * The Blog index (Settings → Reading → Posts page) — matches build_blog()
 * in the static prototype. WordPress renders this template for the posts
 * page specifically when a separate static front page is also set (see
 * front-page.php + bin/seed-site.php's Reading settings).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$hg_categories = get_categories( array( 'hide_empty' => false ) );
?>

<section class="border-b border-ink/10 bg-white py-14 md:py-16">
	<div class="container-xw">
		<div class="flex flex-wrap items-start justify-between gap-6">
			<div>
				<h1 class="text-3xl font-extrabold text-ink md:text-4xl"><?php esc_html_e( 'The Blog', 'healthgists' ); ?></h1>
				<p class="mt-3 max-w-xl text-sm text-ink/60"><?php esc_html_e( 'Every article, in one place — filter by topic or just start scrolling.', 'healthgists' ); ?></p>
			</div>
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="w-full sm:w-64">
				<label class="sr-only" for="hg-blog-search"><?php esc_html_e( 'Search articles', 'healthgists' ); ?></label>
				<div class="relative">
					<svg viewBox="0 0 24 24" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-ink/40" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>
					<input id="hg-blog-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search articles…', 'healthgists' ); ?>" class="w-full rounded-full border border-ink/15 bg-white py-2.5 pl-10 pr-4 text-sm text-ink outline-none focus:border-green" />
				</div>
			</form>
		</div>
		<nav aria-label="<?php esc_attr_e( 'Filter by category', 'healthgists' ); ?>" class="-mx-6 mt-7 flex flex-nowrap gap-3 overflow-x-auto px-6 pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden md:mx-0 md:flex-wrap md:overflow-visible md:px-0">
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="shrink-0 whitespace-nowrap rounded-full bg-green px-4 py-2 text-sm font-semibold text-white"><?php esc_html_e( 'All', 'healthgists' ); ?></a>
			<?php foreach ( $hg_categories as $cat ) : ?>
				<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" class="shrink-0 whitespace-nowrap rounded-full border border-ink/12 bg-white px-4 py-2 text-sm font-medium text-ink/70 transition-colors hover:border-green hover:text-green"><?php echo esc_html( $cat->name ); ?></a>
			<?php endforeach; ?>
		</nav>
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
			<p class="text-sm text-ink/50"><?php esc_html_e( 'No articles published yet — check back soon.', 'healthgists' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/newsletter' ); ?>

<?php get_footer(); ?>
