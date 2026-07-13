# Platform marketing assets

Logos for the **public home page** (`/`) — PANTAS marketing site, separate from the school branding used in the staff portal, OPAC, and login.

## Files

| File | Used for |
|------|----------|
| `logo.png` | Large logo in the about section |
| `logo-landscape.png` | Header and footer (wide layout) |
| `vendor-logo.png` | Footer partner/vendor mark (e.g. AREA51) |

PNG, JPG, ICO, and SVG are all supported.

## `.env` (required when using different logos from the school)

```env
# School — staff portal, login, OPAC
BRANDING_LOGO=images/branding/logo.png
BRANDING_LOGO_LANDSCAPE=images/branding/logo-landscape.png
BRANDING_LOGO_COMPACT=images/branding/logo-compact.png
BRANDING_FAVICON=images/branding/favicon.ico

# Platform — public home page (/)
BRANDING_PLATFORM_LOGO=images/platform/logo.png
BRANDING_PLATFORM_LOGO_LANDSCAPE=images/platform/logo-landscape.png
BRANDING_PLATFORM_VENDOR_LOGO=images/platform/vendor-logo.png
```

After uploading files and editing `.env` on the server:

```bash
php artisan config:clear
```

SVG placeholders ship with the repo for development. On deploy, drop PNG/JPG files using the default names above (or override paths in `.env`).
