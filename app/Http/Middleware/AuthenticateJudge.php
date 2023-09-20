<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
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
        $user = $request->user();
        $url = $request->fullUrl();
        if(Str::contains($url,'judge/recording/save')) {
            if($user->isOrganizerAdmin() || $user->isOrganizer()) {
                return $next($request);
            }
        }

        if(!$user->isJudge() AND !$user->isAdmin()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }
            return redirect()->guest('login');
        }

        return $next($request);
    }
}
