<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('clubops:export-players', function () {
    $this->info('Exporting players...');
})->describe('Export player list as CSV');
