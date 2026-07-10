# School branding assets

Replace these files when deploying for a new institution. Paths are configured in `config/branding.php` and overridable via `.env`.

| File | Used for |
|------|----------|
| `logo.svg` / `logo.png` | Sidebar, auth login, OPAC header |
| `logo-landscape.svg` | Wide header layouts |
| `logo-compact.svg` | Small marks, OPAC search |
| `favicon.svg` / `favicon.ico` | Browser tab icon |
| `banner.svg` / `banner.jpg` | Staff catalog hero banner |
| `partner-zendy.svg` | Optional OPAC partner tile |

**Colors** are not stored here — edit `public/branding/branding.css` (copy from `branding.css.example`).

**Quick setup for a new school:**

1. `cp public/branding/branding.css.example public/branding/branding.css` and edit colors/fonts
2. Drop your logo files here (same filenames, or set `BRANDING_LOGO=images/branding/my-logo.png` in `.env`)
3. Set `BRANDING_SCHOOL_NAME`, `BRANDING_LIBRARY_NAME`, `APP_NAME` in `.env`

SVG placeholders ship with the repo so the app works before you add real assets.
