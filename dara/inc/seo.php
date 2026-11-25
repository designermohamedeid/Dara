<?php
/**
 * SEO: fallbacks when no SEO plugin is active, and clean hand-off to Rank Math
 * (or Yoast / AIOSEO / SEOPress / TSF) when one is.
 *
 * Rule of thumb: the theme never prints a meta tag, canonical, robots tag or
 * JSON-LD block that an active SEO plugin already prints. With Rank Math it
 * feeds its data through Rank Math's own filters instead.
 *
 * @package Dara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether an SEO plugin handles titles, meta, canonicals and social tags.
 *
 * @return bool
 */
function dara_seo_plugin() {
	$active = defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' );
	return (bool) apply_filters( 'dara_seo_plugin_active', $active );
}

/**
 * Whether Rank Math is active.
 *
 * @return bool
 */
function dara_rank_math() {
	return defined( 'RANK_MATH_VERSION' );
}

/**
 * Pages that only make sense per visitor (compare, favorites).
 *
 * @return int[]
 */
function dara_private_page_ids() {
	$ids = array();
	foreach ( array( 'page-templates/compare.php', 'page-templates/favorites.php' ) as $tpl ) {
		$url = dara_template_url( $tpl );
		if ( $url ) {
			$ids[] = (int) url_to_postid( $url );
		}
	}
	return array_values( array_filter( $ids ) );
}

/**
 * Should the current view stay out of search results?
 *
 * Per-visitor pages, and property searches with filters or a sort order: those
 * are endless URL combinations of the same listings. City and type archives
 * (/city/riyadh/, /type/villa/) stay indexable and are the pages to rank.
 *
 * @return bool
 */
