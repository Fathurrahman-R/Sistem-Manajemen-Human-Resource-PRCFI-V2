<?php

namespace App\Models;

use App\Enum\Permission;
use App\Enum\Timesheet\StatusPersetujuan;
use App\Models\Master\Karyawan;
use App\Policies\TimesheetPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(TimesheetPolicy::class)]
class Timesheet extends Model
{
    /** @use HasFactory<\Database\Factories\TimesheetFactory> */
    use HasFactory;

    protected $table = 'd_timesheet';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'karyawan_id',
        'signature_karyawan',
        'tanggal',
        'status',
        'signature_direktur',
        'path_kehadiran',
        'path_aktivitas'
    ];

    protected function casts(): array
    {
        return [
            'karyawan_id' => 'int',
            'signature_karyawan' => 'string',
            'tanggal' => 'date',
            'status' => StatusPersetujuan::class,
            'signature_direktur' => 'string',
            'path_kehadiran' => 'string',
            'path_aktivitas' => 'string',
        ];
    }
    public function reviewed(User $user):bool
    {
        if ($user->hasPermissionTo(Permission::DIRECT_MANAGE_TIMESHEET))
        {
            return $this->update([
                'status' => StatusPersetujuan::Dilihat
            ]);
        }
        return false;
    }
    public function directTo(User $user):bool
    {
        if ($user->hasPermissionTo(Permission::DIRECT_MANAGE_TIMESHEET))
        {
            return $this->update([
                'status' => StatusPersetujuan::Diteruskan
            ]);
        }
        return false;
    }
    public function approved(User $user):bool
    {
        if ($user->hasPermissionTo(Permission::APPROVE_MANAGE_TIMESHEET))
        {
            return $this->update([
                'status' => StatusPersetujuan::Approved
            ]);
        }
        return false;
    }
    public function rejected(User $user):bool
    {
        if ($user->hasPermissionTo(Permission::REJECT_MANAGE_TIMESHEET))
        {
            return $this->update([
                'status' => StatusPersetujuan::Rejected
            ]);
        }
        return false;
    }
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
    public function isi_timesheet(): HasMany
    {
        return $this->hasMany(IsiTimesheet::class, 'timesheet_id', 'id');
    }
    public function cuti(): BelongsToMany
    {
        return $this->belongsToMany(Cuti::class);
    }
}
