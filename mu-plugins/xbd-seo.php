<?php
/**
 * Plugin Name: XBD SEO
 * Description: Structured data (ProfessionalService, FAQPage, Service list, Portfolio list) for x-bd.com.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function xbd_faq_items( $post_id ) {
	$raw = get_post_meta( $post_id, '_elementor_data', true );
	if ( ! $raw ) { return []; }
	$s = is_string( $raw ) ? $raw : wp_json_encode( $raw );
	$d = json_decode( $s, true );
	$html = isset( $d[0]['elements'][0]['settings']['html'] ) ? $d[0]['elements'][0]['settings']['html'] : '';
	preg_match_all( '#<details><summary>(.*?)</summary><p>(.*?)</p></details>#s', $html, $m, PREG_SET_ORDER );
	$out = [];
	foreach ( $m as $x ) {
		$out[] = [ '@type' => 'Question', 'name' => wp_strip_all_tags( html_entity_decode( $x[1] ) ),
			'acceptedAnswer' => [ '@type' => 'Answer', 'text' => wp_strip_all_tags( html_entity_decode( $x[2] ) ) ] ];
	}
	return $out;
}

add_action( 'wp_head', function () {
	if ( ! is_singular( 'page' ) ) { return; }
	$id   = get_queried_object_id();
	$home = home_url( '/' );
	$graph = [];
	$slug = get_post_field( 'post_name', $id );
	if ( 'services' === $slug ) {
		$list = [ 'Website Design & Development', 'SEO & Search Growth', 'Social Media Marketing', 'Graphic Design', 'Content Creation', 'Branding & Visual Identity', 'Video Editing' ];
		foreach ( $list as $n ) {
			$graph[] = [ '@type' => 'Service', 'name' => $n, 'provider' => [ '@id' => $home . '#organization' ], 'areaServed' => [ 'Bangladesh', 'United Kingdom', 'United States' ], 'serviceType' => $n ];
		}
	}
	if ( 'portfolio' === $slug ) {
		$items = [ [ 'Unique Mark', 'https://uniquemark.co.uk/' ], [ 'Green Fund Initiative', 'https://greenfundi.org/' ], [ 'Navana Petroleum', 'https://navana-petro.com/' ], [ 'Pinnacle AI Agency', 'https://pinnacleaiagency.io/' ], [ 'BCCD', 'https://bccd.org.uk/' ] ];
		$li = [];
		foreach ( $items as $i => $p ) { $li[] = [ '@type' => 'ListItem', 'position' => $i + 1, 'item' => [ '@type' => 'CreativeWork', 'name' => $p[0], 'url' => $p[1], 'creator' => [ '@id' => $home . '#organization' ] ] ]; }
		$graph[] = [ '@type' => 'ItemList', 'name' => 'XBD portfolio', 'itemListElement' => $li ];
	}
	if ( in_array( $slug, [ 'faq', 'pricing' ], true ) || is_front_page() ) {
		$faq = xbd_faq_items( $id );
		if ( $faq ) { $graph[] = [ '@type' => 'FAQPage', 'mainEntity' => $faq ]; }
	}
	if ( 'about' === $slug ) { $graph[] = [ '@type' => 'AboutPage', 'name' => 'About XBD', 'about' => [ '@id' => $home . '#organization' ] ]; }
	if ( 'contact' === $slug ) { $graph[] = [ '@type' => 'ContactPage', 'name' => 'Contact XBD', 'about' => [ '@id' => $home . '#organization' ] ]; }
	if ( ! $graph ) { return; }
	echo "<script type=\"application/ld+json\">" . wp_json_encode( [ '@context' => 'https://schema.org', '@graph' => $graph ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}, 30 );

// Enrich Yoast's Organization piece (single source of truth for the business entity).
add_filter( 'wpseo_schema_organization', function ( $d ) {
	$d['@type']       = [ 'Organization', 'ProfessionalService' ];
	$d['alternateName'] = 'XBD';
	$d['description'] = 'XBD is a digital growth agency in Dhaka, Bangladesh offering SEO, website design and development, social media marketing, graphic design, content creation, branding and video editing.';
	$d['email']       = 'info@x-bd.com';
	$d['telephone']   = '+8801833375133';
	$d['address']     = [ '@type' => 'PostalAddress', 'addressLocality' => 'Dhaka', 'addressCountry' => 'BD' ];
	$d['areaServed']  = [ 'Bangladesh', 'United Kingdom', 'United States' ];
	$d['founder']     = [
		[ '@type' => 'Person', 'name' => 'Saizul Amin', 'jobTitle' => 'Founder & CEO', 'url' => 'https://saizul.com/' ],
		[ '@type' => 'Person', 'name' => 'Anam Ahmed', 'jobTitle' => 'Co-founder & Chief Creative Officer' ],
	];
	$d['sameAs']      = [ 'https://www.facebook.com/XploreBD/', 'https://instagram.com/xploreBD', 'https://www.linkedin.com/company/xplorebd/', 'https://youtube.com/@xplorebranddifferently' ];
	$d['knowsAbout']  = [ 'SEO', 'Website design', 'Social media marketing', 'Graphic design', 'Content creation', 'Branding', 'Video editing' ];
	return $d;
} );
