<?php

namespace App\Notifications;

use App\Models\Pendaftar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pendaftar;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Pendaftar $pendaftar)
    {
        $this->pendaftar = $pendaftar;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Status Verifikasi Pendaftaran - SMK Bakti Nusantara 666')
            ->view('emails.verification-status', [
                'pendaftar' => $this->pendaftar
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'pendaftar_id' => $this->pendaftar->id,
            'status' => $this->pendaftar->status,
            'catatan' => $this->pendaftar->catatan_verifikasi
        ];
    }
}