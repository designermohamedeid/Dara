<?php
/**
 * Options and template helpers.
 *
 * @package Dara
 */

defined( 'ABSPATH' ) || exit;

/**
 * Customizer defaults (translatable, so a fresh Arabic site shows Arabic copy).
 *
 * @return array
 */
function dara_defaults() {
	static $d = null;
	if ( null !== $d ) {
		return $d;
	}
	$d = array(
		// Brand.
		'primary_color'      => '#0B6E5F',
		'ink_color'          => '#0E1A2B',
		'header_cta_text'    => __( 'List your property', 'dara' ),
		'header_cta_url'     => '',
		// Hero.
		'hero_image'         => 0,
		'hero_eyebrow'       => __( 'Properties for sale, rent and new developments', 'dara' ),
		'hero_title'         => __( 'Find your next property with confidence', 'dara' ),
		'hero_text'          => __( 'Detailed listings, clear prices and an agent who stays with you until the contract is signed.', 'dara' ),
		'hero_popular'       => '',
		// Featured.
		'show_featured'      => true,
		'featured_eyebrow'   => __( 'Hand-picked', 'dara' ),
		'featured_title'     => __( 'Latest featured properties', 'dara' ),
		'featured_count'     => 6,
		'featured_only'      => false,
		// Types.
		'show_types'         => true,
		'types_eyebrow'      => __( 'Browse by type', 'dara' ),
		'types_title'        => __( 'What are you looking for?', 'dara' ),
		// Projects.
		'show_projects'      => true,
		'projects_eyebrow'   => __( 'Developer projects', 'dara' ),
		'projects_title'     => __( 'New off-plan and under-construction projects', 'dara' ),
		'projects_text'      => __( 'Follow construction progress, see available units and payment plans, and register your interest directly.', 'dara' ),
		'projects_count'     => 2,
		// Services.
		'show_services'      => true,
		'services_eyebrow'   => __( 'Our services', 'dara' ),
		'services_title'     => __( 'Everything your property needs, in one place', 'dara' ),
		'services_items'     => implode(
			"\n",
			array(
				'megaphone | ' . __( 'Real estate marketing', 'dara' ) . ' | ' . __( 'Professional photography and digital campaigns that reach the right buyer fast.', 'dara' ) . ' | ',
				'key | ' . __( 'Property management', 'dara' ) . ' | ' . __( 'Rent collection, maintenance and renewals with clear periodic reports for owners.', 'dara' ) . ' | ',
				'chart | ' . __( 'Financing solutions', 'dara' ) . ' | ' . __( 'We compare mortgage offers and guide you through the process until approval.', 'dara' ) . ' | ',
				'clipboard | ' . __( 'Property valuation', 'dara' ) . ' | ' . __( 'A clear estimate of the fair market value before you buy or sell.', 'dara' ) . ' | ',
			)
		),
		// Why us.
		'show_why'           => true,
		'why_image'          => 0,
		'why_eyebrow'        => __( 'Why us?', 'dara' ),
		'why_title'          => __( 'A clear real estate experience from search to handover', 'dara' ),
		'why_text'           => __( 'We show every detail you need to decide with confidence, and arrange the viewing, negotiation and paperwork for you.', 'dara' ),
		'why_points'         => implode(
			"\n",
			array(
				__( 'Complete, up-to-date listing details', 'dara' ),
				__( 'Published prices with no hidden fees', 'dara' ),
				__( 'A dedicated agent on phone and WhatsApp', 'dara' ),
				__( 'Help with financing, documentation and transfer', 'dara' ),
			)
		),
		'why_badge_title'    => __( 'Licensed listings', 'dara' ),
		'why_badge_text'     => __( 'A license number on every ad', 'dara' ),
		'why_btn_text'       => __( 'Talk to an advisor', 'dara' ),
		'why_btn_url'        => '',
		// Blog.
		'show_blog'          => true,
		'blog_eyebrow'       => __( 'Blog', 'dara' ),
		'blog_title'         => __( 'Your guide to the property market', 'dara' ),
		'blog_count'         => 3,
		// CTA.
		'show_cta'           => true,
		'cta_title'          => __( 'Own a property you want to sell or rent?', 'dara' ),
		'cta_text'           => __( 'Send us the details and an agent will contact you within one business day to arrange photos and pricing.', 'dara' ),
		'cta_btn_text'       => __( 'List your property', 'dara' ),
		'cta_btn_url'        => '',
		// Contact.
		'phone'              => '',
		'whatsapp'           => '',
		'email'              => '',
		'address'            => '',
		'map_lat'            => '',
		'map_lng'            => '',
		// Social.
		'social_x'           => '',
		'social_instagram'   => '',
		'social_snapchat'    => '',
		'social_tiktok'      => '',
		'social_youtube'     => '',
		'social_linkedin'    => '',
		'social_facebook'    => '',
		// Footer.
		'footer_about'       => __( 'We help you buy, sell, rent and manage property, and present developer projects transparently from the first visit to the signed contract.', 'dara' ),
		'newsletter_show'    => true,
		'newsletter_text'    => __( 'Get the newest properties and projects first.', 'dara' ),
		/* translators: Keep {year} and {site}. */
		'copyright'          => __( '© {year} {site}. All rights reserved.', 'dara' ),
		'designer_credit'    => true,
		// Mortgage calculator.
		'mortgage_show'      => true,
		'mortgage_method'    => 'amortized',
		'mortgage_rate'      => '5.5',
		'mortgage_down'      => 10,
		'mortgage_years'     => 25,
		'mortgage_max_years' => 30,
		'mortgage_note'      => __( 'Estimate only. The final installment depends on the bank, your salary and your obligations.', 'dara' ),
		// Performance.
		'perf_emoji'         => true,
		'perf_block_css'     => true,
		'perf_inline_css'    => true,
		'perf_webp'          => true,
		'perf_prefetch'      => true,
		'ux_motion'          => true,
		'ux_back_to_top'     => true,
		'ux_recent'          => true,
		'map_provider'       => 'osm',
		'map_google_key'     => '',
		'map_tiles'          => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
		'map_attribution'    => '&copy; OpenStreetMap',
	);
	return $d;
}

