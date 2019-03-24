<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CompanyAuthenticateMiddleware
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
        $company_id = $request->company_id;
        
        if (!\Auth::check() || \Auth::user()->company_id != $company_id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        if (\Auth::user()->company_id === $company_id && \Auth::user()->role_id == 4) {
//            return redirect('/account_settings');
        }

        // if (Auth::user()->email_verified == 0) {
        //     return redirect('email_verification');
        // }

        return $next($request);
    }
}
