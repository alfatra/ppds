<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountActivation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login dan akun belum diaktivasi
        if (auth()->check() && !auth()->user()->is_active) {
            // Set flash message ke session
            session()->flash('inactive_account_warning', true);
        }

        return $next($request);
    }
}
