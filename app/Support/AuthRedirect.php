<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthRedirect
{
    /**
     * Prefixes a role may be redirected to after login (path only, no query string).
     *
     * @var array<string, list<string>>
     */
    protected const ALLOWED_PREFIXES = [
        'admin' => [
            '/book',
            '/books',
            '/students',
            '/employees',
            '/logs',
            '/files',
            '/rooms',
            '/admin',
            '/attendance',
            '/account',
            '/opac',
            '/ebooks',
            '/prospectus',
            '/sms-blast',
            '/view-users',
            '/catalog',
            '/rfid-scanner',
            '/patron-suggestions',
            '/book-suggestions',
            '/feedbacks',
            '/download-book-report',
            '/reports',
            '/export-books',
            '/export-transactions',
            '/import-books',
            '/checkout',
            '/staff',
            '/program',
            '/filter',
        ],
        'staff' => [
            '/book',
            '/books',
            '/logs',
            '/rooms',
            '/attendance',
            '/account',
            '/opac',
            '/ebooks',
            '/catalog',
            '/rfid-scanner',
            '/patron-suggestions',
            '/book-suggestions',
            '/feedbacks',
            '/download-book-report',
            '/reports',
            '/export-books',
            '/export-transactions',
            '/import-books',
            '/checkout',
            '/staff',
            '/program',
            '/filter',
        ],
        'developer' => [
            '/developer',
        ],
        'student' => [
            '/opac',
            '/account',
            '/register',
            '/feedback',
            '/rooms',
            '/kiosk',
            '/student',
            '/checkout',
        ],
        'faculty' => [
            '/opac',
            '/account',
            '/register',
            '/feedback',
            '/rooms',
            '/kiosk',
            '/student',
            '/checkout',
        ],
    ];

    public static function afterLogin(User $user, Request $request): RedirectResponse
    {
        $intended = $request->session()->pull('url.intended');
        $default = self::defaultUrl($user->role);

        if (is_string($intended) && self::isAllowedForRole($intended, $user->role)) {
            return redirect()->to($intended);
        }

        return redirect()->to($default);
    }

    public static function defaultUrl(?string $role): string
    {
        return match ($role) {
            'admin', 'staff' => route('book.index'),
            'developer' => route('developer.dashboard'),
            'student', 'faculty' => route('landing'),
            default => route('login'),
        };
    }

    public static function isAllowedForRole(string $url, ?string $role): bool
    {
        if (! $role || ! isset(self::ALLOWED_PREFIXES[$role])) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: $url;

        foreach (self::ALLOWED_PREFIXES[$role] as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return true;
            }
        }

        return false;
    }
}
