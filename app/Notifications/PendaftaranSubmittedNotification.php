<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendaftaranSubmittedNotification extends Notification
{
    use Queueable;

    protected $pendaftar;

    public function __construct($pendaftar)
    {
        $this->pendaftar = $pendaftar;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🎉 Pendaftaran Berhasil - SMK Bakti Nusantara 666')
            ->view('emails.pendaftaran-berhasil', ['pendaftar' => $this->pendaftar]);
    }
}
