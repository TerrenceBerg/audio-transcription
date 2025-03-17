<div>
    <form wire:submit.prevent="upload">
        {{csrf_token()}}
        <div class="mb-3">
            <label class="form-label">Upload Audio</label>
            <input type="file" class="form-control" wire:model="file">
        </div>

        @error('file') <span class="text-danger">{{ $message }}</span> @enderror

        <button type="submit" class="btn btn-primary">
            Upload & Transcribe
        </button>
    </form>

    @if (session()->has('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif
</div>
