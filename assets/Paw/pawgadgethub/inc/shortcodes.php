<?php
/**
 * Affiliate shortcodes: product box, comparison table, pros/cons, CTA button.
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Star rating (CSS-filled, accessible).
 *
 * @param float|string $rating 0-5.
 * @return string
 */
function pgh_stars_html( $rating ) {
	$r   = max( 0, min( 5, (float) $rating ) );
	$pct = round( $r / 5 * 100 );
	return '<span class="pgh-stars" style="--pgh-rating:' . (int) $pct . '%" role="img" aria-label="' . esc_attr(
		sprintf(
			/* translators: %s: rating number */
			__( 'Rated %s out of 5', 'pawgadgethub' ),
			number_format_i18n( $r, 1 )
		)
	) . '"></span>';
}

/**
 * Split "a|b|c" into a clean array.
 *
 * @param string $str Pipe-separated list.
 * @return array
 */
function pgh_split_list( $str ) {
	return array_values( array_filter( array_map( 'trim', explode( '|', (string) $str ) ), 'strlen' ) );
}

/**
 * Default button label for a URL.
 *
 * @param string $url Link.
 * @return string
 */
function pgh_default_button_label( $url ) {
	$host = (string) wp_parse_url( $url, PHP_URL_HOST );
	if ( false !== stripos( $host, 'amazon.' ) || false !== stripos( $host, 'amzn.' ) || 'a.co' === $host ) {
		return __( 'Check Price on Amazon', 'pawgadgethub' );
	}
	return __( 'Check Price', 'pawgadgethub' );
}

/**
 * Affiliate link attributes.
 *
 * @return string
 */
function pgh_link_attrs() {
	return ' target="_blank" rel="sponsored nofollow noopener"';
}

/**
 * [pgh_product title="" url="" image="" badge="Top Pick" rank="1" rating="4.8" price="$99" pros="a|b" cons="a|b" button=""]Summary[/pgh_product]
 */
