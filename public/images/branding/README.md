# School branding assets

Logos for the **staff portal, login, OPAC, and kiosk** — not the public PANTAS marketing home page.

| File | Used for |
|------|----------|
| `logo.png` | Sidebar, auth login, OPAC header |
| `logo-landscape.png` | Wide header layouts |
| `logo-compact.png` | Small marks, OPAC search |
| `favicon.ico` / `favicon.png` | Browser tab icon |
| `banner.jpg` | Staff catalog hero banner |
| `partner-zendy.svg` | Optional OPAC partner tile |

PNG, JPG, ICO, and SVG are all supported.

**Platform home page (`/`)** uses separate files in `public/images/platform/` — see `public/images/platform/README.md`.

## `.env`

```env
BRANDING_LOGO=images/branding/logo.png
BRANDING_LOGO_LANDSCAPE=images/branding/logo-landscape.png
BRANDING_LOGO_COMPACT=images/branding/logo-compact.png
BRANDING_FAVICON=images/branding/favicon.ico
BRANDING_BANNER=images/branding/banner.jpg
```

**Colors** are not stored here — edit `public/branding/branding.css`.
