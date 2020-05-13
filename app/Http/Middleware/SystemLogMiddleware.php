<?php

namespace App\Http\Middleware;

use App\Models\SystemLog;
use Closure;
use Log;

/**
 * Class SystemLogMiddleware
 *
 */
class SystemLogMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if (config('app.syslog')) {
            try {
                SystemLog::record($request, $response);
            } catch (\Exception $e) {
                Log::error($e);
            }
        }
    }
}
