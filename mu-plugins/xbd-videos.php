<?php
/**
 * Plugin Name: XBD Videos
 * Description: Pulls videos from the XBD YouTube channel automatically and renders them on the home page and the /videos/ page.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

const XBD_YT_CHANNEL = 'UCQ60Fpv8bePdYhCoQitvAdA';
const XBD_YT_HANDLE  = 'https://www.youtube.com/@XploreBrandDifferently';
const XBD_YT_OPT     = 'xbd_yt_videos';

/* ---------------------------------------------------------------------------
 * Data: the channel RSS feed lists the latest 15 uploads. Every video we see is
 * merged into a stored library, so the archive keeps growing past 15.
 * ------------------------------------------------------------------------- */
function xbd_yt_refresh( $force = false ) {
	if ( ! $force && get_transient( 'xbd_yt_fresh' ) ) { return; }
	set_transient( 'xbd_yt_fresh', 1, HOUR_IN_SECONDS );

	$res = wp_remote_get( 'https://www.youtube.com/feeds/videos.xml?channel_id=' . XBD_YT_CHANNEL, [ 'timeout' => 8 ] );
	if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) { return; }
	$body = wp_remote_retrieve_body( $res );
	if ( ! $body ) { return; }

	libxml_use_internal_errors( true );
	$xml = simplexml_load_string( $body );
	if ( ! $xml ) { return; }
	$xml->registerXPathNamespace( 'a', 'http://www.w3.org/2005/Atom' );
	$xml->registerXPathNamespace( 'yt', 'http://www.youtube.com/xml/schemas/2015' );
	$xml->registerXPathNamespace( 'media', 'http://search.yahoo.com/mrss/' );

	$lib   = get_option( XBD_YT_OPT, [] );
	$lib   = is_array( $lib ) ? $lib : [];
	$added = false;

	foreach ( $xml->xpath( '//a:entry' ) as $e ) {
		$yt  = $e->children( 'http://www.youtube.com/xml/schemas/2015' );
		$id  = (string) $yt->videoId;
		if ( ! preg_match( '/^[\w-]{11}$/', $id ) ) { continue; }
		$med   = $e->children( 'http://search.yahoo.com/mrss/' )->group;
		$desc  = $med ? (string) $med->children( 'http://search.yahoo.com/mrss/' )->description : '';
		$item  = [
			'id'    => $id,
			'title' => html_entity_decode( (string) $e->title, ENT_QUOTES, 'UTF-8' ),
			'date'  => (string) $e->published,
			'desc'  => wp_trim_words( $desc, 40, '…' ),
		];
		if ( ! isset( $lib[ $id ] ) ) {
			$item['short'] = xbd_yt_is_short( $id );
			$added         = true;
		} else {
			$item['short'] = ! empty( $lib[ $id ]['short'] );
		}
		$lib[ $id ] = $item;
	}

	uasort( $lib, function ( $a, $b ) { return strcmp( $b['date'], $a['date'] ); } );
	update_option( XBD_YT_OPT, $lib, false );

	// New upload: purge page cache so home and /videos/ show it straight away.
	if ( $added ) {
		do_action( 'cache_enabler_clear_complete_cache' );
	}
}

/** YouTube serves /shorts/ID with 200 only for Shorts; regular videos redirect. */
function xbd_yt_is_short( $id ) {
	$r = wp_remote_head( 'https://www.youtube.com/shorts/' . $id, [ 'redirection' => 0, 'timeout' => 6, 'user-agent' => 'curl/8.5.0', 'headers' => [ 'Cookie' => 'SOCS=CAI' ] ] );
	return ! is_wp_error( $r ) && 200 === wp_remote_retrieve_response_code( $r );
}

function xbd_yt_videos() {
	xbd_yt_refresh();
	$lib = get_option( XBD_YT_OPT, [] );
	return is_array( $lib ) ? array_values( $lib ) : [];
}

// Hourly background refresh.
add_action( 'xbd_yt_cron', function () { xbd_yt_refresh( true ); } );
add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'xbd_yt_cron' ) ) {
		wp_schedule_event( time() + 60, 'hourly', 'xbd_yt_cron' );
	}
} );

/* ---------------------------------------------------------------------------
 * Presentation helpers
 * ------------------------------------------------------------------------- */
function xbd_yt_split_title( $t ) {
	$t     = trim( preg_replace( '/\s*#\S+/u', '', $t ) );
	$parts = array_map( 'trim', explode( '|', $t, 2 ) );
	return [ $parts[0], isset( $parts[1] ) ? $parts[1] : '' ];
}

