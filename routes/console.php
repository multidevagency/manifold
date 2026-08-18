<?php

use Illuminate\Foundation\DevCommands;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// `composer dev` boots the API stack; the Nuxt admin belongs in the same view.
DevCommands::register('pnpm --dir admin dev', 'admin')->purple();
