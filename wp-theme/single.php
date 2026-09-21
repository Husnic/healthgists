<?php
/**
 * Single post — matches build_post() in the static prototype: breadcrumbs,
 * category badge, title, byline, featured image banner, article body,
 * "reviewed by" box, related posts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

have_posts() && the_post();

$hg_post_id     = get_the_ID();
$hg_categories  = get_the_category( $hg_post_id );
$hg_cat         = ! empty( $hg_categories ) ? $hg_categories[0] : null;
$hg_read_time   = hg_read_time( $hg_post_id );
$hg_author_name = get_the_author();
?>

<article>
	<section class="border-b border-ink/10 bg-white py-12 md:py-16">
		<div class="container-xw mx-auto max-w-3xl">
			<?php
			$hg_trail = array(
				array( __( 'Home', 'healthgists' ), home_url( '/' ) ),
				array( __( 'Blog', 'healthgists' ), get_permalink( get_option( 'page_for_posts' ) ) ),
			);
			if ( $hg_cat ) {
				$hg_trail[] = array( $hg_cat->name, get_category_link( $hg_cat ) );
			}
			hg_breadcrumbs( $hg_trail );
			?>
			<?php if ( $hg_cat ) : ?>
				<a href="<?php echo esc_url( get_category_link( $hg_cat ) ); ?>" class="font-sans mt-4 inline-block rounded-full bg-green/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-green"><?php echo esc_html( $hg_cat->name ); ?></a>
			<?php endif; ?>
			<h1 class="mt-5 text-3xl font-extrabold leading-tight text-ink md:text-4xl"><?php the_title(); ?></h1>
			<div class="font-sans mt-5 flex items-center gap-3 text-sm text-ink/50">
				<span class="font-semibold text-ink/70"><?php echo esc_html( $hg_author_name ); ?></span><span>&middot;</span><span><?php echo esc_html( get_the_date() ); ?></span><span>&middot;</span><span><?php echo esc_html( $hg_read_time ); ?></span>
			</div>
		</div>
	</section>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="container-xw mx-auto -mt-6 max-w-4xl md:-mt-10">
			<div class="overflow-hidden rounded-3xl shadow-xl shadow-ink/10">
				<?php the_post_thumbnail( 'hg-banner', array( 'class' => 'aspect-[16/8] w-full object-cover' ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<section class="py-14 md:py-16">
		<div class="container-xw mx-auto max-w-2xl">
			<div class="article-body">
				<?php the_content(); ?>
			</div>

			<div class="font-sans mt-12 flex items-center gap-4 rounded-2xl border border-ink/10 bg-paper-warm p-6">
				<div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green text-sm font-bold text-white">
					<?php echo esc_html( strtoupper( substr( $hg_author_name, 0, 2 ) ) ); ?>
				</div>
				<div>
					<p class="text-sm font-semibold text-ink"><?php echo esc_html( $hg_author_name ); ?></p>
					<p class="text-xs text-ink/50"><?php esc_html_e( 'Reviewed for accuracy by the Healthgists editorial team.', 'healthgists' ); ?></p>
				</div>
			</div>

			<?php
			$hg_share_url   = rawurlencode( get_permalink() );
			$hg_share_title = rawurlencode( get_the_title() );
			$hg_share_links = array(
				'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $hg_share_url,
				'x'        => 'https://twitter.com/intent/tweet?url=' . $hg_share_url . '&text=' . $hg_share_title,
				'whatsapp' => 'https://wa.me/?text=' . $hg_share_title . '%20' . $hg_share_url,
			);
			?>
			<div class="font-sans mt-8 flex items-center gap-3">
				<span class="text-xs font-semibold uppercase tracking-wide text-ink/40"><?php esc_html_e( 'Share', 'healthgists' ); ?></span>
				<?php foreach ( $hg_share_links as $hg_platform => $hg_share_url_full ) : ?>
					<a href="<?php echo esc_url( $hg_share_url_full ); ?>" target="_blank" rel="noreferrer noopener" aria-label="<?php echo esc_attr( sprintf( __( 'Share on %s', 'healthgists' ), ucfirst( $hg_platform ) ) ); ?>" class="flex h-9 w-9 items-center justify-center rounded-full border border-ink/12 text-ink/60 transition-colors hover:border-green hover:text-green">
						<?php hg_icon( $hg_platform, 'h-4 w-4' ); ?>
					</a>
				<?php endforeach; ?>
				<button type="button" data-copy-link="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Copy link', 'healthgists' ); ?>" class="flex h-9 w-9 items-center justify-center rounded-full border border-ink/12 text-ink/60 transition-colors hover:border-green hover:text-green">
					<?php hg_icon( 'link', 'h-4 w-4' ); ?>
				</button>
				<span data-copy-link-status class="hidden text-xs text-green"><?php esc_html_e( 'Link copied', 'healthgists' ); ?></span>
			</div>
		</div>
	</section>
</article>

<?php
$hg_related_args = array(
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'post__not_in'   => array( $hg_post_id ),
);
if ( $hg_cat ) {
	$hg_related_args['cat'] = $hg_cat->term_id;
}
$hg_related = new WP_Query( $hg_related_args );
if ( $hg_related->have_posts() ) :
	?>
	<section class="border-t border-ink/10 py-16 md:py-20">
		<div class="container-xw">
			<h2 class="text-2xl font-bold text-ink"><?php esc_html_e( 'More from Healthgists', 'healthgists' ); ?></h2>
			<div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
				<?php
				while ( $hg_related->have_posts() ) :
					$hg_related->the_post();
					hg_render_post_card( get_post() );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
endif;
?>

<?php get_template_part( 'template-parts/newsletter' ); ?>

<?php get_footer(); ?>
