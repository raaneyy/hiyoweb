<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureReaderVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('is_reader_verified') && ! session('is_admin')) {
            return redirect()->route('login')->with('error', 'Silakan verifikasi tanggal terlebih dahulu untuk membaca diary.');
        }

        return $next($request);
    }
}
