# X-BD Website (x-bd.com)

Source of the custom layer behind **[x-bd.com](https://x-bd.com)**, a digital agency website built on WordPress + Elementor (Hello Elementor theme).

## Structure
- `mu-plugins/xbd-layout.php`: must-use plugin that renders the custom header, footer, blog archive, single post and 404 templates
- `mu-plugins/xbd/`: templates (`header.php`, `footer.php`, `archive.php`, `single.php`, `notfound.php`), styles (`xbd.css`, `xbd-blog.css`) and script (`xbd.js`)
- `mu-plugins/xbd-seo.php`: SEO helpers (structured data / meta)
- `mu-plugins/xbd-videos.php`: pulls the [XBD YouTube channel](https://www.youtube.com/@XploreBrandDifferently) feed every hour, keeps a growing video library, and renders it with the `[xbd_videos]` shortcode (home page grid, `/videos/` page with filters, featured latest upload, in-page player and VideoObject schema)
- `pages/*.json`: Elementor data for Home, About, Services, Pricing, Portfolio, Videos, FAQ, Blog and Contact pages

## Install
1. Copy `mu-plugins/*` into `wp-content/mu-plugins/`.
2. Install WordPress + Elementor + Hello Elementor theme.
3. Import page JSON into each page's `_elementor_data` meta (e.g. with WP-CLI `wp post meta update <ID> _elementor_data "$(cat pages/home.json)"`), then regenerate Elementor CSS.

## Video section

New uploads on the YouTube channel appear automatically on the home page (latest 6) and on `/videos/` (all) within an hour. The channel RSS feed only lists the latest 15 uploads, so every video seen is stored in the `xbd_yt_videos` option and the archive keeps growing. When a new video is found the Cache Enabler page cache is purged. Shorts are detected and played in a vertical player.

Shortcode options:

```
[xbd_videos limit="6"]                         latest 6 as a grid
[xbd_videos limit="all" filters="1" schema="1"] full library with category filters + VideoObject JSON-LD
[xbd_videos featured="1"]                      latest upload as a large card
```

## Stack
WordPress · Elementor · PHP 8.3 · Nginx (CloudPanel) · Redis object cache · Cache Enabler

**Author:** [Saizul Amin](https://saizul.com)
