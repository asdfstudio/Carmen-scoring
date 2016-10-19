<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateJudge
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
        if($request->user()->isJudge() == false AND $request->user()->isAdmin() == false)
				{
					if ($request->ajax() || $request->wantsJson()) 
					{
          		return response('Unauthorized.', 401);
          }

          return redirect()->guest('login');
				}
				
        return $next($request);
    }
}
