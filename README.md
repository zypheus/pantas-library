# PANTAS — Library Management System

Laravel application for cataloging, circulation, patron registration, attendance scanning, room reservations, e-resources, and staff administration. **Multi-school:** colors and logos are configured per deployment, not hardcoded in the repo.

Design tokens and shadcn theme vars live in `resources/css/app.css` and `resources/css/brand-tokens.css`. Per-school colors/fonts: `public/branding/branding.css`. Per-school images: `public/images/branding/`. Preview at `/design-system` (local dev only).

## Requirements

- PHP 8.2+ with common Laravel extensions (`pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`)
- Composer
- MySQL 8+ (or MariaDB)
- Node.js 18+ and npm (required for Vite — admin shell and Inertia pages will not load without built or dev assets)

## Quick start (local)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
cp public/branding/branding.css.example public/branding/branding.css
```

Create a MySQL database (example name `demo_2`), then set in `.env`:

```env
APP_URL=http://127.0.0.1:8000
DB_DATABASE=demo_2
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seed MARC catalog framework:

```bash
php artisan migrate
php artisan db:seed --class=MarcFrameworkSeeder
php artisan storage:link
```

Optional — full sample data (programs, students, books, attendance, test admin user):

```bash
php artisan db:seed
```

That creates `test@example.com` / `password` (admin role).

Optional — 10 sample students only for QR/attendance testing (`S-00000001` … `S-00000010`):

```bash
php artisan db:seed --class=StudentSampleSeeder
```

Serve the app (PHP + queue + Vite together):

```bash
composer run dev
```

Or run separately:

```bash
php artisan serve
npm run dev
```

Open **http://127.0.0.1:8000** and sign in with an **admin** or **staff** user from the database, the seeded test account, or **Create Account** in the admin UI.

For a production-style asset build without the Vite dev server:

```bash
npm run build
php artisan serve
```

## Common commands

```bash
composer run dev          # php artisan serve + queue + npm run dev
php artisan serve
npm run dev
npm run build
npm run build:tailwind    # legacy Tailwind utility bundle for Blade pages
php artisan test --filter=SomeTest
./vendor/bin/pint
php artisan route:list
```

## Frontend layout

| Path | Purpose |
| --- | --- |
| `resources/views/layouts/sec.blade.php` | Admin layout — mounts React admin shell around Blade `@yield('content')` |
| `resources/js/admin-shell.jsx` | Entry for the Blade admin shell |
| `resources/js/app.jsx` | Entry for full Inertia pages |
| `resources/js/Layouts/AdminLayout.jsx` | Inertia wrapper using the same shell |
| `resources/js/components/ui/` | shadcn/ui components |
| `resources/js/Pages/` | Inertia page components |
| `app/Support/AdminShell.php` | Shared nav, user, and notification props for shell |

Add shadcn components with `npx shadcn@latest add <component>` (requires `components.json`).

## Environment notes

| Variable | Purpose |
| --- | --- |
| `BRANDING_CSS` | Per-school stylesheet under `public/branding/` (see `public/branding/README.md`) |
| `BRANDING_SCHOOL_NAME` / `BRANDING_LIBRARY_NAME` | Display names in OPAC, sidebar, auth |
| `BRANDING_LOGO` / `BRANDING_FAVICON` / `BRANDING_BANNER` | Paths under `public/images/` (default: `images/branding/*`) |
| `SMS_MODEM_URL` / `SMS_MODEM_API_KEY` | Local Flask SMS bridge (optional) |
| `GOOGLE_BOOKS_API_KEY` | ISBN lookup quota for cataloging (optional) |

Copy `.env.example` — **never commit** your real `.env` file.

`public/branding/branding.css` is gitignored; use `branding.css.example` as the template.

### New school deployment

1. `cp public/branding/branding.css.example public/branding/branding.css` — edit colors/fonts  
2. Replace files in `public/images/branding/` (logo, favicon, banner) or set `BRANDING_*` paths in `.env`  
3. Set `APP_NAME`, `BRANDING_SCHOOL_NAME`, `BRANDING_LIBRARY_NAME` in `.env`  
4. Run `npm run build` locally and upload `public/build/` to shared hosting (see Hostinger notes in chat/docs)

## Pushing to GitHub

Remote is configured as `origin` → `https://github.com/borskenetic/usm.git`.

From the project root:

```bash
git status
git add -A
git commit -m "Initial commit"
git branch -M main
git push -u origin main
```

Before pushing, confirm `git status` does **not** list:

- `.env`
- `public/branding/branding.css`
- Uploads under `public/images/profile_pictures/`, etc.

## Fresh clone on another machine

```bash
git clone https://github.com/borskenetic/usm.git
cd usm
composer install
npm install
cp .env.example .env
cp public/branding/branding.css.example public/branding/branding.css
# edit .env for DB credentials and APP_URL
php artisan key:generate
php artisan migrate
php artisan db:seed --class=MarcFrameworkSeeder
php artisan storage:link
composer run dev
```

Ensure writable directories: `storage/`, `bootstrap/cache/`, and upload folders under `public/images/` (see `.gitkeep` files).

**Videos:** MP4 files under `public/videos/` are not stored on GitHub (too large). After cloning, copy your slideshow/background videos into `public/videos/` on the server.

**Database copy (optional):** To mirror an existing environment instead of seeding, export/import the MySQL database and copy upload folders from the source machine. Still run `composer install`, `npm install`, `php artisan key:generate`, and `php artisan storage:link` on the new machine.

## Main features

- **Catalog** — MARC-based books, programs, circulation, fines, trash/archive
- **E-Resources** — `/ebooks` digital collection with program/subject filters
- **Patrons** — student registration, ID cards, pending approvals
- **Attendance** — QR scan in/out, reports, optional logout feedback
- **Rooms** — reservations, schedule, pending queue, logs
- **Admin** — user accounts (admin/staff/faculty/student roles)

## License

Application code follows your project license. Laravel framework components are [MIT](https://opensource.org/licenses/MIT).
