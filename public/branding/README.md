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

### Gate terminal (`/attendance`)

| Variable | What you see |
|----------|----------------|
| `--brand-gate-sidebar-bg` | Left student panel (defaults to `--brand-primary`) |
| `--brand-gate-title-text` | Header "PANTAS" title color |
| `--brand-gate-footer-bg` | Bottom footer bar |
| `--brand-gate-highlight` | Clock, scan line, video border |
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
| `images/branding/` | **Per-school** — logo, favicon, banner (replace on each deploy) |
| `images/system/` | App defaults — placeholder book cover, avatar |
| `images/platform/` | PANTAS marketing home page logos |

See `public/images/branding/README.md` for filenames.

## Display names (`.env`)

```env
BRANDING_SCHOOL_NAME="Your University"
BRANDING_LIBRARY_NAME="University Library"
BRANDING_SYSTEM_NAME="PANTAS"
BRANDING_PORTAL_SUBTITLE="Staff portal"
BRANDING_LOGO=images/branding/logo.png
BRANDING_FAVICON=images/branding/favicon.ico
```

After changing `.env`: `php artisan config:clear`

After changing `branding.css`: hard-refresh the browser (Ctrl+F5). The app appends a file timestamp to the CSS URL so local edits show up without clearing cache.

SVG placeholders ship with the repo so the app runs before you add real files.
