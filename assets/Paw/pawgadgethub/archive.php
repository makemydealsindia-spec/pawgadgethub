<?php
/**
 * Category, tag, author and date archives.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$cat_meta = null;
if ( is_category() ) {
	$term     = get_queried_object();
	$all_cats = pgh_categories();
	if ( $term && isset( $all_cats[ $term->slug ] ) ) {
		$cat_meta = $all_cats[ $term->slug ];
	}
}
?>
<main id="content" class="site-main">
	<div class="container">
		<?php pgh_breadcrumbs(); ?>

		<header class="pgh-archive-head">
			<?php if ( $cat_meta ) : ?>
				<span class="pgh-archive-head__icon"><?php echo pgh_icon( $cat_meta['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<?php endif; ?>
			<h1><?php the_archive_title(); ?></h1>
			<?php if ( get_the_archive_description() ) : ?>
				<div class="pgh-archive-head__desc"><?php the_archive_description(); ?></div>
			<?php elseif ( $cat_meta ) : ?>
				<div class="pgh-archive-head__desc"><p><?php echo esc_html( $cat_meta['blurb'] ); ?></p></div>
			<?php endif; ?>
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
