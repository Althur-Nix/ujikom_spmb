<?php

namespace App\Services;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Simpan log aktivitas terstandard.
     *
     * @param string $aksi
     * @param string $objek
     * @param array $data
     * @param int|null $userId
     * @return LogAktivitas
     */
    public function log(string $aksi, string $objek, array $data = [], ?int $userId = null): LogAktivitas
    {
        $record = LogAktivitas::create([
            'user_id' => $userId,
            'aksi' => $aksi,
            'objek' => $objek,
            'objek_id' => $data['objek_id'] ?? null,
            'objek_data' => $data,
            'waktu' => now(),
            'ip' => Request::ip(),
        ]);

        return $record;
    }

    public function logVerifikasi(string $jenis, int $pendaftarId, string $status, ?string $catatan = null, ?int $userId = null): LogAktivitas
    {
        return $this->log(
            "verifikasi_{$jenis}",
            "pendaftar",
            [
                'objek_id' => $pendaftarId,
                'status' => $status,
                'catatan' => $catatan
            ],
            $userId
        );
    }
}