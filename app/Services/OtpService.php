<?php

namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpService
{
    public function generate(string $email): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Hapus hanya OTP yang belum verified untuk email ini
        Otp::where('email', $email)
            ->where('is_verified', false)
            ->delete();
        
        Otp::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(5),
            'is_verified' => false,
        ]);
        
        $this->sendOtp($email, $code);
        
        return $code;
    }
    
    public function verify(string $email, string $code): bool
    {
        $otp = Otp::where('email', $email)
            ->where('code', $code)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();
            
        if ($otp) {
            $otp->update(['is_verified' => true]);
            return true;
        }
        
        return false;
    }
    
    private function sendOtp(string $email, string $code): void
    {
        // Log OTP untuk testing
        \Log::info("OTP untuk {$email}: {$code}");
        
        try {
            Mail::send('emails.otp', ['code' => $code], function ($message) use ($email) {
                $message->to($email)
                    ->subject('🔐 Kode OTP Verifikasi - SPMB SMK Bakti Nusantara 666');
            });
        } catch (\Exception $e) {
            \Log::error("Gagal kirim email OTP: " . $e->getMessage());
        }
    }
}
