<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return redirect('/');
    }

    /**
     * Handle the login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        
        $user = \App\Models\User::where('email', $email)->first();
        
        if ($user && \Hash::check($password, $user->password)) {
            $request->session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]);
            
            // Redirect berdasarkan role
            $redirectUrl = $this->getRedirectUrlByRole($user->role);
            
            return response()->json([
                'success' => true,
                'user' => ['role' => $user->role],
                'redirect_url' => $redirectUrl
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }
    
    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrlByRole($role)
    {
        switch ($role) {
            case 'admin':
                return route('admin.master');
            case 'panitia':
                return route('panitia.dashboard');
            case 'keuangan':
                return route('keuangan.dashboard');
            case 'kepala_sekolah':
                return route('kepala-sekolah.dashboard');
            case 'pendaftar':
            case 'siswa':
                return route('pendaftar.dashboard');
            default:
                return route('admin.master');
        }
    }

    /**
     * Handle the logout request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