function dara_should_noindex() {
	if ( is_page_template( array( 'page-templates/compare.php', 'page-templates/favorites.php' ) ) ) {
		return true;
	}
	if ( ( is_post_type_archive( 'dara_property' ) || is_tax( array( 'property_type', 'property_city' ) ) ) && function_exists( 'dara_filters_active' ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check.
		return dara_filters_active() || isset( $_GET['sort'] );
	}
	return false;
}

/**
 * Core robots tag (used when no SEO plugin, and by Yoast/AIOSEO which read it).
 *
 * @param array $robots Directives.
 * @return array
 */
function dara_wp_robots( $robots ) {
	if ( dara_should_noindex() ) {
		unset( $robots['index'], $robots['max-image-preview'] );
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'dara_wp_robots' );

/**
 * Rank Math prints its own robots tag: pass the same decision to it.
 *
 * @param array $robots Rank Math directives (index => noindex|index, follow => ...).
 * @return array
 */
function dara_rank_math_robots( $robots ) {
	if ( dara_should_noindex() ) {
		$robots['index']  = 'noindex';
		$robots['follow'] = 'follow';
	}
	return $robots;
}
add_filter( 'rank_math/frontend/robots', 'dara_rank_math_robots' );

/**
 * Keep compare/favorites pages out of sitemaps (core + Rank Math).
 *
 * @param array $ids Excluded IDs.
 * @return array
 */
function dara_rank_math_sitemap_exclude( $ids ) {
	return array_merge( (array) $ids, dara_private_page_ids() );
}
add_filter( 'rank_math/sitemap/posts_to_exclude', 'dara_rank_math_sitemap_exclude' );

/**
 * Core sitemap: drop the per-visitor pages.
 *
 * @param array  $args      Query args.
 * @param string $post_type Post type.
 * @return array
 */
function dara_core_sitemap_exclude( $args, $post_type ) {
	if ( 'page' === $post_type ) {
		$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), dara_private_page_ids() );
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'dara_core_sitemap_exclude', 10, 2 );

/**
 * Descriptive document title for property searches ("Villas for sale in Riyadh").
 * SEO plugins replace the whole title, so this only shows without one.
 *
 * @param array $parts Title parts.
 * @return array
 */
function dara_document_title( $parts ) {
	if ( is_post_type_archive( 'dara_property' ) && function_exists( 'dara_filters_active' ) && dara_filters_active() ) {
		$parts['title'] = dara_listing_title();
	}
	return $parts;
}
add_filter( 'document_title_parts', 'dara_document_title' );

/**
 * Description for the current view (used for meta description + social tags).
 *
 * @return string
 */
function dara_seo_description() {
	$desc = '';
	if ( is_front_page() ) {
		$desc = get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : dara_mod( 'hero_text' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		$desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 30, '…' );
	} elseif ( is_home() ) {
		$desc = get_bloginfo( 'description' );
	} elseif ( is_post_type_archive( 'dara_property' ) ) {
		$desc = dara_listing_title() . ' — ' . get_bloginfo( 'name' );
	} elseif ( is_archive() ) {
		$desc = wp_strip_all_tags( get_the_archive_description() );
		if ( '' === trim( $desc ) ) {
			$desc = dara_page_title() . ' — ' . get_bloginfo( 'name' );
		}
	}
	$desc = trim( preg_replace( '/\s+/', ' ', (string) $desc ) );
	return $desc ? wp_html_excerpt( $desc, 160, '…' ) : '';
}

/**
 * Share image for the current view.
 *
 * @return int Attachment ID or 0.
 */
function dara_seo_image_id() {
	if ( is_singular() ) {
		$id = (int) get_post_thumbnail_id( get_queried_object_id() );
		if ( ! $id && function_exists( 'dara_gallery_ids' ) ) {
			$ids = dara_gallery_ids( get_queried_object_id() );
			$id  = $ids ? (int) $ids[0] : 0;
		}
		if ( $id ) {
			return $id;
		}
	}
	return (int) dara_mod( 'hero_image' );
}

/**
 * Meta description, canonical for archives and Open Graph / Twitter tags,
 * only when no SEO plugin is active.
 */
function dara_seo_head() {
	if ( dara_seo_plugin() || is_404() ) {
		return;
	}
	$desc = dara_seo_description();
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}

	// Core prints rel=canonical on singular views only.
	$url = '';
	if ( is_singular() ) {
		$url = wp_get_canonical_url();
	} elseif ( is_front_page() || is_home() ) {
		$url = is_front_page() ? home_url( '/' ) : get_permalink( (int) get_option( 'page_for_posts' ) );
	} elseif ( is_archive() && ! dara_should_noindex() ) {
		$url = get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ), false );
		$url = remove_query_arg( array_keys( $_GET ), $url ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- strip query args only.
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}

	$title = wp_get_document_title();
	$tags  = array(
		'og:type'      => is_singular( array( 'post' ) ) ? 'article' : 'website',
		'og:site_name' => get_bloginfo( 'name' ),
		'og:locale'    => get_locale(),
		'og:title'     => $title,
		'og:url'       => $url,
	);
	if ( $desc ) {
		$tags['og:description'] = $desc;
	}
	$image = dara_seo_image_id();
	$src   = $image ? wp_get_attachment_image_src( $image, 'dara-wide' ) : false;
	if ( $src ) {
		$tags['og:image']        = $src[0];
		$tags['og:image:width']  = $src[1];
		$tags['og:image:height'] = $src[2];
		$alt                     = get_post_meta( $image, '_wp_attachment_image_alt', true );
		$tags['og:image:alt']    = $alt ? $alt : $title;
	}
	foreach ( $tags as $property => $content ) {
		if ( '' !== (string) $content ) {
			printf( '<meta property="%1$s" content="%2$s">' . "\n", esc_attr( $property ), esc_attr( $content ) );
		}
	}
	printf( '<meta name="twitter:card" content="%s">' . "\n", $src ? 'summary_large_image' : 'summary' );
}
add_action( 'wp_head', 'dara_seo_head', 2 );

/**
 * WebSite + Organization JSON-LD on the home page (no SEO plugin only).
 */
function dara_seo_site_schema() {
	if ( dara_seo_plugin() || ! is_front_page() || ! apply_filters( 'dara_output_schema', true ) ) {
		return;
	}
	$home = home_url( '/' );
	$org  = array(
		'@type' => 'RealEstateAgent',
		'@id'   => $home . '#organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => $home,
	);
	if ( has_custom_logo() ) {
		$logo = wp_get_attachment_image_url( (int) get_theme_mod( 'custom_logo' ), 'full' );
		if ( $logo ) {
			$org['logo'] = $logo;
			$org['image'] = $logo;
		}
	}
	foreach ( array( 'phone' => 'telephone', 'email' => 'email', 'address' => 'address' ) as $mod => $prop ) {
		if ( dara_mod( $mod ) ) {
			$org[ $prop ] = dara_mod( $mod );
		}
	}
	$same = array();
	foreach ( array( 'x', 'instagram', 'snapchat', 'tiktok', 'youtube', 'linkedin', 'facebook' ) as $net ) {
		if ( dara_mod( 'social_' . $net ) ) {
			$same[] = dara_mod( 'social_' . $net );
		}
	}
	if ( $same ) {
		$org['sameAs'] = $same;
	}
	$site = array(
		'@type'      => 'WebSite',
		'@id'        => $home . '#website',
		'url'        => $home,
		'name'       => get_bloginfo( 'name' ),
		'inLanguage' => get_bloginfo( 'language' ),
		'publisher'  => array( '@id' => $home . '#organization' ),
	);
	if ( post_type_exists( 'dara_property' ) && get_post_type_archive_link( 'dara_property' ) ) {
		$site['potentialAction'] = array(
			'@type'       => 'SearchAction',
			'target'      => add_query_arg( 'location', '{search_term_string}', get_post_type_archive_link( 'dara_property' ) ),
			'query-input' => 'required name=search_term_string',
		);
	}
	dara_print_json_ld(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => array( $org, $site ),
		)
	);
}
add_action( 'wp_head', 'dara_seo_site_schema', 20 );

