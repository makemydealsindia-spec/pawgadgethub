<?php
/**
 * Shown when there is nothing to list.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="pgh-none">
	<h2><?php esc_html_e( 'Nothing found here yet', 'pawgadgethub' ); ?></h2>
	<p><?php esc_html_e( 'Try a different search, or browse our categories from the menu.', 'pawgadgethub' ); ?></p>
	<?php get_search_form(); ?>
</section>
