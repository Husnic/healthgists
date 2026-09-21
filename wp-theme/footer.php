<?php
/**
 * The footer for the theme — matches footer() in the static prototype's
 * bin/generate.py.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hg_categories = get_categories( array( 'hide_empty' => false ) );
?>
</main>

<footer class="bg-ink text-white/70">
	<div class="container-xw grid grid-cols-1 gap-10 py-16 md:grid-cols-4">
		<div class="md:col-span-2">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand/logo-white.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-8 w-auto" />
			<p class="mt-4 max-w-sm text-sm leading-relaxed text-white/55"><?php echo esc_html( hg_org_info( 'tagline' ) ); ?> &mdash; <?php esc_html_e( 'evidence-based health articles, wellness tips, and medical news, curated by professionals.', 'healthgists' ); ?></p>
			<div class="mt-6 flex gap-3">
				<?php foreach ( hg_org_info( 'socials' ) as $network => $link ) : ?>
					<a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noreferrer" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 transition-colors hover:border-white/40 hover:text-white">
						<?php hg_icon( $network, 'h-4 w-4' ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<div>
			<p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40"><?php esc_html_e( 'Categories', 'healthgists' ); ?></p>
			<ul class="mt-4 space-y-2 text-sm">
				<?php foreach ( $hg_categories as $cat ) : ?>
					<li><a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" class="hover:text-white"><?php echo esc_html( $cat->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div>
			<p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40"><?php esc_html_e( 'Site', 'healthgists' ); ?></p>
			<ul class="mt-4 space-y-2 text-sm">
				<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="hover:text-white"><?php esc_html_e( 'About', 'healthgists' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="hover:text-white"><?php esc_html_e( 'Contact', 'healthgists' ); ?></a></li>
				<li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="hover:text-white"><?php esc_html_e( 'Blog', 'healthgists' ); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="border-t border-white/10">
		<div class="container-xw flex flex-col gap-2 py-6 text-xs text-white/40 sm:flex-row sm:items-center sm:justify-between">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> &mdash; <?php esc_html_e( 'all rights reserved.', 'healthgists' ); ?></span>
			<span><?php esc_html_e( 'Built by', 'healthgists' ); ?> <a href="https://husnic.com" target="_blank" rel="noreferrer" class="text-white/60 hover:text-white">Husnic Consulting</a></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
