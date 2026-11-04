<?php
/**
 * XBD single post template.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) :
	the_post();
	$pid     = get_the_ID();
	$url     = get_permalink();
	$title   = get_the_title();
	list( $content, $toc ) = xbd_build_toc( apply_filters( 'the_content', get_the_content() ) );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$mins    = max( 1, (int) ceil( $words / 200 ) );
	$cats    = get_the_category();
	$cat     = $cats ? $cats[0] : null;
	$aid     = (int) get_the_author_meta( 'ID' );
	$aname   = get_the_author_meta( 'display_name' );
	$abio    = get_the_author_meta( 'description' );
	$photo   = ( 3 === $aid ) ? content_url( '/uploads/2026/10/xbd-team-saizul-amin.jpg' ) : get_avatar_url( $aid, [ 'size' => 160 ] );
	$mod     = get_the_modified_date( 'U' ) > get_the_date( 'U' ) + DAY_IN_SECONDS;
	$related = [];
	if ( $cat ) {
		$related = get_posts( [ 'category__in' => wp_list_pluck( $cats, 'term_id' ), 'post__not_in' => [ $pid ], 'numberposts' => 3, 'post_status' => 'publish' ] );
	}
	if ( count( $related ) < 3 ) {
		$more = get_posts( [ 'post__not_in' => array_merge( [ $pid ], wp_list_pluck( $related, 'ID' ) ), 'numberposts' => 3 - count( $related ), 'post_status' => 'publish' ] );
		$related = array_merge( $related, $more );
	}
	$prev = get_previous_post(); $next = get_next_post();
	$enc  = rawurlencode( $url ); $etl = rawurlencode( $title );
	?>
<main id="xbd-post" class="xbp">
	<div class="xbp-progress" aria-hidden="true"><i></i></div>
	<header class="xbp-hero">
		<div class="xbp-bg"></div>
		<div class="xbp-wrap">
			<nav class="xbp-crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a><?php if ( $cat ) : ?> / <a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a><?php endif; ?></nav>
			<?php if ( $cats ) : ?><div class="xbp-cats"><?php foreach ( array_slice( $cats, 0, 3 ) as $c ) : ?><a class="xbp-tag" href="<?php echo esc_url( get_category_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a><?php endforeach; ?></div><?php endif; ?>
			<h1><?php echo esc_html( $title ); ?></h1>
			<?php if ( has_excerpt() ) : ?><p class="xbp-lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			<div class="xbp-meta">
				<span class="xbp-by"><img src="<?php echo esc_url( $photo ); ?>" alt="" width="40" height="40" loading="lazy"><b><?php echo esc_html( $aname ); ?></b></span>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></time>
				<?php if ( $mod ) : ?><span>Updated <?php echo esc_html( get_the_modified_date( 'j M Y' ) ); ?></span><?php endif; ?>
				<span><?php echo (int) $mins; ?> min read</span>
			</div>
		</div>
	</header>

	<div class="xbp-wrap xbp-layout">
		<article class="xbp-article">
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="xbp-feat"><?php the_post_thumbnail( 'large', [ 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => esc_attr( $title ) ] ); ?></figure>
			<?php endif; ?>
			<?php if ( $toc ) : ?>
				<details class="xbp-toc-m"><summary>In this article</summary><?php echo $toc; // phpcs:ignore ?></details>
			<?php endif; ?>
			<div class="xbp-content entry-content"><?php echo $content; // phpcs:ignore ?></div>

			<div class="xbp-share" aria-label="Share this article">
				<span>Share</span>
				<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $enc; ?>" target="_blank" rel="noopener" aria-label="Share on Facebook">Facebook</a>
				<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $enc; ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn">LinkedIn</a>
				<a href="https://twitter.com/intent/tweet?url=<?php echo $enc; ?>&text=<?php echo $etl; ?>" target="_blank" rel="noopener" aria-label="Share on X">X</a>
				<a href="https://wa.me/?text=<?php echo $etl . '%20' . $enc; ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp">WhatsApp</a>
				<button type="button" data-copy="<?php echo esc_attr( $url ); ?>">Copy link</button>
			</div>

			<aside class="xbp-author">
				<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $aname ); ?>" width="84" height="84" loading="lazy">
				<div>
					<small>Written by</small>
					<h2><?php echo esc_html( $aname ); ?></h2>
					<p><?php echo esc_html( $abio ? $abio : 'Founder & CEO of XBD (Xplore Brand Differently), a digital growth agency in Dhaka. Works on SEO, websites, AI automation and performance marketing for brands in Bangladesh, the UK and the USA.' ); ?></p>
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About the XBD team →</a>
				</div>
			</aside>

			<?php if ( $prev || $next ) : ?>
			<nav class="xbp-pn" aria-label="More articles">
				<?php if ( $prev ) : ?><a class="p" href="<?php echo esc_url( get_permalink( $prev ) ); ?>"><small>← Previous</small><b><?php echo esc_html( get_the_title( $prev ) ); ?></b></a><?php else : ?><span></span><?php endif; ?>
				<?php if ( $next ) : ?><a class="n" href="<?php echo esc_url( get_permalink( $next ) ); ?>"><small>Next →</small><b><?php echo esc_html( get_the_title( $next ) ); ?></b></a><?php endif; ?>
			</nav>
			<?php endif; ?>
		</article>

		<aside class="xbp-side">
			<div class="xbp-sticky">
				<?php if ( $toc ) : ?><nav class="xbp-toc" aria-label="Table of contents"><b>In this article</b><?php echo $toc; // phpcs:ignore ?></nav><?php endif; ?>
				<div class="xbp-cta">
					<b>Want results like this?</b>
					<p>Get a free growth audit for your website, SEO and social media.</p>
					<a class="xbp-btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get a free audit →</a>
				</div>
			</div>
		</aside>
	</div>

	<?php if ( $related ) : ?>
	<section class="xbp-related">
		<div class="xbp-wrap">
			<h2>Keep reading</h2>
			<div class="xbp-grid"><?php foreach ( $related as $r ) { xbd_post_card( $r ); } ?></div>
		</div>
	</section>
	<?php endif; ?>

	<section class="xbp-band">
		<div class="xbp-wrap"><div class="xbp-bandin">
			<h2>Ready to grow your brand online?</h2>
			<p>XBD builds the websites, search visibility and creative that bring customers. Tell us your goal and we will reply with honest advice and a clear quote.</p>
			<a class="xbp-btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get a free growth audit →</a>
		</div></div>
	</section>
</main>
<script>
(function(){var p=document.querySelector('.xbp-progress i'),a=document.querySelector('.xbp-content');
function s(){if(!p||!a)return;var r=a.getBoundingClientRect(),t=r.height-window.innerHeight*.5,v=Math.min(1,Math.max(0,(-r.top+window.innerHeight*.2)/t));p.style.width=(v*100)+'%'}
window.addEventListener('scroll',s,{passive:true});s();
document.querySelectorAll('[data-copy]').forEach(function(b){b.addEventListener('click',function(){var u=b.getAttribute('data-copy');(navigator.clipboard?navigator.clipboard.writeText(u):Promise.reject()).then(function(){b.textContent='Copied ✓'},function(){window.prompt('Copy link',u)});setTimeout(function(){b.textContent='Copy link'},2000)})});
var l=document.querySelectorAll('.xbp-toc a'),m={};l.forEach(function(x){m[x.getAttribute('href').slice(1)]=x});
if('IntersectionObserver' in window){var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){l.forEach(function(x){x.classList.remove('on')});var k=m[e.target.id];if(k)k.classList.add('on')}})},{rootMargin:'-90px 0px -70% 0px'});document.querySelectorAll('.xbp-content h2[id],.xbp-content h3[id]').forEach(function(h){io.observe(h)})}})();
</script>
<?php
endwhile;
get_footer();
