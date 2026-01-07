<?php

namespace App\Notifications\Cuti;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class DirectedCuti extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $karyawan,
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
        $posisi = DB::table('m_karyawan')->where('id', $notifiable->karyawan_id)->value('posisi');

        return (new MailMessage)
            ->greeting("Yth. $posisi PRCFI Pontianak")
            ->line("Pengajuan cuti oleh $this->karyawan baru saja diteruskan dan sedang menunggu persetujuan anda, periksa sekarang dengan menekan tombol dibawah. Terima Kasih")
            ->action('Periksa Cuti', url('/dashboard/cutis'));
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
