<?php
/**
 * Fallback template — WordPress requires an index.php in every theme.
 * In normal use, front-page.php / home.php / single.php / archive.php /
 * page.php cover every URL this theme serves; this only renders for an
 * unexpected query WordPress couldn't match to a more specific template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="py-20">
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
		<?php else : ?>
			<p class="text-sm text-ink/50"><?php esc_html_e( 'Nothing found.', 'healthgists' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
