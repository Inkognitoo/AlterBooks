<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
            return $next($request);
        } else {
            return response($this->buildResponse('error', 'Unauthorized'), 401)
                ->header('Content-Type', 'text/json');
        }
//        if (Auth::guard($guard)->guest()) {
//            if ($request->ajax() || $request->wantsJson()) {
//                return response('Unauthorized.', 401);
//            } else {
//                return redirect()->guest('login');
//            }
//        }

        //return $next($request);
    }

    private function buildResponse($status, $payload)
    {
        return json_encode([
            'status' => $status,
            'payload' => $payload
        ]);
    }
}
