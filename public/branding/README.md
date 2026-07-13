# School branding

Per-school **colors/fonts** and **image assets** are configured outside Git so each deployment can differ.

## One file for all colors

Edit **`public/branding/branding.css`** (copy from `branding.css.example`). That single file controls:

- Staff sidebar (background, active tab, hover, yellow accent bar)
- React admin shell (cards, buttons, headers)
- Legacy Blade pages (kiosk, OPAC, tables, auth)
- Bootstrap theme variables

Names and logos use **`.env`** (see below).

```bash
cp public/branding/branding.css.example public/branding/branding.css
```

```env
BRANDING_CSS=branding/branding.css
```

### Core palette (start here)

| Variable | Purpose |
|----------|---------|
| `--brand-primary` | Gold / yellow highlights |
| `--brand-accent` | Green |
| `--brand-blue` | Blue actions & active nav |
| `--brand-green-dark` | Dark green (hovers, borders) |

### Sidebar

| Variable | What you see |
|----------|----------------|
| `--brand-sidebar-bg` | Sidebar background (green) |
| `--brand-sidebar-portal-text` | "STAFF PORTAL" subtitle (defaults to `--brand-text-light`) |
| `--brand-sidebar-role-text` | Administrator / Staff badge (defaults to `--brand-text-light`) |
| `--brand-sidebar-active-bg` | Active menu tab fill (blue) |
| `--brand-sidebar-highlight` | Yellow left bar on active tab |
| `--brand-sidebar-active-text` | Text on active tab |
| `--brand-sidebar-border` | Dividers |

Hover is derived automatically from the active color — no separate variable needed.

### Main content (catalog tables, cards)

Separate from sidebar/gate colors — keeps table text readable on white backgrounds.

| Variable | What you see |
|----------|----------------|
| `--brand-shell-text` | Table body text, titles, authors (defaults to `--brand-text-dark`) |
| `--brand-shell-muted-text` | "Showing 2229 titles…", hints |
| `--brand-shell-action-text` | View / Actions button labels in tables |
| `--brand-shell-filter-label` | Catalog filter sidebar labels (can use `--brand-blue`) |
| `--brand-shell-button-bg` | Filled buttons, Available badge, active pagination |
| `--brand-shell-button-text` | Text on filled buttons and badges |

### Gate terminal (`/attendance`)

| Variable | What you see |
|----------|----------------|
| `--brand-gate-sidebar-bg` | Left student panel (defaults to `--brand-primary`) |
| `--brand-gate-title-text` | Header "PANTAS" title color |
| `--brand-gate-footer-bg` | Bottom footer bar |
| `--brand-gate-highlight` | Scan line, video border |
| `--brand-gate-clock-text` | Live clock on gate sidebar (must contrast with sidebar bg) |
| `--brand-gate-modal-button-bg` | Section picker buttons |

Gate terminal uses **`--brand-primary`**, not `--brand-accent`. Staff sidebar uses `--brand-accent`.

### Staff sign-in (`/login`)

| Variable | What you see |
|----------|----------------|
| `--brand-auth-signin-bg` | Sign in button (defaults to `--brand-accent`) |
| `--brand-auth-signin-hover-bg` | Sign in button on hover |
| `--brand-auth-signin-text` | Sign in button label |

### Typography

| Variable | Purpose |
|----------|---------|
| `--brand-font-family` | Body text, forms, buttons, tables |
| `--brand-font-family-heading` | Headings and school name |
| `--brand-font-family-mono` | Barcodes, code |

`public/css/brand-typography.css` applies fonts across staff pages, kiosk, OPAC, and Blade screens.

## Image assets

All paths live under `public/images/`:

| Folder | Purpose |
|--------|---------|
| `images/branding/` | **School** — staff sidebar, login, OPAC, favicon, banner |
| `images/platform/` | **Platform home** (`/`) — PANTAS marketing logos (set `BRANDING_PLATFORM_*` in `.env`) |
| `images/system/` | App defaults — placeholder book cover, avatar |

See `public/images/branding/README.md` and `public/images/platform/README.md`.

## Display names & logos (`.env`)

Use **two logo sets** when the public home page should show PANTAS marketing art and the staff app should show the school mark:

```env
# School
BRANDING_SCHOOL_NAME="Misamis Oriental State University - Main"
BRANDING_LIBRARY_NAME="MOIST Library"
BRANDING_LOGO=images/branding/logo.png
BRANDING_LOGO_LANDSCAPE=images/branding/logo-landscape.png
BRANDING_FAVICON=images/branding/favicon.ico

# Platform home page (/)
BRANDING_PLATFORM_LOGO=images/platform/logo.png
BRANDING_PLATFORM_LOGO_LANDSCAPE=images/platform/logo-landscape.png
BRANDING_PLATFORM_VENDOR_LOGO=images/platform/vendor-logo.png
```

After changing `.env`: `php artisan config:clear`

After changing `branding.css`: hard-refresh the browser (Ctrl+F5). The app appends a file timestamp to the CSS URL so local edits show up without clearing cache.

Default asset paths expect **PNG/JPG** filenames (see `config/branding.php`). SVG placeholders ship with the repo for local dev; on deploy, use the default names or override in `.env`.
