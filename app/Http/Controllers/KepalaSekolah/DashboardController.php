<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\PendaftarDataSiswa;
use App\Models\PendaftarAsalSekolah;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->session()->get('user');
        $mockUser = (object) $user;
        
        // Data untuk grafik dan monitoring
        $totalPendaftar = Pendaftar::count();
        $pendaftarDiterima = Pendaftar::where('status', 'ACCEPTED')->count();
        $totalPembayaran = Pendaftar::join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->where('pendaftar.status', 'PAID')
            ->sum('gelombang.biaya_daftar');
        
        // Data asal sekolah
        $asalSekolah = PendaftarAsalSekolah::select('nama_sekolah', DB::raw('count(*) as total'))
            ->whereNotNull('nama_sekolah')
            ->groupBy('nama_sekolah')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Data asal wilayah berdasarkan kabupaten/kota
        $asalWilayah = PendaftarDataSiswa::with(['regency.province'])
            ->select('regency_id', DB::raw('count(*) as total'))
            ->whereNotNull('regency_id')
            ->groupBy('regency_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Data per jurusan
        $dataJurusan = Jurusan::withCount(['pendaftar', 'pendaftarDiterima' => function($query) {
            $query->where('status', 'ACCEPTED');
        }])->get();
        
        // Data per bulan (6 bulan terakhir)
        $dataBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $dataBulanan[] = [
                'bulan' => $bulan->format('M Y'),
                'total' => Pendaftar::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->count()
            ];
        }
        
        return view('kepala-sekolah.dashboard', compact(
            'mockUser', 'totalPendaftar', 'pendaftarDiterima', 'totalPembayaran',
            'asalSekolah', 'asalWilayah', 'dataJurusan', 'dataBulanan'
        ));
    }

    public function asalWilayah(Request $request)
    {
        $user = $request->session()->get('user');
        $mockUser = (object) $user;
        $asalWilayah = PendaftarDataSiswa::with(['regency.province'])
            ->select('regency_id', DB::raw('count(*) as total'))
            ->whereNotNull('regency_id')
            ->groupBy('regency_id')
            ->orderBy('total', 'desc')
            ->paginate(15);
        
        $detailWilayah = PendaftarDataSiswa::with(['district.regency.province'])
            ->select('district_id', DB::raw('count(*) as total'))
            ->whereNotNull('district_id')
            ->groupBy('district_id')
            ->orderBy('total', 'desc')
            ->get();
        
        return view('kepala-sekolah.asal-wilayah', compact('mockUser', 'asalWilayah', 'detailWilayah'));
    }
}