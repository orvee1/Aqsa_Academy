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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->longText('body')->nullable();
            $table->string('file_path')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->boolean('is_published')->default(true);
            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_pinned')->default(false);

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // ✅ short index names (MySQL safe)
            $table->index(['is_published', 'is_hidden', 'published_at'], 'notices_pub_idx');
            $table->index('expires_at', 'notices_exp_idx');
            $table->index('created_by', 'notices_cb_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
