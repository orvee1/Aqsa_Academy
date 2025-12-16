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
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->string('disk')->default('public')->index();
            $table->text('path');
            $table->string('mime')->nullable()->index();
            $table->unsignedBigInteger('size')->nullable();

            $table->string('uploaded_by');

            $table->timestamps();
            $table->index(['disk','uploaded_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
