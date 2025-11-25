<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\Jurusan;
use App\Models\PendaftarPembayaran;
use App\Models\PendaftarDataSiswa;
use App\Models\PendaftarAsalSekolah;
use Illuminate\Support\Facades\DB;

class KepalaSekolahController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->session()->get('user');
        $mockUser = (object) $user;

        // Statistik keseluruhan
        $stats = [
            'total_pendaftar' => Pendaftar::count(),
            'diterima' => Pendaftar::where('status', 'ACCEPTED')->count(),
            'ditolak' => Pendaftar::where('status', 'REJECTED')->count(),
            'pending' => Pendaftar::where('status', 'SUBMIT')->count(),
            'total_pembayaran' => PendaftarPembayaran::where('status', 'VERIFIED')->sum('nominal') ?? 0
        ];

        // Data untuk grafik pendaftar per bulan
        $pendaftarPerBulan = Pendaftar::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as jumlah')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        // Data untuk grafik status pendaftar
        $statusData = Pendaftar::select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->get();

        // Data pendaftar per jurusan
        $pendaftarPerJurusan = Jurusan::withCount('pendaftar')
            ->get();

        // Data asal sekolah
        $asalSekolah = PendaftarAsalSekolah::select('nama_sekolah', DB::raw('COUNT(*) as total'))
            ->whereNotNull('nama_sekolah')
            ->groupBy('nama_sekolah')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Data asal wilayah berdasarkan kabupaten
        $asalWilayah = PendaftarDataSiswa::with(['regency', 'province'])
            ->select('regency_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('regency_id')
            ->groupBy('regency_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Menyesuaikan nama variabel dengan view
        $totalPendaftar = $stats['total_pendaftar'];
        $pendaftarDiterima = $stats['diterima'];
        $totalPembayaran = $stats['total_pembayaran'];
        $dataBulanan = $pendaftarPerBulan->map(function($item) {
            return [
                'bulan' => date('M', mktime(0, 0, 0, $item->bulan, 1)),
                'total' => $item->jumlah
            ];
        })->toArray();
        $dataJurusan = $pendaftarPerJurusan;

        return view('kepala-sekolah.dashboard', compact(
            'mockUser', 
            'totalPendaftar',
            'pendaftarDiterima', 
            'totalPembayaran',
            'dataBulanan',
            'dataJurusan',
            'asalSekolah',
            'asalWilayah'
        ));
    }

    public function daftarPendaftar(Request $request)
    {
        $user = $request->session()->get('user');
        $mockUser = (object) $user;
        
        $pendaftar = Pendaftar::with(['user', 'jurusan', 'dataSiswa'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('kepala-sekolah.pendaftar', compact('pendaftar', 'mockUser'));
    }

    public function pendaftarDiterima(Request $request)
    {
        $user = $request->session()->get('user');
        $mockUser = (object) $user;
        
        $pendaftar = Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'asalSekolah'])
            ->where('status', 'ACCEPTED')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('kepala-sekolah.diterima', compact('pendaftar', 'mockUser'));
    }

    public function rekapPembayaran(Request $request)
    {
        $user = $request->session()->get('user');
        $mockUser = (object) $user;
        
        // Hanya tampilkan pembayaran yang terverifikasi
        $pembayaran = PendaftarPembayaran::with(['pendaftar.user', 'pendaftar.dataSiswa'])
            ->where('status', 'VERIFIED')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalPembayaran = PendaftarPembayaran::where('status', 'VERIFIED')->sum('nominal');
        
        return view('kepala-sekolah.pembayaran', compact('pembayaran', 'totalPembayaran', 'mockUser'));
    }

    public function dataAsalSekolah(Request $request)
    {
        $user = $request->session()->get('user');
        $mockUser = (object) $user;
        
        $asalSekolah = PendaftarAsalSekolah::select('nama_sekolah', DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('nama_sekolah')
            ->groupBy('nama_sekolah')
            ->orderBy('jumlah', 'desc')
            ->paginate(20);
        
        return view('kepala-sekolah.asal-sekolah', compact('asalSekolah', 'mockUser'));
    }

    public function dataAsalWilayah(Request $request)
    {
        $user = $request->session()->get('user');
        $mockUser = (object) $user;
        
        // Data per kabupaten untuk tabel
        $asalWilayah = PendaftarDataSiswa::with(['regency.province'])
            ->select('regency_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('regency_id')
            ->groupBy('regency_id')
            ->orderBy('total', 'desc')
            ->paginate(20);
        
        // Data detail per kecamatan untuk tabel
        $detailWilayah = PendaftarDataSiswa::with(['district.regency.province'])
            ->select('district_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('district_id')
            ->groupBy('district_id')
            ->orderBy('total', 'desc')
            ->limit(50)
            ->get();
        
        return view('kepala-sekolah.asal-wilayah', compact('asalWilayah', 'detailWilayah', 'mockUser'));
    }
}