<?php
/**
 * Search form.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pgh_uid = wp_unique_id( 'pgh-search-' );
?>
<form role="search" method="get" class="pgh-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $pgh_uid ); ?>"><?php esc_html_e( 'Search for:', 'pawgadgethub' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $pgh_uid ); ?>" class="pgh-search__input" placeholder="<?php echo esc_attr_x( 'Search reviews, e.g. "cat feeder"', 'placeholder', 'pawgadgethub' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="pgh-search__btn">
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'pawgadgethub' ); ?></span>
		<?php echo pgh_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</button>
</form>
