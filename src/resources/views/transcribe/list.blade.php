@extends('audio-transcription::layouts.app')

@section('title', 'Transcription List')

@section('content')
<div class="card glowing-border shadow-lg">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h1 class="h3 mb-0">Audio Transcriptions</h1>
        <a href="{{ route('transcribe.form') }}" class="btn btn-primary">Upload New</a>
    </div>

    <div class="card-body p-0">
{{--        @foreach($transcriptions as $transcription)--}}
            <!-- Livewire Component -->
            <livewire:transcriptions-list />
{{--        @endforeach--}}
    </div>

{{--    <div class="card-footer bg-white">--}}
{{--        <div class="d-flex justify-content-between align-items-center">--}}
{{--            <div class="small text-muted">--}}
{{--                Showing {{ $transcriptions->firstItem() ?? 0 }} to {{ $transcriptions->lastItem() ?? 0 }}--}}
{{--                of {{ $transcriptions->total() ?? 0 }} transcriptions--}}
{{--            </div>--}}
{{--            <div>--}}
{{--                {!! $transcriptions->withQueryString()->links('pagination::bootstrap-5') !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
@endsection
