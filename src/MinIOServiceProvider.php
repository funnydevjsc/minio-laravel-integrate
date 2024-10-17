<?php

namespace FunnyDev\MinIO;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class MinIOServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/minio.php' => config_path('minio.php'),
        ], 'minio');

        try {
            if (!file_exists(config_path('minio.php'))) {
                $this->commands([
                    \Illuminate\Foundation\Console\VendorPublishCommand::class,
                ]);

                Artisan::call('vendor:publish', ['--provider' => 'FunnyDev\\MinIO\\MinIOServiceProvider', '--tag' => ['minio']]);
            }
        } catch (\Exception) {}
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/minio.php', 'minio'
        );
        $this->app->singleton(\FunnyDev\MinIO\MinIOSdk::class, function ($app) {
            $server = $app['config']['minio.api_key'];
            $accessKey = $app['config']['minio.access_key'];
            $secretKey = $app['config']['minio.secret_key'];
            return new \FunnyDev\MinIO\MinIOSdk($server, $cookie);
        });
    }
}