/**
 * Theme option with default.
 *
 * @param string $key Key.
 * @return mixed
 */
function dara_mod( $key ) {
	$d = dara_defaults();
	return get_theme_mod( $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

/**
 * Whether the Dara Core plugin is active.
 *
 * @return bool
 */
function dara_has_core() {
	return function_exists( 'dara_price_parts' ) && post_type_exists( 'dara_property' );
}

/**
 * Transparent header over a hero image?
 *
 * @return bool
 */
function dara_header_is_overlay() {
	if ( is_singular( 'dara_project' ) ) {
		return true;
	}
	$id = get_queried_object_id();
	if ( is_front_page() && ! ( 'page' === get_option( 'show_on_front' ) && dara_has_dara_blocks( $id ) ) ) {
		return true; // Customizer home starts with the hero.
	}
	return is_singular( 'page' ) && 'dara/hero' === dara_first_block( $id );
}

/**
 * Lines "a | b | c" from a theme mod.
 *
 * @param string $key     Mod key.
 * @param int    $columns Columns.
 * @return array[]
 */
function dara_mod_lines( $key, $columns = 1 ) {
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) dara_mod( $key ) ) as $line ) {
		$line = trim( $line );
		if ( '' !== $line ) {
			$rows[] = array_pad( array_map( 'trim', explode( '|', $line ) ), $columns, '' );
		}
	}
	return $rows;
}

/**
 * Site logo (custom logo or text mark).
 *
 * @param bool $light Light version for dark backgrounds.
 */
