<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Arr;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectToz($request, $guards)
        );
    }

    protected function redirectToz($request, $guards)
    {
        if (! $request->expectsJson()) {
            $guard = Arr::get($guards, 0);
            switch ($guard) {
                case 'web':
                    return route('login');
                default:
                    return route('admin.login');
                    break;
            }
        }
    }
}
