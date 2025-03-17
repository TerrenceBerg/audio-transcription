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
    public function transcribe(UploadedFile $file): string
    {
        if (!$file->isValid() || !in_array($file->getMimeType(), ['audio/mpeg', 'audio/wav', 'audio/mp4', 'audio/x-m4a'])) {
            return '';
        }

        $path = $file->storeAs('temp', $file->getClientOriginalName(), 'local');
        $fullPath = Storage::disk('local')->path($path);

        try {

//            Livewire::dispatch('transcriptionStatus', 'Processing...');

            $response = $this->client->audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($fullPath, 'r'),
            ]);

            // Emit event for "Completed"
//            Livewire::dispatch('transcriptionStatus', 'Completed');

            Storage::disk('local')->delete($path);

            return $response->text ?? '';
        } catch (\Exception $e) {
            Log::error('Transcription failed: ' . $e->getMessage());
//            Livewire::dispatch('transcriptionStatus', 'Failed');
            return '';
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