function xbd_yt_cat( $v ) {
	if ( ! empty( $v['short'] ) ) { return 'short'; }
	return preg_match( '/promo|explainer|launch|saas|product/i', $v['title'] ) ? 'promo' : 'brand';
}

function xbd_yt_card( $v, $eager = false ) {
	list( $title, $kicker ) = xbd_yt_split_title( $v['title'] );
	$cat    = xbd_yt_cat( $v );
	$labels = [ 'promo' => 'Product promo', 'brand' => 'Brand story', 'short' => 'Short' ];
	$date   = $v['date'] ? date_i18n( 'M Y', strtotime( $v['date'] ) ) : '';
	$thumb  = 'https://i.ytimg.com/vi/' . $v['id'] . '/hqdefault.jpg';
	ob_start(); ?>
	<button class="xv-card" type="button" data-id="<?php echo esc_attr( $v['id'] ); ?>" data-short="<?php echo $cat === 'short' ? '1' : '0'; ?>" data-c="<?php echo esc_attr( $cat ); ?>" aria-label="<?php echo esc_attr( 'Play video: ' . $title ); ?>">
		<span class="xv-th">
			<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" width="480" height="360" loading="<?php echo $eager ? 'eager' : 'lazy'; ?>" decoding="async">
			<span class="xv-play" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M8 5.5v13l11-6.5z"/></svg></span>
			<span class="xv-badge"><?php echo esc_html( $labels[ $cat ] ); ?></span>
		</span>
		<span class="xv-meta">
			<small><?php echo esc_html( $kicker ? $kicker : 'Xplore Brand Differently' ); ?><?php echo $date ? ' · ' . esc_html( $date ) : ''; ?></small>
			<strong><?php echo esc_html( $title ); ?></strong>
		</span>
	</button>
	<?php
	return ob_get_clean();
}

/* ---------------------------------------------------------------------------
 * Shortcode: [xbd_videos limit="6"]  |  [xbd_videos limit="all" filters="1" schema="1"]
 *            [xbd_videos featured="1"]  (latest video as a large card, for the page hero)
 * ------------------------------------------------------------------------- */
