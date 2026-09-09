# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

---

## Project Context

**Client:** Grahamisu (tiramisu dessert brand)
**Project:** WordPress + WooCommerce e-commerce store — separate from the Nuxt marketing site at `/var/www/grahamisu/`
**Reference project:** `/var/www/woocommerce-practice/` — dev sandbox used to build and test patterns before applying here.
**DB:** `db_grahamisu` on localhost (user: root) — `wp-config.php` is never committed.

---

## Critical Rules

1. Never write code without going through the proper workflow steps first.
2. Ask before starting each new feature or module. Build independently once approved, then present for approval before moving on.
3. One thing at a time. Test before moving on.
4. **At session start, read `KNOWN-ISSUES.md`** for project-specific bugs and workarounds. Create it if it doesn't exist yet.
5. Always bring a recommendation — don't just ask blank questions. Suggest the best approach, explain why, then ask if the owner agrees.

---

## Build Commands

All commands run from `wp-content/themes/grahamisu/`:

```bash
npm run watch     # development — rebuilds main.css on file save
npm run build     # production — minified output
```

**After any CSS change visible in browser:** clear the WP Rocket cache (admin bar → WP Rocket → Clear Cache), then hard-refresh (Ctrl+Shift+R). WP Rocket combines and caches CSS — the browser will serve stale styles until cache is cleared.

---

## Theme Architecture

Custom theme: `wp-content/themes/grahamisu/`

### CSS

- **Edit:** `assets/css/input.css` — never edit `main.css` directly.
- **Output:** `assets/css/main.css` — compiled by Tailwind, enqueued by WordPress.
- Tailwind v4 CSS-first config — no `tailwind.config.js`. Brand tokens in `@theme {}` inside `input.css`.
- `@source "../../.."` (relative to `assets/css/`) resolves to `wp-content/themes/` — scans all PHP files for utility classes.
- **CSS cascade gotcha:** Tailwind utility classes are in `@layer utilities`. Unlayered CSS (from WooCommerce or WordPress core) has higher cascade priority and can override utilities. Use higher-specificity selectors in `input.css` when fighting WooCommerce defaults (see existing `.sp` and `.woocommerce` overrides as examples).

### Fonts loaded via Google Fonts

| Token / class | Family | Used for |
|---|---|---|
| `font-primary` | Playfair Display | Brand fallback, nav |
| `font-script` | Kaushan Script | Display headings (hero, cart title) |
| `font-['Cormorant_Garamond',serif]` | Cormorant Garamond | Testimonial quote, avatar initial |
| `font-['Lato',sans-serif]` | Lato | Body copy, labels, UI |
| `font-['Inter']` | Inter | Cart column headers, nav links |

### JavaScript (`assets/js/main.js`)

Vanilla JS, no bundler. Handles five behaviors, each in its own IIFE:

1. **Mobile menu** — `.menu-toggle` toggles `.is-open` on `.site-nav`
2. **Image carousel** — `.sp__carousel` with `data-images` JSON array; thumbnails `.sp__thumb` toggle `.is-active`
3. **Qty +/− buttons** — `.sp-qty-box` wrapping WooCommerce qty input
4. **Fulfillment tabs** — `.gc-fulfillment__tab[data-tab="pickup|delivery"]` shows/hides `.gc-pickup-fields` / `.gc-delivery-fields`
5. **Date picker** — `.gc-date-picker` elements get a JS-built calendar injected (no library)
6. **Time slot picker** — `.gc-time-picker` + `.gc-time-dropdown` with `.gc-time-slot` buttons

### Template files

```
header.php                              ← sticky dark nav, cart icon with AJAX fragment
footer.php                              ← dark footer, social icons, closes .site-content div
woocommerce.php                         ← WooCommerce wrapper (uses .container — max-w 1200px)
page-home.php                           ← Template Name: Homepage; pulls 4 template parts
page.php                                ← generic page fallback
template-parts/page/homepage/
    banner.php                          ← hero section (headline + product image)
    products.php                        ← product grid (static slugs + WP uploads images)
    gallery.php                         ← gallery section with Best Seller badge
    testimonial.php                     ← single quote block
woocommerce/
    content-single-product.php          ← full product page layout (gallery + size pills + ATC)
    cart/cart.php                       ← custom cart layout with fulfillment tabs
    content-page.php                    ← generic WC page content wrapper
    global/quantity-input.php           ← custom qty stepper markup for .sp-qty-box
```

### WooCommerce template override pattern

To override any WooCommerce template, copy it from:
`wp-content/plugins/woocommerce/templates/<path>`
to:
`wp-content/themes/grahamisu/woocommerce/<path>`

### Size pill grouping (product variants)

The single product template auto-discovers size variants using **product tags prefixed `grp-`** (e.g. `grp-tiramisu`). All products sharing the same `grp-*` tag are rendered as pill links (`.sp-size-pill`) on each other's product pages. To add a new size family, tag all related products with the same `grp-*` slug in WP admin.

### Layout convention

All full-width sections use `max-w-[1440px] mx-auto` as the inner container. Horizontal padding is applied per-section (not a shared wrapper class) since each section has different insets. The `.container` class in `style.css` (max-width 1200px) is used only by `woocommerce.php` for non-custom WC pages.

---

## Brand Tokens

| Token | Value |
|---|---|
| `--color-bg` | `#fdf2ec` (cream background) |
| `--color-brown` | `#61453a` (headings, buttons) |
| `--color-gold` | `#c8a56a` (accent, button border) |
| `--color-dark` | `#371613` (nav, footer background) |
| `--color-rust` | `#6c290f` (ATC button, size pills, calendar) |
| `--color-surface` | `#f1eff0` (product page bg, products section) |
| `--color-muted` | `#676565` (secondary labels) |

---

## Design Reference

**Figma file key:** `2DKqXkmOHePE5JWurVSOy8`

Before coding any template or page, fetch the matching design using `get_design_context`. Figma is the single source of truth for layout, spacing, and colors.

---

## Error Tracking

- Project-specific bugs → `KNOWN-ISSUES.md` in this directory (create if missing)
- Cross-project patterns → `/var/www/grahamisu/CLAUDE.md` error learning section

Format for `KNOWN-ISSUES.md` entries:
```
### [Short Title]
- **Severity:** Critical / High / Medium / Low
- **Area:** Theme / Plugin / WooCommerce / DevOps
- **Symptom:** What you see when this happens
- **Root Cause:** Why it happens
- **Fix:** How to resolve it
```

---

## Custom Plugin Pattern (if needed)

Follow `woo-product-fields` at `/var/www/woocommerce-practice/wp-content/plugins/woo-product-fields/`:

- PHP: singleton class on `plugins_loaded`, Composer PSR-4 + Jetpack autoloader
- JS: `@wordpress/scripts` webpack + `@woocommerce/dependency-extraction-webpack-plugin`
- Adding a WC admin page requires matching `path` in both PHP (`Setup::register_page`) and JS (`addFilter('woocommerce_admin_pages_list')`)

```bash
npm install && npm run build   # from plugin root
npm start                      # watch mode
./vendor/bin/phpunit tests/    # PHP tests
```
