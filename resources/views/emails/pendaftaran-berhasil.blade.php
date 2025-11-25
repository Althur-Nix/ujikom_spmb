@extends('emails.base')

@section('title', 'Pendaftaran Berhasil')
@section('header-icon', '🎉')
@section('header-title', 'Pendaftaran Berhasil!')

@section('content')
    <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
        Halo <strong>{{ $pendaftar->dataSiswa->nama }}</strong>,
    </p>
    <p style="color: #666666; font-size: 14px; line-height: 1.6; margin: 0 0 30px 0;">
        Selamat! Pendaftaran Anda telah berhasil disimpan. Berikut detail pendaftaran Anda:
    </p>
    
    <!-- Info Box -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 8px; margin-bottom: 30px;">
        <tr>
            <td style="padding: 20px;">
                <table width="100%" cellpadding="5" cellspacing="0">
                    <tr>
                        <td style="color: #666666; font-size: 14px; padding: 8px 0;">No. Pendaftaran</td>
                        <td style="color: #333333; font-size: 14px; font-weight: bold; padding: 8px 0;">: {{ $pendaftar->no_pendaftaran }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666666; font-size: 14px; padding: 8px 0;">Jurusan Pilihan</td>
                        <td style="color: #333333; font-size: 14px; font-weight: bold; padding: 8px 0;">: {{ $pendaftar->jurusan->nama }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666666; font-size: 14px; padding: 8px 0;">Status</td>
                        <td style="color: #333333; font-size: 14px; font-weight: bold; padding: 8px 0;">: <span style="color: #3498db;">Silakan Upload Berkas</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    <!-- Steps -->
    <div style="background-color: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
        <p style="color: #1976d2; font-size: 14px; font-weight: bold; margin: 0 0 10px 0;">📋 Langkah Selanjutnya:</p>
        <ol style="color: #1976d2; font-size: 13px; margin: 0; padding-left: 20px; line-height: 1.8;">
            <li>Upload berkas pendaftaran (Ijazah, Rapor, Akta, KK, dll)</li>
            <li>Tunggu verifikasi dari panitia</li>
            <li>Lakukan pembayaran setelah berkas diverifikasi</li>
            <li>Tunggu pengumuman seleksi final</li>
        </ol>
    </div>
    
    <!-- CTA Button -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <a href="{{ url('/pendaftar/berkas') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 25px; font-size: 16px; font-weight: bold; display: inline-block;">
                    📤 Upload Berkas Sekarang
                </a>
            </td>
        </tr>
    </table>
    
    <p style="color: #999999; font-size: 12px; line-height: 1.6; margin: 30px 0 0 0; text-align: center;">
        Jika tombol tidak berfungsi, salin link berikut ke browser Anda:<br>
        <a href="{{ url('/pendaftar/berkas') }}" style="color: #667eea;">{{ url('/pendaftar/berkas') }}</a>
    </p>
@endsection
