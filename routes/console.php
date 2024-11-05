<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
    $this->Command('queue:process-fallback')->everyMinute();
})->purpose('Display an inspiring quote')->hourly();
