<?php


namespace TerrenceChristopher\AudioTranscription\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use OpenAI\Client as OpenAIClient;
use Exception;

class OpenAITranscriptionService
{
    protected OpenAIClient $client;

    public function __construct(OpenAIClient $client)
    {
        $this->client = $client;
    }

    /**
     * Transcribes an audio file using OpenAI.
     *
     * @param UploadedFile $file
     * @return string
     * @throws Exception
     */
    public function transcribe(UploadedFile $file): array
    {
        // Check if the uploaded file is valid and is an accepted audio format
        if (!$file->isValid() || !in_array($file->getMimeType(), ['audio/mpeg', 'audio/wav', 'audio/mp4', 'audio/x-m4a'])) {
            return [];
        }

        // Store the uploaded file temporarily
        $path = $file->storeAs('transcript_audio_files', $file->getClientOriginalName(), 'local');
        $fullPath = Storage::disk('local')->path($path);

        try {
            // Send the file to the transcription API (e.g., OpenAI Whisper)
            $response = $this->client->audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($fullPath, 'r'),
            ]);

            // Return transcription text and file URL for the list view
            return [
                'text' => $response->text ?? '',
                'file_url' => Storage::disk('local')->url($path), // Generate the URL to listen to the file
            ];
        } catch (\Exception $e) {
            // Log the error if transcription fails
            Log::error('Transcription failed: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Check if the file is a valid audio file.
     *
     * @param UploadedFile $file
     * @return bool
     */
    private function isValidAudioFile(UploadedFile $file): bool
    {
        $allowedExtensions = ['mp3', 'wav', 'ogg', 'm4a', 'flac'];
        return in_array($file->getClientOriginalExtension(), $allowedExtensions);
    }
}
