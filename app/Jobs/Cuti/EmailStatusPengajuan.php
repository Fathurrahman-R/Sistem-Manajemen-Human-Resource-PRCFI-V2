<?php

namespace App\Jobs\Cuti;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EmailStatusPengajuan implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $id,
        public ?string $status = "berhasil diajukan!",
        public ?string $message = "Tunggu email selanjutnya untuk pembaruan status pengajuan",
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::query()->where('users.karyawan_id', $this->id)->first();
        $user->notify(new \App\Notifications\Cuti\EmailStatusPengajuan($this->status, $this->message));
    }
}
