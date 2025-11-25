<!DOCTYPE html>
<html>
<head>
    <title>Cetak Kartu Pendaftar</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .no-print { display: block; }
        .kartu-container { border: 2px solid #000; max-width: 800px; margin: 0 auto; }
        .kartu-header { background: #3498db; color: white; padding: 20px; text-align: center; }
        .kartu-body { padding: 30px; }
        .table { width: 100%; border-collapse: collapse; }
        .table td { padding: 8px; vertical-align: top; }
        .foto-box { width: 120px; height: 160px; border: 1px solid #000; text-align: center; padding: 20px; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">Cetak Kartu</button>
        <a href="{{ route('pendaftar.dashboard') }}" style="margin-left: 10px; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none;">Kembali</a>
    </div>
    <div class="kartu-container">
        <div class="kartu-header">
            <h2 style="margin: 0;">KARTU PENDAFTAR</h2>
            <h3 style="margin: 5px 0 0 0;">SMK BAKTI NUSANTARA 666</h3>
        </div>
        <div class="kartu-body">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 65%; vertical-align: top;">
                        <table class="table">
                            <tr>
                                <td width="30%"><strong>No. Pendaftaran</strong></td>
                                <td width="5%">:</td>
                                <td>{{ $pendaftar->no_pendaftaran ?? '2024' . str_pad(rand(1,999), 3, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>:</td>
                                <td>{{ optional($pendaftar->dataSiswa)->nama ?? optional($pendaftar->user)->name ?? 'Nama Pendaftar' }}</td>
                            </tr>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>:</td>
                                <td>{{ optional($pendaftar->dataSiswa)->nisn ?? '1234567890' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jurusan</strong></td>
                                <td>:</td>
                                <td>{{ optional($pendaftar->jurusan)->nama ?? 'Rekayasa Perangkat Lunak' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Gelombang</strong></td>
                                <td>:</td>
                                <td>{{ optional($pendaftar->gelombang)->nama ?? 'Gelombang 1 - 2024' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tempat, Tgl Lahir</strong></td>
                                <td>:</td>
                                <td>{{ (optional($pendaftar->dataSiswa)->tempat_lahir ?? 'Jakarta') . (optional($pendaftar->dataSiswa)->tgl_lahir ? ', ' . optional($pendaftar->dataSiswa)->tgl_lahir->format('d/m/Y') : ', 01/01/2000') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>:</td>
                                <td>
                                    @switch($pendaftar->status ?? 'DRAFT')
                                        @case('SUBMIT')
                                            <strong>Menunggu Verifikasi</strong>
                                            @break
                                        @case('ADM_PASS')
                                            <strong>Lulus Administrasi</strong>
                                            @break
                                        @case('PAYMENT_PENDING')
                                            <strong>Menunggu Verifikasi Pembayaran</strong>
                                            @break
                                        @case('PAID')
                                            <strong>Pembayaran Terverifikasi</strong>
                                            @break
                                        @case('ACCEPTED')
                                            <strong>DITERIMA</strong>
                                            @break
                                        @default
                                            <strong>{{ $pendaftar->status ?? 'Draft' }}</strong>
                                    @endswitch
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 35%; text-align: center; vertical-align: top;">
                        <div class="foto-box">
                            <strong>Foto 3x4</strong><br>
                            <small>Tempel foto di sini</small>
                        </div>
                    </td>
                </tr>
            </table>
            
            <div style="border-top: 1px solid #000; margin-top: 20px; padding-top: 15px;">
                <p style="margin: 0 0 10px 0;"><strong>Catatan Penting:</strong></p>
                <ul style="margin: 0; padding-left: 20px; font-size: 12px;">
                    <li>Kartu ini adalah bukti pendaftaran resmi SMK BAKTI NUSANTARA 666</li>
                    <li>Harap disimpan dengan baik dan dibawa saat mengikuti tes atau kegiatan sekolah</li>
                    <li>Tempel foto 3x4 terbaru pada tempat yang telah disediakan</li>
                    <li>Kartu ini tidak dapat dipindahtangankan</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
                Dicetak pada: {{ date('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>

