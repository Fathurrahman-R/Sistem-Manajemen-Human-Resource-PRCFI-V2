<?php

namespace App\Models;

use App\Enum\Cuti\StatusPengajuan;
use App\Enum\Permission;
use App\Enum\Role;
use App\Models\Master\Karyawan;
use App\Policies\CutiPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Date;

#[UsePolicy(CutiPolicy::class)]
class Cuti extends Model
{
    /** @use HasFactory<\Database\Factories\CutiFactory> */
    use HasFactory;

    protected $table = 'd_cuti';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'karyawan_id',
        'tempat_dibuat',
        'tanggal_dibuat',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'signature_karyawan',
        'lampiran',
        'status',
        'approved_at',
        'approved_date',
        'approved_by',
        'signature_direktur',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'karyawan_id' => 'int',
            'tempat_dibuat' => 'string',
            'tanggal_dibuat' => 'date',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'keterangan' => 'string',
            'lampiran' => 'array',
            'status' => StatusPengajuan::class,
            'approved_at' => 'string',
            'approved_date' => 'date',
            'approved_by' => 'string',
            'file_path' => 'string',
        ];
    }
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    public function rechieved(User $user):bool
    {
        if($user->hasPermissionTo(Permission::DIRECT_MANAGE_CUTI))
        {
            return $this->update([
                'status'=>StatusPengajuan::MenungguHR->value
            ]);
        }
        return false;
    }
    public function directTo(User $user):bool
    {
        if ($user->hasPermissionTo(Permission::DIRECT_MANAGE_CUTI))
        {
            return $this->update([
                'status'=>StatusPengajuan::MenungguDirektur->value
            ]);
        }
        return false;
    }
    public function approve(string $at, $date, User $user):bool
    {
        if($user->hasPermissionTo(Permission::APPROVE_MANAGE_CUTI))
        {
            return $this->update([
                'status' => StatusPengajuan::Disetujui->value,
                'approved_at' => $at,
                'approved_date' => $date,
                'approved_by' => $user->name
            ]);
        }
        return false;
    }
    public function reject(User $user):bool
    {
        if($user->hasPermissionTo(Permission::REJECT_MANAGE_CUTI))
        {
            return $this->update([
                'status' => StatusPengajuan::Ditolak->value
            ]);
        }
        return false;
    }

    /**
     * Get jumlah lampiran
     */
    public function getJumlahLampiranAttribute(): int
    {
        return is_array($this->lampiran) ? count($this->lampiran) : 0;
    }

    /**
     * Check apakah memiliki lampiran
     */
    public function hasLampiran(): bool
    {
        return $this->jumlah_lampiran > 0;
    }
}
