<?php

namespace App\Providers;

use App\Services\PdfFileService;
use Illuminate\Support\ServiceProvider;
use Smalot\PdfParser\Parser;

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
        $this->app->singleton('App\Services\PdfFleService', function() {
            return new PdfFileService(new Parser);
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
