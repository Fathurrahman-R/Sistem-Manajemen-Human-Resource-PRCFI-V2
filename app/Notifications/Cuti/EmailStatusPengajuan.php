<?php

namespace App\Notifications\Cuti;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailStatusPengajuan extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ?string $status,
        public ?string $message,
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Status Pengajuan')
            ->greeting("Hai, $notifiable->name")
            ->line("Cuti kamu $this->status $this->message.")
            ->line("(pengajuan cuti tidak bisa diubah maupun dihapus setelah ditandai dilihat oleh yang berwenang)")
            ->line('Terima Kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
