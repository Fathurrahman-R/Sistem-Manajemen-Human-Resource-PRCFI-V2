<?php

namespace App\Observers;

use App\Enum\Timesheet\DefaultPlacePerformance;
use App\Enum\Timesheet\Location;
use App\Models\IsiTimesheet;
use App\Models\Timesheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TimesheetObserver
{
    public function created(Timesheet $timesheet): void
    {
        // Pastikan tanggal adalah instance Carbon
        $baseDate = $timesheet->tanggal instanceof Carbon
            ? $timesheet->tanggal->copy()
            : Carbon::parse($timesheet->tanggal);

        // Ambil awal dan akhir bulan dari tanggal timesheet
        $startOfMonth = $baseDate->copy()->startOfMonth();
        $endOfMonth = $baseDate->copy()->endOfMonth();

        DB::afterCommit(function () use ($timesheet, $startOfMonth, $endOfMonth) {
            $cursor = $startOfMonth->copy();

            while ($cursor->lte($endOfMonth)) {
                if ($cursor->isSaturday() || $cursor->isSunday()) {
                    IsiTimesheet::create([
                        'timesheet_id' => $timesheet->id,
                        'tanggal' => $cursor->toDateString(),
                        'jam_bekerja' => 1,
                        'location' => Location::Weekends,
                        'place' => DefaultPlacePerformance::Pontianak,
                        'work_done' => Location::Weekends,
                    ]);
                }
                $cursor->addDay();
            }
        });
    }
}
