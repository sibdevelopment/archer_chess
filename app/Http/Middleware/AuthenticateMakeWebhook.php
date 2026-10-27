<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticateMakeWebhook
{
    public function handle(Request $request, Closure $next)
    {
        $expectedToken = (string) config('integrations.make.instant_form_secret');

        if ($expectedToken === '') {
            return new JsonResponse([
                'message' => 'Make Instant Form integration is not configured.',
            ], 503);
        }

        $providedToken = (string) $request->bearerToken();

        if ($providedToken === '' || ! hash_equals($expectedToken, $providedToken)) {
            return new JsonResponse([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return $next($request);
    }
}
