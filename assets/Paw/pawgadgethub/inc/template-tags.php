<?php
/**
 * Template helpers: icons, logo, categories, breadcrumbs, cards.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The five content silos from the site blueprint.
 *
 * @return array slug => [ label, icon, blurb ]
 */
function pgh_categories() {
	return array(
		'smart-feeders'       => array(
			'label' => __( 'Smart Feeders', 'pawgadgethub' ),
			'icon'  => 'feeder',
			'tone'  => 'teal',
			'blurb' => __( 'Schedule meals, control portions, and end 5 AM wake-ups.', 'pawgadgethub' ),
		),
		'gps-trackers'        => array(
			'label' => __( 'GPS & Trackers', 'pawgadgethub' ),
			'icon'  => 'gps',
			'tone'  => 'orange',
			'blurb' => __( 'Keep escape artists and trail dogs safe, wherever they roam.', 'pawgadgethub' ),
		),
		'pet-cameras'         => array(
			'label' => __( 'Pet Cameras', 'pawgadgethub' ),
			'icon'  => 'camera',
			'tone'  => 'amber',
			'blurb' => __( 'Check in, toss a treat, and ease separation anxiety.', 'pawgadgethub' ),
		),
		'litter-boxes'        => array(
			'label' => __( 'Self-Cleaning Litter', 'pawgadgethub' ),
			'icon'  => 'litter',
			'tone'  => 'teal',
			'blurb' => __( 'Skip the scooping and keep apartment odor under control.', 'pawgadgethub' ),
		),
		'water-fountains'     => array(
			'label' => __( 'Water Fountains', 'pawgadgethub' ),
			'icon'  => 'fountain',
			'tone'  => 'sky',
			'blurb' => __( 'Fresh, filtered, whisper-quiet water that pets love.', 'pawgadgethub' ),
		),
		'wireless-dog-fences' => array(
			'label' => __( 'Wireless Dog Fences', 'pawgadgethub' ),
			'icon'  => 'fence',
			'tone'  => 'orange',
			'blurb' => __( 'GPS and radar yard boundaries with zero buried wires.', 'pawgadgethub' ),
		),
		'pet-home-air-care'   => array(
			'label' => __( 'Pet Home & Air Care', 'pawgadgethub' ),
			'icon'  => 'air',
			'tone'  => 'sky',
			'blurb' => __( 'HEPA hair purifiers, heated cat beds, and paw washers.', 'pawgadgethub' ),
		),
		'smart-pet-toys'      => array(
			'label' => __( 'Smart Toys & Backyard', 'pawgadgethub' ),
			'icon'  => 'toy',
			'tone'  => 'amber',
			'blurb' => __( 'Automatic ball launchers and AI bird feeder cameras.', 'pawgadgethub' ),
		),
	);
}

/**
 * URL for one of the five silos (uses the real category if it exists).
 *
 * @param string $slug Category slug.
 * @return string
 */
function pgh_category_url( $slug ) {
	$term = get_category_by_slug( $slug );
	if ( $term && ! is_wp_error( $term ) ) {
		$link = get_category_link( $term );
		if ( ! is_wp_error( $link ) ) {
			return $link;
		}
	}
	return home_url( '/category/' . $slug . '/' );
}

/**
 * Inline SVG icon (stroke based, inherits currentColor).
 *
 * @param string $name Icon name.
 * @return string
 */
