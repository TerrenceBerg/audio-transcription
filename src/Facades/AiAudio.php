<?php

namespace TerrenceChristopher\AudioTranscription\Facades;

use Illuminate\Support\Facades\Facade;
use Transcribe\AiAudio\AudioTranscriptionService;

/**
 * @method static string transcribe(string $audioFilePath)
 * @method static array identifySpeaker(string $audioContent)
 *
 * @see \Transcribe\AiAudio\AudioTranscriptionService
 */
class AiAudio extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AudioTranscriptionService::class;
    }
}
