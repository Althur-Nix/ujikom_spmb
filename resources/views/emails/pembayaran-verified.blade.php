@extends('emails.base')

@section('title', $status === 'ACCEPTED' ? 'Selamat! Anda Diterima' : 'Pembayaran Ditolak')
@section('header-icon', $status === 'ACCEPTED' ? '🎉' : '❌')
@section('header-title', $status === 'ACCEPTED' ? 'Selamat! Anda Diterima!' : 'Pembayaran Ditolak')

@section('content')
    <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
        Halo <strong>{{ $pendaftar->dataSiswa->nama }}</strong>,
    </p>
    
    @if($status === 'ACCEPTED')
        <p style="color: #666666; font-size: 14px; line-height: 1.6; margin: 0 0 30px 0;">
            <strong style="color: #00b894;">SELAMAT!</strong> Pembayaran Anda telah diverifikasi dan Anda <strong style="color: #00b894;">DITERIMA</strong> di SMK Bakti Nusantara 666.
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
                            <td style="color: #666666; font-size: 14px; padding: 8px 0;">Jurusan</td>
                            <td style="color: #333333; font-size: 14px; font-weight: bold; padding: 8px 0;">: {{ $pendaftar->jurusan->nama }}</td>
                        </tr>
                        <tr>
                            <td style="color: #666666; font-size: 14px; padding: 8px 0;">Status</td>
                            <td style="color: #00b894; font-size: 14px; font-weight: bold; padding: 8px 0;">: DITERIMA</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <!-- Success Box -->
        <div style="background-color: #d1f2eb; border-left: 4px solid #00b894; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
            <p style="color: #00b894; font-size: 14px; font-weight: bold; margin: 0 0 10px 0;">🎓 Langkah Selanjutnya:</p>
            <p style="color: #00b894; font-size: 13px; margin: 0; line-height: 1.8;">
                Silakan cetak kartu pendaftaran Anda sebagai bukti penerimaan dan simpan dengan baik.
            </p>
        </div>
        
        <!-- CTA Button -->
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center" style="padding: 20px 0;">
                    <a href="{{ url('/pendaftar/cetak') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 25px; font-size: 16px; font-weight: bold; display: inline-block;">
                        🖨️ Cetak Kartu Pendaftaran
                    </a>
                </td>
            </tr>
        </table>
        
        <!-- Welcome Message -->
        <div style="background-color: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; border-radius: 5px; margin-top: 30px;">
            <p style="color: #1976d2; font-size: 14px; font-weight: bold; margin: 0 0 10px 0;">🏫 Selamat Bergabung!</p>
            <p style="color: #1976d2; font-size: 13px; margin: 0; line-height: 1.8;">
                Selamat bergabung dengan keluarga besar SMK Bakti Nusantara 666! Kami berkomitmen memberikan pendidikan terbaik untuk masa depan Anda.
            </p>
        </div>
        
        <p style="color: #999999; font-size: 12px; line-height: 1.6; margin: 30px 0 0 0; text-align: center;">
            Jika tombol tidak berfungsi, salin link berikut ke browser Anda:<br>
            <a href="{{ url('/pendaftar/cetak') }}" style="color: #667eea;">{{ url('/pendaftar/cetak') }}</a>
        </p>
    @else
        <p style="color: #666666; font-size: 14px; line-height: 1.6; margin: 0 0 30px 0;">
            Mohon maaf, bukti pembayaran Anda <strong style="color: #e74c3c;">DITOLAK</strong>.
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
        
        <!-- Error Box -->
        <div style="background-color: #ffeaa7; border-left: 4px solid #e74c3c; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
            <p style="color: #e74c3c; font-size: 14px; font-weight: bold; margin: 0 0 10px 0;">💳 Tindakan yang Diperlukan:</p>
            <p style="color: #e74c3c; font-size: 13px; margin: 0; line-height: 1.8;">
                Silakan upload ulang bukti pembayaran yang valid. Pastikan bukti pembayaran jelas dan sesuai dengan nominal yang diminta.
            </p>
        </div>
        
        <!-- CTA Button -->
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center" style="padding: 20px 0;">
                    <a href="{{ url('/pendaftar/pembayaran') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 25px; font-size: 16px; font-weight: bold; display: inline-block;">
                        💳 Upload Ulang Bukti Pembayaran
                    </a>
                </td>
            </tr>
        </table>
        
        <p style="color: #999999; font-size: 12px; line-height: 1.6; margin: 30px 0 0 0; text-align: center;">
            Jika tombol tidak berfungsi, salin link berikut ke browser Anda:<br>
            <a href="{{ url('/pendaftar/pembayaran') }}" style="color: #667eea;">{{ url('/pendaftar/pembayaran') }}</a>
        </p>
    @endif
@endsection