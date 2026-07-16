## Developer Admin Customization Plan

Build an admin-only “Appearance & Branding” area that extends the existing branding system. Store editable settings in the database, while retaining [config/branding.php](C:/clone-github-repo/library-system/library-system/config/branding.php) as the fallback defaults.

### 1. Settings and storage

Create a site_settings table and model with grouped JSON settings:

- branding: school, library and system names; logos; favicon; banner; external links.
- landing_page: hero heading, subtitle, search placeholder, section headings, visibility toggles, and optional background image.
- buttons: primary, secondary, success, danger, hover, text and border colors.
- tables: header background/text, row background/text, alternate row, hover, border and selected-row colors.
- theme: sidebar, page, card, text and accent colors.

Include draft/published values, updated_by, timestamps, and a version number for rollback or auditing.

### 2. Branding service

Refactor [Branding.php](C:/clone-github-repo/library-system/library-system/app/Support/Branding.php) to resolve settings in this order:

1. Published database settings
2. Existing environment/config values
3. Safe built-in defaults

Cache published settings and invalidate the cache after an update. Continue returning the same Blade and React structures so existing pages remain compatible.

### 3. Admin interface

Add an admin-only route such as /admin/appearance, protected by the existing can:isAdmin authorization.

Organize the interface into tabs:

- Branding
- Landing Page
- Buttons
- Tables
- Advanced Theme

Each tab should provide:

- Color pickers plus editable hex values
- Image upload, preview, replace and reset controls
- Live desktop/mobile preview
- “Save Draft,” “Publish,” and “Reset to Default” actions
- Contrast warnings for unreadable text/background combinations
- Clear indication of inherited versus customized values

Add “Appearance & Branding” to the Admin section in [adminNavigation.js](C:/clone-github-repo/library-system/library-system/resources/js/config/adminNavigation.js).

### 4. CSS token integration

Keep the existing CSS-variable architecture in [branding.css](C:/clone-github-repo/library-system/library-system/public/branding/branding.css) and [brand-tokens.css](C:/clone-github-repo/library-system/library-system/resources/css/brand-tokens.css).

Add explicit tokens such as:

--brand-button-primary-bg
--brand-button-primary-text
--brand-button-primary-hover-bg
--brand-button-secondary-bg
--brand-table-header-bg
--brand-table-header-text
--brand-table-row-bg
--brand-table-row-alt-bg
--brand-table-row-hover-bg
--brand-table-border
--brand-table-text

Expose published values through a dynamic, cacheable stylesheet endpoint such as /branding/theme.css. This is safer and more deployment-friendly than rewriting files under public/.

Do not allow branding controls to replace semantic availability, warning, success or danger colors unless they are edited explicitly.

### 5. Landing-page customization

Update [landing.blade.php](C:/clone-github-repo/library-system/library-system/resources/views/books/landing.blade.php) to consume configurable content for:

- OPAC heading and subtitle
- Search placeholder and button label
- Hero background/image
- New-arrivals heading and description
- External-links heading and visibility
- Header navigation visibility
- Section ordering and visibility

For the first release, use predefined sections and toggles rather than a free-form page builder. This keeps the page accessible and avoids storing arbitrary HTML.

### 6. Validation and security

Create a dedicated request validator:

- Colors: valid hex, RGB or approved CSS color syntax
- Uploaded images: strict MIME type, dimensions and file-size limits
- URLs: HTTPS or approved internal relative paths
- Text: length limits and HTML stripping
- Settings keys: server-side allowlist only

Store uploads through Laravel storage with unique filenames. Record publishing and resetting through the existing admin activity logger.

If “developer admin” is intended to be more privileged than a normal administrator, introduce a dedicated permission such as manageAppearance; otherwise, use the current admin gate.

### 7. Compatibility work

Apply the new tokens to both UI systems:

- React/shadcn components in resources/js
- Bootstrap and legacy Blade pages in resources/views and css

Audit hard-coded .table-dark, .table-light, .btn-*, inline colors and page-specific table styles. Replace only presentation colors; preserve semantic status styling and email/PDF colors unless those outputs are explicitly brought into scope.

### 8. Testing

Add feature tests covering:

- Staff and unauthenticated users cannot access appearance settings
- Admin can save a draft and publish it
- Invalid colors, URLs and images are rejected
- Reset restores fallback values
- Published settings appear in Blade and Inertia branding data
- Cached values are invalidated after publishing
- Upload replacement does not delete shared/default assets

Add visual checks for the OPAC landing page, admin shell, buttons and representative tables at desktop and mobile widths.

### Recommended delivery order

1. Database settings, resolver and authorization
2. Dynamic theme stylesheet and cache invalidation
3. Branding and button controls
4. Table-color controls and hard-coded-style audit
5. Landing-page content and section controls
6. Live preview, drafts, publishing and reset
7. Accessibility, regression tests and rollout documentation
