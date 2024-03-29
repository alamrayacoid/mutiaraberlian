<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Pusat
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
        if(!Session::get('isPusat'))
            return redirect()->route('errors.404');

        return $next($request);
    }
}
