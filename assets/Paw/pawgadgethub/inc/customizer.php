<?php
/**
 * Customizer settings (Appearance > Customize > PawGadgetHub).
 *
 * @package PawGadgetHub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default values for every theme option.
 *
 * @return array
 */
function pgh_defaults() {
	return array(
		'hero_title'           => __( 'Smart Tech for Happier Pets', 'pawgadgethub' ),
		'hero_subtitle'        => __( 'Honest reviews and buying guides for modern pet parents: feeders, GPS trackers, cameras, litter boxes, and fountains that make pet care effortless.', 'pawgadgethub' ),
		'show_disclosure'      => true,
		'disclosure_text'      => __( 'We may earn a commission if you buy through links on this page, at no extra cost to you. It never changes our verdict.', 'pawgadgethub' ),
		'amazon_statement'     => __( 'As an Amazon Associate we earn from qualifying purchases.', 'pawgadgethub' ),
		'show_toc'             => true,
		'toc_min'              => 3,
		'newsletter_title'     => __( 'Get the latest pet tech deals and smart hacks', 'pawgadgethub' ),
		'newsletter_text'      => __( 'One short email when something great drops. No spam, ever.', 'pawgadgethub' ),
		'newsletter_action'    => '',
		'newsletter_field'     => 'EMAIL',
		'newsletter_shortcode' => '',
		'footer_about'         => __( 'Independent, honest reviews of the smart gadgets that make pet care effortless. Written by pet parents and tech fans.', 'pawgadgethub' ),
	);
}

/**
 * Get a theme option with its default.
 *
 * @param string $key Option key (without the pgh_ prefix).
 * @return mixed
 */
function pgh_opt( $key ) {
	$defaults = pgh_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( 'pgh_' . $key, $default );
}

function pgh_sanitize_checkbox( $value ) {
	return ( isset( $value ) && true == $value ) ? true : false; // phpcs:ignore WordPress.PHP.StrictComparisons
}

function pgh_sanitize_int_min( $value ) {
	return max( 1, absint( $value ) );
}

/**
 * Register Customizer controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function pgh_customize_register( $wp_customize ) {
	$d = pgh_defaults();

	$wp_customize->add_panel(
		'pgh_panel',
		array(
			'title'    => __( 'PawGadgetHub', 'pawgadgethub' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'pgh_home'       => array(
			'title'  => __( 'Homepage', 'pawgadgethub' ),
			'fields' => array(
				'hero_title'    => array( __( 'Hero headline', 'pawgadgethub' ), 'text', 'sanitize_text_field' ),
				'hero_subtitle' => array( __( 'Hero subtitle', 'pawgadgethub' ), 'textarea', 'sanitize_textarea_field' ),
			),
		),
		'pgh_affiliate'  => array(
			'title'  => __( 'Affiliate & Disclosure', 'pawgadgethub' ),
			'fields' => array(
				'show_disclosure'  => array( __( 'Show the disclosure banner on posts', 'pawgadgethub' ), 'checkbox', 'pgh_sanitize_checkbox' ),
				'disclosure_text'  => array( __( 'Banner text (top of each post)', 'pawgadgethub' ), 'textarea', 'sanitize_textarea_field' ),
				'amazon_statement' => array( __( 'Amazon Associates statement (footer). Keep the official wording.', 'pawgadgethub' ), 'textarea', 'sanitize_textarea_field' ),
			),
		),
		'pgh_toc'        => array(
			'title'  => __( 'Table of Contents', 'pawgadgethub' ),
			'fields' => array(
				'show_toc' => array( __( 'Add a table of contents to posts', 'pawgadgethub' ), 'checkbox', 'pgh_sanitize_checkbox' ),
				'toc_min'  => array( __( 'Only when a post has at least this many headings (H2/H3)', 'pawgadgethub' ), 'number', 'pgh_sanitize_int_min' ),
			),
		),
		'pgh_newsletter' => array(
			'title'       => __( 'Newsletter', 'pawgadgethub' ),
			'description' => __( 'Paste the form action URL from your email service (Mailchimp "Embedded form", ConvertKit, MailerLite, Brevo...). Or use a plugin shortcode instead. The box stays hidden from visitors until one is set.', 'pawgadgethub' ),
			'fields'      => array(
				'newsletter_title'     => array( __( 'Heading', 'pawgadgethub' ), 'text', 'sanitize_text_field' ),
				'newsletter_text'      => array( __( 'Short text', 'pawgadgethub' ), 'textarea', 'sanitize_textarea_field' ),
				'newsletter_action'    => array( __( 'Form action URL', 'pawgadgethub' ), 'url', 'esc_url_raw' ),
				'newsletter_field'     => array( __( 'Email field name (Mailchimp uses EMAIL)', 'pawgadgethub' ), 'text', 'sanitize_text_field' ),
				'newsletter_shortcode' => array( __( 'OR plugin shortcode (overrides the form above)', 'pawgadgethub' ), 'text', 'sanitize_text_field' ),
			),
		),
		'pgh_footer'     => array(
			'title'  => __( 'Footer', 'pawgadgethub' ),
			'fields' => array(
				'footer_about' => array( __( 'About text', 'pawgadgethub' ), 'textarea', 'sanitize_textarea_field' ),
			),
		),
	);

	foreach ( $fields as $section_id => $section ) {
		$wp_customize->add_section(
			$section_id,
			array(
				'title'       => $section['title'],
				'description' => isset( $section['description'] ) ? $section['description'] : '',
				'panel'       => 'pgh_panel',
			)
		);
		foreach ( $section['fields'] as $key => $field ) {
			$wp_customize->add_setting(
				'pgh_' . $key,
				array(
					'default'           => $d[ $key ],
					'sanitize_callback' => $field[2],
					'transport'         => 'refresh',
				)
			);
			$wp_customize->add_control(
				'pgh_' . $key,
				array(
					'label'   => $field[0],
					'section' => $section_id,
					'type'    => $field[1],
				)
			);
		}
	}
}
add_action( 'customize_register', 'pgh_customize_register' );
