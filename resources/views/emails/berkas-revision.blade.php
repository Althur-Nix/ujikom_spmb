@extends('emails.base')

@section('title', 'Revisi Berkas Diperlukan')
@section('header-icon', '📝')
@section('header-title', 'Revisi Berkas Diperlukan')

@section('content')
    <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
        Halo <strong>{{ $pendaftar->dataSiswa->nama }}</strong>,
    </p>
    <p style="color: #666666; font-size: 14px; line-height: 1.6; margin: 0 0 30px 0;">
        Berkas administrasi Anda memerlukan <strong>REVISI</strong>. Silakan periksa dan perbaiki berkas berikut:
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
                </table>
            </td>
        </tr>
    </table>
    
    <!-- Berkas yang Ditolak -->
    <div style="background-color: #ffeaa7; border-left: 4px solid #fdcb6e; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
        <p style="color: #e17055; font-size: 14px; font-weight: bold; margin: 0 0 10px 0;">📋 Berkas yang Perlu Diperbaiki:</p>
        <ul style="color: #e17055; font-size: 13px; margin: 0; padding-left: 20px; line-height: 1.8;">
            @foreach($berkasYangDitolak as $berkas)
            <li><strong>{{ $berkas->jenis }}</strong>: {{ $berkas->catatan ?? 'Perlu diperbaiki' }}</li>
            @endforeach
        </ul>
    </div>
    
    <!-- CTA Button -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <a href="{{ url('/pendaftar/berkas') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 25px; font-size: 16px; font-weight: bold; display: inline-block;">
                    📤 Upload Ulang Berkas
                </a>
            </td>
        </tr>
    </table>
    
    <p style="color: #999999; font-size: 12px; line-height: 1.6; margin: 30px 0 0 0; text-align: center;">
        Jika tombol tidak berfungsi, salin link berikut ke browser Anda:<br>
        <a href="{{ url('/pendaftar/berkas') }}" style="color: #667eea;">{{ url('/pendaftar/berkas') }}</a>
    </p>
@endsection