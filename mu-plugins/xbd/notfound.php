<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<main id="xbd-404" class="xbp">
	<header class="xbp-hero xbp-hero-s"><div class="xbp-bg"></div><div class="xbp-wrap">
		<span class="xbp-tag">// 404</span>
		<h1>Page not found</h1>
		<p class="xbp-lead">The page you’re looking for has moved or doesn’t exist. Try one of these instead.</p>
		<p class="xbp-actions"><a class="xbp-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <a class="xbp-btn alt" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a> <a class="xbp-btn alt" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a> <a class="xbp-btn alt" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></p>
	</div></header>
</main>
<?php
get_footer();
