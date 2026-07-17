<?php

namespace App\Http\Middleware;

use App\Support\Branding;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyFeatureFlags
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Branding::featureEnabled('maintenance_mode')) {
            $user = $request->user();
            $allowed = $user && in_array($user->role, ['admin', 'developer'], true);
            $isExempt = $request->is('login', 'logout', 'up', 'branding/*', 'developer', 'developer/*');

            if (! $allowed && ! $isExempt) {
                return response()->view('maintenance', [
                    'brand' => Branding::forViews(),
                ], 503);
            }
        }

        view()->share('showStagingBanner', Branding::featureEnabled('show_staging_banner'));

        return $next($request);
    }
}
