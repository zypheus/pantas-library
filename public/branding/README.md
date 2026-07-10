# School branding

Per-school **colors/fonts** and **image assets** are configured outside Git so each deployment can differ.

## Colors & fonts

`branding.css` is **not** stored in Git.

```bash
cp public/branding/branding.css.example public/branding/branding.css
```

Edit `public/branding/branding.css`, or set in `.env`:

```env
BRANDING_CSS=branding/branding.css
```

### Typography variables

| Variable | Purpose |
|----------|---------|
| `--brand-font-family` | Body text, forms, buttons, tables |
| `--brand-font-family-heading` | Headings and school name |
| `--brand-font-family-mono` | Barcodes, code |

`public/css/brand-typography.css` applies these across staff pages, kiosk, OPAC, and Blade screens.

## Image assets

All paths live under `public/images/`:

| Folder | Purpose |
|--------|---------|
| `images/branding/` | **Per-school** — logo, favicon, banner (replace on each deploy) |
| `images/system/` | App defaults — placeholder book cover, avatar |
| `images/platform/` | PANTAS marketing home page logos |

See `public/images/branding/README.md` for filenames.

Override any path in `.env`:

```env
BRANDING_SCHOOL_NAME="Your University"
BRANDING_LIBRARY_NAME="University Library"
BRANDING_LOGO=images/branding/logo.png
BRANDING_FAVICON=images/branding/favicon.ico
```

SVG placeholders ship with the repo so the app runs before you add real files.
