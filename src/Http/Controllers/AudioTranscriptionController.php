<?php

namespace App\Http\Controllers;

use App\Models\AudioTranscription;
use App\Jobs\TranscribeAudioJob;
use Illuminate\Http\Request;
use Transcribe\AiAudio\Facades\AiAudio;

class AudioTranscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'audio_url' => 'required|url'
        ]);

        $transcription = AudioTranscription::create($validated);

        // Transcribe the audio (you might want to move this to a job)
        $transcribedText = AiAudio::transcribe($validated['audio_url']);
        $transcription->update(['audio_transcription' => $transcribedText]);

        return response()->json($transcription, 201);
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
        $transcriptions = AudioTranscription::latest()->paginate(10); // Changed from take(5)
        return view('transcribe.upload', compact('transcriptions'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'audio_file' => 'required|file|mimes:mp3,wav,m4a,mpga|max:25000'
        ]);

        $file = $request->file('audio_file');
        $path = $file->store('audio_files', 'public');
        $url = asset('storage/' . $path);

        $transcription = AudioTranscription::create([
            'audio_url' => $url,
        ]);

        TranscribeAudioJob::dispatch($transcription);

        return redirect()->back()->with('success', 'Audio uploaded and queued for transcription.');
    }

    public function list()
    {
        $transcriptions = AudioTranscription::latest()
            ->withTrashed()
            ->paginate(15);
        return view('transcribe.list', compact('transcriptions'));
    }
}