function dara_logo( $light = false ) {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="logo%1$s" href="%2$s" rel="home"><span class="logo__mark">%3$s</span><span class="logo__text">%4$s</span></a>',
		$light ? ' logo--light' : '',
		esc_url( home_url( '/' ) ),
		dara_icon( 'logo', 26 ), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Responsive image helper.
 *
 * @param int    $id    Attachment ID.
 * @param string $size  Size.
 * @param array  $attr  Attributes.
 * @return string
 */
function dara_img( $id, $size = 'dara-card', $attr = array() ) {
	if ( ! $id ) {
		return '<span class="img-placeholder" aria-hidden="true">' . dara_icon( 'image', 32 ) . '</span>';
	}
	$attr = array_merge(
		array(
			'loading'  => 'lazy',
			'decoding' => 'async',
		),
		$attr
	);
	return wp_get_attachment_image( $id, $size, false, $attr );
}

/**
 * Social networks.
 *
 * @return array key => label
 */
function dara_social_networks() {
	return array(
		'x'         => 'X',
		'instagram' => 'Instagram',
		'snapchat'  => 'Snapchat',
		'tiktok'    => 'TikTok',
		'youtube'   => 'YouTube',
		'linkedin'  => 'LinkedIn',
		'facebook'  => 'Facebook',
	);
}

/**
 * Social links list.
 */
function dara_social_links() {
	$out = '';
	foreach ( dara_social_networks() as $key => $label ) {
		$url = dara_mod( 'social_' . $key );
		if ( $url ) {
			$out .= sprintf( '<a href="%1$s" target="_blank" rel="noopener" aria-label="%2$s">%3$s</a>', esc_url( $url ), esc_attr( $label ), dara_icon( $key, 18 ) );
		}
	}
	if ( dara_mod( 'whatsapp' ) ) {
		$out .= sprintf( '<a href="%1$s" target="_blank" rel="noopener" aria-label="WhatsApp">%2$s</a>', esc_url( 'https://wa.me/' . preg_replace( '/\D+/', '', dara_mod( 'whatsapp' ) ) ), dara_icon( 'whatsapp', 18 ) );
	}
	if ( $out ) {
		echo '<div class="social">' . $out . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
	}
}

/**
 * Other language links (Polylang / WPML).
 *
 * @return array
 */
function dara_languages() {
	$langs = array();
	if ( function_exists( 'pll_the_languages' ) ) {
		foreach ( (array) pll_the_languages( array( 'raw' => 1 ) ) as $l ) {
			if ( empty( $l['current_lang'] ) ) {
				$langs[] = array(
					'url'   => $l['url'],
					'label' => strtoupper( $l['slug'] ),
					'name'  => $l['name'],
				);
			}
		}
	} elseif ( has_filter( 'wpml_active_languages' ) ) {
		foreach ( (array) apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) ) as $l ) {
			if ( empty( $l['active'] ) ) {
				$langs[] = array(
					'url'   => $l['url'],
					'label' => strtoupper( $l['code'] ),
					'name'  => $l['native_name'],
				);
			}
		}
	}
	return $langs;
}

/**
 * Page title for the inner title bar.
 *
 * @return string
 */
function dara_page_title() {
	if ( is_home() ) {
		$id = (int) get_option( 'page_for_posts' );
		return $id ? get_the_title( $id ) : __( 'Blog', 'dara' );
	}
	if ( is_search() ) {
		/* translators: %s: search term. */
		return sprintf( __( 'Search: %s', 'dara' ), get_search_query() );
	}
	if ( is_404() ) {
		return __( 'Page not found', 'dara' );
	}
	if ( is_post_type_archive( 'dara_property' ) ) {
		return dara_listing_title();
	}
	if ( is_post_type_archive() ) {
		return post_type_archive_title( '', false );
	}
	if ( is_tax( 'project_developer' ) ) {
		return single_term_title( '', false );
	}
	if ( is_archive() ) {
		return wp_strip_all_tags( get_the_archive_title() );
	}
	return single_post_title( '', false );
}

/**
 * Descriptive title for property searches ("Villas for sale in Riyadh").
 *
 * @return string
 */
