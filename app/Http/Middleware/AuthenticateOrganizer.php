<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateOrganizer
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
        if($request->user()->isOrganizer() == false AND $request->user()->isAdmin() == false)
				{
					if ($request->ajax() || $request->wantsJson()) 
					{
          		return response('Unauthorized.', 401);
          }

          return redirect()->guest(route('login'));
				}
				
        return $next($request);
    }
}
