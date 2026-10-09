<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diary_id')->constrained('diaries')->cascadeOnDelete();
            $table->integer('page_number');
            $table->string('song_title')->nullable();
            $table->string('audio_file')->nullable();
            $table->enum('layout_type', ['full_media', 'photo_album_4', 'text_only'])->default('text_only');
            $table->string('note_title')->nullable();
            $table->text('note_content')->nullable();
            $table->timestamps();

            $table->unique(['diary_id', 'page_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