function pgh_icon( $name ) {
	$paths = array(
		'feeder'   => '<path d="M3 12h18c0 4.4-3.6 8-8 8h-2c-4.4 0-8-3.6-8-8z"/><path d="M8 8.2h.01M12 6.2h.01M16 8.2h.01"/>',
		'gps'      => '<path d="M12 21s-6-5.2-6-10a6 6 0 1 1 12 0c0 4.8-6 10-6 10z"/><circle cx="12" cy="11" r="2.2"/>',
		'camera'   => '<rect x="3" y="7" width="18" height="12" rx="3"/><circle cx="12" cy="13" r="3.2"/><path d="M8 7l1.2-2h5.6L16 7"/>',
		'litter'   => '<path d="M4 20h16M6 20v-5a6 6 0 0 1 12 0v5"/><path d="M10 20v-3a2 2 0 0 1 4 0v3"/>',
		'fountain' => '<path d="M12 3s6 6.2 6 10.5a6 6 0 0 1-12 0C6 9.2 12 3 12 3z"/><path d="M9.5 14a2.6 2.6 0 0 0 2.5 2.5"/>',
		'fence'    => '<path d="M4 20V8l2-3 2 3v12M10 20V8l2-3 2 3v12M16 20V8l2-3 2 3v12M3 12h18M3 17h18"/>',
		'air'      => '<path d="M3 8h11a3 3 0 1 0-3-3M3 12h15a3 3 0 1 1-3 3M3 16h8a2.5 2.5 0 1 1-2.5 2.5"/>',
		'toy'      => '<circle cx="12" cy="12" r="8"/><path d="M8.5 5.2a9 9 0 0 1 7 13.6M15.5 5.2a9 9 0 0 0-7 13.6"/>',
		'search'   => '<circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/>',
		'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'    => '<path d="M6 6l12 12M18 6L6 18"/>',
		'check'    => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
		'flask'    => '<path d="M9 3h6M10 3v6l-5.2 9.2A2 2 0 0 0 6.5 21h11a2 2 0 0 0 1.7-2.8L14 9V3"/><path d="M7.5 15h9"/>',
		'shield'   => '<path d="M12 3l7 3v5.5c0 4.4-3 7.6-7 9.5-4-1.9-7-5.1-7-9.5V6z"/><path d="M9 12l2.2 2.2L15.5 10"/>',
		'scale'    => '<path d="M12 4v16M6 20h12M5 8h14"/><path d="M7.5 8l-3 7a3 3 0 0 0 6 0zM16.5 8l-3 7a3 3 0 0 0 6 0z"/>',
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M4 7.5l8 6 8-6"/>',
		'clock'    => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'info'     => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg class="pgh-icon pgh-icon--' . esc_attr( $name ) . '" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

/**
 * The PawGadgetHub paw mark (colors are set in CSS so it works on light and dark).
 *
 * @param string $class Extra CSS class.
 * @return string
 */
function pgh_paw_svg( $class = '' ) {
	return '<svg class="pgh-paw ' . esc_attr( $class ) . '" viewBox="0 0 64 64" aria-hidden="true" focusable="false">'
		. '<g class="pgh-paw__toes">'
		. '<ellipse cx="13.5" cy="29.5" rx="6" ry="8" transform="rotate(-22 13.5 29.5)"/>'
		. '<ellipse cx="25.5" cy="16" rx="6.4" ry="8.8" transform="rotate(-8 25.5 16)"/>'
		. '<ellipse cx="38.5" cy="16" rx="6.4" ry="8.8" transform="rotate(8 38.5 16)"/>'
		. '<ellipse cx="50.5" cy="29.5" rx="6" ry="8" transform="rotate(22 50.5 29.5)"/>'
		. '</g>'
		. '<path class="pgh-paw__pad" d="M32 29.5c9.8 0 18 9.2 18 17.3 0 6.6-5.2 9.8-10.4 8.7-2.8-.6-5-1.3-7.6-1.3s-4.8.7-7.6 1.3C19.2 56.6 14 53.4 14 46.8c0-8.1 8.2-17.3 18-17.3z"/>'
		. '<circle class="pgh-paw__dot" cx="32" cy="47.5" r="3.6"/>'
		. '</svg>';
}

/**
 * Site logo: uploaded logo if set, otherwise the built-in paw + wordmark.
 */
function pgh_site_logo( $reversed = true ) {
	printf(
		'<a class="site-logo" href="%1$s" rel="home" aria-label="%2$s"><img class="site-logo__mark-img" src="%3$s" alt="" width="44" height="44" /><span class="site-logo__text">PawGadget<em>Hub</em></span></a>',
		esc_url( home_url( '/' ) ),
		esc_attr( get_bloginfo( 'name' ) ),
		esc_url( PGH_URI . '/assets/img/site-icon-512.png' )
	);
}

/**
 * Prominent social share bar for single articles.
 */
function pgh_share_bar( $position = 'top' ) {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( wp_strip_all_tags( get_the_title() ) );
	$img   = rawurlencode( (string) get_the_post_thumbnail_url( get_the_ID(), 'large' ) );

	$svg_copy = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 1H4a2 2 0 0 0-2 2v14h2V3h12V1zm3 4H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2zm0 16H8V7h11v14z"/></svg>';
	$svg_wa   = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2a9.9 9.9 0 0 0-8.5 15l-1.1 4.1 4.2-1.1a9.9 9.9 0 0 0 5.4 1.6h.01A9.93 9.93 0 0 0 22 11.7 9.93 9.93 0 0 0 12.04 2zm5.8 14.1c-.24.68-1.4 1.3-1.95 1.38-.52.08-1.18.11-1.9-.12-.44-.14-1-.33-1.72-.64-3.03-1.31-5-4.38-5.15-4.58-.15-.2-1.23-1.64-1.23-3.13 0-1.49.78-2.22 1.06-2.52.28-.3.61-.38.81-.38h.58c.19 0 .44-.07.69.52.24.6.83 2.04.9 2.19.08.15.13.33.03.53-.1.2-.15.33-.3.5-.15.18-.32.4-.45.53-.15.15-.31.32-.13.62.18.3.8 1.32 1.72 2.14 1.18 1.05 2.18 1.38 2.48 1.53.3.15.48.13.66-.08.18-.2.76-.88.96-1.18.2-.3.4-.25.68-.15.28.1 1.77.83 2.07.98.3.15.5.23.58.36.08.13.08.76-.16 1.44z"/></svg>';
	$svg_x    = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.24 2.25h3.31l-7.23 8.26L22.82 21.75h-6.66l-5.21-6.82-5.97 6.82H1.67l7.73-8.84L1.25 2.25h6.83l4.71 6.23 5.45-6.23zm-1.16 17.52h1.83L7.08 4.13H5.12l11.96 15.64z"/></svg>';
	$svg_fb   = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.33-.04-1.56-.14-2.86-.14-2.72 0-4.64 1.66-4.64 4.7v2.8H7v4h3V22h4v-8.5z"/></svg>';
	$svg_pin  = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-3.65 19.31c-.05-.79-.1-2 .02-2.86l1.2-5.08s-.3-.61-.3-1.51c0-1.42.82-2.48 1.85-2.48.87 0 1.29.65 1.29 1.44 0 .88-.56 2.19-.85 3.4-.24 1.02.51 1.85 1.52 1.85 1.82 0 3.22-1.92 3.22-4.69 0-2.45-1.76-4.17-4.28-4.17-2.92 0-4.63 2.19-4.63 4.45 0 .88.34 1.83.76 2.34.08.1.1.19.07.29l-.28 1.15c-.04.18-.15.22-.34.13-1.26-.59-2.05-2.43-2.05-3.91 0-3.18 2.31-6.11 6.67-6.11 3.5 0 6.22 2.49 6.22 5.83 0 3.48-2.19 6.28-5.24 6.28-1.02 0-1.99-.53-2.32-1.16l-.63 2.4c-.23.88-.85 1.98-1.26 2.65A10 10 0 1 0 12 2z"/></svg>';
	?>
	<div class="pgh-share pgh-share--<?php echo esc_attr( $position ); ?>" aria-label="<?php esc_attr_e( 'Share this guide', 'pawgadgethub' ); ?>">
		<span class="pgh-share__label"><?php esc_html_e( 'Share:', 'pawgadgethub' ); ?></span>
		<div class="pgh-share__btns">
			<button type="button" class="pgh-share__btn pgh-share__btn--copy js-copy-link" data-url="<?php echo esc_url( get_permalink() ); ?>">
				<?php echo $svg_copy; // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php esc_html_e( 'Copy Link', 'pawgadgethub' ); ?></span>
			</button>
			<a class="pgh-share__btn pgh-share__btn--wa" href="https://api.whatsapp.com/send?text=<?php echo esc_attr( $title . '%20' . $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo $svg_wa; // phpcs:ignore WordPress.Security.EscapeOutput ?><span>WhatsApp</span></a>
			<a class="pgh-share__btn pgh-share__btn--x" href="https://twitter.com/intent/tweet?text=<?php echo esc_attr( $title ); ?>&amp;url=<?php echo esc_attr( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on X"><?php echo $svg_x; // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Post</span></a>
			<a class="pgh-share__btn pgh-share__btn--fb" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo $svg_fb; // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Facebook</span></a>
			<a class="pgh-share__btn pgh-share__btn--pin" href="https://pinterest.com/pin/create/button/?url=<?php echo esc_attr( $url ); ?>&amp;media=<?php echo esc_attr( $img ); ?>&amp;description=<?php echo esc_attr( $title ); ?>" target="_blank" rel="noopener noreferrer"><?php echo $svg_pin; // phpcs:ignore WordPress.Security.EscapeOutput ?><span>Pinterest</span></a>
		</div>
	</div>
	<?php
}

/**
 * Primary menu fallback: the five silos.
 */
function pgh_nav_fallback() {
	$cats = pgh_categories();
	$top_slugs = array( 'smart-feeders', 'gps-trackers', 'pet-cameras' );
	echo '<ul class="menu">';
	printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( home_url( '/' ) ), esc_html__( 'Home', 'pawgadgethub' ) );
	foreach ( $top_slugs as $slug ) {
		if ( isset( $cats[ $slug ] ) ) {
			printf(
				'<li class="menu-item"><a href="%s">%s</a></li>',
				esc_url( pgh_category_url( $slug ) ),
				esc_html( $cats[ $slug ]['label'] )
			);
		}
	}
	echo '<li class="menu-item menu-item-has-children"><a href="#categories">' . esc_html__( 'More Guides', 'pawgadgethub' ) . '</a><ul class="sub-menu">';
	foreach ( $cats as $slug => $cat ) {
		if ( ! in_array( $slug, $top_slugs, true ) ) {
			printf(
				'<li class="menu-item"><a href="%s">%s</a></li>',
				esc_url( pgh_category_url( $slug ) ),
				esc_html( $cat['label'] )
			);
		}
	}
	echo '</ul></li></ul>';
}

/**
 * Footer menu fallback: the five legal and trust pages.
 */
function pgh_footer_nav_fallback() {
	$pages = array(
		'about'                => __( 'About Us', 'pawgadgethub' ),
		'contact'              => __( 'Contact', 'pawgadgethub' ),
		'affiliate-disclosure' => __( 'Affiliate Disclosure', 'pawgadgethub' ),
		'privacy-policy'       => __( 'Privacy Policy', 'pawgadgethub' ),
		'terms'                => __( 'Terms of Service', 'pawgadgethub' ),
	);
	echo '<ul class="menu">';
	foreach ( $pages as $slug => $label ) {
		printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( pgh_page_url( $slug ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Permalink for a page slug, with a sensible fallback.
 *
 * @param string $slug Page slug.
 * @return string
 */
function pgh_page_url( $slug ) {
	// WordPress's own Privacy Policy page (set in Settings > Privacy) wins when it is published.
	if ( 'privacy-policy' === $slug && function_exists( 'get_privacy_policy_url' ) ) {
		$privacy = get_privacy_policy_url();
		if ( $privacy ) {
			return $privacy;
		}
	}
	$page = get_page_by_path( $slug );
	if ( $page && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}
	return home_url( '/' . $slug . '/' );
}

/**
 * First useful category as a link chip.
 */
function pgh_category_chip() {
	$cats = get_the_category();
	foreach ( $cats as $cat ) {
		if ( 'uncategorized' === $cat->slug ) {
			continue;
		}
		printf(
			'<a class="pgh-chip" href="%s">%s</a>',
			esc_url( get_category_link( $cat ) ),
			esc_html( $cat->name )
		);
		return;
	}
}

/**
 * Estimated reading time in minutes.
 *
 * @param int|null $post_id Post ID.
 * @return int
 */
function pgh_reading_time( $post_id = null ) {
	$text  = wp_strip_all_tags( get_post_field( 'post_content', $post_id ) );
	$words = str_word_count( $text );
	return max( 1, (int) ceil( $words / 220 ) );
}

/**
 * Breadcrumb trail (visual only; your SEO plugin can add the schema).
 */
function pgh_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	$items   = array();
	$items[] = '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'pawgadgethub' ) . '</a>';

	if ( is_singular( 'post' ) ) {
		foreach ( get_the_category() as $cat ) {
			if ( 'uncategorized' !== $cat->slug ) {
				$items[] = '<a href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
				break;
			}
		}
		$items[] = '<span aria-current="page">' . esc_html( wp_trim_words( get_the_title(), 8, '…' ) ) . '</span>';
	} elseif ( is_page() ) {
		$items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$items[] = '<span aria-current="page">' . esc_html( single_term_title( '', false ) ) . '</span>';
	} elseif ( is_search() ) {
		$items[] = '<span aria-current="page">' . esc_html__( 'Search', 'pawgadgethub' ) . '</span>';
	} else {
		return;
	}

	echo '<nav class="pgh-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'pawgadgethub' ) . '"><ol>';
	foreach ( $items as $item ) {
		echo '<li>' . $item . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts above.
	}
	echo '</ol></nav>';
}

/**
 * Affiliate disclosure banner shown on review posts.
 */
function pgh_disclosure_banner() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	if ( ! pgh_opt( 'show_disclosure' ) || get_post_meta( get_the_ID(), '_pgh_hide_disclosure', true ) ) {
		return;
	}
	printf(
		'<aside class="pgh-disclosure" role="note">%1$s<p>%2$s <a href="%3$s">%4$s</a></p></aside>',
		pgh_icon( 'info' ), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( pgh_opt( 'disclosure_text' ) ),
		esc_url( pgh_page_url( 'affiliate-disclosure' ) ),
		esc_html__( 'Learn more', 'pawgadgethub' )
	);
}

/**
 * Related posts (same category).
 */
function pgh_related_posts() {
	$cats = wp_get_post_categories( get_the_ID() );
	if ( empty( $cats ) ) {
		return;
	}
	$q = new WP_Query(
		array(
			'category__in'        => $cats,
			'post__not_in'        => array( get_the_ID() ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( ! $q->have_posts() ) {
		return;
	}
	echo '<section class="pgh-related"><div class="container"><h2 class="pgh-section__title">' . esc_html__( 'Keep reading', 'pawgadgethub' ) . '</h2><div class="pgh-grid pgh-grid--3">';
	while ( $q->have_posts() ) {
		$q->the_post();
		get_template_part( 'template-parts/card' );
	}
	echo '</div></div></section>';
	wp_reset_postdata();
}

/**
 * Featured posts for the homepage: posts tagged "featured", filled up with the latest posts.
 *
 * @param int $n How many.
 * @return WP_Query|null
 */
function pgh_featured_query( $n = 4 ) {
	$ids = array();

	$tagged = new WP_Query(
		array(
			'posts_per_page'      => $n,
			'tag'                 => 'featured',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
		)
	);
	$ids    = $tagged->posts;

	if ( count( $ids ) < $n ) {
		$latest = new WP_Query(
			array(
				'posts_per_page'      => $n - count( $ids ),
				'post__not_in'        => $ids,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'fields'              => 'ids',
			)
		);
		$ids    = array_merge( $ids, $latest->posts );
	}

	if ( empty( $ids ) ) {
		return null;
	}

	return new WP_Query(
		array(
			'post__in'            => $ids,
			'orderby'             => 'post__in',
			'posts_per_page'      => $n,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

/**
 * Newsletter box. Works with a pasted form action URL (Mailchimp, ConvertKit, etc.) or a plugin shortcode.
 */
function pgh_render_newsletter() {
	$shortcode = trim( (string) pgh_opt( 'newsletter_shortcode' ) );
	$action    = trim( (string) pgh_opt( 'newsletter_action' ) );
	$field     = trim( (string) pgh_opt( 'newsletter_field' ) );
	$field     = '' !== $field ? $field : 'EMAIL';

	if ( '' === $shortcode && '' === $action && ! current_user_can( 'manage_options' ) ) {
		return; // Hide the box from visitors until it is configured.
	}

	echo '<section class="pgh-newsletter" id="newsletter"><div class="container"><div class="pgh-newsletter__box">';
	echo '<div class="pgh-newsletter__copy"><span class="pgh-newsletter__icon">' . pgh_icon( 'mail' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<h2>' . esc_html( pgh_opt( 'newsletter_title' ) ) . '</h2>';
	echo '<p>' . esc_html( pgh_opt( 'newsletter_text' ) ) . '</p></div>';
	echo '<div class="pgh-newsletter__form">';

	if ( '' !== $shortcode ) {
		echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput
	} elseif ( '' !== $action ) {
		printf(
			'<form action="%1$s" method="post" target="_blank" class="pgh-nl-form" novalidate><label class="screen-reader-text" for="pgh-nl-email">%2$s</label><input type="email" id="pgh-nl-email" name="%3$s" placeholder="%4$s" required autocomplete="email"><button type="submit" class="pgh-btn">%5$s</button></form><p class="pgh-newsletter__fine">%6$s</p>',
			esc_url( $action ),
			esc_html__( 'Email address', 'pawgadgethub' ),
			esc_attr( $field ),
			esc_attr__( 'Your email address', 'pawgadgethub' ),
			esc_html__( 'Get the deals', 'pawgadgethub' ),
			esc_html__( 'No spam. Unsubscribe anytime.', 'pawgadgethub' )
		);
	} else {
		echo '<p class="pgh-notice">' . esc_html__( 'Admin notice (visitors do not see this box yet): connect your email service in Appearance > Customize > PawGadgetHub > Newsletter.', 'pawgadgethub' ) . '</p>';
	}

	echo '</div></div></div></section>';
}
