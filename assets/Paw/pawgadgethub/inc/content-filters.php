<?php
/**
 * Content filters: automatic table of contents and affiliate link attributes.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add a table of contents before the first H2/H3 in single posts.
 *
 * @param string $content Post content.
 * @return string
 */
function pgh_toc_filter( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	if ( ! pgh_opt( 'show_toc' ) || get_post_meta( get_the_ID(), '_pgh_hide_toc', true ) ) {
		return $content;
	}

	$original = $content;
	$items    = array();
	$used     = array();

	$content = preg_replace_callback(
		'/<h(2)([^>]*)>(.*?)<\/h\1>/is',
		function ( $m ) use ( &$items, &$used ) {
			$level = (int) $m[1];
			$attrs = $m[2];
			$inner = $m[3];
			$text  = trim( wp_strip_all_tags( $inner ) );
			if ( '' === $text ) {
				return $m[0];
			}
			if ( preg_match( '/\sid=(["\'])(.*?)\1/i', $attrs, $idm ) ) {
				$id = $idm[2];
			} else {
				$base = sanitize_title( $text );
				$base = '' !== $base ? $base : 'section';
				$id   = $base;
				$n    = 2;
				while ( isset( $used[ $id ] ) ) {
					$id = $base . '-' . $n++;
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;
			$items[]     = array(
				'level' => $level,
				'id'    => $id,
				'text'  => $text,
			);
			return '<h' . $level . $attrs . '>' . $inner . '</h' . $level . '>';
		},
		$content
	);

	if ( null === $content || count( $items ) < (int) pgh_opt( 'toc_min' ) ) {
		return $original;
	}

	$html  = '<nav class="pgh-toc" aria-label="' . esc_attr__( 'Table of contents', 'pawgadgethub' ) . '">';
	$html .= '<details><summary>' . sprintf( esc_html__( 'In this guide (%d sections)', 'pawgadgethub' ), count( $items ) ) . '</summary><ol>';
	foreach ( $items as $item ) {
		$html .= '<li class="pgh-toc__item pgh-toc__item--h' . (int) $item['level'] . '"><a href="#' . esc_attr( $item['id'] ) . '">' . esc_html( $item['text'] ) . '</a></li>';
	}
	$html .= '</ol></details></nav>';

	if ( preg_match( '/<h2[\s>]/i', $content, $mm, PREG_OFFSET_CAPTURE ) ) {
		$pos     = $mm[0][1];
		$content = substr( $content, 0, $pos ) . $html . substr( $content, $pos );
	} else {
		$content = $html . $content;
	}

	// Wrap tables for clean mobile horizontal scrolling & strip any em-dashes/en-dashes.
	$content = preg_replace( '/(<table\b[^>]*>.*?<\/table>)/is', '<div class="pgh-table-wrap">$1</div>', $content );
	$content = str_replace( array( '—', '–', '&mdash;', '&ndash;', '&#8212;', '&#8211;' ), array( ': ', '-', ': ', '-', ': ', '-' ), $content );

	return $content;
}
add_filter( 'the_content', 'pgh_toc_filter', 15 );

/**
 * Ensure titles also have zero em-dashes or en-dashes.
 */
function pgh_clean_dashes_title( $title ) {
	return str_replace( array( '—', '–', '&mdash;', '&ndash;', '&#8212;', '&#8211;' ), array( ': ', '-', ': ', '-', ': ', '-' ), $title );
}
add_filter( 'the_title', 'pgh_clean_dashes_title', 20 );

/**
 * Domains treated as affiliate links (they get rel="sponsored nofollow noopener" and open in a new tab).
 * Add more with the 'pgh_affiliate_domains' filter.
 *
 * @return array
 */
function pgh_affiliate_domains() {
	return apply_filters(
		'pgh_affiliate_domains',
		array(
			'amazon.com',
			'amzn.to',
			'amzn.com',
			'a.co',
			'tractive.com',
			'furbo.com',
			'petlibro.com',
			'whisker.com',
			'litter-robot.com',
			'chewy.com',
			'shareasale.com',
			'shrsl.com',
			'awin1.com',
			'sjv.io',
		)
	);
}

/**
 * Is this URL on an affiliate domain?
 *
 * @param string $url URL.
 * @return bool
 */
function pgh_is_affiliate_url( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ! $host ) {
		return false;
	}
	$host = strtolower( preg_replace( '/^www\./', '', $host ) );
	foreach ( pgh_affiliate_domains() as $domain ) {
		if ( $host === $domain || substr( $host, -( strlen( $domain ) + 1 ) ) === '.' . $domain ) {
			return true;
		}
	}
	return false;
}

/**
 * Mark affiliate links in post content.
 *
 * @param string $content Post content.
 * @return string
 */
function pgh_mark_affiliate_links( $content ) {
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) || false === stripos( $content, '<a ' ) ) {
		return $content;
	}
	$p = new WP_HTML_Tag_Processor( $content );
	while ( $p->next_tag( 'a' ) ) {
		$href = $p->get_attribute( 'href' );
		if ( is_string( $href ) && pgh_is_affiliate_url( $href ) ) {
			$p->set_attribute( 'rel', 'sponsored nofollow noopener' );
			$p->set_attribute( 'target', '_blank' );
		}
	}
	return $p->get_updated_html();
}
add_filter( 'the_content', 'pgh_mark_affiliate_links', 20 );
