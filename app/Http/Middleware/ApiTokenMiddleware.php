<?php

namespace App\Http\Middleware;

use Closure;

class ApitokenMiddleware
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
        $token = $request->get('access_token');
        $company_id = $request->route('id');
        $check = \App\ApiToken::where('access_token', '=', $token)->where('company_id', '=', $company_id)->first();

        if (count($check) == 1) {
            return $next($request);
        } else {
            return response('Unauthorized.', 401);
        }
    }
}