function dara_listing_title() {
	if ( ! dara_has_core() ) {
		return post_type_archive_title( '', false );
	}
	$f     = dara_get_filters();
	$title = post_type_archive_title( '', false );
	if ( $f['type'] ) {
		$term = get_term_by( 'slug', $f['type'][0], 'property_type' );
		if ( $term ) {
			$title = $term->name;
		}
	}
	if ( $f['purpose'] ) {
		$title .= ' ' . dara_purpose_label( $f['purpose'] );
	}
	if ( $f['location'] ) {
		/* translators: %s: location. */
		$title .= ' ' . sprintf( __( 'in %s', 'dara' ), $f['location'] );
	}
	return $title;
}

/**
 * Breadcrumb trail of the current view: array of array( url, label ); the last item has no URL.
 *
 * @return array
 */
function dara_breadcrumb_items() {
	$items = array( array( home_url( '/' ), __( 'Home', 'dara' ) ) );
	if ( is_singular() && ! is_page() ) {
		$type = get_post_type();
		if ( 'post' === $type ) {
			$blog = (int) get_option( 'page_for_posts' );
			if ( $blog ) {
				$items[] = array( get_permalink( $blog ), get_the_title( $blog ) );
			}
		} else {
			$obj = get_post_type_object( $type );
			if ( $obj && $obj->has_archive ) {
				$items[] = array( get_post_type_archive_link( $type ), $obj->labels->name );
			}
		}
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $a ) {
			$items[] = array( get_permalink( $a ), get_the_title( $a ) );
		}
	} elseif ( is_tax( array( 'property_type', 'property_city' ) ) ) {
		$items[] = array( get_post_type_archive_link( 'dara_property' ), get_post_type_object( 'dara_property' )->labels->name );
	} elseif ( is_tax( 'project_developer' ) ) {
		$devs    = dara_template_url( 'page-templates/developers.php' );
		$items[] = $devs ? array( $devs, get_the_title( url_to_postid( $devs ) ) ) : array( get_post_type_archive_link( 'dara_project' ), get_post_type_object( 'dara_project' )->labels->name );
	}
	$items[] = array( '', is_singular() ? get_the_title( get_queried_object_id() ) : dara_page_title() );
	return apply_filters( 'dara_breadcrumb_items', $items );
}

/**
 * Breadcrumbs: Yoast / Rank Math when their breadcrumbs are switched on, the theme's trail otherwise.
 */
function dara_breadcrumbs() {
	$provider = dara_breadcrumbs_provider();
	if ( 'yoast' === $provider ) {
		yoast_breadcrumb( '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'dara' ) . '">', '</nav>' );
		return;
	}
	if ( 'rank_math' === $provider ) {
		rank_math_the_breadcrumbs(
			array(
				'wrap_before' => '<nav class="breadcrumbs breadcrumbs--rank-math" aria-label="' . esc_attr__( 'Breadcrumbs', 'dara' ) . '"><p>',
				'wrap_after'  => '</p></nav>',
			)
		);
		return;
	}
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'dara' ) . '"><ol>';
	foreach ( dara_breadcrumb_items() as $item ) {
		echo $item[0] ? '<li><a href="' . esc_url( $item[0] ) . '">' . esc_html( $item[1] ) . '</a></li>' : '<li aria-current="page">' . esc_html( $item[1] ) . '</li>';
	}
	echo '</ol></nav>';
}

/**
 * Pagination.
 */
function dara_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => dara_icon( 'chevron-r', 18, 'flip-ltr' ) . '<span class="screen-reader-text">' . __( 'Previous page', 'dara' ) . '</span>',
			'next_text'          => dara_icon( 'chevron-r', 18, 'flip-rtl' ) . '<span class="screen-reader-text">' . __( 'Next page', 'dara' ) . '</span>',
			'before_page_number' => '',
		)
	);
}

/**
 * Copyright line.
 *
 * @return string
 */
function dara_copyright() {
	return str_replace( array( '{year}', '{site}' ), array( wp_date( 'Y' ), get_bloginfo( 'name' ) ), dara_mod( 'copyright' ) );
}

/**
 * Archive link for properties, optionally filtered.
 *
 * @param array $args Query args.
 * @return string
 */
