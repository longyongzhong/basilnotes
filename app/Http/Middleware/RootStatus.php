<?php

namespace App\Http\Middleware;

use Closure;

class RootStatus
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
        if (!session("root")){
            return redirect("/managelogin");
        }
        return $next($request);
    }
}
