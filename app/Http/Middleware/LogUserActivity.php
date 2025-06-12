<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\ActivityLoggerService;

class LogUserActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check()) {
            ActivityLoggerService::log('page_visit', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'status' => $response->getStatusCode()
            ]);
        }

        return $response;
    }
}