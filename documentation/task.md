# Developer Admin Appearance and Branding Manager

## Goal

Create an admin-only Appearance & Branding manager that allows an authorized administrator to change application branding, OPAC landing-page content, button colors, and table colors without manually editing environment or CSS files.

Extend the existing branding architecture in `config/branding.php`, `app/Support/Branding.php`, `public/branding/branding.css`, and `resources/css/brand-tokens.css`. Existing configuration values must remain the fallback until custom settings are published.

## Task 1: Add appearance settings persistence

- [ ] Create a `site_settings` migration and `SiteSetting` model.
- [ ] Support `branding`, `landing_page`, `buttons`, `tables`, and `theme` setting groups.
- [ ] Store draft and published JSON separately.
- [ ] Record the version, editor, publisher, publishing date, and timestamps.
- [ ] Add model casts, relationships, and test helpers.
- [ ] Resolve defaults from existing configuration instead of duplicating them in the database.

**Complete when:** settings can be stored without changing the current application appearance.

## Task 2: Add authorization and routes

- [ ] Decide whether to use `can:isAdmin` or add a dedicated `manageAppearance` permission.
- [ ] Add protected routes under `/admin/appearance`.
- [ ] Add actions for editing, saving drafts, publishing, discarding drafts, resetting, and uploading assets.
- [ ] Add Appearance & Branding to the Admin section of `resources/js/config/adminNavigation.js`.
- [ ] Protect every mutation with authentication, authorization, and CSRF validation.
- [ ] Test access for guests, staff, and authorized administrators.

**Complete when:** only authorized administrators can view or change appearance settings.

## Task 3: Build the settings resolver and cache

- [ ] Update `app/Support/Branding.php` to read published database settings.
- [ ] Resolve each value in this order: published database value, configuration value, built-in default.
- [ ] Preserve the existing Blade and Inertia branding structures where possible.
- [ ] Cache published settings.
- [ ] Invalidate caches after publishing or resetting.
- [ ] Handle a temporarily missing settings table safely during deployment.
- [ ] Test resolution precedence and fallback behavior.

**Complete when:** published values resolve consistently and existing installations remain visually unchanged.

## Task 4: Add dynamic theme CSS

- [ ] Add a public, cacheable endpoint such as `GET /branding/theme.css`.
- [ ] Generate only allowlisted CSS custom properties.
- [ ] Normalize and safely serialize color values.
- [ ] Add ETag or last-modified support and suitable cache headers.
- [ ] Load the dynamic stylesheet after the static branding stylesheet.
- [ ] Do not rewrite files under `public/` at runtime.
- [ ] Test that unknown keys and unsafe CSS values cannot be emitted.

Required token groups include:

```css
--brand-button-primary-bg
--brand-button-primary-text
--brand-button-primary-hover-bg
--brand-button-secondary-bg
--brand-button-secondary-text
--brand-button-secondary-hover-bg
--brand-table-header-bg
--brand-table-header-text
--brand-table-row-bg
--brand-table-row-alt-bg
--brand-table-row-hover-bg
--brand-table-row-selected-bg
--brand-table-footer-bg
--brand-table-border
--brand-table-text
```

**Complete when:** publishing an allowed color updates the application without a deployment or frontend rebuild.

## Task 5: Build the Branding tab

- [ ] Add fields for school, library, and system names.
- [ ] Add the staff portal subtitle.
- [ ] Add school-home and external-resource URLs.
- [ ] Add uploads for primary, landscape, and compact logos.
- [ ] Add uploads for the favicon, banner, and partner logo.
- [ ] Provide preview, replace, remove, and reset controls.
- [ ] Distinguish inherited, draft, and published values.
- [ ] Validate image types, sizes, and dimensions.
- [ ] Store managed uploads with unique filenames.
- [ ] Never delete deployment-provided default assets.

**Complete when:** branding text and assets can be drafted, previewed, published, and reset.

## Task 6: Build the button color controls

- [ ] Add primary button background, text, border, hover, focus, and disabled colors.
- [ ] Add equivalent secondary button controls.
- [ ] Keep success and danger colors semantically distinct.
- [ ] Apply tokens to shared React/shadcn button components.
- [ ] Apply tokens to shared Blade and Bootstrap button styles.
- [ ] Audit legacy pages for hard-coded button colors.
- [ ] Verify default, hover, focus, active, and disabled states.

**Complete when:** supported React and Blade buttons consistently use the published palette.

## Task 7: Build the table color controls

- [ ] Add table header background and text colors.
- [ ] Add standard, alternating, hover, selected, and footer row colors.
- [ ] Add table text and border colors.
- [ ] Apply tokens to the shared React table component.
- [ ] Apply tokens to legacy Bootstrap and Blade table styles.
- [ ] Audit `.table-dark`, `.table-light`, inline colors, and page-specific overrides.
- [ ] Preserve warning, success, danger, availability, and borrowing semantics.
- [ ] Exclude transactional email and PDF tables from the first release.

**Complete when:** representative admin and OPAC tables use the published palette without losing semantic states.

