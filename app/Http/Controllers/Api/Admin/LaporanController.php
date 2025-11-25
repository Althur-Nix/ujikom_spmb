<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exports\PendaftarExport;
use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    /**
     * GET api/admin/laporan/pendaftar?format=pdf|xlsx&gelombang_id=&jurusan_id=
     */
    public function exportPendaftar(Request $request)
    {
        $format = strtolower($request->query('format', 'xlsx'));
        $filters = [
            'gelombang_id' => $request->query('gelombang_id'),
            'jurusan_id' => $request->query('jurusan_id'),
        ];

        $query = Pendaftar::with(['user','jurusan','gelombang','pendaftar_data_siswa','pendaftar_asal_sekolah'])->select('pendaftar.*');

        if ($filters['gelombang_id']) $query->where('gelombang_id', $filters['gelombang_id']);
        if ($filters['jurusan_id']) $query->where('jurusan_id', $filters['jurusan_id']);

        if ($format === 'pdf') {
            // jika tidak ada kolom tanggal_daftar di DB gunakan created_at sebagai fallback
            $rows = $query->orderBy('created_at')->get();
            $pdf = Pdf::loadView('exports.pendaftar_pdf', ['rows' => $rows]);
            $filename = 'pendaftar_' . now()->format('Ymd_His') . '.pdf';
            return $pdf->download($filename);
        }

        $export = new PendaftarExport($query->orderBy('created_at'));
        $filename = 'pendaftar_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download($export, $filename);
    }
}