/**
 * Print one JSON-LD block.
 *
 * @param array $data Data.
 */
function dara_print_json_ld( $data ) {
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ) . '</script>' . "\n";
}

/**
 * Who draws the breadcrumbs: 'yoast', 'rank_math' or 'dara'.
 *
 * Rank Math's breadcrumbs only print when they are switched on in
 * Rank Math → General Settings → Breadcrumbs; otherwise the theme's trail shows.
 *
 * @return string
 */
function dara_breadcrumbs_provider() {
	if ( function_exists( 'yoast_breadcrumb' ) && class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'breadcrumbs-enable' ) ) {
		return 'yoast';
	}
	if ( function_exists( 'rank_math_the_breadcrumbs' ) && class_exists( '\RankMath\Helper' ) && \RankMath\Helper::is_breadcrumbs_enabled() ) {
		return 'rank_math';
	}
	return 'dara';
}

/**
 * BreadcrumbList for the theme's own trail.
 *
 * @return array|null
 */
function dara_breadcrumb_schema() {
	if ( is_front_page() || is_404() || is_search() ) {
		return null;
	}
	$items = dara_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return null;
	}
	$list = array();
	foreach ( $items as $i => $item ) {
		$entry = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => wp_strip_all_tags( $item[1] ),
		);
		if ( $item[0] ) {
			$entry['item'] = $item[0];
		}
		$list[] = $entry;
	}
	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => strtok( (string) ( is_singular() ? get_permalink() : get_pagenum_link( 1, false ) ), '?' ) . '#breadcrumb',
		'itemListElement' => $list,
	);
}

/**
 * No SEO plugin: print BreadcrumbList for the theme trail.
 */
function dara_breadcrumb_schema_head() {
	if ( dara_seo_plugin() || ! apply_filters( 'dara_output_schema', true ) ) {
		return;
	}
	$crumbs = dara_breadcrumb_schema();
	if ( $crumbs ) {
		dara_print_json_ld( array( '@context' => 'https://schema.org' ) + $crumbs );
	}
}
add_action( 'wp_head', 'dara_breadcrumb_schema_head', 21 );

/**
 * Rank Math with its breadcrumbs off: the visible trail is the theme's, so add
 * a matching BreadcrumbList to Rank Math's graph (it adds none in that case).
 *
 * @param array $data Rank Math graph pieces.
 * @return array
 */
function dara_rank_math_breadcrumbs_schema( $data ) {
	if ( 'dara' !== dara_breadcrumbs_provider() || isset( $data['BreadcrumbList'] ) || ! apply_filters( 'dara_output_schema', true ) ) {
		return $data;
	}
	$crumbs = dara_breadcrumb_schema();
	if ( $crumbs ) {
		$data['BreadcrumbList'] = $crumbs;
	}
	return $data;
}
add_filter( 'rank_math/json_ld', 'dara_rank_math_breadcrumbs_schema', 20 );

/**
 * Listing photos with no alt text get the property/project title, so image
 * search and screen readers get something meaningful.
 *
 * @param array   $attr       Attributes.
 * @param WP_Post $attachment Attachment.
 * @return array
 */
function dara_image_alt_fallback( $attr, $attachment ) {
	if ( isset( $attr['alt'] ) && '' !== trim( $attr['alt'] ) ) {
		return $attr;
	}
	$post = get_post();
	if ( ! $post || ! in_array( $post->post_type, array( 'dara_property', 'dara_project', 'dara_agent' ), true ) ) {
		return $attr;
	}
	// Decorative full-bleed hero backgrounds keep alt="" (text sits on top).
	if ( isset( $attr['class'] ) && false !== strpos( $attr['class'], 'hero__bg' ) ) {
		return $attr;
	}
	$attr['alt'] = get_the_title( $post );
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'dara_image_alt_fallback', 10, 2 );
