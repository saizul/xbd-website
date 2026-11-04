# X-BD Website (x-bd.com)

Source of the custom layer behind **[x-bd.com](https://x-bd.com)**, a digital agency website built on WordPress + Elementor (Hello Elementor theme).

## Structure
- `mu-plugins/xbd-layout.php`: must-use plugin that renders the custom header, footer, blog archive, single post and 404 templates
- `mu-plugins/xbd/`: templates (`header.php`, `footer.php`, `archive.php`, `single.php`, `notfound.php`), styles (`xbd.css`, `xbd-blog.css`) and script (`xbd.js`)
- `mu-plugins/xbd-seo.php`: SEO helpers (structured data / meta)
- `pages/*.json`: Elementor data for Home, About, Services, Pricing, Portfolio, FAQ, Blog and Contact pages

## Install
1. Copy `mu-plugins/*` into `wp-content/mu-plugins/`.
2. Install WordPress + Elementor + Hello Elementor theme.
3. Import page JSON into each page's `_elementor_data` meta (e.g. with WP-CLI `wp post meta update <ID> _elementor_data "$(cat pages/home.json)"`), then regenerate Elementor CSS.

## Stack
WordPress · Elementor · PHP 8.3 · Nginx (CloudPanel) · Redis object cache · Cache Enabler

**Author:** [Saizul Amin](https://saizul.com)
