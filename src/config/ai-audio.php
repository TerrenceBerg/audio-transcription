<?php

return [
    'openai_api_key' => env('OPENAI_API_KEY'),
    
    'models' => [
        'transcription' => 'whisper-1',
    ],
    
    'audio' => [
        'supported_formats' => ['mp3', 'wav', 'm4a', 'mpga'],
        'max_file_size' => 25 * 1024 * 1024, // 25MB
    ]
];
