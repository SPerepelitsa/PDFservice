<?php

namespace App\Providers;

use App\Services\PdfFileService;
use Illuminate\Support\ServiceProvider;

class PdfServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('serviceForPdfFiles', 'App\Services\PdfFileService');
        $this->app->singleton('App\Services\ChallengeService', function() {
            return new PdfFileService;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
