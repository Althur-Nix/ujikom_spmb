<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BerkasVerifiedNotification extends Notification
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
        $subject = $this->status === 'ADM_PASS' 
            ? 'Berkas Administrasi Diterima - SMK Bakti Nusantara 666'
            : 'Berkas Administrasi Ditolak - SMK Bakti Nusantara 666';
            
        return (new MailMessage)
            ->subject($subject)
            ->view('emails.berkas-verified', [
                'pendaftar' => $this->pendaftar,
                'status' => $this->status
            ]);
    }
}
