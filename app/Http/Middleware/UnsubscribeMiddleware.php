<?php

namespace App\Http\Middleware;

use Closure;
use Validator;

class UnsubscribeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|exists:followers',
                'code' => 'required|size:20'
            ]
        );

        if ($validator->fails()){
            return redirect('/');
        }

        return $next($request);
    }
}
