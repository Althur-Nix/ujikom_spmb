<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use App\Models\User;
use App\Notifications\PendaftaranSubmittedNotification;
use App\Notifications\BerkasVerifiedNotification;
use App\Notifications\BerkasRevisionNotification;
use App\Notifications\PembayaranVerifiedNotification;

class TestEmailController extends Controller
{
    public function testPendaftaranEmail()
    {
        $pendaftar = Pendaftar::with(['user', 'dataSiswa', 'jurusan'])->first();
        
        if (!$pendaftar) {
            return 'Tidak ada data pendaftar untuk testing';
        }
        
        $pendaftar->user->notify(new PendaftaranSubmittedNotification($pendaftar));
        
        return 'Email pendaftaran berhasil dikirim ke: ' . $pendaftar->user->email;
    }
    
    public function testBerkasEmail($status = 'ADM_PASS')
    {
        $pendaftar = Pendaftar::with(['user', 'dataSiswa', 'jurusan', 'gelombang'])->first();
        
        if (!$pendaftar) {
            return 'Tidak ada data pendaftar untuk testing';
        }
        
        $pendaftar->user->notify(new BerkasVerifiedNotification($pendaftar, $status));
        
        return 'Email verifikasi berkas (' . $status . ') berhasil dikirim ke: ' . $pendaftar->user->email;
    }
    
    public function testRevisiEmail()
    {
        $pendaftar = Pendaftar::with(['user', 'dataSiswa', 'berkas'])->first();
        
        if (!$pendaftar) {
            return 'Tidak ada data pendaftar untuk testing';
        }
        
        $berkasYangDitolak = collect([
            (object)['jenis' => 'IJAZAH', 'catatan' => 'Foto tidak jelas, mohon upload ulang dengan kualitas lebih baik'],
            (object)['jenis' => 'RAPOR', 'catatan' => 'Halaman tidak lengkap']
        ]);
        
        $pendaftar->user->notify(new BerkasRevisionNotification($pendaftar, $berkasYangDitolak));
        
        return 'Email revisi berkas berhasil dikirim ke: ' . $pendaftar->user->email;
    }
    
    public function testPembayaranEmail($status = 'ACCEPTED')
    {
        $pendaftar = Pendaftar::with(['user', 'dataSiswa', 'jurusan'])->first();
        
        if (!$pendaftar) {
            return 'Tidak ada data pendaftar untuk testing';
        }
        
        $pendaftar->user->notify(new PembayaranVerifiedNotification($pendaftar, $status));
        
        return 'Email verifikasi pembayaran (' . $status . ') berhasil dikirim ke: ' . $pendaftar->user->email;
    }
}
