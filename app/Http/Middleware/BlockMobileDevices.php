<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockMobileDevices
{
    protected array $mobileKeywords = [
        'android', 'iphone', 'ipad', 'ipod', 'blackberry',
        'windows phone', 'iemobile', 'opera mini', 'opera mobi',
        'mobile', 'webos', 'kindle', 'silk/',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Allow the blocked page itself, and the tiny endpoint that sets the cookie
        if ($request->is('blocked')) {
            return $next($request);
        }

        $userAgent = strtolower($request->userAgent() ?? '');
        $uaSaysMobile = false;

        foreach ($this->mobileKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                $uaSaysMobile = true;
                break;
            }
        }

        if ($request->header('Sec-CH-UA-Mobile') === '?1') {
            $uaSaysMobile = true;
        }

        // Trust the device-capability cookie over a flip-flopping UA header
        $cookieSaysMobile = $request->cookie('device_check') === 'mobile';

        if ($uaSaysMobile || $cookieSaysMobile) {
            abort(403);
        }

        return $next($request);
    }
}