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
?>

<div class="entry-content">
	<?php the_content(); ?>
</div>

<?php get_template_part( 'template-parts/newsletter' ); ?>

<?php get_footer(); ?>
