@extends('audio-transcription::layouts.app')

@section('title', 'AI Audio Transcription')

@section('content')
<div class="card glowing-border shadow-lg">
    <div class="col-md-12">
        <div class="card glowing-border shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="card-title h3 mb-0">Audio Transcription</h1>
                    <a href="{{ route('transcribe.list') }}" class="btn btn-outline-primary">
                        View All Transcriptions
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('transcribe.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Upload Audio File</label>
                        <input type="file" name="audio_file" accept="audio/*" class="form-control">
{{--                        @error('audio_file')--}}
{{--                            <div class="text-danger mt-1 small">{{ $message }}</div>--}}
{{--                        @enderror--}}
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Upload and Transcribe
                    </button>
                </form>

                <div class="mt-5">
                    <h2 class="h4 mb-4">Recent Transcriptions</h2>
                        <!-- Livewire Component -->
                        @livewire('transcriptions-list')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
