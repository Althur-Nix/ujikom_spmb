@extends('emails.base')

@section('title', 'Status Verifikasi Pendaftaran')
@section('header-icon', $pendaftar->status === 'ADM_PASS' ? '✅' : '❌')
@section('header-title', 'Status Verifikasi Pendaftaran')

@section('content')
    <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
        Halo <strong>{{ $pendaftar->nama }}</strong>,
    </p>
    <p style="color: #666666; font-size: 14px; line-height: 1.6; margin: 0 0 30px 0;">
        Status verifikasi pendaftaran Anda telah diperbarui.
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
                        <td style="color: #666666; font-size: 14px; padding: 8px 0;">Status</td>
                        <td style="color: {{ $pendaftar->status === 'ADM_PASS' ? '#00b894' : '#e74c3c' }}; font-size: 14px; font-weight: bold; padding: 8px 0;">
                            : {{ $pendaftar->status === 'ADM_PASS' ? 'LOLOS' : 'TIDAK LOLOS' }}
                        </td>
                    </tr>
                    @if($pendaftar->catatan_verifikasi)
                    <tr>
                        <td style="color: #666666; font-size: 14px; padding: 8px 0; vertical-align: top;">Catatan</td>
                        <td style="color: #333333; font-size: 14px; padding: 8px 0;">: {{ $pendaftar->catatan_verifikasi }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>
    
    @if($pendaftar->status === 'ADM_PASS')
        <!-- Success Box -->
        <div style="background-color: #d1f2eb; border-left: 4px solid #00b894; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
            <p style="color: #00b894; font-size: 14px; font-weight: bold; margin: 0 0 10px 0;">🎉 Selamat!</p>
            <p style="color: #00b894; font-size: 13px; margin: 0; line-height: 1.8;">
                Verifikasi pendaftaran Anda berhasil. Silakan lanjutkan ke tahap berikutnya.
            </p>
        </div>
    @else
        <!-- Error Box -->
        <div style="background-color: #ffeaa7; border-left: 4px solid #e74c3c; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
            <p style="color: #e74c3c; font-size: 14px; font-weight: bold; margin: 0 0 10px 0;">❌ Mohon Maaf</p>
            <p style="color: #e74c3c; font-size: 13px; margin: 0; line-height: 1.8;">
                Verifikasi pendaftaran Anda belum berhasil. Silakan periksa catatan di atas dan lakukan perbaikan yang diperlukan.
            </p>
        </div>
    @endif
    
    <!-- CTA Button -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <a href="{{ url('/pendaftaran/status') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 25px; font-size: 16px; font-weight: bold; display: inline-block;">
                    📋 Lihat Detail Status
                </a>
            </td>
        </tr>
    </table>
    
    <p style="color: #999999; font-size: 12px; line-height: 1.6; margin: 30px 0 0 0; text-align: center;">
        Jika tombol tidak berfungsi, salin link berikut ke browser Anda:<br>
        <a href="{{ url('/pendaftaran/status') }}" style="color: #667eea;">{{ url('/pendaftaran/status') }}</a>
    </p>
@endsection