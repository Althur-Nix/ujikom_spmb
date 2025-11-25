<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\PendaftarPembayaran;
use App\Exports\PendaftarExport;
use App\Exports\PembayaranExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function excel(Request $request)
    {
        $filters = $request->only(['status', 'jurusan_id', 'gelombang_id']);
        $filename = 'laporan-pendaftar-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(new PendaftarExport($filters), $filename);
    }

    public function pdf(Request $request)
    {
        $query = Pendaftar::with(['dataSiswa', 'jurusan', 'gelombang']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->jurusan_id) {
            $query->where('jurusan_id', $request->jurusan_id);
        }
        if ($request->gelombang_id) {
            $query->where('gelombang_id', $request->gelombang_id);
        }
        
        $pendaftar = $query->get();
        
        $pdf = Pdf::loadView('kepala-sekolah.export-pdf', compact('pendaftar'));
        return $pdf->download('laporan-pendaftar-' . date('Y-m-d-His') . '.pdf');
    }

    public function pembayaranExcel()
    {
        $filename = 'laporan-pembayaran-' . date('Y-m-d-His') . '.xlsx';
        return Excel::download(new PembayaranExport(), $filename);
    }

    public function pembayaranPdf()
    {
        $pembayaran = PendaftarPembayaran::with(['pendaftar.dataSiswa', 'pendaftar.user'])
            ->where('status', 'VERIFIED')
            ->get();
        
        $totalPembayaran = $pembayaran->sum('nominal');
        
        $pdf = Pdf::loadView('kepala-sekolah.pembayaran-pdf', compact('pembayaran', 'totalPembayaran'));
        return $pdf->download('laporan-pembayaran-' . date('Y-m-d-His') . '.pdf');
    }
}
