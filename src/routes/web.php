<?php

use Illuminate\Support\Facades\Route;
use TerrenceChristopher\AudioTranscription\Http\Controllers\AudioTranscriptionController;

Route::get('/transcribe', [AudioTranscriptionController::class, 'uploadForm'])->name('transcribe.form');
Route::post('/transcribe', [AudioTranscriptionController::class, 'upload'])->name('transcribe.upload');
Route::get('/transcribe/list', [AudioTranscriptionController::class, 'list'])->name('transcribe.list');
