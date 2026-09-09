# Grahamisu — WooCommerce Store

E-commerce store for Grahamisu, a tiramisu dessert brand. Built on WordPress + WooCommerce with a fully custom theme.

---

## Stack

- **WordPress** — CMS and e-commerce platform
- **WooCommerce** — product, cart, and checkout management
- **Custom Theme** — `wp-content/themes/grahamisu/`
- **Tailwind CSS v4** — CSS-first config, no `tailwind.config.js`
- **Vanilla JS** — no bundler; mobile menu, carousel, qty stepper, date/time picker

---

## Local Setup

1. Clone the repo into your web server root (e.g. `/var/www/grahamisu-woocommerce/`)
2. Create a database and copy `wp-config-sample.php` → `wp-config.php`, filling in DB credentials
3. Visit `http://your-local-domain/wp-admin/install.php` to complete WordPress setup
4. Activate the **Grahamisu** theme and the **WooCommerce** plugin

---

## Theme Development

All commands run from `wp-content/themes/grahamisu/`:

```bash
npm install        # install dependencies
npm run watch      # dev — rebuilds main.css on save
npm run build      # production — minified output
```

> After any CSS change: clear WP Rocket cache (admin bar → WP Rocket → Clear Cache), then hard-refresh (`Ctrl+Shift+R`).

### CSS

- Edit `assets/css/input.css` — **never** edit `main.css` directly
- Output: `assets/css/main.css` (compiled by Tailwind, enqueued by WordPress)

### Brand Tokens

| Token | Value | Use |
|---|---|---|
| `--color-bg` | `#fdf2ec` | Cream background |
| `--color-brown` | `#61453a` | Headings, buttons |
| `--color-gold` | `#c8a56a` | Accent, button border |
| `--color-dark` | `#371613` | Nav, footer |
| `--color-rust` | `#6c290f` | ATC button, size pills |
| `--color-surface` | `#f1eff0` | Product page bg |
| `--color-muted` | `#676565` | Secondary labels |

---

## Key Files

```
wp-content/themes/grahamisu/
├── assets/css/input.css          ← Tailwind source
├── assets/js/main.js             ← Vanilla JS behaviors
├── header.php                    ← Sticky dark nav + cart icon
├── footer.php                    ← Dark footer
├── page-home.php                 ← Homepage template
├── template-parts/page/homepage/ ← Banner, products, gallery, testimonial
└── woocommerce/
    ├── content-single-product.php ← Product page layout
    ├── cart/cart.php              ← Cart with fulfillment tabs
    └── global/quantity-input.php  ← Custom qty stepper
```

---

## Design Reference

Figma file key: `2DKqXkmOHePE5JWurVSOy8`
