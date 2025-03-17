<?php

namespace TerrenceChristopher\AudioTranscription\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use TerrenceChristopher\AudioTranscription\Models\AudioTranscription;
use Illuminate\Support\Facades\Storage;
use OpenAI\Client;

class UploadAudio extends Component
{
    use WithFileUploads;

    public $file;

    public function upload()
    {
        $this->validate([
            'audio_file' => 'required|file|mimes:mp3,wav,m4a,mpga|max:25000'
        ]);

        // Store the uploaded file
        $path = $this->file->store('audio_files', 'public');
        $url = Storage::url($path);

        // Create transcription entry
        $transcription = AudioTranscription::create([
            'audio_url' => $url,
            'status' => 'processing'
        ]);

        // Dispatch an event to update Livewire list
        $this->dispatch('newTranscription');

        // Process transcription
        $this->transcribeAudio($transcription, $path);
    }

    private function transcribeAudio($transcription, $path)
    {
        try {
            $client = app(Client::class);
            $response = $client->audio()->transcribe([
                'file' => Storage::path($path),
                'model' => 'whisper-1',
            ]);

            $transcription->update([
                'audio_transcription' => $response['text'] ?? 'Failed to transcribe',
                'status' => 'completed',
            ]);

        } catch (\Exception $e) {
            $transcription->update([
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);
        }

        // Refresh Livewire list
        $this->dispatch('transcriptionUpdated');
    }

    public function render()
    {
        return view('audio-transcription::livewire.upload-audio');
    }
}
