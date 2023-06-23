<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isTrueUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->route()->parameter('user');
        $userId = $user->id;
        if ($userId == auth()->id()){
            return $next($request);
        }
        return back();
    }
}
