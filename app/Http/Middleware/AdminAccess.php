<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek autentikasi menggunakan guard admin
        if (!Auth::guard('admin')->check()) {
            // Jika tidak login di guard admin, arahkan ke login admin
            return redirect()->route('admin.login');
        }
        
        // Cek apakah user memiliki role admin
        if (Auth::guard('admin')->user()->role !== 'admin') {
            // Logout dari guard admin
            Auth::guard('admin')->logout();
            
            // Arahkan ke homepage dengan pesan error
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke panel admin');
        }
    
        return $next($request);
    }
}