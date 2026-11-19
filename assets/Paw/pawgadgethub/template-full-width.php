<?php
/**
 * Template Name: Full width (no title, for landing pages)
 * Template Post Type: page
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
		<div class="container">
			<div class="entry-content entry-content--full">
				<?php the_content(); ?>
			</div>
		</div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
