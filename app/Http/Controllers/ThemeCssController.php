<?php

namespace App\Http\Controllers;

use App\Support\ThemeCss;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThemeCssController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $css = ThemeCss::render();
        $etag = ThemeCss::etag();

        if ($request->headers->get('If-None-Match') === $etag) {
            return response('', 304)->withHeaders([
                'ETag' => $etag,
                'Cache-Control' => 'public, max-age=300',
                'Content-Type' => 'text/css; charset=UTF-8',
            ]);
        }

        return response($css, 200, [
            'Content-Type' => 'text/css; charset=UTF-8',
            'Cache-Control' => 'public, max-age=300',
            'ETag' => $etag,
        ]);
    }
}
