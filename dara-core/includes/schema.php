<?php
/**
 * JSON-LD structured data for properties and projects (SEO).
 *
 * @package DaraCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether Rank Math handles the page's structured data.
 *
 * @return bool
 */
function dara_core_rank_math() {
	return defined( 'RANK_MATH_VERSION' );
}

/**
 * RealEstateListing data for a property or project.
 *
 * @param int $id Post ID.
 * @return array
 */
function dara_core_listing_schema( $id ) {
	$images = array();
	foreach ( array_slice( dara_gallery_ids( $id ), 0, 5 ) as $att ) {
		$url = wp_get_attachment_image_url( $att, 'large' );
		if ( $url ) {
			$images[] = $url;
		}
	}
	$url  = get_permalink( $id );
	$desc = has_excerpt( $id ) ? get_the_excerpt( $id ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $id ) ) ), 40, '…' );

	$data = array(
		'@type'        => 'RealEstateListing',
		'@id'          => $url . '#listing',
		'name'         => get_the_title( $id ),
		'url'          => $url,
		'description'  => wp_strip_all_tags( $desc ),
		'datePosted'   => get_the_date( 'c', $id ),
		'dateModified' => get_the_modified_date( 'c', $id ),
		'image'        => $images,
	);

	$is_project = 'dara_project' === get_post_type( $id );
	$key        = $is_project ? '_dara_price_from' : '_dara_price';
	$price      = get_post_meta( $id, $key, true );
	if ( '' !== $price ) {
		$currency       = dara_currency_code();
		$data['offers'] = array(
			'@type'         => 'Offer',
			'url'           => $url,
			'price'         => (float) $price,
			'priceCurrency' => $currency,
			'availability'  => 'https://schema.org/InStock',
		);
		$period = get_post_meta( $id, '_dara_price_period', true );
		$units  = array(
			'year'  => 'ANN',
			'month' => 'MON',
			'day'   => 'DAY',
		);
		if ( '_dara_price' === $key && 'rent' === get_post_meta( $id, '_dara_purpose', true ) ) {
			$data['offers']['businessFunction'] = 'http://purl.org/goodrelations/v1#LeaseOut';
			if ( isset( $units[ $period ] ) ) {
				$data['offers']['priceSpecification'] = array(
					'@type'         => 'UnitPriceSpecification',
					'price'         => (float) $price,
					'priceCurrency' => $currency,
					'unitCode'      => $units[ $period ],
				);
			}
		}
	}

	// What is being listed: rooms and size (properties only).
	if ( ! $is_project ) {
		$about = array( '@type' => 'Accommodation' );
		$area  = get_post_meta( $id, '_dara_area', true );
		$beds  = get_post_meta( $id, '_dara_beds', true );
		$baths = get_post_meta( $id, '_dara_baths', true );
		if ( is_numeric( $area ) && $area > 0 ) {
			$about['floorSize'] = array(
				'@type'    => 'QuantitativeValue',
				'value'    => (float) $area,
				'unitCode' => 'MTK',
			);
		}
		if ( is_numeric( $beds ) && $beds > 0 ) {
			$about['numberOfBedrooms'] = (int) $beds;
		}
		if ( is_numeric( $baths ) && $baths > 0 ) {
			$about['numberOfBathroomsTotal'] = (int) $baths;
		}
		if ( count( $about ) > 1 ) {
			$data['about'] = $about;
		}
	}

	$lat     = get_post_meta( $id, '_dara_lat', true );
	$lng     = get_post_meta( $id, '_dara_lng', true );
	$address = get_post_meta( $id, '_dara_address', true );
	if ( $address || ( $lat && $lng ) ) {
		$place = array( '@type' => 'Place' );
		if ( $address ) {
			$place['address'] = $address;
		}
		if ( $lat && $lng ) {
			$place['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => (float) $lat,
				'longitude' => (float) $lng,
			);
		}
		$data['contentLocation'] = $place;
	}

	return apply_filters( 'dara_schema', $data, $id );
}

/**
 * Print JSON-LD on single property / project pages (when Rank Math is not
 * active; with Rank Math the listing joins its graph, see below).
 */
