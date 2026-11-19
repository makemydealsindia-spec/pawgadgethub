<?php
/**
 * 404 page.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="content" class="site-main">
	<div class="container container--narrow pgh-404">
		<?php echo pgh_paw_svg( 'pgh-404__paw' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<h1><?php esc_html_e( 'Oops, this page ran off.', 'pawgadgethub' ); ?></h1>
		<p><?php esc_html_e( 'We could not find what you were looking for. Try a search, or head back to the reviews.', 'pawgadgethub' ); ?></p>
		<?php get_search_form(); ?>
		<p><a class="pgh-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the homepage', 'pawgadgethub' ); ?></a></p>
	</div>
</main>
<?php
get_footer();
