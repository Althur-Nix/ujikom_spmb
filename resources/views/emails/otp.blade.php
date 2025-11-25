@extends('emails.base')

@section('title', 'Kode OTP Verifikasi')
@section('header-icon', '🔐')
@section('header-title', 'Kode Verifikasi OTP')

@section('content')
    <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
        Halo, Calon Peserta Didik!
    </p>
    <p style="color: #666666; font-size: 14px; line-height: 1.6; margin: 0 0 30px 0;">
        Gunakan kode OTP berikut untuk melanjutkan proses pendaftaran Anda:
    </p>
    
    <!-- OTP Box -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; padding: 25px; display: inline-block;">
                    <p style="color: #ffffff; font-size: 14px; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 2px;">Kode OTP Anda</p>
                    <p style="color: #ffffff; font-size: 42px; font-weight: bold; margin: 0; letter-spacing: 8px; font-family: 'Courier New', monospace;">{{ $code }}</p>
                </div>
            </td>
        </tr>
    </table>
    
    <!-- Warning Box -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 30px;">
        <tr>
            <td style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 5px;">
                <p style="color: #856404; font-size: 13px; margin: 0; line-height: 1.5;">
                    ⏰ <strong>Penting:</strong> Kode ini berlaku selama <strong>5 menit</strong>. Jangan bagikan kode ini kepada siapapun!
                </p>
            </td>
        </tr>
    </table>
    
    <p style="color: #999999; font-size: 12px; line-height: 1.6; margin: 30px 0 0 0; text-align: center;">
        Jika Anda tidak melakukan permintaan ini, abaikan email ini.
    </p>
@endsection
