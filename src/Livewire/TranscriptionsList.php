<?php

namespace TerrenceChristopher\AudioTranscription\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use TerrenceChristopher\AudioTranscription\Models\AudioTranscription;

class TranscriptionsList extends Component
{
    use WithPagination;

    protected $listeners = [
        'transcriptionUpdated' => '$refresh',
        'newTranscription' => '$refresh',
        'transcriptionFailed' => '$refresh'
    ];
    public function render()
    {
        return view('audio-transcription::livewire.transcriptions-list', [
            'transcriptions' => AudioTranscription::latest()->paginate(10)
        ]);
    }
}
