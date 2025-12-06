<?php

namespace App\Models;

use App\Enum\Timesheet\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IsiTimesheet extends Model
{
    /** @use HasFactory<\Database\Factories\IsiTimesheetFactory> */
    use HasFactory;

    protected $table = 'd_isi_timesheet';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        'timesheet_id',
        'tanggal',
        'jam_bekerja',
        'location',
        'place',
        'work_done',
    ];

    protected function casts(): array
    {
        return [
            'timesheet_id' => 'int',
            'tanggal' => 'date',
            'jam_bekerja' => 'int',
            'location' => Location::class,
            'place' => 'string',
            'work_done' => 'string',
        ];
    }
    public function timesheet(): BelongsToMany
    {
        return $this->belongsToMany(Timesheet::class);
    }
}
