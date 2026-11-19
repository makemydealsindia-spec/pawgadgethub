<?php
/**
 * Static pages (About, Contact, legal pages...).
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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'pgh-article pgh-article--page' ); ?>>
			<div class="container container--narrow">
				<?php pgh_breadcrumbs(); ?>
				<header class="pgh-article__head">
					<h1 class="pgh-article__title"><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<?php
				if ( comments_open() ) {
					comments_template();
				}
				?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
