<?php

namespace App\Providers;

use DBarbieri\Aws\S3;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(S3::class, function ($app) {

            $s3 = new S3(env('AWS_ACCESS_KEY_ID'), env('AWS_SECRET_ACCESS_KEY'), env('AWS_DEFAULT_REGION'), env('AWS_BUCKET'));

            return $s3;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
