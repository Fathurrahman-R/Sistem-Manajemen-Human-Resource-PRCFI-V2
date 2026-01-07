<?php

namespace App\Jobs\Cuti;

use App\Enum\Permission;
use App\Models\Master\Karyawan;
use App\Models\User;
use App\Notifications\Cuti\DirectedCuti;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class EmailDiteruskanKeDirektur implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $karyawanId
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::permission([
            Permission::APPROVE_MANAGE_CUTI,
            Permission::REJECT_MANAGE_CUTI,
        ])->get();
        $karyawan = Karyawan::query()->select(['id','nama_lengkap'])
            ->where('id', $this->karyawanId)
            ->first();

        Notification::send($users, new DirectedCuti($karyawan['nama_lengkap']));
    }
}
