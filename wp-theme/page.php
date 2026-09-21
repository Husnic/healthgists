<?php
/**
 * Generic page template — title + block-built content in .entry-content.
 * Used for About and any other page whose content is entirely block-built
 * (Category Showcase, Value Prop Grid, Process Steps, core paragraphs).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

have_posts() && the_post();

hg_page_hero( array(
	'eyebrow'  => __( 'About', 'healthgists' ),
	'subtitle' => has_excerpt() ? get_the_excerpt() : '',
) );
?>

<div class="container-xw py-16 md:py-24">
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</div>

<?php get_template_part( 'template-parts/newsletter' ); ?>

<?php get_footer(); ?>
