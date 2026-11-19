<?php
/**
 * Per-post options: hide the table of contents or the disclosure banner.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pgh_add_meta_box() {
	add_meta_box(
		'pgh_options',
		__( 'PawGadgetHub options', 'pawgadgethub' ),
		'pgh_meta_box_html',
		'post',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'pgh_add_meta_box' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current post.
 */
function pgh_meta_box_html( $post ) {
	wp_nonce_field( 'pgh_save_meta', 'pgh_meta_nonce' );
	$hide_toc  = get_post_meta( $post->ID, '_pgh_hide_toc', true );
	$hide_disc = get_post_meta( $post->ID, '_pgh_hide_disclosure', true );
	?>
	<p>
		<label>
			<input type="checkbox" name="pgh_hide_toc" value="1" <?php checked( $hide_toc, '1' ); ?>>
			<?php esc_html_e( 'Hide table of contents', 'pawgadgethub' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="pgh_hide_disclosure" value="1" <?php checked( $hide_disc, '1' ); ?>>
			<?php esc_html_e( 'Hide affiliate disclosure banner', 'pawgadgethub' ); ?>
		</label>
	</p>
	<p class="description"><?php esc_html_e( 'Only hide the banner on posts with no affiliate links.', 'pawgadgethub' ); ?></p>
	<?php
}

/**
 * Save the meta box.
 *
 * @param int $post_id Post ID.
 */
function pgh_save_meta( $post_id ) {
	if ( ! isset( $_POST['pgh_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pgh_meta_nonce'] ) ), 'pgh_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( 'pgh_hide_toc' => '_pgh_hide_toc', 'pgh_hide_disclosure' => '_pgh_hide_disclosure' ) as $field => $key ) {
		if ( ! empty( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $key, '1' );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}
}
add_action( 'save_post_post', 'pgh_save_meta' );
