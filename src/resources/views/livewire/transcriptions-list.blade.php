<div>
    <div class="card-body p-0">
        @foreach($transcriptions as $transcription)
            <div class="card m-2 border-dark">
                <div class="border-bottom p-3">
                    <div class="row g-3">
                        <div class="col-12 col-md-1">
                            <div class="small text-muted">ID: {{ $transcription->id }}<br>
                                <a href="{{ $transcription->audio_url }}"
                                   class="btn btn-sm btn-outline-primary"
                                   target="_blank">
                                    <i class="bi bi-play-circle me-1"></i> Listen
                                </a>
                            </div>
                        </div>

                        <div class="col-12 col-md-9">
                            <div class="text-break small">
                                <strong>Transcription:</strong><br>
                                {{ $transcription->audio_transcription ?? 'Pending...' }}
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="d-flex flex-column">
                                @if($transcription->deleted_at)
                                    <span class="badge bg-danger">Deleted</span>
                                @elseif($transcription->status === 'completed')
                                    <span class="badge bg-success">Complete</span>
                                @elseif($transcription->status === 'processing')
                                    <span class="badge bg-warning text-dark">Processing</span>
                                @elseif($transcription->status === 'failed')
                                    <span class="badge bg-danger"
                                          data-bs-toggle="tooltip"
                                          title="{{ $transcription->error }}">Failed</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                                <small class="text-muted">
                                    Created: {{ $transcription->created_at->format('m-d-Y H:i') }}
                                </small>
                                <small class="text-muted">
                                    Updated: {{ $transcription->updated_at->format('m-d-Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Showing {{ $transcriptions->firstItem() ?? 0 }} to {{ $transcriptions->lastItem() ?? 0 }}
                of {{ $transcriptions->total() ?? 0 }} transcriptions
            </div>
            <div>
                {!! $transcriptions->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>
