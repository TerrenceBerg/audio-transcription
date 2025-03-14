<?php

namespace YourVendor\AudioTranscription\Facades;

use Illuminate\Support\Facades\Facade;

class AudioTranscriptionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'audio-transcription'; // Must match the binding in the service provider.
    }
}
