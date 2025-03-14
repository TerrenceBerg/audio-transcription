<?php

namespace YourVendor\AudioTranscription\Providers;

use Illuminate\Support\ServiceProvider;

class AudioTranscriptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot()
    {
        // Publish configuration file.
        $this->publishes([
            __DIR__.'/../../config/audio-transcription.php' => config_path('audio-transcription.php'),
        ], 'config');

        // Optionally load routes if defined.
        if (file_exists(__DIR__.'/../../routes/web.php')) {
            $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        }

        // You can also load migrations, views, etc.
        // $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Merge package configuration.
        $this->mergeConfigFrom(
            __DIR__.'/../../config/audio-transcription.php', 'audio-transcription'
        );

        // Register a singleton instance for your package.
        $this->app->singleton('audio-transcription', function ($app) {
            return new \YourVendor\AudioTranscription\AudioTranscription;
        });
    }
}
