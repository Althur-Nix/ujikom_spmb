<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class DashboardController extends Controller
{
    public function siswa()
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->first();
        
        return view('dashboard.siswa', compact('pendaftaran'));
    }
    
    public function admin()
    {
        $stats = [
            'total_pendaftar' => Pendaftaran::count(),
            'menunggu_verifikasi' => Pendaftaran::where('status', 'submitted')->count(),
            'sudah_bayar' => Pendaftaran::where('status', 'paid')->count(),
            'diterima' => Pendaftaran::where('status', 'accepted')->count(),
        ];
        
        $recent = Pendaftaran::with('user')->latest()->take(10)->get();
        
        return view('dashboard.admin', compact('stats', 'recent'));
    }
}