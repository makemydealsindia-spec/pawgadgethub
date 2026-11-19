<?php
/**
 * Homepage.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$featured = pgh_featured_query( 4 );
?>
<main id="content" class="site-main site-main--home">

	<section class="pgh-hero">
		<div class="container pgh-hero__inner">
			<div class="pgh-hero__copy">
				<p class="pgh-eyebrow"><?php esc_html_e( 'Smart pet tech, reviewed', 'pawgadgethub' ); ?></p>
				<h1><?php echo esc_html( pgh_opt( 'hero_title' ) ); ?></h1>
				<p class="pgh-hero__sub"><?php echo esc_html( pgh_opt( 'hero_subtitle' ) ); ?></p>
				<div class="pgh-hero__actions">
					<a class="pgh-btn" href="#reviews"><?php esc_html_e( 'Browse top reviews', 'pawgadgethub' ); ?> <?php echo pgh_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<a class="pgh-btn pgh-btn--ghost" href="<?php echo esc_url( pgh_page_url( 'about' ) ); ?>"><?php esc_html_e( 'How we review', 'pawgadgethub' ); ?></a>
				</div>
			</div>
			<div class="pgh-hero__art" aria-hidden="true">
				<div class="pgh-hero__blob"></div>
				<?php echo pgh_paw_svg( 'pgh-hero__paw' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>
	</section>

	<section class="pgh-section" aria-labelledby="cats-title">
		<div class="container">
			<h2 class="pgh-section__title" id="cats-title"><?php esc_html_e( 'Shop smarter by category', 'pawgadgethub' ); ?></h2>
			<div class="pgh-catgrid">
				<?php foreach ( pgh_categories() as $slug => $cat ) : ?>
					<a class="pgh-catcard" href="<?php echo esc_url( pgh_category_url( $slug ) ); ?>">
						<span class="pgh-catcard__icon"><?php echo pgh_icon( $cat['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<h3><?php echo esc_html( $cat['label'] ); ?></h3>
						<p><?php echo esc_html( $cat['blurb'] ); ?></p>
						<span class="pgh-catcard__more"><?php esc_html_e( 'Explore', 'pawgadgethub' ); ?> <?php echo pgh_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="pgh-section pgh-section--soft" id="reviews" aria-labelledby="featured-title">
		<div class="container">
			<h2 class="pgh-section__title" id="featured-title"><?php esc_html_e( 'Featured reviews & guides', 'pawgadgethub' ); ?></h2>
			<?php if ( $featured && $featured->have_posts() ) : ?>
				<div class="pgh-grid pgh-grid--4">
					<?php
					while ( $featured->have_posts() ) :
						$featured->the_post();
						get_template_part( 'template-parts/card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
				<?php
				$posts_page = (int) get_option( 'page_for_posts' );
				if ( $posts_page ) :
					?>
					<p class="pgh-section__more"><a class="pgh-btn pgh-btn--ghost" href="<?php echo esc_url( get_permalink( $posts_page ) ); ?>"><?php esc_html_e( 'See all reviews', 'pawgadgethub' ); ?> <?php echo pgh_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></p>
				<?php endif; ?>
			<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>
				<p class="pgh-notice"><?php esc_html_e( 'Admin notice: no posts yet. Publish your first review and it will appear here. Tag posts "featured" to pin them to this section.', 'pawgadgethub' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="pgh-section" aria-labelledby="trust-title">
		<div class="container">
			<h2 class="pgh-section__title" id="trust-title"><?php esc_html_e( 'Why trust PawGadgetHub', 'pawgadgethub' ); ?></h2>
			<div class="pgh-pillars">
				<div class="pgh-pillar">
					<span class="pgh-pillar__icon"><?php echo pgh_icon( 'flask' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3><?php esc_html_e( 'Unbiased testing', 'pawgadgethub' ); ?></h3>
					<p><?php esc_html_e( 'We evaluate the hardware, the app reliability, and the real-world battery life, not just the spec sheet.', 'pawgadgethub' ); ?></p>
				</div>
				<div class="pgh-pillar">
					<span class="pgh-pillar__icon"><?php echo pgh_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3><?php esc_html_e( 'Pet safety first', 'pawgadgethub' ); ?></h3>
					<p><?php esc_html_e( 'We favor BPA-free, food-grade materials such as SUS304 stainless steel for anything your pet eats or drinks from.', 'pawgadgethub' ); ?></p>
				</div>
				<div class="pgh-pillar">
					<span class="pgh-pillar__icon"><?php echo pgh_icon( 'scale' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3><?php esc_html_e( 'Independent guidance', 'pawgadgethub' ); ?></h3>
					<p><?php esc_html_e( 'Clear pros and cons on every product we feature, so you can decide with confidence.', 'pawgadgethub' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<?php pgh_render_newsletter(); ?>

</main>
<?php
get_footer();
