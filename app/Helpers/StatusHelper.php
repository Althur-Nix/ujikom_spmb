<?php

namespace App\Helpers;

class StatusHelper
{
    public static function getStatusInfo($pendaftar)
    {
        if (!$pendaftar) {
            return [
                'status' => 'BELUM_DAFTAR',
                'label' => 'Belum Mendaftar',
                'description' => 'Silakan lengkapi form pendaftaran',
                'color' => 'secondary',
                'icon' => 'fas fa-user-plus'
            ];
        }

        // Cek apakah ada berkas yang diupload
        $hasBerkas = $pendaftar->berkas && $pendaftar->berkas->count() > 0;
        $berkasValid = $pendaftar->berkas ? $pendaftar->berkas->where('is_draft', false)->count() > 0 : false;
        
        // Cek apakah ada berkas yang ditolak
        $berkasRejected = $pendaftar->berkas ? $pendaftar->berkas->where('valid', false)->whereNotNull('catatan')->count() > 0 : false;

        switch ($pendaftar->status) {
            case 'SUBMIT':
                if ($berkasRejected) {
                    return [
                        'status' => 'REVISION_REQUIRED',
                        'label' => 'Perlu Revisi Berkas',
                        'description' => 'Ada berkas yang perlu diperbaiki',
                        'color' => 'warning',
                        'icon' => 'fas fa-exclamation-triangle'
                    ];
                }
                
                if (!$berkasValid) {
                    return [
                        'status' => 'PENDING_UPLOAD',
                        'label' => 'Menunggu Upload Berkas',
                        'description' => 'Silakan upload berkas pendaftaran',
                        'color' => 'info',
                        'icon' => 'fas fa-upload'
                    ];
                }
                
                return [
                    'status' => 'SUBMIT',
                    'label' => 'Menunggu Verifikasi Panitia',
                    'description' => 'Berkas sedang diverifikasi oleh panitia',
                    'color' => 'warning',
                    'icon' => 'fas fa-clock'
                ];

            case 'REVISION_REQUIRED':
                return [
                    'status' => 'REVISION_REQUIRED',
                    'label' => 'Perlu Revisi Berkas',
                    'description' => 'Silakan perbaiki berkas yang ditolak',
                    'color' => 'warning',
                    'icon' => 'fas fa-edit'
                ];

            case 'ADM_PASS':
                return [
                    'status' => 'ADM_PASS',
                    'label' => 'Lulus Verifikasi - Silakan Bayar',
                    'description' => 'Berkas diterima, lakukan pembayaran',
                    'color' => 'info',
                    'icon' => 'fas fa-credit-card'
                ];

            case 'ADM_REJECT':
                return [
                    'status' => 'ADM_REJECT',
                    'label' => 'Berkas Ditolak',
                    'description' => 'Pendaftaran tidak lolos verifikasi',
                    'color' => 'danger',
                    'icon' => 'fas fa-times-circle'
                ];

            case 'PAYMENT_PENDING':
                return [
                    'status' => 'PAYMENT_PENDING',
                    'label' => 'Menunggu Verifikasi Pembayaran',
                    'description' => 'Bukti bayar sedang diverifikasi keuangan',
                    'color' => 'warning',
                    'icon' => 'fas fa-hourglass-half'
                ];

            case 'PAID':
                return [
                    'status' => 'PAID',
                    'label' => 'Pembayaran Terverifikasi',
                    'description' => 'Menunggu keputusan seleksi final',
                    'color' => 'primary',
                    'icon' => 'fas fa-money-check'
                ];

            case 'ACCEPTED':
                return [
                    'status' => 'ACCEPTED',
                    'label' => 'DITERIMA - Selamat!',
                    'description' => 'Anda berhasil diterima di sekolah ini',
                    'color' => 'success',
                    'icon' => 'fas fa-trophy'
                ];

            case 'REJECTED':
                return [
                    'status' => 'REJECTED',
                    'label' => 'DITOLAK',
                    'description' => 'Mohon maaf, Anda belum berhasil',
                    'color' => 'danger',
                    'icon' => 'fas fa-times-circle'
                ];

            default:
                return [
                    'status' => 'UNKNOWN',
                    'label' => 'Status Tidak Dikenal',
                    'description' => 'Hubungi admin untuk informasi',
                    'color' => 'secondary',
                    'icon' => 'fas fa-question-circle'
                ];
        }
    }