function dara_listings_url( $args = array() ) {
	$base = dara_has_core() ? get_post_type_archive_link( 'dara_property' ) : home_url( '/' );
	return $args ? add_query_arg( $args, $base ) : $base;
}

/**
 * Section heading.
 *
 * @param string $eyebrow Eyebrow.
 * @param string $title   Title.
 * @param string $text    Optional text.
 * @param string $class   Extra classes.
 */
function dara_section_head( $eyebrow, $title, $text = '', $class = '' ) {
	echo '<div class="section-head ' . esc_attr( $class ) . '">';
	if ( $eyebrow ) {
		echo '<span class="eyebrow">' . esc_html( $eyebrow ) . '</span>';
	}
	echo '<h2 class="section-title">' . esc_html( $title ) . '</h2>';
	if ( $text ) {
		echo '<p class="section-text">' . esc_html( $text ) . '</p>';
	}
	echo '</div>';
}

/**
 * URL of the first page using a page template (cached).
 *
 * @param string $template e.g. page-templates/favorites.php.
 * @return string
 */
function dara_template_url( $template ) {
	$map = get_transient( 'dara_template_pages' );
	if ( false === $map ) {
		$map = array();
		$ids = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
				'posts_per_page' => 50,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		foreach ( $ids as $id ) {
			$slug = get_page_template_slug( $id );
			if ( $slug && ! isset( $map[ $slug ] ) ) {
				$map[ $slug ] = $id;
			}
		}
		set_transient( 'dara_template_pages', $map, DAY_IN_SECONDS );
	}
	return isset( $map[ $template ] ) ? get_permalink( $map[ $template ] ) : '';
}

/**
 * Reset the template page cache when pages change.
 */
function dara_flush_template_pages() {
	delete_transient( 'dara_template_pages' );
}
add_action( 'save_post_page', 'dara_flush_template_pages' );
add_action( 'deleted_post', 'dara_flush_template_pages' );

/**
 * Header / CTA "List your property" link.
 *
 * @param string $mod Theme mod holding a custom URL.
 * @return string
 */
function dara_list_property_url( $mod = 'header_cta_url' ) {
	if ( dara_mod( $mod ) ) {
		return dara_mod( $mod );
	}
	$url = dara_template_url( 'page-templates/list-property.php' );
	return $url ? $url : dara_listings_url();
}

/**
 * Section option: block attribute when given, Customizer value otherwise.
 *
 * @param array|null $args Template part args.
 * @param string     $key  Option key.
 * @return mixed
 */
function dara_part_opt( $args, $key ) {
	if ( is_array( $args ) && array_key_exists( $key, $args ) && null !== $args[ $key ] ) {
		return $args[ $key ];
	}
	return dara_mod( $key );
}

/**
 * "a | b | c" lines from a block attribute or a theme mod.
 *
 * @param array|null $args    Template part args.
 * @param string     $key     Option key.
 * @param int        $columns Columns.
 * @return array[]
 */
function dara_part_lines( $args, $key, $columns = 1 ) {
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) dara_part_opt( $args, $key ) ) as $line ) {
		$line = trim( $line );
		if ( '' !== $line ) {
			$rows[] = array_pad( array_map( 'trim', explode( '|', $line ) ), $columns, '' );
		}
	}
	return $rows;
}

/**
 * Whether a post's content uses Dara blocks.
 *
 * @param int|WP_Post|null $post Post.
 * @return bool
 */
function dara_has_dara_blocks( $post = null ) {
	$post = get_post( $post );
	return $post && dara_premium() && WP_Block_Type_Registry::get_instance()->is_registered( 'dara/hero' ) && false !== strpos( $post->post_content, '<!-- wp:dara/' );
}

/**
 * Name of the first block in a post.
 *
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function dara_first_block( $post = null ) {
	$post = get_post( $post );
	if ( ! $post || ! has_blocks( $post ) ) {
		return '';
	}
	foreach ( parse_blocks( $post->post_content ) as $block ) {
		if ( ! empty( $block['blockName'] ) ) {
			return $block['blockName'];
		}
	}
	return '';
}

/**
 * Mortgage calculator (from the Dara Pro plugin).
 *
 * @param array $args { price, title, apply_url }.
 */