add_shortcode( 'xbd_videos', function ( $atts ) {
	$a      = shortcode_atts( [ 'limit' => '6', 'filters' => '0', 'schema' => '0', 'featured' => '0' ], $atts );
	$videos = xbd_yt_videos();
	if ( ! $videos ) {
		return '<p class="xv-empty">Videos are loading — please check back shortly or visit our <a href="' . esc_url( XBD_YT_HANDLE ) . '" target="_blank" rel="noopener">YouTube channel</a>.</p>';
	}
	xbd_yt_assets();

	if ( '1' === $a['featured'] ) {
		$v = $videos[0];
		return '<div class="xv-feat xv-wrap">' . xbd_yt_card( $v, true ) . '<span class="xv-new">● Latest upload</span></div>';
	}

	$list = 'all' === $a['limit'] ? $videos : array_slice( $videos, 0, max( 1, (int) $a['limit'] ) );
	$out  = '<div class="xv-wrap">';

	if ( '1' === $a['filters'] ) {
		$counts = [ 'all' => count( $list ), 'promo' => 0, 'brand' => 0, 'short' => 0 ];
		foreach ( $list as $v ) { $counts[ xbd_yt_cat( $v ) ]++; }
		$names = [ 'all' => 'All videos', 'promo' => 'Product promos', 'brand' => 'Brand stories', 'short' => 'Shorts' ];
		$out  .= '<div class="xv-filters" role="tablist">';
		foreach ( $names as $k => $n ) {
			if ( 'all' !== $k && ! $counts[ $k ] ) { continue; }
			$out .= '<button type="button" class="xv-f' . ( 'all' === $k ? ' on' : '' ) . '" data-f="' . esc_attr( $k ) . '">' . esc_html( $n ) . ' <span>' . (int) $counts[ $k ] . '</span></button>';
		}
		$out .= '</div>';
	}

	$out .= '<div class="xv-grid">';
	foreach ( $list as $i => $v ) { $out .= xbd_yt_card( $v, $i < 3 ); }
	$out .= '</div></div>';

	if ( '1' === $a['schema'] ) {
		$items = [];
		foreach ( $list as $i => $v ) {
			list( $title ) = xbd_yt_split_title( $v['title'] );
			$items[]       = [
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'item'     => [
					'@type'        => 'VideoObject',
					'name'         => $title,
					'description'  => $v['desc'] ? $v['desc'] : $title . ' — a video by XBD (Xplore Brand Differently).',
					'thumbnailUrl' => 'https://i.ytimg.com/vi/' . $v['id'] . '/hqdefault.jpg',
					'uploadDate'   => $v['date'],
					'embedUrl'     => 'https://www.youtube.com/embed/' . $v['id'],
					'contentUrl'   => 'https://www.youtube.com/watch?v=' . $v['id'],
				],
			];
		}
		$out .= '<script type="application/ld+json">' . wp_json_encode( [ '@context' => 'https://schema.org', '@type' => 'ItemList', 'itemListElement' => $items ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
	}
	return $out;
} );

// Let the shortcode run inside Elementor HTML widgets (same approach as the other XBD shortcodes).
add_filter( 'elementor/widget/render_content', function ( $content, $widget ) {
	return ( 'html' === $widget->get_name() && false !== strpos( $content, '[xbd_videos' ) ) ? do_shortcode( $content ) : $content;
}, 11, 2 );

/* ---------------------------------------------------------------------------
 * Assets (inline, printed once, only on pages that use the shortcode)
 * ------------------------------------------------------------------------- */
function xbd_yt_assets() {
	static $done = false;
	if ( $done ) { return; }
	$done = true;
	add_action( 'wp_footer', 'xbd_yt_print_assets', 5 );
}

function xbd_yt_print_assets() { ?>
<style id="xbd-videos-css">
#xbd-root .xv-wrap{--xv-line:rgba(255,255,255,.1);--xv-card:rgba(255,255,255,.045);--xv-mut:#8e98ba;--xv-bl:#3b82ff;--xv-vi:#8b5cf6;--xv-cy:#22d3ee;--xv-ye:#ffc83d;position:relative}
#xbd-root .xv-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:22px}
#xbd-root .xv-card{all:unset;box-sizing:border-box;display:flex;flex-direction:column;cursor:pointer;border:1px solid var(--xv-line);background:linear-gradient(160deg,rgba(255,255,255,.06),rgba(255,255,255,.02));border-radius:20px;overflow:hidden;transition:transform .35s cubic-bezier(.2,.7,.2,1),border-color .35s,box-shadow .35s;-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}
#xbd-root .xv-card:hover,#xbd-root .xv-card:focus-visible{transform:translateY(-6px);border-color:rgba(139,92,246,.55);box-shadow:0 24px 60px -24px rgba(59,130,255,.55)}
#xbd-root .xv-card:focus-visible{outline:2px solid var(--xv-cy);outline-offset:3px}
#xbd-root .xv-th{position:relative;display:block;aspect-ratio:16/9;overflow:hidden;background:#0b1024}
#xbd-root .xv-th img{width:100%;height:100%;object-fit:cover;transform:scale(1.02);transition:transform .6s cubic-bezier(.2,.7,.2,1),filter .4s;filter:saturate(1.05)}
#xbd-root .xv-card:hover .xv-th img{transform:scale(1.08);filter:saturate(1.2) brightness(.85)}
#xbd-root .xv-th:after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,transparent 45%,rgba(4,6,15,.75));pointer-events:none}
#xbd-root .xv-play{position:absolute;left:50%;top:50%;width:64px;height:64px;margin:-32px 0 0 -32px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--xv-bl),var(--xv-vi));box-shadow:0 0 0 8px rgba(59,130,255,.18),0 12px 30px rgba(0,0,0,.45);z-index:2;transition:transform .35s,box-shadow .35s}
#xbd-root .xv-play svg{width:26px;height:26px;fill:#fff;margin-left:3px}
#xbd-root .xv-card:hover .xv-play{transform:scale(1.12);box-shadow:0 0 0 12px rgba(139,92,246,.22),0 16px 40px rgba(0,0,0,.5)}
#xbd-root .xv-badge{position:absolute;left:14px;top:14px;z-index:2;font:500 11px 'JetBrains Mono',monospace;letter-spacing:.06em;text-transform:uppercase;color:#dfe7ff;background:rgba(4,6,15,.65);border:1px solid var(--xv-line);padding:5px 10px;border-radius:99px;-webkit-backdrop-filter:blur(6px);backdrop-filter:blur(6px)}
#xbd-root .xv-meta{display:flex;flex-direction:column;gap:7px;padding:18px 20px 22px}
#xbd-root .xv-meta small{color:var(--xv-mut);font-size:12.5px;letter-spacing:.01em}
#xbd-root .xv-meta strong{font-family:'Space Grotesk',sans-serif;font-size:18px;line-height:1.3;color:#fff;font-weight:600;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
#xbd-root .xv-filters{display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin:0 0 34px}
#xbd-root .xv-f{all:unset;cursor:pointer;font-size:14px;color:#cdd5f3;border:1px solid var(--xv-line);background:var(--xv-card);padding:9px 16px;border-radius:99px;transition:.25s}
#xbd-root .xv-f span{opacity:.6;margin-left:4px}
#xbd-root .xv-f:hover{border-color:rgba(59,130,255,.5)}
#xbd-root .xv-f.on{color:#fff;background:linear-gradient(135deg,rgba(59,130,255,.35),rgba(139,92,246,.35));border-color:rgba(139,92,246,.6)}
#xbd-root .xv-card.xv-hide{display:none}
#xbd-root .xv-feat{position:relative}
#xbd-root .xv-feat .xv-card{border-radius:24px}
#xbd-root .xv-feat .xv-meta strong{font-size:22px}
#xbd-root .xv-feat .xv-play{width:84px;height:84px;margin:-42px 0 0 -42px}
#xbd-root .xv-new{position:absolute;right:16px;top:16px;z-index:3;font:600 12px 'Inter',sans-serif;color:#04060f;background:var(--xv-ye);padding:6px 12px;border-radius:99px;box-shadow:0 8px 24px rgba(255,200,61,.35)}
#xbd-root .xv-empty{text-align:center;color:#8e98ba}
.xv-modal{position:fixed;inset:0;z-index:99999;display:none;align-items:center;justify-content:center;padding:24px;background:rgba(2,3,10,.86);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px)}
.xv-modal.on{display:flex;animation:xvfade .25s ease}
@keyframes xvfade{from{opacity:0}to{opacity:1}}
.xv-box{position:relative;width:min(1100px,100%);aspect-ratio:16/9;border-radius:18px;overflow:hidden;background:#000;box-shadow:0 40px 120px rgba(0,0,0,.6),0 0 0 1px rgba(255,255,255,.08)}
.xv-box.short{width:auto;height:min(86vh,900px);aspect-ratio:9/16}
.xv-box iframe{position:absolute;inset:0;width:100%;height:100%;border:0}
.xv-x{all:unset;position:absolute;top:18px;right:22px;z-index:2;width:44px;height:44px;border-radius:50%;display:grid;place-items:center;cursor:pointer;color:#fff;font-size:26px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18)}
.xv-x:hover{background:rgba(255,255,255,.2)}
@media (max-width:1024px){
#xbd-root .xv-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media (max-width:640px){
#xbd-root .xv-grid{grid-template-columns:1fr;gap:16px}
#xbd-root .xv-meta strong{font-size:16.5px}
.xv-modal{padding:10px}
}
@media (prefers-reduced-motion:reduce){
#xbd-root .xv-card,#xbd-root .xv-th img,#xbd-root .xv-play{transition:none}
.xv-modal.on{animation:none}
}
</style>
<div class="xv-modal" id="xv-modal" role="dialog" aria-modal="true" aria-label="Video player"><button class="xv-x" type="button" aria-label="Close video">×</button><div class="xv-box"></div></div>
<script id="xbd-videos-js">
(function(){
  var m=document.getElementById('xv-modal'); if(!m) return;
  var box=m.querySelector('.xv-box'), last=null;
  function open(id,short){
    box.className='xv-box'+(short?' short':'');
    box.innerHTML='<iframe src="https://www.youtube-nocookie.com/embed/'+id+'?autoplay=1&rel=0&modestbranding=1&playsinline=1" title="YouTube video player" referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
    m.classList.add('on'); document.documentElement.style.overflow='hidden'; m.querySelector('.xv-x').focus();
  }
  function close(){ m.classList.remove('on'); box.innerHTML=''; document.documentElement.style.overflow=''; if(last) last.focus(); }
  document.addEventListener('click',function(e){
    var c=e.target.closest('.xv-card'); if(c){ last=c; open(c.getAttribute('data-id'),c.getAttribute('data-short')==='1'); return; }
    if(e.target===m||e.target.closest('.xv-x')) close();
    var f=e.target.closest('.xv-f'); if(f){
      var w=f.closest('.xv-wrap'), k=f.getAttribute('data-f');
      w.querySelectorAll('.xv-f').forEach(function(b){b.classList.toggle('on',b===f);});
      w.querySelectorAll('.xv-card').forEach(function(card){card.classList.toggle('xv-hide',k!=='all'&&card.getAttribute('data-c')!==k);});
    }
  });
  document.addEventListener('keydown',function(e){ if(e.key==='Escape'&&m.classList.contains('on')) close(); });
})();
</script>
<?php }
