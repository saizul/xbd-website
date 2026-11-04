<?php
/**
 * XBD custom footer (overrides cruv/template-parts/footer/footer.php).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$xb_home = home_url( '/' );
$xb_up   = content_url( '/uploads/' );
?>
<style id="xbd-ftr-css">
#xbd-ftr{--bg:#04060f;--line:rgba(255,255,255,.1);--txt:#eaeefc;--mut:#8e98ba;--bl:#3b82ff;--vi:#8b5cf6;--ye:#ffc83d;position:relative;z-index:5;background:#04060f;color:var(--txt);font-family:'Inter',system-ui,sans-serif;overflow:hidden;border-top:1px solid var(--line)}
#xbd-ftr *{box-sizing:border-box;margin:0;padding:0}
#xbd-ftr a{color:inherit;text-decoration:none}
#xbd-ftr:before{content:'';position:absolute;inset:auto -10% -60% -10%;height:80%;background:radial-gradient(ellipse at 50% 100%,rgba(139,92,246,.28),transparent 65%);pointer-events:none}
#xbd-ftr .xf-wrap{position:relative;max-width:1240px;margin:0 auto;padding:0 24px}
#xbd-ftr .xf-cta{margin:64px auto 0;padding:34px 40px;border-radius:26px;border:1px solid rgba(139,92,246,.4);background:linear-gradient(120deg,rgba(59,130,255,.28),rgba(139,92,246,.3) 60%,rgba(8,10,24,.9));display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap;backdrop-filter:blur(10px)}
#xbd-ftr .xf-cta h3{font:700 clamp(24px,3.2vw,38px)/1.1 'Space Grotesk',sans-serif;letter-spacing:-.02em;color:#fff;max-width:640px}
#xbd-ftr .xf-cta h3 span{background:linear-gradient(110deg,#7db0ff,#c4a6ff);-webkit-background-clip:text;background-clip:text;color:transparent}
#xbd-ftr .xf-btn{display:inline-flex;align-items:center;gap:8px;padding:15px 26px;border-radius:13px;background:var(--ye);color:#1b1200;font:700 15px 'Space Grotesk',sans-serif;box-shadow:0 10px 30px rgba(255,200,61,.3);transition:transform .25s,box-shadow .25s}
#xbd-ftr .xf-btn:hover{transform:translateY(-3px);box-shadow:0 14px 40px rgba(255,200,61,.45)}
#xbd-ftr .xf-grid{display:grid;grid-template-columns:1.5fr 1fr 1fr 1.3fr;gap:44px;padding:0 0 54px;margin-top:56px}
#xbd-ftr .xf-brand img{height:44px;width:auto;display:block;margin-bottom:18px}
#xbd-ftr .xf-brand p{color:var(--mut);font-size:15px;line-height:1.7;max-width:340px}
#xbd-ftr .xf-soc{display:flex;gap:10px;margin-top:22px}
#xbd-ftr .xf-soc a{width:42px;height:42px;border-radius:12px;display:grid;place-items:center;border:1px solid var(--line);background:rgba(255,255,255,.04);transition:transform .25s,border-color .25s,background .25s}
#xbd-ftr .xf-soc a:hover{transform:translateY(-3px);border-color:var(--vi);background:rgba(139,92,246,.18)}
#xbd-ftr .xf-soc svg{width:19px;height:19px;fill:#dfe5fa}
#xbd-ftr h4{font:600 12px 'JetBrains Mono',monospace;letter-spacing:.12em;text-transform:uppercase;color:#9db8ff;margin-bottom:18px}
#xbd-ftr ul{list-style:none;display:grid;gap:11px}
#xbd-ftr li a{color:#c3cbe6;font-size:15px;transition:color .2s,padding .2s}
#xbd-ftr li a:hover{color:#fff;padding-left:4px}
#xbd-ftr .xf-contact li{display:flex;gap:11px;color:#c3cbe6;font-size:15px;line-height:1.5}
#xbd-ftr .xf-contact em{font-style:normal;flex:0 0 20px}
#xbd-ftr .xf-bottom{border-top:1px solid var(--line);padding:22px 0;display:flex;justify-content:space-between;gap:14px;flex-wrap:wrap;color:#7c86a8;font-size:13.5px;position:relative}
#xbd-ftr .xf-bottom a:hover{color:#fff}
@media(max-width:980px){#xbd-ftr .xf-grid{grid-template-columns:1fr 1fr;gap:34px}#xbd-ftr .xf-cta{padding:26px 22px}}
@media(max-width:600px){#xbd-ftr .xf-grid{grid-template-columns:1fr}#xbd-ftr .xf-wrap{padding:0 18px}}
</style>
<footer id="xbd-ftr" role="contentinfo">
  <div class="xf-wrap">
    <div class="xf-cta">
      <h3>Ready to <span>grow your brand online?</span> Let’s build your growth system.</h3>
      <a class="xf-btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get a free audit →</a>
    </div>
    <div class="xf-grid">
      <div class="xf-brand">
        <img src="<?php echo esc_url( $xb_up ); ?>2026/10/xbd-wordmark-white.png" width="196" height="44" alt="Xplore Brand Differently (XBD)" loading="lazy">
        <p>XBD — Xplore Brand Differently — is a digital growth agency in Dhaka, Bangladesh. We build marketing systems, modern websites and creative that help brands grow consistently.</p>
        <div class="xf-soc">
          <a href="https://www.facebook.com/XploreBD/" target="_blank" rel="noopener" aria-label="XBD on Facebook"><svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
          <a href="https://instagram.com/xploreBD" target="_blank" rel="noopener" aria-label="XBD on Instagram"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5.2" fill="none" stroke="#dfe5fa" stroke-width="2"/><circle cx="12" cy="12" r="4.1" fill="none" stroke="#dfe5fa" stroke-width="2"/><circle cx="17.4" cy="6.6" r="1.25"/></svg></a>
          <a href="https://www.linkedin.com/company/xplorebd/" target="_blank" rel="noopener" aria-label="XBD on LinkedIn"><svg viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>
          <a href="https://youtube.com/@xplorebranddifferently" target="_blank" rel="noopener" aria-label="XBD on YouTube"><svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
        </div>
      </div>
      <div>
        <h4>Services</h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Social Media Marketing</a></li>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">SEO &amp; Search Growth</a></li>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Website Design &amp; Development</a></li>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Graphic Design</a></li>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Branding &amp; Visual Identity</a></li>
          <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Content &amp; Video Editing</a></li>
        </ul>
      </div>
      <div>
        <h4>Company</h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">Portfolio</a></li>
          <li><a href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>">Packages &amp; pricing</a></li>
          <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About &amp; founders</a></li>
          <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
          <li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">FAQ</a></li>
          <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4>Get in touch</h4>
        <ul class="xf-contact">
          <li><em>📍</em><span>Dhaka, Bangladesh<br><small style="color:#7c86a8">Working worldwide · BD · UK · USA</small></span></li>
          <li><em>✉️</em><a href="mailto:info@x-bd.com">info@x-bd.com</a></li>
          <li><em>📞</em><a href="tel:+8801833375133">+880 1833-375133</a></li>
          <li><em>💬</em><a href="https://wa.me/8801833375133" target="_blank" rel="noopener">WhatsApp us</a></li>
        </ul>
      </div>
    </div>
    <div class="xf-bottom">
      <span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Xplore Brand Differently (XBD). All rights reserved.</span>
      <span>Made with care in Dhaka · <a href="<?php echo esc_url( $xb_home ); ?>#top">Back to top ↑</a></span>
    </div>
  </div>
</footer>
