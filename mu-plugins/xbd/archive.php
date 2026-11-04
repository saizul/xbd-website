<?php
/**
 * XBD blog / archive / search template.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$is_blog = is_home();
if ( $is_blog ) {
	$page_id = (int) get_option( 'page_for_posts' );
	$title   = $page_id ? get_the_title( $page_id ) : 'Blog';
	$desc    = 'Practical notes on SEO, websites, social media, branding and video from the XBD team in Dhaka, Bangladesh.';
} elseif ( is_search() ) {
	$title = 'Search results for “' . get_search_query() . '”'; $desc = '';
} elseif ( is_author() ) {
	$title = get_the_author_meta( 'display_name', get_query_var( 'author' ) ); $desc = get_the_author_meta( 'description', get_query_var( 'author' ) );
} else {
	$title = get_the_archive_title(); $desc = wp_strip_all_tags( get_the_archive_description() );
	$title = preg_replace( '/^[^:]+:\s*/', '', $title );
}
$cats = get_categories( [ 'hide_empty' => true, 'exclude' => [ 1 ] ] );
?>
<main id="xbd-blog" class="xbp">
	<header class="xbp-hero xbp-hero-s">
		<div class="xbp-bg"></div>
		<div class="xbp-wrap">
			<nav class="xbp-crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php echo $is_blog ? 'Blog' : '<a href="' . esc_url( home_url( '/blog/' ) ) . '">Blog</a> / ' . esc_html( $title ); ?></nav>
			<span class="xbp-tag">// insights</span>
			<h1><?php echo esc_html( $title ); ?></h1>
			<?php if ( $desc ) : ?><p class="xbp-lead"><?php echo esc_html( $desc ); ?></p><?php endif; ?>
		</div>
	</header>
	<div class="xbp-wrap">
		<?php if ( $cats && ( $is_blog || is_category() ) ) : ?>
		<nav class="xbp-chips" aria-label="Categories">
			<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="<?php echo $is_blog ? 'on' : ''; ?>">All</a>
			<?php foreach ( $cats as $c ) : ?><a href="<?php echo esc_url( get_category_link( $c ) ); ?>" class="<?php echo ( is_category( $c->term_id ) ) ? 'on' : ''; ?>"><?php echo esc_html( $c->name ); ?></a><?php endforeach; ?>
		</nav>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="xbp-grid xbp-grid-l">
				<?php while ( have_posts() ) : the_post(); xbd_post_card( get_post() ); endwhile; ?>
			</div>
			<?php
			the_posts_pagination( [ 'mid_size' => 1, 'prev_text' => '← Newer', 'next_text' => 'Older →', 'class' => 'xbp-pager' ] );
			?>
		<?php else : ?>
			<div class="xbp-empty">
				<h2>Articles are on the way</h2>
				<p>We’re preparing practical guides on SEO, web and social media. Meanwhile, tell us about your project and we’ll share advice directly.</p>
				<a class="xbp-btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Talk to us →</a>
			</div>
		<?php endif; ?>
	</div>
	<section class="xbp-band">
		<div class="xbp-wrap"><div class="xbp-bandin">
			<h2>Ready to grow your brand online?</h2>
			<p>Tell us your goal and we will reply with honest advice and a clear quote.</p>
			<a class="xbp-btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get a free growth audit →</a>
		</div></div>
	</section>
</main>
<?php
get_footer();
