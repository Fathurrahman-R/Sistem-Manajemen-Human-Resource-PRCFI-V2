<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule cleanup dokumen cuti yang expired setiap hari jam 01:00
Schedule::command('cuti:cleanup-documents')->daily()->at('01:00');

