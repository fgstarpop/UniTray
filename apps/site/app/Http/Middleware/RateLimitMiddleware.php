<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, 20)) {
            return new Response('Too Many Requests', 429);
        }

        $this->limiter->hit($key, 1);

        $response = $next($request);

        return $response;
    }

    protected function resolveRequestSignature($request)
    {
        return sha1($request->method() . '|' . $request->server('SERVER_NAME') . '|' . $request->ip());
    }
}
