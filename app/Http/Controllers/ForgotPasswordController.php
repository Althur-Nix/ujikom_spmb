<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email belum terdaftar'], 404);
        }

        // Generate token
        $token = Str::random(60);

        // Hapus token lama jika ada
        PasswordReset::where('email', $request->email)->delete();

        // Simpan token baru
        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Kirim email (untuk sementara return token)
        $resetUrl = url('/reset-password?token=' . $token . '&email=' . urlencode($request->email));

        try {
            Mail::send('emails.reset-password', ['resetUrl' => $resetUrl, 'user' => $user], function($message) use ($user) {
                $message->to($user->email);
                $message->subject('Reset Password - SPMB');
            });
        } catch (\Exception $e) {
            // Jika email gagal dikirim, tetap return success dengan link untuk testing
        }

        return response()->json([
            'success' => true, 
            'message' => 'Link reset password telah dikirim ke email Anda'
        ]);
    }

    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect('/')->with('error', 'Link tidak valid');
        }

        $passwordReset = PasswordReset::where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$passwordReset) {
            return redirect('/')->with('error', 'Link reset password tidak valid atau sudah kadaluarsa');
        }

        // Cek apakah token sudah expired (24 jam)
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            PasswordReset::where('email', $email)->delete();
            return redirect('/')->with('error', 'Link reset password sudah kadaluarsa');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:6|confirmed'
            ]);

            $passwordReset = PasswordReset::where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (!$passwordReset) {
                return response()->json(['success' => false, 'message' => 'Token tidak valid'], 400);
            }

            // Cek expired
            if (now()->diffInHours($passwordReset->created_at) > 24) {
                PasswordReset::where('email', $request->email)->delete();
                return response()->json(['success' => false, 'message' => 'Token sudah kadaluarsa'], 400);
            }

            // Update password
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
            }
            
            $user->update(['password' => bcrypt($request->password)]);

            // Hapus token
            PasswordReset::where('email', $request->email)->delete();

            return response()->json(['success' => true, 'message' => 'Password berhasil direset']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Password minimal 6 karakter dan harus sama'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
