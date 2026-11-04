<?php
/**
 * Plugin Name: XBD Layout
 * Description: XBD custom header/footer, shared assets and legacy-URL redirects (Hello Elementor theme).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'XBD_DIR', __DIR__ . '/xbd/' );

// Hello Elementor: turn off its own header/footer, we render ours.
add_filter( 'hello_elementor_header_footer', '__return_false' );
add_filter( 'hello_elementor_page_title', '__return_false' );

add_action( 'wp_body_open', function () { include XBD_DIR . 'header.php'; }, 1 );
add_action( 'wp_footer', function () { include XBD_DIR . 'footer.php'; }, 1 );

add_action( 'wp_enqueue_scripts', function () {
	$u = content_url( '/mu-plugins/xbd/' );
	wp_enqueue_style( 'xbd', $u . 'xbd.css', [], filemtime( XBD_DIR . 'xbd.css' ) );
	wp_enqueue_script( 'xbd', $u . 'xbd.js', [], filemtime( XBD_DIR . 'xbd.js' ), true );
}, 20 );

// Old demo URLs -> real pages.
add_action( 'template_redirect', function () {
	$p = trim( wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	$map = [
		'team' => '/about/#team', 'portfolio-grid' => '/portfolio/', 'portfolio-list' => '/portfolio/', 'portfolio-details' => '/portfolio/',
		'portfolio-details-2' => '/portfolio/', 'service-details' => '/services/', 'blog-grid' => '/blog/', 'sample-page' => '/', 'home-edit-ratul' => '/',
	];
	if ( isset( $map[ $p ] ) ) { wp_safe_redirect( home_url( $map[ $p ] ), 301 ); exit; }
	if ( preg_match( '#^(portfolio|service|project|team|careers|product)/#', $p ) ) { wp_safe_redirect( home_url( '/' ), 301 ); exit; }
}, 1 );

// Contact Form 7 inside Elementor HTML widgets, without <p>/<br> auto-formatting.
add_filter( 'wpcf7_autop_or_not', '__return_false' );
add_filter( 'elementor/widget/render_content', function ( $content, $widget ) {
	return ( 'html' === $widget->get_name() && ( false !== strpos( $content, '[contact-form-7' ) || false !== strpos( $content, '[xbd_latest_posts' ) ) ) ? do_shortcode( $content ) : $content;
}, 10, 2 );

// Send as info@x-bd.com with a matching envelope sender (needed for SPF alignment).
add_action( 'phpmailer_init', function ( $m ) {
	if ( 'info@x-bd.com' === $m->From ) { $m->Sender = 'info@x-bd.com'; }
} );

// ---------------------------------------------------------------------------
// Blog templates (single post, blog/archive, 404) + helpers
// ---------------------------------------------------------------------------
add_filter( 'template_include', function ( $t ) {
	if ( is_singular( 'post' ) ) { return XBD_DIR . 'single.php'; }
	if ( is_404() ) { return XBD_DIR . 'notfound.php'; }
	if ( is_home() || is_category() || is_tag() || is_author() || is_search() || is_date() ) { return XBD_DIR . 'archive.php'; }
	return $t;
}, 99 );

add_action( 'wp_enqueue_scripts', function () {
	if ( is_singular( 'post' ) || is_home() || is_archive() || is_search() || is_404() || is_front_page() ) {
		$f = XBD_DIR . 'xbd-blog.css';
		if ( file_exists( $f ) ) { wp_enqueue_style( 'xbd-blog', content_url( '/mu-plugins/xbd/xbd-blog.css' ), [ 'xbd' ], filemtime( $f ) ); }
	}
}, 30 );

/** Adds ids to h2/h3 and returns [ content, toc_html ]. */
function xbd_build_toc( $html ) {
	$items = []; $used = [];
	$html = preg_replace_callback( '#<h([23])([^>]*)>(.*?)</h\1>#is', function ( $m ) use ( &$items, &$used ) {
		$lvl = (int) $m[1]; $attrs = $m[2]; $inner = $m[3];
		$text = trim( wp_strip_all_tags( $inner ) );
		if ( preg_match( '#\bid=["\']([^"\']+)["\']#i', $attrs, $im ) ) { $id = $im[1]; }
		else {
			$id = sanitize_title( $text ); if ( '' === $id ) { $id = 'section'; }
			$base = $id; $n = 2; while ( isset( $used[ $id ] ) ) { $id = $base . '-' . $n++; }
			$attrs .= ' id="' . esc_attr( $id ) . '"';
		}
		$used[ $id ] = true;
		if ( 2 === $lvl && 'frequently asked questions' !== strtolower( $text ) || 2 === $lvl ) { $items[] = [ $id, $text ]; }
		return '<h' . $lvl . $attrs . '>' . $inner . '</h' . $lvl . '>';
	}, $html );
	if ( count( $items ) < 3 ) { return [ $html, '' ]; }
	$toc = '<ol>';
	foreach ( $items as $it ) { $toc .= '<li><a href="#' . esc_attr( $it[0] ) . '">' . esc_html( $it[1] ) . '</a></li>'; }
	return [ $html, $toc . '</ol>' ];
}

/** Post card used by the blog grid and "keep reading". */
function xbd_post_card( $post ) {
	$post = get_post( $post );
	if ( ! $post ) { return; }
	$cats = get_the_category( $post->ID );
	$mins = max( 1, (int) ceil( str_word_count( wp_strip_all_tags( $post->post_content ) ) / 200 ) );
	$link = get_permalink( $post );
	echo '<article class="xbp-card"><a class="im" href="' . esc_url( $link ) . '" tabindex="-1" aria-hidden="true">';
	if ( has_post_thumbnail( $post ) ) { echo get_the_post_thumbnail( $post, 'medium_large', [ 'loading' => 'lazy', 'alt' => '' ] ); }
	echo '</a><div class="bd">';
	if ( $cats ) { echo '<small>' . esc_html( $cats[0]->name ) . '</small>'; }
	echo '<h3><a href="' . esc_url( $link ) . '">' . esc_html( get_the_title( $post ) ) . '</a></h3>';
	echo '<p>' . esc_html( wp_trim_words( has_excerpt( $post ) ? $post->post_excerpt : wp_strip_all_tags( $post->post_content ), 24 ) ) . '</p>';
	echo '<div class="ft">' . esc_html( get_the_date( 'j M Y', $post ) ) . ' · ' . (int) $mins . ' min read</div></div></article>';
}

/** [xbd_latest_posts n="3"] renders the home "latest articles" section on the server (nothing if there are no posts). */
add_shortcode( 'xbd_latest_posts', function ( $atts ) {
	$a = shortcode_atts( [ 'n' => 3 ], $atts );
	$posts = get_posts( [ 'numberposts' => (int) $a['n'], 'post_status' => 'publish' ] );
	if ( ! $posts ) { return ''; }
	ob_start();
	echo '<section class="xbp xbp-latest" id="blog"><div class="xbp-wrap"><div class="xbp-head"><span class="xbp-tag">// insights</span><h2>Latest from <span>the XBD blog.</span></h2></div><div class="xbp-grid">';
	foreach ( $posts as $p ) { xbd_post_card( $p ); }
	echo '</div><div class="more"><a class="xbp-btn alt" href="' . esc_url( home_url( '/blog/' ) ) . '">All articles →</a></div></div></section>';
	return ob_get_clean();
} );
