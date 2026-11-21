<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Only allow logged-in admins to open protected admin pages.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('ADMIN_ID') && ! $request->session()->has('admin_id')) {
            $request->session()->put('admin_id', $request->session()->get('ADMIN_ID'));
        }

        if (! $request->session()->has('admin_id')) {
            return redirect()->route('home');
        }

        return $next($request);
    }

    
}
