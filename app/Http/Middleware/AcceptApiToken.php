<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseJson;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcceptApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('app.is_local')) {
            $token = $request->header('API-TOKEN');

            if (!verifyHmacWithExpiry($token)) {
                return ResponseJson::error('Invalid Token Access.', 401, [
                    'credential' => 'Your credential is invalid.',
                ]);
            }
        }

        return $next($request);
    }
}
