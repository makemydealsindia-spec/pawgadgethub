<?php
/**
 * Block patterns: ready-made review layouts (Add block > Patterns > PawGadgetHub).
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pgh_register_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern_category(
		'pawgadgethub',
		array( 'label' => __( 'PawGadgetHub', 'pawgadgethub' ) )
	);

	$verdict = '<!-- wp:group {"className":"is-style-pgh-verdict"} -->
<div class="wp-block-group is-style-pgh-verdict"><!-- wp:paragraph -->
<p><strong>Quick verdict:</strong> Write the one-sentence answer here. Who it is for, and why it wins.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';

	$product = '<!-- wp:shortcode -->
[pgh_product title="Product name" url="https://www.amazon.com/dp/XXXXXXXXXX?tag=YOURTAG-20" badge="Top Pick" rating="4.8" price="$99" pros="First pro|Second pro|Third pro" cons="First con|Second con"]One or two sentences on why this is our pick.[/pgh_product]
<!-- /wp:shortcode -->';

	register_block_pattern(
		'pawgadgethub/review-single',
		array(
			'title'       => __( 'Single product review', 'pawgadgethub' ),
			'description' => __( 'Quick verdict, product box, and the standard review sections.', 'pawgadgethub' ),
			'categories'  => array( 'pawgadgethub' ),
			'content'     => $verdict . $product . '
<!-- wp:heading -->
<h2 class="wp-block-heading">How we tested it</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Describe the setup, the time you spent with it, and what you measured (app reliability, battery life, noise, build quality).</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">What we like</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>The strongest points, with specifics.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">What could be better</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Be honest. Readers trust reviews that name real drawbacks.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Who should buy it</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Match the product to the reader and say who should skip it.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Frequently asked questions</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Add 3 to 5 real questions with short, direct answers.</p>
<!-- /wp:paragraph -->',
		)
	);

	register_block_pattern(
		'pawgadgethub/roundup',
		array(
			'title'       => __( 'Best-of roundup', 'pawgadgethub' ),
			'description' => __( 'Comparison table plus ranked product boxes.', 'pawgadgethub' ),
			'categories'  => array( 'pawgadgethub' ),
			'content'     => $verdict . '
<!-- wp:heading -->
<h2 class="wp-block-heading">Our top picks at a glance</h2>
<!-- /wp:heading -->
<!-- wp:shortcode -->
[pgh_compare headers="Product|Best for|Price|Rating|Buy"]
*Top pick name|Most people|$99|4.8|Check Price::https://www.amazon.com/dp/XXXXXXXXXX?tag=YOURTAG-20
Runner-up name|Budget buyers|$69|4.5|Check Price::https://www.amazon.com/dp/XXXXXXXXXX?tag=YOURTAG-20
Third pick name|Multi-pet homes|$129|4.4|Check Price::https://www.amazon.com/dp/XXXXXXXXXX?tag=YOURTAG-20
[/pgh_compare]
<!-- /wp:shortcode -->
<!-- wp:heading -->
<h2 class="wp-block-heading">The best options, reviewed</h2>
<!-- /wp:heading -->
<!-- wp:shortcode -->
[pgh_product rank="1" title="Top pick name" url="https://www.amazon.com/dp/XXXXXXXXXX?tag=YOURTAG-20" badge="Top Pick" rating="4.8" price="$99" pros="Pro one|Pro two" cons="Con one"]Why it is number one.[/pgh_product]
<!-- /wp:shortcode -->
<!-- wp:shortcode -->
[pgh_product rank="2" title="Runner-up name" url="https://www.amazon.com/dp/XXXXXXXXXX?tag=YOURTAG-20" badge="Best Value" rating="4.5" price="$69" pros="Pro one|Pro two" cons="Con one"]Why it is a great alternative.[/pgh_product]
<!-- /wp:shortcode -->
<!-- wp:heading -->
<h2 class="wp-block-heading">How we chose</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Explain your criteria and testing process so readers know why they can trust the ranking.</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">What to look for</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>The buying-guide section: the features that matter and the marketing that does not.</p>
<!-- /wp:paragraph -->',
		)
	);

	register_block_pattern(
		'pawgadgethub/callout',
		array(
			'title'      => __( 'Callout box', 'pawgadgethub' ),
			'categories' => array( 'pawgadgethub' ),
			'content'    => '<!-- wp:group {"className":"is-style-pgh-callout"} -->
<div class="wp-block-group is-style-pgh-callout"><!-- wp:paragraph -->
<p><strong>Pro tip:</strong> Add a short, useful tip or warning here.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
		)
	);
}
add_action( 'init', 'pgh_register_patterns' );