function pgh_sc_product( $atts, $content = '' ) {
	$a = shortcode_atts(
		array(
			'title'  => '',
			'url'    => '',
			'image'  => '',
			'badge'  => '',
			'rank'   => '',
			'rating' => '',
			'price'  => '',
			'pros'   => '',
			'cons'   => '',
			'button' => '',
			'tag'    => 'h3',
		),
		$atts,
		'pgh_product'
	);

	$tag = in_array( $a['tag'], array( 'h2', 'h3', 'h4' ), true ) ? $a['tag'] : 'h3';
	$url = esc_url( html_entity_decode( $a['url'], ENT_QUOTES ) );

	// Image: attachment ID or URL.
	$img = '';
	if ( is_numeric( $a['image'] ) && (int) $a['image'] > 0 ) {
		$img = wp_get_attachment_image( (int) $a['image'], 'pgh-card', false, array( 'loading' => 'lazy', 'alt' => $a['title'] ) );
	} elseif ( '' !== $a['image'] ) {
		$img = '<img src="' . esc_url( $a['image'] ) . '" alt="' . esc_attr( $a['title'] ) . '" loading="lazy" decoding="async" width="720" height="450">';
	}
	if ( '' === $img ) {
		$img = '<span class="pgh-product__ph">' . pgh_paw_svg() . '</span>';
	}

	$pros = pgh_split_list( $a['pros'] );
	$cons = pgh_split_list( $a['cons'] );

	ob_start();
	?>
	<div class="pgh-product">
		<div class="pgh-product__media">
			<?php if ( '' !== $a['rank'] ) : ?>
				<span class="pgh-product__rank" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: rank */ __( 'Number %s', 'pawgadgethub' ), $a['rank'] ) ); ?>"><?php echo esc_html( '#' . $a['rank'] ); ?></span>
			<?php endif; ?>
			<?php if ( '' !== $a['badge'] ) : ?>
				<span class="pgh-badge"><?php echo esc_html( $a['badge'] ); ?></span>
			<?php endif; ?>
			<?php echo $url ? '<a href="' . $url . '"' . pgh_link_attrs() . ' tabindex="-1" aria-hidden="true">' . $img . '</a>' : $img; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<div class="pgh-product__body">
			<?php if ( '' !== $a['title'] ) : ?>
				<<?php echo esc_html( $tag ); ?> class="pgh-product__title"><?php echo esc_html( $a['title'] ); ?></<?php echo esc_html( $tag ); ?>>
			<?php endif; ?>
			<?php if ( '' !== $a['rating'] || '' !== $a['price'] ) : ?>
				<div class="pgh-product__meta">
					<?php if ( '' !== $a['rating'] ) : ?>
						<?php echo pgh_stars_html( $a['rating'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span class="pgh-product__score"><?php echo esc_html( number_format_i18n( (float) $a['rating'], 1 ) ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== $a['price'] ) : ?>
						<span class="pgh-product__price"><?php echo esc_html( $a['price'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( '' !== trim( $content ) ) : ?>
				<div class="pgh-product__summary"><?php echo wp_kses_post( do_shortcode( $content ) ); ?></div>
			<?php endif; ?>
			<?php if ( $pros || $cons ) : ?>
				<div class="pgh-proscons">
					<?php if ( $pros ) : ?>
						<div class="pgh-proscons__col pgh-proscons__col--pros">
							<strong><?php esc_html_e( 'Pros', 'pawgadgethub' ); ?></strong>
							<ul><?php foreach ( $pros as $p ) : ?><li><?php echo esc_html( $p ); ?></li><?php endforeach; ?></ul>
						</div>
					<?php endif; ?>
					<?php if ( $cons ) : ?>
						<div class="pgh-proscons__col pgh-proscons__col--cons">
							<strong><?php esc_html_e( 'Cons', 'pawgadgethub' ); ?></strong>
							<ul><?php foreach ( $cons as $c ) : ?><li><?php echo esc_html( $c ); ?></li><?php endforeach; ?></ul>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( $url ) : ?>
				<div class="pgh-product__actions">
					<a class="pgh-btn" href="<?php echo $url; // phpcs:ignore WordPress.Security.EscapeOutput ?>"<?php echo pgh_link_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
						<?php echo esc_html( '' !== $a['button'] ? $a['button'] : pgh_default_button_label( $url ) ); ?>
						<?php echo pgh_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'pgh_product', 'pgh_sc_product' );

/**
 * [pgh_proscons pros="a|b" cons="a|b"]
 */
function pgh_sc_proscons( $atts ) {
	$a    = shortcode_atts( array( 'pros' => '', 'cons' => '' ), $atts, 'pgh_proscons' );
	$pros = pgh_split_list( $a['pros'] );
	$cons = pgh_split_list( $a['cons'] );
	if ( ! $pros && ! $cons ) {
		return '';
	}
	ob_start();
	?>
	<div class="pgh-proscons pgh-proscons--standalone">
		<?php if ( $pros ) : ?>
			<div class="pgh-proscons__col pgh-proscons__col--pros">
				<strong><?php esc_html_e( 'Pros', 'pawgadgethub' ); ?></strong>
				<ul><?php foreach ( $pros as $p ) : ?><li><?php echo esc_html( $p ); ?></li><?php endforeach; ?></ul>
			</div>
		<?php endif; ?>
		<?php if ( $cons ) : ?>
			<div class="pgh-proscons__col pgh-proscons__col--cons">
				<strong><?php esc_html_e( 'Cons', 'pawgadgethub' ); ?></strong>
				<ul><?php foreach ( $cons as $c ) : ?><li><?php echo esc_html( $c ); ?></li><?php endforeach; ?></ul>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'pgh_proscons', 'pgh_sc_proscons' );

/**
 * [pgh_cta url=""]Button text[/pgh_cta]
 */
function pgh_sc_cta( $atts, $content = '' ) {
	$a   = shortcode_atts( array( 'url' => '' ), $atts, 'pgh_cta' );
	$url = esc_url( html_entity_decode( $a['url'], ENT_QUOTES ) );
	if ( ! $url ) {
		return '';
	}
	$label = '' !== trim( $content ) ? wp_strip_all_tags( $content ) : pgh_default_button_label( $url );
	return '<p class="pgh-cta"><a class="pgh-btn" href="' . $url . '"' . pgh_link_attrs() . '>' . esc_html( $label ) . pgh_icon( 'arrow' ) . '</a></p>';
}
add_shortcode( 'pgh_cta', 'pgh_sc_cta' );

/**
 * Comparison table.
 *
 * [pgh_compare headers="Product|Price|Battery|Rating|Buy"]
 * *Petlibro Granary|$99|Wi-Fi|4.8|Check Price::https://...
 * Other Feeder|$79|Basic|4.4|Check Price::https://...
 * [/pgh_compare]
 *
 * One row per line. Start a line with * to highlight it as the top pick.
 * A cell written as  Text::URL  becomes a button.
 */
function pgh_sc_compare( $atts, $content = '' ) {
	$a = shortcode_atts( array( 'headers' => '', 'caption' => '' ), $atts, 'pgh_compare' );

	// The editor wraps lines in <p>/<br>; turn that back into plain lines.
	$content = preg_replace( '#<br\s*/?>#i', "\n", (string) $content );
	$content = preg_replace( '#</?p[^>]*>#i', "\n", $content );
	$content = str_replace( array( '&nbsp;', "\xC2\xA0" ), ' ', wp_strip_all_tags( $content ) );

	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', $content ) as $line ) {
		$line = trim( $line );
		if ( '' !== $line ) {
			$rows[] = $line;
		}
	}

	$headers = '' !== trim( $a['headers'] ) ? array_map( 'trim', explode( '|', $a['headers'] ) ) : array();
	if ( ! $headers && $rows ) {
		$headers = array_map( 'trim', explode( '|', array_shift( $rows ) ) );
	}
	if ( ! $headers || ! $rows ) {
		return '';
	}

	ob_start();
	?>
	<div class="pgh-compare" role="region" aria-label="<?php echo esc_attr( '' !== $a['caption'] ? $a['caption'] : __( 'Comparison table', 'pawgadgethub' ) ); ?>" tabindex="0">
		<table class="pgh-compare__table">
			<?php if ( '' !== $a['caption'] ) : ?>
				<caption class="screen-reader-text"><?php echo esc_html( $a['caption'] ); ?></caption>
			<?php endif; ?>
			<thead>
				<tr>
					<?php foreach ( $headers as $h ) : ?>
						<th scope="col"><?php echo esc_html( $h ); ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $rows as $row ) :
					$top = false;
					if ( 0 === strpos( $row, '*' ) ) {
						$top = true;
						$row = ltrim( substr( $row, 1 ) );
					}
					$cells = array_map( 'trim', explode( '|', $row ) );
					?>
					<tr<?php echo $top ? ' class="is-top"' : ''; ?>>
						<?php
						foreach ( $cells as $i => $cell ) :
							$label = isset( $headers[ $i ] ) ? $headers[ $i ] : '';
							$inner = '';
							if ( false !== strpos( $cell, '::' ) ) {
								list( $text, $link ) = array_map( 'trim', explode( '::', $cell, 2 ) );
								$link                = esc_url( html_entity_decode( $link, ENT_QUOTES ) );
								$inner               = $link ? '<a class="pgh-btn pgh-btn--sm" href="' . $link . '"' . pgh_link_attrs() . '>' . esc_html( $text ) . '</a>' : esc_html( $text );
							} else {
								$inner = esc_html( $cell );
							}
							if ( 0 === $i ) :
								?>
								<th scope="row">
									<?php
									echo $inner; // phpcs:ignore WordPress.Security.EscapeOutput
									if ( $top ) {
										echo ' <span class="pgh-badge pgh-badge--sm">' . esc_html__( 'Top Pick', 'pawgadgethub' ) . '</span>';
									}
									?>
								</th>
							<?php else : ?>
								<td data-label="<?php echo esc_attr( $label ); ?>"><?php echo $inner; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
								<?php
							endif;
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'pgh_compare', 'pgh_sc_compare' );
