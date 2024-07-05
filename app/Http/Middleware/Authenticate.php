<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected Auth $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->bearerToken();

        if (!$token || $token !== config('api.token')) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
