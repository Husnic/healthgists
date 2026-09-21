<?php
/**
 * Newsletter signup band — matches newsletter_block() in the static
 * prototype. Include with get_template_part( 'template-parts/newsletter', null, array( 'variant' => 'dark' ) ).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hg_variant = isset( $args['variant'] ) ? $args['variant'] : 'light';
$hg_dark    = 'dark' === $hg_variant;
?>
<section class="bleed <?php echo $hg_dark ? 'bg-ink text-white' : 'bg-lavender'; ?>">
	<div class="container-xw py-16 md:py-20">
		<div class="mx-auto max-w-xl text-center">
			<h2 class="text-2xl font-bold md:text-3xl <?php echo $hg_dark ? 'text-white' : 'text-ink'; ?>"><?php esc_html_e( 'Never miss a health update', 'healthgists' ); ?></h2>
			<p class="mt-3 text-sm <?php echo $hg_dark ? 'text-white/60' : 'text-ink/60'; ?>"><?php esc_html_e( 'Evidence-based articles, straight to your inbox. No spam, unsubscribe anytime.', 'healthgists' ); ?></p>
			<form data-newsletter-form class="mt-6 flex flex-col gap-3 sm:flex-row">
				<input type="email" required placeholder="<?php esc_attr_e( 'Enter your email address', 'healthgists' ); ?>" class="w-full rounded-full border border-ink/15 bg-white px-5 py-3 text-sm text-ink outline-none focus:border-blue" />
				<button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-green px-6 py-3 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5"><?php esc_html_e( 'Subscribe', 'healthgists' ); ?></button>
				<!-- Inside the form deliberately — main.js does form.querySelector()
				     to find this, which only searches descendants. -->
				<p data-newsletter-status class="hidden w-full text-sm sm:mt-3"></p>
			</form>
		</div>
	</div>
</section>
