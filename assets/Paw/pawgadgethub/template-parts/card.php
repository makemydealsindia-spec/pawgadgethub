<?php
/**
 * Post card used in all grids.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article <?php post_class( 'pgh-card' ); ?>>
	<a class="pgh-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'pgh-card', array( 'loading' => 'lazy' ) );
		} else {
			echo '<span class="pgh-card__ph">' . pgh_paw_svg() . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
		?>
	</a>
	<div class="pgh-card__body">
		<?php pgh_category_chip(); ?>
		<h3 class="pgh-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="pgh-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></p>
		<div class="pgh-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<span aria-hidden="true">&middot;</span>
			<span>
				<?php
				/* translators: %d: minutes */
				printf( esc_html__( '%d min read', 'pawgadgethub' ), (int) pgh_reading_time() );
				?>
			</span>
		</div>
	</div>
</article>