    public static function getTimelineStatus($pendaftar)
    {
        $statusInfo = self::getStatusInfo($pendaftar);
        $currentStatus = $statusInfo['status'];

        return [
            'pendaftaran' => [
                'completed' => true,
                'label' => 'Pendaftaran Berhasil',
                'description' => 'Form pendaftaran telah diisi'
            ],
            'upload_berkas' => [
                'completed' => in_array($currentStatus, ['SUBMIT', 'ADM_PASS', 'PAYMENT_PENDING', 'PAID', 'ACCEPTED']),
                'pending' => in_array($currentStatus, ['PENDING_UPLOAD', 'REVISION_REQUIRED']),
                'rejected' => false,
                'label' => 'Upload Berkas',
                'description' => self::getBerkasDescription($currentStatus)
            ],
            'verifikasi_berkas' => [
                'completed' => in_array($currentStatus, ['ADM_PASS', 'PAYMENT_PENDING', 'PAID', 'ACCEPTED']),
                'pending' => $currentStatus === 'SUBMIT',
                'rejected' => $currentStatus === 'ADM_REJECT',
                'label' => 'Verifikasi Berkas',
                'description' => self::getVerifikasiDescription($currentStatus)
            ],
            'pembayaran' => [
                'completed' => in_array($currentStatus, ['PAID', 'ACCEPTED']),
                'pending' => $currentStatus === 'PAYMENT_PENDING',
                'waiting' => $currentStatus === 'ADM_PASS',
                'label' => 'Pembayaran',
                'description' => self::getPembayaranDescription($currentStatus)
            ],
            'seleksi_final' => [
                'completed' => $currentStatus === 'ACCEPTED',
                'rejected' => $currentStatus === 'REJECTED',
                'pending' => $currentStatus === 'PAID',
                'label' => 'Seleksi Final',
                'description' => self::getSeleksiDescription($currentStatus)
            ]
        ];
    }

    private static function getBerkasDescription($status)
    {
        switch ($status) {
            case 'SUBMIT':
            case 'ADM_PASS':
            case 'PAYMENT_PENDING':
            case 'PAID':
            case 'ACCEPTED':
                return 'Berkas telah diupload';
            case 'PENDING_UPLOAD':
                return 'Silakan upload berkas pendaftaran';
            case 'REVISION_REQUIRED':
                return 'Perbaiki berkas yang ditolak';
            default:
                return 'Belum upload berkas';
        }
    }

    private static function getVerifikasiDescription($status)
    {
        switch ($status) {
            case 'ADM_PASS':
            case 'PAYMENT_PENDING':
            case 'PAID':
            case 'ACCEPTED':
                return 'Berkas diterima - Lulus verifikasi';
            case 'ADM_REJECT':
                return 'Berkas ditolak - Tidak memenuhi syarat';
            case 'SUBMIT':
                return 'Menunggu verifikasi berkas oleh panitia';
            default:
                return 'Belum dapat diverifikasi';
        }
    }

    private static function getPembayaranDescription($status)
    {
        switch ($status) {
            case 'PAID':
            case 'ACCEPTED':
                return 'Pembayaran terverifikasi - Lunas';
            case 'PAYMENT_PENDING':
                return 'Bukti pembayaran diupload - Menunggu verifikasi';
            case 'ADM_PASS':
                return 'Silakan lakukan pembayaran';
            default:
                return 'Belum bisa melakukan pembayaran';
        }
    }

    private static function getSeleksiDescription($status)
    {
        switch ($status) {
            case 'ACCEPTED':
                return 'DITERIMA - Selamat! Anda berhasil diterima';
            case 'REJECTED':
                return 'DITOLAK - Mohon maaf, Anda belum berhasil';
            case 'PAID':
                return 'Menunggu keputusan seleksi final';
            default:
                return 'Belum sampai tahap seleksi final';
        }
    }
}