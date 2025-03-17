<?php

namespace TerrenceChristopher\AudioTranscription\Http\Controllers;

use Livewire\Livewire;
use TerrenceChristopher\AudioTranscription\Models\AudioTranscription;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TerrenceChristopher\AudioTranscription\Services\OpenAITranscriptionService;
use Exception;

class AudioTranscriptionController extends Controller
{
    protected OpenAITranscriptionService $transcriptionService;

    public function __construct(OpenAITranscriptionService $transcriptionService)
    {
        $this->transcriptionService = $transcriptionService;
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'audio_url' => 'required|url'
        ]);

        $transcription = AudioTranscription::create($validated);

        $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,ogg,m4a,flac|max:10240',
        ]);

        try {
            $transcription = $this->transcriptionService->transcribe($request->file('audio'));
            dd($transcription);
            return response()->json(['transcription' => $transcription]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);

        }
//        return response()->json($transcription, 201);
    }

    public function index()
    {
        return AudioTranscription::paginate();
    }

    public function show(AudioTranscription $audioTranscription)
    {
        return response()->json($audioTranscription);
    }

    public function destroy(AudioTranscription $audioTranscription)
    {
        $audioTranscription->delete();
        return response()->json(null, 204);
    }

    public function uploadForm()
    {
        return view('audio-transcription::transcribe.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'audio_file' => 'required|file|mimes:mp3,wav,m4a,mpga|max:25000'
        ]);

        $file = $request->file('audio_file');
        $path = $file->store('audio_files', 'public');
        $url = asset('storage/' . $path);

        // Create a transcription entry with PENDING status
        $transcription = AudioTranscription::create([
            'audio_url' => $url,
            'status' => AudioTranscription::STATUS_PENDING
        ]);

        // Broadcast new transcription event
//        Livewire::dispatch('newTranscription');

        try {
            // Call the transcription service
            $transcribedText = $this->transcriptionService->transcribe($file);

            // Update the database with transcribed text
            $transcription->update([
                'audio_transcription' => $transcribedText,
                'status' => AudioTranscription::STATUS_COMPLETED
            ]);

            // Broadcast updated transcription event
//            Livewire::dispatch('transcriptionUpdated');


        } catch (Exception $e) {
            // Update status to failed
            $transcription->update(['status' => AudioTranscription::STATUS_FAILED]);

            // Broadcast update event
//            Livewire::dispatch('transcriptionUpdated');

            return response()->json([
                'error' => 'Transcription failed',
                'message' => $e->getMessage()
            ], 400);
        }
        return redirect()->route('transcribe.form');
    }

    public function list()
    {
        return view('audio-transcription::transcribe.list');
    }
}
