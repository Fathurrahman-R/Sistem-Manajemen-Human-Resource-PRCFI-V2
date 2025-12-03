<?php

namespace App\Models\Master;

use App\Enum\Master\StatusKerja;
use App\Models\Cuti;
use App\Models\User;
use App\Policies\KaryawanPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

#[UsePolicy(KaryawanPolicy::class)]
class Karyawan extends Model
{
    /** @use HasFactory<\Database\Factories\Master\KaryawanFactory> */
    use HasFactory;

    protected $table = 'm_karyawan';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'nama_lengkap',
        'posisi',
        'tempat_lahir',
        'tanggal_lahir',
        'email',
        'jenis_kelamin',
        'riwayat_pendidikan',
        'institusi_pendidikan',
        'english_skill',
        'pengalaman_kerja',
        'tanggal_bergabung',
        'tanggal_expired',
        'masa_kerja',
        'status',
        'cv',
        'ktp',
        'kk',
        'npwp'
    ];

    protected function casts(): array
    {
        return [
            'nama_lengkap'=>'string',
            'posisi'=>'string',
            'tempat_lahir'=>'string',
            'tanggal_lahir'=>'date',
            'email'=>'string',
            'jenis_kelamin'=>'string',
            'riwayat_pendidikan'=>'string',
            'institusi_pendidikan'=>'string',
            'english_skill'=>'string',
            'pengalaman_kerja'=>'int',
            'tanggal_bergabung'=>'date',
            'tanggal_expired'=>'date',
            'masa_kerja'=>'int',
            'status'=>StatusKerja::class,
            'cv'=>'string',
            'ktp'=>'string',
            'kk'=>'string',
            'npwp'=>'string',
        ];
    }
//    protected static function booted(): void
//    {
//        static::created(function (Karyawan $karyawan) {
//            DB::afterCommit(function () use ($karyawan) {
//                User::firstOrCreate(
//                    ['email' => $karyawan->email],
//                    [
//                        'name' => $karyawan->nama_lengkap,
//                        'password' => Hash::make('password'),
//                        'email_verified_at' => now(),
//                    ]
//                );
//            });
//        });
//
//        static::updated(function (Karyawan $karyawan) {
//            if ($karyawan->isDirty('email') || $karyawan->isDirty('nama_lengkap')) {
//                $user = User::where('email', $karyawan->getOriginal('email'))->first();
//                if ($user) {
//                    $user->update([
//                        'name' => $karyawan->nama_lengkap,
//                        'email' => $karyawan->email,
//                    ]);
//                }
//            }
//        });
//    }
    public function user()
    {
        return $this->hasOne(User::class,'email','email');
    }
    public function program(): BelongsToMany
    {
        return $this->belongsToMany(Program::class);
    }
    public function cuti(): HasMany
    {
        return $this->hasMany(Cuti::class, 'karyawan_id', 'id');
    }
}
