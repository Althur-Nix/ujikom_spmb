<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $role = $request->role;
        
        switch ($role) {
            case 'admin':
                return redirect()->route('dashboard.admin');
            case 'keuangan':
                return redirect()->route('dashboard.keuangan');
            case 'siswa':
                return redirect()->route('dashboard.siswa');
            default:
                return back()->with('error', 'Role tidak valid');
        }
    }

    public function logout()
    {
        return redirect()->route('login');
    }
}