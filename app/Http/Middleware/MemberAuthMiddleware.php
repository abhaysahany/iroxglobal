<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (! $request->session()->has('MEMBER_ID')) {
            return redirect()->route('member.login')->with('error', 'Please login to continue.');
        }

        return $next($request);
    }
}
