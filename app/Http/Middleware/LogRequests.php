<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        $logData = [
            'method' => $request->method(),
            'uri' => $request->path(),
            'payload' => $request->except(['password', 'token'])
        ];

        Log::channel('access')->info('API Request', $logData);

        return $next($request);
    }
}
