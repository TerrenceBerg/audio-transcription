<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audio_transcriptions', function (Blueprint $table) {
            $table->id();
            $table->string('audio_url');
            $table->text('audio_transcription')->nullable();
            $table->string('status')->default('pending'); // Add this line
            $table->text('error')->nullable(); // Add this line
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audio_transcriptions');
    }
};
