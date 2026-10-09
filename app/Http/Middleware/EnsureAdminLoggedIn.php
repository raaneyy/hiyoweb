<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('is_admin')) {
            return redirect()->route('login', ['tab' => 'admin'])
                ->with('admin_error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        return $next($request);
    }
}
