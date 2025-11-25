<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PembayaranVerifiedNotification extends Notification
{
    use Queueable;

    protected $pendaftar;
    protected $status;

    public function __construct($pendaftar, $status)
    {
        $this->pendaftar = $pendaftar;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->status === 'ACCEPTED' 
            ? 'Selamat! Anda Diterima di SMK Bakti Nusantara 666'
            : 'Pembayaran Ditolak - SMK Bakti Nusantara 666';
            
        return (new MailMessage)
            ->subject($subject)
            ->view('emails.pembayaran-verified', [
                'pendaftar' => $this->pendaftar,
                'status' => $this->status
            ]);
    }
}
