<?php
/**
 * Contact — page-contact.php (auto-applies to Page slug "contact").
 * Matches build_contact() in the static prototype.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$sent = isset( $_GET['sent'] ) ? sanitize_text_field( wp_unslash( $_GET['sent'] ) ) : '';

have_posts() && the_post();

hg_page_hero( array(
	'eyebrow'  => __( 'Contact', 'healthgists' ),
	'subtitle' => has_excerpt() ? get_the_excerpt() : '',
) );
?>

<section class="py-16 md:py-24">
	<div class="container-xw grid grid-cols-1 gap-12 md:grid-cols-2">
		<div>
			<div class="mt-0 space-y-5 text-sm">
				<div class="flex items-center gap-3">
					<div class="flex h-10 w-10 items-center justify-center rounded-full bg-green/10 text-green">
						<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 5.5C4 4.67 4.67 4 5.5 4h13c.83 0 1.5.67 1.5 1.5v13c0 .83-.67 1.5-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/><path d="m4.5 6 7.5 6 7.5-6"/></svg>
					</div>
					<a href="mailto:<?php echo esc_attr( hg_org_info( 'email' ) ); ?>" class="font-medium text-ink hover:text-green"><?php echo esc_html( hg_org_info( 'email' ) ); ?></a>
				</div>
			</div>
			<?php if ( get_the_content() ) : ?>
				<div class="entry-content mt-10"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>

		<div>
			<?php if ( '1' === $sent ) : ?>
				<div class="mb-6 rounded-xl border border-green/20 bg-green/5 px-4 py-3 text-sm text-green"><?php esc_html_e( "Thank you — your message has been sent. We'll get back to you soon.", 'healthgists' ); ?></div>
			<?php elseif ( 'error' === $sent ) : ?>
				<div class="mb-6 rounded-xl border border-red-600/20 bg-red-50 px-4 py-3 text-sm text-red-800"><?php esc_html_e( 'Something went wrong sending your message — please try again, or email us directly.', 'healthgists' ); ?></div>
			<?php endif; ?>

			<form class="rounded-3xl border border-ink/10 bg-white p-8" method="post" action="<?php echo esc_url( get_permalink() ); ?>">
				<?php wp_nonce_field( 'hg_contact_form', 'hg_contact_nonce' ); ?>
				<input type="text" name="hg_website" value="" class="hidden" tabindex="-1" autocomplete="off" />

				<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
					<div class="sm:col-span-1">
						<label for="hg_name" class="text-xs font-semibold uppercase tracking-wide text-ink/50"><?php esc_html_e( 'Name', 'healthgists' ); ?></label>
						<input required type="text" id="hg_name" name="hg_name" class="mt-2 w-full rounded-xl border border-ink/15 px-4 py-3 text-sm outline-none focus:border-blue" />
					</div>
					<div class="sm:col-span-1">
						<label for="hg_email" class="text-xs font-semibold uppercase tracking-wide text-ink/50"><?php esc_html_e( 'Email', 'healthgists' ); ?></label>
						<input required type="email" id="hg_email" name="hg_email" class="mt-2 w-full rounded-xl border border-ink/15 px-4 py-3 text-sm outline-none focus:border-blue" />
					</div>
					<div class="sm:col-span-2">
						<label for="hg_message" class="text-xs font-semibold uppercase tracking-wide text-ink/50"><?php esc_html_e( 'Message', 'healthgists' ); ?></label>
						<textarea required rows="5" id="hg_message" name="hg_message" class="mt-2 w-full rounded-xl border border-ink/15 px-4 py-3 text-sm outline-none focus:border-blue"></textarea>
					</div>
				</div>
				<button type="submit" name="hg_contact_submit" value="1" class="mt-6 inline-flex items-center justify-center rounded-full bg-green px-6 py-3 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5"><?php esc_html_e( 'Send Message', 'healthgists' ); ?></button>
			</form>
		</div>
	</div>
</section>

<?php get_footer(); ?>
