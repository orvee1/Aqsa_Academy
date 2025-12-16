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
        Schema::create('image_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('image_albums')->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->text('image_path');
            $table->string('caption')->nullable();

            $table->integer('position')->default(0)->index();
            $table->boolean('status')->default(true)->index();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['album_id', 'status', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_items');
    }
};
