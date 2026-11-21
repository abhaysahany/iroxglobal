<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:rank-gen')->everyFifteenMinutes();
Schedule::command('app:rank-income-dis')->daily();
Schedule::command('app:renew-date')->daily();
Schedule::command('app:update-weekly-count')->daily();
Schedule::command('app:pool-income-dis')->monthly();
Schedule::command('app:level-income-dis')->everyFifteenMinutes();