function dara_mortgage( $args = array() ) {
	if ( function_exists( 'dara_pro_mortgage' ) ) {
		dara_pro_mortgage( $args );
	}
}

/**
 * Dara Pro is installed and active on this site.
 *
 * @return bool
 */
function dara_premium() {
	return function_exists( 'dara_pro_active' ) && dara_pro_active();
}

/**
 * Compare page URL ('' when no page uses the Compare template).
 *
 * @return string
 */
function dara_compare_url() {
	return dara_premium() ? dara_template_url( 'page-templates/compare.php' ) : '';
}

/**
 * Add-to-compare button.
 *
 * @param int    $post_id Property ID.
 * @param string $variant "card" (round icon) or "inline" (labelled button).
 */
function dara_compare_button( $post_id, $variant = 'card' ) {
	if ( ! dara_compare_url() ) {
		return;
	}
	$title = get_the_title( $post_id );
	$img   = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
	/* translators: %s: property title. */
	$label = sprintf( __( 'Compare %s', 'dara' ), $title );
	printf(
		'<button type="button" class="%1$s" data-compare="%2$d" data-title="%3$s" data-img="%4$s" aria-pressed="false" aria-label="%5$s">%6$s%7$s</button>',
		'inline' === $variant ? 'btn btn--outline btn--sm cmp-btn--inline' : 'cmp-btn',
		(int) $post_id,
		esc_attr( $title ),
		esc_url( $img ? $img : '' ),
		esc_attr( $label ),
		dara_icon( 'compare', 'inline' === $variant ? 18 : 20 ), // phpcs:ignore WordPress.Security.EscapeOutput
		'inline' === $variant ? esc_html__( 'Compare', 'dara' ) : ''
	);
}

/**
 * Label of one project unit row, used in the inquiry form ("A1 — Apartment").
 *
 * @param array $unit Unit columns: model | type | area | bedrooms | price | status.
 * @return string
 */
function dara_project_unit_label( $unit ) {
	$model = isset( $unit[0] ) ? trim( $unit[0] ) : '';
	$type  = isset( $unit[1] ) ? trim( $unit[1] ) : '';
	return $model && $type ? $model . ' — ' . $type : $model . $type;
}

/**
 * Active search filters as removable chips (listing page).
 *
 * Each chip links to the same search without that one filter, so it works
 * with no JavaScript and keeps the back button meaningful.
 */
