<?php

namespace App\Http\Controllers;

use App\Exports\PendaftarExport;
use App\Models\Pendaftar;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportPendaftarExcel($status = null)
    {
        $filename = 'pendaftar_' . ($status ?? 'semua') . '_' . date('Y-m-d') . '.xlsx';
        return Excel::download(new PendaftarExport($status), $filename);
    }

    public function exportPendaftarPdf($status = null)
    {
        $query = Pendaftar::with(['user', 'dataSiswa', 'jurusan', 'gelombang']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $pendaftar = $query->get();
        $title = 'Data Pendaftar ' . ($status ? strtoupper($status) : 'Semua');
        
        $pdf = Pdf::loadView('exports.pendaftar-pdf', compact('pendaftar', 'title'));
        return $pdf->download('pendaftar_' . ($status ?? 'semua') . '_' . date('Y-m-d') . '.pdf');
    }

    public function exportPembayaranExcel()
    {
        $pendaftar = Pendaftar::with(['user', 'dataSiswa', 'jurusan', 'gelombang'])
            ->whereIn('status', ['PAID', 'ACCEPTED'])
            ->get();

        $data = $pendaftar->map(function($p) {
            return [
                $p->no_pendaftaran,
                $p->dataSiswa->nama ?? '-',
                $p->jurusan->nama ?? '-',
                'Rp ' . number_format($p->gelombang->biaya_daftar ?? 0, 0, ',', '.'),
                $p->status,
                $p->updated_at->format('d-m-Y H:i')
            ];
        });

        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return $this->data; }
            public function headings(): array {
                return ['No Pendaftaran', 'Nama', 'Jurusan', 'Biaya', 'Status', 'Tanggal'];
            }
        }, 'pembayaran_' . date('Y-m-d') . '.xlsx');
    }

    public function exportPembayaranPdf()
    {
        $pendaftar = Pendaftar::with(['user', 'dataSiswa', 'jurusan', 'gelombang'])
            ->whereIn('status', ['PAID', 'ACCEPTED'])
            ->get();
        
        $total = $pendaftar->sum(function($p) {
            return $p->gelombang->biaya_daftar ?? 0;
        });

        $pdf = Pdf::loadView('exports.pembayaran-pdf', compact('pendaftar', 'total'));
        return $pdf->download('pembayaran_' . date('Y-m-d') . '.pdf');
    }
}
