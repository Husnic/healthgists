<?php
/**
 * The front page (Home).
 *
 * The hero, category showcase, and "why trust us" value props all live in
 * the Page's own content as custom blocks (Hero Slider, Category Showcase,
 * Value Prop Grid) — fully editable in wp-admin, matching the static
 * prototype's build_index(). Only "Latest Articles" stays template-driven,
 * since it pulls live from published posts rather than being written
 * content itself — same split phf-ogun uses for its Programmes/Blog
 * sections.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

have_posts() && the_post();
?>

<div class="entry-content">
	<?php the_content(); ?>
</div>

<section class="py-16 md:py-24">
	<div class="container-xw">
		<div class="flex flex-wrap items-end justify-between gap-4">
			<h2 class="text-2xl font-bold text-ink md:text-3xl"><?php esc_html_e( 'Latest Articles', 'healthgists' ); ?></h2>
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="font-sans text-sm font-semibold text-green hover:underline"><?php esc_html_e( 'View all articles', 'healthgists' ); ?> &rarr;</a>
		</div>
		<div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			$hg_latest = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 6 ) );
			if ( $hg_latest->have_posts() ) :
				while ( $hg_latest->have_posts() ) :
					$hg_latest->the_post();
					hg_render_post_card( get_post() );
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/newsletter' ); ?>

<?php get_footer(); ?>
