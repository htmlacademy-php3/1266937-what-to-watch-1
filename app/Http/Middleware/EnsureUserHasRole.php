<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @psalm-api
 */
class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            throw new AuthenticationException();
        }

        if (!\in_array($user->role->name, $roles, true)) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
