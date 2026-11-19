<?php
/**
 * Site footer.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<?php pgh_site_logo(); ?>
			<p><?php echo esc_html( pgh_opt( 'footer_about' ) ); ?></p>
		</div>

		<nav class="site-footer__col" aria-label="<?php esc_attr_e( 'Categories', 'pawgadgethub' ); ?>">
			<h2 class="site-footer__title"><?php esc_html_e( 'Explore', 'pawgadgethub' ); ?></h2>
			<ul class="menu">
				<?php foreach ( pgh_categories() as $slug => $cat ) : ?>
					<li class="menu-item"><a href="<?php echo esc_url( pgh_category_url( $slug ) ); ?>"><?php echo esc_html( $cat['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<nav class="site-footer__col" aria-label="<?php esc_attr_e( 'Footer', 'pawgadgethub' ); ?>">
			<h2 class="site-footer__title"><?php esc_html_e( 'PawGadgetHub', 'pawgadgethub' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'menu',
					'depth'          => 1,
					'fallback_cb'    => 'pgh_footer_nav_fallback',
				)
			);
			?>
		</nav>
	</div>

	<div class="site-footer__bottom">
		<div class="container">
			<p class="site-footer__disclosure"><?php echo esc_html( pgh_opt( 'amazon_statement' ) ); ?></p>
			<p class="site-footer__copy">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
				<?php esc_html_e( 'All rights reserved. Product names and logos belong to their respective owners.', 'pawgadgethub' ); ?>
			</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
