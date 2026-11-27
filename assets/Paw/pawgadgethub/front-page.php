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

$featured = pgh_featured_query( 9 );

// Grab up to 3 latest post thumbnails for the warm hero photo collage.
$hero_thumbs = array();
$collage_q   = new WP_Query(
	array(
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'meta_key'            => '_thumbnail_id',
	)
);
if ( $collage_q->have_posts() ) {
	while ( $collage_q->have_posts() ) {
		$collage_q->the_post();
		$hero_thumbs[] = array(
			'url'   => get_the_post_thumbnail_url( get_the_ID(), 'pgh-card' ),
			'title' => get_the_title(),
			'link'  => get_permalink(),
		);
	}
	wp_reset_postdata();
}
$total_posts = (int) wp_count_posts( 'post' )->publish;
?>
<main id="content" class="site-main site-main--home">

	<section class="pgh-hero">
		<div class="container pgh-hero__inner">
			<div class="pgh-hero__copy">
				<div class="pgh-hero__brandpill">
					<img src="<?php echo esc_url( PGH_URI . '/assets/img/site-icon-512.png' ); ?>" alt="" width="28" height="28" />
					<span><?php esc_html_e( 'Trusted by US Dog & Cat Parents', 'pawgadgethub' ); ?></span>
				</div>
				<h1><?php echo esc_html( pgh_opt( 'hero_title' ) ); ?></h1>
				<p class="pgh-hero__sub"><?php echo esc_html( pgh_opt( 'hero_subtitle' ) ); ?></p>
				<div class="pgh-hero__actions">
					<a class="pgh-btn" href="#reviews"><?php esc_html_e( 'Explore Top Reviews', 'pawgadgethub' ); ?> <?php echo pgh_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<a class="pgh-btn pgh-btn--pet" href="#reviews" data-jump-pet="dog">&#128054; <?php esc_html_e( 'For Dogs', 'pawgadgethub' ); ?></a>
					<a class="pgh-btn pgh-btn--pet" href="#reviews" data-jump-pet="cat">&#128049; <?php esc_html_e( 'For Cats', 'pawgadgethub' ); ?></a>
				</div>
			</div>

			<div class="pgh-hero__visual">
				<?php if ( count( $hero_thumbs ) >= 3 ) : ?>
					<div class="pgh-collage">
						<a class="pgh-collage__main" href="<?php echo esc_url( $hero_thumbs[0]['link'] ); ?>">
							<img src="<?php echo esc_url( $hero_thumbs[0]['url'] ); ?>" alt="<?php echo esc_attr( $hero_thumbs[0]['title'] ); ?>" loading="eager" />
						</a>
						<a class="pgh-collage__sub pgh-collage__sub--top" href="<?php echo esc_url( $hero_thumbs[1]['link'] ); ?>">
							<img src="<?php echo esc_url( $hero_thumbs[1]['url'] ); ?>" alt="<?php echo esc_attr( $hero_thumbs[1]['title'] ); ?>" loading="eager" />
						</a>
						<a class="pgh-collage__sub pgh-collage__sub--bot" href="<?php echo esc_url( $hero_thumbs[2]['link'] ); ?>">
							<img src="<?php echo esc_url( $hero_thumbs[2]['url'] ); ?>" alt="<?php echo esc_attr( $hero_thumbs[2]['title'] ); ?>" loading="eager" />
						</a>
						<div class="pgh-collage__badge">
							<img src="<?php echo esc_url( PGH_URI . '/assets/img/site-icon-512.png' ); ?>" alt="PawGadgetHub" width="40" height="40" />
							<div>
								<strong><?php esc_html_e( 'Real-Home Tested', 'pawgadgethub' ); ?></strong>
								<span><?php esc_html_e( 'Zero sponsor bias', 'pawgadgethub' ); ?></span>
							</div>
						</div>
					</div>
				<?php else : ?>
					<div class="pgh-hero__art" aria-hidden="true">
						<div class="pgh-hero__blob"></div>
						<img class="pgh-hero__mascot" src="<?php echo esc_url( PGH_URI . '/assets/img/site-icon-512.png' ); ?>" alt="PawGadgetHub Mascot" />
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="pgh-trustbar" aria-label="<?php esc_attr_e( 'Editorial standards', 'pawgadgethub' ); ?>">
		<div class="container pgh-trustbar__inner">
			<div class="pgh-trustbar__item"><strong><?php echo esc_html( max( 16, $total_posts ) ); ?>+</strong> <span><?php esc_html_e( 'In-Depth Pet Tech Guides', 'pawgadgethub' ); ?></span></div>
			<div class="pgh-trustbar__item"><strong>100%</strong> <span><?php esc_html_e( 'Independent & Unsponsored', 'pawgadgethub' ); ?></span></div>
			<div class="pgh-trustbar__item"><strong>Vet-Safe</strong> <span><?php esc_html_e( 'AAFCO & AVMA Referenced', 'pawgadgethub' ); ?></span></div>
			<div class="pgh-trustbar__item"><strong>2026</strong> <span><?php esc_html_e( 'Live US Pricing & Specs', 'pawgadgethub' ); ?></span></div>
		</div>
	</section>

	<section class="pgh-section" id="categories" aria-labelledby="cats-title">
		<div class="container">
			<div class="pgh-section__head">
				<p class="pgh-eyebrow"><?php esc_html_e( '8 Smart Pet Niches', 'pawgadgethub' ); ?></p>
				<h2 class="pgh-section__title" id="cats-title"><?php esc_html_e( 'Shop smarter by category', 'pawgadgethub' ); ?></h2>
			</div>
			<div class="pgh-catgrid pgh-catgrid--8">
				<?php foreach ( pgh_categories() as $slug => $cat ) : ?>
					<?php $tone = isset( $cat['tone'] ) ? $cat['tone'] : 'teal'; ?>
					<a class="pgh-catcard pgh-catcard--<?php echo esc_attr( $tone ); ?>" href="<?php echo esc_url( pgh_category_url( $slug ) ); ?>">
						<span class="pgh-catcard__icon"><?php echo pgh_icon( $cat['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<h3><?php echo esc_html( $cat['label'] ); ?></h3>
						<p><?php echo esc_html( $cat['blurb'] ); ?></p>
						<span class="pgh-catcard__more"><?php esc_html_e( 'Explore', 'pawgadgethub' ); ?> <?php echo pgh_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="pgh-section pgh-section--warm" id="reviews" aria-labelledby="featured-title">
		<div class="container">
			<div class="pgh-reviews-header">
				<div>
					<p class="pgh-eyebrow"><?php esc_html_e( 'Hands-On Picks', 'pawgadgethub' ); ?></p>
					<h2 class="pgh-section__title pgh-section__title--left" id="featured-title"><?php esc_html_e( 'Latest reviews & buying guides', 'pawgadgethub' ); ?></h2>
				</div>
				<div class="pgh-pet-tabs" role="group" aria-label="<?php esc_attr_e( 'Filter guides by pet', 'pawgadgethub' ); ?>">
					<button type="button" class="pgh-pet-tab is-active" data-filter="all" aria-pressed="true"><?php esc_html_e( 'All Pets', 'pawgadgethub' ); ?></button>
					<button type="button" class="pgh-pet-tab" data-filter="dog" aria-pressed="false">&#128054; <?php esc_html_e( 'Dogs', 'pawgadgethub' ); ?></button>
					<button type="button" class="pgh-pet-tab" data-filter="cat" aria-pressed="false">&#128049; <?php esc_html_e( 'Cats', 'pawgadgethub' ); ?></button>
				</div>
			</div>

			<?php if ( $featured && $featured->have_posts() ) : ?>
				<div class="pgh-grid pgh-grid--3">
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
				<p class="pgh-notice"><?php esc_html_e( 'Admin notice: no posts yet. Publish your first review and it will appear here.', 'pawgadgethub' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="pgh-section" aria-labelledby="trust-title">
		<div class="container">
			<div class="pgh-section__head">
				<p class="pgh-eyebrow"><?php esc_html_e( 'Our Promise', 'pawgadgethub' ); ?></p>
				<h2 class="pgh-section__title" id="trust-title"><?php esc_html_e( 'Why pet parents trust PawGadgetHub', 'pawgadgethub' ); ?></h2>
			</div>
			<div class="pgh-pillars">
				<div class="pgh-pillar">
					<span class="pgh-pillar__icon"><?php echo pgh_icon( 'flask' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3><?php esc_html_e( 'Real-world testing', 'pawgadgethub' ); ?></h3>
					<p><?php esc_html_e( 'We evaluate app reliability, multi-pet interference, and real battery life — not just marketing claims.', 'pawgadgethub' ); ?></p>
				</div>
				<div class="pgh-pillar">
					<span class="pgh-pillar__icon"><?php echo pgh_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3><?php esc_html_e( 'Pet safety first', 'pawgadgethub' ); ?></h3>
					<p><?php esc_html_e( 'We prioritize BPA-free, food-grade SUS304 stainless steel and vet-backed safety guidelines.', 'pawgadgethub' ); ?></p>
				</div>
				<div class="pgh-pillar">
					<span class="pgh-pillar__icon"><?php echo pgh_icon( 'scale' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3><?php esc_html_e( 'Honest drawbacks', 'pawgadgethub' ); ?></h3>
					<p><?php esc_html_e( 'Every review highlights subscription costs and real flaws so you never get hit with surprise fees.', 'pawgadgethub' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<?php pgh_render_newsletter(); ?>

</main>
<?php
get_footer();
