<?php

namespace TerrenceChristopher\AudioTranscription;

use TerrenceChristopher\AudioTranscription\Livewire\TranscriptionsList;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use OpenAI\Factory;
use TerrenceChristopher\AudioTranscription\Services\OpenAITranscriptionService;
use OpenAI\Client;

class AudioTranscriptionProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot()
    {
        // Publish configuration file.
        $this->publishes([
            __DIR__.'/config/ai-audio.php' => config_path('audio-transcription.php'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/public' => public_path('audio-transcription'),
        ], 'public');
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
        // ✅ Load Views (Ensure directory path is correct)
        $this->loadViewsFrom(__DIR__.'/resources/views', 'audio-transcription');

        // Merge package configuration.
        $this->mergeConfigFrom(
            __DIR__.'/config/ai-audio.php', 'audio-transcription'
        );
        // ✅ Load Routes (Remove runningInConsole check)
        if (! $this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        }

        // ✅ Load Controllers (Ensure correct path)
        $this->loadControllers();
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');


        // Correctly instantiate OpenAI Client using Factory
        $this->app->singleton(Client::class, function ($app) {
            return (new Factory())
                ->withApiKey(config('audio-transcription.openai_api_key')) // Get API key from config
                ->make();
        });
        Livewire::component('transcriptions-list', TranscriptionsList::class);
        Livewire::component('upload-audio', \TerrenceChristopher\AudioTranscription\Livewire\UploadAudio::class);

        // Bind Transcription Service
        $this->app->singleton(OpenAITranscriptionService::class, function ($app) {
            return new OpenAITranscriptionService($app->make(Client::class));
        });

    }
    protected function loadControllers()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php'); // Ensure that routes use the controller path
    }
}
