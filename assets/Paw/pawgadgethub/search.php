<?php
/**
 * Search results.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="content" class="site-main">
	<div class="container">
		<?php pgh_breadcrumbs(); ?>
		<header class="pgh-archive-head">
			<h1>
				<?php
				/* translators: %s: search query */
				printf( esc_html__( 'Search results for: %s', 'pawgadgethub' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="pgh-grid pgh-grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => esc_html__( 'Previous', 'pawgadgethub' ),
					'next_text' => esc_html__( 'Next', 'pawgadgethub' ),
				)
			);
			?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content-none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
