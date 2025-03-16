<?php

namespace App\Jobs;

use App\Models\AudioTranscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Transcribe\AiAudio\Facades\AiAudio;

class TranscribeAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 600; // 10 minutes

    public function __construct(
        protected AudioTranscription $transcription
    ) {}

    public function handle()
    {
        try {
            Log::info('Starting transcription with config', [
                'has_api_key' => !empty(config('ai-audio.openai_api_key')),
                'key_length' => strlen(config('ai-audio.openai_api_key') ?? '')
            ]);

            if (empty(config('ai-audio.openai_api_key'))) {
                throw new \RuntimeException('OpenAI API key is not configured');
            }

            Log::info('Starting transcription job', ['id' => $this->transcription->id]);
            $this->transcription->update(['status' => AudioTranscription::STATUS_PROCESSING]);
            
            // Get file path correctly using parse_url and realpath
            $urlPath = parse_url($this->transcription->audio_url, PHP_URL_PATH);
            $relativePath = str_replace('/storage/', '', $urlPath);
            $fullPath = realpath(storage_path('app/public/' . $relativePath));
            
            if (!$fullPath) {
                throw new \RuntimeException("Could not resolve real path for audio file");
            }
            
            Log::info('File path resolved', [
                'original_url' => $this->transcription->audio_url,
                'resolved_path' => $fullPath
            ]);

            if (!file_exists($fullPath)) {
                throw new \RuntimeException("Audio file not found at path: {$fullPath}");
            }

            if (!is_readable($fullPath)) {
                throw new \RuntimeException("Audio file is not readable at path: {$fullPath}");
            }

            $filesize = filesize($fullPath);
            Log::info('Audio file details', [
                'exists' => true,
                'readable' => true,
                'size' => $filesize,
                'mime' => mime_content_type($fullPath)
            ]);

            Log::info('About to call OpenAI transcribe', [
                'file_exists' => file_exists($fullPath),
                'file_size' => filesize($fullPath),
                'mime_type' => mime_content_type($fullPath)
            ]);

            $transcribedText = AiAudio::transcribe($fullPath);
            
            Log::info('Transcription response', [
                'text_length' => strlen($transcribedText),
                'text_preview' => substr($transcribedText, 0, 100)
            ]);

            if (empty($transcribedText)) {
                throw new \RuntimeException('Transcription resulted in empty text');
            }

            $this->transcription->update([
                'audio_transcription' => $transcribedText,
                'status' => AudioTranscription::STATUS_COMPLETED
            ]);
            
            Log::info('Transcription completed successfully', ['id' => $this->transcription->id]);
        } catch (\Exception $e) {
            Log::error('Transcription failed', [
                'id' => $this->transcription->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->transcription->update([
                'status' => AudioTranscription::STATUS_FAILED,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Transcription job failed finally', [
            'id' => $this->transcription->id,
            'error' => $exception->getMessage()
        ]);
    }
}
