<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifikasiService
{
    /**
     * Kirim kode verifikasi ke user (simulasi via mail log).
     */
    public function kirimKodeVerifikasi(User $user, string $code): bool
    {
        $to = $user->email;
        $subject = "Kode Verifikasi SPMB";
        $body = "Halo {$user->name}, kode verifikasi Anda: {$code}";

        try {
            // Mail diset di .env sebagai log — gunakan Mail::raw supaya tercatat di log driver
            Mail::raw($body, function ($m) use ($to, $subject) {
                $m->to($to)->subject($subject);
            });

            Log::info("NotifikasiService::kirimKodeVerifikasi sent to {$to}", ['code' => $code, 'user_id' => $user->id]);
            return true;
        } catch (\Throwable $e) {
            Log::warning("NotifikasiService::kirimKodeVerifikasi failed: {$e->getMessage()}", ['user_id' => $user->id]);
            return false;
        }
    }
    /**
     * Kirim notifikasi perubahan status pendaftaran (simulasi).
     */
    public function kirimNotifStatus(User $user, string $jenisStatus): bool
    {
        $to = $user->email;
        $subject = "Status Pendaftaran: {$jenisStatus}";
        $body = "Halo {$user->name}, status pendaftaran Anda telah berubah menjadi: {$jenisStatus}";

        try {
            Mail::raw($body, function ($m) use ($to, $subject) {
                $m->to($to)->subject($subject);
            });
            Log::info("NotifikasiService::kirimNotifStatus sent to {$to}", ['status' => $jenisStatus, 'user_id' => $user->id]);
            return true;
        } catch (\Throwable $e) {
            Log::warning("NotifikasiService::kirimNotifStatus failed: {$e->getMessage()}", ['user_id' => $user->id]);
            return false;
        }
    }
}