@extends('emails.base')

@section('title', 'Reset Password')
@section('header-icon', '🔑')
@section('header-title', 'Reset Password')

@section('content')
    <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
        Halo <strong>{{ $user->name }}</strong>,
    </p>
    <p style="color: #666666; font-size: 14px; line-height: 1.6; margin: 0 0 30px 0;">
        Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.
    </p>
    
    <!-- CTA Button -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <a href="{{ $resetUrl }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 25px; font-size: 16px; font-weight: bold; display: inline-block;">
                    🔑 Reset Password Sekarang
                </a>
            </td>
        </tr>
    </table>
    
    <!-- Warning Box -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 30px;">
        <tr>
            <td style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 5px;">
                <p style="color: #856404; font-size: 13px; margin: 0; line-height: 1.5;">
                    ⏰ <strong>Penting:</strong> Link ini akan kadaluarsa dalam <strong>24 jam</strong>. Jika Anda tidak meminta reset password, abaikan email ini.
                </p>
            </td>
        </tr>
    </table>
    
    <p style="color: #999999; font-size: 12px; line-height: 1.6; margin: 30px 0 0 0; text-align: center;">
        Jika tombol tidak berfungsi, salin link berikut ke browser Anda:<br>
        <a href="{{ $resetUrl }}" style="color: #667eea;">{{ $resetUrl }}</a>
    </p>
@endsection
