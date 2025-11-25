<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\Jurusan;
use App\Models\Gelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPendaftar = Pendaftar::count();
        $pendaftarVerified = Pendaftar::where('status', 'ADM_PASS')->count();
        $pendaftarPending = Pendaftar::where('status', 'SUBMIT')->count();
        $pendaftarRejected = Pendaftar::where('status', 'ADM_REJECT')->count();
        
        $recentPendaftar = Pendaftar::with(['user', 'jurusan', 'dataSiswa'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalPendaftar',
            'pendaftarVerified', 
            'pendaftarPending',
            'pendaftarRejected',
            'recentPendaftar'
        ));
    }
}