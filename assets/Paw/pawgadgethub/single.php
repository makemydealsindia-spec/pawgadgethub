<?php
/**
 * Single post (review / buying guide).
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="content" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'pgh-article' ); ?>>
			<div class="container container--narrow">
				<?php pgh_breadcrumbs(); ?>

				<header class="pgh-article__head">
					<?php pgh_category_chip(); ?>
					<h1 class="pgh-article__title"><?php the_title(); ?></h1>
					<div class="pgh-article__meta">
						<span><img class="pgh-byline-avatar" src="<?php echo esc_url( PGH_URI . '/assets/img/site-icon-512.png' ); ?>" alt="" width="28" height="28" /> <?php the_author(); ?></span>
						<span><?php echo pgh_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php
							/* translators: %s: date */
							printf( esc_html__( 'Updated %s', 'pawgadgethub' ), '<time datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( get_the_modified_date() ) . '</time>' ); // phpcs:ignore WordPress.Security.EscapeOutput
							?>
						</span>
						<span>
							<?php
							/* translators: %d: minutes */
							printf( esc_html__( '%d min read', 'pawgadgethub' ), (int) pgh_reading_time() );
							?>
						</span>
					</div>
				</header>

				<?php pgh_share_bar( 'top' ); ?>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="container container--wide">
					<figure class="pgh-article__image">
						<?php the_post_thumbnail( 'pgh-wide', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					</figure>
				</div>
			<?php endif; ?>

			<div class="container container--narrow">
				<div class="entry-content">
					<?php the_content(); ?>
				</div>

				<?php
				wp_link_pages(
					array(
						'before' => '<nav class="pgh-page-links">' . esc_html__( 'Pages:', 'pawgadgethub' ),
						'after'  => '</nav>',
					)
				);
				?>

				<?php if ( get_the_tags() ) : ?>
					<div class="pgh-tags"><?php the_tags( '', '', '' ); ?></div>
				<?php endif; ?>

				<?php pgh_share_bar( 'bottom' ); ?>

				<aside class="pgh-authorbox">
					<div class="pgh-authorbox__avatar">
						<img src="<?php echo esc_url( PGH_URI . '/assets/img/site-icon-512.png' ); ?>" alt="<?php echo esc_attr( get_the_author() ); ?>" width="56" height="56" />
					</div>
					<div>
						<p class="pgh-authorbox__name"><?php echo esc_html( get_the_author() ); ?></p>
						<p>
							<?php
							$bio = get_the_author_meta( 'description' );
							echo esc_html( $bio ? $bio : __( 'Part of the independent PawGadgetHub team of pet parents and tech fans who research and test smart gadgets for pets.', 'pawgadgethub' ) );
							?>
						</p>
					</div>
				</aside>

				<?php pgh_disclosure_banner(); ?>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</article>

		<?php pgh_related_posts(); ?>

	<?php endwhile; ?>
</main>
<?php
get_footer();