function dara_filter_chips() {
	if ( ! function_exists( 'dara_get_filters' ) || ! dara_filters_active() ) {
		return;
	}
	$f    = dara_get_filters();
	$base = strtok( (string) get_pagenum_link( 1, false ), '?' );
	$args = array();
	foreach ( $f as $k => $v ) {
		if ( '' !== $v && array() !== $v && ! ( 'sort' === $k && 'newest' === $v ) ) {
			$args[ $k ] = $v;
		}
	}
	$url = function ( $drop_key, $drop_value = null ) use ( $base, $args ) {
		$next = $args;
		if ( null !== $drop_value && is_array( $next[ $drop_key ] ) ) {
			$next[ $drop_key ] = array_values( array_diff( $next[ $drop_key ], array( $drop_value ) ) );
			if ( ! $next[ $drop_key ] ) {
				unset( $next[ $drop_key ] );
			}
		} else {
			unset( $next[ $drop_key ] );
		}
		return $next ? $base . '?' . http_build_query( $next ) : $base;
	};
	$term_name = function ( $slug, $tax ) {
		$t = get_term_by( 'slug', $slug, $tax );
		return $t ? $t->name : $slug;
	};
	$range = function ( $min, $max, $unit ) {
		if ( '' !== $min && '' !== $max ) {
			return dara_number( $min ) . ' – ' . dara_number( $max ) . $unit;
		}
		/* translators: %s: value. */
		return '' !== $min ? sprintf( __( 'From %s', 'dara' ), dara_number( $min ) . $unit ) : sprintf( __( 'Up to %s', 'dara' ), dara_number( $max ) . $unit );
	};

	$chips = array();
	if ( $f['purpose'] ) {
		$chips[] = array( dara_purpose_label( $f['purpose'] ), $url( 'purpose' ) );
	}
	if ( $f['location'] ) {
		$chips[] = array( $f['location'], $url( 'location' ) );
	}
	foreach ( $f['type'] as $slug ) {
		$chips[] = array( $term_name( $slug, 'property_type' ), $url( 'type', $slug ) );
	}
	if ( '' !== $f['min_price'] || '' !== $f['max_price'] ) {
		$chips[] = array( $range( $f['min_price'], $f['max_price'], '' ), remove_query_arg( 'max_price', $url( 'min_price' ) ) );
	}
	if ( '' !== $f['beds'] ) {
		/* translators: %s: number of bedrooms. */
		$chips[] = array( sprintf( __( '%s+ bedrooms', 'dara' ), dara_number( $f['beds'] ) ), $url( 'beds' ) );
	}
	if ( '' !== $f['baths'] ) {
		/* translators: %s: number of bathrooms. */
		$chips[] = array( sprintf( __( '%s+ bathrooms', 'dara' ), dara_number( $f['baths'] ) ), $url( 'baths' ) );
	}
	if ( '' !== $f['min_area'] || '' !== $f['max_area'] ) {
		$chips[] = array( $range( $f['min_area'], $f['max_area'], ' ' . __( 'm²', 'dara' ) ), remove_query_arg( 'max_area', $url( 'min_area' ) ) );
	}
	foreach ( $f['features'] as $slug ) {
		$chips[] = array( $term_name( $slug, 'property_feature' ), $url( 'features', $slug ) );
	}
	if ( ! $chips ) {
		return;
	}
	echo '<ul class="filter-chips" aria-label="' . esc_attr__( 'Active filters', 'dara' ) . '">';
	foreach ( $chips as $chip ) {
		printf(
			'<li><a class="filter-chip" href="%1$s"><span>%2$s</span>%3$s<span class="screen-reader-text">%4$s</span></a></li>',
			esc_url( $chip[1] ),
			esc_html( $chip[0] ),
			dara_icon( 'close', 14 ), // phpcs:ignore WordPress.Security.EscapeOutput
			esc_html__( 'Remove filter', 'dara' )
		);
	}
	printf( '<li><a class="filter-chips__clear" href="%1$s">%2$s</a></li>', esc_url( $base ), esc_html__( 'Clear all', 'dara' ) );
	echo '</ul>';
}

/**
 * Number of active filters (badge on the mobile "Filters" button).
 *
 * @return int
 */
function dara_filter_count() {
	if ( ! function_exists( 'dara_get_filters' ) ) {
		return 0;
	}
	$f = dara_get_filters();
	unset( $f['sort'] );
	$n = 0;
	foreach ( $f as $k => $v ) {
		if ( in_array( $k, array( 'max_price', 'max_area' ), true ) && '' !== $f[ str_replace( 'max', 'min', $k ) ] ) {
			continue;
		}
		$n += is_array( $v ) ? count( $v ) : ( '' !== $v ? 1 : 0 );
	}
	return $n;
}

/**
 * "Recently viewed" strip, filled from the visitor's browser history (localStorage).
 *
 * @param int $current Current property ID (recorded, and left out of the list).
 */
function dara_recent_section( $current = 0 ) {
	if ( ! dara_mod( 'ux_recent' ) || ! post_type_exists( 'dara_property' ) ) {
		return;
	}
	printf(
		'<section class="related recent" data-recent="%1$d" hidden><h2 class="section-title">%2$s</h2><div class="grid grid--cards" data-recent-list></div></section>',
		(int) $current,
		esc_html__( 'Recently viewed', 'dara' )
	);
}
