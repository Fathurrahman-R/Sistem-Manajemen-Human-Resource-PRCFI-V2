<?php

namespace App\Models\Master;

use App\Policies\ProgramPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(ProgramPolicy::class)]
class Program extends Model
{
    /** @use HasFactory<\Database\Factories\Master\ProgramFactory> */
    use HasFactory;

    protected $table = 'm_program';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'nama',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'nama'=>'string',
            'lokasi'=>'string',
            'tanggal_mulai'=>'date',
            'tanggal_selesai'=>'date',
        ];
    }
    public function karyawans(): BelongsToMany
    {
        return $this->belongsToMany(Karyawan::class);
    }
}
