<?php
/**
 * Comments.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="pgh-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="pgh-comments__title">
			<?php
			printf(
				/* translators: %s: number of comments */
				esc_html( _n( '%s comment', '%s comments', get_comments_number(), 'pawgadgethub' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>
		<ol class="pgh-comments__list">
			<?php
			wp_list_comments(
				array(
					'type'        => 'comment',
					'style'       => 'ol',
					'short_ping'  => false,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'pawgadgethub' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</section>
