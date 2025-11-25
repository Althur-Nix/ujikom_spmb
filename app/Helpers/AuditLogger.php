<?php

namespace App\Helpers;

use App\Models\LogAktivitas;

class AuditLogger
{
    public static function log($aksi, $objek = null, $objekId = null, $meta = [])
    {
        $userId = session('user.id');
        
        LogAktivitas::create([
            'user_id' => $userId,
            'aksi' => $aksi,
            'objek' => $objek,
            'objek_id' => $objekId,
            'meta' => $meta
        ]);
    }
}
