<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Check expired payments every 1 minutes
Schedule::command('payments:check-expired')
    ->everyMinute()
    ->description('Auto-cancel expired payment transactions');
    
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Temporary untuk testing - jadi setiap 1 menit
Schedule::command('promotions:create-expiry-promotions')
    ->everyMinute() // ← Ubah dari daily()
    ->description('Auto-create promotions for products near expiry');

Schedule::command('products:check-expired')
    ->everyMinute() // ← Ubah dari daily()
    ->description('Auto-reduce stock for expired products');

Schedule::command('promotions:disable-expired-products')
    ->everyMinute() // ← Ubah dari daily()
    ->description('turn off all promotions automatically');