function dara_core_schema() {
	if ( ! is_singular( array( 'dara_property', 'dara_project' ) ) || ! apply_filters( 'dara_output_schema', true ) || dara_core_rank_math() ) {
		return;
	}
	$data = array( '@context' => 'https://schema.org' ) + dara_core_listing_schema( get_queried_object_id() );
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dara_core_schema' );

/**
 * Rank Math: add the listing to Rank Math's single @graph instead of printing
 * a second JSON-LD block, linked to Rank Math's WebPage. Skipped when the
 * editor already set a RealEstateListing schema in Rank Math for this post.
 *
 * @param array $data Rank Math graph pieces.
 * @return array
 */
function dara_core_rank_math_schema( $data ) {
	if ( ! is_singular( array( 'dara_property', 'dara_project' ) ) || ! apply_filters( 'dara_output_schema', true ) ) {
		return $data;
	}
	foreach ( (array) $data as $piece ) {
		if ( is_array( $piece ) && isset( $piece['@type'] ) && in_array( 'RealEstateListing', (array) $piece['@type'], true ) ) {
			return $data;
		}
	}
	$id      = get_queried_object_id();
	$listing = dara_core_listing_schema( $id );
	if ( isset( $data['WebPage'] ) && ! empty( $data['WebPage']['@id'] ) ) {
		$listing['mainEntityOfPage'] = array( '@id' => $data['WebPage']['@id'] );
	}
	if ( isset( $data['publisher'] ) && ! empty( $data['publisher']['@id'] ) ) {
		$listing['provider'] = array( '@id' => $data['publisher']['@id'] );
	}
	$data['dara-listing'] = $listing;
	return $data;
}
add_filter( 'rank_math/json_ld', 'dara_core_rank_math_schema', 20 );

/**
 * Rank Math variables for titles and descriptions:
 * %dara_price%, %dara_purpose%, %dara_city%, %dara_type%, %dara_area%, %dara_beds%.
 * Example title: %title% – %dara_price% | %sitename%
 */
function dara_core_rank_math_vars() {
	if ( ! function_exists( 'rank_math_register_var_replacement' ) ) {
		return;
	}
	$post_id = function () {
		return is_singular() ? get_queried_object_id() : get_the_ID();
	};
	$vars = array(
		'dara_price'   => array(
			__( 'Property price', 'dara-core' ),
			function () use ( $post_id ) {
				$id = $post_id();
				if ( ! $id ) {
					return '';
				}
				if ( 'dara_project' === get_post_type( $id ) ) {
					$parts = dara_price_parts( $id, '_dara_price_from' );
					return $parts['raw'] ? trim( $parts['amount'] . ' ' . $parts['unit'] ) : '';
				}
				return dara_format_price( $id );
			},
		),
		'dara_purpose' => array(
			__( 'For sale / for rent', 'dara-core' ),
			function () use ( $post_id ) {
				$purpose = get_post_meta( (int) $post_id(), '_dara_purpose', true );
				return 'rent' === $purpose ? __( 'For rent', 'dara-core' ) : ( 'sale' === $purpose ? __( 'For sale', 'dara-core' ) : '' );
			},
		),
		'dara_city'    => array(
			__( 'Property city / district', 'dara-core' ),
			function () use ( $post_id ) {
				$terms = get_the_terms( (int) $post_id(), 'property_city' );
				return $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '';
			},
		),
		'dara_type'    => array(
			__( 'Property type', 'dara-core' ),
			function () use ( $post_id ) {
				$terms = get_the_terms( (int) $post_id(), 'property_type' );
				return $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '';
			},
		),
		'dara_area'    => array(
			__( 'Area (m²)', 'dara-core' ),
			function () use ( $post_id ) {
				$area = get_post_meta( (int) $post_id(), '_dara_area', true );
				return is_numeric( $area ) ? dara_number( $area ) . ' ' . __( 'm²', 'dara-core' ) : '';
			},
		),
		'dara_beds'    => array(
			__( 'Bedrooms', 'dara-core' ),
			function () use ( $post_id ) {
				$beds = get_post_meta( (int) $post_id(), '_dara_beds', true );
				return is_numeric( $beds ) ? (string) (int) $beds : '';
			},
		),
	);
	foreach ( $vars as $name => $def ) {
		rank_math_register_var_replacement(
			$name,
			array(
				'name'        => $def[0],
				'description' => $def[0],
				'variable'    => $name,
				'example'     => '',
			),
			$def[1]
		);
	}
}
add_action( 'rank_math/vars/register_extra_replacements', 'dara_core_rank_math_vars' );
