<?php
/**
 * Category archive — matches build_category() in the static prototype:
 * a photo banner (from the category's cover-image term meta) + post grid.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$hg_term  = get_queried_object();
$hg_image = ( $hg_term instanceof WP_Term ) ? hg_get_category_image_url( $hg_term, 'hg-banner' ) : '';
?>

<section class="bleed" <?php echo $hg_image ? 'style="background-image:url(\'' . esc_url( $hg_image ) . '\');background-size:cover;background-position:center;"' : ''; ?>>
	<div class="<?php echo $hg_image ? 'bg-ink/55' : 'bg-ink'; ?> py-20 md:py-24">
		<div class="container-xw">
			<p class="font-sans text-xs font-semibold uppercase tracking-[0.25em] text-white/70"><?php esc_html_e( 'Category', 'healthgists' ); ?></p>
			<h1 class="mt-3 text-3xl font-extrabold text-white md:text-4xl"><?php single_cat_title(); ?></h1>
			<?php if ( $hg_term instanceof WP_Term && $hg_term->description ) : ?>
				<p class="mt-3 max-w-xl text-sm text-white/75"><?php echo esc_html( $hg_term->description ); ?></p>
			<?php endif; ?>
		</div>
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
			<p class="text-sm text-ink/50"><?php esc_html_e( 'No articles in this category yet — check back soon.', 'healthgists' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/newsletter' ); ?>

<?php get_footer(); ?>
