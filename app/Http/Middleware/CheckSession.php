<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!$request->session()->has('user')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = $request->session()->get('user');
        
        if ($role && $user['role'] !== $role) {
            // Jangan redirect ke home jika role tidak sesuai, redirect ke dashboard yang sesuai
            switch ($user['role']) {
                case 'admin':
                    return redirect()->route('admin.master');
                case 'panitia':
                    return redirect()->route('panitia.dashboard');
                case 'keuangan':
                    return redirect()->route('keuangan.dashboard');
                case 'kepala_sekolah':
                    return redirect()->route('kepala-sekolah.dashboard');
                case 'pendaftar':
                    return redirect()->route('pendaftar.dashboard');
                default:
                    return redirect('/')->with('error', 'Akses ditolak');
            }
        }

        return $next($request);
    }
}