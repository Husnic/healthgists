# Healthgists — WordPress Theme

Custom theme for Healthgists, a Nigerian health publication, built with Tailwind CSS. No page-builder or extra plugins required — this is a real WordPress theme using native Posts and Categories, plus a built-in contact form.

**How editing works:** the theme (PHP/CSS/JS files) controls layout, colors, and structure — including a set of custom blocks (Hero Slider, Category Showcase, Value Prop Grid, Process Steps) that reproduce the original bespoke design from the static prototype this theme was converted from (`../bin/generate.py`, one level up from this folder). All actual page text/images are normal WordPress content — Post/Page Title, Excerpt, Featured Image, and the block editor body — editable from wp-admin without touching code. Design changes still require editing theme files. This is a classic PHP theme, not a page-builder theme — **don't install Elementor/Divi/etc. on it**, they expect to take over the content area in a way this theme's structural pages (header, footer, hero) don't support, and you'll hit errors.

## Install on your existing WordPress host

1. **Build the assets** (compiles Tailwind CSS and the custom blocks' editor JS — required before zipping, every time you change theme code):
   ```
   cd wp-theme
   npm install   # first time only
   npm run build
   ```
2. **Zip the theme** — everything in this folder *except* `node_modules`, `src`, `package.json`, `package-lock.json`, and this `README.md` (the compiled `build/` folder must be included):
   ```
   zip -r healthgists.zip . -x "node_modules/*" "src/*" "package.json" "package-lock.json" "README.md"
   ```
3. In `wp-admin`, go to **Appearance → Themes → Add New Theme → Upload Theme**, choose `healthgists.zip`, install, then **Activate** (or **Replace** if updating).
4. Go to **Settings → Permalinks** and click **Save Changes** once (registers category archive URLs cleanly).

## First-time content setup

The fastest path — if you have WP-CLI (via SSH or a local install):

```
wp eval-file wp-content/themes/healthgists/bin/seed-site.php
```

This creates, idempotently (safe to re-run — skips anything that already exists):
- 4 categories (Preventive Care, Genetics & Family Health, Mental Health, Nutrition & Wellness), each with a description and cover photo, using the real stock photos bundled in `assets/images/covers/`.
- 4 real sample blog posts, one per category, with featured images.
- Home, About, Contact, and Blog pages, with Home's and About's content built from the theme's custom blocks (Hero Slider, Category Showcase, Value Prop Grid, Process Steps) — matching the original design exactly, and immediately editable in wp-admin from there.
- Reading settings (Home = front page, Blog = posts page).

Without WP-CLI, build these by hand in wp-admin — see "Editing content" below for what each block does; every one of them is available in the block inserter once the theme's active.

### Pages (create manually if not using the seed script)

| Title | Slug | Template | Notes |
|---|---|---|---|
| Home | `home` | `front-page.php` | Hero Slider + Category Showcase + Value Prop Grid blocks in its content; "Latest Articles" below is a live query, not editable content. |
| About | `about` | `page.php` | Story paragraphs + Process Steps + Category Showcase blocks. |
| Contact | `contact` | `page-contact.php` | Working contact form; body content above the form is optional. |
| Blog | `blog` | `home.php` | Leave content empty — this is your posts index (Settings → Reading → Posts page). |

Then **Settings → Reading**: "Your homepage displays" → A static page → Homepage: `Home`, Posts page: `Blog`.

### Categories

Each category needs a **Description** (shown on its archive banner and in Category Showcase cards) and a **Cover Image**, set via the "Cover Image" field on the category's Add/Edit screen (Posts → Categories) — not a plugin, a custom field this theme adds (see `inc/category-meta.php`).

### Custom blocks

- **Hero Slider** — the rotating banner on Home. Add Hero Slide blocks inside it; each slide either **links to a post** (auto-fills its photo, category, title, and excerpt) or is filled in manually (eyebrow, heading, subtitle, image) for a brand/announcement slide. The two navigation buttons on a manual slide and the "Read Article" button on a linked slide are fixed, not separately editable — matching the original design.
- **Category Showcase** — a grid of photo cards, one per category you pick; the photo and description come from that category's own settings, not the block.
- **Value Prop Grid** — icon + title + text cards ("why trust us" style). Pick an icon and a tint (green/blue/gold) per item.
- **Process Steps** — numbered steps, used for About's "How We Work".

## Editing brand details

- Colors/fonts: `assets/css/input.css` (the `@theme` block) — then rebuild with `npm run build` if you change it.
- Org info (email, socials, newsletter signup endpoint): `inc/template-tags.php` → `hg_org_info()`.
- Icons available to the Value Prop block: `inc/icons.php`.

## Photos

Real stock photography bundled with the theme lives in `assets/images/covers/` — used as category cover images and post featured images by the seed script. Swap any of them for real photos any time via the Featured Image / category Cover Image fields; nothing in the theme code references these filenames directly except the seed script.

## Contact form

`page-contact.php` posts to itself and is handled in `inc/contact-handler.php` using WordPress's built-in `wp_mail()` — no plugin required. It sends to the address in `hg_org_info('email')`. If your host doesn't have outgoing mail configured, install an SMTP plugin (e.g. WP Mail SMTP) and connect it to your email provider — the form code doesn't need to change.

## Local development

One-time setup, then preview anytime — no MySQL, no LocalWP/MAMP (same SQLite-based harness as the phf-ogun theme build):

```
npm install && npm run build   # from wp-theme/ — compiles CSS + blocks, required before the theme will render/edit correctly
cd ..                          # into healthgists/ (one level up from wp-theme/)
./local-dev-setup.sh       # once — downloads WordPress, sets it up (SQLite), activates the theme, runs bin/seed-site.php
./local-dev-serve.sh       # each time you want to preview — visit http://localhost:8988
```

Requires `php` and `wp-cli` (`brew install php wp-cli` if you don't have them). The theme is **symlinked** in, not copied — edit any file in `wp-theme/` and refresh the browser; CSS/block changes need `npm run watch` (or `npm run build` once) running from `wp-theme/`. wp-admin login: `admin` / `admin`.

**Cleanup when done:** `rm -rf local-site` — that's the entire throwaway WordPress install (SQLite db included). Stop the server with Ctrl+C, or `pkill -f "php -d memory_limit=512M -S localhost:8988"` if it's backgrounded.

`local-dev-setup.sh` calls `bin/seed-site.php` via `wp eval-file`, same as the live-host setup below — re-run `./local-dev-setup.sh` any time to reset the local site back to a clean seeded state.

## Reusable for other builds

Same conventions as phf-ogun's theme, which this one was built to match:
- `src/blocks/*` + `inc/blocks.php` — the "custom dynamic block" pattern: `@wordpress/scripts` auto-discovers every `src/blocks/<name>/block.json`, zero webpack config needed; each block's `edit.js` is the wp-admin editing UI and its PHP `render_callback` in `inc/blocks.php` outputs the theme's own Tailwind markup.
- `inc/category-meta.php` — the taxonomy-term-meta + `wp.media` picker pattern, useful anywhere a taxonomy needs its own image field (categories don't have one built in, unlike posts).
- `inc/contact-handler.php` — `wp_mail()` contact form pattern (nonce + honeypot), no plugin.
- `assets/js/main.js`'s mobile popover nav + hero crossfade slider — no site-specific logic, copy as-is.
- `package.json` + `assets/css/input.css` — Tailwind v4 CLI compiling straight into a theme.

Site-specific, rewrite per project: `hg_org_info()`, the `@theme` color tokens, and the actual page copy in `bin/seed-site.php`.
