<form wire:submit.prevent="upload">
    <div class="mb-3">
        <label class="form-label">Upload Audio</label>
        <input type="file" class="form-control" wire:model="file">
    </div>

    @error('file') <span class="text-danger">{{ $message }}</span> @enderror

    <button type="submit" class="btn btn-primary">Upload & Transcribe</button>
</form>
