<?php
/**
 * Front-end performance tweaks (each can be switched off in Customize > Dara > Performance).
 *
 * @package Dara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove the emoji detection script and styles.
 */
function dara_disable_emojis() {
	if ( ! dara_mod( 'perf_emoji' ) ) {
		return;
	}
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'dara_disable_emojis' );

/**
 * Only load block-library CSS where blocks are actually used.
 */
function dara_trim_block_css() {
	if ( ! dara_mod( 'perf_block_css' ) ) {
		return;
	}
	$keep = is_home() || is_search()
		|| ( is_archive() && ! is_post_type_archive( array( 'dara_property', 'dara_project' ) ) && ! is_tax( array( 'property_type', 'property_city' ) ) )
		|| ( is_singular() && has_blocks( get_queried_object_id() ) );
	if ( $keep ) {
		return;
	}
	foreach ( array( 'wp-block-library', 'wp-block-library-theme', 'classic-theme-styles', 'global-styles' ) as $handle ) {
		wp_dequeue_style( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'dara_trim_block_css', 100 );

/**
 * Classic themes load the whole block library (~110 KB). Load only the CSS of
 * blocks that are actually rendered on the page instead.
 */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * New JPEG uploads get WebP sub-sizes (smaller files, same quality).
 *
 * @param array $formats Mime map.
 * @return array
 */
function dara_webp_uploads( $formats ) {
	if ( dara_mod( 'perf_webp' ) && wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ) {
		$formats['image/jpeg'] = 'image/webp';
	}
	return $formats;
}
add_filter( 'image_editor_output_format', 'dara_webp_uploads' );

/**
 * Prefetch a page when the visitor hovers / presses a link, so it opens almost
 * instantly. WordPress 6.8+ ships Speculation Rules (eagerness "conservative");
 * this moves it to "moderate". Older WordPress gets an equivalent rule set.
 *
 * @param array|null $config Core configuration.
 * @return array|null
 */
function dara_speculation_config( $config ) {
	if ( dara_mod( 'perf_prefetch' ) && is_array( $config ) && ( empty( $config['eagerness'] ) || 'auto' === $config['eagerness'] ) ) {
		$config['eagerness'] = 'moderate';
	}
	return $config;
}
add_filter( 'wp_speculation_rules_configuration', 'dara_speculation_config' );

/**
 * Speculation Rules for WordPress older than 6.8.
 */
function dara_speculation_rules_legacy() {
	if ( function_exists( 'wp_get_speculation_rules_configuration' ) || ! dara_mod( 'perf_prefetch' ) || is_user_logged_in() ) {
		return;
	}
	$rules = array(
		'prefetch' => array(
			array(
				'source'    => 'document',
				'where'     => array(
					'and' => array(
						array( 'href_matches' => '/*' ),
						array( 'not' => array( 'href_matches' => array( '/wp-*.php', '/wp-admin/*', '/wp-content/*', '/*\\?*' ) ) ),
						array( 'not' => array( 'selector_matches' => 'a[rel~="nofollow"], a[target="_blank"], .no-prefetch' ) ),
					),
				),
				'eagerness' => 'moderate',
			),
		),
	);
	echo '<script type="speculationrules">' . wp_json_encode( $rules, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_footer', 'dara_speculation_rules_legacy' );

/**
 * Default favicon in the brand colours until a Site Icon is set
 * (Customize > Site Identity). Also stops the /favicon.ico 404.
 */
function dara_default_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	$base = DARA_URI . '/assets/images/';
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( $base . 'favicon.svg' ) );
	printf( '<link rel="icon" href="%s" sizes="32x32" type="image/png">' . "\n", esc_url( $base . 'favicon-32.png' ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( $base . 'apple-touch-icon.png' ) );
}
add_action( 'wp_head', 'dara_default_favicon', 3 );
