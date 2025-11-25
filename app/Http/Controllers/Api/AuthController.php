<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\PendaftaranService;

class AuthController extends Controller
{
    protected PendaftaranService $pendaftaran;

    public function __construct(PendaftaranService $pendaftaran)
    {
        $this->pendaftaran = $pendaftaran;
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:120',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
        ]);

        // optional: langsung buat pendaftar minimal
        // $this->pendaftaran->createPendaftar($user, ['gelombang_id'=>$request->gelombang_id ?? null]);

        // token via Sanctum (pastikan Sanctum terpasang)
        $token = method_exists($user, 'createToken') ? $user->createToken('api-token')->plainTextToken : null;

        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ], 201);
    }

    public function login(Request $request)
    {
        $creds = $request->validate(['email'=>'required|email','password'=>'required']);

        if (!Auth::attempt($creds)) {
            return response()->json(['message'=>'Unauthorized'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = method_exists($user, 'createToken') ? $user->createToken('api-token')->plainTextToken : null;

        return response()->json(['user'=>$user,'token'=>$token]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();
        return response()->json(['message'=>'Logged out']);
    }
}