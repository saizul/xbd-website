<?php
/**
 * XBD custom header (overrides cruv/template-parts/header/header.php).
 * Self-contained: markup + scoped CSS + tiny JS. Brand: Xplore Brand Differently.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$xb_home = home_url( '/' );
$xb_up   = content_url( '/uploads/' );
if ( ! function_exists( 'xbd_cur' ) ) {
	function xbd_cur( $p ) {
		$r = '/' . trim( wp_parse_url( add_query_arg( [] ), PHP_URL_PATH ), '/' ) . '/';
		return ( strpos( $r, $p ) === 0 ) ? ' class="on"' : '';
	}
}
?>
<style id="xbd-fonts">
@font-face{font-family:'Space Grotesk';font-style:normal;font-weight:300 700;font-display:swap;src:url(<?php echo esc_url( $xb_up ); ?>xbd-fonts/SpaceGrotesk-400.woff2) format('woff2')}
@font-face{font-family:'Inter';font-style:normal;font-weight:100 900;font-display:swap;src:url(<?php echo esc_url( $xb_up ); ?>xbd-fonts/Inter-400.woff2) format('woff2')}
@font-face{font-family:'JetBrains Mono';font-style:normal;font-weight:100 800;font-display:swap;src:url(<?php echo esc_url( $xb_up ); ?>xbd-fonts/JetBrainsMono-400.woff2) format('woff2')}
</style>
<style id="xbd-hdr-css">
#xbd-hdr{--bg:#04060f;--line:rgba(255,255,255,.12);--txt:#eaeefc;--mut:#8e98ba;--bl:#3b82ff;--vi:#8b5cf6;--ye:#ffc83d;position:fixed;top:14px;left:0;right:0;z-index:99990;font-family:'Inter',system-ui,sans-serif;pointer-events:none}
body.admin-bar #xbd-hdr{top:46px}
@media(min-width:783px){body.admin-bar #xbd-hdr{top:46px}}
#xbd-hdr nav a.on{color:#fff;background:rgba(139,92,246,.2)}#xbd-hdr .xh-mob a.on{color:#fff;background:rgba(139,92,246,.2)}
#xbd-hdr *{box-sizing:border-box;margin:0;padding:0}
#xbd-hdr a{text-decoration:none;color:inherit}
#xbd-hdr .xh-bar{pointer-events:auto;max-width:1240px;margin:0 auto;padding:10px 12px 10px 20px;display:flex;align-items:center;gap:22px;border-radius:18px;background:rgba(6,9,24,.62);border:1px solid var(--line);backdrop-filter:blur(16px) saturate(140%);-webkit-backdrop-filter:blur(16px) saturate(140%);box-shadow:0 12px 40px rgba(0,0,0,.35);transition:background .3s,border-color .3s}
#xbd-hdr.xh-solid .xh-bar{background:rgba(5,7,18,.9);border-color:rgba(139,92,246,.35)}
#xbd-hdr .xh-wrap{padding:0 16px}
#xbd-hdr .xh-logo{display:flex;align-items:center;flex:0 0 auto}
#xbd-hdr .xh-logo img{height:34px;width:auto;display:block}
#xbd-hdr nav{display:flex;gap:4px;margin-left:auto}
#xbd-hdr nav a{position:relative;padding:9px 14px;border-radius:11px;font:500 14.5px 'Inter',sans-serif;color:#cfd6ee;transition:color .2s,background .2s}
#xbd-hdr nav a:hover{color:#fff;background:rgba(255,255,255,.07)}
#xbd-hdr .xh-cta{flex:0 0 auto;display:inline-flex;align-items:center;gap:8px;padding:11px 20px;border-radius:12px;background:var(--ye);color:#1b1200;font:700 14px 'Space Grotesk',sans-serif;box-shadow:0 8px 26px rgba(255,200,61,.28);transition:transform .25s,box-shadow .25s}
#xbd-hdr .xh-cta:hover{transform:translateY(-2px);box-shadow:0 12px 34px rgba(255,200,61,.45)}
#xbd-hdr .xh-burger{display:none;pointer-events:auto;margin-left:auto;width:44px;height:44px;border-radius:12px;border:1px solid var(--line);background:rgba(255,255,255,.05);cursor:pointer;position:relative}
#xbd-hdr .xh-burger i{position:absolute;left:12px;right:12px;height:2px;border-radius:2px;background:#fff;transition:transform .3s,opacity .3s,top .3s}
#xbd-hdr .xh-burger i:nth-child(1){top:15px}#xbd-hdr .xh-burger i:nth-child(2){top:21px}#xbd-hdr .xh-burger i:nth-child(3){top:27px}
#xbd-hdr.xh-open .xh-burger i:nth-child(1){top:21px;transform:rotate(45deg)}
#xbd-hdr.xh-open .xh-burger i:nth-child(2){opacity:0}
#xbd-hdr.xh-open .xh-burger i:nth-child(3){top:21px;transform:rotate(-45deg)}
#xbd-hdr .xh-mob{pointer-events:auto;display:none;max-width:1240px;margin:8px auto 0;padding:10px;border-radius:18px;background:rgba(5,7,18,.96);border:1px solid var(--line);backdrop-filter:blur(16px);box-shadow:0 20px 60px rgba(0,0,0,.5)}
#xbd-hdr .xh-mob a{display:block;padding:14px 16px;border-radius:12px;font:500 16px 'Inter',sans-serif;color:#dfe5fa}
#xbd-hdr .xh-mob a:hover{background:rgba(255,255,255,.06)}
#xbd-hdr .xh-mob .xh-cta{display:flex;justify-content:center;margin-top:8px;color:#1b1200}
@media(max-width:980px){#xbd-hdr nav,#xbd-hdr .xh-bar>.xh-cta{display:none}#xbd-hdr .xh-burger{display:block}#xbd-hdr.xh-open .xh-mob{display:block}}
html{scroll-behavior:smooth;scroll-padding-top:96px}
@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}}
</style>
<div id="xbd-hdr" role="banner">
  <div class="xh-wrap">
    <div class="xh-bar">
      <a class="xh-logo" href="<?php echo esc_url( $xb_home ); ?>" aria-label="Xplore Brand Differently — home"><img src="<?php echo esc_url( $xb_up ); ?>2026/10/xbd-wordmark-white.png" width="152" height="34" alt="Xplore Brand Differently (XBD)"></a>
      <nav aria-label="Main">
<a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"<?php echo xbd_cur( '/services/' ); ?>>Services</a>
<a href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>"<?php echo xbd_cur( '/portfolio/' ); ?>>Portfolio</a>
<a href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>"<?php echo xbd_cur( '/pricing/' ); ?>>Pricing</a>
<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"<?php echo xbd_cur( '/about/' ); ?>>About</a>
<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"<?php echo xbd_cur( '/blog/' ); ?>>Blog</a>
</nav>
      <a class="xh-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get a free audit →</a>
      <button class="xh-burger" type="button" aria-label="Open menu" aria-expanded="false"><i></i><i></i><i></i></button>
    </div>
    <div class="xh-mob">
<a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"<?php echo xbd_cur( '/services/' ); ?>>Services</a>
<a href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>"<?php echo xbd_cur( '/portfolio/' ); ?>>Portfolio</a>
<a href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>"<?php echo xbd_cur( '/pricing/' ); ?>>Pricing</a>
<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"<?php echo xbd_cur( '/about/' ); ?>>About</a>
<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"<?php echo xbd_cur( '/blog/' ); ?>>Blog</a>
<a class="xh-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get a free audit →</a>
    </div>
  </div>
</div>
<script>
(function(){var h=document.getElementById('xbd-hdr');if(!h)return;var b=h.querySelector('.xh-burger');
function s(){h.classList.toggle('xh-solid',window.scrollY>30)}s();window.addEventListener('scroll',s,{passive:true});
b.addEventListener('click',function(){var o=h.classList.toggle('xh-open');b.setAttribute('aria-expanded',o?'true':'false')});
h.querySelectorAll('.xh-mob a').forEach(function(a){a.addEventListener('click',function(){h.classList.remove('xh-open');b.setAttribute('aria-expanded','false')})});})();
</script>
