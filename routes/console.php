<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('ChurchSystem API inspired and ready.');
})->purpose('Display a ChurchSystem startup message');
