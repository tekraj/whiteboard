<?php

namespace App\Http\Middleware;

use Closure;

class CoAdminMiddleware
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
        if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isMonitor() || auth()->user()->isCoAdmin())){
            return $next($request);
        }
        return redirect('admin/');
    }
}