## Task 8: Add landing-page controls

- [ ] Edit the OPAC hero kicker, heading, subtitle, search placeholder, button label, and helper text.
- [ ] Edit the new-arrivals section title and description.
- [ ] Edit the external-links section title and description.
- [ ] Add an optional hero background image.
- [ ] Add visibility controls for supported navigation links and sections.
- [ ] Add ordering controls for predefined sections.
- [ ] Update `resources/views/books/landing.blade.php` to consume resolved values.
- [ ] Use the current content as fallback.
- [ ] Preserve search, filtering, carousel behavior, responsiveness, and accessible heading structure.
- [ ] Do not accept arbitrary HTML, CSS, or JavaScript.

**Complete when:** administrators can customize the OPAC landing page without changing code.

## Task 9: Add preview and publishing workflows

- [ ] Create Branding, Landing Page, Buttons, Tables, and Advanced Theme tabs.
- [ ] Pair color pickers with editable normalized values.
- [ ] Add desktop and mobile previews.
- [ ] Preview branding, landing content, buttons, and tables together.
- [ ] Add Save Draft, Publish, Discard Draft, and Reset to Default actions.
- [ ] Warn before publishing and resetting.
- [ ] Show accessibility contrast warnings.
- [ ] Invalidate caches after publishing or resetting.
- [ ] Log changes through the existing admin activity logger.

**Complete when:** administrators can safely review a complete draft before it affects users.

## Task 10: Validate inputs and uploads

- [ ] Create dedicated form request classes.
- [ ] Accept only known setting groups and keys.
- [ ] Validate and normalize supported color values.
- [ ] Accept only HTTPS URLs or approved internal relative paths.
- [ ] Strip HTML from plain-text fields and enforce length limits.
- [ ] Validate upload MIME type, file size, and image dimensions.
- [ ] Delete replaced assets only when they are managed uploads and no longer referenced.
- [ ] Return clear field-level validation messages.

**Complete when:** malformed or unsafe settings cannot be stored, published, or emitted as CSS.

## Task 11: Add automated tests

- [ ] Guests cannot access appearance management.
- [ ] Staff cannot access appearance management.
- [ ] Authorized administrators can use the editor.
- [ ] Draft changes do not affect published output.
- [ ] Publishing activates draft values and increments the version.
- [ ] Reset restores configuration or built-in fallbacks.
- [ ] Unknown keys, malformed colors, unsafe URLs, and invalid uploads are rejected.
- [ ] Generated CSS contains only allowlisted tokens.
- [ ] Publishing invalidates settings and stylesheet caches.
- [ ] Published values appear in Blade and Inertia branding data.
- [ ] Resolver precedence is database, configuration, then built-in default.
- [ ] Installations without settings retain their current appearance.

**Complete when:** all critical backend workflows and failure paths have automated coverage.

## Task 12: Accessibility, regression testing, and documentation

- [ ] Verify text/background contrast.
- [ ] Verify keyboard navigation, labels, errors, and focus indicators.
- [ ] Verify OPAC home and search results at desktop and mobile sizes.
- [ ] Verify the admin catalog, account lists, attendance logs, and room tables.
- [ ] Verify all supported button and table states.
- [ ] Verify transparent, square, and landscape uploaded images.
- [ ] Verify dark mode where supported.
- [ ] Run the relevant test suite and frontend build.
- [ ] Test migrations on an existing installation.
- [ ] Document permissions, storage, caching, publishing, reset, and rollback procedures.

**Complete when:** all acceptance criteria and the Definition of Done are satisfied.

## Acceptance criteria

- [ ] Appearance & Branding is visible only to authorized administrators.
- [ ] Draft changes do not affect public or staff pages.
- [ ] Publishing updates supported styling without a deployment or frontend build.
- [ ] Branding text, images, links, landing content, visibility, and section order are editable.
- [ ] Button and table colors update across supported React and Blade pages.
- [ ] Invalid colors, URLs, keys, and uploads are rejected with useful messages.
- [ ] Contrast problems are reported before publishing.
- [ ] Reset restores fallback values.
- [ ] Existing installations retain their appearance until settings are published.
- [ ] Publishing refreshes cached settings and stylesheet output.
- [ ] Existing OPAC search, navigation, forms, tables, and responsive behavior continue working.
- [ ] Appearance changes are recorded in the admin activity log.

## Out of scope

- Arbitrary HTML, CSS, or JavaScript editing.
- A drag-and-drop page builder.
- Per-user or per-role themes.
- Transactional email and generated PDF styling.
- Editing business-semantic statuses through general branding controls.
- Automatic deletion of deployment-provided assets.

## Definition of done

- [ ] All 12 tasks and acceptance criteria are complete.
- [ ] Relevant automated tests pass.
- [ ] Frontend assets build successfully.
- [ ] Migrations run successfully on an existing installation.
- [ ] Representative pages have no new console or server errors.
- [ ] Default deployments remain visually unchanged until settings are published.
- [ ] Administration and rollback procedures are documented.
