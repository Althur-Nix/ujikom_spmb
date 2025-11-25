<?php

namespace App\Exports;

use App\Models\PendaftarPembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PendaftarPembayaran::with(['pendaftar.dataSiswa', 'pendaftar.user'])
            ->where('status', 'VERIFIED')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'Nama',
            'Bank Tujuan',
            'Nama Pengirim',
            'Nominal',
            'Tanggal Transfer',
            'Status'
        ];
    }

    public function map($pembayaran): array
    {
        return [
            $pembayaran->pendaftar->no_pendaftaran ?? '-',
            optional($pembayaran->pendaftar->dataSiswa)->nama ?? optional($pembayaran->pendaftar->user)->name ?? '-',
            $pembayaran->bank_tujuan ?? '-',
            $pembayaran->nama_pengirim ?? '-',
            $pembayaran->nominal ?? 0,
            $pembayaran->tanggal_transfer ? (is_string($pembayaran->tanggal_transfer) ? date('d/m/Y', strtotime($pembayaran->tanggal_transfer)) : $pembayaran->tanggal_transfer->format('d/m/Y')) : '-',
            $pembayaran->status == 'VERIFIED' ? 'Terverifikasi' : $pembayaran->status
        ];
    }
}
