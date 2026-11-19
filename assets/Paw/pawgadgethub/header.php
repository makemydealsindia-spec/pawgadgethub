<?php
/**
 * Site header.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'pawgadgethub' ); ?></a>

<header class="site-header" id="masthead">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php pgh_site_logo(); ?>
		</div>

		<nav id="site-nav" class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'pawgadgethub' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu',
					'depth'          => 2,
					'fallback_cb'    => 'pgh_nav_fallback',
				)
			);
			?>
		</nav>

		<div class="site-header__tools">
			<button type="button" class="icon-btn js-search-toggle" aria-expanded="false" aria-controls="header-search">
				<span class="screen-reader-text"><?php esc_html_e( 'Search', 'pawgadgethub' ); ?></span>
				<?php echo pgh_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<button type="button" class="icon-btn nav-toggle js-nav-toggle" aria-expanded="false" aria-controls="site-nav">
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'pawgadgethub' ); ?></span>
				<span class="nav-toggle__open"><?php echo pgh_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="nav-toggle__close"><?php echo pgh_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</button>
		</div>
	</div>

	<div id="header-search" class="header-search" hidden>
		<div class="container">
			<?php get_search_form(); ?>
		</div>
	</div>
</header>
