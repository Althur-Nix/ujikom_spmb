<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BerkasRevisionNotification extends Notification
{
    use Queueable;

    protected $pendaftar;
    protected $berkasYangDitolak;

    public function __construct($pendaftar, $berkasYangDitolak)
    {
        $this->pendaftar = $pendaftar;
        $this->berkasYangDitolak = $berkasYangDitolak;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Revisi Berkas Diperlukan - SMK Bakti Nusantara 666')
            ->view('emails.berkas-revision', [
                'pendaftar' => $this->pendaftar,
                'berkasYangDitolak' => $this->berkasYangDitolak
            ]);
    }
}
