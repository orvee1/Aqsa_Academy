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
        Schema::create('video_items', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('youtube_url');
            $table->text('thumbnail_path')->nullable();

            $table->integer('position')->default(0)->index();
            $table->boolean('status')->default(true)->index();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_items');
    }
};
