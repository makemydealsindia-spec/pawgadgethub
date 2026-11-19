<?php
/**
 * Fallback template and the blog index.
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
		<header class="pgh-archive-head">
			<h1><?php echo is_home() && ! is_front_page() ? esc_html( single_post_title( '', false ) ) : esc_html__( 'Latest reviews & guides', 'pawgadgethub' ); ?></h1>
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
