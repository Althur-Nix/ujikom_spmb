<?php

namespace App\Exports;

use App\Models\Pendaftar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PendaftarExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pendaftar::with(['dataSiswa', 'jurusan', 'gelombang']);
        
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['jurusan_id'])) {
            $query->where('jurusan_id', $this->filters['jurusan_id']);
        }
        if (!empty($this->filters['gelombang_id'])) {
            $query->where('gelombang_id', $this->filters['gelombang_id']);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'Nama',
            'Email',
            'No. HP',
            'Jurusan',
            'Gelombang',
            'Status',
            'Tanggal Daftar'
        ];
    }

    public function map($pendaftar): array
    {
        return [
            $pendaftar->no_pendaftaran,
            $pendaftar->dataSiswa->nama ?? '-',
            $pendaftar->user->email ?? '-',
            $pendaftar->dataSiswa->hp ?? '-',
            $pendaftar->jurusan->nama ?? '-',
            $pendaftar->gelombang->nama ?? '-',
            $this->getStatusLabel($pendaftar->status),
            $pendaftar->created_at->format('d/m/Y H:i')
        ];
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'SUBMIT' => 'Dikirim',
            'ADM_PASS' => 'Lulus Administrasi',
            'ADM_REJECT' => 'Ditolak Administrasi',
            'PAYMENT_PENDING' => 'Menunggu Verifikasi Bayar',
            'PAID' => 'Terbayar',
            'ACCEPTED' => 'Diterima',
            'REJECTED' => 'Ditolak'
        ];
        return $labels[$status] ?? $status;
    }
}
