<?php

namespace App\Http\Middleware;
use Illuminate\Support\Str;

use Closure;
use Config;

class SetDynamicJWTSecretKey
{
    public function handle($request, Closure $next)
    {
        $url = request()->segment(2);
        // Your logic to determine the dynamic secret key
        $dynamicKey = $this->calculateDynamicKey($url);
        // Update JWT configuration dynamically
        Config::set('jwt.secret', $dynamicKey);

        return $next($request);
    }

    private function calculateDynamicKey($url)
    {
        switch (true) {
            case Str::is($url, 'app1'):
                return Config::get('app_tokens.application1');
                break;
            case Str::is($url, 'app2'):
                return Config::get('app_tokens.application2');
                break;
            default:
                return Config::get('app_tokens.jwt_secret');
        }
        // Your logic to calculate the dynamic secret key
        // For example, fetching it from a database, environment variable, etc.
        return Config::get('app_tokens.jwt_secret');
    }
}
