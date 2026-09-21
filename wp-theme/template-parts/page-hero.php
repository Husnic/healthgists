<?php
/**
 * Photo banner hero for interior pages (About, Contact, and any future
 * page that wants one) — same visual treatment as the category archive
 * banner in archive.php. Call via hg_page_hero( array( 'eyebrow' => ...,
 * 'title' => ..., 'subtitle' => ..., 'image' => ... ) ).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hg_eyebrow  = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$hg_title    = isset( $args['title'] ) ? $args['title'] : get_the_title();
$hg_subtitle = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$hg_image    = isset( $args['image'] ) ? $args['image'] : '';
?>
<section class="bleed" <?php echo $hg_image ? 'style="background-image:url(\'' . esc_url( $hg_image ) . '\');background-size:cover;background-position:center;"' : ''; ?>>
	<div class="<?php echo $hg_image ? 'bg-ink/55' : 'bg-ink'; ?> py-20 md:py-24">
		<div class="container-xw">
			<?php if ( $hg_eyebrow ) : ?>
				<p class="font-sans text-xs font-semibold uppercase tracking-[0.25em] text-white/70"><?php echo esc_html( $hg_eyebrow ); ?></p>
			<?php endif; ?>
			<h1 class="mt-3 text-3xl font-extrabold text-white md:text-4xl"><?php echo esc_html( $hg_title ); ?></h1>
			<?php if ( $hg_subtitle ) : ?>
				<p class="mt-3 max-w-xl text-sm text-white/75"><?php echo esc_html( $hg_subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
