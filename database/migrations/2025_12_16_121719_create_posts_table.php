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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->string('post_category_id');

            $table->string('title');
            $table->string('slug');

            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();

            $table->text('featured_image_path')->nullable();

            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamp('published_at')->nullable();

            $table->string('created_by');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug', 'deleted_at']);
            $table->index(['post_category_id', 'status', 'published_at']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
